<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * A consequence of a Correct under the trim policy (BD-REV = trim; FP §12.4 option C: recorded on its cause, frozen
 * at decision time). P-2: it states the interval the decision removes from another period. Resolution applies it only
 * while its cause is active and not superseded, and only within the cause's current BaseClaim (LiveCut, stage 5).
 * Each accepted consequence is the data of one PeriodTrimmed event (contract §10a Correct (10)).
 */
final readonly class TrimConsequence
{
    public function __construct(private PeriodId $period, private IntervalSet $removed)
    {
        if ($removed->isEmpty()) {
            throw new \InvalidArgumentException('A trim removes a non-empty interval.');
        }
    }

    /** The trimmed period. */
    public function period(): PeriodId
    {
        return $this->period;
    }

    /** The removed interval, as stated by the decision. */
    public function removed(): IntervalSet
    {
        return $this->removed;
    }
}
