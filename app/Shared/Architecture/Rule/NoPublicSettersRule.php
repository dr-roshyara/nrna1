<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Rule;

final class NoPublicSettersRule implements ArchitectureRule
{
    public function name(): string
    {
        return 'no_public_setters';
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
        $forbiddenPatterns = $config['forbidden_patterns'] ?? ['public function set'];

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
            foreach ($forbiddenPatterns as $pattern) {
                if (str_contains($content, $pattern)) {
                    $violations[] = sprintf('%s contains forbidden pattern: %s', $file->getPathname(), $pattern);
                    break;
                }
            }
        }

        return new RuleResult($this->name(), $this->severity(), $this->category(), $violations);
    }
}
