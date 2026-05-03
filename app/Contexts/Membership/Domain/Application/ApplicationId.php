<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Application;

use Illuminate\Support\Str;

final readonly class ApplicationId
{
    private function __construct(private string $value)
    {
        if (!$this->isValidUlid($value) && !$this->isValidUuid($value)) {
            throw new \InvalidArgumentException("Invalid ApplicationId: {$value}. Expected ULID or UUID");
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

    public function equals(ApplicationId $other): bool
    {
        return $this->value === $other->value;
    }

    private function isValidUlid(string $value): bool
    {
        return (bool) preg_match('/^[0-9A-Z]{26}$/i', $value);
    }

    private function isValidUuid(string $value): bool
    {
        return (bool) preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
