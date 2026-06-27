<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Factories;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeGeoIdentity;

/**
 * CommitteeGeoIdentityFactory
 *
 * PURE identity construction. No provider, no projection, no external
 * dependency. Identity creation and semantic projection are DIFFERENT
 * lifecycles and must not share a service boundary.
 */
final readonly class CommitteeGeoIdentityFactory
{
    public function create(int $geoUnitId): CommitteeGeoIdentity
    {
        return new CommitteeGeoIdentity($geoUnitId);
    }
}
