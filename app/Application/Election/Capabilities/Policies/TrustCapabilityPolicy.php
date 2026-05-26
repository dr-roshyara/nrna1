<?php

namespace App\Application\Election\Capabilities\Policies;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Capabilities\CapabilityDecision;
use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Application\Election\Capabilities\CapabilityPolicyLayer;
use App\Application\Election\Capabilities\Policy\CapabilityPolicy;

final class TrustCapabilityPolicy implements CapabilityPolicy
{
    public function layer(): CapabilityPolicyLayer
    {
        return CapabilityPolicyLayer::Trust;
    }

    public function evaluate(CapabilityContext $context): ?CapabilityDecision
    {
        // INVARIANT 7: No trust evidence → abstain (non-voting action)
        if (is_null($context->trust)) {
            return null;
        }

        $overlay = $context->trust->overlayInfluence;
        $result = $context->trust->result;

        // INVARIANT 3: Interpret overlay signals HERE — only this policy touches them
        if ($overlay->requiresReview) {
            return CapabilityDecision::deny(
                CapabilityDenialReason::ConstitutionalReviewPending,
                'Constitutional review required before participation',
                \App\Application\Election\Capabilities\CapabilitySeverity::HardBlock,
            );
        }

        if ($overlay->isInconclusive) {
            return CapabilityDecision::deny(
                CapabilityDenialReason::TrustEvaluationInconclusive,
                'Trust cannot be established at this time',
                \App\Application\Election\Capabilities\CapabilitySeverity::HardBlock,
            );
        }

        if (!$result->trusted) {
            return CapabilityDecision::deny(
                CapabilityDenialReason::TrustDenied,
                $result->reason,
                \App\Application\Election\Capabilities\CapabilitySeverity::HardBlock,
            );
        }

        // INVARIANT 2: No I/O, no computation — pure signal interpretation
        return null; // abstain — trust passes, let other policies run
    }
}
