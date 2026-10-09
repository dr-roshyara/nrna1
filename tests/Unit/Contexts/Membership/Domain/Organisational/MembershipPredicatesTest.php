<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Interval;
use App\Contexts\Membership\Domain\Organisational\IntervalSet;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * Slice 0 contract §13 (Member-level predicates), §17 (re-entry), §11 (KnownAt). PBDIGIT-258.
 */
final class MembershipPredicatesTest extends TestCase
{
    private static function t(int $o): TimePoint
    {
        return TimePoint::of($o);
    }

    private static function iv(int $a, int $b): Interval
    {
        return Interval::of(self::t($a), self::t($b));
    }

    /** A1 [1,5) term to 5; A2 [5,∞) term to 9 (seamless re-entry); A3 from 12, term to 15. */
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

    public function test_seamless_re_entry_gives_adjacent_periods(): void
    {
        $r = self::threePeriods()->member->resolve();

        $this->assertTrue(IntervalSet::of(self::iv(1, 5))->equals($r->period(PeriodId::fromString('A1'))->lifetime()));
        $this->assertTrue(IntervalSet::of(self::iv(5, 10))->equals($r->period(PeriodId::fromString('A2'))->lifetime()));
    }

    public function test_the_current_period_is_the_one_whose_lifetime_contains_the_time(): void
    {
        $r = self::threePeriods()->member->resolve();

        $this->assertSame('A1', $r->currentPeriod(self::t(4))?->value());
        $this->assertSame('A2', $r->currentPeriod(self::t(5))?->value());
        $this->assertNull($r->currentPeriod(self::t(11)), 'between periods there is none');
        $this->assertSame('A3', $r->currentPeriod(self::t(40))?->value());
        $this->assertNull($r->currentPeriod(self::t(0)));
    }

    public function test_first_and_current_admission(): void
    {
        $r = self::threePeriods()->member->resolve();

        $this->assertTrue(self::t(1)->equals($r->firstAdmission()));
        $this->assertTrue(self::t(5)->equals($r->currentAdmission(self::t(7))));
        $this->assertNull($r->currentAdmission(self::t(11)));
        $this->assertNull(MemberHistory::start()->member->resolve()->firstAdmission());
    }

    public function test_continuous_membership_spans_seamless_periods_and_stops_at_a_lapse(): void
    {
        $r = self::threePeriods()->member->resolve();

        $this->assertTrue(self::iv(1, 9)->equals($r->continuousMembership(self::t(6))), 'A1 term [1,5) + A2 term [5,9)');
        $this->assertNull($r->continuousMembership(self::t(9)), 'lapsed at 9: in the lifetime of A2, not a member');
        $this->assertTrue(self::iv(12, 15)->equals($r->continuousMembership(self::t(13))));
    }

    public function test_cumulative_membership_up_to_and_including_a_time(): void
    {
        $r = self::threePeriods()->member->resolve();

        $this->assertTrue(IntervalSet::of(self::iv(1, 9), self::iv(12, 14))->equals($r->cumulativeMembership(self::t(13))));
        $this->assertTrue(IntervalSet::of(self::iv(1, 2))->equals($r->cumulativeMembership(self::t(1))), 'includes t itself');
    }

    public function test_known_at_shows_only_facts_recorded_up_to_that_instant(): void
    {
        $member = self::threePeriods()->member;

        $this->assertCount(0, $member->knownAt(Instant::of(100))->periods());
        $this->assertCount(1, $member->knownAt(Instant::of(101))->periods());
        $this->assertTrue($member->knownAt(Instant::of(101))->period(PeriodId::fromString('A1'))->baseClaim()->isOpenEnded(), 'the End was recorded later');
        $this->assertFalse($member->knownAt(Instant::of(102))->period(PeriodId::fromString('A1'))->baseClaim()->isOpenEnded());
        $this->assertCount(3, $member->knownAt(Instant::of(999))->periods());
    }

    public function test_a_backdated_end_is_known_only_after_it_was_recorded(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 1, 50), 200)->commit($h->end('E1', 'A1', 3), 900);

        $this->assertTrue($h->member->knownAt(Instant::of(500))->isMember(self::t(10)), 'what we knew at 500');
        $this->assertFalse($h->member->knownAt(Instant::of(900))->isMember(self::t(10)), 'what we know now');
    }
}
