<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Rule;

final class DependencyDirectionRule implements ArchitectureRule
{
    public function name(): string
    {
        return 'dependency_direction';
    }

    public function category(): RuleCategory
    {
        return RuleCategory::DEPENDENCY;
    }

    public function severity(): RuleSeverity
    {
        return RuleSeverity::ERROR;
    }

    /**
     * Enforce layer dependency direction within a bounded context.
     *
     * Config:
     *   - `domain_path` string: path to Domain directory
     *   - `application_path` string: path to Application directory
     *   - `context_namespace` string: e.g. "App\\Contexts\\Geography"
     *   - `allowed_application_dependencies` string[]: additional namespaces Application may import
     *   - `allowed_domain_dependencies` string[]: additional namespaces Domain may import
     *   - `excluded_prefixes` string[]: files to exclude from scanning
     *
     * Rules:
     *   - Domain may only import: itself, App\Shared, and explicitly allowed namespaces
     *   - Application may import: Domain, itself, App\Shared, and explicitly allowed namespaces
     *   - Application must NOT import: Infrastructure
     */
    public function check(array $config): RuleResult
    {
        $violations = [];
        $discovery = new \App\Shared\Architecture\Services\FileDiscoveryService();

        $domainPath = $config['domain_path'] ?? null;
        $applicationPath = $config['application_path'] ?? null;
        $contextNamespace = $config['context_namespace'] ?? '';
        $excludedPrefixes = $config['excluded_prefixes'] ?? [];

        // Check Domain: should not import Application or Infrastructure
        if ($domainPath !== null && is_dir($domainPath) && $contextNamespace !== '') {
            $files = $discovery->getPhpFiles($domainPath);
            if ($excludedPrefixes !== []) {
                $files = $discovery->excludePaths($files, $excludedPrefixes);
            }

            foreach ($files as $file) {
                $content = file_get_contents($file->getRealPath());
                $appNs = $contextNamespace . '\\Application\\';
                $infraNs = $contextNamespace . '\\Infrastructure\\';

                if (preg_match('~use\s+' . preg_quote($appNs, '~') . '~', $content)) {
                    $violations[] = sprintf('%s: Domain must not import Application namespace', $file->getPathname());
                }
                if (preg_match('~use\s+' . preg_quote($infraNs, '~') . '~', $content)) {
                    $violations[] = sprintf('%s: Domain must not import Infrastructure namespace', $file->getPathname());
                }
            }
        }

        // Check Application: should not import Infrastructure (allowed: Domain, Shared, other allowed)
        if ($applicationPath !== null && is_dir($applicationPath) && $contextNamespace !== '') {
            $files = $discovery->getPhpFiles($applicationPath);

            foreach ($files as $file) {
                $content = file_get_contents($file->getRealPath());
                $infraNs = $contextNamespace . '\\Infrastructure\\';

                if (preg_match('~use\s+' . preg_quote($infraNs, '~') . '~', $content)) {
                    $violations[] = sprintf('%s: Application must not import Infrastructure namespace', $file->getPathname());
                }
            }
        }

        return new RuleResult($this->name(), $this->severity(), $this->category(), $violations);
    }
}
