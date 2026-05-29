<?php

namespace App\Application\Election\Security\Overlays;

use App\Application\Election\Security\Overlay;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\Simplified\OverlaySignal;

final class SuspiciousActivityOverlay implements Overlay
{
    public function evaluate(TrustCapabilityContext $ctx): OverlaySignal
    {
        // Suspicious activity overlay — pure observation semantics
        // Describes: unusual voting pattern detected
        // Does NOT escalate directly — only reports evidence inconsistency
        // Resolver interprets the pattern and decides on procedures

        if (is_null($ctx->election) || !$ctx->election->trust_overlay_active) {
            return OverlaySignal::contextStable(
                'suspicious_activity',
                'Normal activity pattern observed',
                [],
            );
        }

        return OverlaySignal::attestationPresent(
            'suspicious_activity',
            'Unusual voting pattern detected: ' . ($ctx->election->trust_overlay_reason ?? 'suspicious_activity_detected'),
            [
                'suspicious_activity_active' => true,
                'overlay_reason' => $ctx->election->trust_overlay_reason ?? '',
            ],
        );
    }

    public function identifier(): string
    {
        return 'suspicious_activity';
    }
}
