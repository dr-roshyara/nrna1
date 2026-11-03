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
 * Correction slice C-2, step 6: BD-REV-ER = refuse (PO 2026-11-02). The enforcement set gains ER (canonical §6;
 * decision record §3): no live admission may be left with a non-empty BaseClaim and an empty lifetime
 * (`PeriodAbsorbed`). Such a period is removed only by annulling or voiding its Admit. PBDIGIT-327.
 *
 * Every history's outcome was computed by the oracle port first (refuse_absorption = true).
 */
final class CorrectAbsorptionTest extends TestCase
{
    // Contract §9 minimal case: Admit(p0@0), End(p0@1), Admit(p1@1), Correct(Admit p1 → 0) would erase p0.
    public function test_a_correction_that_absorbs_another_period_is_refused(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A0', 0, null, true));
        $h->commit($h->end('E0', 'A0', 1));
        $h->commit($h->admit('A1', 1, null, true));
        $logBefore = $h->member->log();

        $this->assertSame(RefusalReason::PeriodAbsorbed, $h->correct('C1', 'A1', 0)->refusal());
        $this->assertSame($logBefore, $h->member->log(), 'nothing appended');
    }

    // An enlarging End correction that would swallow the whole next period is refused the same way.
    public function test_an_end_correction_that_absorbs_the_next_period_is_refused(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A0', 0, null, true));
        $h->commit($h->end('E0', 'A0', 2));
        $h->commit($h->admit('A1', 2, null, true));
        $h->commit($h->end('E1', 'A1', 3));

        $this->assertSame(RefusalReason::PeriodAbsorbed, $h->correct('C1', 'E0', 4)->refusal());
    }

    // Decision record §2: the other period is removed only by explicitly setting its Admit aside; then the correction is accepted.
    public function test_after_the_other_admission_is_annulled_the_correction_is_accepted(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A0', 0, null, true));
        $h->commit($h->end('E0', 'A0', 1));
        $h->commit($h->admit('A1', 1, null, true));
        $h->commit($h->annul('X1', 'A0'));

        $decision = $h->correct('C1', 'A1', 0);
        $this->assertTrue($decision->isAccepted());
        $h->commit($decision);
        $this->assertTrue($h->member->resolve()->isMember(TimePoint::of(0)));
    }

    // ER is an invariant of G* (canonical §6), so it holds for every decision: an End that leaves its own period
    // entirely inside an existing trim is refused (oracle: 21 such End refusals at L = 5); ending after the trim is not.
    public function test_an_end_inside_its_own_period_trim_is_refused(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A0', 0, null, true));
        $h->commit($h->end('E0', 'A0', 2));
        $h->commit($h->admit('A1', 2, null, true));
        $h->commit($h->correct('C1', 'E0', 4));

        $this->assertSame(RefusalReason::PeriodAbsorbed, $h->end('E1', 'A1', 4)->refusal());
        $this->assertTrue($h->end('E1', 'A1', 5)->isAccepted());
    }

    // ER is checked last: on a state that also violates INV-9, INV-EP or INV-COV, those are reported first.
    // (No such state is reachable in the bounded space; this pins the order of the enforcement set itself.)
    public function test_absorption_is_reported_after_the_other_invariants(): void
    {
        $absorbed = new ResolvedPeriod(PeriodId::fromString('P0'), self::interval(0, 2), IntervalSet::empty(), IntervalSet::empty());
        $overlapA = new ResolvedPeriod(PeriodId::fromString('P1'), self::interval(3, 6), IntervalSet::of(self::interval(3, 6)), IntervalSet::empty());
        $overlapB = new ResolvedPeriod(PeriodId::fromString('P2'), self::interval(5, 8), IntervalSet::of(self::interval(5, 8)), IntervalSet::empty());

        $violations = InvariantSet::violations(new ResolvedMember([$absorbed, $overlapA, $overlapB]));

        $this->assertSame(RefusalReason::OverlappingMembershipPeriods, $violations[0]);
        $this->assertSame(RefusalReason::PeriodAbsorbed, end($violations));
    }

    // A period whose BaseClaim is itself empty is not absorbed (nothing was taken from it).
    public function test_a_period_with_an_empty_claim_is_not_absorbed(): void
    {
        $empty = new ResolvedPeriod(PeriodId::fromString('P0'), self::interval(2, 2), IntervalSet::empty(), IntervalSet::empty());

        $this->assertSame([], InvariantSet::violations(new ResolvedMember([$empty])));
    }

    private static function interval(int $from, int $until): Interval
    {
        return Interval::of(TimePoint::of($from), TimePoint::of($until));
    }
}
