# ADR: Dual Committee Creation Paths — Deprecation Strategy

**Date:** 2026-05-13  
**Status:** ACCEPTED  
**Type:** Architecture Decision  
**Affects:** Committee formation, governance validation, event sourcing

---

## Context

### The Two Implementations

The codebase has two competing committee creation implementations that evolved from different architectural eras:

**Legacy Path (Currently Active)**
```
CommitteeManagementController::store()
  ↓
CreateCommitteeUseCase (interface)
  ↓
TransactionalCreateCommittee (wrapper)
  ↓
InternalCreateCommittee (implementation)
  ↓
Committee aggregate (1100+ lines, operational model)
```

**New Canonical Path (Built, Not Wired)**
```
CreateCommitteeHandler (ready for HTTP binding)
  ↓
ConstitutionalCommittee aggregate (event-sourced, constitutional model)
  ↓
GovernancePolicy::assertAllowed() (matrix-aware validation)
  ↓
CommitteeEstablished event (published via EventBusPort)
```

### Key Differences

| Aspect | Legacy | New |
|--------|--------|-----|
| **Command** | `InternalCreateCommitteeCommand` (8 fields, operational) | `CreateCommitteeCommand` (2 fields, structural) |
| **Validation** | `CommitteeCreationPolicy` (structure-based rules) | `GovernancePolicy` (2D matrix-based rules) |
| **Aggregate** | `Committee` (operational state machine) | `ConstitutionalCommittee` (constitutional, event-sourced) |
| **Events** | None (implicit) | `CommitteeEstablished` (explicit domain event) |
| **Input Data** | Structure lookup, geographic reference parsing | Pre-validated GovernanceAssignment |
| **Tests** | 3 failing (pre-existing) | 6 passing |
| **HTTP Entry** | ✅ Wired via CommitteeManagementController | ❌ Not wired to any controller |

### Why Both Exist

1. **Legacy**: Handles operational committee management with committee structure definitions, geographic hierarchies, and category derivation — features the new handler doesn't yet support.

2. **New**: Implements the canonical geo×governance matrix model (Phases 1-3 complete, all tests passing) — a simpler, more composable design focused on constitutional governance rules.

### Problem: Inconsistent Validation

- **Legacy path** validates against `CommitteeStructure` definitions in the database
- **New path** validates against `GovernanceMatrix` (read-only projection of `governance_level_definitions`)

If both paths are used simultaneously:
- Same governance validation rules could be enforced differently
- One might accept what the other rejects
- Geo unit filtering could diverge

---

## Decision

**Deprecate the legacy path. Keep both active during transition. Wire new handler only when consumers are ready to migrate.**

### Rationale

1. **Maintains Stability**: Legacy path continues serving existing features (structure definitions, category derivation) without disruption.

2. **De-risks Migration**: Gives time to:
   - Understand feature dependencies
   - Build equivalent capabilities in new path if needed
   - Test new handler with real election workflows
   - Migrate one consumer at a time

3. **Prevents Dual Maintenance**: Rather than maintaining both indefinitely, set a clear deprecation deadline and migration path.

4. **Validates New Design**: Running new handler in parallel (via new HTTP endpoint) proves it works before removing legacy.

### Deprecation Marker

```php
/**
 * @deprecated Use CreateCommitteeHandler in UseCases\CreateCommittee instead.
 * This legacy class will be removed after all consumers are migrated to the new
 * CreateCommitteeHandler which uses GovernancePolicy and ConstitutionalCommittee.
 * The new handler is matrix-aware and event-sourced.
 *
 * Migration Timeline:
 * - Phase 1 (Now): Mark deprecated, keep both paths active
 * - Phase 2 (v2.0): Wire new handler to HTTP, test with real users
 * - Phase 3 (v2.1): Migrate first consumer to new path
 * - Phase 4 (v3.0): Remove legacy CreateCommittee class
 */
final class CreateCommittee
```

---

## Implementation Plan

### Phase 1 (NOW): Parallel Operation

**Status:** CURRENT  
**Timeline:** This sprint

1. ✅ Mark legacy `CreateCommittee` as `@deprecated`
2. ✅ Add `GeoUnitController::store()` to populate reference data
3. ❌ **TODO:** Wire new `CreateCommitteeHandler` to a new HTTP route
4. ❌ **TODO:** Document feature parity matrix (what new path needs to support)

**Outcome:** Both paths working, new path testable but not exposed to users.

### Phase 2: Feature Parity Assessment

**Timeline:** End of sprint / next sprint

Analyze legacy `InternalCreateCommittee` to identify features the new handler must support:

```
✅ Already supported by new path:
   - Committee name + governance assignment
   - Policy validation (matrix-based)
   - Event publication
   - Transaction boundaries

❌ May need in new path:
   - Committee category derivation (explicit type vs. geo-derived)
   - Regional/geographic structure lookup
   - Committee code generation strategy
   - Additional governance context snapshots
```

**Decision Point:** If new path can support all features → proceed to Phase 3. If major gaps → extend new path or add adapter layer.

### Phase 3: Gradual Migration

**Timeline:** Sprint 2-3

Create an adapter that lets `CommitteeManagementController` route to new handler:

```php
// Controller stays same, but can route to new path
class CommitteeManagementController {
    public function store(Organisation $organisation) {
        $command = $this->requestToCommand($organisation); // adapts request
        
        // Use new handler for new orgs, legacy for existing
        if ($organisation->uses_governance_matrix) {
            $id = app(CreateCommitteeHandler::class)->handle($command);
        } else {
            $id = app(CreateCommitteeUseCase::class)->execute($command);
        }
        // ...
    }
}
```

**Milestones:**
- v2.0: New handler exposed via separate `/api/committees/create-canonical` endpoint
- v2.1: CommitteeManagementController can route to both paths (feature flag or org setting)
- v3.0: Legacy path removed entirely

### Phase 4: Legacy Removal

**Timeline:** Major version (v3.0)

After all consumers migrated:
1. Delete `CreateCommittee.php` (legacy)
2. Delete `InternalCreateCommittee.php` (legacy wrapper)
3. Delete `TransactionalCreateCommittee.php` (replaced by handler's transactional concerns)
4. Simplify controller to use only new handler

**Communication:** 
- Deprecation warnings in v2.0
- Removal notes in v3.0 CHANGELOG
- Migration guide in docs

---

## Consequences

### Positive

1. **Clear Deprecation Path**: Future developers know legacy path is temporary
2. **No Disruption**: Existing features continue working while new path matures
3. **Testability**: New handler can be tested in isolation before full deployment
4. **Learning Opportunity**: Running both paths side-by-side reveals gaps/differences
5. **Event Sourcing Foundation**: New path provides audit trail via explicit events

### Negative

1. **Dual Maintenance**: Two paths require attention during transition period
2. **Potential Divergence**: Validation rules could inconsistently apply if both paths are used in same organisation
3. **Code Duplication**: Some logic may need to exist in both paths temporarily
4. **Cognitive Load**: Developers must understand both implementations

### Mitigation

- Document feature parity matrix explicitly
- Use feature flags to prevent dual-path usage in same org
- Set hard deadlines for Phase 3 and Phase 4
- Create migration checklist for consumers

---

## Alternatives Considered

### Alternative 1: Immediate Replacement
**Abandon legacy path, wire new handler immediately.**

**Rejected because:**
- New handler lacks features legacy path provides (category derivation, structure context)
- No time to assess full feature parity
- Risk of breaking existing election workflows
- No safe testing period

### Alternative 2: Keep Both Indefinitely
**Allow both paths to coexist permanently.**

**Rejected because:**
- Doubles maintenance burden
- Risk of divergent validation logic
- Confuses future developers about "official" path
- Prevents architectural closure

### Alternative 3: Rewrite Legacy to Use New Policy
**Keep Committee aggregate but swap validation engine.**

**Rejected because:**
- Committee aggregate is opaque (1100+ lines, many dependencies)
- Harder to test than starting from ConstitutionalCommittee
- Doesn't gain event sourcing benefits
- Defers addressing architectural inconsistency

### Alternative 4: Full Replacement on v2.0
**Keep legacy active in v1.x, remove completely in v2.0.**

**Considered, but:**
- Too aggressive for production code
- May break organisations mid-election
- Provides no migration window
- Adopted softer deadline approach instead

---

## Key Decision Points for Future

### How to Prevent Dual-Path Divergence?

When both paths are active:
- **Same organisation cannot use both** (feature flag enforcement)
- New organisations → new path only
- Existing organisations → legacy path until explicitly migrated
- Validation rules must remain synchronized (both read from `governance_level_definitions`)

### Feature Parity Gate

Before removing legacy path, check:
- [ ] New path handles all committee types (central, regional, local, ad-hoc)
- [ ] New path derives committee categories correctly
- [ ] New path supports all geographic structures (hierarchical, regional, flat)
- [ ] New path integrates with member assignment eligibility checks
- [ ] Events are published consistently and consumed correctly
- [ ] Integration tests pass with real election workflows

### Code Organization

Consider moving both implementations to `UseCases/CreateCommittee/`:

```
app/Contexts/Membership/Application/Committee/UseCases/CreateCommittee/
├── CreateCommitteeCommand.php (new)
├── CreateCommitteeHandler.php (new)
├── Legacy/
│   ├── CreateCommitteeUseCase.php (deprecated)
│   ├── InternalCreateCommittee.php (deprecated)
│   └── TransactionalCreateCommittee.php (deprecated)
└── Ports/
    ├── CommitteeRepositoryPort.php
    └── EventBusPort.php
```

This makes deprecation status explicit in filesystem structure.

---

## Related Decisions

- **ADR: Geo×Governance Matrix** — Why new path uses GovernancePolicy instead of structure-based rules
- **Decision: Event Sourcing for ConstitutionalCommittee** — Why new path publishes CommitteeEstablished events
- **Policy: Geographic Unit Creation** — How GeoUnitController populates reference data that GovernancePolicy reads

---

## Open Questions

1. **When should Phase 2 (feature parity assessment) happen?** — End of this sprint or after GeoUnits are populated?
2. **Should new path support "structure-less" creation?** — Or require structure still exists for backward compatibility?
3. **What happens to organisations that have invalid governance configurations?** — How does new path handle edge cases legacy path tolerates?
4. **Is TransactionalCreateCommittee wrapper needed in new path?** — Or should transaction boundary be in CreateCommitteeHandler?

---

## Approval

- [ ] **Tech Lead:** Review and approve decision
- [ ] **Product:** Acknowledge timeline and user impact  
- [ ] **QA:** Understand testing strategy for dual paths
- [ ] **Docs:** Plan migration guide for v3.0 release

---

**Document Version:** 1.0  
**Last Updated:** 2026-05-13  
**Next Review:** After Phase 1 completion
