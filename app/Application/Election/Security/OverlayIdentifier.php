<?php

namespace App\Application\Election\Security;

/**
 * OverlayIdentifier — Typed Constitutional Overlay Topology Metadata
 *
 * Replaces string-based overlay ordering with enumerated overlay contracts.
 * Prevents naming drift, ensures type-safe replay legitimacy,
 * and makes overlay ordering observable without exposing internals.
 *
 * INVARIANT: Overlay ordering is immutable constitutional metadata.
 * Changing order invalidates deterministic replay. Never modify.
 *
 * Priority 2: Observable Overlay Topology (Deterministic Sequencing)
 * Replaces implicit container discovery order with explicit typing.
 */
enum OverlayIdentifier: string
{
    case EmergencyCondition = 'emergency_condition';
    case RegistrarAttestationElevation = 'registrar_attestation_elevation';
    case SuspiciousActivity = 'suspicious_activity';
    case IpVelocity = 'ip_velocity';
    case DeviceAnomaly = 'device_anomaly';

    /**
     * Deterministic evaluation order (immutable, constitutional)
     *
     * Order enforces:
     * 1. EmergencyCondition first (exceptional governance)
     * 2. RegistrarAttestationElevation second (registrar authority signals)
     * 3. SuspiciousActivity third (operational security layer)
     * 4. IpVelocity third (operational anomalies)
     * 5. DeviceAnomaly last (contextual signals)
     *
     * This order is not arbitrary — it is constitutional evaluation sequencing.
     * Stratification assigns layers; ordering establishes deterministic aggregation.
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
     * Returns the deterministic sequence that guarantees
     * same evidence → same constitutional aggregation
     */
    public static function observableTopology(): array
    {
        return array_map(fn(self $overlay) => $overlay->value, self::evaluationOrder());
    }
}
