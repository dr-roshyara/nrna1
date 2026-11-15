<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * Adds a term segment [effectiveAt, until) to a membership period (D-163; accepted design D-177 §2, §4).
 *
 * - `until` is finite or ∞ (an open-ended term).
 * - It never changes a lifetime or coverage: a Renew carries no consequences and no trims ([RAT] §2).
 * - The period is the one containing effectiveAt, fixed by Member::decideRenew at the decision and stored here.
 * - Not an EffectiveDatedFact: that capability marks the dates a Correct may replace (Admit, End). Correcting a
 *   Renew is refused until its rule is decided (D-177 row 20; PBDIGIT-354 Q-2).
 */
final readonly class Renew implements Fact
{
    public function __construct(
        private FactId $id,
        private PeriodId $period,
        private TimePoint $effectiveAt,
        private TimePoint $until,
        private Provenance $provenance,
        private ?Instant $decidedAt = null,
    ) {
        if ($effectiveAt->isInfinite()) {
            throw new \InvalidArgumentException('A renewal takes effect at a finite time.');
        }
        if (!$effectiveAt->isBefore($until)) {
            throw new \InvalidArgumentException('A renewed term must end after it starts.');
        }
    }

    public function id(): FactId
    {
        return $this->id;
    }

    public function period(): PeriodId
    {
        return $this->period;
    }

    public function effectiveAt(): TimePoint
    {
        return $this->effectiveAt;
    }

    /** Finite, or ∞ for an open-ended term. */
    public function until(): TimePoint
    {
        return $this->until;
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
