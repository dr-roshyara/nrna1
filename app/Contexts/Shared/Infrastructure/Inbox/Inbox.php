<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Infrastructure\Inbox;

use App\Contexts\Shared\Application\Inbox\InboxHandler;
use App\Contexts\Shared\Application\Inbox\InboxMessage;
use App\Contexts\Shared\Application\Inbox\InboxOutcome;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

/**
 * Idempotent-consumption entry point for FRESH delivery (ADR-T4): dedupe-claims
 * the (event_id, consumer_context) slot, then delegates run+classify to the
 * shared {@see InboxExecutionEngine}. A handler is invoked at most once per
 * (event_id, consumer_context); duplicates and races are absorbed here.
 *
 * Owner: Shared Infrastructure
 * Layer: Infrastructure (Laravel allowed; mirrors OutboxEventProcessor's direct
 *        DB::transaction convention — ER-03 sibling consistency)
 * Responsibility: dedupe-claim (fresh delivery) → delegate to execution engine, in ONE txn
 * Traceability: Blueprint §6/§7/§8 · ADR-T1 (one txn) · ADR-T4 (dedupe) · Matrix: Inbox
 *
 * Classification lives in InboxExecutionEngine (shared with redrive) — Inbox does
 * NOT accumulate runtime behaviours (no God Service). A PARKED row is never
 * re-invoked here; retry timing is owned solely by redrive (D-10).
 * The wrapper never inspects payload semantics (Blueprint §6 context-boundary).
 */
final class Inbox
{
    public function __construct(private readonly InboxExecutionEngine $engine)
    {
    }

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

                // Run + classify in the SAME transaction (shared with redrive).
                return $this->engine->execute($row, $message, $handler);
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
