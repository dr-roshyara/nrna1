# Platform Dashboard — Comprehensive Tutorial

## Overview

The **Platform Admin Dashboard** (`/platform/dashboard`) is the central hub for managing elections across all organizations on the Public Digit platform. It provides platform administrators with visibility and control over election approvals, capacity management, and platform operations.

### Key Responsibilities

- ✅ **Approve large elections** (>40 voters) for administration
- ✅ **Reject elections** with reasons if they don't meet requirements
- ✅ **Monitor election status** across all organizations
- ✅ **Manage platform capacity** with transparent subscription model
- ✅ **Access election data** with filtering, sorting, and pagination

---

## Quick Access

| Page | URL | Purpose |
|------|-----|---------|
| **Dashboard** | `/platform/dashboard` | Central hub with stats and navigation |
| **Pending Approvals** | `/platform/elections/pending` | Elections waiting for approval (>40 voters) |
| **All Elections** | `/platform/elections/all` | Complete election inventory with subscription status |

---

## Prerequisites

### User Roles Required
- **`super_admin`**: Platform owner, can manage platform admins
- **`platform_admin`**: Platform staff, can approve/reject elections

### How to Grant Access

```php
// Grant super_admin (via tinker or migration)
$user = User::find('user-id');
$user->update(['is_super_admin' => true]);

// Grant platform_admin role
$user = User::find('user-id');
$user->update(['platform_role' => 'platform_admin']);
```

---

## The Subscription Model

The platform uses a **capacity-gated approval system** that determines whether elections auto-approve or require manual approval:

```
ELECTION SUBMISSION
        ↓
    ┌───────────────────────┐
    │ expected_voter_count? │
    └───────────────────────┘
         ↙                 ↘
    ≤ 40 voters          > 40 voters
         ↓                   ↓
    AUTO-APPROVE        REQUIRES APPROVAL
    (Free)              (Paid/Premium)
         ↓                   ↓
    administration    pending_approval
    state             state
```

### Free Elections (≤ 40 voters)
- ✓ Auto-approved immediately
- ✓ No platform admin intervention needed
- ✓ Organization can proceed to administration phase
- ✓ Intended for: SMOs, small committees, departments

### Paid/Approval Elections (> 40 voters)
- ⭐ Requires platform admin review
- ⭐ Must be approved before proceeding
- ⭐ Can be rejected with reason
- ⭐ Intended for: Large organizations, multi-site elections, federations

---

## Main Features

### 1. Dashboard Stats & Navigation

The dashboard displays 4 key metrics:

```
┌──────────────────────────────────────┐
│ Pending Elections: 3                  │  ← Click to review
├──────────────────────────────────────┤
│ Platform Admins: 2                    │
├──────────────────────────────────────┤
│ Active Organizations: 5               │
├──────────────────────────────────────┤
│ Total Elections: 42                   │
└──────────────────────────────────────┘
```

Below the stats, an **Elections Management** section provides navigation:
- **Pending Approvals**: Link to elections awaiting approval
- **All Elections**: Link to complete election inventory with filters

### 2. Pending Approvals Page

Shows elections in `pending_approval` state that need platform admin review.

**Columns:**
- Election Name
- Organization
- Expected Voters
- Submitted Date
- Actions (Approve / Reject)

**Actions:**
- **Approve**: Moves election to `administration` state
- **Reject**: Returns election to `draft` with reason required

### 3. All Elections Page

Provides complete transparency into all elections across the platform.

**Features:**
- **Filter by subscription status**: Free / Paid / All
- **Sort by**: Name, Expected Voters, Created Date
- **Pagination**: 25 elections per page
- **Summary cards** showing:
  - Total elections
  - Free elections count (≤40 voters)
  - Paid elections count (>40 voters)

**Color-coded subscription badges:**
- 💚 **Free** (≤40 voters, green)
- 💰 **Paid** (>40 voters, amber)

---

## Architecture

### Database Schema

```sql
-- Users with platform roles
users
├── id (UUID)
├── is_super_admin (boolean)     ← Platform owner
├── platform_role (varchar)      ← 'platform_admin' or NULL
└── ...

-- Elections with voter capacity
elections
├── id (UUID)
├── name (varchar)
├── expected_voter_count (integer) ← Determines approval path
├── state (varchar)              ← draft, pending_approval, administration, etc.
├── submitted_for_approval_at (timestamp)
├── organisation_id (UUID)
└── ...
```

### Role Resolution Priority

The `resolveActorRole()` method in Election model determines what actions a user can take:

```php
1. Check if 'system' → 'system' role (auto-submit)
2. Check if super_admin → 'super_admin' role (approval only)
3. Check if platform_admin → 'platform_admin' role (approval only)
4. Check election-level roles → 'chief', 'deputy', 'observer'
5. Check org-level roles → 'owner', 'admin'
6. Default → 'observer'
```

### Permission Matrix

| Action | Required Role(s) | State Transition |
|--------|------------------|------------------|
| `submit_for_approval` | owner, admin, chief | draft → pending_approval OR administration |
| `approve` | super_admin, platform_admin | pending_approval → administration |
| `reject` | super_admin, platform_admin | pending_approval → draft |
| `auto_submit` | system | draft → administration |

---

## Workflow Example: Approving an Election

### Step 1: Organization Creates Large Election

```
Organization admin creates election:
- Name: "National Board Election 2026"
- Expected voters: 150
- State: draft
```

### Step 2: Organization Submits for Approval

```
Organization admin submits election
  ↓
Capacity check: 150 > 40?
  ↓
YES → State changed to: pending_approval
  ↓
Election appears in /platform/elections/pending
```

### Step 3: Platform Admin Reviews

Platform admin navigates to `/platform/elections/pending` and sees:

```
┌─────────────────────────────────────────────┐
│ National Board Election 2026                │
│ Org: National Association XYZ              │
│ Expected Voters: 150                        │
│ Submitted: Mar 28, 2026                     │
│ [Approve]  [Reject]                        │
└─────────────────────────────────────────────┘
```

### Step 4: Platform Admin Approves

Clicks **Approve** button:
- Optional notes can be added
- Election state changed to: `administration`
- Organization receives success notification
- Election no longer appears in pending approvals

### Step 5: Organization Proceeds

Organization can now configure:
- Candidate nominations
- Voting phases
- Election timeline

---

## Common Tasks

### View All Elections with Subscription Status

**URL:** `/platform/elections/all?filter=all&sort=created_at&direction=desc`

This page shows:
- Every election across all organizations
- Which are free (auto-approved)
- Which required admin approval
- Current state of each election

### Filter for Pending Approvals Only

**URL:** `/platform/elections/pending`

Shows only elections in `pending_approval` state waiting for review.

### Sort Elections by Voter Count

**URL:** `/platform/elections/all?sort=expected_voter_count&direction=desc`

Shows largest elections first — useful for identifying high-capacity events.

### Approve an Election

1. Go to `/platform/elections/pending`
2. Find the election
3. Click **Approve** button
4. (Optional) Add approval notes
5. Click **Approve** in confirmation dialog
6. Election moves to `administration` state

### Reject an Election

1. Go to `/platform/elections/pending`
2. Find the election
3. Click **Reject** button
4. Enter rejection reason (minimum 10 characters)
5. Click **Reject** in confirmation dialog
6. Election returns to `draft` state with reason saved

---

## Error Handling

### Common Issues & Solutions

| Issue | Cause | Solution |
|-------|-------|----------|
| 403 Forbidden on `/platform/*` | User not `super_admin` or `platform_admin` | Check user's role in database |
| Election not in pending_approvals | Voter count ≤ 40 (auto-approved) | Check `expected_voter_count` field |
| Cannot approve from wrong state | Election not in `pending_approval` | Ensure election was submitted and in pending state |
| Rejection reason too short | Reason < 10 characters | Provide detailed reason (≥10 chars) |

---

## Next Steps

- **[Getting Started](./GETTING_STARTED.md)** — Step-by-step guide to access and use dashboard
- **[Election Approval Workflow](./ELECTION_APPROVAL_WORKFLOW.md)** — Detailed approval process
- **[Subscription Model](./SUBSCRIPTION_MODEL.md)** — Understanding free vs paid elections
- **[Architecture](./ARCHITECTURE.md)** — Technical implementation details
- **[Extending](./EXTENDING.md)** — Adding new platform features

---

**Last Updated:** April 28, 2026  
**Status:** Production Ready ✅
