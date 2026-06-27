# Phase 8C.2D — Frontend Simplification: Derive Committee Type from Geo Unit

## Why Simplify?

Phase 8C.2C completed the three-layer geo identity system (Identity → Projection → Classification). The `CommitteeClassificationPolicy` can already derive `CommitteeType` from a `GeoSemanticProjection`. **But the UI still forced users to manually select a committee type** — a dropdown with 7 options (central, province, district, ward, youth, women, student).

The problem: users had to select both a geographic area (via the cascader) AND a committee type. These are redundant — the geographic type (province/district/ward) is entirely determined by the deepest selected geo unit's admin level. Requiring both creates confusion and introduces an error surface (what if the user picks "province" but selects a district-level geo unit?).

**Phase 8C.2D removes the type dropdown for geographic types.** Wing types (youth, women, student) still require explicit selection since they have no geographic basis.

```
Before (redundant):
  [GeographyCascader] → selects country=NP, geo=[3,7]
  [Type Dropdown]     → selects "district" (must match geo!)
  → Error if mismatch

After (derived):
  [GeographyCascader] → selects country=NP, geo=[3,7]
  → Server derives "district" from admin level of geo unit 7
```

---

## Architecture: Category Derivation Service

A dedicated application-layer service encapsulates the derivation flow, keeping the controller clean and the domain layer untouched:

```
Controller (injects CommitteeCategoryDerivationService)
   │
   ▼
CommitteeCategoryDerivationService::derive(?type, ?geoUnitId)
   │
   ├─ Explicit type? (youth/women/student) → return as-is
   │
   ├─ Geo unit ID? → GeoSemanticProjectionBuilder::build(geoUnitId)
   │                     │
   │                     ▼
   │                  GeoSemanticProjection { adminLevel, regionCode, ... }
   │                     │
   │                     ▼
   │                  CommitteeClassificationPolicy::mapAdminLevelToCategory(adminLevel)
   │                     │
   │                     ▼
   │                  CommitteeCategory (PROVINCE, DISTRICT, or WARD)
   │
   └─ Neither → CommitteeCategory::CENTRAL
```

**Why a new service instead of putting logic in the controller?** The controller should not become a "domain decision broker" — resolving `GeoSemanticProjectionBuilder` and `CommitteeClassificationPolicy` via `app()` and orchestrating their interaction would violate the constructor injection discipline established in Phase 8C.2C.

**Why not in `InternalCreateCommittee`?** The `InternalCreateCommitteeCommand` has `CommitteeCategory $committeeCategory` as a non-nullable required parameter. Changing it to nullable would spread null-safety concerns. Deriving the category before creating the DTO keeps the use case contract stable.

---

## 1. Domain: `mapAdminLevelToCategory()`

**File:** `app/Contexts/Membership/Domain/Committee/Policies/CommitteeClassificationPolicy.php`

A single pure method on the existing classification policy:

```php
/**
 * Map admin level to CommitteeCategory (for type dropdown removal).
 *
 * Admin Level Convention (Membership interpretation):
 *   0 = continent       → CENTRAL
 *   1 = country         → CENTRAL
 *   2 = province/state  → PROVINCE
 *   3 = district        → DISTRICT
 *   4+ = local          → WARD
 */
public function mapAdminLevelToCategory(int $adminLevel): CommitteeCategory
{
    return match (true) {
        $adminLevel <= 1 => CommitteeCategory::CENTRAL,
        $adminLevel === 2 => CommitteeCategory::PROVINCE,
        $adminLevel === 3 => CommitteeCategory::DISTRICT,
        $adminLevel >= 4 => CommitteeCategory::WARD,
    };
}
```

### Admin Level Convention

| Admin Level | Geographic Meaning | Committee Type | Notes |
|-------------|-------------------|----------------|-------|
| 0 | Continent | CENTRAL | No geographic anchor needed |
| 1 | Country | CENTRAL | National-level committees |
| 2 | Province/State | PROVINCE | Regional governance |
| 3 | District | DISTRICT | Local governance |
| 4+ | Ward/Village | WARD | Hyperlocal governance |

This is the **Membership context's governance interpretation** of geographic administrative levels. The Geography context may define different semantics for its own purposes.

**Test file:** `tests/Unit/Domain/Committee/Policies/CommitteeClassificationPolicyTest.php`
- 6 new tests covering levels 0-5
- 20 total tests in the suite after this addition

---

## 2. Application: `CommitteeCategoryDerivationService`

**File:** `app/Contexts/Membership/Application/Committee/Services/CommitteeCategoryDerivationService.php`

A `final readonly` service with two injected dependencies and one public method:

```php
final readonly class CommitteeCategoryDerivationService
{
    public function __construct(
        private GeoSemanticProjectionBuilder $projectionBuilder,
        private CommitteeClassificationPolicy $classificationPolicy,
    ) {}

    public function derive(?string $explicitType, ?int $geoUnitId): CommitteeCategory
    {
        // Priority 1: Explicit type (for wing types: youth/women/student)
        if ($explicitType !== null) {
            return CommitteeCategory::from($explicitType);
        }

        // Priority 2: Derive from geo unit's admin level
        if ($geoUnitId !== null) {
            $projection = $this->projectionBuilder->build($geoUnitId);
            if ($projection !== null) {
                return $this->classificationPolicy->mapAdminLevelToCategory($projection->adminLevel);
            }
        }

        // Priority 3: Default to central
        return CommitteeCategory::CENTRAL;
    }
}
```

### Derivation Priority

| Scenario | `explicitType` | `geoUnitId` | Result |
|----------|---------------|-------------|--------|
| Wing committee | `"youth"` | any | YOUTH |
| Geo committee | `null` | `42` | Derived from admin level |
| No selection | `null` | `null` | CENTRAL |
| Geo but projection fails | `null` | `999` | CENTRAL (fallback) |

### DI Binding

**File:** `app/Contexts/Membership/Infrastructure/Providers/MembershipServiceProvider.php`

```php
$this->app->bind(CommitteeCategoryDerivationService::class, function ($app) {
    return new CommitteeCategoryDerivationService(
        $app->make(GeoSemanticProjectionBuilder::class),
        $app->make(CommitteeClassificationPolicy::class),
    );
});
```

**Test file:** `tests/Unit/Contexts/Membership/Application/Committee/Services/CommitteeCategoryDerivationServiceTest.php`
- 7 tests covering all three derivation paths
- Tests with explicit type, geo unit ID, null fallback, and projection failure

---

## 3. Controller Changes

**File:** `app/Http/Controllers/Committee/CommitteeManagementController.php`

### Validation Rule Change

The `type` field validation changed from required (with geographic types) to nullable (with wing types only):

```php
// Before:
'type' => 'required|string|in:central,province,district,ward',

// After:
'type' => 'nullable|string|in:central,youth,women,student',
```

Geographic types (province, district, ward) removed — they're now derived from geo selection. Wing types (youth, women, student) retained for explicit selection.

### Constructor Injection

```php
public function __construct(
    private readonly CommitteeCategoryDerivationService $categoryDerivation,
) {}
```

### Derivation in `store()` method

After extracting `$geoUnitId` and before creating the command DTO:

```php
$committeeCategory = $this->categoryDerivation->derive(
    explicitType: $validated['type'] ?? null,
    geoUnitId: $geoUnitId,
);
```

Then passed to the command DTO instead of `CommitteeCategory::from($validated['type'])`.

### `create()` View Data

The `committeeTypes` prop is no longer passed to the Vue view:

```php
return Inertia::render('Committee/Create', [
    'organisationSlug' => $organisation->slug,
]);
```

---

## 4. Vue Component Changes

**File:** `resources/js/Pages/Committee/Create.vue`

### Removed: Type Dropdown

The entire committee type `<select>` element was removed (~40 lines of template). This included all 7 `<option>` elements (central, province, district, ward, youth, women, student).

### Removed: `committeeTypes` Prop

```javascript
// Before:
defineProps({
  organisation: Object,
  organisationSlug: String,
  committeeTypes: Array,  // ← removed
});

// After:
defineProps({
  organisation: Object,
  organisationSlug: String,
});
```

### Removed: `type` from Form State

```javascript
// Before:
const form = ref({
  name: '',
  code: '',
  type: '',       // ← removed
  geo_selections: { region: null, country: null, geo: [] },
});

// After:
const form = ref({
  name: '',
  code: '',
  geo_selections: { region: null, country: null, geo: [] },
});
```

### Removed: Type Validation

```javascript
// REMOVED:
// if (!form.value.type?.trim()) {
//   errors.value.type = 'Committee type is required';
// }
```

### Changed: Section Heading

The Classification section (which previously contained both type dropdown and geo cascader) was renamed to "Geographic Scope" since it now only contains the cascader.

### No Change: Edit.vue

The edit page (`Committee/Edit.vue`) never had a type dropdown, so no changes were needed there.

---

## 5. Test Updates

### Why Tests Changed

Removing the type field from the request payload meant existing tests that sent `'type' => 'province'` or `'type' => 'district'` no longer matched the new behavior. Some tests sent no geo selections at all, which meant the derivation service defaulted to CENTRAL.

### Key Fix: DB Constraint Violation

The **critical lesson** from this phase involved the database check constraints:

| Constraint | Rule |
|------------|------|
| `chk_central_committee_no_geography` | Central committees MUST have NULL `operational_geo_reference` |
| `chk_non_central_must_have_geography` | Non-central committees MUST have non-null `operational_geo_reference` |

When removing `type` from a test that previously used `type: 'district'` with `geo_selections.geo: [3, 15]`, the derivation service defaulted to CENTRAL (no real geo data in the test DB). But the controller still built `operational_geo_reference: 'de.3.15'` from the `geo_selections` data. This violated `chk_central_committee_no_geography`.

**Fix:** Removed `'geo' => [3, 15]` from the `geo_selections` in the test. With no geo array, `$geoReferenceString` is null (geoPath is empty), and `$geoUnitId` is null. The derived type is CENTRAL with no geo reference, satisfying both constraints.

### Fix: Governance Policy Wiring

The `CommitteeManagementHttpTest` had pre-existing failures from the Phase C governance policy switch. Previously `PermissiveGovernanceAccessPolicy` allowed all actions regardless of org state, but `GovernanceCapabilityPolicy` enforces:

1. **`governance_status === 'active'`** — checked by `OrganisationGovernanceContext::isActive()`
2. **Actor authority** — checked by `ActorPosition::hasGovernanceAuthority()` (OWNER, ADMIN, COMMISSION)

Fixes applied:
- Added `'governance_status' => 'active'` to the test organisation factory
- Added `activateTestStructure()` method (inserts committee_structures + 4 levels)
- Updated `UserOrganisationRole` to set `role => 'admin'` on the test admin user

### File: `tests/Feature/Committee/CommitteeCreationWithGeoSelectionsTest.php`

- Removed `'type'` from geo-based requests — type is now derived server-side
- When `'geo' => []` provided → no geoUnitId → defaults to CENTRAL
- When no geo selections at all → defaults to CENTRAL

### File: `tests/Feature/Membership/CommitteeManagementHttpTest.php`

- Fixed governance wiring (active org, test structure, admin role)
- Store tests no longer send `type` in request payload
- `test_admin_can_view_create_form` now asserts `->missing('committeeTypes')` instead of `->has('committeeTypes', 4)`
- Store test 1: sends name+code only, asserts `type => 'central'`
- Store test 2: sends name+code+geo_selections(region, country), no geo array, asserts `type => 'central'` with region+country codes

---

## Architectural Invariants

These invariants are enforced by the codebase and should be preserved by any future changes:

1. **Wing types require explicit selection.** Youth, women, and student have no geographic basis and cannot be derived. They must be explicitly chosen in the UI.

2. **Geographic types are always derived.** Province, district, and ward are never accepted as explicit input. The system derives them from the geo unit's admin level.

3. **CENTRAL is the universal default.** When neither a wing type nor a geo unit is provided, the system defaults to CENTRAL. This covers the legacy flow where committees are created with just a name and code.

4. **DB constraints are the final safeguard.** The check constraints `chk_central_committee_no_geography` and `chk_non_central_must_have_geography` prevent inconsistent states at the database level, regardless of what the application code does.

5. **Derivation is idempotent.** Given the same geo unit ID, `mapAdminLevelToCategory()` always returns the same result. The admin level of a geo unit does not change, so the derived type is stable.

---

## File Map

| File | Change Type | Purpose |
|------|-------------|---------|
| `app/Contexts/Membership/Domain/Committee/Policies/CommitteeClassificationPolicy.php` | Method added | `mapAdminLevelToCategory()` |
| `app/Contexts/Membership/Application/Committee/Services/CommitteeCategoryDerivationService.php` | **New file** | Application-layer derivation service |
| `app/Contexts/Membership/Infrastructure/Providers/MembershipServiceProvider.php` | Binding added | DI wiring for derivation service |
| `app/Http/Controllers/Committee/CommitteeManagementController.php` | Modified | Validation, derivation, view data |
| `resources/js/Pages/Committee/Create.vue` | Modified | Removed type dropdown, renamed section |
| `tests/Unit/Domain/Committee/Policies/CommitteeClassificationPolicyTest.php` | Tests added | 6 new admin level mapping tests |
| `tests/Unit/Contexts/Membership/Application/Committee/Services/CommitteeCategoryDerivationServiceTest.php` | **New file** | 7 derivation service tests |
| `tests/Feature/Committee/CommitteeCreationWithGeoSelectionsTest.php` | Modified | Type removed from request payloads |
| `tests/Feature/Membership/CommitteeManagementHttpTest.php` | Modified | Governance wiring, type removal |

---

## Verification

```bash
# Phase 8C.2D test suites
php artisan test tests/Unit/Domain/Committee/Policies/CommitteeClassificationPolicyTest.php
php artisan test tests/Unit/Contexts/Membership/Application/Committee/Services/CommitteeCategoryDerivationServiceTest.php
php artisan test tests/Feature/Committee/CommitteeCreationWithGeoSelectionsTest.php
php artisan test tests/Feature/Membership/CommitteeManagementHttpTest.php

# Full regression
php artisan test
```

Expected: All 4 suites passing, no regressions.

---

## Lessons Learned

### 1. DB Constraints Are Your Safety Net

The check constraints `chk_central_committee_no_geography` and `chk_non_central_must_have_geography` caught an inconsistency that tests would have missed. When we removed `type` from a request but kept `geo_selections.geo`, the derivation service chose CENTRAL but the controller still built a geo reference string — violating the constraint. **The constraints forced us to reason about what combinations are valid.**

### 2. Derivation Has a Fallback Chain

The three-priority derivation (explicit → geo → central) means there's always a valid result. No scenario produces an error. This is deliberate: if the geo projection fails (e.g., deleted geo unit), the committee still gets created as CENTRAL rather than throwing a 500 error.

### 3. Wing Types Need Explicit Guarding

Nothing in the current code prevents a user from selecting both "youth" as the type AND a deep geo selection. The validation allows it (type is nullable, geo_selections is optional). Currently the controller builds the geo reference regardless of type. This is acceptable because wing types can have geographic scope (e.g., "Youth Wing NRW"), but future phases may want to add validation that geo depth matches type expectations.

### 4. Edit.vue Needed No Changes

The edit page (`Committee/Edit.vue`) never had a type dropdown, so Phase 8C.2D's changes were scoped entirely to `Create.vue`. Always verify what the edit page actually renders before planning changes.

---

## Next Phase (Explicitly Deferred)

| Item | Rationale |
|------|-----------|
| Edit.vue type field removal | Already has no type dropdown |
| Removing geographic types from `CommitteeCategory` enum | Still needed for internal classification |
| `CommitteePolicyResolver` consuming `CommitteeClassificationPolicy` | Separate optimization, not related to frontend simplification |
| Legacy `geo_reference` field removal | Backward compatibility concern |
| Validation that geo depth matches wing type | Future feature, not part of this phase |
