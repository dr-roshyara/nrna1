<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Timeline;

use App\Contexts\Membership\Domain\Committee\Constitutional\Timeline\DecidedAtSequencePolicy;
use App\Contexts\Membership\Domain\Committee\Constitutional\Timeline\PersistedAtSequencePolicy;
use PHPUnit\Framework\TestCase;

class TimelineSequencePolicyTest extends TestCase
{
    /**
     * @test
     * DecidedAtSequencePolicy orders earlier decision first
     */
    public function test_decided_at_policy_orders_earlier_decision_first(): void
    {
        $policy = new DecidedAtSequencePolicy();

        $earlier = new \DateTimeImmutable('2026-01-01T10:00:00Z');
        $later = new \DateTimeImmutable('2026-01-02T10:00:00Z');

        $result = $policy->compare($earlier, $later);

        $this->assertLessThan(0, $result);
    }

    /**
     * @test
     * PersistedAtSequencePolicy orders by persistence time
     */
    public function test_persisted_at_policy_orders_by_persistence_time(): void
    {
        $policy = new PersistedAtSequencePolicy();

        $earlier = new \DateTimeImmutable('2026-01-01T10:00:00Z');
        $later = new \DateTimeImmutable('2026-01-02T10:00:00Z');

        $result = $policy->compare($earlier, $later);

        $this->assertLessThan(0, $result);
    }

    /**
     * @test
     * Same timestamp produces zero comparison
     */
    public function test_same_timestamp_produces_zero(): void
    {
        $policy = new DecidedAtSequencePolicy();

        $timestamp = new \DateTimeImmutable('2026-01-01T10:00:00Z');

        $result = $policy->compare($timestamp, $timestamp);

        $this->assertSame(0, $result);
    }
}
