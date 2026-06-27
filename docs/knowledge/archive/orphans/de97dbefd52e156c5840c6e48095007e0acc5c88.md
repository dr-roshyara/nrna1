# organisation_id Column: Quick Reference

**For busy developers - the essentials**

---

## The Problem It Solves

**Without `organisation_id`:** Voter step data could leak between organisations
**With `organisation_id`:** Each organisation's data is completely isolated

---

## The Model

```php
class VoterSlugStep extends Model
{
    use BelongsToTenant;  // ⬅️ This trait uses organisation_id
}
```

## The Table

```
voter_slug_steps
├── id
├── voter_slug_id
├── election_id
├── organisation_id        ← NEW (March 2, 2026)
├── step
├── ip_address
├── started_at
├── completed_at
├── metadata
├── created_at
├── updated_at
```

---

## 5 Common Tasks

### 1. Create a Step

```php
VoterSlugStep::create([
    'voter_slug_id' => $slug->id,
    'election_id' => $slug->election_id,
    'organisation_id' => $slug->organisation_id,  // ✅ ALWAYS SET THIS
    'step' => 1,
    'ip_address' => $request->ip(),
]);
```

### 2. Get Steps for a Voter

```php
// Automatically filtered by organisation_id ✅
$steps = VoterSlugStep::where('voter_slug_id', $slug->id)
    ->orderBy('step')
    ->get();
```

### 3. Check if Step Completed

```php
$completed = VoterSlugStep::where('voter_slug_id', $slug->id)
    ->where('step', 2)
    ->whereNotNull('completed_at')
    ->exists();

if (!$completed) {
    return response()->json(['error' => 'Step 2 not completed'], 403);
}
```

### 4. Get All Steps for Election (Admin)

```php
// ⚠️ Must explicitly handle global scope
$steps = VoterSlugStep::withoutGlobalScopes()
    ->where('election_id', $election->id)
    ->where('organisation_id', $election->organisation_id)  // ✅ Re-apply filtering
    ->get();
```

### 5. Delete Old Steps (Cleanup)

```php
// Foreign key CASCADE DELETE handles related records
VoterSlugStep::where('election_id', $id)
    ->where('created_at', '<', now()->subMonths(6))
    ->delete();
```

---

## Do's and Don'ts

| ✅ DO | ❌ DON'T |
|-------|---------|
| Set `organisation_id` when creating | Forget `organisation_id` |
| Trust the global scope | Manually re-filter every query |
| Use `with()` for eager loading | Load relationships 1-by-1 |
| Test with multiple orgs | Test with only 1 org |
| Justify use of `withoutGlobalScopes()` | Bypass scope without reason |

---

## Common Errors & Fixes

### Error: "Unknown column 'organisation_id'"

```php
// Fix: Run the migration
php artisan migrate
```

### Error: "Column 'organisation_id' doesn't have a default value"

```php
// Fix: Set organisation_id when creating
VoterSlugStep::create([
    ...,
    'organisation_id' => $slug->organisation_id,  // ✅
]);
```

### Getting Steps from Wrong Org

```php
// Problem: Global scope isn't applied
// Solution: Verify model has the trait
class VoterSlugStep extends Model
{
    use BelongsToTenant;  // ✅ Add if missing
}
```

---

## Testing Checklist

- [ ] Create step with organisation_id set
- [ ] Query steps - verify only current org's steps returned
- [ ] Test with multiple organisations
- [ ] Test cross-org access prevention
- [ ] Test admin bypass with `withoutGlobalScopes()`

---

## Key Dates

| Date | Event |
|------|-------|
| Mar 2, 2026 | Migration created & applied |
| Mar 2, 2026 | Exception handling tests: 8/8 ✅ |
| Mar 2, 2026 | Tenant isolation tests: 33/53 ✅ |
| TBD | Deploy to staging |
| TBD | Deploy to production |

---

## Links to Full Docs

- **Full Multi-Tenancy Guide:** `01-multi-tenancy-isolation.md`
- **Implementation Examples:** `02-voter-slug-steps-guide.md`
- **Migration & Deployment:** `03-migration-and-deployment.md`

---

## Emergency Contact

If `organisation_id` issues arise:
1. Check the full docs first (links above)
2. Search troubleshooting section
3. Check the migration status: `php artisan migrate:status`
4. Verify column exists: `php artisan tinker` → `Schema::hasColumn('voter_slug_steps', 'organisation_id')`

---

**Status:** ✅ Production Ready
**Last Updated:** March 2, 2026
