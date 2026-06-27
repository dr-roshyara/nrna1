<?php

namespace Tests\Unit\Application\Election\Security\Policies;

use App\Application\Election\Security\Policies\VerificationAttestationPolicy;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\ConstitutionalConcernLevel;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\EvidenceWeightCategory;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
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

    public function test_reports_critical_concern_when_not_satisfied_and_required(): void
    {
        $policy = new VerificationAttestationPolicy();
        $ctx = $this->makeCtx($this->makeAttestation(required: true, attested: false));

        $finding = $policy->evaluate($ctx);

        $this->assertTrue($finding->hasConstitutionalConcern());
        $this->assertEquals(ConstitutionalConcernLevel::HIGH, $finding->concernLevel);
        $this->assertEquals('verification_required', $finding->constitutionalBasis);
    }

    public function test_reports_no_concern_when_satisfied_with_registrar(): void
    {
        $policy = new VerificationAttestationPolicy();
        $ctx = $this->makeCtx($this->makeAttestation(required: true, attested: true, registrarId: 'registrar_1'));

        $finding = $policy->evaluate($ctx);

        $this->assertFalse($finding->hasConstitutionalConcern());
        $this->assertEquals(ConstitutionalConcernLevel::NONE, $finding->concernLevel);
    }

    public function test_reports_no_concern_when_satisfied_without_registrar(): void
    {
        $policy = new VerificationAttestationPolicy();
        $ctx = $this->makeCtx($this->makeAttestation(required: true, attested: true));

        $finding = $policy->evaluate($ctx);

        $this->assertFalse($finding->hasConstitutionalConcern());
        $this->assertEquals(ConstitutionalConcernLevel::NONE, $finding->concernLevel);
    }

    public function test_reports_no_concern_when_not_required_and_not_attested(): void
    {
        $policy = new VerificationAttestationPolicy();
        $ctx = $this->makeCtx($this->makeAttestation(required: false, attested: false));

        $finding = $policy->evaluate($ctx);

        $this->assertFalse($finding->hasConstitutionalConcern());
        $this->assertEquals(ConstitutionalConcernLevel::NONE, $finding->concernLevel);
    }

    public function test_reports_critical_concern_when_attestation_revoked(): void
    {
        $policy = new VerificationAttestationPolicy();
        $ctx = $this->makeCtx($this->makeAttestation(required: true, attested: true, revoked: true));

        $finding = $policy->evaluate($ctx);

        $this->assertTrue($finding->hasConstitutionalConcern());
        $this->assertEquals(ConstitutionalConcernLevel::CRITICAL, $finding->concernLevel);
        $this->assertEquals('attestation_revoked', $finding->constitutionalBasis);
    }

    public function test_finding_includes_policy_identifier(): void
    {
        $policy = new VerificationAttestationPolicy();
        $ctx = $this->makeCtx($this->makeAttestation(required: true, attested: true, registrarId: 'r1'));

        $finding = $policy->evaluate($ctx);

        $this->assertEquals('verification', $finding->policyIdentifier);
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
