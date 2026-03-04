# 1. Multi-Tenancy Overview

## What is Multi-Tenancy?

Public Digit serves multiple independent organisations from a single application instance. Each organisation's data is **completely isolated** while sharing the same codebase.

```
┌─────────────────────────────────────────────────┐
│         Public Digit Platform                    │
├─────────────────────────────────────────────────┤
│  ┌──────────────┐  ┌──────────────┐            │
│  │   Org A      │  │   Org B      │            │
│  │  (Live)      │  │  (Live)      │            │
│  │ org_id = 2   │  │ org_id = 3   │            │
│  └──────────────┘  └──────────────┘            │
│                                                  │
│  ┌──────────────────────────────────┐           │
│  │  Platform (org_id = 1)           │           │
│  │  - Demo mode                     │           │
│  │  - Platform onboarding           │           │
│  │  - Admin management              │           │
│  └──────────────────────────────────┘           │
└─────────────────────────────────────────────────┘
```

## The User-Organisation Relationship

### Two Concepts: Different Purposes

**1. `users.organisation_id` (Current Assignment)**
```
Shows which organisation the user is currently assigned to or was last using.
- For platform users: = 1 (the platform)
- For tenant users: = X (the organisation they joined)
- Can be NULL initially
```

**2. `user_organisation_roles` (Actual Membership)**
```
Pivot table showing ALL organisations a user belongs to.
Stores actual permission relationships.
```

### Example: User Lifecycle

```sql
-- User registers with no organisation assignment
INSERT INTO users (id, name, email, organisation_id, ...)
VALUES (1, 'Alice', 'alice@example.com', NULL, ...);

-- RegisterController creates pivot for platform org
INSERT INTO user_organisation_roles (user_id, organisation_id, role, ...)
VALUES (1, 1, 'member', ...);

-- User is now assigned to platform
UPDATE users SET organisation_id = 1 WHERE id = 1;

-- User later joins Organisation A
INSERT INTO user_organisation_roles (user_id, organisation_id, role, ...)
VALUES (1, 2, 'member', ...);

-- User's current assignment changes
UPDATE users SET organisation_id = 2 WHERE id = 1;

-- Query: What organisations does Alice belong to?
SELECT organisation_id FROM user_organisation_roles
WHERE user_id = 1;
-- Result: [1, 2] (platform AND organisation A)
```

## Critical Design Decision: Separate Platform from Tenants

The system uses a **two-tier model:**

### Tier 1: Platform (organisation_id = 1)
- Manages all organisations
- Handles user onboarding
- Provides demo environment
- Stores global reference data

### Tier 2: Tenant Organisations (organisation_id > 1)
- Live elections
- Active voting
- Real member participation

## Isolation Guarantees

### Database Layer
```php
// ✅ REQUIRED - Every query must scope by organisation_id
$elections = Election::where('organisation_id', $user->organisation_id)
    ->get();

// ❌ WRONG - Cross-tenant query
$allElections = Election::all();  // This would include other orgs!
```

### Model Layer
```php
// Global scopes enforce organisation_id on ALL queries
class Election extends Model {
    protected static function booted() {
        static::addGlobalScope('organisation_id', function ($query) {
            $query->where('organisation_id', Auth::user()->organisation_id);
        });
    }
}
```

### Middleware Layer
```php
// EnsureOrganisationMember validates user has access to route organisation
if (!$user->organisationRoles()
    ->where('organisations.id', $organisation->id)
    ->exists()) {
    abort(403);  // Tenant isolation enforced
}
```

### Session Layer
```php
// User's current organisation is tracked in session
session(['current_organisation_id' => $user->organisation_id]);
```

## The Pivot Table: Foundation of Multi-Tenancy

```sql
CREATE TABLE user_organisation_roles (
    id PRIMARY KEY,
    user_id BIGINT (FOREIGN KEY),      -- Which user
    organisation_id BIGINT (FOREIGN KEY),  -- Which organisation
    role ENUM('member', 'admin', ...),  -- What role
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- CRITICAL: This table is the source of truth for membership
-- If a row doesn't exist, the user cannot access that organisation
```

## What Makes This Unique

Unlike frameworks that use a "current tenant" context (Stancl, Hyn), Public Digit:

1. **Explicit Tenant Context** - Every service receives `organisation_id` parameter
2. **Membership-Based** - Users explicitly belong to organisations via pivot
3. **No Automatic Scoping** - Developers must consciously scope queries
4. **Testable** - No global state, all tenant context passed explicitly
5. **Safe** - Queries without scope filtering will fail tests

## The 403 Error Explained

When a user tries to access an organisation but **has no pivot record**, the middleware rejects it:

```php
// Middleware check
if (!$user->organisationRoles()
    ->where('organisations.id', $organisationId)
    ->exists()) {
    return abort(403);  // No pivot = no access
}
```

**This is a feature, not a bug.** It prevents accidental data leaks.

## Registration Flow (Simplified)

```
1. User submits registration form
   ↓
2. RegisterController::store() validates and creates User
   ↓
3. User.created() event fires (boot method listens)
   ↓
4. Fallback pivot creation: INSERT INTO user_organisation_roles
   ↓
5. User assigned to platform: UPDATE users SET organisation_id = 1
   ↓
6. Redirect to email verification
   ↓
7. User verifies email
   ↓
8. Login → DashboardResolver → /dashboard/welcome
```

---

**Next:** [02-USER_MODEL_LIFECYCLE.md](02-USER_MODEL_LIFECYCLE.md)
