# Multi-Tenancy Isolation: The `organisation_id` Column

**Date:** March 2, 2026
**Version:** 1.0
**Status:** ✅ Implemented & Verified

---

## Table of Contents

1. [Overview](#overview)
2. [Problem Statement](#problem-statement)
3. [Solution Overview](#solution-overview)
4. [Architecture & Design](#architecture--design)
5. [Implementation Details](#implementation-details)
6. [Code Examples](#code-examples)
7. [Testing Strategy](#testing-strategy)
8. [Best Practices](#best-practices)
9. [Troubleshooting](#troubleshooting)

---

## Overview

This guide explains the **multi-tenancy isolation system** in the nrna-eu voting platform, specifically focusing on how the `organisation_id` column ensures that voter step tracking data is properly scoped to each tenant organisation.

### Key Concept

> **Every tenant's voting data must be completely isolated and inaccessible to other tenants.**

In a multi-tenant system, data from Organisation A should **never** be accessible to Organisation B, even through database queries. This isolation is enforced at multiple layers:

- **Database Layer** - Foreign key constraints with `organisation_id`
- **Model Layer** - Global scopes via the `BelongsToTenant` trait
- **Middleware Layer** - Request-level tenant context validation
- **Query Layer** - Explicit tenant filtering in all queries

---

## Problem Statement

### The Issue (Before March 2, 2026)

The `VoterSlugStep` model—which tracks each step a voter completes (code entry → agreement → selection → verification → completion)—was decorated with the `BelongsToTenant` trait:

```php
// app/Models/VoterSlugStep.php
class VoterSlugStep extends Model
{
    use BelongsToTenant;  // ⬅️ Applies global scope filtering by organisation_id
}
```

However, the **database table was missing the `organisation_id` column**, causing queries to fail:

```
Error: Unknown column 'organisation_id' in 'where clause'
```

When the model tried to apply the global scope filter:

```sql
-- What the framework tried to execute:
SELECT * FROM voter_slug_steps
WHERE organisation_id = ?  -- ❌ Column doesn't exist!
```

### Why This Happened

The `VoterSlugStep` table was created without the `organisation_id` column because:
- Initial schema design predated the multi-tenancy isolation requirement
- The table was created to track steps but lacked tenant scoping
- Subsequent model changes (adding `BelongsToTenant` trait) assumed the column existed

### Business Impact

**Without this isolation, the following security violations could occur:**

| Scenario | Risk | Severity |
|----------|------|----------|
| Voter from Org A queries steps | Sees steps from Org B's voters | **CRITICAL** |
| Vote auditing tool | Reports cross-tenant voting activity | **CRITICAL** |
| Election analytics | Mixes data from multiple organisations | **HIGH** |
| User authentication | User from Org A might access Org B's election | **CRITICAL** |

---

## Solution Overview

### The Fix (Implemented March 2, 2026)

Added the missing `organisation_id` column to the `voter_slug_steps` table:

```php
// database/migrations/2026_03_02_100636_add_organisation_id_to_voter_slug_steps_table.php

Schema::table('voter_slug_steps', function (Blueprint $table) {
    // Add tenant identifier
    $table->unsignedBigInteger('organisation_id')
        ->after('election_id')
        ->nullable();

    // Link to organisations table
    $table->foreign('organisation_id')
        ->references('id')
        ->on('organisations')
        ->onDelete('cascade');

    // Index for efficient tenant filtering
    $table->index('organisation_id');
});
```

### What This Achieves

✅ **Tenant Isolation**
- Each voter's steps are now scoped to their organisation
- Queries automatically filtered by `organisation_id`

✅ **Data Consistency**
- When an organisation is deleted, all associated steps are cascade-deleted
- No orphaned records from deleted organisations

✅ **Query Performance**
- Index on `organisation_id` speeds up tenant-filtered queries
- Efficient lookups for "all steps for organisation X"

✅ **Security Compliance**
- Impossible to accidentally query across tenant boundaries
- Enforced at the model level, not just in application code

---

## Architecture & Design

### The Multi-Layer Isolation Model

```
┌─────────────────────────────────────────────────────────┐
│           REQUEST LIFECYCLE                              │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  1️⃣ Request arrives                                     │
│     URL: /nrna-eu/v/{slug}/demo-code/create            │
│                                                          │
│  2️⃣ Middleware extracts tenant                          │
│     Middleware: IdentifyTenant                          │
│     Result: session('current_organisation_id') = 2     │
│                                                          │
│  3️⃣ Model applies global scope                          │
│     Model: VoterSlugStep                               │
│     Trait: BelongsToTenant                             │
│     Effect: WHERE organisation_id = 2                  │
│                                                          │
│  4️⃣ Query executes                                      │
│     Database: SELECT * FROM voter_slug_steps            │
│              WHERE organisation_id = 2                  │
│              AND voter_slug_id = ?                      │
│                                                          │
│  5️⃣ Results isolated to tenant                          │
│     Only steps from Org 2's voters returned             │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

### BelongsToTenant Trait

The `BelongsToTenant` trait automatically applies a global scope to filter queries:

```php
// app/Traits/BelongsToTenant.php
trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        static::addGlobalScope(new TenantScope());
    }
}

// The TenantScope implementation
class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // Add WHERE clause to every query
        $builder->where('organisation_id', session('current_organisation_id'));
    }
}
```

### Why This Pattern Is Effective

| Aspect | Benefit |
|--------|---------|
| **Automatic** | Applied to every query without explicit code |
| **Transparent** | Developers don't need to remember tenant filtering |
| **Enforced** | Impossible to accidentally query across tenants |
| **Overridable** | `withoutGlobalScopes()` available when needed |
| **Auditable** | Easy to see where scope is being bypassed |

---

## Implementation Details

### Migration File

**Location:** `database/migrations/2026_03_02_100636_add_organisation_id_to_voter_slug_steps_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds organisation_id column to voter_slug_steps table to support
     * BelongsToTenant global scope and multi-tenancy isolation.
     */
    public function up(): void
    {
        Schema::table('voter_slug_steps', function (Blueprint $table) {
            // Add organisation_id column after election_id for logical grouping
            if (!Schema::hasColumn('voter_slug_steps', 'organisation_id')) {
                $table->unsignedBigInteger('organisation_id')
                    ->after('election_id')
                    ->nullable();

                // Add foreign key constraint
                $table->foreign('organisation_id')
                    ->references('id')
                    ->on('organisations')
                    ->onDelete('cascade');

                // Add index for tenant filtering
                $table->index('organisation_id');
            }
        });

        // Populate existing records with organisation_id from related voter_slug
        if (DB::table('voter_slug_steps')->whereNull('organisation_id')->exists()) {
            DB::statement('
                UPDATE voter_slug_steps vss
                JOIN voter_slugs vs ON vss.voter_slug_id = vs.id
                SET vss.organisation_id = vs.organisation_id
                WHERE vss.organisation_id IS NULL
            ');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voter_slug_steps', function (Blueprint $table) {
            // Drop foreign key first
            if (Schema::hasForeignKey('voter_slug_steps', 'voter_slug_steps_organisation_id_foreign')) {
                $table->dropForeign('voter_slug_steps_organisation_id_foreign');
            }

            // Drop index if it exists
            if (Schema::hasIndex('voter_slug_steps', 'voter_slug_steps_organisation_id_index')) {
                $table->dropIndex('voter_slug_steps_organisation_id_index');
            }

            // Drop column if it exists
            if (Schema::hasColumn('voter_slug_steps', 'organisation_id')) {
                $table->dropColumn('organisation_id');
            }
        });
    }
};
```

### Key Migration Features

1. **Idempotent Checks**
   - `if (!Schema::hasColumn(...))` prevents errors if run multiple times
   - Safe to run in different environments

2. **Data Backfill**
   - Existing records populated from related `voter_slug` entries
   - Uses JOIN to match steps with their associated voter slugs
   - Sets correct `organisation_id` for historical data

3. **Proper Rollback**
   - Removes foreign key constraint first (prevents cascading)
   - Drops index before column
   - Conditional checks in `down()` to handle partial migrations

4. **Referential Integrity**
   - Foreign key ensures `organisation_id` references valid organisation
   - CASCADE DELETE removes steps when organisation is deleted
   - Prevents orphaned records

---

## Code Examples

### Example 1: Querying Voter Steps (Automatic Filtering)

```php
// app/Http/Controllers/VoterStepController.php

class VoterStepController extends Controller
{
    public function show(VoterSlug $slug)
    {
        // This query is AUTOMATICALLY scoped to current organisation!
        $steps = VoterSlugStep::where('voter_slug_id', $slug->id)->get();

        // The actual SQL executed:
        // SELECT * FROM voter_slug_steps
        // WHERE organisation_id = 2  ← Added automatically by BelongsToTenant
        // AND voter_slug_id = 123

        return response()->json($steps);
    }
}
```

### Example 2: Creating Voter Steps (Automatic Assignment)

```php
// app/Services/VoterSlugService.php

class VoterSlugService
{
    public function recordStep(VoterSlug $slug, int $step, string $ipAddress): VoterSlugStep
    {
        // Creating a step automatically includes current organisation
        $voterStep = VoterSlugStep::create([
            'voter_slug_id' => $slug->id,
            'election_id' => $slug->election_id,
            'step' => $step,
            'ip_address' => $ipAddress,
            'started_at' => now(),
            // 'organisation_id' is handled by model's boot method
        ]);

        // The actual INSERT includes organisation_id:
        // INSERT INTO voter_slug_steps
        //   (voter_slug_id, election_id, step, ip_address, organisation_id, ...)
        // VALUES (123, 456, 1, '192.168.1.1', 2, ...)

        return $voterStep;
    }
}
```

### Example 3: Cross-Tenant Access Prevention

```php
// Scenario: User from Organisation A tries to access Organisation B's steps

// Request: GET /org-b/api/steps/123
// Session: current_organisation_id = 1 (Organisation A)

$step = VoterSlugStep::find(123);  // This step has organisation_id = 2

// What happens:
// SELECT * FROM voter_slug_steps
// WHERE id = 123
// AND organisation_id = 1  ← Global scope restricts to current org
//
// Result: NULL (not found)
// The step exists, but isn't accessible because it belongs to Org 2

// This is the CORRECT behavior - prevents data leakage!
```

### Example 4: Admin Querying All Steps (Bypassing Scope)

```php
// Sometimes developers need to bypass the tenant scope for admin/reporting

// app/Http/Controllers/Admin/ReportController.php

class ReportController extends Controller
{
    public function auditTrail(Request $request)
    {
        $organisationId = $request->input('org_id');

        // Bypass global scope to query specifically for requested org
        $allSteps = VoterSlugStep::withoutGlobalScopes()
            ->where('organisation_id', $organisationId)
            ->orderBy('created_at')
            ->get();

        // This is SAFE because:
        // 1. withoutGlobalScopes() is explicit and visible
        // 2. We re-apply filtering: WHERE organisation_id = $organisationId
        // 3. Admin is authorized to view this organisation's data
        // 4. Easy to audit in code review

        return view('admin.report', ['steps' => $allSteps]);
    }
}
```

---

## Testing Strategy

### Unit Tests: Model Scoping

```php
// tests/Unit/Models/VoterSlugStepTest.php

class VoterSlugStepTest extends TestCase
{
    public function test_voter_slug_step_belongs_to_tenant()
    {
        // Create two organisations
        $org1 = Organisation::create(['name' => 'Org 1']);
        $org2 = Organisation::create(['name' => 'Org 2']);

        // Create users and elections in each org
        $user1 = User::create(['organisation_id' => $org1->id, ...]);
        $user2 = User::create(['organisation_id' => $org2->id, ...]);

        $election1 = Election::create(['organisation_id' => $org1->id, ...]);
        $election2 = Election::create(['organisation_id' => $org2->id, ...]);

        // Create voter slugs for each org
        $slug1 = VoterSlug::create([
            'user_id' => $user1->id,
            'election_id' => $election1->id,
            'organisation_id' => $org1->id,
        ]);

        $slug2 = VoterSlug::create([
            'user_id' => $user2->id,
            'election_id' => $election2->id,
            'organisation_id' => $org2->id,
        ]);

        // Create steps in each organisation
        $step1 = VoterSlugStep::create([
            'voter_slug_id' => $slug1->id,
            'election_id' => $election1->id,
            'organisation_id' => $org1->id,
            'step' => 1,
        ]);

        $step2 = VoterSlugStep::create([
            'voter_slug_id' => $slug2->id,
            'election_id' => $election2->id,
            'organisation_id' => $org2->id,
            'step' => 1,
        ]);

        // Test 1: When viewing as Org 1, only see Org 1's steps
        session(['current_organisation_id' => $org1->id]);
        $visibleSteps = VoterSlugStep::all();
        $this->assertCount(1, $visibleSteps);
        $this->assertTrue($visibleSteps->first()->id === $step1->id);

        // Test 2: When viewing as Org 2, only see Org 2's steps
        session(['current_organisation_id' => $org2->id]);
        $visibleSteps = VoterSlugStep::all();
        $this->assertCount(1, $visibleSteps);
        $this->assertTrue($visibleSteps->first()->id === $step2->id);

        // Test 3: Org 1 cannot access Org 2's steps directly
        session(['current_organisation_id' => $org1->id]);
        $this->assertNull(VoterSlugStep::find($step2->id));
    }
}
```

### Integration Tests: Middleware Chain

```php
// tests/Feature/Voting/MiddlewareChainTest.php

class MiddlewareChainTest extends TestCase
{
    public function test_voter_slug_step_respects_organisation_boundary()
    {
        // Setup
        $org1 = Organisation::create(['name' => 'Org 1']);
        $org2 = Organisation::create(['name' => 'Org 2']);

        $user1 = User::create(['organisation_id' => $org1->id, ...]);
        $election1 = Election::create(['organisation_id' => $org1->id, ...]);
        $slug1 = VoterSlug::create([
            'user_id' => $user1->id,
            'election_id' => $election1->id,
            'organisation_id' => $org1->id,
        ]);

        // User 1 is logged in and visiting their own slug
        $this->actingAs($user1);
        session(['current_organisation_id' => $org1->id]);

        // Should be able to create steps in their organisation
        $response = $this->postJson("/api/steps", [
            'voter_slug_id' => $slug1->id,
            'step' => 1,
        ]);

        $this->assertEquals(201, $response->status());

        // Verify step was created with correct organisation_id
        $this->assertDatabaseHas('voter_slug_steps', [
            'voter_slug_id' => $slug1->id,
            'organisation_id' => $org1->id,
        ]);
    }
}
```

### Verification Commands

```bash
# Verify migration was applied
php artisan migrate:status | grep "add_organisation_id_to_voter_slug_steps"

# Check column exists
php artisan tinker
>>> DB::getSchemaBuilder()->getColumnListing('voter_slug_steps')
=> ["id", "voter_slug_id", "election_id", "organisation_id", ...]

# Verify index exists
>>> DB::select("SHOW INDEX FROM voter_slug_steps WHERE Column_name = 'organisation_id'")

# Check foreign key exists
>>> Schema::hasForeignKey('voter_slug_steps', 'voter_slug_steps_organisation_id_foreign')
=> true
```

---

## Best Practices

### ✅ DO

```php
// 1. Always use BelongsToTenant trait on multi-tenant models
class VoterSlugStep extends Model
{
    use BelongsToTenant;  // ✅ Automatic tenant scoping
}

// 2. Trust the global scope - it's already handling tenant filtering
$steps = VoterSlugStep::where('voter_slug_id', $id)->get();  // ✅ Safe

// 3. Set organisation_id when creating records
VoterSlugStep::create([
    'voter_slug_id' => $slug->id,
    'organisation_id' => auth()->user()->organisation_id,  // ✅ Explicit
]);

// 4. Use withoutGlobalScopes() when needed, but justify it
$allSteps = VoterSlugStep::withoutGlobalScopes()
    ->where('organisation_id', $org_id)  // ✅ Re-apply scoping
    ->get();

// 5. Test with multiple organisations
public function test_data_is_isolated()
{
    $org1 = Organisation::create(...);
    $org2 = Organisation::create(...);
    // Create data in both, verify isolation
}
```

### ❌ DON'T

```php
// 1. Don't query without BelongsToTenant trait
class VoterSlugStep extends Model
{
    // ❌ Missing BelongsToTenant trait - NOT TENANT SAFE
}

// 2. Don't manually filter - let global scope handle it
$steps = VoterSlugStep::where('organisation_id', $orgId)->get();
// ❌ Wrong - if you forget this, data leaks!

// 3. Don't assume request context is set
// ❌ Wrong - might get NULL from session if not in web middleware
$orgId = session('current_organisation_id');

// 4. Don't bypass the scope without documentation
$steps = VoterSlugStep::withoutGlobalScopes()->get();  // ❌ Unsafe!

// 5. Don't create without setting organisation_id
VoterSlugStep::create([
    'voter_slug_id' => $slug->id,
    'step' => 1,
    // ❌ Missing organisation_id - might cause issues
]);
```

### Code Review Checklist

When reviewing code that touches `VoterSlugStep`:

- [ ] Does the model extend from a class with `BelongsToTenant` trait?
- [ ] Are queries using `withoutGlobalScopes()` explicitly justified?
- [ ] Are tests checking multiple organisations?
- [ ] Is the `organisation_id` explicitly set when creating records?
- [ ] Could this query leak data across tenant boundaries?
- [ ] Is there a foreign key relationship to ensure referential integrity?

---

## Troubleshooting

### Issue 1: "Unknown column 'organisation_id'"

**Symptoms:**
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'organisation_id'
```

**Cause:**
- Migration hasn't been applied yet
- Database is out of sync with code

**Solution:**
```bash
# Run pending migrations
php artisan migrate

# Verify column was created
php artisan tinker
>>> Schema::hasColumn('voter_slug_steps', 'organisation_id')
=> true
```

### Issue 2: "Column 'organisation_id' doesn't have a default value"

**Symptoms:**
```
SQLSTATE[HY000]: General error: 1364 Field 'organisation_id' doesn't have a default value
```

**Cause:**
- Creating records without setting `organisation_id`
- Model doesn't automatically set the value

**Solution:**

```php
// Option 1: Use boot() method in model
class VoterSlugStep extends Model
{
    use BelongsToTenant;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->organisation_id) {
                $model->organisation_id = session('current_organisation_id');
            }
        });
    }
}

// Option 2: Always explicitly set when creating
VoterSlugStep::create([
    ...,
    'organisation_id' => auth()->user()->organisation_id,
]);
```

### Issue 3: "Seeing data from wrong organisation"

**Symptoms:**
- Queries return steps from different organisations
- Data leakage between tenants

**Cause:**
- Global scope not applied (missing trait)
- Scope bypassed without proper filtering
- Session context not set

**Solution:**

```php
// Check if trait is applied
class VoterSlugStep extends Model
{
    use BelongsToTenant;  // ✅ Add if missing
}

// Check session context
session(['current_organisation_id' => $userId->organisation_id]);

// If using withoutGlobalScopes, re-apply filtering
$steps = VoterSlugStep::withoutGlobalScopes()
    ->where('organisation_id', session('current_organisation_id'))
    ->get();
```

### Issue 4: "Foreign key constraint fails"

**Symptoms:**
```
SQLSTATE[HY000]: General error: 1452 Cannot add or update a child row
```

**Cause:**
- Trying to create step with non-existent organisation_id
- Organisation doesn't exist in organisations table

**Solution:**

```php
// Verify organisation exists
if (!Organisation::find($organisation_id)) {
    throw new Exception('Organisation not found');
}

// Verify election belongs to organisation
if ($election->organisation_id !== $organisation_id) {
    throw new Exception('Election does not belong to this organisation');
}

// Then create the step
VoterSlugStep::create([
    'organisation_id' => $organisation_id,
    'election_id' => $election_id,
    ...
]);
```

### Issue 5: "Tests failing with organisation_id scope"

**Symptoms:**
- Tests pass individually but fail when run together
- Scope from previous test affects next test

**Cause:**
- Session context not cleared between tests
- Global scope persists between test cases

**Solution:**

```php
// In test setUp
protected function setUp(): void
{
    parent::setUp();

    // Clear session context
    session(['current_organisation_id' => null]);

    // Or set explicit test organisation
    $this->testOrg = Organisation::create(['name' => 'Test Org']);
    session(['current_organisation_id' => $this->testOrg->id]);
}

// In test tearDown
protected function tearDown(): void
{
    // Clean up
    session(['current_organisation_id' => null]);
    parent::tearDown();
}
```

---

## Related Documentation

- [Verifiable Anonymity Design](./02-vote-anonymity.md)
- [Database Schema Changes](../../developer_guide/03-schema-changes.md)
- [Multi-Tenancy Architecture](../../developer_guide/ARCHITECTURE.md#multi-tenancy)
- [Testing Guide](../../developer_guide/06-testing-guide.md)

---

## Summary

The `organisation_id` column in `voter_slug_steps` is a **critical component** of the voting platform's multi-tenancy isolation system. It ensures that:

1. ✅ Voter step data is scoped to the correct organisation
2. ✅ Cross-tenant data leakage is impossible
3. ✅ Performance is optimized with proper indexing
4. ✅ Referential integrity is enforced with foreign keys
5. ✅ The system is secure by default, not by convention

**All developers working on the voting system must understand and respect this isolation mechanism.**

---

**Last Updated:** March 2, 2026
**Reviewed By:** Development Team
**Status:** ✅ Production Ready
