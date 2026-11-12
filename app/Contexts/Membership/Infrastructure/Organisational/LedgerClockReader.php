<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Domain\Organisational\Instant;
use Illuminate\Database\Connection;

/**
 * The ledger's one clock (D-209; PBDIGIT-343 plan D-1): the current instant of the database behind $db, in epoch
 * microseconds. Production has exactly one implementation, PostgresLedgerClockReader (architecture test); scripted
 * readers exist only in tests. Read it after taking the organisation's clock-row lock, inside the transaction.
 */
interface LedgerClockReader
{
    public function now(Connection $db): Instant;
}
