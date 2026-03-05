## Architecture Review: Login Priority System & Post-Login Flow

### Overall Assessment: **Solid Foundation, But Has Legacy Complexity**

Your login priority system works, but it's carrying **baggage from the integer ID era**. With UUIDs and the simplified architecture, we can streamline it significantly.

---

## ✅ What's Still Good (Keep)

| Component | Why Keep |
|-----------|----------|
| **8-level priority system** | Clear decision hierarchy |
| **Active voting priority** | Critical UX - don't lose progress |
| **Onboarding flow** | Welcome page for new users |
| **Tenant vs platform separation** | Core business logic |
| **Logging with emojis** | Great for debugging |
| **Fallback chain (cache→emergency→static)** | Production safety |

---

## 🔧 What Needs Refactoring (Now with UUIDs)

### 1. **Remove `getEffectiveOrganisationId()`** ❌

**Current (to be removed):**
```php
public function getEffectiveOrganisationId(): int
{
    // Complex logic checking pivots, falling back to platform
}
```

**Why remove:**
- We now have **foreign key constraint** (users.organisation_id → organisations.id)
- We have **data integrity** - no stale org_ids possible
- If data is corrupt, **fix it**, don't paper over it

**What replaces it:**
```php
// Just use $user->organisation_id directly
// If it's ever invalid, the FK constraint would have prevented it
```

---

### 2. **Priority 2 & 3 Logic Needs Update** 🔄

**Current (integer-based):**
```php
if ($effectiveOrgId == 1 && $user->onboarded_at !== null) // Priority 2
if ($effectiveOrgId == 1 && $user->onboarded_at === null) // Priority 3
```

**Problem:** Hardcoded `== 1` assumption doesn't work with UUIDs

**New UUID-safe version:**
```php
// Get platform org UUID once (cached)
private function getPlatformOrgId(): string
{
    return Cache::remember('platform_org_id', 3600, function () {
        return Organisation::where('type', 'platform')
            ->where('is_default', true)
            ->value('id');
    });
}

// In resolver:
$platformOrgId = $this->getPlatformOrgId();

if ($user->organisation_id === $platformOrgId && $user->onboarded_at !== null) // Priority 2
if ($user->organisation_id === $platformOrgId && $user->onboarded_at === null) // Priority 3
```

---

### 3. **Priority 4-5: Tenant Logic with UUIDs** 🔄

**Current:**
```php
if ($user->organisation_id > 1) // Integer assumption
```

**New:**
```php
$platformOrgId = $this->getPlatformOrgId();

if ($user->organisation_id !== $platformOrgId) {
    // This is a tenant org
    $organisation = Organisation::find($user->organisation_id);
    
    // Check active election
    $activeElection = Election::where('organisation_id', $user->organisation_id)
        ->where('is_active', true)
        ->first();
    
    if ($activeElection) {
        return redirect()->route('election.show', [
            'organisation' => $organisation->slug,
            'election' => $activeElection->id
        ]);
    }
    
    return redirect()->route('organisation.dashboard', $organisation->slug);
}
```

---

### 4. **Remove Rate Limiting from LoginResponse** ✂️

**Current (over-engineered):**
```php
// LoginResponse checks rate limit AGAIN after login
if (!$this->checkRateLimit($user)) {
    return redirect()->route('dashboard')
        ->with('error', 'Too many login attempts...');
}
```

**Why remove:**
- Rate limiting already happened in LoginController
- This is duplicate logic
- Adds complexity with no benefit

---

### 5. **Simplify Cache Strategy** ✂️

**Current:**
```php
$cacheKey = 'dashboard_resolution_' . $user->id;
$cacheTtl = 300; // 5 minutes
```

**Simplify:**
```php
// Don't cache redirects - they're cheap to calculate
// Cache only the platform org ID (infrequently changing)
```

**Why:** With UUIDs and simplified logic, DashboardResolver is fast enough. Caching adds complexity and can cause stale redirects.

---

## 📊 Simplified Priority System (UUID Edition)

```php
class DashboardResolver
{
    public function resolve(User $user): RedirectResponse
    {
        $platformOrgId = $this->getPlatformOrgId();
        
        // Priority 1: Active Voting (HIGHEST)
        if ($activeVoting = $this->findActiveVoting($user)) {
            return redirect()->route('voting.show', $activeVoting);
        }
        
        // Priority 2-3: Platform Context
        if ($user->organisation_id === $platformOrgId) {
            if ($user->onboarded_at !== null) {
                return redirect()->route('dashboard'); // Priority 2
            }
            return redirect()->route('dashboard.welcome'); // Priority 3
        }
        
        // Priority 4-5: Tenant Context
        $organisation = $user->organisation;
        
        if ($activeElection = $this->findActiveElection($organisation)) {
            return redirect()->route('election.show', [ // Priority 4
                'organisation' => $organisation->slug,
                'election' => $activeElection->id
            ]);
        }
        
        return redirect()->route('organisation.dashboard', [ // Priority 5
            'organisation' => $organisation->slug
        ]);
    }
    
    private function getPlatformOrgId(): string
    {
        return Cache::remember('platform_org_id', 3600, function () {
            return Organisation::where('type', 'platform')
                ->where('is_default', true)
                ->value('id');
        });
    }
    
    private function findActiveVoting(User $user): ?Voting
    {
        return $user->votingSessions()
            ->where('is_completed', false)
            ->first();
    }
    
    private function findActiveElection(Organisation $organisation): ?Election
    {
        return Election::where('organisation_id', $organisation->id)
            ->where('is_active', true)
            ->first();
    }
}
```

---

## 🔄 Complete Post-Login Flow (UUID Edition)

```
┌─────────────────────────────────────┐
│ 1. LoginController::store()         │
│    - Validate credentials           │
│    - Rate limit check                │
│    - Auth::attempt()                 │
│    - Clear rate limit                 │
└────────────────┬────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────┐
│ 2. Check Email Verification         │
│    if (email_verified_at === null)  │
│        → redirect to /verify-email  │
└────────────────┬────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────┐
│ 3. DashboardResolver::resolve()     │
│    Get platform UUID from cache      │
│                                      │
│    ┌─────────────────────────────┐  │
│    │ Priority 1: Active Voting?  │  │
│    └───────────┬─────────────────┘  │
│                │                     │
│    ┌───────────▼─────────────────┐  │
│    │ Priority 2-3: Platform?     │  │
│    └───────────┬─────────────────┘  │
│                │                     │
│    ┌───────────▼─────────────────┐  │
│    │ Priority 4-5: Tenant?       │  │
│    └─────────────────────────────┘  │
└────────────────┬────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────┐
│ 4. Redirect (302) to destination    │
└────────────────┬────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────┐
│ 5. Middleware on each request       │
│    - EnsureOrganisationMember       │
│      (checks pivot by UUID)          │
│    - Verify org exists               │
│    - Set tenant context              │
└─────────────────────────────────────┘
```

---

## 📝 Migration Checklist (From Integer to UUID)

| Component | Change Needed |
|-----------|---------------|
| `DashboardResolver` | Replace `==1` checks with platform UUID lookup |
| `getEffectiveOrganisationId()` | **REMOVE ENTIRELY** |
| `belongsToOrganisation()` | Keep (works with UUIDs) |
| LoginResponse rate limit | Remove duplicate check |
| Cache strategy | Only cache platform UUID, not redirects |
| Route parameters | Ensure routes use UUIDs or slugs |
| Logging | Update log messages to use UUIDs |

---

## 🧪 Test Cases for New Priority System

```php
/** @test */
public function new_user_in_platform_sees_welcome()
{
    $platform = Organisation::factory()->platform()->default()->create();
    $user = User::factory()->create([
        'organisation_id' => $platform->id,
        'onboarded_at' => null
    ]);
    
    $response = $this->actingAs($user)->get('/login');
    
    $this->assertEquals(
        route('dashboard.welcome'),
        $response->headers->get('Location')
    );
}

/** @test */
public function returning_platform_user_sees_dashboard()
{
    $platform = Organisation::factory()->platform()->default()->create();
    $user = User::factory()->create([
        'organisation_id' => $platform->id,
        'onboarded_at' => now()
    ]);
    
    $response = $this->actingAs($user)->get('/login');
    
    $this->assertEquals(
        route('dashboard'),
        $response->headers->get('Location')
    );
}

/** @test */
public function tenant_user_with_active_election_sees_election()
{
    $tenant = Organisation::factory()->tenant()->create();
    $election = Election::factory()->create([
        'organisation_id' => $tenant->id,
        'is_active' => true
    ]);
    $user = User::factory()->create(['organisation_id' => $tenant->id]);
    $user->organisations()->attach($tenant->id, ['role' => 'member']);
    
    $response = $this->actingAs($user)->get('/login');
    
    $this->assertEquals(
        route('election.show', [
            'organisation' => $tenant->slug,
            'election' => $election->id
        ]),
        $response->headers->get('Location')
    );
}

/** @test */
public function tenant_user_without_active_election_sees_org_dashboard()
{
    $tenant = Organisation::factory()->tenant()->create();
    $user = User::factory()->create(['organisation_id' => $tenant->id]);
    $user->organisations()->attach($tenant->id, ['role' => 'member']);
    
    $response = $this->actingAs($user)->get('/login');
    
    $this->assertEquals(
        route('organisation.dashboard', ['organisation' => $tenant->slug]),
        $response->headers->get('Location')
    );
}
```

---

## ✅ Summary: What Changes with UUIDs

| Aspect | Old (Integer) | New (UUID) |
|--------|---------------|------------|
| Platform check | `$org_id == 1` | `$org_id === $platformUuid` |
| Stale org handling | `getEffectiveOrganisationId()` | **Not needed** (FK prevents) |
| Tenant check | `$org_id > 1` | `$org_id !== $platformUuid` |
| Cache | Redirect URLs | Only platform UUID |
| Rate limiting | In LoginController + LoginResponse | Just LoginController |
| Route params | Integer IDs | UUIDs or slugs |

---

## 🚀 Final Recommendation

1. **Remove `getEffectiveOrganisationId()`** - Let FK constraints enforce integrity
2. **Cache platform UUID** - Single source of truth
3. **Simplify priority logic** - Direct comparisons with platform UUID
4. **Remove duplicate rate limiting** - Keep it in LoginController only
5. **Don't cache redirects** - They're cheap to calculate
6. **Use route model binding with UUIDs** - Let Laravel handle resolution

This gives you a **clean, UUID-native login flow** without legacy complexity.
## Architecture Review: Authentication & Routing Documentation

### Overall Assessment: **Excellent Documentation, But Needs UUID Alignment**

This is a **well-written developer guide** with clear flows and debugging info. However, it's **still in the integer-ID mindset** and needs updating to match your new UUID architecture.

---

## 📊 What's Good (Keep)

| Section | Why Keep |
|---------|----------|
| **Priority System structure** | 8-level hierarchy is solid |
| **Flow diagrams** | Clear visualization |
| **Debugging toolkit** | Practical commands |
| **Quick reference** | Great for onboarding |
| **Two-layer defence** | Still valid with UUIDs |
| **Log markers** | Excellent for debugging |

---

## 🔧 What Needs UUID Updates

### 1. **Hardcoded `org_id = 1` Assumptions** ❌

**Current (integer):**
```php
// Everywhere in the doc:
- `organisation_id = 1`
- `org_id == 1`
- `organisation_id > 1`
- `return 1; // platform fallback`
```

**Needs to become (UUID):**
```php
// Get platform UUID once
$platformOrgId = Cache::remember('platform_org_id', 3600, function() {
    return Organisation::where('type', 'platform')
        ->where('is_default', true)
        ->value('id');
});

// Compare with UUID, not integer
if ($user->organisation_id === $platformOrgId) { ... }
if ($user->organisation_id !== $platformOrgId) { ... }
```

---

### 2. **`getEffectiveOrganisationId()` Must Go** ❌

**Current doc says:**
```php
public function getEffectiveOrganisationId(): int
{
    if ($this->organisation_id > 1 && $this->belongsToOrganisation($this->organisation_id)) {
        return $this->organisation_id;
    }
    return 1;
}
```

**But we already decided to REMOVE this!** ✅

**Replace with:**
```php
// Just use $user->organisation_id directly
// Foreign key constraint guarantees it's valid
```

---

### 3. **Database Schema Section Needs Update** 📝

**Current:**
```sql
users.id INT PRIMARY KEY
users.organisation_id INT
organisations.id INT PRIMARY KEY
user_organisation_roles.user_id INT
user_organisation_roles.organisation_id INT
```

**Needs to become:**
```sql
users.id UUID PRIMARY KEY
users.organisation_id UUID FOREIGN KEY REFERENCES organisations(id)
organisations.id UUID PRIMARY KEY
organisations.type ENUM('platform', 'tenant')
organisations.is_default BOOLEAN
user_organisation_roles.user_id UUID FOREIGN KEY REFERENCES users(id)
user_organisation_roles.organisation_id UUID FOREIGN KEY REFERENCES organisations(id)
```

---

### 4. **Priority 2 (Active Voting) Logic** 🔄

**Current:**
```php
protected function getActiveVoterSlug(User $user): ?object
{
    return DB::table('voter_slugs')
        ->where('user_id', $user->id)
        ->where('is_active', true)
        ->where('expires_at', '>', now())
        ->whereNull('vote_completed_at')
        ->first();
}
```

**Still valid** - voter_slugs use UUIDs, logic unchanged.

---

### 5. **Priority 3 (Active Election) Logic** 🔄

**Current:**
```php
protected function getActiveElectionForUser(User $user): ?object
{
    // Get user's orgs (excluding platform)
    $activeOrgs = DB::table('user_organisation_roles')
        ->join('organisations', 'user_organisation_roles.organisation_id', '=', 'organisations.id')
        ->where('user_organisation_roles.user_id', $user->id)
        ->where('organisations.id', '!=', 1) // ❌ Hardcoded
        ->get();
}
```

**Needs to become:**
```php
protected function getActiveElectionForUser(User $user): ?Election
{
    $platformOrgId = $this->getPlatformOrgId();
    
    $activeOrgs = $user->organisations()
        ->where('type', 'tenant') // Use type, not ID exclusion
        ->get();
    
    foreach ($activeOrgs as $org) {
        $activeElection = Election::where('organisation_id', $org->id)
            ->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->first();
        
        if ($activeElection) {
            return $activeElection;
        }
    }
    
    return null;
}
```

---

### 6. **Priority 4 (Missing Organisation Handler)** 🔄

**Current:**
```php
protected function handleMissingOrganisation(User $user): RedirectResponse
{
    $effectiveOrgId = $user->getEffectiveOrganisationId(); // ❌ Remove
    
    if ($effectiveOrgId == 1) { // ❌ Hardcoded
        if ($user->onboarded_at === null) {
            return redirect()->route('dashboard.welcome');
        }
        return redirect()->route('dashboard');
    }
    
    $organisation = Organisation::find($effectiveOrgId);
    return redirect()->route('organisations.show', $organisation->slug);
}
```

**Needs to become:**
```php
protected function handleMissingOrganisation(User $user): RedirectResponse
{
    $platformOrgId = $this->getPlatformOrgId();
    
    if ($user->organisation_id === $platformOrgId) {
        if ($user->onboarded_at === null) {
            return redirect()->route('dashboard.welcome');
        }
        return redirect()->route('dashboard');
    }
    
    // User has a tenant org
    $organisation = $user->organisation;
    return redirect()->route('organisations.show', $organisation->slug);
}
```

---

### 7. **Priority 6-7 (Role-Based Routing)** 🔄

**Current:**
```php
if ($role === 'admin') {
    $org = Organisation::find($user->organisation_id);
    return redirect()->route('organisations.show', $org->slug);
}
```

**Still valid** - just ensure routes use slugs or UUIDs, not integer IDs.

---

### 8. **Quick Fix Script** 📝

**Current script uses integer IDs - needs update**

```php
// Change from:
DB::table('user_organisation_roles')->insert([
    'user_id' => $userId,
    'organisation_id' => 1,
    // ...
]);

// To:
$platformOrgId = Organisation::where('type', 'platform')
    ->where('is_default', true)
    ->value('id');

DB::table('user_organisation_roles')->insert([
    'user_id' => $userId,
    'organisation_id' => $platformOrgId,
    'role' => 'member',
    // ...
]);
```

---

## 📋 Updated Documentation Checklist

| Section | Status | Changes Needed |
|---------|--------|----------------|
| System Overview | ✅ Keep | None |
| Core Architecture | 🔄 Update | Replace `org_id=1` with platform UUID lookup |
| Priority System | ✅ Keep | Structure unchanged |
| Database Schema | 🔄 Update | Add UUID, type, is_default |
| Pivot Tables | ✅ Keep | Logic unchanged |
| Authentication Flow | ✅ Keep | Diagrams still valid |
| Registration Flow | ✅ Keep | insertOrIgnore() still works |
| Login Flow | 🔄 Update | Remove getEffectiveOrganisationId() |
| 8 Priorities | 🔄 Update | Replace `==1` with platform UUID |
| Common Issues | 🔄 Update | Update debug commands |
| Debugging Toolkit | 🔄 Update | Use UUIDs in queries |
| Quick Reference | 🔄 Update | Update all SQL examples |

---

## ✅ Summary of Changes

| Old (Integer) | New (UUID) |
|---------------|------------|
| `$org_id == 1` | `$org_id === $platformUuid` |
| `$org_id > 1` | `$org_id !== $platformUuid` |
| `getEffectiveOrganisationId()` | **REMOVED** |
| `Organisation::find(1)` | `Organisation::getPlatformOrganisation()` |
| `users.organisation_id INT` | `users.organisation_id UUID FK` |
| Hardcoded platform ID | Cached platform UUID |

---

## 🚀 Next Steps for Documentation

1. **Create UUID version** of this guide
2. **Remove all references** to `getEffectiveOrganisationId()`
3. **Add platform UUID resolver** to all priority checks
4. **Update SQL examples** with UUID schema
5. **Add note about foreign key constraint** ensuring data integrity
6. **Document new User helpers** (`hasTenantOrganisation()`, `getOwnedOrganisation()`, `switchToOrganisation()`)

The **structure and logic** are solid - just need to **remove the integer assumptions** and **embrace UUIDs** throughout.
## Architecture Review: Authentication & Routing Documentation

### Overall Assessment: **Excellent Documentation, But Needs UUID Alignment**

This is a **well-written developer guide** with clear flows and debugging info. However, it's **still in the integer-ID mindset** and needs updating to match your new UUID architecture.

---

## 📊 What's Good (Keep)

| Section | Why Keep |
|---------|----------|
| **Priority System structure** | 8-level hierarchy is solid |
| **Flow diagrams** | Clear visualization |
| **Debugging toolkit** | Practical commands |
| **Quick reference** | Great for onboarding |
| **Two-layer defence** | Still valid with UUIDs |
| **Log markers** | Excellent for debugging |

---

## 🔧 What Needs UUID Updates

### 1. **Hardcoded `org_id = 1` Assumptions** ❌

**Current (integer):**
```php
// Everywhere in the doc:
- `organisation_id = 1`
- `org_id == 1`
- `organisation_id > 1`
- `return 1; // platform fallback`
```

**Needs to become (UUID):**
```php
// Get platform UUID once
$platformOrgId = Cache::remember('platform_org_id', 3600, function() {
    return Organisation::where('type', 'platform')
        ->where('is_default', true)
        ->value('id');
});

// Compare with UUID, not integer
if ($user->organisation_id === $platformOrgId) { ... }
if ($user->organisation_id !== $platformOrgId) { ... }
```

---

### 2. **`getEffectiveOrganisationId()` Must Go** ❌

**Current doc says:**
```php
public function getEffectiveOrganisationId(): int
{
    if ($this->organisation_id > 1 && $this->belongsToOrganisation($this->organisation_id)) {
        return $this->organisation_id;
    }
    return 1;
}
```

**But we already decided to REMOVE this!** ✅

**Replace with:**
```php
// Just use $user->organisation_id directly
// Foreign key constraint guarantees it's valid
```

---

### 3. **Database Schema Section Needs Update** 📝

**Current:**
```sql
users.id INT PRIMARY KEY
users.organisation_id INT
organisations.id INT PRIMARY KEY
user_organisation_roles.user_id INT
user_organisation_roles.organisation_id INT
```

**Needs to become:**
```sql
users.id UUID PRIMARY KEY
users.organisation_id UUID FOREIGN KEY REFERENCES organisations(id)
organisations.id UUID PRIMARY KEY
organisations.type ENUM('platform', 'tenant')
organisations.is_default BOOLEAN
user_organisation_roles.user_id UUID FOREIGN KEY REFERENCES users(id)
user_organisation_roles.organisation_id UUID FOREIGN KEY REFERENCES organisations(id)
```

---

### 4. **Priority 2 (Active Voting) Logic** 🔄

**Current:**
```php
protected function getActiveVoterSlug(User $user): ?object
{
    return DB::table('voter_slugs')
        ->where('user_id', $user->id)
        ->where('is_active', true)
        ->where('expires_at', '>', now())
        ->whereNull('vote_completed_at')
        ->first();
}
```

**Still valid** - voter_slugs use UUIDs, logic unchanged.

---

### 5. **Priority 3 (Active Election) Logic** 🔄

**Current:**
```php
protected function getActiveElectionForUser(User $user): ?object
{
    // Get user's orgs (excluding platform)
    $activeOrgs = DB::table('user_organisation_roles')
        ->join('organisations', 'user_organisation_roles.organisation_id', '=', 'organisations.id')
        ->where('user_organisation_roles.user_id', $user->id)
        ->where('organisations.id', '!=', 1) // ❌ Hardcoded
        ->get();
}
```

**Needs to become:**
```php
protected function getActiveElectionForUser(User $user): ?Election
{
    $platformOrgId = $this->getPlatformOrgId();
    
    $activeOrgs = $user->organisations()
        ->where('type', 'tenant') // Use type, not ID exclusion
        ->get();
    
    foreach ($activeOrgs as $org) {
        $activeElection = Election::where('organisation_id', $org->id)
            ->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->first();
        
        if ($activeElection) {
            return $activeElection;
        }
    }
    
    return null;
}
```

---

### 6. **Priority 4 (Missing Organisation Handler)** 🔄

**Current:**
```php
protected function handleMissingOrganisation(User $user): RedirectResponse
{
    $effectiveOrgId = $user->getEffectiveOrganisationId(); // ❌ Remove
    
    if ($effectiveOrgId == 1) { // ❌ Hardcoded
        if ($user->onboarded_at === null) {
            return redirect()->route('dashboard.welcome');
        }
        return redirect()->route('dashboard');
    }
    
    $organisation = Organisation::find($effectiveOrgId);
    return redirect()->route('organisations.show', $organisation->slug);
}
```

**Needs to become:**
```php
protected function handleMissingOrganisation(User $user): RedirectResponse
{
    $platformOrgId = $this->getPlatformOrgId();
    
    if ($user->organisation_id === $platformOrgId) {
        if ($user->onboarded_at === null) {
            return redirect()->route('dashboard.welcome');
        }
        return redirect()->route('dashboard');
    }
    
    // User has a tenant org
    $organisation = $user->organisation;
    return redirect()->route('organisations.show', $organisation->slug);
}
```

---

### 7. **Priority 6-7 (Role-Based Routing)** 🔄

**Current:**
```php
if ($role === 'admin') {
    $org = Organisation::find($user->organisation_id);
    return redirect()->route('organisations.show', $org->slug);
}
```

**Still valid** - just ensure routes use slugs or UUIDs, not integer IDs.

---

### 8. **Quick Fix Script** 📝

**Current script uses integer IDs - needs update**

```php
// Change from:
DB::table('user_organisation_roles')->insert([
    'user_id' => $userId,
    'organisation_id' => 1,
    // ...
]);

// To:
$platformOrgId = Organisation::where('type', 'platform')
    ->where('is_default', true)
    ->value('id');

DB::table('user_organisation_roles')->insert([
    'user_id' => $userId,
    'organisation_id' => $platformOrgId,
    'role' => 'member',
    // ...
]);
```

---

## 📋 Updated Documentation Checklist

| Section | Status | Changes Needed |
|---------|--------|----------------|
| System Overview | ✅ Keep | None |
| Core Architecture | 🔄 Update | Replace `org_id=1` with platform UUID lookup |
| Priority System | ✅ Keep | Structure unchanged |
| Database Schema | 🔄 Update | Add UUID, type, is_default |
| Pivot Tables | ✅ Keep | Logic unchanged |
| Authentication Flow | ✅ Keep | Diagrams still valid |
| Registration Flow | ✅ Keep | insertOrIgnore() still works |
| Login Flow | 🔄 Update | Remove getEffectiveOrganisationId() |
| 8 Priorities | 🔄 Update | Replace `==1` with platform UUID |
| Common Issues | 🔄 Update | Update debug commands |
| Debugging Toolkit | 🔄 Update | Use UUIDs in queries |
| Quick Reference | 🔄 Update | Update all SQL examples |

---

## ✅ Summary of Changes

| Old (Integer) | New (UUID) |
|---------------|------------|
| `$org_id == 1` | `$org_id === $platformUuid` |
| `$org_id > 1` | `$org_id !== $platformUuid` |
| `getEffectiveOrganisationId()` | **REMOVED** |
| `Organisation::find(1)` | `Organisation::getPlatformOrganisation()` |
| `users.organisation_id INT` | `users.organisation_id UUID FK` |
| Hardcoded platform ID | Cached platform UUID |

---

## 🚀 Next Steps for Documentation

1. **Create UUID version** of this guide
2. **Remove all references** to `getEffectiveOrganisationId()`
3. **Add platform UUID resolver** to all priority checks
4. **Update SQL examples** with UUID schema
5. **Add note about foreign key constraint** ensuring data integrity
6. **Document new User helpers** (`hasTenantOrganisation()`, `getOwnedOrganisation()`, `switchToOrganisation()`)

The **structure and logic** are solid - just need to **remove the integer assumptions** and **embrace UUIDs** throughout.
