<?php

namespace App\Application\Election\Security\Overlays;

use App\Application\Election\Security\Overlay;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\Simplified\OverlaySignal;

/**
 * ParticipationDensityObservation
 *
 * PHASE C.2 — Constitutional Overlay Implementation
 *
 * Responsibility: ONLY observe participation concentration evidence
 *
 * NOT responsible for:
 * - Blocking participation
 * - Deriving sovereignty consequences
 * - Trust scoring
 * - Fraud detection
 * - Ranked interpretation
 *
 * Constitutional relationship observed:
 * Participation from single network should not exceed constitutional threshold.
 * This is an observable relationship about participation distribution,
 * NOT a security enforcement mechanism.
 *
 * INVARIANT: This overlay returns OBSERVATIONAL signals only.
 * Interpretation of density constraint violations is delegated to resolver.
 *
 * Determinism guarantee:
 * votesFromThisIp vs maxVotesPerIp comparison uses INTEGER-ONLY logic.
 * Identical vote counts and thresholds ALWAYS yield identical observations.
 *
 * No probabilistic elements:
 * - ❌ "trust confidence" scoring
 * - ❌ "abuse likelihood" estimation
 * - ❌ "risk ranking"
 * - ✅ "density_within_constraint", "density_exceeded"
 *
 * This constraint ensures constitutional determinism is preserved.
 */
final class ParticipationDensityObservation implements Overlay
{
    public function evaluate(TrustCapabilityContext $ctx): OverlaySignal
    {
        // STEP 1: Check if density constraint is even active
        if (!$ctx->network->restrictionEnabled || $ctx->network->bindingStrategy === 'none') {
            return OverlaySignal::contextStable(
                'participation_density',
                'Participation density constraint not active in this election',
                [
                    'restrictionEnabled' => false,
                    'bindingStrategy' => $ctx->network->bindingStrategy,
                    'densityObservable' => false,
                ],
            );
        }

        // STEP 2: Compare participation count against constitutional threshold
        // Integer-only comparison: ensures deterministic outcome
        $votesFromThisNetwork = $ctx->network->votesFromThisIp;
        $maxVotesAllowed = $ctx->network->maxVotesPerIp;

        if ($votesFromThisNetwork < $maxVotesAllowed) {
            // Participation distributed within constitutional limits
            return OverlaySignal::contextStable(
                'participation_density',
                'Participation density within constitutional constraint',
                [
                    'votesFromThisNetwork' => $votesFromThisNetwork,
                    'maxVotesAllowed' => $maxVotesAllowed,
                    'bindingStrategy' => $ctx->network->bindingStrategy,
                    'densityState' => 'within_constraint',
                    'remainingCapacity' => $maxVotesAllowed - $votesFromThisNetwork,
                ],
            );
        }

        // Participation concentrated beyond constitutional threshold
        return OverlaySignal::evidenceInconsistent(
            'participation_density',
            'Participation density exceeds constitutional constraint',
            [
                'votesFromThisNetwork' => $votesFromThisNetwork,
                'maxVotesAllowed' => $maxVotesAllowed,
                'bindingStrategy' => $ctx->network->bindingStrategy,
                'densityState' => 'exceeds_constraint',
                'excessParticipation' => $votesFromThisNetwork - $maxVotesAllowed,
            ],
        );
    }

    public function identifier(): string
    {
        return 'participation_density';
    }
}
