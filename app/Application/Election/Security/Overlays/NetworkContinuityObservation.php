<?php

namespace App\Application\Election\Security\Overlays;

use App\Application\Election\Security\Overlay;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\Simplified\OverlaySignal;

/**
 * NetworkContinuityObservation
 *
 * PHASE C.1 — Constitutional Overlay Implementation
 *
 * Responsibility: ONLY observe participation network continuity relationship
 *
 * NOT responsible for:
 * - Blocking participation
 * - Deriving sovereignty consequences
 * - Escalating decisions
 * - Interpreting authority
 *
 * Constitutional relationship observed:
 * Participation initiated from network X should continue from same network.
 * This is an observable relationship about constitutional consistency,
 * NOT a security enforcement rule.
 *
 * INVARIANT: This overlay returns OBSERVATIONAL signals only.
 * Interpretation of continuity insufficiency is delegated to resolver.
 *
 * Vocabulary constraint:
 * - ✅ "continuity confirmed", "continuity insufficient"
 * - ❌ "IP valid", "IP invalid", "network violation"
 *
 * This distinction ensures the overlay observes constitutional relationships
 * rather than enforcing procedural security topology.
 */
final class NetworkContinuityObservation implements Overlay
{
    public function evaluate(TrustCapabilityContext $ctx): OverlaySignal
    {
        // STEP 1: Check binding strategy determines if continuity is even observable
        if ($ctx->network->bindingStrategy === 'none') {
            return OverlaySignal::contextStable(
                'network_continuity',
                'Network continuity not constrained by election constitution',
                [
                    'bindingStrategy' => 'none',
                    'continuityObservable' => false,
                ],
            );
        }

        // STEP 2: Check if registration network hash is established
        // If no registered hash, no continuity relationship can be observed
        if (is_null($ctx->network->registeredIpHash)) {
            return OverlaySignal::contextStable(
                'network_continuity',
                'No registered network recorded, continuity relationship cannot be established',
                [
                    'registeredNetworkHash' => null,
                    'continuityObservable' => false,
                ],
            );
        }

        // STEP 3: Observe continuity relationship
        // Compare registration network with current voting network
        if ($ctx->network->currentIpHash === $ctx->network->registeredIpHash) {
            // Continuity confirmed: participation continues from same network
            return OverlaySignal::contextStable(
                'network_continuity',
                'Network continuity confirmed: participation continues from registered network',
                [
                    'registeredNetworkHash' => $ctx->network->registeredIpHash,
                    'currentNetworkHash' => $ctx->network->currentIpHash,
                    'bindingStrategy' => $ctx->network->bindingStrategy,
                    'continuityState' => 'confirmed',
                ],
            );
        }

        // Continuity insufficient: participation switched networks
        return OverlaySignal::evidenceInconsistent(
            'network_continuity',
            'Network continuity insufficient: participation network differs from registration',
            [
                'registeredNetworkHash' => $ctx->network->registeredIpHash,
                'currentNetworkHash' => $ctx->network->currentIpHash,
                'bindingStrategy' => $ctx->network->bindingStrategy,
                'continuityState' => 'inconsistent',
            ],
        );
    }

    public function identifier(): string
    {
        return 'network_continuity';
    }
}
