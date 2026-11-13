<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

/**
 * v3.11 §6: the clock reading under the lock is at or below closed_through_time, or below the previous recorded_at.
 * Refused, never adjusted: nothing was written; retry later (CLOCK_NOT_PAST_BOUNDARY).
 */
final class ClockNotPastBoundary extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('CLOCK_NOT_PAST_BOUNDARY: the database clock is not past the boundary; nothing was written.');
    }
}
