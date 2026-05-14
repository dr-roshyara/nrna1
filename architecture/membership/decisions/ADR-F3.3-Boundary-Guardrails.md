# ADR-F3.3-Boundary-Guardrails: Event Semantics & Reconstruction Isolation

**Status:** ACCEPTED (F3.3 implementation deferred; boundaries documented now)  
**Date:** 2026-05-15  
**Scope:** Architectural contract to prevent coupling as UI and services evolve in parallel  

---

## 1. Decision

Three reconstruction paths MUST remain semantically isolated as the system grows:

| Path | Owner | Semantics | Immutable Property |
|------|-------|-----------|-------------------|
| **create()** | Domain command | New relationship establishment | Emits MembershipEstablished |
| **reconstitute()** | Persistence layer | DB snapshot reconstruction | Validates reconstructed chain |
| **replay()** [F3.3] | Event stream | Historical deterministic folding | Emits NOTHING |

Each path has a single responsibility. They must never blur together.

---

## 2. Hard Boundaries (Non-Negotiable)

### Boundary A: Policy → Decision-time only

```
MembershipTransitionPolicy::canTransition()
MembershipTransitionPolicy::assertAuditRequirementsMet()
```

**MUST be called:**
- When domain actions execute (suspend, restore, terminate)
- When application services validate user actions

**MUST NOT be called:**
- Inside replay() or any fold function
- Inside read model adapters
- Inside UI logic

**Why:** Policy approves *decisions*. Replay reconstructs *facts*. Facts don't need approval — they already happened.

---

### Boundary B: Replay → Pure fold only

```
MembershipLineage::replay()
```

**Is allowed:**
- Deterministic state transformation
- Call `CommitteeAssociation::rehydrate()` (structural validation only)
- Emit absolutely NOTHING

**Is forbidden:**
- Any call to `MembershipTransitionPolicy`
- Any `recordEvent()` (would create infinite loop)
- Any side effect (DB, cache, external service)
- Any mutable reference escape

**Why:** Replay is reconstruction, not execution. If it produces side effects, it's no longer deterministic.

---

### Boundary C: Read model → Never domain aggregate

Read models read domain state but never invoke domain methods:

```
// ✅ CORRECT
$lineage = $repository->find($lineageId);
$status = $lineage->currentStatus();  // query only
return CommitteeMembershipReadModelAdapter::adapt($lineage);

// ❌ WRONG
$lineage = $repository->find($lineageId);
$lineage->suspend($actorId, $reason, $now);  // domain mutation in read context
```

**Why:** Read models are reporting layer. Domain mutations are business layer. They have different concurrency and consistency models.

---

### Boundary D: UI → Read model only, never aggregate

UI consumes read model projections, not domain aggregates:

```
// ✅ CORRECT
CommitteeReadModel::find($committeeId)
    ->members()  // pre-computed view
    ->map(fn($member) => ['name' => $member->name, 'status' => $member->status])

// ❌ WRONG
$aggregate = $repository->findAggregate($committeeId);
$lineage = $aggregateRoot->episodes();  // accessing domain internals from UI
return $lineage->map(...);
```

**Why:** UI layer and domain layer have incompatible dependencies and change rates. Coupling them creates unnecessary breakage.

---

## 3. Forbidden Crossings (What breaks immediately if violated)

| Violation | Detection | Consequence |
|-----------|-----------|------------|
| Policy inside replay | Code review + test failure | Replay becomes non-deterministic |
| Event emission inside fold | Runtime assertion (double-check guard) | Events loop infinitely |
| Domain mutation inside read model | Code review + test isolation | Data corruption (different reader sees different state) |
| Aggregate reference in UI | Code review + dependency injection | UI tightly coupled to domain; breaks when domain changes |
| UI directly calling policy | Code review + test isolation | Policy logic duplicated in view layer |

---

## 4. Integration Rule: Read Model Adapter Boundary

This is the **single contract point** between domain and UI:

```
Domain Truth
    ↓
MembershipLineage::episodes() [read-only query]
    ↓
CommitteeMembershipReadModelAdapter::adapt()
    ↓
DTO / View Model (status, name, timestamps)
    ↓
UI component (renders badges, lists)
```

### Contract:
1. **Domain**: Exposes `currentStatus()`, `isActive()`, `isSuspended()`, `isTerminated()` read-only queries
2. **Adapter**: Converts domain state → flat DTO (single read operation, no caching needed yet)
3. **UI**: Renders the DTO, never references aggregate directly

### Not allowed to pass through adapter:
- The lineage object itself
- Episode objects
- Any domain reference (except IDs for linkage)

---

## 5. Why This Exists

**Without clear boundaries:**

* Policy logic creeps into replay (performance optimization → correctness bug)
* Read model queries trigger side effects (cache invalidation chains → silent data corruption)
* UI directly invokes domain policy (UI framework migration becomes domain refactoring)
* Each layer duplicates validation (policy in UI, policy in domain, policy in read model)

**With clear boundaries:**

* F3.3 event sourcing can be added without touching UI
* Committee, Elections, and Governance contexts can evolve independently
* Read model can be caching, eventual consistency, or derived from events — doesn't matter
* UI layer can be rewritten without touching domain

---

## Verification

**For each new feature:**

1. **Is policy being called outside domain/application?** → Refactor into command handler
2. **Is replay emitting events?** → Remove `recordEvent()` call immediately
3. **Is read model calling domain mutation methods?** → Replace with query method, move to adapter
4. **Is UI accessing aggregate properties?** → Introduce adapter DTO, pass only DTO to view

---

## Status

**Implemented:** Boundary structure exists (F3.2 guarantees this)  
**Documented:** This document  
**Enforced:** Code review process + test isolation  
**Next:** F3.3 implementation respects these boundaries; UI integration follows adapter contract

