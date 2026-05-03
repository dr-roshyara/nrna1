<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Services;

use App\Contexts\Membership\Application\Interfaces\FeatureGateAdapterInterface;
use App\Contexts\Subscription\Application\Services\FeatureGateService;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Feature Gate Adapter
 *
 * Anti-Corruption Layer: Wraps Subscription Context's FeatureGateService
 * for use by Membership Context.
 *
 * Architecture Pattern: Adapter
 * - Translates Membership Context needs → Subscription Context calls
 * - Protects Membership from Subscription Context changes
 * - Optional dependency (graceful degradation if Subscription not available)
 *
 * Global Platform: Supports ANY political party worldwide
 */
final class FeatureGateAdapter implements FeatureGateAdapterInterface
{
    public function __construct(
        private ?FeatureGateService $featureGate = null
    ) {
    }

    /**
     * Check if tenant can import CSV
     *
     * MVP Behavior: If Subscription Context not available, allow (graceful degradation)
     */
    public function canImportCsv(TenantId $tenantId): bool
    {
        // Graceful degradation: If subscription module not installed, allow
        if ($this->featureGate === null) {
            return true;
        }

        return $this->featureGate->can(
            $tenantId->toString(),
            'membership',
            'csv_import'
        );
    }

    /**
     * Check if CSV import would exceed member quota
     *
     * MVP Behavior: If Subscription Context not available, never exceed (graceful degradation)
     */
    public function wouldExceedMemberQuota(TenantId $tenantId, int $additionalMembers): bool
    {
        // Graceful degradation: If subscription module not installed, never exceed
        if ($this->featureGate === null) {
            return false;
        }

        return $this->featureGate->isQuotaExceeded(
            $tenantId->toString(),
            'membership',
            'member_count',
            $additionalMembers
        );
    }
}
