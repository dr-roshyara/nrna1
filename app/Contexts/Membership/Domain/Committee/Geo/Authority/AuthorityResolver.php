<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Authority;

use App\Contexts\Membership\Domain\Committee\Context\GeographicScope;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\GeoAuthorityGraph;

final class AuthorityResolver
{
    public function resolve(GeographicScope $actorScope, GeoAuthorityGraph $graph, \DateTimeImmutable $at): ResolvedAuthority
    {
        $rootNode = $graph->findNodeByScope($actorScope);
        if ($rootNode === null || !$rootNode->active) {
            return ResolvedAuthority::none();
        }

        $delegated = $graph->reachableJurisdictions($rootNode->id, $at);
        $delegatedIds = array_map(fn($item) => $item['node']->id, $delegated);

        return new ResolvedAuthority(
            directJurisdictionId: $rootNode->id,
            delegatedJurisdictionIds: $delegatedIds,
            exceptionZoneIds: [],
            authorityScore: 100,
        );
    }
}
