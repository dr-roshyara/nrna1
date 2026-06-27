# Election Administration User Guide

**How to plan, create, manage, and complete a real election on the platform**

**Date:** 2026-05-24  
**Audience:** Election officers (chief, deputy, commissioner), organisation administrators

---

## Table of Contents

1. [Roles & Permissions](#roles--permissions)
2. [Quick Start: Your First Election](#quick-start-your-first-election)
3. [Complete Lifecycle Walkthrough](#complete-lifecycle-walkthrough)
   - [Phase 1: Draft — Setup (State: Draft)](#phase-1-draft--setup-state-draft)
   - [Phase 2: Submit for Approval — Platform Review (State: SubmittedForApproval)](#phase-2-submit-for-approval--platform-review-state-submittedforapproval)
   - [Phase 3: Setup Administration (State: SetupAdministration)](#phase-3-setup-administration-state-setupadministration)
   - [Phase 4: Setup Nomination (State: SetupNomination)](#phase-4-setup-nomination-state-setupnomination)
   - [Phase 5: Ready for Voting (State: ReadyForVoting)](#phase-5-ready-for-voting-state-readyforvoting)
   - [Phase 6: Voting Active (State: VotingActive)](#phase-6-voting-active-state-votingactive)
   - [Phase 7: Counting (State: Counting)](#phase-7-counting-state-counting)
   - [Phase 8: Results Published (State: ResultsPublished)](#phase-8-results-published-state-resultspublished)
   - [Phase 9: Archived (State: Archived)](#phase-9-archived-state-archived)
   - [Suspension: Operational Governance Freeze (State: Suspended)](#suspension-operational-governance-freeze-state-suspended)
4. [Timeline Configuration](#timeline-configuration)
5. [Voter Management](#voter-management)
6. [Post & Candidate Management](#post--candidate-management)
7. [Electing Election Officers](#electing-election-officers)
8. [Best Practices](#best-practices)
9. [Glossary](#glossary)

---

## Roles & Permissions

### Three Officer Roles

Each election has its own set of officers defined in the `election_officers` table:

| Role | Capabilities | Appointment |
|------|-------------|-------------|
| **Chief** | Full control: create, manage settings, approve voters, manage posts, manage candidates, publish results, suspend/resume election | Appointed by organisation admin |
| **Deputy** | All actions except publishing results and suspending the election | Appointed by chief or admin |
| **Commissioner** | Read-only: view management pages, view results. No write actions | Appointed by chief or admin |

### Permission Summary

| Action | Chief | Deputy | Commissioner |
|--------|-------|--------|-------------|
| View management page | ✅ | ✅ | ✅ |
| Manage settings (dates, timeline) | ✅ | ✅ | ❌ |
| Manage posts (positions) | ✅ | ✅ | ❌ |
| Manage voters (import, approve) | ✅ | ✅ | ❌ |
| Manage candidates (approve/reject) | ✅ | ✅ | ❌ |
| Complete administration phase | ✅ | ✅ | ❌ |
| Complete nomination phase | ✅ | ✅ | ❌ |
| Open voting | ✅ | ✅ | ❌ |
| Close voting | ✅ | ✅ | ❌ |
| Publish results | ✅ | ❌ | ❌ |
| Suspend election | ✅ | ❌ | ❌ |
| Resume election | ✅ | ❌ | ❌ |
| Archive election | ✅ | ✅ | ❌ |
| View results | ✅ | ✅ | ✅ |

### Platform Admin

Platform super-admins have system-level governance authority, including the ability to suspend/resume any election across all organisations. This is reserved for governance emergencies.

---

## Quick Start: Your First Election

### Prerequisites

1. You need an **organisation** set up on the platform
2. You need the **owner** or **admin** role for that organisation
3. You need at least one **chief officer** assigned to the election

### Step-by-Step

```
1. CREATE ELECTION
   │  Organisation admin creates the election
   │  Choose name, expected voter count, timezone
   │  Set suggested dates for administration, nomination, voting
   │
2. SET UP ELECTION
   │  Chief/deputy assigns:
   │  ├── Election officers (chief + deputies + commissioners)
   │  ├── Posts (positions up for election)
   │  ├── Voters (import membership list)
   │  └── Timeline (phase dates)
   │
3. OPEN NOMINATIONS
   │  Chief completes administration → nomination phase opens
   │  Candidates can apply, chief approves/rejects
   │
4. START VOTING
   │  Chief completes nomination → ready for voting
   │  Chief opens voting → voters cast ballots
   │
5. COUNT & PUBLISH
   │  Chief closes voting → counting begins
   │  Chief publishes results → results visible
   │
6. ARCHIVE
   │  Chief archives → election complete
```

---

## Complete Lifecycle Walkthrough

### Phase 1: Draft — Setup (State: Draft)

**What it means:** The election has been created but no setup work has been done yet.

**What you can do:**
- View the election management page
- Edit election settings (name, dates, timezone)
- **Cannot** yet add posts, voters, or officers — these are configured in Setup Administration

**Who does it:** Organisation admin creates the election. You must have `owner` or `admin` role in the organisation.

**How to create an election:**
1. Navigate to your organisation page
2. Click **"Create Election"**
3. Fill in:
   - **Name** — Unique within your organisation
   - **Expected voter count** — Used for capacity planning
   - **Timezone** — Required for phase scheduling
   - **Suggested dates** — Timeline for each phase (optional, can be set later)
4. Click **"Create Election"**

**Next step:** Click **"Activate"** on the management page to begin setup.

---

### Phase 2: Submit for Approval — Platform Review (State: SubmittedForApproval)

**What it means:** The election has been submitted for platform approval. This verifies the election meets basic requirements.

**What you can do:**
- View the election
- **Cannot** edit settings or proceed until approved

**How to submit:**
1. From the management page, review the pre-submission checklist
2. Ensure timezone is configured
3. Click **"Submit for Approval"**

**After approval:** The election moves to Setup Administration.

**If rejected:** You'll see the reason. Click **"Revise and Resubmit"** to fix issues and try again.

---

### Phase 3: Setup Administration (State: SetupAdministration)

**What it means:** This is where you configure the election structure — officers, posts (positions), and voters (the electorate).

**What you can do:**
- Manage election officers (appoint chief, deputy, commissioners)
- Create posts (positions candidates will run for)
- Import/manage voters
- Configure timeline dates
- Upload organisation logo

#### 3a: Appoint Election Officers

Before you can complete administration, you need at least one **active chief officer**.

1. Go to the **Officers** section
2. Click **"Add Officer"**
3. Select a user and assign role: `chief`, `deputy`, or `commissioner`
4. Set status to `active`

#### 3b: Create Posts (Positions)

Posts are the positions candidates will run for (e.g., "President", "Secretary", "Board Member").

1. Go to the **Posts** section
2. Click **"Add Post"**
3. Configure:
   - **Name** — e.g., "President"
   - **Description** — Optional
   - **Number to elect** — How many winners for this post
   - **National or regional** — National posts visible to all voters; regional posts filtered by voter region
4. Click **"Save"**

#### 3c: Import Voters

Voters are the people who can vote in this election.

1. Go to the **Voters** section
2. Click **"Import Voters"**
3. Upload CSV or add individually
4. Voters appear in `pending` status — you must **approve** them before they can vote

**Preconditions to complete administration:**
- At least **one post** created
- At least **one voter** approved
- At least **one active chief officer**

**How to complete:**
1. Ensure all three preconditions are met
2. Click **"Complete Administration"**
3. Provide a reason (required, min 5 characters)

**Next step:** Nomination phase opens.

---

### Phase 4: Setup Nomination (State: SetupNomination)

**What it means:** Candidates can apply (or be nominated) for posts. The chief/deputy approves or rejects candidacies.

**What you can do:**
- View candidate applications
- Approve or reject candidates
- Manage candidacy settings

**Candidate Application Flow:**
```
Candidate visits election page
    ↓
Clicks "Apply for Candidacy"
    ↓
Selects post → fills in details → submits
    ↓
Chief/Deputy reviews application
    ├── Approves → Candidate added to ballot
    └── Rejects → Candidate notified
```

**Precondition to complete nomination:**
- At least **one approved candidate** per post

**How to complete:**
1. Ensure all posts have approved candidates
2. Click **"Complete Nomination"**
3. Provide a reason (required)

**Need to force-close nominations?** Use **"Force Close Nomination"** to reject all pending candidates and proceed. Use only in emergencies.

**Next step:** Ready for Voting.

---

### Phase 5: Ready for Voting (State: ReadyForVoting)

**What it means:** All setup is done. The election is waiting for the voting window to open.

**What you can do:**
- Review the election configuration
- Verify voter list
- Check that voting dates are configured

**Preconditions to open voting:**
- **Voting window defined** — Both `voting_starts_at` and `voting_ends_at` must be set
- **Timezone set** — Election must have a timezone configured
- **Capacity eligibility** — Election meets plan requirements

**How to open voting:**
1. Set voting start and end dates in the **Timeline** section
2. Click **"Open Voting"**
3. The election moves to Voting Active state when the voting window opens

**Important:** If voting dates are in the future, the election will remain in Ready for Voting until the window opens. If voting dates are in the past or present, voting opens immediately.

**Next step:** Voting Active.

---

### Phase 6: Voting Active (State: VotingActive)

**What it means:** Voters are casting their ballots. This is the core voting period.

**What you can do:**
- Monitor voting progress (voter stats)
- View the election viewboard (read-only)
- **Cannot** modify posts, voters, candidates, or settings

**Voter Experience:**
```
Voter visits election page
    ↓
Enters voting code (or uses voter slug)
    ↓
Reviews candidates for each post
    ↓
Selects candidates (or "no vote" option)
    ↓
Confirms selection → votes cast
    ↓
Receives verification code
```

**How to close voting:**
1. When the voting period ends (or you need to close early)
2. Click **"Close Voting"**
3. Provide a reason (optional)

**Note:** The system can auto-close voting if configured via the timeline settings. Manual closure is always available to chief/deputy.

**Next step:** Counting.

---

### Phase 7: Counting (State: Counting)

**What it means:** Voting has ended. Votes are being tallied. Results are not yet visible to voters.

**What you can do:**
- View vote statistics (number of ballots cast)
- **Cannot** view individual votes (anonymity guarantee)
- Prepare to publish results

**How to publish results:**
1. Review that all votes are properly tallied
2. Click **"Publish Results"**
3. The system automatically verifies vote-result integrity before publishing

**Note:** Only the **chief** can publish results. Deputies and commissioners cannot.

**Integrity verification:** Before publishing, the system verifies that stored results match expected counts. Any discrepancies are automatically corrected and logged.

**Next step:** Results Published.

---

### Phase 8: Results Published (State: ResultsPublished)

**What it means:** Results are visible to all voters and the public.

**What you can do:**
- View final results
- View per-post breakdowns
- Unpublish results if needed (chief only)
- Prepare to archive

**How to unpublish:**
1. Click **"Unpublish Results"**
2. Make corrections if needed
3. Re-publish when ready

**How to archive:**
1. Ensure results are final
2. Click **"Archive"**
3. The election becomes a historical record

**Next step:** Archived.

---

### Phase 9: Archived (State: Archived)

**What it means:** The election is complete and archived. This is a terminal state.

**What you can do:**
- View archived results
- Access historical reports
- **Cannot** make any changes

**Archived elections remain accessible** for audit and reference. All data is preserved.

---

### Suspension: Operational Governance Freeze (State: Suspended)

**What it means:** The election is temporarily frozen due to a governance issue. All operations are locked except the ability to resume.

**When to suspend:**
- Misconduct or irregularities detected
- Emergency requiring investigation
- Legal/regulatory hold
- Any situation requiring immediate freeze

**What happens during suspension:**

| Capability | During Suspension | After Resume |
|-----------|------------------|--------------|
| View management page | ✅ | ✅ |
| View results | ✅ | ✅ |
| Manage settings | ❌ | ✅ |
| Manage voters | ❌ | ✅ |
| Manage posts/candidates | ❌ | ✅ |
| Open/close voting | ❌ | ✅ |
| Publish results | ❌ | ✅ |
| Resume election | ✅ | N/A |

**How to suspend:**
1. Click **"Suspend Election"** button (visible only to chief)
2. In the governance modal:
   - **Reason** — Required, min 10 characters. Explain why suspension is needed.
   - **Category** — Optional: General, Misconduct, Emergency, Investigation, Other
3. Click **"Suspend Election"** to confirm
4. The election freezes immediately

**What you see during suspension:**
- Red banner: "Election Suspended — Reason: [your reason]"
- All action buttons hidden except "Resume Election"
- Voters see a message that the election is suspended

**How to resume:**
1. Click **"Resume Election"** in the suspension banner
2. The election returns to its pre-suspension state
3. All operations become available again

**Suspension categories:**

| Category | Use Case |
|----------|----------|
| General | Default — any non-specific governance issue |
| Misconduct | Candidate or officer misconduct allegations |
| Emergency | Unexpected urgent situation |
| Investigation | Active investigation requiring freeze |
| Other | Reason doesn't fit other categories |

---

## Timeline Configuration

### Phase Dates

The election has configurable suggested dates for each phase:

| Phase | Start Field | End Field |
|-------|-------------|-----------|
| Administration | `administration_suggested_start` | `administration_suggested_end` |
| Nomination | `nomination_suggested_start` | `nomination_suggested_end` |
| Voting | `voting_starts_at` | `voting_ends_at` |

### Chronological Rules

```
administration_end < nomination_start < nomination_end < voting_starts_at < voting_ends_at
```

The system enforces chronological ordering:

| Rule | Violation Message |
|------|------------------|
| Nomination must start after administration ends | "Nomination must start after administration ends." |
| Voting must start after nomination ends | "Voting must start after nomination ends." |

### Phase Edit Restrictions

| Phase | Can Edit Before Started? | Can Edit After Completed? |
|-------|-------------------------|---------------------------|
| Administration | Yes | No |
| Nomination | Yes | No |
| Voting | Yes (before voting starts) | No (locked) |
| Results | N/A | Never editable |

### Auto-Transition Settings

On the Timeline page, you can configure:
- **Allow auto-transition** — Enable automatic phase progression
- **Auto-transition grace days** — Buffer period before auto-transition kicks in

---

## Voter Management

### Voter Import

1. Go to the **Voters** section on the management page
2. Click **"Import Voters"**
3. Choose import method:
   - **CSV upload** — Bulk import from spreadsheet
   - **Manual entry** — Add individual voters
4. Voters appear in `pending` status

### Voter Approval

Pending voters must be approved before they can vote:

1. Go to the **Voters** section
2. Select voters to approve (bulk selection available)
3. Click **"Approve Voters"**

**Bulk operations (chief/deputy only):**
- **Bulk approve** — Up to 1000 voters at a time
- **Bulk disapprove** — Up to 1000 voters at a time

### Voter Statuses

| Status | Meaning |
|--------|---------|
| `pending` | Imported but not yet approved |
| `active` | Approved and eligible to vote |
| `inactive` | Disapproved or removed |

---

## Post & Candidate Management

### Posts

**Creating a post:**
1. Go to **Posts** on the management page
2. Click **"Add Post"**
3. Set name, number to elect, national/regional scope
4. For regional posts: set the region/state filter

**Post types:**
- **National** — Visible to all voters in the election
- **Regional** — Only visible to voters in the specified region

### Candidates

**Candidate flow:**
- Candidates apply through the election page
- Chief/deputy reviews and approves/rejects
- Approved candidates appear on the ballot

**Managing candidates:**
1. Go to **Candidates** on the management page
2. View pending, approved, and rejected candidates
3. Click **"Approve"** or **"Reject"** for each

---

## Electing Election Officers

### Appointment

Election officers must be appointed before administration can be completed:

1. Go to **Officers** on the management page
2. Click **"Add Officer"**
3. Select user, assign role, set active status

### Officer Status

| Status | Meaning |
|--------|---------|
| `active` | Officer is currently serving |
| `inactive` | Officer is not serving |

### Current Officers

At a minimum, you need **one active chief** to complete the administration phase. There is no upper limit on the number of officers.

---

## Best Practices

### Before Launch

- [ ] **Set timezone** — Required for submission; prevents scheduling confusion
- [ ] **Configure all phase dates** — Helps voters and candidates plan
- [ ] **Verify voter list** — Ensure all eligible voters are imported and approved
- [ ] **Test with a small group** — Have a few officers test the voting flow
- [ ] **Appoint backup deputy** — Ensure continuity if chief is unavailable

### During Voting

- [ ] **Monitor voter participation** — Check voter stats regularly
- [ ] **Keep voting window reasonable** — 24-72 hours is typical
- [ ] **Communicate voting instructions** — Share the election link with voters

### After Voting

- [ ] **Verify results before publishing** — Review counts for anomalies
- [ ] **Communicate results promptly** — Share via organisation channels
- [ ] **Archive when complete** — Preserves the election record

### Suspension

- [ ] **Document the reason clearly** — The reason is recorded in the governance audit trail
- [ ] **Use appropriate category** — Helps with governance reporting
- [ ] **Resume promptly** — Once the issue is resolved, resume to restore operations
- [ ] **Only chief should suspend** — This is a governance intervention, not a routine action

---

## Glossary

| Term | Definition |
|------|------------|
| **Election** | A single voting event with posts, candidates, and voters |
| **Organisation** | The tenant that owns the election |
| **Post** | A position to be elected (e.g., "President") |
| **Candidate** | A person running for a post |
| **Voter** | A person eligible to vote in the election |
| **Chief** | Election officer with full management authority |
| **Deputy** | Election officer with most management authority |
| **Commissioner** | Read-only election officer |
| **Suspension** | Temporary governance freeze of all operations |
| **Timeline** | Configuration of phase dates for the election |
| **SSOT** | Single Source of Truth — state derived from facts |
| **Lifecycle** | The progression of states from Draft to Archived |
| **Operational Overlay** | Governance intervention (suspension) separate from lifecycle |
| **Governance Audit** | Record of governance actions (suspend/resume) |
| **Voting Window** | The period during which voting is open |
| **Constitutional Transition** | A state change validated by the Constitutional Transition Guard |

---

## Need Help?

| Issue | Contact |
|-------|---------|
| Account/access problems | Platform support |
| Election configuration questions | Your organisation's chief officer |
| Technical issues during voting | Platform support |
| Governance concerns | Platform admin |
