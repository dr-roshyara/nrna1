<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Contestation;

use App\Contexts\Contestation\Application\AdjudicateChallengeHandler;
use App\Contexts\Contestation\Application\ResolveChallengeHandler;
use App\Contexts\Contestation\Domain\Challenge\Challenge;
use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\ChallengeState;
use App\Contexts\Contestation\Domain\Repository\ChallengeRepository;
use App\Contexts\Shared\Application\Inbox\InboxMessage;
use App\Contexts\Shared\Infrastructure\Inbox\Inbox;
use App\Contexts\Shared\Infrastructure\Inbox\InboxHandlerRegistry;
use App\Models\Organisation;
use App\Services\TenantContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * PB-005 Step 5C (RED) — messaging integration, asserted as end-to-end BEHAVIOUR (not a
 * prescribed seam): consuming the correction-loop events through the frozen Inbox drives
 * the Contestation reaction, which publishes `ChallengeAdjudicated` / `ChallengeResolved`
 * to the outbox atomically. The published `ChallengeResolved` Integration Event is
 * ENRICHED with `resolution` (upheld/dismissed) supplied by the Application — the Domain
 * event stays minimal (F-2). HOW the Application supplies it is left to GREEN.
 */
final class ContestationReactionMessagingTest extends TestCase
{
    private string $orgId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orgId = (string) Organisation::create([
            'name' => 'Contestation messaging org',
            'slug' => 'contestation-msg-'.Str::uuid(),
            'type' => 'tenant',
            'is_default' => false,
        ])->id;
        TenantContext::set($this->orgId);
    }

    private function repo(): ChallengeRepository
    {
        return $this->app->make(ChallengeRepository::class);
    }

    private function inbox(): Inbox
    {
        return $this->app->make(Inbox::class);
    }

    private function seedRoutedChallenge(): string
    {
        // Raw uuid: the challenge id becomes the outbox `aggregate_id` (a uuid column).
        $id = (string) Str::uuid();
        $this->repo()->save(Challenge::reconstitute(ChallengeId::fromString($id), ChallengeState::Routed, null));

        return $id;
    }

    private function determinationIssued(string $challengeRef, string $determinationId, string $outcome): InboxMessage
    {
        return new InboxMessage(
            eventId: (string) Str::uuid(),
            eventType: 'DeterminationIssued',
            payload: [
                'schema_version' => 2,
                'determinationId' => $determinationId,
                'challengeRef' => $challengeRef,
                'outcome' => $outcome,
                'legitimacy' => 'legitimate',
                'occurredAt' => '2026-07-09T10:00:00+00:00',
            ],
            organisationId: $this->orgId,
        );
    }

    private function electionCorrectionApplied(string $determinationId): InboxMessage
    {
        return new InboxMessage(
            eventId: (string) Str::uuid(),
            eventType: 'ElectionCorrectionApplied',
            payload: [
                'schema_version' => 1,
                'electionId' => 'election-'.Str::uuid(),
                'determinationId' => $determinationId,
                'correctionType' => 'contained_only',
                'appliedAt' => '2026-07-09T10:05:00+00:00',
            ],
            organisationId: $this->orgId,
        );
    }

    /** @return array<string, mixed> */
    private function outboxPayload(string $eventType): array
    {
        $row = DB::table('outbox_events')
            ->where('organisation_id', $this->orgId)
            ->where('event_type', $eventType)
            ->first();
        $this->assertNotNull($row, "expected an outbox row for {$eventType}");
        /** @var array<string, mixed> $payload */
        $payload = json_decode((string) $row->payload, true);

        return $payload;
    }

    public function test_upheld_loop_publishes_adjudicated_then_resolved_with_upheld_resolution(): void
    {
        $determinationId = 'det-'.Str::uuid();
        $challengeRef = $this->seedRoutedChallenge();

        $this->inbox()->consume(
            $this->determinationIssued($challengeRef, $determinationId, 'upheld'),
            $this->app->make(AdjudicateChallengeHandler::class),
        );
        $this->assertDatabaseHas('outbox_events', [
            'organisation_id' => $this->orgId,
            'aggregate_type' => 'Challenge',
            'aggregate_id' => $challengeRef,
            'event_type' => 'ChallengeAdjudicated',
            'status' => 'pending',
        ]);

        $this->inbox()->consume(
            $this->electionCorrectionApplied($determinationId),
            $this->app->make(ResolveChallengeHandler::class),
        );
        $resolved = $this->outboxPayload('ChallengeResolved');
        $this->assertSame('upheld', $resolved['resolution'] ?? null, 'published ChallengeResolved must be enriched with resolution');
    }

    public function test_dismissed_determination_publishes_adjudicated_and_resolved_with_dismissed_resolution(): void
    {
        $determinationId = 'det-'.Str::uuid();
        $challengeRef = $this->seedRoutedChallenge();

        $this->inbox()->consume(
            $this->determinationIssued($challengeRef, $determinationId, 'dismissed'),
            $this->app->make(AdjudicateChallengeHandler::class),
        );

        $this->assertDatabaseHas('outbox_events', [
            'organisation_id' => $this->orgId,
            'event_type' => 'ChallengeAdjudicated',
            'aggregate_id' => $challengeRef,
        ]);
        $resolved = $this->outboxPayload('ChallengeResolved');
        $this->assertSame('dismissed', $resolved['resolution'] ?? null, 'the Dismissed short-circuit publishes a dismissed resolution');
    }

    public function test_inbox_registry_resolves_both_contestation_handlers(): void
    {
        $registry = $this->app->make(InboxHandlerRegistry::class);

        $this->assertInstanceOf(AdjudicateChallengeHandler::class, $registry->handlerFor('Contestation', 'DeterminationIssued'));
        $this->assertInstanceOf(ResolveChallengeHandler::class, $registry->handlerFor('Contestation', 'ElectionCorrectionApplied'));
    }
}
