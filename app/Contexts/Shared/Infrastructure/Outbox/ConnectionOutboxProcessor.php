<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Infrastructure\Outbox;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Log;

/**
 * The outbox relay ON A GIVEN CONNECTION (PBDIGIT-345 task 9b; tactical design §3.4, §3.10.2, ISO-9). Used by the
 * per-organisation relay (one organisation database per run); the central relay (OutboxEventProcessor, central
 * events) is a separate, unchanged class: two relays, never one relay reading both.
 *
 * Every step runs on the given connection, never the default:
 * 1. refuse to run inside an open transaction: dispatch must happen outside any organisation transaction (T-24, Q-7);
 * 2. CLAIM committed rows atomically (`FOR UPDATE SKIP LOCKED`, one statement, committed): pending rows, or rows whose
 *    claim LEASE ran out (a crashed relay) → status `processing`, available_at = now + lease. Uncommitted rows are
 *    invisible; a row under a live claim is not claimed twice;
 * 3. ISO-9: an event whose organisation_id is not the expected organisation is DEAD-LETTERED (status `failed`, row
 *    kept) and alerted (Log::critical), never dispatched;
 * 4. hydrate (EventHydratorRegistry), dispatch the domain event and the IntegrationEvent, then mark `completed`;
 * 5. same failure rules as the central relay: an unregistered type is dead-lettered at once; any other failure is
 *    retried after 5 minutes, and dead-lettered after 5 attempts. The original row is always kept.
 * At least once: a crash between dispatch and mark re-delivers after the lease; consumers are idempotent by event_id.
 */
final class ConnectionOutboxProcessor
{
    public const MAX_ATTEMPTS = 5;
    public const RETRY_DELAY_MINUTES = 5;
    public const LEASE_SECONDS = 300;
    private const BATCH = 100;

    public function __construct(
        private readonly EventHydratorRegistry $registry,
        private readonly Dispatcher $events,
    ) {
    }

    /** @return array{delivered: int, retried: int, dead_lettered: int} */
    public function process(ConnectionInterface $db, string $expectedOrganisationId): array
    {
        if ($db->transactionLevel() > 0) {
            throw new \LogicException('The outbox relay never runs inside an open transaction: it dispatches only committed events, outside any transaction.');
        }
        // available_at is `timestamp` (precision 0, the central shape): its CURRENT_TIMESTAMP default is ROUNDED to the
        // second, up to 0.5 s ahead of now(). Compare with the same rounding (localtimestamp(0)), so an event is claimable
        // from the moment it is committed (rounding is monotonic: a later relay never sees an earlier event as future).
        $claimed = $db->select("UPDATE outbox_events SET status = 'processing', available_at = now() + make_interval(secs => ?)
            WHERE id IN (SELECT id FROM outbox_events WHERE status IN ('pending', 'processing') AND available_at <= localtimestamp(0)
                         ORDER BY created_at, id LIMIT ? FOR UPDATE SKIP LOCKED)
            RETURNING *", [self::LEASE_SECONDS, self::BATCH]);
        usort($claimed, static fn (object $x, object $y): int => [$x->created_at, $x->id] <=> [$y->created_at, $y->id]);

        $report = ['delivered' => 0, 'retried' => 0, 'dead_lettered' => 0];
        foreach ($claimed as $row) {
            if ($row->organisation_id !== $expectedOrganisationId) {
                Log::critical('outbox.iso9.organisation_mismatch', ['outbox_event_id' => $row->id, 'event_type' => $row->event_type,
                    'expected_organisation' => $expectedOrganisationId, 'event_organisation' => $row->organisation_id]);
                $this->deadLetter($db, $row);
                $report['dead_lettered']++;
                continue;
            }
            try {
                $payload = json_decode((string) $row->payload, true, 512, JSON_THROW_ON_ERROR);
                $domainEvent = $this->registry->hydratorFor($row->event_type)->hydrate($payload);
                $integrationEvent = new IntegrationEvent($row->event_id, $row->event_type, $row->aggregate_type, $row->aggregate_id,
                    $row->organisation_id, $payload, CarbonImmutable::parse($row->created_at), $row->correlation_id, $row->causation_id);
                $this->events->dispatch($domainEvent);
                $this->events->dispatch($integrationEvent);
                $db->update("UPDATE outbox_events SET status = 'completed', processed_at = now() WHERE id = ? AND status = 'processing'", [$row->id]);
                $report['delivered']++;
            } catch (UnregisteredEventType $e) {
                Log::error('outbox.dead_lettered', ['outbox_event_id' => $row->id, 'event_type' => $row->event_type, 'reason' => $e->deadLetterReason()]);
                $this->deadLetter($db, $row);
                $report['dead_lettered']++;
            } catch (\Throwable $e) {
                Log::error('outbox.delivery_failed', ['outbox_event_id' => $row->id, 'event_type' => $row->event_type, 'error_class' => $e::class]);
                $attempts = (int) $row->attempts + 1;
                if ($attempts >= self::MAX_ATTEMPTS) {
                    $db->update("UPDATE outbox_events SET status = 'failed', attempts = ?, processed_at = now() WHERE id = ?", [$attempts, $row->id]);
                    $report['dead_lettered']++;
                } else {
                    $db->update("UPDATE outbox_events SET status = 'pending', attempts = ?, available_at = now() + make_interval(mins => ?) WHERE id = ?",
                        [$attempts, self::RETRY_DELAY_MINUTES, $row->id]);
                    $report['retried']++;
                }
            }
        }

        return $report;
    }

    private function deadLetter(ConnectionInterface $db, object $row): void
    {
        $db->update("UPDATE outbox_events SET status = 'failed', processed_at = now() WHERE id = ?", [$row->id]);
    }
}
