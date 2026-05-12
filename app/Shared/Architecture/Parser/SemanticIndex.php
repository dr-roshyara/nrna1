<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Parser;

interface SemanticIndex
{
    /** @return PhpFileView[] */
    public function files(): array;

    /** @return PhpFileView[] */
    public function filesInPath(string $path): array;

    public function findClass(string $fqcn): ?ClassLike;

    /** @return Import[] */
    public function importsOf(string $fqcn): array;

    /** @return PhpFileView[] */
    public function filesByNamespace(string $namespace): array;
}
