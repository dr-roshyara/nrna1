<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCoreApplication;

use App\Contexts\Election\Domain\OperatingCore\Event\CommitteePositionExpressed;
use App\Contexts\Election\Domain\OperatingCore\Event\GateFailedByDecision;
use App\Contexts\Election\Domain\OperatingCore\Event\GateSatisfied;
use App\Contexts\Election\Domain\OperatingCore\Event\RecoveryPeriodStarted;
use App\Contexts\Election\Domain\OperatingCore\Exception\SeatAlreadyExpressedPosition;
use App\Contexts\Election\Domain\OperatingCore\Gate\AcceptancePosition;
use App\Contexts\Election\Domain\OperatingCore\Port\HistoryKind;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;

/**
 * EM-IMPL-002 RED — UC-1 `ExpressCommitteePosition` (F-2 consequence sequence).
 *
 * RESPONSIBILITY TABLE (G-2):
 *  receive command                       → Application (this handler)
 *  authenticate/authorize the caller     → expressly NOT here (upstream — A-2/Q-1;
 *                                          seat identity arrives as an opaque reference)
 *  decide election meaning (I-7…I-12,
 *  P-1/P-2 classification, the decision) → Domain (frozen AG-2 + policies)
 *  record fact / refusal                 → Domain fact via ProtocolAppend (P-2H kind set per fact)
 *  persist                               → Infrastructure — later increment (in-memory doubles here)
 *
 * Pins: RED-2 (Meaning-1 — UC-1 permitted while Inoperative), the F-2 sequence
 * (A-9 traceability: DecidedFailure → halt recorded → period started with
 * PolicyBinding), RED-3 (no lifecycle advancement on GateSatisfied), I-12
 * (dissent recorded after pass), consequence-fact re-issue idempotence (Q-2/A-2,
 * answerable without persistence via re-derivation), and Q-2 duplicate handling.
 */
final class ExpressCommitteePositionHandlerRedTest extends OperatingCoreApplicationTestCase
{
    /**
     * RED-2 / W-2 (Meaning-1, adopted EM-GOV-070/071 — Reading A): decision FACTS
     * are recordable while the election is Inoperative; only the progression
     * TRANSITION defers (Meaning-2). A guard here would be Reading B, which the
     * PO did not choose.
     */
    public function test_red2_position_expression_is_permitted_while_inoperative(): void
    {
        $committee = $this->seedCommittee('s1', 's2', 's3');
        $gate = $this->seedGate(3);
        // Two recorded vacancies: non-vacant 1 < required 2 — Inoperative (P-3 arithmetic).
        $committee->recordVacancy($this->seat('s1'), \App\Contexts\Election\Domain\OperatingCore\Committee\VacancyGround::DeathOrPermanentIncapacity, null, $this->at(100));
        $committee->recordVacancy($this->seat('s2'), \App\Contexts\Election\Domain\OperatingCore\Committee\VacancyGround::DeathOrPermanentIncapacity, null, $this->at(200));
        $this->assertTrue($committee->unableToFunction($gate->requiredVotes()), 'Fixture: the election is Inoperative.');

        $this->instants->setNowEpoch(1_000);
        $this->expressPositionHandler()->handle($this->expressCommand('s3', AcceptancePosition::Accept));

        $this->assertSame(1, $this->protocol->countEventsOf(CommitteePositionExpressed::class), 'Meaning-1: the position fact is recorded while Inoperative (EM-GOV-071).');
        $this->assertCount(0, $this->protocol->refusals(), 'No Inoperative guard may refuse expression (W-2; EM-GOV-066: a cast vote stands).');
        $entry = $this->protocol->entriesOfEvent(CommitteePositionExpressed::class)[0];
        $this->assertSame(HistoryKind::ProgressionDecision, $entry->kind, 'Position facts belong to the progression-decision history (P-2H).');
    }

    /**
     * F-2 (A-9 traceability, pinned verbatim from the approved flow): decided
     * failure ⇒ halt recorded ⇒ halted-recovery period started with the policy
     * binding captured at start — orchestrated by the handler, decided by the
     * domain (P-2), never the other way around.
     */
    public function test_f2_decided_failure_appends_halt_and_starts_halted_recovery_with_policy_binding(): void
    {
        $this->seedCommittee('s1', 's2', 's3');
        $this->seedGate(3);
        $handler = $this->expressPositionHandler();

        $this->instants->setNowEpoch(1_000);
        $handler->handle($this->expressCommand('s1', AcceptancePosition::Object));
        $this->instants->setNowEpoch(2_000);
        $handler->handle($this->expressCommand('s2', AcceptancePosition::Object)); // R-F2: at 3/2, two objections decide failure

        $this->assertSame(
            [CommitteePositionExpressed::class, CommitteePositionExpressed::class, GateFailedByDecision::class, RecoveryPeriodStarted::class],
            $this->protocol->eventClassSequence(),
            'F-2: position fact → GateFailedByDecision → RecoveryPeriodStarted — and nothing else.'
        );
        $this->assertSame(
            [HistoryKind::ProgressionDecision, HistoryKind::ProgressionDecision, HistoryKind::ProgressionDecision, HistoryKind::Lifecycle],
            $this->protocol->eventKindSequence(),
            'P-2H: gate-outcome facts are progression-decision history; the period start is lifecycle history.'
        );

        /** @var GateFailedByDecision $failure */
        $failure = $this->protocol->entriesOfEvent(GateFailedByDecision::class)[0]->event;
        $this->assertSame(2_000, $failure->failedAt->epochSeconds, 'The halt anchors to the causing fact\'s own instant (A-9; DD-1).');

        $this->assertSame([PeriodKind::HaltedElectionRecovery], $this->policies->requestedKinds, 'The snapshot is taken for the halted-recovery period only (§8a; EM-GOV-050(b)).');

        $process = $this->recoveries->find($this->electionId(), PeriodKind::HaltedElectionRecovery);
        $this->assertNotNull($process, 'The halted-recovery period exists as AG-3.');
        $this->assertSame('policy-v1', $process->policyBinding()->policyVersion, 'I-13: policy version bound at start.');
        $this->assertSame(self::HALTED_POLICY_SECONDS, $process->policyBinding()->durationSeconds, 'I-13: duration bound at start.');
        $this->assertSame(600, $process->readingAt($this->at(2_600))->elapsedSeconds, 'The period onset is the halt instant (2000), never the evaluation\'s (DD-1).');
    }

    /**
     * RED-3 / W-6 / B-1: the gate decides ACCEPTANCE, never progression. On
     * DecidedPass exactly `GateSatisfied` is appended — no phase advances, no
     * lifecycle fact appears, no period starts.
     */
    public function test_red3_decided_pass_appends_gate_satisfied_and_nothing_more(): void
    {
        $this->seedCommittee('s1', 's2', 's3');
        $this->seedGate(3);
        $handler = $this->expressPositionHandler();

        $this->instants->setNowEpoch(1_000);
        $handler->handle($this->expressCommand('s1', AcceptancePosition::Accept));
        $this->instants->setNowEpoch(2_000);
        $handler->handle($this->expressCommand('s2', AcceptancePosition::Accept)); // 2 of 3 ⇒ ⌈2·3/3⌉ = 2 reached

        $this->assertSame(
            [CommitteePositionExpressed::class, CommitteePositionExpressed::class, GateSatisfied::class],
            $this->protocol->eventClassSequence(),
            'B-1: NOTHING ELSE follows satisfaction — no phase advance, no lifecycle event, progression stays the Chief\'s.'
        );
        $this->assertSame(
            [HistoryKind::ProgressionDecision, HistoryKind::ProgressionDecision, HistoryKind::ProgressionDecision],
            $this->protocol->eventKindSequence(),
            'P-2H: no lifecycle entry exists on a pass — satisfaction is an acceptance decision, not a transition.'
        );
        $this->assertSame(0, $this->recoveries->saveCount, 'No recovery period has any business on a pass.');
        $this->assertNull($this->recoveries->find($this->electionId(), PeriodKind::HaltedElectionRecovery));
    }

    /** I-12 (EM-GOV-031, 005): dissent is recorded even where the threshold is already achieved. */
    public function test_dissent_is_recorded_even_after_the_threshold_is_achieved(): void
    {
        $this->seedCommittee('s1', 's2', 's3');
        $this->seedGate(3);
        $handler = $this->expressPositionHandler();

        $handler->handle($this->expressCommand('s1', AcceptancePosition::Accept));
        $handler->handle($this->expressCommand('s2', AcceptancePosition::Accept));
        $this->instants->setNowEpoch(3_000);
        $handler->handle($this->expressCommand('s3', AcceptancePosition::Object));

        $this->assertSame(3, $this->protocol->countEventsOf(CommitteePositionExpressed::class), 'I-12: the objection after the pass is a recorded fact.');
        $this->assertCount(0, $this->protocol->refusals(), 'Dissent after achievement is not refused (EM-GOV-031).');
    }

    /**
     * Q-2 / A-2 (idempotence, answerable without persistence): an outcome fact is
     * derived from the recorded positions, so re-deriving after a later expression
     * must never re-issue `GateSatisfied` — the classification did not change.
     */
    public function test_consequence_facts_are_not_reissued_on_further_expressions(): void
    {
        $this->seedCommittee('s1', 's2', 's3');
        $this->seedGate(3);
        $handler = $this->expressPositionHandler();

        $handler->handle($this->expressCommand('s1', AcceptancePosition::Accept));
        $handler->handle($this->expressCommand('s2', AcceptancePosition::Accept));
        $handler->handle($this->expressCommand('s3', AcceptancePosition::Object));

        $this->assertSame(1, $this->protocol->countEventsOf(GateSatisfied::class), 'Q-2/A-2: the outcome fact exists once; a later expression never re-issues it.');
    }

    /**
     * Q-2 (UC-1 duplicate handling) + Q-3: a second expression by the same seat is
     * refused by the domain (I-7); the refusal is RECORDED with the domain reason
     * and no second position fact exists — the first stands (I-8).
     */
    public function test_q2_duplicate_expression_records_a_refusal_and_never_a_second_fact(): void
    {
        $this->seedCommittee('s1', 's2', 's3');
        $gate = $this->seedGate(3);
        $handler = $this->expressPositionHandler();

        $handler->handle($this->expressCommand('s1', AcceptancePosition::Accept));
        $this->toleratingDomainRefusal(fn () => $handler->handle($this->expressCommand('s1', AcceptancePosition::Object)));

        $this->assertSame(1, $this->protocol->countEventsOf(CommitteePositionExpressed::class), 'I-8: the first position stands; never a second fact (Q-2).');
        $this->assertCount(1, $this->protocol->refusals(), 'Q-3: the refused command appends a refusal record, never a fact.');
        $this->assertSame(
            SeatAlreadyExpressedPosition::withId('s1')->getMessage(),
            $this->protocol->refusals()[0]->reason,
            'The refusal carries the domain exception\'s business message (Rule 8; EM-GOV-005).'
        );
        $this->assertSame(AcceptancePosition::Accept, $gate->positions()['s1'], 'The recorded position is unchanged (G-4: the handler cannot bypass the domain).');
    }
}
