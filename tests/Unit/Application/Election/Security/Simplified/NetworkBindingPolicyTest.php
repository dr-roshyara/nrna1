<?php

namespace Tests\Unit\Application\Election\Security\Simplified;

use App\Application\Election\Security\Simplified\Policies\NetworkBindingPolicy;
use App\Application\Election\Security\Simplified\PolicyFinding;
use App\Domain\Election\Security\Simplified\ConstitutionalEvidenceSnapshot;
use App\Domain\Election\Security\Simplified\ElectionConstitutionSnapshot;
use App\Domain\Election\Security\Simplified\VerificationEvidence;
use App\Domain\Election\Security\Simplified\NetworkEvidence;
use App\Domain\Election\Security\Simplified\DeviceEvidence;
use App\Domain\Election\Security\Simplified\SessionContinuity;
use App\Domain\Election\Security\Simplified\ElectionConstitutionHasher;
use PHPUnit\Framework\TestCase;

class NetworkBindingPolicyTest extends TestCase
{
    private NetworkBindingPolicy $policy;

    protected function setUp(): void
    {
        $this->policy = new NetworkBindingPolicy();
    }

    public function test_passes_when_strategy_none(): void
    {
        $evidence = $this->createSnapshot(
            constitution: new ElectionConstitutionSnapshot('none', 6, 'none', 'single_code', false),
        );

        $finding = $this->policy->evaluate($evidence);

        $this->assertTrue($finding->passed);
        $this->assertSame('network_binding_policy', $finding->policyIdentifier);
    }

    public function test_ip_count_within_base_limit(): void
    {
        $evidence = $this->createSnapshot(
            constitution: new ElectionConstitutionSnapshot('ip_count', 6, 'none', 'single_code', false),
            network: new NetworkEvidence('abc123', 6, 3, true, 'ip_count'),
        );

        $finding = $this->policy->evaluate($evidence);

        $this->assertTrue($finding->passed);
    }

    public function test_ip_count_exceeds_base_limit(): void
    {
        $evidence = $this->createSnapshot(
            constitution: new ElectionConstitutionSnapshot('ip_count', 6, 'none', 'single_code', false),
            network: new NetworkEvidence('abc123', 6, 7, true, 'ip_count'),
        );

        $finding = $this->policy->evaluate($evidence);

        $this->assertFalse($finding->passed);
        $this->assertStringContainsString('Article 1', $finding->constitutionalBasis);
    }

    public function test_ip_strict_current_ip_matches(): void
    {
        $evidence = $this->createSnapshot(
            constitution: new ElectionConstitutionSnapshot('ip_strict', 6, 'none', 'single_code', false),
            network: new NetworkEvidence('abc123', 6, 0, true, 'ip_strict'),
            continuity: new SessionContinuity('s1', 'abc123', 'abc123', false, 'continuous'),
        );

        $finding = $this->policy->evaluate($evidence);

        $this->assertTrue($finding->passed);
    }

    public function test_ip_strict_current_ip_mismatch(): void
    {
        $evidence = $this->createSnapshot(
            constitution: new ElectionConstitutionSnapshot('ip_strict', 6, 'none', 'single_code', false),
            network: new NetworkEvidence('xyz789', 6, 0, true, 'ip_strict'),
            continuity: new SessionContinuity('s1', 'abc123', 'xyz789', true, 'interrupted'),
        );

        $finding = $this->policy->evaluate($evidence);

        $this->assertFalse($finding->passed);
        $this->assertStringContainsString('Article 4', $finding->constitutionalBasis);
    }

    public function test_returns_policy_finding_not_authority(): void
    {
        $evidence = $this->createSnapshot(
            constitution: new ElectionConstitutionSnapshot('ip_count', 6, 'none', 'single_code', false),
        );

        $finding = $this->policy->evaluate($evidence);

        $this->assertInstanceOf(PolicyFinding::class, $finding);

        // Verify no authority vocabulary
        $this->assertObjectNotHasProperty('allow', $finding);
        $this->assertObjectNotHasProperty('deny', $finding);
        $this->assertObjectNotHasProperty('trustLevel', $finding);
        $this->assertObjectNotHasProperty('authorized', $finding);
    }

    public function test_no_threshold_math_in_policy(): void
    {
        // NetworkBindingPolicy should NOT compute trust-level-adjusted thresholds
        // That is NetworkThresholdInterpreter's responsibility
        $evidence = $this->createSnapshot(
            constitution: new ElectionConstitutionSnapshot('ip_count', 6, 'none', 'single_code', false),
            network: new NetworkEvidence('abc123', 6, 4, true, 'ip_count'),
        );

        $finding = $this->policy->evaluate($evidence);

        // Policy only reports whether base limit is exceeded
        $this->assertTrue($finding->passed);
        $this->assertArrayHasKey('strategy', $finding->supportingFacts);
        $this->assertArrayHasKey('votes_from_this_ip', $finding->supportingFacts);
        $this->assertArrayHasKey('base_max_allowed', $finding->supportingFacts);
        $this->assertSame('ip_count', $finding->supportingFacts['strategy']);
        $this->assertSame(4, $finding->supportingFacts['votes_from_this_ip']);
        $this->assertSame(6, $finding->supportingFacts['base_max_allowed']);

        // Policy must NOT contain any trust-level-adjusted value
        $this->assertArrayNotHasKey('threshold_adjusted', $finding->supportingFacts);
        $this->assertArrayNotHasKey('trust_level', $finding->supportingFacts);
    }

    private function createSnapshot(
        ElectionConstitutionSnapshot $constitution,
        ?NetworkEvidence $network = null,
        ?VerificationEvidence $verification = null,
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
