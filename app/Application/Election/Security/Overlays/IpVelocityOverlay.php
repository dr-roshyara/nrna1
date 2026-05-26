<?php

namespace App\Application\Election\Security\Overlays;

use App\Application\Election\Security\ConstitutionalOverlay;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\OverlayInfluence;
use App\Domain\Election\Security\OverlaySignal;
use App\Models\ElectionSecurityEvent;
use Illuminate\Support\Facades\DB;

final class IpVelocityOverlay implements ConstitutionalOverlay
{
    private int $velocityThreshold;

    private int $velocityWindowSeconds;

    public function __construct()
    {
        $this->velocityThreshold = (int) env('IP_VELOCITY_THRESHOLD', 10);
        $this->velocityWindowSeconds = 300; // 5 minutes
    }

    public function evaluate(TrustCapabilityContext $ctx): OverlaySignal
    {
        // IP velocity check: too many votes from same IP in short time
        // Signal inconclusive trust evaluation if velocity threshold exceeded

        if (is_null($ctx->election)) {
            return OverlaySignal::continue($this->identifier());
        }

        // Query recent votes from this IP in the velocity window
        // For now, just count all events in the velocity window
        // TODO: Filter by actual IP hash when ElectionSecurityEvent has indexed JSON query support
        $recentVotes = ElectionSecurityEvent::query()
            ->where('election_id', $ctx->election->id)
            ->where('recorded_at', '>=', now()->subSeconds($this->velocityWindowSeconds))
            ->count();

        if ($recentVotes >= $this->velocityThreshold) {
            // Velocity threshold exceeded - cannot reliably establish trust
            return new OverlaySignal(
                OverlayInfluence::TRUST_EVALUATION_INCONCLUSIVE,
                $this->identifier(),
                'ip_velocity_threshold_exceeded',
                [
                    'recent_votes_from_ip' => $recentVotes,
                    'threshold' => $this->velocityThreshold,
                    'window_seconds' => $this->velocityWindowSeconds,
                ],
                null,
                null,
            );
        }

        return OverlaySignal::continue($this->identifier());
    }

    public function identifier(): string
    {
        return 'ip_velocity';
    }
}
