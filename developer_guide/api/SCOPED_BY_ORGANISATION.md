# ScopedByOrganisation Trait Guide

**Strict API-context tenant scoping with fail-closed security.**

---

## Overview

`ScopedByOrganisation` is a Laravel Eloquent trait that enforces automatic tenant (organisation) isolation for models used **exclusively in API context**. Unlike `BelongsToTenant`, it has no session fallback and throws an exception if the tenant context is missing.

**CRITICAL SECURITY GUARANTEE:**
When `TenantContext::get()` returns null, the trait **THROWS an exception**. It never returns unfiltered data.

---

## When to Use This Trait

### ✅ USE ScopedByOrganisation

Models that:
- Are used **ONLY in `/api/v1/*` routes** (never in web controllers)
- Contain sensitive data requiring strict tenant isolation (financial, governance)
- Should fail loudly if tenant context is missing (API safety)
- Don't need session-based fallback (HTTP headers only)

### ❌ DON'T USE ScopedByOrganisation

Models that:
- Are used in **both web and API contexts** (use `BelongsToTenant` instead)
- Are queried from web controllers with `Inertia::render()`
- Need session-based tenant fallback
- Are part of the read-model layer for web rendering

---

## Comparison: ScopedByOrganisation vs BelongsToTenant

| Feature | ScopedByOrganisation | BelongsToTenant |
|---------|---------------------|-----------------|
| **Location** | `app/Models/Traits/` | `app/Traits/` |
| **Tenant Context Source** | HTTP header (`X-Organisation-ID`) | Session OR HTTP header |
| **Session Fallback** | ❌ No | ✅ Yes (web compatibility) |
| **Platform-Org Cache** | ❌ No | ✅ Yes (performance) |
| **Null Context Behavior** | 🛑 **THROWS exception** | Returns all (platform context) |
| **Use Case** | API-exclusive models | Shared web + API models |

---

## Implementation Details

### Trait Code

```php
trait ScopedByOrganisation
{
    protected static function bootScopedByOrganisation(): void
    {
        static::addGlobalScope('organisation_scope', function (Builder $builder) {
            $tenantId = TenantContext::get();

            // SECURITY: Fail closed — null context must throw, never return unfiltered
            if ($tenantId === null) {
                throw new \RuntimeException(
                    'TenantContext is not set. ' . static::class . ' requires tenant isolation.'
                );
            }

            $builder->where(
                $builder->getModel()->getTable() . '.organisation_id',
                $tenantId
            );
        });

        static::creating(function (Model $model) {
            if (empty($model->organisation_id)) {
                $tenantId = TenantContext::get();

                // SECURITY: Fail closed on create
                if ($tenantId === null) {
                    throw new \RuntimeException(
                        'Cannot create ' . static::class . ' without TenantContext set.'
                    );
                }

                $model->organisation_id = $tenantId;
            }
        });
    }

    public function scopeWithoutTenantScope(Builder $query): Builder
    {
        return $query->withoutGlobalScope('organisation_scope');
    }

    public function scopeForTenant(Builder $query, string $organisationId): Builder
    {
        return $query->withoutGlobalScope('organisation_scope')
                     ->where($this->getTable() . '.organisation_id', $organisationId);
    }
}
```

### Key Security Features

1. **Qualified Table Names**: Uses `$builder->getModel()->getTable() . '.organisation_id'` to prevent column ambiguity in JOINs
2. **Fail-Closed on Read**: `null` context throws `RuntimeException` before query executes
3. **Fail-Closed on Create**: Auto-fill requires valid context; throws if missing
4. **Global Scope**: Applied automatically to all queries (no opt-out by accident)
5. **Preserve Explicit Values**: Doesn't overwrite `organisation_id` if already set

---

## How to Use

### 1. Apply to Model

```php
<?php

namespace App\Models;

use App\Models\Traits\ScopedByOrganisation;
use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    use ScopedByOrganisation;

    protected $fillable = ['organisation_id', 'name', 'status'];
}
```

### 2. Ensure TenantContext is Set

Before querying the model, ensure `TenantContext` contains the tenant ID:

```php
// In middleware (runs automatically)
TenantContext::set($organisationId);

// Then queries work
$elections = Election::all(); // Returns only this org's elections
```

### 3. Missing Context = Exception

```php
// ❌ This throws RuntimeException
TenantContext::clear();
$elections = Election::all();  // Throws: TenantContext is not set...
```

### 4. Create Records

```php
// ✅ Auto-fills organisation_id from context
TenantContext::set('org-123');
$election = Election::create(['name' => 'Board Election']);
// organisation_id automatically set to 'org-123'

// ❌ Fails if context is null
TenantContext::clear();
Election::create(['name' => 'Test']); // Throws exception
```

### 5. Explicit org_id Overrides Context

```php
TenantContext::set('org-123');

// If explicitly provided, NOT overwritten by context
$election = Election::create([
    'organisation_id' => 'org-456',  // This value is preserved
    'name' => 'Special Election'
]);
// Result: organisation_id = 'org-456' (not 'org-123')
```

### 6. Admin Scope Bypass (Rare)

```php
// Bypass global scope for admin operations only
// (Prefer explicit repositories instead)
Election::withoutTenantScope()->get();  // Returns ALL elections

// Query specific org (admin operations)
Election::forTenant('org-456')->get();  // Returns org-456's elections only
```

---

## API Endpoint Flow

### Request Pipeline

```
Client Request (with X-Organisation-ID header)
    ↓
TenantContext middleware sets context from header
    ↓
Controller queries Election::all()
    ↓
ScopedByOrganisation global scope applies WHERE clause
    ↓
Only this org's elections returned ✓
```

### Error Handling

```php
// No header = fail-closed
GET /api/v1/elections
// ❌ RuntimeException caught by exception handler
// ✅ Returns 400 JSON: {"error": "Missing tenant context", "code": "MISSING_TENANT_CONTEXT"}

// Header present = success
GET /api/v1/elections
X-Organisation-ID: org-123
// ✅ Returns org-123's elections
```

---

## Testing Strategy

### Unit Test Template

```php
use PHPUnit\Framework\TestCase;
use App\Services\TenantContext;
use App\Models\Election;

class ElectionScopingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Create test organisations
        Organisation::create(['id' => 'org-1', 'name' => 'Org 1', 'slug' => 'org-1']);
        Organisation::create(['id' => 'org-2', 'name' => 'Org 2', 'slug' => 'org-2']);
    }

    protected function tearDown(): void
    {
        TenantContext::clear();
        parent::tearDown();
    }

    public function test_scopes_queries_to_tenant_context()
    {
        TenantContext::set('org-1');
        Election::create(['name' => 'Org 1 Election']);

        TenantContext::set('org-2');
        Election::create(['name' => 'Org 2 Election']);

        // Act: Query as org-1
        TenantContext::set('org-1');
        $results = Election::all();

        // Assert: Only org-1's election returned
        $this->assertCount(1, $results);
        $this->assertEquals('org-1', $results->first()->organisation_id);
    }

    public function test_throws_exception_when_context_null()
    {
        TenantContext::set('org-1');
        Election::create(['name' => 'Test']);

        TenantContext::clear();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('TenantContext is not set');

        Election::all();
    }

    public function test_auto_fills_organisation_id_on_create()
    {
        TenantContext::set('org-1');
        $election = Election::create(['name' => 'Board Vote']);

        $this->assertEquals('org-1', $election->organisation_id);
    }

    public function test_throws_on_create_without_context()
    {
        TenantContext::clear();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot create');

        Election::create(['name' => 'Test']);
    }
}
```

---

## Common Pitfalls

### ❌ Pitfall 1: Mixed Web + API Models

```php
// WRONG: Applied to model used in web controller
class Member extends Model
{
    use ScopedByOrganisation;  // ❌ Will break web requests
}

// CORRECT: Use BelongsToTenant
class Member extends Model
{
    use BelongsToTenant;  // ✅ Works in both web and API
}
```

### ❌ Pitfall 2: Forgetting to Set Context

```php
// This code will fail at query time
public function showElection($id)
{
    // ❌ TenantContext is null!
    $election = Election::find($id);  // Throws exception
}

// CORRECT: Set context first (middleware does this)
// Or ensure middleware has already set it
```

### ❌ Pitfall 3: Catching the Exception

```php
// WRONG: Hiding the security failure
try {
    $elections = Election::all();
} catch (RuntimeException $e) {
    return [];  // ❌ Returns empty list instead of failing
}

// CORRECT: Let exception propagate (caught by exception handler)
$elections = Election::all();  // ✅ Throws, handler returns 400 JSON
```

### ❌ Pitfall 4: Assuming Implicit Context in Tests

```php
public function test_election()
{
    // ❌ Context is not automatically set
    $election = Election::create(['name' => 'Test']);  // Throws!

    // CORRECT: Set context explicitly
    TenantContext::set('org-1');
    $election = Election::create(['name' => 'Test']);  // ✅ Works
}
```

---

## Migration Path: BelongsToTenant → ScopedByOrganisation

If you need to convert a model from `BelongsToTenant` to `ScopedByOrganisation`:

### Prerequisites

1. **Audit all usages**: Grep for the model across the codebase
2. **Verify API-only**: Ensure it's NOT used in any web controller
3. **Check relationships**: Update related queries if they also need scoping
4. **Review tests**: Update tests to set TenantContext explicitly

### Conversion Steps

```php
// Step 1: Replace trait
- use BelongsToTenant;
+ use ScopedByOrganisation;

// Step 2: Update tests to handle exceptions
public function test_my_model()
{
    TenantContext::set('org-1');  // ADD THIS
    $model = MyModel::create([...]);
    // ...
    TenantContext::clear();  // ADD THIS in tearDown
}

// Step 3: Test in API context only
// Ensure middleware sets TenantContext from X-Organisation-ID header
```

---

## Performance Considerations

### Query Performance

The global scope adds a single `WHERE` clause:
```sql
SELECT * FROM elections 
WHERE organisation_id = 'org-123'
```

**No performance penalty** if `organisation_id` is indexed (which it should be).

### Testing Performance

Each test that needs a different tenant should:
1. Create a test organisation in `setUp()`
2. Use raw SQL inserts for large datasets (bypass the trait)
3. Clear context in `tearDown()`

---

## Security Guarantees

### ✅ Guaranteed

1. **No cross-tenant leakage** — Queries always filter by organisation_id
2. **Fail-closed** — Missing context throws exception, never returns unfiltered
3. **No silent failures** — Exception message clearly states what's missing
4. **Explicit scoping** — Admin methods (`withoutTenantScope`, `forTenant`) are visible

### ⚠️ Not Guaranteed

1. **Relationship leakage** — If related models don't use scoping, they can leak data
   - Solution: Apply trait to all related API models
2. **Cache leakage** — Manual caching without tenant key
   - Solution: Always include organisation_id in cache keys
3. **Raw queries** — `DB::select()` bypasses scoping
   - Solution: Use Eloquent queries only in API context

---

## Exception Messages

### RuntimeException: "TenantContext is not set"

```
RuntimeException: TenantContext is not set. App\Models\Election requires tenant isolation.
```

**Cause**: Query executed without setting `TenantContext::set($organisationId)`

**Fix**:
1. Ensure middleware sets context from header
2. In tests, call `TenantContext::set($orgId)` before querying

### RuntimeException: "Cannot create ... without TenantContext"

```
RuntimeException: Cannot create App\Models\Election without TenantContext set.
```

**Cause**: Tried to create record without context

**Fix**:
1. Set context before `Model::create()`
2. Or pass explicit `organisation_id` in create array

---

## Verification Checklist

- [ ] Model is used **ONLY** in API endpoints (not web controllers)
- [ ] Trait applied: `use ScopedByOrganisation;`
- [ ] Tests set `TenantContext` explicitly
- [ ] Tests clear context in `tearDown()`
- [ ] Tests verify exception thrown when context null
- [ ] Exception handler catches and returns 400 JSON
- [ ] API middleware sets `TenantContext` from `X-Organisation-ID` header
- [ ] All related models also have scoping trait
- [ ] Cache keys include organisation_id
- [ ] No raw SQL queries for this model
- [ ] Unit tests pass (8/8 test cases from template)

---

## Related Documentation

- [[MULTI_TENANCY.md]] — Multi-tenancy architecture
- [[EXCEPTION_HARDENING.md]] — API exception handling
- [[README.md]] — API layer overview

---

**Status:** ✅ Production Ready
**Security Level:** High (fail-closed)
**Use Case:** API-exclusive models with strict tenant isolation
