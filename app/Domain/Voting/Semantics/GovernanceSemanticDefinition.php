<?php

declare(strict_types=1);

namespace App\Domain\Voting\Semantics;

use App\Domain\Voting\QuorumRule\QuorumRule;

/**
 * GovernanceSemanticDefinition — Semantic Governance Intent Layer
 *
 * Separates governance MEANING (semantic layer) from rule STRUCTURE (algebra layer).
 *
 * Key Contracts:
 * 1. semanticIdentity() stable across structural variation with same meaning
 * 2. Different intents produce different semantic identities
 * 3. Immutable after construction (readonly class)
 * 4. Intent and category are constitutional metadata, not evaluation strategy
 * 5. Structural fingerprint captures HOW, semantic identity captures WHY
 *
 * Critical Architectural Principle:
 *   Structure != Meaning
 *
 * Example:
 *   STRUCTURE: MinimumBasisPointQuorumRule(5000)
 *   MEANING: ORDINARY_MOTION with simple majority semantics
 *
 * These are orthogonal concepts that must remain distinct.
 */
final readonly class GovernanceSemanticDefinition
{
    private function __construct(
        private QuorumRule $rule,
        private SemanticIntent $semanticIntent,
        private SemanticCategory $semanticCategory,
        private string $description,
    ) {}

    /**
     * Factory: Create semantic definition from governance intent + rule.
     */
    public static function create(
        QuorumRule $rule,
        SemanticIntent $intent,
        SemanticCategory $category,
        string $description,
    ): self {
        return new self($rule, $intent, $category, $description);
    }

    /**
     * Semantic identity — stable hash of meaning, not structure.
     *
     * Includes:
     *   - Constitutional intent (WHY)
     *   - Category classification
     *   - Semantic schema version
     *
     * Does NOT include:
     *   - Structural rule details
     *   - Description variations
     *   - Implementation order
     *
     * Remains stable across:
     *   - Child rule ordering
     *   - Structural recompilation
     *   - Alias normalization
     *
     * Changes when:
     *   - Intent changes
     *   - Category changes
     *   - Semantic schema evolves
     */
    public function semanticIdentity(): string
    {
        return hash('sha256', json_encode([
            'semantic_intent'    => $this->semanticIntent->value,
            'semantic_category'  => $this->semanticCategory->value,
            'semantic_schema'    => '1.0',
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Structural fingerprint — captures HOW, not WHY.
     *
     * Includes rule structure for audit trails.
     *
     * Changes when:
     *   - Rule structure changes
     *   - Normalization varies
     *   - Description changes
     *
     * Does NOT affect semantic identity.
     */
    public function structuralFingerprint(): string
    {
        return hash('sha256', json_encode([
            'rule_fingerprint' => $this->rule instanceof \App\Domain\Voting\QuorumRule\CompositeQuorumRule
                ? $this->rule->fingerprint()
                : $this->rule->identity(),
            'description'      => $this->description,
            'structural_schema' => '1.0',
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Get the constitutional intent (WHY).
     */
    public function intent(): SemanticIntent
    {
        return $this->semanticIntent;
    }

    /**
     * Get the rule category (HOW).
     */
    public function category(): SemanticCategory
    {
        return $this->semanticCategory;
    }

    /**
     * Get the human-readable description.
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * Get the underlying quorum rule (structural layer).
     */
    public function rule(): QuorumRule
    {
        return $this->rule;
    }

    /**
     * Human-readable representation of this semantic definition.
     */
    public function __toString(): string
    {
        return "{$this->semanticIntent->value} ({$this->semanticCategory->value}): {$this->description}";
    }
}
