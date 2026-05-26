<?php

namespace App\Application\Election\Capabilities;

enum CapabilityDenialReason: string
{
    case Suspended = 'suspended';
    case MissingRole = 'missing_role';
    case InvalidLifecycle = 'invalid_lifecycle';
    case UnmetPrecondition = 'unmet_precondition';
    case TrustDenied = 'trust_denied';
    case ConstitutionalReviewPending = 'constitutional_review_pending';
    case TrustEvaluationInconclusive = 'trust_evaluation_inconclusive';

    public function label(): string
    {
        return match ($this) {
            self::Suspended => 'Election Suspended',
            self::MissingRole => 'Missing Required Role',
            self::InvalidLifecycle => 'Invalid Lifecycle State',
            self::UnmetPrecondition => 'Unmet Requirements',
            self::TrustDenied => 'Trust Verification Failed',
            self::ConstitutionalReviewPending => 'Constitutional Review Required',
            self::TrustEvaluationInconclusive => 'Trust Cannot Be Established',
        };
    }
}
