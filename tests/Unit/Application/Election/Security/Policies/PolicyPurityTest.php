<?php

namespace Tests\Unit\Application\Election\Security\Policies;

use App\Application\Election\Security\Policies\DeviceBindingPolicy;
use App\Application\Election\Security\Policies\NetworkBindingPolicy;
use App\Application\Election\Security\Policies\VerificationAttestationPolicy;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\ConstitutionalConcernLevel;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustValidityScope;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * PolicyPurityTest (D.R.3)
 *
 * Proves policies are PURE constitutional fact reporters.
 * Policies emit PolicyFinding (descriptive evidence only).
 * Policies NEVER emit authority decisions.
 */
class PolicyPurityTest extends TestCase
{
    private function makeContext(): TrustCapabilityContext
    {
        return new TrustCapabilityContext(
            election: null,
            user: null,
            network: new NetworkTrustEvidence(
                currentIpHash: 'hash', registeredIpHash: 'hash', whitelist: null,
                maxVotesPerIp: 6, votesFromThisIp: 1, restrictionEnabled: true, bindingStrategy: 'ip_count',
            ),
            device: new DeviceTrustContext(
                fingerprintHash: 'fp', registeredFingerprintHash: 'fp',
                matchType: FingerprintMatchType::ExactMatch, captureMethod: 'canvas', volatility: 'stable',
            ),
            attestation: new VerificationAttestationRecord(
                required: false, attested: true, registrarId: null, attestationTimestamp: new \DateTimeImmutable(),
                protocol: 'none', networkEvidenceHash: null, deviceEvidenceHash: null,
                revoked: false, validityScope: TrustValidityScope::ElectionScoped,
            ),
            sessionContinuity: new VotingSessionTrustContinuity(
                sessionId: 's1', ipHashAtStart: 'hash', ipHashCurrent: 'hash',
                deviceChanged: false, continuityState: 'continuous',
            ),
        );
    }

    public function test_verification_policy_returns_constitutional_finding(): void
    {
        $policy = new VerificationAttestationPolicy();
        $result = $policy->evaluate($this->makeContext());

        // Assert: Returns PolicyFinding (not VotingTrustResult or authority decision)
        $this->assertInstanceOf(\App\Application\Election\Security\PolicyFinding::class, $result);

        // Assert: Result contains ONLY descriptive properties
        $this->assertIsString($result->concernLevel->value);  // concernLevel is enum (string-backed)
        $this->assertIsString($result->evidenceWeight->value);  // evidenceWeight is enum (string-backed)
        $this->assertIsString($result->constitutionalBasis);
        $this->assertIsArray($result->supportingFacts);
        $this->assertIsString($result->policyIdentifier);
    }

    public function test_verification_policy_no_authority_vocabulary(): void
    {
        $policy = new VerificationAttestationPolicy();
        $result = $policy->evaluate($this->makeContext());

        // Assert: No forbidden authority words anywhere in the finding
        $jsonResult = strtolower(json_encode($result));
        $forbiddenWords = ['deny', 'allow', 'grant', 'authorize', 'permit'];
        foreach ($forbiddenWords as $word) {
            $this->assertStringNotContainsString($word, $jsonResult, "Finding cannot contain authority vocabulary: $word");
        }
    }

    public function test_policies_implement_constitutional_policy_interface(): void
    {
        $policies = [
            new VerificationAttestationPolicy(),
            new NetworkBindingPolicy(),
            new DeviceBindingPolicy(),
        ];

        foreach ($policies as $policy) {
            $this->assertInstanceOf(\App\Application\Election\Security\ConstitutionalPolicy::class, $policy);
        }
    }

    public function test_policies_declare_identifier(): void
    {
        $policies = [
            new VerificationAttestationPolicy(),
            new NetworkBindingPolicy(),
            new DeviceBindingPolicy(),
        ];

        $identifiers = [];
        foreach ($policies as $policy) {
            $id = $policy->identifier();
            $this->assertIsString($id);
            $this->assertNotEmpty($id);
            $identifiers[] = $id;
        }

        // Identifiers should be unique
        $this->assertEquals(count($identifiers), count(array_unique($identifiers)), 'Policy identifiers must be unique');
    }

    public function test_policies_declare_dependencies(): void
    {
        $verificationPolicy = new VerificationAttestationPolicy();
        $networkPolicy = new NetworkBindingPolicy();
        $devicePolicy = new DeviceBindingPolicy();

        // Verification has no dependencies (foundational)
        $this->assertEmpty($verificationPolicy->dependencies());

        // Network depends on verification
        $this->assertContains('verification', $networkPolicy->dependencies());

        // Device depends on verification
        $this->assertContains('verification', $devicePolicy->dependencies());
    }

    public function test_network_policy_no_constructor_dependencies_on_resolver(): void
    {
        $policy = new NetworkBindingPolicy();
        $reflection = new ReflectionClass($policy);

        // Assert: No constructor parameters at all (no hidden state)
        $constructor = $reflection->getConstructor();
        $this->assertNull($constructor, "Policy should have no constructor parameters (context-only evaluation)");
    }

    public function test_device_policy_no_constructor_dependencies_on_resolver(): void
    {
        $policy = new DeviceBindingPolicy();
        $reflection = new ReflectionClass($policy);

        // Assert: No constructor parameters at all (no hidden state)
        $constructor = $reflection->getConstructor();
        $this->assertNull($constructor, "Policy should have no constructor parameters (context-only evaluation)");
    }

    public function test_verification_policy_no_constructor_dependencies_on_resolver(): void
    {
        $policy = new VerificationAttestationPolicy();
        $reflection = new ReflectionClass($policy);

        // Assert: No constructor parameters at all (no hidden state)
        $constructor = $reflection->getConstructor();
        $this->assertNull($constructor, "Policy should have no constructor parameters (context-only evaluation)");
    }

    public function test_network_policy_evaluation_is_deterministic(): void
    {
        $policy = new NetworkBindingPolicy();
        $ctx = $this->makeContext();

        // Evaluate twice with same context
        $result1 = $policy->evaluate($ctx);
        $result2 = $policy->evaluate($ctx);

        // Assert: Deterministic results
        $this->assertEquals(
            $result1->concernLevel->value,
            $result2->concernLevel->value,
            'Policy evaluation must be deterministic'
        );
        $this->assertEquals(
            $result1->constitutionalBasis,
            $result2->constitutionalBasis,
            'Policy basis must be deterministic'
        );
    }

    public function test_no_policy_returns_voting_trust_result(): void
    {
        $policies = [
            new VerificationAttestationPolicy(),
            new NetworkBindingPolicy(),
            new DeviceBindingPolicy(),
        ];

        foreach ($policies as $policy) {
            $result = $policy->evaluate($this->makeContext());

            // Assert: Returns PolicyFinding, NOT VotingTrustResult
            $this->assertInstanceOf(\App\Application\Election\Security\PolicyFinding::class, $result);
            $this->assertNotInstanceOf(\App\Domain\Election\Security\VotingTrustResult::class, $result);
        }
    }

    public function test_policy_findings_have_minimal_concern_levels(): void
    {
        // When no concern is found, finding should indicate no concern
        $verificationPolicy = new VerificationAttestationPolicy();
        $result = $verificationPolicy->evaluate($this->makeContext());

        // Satisfied attestation should have NO concern
        $this->assertEquals(ConstitutionalConcernLevel::NONE, $result->concernLevel);
    }
}
