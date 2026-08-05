# F3 Architecture Strategy: Dependency Graph & Load-Bearing Decisions

**Date:** 2026-05-14  
**Phase:** Post-F3.1 Strategic Planning  
**Context:** Architectural trajectory management for F3.2 → F3.5  
**Status:** DECISION RECORD (governs all subsequent F3 implementation)

---

## Executive Summary

F3.1 established the constitutional foundation (immutable entities, lifecycle integrity, event emission, audit trail).

F3.2-F3.5 is NOT linear feature implementation. It is **architectural consolidation** with explicit load-bearing decisions.

This document maps:
- **Dependency relationships** (what blocks what)
- **Boundary placement** (where each concern lives)
- **Reversibility costs** (which decisions are expensive to change)
- **Phase scope** (explicit responsibility boundaries)
- **Anti-pattern guardrails** (what we're protecting against)

---

## 1. F3 Dependency Graph

```
F3.1 (COMPLETE)
  ├─ Immutable Entity Behavior ✓
  ├─ Aggregate Lifecycle Integrity ✓
  ├─ Event Emission ✓
  └─ Audit Traceability ✓

F3.1 ──┬──→ F3.2 (Mutation Resistance)
       │     ├─ Constructor Safety
       │     ├─ Hydration Verification
       │     └─ Integrity Assertions
       │
       └──→ F3.3 (Event Replay)
             ├─ Authoritative Event Model
             ├─ Temporal Consistency
             └─ Deterministic Reconstruction

F3.2 ──┐
       ├──→ F3.4 (Governance Policy Layer)
F3.3 ──┤     ├─ Actor Authority
       │     ├─ Institutional Jurisdiction
       │     └─ Constitutional Scope
       │
       └──→ F3.5 (Read Models / Projections)
             ├─ Query Optimization
             ├─ Dashboard Visibility
             └─ CQRS-like Separation
```

### Key Dependencies

| Phase | Depends On | Critical Decision | Blocks |
|-------|-----------|------------------|--------|
| F3.2  | F3.1      | Transition truth location | F3.4 design |
| F3.3  | F3.1      | Event semantics (fact vs. notification) | F3.4, F3.5 |
| F3.4  | F3.2, F3.3 | Policy engine vs. distributed rules | F3.5 usability |
| F3.5  | F3.3, F3.4 | Projection consistency model | Scalability |

---

## 2. Boundary Matrix: Where Each Concern Lives

This matrix clarifies **load-bearing placement decisions**.

```
┌──────────────────────────────┬────────────┬───────────┬──────────────┬──────────────┐
│ Concern                      │ Entity     │ Aggregate │ Governance   │ Infrastructure│
│                              │ (Pure PHP) │ (DDD)     │ Layer (New)  │ (Eloquent)   │
├──────────────────────────────┼────────────┼───────────┼──────────────┼──────────────┤
│ Transition Validity Rules    │   ✓ LOW    │  ✓ HIGH   │     -        │      -       │
│ (ACTIVE→SUSPENDED allowed?)  │            │           │              │              │
├──────────────────────────────┼────────────┼───────────┼──────────────┼──────────────┤
│ Lifecycle State Evolution    │   ✓        │  ✓        │     -        │      -       │
│ (episode sequencing)         │            │           │              │              │
├──────────────────────────────┼────────────┼───────────┼──────────────┼──────────────┤
│ Immutable Episode Creation   │   ✓        │  ✓ COORD  │     -        │      -       │
├──────────────────────────────┼────────────┼───────────┼──────────────┼──────────────┤
│ Domain Event Emission        │   ✓ RECORD │  ✓ OWNER  │     -        │      -       │
├──────────────────────────────┼────────────┼───────────┼──────────────┼──────────────┤
│ Event Integrity Assertion    │     -      │  ✓ EMIT   │     -        │  ✓ STORE    │
│ (one event per transition)   │            │           │              │              │
├──────────────────────────────┼────────────┼───────────┼──────────────┼──────────────┤
│ Audit Trail Persistence      │     -      │  ✓ SUPPLY │     -        │  ✓ PERSIST  │
│ (actorId, reason, timestamp) │            │           │              │              │
├──────────────────────────────┼────────────┼───────────┼──────────────┼──────────────┤
│ Actor Authority (F3.4+)      │     -      │     -     │  ✓ ENFORCE   │      -       │
│ (Can NCC suspend LCC member?)│            │           │              │              │
├──────────────────────────────┼────────────┼───────────┼──────────────┼──────────────┤
│ Institutional Jurisdiction   │     -      │     -     │  ✓ EVALUATE  │      -       │
│ (F3.4+)                      │            │           │              │              │
├──────────────────────────────┼────────────┼───────────┼──────────────┼──────────────┤
│ Replay Reconstruction (F3.3) │     -      │  ✓ SUPPLY │     -        │  ✓ SOURCE   │
├──────────────────────────────┼────────────┼───────────┼──────────────┼──────────────┤
│ Query Optimization (F3.5)    │     -      │     -     │     -        │  ✓ PROJECT  │
├──────────────────────────────┼────────────┼───────────┼──────────────┼──────────────┤
│ Dashboard Visibility (F3.5)  │     -      │     -     │  ✓ SOURCE    │  ✓ RENDER   │
└──────────────────────────────┴────────────┴───────────┴──────────────┴──────────────┘
```

### Legend

* `✓ HIGH` = primary responsibility (critical decision point)
* `✓` = shared responsibility (one layer owns, others support)
* `✓ COORD` = coordination role (orchestrates without deciding)
* `✓ OWNER` = authoritative source
* `✓ EMIT` = generates/emits
* `✓ RECORD` = captures/records
* `✓ SUPPLY` = provides data to
* `✓ PERSIST` = stores/retrieves
* `✓ ENFORCE` = checks/validates
* `✓ EVALUATE` = runs policy logic
* `✓ SOURCE` = authoritative data source
* `✓ PROJECT` = transforms for view
* `✓ RENDER` = displays
* `-` = not responsible

### Critical Insight

**F3.2 & F3.3 decisions (entity/aggregate responsibility) are LOAD-BEARING for F3.4 & F3.5.**

If transition rules are scattered between entity + aggregate, F3.4 governance layer becomes confused about where to query truth.

If event semantics are weak, F3.3 replay reconstruction will be fragile, breaking F3.5 projections.

---

## 3. Reversibility Analysis: Load-Bearing vs. Flexible Decisions

### Load-Bearing Decisions (VERY EXPENSIVE to change later)

| Decision | Current Choice | Cost to Reverse | Why It Matters |
|----------|------------------|-----------------|---|
| **Event is authoritative fact (F3.3)** | YES: events are canonical historical truth | VERY HIGH | F3.3 replay, F3.5 projections depend on this. Changing means rewriting event semantics. |
| **Aggregate owns event emission (F3.2)** | YES: lineage.recordEvent() | HIGH | If we move to service-level emission, F3.4 governance engine can't inspect aggregate state safely. |
| **Transition rules in entity + aggregate (F3.2)** | YES: dual enforcement | MEDIUM-HIGH | If we consolidate into one layer later, we lose defense-in-depth. Changing means updating both. |
| **Replay boundary: DB snapshots + hydration (F3.3)** | Current DB = source of truth, events supplement | HIGH | Full event sourcing is expensive. This hybrid model is path-dependent. |
| **Governance in separate layer (F3.4+)** | TBD: domain isolation vs. service-based | MEDIUM | If governance logic leaks into entity/aggregate, untangling later is costly. |

### Reversible Decisions (low cost to change)

| Decision | Current Choice | Cost to Reverse | Notes |
|----------|---|---|---|
| Transition contract interface (F3.2) | TBD: introduce if duplication emerges | LOW | Can add abstraction later without breaking existing code. |
| Event versioning strategy (F3.3) | TBD: decide during implementation | LOW | Can add versioning shim later without changing event structure. |
| Read model projection strategy (F3.5) | TBD: choose during F3.5 | LOW | Can switch from synchronous to async projections easily. |
| Governance policy engine structure (F3.4) | TBD: monolithic vs. micro-policies | LOW | Can refactor policy organization without changing boundaries. |

### Load-Bearing Decision Rule

> If changing this decision in F3.5 requires rewriting F3.2/F3.3, it is load-bearing and must be decided now.

---

## 4. Phase Responsibility Specification (Explicit Scope)

### F3.2 — Mutation Resistance & Contract Hardening

**Goal:** Prevent invalid state construction and ensure hydration integrity.

**Owns:**
- ✓ Entity constructor safety (reject invalid state at construction time)
- ✓ Immutability verification (transition methods return new instances)
- ✓ Hydration validation (loaded state must satisfy invariants)
- ✓ Event integrity assertions (one event per valid transition)
- ✓ Illegal reconstruction detection (bypass attempts fail loudly)

**Does NOT own:**
- ✗ Governance rules (actor authority, jurisdiction, override legality)
- ✗ Authorization logic (who can suspend whom)
- ✗ Policy orchestration (conditional transitions based on institutional rules)
- ✗ Event replay/reconstruction (that's F3.3)
- ✗ Query optimization (that's F3.5)

**Success Criteria:**
- No way to construct invalid MembershipStatus transitions outside of documented methods
- Invalid hydration fails with clear error, not silent corruption
- 100% of domain events have corresponding mutations
- Mutation resistance tests pass

---

### F3.3 — Event Integrity & Replay Foundation

**Goal:** Establish temporal consistency and prepare for deterministic reconstruction.

**Owns:**
- ✓ Event sequence verification (strict ordering, no gaps)
- ✓ Replay reconstruction (can rebuild aggregate from events)
- ✓ Deterministic hydration (same events → same state, always)
- ✓ Event versioning preparation (plan for schema evolution)
- ✓ Temporal consistency (events faithfully represent state changes)

**Does NOT own:**
- ✗ Full event sourcing (DB remains source of truth, events supplement)
- ✗ Event storage architecture (infrastructure decides that)
- ✗ Governance policy application (F3.4 owns that)
- ✗ Query models (F3.5 owns that)

**Success Criteria:**
- Aggregate can be reconstructed from event sequence + snapshot
- Replay is deterministic (100% consistent results)
- Event sequence is immutable and gapless
- No event data loss under normal operation

---

### F3.4 — Governance Policy Layer (FUTURE)

**Goal:** Introduce institutional authority and legitimacy rules.

**Will own:**
- ✓ Actor authority (can this actor perform this action)
- ✓ Institutional jurisdiction (does this institution have scope)
- ✓ Constitutional scope rules (region overrides, exception hierarchy)
- ✓ Override legality (can discipline bodies suspend their own members?)
- ✓ Governance decision logging

**Will NOT own:**
- ✗ Entity/aggregate transition rules (those live in domain)
- ✗ Event emission (that's aggregate responsibility)
- ✗ Query optimization (that's F3.5)

**Design Constraint:**
- Governance engine consults, does NOT mutate domain aggregates
- Separation of concerns: domain validity vs. institutional legitimacy

---

### F3.5 — Read Models & Projections (FUTURE)

**Goal:** Scalable, optimized views for dashboard and queries.

**Will own:**
- ✓ Event-to-read-model projection
- ✓ Query optimization and indexing
- ✓ Dashboard data materialization
- ✓ Temporal timeline views
- ✓ CQRS-like read separation

**Will NOT own:**
- ✗ Domain logic (read models reflect, don't decide)
- ✗ Governance policies (F3.4 decides)
- ✗ Event semantics (F3.3 owns that)

**Design Constraint:**
- Read models are eventually consistent but based on authoritative events
- Can be rebuilt from events at any time
- Projections are consumption, not authority

---

## 5. Anti-Pattern Guardrails: What We Protect Against

### ✗ Anti-Pattern 1: Service Explosion

**Definition:** Business logic migrates out of domain into services.

**Symptom:**
```php
// BAD: Logic in service, not entity
$service->suspendMembership($lineageId, $actorId, $reason);
  // service decides WHEN to create episode
  // service decides WHAT status to use
  // entity is just a data container
```

**Protection:**
- F3.2 enforces entity methods own transition logic
- Aggregate coordinates, service does NOT decide

**Test:** Entity transition methods must fail if called with invalid arguments.

---

### ✗ Anti-Pattern 2: Anemic Entities

**Definition:** Entities are property bags (getters/setters only).

**Symptom:**
```php
// BAD: No behavior
$episode = new CommitteeAssociation(...);
$episode->status = MembershipStatus::SUSPENDED; // Direct assignment
```

**Protection:**
- F3.2 enforces readonly properties
- Only transition methods can create new instances
- Direct property assignment is impossible

**Test:** Reflection-based property override attempt must fail.

---

### ✗ Anti-Pattern 3: Policy in Aggregates

**Definition:** Governance rules embedded in domain aggregate.

**Symptom:**
```php
// BAD: Governance in aggregate
$lineage->suspend($actorId, $reason);
  // aggregate checks: "can this actor suspend?"
  // NO! That's governance, not domain.
```

**Protection:**
- F3.2: Aggregate suspends (domain responsibility)
- F3.4: Governance engine checks authority (institutional responsibility)
- Separation enforced in architecture

**Test:** Aggregate allows any actor; governance layer validates authority.

---

### ✗ Anti-Pattern 4: Premature Event Sourcing

**Definition:** Full event sourcing before we're ready.

**Symptom:**
```php
// BAD: Trying to replay from genesis
$lineage = MembershipLineage::fromEventStream($events);
  // No snapshots, must replay all 1000 events every load
```

**Protection:**
- F3.3: Hybrid model (DB + events)
- DB remains source of truth, events supplement for audit/replay
- Full event sourcing deferred (don't prematurely optimize)

**Test:** Replay works from snapshots, not genesis events.

---

### ✗ Anti-Pattern 5: God Governance Engine

**Definition:** One monolithic policy engine deciding everything.

**Symptom:**
```php
// BAD: All governance in one class
class GovernanceEngine {
  public function canSuspend() { ... }
  public function canRestore() { ... }
  public function canTerminate() { ... }
  public function canReapply() { ... }
  // etc... 500 lines
}
```

**Protection:**
- F3.4: Multiple policy interfaces (one per concern)
- Engine coordinates, policies are distributed
- Composition over monolith

**Test:** Each governance concern has isolated testable policy.

---

### ✗ Anti-Pattern 6: Silent Invalid State

**Definition:** Invalid state created without error or event.

**Symptom:**
```php
// BAD: No guard, no event
$lineage->restore(...) // called on TERMINATED
  // Returns null or FALSE silently?
  // No error, no event, caller doesn't know
```

**Protection:**
- F3.2: Exceptions on invalid transitions
- F3.3: Events are emitted or fail loudly
- No silent failures

**Test:** Every invalid transition throws specific exception.

---

### ✗ Anti-Pattern 7: Event as Side-Effect

**Definition:** Events are optional notifications, not authoritative facts.

**Symptom:**
```php
// BAD: Event might not exist
$lineage->suspend(...);
// Did event emit? Was it stored? Unknown.
// F3.5 projections inconsistent.
```

**Protection:**
- F3.3: Events are canonical, not optional
- Emission happens inside aggregate, not outside
- Failure to emit = failure to transition

**Test:** Event existence verified for every transition.

---

## 6. Guardrail Enforcement Strategy

### Architecture Guards (Prevent at Construction Time)

| Anti-Pattern | Guard | Mechanism |
|--------------|-------|-----------|
| Service explosion | Entity methods are primary | Only aggregate methods exposed to handlers |
| Anemic entities | Readonly class + transition methods | Immutability enforced by PHP |
| Policy in aggregates | Separate governance layer | F3.4 layer created later, isolated |
| Premature event sourcing | Hybrid DB + events model | Events supplement, don't replace DB |
| God governance engine | Multiple policy interfaces | Each policy is separate, composed |

### Test Guards (Prevent at Test Time)

| Anti-Pattern | Test | Location |
|--------------|------|----------|
| Silent invalid state | F3.2: mutation resistance suite | tests/Unit/Constitutional/MutationResistance/ |
| Events as side-effect | F3.3: event integrity suite | tests/Unit/Constitutional/EventIntegrity/ |
| Direct property assignment | F3.2: reflection-based override test | tests/Unit/Constitutional/MutationResistance/ |

---

## 7. Decision Log: Explicit Trade-Offs

### Decision 1: Entity Owns Transition Methods (Decided: F3.1)

**Choice:** Entity.suspend(), restore(), terminate() with state guards

**Alternative:** Aggregate-only transitions (no entity methods)

**Trade-Off:**
| Aspect | Entity Methods | Aggregate-Only |
|--------|---|---|
| Code clarity | Higher (method on object) | Lower (distant operation) |
| Reusability | Better (entity can be tested alone) | Worse (depends on aggregate) |
| Defensive layers | Better (2 layers check) | Weaker (1 layer) |

**Decision:** Entity methods. Defense-in-depth outweighs complexity.

---

### Decision 2: Events Are Authoritative Facts (Decided: Pre-F3.2)

**Choice:** Events are canonical historical truth, not side-effect notifications

**Alternative:** Events as optional logging/audit

**Trade-Off:**
| Aspect | Authoritative | Optional |
|--------|---|---|
| F3.3 replay reliability | Guarantees deterministic | Uncertain |
| F3.5 projection consistency | Strong guarantees | Eventually consistent risk |
| Implementation complexity | Moderate | Low |

**Decision:** Authoritative. F3.3/F3.5 depend on this.

---

### Decision 3: Hybrid Replay Model (Decided: F3.3 Era)

**Choice:** DB snapshots + events (not full event sourcing)

**Alternative:** Full event sourcing from genesis

**Trade-Off:**
| Aspect | Hybrid | Full ES |
|--------|---|---|
| Load time | Fast (snapshot + small event tail) | Slow (replay all events) |
| Complexity | Moderate | High |
| When to apply | Now (don't over-engineer) | Later (if scale demands) |

**Decision:** Hybrid. Path dependency: can upgrade to full ES later if needed.

---

### Decision 4: Governance in Separate Layer (Decided: F3.4 Planning)

**Choice:** F3.4 governance layer separate from F3.1-F3.3 domain logic

**Alternative:** Governance rules in entity/aggregate

**Trade-Off:**
| Aspect | Separate Layer | In Domain |
|--------|---|---|
| Testability | Each concerns isolated | Intertwined |
| Reusability | Governance logic portable | Tied to domain |
| Complexity | More layers (coordination) | Simpler upfront |

**Decision:** Separate layer. Prevents policy-in-domain anti-pattern.

---

## 8. Next Steps: F3.2 Implementation Plan

### Phase A ✅ COMPLETE

- [x] Strategic F3 architecture mapping
- [x] Boundary clarification
- [x] Load-bearing decision review
- [x] Anti-pattern guardrails

### Phase B (Next Session)

- [ ] F3.2 RED test suite design
- [ ] Mutation resistance attack vectors
- [ ] Immutability guarantee tests
- [ ] Event integrity assertions

### Execution Order

```
F3 Strategy (THIS DOCUMENT)
        ↓
F3.2 RED Suite Design
        ↓
F3.2 Implementation (TDD)
        ↓
F3.3 Planning
        ↓
... repeat for F3.3, F3.4, F3.5
```

---

## 9. References & Traceability

**Upstream Decisions:**
- F3.1 (COMPLETE): Constitutional hardening — entity behavior + audit + events
- F2 (COMPLETE): Real application repositories + geo path providers
- F1 (COMPLETE): UI integration

**Load-Bearing on:**
- F3.2: Mutation resistance test strategy
- F3.3: Event replay model and versioning
- F3.4: Governance policy engine design
- F3.5: Projection consistency guarantees

**Related Documents:**
- `./20260514_1710_phase_f.md` — F3 phase definitions and goals
- `./PHASE_F1_F2_DEVELOPER_GUIDE.md` — F1/F2 implementation guide

---

## Approval & Sign-Off

**Strategic Decision Owner:** Senior Architect  
**Date:** 2026-05-14  
**Status:** APPROVED FOR F3.2 IMPLEMENTATION  

**Checkpoint:** Before implementing F3.2, confirm:
- [ ] All 5 artifacts reviewed
- [ ] Load-bearing decisions understood
- [ ] Boundary placement accepted
- [ ] Anti-pattern guardrails committed to
- [ ] F3.2 scope is clear and bounded

**Next:** Proceed to F3.2 RED test suite design.

---

**Generated:** 2026-05-14 | **Version:** 1.0 | **Status:** DECISION RECORD
