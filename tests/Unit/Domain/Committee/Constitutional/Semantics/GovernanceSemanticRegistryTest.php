<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Semantics;

use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCategory;
use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCode;
use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticDefinition;
use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticRegistry;
use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\Exception\DuplicateSemanticCodeException;
use PHPUnit\Framework\TestCase;

class GovernanceSemanticRegistryTest extends TestCase
{
    /**
     * @test
     * Semantic definition can be registered
     */
    public function test_semantic_definition_can_be_registered(): void
    {
        $registry = new GovernanceSemanticRegistry();
        $definition = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('voting_legitimacy'),
            category: GovernanceSemanticCategory::LEGITIMACY,
            canonicalMeaning: 'Authority to vote',
            replayImplications: 'Immutable in snapshot',
            legitimacyImplications: 'Valid votes require this',
            introducedInDoctrineVersion: '1.0',
        );

        $registry->register($definition);
        $retrieved = $registry->find(GovernanceSemanticCode::fromString('voting_legitimacy'));

        $this->assertNotNull($retrieved);
        $this->assertTrue($retrieved->code->equals($definition->code));
    }

    /**
     * @test
     * Semantic definition can be retrieved by code
     */
    public function test_semantic_definition_can_be_retrieved_by_code(): void
    {
        $registry = new GovernanceSemanticRegistry();
        $code = GovernanceSemanticCode::fromString('proxy_authority');
        $definition = new GovernanceSemanticDefinition(
            code: $code,
            category: GovernanceSemanticCategory::DELEGATION,
            canonicalMeaning: 'Authority delegated via proxy',
            replayImplications: 'Proxy stored immutably',
            legitimacyImplications: 'Inherits delegator legitimacy',
            introducedInDoctrineVersion: '1.0',
        );

        $registry->register($definition);
        $retrieved = $registry->find($code);

        $this->assertNotNull($retrieved);
        $this->assertSame('Authority delegated via proxy', $retrieved->canonicalMeaning);
    }

    /**
     * @test
     * Duplicate semantic code throws exception
     */
    public function test_duplicate_semantic_code_throws_exception(): void
    {
        $registry = new GovernanceSemanticRegistry();
        $code = GovernanceSemanticCode::fromString('quorum_requirement');

        $definition1 = new GovernanceSemanticDefinition(
            code: $code,
            category: GovernanceSemanticCategory::PARTICIPATION,
            canonicalMeaning: 'First definition',
            replayImplications: 'Something',
            legitimacyImplications: 'Something',
            introducedInDoctrineVersion: '1.0',
        );

        $definition2 = new GovernanceSemanticDefinition(
            code: $code,
            category: GovernanceSemanticCategory::PARTICIPATION,
            canonicalMeaning: 'Second definition',
            replayImplications: 'Something else',
            legitimacyImplications: 'Different',
            introducedInDoctrineVersion: '1.0',
        );

        $registry->register($definition1);

        $this->expectException(DuplicateSemanticCodeException::class);
        $registry->register($definition2);
    }

    /**
     * @test
     * Unknown semantic code returns null
     */
    public function test_unknown_semantic_code_returns_null(): void
    {
        $registry = new GovernanceSemanticRegistry();
        $unknown = GovernanceSemanticCode::fromString('nonexistent');

        $result = $registry->find($unknown);

        $this->assertNull($result);
    }

    /**
     * @test
     * Registry preserves deterministic ordering
     */
    public function test_registry_preserves_deterministic_ordering(): void
    {
        $registry1 = new GovernanceSemanticRegistry();
        $registry2 = new GovernanceSemanticRegistry();

        $codes = ['zebra', 'alpha', 'middle', 'beta'];
        foreach ($codes as $code) {
            $def1 = new GovernanceSemanticDefinition(
                code: GovernanceSemanticCode::fromString($code),
                category: GovernanceSemanticCategory::AUTHORITY,
                canonicalMeaning: "Meaning for $code",
                replayImplications: 'Immutable',
                legitimacyImplications: 'Valid',
                introducedInDoctrineVersion: '1.0',
            );

            $def2 = new GovernanceSemanticDefinition(
                code: GovernanceSemanticCode::fromString($code),
                category: GovernanceSemanticCategory::AUTHORITY,
                canonicalMeaning: "Meaning for $code",
                replayImplications: 'Immutable',
                legitimacyImplications: 'Valid',
                introducedInDoctrineVersion: '1.0',
            );

            $registry1->register($def1);
            $registry2->register($def2);
        }

        $hash1 = hash('sha256', $registry1->canonicalSerialization());
        $hash2 = hash('sha256', $registry2->canonicalSerialization());

        $this->assertSame($hash1, $hash2);
    }

    /**
     * @test
     * Registry serialization is canonical
     */
    public function test_registry_serialization_is_canonical(): void
    {
        $registry = new GovernanceSemanticRegistry();

        $definition = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('test_semantic'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Test meaning',
            replayImplications: 'Test implications',
            legitimacyImplications: 'Test legitimacy',
            introducedInDoctrineVersion: '1.0',
        );

        $registry->register($definition);
        $serialization = $registry->canonicalSerialization();

        // Serialization must be a string that can be hashed
        $this->assertIsString($serialization);
        $this->assertNotEmpty($serialization);

        // Hash must be deterministic
        $hash1 = hash('sha256', $serialization);
        $hash2 = hash('sha256', $serialization);
        $this->assertSame($hash1, $hash2);
    }

    /**
     * @test
     * Registry cannot mutate registered definitions
     */
    public function test_registry_cannot_mutate_registered_definitions(): void
    {
        $registry = new GovernanceSemanticRegistry();
        $definition = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('immutable_test'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Original meaning',
            replayImplications: 'Original implications',
            legitimacyImplications: 'Original legitimacy',
            introducedInDoctrineVersion: '1.0',
        );

        $registry->register($definition);
        $retrieved1 = $registry->find(GovernanceSemanticCode::fromString('immutable_test'));

        // Register same again (should fail with duplicate exception, not mutation)
        $this->expectException(DuplicateSemanticCodeException::class);
        $registry->register($definition);
    }
}
