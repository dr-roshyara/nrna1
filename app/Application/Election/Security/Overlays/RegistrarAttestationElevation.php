<?php

namespace App\Application\Election\Security\Overlays;

use App\Application\Election\Security\Overlay;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\Simplified\OverlaySignal;

final class RegistrarAttestationElevation implements Overlay
{
    public function evaluate(TrustCapabilityContext $ctx): OverlaySignal
    {
        // Registrar attestation overlay — pure observation semantics
        // Describes: registrar has verified the voter
        // Does NOT suggest elevation — only reports evidence
        // Resolver interprets the attestation evidence and decides capability

        if (is_null($ctx->attestation) || !$ctx->attestation->isSatisfied() || $ctx->attestation->revoked) {
            return OverlaySignal::contextStable(
                'registrar_attestation',
                'No registrar attestation present',
                [],
            );
        }

        if (!is_null($ctx->attestation->registrarId)) {
            return OverlaySignal::evidenceInconsistent(
                'registrar_attestation',
                'Registrar attestation present — evidence inconsistent with baseline unverified state',
                ['registrar_id' => $ctx->attestation->registrarId],
            );
        }

        return OverlaySignal::contextStable(
            'registrar_attestation',
            'No registrar attestation present',
            [],
        );
    }

    public function identifier(): string
    {
        return 'registrar_attestation';
    }
}
