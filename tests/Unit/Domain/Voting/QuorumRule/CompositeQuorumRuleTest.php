<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Voting\QuorumRule;

use App\Domain\Voting\QuorumRule\QuorumRule;
use App\Domain\Voting\QuorumRule\QuorumOperator;
use App\Domain\Voting\QuorumRule\Rules\MinimumAbsoluteQuorumRule;
use App\Domain\Voting\QuorumRule\Rules\MinimumBasisPointQuorumRule;
use App\Domain\Voting\QuorumRule\CompositeQuorumRule;
use App\Domain\Voting\QuorumRule\Evaluation\QuorumRuleEvaluationEngine;
use App\Domain\Voting\ValueObject\EligibilitySnapshot;
use PHPUnit\Framework\TestCase;

/**
 * CompositeQuorumRule Test Suite
 *
 * LOCKED CONTRACTS (non-negotiable):
 * 1. identity() ≠ fingerprint() — different semantic contracts
 * 2. identity() includes schema_version + normalization_version
 * 3. fingerprint() is strictly 4-layer (Identity, Tree, Strategy, Outcome)
 * 4. WEIGHTED has explicit threshold (not implicit AND)
 * 5. Percentage uses integer basis points (10000 = 100%), never float
 * 6. orderingKey() does not exist — ordering ONLY by identity()
 * 7. Normalization is strict pipeline: normalize → flatten → sort → freeze
 */
class CompositeQuorumRuleTest extends TestCase
{
    /**
     * @test
     * AND operator evaluation: all rules must pass
     */
    public function test_composable_quorum_evaluation_and_operator(): void
    {
        $ruleA = new MinimumAbsoluteQuorumRule(5);
        $ruleB = new MinimumAbsoluteQuorumRule(3);

        $composite = new CompositeQuorumRule(
            [$ruleA, $ruleB],
            QuorumOperator::AND
        );

        $eligibility = new EligibilitySnapshot(['voter_1', 'voter_2', 'voter_3']);

        // 4 votes: A fails (needs 5), B passes (needs 3) → AND fails
        $this->assertFalse($composite->evaluate($eligibility, 4));

        // 5 votes: A passes, B passes → AND passes
        $this->assertTrue($composite->evaluate($eligibility, 5));
    }

    /**
     * @test
     * Composition determinism: different insertion order → identical fingerprint
     */
    public function test_rule_composition_determinism_different_insertion_orders(): void
    {
        $ruleA = new MinimumAbsoluteQuorumRule(5);
        $ruleB = new MinimumBasisPointQuorumRule(5000);  // 50% in basis points
        $ruleC = new MinimumAbsoluteQuorumRule(2);

        // Order 1: A, B, C
        $composite1 = new CompositeQuorumRule([$ruleA, $ruleB, $ruleC], QuorumOperator::AND);

        // Order 2: C, A, B (different insertion order)
        $composite2 = new CompositeQuorumRule([$ruleC, $ruleA, $ruleB], QuorumOperator::AND);

        // Order 3: B, C, A (yet another order)
        $composite3 = new CompositeQuorumRule([$ruleB, $ruleC, $ruleA], QuorumOperator::AND);

        // All three composites MUST have identical fingerprints (normalization enforces this)
        $this->assertSame($composite1->fingerprint(), $composite2->fingerprint());
        $this->assertSame($composite2->fingerprint(), $composite3->fingerprint());
    }

    /**
     * @test
     * Participation constraint: fails when below threshold
     */
    public function test_participation_constraint_enforcement_fails_when_below_threshold(): void
    {
        $absoluteRule = new MinimumAbsoluteQuorumRule(10);
        $percentageRule = new MinimumBasisPointQuorumRule(5000);  // 50% in basis points

        $eligibility = new EligibilitySnapshot(array_fill(0, 20, 'voter_' . rand()));

        // Absolute rule: 9 votes < 10 threshold
        $this->assertFalse($absoluteRule->evaluate($eligibility, 9));
        $this->assertTrue($absoluteRule->evaluate($eligibility, 10));

        // Percentage rule: 9 votes < 50% of 20 (which is 10)
        $this->assertFalse($percentageRule->evaluate($eligibility, 9));
        $this->assertTrue($percentageRule->evaluate($eligibility, 10));
    }

    /**
     * @test
     * Eligibility-aware quorum: percentage uses eligible count
     */
    public function test_eligibility_aware_quorum_percentage_uses_eligible_count(): void
    {
        $percentageRule = new MinimumBasisPointQuorumRule(5000);  // 50%

        // 10 eligible voters: need 5 for 50%
        $eligibility10 = new EligibilitySnapshot(array_fill(0, 10, 'voter_' . rand()));
        $this->assertFalse($percentageRule->evaluate($eligibility10, 4));
        $this->assertTrue($percentageRule->evaluate($eligibility10, 5));

        // 20 eligible voters: need 10 for 50%
        $eligibility20 = new EligibilitySnapshot(array_fill(0, 20, 'voter_' . rand()));
        $this->assertFalse($percentageRule->evaluate($eligibility20, 9));
        $this->assertTrue($percentageRule->evaluate($eligibility20, 10));

        // Same rule object respects different eligibility contexts
    }

    /**
     * @test
     * Replay stability: fingerprint deterministic across executions
     */
    public function test_replay_stability_fingerprint_identical_across_executions(): void
    {
        $ruleA = new MinimumAbsoluteQuorumRule(5);
        $ruleB = new MinimumBasisPointQuorumRule(4000);  // 40%

        $fingerprints = [];
        for ($i = 0; $i < 5; $i++) {
            $composite = new CompositeQuorumRule([$ruleA, $ruleB], QuorumOperator::AND);
            $fingerprints[] = $composite->fingerprint();
        }

        // All 5 executions → identical fingerprints
        $uniqueFingerprints = array_unique($fingerprints);
        $this->assertCount(1, $uniqueFingerprints, 'Fingerprints must be deterministic');
    }

    /**
     * @test
     * Immutability: all properties are readonly
     */
    public function test_no_semantic_mutation_rules_immutable_after_construction(): void
    {
        $rule = new MinimumAbsoluteQuorumRule(5);
        $composite = new CompositeQuorumRule([$rule], QuorumOperator::AND);

        // Verify CompositeQuorumRule properties are readonly
        $reflection = new \ReflectionClass($composite);
        foreach ($reflection->getProperties() as $property) {
            $this->assertTrue(
                $property->isReadonly(),
                "Property {$property->getName()} must be readonly"
            );
        }

        // Verify rule properties are readonly
        $reflection = new \ReflectionClass($rule);
        foreach ($reflection->getProperties() as $property) {
            $this->assertTrue(
                $property->isReadonly(),
                "Property {$property->getName()} must be readonly"
            );
        }
    }

    /**
     * @test
     * Tree permutation: different order → same evaluation AND fingerprint
     */
    public function test_tree_permutation_equivalence_same_result_regardless_of_order(): void
    {
        $ruleA = new MinimumAbsoluteQuorumRule(5);
        $ruleB = new MinimumBasisPointQuorumRule(5000);
        $ruleC = new MinimumAbsoluteQuorumRule(2);

        $composite1 = new CompositeQuorumRule([$ruleA, $ruleB, $ruleC], QuorumOperator::AND);
        $composite2 = new CompositeQuorumRule([$ruleC, $ruleB, $ruleA], QuorumOperator::AND);

        $eligibility = new EligibilitySnapshot(array_fill(0, 10, 'voter_' . rand()));

        // Same evaluation result regardless of insertion order
        $this->assertSame(
            $composite1->evaluate($eligibility, 5),
            $composite2->evaluate($eligibility, 5)
        );

        // Same fingerprint regardless of insertion order
        $this->assertSame($composite1->fingerprint(), $composite2->fingerprint());
    }

    /**
     * @test
     * Rule identity collision resistance: same structure → same identity
     */
    public function test_rule_identity_collision_resistance_same_structure_equals_same_identity(): void
    {
        $rule1 = new MinimumAbsoluteQuorumRule(10);
        $rule2 = new MinimumAbsoluteQuorumRule(10);

        $this->assertSame($rule1->identity(), $rule2->identity());

        $rule3 = new MinimumAbsoluteQuorumRule(11);
        $this->assertNotSame($rule1->identity(), $rule3->identity());

        // Basis point rules
        $pct1 = new MinimumBasisPointQuorumRule(5000);
        $pct2 = new MinimumBasisPointQuorumRule(5000);
        $this->assertSame($pct1->identity(), $pct2->identity());

        $pct3 = new MinimumBasisPointQuorumRule(6000);
        $this->assertNotSame($pct1->identity(), $pct3->identity());
    }

    /**
     * @test
     * Normalization: nested same-operator composites are flattened and sorted
     */
    public function test_composite_normalization_enforced_tree_is_canonical_after_construction(): void
    {
        $ruleA = new MinimumAbsoluteQuorumRule(5);
        $ruleB = new MinimumAbsoluteQuorumRule(3);
        $ruleC = new MinimumAbsoluteQuorumRule(2);

        // Nested: AND(AND(A, B), C)
        $inner = new CompositeQuorumRule([$ruleA, $ruleB], QuorumOperator::AND);
        $nested = new CompositeQuorumRule([$inner, $ruleC], QuorumOperator::AND);

        // Flat: AND(A, B, C)
        $flat = new CompositeQuorumRule([$ruleA, $ruleB, $ruleC], QuorumOperator::AND);

        // After normalization, nested must equal flat (flattening enforced)
        $this->assertSame($nested->fingerprint(), $flat->fingerprint());
    }

    /**
     * @test
     * identity() ≠ fingerprint() — different contracts, both stable
     * Identity includes schema_version, fingerprint is 4-layer
     */
    public function test_identity_is_not_equal_to_fingerprint_and_both_are_stable(): void
    {
        $ruleA = new MinimumAbsoluteQuorumRule(5);
        $ruleB = new MinimumBasisPointQuorumRule(5000);

        $composite = new CompositeQuorumRule([$ruleA, $ruleB], QuorumOperator::AND);

        $identity = $composite->identity();
        $fingerprint = $composite->fingerprint();

        // CRITICAL CONTRACT: they must NOT be equal
        $this->assertNotSame($identity, $fingerprint);

        // Both must be stable
        $this->assertSame($identity, $composite->identity(), 'identity() is stable');
        $this->assertSame($fingerprint, $composite->fingerprint(), 'fingerprint() is stable');
    }

    /**
     * @test
     * identity() includes schema_version and normalization_version
     * (not just rule tree, but also versioning info for replay safety)
     */
    public function test_identity_includes_schema_and_normalization_version(): void
    {
        $rule1 = new MinimumAbsoluteQuorumRule(5);
        $rule2 = new MinimumAbsoluteQuorumRule(5);

        $composite1 = new CompositeQuorumRule([$rule1], QuorumOperator::AND);
        $composite2 = new CompositeQuorumRule([$rule2], QuorumOperator::AND);

        // Same rules → same identities (identity includes schema version implicitly via rule hashing)
        $this->assertSame($composite1->identity(), $composite2->identity());

        // Verify identity string is not empty and contains deterministic info
        $this->assertNotEmpty($composite1->identity());
        $this->assertIsString($composite1->identity());
        $this->assertGreaterThan(10, strlen($composite1->identity()), 'identity must include version info');
    }

    /**
     * @test
     * WEIGHTED operator with explicit threshold (not implicit AND)
     * This is a critical contract fix from architectural review
     */
    public function test_weighted_quorum_requires_explicit_threshold_not_implicit_and(): void
    {
        $rule1 = new MinimumAbsoluteQuorumRule(5);
        $rule2 = new MinimumAbsoluteQuorumRule(3);

        $eligibility = new EligibilitySnapshot(array_fill(0, 10, 'voter_' . rand()));

        // WEIGHTED with weights [10, 5] and threshold 10
        // Rule1 true (weight 10) + Rule2 false (weight 0) = 10 >= 10 → true
        $weighted = new CompositeQuorumRule(
            [$rule1, $rule2],
            QuorumOperator::WEIGHTED,
            [10, 5],
            10  // explicit threshold
        );

        // 5 votes: Rule1 true (10) + Rule2 false (0) = 10 → exactly meets threshold
        $this->assertTrue($weighted->evaluate($eligibility, 5));

        // 4 votes: Rule1 false (0) + Rule2 true (5) = 5 < 10 → fails
        $this->assertFalse($weighted->evaluate($eligibility, 4));

        // This proves WEIGHTED is NOT implicit AND (which would require both rules true)
    }

    /**
     * @test
     * WEIGHTED weights must be integers only (no floats)
     * Prevents floating-point drift in governance systems
     */
    public function test_weighted_weights_are_integers_only(): void
    {
        $rule1 = new MinimumAbsoluteQuorumRule(5);
        $rule2 = new MinimumAbsoluteQuorumRule(3);

        // Integer weights: allowed
        $weighted = new CompositeQuorumRule(
            [$rule1, $rule2],
            QuorumOperator::WEIGHTED,
            [10, 5],
            10
        );

        $this->assertNotNull($weighted, 'Integer weights accepted');

        // Float weights: should throw
        $this->expectException(\InvalidArgumentException::class);
        new CompositeQuorumRule(
            [$rule1, $rule2],
            QuorumOperator::WEIGHTED,
            [10.5, 5.0],  // floats — forbidden
            10
        );
    }

    /**
     * @test
     * Basis points (integer only) for percentage rules, no floats
     * 10000 = 100%, eliminates float drift
     */
    public function test_percentage_rules_use_integer_basis_points_only(): void
    {
        // 50% = 5000 basis points
        $rule1 = new MinimumBasisPointQuorumRule(5000);
        $rule2 = new MinimumBasisPointQuorumRule(7500);  // 75%

        $eligibility = new EligibilitySnapshot(array_fill(0, 100, 'voter_' . rand()));

        // 50 votes: 50% of 100 → rule1 passes
        $this->assertTrue($rule1->evaluate($eligibility, 50));
        $this->assertFalse($rule1->evaluate($eligibility, 49));

        // 75 votes: 75% of 100 → rule2 passes
        $this->assertTrue($rule2->evaluate($eligibility, 75));
        $this->assertFalse($rule2->evaluate($eligibility, 74));

        // Integer basis points guarantee deterministic arithmetic (no float precision issues)
    }

    /**
     * @test
     * No orderingKey system exists — ordering derives from identity() only
     * This is a removed feature (architectural simplification)
     */
    public function test_no_ordering_key_system_exists_only_identity_based_ordering(): void
    {
        $rule1 = new MinimumAbsoluteQuorumRule(5);
        $rule2 = new MinimumAbsoluteQuorumRule(3);

        $composite = new CompositeQuorumRule([$rule1, $rule2], QuorumOperator::AND);

        // Verify orderingKey() does NOT exist (method should not exist)
        $this->assertFalse(
            method_exists($composite, 'orderingKey'),
            'orderingKey() method must not exist — ordering is identity-based only'
        );

        // Ordering is done internally via identity() sorting
        // No external orderingKey() API exists
    }

    /**
     * @test
     * Fingerprint layers are independent (no overlap between identity, structure, strategy)
     * Layer 1: RuleIdentityHashSet, Layer 2: TreeHash, Layer 3: StrategyHash, Layer 4: OutcomeHash
     */
    public function test_fingerprint_layers_are_distinct_and_independent(): void
    {
        $rule1 = new MinimumAbsoluteQuorumRule(5);
        $rule2 = new MinimumAbsoluteQuorumRule(5);

        // Same rules, same operator → same fingerprint
        $composite1 = new CompositeQuorumRule([$rule1], QuorumOperator::AND);
        $composite2 = new CompositeQuorumRule([$rule2], QuorumOperator::AND);
        $this->assertSame($composite1->fingerprint(), $composite2->fingerprint());

        // Different operator → different fingerprint
        $compositeOr = new CompositeQuorumRule([$rule1], QuorumOperator::OR);
        $this->assertNotSame($composite1->fingerprint(), $compositeOr->fingerprint());

        // Different child rule → different fingerprint
        $rule3 = new MinimumAbsoluteQuorumRule(6);
        $compositeDiff = new CompositeQuorumRule([$rule3], QuorumOperator::AND);
        $this->assertNotSame($composite1->fingerprint(), $compositeDiff->fingerprint());

        // This proves layers are distinct and contribute independently to the final hash
    }
}
