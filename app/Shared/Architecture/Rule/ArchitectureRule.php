<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Rule;

interface ArchitectureRule
{
    public function name(): string;

    public function category(): RuleCategory;

    public function severity(): RuleSeverity;

    /**
     * @param array<string, mixed> $config Rule-specific configuration.
     * @return RuleResult
     */
    public function check(array $config): RuleResult;
}
