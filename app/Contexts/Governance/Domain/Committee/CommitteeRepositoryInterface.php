<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Committee;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * CommitteeRepositoryInterface
 *
 * Domain port for Committee aggregate persistence.
 * Responsibility: Load and save Committee aggregates.
 * No Laravel dependencies — pure domain contracts.
 */
interface CommitteeRepositoryInterface
{
    /**
     * Load a Committee aggregate by ID and tenant.
     * Returns null if not found.
     */
    public function findById(CommitteeId $id, TenantId $tenantId): ?Committee;

    /**
     * Save a Committee aggregate (and dispatch its events).
     */
    public function save(Committee $committee, TenantId $tenantId): void;
}
