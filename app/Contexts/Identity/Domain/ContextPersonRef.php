<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Domain;

/**
 * D-225 IG-1: an opaque reference to one Identity Person within one platform context. Issued once per
 * (Person, context), never reused, never changed. It carries no data and grants no authority.
 * Two different references mean "not known to be the same person", never "proven different people" (D-226 §5a).
 */
final readonly class ContextPersonRef
{
    private function __construct(private string $value)
    {
        if (preg_match('/^[0-9a-f]{32}$/', $value) !== 1) {
            throw new \InvalidArgumentException('A ContextPersonRef is 32 lower-case hex characters.');
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
