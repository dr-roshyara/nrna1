# Phase 8E — Canonical Geo Architecture: Geographic Eligibility for Member Assignment

## What Changed

Phase 8E introduces the **geographic eligibility validation pipeline** — the system that ensures a member can only be assigned to a committee whose operational geography covers the member's residence area.

Before Phase 8E, `assignMember()` did **no geographic validation**. A member from province 4 could be assigned to a provincial committee in province 3 with no warning. Phase 8E adds the full validation chain:

**Before (Phase 8C/8D):**
```
Controller → AssignMemberToCommitteeHandler
    → Committee::assignMember()    ← no geo check
    → repository->save()
```

**After (Phase 8E):**
```
Controller → AssignMemberToCommitteeHandler
    → validateGeoEligibility()
        → GeoSemanticProjectionBuilder::build(memberResidence)
        → GeoSemanticProjectionBuilder::build(committeeGeo)
        → GeoPathChainFactory::from(projection)
        → CommitteeEligibilityPolicy::isEligible(committee, member)
    → Committee::assignMember()
    → repository->save()
```

---

## Architecture

```
geo_administrative_units (DB, has materialized path)
    ↓
GeographicJurisdictionProvider (port/ACL interface)
    └─ resolve(geoUnitId) → GeographicJurisdiction (+path)
    ↓
GeoSemanticProjectionBuilder (single source of truth)
    └─ build(geoUnitId) → GeoSemanticProjection (+path)
    ↓
GeoPathChainFactory (pure factory)
    └─ from(projection) → GeoPathChain
    ↓
CommitteeEligibilityPolicy (pure policy)
    └─ isEligible(committee, member) → bool
        └─ str_starts_with(member.path, committee.path)
```

### Data Flow

```
User assigns member with geo
    ↓
AssignMemberToCommitteeHandler::handle(command)
    ├── 1. Load Member aggregate → get residence geo unit ID
    ├── 2. Load Committee aggregate → get operational geo unit ID
    ├── 3. Build projections for both
    │      └─ GeoSemanticProjectionBuilder::build(geoUnitId)
    │           └─ GeographicJurisdictionProvider::resolve(geoUnitId)
    │                └─ GeographicJurisdictionProviderAdapter
    │                     └─ GeoAdministrativeUnit::find(geoUnitId)
    ├── 4. Derive GeoPathChains
    │      └─ GeoPathChainFactory::from(projection)
    ├── 5. Evaluate eligibility
    │      └─ CommitteeEligibilityPolicy::isEligible(committeeChain, memberChain)
    │           └─ str_starts_with(member.path, committee.path)
    ├── 6. Committee::assignMember(member, role, ...)
    └── 7. repository->save(committee)
```

---

## New Types

### GeographicJurisdiction (DTO)

File: `Domain/Committee/Ports/GeographicJurisdiction.php`

```php
final readonly class GeographicJurisdiction
{
    public function __construct(
        public int $geoUnitId,
        public int $adminLevel,
        public string $regionCode,
        public string $countryCode,
        public string $path = '',
    ) {}
}
```

Data transfer object from the ACL layer. The `path` field is the materialized path from `geo_administrative_units` (e.g., `/1/23/456`).

### GeoSemanticProjection

File: `Domain/Committee/Projections/GeoSemanticProjection.php`

```php
final readonly class GeoSemanticProjection
{
    public function __construct(
        public int $geoUnitId,
        public int $adminLevel,
        public string $regionCode,
        public string $countryCode,
        public string $path = '',
    ) {}
}
```

Enriched read model. This is the **explicit boundary** between geo interpretation and governance decisions. Can be cached/versioned independently.

### GeoPathChain

File: `Domain/Committee/ValueObjects/GeoPathChain.php`

```php
final readonly class GeoPathChain
{
    public int $geoUnitId;
    public string $path;
    public array $segments;  // int[] ancestor IDs, root-first

    public function contains(int $geoUnitId): bool
    public function startsWith(string $pathPrefix): bool
}
```

Value object with two comparison methods: `contains()` checks hierarchy membership by ID, `startsWith()` does path-prefix matching. ZERO dependencies.

### MemberResidenceGeoIdentity

File: `Domain/Member/ValueObjects/MemberResidenceGeoIdentity.php`

```php
final readonly class MemberResidenceGeoIdentity
{
    public function __construct(
        public int $residenceGeoUnitId
    ) {}
}
```

Wraps the raw `geo_administrative_units.id` FK as a typed VO. Used by the Member aggregate and the eligibility pipeline.

---

## Pipeline Components (in order)

### 1. GeographicJurisdictionProvider (Port)

File: `Domain/Committee/Ports/GeographicJurisdictionProvider.php`

```php
interface GeographicJurisdictionProvider
{
    public function resolve(int $geoUnitId): ?GeographicJurisdiction;
}
```

Anti-corruption layer port. Implemented in infrastructure via `GeographicJurisdictionProviderAdapter` which queries `GeoAdministrativeUnit`.

### 2. GeoSemanticProjectionBuilder

File: `Domain/Committee/Services/GeoSemanticProjectionBuilder.php`

```php
final class GeoSemanticProjectionBuilder
{
    public function __construct(
        private readonly GeographicJurisdictionProvider $provider,
    ) {}

    public function build(int $geoUnitId): ?GeoSemanticProjection
    {
        $jurisdiction = $this->provider->resolve($geoUnitId);
        if ($jurisdiction === null) return null;

        return new GeoSemanticProjection(
            geoUnitId: $jurisdiction->geoUnitId,
            adminLevel: $jurisdiction->adminLevel,
            regionCode: $jurisdiction->regionCode,
            countryCode: $jurisdiction->countryCode,
            path: $jurisdiction->path,
        );
    }
}
```

**Single source of truth** for building projections. The ONLY service that bridges the ACL port to the semantic projection layer.

### 3. GeoPathChainFactory

File: `Domain/Committee/Factories/GeoPathChainFactory.php`

```php
final readonly class GeoPathChainFactory
{
    public static function from(GeoSemanticProjection $projection): GeoPathChain
    {
        $path = $projection->path;

        if ($path === '') {
            return new GeoPathChain(geoUnitId: $projection->geoUnitId, path: '', segments: []);
        }

        $segments = array_map(
            'intval',
            array_filter(explode('/', trim($path, '/')))
        );

        return new GeoPathChain(
            geoUnitId: $projection->geoUnitId,
            path: $path,
            segments: $segments,
        );
    }
}
```

Pure factory. Parses the materialized path string `/1/23/456` into `[1, 23, 456]`.

### 4. CommitteeEligibilityPolicy

File: `Domain/Committee/Policies/CommitteeEligibilityPolicy.php`

```php
final readonly class CommitteeEligibilityPolicy
{
    public function isEligible(GeoPathChain $committee, GeoPathChain $member): bool
    {
        // GEO-ELIG-2: Central committees (empty path) cover all members
        if ($committee->path === '') return true;

        // GEO-ELIG-3: Geographic committees require member to have geography
        if ($member->path === '') return false;

        // GEO-ELIG-1: Member is within committee if member's path starts with committee's path
        return $member->startsWith($committee->path);
    }
}
```

ZERO dependencies. Deterministic. The core business rule encoded in 3 lines.

### 5. GeographicEligibilityValidator

File: `Domain/Committee/Services/GeographicEligibilityValidator.php`

```php
final readonly class GeographicEligibilityValidator
{
    public function __construct(
        private readonly GeoSemanticProjectionBuilder $projectionBuilder,
        private readonly CommitteeEligibilityPolicy $policy,
    ) {}

    public function validate(int $memberResidenceGeoUnitId, int $committeeGeoUnitId): bool
    {
        $memberProjection = $this->projectionBuilder->build($memberResidenceGeoUnitId);
        $committeeProjection = $this->projectionBuilder->build($committeeGeoUnitId);

        if ($memberProjection === null || $committeeProjection === null) return false;

        $memberChain = GeoPathChainFactory::from($memberProjection);
        $committeeChain = GeoPathChainFactory::from($committeeProjection);

        return $this->policy->isEligible($committeeChain, $memberChain);
    }
}
```

Domain service orchestrating projection building → chain derivation → policy evaluation. Used by the application handler.

### 6. AssignMemberToCommitteeHandler (Updated)

File: `Application/Committee/Handlers/AssignMemberToCommitteeHandler.php`

```php
final readonly class AssignMemberToCommitteeHandler
{
    public function handle(AssignMemberWithGeoCommand $command): void
    {
        // 1. Load aggregates
        $member = $this->members->find($command->memberId, $memberTenantId);
        $committee = $this->committees->findForTenant($command->committeeId, $command->tenantId);

        // 2. Geo eligibility check via projection → chain → policy
        $geoValidated = $this->validateGeoEligibility($committee, $member);

        // 3. Delegate to aggregate for assignment
        $memberGeography = $geoValidated ? $committee->getOperationalGeo() : null;
        $committee->assignMember(
            memberId: $memberId,
            rolePath: $command->rolePath,
            nominationType: $command->nominationType,
            memberGeography: $memberGeography,
            electionDate: $command->electionDate,
            // ... other fields
        );

        // 4. Persist and dispatch events
        $this->committees->saveForTenant($committee);
        $this->eventBus->dispatchAll($committee->pullEvents());
    }

    private function validateGeoEligibility($committee, $member): bool
    {
        $committeeGeoUnitId = $committee->getGeoUnitId();
        $memberResidenceGeoUnitId = $member->getResidenceGeoIdentity()?->residenceGeoUnitId;

        // Central committees (no geoUnitId) cover all members
        if ($committeeGeoUnitId === null) return false;

        // Geographic committees require member residence
        if ($memberResidenceGeoUnitId === null) throw new DomainException(
            'Member without residence geography cannot be assigned to a geographic committee'
        );

        // Build projections → derive chains → evaluate policy
        $memberProjection = $this->projectionBuilder->build($memberResidenceGeoUnitId);
        $committeeProjection = $this->projectionBuilder->build($committeeGeoUnitId);

        $memberChain = GeoPathChainFactory::from($memberProjection);
        $committeeChain = GeoPathChainFactory::from($committeeProjection);

        if (!$this->policy->isEligible($committeeChain, $memberChain)) {
            throw new DomainException(
                'Member residence geography is not within the committee\'s operational geography'
            );
        }

        return true;
    }
}
```

Key design decisions:
- Returns `bool` (not void) — the `true` return signals to the caller that geo validation was performed
- Central committees skip the check entirely
- When validation passes, the committee's own operational GeoReference is passed as `memberGeography` to satisfy the aggregate's `coversGeography()` invariant (equal → true)

### 7. NearbyCommitteesQueryService

File: `Application/Committee/Services/NearbyCommitteesQueryService.php`

```php
final readonly class NearbyCommitteesQueryService
{
    public function getNearby(int $memberGeoUnitId): Collection
    {
        $memberProjection = $this->projectionBuilder->build($memberGeoUnitId);
        if ($memberProjection === null || $memberProjection->path === '') {
            return collect();
        }

        $memberPath = $memberProjection->path;

        return CommitteeModel::query()
            ->join('geo_administrative_units', 'committees.geo_unit_id', '=', 'geo_administrative_units.id')
            ->whereRaw('? LIKE CONCAT(geo_administrative_units.path, \'%\')', [$memberPath])
            ->select('committees.*')
            ->distinct()
            ->get();
    }
}
```

Read model using SQL `LIKE` with materialized path prefix matching. Finds all non-central committees whose operational geography covers the member's residence. No domain events, no side effects.

### 8. MemberGeographyController

File: `Http/Controllers/Committee/MemberGeographyController.php`

Three endpoints:
- `GET /members/{member}/geography` — Show member's residence geo
- `PUT /members/{member}/geography` — Update member's residence geo (validates geo_unit_id exists)
- `GET /members/{member}/nearby-committees` — Find committees covering member's geography

---

## Business Rules

| Rule | Statement | Enforced By |
|---|---|---|
| GEO-ELIG-1 | Member is eligible if committee's geo unit is ancestor of (or equal to) member's residence | `CommitteeEligibilityPolicy::isEligible()` |
| GEO-ELIG-2 | Central committees (no geography) are eligible for ALL members | `CommitteeEligibilityPolicy::isEligible()` |
| GEO-ELIG-3 | Members without residence cannot be assigned to geographic committees | `AssignMemberToCommitteeHandler::validateGeoEligibility()` |
| GEO-ELIG-4 | Central committees skip geo validation entirely | `AssignMemberToCommitteeHandler::validateGeoEligibility()` |

---

## Key Files

| File | Responsibility |
|---|---|
| `Domain/Committee/Ports/GeographicJurisdiction.php` | ACL DTO: geo unit data + path |
| `Domain/Committee/Ports/GeographicJurisdictionProvider.php` | ACL port interface |
| `Domain/Committee/Projections/GeoSemanticProjection.php` | Canonical projection: admin level + path |
| `Domain/Committee/Services/GeoSemanticProjectionBuilder.php` | Builds projections from provider (single source of truth) |
| `Domain/Committee/Factories/GeoPathChainFactory.php` | Pure factory: projection → chain |
| `Domain/Committee/ValueObjects/GeoPathChain.php` | Chain with path comparison methods |
| `Domain/Committee/Policies/CommitteeEligibilityPolicy.php` | Pure policy: 3-rule eligibility check |
| `Domain/Committee/Services/GeographicEligibilityValidator.php` | Domain service: projection→chain→policy |
| `Domain/Member/ValueObjects/MemberResidenceGeoIdentity.php` | VO wrapping residence geo unit FK |
| `Application/Committee/Commands/AssignMemberWithGeoCommand.php` | Command with full geo context |
| `Application/Committee/Handlers/AssignMemberToCommitteeHandler.php` | Application handler orchestrating validation + assignment |
| `Application/Committee/Services/NearbyCommitteesQueryService.php` | Read model: find covering committees |
| `Http/Controllers/Committee/MemberGeographyController.php` | Three endpoints for member geo CRUD + nearby query |
| `Infrastructure/Services/GeographicJurisdictionProviderAdapter.php` | Eloquent-based ACL implementation |
| `Infrastructure/Providers/MembershipServiceProvider.php` | DI bindings |

---

## Routes

```php
// All under: auth + verified + organisations/{organisation}
GET    /members/{member}/geography                 → MemberGeographyController@show
PUT    /members/{member}/geography                 → MemberGeographyController@update
GET    /members/{member}/nearby-committees          → MemberGeographyController@nearbyCommittees
```

---

## Test Patterns

### CommitteeEligibilityPolicyTest (9 tests)

Pure policy test — no mocks needed:

```php
// Eligible: central committee
new GeoPathChain(1, '', []),                          // committee
new GeoPathChain(100, '/1/23/456', [1, 23, 456]),    // member
// → true

// Eligible: same geo unit
new GeoPathChain(10, '/1/23', [1, 23]),               // committee
new GeoPathChain(10, '/1/23', [1, 23]),               // member
// → true

// Ineligible: different branch
new GeoPathChain(23, '/1/23', [1, 23]),               // committee
new GeoPathChain(99, '/1/99/456', [1, 99, 456]),      // member
// → false
```

### GeographicEligibilityValidator Test

Test through the validator — mock `GeographicJurisdictionProvider` (interface), use real `GeoSemanticProjectionBuilder`:

```php
$provider = $this->createMock(GeographicJurisdictionProvider::class);
$provider->method('resolve')
    ->willReturnMap([
        [456, new GeographicJurisdiction(456, 3, '3', 'NP', '/1/23/456')],
        [23, new GeographicJurisdiction(23, 2, '3', 'NP', '/1/23')],
    ]);

$builder = new GeoSemanticProjectionBuilder($provider);
$validator = new GeographicEligibilityValidator(
    $builder,
    new CommitteeEligibilityPolicy(),
);

$this->assertTrue($validator->validate(456, 23));
```

Key rule: **GeoSemanticProjectionBuilder is `final` — do NOT mock it. Mock the interface instead.**

### AssignMemberToCommitteeHandlerTest (6 tests)

Tests through the full flow — mock repositories, use real builder + policy:

```php
// Member in province 3, district 23, ward 456 — within committee area
$this->provider->method('resolve')
    ->willReturnMap([
        [456, new GeographicJurisdiction(456, 3, '3', 'NP', '/1/23/456')],
        [23, new GeographicJurisdiction(23, 2, '3', 'NP', '/1/23')],
    ]);

$this->handler->handle($this->makeCommand());
$assignments = $committee->getAssignments();
$this->assertCount(1, $assignments);
```

Always create geographic committees using `Committee::create()` (with explicit `geoUnitId`), not `createForGeography()` (which does not set `geoUnitId`):

```php
private function createGeographicCommittee(int $geoUnitId, GeoReference $operationalGeo): Committee
{
    $level = CommitteeLevel::create(
        index: 3, code: 'province', name: 'Province',
        geoPolicy: GeoPolicy::REQUIRED,
        geoScope: new GeoScope('NP'),
        roleLimits: ['president' => 1],
        minMembershipYears: 0, ageRange: null, genderRequirement: null,
    );

    $policy = new CommitteePolicy(
        type: CommitteeType::province(),
        structure: new GeographicCommitteeStructure(),
        level: $level,
    );

    return Committee::create(
        id: $this->committeeId,
        tenantId: $this->tenantId,
        policy: $policy,
        structureId: CommitteeStructureId::generate(),
        levelIndex: $level->index,
        levelName: $level->name,
        geoPolicy: $level->geoPolicy,
        geoScope: $level->geoScope,
        name: CommitteeName::fromString('Province Committee'),
        code: 'PC-001',
        operationalGeo: $operationalGeo,
        geoUnitId: $geoUnitId,  // ← explicit!
    );
}
```

---

## Common Pitfalls

1. **Committee::createForGeography() does NOT set geoUnitId**. `getGeoUnitId()` returns `null`, causing `validateGeoEligibility()` to skip the check. Always use `Committee::create()` with explicit `geoUnitId` for geographic committees.

2. **GeoSemanticProjectionBuilder is final**. Cannot mock directly. Mock `GeographicJurisdictionProvider` (interface) and pass the real builder.

3. **TenantId type mismatch**. The Member repository expects `Membership\Domain\ValueObjects\TenantId`. The AssignMemberWithGeoCommand carries `Shared\Domain\ValueObjects\TenantId`. Convert explicitly:
   ```php
   $memberTenantId = new MembershipTenantId($command->tenantId->value());
   ```

4. **MemberId type mismatch**. `assignMember()` expects `ValueObjects\MemberId` (flexible string). The Member aggregate uses `Domain\Member\MemberId` (UUID/ULID). Convert explicitly:
   ```php
   new \App\Contexts\Membership\Domain\ValueObjects\MemberId($command->memberId->value())
   ```

5. **NominationType factory methods**. Use `NominationType::elected()`, not `NominationType::ELECTED` (constants are private). Elected nomination requires `electionDate`.

6. **No `residence_geo_unit_id` column**. If the member's residence geo is not persisted, `getResidenceGeoIdentity()` returns `null` and geographic assignment throws `DomainException`. Run the Phase 8E migration before testing.

7. **Election date is required for elected nominations**. The `AssignMemberWithGeoCommand` must include `electionDate` when `nominationType` is `elected()`.

---

## Test Verification

```bash
# Policy tests (9 pure tests — no mocks)
php artisan test tests/Unit/Contexts/Membership/Domain/Committee/Policies/CommitteeEligibilityPolicyTest.php

# Validator tests (6 tests — mock provider only)
php artisan test tests/Unit/Contexts/Membership/Domain/Committee/Services/GeographicEligibilityValidatorTest.php

# Handler tests (6 tests — full integration flow)
php artisan test tests/Unit/Contexts/Membership/Application/Committee/AssignMemberToCommitteeHandlerTest.php

# All Phase 8E: 21 tests
```
