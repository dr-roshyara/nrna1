<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Parser;

use App\Shared\Architecture\Services\FileDiscoveryService;
use SplFileInfo;

final class SemanticIndexBuilder
{
    public function __construct(
        private readonly PhpFileParser $parser,
        private readonly ParsedFileCache $cache,
        private readonly FileDiscoveryService $fileDiscovery,
        private readonly ParseFailurePolicy $failurePolicy = ParseFailurePolicy::WARN_AND_SKIP,
    ) {}

    /**
     * @param string[] $contextPaths  Absolute directory paths to scan
     */
    public function build(array $contextPaths): SemanticIndex
    {
        $files = [];

        foreach ($contextPaths as $path) {
            $discovered = $this->fileDiscovery->getPhpFiles($path);
            $files = array_merge($files, $discovered);
        }

        // Deduplicate by real path
        $seen = [];
        $unique = [];

        foreach ($files as $file) {
            $realPath = $file->getRealPath();

            if ($realPath !== false && ! isset($seen[$realPath])) {
                $seen[$realPath] = true;
                $unique[] = $file;
            }
        }

        // Sort deterministically by path
        usort($unique, fn (SplFileInfo $a, SplFileInfo $b) => strcmp($a->getRealPath(), $b->getRealPath()));

        $parsedFiles = $this->cache->getMultiple($unique, $this->parser, $this->failurePolicy);

        return new class($parsedFiles) implements SemanticIndex
        {
            /** @param PhpFile[] $files */
            public function __construct(
                private readonly array $files,
            ) {}

            public function files(): array
            {
                return $this->files;
            }

            public function filesInPath(string $path): array
            {
                $normalized = str_replace('\\', '/', $path);

                return array_values(
                    array_filter(
                        $this->files,
                        fn (PhpFileView $f) => str_starts_with(str_replace('\\', '/', $f->path()), $normalized),
                    ),
                );
            }

            public function findClass(string $fqcn): ?ClassLike
            {
                foreach ($this->files as $file) {
                    foreach ($file->classes() as $class) {
                        $fileNamespace = $file->namespace() ?? '';

                        $full = $fileNamespace !== ''
                            ? $fileNamespace . '\\' . $class->name
                            : $class->name;

                        if ($full === $fqcn || $class->name === $fqcn) {
                            return $class;
                        }
                    }
                }

                return null;
            }

            public function importsOf(string $fqcn): array
            {
                foreach ($this->files as $file) {
                    $fileNamespace = $file->namespace() ?? '';

                    $full = $fileNamespace !== ''
                        ? $fileNamespace . '\\' . $fqcn
                        : $fqcn;

                    // Check if any class in the file matches the namespace
                    foreach ($file->classes() as $class) {
                        $classFull = $fileNamespace !== ''
                            ? $fileNamespace . '\\' . $class->name
                            : $class->name;

                        if ($classFull === $fqcn || $class->name === $fqcn) {
                            return $file->imports();
                        }
                    }
                }

                return [];
            }

            public function filesByNamespace(string $namespace): array
            {
                $normalized = rtrim(str_replace('\\', '\\\\', $namespace), '\\');

                return array_values(
                    array_filter(
                        $this->files,
                        fn (PhpFileView $f) => $f->namespace() === $namespace
                            || str_starts_with($f->namespace() ?? '', $namespace . '\\'),
                    ),
                );
            }
        };
    }

    /**
     * Build index from a single path.
     */
    public function buildFromPath(string $path): SemanticIndex
    {
        return $this->build([$path]);
    }
}
