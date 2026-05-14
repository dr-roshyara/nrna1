<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Domain\ValueObjects;

final readonly class ActorId
{
    private function __construct(private string $value)
    {
        if (empty(trim($value))) {
            throw new \DomainException('ActorId cannot be empty');
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
