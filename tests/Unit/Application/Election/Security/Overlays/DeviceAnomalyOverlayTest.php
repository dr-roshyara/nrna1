<?php

namespace Tests\Unit\Application\Election\Security\Overlays;

use App\Application\Election\Security\Overlays\DeviceAnomalyOverlay;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustValidityScope;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use App\Models\Election;
use PHPUnit\Framework\TestCase;

class DeviceAnomalyOverlayTest extends TestCase
{
    private function makeContext(
        string $deviceVolatility = 'stable',
        bool $deviceChanged = false,
    ): TrustCapabilityContext {
        return new TrustCapabilityContext(
            election: new Election(),
            user: null,
            network: new NetworkTrustEvidence('hash', null, null, 6, 1, true, 'ip_count'),
            device: new DeviceTrustContext(
                'fp_hash', 'fp_hash', FingerprintMatchType::ExactMatch, 'canvas', $deviceVolatility
            ),
            attestation: new VerificationAttestationRecord(
                false, false, null, null, 'none', null, null, false, TrustValidityScope::ElectionScoped
            ),
            sessionContinuity: new VotingSessionTrustContinuity('s1', 'h', 'h', $deviceChanged, 'continuous'),
        );
    }

    public function test_reports_stable_when_device_stable(): void
    {
        $overlay = new DeviceAnomalyOverlay();
        $ctx = $this->makeContext(deviceVolatility: 'stable', deviceChanged: false);

        $signal = $overlay->evaluate($ctx);

        $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
        $this->assertEquals('device_anomaly', $signal->overlayIdentifier);
    }

    public function test_reports_attestation_when_device_becomes_volatile(): void
    {
        $overlay = new DeviceAnomalyOverlay();
        $ctx = $this->makeContext(deviceVolatility: 'volatile', deviceChanged: true);

        $signal = $overlay->evaluate($ctx);

        $this->assertEquals('ADDITIONAL_ATTESTATION_PRESENT', $signal->signalType);
        $this->assertEquals('device_anomaly', $signal->overlayIdentifier);
    }

    public function test_identifier_matches_registry(): void
    {
        $overlay = new DeviceAnomalyOverlay();

        $this->assertEquals('device_anomaly', $overlay->identifier());
    }
}
