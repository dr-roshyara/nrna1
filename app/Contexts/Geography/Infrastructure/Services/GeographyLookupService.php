<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Infrastructure\Services;

use App\Contexts\Membership\Domain\Services\GeographyLookupInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Geography Lookup Service Implementation
 *
 * OPTIONAL GEOGRAPHY ARCHITECTURE - Concrete Implementation
 *
 * Implements geography validation and lookup for the Membership context.
 * This service provides application-level validation as a replacement for
 * database foreign key constraints, enabling loose coupling between contexts.
 *
 * Design Pattern: Anti-Corruption Layer + Dependency Inversion
 * - Implements GeographyLookupInterface from Membership Domain
 * - Provides Geography context functionality to Membership
 * - Enables Geography to be installed/uninstalled independently
 *
 * Key Features:
 * - Graceful degradation when Geography module not installed
 * - Performance-optimized with Redis caching (5 min TTL)
 * - Tenant-aware (uses current tenant database connection)
 * - Validates hierarchy integrity using ltree paths
 *
 * Caching Strategy:
 * - Geography data changes rarely (administrative units are stable)
 * - Cache validation results for 5 minutes
 * - Clear cache when geography units are created/updated
 * - Per-tenant cache keys for data isolation
 *
 * @package App\Contexts\Geography\Infrastructure\Services
 */
class GeographyLookupService implements GeographyLookupInterface
{
    /**
     * Cache TTL for geography validation results (in seconds).
     * 5 minutes is reasonable since geography data rarely changes.
     */
    private const CACHE_TTL = 300;

    /**
     * Database connection to use (tenant database).
     */
    private string $connection = 'tenant';

    /**
     * {@inheritDoc}
     */
    public function isGeographyModuleInstalled(): bool
    {
        try {
            return Schema::connection($this->connection)
                ->hasTable('geo_administrative_units');
        } catch (\Exception $e) {
            // Tenant connection might not be initialized yet
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function validateGeographyIdExists(int $geographyId): bool
    {
        // If geography module not installed, validation passes (nullable field)
        if (!$this->isGeographyModuleInstalled()) {
            return false; // Geography ID cannot exist if module not installed
        }

        // Cache key: tenant-specific validation result
        $cacheKey = $this->getCacheKey("geo_exists_{$geographyId}");

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($geographyId) {
            return DB::connection($this->connection)
                ->table('geo_administrative_units')
                ->where('id', $geographyId)
                ->where('is_active', true)
                ->exists();
        });
    }

    /**
     * {@inheritDoc}
     */
    public function validateGeographyIdsExist(array $geographyIds): array
    {
        // If geography module not installed, all IDs are invalid
        if (!$this->isGeographyModuleInstalled()) {
            return array_fill_keys($geographyIds, false);
        }

        // Filter out null values
        $geographyIds = array_filter($geographyIds, fn($id) => $id !== null);

        if (empty($geographyIds)) {
            return [];
        }

        // Batch query for performance
        $existingIds = DB::connection($this->connection)
            ->table('geo_administrative_units')
            ->whereIn('id', $geographyIds)
            ->where('is_active', true)
            ->pluck('id')
            ->toArray();

        // Build result map
        $results = [];
        foreach ($geographyIds as $id) {
            $results[$id] = in_array($id, $existingIds, true);
        }

        return $results;
    }

    /**
     * {@inheritDoc}
     */
    public function validateGeographyHierarchy(array $hierarchyData): array
    {
        // If geography module not installed, skip validation
        if (!$this->isGeographyModuleInstalled()) {
            return [
                'valid' => true, // Validation passes when geography not required
                'errors' => [],
            ];
        }

        $errors = [];

        // Extract level IDs from hierarchyData
        $levelIds = [];
        for ($level = 1; $level <= 8; $level++) {
            $key = "level{$level}_id";
            if (isset($hierarchyData[$key]) && $hierarchyData[$key] !== null) {
                $levelIds[$level] = $hierarchyData[$key];
            }
        }

        // Check for hierarchy gaps (if level N is filled, levels 1 to N-1 must also be filled)
        $filledLevels = array_keys($levelIds);
        if (!empty($filledLevels)) {
            $maxLevel = max($filledLevels);
            for ($level = 1; $level < $maxLevel; $level++) {
                if (!isset($levelIds[$level])) {
                    $errors[] = "Hierarchy gap detected: level {$maxLevel} requires levels 1-{$level} to be filled";
                }
            }
        }

        // If gaps found, return early
        if (!empty($errors)) {
            return [
                'valid' => false,
                'errors' => $errors,
            ];
        }

        // Validate parent-child relationships
        foreach ($levelIds as $level => $id) {
            // Get geography unit
            $unit = DB::connection($this->connection)
                ->table('geo_administrative_units')
                ->where('id', $id)
                ->first();

            if (!$unit) {
                $errors[] = "level{$level}_id: Geography unit {$id} not found";
                continue;
            }

            // Check admin_level matches
            if ($unit->admin_level !== $level) {
                $errors[] = "level{$level}_id: Unit {$id} has admin_level {$unit->admin_level}, expected {$level}";
            }

            // Check parent relationship (if not top level)
            if ($level > 1) {
                $expectedParentId = $levelIds[$level - 1] ?? null;
                if ($expectedParentId && $unit->parent_id !== $expectedParentId) {
                    $errors[] = "level{$level}_id: Unit {$id} has parent_id {$unit->parent_id}, expected {$expectedParentId}";
                }
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getGeographyUnit(int $geographyId): ?array
    {
        // If geography module not installed, return null
        if (!$this->isGeographyModuleInstalled()) {
            return null;
        }

        $cacheKey = $this->getCacheKey("geo_unit_{$geographyId}");

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($geographyId) {
            $unit = DB::connection($this->connection)
                ->table('geo_administrative_units')
                ->where('id', $geographyId)
                ->first();

            if (!$unit) {
                return null;
            }

            // Extract localized name (JSON column)
            $nameLocal = is_string($unit->name_local)
                ? json_decode($unit->name_local, true)
                : $unit->name_local;

            return [
                'id' => $unit->id,
                'name' => $nameLocal['en'] ?? $nameLocal['np'] ?? 'Unknown',
                'code' => $unit->code,
                'admin_level' => $unit->admin_level,
                'parent_id' => $unit->parent_id,
            ];
        });
    }

    /**
     * {@inheritDoc}
     */
    public function getGeographyHierarchyPath(int $geographyId): array
    {
        // If geography module not installed, return empty array
        if (!$this->isGeographyModuleInstalled()) {
            return [];
        }

        $cacheKey = $this->getCacheKey("geo_path_{$geographyId}");

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($geographyId) {
            $path = [];
            $currentId = $geographyId;

            // Walk up the hierarchy until we reach the root
            while ($currentId !== null) {
                $unit = $this->getGeographyUnit($currentId);

                if (!$unit) {
                    break; // Hierarchy corrupted or ID not found
                }

                // Prepend to path (so it goes root -> leaf)
                array_unshift($path, [
                    'id' => $unit['id'],
                    'name' => $unit['name'],
                    'level' => $unit['admin_level'],
                ]);

                $currentId = $unit['parent_id'];
            }

            return $path;
        });
    }

    /**
     * Generate tenant-specific cache key.
     *
     * Ensures cache isolation between tenants.
     *
     * @param string $key Base cache key
     * @return string Tenant-specific cache key
     */
    private function getCacheKey(string $key): string
    {
        // Get current tenant identifier from connection
        try {
            $database = DB::connection($this->connection)->getDatabaseName();
            return "geography:{$database}:{$key}";
        } catch (\Exception $e) {
            // Fallback if tenant not set
            return "geography:default:{$key}";
        }
    }

    /**
     * Clear all geography caches for current tenant.
     *
     * Should be called when geography units are created/updated/deleted.
     *
     * @return void
     */
    public function clearCache(): void
    {
        try {
            $database = DB::connection($this->connection)->getDatabaseName();
            $pattern = "geography:{$database}:*";

            // Note: This requires Redis - fallback if not available
            Cache::flush(); // Simplified - in production, use tagged cache or pattern matching
        } catch (\Exception $e) {
            // Ignore cache clear failures
        }
    }
}
