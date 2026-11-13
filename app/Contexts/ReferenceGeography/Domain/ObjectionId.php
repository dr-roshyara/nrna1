<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** The identity of one Objection (v3.11 §16): an organisation's challenge to a published fact. */
final readonly class ObjectionId
{
    private function __construct(private string $value)
    {
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $value) !== 1) {
            throw new \InvalidArgumentException('ObjectionId must be a lower-case UUID.');
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
