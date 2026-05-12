<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Services;

final class ImportScanner
{
    /**
     * Scan file content for forbidden use-import patterns.
     *
     * @param string $content File contents
     * @param string[] $forbiddenPatterns Fully qualified class prefixes to search for
     * @return string[] Matched patterns that were found
     */
    public function findForbiddenImports(string $content, array $forbiddenPatterns): array
    {
        $found = [];
        foreach ($forbiddenPatterns as $forbidden) {
            if (preg_match('~use\s+' . preg_quote($forbidden, '~') . '~', $content)) {
                $found[] = $forbidden;
            }
        }
        return $found;
    }

    /**
     * Check if content contains any of the given patterns (non-import matching).
     *
     * @param string $content
     * @param string[] $patterns
     * @return string[] Matched patterns
     */
    public function containsAny(string $content, array $patterns): array
    {
        $found = [];
        foreach ($patterns as $pattern) {
            if (str_contains($content, $pattern)) {
                $found[] = $pattern;
            }
        }
        return $found;
    }

    /**
     * Check if content matches a regex pattern.
     */
    public function matchesRegex(string $content, string $pattern): bool
    {
        return (bool) preg_match($pattern, $content);
    }
}
