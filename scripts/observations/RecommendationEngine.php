<?php

declare(strict_types=1);

/**
 * Recommendation Engine v1 — deterministic, pure, advisory.
 *
 * evaluate(facts, rules) → recommendation drafts. Rules are DATA
 * (recommendation-rules.yaml); the engine knows no tool, no metric
 * source, no threshold philosophy. It RECOMMENDS — the developer
 * DECIDES (three-level separation, enforced by construction and test).
 *
 * Capability note (approved plan 20260804-1200): this is today's
 * implementation of the Recommendation capability (business sense).
 * Spike-level ownership: engineering observation tooling.
 */
final class RecommendationEngine
{
    /**
     * @param array<string,list<array<string,mixed>>> $facts  source => fact rows
     * @param list<array<string,mixed>> $rules
     * @return list<array<string,mixed>> recommendation records (status: issued)
     */
    public static function evaluate(array $facts, array $rules): array
    {
        $recommendations = [];
        foreach ($rules as $rule) {
            foreach ($facts[$rule['source']] ?? [] as $fact) {
                if (!self::matches($rule, $fact)) {
                    continue;
                }
                $subject = (string) ($fact['subject'] ?? '(unknown)');
                $recommendations[] = [
                    'id'            => 'REC-' . substr(sha1($rule['id'] . '|' . $subject), 0, 10),
                    'rule'          => $rule['id'],
                    'subject'       => $subject,
                    'evidence_refs' => $fact['evidence_refs'] ?? [$rule['source']],
                    'text'          => strtr($rule['recommend'], [
                        '{subject}' => $subject,
                        '{value}'   => (string) ($fact['value'] ?? ''),
                    ]),
                    'ts'            => date('c'),
                    'status'        => 'issued',
                ];
            }
        }
        return $recommendations;
    }

    /** @param array<string,mixed> $rule @param array<string,mixed> $fact */
    private static function matches(array $rule, array $fact): bool
    {
        return match ($rule['op']) {
            '>'    => (float) ($fact['value'] ?? 0) > (float) $rule['threshold'],
            '>='   => (float) ($fact['value'] ?? 0) >= (float) $rule['threshold'],
            'flag' => (bool) ($fact[$rule['field'] ?? 'flag'] ?? false) === true,
            default => false,
        };
    }
}
