<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Domain;

/**
 * I-TDD-02 §4: the organisation's own identifier of a local entry E (a person it recorded in its own database).
 * Opaque to Identity: Identity stores it only to keep issuance idempotent on (O, E) (INV-REF-2) and never reads
 * anything behind it. Non-empty, no whitespace, at most 128 characters.
 */
final readonly class LocalEntryRef
{
    private function __construct(private string $value)
    {
        if (preg_match('/^\S{1,128}$/', $value) !== 1) {
            throw new \InvalidArgumentException('A local entry reference is 1 to 128 non-space characters.');
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
}
