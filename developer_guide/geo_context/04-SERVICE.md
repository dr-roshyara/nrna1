# Geography Context - Service Layer

## Overview

The **GeographyService** provides application-level methods for common geographic queries. It abstracts domain logic and repository access.

---

## Service Architecture

```
┌─────────────────────────────────┐
│     Geography API Controller    │
└────────────┬────────────────────┘
             │
┌────────────▼────────────────────┐
│    GeographyService             │
│  (Queries, Caching)             │
└────────────┬────────────────────┘
             │
┌────────────▼────────────────────┐
│   GeoUnitRepository             │
│   (Database Abstraction)        │
└────────────┬────────────────────┘
             │
┌────────────▼────────────────────┐
│   PostgreSQL Database           │
│   (geographic_units table)      │
└─────────────────────────────────┘
```

---

## GeographyService Implementation

File: `app/Contexts/Geography/Application/GeographyService.php`

```php
<?php

namespace App\Contexts\Geography\Application;

use App\Contexts\Geography\Domain\ValueObjects\{
    CountryCode,
    GeographyLevel,
    GeoUnitId,
};
use App\Contexts\Geography\Infrastructure\Repositories\GeoUnitRepository;
use App\Models\Geography\GeoUnit;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Database\Eloquent\Collection;
use Ramsey\Uuid\Uuid;

class GeographyService
{
    private const CACHE_TTL = 86400; // 24 hours

    public function __construct(
        private GeoUnitRepository $repository,
        private Cache $cache,
    ) {}

    /**
     * Get geographic unit by ID
     *
     * @param string $id UUID
     * @param string $tenantId UUID
     * @return GeoUnit|null
     */
    public function getUnitById(string $id, string $tenantId): ?GeoUnit
    {
        $cacheKey = "geo:unit:{$id}:{$tenantId}";

        return $this->cache->remember($cacheKey, self::CACHE_TTL, function () use ($id, $tenantId) {
            return $this->repository->findForTenant($id, $tenantId);
        });
    }

    /**
     * Get all ancestors (parents + grandparents + ...)
     *
     * Example: Get all ancestors of Kathmandu District
     *   - Country (Nepal)
     *   - Province 1
     *   - Kathmandu District
     *
     * @param string $unitId UUID
     * @param string $tenantId UUID
     * @return Collection
     */
    public function getAncestors(string $unitId, string $tenantId): Collection
    {
        $unit = $this->getUnitById($unitId, $tenantId);

        if (!$unit) {
            return collect();
        }

        $cacheKey = "geo:ancestors:{$unitId}:{$tenantId}";

        return $this->cache->remember($cacheKey, self::CACHE_TTL, function () use ($unit, $tenantId) {
            return $this->repository->getAncestorsForTenant($unit->path, $tenantId);
        });
    }

    /**
     * Get all descendants (children + grandchildren + ...)
     *
     * Example: Get all descendants of Province 1
     *   - All districts in Province 1
     *   - All municipalities in those districts
     *   - All wards in those municipalities
     *
     * @param string $unitId UUID
     * @param string $tenantId UUID
     * @return Collection
     */
    public function getDescendants(string $unitId, string $tenantId): Collection
    {
        $unit = $this->getUnitById($unitId, $tenantId);

        if (!$unit) {
            return collect();
        }

        $cacheKey = "geo:descendants:{$unitId}:{$tenantId}";

        return $this->cache->remember($cacheKey, self::CACHE_TTL, function () use ($unit, $tenantId) {
            return $this->repository->getDescendantsForTenant($unit->path, $tenantId);
        });
    }

    /**
     * Get direct children only
     *
     * Example: Get all provinces in Nepal
     *
     * @param string $parentId UUID
     * @param string $tenantId UUID
     * @return Collection
     */
    public function getChildren(string $parentId, string $tenantId): Collection
    {
        return $this->repository->getChildrenForTenant($parentId, $tenantId);
    }

    /**
     * Get all units at specific level
     *
     * Example: Get all districts (level 3) in a country
     *
     * @param CountryCode $countryCode
     * @param int $level (1-8)
     * @param string $tenantId UUID
     * @return Collection
     */
    public function getUnitsByLevel(CountryCode $countryCode, int $level, string $tenantId): Collection
    {
        $cacheKey = "geo:level:{$countryCode->value()}:{$level}:{$tenantId}";

        return $this->cache->remember($cacheKey, self::CACHE_TTL, function () use ($countryCode, $level, $tenantId) {
            return $this->repository->getByLevelForTenant($countryCode->value(), $level, $tenantId);
        });
    }

    /**
     * Get hierarchy path from root to unit
     *
     * Example: Get path Nepal → Province 1 → Kathmandu → Kathmandu Metro
     *
     * @param string $unitId UUID
     * @param string $tenantId UUID
     * @return array Path of unit names
     */
    public function getHierarchyPath(string $unitId, string $tenantId): array
    {
        $ancestors = $this->getAncestors($unitId, $tenantId)->pluck('name');
        $current = $this->getUnitById($unitId, $tenantId);

        if ($current) {
            $ancestors->push($current->name);
        }

        return $ancestors->toArray();
    }

    /**
     * Find unit by country and name
     *
     * @param string $name
     * @param CountryCode $countryCode
     * @param string $tenantId UUID
     * @return GeoUnit|null
     */
    public function findByName(string $name, CountryCode $countryCode, string $tenantId): ?GeoUnit
    {
        return $this->repository->findByNameForTenant($name, $countryCode->value(), $tenantId);
    }

    /**
     * Create new geographic unit
     *
     * @param array $data
     *  - tenant_id: string (UUID)
     *  - country_code: string (2-char ISO code)
     *  - level: int (1-8)
     *  - parent_id: string|null (UUID)
     *  - name: string
     *  - code: string|null
     * @return GeoUnit
     * @throws InvalidParentChildException
     */
    public function createUnit(array $data): GeoUnit
    {
        $tenantId = $data['tenant_id'];

        // Generate path
        if ($data['level'] === 1) {
            $path = '1';
        } else {
            $parent = $this->getUnitById($data['parent_id'], $tenantId);
            if (!$parent) {
                throw new \InvalidArgumentException('Parent unit not found');
            }

            // Append new segment to parent path
            $lastSegment = (int) explode('.', $parent->path)[count(explode('.', $parent->path)) - 1];
            $path = $parent->path . '.' . ($lastSegment + 1);
        }

        $unit = new GeoUnit([
            'id' => Uuid::uuid4()->toString(),
            'tenant_id' => $tenantId,
            'country_code' => $data['country_code'],
            'level' => $data['level'],
            'parent_id' => $data['parent_id'] ?? null,
            'path' => $path,
            'name' => $data['name'],
            'code' => $data['code'] ?? null,
        ]);

        $this->repository->saveForTenant($unit, $tenantId);

        // Invalidate caches
        $this->invalidateUnitCaches($tenantId);

        return $unit;
    }

    /**
     * Update geographic unit
     *
     * @param string $id UUID
     * @param array $data
     * @param string $tenantId UUID
     * @return GeoUnit|null
     */
    public function updateUnit(string $id, array $data, string $tenantId): ?GeoUnit
    {
        $unit = $this->repository->findForTenant($id, $tenantId);

        if (!$unit) {
            return null;
        }

        // Allowed updates
        $unit->update($data);
        $this->repository->saveForTenant($unit, $tenantId);

        // Invalidate caches
        $this->invalidateUnitCaches($tenantId);
        $this->cache->forget("geo:unit:{$id}:{$tenantId}");

        return $unit;
    }

    /**
     * Delete geographic unit (soft delete only if has children)
     *
     * @param string $id UUID
     * @param string $tenantId UUID
     * @return bool
     */
    public function deleteUnit(string $id, string $tenantId): bool
    {
        $unit = $this->repository->findForTenant($id, $tenantId);

        if (!$unit) {
            return false;
        }

        // Prevent deletion if has children
        if ($this->getChildren($id, $tenantId)->count() > 0) {
            throw new \InvalidArgumentException('Cannot delete unit with children');
        }

        $this->repository->deleteForTenant($id, $tenantId);

        // Invalidate caches
        $this->invalidateUnitCaches($tenantId);
        $this->cache->forget("geo:unit:{$id}:{$tenantId}");

        return true;
    }

    /**
     * Invalidate all caches for a tenant
     */
    private function invalidateUnitCaches(string $tenantId): void
    {
        // Pattern invalidation would be ideal but Redis required
        // For now, rely on TTL (24 hours)
    }
}
```

---

## Repository Interface

File: `app/Contexts/Geography/Domain/Repositories/GeoUnitRepositoryInterface.php`

```php
<?php

namespace App\Contexts\Geography\Domain\Repositories;

use App\Models\Geography\GeoUnit;
use Illuminate\Database\Eloquent\Collection;

interface GeoUnitRepositoryInterface
{
    /**
     * Find unit by ID for tenant
     */
    public function findForTenant(string $id, string $tenantId): ?GeoUnit;

    /**
     * Get all ancestors using ltree operator @>
     */
    public function getAncestorsForTenant(string $path, string $tenantId): Collection;

    /**
     * Get all descendants using ltree operator <@
     */
    public function getDescendantsForTenant(string $path, string $tenantId): Collection;

    /**
     * Get direct children only
     */
    public function getChildrenForTenant(string $parentId, string $tenantId): Collection;

    /**
     * Get units at specific level
     */
    public function getByLevelForTenant(string $countryCode, int $level, string $tenantId): Collection;

    /**
     * Find by name
     */
    public function findByNameForTenant(string $name, string $countryCode, string $tenantId): ?GeoUnit;

    /**
     * Save unit
     */
    public function saveForTenant(GeoUnit $unit, string $tenantId): void;

    /**
     * Delete unit
     */
    public function deleteForTenant(string $id, string $tenantId): void;
}
```

---

## Repository Implementation

File: `app/Contexts/Geography/Infrastructure/Repositories/EloquentGeoUnitRepository.php`

```php
<?php

namespace App\Contexts\Geography\Infrastructure\Repositories;

use App\Contexts\Geography\Domain\Repositories\GeoUnitRepositoryInterface;
use App\Models\Geography\GeoUnit;
use Illuminate\Database\Eloquent\Collection;

class EloquentGeoUnitRepository implements GeoUnitRepositoryInterface
{
    public function findForTenant(string $id, string $tenantId): ?GeoUnit
    {
        return GeoUnit::where('tenant_id', $tenantId)
            ->where('id', $id)
            ->first();
    }

    public function getAncestorsForTenant(string $path, string $tenantId): Collection
    {
        return GeoUnit::where('tenant_id', $tenantId)
            ->whereRaw('path @> ?', [$path])
            ->where('path', '!=', $path) // Exclude self
            ->orderByRaw('nlevel(path)')
            ->get();
    }

    public function getDescendantsForTenant(string $path, string $tenantId): Collection
    {
        return GeoUnit::where('tenant_id', $tenantId)
            ->whereRaw('path <@ ?', [$path])
            ->where('path', '!=', $path) // Exclude self
            ->orderBy('path')
            ->get();
    }

    public function getChildrenForTenant(string $parentId, string $tenantId): Collection
    {
        return GeoUnit::where('tenant_id', $tenantId)
            ->where('parent_id', $parentId)
            ->orderBy('name')
            ->get();
    }

    public function getByLevelForTenant(string $countryCode, int $level, string $tenantId): Collection
    {
        return GeoUnit::where('tenant_id', $tenantId)
            ->where('country_code', $countryCode)
            ->where('level', $level)
            ->orderBy('name')
            ->get();
    }

    public function findByNameForTenant(string $name, string $countryCode, string $tenantId): ?GeoUnit
    {
        return GeoUnit::where('tenant_id', $tenantId)
            ->where('country_code', $countryCode)
            ->where('name', $name)
            ->first();
    }

    public function saveForTenant(GeoUnit $unit, string $tenantId): void
    {
        $unit->save();
    }

    public function deleteForTenant(string $id, string $tenantId): void
    {
        GeoUnit::where('tenant_id', $tenantId)
            ->where('id', $id)
            ->delete();
    }
}
```

---

## Usage Examples

### In Controllers

```php
// Get a geographic unit
$unit = $this->geographyService->getUnitById($unitId, $tenantId);

// Get all provinces in Nepal
$provinces = $this->geographyService->getUnitsByLevel(
    new CountryCode('NP'),
    2,
    $tenantId
);

// Get full path Nepal → Province → District → Municipality
$path = $this->geographyService->getHierarchyPath($municipalityId, $tenantId);
// Returns: ['Nepal', 'Province 1', 'Kathmandu', 'Kathmandu Metro City']

// Get all districts under Province 1
$districts = $this->geographyService->getDescendants($province1Id, $tenantId);

// Create new district
$newDistrict = $this->geographyService->createUnit([
    'tenant_id' => $tenantId,
    'country_code' => 'NP',
    'level' => 3,
    'parent_id' => $provinceId,
    'name' => 'Bhaktapur',
    'code' => 'BKT',
]);
```

---

## Caching Strategy

### What Gets Cached

```
┌─────────────────────────────────────┐
│ Cache TTL: 24 hours                 │
├─────────────────────────────────────┤
│ geo:unit:{id}:{tenantId}            │
│ geo:ancestors:{id}:{tenantId}       │
│ geo:descendants:{id}:{tenantId}     │
│ geo:level:{cc}:{level}:{tenantId}   │
└─────────────────────────────────────┘
```

### Cache Invalidation

```php
// Automatic invalidation on:
- createUnit()   → Clears all caches for tenant
- updateUnit()   → Clears unit + parent/child caches
- deleteUnit()   → Clears all caches for tenant
```

---

## Dependency Injection

### Register in Service Container

File: `app/Providers/GeographyServiceProvider.php`

```php
namespace App\Providers;

use App\Contexts\Geography\Application\GeographyService;
use App\Contexts\Geography\Domain\Repositories\GeoUnitRepositoryInterface;
use App\Contexts\Geography\Infrastructure\Repositories\EloquentGeoUnitRepository;
use Illuminate\Support\ServiceProvider;

class GeographyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repository interface to implementation
        $this->app->bind(
            GeoUnitRepositoryInterface::class,
            EloquentGeoUnitRepository::class
        );

        // Bind service
        $this->app->singleton(GeographyService::class, function ($app) {
            return new GeographyService(
                $app->make(EloquentGeoUnitRepository::class),
                $app->make('cache.store')
            );
        });
    }

    public function boot(): void
    {
        // Boot logic if needed
    }
}
```

### Usage in Controllers

```php
class GeographyController extends Controller
{
    public function __construct(
        private GeographyService $geographyService
    ) {}

    public function show($unitId, Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        $unit = $this->geographyService->getUnitById($unitId, $tenantId);

        return response()->json($unit);
    }
}
```

---

## Next Steps

→ See `05-TESTING.md` for comprehensive test strategy

---
