<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee;

use App\Contexts\Membership\Domain\Committee\Ports\GeoContextPort;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;

final class CommitteeCreationPolicy
{
    public function __construct(private readonly GeoContextPort $geoPort) {}

    public function assertCanCreate(
        CommitteeStructure $structure,
        int $levelIndex,
        ?GeoReference $geoReference
    ): void {
        // Step 1: Delegate structural validation to aggregate
        $structure->assertCanCreateCommittee($levelIndex);

        // Step 2: Caller fetches level properties and provides decisions
        $level = $structure->getLevel($levelIndex);

        // Step 3: Validate geo requirement
        if ($level->geoPolicy->requiresGeo() && $geoReference === null) {
            throw new \DomainException('GeoPolicy requires a GeoReference for this level');
        }

        // Step 4: Validate geo reference against geography context
        if ($geoReference !== null && $level->geoScope !== null) {
            $this->geoPort->validateGeoReference($level->geoScope, $geoReference);
        }
    }
}
