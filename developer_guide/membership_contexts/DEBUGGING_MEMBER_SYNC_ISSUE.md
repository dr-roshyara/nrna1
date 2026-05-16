# Debugging Guide: Member Sync Issue (CSV Import + Committee Assignment)

**Date:** 2026-05-16  
**Issue:** Nab Raj Roshyara appeared in committee dashboard but NOT in organisation members list  
**Root Cause:** Missing automatic Member record creation when assigning users to committees  
**Status:** ✅ RESOLVED

---

## The Problem

User reported:
- ✅ CSV import created 5 members
- ✅ Committee dashboard showed "Nab Raj Roshyara" as committee member (chair)
- ❌ Organisation members list (`/organisations/{id}/members`) didn't show Nab Raj Roshyara
- ❌ Member search in committee assignment modal couldn't find imported members

---

## Debugging Process

### Phase 1: Data Inconsistency Discovery

**Step 1.1:** Check members table
```bash
# Found in tinker:
$members = Member::withoutGlobalScopes()
    ->where('organisation_id', $org->id)
    ->get(['id']);
// Result: 5 imported members with IDs:
// - 9a487482-23ec-4d84-9714-c3b9219e4bf7
// - b6097770-d1c3-4fc5-a8b9-1d59da542fd4
// - (3 more...)
```

**Step 1.2:** Check committee_member_projection
```bash
# Query showed:
SELECT * FROM committee_member_projection 
WHERE tenant_id = 'a1ca231c-59aa-4950-8b23-75b16d5c176a'

// Result: Found Nab Roshyara with:
// - member_id: a1ca22ee-eb04-48e5-b9ef-ad95ad059ce9
// - member_name: Nab Roshyara
// - member_email: restaurant.namastenepal@gmail.com
```

**Step 1.3:** Cross-check - Does member_id exist in members table?
```bash
# Query:
Member::withoutGlobalScopes()
    ->where('id', 'a1ca22ee-eb04-48e5-b9ef-ad95ad059ce9')
    ->first()

// Result: NULL - Member doesn't exist!
```

**🔴 Discovery:** The committee is referencing a member_id that doesn't exist in the members table.

---

### Phase 2: Identity Chain Analysis

**Step 2.1:** Is the member_id actually a User?
```bash
$user = User::where('id', 'a1ca22ee-eb04-48e5-b9ef-ad95ad059ce9')->first();

// Result: YES - User found:
// - id: a1ca22ee-eb04-48e5-b9ef-ad95ad059ce9
// - name: Nab Roshyara
// - email: restaurant.namastenepal@gmail.com
```

**Step 2.2:** Does this user have an OrganisationUser record?
```bash
$orgUser = OrganisationUser::withoutGlobalScopes()
    ->where('user_id', $user->id)
    ->where('organisation_id', $org->id)
    ->first();

// Result: NULL - No OrganisationUser record
```

**🔴 Discovery:** The user exists but isn't linked to the organisation via OrganisationUser.

---

### Phase 3: Root Cause Analysis

**Question:** How did Nab Roshyara get assigned to the committee without being an organisation member?

**Investigation:** Check how committee members are assigned

Found in `app/Http/Controllers/Api/Governance/CommitteeMemberController.php` (line 115):

```php
// OLD CODE - PROBLEM:
$member = \DB::table('users')
    ->where('id', $memberId->value())
    ->first(['id', 'name', 'email']);

// ❌ ISSUE: Doesn't check if user is an OrganisationUser
// ❌ ISSUE: Doesn't ensure Member record exists
// ❌ ISSUE: Allows assigning non-organisation users to committees
```

**🔴 Root Cause:** When a user is assigned to a committee:
1. System checks if User exists ✅
2. System does NOT check if they're an organisation user ❌
3. System does NOT create Member record ❌
4. Committee assignment succeeds with orphaned reference
5. Member appears in committee but not in members list

---

### Phase 4: Data Flow Analysis

**The two separate worlds:**

```
MEMBERS LIST (Members page)
├── Checks: Member table
├── Searches: personal_info JSON (imported members)
└── Display: Name + Email

COMMITTEE DASHBOARD (Committee page)
├── Checks: committee_member_projection table
├── References: member_id (which can be User ID!)
└── Display: Name from projection (regardless of Member record)
```

**Why the mismatch:**
- Committee assignment directly creates `committee_member_projection` record
- Does NOT require Member record to exist
- Members page queries Member table - so orphaned records don't show

---

## Solutions Implemented

### Solution 1: Auto-create Member on Committee Assignment

**File:** `app/Http/Controllers/Api/Governance/CommitteeMemberController.php`

**Changes:**
```php
// STEP 1: Validate user exists
$user = User::where('id', $memberId->value())->first();
if (!$user) {
    return response()->json(['error' => 'User not found'], 404);
}

// STEP 2: Check user is organisation user (NEW)
$orgUser = OrganisationUser::withoutGlobalScopes()
    ->where('user_id', $memberId->value())
    ->where('organisation_id', $tenantId->value())
    ->first();

if (!$orgUser) {
    return response()->json(
        ['error' => 'User is not a member of this organisation'], 
        403
    );
}

// STEP 3: Auto-create Member record if missing (NEW)
$existingMember = Member::withoutGlobalScopes()
    ->where('id', $memberId->value())
    ->where('organisation_id', $tenantId->value())
    ->first();

if (!$existingMember) {
    Member::withoutGlobalScopes()->create([
        'id' => $memberId->value(),
        'organisation_id' => $tenantId->value(),
        'organisation_user_id' => $orgUser->id,
        'status' => 'active',
        'fees_status' => 'unpaid',
        'joined_at' => now(),
        'personal_info' => json_encode([
            'fullName' => $user->name,
            'email' => $user->email,
            'phone' => null,
        ]),
    ]);
}

// STEP 4: Proceed with assignment (existing code)
```

**Effect:** When someone is assigned to a committee, a Member record is automatically created if it doesn't exist.

---

### Solution 2: Auto-create Member for CSV Imports

**File:** `app/Contexts/Membership/Infrastructure/Services/TenantAuthProvisioningAdapter.php`

**Changes:**
```php
public function createForCsvImport(
    TenantId $tenantId,
    string $email,
    string $fullName,
    array $options = []
): TenantUserId {
    // Create OrganisationUser record for imported member
    $orgUser = OrganisationUser::create([
        'organisation_id' => $tenantId->value(),
        'user_id' => null,  // CSV imports don't have associated users
        'role' => 'member',
        'status' => 'active',
        'joined_at' => now(),
    ]);

    return new TenantUserId($orgUser->id);
}
```

**Effect:** CSV imported members get both:
- OrganisationUser record (links member to organisation)
- Member record (with personal_info JSON)

---

### Solution 3: Search Both Member Types

**File:** `app/Http/Controllers/Committee/MemberSearchController.php`

**Problem:** Member search only searched `users` table, not imported members.

**Changes:**
```php
// Search BOTH imported members and organisation users

// 1. Search imported members (in personal_info JSON)
$importedMembers = Member::withoutGlobalScopes()
    ->where('organisation_id', $organisation->id)
    ->whereRaw("(personal_info::jsonb->>'fullName') ILIKE ?", ["%{$query}%"])
    ->get()
    ->map(function ($member) {
        $personalInfo = json_decode($member->personal_info, true) ?? [];
        return [
            'id' => $member->id,
            'name' => $personalInfo['fullName'] ?? 'Unknown',
            'email' => $personalInfo['email'] ?? 'Unknown',
        ];
    });

// 2. Search organisation users
$organisationUsers = User::withoutGlobalScopes()
    ->where('organisation_id', $organisation->id)
    ->where(function ($builder) use ($query) {
        $builder->where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%");
    })
    ->get()
    ->map(fn ($user) => [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
    ]);

// 3. Combine and deduplicate
$allMembers = collect()
    ->merge($importedMembers)
    ->merge($organisationUsers)
    ->unique('id')
    ->take($limit)
    ->values();
```

**Effect:** Committee member assignment modal can now find both:
- Imported members (CSV upload)
- Organisation users (invited/registered)

---

## Testing & Verification

### Test Scenario 1: CSV Import
```
✅ Upload CSV with 5 members
✅ Members appear in /organisations/{id}/members (with names)
✅ OrganisationUser records created
✅ Member records created with organisation_user_id set
```

### Test Scenario 2: Committee Assignment
```
✅ Go to committee dashboard
✅ Search for imported member by name
✅ Member found in search results
✅ Assign to committee
✅ Member appears in committee
✅ Member still visible in members list
```

### Test Scenario 3: Data Consistency
```
✅ Member table has all members
✅ OrganisationUser table has links
✅ committee_member_projection references valid members
✅ No orphaned references
```

---

## Key Learnings

### 1. Separate Data Worlds
The system has two separate views of members:
- **Members Table:** Authoritative source, used by Members page
- **Committee Projection:** Operational state, used by Committee page

These must stay in sync.

### 2. Three Types of Members

```
TYPE 1: CSV Imported Members
├── Has: Member record + OrganisationUser record
├── No User account
└── Used for bulk membership imports

TYPE 2: Invited Organisation Users
├── Has: User account + OrganisationUser record
├── May have: Member record (auto-created on committee assignment)
└── Used for user invitations

TYPE 3: Orphaned Committee References (BUG - now fixed)
├── Had: Only committee_member_projection reference
├── No Member record
├── No OrganisationUser record
└── Caused: Member appears in committee but not in members list
```

### 3. PostgreSQL JSON Queries

For searching in JSON fields:
```sql
-- PostgreSQL specific (not MySQL):
WHERE (personal_info::jsonb->>'fullName') ILIKE ?
WHERE (personal_info::jsonb->>'email') ILIKE ?

-- NOT: json_extract() (MySQL syntax)
```

### 4. Validation Order Matters

**Wrong order:**
```php
// Check if user exists, then assign to committee
// ❌ User could be unrelated to organisation
```

**Correct order:**
```php
// 1. Check user exists
// 2. Check user is organisation user
// 3. Create Member record if needed
// 4. Then assign to committee
```

---

## Prevention Checklist

When adding new member operations, ensure:

- [ ] **Data consistency check:** Both Member table AND organisation_user table updated
- [ ] **Validation order:** Organisation membership before committee assignment
- [ ] **Search coverage:** Search both imported members AND organisation users
- [ ] **Error messages:** Distinguish between "user not found" and "not an org member"
- [ ] **JSON handling:** Use PostgreSQL syntax for JSON queries
- [ ] **Test both paths:** CSV imports + direct assignment

---

## Files Modified

| File | Change | Purpose |
|------|--------|---------|
| `CommitteeMemberController.php` | Auto-create Member record | Prevent orphaned references |
| `TenantAuthProvisioningAdapter.php` | Create OrganisationUser for CSV imports | Link imported members to organisation |
| `MemberSearchController.php` | Search both imported + org users | Find members in assignment modal |
| `MemberController.php` | Extract personal_info JSON | Display imported member names |

---

## Related Issues Fixed

1. **Issue:** Imported members have NULL organisation_user_id
   - **Solution:** Made column nullable, populated from JSON on display

2. **Issue:** Imported members don't show in members list
   - **Solution:** Updated MemberController to extract names from personal_info

3. **Issue:** Can't find imported members in committee assignment
   - **Solution:** Updated MemberSearchController to search Member table

4. **Issue:** Assigning user to committee doesn't create Member record
   - **Solution:** Auto-create Member on committee assignment

---

## Architecture Diagram: Corrected Flow

```
CSV IMPORT
  ↓
TenantAuthProvisioningAdapter
  ├─ Create OrganisationUser record
  └─ Return TenantUserId (the org_user.id)
  ↓
MemberImportService
  ├─ Create Member aggregate
  ├─ Link to organisation_user_id
  ├─ Save Member with org_user_id
  └─ Store events in outbox
  ↓
Result: Member in both tables ✅

COMMITTEE ASSIGNMENT (User)
  ↓
CommitteeMemberController.store()
  ├─ Validate user exists
  ├─ Check user is organisation user
  ├─ Auto-create Member if missing
  └─ Assign to committee
  ↓
Result: Member created, linked, and assigned ✅

MEMBER SEARCH
  ↓
MemberSearchController.index()
  ├─ Search imported members (JSON)
  ├─ Search organisation users (User table)
  └─ Return combined list
  ↓
Result: Both types found ✅
```

---

## Conclusion

The issue was a **data consistency problem** caused by the system allowing committee assignment without ensuring Member records existed. The fix involved:

1. **Adding validation** to ensure users are organisation members before committee assignment
2. **Auto-creating** Member records to maintain data consistency
3. **Updating search** to cover both member types
4. **Linking** all three data layers (User → OrganisationUser → Member)

This ensures the invariant: **Every committee member must also be an organisation member.**
