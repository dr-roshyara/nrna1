<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Interval;
use App\Contexts\Membership\Domain\Organisational\IntervalSet;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\RecordedFact;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\Restore;
use App\Contexts\Membership\Domain\Organisational\Suspend;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * PBDIGIT-354 S2-2: Suspend and Restore (D-163; accepted design D-177 §2, §4, §5 rows 8–16, 15b, 18–19, 22, 23;
 * §16 C-1, C-2; D-205 (3)).
 * - Suspended(q, t) = t ∈ lifetime(q) ∧ t ∈ restrictions(q). Suspension never changes coverage, the term or IsMember.
 * - The ACT of suspending or restoring needs the period in force at its effective date; the RESTRICTION continues if
 *   the term later lapses (F-04).
 * Times are day ordinals. The fixture member: admitted at 1, period [1, ∞), term [1, 100).
 */
final class SuspendRestoreTest extends TestCase
{
    private static function t(int $o): TimePoint
    {
        return TimePoint::of($o);
    }

    private static function admitted(int $termUntil = 100): MemberHistory
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 1, $termUntil));

        return $h;
    }

    private static function suspended(MemberHistory $h, int $t): bool
    {
        return $h->member->resolve()->isSuspended(self::t($t));
    }

    private static function set(int $from, ?int $until): IntervalSet
    {
        return IntervalSet::of($until === null ? Interval::from(self::t($from)) : Interval::of(self::t($from), self::t($until)));
    }

    public function test_row9_an_open_ended_suspension_restricts_from_its_date_and_leaves_membership_alone(): void
    {
        $h = self::admitted();
        $h->commit($h->suspend('S1', 10));

        $this->assertFalse(self::suspended($h, 9));
        $this->assertTrue(self::suspended($h, 10));
        $this->assertTrue($h->member->resolve()->isMember(self::t(50)), 'S2: suspension does not change IsMember');
        $this->assertTrue(self::suspended($h, 5_000), 'F-04: the restriction continues after the term lapsed at 100');
    }

    public function test_row10_a_fixed_length_suspension_ends_by_itself(): void
    {
        $h = self::admitted();
        $h->commit($h->suspend('S1', 10, 20));

        $this->assertTrue(self::suspended($h, 19));
        $this->assertFalse(self::suspended($h, 20), 'half-open: ends at its `until` without a Restore');
    }

    public function test_row11_a_restore_ends_the_restriction_at_its_date(): void
    {
        $h = self::admitted();
        $h->commit($h->suspend('S1', 10, 20));
        $h->commit($h->restore('R1', 'S1', 15));

        $this->assertTrue(self::set(10, 15)->equals($h->member->resolve()->suspendedIntervals()));
    }

    public function test_row12_a_restore_outside_the_restriction_is_refused(): void
    {
        $h = self::admitted();
        $h->commit($h->suspend('S1', 10, 20));

        $this->assertSame(RefusalReason::OutsideRestriction, $h->restore('R1', 'S1', 20)->refusal(), 'already ended at 20');
        $this->assertSame(RefusalReason::OutsideRestriction, $h->restore('R2', 'S1', 30)->refusal());
        $this->assertSame(RefusalReason::OutsideRestriction, $h->restore('R3', 'S1', 10)->refusal(), 'not after its start: that would be an Annul or a Void');
        $this->assertSame(RefusalReason::OutsideRestriction, $h->restore('R4', 'S1', 5)->refusal());
        $this->assertTrue($h->restore('R5', 'S1', 11)->isAccepted());
    }

    public function test_row13_each_restore_ends_one_suspension_only(): void
    {
        $h = self::admitted();
        $h->commit($h->suspend('S1', 10));
        $h->commit($h->suspend('S2', 12));
        $h->commit($h->restore('R1', 'S1', 15));

        $this->assertTrue(self::suspended($h, 16), 'S2 still applies');
        $this->assertTrue(self::set(10, null)->equals($h->member->resolve()->suspendedIntervals()), '[10,15) ∪ [12,∞)');
    }

    public function test_row14_a_suspension_has_one_live_restore(): void
    {
        $h = self::admitted();
        $h->commit($h->suspend('S1', 10));
        $h->commit($h->restore('R1', 'S1', 15));

        $this->assertSame(RefusalReason::AlreadyRestored, $h->restore('R2', 'S1', 12)->refusal());
    }

    public function test_row15_suspending_a_lapsed_membership_is_refused(): void
    {
        $h = self::admitted();   // lapsed from 100

        $this->assertSame(RefusalReason::NotInForce, $h->suspend('S1', 150)->refusal(), 'nothing to restrict: the person exercises no rights');
        $this->assertSame(RefusalReason::NotInForce, $h->suspend('S2', 100)->refusal(), 'half-open: at 100 the term is over');
        $this->assertTrue($h->suspend('S3', 99)->isAccepted());
    }

    public function test_row15b_a_restore_after_the_term_lapsed_needs_a_renewal_first(): void
    {
        $h = self::admitted();
        $h->commit($h->suspend('S1', 90));                 // suspended; the term lapses at 100

        $this->assertSame(RefusalReason::NotInForce, $h->restore('R1', 'S1', 120)->refusal());

        $h->commit($h->renew('N1', 100, 200));             // renewed, back-dated to 100
        $this->assertTrue($h->restore('R2', 'S1', 120)->isAccepted());
    }

    public function test_row16_suspending_after_the_periods_end_is_refused(): void
    {
        $h = self::admitted();
        $h->commit($h->end('E1', 'A1', 50));

        $this->assertSame(RefusalReason::PeriodEnded, $h->suspend('S1', 60)->refusal());
        $this->assertSame(RefusalReason::PeriodEnded, $h->suspend('S2', 50)->refusal());
    }

    public function test_suspending_before_any_period_is_refused(): void
    {
        $this->assertSame(RefusalReason::NoPeriodAt, MemberHistory::start()->suspend('S1', 5)->refusal());
        $this->assertSame(RefusalReason::NoPeriodAt, self::admitted()->suspend('S2', 0)->refusal());
    }

    public function test_an_empty_restriction_is_refused(): void
    {
        $this->assertSame(RefusalReason::EmptyRestriction, self::admitted()->suspend('S1', 10, 10)->refusal());
        $this->assertSame(RefusalReason::EmptyRestriction, self::admitted()->suspend('S2', 10, 9)->refusal());
    }

    public function test_d205_neither_act_may_take_effect_after_its_decision_date(): void
    {
        $h = self::admitted();

        $this->assertSame(RefusalReason::EffectiveDateAfterDecisionDate, $h->suspend('S1', 10, null, 9)->refusal());
        $h->commit($h->suspend('S2', 10, null, 10));
        $this->assertSame(RefusalReason::EffectiveDateAfterDecisionDate, $h->restore('R1', 'S2', 15, 14)->refusal());
        $this->assertTrue($h->restore('R2', 'S2', 15, 15)->isAccepted());
    }

    public function test_a_fact_id_is_used_once(): void
    {
        $h = self::admitted();
        $h->commit($h->suspend('S1', 10));

        $this->assertSame(RefusalReason::DuplicateFactId, $h->suspend('A1', 12)->refusal());
        $this->assertSame(RefusalReason::DuplicateFactId, $h->restore('S1', 'S1', 12)->refusal());
    }

    public function test_row8_a_renewal_while_suspended_is_accepted_and_the_term_runs(): void
    {
        $h = self::admitted();
        $h->commit($h->suspend('S1', 90));
        $h->commit($h->renew('N1', 95, 200));

        $this->assertTrue($h->member->resolve()->isMember(self::t(150)), 'the term runs');
        $this->assertTrue(self::suspended($h, 150), 'and the member is still suspended');
    }

    public function test_a_restore_targets_a_live_suspension(): void
    {
        $h = self::admitted();
        $h->commit($h->suspend('S1', 10));

        $this->assertSame(RefusalReason::TargetNotFound, $h->restore('R1', 'NOPE', 15)->refusal());
        $this->assertSame(RefusalReason::NotSuspended, $h->restore('R2', 'A1', 15)->refusal(), 'an admission is not a suspension');

        $h->commit($h->annul('X1', 'S1'));
        $this->assertSame(RefusalReason::NotSuspended, $h->restore('R3', 'S1', 15)->refusal(), 'an annulled suspension restricts nothing');
    }

    public function test_row18_annulling_a_suspension_removes_the_restriction_and_orphans_its_restore(): void
    {
        $h = self::admitted();
        $h->commit($h->suspend('S1', 10), 2000);
        $h->commit($h->restore('R1', 'S1', 15), 3000);
        $h->commit($h->annul('X1', 'S1'), 4000);

        $this->assertTrue($h->member->resolve()->suspendedIntervals()->isEmpty(), 'the orphaned Restore has no effect');
        $this->assertTrue($h->member->knownAt(Instant::of(3500))->isSuspended(self::t(12)), 'as known before the annulment');
    }

    public function test_row19_annulling_a_restore_lets_the_suspension_run_again(): void
    {
        $h = self::admitted();
        $h->commit($h->suspend('S1', 10));
        $h->commit($h->restore('R1', 'S1', 15));
        $h->commit($h->annul('X1', 'R1'));

        $this->assertTrue(self::suspended($h, 20), 'from its start, as if never restored');
        $this->assertTrue($h->restore('R2', 'S1', 18)->isAccepted(), 'no live Restore remains, so a new one is accepted');
    }

    public function test_voiding_a_suspension_removes_it(): void
    {
        $h = self::admitted();
        $h->commit($h->suspend('S1', 10));
        $h->commit($h->void('V1', 'S1'));

        $this->assertFalse(self::suspended($h, 12));
    }

    public function test_row22_neither_fact_is_visible_before_its_record_time(): void
    {
        $h = self::admitted();
        $h->commit($h->suspend('S1', 10), 2000);
        $h->commit($h->restore('R1', 'S1', 15), 3000);

        $this->assertFalse($h->member->knownAt(Instant::of(1999))->isSuspended(self::t(12)));
        $this->assertTrue($h->member->knownAt(Instant::of(2000))->isSuspended(self::t(20)), 'known as open-ended until the Restore is recorded');
        $this->assertFalse($h->member->knownAt(Instant::of(3000))->isSuspended(self::t(20)));
    }

    public function test_row23_suspension_and_restoration_change_neither_coverage_nor_the_time_in_force(): void
    {
        $h = self::admitted();
        $coverage = $h->member->resolve()->coverage();
        $inForce = $h->member->resolve()->inForceIntervals();
        $h->commit($h->suspend('S1', 10));
        $h->commit($h->restore('R1', 'S1', 15));

        $this->assertTrue($coverage->equals($h->member->resolve()->coverage()));
        $this->assertTrue($inForce->equals($h->member->resolve()->inForceIntervals()));
    }

    public function test_the_published_restriction_stops_at_the_periods_end(): void
    {
        $h = self::admitted();
        $h->commit($h->suspend('S1', 10));
        $h->commit($h->end('E1', 'A1', 50));

        $this->assertTrue(self::set(10, 50)->equals($h->member->resolve()->suspendedIntervals()), 'Suspended = restrictions ∩ lifetime');
        $this->assertFalse(self::suspended($h, 50));
    }

    public function test_a_restriction_never_carries_into_a_later_period(): void
    {
        $h = self::admitted();
        $h->commit($h->suspend('S1', 10));           // open-ended, on A1
        $h->commit($h->end('E1', 'A1', 50));
        $h->commit($h->admit('A2', 60, 90));

        $this->assertFalse(self::suspended($h, 70), 'a new admission starts unrestricted');
        $this->assertSame(RefusalReason::NotInForce, $h->restore('R1', 'S1', 70)->refusal(), "the suspension's own period has ended, so it is not in force (D-177 §4 gives Restore no other refusal for this)");
    }

    public function test_the_suspension_belongs_to_the_period_containing_its_effective_date(): void
    {
        $h = self::admitted();
        $h->commit($h->end('E1', 'A1', 50));
        $h->commit($h->admit('A2', 60, 90));

        $decision = $h->suspend('S1', 70);

        $this->assertTrue($decision->isAccepted());
        $this->assertInstanceOf(Suspend::class, $decision->fact());
        $this->assertTrue(PeriodId::fromString('A2')->equals($decision->fact()->period()));
    }

    public function test_correcting_a_suspension_or_a_restore_is_refused_until_the_correction_step(): void
    {
        // D-177 row 14 ("a Correct changes its date") is part of S2-5 (Correct / Void); until then the kernel refuses.
        $h = self::admitted();
        $h->commit($h->suspend('S1', 10));
        $h->commit($h->restore('R1', 'S1', 15));

        $this->assertSame(RefusalReason::CorrectionOfSuspendOrRestoreNotSupported, $h->correct('C1', 'S1', 11)->refusal());
        $this->assertSame(RefusalReason::CorrectionOfSuspendOrRestoreNotSupported, $h->correct('C2', 'R1', 16)->refusal());
    }

    public function test_a_stored_suspension_must_name_an_earlier_admission(): void
    {
        $h = self::admitted();
        $orphan = new Suspend(FactId::fromString('S1'), PeriodId::fromString('NOPE'), self::t(10), TimePoint::infinity(), MemberHistory::provenance());

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('S1 refers to NOPE');
        Member::fromLog($h->member->id(), $h->member->holder(), $h->member->organisation(), ...[...$h->member->log(), RecordedFact::of(2, Instant::of(5_000), $orphan)]);
    }

    public function test_a_stored_restore_must_name_an_earlier_suspension(): void
    {
        $h = self::admitted();
        $orphan = new Restore(FactId::fromString('R1'), FactId::fromString('A1'), self::t(15), MemberHistory::provenance());

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('R1 refers to A1');
        Member::fromLog($h->member->id(), $h->member->holder(), $h->member->organisation(), ...[...$h->member->log(), RecordedFact::of(2, Instant::of(5_000), $orphan)]);
    }

    public function test_a_suspension_starts_at_a_finite_time_and_ends_after_it(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Suspend(FactId::fromString('S1'), PeriodId::fromString('A1'), self::t(10), self::t(10), MemberHistory::provenance());
    }
}
