# Viewboard — Read-Only Election Monitor

**Available to:** All active election officers (Chief, Deputy, Commissioner)

The Viewboard gives any officer a live, read-only view of the election's current state and statistics. It is the primary interface for **Commissioners**, who have no write access.

---

## How to Access

Direct URL pattern:
```
/elections/{election-id}/viewboard
```

> **Not seeing the Viewboard?** Your officer appointment may still be in **Pending** status. See [Accepting Your Appointment](./02-accepting-invitation.md).

---

## Page Overview

```
┌─────────────────────────────────────────────────────────────┐
│  [Election Name] — Live Viewboard                           │
│  [Organisation Name]             Read-only view             │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  Current Status                                             │
│  ─────────────                                              │
│  ┌───────────────┐  ┌───────────────┐  ┌───────────────┐   │
│  │ Election      │  │ Voting Period │  │ Results       │   │
│  │   Active      │  │   Open        │  │  Not Published│   │
│  └───────────────┘  └───────────────┘  └───────────────┘   │
│                                                             │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  Voting Statistics                                          │
│  ─────────────────                                          │
│  Total registered:   120                                    │
│  Active voters:       98                                    │
│  Eligible:            98                                    │
│  Suspended:            4                                    │
│                                                             │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  [ View Published Results → ]    (visible after publish)   │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## Status Cards

Three cards show the live state of the election:

### 1. Election Status
| Shows | Meaning |
|-------|---------|
| **Active** | The election is running |
| **Completed** | The election has ended |

### 2. Voting Period
| Shows | Meaning |
|-------|---------|
| **Open** | Voters can submit ballots right now |
| **Closed** | Voting is not currently accepting submissions |

### 3. Results
| Shows | Meaning |
|-------|---------|
| **Not Published** | Results are internal only |
| **Published** | Voters can see the results on the results page |

---

## Voting Statistics

| Statistic | What it means |
|-----------|--------------|
| **Total registered** | All members assigned to this election |
| **Active voters** | Members with Active (approved) status |
| **Eligible** | Active members whose access has not expired |
| **Suspended** | Members currently suspended from voting |

These numbers update automatically as officers make changes on the voter list.

---

## Viewing Published Results

When the Chief publishes results, a **"View Published Results →"** link appears at the bottom of the Viewboard. Click it to see the results page — the same page voters see.

The link is only visible **after** results have been published.

---

## What Commissioners Cannot Do

The Viewboard is intentionally read-only. As a Commissioner, you **cannot**:
- Open or close voting
- Approve or suspend voters
- Publish or unpublish results
- Assign new voters

Attempting to access the Management Dashboard will result in a **403 Forbidden** error.

> **This is by design.** The Commissioner role exists to provide neutral oversight without the ability to influence the election outcome.

---

## Refreshing the Data

The Viewboard does not auto-refresh. To see the latest statistics:

- Press **F5** or **Ctrl+R** to refresh the page
- Or click your browser's refresh button

Refresh at regular intervals during election day for up-to-date counts.

---

## Next Step

- [Frequently Asked Questions →](./06-faq-troubleshooting.md)
