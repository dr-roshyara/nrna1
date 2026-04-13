# Voter List Management — Developer Guide

This folder contains documentation for managing voters in real elections.

## 📚 Guides

### 1. [BULK_ASSIGN_BUG_FIX.md](./BULK_ASSIGN_BUG_FIX.md)
**Query Logic Mismatch in Bulk Voter Assignment**

- **Problem:** Members with NULL `membership_type_id` could not be assigned as voters
- **Root Cause:** INNER JOIN vs LEFT JOIN mismatch between controller and model
- **Solution:** Align `bulkAssignVoters()` to use LEFT JOIN with NULL handling
- **Impact:** All members can now be assigned, regardless of membership type
- **Status:** ✅ RESOLVED

**When to read this:**
- Debugging bulk voter assignment issues
- Understanding voter eligibility validation
- Learning about query design pitfalls
- Reviewing similar bulk operation code

---

## 🔄 Voter Assignment Workflow

```
┌─────────────────────────────────────────────────────────────┐
│ 1. ELIGIBLE MEMBERS LIST                                     │
│ ├─ Active members in organisation                            │
│ ├─ Fees paid or exempt                                       │
│ ├─ Membership type: NULL OR has voting_rights = true         │
│ └─ Not already assigned to this election                     │
└──────────────────────────┬──────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│ 2. BULK ASSIGN FORM (Vue Component)                          │
│ ├─ User selects members via checkboxes                       │
│ ├─ Click "Assign" button                                     │
│ └─ Form submits user_ids array                               │
└──────────────────────────┬──────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│ 3. CONTROLLER VALIDATION (ElectionVoterController)           │
│ ├─ Validate user_ids are UUIDs                               │
│ ├─ Filter eligible members (LEFT JOIN + NULL handling)       │
│ ├─ Count invalid assignments                                 │
│ └─ Pass to model for bulk insert                             │
└──────────────────────────┬──────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│ 4. MODEL BULK INSERT (ElectionMembership::bulkAssignVoters)  │
│ ├─ Lock election row (prevent race conditions)               │
│ ├─ Re-validate eligibility ← CRITICAL (must match controller)│
│ ├─ Filter already-assigned voters                            │
│ ├─ Batch insert new voter memberships                        │
│ └─ Return ['success' => n, 'already_existing' => m, ...]     │
└──────────────────────────┬──────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│ 5. REDIRECT WITH FLASH MESSAGE                               │
│ ├─ Redirect to voters list page                              │
│ ├─ Flash bulk_result with counts                             │
│ └─ Frontend displays success message                         │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔑 Key Files

| File | Purpose | Key Method |
|------|---------|-----------|
| `app/Http/Controllers/ElectionVoterController.php` | Handles voter management requests | `bulkStore()` |
| `app/Models/ElectionMembership.php` | Manages voter memberships | `bulkAssignVoters()` |
| `resources/js/Pages/Elections/Voters/Index.vue` | Voter list UI & forms | `bulkAssign()` |
| `app/Traits/BelongsToTenant.php` | Automatic organisation scoping | Global scope |
| `app/Models/Member.php` | Organisation member model | Eligibility checks |

---

## ✅ Voter Eligibility Rules

A member can be assigned as a voter if ALL of these are true:

1. **Member Status** = `active`
2. **Fees Status** ∈ [`paid`, `exempt`]
3. **Membership Type** = NULL OR has `grants_voting_rights` = true
4. **Membership Expiry** = NULL OR expires in future
5. **Soft Delete** = Not deleted (`deleted_at` IS NULL)
6. **Not Already Assigned** to this election (no duplicate voter memberships)

### Pseudo Code
```
Member is eligible IF:
  (status = 'active')
  AND (fees_status IN ['paid', 'exempt'])
  AND (
    membership_type_id IS NULL
    OR membership_type.grants_voting_rights = true
  )
  AND (
    membership_expires_at IS NULL
    OR membership_expires_at > NOW()
  )
  AND (deleted_at IS NULL)
  AND (NOT already a voter in this election)
```

---

## 🐛 Common Issues & Solutions

### Issue: Members not appearing in "Assign" list
**Check:**
- Is member status = `active`?
- Is fees_status = `paid` or `exempt`?
- Does member have a valid membership_type with voting rights, OR is it NULL?
- Is membership not expired?
- Is member already assigned to this election?

**Query to debug:**
```php
$org = Organisation::where('slug', 'org-slug')->first();
$election = Election::where('slug', 'election-slug')->first();

$unassignedMembers = DB::table('members')
    ->join('organisation_users', ...)
    ->leftJoin('membership_types', ...)
    ->where('members.organisation_id', $org->id)
    ->where('members.status', 'active')
    ->whereIn('members.fees_status', ['paid', 'exempt'])
    ->where(fn ($q) => $q->whereNull('members.membership_type_id')
                         ->orWhere('membership_types.grants_voting_rights', true))
    ->where(fn ($q) => $q->whereNull('members.membership_expires_at')
                         ->orWhere('members.membership_expires_at', '>', now()))
    ->whereNull('members.deleted_at')
    ->whereNotIn('organisation_users.user_id', $election->memberships()->pluck('user_id'))
    ->get();
```

### Issue: Form submits but voters not added
**See:** [BULK_ASSIGN_BUG_FIX.md](./BULK_ASSIGN_BUG_FIX.md) — LEFT JOIN vs INNER JOIN mismatch

### Issue: No flash message shown
**Check:**
- Is the redirect route correct?
- Is the flash data being passed?
- Is the Vue component displaying `$page.props.flash.bulk_result`?

---

## 🧪 Testing

### Test Cases to Cover
- [ ] Assign single member
- [ ] Bulk assign multiple members
- [ ] Assign member with NULL membership_type ← Critical fix
- [ ] Assign member with valid membership_type
- [ ] Don't assign already-assigned member (should skip, not error)
- [ ] Don't assign inactive member
- [ ] Don't assign member with expired membership
- [ ] Don't assign member with unpaid fees
- [ ] Form validation: empty user_ids array
- [ ] Form validation: invalid UUID format

### Run Tests
```bash
php artisan test tests/Feature/Election/ElectionVoterControllerTest.php
php artisan test tests/Feature/Election/BulkAssignVotersTest.php
```

---

## 📊 Database Schema

### Key Tables

**members**
```
- id (uuid)
- organisation_id (uuid)
- organisation_user_id (uuid) — Foreign key to organisation_users
- membership_type_id (uuid, nullable) ← Can be NULL
- status (enum: active, inactive, suspended)
- fees_status (enum: paid, exempt, unpaid)
- membership_expires_at (timestamp, nullable)
- deleted_at (timestamp, nullable)
```

**membership_types**
```
- id (uuid)
- name (string)
- grants_voting_rights (boolean)
- organisation_id (uuid)
```

**election_memberships**
```
- id (uuid)
- election_id (uuid)
- user_id (uuid)
- organisation_id (uuid)
- role (enum: voter, observer, admin)
- status (enum: active, inactive, removed)
- assigned_by (uuid, nullable)
- assigned_at (timestamp)
```

---

## 🚀 Performance Considerations

### Query Optimization
- ✅ Uses indexed foreign keys (`organisation_id`, `user_id`)
- ✅ Eager loads membership_types via LEFT JOIN (1 query, not N+1)
- ✅ Avoids loading full member/user objects (uses `pluck()`)
- ⚠️ Could use `whereIn()` pagination for very large organisations (100k+ members)

### Bulk Insert
- ✅ Uses database transaction (`DB::transaction()`)
- ✅ Uses row locking (`lockForUpdate()`) to prevent race conditions
- ✅ Batch inserts all voters in single query (not looped)

---

## 📖 Related Documentation

- **Voter Verification:** `developer_guide/election/real_election/voter_verification/`
- **Election Lifecycle:** `developer_guide/election/real_election/ELECTION_LIFECYCLE.md`
- **Member Management:** `developer_guide/organisation/members/`
- **Multi-Tenancy:** `developer_guide/architecture/MULTI_TENANCY.md`

---

## 👥 Contact

For questions about:
- **Voter eligibility rules:** See voter assignment workflow above
- **Query design:** See BULK_ASSIGN_BUG_FIX.md → "Prevention Strategies"
- **Form submission:** Check `resources/js/Pages/Elections/Voters/Index.vue`
- **UI/UX:** Check the Vue component's `bulkAssign()` function
