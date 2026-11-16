<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * PBDIGIT-354 S2-5b in the kernel:
 * - **D-237 (D-177 row 20):** a Correct of a Renew sets its `until`: any date strictly after the Renew's start, or
 *   open-ended (∞); NOT bound by "not after the decision date" (a term's end is not an effective date). **D-242:** the
 *   Renew's start is never changed by it.
 * - **D-177 row 14:** a Correct of a Restore sets its date, under the Restore's own rules: the Suspend live, the period
 *   in force, strictly inside the restriction, not after the decision date (D-205).
 * - **D-241:** a Suspend's dates are not correctable (Void and record again).
 * - The originals stay; corrections chain; a Void of a Correct restores the previous value; knownAt before the Correct
 *   shows the original.
 * Day ordinals.
 */
final class CorrectRenewAndRestoreTest extends TestCase
{
    /** A1 admitted at 1, term [1, 40) (lapses at 40); R1: a late renewal [50, 150) decided on 50 (gap 40 … 50). */
    private static function renewed(): MemberHistory
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 1, 40), 100);
        $h->commit($h->renew('R1', 50, 150, 50), 200);

        return $h;
    }

    /** A1 admitted at 1, open-ended term; S1 suspended [20, 80); T1 restores it at 50. */
    private static function restored(): MemberHistory
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 1, termIsInfinite: true), 100);
        $h->commit($h->suspend('S1', 20, 80), 200);
        $h->commit($h->restore('T1', 'S1', 50), 300);

        return $h;
    }

    private static function inForce(MemberHistory $h, int $at): bool
    {
        return $h->member->resolve()->period(\App\Contexts\Membership\Domain\Organisational\PeriodId::fromString('A1'))->isInForce(TimePoint::of($at));
    }

    private static function suspended(MemberHistory $h, int $at): bool
    {
        return $h->member->resolve()->isSuspended(TimePoint::of($at));
    }

    public function test_d237_a_renewals_until_is_corrected_even_beyond_the_decision_date(): void
    {
        $h = self::renewed();

        $h->commit($h->correct('C1', 'R1', 200, 60), 300);   // decided on 60, until 200: D-237 (2)

        $this->assertTrue(self::inForce($h, 199));
        $this->assertFalse(self::inForce($h, 200));
        $this->assertFalse(self::inForce($h, 45), 'D-242: the start is unchanged; the gap 40 … 50 stays');
        $this->assertTrue(self::inForce($h, 50));
    }

    public function test_d237_a_renewals_until_is_corrected_to_open_ended(): void
    {
        $h = self::renewed();

        $h->commit($h->correctToOpenEnded('C1', 'R1', 60), 300);

        $this->assertTrue(self::inForce($h, 1_000_000));
        $this->assertFalse(self::inForce($h, 45));
    }

    public function test_d237_the_corrected_until_must_be_strictly_after_the_renewals_start(): void
    {
        $h = self::renewed();

        $this->assertSame(RefusalReason::EmptySegment, $h->correct('C1', 'R1', 50, 60)->refusal(), 'equal to the start');
        $this->assertSame(RefusalReason::EmptySegment, $h->correct('C1', 'R1', 49, 60)->refusal(), 'before it');
        $this->assertSame(RefusalReason::ValueUnchanged, $h->correct('C1', 'R1', 150, 60)->refusal());
        $this->assertTrue($h->correct('C1', 'R1', 51, 60)->isAccepted(), 'shortened, but after the start');
    }

    public function test_d237_history_corrections_chain_a_void_restores_and_knownat_shows_the_original(): void
    {
        $h = self::renewed();
        $h->commit($h->correct('C1', 'R1', 200, 60), 300);
        $h->commit($h->correctToOpenEnded('C2', 'C1', 70), 400);

        $this->assertCount(4, $h->member->log(), 'Admit, Renew, both Corrects: nothing rewritten');
        $this->assertTrue(self::inForce($h, 1_000_000), 'the latest correction of the chain');
        $this->assertTrue($h->member->knownAt(Instant::of(250))->period(\App\Contexts\Membership\Domain\Organisational\PeriodId::fromString('A1'))->isInForce(TimePoint::of(149)));
        $this->assertFalse($h->member->knownAt(Instant::of(250))->period(\App\Contexts\Membership\Domain\Organisational\PeriodId::fromString('A1'))->isInForce(TimePoint::of(150)), 'as known before the Correct: the original until');

        $h->commit($h->void('V1', 'C2'), 500);
        $this->assertFalse(self::inForce($h, 200), 'C2 voided: C1 (200) applies again');
        $this->assertTrue(self::inForce($h, 199));
    }

    public function test_d237_a_voided_renewal_is_not_corrected(): void
    {
        $h = self::renewed();
        $h->commit($h->void('V1', 'R1'), 300);

        $this->assertSame(RefusalReason::CorrectOfVoidedFact, $h->correct('C1', 'R1', 200, 60)->refusal());
    }

    public function test_only_a_renewal_chain_may_be_corrected_to_open_ended(): void
    {
        $h = self::restored();

        $this->expectException(\InvalidArgumentException::class);
        $h->correctToOpenEnded('C1', 'T1');
    }

    public function test_row14_a_restores_date_is_corrected_inside_the_restriction(): void
    {
        $h = self::restored();

        $h->commit($h->correct('C1', 'T1', 60, 70), 400);

        $this->assertTrue(self::suspended($h, 59));
        $this->assertFalse(self::suspended($h, 60));
        $this->assertCount(4, $h->member->log(), 'the Restore is not rewritten');
        $this->assertFalse($h->member->knownAt(Instant::of(350))->isSuspended(TimePoint::of(55)), 'as known before the Correct: restored on 50');
    }

    public function test_row14_a_corrected_restore_keeps_the_restores_own_rules(): void
    {
        $h = self::restored();

        $this->assertSame(RefusalReason::OutsideRestriction, $h->correct('C1', 'T1', 80)->refusal(), 'on the restriction end');
        $this->assertSame(RefusalReason::OutsideRestriction, $h->correct('C1', 'T1', 20)->refusal(), 'on its start');
        $this->assertSame(RefusalReason::OutsideRestriction, $h->correct('C1', 'T1', 10)->refusal());
        $this->assertSame(RefusalReason::EffectiveDateAfterDecisionDate, $h->correct('C1', 'T1', 60, 55)->refusal(), 'D-205');
        $this->assertSame(RefusalReason::ValueUnchanged, $h->correct('C1', 'T1', 50)->refusal());
        $h->commit($h->void('V1', 'S1'), 400);
        $this->assertSame(RefusalReason::NotSuspended, $h->correct('C1', 'T1', 60)->refusal(), 'its suspension voided');
    }

    public function test_row14_a_correction_of_a_corrected_restore(): void
    {
        $h = self::restored();
        $h->commit($h->correct('C1', 'T1', 60, 70), 400);
        $h->commit($h->correct('C2', 'C1', 40, 70), 500);

        $this->assertFalse(self::suspended($h, 40));
        $this->assertTrue(self::suspended($h, 39));
    }

    public function test_d241_a_suspension_is_not_corrected(): void
    {
        $h = self::restored();

        $this->assertSame(RefusalReason::CorrectionOfSuspendNotPermitted, $h->correct('C1', 'S1', 25)->refusal());
    }

    public function test_a_stored_log_with_corrections_of_renew_and_restore_reloads(): void
    {
        $h = self::renewed();
        $h->commit($h->correctToOpenEnded('C1', 'R1', 60), 300);

        $reloaded = Member::fromLog($h->member->id(), $h->member->holder(), $h->member->organisation(), ...$h->member->log());
        $this->assertTrue($reloaded->resolve()->period(\App\Contexts\Membership\Domain\Organisational\PeriodId::fromString('A1'))->isInForce(TimePoint::of(1_000_000)));
    }

    public function test_a_stored_open_ended_correction_of_a_non_renewal_is_rejected_on_load(): void
    {
        $h = self::restored();
        $log = $h->member->log();
        $bad = \App\Contexts\Membership\Domain\Organisational\RecordedFact::of(4, Instant::of(400),
            new \App\Contexts\Membership\Domain\Organisational\Correct(FactId::fromString('C1'), FactId::fromString('T1'), TimePoint::infinity(), Provenance::decision('r'), Instant::of(900)));

        $this->expectException(\InvalidArgumentException::class);
        Member::fromLog($h->member->id(), $h->member->holder(), $h->member->organisation(), ...[...$log, $bad]);
    }
}
