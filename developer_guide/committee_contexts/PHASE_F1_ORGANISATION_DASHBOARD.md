# Phase F1 — Organisation Dashboard Membership Integration

**Status:** ✅ Complete  
**Commit:** `f2d52d2fa`  
**Tags:** `F1-1-DONE`, `F1-DONE`

---

## Overview

Phase F1 integrates committee membership data into the organisation dashboard (`/organisations/{slug}`) without adding new routes or bounded contexts. The membership widget displays:

- **Active memberships** — committees where the member has `has_active_association`
- **Pending applications** — committees where the member has `has_pending_application`
- **Eligible committees** — committees the member can join (`can_apply`)

The implementation uses **TDD-first, production-grade DDD** with clean constructor injection and proper separation of concerns across Infrastructure → Application → Domain layers.

---

## Architecture

### Composition Root

The `OrganisationController::show()` acts as the composition root for the organisation context. It orchestrates:

1. **Membership query** — resolves member data via `MyCommitteesQueryService`
2. **Geo path resolution** — determines member's geographic context via `MemberGeoPathProviderPort`
3. **Data transformation** — converts domain view objects to Inertia-serializable primitives
4. **Inertia prop** — passes `membership` array to Vue component

```
┌─────────────────────────────────────────────┐
│  OrganisationController::show()              │
│  (Composition Root)                         │
└──────────────┬──────────────────────────────┘
               │
        ┌──────┴──────┐
        ▼             ▼
MyCommitteesQueryService  MemberGeoPathProviderPort
  (Membership Context)     (Membership Context)
        │                  │
        └──────┬───────────┘
               ▼
    Data Transform → Inertia Props
               │
               ▼
     Show.vue (MembershipWidget)
```

### Dependency Injection Chain

**MembershipServiceProvider (Infrastructure):**

```php
// Port → Implementation bindings
$this->app->bind(MemberGeoPathProviderPort::class, SessionMemberGeoPathProvider::class);
$this->app->bind(
    EligibleCommitteeQueryService::class,
    function ($app) {
        return new EligibleCommitteeQueryServiceImpl(
            $app->make(CommitteeRepositoryInterface::class),
            $app->make(MembershipLineageRepositoryPort::class),
            $app->make(MembershipApplicationRepositoryPort::class),
            $app->make(CommitteeEligibilityPolicy::class),
            $app->make(CommitteeGeoPathProviderPort::class),
        );
    }
);
$this->app->bind(MembershipApplicationRepositoryPort::class, StubMembershipApplicationRepository::class);
$this->app->bind(CommitteeGeoPathProviderPort::class, StubCommitteeGeoPathProvider::class);
```

**OrganisationController:**

```php
public function __construct(
    private readonly MyCommitteesQueryService $membershipService,
    private readonly MemberGeoPathProviderPort $geoPathProvider,
) {}
```

All dependencies resolved via constructor injection—no service locator, no `app()` calls.

---

## Boundary Crossing (Value Objects)

At the HTTP request boundary, domain value objects are constructed from user/organisation data:

```php
// In OrganisationController::show()
$memberId = MemberId::fromString((string) $user->id);
$tenantId = TenantId::fromOrganisationId((string) $organisation->id);
$memberGeoPath = $this->geoPathProvider->resolveForMember($memberId, $tenantId);
```

Then the membership query returns a view model of primitives:

```php
$membershipData = array_map(fn ($view) => [
    'committee_id' => $view->committeeId,
    'committee_name' => $view->committeeName,
    'committee_code' => $view->committeeCode,
    'governance_level' => $view->governanceLevel,
    'has_active_association' => $view->hasActiveAssociation,
    'has_pending_application' => $view->hasPendingApplication,
    'can_apply' => $view->canApply,
    'application_status' => $view->applicationStatus,
], $this->membershipService->getForMember($memberId, $tenantId, $memberGeoPath));
```

The Inertia prop is **primitives only** — no domain objects, no Eloquent models.

---

## Frontend Integration

### MembershipWidget.vue

**Location:** `resources/js/Pages/Organisations/Partials/MembershipWidget.vue`

A pure presentational component:

```vue
<template>
  <div class="portal-card portal-card--membership-detail">
    <!-- Icon, title, counts -->
  </div>
</template>

<script setup>
const props = defineProps({
  membership: { type: Array, default: () => [] },
})

const activeCount = computed(() => props.membership.filter(c => c.has_active_association).length)
const pendingCount = computed(() => props.membership.filter(c => c.has_pending_application).length)
const eligibleCount = computed(() => props.membership.filter(c => c.can_apply).length)
</script>
```

**No API calls. No watchers. No state.** Props-only.

### Zone 2 Integration (Show.vue)

The widget is placed in the **Member Portals** zone (Zone 2) alongside Voter Hub, Election Commission, and Organisation Roles:

```html
<div class="portal-grid">
  <!-- Voter Hub card -->
  <!-- Election Commission card (conditional) -->
  <!-- Membership card -->
  <!-- Organisation Roles card (conditional) -->
  
  <!-- New: Membership Widget (F1) -->
  <MembershipWidget :membership="membership" />
</div>
```

---

## Stub Implementations (Phase F1 Safe Defaults)

### StubMembershipApplicationRepository

**Purpose:** Safe empty default. Indicates no active applications in F1.

```php
public function existsActiveForTenant(
    MemberId $memberId,
    CommitteeId $committeeId,
    TenantId $tenantId
): bool {
    return false;  // No active applications until Phase F2
}
```

**Why:** Real implementation requires a `membership_applications` table and Eloquent model. F1 stubs to false, making all members eligible (unless blocked by other policies).

**Phase F2 replacement:** `EloquentMembershipApplicationRepository` with database queries.

### StubCommitteeGeoPathProvider

**Purpose:** Safe empty default. Geographic committees are ineligible in F1.

```php
public function resolveForCommittee(int $geoUnitId): GeoPathChain {
    return new GeoPathChain(1, '/', [1]);  // Root path = no geographic match
}
```

**Why:** Real implementation requires `GeoSemanticProjectionBuilder` to construct geographic hierarchies. F1 stubs to root level, making geographic committees ineligible (conservative default).

**Phase F2 replacement:** Real implementation using geo projection builder.

---

## SessionMemberGeoPathProvider

**Purpose:** Resolve member's geographic context from session/auth.

**Current F1 Behavior:**

```php
public function resolveForMember(MemberId $memberId, TenantId $tenantId): GeoPathChain {
    // TODO: Load from user profile / session
    // For now: return root geo path (member at top level)
    // This makes geographic committees ineligible (safe default)
    return new GeoPathChain(1, '/', [1]);
}
```

**Why:** Member's geographic assignment is not yet in the user profile. When available (Phase F2), this will query the user's profile data and return the correct geo path.

**Phase F2 change:** Load `user.geo_region` or similar and construct appropriate `GeoPathChain`.

---

## Testing Strategy

### Feature Tests

**File:** `tests/Feature/OrganisationDashboardMembershipTest.php`

Two tests verify the integration:

```php
public function test_organisation_show_includes_membership_prop(): void {
    // Test that 'membership' prop exists and is array
}

public function test_membership_prop_is_array(): void {
    // Test prop structure
}
```

### Unit Tests (Preserved)

All Constitutional/Membership unit tests remain passing:

```bash
php artisan test tests/Unit/Constitutional/Membership/ --no-coverage
# Expected: 72 passed, 4 incomplete
```

### Regression Gates (STEP 7)

```bash
# Unit tests
php artisan test tests/Unit/Constitutional/Membership/ --no-coverage
# Expected: 72 passed, 4 incomplete

# Feature tests
php artisan test tests/Feature/OrganisationDashboardMembershipTest.php --no-coverage
# Expected: 2 passed

# Vue build
npm run build
# Expected: Success (no errors)
```

---

## Files Changed

| File | Type | Change | Reason |
|------|------|--------|--------|
| `app/Contexts/Membership/Infrastructure/Providers/MembershipServiceProvider.php` | MODIFY | Add 4 DI bindings | Wire up ports to implementations |
| `app/Http/Controllers/OrganisationController.php` | MODIFY | Add constructor + query in show() | Composition root orchestration |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentMembershipLineageRepository.php` | MODIFY | Add `findLineageByMemberAndCommitteeForTenant()` | Satisfy interface contract |
| `resources/js/Pages/Organisations/Partials/MembershipWidget.vue` | CREATE | Widget component | Presentational layer |
| `resources/js/Pages/Organisations/Show.vue` | MODIFY | Add import + prop + widget | Integrate widget in Zone 2 |
| `tests/Feature/OrganisationDashboardMembershipTest.php` | CREATE | Feature tests | TDD validation |
| `app/Contexts/Membership/Infrastructure/Query/StubMembershipApplicationRepository.php` | CREATE | Stub implementation | Safe F1 default |
| `app/Contexts/Membership/Infrastructure/Query/StubCommitteeGeoPathProvider.php` | CREATE | Stub implementation | Safe F1 default |

---

## Phase F2 Prerequisites

To replace stub implementations with real database-backed versions:

### 1. Membership Applications Table

**Migration needed:**

```php
Schema::create('membership_applications', function (Blueprint $table) {
    $table->ulid('id')->primary();
    $table->ulid('organisation_id');
    $table->ulid('member_id');
    $table->ulid('committee_id');
    $table->enum('status', ['pending', 'approved', 'rejected', 'withdrawn'])->default('pending');
    $table->timestamp('applied_at')->useCurrent();
    $table->timestamp('decided_at')->nullable();
    $table->foreign('organisation_id')->references('id')->on('organisations');
    $table->index(['organisation_id', 'member_id', 'committee_id']);
});
```

### 2. Geographic Projection Builder

When user profile includes `geo_region`, update `SessionMemberGeoPathProvider`:

```php
// Phase F2 enhancement
$userGeoRegion = auth()->user()->geo_region;
return GeoSemanticProjectionBuilder::fromRegion($userGeoRegion)->build();
```

### 3. EloquentMembershipApplicationRepository

Replace `StubMembershipApplicationRepository` with real Eloquent implementation:

```php
final class EloquentMembershipApplicationRepository implements MembershipApplicationRepositoryPort {
    public function existsActiveForTenant(...): bool {
        return MembershipApplicationModel::where(...)
            ->where('status', 'pending')
            ->exists();
    }
}
```

---

## Key Invariants Maintained

✅ **72/72 constitutional tests** preserved (4 incomplete)  
✅ **No new routes** added (uses existing `/organisations/{slug}`)  
✅ **No domain logic in controller** (pure orchestration)  
✅ **Constructor injection only** (no `app()` service locator)  
✅ **Value objects at boundaries** (MemberId, TenantId instantiated in controller)  
✅ **Inertia props primitives only** (no domain objects)  
✅ **Widget pure presentational** (props-only, no state, no API calls)  
✅ **Stub implementations safe** (return empty defaults, not null)  

---

## Rollback Strategy

| Scenario | Command |
|----------|---------|
| Before any F1 change | `git reset --hard F1-BASELINE` |
| Controller broken | `git reset --hard F1-BASELINE` |
| Vue broken only | `git reset --hard F1-1-DONE` |
| Full safety reset | `git reset --hard E-DONE` |

---

## How to Extend (Phase F2+)

### Adding a New Committee Property to the Widget

1. **Query service** returns new `$view->newProperty` in `MyCommitteesQueryService`
2. **Controller** adds to mapping: `'new_property' => $view->newProperty`
3. **Vue component** receives via prop, filters/displays in computed property
4. **No domain changes** — data flow is unidirectional: Domain → Query → Controller → Vue

### Switching from Stub to Real Implementation

1. Create Eloquent model and migration
2. Create `Eloquent{Name}Repository` class implementing the port
3. Update binding in `MembershipServiceProvider`:
   ```php
   $this->app->bind(SomePort::class, EloquentSomeRepository::class);
   ```
4. Add tests for new implementation
5. Run regression suite to verify compatibility

---

## Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                     HTTP REQUEST BOUNDARY                        │
├─────────────────────────────────────────────────────────────────┤
│  OrganisationController::show()  ← Constructor Injection         │
│     ├─ MyCommitteesQueryService                                 │
│     └─ MemberGeoPathProviderPort                                │
└──────────────┬───────────────────────────────────────────────────┘
               │
        ┌──────┴──────────────────────────────┐
        │  Value Object Construction          │
        │  MemberId::fromString(user.id)      │
        │  TenantId::fromOrganisationId(...)  │
        └──────┬───────────────────────────────┘
               │
        ┌──────▼──────────────────────────────┐
        │    MEMBERSHIP CONTEXT                │
        │  (Domain + Application)              │
        ├──────────────────────────────────────┤
        │ MyCommitteesQueryService.getForMember│
        │   → Phase C eligibility logic        │
        │   → View model (primitives)          │
        └──────┬───────────────────────────────┘
               │
        ┌──────▼──────────────────────────────┐
        │  Data Transform (Primitives Only)    │
        │  ['committee_id' => $v->committeeId]│
        └──────┬───────────────────────────────┘
               │
        ┌──────▼──────────────────────────────┐
        │    INERTIA PROPS                     │
        │  'membership' => $membershipData     │
        └──────┬───────────────────────────────┘
               │
┌──────────────▼─────────────────────────────┐
│  FRONTEND BOUNDARY                          │
├─────────────────────────────────────────────┤
│  Show.vue                                   │
│    ├─ import MembershipWidget              │
│    ├─ prop: membership                     │
│    └─ <MembershipWidget :membership="..." />
└─────────────────────────────────────────────┘
   │
   └─ MembershipWidget.vue (Pure Presentational)
      ├─ No state
      ├─ No API calls
      ├─ Computed: activeCount, pendingCount, eligibleCount
      └─ Template: portal-card rendering
```

---

## Git History

```
f2d52d2fa F1-DONE: membership widget on organisation dashboard (Zone 2)
ba94a8b1a F1-1: inject MyCommitteesQueryService into OrganisationController via constructor
```

---

## Next Steps (Phase F2)

1. **Implement membership_applications table** with migrations
2. **Replace StubMembershipApplicationRepository** with EloquentMembershipApplicationRepository
3. **Enhance SessionMemberGeoPathProvider** to load from user profile
4. **Replace StubCommitteeGeoPathProvider** with GeoSemanticProjectionBuilder-based implementation
5. **Add integration tests** for real database implementations
6. **Run full regression suite** to verify compatibility

---

## Related Documentation

- [Committee Contexts README](./README.md) — Overall committee system architecture
- [Phase C: EligibleCommitteeQueryService](./committee_formation/IMPLEMENTATION_GUIDE.md) — Eligibility logic (single source of truth)
- [User Manual: Committee Membership](./user_manual/create-committee.md) — End-user perspective
- [CLAUDE.md](../../CLAUDE.md) — Backend architecture rules

