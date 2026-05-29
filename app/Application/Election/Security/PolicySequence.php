<?php

namespace App\Application\Election\Security;

use App\Application\Election\Security\Policies\DeviceBindingPolicy;
use App\Application\Election\Security\Policies\NetworkBindingPolicy;
use App\Application\Election\Security\Policies\VerificationAttestationPolicy;
use App\Domain\Election\Security\PolicyFinding;
use App\Domain\Election\Security\VotingTrustResult;
use App\Application\Election\Security\PolicyIdentifier;

final class PolicySequence
{
    // Orchestration layer that runs pure constitutional policies and assembles their findings into a VotingTrustResult
    // Policies return PolicyFinding (descriptive evidence). Orchestrator produces VotingTrustResult (evidence state).
    // Constitutional evaluation precedence (constitutional dependency hierarchy, not execution pipeline):
    // 1. VerificationAttestationPolicy → establishes attestation legitimacy (foundation)
    // 2. NetworkBindingPolicy → validates continuity within legitimacy (depends on verification)
    // 3. DeviceBindingPolicy → validates device continuity (final integrity check)

    /**
     * @var array<ConstitutionalPolicy> Policies ordered by dependency graph
     */
    private array $orderedPolicies;

    public function __construct(
        private VerificationAttestationPolicy $verificationPolicy,
        private NetworkBindingPolicy $networkPolicy,
        private DeviceBindingPolicy $devicePolicy,
    ) {
        $this->orderedPolicies = [
            $this->verificationPolicy,
            $this->networkPolicy,
            $this->devicePolicy,
        ];

        // D.R.3: Validate dependency graph at construction (fail-fast)
        $this->validateDependencyGraph();
    }

    /**
     * Observable Constitutional Policy Ordering (Priority 1 — Determinism)
     *
     * Returns the immutable, typed policy evaluation order.
     * This is NOT implementation detail — this is constitutional topology.
     *
     * INVARIANT: Same PolicyIdentifier sequence GUARANTEES
     * same constitutional evaluation result for identical evidence.
     *
     * This method replaces reflection-based topology discovery
     * with explicit, auditable, observable ordering.
     */
    public function getEvaluationOrder(): array
    {
        return PolicyIdentifier::evaluationOrder();
    }

    /**
     * Observable Policy Topology (for replay verification)
     *
     * Returns string identifiers for validation and audit purposes.
     * Enables external systems to verify replay determinism.
     */
    public function observableTopology(): array
    {
        return PolicyIdentifier::observableTopology();
    }

    private function validateDependencyGraph(): void
    {
        // Build dependency map
        $policyMap = [];
        foreach ($this->orderedPolicies as $policy) {
            $policyMap[$policy->identifier()] = $policy;
        }

        // Validate all declared dependencies exist
        foreach ($this->orderedPolicies as $policy) {
            foreach ($policy->dependencies() as $depId) {
                if (!isset($policyMap[$depId])) {
                    throw new \LogicException(
                        sprintf(
                            'Policy "%s" declares dependency on "%s" which does not exist',
                            $policy->identifier(),
                            $depId
                        )
                    );
                }
            }
        }

        // Check for circular dependencies (depth-first traversal)
        foreach ($this->orderedPolicies as $policy) {
            $this->checkForCircularDependency($policy->identifier(), $policyMap, []);
        }
    }

    private function checkForCircularDependency(
        string $policyId,
        array $policyMap,
        array $visitStack
    ): void {
        if (in_array($policyId, $visitStack)) {
            throw new \LogicException(
                sprintf(
                    'Circular dependency detected in policy "%s": %s → %s',
                    $policyId,
                    implode(' → ', $visitStack),
                    $policyId
                )
            );
        }

        $visitStack[] = $policyId;
        $policy = $policyMap[$policyId];

        foreach ($policy->dependencies() as $depId) {
            $this->checkForCircularDependency($depId, $policyMap, $visitStack);
        }
    }

    public function evaluate(TrustCapabilityContext $ctx): VotingTrustResult
    {
        // Step 1: Verification Attestation
        $verificationFinding = $this->verificationPolicy->evaluate($ctx);
        if ($verificationFinding->hasConstitutionalConcern()) {
            return VotingTrustResult::insufficientEvidence(
                reason: $verificationFinding->constitutionalBasis,
                trustLevel: $ctx->currentTrustLevel(),
                context: $verificationFinding->supportingFacts,
                sequence: [$verificationFinding->policyIdentifier => $verificationFinding->constitutionalBasis],
            );
        }

        // Step 2: Network Binding
        $networkFinding = $this->networkPolicy->evaluate($ctx);
        if ($networkFinding->hasConstitutionalConcern()) {
            return VotingTrustResult::insufficientEvidence(
                reason: $networkFinding->constitutionalBasis,
                trustLevel: $ctx->currentTrustLevel(),
                context: array_merge(
                    $verificationFinding->supportingFacts,
                    $networkFinding->supportingFacts,
                ),
                sequence: array_merge(
                    [$verificationFinding->policyIdentifier => 'clear'],
                    [$networkFinding->policyIdentifier => $networkFinding->constitutionalBasis],
                ),
            );
        }

        // Step 3: Device Binding
        $deviceFinding = $this->devicePolicy->evaluate($ctx);
        if ($deviceFinding->hasConstitutionalConcern()) {
            return VotingTrustResult::insufficientEvidence(
                reason: $deviceFinding->constitutionalBasis,
                trustLevel: $ctx->currentTrustLevel(),
                context: array_merge(
                    $verificationFinding->supportingFacts,
                    $networkFinding->supportingFacts,
                    $deviceFinding->supportingFacts,
                ),
                sequence: array_merge(
                    [$verificationFinding->policyIdentifier => 'clear'],
                    [$networkFinding->policyIdentifier => 'clear'],
                    [$deviceFinding->policyIdentifier => $deviceFinding->constitutionalBasis],
                ),
            );
        }

        // All findings clear: sufficient evidence
        return VotingTrustResult::sufficientEvidence(
            trustLevel: $ctx->currentTrustLevel(),
            context: array_merge(
                $verificationFinding->supportingFacts,
                $networkFinding->supportingFacts,
                $deviceFinding->supportingFacts,
            ),
            sequence: array_merge(
                [$verificationFinding->policyIdentifier => 'clear'],
                [$networkFinding->policyIdentifier => 'clear'],
                [$deviceFinding->policyIdentifier => 'clear'],
            ),
        );
    }
}
