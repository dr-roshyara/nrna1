<?php

namespace App\Application\Election\Security\Simplified\Policies;

use App\Application\Election\Security\Simplified\ConstitutionalPolicy;
use App\Application\Election\Security\Simplified\PolicyFinding;
use App\Domain\Election\Security\Simplified\ConstitutionalEvidenceSnapshot;

/**
 * NetworkBindingPolicy
 *
 * Observes network evidence against constitutional network binding strategy.
 * Evidence observation only — NO threshold math.
 * Threshold interpretation (trust-level-adjusted limits) belongs to NetworkThresholdInterpreter.
 *
 * Strategies:
 * - none: network participation unrestricted
 * - ip_count: votes from this IP must be within constitutional base limit
 * - ip_strict: current IP must match session IP continuity
 */
final class NetworkBindingPolicy implements ConstitutionalPolicy
{
    public function evaluate(ConstitutionalEvidenceSnapshot $evidence): PolicyFinding
    {
        $constitution = $evidence->constitution;
        $network = $evidence->network;

        return match ($constitution->networkBindingStrategy) {
            'none' => new PolicyFinding(
                passed: true,
                constitutionalBasis: 'Network binding not required by Article 1',
                policyIdentifier: 'network_binding_policy',
                supportingFacts: ['strategy' => 'none'],
            ),

            'ip_count' => new PolicyFinding(
                passed: $network->votesFromThisIp <= $constitution->maxVotesPerIp,
                constitutionalBasis: $network->votesFromThisIp <= $constitution->maxVotesPerIp
                    ? 'IP vote count within constitutional limit per Article 1'
                    : 'IP vote count exceeds constitutional limit — violates Article 1',
                policyIdentifier: 'network_binding_policy',
                supportingFacts: [
                    'strategy' => 'ip_count',
                    'votes_from_this_ip' => $network->votesFromThisIp,
                    'base_max_allowed' => $constitution->maxVotesPerIp,
                ],
            ),

            'ip_strict' => new PolicyFinding(
                passed: $network->currentIpHash === $evidence->continuity->ipHashAtStart,
                constitutionalBasis: $network->currentIpHash === $evidence->continuity->ipHashAtStart
                    ? 'IP continuity preserved per Article 4'
                    : 'IP mismatch — violates Article 4 network continuity invariant',
                policyIdentifier: 'network_binding_policy',
                supportingFacts: [
                    'strategy' => 'ip_strict',
                    'ip_match' => $network->currentIpHash === $evidence->continuity->ipHashAtStart,
                ],
            ),

            default => new PolicyFinding(
                passed: false,
                constitutionalBasis: 'Unknown network binding strategy: ' . $constitution->networkBindingStrategy,
                policyIdentifier: 'network_binding_policy',
                supportingFacts: ['strategy' => $constitution->networkBindingStrategy, 'error' => 'unknown_strategy'],
            ),
        };
    }
}
