<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Member;

use Illuminate\Support\Str;

final readonly class MemberId
{
    private function __construct(private string $value)
    {
        if (!$this->isValidUlid($value)) {
            throw new \InvalidArgumentException("Invalid ULID format: {$value}");
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

    public function value(): string
    {
        return $this->value;
    }

    public function toString(): string
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

    private function isValidUlid(string $value): bool
    {
        return preg_match('/^[0-7][0-9A-HJKMNP-TV-Z]{25}$/i', $value) === 1;
    }
}
