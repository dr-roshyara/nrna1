<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Infrastructure\Outbox;

use App\Contexts\Adjudication\Application\Port\EventOutbox;
use App\Contexts\Adjudication\Domain\DomainEvent;
use App\Contexts\Adjudication\Domain\Events\DeterminationIssued;
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
    public function enqueue(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            match (true) {
                $event instanceof DeterminationIssued => $this->writeDeterminationIssued($event),
                default => throw new \LogicException(
                    'No outbox mapping for event ' . $event::class
                ),
            };
        }
    }

    private function writeDeterminationIssued(DeterminationIssued $event): void
    {
        $outboxEvent = new OutboxEvent([
            'event_id' => (string) Str::uuid(),
            'organisation_id' => TenantContext::require(),
            'aggregate_type' => 'Determination',
            'aggregate_id' => $event->determinationId->toString(),
            'event_type' => 'DeterminationIssued',
            'payload' => [
                // ADR-PL-01: payload schema version 2 — additively carries the
                // contested-outcome reference. Same event; hydrator reads v1+v2.
                'schema_version' => 2,
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
                'occurredAt' => $event->occurredAt->format(DATE_ATOM),
            ],
            'status' => 'pending',
            'attempts' => 0,
            'available_at' => now(),
        ]);
        $outboxEvent->save();
    }
}
