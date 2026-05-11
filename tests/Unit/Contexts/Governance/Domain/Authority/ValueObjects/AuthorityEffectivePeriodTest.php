<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\Authority\ValueObjects;

use App\Contexts\Governance\Domain\Authority\ValueObjects\AuthorityEffectivePeriod;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class AuthorityEffectivePeriodTest extends TestCase
{
    private DateTimeImmutable $start;

    protected function setUp(): void
    {
        $this->start = new DateTimeImmutable('2026-05-09T00:00:00Z');
    }

    public function test_open_ended_period_is_active_after_start(): void
    {
        $period = AuthorityEffectivePeriod::openEnded($this->start);
        $afterStart = new DateTimeImmutable('2030-01-01T00:00:00Z');

        $this->assertTrue($period->isActive($afterStart));
    }

    public function test_open_ended_period_is_not_active_before_start(): void
    {
        $period = AuthorityEffectivePeriod::openEnded($this->start);
        $beforeStart = new DateTimeImmutable('2025-01-01T00:00:00Z');

        $this->assertFalse($period->isActive($beforeStart));
    }

    public function test_open_ended_period_has_null_end(): void
    {
        $period = AuthorityEffectivePeriod::openEnded($this->start);
        $this->assertNull($period->end());
    }

    public function test_bounded_period_is_active_within_range(): void
    {
        $end = new DateTimeImmutable('2027-05-09T00:00:00Z');
        $period = AuthorityEffectivePeriod::bounded($this->start, $end);
        $during = new DateTimeImmutable('2026-12-01T00:00:00Z');

        $this->assertTrue($period->isActive($during));
    }

    public function test_bounded_period_is_not_active_after_end(): void
    {
        $end = new DateTimeImmutable('2027-05-09T00:00:00Z');
        $period = AuthorityEffectivePeriod::bounded($this->start, $end);
        $after = new DateTimeImmutable('2028-01-01T00:00:00Z');

        $this->assertFalse($period->isActive($after));
    }

    public function test_bounded_period_is_active_exactly_at_end(): void
    {
        $end = new DateTimeImmutable('2027-05-09T00:00:00Z');
        $period = AuthorityEffectivePeriod::bounded($this->start, $end);

        $this->assertTrue($period->isActive($end));
    }

    public function test_bounded_end_equal_to_start_throws_domain_exception(): void
    {
        $this->expectException(\DomainException::class);
        AuthorityEffectivePeriod::bounded($this->start, $this->start);
    }

    public function test_bounded_end_before_start_throws_domain_exception(): void
    {
        $this->expectException(\DomainException::class);
        $before = new DateTimeImmutable('2025-01-01T00:00:00Z');
        AuthorityEffectivePeriod::bounded($this->start, $before);
    }

    public function test_equals_same_open_ended_period(): void
    {
        $a = AuthorityEffectivePeriod::openEnded($this->start);
        $b = AuthorityEffectivePeriod::openEnded($this->start);

        $this->assertTrue($a->equals($b));
    }

    public function test_equals_same_bounded_period(): void
    {
        $end = new DateTimeImmutable('2027-05-09T00:00:00Z');
        $a = AuthorityEffectivePeriod::bounded($this->start, $end);
        $b = AuthorityEffectivePeriod::bounded($this->start, $end);

        $this->assertTrue($a->equals($b));
    }

    public function test_not_equals_different_end(): void
    {
        $end1 = new DateTimeImmutable('2027-05-09T00:00:00Z');
        $end2 = new DateTimeImmutable('2028-05-09T00:00:00Z');

        $a = AuthorityEffectivePeriod::bounded($this->start, $end1);
        $b = AuthorityEffectivePeriod::bounded($this->start, $end2);

        $this->assertFalse($a->equals($b));
    }

    public function test_open_ended_not_equals_bounded(): void
    {
        $end = new DateTimeImmutable('2027-05-09T00:00:00Z');
        $a = AuthorityEffectivePeriod::openEnded($this->start);
        $b = AuthorityEffectivePeriod::bounded($this->start, $end);

        $this->assertFalse($a->equals($b));
    }

    public function test_start_accessor(): void
    {
        $period = AuthorityEffectivePeriod::openEnded($this->start);
        $this->assertSame($this->start, $period->start());
    }

    public function test_is_immutable(): void
    {
        $reflection = new \ReflectionClass(AuthorityEffectivePeriod::class);
        $this->assertTrue($reflection->isReadOnly(), 'AuthorityEffectivePeriod must be readonly class');
    }
}
