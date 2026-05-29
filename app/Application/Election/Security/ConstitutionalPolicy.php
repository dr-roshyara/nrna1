<?php

namespace App\Application\Election\Security;

use App\Domain\Election\Security\PolicyIdentifier;

/**
 * ConstitutionalPolicy (D.R.3)
 *
 * Policies are PURE constitutional evaluators.
 *
 * CRITICAL INVARIANT:
 * - evaluate() accepts ONLY immutable TrustCapabilityContext
 * - policies NEVER receive other policy results as parameters
 * - policies NEVER call other policies
 * - policies emit PolicyFinding (descriptive facts)
 * - policies NEVER emit authority decisions
 *
 * This interface seals distributed sovereignty leakage through policy coupling.
 */
interface ConstitutionalPolicy
{
    /**
     * Policy's constitutional domain identifier.
     *
     * @return string One of: 'verification', 'network', 'device', 'continuity', 'authorization'
     */
    public function identifier(): string;

    /**
     * Constitutional dependencies (by policy identifier).
     *
     * Which policy identifiers must be evaluated BEFORE this policy?
     * Example: NetworkBindingPolicy depends on ['verification']
     *
     * @return array<string> Empty array if no dependencies
     */
    public function dependencies(): array;

    /**
     * Evaluate constitutional facts and emit findings.
     *
     * CRITICAL: This method accepts ONLY immutable context.
     * It NEVER receives previous policy results.
     * It NEVER inspects resolver state.
     * It NEVER determines participation authority.
     *
     * @param TrustCapabilityContext $context Immutable constitutional facts only
     * @return PolicyFinding Descriptive finding (never authority decision)
     */
    public function evaluate(TrustCapabilityContext $context): PolicyFinding;
}
