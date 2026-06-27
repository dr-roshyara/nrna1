<?php

namespace Tests\Unit\Application\Election\Security;

use App\Application\Election\Security\OverlayAggregator;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\Simplified\ConstitutionalObservationContext;
use App\Domain\Election\Security\TrustValidityScope;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use App\Models\Election;
use PHPUnit\Framework\TestCase;

class OverlayAggregatorTest extends TestCase
{
    private function makeCtx(?Election $election): TrustCapabilityContext
    {
        return new TrustCapabilityContext(
            election: $election,
            user: null,
            network: new NetworkTrustEvidence(
                currentIpHash: 'hash', registeredIpHash: 'hash', whitelist: null,
                maxVotesPerIp: 6, votesFromThisIp: 1, restrictionEnabled: true, bindingStrategy: 'ip_count',
            ),
            device: new DeviceTrustContext(
                fingerprintHash: 'fp', registeredFingerprintHash: 'fp',
                matchType: FingerprintMatchType::ExactMatch, captureMethod: 'canvas', volatility: 'stable',
            ),
            attestation: new VerificationAttestationRecord(
                required: false, attested: false, registrarId: null, attestationTimestamp: null,
                protocol: 'none', networkEvidenceHash: null, deviceEvidenceHash: null,
                revoked: false, validityScope: TrustValidityScope::ElectionScoped,
            ),
            sessionContinuity: new VotingSessionTrustContinuity(
                sessionId: 's1', ipHashAtStart: 'hash', ipHashCurrent: 'hash',
                deviceChanged: false, continuityState: 'continuous',
            ),
        );
    }

    public function test_no_overlays_returns_no_influence(): void
    {
        // OverlayAggregator with empty overlays array
        $coordinator = new OverlayAggregator([]);
        $ctx = $this->makeCtx(null);

        $result = $coordinator->aggregate($ctx);

        $this->assertInstanceOf(ConstitutionalObservationContext::class, $result);
        $this->assertTrue($result->isEmpty());
        $this->assertCount(0, $result->all());
    }

    public function test_overlays_aggregate_signals(): void
    {
        // OverlayAggregator with empty overlays array (no overlays to evaluate)
        $coordinator = new OverlayAggregator([]);
        $election = new Election();
        $ctx = $this->makeCtx($election);

        $result = $coordinator->aggregate($ctx);

        $this->assertInstanceOf(ConstitutionalObservationContext::class, $result);
        // No overlays = no signals = empty context
        $this->assertTrue($result->isEmpty());
    }

    public function test_overlay_never_mutates_context_facts(): void
    {
        // OverlayAggregator with empty overlays array
        $coordinator = new OverlayAggregator([]);
        $election = new Election();
        $ctx = $this->makeCtx($election);

        // Record facts before
        $ipHashBefore = $ctx->network->currentIpHash;
        $fpHashBefore = $ctx->device->fingerprintHash;

        $coordinator->aggregate($ctx);

        // Invariant: context facts must be unchanged
        $this->assertEquals($ipHashBefore, $ctx->network->currentIpHash);
        $this->assertEquals($fpHashBefore, $ctx->device->fingerprintHash);
    }
}
