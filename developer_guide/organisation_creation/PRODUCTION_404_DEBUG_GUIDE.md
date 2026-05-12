# Organisation Creation — Production 404 Debugging Guide
# `POST /organisations` → 404 on DigitalOcean, works locally

**Branch:** `postgressql`
**Session Date:** 2026-05-12
**Author:** Debugging session with Claude

---

## Overview

This guide documents the root cause and fix for `POST /organisations` returning 404
in production (DigitalOcean) while working correctly in local development.

**Symptoms:**
- Filling out `/my-organisations/create` and submitting returns a 404 in production
- Locally, the same form creates the organisation and redirects to its show page
- No error is visible in the browser — just a 404 page
- Laravel logs may show an `SQLSTATE` or `QueryException` before the 404

---

## Quick Diagnosis Flowchart

```
POST /organisations → 404 in production?
│
├─ Check production logs first (storage/logs/laravel.log)
│  ├─ "SQLSTATE[42S22]: Unknown column" → Pending migrations (see Bug #1)
│  ├─ "ALTER TABLE ... AFTER status" error → Migration bug (see Bug #2)
│  └─ No log entries at all → Route caching issue (see Cache section)
│
├─ Run php artisan migrate:status in production
│  ├─ Pending migrations present → Run php artisan migrate
│  └─ All migrations run → Check route/config cache
│
└─ Check bootstrap/cache/services.php in git
   └─ If tracked → remove it (see Git section)
```

---

## The Request Path

```
POST /organisations
    │
    ├─ auth middleware
    │
    ├─ OrganisationController::store()
    │      DB::transaction()
    │        Organisation::create([...geography columns...])  ← REQUIRES migrations to have run
    │        UserOrganisationRole::create([...])
    │      redirect()->route('organisations.show', $org->slug)
    │
    └─ GET /organisations/{slug}
           EnsureOrganisationMember middleware
           OrganisationController::show()
           → Inertia render
```

The `store()` method unconditionally passes these columns to `Organisation::create()`:

```php
'committee_structure' => $request->input('committee_structure', 'flat'),
'geographic_scope'    => $request->geographic_scope,
'allowed_countries'   => $request->allowed_countries ?? [],
'base_country_code'   => $request->base_country_code,
'base_region_id'      => $request->base_region_id,
'geographic_levels'   => $geographicLevels,
```

These columns are added by migrations that may not have run in production.

---

## Bug #1 — Pending Geography Migrations (Primary Root Cause)

### Location
`database/migrations/2026_05_04_000001_add_geographic_scope_to_organisations.php`

### What it adds
```sql
ALTER TABLE organisations ADD committee_structure VARCHAR(50) NOT NULL DEFAULT 'flat';
ALTER TABLE organisations ADD geographic_scope VARCHAR(50) NULL;
ALTER TABLE organisations ADD allowed_countries JSON NULL;
ALTER TABLE organisations ADD base_country_code CHAR(2) NULL;
ALTER TABLE organisations ADD base_region_id UUID NULL;
```
Also adds `geographic_levels` (JSON, nullable).

### What happens if it hasn't run
`Organisation::create()` passes these column names in the INSERT. MySQL throws:
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'committee_structure' in 'field list'
```
Laravel catches this as a `QueryException` inside the `DB::transaction()`. The transaction
is rolled back. The exception propagates, Laravel returns a 500. DigitalOcean's nginx may
surface this as a 404 depending on error page configuration.

### Fix
Run `php artisan migrate` in production.

---

## Bug #2 — `->after('status')` in Governance Migration (Blocked `php artisan migrate`)

### Location
`database/migrations/2026_05_07_000001_add_governance_status_to_organisations.php`

### The bug (before fix)
```php
$table->string('governance_status', 30)
    ->default('pending_setup')
    ->after('status')  // BUG: 'status' column does not exist in organisations table
    ->comment('pending_setup, governance_configured, active, suspended');
```

### Why it matters
On MySQL, `ALTER TABLE ... AFTER column_name` fails if `column_name` does not exist:
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'status' in 'organisations'
```
This causes `php artisan migrate` to abort mid-run, leaving ALL subsequent migrations
as "Pending". If this migration runs before `2026_05_04_000001`, the geography columns
never get added and Bug #1 manifests.

### Fix applied (2026-05-12)
Changed `->after('status')` to `->after('languages')` since `languages` is in the
base organisations table created by `2026_03_05_000001_create_uuid_organisations_table.php`.

```php
// BEFORE (broken):
$table->string('governance_status', 30)
    ->default('pending_setup')
    ->after('status')
    ->comment('pending_setup, governance_configured, active, suspended');

// AFTER (fixed):
$table->string('governance_status', 30)
    ->default('pending_setup')
    ->after('languages')
    ->comment('pending_setup, governance_configured, active, suspended');
```

---

## Log-Based Diagnosis

`OrganisationController::store()` has `\Log::info()` calls at each step. Check them
to determine exactly where execution stops.

```bash
# Clear old log, attempt org creation, then grep
> storage/logs/laravel.log && touch storage/logs/laravel.log

# After attempting creation:
grep -E "(Organisation creation|Organisation created|UserOrganisationRole|QueryException|SQLSTATE)" \
    storage/logs/laravel.log
```

### Expected log sequence (success)
```
Organisation creation started
Organisation created: {slug}
UserOrganisationRole created
Redirecting to organisations.show
```

### Failure patterns

| Last log entry | Next step |
|----------------|-----------|
| No entries | Route cache is stale — clear and regenerate |
| "Organisation creation started" only | DB error during `Organisation::create()` — check for SQLSTATE below |
| "Organisation created" but no role entry | `UserOrganisationRole::create()` failed |
| Full sequence but still 404 | `EnsureOrganisationMember` middleware (see Middleware section) |

---

## Production Action Plan

Run these commands on your DigitalOcean server:

```bash
# 1. Pull the migration fix
git pull

# 2. Check which migrations are pending
php artisan migrate:status

# 3. Run pending migrations
php artisan migrate

# 4. Clear all caches
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 5. Regenerate production caches
php artisan route:cache
php artisan config:cache
```

---

## EnsureOrganisationMember Middleware (NOT the 404 source)

`app/Http/Middleware/EnsureOrganisationMember.php` is applied to `GET /organisations/{slug}`.

For standard web requests it does NOT return a 404. It redirects to `dashboard` with
an error message. It returns a JSON 404 only when `$request->expectsJson()` is true
(i.e., direct API calls, not Inertia form submissions).

If you see a redirect to `/dashboard` with "You are not a member of this organisation"
after org creation succeeds, the `UserOrganisationRole` record was not created. Check
the transaction in `store()`.

---

## Route Verification

The `POST /organisations` route is NOT behind `ensure.organisation` middleware.
`GET /organisations/{slug}` IS behind it. Confirmed in `routes/web.php`:

```php
// No ensure.organisation middleware here:
Route::post('/organisations', [OrganisationController::class, 'store'])
    ->name('organisations.store');

// EnsureOrganisationMember only applies to show/edit/update:
Route::middleware('ensure.organisation')->group(function () {
    Route::get('/organisations/{slug}', [OrganisationController::class, 'show'])
        ->name('organisations.show');
    // ...
});
```

No duplicate `organisations.show` route names exist in the codebase.

---

## Git Hygiene: `bootstrap/cache/services.php`

This file is auto-generated by `php artisan package:discover` and should NOT be
committed to git. If it's tracked, deploying it can cause class binding mismatches
in production because it embeds absolute paths from the local machine.

```bash
# Check if tracked
git ls-files bootstrap/cache/services.php

# Remove from tracking
echo "bootstrap/cache/" >> .gitignore
git rm --cached bootstrap/cache/services.php
git rm --cached bootstrap/cache/packages.php 2>/dev/null || true
git commit -m "Remove bootstrap cache files from git tracking"
```

---

## CHECK Constraint Failures (Edge Case)

Migration `2026_05_04_000001` adds DB CHECK constraints:

```sql
-- chk_geographic_consistency: hierarchical/regional must have geographic_scope
-- chk_single_country_has_code: single_country must have base_country_code
-- chk_flat_no_scope:           flat structure must have NULL geographic_scope
```

If you submit the org creation form with an inconsistent combination (e.g.,
`committee_structure = 'hierarchical'` with no `geographic_scope`), the INSERT
will fail with a CHECK constraint violation on MySQL 8+ or PostgreSQL.

Symptom: `SQLSTATE[HY000]: Check constraint 'chk_geographic_consistency' is violated`

Fix: Ensure the Create.vue form validation prevents inconsistent combinations before
submission, or add a server-side check in `OrganisationController::store()`.

---

## Future Prevention

- Never use `->after('column_name')` in a migration without confirming the referenced
  column exists in the base table migration.
- When adding columns that are unconditionally used in a controller, make them nullable
  with a default, or add server-side validation before the `Model::create()` call.
- Always run `php artisan migrate:status` before deploying controller changes that
  reference new columns.
- Add `bootstrap/cache/` to `.gitignore` if not already there.

---

**Last Updated:** 2026-05-12
**Author:** Debugging session (Claude)
**Related Files:**
- `app/Http/Controllers/OrganisationController.php` — `store()` and `show()` methods
- `database/migrations/2026_05_04_000001_add_geographic_scope_to_organisations.php`
- `database/migrations/2026_05_07_000001_add_governance_status_to_organisations.php` (fixed)
- `app/Http/Middleware/EnsureOrganisationMember.php`
- `routes/web.php` — lines 443–530
