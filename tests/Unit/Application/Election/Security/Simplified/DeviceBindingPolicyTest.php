<?php

namespace Tests\Unit\Application\Election\Security\Simplified;

use App\Application\Election\Security\Simplified\Policies\DeviceBindingPolicy;
use App\Application\Election\Security\Simplified\PolicyFinding;
use App\Domain\Election\Security\Simplified\ConstitutionalEvidenceSnapshot;
use App\Domain\Election\Security\Simplified\ElectionConstitutionSnapshot;
use App\Domain\Election\Security\Simplified\VerificationEvidence;
use App\Domain\Election\Security\Simplified\NetworkEvidence;
use App\Domain\Election\Security\Simplified\DeviceEvidence;
use App\Domain\Election\Security\Simplified\SessionContinuity;
use App\Domain\Election\Security\Simplified\ElectionConstitutionHasher;
use PHPUnit\Framework\TestCase;

class DeviceBindingPolicyTest extends TestCase
{
    private DeviceBindingPolicy $policy;

    protected function setUp(): void
    {
        $this->policy = new DeviceBindingPolicy();
    }

    public function test_passes_when_strategy_none(): void
    {
        $evidence = $this->createSnapshot(
            constitution: new ElectionConstitutionSnapshot('none', 6, 'none', 'single_code', false),
        );

        $finding = $this->policy->evaluate($evidence);

        $this->assertTrue($finding->passed);
        $this->assertSame('device_binding_policy', $finding->policyIdentifier);
    }

    public function test_fingerprint_required_and_exact_match(): void
    {
        $evidence = $this->createSnapshot(
            constitution: new ElectionConstitutionSnapshot('none', 6, 'fingerprint_required', 'single_code', false),
            device: new DeviceEvidence('abc123', 'exact_match', 'browser_api', 'stable'),
        );

        $finding = $this->policy->evaluate($evidence);

        $this->assertTrue($finding->passed);
    }

    public function test_fingerprint_required_and_no_match(): void
    {
        $evidence = $this->createSnapshot(
            constitution: new ElectionConstitutionSnapshot('none', 6, 'fingerprint_required', 'single_code', false),
            device: new DeviceEvidence('abc123', 'no_match', 'browser_api', 'volatile'),
        );

        $finding = $this->policy->evaluate($evidence);

        $this->assertFalse($finding->passed);
        $this->assertStringContainsString('Article 5', $finding->constitutionalBasis);
    }

    public function test_not_required_null_fp_passes(): void
    {
        $evidence = $this->createSnapshot(
            constitution: new ElectionConstitutionSnapshot('none', 6, 'none', 'single_code', false),
            device: new DeviceEvidence(null, 'not_required', 'none', 'stable'),
        );

        $finding = $this->policy->evaluate($evidence);

        $this->assertTrue($finding->passed);
    }

    public function test_returns_policy_finding_not_authority(): void
    {
        $evidence = $this->createSnapshot(
            constitution: new ElectionConstitutionSnapshot('none', 6, 'fingerprint_required', 'single_code', false),
            device: new DeviceEvidence('abc123', 'exact_match', 'browser_api', 'stable'),
        );

        $finding = $this->policy->evaluate($evidence);

        $this->assertInstanceOf(PolicyFinding::class, $finding);
        $this->assertObjectNotHasProperty('allow', $finding);
        $this->assertObjectNotHasProperty('deny', $finding);
        $this->assertObjectNotHasProperty('trustLevel', $finding);
        $this->assertObjectNotHasProperty('authorized', $finding);
    }

    public function test_supporting_facts_contain_device_details(): void
    {
        $evidence = $this->createSnapshot(
            constitution: new ElectionConstitutionSnapshot('none', 6, 'fingerprint_required', 'single_code', false),
            device: new DeviceEvidence('abc123', 'exact_match', 'browser_api', 'stable'),
        );

        $finding = $this->policy->evaluate($evidence);

        $this->assertArrayHasKey('strategy', $finding->supportingFacts);
        $this->assertArrayHasKey('match_type', $finding->supportingFacts);
        $this->assertArrayHasKey('fingerprint_present', $finding->supportingFacts);
        $this->assertSame('fingerprint_required', $finding->supportingFacts['strategy']);
        $this->assertSame('exact_match', $finding->supportingFacts['match_type']);
        $this->assertTrue($finding->supportingFacts['fingerprint_present']);
    }

    private function createSnapshot(
        ElectionConstitutionSnapshot $constitution,
        ?DeviceEvidence $device = null,
        ?VerificationEvidence $verification = null,
        ?NetworkEvidence $network = null,
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
