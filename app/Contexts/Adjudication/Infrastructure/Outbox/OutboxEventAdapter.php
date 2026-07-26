<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Infrastructure\Outbox;

use App\Contexts\Adjudication\Application\Port\EventOutbox;
use App\Contexts\Adjudication\Domain\DomainEvent;
use App\Contexts\Adjudication\Domain\Events\DeterminationIssued;
use App\Contexts\Shared\Application\Messaging\EventProvenance;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxEvent;
use App\Services\TenantContext;
use Illuminate\Support\Str;

/**
 * Infrastructure adapter for the EventOutbox port: writes domain events to the
 * existing `outbox_events` table (reused; dispatched by `outbox:process`).
 * Explicit per-event payload mapping (no reflection). Tenant resolved from
 * TenantContext, keeping the domain tenant-free (ADR-T16).
 */
final class OutboxEventAdapter implements EventOutbox
{
    public function enqueue(EventProvenance $provenance, DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            match (true) {
                $event instanceof DeterminationIssued => $this->writeDeterminationIssued($event, $provenance),
                default => throw new \LogicException(
                    'No outbox mapping for event ' . $event::class
                ),
            };
        }
    }

    private function writeDeterminationIssued(DeterminationIssued $event, EventProvenance $provenance): void
    {
        $outboxEvent = new OutboxEvent([
            'event_id' => (string) Str::uuid(),
            'organisation_id' => TenantContext::require(),
            'aggregate_type' => 'Determination',
            'aggregate_id' => $event->determinationId->toString(),
            'event_type' => 'DeterminationIssued',
            'correlation_id' => $provenance->correlationId,
            'causation_id' => $provenance->causationId,
            'payload' => [
                // ADR-T22 (WP-1): payload schema version 3 — additively carries
                // the fixed considered-evidence set. Same event; hydrator window
                // is (v3, v2) — v1 retired per the versioning rule.
                'schema_version' => 3,
                'determinationId' => $event->determinationId->toString(),
                'challengeRef' => $event->challengeRef->toString(),
                'outcome' => $event->outcome->value,
                'legitimacy' => $event->legitimacy->value,
                'reason' => $event->reason->toString(),
                'evidenceEnvelopeRef' => $event->evidenceEnvelopeRef->toString(),
                'issuedByAuthority' => $event->issuedByAuthority->toString(),
                'jurisdiction' => $event->jurisdiction->toString(),
                'contestedOutcome' => $event->contestedOutcome === null ? null : [
                    'electionId' => $event->contestedOutcome->electionId->toString(),
                    'type' => $event->contestedOutcome->type->value,
                    'targetId' => $event->contestedOutcome->targetId->toString(),
                ],
                'evidenceSet' => $event->evidenceSet?->toArray(),
                'occurredAt' => $event->occurredAt->format(DATE_ATOM),
            ],
            'status' => 'pending',
            'attempts' => 0,
            'available_at' => now(),
        ]);
        $outboxEvent->save();
    }
}
