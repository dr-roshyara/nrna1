<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\DeclarationId;
use App\Contexts\Membership\Domain\Organisational\End;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\ScheduledResignation;
use App\Contexts\Membership\Domain\Organisational\ScheduledResignationState;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * PBDIGIT-354 S2-4a: the scheduled resignation declaration and D-205's single exception (D-201, D-204, D-205, D-223;
 * M-TDD-01 §5.2; MI-1, MI-3, MI-15a … f in the domain).
 *
 * - A resignation for a LATER date is a declaration held outside the ledger, per period: scheduled → executed /
 *   withdrawn / superseded. It changes nothing in the membership until executed.
 * - Executing it is the ONLY End that may take effect after its decision date: effective = the scheduled date,
 *   decided = the declaration, recorded on or after the scheduled date (never before).
 * Day ordinals. The member: admitted at 1, period A1 [1, ∞), term [1, 100).
 */
final class ScheduledResignationTest extends TestCase
{
    private static function t(int $o): TimePoint
    {
        return TimePoint::of($o);
    }

    private static function admitted(): MemberHistory
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 1, 100));

        return $h;
    }

    /** @param list<ScheduledResignation> $existing */
    private static function schedule(MemberHistory $h, string $id = 'D1', int $declared = 10, int $effective = 30, array $existing = []): ScheduledResignation|RefusalReason
    {
        return ScheduledResignation::schedule(DeclarationId::fromString($id), $h->member->resolve(), $existing, self::t($declared), self::t($effective),
            Provenance::decision('P1'), Instant::of(5_000));
    }

    public function test_a_resignation_from_a_later_date_is_scheduled_for_the_current_period(): void
    {
        $d = self::schedule(self::admitted());

        $this->assertInstanceOf(ScheduledResignation::class, $d);
        $this->assertSame(ScheduledResignationState::Scheduled, $d->state());
        $this->assertTrue(PeriodId::fromString('A1')->equals($d->period()));
        $this->assertSame([10, 30], [$d->declarationDate()->ordinal(), $d->effectiveDate()->ordinal()]);
    }

    public function test_a_scheduled_resignation_changes_nothing_in_the_membership(): void
    {
        $h = self::admitted();
        $before = $h->member->resolve()->coverage();
        self::schedule($h);

        $this->assertTrue($before->equals($h->member->resolve()->coverage()), 'not a ledger fact (D-204): C1 / C2 show nothing (MI-4)');
        $this->assertSame(1, $h->member->version());
    }

    public function test_the_scheduled_date_must_be_later_than_the_declaration(): void
    {
        $this->assertSame(RefusalReason::ScheduledDateNotAfterDeclaration, self::schedule(self::admitted(), 'D1', 10, 10), 'the same day is resign now (S2-3)');
        $this->assertSame(RefusalReason::ScheduledDateNotAfterDeclaration, self::schedule(self::admitted(), 'D1', 10, 9));
    }

    public function test_there_must_be_a_current_period_that_has_not_ended_by_the_scheduled_date(): void
    {
        $this->assertSame(RefusalReason::PeriodNotFound, self::schedule(MemberHistory::start()));
        $this->assertSame(RefusalReason::PeriodNotFound, self::schedule(self::admitted(), 'D1', 0, 30), 'before the admission');

        $h = self::admitted();
        $h->commit($h->end('E1', 'A1', 20));
        $this->assertSame(RefusalReason::PeriodNotFound, self::schedule($h, 'D1', 25, 30), 'already ended at the declaration');
        $this->assertSame(RefusalReason::PeriodEnded, self::schedule($h, 'D2', 15, 30), 'it ends at 20, before the scheduled 30');
    }

    public function test_one_scheduled_declaration_per_period(): void
    {
        $h = self::admitted();
        $first = self::schedule($h);

        $this->assertSame(RefusalReason::ScheduledResignationAlreadyPending, self::schedule($h, 'D2', 11, 40, [$first]));
        $this->assertSame(RefusalReason::DuplicateDeclarationId, self::schedule($h, 'D1', 11, 40, [$first->withdraw(self::t(12))]));
        $this->assertInstanceOf(ScheduledResignation::class, self::schedule($h, 'D2', 13, 40, [$first->withdraw(self::t(12))]), 'after a withdrawal, a new one');
    }

    public function test_withdrawal_only_before_the_scheduled_date_starts(): void
    {
        $d = self::schedule(self::admitted());

        $this->assertSame(ScheduledResignationState::Withdrawn, $d->withdraw(self::t(29))->state());
        $this->assertSame(RefusalReason::WithdrawalAfterEffectiveDate, $d->withdraw(self::t(30)), 'the date has started (D-201)');
        $this->assertSame(RefusalReason::WithdrawalAfterEffectiveDate, $d->withdraw(self::t(31)));
        $this->assertSame(RefusalReason::DeclarationNotScheduled, $d->withdraw(self::t(20))->withdraw(self::t(21)));
    }

    public function test_mi3_a_declaration_is_superseded_when_its_period_ends_earlier(): void
    {
        $d = self::schedule(self::admitted());

        $superseded = $d->supersede('the period ended earlier: E1');
        $this->assertSame(ScheduledResignationState::Superseded, $superseded->state());
        $this->assertSame('the period ended earlier: E1', $superseded->supersededBecause());
        $this->assertSame(RefusalReason::DeclarationNotScheduled, $superseded->supersede('again'));
        $this->assertSame(RefusalReason::DeclarationNotScheduled, $d->withdraw(self::t(20))->supersede('x'));
    }

    public function test_mi15a_execution_on_the_date_records_the_end_on_that_date_decided_by_the_declaration(): void
    {
        $h = self::admitted();
        $d = self::schedule($h);

        $decision = $h->member->decideScheduledResignationEnd(FactId::fromString('E1'), $d, self::t(30));

        $this->assertTrue($decision->isAccepted());
        $end = $decision->fact();
        $this->assertInstanceOf(End::class, $end);
        $this->assertSame(['resignation', 30, 'A1'], [$end->kind()->value(), $end->effectiveAt()->ordinal(), $end->period()->value()]);
        $this->assertSame('D1', $end->provenance()->declarationRef(), 'traceable to its declaration');
        $this->assertSame('P1', $end->provenance()->actorOrSource(), 'the answer stored with the declaration');
        $this->assertSame(5_000, $end->decidedAt()?->ordinal(), 'decided when the member declared, not when executed (D-205 (2))');
        $h->commit($decision);
        $this->assertTrue($h->member->resolve()->isMember(self::t(29)));
        $this->assertFalse($h->member->resolve()->isMember(self::t(30)));
    }

    public function test_mi15b_late_execution_still_takes_effect_on_the_scheduled_date(): void
    {
        $decision = self::admitted()->member->decideScheduledResignationEnd(FactId::fromString('E1'), self::schedule(self::admitted()), self::t(45));

        $this->assertSame(30, $decision->fact()->effectiveAt()->ordinal(), 'D-223: backdating, effective on the scheduled date');
    }

    public function test_mi15c_execution_before_the_scheduled_date_is_refused(): void
    {
        $h = self::admitted();

        $this->assertSame(RefusalReason::ExecutionBeforeEffectiveDate, $h->member->decideScheduledResignationEnd(FactId::fromString('E1'), self::schedule($h), self::t(29))->refusal());
    }

    public function test_only_a_scheduled_declaration_can_be_executed(): void
    {
        $h = self::admitted();
        $d = self::schedule($h);

        foreach ([$d->withdraw(self::t(20)), $d->supersede('x'), $d->markExecuted(FactId::fromString('E0'))] as $other) {
            $this->assertSame(RefusalReason::DeclarationNotScheduled, $h->member->decideScheduledResignationEnd(FactId::fromString('E1'), $other, self::t(30))->refusal(), $other->state()->value);
        }
    }

    public function test_execution_is_refused_when_the_period_has_ended_meanwhile(): void
    {
        $h = self::admitted();
        $d = self::schedule($h);
        $h->commit($h->end('E9', 'A1', 20));

        $this->assertSame(RefusalReason::PeriodAlreadyEnded, $h->member->decideScheduledResignationEnd(FactId::fromString('E1'), $d, self::t(30))->refusal(), 'the declaration is to be superseded instead (MI-3)');
    }

    public function test_execution_uses_a_fresh_fact_id(): void
    {
        $h = self::admitted();

        $this->assertSame(RefusalReason::DuplicateFactId, $h->member->decideScheduledResignationEnd(FactId::fromString('A1'), self::schedule($h), self::t(30))->refusal());
    }

    public function test_marking_executed_keeps_the_end_reference_and_the_history(): void
    {
        $executed = self::schedule(self::admitted())->markExecuted(FactId::fromString('E1'));

        $this->assertSame(ScheduledResignationState::Executed, $executed->state());
        $this->assertSame('E1', $executed->executedAs()->value());
        $this->assertSame([10, 30], [$executed->declarationDate()->ordinal(), $executed->effectiveDate()->ordinal()]);
        $this->assertSame(RefusalReason::DeclarationNotScheduled, $executed->markExecuted(FactId::fromString('E2')));
    }

    public function test_mi1_mi15f_the_exception_applies_to_no_other_end_and_no_other_fact(): void
    {
        $h = self::admitted();

        $this->assertSame(RefusalReason::EffectiveDateAfterDecisionDate,
            $h->member->decideEnd(FactId::fromString('E1'), PeriodId::fromString('A1'), self::t(30), self::t(10), \App\Contexts\Membership\Domain\Organisational\EndKind::fromString('resignation'), Provenance::decision('P1'))->refusal(),
            'an ordinary End is still not future-dated');
        $this->assertSame(RefusalReason::EffectiveDateAfterDecisionDate, $h->renew('R1', 30, 200, 10)->refusal());
        $this->assertSame(RefusalReason::EffectiveDateAfterDecisionDate, $h->suspend('S1', 30, null, 10)->refusal());
    }

    public function test_the_declaration_needs_a_finite_scheduled_date(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        ScheduledResignation::schedule(DeclarationId::fromString('D1'), self::admitted()->member->resolve(), [], self::t(10), TimePoint::infinity(), Provenance::decision('P1'), null);
    }
}
