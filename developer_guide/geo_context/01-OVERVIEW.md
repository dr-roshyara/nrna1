# Geography Context - Developer Guide

## Overview

The **Geography Context** is responsible for managing geographic hierarchies across the Public Digit platform. It uses **PostgreSQL with the ltree extension** for efficient hierarchical queries.

### Architecture: Two-Database Setup (Path 2)

```
┌─────────────────────────────────────────────────────────┐
│                 PUBLIC DIGIT PLATFORM                    │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  ┌──────────────────────────┐   ┌───────────────────┐   │
│  │   MAIN APPLICATION       │   │   GEOGRAPHY       │   │
│  │  (Users, Elections,      │   │  (Hierarchies,    │   │
│  │   Votes, Members,        │   │   ltree, Regions) │   │
│  │   Newsletters)           │   │                   │   │
│  │                          │   │                   │   │
│  │  Database: MySQL         │   │ Database: PgSQL   │   │
│  │  Connection: default     │   │ Connection: geo   │   │
│  └──────────────────────────┘   └───────────────────┘   │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

---

## Why Two Databases?

| Reason | Benefit |
|--------|---------|
| **PostgreSQL ltree** | Efficient hierarchical queries (path containment, depth) |
| **MySQL stability** | Main app uses proven MySQL setup |
| **Separation of concerns** | Geography is isolated, independent service |
| **Future flexibility** | Can consolidate to single PostgreSQL when performance is tuned |

---

## Quick Start

### 1. Database Configuration

Both databases are pre-configured in `config/database.php`:

```php
'connections' => [
    'mysql' => [...],       // Main app (default)
    'pgsql_geo' => [...],   // Geography only
]
```

### 2. Environment Setup

```bash
# .env - Main application (MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nrna_de
DB_USERNAME=nrna
DB_PASSWORD=your_password

# Geography database (PostgreSQL)
DB_PGSQL_GEO_HOST=127.0.0.1
DB_PGSQL_GEO_PORT=5432
DB_PGSQL_GEO_DATABASE=publicdigit
DB_PGSQL_GEO_USERNAME=publicdigit_user
DB_PGSQL_GEO_PASSWORD=your_password
```

### 3. Enable ltree Extension

```sql
-- Run once on PostgreSQL database
CREATE EXTENSION IF NOT EXISTS ltree;
```

### 4. Create Geography Models

```php
namespace App\Models\Geography;

use Illuminate\Database\Eloquent\Model;

class GeographicUnit extends Model
{
    protected $connection = 'pgsql_geo';  // Force PostgreSQL connection
    protected $table = 'geographic_units';
    
    protected $fillable = [
        'organisation_id',
        'parent_id',
        'level',
        'code',
        'name',
        'name_nepali',
        'country_code',
        'path',  // ltree column
    ];
}
```

---

## Key Concepts

### ltree (Materialized Path)

PostgreSQL's `ltree` extension provides **fast hierarchical queries** using a path notation:

```
Nepal
├── Province 1 (path: 1)
│   ├── District 1 (path: 1.1)
│   │   └── Municipality 1 (path: 1.1.1)
├── Province 2 (path: 2)
│   └── District 2 (path: 2.1)
```

### Path Operations

```php
// Find all descendants of a path
GeographicUnit::whereRaw("path <@ ?", ['1.1'])->get();

// Find all ancestors of a path
GeographicUnit::whereRaw("path @> ?", ['1.1.1'])->get();

// Find direct children
GeographicUnit::where('parent_id', $id)->get();

// Find by depth
GeographicUnit::whereRaw("nlevel(path) = ?", [3])->get();
```

---

## Current Status

| Feature | Status | Notes |
|---------|--------|-------|
| **Two-database config** | ✅ Complete | MySQL (main) + PostgreSQL (geo) |
| **Models** | 🚧 Pending | Need to create GeographicUnit model |
| **Migrations** | 🚧 Pending | Need to create schema with ltree |
| **Services** | 🚧 Pending | GeographyService with path queries |
| **Tests** | 🚧 Pending | Unit & integration tests |

---

## Next Steps

1. **Create Geography Models** → See `02-MODELS.md`
2. **Write Migrations** → See `03-MIGRATIONS.md`
3. **Implement GeographyService** → See `04-SERVICE.md`
4. **Add Tests** → See `05-TESTING.md`

---

## Future: Single Database Consolidation

When PostgreSQL performance is tuned (estimated 1-2 weeks):

```bash
# 1. Fix PostgreSQL performance issues
# 2. Migrate MySQL data to PostgreSQL
# 3. Update config/database.php
DB_CONNECTION=pgsql

# 4. Remove MySQL (if desired)
# Everything now runs on PostgreSQL
```

All code written for Path 2 is **forward-compatible** with single PostgreSQL.

---

## Support

- **Config questions**: See `config/database.php`
- **Model patterns**: See `02-MODELS.md`
- **Query examples**: See `04-SERVICE.md`
- **Troubleshooting**: See `06-TROUBLESHOOTING.md`
