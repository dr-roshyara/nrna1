# Round 34B — Command Discovery

## ARB Formal Decision

```
Round 34B

APPROVED WITH OBSERVATIONS

DDD Quality:            VERY HIGH
Command Discovery:      VERY HIGH
Governance Discipline:  VERY HIGH
Ubiquitous Language:    HIGH
Design Contamination:   LOW

Confidence: HIGH
```

**Verified:**
- Commands express actor intent — not database actions, not event names
- CastVote selected as ubiquitous language (voters cast votes; systems record votes)
- VerificationRequested resolved: DISCOVERED (RequestVerification → PENDING → officer obligation)
- VoteRejected resolved: DISCOVERED with reason variants; technical terminology in variant names to be refined in future ADR
- VO-1 anonymity boundary correctly positioned: eligibility uses identity; CastVote does not
- Governance debt remains visible on all GovernanceState commands
- Candidate aggregates remain candidates

**Observations (non-blocking):**

1. `CastVote` must not evaluate eligibility. Eligibility must be fully resolved before `CastVote` arrives at the aggregate. The Vote aggregate contains no eligibility logic. This must be stated explicitly in Round 34C.

2. `TransitionGovernanceState` is provisionally acceptable pending D35/D37/ADH-1 resolution. Later design may produce more precise commands: `OpenVoting`, `CloseVoting`, `PublishResults`.

3. `VoteRejected[DuplicateHash]` — "DuplicateHash" is implementation-oriented. Voters do not think in hash terms. Future ADR must refine rejection vocabulary into domain-centric language.

**Architecture Constraint Pending ADR (reinforced from Round 34A):**
> Identity stops before the Vote aggregate boundary. Eligibility context evaluates voter identity. CastVote command carries ballot content only. This is the architectural enforcement point for VO-1 at the command layer.

**Authorization:** Round 34C Aggregate Interaction Analysis — AUTHORIZED

---

**Date:** 2026-06-08

**Phase:** Command Discovery

**Type:** Design Artifact

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 33 Aggregate Boundary Design (APPROVED)
- Round 34A Domain Event Discovery (APPROVED)

**Purpose:** Discover commands that express actor intent within approved aggregate lifecycles. Commands emerge from actor intent. Commands must not be derived mechanically from event names.

**Governing Rules:**

```
Commands express intent.
Aggregates decide outcome.
Events record outcome.
```

```
Commands are requests.
Events are facts.
```

**Discovery Chain:**

```
Actor
    ↓
Intent
    ↓
Command
    ↓
Aggregate evaluates invariants
    ↓
Domain Event
```

---

## Scope

This document covers command discovery only.

**OUT OF SCOPE:**
- Domain events (Round 34A — complete)
- Aggregate interaction patterns (Round 34C)
- Command handlers, application services, HTTP controllers
- Command buses, message brokers, serialization
- Authorization enforcement implementation

**Source aggregates:**
- Verification (APPROVED AGGREGATE DESIGN)
- Vote (APPROVED AGGREGATE DESIGN)
- GovernanceState (APPROVED AGGREGATE PATTERN — Governance Debt Active)
- RoleAssignment (AGGREGATE CANDIDATE)
- ReplaySession (AGGREGATE CANDIDATE)

**Provisional events to resolve in this round:**
- VerificationRequested — investigate whether actor intent produces this event
- VoteRejected — determine whether one command produces multiple outcomes, or multiple commands exist

---

## Discovery Method

For each aggregate, four questions are applied:

```
Step 1: Who are the actors?
Step 2: What does each actor intend?
Step 3: What command names capture that intent in the ubiquitous language?
Step 4: What does the aggregate decide, and what event results?
```

Commands must use the actor's language, not the system's language.

Wrong: `RecordVote` (system perspective)
Right: `CastVote` or `SubmitBallot` (actor perspective)

---

## Aggregate 1 — Verification

### Step 1: Actors

| Actor | Role |
|-------|------|
| Verification Officer | Holds authority to attest and revoke trust |
| Participant | Seeks verification of their identity |

---

### Step 2: Actor Intents

| Actor | Intent |
|-------|--------|
| Participant | "I wish my identity to be verified" |
| Officer | "I attest this identity as trustworthy" |
| Officer | "I withdraw trust from this identity" |

---

### Step 3: Command Candidates

| Command | Actor | Intent Captured |
|---------|-------|----------------|
| **RequestVerification** | Participant | "I wish to begin the verification process" |
| **GrantVerification** | Officer | "I attest this identity" |
| **RevokeVerification** | Officer | "I withdraw this trust attestation" |

**Note on ubiquitous language:**
- `RequestVerification` not `InitiateVerification` — the participant requests; the officer decides
- `GrantVerification` not `ApproveVerification` or `AcceptVerification` — the officer grants trust, not approval of a form
- `RevokeVerification` not `RemoveVerification` or `DeleteVerification` — revocation is a deliberate governance act

---

### Step 4: Aggregate Decision Chain

**RequestVerification:**
```
Participant intends to request verification
    ↓
Aggregate evaluates:
  — Is this identity already verified? (TA-1 uniqueness check)
  — Is the request structurally valid?
    ↓
Outcome:
  VerificationRequested  (lifecycle: → PENDING)
```

**Resolution of Round 34A provisional item:** `VerificationRequested` is a valid domain event. The PENDING lifecycle state is created by an actor's deliberate request. The domain cares that a verification request exists because the officer must then act on it. The investigation is complete: `VerificationRequested` is elevated from "Requires Investigation" to **DISCOVERED**.

---

**GrantVerification:**
```
Officer intends to attest identity
    ↓
Aggregate evaluates:
  TA-1: Is verification unique? (no prior ACTIVE for this identity)
  TA-2: Is attribution captured? (officer identity recorded)
  TA-3: Append-only constraint satisfied?
    ↓
Outcome:
  VerificationGranted  (lifecycle: PENDING → ACTIVE)
```

---

**RevokeVerification:**
```
Officer intends to withdraw trust
    ↓
Aggregate evaluates:
  TA-2: Is attribution captured? (officer identity recorded)
  TA-3: Append-only constraint satisfied?
    ↓
Outcome:
  VerificationRevoked  (lifecycle: ACTIVE → REVOKED)
```

---

### Verification Command Summary

| Command | Invariants Evaluated | Outcome Event |
|---------|---------------------|---------------|
| RequestVerification | TA-1 (uniqueness pre-check) | VerificationRequested |
| GrantVerification | TA-1, TA-2, TA-3 | VerificationGranted |
| RevokeVerification | TA-2, TA-3 | VerificationRevoked |

**Note:** `RevokeVerification` may produce no event if preconditions are not met (e.g., verification is already REVOKED). That failure is a domain constraint violation, not a domain event.

---

## Aggregate 2 — Vote

### Step 1: Actors

| Actor | Role |
|-------|------|
| Eligible Voter | Participant whose eligibility has been established |

**Note:** There is only one actor type for the Vote aggregate. No officer, administrator, or system can cast a vote on behalf of a voter. This is a constitutional consequence of VO-1.

---

### Step 2: Actor Intents

| Actor | Intent |
|-------|--------|
| Eligible Voter | "I wish to cast my ballot in this election" |

---

### Step 3: Command Candidates — Ubiquitous Language Investigation

The ARB asked Round 34B to discover the correct ubiquitous language.

Candidates evaluated:

| Candidate | Perspective | Assessment |
|-----------|-------------|-----------|
| `RecordVote` | System perspective | Rejected — this is what the system does, not what the voter does |
| `SubmitVote` | Actor perspective | Valid — "submit" captures the deliberate act of tendering a ballot |
| `CastVote` | Actor perspective | Valid — "cast" is the canonical electoral term for the voter's act |
| `SubmitBallot` | Actor perspective | Valid — "ballot" emphasizes the selection artifact, not the act |

**Decision:** `CastVote` is preferred. It is:
- The canonical term in electoral domain language
- Actor-centric (voters cast votes; systems record them)
- Technology-independent (exists in paper-based elections)
- Clearly distinct from `VoteRecorded` (the system's record of the outcome)

---

### Step 4: Aggregate Decision Chain

**CastVote — Resolution of Round 34A provisional VoteRejected:**

```
Eligible Voter intends to cast their ballot
    ↓
Aggregate evaluates:
  VO-1: Does this vote create a voter-vote link? → MUST NOT
  VO-2: Is the checksum valid?
  VO-3: Is the receipt hash generated?
  VO-4: Can all selections be recorded atomically?
    +
  Is voting currently active? (governance gate)
  Is this a duplicate submission?
    ↓
Outcomes:
  VoteRecorded           (all invariants satisfied)

  — or —

  VoteRejected[VotingClosed]    (voting not active)
  VoteRejected[DuplicateHash]   (VO-2 duplicate detected)
  VoteRejected[IntegrityFailure] (VO-2/VO-3 failed)
```

**Resolution of VoteRejected provisional classification:** There is one command (`CastVote`) with multiple possible rejection outcomes. The rejection reasons are distinct semantic categories:

| Rejection Variant | Meaning | Domain Significance |
|------------------|---------|-------------------|
| VoteRejected[VotingClosed] | Governance gate not satisfied | Election state problem |
| VoteRejected[DuplicateHash] | Integrity constraint violation | Security/integrity problem |
| VoteRejected[IntegrityFailure] | Checksum/hash computation failure | Data integrity problem |

These may eventually become separate named events (VoteRejectedDueToClosure, VoteRejectedDueToDuplicate, etc.) or a single parametrized event. That decision belongs to Round 36 threat modeling and Round 38 ADRs. For now: `VoteRejected` with reason variants is the correct model.

**VoteRejected is elevated from PROVISIONAL to DISCOVERED** — the rejection is a genuine domain fact. Reason parametrization is a design refinement, not an event existence question.

---

### Vote Command Summary

| Command | Invariants Evaluated | Outcome Events |
|---------|---------------------|---------------|
| CastVote | VO-1, VO-2, VO-3, VO-4 + governance gate | VoteRecorded — or — VoteRejected[reason] |

**Anonymity constraint on CastVote:** The command must contain ballot selections and supporting integrity data. It must not carry voter identity in any form. The aggregate constructs the anonymous vote record from ballot content alone. Voter identity is used only to evaluate eligibility preconditions before the command reaches the aggregate root, and is never stored within the Vote aggregate boundary.

---

## Aggregate 3 — GovernanceState

### Step 1: Actors

| Actor | Role |
|-------|------|
| Authorized Governance Officer | Holds authority to transition election lifecycle states |

**Governance caution:** D35, D37, ADH-1 remain unresolved. Who specifically holds authority to issue each command depends on ADH-1 resolution. Commands are discovered here; actor authorization is governance-dependent.

---

### Step 2: Actor Intents

| Actor | Intent |
|-------|--------|
| Officer | "I intend to advance the election to the next constitutional state" |
| Officer | "I intend to suspend the election temporarily" |
| Officer | "I intend to lift the suspension on the election" |

---

### Step 3: Command Candidates

| Command | Actor | Intent Captured |
|---------|-------|----------------|
| **TransitionGovernanceState** | Officer | "Advance to next constitutional lifecycle state" |
| **SuspendGovernance** | Officer | "Impose suspension overlay on active state" |
| **ResumeGovernance** | Officer | "Lift suspension; restore active state" |

**Note on `TransitionGovernanceState`:** This is intentionally generic. The target state is a parameter, not a separate command per transition, because the aggregate invariant (CG-1 determinism, CG-2 preconditions) governs what transitions are valid — not the command name.

---

### Step 4: Aggregate Decision Chain

**TransitionGovernanceState:**
```
Officer intends to advance election state
    ↓
Aggregate evaluates:
  CG-1: Is this a deterministic transition? (no ambiguous paths)
  CG-2: Are all preconditions satisfied?
  (ADH-1 deferred: who can invoke this is governance-dependent)
    ↓
Outcome:
  GovernanceTransitionCompleted
```

**SuspendGovernance:**
```
Officer intends to impose suspension
    ↓
Aggregate evaluates:
  CG-3 (provisional): Does suspension overlay contradict base state?
  (ADH-1 deferred: who can suspend is governance-dependent)
  (D35 deferred: what suspension means downstream is unresolved)
    ↓
Outcome:
  GovernanceSuspended
```

**ResumeGovernance:**
```
Officer intends to lift suspension
    ↓
Aggregate evaluates:
  Is election currently suspended? (precondition)
  (ADH-1 deferred)
  (D37 deferred: what resumption enforces is unresolved)
    ↓
Outcome:
  GovernanceResumed
```

---

### GovernanceState Command Summary

| Command | Invariants Evaluated | Outcome Event | Governance Debt |
|---------|---------------------|---------------|----------------|
| TransitionGovernanceState | CG-1, CG-2 | GovernanceTransitionCompleted | ADH-1 (actor authorization) |
| SuspendGovernance | CG-3 (provisional) | GovernanceSuspended | ADH-1, D35 |
| ResumeGovernance | Precondition: SUSPENDED | GovernanceResumed | ADH-1, D37 |

---

## Aggregate 4 — RoleAssignment (Candidate)

### Governance Note

RoleAssignment is a candidate aggregate. Command discovery is exploratory. Commands identified here are candidates until aggregate promotion is complete.

### Actor → Command (Condensed)

**Actors:** Governance officer (with delegation authority)

**Candidate Commands:**

| Command | Intent | Blocked By |
|---------|--------|-----------|
| **AssignRole** | Officer assigns a role to a user in election scope | ADC-1 (exclusivity rule), ADH-1 (delegation authority) |
| **RevokeRole** | Officer revokes a role from a user | ADC-2 (temporal validity, revocation authority) |

Neither command may be finalized until ADC-1, ADC-2, and ADH-1 are resolved.

---

## Aggregate 5 — ReplaySession (Candidate)

### Governance Note

ReplaySession is a candidate aggregate. D36 (invocation authority) and ADGR-1 (governance authority) are unresolved.

### Actor → Command (Condensed)

**Actors:** Authorized governance actor (authority class unresolved by D36)

**Candidate Commands:**

| Command | Intent | Blocked By |
|---------|--------|-----------|
| **InvokeReplay** | Governance actor initiates evidence replay | D36 (invocation authority), ADGR-1 |
| **CertifyReplay** | Governance actor certifies replay outcome | D35, D37 (certification binds legitimacy determination) |

Neither command may be finalized until D35, D36, D37, and ADGR-1 are resolved.

---

## Provisional Event Resolutions

This round resolves both provisional events from Round 34A:

### VerificationRequested — RESOLVED

**New classification: DISCOVERED**

`RequestVerification` command (actor: Participant) produces `VerificationRequested` (lifecycle: → PENDING). The PENDING state is created by actor intent. The domain cares that a verification request exists because it creates an obligation for the officer. This is a completed business fact.

---

### VoteRejected — RESOLVED

**New classification: DISCOVERED (with reason variants)**

`CastVote` is the single command. The aggregate evaluates four invariants plus governance gate and duplicate check. Multiple rejection outcomes are possible. Reason variants (VotingClosed, DuplicateHash, IntegrityFailure) are genuine domain distinctions, but whether they become separate events or a single parametrized event is a Round 36/38 decision. The event `VoteRejected` exists as a domain fact.

---

## Complete Command Catalog

### Discovered Commands

| Command | Aggregate | Actor | Produces |
|---------|-----------|-------|---------|
| RequestVerification | Verification | Participant | VerificationRequested |
| GrantVerification | Verification | Officer | VerificationGranted |
| RevokeVerification | Verification | Officer | VerificationRevoked |
| CastVote | Vote | Eligible Voter | VoteRecorded — or — VoteRejected[reason] |
| TransitionGovernanceState | GovernanceState | Officer | GovernanceTransitionCompleted |
| SuspendGovernance | GovernanceState | Officer | GovernanceSuspended |
| ResumeGovernance | GovernanceState | Officer | GovernanceResumed |

### Candidate Commands (Not Yet Approved)

| Command | Aggregate | Blocked By |
|---------|-----------|-----------|
| AssignRole | RoleAssignment | ADC-1, ADC-2, ADH-1 |
| RevokeRole | RoleAssignment | ADC-2, ADH-1 |
| InvokeReplay | ReplaySession | D36, ADGR-1 |
| CertifyReplay | ReplaySession | D35, D36, D37, ADGR-1 |

---

## Updated Event Catalog (Round 34A + 34B Combined)

### Fully Discovered Events (incorporating 34B resolutions)

| Event | Aggregate | Produced By |
|-------|-----------|------------|
| VerificationRequested | Verification | RequestVerification |
| VerificationGranted | Verification | GrantVerification |
| VerificationRevoked | Verification | RevokeVerification |
| VoteRecorded | Vote | CastVote |
| VoteRejected[reason] | Vote | CastVote |
| GovernanceTransitionCompleted | GovernanceState | TransitionGovernanceState |
| GovernanceSuspended | GovernanceState | SuspendGovernance |
| GovernanceResumed | GovernanceState | ResumeGovernance |

---

## Governance Observation — Anonymity Constraint on CastVote

The `CastVote` command chain enforces VO-1 at the application layer boundary:

```
Actor carries voter identity (for eligibility check)
    ↓
Eligibility context evaluates eligibility (voter identity used here)
    ↓
CastVote command is issued (ballot content only — no voter identity)
    ↓
Vote aggregate records VoteRecorded (no voter identity ever stored)
```

Voter identity is used before the command boundary. It does not cross the aggregate boundary. This is the constitutional enforcement point for VO-1 at the command layer.

This observation should inform Round 34C aggregate interaction analysis: Eligibility and Vote interact at the command boundary, not through shared state.

---

## ARB Review

**Status: APPROVED — See ARB Formal Decision at top of document.**

Round 34C Aggregate Interaction Analysis is authorized to begin.

**Round 34C Primary Question:**

```
How do contexts collaborate without violating the boundaries
that discovery worked so hard to establish?
```

**Key interaction to analyze first:**

```
Eligibility evaluates voter identity
    ↓
Identity stops at the Vote aggregate boundary
    ↓
CastVote carries ballot content only
```

The architecture now has a coherent chain:

```
Decision Owner
    ↓
Invariant
    ↓
Aggregate
    ↓
Command
    ↓
Event
```

Round 34C determines how those chains interact across bounded contexts.
