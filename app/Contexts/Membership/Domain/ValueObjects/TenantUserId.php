<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

/**
 * Tenant User ID Value Object
 *
 * Represents the unique identifier for a tenant user in the TenantAuth context.
 * This is a foreign concept to Membership domain - it's the link between
 * "digital identity" (TenantAuth) and "membership" (Membership).
 *
 * Business Rule: Every member MUST have a tenant user ID (1:1 required relationship)
 *
 * Format: ULID (26 characters, alphanumeric, uppercase)
 * Example: "01JKUSER1234567890ABCDEFGH"
 */
final readonly class TenantUserId
{
    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    private function validate(string $value): void
    {
        // Empty check
        if (empty(trim($value))) {
            throw new \InvalidArgumentException(
                'Tenant user ID cannot be empty'
            );
        }

        // ULID format: 26 characters, alphanumeric
        if (!preg_match('/^[0-9A-Z]{26}$/', $value)) {
            throw new \InvalidArgumentException(
                "Invalid tenant user ID format: {$value}. Expected ULID (26 alphanumeric characters)"
            );
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(TenantUserId $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
