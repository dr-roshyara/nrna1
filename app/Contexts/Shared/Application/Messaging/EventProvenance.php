<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Application\Messaging;

/**
 * Message lineage for published events (ADR-MP-06 / F-PB006-2) — the invariant, reified:
 *
 *   "A producer initiating a new causal chain mints a CorrelationId only when no
 *    incoming correlation already exists; otherwise it propagates the existing
 *    CorrelationId and records the triggering message as the CausationId."
 *
 * Producer-independent and bounded-context-independent. Supplied EXPLICITLY at publish
 * time (never ambient). Pure PHP; @immutable.
 *
 * CONSTITUTIONAL AUDIT INVARIANT (ARB, 2026-07-10):
 *  - Exactly ONE producer mints a CorrelationId for each constitutional conversation.
 *  - Every subsequent producer propagates that CorrelationId UNCHANGED — no producer
 *    may mint a second correlation within a conversation.
 *  - Every publication after the first records its IMMEDIATE causal predecessor as
 *    the CausationId.
 *  - CorrelationId identifies the CONVERSATION, not the event; CausationId identifies
 *    the DIRECT PARENT, not the chain.
 *
 * A CONSTITUTIONAL CONVERSATION is the complete causal chain of events originating
 * from one constitutional decision process (see ADR-MP-06 + developer guide 08).
 * EventProvenance applies ONLY to Integration Events — NEVER Domain Events (no Domain
 * Event class may carry correlation/causation properties).
 */
final readonly class EventProvenance
{
    private function __construct(
        public string $correlationId,
        public ?string $causationId,
    ) {
    }

    /** A producer initiating a NEW causal chain: mint the correlation, no cause yet. */
    public static function start(string $mintedCorrelationId): self
    {
        return new self($mintedCorrelationId, null);
    }

    /**
     * A producer reacting to a consumed message: propagate its correlation and record the
     * triggering message as the cause. If the incoming chain carries no correlation, the
     * triggering event's id becomes the correlation — DETERMINISTIC (replay-safe: a
     * redelivered message yields the identical provenance; a random mint would not).
     */
    public static function fromConsumed(?string $incomingCorrelationId, string $triggeringEventId): self
    {
        return new self($incomingCorrelationId ?? $triggeringEventId, $triggeringEventId);
    }
}
