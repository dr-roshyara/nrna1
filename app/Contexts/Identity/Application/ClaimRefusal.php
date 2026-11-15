<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application;

/**
 * Why Identity refused a claim operation (I-2). Recorded in the content-free audit (§15); never shown. Every refusal
 * gives the caller the SAME neutral message (§12): it never reveals whether a person, account or claim exists.
 */
enum ClaimRefusal: string
{
    case NotActing = 'not-acting';                       // R-1: the session holds no R in this organisation
    case UnknownEntry = 'unknown-entry';                 // no PersonRef issued for this local entry
    case UnknownClaim = 'unknown-claim';                 // no such claim in this organisation (ISO-1)
    case EvidenceOrganisation = 'evidence-organisation';
    case EvidenceActor = 'evidence-actor';               // the answer's actor is not the session's own R
    case EvidenceAct = 'evidence-act';
    case EvidenceSubject = 'evidence-subject';
    case EvidenceOutcome = 'evidence-outcome';           // NotAuthorised or Undetermined
    case EvidenceDate = 'evidence-date';                 // evaluatedFor is not the act's date (D-12)
    case EvidenceStale = 'evidence-stale';               // not checked within this request
    case EvidenceReplayed = 'evidence-replayed';         // the answer was already consumed
    case ReferenceBound = 'reference-bound';             // INV-ID-6
    case ActiveClaimExists = 'active-claim-exists';      // INV-CLM-1
    case NotActive = 'not-active';                       // replace / revoke need an active claim (R8-03)

    public function publicMessage(): string
    {
        return 'The claim request could not be completed.';
    }
}
