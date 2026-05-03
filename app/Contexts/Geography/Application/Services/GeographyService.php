<?php

namespace App\Contexts\Geography\Application\Services;

use App\Contexts\Geography\Domain\Models\Country;
use App\Contexts\Geography\Domain\Models\GeoAdministrativeUnit;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

/**
 * GeographyService
 *
 * Application service for geography operations with caching and validation.
 *
 * Features:
 * - Country hierarchy retrieval
 * - Administrative unit queries
 * - Hierarchy validation
 * - Ancestor/descendant queries
 * - Redis caching for performance
 */
class GeographyService
{
    /**
     * Cache TTL (Time To Live) in seconds
     */
    private const CACHE_TTL = 86400; // 24 hours

    /**
     * Cache key prefix
     */
    private const CACHE_PREFIX = 'geography';

    /**
     * Get the complete administrative hierarchy for a country.
     *
     * @param string $countryCode ISO 3166-1 alpha-2 code (NP, IN, US)
     * @return array Hierarchical structure with all levels
     */
    public function getCountryHierarchy(string $countryCode): array
    {
        $cacheKey = $this->getCacheKey("hierarchy:{$countryCode}");

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($countryCode) {
            $country = Country::findOrFail($countryCode);
            $hierarchy = [
                'country' => [
                    'code' => $country->code,
                    'name' => $country->name_en,
                    'name_local' => $country->name_local,
                ],
                'levels' => []
            ];

            foreach ($country->admin_levels as $level => $config) {
                $units = GeoAdministrativeUnit::byCountry($countryCode)
                    ->byLevel($level)
                    ->active()
                    ->orderBy('name_local->en')
                    ->get(['id', 'code', 'name_local', 'parent_id', 'admin_type'])
                    ->toArray();

                $hierarchy['levels'][$level] = [
                    'name' => $config['name'],
                    'local_name' => $config['local_name'],
                    'expected_count' => $config['count'],
                    'actual_count' => count($units),
                    'units' => $units
                ];
            }

            return $hierarchy;
        });
    }

    /**
     * Get administrative units at a specific level for a country.
     *
     * @param string $countryCode
     * @param int $adminLevel
     * @param int|null $parentId Filter by parent (optional)
     * @return Collection
     */
    public function getUnitsAtLevel(
        string $countryCode,
        int $adminLevel,
        ?int $parentId = null
    ): Collection {
        $cacheKey = $this->getCacheKey("units:{$countryCode}:{$adminLevel}:" . ($parentId ?? 'all'));

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($countryCode, $adminLevel, $parentId) {
            $query = GeoAdministrativeUnit::byCountry($countryCode)
                ->byLevel($adminLevel)
                ->active()
                ->orderBy('name_local->en');

            if ($parentId !== null) {
                $query->where('parent_id', $parentId);
            }

            return $query->get();
        });
    }

    /**
     * Get a single administrative unit by ID.
     *
     * @param int $unitId
     * @return GeoAdministrativeUnit|null
     */
    public function getUnitById(int $unitId): ?GeoAdministrativeUnit
    {
        $cacheKey = $this->getCacheKey("unit:{$unitId}");

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($unitId) {
            return GeoAdministrativeUnit::find($unitId);
        });
    }

    /**
     * Get a single administrative unit by code.
     *
     * @param string $code
     * @return GeoAdministrativeUnit|null
     */
    public function getUnitByCode(string $code): ?GeoAdministrativeUnit
    {
        $cacheKey = $this->getCacheKey("unit:code:{$code}");

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($code) {
            return GeoAdministrativeUnit::where('code', $code)->first();
        });
    }

    /**
     * Get child units of a parent unit.
     *
     * @param int $parentId
     * @return Collection
     */
    public function getChildUnits(int $parentId): Collection
    {
        $cacheKey = $this->getCacheKey("children:{$parentId}");

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($parentId) {
            return GeoAdministrativeUnit::where('parent_id', $parentId)
                ->active()
                ->orderBy('name_local->en')
                ->get();
        });
    }

    /**
     * Get all ancestors of a unit.
     *
     * @param int $unitId
     * @return Collection
     */
    public function getAncestors(int $unitId): Collection
    {
        $unit = $this->getUnitById($unitId);

        if (!$unit) {
            return collect([]);
        }

        return $unit->ancestors();
    }

    /**
     * Get all descendants of a unit.
     *
     * @param int $unitId
     * @return Collection
     */
    public function getDescendants(int $unitId): Collection
    {
        $unit = $this->getUnitById($unitId);

        if (!$unit) {
            return collect([]);
        }

        return $unit->descendants();
    }

    /**
     * Validate geographic hierarchy.
     *
     * Ensures that all provided unit IDs form a valid hierarchy:
     * - All units belong to the same country
     * - Each unit is a valid child of its parent
     * - Levels are in correct order
     *
     * @param string $countryCode
     * @param array $unitIds [level1_id, level2_id, level3_id, level4_id]
     * @return bool
     */
    public function validateGeographyHierarchy(string $countryCode, array $unitIds): bool
    {
        // Filter out null/empty values
        $unitIds = array_filter($unitIds);

        if (empty($unitIds)) {
            return true; // Empty is valid
        }

        // Fetch all units at once
        $units = GeoAdministrativeUnit::whereIn('id', $unitIds)
            ->get()
            ->keyBy('id');

        // Verify all units exist and belong to the correct country
        if ($units->count() !== count($unitIds)) {
            return false; // Some units don't exist
        }

        if (!$units->every(fn($unit) => $unit->country_code === $countryCode)) {
            return false; // Units from different country
        }

        // Sort by admin level
        $sortedUnits = $units->sortBy('admin_level')->values();

        // Validate parent-child relationships
        for ($i = 1; $i < $sortedUnits->count(); $i++) {
            $child = $sortedUnits[$i];
            $parent = $sortedUnits[$i - 1];

            if ($child->parent_id !== $parent->id) {
                return false; // Invalid hierarchy
            }
        }

        return true;
    }

    /**
     * Validate specific hierarchy (level by level).
     *
     * @param string $countryCode
     * @param int|null $level1Id
     * @param int|null $level2Id
     * @param int|null $level3Id
     * @param int|null $level4Id
     * @return array Returns ['valid' => bool, 'errors' => array]
     */
    public function validateHierarchyDetailed(
        string $countryCode,
        ?int $level1Id = null,
        ?int $level2Id = null,
        ?int $level3Id = null,
        ?int $level4Id = null
    ): array {
        $errors = [];

        // Validate level 1
        if ($level1Id) {
            $unit = $this->getUnitById($level1Id);
            if (!$unit) {
                $errors['level1'] = 'Invalid unit ID';
            } elseif ($unit->country_code !== $countryCode) {
                $errors['level1'] = 'Unit does not belong to ' . $countryCode;
            } elseif ($unit->admin_level !== 1) {
                $errors['level1'] = 'Unit is not at level 1';
            }
        }

        // Validate level 2
        if ($level2Id) {
            $unit = $this->getUnitById($level2Id);
            if (!$unit) {
                $errors['level2'] = 'Invalid unit ID';
            } elseif ($level1Id && $unit->parent_id !== $level1Id) {
                $errors['level2'] = 'Unit does not belong to selected level 1 unit';
            }
        }

        // Validate level 3
        if ($level3Id) {
            $unit = $this->getUnitById($level3Id);
            if (!$unit) {
                $errors['level3'] = 'Invalid unit ID';
            } elseif ($level2Id && $unit->parent_id !== $level2Id) {
                $errors['level3'] = 'Unit does not belong to selected level 2 unit';
            }
        }

        // Validate level 4
        if ($level4Id) {
            $unit = $this->getUnitById($level4Id);
            if (!$unit) {
                $errors['level4'] = 'Invalid unit ID';
            } elseif ($level3Id && $unit->parent_id !== $level3Id) {
                $errors['level4'] = 'Unit does not belong to selected level 3 unit';
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Clear geography cache for a specific country.
     *
     * @param string|null $countryCode If null, clears all geography cache
     * @return void
     */
    public function clearCache(?string $countryCode = null): void
    {
        if ($countryCode) {
            // Clear specific country cache
            Cache::forget($this->getCacheKey("hierarchy:{$countryCode}"));

            // Clear units cache for this country
            $country = Country::find($countryCode);
            if ($country) {
                foreach (array_keys($country->admin_levels) as $level) {
                    Cache::forget($this->getCacheKey("units:{$countryCode}:{$level}:all"));
                }
            }
        } else {
            // Clear all geography cache
            Cache::flush();
        }
    }

    /**
     * Get cache key with prefix.
     *
     * @param string $key
     * @return string
     */
    private function getCacheKey(string $key): string
    {
        return self::CACHE_PREFIX . ':' . $key;
    }

    /**
     * Get formatted geography for display.
     *
     * @param int $unitId
     * @param string $language
     * @return array
     */
    public function getUnitWithAncestors(int $unitId, string $language = 'en'): array
    {
        $unit = $this->getUnitById($unitId);

        if (!$unit) {
            return [];
        }

        $ancestors = $this->getAncestors($unitId);

        return [
            'unit' => [
                'id' => $unit->id,
                'name' => $unit->getName($language),
                'type' => $unit->admin_type,
                'level' => $unit->admin_level,
            ],
            'ancestors' => $ancestors->map(fn($a) => [
                'id' => $a->id,
                'name' => $a->getName($language),
                'type' => $a->admin_type,
                'level' => $a->admin_level,
            ])->toArray(),
            'full_path' => $unit->getFullPath($language)
        ];
    }
}
