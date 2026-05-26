<?php

namespace App\Application\Election\Security\Overlays;

use App\Application\Election\Security\ConstitutionalOverlay;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\OverlayInfluence;
use App\Domain\Election\Security\OverlaySignal;
use App\Domain\Election\Security\TrustLevel;

final class RegistrarAttestationElevation implements ConstitutionalOverlay
{
    public function evaluate(TrustCapabilityContext $ctx): OverlaySignal
    {
        // Elevation is a SUGGESTION, not an override
        // Resolver (D.5) DECIDES whether to accept elevation
        // This overlay is influence-only — never grants authority

        // If no attestation or attestation revoked, no elevation possible
        if (is_null($ctx->attestation) || !$ctx->attestation->isSatisfied() || $ctx->attestation->revoked) {
            return OverlaySignal::continue($this->identifier());
        }

        // If registrar present and attestation valid, suggest elevation
        if (!is_null($ctx->attestation->registrarId)) {
            return new OverlaySignal(
                OverlayInfluence::TRUST_ELEVATION_REQUEST,
                $this->identifier(),
                'registrar_attestation_present',
                ['registrar_id' => $ctx->attestation->registrarId],
                null,
                TrustLevel::RegistrarAttested,
            );
        }

        return OverlaySignal::continue($this->identifier());
    }

    public function identifier(): string
    {
        return 'registrar_attestation_elevation';
    }
}
