# Geography Context - Migrations & Schema

## Overview

Geography migrations are **PostgreSQL-only** and stored in the context migrations folder. They use the `pgsql_geo` connection configured in `config/database.php`.

---

## Directory Structure

```
database/migrations/
└── Geography/
    ├── Landlord/
    │   └── 2026_XX_XX_000000_create_geographic_units_table.php
    └── Tenant/
        └── (future: tenant-specific geography)
```

> **CRITICAL:** Migrations in `Landlord/` subfolder are executed on landlord (PostgreSQL) database only.

---

## Initial Schema Migration

### Create the Geographic Units Table

File: `database/migrations/Geography/Landlord/2026_01_15_000000_create_geographic_units_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'pgsql_geo';

    public function up(): void
    {
        // Enable ltree extension (required for path column)
        DB::statement('CREATE EXTENSION IF NOT EXISTS ltree');

        Schema::create('geographic_units', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            
            // Country ISO 3166-1 alpha-2 code
            $table->string('country_code', 2);
            
            // Hierarchical level (1-8)
            $table->unsignedSmallInteger('level')->index();
            
            // Parent unit ID (nullable for root level 1)
            $table->uuid('parent_id')->nullable()->index();
            
            // Materialized path for efficient hierarchical queries
            // Example: "1.12.123.1234" for deep nesting
            // PostgreSQL ltree type is more efficient than string
            $table->text('path')->index();
            
            // Names (primary language + alternate)
            $table->string('name', 255);
            $table->string('name_alternate', 255)->nullable();
            
            // Official geographic code (if applicable)
            $table->string('code', 50)->nullable();
            
            // Active status
            $table->boolean('is_active')->default(true)->index();
            
            // Timestamps
            $table->timestamps();
            
            // Composite index for tenant + country + level
            $table->index(['tenant_id', 'country_code', 'level']);
            
            // Composite index for tenant + parent
            $table->index(['tenant_id', 'parent_id']);
            
            // Unique constraint: path must be unique per tenant
            $table->unique(['tenant_id', 'path']);
            
            // Foreign key to parent unit
            $table->foreign('parent_id')
                ->references('id')
                ->on('geographic_units')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('geographic_units');
        // Note: Do NOT drop ltree extension - other tables may use it
    }
};
```

---

## Key Schema Decisions

### 1. ltree Extension

PostgreSQL's `ltree` extension is **required** for materialized path queries:

```php
// Install on database creation
DB::statement('CREATE EXTENSION IF NOT EXISTS ltree');
```

**Why ltree?**
- Efficient prefix-based searches: `path <@ '1.12'` finds all descendants
- Efficient ancestry searches: `path @> '1.12.123'` finds all ancestors
- Depth calculation: `nlevel(path)` returns depth instantly
- Better than string concatenation for geographic hierarchies

---

### 2. Path Column Type

```php
// OPTION A: Using ltree type (recommended)
$table->createCommand('ALTER TABLE geographic_units ADD COLUMN path ltree');

// OPTION B: Using text (fallback, slower queries)
$table->text('path')->index();
```

**Recommendation:** Use text column during initial setup, migrate to ltree type after:

```php
// In separate migration after table exists
DB::statement('ALTER TABLE geographic_units ALTER COLUMN path TYPE ltree USING path::ltree');
DB::statement('CREATE INDEX ON geographic_units USING gist(path)');
```

---

### 3. Indexes for Performance

```php
// Composite indexes for common queries
$table->index(['tenant_id', 'country_code', 'level']);
$table->index(['tenant_id', 'parent_id']);

// Unique path per tenant
$table->unique(['tenant_id', 'path']);

// ltree GiST index (if using ltree type)
// DB::statement('CREATE INDEX ON geographic_units USING gist(path)');
```

---

## Seeding Geographic Data

### Seed Nepal Hierarchy (Example)

File: `database/seeders/Geography/NepalGeographySeeder.php`

```php
<?php

namespace Database\Seeders\Geography;

use App\Models\Geography\GeoUnit;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class NepalGeographySeeder extends Seeder
{
    public function run(): void
    {
        $tenantId = Uuid::uuid4()->toString(); // Production: get from tenant context

        // Level 1: Country (Nepal)
        $nepal = GeoUnit::create([
            'id' => Uuid::uuid4()->toString(),
            'tenant_id' => $tenantId,
            'country_code' => 'NP',
            'level' => 1,
            'parent_id' => null,
            'path' => '1',
            'name' => 'Nepal',
            'code' => 'NP',
        ]);

        // Level 2: Provinces
        $province1 = GeoUnit::create([
            'id' => Uuid::uuid4()->toString(),
            'tenant_id' => $tenantId,
            'country_code' => 'NP',
            'level' => 2,
            'parent_id' => $nepal->id,
            'path' => '1.1',
            'name' => 'Province 1',
            'code' => 'P1',
        ]);

        // Level 3: Districts
        $district1 = GeoUnit::create([
            'id' => Uuid::uuid4()->toString(),
            'tenant_id' => $tenantId,
            'country_code' => 'NP',
            'level' => 3,
            'parent_id' => $province1->id,
            'path' => '1.1.1',
            'name' => 'Kathmandu',
            'code' => 'KTM',
        ]);

        // Level 4: Municipalities
        GeoUnit::create([
            'id' => Uuid::uuid4()->toString(),
            'tenant_id' => $tenantId,
            'country_code' => 'NP',
            'level' => 4,
            'parent_id' => $district1->id,
            'path' => '1.1.1.1',
            'name' => 'Kathmandu Metropolitan City',
            'code' => 'KMC',
        ]);

        // ... continue for all provinces, districts, municipalities
    }
}
```

---

## Migration Commands

### Running Migrations

```bash
# Run all migrations (will use pgsql_geo connection)
php artisan migrate --database=pgsql_geo

# Run specific migration file
php artisan migrate --path=database/migrations/Geography/Landlord

# Rollback
php artisan migrate:rollback --database=pgsql_geo

# Fresh start (⚠️ deletes all data)
php artisan migrate:fresh --database=pgsql_geo
```

---

## Testing Migrations

Before production deployment:

```bash
# Test with PostgreSQL test database
DB_PGSQL_GEO_DATABASE=publicdigit_test php artisan migrate:fresh --database=pgsql_geo

# Verify schema
psql -U publicdigit_user -d publicdigit_test -c "\d geographic_units"
```

Expected output:
```
                 Table "public.geographic_units"
      Column      |              Type              
------------------+------------------------------------------------------------
 id               | uuid
 tenant_id        | uuid
 country_code     | character varying(2)
 level            | smallint
 parent_id        | uuid
 path             | text
 name             | character varying(255)
 name_alternate   | character varying(255)
 code             | character varying(50)
 is_active        | boolean
 created_at       | timestamp(0) without time zone
 updated_at       | timestamp(0) without time zone
```

---

## Query Examples

### Find All Descendants

```php
// Find all units under "Province 1" (path = '1.1')
$descendants = GeoUnit::whereRaw("path <@ ?", ['1.1'])->get();

// SQL generated:
// SELECT * FROM geographic_units WHERE path <@ '1.1'
```

### Find All Ancestors

```php
// Find all parents of "Municipality 1" (path = '1.1.1.1')
$ancestors = GeoUnit::whereRaw("path @> ?", ['1.1.1.1'])->get();

// SQL generated:
// SELECT * FROM geographic_units WHERE path @> '1.1.1.1'
```

### Find at Specific Level

```php
// Find all provinces (level 2)
$provinces = GeoUnit::whereRaw('nlevel(path) = ?', [2])->get();

// SQL generated:
// SELECT * FROM geographic_units WHERE nlevel(path) = 2
```

### Direct Children Only

```php
// Find districts under a province
$districts = $province->children()->get();
// OR raw query:
$districts = GeoUnit::where('parent_id', $province->id)->get();
```

---

## Migration Checkpoints

| Checkpoint | Command | Verification |
|-----------|---------|--------------|
| Extension enabled | `CREATE EXTENSION IF NOT EXISTS ltree` | `\dx` lists `ltree` |
| Table created | `migrate --database=pgsql_geo` | `\dt` shows table |
| Indexes created | Migration auto-creates | `\di` shows indexes |
| Data seeded | `db:seed --class=NepalGeographySeeder` | `SELECT COUNT(*) FROM geographic_units` |

---

## Future Enhancements

### 1. Convert Path to Native ltree Type

When performance tuning is complete:

```php
// Migration to convert text → ltree
DB::statement('ALTER TABLE geographic_units ALTER COLUMN path TYPE ltree USING path::ltree');
```

### 2. Add GiST Index

For millions of rows:

```php
// GiST index for ultra-fast hierarchical queries
DB::statement('CREATE INDEX ON geographic_units USING gist(path)');
```

---

## Next Steps

→ See `04-SERVICE.md` for GeographyService implementation

---
