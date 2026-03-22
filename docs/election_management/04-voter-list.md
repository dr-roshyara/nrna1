# Managing the Voter List

**Available to:** Chief and Deputy Election Officers only

The Voter List shows every member assigned to the election. From here you approve, suspend, or remove individual voters. Voters must be **Approved (Active)** before they can submit a ballot.

---

## How to Access

From the Management Dashboard, click **"Manage Voter List →"**

Direct URL pattern:
```
/organisations/{slug}/elections/{election-id}/voters
```

---

## Page Overview

```
┌─────────────────────────────────────────────────────────────┐
│  [Organisation Name] — [Election Name]                      │
│  Voter Management                          [ Export CSV ]   │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐   │
│  │ Active   │  │ Eligible │  │ Inactive │  │ Removed  │   │
│  │    98    │  │    98    │  │     4    │  │     2    │   │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘   │
│                                                             │
├─────────────────────────────────────────────────────────────┤
│  Assign Voter                                               │
│  ┌─────────────────────────────────────┐  [ Assign ]       │
│  │ User ID (UUID)                      │                   │
│  └─────────────────────────────────────┘                   │
├─────────────────────────────────────────────────────────────┤
│  Name          Email           Status    Assigned  Actions  │
│  ────────────────────────────────────────────────────────   │
│  Alice Smith   alice@…com      ● active  Mar 15    Suspend  │
│                                                    Remove   │
│  Bob Jones     bob@…com        ○ invited Mar 16    Approve  │
│                                                    Remove   │
│  Carol Chen    carol@…com      ● inactive Mar 14   Approve  │
│                                                    Remove   │
└─────────────────────────────────────────────────────────────┘
```

---

## Understanding Voter Statuses

Each voter on the list has one of four statuses:

| Status | Badge Colour | Meaning | Can Vote? |
|--------|-------------|---------|-----------|
| **active** | 🟢 Green | Approved — eligible to vote | ✅ Yes |
| **invited** | 🔵 Blue | Assigned but not yet approved | ❌ No |
| **inactive** | 🟡 Yellow | Suspended by an officer | ❌ No |
| **removed** | 🔴 Red | Removed from this election | ❌ No |

> **Only voters with Active status can submit a ballot.** All others are blocked at the voting step.

---

## Approving a Voter

Approving changes a voter's status from `invited` or `inactive` to `active`.

**Steps:**

1. Find the voter in the table
2. Click the **Approve** button in their row (shown when status is not already active)
3. The page refreshes automatically
4. The voter's status badge changes to 🟢 **active**
5. A success message appears: *"Voter [Name] approved."*

```
Before:  Bob Jones   ○ invited   [ Approve ] [ Remove ]
After:   Bob Jones   ● active              [ Suspend ] [ Remove ]
```

> **Approve voters before opening voting.** Once voting is open, any voter not yet approved will be unable to submit a ballot.

---

## Suspending a Voter

Suspending changes an active voter's status to `inactive`. They can no longer vote until re-approved.

**Steps:**

1. Find the voter in the table (must be currently `active`)
2. Click the **Suspend** button in their row
3. A confirmation prompt appears: *"Suspend [Name]? They will no longer be eligible to vote."*
4. Click **OK** to confirm
5. The voter's status changes to 🟡 **inactive**
6. A success message appears: *"Voter [Name] suspended."*

> **Use suspension carefully during an active election.** A suspended voter cannot vote, even if voting is still open.

### Re-approving a suspended voter

Suspended voters show an **Approve** button. Simply click it to restore their eligibility.

---

## Removing a Voter

Removing a voter sets their status to `removed`. This is permanent — a removed voter does not show an Approve button.

**Steps:**

1. Click **Remove** in the voter's row
2. Confirm the prompt: *"Remove [Name] from the election?"*
3. Status changes to 🔴 **removed**

> **Removing is different from suspending.** Suspended voters can be re-approved. Removed voters cannot be restored through the voter list UI — contact your system administrator if a removal was made in error.

---

## Assigning a New Voter

If a member needs to be added to the election voter list:

1. Find the member's **User ID** (UUID — your administrator can provide this)
2. Paste it into the **"Assign Voter"** input at the top of the page
3. Click **Assign**
4. The voter appears in the table with `invited` status
5. Approve them immediately or approve before voting opens

> **Only organisation members can be assigned as voters.** If you receive an error like "not a member of this organisation", the User ID does not belong to a current member.

---

## Exporting the Voter List

To download the full voter list as a spreadsheet:

1. Click **Export CSV** (top-right of the page)
2. A file named `voters-{election-id}.csv` downloads automatically

The CSV contains:
```
Name, Email, Status, Assigned At
Alice Smith, alice@example.com, active, 2026-03-15 10:00:00
Bob Jones, bob@example.com, invited, 2026-03-16 09:30:00
```

---

## Statistics Cards

The four cards at the top update automatically as you approve/suspend voters:

| Card | What it shows |
|------|--------------|
| **Active** | Voters currently eligible to vote |
| **Eligible** | Active voters whose access hasn't expired |
| **Inactive** | Suspended voters |
| **Removed** | Removed voters |

---

## Workflow: Before the Election

Recommended preparation steps:

```
1. All members imported / assigned to voter list
         ↓
2. Review invited voters (blue badges)
         ↓
3. Approve all eligible voters (click Approve)
         ↓
4. Suspend any ineligible members (click Suspend)
         ↓
5. Return to Management Dashboard
         ↓
6. Click "Open Voting"
```

---

## Common Mistakes

### "I approved someone by mistake"

Click **Suspend** on that voter. This reverses the approval and prevents them from voting.

### "I suspended someone by mistake"

Click **Approve** on that voter. Their status immediately returns to active.

### "A voter says they cannot vote but their status is Active"

Check that voting is actually open on the [Management Dashboard](./03-management-dashboard.md). If Status shows "Completed", voting has been closed.

### "I don't see an Approve button for a voter"

The voter's current status is already `active` — they are already approved. Check the status badge in their row.

---

## Next Steps

- [Monitor election progress on the Viewboard →](./05-viewboard.md)
- [Open voting from the Management Dashboard →](./03-management-dashboard.md#open-and-close-voting)
