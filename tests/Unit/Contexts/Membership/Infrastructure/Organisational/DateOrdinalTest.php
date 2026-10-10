<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\DateOrdinal;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-264 F-1: a valid-time ordinal outside 0001-01-01 … 9999-12-31 must be refused before
 * any write, because a five-digit year cannot be read back and makes the Member unloadable.
 * Ordinal = days since 1970-01-01 (proleptic Gregorian): 9999-12-31 = 2932896, 0001-01-01 = −719162.
 */
final class DateOrdinalTest extends TestCase
{
    public function test_the_last_supported_date_round_trips(): void
    {
        $this->assertSame('9999-12-31', DateOrdinal::toDate(TimePoint::of(2_932_896)));
        $this->assertSame(2_932_896, DateOrdinal::fromDate('9999-12-31')->ordinal());
    }

    public function test_the_first_supported_date_round_trips(): void
    {
        $this->assertSame('0001-01-01', DateOrdinal::toDate(TimePoint::of(-719_162)));
        $this->assertSame(-719_162, DateOrdinal::fromDate('0001-01-01')->ordinal());
    }

    public function test_a_date_after_9999_12_31_is_refused(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        DateOrdinal::toDate(TimePoint::of(2_932_897));
    }

    public function test_a_date_before_0001_01_01_is_refused(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        DateOrdinal::toDate(TimePoint::of(-719_163));
    }

    public function test_an_extreme_ordinal_is_refused_before_date_arithmetic(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        DateOrdinal::toDate(TimePoint::of(PHP_INT_MAX));
    }
}
