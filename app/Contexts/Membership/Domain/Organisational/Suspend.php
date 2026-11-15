<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * Restricts a member's rights for a time (D-163; accepted design D-177 §2, §4): the restriction
 * [effectiveAt, until), where `until` is ∞ when the decision names no end (it then runs until a Restore).
 *
 * - Membership and its term continue: a Suspend changes neither a lifetime, coverage nor IsMember ([RAT] S2).
 * - The period is the one containing effectiveAt, fixed by Member::decideSuspend and stored here.
 * - Not an EffectiveDatedFact: correcting its date belongs to the correction step (S2-5).
 * - It carries no reason, ground or consequence: none is defined by the accepted texts.
 */
final readonly class Suspend implements Fact
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
            throw new \InvalidArgumentException('A suspension takes effect at a finite time.');
        }
        if (!$effectiveAt->isBefore($until)) {
            throw new \InvalidArgumentException('A restriction must end after it starts.');
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

    /** Finite, or ∞ when the suspension names no end. */
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
