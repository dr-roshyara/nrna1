<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Parser;

final readonly class Import
{
    public function __construct(
        public string $namespace,
        public string $alias,
        public string $type,
        public int $line,
    ) {}
}
