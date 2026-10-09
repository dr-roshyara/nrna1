<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * The kind of an End. Opaque in Slice 0: the set of termination grounds is BD-3 / LQ-3 (open).
 * Resolution never reads it (contract §18).
 */
final readonly class EndKind
{
    private function __construct(private string $value)
    {
        if (trim($value) === '') {
            throw new \InvalidArgumentException('EndKind cannot be empty.');
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
