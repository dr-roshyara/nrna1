<?php

declare(strict_types=1);

namespace App\Domain\Voting\QuorumRule;

/**
 * QuorumOperator — Sealed Algebra Primitive
 *
 * SEMANTIC GUARANTEES (non-negotiable):
 *
 * AND
 *   Logic: All child rules must evaluate to true
 *   Semantics: Logical conjunction
 *   Determinism: No ordering dependency; all rules must pass
 *
 * OR
 *   Logic: At least one child rule must evaluate to true
 *   Semantics: Logical disjunction
 *   Determinism: No ordering dependency; short-circuit on first pass
 *
 * WEIGHTED
 *   Logic: Accumulate integer weights where rule=true, compare to threshold
 *   Formula: weightedSum = Σ(weight_i × (rule_i ? 1 : 0)) >= threshold
 *   Semantics: Deterministic linear algebra
 *   ⚠️ NOT probabilistic, NOT priority-based, NOT heuristic
 *   Determinism: Pure integer arithmetic; no floats, no rounding
 *
 * ORDERING:
 *   All composition ordering is EXCLUSIVELY identity-based (identity())
 *   No secondary ordering systems exist
 */
enum QuorumOperator: string
{
    case AND      = 'AND';
    case OR       = 'OR';
    case WEIGHTED = 'WEIGHTED';
}
