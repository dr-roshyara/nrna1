<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

/**
 * The caller's key for one intended command. A retry with the same key must not
 * record a second fact (Slice 1a contract IDEM-1…4). Scoped per tenant.
 */
final readonly class IdempotencyKey
{
    private function __construct(private string $value)
    {
        if (trim($value) === '') {
            throw new \InvalidArgumentException('IdempotencyKey cannot be empty.');
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
