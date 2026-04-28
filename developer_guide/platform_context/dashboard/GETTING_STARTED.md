# Getting Started — Platform Dashboard Access & Usage

## 1. Prerequisites: Ensure You Have Platform Admin Access

### Check Your Role

Open `php artisan tinker` and verify:

```php
$user = User::find('your-user-id');
$user->is_super_admin;        // true or false
$user->platform_role;          // 'platform_admin' or NULL
$user->isPlatformAdmin();      // Should return true
```

### Grant Platform Admin Access

If you need to add platform admin role:

```php
// Option A: Super Admin (platform owner)
$user = User::find('user-id');
$user->update(['is_super_admin' => true]);

// Option B: Platform Admin (platform staff)
$user = User::find('user-id');
$user->update(['platform_role' => 'platform_admin']);

// Verify
$user->refresh();
$user->isPlatformAdmin(); // Should return true
```

---

## 2. Accessing the Platform Dashboard

### Via URL

Navigate to: **`http://localhost:8000/platform/dashboard`**

### Via Navigation (Once Logged In)

1. Log in to your account
2. Look for **"🔑 Platform Admin"** link in the main navigation header
3. Click it → redirects to `/platform/dashboard`

### Via Direct Election Link

You can also access elections pages directly:
- **Pending Approvals:** `http://localhost:8000/platform/elections/pending`
- **All Elections:** `http://localhost:8000/platform/elections/all`

---

## 3. Understanding the Dashboard Layout

```
┌─────────────────────────────────────────────────────┐
│ [Logo] PublicDigit          [Language] [Back to App]│ ← Header
├─────────────────────────────────────────────────────┤
│ [🏠 Dashboard] [⏳ Pending] [📊 All Elections]       │ ← Nav Tabs
├─────────────────────────────────────────────────────┤
│                                                     │
│  Platform Admin Dashboard                           │
│  Manage elections and platform configurations       │
│                                                     │
│  [Pending] [Platform] [Organizations] [Total]       │ ← Stats Cards
│   Elections  Admins      count        Elections
│     3         2           5            42
│                                                     │
│  Elections Management                               │
│  [Pending Approvals]     [All Elections]            │ ← Navigation
│   3 elections waiting     See subscription status   │
│                                                     │
│  Coming Soon                                        │
│  [Manage Platform Admins]  [System Settings]        │
│                                                     │
├─────────────────────────────────────────────────────┤
│ Footer with links, social media, copyright          │
└─────────────────────────────────────────────────────┘
```

---

## 4. Your First Task: Review Pending Elections

### Step 1: View Pending Approvals

From the dashboard, click **"Pending Approvals"** card, or navigate to:
```
http://localhost:8000/platform/elections/pending
```

You'll see:
- Count of elections waiting approval
- Table with election details
- Approve/Reject buttons for each election

### Step 2: Find an Election to Approve

Look for elections with:
- **State:** pending_approval
- **Expected Voters:** > 40
- **Organization:** Any tenant organization

Example:
```
┌──────────────────────────────────────────┐
│ Large Voter Election - 2026-04-27 22:32  │
│ Org: Namaste Nepal GmbH                  │
│ Expected Voters: 50                      │
│ Submitted: Apr 28, 2026 10:15            │
│ [Approve] [Reject]                       │
└──────────────────────────────────────────┘
```

### Step 3: Approve the Election

Click **[Approve]** button:

```
Modal Popup:
┌─────────────────────────────────────────┐
│ Approve Election                        │
│                                         │
│ Are you sure you want to approve        │
│ "Large Voter Election - 2026-04-27"?   │
│                                         │
│ Notes (optional):                       │
│ [____________________________]           │
│                                         │
│ [Cancel]  [Approve]                     │
└─────────────────────────────────────────┘
```

- (Optional) Add notes about approval
- Click **Approve** button
- Election moves to `administration` state
- You'll see success message
- Election disappears from pending list

### Step 4: Verify Approval

Go to **All Elections** page:
```
http://localhost:8000/platform/elections/all
```

Search for the election you just approved:
- **State** should now be: `Administration` (blue badge)
- **Subscription** should show: `⭐ Paid` (amber badge)

---

## 5. Your Second Task: Reject an Election

### Step 1: Find Election to Reject

Go to `/platform/elections/pending` and choose an election you want to reject.

### Step 2: Click Reject

Click **[Reject]** button:

```
Modal Popup:
┌─────────────────────────────────────────┐
│ Reject Election                         │
│                                         │
│ Are you sure you want to reject         │
│ "Test Election"?                        │
│                                         │
│ Reason (required, min 10 chars):        │
│ [________________________________]      │
│ "Please clarify voter eligibility..."   │
│                                         │
│ [Cancel]  [Reject]                      │
└─────────────────────────────────────────┘
```

### Step 3: Provide Reason

Enter a detailed rejection reason (minimum 10 characters):

**Good reasons:**
- "Voter eligibility list incomplete; requires verification before approval"
- "Organization needs to update member database with current contact information"
- "Election timeline conflicts with organization's bylaws; please reschedule"

**Why reason matters:**
- Organization sees it and understands why rejected
- Creates audit trail for compliance
- Helps them fix issues and resubmit

### Step 4: Click Reject

Click **[Reject]** button:
- Election returns to `draft` state
- Reason is saved in election record
- Organization receives rejection message
- Election disappears from pending list

### Step 5: Verify Rejection

Organization must:
1. View rejection reason
2. Fix the issues
3. Resubmit election for approval

---

## 6. Viewing All Elections (Transparency)

### Access All Elections Page

Navigate to: **`http://localhost:8000/platform/elections/all`**

### Key Features

**Summary Cards:**
```
Total Elections: 42
Free Elections: 30  (≤40 voters, auto-approved)
Paid Elections: 12  (>40 voters, required approval)
```

**Filter by Subscription:**
- **All** - Show all elections
- **Free** - Show only ≤40 voter elections
- **Paid** - Show only >40 voter elections

**Sort Columns (Click to Sort):**
- Election Name (↑ ascending, ↓ descending)
- Expected Voters (by count)
- Created Date (newest first)

**Example Table:**
```
Election Name          | Organization      | Voters | Status  | State
─────────────────────────────────────────────────────────────────────
National Election 2026 | Namaste Nepal     | 150    | ⭐ Paid  | Administration
Board Meeting 2026     | Public Digit      | 25     | ✓ Free   | Draft
Regional Vote          | Local Society     | 85     | ⭐ Paid  | Pending Approval
Committee Election     | Worker Union      | 18     | ✓ Free   | Completed
```

**States & Colors:**
```
draft                 → Gray badge
pending_approval      → Yellow badge
administration        → Blue badge
nomination           → Purple badge
voting               → Green badge
results_pending      → Orange badge
results              → Teal badge
```

---

## 7. Common Workflows

### Workflow: Approve 5 Pending Elections

1. Go to `/platform/elections/pending`
2. For each election:
   - Click **[Approve]**
   - (Optional) Add notes
   - Click **Approve** button
   - Wait for success message
3. All 5 elections move to `administration` state
4. Pending count decreases by 5

### Workflow: Find All Large Elections (>100 voters)

1. Go to `/platform/elections/all`
2. Click **"Expected Voters"** column header to sort
3. Largest elections appear at top
4. Identify which are awaiting approval (yellow badge)
5. Note any patterns (organization, date range, etc.)

### Workflow: Monitor Free vs Paid Ratio

1. Go to `/platform/elections/all`
2. Look at summary cards:
   - If Free > Paid: Most orgs are small/SMOs
   - If Paid > Free: More large organizations using platform
3. Filter by "Free" and "Paid" to see breakdown

---

## 8. Tips & Best Practices

### ✅ DO

- **Review election details** before approving (expected voters, organization, timeline)
- **Provide detailed rejection reasons** so organizations know how to fix issues
- **Check All Elections** periodically to understand platform usage patterns
- **Monitor pending approval** queue — keep it under control
- **Document approval decisions** in notes field for audit trail

### ❌ DON'T

- **Approve without reviewing** — even if expected voter count is correct
- **Provide vague rejection reasons** like "not approved" — explain WHY
- **Leave pending elections** unreviewed for weeks — creates backlog
- **Assume all large elections need approval** — check actual voter count vs expected
- **Forget to check organization capacity** — ensure they can support large election

---

## 9. Troubleshooting

### "403 Forbidden" Error

**Problem:** You get 403 when accessing `/platform/dashboard`

**Solution:**
```bash
# Check your user's role
php artisan tinker

$user = User::find('your-id');
$user->isPlatformAdmin(); // Must return true

# If false, grant role:
$user->update(['is_super_admin' => true]);
$user->refresh();
```

### "No Pending Elections" but Dashboard Shows Count

**Problem:** Dashboard says 3 pending, but pending approvals page is empty

**Solution:**
- Refresh the page (F5)
- Check browser cache (`Ctrl+Shift+Del`)
- Verify elections are actually in `pending_approval` state:
  ```php
  Election::where('state', 'pending_approval')->count();
  ```

### Election Disappears After Approval

**Problem:** After approving election, it's gone from pending list

**Solution:** This is **correct behavior!**
- Election moved to `administration` state
- It should no longer appear in pending list
- View it in `/platform/elections/all` to confirm

---

## 10. Next Steps

After mastering these basics:

- Read **[Election Approval Workflow](./ELECTION_APPROVAL_WORKFLOW.md)** for deeper understanding
- Read **[Architecture](./ARCHITECTURE.md)** to understand how the system works
- Read **[Extending](./EXTENDING.md)** to add new platform features

---

**Status:** Ready to Use ✅  
**Last Updated:** April 28, 2026
