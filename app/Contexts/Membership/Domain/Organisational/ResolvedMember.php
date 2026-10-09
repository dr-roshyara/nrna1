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
}
