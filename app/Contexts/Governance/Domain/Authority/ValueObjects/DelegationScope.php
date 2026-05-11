<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Authority\ValueObjects;

final readonly class DelegationScope
{
    private function __construct(private string $value) {}

    public static function from(string $value): self
    {
        $trimmed = trim($value);

        if ($trimmed === '') {
            throw new \DomainException('DelegationScope cannot be empty');
        }

        return new self($trimmed);
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
