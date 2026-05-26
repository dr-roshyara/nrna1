<?php

namespace App\Application\Election\Security\Policies;

use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\VotingTrustResult;

final class VerificationAttestationPolicy
{
    // Constitutional dependency: establishes attestation legitimacy basis
    // All subsequent policies receive the trust level this policy establishes

    public function evaluate(TrustCapabilityContext $ctx): VotingTrustResult
    {
        if ($ctx->attestation->revoked) {
            return VotingTrustResult::deny(
                reason: 'attestation_revoked',
                context: ['attestation_revoked' => true],
                sequence: ['verification_attestation_policy' => 'denied_revoked'],
            );
        }

        if (!$ctx->attestation->isSatisfied()) {
            return VotingTrustResult::deny(
                reason: 'verification_required',
                context: ['attestation_satisfied' => false, 'required' => $ctx->attestation->required],
                sequence: ['verification_attestation_policy' => 'denied_not_attested'],
            );
        }

        $trustLevel = $ctx->attestation->trustLevel();
        $source = $ctx->attestation->registrarId !== null ? 'registrar' : ($ctx->attestation->attested ? 'session' : 'none');

        return VotingTrustResult::allow(
            trustLevel: $trustLevel,
            context: ['attestation_source' => $source],
            sequence: ['verification_attestation_policy' => 'passed_' . $trustLevel->value],
        );
    }
}
