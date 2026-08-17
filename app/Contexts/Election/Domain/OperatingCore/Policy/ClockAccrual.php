<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Policy;

use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;

/**
 * P-5 `ClockAccrual` (stateless): elapsed time is the INTERVAL SUM while the
 * triggering condition was active — a computation over recorded condition
 * intervals, never a ticking counter (DD-1; EM-GOV-062, 060, 061). Because
 * *operative* and *Inoperative* are mutually exclusive recorded conditions, two
 * clocks derived from one fact stream can never accrue over the same instant —
 * by construction, not by coordination.
 */
final class ClockAccrual
{
    private function __construct()
    {
    }

    /**
     * @param list<array{int, int|null}> $intervals recorded [activeFromEpoch, activeUntilEpoch|null]
     */
    public static function elapsedSeconds(array $intervals, RecordedInstant $at): int
    {
        $elapsed = 0;
        foreach ($intervals as [$from, $until]) {
            $effectiveUntil = $until === null ? $at->epochSeconds : min($until, $at->epochSeconds);
            $elapsed += max(0, $effectiveUntil - $from);
        }

        return $elapsed;
    }
}
