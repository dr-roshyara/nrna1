<?php

namespace App\Application\Election\Security\Overlays;

use App\Application\Election\Security\Overlay;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\Simplified\OverlaySignal;

final class EmergencyConditionOverlay implements Overlay
{
    public function evaluate(TrustCapabilityContext $ctx): OverlaySignal
    {
        // Emergency condition overlay — pure observation semantics
        // Returns frozen vocabulary signal only
        // Resolver interprets concern level and decides on procedures

        if (is_null($ctx->election) || !$ctx->election->trust_overlay_active) {
            return OverlaySignal::contextStable(
                'emergency_condition',
                'No emergency active',
                [],
            );
        }

        // Emergency detected — signal evidence inconsistency
        return OverlaySignal::evidenceInconsistent(
            'emergency_condition',
            'Election suspended: ' . ($ctx->election->trust_overlay_reason ?? 'emergency_condition_activated'),
            ['emergency_active' => true, 'overlay_reason' => $ctx->election->trust_overlay_reason ?? ''],
        );
    }

    public function identifier(): string
    {
        return 'emergency_condition';
    }
}
