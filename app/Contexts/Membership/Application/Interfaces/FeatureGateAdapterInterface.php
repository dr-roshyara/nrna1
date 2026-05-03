<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Interfaces;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Feature Gate Adapter Interface
 *
 * Anti-Corruption Layer: Membership Context's view of subscription features.
 * Defines what Membership Context needs, not what Subscription Context provides.
 *
 * Architecture Pattern: Adapter / Anti-Corruption Layer
 * - Membership Context depends on THIS interface (abstraction)
 * - Adapter implements this and wraps Subscription Context
 * - Loose coupling between contexts maintained
 * - Enables testing via mocking this interface
 *
 * Global Platform: Supports ANY political party worldwide
 */
interface FeatureGateAdapterInterface
{
    /**
     * Check if tenant can import CSV
     *
     * @param TenantId $tenantId The tenant identifier
     * @return bool True if tenant has CSV import feature access
     */
    public function canImportCsv(TenantId $tenantId): bool;

    /**
     * Check if CSV import would exceed member quota
     *
     * @param TenantId $tenantId The tenant identifier
     * @param int $additionalMembers Number of members to import
     * @return bool True if quota would be exceeded
     */
    public function wouldExceedMemberQuota(TenantId $tenantId, int $additionalMembers): bool;
}
