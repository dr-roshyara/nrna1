# Managing Voters

**Approve and suspend voters (Commission Members)**

This guide explains how to approve and suspend voters as a commission member.

---

## 📋 Table of Contents

1. [Understanding Voter Statuses](#understanding-voter-statuses)
2. [Approving Voters](#approving-voters)
3. [Suspending Voters](#suspending-voters)
4. [Confirmation Dialogs](#confirmation-dialogs)
5. [Audit Trail & History](#audit-trail--history)
6. [Common Questions](#common-questions)

---

## Understanding Voter Statuses

### Three Voter States

```
┌─────────────────────────────────────────────────────┐
│                   VOTER STATUSES                    │
├─────────────────────────────────────────────────────┤
│                                                     │
│  🟡 PENDING                                         │
│  ├─ Not yet reviewed                                │
│  ├─ Cannot vote                                     │
│  ├─ Needs: Commission approval                      │
│  └─ Action: Click [Approve] or [Suspend]           │
│                                                     │
│  🟢 APPROVED                                        │
│  ├─ Commission has approved for voting              │
│  ├─ Can vote immediately                            │
│  ├─ Status set by: Commission member name           │
│  └─ Action: Click [Suspend] to revoke              │
│                                                     │
│  🔴 SUSPENDED                                       │
│  ├─ Previously approved, now suspended              │
│  ├─ Cannot vote while suspended                     │
│  ├─ Originally approved by: Shown in "Approved By"  │
│  └─ Action: Click [Approve] to reinstate           │
│                                                     │
└─────────────────────────────────────────────────────┘
```

### Status Transitions

```
Possible state changes:

PENDING ──[Approve]──> APPROVED ──[Suspend]──> SUSPENDED
   ↓                                              ↓
   └──────[Suspend]──────────────────────────────┘

SUSPENDED ──[Approve]──> APPROVED (back to voting)
```

---

## Approving Voters

### What "Approve" Means

✅ **Approving a voter means:**
- Commission confirms this person is authorized to vote
- Voter can now participate in the election
- Your name is recorded as the approver
- Action is logged for audit purposes

### Step-by-Step: Approve One Voter

**Method 1: Using the Table Button**

```
┌────┬────────┬──────────────┬─────────┬────────┬──────────────┐
│S.N.│ User ID│    Name      │ Status  │Approved│   Actions    │
│    │        │              │         │   By   │              │
├────┼────────┼──────────────┼─────────┼────────┼──────────────┤
│ 1. │ USR001 │John Smith    │Pending  │  —     │[Approve] [+] │
│                                        ↑ Click here
└────┴────────┴──────────────┴─────────┴────────┴──────────────┘
```

**Steps:**
1. Locate the voter in the table (use search/filters if needed)
2. Look for status "Pending" (yellow indicator)
3. Click **[Approve]** button in the Actions column
4. Confirmation dialog appears (see below)
5. Click **[Confirm]** to finalize
6. Status changes to "Approved" (green)
7. Your name appears in "Approved By" column

### What Happens After Approval

```
Timeline of approval:

1. You click [Approve]
           ↓
2. Confirmation dialog shows ("Are you sure?")
           ↓
3. You click [Confirm]
           ↓
4. Button becomes disabled/loading (shows progress)
           ↓
5. Status updates: Pending → Approved ✓
           ↓
6. Your name appears in "Approved By"
           ↓
7. Voter can now vote
           ↓
8. Action is logged in audit trail
```

### Approving From Pending Status

If voter status is **🟡 Pending:**
- Button shows: **[Approve]** (green button)
- Click to approve them for voting

### Re-Approving From Suspended Status

If voter status is **🔴 Suspended:**
- Button shows: **[Approve]** (green button)
- Click to reinstate them for voting
- Original approval info is preserved

---

## Suspending Voters

### What "Suspend" Means

⚠️ **Suspending a voter means:**
- Voter loses voting privileges immediately
- Previously approved voters can be revoked
- You can suspend pending or approved voters
- Suspension is visible in audit logs

### Reasons to Suspend a Voter

Common scenarios when suspension is appropriate:

✅ **Duplicate registration**
- Person registered twice (accidentally or maliciously)
- Suspend the duplicate account

✅ **Fraudulent account**
- Detected fake voter
- Suspend to prevent voting

✅ **Voter requested suspension**
- Voter asked to be removed from elections
- Honor their request

✅ **Administrative error**
- Wrong person approved
- Suspend to correct the mistake

✅ **Compliance issue**
- Voter no longer meets eligibility
- Suspend from voting

### Step-by-Step: Suspend One Voter

**Method 1: Using the Table Button**

```
┌────┬────────┬──────────────┬─────────┬────────┬──────────────┐
│S.N.│ User ID│    Name      │ Status  │Approved│   Actions    │
│    │        │              │         │   By   │              │
├────┼────────┼──────────────┼─────────┼────────┼──────────────┤
│ 2. │ USR002 │Jane Doe      │Approved │ Admin  │[Suspend] [+] │
│                                        ↑ Click here
└────┴────────┴──────────────┴─────────┴────────┴──────────────┘
```

**Steps:**
1. Locate the voter in the table
2. Look for status "Approved" (green indicator)
3. Click **[Suspend]** button in the Actions column
4. Confirmation dialog appears
5. Click **[Confirm Suspension]** to finalize
6. Status changes to "Suspended" (red)
7. Voter can no longer vote

### Suspending Pending Voters

You can also suspend voters who are still pending:

```
Pending voter (🟡):
- Approve → Voter becomes eligible
- Suspend → Voter rejected (marked as suspended instead)
```

### What Happens After Suspension

```
Timeline of suspension:

1. You click [Suspend]
           ↓
2. Confirmation dialog shows ("Are you sure?")
           ↓
3. You click [Confirm Suspension]
           ↓
4. Button becomes disabled/loading
           ↓
5. Status updates: Approved → Suspended ✓
           ↓
6. Voter cannot vote immediately
           ↓
7. Original approver info is preserved
           ↓
8. Action is logged in audit trail
```

---

## Confirmation Dialogs

### Why Confirmation?

Approval and suspension are **permanent actions** (per voting period), so the system asks you to confirm.

### Approval Confirmation

```
┌────────────────────────────────────────┐
│          CONFIRM APPROVAL              │
├────────────────────────────────────────┤
│                                        │
│  Are you sure you want to approve:     │
│                                        │
│  Name: John Smith                      │
│  User ID: USR001                       │
│  Status: Pending → Approved            │
│                                        │
│  This action cannot be undone.*        │
│                                        │
│  [ Cancel ]  [ Confirm Approval ]      │
│                                        │
│  * Can be reversed by suspending later │
│                                        │
└────────────────────────────────────────┘
```

**Information shown:**
- ✅ Voter name
- ✅ Voter ID
- ✅ Status change (what will happen)
- ✅ Warning about action

**Buttons:**
- **[Cancel]** - Don't approve, go back to list
- **[Confirm Approval]** - Yes, approve this voter

### Suspension Confirmation

```
┌────────────────────────────────────────┐
│         CONFIRM SUSPENSION             │
├────────────────────────────────────────┤
│                                        │
│  Are you sure you want to suspend:     │
│                                        │
│  Name: Jane Doe                        │
│  User ID: USR002                       │
│  Status: Approved → Suspended          │
│                                        │
│  This action cannot be undone.*        │
│                                        │
│  [ Cancel ]  [ Confirm Suspension ]    │
│                                        │
│  * Can be reversed by approving later  │
│                                        │
└────────────────────────────────────────┘
```

**Information shown:**
- ✅ Voter name
- ✅ Voter ID
- ✅ Status change (what will happen)
- ✅ Warning about action

**Buttons:**
- **[Cancel]** - Don't suspend, go back to list
- **[Confirm Suspension]** - Yes, suspend this voter

### Keyboard Controls in Dialogs

- **Tab** - Move between buttons
- **Enter** - Activate focused button
- **Escape** - Close dialog (same as Cancel)

---

## Audit Trail & History

### What Gets Recorded

Every approval and suspension action is logged:

```
Audit Log Entry Example:

Date: 2026-02-23
Time: 14:30:45
Action: Approval
Voter: John Smith (USR001)
Approved By: Admin User
Commission Member: Sarah Johnson
IP Address: 192.168.1.100
Status Change: Pending → Approved
Notes: (any additional notes)
```

### Why This Matters

✅ **Transparency:** Voters can see who approved them
✅ **Accountability:** You're responsible for your approvals
✅ **Audit Trail:** Election integrity is verifiable
✅ **Dispute Resolution:** Disputes can be investigated

### Viewing Audit History

To see who approved a voter:

1. Find the voter in the table
2. Look at the **"Approved By"** column
3. Shows the name of commission member who approved them
4. (Optional) Click voter name to see full history

---

## Common Questions

### Q: Can I undo an approval?

**A:** Yes! You can suspend an approved voter at any time. This effectively "undoes" the approval.

```
Approval (Pending → Approved)
         ↓
Suspension (Approved → Suspended)  = Undone
```

---

### Q: Can I undo a suspension?

**A:** Yes! You can approve a suspended voter to reinstate them.

```
Suspension (Approved → Suspended)
         ↓
Re-approval (Suspended → Approved)  = Undone
```

---

### Q: Will the voter know I suspended them?

**A:** Depends on your organization's notification settings. Typically:
- The system may send an email notification
- The voter may see their status changed in their account
- Check with your administrator for notification settings

---

### Q: Can I approve voters before the election starts?

**A:** Yes! You can approve voters anytime. Approved voters can vote:
- During the scheduled voting window
- If the election is active and open for voting
- Once all election setup is complete

---

### Q: What if I accidentally approved the wrong person?

**A:** No problem!
1. Find the voter in the list
2. Click **[Suspend]** to revoke their approval
3. This removes their voting privilege
4. Then approve the correct voter

---

### Q: Who can approve voters?

**A:** Only **Commission Members** and **Admins** can approve voters.

**Member and Staff roles** can view voters but cannot approve or suspend.

See [Voter List Overview](./02-voter-list-overview.md) for role differences.

---

### Q: How many voters should I approve per day?

**A:** There's no limit! You can approve:
- All pending voters at once (after review)
- A few voters per day as they register
- In bulk using bulk operations
- As needed for your election timeline

See [Bulk Operations](./05-bulk-operations.md) for approving many voters quickly.

---

### Q: Are there any restrictions on approvals?

**A:** No, you have full flexibility:
- ✅ Approve in any order
- ✅ Approve at any time
- ✅ Approve all at once or gradually
- ✅ Change approvals anytime before voting ends

---

## Troubleshooting

### ❌ "[Approve] button is disabled"

**Problem:** Can't click the approve button

**Causes & Solutions:**
1. ✅ Check your role - Must be Commission Member or Admin
2. ✅ Voter is already approved - Button shows [Suspend] instead
3. ✅ System is processing - Button may be temporarily disabled
4. ✅ Refresh page - Sometimes UI doesn't update

---

### ❌ "Approval failed" error message

**Problem:** Click approve, see error instead of success

**Solutions:**
1. ✅ Check internet connection
2. ✅ Refresh the page
3. ✅ Try again in a few seconds
4. ✅ Contact your administrator if error persists

---

### ❌ "Can't find the voter I want to approve"

**Problem:** Voter missing from list

**Solutions:**
1. ✅ Use search to find them specifically
2. ✅ Check filters - Status might be set to exclude them
3. ✅ Click "Clear All" to reset filters
4. ✅ Check you're in correct organization
5. ✅ Refresh page - Might not have loaded yet

See [Searching & Filtering](./03-searching-filtering.md) for help.

---

## Next Steps

👉 **Need to approve many voters quickly?** Go to [Bulk Operations](./05-bulk-operations.md)

👉 **Want to see statistics?** Go to [Statistics & Reports](./06-statistics-reports.md)

👉 **Need to find specific voters?** Go to [Searching & Filtering](./03-searching-filtering.md)

👉 **Having issues?** Go to [Tips & Troubleshooting](./07-tips-troubleshooting.md)

---

## 🆘 Need Help?

- **Keyboard only access?** See [Accessibility Guide](./08-accessibility.md)
- **Questions about roles?** See [Voter List Overview](./02-voter-list-overview.md#for-different-roles)
- **General troubleshooting?** See [Tips & Troubleshooting](./07-tips-troubleshooting.md)

---

**Happy voting! 🗳️**
