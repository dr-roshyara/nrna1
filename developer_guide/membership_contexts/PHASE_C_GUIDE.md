# Phase C: EligibleCommitteeQueryService & CommitteeIdBridge

**Date:** May 14, 2026  
**Status:** Phase C In Progress — B.1.5 Complete ✅

---

## What Was Built (This Session)

### B.1.5: Propagation Validation Layer ✅ COMPLETE

**Purpose:** Validate that CommitteeIdBridge (anti-corruption layer) safely handles type conversion at repository boundaries before Phase C proceeds.

**Location:** `tests/Unit/Constitutional/Membership/CommitteeIdPropagationTest.php`

**Status:** 5/5 TESTS PASSING ✅

#### Tests

| Test | Purpose | Status |
|------|---------|--------|
| `test_repository_accepts_legacy_committee_id_and_retrieves` | Legacy ID → repository → retrieval works | ✅ PASS |
| `test_multiple_committees_with_different_legacy_ids` | Multiple committees maintain ID isolation | ✅ PASS |
| `test_repository_returns_consistent_id_across_multiple_retrievals` | Multiple retrievals return consistent IDs | ✅ PASS |
| `test_bridge_round_trip_maintains_identity` | Legacy ↔ canonical ↔ legacy preserves values | ✅ PASS |
| `test_repository_exists_method_accepts_legacy_committee_id` | `exists()` method handles legacy IDs | ✅ PASS |

**16 Total Assertions Passed**

---

## CommitteeIdBridge: Anti-Corruption Layer

### Why It Exists

**Problem:** Codebase has TWO CommitteeId types:
- **Legacy:** `App\Contexts\Membership\Domain\ValueObjects\CommitteeId` (82+ files)
- **Canonical:** `App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId` (42+ files)

**Why Dual Types?** Domain refactoring in progress. Committee aggregate was separated into own bounded context with its own CommitteeId.

**Solution:** CommitteeIdBridge — temporary anti-corruption layer enabling safe coexistence during migration.

---

### How It Works

**Location:** `app/Contexts/Membership/Domain/Shared/Identity/CommitteeIdBridge.php`

**Normalization at Repository Boundary:**

```php
// EloquentCommitteeRepository.php
public function findForTenant(CommitteeId|CanonicalCommitteeId $id, TenantId $tenantId): ?Committee
{
    // CRITICAL: Normalize at entry point (bridge one-way)
    $id = CommitteeIdBridge::toCanonical($id);
    
    // Rest of method uses canonical type only
    $model = CommitteeModel::where('id', $id->value())->first();
    // ...
}
```

**Repository Interface (Dual Type Acceptance):**

```php
// app/Contexts/Membership/Domain/Repositories/CommitteeRepositoryInterface.php

public function findForTenant(CommitteeId|CanonicalCommitteeId $id, TenantId $tenantId): ?Committee;
public function existsForTenant(CommitteeId|CanonicalCommitteeId $id, TenantId $tenantId): bool;
public function deleteForTenant(CommitteeId|CanonicalCommitteeId $id, TenantId $tenantId): void;
```

---

### Bridge API

```php
use App\Contexts\Membership\Domain\Shared\Identity\CommitteeIdBridge;

// Convert legacy → canonical
$canonical = CommitteeIdBridge::toCanonical(
    new LegacyCommitteeId('some-id')
);
// Returns: CanonicalCommitteeId

// Convert canonical → legacy
$legacy = CommitteeIdBridge::toLegacy(
    CanonicalCommitteeId::fromString('some-id')
);
// Returns: LegacyCommitteeId
```

**Both conversions preserve identity (no data loss).**

---

### Bridge Lifecycle

```
CREATE                Temporary coexistence layer
   ↓
TEST (B.1.5)          Validate type safety at boundaries
   ↓
USE (Phase C)         EligibleCommitteeQueryService proven safe
   ↓
MIGRATE               Full CommitteeId migration to canonical
   ↓
DELETE                Bridge removed after migration complete
```

**Current Status:** Between TEST and USE phases

---

## Phase C: EligibleCommitteeQueryService

### Purpose

Answer governance question: **"Which committees can this member constitutionally join?"**

NOT:
- "Can member vote?" (VotingEligibilityPolicy handles that)
- "What is member's current role?" (MembershipLineage handles that)
- "Is there a pending application?" (Application system handles that)

ONLY: Geographic eligibility + active status + state decoration

---

### Architecture

**Interface:**
```php
interface EligibleCommitteeQueryService
{
    /**
     * @return EligibleCommitteeView[]
     */
    public function eligibleForMember(
        MemberId $memberId,
        TenantId $tenantId,
        GeoPathChain $memberGeoPath,
    ): array;
}
```

**Implementation:** `EligibleCommitteeQueryServiceImpl`

**Location:** `app/Contexts/Membership/Application/Membership/Query/`

---

### Data Transfer Object (DTO)

**EligibleCommitteeView** — read-only, primitives only

```php
final readonly class EligibleCommitteeView
{
    public function __construct(
        public string $committeeId,           // UUID
        public string $committeeName,         // Display name
        public string $committeeCode,         // Code (CC-001, etc.)
        public int $governanceLevel,          // 0=central, higher=more local
        public bool $hasActiveAssociation,    // Member is already in committee
        public bool $hasPendingApplication,   // Member has pending application
    ) {}
}
```

**Why DTO?**
- No aggregate leakage
- Type-safe for HTTP responses
- Explicit about what caller sees
- Immutable (readonly)

---

### Orchestration Flow

```
eligibleForMember(memberId, tenantId, memberGeoPath)
    ↓
Load all committees for tenant
    ↓
Filter by status = active
    ↓
For each committee, evaluate geographic eligibility:
    ├─ IF central → eligible (geography irrelevant)
    └─ IF geographic → check CommitteeEligibilityPolicy
        └─ member.path STARTS WITH committee.path? → eligible : ineligible
    ↓
For each eligible committee, decorate with state:
    ├─ hasActiveAssociation ← MembershipLineage query
    └─ hasPendingApplication ← MembershipApplication query
    ↓
Sort deterministically:
    ├─ Primary: governanceLevel ASC (central first)
    └─ Secondary: committeeName ASC (alphabetical)
    ↓
Return EligibleCommitteeView[]
```

---

### Geographic Eligibility Rules

**Rule 1 (Central):** GEO-ELIG-2
- **Committee:** Central (null geoUnitId)
- **Members:** ALL eligible
- **Logic:** Skip policy check, return true immediately

**Rule 2 (Geographic):** GEO-ELIG-1/3
- **Committee:** Province, District, Ward (non-null geoUnitId)
- **Members:** Only those whose geography matches
- **Logic:** Check CommitteeEligibilityPolicy (path matching)

**Rule 3 (No Geography):** Ineligible for geographic committees
- **Member:** No geo path set (empty string)
- **Committee:** Geographic
- **Result:** INELIGIBLE (cannot join regional committees without location)

---

### Usage Example

```php
// In controller or command handler
$service = app(EligibleCommitteeQueryService::class);

$eligible = $service->eligibleForMember(
    memberId: new MemberId('user-123'),
    tenantId: TenantId::fromString('tenant-456'),
    memberGeoPath: GeoPathChain::fromString('np.1.12.345'),
    // Member is in Ward 345 of District 12 in Province 1
);

// Returns: EligibleCommitteeView[]
// Sorted by level (central first), then name

foreach ($eligible as $view) {
    printf(
        "Committee: %s (Level %d, Associated: %s)\n",
        $view->committeeName,
        $view->governanceLevel,
        $view->hasActiveAssociation ? 'Yes' : 'No'
    );
}

// Output might be:
// Committee: Central Committee (Level 0, Associated: Yes)
// Committee: Province Committee (Level 2, Associated: No)
// Committee: District Committee (Level 3, Associated: No)
```

---

### Dependencies (Ports & Repositories)

**Injected:**
1. `CommitteeRepositoryInterface` — Load committees
2. `MembershipLineageRepositoryPort` — Check member associations
3. `MembershipApplicationRepositoryPort` — Check pending applications
4. `CommitteeEligibilityPolicy` — Evaluate geographic eligibility
5. `CommitteeGeoPathProviderPort` — Resolve geoUnitId → GeoPathChain

**Why Ports?**
- Service remains testable without geo infrastructure
- Mock ports in tests, real implementations in production
- Decouples query logic from infrastructure

---

## Test-First Implementation (TDD)

### Test File

**Location:** `tests/Unit/Constitutional/Membership/Query/EligibleCommitteeQueryServiceTest.php`

**Status:** RED (14 tests created, failing because implementation incomplete)

### Test Categories (14 Tests)

#### Geo Eligibility (4 tests)
```php
test_central_committee_eligible_for_member_with_no_geo_path
test_central_committee_eligible_for_member_with_geo_path
test_geographic_committee_eligible_when_member_path_matches
test_geographic_committee_ineligible_when_member_path_does_not_match
test_member_with_empty_geo_path_ineligible_for_geographic_committee
```

#### Status Filtering (2 tests)
```php
test_inactive_committee_excluded_from_results
test_dissolved_committee_excluded_from_results
```

#### Association Decoration (2 tests)
```php
test_eligible_committee_with_active_association_has_flag_true
test_eligible_committee_without_association_has_flag_false
```

#### Application Decoration (2 tests)
```php
test_eligible_committee_with_pending_application_has_flag_true
test_eligible_committee_without_pending_application_has_flag_false
```

#### Ordering (2 tests)
```php
test_results_sorted_by_governance_level_ascending
test_results_sorted_by_name_when_levels_equal
```

#### Boundary Cases (2 tests)
```php
test_no_committees_returns_empty_array
test_all_ineligible_committees_returns_empty_array
```

---

### RED → GREEN → REFACTOR

**Phase C.1 (CURRENT):** RED — Tests written, failing
**Phase C.2:** GREEN — EligibleCommitteeQueryServiceImpl completed
**Phase C.3:** REFACTOR — Optimize, extract helpers if needed
**Phase C.4:** Verify full constitutional regression (52+ Phase B tests + 14 Phase C tests)

---

## Integration Points

### With MembershipLineage

```php
// Query service checks if member is already in committee
$lineage = $this->membershipLineageRepository->findLineageByMemberAndCommitteeForTenant(
    memberId: $memberId,
    committeeId: $committee->getId(),
    tenantId: $tenantId,
);

$hasActiveAssociation = $lineage !== null && $lineage->isActive();
```

---

### With MembershipApplication

```php
// Query service checks for pending applications
$hasPending = $this->membershipApplicationRepository->existsActiveForTenant(
    memberId: $memberId,
    committeeId: $committeeId,
    tenantId: $tenantId,
);
```

---

### With CommitteeEligibilityPolicy

```php
// Query service evaluates geographic eligibility
$isEligible = $this->committeeEligibilityPolicy->isEligible(
    committeeGeoPath: $committeeGeoPath,      // From CommitteeGeoPathProviderPort
    memberGeoPath: $memberGeoPath,            // From caller
);
```

---

## Architecture Decisions (Recorded)

### Decision 1: CommitteeIdBridge Instead of Immediate Refactor

| Aspect | Decision | Rationale |
|--------|----------|-----------|
| Approach | Anti-corruption bridge, not big-bang refactor | 82+ vs 42 file usage distribution requires validation first |
| Validation | B.1.5 propagation tests (5/5 passing) | Proves type safety without full migration |
| Migration | Later phase after Phase C proves safe | Current priority is governance query stability |

---

### Decision 2: DTO Instead of Aggregate Exposure

| Aspect | Decision | Rationale |
|--------|----------|-----------|
| Return Type | EligibleCommitteeView (DTO) | Prevents mutation, explicit about read model |
| Fields | Primitives only, no objects | HTTP serialization safe, type-explicit |
| Immutability | readonly class | Guarantees caller cannot modify result |

---

### Decision 3: Ports for Infrastructure Dependencies

| Aspect | Decision | Rationale |
|--------|----------|-----------|
| GeoPathProvider | Port interface, not direct dependency | Service testable without geo infrastructure |
| Lineage/Application | Port interfaces | Can mock in tests, real impl in production |
| Mocks in Tests | Stub/mock all 5 dependencies | Tests verify orchestration, not implementation |

---

### Decision 4: Pure Query (No Mutations)

| Aspect | Decision | Rationale |
|--------|----------|-----------|
| Side Effects | None (read-only) | Query services never change state |
| Domain Events | None | No business event triggered by queries |
| Transactions | None needed | Read-only, no consistency risk |

---

## Files Created/Modified

### New Files

| File | Purpose | Status |
|------|---------|--------|
| `EligibleCommitteeView.php` | DTO for query results | ✅ Created |
| `EligibleCommitteeQueryService.php` | Interface | ✅ Created |
| `CommitteeGeoPathProviderPort.php` | Port interface | ✅ Created |
| `EligibleCommitteeQueryServiceImpl.php` | Implementation | ✅ Created (shell) |
| `EligibleCommitteeQueryServiceTest.php` | 14 TDD tests | ✅ Created (RED) |
| `CommitteeIdPropagationTest.php` | B.1.5 validation | ✅ Created (5/5 PASS) |

### Modified Files

| File | Change | Status |
|------|--------|--------|
| `CommitteeRepositoryInterface.php` | Accept `CommitteeId\|CanonicalCommitteeId` | ✅ Updated |
| `EloquentCommitteeRepository.php` | Normalize types via bridge | ✅ Updated |

### Created (Bridge)

| File | Purpose | Status |
|------|---------|--------|
| `CommitteeIdBridge.php` | Anti-corruption layer | ✅ Created (TEMPORARY) |

---

## Testing Strategy for Phase C

### Unit Tests (14 Tests)

**Base Class:** `PureDomainTestCase` (no database)

**Mocking Strategy:**
```php
// Mock all 5 dependencies
$committeeRepository = Mockery::mock(CommitteeRepositoryInterface::class);
$lineageRepository = Mockery::mock(MembershipLineageRepositoryPort::class);
$applicationRepository = Mockery::mock(MembershipApplicationRepositoryPort::class);
$policy = Mockery::mock(CommitteeEligibilityPolicy::class);
$geoProvider = Mockery::mock(CommitteeGeoPathProviderPort::class);

// Service orchestrates mocked dependencies
$service = new EligibleCommitteeQueryServiceImpl(
    $committeeRepository,
    $lineageRepository,
    $applicationRepository,
    $policy,
    $geoProvider,
);
```

### Integration Tests (Later)

After Phase C.3 (implementation complete), add:
- Database-backed tests with real repositories
- Full workflow tests (multiple tenants, geographic hierarchies)
- Performance tests (10K+ committees, scale testing)

---

## Common Patterns (Phase C)

### Pattern 1: Geographic Eligibility Check

```php
if ($committee->getGeoUnitId() === null) {
    // Central committee — always eligible
    return true;
}

if ($memberGeoPath->isEmpty()) {
    // Geographic committee but member has no geography
    return false;
}

// Resolve committee geography and check policy
$committeeGeoPath = $this->geoPathProvider->resolveForCommittee($committee->getGeoUnitId());
return $this->policy->isEligible($committeeGeoPath, $memberGeoPath);
```

### Pattern 2: Decoration with State

```php
$lineage = $this->membershipLineageRepository->findLineageByMemberAndCommitteeForTenant(
    memberId: $memberId,
    committeeId: $committee->getId(),
    tenantId: $tenantId,
);

$view = new EligibleCommitteeView(
    committeeId: (string) $committee->getId(),
    committeeName: $committee->getName()->value(),
    governanceLevel: $committee->levelIndex() ?? \PHP_INT_MAX,
    hasActiveAssociation: $lineage !== null && $lineage->isActive(),
    hasPendingApplication: $this->membershipApplicationRepository->existsActiveForTenant(...),
);
```

### Pattern 3: Deterministic Sorting

```php
usort($views, function (EligibleCommitteeView $a, EligibleCommitteeView $b) {
    // Primary: governance level (lower = more central)
    if ($a->governanceLevel !== $b->governanceLevel) {
        return $a->governanceLevel <=> $b->governanceLevel;
    }
    
    // Secondary: committee name (alphabetical)
    return $a->committeeName <=> $b->committeeName;
});
```

---

## Next Steps

### Phase C.2: GREEN (Next)
1. Implement `EligibleCommitteeQueryServiceImpl`
2. Wire up all 5 dependencies
3. Run 14 tests → expect all PASSING
4. Verify B.1.5 regression still 5/5

### Phase C.3: REFACTOR
1. Extract helpers if needed
2. Optimize hot paths
3. Add documentation/comments
4. Code review

### Phase C.4: VERIFY
1. Run full constitutional test suite
2. Expect 52 (Phase B) + 14 (Phase C) = 66 tests passing
3. Performance baseline
4. Ready for Phase D (dashboard/UI integration)

---

## Reference

**Bridge Pattern:**
- Temporary anti-corruption layer
- B.1.5 validates safety (5/5 tests)
- Will be deleted after Phase C stabilizes

**Query Service Pattern:**
- Pure read-only orchestration
- Multiple repository/policy dependencies
- Returns immutable DTO
- No domain events, no mutations

**DTO Pattern:**
- Primitives only (no objects)
- readonly (immutable)
- Explicit about what caller sees
- Type-safe for HTTP

---

## Status Summary

| Component | Status | Tests | Coverage |
|-----------|--------|-------|----------|
| **CommitteeIdBridge** | ✅ STABLE | 5/5 PASS | 100% (B.1.5) |
| **CommitteeRepositoryInterface** | ✅ UPDATED | N/A | Interface extended |
| **EligibleCommitteeQueryService** | 🔄 IN PROGRESS | 14 RED | TDD in progress |
| **Phase B (Membership)** | ✅ COMPLETE | 52 PASS | Regression protected |
| **Phase C.1 (TDD)** | ✅ COMPLETE | 14 RED | Tests written |
| **Phase C.2 (Implementation)** | ⏳ PENDING | — | Next |

---

**Last Updated:** 2026-05-14  
**Author:** Engineering Team  
**Maintainer:** Architecture  
**Review Status:** Ready for implementation (Phase C.2)
