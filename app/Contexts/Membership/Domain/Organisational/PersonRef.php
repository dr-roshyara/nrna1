<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * A reference to the person who holds the membership. Identity is owned by the Identity context.
 */
final readonly class PersonRef
{
    private function __construct(private string $value)
    {
        if (trim($value) === '') {
            throw new \InvalidArgumentException('PersonRef cannot be empty.');
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
