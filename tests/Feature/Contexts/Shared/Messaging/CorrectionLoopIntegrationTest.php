<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Shared\Messaging;

use App\Contexts\Adjudication\Application\Command\IssueDeterminationCommand;
use App\Contexts\Adjudication\Application\Service\AdjudicationService;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\ContestedOutcomeRef;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\ElectionId as AdjElectionId;
use App\Contexts\Adjudication\Domain\Determination\EvidenceEnvelopeRef;
use App\Contexts\Adjudication\Domain\Determination\EvidenceSet;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Jurisdiction;
use App\Contexts\Adjudication\Domain\Determination\Legitimacy;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use App\Contexts\Adjudication\Domain\Determination\TargetId;
use App\Contexts\Adjudication\Domain\Determination\TargetType;
use App\Contexts\Contestation\Domain\Challenge\Challenge;
use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\ChallengeState;
use App\Contexts\Contestation\Domain\Repository\ChallengeRepository;
use App\Contexts\Shared\Infrastructure\Inbox\RedriveParkedInboxEvents;
use App\Contexts\Shared\Infrastructure\Outbox\IntegrationEvent;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxEventProcessor;
use App\Models\Organisation;
use App\Services\TenantContext;
use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * PB-006 6B-2 — Blueprint §9 integration tests over the REAL path
 * (outbox → relay → IntegrationEventDispatcher → inbox → handler). No hand-stitched
 * delivery. Covers IT-1 (+IT-8 chain), IT-2, IT-3, IT-4; IT-5/IT-6/IT-7 are evidenced by
 * the existing relay/inbox/architecture suites (mapping recorded in the IDD/6C evidence).
 */
final class CorrectionLoopIntegrationTest extends TestCase
{
    private string $orgId;
    private string $electionId;
    private string $challengeId;

    protected function setUp(): void
    {
        parent::setUp();
        $org = Organisation::create([
            'name' => 'Loop org',
            'slug' => 'loop-'.Str::uuid(),
            'type' => 'tenant',
            'is_default' => false,
        ]);
        $this->orgId = (string) $org->id;
        TenantContext::set($this->orgId);

        $this->electionId = (string) Str::uuid();
        DB::table('elections')->insert([
            'id' => $this->electionId,
            'organisation_id' => $this->orgId,
            'name' => 'Legacy election',
            'slug' => 'legacy-'.$this->electionId,
            'type' => 'real',
            'status' => 'completed',
            'is_active' => true,
            'created_at' => '2026-07-10 09:00:00',
            'updated_at' => '2026-07-10 09:00:00',
        ]);

        $this->challengeId = (string) Str::uuid();
        $this->app->make(ChallengeRepository::class)
            ->save(Challenge::reconstitute(ChallengeId::fromString($this->challengeId), ChallengeState::Routed, null));
    }

    private function issue(DeterminationOutcome $outcome): string
    {
        return $this->app->make(AdjudicationService::class)->issueDetermination(
            new IssueDeterminationCommand(
                ChallengeRef::fromString($this->challengeId),
                $outcome,
                Legitimacy::Legitimate,
                Reason::fromString('Tally dispute.'),
                IssuedByAuthority::fromString('ARB'),
                Jurisdiction::fromString('National'),
                EvidenceEnvelopeRef::fromString('ev-1'),
                ContestedOutcomeRef::of(
                    AdjElectionId::fromString($this->electionId),
                    TargetType::ElectionResult,
                    TargetId::fromString('result-1'),
                ),
                EvidenceSet::fromRefs('ev-1'),
                new DateTimeImmutable('2026-07-10T10:00:00+00:00'),
            ),
        )->toString();
    }

    private function relay(): void
    {
        $this->app->make(OutboxEventProcessor::class)->handle();
    }

    private function outboxRow(string $eventType, string $aggregateId): ?object
    {
        return DB::table('outbox_events')
            ->where('organisation_id', $this->orgId)
            ->where('event_type', $eventType)
            ->where('aggregate_id', $aggregateId)
            ->first();
    }

    private function challengeState(): ChallengeState
    {
        $challenge = $this->app->make(ChallengeRepository::class)->find(ChallengeId::fromString($this->challengeId));
        $this->assertNotNull($challenge);

        return $challenge->state();
    }

    /** IT-1 + IT-8: full Upheld loop over two relay runs; ONE correlation, causation per hop. */
    public function test_it1_upheld_loop_resolves_the_challenge_with_one_correlation_across_all_rows(): void
    {
        $determinationId = $this->issue(DeterminationOutcome::Upheld);

        $this->relay();   // DeterminationIssued → Election corrects + Contestation adjudicates
        $this->relay();   // ElectionCorrectionApplied → Contestation resolves

        $issued = $this->outboxRow('DeterminationIssued', $determinationId);
        $correction = $this->outboxRow('ElectionCorrectionApplied', $this->electionId);
        $adjudicated = $this->outboxRow('ChallengeAdjudicated', $this->challengeId);
        $resolved = $this->outboxRow('ChallengeResolved', $this->challengeId);
        $this->assertNotNull($issued);
        $this->assertNotNull($correction);
        $this->assertNotNull($adjudicated);
        $this->assertNotNull($resolved);

        // Business outcome: the loop closed.
        $this->assertSame(ChallengeState::Resolved, $this->challengeState());
        $this->assertSame(1, DB::table('election_applied_determinations')
            ->where('organisation_id', $this->orgId)
            ->where('election_id', $this->electionId)
            ->count(), 'correction ledger row persisted');
        /** @var array<string, mixed> $resolvedPayload */
        $resolvedPayload = json_decode((string) $resolved->payload, true);
        $this->assertSame('upheld', $resolvedPayload['resolution'] ?? null);

        // IT-8: ONE correlation across every row; causation links each hop.
        $root = (string) $issued->correlation_id;
        $this->assertNotSame('', $root);
        foreach ([$correction, $adjudicated, $resolved] as $row) {
            $this->assertSame($root, (string) $row->correlation_id, 'one CorrelationId per conversation');
        }
        $this->assertSame((string) $issued->event_id, (string) $correction->causation_id);
        $this->assertSame((string) $issued->event_id, (string) $adjudicated->causation_id);
        $this->assertSame((string) $correction->event_id, (string) $resolved->causation_id, 'resolution is caused by the correction');

        // IT-8: the chain is queryable end-to-end by correlation.
        $this->assertSame(4, DB::table('outbox_events')->where('correlation_id', $root)->count());
        $this->assertGreaterThanOrEqual(3, DB::table('inbox_events')->where('correlation_id', $root)->count(),
            'delivered inbox rows continue the same correlation');
    }

    /** IT-2: Dismissed — Election stays silent; Contestation adjudicates + resolves (short-circuit). */
    public function test_it2_dismissed_loop_resolves_without_any_election_correction(): void
    {
        $determinationId = $this->issue(DeterminationOutcome::Dismissed);

        $this->relay();

        $this->assertSame(ChallengeState::Resolved, $this->challengeState());
        $this->assertNull($this->outboxRow('ElectionCorrectionApplied', $this->electionId), 'Election emits NOTHING on Dismissed');

        $resolved = $this->outboxRow('ChallengeResolved', $this->challengeId);
        $this->assertNotNull($resolved);
        /** @var array<string, mixed> $payload */
        $payload = json_decode((string) $resolved->payload, true);
        $this->assertSame('dismissed', $payload['resolution'] ?? null);

        $issued = $this->outboxRow('DeterminationIssued', $determinationId);
        $this->assertNotNull($issued);
        $this->assertSame((string) $issued->correlation_id, (string) $resolved->correlation_id);
        $this->assertSame((string) $issued->event_id, (string) $resolved->causation_id, 'short-circuit resolution is caused by the determination');
    }

    /** IT-3: redelivery of the same event is deduped per consumer — exactly one effect. */
    public function test_it3_duplicate_delivery_produces_no_second_effect(): void
    {
        $determinationId = $this->issue(DeterminationOutcome::Upheld);
        $this->relay();

        $issued = $this->outboxRow('DeterminationIssued', $determinationId);
        $this->assertNotNull($issued);

        // Redeliver the SAME integration event through the real listener path.
        /** @var array<string, mixed> $payload */
        $payload = json_decode((string) $issued->payload, true);
        event(new IntegrationEvent(
            eventId: (string) $issued->event_id,
            eventType: 'DeterminationIssued',
            aggregateType: 'Determination',
            aggregateId: $determinationId,
            organisationId: $this->orgId,
            payload: $payload,
            occurredAt: new DateTimeImmutable('2026-07-10T10:00:00+00:00'),
            correlationId: (string) $issued->correlation_id,
            causationId: null,
        ));

        $this->assertSame(1, DB::table('outbox_events')
            ->where('event_type', 'ChallengeAdjudicated')->where('aggregate_id', $this->challengeId)->count(),
            'no second adjudication');
        $this->assertSame(1, DB::table('outbox_events')
            ->where('event_type', 'ElectionCorrectionApplied')->where('aggregate_id', $this->electionId)->count(),
            'no second correction');
        $this->assertSame(2, DB::table('inbox_events')->where('event_id', $issued->event_id)->count(),
            'one inbox row per consumer — duplicates deduped, not duplicated');
    }

    /** IT-4: out-of-order — the correction arrives before the determination: park, then redrive resolves. */
    public function test_it4_out_of_order_correction_parks_then_redrive_resolves(): void
    {
        // The determination exists but its delivery is DELAYED (available_at in the future).
        $determinationId = $this->issue(DeterminationOutcome::Upheld);
        DB::table('outbox_events')
            ->where('event_type', 'DeterminationIssued')->where('aggregate_id', $determinationId)
            ->update(['available_at' => now()->addMinutes(10)]);

        // A correction referencing that determination is published FIRST (out of order).
        $issued = $this->outboxRow('DeterminationIssued', $determinationId);
        $this->assertNotNull($issued);
        $correctionEventId = (string) Str::uuid();
        DB::table('outbox_events')->insert([
            'id' => (string) Str::uuid(),
            'event_id' => $correctionEventId,
            'organisation_id' => $this->orgId,
            'aggregate_type' => 'Election',
            'aggregate_id' => $this->electionId,
            'event_type' => 'ElectionCorrectionApplied',
            'payload' => json_encode([
                'schema_version' => 1,
                'electionId' => $this->electionId,
                'determinationId' => $determinationId,
                'correctionType' => 'contained_only',
                'appliedAt' => '2026-07-10T10:05:00+00:00',
            ]),
            'correlation_id' => $issued->correlation_id,
            'causation_id' => $issued->event_id,
            'status' => 'pending',
            'attempts' => 0,
            'available_at' => now(),
            'created_at' => now(),
        ]);

        $this->relay();   // delivers ONLY the correction → premature → parked
        $this->assertSame('parked', DB::table('inbox_events')->where('event_id', $correctionEventId)->value('status'));
        $this->assertSame(ChallengeState::Routed, $this->challengeState(), 'nothing resolved yet');

        // The determination becomes available and is delivered → Contestation adjudicates.
        DB::table('outbox_events')
            ->where('event_type', 'DeterminationIssued')->where('aggregate_id', $determinationId)
            ->update(['available_at' => now()]);
        $this->relay();
        $this->assertSame(ChallengeState::Adjudicated, $this->challengeState());

        // Redrive the parked correction (due now) → the challenge resolves.
        DB::table('inbox_events')->where('event_id', $correctionEventId)->update(['parked_until' => now()->subMinute()]);
        $this->app->make(RedriveParkedInboxEvents::class)->handle();

        $this->assertSame('processed', DB::table('inbox_events')->where('event_id', $correctionEventId)->value('status'));
        $this->assertSame(ChallengeState::Resolved, $this->challengeState(), 'park + redrive completed the loop');
    }
}
