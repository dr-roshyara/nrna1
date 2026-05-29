<?php

namespace App\Application\Election\Security\Simplified\Policies;

use App\Application\Election\Security\Simplified\ConstitutionalPolicy;
use App\Application\Election\Security\Simplified\PolicyFinding;
use App\Domain\Election\Security\Simplified\ConstitutionalEvidenceSnapshot;

/**
 * VerificationPolicy
 *
 * Evaluates whether verification evidence satisfies constitutional requirements.
 * Returns evidence facts only — NO trust level derivation.
 * Trust level is derived by the Resolver from aggregated PolicyFindings.
 */
final class VerificationPolicy implements ConstitutionalPolicy
{
    public function evaluate(ConstitutionalEvidenceSnapshot $evidence): PolicyFinding
    {
        $constitution = $evidence->constitution;
        $verification = $evidence->verification;

        if (!$constitution->verificationRequired) {
            return new PolicyFinding(
                passed: true,
                constitutionalBasis: 'Verification not required by Article 6',
                policyIdentifier: 'verification_policy',
                supportingFacts: [
                    'required' => false,
                    'attested' => false,
                    'registrar_present' => false,
                ],
            );
        }

        $passed = $verification->attested;

        return new PolicyFinding(
            passed: $passed,
            constitutionalBasis: $passed
                ? 'Verification attested satisfies Article 6'
                : 'Verification required but not attested — violates Article 6',
            policyIdentifier: 'verification_policy',
            supportingFacts: [
                'required' => $verification->required,
                'attested' => $verification->attested,
                'registrar_present' => $verification->registrarId !== null,
            ],
        );
    }
}
