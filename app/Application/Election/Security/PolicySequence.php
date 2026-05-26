<?php

namespace App\Application\Election\Security;

use App\Application\Election\Security\Policies\DeviceBindingPolicy;
use App\Application\Election\Security\Policies\NetworkBindingPolicy;
use App\Application\Election\Security\Policies\VerificationAttestationPolicy;
use App\Domain\Election\Security\VotingTrustResult;

final class PolicySequence
{
    // Constitutional evaluation precedence (NOT execution pipeline — this is constitutional dependency hierarchy)
    // Each policy's ordering is REQUIRED by constitutional legitimacy structure, not chosen for performance
    // Dependency hierarchy:
    // 1. VerificationAttestationPolicy → establishes attestation legitimacy (trust level)
    //    · Foundational: no subsequent policy can run before participation legitimacy is established
    // 2. NetworkBindingPolicy → validates continuity within legitimacy (uses trust level from step 1)
    //    · Network continuity operates ONLY within established legitimacy (trust level from step 1)
    //    · Cannot run before attestation establishes what legitimacy level governs this evaluation
    // 3. DeviceBindingPolicy → validates continuity integrity
    //    · Device binding is final integrity check after both legitimacy and network continuity succeed

    public function __construct(
        private VerificationAttestationPolicy $verificationPolicy,
        private NetworkBindingPolicy $networkPolicy,
        private DeviceBindingPolicy $devicePolicy,
    ) {}

    public function evaluate(TrustCapabilityContext $ctx): VotingTrustResult
    {
        // Step 1: Verification Attestation (establishes trust level)
        $verificationResult = $this->verificationPolicy->evaluate($ctx);
        if (!$verificationResult->trusted) {
            return $verificationResult; // Short-circuit on denial
        }

        // Step 2: Network Binding (uses trust level from step 1)
        $networkResult = $this->networkPolicy->evaluate($ctx, $verificationResult->trustLevel);
        if (!$networkResult->trusted) {
            return $networkResult; // Short-circuit on denial
        }

        // Merge policy outcomes before step 3
        $mergedSequence = array_merge(
            $verificationResult->policyOutcomeSequence,
            $networkResult->policyOutcomeSequence,
        );

        // Step 3: Device Binding
        $deviceResult = $this->devicePolicy->evaluate($ctx);
        if (!$deviceResult->trusted) {
            // Reconstruct deny result with merged sequence (readonly property)
            return VotingTrustResult::deny(
                reason: $deviceResult->reason,
                context: array_merge(
                    $verificationResult->auditContext,
                    $networkResult->auditContext,
                    $deviceResult->auditContext,
                ),
                sequence: array_merge($mergedSequence, $deviceResult->policyOutcomeSequence),
            );
        }

        // All pass: composite allow with all outcomes
        $finalSequence = array_merge($mergedSequence, $deviceResult->policyOutcomeSequence);

        return VotingTrustResult::allow(
            trustLevel: $deviceResult->trustLevel,
            context: array_merge(
                $verificationResult->auditContext,
                $networkResult->auditContext,
                $deviceResult->auditContext,
            ),
            sequence: $finalSequence,
        );
    }
}
