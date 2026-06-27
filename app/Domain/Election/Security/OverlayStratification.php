<?php

namespace App\Domain\Election\Security;

enum OverlayStratification: string
{
    case EMERGENCY_CONSTITUTIONAL = 'emergency_constitutional';
    case GOVERNANCE_LAYER         = 'governance_layer';
    case OPERATIONAL_LAYER        = 'operational_layer';
    case CONTEXTUAL_LAYER         = 'contextual_layer';

    // Purpose: ordering metadata for aggregation traversal ONLY.
    // Does NOT determine which signal "wins" — that is Resolver responsibility.

    /**
     * Deterministic overlay evaluation order (immutable, constitutional)
     *
     * Order enforces deterministic aggregation sequencing:
     * 1. Emergency conditions evaluated first (exceptional governance)
     * 2. Governance layer second (registrar attestation elevation)
     * 3. Operational layer third (suspicious activity, velocity anomalies)
     * 4. Contextual layer last (device anomalies, session consistency)
     *
     * This order is NOT authority precedence — it is deterministic sequencing.
     * All signals are aggregated descriptively regardless of order.
     */
    public static function evaluationOrder(): array
    {
        return [
            self::EMERGENCY_CONSTITUTIONAL,
            self::GOVERNANCE_LAYER,
            self::OPERATIONAL_LAYER,
            self::CONTEXTUAL_LAYER,
        ];
    }

    /**
     * Observable overlay topology for replay verification
     *
     * Returns string identifiers for external systems to verify
     * that overlay aggregation order matches constitutional expectations.
     *
     * This enables replay legitimacy auditing without exposing internal structure.
     */
    public static function observableTopology(): array
    {
        return array_map(fn(self $stratum) => $stratum->value, self::evaluationOrder());
    }
}
