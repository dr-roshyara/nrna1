<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Parser;

interface PhpFileView
{
    public function path(): string;

    public function namespace(): ?string;

    /** @return Import[] */
    public function imports(): array;

    /** @return ClassLike[] */
    public function classes(): array;

    /** @return Method[] */
    public function methods(): array;

    public function hasClass(string $name): bool;
}
