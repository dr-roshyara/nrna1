<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * The data of one TrimRevived event (contract §10a Correct (10)): a decision made an earlier trim apply again (CFC+),
 * e.g. by restoring its cause or by setting aside the correction that superseded it. A trim is identified by its cause
 * and the trimmed period (FP §12.4: id derived from (cause, period)). Emission is COR-3.
 */
final readonly class TrimRevival
{
    public function __construct(private FactId $cause, private PeriodId $period)
    {
    }

    public function cause(): FactId
    {
        return $this->cause;
    }

    public function period(): PeriodId
    {
        return $this->period;
    }
}
