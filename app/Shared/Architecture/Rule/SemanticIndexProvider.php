<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Rule;

use App\Shared\Architecture\Parser\SemanticIndex;
use App\Shared\Architecture\Parser\SemanticIndexBuilder;

final class SemanticIndexProvider
{
    private ?SemanticIndex $index = null;

    public function __construct(
        private readonly SemanticIndexBuilder $builder,
    ) {}

    /**
     * @param array<string, mixed> $config Must contain a 'paths' key with string[] of absolute directory paths.
     */
    public function getIndex(array $config): SemanticIndex
    {
        if ($this->index !== null) {
            return $this->index;
        }

        $paths = $config['paths']
            ?? throw new \InvalidArgumentException('Architecture ruleset config must contain a "paths" key');

        if (!is_array($paths) || $paths === []) {
            throw new \InvalidArgumentException('Architecture ruleset "paths" must be a non-empty array of directory paths');
        }

        $this->index = $this->builder->build($paths);
        return $this->index;
    }

    public function clear(): void
    {
        $this->index = null;
    }
}
