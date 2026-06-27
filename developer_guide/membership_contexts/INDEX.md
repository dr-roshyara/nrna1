# Membership Context Developer Guide Index

## 📚 Documentation Structure

This guide covers the **Domain-Driven Design implementation** of the Membership Context, including a strangler fig migration from legacy code.

### Quick Start

1. **First Time?** → Start with `README.md` for architectural overview
2. **Working with Repositories?** → Read `REPOSITORY_PATTERN.md` (especially the deterministic persistence section)
3. **Multi-tenant Setup?** → Read `MULTI_TENANCY.md`
4. **Creating Use Cases?** → Read `USE_CASES.md`

---

## Guides

### README.md
**Overview and Architecture**

- High-level architecture diagram
- Key design decisions
- Domain layer (aggregates, value objects, events)
- Infrastructure layer (repositories)
- Application layer (use cases)
- Testing strategy
- Common pitfalls
- File structure

**When to read:** Getting started, understanding overall design

---

### REPOSITORY_PATTERN.md
**The Deterministic Persistence Problem and Solution**

- Root cause of the firstOrCreate() bug
- Why it caused 3 tests to fail (11→14 tests passing)
- The deterministic solution (first() + create pattern)
- Implementation patterns for insert vs update
- Before/after test comparisons
- Rules to follow and rules to break

**When to read:**
- Before implementing a repository
- Troubleshooting test failures
- Understanding why firstOrCreate() is banned

**Key Takeaway:** Always use `first()` + manual create, never `firstOrCreate()` or `updateOrCreate()`

Commit: `51dafe2d9`

---

### MULTI_TENANCY.md
**Tenant Isolation and Global Scopes**

- Global scope pattern with BelongsToTenant
- Repository tenant scoping requirements
- Testing tenant isolation (3 test patterns)
- Common tenant-related bugs
- Controller layer tenant handling
- Middleware checks
- Database schema requirements
- Defensive layering strategy

**When to read:**
- Loading or saving multi-tenant data
- Debugging cross-tenant data leaks
- Writing tenant isolation tests
- Understanding withoutGlobalScopes() pattern

---

### USE_CASES.md
**Orchestration and Command Pattern**

- Use case structure and the five steps
  1. Load aggregates
  2. Call domain methods
  3. Create new aggregates
  4. Save all aggregates
  5. Dispatch events

- Commands as DTOs
- Controller integration
- Unit and integration testing
- Common use case mistakes
- Checklist for new use cases

**When to read:**
- Creating a new use case
- Understanding how domain methods fit together
- Testing use cases
- Integrating with controllers

---

## Phase 3D Summary

**Goal:** Complete the strangler fig migration by refactoring MembershipApplicationController to use DDD use cases.

**Result:**
- ✅ All 14 membership application tests passing
- ✅ Deterministic repository pattern implemented
- ✅ Three failing tests now pass:
  - approve_creates_organisation_user_and_member
  - approve_creates_pending_membership_fee
  - approve_fires_membership_application_approved_event

**Key Fix:** Replaced `firstOrCreate()` with explicit `first()` + create pattern

Commit: `51dafe2d9`

---

## Design Principles

### 1. Single Write Authority
Only one code path writes to a table at a time. When transitioning from legacy to DDD, delete the old code immediately.

### 2. Deterministic Persistence
Never use `firstOrCreate()` in repositories. Always use explicit `first()` → check → create → fill → save pattern.

### 3. Multi-Tenancy First
All queries must include both the value lookup AND the organisation_id filter.

### 4. Pure Domain
Domain layer has zero Laravel dependencies. No Eloquent, no Facades, no HTTP concerns.

### 5. Explicit Coordination
Use cases explicitly orchestrate aggregates, repositories, and events. No magic.

---

## Architecture Layers

```
HTTP Requests
     ↓
Controllers (validation, DTO creation)
     ↓
Use Cases (orchestration)
     ↓
Domain Aggregates (business logic)
     ↓
Repositories (persistence)
     ↓
Eloquent Models (database adapters)
     ↓
PostgreSQL Database
```

Each layer is isolated and testable independently.

---

## Testing Strategy

### Unit Tests
- Domain aggregates and value objects
- No database required
- Pure business logic

### Integration Tests
- Repositories with PostgreSQL
- Use cases with aggregates
- Multi-tenant isolation

### Feature Tests
- Controllers and form handling
- HTTP responses
- Full request lifecycle

All three levels are required for complete coverage.

---

## PostgreSQL Requirement

The membership context **requires PostgreSQL** for testing (not SQLite) because:
- Global scope + tenant filtering requires proper transaction isolation
- UUID column type validation
- Future geography context needs recursive CTEs
- Proper concurrent transaction handling

SQLite's locking model breaks the test assumptions.

---

## Current Phase (3D)

**What's Implemented:**
- ✅ Application aggregate (submit, approve, reject)
- ✅ Member aggregate (register, activate)
- ✅ Fee aggregate (create, pay, overdue)
- ✅ Three use cases (submit, approve, register)
- ✅ All 14 tests passing
- ✅ Deterministic repository pattern

**What's Next (Phase 3E):**
- [ ] Finance integration and reports
- [ ] Invoice generation
- [ ] Scheduled jobs (renewals, overdue detection)
- [ ] Payment recording UI
- [ ] Member lifecycle transitions

---

## Important Checklist

Before writing code:

- [ ] Are you in the domain layer? → No Eloquent, no Laravel
- [ ] Are you in a repository? → Use first() + create, never firstOrCreate()
- [ ] Are you saving aggregates? → Save all, not partial
- [ ] Are you dispatching events? → After save, in transaction
- [ ] Are you testing tenants? → Always test multiple organisations
- [ ] Are you using value objects? → Never pass raw strings

---

## File Locations

- **Domain:** `app/Contexts/Membership/Domain/*/`
- **Use Cases:** `app/Contexts/Membership/Application/*/UseCases/`
- **Repositories:** `app/Contexts/Membership/Infrastructure/Repositories/`
- **Tests:** `tests/Feature/Membership/`, `tests/Unit/Contexts/Membership/`
- **Plan:** `.claude/plans/clear-jazzy-matsumoto.md` (Phase 3D section)

---

## Key Commits

- `51dafe2d9` - Fix: Deterministic repository pattern (firstOrCreate → first)

---

## Questions?

If something doesn't match the guide:
1. Check if it's a recent change (look at commit history)
2. Check if it's a legitimate exception (noted in that section)
3. File an issue or update the guide

---

**Last Updated:** May 3, 2026  
**Phase:** 3D (Strangler Fig Routing)  
**Status:** ✅ All 14 tests passing  
**Test Suite:** 14/14 membership application tests
