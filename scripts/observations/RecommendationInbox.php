<?php

declare(strict_types=1);

/**
 * Recommendation inbox — pure classification of recommendations awaiting a
 * developer decision. Lists only; never decides, never infers.
 *
 * Semantics match the Loop Completion monitor: a recommendation is OPEN when
 * no decision exists for it (the latest decision classifies re-decided ones);
 * DEFERRED surfaces separately as awaiting re-decision.
 */
final class RecommendationInbox
{
    /**
     * @param array<int,array<string,mixed>> $recommendations
     * @param array<int,array<string,mixed>> $decisionRecords decisions.jsonl rows (decision + rationale types mixed)
     * @return array{needs_decision: array<int,array<string,mixed>>, deferred: array<int,array<string,mixed>>}
     */
    public static function classify(array $recommendations, array $decisionRecords): array
    {
        $latest = [];
        foreach ($decisionRecords as $r) {
            if (($r['type'] ?? '') === 'decision' && isset($r['recommendation_id'])) {
                $latest[$r['recommendation_id']] = $r['decision'];
            }
        }

        $byTs = static fn (array $a, array $b): int => strcmp((string) $a['ts'], (string) $b['ts']);

        $open = array_values(array_filter($recommendations, fn ($r) => !isset($latest[$r['id']])));
        usort($open, $byTs);

        $deferred = array_values(array_filter($recommendations, fn ($r) => ($latest[$r['id']] ?? null) === 'DEFERRED'));
        usort($deferred, $byTs);

        return ['needs_decision' => $open, 'deferred' => $deferred];
    }
}
