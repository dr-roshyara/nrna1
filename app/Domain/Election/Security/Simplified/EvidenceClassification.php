<?php

namespace App\Domain\Election\Security\Simplified;

/**
 * EvidenceClassification
 *
 * Describes the classification of evidence provenance in the evaluation pipeline.
 * This is a factual classification of what evidence exists, NOT a trust level.
 *
 * Replaces TrustLevel in the simplified/constitutional runtime.
 * OLD pipeline TrustLevel retains its name (removed in Phase E.3 cutover).
 *
 * Values:
 * - Initial: Baseline state, no additional evidence yet (was Unverified)
 * - Attested: Device or network attested (was Attested)
 * - ContinuityProven: Session continuity established (was ContinuityVerified)
 * - RegistrarConfirmed: Registrar provided official verification (was RegistrarAttested)
 */
enum EvidenceClassification: string
{
    case Initial = 'initial';
    case Attested = 'attested';
    case ContinuityProven = 'continuity_proven';
    case RegistrarConfirmed = 'registrar_confirmed';
}
