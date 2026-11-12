<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Infrastructure\Organisational\InstantMicros;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresLedgerClockReader;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-343 task 3 (plan D-1; D-209): the reader returns the database clock of the connection it is given.
 */
final class PostgresLedgerClockReaderTest extends MembershipPostgresTestCase
{
    private static function databaseNow(): int
    {
        return (int) DB::selectOne('SELECT ' . InstantMicros::selectExpression('clock_timestamp()') . ' AS us')->us;
    }

    public function test_it_returns_the_database_clock_reading(): void
    {
        $before = self::databaseNow();
        $reading = (new PostgresLedgerClockReader())->now(DB::connection());
        $after = self::databaseNow();

        $this->assertGreaterThanOrEqual($before, $reading->ordinal());
        $this->assertLessThanOrEqual($after, $reading->ordinal());
    }

    public function test_successive_readings_inside_one_transaction_advance(): void
    {
        $reader = new PostgresLedgerClockReader();

        [$first, $second] = DB::connection()->transaction(static function () use ($reader): array {
            $a = $reader->now(DB::connection());
            usleep(2000);

            return [$a, $reader->now(DB::connection())];
        });

        $this->assertGreaterThan($first->ordinal(), $second->ordinal(), 'clock_timestamp(), not now(): the transaction start time would not advance');
    }
}
