---
name: geo-units-shared-reference-data
description: "Geo units (countries, provinces, districts) are global reference data, not tenant-scoped. Only governance level definitions are tenant-specific."
metadata: 
  node_type: memory
  type: feedback
  originSessionId: c58c462c-c198-465e-89b3-69a6bfe193fb
---

# Geo Units Architecture: Shared Reference Data

## The Pattern

| Entity | Tenant-Scoped? | Reason |
|--------|----------------|--------|
| `geo_administrative_units` | ❌ NO | Countries, provinces, districts are geographic facts shared across all organizations |
| `governance_level_definitions` | ✅ YES | Each tenant configures which geo levels are relevant to their governance structure |
| `countries` | ❌ NO | Global reference data |

## Why This Matters

**Incorrect approach:**
```php
// ❌ WRONG: Filtering geo units by organisation_id
$units = DB::table('geo_administrative_units')
    ->where('organisation_id', $tenantId)
    ->get();
// Returns 0 rows if seeding didn't set organisation_id on geo units
```

**Correct approach:**
```php
// ✅ RIGHT: Geo units are global; filter by governance levels only
$adminLevels = $this->resolveAdminLevels($governanceLevels);
$units = DB::table('geo_administrative_units')
    ->whereIn('admin_level', $adminLevels)  // Tenant-specific filtering
    ->where('is_active', true)              // No organisation_id filter
    ->get();
```

## Practical Example

```
Two organizations in Nepal:
  - Org A: Governance covers [country, province, district] (levels 0-2)
  - Org B: Governance covers [country, province] only (levels 0-1)

Geography:
  - "Nepal" (level 0) — SHARED by both orgs
  - "Kathmandu Province" (level 1) — SHARED by both orgs
  - "Kathmandu District" (level 2) — EXISTS in DB, but Org B doesn't USE it

Solution:
  - Don't filter by organisation_id
  - Load Org A's governance levels → [0, 1, 2] → show all
  - Load Org B's governance levels → [0, 1] → show only levels 0-1 from same shared table
```

## Implementation Pattern

1. **Load governance levels for tenant** (tenant-scoped)
   ```php
   $governanceLevels = DB::table('governance_level_definitions')
       ->where('tenant_id', $tenantId)
       ->get();
   ```

2. **Resolve which admin levels those governance levels map to** (geospatial mapping)
   ```php
   $adminLevels = $this->resolveAdminLevels($governanceLevels);
   // e.g., ['CONT' → 0, 'COUNTRY' → 1, 'PROV' → 2]
   ```

3. **Filter geo units by those admin levels ONLY** (no organisation_id)
   ```php
   $units = DB::table('geo_administrative_units')
       ->whereIn('admin_level', $adminLevels)
       ->where('is_active', true)
       ->get();
   ```

## Files Fixed (2026-05-15)

- **GeoUnitController.php**: Renamed `getTenantId()` → `getTenantIdForGovernance()` to clarify purpose
- **GovernanceGeoUnitQueryService.php**: Removed 4 `WHERE organisation_id = $tenantId` filters:
  - Line 76: `fetchFlat()` method
  - Line 139: `breadcrumb()` method  
  - Line 155: `breadcrumb()` ancestor query
  - Line 204: `lookup()` method

## Result

All 13 tests in `GeoUnitControllerTest` now pass ✅

## Why: Historic Context

The controller initially tried to scope geo units by tenant because the seeding didn't set organisation_id on geo units. Instead of fixing the seeding, it's architecturally correct to NOT filter by organisation_id at all—geo units are reference data.

If a future need arises to create tenant-specific geo units (e.g., custom administrative divisions), that would require a different table or a boolean flag, not simply adding organisation_id and filtering by it.
