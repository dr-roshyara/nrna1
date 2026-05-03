<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Repositories;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;

interface CommitteeRepositoryInterface
{
    /**
     * Find committee by ID for specific tenant.
     *
     * @param CommitteeId $id Committee identifier
     * @param TenantId $tenantId Tenant identifier
     * @return Committee|null Returns null if not found
     */
    public function findForTenant(CommitteeId $id, TenantId $tenantId): ?Committee;

    /**
     * Save committee for specific tenant (cascades assignments).
     *
     * Business Rules:
     * - Must persist the Committee aggregate and all owned CommitteeAssignment entities
     * - Must dispatch recorded domain events
     * - Must validate tenant ownership
     *
     * @param Committee $committee Committee aggregate to save
     */
    public function saveForTenant(Committee $committee): void;

    /**
     * Find committees by type for tenant.
     *
     * @param CommitteeType $type Committee type (Central, Province, District, etc.)
     * @param TenantId $tenantId Tenant identifier
     * @return array<Committee> Array of committees matching the type
     */
    public function findByTypeForTenant(CommitteeType $type, TenantId $tenantId): array;

    /**
     * Find committees by operational geography for tenant.
     *
     * Business Rules:
     * - Geographic committees match exact geography or parent geography
     * - Central committees are NOT returned (they have no geography)
     * - Wings with geography follow geographic rules
     * - Wings without geography are NOT returned
     *
     * @param GeoReference $geoReference Operational geography reference
     * @param TenantId $tenantId Tenant identifier
     * @return array<Committee> Array of committees covering the geography
     */
    public function findByGeographyForTenant(GeoReference $geoReference, TenantId $tenantId): array;

    /**
     * Check if committee exists for tenant.
     *
     * Used for uniqueness validation before creation.
     *
     * @param CommitteeId $id Committee identifier
     * @param TenantId $tenantId Tenant identifier
     * @return bool True if committee exists for this tenant
     */
    public function existsForTenant(CommitteeId $id, TenantId $tenantId): bool;

    /**
     * Delete committee for specific tenant.
     *
     * Business Rules:
     * - Must delete all owned CommitteeAssignment entities (cascade)
     * - Must validate tenant ownership before deletion
     * - Typically soft delete via status change (CommitteeStatus::DELETED)
     *
     * @param CommitteeId $id Committee identifier
     * @param TenantId $tenantId Tenant identifier
     */
    public function deleteForTenant(CommitteeId $id, TenantId $tenantId): void;

    /**
     * Find all committees for tenant.
     *
     * @param TenantId $tenantId Tenant identifier
     * @return array<Committee> All committees for the tenant
     */
    public function findAllForTenant(TenantId $tenantId): array;
}