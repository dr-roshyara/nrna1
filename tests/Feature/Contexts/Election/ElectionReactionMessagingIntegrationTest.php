<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Election;

use App\Contexts\Election\Application\Port\AppliedDeterminationLedger;
use App\Contexts\Election\Application\DeterminationIssuedReactionHandler;
use App\Contexts\Election\Domain\DeterminationId;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Shared\Application\Inbox\InboxMessage;
use App\Contexts\Shared\Infrastructure\Inbox\Inbox;
use App\Contexts\Shared\Infrastructure\Inbox\InboxHandlerRegistry;
use App\Models\Organisation;
use App\Services\TenantContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;
use Tests\TestCase;

/**
 * PB-004 Step 4B (RED) — end-to-end messaging integration: consuming a binding
 * `DeterminationIssued` through the frozen Inbox drives the Election reaction, which
 * writes `ElectionCorrectionApplied` to the outbox AND records reaction state — atomically
 * (both inside the inbox's single transaction, ADR-T1). Duplicate delivery emits no second
 * event; an infrastructure failure rolls back BOTH writes; the registry resolves the
 * Election handler.
 *
 * Unique org per test (pgsql harness has no per-test rollback); assertions are tenant- and
 * election-scoped.
 */
final class ElectionReactionMessagingIntegrationTest extends TestCase
{
    private string $orgId;
    private string $electionId;

    protected function setUp(): void
    {
        parent::setUp();
        $org = Organisation::create([
            'name' => 'Election messaging org',
            'slug' => 'election-msg-'.Str::uuid(),
            'type' => 'tenant',
            'is_default' => false,
        ]);
        $this->orgId = (string) $org->id;
        TenantContext::set($this->orgId);

        // The election already exists in legacy (uuid — the outbox aggregate_id column is uuid).
        $this->electionId = (string) Str::uuid();
        DB::table('elections')->insert([
            'id' => $this->electionId,
            'organisation_id' => $this->orgId,
            'name' => 'Legacy election',
            'slug' => 'legacy-'.$this->electionId,
            'type' => 'real',
            'status' => 'completed',
            'is_active' => true,
            'created_at' => '2026-07-08 09:00:00',
            'updated_at' => '2026-07-08 09:00:00',
        ]);
    }

    private function message(?string $eventId = null, string $determinationId = 'det-9'): InboxMessage
    {
        return new InboxMessage(
            eventId: $eventId ?? (string) Str::uuid(),
            eventType: 'DeterminationIssued',
            payload: [
                'schema_version' => 2,
                'determinationId' => $determinationId,
                'challengeRef' => 'ch-9',
                'outcome' => 'upheld',
                'legitimacy' => 'legitimate',
                'contestedOutcome' => ['electionId' => $this->electionId, 'type' => 'election_result', 'targetId' => 'result-1'],
                'occurredAt' => '2026-07-08T10:00:00+00:00',
            ],
            organisationId: $this->orgId,
            correlationId: (string) Str::uuid(),
            causationId: (string) Str::uuid(),
        );
    }

    private function handler(): DeterminationIssuedReactionHandler
    {
        return $this->app->make(DeterminationIssuedReactionHandler::class);
    }

    private function inbox(): Inbox
    {
        return $this->app->make(Inbox::class);
    }

    private function outboxCorrectionRows(): int
    {
        return DB::table('outbox_events')
            ->where('organisation_id', $this->orgId)
            ->where('aggregate_id', $this->electionId)
            ->where('event_type', 'ElectionCorrectionApplied')
            ->count();
    }

    private function ledgerRows(): int
    {
        return DB::table('election_applied_determinations')
            ->where('organisation_id', $this->orgId)
            ->where('election_id', $this->electionId)
            ->count();
    }

    public function test_consuming_upheld_determination_writes_correction_event_and_ledger_atomically(): void
    {
        $this->inbox()->consume($this->message(), $this->handler());

        $this->assertSame(1, $this->outboxCorrectionRows(), 'exactly one ElectionCorrectionApplied enqueued');
        $this->assertSame(1, $this->ledgerRows(), 'reaction state recorded in the same transaction');
        $this->assertDatabaseHas('outbox_events', [
            'organisation_id' => $this->orgId,
            'aggregate_id' => $this->electionId,
            'aggregate_type' => 'Election',
            'event_type' => 'ElectionCorrectionApplied',
            'status' => 'pending',
        ]);
    }

    public function test_duplicate_delivery_emits_no_second_event(): void
    {
        $eventId = (string) Str::uuid();
        $this->inbox()->consume($this->message($eventId), $this->handler());
        $this->inbox()->consume($this->message($eventId), $this->handler()); // same event_id → inbox dedupe

        $this->assertSame(1, $this->outboxCorrectionRows(), 'a duplicate delivery must not enqueue a second correction');
    }

    public function test_a_ledger_failure_rolls_back_the_outbox_write(): void
    {
        // remember() throws AFTER the outbox enqueue; the inbox transaction must roll back BOTH.
        $this->app->bind(AppliedDeterminationLedger::class, fn () => new class implements AppliedDeterminationLedger {
            public function appliedDeterminations(ElectionId $id): array
            {
                return [];
            }

            public function remember(ElectionId $id, DeterminationId ...$determinations): void
            {
                throw new RuntimeException('ledger unavailable');
            }
        });

        try {
            $this->inbox()->consume($this->message(), $this->handler());
            $this->fail('expected the ledger failure to propagate');
        } catch (RuntimeException) {
            $this->assertSame(0, $this->outboxCorrectionRows(), 'the outbox write must roll back with the aggregate save');
        }
    }

    public function test_inbox_registry_resolves_the_election_reaction_handler(): void
    {
        $handler = $this->app->make(InboxHandlerRegistry::class)->handlerFor('Election', 'DeterminationIssued');

        $this->assertInstanceOf(DeterminationIssuedReactionHandler::class, $handler);
    }
}
