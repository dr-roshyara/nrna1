<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Committee;

use Ramsey\Uuid\Uuid;

final readonly class CommitteeId
{
    private function __construct(private string $value)
    {
        // Accept both UUID and ULID formats
        if (!Uuid::isValid($value) && !$this->isValidUlid($value)) {
            throw new \InvalidArgumentException("Invalid CommitteeId: {$value}");
        }
    }

    private function isValidUlid(string $value): bool
    {
        // ULID format: 26 alphanumeric characters (Crockford base32)
        return preg_match('/^[0-7][0-9a-z]{25}$/i', $value) === 1;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
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
