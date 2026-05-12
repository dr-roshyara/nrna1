<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Rule;

final class ImmutableDtoRule implements ArchitectureRule
{
    public function name(): string
    {
        return 'immutable_dto';
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
        $excludePatterns = $config['exclude_patterns'] ?? [];

        if ($path === null || !is_dir($path)) {
            return new RuleResult($this->name(), $this->severity(), $this->category());
        }

        $violations = [];
        $discovery = new \App\Shared\Architecture\Services\FileDiscoveryService();

        foreach ($discovery->getPhpFiles($path) as $file) {
            $skip = false;
            foreach ($excludePatterns as $pattern) {
                if (str_contains($file->getFilename(), $pattern)) {
                    $skip = true;
                    break;
                }
            }
            if ($skip) {
                continue;
            }

            $content = file_get_contents($file->getRealPath());
            if (!str_contains($content, 'final readonly class')) {
                $violations[] = sprintf('%s must be final readonly', $file->getPathname());
            }
        }

        return new RuleResult($this->name(), $this->severity(), $this->category(), $violations);
    }
}
