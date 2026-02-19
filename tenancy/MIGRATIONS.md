# Multi-Tenancy Migrations Guide

Complete guide to writing and managing migrations for multi-tenant models.

## Overview

When adding multi-tenancy to a model, you need to:
1. Create a migration adding the `organisation_id` column
2. Ensure the column is indexed
3. Make it nullable (for platform-level data)
4. Position it correctly in the table

## Migration Template

Use this template for all tenant-scoped models:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganisationIdToElectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('elections', function (Blueprint $table) {
            // Check to prevent errors if migration runs twice
            if (!Schema::hasColumn('elections', 'organisation_id')) {
                $table->unsignedBigInteger('organisation_id')
                      ->nullable()
                      ->after('id')
                      ->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('elections', function (Blueprint $table) {
            // Safe removal - check before dropping
            if (Schema::hasColumn('elections', 'organisation_id')) {
                // Drop index first to avoid foreign key issues
                $table->dropIndex(['organisation_id']);
                // Then drop column
                $table->dropColumn('organisation_id');
            }
        });
    }
}
```

## Column Properties Explained

```php
$table->unsignedBigInteger('organisation_id')
      ->nullable()           // Allow NULL for platform-level data
      ->after('id')          // Position right after id
      ->index();             // Create index for fast lookups
```

### Property Details

| Property | Purpose | Why |
|----------|---------|-----|
| `unsignedBigInteger` | Type matching organisations table | Consistency with foreign key |
| `->nullable()` | Allow NULL values | Platform users can have NULL |
| `->after('id')` | Position in table | Standard practice |
| `->index()` | Create database index | Fast WHERE organisation_id = X |

## Creating Migrations

### Method 1: Using Artisan Command

```bash
php artisan make:migration add_organisation_id_to_elections_table --table=elections
```

This creates: `database/migrations/2026_02_19_XXXXXX_add_organisation_id_to_elections_table.php`

Then edit the migration to follow the template above.

### Method 2: Manual Creation

Create file in `database/migrations/` with name pattern:
```
YYYY_MM_DD_HHMMSS_add_organisation_id_to_table_name_table.php
```

Example:
```
2026_02_19_185532_add_organisation_id_to_elections_table.php
```

## Running Migrations

### Run All Pending
```bash
php artisan migrate
```

Output:
```
Migrating: 2026_02_19_185532_add_organisation_id_to_elections_table
Migrated: 2026_02_19_185532_add_organisation_id_to_elections_table
```

### Run Specific Migration
```bash
php artisan migrate --path=database/migrations/2026_02_19_185532_add_organisation_id_to_elections_table.php
```

### Check Status
```bash
php artisan migrate:status
```

Output:
```
Migration name                                                          Batch  Ran?
2026_02_19_185532_add_organisation_id_to_elections_table              1      Yes
```

### Rollback
```bash
# Rollback last batch
php artisan migrate:rollback

# Rollback all
php artisan migrate:reset

# Refresh (reset + migrate)
php artisan migrate:refresh
```

## Advanced Migration Patterns

### Pattern 1: Migrating Existing Data

If the table already has data and you want to assign it to an organization:

```php
public function up()
{
    Schema::table('elections', function (Blueprint $table) {
        if (!Schema::hasColumn('elections', 'organisation_id')) {
            $table->unsignedBigInteger('organisation_id')
                  ->nullable()
                  ->after('id')
                  ->index();
        }
    });

    // Assign all existing records to a default organization
    DB::table('elections')->update(['organisation_id' => 1]);
}

public function down()
{
    Schema::table('elections', function (Blueprint $table) {
        if (Schema::hasColumn('elections', 'organisation_id')) {
            $table->dropIndex(['organisation_id']);
            $table->dropColumn('organisation_id');
        }
    });
}
```

### Pattern 2: Foreign Key Constraint

If you want to enforce referential integrity:

```php
public function up()
{
    Schema::table('elections', function (Blueprint $table) {
        if (!Schema::hasColumn('elections', 'organisation_id')) {
            $table->unsignedBigInteger('organisation_id')
                  ->nullable()
                  ->after('id')
                  ->index();

            // Add foreign key constraint
            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('cascade');  // Delete elections if org deleted
        }
    });
}

public function down()
{
    Schema::table('elections', function (Blueprint $table) {
        if (Schema::hasColumn('elections', 'organisation_id')) {
            // Drop foreign key first
            $table->dropForeign(['organisation_id']);
            $table->dropIndex(['organisation_id']);
            $table->dropColumn('organisation_id');
        }
    });
}
```

### Pattern 3: Multiple Foreign Keys

For complex scenarios with multiple tenant-scoped columns:

```php
public function up()
{
    Schema::table('votes', function (Blueprint $table) {
        // Add organisation_id for tenant scoping
        if (!Schema::hasColumn('votes', 'organisation_id')) {
            $table->unsignedBigInteger('organisation_id')
                  ->nullable()
                  ->after('id')
                  ->index();
        }

        // Add foreign key reference
        if (!Schema::hasColumn('votes', 'organisation_id')) {
            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations');
        }
    });
}

public function down()
{
    Schema::table('votes', function (Blueprint $table) {
        if (Schema::hasColumn('votes', 'organisation_id')) {
            $table->dropForeign(['organisation_id']);
            $table->dropIndex(['organisation_id']);
            $table->dropColumn('organisation_id');
        }
    });
}
```

### Pattern 4: Disabling Foreign Key Checks

If you encounter foreign key constraint errors:

```php
public function up()
{
    // Temporarily disable FK checks
    DB::statement('SET FOREIGN_KEY_CHECKS=0');

    Schema::table('elections', function (Blueprint $table) {
        if (!Schema::hasColumn('elections', 'organisation_id')) {
            $table->unsignedBigInteger('organisation_id')
                  ->nullable()
                  ->after('id')
                  ->index();
        }
    });

    // Re-enable FK checks
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
}

public function down()
{
    DB::statement('SET FOREIGN_KEY_CHECKS=0');

    Schema::table('elections', function (Blueprint $table) {
        if (Schema::hasColumn('elections', 'organisation_id')) {
            $table->dropIndex(['organisation_id']);
            $table->dropColumn('organisation_id');
        }
    });

    DB::statement('SET FOREIGN_KEY_CHECKS=1');
}
```

## Migration Naming Conventions

Use consistent naming:

```
add_organisation_id_to_{table_name}_table.php
```

Examples:
```
add_organisation_id_to_elections_table.php
add_organisation_id_to_posts_table.php
add_organisation_id_to_votes_table.php
add_organisation_id_to_candidacies_table.php
```

## Verification After Migration

After running a migration, verify the changes:

```bash
php artisan tinker
```

```php
// Check column exists
> Schema::hasColumn('elections', 'organisation_id')
=> true

// Check column properties
> $columns = DB::select("SHOW COLUMNS FROM elections")
> dd($columns)
// Should show organisation_id column

// Check index exists
> $indexes = DB::select("SHOW INDEX FROM elections WHERE Column_name = 'organisation_id'")
> dd($indexes)
// Should show index exists

// Test querying
> session(['current_organisation_id' => 1])
> Election::create(['name' => 'Test'])
> $e = Election::find(1)
> $e->organisation_id
=> 1
```

## Database Schema

After migration, the table should look like:

```sql
CREATE TABLE elections (
    id UNSIGNED BIGINT PRIMARY KEY AUTO_INCREMENT,
    organisation_id UNSIGNED BIGINT NULL,
    name VARCHAR(255),
    status VARCHAR(50),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    INDEX idx_organisation_id (organisation_id),
    FOREIGN KEY (organisation_id) REFERENCES organisations(id)
);
```

## Idempotency

Always make migrations idempotent (safe to run multiple times):

```php
// ✅ GOOD - Safe to run multiple times
public function up()
{
    Schema::table('elections', function (Blueprint $table) {
        if (!Schema::hasColumn('elections', 'organisation_id')) {  // ← Check first
            $table->unsignedBigInteger('organisation_id')
                  ->nullable()
                  ->after('id')
                  ->index();
        }
    });
}

// ❌ BAD - Errors if run twice
public function up()
{
    Schema::table('elections', function (Blueprint $table) {
        $table->unsignedBigInteger('organisation_id')  // No check!
              ->nullable()
              ->after('id')
              ->index();
    });
}
```

## Error Handling

### Error: Column Already Exists

```
SQLSTATE[42S21]: Column already exists
```

**Cause**: Migration ran twice or column manually added.

**Fix**: Check for column before adding:
```php
if (!Schema::hasColumn('elections', 'organisation_id')) {
    // Add column
}
```

### Error: Foreign Key Constraint Fails

```
SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update
```

**Cause**: Parent record doesn't exist.

**Fix**: Disable FK checks or set nullable:
```php
$table->unsignedBigInteger('organisation_id')
      ->nullable()  // ← Allow NULL
      ->index();
```

### Error: Index Already Exists

```
SQLSTATE[HY000]: General error: 1061 Duplicate key name
```

**Cause**: Migration tried to create duplicate index.

**Fix**: Check before creating:
```php
if (!Schema::hasIndexes('elections')['organisation_id']) {
    $table->index('organisation_id');
}
```

## Rolling Back Migrations

### Safe Rollback

```bash
# Rollback last batch
php artisan migrate:rollback

# Rollback 1 step
php artisan migrate:rollback --step=1

# Rollback all
php artisan migrate:reset
```

**Always provide a proper `down()` method**:
```php
public function down()
{
    Schema::table('elections', function (Blueprint $table) {
        if (Schema::hasColumn('elections', 'organisation_id')) {
            $table->dropIndex(['organisation_id']);
            $table->dropColumn('organisation_id');
        }
    });
}
```

## Testing Migrations

Test migrations before deploying:

```bash
# Migrate fresh
php artisan migrate:fresh

# Rollback and re-migrate
php artisan migrate:rollback
php artisan migrate

# Run in test environment
php artisan migrate --env=testing
```

## Migration Checklist

Before committing a migration:

- [ ] ✅ Uses `Schema::hasColumn()` before adding
- [ ] ✅ Uses `Schema::hasColumn()` before dropping
- [ ] ✅ Column named `organisation_id`
- [ ] ✅ Column type is `unsignedBigInteger`
- [ ] ✅ Column is nullable: `->nullable()`
- [ ] ✅ Column is indexed: `->index()`
- [ ] ✅ Column positioned: `->after('id')`
- [ ] ✅ `down()` method implemented properly
- [ ] ✅ Migration can rollback without errors
- [ ] ✅ No raw SQL (use Schema builder)

## Commonly Used Migrations

### Election Model
```php
Schema::table('elections', function (Blueprint $table) {
    if (!Schema::hasColumn('elections', 'organisation_id')) {
        $table->unsignedBigInteger('organisation_id')
              ->nullable()
              ->after('id')
              ->index();
    }
});
```

### Post Model
```php
Schema::table('posts', function (Blueprint $table) {
    if (!Schema::hasColumn('posts', 'organisation_id')) {
        $table->unsignedBigInteger('organisation_id')
              ->nullable()
              ->after('id')
              ->index();
    }
});
```

### Vote Model
```php
Schema::table('votes', function (Blueprint $table) {
    if (!Schema::hasColumn('votes', 'organisation_id')) {
        $table->unsignedBigInteger('organisation_id')
              ->nullable()
              ->after('id')
              ->index();
    }
});
```

---

## Best Practices

1. **Always check before modifying columns**:
   ```php
   if (!Schema::hasColumn('table', 'column')) {
       // Add
   }
   ```

2. **Index organisation_id columns**:
   ```php
   ->index()  // Must have this!
   ```

3. **Make organisation_id nullable**:
   ```php
   ->nullable()  // For platform-level data
   ```

4. **Position correctly**:
   ```php
   ->after('id')  // Right after id
   ```

5. **Provide complete down() methods**:
   ```php
   public function down()
   {
       // Reverse every change in up()
   }
   ```

6. **Test migrations before merging**:
   ```bash
   php artisan migrate
   php artisan migrate:rollback
   php artisan migrate
   ```

---

## Summary

| Aspect | Standard | Example |
|--------|----------|---------|
| **Column Type** | unsignedBigInteger | `->unsignedBigInteger()` |
| **Nullable** | Yes | `->nullable()` |
| **Index** | Yes | `->index()` |
| **Position** | After id | `->after('id')` |
| **Naming** | Consistent | `organisation_id` |
| **Idempotent** | Always | `if (!Schema::hasColumn(...))` |
| **Rollback** | Complete | Full `down()` implementation |

---

**Done!** Your multi-tenancy migrations are now properly structured and ready for production use.
