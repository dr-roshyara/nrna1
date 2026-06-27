# ADR-0001: Committee Write/Read Model Separation

**Date:** 2026-05-16  
**Status:** Accepted  
**Decision Maker:** Architecture Council  
**Priority:** High (Governance Clarity)  

---

## 1. Problem Statement

The system contains two distinct "Committee" abstractions:

1. **Write Model (Source of Truth)**
   - Location: `app/Contexts/Membership/Domain/Committee`
   - Role: Constitutional governance aggregate root
   - Responsibility: Committee lifecycle, membership assignments
   - State: Immutable lineage tracking via `committee_associations` table

2. **Read Model (Projections)**
   - Location: `app/Contexts/Committee`
   - Role: Query-optimized views for dashboards and reports
   - Responsibility: Denormalized reads, fast queries
   - State: Derived from write model events

### Current Risk

Using identical name `Committee` in both contexts creates:
- ✗ Import ambiguity (which Committee am I using?)
- ✗ Semantic confusion (write vs read intent unclear)
- ✗ Risk of domain logic leaking into read layer
- ✗ Harder onboarding for new developers

---

## 2. Decision

### Accepted: Progressive Disambiguation Strategy

We maintain both models but clarify their roles through:

#### Phase 1 (Immediate): Namespace Clarity + ADR Lock
**Timeline:** Implemented immediately (0 code moves)

1. **Namespace Aliases in Service Providers**
   ```php
   // config/services.php or ServiceProvider boot()
   use App\Contexts\Membership\Domain\Committee as CommitteeWriteModel;
   use App\Contexts\Committee\ReadModels\CommitteeProjection as CommitteeReadModel;
   
   // Bind to container with explicit names
   $this->app->bind('committee.write', CommitteeWriteModel::class);
   $this->app->bind('committee.read', CommitteeReadModel::class);
   ```

2. **Code Review Guidelines**
   - Never use bare `Committee` import without alias
   - Required pattern: `CommitteeWrite` or `CommitteeRead`
   - Exception: Within same context folder (local imports)

3. **Documentation in Code**
   ```php
   /**
    * CommitteeWriteModel (v1.0)
    * 
    * Part of DDD Write Layer (source of truth for governance decisions)
    * See: ADR-0001
    * 
    * [Temporary naming during Phase F convergence]
    * Will be renamed structurally in Phase F.5 (post-green-tests)
    */
   final readonly class Committee
   ```

---

#### Phase 2 (Post-Green Tests): Structural Rename
**Timeline:** Phase F.5 (after all tests pass)  
**Scope:** One coordinated PR, one day of work

```bash
# Coordinated folder rename
git mv app/Contexts/Membership/Domain/Committee \
        app/Contexts/Membership/Domain/CommitteeWrite

git mv app/Contexts/Committee \
        app/Contexts/CommitteeRead

# Update 100+ namespace declarations via IDE refactoring
# Run full test suite
php artisan test --parallel

# Commit with [RENAME] prefix for easy rollback
git commit -m "[RENAME] Disambiguate Committee into CommitteeWrite/CommitteeRead

Implements Phase 2 of ADR-0001.
All tests passing: 154/154 ✅
"
```

---

## 3. Architecture Rationale

### Write Model (`CommitteeWriteModel`)

**Responsibility:** Constitutional truth for governance decisions

```
CommitteeWriteModel
├── Aggregate Root (final)
├── Invariants: geo eligibility, membership lifecycle
├── State: immutable lineage via committee_associations
└── Events: MemberAssignedToCommittee, CommitteeSuspended, etc.
```

**Guarantees:**
- ✅ CQRS write side authority
- ✅ No query optimization (always current)
- ✅ Strict DDD purity (no Laravel)
- ✅ Source of truth for all governance decisions

---

### Read Model (`CommitteeReadModel`)

**Responsibility:** Query-optimized projections for UI/dashboards

```
CommitteeReadModel
├── Denormalized view
├── Optimized for: list queries, filtering, sorting
├── State: eventual consistency (events → projections)
└── Cache: safe to invalidate and rebuild
```

**Guarantees:**
- ✅ Fast reads (no complex joins)
- ✅ Dashboard-ready format
- ✅ Eventual consistency from write model
- ✅ Can be rebuilt from event stream

---

## 4. Boundaries (Critical for DDD)

### Write Side Isolation
```
Membership/Domain/Committee
├── MUST NOT import from app/Contexts/Committee
├── MUST NOT use read projections
└── MUST use only value objects + domain events
```

### Read Side Dependency
```
Contexts/Committee
├── MAY import from Membership/Domain/Committee (read-only)
├── MUST NOT modify write model
└── MUST listen to write model events
```

---

## 5. Implementation Timeline

| Phase | Action | Timeline | Impact |
|-------|--------|----------|--------|
| F.3 | ✅ Reference slice stable | Complete | 6/6 tests GREEN |
| F.4 | **NOW** ADR + aliases | 1 hour | Clarity locked |
| F.4 | Test convergence | 2-4 hours | 154 tests GREEN |
| F.5 | Structural rename | 1 day | Code clarity +100% |

---

## 6. Risk Mitigation

### Risk 1: "Developers forget to use aliases"
**Mitigation:** Code review checklist, linter rules (Phase F.5+)

### Risk 2: "Naming causes confusion during test fixes"
**Mitigation:** Keep aliases lightweight, rename only after tests pass

### Risk 3: "Rename breaks hidden dependencies"
**Mitigation:** Use IDE refactoring (safe), run full test suite before merge

---

## 7. Alternatives Considered

### ❌ Alternative A: Immediate Rename
- **Pro:** Solves problem immediately
- **Con:** Breaks 100+ files during critical test convergence phase
- **Decision:** Deferred to Phase F.5

### ❌ Alternative B: Keep Current Names
- **Pro:** No code changes
- **Con:** Semantic confusion persists
- **Decision:** Mitigated by aliases + ADR documentation

### ✅ Alternative C: Progressive (Chosen)
- **Pro:** Clarity now, safe rename later
- **Con:** Temporary naming during transition
- **Decision:** Best balance of risk/clarity

---

## 8. Success Criteria

- [ ] ADR documented and approved
- [ ] Namespace aliases implemented in ServiceProvider
- [ ] Code review guidelines updated
- [ ] All 154 tests passing (Phase F.4)
- [ ] Structural rename executed (Phase F.5)
- [ ] Zero regressions post-rename

---

## 9. Related Decisions

- **ADR-0000:** DDD Layering Architecture
- **Phase F.3:** Geo-Based Committee Member Assignment (Reference Slice)
- **Phase F.4:** Test Convergence (Active)
- **Phase F.5:** Structural Rename (Scheduled)

---

## 10. Review History

| Date | Reviewer | Status | Notes |
|------|----------|--------|-------|
| 2026-05-16 | Architecture Council | Accepted | Progressive approach approved |

---

**This ADR is binding. Any deviation requires a follow-up ADR.**
