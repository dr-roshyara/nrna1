<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * The identity of a scheduled resignation declaration (PBDIGIT-354 S2-4a). Supplied by the caller; the domain
 * generates no ids. Not a FactId: a declaration is held outside the ledger (D-204).
 */
final readonly class DeclarationId
{
    private function __construct(private string $value)
    {
        if (trim($value) === '') {
            throw new \InvalidArgumentException('DeclarationId cannot be empty.');
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
