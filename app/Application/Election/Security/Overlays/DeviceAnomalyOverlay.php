<?php

namespace App\Application\Election\Security\Overlays;

use App\Application\Election\Security\Overlay;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\Simplified\OverlaySignal;

final class DeviceAnomalyOverlay implements Overlay
{
    public function evaluate(TrustCapabilityContext $ctx): OverlaySignal
    {
        // Device anomaly overlay — pure observation semantics
        // Describes: device fingerprint changed during session or device became volatile
        // Does NOT recommend reverification — only reports the anomaly observation
        // Resolver interprets the anomaly and decides on procedures

        if (is_null($ctx->device)) {
            return OverlaySignal::contextStable(
                'device_anomaly',
                'Device stable, no changes detected',
                [],
            );
        }

        if ($ctx->sessionContinuity->deviceChanged && $ctx->device->volatility === 'volatile') {
            return OverlaySignal::attestationPresent(
                'device_anomaly',
                'Device fingerprint changed since session start or became volatile',
                [
                    'device_volatility' => $ctx->device->volatility,
                    'session_device_changed' => true,
                ],
            );
        }

        return OverlaySignal::contextStable(
            'device_anomaly',
            'Device stable, no changes detected',
            [],
        );
    }

    public function identifier(): string
    {
        return 'device_anomaly';
    }
}
