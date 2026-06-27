<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Factories;

use App\Contexts\Membership\Domain\Committee\Projections\GeoSemanticProjection;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GeoPathChain;

/**
 * GeoPathChainFactory — Derives a GeoPathChain from a GeoSemanticProjection.
 *
 * Pure factory. No dependencies. Separates chain derivation from
 * projection building for cleaner separation of concerns.
 */
final readonly class GeoPathChainFactory
{
    /**
     * Build a GeoPathChain from a GeoSemanticProjection.
     *
     * Parses the canonical path into segment IDs.
     * Returns null if the projection has no path (central committees).
     */
    public static function from(GeoSemanticProjection $projection): GeoPathChain
    {
        $path = $projection->path;

        if ($path === '') {
            return new GeoPathChain(
                geoUnitId: $projection->geoUnitId,
                path: '',
                segments: [],
            );
        }

        $segments = array_map(
            'intval',
            array_filter(explode('/', trim($path, '/')))
        );

        return new GeoPathChain(
            geoUnitId: $projection->geoUnitId,
            path: $path,
            segments: $segments,
        );
    }
}
