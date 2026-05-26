<?php

namespace Tests\Unit\Application\Election\Security\Policies;

use App\Application\Election\Security\Policies\DeviceBindingPolicy;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustValidityScope;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use PHPUnit\Framework\TestCase;

class DeviceBindingPolicyTest extends TestCase
{
    private function makeCtx(DeviceTrustContext $device): TrustCapabilityContext
    {
        return new TrustCapabilityContext(
            election: null,
            user: null,
            network: new NetworkTrustEvidence(
                currentIpHash: 'hash', registeredIpHash: 'hash', whitelist: null,
                maxVotesPerIp: 6, votesFromThisIp: 1, restrictionEnabled: true, bindingStrategy: 'ip_count',
            ),
            device: $device,
            attestation: new VerificationAttestationRecord(
                required: true, attested: true, registrarId: null,
                attestationTimestamp: new \DateTimeImmutable(),
                protocol: 'fingerprint_only', networkEvidenceHash: 'hash', deviceEvidenceHash: 'fp',
                revoked: false, validityScope: TrustValidityScope::ElectionScoped,
            ),
            sessionContinuity: new VotingSessionTrustContinuity(
                sessionId: 's1', ipHashAtStart: 'hash', ipHashCurrent: 'hash',
                deviceChanged: false, continuityState: 'continuous',
            ),
        );
    }

    public function test_not_required_always_allows(): void
    {
        $policy = new DeviceBindingPolicy();
        $ctx = $this->makeCtx(new DeviceTrustContext(
            fingerprintHash: null, registeredFingerprintHash: null,
            matchType: FingerprintMatchType::NotRequired,
            captureMethod: 'none', volatility: 'stable',
        ));

        $result = $policy->evaluate($ctx);

        $this->assertTrue($result->trusted);
    }

    public function test_exact_match_allows(): void
    {
        $policy = new DeviceBindingPolicy();
        $ctx = $this->makeCtx(new DeviceTrustContext(
            fingerprintHash: 'fp_hash', registeredFingerprintHash: 'fp_hash',
            matchType: FingerprintMatchType::ExactMatch,
            captureMethod: 'canvas', volatility: 'stable',
        ));

        $result = $policy->evaluate($ctx);

        $this->assertTrue($result->trusted);
    }

    public function test_no_match_when_required_denies(): void
    {
        $policy = new DeviceBindingPolicy();
        $ctx = $this->makeCtx(new DeviceTrustContext(
            fingerprintHash: 'fp_new', registeredFingerprintHash: 'fp_original',
            matchType: FingerprintMatchType::NoMatch,
            captureMethod: 'canvas', volatility: 'stable',
        ));

        $result = $policy->evaluate($ctx);

        $this->assertFalse($result->trusted);
        $this->assertEquals('device_attestation_failed', $result->reason);
    }

    public function test_no_match_when_not_required_allows(): void
    {
        $policy = new DeviceBindingPolicy();
        $ctx = $this->makeCtx(new DeviceTrustContext(
            fingerprintHash: 'fp_new', registeredFingerprintHash: 'fp_original',
            matchType: FingerprintMatchType::NotRequired,
            captureMethod: 'canvas', volatility: 'stable',
        ));

        $result = $policy->evaluate($ctx);

        $this->assertTrue($result->trusted);
    }
}
