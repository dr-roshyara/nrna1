<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\OperatingCore\Handler;

use App\Contexts\Election\Application\OperatingCore\Command\RecordVacancyEventCommand;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Event\ElectionBecameInoperative;
use App\Contexts\Election\Domain\OperatingCore\Event\RecoveryPeriodStarted;
use App\Contexts\Election\Domain\OperatingCore\Exception\SeatAlreadyVacant;
use App\Contexts\Election\Domain\OperatingCore\Gate\GateDesignation;
use App\Contexts\Election\Domain\OperatingCore\Gate\RequiredVotes;
use App\Contexts\Election\Domain\OperatingCore\Policy\InoperativeOnset;
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
 * EM-IMPL-002 — UC-2 handler (GREEN-3 behaviour).
 * Orchestrates only: the domain decides, the protocol records (G-1/G-2).
 * Port wiring pinned by the RED base fixture (§3a).
 *
 * The authorized flow (F-5), and nothing beyond it:
 *   command → load AG-1 + the ESTABLISHED denominator → the domain's operational
 *   verdict BEFORE → ONE domain act (`ElectionCommittee::recordVacancy`) → append
 *   the returned fact → save → P-4 `InoperativeOnset` → where P-4 RETURNS an onset
 *   that this act began: record it, pause an accruing halted period, and
 *   start-or-resume the single restoration allowance.
 *
 * The three named risks of this use case, and how each is answered:
 *
 *  RISK 1 — lifecycle decision leakage. The handler holds NO predicate of its own.
 *  It asks the aggregate (`unableToFunction`) and the policy (`InoperativeOnset::
 *  onsetFor`) and records THEIR answers. There is no `if (vacancies > n)` here and
 *  no application-side notion of what a vacancy means; per GREEN2-001 the onset
 *  fact is appended ONLY from P-4's returned instant, never from a count.
 *
 *  RISK 2 — I-15, one allowance per ELECTION (EM-GOV-061(b): nothing renews,
 *  restarts or extends it). The allowance is located by a repository LOOKUP, not by
 *  a counter: absent → start it once and record the start fact; present → `resume()`
 *  the SAME instance, appending no second start fact and re-binding no policy
 *  (I-13). No second instance of the kind can be created, and no "already started"
 *  flag is stored anywhere (W-4).
 *
 *  RISK 3 — the non-retroactive clock (DD-1; EM-GOV-060). The handler performs NO
 *  time arithmetic: it never subtracts instants, never reconstructs elapsed time,
 *  never reads a clock. It hands the domain a RECORDED INSTANT — P-4's returned
 *  onset, which is the causing event's own instant, never the evaluation's — and
 *  the domain computes every interval itself (`ClockAccrual` inside AG-3).
 *
 * Meaning-1/RED-1 (EM-GOV-070/071): a vacancy is a CONDITION, never an actor. This
 * handler therefore records no acceptance-gate outcome of any kind, and touches the
 * gate only to READ the denominator bound at establishment (EM-GOV-057). OPEN ∧
 * INOPERATIVE is a valid region: the gate's classification is re-derived on the new
 * recorded facts by whoever asks, and stays nobody's decision here.
 *
 * Q-REF (as fixed at GREEN-2): a domain refusal is RECORDED first and then
 * RETHROWN. The catch is narrow — only `SeatAlreadyVacant` (well-formed but
 * rule-refused, I-2). A request naming no constituted seat is a CALLER ERROR that
 * propagates unrecorded (A-5; EM-GOV-005 negative half).
 */
final class RecordVacancyEventHandler
{
    /** The requested act, as named in a recorded refusal (EM-GOV-005). */
    private const ACT = 'record-vacancy-event';

    public function __construct(
        private readonly ElectionCommitteeRepository $committees,
        private readonly AcceptanceGateDecisionRepository $gates,
        private readonly RecoveryProcessRepository $recoveries,
        private readonly ServicePolicySnapshot $policies,
        private readonly ProtocolAppend $protocol,
        private readonly InstantSource $instants,
    ) {
    }

    public function handle(RecordVacancyEventCommand $command): void
    {
        $committee = $this->committees->find($command->electionId)
            ?? throw $this->unresolvedReference(sprintf(
                'No constituted Committee exists for election "%s".',
                $command->electionId->toString(),
            ));

        $required = $this->establishedRequiredVotes($command->electionId);
        $recordedAt = $this->instants->now();

        // The domain's own verdict BEFORE this act (ruling 2 — verdict comparison,
        // never a stored flag). It answers one question only: had the condition
        // already begun? If so, this act cannot be its onset.
        $conditionAlreadyBegun = $committee->unableToFunction($required);

        try {
            // THE one governed act. The returned fact is the truth (I-8, I-2); the
            // handler never constructs a primary fact itself (G-4).
            $fact = $committee->recordVacancy($command->seatId, $command->ground, $command->reason, $recordedAt);
        } catch (SeatAlreadyVacant $refused) {
            $this->protocol->append(ProtocolEntry::refusal(
                HistoryKind::Lifecycle,
                new RefusalRecord(self::ACT, $refused->getMessage(), $recordedAt),
                $recordedAt,
            ));

            throw $refused;
        }

        $this->protocol->append(ProtocolEntry::event(HistoryKind::Lifecycle, $fact, $recordedAt));
        $this->committees->save($committee);

        if ($conditionAlreadyBegun) {
            return; // The onset is already a recorded fact; no repetition renews it (EM-GOV-061(b), 065).
        }

        // P-4 supplies the onset — or null while the Committee remains able to
        // function. The handler decides nothing here; it asks and obeys.
        $onset = InoperativeOnset::onsetFor($committee, $required, $fact->vacatedAt);

        if ($onset !== null) {
            $this->recordOnsetConsequences($command->electionId, $onset);
        }
    }

    /**
     * The consequence sequence of an onset (F-5), each step a separate recorded act
     * anchored to the SAME recorded instant — so ordering distorts no clock (DD-1).
     */
    private function recordOnsetConsequences(ElectionId $electionId, RecordedInstant $onset): void
    {
        $this->protocol->append(ProtocolEntry::event(
            HistoryKind::Lifecycle,
            new ElectionBecameInoperative($electionId, $onset),
            $onset,
        ));

        $this->pauseAccruingHaltedRecovery($electionId, $onset);
        $this->startOrResumeTheRestorationAllowance($electionId, $onset);
    }

    /**
     * EM-GOV-060/062: the halted-recovery clock stops accruing FROM the recorded
     * moment, non-retroactively — the domain's own rule, applied by the domain.
     * Safe by construction: this path runs only where the condition BEGINS with
     * this act, so the election was operative until now and any halted period of
     * this election is therefore still accruing.
     */
    private function pauseAccruingHaltedRecovery(ElectionId $electionId, RecordedInstant $onset): void
    {
        $halted = $this->recoveries->find($electionId, PeriodKind::HaltedElectionRecovery);

        if ($halted === null) {
            return; // No halted period exists: there is nothing to pause.
        }

        $halted->pause($onset);
        $this->recoveries->save($halted);
    }

    /** I-15/I-16: ONE allowance per election — started once, thereafter only resumed. */
    private function startOrResumeTheRestorationAllowance(ElectionId $electionId, RecordedInstant $onset): void
    {
        $restoration = $this->recoveries->find($electionId, PeriodKind::CommitteeRestoration);

        if ($restoration === null) {
            $binding = $this->policies->snapshotFor(PeriodKind::CommitteeRestoration);
            $restoration = RecoveryProcess::start($electionId, PeriodKind::CommitteeRestoration, $binding, $onset);

            $this->protocol->append(ProtocolEntry::event(
                HistoryKind::Lifecycle,
                new RecoveryPeriodStarted($electionId, PeriodKind::CommitteeRestoration, $binding, $onset),
                $onset,
            ));
        } else {
            // The SAME allowance resumes with its remaining portion; no start fact,
            // no policy re-binding, no second instance (EM-GOV-061, I-13, I-16).
            $restoration->resume($onset);
        }

        $this->recoveries->save($restoration);
    }

    /**
     * The denominator ESTABLISHED at the acceptance decision (EM-GOV-057) — read,
     * never calculated: the required Committee Votes are whatever the established
     * decision bound. Both designations of one election bind the same constituted
     * denominator under the one named rule, so the first established decision found
     * answers the question.
     */
    private function establishedRequiredVotes(ElectionId $electionId): RequiredVotes
    {
        foreach (GateDesignation::cases() as $designation) {
            $decision = $this->gates->find($electionId, $designation);

            if ($decision !== null) {
                return $decision->requiredVotes();
            }
        }

        throw $this->unresolvedReference(sprintf(
            'No acceptance decision is established for election "%s"; the denominator cannot be read.',
            $electionId->toString(),
        ));
    }

    /**
     * ⚠️ OPEN — the unknown-aggregate lookup shape is NOT settled (registered
     * condition 3). This is deliberately ONE site per handler with an explicit
     * name, so closing the taxonomy is a mechanical change rather than a hunt.
     * `InvalidArgumentException` is carried over from UC-1 as the SAME provisional
     * shape rather than a second competing one — it is not precedent, and the
     * question is re-raised with this slice.
     */
    private function unresolvedReference(string $message): InvalidArgumentException
    {
        return new InvalidArgumentException($message);
    }
}
