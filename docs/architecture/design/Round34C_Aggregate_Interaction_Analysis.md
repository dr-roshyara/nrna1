# Round 34C — Aggregate Interaction Analysis

## ARB Formal Decision

```
Round 34C

APPROVED WITH 2 IMPORTANT OBSERVATIONS

DDD Quality:            EXCELLENT
Boundary Integrity:     EXCELLENT
Interaction Modeling:   VERY HIGH
Governance Discipline:  VERY HIGH

Confidence: VERY HIGH
```

**Key Achievement:** Contexts collaborate; aggregates do not. This distinction is preserved throughout.

**Architectural Rule Elevated (pending ADR):**
> "No aggregate knows another aggregate's identity." To be formalized in a future Architecture Constraint ADR.

**Observations:**

**OBS-34C-1 — GovernanceState → Vote contract undiscovered:**
The interaction is approved. What exactly Vote consumes from GovernanceState remains unmodeled. Round 35 must investigate: `CurrentElectionPhase`? `VotingWindowStatus`? `VotingAuthorized`? These are not equivalent. Classification: APPROVED INTERACTION — CONTRACT UNDISCOVERED.

**OBS-34C-2 — Monitor Eligibility for God Context drift:**
Eligibility currently evaluates: Verification status, GovernanceState, enrollment, voting window. Round 35 must ask: is Eligibility making eligibility decisions, or becoming an orchestration layer? That distinction determines whether Eligibility remains a stateless evaluator or requires a design boundary correction.

**Missing Interaction (Round 35 investigation item):**
GovernanceState → Verification: Can verification be granted when governance is suspended? Also applies to RoleAssignment once promoted.

**Authorization:** Round 35 Command and Process Design — AUTHORIZED

---

**Date:** 2026-06-08

**Phase:** Aggregate Interaction Analysis

**Type:** Design Artifact

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 32B Design Baseline Consolidation (APPROVED)
- Round 33 Aggregate Boundary Design (APPROVED)
- Round 34A Domain Event Discovery (APPROVED)
- Round 34B Command Discovery (APPROVED)

**Purpose:** Analyze how approved aggregates and bounded contexts interact across boundaries without collapsing the boundaries that discovery established.

**Central Question:**

```
How do contexts collaborate
without violating boundaries?
```

**Governing Principle:** Aggregates do not share state. Contexts collaborate through published events, explicit contracts, or upstream/downstream relationships. Interaction analysis discovers those seams — it does not merge contexts or create shared ownership.

---

## Scope

This document covers cross-aggregate and cross-context interaction patterns only.

**OUT OF SCOPE:**
- Internal aggregate behavior (covered in Round 33)
- Command handling implementation (application layer — Round 35+)
- Event-driven infrastructure (messaging, queues, brokers — Round 39)
- HTTP API design
- Database schema or persistence design

---

## Available Chain (Rounds 33–34B Combined)

The architecture now has a complete chain for each approved aggregate:

```
Decision Owner → Invariant → Aggregate → Command → Event
```

| Aggregate | Decision | Key Commands | Key Events |
|-----------|----------|-------------|-----------|
| Verification | D1 (Is identity trustworthy?) | RequestVerification, GrantVerification, RevokeVerification | VerificationRequested, VerificationGranted, VerificationRevoked |
| Vote | D5 (Is vote valid and anonymous?) | CastVote | VoteRecorded, VoteRejected[reason] |
| GovernanceState | D4 (What is the valid lifecycle state?) | TransitionGovernanceState, SuspendGovernance, ResumeGovernance | GovernanceTransitionCompleted, GovernanceSuspended, GovernanceResumed |

---

## Interaction 1 — Eligibility → Vote

### Interaction Description

Before `CastVote` may be issued, voter eligibility must be established. The Vote aggregate does not evaluate eligibility — that is the Eligibility context's responsibility (D2).

**Constitutional constraint:** The vote must not link voter to selection (VO-1). This means voter identity must not cross the Vote aggregate boundary.

### Interaction Model

```
Eligible Voter attempts to cast a ballot
    ↓
Eligibility Context
  evaluates voter identity against election enrollment
  evaluates voter identity against GovernanceState (voting active?)
    ↓
  result: ELIGIBLE / NOT ELIGIBLE
    ↓
If ELIGIBLE:
  CastVote issued to Vote aggregate
  Ballot content only — no voter identity
    ↓
  Vote aggregate evaluates VO-1, VO-2, VO-3, VO-4
    ↓
  VoteRecorded (anonymous)
```

### Boundary Rule

```
Eligibility uses voter identity.
CastVote does not carry voter identity.
```

This is the architectural enforcement point for VO-1 at the command boundary. Voter identity is consumed by Eligibility and discarded. The ballot content continues to the Vote aggregate without it.

**Integration Pattern:** Eligibility is a precondition validator, not a co-owner of the Vote. It is queried synchronously before command dispatch. The Vote aggregate does not call Eligibility — it assumes the precondition was satisfied by whoever issued the command.

---

## Interaction 2 — GovernanceState → Vote (Governance Gate)

### Interaction Description

Vote recording requires the election to be in an active voting state. `CastVote` depends on the current `GovernanceState` lifecycle position.

### Interaction Model

```
CastVote arrives at Vote aggregate
    ↓
Aggregate evaluates governance gate:
  Is voting currently active?
    ↓
  Yes: proceed with VO-1/2/3/4 evaluation
  No: VoteRejected[VotingClosed]
```

### Boundary Rule

The Vote aggregate must know the current governance state at the time of `CastVote`. It does not own that state. It reads a published value from the GovernanceState context.

**Integration Pattern:** GovernanceState publishes its current lifecycle state. Vote aggregate reads it as a precondition — not as a dependency on the GovernanceState aggregate instance.

**Governance caution:** D35, D37, ADH-1 affect what lifecycle states allow voting. The pattern is discovered; the exact gate condition may change with governance debt resolution.

**OBS-34C-1:** Contract undiscovered. What exactly Vote consumes is not yet modeled — `CurrentElectionPhase`, `VotingWindowStatus`, and `VotingAuthorized` are not equivalent. Round 35 must investigate the contract. Classification: APPROVED INTERACTION — CONTRACT UNDISCOVERED.

---

## Interaction 3 — GovernanceState → Eligibility (Voting Window)

### Interaction Description

Eligibility evaluation depends on whether voting is open. The Eligibility context reads governance state to determine if the voter's window is active.

### Interaction Model

```
Voter requests to vote
    ↓
Eligibility Context
  checks: is voter enrolled?
  checks: GovernanceState — is voting active?
    ↓
  result: ELIGIBLE (both conditions met)
         NOT ELIGIBLE (voter not enrolled)
         NOT ELIGIBLE (voting not active)
```

### Boundary Rule

Eligibility and GovernanceState are independent contexts. Eligibility reads GovernanceState's published lifecycle position. GovernanceState does not call Eligibility.

**Integration Pattern:** Same as Interaction 2 — GovernanceState publishes lifecycle state as a readable fact. Contexts that depend on it consume the published value.

---

## Interaction 4 — Verification → Eligibility

### Interaction Description

Eligibility depends on whether the voter's identity has been verified. A voter cannot be eligible if their identity has not been attested.

### Interaction Model

```
Eligibility Context evaluates voter
    ↓
reads: VerificationGranted event history for this voter
  (or: reads current Verification status)
    ↓
  If ACTIVE: voter identity is attested → eligible (subject to other conditions)
  If PENDING or REVOKED: voter identity not attested → not eligible
```

### Boundary Rule

Eligibility reads Verification status — it does not re-attest identity. Verification is the single decision owner for D1.

**Integration Pattern:** Eligibility is a downstream consumer of Verification status. When `VerificationGranted` or `VerificationRevoked` occurs, Eligibility's answer about a given voter may change.

---

## Interaction 5 — Vote → Audit

### Interaction Description

Audit (D6) records every business fact for accountability. VoteRecorded and VoteRejected are both observable by Audit.

### Interaction Model

```
VoteRecorded
    ↓
Audit receives event
  Records: fact occurred, no voter identity

VoteRejected[reason]
    ↓
Audit receives event
  Records: rejection occurred, reason category, no voter identity
```

### Boundary Rule

Audit is a receiver only. It does not influence vote behavior. Vote aggregate publishes events; Audit consumes them without feedback.

**Note on anonymity:** Audit receives vote facts without voter identity. Audit records are consistent with VO-1 — they demonstrate that a vote occurred but cannot link it to a voter.

---

## Interaction 6 — Verification/GovernanceState → Audit

### Interaction Description

Audit records all governance and trust events for the accountability audit trail.

```
VerificationGranted / VerificationRevoked
GovernanceTransitionCompleted / GovernanceSuspended / GovernanceResumed
    ↓
Audit receives and records each
```

Audit does not validate or judge these events. It archives them.

---

## Interaction 7 — GovernanceState → Verification (Investigation Required)

### Interaction Description

Can verification be granted when governance is suspended?

The answer may be YES (verification is a trust attestation independent of election lifecycle) or NO (governance suspension prevents all domain operations including trust changes). This has not been investigated.

**Assessment:** Interaction exists as a candidate. Investigation is deferred to Round 35. If governance suspension blocks verification grants, GovernanceState becomes an upstream dependency of Verification — which would expand the context dependency map significantly.

The same question will apply to RoleAssignment once promoted from candidate status.

---

## Interaction 8 — GovernanceState → Results/Tallying (Deferred)

### Interaction Description

Results publication is gated on governance state (election must be closed before results are published). D39 (counting state ownership) remains unresolved.

**Assessment:** This interaction exists. Its model cannot be finalized until D39 is resolved. Design is deferred.

---

## Context Dependency Map

```
                    ┌─────────────────────────┐
                    │      GovernanceState      │
                    │  (publishes lifecycle)   │
                    └─────────┬───────────────┘
                              │ publishes to
                    ┌─────────▼───────────────┐
            ┌──────▶│        Eligibility       │◀──────────────┐
            │       │  (evaluates preconditions)│               │
            │       └─────────┬───────────────┘               │
            │                 │ eligibility established         │
            │       ┌─────────▼───────────────┐               │
reads       │       │          Vote            │               │ reads
status      │       │  (enforces VO-1/2/3/4)  │               │ status
            │       └─────────┬───────────────┘               │
            │                 │ VoteRecorded                   │
            │       ┌─────────▼───────────────┐               │
            │       │          Audit           │               │
            │       │  (receives all events)  │◀─VerificationGranted/Revoked
            │       └─────────────────────────┘   GovernanceTransitionCompleted
            │
     ┌──────┴─────────────────┐
     │       Verification     │
     │  (owns D1: identity    │
     │   trustworthiness)     │
     └────────────────────────┘
```

---

## Cross-Context Integration Patterns Discovered

### Pattern 1: Published State

GovernanceState publishes its current lifecycle position. Multiple contexts (Eligibility, Vote, future Results) read this value without calling into the GovernanceState aggregate.

- Producer: GovernanceState
- Consumers: Eligibility, Vote (as precondition gate)
- Nature: Read-only, no feedback

### Pattern 2: Event Observation

Audit observes events published by other aggregates. No direct call, no shared state.

- Producers: Verification, Vote, GovernanceState
- Consumer: Audit
- Nature: Fire-and-forget, no feedback

### Pattern 3: Precondition Evaluation

Eligibility evaluates multiple upstream values (Verification status + GovernanceState) before command dispatch. The Vote aggregate does not re-evaluate these — it assumes they were checked by the command issuer.

- Upstream: Verification (status), GovernanceState (lifecycle position)
- Evaluator: Eligibility Context
- Result: ELIGIBLE / NOT ELIGIBLE (binary, consumed by command dispatcher, not stored in Vote)

### Pattern 4: Constitutional Boundary (VO-1 Enforcement)

Voter identity is consumed at the Eligibility boundary. It does not travel into the CastVote command or the Vote aggregate. This is not a pattern in the integration-message sense — it is a constitutional design constraint that shapes how all patterns are implemented.

---

## Boundary Integrity Observations

### Observation 0 — Eligibility God Context Risk (OBS-34C-2)

Eligibility currently evaluates four inputs: Verification status, GovernanceState lifecycle, voter enrollment, and voting window. This is acceptable for a precondition evaluator. However, the risk of drift into an orchestration layer is real.

Round 35 must explicitly ask: is Eligibility making eligibility decisions, or is it becoming a coordination hub that knows too much? The answer determines whether Eligibility remains a stateless domain service or requires a design boundary correction.

---

### Observation 1 — Eligibility Is Not a Co-Owner of Vote

Eligibility evaluates whether a voter may cast. It does not participate in the act of casting. Once `CastVote` is issued, the Vote aggregate decides the outcome alone. Eligibility has no role in the Vote aggregate's invariant evaluation.

This prevents a common DDD mistake: "eligibility service" that is called inside the aggregate boundary.

### Observation 2 — GovernanceState Is Not Inside Vote

The governance gate in `CastVote` (is voting active?) reads a published value from GovernanceState. GovernanceState is not injected into, called by, or owned by the Vote aggregate.

If GovernanceState transitions mid-vote (edge case), the Vote aggregate's decision is based on the state at the moment the command was evaluated. That race condition belongs to Round 36C threat modeling, not to aggregate design.

### Observation 3 — No Aggregate Knows Another Aggregate's Identity

Aggregates do not hold references to other aggregate instances. Contexts communicate through published events or query-time reads of published state. This preserves consistency boundaries established in Round 33.

---

## Governance Debt Impact on Interactions

| Interaction | Governance Debt | Impact on Model |
|------------|----------------|----------------|
| SuspendGovernance affects CastVote | ADH-1, D35 | Governance gate condition may change |
| Eligibility reads Verification | None | Stable |
| Eligibility reads GovernanceState | ADH-1 (authority to transition) | Gate condition stable; authority to create condition is governance-dependent |
| ReplaySession invocation authority | D36, ADGR-1 | Interaction pattern with GovernanceState and Audit deferred until resolution |
| Results publication gate | D39 | Deferred |

---

## ARB Review

**Status: APPROVED — See ARB Formal Decision at top of document.**

Round 35 Command and Process Design is authorized to begin.

**Open Items Carried Into Round 35:**

1. OBS-34C-1: GovernanceState → Vote contract. Discover what published value Vote actually consumes.
2. OBS-34C-2: Eligibility scope review. Is it a precondition evaluator or an emerging orchestrator?
3. GovernanceState → Verification interaction: Can verification be granted during governance suspension?

**Architecture Constant Now Established:**

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
    ↓
Interaction
```

The architecture is no longer a collection of bounded contexts. It is a coherent constitutional governance model with explicit ownership, behavior, and collaboration rules.
