<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Graph;

final readonly class DependencyGraph
{
    /**
     * @param array<string, string[]> $dependencies  file => files it depends on
     * @param array<string, string[]> $dependents    file => files that depend on it
     */
    public function __construct(
        private array $dependencies = [],
        private array $dependents = [],
    ) {}

    /**
     * Files this file directly depends on.
     * @return string[]
     */
    public function getDependencies(string $file): array
    {
        return $this->dependencies[$file] ?? [];
    }

    /**
     * Files that directly depend on this file.
     * @return string[]
     */
    public function getDependents(string $file): array
    {
        return $this->dependents[$file] ?? [];
    }

    /**
     * All files in the graph.
     * @return string[]  Sorted alphabetically
     */
    public function getAllFiles(): array
    {
        $files = array_unique(array_merge(
            array_keys($this->dependencies),
            array_keys($this->dependents),
        ));
        sort($files);
        return $files;
    }

    /**
     * @return array<string, string[]>
     */
    public function allDependencies(): array
    {
        return $this->dependencies;
    }

    /**
     * @return array<string, string[]>
     */
    public function allDependents(): array
    {
        return $this->dependents;
    }
}
