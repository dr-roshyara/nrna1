<?php

namespace Tests\Unit\Application\Election\Security\Policies;

use App\Application\Election\Security\Policies\VerificationAttestationPolicy;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\TrustValidityScope;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use PHPUnit\Framework\TestCase;

class VerificationAttestationPolicyTest extends TestCase
{
    private function makeCtx(VerificationAttestationRecord $attestation): TrustCapabilityContext
    {
        return new TrustCapabilityContext(
            election: null,
            user: null,
            network: new NetworkTrustEvidence(
                currentIpHash: 'hash', registeredIpHash: 'hash',
                whitelist: null, maxVotesPerIp: 6, votesFromThisIp: 1,
                restrictionEnabled: true, bindingStrategy: 'ip_count',
            ),
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

    private function makeAttestation(bool $required, bool $attested, ?string $registrarId = null, bool $revoked = false): VerificationAttestationRecord
    {
        return new VerificationAttestationRecord(
            required: $required,
            attested: $attested,
            registrarId: $registrarId,
            attestationTimestamp: $attested ? new \DateTimeImmutable() : null,
            protocol: 'both',
            networkEvidenceHash: 'hash',
            deviceEvidenceHash: 'fp',
            revoked: $revoked,
            validityScope: TrustValidityScope::ElectionScoped,
        );
    }

    public function test_not_satisfied_and_required_returns_deny(): void
    {
        $policy = new VerificationAttestationPolicy();
        $ctx = $this->makeCtx($this->makeAttestation(required: true, attested: false));

        $result = $policy->evaluate($ctx);

        $this->assertFalse($result->trusted);
        $this->assertEquals('verification_required', $result->reason);
    }

    public function test_satisfied_with_registrar_returns_registrar_attested(): void
    {
        $policy = new VerificationAttestationPolicy();
        $ctx = $this->makeCtx($this->makeAttestation(required: true, attested: true, registrarId: 'registrar_1'));

        $result = $policy->evaluate($ctx);

        $this->assertTrue($result->trusted);
        $this->assertEquals(TrustLevel::RegistrarAttested, $result->trustLevel);
    }

    public function test_satisfied_without_registrar_returns_attested(): void
    {
        $policy = new VerificationAttestationPolicy();
        $ctx = $this->makeCtx($this->makeAttestation(required: true, attested: true));

        $result = $policy->evaluate($ctx);

        $this->assertTrue($result->trusted);
        $this->assertEquals(TrustLevel::Attested, $result->trustLevel);
    }

    public function test_not_required_returns_unverified_allow(): void
    {
        $policy = new VerificationAttestationPolicy();
        $ctx = $this->makeCtx($this->makeAttestation(required: false, attested: false));

        $result = $policy->evaluate($ctx);

        $this->assertTrue($result->trusted);
        $this->assertEquals(TrustLevel::Unverified, $result->trustLevel);
    }

    public function test_revoked_attestation_returns_deny(): void
    {
        $policy = new VerificationAttestationPolicy();
        $ctx = $this->makeCtx($this->makeAttestation(required: true, attested: true, revoked: true));

        $result = $policy->evaluate($ctx);

        $this->assertFalse($result->trusted);
        $this->assertEquals('attestation_revoked', $result->reason);
    }

    public function test_policy_outcome_recorded_in_sequence(): void
    {
        $policy = new VerificationAttestationPolicy();
        $ctx = $this->makeCtx($this->makeAttestation(required: true, attested: true, registrarId: 'r1'));

        $result = $policy->evaluate($ctx);

        $this->assertArrayHasKey('verification_attestation_policy', $result->policyOutcomeSequence);
    }

    public function test_policies_do_not_call_each_other(): void
    {
        // Invariant 4: policy is self-contained, receives only TrustCapabilityContext
        $policy = new VerificationAttestationPolicy();
        $reflected = new \ReflectionClass($policy);

        // No constructor dependencies (no injected policy collaborators)
        $this->assertCount(0, $reflected->getConstructor()?->getParameters() ?? []);
    }
}
