<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

use Illuminate\Support\Str;

final readonly class MembershipTypeId
{
    private string $value;

    public function __construct(string $value)
    {
        if (!$this->isValidUuid($value)) {
            throw new \InvalidArgumentException("Invalid UUID format: {$value}");
        }
        $this->value = strtolower($value);
    }

    public static function generate(): self
    {
        return new self((string) Str::uuid());
    }

    public static function fromString(string|\Stringable $value): self
    {
        return new self((string) $value);
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

    private function isValidUuid(string $value): bool
    {
        return preg_match(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i',
            $value
        ) === 1;
    }
}
