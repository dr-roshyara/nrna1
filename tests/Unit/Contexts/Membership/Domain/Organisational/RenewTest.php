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
use App\Contexts\Membership\Domain\Organisational\Renew;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * PBDIGIT-354 S2-1: Renew (D-163; accepted design D-177 §2, §4, §5 rows 1–7, 17, 21–23; D-205 (3)).
 * A Renew adds a term segment [effectiveAt, until) to the period containing effectiveAt. It never changes coverage.
 * Times are day ordinals, as in the other kernel tests.
 */
final class RenewTest extends TestCase
{
    private static function t(int $o): TimePoint
    {
        return TimePoint::of($o);
    }

    /** admitted at 1, term [1, 10) */
    private static function admitted(int $at = 1, ?int $termUntil = 10): MemberHistory
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', $at, $termUntil));

        return $h;
    }

    private static function isMember(MemberHistory $h, int $t): bool
    {
        return $h->member->resolve()->isMember(self::t($t));
    }

    public function test_row1_a_renewal_adjoining_the_term_gives_continuous_in_force(): void
    {
        $h = self::admitted();
        $h->commit($h->renew('R1', 8, 20));

        foreach ([1, 9, 10, 19] as $t) {
            $this->assertTrue(self::isMember($h, $t), "in force at $t");
        }
        $this->assertFalse(self::isMember($h, 20), 'the renewed term ends at 20 (half-open)');
    }

    public function test_an_open_ended_renewal_is_in_force_until_ended(): void
    {
        $h = self::admitted();
        $h->commit($h->renew('R1', 8, null));

        $this->assertTrue(self::isMember($h, 5_000));
    }

    public function test_row3_a_late_renewal_keeps_the_lapsed_gap(): void
    {
        $h = self::admitted();                       // term ended at 10, not renewed
        $h->commit($h->renew('R1', 15, 25));         // decided and effective at 15

        $this->assertFalse(self::isMember($h, 12), 'lapsed 10..15 stays lapsed (INV-12)');
        $this->assertTrue($h->member->resolve()->coverage()->contains(self::t(12)), 'lapsed is not ended: still covered (INV-3)');
        $this->assertTrue(self::isMember($h, 15));
        $this->assertFalse(self::isMember($h, 25));
    }

    public function test_row4_an_expressly_back_dated_renewal_closes_the_gap(): void
    {
        $h = self::admitted();
        $h->commit($h->renew('R1', 10, 25));         // decided later (fixture decision date), effective from 10

        $this->assertTrue(self::isMember($h, 12));
    }

    public function test_row5_a_renewal_after_a_formal_end_is_refused(): void
    {
        $h = self::admitted();
        $h->commit($h->end('E1', 'A1', 12));

        $this->assertSame(RefusalReason::PeriodEnded, $h->renew('R1', 15, 25)->refusal());
        $this->assertSame(RefusalReason::PeriodEnded, $h->renew('R2', 12, 25)->refusal(), 'at the end instant the period is already ended (half-open)');
        $this->assertTrue($h->renew('R3', 11, 25)->isAccepted(), 'before the end it is still the open period');
    }

    public function test_row6_a_renewal_before_the_periods_start_is_refused(): void
    {
        $this->assertSame(RefusalReason::NoPeriodAt, self::admitted(5)->renew('R1', 3, 9)->refusal());
        $this->assertSame(RefusalReason::NoPeriodAt, MemberHistory::start()->renew('R1', 3, 9)->refusal(), 'no period at all');
    }

    public function test_row7_overlapping_renewals_are_a_union(): void
    {
        $h = self::admitted();
        $h->commit($h->renew('R1', 8, 20));
        $h->commit($h->renew('R2', 15, 30));

        $this->assertTrue(self::isMember($h, 25));
        $this->assertFalse(self::isMember($h, 30));
    }

    public function test_an_empty_segment_is_refused(): void
    {
        $this->assertSame(RefusalReason::EmptySegment, self::admitted()->renew('R1', 8, 8)->refusal());
        $this->assertSame(RefusalReason::EmptySegment, self::admitted()->renew('R2', 8, 7)->refusal());
    }

    public function test_d205_a_renewal_may_not_take_effect_after_its_decision_date(): void
    {
        $h = self::admitted();

        $this->assertSame(RefusalReason::EffectiveDateAfterDecisionDate, $h->renew('R1', 9, 20, 8)->refusal(), 'decided on 8, effective 9: future-dated');
        $this->assertTrue($h->renew('R2', 8, 20, 8)->isAccepted(), 'effective on the decision date');
    }

    public function test_a_fact_id_is_used_once(): void
    {
        $this->assertSame(RefusalReason::DuplicateFactId, self::admitted()->renew('A1', 8, 20)->refusal());
    }

    public function test_a_period_admitted_without_a_term_becomes_in_force_from_its_renewal(): void
    {
        $h = self::admitted(1, null);                // attested-history shape: lapsed from the start
        $h->commit($h->renew('R1', 4, 9));

        $this->assertFalse(self::isMember($h, 3));
        $this->assertTrue(self::isMember($h, 4));
    }

    public function test_the_renewal_belongs_to_the_period_containing_its_effective_date(): void
    {
        $h = self::admitted();
        $h->commit($h->end('E1', 'A1', 5));
        $h->commit($h->admit('A2', 7, 9));

        $decision = $h->renew('R1', 8, 20);

        $this->assertTrue($decision->isAccepted(), 'the open period A2 contains 8');
        $this->assertInstanceOf(Renew::class, $decision->fact());
        $this->assertTrue(PeriodId::fromString('A2')->equals($decision->fact()->period()));
        $this->assertSame(RefusalReason::PeriodEnded, $h->renew('R2', 6, 20)->refusal(), 'between the periods: the earlier one has ended');
    }

    public function test_a_renewed_term_never_carries_into_a_later_period(): void
    {
        $h = self::admitted();                       // A1: [1, ∞), term [1, 10)
        $h->commit($h->renew('R1', 3, 30));          // A1's term now reaches 30
        $h->commit($h->end('E1', 'A1', 5));          // A1 ends at 5
        $h->commit($h->admit('A2', 7, 9));           // A2: its own term [7, 9)

        $this->assertTrue(self::isMember($h, 8));
        $this->assertFalse(self::isMember($h, 15), "A1's renewal to 30 is not A2's term: A2 is lapsed after 9");
        $this->assertFalse(self::isMember($h, 6), 'between the periods');
    }

    public function test_row23_a_renewal_never_changes_coverage(): void
    {
        $h = self::admitted();
        $before = $h->member->resolve()->coverage();
        $h->commit($h->renew('R1', 15, 25));

        $this->assertTrue($before->equals($h->member->resolve()->coverage()));
    }

    public function test_a_renewed_term_stops_at_the_periods_end(): void
    {
        $h = self::admitted();
        $h->commit($h->renew('R1', 8, 30));
        $h->commit($h->end('E1', 'A1', 20));

        $this->assertTrue(self::isMember($h, 19));
        $this->assertFalse(self::isMember($h, 20), 'INV-2: ended is never in force');
        // the PUBLISHED intervals (C1 / C2 `inForce`) are clipped too, not only the point predicate
        $this->assertTrue(IntervalSet::of(Interval::of(self::t(1), self::t(20)))->equals($h->member->resolve()->inForceIntervals()));
    }

    public function test_row17_annulling_a_renewal_removes_its_segment_from_then_on(): void
    {
        $h = self::admitted();
        $h->commit($h->renew('R1', 8, 20), 2000);   // the fixture clock records the admission at 1001
        $h->commit($h->annul('X1', 'R1'), 3000);

        $this->assertFalse(self::isMember($h, 15));
        $this->assertTrue($h->member->knownAt(Instant::of(2500))->isMember(self::t(15)), 'as known before the annulment, it was in force');
    }

    public function test_voiding_a_renewal_removes_its_segment(): void
    {
        $h = self::admitted();
        $h->commit($h->renew('R1', 8, 20));
        $h->commit($h->void('V1', 'R1'));

        $this->assertFalse(self::isMember($h, 15));
    }

    public function test_row21_after_the_end_is_annulled_a_renewal_is_accepted_again(): void
    {
        $h = self::admitted();
        $h->commit($h->end('E1', 'A1', 12));
        $this->assertSame(RefusalReason::PeriodEnded, $h->renew('R1', 15, 25)->refusal());

        $h->commit($h->annul('X1', 'E1'));

        $this->assertTrue($h->renew('R2', 15, 25)->isAccepted());
    }

    public function test_row22_a_renewal_is_not_visible_before_its_record_time(): void
    {
        $h = self::admitted();
        $h->commit($h->renew('R1', 8, 20), 2000);

        $this->assertFalse($h->member->knownAt(Instant::of(1999))->isMember(self::t(15)));
        $this->assertTrue($h->member->knownAt(Instant::of(2000))->isMember(self::t(15)));
    }

    public function test_correcting_a_renewal_is_refused_until_its_rule_is_decided(): void
    {
        // D-177 row 20 (Correct a Renew's `until`) needs a decision on the dating rule for a corrected `until` (Q-2).
        $h = self::admitted();
        $h->commit($h->renew('R1', 8, 20));

        $this->assertSame(RefusalReason::CorrectionOfRenewNotSupported, $h->correct('C1', 'R1', 25)->refusal());
    }

    public function test_a_stored_renewal_must_name_an_earlier_admission(): void
    {
        $h = self::admitted();
        $orphan = new Renew(FactId::fromString('R1'), PeriodId::fromString('NOPE'), self::t(8), self::t(20), MemberHistory::provenance());

        // recorded after the admission (1001), so only the structural rule can refuse this log
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('R1 refers to NOPE');
        Member::fromLog($h->member->id(), $h->member->holder(), $h->member->organisation(), ...[...$h->member->log(), RecordedFact::of(2, Instant::of(5_000), $orphan)]);
    }

    public function test_a_renewal_takes_effect_at_a_finite_time_and_ends_after_it(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Renew(FactId::fromString('R1'), PeriodId::fromString('A1'), self::t(8), self::t(8), MemberHistory::provenance());
    }
}
