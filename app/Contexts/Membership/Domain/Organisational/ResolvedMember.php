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

    /**
     * INV-COV: the union of period lifetimes (C1/C2 spec §3). A lapsed period is inside coverage.
     * Empty when there is no membership ("not a member", not an error).
     */
    public function coverage(): IntervalSet
    {
        $all = IntervalSet::empty();
        foreach ($this->periods as $period) {
            $all = $all->union($period->lifetime());
        }

        return $all;
    }

    /** IsMember as intervals: the terms, each already clipped to its lifetime (CR-02, D-161). */
    public function inForceIntervals(): IntervalSet
    {
        return $this->inForce();
    }

    /**
     * The restriction intervals within lifetimes (CR-02, D-161). Always empty for now: no Suspend fact
     * exists yet (release-1 build slice 5). Stated as an empty set, never omitted, so C1 / C2 carry it.
     */
    public function suspendedIntervals(): IntervalSet
    {
        $all = IntervalSet::empty();
        foreach ($this->periods as $period) {
            $all = $all->union($period->restrictions());
        }

        return $all;
    }

    /** S2-2: some period is suspended at t. Does not change isMember (S2). */
    public function isSuspended(TimePoint $t): bool
    {
        foreach ($this->periods as $period) {
            if ($period->isSuspended($t)) {
                return true;
            }
        }

        return false;
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
