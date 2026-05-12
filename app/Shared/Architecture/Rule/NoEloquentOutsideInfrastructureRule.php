<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Rule;

final class NoEloquentOutsideInfrastructureRule implements ArchitectureRule
{
    public function name(): string
    {
        return 'no_eloquent_outside_infrastructure';
    }

    public function category(): RuleCategory
    {
        return RuleCategory::DEPENDENCY;
    }

    public function severity(): RuleSeverity
    {
        return RuleSeverity::BLOCKER;
    }

    public function check(array $config): RuleResult
    {
        $paths = $config['paths'] ?? [];
        $excludedPrefixes = $config['excluded_prefixes'] ?? [];
        $forbidden = $config['forbidden_patterns'] ?? [
            'extends Model',
            'Illuminate\\Database\\Eloquent',
        ];

        if ($paths === []) {
            return new RuleResult($this->name(), $this->severity(), $this->category());
        }

        $violations = [];
        $discovery = new \App\Shared\Architecture\Services\FileDiscoveryService();

        foreach ($paths as $path) {
            if (!is_dir($path)) {
                continue;
            }

            $files = $discovery->getPhpFiles($path);
            if ($excludedPrefixes !== []) {
                $files = $discovery->excludePaths($files, $excludedPrefixes);
            }

            foreach ($files as $file) {
                $content = file_get_contents($file->getRealPath());
                foreach ($forbidden as $pattern) {
                    if (preg_match('~' . preg_quote($pattern, '~') . '~', $content)) {
                        $violations[] = sprintf('%s uses Eloquent: %s', $file->getPathname(), $pattern);
                        break;
                    }
                }
            }
        }

        return new RuleResult($this->name(), $this->severity(), $this->category(), $violations);
    }
}
