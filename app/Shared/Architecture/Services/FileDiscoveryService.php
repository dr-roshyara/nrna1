<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Services;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final class FileDiscoveryService
{
    /**
     * @return iterable<SplFileInfo>
     */
    public function getPhpFiles(string $path): iterable
    {
        if (!is_dir($path)) {
            return [];
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php') {
                yield $file;
            }
        }
    }

    /**
     * @param iterable<SplFileInfo> $files
     * @param string[] $excludedPrefixes Normalized directory/file prefixes to exclude
     * @return iterable<SplFileInfo>
     */
    public function excludePaths(iterable $files, array $excludedPrefixes): iterable
    {
        foreach ($files as $file) {
            $normalized = str_replace('\\', '/', $file->getRealPath());

            $keep = true;
            foreach ($excludedPrefixes as $prefix) {
                if (str_contains($normalized, $prefix)) {
                    $keep = false;
                    break;
                }
            }

            if ($keep) {
                yield $file;
            }
        }
    }

    /**
     * @param iterable<SplFileInfo> $files
     * @param string[] $excludedBasenames File basenames to exclude
     * @return iterable<SplFileInfo>
     */
    public function excludeBasenames(iterable $files, array $excludedBasenames): iterable
    {
        foreach ($files as $file) {
            if (!in_array($file->getBasename(), $excludedBasenames, true)) {
                yield $file;
            }
        }
    }
}
