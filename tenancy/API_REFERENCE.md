# Multi-Tenancy API Reference

Complete API reference for all multi-tenancy methods and functions.

## Table of Contents

1. [BelongsToTenant Trait](#belongstotenant-trait)
2. [Query Scopes](#query-scopes)
3. [Helper Methods](#helper-methods)
4. [Middleware](#middleware)
5. [Helper Functions](#helper-functions)

---

## BelongsToTenant Trait

Location: `app/Traits/BelongsToTenant.php`

### Overview

The `BelongsToTenant` trait provides automatic tenant scoping for Eloquent models. Add it to any model to enable multi-tenancy.

### Usage

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class YourModel extends Model
{
    use BelongsToTenant;

    protected $fillable = ['organisation_id', 'name', ...];
}
```

---

## Query Scopes

### scopeIgnoreTenant()

Removes the global tenant scope (admin operations only).

```php
// Signature
public function scopeIgnoreTenant(Builder $query): Builder

// Usage
$allRecords = Model::ignoreTenant()->get();
$allRecords = Model::withoutGlobalScopes()->get();  // Equivalent

// Returns
Collection of all records from all tenants, regardless of current session
```

**Use Case**: Admin dashboards, system reports, debugging

**⚠️ Security Warning**: Only use in protected admin operations

**Example**:
```php
// Get all elections from all organizations
$allElections = Election::ignoreTenant()->get();

// Get all users from all organizations
$allUsers = User::ignoreTenant()->get();
```

---

### scopeForOrganisation()

Queries records for a specific organization.

```php
// Signature
public function scopeForOrganisation(Builder $query, $organisationId): Builder

// Usage
$orgRecords = Model::forOrganisation(2)->get();

// Parameters
- $organisationId: int|string - Organisation ID to query

// Returns
Builder instance for chaining, results filtered by organisation_id
```

**Use Case**: Multi-org admin panels, bulk operations for specific tenant

**Example**:
```php
// Get all elections for Organisation 2
$org2Elections = Election::forOrganisation(2)->get();

// Get count of users in Organisation 3
$org3UserCount = User::forOrganisation(3)->count();

// Get first post for Organisation 1
$org1FirstPost = Post::forOrganisation(1)->first();

// Combine with other conditions
$activeElections = Election::forOrganisation(2)
    ->where('status', 'active')
    ->get();
```

---

### scopeForDefaultPlatform()

Queries records with null organisation_id (platform-level data).

```php
// Signature
public function scopeForDefaultPlatform(Builder $query): Builder

// Usage
$platformRecords = Model::forDefaultPlatform()->get();

// Returns
Builder instance for chaining, results where organisation_id IS NULL
```

**Use Case**: System-wide configuration, shared data not specific to any organization

**Example**:
```php
// Get platform-level elections
$systemElections = Election::forDefaultPlatform()->get();

// Get platform-level settings
$settings = SystemSetting::forDefaultPlatform()->get();

// Check if platform settings exist
if (SystemSetting::forDefaultPlatform()->exists()) {
    // Process platform settings
}
```

---

## Helper Methods

### belongsToCurrentOrganisation()

Checks if a record belongs to the currently authenticated organization.

```php
// Signature
public function belongsToCurrentOrganisation(): bool

// Usage
if ($election->belongsToCurrentOrganisation()) {
    // Safe to show/edit
}

// Returns
bool - True if record's organisation_id matches session organisation_id
```

**Use Case**: Authorization checks before displaying/editing

**Example**:
```php
$election = Election::find($id);

if ($election->belongsToCurrentOrganisation()) {
    // User can view/edit
    return $election;
} else {
    // Deny access
    abort(403, 'Not authorized');
}
```

---

### belongsToOrganisation()

Checks if a record belongs to a specific organization.

```php
// Signature
public function belongsToOrganisation($organisationId): bool

// Parameters
- $organisationId: int|string - Organisation ID to check

// Usage
if ($election->belongsToOrganisation(1)) {
    // Belongs to org 1
}

// Returns
bool - True if record's organisation_id matches parameter
```

**Use Case**: Multi-org operations, checking specific tenant ownership

**Example**:
```php
$election = Election::find($id);

// Check if election belongs to org 1
if ($election->belongsToOrganisation(1)) {
    echo "Owned by Organisation 1";
}

// Check multiple orgs
$targetOrgId = 3;
if ($election->belongsToOrganisation($targetOrgId)) {
    echo "Belongs to target org";
}

// Use in logic
$election = Election::withoutGlobalScopes()->find($id);
if ($election && $election->belongsToOrganisation(auth()->user()->organisation_id)) {
    // Safe to operate on this election
}
```

---

## Middleware

### TenantContext Middleware

Location: `app/Http/Middleware/TenantContext.php`

Automatically sets session tenant context on authenticated requests.

```php
// Signature
public function handle($request, $next)

// What it does
- Checks if user is authenticated
- Extracts organisation_id from auth()->user()
- Sets session['current_organisation_id'] to user's organisation_id

// Auto-registers in
- app/Http/Kernel.php (web middleware group)
```

**Usage**: Automatic - no manual invocation needed

**Example Flow**:
```
User logs in (organisation_id = 1)
    ↓
TenantContext middleware runs
    ↓
session(['current_organisation_id' => 1])
    ↓
All subsequent queries filtered by organisation_id = 1
```

**Registration**:
```php
// In app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        // ...
        \App\Http\Middleware\TenantContext::class,
    ],
];
```

---

## Helper Functions

Location: `app/Helpers/tenant.php`

### current_organisation_id()

Get the current organisation ID from session.

```php
// Signature
function current_organisation_id(): int|null

// Usage
$orgId = current_organisation_id();

// Returns
int - Organisation ID from session
null - If session not set
```

**Example**:
```php
$orgId = current_organisation_id();

if ($orgId) {
    // Operation for current org
    $elections = Election::where('organisation_id', $orgId)->get();
} else {
    // No tenant context
    abort(403, 'No tenant context');
}
```

---

### tenant_log()

Log a message with tenant context (creates per-tenant log file).

```php
// Signature
function tenant_log(
    $message,
    $level = 'info',
    $context = []
): void

// Parameters
- $message: string - Log message
- $level: string - Log level (debug, info, notice, warning, error, critical, alert, emergency)
- $context: array - Additional context data

// Usage
tenant_log('Election created', 'info', ['election_id' => $id]);

// Creates log file
storage/logs/tenant_{organisation_id}.log
```

**Example**:
```php
// Log with current tenant context
tenant_log('User voted in election', 'info');

// Log with context
tenant_log('Vote recorded', 'info', [
    'election_id' => $election->id,
    'user_id' => auth()->id(),
]);

// Log error
tenant_log('Vote validation failed', 'error', [
    'reason' => 'Duplicate vote',
    'election_id' => $election->id,
]);
```

**Output Example** (in `storage/logs/tenant_1.log`):
```
[2026-02-19 10:30:45] tenant_1.INFO: User voted in election [] []
[2026-02-19 10:30:46] tenant_1.INFO: Vote recorded {"election_id":1,"user_id":42} []
```

---

### current_tenant_log_file()

Get the current tenant's log file path.

```php
// Signature
function current_tenant_log_file(): string

// Usage
$logFile = current_tenant_log_file();

// Returns
string - Absolute path to current tenant's log file
```

**Example**:
```php
$logFile = current_tenant_log_file();

// Output example:
// /var/www/html/storage/logs/tenant_1.log

// Use to read logs
$logs = file_get_contents($logFile);
echo $logs;
```

---

## Configuration

### Session Key

The session key used for tenant context is:

```php
'current_organisation_id'
```

You can access or set it:

```php
// Get
$orgId = session('current_organisation_id');

// Set
session(['current_organisation_id' => 1]);

// Check
if (session()->has('current_organisation_id')) {
    // Tenant context exists
}

// Forget
session()->forget('current_organisation_id');
```

---

## Global Scope Details

### How Global Scope Works

When you use the `BelongsToTenant` trait, a global scope is registered:

```php
static::addGlobalScope('tenant', function (Builder $query) {
    $query->where('organisation_id', session('current_organisation_id'));
});
```

This scope is automatically applied to:
- `get()` queries
- `all()` queries
- `first()` queries
- `find()` queries
- `count()` queries
- `exists()` queries
- `update()` operations
- `delete()` operations

### Bypass Methods

To bypass the global scope (admin operations):

```php
// Method 1: withoutGlobalScopes()
$all = Model::withoutGlobalScopes()->get();

// Method 2: ignoreTenant() (alias)
$all = Model::ignoreTenant()->get();

// Method 3: Combine with other conditions
$specific = Model::withoutGlobalScopes()
    ->where('organisation_id', 2)
    ->get();
```

---

## Common Patterns

### Pattern 1: Safe Record Access

```php
$record = Model::find($id);

if ($record && $record->belongsToCurrentOrganisation()) {
    // Safe to use
    return $record;
} else {
    abort(403, 'Not found');
}
```

### Pattern 2: Admin Record Access

```php
$record = Model::withoutGlobalScopes()->find($id);

if ($record && $record->belongsToOrganisation(auth()->user()->organisation_id)) {
    // Belongs to user's org
    return $record;
} else {
    abort(403, 'Access denied');
}
```

### Pattern 3: Bulk Operations

```php
// All operations respect tenant scope
$count = Model::update(['status' => 'active']); // Only current tenant
$deleted = Model::delete();                     // Only current tenant
```

### Pattern 4: Relationships

```php
// Parent-child relationships
$election = Election::find($id);
$posts = $election->posts()->get();  // Already scoped by parent

// Reverse relationship
$post = Post::find($id);
$election = $post->election;  // Already verified as same tenant
```

---

## Error Scenarios

### Scenario 1: Session Not Set

```php
session(['current_organisation_id' => null]);

$records = Model::all();  // Returns empty - filters by NULL
// This is expected behavior for platform-level data
```

### Scenario 2: Cross-Tenant Access

```php
session(['current_organisation_id' => 1]);

$record = Model::find($id);  // where id belongs to org 2
// Returns NULL - not found from org 1 perspective
```

### Scenario 3: Bypass Without Permission

```php
// Anyone can call this in code, but it should only be in admin operations
$allRecords = Model::withoutGlobalScopes()->get();

// Security relies on middleware and authorization gates
```

---

## Performance Considerations

### Index Status

All `organisation_id` columns are indexed:

```sql
-- Check if indexed
SHOW INDEX FROM elections WHERE Column_name = 'organisation_id';

-- Example output:
-- Table, Non_unique, Key_name, Seq_in_index, Column_name
-- elections, 1, organisation_id, 1, organisation_id
```

### Query Performance

With index:
```sql
-- Fast (uses index)
SELECT * FROM elections
WHERE organisation_id = 1;
```

Without index:
```sql
-- Slow (full table scan)
SELECT * FROM elections
WHERE organisation_id = 1;  -- No index = O(n)
```

---

## Summary Table

| Method | Purpose | Returns | Admin Only |
|--------|---------|---------|-----------|
| `ignoreTenant()` | Bypass scoping | Builder | ✅ Yes |
| `forOrganisation()` | Query specific tenant | Builder | ❌ No |
| `forDefaultPlatform()` | Query platform data | Builder | ❌ No |
| `belongsToCurrentOrganisation()` | Check current tenant | bool | ❌ No |
| `belongsToOrganisation()` | Check specific tenant | bool | ❌ No |
| `current_organisation_id()` | Get session org_id | int/null | ❌ No |
| `tenant_log()` | Log with tenant context | void | ❌ No |
| `current_tenant_log_file()` | Get log file path | string | ❌ No |

---

**Next**: See [BEST_PRACTICES.md](./BEST_PRACTICES.md) for development best practices.
