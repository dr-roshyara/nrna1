<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** A claim's value, or none: a withdrawal is a claim with value none in the target's own slot (v3.11 §3). */
final readonly class ClaimValue
{
    private function __construct(private ?string $value)
    {
    }

    public static function of(string $value): self
    {
        if (trim($value) === '') {
            throw new \InvalidArgumentException('A claim value cannot be blank; use none() for a withdrawal.');
        }

        return new self($value);
    }

    public static function none(): self
    {
        return new self(null);
    }

    public function isNone(): bool
    {
        return $this->value === null;
    }

    public function value(): string
    {
        return $this->value ?? throw new \LogicException('A withdrawal has no value.');
    }
}
