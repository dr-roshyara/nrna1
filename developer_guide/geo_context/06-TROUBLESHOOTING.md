# Geography Context - Troubleshooting Guide

## Common Issues & Solutions

---

## Issue 1: "Unknown column 'path' in ltree"

### Symptoms
```
SQLSTATE[42703]: Undefined column: 7 ERROR: column "path" does not exist
```

### Root Cause
The `path` column exists but PostgreSQL ltree operators are being used on it before the extension is loaded.

### Solution

```bash
# Verify ltree extension is enabled
psql -U publicdigit_user -d publicdigit -c "\dx ltree"

# If not shown, enable it:
psql -U publicdigit_user -d publicdigit -c "CREATE EXTENSION IF NOT EXISTS ltree"

# Verify column exists
psql -U publicdigit_user -d publicdigit -c "\d geographic_units"
```

**If still failing:**

1. Drop and recreate the extension:
```sql
DROP EXTENSION IF EXISTS ltree CASCADE;
CREATE EXTENSION ltree;
```

2. Re-run migrations:
```bash
php artisan migrate:fresh --database=pgsql_geo
```

---

## Issue 2: "Operator does not exist: text <@ text"

### Symptoms
```
SQLSTATE[42883]: Undefined function: 7 ERROR: operator does not exist: text <@ text
```

### Root Cause
The `path` column is TEXT type, but ltree operators require ltree type.

### Solution

Convert the column to ltree type:

```php
// Create migration: database/migrations/Geography/Landlord/2026_01_16_000000_convert_path_to_ltree.php
public function up(): void
{
    DB::statement('ALTER TABLE geographic_units ALTER COLUMN path TYPE ltree USING path::ltree');
    DB::statement('CREATE INDEX ON geographic_units USING gist(path)');
}

public function down(): void
{
    DB::statement('DROP INDEX geographic_units_path_idx');
    DB::statement('ALTER TABLE geographic_units ALTER COLUMN path TYPE text');
}
```

```bash
php artisan migrate --database=pgsql_geo
```

---

## Issue 3: "SQLSTATE[42P01]: Undefined table: 'geographic_units'"

### Symptoms
```
SQLSTATE[42P01]: Undefined table: 7 ERROR: relation "geographic_units" does not exist
```

### Root Cause
Migrations haven't been run on the PostgreSQL database, or migrations are running on wrong connection.

### Solution

```bash
# Verify database connection
php artisan tinker
>>> DB::connection('pgsql_geo')->statement('SELECT 1')
# Should return true

# Run migrations explicitly with connection
php artisan migrate --database=pgsql_geo

# Verify tables exist
psql -U publicdigit_user -d publicdigit -c "\dt"
```

---

## Issue 4: "Missing path value when creating unit"

### Symptoms
```
SQLSTATE[23502]: Not null violation: 7 ERROR: null value in column "path"
```

### Root Cause
Service `createUnit()` doesn't generate path correctly, or path is NULL.

### Solution

Verify the path generation logic in `GeographyService::createUnit()`:

```php
// For root level (level 1)
if ($data['level'] === 1) {
    $path = '1';
}

// For non-root levels, parent must exist
if ($data['level'] > 1 && !isset($data['parent_id'])) {
    throw new \InvalidArgumentException('Non-root units must have parent_id');
}

// Generate path from parent
$parent = $this->getUnitById($data['parent_id'], $tenantId);
if (!$parent) {
    throw new \InvalidArgumentException('Parent unit not found');
}

$path = $parent->path . '.' . generateNextSegment($parent->path);
```

**Verify parent exists before creating child:**

```php
// WRONG
$child = $service->createUnit([
    'parent_id' => 'non-existent-id'
]);

// RIGHT
$parent = $service->getUnitById($parentId, $tenantId);
if (!$parent) {
    throw new \Exception('Parent not found');
}

$child = $service->createUnit([
    'parent_id' => $parent->id
]);
```

---

## Issue 5: "Query returns no results for ancestors"

### Symptoms
```
$ancestors = $service->getAncestors($unitId, $tenantId);
// Returns empty collection even though parent exists
```

### Root Cause
Path format is incorrect (uses different delimiter, or starts with 0).

### Solution

Verify path format is correct:

```php
// CORRECT format: 1.12.123.1234 (numbers only, dot-separated, no leading zeros)
$unit->path = '1.12.123.1234'  ✓

// WRONG formats
$unit->path = '1-12-123'         ✗ Wrong delimiter
$unit->path = '01.012.0123'      ✗ Leading zeros
$unit->path = '1.a.12'           ✗ Non-numeric
```

**Fix:**

1. Verify path in database:
```sql
SELECT id, path, CAST(nlevel(path) AS int) as depth FROM geographic_units LIMIT 5;
```

2. If paths are wrong, fix them in a data migration:
```php
// Fix paths with leading zeros
DB::statement("UPDATE geographic_units SET path = CONCAT('1.', SUBSTRING(path, 3))");
```

---

## Issue 6: "Ltree operator @> not finding ancestors"

### Symptoms
```
$ancestors = $service->getAncestors('1.1.1', $tenantId);
// Returns only the unit itself, not parents
```

### Root Cause
Path operator `@>` (contains) is reversed - should be `<@` for descendants.

### Solution

In `EloquentGeoUnitRepository`:

```php
// WRONG - This gets descendants
public function getAncestorsForTenant(string $path, string $tenantId): Collection
{
    return GeoUnit::whereRaw('path <@ ?', [$path])  // ❌ WRONG
        ->get();
}

// RIGHT - This gets ancestors
public function getAncestorsForTenant(string $path, string $tenantId): Collection
{
    return GeoUnit::whereRaw('path @> ?', [$path])  // ✓ RIGHT
        ->where('path', '!=', $path)
        ->get();
}
```

**ltree operators:**
- `path <@ '1.12'` → path is contained by '1.12' (descendants of 1.12)
- `path @> '1.12'` → path contains '1.12' (ancestors of 1.12)

---

## Issue 7: "Cache key format incompatible"

### Symptoms
```
Error: Unsupported operand type(s) for .: "Uuid" and "string"
```

### Root Cause
Cache key generation using `CountryCode` object instead of string.

### Solution

In `GeographyService`:

```php
// WRONG
$cacheKey = "geo:level:{$countryCode}:{$level}:{$tenantId}";

// RIGHT
$cacheKey = "geo:level:{$countryCode->value()}:{$level}:{$tenantId}";
```

---

## Issue 8: "Test fails with 'There is already an active transaction'"

### Symptoms
```
Laravel\Framework\Database\DatabaseTransactionException: There is already an active transaction
```

### Root Cause
PostgreSQL transactions don't nest like MySQL. RefreshDatabase uses transactions which conflict.

### Solution

Override `beginDatabaseTransaction()` in `TestCase.php`:

```php
<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function beginDatabaseTransaction(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            // PostgreSQL doesn't support nested transactions well
            // Skip transaction-based isolation, let RefreshDatabase use migrate:fresh
            return;
        }

        parent::beginDatabaseTransaction();
    }
}
```

---

## Issue 9: "Model returns wrong connection"

### Symptoms
```
// Query goes to MySQL instead of PostgreSQL
GeoUnit::where('id', $id)->get();
// SQLSTATE[42S02]: Table not found: 'geographic_units'
```

### Root Cause
`GeoUnit` model doesn't have `protected $connection = 'pgsql_geo'`.

### Solution

In `app/Models/Geography/GeoUnit.php`:

```php
class GeoUnit extends Model
{
    protected $connection = 'pgsql_geo';  // ← REQUIRED!
    protected $table = 'geographic_units';
    
    // ... rest of model
}
```

Verify:
```php
$unit = new GeoUnit();
echo $unit->getConnectionName();  // Should print: pgsql_geo
```

---

## Issue 10: "Migration runs on wrong database"

### Symptoms
```
php artisan migrate

# Migrates MySQL instead of PostgreSQL
# geographic_units table not created
```

### Root Cause
Migration doesn't specify `$connection` property.

### Solution

In migration file:

```php
return new class extends Migration
{
    protected $connection = 'pgsql_geo';  // ← REQUIRED!

    public function up(): void
    {
        // ...
    }
};
```

Verify:
```bash
# Run with explicit connection
php artisan migrate --database=pgsql_geo

# Check what migrated
psql -U publicdigit_user -d publicdigit -c "SELECT * FROM migrations WHERE batch = (SELECT MAX(batch) FROM migrations);"
```

---

## Issue 11: "Parent-child constraint violation"

### Symptoms
```
SQLSTATE[23502]: Foreign key constraint "geographic_units_parent_id_foreign" violated
```

### Root Cause
Trying to create child unit with non-existent parent.

### Solution

Always verify parent exists:

```php
// WRONG
$district = $service->createUnit([
    'parent_id' => 'unknown-parent-id'  // Parent doesn't exist!
]);

// RIGHT
$parent = $service->getUnitById($parentId, $tenantId);
if (!$parent) {
    throw new \InvalidArgumentException("Parent {$parentId} not found");
}

$district = $service->createUnit([
    'parent_id' => $parent->id
]);
```

---

## Issue 12: "Cache returns stale data"

### Symptoms
```
// Update a unit
$service->updateUnit($unitId, ['name' => 'New Name'], $tenantId);

// Query returns old name
$unit = $service->getUnitById($unitId, $tenantId);
echo $unit->name;  // Still shows old name!
```

### Root Cause
Cache invalidation doesn't match cache key format.

### Solution

Ensure cache invalidation matches key generation:

```php
// Cache key format
$cacheKey = "geo:unit:{$id}:{$tenantId}";

// Invalidation must be exact
$this->cache->forget($cacheKey);

// If using string interpolation, verify it matches
public function updateUnit(string $id, array $data, string $tenantId): ?GeoUnit
{
    // ... update logic ...
    
    // Invalidate both the unit cache and related caches
    $this->cache->forget("geo:unit:{$id}:{$tenantId}");
    $this->invalidateUnitCaches($tenantId);  // Invalidate ancestor/descendant caches
    
    return $unit;
}
```

---

## Issue 13: "PostgreSQL UUID type mismatch"

### Symptoms
```
SQLSTATE[42883]: Undefined function: 7 ERROR: invalid input syntax for type uuid
```

### Root Cause
Comparing UUID column with non-UUID string (e.g., slug).

### Solution

Check types before querying:

```php
// WRONG - ID column is UUID, but $id might be slug
GeoUnit::where('id', $id)->first();  // UUID vs string

// RIGHT - Verify it's actually UUID
if (!Str::isUuid($id)) {
    return null;  // Not a UUID
}

GeoUnit::where('id', $id)->first();
```

---

## Issue 14: "Depth calculation fails"

### Symptoms
```
// nlevel() function errors
GeoUnit::whereRaw('nlevel(path) = ?', [3])->get();
// SQLSTATE[42883]: Undefined function: nlevel()
```

### Root Cause
ltree extension not properly installed or path column is TEXT not ltree type.

### Solution

1. **Verify extension:**
```bash
psql -U publicdigit_user -d publicdigit -c "\dx ltree"
```

2. **Convert path to ltree type:**
```bash
# Create and run migration
php artisan make:migration ConvertPathToLtreeType --database=pgsql_geo
```

```php
public function up(): void
{
    DB::statement('ALTER TABLE geographic_units ALTER COLUMN path TYPE ltree USING path::ltree');
}
```

3. **Verify it works:**
```bash
psql -U publicdigit_user -d publicdigit -c "SELECT id, nlevel(path) FROM geographic_units LIMIT 1;"
```

---

## Verification Checklist

Before deployment, verify:

```bash
# ✓ Extension is installed
psql -U publicdigit_user -d publicdigit -c "\dx ltree"

# ✓ Tables exist
psql -U publicdigit_user -d publicdigit -c "\dt geographic_units"

# ✓ ltree queries work
psql -U publicdigit_user -d publicdigit -c "SELECT COUNT(*) FROM geographic_units WHERE path <@ '1';"

# ✓ All tests pass
php artisan test tests/Unit/Contexts/Geography
php artisan test tests/Feature/Contexts/Geography

# ✓ No migrations pending
php artisan migrate:status --database=pgsql_geo
```

---

## Performance Debugging

### Slow Query Analysis

```bash
# Enable query logging
LARAVEL_QUERY_LOG=true php artisan migrate --database=pgsql_geo

# Check query execution time
psql -U publicdigit_user -d publicdigit -c "EXPLAIN ANALYZE SELECT * FROM geographic_units WHERE path <@ '1.12';"

# Index status
psql -U publicdigit_user -d publicdigit -c "\d+ geographic_units"
```

### Add GiST Index for Large Datasets

```php
// Migration for performance optimization
DB::statement('CREATE INDEX IF NOT EXISTS idx_geo_path_gist ON geographic_units USING gist(path)');
```

---

## Getting Help

1. **Check logs:**
```bash
tail -f storage/logs/laravel.log
```

2. **Test database directly:**
```bash
psql -U publicdigit_user -d publicdigit

# List tables
\dt

# Check geographic_units
SELECT id, path, nlevel(path) FROM geographic_units LIMIT 5;
```

3. **Verify model configuration:**
```php
php artisan tinker
>>> $unit = \App\Models\Geography\GeoUnit::first();
>>> echo $unit->getConnectionName();  // Should be: pgsql_geo
```

---

## See Also

- PostgreSQL ltree documentation: https://www.postgresql.org/docs/current/ltree.html
- Laravel database connections: https://laravel.com/docs/database#configuration
- PostgreSQL UUID: https://www.postgresql.org/docs/current/datatype-uuid.html

---
