<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Committee\ValueObjects;

use App\Contexts\Membership\Domain\Committee\ValueObjects\TermPeriod;
use DateTimeImmutable;
use DomainException;
use PHPUnit\Framework\TestCase;

final class TermPeriodTest extends TestCase
{
    private const FIXED_NOW = '2026-06-15T12:00:00Z';

    public function test_it_accepts_valid_period(): void
    {
        $start = new DateTimeImmutable('2026-01-01T00:00:00Z');
        $end = new DateTimeImmutable('2026-12-31T23:59:59Z');

        $period = TermPeriod::from($start, $end);

        $this->assertEquals($start, $period->start());
        $this->assertEquals($end, $period->end());
    }

    public function test_it_rejects_end_before_start(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Term end must be after start');

        $start = new DateTimeImmutable('2026-12-31T00:00:00Z');
        $end = new DateTimeImmutable('2026-01-01T00:00:00Z');

        TermPeriod::from($start, $end);
    }

    public function test_it_rejects_equal_start_and_end(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Term end must be after start');

        $time = new DateTimeImmutable('2026-06-15T12:00:00Z');

        TermPeriod::from($time, $time);
    }

    public function test_it_is_active_between_boundaries(): void
    {
        $start = new DateTimeImmutable('2026-01-01T00:00:00Z');
        $end = new DateTimeImmutable('2026-12-31T23:59:59Z');
        $now = new DateTimeImmutable(self::FIXED_NOW);

        $period = TermPeriod::from($start, $end);

        $this->assertTrue($period->isActive($now));
    }

    public function test_it_is_active_at_start_boundary(): void
    {
        $start = new DateTimeImmutable('2026-06-15T12:00:00Z');
        $end = new DateTimeImmutable('2026-12-31T23:59:59Z');
        $now = new DateTimeImmutable(self::FIXED_NOW);

        $period = TermPeriod::from($start, $end);

        $this->assertTrue($period->isActive($now));
    }

    public function test_it_is_active_at_end_boundary(): void
    {
        $start = new DateTimeImmutable('2026-01-01T00:00:00Z');
        $end = new DateTimeImmutable('2026-06-15T12:00:00Z');
        $now = new DateTimeImmutable(self::FIXED_NOW);

        $period = TermPeriod::from($start, $end);

        $this->assertTrue($period->isActive($now));
    }

    public function test_it_is_inactive_before_start(): void
    {
        $start = new DateTimeImmutable('2026-06-16T00:00:00Z');
        $end = new DateTimeImmutable('2026-12-31T23:59:59Z');
        $now = new DateTimeImmutable(self::FIXED_NOW);

        $period = TermPeriod::from($start, $end);

        $this->assertFalse($period->isActive($now));
    }

    public function test_it_is_inactive_after_end(): void
    {
        $start = new DateTimeImmutable('2026-01-01T00:00:00Z');
        $end = new DateTimeImmutable('2026-06-14T23:59:59Z');
        $now = new DateTimeImmutable(self::FIXED_NOW);

        $period = TermPeriod::from($start, $end);

        $this->assertFalse($period->isActive($now));
    }

    public function test_it_supports_value_equality(): void
    {
        $start = new DateTimeImmutable('2026-01-01T00:00:00Z');
        $end = new DateTimeImmutable('2026-12-31T23:59:59Z');

        $period1 = TermPeriod::from($start, $end);
        $period2 = TermPeriod::from($start, $end);

        $this->assertTrue($period1->equals($period2));
    }

    public function test_it_detects_unequal_periods(): void
    {
        $start1 = new DateTimeImmutable('2026-01-01T00:00:00Z');
        $end1 = new DateTimeImmutable('2026-12-31T23:59:59Z');

        $start2 = new DateTimeImmutable('2026-01-01T00:00:00Z');
        $end2 = new DateTimeImmutable('2026-06-30T23:59:59Z');

        $period1 = TermPeriod::from($start1, $end1);
        $period2 = TermPeriod::from($start2, $end2);

        $this->assertFalse($period1->equals($period2));
    }

    public function test_it_is_immutable(): void
    {
        $start = new DateTimeImmutable('2026-01-01T00:00:00Z');
        $end = new DateTimeImmutable('2026-12-31T23:59:59Z');

        $period = TermPeriod::from($start, $end);

        $originalStart = $period->start();
        $modifiedStart = $originalStart->modify('+1 day');

        $this->assertNotEquals($modifiedStart, $period->start());
        $this->assertEquals($originalStart, $period->start());
    }
}
