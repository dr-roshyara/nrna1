<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Fee;

final readonly class FeeStatus
{
    private function __construct(private string $value)
    {
        if (!in_array($value, ['pending', 'paid', 'overdue', 'waived'])) {
            throw new \InvalidArgumentException("Invalid FeeStatus: {$value}");
        }
    }

    public static function pending(): self
    {
        return new self('pending');
    }

    public static function paid(): self
    {
        return new self('paid');
    }

    public static function overdue(): self
    {
        return new self('overdue');
    }

    public static function waived(): self
    {
        return new self('waived');
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isPending(): bool
    {
        return $this->value === 'pending';
    }

    public function isPaid(): bool
    {
        return $this->value === 'paid';
    }

    public function isOverdue(): bool
    {
        return $this->value === 'overdue';
    }

    public function isWaived(): bool
    {
        return $this->value === 'waived';
    }

    public function equals(FeeStatus $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
