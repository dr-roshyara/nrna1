<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Rule;

final class ReadonlyValueObjectRule implements ArchitectureRule
{
    public function name(): string
    {
        return 'readonly_value_object';
    }

    public function category(): RuleCategory
    {
        return RuleCategory::IMMUTABILITY;
    }

    public function severity(): RuleSeverity
    {
        return RuleSeverity::ERROR;
    }

    public function check(array $config): RuleResult
    {
        $path = $config['path'] ?? null;
        $excludedBasenames = $config['excluded_basenames'] ?? [];

        if ($path === null || !is_dir($path)) {
            return new RuleResult($this->name(), $this->severity(), $this->category());
        }

        $violations = [];
        $discovery = new \App\Shared\Architecture\Services\FileDiscoveryService();

        foreach ($discovery->getPhpFiles($path) as $file) {
            if (in_array($file->getBasename(), $excludedBasenames, true)) {
                continue;
            }

            $content = file_get_contents($file->getRealPath());

            $isReadonly = str_contains($content, 'readonly class')
                || str_contains($content, 'enum ')
                || str_contains($content, 'interface ');

            if (!$isReadonly) {
                $violations[] = sprintf('%s must be readonly', $file->getPathname());
            }
        }

        return new RuleResult($this->name(), $this->severity(), $this->category(), $violations);
    }
}
