# Phase 8C.2B — GeographicJurisdictionProvider Anti-Corruption Layer (Boundary Only)

## Context

Phase 8C.2A added `geo_unit_id` as a storage-only foreign key to the `committees` table. Phase 8C.2B creates the **anti-corruption layer (ACL) boundary** — the domain port, DTO, and infrastructure adapter that enable semantic inference in Phase 8C.2C while keeping the domain layer pure.

### Why Boundary-First?

The Membership context needs geographic information (admin level, region code, country code) to make decisions about committee categorization. But it must not depend on the Geography context's Eloquent models or domain objects directly.

The Hexagonal Architecture pattern solves this:

```
┌─────────────────────────────────────────────────────┐
│                  Membership Context                   │
│                                                      │
│  Domain Layer (pure PHP, no framework deps)          │
│    ┌─────────────────────────────────────────┐      │
│    │  GeographicJurisdictionProvider (interface)│    │
│    │  GeographicJurisdiction (readonly DTO)   │    │
│    └────────────┬────────────────────────────┘      │
│                 │ implements                         │
│                 ▼                                    │
│  Infrastructure Layer (Laravel/Eloquent allowed)      │
│    ┌─────────────────────────────────────────┐      │
│    │  GeographicJurisdictionProviderAdapter   │      │
│    │  (queries GeoAdministrativeUnit model)   │      │
│    └─────────────────────────────────────────┘      │
│                                                      │
│           Bounded Context Boundary                   │
├─────────────────────────────────────────────────────┤
│                  Geography Context                    │
│                                                      │
│    GeoAdministrativeUnit (Eloquent model)            │
│    (queried by adapter, not by domain layer)         │
└─────────────────────────────────────────────────────┘
```

## Scope Rule

> **8C.2B = infrastructure boundary only. No interpretation, no mapping, no inference.**
> **8C.2C = semantic logic (adminLevel → category mapping, resolver inference, canonical format).**
> **8C.2D = frontend changes (tree selector replacing type dropdown).**

### What 8C.2B Delivers

| Deliverable | Layer | File |
|-------------|-------|------|
| `GeographicJurisdictionProvider` interface | Domain Port | `Domain/Committee/Ports/GeographicJurisdictionProvider.php` |
| `GeographicJurisdiction` DTO | Domain Port | `Domain/Committee/Ports/GeographicJurisdiction.php` |
| `GeographicJurisdictionProviderAdapter` | Infrastructure | `Infrastructure/Services/GeographicJurisdictionProviderAdapter.php` |
| DI binding in `MembershipServiceProvider` | Infrastructure | `Infrastructure/Providers/MembershipServiceProvider.php` |
| DTO unit tests | Tests | `tests/Unit/Domain/Committee/Ports/GeographicJurisdictionTest.php` |
| Adapter unit tests | Tests | `tests/Unit/Infrastructure/Services/GeographicJurisdictionProviderAdapterTest.php` |

### What Is EXPLICITLY Deferred to 8C.2C

- `CommitteePolicyResolver` changes (no provider injection, no inference path)
- `CommitteeStructure::getLevelIndexByAdminLevel()` or equivalent mapping
- Any `categoryFromAdminLevel()` derivation
- Any change to committee creation behavior
- Canonical format changes
- Any consumption of the provider by business logic

**The provider interface is defined but not consumed by any business logic in this phase.** Committee creation continues to work exactly as before.

## Architectural Reasoning

### Why the Port Belongs in the Domain Layer

The `GeographicJurisdictionProvider` interface lives in `Membership\Domain\Committee\Ports\` even though it references a concept from the Geography context. This is the **Port & Adapters** pattern:

- The **port** (interface) defines what the domain needs — "give me geographic jurisdiction for this ID"
- The **adapter** (implementation) knows how to fulfill that need from the Geography context
- The domain layer stays pure — it depends on an interface, not on Eloquent or Geography classes

This means the interface uses terminology from the **client's** perspective (Membership), not the **server's** (Geography). The adapter translates between the two worlds.

### Why the DTO Is `readonly`

PHP 8.1+ `readonly` classes provide:

1. **Immutability guarantee** — once constructed, the DTO cannot change. This prevents accidental mutation in business logic.
2. **No behavior** — pure data carrier, enforcing the boundary-only intent.
3. **Abstract equality** — readonly objects with the same property values are equal by default, simplifying test assertions.

```php
final readonly class GeographicJurisdiction
{
    public function __construct(
        public int $geoUnitId,
        public int $adminLevel,
        public string $regionCode,
        public string $countryCode,
    ) {}
}
```

### Why the Adapter Injects an Eloquent Model (Not a Repository)

The adapter is infrastructure — it's allowed to use Eloquent directly. Injecting the `GeoAdministrativeUnit` model rather than a Geography repository:

- Avoids requiring the Geography context to expose a repository for a single query
- Keeps the adapter self-contained
- Follows the "Read Model" pattern from the architecture guidelines (Eloquent for reads is acceptable)

```php
final class GeographicJurisdictionProviderAdapter implements GeographicJurisdictionProvider
{
    public function __construct(
        private readonly GeoAdministrativeUnit $model,
    ) {}
```

## Files

### 1. Domain Port — `GeographicJurisdictionProvider` Interface

```php
<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Ports;

interface GeographicJurisdictionProvider
{
    public function resolve(int $geoUnitId): ?GeographicJurisdiction;
}
```

- Pure interface — no framework imports, no Laravel dependencies
- Single method: `resolve(int $geoUnitId)` returns a nullable DTO
- Returns null when the geo unit is not found (caller decides how to handle)

### 2. Domain DTO — `GeographicJurisdiction`

```php
<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Ports;

final readonly class GeographicJurisdiction
{
    public function __construct(
        public int $geoUnitId,
        public int $adminLevel,
        public string $regionCode,
        public string $countryCode,
    ) {}
}
```

- `readonly` — immutable data carrier
- All properties are public (DTOs have no behavior, no encapsulation is needed)
- Raw scalar types only — no value objects, no domain entities
- `regionCode` is empty string by convention when not set (handled by adapter)

### 3. Infrastructure Adapter — `GeographicJurisdictionProviderAdapter`

```php
<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Services;

use App\Contexts\Geography\Domain\Models\GeoAdministrativeUnit;
use App\Contexts\Membership\Domain\Committee\Ports\GeographicJurisdiction;
use App\Contexts\Membership\Domain\Committee\Ports\GeographicJurisdictionProvider;

final class GeographicJurisdictionProviderAdapter implements GeographicJurisdictionProvider
{
    public function __construct(
        private readonly GeoAdministrativeUnit $model,
    ) {}

    public function resolve(int $geoUnitId): ?GeographicJurisdiction
    {
        $unit = $this->model->find($geoUnitId);

        if ($unit === null) {
            return null;
        }

        return new GeographicJurisdiction(
            geoUnitId: (int) $unit->id,
            adminLevel: (int) $unit->admin_level,
            regionCode: (string) ($unit->region_code ?? ''),
            countryCode: (string) $unit->country_code,
        );
    }
}
```

- Infrastructure layer — crosses the Geography bounded context boundary
- Explicit casting ensures type safety (Eloquent returns mixed types)
- Null coalescing on `region_code` handles nullable columns gracefully
- Returns null for not-found, matching the interface contract

### 4. DI Binding in `MembershipServiceProvider`

```php
$this->app->bind(
    GeographicJurisdictionProvider::class,
    GeographicJurisdictionProviderAdapter::class
);
```

### 5. Tests

#### DTO Tests (`tests/Unit/Domain/Committee/Ports/GeographicJurisdictionTest.php`)

Four tests verifying:

1. **Construction** — DTO stores all four properties correctly
2. **Readonly property existence** — properties are publicly readable
3. **Equality** — two DTOs with same values are equal
4. **Inequality** — two DTOs with different values are not equal

#### Adapter Tests (`tests/Unit/Infrastructure/Services/GeographicJurisdictionProviderAdapterTest.php`)

Two tests verifying:

1. **Valid ID** — returns `GeographicJurisdiction` with correct values
2. **Invalid ID** — returns null

Uses Mockery to mock `GeoAdministrativeUnit::find()` to avoid database dependency:

```php
$model = Mockery::mock(GeoAdministrativeUnit::class);
$model->shouldReceive('find')
    ->with(42)
    ->andReturn((object) [
        'id' => 42,
        'admin_level' => 2,
        'region_code' => 'asia',
        'country_code' => 'NP',
    ]);
```

## Lessons Learned

### 1. Don't Mix Boundary Work with Semantic Logic

The initial plan for 8C.2B incorrectly included `CommitteeStructure::getLevelIndexByAdminLevel()`, `categoryFromAdminLevel()`, and `CommitteePolicyResolver` changes. These are semantic inference tasks that belong in 8C.2C.

**Warning signs that a plan is overreaching:**
- The plan introduces "mapping" or "transformation" logic
- The plan changes how business decisions are made (committee creation, categorization)
- The plan modifies domain services or resolvers
- The plan can't be described as "pure infrastructure boundary"

**The litmus test:** If removing the deliverable would break committee creation behavior, it belongs in 8C.2C, not 8C.2B.

### 2. Two Repositories Exist for Committees

During Phase 8C.2A, `geo_unit_id` was persisted as null despite appearing correct in test data. Root cause: `EloquentCommitteeRepository` (secondary read path) was edited but `EloquentCommitteeAggregateRepository` (actual persist path) was not.

**Always check which repository implements the aggregate repository interface when adding new fields:**

```
CommitteeRepositoryInterface → EloquentCommitteeRepository (read path)
CommitteeAggregateRepositoryInterface → EloquentCommitteeAggregateRepository (write/persist path)
```

Both must be updated for new fields to survive a write-read cycle.

### 3. Model Fillable Blocks Mass Assignment

When adding a new column, ensure it's in the Eloquent model's `$fillable` array. Eloquent's mass-assignment protection silently drops unlisted fields — no error, no warning.

## Verification

```bash
# DTO tests
php artisan test tests/Unit/Domain/Committee/Ports/GeographicJurisdictionTest.php

# Adapter tests
php artisan test tests/Unit/Infrastructure/Services/GeographicJurisdictionProviderAdapterTest.php

# Regression — all must pass unchanged
php artisan test tests/Feature/Committee/
```

## Next Phase (8C.2C)

Phase 8C.2C will consume the provider defined here:

1. **Inject** `GeographicJurisdictionProvider` into `CommitteePolicyResolver`
2. **Implement** `getLevelIndexByAdminLevel()` mapping on `CommitteeStructure`
3. **Derive** `categoryFromAdminLevel()` from admin level to committee category
4. **Wire** resolver changes into `CommitteeCreationPolicy`
5. **Handle** the invariant: if `geo_unit_id` is provided but provider returns null, throw (no silent fallback)

But that is explicitly deferred. For now, the boundary exists. It is not consumed. Committee creation works exactly as before.
