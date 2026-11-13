<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Domain;

/** Identity's canonical Person (D-75; I-TDD-02 INV-ID-1). Never handed to another context (D-225 IG-3). */
final readonly class PersonId
{
    private function __construct(private string $value)
    {
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $value) !== 1) {
            throw new \InvalidArgumentException('PersonId must be a lower-case UUID.');
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
