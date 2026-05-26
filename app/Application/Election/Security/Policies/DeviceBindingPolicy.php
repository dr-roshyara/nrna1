<?php

namespace App\Application\Election\Security\Policies;

use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\VotingTrustResult;

final class DeviceBindingPolicy
{
    // Constitutional dependency: validates device continuity integrity
    // Isolated evaluator — receives only TrustCapabilityContext

    public function evaluate(TrustCapabilityContext $ctx): VotingTrustResult
    {
        if ($ctx->device->matchType === FingerprintMatchType::NotRequired) {
            return VotingTrustResult::allow(
                trustLevel: $ctx->currentTrustLevel(),
                context: ['device_binding' => 'not_required'],
                sequence: ['device_binding_policy' => 'passed_not_required'],
            );
        }

        if ($ctx->device->satisfiesDeviceAttestation()) {
            return VotingTrustResult::allow(
                trustLevel: $ctx->currentTrustLevel(),
                context: ['device_binding' => 'exact_match'],
                sequence: ['device_binding_policy' => 'passed_exact_match'],
            );
        }

        return VotingTrustResult::deny(
            reason: 'device_attestation_failed',
            context: ['fingerprint_match_type' => $ctx->device->matchType->value],
            sequence: ['device_binding_policy' => 'denied_no_match'],
        );
    }
}
