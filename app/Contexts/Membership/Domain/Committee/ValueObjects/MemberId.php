<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\ValueObjects;

use DomainException;

final readonly class MemberId
{
    private function __construct(private string $value) {}

    public static function from(string $value): self
    {
        $trimmed = trim($value);
        if (empty($trimmed)) {
            throw new DomainException('Member ID cannot be empty');
        }
        return new self($trimmed);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
