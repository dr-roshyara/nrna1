<?php

namespace App\Application\Election\Security\Policies;

use App\Application\Election\Security\PolicyFinding;
use App\Application\Election\Security\ConstitutionalPolicy;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\ConstitutionalConcernLevel;
use App\Domain\Election\Security\EvidenceWeightCategory;

final class VerificationAttestationPolicy implements ConstitutionalPolicy
{
    public function identifier(): string
    {
        return 'verification';
    }

    public function dependencies(): array
    {
        return [];  // Verification is foundational — no policy dependencies
    }

    public function evaluate(TrustCapabilityContext $ctx): PolicyFinding
    {
        if ($ctx->attestation->revoked) {
            return new PolicyFinding(
                concernLevel: ConstitutionalConcernLevel::CRITICAL,
                evidenceWeight: EvidenceWeightCategory::DEFINITIVE,
                constitutionalBasis: 'attestation_revoked',
                supportingFacts: ['attestation_revoked' => true],
                policyIdentifier: $this->identifier(),
            );
        }

        if (!$ctx->attestation->isSatisfied()) {
            return new PolicyFinding(
                concernLevel: ConstitutionalConcernLevel::HIGH,
                evidenceWeight: EvidenceWeightCategory::DEFINITIVE,
                constitutionalBasis: 'verification_required',
                supportingFacts: ['attestation_satisfied' => false, 'required' => $ctx->attestation->required],
                policyIdentifier: $this->identifier(),
            );
        }

        $source = $ctx->attestation->registrarId !== null ? 'registrar' : ($ctx->attestation->attested ? 'session' : 'none');

        return PolicyFinding::noFinding($this->identifier());
    }
}
