# Round 35B — Command Flow Analysis

## ARB Formal Decision

```
Round 35B

APPROVED WITH 3 OBSERVATIONS

Command Flow Quality:      VERY HIGH
Behavioral Modeling:       VERY HIGH
Governance Discipline:     VERY HIGH
DDD Alignment:             VERY HIGH

Confidence: HIGH
```

**Key achievements:**
- OBS-34C-1 resolved: `VotingAuthorized` is the correct contract value (GovernanceState owns the decision; Vote consumes the result)
- OBS-35A-1 resolved: Eligibility must be re-evaluated at command dispatch time, not only at session entry (BR-1)
- OBS-35A-2A/2B resolved: Verification processes operate independently of suspension (D1 ≠ D4), subject to ADH-1 resolution (BR-3)
- `VerificationDenied` correctly classified as CANDIDATE pending `DenyVerification` command approval

**Observations:**

**OBS-35B-1 — Verification → Eligibility propagation semantics (Round 35C):**
When `VerificationGranted` fires, when does Eligibility observe it? Immediately? Eventually? At evaluation time only? This is an event-flow question, not an implementation question. Round 35C must investigate.

**OBS-35B-2 — BR-3 notes ADH-1 dependency:**
"Verification operates independently of suspension" is current evidence, not settled architecture. ADH-1 could establish a broader operational freeze. Applied.

**OBS-35B-3 — Structural:** CastVote table split into Path A / Path B to remove duplicate "Downstream Effect" rows. Applied.

```
Governance Status:
Round 35B  CLOSED
Round 35C  AUTHORIZED
```

---

**Date:** 2026-06-08

**Phase:** Command Flow Analysis

**Type:** Design Artifact

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 34A Domain Event Discovery (APPROVED)
- Round 34B Command Discovery (APPROVED)
- Round 34C Aggregate Interaction Analysis (APPROVED)
- Round 35A Process Discovery (APPROVED)

**Purpose:** Analyze the complete flow of each discovered command — from actor intent through aggregate decision to downstream effect. Resolve the six open questions carried from Round 35A.

**Governing Rule (Round 35B):**

```
A command flow must identify:

Actor
    ↓
Intent
    ↓
Command
    ↓
Aggregate Decision
    ↓
Event
    ↓
Downstream Effect
```

---

## Scope

This document covers command flow analysis for all five discovered processes.

**Six Open Questions to resolve:**

1. Does `VerificationDenied` exist as a domain event?
2. Is `RequestVerification` permitted during governance suspension? (OBS-35A-2A)
3. Is `GrantVerification` permitted during governance suspension? (OBS-35A-2B)
4. What specific value does Vote consume from GovernanceState? (OBS-34C-1)
5. Eligibility evaluation timing — race condition between `VerificationRevoked` and `CastVote` (OBS-35A-1)
6. What happens when `GovernanceSuspended` fires while `CastVote` is being processed?

---

## Command Flow 1 — RequestVerification

**Process:** Verification Process

| Step | Content |
|------|---------|
| Actor | Participant |
| Intent | "I wish my identity to be attested" |
| Command | RequestVerification |
| Aggregate Decision | Is this identity already ACTIVE or PENDING? (TA-1 uniqueness pre-check) |
| Event | VerificationRequested |
| Downstream Effect | Verification lifecycle → PENDING; officer now has an unresolved attestation obligation |

**Note:** The downstream effect is not a technical notification — it is a governance obligation. An officer must eventually act on a PENDING verification.

---

## Command Flow 2 — GrantVerification

**Process:** Verification Process (officer path)

| Step | Content |
|------|---------|
| Actor | Verification Officer |
| Intent | "I attest this identity as trustworthy" |
| Command | GrantVerification |
| Aggregate Decision | TA-1 (uniqueness), TA-2 (attribution), TA-3 (append-only) |
| Event | VerificationGranted |
| Downstream Effect | Verification lifecycle → ACTIVE; Eligibility now sees active status for this identity; Audit records the attestation |

---

## Command Flow 3 — VerificationDenied Investigation

**Open Question 1:** Does explicit denial constitute a domain event?

**Analysis:**

Two possible officer behaviors exist when reviewing a pending verification:

| Behavior | Lifecycle Change | Domain Significance |
|----------|-----------------|-------------------|
| Officer does not act | Remains PENDING | No business fact has occurred |
| Officer explicitly declines | PENDING → DENIED | A deliberate governance decision was made |

The distinction matters:

```
Non-action
    =
Process incomplete

Explicit denial
    =
Completed business fact (officer decided this identity should not be attested)
```

**Assessment:** A deliberate refusal to grant verification is a completed business fact. It satisfies all three domain event conditions:
1. Business fact occurred (officer made an attestation decision)
2. Meaningful to domain (affects the participant's ability to seek re-verification; differs from indefinite PENDING)
3. Technology-independent (equivalent in paper-based systems — officer returns the application marked "rejected")

**Resolution:** `VerificationDenied` is a valid domain event candidate.

**Command flow if admitted:**

| Step | Content |
|------|---------|
| Actor | Verification Officer |
| Intent | "I decline to attest this identity" |
| Command | DenyVerification |
| Aggregate Decision | TA-2 (attribution of denial), TA-3 (append-only) |
| Event | VerificationDenied |
| Downstream Effect | Verification lifecycle → DENIED (terminal); participant may request re-verification (new process) |

**Classification:** `VerificationDenied` is CANDIDATE pending ARB approval of the `DenyVerification` command. The process completion condition in Round 35A must be updated if approved.

---

## Command Flow 4 — RevokeVerification

**Process:** Verification Revocation Process

| Step | Content |
|------|---------|
| Actor | Verification Officer |
| Intent | "I withdraw trust from this identity" |
| Command | RevokeVerification |
| Aggregate Decision | TA-2 (attribution), TA-3 (append-only); precondition: verification must be ACTIVE |
| Event | VerificationRevoked |
| Downstream Effect | Verification lifecycle → REVOKED (terminal); Eligibility status for this voter changes; any in-progress voting flow is affected (see OBS-35A-1 below) |

---

## Command Flow 5 — CastVote

**Process:** Voting Process

**Path A — Valid ballot:**

| Step | Content |
|------|---------|
| Actor | Eligible Voter |
| Intent | "I cast my ballot in this election" |
| Command | CastVote (ballot content only — no voter identity) |
| Aggregate Decision | VO-1, VO-2, VO-3, VO-4 satisfied; governance gate: VotingAuthorized = true |
| Event | VoteRecorded |
| Downstream Effect | Vote permanently and anonymously recorded; Audit observes; Results/Tallying future consumer (D39 pending) |

**Path B — Invalid ballot:**

| Step | Content |
|------|---------|
| Actor | Eligible Voter |
| Intent | "I cast my ballot in this election" |
| Command | CastVote |
| Aggregate Decision | One or more of VO-1/2/3/4 failed, or VotingAuthorized = false |
| Event | VoteRejected[reason] |
| Downstream Effect | No vote recorded; Audit observes reason category; voter may reattempt if reason permits and governance allows |

### Resolution of OBS-34C-1 — GovernanceState → Vote Contract

**Open Question 4:** What specific value does Vote consume from GovernanceState?

**Analysis:**

The Voting Process requires one governance precondition: voting must currently be permitted. This is derived from GovernanceState lifecycle position.

Three candidate contract values were identified in Round 34C:
- `CurrentElectionPhase` — full lifecycle state (too much information; Vote shouldn't know all phases)
- `VotingWindowStatus` — open/closed binary (too implementation-specific)
- `VotingAuthorized` — permission flag derived from governance state (correct abstraction level)

**Assessment:** The Vote aggregate's governance gate is asking a single question: "Is voting currently authorized?" It does not need to know that the election is in SETUP, VOTING_ACTIVE, SUSPENDED, or CLOSED. It only needs the authorization decision.

**Contract Resolution:**

```
GovernanceState publishes:
VotingAuthorized = true/false

Derived from lifecycle:
  VOTING_ACTIVE  → VotingAuthorized = true
  Any other state → VotingAuthorized = false
  SUSPENDED overlay → VotingAuthorized = false
```

**Classification:** `VotingAuthorized` is the correct contract value. This resolves OBS-34C-1.

GovernanceState is the sole authority for computing `VotingAuthorized`. Vote reads it as a precondition. The decision to set VotingAuthorized = true belongs to GovernanceState alone.

### Resolution of OBS-35A-1 — Eligibility Timing Race Condition

**Open Question 5:** What happens when Verification is revoked between eligibility check and CastVote?

**Scenario:**

```
Step 1: Eligibility evaluated → ELIGIBLE
Step 2: VerificationRevoked fires
Step 3: CastVote arrives at Vote aggregate
```

**Analysis:**

Two design options exist:

| Option | Approach | Constitutional Risk |
|--------|---------|-------------------|
| A: Point-in-time eligibility | Eligibility check at voting entry; not re-evaluated before CastVote | Revoked voter can complete vote — constitutional integrity risk |
| B: Re-evaluate at command boundary | Eligibility re-checked immediately before CastVote is accepted | Revoked voter is blocked — constitutionally sound |

**Assessment:** Option B is required by the constitutional commitment to election integrity. A voter whose verification was revoked must not be able to cast a vote, even if eligibility was established moments before revocation.

**Resolution:**

```
Eligibility precondition is evaluated
immediately before CastVote is dispatched.

Not once at voting session entry.

If VerificationRevoked fires between
the eligibility check and CastVote dispatch,
CastVote must be preceded by a fresh
eligibility evaluation.
```

This means Eligibility must be callable at command dispatch time, not only at session initiation. This is a behavioral requirement that Round 35C and 35D should carry forward.

**Classification:** OBS-35A-1 resolved. Eligibility must be evaluated at command dispatch time.

### Resolution of Question 6 — Suspension Mid-Vote

**Open Question 6:** What happens when GovernanceSuspended fires while CastVote is being processed?

**Scenario:**

```
CastVote accepted for processing
GovernanceSuspended fires
VotingAuthorized = false
```

**Analysis:**

The Vote aggregate evaluates VotingAuthorized as a precondition before accepting CastVote. If the evaluation occurs before GovernanceSuspended fires, the command proceeds. If it occurs after, the command is rejected.

**Assessment:** This is a genuine race condition at the consistency boundary between GovernanceState and Vote. However:

1. GovernanceState publishes VotingAuthorized as a derived value
2. Vote reads it at a point in time
3. The window between evaluation and completion is atomic within the Vote aggregate

The behavioral risk is real but bounded: a vote that begins processing before suspension takes effect will complete. A vote that begins processing after suspension takes effect will be rejected.

**Resolution:** This race condition is acknowledged and accepted. The Vote aggregate's atomicity guarantee (VO-4) means that once processing begins, it completes consistently. The suspension creates a boundary, not a mid-operation interrupt. This scenario is a Round 36C threat modeling concern, not a command flow defect.

---

## Command Flow 6 — TransitionGovernanceState

**Process:** Governance Transition Process

| Step | Content |
|------|---------|
| Actor | Authorized Governance Officer (ADH-1 pending) |
| Intent | "I advance the election lifecycle to the next constitutional state" |
| Command | TransitionGovernanceState (target state) |
| Aggregate Decision | CG-1 (determinism), CG-2 (preconditions satisfied) |
| Event | GovernanceTransitionCompleted |
| Downstream Effect | VotingAuthorized recalculated; Authorization capabilities change; Eligibility voting window changes; Audit records state change |

**Note on downstream cascades:**

When `GovernanceTransitionCompleted` fires, multiple contexts recalculate their state:

```
GovernanceTransitionCompleted
    ↓
VotingAuthorized recalculated by GovernanceState
    ↓
Eligibility reads new VotingAuthorized
    ↓
Vote gate changes for subsequent CastVote commands
```

This cascade is not a process manager or saga. It is a consequence of published state. Contexts reading GovernanceState do so on-demand when they need the value.

---

## Command Flow 7 — SuspendGovernance

**Process:** Governance Suspension Process

| Step | Content |
|------|---------|
| Actor | Authorized Governance Officer (ADH-1 pending) |
| Intent | "I impose a temporary suspension on this election" |
| Command | SuspendGovernance |
| Aggregate Decision | CG-3 provisional (overlay does not contradict base state); precondition: election not already suspended |
| Event | GovernanceSuspended |
| Downstream Effect | VotingAuthorized = false; Authorization capabilities change; Audit records; in-progress voting accepts current batch per VO-4, subsequent CastVote commands rejected |

### Resolution of OBS-35A-2A and OBS-35A-2B

**Open Question 2:** Is `RequestVerification` permitted during governance suspension?

**Open Question 3:** Is `GrantVerification` permitted during governance suspension?

**Analysis:**

Verification is a trust attestation decision (D1). GovernanceState governs the election lifecycle (D4). These are owned by different decision owners.

The constitutional question is: does suspension of an election lifecycle state impose any restriction on identity attestation?

Examination:

| Argument for restriction | Argument against restriction |
|--------------------------|------------------------------|
| Suspension may indicate legal challenge to all election operations | Trust attestation is about identity, not election activity — it exists before and after elections |
| Operational caution during suspension might warrant freezing all processes | Officers attesting identity may be needed during suspension to resolve eligibility disputes |

**Assessment:**

D1 (Is this identity trustworthy?) and D4 (What is the election lifecycle state?) are separate decision domains. Suspension governs voting activity, not trust attestation. An officer's ability to attest identity is independent of whether the election is currently in a suspended state.

**Resolution:**

```
OBS-35A-2A: RequestVerification during suspension
    → PERMITTED
    Rationale: verification request is a trust attestation act, not a voting act.
    GovernanceState governs voting authorization, not identity attestation.

OBS-35A-2B: GrantVerification during suspension
    → PERMITTED
    Rationale: same. Officers attesting identity must remain able to do so
    during suspension — particularly because eligibility disputes may need
    resolution before suspension is lifted.
```

**Governance caution:** This resolution is based on the boundary between D1 and D4. If ADH-1 resolution establishes that suspension imposes a broader operational freeze across all domain operations, this classification would need to be revisited.

---

## Command Flow 8 — ResumeGovernance

**Process:** Governance Suspension Process (resumption)

| Step | Content |
|------|---------|
| Actor | Authorized Governance Officer (ADH-1 pending) |
| Intent | "I lift the suspension and restore normal election operations" |
| Command | ResumeGovernance |
| Aggregate Decision | Precondition: election currently SUSPENDED; CG-1, D37 pending semantics |
| Event | GovernanceResumed |
| Downstream Effect | VotingAuthorized recalculated (if transition restores VOTING_ACTIVE, VotingAuthorized = true); Eligibility voting window restored; Audit records |

---

## Open Question Resolutions — Summary

| # | Question | Resolution | Classification |
|---|---------|-----------|---------------|
| 1 | VerificationDenied exist? | YES — explicit denial is a completed business fact | CANDIDATE (DenyVerification command) |
| 2 | RequestVerification during suspension? | PERMITTED — D1 and D4 are separate decision domains | RESOLVED |
| 3 | GrantVerification during suspension? | PERMITTED — same rationale | RESOLVED |
| 4 | GovernanceState → Vote contract? | VotingAuthorized (boolean derived from lifecycle) | RESOLVED |
| 5 | Eligibility timing race condition? | Eligibility must be re-evaluated at command dispatch time, not only at session entry | RESOLVED — behavioral requirement |
| 6 | Suspension mid-vote? | Race condition acknowledged and accepted; VO-4 atomicity bounds the window; Round 36C concern | ACKNOWLEDGED |

---

## Downstream Effect Map

| Event | Downstream Effects |
|-------|-------------------|
| VerificationRequested | Officer has unresolved attestation obligation (governance) |
| VerificationGranted | Eligibility: voter status may change; Audit: records |
| VerificationDenied (CANDIDATE) | Participant: may re-request; Audit: records |
| VerificationRevoked | Eligibility: voter status changes; in-progress voting flows re-evaluate; Audit: records |
| VoteRecorded | Audit: records (anonymous); Results/Tallying: future (D39) |
| VoteRejected[reason] | Audit: records reason category; voter: may reattempt if reason permits |
| GovernanceTransitionCompleted | VotingAuthorized: recalculated; Authorization: capabilities change; Eligibility: window changes; Audit: records |
| GovernanceSuspended | VotingAuthorized = false; Authorization: capabilities change; Audit: records |
| GovernanceResumed | VotingAuthorized: recalculated; Eligibility: window restores; Audit: records |

---

## Behavioral Requirements Emerging From Command Flow Analysis

The following behavioral requirements are discovered from command flow analysis. These inform Round 35C and 35D.

**BR-1:** Eligibility must be evaluable at command dispatch time (not only at session entry). Required by OBS-35A-1 resolution.

**BR-2:** GovernanceState must publish `VotingAuthorized` as a derived boolean. Consumers read this value; they do not interpret lifecycle states directly. Required by OBS-34C-1 resolution.

**BR-3:** Verification processes operate independently of election lifecycle suspension based on current evidence (D1 and D4 are separate decision domains). **Subject to ADH-1 resolution** — if ADH-1 establishes that suspension imposes a broader operational freeze across all domain operations, this requirement must be revisited.

**BR-4:** VoteRejected[VotingClosed] may allow voter re-attempt if governance suspension is lifted and resumption restores voting authorization. Round 35C should analyze this downstream effect.

---

## ARB Review

**Status: APPROVED — See ARB Formal Decision at top of document.**

Round 35C Event Flow Analysis is authorized to begin.

**Open item carried into Round 35C:**

OBS-35B-1: When does Eligibility observe `VerificationGranted`? Immediately, eventually, or only at evaluation time? This is the first question Round 35C must address.
