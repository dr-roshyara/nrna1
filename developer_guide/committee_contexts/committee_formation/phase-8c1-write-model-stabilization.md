# Phase 8C.1 — Membership Write Model Stabilization

## What Changed

### Critical Bug Fixed

`Committee::create()` **hardcoded `CommitteeType::central()`**. Every committee — regardless of the type selected in the UI — was stored as `"central"` in the database. Province, district, and ward committees lost their semantic identity.

### Root Cause

The old `create()` signature accepted individual type/structure/level parameters but ignored them:

```php
// OLD — type was never wired from parameters
public static function create(
    CommitteeId $id,
    TenantId $tenantId,
    CommitteeStructureId $structureId,
    ...
): self {
    $committee->type = CommitteeType::central(); // HARDCODED
    $committee->structure = new CentralCommitteeStructure();
}
```

### Solution

Introduce `CommitteePolicy` — a single value object bundling `CommitteeType`, `CommitteeStructure` (strategy), and `CommitteeLevel`. This becomes the single authority for governance classification at creation time.

---

## New Types

### `CommitteeCategory` (backed enum)

File: `Domain/Committee/ValueObjects/CommitteeCategory.php`

```php
enum CommitteeCategory: string
{
    case CENTRAL = 'central';
    case PROVINCE = 'province';
    case DISTRICT = 'district';
    case WARD = 'ward';
    case YOUTH = 'youth';
    case WOMEN = 'women';
    case STUDENT = 'student';
}
```

Helpers: `isWingType()`, `isGeographicType()`, `isCentral()`.

### `CommitteePolicy` (readonly value object)

File: `Domain/Committee/ValueObjects/CommitteePolicy.php`

```php
final readonly class CommitteePolicy
{
    public function __construct(
        public CommitteeType $type,
        public CommitteeStructure $structure,
        public CommitteeLevel $level,
    ) {}
}
```

### `CommitteePolicyResolver`

File: `Domain/Committee/Services/CommitteePolicyResolver.php`

Single entry point for resolving a `CommitteeCategory` into a complete `CommitteePolicy`. Works against an active `CommitteeStructure`.

```php
public function resolve(
    CommitteeStructure $structure,
    InternalCreateCommitteeCommand $command
): CommitteePolicy;
```

Resolution flow:
1. Map `CommitteeCategory` → `CommitteeType` (central, geographic, youth_wing, etc.)
2. Map `CommitteeCategory` → level index via `$structure->getLevelIndexForCategory()`
3. Fetch `CommitteeLevel` from structure via `$structure->getLevel(index)`
4. Instantiate correct `CommitteeStructure` strategy via `CommitteeStructureRegistry::forType()`

### `CommitteeStructure::getLevelIndexForCategory()`

File: `Domain/Committee/CommitteeStructure.php`

Category→level topology is owned by the structure aggregate, not the resolver. This prevents hardcoded assumptions like "province => level 2" from leaking into policy resolution.

| Category | Level Index |
|---|---|
| CENTRAL, YOUTH, WOMEN, STUDENT | 1 |
| PROVINCE | 2 |
| DISTRICT | 3 |
| WARD | 4 |

---

## Architecture Rules

### 1. Aggregate Purity

`Committee` must NEVER resolve repositories or services from the container. It stores IDs only. All resolution happens **before** `Committee::create()` is called, inside the `InternalCreateCommittee` use case.

### 2. No Geography Coupling

Membership domain must not import `GeoAdministrativeUnit` or any Geography entity. Geography integration is deferred to Phase 8C.2 (via `GeographicJurisdictionProvider` anti-corruption layer).

### 3. Category Enum, Not String

`CommitteeCategory` backed enum replaces raw `string $committeeCategory` — eliminates primitive obsession, typo risk, and implicit semantics.

### 4. Level Mapping on Structure, Not Resolver

`CommitteeStructure::getLevelIndexForCategory()` owns the category→level topology. The resolver delegates rather than hardcoding `province => 2`.

### 5. Single Decision Point

All type/structure inference lives in `CommitteePolicyResolver`. No scattered logic.

---

## How the Creation Flow Works

```
Controller (validates request)
    → InternalCreateCommitteeCommand DTO (typed, with CommitteeCategory enum)
    → InternalCreateCommittee use case
        → GovernanceAccessPolicyInterface::assertCanCreateCommittee()  [permissive seam]
        → CommitteeStructureRepository::findActiveByTenantForUpdate()  [pessimistic lock]
        → CommitteePolicyResolver::resolve(structure, command)         [single decision point]
        → GeoReference::fromString()                                   [parse geo ref]
        → CommitteeCreationPolicy::assertCanCreate()                    [validate structure+level+geo]
        → Committee::create(id, tenantId, policy, structureId, ...)     [aggregate creation]
        → CommitteeRepository::persist(committee)                       [save]
    → Redirect to committee dashboard
```

---

## Key Files

| File | Responsibility |
|---|---|
| `Domain/Committee/ValueObjects/CommitteeCategory.php` | Backed enum for all committee categories |
| `Domain/Committee/ValueObjects/CommitteePolicy.php` | Policy VO bundling type + structure + level |
| `Domain/Committee/Services/CommitteePolicyResolver.php` | Resolves category → full policy |
| `Domain/Committee/CommitteeStructureRegistry.php` | Maps type → strategy implementation |
| `Domain/Committee/CommitteeStructure.php` | Aggregate: `getLevelIndexForCategory()`, `getLevel()` |
| `Domain/Committee/Committee.php` | Aggregate: `create()` now requires `CommitteePolicy` |
| `Application/Committee/DTOs/InternalCreateCommitteeCommand.php` | Typed DTO with `CommitteeCategory` |
| `Application/Committee/InternalCreateCommittee.php` | Use case orchestrating creation |
| `Infrastructure/Application/TransactionalCreateCommittee.php` | Decorator wrapping in DB transaction |
| `Http/Controllers/Committee/CommitteeManagementController.php` | Thin controller creating DTO from request |
| `Infrastructure/Providers/MembershipServiceProvider.php` | DI bindings for resolver, policy, etc. |

---

## Migration Guide

### If You Call `Committee::create()` Directly

**Before** (Phase 8B):
```php
Committee::create(
    id: CommitteeId::generate(),
    tenantId: $tenantId,
    structureId: $structureId,
    levelIndex: 1,
    levelName: 'Level 1',
    geoPolicy: GeoPolicy::NONE,
    geoScope: null,
    name: CommitteeName::fromString('Test'),
    code: 'TEST',
    operationalGeo: null,
);
```

**After** (Phase 8C.1):
```php
$policy = new CommitteePolicy(
    type: CommitteeType::central(),
    structure: new CentralCommitteeStructure(),
    level: CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null),
);

Committee::create(
    id: CommitteeId::generate(),
    tenantId: $tenantId,
    policy: $policy,                          // NEW — required 3rd argument
    structureId: $structureId,
    levelIndex: 1,
    levelName: 'Level 1',
    geoPolicy: GeoPolicy::NONE,
    geoScope: null,
    name: CommitteeName::fromString('Test'),
    code: 'TEST',
    operationalGeo: null,
);
```

### If You Use `InternalCreateCommitteeCommand`

**Before**:
```php
new InternalCreateCommitteeCommand(
    tenantId: ...,
    committeeCode: ...,
    committeeName: ...,
    committeeCategory: 'central',           // raw string
    geoReference: ...,
    regionCode: ...,
    countryCode: ...,
);
```

**After**:
```php
new InternalCreateCommitteeCommand(
    tenantId: ...,
    committeeCode: ...,
    committeeName: ...,
    committeeCategory: CommitteeCategory::CENTRAL,  // typed enum
    geoReference: ...,
    regionCode: ...,
    countryCode: ...,
);
```

### In Tests

Test helper pattern for creating `CommitteePolicy`:

```php
private function makeCentralPolicy(): CommitteePolicy
{
    return new CommitteePolicy(
        type: CommitteeType::central(),
        structure: new CentralCommitteeStructure(),
        level: CommitteeLevel::create(
            index: 1,
            code: null,
            name: 'Level 1',
            geoPolicy: GeoPolicy::NONE,
            geoScope: null,
            roleLimits: [],
            minMembershipYears: 0,
            ageRange: null,
            genderRequirement: null,
        ),
    );
}

private function makeGeographicPolicy(
    int $levelIndex,
    string $scope,
    string $levelName
): CommitteePolicy {
    return new CommitteePolicy(
        type: CommitteeType::geographic(),
        structure: new GeographicCommitteeStructure(),
        level: CommitteeLevel::create(
            index: $levelIndex,
            code: $scope,
            name: $levelName,
            geoPolicy: GeoPolicy::REQUIRED,
            geoScope: new GeoScope($scope),
            roleLimits: [],
            minMembershipYears: 0,
            ageRange: null,
            genderRequirement: null,
        ),
    );
}
```

---

## Common Pitfalls

1. **Missing `policy` argument**: `Committee::create()` now requires `CommitteePolicy` as the 3rd parameter (positional or named). Omitting it causes `ArgumentCountError`.

2. **Governance status `pending_setup`**: The `GovernanceAccessPolicyInterface` checks that the organisation has `governance_status = 'active'`. Factory-created organisations default to `null` (`pending_setup`). Always set `'governance_status' => 'active'` in feature tests.

3. **DB check constraints**: Two CHECK constraints exist on `committees` table:
   - `chk_central_committee_no_geography`: central type must have NULL `operational_geo_reference`
   - `chk_non_central_must_have_geography`: geographic types must have non-NULL `operational_geo_reference`

4. **`operational_geo_reference`, not `geo_reference`**: The Eloquent model column is `operational_geo_reference`. There is no accessor for `geo_reference`.

5. **CommitteeType::geographic()**: Province, district, and ward categories all map to `CommitteeType::geographic()` which has the string value `'geographic'` in the database — not `'province'`, `'district'`, or `'ward'`.

---

## What Phase 8C.2 Will Cover (Geography Integration)

- `geo_unit_id` FK + unique constraint migration
- `GeographicJurisdictionProvider` anti-corruption layer
- Replace category-based mapping with provider-based inference
- Frontend tree selector (replacing type dropdown)
- Canonical geo-reference format (`region:asia.country:IN.geo:3.7`)
