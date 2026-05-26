<?php

namespace Tests\Unit\Application\Election\Security;

use App\Application\Election\Security\Policies\DeviceBindingPolicy;
use App\Application\Election\Security\Policies\NetworkBindingPolicy;
use App\Application\Election\Security\Policies\VerificationAttestationPolicy;
use App\Application\Election\Security\PolicySequence;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\TrustValidityScope;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use PHPUnit\Framework\TestCase;

class PolicySequenceTest extends TestCase
{
    private function makeFullPassCtx(): TrustCapabilityContext
    {
        return new TrustCapabilityContext(
            election: null,
            user: null,
            network: new NetworkTrustEvidence(
                currentIpHash: 'hash', registeredIpHash: 'hash', whitelist: null,
                maxVotesPerIp: 10, votesFromThisIp: 2,
                restrictionEnabled: true, bindingStrategy: 'ip_count',
            ),
            device: new DeviceTrustContext(
                fingerprintHash: 'fp', registeredFingerprintHash: 'fp',
                matchType: FingerprintMatchType::ExactMatch,
                captureMethod: 'canvas', volatility: 'stable',
            ),
            attestation: new VerificationAttestationRecord(
                required: true, attested: true, registrarId: null,
                attestationTimestamp: new \DateTimeImmutable(),
                protocol: 'both', networkEvidenceHash: 'hash', deviceEvidenceHash: 'fp',
                revoked: false, validityScope: TrustValidityScope::ElectionScoped,
            ),
            sessionContinuity: new VotingSessionTrustContinuity(
                sessionId: 's1', ipHashAtStart: 'hash', ipHashCurrent: 'hash',
                deviceChanged: false, continuityState: 'continuous',
            ),
        );
    }

    private function makeUnverifiedCtx(): TrustCapabilityContext
    {
        return new TrustCapabilityContext(
            election: null, user: null,
            network: new NetworkTrustEvidence(
                currentIpHash: 'hash', registeredIpHash: 'hash', whitelist: null,
                maxVotesPerIp: 10, votesFromThisIp: 2,
                restrictionEnabled: true, bindingStrategy: 'ip_count',
            ),
            device: new DeviceTrustContext(
                fingerprintHash: null, registeredFingerprintHash: null,
                matchType: FingerprintMatchType::NotRequired,
                captureMethod: 'none', volatility: 'stable',
            ),
            attestation: new VerificationAttestationRecord(
                required: false, attested: false, registrarId: null,
                attestationTimestamp: null, protocol: 'none',
                networkEvidenceHash: null, deviceEvidenceHash: null,
                revoked: false, validityScope: TrustValidityScope::ElectionScoped,
            ),
            sessionContinuity: new VotingSessionTrustContinuity(
                sessionId: 's1', ipHashAtStart: 'hash', ipHashCurrent: 'hash',
                deviceChanged: false, continuityState: 'continuous',
            ),
        );
    }

    private function makeSequence(): PolicySequence
    {
        return new PolicySequence(
            verificationPolicy: new VerificationAttestationPolicy(),
            networkPolicy: new NetworkBindingPolicy(),
            devicePolicy: new DeviceBindingPolicy(),
        );
    }

    public function test_all_policies_pass_returns_allow(): void
    {
        $sequence = $this->makeSequence();
        $result = $sequence->evaluate($this->makeFullPassCtx());

        $this->assertTrue($result->trusted);
    }

    public function test_verification_denial_short_circuits_network_and_device(): void
    {
        // Verification required + not attested → denial, network/device never evaluated
        $ctx = new TrustCapabilityContext(
            election: null, user: null,
            network: new NetworkTrustEvidence(
                currentIpHash: 'hash', registeredIpHash: 'hash', whitelist: null,
                maxVotesPerIp: 10, votesFromThisIp: 100, // would fail if evaluated
                restrictionEnabled: true, bindingStrategy: 'ip_count',
            ),
            device: new DeviceTrustContext(
                fingerprintHash: 'new', registeredFingerprintHash: 'original',
                matchType: FingerprintMatchType::NoMatch, // would fail if evaluated
                captureMethod: 'canvas', volatility: 'stable',
            ),
            attestation: new VerificationAttestationRecord(
                required: true, attested: false, registrarId: null,
                attestationTimestamp: null, protocol: 'none',
                networkEvidenceHash: null, deviceEvidenceHash: null,
                revoked: false, validityScope: TrustValidityScope::ElectionScoped,
            ),
            sessionContinuity: new VotingSessionTrustContinuity(
                sessionId: 's1', ipHashAtStart: 'hash', ipHashCurrent: 'hash',
                deviceChanged: false, continuityState: 'continuous',
            ),
        );

        $sequence = $this->makeSequence();
        $result = $sequence->evaluate($ctx);

        $this->assertFalse($result->trusted);
        $this->assertEquals('verification_required', $result->reason);
        // If short-circuited, network_binding_policy key absent
        $this->assertArrayNotHasKey('network_binding_policy', $result->policyOutcomeSequence);
    }

    public function test_trust_level_from_verification_flows_to_network_policy(): void
    {
        // Unverified context (not required) → Unverified trust → network uses Unverified thresholds
        $sequence = $this->makeSequence();
        $result = $sequence->evaluate($this->makeUnverifiedCtx());

        $this->assertTrue($result->trusted);
        $this->assertEquals(TrustLevel::Unverified, $result->trustLevel);
    }

    public function test_all_three_policy_outcomes_present_when_all_pass(): void
    {
        $sequence = $this->makeSequence();
        $result = $sequence->evaluate($this->makeFullPassCtx());

        $this->assertArrayHasKey('verification_attestation_policy', $result->policyOutcomeSequence);
        $this->assertArrayHasKey('network_binding_policy', $result->policyOutcomeSequence);
        $this->assertArrayHasKey('device_binding_policy', $result->policyOutcomeSequence);
    }

    public function test_device_denial_returns_device_reason(): void
    {
        $ctx = new TrustCapabilityContext(
            election: null, user: null,
            network: new NetworkTrustEvidence(
                currentIpHash: 'hash', registeredIpHash: 'hash', whitelist: null,
                maxVotesPerIp: 10, votesFromThisIp: 1,
                restrictionEnabled: true, bindingStrategy: 'ip_count',
            ),
            device: new DeviceTrustContext(
                fingerprintHash: 'fp_new', registeredFingerprintHash: 'fp_original',
                matchType: FingerprintMatchType::NoMatch,
                captureMethod: 'canvas', volatility: 'stable',
            ),
            attestation: new VerificationAttestationRecord(
                required: true, attested: true, registrarId: null,
                attestationTimestamp: new \DateTimeImmutable(),
                protocol: 'both', networkEvidenceHash: 'hash', deviceEvidenceHash: 'fp_original',
                revoked: false, validityScope: TrustValidityScope::ElectionScoped,
            ),
            sessionContinuity: new VotingSessionTrustContinuity(
                sessionId: 's1', ipHashAtStart: 'hash', ipHashCurrent: 'hash',
                deviceChanged: false, continuityState: 'continuous',
            ),
        );

        $sequence = $this->makeSequence();
        $result = $sequence->evaluate($ctx);

        $this->assertFalse($result->trusted);
        $this->assertEquals('device_attestation_failed', $result->reason);
    }
}
