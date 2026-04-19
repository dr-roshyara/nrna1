# Geography Context - Testing Strategy

## Overview

Geography Context testing uses **TDD-first approach** with 100% coverage of:
- Domain Value Objects
- Domain Services
- Repository Contracts
- Application Service Layer

---

## Test Structure

```
tests/Unit/Contexts/Geography/
├── Domain/
│   ├── ValueObjects/
│   │   ├── CountryCodeTest.php
│   │   ├── GeoPathTest.php
│   │   ├── GeographyLevelTest.php
│   │   ├── LocalizedNameTest.php
│   │   └── GeographyHierarchyTest.php
│   ├── GeographicUnitTest.php
│   └── Repositories/
│       └── GeoUnitRepositoryTest.php
├── Application/
│   └── GeographyServiceTest.php
└── Infrastructure/
    └── Repositories/
        └── EloquentGeoUnitRepositoryTest.php

tests/Feature/Contexts/Geography/
└── GeographyAPITest.php
```

---

## Unit Tests: Value Objects

### CountryCode Tests

File: `tests/Unit/Contexts/Geography/Domain/ValueObjects/CountryCodeTest.php`

```php
<?php

namespace Tests\Unit\Contexts\Geography\Domain\ValueObjects;

use App\Contexts\Geography\Domain\Exceptions\CountryNotSupportedException;
use App\Contexts\Geography\Domain\ValueObjects\CountryCode;
use PHPUnit\Framework\TestCase;

class CountryCodeTest extends TestCase
{
    /** @test */
    public function it_creates_valid_country_code(): void
    {
        $code = new CountryCode('NP');

        $this->assertEquals('NP', $code->value());
    }

    /** @test */
    public function it_normalizes_lowercase_to_uppercase(): void
    {
        $code = new CountryCode('np');

        $this->assertEquals('NP', $code->value());
    }

    /** @test */
    public function it_throws_for_unsupported_country(): void
    {
        $this->expectException(CountryNotSupportedException::class);

        new CountryCode('XX');
    }

    /** @test */
    public function it_compares_country_codes(): void
    {
        $code1 = new CountryCode('NP');
        $code2 = new CountryCode('NP');
        $code3 = new CountryCode('IN');

        $this->assertTrue($code1->equals($code2));
        $this->assertFalse($code1->equals($code3));
    }

    /** @test */
    public function it_supports_nepal_india_usa(): void
    {
        $nepal = new CountryCode('NP');
        $india = new CountryCode('IN');
        $usa = new CountryCode('US');

        $this->assertEquals('NP', $nepal->value());
        $this->assertEquals('IN', $india->value());
        $this->assertEquals('US', $usa->value());
    }
}
```

### GeoPath Tests

File: `tests/Unit/Contexts/Geography/Domain/ValueObjects/GeoPathTest.php`

```php
<?php

namespace Tests\Unit\Contexts\Geography\Domain\ValueObjects;

use App\Contexts\Geography\Domain\Exceptions\InvalidHierarchyException;
use App\Contexts\Geography\Domain\Exceptions\MaxHierarchyDepthException;
use App\Contexts\Geography\Domain\ValueObjects\GeoPath;
use PHPUnit\Framework\TestCase;

class GeoPathTest extends TestCase
{
    /** @test */
    public function it_creates_valid_path(): void
    {
        $path = new GeoPath('1.12.123');

        $this->assertEquals('1.12.123', $path->toString());
    }

    /** @test */
    public function it_creates_from_segments(): void
    {
        $path = GeoPath::fromSegments([1, 12, 123]);

        $this->assertEquals('1.12.123', $path->toString());
    }

    /** @test */
    public function it_appends_segment(): void
    {
        $path = new GeoPath('1.12');
        $extended = $path->append(123);

        $this->assertEquals('1.12.123', $extended->toString());
    }

    /** @test */
    public function it_calculates_depth(): void
    {
        $this->assertEquals(1, (new GeoPath('1'))->depth());
        $this->assertEquals(2, (new GeoPath('1.12'))->depth());
        $this->assertEquals(3, (new GeoPath('1.12.123'))->depth());
    }

    /** @test */
    public function it_throws_for_zero_segment(): void
    {
        $this->expectException(InvalidHierarchyException::class);

        new GeoPath('1.0.123');
    }

    /** @test */
    public function it_throws_for_non_numeric_segment(): void
    {
        $this->expectException(InvalidHierarchyException::class);

        new GeoPath('1.abc.123');
    }

    /** @test */
    public function it_throws_for_depth_exceeding_8(): void
    {
        $this->expectException(MaxHierarchyDepthException::class);

        new GeoPath('1.2.3.4.5.6.7.8.9');
    }

    /** @test */
    public function it_rejects_invalid_append(): void
    {
        $path = new GeoPath('1.12');

        $this->expectException(\InvalidArgumentException::class);

        $path->append(0);
    }
}
```

### GeographyLevel Tests

File: `tests/Unit/Contexts/Geography/Domain/ValueObjects/GeographyLevelTest.php`

```php
<?php

namespace Tests\Unit\Contexts\Geography\Domain\ValueObjects;

use App\Contexts\Geography\Domain\ValueObjects\GeographyLevel;
use PHPUnit\Framework\TestCase;

class GeographyLevelTest extends TestCase
{
    /** @test */
    public function it_creates_valid_levels(): void
    {
        foreach (range(1, 8) as $level) {
            $gl = new GeographyLevel($level);
            $this->assertEquals($level, $gl->value());
        }
    }

    /** @test */
    public function it_throws_for_level_zero(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new GeographyLevel(0);
    }

    /** @test */
    public function it_throws_for_level_exceeding_8(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new GeographyLevel(9);
    }

    /** @test */
    public function it_identifies_root_level(): void
    {
        $root = new GeographyLevel(1);
        $nonRoot = new GeographyLevel(2);

        $this->assertTrue($root->isRoot());
        $this->assertFalse($nonRoot->isRoot());
    }

    /** @test */
    public function it_gets_next_level(): void
    {
        $level1 = new GeographyLevel(1);
        $level2 = $level1->next();

        $this->assertEquals(2, $level2->value());
    }

    /** @test */
    public function it_compares_levels(): void
    {
        $level1 = new GeographyLevel(3);
        $level1b = new GeographyLevel(3);
        $level2 = new GeographyLevel(4);

        $this->assertTrue($level1->equals($level1b));
        $this->assertFalse($level1->equals($level2));
    }
}
```

---

## Unit Tests: Domain Service

### GeographyService Tests

File: `tests/Unit/Contexts/Geography/Application/GeographyServiceTest.php`

```php
<?php

namespace Tests\Unit\Contexts\Geography\Application;

use App\Contexts\Geography\Application\GeographyService;
use App\Contexts\Geography\Domain\Repositories\GeoUnitRepositoryInterface;
use App\Contexts\Geography\Domain\ValueObjects\CountryCode;
use App\Models\Geography\GeoUnit;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class GeographyServiceTest extends TestCase
{
    private GeoUnitRepositoryInterface $repository;
    private Cache $cache;
    private GeographyService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = Mockery::mock(GeoUnitRepositoryInterface::class);
        $this->cache = Mockery::mock(Cache::class);
        $this->service = new GeographyService($this->repository, $this->cache);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_gets_unit_from_cache(): void
    {
        $unitId = Uuid::uuid4()->toString();
        $tenantId = Uuid::uuid4()->toString();
        $unit = new GeoUnit(['id' => $unitId, 'tenant_id' => $tenantId]);

        $this->cache->shouldReceive('remember')
            ->once()
            ->andReturn($unit);

        $result = $this->service->getUnitById($unitId, $tenantId);

        $this->assertEquals($unitId, $result->id);
    }

    /** @test */
    public function it_returns_null_for_nonexistent_unit(): void
    {
        $unitId = Uuid::uuid4()->toString();
        $tenantId = Uuid::uuid4()->toString();

        $this->cache->shouldReceive('remember')
            ->once()
            ->andReturn(null);

        $result = $this->service->getUnitById($unitId, $tenantId);

        $this->assertNull($result);
    }

    /** @test */
    public function it_gets_ancestors(): void
    {
        $unitId = Uuid::uuid4()->toString();
        $tenantId = Uuid::uuid4()->toString();
        $path = '1.12.123';

        $unit = new GeoUnit(['id' => $unitId, 'path' => $path]);
        $ancestors = collect([
            new GeoUnit(['id' => Uuid::uuid4()->toString(), 'path' => '1']),
            new GeoUnit(['id' => Uuid::uuid4()->toString(), 'path' => '1.12']),
        ]);

        $this->cache->shouldReceive('remember')
            ->andReturnUsing(function ($key, $ttl, $callback) use ($unit, $ancestors) {
                if (str_contains($key, 'unit')) {
                    return $unit;
                }
                return $ancestors;
            });

        $this->repository->shouldReceive('getAncestorsForTenant')
            ->andReturn($ancestors);

        $result = $this->service->getAncestors($unitId, $tenantId);

        $this->assertCount(2, $result);
    }

    /** @test */
    public function it_gets_descendants(): void
    {
        $unitId = Uuid::uuid4()->toString();
        $tenantId = Uuid::uuid4()->toString();
        $path = '1.12';

        $unit = new GeoUnit(['id' => $unitId, 'path' => $path]);
        $descendants = collect([
            new GeoUnit(['id' => Uuid::uuid4()->toString(), 'path' => '1.12.123']),
            new GeoUnit(['id' => Uuid::uuid4()->toString(), 'path' => '1.12.124']),
        ]);

        $this->cache->shouldReceive('remember')
            ->andReturnUsing(function ($key, $ttl, $callback) use ($unit, $descendants) {
                if (str_contains($key, 'unit')) {
                    return $unit;
                }
                return $descendants;
            });

        $this->repository->shouldReceive('getDescendantsForTenant')
            ->andReturn($descendants);

        $result = $this->service->getDescendants($unitId, $tenantId);

        $this->assertCount(2, $result);
    }

    /** @test */
    public function it_creates_unit(): void
    {
        $tenantId = Uuid::uuid4()->toString();

        $this->repository->shouldReceive('saveForTenant')->once();
        $this->cache->shouldReceive('remember')->andReturnUsing(function ($key, $ttl, $cb) {
            return $cb();
        });

        $unit = $this->service->createUnit([
            'tenant_id' => $tenantId,
            'country_code' => 'NP',
            'level' => 1,
            'name' => 'Nepal',
        ]);

        $this->assertEquals('Nepal', $unit->name);
        $this->assertEquals('NP', $unit->country_code);
    }
}
```

---

## Integration Tests: Database Queries

### EloquentGeoUnitRepository Tests

File: `tests/Feature/Contexts/Geography/EloquentGeoUnitRepositoryTest.php`

```php
<?php

namespace Tests\Feature\Contexts\Geography;

use App\Contexts\Geography\Infrastructure\Repositories\EloquentGeoUnitRepository;
use App\Models\Geography\GeoUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class EloquentGeoUnitRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected string $connection = 'pgsql_geo';
    private EloquentGeoUnitRepository $repository;
    private string $tenantId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentGeoUnitRepository();
        $this->tenantId = Uuid::uuid4()->toString();
    }

    private function createUnit(array $overrides = []): GeoUnit
    {
        return GeoUnit::create(array_merge([
            'id' => Uuid::uuid4()->toString(),
            'tenant_id' => $this->tenantId,
            'country_code' => 'NP',
            'level' => 1,
            'path' => '1',
            'name' => 'Nepal',
        ], $overrides));
    }

    /** @test */
    public function it_finds_unit_for_tenant(): void
    {
        $unit = $this->createUnit();

        $found = $this->repository->findForTenant($unit->id, $this->tenantId);

        $this->assertEquals($unit->id, $found->id);
    }

    /** @test */
    public function it_returns_null_for_other_tenant(): void
    {
        $unit = $this->createUnit();
        $otherTenantId = Uuid::uuid4()->toString();

        $found = $this->repository->findForTenant($unit->id, $otherTenantId);

        $this->assertNull($found);
    }

    /** @test */
    public function it_gets_ancestors_using_ltree(): void
    {
        $country = $this->createUnit(['level' => 1, 'path' => '1']);
        $province = $this->createUnit(['level' => 2, 'parent_id' => $country->id, 'path' => '1.1']);
        $district = $this->createUnit(['level' => 3, 'parent_id' => $province->id, 'path' => '1.1.1']);

        $ancestors = $this->repository->getAncestorsForTenant('1.1.1', $this->tenantId);

        $this->assertCount(2, $ancestors);
        $this->assertTrue($ancestors->pluck('id')->contains($country->id));
        $this->assertTrue($ancestors->pluck('id')->contains($province->id));
    }

    /** @test */
    public function it_gets_descendants_using_ltree(): void
    {
        $country = $this->createUnit(['level' => 1, 'path' => '1']);
        $province = $this->createUnit(['level' => 2, 'parent_id' => $country->id, 'path' => '1.1']);
        $district = $this->createUnit(['level' => 3, 'parent_id' => $province->id, 'path' => '1.1.1']);

        $descendants = $this->repository->getDescendantsForTenant('1', $this->tenantId);

        $this->assertCount(2, $descendants);
        $this->assertTrue($descendants->pluck('id')->contains($province->id));
        $this->assertTrue($descendants->pluck('id')->contains($district->id));
    }

    /** @test */
    public function it_gets_children_only(): void
    {
        $country = $this->createUnit(['level' => 1, 'path' => '1']);
        $province1 = $this->createUnit([
            'level' => 2,
            'parent_id' => $country->id,
            'path' => '1.1',
            'name' => 'Province 1'
        ]);
        $province2 = $this->createUnit([
            'level' => 2,
            'parent_id' => $country->id,
            'path' => '1.2',
            'name' => 'Province 2'
        ]);

        $children = $this->repository->getChildrenForTenant($country->id, $this->tenantId);

        $this->assertCount(2, $children);
    }

    /** @test */
    public function it_gets_units_by_level(): void
    {
        $country = $this->createUnit(['level' => 1, 'path' => '1']);
        $province1 = $this->createUnit([
            'level' => 2,
            'parent_id' => $country->id,
            'path' => '1.1',
            'name' => 'Province 1'
        ]);
        $province2 = $this->createUnit([
            'level' => 2,
            'parent_id' => $country->id,
            'path' => '1.2',
            'name' => 'Province 2'
        ]);

        $provinces = $this->repository->getByLevelForTenant('NP', 2, $this->tenantId);

        $this->assertCount(2, $provinces);
    }

    /** @test */
    public function it_finds_by_name(): void
    {
        $unit = $this->createUnit(['name' => 'Kathmandu']);

        $found = $this->repository->findByNameForTenant('Kathmandu', 'NP', $this->tenantId);

        $this->assertEquals($unit->id, $found->id);
    }

    /** @test */
    public function it_deletes_unit(): void
    {
        $unit = $this->createUnit();

        $this->repository->deleteForTenant($unit->id, $this->tenantId);

        $found = $this->repository->findForTenant($unit->id, $this->tenantId);
        $this->assertNull($found);
    }
}
```

---

## Running Tests

### Test Commands

```bash
# Run all Geography tests
php artisan test tests/Unit/Contexts/Geography
php artisan test tests/Feature/Contexts/Geography

# Run specific test file
php artisan test tests/Unit/Contexts/Geography/Domain/ValueObjects/GeoPathTest.php

# Run with coverage
php artisan test --coverage tests/Unit/Contexts/Geography

# Run against PostgreSQL
php artisan test --database=pgsql_geo tests/Feature/Contexts/Geography
```

---

## Coverage Goals

Target: **100% coverage** of:

```
✓ All Value Objects
✓ Domain exceptions
✓ Repository contracts
✓ Service methods
✓ Ltree queries (ancestors, descendants, level)
✓ Multi-tenant isolation
✓ Error scenarios
✓ Cache invalidation
```

---

## Next Steps

→ See `06-TROUBLESHOOTING.md` for common issues

---
