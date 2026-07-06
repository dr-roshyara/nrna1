<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Infrastructure\Outbox;

/**
 * Contract for context-owned outbox event hydrators.
 *
 * Each bounded context owns the hydrators for the events it produces and
 * registers them in the EventHydratorRegistry via its service provider —
 * introducing a new context event never modifies shared infrastructure.
 *
 * Versioning (ADR-T5): the payload may carry a schema_version marker;
 * version dispatch/upcasting is the hydrator's internal concern.
 *
 * Traceability — Blueprint: Push B §6, §16 step 4 · ADR: ADR-T3, ADR-T5 ·
 * Matrix: Push B — Event Registry · Context: Shared Infrastructure (contract only).
 */
interface EventHydrator
{
    /** Canonical event type name exactly as in the Event Catalog v1.0. */
    public function eventType(): string;

    /**
     * Reconstruct the domain event object from the stored outbox payload.
     *
     * @param array<string, mixed> $payload decoded `outbox_events.payload`
     *
     * @throws \InvalidArgumentException when a required field is missing/invalid
     */
    public function hydrate(array $payload): object;
}
