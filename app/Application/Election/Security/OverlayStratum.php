<?php

namespace App\Application\Election\Security;

/**
 * OverlayStratum — Typed Constitutional Overlay Evaluation Ordering
 *
 * Replaces implicit registration order with explicit, deterministic ordering.
 * Prevents hidden aggregation drift while preserving overlay non-sovereignty.
 *
 * CRITICAL INVARIANT: Overlay ordering is DETERMINISTIC SEQUENCING ONLY.
 * Overlay order is NOT constitutional precedence (that belongs to Policies).
 * Overlays remain influence-only — they NEVER authorize, deny, or short-circuit.
 *
 * Overlay ordering ensures:
 * - Same evidence + same overlay state = same influence signals
 * - Deterministic aggregation for replay legitimacy
 * - Observable topology without exposing internals
 * - Type-safe influence coordination (no string drift)
 */
enum OverlayStratum: string
{
    // Emergency governance layer (exceptional constitutional conditions)
    case EmergencyCondition = 'emergency_condition';

    // Registrar attestation elevation (governance authority elevation signal)
    case RegistrarAttestationElevation = 'registrar_attestation_elevation';

    // Operational security layer (runtime anomaly detection)
    case SuspiciousActivity = 'suspicious_activity';
    case IpVelocity = 'ip_velocity';

    // Contextual layer (device/session consistency)
    case DeviceAnomaly = 'device_anomaly';

    /**
     * Deterministic overlay evaluation order
     *
     * This order ensures:
     * 1. Emergency conditions evaluated first (exceptional governance)
     * 2. Registrar attestation signals second (authority elevation)
     * 3. Operational security third (anomaly detection)
     * 4. Contextual signals last (device consistency)
     *
     * Order is OBSERVABLE (not hidden in container discovery)
     * Order is TYPED (not string-based)
     * Order is IMMUTABLE (guarantees replay determinism)
     *
     * IMPORTANT: This order does NOT imply sovereignty hierarchy.
     * It is purely sequencing for deterministic aggregation.
     */
    public static function evaluationOrder(): array
    {
        return [
            self::EmergencyCondition,
            self::RegistrarAttestationElevation,
            self::SuspiciousActivity,
            self::IpVelocity,
            self::DeviceAnomaly,
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

    /**
     * Validation: Ensure overlay remains influence-only
     *
     * Documents the non-sovereignty constraint that must be maintained.
     * Overlays NEVER authorize, deny, grant, or short-circuit evaluation.
     *
     * This method serves as architectural documentation more than runtime check.
     */
    public static function validateNonSovereignty(string $overlayIdentifier): void
    {
        // Architectural constraint: overlay identifiers map to influence-only overlays
        // If an overlay identifier maps to a sovereign action, architecture is corrupted

        $nonSovereignMappings = [
            'emergency_condition' => 'signals review requirement (non-sovereign)',
            'registrar_attestation_elevation' => 'suggests trust elevation (non-sovereign)',
            'suspicious_activity' => 'signals review requirement (non-sovereign)',
            'ip_velocity' => 'signals evidence inconclusive (non-sovereign)',
            'device_anomaly' => 'signals re-verification requirement (non-sovereign)',
        ];

        if (!isset($nonSovereignMappings[$overlayIdentifier])) {
            throw new \LogicException(
                sprintf(
                    'Overlay "%s" has no documented non-sovereign mapping. ' .
                    'Overlays MUST be influence-only. ' .
                    'They NEVER authorize, deny, grant, or short-circuit evaluation.',
                    $overlayIdentifier
                )
            );
        }
    }
}
