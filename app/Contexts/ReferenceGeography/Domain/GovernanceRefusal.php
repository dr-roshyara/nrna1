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
}
