<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCoreApplication;

use App\Contexts\Election\Domain\OperatingCore\Committee\VacancyGround;
use App\Contexts\Election\Domain\OperatingCore\Condition\GateIntervalState;
use App\Contexts\Election\Domain\OperatingCore\Event\CommitteeSeatVacated;
use App\Contexts\Election\Domain\OperatingCore\Event\ElectionBecameInoperative;
use App\Contexts\Election\Domain\OperatingCore\Event\GateFailedByDecision;
use App\Contexts\Election\Domain\OperatingCore\Event\GateSatisfied;
use App\Contexts\Election\Domain\OperatingCore\Event\RecoveryPeriodStarted;
use App\Contexts\Election\Domain\OperatingCore\Exception\SeatAlreadyVacant;
use App\Contexts\Election\Domain\OperatingCore\Gate\AcceptancePosition;
use App\Contexts\Election\Domain\OperatingCore\Port\HistoryKind;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;

/**
 * EM-IMPL-002 RED — UC-2 `RecordVacancyEvent` (F-5 consequence sequence).
 *
 * RESPONSIBILITY TABLE (G-2):
 *  receive command                   → Application (this handler)
 *  authenticate/authorize the caller → expressly NOT here — the vacancy-recording
 *                                      authority is a NAMED OPEN dependency (M-4/A-6);
 *                                      the shape is validated regardless
 *  decide election meaning (I-2/I-3,
 *  P-3/P-4 onset arithmetic)         → Domain (frozen AG-1 + policies)
 *  record fact / refusal             → ProtocolAppend (P-2H kind set per fact)
 *  persist                           → Infrastructure — later increment
 *
 * Pins: F-5 (Inoperative onset at the causing event's own instant → halted-clock
 * pause → restoration-period start with PolicyBinding), RED-1 behavioural half
 * (a breaching vacancy NEVER produces a gate-outcome fact — OPEN ∧ INOPERATIVE
 * is a valid region, EM-GOV-070), I-15 (one restoration allowance per election —
 * resume, never a second start), Q-2 duplicate handling.
 */
final class RecordVacancyEventHandlerRedTest extends OperatingCoreApplicationTestCase
{
    public function test_a_vacancy_without_onset_appends_only_the_vacancy_fact(): void
    {
        $this->seedCommittee('s1', 's2', 's3');
        $this->seedGate(3);

        $this->instants->setNowEpoch(1_000);
        $this->recordVacancyHandler()->handle($this->vacancyCommand('s1'));

        $this->assertSame([CommitteeSeatVacated::class], $this->protocol->eventClassSequence(), 'Non-vacant 2 ≥ required 2: the Committee still functions — the vacancy fact alone is recorded.');
        $this->assertSame([HistoryKind::Lifecycle], $this->protocol->eventKindSequence(), 'P-2H: committee-structure facts are lifecycle history, never progression decisions.');
        $this->assertNull($this->recoveries->find($this->electionId(), PeriodKind::CommitteeRestoration), 'No restoration period without an onset.');
    }

    /**
     * F-5 (A-9 traceability): the breaching vacancy ⇒ `ElectionBecameInoperative`
     * at THE EVENT'S OWN instant (P-4 — never the evaluation's) ⇒ restoration
     * period started with the policy bound at start (EM-GOV-050(b), 065).
     */
    public function test_f5_breaching_vacancy_records_onset_at_the_events_instant_and_starts_restoration(): void
    {
        $this->seedCommittee('s1', 's2', 's3');
        $this->seedGate(3);
        $handler = $this->recordVacancyHandler();

        $this->instants->setNowEpoch(1_000);
        $handler->handle($this->vacancyCommand('s1'));
        $this->instants->setNowEpoch(2_000);
        $handler->handle($this->vacancyCommand('s2')); // non-vacant 1 < required 2 — onset

        $this->assertSame(
            [CommitteeSeatVacated::class, CommitteeSeatVacated::class, ElectionBecameInoperative::class, RecoveryPeriodStarted::class],
            $this->protocol->eventClassSequence(),
            'F-5: vacancy fact → Inoperative onset → restoration-period start — and nothing else.'
        );
        $this->assertSame(
            [HistoryKind::Lifecycle, HistoryKind::Lifecycle, HistoryKind::Lifecycle, HistoryKind::Lifecycle],
            $this->protocol->eventKindSequence(),
            'P-2H: every F-5 fact is lifecycle history.'
        );

        /** @var ElectionBecameInoperative $onset */
        $onset = $this->protocol->entriesOfEvent(ElectionBecameInoperative::class)[0]->event;
        $this->assertSame(2_000, $onset->onset->epochSeconds, 'P-4: onset = the causing vacancy event\'s instant, never the evaluation\'s (EM-GOV-065).');

        $this->assertSame([PeriodKind::CommitteeRestoration], $this->policies->requestedKinds, 'The snapshot is taken for the restoration period only (§8b).');

        $process = $this->recoveries->find($this->electionId(), PeriodKind::CommitteeRestoration);
        $this->assertNotNull($process, 'The restoration period exists as AG-3.');
        $this->assertSame(self::RESTORATION_POLICY_SECONDS, $process->policyBinding()->durationSeconds, 'I-13: duration bound at start.');
        $this->assertSame(300, $process->readingAt($this->at(2_300))->elapsedSeconds, 'The period accrues from the recorded onset (2000) — DD-1.');
    }

    /** EM-GOV-060 / §8b: the Inoperative onset pauses an accruing halted-recovery clock FROM the recorded moment, non-retroactively. */
    public function test_f5_onset_pauses_an_accruing_halted_recovery_clock_at_the_recorded_moment(): void
    {
        $this->seedCommittee('s1', 's2', 's3');
        $this->seedGate(3);
        $this->seedRecoveryProcess(PeriodKind::HaltedElectionRecovery, 500); // accruing since 500
        $handler = $this->recordVacancyHandler();

        $this->instants->setNowEpoch(1_000);
        $handler->handle($this->vacancyCommand('s1'));
        $this->instants->setNowEpoch(2_000);
        $handler->handle($this->vacancyCommand('s2')); // onset at 2000

        $halted = $this->recoveries->find($this->electionId(), PeriodKind::HaltedElectionRecovery);
        $this->assertNotNull($halted);
        $this->assertSame(
            1_500,
            $halted->readingAt($this->at(3_000))->elapsedSeconds,
            'EM-GOV-060/062: the halted clock pauses at the onset (2000) — elapsed stays 1500, it never runs while Inoperative.'
        );
    }

    /**
     * RED-1 / W-1, behavioural half (adopted EM-GOV-070/071): a condition never
     * becomes an actor — a breaching vacancy produces NO gate-outcome fact, and
     * the gate stays OPEN where a standing accept keeps the threshold achievable:
     * OPEN ∧ INOPERATIVE is a valid region.
     */
    public function test_red1_a_breaching_vacancy_never_closes_or_fails_the_gate(): void
    {
        $committee = $this->seedCommittee('s1', 's2', 's3');
        $gate = $this->seedGate(3);
        $gate->expressPosition($this->seat('s1'), AcceptancePosition::Accept, $this->at(10)); // a standing accept — it stands (EM-GOV-066)
        $handler = $this->recordVacancyHandler();

        $this->instants->setNowEpoch(1_000);
        $handler->handle($this->vacancyCommand('s1'));
        $this->instants->setNowEpoch(2_000);
        $handler->handle($this->vacancyCommand('s2')); // Inoperative onset

        $this->assertSame(0, $this->protocol->countEventsOf(GateFailedByDecision::class), 'RED-1: unableToFunction can NEVER cause gate closure (wrong BY ADOPTED RULE 070/071).');
        $this->assertSame(0, $this->protocol->countEventsOf(GateSatisfied::class), 'No gate outcome of any kind flows from a vacancy.');
        $this->assertSame(1, $this->protocol->countEventsOf(ElectionBecameInoperative::class), 'The condition is recorded as a condition — an input to rules, never an actor.');
        $this->assertSame(
            GateIntervalState::Open,
            $gate->intervalState($committee),
            'EM-GOV-070: OPEN ∧ INOPERATIVE is valid — the standing accept keeps the threshold achievable (1 accept + 1 unexpressed non-vacant ≥ 2).'
        );
    }

    /** I-15 / EM-GOV-061(b): the restoration allowance is per ELECTION — a second onset RESUMES the paused period; nothing renews, restarts or extends it. */
    public function test_i15_second_onset_resumes_the_existing_restoration_period_and_never_starts_a_second(): void
    {
        $this->seedCommittee('s1', 's2', 's3');
        $this->seedGate(3);
        $existing = $this->seedRecoveryProcess(PeriodKind::CommitteeRestoration, 100);
        $existing->pause($this->at(200)); // a prior Inoperative episode consumed 100s of the allowance
        $handler = $this->recordVacancyHandler();

        $this->instants->setNowEpoch(1_000);
        $handler->handle($this->vacancyCommand('s1'));
        $this->instants->setNowEpoch(2_000);
        $handler->handle($this->vacancyCommand('s2')); // second onset

        $this->assertSame(0, $this->protocol->countEventsOf(RecoveryPeriodStarted::class), '§8b: the start fact is appended the FIRST time only — a resume is not a start.');
        $this->assertSame([], $this->policies->requestedKinds, 'I-13: the binding was captured at the original start; a resume never re-binds policy.');

        $process = $this->recoveries->find($this->electionId(), PeriodKind::CommitteeRestoration);
        $this->assertSame($existing, $process, 'I-15: one allowance per election — the same AG-3 instance, never a second.');
        $this->assertSame(
            600,
            $process->readingAt($this->at(2_500))->elapsedSeconds,
            'I-16: the REMAINING portion resumes — (200−100) + (2500−2000) = 600; interval arithmetic creates no time.'
        );
    }

    /** Q-2 (UC-2 duplicate handling) + Q-3: vacating an already-vacant seat is refused; the refusal is recorded; never a second fact, never a second onset. */
    public function test_q2_duplicate_vacancy_records_a_refusal_and_never_a_second_fact(): void
    {
        $this->seedCommittee('s1', 's2', 's3');
        $this->seedGate(3);
        $handler = $this->recordVacancyHandler();

        $this->instants->setNowEpoch(1_000);
        $handler->handle($this->vacancyCommand('s1'));
        $this->toleratingDomainRefusal(fn () => $handler->handle($this->vacancyCommand('s1', VacancyGround::LossOfEligibilityOrIndependence)));

        $this->assertSame(1, $this->protocol->countEventsOf(CommitteeSeatVacated::class), 'Q-2: never a second vacancy fact for the same seat.');
        $this->assertSame(0, $this->protocol->countEventsOf(ElectionBecameInoperative::class), 'A refused command derives no consequence.');
        $this->assertCount(1, $this->protocol->refusals(), 'Q-3: the refusal is recorded, never absorbed silently (W-9).');
        $this->assertSame(
            SeatAlreadyVacant::withId('s1')->getMessage(),
            $this->protocol->refusals()[0]->reason,
            'The refusal carries the domain reason (EM-GOV-005).'
        );
    }
}
