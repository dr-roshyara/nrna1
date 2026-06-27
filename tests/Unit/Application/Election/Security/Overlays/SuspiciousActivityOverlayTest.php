<?php

namespace Tests\Unit\Application\Election\Security\Overlays;

use App\Application\Election\Security\Overlays\SuspiciousActivityOverlay;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustValidityScope;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use App\Models\Election;
use Tests\TestCase;

class SuspiciousActivityOverlayTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    private function makeContext(?Election $election = null): TrustCapabilityContext
    {
        return new TrustCapabilityContext(
            election: $election,
            user: null,
            network: new NetworkTrustEvidence('hash', null, null, 6, 1, true, 'ip_count'),
            device: new DeviceTrustContext(null, null, FingerprintMatchType::NotRequired, 'none', 'stable'),
            attestation: new VerificationAttestationRecord(
                false, false, null, null, 'none', null, null, false, TrustValidityScope::ElectionScoped
            ),
            sessionContinuity: new VotingSessionTrustContinuity('s1', 'h', 'h', false, 'continuous'),
        );
    }

    public function test_reports_stable_when_activity_normal(): void
    {
        $overlay = new SuspiciousActivityOverlay();
        $election = Election::factory()->create(['trust_overlay_active' => false]);
        $ctx = $this->makeContext($election);

        $signal = $overlay->evaluate($ctx);

        $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
        $this->assertEquals('suspicious_activity', $signal->overlayIdentifier);
    }

    public function test_reports_attestation_when_suspicious_activity_detected(): void
    {
        $overlay = new SuspiciousActivityOverlay();
        $election = Election::factory()->create([
            'trust_overlay_active' => true,
            'trust_overlay_reason' => 'Suspicious voting pattern detected',
        ]);
        $ctx = $this->makeContext($election);

        $signal = $overlay->evaluate($ctx);

        $this->assertEquals('ADDITIONAL_ATTESTATION_PRESENT', $signal->signalType);
        $this->assertEquals('suspicious_activity', $signal->overlayIdentifier);
    }

    public function test_evaluate_never_mutates_context(): void
    {
        $overlay = new SuspiciousActivityOverlay();
        $election = Election::factory()->create(['trust_overlay_active' => true]);
        $ctx = $this->makeContext($election);

        $originalNetworkHash = $ctx->network->currentIpHash;

        $overlay->evaluate($ctx);

        // Verify context unchanged
        $this->assertEquals($originalNetworkHash, $ctx->network->currentIpHash);
    }

    public function test_identifier_matches_registry(): void
    {
        $overlay = new SuspiciousActivityOverlay();

        $this->assertEquals('suspicious_activity', $overlay->identifier());
    }
}
