<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Query;

use App\Contexts\Membership\Application\Membership\Ports\MemberGeoPathProviderPort;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GeoPathChain;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * SessionMemberGeoPathProvider
 *
 * Resolves member's geographic context from session/auth.
 * Infrastructure implementation of MemberGeoPathProviderPort.
 *
 * TODO: Query from User profile / session data once available
 */
final class SessionMemberGeoPathProvider implements MemberGeoPathProviderPort
{
    public function resolveForMember(
        MemberId $memberId,
        TenantId $tenantId,
    ): GeoPathChain {
        // TODO: Load from user profile / session
        // For now: return root geo path (member at top level)
        // This makes geographic committees ineligible (safe default)
        return new GeoPathChain(1, '/', [1]);
    }
}
