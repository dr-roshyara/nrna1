<?php

namespace App\Application\Election\Security\Simplified\Policies;

use App\Application\Election\Security\Simplified\ConstitutionalPolicy;
use App\Application\Election\Security\Simplified\PolicyFinding;
use App\Domain\Election\Security\Simplified\ConstitutionalEvidenceSnapshot;

/**
 * DeviceBindingPolicy
 *
 * Evaluates device fingerprint evidence against constitutional device binding strategy.
 * Evidence observation only — NO authority derivation.
 *
 * Strategies:
 * - none: device binding not required
 * - fingerprint_required: device fingerprint must match attested evidence
 */
final class DeviceBindingPolicy implements ConstitutionalPolicy
{
    public function evaluate(ConstitutionalEvidenceSnapshot $evidence): PolicyFinding
    {
        $constitution = $evidence->constitution;
        $device = $evidence->device;

        if ($constitution->deviceBindingStrategy === 'none') {
            return new PolicyFinding(
                passed: true,
                constitutionalBasis: 'Device binding not required by Article 5',
                policyIdentifier: 'device_binding_policy',
                supportingFacts: ['strategy' => 'none'],
            );
        }

        $passed = $device->matchType === 'exact_match';

        return new PolicyFinding(
            passed: $passed,
            constitutionalBasis: $passed
                ? 'Fingerprint continuity preserved per Article 5'
                : 'Fingerprint mismatch — violates Article 5 device continuity invariant',
            policyIdentifier: 'device_binding_policy',
            supportingFacts: [
                'strategy' => $constitution->deviceBindingStrategy,
                'match_type' => $device->matchType,
                'fingerprint_present' => $device->fingerprintHash !== null,
            ],
        );
    }
}
