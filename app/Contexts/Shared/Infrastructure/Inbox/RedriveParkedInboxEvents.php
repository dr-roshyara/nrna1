<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Infrastructure\Inbox;

use App\Contexts\Shared\Application\Inbox\InboxMessage;
use App\Domain\Shared\Clock\ClockInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Recovery layer: re-drives PARKED inbox rows whose retry time has arrived,
 * bounded by an absolute deadline. Selection + deadline + delegation only —
 * NO business logic (the handler owns all business behaviour; Rule 5).
 * Re-invocation goes through the shared {@see InboxExecutionEngine}, so recovery
 * can only produce outcomes reachable by normal delivery (same handler, same
 * message — only timing differs).
 *
 * Owner: Shared Infrastructure
 * Layer: Infrastructure
 * Responsibility: select due parked rows → deadline/handler check → delegate re-invoke
 * Traceability: Blueprint §7 F4, §7.1 Recovery · ADR-T1 (row-per-txn) · ADR-T4 · Matrix: Inbox
 *
 * Time is INJECTED (ClockInterface) — selection and deadline use $clock->now(),
 * never ambient time (R2). Each row is processed in its OWN transaction with a
 * row lock, so concurrent redrive runs never double-process (Rule 1/4).
 */
final class RedriveParkedInboxEvents
{
    public function __construct(
        private readonly InboxHandlerRegistry $registry,
        private readonly InboxExecutionEngine $engine,
        private readonly ClockInterface $clock,
    ) {
    }

    public function handle(): void
    {
        $now = $this->clock->now();

        InboxEvent::query()
            ->parkedDue($now)
            ->orderBy('parked_until')
            ->get()
            ->each(fn (InboxEvent $row) => $this->redriveOne($row->getKey()));
    }

    private function redriveOne(string $id): void
    {
        DB::transaction(function () use ($id): void {
            $row = InboxEvent::query()->lockForUpdate()->find($id);

            // Concurrency guard: another worker may have finalized it first.
            if ($row === null || $row->status !== 'parked') {
                return;
            }

            $now = $this->clock->now();

            // Absolute deadline reached → dead-letter without re-invoking (F4).
            if ($row->park_deadline !== null && $row->park_deadline->toDateTimeImmutable() <= $now) {
                $row->markDead();
                $this->alert($row, 'PARK_DEADLINE_EXCEEDED');

                return;
            }

            try {
                $handler = $this->registry->handlerFor($row->consumer_context, $row->event_type);
            } catch (UnregisteredInboxHandler $e) {
                $row->markDead();
                $this->alert($row, $e->deadLetterReason());

                return;
            }

            $message = new InboxMessage(
                eventId: $row->event_id,
                eventType: $row->event_type,
                payload: $row->payload,
                organisationId: $row->organisation_id,
                correlationId: $row->correlation_id,
                causationId: $row->causation_id,
            );

            // Re-invoke via the SAME engine consume() uses (no God Service).
            $this->engine->execute($row, $message, $handler);
        });
    }

    private function alert(InboxEvent $row, string $reason): void
    {
        Log::error('Inbox message dead-lettered', [
            'dead_letter_reason' => $reason,
            'event_id' => $row->event_id,
            'consumer_context' => $row->consumer_context,
            'event_type' => $row->event_type,
            'organisation_id' => $row->organisation_id,
        ]);
    }
}
