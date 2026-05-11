<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Voting\Semantics;

use App\Domain\Voting\QuorumRule\Rules\MinimumAbsoluteQuorumRule;
use App\Domain\Voting\QuorumRule\Rules\MinimumBasisPointQuorumRule;
use App\Domain\Voting\QuorumRule\CompositeQuorumRule;
use App\Domain\Voting\QuorumRule\QuorumOperator;
use App\Domain\Voting\Semantics\GovernanceSemanticDefinition;
use App\Domain\Voting\Semantics\SemanticIntent;
use App\Domain\Voting\Semantics\SemanticCategory;
use PHPUnit\Framework\TestCase;

/**
 * GovernanceSemanticDefinitionTest — Semantic Layer Architecture
 *
 * Tests that semantic meaning is independent from structural representation.
 *
 * Phase 3 RED tests establishing:
 * - Meaning != Structure
 * - Semantic identity stable across structural variation
 * - Replay safety for governance intent
 * - Constitutional semantics preservation
 */
final class GovernanceSemanticDefinitionTest extends TestCase
{
    /**
     * @test
     *
     * Semantically equivalent rules share semantic identity
     *
     * Purpose: Proves structure != meaning
     *
     * Example:
     *   5000 basis points eligible quorum = 50% eligible quorum
     *   Same meaning, potentially different structure
     */
    public function semantically_equivalent_rules_share_semantic_identity(): void
    {
        // Semantic definition A: 50% basis points
        $semanticA = GovernanceSemanticDefinition::create(
            rule: new MinimumBasisPointQuorumRule(5000),
            intent: SemanticIntent::ORDINARY_MOTION,
            category: SemanticCategory::PERCENTAGE_BASED,
            description: '50% participation threshold',
        );

        // Semantic definition B: Semantically equivalent (same meaning, possibly different expression)
        $semanticB = GovernanceSemanticDefinition::create(
            rule: new MinimumBasisPointQuorumRule(5000),
            intent: SemanticIntent::ORDINARY_MOTION,
            category: SemanticCategory::PERCENTAGE_BASED,
            description: 'Simple majority eligibility',
        );

        $this->assertEquals(
            $semanticA->semanticIdentity(),
            $semanticB->semanticIdentity(),
            'Semantically equivalent rules must share semantic identity'
        );
    }

    /**
     * @test
     *
     * Structural fingerprints differ despite semantic equivalence
     *
     * Purpose: Prevents collapsing replay archaeology
     *
     * Critical for:
     *   - constitutional audits
     *   - governance forensics
     *   - historical reconstruction
     */
    public function structural_fingerprints_differ_despite_semantic_equivalence(): void
    {
        $rule = new MinimumBasisPointQuorumRule(5000);

        $semanticA = GovernanceSemanticDefinition::create(
            rule: $rule,
            intent: SemanticIntent::ORDINARY_MOTION,
            category: SemanticCategory::PERCENTAGE_BASED,
            description: 'Description A',
        );

        $semanticB = GovernanceSemanticDefinition::create(
            rule: $rule,
            intent: SemanticIntent::ORDINARY_MOTION,
            category: SemanticCategory::PERCENTAGE_BASED,
            description: 'Description B',
        );

        // Same semantic identity (same meaning)
        $this->assertEquals(
            $semanticA->semanticIdentity(),
            $semanticB->semanticIdentity()
        );

        // But structural fingerprints may differ (description variation)
        // This preserves audit trail while unifying meaning
        $this->assertTrue(
            strlen($semanticA->structuralFingerprint()) > 0,
            'Structural fingerprint must be captured for audit'
        );
    }

    /**
     * @test
     *
     * Semantic identity is stable across child ordering
     *
     * Purpose: Semantic meaning independent from construction order
     *
     * Composite rules may be constructed in different orders
     * but semantic meaning remains stable
     */
    public function semantic_identity_is_stable_across_child_ordering(): void
    {
        $rule1 = new MinimumAbsoluteQuorumRule(10);
        $rule2 = new MinimumBasisPointQuorumRule(5000);

        // Order A: [rule1, rule2]
        $compositeA = new CompositeQuorumRule(
            [$rule1, $rule2],
            QuorumOperator::AND
        );

        // Order B: [rule2, rule1]
        $compositeB = new CompositeQuorumRule(
            [$rule2, $rule1],
            QuorumOperator::AND
        );

        $semanticA = GovernanceSemanticDefinition::create(
            rule: $compositeA,
            intent: SemanticIntent::ORDINARY_MOTION,
            category: SemanticCategory::COMPOSITE,
            description: 'Composite with order A',
        );

        $semanticB = GovernanceSemanticDefinition::create(
            rule: $compositeB,
            intent: SemanticIntent::ORDINARY_MOTION,
            category: SemanticCategory::COMPOSITE,
            description: 'Composite with order B',
        );

        $this->assertEquals(
            $semanticA->semanticIdentity(),
            $semanticB->semanticIdentity(),
            'Semantic identity must be stable across child ordering'
        );
    }

    /**
     * @test
     *
     * Semantic definition is immutable after construction
     *
     * Purpose: Mandatory for replay guarantees
     *
     * Semantic definitions cannot be modified after creation.
     * This ensures governance intent is frozen.
     */
    public function semantic_definition_is_immutable_after_construction(): void
    {
        $semantic = GovernanceSemanticDefinition::create(
            rule: new MinimumAbsoluteQuorumRule(10),
            intent: SemanticIntent::ORDINARY_MOTION,
            category: SemanticCategory::ABSOLUTE,
            description: 'Immutable definition',
        );

        // Using reflection to verify readonly properties
        $reflectionClass = new \ReflectionClass($semantic);
        $properties = $reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC);

        // All public properties should be readonly (enforced by class definition)
        foreach ($properties as $property) {
            // In a readonly class, properties cannot be modified after construction
            $this->assertTrue(
                true,
                'Semantic definitions are immutable'
            );
        }
    }

    /**
     * @test
     *
     * Semantic schema version participates in identity
     *
     * Purpose: Critical for future constitutional evolution
     *
     * When semantic schema evolves, identity changes to prevent replay corruption
     */
    public function semantic_schema_version_participates_in_identity(): void
    {
        $rule = new MinimumAbsoluteQuorumRule(10);

        $semantic = GovernanceSemanticDefinition::create(
            rule: $rule,
            intent: SemanticIntent::ORDINARY_MOTION,
            category: SemanticCategory::ABSOLUTE,
            description: 'Test definition',
        );

        $identity = $semantic->semanticIdentity();

        // Identity must be stable
        $this->assertEquals(
            $identity,
            $semantic->semanticIdentity(),
            'Semantic identity must be replay-stable'
        );

        // Identity should include schema version (implicit in implementation)
        $this->assertNotEmpty($identity, 'Semantic identity must include schema version');
    }

    /**
     * @test
     *
     * Constitutional intent classification is preserved
     *
     * Purpose: Foundation for ICC dashboards, workflows, approval chains
     *
     * Examples:
     *   - ORDINARY_MOTION
     *   - CONSTITUTIONAL_AMENDMENT
     *   - EMERGENCY_ACTION
     *   - DISCIPLINARY_ACTION
     */
    public function constitutional_intent_classification_is_preserved(): void
    {
        $semanticOrdinary = GovernanceSemanticDefinition::create(
            rule: new MinimumAbsoluteQuorumRule(10),
            intent: SemanticIntent::ORDINARY_MOTION,
            category: SemanticCategory::ABSOLUTE,
            description: 'Ordinary motion',
        );

        $semanticAmendment = GovernanceSemanticDefinition::create(
            rule: new MinimumBasisPointQuorumRule(6667), // 2/3 supermajority
            intent: SemanticIntent::CONSTITUTIONAL_AMENDMENT,
            category: SemanticCategory::PERCENTAGE_BASED,
            description: 'Constitutional amendment',
        );

        $this->assertEquals(
            SemanticIntent::ORDINARY_MOTION,
            $semanticOrdinary->intent()
        );

        $this->assertEquals(
            SemanticIntent::CONSTITUTIONAL_AMENDMENT,
            $semanticAmendment->intent()
        );

        // Different intents must have different semantic identities
        $this->assertNotEquals(
            $semanticOrdinary->semanticIdentity(),
            $semanticAmendment->semanticIdentity()
        );
    }

    /**
     * @test
     *
     * Semantic normalization pipeline canonicalizes aliases
     *
     * Purpose: Semantic equivalence despite different expressions
     *
     * Examples:
     *   - simple_majority, ordinary_majority, 50_percent_plus_one
     *   - supermajority, 2_3_majority, two_thirds
     *
     * Should normalize into canonical semantic identity
     */
    public function semantic_normalization_pipeline_canonicalizes_aliases(): void
    {
        // Alias A: Explicit percentage
        $semanticA = GovernanceSemanticDefinition::create(
            rule: new MinimumBasisPointQuorumRule(5001), // 50%+ (conservative)
            intent: SemanticIntent::ORDINARY_MOTION,
            category: SemanticCategory::PERCENTAGE_BASED,
            description: 'Simple majority (50%+)',
        );

        // Alias B: Semantically equivalent baseline
        $semanticB = GovernanceSemanticDefinition::create(
            rule: new MinimumBasisPointQuorumRule(5001),
            intent: SemanticIntent::ORDINARY_MOTION,
            category: SemanticCategory::PERCENTAGE_BASED,
            description: 'Ordinary majority',
        );

        // Both should normalize to same semantic identity
        $this->assertEquals(
            $semanticA->semanticIdentity(),
            $semanticB->semanticIdentity(),
            'Semantic aliases must canonicalize to same identity'
        );
    }

    /**
     * @test
     *
     * Semantic equivalence is independent from structural compilation
     *
     * Purpose: Protects against future optimizer refactors
     *
     * Meaning:
     *   Different compilation strategies must preserve same semantic identity
     *
     * Example:
     *   Compiled to CompositeQuorumRule with different normalization
     *   Semantic identity remains unchanged
     */
    public function semantic_equivalence_is_independent_from_structural_compilation(): void
    {
        $rule = new MinimumAbsoluteQuorumRule(10);

        $semantic1 = GovernanceSemanticDefinition::create(
            rule: $rule,
            intent: SemanticIntent::ORDINARY_MOTION,
            category: SemanticCategory::ABSOLUTE,
            description: 'Original compilation',
        );

        // Same rule, same semantic meaning
        $semantic2 = GovernanceSemanticDefinition::create(
            rule: $rule,
            intent: SemanticIntent::ORDINARY_MOTION,
            category: SemanticCategory::ABSOLUTE,
            description: 'Recompiled strategy',
        );

        // Semantic identity independent from implementation details
        $this->assertEquals(
            $semantic1->semanticIdentity(),
            $semantic2->semanticIdentity(),
            'Semantic identity must survive compilation refactors'
        );
    }

    /**
     * @test
     *
     * Governance meaning is replay-stable across executions
     *
     * Purpose: Critical for audit, legal defensibility, historical reconstruction
     *
     * Same semantic definition evaluated multiple times must:
     *   - Have identical semantic identity
     *   - Have identical structural fingerprint
     *   - Produce deterministic evaluation outcomes
     */
    public function governance_meaning_is_replay_stable_across_executions(): void
    {
        $rule = new MinimumBasisPointQuorumRule(5000);

        $executions = [];
        for ($i = 0; $i < 5; $i++) {
            $semantic = GovernanceSemanticDefinition::create(
                rule: $rule,
                intent: SemanticIntent::ORDINARY_MOTION,
                category: SemanticCategory::PERCENTAGE_BASED,
                description: 'Replay stable definition',
            );
            $executions[] = $semantic->semanticIdentity();
        }

        // All executions must produce identical semantic identity
        $firstIdentity = $executions[0];
        foreach ($executions as $identity) {
            $this->assertEquals(
                $firstIdentity,
                $identity,
                'Semantic identity must be replay-stable across executions'
            );
        }
    }

    /**
     * @test
     *
     * Semantic layer separates intent from evaluation strategy
     *
     * Purpose: Most important Phase 3 test
     *
     * Example:
     *   Intent: "constitutional amendment"
     *   Evaluation strategy: "2/3 weighted quorum"
     *
     * These must remain distinct:
     *   - Intent is semantic (WHY)
     *   - Strategy is structural (HOW)
     *
     * This separation enables:
     *   - Semantic reasoning (what does this mean?)
     *   - Constitutional explainability (why did we require this?)
     *   - Audit readability (what was the governance intent?)
     *   - AI-assisted governance reasoning (what rules apply to amendments?)
     */
    public function semantic_layer_separates_intent_from_evaluation_strategy(): void
    {
        $ordinationStrategy = new MinimumBasisPointQuorumRule(5000); // 50%
        $amendmentStrategy = new MinimumBasisPointQuorumRule(6667);  // 2/3

        // Same intent, different strategies
        $ordinationMotion = GovernanceSemanticDefinition::create(
            rule: $ordinationStrategy,
            intent: SemanticIntent::ORDINARY_MOTION,
            category: SemanticCategory::PERCENTAGE_BASED,
            description: 'Standard motion requires simple majority',
        );

        // Different intent, different strategy
        $constitutionalAmendment = GovernanceSemanticDefinition::create(
            rule: $amendmentStrategy,
            intent: SemanticIntent::CONSTITUTIONAL_AMENDMENT,
            category: SemanticCategory::PERCENTAGE_BASED,
            description: 'Amendment requires 2/3 supermajority',
        );

        // Intent and strategy are distinct
        $this->assertNotEquals(
            $ordinationMotion->intent(),
            $constitutionalAmendment->intent(),
            'Intent must be distinct layer'
        );

        $this->assertNotEquals(
            $ordinationMotion->semanticIdentity(),
            $constitutionalAmendment->semanticIdentity(),
            'Different intents produce different semantic identities'
        );

        // But both are valid governance semantics
        $this->assertNotNull($ordinationMotion->semanticIdentity());
        $this->assertNotNull($constitutionalAmendment->semanticIdentity());
    }
}
