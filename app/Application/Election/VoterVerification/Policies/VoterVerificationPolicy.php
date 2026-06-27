<?php

namespace App\Application\Election\VoterVerification\Policies;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Capabilities\CapabilityDecision;
use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Application\Election\Capabilities\CapabilityPolicyLayer;
use App\Application\Election\Capabilities\Policy\CapabilityPolicy;
use App\Domain\Election\Security\VoterVerification\VerificationSession;

/**
 * VoterVerificationPolicy
 *
 * Evaluates whether officer evidence capture is complete.
 *
 * INVARIANT: The officer is evidence attestation authority, NOT voting authority.
 * This policy only checks if a constitutional precondition (verification) is met.
 * It NEVER authorizes participation — only abstains or prohibits.
 *
 * Layer: Preconditions (evaluated before trust/lifecycle)
 */
final readonly class VoterVerificationPolicy implements CapabilityPolicy
{
    public function layer(): CapabilityPolicyLayer
    {
        return CapabilityPolicyLayer::Preconditions;
    }

    public function evaluate(CapabilityContext $context): ?CapabilityDecision
    {
        $session = $context->actionMetadata['verification_session'] ?? null;

        if (!$session instanceof VerificationSession) {
            return null; // No verification data → abstain
        }

        if (!$session->isRequired) {
            return null; // Verification not required → abstain
        }

        if ($session->isComplete) {
            return null; // Precondition met → abstain (let other policies decide)
        }

        // Verification required but incomplete → precondition not met
        return CapabilityDecision::prohibited(
            CapabilityDenialReason::UnmetPrecondition,
            'Voter verification required but not yet completed by officer',
        );
    }
}
