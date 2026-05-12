<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Graph;

use App\Shared\Architecture\Parser\SemanticIndex;

final class DependencyGraphBuilder
{
    /**
     * Build a dependency graph from a SemanticIndex.
     *
     * For each file, resolves its use-imports to the files that define those
     * namespaces/classes. Only files within the index are included — external
     * (vendor) imports are silently skipped.
     */
    public function build(SemanticIndex $index): DependencyGraph
    {
        $dependencies = []; // file → file[]
        $dependents = [];   // file → file[]

        // Build a map: namespace → file path for all files in the index
        $namespaceMap = $this->buildNamespaceMap($index);

        foreach ($index->files() as $file) {
            $filePath = $file->path();
            $deps = [];

            foreach ($file->imports() as $import) {
                $importNs = $import->namespace;

                // Try exact match first (e.g. "App\Services\Foo")
                if (isset($namespaceMap[$importNs])) {
                    $targetFile = $namespaceMap[$importNs];
                    if ($targetFile !== $filePath) {
                        $deps[$targetFile] = true;
                    }
                    continue;
                }

                // Try parent namespace resolution (e.g. "App\Services\Foo\Bar"
                // might be defined in a file with namespace "App\Services\Foo\Bar")
                // and also check if the import namespace has a class that matches
                $class = $index->findClass($importNs);
                if ($class !== null) {
                    // Find which file defines this class
                    foreach ($index->files() as $f) {
                        foreach ($f->classes() as $c) {
                            $fqcn = ($f->namespace() ? $f->namespace() . '\\' : '') . $c->name;
                            if ($fqcn === $importNs) {
                                if ($f->path() !== $filePath) {
                                    $deps[$f->path()] = true;
                                }
                                break 2;
                            }
                        }
                    }
                }
            }

            if ($deps !== []) {
                $depPaths = array_keys($deps);
                sort($depPaths);
                $dependencies[$filePath] = $depPaths;

                foreach ($depPaths as $depPath) {
                    $dependents[$depPath][] = $filePath;
                }
            }
        }

        // Sort dependent lists deterministically
        foreach ($dependents as $path => $deps) {
            sort($dependents[$path]);
        }

        return new DependencyGraph($dependencies, $dependents);
    }

    /**
     * @return array<string, string>  namespace => file path
     */
    private function buildNamespaceMap(SemanticIndex $index): array
    {
        $map = [];
        foreach ($index->files() as $file) {
            $ns = $file->namespace();
            if ($ns !== null) {
                $map[$ns] = $file->path();
            }
            // Also map each class's FQCN
            foreach ($file->classes() as $class) {
                $fqcn = $ns !== null ? $ns . '\\' . $class->name : $class->name;
                $map[$fqcn] = $file->path();
            }
        }
        return $map;
    }
}
