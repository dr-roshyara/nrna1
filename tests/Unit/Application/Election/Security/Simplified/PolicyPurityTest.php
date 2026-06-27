<?php

namespace Tests\Unit\Application\Election\Security\Simplified;

use App\Application\Election\Security\Simplified\ConstitutionalPolicy;
use App\Application\Election\Security\Simplified\Policies\DeviceBindingPolicy;
use App\Application\Election\Security\Simplified\Policies\NetworkBindingPolicy;
use App\Application\Election\Security\Simplified\Policies\VerificationPolicy;
use PHPUnit\Framework\TestCase;

/**
 * Policy Purity Test
 *
 * Architectural fitness functions that enforce Constitutional Vocabulary Doctrine
 * for all Simplified policy implementations.
 *
 * Violations indicate framework contamination or authority leakage into the evaluation layer.
 */
class PolicyPurityTest extends TestCase
{
    private array $policyClasses;

    protected function setUp(): void
    {
        parent::setUp();

        $this->policyClasses = [
            VerificationPolicy::class,
            NetworkBindingPolicy::class,
            DeviceBindingPolicy::class,
        ];
    }

    /**
     * INVARIANT: Simplified policies must NOT import Illuminate/Eloquent.
     * Policies are Application-layer evaluation logic — framework contamination breaks replay determinism.
     */
    public function test_policies_do_not_import_eloquent(): void
    {
        foreach ($this->policyClasses as $class) {
            $reflection = new \ReflectionClass($class);
            $filename = $reflection->getFileName();
            $contents = file_get_contents($filename);

            $this->assertDoesNotMatchRegularExpression(
                '/Illuminate\\\\/',
                $contents,
                "{$class} imports Illuminate — framework contamination detected in evaluation layer"
            );
        }
    }

    /**
     * INVARIANT: Simplified policies must NOT use authority vocabulary.
     * Authority vocabulary (allow, deny, grant, authorize, permitted) belongs in the Resolver only.
     */
    public function test_policies_do_not_use_authority_methods(): void
    {
        $authorityVerbs = ['allow', 'deny', 'grant', 'authorize', 'permit'];

        foreach ($this->policyClasses as $class) {
            $reflection = new \ReflectionClass($class);

            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->isStatic() || $method->getName() === '__construct') {
                    continue;
                }

                $methodName = $method->getName();

                foreach ($authorityVerbs as $verb) {
                    $this->assertDoesNotMatchRegularExpression(
                        "/^{$verb}/i",
                        $methodName,
                        "{$class}::{$methodName}() uses authority verb '{$verb}' — only Resolver may grant authority"
                    );
                }
            }
        }
    }

    /**
     * INVARIANT: Each policy must have a unique policyIdentifier.
     * Duplicate identifiers break causality chain reconstruction.
     */
    public function test_policies_have_unique_identifiers(): void
    {
        $identifiers = [];
        $usedBy = [];

        foreach ($this->policyClasses as $class) {
            $reflection = new \ReflectionClass($class);

            $this->assertTrue(
                $reflection->implementsInterface(ConstitutionalPolicy::class),
                "{$class} must implement ConstitutionalPolicy interface"
            );

            // Read the policyIdentifier from PolicyFinding returned by evaluate()
            $filename = $reflection->getFileName();
            $contents = file_get_contents($filename);

            // Extract hardcoded policyIdentifier strings
            preg_match_all('/policyIdentifier\s*:\s*\'([^\']+)\'/', $contents, $matches);

            // Get unique identifiers for this class (same identifier may appear in multiple branches)
            $classIdentifiers = array_unique($matches[1]);

            foreach ($classIdentifiers as $identifier) {
                if (isset($usedBy[$identifier]) && $usedBy[$identifier] !== $class) {
                    $this->fail(
                        "Duplicate policy identifier '{$identifier}' — used by both {$usedBy[$identifier]} and {$class}"
                    );
                }
                $usedBy[$identifier] = $class;
                $identifiers[] = $identifier;
            }
        }

        $this->assertGreaterThanOrEqual(
            count($this->policyClasses),
            count(array_unique($identifiers)),
            'Each policy should define at least one unique policyIdentifier'
        );
    }

    /**
     * INVARIANT: Simplified policies must implement ConstitutionalPolicy interface.
     * This ensures all policies follow the same evaluation contract.
     */
    public function test_all_policies_implement_constitutional_policy(): void
    {
        foreach ($this->policyClasses as $class) {
            $reflection = new \ReflectionClass($class);

            $this->assertTrue(
                $reflection->implementsInterface(ConstitutionalPolicy::class),
                "{$class} must implement ConstitutionalPolicy interface"
            );

            $this->assertTrue(
                $reflection->hasMethod('evaluate'),
                "{$class} must implement evaluate() method from ConstitutionalPolicy"
            );
        }
    }

    /**
     * INVARIANT: Simplified policies must NOT return boolean or EvidenceClassification.
     * Policies return PolicyFinding — evidence facts only.
     * Boolean returns would create false authority semantics.
     */
    public function test_policies_do_not_return_boolean_or_trust_level(): void
    {
        foreach ($this->policyClasses as $class) {
            $reflection = new \ReflectionClass($class);

            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->getName() !== 'evaluate') {
                    continue;
                }

                $returnType = $method->getReturnType();
                if ($returnType instanceof \ReflectionNamedType) {
                    $this->assertNotEquals(
                        'bool',
                        $returnType->getName(),
                        "{$class}::evaluate() returns bool — policies must not grant authority"
                    );

                    $this->assertStringNotContainsString(
                        'EvidenceClassification',
                        $returnType->getName(),
                        "{$class}::evaluate() returns EvidenceClassification — policies must not classify evidence"
                    );
                }
            }
        }
    }

    /**
     * INVARIANT: Policy file names must match the class name (PSR-4 compliance).
     */
    public function test_policy_file_name_matches_class(): void
    {
        foreach ($this->policyClasses as $class) {
            $reflection = new \ReflectionClass($class);
            $filename = $reflection->getFileName();
            $basename = basename($filename, '.php');

            $shortName = $reflection->getShortName();
            $this->assertEquals(
                $shortName,
                $basename,
                "File name '{$basename}.php' must match class name '{$shortName}'"
            );
        }
    }
}
