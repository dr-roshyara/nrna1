Now that your routing architecture is locked down, your middleware is perfectly sequenced, and your frontend-to-backend communication is running flawlessly, you can focus on expanding your multi-tenant SaaS platform.

Here are the concrete next steps to move this project forward:

---

## 1. Implement Client-Side Global Request Interceptors

Instead of manually adding headers (`X-Organisation-ID`, `X-Requested-With`) and raw stream handling parsing blocks inside every single component (like `CommitteeMemberManager.vue`), you should centralize this behavior.

* **Axios or Fetch Wrapper:** Create an API utility wrapper or an Axios instance that automatically injects the tenant ID from Inertia’s global page props (`usePage().props.organisationId`) into every `/api/v1/*` outbound request.
* **Global Error Handling:** Route all common error codes (like `401 Unauthorized` or `MISSING_TENANT_CONTEXT`) to a single global error handler or notification toast pipeline.

---

## 2. Standardize Tenant Scoping Across All Domain Models

The last blocker you faced was a raw database error due to a column mismatch (`tenant_id` vs `organisation_id`). To prevent this from popping up elsewhere:

* **Audit Domain Traits:** Ensure all models (Committees, Elections, Memberships, etc.) inherit from a unified, explicit multi-tenancy trait.
* **Enforce Column Mapping:** Explicitly verify that your global tenant application scopes look for `organisation_id` rather than generic framework defaults.
* **Write a Base Test Case:** Set up a feature test case (`TenantScopedTestCase`) that automatically asserts that any database query generated within an API request context contains the appropriate `WHERE organisation_id = ?` clause.

---

## 3. Implement the Command / Handler Pattern for the Write-Side

Since your controller adapters are now beautifully slim HTTP layers passing pure PHP objects, implement the rest of your domain mutations following strict **Domain-Driven Design (DDD)** and **Test-Driven Development (TDD)**:

* Write the validation rules inside your requests.
* Pass execution from the controller to a dedicated `CommandHandler` (e.g., `AddCommitteeMemberHandler`).
* Raise a Domain Event (e.g., `CommitteeMemberAdded`) upon successful execution to notify other bounded contexts (like sending an automated Matrix Chat invitation or updating the Election registry).

---

## 4. Write Feature Integration Tests

Now that the pipeline is perfectly stable, lock it down with automated tests so future refactoring doesn't break this delicate balance. Write integration tests covering:

* A request sent **without** the `X-Organisation-ID` header returns a `400 / 406 JSON` validation block (not a `302`).
* A request sent **without** an active session cookie returns a clean `401 Unauthorized` JSON payload.
* A valid request executes completely and returns a `201 Created` or `200 OK` JSON response.

---

### Where should we dive in first?

Would you like to build out the automated Axios/Fetch tenant wrapper for your Vue frontend, or should we clean up the tenant scoping trait on the backend models?
Let's start by bulletproofing your multi-tenant scoping on the backend models, as this directly addresses the database column mismatch (`tenant_id` vs `organisation_id`) we just uncovered. Ensuring your models handle the tenant scope identically will prevent future query crashes.

Here is the plan to build and apply a rock-solid, reusable tenant scope trait across your application.

---

### Step 1: Create a Dedicated Tenant Trait

Instead of relying on generic traits that assume a default column name like `tenant_id`, we will create a dedicated `ScopedByOrganisation` trait. This trait automatically forces the model to include `where('organisation_id', ...)` on every single query using your global `TenantContext` service.

Let's review the structure for this trait:

```php
<?php

namespace App\Models\Traits;

use App\Services\TenantContext; // Or your explicit service namespace
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait ScopedByOrganisation
{
    /**
     * Boot the trait to apply the global multi-tenancy scope.
     */
    protected static function bootScopedByOrganisation(): void
    {
        static::creating(function (Model $model) {
            // Automatically fill organization field on creation if context is active
            if (empty($model->organisation_id) && TenantContext::get()) {
                $model->organisation_id = TenantContext::get();
            }
        });

        static::addGlobalScope('organisation_scope', function (Builder $builder) {
            if (TenantContext::get()) {
                $builder->where($builder->getModel()->getTable() . '.organisation_id', TenantContext::get());
            }
        });
    }
}

```

---

### Step 2: Update the Models

Once this trait is built, you can strip out any custom or generic scopes from your tenant-specific models (like `Member`, `Committee`, `MemberDirectory`, etc.) and cleanly apply this explicit trait:

```php
<?php

namespace App\Models;

use App\Models\Traits\ScopedByOrganisation;
use Illuminate\Database\Eloquent\Model;

class MemberDirectory extends Model
{
    use ScopedByOrganisation;

    protected $table = 'member_directories';
    // ... rest of your model configuration
}

```

---

### Let's Do This

Shall we have the workspace engine generate this trait file (`app/Models/Traits/ScopedByOrganisation.php`) and start updating your target models to apply it?
Excellent. Let's make this concrete. To implement this backend multi-tenant abstraction cleanly, we need to ensure that your global `TenantContext` service is driving the Eloquent models directly.

Let's inspect your workspace layout to generate this trait file exactly where it belongs.

### Step 1: Locating the Core Trait Directory

Let's verify your current `app/Models` directory structure to see if you have an existing directory for shared traits, or if we should create `app/Models/Traits`.

I will run a quick check on your directory tree:

```bash
cd "C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu" && dir app\Models

```

Once we see where your models are aggregated, we will write the explicit `ScopedByOrganisation` trait code and apply it directly to `MemberDirectory` and any adjacent models to eliminate those query crashes permanently. Let's inspect the files!
review: 
## 🐘 Ganesh Ji's Verdict on Gemini's Next Steps

*"Beta, Gemini has given you a MAP to the promised land. His advice is SOLID, PRODUCTION-READY, and addresses the EXACT pain points you just experienced.*

*Let me review each recommendation with the wisdom of a senior architect."*

---

## Gemini's Recommendations - Senior Architect Review

### ✅ Recommendation 1: Client-Side Global Request Interceptors

```yaml
VERDICT: ✅ CRITICAL - DO THIS NOW

WHY:
  - Your CommitteeMemberManager.vue has duplicate header code
  - Every new component will repeat the same mistakes
  - Centralized API client prevents tenant header omissions

IMPLEMENTATION:
  - Create resources/js/services/api.js with axios interceptors
  - Inject X-Organisation-ID from page.props automatically
  - Handle 401/403 globally (redirect to login or show toast)

PRIORITY: HIGH (2-3 hours)
```

### ✅ Recommendation 2: Standardize Tenant Scoping Across Models

```yaml
VERDICT: ✅ CRITICAL - DO THIS NOW

WHY:
  - You just had a column mismatch (tenant_id vs organisation_id)
  - Different models will have inconsistent scoping
  - Global scope prevents "forgot to filter by tenant" bugs

IMPLEMENTATION:
  - Create ScopedByOrganisation trait
  - Apply to ALL tenant-scoped models
  - Auto-fill organisation_id on create

PRIORITY: HIGH (1-2 hours)
```

### ✅ Recommendation 3: Command/Handler Pattern for Write-Side

```yaml
VERDICT: ✅ GOOD - BUT YOU ALREADY HAVE THIS

WHY:
  - Your CommitteeMemberController already uses Command pattern
  - MemberImportService follows this pattern
  - You're already doing DDD correctly here

ACTION: Document existing pattern, ensure consistency

PRIORITY: MEDIUM (1 hour for audit)
```

### ✅ Recommendation 4: Feature Integration Tests

```yaml
VERDICT: ✅ CRITICAL - DO THIS NOW

WHY:
  - You just spent HOURS debugging routing issues
  - Tests would have caught the 401/HTML problem immediately
  - Lock down the working behavior

IMPLEMENTATION:
  - Test missing X-Organisation-ID → 400 JSON
  - Test unauthenticated → 401 JSON
  - Test valid request → 200/201 JSON

PRIORITY: HIGH (2-3 hours)
```

---

## 🎯 Immediate Priority Order

```yaml
TODAY (2-3 hours):
  1. ✅ Create ScopedByOrganisation trait (prevents SQL column mismatches)
  2. ✅ Apply to MemberDirectory, Committee, Member models
  3. ✅ Create centralized API client with interceptors

TOMORROW (2-3 hours):
  4. ✅ Write integration tests for API authentication flow
  5. ✅ Test tenant header validation
  6. ✅ Document the patterns

THIS WEEK (4-6 hours):
  7. ✅ Audit existing models for tenant scoping consistency
  8. ✅ Standardize Command/Handler pattern documentation
  9. ✅ Add global error handling for API responses
```

---

## What to Tell Claude

```prompt
Gemini's recommendations are approved. Execute in this order:

## Phase 1 (Today - Critical)

1. Create `app/Models/Traits/ScopedByOrganisation.php`:
   - Global scope to filter by organisation_id
   - Auto-fill organisation_id on create from TenantContext
   - Apply to MemberDirectory, Committee, Member models

2. Create `resources/js/services/api.js`:
   - Axios instance with withCredentials: true
   - Interceptor to add X-Organisation-ID from page.props
   - Global error handling for 401/403/400

3. Update CommitteeMemberManager.vue to use the new api client

## Phase 2 (Tomorrow - Lock Down)

4. Create `tests/Feature/API/AuthenticationTest.php`:
   - Test missing tenant header → 400 JSON
   - Test unauthenticated → 401 JSON
   - Test valid request → 200/201 JSON

5. Create `tests/Feature/API/TenantScopeTest.php`:
   - Verify queries include organisation_id filter

Proceed step by step. Report after each phase.
```