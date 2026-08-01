## Architecture Review: User Model Lifecycle & Pivot System

### Overall Assessment: **Good Foundation, But Over-Engineered**

Your architecture works but has **unnecessary complexity**. Let me break down what to keep, what to simplify, and what to fix.

---

## ✅ What's Good (Keep)

### 1. Pivot Table as Source of Truth
```sql
user_organisation_roles with UNIQUE(user_id, organisation_id)
```
**Why keep:** Perfect. Single source of truth, prevents duplicates.

### 2. `belongsToOrganisation()` Method
```php
public function belongsToOrganisation(int $organisationId): bool
```
**Why keep:** Simple, clear, does one thing well.

### 3. Platform Organisation Fallback
**Why keep:** Essential safety net for orphaned users.

---

## ⚠️ What's Over-Engineered (Simplify)

### 1. **Two Creation Points for Pivot** ❌

**Current:**
- RegisterController creates pivot
- User::created() creates pivot (backup)
- Both use insertOrIgnore()

**Problem:** You're solving a race condition that doesn't exist.

**Simplify To:**
```php
// ONLY in RegisterController - single source of truth
public function store(Request $request)
{
    DB::transaction(function () use ($request) {
        $user = User::create([...]);
        
        // Single, explicit pivot creation
        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $this->getPlatformOrg()->id,
            'role' => 'member'
        ]);
    });
}

// Remove User::created() hook entirely
```

**Why:** 
- Registration is the **only** way users enter system
- Seeders/commands can create pivots explicitly
- Less code, less magic, fewer failure points

---

### 2. **`getEffectiveOrganisationId()`** ❌

**Current:** Complex logic checking pivots, falling back to platform

**Problem:** This method shouldn't exist. It's masking data corruption.

**Simplify To:**
```php
// REMOVE this method entirely

// Instead, ensure data is ALWAYS valid:
// 1. Add database constraint
ALTER TABLE users ADD CONSTRAINT fk_user_organisation 
    FOREIGN KEY (organisation_id) REFERENCES organisations(id);

// 2. Never allow NULL
$table->uuid('organisation_id')->nullable(false);

// 3. In code, just use $user->organisation_id directly
// If it's wrong, fix the data, don't paper over it
```

**Why:** 
- Valid data doesn't need "effective" logic
- Corrupted data needs fixing, not hiding
- KISS principle: if organisation_id is wrong, crash loudly

---

### 3. **User::boot() Hook** ❌

**Current:** Tries to fix missing pivots and NULL org_ids

**Problem:** Magic behavior that hides bugs in calling code.

**Simplify To:**
```php
// REMOVE entire booted() hook

// Instead, make calling code responsible:
class UserController extends Controller
{
    public function store(Request $request)
    {
        DB::transaction(function () {
            $user = User::create([...]);
            
            // Explicitly create pivot
            $user->organisations()->attach(
                $this->getPlatformOrg()->id,
                ['role' => 'member']
            );
            
            // Explicitly set organisation_id
            $user->update(['organisation_id' => $this->getPlatformOrg()->id]);
        });
    }
}
```

**Why:**
- No magic = predictable behavior
- Caller owns the responsibility
- Easier to debug (stack trace goes to controller, not model)

---

### 4. **Four Invariants** → **Simplify to Two** ✅

**Current:** Four complex invariants with edge cases

**Simplify To:**

```php
// INVARIANT 1: Every user belongs to platform
$user->organisations()->where('type', 'platform')->exists(); // MUST be true

// INVARIANT 2: user.organisation_id points to a valid membership
$user->belongsToOrganisation($user->organisation_id); // MUST be true
```

**Remove these:**
- ~~"organisation_id never NULL"~~ (handled by DB constraint)
- ~~"Pivot exists for assigned org"~~ (same as Invariant 2)

---

## 🔧 Critical Fixes Needed

### 1. **Add Foreign Key Constraint** (Missing!)

```php
// CURRENT (wrong):
$table->foreignId('organisation_id')->nullable();

// SHOULD BE:
$table->uuid('organisation_id');
$table->foreign('organisation_id')
      ->references('id')
      ->on('organisations')
      ->onDelete('restrict'); // NEVER allow delete if users exist
```

**Why critical:** Without FK, you can delete organisations with active users → orphaned data.

---

### 2. **Use Models, Not DB Facade** 

```php
// CURRENT (scattered DB::table calls):
DB::table('user_organisation_roles')->insertOrIgnore([...]);

// SHOULD BE:
UserOrganisationRole::firstOrCreate([
    'user_id' => $user->id,
    'organisation_id' => $orgId
], ['role' => 'member']);
```

**Why:** Models give you events, validation, and consistent behavior.

---

### 3. **Fix Migration Logic**

```php
// CURRENT migration tries to "fix" data
// PROBLEM: Migrations should migrate schema, not fix data

// BETTER: Create command for data fixes
php artisan user:fix-stale-organisations

// Migration only does schema:
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->foreign('organisation_id')
              ->references('id')
              ->on('organisations')
              ->onDelete('restrict');
    });
}
```

---

## 📊 Simplified Architecture Diagram

```
┌─────────────────┐
│   Registration  │
│   Controller    │
└────────┬────────┘
         │ 1. Create User
         │ 2. Create Platform Pivot
         │ 3. Set organisation_id
         ↓
┌─────────────────┐     ┌─────────────────────────┐
│      User       │────▶│  user_organisation_roles │
│                 │     │  (source of truth)       │
│ organisation_id │◀────│  - user_id              │
└─────────────────┘     │  - organisation_id      │
         │              │  - role                  │
         │              └─────────────────────────┘
         │
         │ Always points to
         │ a valid pivot
         ↓
┌─────────────────┐
│   Queries use   │
│ organisation_id │
│ directly (no    │
│ "effective"     │
│ methods)        │
└─────────────────┘
```

---

## ✅ Final Recommendation

### Keep:
- ✅ Pivot table as source of truth
- ✅ UNIQUE constraint
- ✅ `belongsToOrganisation()` method
- ✅ Platform fallback concept

### Remove/Simplify:
- ❌ Remove User::booted() hook
- ❌ Remove `getEffectiveOrganisationId()`
- ❌ Remove dual pivot creation
- 🔧 Use models, not DB facade
- 🔧 Add missing FK constraints

### New Rules:
1. **One way to create pivots**: In controller, explicitly
2. **One source of truth**: organisation_id must match pivot
3. **No magic fixes**: If data is wrong, fix it with commands, not model hooks

**Result:** Less code, fewer edge cases, more predictable behavior.
## Architecture for Demo → Paid Flow

### Business Case Summary
1. User registers → gets **publicdigit** (demo org)
2. Explores demo elections
3. Satisfied → creates **their own organisation**
4. System **moves them** from publicdigit to their org
5. Keep platform membership for support/updates

---

## ✅ Simplified Architecture

### Database Structure (Already Have)
```sql
organisations:
- id (UUID)
- type: 'platform' | 'tenant'
- is_default: boolean  (only for platform)
- name: 'PublicDigit' | 'Acme Inc'

user_organisation_roles:  -- Source of truth
- user_id
- organisation_id
- role: 'member' | 'admin' | 'owner'

users:
- id
- organisation_id (current/last used)
```

### Key Principle
**Users can belong to MULTIPLE orgs, but have ONE "current" org**

---

## 🔄 The User Journey

### Step 1: Registration (Demo Mode)
```php
// After registration:
$user = User::create([...]);
$publicdigit = Organisation::getDefaultPlatform(); // type='platform'

// User belongs to publicdigit (for demo)
$user->organisations()->attach($publicdigit->id, [
    'role' => 'member'
]);

// Current org = publicdigit (see demo)
$user->update(['organisation_id' => $publicdigit->id]);
```

**Result:** User sees demo elections, explores features

---

### Step 2: Create Their Own Organisation
```php
class OrganisationController
{
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            // 1. Create new tenant organisation
            $newOrg = Organisation::create([
                'name' => $request->name,
                'type' => 'tenant',
                'is_default' => false
            ]);
            
            // 2. Add user as OWNER of new org
            $user->organisations()->attach($newOrg->id, [
                'role' => 'owner'  // Special role!
            ]);
            
            // 3. MOVE them to new org (change current)
            $user->update(['organisation_id' => $newOrg->id]);
            
            // 4. They STILL belong to publicdigit (for support)
            // Pivot already exists, we keep it
        });
    }
}
```

---

## 🧠 Critical Architecture Decisions

### 1. **Multiple Memberships is the Key**
```php
// User belongs to BOTH:
$user->organisations()->pluck('name');
// ['PublicDigit', 'Acme Inc']  ✅ Both exist!

// But current is Acme Inc:
$user->organisation_id; // Acme Inc's UUID
```

### 2. **Role Distinction Matters**
```php
// In publicdigit: role = 'member'
// In their org: role = 'owner'

// Later they can add staff:
$user2->organisations()->attach($newOrg->id, [
    'role' => 'admin'  // Different permissions
]);
```

### 3. **Keep Platform Membership FOREVER**
```php
// NEVER detach from platform org
// Why? 
// - Support access
// - Platform announcements
// - Cross-tenant features
// - Emergency fallback
```

---

## 📊 Visual Flow

```
REGISTRATION
┌─────────────────┐
│  User Created   │
└────────┬────────┘
         │
         ▼
┌─────────────────┐     ┌─────────────────┐
│  Belongs to:    │────▶│  Current Org:   │
│  - PublicDigit  │     │  - PublicDigit  │
│  (role=member)  │     │  (see demo)     │
└─────────────────┘     └─────────────────┘

CREATE ORGANISATION
┌─────────────────┐
│  Create Acme    │
│  (type=tenant)  │
└────────┬────────┘
         │
         ▼
┌─────────────────┐     ┌─────────────────┐
│  Belongs to:    │────▶│  Current Org:   │
│  - PublicDigit  │     │  - Acme Inc     │
│  - Acme Inc     │     │  (run real      │
│  (role=owner)   │     │   elections)    │
└─────────────────┘     └─────────────────┘

LATER - SWITCH BACK
┌─────────────────┐
│  User clicks    │
│  "Switch to     │
│   PublicDigit"  │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Current Org:   │
│  - PublicDigit  │
│  (support/demo) │
└─────────────────┘
```

---

## 💻 Clean Implementation

### User Model Methods
```php
class User extends Model
{
    // Simple check - any org of this type?
    public function hasTenantOrganisation(): bool
    {
        return $this->organisations()
            ->where('type', 'tenant')
            ->exists();
    }
    
    // Get their "own" org (where they're owner)
    public function getOwnedOrganisation(): ?Organisation
    {
        return $this->organisations()
            ->wherePivot('role', 'owner')
            ->where('type', 'tenant')
            ->first();
    }
    
    // Switch context (simple!)
    public function switchToOrganisation(Organisation $org): void
    {
        if (!$this->belongsToOrganisation($org->id)) {
            throw new Exception("Don't belong there!");
        }
        
        $this->update(['organisation_id' => $org->id]);
    }
}
```

### Dashboard Logic
```php
class DashboardController
{
    public function index()
    {
        $user = auth()->user();
        
        // If user has no tenant org yet, show demo
        if (!$user->hasTenantOrganisation()) {
            return view('demo.dashboard', [
                'elections' => Election::where('organisation_id', 
                    Organisation::getDefaultPlatform()->id
                )->get()
            ]);
        }
        
        // They have their own org - show real data
        return view('dashboard', [
            'elections' => Election::where('organisation_id', 
                $user->organisation_id  // current org
            )->get()
        ]);
    }
}
```

---

## ✅ Why This Architecture Wins

| Principle | How It's Satisfied |
|-----------|-------------------|
| **Simple** | Just pivot table + current_org field |
| **Explicit** | No magic - you see when we attach/detach |
| **Flexible** | Users can belong to multiple orgs |
| **Safe** | Platform membership always retained |
| **Testable** | Clear state transitions |

---

## 🚫 What NOT to Do

```php
// DON'T delete platform membership
$user->organisations()->detach($platform->id); // ❌ BAD!

// DON'T use complex "effective" logic
$org = $user->getEffectiveOrganisation(); // ❌ MAGIC!

// DON'T let users create orgs without membership
$org = Organisation::create([...]); // ❌ Who owns it?
```

---

## 🔑 Key Insight

**The architecture already supports this!** Your existing:
- Pivot table with roles ✅
- Current organisation_id ✅
- Platform org with type='platform' ✅

You just need to:
1. Add `role = 'owner'` for org creators
2. Keep platform membership FOREVER
3. Simple switch when they create org

**No new tables. No complex logic. Just use what you have.**