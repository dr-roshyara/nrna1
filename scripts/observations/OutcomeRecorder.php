<?php

declare(strict_types=1);

/**
 * Outcome Recorder (Phase 4) — RAW evidence only.
 *
 * Joins a recommendation and its decision to the metric reality N commits
 * later: baseline, current, delta, commits since. It never judges —
 * "did the outcome support the recommendation?" is the ASSESSMENT stage's
 * question (deterministic first, statistical later), pinned by test here.
 */
final class OutcomeRecorder
{
    /** @return array<string,mixed> raw outcome record */
    public static function record(
        string $recommendationId,
        string $decisionRef,
        string $metric,
        string $subject,
        float|int $baseline,
        float|int $current,
        int $commitsSince
    ): array {
        if ($commitsSince < 1) {
            throw new \InvalidArgumentException(
                'outcome recording is premature: 0 commits since the decision — the subject cannot have changed; re-run after real work'
            );
        }

        return [
            'recommendation_id'      => $recommendationId,
            'decision_ref'           => $decisionRef,
            'metric'                 => $metric,
            'subject'                => $subject,
            'baseline'               => $baseline,
            'current'                => $current,
            'delta'                  => $current - $baseline,
            'commits_since_decision' => $commitsSince,
            'ts'                     => date('c'),
        ];
    }
}
