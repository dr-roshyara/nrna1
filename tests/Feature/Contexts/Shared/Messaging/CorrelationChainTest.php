<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Shared\Messaging;

use App\Contexts\Adjudication\Application\Command\IssueDeterminationCommand;
use App\Contexts\Adjudication\Application\Service\AdjudicationService;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\ContestedOutcomeRef;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\ElectionId;
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
use App\Contexts\Shared\Infrastructure\Outbox\OutboxEventProcessor;
use App\Models\Organisation;
use App\Services\TenantContext;
use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * PB-006 6B-1 (RED) — F-PB006-2: the constitutional AUDIT CHAIN across the REAL path.
 * IT-8 requires one CorrelationId across every row of the loop and CausationId linking
 * each hop. Behaviour asserted (the propagation seam is NOT prescribed — it emerges):
 *
 *   Adjudication issues        → its outbox row carries a minted correlation_id
 *   relay + dispatcher deliver → both reacting contexts consume it
 *   Election + Contestation produce → their outbox rows carry the SAME correlation_id
 *                                      and causation_id = the DeterminationIssued event_id
 */
final class CorrelationChainTest extends TestCase
{
    private string $orgId;
    private string $electionId;
    private string $challengeId;

    protected function setUp(): void
    {
        parent::setUp();
        $org = Organisation::create([
            'name' => 'Correlation chain org',
            'slug' => 'corr-'.Str::uuid(),
            'type' => 'tenant',
            'is_default' => false,
        ]);
        $this->orgId = (string) $org->id;
        TenantContext::set($this->orgId);

        // The election exists in legacy (Election reaction resolves existence via the ACL).
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

        // The challenge is already Routed (PB-005 Q1 scope).
        $this->challengeId = (string) Str::uuid();
        $this->app->make(ChallengeRepository::class)
            ->save(Challenge::reconstitute(ChallengeId::fromString($this->challengeId), ChallengeState::Routed, null));
    }

    public function test_correlation_flows_from_determination_through_both_reacting_contexts(): void
    {
        // T2 — Adjudication issues (Upheld) through the real producer path.
        $determinationId = $this->app->make(AdjudicationService::class)->issueDetermination(
            new IssueDeterminationCommand(
                ChallengeRef::fromString($this->challengeId),
                DeterminationOutcome::Upheld,
                Legitimacy::Legitimate,
                Reason::fromString('Tally dispute upheld.'),
                IssuedByAuthority::fromString('ARB'),
                Jurisdiction::fromString('National'),
                EvidenceEnvelopeRef::fromString('ev-1'),
                ContestedOutcomeRef::of(
                    ElectionId::fromString($this->electionId),
                    TargetType::ElectionResult,
                    TargetId::fromString('result-1'),
                ),
                EvidenceSet::fromRefs('ev-1'),
                new DateTimeImmutable('2026-07-10T10:00:00+00:00'),
            ),
        );

        $issued = DB::table('outbox_events')
            ->where('organisation_id', $this->orgId)
            ->where('event_type', 'DeterminationIssued')
            ->where('aggregate_id', $determinationId->toString())
            ->first();
        $this->assertNotNull($issued);
        // Loop start (raise path absent): the producer MINTS the correlation.
        $this->assertNotNull($issued->correlation_id, 'DeterminationIssued outbox row must carry a minted correlation_id');
        $rootCorrelation = (string) $issued->correlation_id;

        // Relay run — hydrate → dispatch → IntegrationEventDispatcher delivers to BOTH consumers.
        $this->app->make(OutboxEventProcessor::class)->handle();

        // Election produced ElectionCorrectionApplied — same correlation, caused by the determination event.
        $correction = DB::table('outbox_events')
            ->where('organisation_id', $this->orgId)
            ->where('event_type', 'ElectionCorrectionApplied')
            ->where('aggregate_id', $this->electionId)
            ->first();
        $this->assertNotNull($correction, 'Election must have produced the correction over the real path');
        $this->assertSame($rootCorrelation, (string) $correction->correlation_id, 'ONE correlation across the loop (IT-8)');
        $this->assertSame((string) $issued->event_id, (string) $correction->causation_id, 'causation links the hop (IT-8)');

        // Contestation produced ChallengeAdjudicated — same correlation, same causation hop.
        $adjudicated = DB::table('outbox_events')
            ->where('organisation_id', $this->orgId)
            ->where('event_type', 'ChallengeAdjudicated')
            ->where('aggregate_id', $this->challengeId)
            ->first();
        $this->assertNotNull($adjudicated, 'Contestation must have adjudicated over the real path');
        $this->assertSame($rootCorrelation, (string) $adjudicated->correlation_id, 'ONE correlation across the loop (IT-8)');
        $this->assertSame((string) $issued->event_id, (string) $adjudicated->causation_id, 'causation links the hop (IT-8)');

        // And the delivered inbox rows carry the same correlation (audit chain has no gap).
        $inboxCorrelations = DB::table('inbox_events')
            ->where('event_id', $issued->event_id)
            ->pluck('correlation_id')
            ->all();
        $this->assertNotEmpty($inboxCorrelations);
        foreach ($inboxCorrelations as $correlation) {
            $this->assertSame($rootCorrelation, (string) $correlation, 'inbox rows continue the same correlation');
        }
    }
}
