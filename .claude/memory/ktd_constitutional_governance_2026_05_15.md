# 🧠 KNOWLEDGE TRANSFER DOCUMENT (KTD)

## Constitutional Governance Platform — Laravel DDD / CQRS System

**Date:** 2026-05-15
**Status:** Active Development (Convergence Phase Started)
**Frontend:** Vue 3
**Backend:** Laravel (PHP 8+)
**Architecture Style:** Domain-Driven Design (DDD) + CQRS-inspired temporal model (single write model)
**Persistence:** MySQL/PostgreSQL (Eloquent ORM)
**Testing:** PHPUnit Feature + Unit Tests (TDD-informed, currently stabilization phase)

---

# 1. 🎯 SYSTEM PURPOSE

The system is a **constitutional governance platform** that models:

* Members
* Committees
* Geographic governance boundaries
* Membership lifecycle (assignment, status transitions, lineage tracking)
* Event-driven governance decisions

It simulates **real-world governance structures** with:

* Constitutional constraints
* Geographic eligibility rules
* Temporal membership history
* Immutable auditability of membership changes

---

# 2. 🧱 CURRENT ARCHITECTURE STATE

## 2.1 Critical Architecture Decision

```yaml
SINGLE WRITE MODEL: committee_associations
Membership lineage: represented by lineage_id (groups events)
Previous CQRS dual-table model: REJECTED
```

---

## 2.2 Persistence Model (FINALIZED)

### ✅ ONLY WRITE TABLE

```
committee_associations
├── id (primary key)
├── lineage_id (UUID - groups constitutional identity)
├── member_id
├── committee_id
├── status (active | suspended | terminated)
└── ... (timestamps, metadata)
```

### Meaning:

| Concept             | Representation            |
| ------------------- | ------------------------- |
| Membership Identity | `lineage_id` (UUID)       |
| Lifecycle events    | Multiple rows per lineage |
| Current state       | `status` field            |
| Assignment history  | temporal rows             |

---

### ❌ Deprecated Models (DO NOT USE)

* `membership_lineages` table ❌
* `committee_assignments` table ❌
* CQRS dual-write assumptions ❌

These were explicitly removed from the architecture.

---

# 3. 🧠 DOMAIN MODEL

## 3.1 Core Entities

### Member
* Location: `app/Contexts/Membership/Domain/Member/Member.php`
* Database: `members` table
* Key field: `residence_geo_unit_id`
* Role: Source of eligibility input

### Committee
* Location: `app/Contexts/Membership/Domain/Committee/Committee.php`
* Database: `committees` table
* Key field: `operational_geo` (nullable)
  * NULL = Central committee (no geo restriction)
  * Non-NULL = Geo-scoped committee

### Committee Association (WRITE MODEL)
* Location: Database only (`committee_associations` table)
* Represents: Membership assignment event + lifecycle state + temporal record
* Key field: `lineage_id` (groups events)

---

## 3.2 Membership Lineage Concept

Instead of separate lineage table, lineage_id groups all membership events:

```
lineage_id = X
  → Row 1: status='active' (initial assignment)
  → Row 2: status='suspended' (lifecycle change)
  → Row 3: status='reactivated' (restoration)
  → Row 4: status='terminated' (end)
```

---

# 4. 🧠 BUSINESS RULES (DOMAIN INVARIANTS)

## 4.1 Committee Eligibility Rule

```text
IF committee.operational_geo IS NULL
   → always eligible (central committee)

ELSE
   → member.residence_geo_unit_id MUST be within committee geo scope
```

**Implementation:**
* Location: `CommitteeEligibilityPolicy::isEligible()`
* Throws: `CommitteeEligibilityException` (typed exception)

---

## 4.2 Duplicate Assignment Rule

```text
A member cannot have more than ONE active membership per committee
```

**Implementation:**
* Checked in handler before transaction
* Uses: `MembershipLineageRepository::findLineageByMemberAndCommitteeForTenant()`
* Throws: `DomainException('Member already has an active membership...')`

---

## 4.3 Transaction Boundary Rule

```text
VALIDATE (outside transaction)
   ↓
WRITE (inside transaction, atomic)
   ↓
DISPATCH (after commit)
```

**Implementation:**
* Location: `AssignMemberToCommitteeHandler::handle()`
* Pattern: `DB::transaction()` with post-commit event dispatch

---

## 4.4 Event Rule

```text
Events dispatched ONLY AFTER successful commit
```

---

# 5. 🧠 DOMAIN POLICY LAYER

## Current Policy

**File:** `app/Contexts/Membership/Domain/Committee/Policies/CommitteeEligibilityPolicy.php`

### Responsibilities:
* Validate geo eligibility
* Enforce central vs geo-scoped logic
* Return boolean (pure function)

### Exception Strategy:

Uses **typed exceptions (NOT string-based errors)**:

```php
CommitteeEligibilityException
```

---

## Exception Types (Canonical)

**File:** `app/Contexts/Membership/Domain/Committee/Exceptions/CommitteeEligibilityException.php`

| Method                | Thrown When                    | Code              |
| --------------------- | ------------------------------ | ----------------- |
| missingResidenceGeo() | Member has no residence geo    | MEMBER_NO_RESIDENCE_GEO |
| geoOutOfScope()       | Member outside committee geo   | GEO_OUT_OF_SCOPE  |

---

# 6. 🧪 TESTING ARCHITECTURE STATE

## Reference Implementation: STABLE

**Test File:** `tests/Feature/Committee/AssignMemberWithLineageTest.php`

**Status:** 6/6 GREEN ✅

---

## Testing Principles Established

### ❌ WRONG (OLD PATTERN)

```php
expectExceptionMessage("exact string match")
assertDatabaseHas('membership_lineages', ...)
assertDatabaseHas('committee_assignments', ...)
```

### ✅ RIGHT (NEW PATTERN)

```php
expectException(CommitteeEligibilityException::class)
assertDatabaseHas('committee_associations', [
    'member_id' => $this->member->id,
    'committee_id' => $committee->id,
    'status' => 'active',
])
```

---

## Key Test Fixes Applied

1. **Member Residence Geo Setup**
   * Fixed: `$this->member->update(['residence_geo_unit_id' => ...])`
   * MUST update `members` table, not `users` table
   * MUST be in `$fillable` array on Member model

2. **Mock Namespace Correction**
   * OLD: `Application\Ports\MembershipLineageRepositoryPort`
   * NEW: `Application\Membership\Ports\MembershipLineageRepositoryPort`

3. **Exception Assertions**
   * OLD: `expectException(\DomainException::class)` + message check
   * NEW: `expectException(CommitteeEligibilityException::class)` (type only)

---

# 7. 🚀 STRATEGIC STATE

## We are NOT in:

* ❌ Architecture design phase
* ❌ CQRS transformation phase
* ❌ Abstraction expansion phase
* ❌ Snapshot/Kernel contract phase

---

## We ARE in:

### 🔥 Convergence Phase (Critical)

**Goal:**
```
Align all tests + handlers to ONE truth model: committee_associations
```

**Current:**
* 1 slice stable (AssignMember)
* 154 slices still broken (legacy CQRS assumptions)

**Risk:**
* HIGH if pattern propagated without understanding
* MEDIUM if controlled convergence applied

---

# 8. 🧭 EXECUTION PRINCIPLES

## Golden Rules

1. **No new abstraction without 2+ passing test slices**
   * Prevent premature generalization
   * Wait for patterns to emerge

2. **No dual-write models**
   * committee_associations is ONLY truth
   * No parallel membership_lineages table

3. **No string-based exception assertions**
   * Assert exception TYPE, not message
   * Messages are implementation detail

4. **No snapshot layer**
   * Explicitly rejected in this system
   * Domain objects are snapshots (via reconstitution)

5. **Always converge toward real database model**
   * Tests should mirror actual persistence
   * Don't invent test-specific models

---

# 9. 📊 WHAT IS NOW STABLE

### Reference Slice: AssignMemberWithLineage

Location: `tests/Feature/Committee/AssignMemberWithLineageTest.php`

**6 passing tests demonstrate:**
* Correct geo policy enforcement ✅
* Correct exception typing ✅
* Correct DB assertions (committee_associations only) ✅
* Correct transaction boundaries ✅
* Correct post-commit event dispatch ✅

---

## Pattern for Replication

This test slice is the **golden implementation pattern**:

1. Load aggregates via repositories
2. Validate via domain policy (returns bool)
3. Check duplicate rule (query lineage)
4. Wrap writes in transaction
5. Assert only on real tables (committee_associations)
6. Expect typed exceptions (not strings)

---

# 10. 🚀 NEXT CHAT SESSION INSTRUCTIONS

## Context Starter for Next Session

```
You are continuing a Laravel DDD system in convergence phase.

CURRENT STATE:
- Single write model: committee_associations
- Membership lineage: lineage_id (temporal grouping)
- One reference slice stable: AssignMember (6/6 GREEN)
- Remaining 154 tests broken (legacy CQRS assumptions)

YOUR ROLE:
- Guide safe, controlled convergence
- Prevent reintroduction of CQRS dual-model
- Extract reusable patterns (do NOT generalize yet)
- Ensure consistency across domain + handlers + tests

DO NOT:
- Introduce new abstractions
- Expand snapshot/Kernel contract
- Bulk refactor without understanding variance
- Break working tests

DO:
- Categorize remaining failures
- Apply reference pattern iteratively
- Verify each batch before proceeding
- Document architectural decisions
```

---

# 11. 📋 POTENTIAL NEXT STEPS

If you return to this system, consider:

### Option A: Failure Categorization
```bash
php artisan test --no-coverage 2>&1 | grep -E "FAILED|ERROR" | head -50
```
Group by: geo-policy | duplicate-rules | lifecycle | events | persistence

### Option B: Safe Propagation Strategy
1. Pick one category (e.g., all geo-policy failures)
2. Apply reference pattern to 5-10 tests
3. Verify GREEN
4. Document pattern
5. Move to next category

### Option C: Test Migration Script
Generate template for batch updating test assertions from old to new pattern

---

# 12. 🎯 FINAL SUMMARY

### System Truth

```
committee_associations (ONLY write table)
├── temporal membership events
├── lineage_id (groups constitutional identity)
└── status (state machine: active → suspended → terminated)
```

### Achieved Stability

* One vertical slice fully stable (AssignMember)
* Typed exception system in place
* Geo policy correctly enforced
* Handler transaction boundaries correct
* Test pattern established (type assertions, real DB tables)

### Remaining Work

* Converge 154 tests to reference pattern
* Prevent CQRS assumptions from reappearing
* Build consistent handler + policy layer across domain

---

**End of KTD**
