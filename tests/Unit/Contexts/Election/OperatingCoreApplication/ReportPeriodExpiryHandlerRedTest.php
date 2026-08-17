<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCoreApplication;

use App\Contexts\Election\Domain\OperatingCore\Committee\VacancyGround;
use App\Contexts\Election\Domain\OperatingCore\Event\ElectionCancelledOnRestorationExpiry;
use App\Contexts\Election\Domain\OperatingCore\Event\RecoveryPeriodExpired;
use App\Contexts\Election\Domain\OperatingCore\Gate\AcceptancePosition;
use App\Contexts\Election\Domain\OperatingCore\Port\HistoryKind;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;

/**
 * EM-IMPL-002 RED — UC-4 `ReportPeriodExpiry` (F-7/F-8 consequence flows).
 *
 * RESPONSIBILITY TABLE (G-2):
 *  receive the report                → Application (this handler); the reporter is
 *                                      Infrastructure, a LATER increment — it
 *                                      REPORTS only (EM-OPEN-047 resolution)
 *  authenticate/authorize the caller → expressly NOT here — reporting is not
 *                                      authority (A-4); P-6 can refuse any report
 *  decide the consequence            → Domain: P-6 `ExpiryConsequence` is the ONLY
 *                                      place the answer exists (EM-GOV-063/058)
 *  record fact / refusal             → ProtocolAppend (Lifecycle history)
 *  persist                           → Infrastructure — later increment
 *
 * Pins: the rule's consequence (never the clock's or the reporter's), NO aggregate
 * mutation (§4), the Inoperative guard on the halted branch (EM-GOV-062), and Q-3
 * (a refused report appends a refusal record, never a fact).
 *
 * Q-2 note (UC-4): duplicate-report deduplication is NOT answerable without
 * persistence — the handler has no protocol read surface in this increment. It is
 * deliberately NOT pinned here; it belongs to ADR candidate A-2 / Increment 3.
 * (Named openly per the grant: never silently.)
 */
final class ReportPeriodExpiryHandlerRedTest extends OperatingCoreApplicationTestCase
{
    /** F-7 / EM-GOV-063: expired halted-recovery ⇒ the terminal consequence, rendered ELECTION DISCONTINUED (EM-GOV-069) — recorded in the Lifecycle history. */
    public function test_an_expired_halted_recovery_report_appends_the_terminal_consequence(): void
    {
        $this->seedCommittee('s1', 's2', 's3'); // fully occupied — Operative
        $gate = $this->seedGate(3);
        $gate->expressPosition($this->seat('s1'), AcceptancePosition::Object, $this->at(10));
        $gate->expressPosition($this->seat('s2'), AcceptancePosition::Object, $this->at(20)); // halt by decision — recovery never succeeded
        $this->seedRecoveryProcess(PeriodKind::HaltedElectionRecovery, 0); // accruing since 0

        $this->instants->setNowEpoch(4_000); // elapsed 4000 ≥ 3600 — genuinely expired on recorded intervals
        $this->reportExpiryHandler()->handle($this->expiryReportCommand(PeriodKind::HaltedElectionRecovery));

        $this->assertSame([RecoveryPeriodExpired::class], $this->protocol->eventClassSequence(), 'F-7: exactly the terminal consequence fact.');
        $this->assertSame([HistoryKind::Lifecycle], $this->protocol->eventKindSequence(), 'The consequence is lifecycle history (§4).');

        /** @var RecoveryPeriodExpired $expired */
        $expired = $this->protocol->entriesOfEvent(RecoveryPeriodExpired::class)[0]->event;
        $this->assertTrue($expired->recoveryFailed, 'EM-GOV-063: expiry + failed recovery + resulting state — three facts recorded together.');
        $this->assertSame('Election Discontinued', $expired->resultingState->businessRendering(), 'EM-GOV-069: the ONE governed terminal rendering.');

        $this->assertSame(0, $this->committees->saveCount + $this->gates->saveCount + $this->recoveries->saveCount, '§4: UC-4 mutates NO aggregate — P-6 evaluates and the handler appends.');
    }

    /** F-8 / EM-GOV-058: expired restoration period while Inoperative ⇒ ELECTION-LEVEL cancellation — an Election Rule, never a service decision. */
    public function test_an_expired_restoration_report_appends_the_election_level_cancellation(): void
    {
        $committee = $this->seedCommittee('s1', 's2', 's3');
        $this->seedGate(3);
        $committee->recordVacancy($this->seat('s1'), VacancyGround::DeathOrPermanentIncapacity, null, $this->at(10));
        $committee->recordVacancy($this->seat('s2'), VacancyGround::DeathOrPermanentIncapacity, null, $this->at(20)); // Inoperative — the restoration branch's own region
        $this->seedRecoveryProcess(PeriodKind::CommitteeRestoration, 0);

        $this->instants->setNowEpoch(8_000); // elapsed 8000 ≥ 7200 — expired
        $this->reportExpiryHandler()->handle($this->expiryReportCommand(PeriodKind::CommitteeRestoration));

        $this->assertSame([ElectionCancelledOnRestorationExpiry::class], $this->protocol->eventClassSequence(), 'F-8: exactly the election-level cancellation fact.');
        $this->assertSame([HistoryKind::Lifecycle], $this->protocol->eventKindSequence());

        /** @var ElectionCancelledOnRestorationExpiry $cancelled */
        $cancelled = $this->protocol->entriesOfEvent(ElectionCancelledOnRestorationExpiry::class)[0]->event;
        $this->assertSame('Election Cancelled', $cancelled->cancellation->businessRendering(), 'D-9/B-4: Election Cancelled ≠ Election Discontinued ≠ opportunity-cancelled — never one value.');

        $this->assertSame(0, $this->committees->saveCount + $this->gates->saveCount + $this->recoveries->saveCount, '§4: no aggregate mutation.');
    }

    /** Q-3 / §8d: a report P-6 refuses (not expired on recorded intervals) appends a REFUSAL record — never a fact; the clock produced no event. */
    public function test_q3_an_unexpired_report_is_refused_and_recorded_as_a_refusal_never_a_fact(): void
    {
        $this->seedCommittee('s1', 's2', 's3');
        $gate = $this->seedGate(3);
        $gate->expressPosition($this->seat('s1'), AcceptancePosition::Object, $this->at(10));
        $gate->expressPosition($this->seat('s2'), AcceptancePosition::Object, $this->at(20));
        $this->seedRecoveryProcess(PeriodKind::HaltedElectionRecovery, 0);

        $this->instants->setNowEpoch(1_000); // elapsed 1000 < 3600 — not expired
        $this->toleratingDomainRefusal(fn () => $this->reportExpiryHandler()->handle($this->expiryReportCommand(PeriodKind::HaltedElectionRecovery)));

        $this->assertSame([], $this->protocol->eventClassSequence(), 'Q-3: a refused report appends NO fact — the consequence never existed.');
        $this->assertCount(1, $this->protocol->refusals(), 'F-PROTO-1 property 6: the refusal is recorded independently of any state success.');
        $this->assertStringContainsString('has not expired', $this->protocol->refusals()[0]->reason, 'The refusal carries P-6\'s business reason (EM-GOV-063).');
    }

    /** EM-GOV-062/063: the terminal consequence cannot fire while Inoperative — the halted clock never runs there; the report is refused and recorded. */
    public function test_a_halted_branch_report_while_inoperative_is_refused(): void
    {
        $committee = $this->seedCommittee('s1', 's2', 's3');
        $gate = $this->seedGate(3);
        $gate->expressPosition($this->seat('s1'), AcceptancePosition::Object, $this->at(10));
        $gate->expressPosition($this->seat('s2'), AcceptancePosition::Object, $this->at(20));
        $halted = $this->seedRecoveryProcess(PeriodKind::HaltedElectionRecovery, 0);
        $halted->pause($this->at(3_700)); // already past the duration when the Inoperative onset paused it
        $committee->recordVacancy($this->seat('s1'), VacancyGround::DeathOrPermanentIncapacity, null, $this->at(3_700));
        $committee->recordVacancy($this->seat('s2'), VacancyGround::DeathOrPermanentIncapacity, null, $this->at(3_700)); // Inoperative now

        $this->instants->setNowEpoch(5_000);
        $this->toleratingDomainRefusal(fn () => $this->reportExpiryHandler()->handle($this->expiryReportCommand(PeriodKind::HaltedElectionRecovery)));

        $this->assertSame(0, $this->protocol->countEventsOf(RecoveryPeriodExpired::class), 'EM-GOV-062: no terminal consequence while Inoperative.');
        $this->assertCount(1, $this->protocol->refusals(), 'The refusal is recorded with its reason (EM-GOV-005).');
        $this->assertStringContainsString('Inoperative', $this->protocol->refusals()[0]->reason);
    }
}
