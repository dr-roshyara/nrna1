# Complete Import Tutorial: Voters, Members & Participants

## Platform: Public Digit
**Audience:** Organisation administrators, election officers, membership managers
**Last updated:** 2026-04-06

---

## Table of Contents

1. [Introduction](#introduction)
2. [Understanding the Three Entity Types](#understanding-the-three-entity-types)
3. [Importing Participants (Staff, Guests, Election Committee)](#importing-participants)
4. [Importing Members (Formal Membership)](#importing-members)
5. [Importing Voters (Election-Specific)](#importing-voters)
6. [Troubleshooting Common Import Errors](#troubleshooting)
7. [Best Practices & Tips](#best-practices)
8. [Quick Reference Card](#quick-reference)

---

## 1. Introduction

The Public Digit platform supports **bulk import** of three distinct entity types via CSV or Excel files. This tutorial explains each import type, when to use it, and how to troubleshoot common issues.

### What You Can Import

| Entity | Purpose | Template Columns | Max Rows |
|--------|---------|-----------------|----------|
| **Participants** | Staff, guests, election committee | 5 columns | 10,000 |
| **Members** | Formal full/associate members | 5 columns | 10,000 |
| **Voters** | Election-specific voter registration | 1 column | 10,000 |

---

## 2. Understanding the Three Entity Types

Before importing, understand the differences:

| Aspect | Participant | Member | Voter |
|--------|-------------|--------|-------|
| **Purpose** | Operational roles | Formal membership | Election participation |
| **Can vote?** | Only election committee | Full members only (paid) | Yes (election-specific) |
| **Pays fees?** | No | Yes (full/associate) | No |
| **Has expiry?** | Guests only | Yes (all members) | Election-specific |
| **Managed by** | Owner/Admin | Owner/Admin | Election officers |

### The Relationship Chain

```
User (platform account)
    │
    ├──→ Participant (staff/guest/committee) ──→ Can manage elections (committee only)
    │
    ├──→ Member (formal membership) ──→ Can vote if:
    │        • status = 'active'
    │        • fees_status = 'paid' or 'exempt'
    │        • membership_type grants voting rights
    │        • not expired
    │
    └──→ Voter (election-specific) ──→ Can vote in ONE election
```

---

## 3. Importing Participants

**Use case:** Add staff members, temporary guests, or election committee members in bulk.

### 3.1 Template Format

Download template from: `Organisation → Membership → Participants → Import → Download Template`

| Column | Required | Format | Example |
|--------|----------|--------|---------|
| `email` | Yes | Valid email, must exist in platform | `john.doe@example.com` |
| `participant_type` | Yes | `staff`, `guest`, or `election_committee` | `election_committee` |
| `role` | No | Free text, max 100 chars | `vote_validator` |
| `expires_at` | No | YYYY-MM-DD (future date, guests only) | `2026-12-31` |
| `permissions` | No | JSON string | `{"manage_voters":true}` |

### 3.2 Sample File

```csv
email,participant_type,role,expires_at,permissions
ana.smith@example.com,staff,membership_coordinator,,
boris.jones@example.com,guest,observer,2026-12-31,{"view_only":true}
claudia.mueller@example.com,election_committee,scrutineer,,
david.kim@example.com,election_committee,chief_officer,,
```

### 3.3 Import Steps

**Step 1: Navigate to Import Page**
```
Organisation → Membership → Participants → Import Voters
```

**Step 2: Upload File**
- Click "Select File" or drag & drop your CSV/Excel file
- Maximum file size: 10 MB
- Supported formats: `.csv`, `.xlsx`, `.xls`

**Step 3: Preview & Validate**
The system shows a preview table with:
- ✅ **Valid** rows (will be imported)
- ❌ **Invalid** rows (skipped, with error reasons)

Example preview:
```
Row | Email                    | Type              | Status      | Errors
2   | ana.smith@example.com   | staff             | ✅ Valid    | 
3   | boris.jones@example.com | guest             | ✅ Valid    | 
4   | unknown@example.com     | staff             | ❌ Invalid  | User does not exist
```

**Step 4: Confirm Import**
- Click "Import X valid rows"
- Invalid rows are skipped (no partial imports)
- Success message shows: `X created, Y updated, Z skipped`

### 3.4 What Happens After Import

| Participant Type | Effect |
|------------------|--------|
| **Staff** | Can access admin areas based on permissions |
| **Guest** | Temporary access until `expires_at` date |
| **Election Committee** | Can manage elections, validate votes |

### 3.5 Updating Existing Participants

If you import the same email twice:
- **Staff or election_committee** → Updates role, permissions, keeps same ID
- **Guest** → Updates expiry date, resets assignment date

---

## 4. Importing Members

**Use case:** Add formal members (full or associate) in bulk after approving applications.

### 4.1 Template Format

Download template from: `Organisation → Membership → Members → Import → Download Template`

| Column | Required | Format | Example |
|--------|----------|--------|---------|
| `email` | Yes | Valid email, must exist in platform | `member@example.com` |
| `membership_type` | Yes | `full` or `associate` | `full` |
| `joined_at` | Yes | YYYY-MM-DD | `2026-01-15` |
| `expires_at` | No | YYYY-MM-DD (future date) | `2026-12-31` |
| `fees_status` | Yes | `paid`, `unpaid`, `partial`, `exempt` | `paid` |

### 4.2 Sample File

```csv
email,membership_type,joined_at,expires_at,fees_status
ana.smith@example.com,full,2026-01-01,2026-12-31,paid
boris.jones@example.com,associate,2026-02-15,,exempt
claudia.mueller@example.com,full,2026-03-01,2027-02-28,unpaid
david.kim@example.com,associate,2026-04-01,2026-12-31,partial
```

### 4.3 Import Steps

**Step 1: Navigate to Import Page**
```
Organisation → Membership → Members → Import Members
```

**Step 2: Upload & Preview**
Same process as participants (upload → preview → validate)

**Step 3: Review Member Eligibility**
The system will show which members will have voting rights:

| Membership Type | Fees Status | Voting Rights |
|----------------|-------------|---------------|
| Full | paid or exempt | ✅ **Full voting rights** |
| Full | unpaid or partial | ❌ Voice only (cannot vote) |
| Associate | any | ❌ No voting rights (observer) |

**Step 4: Confirm Import**

### 4.4 Automatic Features After Import

| Feature | How It Works |
|---------|--------------|
| **Membership number** | Auto-generated (`M` + 8 random chars) |
| **Voting rights** | Calculated from type + fees + expiry |
| **Expiry notifications** | Sent automatically 30, 14, 7 days before |
| **Fee tracking** | Creates pending fee records if `fees_status = 'unpaid'` |

### 4.5 Updating Existing Members

If you import the same email again:
- Updates `fees_status`, `expires_at`, `membership_type`
- Preserves membership number and payment history
- **Does not** create duplicate records

---

## 5. Importing Voters

**Use case:** Register voters for a specific election after membership eligibility is confirmed.

### 5.1 Prerequisites

Before importing voters, ensure:
1. ✅ Election exists and is in `draft` or `registration` status
2. ✅ Users already have **active formal membership** with voting rights
3. ✅ You are an **election officer** (chief/deputy) or **org owner/admin**

### 5.2 Template Format

Download template from: `Election → Voters → Import → Download Template`

| Column | Required | Format | Example |
|--------|----------|--------|---------|
| `email` | Yes | Valid email, must be eligible voter | `voter@example.com` |

**Only one column!** The system checks eligibility automatically.

### 5.3 Sample File

```csv
email
ana.smith@example.com
boris.jones@example.com
claudia.mueller@example.com
```

### 5.4 Import Steps

**Step 1: Navigate to Election Voters Page**
```
Elections → [Select Election] → Voters → Import Voters
```

**Step 2: Upload & Preview**

The preview table shows:

| Status | Meaning |
|--------|---------|
| ✅ **Valid** | User is eligible and will be registered |
| ❌ **Invalid** | User fails eligibility (reason shown) |

Common invalid reasons:
- `User does not exist in the platform`
- `User is not an active formal member with full voting rights`
- `User is already registered for this election`

**Step 3: Confirm Import**

**Step 4: Verify Results**

Success message shows:
```
Voter import completed: 45 registered, 3 already existing, 2 skipped.
```

### 5.5 Who Can Be a Voter? (Eligibility Checklist)

A user can be imported as a voter **ONLY if ALL conditions are true**:

| # | Condition | How to Verify |
|---|-----------|---------------|
| 1 | Has formal member record | Check `Members` table |
| 2 | Membership status = `active` | Not suspended/ended |
| 3 | Fees status = `paid` or `exempt` | Not unpaid/partial |
| 4 | Membership type grants voting rights | `grants_voting_rights = true` |
| 5 | Membership not expired | `expires_at` > today or NULL |
| 6 | Not already registered for this election | Not in `election_memberships` |

### 5.6 Re-importing the Same File

| Scenario | Result |
|----------|--------|
| Same email, already registered | Skipped (counted as `already_existing`) |
| Same email, now eligible (wasn't before) | Will be registered |
| Same email, different election | Must import to each election separately |

---

## 6. Troubleshooting Common Import Errors

### 6.1 "User does not exist in the platform"

**Cause:** Email not found in `users` table.

**Solution:**
1. User must register on Public Digit first
2. Or invite them via `Organisation → Members → Invite`
3. Then re-import after they accept invitation

### 6.2 "User is not an active formal member" (Voter import)

**Cause:** User has platform account but no valid membership.

**Solution:**
1. Check if user has `Member` record (`Organisation → Members`)
2. If no: Process membership application first
3. If yes: Check status, fees, expiry, membership type

**Quick diagnostic query for admins:**
```sql
SELECT u.email, m.status, m.fees_status, mt.grants_voting_rights, m.membership_expires_at
FROM users u
JOIN organisation_users ou ON ou.user_id = u.id
JOIN members m ON m.organisation_user_id = ou.id
JOIN membership_types mt ON mt.id = m.membership_type_id
WHERE u.email = 'user@example.com';
```

### 6.3 "Invalid participant_type"

**Cause:** Column value not one of: `staff`, `guest`, `election_committee`.

**Solution:** Correct the spelling in your file.

### 6.4 "expires_at must be a future date"

**Cause:** Date is in the past or invalid format.

**Solution:**
- Use `YYYY-MM-DD` format (e.g., `2026-12-31`)
- Ensure date is **after today**
- Leave blank for no expiry (staff/committee only)

### 6.5 File upload fails with 422 error

**Cause:** File format not recognized.

**Solution:**
- Use `.csv`, `.xlsx`, or `.xls` format
- Max file size: 10 MB
- Check for BOM in CSV (save as UTF-8 without BOM)

### 6.6 Partial import (some rows skipped)

**Expected behavior!** The system:
- Validates each row independently
- Skips only invalid rows
- Success message shows counts

**To fix skipped rows:**
1. Export or note the skipped emails from preview
2. Fix the underlying issue (e.g., create missing users, update fees)
3. Re-import **only the skipped rows** (or whole file again)

---

## 7. Best Practices & Tips

### Before Importing

| Task | Why |
|------|-----|
| **Download fresh template** | Ensures correct column order |
| **Validate emails first** | Prevents "user not found" errors |
| **Test with 3-5 rows** | Catch issues before bulk import |
| **Backup current data** | Export existing list first |

### During Import

| Tip | Benefit |
|-----|---------|
| **Review preview table** | Catch errors before committing |
| **Check invalid row count** | If >10%, fix source file |
| **Import in batches** | 1,000 rows at a time for large files |
| **Keep import logs** | Save success message counts |

### After Import

| Action | Purpose |
|--------|---------|
| **Verify counts** | Compare with expected numbers |
| **Spot-check random rows** | Confirm correct assignment |
| **Notify affected users** | Send welcome/confirmation emails |
| **Update documentation** | Track import date and batch ID |

### Performance Guidelines

| Entity | Safe Batch Size | Estimated Time |
|--------|----------------|----------------|
| Participants | 10,000 rows | 30-60 seconds |
| Members | 5,000 rows | 60-120 seconds |
| Voters | 10,000 rows | 20-40 seconds |

### Security Notes

| Entity | Who Can Import |
|--------|----------------|
| Participants | Organisation Owner or Admin only |
| Members | Organisation Owner or Admin only |
| Voters | Election Chief/Deputy OR Org Owner/Admin |

---

## 8. Quick Reference Card

### Import Paths

| Import Type | Navigation |
|-------------|------------|
| Participants | `Organisation → Membership → Participants → Import` |
| Members | `Organisation → Membership → Members → Import` |
| Voters | `Elections → [Select] → Voters → Import` |

### Template Columns Quick Reference

**Participants:**
```
email, participant_type, role, expires_at, permissions
```

**Members:**
```
email, membership_type, joined_at, expires_at, fees_status
```

**Voters:**
```
email
```

### Eligibility at a Glance

| Entity | Requires |
|--------|----------|
| Participant | Platform account |
| Member | Platform account + approval |
| Voter | Platform account + active paid membership |

### Common Error Fixes

| Error | Quick Fix |
|-------|-----------|
| `User does not exist` | Invite user first |
| `Invalid participant_type` | Use staff/guest/election_committee |
| `Not an active formal member` | Check membership status/fees |
| `Invalid date format` | Use YYYY-MM-DD |
| `File too large` | Split into multiple files |

### Success Indicators

After successful import, you should see:

```
✅ Import completed: X created, Y updated, Z skipped.
```

And verify in UI:
- Participants appear in participant list
- Members appear in member list with correct status
- Voters appear in election voter list

---

## Need Help?

- **Check logs:** `storage/logs/laravel.log` for detailed errors
- **Contact support:** platform@publicdigit.com
- **Developer docs:** `developer_guide/membership/`

---

**Document Version:** 1.0
**Last Tested:** 2026-04-06
**Next Review:** 2026-07-06 