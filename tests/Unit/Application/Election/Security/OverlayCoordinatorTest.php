<?php

namespace Tests\Unit\Application\Election\Security;

use App\Application\Election\Security\OverlayCoordinator;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustValidityScope;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use App\Models\Election;
use PHPUnit\Framework\TestCase;

class OverlayCoordinatorTest extends TestCase
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

    public function test_no_overlay_active_returns_null(): void
    {
        $coordinator = new OverlayCoordinator();
        $ctx = $this->makeCtx(null); // no election = no overlay

        $result = $coordinator->apply($ctx);

        $this->assertNull($result);
    }

    public function test_trust_overlay_inactive_returns_null(): void
    {
        $coordinator = new OverlayCoordinator();
        $election = new Election();
        $election->trust_overlay_active = false;
        $ctx = $this->makeCtx($election);

        $result = $coordinator->apply($ctx);

        $this->assertNull($result);
    }

    public function test_trust_overlay_active_returns_deny(): void
    {
        $coordinator = new OverlayCoordinator();
        $election = new Election();
        $election->trust_overlay_active = true;
        $election->trust_overlay_reason = 'Suspicious activity detected';
        $ctx = $this->makeCtx($election);

        $result = $coordinator->apply($ctx);

        $this->assertNotNull($result);
        $this->assertFalse($result->trusted);
        $this->assertEquals('overlay_active', $result->reason);
    }

    public function test_overlay_never_mutates_context_facts(): void
    {
        $coordinator = new OverlayCoordinator();
        $election = new Election();
        $election->trust_overlay_active = true;
        $ctx = $this->makeCtx($election);

        // Record facts before
        $ipHashBefore = $ctx->network->currentIpHash;
        $fpHashBefore = $ctx->device->fingerprintHash;

        $coordinator->apply($ctx);

        // Invariant 5: context facts must be unchanged
        $this->assertEquals($ipHashBefore, $ctx->network->currentIpHash);
        $this->assertEquals($fpHashBefore, $ctx->device->fingerprintHash);
    }
}
