# Bulk Operations

**Work with multiple voters at once**

This guide shows how to select and manage many voters simultaneously.

---

## 📋 Table of Contents

1. [Bulk Operation Basics](#bulk-operation-basics)
2. [Selecting Voters](#selecting-voters)
3. [Bulk Approve](#bulk-approve)
4. [Bulk Suspend](#bulk-suspend)
5. [Deselecting Voters](#deselecting-voters)
6. [Best Practices](#best-practices)
7. [Troubleshooting](#troubleshooting)

---

## Bulk Operation Basics

### What Are Bulk Operations?

**Bulk operations** let you approve or suspend **many voters at once** instead of clicking each one individually.

### When to Use Bulk Operations

✅ **Perfect for:**
- Commission needs to approve 500+ pending voters
- Multiple staff members registered today
- Batch processing at end of registration period
- Reviewing and approving by region together

❌ **Not ideal for:**
- Approving 1-2 voters (just use the single button)
- Reviewing each voter individually (can't see details in bulk)

### Performance Benefit

```
Individual approval:
- 1 click per voter
- 500 voters = 500 clicks
- Time: ~30 minutes

Bulk approval:
- 1 selection process
- 1 bulk approve click
- 500 voters = 1 minute
```

---

## Selecting Voters

### Step 1: Click Checkboxes

Each voter row has a checkbox on the left:

```
┌─────┬────────┬──────────────┬─────────┬────────┬──────────────┐
│ ☐   │ User ID│    Name      │ Status  │Approved│   Actions    │
│     │        │              │         │   By   │              │
├─────┼────────┼──────────────┼─────────┼────────┼──────────────┤
│ ☑   │ USR001 │John Smith    │Pending  │  —     │[Approve] [+] │
│ ☑   │ USR002 │Jane Doe      │Pending  │  —     │[Approve] [+] │
│ ☐   │ USR003 │Bob Jones     │Approved │ Admin  │[Suspend] [+] │
│ ☑   │ USR004 │Mary Johnson  │Pending  │  —     │[Approve] [+] │
│     │  ...   │    ...       │  ...    │  ...   │   ...        │
└─────┴────────┴──────────────┴─────────┴────────┴──────────────┘
     ↑ Click to select voters
```

**To select a voter:**
1. Click the checkbox next to their row
2. Checkbox becomes checked (☑)
3. Row may highlight for visibility

**To select multiple voters:**
- Click checkbox for first voter
- Click checkbox for second voter
- Click checkbox for third voter
- ...continue as needed

### Step 2: View Selection Status

Once you select voters, you'll see:

```
┌─────────────────────────────────────────────┐
│ Selected: 3 voters                          │
│                                             │
│ Actions Available:                          │
│ [ Approve Selected ]  [ Suspend Selected ]  │
│                                             │
└─────────────────────────────────────────────┘
```

**Shows:**
- Number of voters selected
- Available bulk action buttons
- Clear all option (if needed)

---

## Bulk Approve

### What Bulk Approve Does

✅ **Bulk Approve:**
- Approves all selected voters at once
- Updates all statuses to "Approved"
- Records you as the approver for each
- Creates audit log entries for each voter

### Step-by-Step: Bulk Approve Workflow

**Step 1: Select voters**
```
Select all pending voters:
☑ John Smith (Pending)
☑ Jane Doe (Pending)
☑ Mary Johnson (Pending)
```

**Step 2: Click Bulk Approve button**
```
[ Approve Selected (3) ]
        ↓ Click
```

**Step 3: Confirmation dialog appears**
```
┌──────────────────────────────────────────────┐
│         BULK APPROVAL CONFIRMATION           │
├──────────────────────────────────────────────┤
│                                              │
│  Are you sure you want to approve:           │
│                                              │
│  Selected voters: 3                          │
│  Status change: Pending → Approved           │
│                                              │
│  This will approve:                          │
│  • John Smith (USR001)                       │
│  • Jane Doe (USR002)                         │
│  • Mary Johnson (USR004)                     │
│                                              │
│  ⚠️  This action cannot be undone easily.*   │
│                                              │
│  [ Cancel ]  [ Confirm Approval ]            │
│                                              │
│  * Can suspend individually if needed        │
│                                              │
└──────────────────────────────────────────────┘
```

**Step 4: Click Confirm Approval**
```
         ↓ Click

Processing... (may take a few seconds)
```

**Step 5: Success!**
```
✓ Approved 3 voters successfully!

The voter list updates:
- All 3 statuses now show: Approved (green)
- "Approved By" shows your name
- Selection is cleared
```

### Tips for Bulk Approve

**Tip 1: Filter first**
```
Before selecting:
1. Click "Show Filters"
2. Filter: Status = Pending
3. Filter: Region = Bayern
4. Now only pending Bayern voters show
5. Select all of those
6. Bulk approve them together
```

**Tip 2: Select all on current page**
```
Option A: Click the checkbox in the header
- Selects all voters visible on this page
- If you have 50 per page, selects up to 50

Option B: Select individually
- If you only want specific voters
- Check the ones you want to approve
```

**Tip 3: Verify before clicking confirm**
- Check list in confirmation dialog
- Make sure you're approving the right people
- Review names carefully

---

## Bulk Suspend

### What Bulk Suspend Does

⚠️ **Bulk Suspend:**
- Suspends all selected voters at once
- Updates all statuses to "Suspended"
- Records action in audit log
- Prevents suspended voters from voting

### Step-by-Step: Bulk Suspend Workflow

**Step 1: Select voters to suspend**
```
Select voters you want to suspend:
☑ Fake Account 1 (Pending)
☑ Duplicate Entry (Approved)
☑ Fraudulent (Approved)
```

**Step 2: Click Bulk Suspend button**
```
[ Suspend Selected (3) ]
        ↓ Click
```

**Step 3: Confirmation dialog appears**
```
┌──────────────────────────────────────────────┐
│        BULK SUSPENSION CONFIRMATION          │
├──────────────────────────────────────────────┤
│                                              │
│  Are you sure you want to suspend:           │
│                                              │
│  Selected voters: 3                          │
│  Status change: [Current] → Suspended        │
│                                              │
│  This will suspend:                          │
│  • Fake Account 1 (FAK001)                   │
│  • Duplicate Entry (DUP002)                  │
│  • Fraudulent (FRD003)                       │
│                                              │
│  ⚠️  These voters cannot vote while suspended│
│                                              │
│  [ Cancel ]  [ Confirm Suspension ]          │
│                                              │
└──────────────────────────────────────────────┘
```

**Step 4: Click Confirm Suspension**
```
         ↓ Click

Processing... (may take a few seconds)
```

**Step 5: Success!**
```
✓ Suspended 3 voters successfully!

The voter list updates:
- All 3 statuses now show: Suspended (red)
- Selection is cleared
- You can approve them later if needed
```

### Tips for Bulk Suspend

**Tip 1: Use search + filters**
```
Before selecting:
1. Filter: Status = Approved (to find people to suspend)
2. Search: "fake" (to find fraudulent accounts)
3. Select the results
4. Bulk suspend
```

**Tip 2: Be extra careful**
- Bulk operations affect many people at once
- Review the confirmation dialog thoroughly
- Double-check voter names before confirming

**Tip 3: Can be reversed**
- You can re-approve suspended voters later
- So bulk suspend is safer than it sounds

---

## Deselecting Voters

### Remove Individual Selections

**To uncheck one voter:**
1. Click the checkbox again
2. Checkmark disappears
3. Voter is deselected

### Clear All Selections

**To deselect everyone at once:**

```
If you see this button:

[ Clear All Selections ]
      ↓ Click

Result: All checkboxes cleared
```

Or:
1. Find the header checkbox (☑) at top of list
2. Click it to toggle all selections
3. All voters deselected

### Selection Count Updates

As you select/deselect:
```
Before: No selections
   ↓
[ ☑ John ] Selected: 1 voter
   ↓
[ ☑ Jane ] Selected: 2 voters
   ↓
[ ☐ John ] Selected: 1 voter
   ↓
[ ☐ Jane ] Selected: 0 voters
```

---

## Best Practices

### ✅ DO

**✅ DO: Filter before selecting**
- Narrow down to the voters you want first
- Status, Region, Date range filters help
- Reduces chance of selecting wrong voters

**✅ DO: Review the confirmation**
- Read the names in the confirmation dialog
- Count matches your selection
- Verify you want to proceed

**✅ DO: Use bulk operations for many voters**
- 10+ voters → Bulk is faster and easier
- 1-3 voters → Individual buttons are fine

**✅ DO: Know you can undo**
- Approved someone by mistake? Suspend them
- Suspended someone? Re-approve them
- Actions are reversible (per voting period)

### ❌ DON'T

**❌ DON'T: Bulk approve without reviewing**
- Always check the confirmation dialog
- Make sure you're approving the right people

**❌ DON'T: Bulk suspend without filters**
- Risk of suspending the wrong people
- Use filters to narrow down first

**❌ DON'T: Skip the confirmation**
- The confirmation is your safety net
- Take 5 seconds to review

**❌ DON'T: Assume selections persist**
- If you leave the page, selections clear
- Re-select after navigating away

---

## Troubleshooting

### ❌ "Checkboxes not appearing"

**Problem:** No checkboxes visible in the table

**Solutions:**
1. ✅ Refresh page (F5 or Ctrl+R)
2. ✅ Check your screen width (may be hidden on very small screens)
3. ✅ Check your role - Must be Commission Member to see options
4. ✅ Clear browser cache
5. ✅ Try different browser

---

### ❌ "Bulk action buttons disabled"

**Problem:** [Approve Selected] and [Suspend Selected] buttons are grayed out

**Causes:**
1. ✅ No voters selected - Click checkboxes first
2. ✅ Wrong role - Must be Commission Member
3. ✅ System processing - Wait a few seconds

---

### ❌ "Selected voters keep disappearing"

**Problem:** Select voters, click bulk action, selections clear before I can confirm

**Causes:**
- This is normal behavior
- After clicking action button, selections transfer to confirmation dialog
- Selections re-clear after confirmation

---

### ❌ "Bulk operation failed"

**Problem:** Get error after clicking Confirm

**Solutions:**
1. ✅ Check internet connection
2. ✅ Refresh page
3. ✅ Try again with fewer voters (large batches sometimes timeout)
4. ✅ Split into smaller batches
5. ✅ Contact administrator if persistent

---

## Next Steps

👉 **Want to see statistics?** Go to [Statistics & Reports](./06-statistics-reports.md)

👉 **Need to find voters first?** Go to [Searching & Filtering](./03-searching-filtering.md)

👉 **Questions about individual approvals?** Go to [Managing Voters](./04-managing-voters.md)

---

## 🆘 Need Help?

- **General troubleshooting?** See [Tips & Troubleshooting](./07-tips-troubleshooting.md)
- **Using keyboard only?** See [Accessibility Guide](./08-accessibility.md)
- **Can't find voters to approve?** See [Searching & Filtering](./03-searching-filtering.md)

---

**Happy voting! 🗳️**
