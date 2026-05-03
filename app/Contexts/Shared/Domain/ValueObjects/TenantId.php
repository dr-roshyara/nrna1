<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Domain\ValueObjects;

/**
 * Tenant identity value object.
 *
 * Invariant: TenantId::value() === organisations.id (UUID string)
 *
 * "Tenant" is the technical isolation boundary; "Organisation" is the business
 * concept. In this system they are 1-to-1. The database column is named
 * `organisation_id` and the session key is `current_organisation_id`, but the
 * domain always works with TenantId for type safety. Use fromOrganisationId()
 * at infrastructure call-sites to make the mapping explicit.
 */
final readonly class TenantId
{
    private function __construct(private string $value)
    {
        if (empty(trim($value))) {
            throw new \InvalidArgumentException('TenantId cannot be empty');
        }
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    /**
     * Named constructor that makes the TenantId ↔ organisation_id mapping
     * explicit at infrastructure call-sites (e.g. resolving from session or
     * HTTP request context).
     */
    public static function fromOrganisationId(string $orgId): self
    {
        return new self($orgId);
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
