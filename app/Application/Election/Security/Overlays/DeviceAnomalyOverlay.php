<?php

namespace App\Application\Election\Security\Overlays;

use App\Application\Election\Security\ConstitutionalOverlay;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\OverlayInfluence;
use App\Domain\Election\Security\OverlaySignal;

final class DeviceAnomalyOverlay implements ConstitutionalOverlay
{
    public function evaluate(TrustCapabilityContext $ctx): OverlaySignal
    {
        // Device anomaly detection: volatile fingerprints after stable session
        // Signal re-verification requirement when device becomes unstable

        // No anomaly if no device context
        if (is_null($ctx->device)) {
            return OverlaySignal::continue($this->identifier());
        }

        // Detect anomaly: device changed AND current device is volatile
        // This indicates the device fingerprint became unstable/volatile
        if ($ctx->sessionContinuity->deviceChanged && $ctx->device->volatility === 'volatile') {
            return new OverlaySignal(
                OverlayInfluence::REQUIRE_RE_VERIFICATION,
                $this->identifier(),
                'device_volatility_anomaly_detected',
                [
                    'device_volatility' => $ctx->device->volatility,
                    'session_device_changed' => true,
                ],
                null,
                null,
            );
        }

        return OverlaySignal::continue($this->identifier());
    }

    public function identifier(): string
    {
        return 'device_anomaly';
    }
}
