# Repository Pattern Guide

## The Deterministic Persistence Problem

### What Went Wrong

```php
// ❌ THIS CAUSED THE BUG
$model = $this->model
    ->withoutGlobalScopes()
    ->where('id', $appId)
    ->where('organisation_id', $orgId)
    ->firstOrCreate([
        'id' => $appId,
        'organisation_id' => $orgId,
    ]);

$model->status = 'approved';  // Updates this instance
$model->save();
```

**Problem:**
1. `firstOrCreate()` has two possible outcomes
2. If row exists: returns existing row
3. If row doesn't exist: creates NEW row and returns it
4. With `withoutGlobalScopes()` + global scopes + global state
5. Sometimes it returned a DIFFERENT instance than what existed in DB
6. Test queries original row → sees old value
7. Repository updated different row → not visible to test

**Result:** 11/14 tests passing, 3 failing with "application status not changing"

### The Solution

```php
// ✅ CORRECT - Always deterministic
$model = $this->model
    ->withoutGlobalScopes()
    ->where('id', $id)
    ->where('organisation_id', $org_id)
    ->first();

// Create new instance only if not found
if (!$model) {
    $model = new $this->model();
    $model->id = $id;
    $model->organisation_id = $org_id;
}

// Modify the loaded or created instance
$model->status = $application->getStatus()->value();
$model->rejection_reason = $application->getRejectionReason();
$model->application_data = $application->getApplicationData();

// Save the same instance
$model->save();
```

**Why this works:**
1. `first()` is deterministic → always returns same row
2. If not found, create new instance with required fields
3. Fill all properties from aggregate
4. Save → either INSERT (new) or UPDATE (existing)
5. Always updating the same instance that was loaded

## Implementation Pattern

### For New Aggregates (Insert)

```php
public function save(Member $member, TenantId $tenantId): void
{
    // Try to load existing
    $model = $this->model
        ->withoutGlobalScopes()
        ->where('id', $member->getId()->value())
        ->where('organisation_id', $tenantId->value())
        ->first();

    // Create new instance if not found
    if (!$model) {
        $model = new $this->model();
        $model->id = $member->getId()->value();
        $model->organisation_id = $tenantId->value();
    }

    // Fill properties
    $model->personal_info = json_encode([
        'fullName' => $member->getPersonalInfo()->getFullName(),
        'email' => $member->getPersonalInfo()->getEmail(),
        'phone' => $member->getPersonalInfo()->getPhone(),
    ]);
    $model->membership_type_id = $member->getMembershipTypeId()->value();
    $model->status = $member->getStatus()->value();

    // Save (INSERT or UPDATE)
    $model->save();
}
```

### For Existing Aggregates (Update)

```php
public function save(Application $application, TenantId $tenantId): void
{
    // Load must succeed (application already exists)
    $model = $this->model
        ->withoutGlobalScopes()
        ->where('id', $application->getId()->value())
        ->where('organisation_id', $tenantId->value())
        ->first();

    if (!$model) {
        // For updates, not finding the row is an error
        throw new RuntimeException("Application not found: " . $application->getId()->value());
    }

    // Fill properties
    $model->status = $application->getStatus()->value();
    $model->rejection_reason = $application->getRejectionReason();

    // Save
    $model->save();
}
```

## Testing the Fix

### Before (Broken)
```
Test Run 1: Create application → PASS
Test Run 2: Approve application → FAIL (status still "submitted")
Test Run 3: Approve application → FAIL (status still "submitted")

✗ firstOrCreate() created duplicate rows
✗ Repository updated row A
✗ Test queried row B (original)
```

### After (Fixed)
```
Test Run 1: Create application → PASS
Test Run 2: Approve application → PASS (status is "approved")
Test Run 3: Approve application → PASS (status is "approved")

✓ first() + create pattern is deterministic
✓ Repository always updates the loaded row
✓ Test queries same row that was updated
```

## Rules to Follow

### ✅ DO

- Use `withoutGlobalScopes()` in repository load queries
- Use `where('id', ...)` AND `where('organisation_id', ...)` together
- Check `if (!$model)` and create new instance if needed
- Fill ALL properties before save
- Return aggregate from load methods, not model
- Use reconstitute() to map model → aggregate

### ❌ DON'T

- Use `firstOrCreate()` in repositories
- Use `updateOrCreate()` with global scopes
- Use `where()` without `organisation_id` clause
- Forget to set id + organisation_id on new models
- Leave properties unfilled before save
- Mix database queries and domain logic

## Why This Matters

Repository pattern's job:
1. **Load:** Eloquent model → Domain aggregate
2. **Save:** Domain aggregate → Eloquent model → Database

If loading and saving are non-deterministic:
- Domain invariants break
- Tests become flaky
- Concurrent requests cause data corruption
- Hard to debug (looks like transaction issue, but it's not)

The deterministic pattern ensures:
- Every load returns the same row
- Every save updates that same row
- No duplicate rows
- No ghost updates
- Tests always reliable

---

**Historical Context:**

Phase 3D had 3 failing tests (11/14 passing). Root cause was `firstOrCreate()` creating non-deterministic behavior. After switching to explicit `first()` + create pattern, all 14 tests pass.

Commit: `51dafe2d9`
