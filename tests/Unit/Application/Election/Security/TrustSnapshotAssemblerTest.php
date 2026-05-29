<?php

namespace Tests\Unit\Application\Election\Security;

use App\Application\Election\Security\TrustCapabilityContext;
use App\Application\Election\Security\TrustSnapshotAssembler;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustEvaluationState;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\TrustValidityScope;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use App\Domain\Election\Security\VotingTrustResult;
use App\Models\Election;
use PHPUnit\Framework\TestCase;

class TrustSnapshotAssemblerTest extends TestCase
{
    private function makeCtx(?Election $election = null): TrustCapabilityContext
    {
        return new TrustCapabilityContext(
            election: $election,
            user: null,
            network: new NetworkTrustEvidence(
                currentIpHash: 'hash', registeredIpHash: 'hash', whitelist: null,
                maxVotesPerIp: 6, votesFromThisIp: 1,
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

    public function test_assembles_snapshot_from_allow_result(): void
    {
        $result = VotingTrustResult::sufficientEvidence(
            trustLevel: TrustLevel::Attested,
            context: ['network_binding' => 'exact_match'],
            sequence: ['verification_attestation_policy' => 'passed_attested'],
        );

        $ctx = $this->makeCtx();
        $assembler = new TrustSnapshotAssembler();
        $snapshot = $assembler->assemble($result, $ctx);

        $this->assertTrue($snapshot->trusted);
        $this->assertEquals(TrustLevel::Attested, $snapshot->trustLevel);
        $this->assertEquals('verification_attestation_policy', array_key_first($snapshot->trustProvenance));
    }

    public function test_assembles_snapshot_from_deny_result(): void
    {
        $result = VotingTrustResult::insufficientEvidence(
            reason: 'network_limit_exceeded',
            trustLevel: TrustLevel::Unverified,
            context: ['trust_level' => 'unverified'],
            sequence: ['network_binding_policy' => ['outcome' => 'denied']],
        );

        $ctx = $this->makeCtx();
        $assembler = new TrustSnapshotAssembler();
        $snapshot = $assembler->assemble($result, $ctx);

        $this->assertFalse($snapshot->trusted);
        $this->assertEquals('network_limit_exceeded', $snapshot->denialReason);
    }

    public function test_attestation_valid_from_context(): void
    {
        $result = VotingTrustResult::sufficientEvidence(
            trustLevel: TrustLevel::Attested,
            context: [],
            sequence: [],
        );

        $ctx = $this->makeCtx();
        $assembler = new TrustSnapshotAssembler();
        $snapshot = $assembler->assemble($result, $ctx);

        $this->assertTrue($snapshot->attestationValid);
        $this->assertEquals('session', $snapshot->attestationSource);
    }

    public function test_requires_view_token_for_dual_code(): void
    {
        $result = VotingTrustResult::sufficientEvidence(
            trustLevel: TrustLevel::Attested,
            context: [],
            sequence: [],
        );

        $election = new Election();
        $election->ballot_authorization_protocol = 'dual_code';
        $ctx = $this->makeCtx($election);

        $assembler = new TrustSnapshotAssembler();
        $snapshot = $assembler->assemble($result, $ctx);

        $this->assertTrue($snapshot->requiresViewToken);
        $this->assertTrue($snapshot->requiresSeparateCommit);
    }
}
