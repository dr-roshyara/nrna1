<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Runner;

use App\Shared\Architecture\Rule\ArchitectureRule;
use App\Shared\Architecture\Rule\RuleResult;
use App\Shared\Architecture\Rule\RuleSeverity;

final class RuleRunner
{
    /**
     * @param ArchitectureRule[] $rules
     * @param array<string, mixed> $sharedConfig Shared config passed to every rule
     * @return RuleResult[]
     */
    public function run(array $rules, array $sharedConfig = []): array
    {
        $results = [];
        foreach ($rules as $rule) {
            $config = $sharedConfig[$rule->name()] ?? [];
            $results[] = $rule->check($config);
        }
        return $results;
    }

    /**
     * Run only rules meeting a minimum severity threshold.
     *
     * @param ArchitectureRule[] $rules
     * @return RuleResult[]
     */
    public function runWithMinimumSeverity(array $rules, RuleSeverity $minSeverity, array $sharedConfig = []): array
    {
        $results = [];
        foreach ($rules as $rule) {
            if ($rule->severity()->value >= $minSeverity->value) {
                $config = $sharedConfig[$rule->name()] ?? [];
                $results[] = $rule->check($config);
            }
        }
        return $results;
    }

    /**
     * Format failed results into a human-readable string for test failure messages.
     *
     * @param RuleResult[] $results
     */
    public function formatFailures(array $results): string
    {
        $lines = [];
        foreach ($results as $result) {
            if ($result->failed()) {
                $lines[] = sprintf(
                    '[%s] %s (%s): %d violation(s)',
                    $result->severity->name,
                    $result->ruleName,
                    $result->category->value,
                    $result->violationCount(),
                );
                foreach ($result->violations as $violation) {
                    $lines[] = "  - {$violation}";
                }
            }
        }
        return implode("\n", $lines);
    }

    /**
     * @param RuleResult[] $results
     * @return RuleResult[]
     */
    public function failedResults(array $results): array
    {
        return array_values(array_filter($results, fn (RuleResult $r) => $r->failed()));
    }
}
