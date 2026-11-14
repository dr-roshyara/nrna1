<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** Why a governance act is not allowed (v3.11 §8; D-84, D-84a, D-88, D-89). */
enum GovernanceRefusal: string
{
    case NotSubmitted = 'not-submitted';
    case NotAppointed = 'not-appointed';
    case SecondPersonRequired = 'second-person-required';
    case ApproverNotIndependent = 'approver-not-independent';
    case ApproverNotAppointedWhenApproving = 'approver-not-appointed-when-approving';
    /** A decider has no conflict declaration for the matter (D-84, D-84a; v3.11 §8 Checks). */
    case ConflictDeclarationMissing = 'conflict-declaration-missing';
    /** A decider declared an organisational interest in a judgment matter (D-84a): the matter waits. */
    case MateriallyConflicted = 'materially-conflicted';
    /** G-6c-4 (D-90, design revision 3 §7): the finding to confirm is not open (closed, confirmed, corrected, or no finding of this publication) */
    case FindingNotOpen = 'finding-not-open';
    /** G-6c-4: the confirmer did not name the published content version's digest */
    case DigestMismatch = 'digest-mismatch';
    /** G-6c-4: the confirmer is known-same as an excluded person or as the counted approver whose defect the finding records */
    case ConfirmerNotIndependent = 'confirmer-not-independent';
    /** G-6c-5 (D-90, design revision 3 §8.2 rows 5 to 7): a correction link needs an OPEN finding on the publication it corrects */
    case CorrectionWithoutOpenFinding = 'correction-without-open-finding';
    /** G-6c-5 (§9, full coverage): the correcting revision does not replace every flagged claim over its whole valid interval */
    case CorrectionIncomplete = 'correction-incomplete';
}
