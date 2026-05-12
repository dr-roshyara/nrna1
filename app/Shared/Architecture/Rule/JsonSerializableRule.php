<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Rule;

final class JsonSerializableRule implements ArchitectureRule
{
    public function name(): string
    {
        return 'json_serializable_dto';
    }

    public function category(): RuleCategory
    {
        return RuleCategory::API_CONTRACT;
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
            if (!str_contains($content, 'implements \\JsonSerializable')
                && !str_contains($content, 'implements JsonSerializable')
            ) {
                $violations[] = sprintf('%s must implement JsonSerializable', $file->getPathname());
            }
        }

        return new RuleResult($this->name(), $this->severity(), $this->category(), $violations);
    }
}
