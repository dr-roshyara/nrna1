<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Membership\Ports;

use App\Contexts\Membership\Domain\Committee\ValueObjects\GeoPathChain;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * MemberGeoPathProviderPort
 *
 * Abstracts resolution of member's geographic context.
 * Used in application layer to avoid controller-level GeoPathChain construction.
 *
 * Port approach ensures:
 * - Caller doesn't care HOW geo path is resolved
 * - Application layer doesn't construct value objects
 * - Infrastructure handles geo/user context retrieval
 */
interface MemberGeoPathProviderPort
{
    /**
     * Resolve member's geographic path for eligibility evaluation.
     *
     * Returns the member's geographic context from session/auth/profile.
     * May return empty GeoPathChain if member has no geographic context.
     */
    public function resolveForMember(
        MemberId $memberId,
        TenantId $tenantId,
    ): GeoPathChain;
}
