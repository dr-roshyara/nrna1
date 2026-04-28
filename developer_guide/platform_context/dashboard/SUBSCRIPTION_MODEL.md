# Subscription Model — Free vs Paid Elections

## Overview

The **Public Digit Subscription Model** uses a simple, transparent **capacity-based pricing** approach:

- **Free Elections**: ≤ 40 expected voters
- **Paid Elections**: > 40 expected voters

Elections are automatically classified based on the number of voters the organization expects to participate.

---

## The Model

```
┌────────────────────────────────────────────────────────────────┐
│                     ELECTION SUBMISSION                         │
├────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Organization enters:                                           │
│  "Expected Voters: 120"                                         │
│                                                                 │
│                        ▼                                        │
│                  ┌──────────────┐                              │
│                  │ 120 > 40 ?   │                              │
│                  └──────────────┘                              │
│                    ╱          ╲                                 │
│                   ╱            ╲                                │
│                  YES            NO                              │
│                  │              │                               │
│                  ▼              ▼                                │
│            ┌─────────┐    ┌─────────┐                          │
│            │  PAID   │    │  FREE   │                          │
│            │ ELECTION│    │ ELECTION│                          │
│            └────┬────┘    └────┬────┘                          │
│                 │              │                                │
│                 ▼              ▼                                │
│        pending_approval    administration                       │
│        (Requires Review)    (Auto-Approved)                    │
│                 │              │                                │
│                 │   Platform   │                                │
│                 │   Admin must  │  Org proceeds                │
│                 │   Approve or  │  immediately                 │
│                 │   Reject      │  to setup phase              │
│                 │              │                                │
│                 ▼              ▼                                │
│        ┌──────────────┐ ┌──────────────┐                       │
│        │ Approved by  │ │    Ready     │                       │
│        │   Platform   │ │   to Use     │                       │
│        │    Admin     │ │              │                       │
│        └──────────────┘ └──────────────┘                       │
│                 │              │                                │
│                 └──────┬───────┘                                │
│                        ▼                                        │
│           Organization Configures Election                      │
│           (Nominations, Voting Phases, etc.)                   │
│                                                                 │
└────────────────────────────────────────────────────────────────┘
```

---

## FREE Elections (≤ 40 voters)

### Definition
Any election with **expected voter count of 40 or fewer**.

### Characteristics

| Aspect | Details |
|--------|---------|
| **Approval Process** | Automatic (no platform review needed) |
| **Time to Start** | Immediate (minutes) |
| **Review Required** | None |
| **Cost** | Free |
| **Use Case** | Small committees, departments, teams |

### How It Works

1. Organization enters: `expected_voters = 30`
2. System checks: `30 ≤ 40` → YES
3. Election automatically moves to `administration` state
4. Organization can immediately start configuring:
   - Candidate nominations
   - Voting phases
   - Election timeline
5. **No waiting for platform admin approval**

### Ideal For

```
Department Committee Election
├── 15 members total
├── 12 expected to vote
└── Quick decision-making needed
    → Uses FREE tier

Small Non-Profit Board
├── 35 members
├── Annual board election
└── Can start voting setup immediately
    → Uses FREE tier

Student Government Class Vote
├── 28 students in class
├── Vote on class project
└── Need results in 1 week
    → Uses FREE tier
```

---

## PAID Elections (> 40 voters)

### Definition
Any election with **more than 40 expected voters**.

### Characteristics

| Aspect | Details |
|--------|---------|
| **Approval Process** | Requires platform admin review |
| **Time to Start** | 1-5 business days (typical) |
| **Review Required** | Platform admin verifies capacity & data quality |
| **Cost** | Paid (negotiated per organization) |
| **Use Case** | Large organizations, federations, nationwide votes |

### How It Works

1. Organization enters: `expected_voters = 150`
2. System checks: `150 > 40` → YES
3. Election moves to `pending_approval` state
4. **Waits for platform admin review** (see Approval Workflow)
5. Platform admin:
   - Reviews election data
   - Verifies member list quality
   - Approves or provides feedback
6. Upon approval → moves to `administration` state
7. **Then** organization can configure election details

### Timeline Example

```
Monday 9:00 AM  - Organization submits 150-voter election
                 (goes to pending_approval)

Monday 2:00 PM  - Platform admin reviews
                 → Requests voter list verification

Tuesday 10:00 AM - Organization provides updated list
                 → Resubmits election

Tuesday 3:00 PM  - Platform admin approves
                 → Election moves to administration

Wednesday 9:00 AM - Organization begins setup
                 (~1.5 days from initial submission)
```

### Ideal For

```
National Organization Election
├── 500+ members nationwide
├── Board director elections
├── Annual strategic voting
└── Requires platform verification
    → Uses PAID tier

Multi-Site Corporation
├── 200 employees across 5 locations
├── Shareholder voting
└── Needs admin oversight
    → Uses PAID tier

State-Level Union
├── 1000+ members
├── Delegate selection
├── Regional voting with audit trail
└── Requires platform coordination
    → Uses PAID tier
```

---

## Why This Model?

### Capacity Justification

**Small elections (≤40):**
- Low technical risk
- Minimal data quality issues
- Quick setup process
- Can be auto-approved

**Large elections (>40):**
- Require human verification
- Platform must ensure:
  - Member database quality
  - Voter eligibility verification
  - Proper infrastructure
  - Audit trail completeness
- Justifies platform admin review

### Business Model

```
FREE elections:      ⟵ Customer acquisition & retention
                      (Easy to start, low barrier)

PAID elections:      ⟵ Revenue model
                      (Premium service for large orgs)
                      ⟵ Ensures quality & reliability
                      (Platform admin oversight)
```

---

## Checking Election Status

### Organization View

```
All Elections Page:
┌─────────────────────────────────────────────────┐
│ Election Name    │ Voters │ Status              │
├─────────────────────────────────────────────────┤
│ Board Election   │   25   │ ✓ Free              │
│ Annual Vote      │  150   │ ⭐ Paid             │
│ Committee        │   40   │ ✓ Free              │
│ National Council │ 500    │ ⭐ Paid             │
└─────────────────────────────────────────────────┘
```

### Platform Admin View

```
All Elections Page:
┌─────────────────────────────────────────────────────────┐
│ Election Name │ Org    │ Voters │ Type │ State           │
├─────────────────────────────────────────────────────────┤
│ Board Vote    │ Demo   │  25    │ Free │ Draft           │
│ National 2026 │ Namaste│ 150    │ Paid │ Pending Approval│
│ City Council  │ Public │ 500    │ Paid │ Administration  │
└─────────────────────────────────────────────────────────┘
```

---

## Transition Between Tiers

### Scenario: Expected voters change

**Can an election move from Free to Paid?**

```
Initial:  expected_voters = 30 (FREE)
Updated:  expected_voters = 50 (PAID)

Result:   Depends on current state

IF in draft:           Can update and resubmit as PAID
IF approved as FREE:   Cannot retroactively change
                       (Election already approved)
IF in pending:         Can update expected_voters
                       (Resubmit will recalculate)
```

**Best Practice:**
- Organizations should provide realistic voter counts
- If uncertain, estimate higher (requires approval)
- Better to approve more voters than expected than discover fewer
- Platform admin can provide guidance during approval

---

## Approval Thresholds

### Decision Points

```
≤ 40 voters:
├── System checks: No platform review
├── Database: immediate write to administration state
├── Org sees: "Your election is ready to setup"
└── Time: ~2 seconds

41-100 voters:
├── System checks: Flag for admin review
├── Requires: Platform admin approval
├── Typical review time: 1-2 business days
└── Org sees: "Awaiting platform approval"

101+ voters:
├── System checks: Flag for admin review
├── Requires: Platform admin approval
├── Additional checks: Capacity verification
├── Typical review time: 2-5 business days
└── Org sees: "Awaiting platform approval"
```

---

## Pricing & Terms

### Free Tier Limitations

- **Expected voters:** ≤ 40
- **Voting duration:** Unlimited (within election timeline)
- **Results:** Viewable by organization
- **Audit trail:** Complete (for verification)
- **Support:** Community documentation & FAQs

### Paid Tier Benefits

- **Expected voters:** 41 - 1,000,000+
- **Voting duration:** Unlimited
- **Results:** Detailed analytics & reports
- **Audit trail:** Complete + platform verification
- **Support:** Dedicated platform admin review
- **Capacity assurance:** Platform oversees infrastructure
- **SLA:** 1-5 business day approval time

### Negotiation

Large organizations (500+ voters):
- Pricing negotiated per contract
- Volume discounts available
- Custom SLA agreements
- Dedicated account support

---

## Monitoring Free vs Paid Ratio

### Dashboard Metrics

Platform admin can monitor:

```
Total Elections:    142
├── Free:          98  (69%)
└── Paid:          44  (31%)

Free Elections Trend:        Small orgs, high volume
Paid Elections Trend:        Growing larger orgs
```

### What This Tells You

| Ratio | Insight |
|-------|---------|
| 80% Free, 20% Paid | Platform attracting small orgs, SMEs |
| 50% Free, 50% Paid | Mixed ecosystem, balanced growth |
| 20% Free, 80% Paid | Enterprise focus, premium clients |

---

## FAQ

### Q: Why can't small elections get platform admin approval too?

**A:** Auto-approval for small elections means:
- Faster time-to-value for organizations
- Lower platform operating costs
- Simpler process for typical use cases
- Quality inherent in small, managed groups

Larger elections need review because they have greater risk of data quality issues.

### Q: Can organization upgrade mid-election?

**A:** No. Election's tier is set at submission time.

If organization changes expected_voters:
- **If not yet submitted:** Resubmit with new count
- **If already approved:** Election tier is locked
- **If pending:** Can update and resubmit

### Q: What if actual voters exceed expected?

**A:** Election still completes normally.

Example:
- Expected: 50 (PAID tier)
- Actual participants: 48

**Result:** No issue. Expected vs actual is just for planning.

### Q: Who decides if election is free or paid?

**A:** The system decides automatically based on `expected_voter_count`.

Organization sets this number when creating election. Platform applies rules:
- ≤ 40 → FREE (auto-approved)
- > 40 → PAID (requires review)

Organization **cannot choose** which tier.

### Q: Can free elections access paid features?

**A:** Free elections have all core features:
- Voting workflow
- Results calculation
- Audit trail
- Multi-candidate support
- Regional filtering

No feature-based restrictions. Only approval process differs.

---

## Related Documentation

- [Getting Started](./GETTING_STARTED.md) — User guide
- [Election Approval Workflow](./ELECTION_APPROVAL_WORKFLOW.md) — Approval process details
- [Architecture](./ARCHITECTURE.md) — Technical details
- [Extending](./EXTENDING.md) — Adding new features

---

**Status:** Production Ready ✅  
**Last Updated:** April 28, 2026
