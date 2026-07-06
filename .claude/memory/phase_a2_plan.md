---
name: Phase A2 Governance Epoch Integrity Plan
description: TDD-first implementation of governance temporal invariants (G-002, G-003, G-006)
type: project
originSessionId: 0b8d9422-360e-4388-83e6-9972ffe33c53
---
# Phase A2: Governance Epoch Integrity — Foundation + Test Strategy

**Status**: Tests written and failing (RED state)  
**Date Started**: 2026-05-07  
**Architecture**: Temporal governance system with epoch-based semantics

---

## What Phase A2 Accomplishes

Converts governance model from:
- Logically correct versioning
- To physically enforced temporal integrity

Makes impossible:
- Duplicate versions within tenant
- Governance branching (multiple DRAFT successors)
- Evolution of non-ACTIVE structures

---

## Core Artifacts Created

### 1. GOVERNANCE_INVARIANTS.md
- Formal documentation of 6 temporal invariants (G-001 through G-006)
- G-001 ✅ (already implemented)
- G-002 🚧 (unique versioning — target for A2)
- G-003 🚧 (single draft successor — target for A2)
- G-004 ✅ (immutable snapshots — done in A1)
- G-005 ⏸️ (temporal windows — deferred to A3)
- G-006 🚧 (no deprecated evolution — target for A2)

### 2. TemporalIntegrityConstraintsTest.php
Test-driven specification with 12 failing tests:

**G-002 Tests (Unique Versioning)**
- ❌ test_cannot_create_duplicate_version_within_tenant
- ✅ test_version_numbers_are_unique_per_tenant_isolation (passing)

**G-003 Tests (Single Draft Successor)**
- ❌ test_cannot_create_multiple_draft_successors
- ❌ test_prevents_governance_branching

**G-006 Tests (Evolution Guards)**
- ❌ test_cannot_evolve_deprecated_structure
- ❌ test_only_active_structures_can_evolve

**Repository API Tests**
- ❌ test_can_find_draft_successor_of_structure
- ❌ test_can_find_structure_by_version
- ❌ test_can_retrieve_full_lineage_chain
- ❌ test_lineage_chain_cannot_have_gaps

**Regression Test (G-001)**
- ❌ test_only_one_active_structure_per_tenant

### 3. Migration: enforce_governance_epoch_integrity.php
Ready to apply constraints:
- `UNIQUE (organisation_id, version)` — G-002
- `UNIQUE (parent_structure_id) WHERE status = 'DRAFT'` — G-003
- Lineage indexes for performance
- Status/version indexes for queries

---

## Current Test Results

```
Total: 12 tests
Passing: 1
Failing: 11 (expected — RED phase)

Blocked by:
1. Repository methods not implemented
2. Domain guards not implemented
3. Database constraints not yet applied
4. Repository hardDelete() method missing
```

---

## Implementation Sequence (Next Steps)

### Step 1: Add Domain Guard (CannotEvolveNonActiveStructure)
File: `app/Contexts/Membership/Domain/Committee/CommitteeStructure.php`
- Add exception: `CannotEvolveNonActiveStructure`
- Update `evolve()` method to check `isActive()`
- Estimated: 10 min

### Step 2: Add Repository Methods
File: `app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeStructureRepository.php`
- `findDraftSuccessorOf(CommitteeStructureId): ?CommitteeStructure`
- `findVersion(TenantId, int): ?CommitteeStructure`
- `findLineageChain(CommitteeStructureId): array`
- `hardDelete(CommitteeStructure): void`
- Estimated: 30 min

### Step 3: Apply Migration
Database constraints enforcement
- Run migration to add UNIQUE constraints
- Verify indexes created
- Estimated: 5 min

### Step 4: Verify Tests GREEN
Run full test suite
- All 12 TemporalIntegrityConstraintsTest tests should pass
- Regression tests (TemporalIdentityTest, TemporalSnapshotTest, CreateCommitteeUseCaseTest) must stay GREEN
- Estimated: 10 min

---

## Key Design Decisions Made

### Decision 1: UNIQUE (organisation_id, version)
- Allows each tenant to have v1, v2, v3
- Prevents same tenant from having duplicate versions
- Foundation for stable governance references

### Decision 2: UNIQUE (parent_structure_id) WHERE status = 'DRAFT'
- Only ONE DRAFT successor per parent allowed
- Multiple DEPRECATED successors allowed (historical)
- Prevents governance branching

### Decision 3: Domain Guard First, Database Second
- Domain throws `CannotEvolveNonActiveStructure` immediately
- Database constraint is failsafe
- Defensive layering

### Decision 4: Lineage as First-Class Query API
- Dedicated repository methods for temporal queries
- Not mixed with generic find() methods
- Semantic clarity

---

## Risk Assessment

**Low Risk**:
- ✅ Tests are isolated (RefreshDatabase)
- ✅ No existing data conflicts expected (new feature)
- ✅ Constraints are additive (no destructive changes)

**Mitigation**:
- Run full regression test suite before & after
- Migration includes rollback (down() method)
- Verify no orphaned structures before applying constraints

---

## Expected Outcome (After A2)

```
FROM:
  Committee ─────────────► CommitteeStructure (live, mutable)
  
TO:
  Committee ──snapshots─► Governance epoch (immutable, versioned)
  
+ System now enforces:
  ✓ One ACTIVE epoch per tenant
  ✓ Unique versions within lineage
  ✓ Linear evolution chains (no branching)
  ✓ Immutable historical snapshots
  ✓ Queryable temporal semantics
```

---

## Acceptance Criteria

- [ ] TemporalIntegrityConstraintsTest: 12/12 passing
- [ ] TemporalIdentityTest: 13/13 passing (regression)
- [ ] TemporalSnapshotTest: 8/8 passing (regression)
- [ ] CreateCommitteeUseCaseTest: 6/6 passing (regression)
- [ ] Migration applied without errors
- [ ] Database constraints verified in production schema
- [ ] No duplicate versions exist in any tenant
- [ ] No governance branching detected
- [ ] Documentation updated in GOVERNANCE_INVARIANTS.md

---

## What Phase A2 Does NOT Do

⏸️ Temporal window enforcement (G-005) — deferred to A3  
⏸️ Event sourcing — deferred to Phase D  
⏸️ Projections — deferred to Phase D  
⏸️ Governance access policies — deferred to Phase B  
⏸️ Transactional hardening — deferred to Phase C  

---

## Relationship to Other Phases

```
A1: Temporal Identity Foundation ✅ COMPLETE
  ↓
A2: Governance Epoch Integrity 🚧 IN PROGRESS
  ↓
A3: Effective-Time Enforcement ⏸️ (deferred)
  ↓
B: Governance Access Policies ⏸️
  ↓
C: Transactional Hardening ⏸️
  ↓
D: Durable Event Stream ⏸️
```

Each phase builds on prior (cannot skip).
