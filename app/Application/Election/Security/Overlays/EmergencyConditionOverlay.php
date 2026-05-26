<?php

namespace App\Application\Election\Security\Overlays;

use App\Application\Election\Security\ConstitutionalOverlay;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\OverlayInfluence;
use App\Domain\Election\Security\OverlaySignal;

final class EmergencyConditionOverlay implements ConstitutionalOverlay
{
    public function evaluate(TrustCapabilityContext $ctx): OverlaySignal
    {
        // Emergency governance is NOT casual overlay behavior
        // This overlay signals REQUIRE_CONSTITUTIONAL_REVIEW — NOT suspension or denial
        // Authority to activate emergency is deferred constitutional modeling (Phase D+)

        if (is_null($ctx->election) || !$ctx->election->trust_overlay_active) {
            return OverlaySignal::continue($this->identifier());
        }

        // Emergency condition detected - require constitutional review
        return new OverlaySignal(
            OverlayInfluence::REQUIRE_CONSTITUTIONAL_REVIEW,
            $this->identifier(),
            $ctx->election->trust_overlay_reason ?? 'emergency_condition_activated',
            ['emergency_active' => true, 'overlay_reason' => $ctx->election->trust_overlay_reason],
            'election_officer', // Who must conduct review
            null,
        );
    }

    public function identifier(): string
    {
        return 'emergency_condition';
    }
}
