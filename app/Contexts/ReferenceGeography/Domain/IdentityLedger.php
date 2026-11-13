<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * I-3 (v3.11 §2): an identity is minted once and never deleted or reused. Retirement keeps it minted.
 * Immutable value. Persistence enforces the same rule durably (G-4: unique, insert-only).
 */
final class IdentityLedger
{
    /** @param array<string, bool> $minted id => retired */
    private function __construct(private readonly array $minted)
    {
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public function mint(ReferenceUnitId $id): self
    {
        if (isset($this->minted[$id->value()])) {
            throw new \InvalidArgumentException('I-3: an identity is never reused.');
        }

        return new self($this->minted + [$id->value() => false]);
    }

    public function retire(ReferenceUnitId $id): self
    {
        if (!isset($this->minted[$id->value()])) {
            throw new \InvalidArgumentException('Only a minted identity can be retired.');
        }
        $next = $this->minted;
        $next[$id->value()] = true;

        return new self($next);
    }

    public function hasMinted(ReferenceUnitId $id): bool
    {
        return isset($this->minted[$id->value()]);
    }
}
