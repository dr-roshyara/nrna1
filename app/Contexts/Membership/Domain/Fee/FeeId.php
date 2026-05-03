<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Fee;

use Illuminate\Support\Str;

final readonly class FeeId
{
    private function __construct(private string $value)
    {
        if (!$this->isValidUlid($value)) {
            throw new \InvalidArgumentException("Invalid FeeId: {$value}");
        }
    }

    public static function generate(): self
    {
        return new self((string) Str::ulid());
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function equals(FeeId $other): bool
    {
        return $this->value === $other->value;
    }

    private function isValidUlid(string $value): bool
    {
        return (bool) preg_match('/^[0-9A-Z]{26}$/i', $value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
