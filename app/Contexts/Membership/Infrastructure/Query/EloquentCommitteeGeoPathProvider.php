<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Query;

use App\Contexts\Membership\Application\Membership\Query\Ports\CommitteeGeoPathProviderPort;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GeoPathChain;
use App\Contexts\Membership\Domain\Committee\Services\GeoSemanticProjectionBuilder;
use App\Contexts\Membership\Domain\Committee\Factories\GeoPathChainFactory;

final class EloquentCommitteeGeoPathProvider implements CommitteeGeoPathProviderPort
{
    public function __construct(
        private readonly GeoSemanticProjectionBuilder $geoBuilder,
    ) {}

    public function resolveForCommittee(int $geoUnitId): GeoPathChain
    {
        $projection = $this->geoBuilder->build($geoUnitId);

        if ($projection === null) {
            throw new \RuntimeException("Geo unit not found: {$geoUnitId}");
        }

        return GeoPathChainFactory::from($projection);
    }
}
