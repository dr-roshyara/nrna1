<?php

namespace Tests\Unit\Domain\Election\Security\Simplified;

use App\Domain\Election\Security\Simplified\ParticipationEligibilityEvidence;
use PHPUnit\Framework\TestCase;

/**
 * ParticipationEligibilityEvidenceTest
 *
 * Verifies the eligibility evidence value object is:
 * - Read-only frozen evidence (not authority)
 * - Observational only (no methods, no derivation)
 * - Contains all fields needed for replay-addressable eligibility
 */
class ParticipationEligibilityEvidenceTest extends TestCase
{
    public function test_is_readonly(): void
    {
        $reflection = new \ReflectionClass(ParticipationEligibilityEvidence::class);
        $this->assertTrue($reflection->isReadOnly());
    }

    public function test_all_properties_are_public_readonly(): void
    {
        $evidence = new ParticipationEligibilityEvidence(
            hasActiveMembership: true,
            hasValidAssignment: true,
            hasApproval: true,
            isSuspended: false,
            eligibilityEvaluatedAt: new \DateTimeImmutable('2026-05-27 12:00:00'),
            eligibilitySourceVersion: '1.0',
            eligibilityHash: 'abc123def456',
        );

        $this->assertTrue($evidence->hasActiveMembership);
        $this->assertTrue($evidence->hasValidAssignment);
        $this->assertTrue($evidence->hasApproval);
        $this->assertFalse($evidence->isSuspended);
        $this->assertEquals('2026-05-27 12:00:00', $evidence->eligibilityEvaluatedAt->format('Y-m-d H:i:s'));
        $this->assertEquals('1.0', $evidence->eligibilitySourceVersion);
        $this->assertEquals('abc123def456', $evidence->eligibilityHash);
    }

    public function test_suspended_voter_still_captures_evidence(): void
    {
        // Even a suspended voter has eligibility evidence — it's observational, not a denial
        $evidence = new ParticipationEligibilityEvidence(
            hasActiveMembership: true,
            hasValidAssignment: true,
            hasApproval: false,
            isSuspended: true,
            eligibilityEvaluatedAt: new \DateTimeImmutable(),
            eligibilitySourceVersion: '1.0',
            eligibilityHash: 'suspended-hash',
        );

        $this->assertTrue($evidence->isSuspended);
        $this->assertFalse($evidence->hasApproval);
    }

    public function test_has_no_behavioral_methods(): void
    {
        $reflection = new \ReflectionClass(ParticipationEligibilityEvidence::class);
        $methods = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            fn($m) => $m->getName() !== '__construct'
        );
        $this->assertCount(0, $methods, 'Eligibility evidence must be pure data — no behavioral methods');
    }

    public function test_has_no_authority_properties(): void
    {
        $evidence = new ParticipationEligibilityEvidence(
            hasActiveMembership: true,
            hasValidAssignment: true,
            hasApproval: true,
            isSuspended: false,
            eligibilityEvaluatedAt: new \DateTimeImmutable(),
            eligibilitySourceVersion: '1.0',
            eligibilityHash: 'auth-check-hash',
        );

        // Must NOT have authority-like properties
        $this->assertObjectNotHasProperty('allowed', $evidence);
        $this->assertObjectNotHasProperty('denied', $evidence);
        $this->assertObjectNotHasProperty('granted', $evidence);
        $this->assertObjectNotHasProperty('authorized', $evidence);
        $this->assertObjectNotHasProperty('permitted', $evidence);
    }
}
