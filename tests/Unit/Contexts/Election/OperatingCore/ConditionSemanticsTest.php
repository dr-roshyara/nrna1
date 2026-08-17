<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCore;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Committee\CommitteeSeatId;
use App\Contexts\Election\Domain\OperatingCore\Committee\ElectionCommittee;
use App\Contexts\Election\Domain\OperatingCore\Committee\VacancyGround;
use App\Contexts\Election\Domain\OperatingCore\Committee\VacancyReason;
use App\Contexts\Election\Domain\OperatingCore\Condition\ElectionOperationalStatus;
use App\Contexts\Election\Domain\OperatingCore\Condition\GateIntervalState;
use App\Contexts\Election\Domain\OperatingCore\Condition\HaltedAtGate;
use App\Contexts\Election\Domain\OperatingCore\Condition\OperationalCondition;
use App\Contexts\Election\Domain\OperatingCore\Condition\TerminalStatePlaceholder;
use App\Contexts\Election\Domain\OperatingCore\Event\ElectionCancelledOnRestorationExpiry;
use App\Contexts\Election\Domain\OperatingCore\Event\RecoveryPeriodExpired;
use App\Contexts\Election\Domain\OperatingCore\Exception\ExpiryConsequencePreconditionNotMet;
use App\Contexts\Election\Domain\OperatingCore\Gate\AcceptanceGateDecision;
use App\Contexts\Election\Domain\OperatingCore\Gate\GateDesignation;
use App\Contexts\Election\Domain\OperatingCore\Gate\RequiredVotes;
use App\Contexts\Election\Domain\OperatingCore\Gate\ThresholdRule;
use App\Contexts\Election\Domain\OperatingCore\Policy\ExpiryConsequence;
use App\Contexts\Election\Domain\OperatingCore\Policy\InoperativeOnset;
use App\Contexts\Election\Domain\OperatingCore\Policy\ResumptionTarget;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PolicyBinding;
use App\Contexts\Election\Domain\OperatingCore\Recovery\RecoveryProcess;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;
use PHPUnit\Framework\TestCase;

/**
 * The four distinct condition semantics and their consequences — P-4, P-6, P-7,
 * HALTED ∧ INOPERATIVE representability (EM-ARCH-001 §2b/§2e/§5d; EM-GOV-058,
 * 059(b)(c), 062, 063, 065; EM-GOV-069). RED tests 23–26 of the readiness
 * report §E, plus the explicit P-7 test.
 */
final class ConditionSemanticsTest extends TestCase
{
    private function at(int $epoch): RecordedInstant
    {
        return RecordedInstant::fromEpochSeconds($epoch);
    }

    private function committeeOfThree(): ElectionCommittee
    {
        return ElectionCommittee::constitute(
            ElectionId::fromString('election-1'),
            CommitteeSeatId::fromString('seat-a'),
            CommitteeSeatId::fromString('seat-b'),
            CommitteeSeatId::fromString('seat-c'),
        );
    }

    /**
     * Test 23 — P-4: Inoperative begins AT the causing recorded vacancy event — no declaration,
     * no determiner; onset is the event's instant, never evaluation time (EM-GOV-065).
     */
    public function test_inoperative_onset_is_the_causing_recorded_event_instant(): void
    {
        $committee = $this->committeeOfThree();
        $required = RequiredVotes::forConstitutedSize(3); // 2

        // First vacancy: 2 non-vacant ≥ 2 required — no onset.
        $committee->recordVacancy(
            CommitteeSeatId::fromString('seat-a'),
            VacancyGround::ResignationWithReason,
            VacancyReason::fromString('stated reason'),
            $this->at(1_000),
        );
        $this->assertNull(InoperativeOnset::onsetFor($committee, $required, $this->at(1_000)));

        // Second vacancy at t=2_000 breaches the arithmetic: onset IS that instant.
        $committee->recordVacancy(
            CommitteeSeatId::fromString('seat-b'),
            VacancyGround::DeathOrPermanentIncapacity,
            null,
            $this->at(2_000),
        );
        $onset = InoperativeOnset::onsetFor($committee, $required, $this->at(2_000));
        $this->assertNotNull($onset);
        $this->assertSame(2_000, $onset->epochSeconds, 'Inoperative begins at the causing recorded event (EM-GOV-065).');
    }

    /** Test 24 — EM-GOV-059(b): HALTED ∧ INOPERATIVE is representable; Inoperative is dominant but never erases the halt. */
    public function test_halted_and_inoperative_are_representable_together_and_the_halt_is_retained(): void
    {
        $halt = new HaltedAtGate(GateDesignation::First, $this->at(1_000));

        $status = ElectionOperationalStatus::operativeHalted($halt);
        $this->assertTrue($status->isHalted());
        $this->assertSame(OperationalCondition::Operative, $status->condition());

        $overlaid = $status->becameInoperative();
        $this->assertSame(OperationalCondition::Inoperative, $overlaid->condition());
        $this->assertTrue($overlaid->isHalted(), 'Inoperative does not erase the halt (EM-GOV-059(b)).');
        $this->assertNotNull($overlaid->haltedAtGate());
        $this->assertSame(GateDesignation::First, $overlaid->haltedAtGate()->gate);
    }

    /**
     * Test 25 — P-6: reported halted-recovery expiry with halt ∧ expired ∧ recovery-not-succeeded
     * records the THREE facts and yields the terminal placeholder (EM-GOV-063); it cannot fire
     * while Inoperative (EM-GOV-062); restoration expiry yields election-level cancellation (EM-GOV-058).
     */
    public function test_expiry_consequences_are_rule_evaluated_never_clock_produced(): void
    {
        $electionId = ElectionId::fromString('election-1');
        $halt = new HaltedAtGate(GateDesignation::First, $this->at(0));

        $halted = RecoveryProcess::start(
            $electionId,
            PeriodKind::HaltedElectionRecovery,
            PolicyBinding::of('service-policy-v1', 100),
            $this->at(0),
        );

        // Reported before expiry: the rule refuses — the clock decides nothing.
        try {
            ExpiryConsequence::onHaltedRecoveryExpiry($electionId, $halt, $halted, $this->at(50), false, OperationalCondition::Operative);
            $this->fail('An unexpired period must yield no consequence.');
        } catch (ExpiryConsequencePreconditionNotMet) {
            // expected
        }

        // While Inoperative the halted clock never ran — the consequence cannot fire (EM-GOV-062/063).
        try {
            ExpiryConsequence::onHaltedRecoveryExpiry($electionId, $halt, $halted, $this->at(200), false, OperationalCondition::Inoperative);
            $this->fail('The terminal consequence cannot fire while the election is Inoperative.');
        } catch (ExpiryConsequencePreconditionNotMet) {
            // expected
        }

        // Recovery succeeded ⇒ no terminal consequence.
        try {
            ExpiryConsequence::onHaltedRecoveryExpiry($electionId, $halt, $halted, $this->at(200), true, OperationalCondition::Operative);
            $this->fail('A succeeded recovery must yield no terminal consequence.');
        } catch (ExpiryConsequencePreconditionNotMet) {
            // expected
        }

        // Halt ∧ expired ∧ failed recovery — three facts, recorded together (EM-GOV-063).
        $event = ExpiryConsequence::onHaltedRecoveryExpiry($electionId, $halt, $halted, $this->at(200), false, OperationalCondition::Operative);
        $this->assertInstanceOf(RecoveryPeriodExpired::class, $event);
        $this->assertTrue($event->recoveryFailed);
        $this->assertSame(200, $event->expiredAt->epochSeconds);
        $this->assertInstanceOf(TerminalStatePlaceholder::class, $event->resultingState);
        $this->assertSame('Election Discontinued', $event->resultingState->businessRendering());

        // Restoration-period expiry ⇒ ELECTION-LEVEL cancellation, an Election Rule's consequence (EM-GOV-058).
        $restoration = RecoveryProcess::start(
            $electionId,
            PeriodKind::CommitteeRestoration,
            PolicyBinding::of('service-policy-v1', 100),
            $this->at(0),
        );
        $cancelled = ExpiryConsequence::onRestorationExpiry($electionId, $restoration, $this->at(150));
        $this->assertInstanceOf(ElectionCancelledOnRestorationExpiry::class, $cancelled);
        $this->assertSame('Election Cancelled', $cancelled->cancellation->businessRendering());

        // Kind discipline: the wrong period kind is refused on either path.
        $this->expectException(ExpiryConsequencePreconditionNotMet::class);
        ExpiryConsequence::onRestorationExpiry($electionId, $halted, $this->at(200));
    }

    /**
     * Test 26 — P-7: restoration returns the election to its prior condition at the UNRESOLVED gate;
     * restored ability to decide, never a deemed decision (EM-GOV-059(c), 060).
     */
    public function test_resumption_returns_to_the_unresolved_gate_never_a_deemed_decision(): void
    {
        $halt = new HaltedAtGate(GateDesignation::Second, $this->at(1_000));

        $this->assertSame(GateDesignation::Second, ResumptionTarget::resolve($halt));

        // The gate itself remains undecided after restoration: nobody's acceptance satisfied it.
        $committee = $this->committeeOfThree();
        $gate = AcceptanceGateDecision::establish(
            ElectionId::fromString('election-1'),
            GateDesignation::Second,
            ThresholdRule::twoThirdsOfCommitteeVotes(),
            3,
        );
        $this->assertSame(
            GateIntervalState::Open,
            $gate->intervalState($committee),
            'Restoration restores the ability to decide — it never treats the gate as satisfied (EM-GOV-059(c)).'
        );
        $this->assertNotSame(GateIntervalState::DecidedPass, $gate->intervalState($committee));
    }
}
