# Round 35C — Event Flow Analysis

## ARB Formal Decision

```
Round 35C

APPROVED WITH 2 OBSERVATIONS

Event Flow Quality:       EXCELLENT
Boundary Preservation:    EXCELLENT
DDD Discipline:           VERY HIGH
Governance Discipline:    VERY HIGH

Confidence: VERY HIGH
```

**Key achievements:**
- OBS-35B-1 resolved: VerificationGranted → Eligibility is evaluation-time only
- OBS-35C-1 resolved: VerificationRevoked → Eligibility is evaluation-time only (revocation protection comes from BR-1, not event subscription)
- OBS-34C-2 resolved: Eligibility confirmed as stateless evaluator — does not accumulate knowledge, does not maintain derived state, evaluates authoritative sources on demand — God Context risk does not materialize
- Emergent architectural property identified: Pull Over Push — contexts publish authority; consumers evaluate on demand

**Observations:**

**OBS-35D-1 — Authoritative Source Catalog (Round 35D):**
Round 35C repeatedly references Verification aggregate, VotingAuthorized, enrollment, and GovernanceState as authoritative sources. Round 35D must explicitly catalogue which context owns which authority. This will strengthen choreography analysis.

**BR-5 scope correction:** BR-5 should be scoped to currently discovered contexts, not stated as a universal prohibition. Results/Tallying (D39 unresolved) may legitimately maintain derived state. Applied below.

**VoteRejected[EligibilityRevoked]:** Classification remains CANDIDATE reason variant. The name "EligibilityRevoked" is not the correct business fact — "Eligibility evaluation failed" is more precise. The rejection taxonomy belongs in a future ADR.

```
Governance Status:
Round 35C  CLOSED
Round 35D  AUTHORIZED
```

---

**Date:** 2026-06-08

**Phase:** Event Flow Analysis

**Type:** Design Artifact

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 34A Domain Event Discovery (APPROVED)
- Round 34B Command Discovery (APPROVED)
- Round 34C Aggregate Interaction Analysis (APPROVED)
- Round 35A Process Discovery (APPROVED)
- Round 35B Command Flow Analysis (APPROVED)

**Purpose:** Analyze how approved domain events propagate meaning through the system while preserving bounded-context ownership.

**Central Question:**

```
How do events influence downstream contexts
without violating their boundaries?
```

**Primary Investigation Items:**
- OBS-35B-1: VerificationGranted → Eligibility propagation semantics
- OBS-35C-1: VerificationRevoked → Eligibility propagation semantics

**Governing Method (per ARB instruction):**

For every approved event:
1. Who observes it?
2. Why do they observe it?
3. What domain meaning changes?
4. Is the observation immediate, eventual, or evaluation-time only?
5. Does ownership remain intact?

---

## Scope

**OUT OF SCOPE:**
- Event broker design, topic naming, queue configuration
- Subscriber registration and event handler implementation
- Event sourcing decisions
- Saga orchestration

---

## Timing Classification

Before analysis, three observation timing modes are defined:

| Mode | Description | When Applicable |
|------|------------|----------------|
| **Immediate** | Observer is updated within the same unit of work as the event | When the observer must maintain consistent state in the same transaction |
| **Eventual** | Observer is updated asynchronously after the event fires | When fire-and-forget is constitutionally acceptable (Audit) |
| **Evaluation-time** | Observer reads the authoritative source at the moment it needs to decide | When the observer is a stateless evaluator — it has no state to update |

The distinction between **eventual** and **evaluation-time** is important:

```
Eventual → observer maintains its own state; event pushes updates
Evaluation-time → observer has no state; event does not push anything;
                  observer reads on demand
```

---

## Event 1 — VerificationRequested

**1. Who observes it?**
- Trust Attestation Context (internal — state transition to PENDING)
- Audit (external observer)

**2. Why do they observe it?**
- Trust Attestation: the aggregate must record that a request is PENDING — an officer obligation now exists
- Audit: records that a verification request was initiated, when, and by whom

**3. What domain meaning changes?**
- An attestation obligation exists for the officer corps
- The participant's verification status is now PENDING (not unverified, not active)

**4. Timing:**
- Trust Attestation (internal): Immediate — lifecycle transition is atomic within the aggregate
- Audit: Eventual — fire-and-forget; no feedback

**5. Ownership intact?**
Yes. Trust Attestation owns the state; Audit observes without influencing.

---

## Event 2 — VerificationGranted

**1. Who observes it?**
- Trust Attestation Context (internal — state transition PENDING → ACTIVE)
- Eligibility Context (external)
- Audit (external)

**2. Why do they observe it?**
- Trust Attestation: lifecycle changes to ACTIVE; TA-1 uniqueness constraint is now locked
- Eligibility: this voter's verification status has changed — future eligibility evaluations will see ACTIVE
- Audit: records the attestation act

**3. What domain meaning changes?**
- The voter's identity is now attested as trustworthy
- Eligibility can now assess this voter as holding valid verification (for future evaluations)

**4. Timing — Resolution of OBS-35B-1:**

The question was: does Eligibility observe VerificationGranted immediately, eventually, or only at evaluation time?

**Assessment:**

Eligibility is a stateless evaluator established in Round 34C. It has no eligibility-state to maintain between evaluations. When Eligibility evaluates a voter, it reads the current Verification status from the authoritative source at that moment.

This means:
- Eligibility does not maintain a cache of "who is verified"
- Eligibility does not subscribe to VerificationGranted events to update local state
- When Eligibility evaluates, it reads the current status of the Verification aggregate

**Resolution:** Eligibility observes VerificationGranted at **evaluation-time only**.

```
VerificationGranted fires
    ↓
No push to Eligibility
    ↓
Next time Eligibility evaluates this voter:
  reads Verification aggregate status → ACTIVE
  result: verification precondition satisfied
```

**Implications:**
- This resolves OBS-34C-2 (Eligibility God Context risk) — confirmed as a stateless evaluator, not an event subscriber maintaining its own state
- Eligibility is not a context that accumulates knowledge; it is a context that evaluates on demand

**5. Ownership intact?**
Yes. Trust Attestation owns verification status. Eligibility reads it. Audit records it.

---

## Event 3 — VerificationRevoked

**1. Who observes it?**
- Trust Attestation Context (internal — state transition ACTIVE → REVOKED)
- Eligibility Context (external)
- Audit (external)
- Voting flows in progress (behavioral risk — OBS-35A-1)

**2. Why do they observe it?**
- Trust Attestation: lifecycle changes to REVOKED (terminal)
- Eligibility: this voter's verification status has changed — future evaluations will see REVOKED
- Audit: records the revocation with officer attribution
- Voting flows: a voter who was eligible may now be ineligible

**3. What domain meaning changes?**
- The voter's identity is no longer attested as trustworthy
- Any eligibility evaluation after this point must return NOT ELIGIBLE for verification precondition

**4. Timing — Resolution of OBS-35C-1:**

**Assessment:**

The same evaluation-time model applies as for VerificationGranted. Eligibility reads Verification status on demand — it does not maintain a subscriber relationship.

However, VerificationRevoked has an additional behavioral consequence: an eligible voter in a voting flow may have their eligibility change mid-flow.

**Combined with BR-1 (eligibility at command dispatch time):**

```
Scenario:

Step 1: Eligibility evaluated → ELIGIBLE (Verification = ACTIVE)
Step 2: VerificationRevoked fires
Step 3: CastVote arrives — eligibility re-evaluated at dispatch
  → Verification status = REVOKED
  → Eligibility returns NOT ELIGIBLE
  → CastVote rejected (VoteRejected[EligibilityRevoked])
```

This is the constitutionally correct behavior. The voter's verification was revoked before the ballot was cast. The revocation takes effect.

**Resolution:** Eligibility observes VerificationRevoked at **evaluation-time only** — same as VerificationGranted. The protection against revoked-voter voting is provided by BR-1 (eligibility at command dispatch time), not by event-push notification.

**Note on VoteRejected[EligibilityRevoked]:** This adds a new rejection reason variant to VoteRejected that was not modeled in Round 34B. Classification: CANDIDATE reason variant. **Name is provisional** — "EligibilityRevoked" describes a mechanism, not a business fact. The correct business fact is "eligibility evaluation failed." The rejection taxonomy and naming belong in a future ADR. Do not promote to DISCOVERED until the naming is resolved.

**5. Ownership intact?**
Yes. Trust Attestation owns revocation; Eligibility evaluates on demand; Audit records the fact.

---

## Event 4 — VerificationDenied (CANDIDATE)

**Status:** CANDIDATE pending ARB approval of `DenyVerification` command.

**Conditional analysis (if admitted):**

1. Who observes: Trust Attestation (internal — PENDING → DENIED), Audit
2. Why: DENIED is a terminal state; officer has made a deliberate decision; Audit records it
3. Domain meaning: Participant may seek re-verification (new PENDING process); this decision is now attributed and permanent
4. Timing: Internal immediate; Audit eventual
5. Ownership: Trust Attestation owns; Audit observes

---

## Event 5 — VoteRecorded

**1. Who observes it?**
- Vote Context (internal — vote is permanently recorded)
- Audit (external)
- Results/Tallying (external — future, D39 unresolved)

**2. Why do they observe it?**
- Vote Context: the aggregate has fulfilled its constitutional function
- Audit: records the anonymous ballot fact
- Results/Tallying: one valid ballot has been added to the election record

**3. What domain meaning changes?**
- One anonymous ballot is permanently part of the election record
- Election count has changed (observable to Results/Tallying, but the counting mechanism is D39-dependent)

**4. Timing:**
- Vote Context (internal): Immediate — VO-4 atomicity
- Audit: Eventual — fire-and-forget; no feedback required
- Results/Tallying: Deferred — D39 must resolve ownership before timing can be specified

**5. Ownership intact?**
Yes. Vote Context owns the recorded ballot; Audit observes without influencing; Vote aggregate never receives voter identity.

**Anonymity constraint:** Audit observes that a VoteRecorded event occurred. It must not observe which voter cast the ballot. The event payload carries no voter identity (VO-1 permanent constraint).

---

## Event 6 — VoteRejected[reason]

**1. Who observes it?**
- Vote Context (internal — CastVote command was processed; no vote created)
- Audit (external)
- Voter (synchronous feedback — receives rejection reason)

**2. Why do they observe it?**
- Vote Context: the aggregate evaluated invariants or preconditions and refused the ballot
- Audit: records the rejection for accountability (reason category, not voter identity)
- Voter: needs to understand why their ballot was refused

**3. What domain meaning changes?**
- No ballot has been added to the election record
- The rejection reason indicates a specific domain constraint failure

**4. Timing:**
- Voter feedback: Immediate (synchronous — the command caller receives the result)
- Audit: Eventual — fire-and-forget
- No downstream context changes its state based on VoteRejected — it is a terminal failure outcome for that CastVote attempt

**5. Ownership intact?**
Yes. Vote Context owns the rejection decision; Audit observes without influencing.

**Anonymity constraint:** Audit records the reason category, not voter identity.

---

## Event 7 — GovernanceTransitionCompleted

**1. Who observes it?**
- Constitutional Governance Context (internal — lifecycle state changes)
- GovernanceState derives: VotingAuthorized recalculated
- Authorization Context (external — capabilities may change)
- Eligibility (external — VotingAuthorized recalculated; reads on demand)
- Vote (external — VotingAuthorized precondition changes)
- Audit (external)

**2. Why do they observe it?**
- GovernanceState: internal state change is complete; VotingAuthorized derived value is updated
- Authorization: some capabilities are tied to lifecycle state
- Eligibility: uses VotingAuthorized as part of precondition evaluation
- Vote: governance gate (VotingAuthorized) may have changed
- Audit: records the constitutional state change

**3. What domain meaning changes?**
- The election is in a new constitutional state
- VotingAuthorized = true if the new state is VOTING_ACTIVE; false otherwise
- This is the most broadcast event in the model — multiple contexts change their behavior

**4. Timing:**
- GovernanceState (internal): Immediate — lifecycle and VotingAuthorized are computed atomically
- Authorization: Evaluation-time — Authorization reads lifecycle state when it evaluates capabilities
- Eligibility: Evaluation-time — reads VotingAuthorized at command dispatch time (BR-1)
- Vote: Evaluation-time — reads VotingAuthorized at the moment CastVote arrives
- Audit: Eventual — fire-and-forget

**5. Ownership intact?**
Yes. GovernanceState owns the lifecycle decision and VotingAuthorized derivation. Downstream contexts consume the published value without re-computing the governance decision.

---

## Event 8 — GovernanceSuspended

**1. Who observes it?**
- Constitutional Governance Context (internal — SUSPENDED overlay applied)
- GovernanceState derives: VotingAuthorized = false
- Authorization Context (external)
- Vote (external — VotingAuthorized = false immediately blocks new CastVote commands)
- Audit (external)

**2. Why do they observe it?**
- GovernanceState: internal state updated; VotingAuthorized = false derived immediately
- Authorization: capabilities may change under suspension
- Vote: the governance gate is now closed — CastVote commands will receive VoteRejected[VotingClosed]
- Audit: records the suspension act

**3. What domain meaning changes?**
- Election operations are temporarily halted
- No new votes can be accepted until GovernanceResumed

**4. Timing:**
- GovernanceState (internal): Immediate
- Authorization: Evaluation-time
- Vote: Evaluation-time — reads VotingAuthorized at CastVote dispatch; next command after suspension will see VotingAuthorized = false
- Audit: Eventual

**Race condition note (from Round 35B Q6):** A CastVote already in atomic processing when GovernanceSuspended fires completes due to VO-4. The next CastVote after suspension sees VotingAuthorized = false. The window is bounded by VO-4 atomicity.

**5. Ownership intact?**
Yes. GovernanceState owns the suspension decision; Vote reads authorization; neither context owns the other's decision.

---

## Event 9 — GovernanceResumed

**1. Who observes it?**
- Constitutional Governance Context (internal — SUSPENDED overlay removed; base state restored)
- GovernanceState derives: VotingAuthorized recalculated (may return to true if base state was VOTING_ACTIVE)
- Authorization Context (external)
- Vote (external — VotingAuthorized may return to true)
- Eligibility (external — voting window may restore)
- Audit (external)

**2. Why do they observe it?**
- GovernanceState: SUSPENDED overlay removed; base state governance rules restored
- Authorization: capabilities may be restored
- Vote: CastVote commands may be accepted again
- Eligibility: voting precondition may be restored
- Audit: records the resumption act

**3. What domain meaning changes?**
- Election operations are restored to base state
- VoteRejected[VotingClosed] votes cast during suspension may be eligible for re-submission (BR-4)

**4. Timing:**
- GovernanceState (internal): Immediate
- All external contexts: Evaluation-time — read VotingAuthorized on demand at the next evaluation

**5. Ownership intact?**
Yes.

---

## Resolution of OBS-35B-1 and OBS-35C-1

**OBS-35B-1 — VerificationGranted → Eligibility propagation semantics:**

```
RESOLVED: Evaluation-time only.
```

Eligibility does not maintain eligibility state. It does not subscribe to VerificationGranted. It reads Verification aggregate status at the moment of evaluation.

**OBS-35C-1 — VerificationRevoked → Eligibility propagation semantics:**

```
RESOLVED: Evaluation-time only. Same model.
```

Protection against revoked-voter voting is provided by BR-1 (eligibility at command dispatch time), not by event subscription. The evaluation-time model means revocation takes effect on the next eligibility evaluation — which, per BR-1, occurs before any CastVote is dispatched.

---

## Resolution of OBS-34C-2 — Eligibility God Context

The evaluation-time observation model formally resolves the God Context risk raised in Round 34C.

```
Eligibility does not subscribe to any event.

Eligibility does not maintain state.

Eligibility reads from authoritative sources on demand:
    — Verification aggregate status (for TA check)
    — VotingAuthorized derived value (for governance gate)
    — Election enrollment (for participation check)

Eligibility is a stateless precondition evaluator.
```

**OBS-34C-2: RESOLVED.** Eligibility is confirmed as a stateless domain service. It evaluates; it does not accumulate. The God Context risk does not materialize with the evaluation-time model.

---

## Unified Timing Map

| Event | Trust Attestation | Eligibility | Vote | Authorization | Audit | Results/Tallying |
|-------|------------------|-------------|------|--------------|-------|-----------------|
| VerificationRequested | Immediate (internal) | — | — | — | Eventual | — |
| VerificationGranted | Immediate (internal) | Evaluation-time | — | — | Eventual | — |
| VerificationRevoked | Immediate (internal) | Evaluation-time | — | — | Eventual | — |
| VoteRecorded | Immediate (internal) | — | — | — | Eventual | Deferred (D39) |
| VoteRejected[reason] | Immediate (internal) | — | — | — | Eventual | — |
| GovernanceTransitionCompleted | Immediate (internal) | Evaluation-time | Evaluation-time | Evaluation-time | Eventual | — |
| GovernanceSuspended | Immediate (internal) | — | Evaluation-time | Evaluation-time | Eventual | — |
| GovernanceResumed | Immediate (internal) | Evaluation-time | Evaluation-time | Evaluation-time | Eventual | — |

**Key pattern:** Audit always receives events eventually. Domain contexts with no persistent state (Eligibility, Vote preconditions, Authorization capabilities) read authoritative sources at evaluation time. No domain context subscribes to events to maintain its own derived state.

---

## Emergent Architectural Property — Pull Over Push

The evaluation-time observation model reveals an important architectural property of this domain:

```
Constitutional governance contexts
do not push state updates to each other.

They publish authoritative state.
Consumers pull when they need to decide.
```

This is consistent with:
- Decision ownership (each context owns its own decisions)
- Constitutional authority model (no context defers to another mid-decision)
- Bounded-context integrity (no shared mutable state)

This property will be relevant to Round 35D choreography analysis and should inform any future messaging or integration ADR.

---

## New Behavioral Requirement

**BR-5:** Among currently discovered contexts, no context subscribes to events to maintain derived state. Contexts with preconditions read authoritative sources at evaluation time (pull). Audit is the sole event consumer that records facts (eventual fire-and-forget). This is the evaluation-time observation principle for the discovered domain model. **Note:** Future contexts not yet designed (e.g., Results/Tallying, D39 unresolved) may legitimately require derived state — BR-5 does not prohibit this; it describes the currently discovered model.

---

## ARB Review

**Status: APPROVED — See ARB Formal Decision at top of document.**

Round 35D Context Choreography is authorized to begin.

**OBS-35D-1 carried into Round 35D:**
Produce an Authoritative Source Catalog — which context owns which authority? This will ground the choreography analysis in the decision-ownership model established throughout Rounds 29–35C.
