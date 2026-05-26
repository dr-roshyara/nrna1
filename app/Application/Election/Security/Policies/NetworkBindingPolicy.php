<?php

namespace App\Application\Election\Security\Policies;

use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\VotingTrustResult;

final class NetworkBindingPolicy
{
    // Constitutional dependency: validates network continuity within established legitimacy
    // Receives trust level from VerificationAttestationPolicy (via PolicySequence)
    // Applies trust-elevated thresholds: RegistrarAttested=2x, ContinuityVerified=1.5x, Attested=1x, Unverified=0.5x

    public function evaluate(TrustCapabilityContext $ctx, TrustLevel $currentTrustLevel): VotingTrustResult
    {
        if (!$ctx->network->restrictionEnabled) {
            return VotingTrustResult::allow(
                trustLevel: $currentTrustLevel,
                context: ['network_restriction' => 'disabled'],
                sequence: ['network_binding_policy' => ['outcome' => 'passed_unrestricted', 'remaining_votes' => PHP_INT_MAX]],
            );
        }

        if ($ctx->network->isWhitelisted()) {
            return VotingTrustResult::allow(
                trustLevel: $currentTrustLevel,
                context: ['network_restriction' => 'whitelisted'],
                sequence: ['network_binding_policy' => ['outcome' => 'passed_whitelisted', 'remaining_votes' => PHP_INT_MAX]],
            );
        }

        if ($ctx->network->exceedsLimit($currentTrustLevel)) {
            return VotingTrustResult::deny(
                reason: 'network_limit_exceeded',
                context: [
                    'trust_level' => $currentTrustLevel->value,
                    'votes_from_ip' => $ctx->network->votesFromThisIp,
                ],
                sequence: ['network_binding_policy' => ['outcome' => 'denied_limit_exceeded', 'remaining_votes' => 0]],
            );
        }

        $remaining = $ctx->network->remainingVotes($currentTrustLevel);

        return VotingTrustResult::allow(
            trustLevel: $currentTrustLevel,
            context: ['remaining_votes' => $remaining],
            sequence: ['network_binding_policy' => ['outcome' => 'passed', 'remaining_votes' => $remaining]],
        );
    }
}
