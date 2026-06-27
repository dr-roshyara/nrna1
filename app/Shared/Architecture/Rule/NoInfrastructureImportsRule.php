<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Rule;

final class NoInfrastructureImportsRule implements ArchitectureRule
{
    public function name(): string
    {
        return 'no_infrastructure_imports';
    }

    public function category(): RuleCategory
    {
        return RuleCategory::DEPENDENCY;
    }

    public function severity(): RuleSeverity
    {
        return RuleSeverity::ERROR;
    }

    public function check(array $config): RuleResult
    {
        $path = $config['path'] ?? null;
        $contextNamespace = $config['context_namespace'] ?? null;
        $excludedPrefixes = $config['excluded_prefixes'] ?? [];

        if ($path === null || $contextNamespace === null || !is_dir($path)) {
            return new RuleResult($this->name(), $this->severity(), $this->category());
        }

        $infraPrefix = $contextNamespace . '\\Infrastructure\\';
        $violations = [];
        $discovery = new \App\Shared\Architecture\Services\FileDiscoveryService();

        $files = $discovery->getPhpFiles($path);
        if ($excludedPrefixes !== []) {
            $files = $discovery->excludePaths($files, $excludedPrefixes);
        }

        foreach ($files as $file) {
            $content = file_get_contents($file->getRealPath());
            if (preg_match('~use\s+' . preg_quote($infraPrefix, '~') . '~', $content)) {
                $violations[] = sprintf('%s imports infrastructure namespace %s', $file->getPathname(), $contextNamespace);
            }
        }

        return new RuleResult($this->name(), $this->severity(), $this->category(), $violations);
    }
}
