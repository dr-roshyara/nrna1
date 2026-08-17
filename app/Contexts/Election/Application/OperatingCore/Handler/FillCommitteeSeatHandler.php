<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\OperatingCore\Handler;

use App\Contexts\Election\Application\OperatingCore\Command\FillCommitteeSeatCommand;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Event\ElectionRestored;
use App\Contexts\Election\Domain\OperatingCore\Exception\SeatNotVacant;
use App\Contexts\Election\Domain\OperatingCore\Gate\AcceptanceGateDecision;
use App\Contexts\Election\Domain\OperatingCore\Gate\GateDesignation;
use App\Contexts\Election\Domain\OperatingCore\Port\HistoryKind;
use App\Contexts\Election\Domain\OperatingCore\Port\InstantSource;
use App\Contexts\Election\Domain\OperatingCore\Port\ProtocolAppend;
use App\Contexts\Election\Domain\OperatingCore\Port\ProtocolEntry;
use App\Contexts\Election\Domain\OperatingCore\Port\RefusalRecord;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;
use App\Contexts\Election\Domain\OperatingCore\Repository\AcceptanceGateDecisionRepository;
use App\Contexts\Election\Domain\OperatingCore\Repository\ElectionCommitteeRepository;
use App\Contexts\Election\Domain\OperatingCore\Repository\RecoveryProcessRepository;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;

/**
 * EM-IMPL-002 — UC-3 handler (GREEN-4 behaviour).
 * NO policy-snapshot port: restoration RESUMES remaining portions, it never
 * starts a period (EM-GOV-061(a); §8c). Orchestrates only (G-1/G-2).
 *
 * The authorized flow (F-6), and nothing beyond it:
 *   command → load AG-1 + the ESTABLISHED acceptance decision → the domain's
 *   operational verdict BEFORE → ONE domain act (`ElectionCommittee::fillSeat`) →
 *   append the returned fact → save → the domain's verdict AFTER → where the
 *   condition ENDED with this act: pause the restoration allowance, record the
 *   return to the unresolved gate, and resume the paused halted period with its
 *   REMAINING portion.
 *
 * WHAT THIS HANDLER IS NOT (the GREEN-4 danger — accidental ownership transfer):
 *
 *  · NOT appointment authority. `appointeeReference` is OPAQUE and is passed
 *    through untouched: no eligibility check, no authority validation, no
 *    interpretation of the external body's meaning. Filling arrives only from the
 *    external organisational authority through a FUTURE D-1 adapter; no internal
 *    caller exists (EM-GOV-028/056; A-3; B-3). This handler is that adapter's
 *    future entry point and calls OUT to nobody.
 *
 *  · NOT the author of restoration. Whether the Committee can function again is
 *    the aggregate's own arithmetic (`unableToFunction`, P-3), asked before and
 *    after the act. The handler records the change of the domain's verdict; it
 *    computes no sufficiency of its own (GREEN2-001).
 *
 *  · NOT a gate authority (W-8; EM-GOV-059(c)). Nothing here reopens, closes,
 *    satisfies or fails an acceptance decision, and no acceptance-outcome fact is
 *    ever appended. `Unachievable → OPEN` is not performed at all: the gate's
 *    classification is DERIVED on the new recorded facts by whoever next asks
 *    (P-2/I-11), so the fill fact alone changes the answer. Restoration returns
 *    the ABILITY to decide — never a deemed decision.
 *
 *  · NOT a clock. No instant arithmetic, no elapsed reconstruction: the handler
 *    hands the domain the recording instant and AG-3 computes every interval
 *    (DD-1; EM-GOV-060) — the resumed period continues with its remaining
 *    portion because interval arithmetic creates no time (I-16).
 *
 * Q-REF (as fixed at GREEN-2): a domain refusal is RECORDED first and then
 * RETHROWN. The catch is narrow — only `SeatNotVacant` (well-formed but
 * rule-refused: only a vacancy can be filled, EM-GOV-056). A request naming no
 * constituted seat raises the domain's own `UnknownCommitteeSeat`, which is a
 * CALLER ERROR and propagates unrecorded (A-5; EM-GOV-005 negative half).
 *
 * ⚠️ TWO QUESTIONS DELIBERATELY LEFT OPEN — reported, never improvised:
 *
 *  1. UNKNOWN-AGGREGATE LOOKUP (registered condition 3, and GREEN-4's explicit
 *     instruction): this handler invents NO exception type and copies none. The
 *     absent-aggregate branch is therefore NOT implemented here; the lookups
 *     below return the aggregate the authorized flow requires. Closing the
 *     refusal taxonomy is the prerequisite for writing that branch.
 *
 *  2. RESUMPTION TARGET (`Q-RESTORE`, registered at RED acceptance): P-7
 *     (`ResumptionTarget`, its resolve method) takes a `HaltedAtGate`, and NO port in the
 *     authorized six-port universe can supply one — the protocol is append-only
 *     (no read side), AG-3 carries no gate, and AG-2 records no halt. This
 *     handler therefore does NOT call P-7 and does NOT construct a `HaltedAtGate`
 *     (constructing one would invent the halt fact). It names the election's
 *     ESTABLISHED acceptance decision as the return target — exact in Model A,
 *     where the tests and the record establish one decision, and INSUFFICIENT the
 *     day two designations are established at once. No tie-break rule and no
 *     fallback is authored here. This needs a ruling before a second decision can
 *     ever be established.
 */
final class FillCommitteeSeatHandler
{
    /** The requested act, as named in a recorded refusal (EM-GOV-005). */
    private const ACT = 'fill-committee-seat';

    public function __construct(
        private readonly ElectionCommitteeRepository $committees,
        private readonly AcceptanceGateDecisionRepository $gates,
        private readonly RecoveryProcessRepository $recoveries,
        private readonly ProtocolAppend $protocol,
        private readonly InstantSource $instants,
    ) {
    }

    public function handle(FillCommitteeSeatCommand $command): void
    {
        $committee = $this->committees->find($command->electionId);
        $decision = $this->establishedAcceptanceDecision($command->electionId);
        $required = $decision->requiredVotes();
        $recordedAt = $this->instants->now();

        // The domain's own verdict BEFORE the act (ruling 2 — verdict comparison,
        // never a stored flag): was the condition active at all?
        $conditionWasActive = $committee->unableToFunction($required);

        try {
            // THE one governed act. The appointee reference is passed through
            // opaquely; the returned fact is the truth (I-4/I-8) and the handler
            // never constructs a primary fact itself (G-4).
            $fact = $committee->fillSeat($command->seatId, $command->appointeeReference, $recordedAt);
        } catch (SeatNotVacant $refused) {
            $this->protocol->append(ProtocolEntry::refusal(
                HistoryKind::Lifecycle,
                new RefusalRecord(self::ACT, $refused->getMessage(), $recordedAt),
                $recordedAt,
            ));

            throw $refused;
        }

        $this->protocol->append(ProtocolEntry::event(HistoryKind::Lifecycle, $fact, $recordedAt));
        $this->committees->save($committee);

        if (! $conditionWasActive) {
            return; // Nothing was interrupted, so nothing is restored (I-4: no decision is altered).
        }

        if ($committee->unableToFunction($required)) {
            return; // The domain says the Committee still cannot function: the condition persists.
        }

        $this->recordRestorationConsequences($command->electionId, $decision->gate(), $fact->filledAt);
    }

    /**
     * The consequence sequence of a restoration (F-6), each step anchored to the
     * fill fact's own recorded instant — so ordering distorts no clock (DD-1).
     */
    private function recordRestorationConsequences(
        ElectionId $electionId,
        GateDesignation $returnsToGate,
        RecordedInstant $restoredAt,
    ): void {
        $this->pauseAccruingRestorationAllowance($electionId, $restoredAt);

        $this->protocol->append(ProtocolEntry::event(
            HistoryKind::Lifecycle,
            new ElectionRestored($electionId, $returnsToGate, $restoredAt),
            $restoredAt,
        ));

        $this->resumePausedHaltedRecovery($electionId, $restoredAt);
    }

    /**
     * EM-GOV-062: the restoration clock accrues only while the condition holds, so
     * it stops at the recorded restoration — non-retroactively, by the domain's own
     * rule. The allowance itself is never reset, renewed or extended (I-15).
     * Safe by construction: this path runs only where the condition was active
     * until this act, so any restoration allowance of this election was accruing.
     */
    private function pauseAccruingRestorationAllowance(ElectionId $electionId, RecordedInstant $restoredAt): void
    {
        $restoration = $this->recoveries->find($electionId, PeriodKind::CommitteeRestoration);

        if ($restoration === null) {
            return; // No allowance is running: there is nothing to pause.
        }

        $restoration->pause($restoredAt);
        $this->recoveries->save($restoration);
    }

    /**
     * EM-GOV-061(a)/I-16: the halted period resumes with its REMAINING portion —
     * no new policy is snapshotted, no duration is chosen, no allowance is created.
     * Safe by construction: a halted period of an election whose condition was
     * active is paused (it was paused at the condition's onset).
     */
    private function resumePausedHaltedRecovery(ElectionId $electionId, RecordedInstant $restoredAt): void
    {
        $halted = $this->recoveries->find($electionId, PeriodKind::HaltedElectionRecovery);

        if ($halted === null) {
            return; // No halted period exists: there is nothing to resume.
        }

        $halted->resume($restoredAt);
        $this->recoveries->save($halted);
    }

    /**
     * The election's ESTABLISHED acceptance decision — read, never calculated: it
     * supplies the denominator bound at establishment (EM-GOV-057) and names the
     * gate the election returns to. See question 2 in the class docblock: this is
     * exact for one established decision and needs a ruling for two.
     */
    private function establishedAcceptanceDecision(ElectionId $electionId): ?AcceptanceGateDecision
    {
        foreach (GateDesignation::cases() as $designation) {
            $decision = $this->gates->find($electionId, $designation);

            if ($decision !== null) {
                return $decision;
            }
        }

        // ⚠️ Question 1 (open): nothing is established for this election. This
        // method reports that truthfully as null rather than inventing an exception
        // shape or borrowing a domain exception whose meaning would be false. The
        // absent-aggregate branch belongs to the unresolved refusal taxonomy.
        return null;
    }
}
