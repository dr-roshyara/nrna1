<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** PublicDigit's identity of one geographical entry (v3.11 §2 item 1): minted once, never deleted or reused (I-3). Opaque: never a code or a name. */
final readonly class ReferenceUnitId
{
    private function __construct(private string $value)
    {
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $value) !== 1) {
            throw new \InvalidArgumentException('ReferenceUnitId must be a lower-case UUID.');
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
