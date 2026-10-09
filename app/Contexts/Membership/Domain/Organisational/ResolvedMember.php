<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * The resolved state of one Member: its periods and the Member-level predicates
 * of contract §13. Derived only.
 */
final readonly class ResolvedMember
{
    /** @param list<ResolvedPeriod> $periods in admission (seq) order */
    public function __construct(private array $periods)
    {
    }

    /** @return list<ResolvedPeriod> */
    public function periods(): array
    {
        return $this->periods;
    }

    public function period(PeriodId $id): ?ResolvedPeriod
    {
        foreach ($this->periods as $period) {
            if ($period->id()->equals($id)) {
                return $period;
            }
        }

        return null;
    }

    public function isMember(TimePoint $t): bool
    {
        foreach ($this->periods as $period) {
            if ($period->isInForce($t)) {
                return true;
            }
        }

        return false;
    }

    /** The unique period whose lifetime contains t (unique by INV-9), or null. */
    public function currentPeriod(TimePoint $t): ?PeriodId
    {
        foreach ($this->periods as $period) {
            if ($period->inLifetime($t)) {
                return $period->id();
            }
        }

        return null;
    }

    public function firstAdmission(): ?TimePoint
    {
        $first = null;
        foreach ($this->periods as $period) {
            if (!$period->lifetime()->isEmpty() && ($first === null || $period->start()->isBefore($first))) {
                $first = $period->start();
            }
        }

        return $first;
    }

    public function currentAdmission(TimePoint $t): ?TimePoint
    {
        $current = $this->currentPeriod($t);

        return $current === null ? null : $this->period($current)?->start();
    }

    /** The maximal interval around t on which the person is continuously a member (may span adjacent periods). */
    public function continuousMembership(TimePoint $t): ?Interval
    {
        foreach ($this->inForce()->intervals() as $interval) {
            if ($interval->contains($t)) {
                return $interval;
            }
        }

        return null;
    }

    /** Every u ≤ t at which the person was a member, as a union of intervals. */
    public function cumulativeMembership(TimePoint $t): IntervalSet
    {
        return $this->inForce()->truncatedAt($t->successor());
    }

    private function inForce(): IntervalSet
    {
        $all = IntervalSet::empty();
        foreach ($this->periods as $period) {
            $all = $all->union($period->term());
        }

        return $all;
    }
}
