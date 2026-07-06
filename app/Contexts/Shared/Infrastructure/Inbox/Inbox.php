<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Infrastructure\Inbox;

use App\Contexts\Shared\Application\Inbox\CausalPreconditionMissing;
use App\Contexts\Shared\Application\Inbox\IdempotentReplay;
use App\Contexts\Shared\Application\Inbox\InboxHandler;
use App\Contexts\Shared\Application\Inbox\InboxMessage;
use App\Contexts\Shared\Application\Inbox\InboxOutcome;
use App\Contexts\Shared\Application\Inbox\PermanentInboxFailure;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * The ONE idempotent-consumption mechanism (ADR-T4). A handler is invoked at
 * most once per (event_id, consumer_context); duplicates, out-of-order arrival,
 * and crashes are absorbed here so consuming contexts stay simple.
 *
 * Owner: Shared Infrastructure
 * Layer: Infrastructure (Laravel allowed; mirrors OutboxEventProcessor's direct
 *        DB::transaction convention — ER-03 sibling consistency)
 * Responsibility: dedupe-claim → invoke handler → classify outcome, in ONE txn
 * Traceability: Blueprint §6/§7/§8 · ADR-T1 (one txn) · ADR-T4 (dedupe) · Matrix: Inbox
 *
 * The wrapper never inspects payload semantics (Blueprint §6 context-boundary).
 * Exception classification is the handler's contract (Blueprint §8):
 *   CausalPreconditionMissing → Parked · IdempotentReplay → Processed
 *   PermanentInboxFailure → DeadLettered · other Throwable → rollback + rethrow.
 */
final class Inbox
{
    public function consume(InboxMessage $message, InboxHandler $handler): InboxOutcome
    {
        $context = $handler->consumerContext();

        try {
            return DB::transaction(function () use ($message, $handler, $context): InboxOutcome {
                // Short-circuit on an already-recorded (event_id, consumer_context).
                $existing = InboxEvent::query()
                    ->where('event_id', $message->eventId)
                    ->where('consumer_context', $context)
                    ->lockForUpdate()
                    ->first();

                if ($existing !== null) {
                    return match ($existing->status) {
                        'processed' => InboxOutcome::Duplicate,
                        'dead' => InboxOutcome::DeadLettered,
                        default => InboxOutcome::Parked,   // parked: re-drive (C5) owns re-attempts
                    };
                }

                // Claim the slot, then invoke the handler in the SAME transaction.
                $row = InboxEvent::create([
                    'event_id' => $message->eventId,
                    'consumer_context' => $context,
                    'event_type' => $message->eventType,
                    'payload' => $message->payload,
                    'organisation_id' => $message->organisationId,
                    'correlation_id' => $message->correlationId,
                    'causation_id' => $message->causationId,
                    'status' => 'parked',
                    'park_attempts' => 0,
                ]);

                return $this->invoke($row, $message, $handler);
            });
        } catch (QueryException $e) {
            // Concurrent claim of the same (event_id, consumer_context): the other
            // worker won; this is a duplicate (Blueprint §7 F3).
            if ($this->isUniqueViolation($e)) {
                return InboxOutcome::Duplicate;
            }
            throw $e;
        }
    }

    private function invoke(InboxEvent $row, InboxMessage $message, InboxHandler $handler): InboxOutcome
    {
        try {
            $handler->handle($message);
            $row->markProcessed();

            return InboxOutcome::Processed;
        } catch (CausalPreconditionMissing $e) {
            $row->markParked(
                now()->addMinutes((int) config('inbox.park_retry_minutes', 5)),
                now()->addMinutes((int) config('inbox.park_deadline_minutes', 60)),
            );

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
        // Any other Throwable propagates → DB::transaction rolls back (row insert
        // undone) → relay redelivery retries cleanly (Blueprint §7 F5).
    }

    private function isUniqueViolation(QueryException $e): bool
    {
        // Postgres 23505 / MySQL 1062 / SQLite "UNIQUE constraint failed".
        $sqlState = $e->getCode();
        if ($sqlState === '23505') {
            return true;
        }
        $message = $e->getMessage();

        return str_contains($message, '1062')
            || str_contains($message, 'UNIQUE constraint failed')
            || str_contains($message, 'Duplicate entry');
    }
}
