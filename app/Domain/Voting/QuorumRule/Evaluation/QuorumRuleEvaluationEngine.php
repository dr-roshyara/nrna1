<?php

declare(strict_types=1);

namespace App\Domain\Voting\QuorumRule\Evaluation;

use App\Domain\Voting\QuorumRule\CompositeQuorumRule;
use App\Domain\Voting\QuorumRule\QuorumRule;
use App\Domain\Voting\ValueObject\EligibilitySnapshot;

/**
 * QuorumRuleEvaluationEngine — Deterministic Quorum Rule Executor
 *
 * Orchestrates rule evaluation with audit trail generation.
 *
 * Responsibilities:
 * - Execute CompositeQuorumRule against eligibility/participation state
 * - Build per-rule evaluation trace for audit logging
 * - Return immutable result with fingerprint for replay verification
 *
 * Pure function: no side effects, deterministic.
 */
final class QuorumRuleEvaluationEngine
{
    /**
     * Execute quorum rule and produce immutable result with audit trail.
     */
    public function execute(
        CompositeQuorumRule $rule,
        EligibilitySnapshot $eligibility,
        int $actualParticipation,
    ): CompositeQuorumEvaluationResult {
        $met = $rule->evaluate($eligibility, $actualParticipation);
        $trace = $this->buildTrace($rule, $eligibility, $actualParticipation);

        return new CompositeQuorumEvaluationResult(
            quorumMet:           $met,
            ruleFingerprint:     $rule->fingerprint(),
            actualParticipation: $actualParticipation,
            eligibleCount:       $eligibility->count(),
            operatorUsed:        $this->getOperatorDescription($rule),
            evaluationTrace:     $trace,
        );
    }

    /**
     * Build per-rule evaluation trace for audit logging.
     *
     * Recursively walks rule tree recording each rule's outcome.
     * For composites, includes child rule results.
     *
     * @return array<array{rule_type: string, rule_identity: string, description: string, result: bool, children?: array}>
     */
    private function buildTrace(
        QuorumRule $rule,
        EligibilitySnapshot $eligibility,
        int $actualParticipation,
    ): array {
        if ($rule instanceof CompositeQuorumRule) {
            return $this->buildCompositeTrace($rule, $eligibility, $actualParticipation);
        }

        return [
            [
                'rule_type' => $this->getRuleType($rule),
                'rule_identity' => $rule->identity(),
                'description' => $rule->description(),
                'result' => $rule->evaluate($eligibility, $actualParticipation),
            ],
        ];
    }

    /**
     * Build trace for composite rules, including child evaluations.
     *
     * @return array<array{rule_type: string, rule_identity: string, operator: string, result: bool, children: array}>
     */
    private function buildCompositeTrace(
        CompositeQuorumRule $rule,
        EligibilitySnapshot $eligibility,
        int $actualParticipation,
    ): array {
        $childTraces = [];

        // Recursively evaluate each child rule via reflection
        $rulesProperty = (new \ReflectionClass($rule))->getProperty('normalizedRules');
        $rulesProperty->setAccessible(true);
        $normalizedRules = $rulesProperty->getValue($rule);

        foreach ($normalizedRules as $childRule) {
            $childTraces[] = $this->buildTrace($childRule, $eligibility, $actualParticipation);
        }

        return [
            [
                'rule_type' => 'CompositeQuorumRule',
                'rule_identity' => $rule->identity(),
                'operator' => $this->getOperatorDescription($rule),
                'description' => $rule->description(),
                'result' => $rule->evaluate($eligibility, $actualParticipation),
                'children' => array_merge(...$childTraces),
            ],
        ];
    }

    /**
     * Get human-readable rule type name.
     */
    private function getRuleType(QuorumRule $rule): string
    {
        $class = get_class($rule);
        return class_basename($class);
    }

    /**
     * Get operator description for composite rules.
     */
    private function getOperatorDescription(CompositeQuorumRule $rule): string
    {
        // Extract operator via reflection (it's a readonly private property)
        $operatorProperty = (new \ReflectionClass($rule))->getProperty('operator');
        $operatorProperty->setAccessible(true);
        $operator = $operatorProperty->getValue($rule);

        return $operator->value;
    }
}
