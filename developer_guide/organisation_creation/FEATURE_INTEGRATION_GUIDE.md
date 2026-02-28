# organisation Creation & Members - Complete Feature Integration Guide

**Document:** Feature Integration Overview
**Date:** February 23, 2026
**Version:** 1.0.0

---

## 🎯 Overview

This document explains how **organisation Creation** integrates with the broader platform features, specifically focusing on:

1. **organisation Creation** (Modal form)
2. **Members Management** (List & administration)
3. **Multi-tenancy** (organisation isolation)
4. **Role-based Permissions** (Admin, voter roles)

---

## 🔄 Feature Workflow

### Complete User Journey

```
┌─────────────────────────────────────────────────────────────────┐
│                     USER JOURNEY                                 │
└─────────────────────────────────────────────────────────────────┘

1. USER REGISTERS
   ├─ Email: user@example.com
   ├─ Password: [encrypted]
   └─ Status: Email verified

2. USER LOGS IN & LANDS ON DASHBOARD
   ├─ No organizations yet
   ├─ Sees "Create organisation" card
   └─ Click to proceed

3. organisation CREATION MODAL OPENS
   ├─ Step 0: Education overlay
   ├─ Step 1: Basic info (name, email)
   ├─ Step 2: Address
   ├─ Step 3: Representative (defaulted to "I am the representative")
   └─ Submit → POST /api/organizations

4. BACKEND PROCESSING
   ├─ Create organisation record
   ├─ Attach current user as ADMIN
   │  └─ user_organization_roles pivot: role = "admin"
   ├─ Attach representative (if different person)
   │  └─ user_organization_roles pivot: role = "voter"
   ├─ Update user's organisation_id
   ├─ Send confirmation email
   └─ Return success response

5. REDIRECT TO organisation DASHBOARD
   ├─ User sees: organisation.show page
   ├─ Stats show member count
   ├─ User is admin
   └─ Can manage organisation

6. USER NAVIGATES TO MEMBERS PAGE
   ├─ URL: /members/index
   ├─ MemberController queries organisation members
   ├─ Only shows members of CURRENT organisation
   ├─ Shows: Name, Email, Region, Role, Joined date
   ├─ User appears once with role "admin"
   └─ If representative added, they appear with role "voter"

7. USER CAN NOW:
   ├─ View members (/members/index)
   ├─ Filter by name, email, role
   ├─ Sort by any column
   ├─ See member statistics
   ├─ Import more members (future feature)
   ├─ Create elections
   └─ Run voting process
```

---

## 📊 Data Model Relationships

### organisation Table

```sql
organizations:
  id                  INTEGER PRIMARY KEY
  name               VARCHAR (organisation name)
  email              VARCHAR (organisation contact email)
  slug               VARCHAR (URL-friendly name)
  address            JSON (street, city, zip, country)
  representative     JSON (name, role, email from form)
  created_by         INTEGER FK → users.id
  created_at         TIMESTAMP
  updated_at         TIMESTAMP
```

### Users Table

```sql
users:
  id                 INTEGER PRIMARY KEY
  email              VARCHAR UNIQUE (email@example.com)
  name               VARCHAR (user's full name)
  password           VARCHAR (bcrypt hash)
  organisation_id    INTEGER FK → organizations.id
  region             VARCHAR (user's region: Bayern, etc.)
  created_at         TIMESTAMP
  updated_at         TIMESTAMP
  ...                (other fields)
```

### Pivot Table: user_organization_roles

```sql
user_organization_roles:  (many-to-many bridge)
  id                 INTEGER PRIMARY KEY
  user_id            INTEGER FK → users.id
  organisation_id    INTEGER FK → organizations.id
  role               ENUM ('admin', 'commission', 'voter', ...)
  permissions        JSON (optional, future feature)
  assigned_at        TIMESTAMP (when user was added)
  created_at         TIMESTAMP
  updated_at         TIMESTAMP

  UNIQUE(user_id, organisation_id)  ← Prevents duplicate membership
```

### Relationships Diagram

```
┌─────────────────┐
│  organisation   │
│  - id=1         │
│  - name="Club"  │
└────────┬────────┘
         │
         │ (1 to many)
         │
      ┌──┴──────────────────────────────────┐
      │                                      │
      │ user_organization_roles             │
      │ (Pivot table)                       │
      │                                      │
      ├──────────────────────────────────────┤
      │ user_id │ org_id │ role             │
      ├──────────────────────────────────────┤
      │    1    │   1    │ admin      ← User 1│
      │    2    │   1    │ voter      ← User 2│
      │    3    │   1    │ commission ← User 3│
      └──────────────────────────────────────┘

         ↓ (references)

      ┌────────────────┐
      │     Users      │
      ├────────────────┤
      │ 1: john@ex.com │
      │ 2: jane@ex.com │
      │ 3: bob@ex.com  │
      └────────────────┘
```

---

## 🔐 Multi-Tenancy Implementation

### organisation Isolation at Each Layer

#### 1. Database Level
```php
// Query: Only show users of THIS organisation
$organisation->users()  // Scoped to organisation.id
    ->get();

// Result: Only pivot records where organisation_id = current_org
```

#### 2. Model Level (Global Scope)
```php
// app/Models/organisation.php
class organisation extends Model {
    protected static function boot() {
        parent::boot();

        // Automatically filter by tenant
        static::addGlobalScope('tenant', function ($query) {
            $query->where('organisation_id', session('current_organisation_id'));
        });
    }
}
```

#### 3. Middleware Level
```php
// app/Http/Middleware/TenantContext.php
// Extracts organisation from URL/session
// Sets session('current_organisation_id')
```

#### 4. Controller Level
```php
// MemberController.php
public function index(Request $request) {
    $organizationId = session('current_organisation_id');

    if (!$organizationId) {
        abort(403, 'No organisation selected');
    }

    $organisation = organisation::findOrFail($organizationId);

    // Verify user is member
    if (!$organisation->users()->where('users.id', auth()->id())->exists()) {
        abort(403, 'Not a member');
    }

    // Safe to query - user is verified
}
```

### Protection Flow

```
Request comes in
    ↓
Middleware verifies organisation context
    ↓
Controller loads organisation
    ↓
Controller verifies user is member
    ↓
Query runs scoped to organisation
    ↓
Results returned for current org only
    ↓
No data leak possible ✓
```

---

## 👥 Roles & Permissions

### User Roles in organisation

| Role | Capabilities | Created By |
|------|-------------|-----------|
| **admin** | View/manage members, create elections, configure settings | System (during org creation) |
| **commission** | View members, create elections, manage voting | Manual assignment (future) |
| **voter** | Participate in elections, cast votes | System (representative) or manual add |

### How Roles Are Created

```
organisation Creation:
    ├─ User creates org
    ├─ System: User → "admin" role
    └─ System: Representative → "voter" role (if different person)

Members Import (future):
    ├─ Admin uploads CSV
    ├─ System: Create users from CSV
    └─ System: Assign users → "voter" role

Manual Assignment (future):
    ├─ Admin clicks "Add member"
    ├─ Admin selects role
    └─ System: User → selected role
```

---

## 💾 Database Operations During organisation Creation

### Step-by-Step Backend Processing

```php
POST /api/organizations

// 1. Validate request
StoreOrganizationRequest validates all fields

// 2. Create organisation
$organisation = organisation::create([
    'name' => $request->name,
    'email' => $request->email,
    'address' => $request->address,
    'representative' => $request->representative,
    'created_by' => $user->id,
    'slug' => Str::slug($request->name),
]);
// Result: organizations table has new record

// 3. Attach current user as admin
$organisation->users()->attach($user->id, [
    'role' => 'admin',
    'assigned_at' => now(),
]);
// Result: user_organization_roles has record
//         user_id: current user
//         role: admin

// 4. Update user's organisation_id
$user->update(['organisation_id' => $organisation->id]);
// Result: users table updated

// 5. Handle representative (if not self)
if (!$isSelfRepresentative) {
    if (strtolower($representativeEmail) !== strtolower($user->email)) {
        $representativeUser = User::firstOrCreate(
            ['email' => $representativeEmail],
            [/* ... */]
        );

        if (!$organisation->users()
                ->where('users.id', $representativeUser->id)
                ->exists()) {
            $organisation->users()->attach($representativeUser->id, [
                'role' => 'voter',
                'assigned_at' => now(),
            ]);
        }

        $representativeUser->update(['organisation_id' => $organisation->id]);
    }
}
// Result: If representative is different person:
//         - User created (if new)
//         - Attached with voter role
//         - Sent invitation email

// 6. Send confirmation email
Mail::to($organisation->email)->send(new OrganizationCreatedMail(
    $organisation,
    $user
));
// Result: Confirmation email sent

// 7. Return success response
return response()->json([
    'success' => true,
    'message' => 'Organisation erfolgreich erstellt!',
    'redirect_url' => route('organizations.show', $organisation->slug),
    'organisation' => [/* ... */]
], 201);
```

### Database State After Creation

```
organizations:
  id: 1
  name: "Turnverein e.V."
  email: "contact@turnverein.de"
  created_by: 1
  created_at: 2026-02-23 10:00:00

users:
  id: 1 (unchanged - already existed)
  email: "user@example.com"
  organisation_id: 1 (updated)

user_organization_roles:
  id: 1
  user_id: 1 (current user)
  organisation_id: 1
  role: "admin"
  assigned_at: 2026-02-23 10:00:00
```

---

## 🔗 Feature Integration Points

### 1. organisation Creation → Member View

```
User creates org
    ↓
Redirected to /organizations/{slug}
    ↓
Shows organisation dashboard
    ↓
User clicks "Members" link
    ↓
Navigates to /members/index
    ↓
MemberController loads members via organisation.users()
    ↓
Only shows members of CURRENT organisation
    ↓
User (admin) + Representative (voter) displayed
```

### 2. organisation Creation → Election Setup

```
User creates org
    ↓
User can now create elections
    ↓
Elections are scoped to organisation
    ↓
Members can vote in org elections
    ↓
Results are org-specific
```

### 3. organisation Creation → Permissions

```
User creates org
    ↓
User automatically gets "admin" role
    ↓
Admin can:
    ├─ View members
    ├─ Create elections
    ├─ Configure settings (future)
    └─ Manage permissions (future)
```

---

## 🧪 Testing the Integration

### Test 1: Complete Creation Flow

```bash
# Create organisation as user@example.com
POST /api/organizations
{
  "name": "Test Org",
  "email": "org@example.com",
  "address": {...},
  "representative": {
    "name": "John",
    "role": "Admin",
    "email": "john@example.com",
    "is_self": false
  },
  "accept_gdpr": true,
  "accept_terms": true
}

# Verify database state
organisation.first()  # Should exist
users.find(1).organisations().count()  # Should be 1
users.find(1).organizations().first().pivot.role  # Should be "admin"
```

### Test 2: Member Visibility

```bash
# After org creation:
GET /members/index

# Should return:
[
  {
    "id": 1,
    "name": "User",
    "email": "user@example.com",
    "role": "admin",
    "assigned_at": "2026-02-23 10:00:00"
  },
  {
    "id": 2,
    "name": "John",
    "email": "john@example.com",
    "role": "voter",
    "assigned_at": "2026-02-23 10:00:00"
  }
]
```

### Test 3: Multi-tenancy Isolation

```bash
# Create org 1 as User A
# Create org 2 as User B

# Login as User A, go to /members/index
# Should see: Only members of org 1

# Login as User B, go to /members/index
# Should see: Only members of org 2

# Verify User A cannot access org 2 data
GET /api/organizations/{org2_id}  # Should return 403
```

### Test 4: Duplicate Prevention

```bash
# Create org with current user as representative
# Go to /members/index
# Should see: 1 member (user as admin, not admin + voter)
```

---

## 📈 Data Integrity Guarantees

### Constraints Protecting Data

```sql
-- Ensure each user can only be added once per org
UNIQUE (user_id, organisation_id) ON user_organization_roles

-- Ensure unique emails
UNIQUE (email) ON users

-- Ensure organisation has owner
FOREIGN KEY (created_by) REFERENCES users(id)
```

### Query Safety

```php
// SAFE: Scoped to organisation
$org->users()->get();
// Returns only this org's users

// SAFE: With authorization check
if ($org->users()->where('users.id', auth()->id())->exists()) {
    // User is member, safe to proceed
}

// UNSAFE: Without scope
User::all();  ← Returns ALL users globally! Don't do this

// UNSAFE: Without org check
$org = organisation::find($id);
$org->users()->get();  ← What if auth'd user isn't member?
```

---

## 🚀 Deployment Checklist

Before deploying changes to organisation creation:

- [ ] **Database**
  - [ ] Run migrations: `php artisan migrate`
  - [ ] Verify constraints exist: `SHOW INDEX FROM users`
  - [ ] No duplicate emails in users table

- [ ] **Code**
  - [ ] Email match check (line 59 in OrganizationController)
  - [ ] Duplicate membership check (line 73)
  - [ ] is_self defaults to true (line 37 in composable)

- [ ] **Frontend**
  - [ ] Email field hidden by default
  - [ ] All validations working
  - [ ] Translations in all three languages

- [ ] **Tests**
  - [ ] organisation creation tests pass
  - [ ] Member management tests pass
  - [ ] Duplicate prevention tests pass
  - [ ] Multi-tenancy tests pass

- [ ] **Documentation**
  - [ ] README.md updated
  - [ ] DUPLICATE_PREVENTION_GUIDE.md current
  - [ ] API documentation current

- [ ] **Monitoring**
  - [ ] Error logging configured
  - [ ] Duplicate attempt monitoring in place
  - [ ] User feedback mechanism working

---

## 🔧 Common Development Tasks

### Task 1: Add a New Field to organisation

1. **Database**
```bash
php artisan make:migration add_phone_to_organizations
# In migration:
$table->string('phone')->nullable();
```

2. **Form** - Add to Step 1 form fields in useOrganizationCreation.js
```javascript
basic: {
  name: '',
  email: '',
  phone: '',  // ← New
}
```

3. **Validation** - Add validation rule
```javascript
if (!formData.basic.phone?.trim()) {
  errors.phone = 'Phone required'
}
```

4. **Component** - Add form input
```vue
<FormInput
  label="Phone"
  :value="data.phone"
  @input="$emit('update:phone', $event)"
/>
```

5. **API** - Update payload and backend
```php
$organisation->update(['phone' => $request->phone]);
```

### Task 2: Add New Role Type

1. **Database** - Update enum
```php
// In migration or schema
$table->enum('role', ['admin', 'commission', 'voter', 'viewer']);
```

2. **Permissions** - Update role definitions
```php
// app/Models/Role.php or similar
const ADMIN = 'admin';
const COMMISSION = 'commission';
const VOTER = 'voter';
const VIEWER = 'viewer';
```

3. **Members Form** - Add role selector (if needed)
```vue
<select v-model="data.role">
  <option value="admin">Admin</option>
  <option value="commission">Commission</option>
  <option value="voter">Voter</option>
  <option value="viewer">Viewer</option>
</select>
```

### Task 3: Change Default Representative Behavior

**Current behavior:** "I am the representative" is CHECKED by default

**To change:**
```javascript
// In useOrganizationCreation.js line 37 & 94
// Change:  is_self: true
// To:      is_self: false
```

⚠️ **Warning:** This will bring back duplicate member issues! Only change if you update Layer 2 & 3 protections accordingly.

---

## 📚 Related Files & Tests

### Test Files
- `tests/Unit/Controllers/OrganizationControllerTest.php` - Backend tests
- `tests/Feature/OrganizationCreationTest.php` - Full flow tests
- `tests/Feature/MemberManagementTest.php` - Members list tests

### Migration Files
- `database/migrations/2026_02_23_000245_*.php` - Email constraint
- `database/migrations/2026_02_22_*.php` - User organisation roles table

### Component Files
- `resources/js/Composables/useOrganizationCreation.js`
- `resources/js/Components/organisation/OrganizationCreateModal.vue`
- `resources/js/Pages/Members/Index.vue`

### Controller Files
- `app/Http/Controllers/Api/OrganizationController.php`
- `app/Http/Controllers/MemberController.php`

### Model Files
- `app/Models/organisation.php`
- `app/Models/User.php`

---

## 🆘 Troubleshooting Integration Issues

### Issue: New member isn't showing in /members/index

**Checklist:**
1. Is user attached to organisation? (Check user_organization_roles table)
2. Is query scoped to current org? (Check MemberController)
3. Is user authenticated? (Check auth middleware)
4. Is user a member? (Should be, or 403 error)

**Solution:**
```bash
php artisan tinker
> organisation.find(1).users().count()  # Should include new member
```

### Issue: User appears twice in members list

**This should NOT happen.** If it does:
1. Check UNIQUE constraint: `SHOW INDEX FROM user_organization_roles`
2. Check for duplicate pivot records:
```bash
> organisation.find(1).users()->where('users.id', 1).count()
# Should be 1, not 2
```
3. Run duplicate prevention tests

### Issue: Can see members from other organizations

**Critical security issue!**

Check:
1. Is organisation scope applied? `$org->users()->get()`
2. Is user verified as member? `$org->users()->where('users.id', auth()->id()).exists()`
3. Is session context set? `session('current_organisation_id')`

```bash
# Test isolation:
# Login as User A (org 1 member)
# Try to query org 2: Should get 403
# Check that organisation scope is active
```

---

## 📞 Support & Resources

| Need | Resource |
|------|----------|
| **Full architecture** | README.md in this directory |
| **Duplicate prevention details** | DUPLICATE_PREVENTION_GUIDE.md |
| **Quick reference** | QUICK_START_DEVELOPER.md |
| **Backend implementation** | BACKEND_IMPLEMENTATION.md |
| **Members feature** | membership/MEMBER_IMPORT_DEVELOPER_GUIDE.md |

---

**Last Updated:** February 23, 2026
**Status:** Production Ready
**Version:** 1.0.0

---

**Questions?** Check the troubleshooting section or review the related documentation files.
