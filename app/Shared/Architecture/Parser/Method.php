<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Parser;

final readonly class Method
{
    /** @param string[] $parameterTypes */
    public function __construct(
        public string $name,
        public string $visibility,
        public bool $isStatic,
        public bool $isAbstract,
        public bool $isFinal,
        public ?string $returnType,
        public bool $returnsVoid,
        public int $parameterCount,
        public array $parameterTypes = [],
        public bool $hasBody = false,
        public int $statementCount = 0,
        public int $line = 0,
    ) {}
}
