<?php

declare(strict_types=1);

namespace App\Domain\Voting\QuorumRule\Evaluation;

/**
 * CompositeQuorumEvaluationResult — Immutable Quorum Evaluation Outcome
 *
 * Captures the complete evaluation state including:
 * - Whether quorum was met (boolean outcome)
 * - Rule fingerprint (for replay verification)
 * - Participation state (actual vs eligible)
 * - Evaluation trace (per-rule outcomes for audit)
 *
 * Contract:
 * - All properties readonly (immutable after construction)
 * - fingerprint() distinct from rule.fingerprint() (adds outcome layer)
 * - evaluationTrace is audit-safe (read-only for logging)
 */
final readonly class CompositeQuorumEvaluationResult
{
    public function __construct(
        public bool   $quorumMet,
        public string $ruleFingerprint,
        public int    $actualParticipation,
        public int    $eligibleCount,
        public string $operatorUsed,
        public array  $evaluationTrace,
    ) {}

    /**
     * Outcome-level fingerprint for evaluation verification.
     *
     * Includes outcome layer (Layer 4) that rule.fingerprint() reserves.
     * Combines rule fingerprint + participation state + quorum decision.
     *
     * NOT equal to rule.fingerprint() — adds evaluation result layer.
     */
    public function fingerprint(): string
    {
        return hash('sha256', json_encode([
            'rule_fingerprint'      => $this->ruleFingerprint,
            'actual_participation'  => $this->actualParticipation,
            'eligible_count'        => $this->eligibleCount,
            'quorum_met'            => $this->quorumMet,
            'operator_used'         => $this->operatorUsed,
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Audit-safe description of evaluation result.
     *
     * Includes participation numbers and whether quorum passed.
     */
    public function description(): string
    {
        $status = $this->quorumMet ? 'PASSED' : 'FAILED';
        return "{$status} ({$this->actualParticipation}/{$this->eligibleCount} participated) [{$this->operatorUsed}]";
    }
}
