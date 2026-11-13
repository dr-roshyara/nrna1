<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Domain;

/** A login. In G-0 this is a legacy users row (D-226 §5a); it authenticates and grants nothing else. */
final readonly class AccountId
{
    private function __construct(private string $value)
    {
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $value) !== 1) {
            throw new \InvalidArgumentException('AccountId must be a lower-case UUID.');
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
