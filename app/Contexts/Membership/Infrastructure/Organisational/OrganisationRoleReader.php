<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * The actor's organisation role, as the legacy policy reads it (one role per user and organisation).
 * Used only by the INTERIM authority adapter. Slice 2a has no production implementation: the Eloquent
 * reader belongs to Slice 2b, held with PostgreSQL (PBDIGIT-271).
 */
interface OrganisationRoleReader
{
    public function roleOf(TenantId $tenant, string $actor): ?string;
}
