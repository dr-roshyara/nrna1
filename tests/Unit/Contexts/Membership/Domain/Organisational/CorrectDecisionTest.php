<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\Correct;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * Correction slice C-2, step 3: the Correct decision, its refusals and the order of its guards.
 * Contract `2026-10-09-membership-correction-slice-semantic-contract-draft.md` §3.3, §10a Correct (3)–(7);
 * PO decisions P-1, P-3, P-4, P-5 (2026-11-03); D-COR-3 (dates only). PBDIGIT-327.
 *
 * What an accepted Correct does to the resolved state (value precedence, consequences, BD-REV-ER) is steps 4–6.
 */
final class CorrectDecisionTest extends TestCase
{
    /** A1 admits at 2; E1 ends period A1 at 5. */
    private static function endedPeriod(): MemberHistory
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 2));
        $h->commit($h->end('E1', 'A1', 5));

        return $h;
    }

    private static function openPeriod(int $at = 2): MemberHistory
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', $at));

        return $h;
    }

    // §3.3: a Correct of an Admit, of an End and of a Correct is accepted (D-COR-3: the date is the only attribute).
    public function test_the_dates_of_an_admit_an_end_and_a_correct_can_be_corrected(): void
    {
        $h = self::endedPeriod();
        $this->assertTrue($h->correct('C1', 'A1', 1)->isAccepted(), 'Admit');
        $this->assertTrue($h->correct('C1', 'E1', 6)->isAccepted(), 'End');

        $h->commit($h->correct('C1', 'A1', 1));
        $this->assertTrue($h->correct('C2', 'C1', 3)->isAccepted(), 'Correct of a Correct');
    }

    // §10a Correct (10), data part: the accepted fact carries what FactCorrected needs.
    public function test_an_accepted_correct_carries_its_fact_data(): void
    {
        $h = self::endedPeriod();

        $decision = $h->member->decideCorrect(FactId::fromString('C1'), FactId::fromString('E1'), TimePoint::of(4), MemberHistory::decisionDate(), Provenance::decision('registrar-2'), Instant::of(950));

        $this->assertTrue($decision->isAccepted());
        $this->assertSame(2, $decision->basedOnVersion());
        $fact = $decision->fact();
        $this->assertInstanceOf(Correct::class, $fact);
        $this->assertTrue($fact->id()->equals(FactId::fromString('C1')));
        $this->assertTrue($fact->target()->equals(FactId::fromString('E1')));
        $this->assertTrue($fact->value()->equals(TimePoint::of(4)));
        $this->assertEquals(Provenance::decision('registrar-2'), $fact->provenance());
        $this->assertEquals(Instant::of(950), $fact->decidedAt());
    }

    public function test_a_reused_fact_id_is_refused(): void
    {
        $h = self::endedPeriod();

        $this->assertSame(RefusalReason::DuplicateFactId, $h->correct('E1', 'A1', 1)->refusal());
    }

    public function test_correcting_an_unknown_fact_is_refused(): void
    {
        $this->assertSame(RefusalReason::TargetNotFound, self::endedPeriod()->correct('C1', 'ZZ', 1)->refusal());
    }

    // §10a Correct (3), N-3: an Annul or a Void has no date to correct.
    public function test_correcting_an_annul_or_a_void_is_refused(): void
    {
        $annulled = self::endedPeriod();
        $annulled->commit($annulled->annul('X1', 'E1'));
        $this->assertSame(RefusalReason::CorrectionOfAnnulOrVoidRefused, $annulled->correct('C1', 'X1', 6)->refusal(), 'Annul');

        $voided = self::endedPeriod();
        $voided->commit($voided->void('V1', 'E1'));
        $this->assertSame(RefusalReason::CorrectionOfAnnulOrVoidRefused, $voided->correct('C1', 'V1', 6)->refusal(), 'Void');
    }

    // §10a Correct (4): a voided target never reflected reality; there is nothing to correct.
    public function test_correcting_a_voided_fact_is_refused(): void
    {
        $admit = self::openPeriod();
        $admit->commit($admit->void('V1', 'A1'));
        $this->assertSame(RefusalReason::CorrectOfVoidedFact, $admit->correct('C1', 'A1', 1)->refusal(), 'voided Admit');

        $end = self::endedPeriod();
        $end->commit($end->void('V1', 'E1'));
        $this->assertSame(RefusalReason::CorrectOfVoidedFact, $end->correct('C1', 'E1', 6)->refusal(), 'voided End');

        $correct = self::openPeriod();
        $correct->commit($correct->correct('C1', 'A1', 1));
        $correct->commit($correct->void('V1', 'C1'));
        $this->assertSame(RefusalReason::CorrectOfVoidedFact, $correct->correct('C2', 'C1', 0)->refusal(), 'voided Correct');
    }

    // P-1 (m5): a Correct of a Correct whose chain root is voided is refused like a directly voided target.
    public function test_correcting_a_chain_whose_root_is_voided_is_refused(): void
    {
        $h = self::openPeriod();
        $h->commit($h->correct('C1', 'A1', 1));
        $h->commit($h->void('V1', 'A1'));

        $this->assertSame(RefusalReason::CorrectOfVoidedFact, $h->correct('C2', 'C1', 0)->refusal());
    }

    // A Void that is itself voided no longer voids its target: the target can be corrected again.
    public function test_a_target_whose_void_was_voided_can_be_corrected(): void
    {
        $h = self::openPeriod();
        $h->commit($h->void('V1', 'A1'));
        $h->commit($h->void('V2', 'V1'));

        $this->assertTrue($h->correct('C1', 'A1', 1)->isAccepted());
    }

    // §10a Correct (5), P-6 (the act governs): an annulled target is accepted (inert until revived; step 4), not TargetNotLive.
    public function test_correcting_an_annulled_target_is_accepted(): void
    {
        $admit = self::openPeriod();
        $admit->commit($admit->annul('X1', 'A1'));
        $this->assertTrue($admit->correct('C1', 'A1', 1)->isAccepted(), 'annulled Admit');

        $chain = self::openPeriod();
        $chain->commit($chain->correct('C1', 'A1', 1));
        $chain->commit($chain->annul('X1', 'A1'));
        $this->assertTrue($chain->correct('C2', 'C1', 0)->isAccepted(), 'Correct whose root is annulled');
    }

    // P-3: a correction to the value the target already has is refused.
    public function test_a_correction_to_the_current_value_is_refused(): void
    {
        $h = self::endedPeriod();
        $this->assertSame(RefusalReason::ValueUnchanged, $h->correct('C1', 'A1', 2)->refusal(), 'Admit, recorded value');
        $this->assertSame(RefusalReason::ValueUnchanged, $h->correct('C1', 'E1', 5)->refusal(), 'End, recorded value');

        $h->commit($h->correct('C1', 'A1', 1));
        $this->assertSame(RefusalReason::ValueUnchanged, $h->correct('C2', 'A1', 1)->refusal(), 'Admit, already corrected to 1');
        $this->assertSame(RefusalReason::ValueUnchanged, $h->correct('C2', 'C1', 1)->refusal(), 'the Correct itself');
        $this->assertTrue($h->correct('C2', 'A1', 2)->isAccepted(), 'back to the recorded value is a change');
    }

    // P-3, value precedence: the latest live correction is the current value; a superseded value is a change again.
    public function test_the_current_value_is_that_of_the_latest_live_correction(): void
    {
        $h = self::openPeriod(3);
        $h->commit($h->correct('C1', 'A1', 1));
        $h->commit($h->correct('C2', 'A1', 2));
        $this->assertTrue($h->correct('C3', 'A1', 1)->isAccepted(), 'C1 is superseded by C2');
        $this->assertSame(RefusalReason::ValueUnchanged, $h->correct('C3', 'A1', 2)->refusal());

        $h->commit($h->annul('X1', 'C2'));
        $this->assertSame(RefusalReason::ValueUnchanged, $h->correct('C3', 'A1', 1)->refusal(), 'C2 annulled: C1 is current');
    }

    // P-4, End side (C-S0-1 carried over): a corrected End must stay after its period's (current) start.
    public function test_a_corrected_end_at_or_before_the_period_start_is_refused(): void
    {
        $h = self::endedPeriod();
        $this->assertSame(RefusalReason::EndNotAfterPeriodStart, $h->correct('C1', 'E1', 2)->refusal(), 'at the start');
        $this->assertSame(RefusalReason::EndNotAfterPeriodStart, $h->correct('C1', 'E1', 1)->refusal(), 'before the start');
        $this->assertTrue($h->correct('C1', 'E1', 3)->isAccepted());

        $h->commit($h->correct('C1', 'A1', 4));
        $this->assertSame(RefusalReason::EndNotAfterPeriodStart, $h->correct('C2', 'E1', 4)->refusal(), 'at the corrected start');
        $this->assertTrue($h->correct('C2', 'E1', 6)->isAccepted());
    }

    // P-4, Admit side (symmetric): a corrected Admit must stay before its period's live End (current value).
    public function test_a_corrected_admit_at_or_after_the_live_end_is_refused(): void
    {
        $h = self::endedPeriod();
        $this->assertSame(RefusalReason::EndNotAfterPeriodStart, $h->correct('C1', 'A1', 5)->refusal(), 'at the end');
        $this->assertSame(RefusalReason::EndNotAfterPeriodStart, $h->correct('C1', 'A1', 6)->refusal(), 'after the end');
        $this->assertTrue($h->correct('C1', 'A1', 4)->isAccepted());

        $h->commit($h->correct('C1', 'E1', 7));
        $this->assertTrue($h->correct('C2', 'A1', 6)->isAccepted(), 'before the corrected end');
        $this->assertSame(RefusalReason::EndNotAfterPeriodStart, $h->correct('C2', 'A1', 7)->refusal(), 'at the corrected end');

        $h->commit($h->annul('X1', 'E1'));
        $this->assertTrue($h->correct('C2', 'A1', 9)->isAccepted(), 'the End is annulled: the period is open');
    }

    // P-4 through a chain: a Correct of a Correct is checked against the role of the chain root.
    public function test_the_ordering_rule_applies_through_a_correction_chain(): void
    {
        $h = self::endedPeriod();
        $h->commit($h->correct('C1', 'E1', 4));
        $this->assertSame(RefusalReason::EndNotAfterPeriodStart, $h->correct('C2', 'C1', 2)->refusal(), 'root is an End');

        $h->commit($h->correct('C2', 'A1', 3));
        $this->assertSame(RefusalReason::EndNotAfterPeriodStart, $h->correct('C3', 'C2', 4)->refusal(), 'root is an Admit');
    }

    // P-5 (D-5(b)): a correction may not move a date after its decision date; the decision date itself is allowed.
    public function test_a_corrected_date_after_the_decision_date_is_refused(): void
    {
        $h = self::openPeriod();
        $this->assertSame(RefusalReason::EffectiveDateAfterDecisionDate, $h->correct('C1', 'A1', 4, 3)->refusal());
        $this->assertTrue($h->correct('C1', 'A1', 3, 3)->isAccepted());
    }

    // P-5 (H1 pattern): the corrected date and the decision date are finite; ∞ is invalid input, not a refusal.
    public function test_an_infinite_corrected_date_or_decision_date_is_invalid_input(): void
    {
        $h = self::openPeriod();
        foreach (['value' => [TimePoint::infinity(), MemberHistory::decisionDate()], 'decision date' => [TimePoint::of(1), TimePoint::infinity()]] as $case => [$value, $decisionDate]) {
            try {
                $h->member->decideCorrect(FactId::fromString('C1'), FactId::fromString('A1'), $value, $decisionDate, MemberHistory::provenance());
                $this->fail("$case: expected InvalidArgumentException");
            } catch (\InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    // A refused decision is pure: nothing is appended and the resolved state is unchanged.
    public function test_a_refused_correct_changes_nothing(): void
    {
        $h = self::endedPeriod();
        $logBefore = $h->member->log();
        $resolvedBefore = $h->member->resolve();

        $this->assertFalse($h->correct('C1', 'E1', 1)->isAccepted());

        $this->assertSame($logBefore, $h->member->log());
        $this->assertEquals($resolvedBefore, $h->member->resolve());
    }

    // Guard order (plan §4, 1–9), pairwise: each case triggers two guards, and the earlier one must answer.
    public function test_correct_guard_order(): void
    {
        // 1 finite dates before 2 DuplicateFactId
        $h = self::endedPeriod();
        try {
            $h->member->decideCorrect(FactId::fromString('E1'), FactId::fromString('A1'), TimePoint::infinity(), MemberHistory::decisionDate(), MemberHistory::provenance());
            $this->fail('finite dates first');
        } catch (\InvalidArgumentException) {
            $this->addToAssertionCount(1);
        }

        // 2 DuplicateFactId before 3 TargetNotFound
        $this->assertSame(RefusalReason::DuplicateFactId, $h->correct('E1', 'ZZ', 1)->refusal(), 'duplicate before not found');
        // 3 TargetNotFound before 9 the dating rule
        $this->assertSame(RefusalReason::TargetNotFound, $h->correct('C1', 'ZZ', 9, 1)->refusal(), 'not found before dating');

        // 4 CorrectionOfAnnulOrVoidRefused before 5 CorrectOfVoidedFact: a Void that is itself voided
        $vv = self::openPeriod();
        $vv->commit($vv->void('V1', 'A1'));
        $vv->commit($vv->void('V2', 'V1'));
        $vv->commit($vv->void('V3', 'V2'));
        $this->assertSame(RefusalReason::CorrectionOfAnnulOrVoidRefused, $vv->correct('C1', 'V2', 1)->refusal(), 'kind before voided');
        // 4 before 9: the target kind is checked before the dating rule
        $this->assertSame(RefusalReason::CorrectionOfAnnulOrVoidRefused, $vv->correct('C1', 'V1', 9, 1)->refusal(), 'kind before dating');

        // 5 CorrectOfVoidedFact before 7 ValueUnchanged, 8 ordering and 9 dating
        $voided = self::endedPeriod();
        $voided->commit($voided->void('V1', 'E1'));
        $this->assertSame(RefusalReason::CorrectOfVoidedFact, $voided->correct('C1', 'E1', 5)->refusal(), 'voided before unchanged');
        $this->assertSame(RefusalReason::CorrectOfVoidedFact, $voided->correct('C1', 'E1', 1)->refusal(), 'voided before ordering');
        $this->assertSame(RefusalReason::CorrectOfVoidedFact, $voided->correct('C1', 'E1', 9, 6)->refusal(), 'voided before dating');

        // 6 an annulled target is not refused: it reaches 7 ValueUnchanged
        $annulled = self::openPeriod();
        $annulled->commit($annulled->annul('X1', 'A1'));
        $this->assertSame(RefusalReason::ValueUnchanged, $annulled->correct('C1', 'A1', 2)->refusal(), 'annulled reaches unchanged');

        // 7 ValueUnchanged before 9 dating: the current value already lies after this decision date
        $late = self::openPeriod(5);
        $this->assertSame(RefusalReason::ValueUnchanged, $late->correct('C1', 'A1', 5, 3)->refusal(), 'unchanged before dating');

        // 8 ordering before 9 dating, on both sides
        $ended = self::endedPeriod();
        $this->assertSame(RefusalReason::EndNotAfterPeriodStart, $ended->correct('C1', 'E1', 1, 0)->refusal(), 'End side before dating');
        $this->assertSame(RefusalReason::EndNotAfterPeriodStart, $ended->correct('C1', 'A1', 6, 4)->refusal(), 'Admit side before dating');
    }
}
