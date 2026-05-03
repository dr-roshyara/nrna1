<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\Services;

use App\Contexts\Geography\Domain\Repositories\GeoUnitRepositoryInterface;
use App\Contexts\Geography\Domain\ValueObjects\GeographyHierarchy;
use App\Contexts\Geography\Domain\ValueObjects\GeoPath;
use App\Contexts\Geography\Domain\ValueObjects\GeoUnitId;
use App\Contexts\Geography\Domain\ValueObjects\CountryCode;
use App\Contexts\Geography\Domain\Exceptions\InvalidHierarchyException;
use App\Contexts\Geography\Domain\Exceptions\InvalidParentChildException;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

/**
 * GeographyPathService
 *
 * Generates materialized paths for geographic hierarchies.
 * Uses PostgreSQL ltree format (dot-separated IDs).
 *
 * Responsibilities:
 * 1. Validate hierarchy completeness
 * 2. Validate parent-child relationships
 * 3. Generate ltree path from validated hierarchy
 * 4. Cache generated paths for performance
 */
class GeographyPathService
{
    private const CACHE_TTL = 86400; // 24 hours
    private const CACHE_PREFIX = 'geo:path:';

    public function __construct(
        private GeoUnitRepositoryInterface $repository,
        private CacheRepository $cache
    ) {}

    /**
     * Generate materialized path from geography hierarchy
     *
     * @throws InvalidHierarchyException
     * @throws InvalidParentChildException
     */
    public function generatePath(GeographyHierarchy $hierarchy): GeoPath
    {
        $cacheKey = $this->generateCacheKey($hierarchy);

        // Try cache first
        $cachedPath = $this->cache->get($cacheKey);
        if ($cachedPath instanceof GeoPath) {
            return $cachedPath;
        }

        // Generate fresh path
        $path = $this->generateFreshPath($hierarchy);

        // Cache the result
        $this->cache->put($cacheKey, $path, self::CACHE_TTL);

        return $path;
    }

    /**
     * Generate path without cache
     */
    private function generateFreshPath(GeographyHierarchy $hierarchy): GeoPath
    {
        $levelIds = $hierarchy->getLevelIds();
        $countryCode = $hierarchy->getCountryCode();

        // Filter out null values
        $filledLevels = array_filter($levelIds, fn($id) => $id !== null);

        if (empty($filledLevels)) {
            throw InvalidHierarchyException::emptyHierarchy($countryCode);
        }

        // Convert to GeoUnitIds
        $unitIds = array_map(
            fn($id) => GeoUnitId::fromInt($id),
            $filledLevels
        );

        // Validate hierarchy
        $this->validateHierarchy($countryCode, $unitIds);

        // Create GeoPath
        return GeoPath::fromIds($filledLevels);
    }

    /**
     * Validate the entire hierarchy
     */
    private function validateHierarchy(CountryCode $countryCode, array $unitIds): void
    {
        $previousUnitId = null;

        foreach ($unitIds as $index => $unitId) {
            // Check unit exists
            $unit = $this->repository->findById($unitId);
            if ($unit === null) {
                throw InvalidHierarchyException::unitNotFound($unitId->toInt(), $index + 1);
            }

            // Check unit belongs to correct country
            if (!$unit->getCountryCode()->equals($countryCode)) {
                throw InvalidHierarchyException::wrongCountry(
                    $unitId->toInt(),
                    $countryCode->toString(),
                    $unit->getCountryCode()->toString()
                );
            }

            // Check parent-child relationship (for non-first unit)
            if ($previousUnitId !== null) {
                if (!$this->repository->isChildOf($unitId, $previousUnitId)) {
                    throw InvalidParentChildException::invalidRelationship(
                        $unitId->toInt(),
                        $previousUnitId->toInt(),
                        $index + 1
                    );
                }
            }

            $previousUnitId = $unitId;
        }
    }

    /**
     * Generate cache key for hierarchy
     */
    private function generateCacheKey(GeographyHierarchy $hierarchy): string
    {
        $levelIds = $hierarchy->getLevelIds();
        $countryCode = $hierarchy->getCountryCode()->toString();

        // Create deterministic key
        $keyData = [
            'country' => $countryCode,
            'levels' => $levelIds,
        ];

        return self::CACHE_PREFIX . md5(serialize($keyData));
    }

    /**
     * Clear cache for specific hierarchy
     */
    public function clearCache(GeographyHierarchy $hierarchy): void
    {
        $cacheKey = $this->generateCacheKey($hierarchy);
        $this->cache->forget($cacheKey);
    }

    /**
     * Clear all geography path caches
     */
    public function clearAllCache(): void
    {
        $keys = $this->cache->get('geo:path:keys', []);

        foreach ($keys as $key) {
            $this->cache->forget($key);
        }

        $this->cache->forget('geo:path:keys');
    }
}