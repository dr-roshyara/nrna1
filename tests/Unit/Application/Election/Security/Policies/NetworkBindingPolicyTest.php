<?php

namespace Tests\Unit\Application\Election\Security\Policies;

use App\Application\Election\Security\Policies\NetworkBindingPolicy;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\ConstitutionalConcernLevel;
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
    private function makeCtxWithAttestationTrustLevel(NetworkTrustEvidence $network, TrustLevel $trustLevel): TrustCapabilityContext
    {
        $attestation = match ($trustLevel) {
            TrustLevel::Unverified => new VerificationAttestationRecord(
                required: false, attested: false, registrarId: null,
                attestationTimestamp: null, protocol: 'none',
                networkEvidenceHash: null, deviceEvidenceHash: null,
                revoked: false, validityScope: TrustValidityScope::ElectionScoped,
            ),
            TrustLevel::Attested => new VerificationAttestationRecord(
                required: true, attested: true, registrarId: null,
                attestationTimestamp: new \DateTimeImmutable(),
                protocol: 'both', networkEvidenceHash: 'hash', deviceEvidenceHash: 'fp',
                revoked: false, validityScope: TrustValidityScope::ElectionScoped,
            ),
            TrustLevel::ContinuityVerified => new VerificationAttestationRecord(
                required: true, attested: true, registrarId: null,
                attestationTimestamp: new \DateTimeImmutable(),
                protocol: 'both', networkEvidenceHash: 'hash', deviceEvidenceHash: 'fp',
                revoked: false, validityScope: TrustValidityScope::ElectionScoped,
            ),
            TrustLevel::RegistrarAttested => new VerificationAttestationRecord(
                required: true, attested: true, registrarId: 'registrar_1',
                attestationTimestamp: new \DateTimeImmutable(),
                protocol: 'both', networkEvidenceHash: 'hash', deviceEvidenceHash: 'fp',
                revoked: false, validityScope: TrustValidityScope::ElectionScoped,
            ),
        };

        return new TrustCapabilityContext(
            election: null,
            user: null,
            network: $network,
            device: new DeviceTrustContext(
                fingerprintHash: 'fp', registeredFingerprintHash: 'fp',
                matchType: FingerprintMatchType::ExactMatch,
                captureMethod: 'canvas', volatility: 'stable',
            ),
            attestation: $attestation,
            sessionContinuity: new VotingSessionTrustContinuity(
                sessionId: 's1', ipHashAtStart: 'hash', ipHashCurrent: 'hash',
                deviceChanged: false, continuityState: 'continuous',
            ),
        );
    }

    public function test_reports_no_concern_when_restriction_disabled(): void
    {
        $policy = new NetworkBindingPolicy();
        $ctx = $this->makeCtxWithAttestationTrustLevel(
            new NetworkTrustEvidence(
                currentIpHash: 'hash', registeredIpHash: null, whitelist: null,
                maxVotesPerIp: 6, votesFromThisIp: 100,
                restrictionEnabled: false, bindingStrategy: 'none',
            ),
            TrustLevel::Unverified
        );

        $finding = $policy->evaluate($ctx);

        $this->assertFalse($finding->hasConstitutionalConcern());
    }

    public function test_reports_no_concern_when_ip_whitelisted(): void
    {
        $policy = new NetworkBindingPolicy();
        $ctx = $this->makeCtxWithAttestationTrustLevel(
            new NetworkTrustEvidence(
                currentIpHash: 'hash_office', registeredIpHash: null,
                whitelist: ['hash_office'],
                maxVotesPerIp: 6, votesFromThisIp: 100,
                restrictionEnabled: true, bindingStrategy: 'whitelist_only',
            ),
            TrustLevel::Unverified
        );

        $finding = $policy->evaluate($ctx);

        $this->assertFalse($finding->hasConstitutionalConcern());
    }

    public function test_reports_concern_when_unverified_exceeds_half_limit(): void
    {
        $policy = new NetworkBindingPolicy();
        $ctx = $this->makeCtxWithAttestationTrustLevel(
            new NetworkTrustEvidence(
                currentIpHash: 'hash', registeredIpHash: 'hash', whitelist: null,
                maxVotesPerIp: 10, votesFromThisIp: 6,
                restrictionEnabled: true, bindingStrategy: 'ip_count',
            ),
            TrustLevel::Unverified
        );

        $finding = $policy->evaluate($ctx);

        $this->assertTrue($finding->hasConstitutionalConcern());
        $this->assertEquals(ConstitutionalConcernLevel::HIGH, $finding->concernLevel);
        $this->assertEquals('network_limit_exceeded', $finding->constitutionalBasis);
    }

    public function test_reports_no_concern_when_attested_within_full_limit(): void
    {
        $policy = new NetworkBindingPolicy();
        $ctx = $this->makeCtxWithAttestationTrustLevel(
            new NetworkTrustEvidence(
                currentIpHash: 'hash', registeredIpHash: 'hash', whitelist: null,
                maxVotesPerIp: 10, votesFromThisIp: 10,
                restrictionEnabled: true, bindingStrategy: 'ip_count',
            ),
            TrustLevel::Attested
        );

        $finding = $policy->evaluate($ctx);

        $this->assertFalse($finding->hasConstitutionalConcern());
    }

    public function test_reports_no_concern_when_registrar_attested_within_2x_limit(): void
    {
        $policy = new NetworkBindingPolicy();
        $ctx = $this->makeCtxWithAttestationTrustLevel(
            new NetworkTrustEvidence(
                currentIpHash: 'hash', registeredIpHash: 'hash', whitelist: null,
                maxVotesPerIp: 10, votesFromThisIp: 20,
                restrictionEnabled: true, bindingStrategy: 'ip_count',
            ),
            TrustLevel::RegistrarAttested
        );

        $finding = $policy->evaluate($ctx);

        $this->assertFalse($finding->hasConstitutionalConcern());
    }

    public function test_reports_concern_when_registrar_attested_exceeds_2x_limit(): void
    {
        $policy = new NetworkBindingPolicy();
        $ctx = $this->makeCtxWithAttestationTrustLevel(
            new NetworkTrustEvidence(
                currentIpHash: 'hash', registeredIpHash: 'hash', whitelist: null,
                maxVotesPerIp: 10, votesFromThisIp: 21,
                restrictionEnabled: true, bindingStrategy: 'ip_count',
            ),
            TrustLevel::RegistrarAttested
        );

        $finding = $policy->evaluate($ctx);

        $this->assertTrue($finding->hasConstitutionalConcern());
        $this->assertEquals(ConstitutionalConcernLevel::HIGH, $finding->concernLevel);
    }

    public function test_supporting_facts_include_vote_count(): void
    {
        $policy = new NetworkBindingPolicy();
        $ctx = $this->makeCtxWithAttestationTrustLevel(
            new NetworkTrustEvidence(
                currentIpHash: 'hash', registeredIpHash: 'hash', whitelist: null,
                maxVotesPerIp: 10, votesFromThisIp: 11,
                restrictionEnabled: true, bindingStrategy: 'ip_count',
            ),
            TrustLevel::Attested
        );

        $finding = $policy->evaluate($ctx);

        $this->assertArrayHasKey('votes_from_ip', $finding->supportingFacts);
        $this->assertEquals(11, $finding->supportingFacts['votes_from_ip']);
    }
}
