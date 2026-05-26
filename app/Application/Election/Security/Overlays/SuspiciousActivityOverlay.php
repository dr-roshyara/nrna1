<?php

namespace App\Application\Election\Security\Overlays;

use App\Application\Election\Security\ConstitutionalOverlay;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\OverlayInfluence;
use App\Domain\Election\Security\OverlaySignal;

final class SuspiciousActivityOverlay implements ConstitutionalOverlay
{
    public function evaluate(TrustCapabilityContext $ctx): OverlaySignal
    {
        // Suspicious activity overlay signals constitutional review requirement
        // This is an operational governance concern, not a direct denial

        // Check if suspicious activity overlay is configured on election
        if (is_null($ctx->election) || !$ctx->election->trust_overlay_active) {
            return OverlaySignal::continue($this->identifier());
        }

        // Suspicious activity detected - require constitutional review
        return new OverlaySignal(
            OverlayInfluence::REQUIRE_CONSTITUTIONAL_REVIEW,
            $this->identifier(),
            $ctx->election->trust_overlay_reason ?? 'suspicious_activity_detected',
            [
                'suspicious_activity_active' => true,
                'overlay_reason' => $ctx->election->trust_overlay_reason,
            ],
            'election_officer',
            null,
        );
    }

    public function identifier(): string
    {
        return 'suspicious_activity';
    }
}
