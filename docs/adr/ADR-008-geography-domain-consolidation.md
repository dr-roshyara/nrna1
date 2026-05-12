# ADR-008: Geography Domain Consolidation

**Status:** Accepted  
**Date:** 2026-05-11  
**Deciders:** Architecture Review, Project Lead  
**Phase:** 8A — Geography Context Foundation  

---

## Context

The Geography context is in a mid-migration DDD state: aggregates, value objects, repository interfaces, and an anti-corruption layer all exist, but the production flow bypasses them. Eloquent dominates runtime paths, CQRS boundaries are absent, DTO contracts are missing, and authorization is not formalized.

This ADR documents the architectural decisions for consolidating the Geography context into a governed domain platform.

---

## Decision 1: Aggregate Boundary

**Each GeoAdministrativeUnit is an aggregate root.** Hierarchy consistency is enforced via domain services, not aggregate scope.

**Rationale:**
- The entire country tree as a single aggregate would be gigantically impractical with impossible concurrency
- Each unit has independent lifecycle (create, update, activate, deactivate)
- Cross-unit invariants (cycle prevention, level ordering) are algorithmic and span aggregates — they belong in domain services
- This aligns with the existing `GeoAdministrativeUnit` entity design

**Implications:**
- Repository operates on single units, not trees
- Subtree mutations (moves) require coordinated multi-aggregate transactions via a domain service
- The `GeographyHierarchyDomainService` owns cross-aggregate rules (cycle detection, level compatibility)
- Transaction boundary = single geo unit mutation

---

## Decision 2: CQRS Strategy

**Explicit Command/Query separation but within the same process and same database.** This is "CQRS Light" — not full event sourcing.

**Commands:** Create, Update, Delete, Import, Move  
**Queries:** GetTree, Search, GetChildren, GetAncestors  

**Rationale:**
- Matches the established Governance context pattern
- Commands operate on the aggregate through the repository (write model)
- Queries use projections (read model) for performance
- Same database avoids eventual consistency complexity at this stage

---

## Decision 3: Projection Consistency Model

**Type:** Transactionally synchronous — projections updated within the same database transaction as the aggregate write.

**Guarantees:**
- Projections are updated within the same DB transaction as the aggregate mutation
- Reads are strongly consistent after commit
- `rebuildAll()` is the authoritative recovery mechanism
- All projector handlers must be idempotent
- Projection failure aborts the entire aggregate transaction

**Rationale:**
- Hierarchy projections are read-critical for committee jurisdiction lookups
- Stale reads in geography would cause governance errors
- Synchronous is acceptable at current scale; async can be introduced when profiling proves it necessary

---

## Decision 4: Transaction Policy

**Command handlers own the transaction boundary.**

Rules:
- Aggregate save + event persistence + projection updates are atomic (single DB transaction)
- Query handlers never open transactions
- Nested transactions are forbidden
- The handler wraps the entire operation in `DB::transaction()`

---

## Decision 5: Domain Event Model

**Semantic events, not generic ones.** Each event name captures the business meaning of the change.

Events:
- `GeoUnitCreated` — new unit added
- `GeoUnitRenamed` — name changed
- `GeoUnitMoved` — parent changed (subtree relocation)
- `GeoUnitActivated` — unit re-enabled
- `GeoUnitDeactivated` — unit disabled
- `GeoUnitValidityChanged` — temporal range modified

**Rationale:**
- Semantic events enable precise projector handlers
- Improve auditability — each event tells a business story
- Future event sourcing migration is simpler with semantic events

---

## Decision 6: Concurrency Strategy

**Optimistic locking via an aggregate version field.**

- Each aggregate has a `version` integer that increments on every write
- Writes include `WHERE version = :expected_version`
- On version mismatch: throw `ConcurrencyException`
- Projection rebuilds are serialized per root tree

**Rationale:**
- Hierarchy systems are concurrency-sensitive (two admins moving the same subtree)
- Optimistic locking is sufficient for admin-paced operations
- Pessimistic locking would introduce unnecessary contention

---

## Decision 7: Anti-Corruption Layer Exit Strategy

**Phased migration, not a big bang.**

- **Phase 8A:** Legacy services remain callable; new CQRS paths added alongside
- **Phase 8B:** All writes routed through aggregate; legacy mutation methods deprecated
- **Phase 8C:** Remove legacy Eloquent mutation paths entirely
- **Final State:** ACL becomes a read-only compatibility layer for external consumers

The `GeographyAntiCorruptionLayer` will be updated to delegate to CQRS handlers for writes while still exposing the legacy interface for backward compatibility.

---

## Decision 8: Cross-Context Constraints (Geography ↔ Governance)

Geography is not isolated. Committee hierarchy depends on geography hierarchy.

Rules enforced at the application layer:
1. **GEO-GOV-01:** A geo unit with active committee bindings cannot be deleted
2. **GEO-GOV-02:** Moving a geo unit triggers committee jurisdiction validation
3. **GEO-GOV-03:** Committee level must be compatible with geo level
4. **GEO-GOV-04:** Committee jurisdiction must remain within geo subtree

These constraints are enforced via application services that coordinate between contexts, not within the domain layer (to avoid cross-context coupling).

---

## Decision 9: Subtree Move Complexity

**Elevated to dedicated sub-phase.** Moving a subtree affects ancestor_path, descendants, depth, cached hierarchy, validity ranges, authority propagation, committee bindings, projections, and cycle detection.

This will be implemented as a `GeoHierarchyMoveService` with its own test suite and dedicated migration step, not as a simple `UpdateGeoUnit` handler.

---

## Decision 10: Read/Write Scalability Intent

**Read Model Strategy:** Optimized for hierarchy traversal, autocomplete, subtree expansion, and committee lookup by geography. Projections serve these reads.

**Write Model Strategy:** Optimized for consistency over throughput. All writes pass through the aggregate for invariant enforcement.

---

## Consequences

**Positive:**
- Formalized aggregate boundaries prevent future misuse
- Transaction policy prevents partial updates
- Projection consistency model prevents stale read risks
- Cross-context constraints protect governance integrity
- ADR serves as authoritative reference for future developers

**Negative:**
- Synchronous projections add write latency (acceptable at current scale)
- Optimistic locking adds retry logic complexity (edge case)
- Phased ACL migration requires maintaining duplicate code paths temporarily

**Risks:**
- Subtree move complexity is the highest-risk operation — mitigated by dedicated phase and property-based tests
- Cross-context constraints add coupling — mitigated by enforcing at application layer, not domain layer
