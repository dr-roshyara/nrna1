# Round 35A — Process Discovery

## ARB Formal Decision

```
Round 35A

APPROVED WITH OBSERVATIONS

Process Modeling Quality:    VERY HIGH
DDD Alignment:              VERY HIGH
Governance Discipline:      VERY HIGH
Behavioral Discovery:       HIGH

Confidence: HIGH
```

**Key Achievement:** Processes are discovered from commands, events, actor intent, and interactions — not from workflow engines, UI screens, or implementation flows.

**Observations:**

**OBS-35A-1 — Eligibility Evaluation Timing (Behavioral Risk):**
The race condition between `VerificationRevoked` and `CastVote` is elevated from an open question to a tracked behavioral risk. Round 35B must explicitly analyze the scenario: Eligibility evaluated → Verification revoked → CastVote arrives. What happens?

**OBS-35A-2 — Verification During Suspension Must Be Split:**
The investigation "Can verification be granted during suspension?" is two separate process questions:
- Question A: Is `RequestVerification` permitted during governance suspension?
- Question B: Is `GrantVerification` permitted during governance suspension?
These are different decisions and must be analyzed separately in Round 35B.

**Authorization:** Round 35B Command Flow Analysis — AUTHORIZED

```
Governance Status:
Round 35A  CLOSED
Round 35B  AUTHORIZED
```

---

**Date:** 2026-06-08

**Phase:** Process Discovery

**Type:** Design Artifact

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 33 Aggregate Boundary Design (APPROVED)
- Round 34A Domain Event Discovery (APPROVED)
- Round 34B Command Discovery (APPROVED)
- Round 34C Aggregate Interaction Analysis (APPROVED)

**Purpose:** Discover the business processes that operate across approved aggregates, commands, and events. The focus shifts from structure to behavior: how does the governance process actually execute?

**Central Question:**

```
What business processes exist?
```

**Governing Principle:** Processes are discovered from commands, events, and actor intents established in Rounds 34A/34B. Processes must not introduce new aggregates, new commands, or new events. They map what is already known into behavioral sequences.

---

## Scope

This document covers process discovery only.

**OUT OF SCOPE:**
- Process implementation (handlers, sagas, application services — Round 39+)
- Messaging infrastructure (brokers, queues, topics)
- Scheduling and timing mechanisms
- Error handling and compensation flows (except where governance debt already flags them)

**Source materials:**
- 7 discovered commands (Round 34B)
- 8 discovered events (Round 34A + 34B resolutions)
- 7 approved interactions (Round 34C)
- Active governance debts: D35, D37, ADH-1, D42B, ADC-1/2, ADGR-1

---

## Discovery Method

For each candidate process:

```
Step 1: Who initiates it?
Step 2: What sequence of commands occurs?
Step 3: Which events mark progress or completion?
Step 4: Which contexts participate?
Step 5: What is the completion condition?
Step 6: What governance constraints apply?
```

---

## Process 1 — Verification Process

### Description

A participant's identity is attested by an officer. The process begins with a request and ends when verification is either granted or the request is abandoned.

### Process Steps

```
1. Participant initiates:
   RequestVerification
        ↓
   VerificationRequested
   (Verification lifecycle: → PENDING)
        ↓
2. Officer observes pending request and acts:

   Path A — Grant:
   GrantVerification
        ↓
   VerificationGranted
   (Verification lifecycle: PENDING → ACTIVE)

   Path B — No action (process does not complete;
            remains PENDING until officer acts)
```

### Contexts Participating

| Context | Role |
|---------|------|
| Trust Attestation | Owns the process — Verification aggregate |
| Audit | Observes VerificationRequested, VerificationGranted |

### Completion Condition

Current: Process completes when `VerificationGranted` is raised. Process is incomplete if the request remains PENDING.

**Subject to investigation (Round 35B):** If `VerificationDenied` becomes a valid domain event, then `VerificationRequested → VerificationDenied` is also a terminal completion path. The process would have two terminal outcomes: GRANTED and DENIED.

### Governance Constraints

- TA-1: Only one ACTIVE verification per identity (grant must check uniqueness)
- TA-2: Officer identity is attributed at grant time
- TA-3: Append-only — no state can be reversed

### Open Question

**No VerificationDenied event was discovered.** If an officer explicitly declines a request (rather than simply not acting), is that a domain event? Investigation deferred to Round 35B command flow analysis.

---

## Process 2 — Verification Revocation Process

### Description

An officer withdraws trust attestation from a previously verified identity. This is a separate process from the verification grant — it can occur at any time while the verification is ACTIVE.

### Process Steps

```
1. Officer initiates:
   RevokeVerification
        ↓
   VerificationRevoked
   (Verification lifecycle: ACTIVE → REVOKED)
```

### Contexts Participating

| Context | Role |
|---------|------|
| Trust Attestation | Owns the process |
| Eligibility | Observes VerificationRevoked — may change eligibility |
| Audit | Observes VerificationRevoked |

### Completion Condition

Process completes atomically when `VerificationRevoked` is raised.

### Downstream Effect

`VerificationRevoked` may change the voter's eligibility status. This is not a two-phase process — revocation completes in Trust Attestation; Eligibility reads the updated status on the next evaluation.

### Governance Constraints

- TA-2: Revocation must be attributed to the officer
- TA-3: Append-only — revocation cannot be undone

---

## Process 3 — Voting Process

### Description

An eligible voter casts a ballot during an active voting window. This is the core constitutional act of the platform.

### Process Steps

```
Pre-conditions (evaluated outside Vote aggregate):
  Eligibility Context evaluates:
    — Is verification ACTIVE?         (reads Verification status)
    — Is voter enrolled in election?   (reads enrollment)
    — Is voting currently active?      (reads GovernanceState)

If ELIGIBLE:
1. Voter initiates:
   CastVote (ballot content only — no voter identity)
        ↓
   Vote aggregate evaluates:
    VO-1: No voter linkage
    VO-2: Checksum valid
    VO-3: Receipt hash generated
    VO-4: Atomic recording

   Path A — Valid:
   VoteRecorded
   (Vote lifecycle: created and closed — one-shot)

   Path B — Invalid:
   VoteRejected[reason]
```

### Contexts Participating

| Context | Role |
|---------|------|
| Eligibility | Evaluates preconditions — does NOT participate in vote act |
| Voting | Owns the process — Vote aggregate |
| Audit | Observes VoteRecorded, VoteRejected |
| Results/Tallying | Observes VoteRecorded (D39 unresolved — future) |

### Completion Condition

Process completes when `VoteRecorded` is raised. Process fails when `VoteRejected[reason]` is raised.

### Constitutional Constraint

**Identity stops at the command boundary.** Eligibility evaluates the voter's identity; `CastVote` carries ballot content only. This is the architectural enforcement of VO-1.

### Governance Constraint

Voting is only permitted when GovernanceState lifecycle is in the voting-active phase. The governance gate is a precondition, not part of the Vote aggregate's invariant evaluation. (OBS-34C-1: exact contract undiscovered.)

---

## Process 4 — Governance Transition Process

### Description

An authorized officer advances the election lifecycle from one constitutional state to another.

### Process Steps

```
1. Officer initiates:
   TransitionGovernanceState (target state as parameter)
        ↓
   GovernanceState aggregate evaluates:
    CG-1: Is this a deterministic transition?
    CG-2: Are all preconditions satisfied?
        ↓
   Path A — Valid transition:
   GovernanceTransitionCompleted

   Path B — Preconditions not met:
   Transition refused (no event raised; command rejected)
```

### Contexts Participating

| Context | Role |
|---------|------|
| Constitutional Governance | Owns the process — GovernanceState aggregate |
| Authorization | Observes GovernanceTransitionCompleted — capabilities may change |
| Eligibility | Observes GovernanceTransitionCompleted — voting window may change |
| Voting | Observes GovernanceTransitionCompleted — gate may change |
| Audit | Observes GovernanceTransitionCompleted |

### Completion Condition

Process completes when `GovernanceTransitionCompleted` is raised.

### Governance Constraints

- ADH-1 (unresolved): Who is authorized to invoke this process is governance-dependent
- D35 (unresolved): Consequences of specific state transitions may affect downstream behavior
- D37 (unresolved): Legitimacy enforcement semantics for certain transitions

### Note on Command Name

`TransitionGovernanceState` is provisional pending D35/D37/ADH-1 resolution. Specific transition commands (`OpenVoting`, `CloseVoting`, `PublishResults`) may emerge once governance debt is resolved.

---

## Process 5 — Governance Suspension Process

### Description

An authorized officer imposes a temporary suspension overlay on an active election, then later lifts it to restore normal operations.

### Suspension Sub-process

```
1. Officer initiates:
   SuspendGovernance
        ↓
   GovernanceState aggregate evaluates:
    CG-3 (provisional): Overlay does not contradict base state
        ↓
   GovernanceSuspended
   (State: ACTIVE → SUSPENDED overlay)
```

### Resumption Sub-process

```
2. Officer initiates:
   ResumeGovernance
        ↓
   GovernanceState aggregate evaluates:
    Precondition: currently SUSPENDED?
        ↓
   GovernanceResumed
   (State: SUSPENDED overlay removed → base state restored)
```

### Contexts Participating

| Context | Role |
|---------|------|
| Constitutional Governance | Owns the process |
| Authorization | Observes GovernanceSuspended, GovernanceResumed |
| Voting | Observes GovernanceSuspended (voting blocked), GovernanceResumed (voting may resume) |
| Audit | Observes both events |

### Completion Condition

Suspension process completes when `GovernanceSuspended` is raised. Resumption process completes when `GovernanceResumed` is raised.

### Governance Constraints

- ADH-1 (unresolved): Who may suspend and who may resume is governance-dependent
- D35 (unresolved): What suspension means for downstream contexts is unresolved
- D37 (unresolved): What resumption enforces is unresolved

### Open Investigation (OBS-35A-2 — Two Separate Questions)

**Question A — Can `RequestVerification` be issued during governance suspension?**
If YES: The request phase of the Verification Process is independent of GovernanceState.
If NO: GovernanceSuspended gates even the initiation of verification.

**Question B — Can `GrantVerification` be issued during governance suspension?**
If YES: Verification Process operates entirely independently of GovernanceState.
If NO: GovernanceState becomes an upstream dependency of Verification (for grant decisions only).

These are different governance decisions. Question A affects participants. Question B affects officers. Both belong in Round 35B behavioral analysis.

---

## Process 6 — Replay Process (CANDIDATE — Governance-Dependent)

### Description

An authorized governance actor invokes evidence replay to verify that historical audit evidence remains consistent with recorded facts.

**Status:** This process is a candidate only. D36 (invocation authority) and ADGR-1 (governance authority) are unresolved. The process exists conceptually; it cannot be modeled in detail until those debts are resolved.

### Candidate Process Steps

```
InvokeReplay
    ↓
ReplayCompleted
    (or: DivergenceDetected)
    ↓
CertifyReplay
    ↓
ReplayCertified
```

### Governance Constraints Blocking Full Discovery

- D36: Who may invoke replay is unresolved
- ADGR-1: What governance authority replay requires is unresolved
- D35: What divergence detection means for legitimacy is unresolved

**Assessment:** Process discovery deferred pending governance debt resolution.

---

## Process Summary

| Process | Status | Initiating Actor | Key Events |
|---------|--------|-----------------|-----------|
| Verification | DISCOVERED | Participant, then Officer | VerificationRequested → VerificationGranted |
| Verification Revocation | DISCOVERED | Officer | VerificationRevoked |
| Voting | DISCOVERED | Eligible Voter | VoteRecorded (or VoteRejected[reason]) |
| Governance Transition | DISCOVERED (contract provisional) | Officer (ADH-1 pending) | GovernanceTransitionCompleted |
| Governance Suspension | DISCOVERED (semantics provisional) | Officer (ADH-1 pending) | GovernanceSuspended → GovernanceResumed |
| Replay | CANDIDATE | Governance Actor (D36 pending) | ReplayCompleted, DivergenceDetected, ReplayCertified |

---

## Open Questions for Round 35B

The following questions emerge from process discovery and require command flow analysis to answer:

1. **VerificationDenied event:** Does an officer's explicit refusal to grant verification constitute a domain event, or only inaction?

2. **Verification during suspension:** Does GovernanceSuspended prevent new VerificationRequested commands from being accepted?

3. **GovernanceState → Vote contract (OBS-34C-1):** What specific published value does the Voting Process precondition consume from GovernanceState?

4. **Eligibility evaluation timing (OBS-35A-1 — Behavioral Risk):** Is eligibility evaluated once when the voter enters the voting flow, or re-evaluated at each step? The race condition: Eligibility evaluated → Verification revoked → CastVote arrives — what happens? This is now a tracked behavioral risk, not merely an open question.

5. **Suspension mid-vote:** If GovernanceSuspended fires while CastVote is being processed, what happens? This is a threat modeling concern for Round 36C, but the behavioral model should at least acknowledge the scenario.

6. **RequestVerification during suspension (OBS-35A-2A):** Is a participant permitted to request verification when governance is suspended? This is a separate question from whether an officer can grant verification during suspension — they are different governance decisions.

---

## Risk Register Update

| Risk | Classification | Mitigation |
|------|---------------|-----------|
| Eligibility God Context (OBS-34C-2) | ACTIVE | Processes 1–3 show Eligibility reads three independent sources; Round 35B must determine if this is coordination or evaluation |
| GovernanceState → Vote contract (OBS-34C-1) | ACTIVE | Process 3 pre-conditions are known; contract value is not |
| Governance suspension semantics (D35) | ACTIVE | Processes 4 and 5 explicitly defer consequence modeling |
| Verification during suspension | NEW (from Round 34C) | Process 5 flags as open investigation |

---

## ARB Review

**Status: APPROVED — See ARB Formal Decision at top of document.**

Round 35B Command Flow Analysis is authorized to begin.

**Six open questions carried into Round 35B:**

1. Does VerificationDenied exist as a domain event?
2. Is RequestVerification permitted during governance suspension? (OBS-35A-2A)
3. Is GrantVerification permitted during governance suspension? (OBS-35A-2B)
4. What specific value does Vote consume from GovernanceState? (OBS-34C-1)
5. Eligibility evaluation timing — what happens when Verification is revoked between eligibility check and CastVote? (OBS-35A-1 — Behavioral Risk)
6. What happens when GovernanceSuspended fires while CastVote is being processed?
