# Plan: Tenant Scoping Trait + Axios API Wrapper
**Date:** 2026-05-17  
**Branch:** phase-f3-constitutional-hardening  
**Strategy:** TDD-first (RED → GREEN → REFACTOR)

---

## Context

The API layer now returns clean JSON for all `/api/v1/*` routes. The remaining two risks are:

1. **Backend:** ~22 Eloquent models have `organisation_id` but no automatic tenant scoping. Any raw query against these models can silently return cross-tenant data. The existing `BelongsToTenant` trait (at `app/Traits/BelongsToTenant.php`) handles 15 models but has a session fallback and platform-org cache that adds complexity for strictly API-context models.

2. **Frontend:** Every Vue component manually constructs the same three headers (`X-Organisation-ID`, `Accept`, `X-Requested-With`) and parses raw `fetch()` response streams. This is a DRY violation — one missed header means a broken request.

**What already works and must not break:**
- `BelongsToTenant` on 15 models — leave intact
- `CommitteeMemberProjection` uses `tenant_id` (not `organisation_id`) intentionally — do not add `ScopedByOrganisation` to it
- `CommitteeMemberQueryService` queries `CommitteeMemberProjection` via explicit `tenant_id` — do not change
- `CommitteeMemberProjectionListener` uses `organisation_id` on `member_directories` (DB::table, not Eloquent) — leave intact

---

## Architecture Decision

`ScopedByOrganisation` is a **stricter, simpler sibling** of `BelongsToTenant`:

| Trait | Location | TenantContext | Session fallback | Platform-org cache | Null behavior |
|-------|----------|--------------|------------------|--------------------|---------------|
| `BelongsToTenant` | `app/Traits/` | ✅ | ✅ | ✅ | Returns all (platform fallback) |
| `ScopedByOrganisation` | `app/Models/Traits/` | ✅ | ❌ | ❌ | **FAILS CLOSED** ❌ throws exception |

`ScopedByOrganisation` is for **API-context models** where a session fallback would silently mask a missing tenant header.

### 🚨 CRITICAL SECURITY BOUNDARY

When `TenantContext::get()` returns `null`:
- ❌ **NEVER** return all tenants
- ❌ **NEVER** silently remove filtering
- ✅ **MUST** throw `MissingTenantContextException`

This is non-negotiable for multi-tenant governance systems.

---

## Phase 1: Backend Trait (TDD)

### Step 1A — Write Tests First (RED)

**File:** `tests/Unit/Models/Traits/ScopedByOrganisationTest.php`

Test cases:
```
✗ it applies organisation_id where clause when TenantContext is set
✗ it throws exception when TenantContext is null (FAIL CLOSED)
✗ it auto-fills organisation_id on create when TenantContext is set
✗ it throws exception on create when TenantContext is null
✗ it does not overwrite organisation_id on create if already set
✗ withoutTenantScope() bypasses the global scope (admin use only)
✗ forTenant($orgId) bypasses scope to query specific tenant
```

These MUST fail before implementation begins. **Critical:** Test that null TenantContext throws, not silently returns data.

### Step 1B — Implement Trait (GREEN)

**File:** `app/Models/Traits/ScopedByOrganisation.php`

```php
<?php

namespace App\Models\Traits;

use App\Services\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * ScopedByOrganisation
 *
 * Strict API-context tenant scoping.
 * FAILS CLOSED: null TenantContext throws exception, never returns unfiltered data.
 *
 * Use this ONLY for API-context models.
 * For web models needing platform-org fallback, use BelongsToTenant instead.
 */
trait ScopedByOrganisation
{
    protected static function bootScopedByOrganisation(): void
    {
        static::addGlobalScope('organisation_scope', function (Builder $builder) {
            $tenantId = TenantContext::get();
            
            // SECURITY: Fail closed — null context must throw, never return unfiltered
            if ($tenantId === null) {
                throw new \RuntimeException(
                    'TenantContext is not set. ' . static::class . ' requires tenant isolation.'
                );
            }
            
            $builder->where(
                $builder->getModel()->getTable() . '.organisation_id',
                $tenantId
            );
        });

        static::creating(function (Model $model) {
            if (empty($model->organisation_id)) {
                $tenantId = TenantContext::get();
                
                // SECURITY: Fail closed on create
                if ($tenantId === null) {
                    throw new \RuntimeException(
                        'Cannot create ' . static::class . ' without TenantContext set.'
                    );
                }
                
                $model->organisation_id = $tenantId;
            }
        });
    }

    /**
     * Bypass tenant scope (admin operations only).
     * @deprecated Use repository pattern for explicit scoping instead.
     */
    public function scopeWithoutTenantScope(Builder $query): Builder
    {
        return $query->withoutGlobalScope('organisation_scope');
    }

    /**
     * Query specific tenant (admin operations only).
     * Bypasses automatic scope to allow admin/reporting queries.
     */
    public function scopeForTenant(Builder $query, string $organisationId): Builder
    {
        return $query->withoutGlobalScope('organisation_scope')
                     ->where($this->getTable() . '.organisation_id', $organisationId);
    }
}
```

### Step 1C — Apply to Critical Governance Models

Models to apply `ScopedByOrganisation` (have `organisation_id`, no current scoping, used in API context):

| Model | File | Why |
|-------|------|-----|
| `ElectionMembership` | `app/Models/ElectionMembership.php` | Governance: voter eligibility boundary |
| `MemberImportJob` | `app/Models/MemberImportJob.php` | Governance: member import scoping |
| `Income` | `app/Models/Income.php` | Financial: cross-tenant risk |
| `Contribution` | `app/Models/Contribution.php` | Financial: cross-tenant risk |

**Models NOT to touch:**
- `Member` — already has `BelongsToTenant` (working)
- `CommitteeMemberProjection` — uses `tenant_id`, not `organisation_id`
- `User`, `OrganisationUser`, `Organisation` — identity layer, not tenant-scoped
- `Election` — 73KB file with complex scoping, handle separately in a dedicated phase

### Step 1D — Run Tests (REFACTOR)

```bash
php artisan test tests/Unit/Models/Traits/ScopedByOrganisationTest.php
```

---

## Phase 2: Frontend Axios Wrapper (TDD)

### Step 2A — Write Tests First (RED)

**File:** `tests/Feature/API/AuthenticationTest.php`

```
✗ unauthenticated request returns 401 JSON (not HTML, not 302)
✗ missing X-Organisation-ID header returns 400 JSON
✗ invalid organisation ID returns 404 JSON  
✗ user without org access returns 403 JSON
✗ valid authenticated request with correct tenant returns 200/201 JSON
```

### Step 2B — Create Axios Instance (GREEN)

**File:** `resources/js/services/api.js`

```javascript
import axios from 'axios';

const apiClient = axios.create({
  baseURL: '/api/v1',
  withCredentials: true,
  headers: {
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  },
});

// Initialize tenant ID during app bootstrap (not in interceptor)
// This avoids Vue composition lifecycle issues with usePage() inside interceptors
export function initializeApiClient(tenantId) {
  if (tenantId) {
    apiClient.defaults.headers.common['X-Organisation-ID'] = tenantId;
  }
}

// Response interceptor: normalise error shapes and emit auth events
apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    const status = error.response?.status;
    const code = error.response?.data?.code;

    if (status === 401 || code === 'UNAUTHENTICATED') {
      // Emit auth expiry event (caller decides redirect/modal/silent-refresh)
      // This is more flexible for future auth strategies
      window.dispatchEvent(new CustomEvent('auth:expired', { detail: { error } }));
    }

    if (status === 400 && code === 'MISSING_TENANT_CONTEXT') {
      console.error('[api] Missing tenant context — check X-Organisation-ID header');
    }

    return Promise.reject(error);
  }
);

export default apiClient;
```

**Bootstrap:** Call during app initialization (in `resources/js/app.js` or Inertia root layout):

```javascript
import { initializeApiClient } from '@/services/api';

const tenantId = usePage().props.organisation?.id 
  ?? usePage().props.auth?.user?.current_organisation_id;
initializeApiClient(tenantId);

// Listen for auth expiry events
window.addEventListener('auth:expired', () => {
  // For now, redirect to login. Future: could be modal, SSO, etc.
  window.location.href = '/login';
});
```

**Benefits:**
- ✅ Avoids `usePage()` inside interceptor
- ✅ Stateless interceptor (deterministic, testable)
- ✅ Flexible auth response (emit event, not hard redirect)
- ✅ Future-proof for modal auth, SSO, mobile apps

### Step 2C — Update CommitteeMemberManager.vue (REFACTOR)

Replace all three raw `fetch()` blocks with `apiClient` calls:

- `fetchMembers()`: `apiClient.get(\`/governance/committees/${props.committeeId}/members\`)`
- `handleAddMember()`: `apiClient.post(\`/governance/committees/${props.committeeId}/members\`, {...})`
- `handleRemoveMember()`: `apiClient.delete(\`/governance/committees/${props.committeeId}/members/${memberId}\`)`

Remove:
- `effectiveTenantId` computed property (header injection moves to interceptor)
- Manual `response.text()` → `JSON.parse()` double-read pattern
- Verbose debug `console.log` statements (keep one error-level log per catch block)

Keep:
- `effectiveOrganisationSlug` — still needed for `members.search` route (not an `/api/v1/*` endpoint)

---

## Phase 3: Integration Tests (Lock Down)

**File:** `tests/Feature/API/TenantScopeTest.php`

```
✗ GET members for committee scoped to correct tenant only
✗ member from tenant A cannot access tenant B's committee members
✗ missing tenant header on GET returns 400
✗ valid tenant context with no committee access returns 404
✗ adding member to committee emits projection event for correct tenant
```

Test base pattern (follow existing `CommitteeMemberApiTest.php`):
- Use `DomainIdFactory::tenant()` for TenantId objects
- Use `actingAs($user)` + `withHeaders(['X-Organisation-ID' => $orgId])`
- Assert `assertStatus(400/401/403/404/200/201)`
- Assert `response->assertJsonStructure(['error', 'code'])`

---

## File Manifest

### New Files
| File | Purpose |
|------|---------|
| `app/Models/Traits/ScopedByOrganisation.php` | Strict API-context tenant scope trait |
| `resources/js/services/api.js` | Centralised Axios instance with tenant interceptor |
| `tests/Unit/Models/Traits/ScopedByOrganisationTest.php` | Unit tests for trait (TDD RED first) |
| `tests/Feature/API/AuthenticationTest.php` | API auth boundary tests |
| `tests/Feature/API/TenantScopeTest.php` | Cross-tenant isolation tests |

### Modified Files
| File | Change |
|------|--------|
| `app/Models/ElectionMembership.php` | Add `use ScopedByOrganisation` |
| `app/Models/MemberImportJob.php` | Add `use ScopedByOrganisation` |
| `app/Models/Income.php` | Add `use ScopedByOrganisation` |
| `app/Models/Contribution.php` | Add `use ScopedByOrganisation` |
| `resources/js/Components/CommitteeMemberManager.vue` | Use `apiClient`, remove manual headers |

### Untouched (Explicitly)
| File | Reason |
|------|--------|
| `app/Traits/BelongsToTenant.php` | Working for 15 models — do not change |
| `app/Models/CommitteeMemberProjection.php` | Uses `tenant_id` intentionally |
| `app/Contexts/Governance/Application/Queries/CommitteeMemberQueryService.php` | Explicit `tenant_id` query — correct |
| `app/Models/Member.php` | Already scoped via `BelongsToTenant` |
| `app/Models/Election.php` | 73KB, complex — separate phase |

---

## Execution Order (TDD Discipline)

```
1. Write ScopedByOrganisationTest.php → all tests RED
2. Implement ScopedByOrganisation.php → tests GREEN
3. Apply trait to 4 models → existing tests still GREEN
4. Write AuthenticationTest.php → tests RED
5. Verify existing bootstrap/app.php handlers make them GREEN (no new code needed)
6. Update CommitteeMemberManager.vue → create api.js first
7. Write TenantScopeTest.php → tests RED/GREEN
```

---

## Verification Checklist

- [ ] `php artisan test tests/Unit/Models/Traits/ScopedByOrganisationTest.php` — all GREEN
- [ ] `php artisan test tests/Feature/API/AuthenticationTest.php` — all GREEN
- [ ] `php artisan test tests/Feature/Governance/Api/CommitteeMemberApiTest.php` — no regressions
- [ ] Add member in browser → no manual header errors in devtools Network tab
- [ ] Remove `X-Organisation-ID` in devtools and confirm 400 JSON response
- [ ] Unauthenticated request in incognito → 401 JSON (not HTML)
- [ ] `php artisan test` — full suite passes (no BelongsToTenant regressions)
