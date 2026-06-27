<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Query;

use App\Contexts\Membership\Application\Membership\Ports\MemberGeoPathProviderPort;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GeoPathChain;
use App\Contexts\Membership\Domain\Committee\Services\GeoSemanticProjectionBuilder;
use App\Contexts\Membership\Domain\Committee\Factories\GeoPathChainFactory;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Models\User;

final class SessionMemberGeoPathProvider implements MemberGeoPathProviderPort
{
    public function __construct(
        private readonly GeoSemanticProjectionBuilder $geoBuilder,
    ) {}

    public function resolveForMember(
        MemberId $memberId,
        TenantId $tenantId,
    ): GeoPathChain {
        $user = User::find($memberId->value());

        if (!$user?->residence_geo_unit_id) {
            return GeoPathChain::empty();
        }

        $projection = $this->geoBuilder->build((int) $user->residence_geo_unit_id);

        if ($projection === null) {
            return GeoPathChain::empty();
        }

        return GeoPathChainFactory::from($projection);
    }
}
