<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\ValueObjects;

use DomainException;

final readonly class AuthorityPath
{
    /** @param array<int, string> $path */
    private function __construct(private array $path) {}

    /** @param array<int, string> $path */
    public static function fromArray(array $path): self
    {
        if (empty($path)) {
            throw new DomainException('Delegation path cannot be empty');
        }

        if ($path[0] !== 'ICC') {
            throw new DomainException('Authority path must start with ICC');
        }

        return new self($path);
    }

    /** @return array<int, string> */
    public function toArray(): array
    {
        return $this->path;
    }

    public function length(): int
    {
        return count($this->path);
    }

    public function equals(self $other): bool
    {
        return $this->path === $other->path;
    }
}
