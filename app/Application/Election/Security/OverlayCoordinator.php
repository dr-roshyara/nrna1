<?php

namespace App\Application\Election\Security;

use App\Domain\Election\Security\OverlayInfluenceContext;

final class OverlayCoordinator
{
    // D.4 implementation: aggregates overlay influence signals ONLY
    // INVARIANT 1: OverlayCoordinator returns OverlayInfluenceContext — NEVER VotingTrustResult
    // INVARIANT 2: Coordinator aggregates ONLY — never resolves which signal "wins"
    // INVARIANT 5: Never mutates TrustCapabilityContext facts
    // INVARIANT 10: Tests prove behavioral influence only — no grep-confidence architecture tests

    /** @param ConstitutionalOverlay[] $overlays */
    public function __construct(private array $overlays) {}

    public function aggregate(TrustCapabilityContext $ctx): OverlayInfluenceContext
    {
        $signals = [];

        foreach ($this->overlays as $overlay) {
            $signal = $overlay->evaluate($ctx);
            $signals[] = $signal;

            // Early break: Collection optimization when conclusive review required
            // NOT conflict resolution — just stops further evaluation when decisive review signal found
            if ($signal->requiresConstitutionalReview()) {
                break;
            }
        }

        return $this->buildInfluenceContext($signals);
    }

    private function buildInfluenceContext(array $signals): OverlayInfluenceContext
    {
        $hasInfluence = false;
        $requiresReview = false;
        $requiresReverification = false;
        $isInconclusive = false;
        $elevationRequest = null;

        foreach ($signals as $signal) {
            if ($signal->influence->value !== 'continue_unchanged') {
                $hasInfluence = true;
            }

            if ($signal->requiresConstitutionalReview()) {
                $requiresReview = true;
            }

            if ($signal->influence->value === 'require_re_verification') {
                $requiresReverification = true;
            }

            if ($signal->influence->value === 'trust_evaluation_inconclusive') {
                $isInconclusive = true;
            }

            if ($signal->influence->value === 'trust_elevation_request' && !is_null($signal->suggestedElevatedTrustLevel)) {
                $elevationRequest = $signal->suggestedElevatedTrustLevel;
            }
        }

        return new OverlayInfluenceContext(
            signals: $signals,
            hasInfluence: $hasInfluence,
            requiresReview: $requiresReview,
            requiresReverification: $requiresReverification,
            isInconclusive: $isInconclusive,
            elevationRequest: $elevationRequest,
        );
    }
}
