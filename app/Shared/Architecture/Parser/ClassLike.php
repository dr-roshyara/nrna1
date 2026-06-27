<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Parser;

final readonly class ClassLike
{
    /** @param string[] $implements */
    /** @param string[] $traits */
    /** @param string[] $attributes */
    /** @param Method[] $methods */
    /** @param string[] $properties */
    public function __construct(
        public string $name,
        public string $type,
        public bool $isFinal,
        public bool $isReadonly,
        public bool $isAbstract,
        public ?string $extends,
        public array $implements = [],
        public array $traits = [],
        public array $attributes = [],
        public array $methods = [],
        public array $properties = [],
    ) {}
}
