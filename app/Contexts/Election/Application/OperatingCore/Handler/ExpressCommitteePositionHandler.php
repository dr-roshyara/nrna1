<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\OperatingCore\Handler;

use App\Contexts\Election\Application\OperatingCore\Command\ExpressCommitteePositionCommand;
use App\Contexts\Election\Domain\OperatingCore\Condition\GateIntervalState;
use App\Contexts\Election\Domain\OperatingCore\Event\GateFailedByDecision;
use App\Contexts\Election\Domain\OperatingCore\Event\GateSatisfied;
use App\Contexts\Election\Domain\OperatingCore\Event\RecoveryPeriodStarted;
use App\Contexts\Election\Domain\OperatingCore\Exception\SeatAlreadyExpressedPosition;
use App\Contexts\Election\Domain\OperatingCore\Port\HistoryKind;
use App\Contexts\Election\Domain\OperatingCore\Port\InstantSource;
use App\Contexts\Election\Domain\OperatingCore\Port\ProtocolAppend;
use App\Contexts\Election\Domain\OperatingCore\Port\ProtocolEntry;
use App\Contexts\Election\Domain\OperatingCore\Port\RefusalRecord;
use App\Contexts\Election\Domain\OperatingCore\Port\ServicePolicySnapshot;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;
use App\Contexts\Election\Domain\OperatingCore\Recovery\RecoveryProcess;
use App\Contexts\Election\Domain\OperatingCore\Repository\AcceptanceGateDecisionRepository;
use App\Contexts\Election\Domain\OperatingCore\Repository\ElectionCommitteeRepository;
use App\Contexts\Election\Domain\OperatingCore\Repository\RecoveryProcessRepository;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;
use InvalidArgumentException;

/**
 * EM-IMPL-002 — UC-1 handler (GREEN-2 behaviour).
 * Orchestrates only: the domain decides, the protocol records (G-1/G-2).
 * Port wiring pinned by the RED base fixture (§3a — constructor-injected ports
 * only; no new port).
 *
 * The GREEN-2 flow, exactly as authorized:
 *   command → load AG-1/AG-2 → ONE domain act (`AcceptanceGateDecision::
 *   expressPosition`) → the domain's own classification (`intervalState`, P-2) →
 *   protocol append. The handler holds NO rule arithmetic: it never counts,
 *   compares thresholds, or re-derives achievability — it asks the aggregate for
 *   the classification before and after the act and records what the domain's
 *   answer became (W-3 · G-1 · G-5).
 *
 * Q-2/A-2 idempotence without persistence: an outcome fact is appended only where
 * the domain's classification CHANGED from undecided to decided by this act, so a
 * later expression (dissent after a pass — I-12) never re-issues `GateSatisfied`.
 *
 * A-9 (registered, confirmed sequencing — the one place the frozen domain exposes
 * a classification but no consequence factory): `GateSatisfied` /
 * `GateFailedByDecision` / the halted-recovery start have no domain-side onset
 * policy analogous to P-4, so Increment 2 realizes F-2 as handler orchestration
 * traceable to the approved flow. The MEANING is entirely the domain's (P-2
 * decides; the handler branches on the domain's verdict and records it). Whether a
 * domain-side onset policy should exist is a future authorized DOMAIN slice's
 * decision, never this increment's act.
 *
 * Q-REF (fixed here, reported for pin-tightening): a domain refusal is RECORDED
 * first (Q-3 — the refusal record, never a fact) and then RETHROWN, so no caller
 * can mistake a refusal for success while no return shape exists (W-9: never
 * absorbed, never silently retried; repo Rule 8: the domain message is
 * user-visible).
 *
 * A-5 taxonomy (provisionally accepted): only the well-formed-but-rule-refused
 * outcome is caught and recorded (`SeatAlreadyExpressedPosition` — I-7). A request
 * that references nothing in the record (unknown election, unknown gate, a gate
 * belonging to another election) is a CALLER ERROR: it propagates unrecorded, so
 * the protocol holds no entry about a subject that never existed (EM-GOV-005,
 * negative half).
 */
final class ExpressCommitteePositionHandler
{
    /** The requested act, as named in a recorded refusal (EM-GOV-005). */
    private const ACT = 'express-committee-position';

    public function __construct(
        private readonly ElectionCommitteeRepository $committees,
        private readonly AcceptanceGateDecisionRepository $gates,
        private readonly RecoveryProcessRepository $recoveries,
        private readonly ServicePolicySnapshot $policies,
        private readonly ProtocolAppend $protocol,
        private readonly InstantSource $instants,
    ) {
    }

    public function handle(ExpressCommitteePositionCommand $command): void
    {
        $committee = $this->committees->find($command->electionId)
            ?? throw new InvalidArgumentException(sprintf(
                'No constituted Committee exists for election "%s".',
                $command->electionId->toString(),
            ));

        $decision = $this->gates->find($command->electionId, $command->gate)
            ?? throw new InvalidArgumentException(sprintf(
                'No acceptance decision exists for gate "%s" of election "%s".',
                $command->gate->value,
                $command->electionId->toString(),
            ));

        $recordedAt = $this->instants->now();

        // The domain's verdict BEFORE this act — asked, never computed (P-2, I-11).
        $stateBefore = $decision->intervalState($committee);

        try {
            // THE one governed act. The returned fact is the truth (I-8) — the
            // handler never constructs a primary fact itself (G-4).
            $fact = $decision->expressPosition($command->seatId, $command->position, $recordedAt);
        } catch (SeatAlreadyExpressedPosition $refused) {
            $this->protocol->append(ProtocolEntry::refusal(
                HistoryKind::ProgressionDecision,
                new RefusalRecord(self::ACT, $refused->getMessage(), $recordedAt),
                $recordedAt,
            ));

            throw $refused;
        }

        $this->protocol->append(ProtocolEntry::event(HistoryKind::ProgressionDecision, $fact, $recordedAt));
        $this->gates->save($decision);

        // Meaning-1 (EM-GOV-071): the election's operational overlay is NOT consulted
        // anywhere on this path — a decision fact is recordable regardless of it; only
        // the progression TRANSITION defers, and that is nobody's act here (W-2).
        // (The overlay's type names are deliberately absent from this file: the W-2
        // guard scans source text, and the absence is the architecture.)
        $this->recordOutcomeOfDecision($command, $stateBefore, $decision->intervalState($committee), $recordedAt);
    }

    /**
     * Records the outcome the DOMAIN decided, where this act changed the verdict.
     * No arithmetic: the two values compared are the domain's own classifications.
     */
    private function recordOutcomeOfDecision(
        ExpressCommitteePositionCommand $command,
        GateIntervalState $stateBefore,
        GateIntervalState $stateAfter,
        RecordedInstant $recordedAt,
    ): void {
        if ($stateAfter === $stateBefore) {
            return; // the verdict did not change: no new outcome fact (Q-2/A-2).
        }

        if ($stateAfter === GateIntervalState::DecidedPass) {
            $this->protocol->append(ProtocolEntry::event(
                HistoryKind::ProgressionDecision,
                new GateSatisfied($command->electionId, $command->gate, $recordedAt),
                $recordedAt,
            ));

            return; // B-1: NOTHING ELSE — the gate decides acceptance, never progression (W-6).
        }

        if ($stateAfter === GateIntervalState::DecidedFailure) {
            $this->protocol->append(ProtocolEntry::event(
                HistoryKind::ProgressionDecision,
                new GateFailedByDecision($command->electionId, $command->gate, $recordedAt),
                $recordedAt,
            ));

            // A separate act, not one large transaction (§4): the halt anchors to the
            // causing fact's own instant, so sequencing distorts no clock (DD-1).
            $this->startHaltedRecoveryPeriod($command, $recordedAt);
        }

        // Open / Unachievable: no outcome fact exists. Unachievable is vacancy
        // arithmetic, never a decided failure, and restoration returns it to OPEN
        // (W-8; EM-GOV-059(c)).
    }

    /** F-2's third step: the governed period starts with its policy bound at start (EM-GOV-050(b); I-13). */
    private function startHaltedRecoveryPeriod(ExpressCommitteePositionCommand $command, RecordedInstant $haltInstant): void
    {
        $binding = $this->policies->snapshotFor(PeriodKind::HaltedElectionRecovery);

        $process = RecoveryProcess::start(
            $command->electionId,
            PeriodKind::HaltedElectionRecovery,
            $binding,
            $haltInstant,
        );

        $this->protocol->append(ProtocolEntry::event(
            HistoryKind::Lifecycle,
            new RecoveryPeriodStarted($command->electionId, PeriodKind::HaltedElectionRecovery, $binding, $haltInstant),
            $haltInstant,
        ));

        $this->recoveries->save($process);
    }
}
