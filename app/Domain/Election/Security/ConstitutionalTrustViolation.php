<?php

namespace App\Domain\Election\Security;

enum ConstitutionalTrustViolation: string
{
    case LEGITIMACY_FAILURE            = 'legitimacy_failure';
    case ATTESTATION_REVOKED           = 'attestation_revoked';
    case CONTINUITY_FAILURE            = 'continuity_failure';
    case NETWORK_LIMIT_EXCEEDED        = 'network_limit_exceeded';
    case REVERIFICATION_REQUIRED       = 'reverification_required';
    case CONSTITUTIONAL_REVIEW_PENDING = 'constitutional_review_pending';
    case TRUST_EVALUATION_INCONCLUSIVE = 'trust_evaluation_inconclusive';
    case OVERLAY_RESTRICTION           = 'overlay_restriction';
    case REGISTRAR_HOLD                = 'registrar_hold';
}
