<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Semantics;

use ReflectionClass;
use PHPUnit\Framework\TestCase;

class ArchitectureFitnessTest extends TestCase
{
    /**
     * @test
     * All semantic layer classes are final
     */
    public function test_all_semantic_classes_are_final(): void
    {
        $semanticClasses = [
            'App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCategory',
            'App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCode',
            'App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticDefinition',
            'App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticRegistry',
            'App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\ImmutableSemanticEvolutionPolicy',
            'App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCollection',
        ];

        foreach ($semanticClasses as $className) {
            $reflection = new ReflectionClass($className);
            $this->assertTrue(
                $reflection->isFinal(),
                "Class {$className} must be final"
            );
        }
    }

    /**
     * @test
     * All semantic exceptions are final
     */
    public function test_all_semantic_exceptions_are_final(): void
    {
        $exceptionClasses = [
            'App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\Exception\DuplicateSemanticCodeException',
            'App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\Exception\SemanticEvolutionViolationException',
        ];

        foreach ($exceptionClasses as $className) {
            $reflection = new ReflectionClass($className);
            $this->assertTrue(
                $reflection->isFinal(),
                "Exception {$className} must be final"
            );
        }
    }

    /**
     * @test
     * Semantic layer has no Laravel imports
     */
    public function test_semantic_layer_has_no_laravel_imports(): void
    {
        $semanticClasses = [
            'App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCategory',
            'App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCode',
            'App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticDefinition',
            'App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticRegistry',
            'App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\ImmutableSemanticEvolutionPolicy',
            'App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCollection',
        ];

        foreach ($semanticClasses as $className) {
            $reflection = new ReflectionClass($className);
            $filename = $reflection->getFileName();
            $content = file_get_contents($filename);

            // Check for Laravel namespace imports
            $this->assertStringNotContainsString(
                'use Illuminate\\',
                $content,
                "Class {$className} contains Laravel imports"
            );

            $this->assertStringNotContainsString(
                'use Laravel\\',
                $content,
                "Class {$className} contains Laravel imports"
            );
        }
    }

    /**
     * @test
     * GovernanceSemanticCode is immutable (no setter methods)
     */
    public function test_semantic_code_has_no_setters(): void
    {
        $reflection = new ReflectionClass(
            'App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCode'
        );

        foreach ($reflection->getMethods() as $method) {
            $this->assertFalse(
                strpos($method->getName(), 'set') === 0,
                "GovernanceSemanticCode must not have setter methods"
            );
        }
    }

    /**
     * @test
     * GovernanceSemanticDefinition is immutable (no setter methods)
     */
    public function test_semantic_definition_has_no_setters(): void
    {
        $reflection = new ReflectionClass(
            'App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticDefinition'
        );

        foreach ($reflection->getMethods() as $method) {
            $this->assertFalse(
                strpos($method->getName(), 'set') === 0,
                "GovernanceSemanticDefinition must not have setter methods"
            );
        }
    }

    /**
     * @test
     * Semantic registry deterministic ordering is enforced
     */
    public function test_semantic_registry_enforces_deterministic_ordering(): void
    {
        $registry1 = new \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticRegistry();
        $registry2 = new \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticRegistry();

        // Register in different orders
        $codes = ['zebra', 'alpha', 'middle', 'beta'];
        foreach ($codes as $code) {
            $def1 = new \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticDefinition(
                code: \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCode::fromString($code),
                category: \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCategory::AUTHORITY,
                canonicalMeaning: "Meaning for $code",
                replayImplications: 'Immutable',
                legitimacyImplications: 'Valid',
                introducedInDoctrineVersion: '1.0',
            );

            $def2 = new \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticDefinition(
                code: \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCode::fromString($code),
                category: \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCategory::AUTHORITY,
                canonicalMeaning: "Meaning for $code",
                replayImplications: 'Immutable',
                legitimacyImplications: 'Valid',
                introducedInDoctrineVersion: '1.0',
            );

            $registry1->register($def1);
            $registry2->register($def2);
        }

        // Serializations must be identical despite different insertion order
        $hash1 = hash('sha256', $registry1->canonicalSerialization());
        $hash2 = hash('sha256', $registry2->canonicalSerialization());

        $this->assertSame($hash1, $hash2);
    }

    /**
     * @test
     * Semantic collection prevents duplicate codes at domain boundary
     */
    public function test_semantic_collection_enforces_uniqueness(): void
    {
        $collection = new \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCollection();

        $def1 = new \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticDefinition(
            code: \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCode::fromString('unique_test'),
            category: \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Test',
            replayImplications: 'Test',
            legitimacyImplications: 'Test',
            introducedInDoctrineVersion: '1.0',
        );

        $def2 = new \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticDefinition(
            code: \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCode::fromString('unique_test'),
            category: \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Test',
            replayImplications: 'Test',
            legitimacyImplications: 'Test',
            introducedInDoctrineVersion: '1.0',
        );

        $collection = $collection->add($def1);

        $this->expectException(\InvalidArgumentException::class);
        $collection->add($def2);
    }

    /**
     * @test
     * Semantic evolution policy prevents retroactive changes
     */
    public function test_semantic_evolution_policy_prevents_mutations(): void
    {
        $policy = new \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\ImmutableSemanticEvolutionPolicy();

        $original = new \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticDefinition(
            code: \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCode::fromString('immutable_test'),
            category: \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Original meaning',
            replayImplications: 'Original replay',
            legitimacyImplications: 'Original legitimacy',
            introducedInDoctrineVersion: '1.0',
        );

        $modified = new \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticDefinition(
            code: \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCode::fromString('immutable_test'),
            category: \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'DIFFERENT meaning',
            replayImplications: 'Original replay',
            legitimacyImplications: 'Original legitimacy',
            introducedInDoctrineVersion: '1.0',
        );

        $this->expectException(
            'App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\Exception\SemanticEvolutionViolationException'
        );

        $policy->validateEvolution($original, $modified);
    }

    /**
     * @test
     * GovernanceSemanticDefinition properties are effectively immutable
     */
    public function test_semantic_definition_properties_are_immutable(): void
    {
        $code = \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCode::fromString('test');
        $definition = new \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticDefinition(
            code: $code,
            category: \App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Original',
            replayImplications: 'Original',
            legitimacyImplications: 'Original',
            introducedInDoctrineVersion: '1.0',
        );

        // Verify properties match constructor values (no mutation possible)
        $this->assertSame('Original', $definition->canonicalMeaning);
        $this->assertSame('Original', $definition->replayImplications);
        $this->assertSame('Original', $definition->legitimacyImplications);
    }
}
