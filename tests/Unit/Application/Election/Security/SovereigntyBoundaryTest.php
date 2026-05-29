<?php

namespace Tests\Unit\Application\Election\Security;

use App\Application\Election\Capabilities\Policies\TrustCapabilityPolicy;
use App\Application\Election\Security\Overlays\DeviceAnomalyOverlay;
use App\Application\Election\Security\Overlays\EmergencyConditionOverlay;
use App\Application\Election\Security\Overlays\IpVelocityOverlay;
use App\Application\Election\Security\Overlays\RegistrarAttestationElevation;
use App\Application\Election\Security\Overlays\SuspiciousActivityOverlay;
use App\Application\Election\Security\Simplified\Policies\DeviceBindingPolicy;
use App\Application\Election\Security\Simplified\Policies\NetworkBindingPolicy;
use App\Application\Election\Security\Simplified\Policies\VerificationPolicy;
use PHPUnit\Framework\TestCase;

/**
 * Sovereignty Boundary Test
 *
 * Architectural fitness functions enforcing the Constitutional Vocabulary Doctrine.
 * These tests protect against sovereignty leakage across architectural layers.
 *
 * Constitutional Vocabulary Doctrine (enforced):
 * - Policies evaluate evidence only — never derive authority
 * - Overlays describe concerns — never decide outcomes
 * - Resolver interprets facts — sole authority derivation point
 * - Controllers orchestrate — never compute trust
 */
class SovereigntyBoundaryTest extends TestCase
{
    // =========================================================================
    // SIMPLIFIED POLICIES — evidence evaluation only
    // =========================================================================

    private array $simplifiedPolicies;
    private array $overlays;

    protected function setUp(): void
    {
        parent::setUp();

        $this->simplifiedPolicies = [
            VerificationPolicy::class,
            NetworkBindingPolicy::class,
            DeviceBindingPolicy::class,
        ];

        $this->overlays = [
            EmergencyConditionOverlay::class,
            RegistrarAttestationElevation::class,
            SuspiciousActivityOverlay::class,
            IpVelocityOverlay::class,
            DeviceAnomalyOverlay::class,
        ];
    }

    /**
     * INVARIANT: Policies must NOT have methods named allow() or deny().
     * Only the Resolver (TrustCapabilityPolicy) may convert evidence into capability decisions.
     */
    public function test_policies_never_allow_or_deny(): void
    {
        $allClasses = array_merge(
            $this->simplifiedPolicies,
            [TrustCapabilityPolicy::class],
        );

        foreach ($allClasses as $class) {
            $reflection = new \ReflectionClass($class);

            $this->assertFalse(
                $reflection->hasMethod('allow'),
                "{$class} has allow() method — only Resolver may allow participation"
            );
            $this->assertFalse(
                $reflection->hasMethod('deny'),
                "{$class} has deny() method — only Resolver may deny participation"
            );
        }
    }

    /**
     * INVARIANT: Overlay classes must NOT return any form of authority decision.
     * Overlays describe evidence state — they never grant or deny participation.
     */
    public function test_overlays_never_return_authority_types(): void
    {
        foreach ($this->overlays as $class) {
            $reflection = new \ReflectionClass($class);

            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->isStatic() || $method->getName() === '__construct') {
                    continue;
                }

                $returnType = $method->getReturnType();
                if (!$returnType instanceof \ReflectionNamedType) {
                    continue;
                }

                $returnTypeName = $returnType->getName();

                $this->assertStringNotContainsString(
                    'CapabilityDecision',
                    $returnTypeName,
                    "{$class}::{$method->getName()} returns CapabilityDecision — overlays must never derive authority"
                );
            }
        }
    }

    /**
     * INVARIANT: Simplified policies must NOT import or reference CapabilityDecision.
     * Policies evaluate evidence, not authority. They belong in the evaluation pipeline,
     * not the resolver pipeline.
     */
    public function test_simplified_policies_never_reference_capability(): void
    {
        foreach ($this->simplifiedPolicies as $class) {
            $reflection = new \ReflectionClass($class);
            $filename = $reflection->getFileName();
            $contents = file_get_contents($filename);

            $this->assertStringNotContainsString(
                'CapabilityDecision',
                $contents,
                "{$class} references CapabilityDecision — simplified policies must not touch authority layer"
            );
            $this->assertStringNotContainsString(
                'CapabilityDenialReason',
                $contents,
                "{$class} references CapabilityDenialReason — simplified policies must not touch authority layer"
            );
        }
    }

    /**
     * INVARIANT: Simplified policies must NOT evaluate State directly.
     * Trust evaluation state transitions belong in the PolicySequence or Resolver,
     * not in individual policies.
     */
    public function test_simplified_policies_never_reference_evaluation_state(): void
    {
        foreach ($this->simplifiedPolicies as $class) {
            $reflection = new \ReflectionClass($class);
            $filename = $reflection->getFileName();
            $contents = file_get_contents($filename);

            $this->assertStringNotContainsString(
                'TrustEvaluationState',
                $contents,
                "{$class} references TrustEvaluationState — policies return PolicyFinding, not evaluation state"
            );
        }
    }

    /**
     * INVARIANT: Simplified overlays (in OverlaySignal) must NOT evaluate State.
     * OverlaySignal is a descriptive data object — it carries signal type, not evaluation state.
     */
    public function test_overlay_signal_never_references_evaluation_state(): void
    {
        $reflection = new \ReflectionClass(\App\Domain\Election\Security\Simplified\OverlaySignal::class);
        $filename = $reflection->getFileName();
        $contents = file_get_contents($filename);

        $this->assertStringNotContainsString(
            'TrustEvaluationState',
            $contents,
            'Simplified OverlaySignal must not reference TrustEvaluationState'
        );
    }

    /**
     * INVARIANT: PolicyFinding must NOT contain TrustLevel.
     * PolicyFinding carries evaluation facts only. Trust level is derived by the Resolver.
     */
    public function test_policy_finding_has_no_trust_level(): void
    {
        $reflection = new \ReflectionClass(\App\Application\Election\Security\Simplified\PolicyFinding::class);

        // Check all properties for TrustLevel
        foreach ($reflection->getProperties() as $property) {
            $type = $property->getType();
            if ($type instanceof \ReflectionNamedType) {
                $this->assertStringNotContainsString(
                    'TrustLevel',
                    $type->getName(),
                    'PolicyFinding has TrustLevel property — findings must not derive trust level'
                );
            }
        }
    }

    /**
     * INVARIANT: All simplified policies must have evaluation logic in evaluate() only.
     * No helper methods that could introduce hidden authority logic.
     */
    public function test_simplified_policies_have_minimal_public_api(): void
    {
        foreach ($this->simplifiedPolicies as $class) {
            $reflection = new \ReflectionClass($class);

            $publicMethods = array_filter(
                $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
                fn($m) => !$m->isStatic() && $m->getName() !== '__construct'
            );

            $this->assertCount(
                1,
                $publicMethods,
                "{$class} has " . count($publicMethods) . " public methods — only evaluate() is allowed"
            );
        }
    }

    /**
     * INVARIANT: Domain layer classes must NOT import from Application layer.
     * Domain is pure PHP — zero framework, zero Application dependencies.
     */
    public function test_domain_never_imports_application(): void
    {
        $domainClasses = [
            \App\Domain\Election\Security\Simplified\OverlaySignal::class,
            \App\Domain\Election\Security\Simplified\EvidenceEvaluationResult::class,
            \App\Domain\Election\Security\Simplified\EvidenceSnapshot::class,
            \App\Domain\Election\Security\Simplified\EvaluationEnvelope::class,
            \App\Domain\Election\Security\Simplified\ConstitutionalEvidenceSnapshot::class,
        ];

        foreach ($domainClasses as $class) {
            $reflection = new \ReflectionClass($class);
            $filename = $reflection->getFileName();
            $contents = file_get_contents($filename);

            $this->assertStringNotContainsString(
                'App\\Application\\',
                $contents,
                "{$class} imports from Application layer — domain must be pure PHP"
            );
        }
    }

    // =========================================================================
    // C9 — Architecture dependency fitness functions
    // =========================================================================

    /**
     * INVARIANT (C9): Security domain objects must NEVER import Illuminate classes.
     * The trust evaluation pipeline lives in Domain/Election/Security/Simplified/
     * and must remain pure PHP — zero Laravel framework dependencies.
     *
     * Scoped to the security evaluation domain. Pre-existing Illuminate usage
     * in other Domain files (Events, Models, StateMachine, Finance) predates
     * this architectural rule and will be migrated in future phases.
     */
    public function test_security_domain_never_imports_illuminate(): void
    {
        $securityDomain = dirname((new \ReflectionClass(\App\Domain\Election\Security\Simplified\OverlaySignal::class))->getFileName(), 1);
        // OverlaySignal is in Simplified/ — up 1 to get Security/Simplified/,
        // but we want the whole Security domain, so go up further
        $securityDomain = dirname($securityDomain, 1); // Security/

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($securityDomain, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        $violations = [];
        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }
            $contents = file_get_contents($file->getPathname());
            if (preg_match('/^use\s+Illuminate\\\\(.*);/m', $contents, $matches)) {
                $violations[] = $file->getPathname() . ': use Illuminate\\' . $matches[1];
            }
        }

        $this->assertEmpty(
            $violations,
            "Security domain must not import Illuminate classes. Found:\n" . implode("\n", $violations)
        );
    }

    /**
     * INVARIANT (C9): Simplified evaluation policy files must NOT import
     * Eloquent models directly. These policies receive data via value objects
     * (ConstitutionalEvidenceSnapshot, PolicyFinding), not Eloquent models.
     *
     * Scoped to the Security/Simplified policies. Pre-existing Application
     * files (CapabilityContext, etc.) predate this architectural rule.
     */
    public function test_simplified_policies_never_import_eloquent(): void
    {
        $policiesDir = dirname(__DIR__, 5) . '/app/Application/Election/Security/Simplified/Policies';

        if (!is_dir($policiesDir)) {
            $this->markTestSkipped('Simplified policies directory does not exist: ' . $policiesDir);
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($policiesDir, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        $violations = [];
        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }
            $contents = file_get_contents($file->getPathname());
            if (preg_match('/^use\s+App\\\\Models\\\\(.*);/m', $contents, $matches)) {
                $violations[] = $file->getPathname() . ': use App\Models\\' . $matches[1];
            }
        }

        $this->assertEmpty(
            $violations,
            "Simplified policies must not import Eloquent models. Found:\n" . implode("\n", $violations)
        );
    }
}
