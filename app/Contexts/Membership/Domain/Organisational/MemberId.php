<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * The identity of one Member aggregate: one per (Person, Organisation) (D1 / B′).
 */
final readonly class MemberId
{
    private function __construct(private string $value)
    {
        if (trim($value) === '') {
            throw new \InvalidArgumentException('MemberId cannot be empty.');
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
