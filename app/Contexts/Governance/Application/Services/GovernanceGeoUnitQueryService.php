<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\Services;

use App\Contexts\Governance\Application\DTOs\GovernanceGeoUnitResponse;
use App\Contexts\Governance\Application\DTOs\GeoUnitQueryFilter;
use Illuminate\Support\Facades\DB;

/**
 * GovernanceGeoUnitQueryService
 *
 * Read-only query service for the governance-projected geographic hierarchy.
 * This is the SINGLE source of truth for reading geo_administrative_units
 * through the governance lens (level constraints, governance context join).
 *
 * CQRS: Read model — uses DB facade directly (Rule 5: Eloquent/DB for reads).
 *
 * Level constraint flow:
 * 1. Load active GovernanceLevelDefinitions for the tenant
 * 2. Resolve their geo_code (CONT→0, COUNTRY→1, PROV→2) to admin_levels
 * 3. Filter geo_administrative_units to those admin_levels only
 * 4. TODO: Integrate TenantGeographyProfile for tenant-type level range
 */
final readonly class GovernanceGeoUnitQueryService
{
    private const GEO_CODE_TO_LEVEL = [
        'CONT'    => 0,
        'COUNTRY' => 1,
        'PROV'    => 2,
    ];

    public function __construct() {}

    /**
     * Fetch flat list of governance-projected geo units with optional filters.
     *
     * @return array<GovernanceGeoUnitResponse>
     */
    public function fetchFlat(string $tenantId, GeoUnitQueryFilter $filter = new GeoUnitQueryFilter()): array
    {
        $governanceLevels = $this->loadGovernanceLevels($tenantId);

        if (empty($governanceLevels)) {
            return [];
        }

        $adminLevels = $this->resolveAdminLevels($governanceLevels);

        if (empty($adminLevels)) {
            return [];
        }

        $query = DB::table('geo_administrative_units as gau')
            ->select([
                'gau.id',
                'gau.code',
                DB::raw("COALESCE(gau.name_local->>'en', gau.code) AS name"),
                'gau.name_local',
                'gau.admin_level',
                'gau.admin_type',
                'gau.parent_id',
                'gau.path',
                'gau.is_active',
                'gau.country_code',
            ])
            ->selectSub(
                DB::table('geo_administrative_units as children')
                    ->whereColumn('children.parent_id', 'gau.id')
                    ->selectRaw('COUNT(*)'),
                'children_count'
            )
            ->whereIn('gau.admin_level', $adminLevels)
            ->where('gau.is_active', true)
            ->where('gau.organisation_id', $tenantId);

        // Apply level filter
        if ($filter->level !== null) {
            $query->where('gau.admin_level', $filter->level);
        }

        // Apply country filter
        if ($filter->country !== null) {
            $query->where('gau.country_code', $filter->country);
        }

        // Apply search filter
        if ($filter->search !== null) {
            $search = $filter->search;
            $query->where(function ($q) use ($search) {
                $q->where('gau.code', 'ilike', "%{$search}%")
                  ->orWhere('gau.name_local->en', 'ilike', "%{$search}%");
            });
        }

        $rows = $query->orderBy('gau.path')->get();

        return $this->attachGovernanceContext($rows, $governanceLevels);
    }

    /**
     * Fetch tree-ready list with depth pre-calculated from materialized path.
     *
     * Returns flat array of GovernanceGeoUnitResponse with the `depth` field
     * populated. Frontend uses `depth` for indentation — no client-side tree building.
     *
     * @return array<GovernanceGeoUnitResponse>
     */
    public function fetchTree(string $tenantId, GeoUnitQueryFilter $filter = new GeoUnitQueryFilter()): array
    {
        return $this->fetchFlat($tenantId, $filter);
    }

    /**
     * Fetch a single geo unit by ID with full governance context.
     */
    public function fetchById(string $tenantId, int $id): ?GovernanceGeoUnitResponse
    {
        $units = $this->fetchFlat($tenantId, new GeoUnitQueryFilter());

        foreach ($units as $unit) {
            if ($unit->id === $id) {
                return $unit;
            }
        }

        return null;
    }

    /**
     * Get breadcrumb trail (ancestors + self) for a geo unit.
     *
     * @return array<GovernanceGeoUnitResponse>
     */
    public function breadcrumb(string $tenantId, int $unitId): array
    {
        $unit = DB::table('geo_administrative_units')
            ->where('id', $unitId)
            ->where('organisation_id', $tenantId)
            ->first(['id', 'code', 'name_local', 'admin_level', 'admin_type', 'parent_id', 'path']);

        if (!$unit) {
            return [];
        }

        $path = trim($unit->path ?? '', '/');

        if (empty($path)) {
            return [GovernanceGeoUnitResponse::fromEloquentRow($unit)];
        }

        $ancestorIds = explode('/', $path);
        $ancestors = DB::table('geo_administrative_units')
            ->select(['id', 'code', 'name_local', 'admin_level', 'admin_type', 'parent_id', 'path'])
            ->whereIn('id', $ancestorIds)
            ->where('organisation_id', $tenantId)
            ->orderBy('admin_level')
            ->get();

        $result = [];
        foreach ($ancestors as $a) {
            $result[] = GovernanceGeoUnitResponse::fromEloquentRow($a);
        }

        // Add an extra name field for breadcrumb display
        $selfResponse = GovernanceGeoUnitResponse::fromEloquentRow($unit);
        $result[] = $selfResponse;

        return $result;
    }

    /**
     * Search/lookup for cascader/autocomplete.
     *
     * @return array<array{id: int, code: string, name: string, admin_level: int, admin_type: string, full_path: string}>
     */
    public function lookup(string $tenantId, string $query, int $limit = 20): array
    {
        if (trim($query) === '') {
            return [];
        }

        $governanceLevels = $this->loadGovernanceLevels($tenantId);

        if (empty($governanceLevels)) {
            return [];
        }

        $adminLevels = $this->resolveAdminLevels($governanceLevels);

        if (empty($adminLevels)) {
            return [];
        }

        $rows = DB::table('geo_administrative_units')
            ->select([
                'id',
                'code',
                DB::raw("COALESCE(name_local->>'en', code) AS name"),
                'admin_level',
                'admin_type',
            ])
            ->whereIn('admin_level', $adminLevels)
            ->where('is_active', true)
            ->where('organisation_id', $tenantId)
            ->where(function ($q) use ($query) {
                $q->where('code', 'ilike', "%{$query}%")
                  ->orWhere('name_local->en', 'ilike', "%{$query}%");
            })
            ->orderBy('admin_level')
            ->orderBy('code')
            ->limit($limit)
            ->get();

        return array_map(function ($row) {
            return [
                'id'         => (int) $row->id,
                'code'       => $row->code ?? '',
                'name'       => $row->name ?? '',
                'admin_level' => (int) $row->admin_level,
                'admin_type'  => $row->admin_type ?? '',
                'full_path'  => $row->name ?? '',
            ];
        }, $rows->all());
    }

    /**
     * Load active governance level definitions for a tenant as a collection keyed by geo_code.
     */
    private function loadGovernanceLevels(string $tenantId): \Illuminate\Support\Collection
    {
        return DB::table('governance_level_definitions')
            ->where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->get()
            ->keyBy('geo_code');
    }

    /**
     * Resolve geo_code (CONT, COUNTRY, PROV) to admin_level integer.
     * Only returns levels that have a known mapping.
     */
    private function resolveAdminLevels(\Illuminate\Support\Collection $governanceLevels): array
    {
        return $governanceLevels
            ->pluck('level')
            ->values()
            ->all();
    }

    /**
     * Convert DB rows to GovernanceGeoUnitResponse DTOs with governance context.
     *
     * @return array<GovernanceGeoUnitResponse>
     */
    private function attachGovernanceContext($rows, \Illuminate\Support\Collection $governanceLevels): array
    {
        $levelMap = $this->buildLevelMap($governanceLevels);

        return array_map(function ($row) use ($levelMap, $governanceLevels) {
            // Attach governance context fields to the row object
            $geoCode = $levelMap[$row->admin_level] ?? null;
            $gov = $geoCode ? ($governanceLevels[$geoCode] ?? null) : null;

            $row->governance_level = $gov->level ?? null;
            $row->governance_committee_name = $gov->committee_name ?? null;
            $row->governance_committee_code = $gov->committee_code ?? null;

            return GovernanceGeoUnitResponse::fromEloquentRow($row);
        }, iterator_to_array($rows));
    }

    /**
     * Build mapping from admin_level to geo_code for quick lookup.
     */
    private function buildLevelMap(\Illuminate\Support\Collection $governanceLevels): array
    {
        $map = [];
        foreach ($governanceLevels as $gov) {
            $map[$gov->level] = $gov->geo_code;
        }

        return $map;
    }
}
