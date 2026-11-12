<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Interval;
use App\Contexts\Membership\Domain\Organisational\IntervalSet;
use App\Contexts\Membership\Domain\Organisational\ResolvedMember;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * PBDIGIT-343 task 1 (plan 20261112-1251 §6 test 1): the kernel functions behind C1 / C2.
 * coverage = ⋃ period lifetimes (INV-COV, C1/C2 spec §3); inForce = ⋃ terms within lifetimes = IsMember
 * (CR-02, D-161); suspended = ⋃ restriction intervals, empty until Suspend exists (slice 5).
 */
final class CoverageAndStandingIntervalsTest extends TestCase
{
    private static function t(int $o): TimePoint
    {
        return TimePoint::of($o);
    }

    private static function iv(int $a, int $b): Interval
    {
        return Interval::of(self::t($a), self::t($b));
    }

    /** A1 [1,5) term to 5; A2 [5,10) term to 9 (lapsed on [9,10)); A3 from 12, term to 15 (lapsed from 15). */
    private static function threePeriods(): MemberHistory
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 1, 5), 101)
          ->commit($h->end('E1', 'A1', 5), 102)
          ->commit($h->admit('A2', 5, 9), 103)
          ->commit($h->end('E2', 'A2', 10), 104)
          ->commit($h->admit('A3', 12, 15), 105);

        return $h;
    }

    public function test_coverage_is_the_union_of_period_lifetimes_with_adjacent_periods_joined(): void
    {
        $coverage = self::threePeriods()->member->resolve()->coverage();

        $this->assertTrue(IntervalSet::of(self::iv(1, 10), Interval::from(self::t(12)))->equals($coverage));
    }

    public function test_a_lapsed_period_stays_inside_coverage_but_not_in_force(): void
    {
        $r = self::threePeriods()->member->resolve();

        $this->assertTrue($r->coverage()->contains(self::t(9)));
        $this->assertFalse($r->inForceIntervals()->contains(self::t(9)));
        $this->assertTrue($r->coverage()->contains(self::t(20)));
        $this->assertFalse($r->inForceIntervals()->contains(self::t(20)));
    }

    public function test_coverage_and_in_force_agree_with_the_point_predicates_everywhere(): void
    {
        $r = self::threePeriods()->member->resolve();

        for ($o = -2; $o <= 25; ++$o) {
            $this->assertSame($r->currentPeriod(self::t($o)) !== null, $r->coverage()->contains(self::t($o)), "coverage at {$o}");
            $this->assertSame($r->isMember(self::t($o)), $r->inForceIntervals()->contains(self::t($o)), "inForce at {$o}");
        }
    }

    public function test_in_force_is_always_within_coverage(): void
    {
        $r = self::threePeriods()->member->resolve();

        $this->assertTrue($r->inForceIntervals()->without($r->coverage())->isEmpty());
    }

    public function test_nobody_is_suspended_before_suspension_exists(): void
    {
        $this->assertTrue(self::threePeriods()->member->resolve()->suspendedIntervals()->isEmpty());
        $this->assertTrue((new ResolvedMember([]))->suspendedIntervals()->isEmpty());
    }

    public function test_no_membership_gives_empty_coverage_and_in_force(): void
    {
        $r = new ResolvedMember([]);

        $this->assertTrue($r->coverage()->isEmpty());
        $this->assertTrue($r->inForceIntervals()->isEmpty());
    }

    public function test_annulling_an_end_restores_the_open_coverage(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 2, null, true))->commit($h->end('E1', 'A1', 6));
        $this->assertTrue(IntervalSet::of(self::iv(2, 6))->equals($h->member->resolve()->coverage()));

        $h->commit($h->annul('X1', 'E1'));

        $this->assertTrue(IntervalSet::of(Interval::from(self::t(2)))->equals($h->member->resolve()->coverage()));
    }

    public function test_voiding_an_admission_removes_its_coverage(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 2, null, true))->commit($h->void('V1', 'A1'));

        $this->assertTrue($h->member->resolve()->coverage()->isEmpty());
    }

    public function test_correcting_an_end_moves_the_coverage_boundary(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 2, null, true))->commit($h->end('E1', 'A1', 5));

        $h->commit($h->correct('C1', 'E1', 7));

        $this->assertTrue(IntervalSet::of(self::iv(2, 7))->equals($h->member->resolve()->coverage()));
    }

    public function test_coverage_as_known_at_tau_uses_only_facts_recorded_by_tau(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 2, null, true), 100)->commit($h->end('E1', 'A1', 6), 200);

        $this->assertTrue(IntervalSet::of(Interval::from(self::t(2)))->equals($h->member->knownAt(Instant::of(150))->coverage()));
        $this->assertTrue(IntervalSet::of(self::iv(2, 6))->equals($h->member->knownAt(Instant::of(200))->coverage()));
        $this->assertTrue($h->member->knownAt(Instant::of(99))->coverage()->isEmpty());
    }
}
