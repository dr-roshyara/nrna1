<?php

namespace App\Application\Election\Capabilities\Policy;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Capabilities\CapabilityDecision;
use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Application\Election\Capabilities\CapabilityPolicyLayer;

final class OverlayCapabilityPolicy implements CapabilityPolicy
{
    public function evaluate(CapabilityContext $context): ?CapabilityDecision
    {
        if ($context->election === null) {
            return null;
        }

        if ($context->election->suspended_at === null) {
            return null;
        }

        if ($context->action === 'resume') {
            return null;
        }

        return CapabilityDecision::shortCircuit(CapabilityDenialReason::Suspended);
    }

    public function layer(): CapabilityPolicyLayer
    {
        return CapabilityPolicyLayer::Overlay;
    }
}
