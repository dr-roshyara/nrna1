<?php

namespace App\Application\Election\Security;

use App\Application\Election\Security\Overlays\DeviceAnomalyOverlay;
use App\Application\Election\Security\Overlays\EmergencyConditionOverlay;
use App\Application\Election\Security\Overlays\IpVelocityOverlay;
use App\Application\Election\Security\Overlays\RegistrarAttestationElevation;
use App\Application\Election\Security\Overlays\SuspiciousActivityOverlay;
use App\Domain\Election\Security\OverlayInfluence;
use App\Domain\Election\Security\OverlayStratification;

final class ConstitutionalOverlayRegistry
{
    private static array $definitions = [
        [
            'identifier'         => 'emergency_condition',
            'class'              => EmergencyConditionOverlay::class,
            'stratification'     => OverlayStratification::EMERGENCY_CONSTITUTIONAL,
            'influences'         => [OverlayInfluence::REQUIRE_CONSTITUTIONAL_REVIEW],
            'registrar_required' => false,
            'federation_aware'   => false,
            'order'              => 1,
        ],
        [
            'identifier'         => 'registrar_attestation_elevation',
            'class'              => RegistrarAttestationElevation::class,
            'stratification'     => OverlayStratification::GOVERNANCE_LAYER,
            'influences'         => [OverlayInfluence::TRUST_ELEVATION_REQUEST],
            'registrar_required' => true,
            'federation_aware'   => true,
            'order'              => 2,
        ],
        [
            'identifier'         => 'suspicious_activity',
            'class'              => SuspiciousActivityOverlay::class,
            'stratification'     => OverlayStratification::OPERATIONAL_LAYER,
            'influences'         => [OverlayInfluence::REQUIRE_CONSTITUTIONAL_REVIEW],
            'registrar_required' => false,
            'federation_aware'   => false,
            'order'              => 3,
        ],
        [
            'identifier'         => 'ip_velocity',
            'class'              => IpVelocityOverlay::class,
            'stratification'     => OverlayStratification::OPERATIONAL_LAYER,
            'influences'         => [OverlayInfluence::TRUST_EVALUATION_INCONCLUSIVE],
            'registrar_required' => false,
            'federation_aware'   => false,
            'order'              => 4,
        ],
        [
            'identifier'         => 'device_anomaly',
            'class'              => DeviceAnomalyOverlay::class,
            'stratification'     => OverlayStratification::CONTEXTUAL_LAYER,
            'influences'         => [OverlayInfluence::REQUIRE_RE_VERIFICATION],
            'registrar_required' => false,
            'federation_aware'   => false,
            'order'              => 5,
        ],
    ];

    /** @return OverlayDefinition[] */
    public static function getOrderedDefinitions(): array
    {
        $definitions = [];
        foreach (self::$definitions as $def) {
            $definitions[] = new OverlayDefinition(
                identifier: $def['identifier'],
                overlayClass: $def['class'],
                stratification: $def['stratification'],
                capableInfluences: $def['influences'],
                requiresRegistrarActivation: $def['registrar_required'],
                federationAware: $def['federation_aware'],
                stratificationOrder: $def['order'],
            );
        }
        usort($definitions, fn ($a, $b) => $a->stratificationOrder <=> $b->stratificationOrder);
        return $definitions;
    }

    public static function findByIdentifier(string $identifier): ?OverlayDefinition
    {
        $definitions = self::getOrderedDefinitions();
        foreach ($definitions as $def) {
            if ($def->identifier === $identifier) {
                return $def;
            }
        }
        return null;
    }
}
