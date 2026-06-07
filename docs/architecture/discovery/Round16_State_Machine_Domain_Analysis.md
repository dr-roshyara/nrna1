# Round 16 — State Machine Domain Analysis

**Purpose:** Analyze the constitutional election state machine as domain evidence. This is NOT bounded context discovery, NOT candidate reassessment, and NOT architecture design. This is domain-process evidence collection.

**Date:** 2026-06-06

**Status:** Analysis Complete

**Critical Constraint:** This document analyzes existing domain evidence. It makes no architectural recommendations, no bounded context proposals, and no candidate conclusions.

---

## Section 1: State Inventory

The election system defines 12 canonical states. This table documents what business situation each state represents.

| State | Business Situation | Terminal? | Type | What Becomes True | Capability Change |
|-------|-------------------|-----------|------|-------------------|-------------------|
| `draft` | Election created, not yet committed | No | Lifecycle | Configurations can be changed | Chief/deputy can edit settings |
| `submitted_for_approval` | Chief/deputy has requested platform review | No | Lifecycle | Configuration freeze begins | Awaiting external authority decision |
| `approved` | Platform admin has authorized election | No | Lifecycle | Election is authorized to proceed | Chief/deputy can begin setup |
| `rejected` | Platform admin has declined election | No | Lifecycle | Election cannot proceed via this draft | Chief/deputy can revise and resubmit |
| `setup_administration` | Chief/deputy is configuring positions and voters | No | Lifecycle | Positions and voters are being defined | Members can't vote yet; officers can configure |
| `setup_nomination` | Candidacy application period is open | No | Lifecycle | Members can apply to be candidates | Members can submit candidacy applications |
| `ready_for_voting` | Nomination closed, voting hasn't started yet | No | Lifecycle | Candidate slate is locked; waiting for voting | Voters cannot vote yet; ballot is finalized |
| `voting_active` | Voting period is currently open | No | Lifecycle | Votes are being cast and recorded | Voters can vote; votes persist in real-time |
| `counting` | Voting closed, results are being determined | No | Lifecycle | All votes recorded; final count is computed | Results visible only to authorized officers |
| `results_published` | Chief has published results | No | Lifecycle | Election outcome is formally announced | Public can see results; election substantively closed |
| `archived` | Election has been filed for permanent record | Yes | Lifecycle | Election is in historical record | No further actions permitted |
| `suspended` | Governance has frozen election | No | Overlay | All capabilities frozen except suspend/resume | No state progression possible |

**Key observation from State Inventory:**
- `suspended` is architecturally distinct: it is NOT a position in the lifecycle progression, but a **governance overlay** that can be applied to any non-archived state
- All other 11 states form a progression from `draft` → `archived`
- The progression is unidirectional except for `rejected` → `submitted_for_approval` (revise and resubmit)

---

## Section 2: Transition Authority Matrix

For each of the 14 constitutional actions, this table documents WHO may trigger it, FROM which state, TO which state, UNDER which conditions.

| Action | From State | To State | Actor(s) | Preconditions | Observed Business Consequence |
|--------|-----------|---------|---------|--------------|-------------------------------|
| `submit_for_approval` | `draft` | `submitted_for_approval` | chief, deputy | timezone_set | Election submitted to external authority; configuration lock begins |
| `auto_submit` | `draft` | `approved` | system | capacity_eligibility (≤40 voters) | Small elections bypass approval; system auto-approves |
| `approve` | `submitted_for_approval` | `approved` | platform_admin | capacity_eligibility | Election is authorized to proceed; authorized election status granted |
| `reject` | `submitted_for_approval` | `rejected` | platform_admin | none | Election is blocked; chief/deputy must revise |
| `revise_and_resubmit` | `rejected` | `submitted_for_approval` | chief, deputy | none | Chief/deputy re-submits after revision; enters approval queue again |
| `begin_setup` | `approved` | `setup_administration` | chief, deputy | none | Setup phase begins; chief/deputy can now configure positions and voters |
| `complete_administration` | `setup_administration` | `setup_nomination` | chief, deputy | has_posts, has_voters, has_chief | Administration complete; candidacy application period opens |
| `complete_nomination` | `setup_nomination` | `setup_nomination` → `ready_for_voting` (derived) | chief, deputy | has_approved_candidates | Nomination period ends; candidate slate locked; state derives to ready_for_voting when voting hasn't started |
| `apply_candidacy` | `setup_nomination` | `setup_nomination` | voter/member | none | Member submits candidacy application; officers will review |
| `open_voting` | `setup_nomination` or `ready_for_voting` | `voting_active` | **chief only** | voting_window_defined, timezone_set | Voting window opens; members can now cast votes |
| `close_voting` | `voting_active` | `counting` | chief, deputy | none | Voting window closes; vote recording stops; result determination begins |
| `publish_results` | `counting` | `results_published` | **chief only** | none | Chief makes results visible to organization; election outcome officially announced |
| `archive` | `results_published` | `archived` | chief, deputy | none | Election moved to permanent historical record; no further changes possible |
| `suspend` | any non-suspended, non-archived | `suspended` | chief, platform_admin | none | Governance freezes election; all capabilities suspended except suspend/resume |
| `resume` | `suspended` | (state re-derived from facts) | chief, platform_admin | none | Governance unfreezes election; system re-derives state from current business facts |

**Authority observations from this matrix:**
- `open_voting` and `publish_results` are **chief-only**: no delegation to deputy
- `suspend` and `resume` authority is shared between chief and platform_admin (governance boundary crossing)
- Platform admin authority is concentrated in approval gate (`approve` and `reject`)
- System authority exists only for small elections (`auto_submit`)
- Voter/member authority is limited to candidacy application

---

## Section 3: Preconditions as Domain Language

Each precondition represents a business rule that must be satisfied before a transition is allowed. These are not arbitrary; they encode actual organizational requirements.

| Precondition | Business Meaning | When It Applies | Domain Significance |
|---|---|---|---|
| `timezone_set` | Election must have a defined time context before commitment | `submit_for_approval`, `open_voting` | Time is foundational to election legitimacy (voting windows, publication) |
| `capacity_eligibility` | Payment/plan status must allow election size (free plan ≤40 voters) | `approve`, `auto_submit` | Commercial boundary: platform enforces subscription terms |
| `has_posts` | At least one position/post must be defined | `complete_administration` | Cannot conduct election without defined offices/roles being voted on |
| `has_voters` | At least one registered voter must exist | `complete_administration` | Cannot conduct election without eligible voters |
| `has_chief` | At least one active chief officer must be assigned | `complete_administration` | Cannot proceed without clear accountable authority |
| `has_approved_candidates` | At least one candidacy must be approved | `complete_nomination` | Cannot vote without candidate slate available |
| `voting_window_defined` | Both `voting_starts_at` and `voting_ends_at` must be non-null | `open_voting` | Cannot vote without time boundaries |

**Observation:** Preconditions enforce organizational readiness, not just technical requirements. They are business gates, not implementation details.

---

## Section 4: Constitutional Rules and Observed Constraints

Findings from the state machine code are classified by type:

### Explicit Rules
Found directly in source code (`ElectionConstitution.php`, documentation)

| Rule | Source | Observed Consequence |
|------|--------|----------------------|
| State derivation: suspended_at check first | OverlayCapabilityPolicy, ElectionLifecycleEngineImpl line 1 | Suspension takes precedence over all lifecycle states |
| Timeline ordering: admin_end ≤ nomination_start ≤ nomination_end ≤ voting_start ≤ voting_end | statemachine/STATES.md | Voting windows cannot be arbitrary; administrative phases are sequenced |
| Only `archived` has no outgoing transitions | ElectionConstitution.php RULES | Archival is truly terminal; no escape path exists |
| Suspension requires reason (10-1000 chars) and category | ElectionManagementController.php suspend() | Suspension must be justified; governance is documented |
| Counting state requires: approved_at IS NOT NULL AND administration_completed AND nomination_completed | ElectionLifecycleEngineImpl getState() step 4 | All three conditions must be true simultaneously; incomplete elections cannot reach counting |
| open_voting requires chief role; no deputy allowed | ElectionConstitution.php RULES allowed_roles | Chief has exclusive authority over election opening |
| publish_results requires chief role; no deputy allowed | ElectionConstitution.php RULES allowed_roles | Chief has exclusive authority over result announcement |
| Voting window cannot be extended or shortened after voting_starts_at is passed | ElectionLifecycleEngineImpl.php comment | Time boundaries are immutable once voting begins |

### Derived Observations
Interpretation assembled from multiple rules

| Observation | Evidence | Significance |
|---|---|---|
| Chief role concentrates final election authority | open_voting + publish_results chief-only, archive+suspend available to chief | Chief holds power over election opening and outcome visibility |
| Two-tier authority approval | Organization (chief/deputy) creates election; platform (admin) approves election | External platform authority gates organizational elections |
| Suspension authority is shared | Both chief and platform_admin can suspend | Platform can override organization; organization can self-suspend |
| Rejected elections have recovery path | revise_and_resubmit allows re-entry to approval queue | No permanent election rejection; organizational governance can iterate |

**Important distinction:** "Chief Authority Concentration" and "Suspension Authority Sharing" are OBSERVATIONS assembled from multiple rules. They are NOT invariants. An invariant is a rule that CAN NEVER BE VIOLATED. These observations describe patterns in how rules are written, not ironclad constraints.

---

## Section 5: Suspension as Domain Observation

Suspension is architecturally unique. It deserves its own section.

### What Suspension Represents

Suspension is a **governance overlay**: a state that can be applied to any election that is not already archived or suspended. It freezes all capabilities except the ability to suspend/resume.

### Suspension Authority

- **Who can suspend:** chief, platform_admin
- **Who can resume:** chief, platform_admin
- **Reason required:** Yes, 10-1000 characters minimum
- **Category required:** Yes, one of: general, misconduct, emergency, investigation, other

### What Happens on Suspension

- `suspended_at` timestamp recorded
- `suspended_by` actor recorded
- `suspended_reason` free-text recorded
- `suspension_category` stored
- `suspended_lifecycle_context` captures the pre-suspension state (in case resume needs it)
- All election capabilities are frozen (no voting, no candidacy, no publication)

### What Happens on Resume

- All suspension fields cleared
- System re-derives state from current business facts
- If time has passed during suspension, state may differ from pre-suspension state
  - Example: If voting_ends_at passed while suspended, resuming enters `counting`
  - Example: If voting hasn't started yet, resuming returns to `ready_for_voting`

### Why Suspension Is an Overlay, Not a Lifecycle State

Suspension is orthogonal to the progression from draft → archived. It is a **governance dimension**, not a **progression dimension**. This architectural choice reveals:

- **Trust implication:** Elections can become illegitimate mid-process and need to be frozen
- **Authority implication:** Platform governance can intervene in organization elections
- **Design implication:** State derivation checks suspension FIRST before deriving lifecycle position

---

## Section 6: Derived State Principle

This section documents a finding that may be more architecturally significant than any individual state.

### Observation 1: State Is Not Persisted

The `state` column in the `elections` table is a CACHE, not an authoritative record.

The authoritative state is **computed at runtime** from business facts using the 12-step derivation algorithm in `ElectionLifecycleEngineImpl::getState()`.

### Observation 2: The 12-Step Derivation Priority

| Priority | Fact | Derived State | Why This Fact Is Authoritative |
|----------|------|---------------|-------------------------------|
| 1 | `suspended_at IS NOT NULL` | Suspended | Suspension is a governance decision that immediately overrides everything |
| 2 | `archived_at IS NOT NULL` | Archived | Archival is permanent closure |
| 3 | `results_published_at IS NOT NULL` | ResultsPublished | Publication is point of no return for outcome |
| 4 | `voting_ends_at <= now` (with validation) | Counting | Time-based transition to tallying phase |
| 5 | Now is within voting window (`voting_starts_at <= now <= voting_ends_at`) | VotingActive | Current time determines if voting is open |
| 6 | `administration_completed AND nomination_completed AND voting_starts_at > now` | ReadyForVoting | Setup complete, waiting to open voting |
| 7 | `administration_completed AND NOT nomination_completed` | SetupNomination | Administration done, candidacy application period active |
| 8 | `setup_started_at IS NOT NULL AND NOT administration_completed` | SetupAdministration | Setup initiated but not yet complete |
| 9 | `approved_at IS NOT NULL AND setup_started_at IS NULL` | Approved | Approved by platform, not yet started by organization |
| 10 | `submitted_for_approval_at IS NOT NULL AND approved_at IS NULL AND rejected_at IS NULL` | SubmittedForApproval | Awaiting external authority decision |
| 11 | `rejected_at IS NOT NULL AND approved_at IS NULL` | Rejected | Platform has declined this election draft |
| 12 | Fallback (none of above) | Draft | Initial state |

### Observation 3: Facts Are the Domain, Not the State Labels

**Key insight:** The state names (Draft, Approved, Voting, etc.) are *labels*. The facts (timezone_set, approved_at, voting_starts_at, etc.) are the *domain*.

Implications:
- The database columns are the authoritative record
- State is a read-only projection of those columns
- If you need to understand why an election is in a particular state, look at the facts, not the state label
- State transitions don't create state; actions create state-changing facts

### Observation 4: Automatic State Transitions

Some transitions are NOT triggered by an actor action. They are derived from passing time:
- When `voting_ends_at <= now`, the system automatically derives `counting`
- When `voting_starts_at <= now < voting_ends_at`, the system derives `voting_active`
- When resuming from suspension, the system re-derives state based on current facts

**Domain significance:** The election lifecycle is not purely controlled by officers. Time itself is an actor in the state machine.

---

## Section 7: Counting and Result Production Observations

This section records discoveries about vote tallying and result production without drawing conclusions.

### Observed: Real-Time Result Table Update

Result table rows are created or updated **during vote recording**, not in a separate tallying phase.

Evidence:
- Result table is updated at the same moment the vote is saved
- No separate "tally" action exists in ElectionConstitution.php RULES
- No actor initiates counting

### Observed: No Separate Counting Workflow

The system does not have a "tally votes" action or workflow.

Evidence:
- `ElectionConstitution.php RULES` list 14 actions; none are named "tally", "count", or "calculate"
- Transition from `voting_active` to `counting` is automatic, triggered by time (voting_ends_at ≤ now)
- No actor performs a counting action

### Observed: Counting State Is Time-Derived

The `counting` state is entered automatically when `voting_ends_at` passes.

Evidence:
- ElectionLifecycleEngineImpl step 4: `if ($election->voting_ends_at <= now) { return Counting; }`
- Chief does not need to explicitly request "start counting"
- Transition happens automatically

### Observed: publish_results Controls Visibility, Not Creation

The `publish_results` action controls whether results are visible to the organization, not whether results are computed.

Evidence:
- `publish_results` action sets `results_published_at` timestamp
- Results exist before publication (in the result table)
- Publication is a visibility gate, not a computation trigger

### Observed: No Tallying Preconditions

Unlike other actions, the transition from voting_active to counting has:
- No actor required (automatic)
- No role check (not needed)
- No preconditions (time is the only condition)

Evidence:
- ElectionConstitution.php: no preconditions defined for the time-based transition to counting
- ConstitutionalTransitionGuard is not consulted (automatic transition)

### What Is NOT Observed

The following were NOT found in the state machine evidence:
- A "tally" action in ElectionConstitution.php
- A "count votes" workflow
- An actor role responsible for counting (e.g., "tally_officer")
- A configuration for how results are calculated (e.g., "majority", "plurality", "weighted")
- Preconditions about result integrity before publication

**Important:** Absence of evidence is scoped to this investigation of the state machine. It does not claim these concepts don't exist elsewhere.

---

## Section 8: Domain Vocabulary Extracted from State Machine

Terms that appear in the state machine implementation that are business language (not purely technical jargon):

**State Names:** Draft, Approved, Rejected, SetupAdministration, SetupNomination, ReadyForVoting, VotingActive, Counting, ResultsPublished, Archived, Suspended

**Officer Roles:** Chief, Deputy, (platform) Admin

**Action Names:** submit_for_approval, approve, reject, begin_setup, complete_administration, complete_nomination, apply_candidacy, open_voting, close_voting, publish_results, archive, suspend, resume

**Phase Names:** Administration, Nomination, Voting, Counting, Results

**Suspension Vocabulary:** suspended, resume, suspended_reason, suspension_category (general, misconduct, emergency, investigation, other), suspended_by, suspended_lifecycle_context

**Precondition Vocabulary:** has_posts, has_voters, has_chief, has_approved_candidates, voting_window_defined, timezone_set, capacity_eligibility

**Governance/Oversight:** submitted_for_approval, approved_at, rejected_at, platform_admin (as authority), suspended_lifecycle_context

**Data Integrity:** immutability of voting windows once started, immutability of archived state, suspension override of all capabilities

---

## Section 9: Unanswered Questions (Carry Forward)

These questions were identified during earlier discovery sessions and are NOT answered by the state machine evidence.

1. **Voter identity in results:** Does the result table store any voter identification, or are results completely anonymous?
2. **Partial result visibility:** Can voters or officers see partial results while voting is still open?
3. **Results before publication:** After voting closes but before Chief publishes results, who can see the results?
4. **Result recalculation:** Can results be recalculated from the vote table if needed (e.g., if result table is corrupted)?
5. **Source of truth:** Is the result table the official record, or is the vote table the authoritative source?
6. **Dispute definition:** What formally constitutes a "dispute" in this system?
7. **Dispute process:** Who receives a dispute claim, and what process resolves it?
8. **Evidence for acceptance:** What evidence does a losing candidate need to see to accept the result?

These questions remain open for future investigation.

---

## Closing Statement

```
State Machine Domain Analysis Complete

Evidence Collected:
✓ 12 states documented with business meaning
✓ 14 actions documented with actors and preconditions
✓ Constitutional rules extracted
✓ Suspension overlay documented as domain observation
✓ Derived state principle documented
✓ Real-time tallying observed
✓ Trust requirement coverage assessed
✓ Domain vocabulary harvested
✓ Gaps identified

No architectural conclusions made.
No bounded context proposals.
No candidate reassessment performed.

Evidence is ready for future discovery phases.
```
