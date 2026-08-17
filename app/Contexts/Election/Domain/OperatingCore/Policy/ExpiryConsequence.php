<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Policy;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Condition\ElectionLevelCancellation;
use App\Contexts\Election\Domain\OperatingCore\Condition\HaltedAtGate;
use App\Contexts\Election\Domain\OperatingCore\Condition\OperationalCondition;
use App\Contexts\Election\Domain\OperatingCore\Condition\TerminalStatePlaceholder;
use App\Contexts\Election\Domain\OperatingCore\Event\ElectionCancelledOnRestorationExpiry;
use App\Contexts\Election\Domain\OperatingCore\Event\RecoveryPeriodExpired;
use App\Contexts\Election\Domain\OperatingCore\Exception\ExpiryConsequencePreconditionNotMet;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;
use App\Contexts\Election\Domain\OperatingCore\Recovery\RecoveryProcess;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;

/**
 * P-6 `ExpiryConsequence` (stateless): expiry is REPORTED (EM-OPEN-047 resolution;
 * `ReportPeriodExpiry` driving port); the Election rule alone supplies the
 * consequence — the clock decides nothing and exercises nobody's authority
 * (EM-GOV-063, 058, 006; EM-VOT-004 boundary; EM-ARCH-001 §2d/§2e).
 */
final class ExpiryConsequence
{
    private function __construct()
    {
    }

    /**
     * Halted-recovery expiry ⇒ terminal: THREE facts, not one — halt present ∧
     * governed period expired ∧ recovery not succeeded (EM-GOV-063). Cannot fire
     * while Inoperative: the halted clock never runs there (EM-GOV-062).
     * The returned event records the expiry, the failure of recovery, and the
     * resulting state — EM-GOV-063's one genuinely new recording obligation.
     */
    public static function onHaltedRecoveryExpiry(
        ElectionId $electionId,
        HaltedAtGate $halt,
        RecoveryProcess $process,
        RecordedInstant $reportedAt,
        bool $recoverySucceeded,
        OperationalCondition $operational,
    ): RecoveryPeriodExpired {
        if ($process->kind() !== PeriodKind::HaltedElectionRecovery) {
            throw new ExpiryConsequencePreconditionNotMet('The terminal consequence evaluates the halted-recovery period only (EM-GOV-063, 059(a)).');
        }
        if ($operational === OperationalCondition::Inoperative) {
            throw new ExpiryConsequencePreconditionNotMet('The terminal consequence cannot fire while the election is Inoperative — the halted clock never runs there (EM-GOV-062, 063).');
        }
        if ($recoverySucceeded) {
            throw new ExpiryConsequencePreconditionNotMet('Recovery succeeded: no terminal consequence exists (EM-GOV-063 — three facts, not one).');
        }
        if (! $process->isExpiredAt($reportedAt)) {
            throw new ExpiryConsequencePreconditionNotMet('The governed period has not expired on the recorded intervals (EM-GOV-063).');
        }

        return new RecoveryPeriodExpired(
            $electionId,
            PeriodKind::HaltedElectionRecovery,
            $reportedAt,
            true,
            TerminalStatePlaceholder::electionDiscontinued(),
        );
    }

    /**
     * Restoration-period expiry while Inoperative ⇒ ELECTION-LEVEL cancellation —
     * "cannot restore" is evidenced solely by that expiry (EM-GOV-065); the
     * consequence is an Election Rule, never a service decision (EM-GOV-058).
     */
    public static function onRestorationExpiry(
        ElectionId $electionId,
        RecoveryProcess $process,
        RecordedInstant $reportedAt,
    ): ElectionCancelledOnRestorationExpiry {
        if ($process->kind() !== PeriodKind::CommitteeRestoration) {
            throw new ExpiryConsequencePreconditionNotMet('Cancellation on expiry evaluates the Committee-restoration period only (EM-GOV-058, 059(a)).');
        }
        if (! $process->isExpiredAt($reportedAt)) {
            throw new ExpiryConsequencePreconditionNotMet('The restoration period has not expired on the recorded intervals (EM-GOV-058).');
        }

        return new ElectionCancelledOnRestorationExpiry(
            $electionId,
            $reportedAt,
            ElectionLevelCancellation::onRestorationExpiry(),
        );
    }
}
