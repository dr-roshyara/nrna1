<?php

declare(strict_types=1);

/**
 * Assessment Service v1 (deterministic) — the interpretation stage.
 *
 * Consumes a raw OUTCOME record and answers: did the outcome support the
 * recommendation? Verdicts are a CLOSED set scoped to recommendation
 * effectiveness: SUPPORTED · PARTIALLY_SUPPORTED · NOT_SUPPORTED ·
 * INCONCLUSIVE. Deterministic thresholds — no AI, no learning; Python
 * arrives only when hundreds of these exist.
 *
 * Metric direction v1: lower-is-better (LCOM4/CBO class). Extend per metric
 * on evidence of need.
 */
final class AssessmentService
{
    private const MIN_COMMITS = 3;              // fewer => the subject barely had a chance to change
    private const SUPPORTED_IMPROVEMENT = 0.20; // >= 20% improvement
    private const PARTIAL_IMPROVEMENT = 0.02;   // >= 2% improvement

    /** @param array<string,mixed> $outcome @return array<string,mixed> assessment record */
    public static function evaluate(array $outcome): array
    {
        $baseline = (float) $outcome['baseline'];
        $current = (float) $outcome['current'];
        $commits = (int) $outcome['commits_since_decision'];
        $improvement = $baseline > 0 ? ($baseline - $current) / $baseline : 0.0;

        if ($commits < self::MIN_COMMITS) {
            $verdict = 'INCONCLUSIVE';
            $basis = sprintf('only %d commits since decision (min %d) — the subject barely had a chance to change', $commits, self::MIN_COMMITS);
        } elseif ($improvement >= self::SUPPORTED_IMPROVEMENT) {
            $verdict = 'SUPPORTED';
            $basis = sprintf('%.0f%% improvement over %d commits', $improvement * 100, $commits);
        } elseif ($improvement >= self::PARTIAL_IMPROVEMENT) {
            $verdict = 'PARTIALLY_SUPPORTED';
            $basis = sprintf('%.0f%% improvement — real but small', $improvement * 100);
        } elseif ($improvement < 0) {
            $verdict = 'NOT_SUPPORTED';
            $basis = sprintf('metric worsened by %.0f%%', abs($improvement) * 100);
        } else {
            $verdict = 'INCONCLUSIVE';
            $basis = 'no meaningful change';
        }

        return [
            'recommendation_id' => $outcome['recommendation_id'],
            'decision_ref'      => $outcome['decision_ref'],
            'metric'            => $outcome['metric'],
            'subject'           => $outcome['subject'],
            'verdict'           => $verdict,
            'basis'             => $basis,
            'improvement_ratio' => round($improvement, 4),
            'ts'                => date('c'),
        ];
    }
}
