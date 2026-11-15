<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Domain;

/**
 * I-TDD-02 §2: an organisation's opaque reference (`PersonRef`, R) for one person it recorded. Issued by Identity to
 * exactly one organisation (INV-REF-1), once per local entry (INV-REF-2). It carries no data and grants nothing:
 * not membership, not access, not identity proof. 32 lower-case hex characters.
 */
final readonly class OrganisationPersonRef
{
    private function __construct(private string $value)
    {
        if (preg_match('/^[0-9a-f]{32}$/', $value) !== 1) {
            throw new \InvalidArgumentException('An organisation PersonRef is 32 lower-case hex characters.');
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
