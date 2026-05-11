<?php

declare(strict_types=1);

namespace App\Domain\Voting\QuorumRule;

use App\Domain\Voting\ValueObject\EligibilitySnapshot;

/**
 * CompositeQuorumRule — Algebra Composition Engine
 *
 * Deterministic orchestration of multiple rules with AND/OR/WEIGHTED operators.
 *
 * CRITICAL CONTRACTS:
 * 1. Tree normalization in constructor (flatten + sort + freeze)
 * 2. identity() ≠ fingerprint() — different semantic contracts
 * 3. identity() includes schema_version and normalization_version
 * 4. fingerprint() is 4-layer hash (Identity Set + Tree + Strategy + Outcome space)
 * 5. ordering is EXCLUSIVELY identity-based (no secondary systems)
 * 6. WEIGHTED has explicit threshold (not implicit AND)
 * 7. WEIGHTED weights are integers only (no floats)
 */
final readonly class CompositeQuorumRule implements QuorumRule
{
    /**
     * Normalized and sorted child rules (CANONICAL ORDER).
     * Sorted by identity() to ensure insertion-order independence.
     *
     * @var QuorumRule[]
     */
    private array $normalizedRules;

    /**
     * Reordered weights matching normalizedRules order.
     * Initially provided in rule constructor order, reordered during normalization.
     *
     * @var int[]|null
     */
    private array|null $reorderedWeights;

    public function __construct(
        array $rules,
        private readonly QuorumOperator $operator,
        private readonly ?array $weights = null,
        private readonly ?int $threshold = null
    ) {
        // Validate WEIGHTED constraints
        if ($this->operator === QuorumOperator::WEIGHTED) {
            if ($this->threshold === null) {
                throw new \InvalidArgumentException('WEIGHTED operator requires explicit threshold');
            }
            // Enforce integer-only weights
            foreach ($this->weights ?? [] as $w) {
                if (!is_int($w)) {
                    throw new \InvalidArgumentException('WEIGHTED weights must be integers only');
                }
            }
        }

        // NORMALIZATION PIPELINE (strict order, deterministic)
        // Step 1: Normalize each child rule
        $normalized = array_map(fn(QuorumRule $r) => $r->normalize(), $rules);

        // Step 2: Flatten same-operator composites (AND(AND(A,B), C) → AND(A,B,C))
        $flattened = $this->flattenSameOperator($normalized, $this->operator);

        // Step 3: Sort by identity() for canonical order (insertion-order independent)
        // Create array of [rule, originalIndex] pairs for tracking
        $ruleIndexPairs = array_map(
            fn(QuorumRule $rule, int $index) => [$rule, $index],
            array_values($flattened),
            array_keys(array_values($flattened))
        );

        usort($ruleIndexPairs, fn($a, $b) => strcmp($a[0]->identity(), $b[0]->identity()));

        // Extract sorted rules and original index order
        $this->normalizedRules = array_column($ruleIndexPairs, 0);
        $originalIndices = array_column($ruleIndexPairs, 1);

        // Reorder weights to match sorted rules (WEIGHTED only)
        $reorderedWeights = null;
        if ($this->operator === QuorumOperator::WEIGHTED && $this->weights !== null) {
            $reorderedWeights = [];
            foreach ($originalIndices as $newIndex => $oldIndex) {
                $reorderedWeights[$newIndex] = $this->weights[$oldIndex] ?? 0;
            }
        }
        $this->reorderedWeights = $reorderedWeights;
    }

    /**
     * Evaluate rule tree against current state.
     *
     * Pure function: no side effects, deterministic.
     */
    public function evaluate(EligibilitySnapshot $eligibility, int $actualParticipation): bool
    {
        return match ($this->operator) {
            QuorumOperator::AND      => $this->evaluateAnd($eligibility, $actualParticipation),
            QuorumOperator::OR       => $this->evaluateOr($eligibility, $actualParticipation),
            QuorumOperator::WEIGHTED => $this->evaluateWeighted($eligibility, $actualParticipation),
        };
    }

    /**
     * AND: all rules must pass.
     */
    private function evaluateAnd(EligibilitySnapshot $eligibility, int $actualParticipation): bool
    {
        foreach ($this->normalizedRules as $rule) {
            if (!$rule->evaluate($eligibility, $actualParticipation)) {
                return false;
            }
        }
        return true;
    }

    /**
     * OR: at least one rule must pass.
     */
    private function evaluateOr(EligibilitySnapshot $eligibility, int $actualParticipation): bool
    {
        foreach ($this->normalizedRules as $rule) {
            if ($rule->evaluate($eligibility, $actualParticipation)) {
                return true;
            }
        }
        return false;
    }

    /**
     * WEIGHTED: integer linear algebra.
     * weightedSum = Σ(weight_i × (rule_i ? 1 : 0)) >= threshold
     */
    private function evaluateWeighted(EligibilitySnapshot $eligibility, int $actualParticipation): bool
    {
        $weightedSum = 0;
        foreach ($this->normalizedRules as $i => $rule) {
            $weight = $this->reorderedWeights[$i] ?? 0;
            if ($rule->evaluate($eligibility, $actualParticipation)) {
                $weightedSum += $weight;
            }
        }
        return $weightedSum >= $this->threshold;
    }

    /**
     * Node-level identity hash (includes schema versions).
     *
     * NOT equal to fingerprint(). Used for:
     * - Composite ordering (identity-based sorting)
     * - Replay version tracking
     *
     * Does NOT include tree structure or outcome.
     */
    public function identity(): string
    {
        $childIds = array_map(fn(QuorumRule $r) => $r->identity(), $this->normalizedRules);
        // Already sorted in constructor, but sort again for safety
        sort($childIds);

        return hash('sha256', json_encode([
            'type'                  => 'CompositeQuorumRule',
            'schema_version'        => '1.0',
            'normalization_version' => 'flatten-sort-v1',
            'operator'              => $this->operator->value,
            'children'              => $childIds,
            'weights'               => $this->weights,
            'threshold'             => $this->threshold,
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Tree-level 4-layer audit hash for replay verification.
     *
     * NOT equal to identity(). Serves different semantic contract.
     *
     * Layer 1: Rule Identity Set Hash (unordered identities)
     * Layer 2: Canonical Tree Structure Hash (full tree serialization)
     * Layer 3: Execution Strategy Hash (operator + weights + threshold)
     * Layer 4: (Outcome hash space reserved for evaluation-time use)
     */
    public function fingerprint(): string
    {
        // Layer 1: RuleIdentityHashSet (sorted node identities, order-independent)
        $identities = array_map(fn(QuorumRule $r) => $r->identity(), $this->normalizedRules);
        sort($identities);
        $layer1 = hash('sha256', implode('|', $identities));

        // Layer 2: NormalizedTreeHash (full recursive tree structure)
        $layer2 = hash('sha256', json_encode(
            $this->serializeTree(),
            JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        ));

        // Layer 3: EvaluationStrategyHash (operator + weights + threshold)
        $layer3 = hash('sha256', json_encode([
            'operator'  => $this->operator->value,
            'weights'   => $this->weights,
            'threshold' => $this->threshold,
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        // Composite fingerprint: all three layers
        return hash('sha256', $layer1 . '|' . $layer2 . '|' . $layer3);
    }

    /**
     * Already normalized in constructor; return self.
     */
    public function normalize(): self
    {
        return $this;
    }

    public function description(): string
    {
        $ruleDescriptions = array_map(fn(QuorumRule $r) => $r->description(), $this->normalizedRules);
        $operator = $this->operator->value;
        return "{$operator}(" . implode(', ', $ruleDescriptions) . ')';
    }

    /**
     * Flatten same-operator nested composites into a flat list.
     *
     * AND(AND(A, B), C) → AND(A, B, C)
     *
     * @param QuorumRule[] $rules
     * @return QuorumRule[] Flattened rules
     */
    private function flattenSameOperator(array $rules, QuorumOperator $operator): array
    {
        $flattened = [];
        foreach ($rules as $rule) {
            // If this is a CompositeQuorumRule with same operator, merge its children
            if ($rule instanceof self && $rule->operator === $operator) {
                foreach ($rule->normalizedRules as $child) {
                    $flattened[] = $child;
                }
            } else {
                $flattened[] = $rule;
            }
        }
        return $flattened;
    }

    /**
     * Serialize normalized tree for fingerprinting.
     *
     * @return array Tree structure as nested array
     */
    private function serializeTree(): array
    {
        return [
            'type'      => 'CompositeQuorumRule',
            'operator'  => $this->operator->value,
            'children'  => array_map(fn(QuorumRule $r) => [
                'type'     => get_class($r),
                'identity' => $r->identity(),
            ], $this->normalizedRules),
            'weights'   => $this->weights,
            'threshold' => $this->threshold,
        ];
    }
}
