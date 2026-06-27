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
 * Format: UUID v4 (8-4-4-4-12 hex format with dashes)
 * Example: "f2310e9e-40ba-443e-b99a-86531f115528"
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

        // UUID v4 format: 8-4-4-4-12 hex with dashes
        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $value)) {
            throw new \InvalidArgumentException(
                "Invalid tenant user ID format: {$value}. Expected UUID v4 (8-4-4-4-12 hex with dashes)"
            );
        }
    }

    public static function fromString(string|\Stringable $value): self
    {
        return new self((string) $value);
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
