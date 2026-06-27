<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Rule;

final class FinalClassRule implements ArchitectureRule
{
    public function name(): string
    {
        return 'final_class';
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
        $excludedBasenames = $config['excluded_basenames'] ?? [];

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
            if (in_array($file->getBasename(), $excludedBasenames, true)) {
                continue;
            }

            $content = file_get_contents($file->getRealPath());

            if (!preg_match('/^(abstract\s+)?(class|interface)\s+/m', $content)) {
                continue; // skip files without class/interface declarations
            }

            $isFinal = str_contains($content, 'final class')
                || str_contains($content, 'final readonly class')
                || str_contains($content, 'interface ');

            if (!$isFinal) {
                $violations[] = sprintf('%s is not final', $file->getPathname());
            }
        }

        return new RuleResult($this->name(), $this->severity(), $this->category(), $violations);
    }
}
