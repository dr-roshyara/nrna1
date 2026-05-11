<?php

declare(strict_types=1);

namespace App\Domain\Voting\QuorumRule;

use App\Domain\Voting\ValueObject\EligibilitySnapshot;

/**
 * QuorumRule — Algebra Element Contract
 *
 * A quorum rule is an immutable, deterministic evaluator.
 *
 * Contract:
 * - evaluate() is a pure function (same inputs → same output)
 * - identity() is stable across instances (same rule type + args → same hash)
 * - normalize() returns canonical form (idempotent)
 *
 * Ordering:
 * - Rules are ordered ONLY by identity() in composite contexts
 * - No secondary ordering systems exist
 */
interface QuorumRule
{
    /**
     * Evaluate rule against current participation state.
     *
     * @param EligibilitySnapshot $eligibility Immutable eligible voter snapshot
     * @param int $actualParticipation Actual vote count (already filtered by eligibility)
     * @return bool True if rule is satisfied, false otherwise
     */
    public function evaluate(EligibilitySnapshot $eligibility, int $actualParticipation): bool;

    /**
     * Human-readable description of this rule.
     *
     * @return string Description suitable for audit logs and UI
     */
    public function description(): string;

    /**
     * Stable node-level identity hash.
     *
     * Must be:
     * - Deterministic: same rule type + args → identical hash
     * - Canonical: includes schema_version, normalization_version info
     * - Collision-resistant: different rules → different hashes
     *
     * Used ONLY for:
     * - Composite sorting (identity-based ordering)
     * - Replay safety (version tracking)
     *
     * NOT used for:
     * - Evaluation semantics (that's evaluate())
     * - Fingerprinting (that's composite-level concern)
     *
     * @return string Hex hash (SHA256 or equivalent)
     */
    public function identity(): string;

    /**
     * Canonical form of this rule (normalization).
     *
     * For atomic rules, returns $this (already canonical).
     * For composite rules, returns normalized tree.
     *
     * Normalization includes:
     * - Flattening same-operator nested composites
     * - Sorting children by identity()
     * - Freezing structure
     *
     * @return self Normalized rule (may be new instance for composites, $this for atoms)
     */
    public function normalize(): self;
}
