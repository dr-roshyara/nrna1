<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCoreApplication;

use App\Contexts\Election\Domain\OperatingCore\Committee\VacancyGround;
use App\Contexts\Election\Domain\OperatingCore\Condition\GateIntervalState;
use App\Contexts\Election\Domain\OperatingCore\Event\CommitteeSeatFilled;
use App\Contexts\Election\Domain\OperatingCore\Event\ElectionRestored;
use App\Contexts\Election\Domain\OperatingCore\Event\GateSatisfied;
use App\Contexts\Election\Domain\OperatingCore\Exception\SeatNotVacant;
use App\Contexts\Election\Domain\OperatingCore\Gate\AcceptancePosition;
use App\Contexts\Election\Domain\OperatingCore\Gate\GateDesignation;
use App\Contexts\Election\Domain\OperatingCore\Port\HistoryKind;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;

/**
 * EM-IMPL-002 RED — UC-3 `FillCommitteeSeat` (F-6 consequence sequence).
 *
 * RESPONSIBILITY TABLE (G-2):
 *  receive command                   → Application (this handler) — the entry point
 *                                      a FUTURE D-1 adapter would call; NOTHING
 *                                      inside the codebase invokes it (A-3; D-1)
 *  authenticate/authorize the caller → expressly NOT here — filling belongs to the
 *                                      EXTERNAL organisational authority alone
 *                                      (EM-GOV-028/056; the wall is the design)
 *  decide election meaning (I-4/I-5,
 *  P-3 sufficiency, P-7 target)      → Domain (frozen AG-1 + policies)
 *  record fact / refusal             → ProtocolAppend (P-2H kind set per fact)
 *  persist                           → Infrastructure — later increment
 *
 * Pins: F-6 (restoration → return to the UNRESOLVED gate — restored ability to
 * decide, never a deemed decision, EM-GOV-059(c); halted clock resumes with the
 * REMAINING portion, EM-GOV-061(a)), RED-3 (no lifecycle advancement on
 * restoration), W-8 (Unachievable returns to OPEN — never treated as failure),
 * Q-2 duplicate handling.
 */
final class FillCommitteeSeatHandlerRedTest extends OperatingCoreApplicationTestCase
{
    /**
     * F-6 (A-9 traceability): fill restores sufficiency ⇒ restoration period
     * pauses ⇒ `ElectionRestored` names the unresolved gate (P-7) ⇒ the paused
     * halted-recovery clock resumes with its REMAINING portion.
     */
    public function test_f6_restoring_fill_pauses_restoration_records_return_to_the_unresolved_gate_and_resumes_the_halted_clock(): void
    {
        // Halt at gate First by decision (R-F2: two objections at 3/2), then Inoperative.
        $committee = $this->seedCommittee('s1', 's2', 's3');
        $gate = $this->seedGate(3);
        $gate->expressPosition($this->seat('s1'), AcceptancePosition::Object, $this->at(10));
        $gate->expressPosition($this->seat('s2'), AcceptancePosition::Object, $this->at(20));
        $halted = $this->seedRecoveryProcess(PeriodKind::HaltedElectionRecovery, 20); // started at the halt
        $committee->recordVacancy($this->seat('s1'), VacancyGround::DeathOrPermanentIncapacity, null, $this->at(40));
        $committee->recordVacancy($this->seat('s2'), VacancyGround::DeathOrPermanentIncapacity, null, $this->at(50)); // Inoperative onset
        $halted->pause($this->at(50)); // halted clock paused at the onset (EM-GOV-062)
        $this->seedRecoveryProcess(PeriodKind::CommitteeRestoration, 50); // restoration accruing since 50

        $this->instants->setNowEpoch(100);
        $this->fillSeatHandler()->handle($this->fillCommand('s1'));

        $this->assertSame(
            [CommitteeSeatFilled::class, ElectionRestored::class],
            $this->protocol->eventClassSequence(),
            'F-6: the fill fact, then the restoration fact — and nothing else.'
        );
        $this->assertSame(
            [HistoryKind::Lifecycle, HistoryKind::Lifecycle],
            $this->protocol->eventKindSequence(),
            'P-2H: fill and restoration are lifecycle history.'
        );

        /** @var ElectionRestored $restored */
        $restored = $this->protocol->entriesOfEvent(ElectionRestored::class)[0]->event;
        $this->assertSame(GateDesignation::First, $restored->returnsToGate, 'P-7: restoration returns to the UNRESOLVED gate — ability restored, never a deemed decision (EM-GOV-059(c)).');
        $this->assertSame(100, $restored->restoredAt->epochSeconds, 'The restoration anchors to the fill fact\'s instant.');

        $restoration = $this->recoveries->find($this->electionId(), PeriodKind::CommitteeRestoration);
        $this->assertSame(50, $restoration->readingAt($this->at(200))->elapsedSeconds, 'The restoration clock paused at 100: elapsed stays 100−50 = 50 (EM-GOV-062).');

        $haltedAfter = $this->recoveries->find($this->electionId(), PeriodKind::HaltedElectionRecovery);
        $this->assertSame(
            130,
            $haltedAfter->readingAt($this->at(200))->elapsedSeconds,
            'EM-GOV-061(a): the halted clock resumes its REMAINING portion — (50−20) + (200−100) = 130; no time is created.'
        );
    }

    /** P-3: a fill that does not restore sufficiency records the fill fact alone; the restoration clock keeps accruing. */
    public function test_a_partial_fill_that_does_not_restore_appends_only_the_fill_fact(): void
    {
        $committee = $this->seedCommittee('s1', 's2', 's3', 's4', 's5'); // required ⌈10/3⌉ = 4
        $this->seedGate(5);
        foreach (['s1', 's2', 's3'] as $i => $seatId) {
            $committee->recordVacancy($this->seat($seatId), VacancyGround::DeathOrPermanentIncapacity, null, $this->at(10 + $i));
        } // non-vacant 2 < 4 — Inoperative
        $this->seedRecoveryProcess(PeriodKind::CommitteeRestoration, 50);

        $this->instants->setNowEpoch(100);
        $this->fillSeatHandler()->handle($this->fillCommand('s1'));

        $this->assertSame([CommitteeSeatFilled::class], $this->protocol->eventClassSequence(), 'Non-vacant 3 < required 4: still Inoperative — the fill fact alone.');
        $restoration = $this->recoveries->find($this->electionId(), PeriodKind::CommitteeRestoration);
        $this->assertSame(150, $restoration->readingAt($this->at(200))->elapsedSeconds, 'The restoration clock keeps accruing — the condition persists.');
    }

    /**
     * W-8 / EM-GOV-059(c) + RED-3: restoration returns Unachievable → OPEN by
     * RE-DERIVATION on the new recorded facts — never a stored transition, never
     * a deemed decision, never a lifecycle advancement.
     */
    public function test_w8_restoration_returns_the_unachievable_gate_to_open_and_deems_nothing_decided(): void
    {
        $committee = $this->seedCommittee('s1', 's2', 's3');
        $gate = $this->seedGate(3);
        $committee->recordVacancy($this->seat('s1'), VacancyGround::DeathOrPermanentIncapacity, null, $this->at(10));
        $committee->recordVacancy($this->seat('s2'), VacancyGround::DeathOrPermanentIncapacity, null, $this->at(20));
        $this->seedRecoveryProcess(PeriodKind::CommitteeRestoration, 20);
        $this->assertSame(GateIntervalState::Unachievable, $gate->intervalState($committee), 'Fixture: vacancy arithmetic made the gate Unachievable — never a decided failure (R-F2).');

        $this->instants->setNowEpoch(100);
        $this->fillSeatHandler()->handle($this->fillCommand('s1'));

        $this->assertSame(GateIntervalState::Open, $gate->intervalState($committee), 'EM-GOV-059(c): restoration returns Unachievable to OPEN — re-derived, not stored.');
        $this->assertSame([], $gate->positions(), 'No deemed decision: the gate is never treated as satisfied by nobody\'s acceptance.');
        $this->assertSame(0, $this->protocol->countEventsOf(GateSatisfied::class), 'RED-3: no gate outcome and no lifecycle advancement flows from restoration (W-6/B-1).');
        $this->assertSame(1, $this->protocol->countEventsOf(ElectionRestored::class), 'The restoration itself is recorded.');
    }

    /** Q-2 (UC-3 duplicate handling) + Q-3: filling a non-vacant seat is refused and recorded; never a second fill fact, never a second restoration. */
    public function test_q2_duplicate_fill_records_a_refusal_and_never_a_second_restoration(): void
    {
        $committee = $this->seedCommittee('s1', 's2', 's3');
        $this->seedGate(3);
        $committee->recordVacancy($this->seat('s1'), VacancyGround::DeathOrPermanentIncapacity, null, $this->at(10));
        $committee->recordVacancy($this->seat('s2'), VacancyGround::DeathOrPermanentIncapacity, null, $this->at(20));
        $this->seedRecoveryProcess(PeriodKind::CommitteeRestoration, 20);
        $handler = $this->fillSeatHandler();

        $this->instants->setNowEpoch(100);
        $handler->handle($this->fillCommand('s1'));
        $this->instants->setNowEpoch(200);
        $this->toleratingDomainRefusal(fn () => $handler->handle($this->fillCommand('s1', 'appointee-ref-2')));

        $this->assertSame(1, $this->protocol->countEventsOf(CommitteeSeatFilled::class), 'Q-2: never a second fill fact for an occupied seat.');
        $this->assertSame(1, $this->protocol->countEventsOf(ElectionRestored::class), 'Q-2: never a second restoration fact.');
        $this->assertCount(1, $this->protocol->refusals(), 'Q-3: the refusal is recorded, never absorbed (W-9).');
        $this->assertSame(
            SeatNotVacant::withId('s1')->getMessage(),
            $this->protocol->refusals()[0]->reason,
            'The refusal carries the domain reason (EM-GOV-056; EM-GOV-005).'
        );
    }
}
