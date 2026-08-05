<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Infrastructure\Outbox;

use App\Contexts\Adjudication\Application\Port\EventOutbox;
use App\Contexts\Adjudication\Domain\DomainEvent;
use App\Contexts\Adjudication\Domain\Events\AdjudicationExpired;
use App\Contexts\Adjudication\Domain\Events\AdjudicationFailureDeclared;
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
                $event instanceof AdjudicationExpired => $this->writeAdjudicationExpired($event, $provenance),
                $event instanceof AdjudicationFailureDeclared => $this->writeAdjudicationFailureDeclared($event, $provenance),
                default => throw new \LogicException(
                    'No outbox mapping for event ' . $event::class
                ),
            };
        }
    }

    /**
     * Section 197 (RULED): expiry ANNOUNCES the failure-to-conclude, and Contestation
     * owns the challenge's disposition -- so this is PUBLISHED LANGUAGE (ARB Decision A).
     * The payload carries no outcome, no legitimacy and no reason: a horizon decides
     * nothing (Constitutional Policy 4).
     */
    /**
     * PM-7 (EPIC-004K §10): the authority found the evidence insufficient, so no ruling can
     * issue. **A failure of EVIDENCE, where `AdjudicationExpired` is a failure of TIME** — hence
     * an authority and a stated ground here, and neither there.
     *
     * The payload carries **no outcome and no legitimacy**: nothing was ruled on the contested
     * outcome, only that it could not be ruled.
     *
     * **Follows the existing adapter pattern verbatim** — dispatch, payload mapping, provenance
     * stamping and persistence together (ARB interpretation of O-2, 2026-08-04). The adapter's
     * combination of those four responsibilities is a recorded implementation characteristic and
     * a candidate future refactoring; **if it is ever decomposed, ALL events migrate together, not
     * this one alone.**
     *
     * **This writer DECIDES NO PROVENANCE.** It stamps whatever `EventProvenance` its caller
     * supplies, exactly as its two siblings do — so the open question of whether the publication
     * site should `start()` a conversation or continue one (plan §6/E2) is **untouched and
     * unprejudiced by this method.**
     */
    private function writeAdjudicationFailureDeclared(
        AdjudicationFailureDeclared $event,
        EventProvenance $provenance,
    ): void {
        (new OutboxEvent([
            'event_id' => (string) Str::uuid(),
            'organisation_id' => TenantContext::require(),
            'aggregate_type' => 'AdjudicationProcess',
            'aggregate_id' => $event->challengeRef->toString(),
            'event_type' => 'AdjudicationFailureDeclared',
            'correlation_id' => $provenance->correlationId,
            'causation_id' => $provenance->causationId,
            'payload' => [
                'schema_version' => 1,
                'challengeRef' => $event->challengeRef->toString(),
                'reason' => $event->reason->toString(),
                'declaredByAuthority' => $event->declaredByAuthority->toString(),
                'declaredAt' => $event->declaredAt->format(DATE_ATOM),
            ],
            'status' => 'pending',
            'attempts' => 0,
            'available_at' => now(),
        ]))->save();
    }

    private function writeAdjudicationExpired(AdjudicationExpired $event, EventProvenance $provenance): void
    {
        (new OutboxEvent([
            'event_id' => (string) Str::uuid(),
            'organisation_id' => TenantContext::require(),
            'aggregate_type' => 'AdjudicationProcess',
            'aggregate_id' => $event->challengeRef->toString(),
            'event_type' => 'AdjudicationExpired',
            'correlation_id' => $provenance->correlationId,
            'causation_id' => $provenance->causationId,
            'payload' => [
                'schema_version' => 1,
                'challengeRef' => $event->challengeRef->toString(),
                'expiredAt' => $event->expiredAt->format(DATE_ATOM),
            ],
            'status' => 'pending',
            'attempts' => 0,
            'available_at' => now(),
        ]))->save();
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
