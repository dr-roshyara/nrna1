## 📋 **CLAUDE CLI PROMPT: Add hasOwnOrganisation Logic**

```bash
## TASK: Add hasOwnOrganisation methods to User model and update DashboardResolver

### Context
We have a platform organisation with type='platform' that serves as the default for all users. 
Users can create their own tenant organisations (type='tenant'). We need to distinguish between:

- Users with their OWN organisation (tenant) → should go to /organisations/{slug}
- Users with ONLY platform organisation → should go to /dashboard/welcome or /dashboard

### Current State
- Platform org exists: `19650b45-6932-4a31-bfe1-25db8e79d9fc` with type='platform'
- Users can belong to multiple orgs via pivot table
- Need helper methods to check for tenant orgs

### Required Changes

#### 1. Add methods to User model

**File:** `app/Models/User.php`

Add these three methods:

```php
/**
 * Check if user has their OWN organisation (tenant, not platform)
 * 
 * A user "owns" an organisation if they have a pivot record
 * and the organisation type is 'tenant'
 * 
 * @return bool
 */
public function hasOwnOrganisation(): bool
{
    return $this->organisations()
        ->where('type', 'tenant')
        ->exists();
}

/**
 * Get user's own organisation (the first tenant org they belong to)
 * 
 * @return Organisation|null
 */
public function getOwnOrganisation(): ?Organisation
{
    return $this->organisations()
        ->where('type', 'tenant')
        ->first();
}

/**
 * Check if user is the owner of a specific organisation
 * Stricter check - user must have role='owner'
 * 
 * @param string $organisationId
 * @return bool
 */
public function isOwnerOf(string $organisationId): bool
{
    return $this->organisationRoles()
        ->where('organisation_id', $organisationId)
        ->where('role', 'owner')
        ->exists();
}
```

#### 2. Update DashboardResolver to use new methods

**File:** `app/Services/DashboardResolver.php`

Replace the `handleMissingOrganisation()` method:

```php
/**
 * Handle case when user has no active organisations or elections
 */
private function handleMissingOrganisation(User $user): RedirectResponse
{
    Log::info('🔍 handleMissingOrganisation called', [
        'user_id' => $user->id,
        'email' => $user->email,
        'raw_org_id' => $user->organisation_id,
        'onboarded_at' => $user->onboarded_at,
    ]);

    // ===== CHECK 1: Does user have THEIR OWN organisation? =====
    if ($user->hasOwnOrganisation()) {
        $ownOrg = $user->getOwnOrganisation();
        
        Log::info('🏢 User has own organisation - redirecting', [
            'user_id' => $user->id,
            'organisation_id' => $ownOrg->id,
            'organisation_slug' => $ownOrg->slug,
            'type' => $ownOrg->type,
        ]);
        
        return redirect()->route('organisations.show', $ownOrg->slug);
    }
    
    // ===== CHECK 2: User is in platform context =====
    // User has NO tenant org, so they're a platform user
    
    if ($user->onboarded_at === null) {
        Log::info('👋 Platform user not onboarded - welcome page', [
            'user_id' => $user->id,
        ]);
        return redirect()->route('dashboard.welcome');
    }
    
    Log::info('🏛️ Platform user onboarded - main dashboard', [
        'user_id' => $user->id,
    ]);
    return redirect()->route('dashboard');
}
```

Also update `hasActiveOrganisations()` to exclude platform org:

```php
/**
 * Check if user has any active organisations (EXCLUDING platform)
 */
private function hasActiveOrganisations(User $user): bool
{
    try {
        $platformOrgId = $this->getPlatformOrgId();
        
        $exists = DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('organisation_id', '!=', $platformOrgId) // Exclude platform
            ->exists();
        
        Log::debug('hasActiveOrganisations check', [
            'user_id' => $user->id,
            'exists' => $exists,
            'platform_org_id' => $platformOrgId,
        ]);
        
        return $exists;
    } catch (\Exception $e) {
        Log::error('Error checking active organisations', [
            'user_id' => $user->id,
            'error' => $e->getMessage(),
        ]);
        return false;
    }
}

/**
 * Get platform organisation ID (cached)
 */
private function getPlatformOrgId(): string
{
    return Cache::remember('platform_org_id', 3600, function () {
        return Organisation::where('type', 'platform')
            ->where('is_default', true)
            ->value('id');
    });
}
```

#### 3. Add/Update Tests

**File:** `tests/Unit/Models/UserTest.php`

Add tests for new methods:

```php
/** @test */
public function user_has_own_organisation_returns_true_when_belongs_to_tenant_org()
{
    $user = User::factory()->create();
    $tenantOrg = Organisation::factory()->tenant()->create();
    
    $user->organisations()->attach($tenantOrg->id, ['role' => 'member']);
    
    $this->assertTrue($user->hasOwnOrganisation());
    $this->assertNotNull($user->getOwnOrganisation());
    $this->assertEquals($tenantOrg->id, $user->getOwnOrganisation()->id);
}

/** @test */
public function user_has_own_organisation_returns_false_when_only_platform()
{
    $user = User::factory()->create();
    $platformOrg = Organisation::factory()->platform()->default()->create();
    
    $user->organisations()->attach($platformOrg->id, ['role' => 'member']);
    
    $this->assertFalse($user->hasOwnOrganisation());
    $this->assertNull($user->getOwnOrganisation());
}

/** @test */
public function user_is_owner_of_returns_true_when_role_is_owner()
{
    $user = User::factory()->create();
    $org = Organisation::factory()->tenant()->create();
    
    $user->organisations()->attach($org->id, ['role' => 'owner']);
    
    $this->assertTrue($user->isOwnerOf($org->id));
}

/** @test */
public function user_is_owner_of_returns_false_when_role_is_member()
{
    $user = User::factory()->create();
    $org = Organisation::factory()->tenant()->create();
    
    $user->organisations()->attach($org->id, ['role' => 'member']);
    
    $this->assertFalse($user->isOwnerOf($org->id));
}
```

**File:** `tests/Feature/Services/DashboardResolverTest.php`

Add test for handleMissingOrganisation with own org:

```php
/** @test */
public function user_with_own_organisation_redirects_to_organisation_page()
{
    $user = User::factory()->create(['onboarded_at' => now()]);
    $ownOrg = Organisation::factory()->tenant()->create(['slug' => 'my-company']);
    
    $user->organisations()->attach($ownOrg->id, ['role' => 'owner']);
    
    $response = $this->resolver->resolve($user);
    
    $this->assertEquals(
        route('organisations.show', 'my-company'),
        $response->getTargetUrl()
    );
}
```

### Execution Order

```bash
# 1. Add methods to User model
# 2. Add tests for new methods (should pass)
# 3. Update DashboardResolver
# 4. Update DashboardResolver tests (should pass)
# 5. Commit

git add app/Models/User.php
git add app/Services/DashboardResolver.php
git add tests/Unit/Models/UserTest.php
git add tests/Feature/Services/DashboardResolverTest.php

git commit -m "feat: Add hasOwnOrganisation logic to distinguish platform vs tenant users

- Add hasOwnOrganisation(), getOwnOrganisation(), isOwnerOf() methods
- Update DashboardResolver to redirect users with own org to their org page
- Update hasActiveOrganisations to exclude platform org
- Add comprehensive tests
- Users with tenant orgs now go to /organisations/{slug}
- Platform-only users continue to welcome/dashboard"
```

### Expected Outcome

| User Type | Before | After |
|-----------|--------|-------|
| Has tenant org | May go to welcome | ✅ Goes to their org page |
| Platform only (new) | Welcome page | ✅ Welcome page |
| Platform only (onboarded) | Dashboard | ✅ Dashboard |

**Proceed with implementation.**<｜end▁of▁thinking｜>## ✅ **APPROVED - EXCELLENT INSTRUCTIONS!**

### The prompt is complete and ready for Claude to execute:

| Component | Status |
|-----------|--------|
| User Model methods | ✅ Clearly specified |
| DashboardResolver updates | ✅ With exact code |
| Platform org exclusion | ✅ Added to hasActiveOrganisations |
| Tests | ✅ Comprehensive coverage |
| Commit message | ✅ Ready to use |

---

## 📋 **EXECUTION SUMMARY FOR CLAUDE**

```bash
# 1. Add methods to User model
# 2. Run User tests (should pass)
# 3. Update DashboardResolver
# 4. Run DashboardResolver tests (should pass)
# 5. Commit all changes
```

**Proceed with implementation.**

# what is given : 
## 📋 **HAS ORGANISATION - DEFINED IN TWO PLACES**

### **1. In User Model (belongsToOrganisation Method)**

```php
// app/Models/User.php

/**
 * Check if user belongs to a specific organisation
 * 
 * This is the SOURCE OF TRUTH for organisation membership.
 * It checks the pivot table directly.
 * 
 * @param string $organisationId UUID of the organisation
 * @return bool
 */
public function belongsToOrganisation(string $organisationId): bool
{
    return $this->organisationRoles()
        ->where('organisation_id', $organisationId)
        ->exists();
}
```

### **2. In DashboardResolver (hasActiveOrganisations Method)**

```php
// app/Services/DashboardResolver.php

/**
 * Check if user has any active organisations (excluding platform)
 * 
 * Active organisations are those where user has ANY role 
 * (member, admin, voter, commission, etc.) in the pivot table.
 * 
 * @param User $user
 * @return bool
 */
private function hasActiveOrganisations(User $user): bool
{
    try {
        $exists = DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('organisation_id', '!=', 1) // Exclude platform org
            ->exists();
        
        Log::debug('DashboardResolver: hasActiveOrganisations check', [
            'user_id' => $user->id,
            'exists' => $exists,
            'organisation_roles' => DB::table('user_organisation_roles')
                ->where('user_id', $user->id)
                ->where('organisation_id', '!=', 1)
                ->get(['organisation_id', 'role'])
                ->toArray()
        ]);
        
        return $exists;
    } catch (\Exception $e) {
        Log::error('DashboardResolver: Error checking active organisations', [
            'user_id' => $user->id,
            'error' => $e->getMessage(),
        ]);
        return false;
    }
}
```

---

## 🔑 **THE PIVOT TABLE (Source of Truth)**

```sql
-- user_organisation_roles table
-- This is where membership is ACTUALLY stored
SELECT * FROM user_organisation_roles 
WHERE user_id = '123' AND organisation_id = '456';
-- If this returns a row → user HAS organisation
-- If empty → user does NOT have organisation
```

---

## 📊 **HOW THEY'RE USED TOGETHER**

```mermaid
graph TB
    subgraph "Check Organisation Membership"
        A[User Object] --> B{belongsToOrganisation()}
        B -->|Checks| C[(user_organisation_roles)]
        C -->|Has row| D[✅ TRUE - User IS member]
        C -->|No row| E[❌ FALSE - User NOT member]
    end
    
    subgraph "Check Any Active Organisations"
        F[User Object] --> G{hasActiveOrganisations()}
        G -->|Checks| H[(user_organisation_roles)]
        H -->|Any row with org_id != 1| I[✅ TRUE - Has at least one org]
        H -->|Only platform or none| J[❌ FALSE - No active orgs]
    end
```

---

## 🎯 **WHY BOTH EXIST**

| Method | Purpose | When Used |
|--------|---------|-----------|
| `belongsToOrganisation($id)` | Check specific org membership | Middleware, permissions |
| `hasActiveOrganisations()` | Check if user has ANY org | DashboardResolver Priority 4 |

---

## ✅ **EXAMPLE USAGE**

```php
// In EnsureOrganisationMember middleware
if (!$user->belongsToOrganisation($organisation->id)) {
    abort(403); // User doesn't belong to THIS specific org
}

// In DashboardResolver
if (!$this->hasActiveOrganisations($user)) {
    // User has NO orgs at all → handle missing org case
    return $this->handleMissingOrganisation($user);
}
```

# what to chagne create has own organstion logic: 

## ✅ **EXACTLY RIGHT! Here's the "Has Own Organisation" Logic**

### **The Distinction:**

| Type | Purpose | User Experience |
|------|---------|-----------------|
| **Platform Org** (`type='platform'`) | Default for all users | Shows demo/welcome |
| **Tenant Org** (`type='tenant'`) | User's own organisation | Shows real dashboard |

---

## 📋 **IMPLEMENT "HAS OWN ORGANISATION" LOGIC**

### **In User Model - Add Helper Method**

```php
// app/Models/User.php

/**
 * Check if user has their OWN organisation (tenant, not platform)
 * 
 * A user "owns" an organisation if:
 * 1. They have a pivot record (membership)
 * 2. The organisation type is 'tenant' (not platform)
 * 3. Optional: They have role='owner' (if you want strict ownership)
 * 
 * @return bool
 */
public function hasOwnOrganisation(): bool
{
    return $this->organisations()
        ->where('type', 'tenant')
        ->exists();
}

/**
 * Get user's own organisation (the first tenant org they belong to)
 * 
 * @return Organisation|null
 */
public function getOwnOrganisation(): ?Organisation
{
    return $this->organisations()
        ->where('type', 'tenant')
        ->first();
}

/**
 * Check if user is the owner of a specific organisation
 * Stricter check - user must have role='owner'
 * 
 * @param string $organisationId
 * @return bool
 */
public function isOwnerOf(string $organisationId): bool
{
    return $this->organisationRoles()
        ->where('organisation_id', $organisationId)
        ->where('role', 'owner')
        ->exists();
}
```

---

### **In DashboardResolver - Update Logic**

```php
// app/Services/DashboardResolver.php

/**
 * Handle case when user has no active organisations or elections
 */
private function handleMissingOrganisation(User $user): RedirectResponse
{
    // DEBUG: Log what's happening
    Log::info('🔍 handleMissingOrganisation called', [
        'user_id' => $user->id,
        'email' => $user->email,
        'raw_org_id' => $user->organisation_id,
        'onboarded_at' => $user->onboarded_at,
        'has_platform_pivot' => DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('organisation_id', $this->getPlatformOrgId())
            ->exists(),
    ]);

    // ===== CHECK 1: Does user have THEIR OWN organisation? =====
    if ($user->hasOwnOrganisation()) {
        $ownOrg = $user->getOwnOrganisation();
        
        Log::info('🏢 User has own organisation - redirecting', [
            'user_id' => $user->id,
            'organisation_id' => $ownOrg->id,
            'organisation_slug' => $ownOrg->slug,
            'type' => $ownOrg->type,
        ]);
        
        return redirect()->route('organisations.show', $ownOrg->slug);
    }
    
    // ===== CHECK 2: User is in platform context =====
    // User has NO tenant org, so they're a platform user
    
    if ($user->onboarded_at === null) {
        Log::info('👋 Platform user not onboarded - welcome page', [
            'user_id' => $user->id,
        ]);
        return redirect()->route('dashboard.welcome');
    }
    
    Log::info('🏛️ Platform user onboarded - main dashboard', [
        'user_id' => $user->id,
    ]);
    return redirect()->route('dashboard');
}

/**
 * Get platform organisation ID (cached)
 */
private function getPlatformOrgId(): string
{
    return Cache::remember('platform_org_id', 3600, function () {
        return Organisation::where('type', 'platform')
            ->where('is_default', true)
            ->value('id');
    });
}
```

---

### **In hasActiveOrganisations - Update to Exclude Platform**

```php
/**
 * Check if user has any active organisations (EXCLUDING platform)
 */
private function hasActiveOrganisations(User $user): bool
{
    try {
        $platformOrgId = $this->getPlatformOrgId();
        
        $exists = DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('organisation_id', '!=', $platformOrgId) // Exclude platform
            ->exists();
        
        Log::debug('hasActiveOrganisations check', [
            'user_id' => $user->id,
            'exists' => $exists,
            'platform_org_id' => $platformOrgId,
        ]);
        
        return $exists;
    } catch (\Exception $e) {
        Log::error('Error checking active organisations', [
            'user_id' => $user->id,
            'error' => $e->getMessage(),
        ]);
        return false;
    }
}
```

---

## 📊 **USER JOURNEY BASED ON THIS LOGIC**

```mermaid
graph TD
    A[User Logs In] --> B{Has Own Organisation?}
    
    B -->|Yes| C[Tenant Org Exists]
    C --> D[Redirect to /organisations/{slug}]
    
    B -->|No| E{Is Onboarded?}
    E -->|No| F[First Time User]
    F --> G[Redirect to /dashboard/welcome]
    
    E -->|Yes| H[Returning Platform User]
    H --> I[Redirect to /dashboard]
```

---

## ✅ **REAL-WORLD EXAMPLES**

### **Example 1: New User (just registered)**
```php
// User data:
- belongs to platform org (type='platform')
- hasOwnOrganisation() = false
- onboarded_at = null

// Result:
handleMissingOrganisation() → /dashboard/welcome
```

### **Example 2: User Created Their Own Organisation**
```php
// User data:
- belongs to platform org (type='platform')
- ALSO belongs to tenant org (type='tenant') with role='owner'
- hasOwnOrganisation() = true
- onboarded_at = 2026-01-01

// Result:
handleMissingOrganisation() → /organisations/acme-inc
```

### **Example 3: Platform Admin (no tenant org)**
```php
// User data:
- belongs to platform org only
- hasOwnOrganisation() = false
- onboarded_at = 2026-01-01

// Result:
handleMissingOrganisation() → /dashboard
```

---

## 📝 **SUMMARY**

| Method | Returns | Purpose |
|--------|---------|---------|
| `hasOwnOrganisation()` | bool | Does user have ANY tenant org? |
| `getOwnOrganisation()` | Organisation\|null | Get their first tenant org |
| `isOwnerOf($id)` | bool | Strict ownership check |

**This cleanly separates platform users from users with their own organisations.**