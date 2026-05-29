<?php

namespace App\Application\Election\Security\Policies;

use App\Application\Election\Security\PolicyFinding;
use App\Application\Election\Security\ConstitutionalPolicy;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\ConstitutionalConcernLevel;
use App\Domain\Election\Security\EvidenceWeightCategory;
use App\Domain\Election\Security\FingerprintMatchType;

final class DeviceBindingPolicy implements ConstitutionalPolicy
{
    public function identifier(): string
    {
        return 'device';
    }

    public function dependencies(): array
    {
        return ['verification'];  // Device binding requires verification first
    }

    public function evaluate(TrustCapabilityContext $ctx): PolicyFinding
    {
        if ($ctx->device->matchType === FingerprintMatchType::NotRequired) {
            return PolicyFinding::noFinding($this->identifier());
        }

        if ($ctx->device->satisfiesDeviceAttestation()) {
            return PolicyFinding::noFinding($this->identifier());
        }

        return new PolicyFinding(
            concernLevel: ConstitutionalConcernLevel::MEDIUM,
            evidenceWeight: EvidenceWeightCategory::MODERATE,
            constitutionalBasis: 'device_attestation_failed',
            supportingFacts: ['fingerprint_match_type' => $ctx->device->matchType->value],
            policyIdentifier: $this->identifier(),
        );
    }
}
