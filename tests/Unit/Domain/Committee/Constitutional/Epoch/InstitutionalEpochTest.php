<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Epoch;

use App\Contexts\Membership\Domain\Committee\Constitutional\Epoch\InstitutionalEpoch;
use PHPUnit\Framework\TestCase;

class InstitutionalEpochTest extends TestCase
{
    /**
     * @test
     * Epoch is active within temporal window
     */
    public function test_epoch_is_active_within_window(): void
    {
        $epoch = new InstitutionalEpoch(
            epochId: 'epoch-2026',
            name: 'Constitutional Period 2026',
            startedAt: new \DateTimeImmutable('2026-01-01T00:00:00Z'),
            endedAt: new \DateTimeImmutable('2026-12-31T23:59:59Z'),
            constitutionReference: 'Constitution Article 5'
        );

        $now = new \DateTimeImmutable('2026-06-15T12:00:00Z');
        $this->assertTrue($epoch->isActiveAt($now));
    }

    /**
     * @test
     * Epoch is not active before start date
     */
    public function test_epoch_is_not_active_before_start(): void
    {
        $epoch = new InstitutionalEpoch(
            epochId: 'epoch-2026',
            name: 'Constitutional Period 2026',
            startedAt: new \DateTimeImmutable('2026-06-01T00:00:00Z'),
            endedAt: new \DateTimeImmutable('2026-12-31T23:59:59Z'),
            constitutionReference: null
        );

        $before = new \DateTimeImmutable('2026-05-15T12:00:00Z');
        $this->assertFalse($epoch->isActiveAt($before));
    }

    /**
     * @test
     * Open epoch (no end date) is active in future
     */
    public function test_open_epoch_has_no_end_date(): void
    {
        $epoch = new InstitutionalEpoch(
            epochId: 'epoch-current',
            name: 'Current Constitutional Period',
            startedAt: new \DateTimeImmutable('2026-01-01T00:00:00Z'),
            endedAt: null,
            constitutionReference: 'Constitution Article 1'
        );

        $this->assertTrue($epoch->isOpen());
        $this->assertTrue($epoch->isActiveAt(new \DateTimeImmutable('2099-12-31T12:00:00Z')));
    }
}
