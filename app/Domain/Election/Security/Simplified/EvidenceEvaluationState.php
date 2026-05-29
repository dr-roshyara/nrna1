<?php

namespace App\Domain\Election\Security\Simplified;

/**
 * EvidenceEvaluationState
 *
 * Describes the state of evidence evaluation — an evidence quality descriptor,
 * NOT an authority decision. This replaces TrustEvaluationState in the
 * simplified/constitutional runtime.
 *
 * Values:
 * - SUFFICIENT_EVIDENCE: All required evidence present and consistent
 * - INSUFFICIENT_EVIDENCE: Required evidence missing or failed checks
 * - REVIEW_REQUIRED: Evidence requires constitutional review
 * - INCONCLUSIVE: Evidence cannot be definitively evaluated
 *
 * Only TrustCapabilityPolicy converts these states into CapabilityDecision.
 */
enum EvidenceEvaluationState: string
{
    case SUFFICIENT_EVIDENCE = 'sufficient_evidence';
    case INSUFFICIENT_EVIDENCE = 'insufficient_evidence';
    case REVIEW_REQUIRED = 'review_required';
    case INCONCLUSIVE = 'inconclusive';
}
