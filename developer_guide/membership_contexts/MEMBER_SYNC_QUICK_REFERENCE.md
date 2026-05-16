# Member Sync Quick Reference

## The Bug: Why Nab Raj Roshyara Appeared in Committee But Not Members List

### Data Before Fix:
```
Members Table:        5 imported members (John, Jane, Bob, Krishna, test@test.de)
OrganisationUsers:    0 (empty)
Committee Projection: Nab Roshyara (member_id = user.id) ← Orphaned!
```

The problem: Committee had a reference to Nab Roshyara's **User ID**, but:
- No Member record existed for that ID
- No OrganisationUser record existed
- Members list queries the Member table → **Nab not found**
- Committee queries projection → **Nab found**

---

## The 4-Step Debugging Process

### Step 1: Identify the Data Mismatch
```bash
# Committee shows member, but members page doesn't
# → Check if member exists in members table
SELECT * FROM members WHERE organisation_id = ?
# Found: 5 imported members, but NOT Nab Roshyara's ID
```

### Step 2: Trace the Orphaned Reference
```bash
# Committee_member_projection has Nab, but where is it from?
SELECT * FROM committee_member_projection
# Found: member_id = a1ca22ee-eb04-48e5-b9ef-ad95ad059ce9
# Check: Does this ID exist in members? NO
# Check: Does this ID exist in users? YES
# → Nab is a User, not a Member!
```

### Step 3: Find the Missing Links
```bash
# User exists, but are they linked to organisation?
SELECT * FROM organisation_users 
WHERE user_id = 'a1ca22ee-...' AND organisation_id = ?
# Result: EMPTY
# → User has no OrganisationUser record!
```

### Step 4: Find Where the Link Should Be Created
```bash
# Where does committee assignment happen?
# app/Http/Controllers/Api/Governance/CommitteeMemberController.php::store()
# → Only checks if user exists, doesn't check org membership
# → Doesn't create Member record
# → Allows orphaned reference
```

---

## The 3-Part Fix

### Fix #1: API Member Assignment
**File:** `app/Http/Controllers/Api/Governance/CommitteeMemberController.php`

```php
// Add validation + auto-create
1. Check user exists
2. Check user is organisation user (NEW) ← Prevents wrong org users
3. Auto-create Member record (NEW) ← Maintains data consistency
4. Assign to committee
```

### Fix #2: CSV Import
**File:** `app/Contexts/Membership/Infrastructure/Services/TenantAuthProvisioningAdapter.php`

```php
// Create BOTH records
1. OrganisationUser (links member to organisation)
2. Member (with personal_info JSON + org_user_id)
```

### Fix #3: Member Search
**File:** `app/Http/Controllers/Committee/MemberSearchController.php`

```php
// Search BOTH types
1. Search imported members (in personal_info::jsonb)
2. Search organisation users (in users table)
3. Deduplicate and return
```

---

## Key Insight: Three Data Worlds

```
┌──────────────────────────────────────────────┐
│  MEMBERS PAGE (/organisations/{id}/members)  │
│  Queries: members table                      │
│  Display: Name + Email from personal_info    │
└──────────────────────────────────────────────┘

┌──────────────────────────────────────────────┐
│  COMMITTEE PAGE (committee dashboard)        │
│  Queries: committee_member_projection table  │
│  Display: Name from projection               │
└──────────────────────────────────────────────┘

┌──────────────────────────────────────────────┐
│  AUTHORITY LAYER (LinkerInterface)           │
│  Records: user → organisation_user → member  │
│  Links: All three must be in sync            │
└──────────────────────────────────────────────┘

BEFORE FIX: Nab only in committee projection
AFTER FIX:  Nab in members table + org_users + projection
```

---

## Verification Checklist

After implementing the fix, verify:

- [ ] CSV import creates both Member + OrganisationUser records
- [ ] Imported members appear in `/organisations/{id}/members`
- [ ] Members are searchable in committee assignment modal
- [ ] Committee assignment auto-creates Member if missing
- [ ] Assigning user to committee doesn't create orphaned references
- [ ] Both imported members and org users appear in search results
- [ ] No member appears in committee but not in members list

---

## PostgreSQL JSON Notes

The fix uses PostgreSQL JSON operators:

```sql
-- Extract field from personal_info JSONB
(personal_info::jsonb->>'fullName') ILIKE ?

-- NOT MySQL syntax:
json_extract(personal_info, '$.fullName')
```

Always cast to `::jsonb` for PostgreSQL operators.

---

## Prevention Rules

When adding member operations:

1. **Always validate** user is org member before assignment
2. **Always create** Member record when assigning to committee
3. **Always search** both imported members AND org users
4. **Always link** user → organisation_user → member
5. **Always test** both CSV import and direct assignment paths

---

## Related Documentation

- `DEBUGGING_MEMBER_SYNC_ISSUE.md` - Full debugging walkthrough
- `../MEMBER_IMPORT_ARCHITECTURE.md` - CSV import design
- `../COMMITTEE_ARCHITECTURE.md` - Committee design

---

## Commit Reference

Commits implementing the fix:
- `CommitteeMemberController.php` - Auto-create Member on assignment
- `TenantAuthProvisioningAdapter.php` - Create OrganisationUser for imports
- `MemberSearchController.php` - Search both member types
- `MemberController.php` - Display personal_info JSON

**All 19 unit tests passing** (verified after fix)
