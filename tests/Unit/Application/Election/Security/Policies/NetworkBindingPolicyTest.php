<?php

namespace Tests\Unit\Application\Election\Security\Policies;

use App\Application\Election\Security\Policies\NetworkBindingPolicy;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\TrustValidityScope;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use PHPUnit\Framework\TestCase;

class NetworkBindingPolicyTest extends TestCase
{
    private function makeCtx(NetworkTrustEvidence $network): TrustCapabilityContext
    {
        return new TrustCapabilityContext(
            election: null,
            user: null,
            network: $network,
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

    public function test_strategy_none_always_allows(): void
    {
        $policy = new NetworkBindingPolicy();
        $ctx = $this->makeCtx(new NetworkTrustEvidence(
            currentIpHash: 'hash', registeredIpHash: null, whitelist: null,
            maxVotesPerIp: 6, votesFromThisIp: 100,
            restrictionEnabled: false, bindingStrategy: 'none',
        ));

        $result = $policy->evaluate($ctx, TrustLevel::Unverified);

        $this->assertTrue($result->trusted);
    }

    public function test_whitelisted_ip_always_allows(): void
    {
        $policy = new NetworkBindingPolicy();
        $ctx = $this->makeCtx(new NetworkTrustEvidence(
            currentIpHash: 'hash_office', registeredIpHash: null,
            whitelist: ['hash_office'],
            maxVotesPerIp: 6, votesFromThisIp: 100,
            restrictionEnabled: true, bindingStrategy: 'whitelist_only',
        ));

        $result = $policy->evaluate($ctx, TrustLevel::Unverified);

        $this->assertTrue($result->trusted);
    }

    public function test_unverified_exceeds_half_limit_returns_deny(): void
    {
        $policy = new NetworkBindingPolicy();
        $ctx = $this->makeCtx(new NetworkTrustEvidence(
            currentIpHash: 'hash', registeredIpHash: 'hash', whitelist: null,
            maxVotesPerIp: 10, votesFromThisIp: 6, // exceeds 5 (10/2 for unverified)
            restrictionEnabled: true, bindingStrategy: 'ip_count',
        ));

        $result = $policy->evaluate($ctx, TrustLevel::Unverified);

        $this->assertFalse($result->trusted);
        $this->assertEquals('network_limit_exceeded', $result->reason);
    }

    public function test_attested_within_full_limit_allows(): void
    {
        $policy = new NetworkBindingPolicy();
        $ctx = $this->makeCtx(new NetworkTrustEvidence(
            currentIpHash: 'hash', registeredIpHash: 'hash', whitelist: null,
            maxVotesPerIp: 10, votesFromThisIp: 10, // exactly at limit for attested
            restrictionEnabled: true, bindingStrategy: 'ip_count',
        ));

        $result = $policy->evaluate($ctx, TrustLevel::Attested);

        $this->assertTrue($result->trusted);
    }

    public function test_continuity_verified_within_15x_limit_allows(): void
    {
        $policy = new NetworkBindingPolicy();
        $ctx = $this->makeCtx(new NetworkTrustEvidence(
            currentIpHash: 'hash', registeredIpHash: 'hash', whitelist: null,
            maxVotesPerIp: 10, votesFromThisIp: 15, // exactly at floor(10*1.5) for continuity
            restrictionEnabled: true, bindingStrategy: 'ip_count',
        ));

        $result = $policy->evaluate($ctx, TrustLevel::ContinuityVerified);

        $this->assertTrue($result->trusted);
    }

    public function test_registrar_attested_within_2x_limit_allows(): void
    {
        $policy = new NetworkBindingPolicy();
        $ctx = $this->makeCtx(new NetworkTrustEvidence(
            currentIpHash: 'hash', registeredIpHash: 'hash', whitelist: null,
            maxVotesPerIp: 10, votesFromThisIp: 20, // exactly at floor(10*2) for registrar
            restrictionEnabled: true, bindingStrategy: 'ip_count',
        ));

        $result = $policy->evaluate($ctx, TrustLevel::RegistrarAttested);

        $this->assertTrue($result->trusted);
    }

    public function test_registrar_attested_exceeds_2x_limit_denies(): void
    {
        $policy = new NetworkBindingPolicy();
        $ctx = $this->makeCtx(new NetworkTrustEvidence(
            currentIpHash: 'hash', registeredIpHash: 'hash', whitelist: null,
            maxVotesPerIp: 10, votesFromThisIp: 21, // exceeds floor(10*2)
            restrictionEnabled: true, bindingStrategy: 'ip_count',
        ));

        $result = $policy->evaluate($ctx, TrustLevel::RegistrarAttested);

        $this->assertFalse($result->trusted);
    }

    public function test_policy_outcome_contains_remaining_votes(): void
    {
        $policy = new NetworkBindingPolicy();
        $ctx = $this->makeCtx(new NetworkTrustEvidence(
            currentIpHash: 'hash', registeredIpHash: 'hash', whitelist: null,
            maxVotesPerIp: 10, votesFromThisIp: 3, // 7 remaining for Attested
            restrictionEnabled: true, bindingStrategy: 'ip_count',
        ));

        $result = $policy->evaluate($ctx, TrustLevel::Attested);

        $this->assertTrue($result->trusted);
        $this->assertEquals(7, $result->policyOutcomeSequence['network_binding_policy']['remaining_votes']);
    }

    public function test_denial_reason_is_network_limit_exceeded(): void
    {
        $policy = new NetworkBindingPolicy();
        $ctx = $this->makeCtx(new NetworkTrustEvidence(
            currentIpHash: 'hash', registeredIpHash: 'hash', whitelist: null,
            maxVotesPerIp: 4, votesFromThisIp: 5,
            restrictionEnabled: true, bindingStrategy: 'ip_count',
        ));

        $result = $policy->evaluate($ctx, TrustLevel::Attested);

        $this->assertEquals('network_limit_exceeded', $result->reason);
    }
}
