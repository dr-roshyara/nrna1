<?php

namespace App\Application\Election\Capabilities\Policy;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Capabilities\CapabilityDecision;
use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Application\Election\Capabilities\CapabilityPolicyLayer;

final class LifecycleCapabilityBaselinePolicy implements CapabilityPolicy
{
    public function evaluate(CapabilityContext $context): ?CapabilityDecision
    {
        $allowedStates = $context->actionMetadata['allowed_states'] ?? [];

        if (empty($allowedStates)) {
            return CapabilityDecision::deny(CapabilityDenialReason::InvalidLifecycle, 'Action not defined in constitution');
        }

        foreach ($allowedStates as $allowedState) {
            if ($context->state->value === $allowedState) {
                return CapabilityDecision::grant();
            }
        }

        return CapabilityDecision::deny(
            CapabilityDenialReason::InvalidLifecycle,
            "Action not allowed in state {$context->state->value}"
        );
    }

    public function layer(): CapabilityPolicyLayer
    {
        return CapabilityPolicyLayer::Lifecycle;
    }
}
