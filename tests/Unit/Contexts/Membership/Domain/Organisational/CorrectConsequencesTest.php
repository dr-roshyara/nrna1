<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\Correct;
use App\Contexts\Membership\Domain\Organisational\Decision;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Interval;
use App\Contexts\Membership\Domain\Organisational\IntervalSet;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Domain\Organisational\TrimConsequence;
use App\Contexts\Membership\Domain\Organisational\TrimRevival;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * Correction slice C-2, step 5: the consequences of a Correct (trim policy, BD-REV = trim), LiveCut and CFC+.
 * Contract §10a Correct (8), (10); FP §12.3 (CFC), §12.4 (option C: a trim is recorded on its cause; P-2: the removed
 * interval as stated by the decision). PBDIGIT-327.
 *
 * Every expected value here was computed by the independent oracle port (G6C2CorrectionOracle) before the kernel
 * code existed; the open end ∞ corresponds to the oracle's horizon.
 */
final class CorrectConsequencesTest extends TestCase
{
    /** A0 = [0, 2) ended by E0; A1 = [2, ∞), adjacent. */
    private static function adjacentPeriods(int $secondStart = 2): MemberHistory
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A0', 0, null, true));
        $h->commit($h->end('E0', 'A0', 2));
        $h->commit($h->admit('A1', $secondStart, null, true));

        return $h;
    }

    private static function lifetime(MemberHistory $h, string $admit): IntervalSet
    {
        return $h->member->resolve()->period(PeriodId::ofAdmit(FactId::fromString($admit)))->lifetime();
    }

    private static function set(int $from, ?int $until): IntervalSet
    {
        return IntervalSet::of(Interval::of(TimePoint::of($from), $until === null ? TimePoint::infinity() : TimePoint::of($until)));
    }

    private static function assertLifetimes(MemberHistory $h, array $expected, string $message): void
    {
        foreach ($expected as $admit => [$from, $until]) {
            self::assertTrue(self::lifetime($h, $admit)->equals(self::set($from, $until)), "$message: lifetime of $admit");
        }
    }

    /** @return list<array{string, string}> [cause, period] per revived trim */
    private static function revived(Decision $decision): array
    {
        return array_map(static fn (TrimRevival $r): array => [$r->cause()->value(), $r->period()->value()], $decision->revivedTrims());
    }

    /** @return list<TrimConsequence> */
    private static function consequences(Decision $decision): array
    {
        $fact = $decision->fact();
        self::assertInstanceOf(Correct::class, $fact);

        return $fact->consequences();
    }

    // §10a Correct (8), g2 Δ: a corrected End that enlarges its period trims the overlap from the adjacent period.
    public function test_an_enlarging_end_correction_trims_the_next_period(): void
    {
        $h = self::adjacentPeriods();
        $decision = $h->correct('C1', 'E0', 4);

        $this->assertTrue($decision->isAccepted(), 'trimmed, not refused as an overlap (step 4 interim lifted)');
        $consequences = self::consequences($decision);
        $this->assertCount(1, $consequences);
        $this->assertTrue($consequences[0]->period()->equals(PeriodId::ofAdmit(FactId::fromString('A1'))));
        $this->assertTrue($consequences[0]->removed()->equals(self::set(2, 4)), 'PeriodTrimmed data: the removed interval');

        $h->commit($decision);
        self::assertLifetimes($h, ['A0' => [0, 4], 'A1' => [4, null]], 'after C1');
    }

    // §10a Correct (8), g2 Δ: a corrected admission that moves earlier trims the end of the previous period.
    public function test_an_earlier_admission_correction_trims_the_previous_period(): void
    {
        $h = self::adjacentPeriods(3);
        $decision = $h->correct('C1', 'A1', 1);

        $consequences = self::consequences($decision);
        $this->assertCount(1, $consequences);
        $this->assertTrue($consequences[0]->period()->equals(PeriodId::ofAdmit(FactId::fromString('A0'))));
        $this->assertTrue($consequences[0]->removed()->equals(self::set(1, 2)));

        $h->commit($decision);
        self::assertLifetimes($h, ['A0' => [0, 1], 'A1' => [1, null]], 'after C1');
    }

    // A correction that shrinks its period, or moves inside free time, has no consequences.
    public function test_a_correction_without_overlap_has_no_consequences(): void
    {
        $h = self::adjacentPeriods(3);
        $this->assertSame([], self::consequences($h->correct('C1', 'E0', 1)), 'shrinking End');
        $this->assertSame([], self::consequences($h->correct('C1', 'E0', 3)), 'enlarging into the gap only');
    }

    // CFC (FP §12.3): annulling or voiding the cause removes its trims with it; nothing is revived.
    public function test_reverting_the_cause_removes_its_trims(): void
    {
        foreach (['Annul' => fn (MemberHistory $h) => $h->annul('X1', 'C1'), 'Void' => fn (MemberHistory $h) => $h->void('V1', 'C1')] as $kind => $revert) {
            $h = self::adjacentPeriods();
            $h->commit($h->correct('C1', 'E0', 4));

            $decision = $revert($h);
            $this->assertSame([], self::revived($decision), "$kind: nothing revived");
            $h->commit($decision);
            self::assertLifetimes($h, ['A0' => [0, 2], 'A1' => [2, null]], "$kind of C1");
        }
    }

    // CFC+ (§10a Correct (8), (10)): restoring the cause restores its trims, and the decision carries TrimRevived.
    public function test_restoring_the_cause_revives_its_trims(): void
    {
        foreach ([
            'Annul(Annul)' => [fn (MemberHistory $h) => $h->annul('X1', 'C1'), fn (MemberHistory $h) => $h->annul('X2', 'X1')],
            'Void(Void)' => [fn (MemberHistory $h) => $h->void('V1', 'C1'), fn (MemberHistory $h) => $h->void('V2', 'V1')],
        ] as $kind => [$revert, $restore]) {
            $h = self::adjacentPeriods();
            $h->commit($h->correct('C1', 'E0', 4));
            $h->commit($revert($h));

            $decision = $restore($h);
            $this->assertSame([['C1', 'A1']], self::revived($decision), "$kind: TrimRevived");
            $h->commit($decision);
            self::assertLifetimes($h, ['A0' => [0, 4], 'A1' => [4, null]], "$kind: C1 restored");
        }
    }

    // §10a Correct (8), inherited clipping: Correct(Correct) inherits its target's trims, clipped to the new claim;
    // the superseded correction's own trims stop applying.
    public function test_a_correction_of_a_correction_inherits_its_trims_clipped(): void
    {
        $h = self::adjacentPeriods();
        $h->commit($h->correct('C1', 'E0', 4));

        $decision = $h->correct('C2', 'C1', 3);
        $consequences = self::consequences($decision);
        $this->assertCount(1, $consequences);
        $this->assertTrue($consequences[0]->period()->equals(PeriodId::ofAdmit(FactId::fromString('A1'))));
        $this->assertTrue($consequences[0]->removed()->equals(self::set(2, 3)), 'C1 trim [2, 4) clipped to [0, 3)');
        $this->assertSame([], self::revived($decision));

        $h->commit($decision);
        self::assertLifetimes($h, ['A0' => [0, 3], 'A1' => [3, null]], 'after C2');
    }

    // CFC+ (§10a Correct (8)): annulling the superseding correction revives the superseded one with its trims.
    public function test_annulling_a_superseding_correction_revives_the_superseded_trims(): void
    {
        $h = self::adjacentPeriods();
        $h->commit($h->correct('C1', 'E0', 4));
        $h->commit($h->correct('C2', 'C1', 3));

        $decision = $h->annul('X1', 'C2');
        $this->assertSame([['C1', 'A1']], self::revived($decision));
        $h->commit($decision);
        self::assertLifetimes($h, ['A0' => [0, 4], 'A1' => [4, null]], 'C1 back in force');
    }

    // LiveCut (stage 5): a trim applies only within its cause's current BaseClaim.
    public function test_a_trim_is_clipped_to_its_cause_period_current_claim(): void
    {
        $h = self::adjacentPeriods();
        $h->commit($h->correct('C1', 'E0', 4));

        $decision = $h->correct('C2', 'E0', 3);
        $this->assertSame([], self::consequences($decision), 'shrinking: no new trim');
        $h->commit($decision);

        self::assertLifetimes($h, ['A0' => [0, 3], 'A1' => [3, null]], 'C1 trim [2, 4) applies as [2, 3)');
    }

    // INV-EP is reachable in C-2 (FP §12.1 CX-B): moving a period across its own trim would split it; G* refuses.
    public function test_moving_a_period_across_its_own_trim_is_refused(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A0', 1, null, true));
        $h->commit($h->end('E0', 'A0', 2));
        $h->commit($h->admit('A1', 3, null, true));
        $h->commit($h->correct('C1', 'E0', 4));

        $this->assertSame(RefusalReason::PeriodLifetimeNotContiguous, $h->correct('C2', 'A1', 2)->refusal());
    }

    // TrimRevived is exact (COR-2 mutation findings): with two causes in force, restoring the second reports only the
    // second; a trim already in force is never reported as revived. Lifetimes checked against the oracle port (H = 10).
    public function test_only_the_restored_trim_is_reported_as_revived(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A0', 0, null, true));
        $h->commit($h->end('E0', 'A0', 2));
        $h->commit($h->admit('A1', 2, null, true));
        $h->commit($h->end('E1', 'A1', 6));
        $h->commit($h->admit('A2', 6, null, true));
        $h->commit($h->correct('C5', 'E0', 3));   // trims A1 by [2, 3)
        $h->commit($h->correct('C6', 'E1', 7));   // trims A2 by [6, 7)
        $h->commit($h->annul('X7', 'C6'));

        $decision = $h->annul('X8', 'X7');
        $this->assertSame([['C6', 'A2']], self::revived($decision), 'C5 was in force throughout');
        $this->assertSame([0], array_keys($decision->revivedTrims()), 'a list');
        $h->commit($decision);
        self::assertLifetimes($h, ['A0' => [0, 3], 'A1' => [3, 7], 'A2' => [7, null]], 'both trims in force');
    }

    // Stage 2 (COR-2 mutation finding): a Correct whose target is set aside is inactive, so its trims stop applying.
    // Here C2's inherited trim would otherwise hide an overlap; the oracle port refuses the Annul the same way.
    public function test_the_trims_of_a_correction_whose_target_is_annulled_stop_applying(): void
    {
        $h = self::adjacentPeriods();
        $h->commit($h->correct('C3', 'E0', 4));   // A0 = [0, 4); trims A1 by [2, 4)
        $h->commit($h->correct('C4', 'C3', 3));   // inherits [2, 3); C3 superseded
        $h->commit($h->correct('C5', 'E0', 5));   // the latest direct correction of E0: A0 = [0, 5); trims A1 by [3, 5)

        // Annul(C3) makes C4 inactive: A1 would be [2, 3) ∪ [5, ∞) and overlap A0 = [0, 5).
        $this->assertSame(RefusalReason::OverlappingMembershipPeriods, $h->annul('X6', 'C3')->refusal());
    }

    // One Correct can trim two periods (review F-1, condition C-1; FP §13.6 case B "one cause → several trims").
    // Annulling C3 leaves A1 with an empty BaseClaim (start 9 after its End 6, review F-3); correcting A1 to 1 then
    // extends it on both sides. Every consequence must be kept and applied. Expected values from the oracle port (H = 10).
    public function test_one_correction_can_trim_two_live_periods(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A0', 0, null, true));
        $h->commit($h->end('E1', 'A0', 2));
        $h->commit($h->admit('A1', 9, null, true));
        $h->commit($h->correct('C3', 'A1', 4));
        $h->commit($h->end('E4', 'A1', 6));
        $h->commit($h->admit('A2', 8, null, true));
        $h->commit($h->annul('X6', 'C3'));
        $h->commit($h->correct('C7', 'A2', 2));

        $decision = $h->correct('C8', 'A1', 1);
        $this->assertTrue($decision->isAccepted());
        $trimmed = [];
        foreach (self::consequences($decision) as $consequence) {
            $trimmed[$consequence->period()->admitFactId()->value()] = $consequence->removed();
        }
        $this->assertSame(['A0', 'A2'], array_keys($trimmed), 'both periods are trimmed');
        $this->assertTrue($trimmed['A0']->equals(self::set(1, 2)));
        $this->assertTrue($trimmed['A2']->equals(self::set(2, 6)));

        $h->commit($decision);
        self::assertLifetimes($h, ['A0' => [0, 1], 'A1' => [1, 6], 'A2' => [6, null]], 'both trims apply');
    }

    // Decisions other than reversals revive nothing.
    public function test_end_and_correct_decisions_revive_nothing(): void
    {
        $h = self::adjacentPeriods(3);
        $this->assertSame([], self::revived($h->end('E1', 'A1', 5)));
        $this->assertSame([], self::revived($h->correct('C1', 'E0', 3)));
    }
}
