<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Shared\Inbox;

use App\Contexts\Shared\Infrastructure\Inbox\InboxEvent;
use App\Models\Organisation;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * PB-003-C2 — inbox_events persistence: dedupe key + state transitions.
 *
 * Owner: Shared Infrastructure · Layer: Infrastructure
 * Traceability: Blueprint §6 · ADR-T4 · D-03 (dedupe key) · D-06 (tenant/corr ids) · Matrix: Inbox
 */
final class InboxEventTest extends TestCase
{
    use RefreshDatabase;

    private function row(array $overrides = []): InboxEvent
    {
        $organisation = $overrides['__org'] ?? Organisation::factory()->create();
        unset($overrides['__org']);

        return InboxEvent::create(array_merge([
            'event_id' => (string) Str::uuid(),
            'consumer_context' => 'Contestation',
            'event_type' => 'DeterminationIssued',
            'payload' => ['probe' => true],
            'organisation_id' => $organisation->id,
            'status' => 'parked',
        ], $overrides));
    }

    public function test_persists_with_identifiers_and_defaults(): void
    {
        $row = $this->row([
            'correlation_id' => (string) Str::uuid(),
            'causation_id' => (string) Str::uuid(),
        ]);

        $fresh = $row->fresh();
        $this->assertSame('parked', $fresh->status);
        $this->assertSame(0, $fresh->park_attempts);
        $this->assertSame(['probe' => true], $fresh->payload);
        $this->assertNotNull($fresh->correlation_id);
        $this->assertNotNull($fresh->causation_id);
    }

    /** D-03: the SAME event consumed by a SECOND consumer is legitimate ... */
    public function test_same_event_id_is_allowed_for_a_different_consumer_context(): void
    {
        $organisation = Organisation::factory()->create();
        $eventId = (string) Str::uuid();

        $this->row(['event_id' => $eventId, 'consumer_context' => 'Election', '__org' => $organisation]);
        $second = $this->row(['event_id' => $eventId, 'consumer_context' => 'Contestation', '__org' => $organisation]);

        $this->assertDatabaseCount('inbox_events', 2);
        $this->assertSame($eventId, $second->fresh()->event_id);
    }

    /** ... but a DUPLICATE (event_id, consumer_context) pair must violate UNIQUE. */
    public function test_duplicate_event_and_consumer_pair_violates_unique_constraint(): void
    {
        $organisation = Organisation::factory()->create();
        $eventId = (string) Str::uuid();

        $this->row(['event_id' => $eventId, 'consumer_context' => 'Election', '__org' => $organisation]);

        $this->expectException(QueryException::class);
        $this->row(['event_id' => $eventId, 'consumer_context' => 'Election', '__org' => $organisation]);
    }

    public function test_organisation_id_is_mandatory(): void
    {
        $this->expectException(QueryException::class);

        InboxEvent::create([
            'event_id' => (string) Str::uuid(),
            'consumer_context' => 'Election',
            'event_type' => 'DeterminationIssued',
            'payload' => [],
            'status' => 'parked',
        ]);
    }

    public function test_state_transitions_persist(): void
    {
        $row = $this->row();

        $row->markProcessed();
        $this->assertSame('processed', $row->fresh()->status);
        $this->assertNotNull($row->fresh()->processed_at);

        $parkedUntil = now()->addMinutes(5);
        $deadline = now()->addMinutes(60);
        $row->markParked($parkedUntil, $deadline);
        $fresh = $row->fresh();
        $this->assertSame('parked', $fresh->status);
        $this->assertSame(1, $fresh->park_attempts);
        $this->assertNotNull($fresh->parked_until);
        $this->assertNotNull($fresh->park_deadline);

        $row->markDead();
        $this->assertSame('dead', $row->fresh()->status);
    }

    public function test_parked_due_scope_returns_only_parked_rows_that_are_due(): void
    {
        $organisation = Organisation::factory()->create();

        $due = $this->row(['__org' => $organisation]);
        $due->markParked(now()->subMinute(), now()->addHour());

        $notDue = $this->row(['__org' => $organisation]);
        $notDue->markParked(now()->addHour(), now()->addHours(2));

        $processed = $this->row(['__org' => $organisation]);
        $processed->markProcessed();

        // Strict-clock (C6A): the scope REQUIRES an injected instant — a re-drive
        // decision must never read ambient time.
        $ids = InboxEvent::parkedDue(new \DateTimeImmutable('now'))->pluck('id')->all();
        $this->assertSame([$due->id], $ids);
    }
}
