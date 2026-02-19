# BelongsToTenant Trait - Complete Reference

Deep dive into the BelongsToTenant trait that powers multi-tenancy.

## File Location

```
app/Traits/BelongsToTenant.php
```

## What Does It Do?

The `BelongsToTenant` trait automatically:
1. Filters queries by organisation_id
2. Auto-fills organisation_id on model creation
3. Provides helper methods for tenant operations
4. Prevents cross-tenant access

## Complete Source Code

```php
<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;

/**
 * BelongsToTenant Trait
 *
 * Provides automatic tenant scoping for Eloquent models.
 *
 * Features:
 * - Applies global scope to filter by organisation_id
 * - Auto-fills organisation_id on model creation
 * - Supports both null (default platform) and non-null organisation_id values
 *
 * Usage:
 *     class User extends Model {
 *         use BelongsToTenant;
 *     }
 *
 * Now all User queries will automatically filter by organisation_id:
 *     User::all();           // Only users from current organisation
 *     User::find($id);       // Returns null if not from current org
 *     User::create([...]);   // Auto-fills organisation_id
 */
trait BelongsToTenant
{
    /**
     * Boot the trait - register global scope and creating observer
     */
    protected static function bootBelongsToTenant()
    {
        // Add global scope to all queries
        static::addGlobalScope('tenant', function (Builder $query) {
            $query->where('organisation_id', session('current_organisation_id'));
        });

        // Auto-fill organisation_id when creating
        static::creating(function (Model $model) {
            // Only set if not already set
            if (is_null($model->organisation_id)) {
                $model->organisation_id = session('current_organisation_id');
            }
        });
    }

    /**
     * Scope: Include all records (bypass global scope for admin operations)
     *
     * Usage: User::withoutGlobalScopes()->get()
     */
    public function scopeIgnoreTenant(Builder $query)
    {
        return $query->withoutGlobalScopes();
    }

    /**
     * Scope: Only records from a specific organisation
     *
     * Usage: User::forOrganisation(1)->get()
     */
    public function scopeForOrganisation(Builder $query, $organisationId)
    {
        return $query->withoutGlobalScopes()->where('organisation_id', $organisationId);
    }

    /**
     * Scope: Only records from default platform (organisation_id = null)
     *
     * Usage: User::forDefaultPlatform()->get()
     */
    public function scopeForDefaultPlatform(Builder $query)
    {
        return $query->withoutGlobalScopes()->whereNull('organisation_id');
    }

    /**
     * Check if this model belongs to the current organisation
     *
     * @return bool
     */
    public function belongsToCurrentOrganisation(): bool
    {
        return $this->organisation_id === session('current_organisation_id');
    }

    /**
     * Check if this model belongs to a specific organisation
     *
     * @param int|null $organisationId
     * @return bool
     */
    public function belongsToOrganisation($organisationId): bool
    {
        return $this->organisation_id === $organisationId;
    }
}
```

## How It Works

### Part 1: Boot Method

```php
protected static function bootBelongsToTenant()
{
    // ... trait initialization code
}
```

**When it runs**: When the model class is first loaded

**What it does**: Sets up two key systems
1. Global scope (automatic filtering)
2. Observer (automatic auto-fill)

### Part 2: Global Scope

```php
static::addGlobalScope('tenant', function (Builder $query) {
    $query->where('organisation_id', session('current_organisation_id'));
});
```

**Effect on queries**:
```php
// When you write:
User::all();

// Laravel adds this automatically:
User::all()->where('organisation_id', session('current_organisation_id'));

// Final SQL:
SELECT * FROM users WHERE organisation_id = 1;
```

**Applies to**:
- ✅ get()
- ✅ all()
- ✅ first()
- ✅ find($id)
- ✅ count()
- ✅ exists()
- ✅ update()
- ✅ delete()

**Does NOT apply to** (unless you use the method):
- ❌ withoutGlobalScopes()->get()
- ❌ ignoreTenant()->get()
- ❌ forOrganisation(2)->get()

### Part 3: Auto-Fill Observer

```php
static::creating(function (Model $model) {
    if (is_null($model->organisation_id)) {
        $model->organisation_id = session('current_organisation_id');
    }
});
```

**When it runs**: Just before a record is created

**What it does**: Checks if organisation_id is null, if so fills it from session

**Example**:
```php
session(['current_organisation_id' => 1]);

$user = User::create([
    'name' => 'John',
    // organisation_id is NOT set here
]);

// The observer runs and sets:
// $user->organisation_id = 1;

// Result:
echo $user->organisation_id; // Output: 1
```

**Prevents accidental use of null**:
```php
// This still works:
$user = User::create([
    'name' => 'John',
    'organisation_id' => 2,  // Explicitly set - observer won't override
]);

echo $user->organisation_id; // Output: 2
```

### Part 4: Query Scope Methods

#### scopeIgnoreTenant()

```php
public function scopeIgnoreTenant(Builder $query)
{
    return $query->withoutGlobalScopes();
}
```

**Purpose**: Temporarily disable all global scopes (including tenancy)

**Use case**: Admin operations that need to see all records

**Example**:
```php
// Get ONLY org 1's users (with tenant scope)
User::all();  // WHERE organisation_id = 1

// Get ALL users from all orgs (without tenant scope)
User::ignoreTenant()->all();  // No WHERE clause
// OR
User::withoutGlobalScopes()->all();  // Same thing
```

**⚠️ Security Warning**: Only use in admin-only operations!

#### scopeForOrganisation()

```php
public function scopeForOrganisation(Builder $query, $organisationId)
{
    return $query->withoutGlobalScopes()
                ->where('organisation_id', $organisationId);
}
```

**Purpose**: Get records from a specific org (not the current one)

**Use case**: Admin panels, reporting, bulk operations

**Example**:
```php
// Get users from specific organisation
$orgUsers = User::forOrganisation(2)->get();

// Equivalent to:
$orgUsers = User::withoutGlobalScopes()
                ->where('organisation_id', 2)
                ->get();
```

#### scopeForDefaultPlatform()

```php
public function scopeForDefaultPlatform(Builder $query)
{
    return $query->withoutGlobalScopes()->whereNull('organisation_id');
}
```

**Purpose**: Get records with null organisation_id (platform-level records)

**Use case**: System data that isn't org-specific

**Example**:
```php
// Get system settings (not org-specific)
$settings = SystemSetting::forDefaultPlatform()->get();

// Equivalent to:
$settings = SystemSetting::withoutGlobalScopes()
                          ->whereNull('organisation_id')
                          ->get();
```

### Part 5: Helper Methods

#### belongsToCurrentOrganisation()

```php
public function belongsToCurrentOrganisation(): bool
{
    return $this->organisation_id === session('current_organisation_id');
}
```

**Purpose**: Check if a record belongs to the current organisation

**Use case**: Authorization checks

**Example**:
```php
$election = Election::find($id);

if ($election->belongsToCurrentOrganisation()) {
    // Safe to show/edit
    $election->update(['status' => 'active']);
} else {
    // Access denied
    abort(403, 'Not found');
}
```

#### belongsToOrganisation()

```php
public function belongsToOrganisation($organisationId): bool
{
    return $this->organisation_id === $organisationId;
}
```

**Purpose**: Check if record belongs to a specific organisation

**Use case**: Multi-org operations

**Example**:
```php
$election = Election::find($id);

// Check if belongs to org 1
if ($election->belongsToOrganisation(1)) {
    // Do something for org 1
}

// Check if belongs to org 2
if ($election->belongsToOrganisation(2)) {
    // Do something for org 2
}
```

## Query Examples

### Example 1: Simple Query

```php
// Set context
session(['current_organisation_id' => 1]);

// Query (automatic scoping)
$elections = Election::all();

// SQL executed:
// SELECT * FROM elections WHERE organisation_id = 1;

// Result: Only org 1's elections
```

### Example 2: Query with Where Clause

```php
session(['current_organisation_id' => 1]);

// Query with additional filter
$active = Election::where('status', 'active')->get();

// SQL executed:
// SELECT * FROM elections
// WHERE organisation_id = 1
// AND status = 'active';

// Result: Only org 1's active elections
```

### Example 3: Find by ID

```php
session(['current_organisation_id' => 1]);

// Find a specific record
$election = Election::find($id);

// If $id belongs to org 1: returns the record
// If $id belongs to org 2: returns null (not found!)
// If $id belongs to org 2 and you need it:
$election = Election::withoutGlobalScopes()->find($id);  // Returns it
```

### Example 4: Admin: See All Records

```php
// Get ALL elections from ALL organisations
$allElections = Election::ignoreTenant()->get();
// OR
$allElections = Election::withoutGlobalScopes()->get();

// Result: All elections, no scoping
```

### Example 5: Admin: Filter by Org

```php
// Get elections only from organisation 2
$org2Elections = Election::forOrganisation(2)->get();

// Result: All of org 2's elections
```

### Example 6: Check Ownership

```php
// Get election
$election = Election::find($id);

// Check if it belongs to current org
if ($election->belongsToCurrentOrganisation()) {
    echo "Safe - belongs to current org";
} else {
    echo "Denied - belongs to different org";
}
```

## Important Caveats

### 1. Session Must Be Set

```php
// ❌ Wrong - session not set
$elections = Election::all();  // Filters by NULL!

// ✅ Correct - session set first
session(['current_organisation_id' => 1]);
$elections = Election::all();  // Filters by 1
```

### 2. NULL Values Are Included

```php
// Set session to org 1
session(['current_organisation_id' => 1]);

// This query:
$records = Record::all();

// Returns records WHERE organisation_id = 1 or organisation_id = NULL
// Because: WHERE organisation_id = 1 includes nulls in some contexts
```

### 3. Global Scope Name Must Be Unique

```php
// If multiple traits add same scope name:
static::addGlobalScope('tenant', ...);  // Trait 1
static::addGlobalScope('tenant', ...);  // Trait 2 - overwrites Trait 1!

// Solution: Use unique names
static::addGlobalScope('your_scope_name', ...);
```

### 4. Bypass Requires Explicit Call

```php
// These ALL include the scope:
Election::get();
Election::where('status', 'active')->get();
Election::find($id);
Election::count();

// Only these bypass the scope:
Election::withoutGlobalScopes()->get();
Election::ignoreTenant()->get();
Election::forOrganisation(2)->get();
```

## Performance Considerations

### Indexing

The trait expects `organisation_id` to be indexed:

```php
// In migration:
$table->unsignedBigInteger('organisation_id')
      ->nullable()
      ->after('id')
      ->index();  // ← Index is critical!

// Without index: O(n) full table scan
// With index: O(log n) fast lookup
```

### Query Optimization

```php
// Efficient - scoped to one org
SELECT * FROM elections WHERE organisation_id = 1;
// Uses index, very fast

// Less efficient - across orgs
SELECT * FROM elections;
FILTER where organisation_id = 1;
// No index, slower
```

### N+1 Problem

The trait doesn't prevent N+1 queries:

```php
// ❌ N+1 problem (slow)
$elections = Election::all();
foreach ($elections as $election) {
    $count = Vote::where('election_id', $election->id)->count();
}

// ✅ Eager loading (fast)
$elections = Election::with('votes')->all();
foreach ($elections as $election) {
    $count = $election->votes->count();
}
```

## Testing the Trait

### Test 1: Verify Scoping

```php
public function test_scope_filters_by_organisation()
{
    session(['current_organisation_id' => 1]);
    Election::create(['name' => 'Org1']);

    session(['current_organisation_id' => 2]);
    Election::create(['name' => 'Org2']);

    session(['current_organisation_id' => 1]);
    $records = Election::all();

    $this->assertCount(1, $records);
    $this->assertEquals('Org1', $records->first()->name);
}
```

### Test 2: Verify Auto-Fill

```php
public function test_auto_fills_organisation_id()
{
    session(['current_organisation_id' => 1]);

    $election = Election::create(['name' => 'Test']);

    $this->assertEquals(1, $election->organisation_id);
}
```

### Test 3: Verify Bypass

```php
public function test_bypass_with_without_global_scopes()
{
    session(['current_organisation_id' => 1]);
    Election::create(['name' => 'Org1']);

    session(['current_organisation_id' => 2]);
    Election::create(['name' => 'Org2']);

    // Without bypass - only org 2
    $records = Election::all();
    $this->assertCount(1, $records);

    // With bypass - all
    $records = Election::withoutGlobalScopes()->get();
    $this->assertCount(2, $records);
}
```

## Troubleshooting

### Problem: Queries return empty results

**Diagnosis**: Session not set

**Fix**:
```php
// Make sure session is set before querying
session(['current_organisation_id' => 1]);
$records = Model::all();
```

### Problem: Can see other org's data

**Diagnosis**: Bypass method used accidentally

**Fix**:
```php
// ❌ Wrong
$records = Model::withoutGlobalScopes()->get();

// ✅ Correct (with scoping)
$records = Model::all();

// ✅ OK (explicit org)
$records = Model::forOrganisation(2)->get();
```

### Problem: Migration fails after adding trait

**Diagnosis**: Migration hasn't run yet

**Fix**:
```bash
php artisan migrate
```

### Problem: Observer not auto-filling

**Diagnosis**: organisation_id already set or session is null

**Fix**:
```php
// Make sure session is set
session(['current_organisation_id' => 1]);

// Make sure organisation_id is NOT in the create() call
$record = Model::create([
    'name' => 'Test',
    // Don't set 'organisation_id' here - let observer do it
]);
```

## Summary

| Feature | How It Works | When It Applies |
|---------|-------------|-----------------|
| **Global Scope** | WHERE organisation_id = X | All queries by default |
| **Auto-Fill** | Sets org_id = session on create | Creating records |
| **ignoreTenant()** | Removes global scope | Admin operations |
| **forOrganisation()** | WHERE organisation_id = Y | Specific org queries |
| **forDefaultPlatform()** | WHERE organisation_id = NULL | Platform-level data |
| **belongsToCurrentOrganisation()** | Compares org_id with session | Authorization checks |
| **belongsToOrganisation()** | Compares org_id with parameter | Multi-org operations |

---

**Next**: See [ADDING_TENANCY.md](./ADDING_TENANCY.md) to add this trait to new models.
