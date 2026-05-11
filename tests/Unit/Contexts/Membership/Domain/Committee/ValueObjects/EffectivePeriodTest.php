<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Committee\ValueObjects;

use App\Contexts\Membership\Domain\Committee\ValueObjects\EffectivePeriod;
use DateTimeImmutable;
use DomainException;
use PHPUnit\Framework\TestCase;

final class EffectivePeriodTest extends TestCase
{
    private const FIXED_NOW = '2026-06-15T12:00:00Z';

    public function test_it_accepts_open_ended_period(): void
    {
        $start = new DateTimeImmutable('2026-01-01T00:00:00Z');

        $period = EffectivePeriod::openEnded($start);

        $this->assertEquals($start, $period->start());
        $this->assertNull($period->end());
    }

    public function test_it_accepts_bounded_period(): void
    {
        $start = new DateTimeImmutable('2026-01-01T00:00:00Z');
        $end = new DateTimeImmutable('2026-12-31T23:59:59Z');

        $period = EffectivePeriod::bounded($start, $end);

        $this->assertEquals($start, $period->start());
        $this->assertEquals($end, $period->end());
    }

    public function test_it_rejects_bounded_period_with_end_before_start(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Period end must be after start');

        $start = new DateTimeImmutable('2026-12-31T00:00:00Z');
        $end = new DateTimeImmutable('2026-01-01T00:00:00Z');

        EffectivePeriod::bounded($start, $end);
    }

    public function test_it_rejects_bounded_period_with_equal_start_and_end(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Period end must be after start');

        $time = new DateTimeImmutable('2026-06-15T12:00:00Z');

        EffectivePeriod::bounded($time, $time);
    }

    public function test_it_detects_active_open_ended_period(): void
    {
        $start = new DateTimeImmutable('2026-01-01T00:00:00Z');
        $now = new DateTimeImmutable(self::FIXED_NOW);

        $period = EffectivePeriod::openEnded($start);

        $this->assertTrue($period->isActive($now));
    }

    public function test_it_detects_active_bounded_period(): void
    {
        $start = new DateTimeImmutable('2026-01-01T00:00:00Z');
        $end = new DateTimeImmutable('2026-12-31T23:59:59Z');
        $now = new DateTimeImmutable(self::FIXED_NOW);

        $period = EffectivePeriod::bounded($start, $end);

        $this->assertTrue($period->isActive($now));
    }

    public function test_it_detects_expired_bounded_period(): void
    {
        $start = new DateTimeImmutable('2026-01-01T00:00:00Z');
        $end = new DateTimeImmutable('2026-06-14T23:59:59Z');
        $now = new DateTimeImmutable(self::FIXED_NOW);

        $period = EffectivePeriod::bounded($start, $end);

        $this->assertFalse($period->isActive($now));
    }

    public function test_it_detects_future_bounded_period(): void
    {
        $start = new DateTimeImmutable('2026-06-16T00:00:00Z');
        $end = new DateTimeImmutable('2026-12-31T23:59:59Z');
        $now = new DateTimeImmutable(self::FIXED_NOW);

        $period = EffectivePeriod::bounded($start, $end);

        $this->assertFalse($period->isActive($now));
    }

    public function test_it_supports_value_equality(): void
    {
        $start = new DateTimeImmutable('2026-01-01T00:00:00Z');
        $end = new DateTimeImmutable('2026-12-31T23:59:59Z');

        $period1 = EffectivePeriod::bounded($start, $end);
        $period2 = EffectivePeriod::bounded($start, $end);

        $this->assertTrue($period1->equals($period2));
    }

    public function test_it_detects_unequal_periods(): void
    {
        $start = new DateTimeImmutable('2026-01-01T00:00:00Z');
        $end1 = new DateTimeImmutable('2026-12-31T23:59:59Z');
        $end2 = new DateTimeImmutable('2026-06-30T23:59:59Z');

        $period1 = EffectivePeriod::bounded($start, $end1);
        $period2 = EffectivePeriod::bounded($start, $end2);

        $this->assertFalse($period1->equals($period2));
    }

    public function test_it_detects_unequal_open_ended_vs_bounded(): void
    {
        $start = new DateTimeImmutable('2026-01-01T00:00:00Z');

        $openEnded = EffectivePeriod::openEnded($start);
        $bounded = EffectivePeriod::bounded($start, new DateTimeImmutable('2026-12-31T23:59:59Z'));

        $this->assertFalse($openEnded->equals($bounded));
    }

    public function test_it_is_immutable(): void
    {
        $start = new DateTimeImmutable('2026-01-01T00:00:00Z');
        $end = new DateTimeImmutable('2026-12-31T23:59:59Z');

        $period = EffectivePeriod::bounded($start, $end);

        $originalStart = $period->start();
        $modifiedStart = $originalStart->modify('+1 day');

        $this->assertNotEquals($modifiedStart, $period->start());
        $this->assertEquals($originalStart, $period->start());
    }
}
