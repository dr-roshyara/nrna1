<?php

declare(strict_types=1);

namespace App\Services\Governance;

use App\Models\GovernanceLevelDefinition;
use App\Models\Organisation;
use Illuminate\Support\Facades\DB;

/**
 * GovernanceGeographyProjectionBuilder
 *
 * Builds a governance-constrained projection over geo_administrative_units.
 *
 * Governance Geography is NOT a separate model or new table. It is a filtered
 * read-optimized view of the canonical geo_administrative_units table, constrained by:
 *
 * 1. GovernanceLevelDefinition — maps governance levels to geographic levels
 * 2. Country scope — from organisation settings
 * 3. Active only — is_active = true
 */
final readonly class GovernanceGeographyProjectionBuilder
{
    /**
     * Build a governance-constrained unit list for an organisation.
     *
     * Returns units ordered by materialized path for frontend tree rendering.
     * Each unit carries governance context (governance_level, committee_name, committee_code)
     * when a governance level definition maps to that unit's admin_level.
     */
    public function forOrganisation(Organisation $org): array
    {
        $governanceLevels = GovernanceLevelDefinition::where('tenant_id', $org->id)
            ->where('is_active', true)
            ->get()
            ->keyBy('geo_code');

        return $this->queryUnits($org, $governanceLevels);
    }

    /**
     * Get a flat list with optional filters (search, level, country).
     */
    public function flatList(Organisation $org, array $filters = []): array
    {
        $governanceLevels = GovernanceLevelDefinition::where('tenant_id', $org->id)
            ->where('is_active', true)
            ->get()
            ->keyBy('geo_code');

        $query = $this->buildBaseQuery($org, $governanceLevels);

        if (isset($filters['level']) && $filters['level'] !== '') {
            $query->where('gau.admin_level', (int) $filters['level']);
        }

        if (isset($filters['country']) && $filters['country'] !== '') {
            $query->where('gau.country_code', strtoupper($filters['country']));
        }

        if (isset($filters['search']) && $filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('gau.code', 'ilike', "%{$search}%")
                  ->orWhere('gau.name_local->en', 'ilike', "%{$search}%");
            });
        }

        $rows = $query->orderBy('gau.path')->get();

        return $this->attachGovernanceContext($rows, $governanceLevels);
    }

    /**
     * Build a hierarchical tree from a flat list.
     *
     * Groups children under their parents using parent_id references.
     * Returns roots (null parent_id) with nested children arrays.
     */
    public function buildTree(array $flatUnits): array
    {
        $index = [];
        $roots = [];

        foreach ($flatUnits as $unit) {
            $unit['children'] = [];
            $index[$unit['id']] = $unit;
        }

        foreach ($index as $id => &$unit) {
            if ($unit['parent_id'] !== null && isset($index[$unit['parent_id']])) {
                $index[$unit['parent_id']]['children'][] = &$unit;
            } else {
                $roots[] = &$unit;
            }
        }
        unset($unit);

        return $roots;
    }

    /**
     * Get breadcrumb trail for a specific geo unit.
     *
     * Uses materialized path to efficiently find all ancestors.
     */
    public function breadcrumb(int $unitId): array
    {
        $unit = DB::table('geo_administrative_units')
            ->select(['id', 'code', DB::raw("name_local->>'en' AS name"), 'admin_level', 'admin_type', 'parent_id', 'path'])
            ->where('id', $unitId)
            ->first();

        if (!$unit) {
            return [];
        }

        $unit = (array) $unit;
        $path = trim($unit['path'] ?? '', '/');

        if (empty($path)) {
            return [$unit];
        }

        $ancestorIds = explode('/', $path);
        $ancestors = DB::table('geo_administrative_units')
            ->select(['id', 'code', DB::raw("name_local->>'en' AS name"), 'admin_level', 'admin_type'])
            ->whereIn('id', $ancestorIds)
            ->orderBy('admin_level')
            ->get()
            ->toArray();

        $result = [];
        foreach ($ancestors as $a) {
            $result[] = (array) $a;
        }
        $result[] = $unit;

        return $result;
    }

    /**
     * Query geo_administrative_units filtered by governance level definitions.
     * Uses the level-to-code mapping defined in governance_level_definitions to determine
     * which admin_levels are relevant (e.g., geo_code 'CONT' → admin_level 0).
     */
    private function queryUnits(Organisation $org, \Illuminate\Support\Collection $governanceLevels): array
    {
        if ($governanceLevels->isEmpty()) {
            return [];
        }

        $rows = $this->buildBaseQuery($org, $governanceLevels)
            ->orderBy('gau.path')
            ->get();

        return $this->attachGovernanceContext($rows, $governanceLevels);
    }

    private function buildBaseQuery(Organisation $org, \Illuminate\Support\Collection $governanceLevels)
    {
        $adminLevels = $this->resolveAdminLevels($governanceLevels);

        if (empty($adminLevels)) {
            return DB::table('geo_administrative_units as gau')->whereRaw('1 = 0');
        }

        return DB::table('geo_administrative_units as gau')
            ->select([
                'gau.id',
                'gau.code',
                DB::raw("gau.name_local->>'en' AS name"),
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
            ->where('gau.is_active', true);
    }

    /**
     * Attach governance context (level, committee_name, committee_code) to each unit
     * based on the mapping between admin_level and governance geo_code.
     */
    private function attachGovernanceContext($rows, \Illuminate\Support\Collection $governanceLevels): array
    {
        $levelMap = $this->buildLevelMap($governanceLevels);

        return collect($rows)->map(function ($row) use ($levelMap, $governanceLevels) {
            $unit = (array) $row;
            $geoCode = $levelMap[$unit['admin_level']] ?? null;
            $gov = $geoCode ? ($governanceLevels[$geoCode] ?? null) : null;

            $unit['governance_level'] = $gov ? $gov->level : null;
            $unit['governance_committee_name'] = $gov ? $gov->committee_name : null;
            $unit['governance_committee_code'] = $gov ? $gov->committee_code : null;

            return $unit;
        })->all();
    }

    /**
     * Build mapping from geo_code (CONT, COUNTRY, PROV) to admin_level.
     * This is the bridge between governance level definitions and geo_administrative_units.
     */
    private function resolveAdminLevels(\Illuminate\Support\Collection $governanceLevels): array
    {
        $geoCodeToLevel = [
            'CONT'    => 0,
            'COUNTRY' => 1,
            'PROV'    => 2,
        ];

        return $governanceLevels
            ->pluck('geo_code')
            ->map(fn(string $code) => $geoCodeToLevel[$code] ?? null)
            ->filter(fn($value) => $value !== null)
            ->values()
            ->all();
    }

    /**
     * Build mapping from admin_level to geo_code for quick lookup.
     */
    private function buildLevelMap(\Illuminate\Support\Collection $governanceLevels): array
    {
        $geoCodeToLevel = [
            'CONT'    => 0,
            'COUNTRY' => 1,
            'PROV'    => 2,
        ];

        $map = [];
        foreach ($governanceLevels as $gov) {
            $adminLevel = $geoCodeToLevel[$gov->geo_code] ?? null;
            if ($adminLevel !== null) {
                $map[$adminLevel] = $gov->geo_code;
            }
        }

        return $map;
    }
}
