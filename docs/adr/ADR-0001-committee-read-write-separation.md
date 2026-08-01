## ✅ Generating ADR-0001: Committee Read/Write Separation

I'll create the complete ADR with proper structure for your architecture documentation system.

---

## File Location

```text
docs/adr/0001-committee-read-write-separation.md
```

---

## Complete ADR Content

```markdown
# ADR-0001: Committee Context Read/Write Separation (CQRS Boundary)

## Status
**Accepted** — March 2025

## Context

The system contains two representations of Committee-related data:

1. **Membership Context → Committee Domain Model (WRITE)**
   - Location: `app/Contexts/Membership/Domain/Committee/`
   - Purpose: Business rules, state transitions, constitutional governance

2. **Committee Context → Read Models (READ)**
   - Location: `app/Contexts/Committee/`
   - Purpose: Query optimization, UI projections, reporting

This separation evolved organically during CQRS implementation but was never formally documented, creating ambiguity for developers about which abstraction to use for which operation.

### Problem Statement

Without explicit documentation, developers cannot determine:
- Where to put new Committee-related business logic
- Whether to use `Committee` model for writes or reads
- Which context owns the "truth" for member-committee relationships
- How to safely optimize queries without corrupting domain state

## Decision

We explicitly separate Committee concerns into **write model (truth)** and **read model (projection)** layers with **no bidirectional dependencies**.

### WRITE MODEL (Source of Truth)

**Location:** `app/Contexts/Membership/Domain/Committee/`

**Responsibilities:**
- Committee lifecycle management (create, suspend, dissolve)
- Geo jurisdiction rules and validation
- Constitutional governance policies
- CommitteeAssignment episode creation
- State transition invariants

**Key Classes:**
- `Committee.php` (Aggregate Root)
- `CommitteeId.php` (Value Object)
- `CommitteeSlug.php` (Value Object)
- `GeoPathChain.php` (Value Object)
- `CommitteeEligibilityPolicy.php` (Domain Service)

**Constraints:**
- ✅ Contains business rules
- ✅ Enforces invariants
- ✅ Source of truth for committee state
- ❌ NO query optimization logic
- ❌ NO direct Laravel Eloquent optimizations

### READ MODEL (Projection)

**Location:** `app/Contexts/Committee/`

**Responsibilities:**
- Query optimization for UI performance
- Read-only projections
- Member-committee listings (denormalized)
- Dashboard statistics
- Reporting data

**Key Classes:**
- `CommitteeMemberView.php` (Read DTO)
- `CommitteeMembershipStats.php` (Read DTO)
- `CommitteeMembershipReadModelPort.php` (Interface)
- `EloquentCommitteeMembershipReadModelPort.php` (Infrastructure Adapter)

**Constraints:**
- ✅ Optimized for read performance
- ✅ Denormalized for specific UI needs
- ✅ Can cache aggressively
- ❌ NO business logic
- ❌ NO state mutations
- ❌ NO write operations (create/update/delete)

## Architecture Diagram

```text
┌─────────────────────────────────────────────────────────────┐
│                     HTTP / Controllers                       │
└─────────────────┬───────────────────────────┬───────────────┘
                  │                           │
                  │ WRITE                      │ READ
                  ▼                           ▼
┌─────────────────────────────────┐  ┌─────────────────────────┐
│   MEMBERSHIP CONTEXT            │  │   COMMITTEE CONTEXT     │
│   ┌───────────────────────────┐ │  │   ┌───────────────────┐ │
│   │ Domain/Committee/         │ │  │   │ Application/      │ │
│   │ - Committee (Aggregate)   │ │  │   │ ReadModel/        │ │
│   │ - CommitteeAssignment     │ │  │   │ - CommitteeMember │ │
│   │ - GeoJurisdictionPolicy   │ │  │   │   View            │ │
│   └───────────────────────────┘ │  │   │ - Stats           │ │
│                                 │  │   └───────────────────┘ │
│   ┌───────────────────────────┐ │  │                         │
│   │ Application/Ports/        │ │  │   ┌───────────────────┐ │
│   │ - CommitteeRepository     │◄┼──┼───│ Infrastructure/    │ │
│   └───────────────────────────┘ │  │   │ - EloquentRead    │ │
│                                 │  │   │   ModelAdapter    │ │
│   ┌───────────────────────────┐ │  │   └───────────────────┘ │
│   │ Infrastructure/           │ │  │                         │
│   │ - EloquentCommitteeRepo   │ │  │   DATABASE (Read-Optimized)
│   └───────────────────────────┘ │  │   - committee_member_views
└─────────────────────────────────┘  └─────────────────────────┘
                  │                           │
                  │ Eventual Consistency      │
                  │ (Domain Events)           │
                  └───────────────────────────┘
```

## Rationale

### Why Separation Is Necessary

1. **Domain Purity** — Write model must remain free of persistence optimizations
2. **Performance** — Read projections can be denormalized without affecting domain invariants
3. **Scalability** — Read layer can scale independently (caching, replicas)
4. **Testability** — Write model tests don't require complex query mocking
5. **Constitutional Governance** — Rules engine must have single source of truth

### Why Not Merge Back Together

| Merge Attempt | Problem |
|---------------|---------|
| Put read logic in domain | Breaks DDD purity, couples queries to aggregates |
| Put write logic in read | Allows accidental state mutation |
| Single Committee class | Mixes responsibilities, violates SRP |

## Consequences

### Positive

- ✅ Clear separation of concerns
- ✅ Write model stays pure (no Eloquent dependencies)
- ✅ Read models can be optimized aggressively
- ✅ Independent deployment possible (different DB replicas)
- ✅ Easier onboarding (folder structure reveals intent)

### Negative

- ❌ Requires eventual consistency between write and read
- ❌ More infrastructure complexity (projections, sync jobs)
- ❌ Must maintain two representations of similar data
- ❌ Learning curve for CQRS pattern

### Mitigation Strategies

| Negative | Mitigation |
|----------|------------|
| Eventual consistency | Use domain events + queue workers |
| Two representations | Auto-generate read models from write events |
| Complexity | Document this ADR + code examples |
| Learning curve | Add `@see` annotations linking to ADR |

## Constraints and Enforcement

### Immediate Enforcement (Code Review)

```php
// ✅ CORRECT — Write operation uses Domain
$committee = Committee::createCentral($name, $code, $geoPath);
$this->committeeRepository->save($committee);

// ❌ INCORRECT — Write operation using Read model
$committee = new CommitteeMemberView(); // Read-only DTO
$committee->save(); // NOT POSSIBLE (no save method)

// ✅ CORRECT — Read operation uses Projection
$view = $this->committeeReadModel->findByMember($memberId);

// ❌ INCORRECT — Read operation accessing domain repository
$committee = $this->committeeRepository->find($id); // For write use only
```

### Automated Enforcement (Planned)

1. **PHPStan Rule** (Q2 2025)
   ```neon
   parameters:
       forbiddenMethodCalls:
           - class: 'App\Contexts\Committee\*'
             method: '/save|update|delete|create/'
   ```

2. **Laravel Model Events** (Q2 2025)
   - Disable saving on read models in boot() method

3. **Git Hooks** (Q3 2025)
   - Prevent commits that import write classes into read context

## Related Decisions

- **ADR-0002:** MembershipLineage as Constitutional Source of Truth (Planned)
- **ADR-0003:** Event Sourcing for Audit Trail (Deferred to F3.3)
- **ADR-0004:** Geo Jurisdiction Matching Strategy (Planned)

## Migration Path for Existing Code

No immediate migration required. Existing code remains functional.

**Phase 2 (Q3 2025):** Rename folders for clarity
```bash
git mv app/Contexts/Membership/Domain/Committee app/Contexts/Membership/Domain/CommitteeWrite
git mv app/Contexts/Committee app/Contexts/CommitteeRead
```

**Phase 3 (Q4 2025):** Add PHPStan rules for enforcement

## Exceptions

The following are **NOT considered violations**:

1. Read model adapters calling write repositories for **read-only queries**
2. Write model using read model DTOs for **response formatting**
3. Controllers injecting both contexts for **different operations**

## References

- [Martin Fowler: CQRS](https://martinfowler.com/bliki/CQRS.html)
- [DDD/CQRS in Laravel (PublicDigit Internal Wiki)](https://wiki.publicdigit.com/architecture/cqrs)
- [Original Architecture Decision: Phase C Committee Query Service](docs/architecture/phase-c-eligible-committee-query.md)

## Review Cycle

- **Next Review:** September 2025 (or when Phase 2 migration occurs)
- **Reviewers:** Architecture Team, Lead Developers
- **Required for changes:** 2 architecture team approvals

---

## Changelog

| Date | Change | Author |
|------|--------|--------|
| 2025-03-15 | Initial ADR created | Architecture Team |
```

---

## Also Create a Short Index File

```markdown
# docs/adr/README.md

# Architecture Decision Records

This directory contains ADRs for PublicDigit's architectural decisions.

## Active ADRs

| ADR | Title | Status | Date |
|-----|-------|--------|------|
| [0001](../knowledge/archive/superseded-adrs/0001-committee-read-write-separation.md) | Committee Context Read/Write Separation (CQRS Boundary) | Accepted | 2025-03-15 |

## Proposed ADRs

- 0002: MembershipLineage as Constitutional Source of Truth
- 0003: Event Sourcing for Audit Trail
- 0004: Geo Jurisdiction Matching Strategy

## Deprecated ADRs

None.

## How to Use

1. **Before writing code** — Check relevant ADRs for constraints
2. **During code review** — Reference ADRs in comments: `// @see ADR-0001`
3. **When changing architecture** — Update ADRs, not just READMEs

## ADR Lifecycle

1. **Proposed** — Draft for discussion
2. **Accepted** — Approved and enforced
3. **Deprecated** — Replaced by newer ADR
4. **Superseded** — No longer applicable
```

---

## Next Steps

1. **Create the `docs/adr/` folder**
2. **Save the ADR as `0001-committee-read-write-separation.md`**
3. **Save the index as `README.md`**
4. **Add link in your pull request template:**

```markdown
<!-- .github/pull_request_template.md -->
## Architecture Impact
- [ ] I've reviewed relevant ADRs in `docs/adr/`
- [ ] This PR respects the write/read separation (ADR-0001)
```

Do you want me to also generate the code comment template for the Committee context classes?