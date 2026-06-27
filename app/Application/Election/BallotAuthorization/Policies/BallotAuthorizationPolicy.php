<?php

namespace App\Application\Election\BallotAuthorization\Policies;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Capabilities\CapabilityDecision;
use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Application\Election\Capabilities\CapabilityPolicyLayer;
use App\Application\Election\Capabilities\Policy\CapabilityPolicy;
use App\Domain\Election\Security\BallotAuthorization\BallotSession;

/**
 * BallotAuthorizationPolicy
 *
 * Evaluates ballot code protocol as an independent sovereignty dimension.
 * BallotAuthorization governs code/token workflow independently of trust.
 *
 * INVARIANT: This policy evaluates CODE PROTOCOL, not trust evidence.
 * Trust may be sufficient but authorization may fail — completely orthogonal.
 *
 * Layer: Preconditions (evaluated before trust/lifecycle)
 */
final readonly class BallotAuthorizationPolicy implements CapabilityPolicy
{
    public function layer(): CapabilityPolicyLayer
    {
        return CapabilityPolicyLayer::Preconditions;
    }

    public function evaluate(CapabilityContext $context): ?CapabilityDecision
    {
        $session = $context->actionMetadata['ballot_session'] ?? null;

        if (!$session instanceof BallotSession) {
            return null; // No ballot session data → abstain
        }

        if ($session->protocol === 'dual_code') {
            return $this->evaluateDualCode($session);
        }

        return $this->evaluateSingleCode($session);
    }

    private function evaluateSingleCode(BallotSession $session): CapabilityDecision
    {
        $codes = $session->codes;

        if (count($codes) < 1) {
            return CapabilityDecision::prohibited(
                CapabilityDenialReason::UnmetPrecondition,
                'Single code protocol requires one ballot code',
            );
        }

        $code = $codes[0];

        if ($code->isUsed) {
            return CapabilityDecision::prohibited(
                CapabilityDenialReason::UnmetPrecondition,
                'Ballot code already consumed',
            );
        }

        return CapabilityDecision::authorized();
    }

    private function evaluateDualCode(BallotSession $session): CapabilityDecision
    {
        $codes = $session->codes;

        if (count($codes) < 2) {
            return CapabilityDecision::prohibited(
                CapabilityDenialReason::UnmetPrecondition,
                'Dual code protocol requires both view and commit codes',
            );
        }

        if (!$session->viewCompleted) {
            return CapabilityDecision::prohibited(
                CapabilityDenialReason::UnmetPrecondition,
                'View step must be completed before commit in dual code protocol',
            );
        }

        // Both codes available and view completed → authorized
        return CapabilityDecision::authorized();
    }
}
