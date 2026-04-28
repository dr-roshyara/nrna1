# Election Approval Workflow — Detailed Guide

## Overview

The **Election Approval Workflow** is the process platform administrators use to review and approve large elections (>40 expected voters) before they can proceed to the administration phase.

---

## The Complete Workflow

```
┌─────────────────────────────────────────────────────────────────┐
│                                                                  │
│  ORGANIZATION                        PLATFORM                   │
│  ─────────────────────────────────────────────────────────────  │
│                                                                  │
│  1. Create Election      ────────▶                              │
│     (Draft state)                                               │
│                                                                  │
│  2. Set Expected Voters  ────────▶                              │
│     (e.g., 150)                                                 │
│                                                                  │
│  3. Submit for Approval  ────────▶  Capacity Check              │
│                             ┌──▶  150 > 40?                    │
│                             │     YES                           │
│                             │                                    │
│                             └──▶  pending_approval state        │
│                                   (Election in queue)           │
│                                                                  │
│                                   4. Platform Admin Reviews     │
│                                      (on /platform/elections/pending)
│                                                                  │
│                             ┌────────────────┬──────────────┐  │
│                             ▼                ▼              ▼  │
│                          APPROVE         REJECT           SKIP │
│                             │              │               │   │
│  5a. Election approved  ◀───┘              │               │   │
│      (administration)                       │               │   │
│      ✅ Proceed to setup                    │               │   │
│                                             │               │   │
│                                       6. Election rejected  │   │
│                                          (draft state)      │   │
│                                       ❌ Back to org        │   │
│                                          (Fix & resubmit)   │   │
│                                                             │   │
│  7. Org reviews rejection reason, fixes issues, resubmits ◀──┘
│
└─────────────────────────────────────────────────────────────────┘
```

---

## Detailed Steps

### Step 1: Organization Creates Election

The process begins when an organization admin creates a new election.

**Form Fields:**
- Election name
- Description
- Start and end dates
- Expected number of voters (critical!)

**Outcome:**
- Election is in `draft` state
- Only visible to organization admins
- Can be modified before submission

### Step 2: Organization Submits for Approval

Organization admin clicks "Submit for Approval" button.

**System Checks:**
```
IF expected_voter_count ≤ 40:
  ✓ Auto-approve → administration state
  ✓ Org can proceed immediately
  (No platform admin review needed)

IF expected_voter_count > 40:
  → pending_approval state
  → Wait for platform admin review
  (Org cannot proceed yet)
```

**Organization sees:**
- "Awaiting platform approval" message
- Cannot access administration features yet
- Expected approval timeframe

### Step 3: Platform Admin Receives Notification

The election appears in the pending approvals queue.

**Platform admin navigates to:**
```
/platform/elections/pending
```

**Sees:**
- Election name
- Organization name
- Expected voter count (150, 200, etc.)
- Submission date/time
- Two action buttons: [Approve] [Reject]

### Step 4a: Platform Admin Approves Election

**Process:**

1. Platform admin clicks **[Approve]** button
2. Modal dialog appears:

```
┌─────────────────────────────────────┐
│ Approve Election?                   │
│                                     │
│ "National Board Election 2026"      │
│ Organization: Namaste Nepal GmbH    │
│ Expected Voters: 150                │
│                                     │
│ Notes (optional):                   │
│ [_______________________________]    │
│ "Verified member database..."       │
│                                     │
│ [Cancel]  [Approve]                 │
└─────────────────────────────────────┘
```

3. (Optional) Add approval notes for audit trail
4. Click **Approve** button
5. System:
   - Changes election state to `administration`
   - Sends success notification to organization
   - Logs approval action for audit trail
   - Records timestamp of approval

**Organization receives:**
- Success notification email
- "Your election has been approved!"
- Can now access administration features
- Proceed to configure voting phases

### Step 4b: Platform Admin Rejects Election

**Process:**

1. Platform admin clicks **[Reject]** button
2. Modal dialog appears:

```
┌─────────────────────────────────────┐
│ Reject Election?                    │
│                                     │
│ "Regional Council Election 2026"    │
│ Organization: Public Digit          │
│ Expected Voters: 85                 │
│                                     │
│ Reason (required, min 10 chars):    │
│ [_______________________________]    │
│ "Member database needs current      │
│  contact verification before we     │
│  can approve large voter counts"    │
│                                     │
│ [Cancel]  [Reject]                  │
└─────────────────────────────────────┘
```

3. **Must provide detailed reason** (minimum 10 characters)
   - Vague reasons not accepted
   - System enforces minimum length

4. Click **Reject** button
5. System:
   - Changes election state back to `draft`
   - Saves rejection reason in database
   - Sends notification to organization with reason
   - Removes election from pending queue
   - Logs rejection action

**Organization receives:**
- Notification: "Your election was not approved"
- **Full rejection reason** so they understand what to fix
- Can modify election and resubmit

---

## Decision Criteria

### ✅ When to Approve

- ✓ Member/voter list appears complete and up-to-date
- ✓ Organization has confirmed voter eligibility
- ✓ Voting timeline is realistic
- ✓ No obvious data quality issues
- ✓ Organization has capacity to administer large election

### ❌ When to Reject

- ❌ Voter list is incomplete or out-of-date
- ❌ Missing member contact information
- ❌ Unclear eligibility criteria
- ❌ Voting timeline conflicts with bylaws
- ❌ Organization lacks infrastructure for large election
- ❌ Data quality concerns (duplicates, invalid entries)

---

## Good Rejection Reasons

**Example 1:**
```
"Voter eligibility list is incomplete. We need confirmation 
that all 150 members have valid contact information and 
have consented to participate."
```

**Example 2:**
```
"The voting timeline conflicts with your bylaws which require 
30-day notice before elections. Please reschedule and resubmit."
```

**Example 3:**
```
"Member database shows duplicate entries. Please deduplicate 
and verify data before resubmission."
```

**Example 4:**
```
"Organization needs to demonstrate capacity to manage 
200+ voters. Please update your infrastructure plan 
and resubmit with evidence of testing."
```

---

## Bad Rejection Reasons (DON'T USE)

❌ "Not approved"
❌ "Need more info"
❌ "Try again later"
❌ "Application incomplete"
❌ "Check your data" (too vague)

---

## After Rejection: Organization Workflow

### Organization receives rejection reason

They see:
```
Your election was NOT approved.

Reason:
"Member database needs current contact verification 
before we can approve large voter counts. Please 
update member records and resubmit."
```

### Organization fixes issues

1. Updates member database
2. Verifies contact information
3. Addresses each concern in rejection reason
4. Modifies election as needed

### Organization resubmits

1. Makes changes to election
2. Clicks "Submit for Approval" again
3. Election returns to `pending_approval` state
4. Platform admin reviews again (second review)

---

## Approval Metrics & Monitoring

### Dashboard Statistics

Platform admin dashboard shows:
- **Pending Count:** How many elections await approval
- **Average Time to Approval:** Days pending (should be < 5)
- **Approval Rate:** % approved vs rejected
- **Resubmission Rate:** How many get rejected & resubmitted

### Performance Goals

- ✓ Review pending elections daily
- ✓ Approve/reject within 24 hours
- ✓ Keep pending queue under 10 elections
- ✓ Provide detailed rejection reasons (100% of time)

---

## State Transitions & Permissions

```
┌──────────────────────────────────────────────────────────┐
│                     STATE MACHINE                         │
├──────────────────────────────────────────────────────────┤
│                                                          │
│  DRAFT ──(submit for approval)──▶ PENDING_APPROVAL       │
│          (if voters > 40)                               │
│                                                          │
│  PENDING_APPROVAL ──(platform admin approves)──▶ ADMINISTRATION
│            │                                            │
│            │                                            │
│            └──(platform admin rejects)──▶ DRAFT          │
│                                                          │
│  ADMINISTRATION ──(org admin configures)──▶ NOMINATION  │
│                                                          │
│  NOMINATION ──(voting starts)──▶ VOTING                 │
│                                                          │
│  VOTING ──(voting ends)──▶ RESULTS_PENDING              │
│                                                          │
│  RESULTS_PENDING ──(results finalized)──▶ RESULTS       │
│                                                          │
│  RESULTS ──(can be reviewed/exported)──▶ COMPLETED      │
│                                                          │
└──────────────────────────────────────────────────────────┘

Who can perform each action:

SUBMIT_FOR_APPROVAL:    Organization Owner / Admin / Chief Officer
APPROVE:                Platform Admin / Super Admin (ONLY)
REJECT:                 Platform Admin / Super Admin (ONLY)
CONFIGURE (admin phase): Organization Owner / Admin
```

---

## Audit Trail

Every approval/rejection action is logged:

```
Election: "National Board Election 2026"
Organisation: Namaste Nepal GmbH
Expected Voters: 150

Audit Log:
──────────────────────────────────────
[2026-04-28 14:32:15 UTC]
User: Dr. Nab Raj Roshyara (super_admin)
Action: APPROVED
State Change: pending_approval → administration
Notes: "Verified current member list 4/28"
IP Address: 192.168.1.100

[2026-04-27 09:15:42 UTC]
User: Krish Sharma (platform_admin)
Action: REJECTED
State Change: pending_approval → draft
Reason: "Member database incomplete"
IP Address: 203.0.113.45

[2026-04-26 16:28:03 UTC]
User: Admin User (org_admin)
Action: SUBMITTED_FOR_APPROVAL
State Change: draft → pending_approval
IP Address: 198.51.100.12
──────────────────────────────────────
```

---

## Best Practices

### ✅ DO

- **Review promptly** — Don't let elections sit pending for weeks
- **Provide clear feedback** — Detailed rejection reasons help orgs succeed
- **Document approvals** — Use notes field for future reference
- **Check data quality** — Spot-check voter lists before approving
- **Communicate** — Email organizations your timeline expectations

### ❌ DON'T

- **Approve without reviewing** — Even if numbers look correct
- **Reject capriciously** — Only reject if there's a real issue
- **Vague feedback** — "Check your data" doesn't help organizations improve
- **Forget to communicate** — Keep organizations informed of timeline
- **Create unnecessary bottlenecks** — Approve legitimate requests promptly

---

## Troubleshooting

### "I approved an election but org says they can't access it"

**Problem:** Election not transitioning to administration state

**Solution:**
```php
// Check election state in database
php artisan tinker
Election::find('election-id')->state;  // Should be 'administration'
```

If not administration:
1. Check database directly: `SELECT state FROM elections WHERE id = 'x'`
2. Verify platform_admin middleware is applied to route
3. Check for errors in logs

### "Org got rejection email but didn't see reason"

**Problem:** Email sent but reason not clear

**Solution:**
- Check notification email template
- Verify rejection reason was saved: `election->rejection_reason`
- Resend email with clear reason text

### "Election is stuck in pending_approval"

**Problem:** Can't approve or reject (modal won't submit)

**Solution:**
1. Check browser console for JavaScript errors
2. Verify CSRF token is valid
3. Ensure user has platform_admin role
4. Try browser cache clear (Ctrl+Shift+Del)

---

## Related Documentation

- [Getting Started](./GETTING_STARTED.md) — Step-by-step user guide
- [Subscription Model](./SUBSCRIPTION_MODEL.md) — Free vs Paid elections explanation
- [Architecture](./ARCHITECTURE.md) — Technical implementation details
- [Extending](./EXTENDING.md) — Adding new platform features

---

**Status:** Production Ready ✅  
**Last Updated:** April 28, 2026
