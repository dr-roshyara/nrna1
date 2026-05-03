# Membership Context Developer Guide

## Overview

The Membership Context is a **Domain-Driven Design (DDD)** bounded context that manages the complete membership lifecycle for multi-tenant organisations. It implements the strangler fig pattern to gradually migrate from legacy Eloquent-based code to a clean DDD architecture.

**Current Status:** Phase 3D (Strangler Fig Routing) — All 14 membership application tests passing ✅

---

## Architecture Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                    HTTP Controllers                              │
│  (MembershipApplicationController, MemberController, etc.)       │
└────────────────────────┬────────────────────────────────────────┘
                         │ (Request → DTO)
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│               Application Layer (Use Cases)                      │
│  ├── SubmitApplication                                           │
│  ├── ApproveApplication (orchestrates 3 aggregates)              │
│  ├── RejectApplication                                           │
│  └── RegisterMember                                              │
└──────────────────────────┬──────────────────────────────────────┘
                           │
┌──────────────────────────┴──────────────────────────────────────┐
│                   Domain Layer (Pure PHP)                        │
│  Aggregates: Application, Member, Fee, Committee                │
│  Value Objects: Ids, Status enums, PersonalInfo                 │
│  Domain Events: MembershipApplicationApproved, FeePaid, etc.    │
└──────────────────────────┬──────────────────────────────────────┘
                           │
┌──────────────────────────┴──────────────────────────────────────┐
│              Infrastructure Layer (Eloquent Adapters)            │
│  Repositories: EloquentApplicationRepository, etc.               │
│  Models: ApplicationContextModel, MemberContextModel, etc.       │
└─────────────────────────────────────────────────────────────────┘
```

## Key Design Decisions

### 1. Deterministic Persistence (CRITICAL)

**Problem:** `firstOrCreate()` is non-deterministic with `withoutGlobalScopes()` + tenant filtering
- Can return different model instances
- Creates duplicate rows if constraints don't match
- Caused 3 tests to fail (application status not changing)

**Solution:** Use explicit `first()` + create pattern:

```php
$model = Model::withoutGlobalScopes()
    ->where('id', $id)
    ->where('organisation_id', $org_id)
    ->first();

if (!$model) {
    $model = new Model();
    $model->id = $id;
    $model->organisation_id = $org_id;
}

$model->fill($data)->save();
```

**Always:**
- Load exact row with `first()`
- Create new instance if needed
- Update loaded instance (deterministic)

**Never:**
- Use `firstOrCreate()` in repositories
- Use `updateOrCreate()` with global scopes
- Mix loading and creating in one call

Commit: `51dafe2d9`

### 2. Multi-Tenancy Pattern

All tables use `organisation_id` + `BelongsToTenant` global scope:

```php
// Repositories use withoutGlobalScopes() + explicit where
$model = Model::withoutGlobalScopes()
    ->where('id', $id)
    ->where('organisation_id', $tenantId)
    ->first();

// Save always passes TenantId explicitly
$repository->save($aggregate, TenantId::fromOrganisationId($org->id));
```

### 3. Single Write Authority

Legacy and DDD code share tables (members, membership_fees), but:
- Only ONE code path writes to a table at a time
- When a feature transitions to DDD, delete legacy code immediately
- No feature flags for dual write paths

### 4. UUID for All IDs

- PostgreSQL native UUID type
- Consistency across contexts
- Legacy members table already uses UUID

## Domain Layer

### Aggregates

**Application:**
- State: submitted → approved or submitted → rejected
- Invariant: Cannot approve twice

**Member:**
- Lean aggregate (fees/applications are separate)
- Has organisation_user_id link to platform user

**Fee:**
- Stores membershipTypeId as historical snapshot
- Separate aggregate with own repository

### Value Objects

```php
// Always use for domain concepts
ApplicationId::fromString($id)
MembershipTypeId::fromString($typeId)
TenantId::fromOrganisationId($orgId)
PersonalInfo::create($name, $email, $phone)
```

Never pass raw strings for domain concepts.

## Infrastructure Layer

### Repository Pattern

```php
// Load
$aggregate = $repository->find($id, $tenantId);

// Modify
$aggregate->approve();

// Save
$repository->save($aggregate, $tenantId);
```

**Key Methods:**
- `find(Id, TenantId): ?Aggregate` - Load one aggregate
- `save(Aggregate, TenantId): void` - Insert or update
- `findByStatus(...): array` - Query by status
- `reconstitute(Model): Aggregate` - Map Eloquent → Domain

## Application Layer

Use cases orchestrate domain + repositories:

```php
public function execute(Command $command): void
{
    // 1. Load aggregates
    $agg1 = $this->repo1->find(...);
    $agg2 = $this->repo2->find(...);

    // 2. Modify via domain methods
    $agg1->approve();
    $agg2 = Aggregate2::create(...);

    // 3. Save all aggregates
    $this->repo1->save($agg1);
    $this->repo2->save($agg2);

    // 4. Dispatch events
    $eventBus->dispatchAll(array_merge(
        $agg1->pullEvents(),
        $agg2->pullEvents()
    ));
}
```

## Testing

### Unit Tests (Domain)
- No database
- Test invariants and state transitions

### Integration Tests (Repositories)
- PostgreSQL database
- Test loading and saving aggregates
- Verify tenant isolation

Always test with multiple tenants to verify isolation.

## Common Pitfalls

1. **Using `firstOrCreate()`** ❌ → Use explicit `first()` + create
2. **Raw strings for domain concepts** ❌ → Use value objects
3. **Forgetting `withoutGlobalScopes()`** ❌ → Always add it in repositories
4. **Not saving all aggregates** ❌ → Save all modified aggregates
5. **Dispatching events before save** ❌ → Save first, dispatch after

## File Structure

```
app/Contexts/Membership/
├── Domain/              (Pure PHP, no Eloquent)
│   ├── Application/, Member/, Fee/
│   └── Repositories/ (interfaces only)
├── Application/         (Orchestration)
│   ├── */UseCases/
│   └── */DTOs/
└── Infrastructure/      (Eloquent allowed)
    ├── Models/
    ├── Repositories/ (implementations)
    └── Providers/
```

## Resources

- Plan: `.claude/plans/clear-jazzy-matsumoto.md`
- Tests: `tests/Feature/Membership/MembershipApplicationTest.php` (14/14 ✅)
- Commit: `51dafe2d9` - Deterministic repository fix

---

**Last Updated:** May 3, 2026 | Phase 3D | All 14 tests passing ✅
