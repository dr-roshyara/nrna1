<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Rule;

final readonly class RuleResult implements \JsonSerializable
{
    public function __construct(
        public string $ruleName,
        public RuleSeverity $severity,
        public RuleCategory $category,
        public array $violations = [],
    ) {}

    public function passed(): bool
    {
        return $this->violations === [];
    }

    public function failed(): bool
    {
        return !$this->passed();
    }

    public function violationCount(): int
    {
        return count($this->violations);
    }

    public function jsonSerialize(): array
    {
        return [
            'rule' => $this->ruleName,
            'severity' => $this->severity->name,
            'category' => $this->category->value,
            'passed' => $this->passed(),
            'violations' => $this->violations,
            'violation_count' => $this->violationCount(),
        ];
    }
}
