<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Infrastructure\Organisational\LedgerClockReader;
use Illuminate\Database\Connection;

/**
 * TEST ONLY (PBDIGIT-343 plan D-1): a ledger clock whose readings a test scripts, for regression and equality cases
 * that a real database clock cannot produce on demand. Never used in production (architecture test).
 */
final class ScriptedLedgerClockReader implements LedgerClockReader
{
    /** @param \Closure(): int $readings the next reading, in epoch microseconds */
    public function __construct(private readonly \Closure $readings)
    {
    }

    public function now(Connection $db): Instant
    {
        return Instant::of(($this->readings)());
    }
}
