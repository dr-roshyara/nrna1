<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCoreApplication;

use App\Contexts\Election\Domain\OperatingCore\Committee\VacancyGround;
use App\Contexts\Election\Domain\OperatingCore\Event\CommitteeSeatVacated;
use App\Contexts\Election\Domain\OperatingCore\Exception\CommitteeTooSmall;
use App\Contexts\Election\Domain\OperatingCore\Exception\SeatAlreadyExpressedPosition;
use App\Contexts\Election\Domain\OperatingCore\Exception\SeatAlreadyVacant;
use App\Contexts\Election\Domain\OperatingCore\Exception\SeatNotVacant;
use App\Contexts\Election\Domain\OperatingCore\Exception\UnknownCommitteeSeat;
use App\Contexts\Election\Domain\OperatingCore\Gate\AcceptancePosition;
use App\Contexts\Election\Domain\OperatingCore\Port\HistoryKind;
use App\Contexts\Election\Domain\OperatingCore\Port\ProtocolEntry;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;

/**
 * EM-IMPL-002 RED — the REFUSAL TAXONOMY (grant item iv; A-5; Q-1/Q-3).
 * One test per baseline domain exception, as the grant requires.
 *
 * DESIGN NOTE (A-5 ruling proposed by RED, subject to independent review —
 * delegated to RED-with-review by proposal §12.5):
 *
 *   The distinguishing principle: a request that is WELL-FORMED AGAINST THE
 *   RECORD but refused by an adopted business rule is a MATERIAL REFUSAL —
 *   recorded with its reason (EM-GOV-005: requests, evaluations, refusals AND
 *   their reasons are material events). A request that REFERENCES SOMETHING THE
 *   RECORD DOES NOT CONTAIN is a CALLER ERROR — it concerns no governed act, so
 *   recording it would create a protocol entry about a subject that never
 *   existed (EM-GOV-005, negative half: no entry for a phase that never
 *   occurred). Caller errors propagate to the caller and are NOT appended.
 *
 *   | Domain exception                     | Classification    |
 *   |--------------------------------------|-------------------|
 *   | SeatAlreadyExpressedPosition (I-7)   | recorded refusal  |
 *   | SeatAlreadyVacant (I-2)              | recorded refusal  |
 *   | SeatNotVacant (EM-GOV-056)           | recorded refusal  |
 *   | CommitteeTooSmall (EM-GOV-033)       | recorded refusal  |
 *   | ExpiryConsequencePreconditionNotMet  | recorded refusal (§8d shows it verbatim) |
 *   | UnknownCommitteeSeat                 | CALLER ERROR — A-5's own example question, answered here |
 *
 *   Refusal HistoryKind follows the kind the refused act's fact would have
 *   carried (position refusals → ProgressionDecision; the rest → Lifecycle) —
 *   extending the P-2H table to refusals; frozen ProtocolAppendContractTest
 *   already exemplifies the position case.
 *
 * Q-3 (pinned): the protocol is appended only AFTER domain acceptance — a
 * refused command yields a refusal record and never a fact.
 */
final class RefusalTaxonomyRedTest extends OperatingCoreApplicationTestCase
{
    public function test_seat_already_expressed_position_is_a_recorded_refusal(): void
    {
        $this->seedCommittee('s1', 's2', 's3');
        $this->seedGate(3);
        $handler = $this->expressPositionHandler();
        $handler->handle($this->expressCommand('s1', AcceptancePosition::Accept));

        $this->toleratingDomainRefusal(fn () => $handler->handle($this->expressCommand('s1', AcceptancePosition::Accept)));

        $this->assertCount(1, $this->protocol->refusals());
        $this->assertSame(SeatAlreadyExpressedPosition::withId('s1')->getMessage(), $this->protocol->refusals()[0]->reason);
        $this->assertSame(HistoryKind::ProgressionDecision, $this->refusalEntryKinds()[0], 'A position refusal belongs to the progression-decision history (P-2H extension).');
    }

    public function test_seat_already_vacant_is_a_recorded_refusal(): void
    {
        $this->seedCommittee('s1', 's2', 's3');
        $this->seedGate(3);
        $handler = $this->recordVacancyHandler();
        $handler->handle($this->vacancyCommand('s1'));

        $this->toleratingDomainRefusal(fn () => $handler->handle($this->vacancyCommand('s1')));

        $this->assertCount(1, $this->protocol->refusals());
        $this->assertSame(SeatAlreadyVacant::withId('s1')->getMessage(), $this->protocol->refusals()[0]->reason);
        $this->assertSame(HistoryKind::Lifecycle, $this->refusalEntryKinds()[0], 'A vacancy refusal belongs to the lifecycle history (P-2H extension).');
    }

    public function test_seat_not_vacant_is_a_recorded_refusal(): void
    {
        $this->seedCommittee('s1', 's2', 's3');
        $this->seedGate(3);

        $this->toleratingDomainRefusal(fn () => $this->fillSeatHandler()->handle($this->fillCommand('s1'))); // occupied seat

        $this->assertCount(1, $this->protocol->refusals());
        $this->assertSame(SeatNotVacant::withId('s1')->getMessage(), $this->protocol->refusals()[0]->reason);
        $this->assertCount(0, $this->protocol->eventEntries(), 'Q-3: no fact from a refused command.');
    }

    public function test_committee_too_small_is_a_recorded_refusal(): void
    {
        $this->toleratingDomainRefusal(fn () => $this->recordConstitutionHandler()->handle($this->constitutionCommand('s1', 's2')));

        $this->assertCount(1, $this->protocol->refusals());
        $this->assertSame(CommitteeTooSmall::withSize(2)->getMessage(), $this->protocol->refusals()[0]->reason);
    }

    public function test_expiry_consequence_precondition_not_met_is_a_recorded_refusal(): void
    {
        $this->seedCommittee('s1', 's2', 's3');
        $gate = $this->seedGate(3);
        $gate->expressPosition($this->seat('s1'), AcceptancePosition::Object, $this->at(10));
        $gate->expressPosition($this->seat('s2'), AcceptancePosition::Object, $this->at(20));
        $this->seedRecoveryProcess(PeriodKind::HaltedElectionRecovery, 0);
        $this->instants->setNowEpoch(100); // far from expiry

        $this->toleratingDomainRefusal(fn () => $this->reportExpiryHandler()->handle($this->expiryReportCommand(PeriodKind::HaltedElectionRecovery)));

        $this->assertCount(1, $this->protocol->refusals(), '§8d verbatim: on precondition failure the handler appends the refusal.');
        $this->assertStringContainsString('EM-GOV-063', $this->protocol->refusals()[0]->reason);
    }

    /**
     * A-5's own example question, answered: a request naming a seat the record
     * never constituted is a CALLER ERROR — it propagates and is NOT recorded
     * (EM-GOV-005 negative half: no protocol entry about a subject that never
     * existed).
     */
    public function test_unknown_committee_seat_is_a_caller_error_and_never_recorded(): void
    {
        $this->seedCommittee('s1', 's2', 's3');
        $this->seedGate(3);

        $caught = null;
        try {
            $this->recordVacancyHandler()->handle($this->vacancyCommand('s9'));
        } catch (UnknownCommitteeSeat $error) {
            $caught = $error;
        }

        $this->assertInstanceOf(UnknownCommitteeSeat::class, $caught, 'A malformed request is the CALLER\'s error — it must reach the caller.');
        $this->assertSame([], $this->protocol->entries, 'Neither a fact nor a refusal: the record holds no entry about a seat that never existed.');
        $this->assertSame(0, $this->protocol->countEventsOf(CommitteeSeatVacated::class));
    }

    /** Q-3, pinned on its own: validate → domain decision → accepted fact → append; a refusal yields a refusal record, never a fact. */
    public function test_q3_protocol_append_happens_only_after_domain_acceptance(): void
    {
        $this->seedCommittee('s1', 's2', 's3');
        $this->seedGate(3);
        $handler = $this->expressPositionHandler();

        // Refused act first: nothing but the refusal may exist afterwards.
        $handler->handle($this->expressCommand('s1', AcceptancePosition::Accept));
        $this->toleratingDomainRefusal(fn () => $handler->handle($this->expressCommand('s1', AcceptancePosition::Object)));

        $this->assertCount(1, $this->protocol->eventEntries(), 'Exactly the accepted act produced a fact.');
        $this->assertCount(1, $this->protocol->refusals(), 'Exactly the refused act produced a refusal.');
        $refusal = $this->protocol->refusals()[0];
        $this->assertNotSame('', trim($refusal->requestedAct), 'The refusal names the requested act (EM-GOV-005).');
        $this->assertNotSame('', trim($refusal->reason), 'The refusal carries its reason (EM-GOV-005).');
    }

    /** @return list<HistoryKind> kinds of the refusal entries, in append order */
    private function refusalEntryKinds(): array
    {
        return array_values(array_map(
            static fn (ProtocolEntry $e) => $e->kind,
            array_filter($this->protocol->entries, static fn (ProtocolEntry $e) => $e->isRefusal()),
        ));
    }
}
