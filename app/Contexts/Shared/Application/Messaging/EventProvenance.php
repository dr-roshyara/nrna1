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
 * Producer-independent and bounded-context-independent. CorrelationId identifies the
 * whole conversation (one per constitutional loop); CausationId links each hop to the
 * exact triggering message. These are provenance, not "just two columns" — the
 * constitutional audit chain is built on them. Supplied EXPLICITLY at publish time
 * (never ambient). Pure PHP; @immutable.
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
