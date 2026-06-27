<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Member;

use Illuminate\Support\Str;

final readonly class MemberId
{
    private function __construct(private string $value)
    {
        if (!$this->isValidUuid($value) && !$this->isValidUlid($value)) {
            throw new \InvalidArgumentException("Invalid MemberId format: {$value}. Expected UUID or ULID");
        }
    }

    public static function generate(): self
    {
        return new self((string) Str::uuid());
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

    private function isValidUuid(string $value): bool
    {
        return (bool) preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $value);
    }

    private function isValidUlid(string $value): bool
    {
        return preg_match('/^[0-7][0-9A-HJKMNP-TV-Z]{25}$/i', $value) === 1;
    }
}
