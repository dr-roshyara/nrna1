<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Infrastructure\Inbox;

use App\Contexts\Shared\Application\Inbox\CausalPreconditionMissing;
use App\Contexts\Shared\Application\Inbox\IdempotentReplay;
use App\Contexts\Shared\Application\Inbox\InboxHandler;
use App\Contexts\Shared\Application\Inbox\InboxMessage;
use App\Contexts\Shared\Application\Inbox\InboxOutcome;
use App\Contexts\Shared\Application\Inbox\PermanentInboxFailure;
use App\Domain\Shared\Clock\ClockInterface;
use Illuminate\Support\Facades\Log;

/**
 * The ONE place that runs an inbox handler against a claimed row and classifies
 * the outcome (Blueprint §8). BOTH fresh delivery (Inbox::consume) and recovery
 * (RedriveParkedInboxEvents) delegate here — so neither `Inbox` nor the redrive
 * service accumulates classification logic (avoids a God Service). Future
 * replay/audit/manual-retry reuse this same seam.
 *
 * Owner: Shared Infrastructure
 * Layer: Infrastructure
 * Responsibility: invoke handler + classify → mark row + return InboxOutcome
 * Traceability: Blueprint §6/§7/§8 · ADR-T1 (runs inside caller's txn) · ADR-T4 · Matrix: Inbox
 *
 * Time is INJECTED (ClockInterface), never discovered (R2) — reuses the shared
 * clock whose contract is "time MUST be externally injected, never ambient".
 * Assumes an ACTIVE transaction (the caller owns the txn boundary): a classified
 * outcome marks the row; any other Throwable propagates → caller rolls back.
 */
final class InboxExecutionEngine
{
    public function __construct(private readonly ClockInterface $clock)
    {
    }

    public function execute(InboxEvent $row, InboxMessage $message, InboxHandler $handler): InboxOutcome
    {
        try {
            $handler->handle($message);
            $row->markProcessed();

            return InboxOutcome::Processed;
        } catch (CausalPreconditionMissing) {
            $now = $this->clock->now();
            $retry = (int) config('inbox.park_retry_minutes', 5);
            $deadlineMinutes = (int) config('inbox.park_deadline_minutes', 60);

            // Deadline is an ABSOLUTE horizon set at first park and PRESERVED
            // across re-parks — otherwise it slides forever and never fires.
            $deadline = $row->park_deadline?->toDateTimeImmutable()
                ?? $now->modify("+{$deadlineMinutes} minutes");

            $row->markParked($now->modify("+{$retry} minutes"), $deadline);

            return InboxOutcome::Parked;
        } catch (IdempotentReplay) {
            $row->markProcessed();

            return InboxOutcome::Processed;
        } catch (PermanentInboxFailure $e) {
            $row->markDead();
            Log::error('Inbox message dead-lettered', [
                'dead_letter_reason' => 'PERMANENT_HANDLER_FAILURE',
                'event_id' => $row->event_id,
                'consumer_context' => $row->consumer_context,
                'event_type' => $row->event_type,
                'organisation_id' => $row->organisation_id,
                'error' => $e->getMessage(),
            ]);

            return InboxOutcome::DeadLettered;
        }
        // Other Throwable propagates → caller's DB::transaction rolls back
        // (fresh row insert undone / parked row unchanged) → retried next tick.
    }
}
