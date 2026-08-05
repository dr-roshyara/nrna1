# Governance Architecture: Temporal Institution Engine

**Status:** Phase C.1 Complete | Phase B Preparation  
**Date:** 2026-05-08  
**Classification:** Architectural Foundation Document

---

## Core Achievement: Governance Epochs as First-Class Citizens

This system is no longer a CRUD governance module with auditing bolted on.

It is a **deterministic institutional timeline engine** with:
- Immutable epoch lineage (structural invariant)
- Transactional epoch capture (G-007, G-008, G-009)
- Archaeology-safe persistence (no FK constraints)
- Anti-branching guarantees (single linear evolution)
- Explicit temporal semantics (snapshot_* prefixing)
- Structurally enforced transaction boundaries (decorator pattern)

This is real temporal architecture.

---

## Architectural Decisions Locked In

### 1. ✅ Temporal Identity Separation

**Decision:** Separate mutable operational state from immutable historical origin

```text
created_from_structure_id  → immutable historical governance that created this committee
structure_id               → (reserved for future operational governance reference)
```

**Why This Matters:**
Without this separation, future developers inevitably mutate historical meaning accidentally.
The rename enforces a hard semantic boundary.

**Never Revert:** This is foundational. Reversing it would corrupt temporal identity.

---

### 2. ✅ Snapshot Field Prefixing

**Decision:** All historical snapshot fields must be prefixed with `snapshot_*`

```text
snapshot_level_name
snapshot_level_code
snapshot_geo_policy
snapshot_geo_scope
snapshot_structure_version
```

**Why This Matters:**
Prefix communicates: "This is frozen historical evidence, not live governance state."
Without it, developers ask: "Why do we duplicate data?" and accidentally couple to outdated versions.

**Never Revert:** Removing prefixes creates immediate temporal ambiguity.

---

### 3. ✅ Level Code Preservation

**Decision:** Store both `snapshot_level_code` (canonical identity) and `snapshot_level_name` (display label)

**Why This Matters:**
Names are presentation. Codes are identity.

Without code, archaeology becomes linguistically unstable:

| Version | Code         | Name (v2)                | Name (v3)                |
| ------- | ------------ | ------------------------ | ------------------------ |
| v2      | CENTRAL_EXEC | Central Committee        | *same*                   |
| v3      | CENTRAL_EXEC | *same*                   | National Executive Cmte  |

If only names persisted, lineage becomes semantically ambiguous.
Now canonical governance identity is preserved independently of presentation evolution.

**Never Revert:** This enables deterministic archaeology.

---

### 4. ✅ No Foreign Key Constraints on Snapshots

**Decision:** Intentionally avoid FK constraints for `created_from_structure_id` → `committee_structures`

**ADR:** Governance snapshots intentionally avoid foreign key enforcement

**Rationale:**
- Archaeological survivability (destroyed governance doesn't erase history)
- Governance archival safety (old epochs can be moved/cleaned independently)
- Historical independence (committees retain truth even if origin structure is removed)
- Replay resilience (archaeology doesn't depend on current governance schema)

**Enforcement:**
- No FK constraint in schema
- Soft reference with canonical identity
- Explicit comment: "NO FOREIGN KEY by design"

**Never Revert:** Adding FK constraints later would break temporal archaeology.

---

### 5. ✅ Transactional Decorator Pattern

**Decision:** All governance mutation use cases must be wrapped in transaction boundary decorators

```text
CreateCommitteeUseCase interface
  → TransactionalCreateCommittee decorator (DB::transaction)
  → InternalCreateCommittee implementation

ActivateCommitteeStructureUseCase interface
  → TransactionalActivateCommitteeStructure decorator (DB::transaction)
  → ActivateCommitteeStructure implementation
```

**Why This Matters:**
Safety becomes structural, not developer discipline.
Container DI makes it structurally impossible to bypass the transaction boundary.

**Never Revert:** Removing decorators reintroduces race conditions between lock acquisition and commit.

---

## Explicit Invariants (G-001 through G-009)

### Phase A1: Temporal Lineage
- **G-001**: Governance structures form a single linear chain (no branching)
- **G-002**: Version numbers increment monotonically
- **G-003**: Parent-child relationships are immutable once established

### Phase A2: Epoch Status Enforcement
- **G-004**: Only ACTIVE structures can evolve
- **G-005**: Parent automatically becomes DEPRECATED when child activates
- **G-006**: Only one ACTIVE structure per tenant at any time

### Phase C: Transactional Hardening
- **G-007**: Governance epoch snapshot must be transactionally stable (locked until commit)
- **G-008**: Committee creation must be atomic (all-or-nothing with full rollback)
- **G-009**: Lock acquisition follows global ordering (prevents deadlocks)

---

## Remaining Architectural Risks

### Risk 1: Snapshot Payload Explosion ⚠️

**Current State:**
```php
snapshot_level_name
snapshot_level_code
snapshot_geo_policy
snapshot_geo_scope
snapshot_structure_version
snapshot_level_index
snapshot_taken_at
```

**Future Pressure:**
Will inevitably grow to include:
- quorum rules
- voting policies
- role mappings
- eligibility constraints
- regional formulas

**Mitigation:**
Eventually introduce `GovernanceSnapshot` Value Object:

```php
final readonly class GovernanceSnapshot {
    public function __construct(
        public string $structureId,
        public int $version,
        public string $levelCode,
        public string $levelName,
        public GeoPolicy $geoPolicy,
        public ?GeoScope $geoScope,
        public DateTimeImmutable $capturedAt,
    ) {}
}
```

**Timeline:** Not urgent now. Introduce after Phase B completes.

---

### Risk 2: Metadata Axis Mixing Still Unresolved ⚠️

**Current State:** Temporal, workflow, and operational metadata remain mixed in aggregates

| Concern     | Example           | Axis         |
| ----------- | ----------------- | ------------ |
| temporal    | effective_from    | time domain  |
| workflow    | activated_by      | approval     |
| operational | activation_reason | audit trail  |

**Eventual Solution:**
Split into separate bounded contexts:

| Aggregate             | Responsibility          |
| -------------------- | ----------------------- |
| GovernanceEpoch       | temporal lineage        |
| GovernanceWorkflow    | approval lifecycle      |
| GovernanceAudit       | operational traceability |

**Timeline:** Phase B preparation should formalize this split.

---

### Risk 3: Lock Ordering Not Yet Formalized ⚠️

**Current State:** Pessimistic locking implemented correctly, but ordering rules not documented.

**Future Pressure:**
As system grows (committee creation, governance activation, membership assignment, elections):
- Multiple use cases will compete for governance locks
- Without global ordering, deadlocks reappear despite current safety

**Required ADR:**
```text
ADR-00X: Governance Lock Acquisition Global Ordering

Mandatory lock acquisition order (all use cases):
1. organisation (if governance status checked)
2. active committee_structures row (FOR UPDATE)
3. committee row (if modifying existing)
4. committee_assignments (if modifying)
5. election aggregates (if modifying)

Violation of this order is a deadlock risk.
```

**Timeline:** Define before Phase B governance policies scale.

---

### Risk 4: Effective-Time Enforcement Deferred ⚠️

**Current State:** Version lineage enforced. Temporal activation windows not enforced.

**Gap:** Governance epochs are structurally safe but not temporally executable.

**Why Deferred Correctly:**
Phase C (transactional consistency) must complete before A3 (effective-time enforcement).
You cannot enforce time-based rules without atomic transactions first.

**Timeline:** After Phase B completes.

---

### Risk 5: Archaeology Query Layer Missing ⚠️

**Current State:** Indexes added. Archaeology is repository-level only.

**Future State Should Include:**
```php
class GovernanceArchaeologyQuery {
    public function findCommitteesCreatedUnder(CommitteeStructureId $id): array
    public function getGovernanceTimeline(TenantId $tenantId): array
    public function reconstructHistoricalContext(CommitteeId $id): GovernanceContext
}
```

**Why:** Archaeology becomes a business capability, not infrastructure detail.

**Timeline:** Introduce alongside Phase B governance policies.

---

## Critical Test Architecture Achievement

### What Changed
Tests now model **actual epoch mechanics** instead of abstract object mutation.

**Before:**
```php
// Abstract test - validates impossible state
$structure->evolve();
assert($structure->version === 2); // But parent still ACTIVE!
```

**After:**
```php
// Real test - validates actual epoch mechanics
$v1->evolve(); // parent auto-marked DEPRECATED
persist($v1);  // persistence order matters
$v2->activate();
persist($v2);  // now v2 is ACTIVE
```

**Impact:** Tests dramatically increase long-term reliability because they validate possible states, not fantasies.

---

## Architecture Maturity Checklist

| Dimension | Status | Evidence |
|-----------|--------|----------|
| Temporal identity | ✅ Mature | created_from_structure_id separation |
| Snapshot semantics | ✅ Mature | snapshot_* prefixing + level_code |
| Transaction safety | ✅ Mature | G-007, G-008, G-009 + decorator enforcement |
| Schema durability | ✅ Mature | Comments + intentional no-FK + indexes |
| Archaeological survivability | ✅ Mature | No foreign keys + canonical codes |
| Lock ordering | ⚠️ Documented, not enforced | Needs ADR + validation |
| Effective-time enforcement | ⏳ Deferred | Planned for Phase A3 |
| Governance capability policies | ⏳ Deferred | Planned for Phase B |
| Snapshot payload management | ⏳ Deferred | GovernanceSnapshot VO for Phase B+ |
| Metadata axis separation | ⏳ Deferred | Needs formalization before scaling |

---

## Phase Prioritization (Locked)

### Completed
1. ✅ **Phase A1**: Temporal lineage (G-001 through G-003)
2. ✅ **Phase A2**: Epoch status enforcement (G-004 through G-006)
3. ✅ **Phase C**: Transactional hardening (G-007 through G-009)
4. ✅ **Phase C.1**: Snapshot semantics (semantic durability)

### Next
5. **Phase B**: Governance access policies (WHO can CREATE/ACTIVATE/EVOLVE)
   - Introduce GovernanceCapabilityPolicy
   - Formalize governance access enforcement
   - Metadata axis split preparation

### Then
6. **Phase A3**: Effective-time enforcement (WHEN epochs become active)
   - Temporal window enforcement
   - Epoch activation validation
   - Time-based accessibility rules

### Future
7. **Phase D**: Durable event streams (HOW governance events propagate)
8. **Phase E**: Archaeology query services (WHERE to read historical truth)

---

## Cultural Imperatives

### 1. Snapshot Fields Are Institutional Facts, Not Metadata

These are not convenience columns.

```text
snapshot_level_name
snapshot_level_code
snapshot_geo_policy
snapshot_structure_version
```

Are:
```text
institutional historical facts
```

That mindset changes how developers treat them.

Treat mutations of snapshot fields as data corruption, not updates.

---

### 2. Temporal Semantics Are First-Class Language

When discussing governance:

Use:
```text
"This committee was created under structure v2"
"The governance evolved to v3"
"The epoch became ACTIVE at timestamp X"
```

Instead of:
```text
"This committee has a structure reference"
"The structure changed"
"The status flag is set"
```

Temporal language forces correct mental models.

---

### 3. Lock Ordering Is Infrastructure Law, Not Convention

Once you scale beyond committee creation:

```text
Lock acquisition order is NOT a suggestion.
It is a structural invariant.
```

Violation causes deadlocks.
Deadlocks cause cascading failures.

Document it. Enforce it. Test it.

---

## References

- **Phase A1 Plan**: Temporal lineage foundations
- **Phase A2 Plan**: Epoch status enforcement
- **Phase C Plan**: Transactional hardening
- **Invariants G-001 through G-009**: Governance consistency guarantees
- **ADR: Governance snapshots avoid FK constraints**: Archaeological survivability rationale
- **Lock Ordering Rule (G-009)**: Global acquisition order specification

---

## Next Review Point

After Phase B completion, reassess:
- Lock ordering enforcement (test coverage)
- Metadata axis separation (bounded context split)
- Snapshot payload growth (GovernanceSnapshot VO introduction)
- Archaeology query patterns (usage analysis)

Timeline: 2026-06-30

---

**Authored:** 2026-05-08  
**Reviewed:** [pending Phase B completion]  
**Status:** Foundation Document — Locked for Phase A/C
