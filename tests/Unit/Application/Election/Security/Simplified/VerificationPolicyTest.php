<?php

namespace Tests\Unit\Application\Election\Security\Simplified;

use App\Application\Election\Security\Simplified\Policies\VerificationPolicy;
use App\Application\Election\Security\Simplified\PolicyFinding;
use App\Domain\Election\Security\Simplified\ConstitutionalEvidenceSnapshot;
use App\Domain\Election\Security\Simplified\ElectionConstitutionSnapshot;
use App\Domain\Election\Security\Simplified\VerificationEvidence;
use App\Domain\Election\Security\Simplified\NetworkEvidence;
use App\Domain\Election\Security\Simplified\DeviceEvidence;
use App\Domain\Election\Security\Simplified\SessionContinuity;
use App\Domain\Election\Security\Simplified\ElectionConstitutionHasher;
use PHPUnit\Framework\TestCase;

class VerificationPolicyTest extends TestCase
{
    private VerificationPolicy $policy;

    protected function setUp(): void
    {
        $this->policy = new VerificationPolicy();
    }

    public function test_passes_when_not_required(): void
    {
        $constitution = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'none',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'none',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: false,
        );

        $evidence = $this->createSnapshot($constitution,
            verification: new VerificationEvidence(false, false, null, null),
        );

        $finding = $this->policy->evaluate($evidence);

        $this->assertTrue($finding->passed);
        $this->assertSame('verification_policy', $finding->policyIdentifier);
    }

    public function test_passes_when_attested(): void
    {
        $constitution = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_count',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'none',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: true,
        );

        $evidence = $this->createSnapshot($constitution,
            verification: new VerificationEvidence(true, true, 'reg_001', new \DateTimeImmutable()),
        );

        $finding = $this->policy->evaluate($evidence);

        $this->assertTrue($finding->passed);
        $this->assertStringContainsString('Article 6', $finding->constitutionalBasis);
    }

    public function test_fails_when_required_and_not_attested(): void
    {
        $constitution = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_count',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'none',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: true,
        );

        $evidence = $this->createSnapshot($constitution,
            verification: new VerificationEvidence(true, false, null, null),
        );

        $finding = $this->policy->evaluate($evidence);

        $this->assertFalse($finding->passed);
        $this->assertStringContainsString('Article 6', $finding->constitutionalBasis);
    }

    public function test_returns_policy_finding_not_trust_level(): void
    {
        $constitution = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'none',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'none',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: false,
        );

        $evidence = $this->createSnapshot($constitution,
            verification: new VerificationEvidence(false, false, null, null),
        );

        $finding = $this->policy->evaluate($evidence);

        // Must return PolicyFinding, not TrustLevel
        $this->assertInstanceOf(PolicyFinding::class, $finding);

        // Verify PolicyFinding has no trust level or authority vocabulary
        $this->assertObjectNotHasProperty('trustLevel', $finding);
        $this->assertIsBool($finding->passed);
    }

    public function test_no_authority_vocabulary_in_output(): void
    {
        $constitution = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_count',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'none',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: true,
        );

        $evidence = $this->createSnapshot($constitution,
            verification: new VerificationEvidence(true, true, 'reg_001', new \DateTimeImmutable()),
        );

        $finding = $this->policy->evaluate($evidence);

        // PolicyFinding must not contain authority vocabulary
        $this->assertObjectNotHasProperty('allowed', $finding);
        $this->assertObjectNotHasProperty('denied', $finding);
        $this->assertObjectNotHasProperty('granted', $finding);
        $this->assertObjectNotHasProperty('authorized', $finding);
        $this->assertObjectNotHasProperty('denialReason', $finding);
        $this->assertObjectNotHasProperty('capability', $finding);
    }

    public function test_supporting_facts_contain_verification_details(): void
    {
        $constitution = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_count',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'none',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: true,
        );

        $evidence = $this->createSnapshot($constitution,
            verification: new VerificationEvidence(true, true, 'reg_001', new \DateTimeImmutable('2026-05-27 12:00:00')),
        );

        $finding = $this->policy->evaluate($evidence);

        $this->assertArrayHasKey('required', $finding->supportingFacts);
        $this->assertArrayHasKey('attested', $finding->supportingFacts);
        $this->assertArrayHasKey('registrar_present', $finding->supportingFacts);
        $this->assertTrue($finding->supportingFacts['required']);
        $this->assertTrue($finding->supportingFacts['attested']);
    }

    private function createSnapshot(
        ElectionConstitutionSnapshot $constitution,
        ?VerificationEvidence $verification = null,
        ?NetworkEvidence $network = null,
        ?DeviceEvidence $device = null,
        ?SessionContinuity $continuity = null,
    ): ConstitutionalEvidenceSnapshot {
        return new ConstitutionalEvidenceSnapshot(
            constitution: $constitution,
            verification: $verification ?? new VerificationEvidence(false, false, null, null),
            network: $network ?? new NetworkEvidence('', 6, 0, false, 'none'),
            device: $device ?? new DeviceEvidence(null, 'not_required', 'none', 'stable'),
            continuity: $continuity ?? new SessionContinuity('', '', '', false, 'continuous'),
            eligibility: new \App\Domain\Election\Security\Simplified\ParticipationEligibilityEvidence(
                hasActiveMembership: true,
                hasValidAssignment: true,
                hasApproval: true,
                isSuspended: false,
                eligibilityEvaluatedAt: new \DateTimeImmutable('2026-05-27 12:00:00'),
                eligibilitySourceVersion: '1.0',
                eligibilityHash: 'test-eligibility-hash',
            ),
            evaluatedAt: new \DateTimeImmutable(),
            constitutionalHash: ElectionConstitutionHasher::hash($constitution),
        );
    }
}
