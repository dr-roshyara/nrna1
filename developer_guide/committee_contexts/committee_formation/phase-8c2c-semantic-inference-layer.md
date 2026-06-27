# Phase 8C.2C — Semantic Inference Layer (Identity → Projection → Classification)

## Why a Third Phase?

Phase 8C.2A added the `geo_unit_id` foreign key column. Phase 8C.2B defined the anti-corruption layer boundary (port + adapter). **But neither phase gave meaning to the ID.**

Raw `geo_unit_id` tells you *where* a committee is anchored. It doesn't tell you:

- What *kind* of committee it is (central, geographic, wing)
- What *semantic region* it belongs to (asia, europe)
- What *admin level* it operates at (continent, country, province, district, ward)

Phase 8C.2C bridges that gap with a **strictly layered three-axis model**:

> **Identity ≠ Meaning ≠ Classification**

| Layer | Responsibility | Type |
|-------|---------------|------|
| **Identity** | What is the entity — pure reference | `CommitteeGeoIdentity` |
| **Semantic Projection** | What does the identity mean in geo context | `GeoSemanticProjection` |
| **Classification** | What does it mean in governance context | `CommitteeClassificationPolicy` |

```
geoUnitId ──→ CommitteeGeoIdentity (identity carrier)
                  │
                  ▼
     GeographicJurisdictionProvider (port/adapter)
                  │
                  ▼
     GeoSemanticProjectionBuilder ──→ GeoSemanticProjection (enriched read model)
                  │
                  ▼
     CommitteeClassificationPolicy ──→ CommitteeType (governance decision)
```

---

## 1. Identity Layer — `CommitteeGeoIdentity`

**File:** `app/Contexts/Membership/Domain/Committee/ValueObjects/CommitteeGeoIdentity.php`

Pure geographic reference — `geoUnitId` only. No admin level, no region code, no country code, no classification logic.

```php
final readonly class CommitteeGeoIdentity
{
    public function __construct(
        public int $geoUnitId,
    ) {
        if ($geoUnitId < 1) {
            throw new \DomainException('geoUnitId must be a positive integer');
        }
    }

    public function equals(self $other): bool
    {
        return $this->geoUnitId === $other->geoUnitId;
    }
}
```

### Rules

- **I-01**: `geoUnitId` must be positive (validated in constructor)
- **I-02**: Immutable — `final readonly` prevents modification after construction
- **I-03**: Not a semantic object — no `adminLevel`, `regionCode`, or `countryCode` fields

### What NOT to Put Here

| ❌ Don't | Why |
|----------|-----|
| `adminLevel` | That's a projection concern — the same geo unit might be interpreted differently by different contexts |
| `regionCode`, `countryCode` | Derived from the geo hierarchy, not intrinsic to the identity |
| `resolveType(): CommitteeType` | Classification is a separate lifecycle. Mixing it here violates I-03 |

### Tests

Tests at `tests/Unit/Domain/Committee/ValueObjects/CommitteeGeoIdentityTest.php`:
- Valid construction (geoUnitId > 0)
- Negative ID throws `DomainException`
- Zero ID throws `DomainException`
- Two identical identities are equal
- Two different identities are not equal

---

## 2. Identity Factory — `CommitteeGeoIdentityFactory`

**File:** `app/Contexts/Membership/Domain/Committee/Factories/CommitteeGeoIdentityFactory.php`

Pure identity construction. No provider, no projection, no external dependency.

```php
final readonly class CommitteeGeoIdentityFactory
{
    public function create(int $geoUnitId): CommitteeGeoIdentity
    {
        return new CommitteeGeoIdentity($geoUnitId);
    }
}
```

### No Dual Responsibility

This factory does ONE thing: construct a `CommitteeGeoIdentity` from a `geoUnitId`. It does NOT build projections, resolve provider data, or perform classification.

**Why it's separate from `GeoSemanticProjectionBuilder`:**

| Concern | `CommitteeGeoIdentityFactory` | `GeoSemanticProjectionBuilder` |
|---------|------------------------------|--------------------------------|
| Dependency | None (pure constructor) | `GeographicJurisdictionProvider` |
| Failure mode | Never fails (validated in VO) | Returns null if provider can't resolve |
| Lifecycle | Always available | Depends on external geo data |
| Testability | Instantiate and call | Requires provider mock |

Mixing these would create a "god service for geography" — a class that both creates identities and resolves projections, coupling two independent lifecycles.

### Tests

Tests at `tests/Unit/Domain/Committee/Factories/CommitteeGeoIdentityFactoryTest.php`:
- `test_create_with_valid_id_returns_identity` — asserts instance type and property value
- `test_create_with_invalid_id_forwards_exception` — asserts `DomainException` from VO validation

---

## 3. Semantic Projection — `GeoSemanticProjection`

**File:** `app/Contexts/Membership/Domain/Committee/Projections/GeoSemanticProjection.php`

Enriched read model providing geographic context for classification. NOT identity — identity is `CommitteeGeoIdentity` (geoUnitId only). NOT classification — this projection is consumed BY classification.

```php
final readonly class GeoSemanticProjection
{
    public function __construct(
        public int $geoUnitId,
        public int $adminLevel,
        public string $regionCode,
        public string $countryCode,
    ) {
        if ($geoUnitId < 1) {
            throw new \DomainException('geoUnitId must be a positive integer');
        }
        if ($adminLevel < 0 || $adminLevel > 10) {
            throw new \DomainException('adminLevel must be between 0 and 10');
        }
    }
}
```

### Design Rationale

- **Immutability**: `final readonly` guarantees the projection is a stable snapshot
- **Validation boundaries**: `geoUnitId` (> 0) and `adminLevel` (0–10) validated at construction
- **Explicit boundary**: This class is the explicit boundary between geo interpretation and governance decisions
- **Cachable**: The projection can be cached, versioned, or refreshed independently of identity and classification layers

### The Projection Is NOT Persisted

`GeoSemanticProjection` is a **transient read model** — it's computed fresh each time from the provider. The only persistent geographic reference is `geo_unit_id` in the `committees` table. This prevents projection schema from leaking into the write model.

### Tests

Tests at `tests/Unit/Domain/Committee/Projections/GeoSemanticProjectionTest.php`:
- Construction with all fields
- Invalid geoUnitId throws
- Invalid adminLevel throws
- Value equality (same values → `equals()` returns true)
- Value inequality (different values → `equals()` returns false)
- Empty region code accepted
- Admin level 0 is valid (continent level)
- Admin level 10 is valid (max)

---

## 4. Projection Builder — `GeoSemanticProjectionBuilder`

**File:** `app/Contexts/Membership/Domain/Committee/Services/GeoSemanticProjectionBuilder.php`

Builds semantic projection from provider data. This is the ONLY service that bridges the ACL to the projection layer.

```php
final class GeoSemanticProjectionBuilder
{
    public function __construct(
        private readonly GeographicJurisdictionProvider $provider,
    ) {}

    public function build(int $geoUnitId): ?GeoSemanticProjection
    {
        $jurisdiction = $this->provider->resolve($geoUnitId);

        if ($jurisdiction === null) {
            return null;
        }

        return new GeoSemanticProjection(
            geoUnitId: $jurisdiction->geoUnitId,
            adminLevel: $jurisdiction->adminLevel,
            regionCode: $jurisdiction->regionCode,
            countryCode: $jurisdiction->countryCode,
        );
    }
}
```

### Why a Builder (not part of the factory)

- The factory creates identity (no dependencies, no I/O)
- The builder creates projections (depends on provider, may return null)
- Different failure modes, different lifecycles, different test strategies

### Flow

```
geoUnitId → GeographicJurisdictionProvider::resolve()
              ↓
           GeographicJurisdiction DTO (from adapter)
              ↓
           GeoSemanticProjection (domain read model)
```

### Failure Handling

| Scenario | Result |
|----------|--------|
| Provider resolves successfully | `GeoSemanticProjection` |
| Provider returns null (geo unit not found) | `null` |
| Provider throws | Exception propagates (caller decides) |

### Tests

Tests at `tests/Unit/Domain/Committee/Services/GeoSemanticProjectionBuilderTest.php` (4 tests):
- `test_build_with_valid_id_returns_projection` — mock provider returns jurisdiction, assert all fields mapped
- `test_build_with_unresolvable_id_returns_null` — mock returns null, assert null
- `test_build_delegates_to_provider` — verify provider::resolve() is called exactly once
- `test_build_maps_all_fields_correctly` — assert `equals()` between expected and actual projections

Uses `createMock(GeographicJurisdictionProvider::class)` to avoid database dependency.

---

## 5. Classification Policy — `CommitteeClassificationPolicy`

**File:** `app/Contexts/Membership/Domain/Committee/Policies/CommitteeClassificationPolicy.php`

Governance-owned classification. Takes a `GeoSemanticProjection` (NOT raw geoUnitId) and optional wing category → `CommitteeType`.

```php
final readonly class CommitteeClassificationPolicy
{
    public function resolve(
        GeoSemanticProjection $projection,
        ?CommitteeCategory $wing = null,
    ): CommitteeType {
        // Wing types bypass geographic classification
        if ($wing !== null && $wing->isWingType()) {
            return match ($wing) {
                CommitteeCategory::YOUTH => CommitteeType::youthWing(),
                CommitteeCategory::WOMEN => CommitteeType::womenWing(),
                CommitteeCategory::STUDENT => CommitteeType::studentWing(),
            };
        }

        // Geographic classification by admin level
        return match (true) {
            $projection->adminLevel <= 1 => CommitteeType::central(),
            $projection->adminLevel === 2,
            $projection->adminLevel === 3,
            $projection->adminLevel >= 4 => CommitteeType::geographic(),
        };
    }

    public function isGeographicEligible(GeoSemanticProjection $projection): bool
    {
        return $projection->adminLevel >= 2;
    }
}
```

### Admin Level Convention (Membership Interpretation)

| Admin Level | Geographic Meaning | Committee Type | Geographic Eligible |
|-------------|-------------------|----------------|--------------------|
| 0 | Continent | CENTRAL | No |
| 1 | Country | CENTRAL | No |
| 2 | Province/State | GEOGRAPHIC | Yes |
| 3 | District | GEOGRAPHIC | Yes |
| 4+ | Local/Ward | GEOGRAPHIC | Yes |

**Important:** This convention is owned by the Membership context. The Geography context defines its own admin level scale. The `GeographicJurisdictionProviderAdapter` is the translation layer.

### Invariants

- **I-06**: Same projection → same `CommitteeType` (deterministic — no random, no date-dependent logic)
- **I-07**: `geoUnitId` is the only persistent geo reference (projections are computed, not stored)

### What This Policy Does NOT Do

| ❌ Forbidden | Why |
|-------------|-----|
| Access `GeographicJurisdictionProvider` | Would bind classification to I/O — violates testability |
| Import `CommitteeGeoIdentity` | Identity and classification are separate layers |
| Construct identities | Classification consumes projections, not identities |
| Access `CommitteeStructure` | Structure is a different domain concept |
| Import Geography context classes | Would violate bounded context boundaries |

### Tests

Tests at `tests/Unit/Domain/Committee/Policies/CommitteeClassificationPolicyTest.php` (14 tests):

| Test | Input | Expected |
|------|-------|----------|
| Admin level 0 | `projection(level=0)` | `central()` |
| Admin level 1 | `projection(level=1)` | `central()` |
| Admin level 2 | `projection(level=2)` | `geographic()` |
| Admin level 3 | `projection(level=3)` | `geographic()` |
| Admin level 4 | `projection(level=4)` | `geographic()` |
| Admin level 5 | `projection(level=5)` | `geographic()` |
| Youth wing | `projection(level=0)` + `YOUTH` | `youthWing()` |
| Women wing | `projection(level=0)` + `WOMEN` | `womenWing()` |
| Student wing | `projection(level=0)` + `STUDENT` | `studentWing()` |
| Geographic eligible level 0 | `projection(level=0)` | `false` |
| Geographic eligible level 1 | `projection(level=1)` | `false` |
| Geographic eligible level 2 | `projection(level=2)` | `true` |
| Geographic eligible level 5 | `projection(level=5)` | `true` |
| Determinism | `projection(level=2)` x2 | Same type both calls |

---

## 6. Aggregate Integration

### 6a. `Committee.php`

The aggregate stores the identity as an optional field. It's derived from `geoUnitId` during creation and reconstruction.

**New field:**
```php
private ?CommitteeGeoIdentity $geoIdentity = null;
```

**In `create()`:**
```php
$committee->geoIdentity = $geoIdentity
    ?? ($geoUnitId !== null ? new CommitteeGeoIdentity($geoUnitId) : null);
```

**In `reconstruct()`:**
```php
$committee->geoIdentity = $geoIdentity
    ?? ($geoUnitId !== null ? new CommitteeGeoIdentity($geoUnitId) : null);
```

**Getter:**
```php
public function getGeoIdentity(): ?CommitteeGeoIdentity
{
    return $this->geoIdentity;
}
```

### 6b. `InternalCreateCommittee.php`

Three new dependencies injected:
- `CommitteeGeoIdentityFactory` — constructs identity from geoUnitId
- `GeoSemanticProjectionBuilder` — builds projection from provider
- `CommitteeClassificationPolicy` — classifies projection into CommitteeType

In `execute()`, identity is assembled after geo reference parsing:
```php
$geoIdentity = $command->geoUnitId !== null
    ? $this->geoIdentityFactory->create($command->geoUnitId)
    : null;
```

The identity is passed to `Committee::create()`:
```php
geoIdentity: $geoIdentity,
```

### 6c. `TransactionalCreateCommittee.php`

No changes needed — it's a transparent decorator that forwards all calls to `InternalCreateCommittee`. The decorator pattern ensures the new dependencies are wired through automatically.

### 6d. `EloquentCommitteeAggregateRepository.php`

In `mapToDomain()`, the identity is reconstructed from the persisted `geo_unit_id`:
```php
geoIdentity: $model->geo_unit_id !== null
    ? new CommitteeGeoIdentity((int) $model->geo_unit_id)
    : null,
```

No migration needed — `geo_unit_id` was already added in Phase 8C.2A. The identity VO is purely an in-memory construct.

---

## 7. DI Bindings

In `MembershipServiceProvider.php`:

```php
// GeoSemanticProjectionBuilder needs the provider
$this->app->bind(GeoSemanticProjectionBuilder::class, function ($app) {
    return new GeoSemanticProjectionBuilder(
        $app->make(GeographicJurisdictionProvider::class)
    );
});

// Factory and policy are stateless singletons
$this->app->singleton(CommitteeGeoIdentityFactory::class);
$this->app->singleton(CommitteeClassificationPolicy::class);
```

The `CreateCommitteeUseCase` binding is updated to pass the new dependencies:
```php
$this->app->bind(CreateCommitteeUseCase::class, function ($app) {
    $internal = new InternalCreateCommittee(
        $app->make(CommitteeStructureRepositoryInterface::class),
        $app->make(CommitteeAggregateRepositoryInterface::class),
        $app->make(CommitteeCreationPolicy::class),
        $app->make(GovernanceAccessPolicyInterface::class),
        $app->make(CommitteePolicyResolver::class),
        $app->make(CommitteeGeoIdentityFactory::class),
        $app->make(GeoSemanticProjectionBuilder::class),
        $app->make(CommitteeClassificationPolicy::class),
    );

    return new TransactionalCreateCommittee($internal);
});
```

---

## 8. Architectural Invariants

### Strict Dependency Rules

| Rule | Description | Violation Example |
|------|-------------|-------------------|
| F-01 | No service locator in application/domain | `app(GeoSemanticProjectionBuilder::class)` |
| F-02 | No classification inside identity VO | `CommitteeGeoIdentity::resolveType()` |
| F-03 | No adminLevel inside identity VO | `CommitteeGeoIdentity::$adminLevel` |
| F-04 | No projection dependency on identity | `GeoSemanticProjection` importing `CommitteeGeoIdentity` |
| F-05 | No geo interpretation inside classification policy | Policy accessing `GeographicJurisdictionProvider` |
| F-06 | No frontend-driven classification | Type string built in Vue |
| F-07 | `CommitteePolicyResolver` NOT modified in this phase | No changes to resolver class |
| F-08 | No dual-responsibility assembler — identity and projection have different lifecycles | Single service doing both `createIdentity()` and `buildProjection()` |

### Why Identity and Projection Are Separate Lifecycles

The critical architectural insight that drove the split between `CommitteeGeoIdentityFactory` and `GeoSemanticProjectionBuilder`:

1. **Hidden coupling**: A combined assembler makes identity construction depend on the projection pipeline existing
2. **Blurred boundaries**: An assembler becomes a "god service for geography" — doing both identity creation and semantic resolution
3. **Test confusion**: Identity construction (pure, always works) and projection building (depends on provider, may return null) have fundamentally different test strategies
4. **Different failure modes**: Identity construction never fails for valid IDs; projection building can return null when the geo database is incomplete

---

## 9. Complete Layer Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                      COMMITTEE CREATION FLOW                          │
│                                                                       │
│  Controller                                                           │
│     │                                                                 │
│     ▼                                                                 │
│  InternalCreateCommitteeCommand (typed DTO with geoUnitId)            │
│     │                                                                 │
│     ▼                                                                 │
│  InternalCreateCommittee.execute()                                    │
│     │                                                                 │
│     ├─── CommitteeGeoIdentityFactory::create(geoUnitId)               │
│     │       │                                                         │
│     │       ▼                                                         │
│     │   CommitteeGeoIdentity (geoUnitId only)                         │
│     │       │                                                         │
│     ├─── GeoSemanticProjectionBuilder::build(geoUnitId)               │
│     │       │                                                         │
│     │       ├─── GeographicJurisdictionProvider::resolve(geoUnitId)   │
│     │       │       │                                                 │
│     │       │       ▼                                                 │
│     │       │   GeographicJurisdiction (DTO from adapter)             │
│     │       │       │                                                 │
│     │       │       ▼                                                 │
│     │       │   GeoSemanticProjection (domain read model)             │
│     │       │                                                         │
│     ├─── CommitteeClassificationPolicy::resolve(projection, wing)     │
│     │       │                                                         │
│     │       ▼                                                         │
│     │   CommitteeType (governance decision)                           │
│     │                                                                 │
│     └─── Committee::create(..., geoIdentity, geoUnitId)               │
│             │                                                         │
│             ▼                                                         │
│         CommitteeRepository::persist()                                │
│             │                                                         │
│             ▼                                                         │
│         Database (geo_unit_id persisted; identity reconstructed)      │
└─────────────────────────────────────────────────────────────────────┘

                    PERSISTENCE BOUNDARY
┌──────────────────────────────────────────────────────────────────────┐
│                      REPOSITORY (Read Path)                           │
│                                                                       │
│  CommitteeModel::find(id)                                             │
│     │                                                                 │
│     ▼                                                                 │
│  mapToDomain()                                                        │
│     │                                                                 │
│     └─── geo_unit_id !== null ? new CommitteeGeoIdentity(...) : null  │
│                                                                       │
│     ▼                                                                 │
│  Committee aggregate (reconstructed with geoIdentity)                 │
└──────────────────────────────────────────────────────────────────────┘
```

---

## 10. File Map

| File | Layer | Purpose |
|------|-------|---------|
| `Domain/Committee/ValueObjects/CommitteeGeoIdentity.php` | Domain | Identity VO — geoUnitId only |
| `Domain/Committee/Projections/GeoSemanticProjection.php` | Domain | Enriched read model |
| `Domain/Committee/Factories/CommitteeGeoIdentityFactory.php` | Domain | Pure identity construction |
| `Domain/Committee/Services/GeoSemanticProjectionBuilder.php` | Domain | Projection from provider |
| `Domain/Committee/Policies/CommitteeClassificationPolicy.php` | Domain | Governance classification |
| `Domain/Committee/Committee.php` | Domain | Identity field + getter added |
| `Application/Committee/InternalCreateCommittee.php` | Application | Injects and wires new deps |
| `Infrastructure/Providers/MembershipServiceProvider.php` | Infrastructure | DI bindings |
| `Infrastructure/Repositories/EloquentCommitteeAggregateRepository.php` | Infrastructure | Identity reconstruction |

### Test Files

| Test File | Tests |
|-----------|-------|
| `tests/Unit/Domain/Committee/ValueObjects/CommitteeGeoIdentityTest.php` | 5 tests |
| `tests/Unit/Domain/Committee/Projections/GeoSemanticProjectionTest.php` | 8 tests |
| `tests/Unit/Domain/Committee/Factories/CommitteeGeoIdentityFactoryTest.php` | 2 tests |
| `tests/Unit/Domain/Committee/Services/GeoSemanticProjectionBuilderTest.php` | 4 tests |
| `tests/Unit/Domain/Committee/Policies/CommitteeClassificationPolicyTest.php` | 14 tests |

**Total: 33 new tests, all passing.**

---

## 11. Verification

```bash
# All new unit tests
php artisan test tests/Unit/Domain/Committee/ValueObjects/CommitteeGeoIdentityTest.php
php artisan test tests/Unit/Domain/Committee/Projections/GeoSemanticProjectionTest.php
php artisan test tests/Unit/Domain/Committee/Factories/CommitteeGeoIdentityFactoryTest.php
php artisan test tests/Unit/Domain/Committee/Services/GeoSemanticProjectionBuilderTest.php
php artisan test tests/Unit/Domain/Committee/Policies/CommitteeClassificationPolicyTest.php

# Regression — existing Committee tests
php artisan test tests/Unit/Contexts/Membership/Domain/Committee/

# Feature tests
php artisan test tests/Feature/Committee/
```

---

## 12. Next Phase (8C.2D)

Phase 8C.2D will focus on frontend changes:

1. **Tree selector** — replace the type dropdown with a geographic tree selector
2. **Automatic type derivation** — derive committee type from selected geographic unit
3. **Frontend simplification** — remove type selection from committee creation form
4. **Backend wiring** — `CommitteePolicyResolver` consuming `CommitteeClassificationPolicy`

The semantic inference layer built here (identity → projection → classification) is the foundation that makes those frontend changes possible without coupling the UI to geographic domain logic.

---

## 13. Lessons Learned

### 1. Boundary-First Pays Off

Defining the `GeographicJurisdictionProvider` port in 8C.2B before writing any semantic logic in 8C.2C prevented the domain layer from leaking Geography context dependencies. The builder and policy both depend on the port interface, never on concrete implementations.

### 2. Three Layers Instead of One

The initial design tried to collapse identity, projection, and classification into a single service. Three separate iterations (v1→v2→v3) revealed that each layer has:
- Different dependencies (none vs provider vs nothing)
- Different failure modes (validated construction vs nullable return vs pure computation)
- Different test strategies (assert instance vs mock provider vs assert type)
- Different lifecycle considerations (always available vs geo data dependent vs stateless)

### 3. The Assembler Trap

A "CommitteeGeoIdentityAssembler" that handled both identity creation and projection building was proposed and rejected. The user identified: "Identity creation and semantic projection are different lifecycles and must never share a service boundary." This led to the cleaner Factory + Builder split.

### 4. Existing Tests as Safety Net

The 124 existing tests (108 unit + 16 feature) provided a zero-regression guarantee. After adding `geoIdentity` to `Committee::create()` and `reconstruct()`, running the full suite confirmed no existing code paths were broken — the new parameter defaults to `null`.

### 5. `geoUnitId` → Identity Derivation

In `Committee::create()` and `reconstruct()`, the identity is derived from `geoUnitId` when not explicitly provided:
```php
$committee->geoIdentity = $geoIdentity
    ?? ($geoUnitId !== null ? new CommitteeGeoIdentity($geoUnitId) : null);
```

This means:
- New code paths (InternalCreateCommittee) pass identity explicitly via the factory
- Existing code paths (tests, other callers) that pass `geoUnitId` get identity derived automatically
- Both paths converge on the same result
