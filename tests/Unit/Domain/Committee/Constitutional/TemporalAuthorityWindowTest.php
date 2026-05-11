<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\TemporalAuthorityWindow;
use App\Contexts\Membership\Domain\Committee\Constitutional\TemporalWindowState;

final class TemporalAuthorityWindowTest extends TestCase
{
    public function test_open_ended_window_is_active_after_start(): void
    {
        $now = new \DateTimeImmutable('2026-05-08T10:00:00Z');
        $future = $now->modify('+1 day');

        $window = new TemporalAuthorityWindow(
            validFrom: $now,
            validUntil: null,
        );

        $this->assertSame(TemporalWindowState::ACTIVE, $window->stateAt($future));
        $this->assertTrue($window->isActiveAt($future));
    }

    public function test_window_is_pending_before_start(): void
    {
        $now = new \DateTimeImmutable('2026-05-08T10:00:00Z');
        $tomorrow = $now->modify('+1 day');
        $yesterday = $now->modify('-1 day');

        $window = new TemporalAuthorityWindow(
            validFrom: $tomorrow,
            validUntil: null,
        );

        $this->assertSame(TemporalWindowState::PENDING, $window->stateAt($now));
        $this->assertTrue($window->isPendingAt($now));
        $this->assertFalse($window->isActiveAt($now));
    }

    public function test_window_is_expired_after_end(): void
    {
        $now = new \DateTimeImmutable('2026-05-08T10:00:00Z');
        $yesterday = $now->modify('-1 day');
        $dayBeforeYesterday = $now->modify('-2 days');

        $window = new TemporalAuthorityWindow(
            validFrom: $dayBeforeYesterday,
            validUntil: $yesterday,
        );

        $this->assertSame(TemporalWindowState::EXPIRED, $window->stateAt($now));
        $this->assertTrue($window->isExpiredAt($now));
        $this->assertFalse($window->isActiveAt($now));
    }

    public function test_boundary_dates_are_active(): void
    {
        $start = new \DateTimeImmutable('2026-05-01T00:00:00Z');
        $end = new \DateTimeImmutable('2026-05-31T23:59:59Z');

        $window = new TemporalAuthorityWindow(
            validFrom: $start,
            validUntil: $end,
        );

        $this->assertSame(TemporalWindowState::ACTIVE, $window->stateAt($start));
        $this->assertSame(TemporalWindowState::ACTIVE, $window->stateAt($end));
        $this->assertTrue($window->isActiveAt($start));
        $this->assertTrue($window->isActiveAt($end));
    }

    public function test_invalid_window_throws_when_end_before_start(): void
    {
        $start = new \DateTimeImmutable('2026-05-10T00:00:00Z');
        $end = new \DateTimeImmutable('2026-05-01T00:00:00Z');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('validUntil cannot be earlier than validFrom');

        new TemporalAuthorityWindow(
            validFrom: $start,
            validUntil: $end,
        );
    }
}
