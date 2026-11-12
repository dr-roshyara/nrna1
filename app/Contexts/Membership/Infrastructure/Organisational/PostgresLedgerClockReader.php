<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Domain\Organisational\Instant;
use Illuminate\Database\Connection;

/**
 * D-209: record time is clock_timestamp() of the organisation's database, read on the given connection. Not now(),
 * which is the transaction's start time and does not advance inside a transaction.
 */
final class PostgresLedgerClockReader implements LedgerClockReader
{
    public function now(Connection $db): Instant
    {
        return Instant::of((int) $db->selectOne('SELECT ' . InstantMicros::selectExpression('clock_timestamp()') . ' AS us')->us);
    }
}
