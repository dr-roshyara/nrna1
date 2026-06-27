<?php

namespace App\Infrastructure\Shared\Clock;

use App\Domain\Shared\Clock\ClockInterface;

/**
 * SystemClock — Production Temporal Evidence
 *
 * Returns current system time for production evaluation.
 * Used in live voting contexts where current moment matters.
 *
 * Priority 3: Production Clock Implementation
 */
final class SystemClock implements ClockInterface
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
    }
}
