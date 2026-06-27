# Phase 8C.2E — Canonical String Serialization (Read-Optimized Geo Projection)

## Problem

The `committees` table stores `geo_unit_id`, `region_code`, and `country_code` as separate columns. Querying "find all committees in Asia" requires joins to `geo_administrative_units` with materialized path traversal — expensive and complex for what should be a simple filter.

## Solution

Add a **single denormalized string column** — `canonical_geo_id` — that captures the complete geographic hierarchy as a deterministic, queryable string.

```
Format:  region:{region}.country:{country}.geo:{geoUnitId}
Example: region:asia.country:NP.geo:7
```

This is a **read-optimized projection** (NOT an authoritative source of truth).

## Architecture

```
┌──────────────────────────────────────────────────────────────────┐
│                   CommitteeModel (Eloquent)                       │
│  geo_unit_id │ region_code │ country_code │ canonical_geo_id     │
│  (authoritative)            (authoritative)   (read projection)  │
└──────────────────────────────────────────────────────────────────┘
         ▲                                        ▲
         │ persist() writes                      │ written by
         │ via repository                        │ serializer
         │                                       │
┌──────────────────────────────────────┐  ┌──────────────────────┐
│ EloquentCommitteeAggregateRepository │──│ CanonicalGeoSerializer│
│                                      │  │ (zero dependencies)  │
│ mapToDomain() reads individual cols  │  └──────────────────────┘
│ NEVER deserializes canonical_geo_id  │
└──────────────────────────────────────┘
```

### Key rules

1. **`canonical_geo_id` is NEVER authoritative** — `geo_unit_id` + individual columns are the source of truth
2. **Serializer has ZERO dependencies** — pure string transformation, no DB calls, no service resolution
3. **Read path ignores `canonical_geo_id`** — aggregate reconstruction uses individual columns. If `canonical_geo_id` is NULL, everything still works
4. **No domain logic depends on it** — it's a query optimization only

## Files Created/Modified

| File | Action | Purpose |
|------|--------|---------|
| `app/Contexts/Membership/Infrastructure/Services/CanonicalGeoSerializer.php` | **New** | Pure string serializer |
| `app/Contexts/Membership/Infrastructure/Services/CanonicalGeoIdentityData.php` | **New** | DTO for deserialization output |
| `database/migrations/2026_05_13_000003_add_canonical_geo_id_to_committees.php` | **New** | Add column + index |
| `app/Contexts/Membership/Infrastructure/Models/CommitteeModel.php` | Modified | Added `canonical_geo_id` to `$fillable` |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeAggregateRepository.php` | Modified | Inject serializer, write canonical_geo_id on persist |
| `app/Console/Commands/BackfillCanonicalGeoIds.php` | **New** | Backfill existing rows |
| `tests/Unit/Contexts/Membership/Infrastructure/Services/CanonicalGeoSerializerTest.php` | **New** | 14 serializer tests |
| `tests/Feature/Membership/CanonicalGeoProjectionTest.php` | **New** | 5 integration tests |

## Step-by-Step Implementation

### Step 1 — DTO (`CanonicalGeoIdentityData`)

A simple `readonly` class to return structured data from `deserialize()`:

```php
final readonly class CanonicalGeoIdentityData
{
    public function __construct(
        public int $geoUnitId,
        public ?string $regionCode = null,
        public ?string $countryCode = null,
    ) {}
}
```

### Step 2 — Serializer (`CanonicalGeoSerializer`)

Zero-dependency class with two methods:

**`serialize(?int $geoUnitId, ?string $regionCode, ?string $countryCode): ?string`**

Returns `null` when `$geoUnitId` is null. Omits region/country segments when those values are null.

```php
serialize(7, 'asia', 'NP')     → 'region:asia.country:NP.geo:7'
serialize(7, null, 'NP')       → 'country:NP.geo:7'
serialize(7, 'asia', null)     → 'region:asia.geo:7'
serialize(7, null, null)       → 'geo:7'
serialize(null, 'asia', 'NP')  → null
```

**`deserialize(?string $value): ?CanonicalGeoIdentityData`**

Returns `null` for null/empty/invalid input. Unknown keys are silently ignored.

```php
deserialize('region:asia.country:NP.geo:7')
  → CanonicalGeoIdentityData(geoUnitId: 7, regionCode: 'asia', countryCode: 'NP')

deserialize('foo:bar.geo:7')
  → CanonicalGeoIdentityData(geoUnitId: 7, regionCode: null, countryCode: null)
```

### Step 3 — Database Migration

```php
Schema::table('committees', function (Blueprint $table) {
    $table->string('canonical_geo_id', 750)
        ->nullable()
        ->after('geo_unit_id');

    $table->index('canonical_geo_id', 'idx_committees_canonical_geo');
});
```

### Step 4 — Repository Integration

Inject `CanonicalGeoSerializer` into `EloquentCommitteeAggregateRepository`:

```php
public function __construct(
    private readonly CanonicalGeoSerializer $serializer,
) {}
```

Write `canonical_geo_id` during `persist()`:

```php
'canonical_geo_id' => $this->serializer->serialize(
    $committee->getGeoUnitId(),
    $committee->getRegionCode(),
    $committee->getCountryCode(),
),
```

**Do NOT change `mapToDomain()`** — the read path continues to use individual columns (`geo_unit_id`, `region_code`, `country_code`).

### Step 5 — DI Binding

No binding needed. `CanonicalGeoSerializer` has zero dependencies, so Laravel's auto-injection resolves it automatically when type-hinted in the repository constructor.

### Step 6 — Backfill Command

```bash
php artisan app:backfill-canonical-geo-ids
```

Chunks by 200 rows, wraps each batch in a transaction, skips rows that already have `canonical_geo_id`.

## Testing

```bash
# Unit tests (serializer logic)
php artisan test tests/Unit/Contexts/Membership/Infrastructure/Services/CanonicalGeoSerializerTest.php

# Integration tests (DB + repository)
php artisan test tests/Feature/Membership/CanonicalGeoProjectionTest.php
```

### Integration test coverage

- `test_persist_stores_canonical_geo_id` — full geo → string written correctly
- `test_persist_sets_null_when_no_geo` — null geo → canonical_geo_id is null
- `test_find_by_id_returns_committee_with_geo_identity` — read path returns values
- `test_legacy_records_without_canonical_geo_id_still_load` — backward compat
- `test_canonical_format_follows_expected_pattern` — format validation

## Deployment Order

1. Deploy migration (adds nullable column — zero downtime)
2. Deploy code changes (accepts null `canonical_geo_id` gracefully)
3. Run backfill command (fills existing rows)
4. New rows get `canonical_geo_id` automatically on insert/update

Any code can be deployed before the backfill runs — null `canonical_geo_id` is always valid.

## Usage in Queries

Use `canonical_geo_id` with `LIKE` for prefix matching (coarse index only — no full-text search):

```php
// Find all committees in Asia
CommitteeModel::where('canonical_geo_id', 'LIKE', 'region:asia.%')->get();

// Find all committees in India
CommitteeModel::where('canonical_geo_id', 'LIKE', 'region:asia.country:IN.%')->get();

// Find committee for specific geo unit
CommitteeModel::where('canonical_geo_id', 'geo:7')->get();
// or partial match
CommitteeModel::where('canonical_geo_id', 'LIKE', '%.geo:7')->get();
```
