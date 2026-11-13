<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * How Reference Geography refers to a steward, preparer, approver or matter reviewer (v3.11 §16; D-225).
 * An opaque value issued by Identity for this context. Geography never resolves it.
 * Two different values mean "not known to be the same person", never "proven different people" (D-226 §5a).
 */
final readonly class PersonReference
{
    private function __construct(private string $value)
    {
        if (preg_match('/^[0-9a-f]{32}$/', $value) !== 1) {
            throw new \InvalidArgumentException('A person reference is 32 lower-case hex characters.');
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
