<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * Ends the restriction of ONE Suspend fact at effectiveAt (accepted design D-177 §2, §4): that suspension's
 * restriction becomes [s.from, min(s.until, effectiveAt)).
 *
 * - Not a ReversalFact: it does not cancel the Suspend (the restriction up to effectiveAt stands), and it must not
 *   take part in the liveness pass. Removing a suspension altogether is an Annul or a Void.
 * - A Restore whose Suspend is no longer live has no effect (row 18).
 * - Not an EffectiveDatedFact: correcting its date belongs to the correction step (S2-5).
 */
final readonly class Restore implements Fact
{
    public function __construct(
        private FactId $id,
        private FactId $suspension,
        private TimePoint $effectiveAt,
        private Provenance $provenance,
        private ?Instant $decidedAt = null,
    ) {
        if ($effectiveAt->isInfinite()) {
            throw new \InvalidArgumentException('A restoration takes effect at a finite time.');
        }
    }

    public function id(): FactId
    {
        return $this->id;
    }

    /** The Suspend fact this Restore ends. */
    public function suspension(): FactId
    {
        return $this->suspension;
    }

    public function effectiveAt(): TimePoint
    {
        return $this->effectiveAt;
    }

    public function decidedAt(): ?Instant
    {
        return $this->decidedAt;
    }

    public function provenance(): Provenance
    {
        return $this->provenance;
    }
}
