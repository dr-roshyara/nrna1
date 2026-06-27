<?php

namespace Tests\Unit\Application\Election\Security\Simplified;

use App\Domain\Election\Security\Simplified\ConstitutionalEvidenceSnapshot;
use App\Domain\Election\Security\Simplified\ElectionConstitutionSnapshot;
use App\Domain\Election\Security\Simplified\VerificationEvidence;
use App\Domain\Election\Security\Simplified\NetworkEvidence;
use App\Domain\Election\Security\Simplified\DeviceEvidence;
use App\Domain\Election\Security\Simplified\SessionContinuity;
use App\Domain\Election\Security\Simplified\ElectionConstitutionHasher;
use PHPUnit\Framework\TestCase;

class ConstitutionalEvidenceSnapshotTest extends TestCase
{
    private ElectionConstitutionSnapshot $constitution;
    private VerificationEvidence $verification;
    private NetworkEvidence $network;
    private DeviceEvidence $device;
    private SessionContinuity $continuity;

    protected function setUp(): void
    {
        $this->constitution = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_count',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'fingerprint_required',
            ballotAuthorizationProtocol: 'dual_code',
            verificationRequired: true,
        );

        $this->verification = new VerificationEvidence(
            required: true,
            attested: true,
            registrarId: 'reg_001',
            attestationTimestamp: new \DateTimeImmutable('2026-05-27 12:00:00'),
        );

        $this->network = new NetworkEvidence(
            currentIpHash: 'abc123',
            maxVotesPerIp: 6,
            votesFromThisIp: 2,
            restrictionEnabled: true,
            bindingStrategy: 'ip_count',
        );

        $this->device = new DeviceEvidence(
            fingerprintHash: 'def456',
            matchType: 'exact_match',
            captureMethod: 'browser_api',
            volatility: 'stable',
        );

        $this->continuity = new SessionContinuity(
            sessionId: 'sess_001',
            ipHashAtStart: 'abc123',
            ipHashCurrent: 'abc123',
            deviceChanged: false,
            continuityState: 'continuous',
        );
    }

    public function test_snapshot_frozen_at_construction(): void
    {
        $snapshot = new ConstitutionalEvidenceSnapshot(
            constitution: $this->constitution,
            verification: $this->verification,
            network: $this->network,
            device: $this->device,
            continuity: $this->continuity,
            eligibility: new \App\Domain\Election\Security\Simplified\ParticipationEligibilityEvidence(
                hasActiveMembership: true,
                hasValidAssignment: true,
                hasApproval: true,
                isSuspended: false,
                eligibilityEvaluatedAt: new \DateTimeImmutable('2026-05-27 12:00:00'),
                eligibilitySourceVersion: '1.0',
                eligibilityHash: 'test-eligibility-hash',
            ),
            evaluatedAt: new \DateTimeImmutable('2026-05-27 12:00:00'),
            constitutionalHash: ElectionConstitutionHasher::hash($this->constitution),
        );

        $this->assertSame($this->constitution, $snapshot->constitution);
        $this->assertSame($this->verification, $snapshot->verification);
        $this->assertSame($this->network, $snapshot->network);
        $this->assertSame($this->device, $snapshot->device);
        $this->assertSame($this->continuity, $snapshot->continuity);
        $this->assertEquals('2026-05-27 12:00:00', $snapshot->evaluatedAt->format('Y-m-d H:i:s'));
    }

    public function test_same_input_produces_same_hash(): void
    {
        $snapshot1 = new ConstitutionalEvidenceSnapshot(
            constitution: $this->constitution,
            verification: $this->verification,
            network: $this->network,
            device: $this->device,
            continuity: $this->continuity,
            eligibility: new \App\Domain\Election\Security\Simplified\ParticipationEligibilityEvidence(
                hasActiveMembership: true,
                hasValidAssignment: true,
                hasApproval: true,
                isSuspended: false,
                eligibilityEvaluatedAt: new \DateTimeImmutable('2026-05-27 12:00:00'),
                eligibilitySourceVersion: '1.0',
                eligibilityHash: 'test-eligibility-hash',
            ),
            evaluatedAt: new \DateTimeImmutable('2026-05-27 12:00:00'),
            constitutionalHash: ElectionConstitutionHasher::hash($this->constitution),
        );

        $snapshot2 = new ConstitutionalEvidenceSnapshot(
            constitution: $this->constitution,
            verification: $this->verification,
            network: $this->network,
            device: $this->device,
            continuity: $this->continuity,
            eligibility: new \App\Domain\Election\Security\Simplified\ParticipationEligibilityEvidence(
                hasActiveMembership: true,
                hasValidAssignment: true,
                hasApproval: true,
                isSuspended: false,
                eligibilityEvaluatedAt: new \DateTimeImmutable('2026-05-27 12:00:00'),
                eligibilitySourceVersion: '1.0',
                eligibilityHash: 'test-eligibility-hash',
            ),
            evaluatedAt: new \DateTimeImmutable('2026-05-27 12:00:00'),
            constitutionalHash: ElectionConstitutionHasher::hash($this->constitution),
        );

        $this->assertSame($snapshot1->constitutionalHash, $snapshot2->constitutionalHash);
    }

    public function test_snapshot_is_readonly(): void
    {
        $snapshot = new ConstitutionalEvidenceSnapshot(
            constitution: $this->constitution,
            verification: $this->verification,
            network: $this->network,
            device: $this->device,
            continuity: $this->continuity,
            eligibility: new \App\Domain\Election\Security\Simplified\ParticipationEligibilityEvidence(
                hasActiveMembership: true,
                hasValidAssignment: true,
                hasApproval: true,
                isSuspended: false,
                eligibilityEvaluatedAt: new \DateTimeImmutable('2026-05-27 12:00:00'),
                eligibilitySourceVersion: '1.0',
                eligibilityHash: 'test-eligibility-hash',
            ),
            evaluatedAt: new \DateTimeImmutable('2026-05-27 12:00:00'),
            constitutionalHash: ElectionConstitutionHasher::hash($this->constitution),
        );

        $this->expectException(\Error::class);
        $snapshot->constitution = $this->constitution;
    }

    public function test_has_no_behavioral_methods(): void
    {
        $reflection = new \ReflectionClass(ConstitutionalEvidenceSnapshot::class);
        $methods = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            fn($m) => $m->getName() !== '__construct'
        );

        $this->assertCount(0, $methods, 'ConstitutionalEvidenceSnapshot must be pure data');
    }
}
