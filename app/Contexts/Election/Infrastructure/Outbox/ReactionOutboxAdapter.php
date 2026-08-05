<?php

declare(strict_types=1);

namespace App\Contexts\Election\Infrastructure\Outbox;

use App\Contexts\Election\Application\Port\ReactionEventOutbox;
use App\Contexts\Election\Domain\Events\ElectionCorrectionApplied;
use App\Contexts\Shared\Application\Messaging\EventProvenance;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxEvent;
use App\Services\TenantContext;
use Illuminate\Support\Str;
use LogicException;

/**
 * Infrastructure adapter for the {@see ReactionEventOutbox} port: writes Election's domain
 * events to the shared `outbox_events` table (reused; the platform relay re-emits them).
 * Explicit per-event mapping (no reflection). The domain event carries only business
 * identity; the adapter assigns the transport `event_id` (a fresh uuid). Tenant is
 * resolved from `TenantContext::require()` — which throws when unset, so a row is never
 * written with an empty `organisation_id` (the FK/S7 lesson).
 *
 * Called inside the inbox's transaction (ADR-T1): the row is committed atomically with the
 * aggregate save, or rolled back with it.
 */
final class ReactionOutboxAdapter implements ReactionEventOutbox
{
    public function enqueue(EventProvenance $provenance, object ...$events): void
    {
        foreach ($events as $event) {
            match (true) {
                $event instanceof ElectionCorrectionApplied => $this->writeElectionCorrectionApplied($event, $provenance),
                default => throw new LogicException('No outbox mapping for event '.$event::class),
            };
        }
    }

    private function writeElectionCorrectionApplied(ElectionCorrectionApplied $event, EventProvenance $provenance): void
    {
        (new OutboxEvent([
            'event_id' => (string) Str::uuid(),
            'organisation_id' => TenantContext::require(),
            'aggregate_type' => 'Election',
            'aggregate_id' => $event->electionId->toString(),
            'event_type' => 'ElectionCorrectionApplied',
            'correlation_id' => $provenance->correlationId,
            'causation_id' => $provenance->causationId,
            'payload' => [
                'schema_version' => 1,
                'electionId' => $event->electionId->toString(),
                'determinationId' => $event->determinationId->toString(),
                'correctionType' => $event->correctionType->value,
                'appliedAt' => $event->appliedAt->format(DATE_ATOM),
            ],
            'status' => 'pending',
            'attempts' => 0,
            'available_at' => now(),
        ]))->save();
    }
}
