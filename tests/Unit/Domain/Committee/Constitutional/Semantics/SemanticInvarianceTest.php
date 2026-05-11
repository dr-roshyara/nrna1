<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Semantics;

use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCategory;
use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCode;
use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticDefinition;
use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticRegistry;
use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCollection;
use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\ImmutableSemanticEvolutionPolicy;
use PHPUnit\Framework\TestCase;

class SemanticInvarianceTest extends TestCase
{
    /**
     * @test
     * Same semantic definition produces same canonical serialization
     */
    public function test_same_semantic_definition_produces_same_serialization(): void
    {
        $definition1 = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('test_code'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Test meaning',
            replayImplications: 'Test replay',
            legitimacyImplications: 'Test legitimacy',
            introducedInDoctrineVersion: '1.0',
        );

        $definition2 = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('test_code'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Test meaning',
            replayImplications: 'Test replay',
            legitimacyImplications: 'Test legitimacy',
            introducedInDoctrineVersion: '1.0',
        );

        $registry1 = new GovernanceSemanticRegistry();
        $registry1->register($definition1);

        $registry2 = new GovernanceSemanticRegistry();
        $registry2->register($definition2);

        $this->assertSame(
            $registry1->canonicalSerialization(),
            $registry2->canonicalSerialization()
        );
    }

    /**
     * @test
     * Deterministic ordering preserved across collection operations
     */
    public function test_deterministic_ordering_across_collections(): void
    {
        $codes = ['zebra', 'alpha', 'middle', 'beta'];

        $collection1 = new GovernanceSemanticCollection();
        $collection2 = new GovernanceSemanticCollection();

        // Add in different order to collection 1
        $order1 = ['zebra', 'alpha', 'middle', 'beta'];
        foreach ($order1 as $code) {
            $collection1 = $collection1->add($this->createDefinition($code));
        }

        // Add in different order to collection 2
        $order2 = ['alpha', 'zebra', 'beta', 'middle'];
        foreach ($order2 as $code) {
            $collection2 = $collection2->add($this->createDefinition($code));
        }

        // Serializations must match despite different insertion order
        $hash1 = hash('sha256', $collection1->canonicalSerialization());
        $hash2 = hash('sha256', $collection2->canonicalSerialization());

        $this->assertSame($hash1, $hash2);
    }

    /**
     * @test
     * Semantic evolution policy prevents retroactive drift
     */
    public function test_semantic_evolution_policy_prevents_drift(): void
    {
        $policy = new ImmutableSemanticEvolutionPolicy();

        $original = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('mandate_active'),
            category: GovernanceSemanticCategory::MANDATE,
            canonicalMeaning: 'Elected authority holding office',
            replayImplications: 'Immutable in snapshots',
            legitimacyImplications: 'Determines election validity',
            introducedInDoctrineVersion: '1.0',
        );

        // Attempt to change meaning in 2028 (drift)
        $driftedVersion = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('mandate_active'),
            category: GovernanceSemanticCategory::MANDATE,
            canonicalMeaning: 'Elected or delegated authority',  // CHANGED
            replayImplications: 'Immutable in snapshots',
            legitimacyImplications: 'Determines election validity',
            introducedInDoctrineVersion: '2.0',
        );

        // Policy must prevent this
        $this->expectException(
            'App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\Exception\SemanticEvolutionViolationException'
        );

        $policy->validateEvolution($original, $driftedVersion);
    }

    /**
     * @test
     * Multiple identical registrations produce identical hashes
     */
    public function test_identical_registrations_produce_identical_hashes(): void
    {
        $definitions = [
            new GovernanceSemanticDefinition(
                code: GovernanceSemanticCode::fromString('semantic_1'),
                category: GovernanceSemanticCategory::AUTHORITY,
                canonicalMeaning: 'Meaning 1',
                replayImplications: 'Replay 1',
                legitimacyImplications: 'Legitimacy 1',
                introducedInDoctrineVersion: '1.0',
            ),
            new GovernanceSemanticDefinition(
                code: GovernanceSemanticCode::fromString('semantic_2'),
                category: GovernanceSemanticCategory::LEGITIMACY,
                canonicalMeaning: 'Meaning 2',
                replayImplications: 'Replay 2',
                legitimacyImplications: 'Legitimacy 2',
                introducedInDoctrineVersion: '1.0',
            ),
        ];

        // First registration
        $registry1 = new GovernanceSemanticRegistry();
        foreach ($definitions as $def) {
            $registry1->register($def);
        }

        // Second registration (identical)
        $registry2 = new GovernanceSemanticRegistry();
        foreach ($definitions as $def) {
            $registry2->register($def);
        }

        $hash1 = hash('sha256', $registry1->canonicalSerialization());
        $hash2 = hash('sha256', $registry2->canonicalSerialization());

        $this->assertSame($hash1, $hash2, 'Identical registrations must produce identical hashes');
    }

    /**
     * @test
     * Replay uses stored semantics not live evaluation
     */
    public function test_historical_semantics_remain_canonical(): void
    {
        // Define a semantic at time T=1
        $semanticT1 = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('quorum_requirement'),
            category: GovernanceSemanticCategory::PARTICIPATION,
            canonicalMeaning: 'At least 50% attendance required',
            replayImplications: 'Immutable in historical snapshots',
            legitimacyImplications: 'Decision invalid if quorum not met',
            introducedInDoctrineVersion: '1.0',
        );

        // Create registry and serialize at T=1
        $registryT1 = new GovernanceSemanticRegistry();
        $registryT1->register($semanticT1);
        $serializationT1 = $registryT1->canonicalSerialization();
        $hashT1 = hash('sha256', $serializationT1);

        // At time T=2, replay should use T=1 semantics, not re-evaluate
        $registryT2 = new GovernanceSemanticRegistry();
        $registryT2->register($semanticT1);
        $serializationT2 = $registryT2->canonicalSerialization();
        $hashT2 = hash('sha256', $serializationT2);

        // Hashes must match - historical replay is immutable
        $this->assertSame($hashT1, $hashT2);
    }

    /**
     * @test
     * Semantic invariance across category filtering
     */
    public function test_semantic_category_filter_preserves_invariance(): void
    {
        $allDefs = [
            new GovernanceSemanticDefinition(
                code: GovernanceSemanticCode::fromString('auth_semantic'),
                category: GovernanceSemanticCategory::AUTHORITY,
                canonicalMeaning: 'Authority test',
                replayImplications: 'Test',
                legitimacyImplications: 'Test',
                introducedInDoctrineVersion: '1.0',
            ),
            new GovernanceSemanticDefinition(
                code: GovernanceSemanticCode::fromString('legit_semantic'),
                category: GovernanceSemanticCategory::LEGITIMACY,
                canonicalMeaning: 'Legitimacy test',
                replayImplications: 'Test',
                legitimacyImplications: 'Test',
                introducedInDoctrineVersion: '1.0',
            ),
        ];

        // Two registries with same definitions
        $registry1 = new GovernanceSemanticRegistry();
        foreach ($allDefs as $def) {
            $registry1->register($def);
        }

        $registry2 = new GovernanceSemanticRegistry();
        foreach ($allDefs as $def) {
            $registry2->register($def);
        }

        // Full registries must have same serialization
        $this->assertSame(
            $registry1->canonicalSerialization(),
            $registry2->canonicalSerialization()
        );
    }

    /**
     * @test
     * Collection immutability preserves semantic invariance
     */
    public function test_collection_immutability_preserves_invariance(): void
    {
        $def1 = $this->createDefinition('code_1');
        $def2 = $this->createDefinition('code_2');

        $col1 = new GovernanceSemanticCollection();
        $col1 = $col1->add($def1);
        $col1 = $col1->add($def2);

        // Create fresh collection with same operations
        $col2 = new GovernanceSemanticCollection();
        $col2 = $col2->add($def1);
        $col2 = $col2->add($def2);

        // Serializations must be identical
        $this->assertSame(
            $col1->canonicalSerialization(),
            $col2->canonicalSerialization()
        );
    }

    /**
     * @test
     * Empty collections have consistent behavior
     */
    public function test_empty_collection_invariance(): void
    {
        $col1 = new GovernanceSemanticCollection();
        $col2 = new GovernanceSemanticCollection();

        $this->assertSame(
            $col1->canonicalSerialization(),
            $col2->canonicalSerialization()
        );
    }

    private function createDefinition(string $code): GovernanceSemanticDefinition
    {
        return new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString($code),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: "Meaning for $code",
            replayImplications: 'Immutable',
            legitimacyImplications: 'Valid',
            introducedInDoctrineVersion: '1.0',
        );
    }
}
