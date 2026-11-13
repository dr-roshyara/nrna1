<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

enum RevisionRefusal: string
{
    case NotDraft = 'not-draft';
    case NotSubmitted = 'not-submitted';
    case Withdrawn = 'withdrawn';
    case NothingToReprepare = 'nothing-to-reprepare';
    case SlotOverlapInRevision = 'slot-overlap-in-revision';
    case UnknownClaim = 'unknown-claim';
    case EvidenceChangedUnderSameIdentifier = 'evidence-changed-under-same-identifier';
    case Incomplete = 'incomplete';
    case ApprovalNamesAnotherVersion = 'approval-names-another-version';
    case NotValidated = 'not-validated';
    case Invalid = 'invalid';
}
