<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\ValueObjects;

use DomainException;
use Ramsey\Uuid\Uuid;

final readonly class CommitteeId
{
    private function __construct(private string $value) {}

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public static function fromString(string $value): self
    {
        if (empty(trim($value))) {
            throw new DomainException('Committee ID cannot be empty');
        }
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

    public function __toString(): string
    {
        return $this->value;
    }
}
