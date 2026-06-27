<?php

namespace Tests\Unit\Application\Election\Security;

use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\TrustValidityScope;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use PHPUnit\Framework\TestCase;

class TrustCapabilityContextTest extends TestCase
{
    private function makeNetwork(): NetworkTrustEvidence
    {
        return new NetworkTrustEvidence(
            currentIpHash: 'hash_current',
            registeredIpHash: 'hash_current',
            whitelist: null,
            maxVotesPerIp: 6,
            votesFromThisIp: 2,
            restrictionEnabled: true,
            bindingStrategy: 'ip_count',
        );
    }

    private function makeDevice(): DeviceTrustContext
    {
        return new DeviceTrustContext(
            fingerprintHash: 'fp_hash',
            registeredFingerprintHash: 'fp_hash',
            matchType: FingerprintMatchType::ExactMatch,
            captureMethod: 'canvas',
            volatility: 'stable',
        );
    }

    private function makeAttestation(bool $attested = true, ?string $registrarId = null): VerificationAttestationRecord
    {
        return new VerificationAttestationRecord(
            required: true,
            attested: $attested,
            registrarId: $registrarId,
            attestationTimestamp: $attested ? new \DateTimeImmutable() : null,
            protocol: 'both',
            networkEvidenceHash: 'hash_current',
            deviceEvidenceHash: 'fp_hash',
            revoked: false,
            validityScope: TrustValidityScope::ElectionScoped,
        );
    }

    private function makeSession(bool $preserved = true): VotingSessionTrustContinuity
    {
        return new VotingSessionTrustContinuity(
            sessionId: 'session_1',
            ipHashAtStart: 'hash_current',
            ipHashCurrent: $preserved ? 'hash_current' : 'hash_different',
            deviceChanged: false,
            continuityState: $preserved ? 'continuous' : 'interrupted',
        );
    }

    public function test_current_trust_level_delegates_to_attestation(): void
    {
        $ctx = new TrustCapabilityContext(
            election: null,
            user: null,
            network: $this->makeNetwork(),
            device: $this->makeDevice(),
            attestation: $this->makeAttestation(attested: true, registrarId: 'registrar_1'),
            sessionContinuity: $this->makeSession(),
        );

        $this->assertEquals(TrustLevel::RegistrarAttested, $ctx->currentTrustLevel());
    }

    public function test_current_trust_level_unverified_when_not_attested(): void
    {
        $ctx = new TrustCapabilityContext(
            election: null,
            user: null,
            network: $this->makeNetwork(),
            device: $this->makeDevice(),
            attestation: $this->makeAttestation(attested: false),
            sessionContinuity: $this->makeSession(),
        );

        $this->assertEquals(TrustLevel::Unverified, $ctx->currentTrustLevel());
    }

    public function test_continuity_preserved_delegates_to_session(): void
    {
        $ctx = new TrustCapabilityContext(
            election: null,
            user: null,
            network: $this->makeNetwork(),
            device: $this->makeDevice(),
            attestation: $this->makeAttestation(),
            sessionContinuity: $this->makeSession(preserved: true),
        );

        $this->assertTrue($ctx->continuityPreserved());
    }

    public function test_continuity_not_preserved_when_ip_changed(): void
    {
        $ctx = new TrustCapabilityContext(
            election: null,
            user: null,
            network: $this->makeNetwork(),
            device: $this->makeDevice(),
            attestation: $this->makeAttestation(),
            sessionContinuity: $this->makeSession(preserved: false),
        );

        $this->assertFalse($ctx->continuityPreserved());
    }

    public function test_context_has_no_authority_deriving_methods(): void
    {
        $ctx = new TrustCapabilityContext(
            election: null,
            user: null,
            network: $this->makeNetwork(),
            device: $this->makeDevice(),
            attestation: $this->makeAttestation(),
            sessionContinuity: $this->makeSession(),
        );

        // Invariant 2: context holds facts, never derived authority conclusions
        $this->assertFalse(method_exists($ctx, 'canVote'));
        $this->assertFalse(method_exists($ctx, 'isAuthorized'));
        $this->assertFalse(method_exists($ctx, 'isEligible'));
        $this->assertFalse(method_exists($ctx, 'trustSufficient'));
    }
}
