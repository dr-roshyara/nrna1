<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Parser;

final readonly class PhpFile implements PhpFileView
{
    /** @param Import[] $imports */
    /** @param ClassLike[] $classes */
    /** @param Method[] $methods */
    public function __construct(
        private string $path,
        private ?string $namespace,
        private array $imports = [],
        private array $classes = [],
        private array $methods = [],
    ) {}

    public function path(): string
    {
        return $this->path;
    }

    public function namespace(): ?string
    {
        return $this->namespace;
    }

    public function imports(): array
    {
        return $this->imports;
    }

    public function classes(): array
    {
        return $this->classes;
    }

    public function methods(): array
    {
        return $this->methods;
    }

    public function hasClass(string $name): bool
    {
        foreach ($this->classes as $class) {
            if ($class->name === $name) {
                return true;
            }
        }

        return false;
    }
}
