# Voter Management for Administrators
## How to Ensure Only Active Members Can Vote

**Platform:** Public Digit  
**Audience:** Organisation administrators, election officers, platform staff  
**Last updated:** 2026-04-06  

---

## 1. Introduction

When your organisation runs an election, only members who have a valid, paid-up membership should be allowed to vote. This guide explains exactly what qualifies a member to vote, who can manage voters, how to add voters to an election, and what happens when someone is disqualified.

---

## 2. The Golden Rule: Who Can Become a Voter?

A person can only be registered as a voter if **all five of the following conditions are true at the same time**:

| # | Condition | What it means |
|---|-----------|---------------|
| 1 | **Active membership** | Their membership record has status = `active`. Suspended or ended memberships are rejected. |
| 2 | **Fees paid or exempt** | Their `fees_status` must be `paid` or `exempt`. Unpaid or partial-payment members cannot vote. |
| 3 | **Voting type** | Their membership type must have *Grants Voting Rights* enabled. Associate members and honorary members (without this flag) are excluded. |
| 4 | **Not expired** | Their membership has not passed its expiry date, or has no expiry (lifetime membership). |
| 5 | **Not deleted** | The membership record must not have been removed from the system. |

If **any one** of these conditions is not met, the system will block the registration and display an error.

---

## 3. Understanding Membership Types and Voting Rights

Membership types are configured by your platform administrator. Each type has a setting called **Grants Voting Rights**.

| Membership Type Example | Grants Voting Rights | Can Vote? |
|------------------------|---------------------|-----------|
| Full Member            | ✅ Yes              | Yes, if fees paid and not expired |
| Associate Member       | ❌ No               | Never, regardless of fees |
| Honorary Member        | ❌ No (default)     | No, unless the type is explicitly configured to grant rights |
| Lifetime Member        | ✅ Yes              | Yes — no expiry check applies |

**To check or change a membership type's voting rights:** Go to *Organisation → Membership → Membership Types* and edit the type.

---

## 4. Fee Status and Voting Rights

| Fee Status | Can Vote? | Notes |
|------------|-----------|-------|
| `paid`     | ✅ Yes    | Standard — fees have been settled |
| `exempt`   | ✅ Yes    | Member is waived from fees (e.g. lifetime, honorary with rights) |
| `unpaid`   | ❌ No     | Member has not yet paid their fees |
| `partial`  | ❌ No     | Partial payment is not sufficient |

If a member has unpaid fees and should be allowed to vote, either:
- Mark their fees as **paid** once payment is received, or
- Set their status to **exempt** if they qualify for a fee waiver

---

## 5. Who Can Manage Voters for an Election?

| Role | Can manage voters? |
|------|--------------------|
| Organisation **Owner** | ✅ Yes |
| Organisation **Admin** | ✅ Yes |
| Election **Chief Officer** | ✅ Yes |
| Election **Deputy Officer** | ✅ Yes |
| Election **Commissioner** | ❌ No (view only) |
| Plain **Member** | ❌ No |
| **Staff** or **Guest** | ❌ No |

---

## 6. How to Add Voters to an Election

### 6.1 Add a Single Voter (Manual)

1. Navigate to your election → **Voters** tab
2. The *Assign Voter* dropdown will show only members who currently qualify (active, paid, voting type, not expired)
3. Select a member from the dropdown and click **Assign**
4. If the selected person does not meet eligibility criteria, the system will display an error

> **Note:** The dropdown only shows eligible, not-yet-assigned members. If someone you expect to see is missing, check their membership status (Section 9 below).

### 6.2 Add Multiple Voters at Once (Bulk Select)

1. On the Voters page, use the checkboxes to select multiple members
2. Click **Bulk Assign**
3. The system will attempt to register all selected users
4. Ineligible users are silently skipped and counted in the `invalid` result
5. You will see a summary: *X registered, Y already existed, Z skipped*

### 6.3 Import Voters from CSV or Excel

For large elections, you can import voters from a spreadsheet.

**Step 1 — Prepare your file**

Create a CSV or Excel file with one column: `email`

```
email
ana.smith@example.com
boris.jones@example.com
claudia.mueller@example.com
```

**Step 2 — Upload and preview**

1. On the Voters page, click **Import Voters**
2. Upload your CSV or Excel file
3. The system will show a **preview table** listing every row with its status:
   - ✅ **Valid** — member found, eligible, will be registered
   - ❌ **Invalid** — row will be skipped (reason shown in the Errors column)

**Common import errors and their causes:**

| Error Message | Cause | Fix |
|---------------|-------|-----|
| `User 'x@example.com' does not exist` | Email not in the platform | Invite the user first |
| `'x@example.com' is not an eligible voter` | Member exists but fails eligibility (unpaid, expired, wrong type) | Update their membership |
| Row has no email | Missing data in spreadsheet | Fix the file |

**Step 3 — Confirm import**

After reviewing the preview, click **Register X Voters**. Only valid rows are processed. Invalid rows are permanently skipped (you can re-import a corrected file separately).

---

## 7. What Prevents Ineligible People From Voting

The system enforces eligibility at **four independent layers**:

### Layer 1 — Voter Registration (write-time)
When you assign a voter (manually, bulk, or import), the system checks all five eligibility conditions before creating the voter record. An ineligible user is never written to the voter list.

### Layer 2 — Voter Dropdown (display-time)
The dropdown on the Voters page only shows members who currently qualify. Expired members, staff-only users, and unpaid members do not appear in the list.

### Layer 3 — Voting Middleware (request-time)
When a voter attempts to access the ballot, the middleware stack verifies:
1. Their voter slug is valid and has not expired
2. They have an **active voter registration for this specific election** (not just any election)
3. They are within their voting time window

If any check fails, they are redirected away from the ballot immediately.

### Layer 4 — Legacy Route Guard (fallback)
The legacy vote submission endpoint also verifies election-scoped registration before processing any ballot. A user registered for Election A cannot submit a ballot to Election B.

---

## 8. How to Remove or Suspend a Voter

### Suspend a voter (temporary)
1. Go to *Election → Voters*
2. Find the voter in the list
3. Click the **Suspend** action
4. Their status changes to `inactive` — they can no longer vote in this election
5. You can re-activate them later if needed

### Remove a voter (permanent)
Use **Remove** to permanently delete their registration from this election. This action cannot be undone.

> **Important:** Suspending a voter takes effect immediately. Due to a 5-minute cache window, a voter who was already mid-session when suspended may be able to complete their current step. The cache clears automatically after 5 minutes.

---

## 9. Troubleshooting: Why Is a Member Missing from the Dropdown?

If you expect a member to appear in the voter assignment dropdown but they are not there, work through this checklist:

**Step 1 — Check membership status**  
Go to *Organisation → Membership → Members* and find the person.
- Status must be `active` (not `suspended`, `ended`, or `pending`)

**Step 2 — Check fee status**  
In their membership record, look at *Fee Status*.
- Must be `paid` or `exempt`
- If unpaid: mark fees as paid or set to exempt

**Step 3 — Check membership type**  
Click on their membership type and verify *Grants Voting Rights* is enabled.

**Step 4 — Check expiry date**  
If their membership has an expiry date, confirm it is in the future.

**Step 5 — Check if already assigned**  
If they are already in the election's voter list (even as `inactive`), they will not appear in the dropdown. Go to *Election → Voters* and search for their name.

**Step 6 — Check if soft-deleted**  
Only platform admins can see soft-deleted members. If the member was removed from the system, they will not appear. Contact your platform administrator.

---

## 10. Voter Eligibility and the Voting Type Chain

The following diagram shows how the system determines voter eligibility:

```
User
  └── Organisation User (must be linked to this organisation)
        └── Member (must have a Member record)
              ├── status = active          ✓ or ✗
              ├── fees_status = paid/exempt ✓ or ✗
              ├── membership_expires_at     ✓ or ✗
              └── Membership Type
                    └── grants_voting_rights = true  ✓ or ✗

All conditions ✓ → ELIGIBLE VOTER
Any condition ✗  → INELIGIBLE (blocked at every path)
```

A user who is only an organisation staff member, guest, or admin — with **no paid formal membership** — cannot be a voter, even if they appear in the organisation's user list.

---

## 11. Frequently Asked Questions

**Q: Can a suspended member vote if they were already assigned before suspension?**  
A: No. Suspension changes their `ElectionMembership.status` to `inactive`. The middleware checks for `status = active` on every voting request. They will be blocked within 5 minutes of suspension (cache window).

**Q: Can I assign voters before the election starts?**  
A: Yes. Voter registration is independent of the election's start date. You can build your voter list before activating the election.

**Q: What happens if a member's fees expire between assignment and voting day?**  
A: If their fees were `paid` when they were assigned, the voter registration remains. Eligibility is checked at **registration time**, not at voting time. However, if you explicitly suspend or remove them from the voter list, they will be blocked.

**Q: Can an associate member ever vote?**  
A: Only if their membership type has *Grants Voting Rights* enabled. Contact your platform administrator to change the type's configuration.

**Q: Can I import the same file twice?**  
A: Yes. The system skips users who are already registered. Duplicate rows are counted as `already_existing` in the import summary.

**Q: Is there a maximum number of voters per election?**  
A: The bulk assignment endpoint accepts up to 1,000 user IDs per request. For larger elections, split into multiple batches or use the CSV import.

---

## 12. Summary Checklist for Administrators

Before an election, verify:

- [ ] All intended voters have an **active** membership record
- [ ] All intended voters have `fees_status = paid` or `exempt`
- [ ] Their membership type has **Grants Voting Rights** enabled
- [ ] No memberships have already expired
- [ ] Voter list is complete (use the import tool for large sets)
- [ ] At least one chief or deputy officer is assigned to the election

On election day:

- [ ] Election status is set to **Active**
- [ ] Voter registration is closed or locked if required by your rules
- [ ] Monitor the Voters page for any suspension requests

After the election:

- [ ] Export the voter participation report for your records
- [ ] Archive or close the election to prevent further submissions
