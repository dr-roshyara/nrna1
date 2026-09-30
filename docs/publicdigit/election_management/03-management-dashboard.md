# Management Dashboard

**Available to:** Chief and Deputy Election Officers only

The Management Dashboard is your control centre for the election. From here you can monitor election status, control the voting period, and (if you are the Chief) publish the results.

---

## How to Access

After logging in:

1. Navigate to your organisation page
2. Find the election you are managing
3. Click **"Management Dashboard"**

Direct URL pattern:
```
/elections/{election-id}/management
```

> **Commissioner?** You do not have access to this page. Use the [Viewboard](./05-viewboard.md) instead.

---

## Page Overview

```
┌─────────────────────────────────────────────────────────────┐
│  [Organisation Name]                                        │
│  [Election Name] — Management Dashboard                     │
│                                                             │
├──────────────────────────────────┬──────────────────────────┤
│  Current Status                  │  Voting Statistics       │
│  ─────────────────               │  ──────────────────────  │
│  Status:    ● Active             │  Total:     120          │
│  Voting:    Open                 │  Approved:   98          │
│  Results:   Not published        │  Suspended:   4          │
│                                  │                          │
├──────────────────────────────────┴──────────────────────────┤
│                                                             │
│  Voting Period Control              Voter Management        │
│  ─────────────────────              ─────────────────────   │
│  [ Open Voting ]                    Total:    120           │
│  [ Close Voting ]                   Approved:  98           │
│                                     Suspended:  4           │
│                                     [ Manage Voter List → ] │
│                                                             │
├─────────────────────────────────────────────────────────────┤
│  Result Management  (Chief only)                            │
│  ─────────────────────────────                              │
│  [ Publish Results ]  [ Unpublish Results ]                 │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## Current Status Section

Shows the live state of the election at a glance:

| Field | Meaning |
|-------|---------|
| **Status: Active** | Voting is currently open |
| **Status: Completed** | Voting has been closed |
| **Voting: Open** | Voters can submit their votes right now |
| **Voting: Closed** | Voting period has ended |
| **Results: Published** | Voters can view the results |
| **Results: Not published** | Results are hidden from voters |

---

## Voting Statistics

Live counts from the voter list:

| Stat | What it means |
|------|---------------|
| **Total registered** | All members assigned to this election |
| **Approved** | Members with Active status — eligible to vote |
| **Suspended** | Members with Inactive status — cannot vote |

---

## Open and Close Voting

### Opening Voting

Clicking **Open Voting** allows voters to submit their ballots.

1. Click **Open Voting**
2. A confirmation prompt appears: *"Are you sure you want to open the voting period?"*
3. Click **OK** to confirm
4. The page reloads with Status: **Active** and Voting: **Open**

> **Before opening voting**, make sure all your voters are approved on the [Voter List](./04-voter-list.md). Voters with "Invited" or "Suspended" status cannot vote.

### Closing Voting

Clicking **Close Voting** ends the voting period. No new votes can be submitted after this.

1. Click **Close Voting**
2. Confirm the prompt: *"Are you sure you want to end the voting period?"*
3. Click **OK**
4. Status changes to: **Completed**, Voting: **Closed**

> **Can you re-open voting after closing?** Yes — click **Open Voting** again. This is intentional for situations where voting was closed prematurely.

---

## Voter Management Section

Shows a summary of voter counts and a link to the full voter list:

```
  Voter Management
  ─────────────────────
  Total registered:  120
  Approved:           98
  Suspended:           4

  [ Manage Voter List → ]
```

Click **Manage Voter List →** to go to the [Voter List page](./04-voter-list.md) where you can approve or suspend individual voters.

---

## Publish Results

**Available to Chief only. Deputy cannot publish results.**

After voting has closed and you are ready to share the outcome:

1. Click **Publish Results**
2. Confirm the prompt
3. Results become visible to voters

### Unpublish Results

If you published by mistake or need to hold results:

1. Click **Unpublish Results**
2. Confirm the prompt
3. Results are hidden again

> **Publishing results does not delete any votes.** It only controls whether the results page is visible to voters. You can publish and unpublish as many times as needed.

---

## Flash Messages

After every action, a message appears at the top of the page:

| Message | Meaning |
|---------|---------|
| ✅ "Voting period opened." | Success — voting is now open |
| ✅ "Voting period closed." | Success — voting has ended |
| ✅ "Results published." | Success — results are now visible |
| ✅ "Results unpublished." | Success — results are hidden |

If you see no message and nothing changes, check your internet connection and try again.

---

## Next Steps

- [Manage the Voter List →](./04-voter-list.md)
- [Monitor via the Viewboard →](./05-viewboard.md)
