<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * The identity of one recorded fact. Supplied by the caller; the domain generates no ids.
 */
final readonly class FactId
{
    private function __construct(private string $value)
    {
        if (trim($value) === '') {
            throw new \InvalidArgumentException('FactId cannot be empty.');
        }
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
