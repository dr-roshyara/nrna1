<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\Services;

use App\Contexts\Membership\Domain\Committee\Services\GeoSemanticProjectionBuilder;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use Illuminate\Support\Collection;

/**
 * NearbyCommitteesQueryService — Read model for finding committees covering a member's geography.
 *
 * Given a member's residence geo unit ID, finds all committees whose operational
 * geography (via path prefix match) covers that location.
 *
 * Uses a JOIN on geo_administrative_units to resolve the materialized path.
 * Pure read model — no domain events, no side effects.
 */
final readonly class NearbyCommitteesQueryService
{
    public function __construct(
        private readonly GeoSemanticProjectionBuilder $projectionBuilder,
    ) {}

    /**
     * Find all committees whose geography covers the given member geo unit.
     *
     * A committee covers a member if the committee's geo unit path is an ancestor
     * of (or equal to) the member's residence geo unit path. Central committees
     * (no geo_unit_id) are excluded — they cover all members but are not "nearby".
     *
     * @param int $memberGeoUnitId Member's residence geo unit ID
     * @return Collection<CommitteeModel> Committees covering this geography
     */
    public function getNearby(int $memberGeoUnitId): Collection
    {
        $memberProjection = $this->projectionBuilder->build($memberGeoUnitId);

        if ($memberProjection === null || $memberProjection->path === '') {
            return collect();
        }

        $memberPath = $memberProjection->path;

        return CommitteeModel::query()
            ->join('geo_administrative_units', 'committees.geo_unit_id', '=', 'geo_administrative_units.id')
            ->whereRaw('? LIKE CONCAT(geo_administrative_units.path, \'%\')', [$memberPath])
            ->select('committees.*')
            ->distinct()
            ->get();
    }
}
