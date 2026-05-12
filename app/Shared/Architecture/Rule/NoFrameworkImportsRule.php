<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Rule;

final class NoFrameworkImportsRule implements ArchitectureRule
{
    public function name(): string
    {
        return 'no_framework_imports';
    }

    public function category(): RuleCategory
    {
        return RuleCategory::DOMAIN_PURITY;
    }

    public function severity(): RuleSeverity
    {
        return RuleSeverity::ERROR;
    }

    public function check(array $config): RuleResult
    {
        $path = $config['path'] ?? null;
        $excludedPrefixes = $config['excluded_prefixes'] ?? [];
        $forbidden = $config['forbidden_patterns'] ?? [
            'Illuminate\\',
            'Laravel\\',
            'Eloquent',
            'Carbon\\',
        ];

        if ($path === null || !is_dir($path)) {
            return new RuleResult($this->name(), $this->severity(), $this->category());
        }

        $violations = [];
        $discovery = new \App\Shared\Architecture\Services\FileDiscoveryService();

        $files = $discovery->getPhpFiles($path);

        if ($excludedPrefixes !== []) {
            $files = $discovery->excludePaths($files, $excludedPrefixes);
        }

        foreach ($files as $file) {
            $content = file_get_contents($file->getRealPath());
            foreach ($forbidden as $pattern) {
                if (preg_match('~use\s+' . preg_quote($pattern, '~') . '~', $content)) {
                    $violations[] = sprintf('%s imports forbidden: %s', $file->getPathname(), $pattern);
                }
            }
        }

        return new RuleResult($this->name(), $this->severity(), $this->category(), $violations);
    }
}
