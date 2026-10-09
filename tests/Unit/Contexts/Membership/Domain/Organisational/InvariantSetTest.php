<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\Interval;
use App\Contexts\Membership\Domain\Organisational\IntervalSet;
use App\Contexts\Membership\Domain\Organisational\InvariantSet;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\ResolvedMember;
use App\Contexts\Membership\Domain\Organisational\ResolvedPeriod;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * Slice 0 contract §14: the invariant checks G* applies to a resolved candidate.
 *
 * In Slice 0, INV-EP and INV-COV can never be violated by a reachable state
 * (contract Lemma 3: no consequences). They are tested here on hand-built
 * resolved states, so that the checks are real before Slice 7 makes them reachable.
 * PBDIGIT-258.
 */
final class InvariantSetTest extends TestCase
{
    private static function iv(int $a, ?int $b): Interval
    {
        return Interval::of(TimePoint::of($a), $b === null ? TimePoint::infinity() : TimePoint::of($b));
    }

    private static function period(string $id, Interval $base, IntervalSet $lifetime): ResolvedPeriod
    {
        return new ResolvedPeriod(PeriodId::fromString($id), $base, $lifetime, IntervalSet::empty());
    }

    public function test_a_valid_state_has_no_violations(): void
    {
        $state = new ResolvedMember([
            self::period('A1', self::iv(1, 5), IntervalSet::of(self::iv(1, 5))),
            self::period('A2', self::iv(5, null), IntervalSet::of(self::iv(5, null))),
        ]);

        $this->assertSame([], InvariantSet::violations($state));
    }

    public function test_inv_9_overlapping_lifetimes(): void
    {
        $state = new ResolvedMember([
            self::period('A1', self::iv(1, 6), IntervalSet::of(self::iv(1, 6))),
            self::period('A2', self::iv(5, null), IntervalSet::of(self::iv(5, null))),
        ]);

        $this->assertSame([RefusalReason::OverlappingMembershipPeriods], InvariantSet::violations($state));
    }

    public function test_inv_ep_a_period_lifetime_must_be_one_contiguous_interval(): void
    {
        $state = new ResolvedMember([
            self::period('A1', self::iv(1, 9), IntervalSet::of(self::iv(1, 3), self::iv(5, 9))),
        ]);

        $this->assertContains(RefusalReason::PeriodLifetimeNotContiguous, InvariantSet::violations($state));
    }

    public function test_inv_cov_lifetimes_must_cover_what_the_base_claims_cover(): void
    {
        $state = new ResolvedMember([
            self::period('A1', self::iv(1, 9), IntervalSet::of(self::iv(1, 4))),
        ]);

        $this->assertContains(RefusalReason::MembershipCoverageLost, InvariantSet::violations($state));
    }

    public function test_a_lifetime_moved_to_another_period_keeps_coverage(): void
    {
        // Consequences move ownership of an instant, never membership itself (INV-COV).
        $state = new ResolvedMember([
            self::period('A1', self::iv(1, 9), IntervalSet::of(self::iv(1, 4))),
            self::period('A2', self::iv(4, null), IntervalSet::of(self::iv(4, null))),
        ]);

        $this->assertSame([], InvariantSet::violations($state));
    }

    public function test_every_reachable_slice_0_state_satisfies_inv_ep_and_inv_cov(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 1, 10))->commit($h->end('E1', 'A1', 4))->commit($h->admit('A2', 6));

        $this->assertSame([], InvariantSet::violations($h->member->resolve()));
    }
}
