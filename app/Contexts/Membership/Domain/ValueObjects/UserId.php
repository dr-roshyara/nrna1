<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

final readonly class UserId
{
    public function __construct(
        private string $value,
    ) {}

    public static function from(string $value): self
    {
        return new self($value);
    }

    public static function sample(): self
    {
        return new self('user-1');
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
