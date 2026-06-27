<?php

namespace App\Application\Election\Security\Overlays;

use App\Application\Election\Security\Overlay;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\Simplified\OverlaySignal;
use App\Domain\Shared\Clock\ClockInterface;
use App\Models\ElectionSecurityEvent;

final class IpVelocityOverlay implements Overlay
{
    private int $velocityThreshold;

    private int $velocityWindowSeconds;

    public function __construct(private ClockInterface $clock)
    {
        $this->velocityThreshold = (int) env('IP_VELOCITY_THRESHOLD', 10);
        $this->velocityWindowSeconds = 300; // 5 minutes
    }

    public function evaluate(TrustCapabilityContext $ctx): OverlaySignal
    {
        // IP velocity overlay — pure observation semantics
        // Describes: IP voting frequency within or exceeds threshold
        // Does NOT require reverification — only reports the observation
        // Resolver interprets high velocity and decides on procedures

        if (is_null($ctx->election)) {
            return OverlaySignal::contextStable(
                'ip_velocity',
                'IP velocity within normal limits',
                [],
            );
        }

        $recentVotes = ElectionSecurityEvent::query()
            ->where('election_id', $ctx->election->id)
            ->where('recorded_at', '>=', $this->clock->now()->sub(new \DateInterval('PT' . $this->velocityWindowSeconds . 'S')))
            ->count();

        if ($recentVotes >= $this->velocityThreshold) {
            // STEP A.3 FIX F3: Semantic correction
            // IP velocity breach is a NETWORK ANOMALY, not an attestation event
            //
            // Attestation = artifact available for reverification (device fingerprint, registrar record)
            // Velocity = network anomaly, continuity uncertainty, elevated scrutiny required
            //
            // Therefore: Use EVIDENCE_INCONSISTENT instead of ADDITIONAL_ATTESTATION_PRESENT
            //
            // SEMANTIC PRESSURE POINT (RF3): "EVIDENCE_INCONSISTENT" may become overloaded over time.
            // Future vocabulary may need "NETWORK_CONTINUITY_UNCERTAIN" category when load increases.
            // This is documented for D.R.4+ phases.
            return OverlaySignal::evidenceInconsistent(
                'ip_velocity',
                'IP voting frequency threshold exceeded — network anomaly detected',
                [
                    'recent_votes_from_ip' => $recentVotes,
                    'threshold' => $this->velocityThreshold,
                    'window_seconds' => $this->velocityWindowSeconds,
                ],
            );
        }

        return OverlaySignal::contextStable(
            'ip_velocity',
            'IP velocity within normal limits',
            [],
        );
    }

    public function identifier(): string
    {
        return 'ip_velocity';
    }
}
