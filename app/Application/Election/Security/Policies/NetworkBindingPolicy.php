<?php

namespace App\Application\Election\Security\Policies;

use App\Application\Election\Security\PolicyFinding;
use App\Application\Election\Security\ConstitutionalPolicy;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\ConstitutionalConcernLevel;
use App\Domain\Election\Security\EvidenceWeightCategory;

final class NetworkBindingPolicy implements ConstitutionalPolicy
{
    public function identifier(): string
    {
        return 'network';
    }

    public function dependencies(): array
    {
        return ['verification'];  // Network binding requires verification first
    }

    public function evaluate(TrustCapabilityContext $ctx): PolicyFinding
    {
        $currentTrustLevel = $ctx->currentTrustLevel();

        if (!$ctx->network->restrictionEnabled) {
            return PolicyFinding::noFinding($this->identifier());
        }

        if ($ctx->network->isWhitelisted()) {
            return PolicyFinding::noFinding($this->identifier());
        }

        if ($ctx->network->exceedsLimit($currentTrustLevel)) {
            return new PolicyFinding(
                concernLevel: ConstitutionalConcernLevel::HIGH,
                evidenceWeight: EvidenceWeightCategory::STRONG,
                constitutionalBasis: 'network_limit_exceeded',
                supportingFacts: [
                    'trust_level' => $currentTrustLevel->value,
                    'votes_from_ip' => $ctx->network->votesFromThisIp,
                    'limit_for_trust_level' => $ctx->network->maxVotesPerIp,
                ],
                policyIdentifier: $this->identifier(),
            );
        }

        return PolicyFinding::noFinding($this->identifier());
    }
}
