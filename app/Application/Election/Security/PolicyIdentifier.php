<?php

namespace App\Application\Election\Security;

/**
 * PolicyIdentifier — Typed Constitutional Policy Topology Metadata
 *
 * Replaces string-based ordering with enumerated policy contracts.
 * Prevents naming drift, ensures type-safe replay legitimacy,
 * and makes policy ordering observable without exposing internals.
 *
 * INVARIANT: Policy ordering is immutable constitutional metadata.
 * Changing order invalidates deterministic replay. Never modify.
 */
enum PolicyIdentifier: string
{
    case VerificationAttestation = 'verification_attestation';
    case NetworkBinding = 'network_binding';
    case DeviceBinding = 'device_binding';

    /**
     * Deterministic evaluation order (immutable, constitutional)
     *
     * Order enforces:
     * 1. VerificationAttestation establishes legitimacy/trust level
     * 2. NetworkBinding uses trust level from (1) for thresholds
     * 3. DeviceBinding validates device consistency
     *
     * This order is not arbitrary — it is constitutional precedence.
     */
    public static function evaluationOrder(): array
    {
        return [
            self::VerificationAttestation,
            self::NetworkBinding,
            self::DeviceBinding,
        ];
    }

    /**
     * Observable policy topology for replay verification
     *
     * Returns the deterministic sequence that guarantee
     * same evidence → same constitutional outcome
     */
    public static function observableTopology(): array
    {
        return array_map(fn(self $policy) => $policy->value, self::evaluationOrder());
    }
}
