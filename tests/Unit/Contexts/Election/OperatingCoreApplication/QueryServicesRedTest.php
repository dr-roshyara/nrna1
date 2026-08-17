<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCoreApplication;

use App\Contexts\Election\Domain\OperatingCore\Committee\VacancyGround;
use App\Contexts\Election\Domain\OperatingCore\Condition\GateIntervalState;
use App\Contexts\Election\Domain\OperatingCore\Gate\AcceptancePosition;
use App\Contexts\Election\Domain\OperatingCore\Gate\GateDesignation;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;

/**
 * EM-IMPL-002 RED — UQ-1…UQ-4, derivation-only queries (proposal §2b):
 * they record NOTHING, store NOTHING, decide NOTHING; zero arithmetic of their
 * own — every answer is a frozen domain derivation (P-2/P-3, DD-1).
 *
 * A-7 (approved QUERY FORM): UQ-2 `ProgressionEligibilityQuery` is the
 * Increment-2 realization of Meaning-2's deferral — the answer-shape, never the
 * act-shape (R-3). It is the exact question the EM-GOV-070/071 registration
 * permits the application layer to ask.
 */
final class QueryServicesRedTest extends OperatingCoreApplicationTestCase
{
    /** UQ-1: the gate interval state, derived via P-2 with the trusted-collaborator guard (I-11) — never stored. */
    public function test_uq1_returns_the_derived_gate_interval_state(): void
    {
        $this->seedCommittee('s1', 's2', 's3');
        $gate = $this->seedGate(3);
        $gate->expressPosition($this->seat('s1'), AcceptancePosition::Accept, $this->at(10));
        $gate->expressPosition($this->seat('s2'), AcceptancePosition::Accept, $this->at(20));

        $query = new \App\Contexts\Election\Application\OperatingCore\Query\GateIntervalStateQuery($this->gates, $this->committees);

        $this->assertSame(GateIntervalState::DecidedPass, $query->execute($this->electionId(), GateDesignation::First));
    }

    /**
     * UQ-2 / Meaning-2 (adopted EM-GOV-071): a decision completed while
     * Inoperative takes effect on progression only when operative again. The
     * query ANSWERS; it never performs, blocks or schedules a transition.
     */
    public function test_uq2_progression_eligibility_defers_while_inoperative_and_holds_when_operative(): void
    {
        $committee = $this->seedCommittee('s1', 's2', 's3');
        $gate = $this->seedGate(3);
        $gate->expressPosition($this->seat('s1'), AcceptancePosition::Accept, $this->at(10));
        $gate->expressPosition($this->seat('s2'), AcceptancePosition::Accept, $this->at(20)); // DecidedPass — the decision stands

        $query = new \App\Contexts\Election\Application\OperatingCore\Query\ProgressionEligibilityQuery($this->gates, $this->committees);

        $this->assertTrue(
            $query->execute($this->electionId(), GateDesignation::First),
            'Operative ∧ DecidedPass: progression can continue — the answer, not the act (B-1: performing it stays the Chief\'s).'
        );

        $committee->recordVacancy($this->seat('s2'), VacancyGround::DeathOrPermanentIncapacity, null, $this->at(30));
        $committee->recordVacancy($this->seat('s3'), VacancyGround::DeathOrPermanentIncapacity, null, $this->at(40)); // Inoperative

        $this->assertFalse(
            $query->execute($this->electionId(), GateDesignation::First),
            'Meaning-2: the completed decision STANDS but takes effect on progression only when operative again (EM-GOV-071).'
        );
    }

    /** UQ-3: a clock reading is a DD-1 computation over recorded intervals — the query adds no arithmetic of its own. */
    public function test_uq3_clock_reading_is_computed_over_the_recorded_intervals(): void
    {
        $process = $this->seedRecoveryProcess(PeriodKind::CommitteeRestoration, 0);
        $process->pause($this->at(600));

        $query = new \App\Contexts\Election\Application\OperatingCore\Query\ClockReadingQuery($this->recoveries);
        $reading = $query->execute($this->electionId(), PeriodKind::CommitteeRestoration, $this->at(1_000));

        $this->assertSame(600, $reading->elapsedSeconds, 'Paused at 600: elapsed is the interval sum (DD-1), not wall time.');
        $this->assertSame(self::RESTORATION_POLICY_SECONDS - 600, $reading->remainingSeconds, 'Remaining derives from the binding captured at start (I-13).');
    }

    /** UQ-4 / EM-OPEN-047 resolution: expiry is a QUESTION — asking is not reporting; no consequence flows from a query. */
    public function test_uq4_period_status_answers_expiry_as_a_question(): void
    {
        $this->seedRecoveryProcess(PeriodKind::HaltedElectionRecovery, 0);
        $this->instants->setNowEpoch(9_999);

        $query = new \App\Contexts\Election\Application\OperatingCore\Query\RecoveryPeriodStatusQuery($this->recoveries);

        $this->assertTrue($query->execute($this->electionId(), PeriodKind::HaltedElectionRecovery, $this->at(4_000)), 'Elapsed 4000 ≥ 3600: expired — as an ANSWER.');
        $this->assertFalse($query->execute($this->electionId(), PeriodKind::HaltedElectionRecovery, $this->at(1_000)), 'Elapsed 1000 < 3600: not expired.');
    }

    /** §2b: queries record nothing, mutate nothing, decide nothing — after every UQ ran, the protocol and repositories are untouched. */
    public function test_queries_record_nothing_and_mutate_nothing(): void
    {
        $committee = $this->seedCommittee('s1', 's2', 's3');
        $gate = $this->seedGate(3);
        $gate->expressPosition($this->seat('s1'), AcceptancePosition::Accept, $this->at(10));
        $gate->expressPosition($this->seat('s2'), AcceptancePosition::Accept, $this->at(20));
        $process = $this->seedRecoveryProcess(PeriodKind::HaltedElectionRecovery, 0);
        $process->pause($this->at(500));

        (new \App\Contexts\Election\Application\OperatingCore\Query\GateIntervalStateQuery($this->gates, $this->committees))
            ->execute($this->electionId(), GateDesignation::First);
        (new \App\Contexts\Election\Application\OperatingCore\Query\ProgressionEligibilityQuery($this->gates, $this->committees))
            ->execute($this->electionId(), GateDesignation::First);
        (new \App\Contexts\Election\Application\OperatingCore\Query\ClockReadingQuery($this->recoveries))
            ->execute($this->electionId(), PeriodKind::HaltedElectionRecovery, $this->at(1_000));
        (new \App\Contexts\Election\Application\OperatingCore\Query\RecoveryPeriodStatusQuery($this->recoveries))
            ->execute($this->electionId(), PeriodKind::HaltedElectionRecovery, $this->at(1_000));

        $this->assertSame([], $this->protocol->entries, 'Asking is not reporting: a query appends nothing — no fact, no refusal.');
        $this->assertSame(0, $this->committees->saveCount + $this->gates->saveCount + $this->recoveries->saveCount, 'A query saves nothing.');
        $this->assertSame([], $this->policies->requestedKinds, 'A query snapshots no policy.');
    }
}
