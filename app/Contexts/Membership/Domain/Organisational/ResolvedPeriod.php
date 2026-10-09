<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * One membership period as resolved from facts (derived; never stored as truth).
 * Predicates per contract §13.
 */
final readonly class ResolvedPeriod
{
    public function __construct(
        private PeriodId $id,
        private Interval $baseClaim,
        private IntervalSet $lifetime,
        private IntervalSet $term,
    ) {
    }

    public function id(): PeriodId
    {
        return $this->id;
    }

    public function start(): TimePoint
    {
        return $this->baseClaim->start();
    }

    public function baseClaim(): Interval
    {
        return $this->baseClaim;
    }

    public function lifetime(): IntervalSet
    {
        return $this->lifetime;
    }

    /** The term segments, already clipped to the lifetime. */
    public function term(): IntervalSet
    {
        return $this->term;
    }

    public function inLifetime(TimePoint $t): bool
    {
        return $this->lifetime->contains($t);
    }

    public function isEnded(TimePoint $t): bool
    {
        $end = $this->baseClaim->until();

        return !$end->isInfinite() && !$t->isBefore($end);
    }

    public function isTermCovered(TimePoint $t): bool
    {
        return $this->term->contains($t);
    }

    public function isInForce(TimePoint $t): bool
    {
        return $this->inLifetime($t) && $this->isTermCovered($t);
    }

    public function isLapsed(TimePoint $t): bool
    {
        return $this->inLifetime($t) && !$this->isTermCovered($t);
    }
}
