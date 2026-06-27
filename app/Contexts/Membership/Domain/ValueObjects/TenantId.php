<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

/**
 * Tenant ID Value Object
 *
 * Represents the unique identifier for a tenant organization.
 *
 * Format: Slug (lowercase, alphanumeric, hyphens)
 * Examples: "uml", "nrna", "congress-usa"
 *
 * Business Rule: Every member belongs to exactly one tenant
 */
final readonly class TenantId
{
    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = strtolower(trim($value));
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public static function fromOrganisationId(string $organisationId): self
    {
        return new self($organisationId);
    }

    private function validate(string $value): void
    {
        // Empty check
        if (empty(trim($value))) {
            throw new \InvalidArgumentException(
                'Tenant ID cannot be empty'
            );
        }

        // Slug format: lowercase, alphanumeric, hyphens
        if (!preg_match('/^[a-z0-9\-]+$/', strtolower($value))) {
            throw new \InvalidArgumentException(
                "Invalid tenant ID format: {$value}. Expected slug (lowercase, alphanumeric, hyphens)"
            );
        }

        // Length check (3-50 characters)
        if (strlen($value) < 3 || strlen($value) > 50) {
            throw new \InvalidArgumentException(
                "Tenant ID must be between 3 and 50 characters, got: " . strlen($value)
            );
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(TenantId $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
