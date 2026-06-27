# Round 34A — Domain Event Discovery

## ARB Formal Decision

```
Round 34A

APPROVED WITH MINOR OBSERVATIONS

DDD Quality:             VERY HIGH
Governance Discipline:   VERY HIGH
Event Discovery Quality: HIGH
Design Contamination:    LOW

Confidence: HIGH
```

**Observations (non-blocking):**

1. `VerificationRequested` — reclassified from "rejected" to "Requires Investigation." PENDING is a genuine lifecycle state; a request may be a completed business fact. Investigate during Round 34B command analysis.

2. `VoteRejected` — marked PROVISIONAL. Rejection reason categories (duplicate hash, integrity failure, voting not active) may represent distinct semantic facts. Refine during Round 34B.

**Architecture Constraint Elevation Pending:**
> "No voter identity may appear in any Vote aggregate event" — to be elevated from Round 34A rule to permanent Architecture Constraint in a future ADR.

**Authorization:** Round 34B Command Discovery — AUTHORIZED

---

**Date:** 2026-06-08

**Phase:** Domain Event Discovery

**Type:** Design Artifact

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 32B Design Baseline Consolidation (APPROVED)
- Round 32C Design Governance Addendum (APPROVED)
- Round 33 Aggregate Boundary Design (APPROVED)

**Purpose:** Discover domain events that represent completed business facts within approved aggregate lifecycles. Domain events are discovered from aggregate behavior, not invented to satisfy integration or messaging needs.

**Governing Rule:** A domain event must satisfy all three conditions:
1. A business fact occurred.
2. The fact is meaningful to the domain.
3. The fact would remain meaningful even if all technology changed tomorrow.

---

## Scope

This document covers domain event discovery only.

**OUT OF SCOPE:**
- Commands (Round 34B)
- Aggregate interaction patterns (Round 34C)
- Event handlers, subscribers, messaging infrastructure
- Event sourcing decisions
- Kafka topics, queues, or broker configuration

**Source aggregates:**
- Verification (APPROVED AGGREGATE DESIGN)
- Vote (APPROVED AGGREGATE DESIGN)
- GovernanceState (APPROVED AGGREGATE PATTERN — Governance Debt Active)
- RoleAssignment (AGGREGATE CANDIDATE)
- ReplaySession (AGGREGATE CANDIDATE)

---

## Discovery Method

For each aggregate, five questions are applied in sequence:

```
Step 1: What decision does the aggregate own?
Step 2: What business facts can occur within its lifecycle?
Step 3: Which facts change the aggregate's lifecycle state?
Step 4: Which facts are externally observable?
Step 5: Which facts deserve a domain event?
```

Not every state change deserves an event. Not every event deserves publication. Discovery precedes both decisions.

---

## Aggregate 1 — Verification

### Step 1: Decision Ownership

**D1 — "Is this identity trustworthy?"**

Owned by Trust Attestation Context. Verification is the aggregate root protecting this decision.

---

### Step 2: Business Facts That Can Occur

| Business Fact | Description |
|--------------|-------------|
| An identity is submitted for verification | A participant requests attestation by an officer |
| An officer grants verification | Identity is attested as trustworthy; active status established |
| An officer revokes verification | Active verification withdrawn; revocation attributed to officer |

---

### Step 3: Facts That Change Lifecycle State

Verification lifecycle: `PENDING → ACTIVE → REVOKED`

| Fact | Lifecycle Change |
|------|----------------|
| Officer grants verification | PENDING → ACTIVE |
| Officer revokes verification | ACTIVE → REVOKED |

Note: REVOKED is terminal (TA-3 append-only). No reversal exists.

---

### Step 4: Facts That Are Externally Observable

Both Eligibility and Authorization downstream contexts read verification status. A change in Verification status is directly observable by those contexts — not through coupling, but through the published state of the aggregate.

| Fact | Who Observes |
|------|-------------|
| Verification granted | Eligibility (reads active status), Authorization (reads active status) |
| Verification revoked | Eligibility (eligibility may change), Authorization (capabilities may change) |

---

### Step 5: Domain Events

| Event Name | Classification | Condition Test |
|-----------|---------------|---------------|
| **VerificationGranted** | DISCOVERED | ✅ Business fact (officer attested identity) ✅ Meaningful to domain (changes eligibility surface) ✅ Technology-independent (would exist in paper-based system) |
| **VerificationRevoked** | DISCOVERED | ✅ Business fact (officer withdrew trust) ✅ Meaningful to domain (downstream access changes) ✅ Technology-independent (would exist in paper-based system) |

**Events Requiring Further Investigation:**
- VerificationRequested — not rejected outright; investigation required. PENDING is a genuine lifecycle state in Round 33. A request may be a completed business fact if the domain cares that the verification request exists independently of whether it was granted. Compare: MembershipApplicationSubmitted, AppealFiled. Resolution deferred to Round 34B command analysis.

**Events Not Included:**
- VerificationPersisted / VerificationSaved — technical events, not domain events

### Domain Event Definitions

**VerificationGranted**
- Trigger: Officer completes attestation of identity
- Invariants satisfied at time of event: TA-1 (uniqueness confirmed), TA-2 (attribution recorded), TA-3 (append-only)
- Externally meaningful: YES — downstream contexts read verification status

**VerificationRevoked**
- Trigger: Officer withdraws trust attestation with attribution
- Invariants satisfied at time of event: TA-2 (revocation attributed), TA-3 (append-only)
- Externally meaningful: YES — changes downstream eligibility and authorization surface

---

## Aggregate 2 — Vote

### Step 1: Decision Ownership

**D5 — "Is this vote valid and anonymous?"**

Owned by Voting Context. Vote is the aggregate root protecting this decision.

**Constitutional reminder:** VO-1 is not a security property to protect — it is a design constraint to never create. Any domain event from this aggregate must not contain voter-linkage information.

---

### Step 2: Business Facts That Can Occur

| Business Fact | Description |
|--------------|-------------|
| A ballot is submitted and accepted | Vote recorded with anonymity, integrity, and receipt hash |
| A ballot is submitted and rejected | Submission failed a validity constraint (duplicate, integrity failure) |

**Business facts excluded by VO-1:**
- Any fact that would associate the vote with the voter cannot be a domain event

---

### Step 3: Facts That Change Lifecycle State

Vote lifecycle: Vote is created atomically (VO-4). There is no post-creation update.

| Fact | Lifecycle Change |
|------|----------------|
| Valid ballot recorded | Vote is created (VO-1, VO-2, VO-3, VO-4 all satisfied atomically) |
| Invalid ballot rejected | Vote is not created |

---

### Step 4: Facts That Are Externally Observable

| Fact | Who Observes |
|------|-------------|
| Vote recorded | Audit (D6 — fire-and-forget), Results/Tallying (D39 — pending ownership resolution) |
| Vote rejected | Audit (D6 — for accountability), Voting workflow (returns result to participant) |

**Note on Results/Tallying:** D39 remains unresolved. The VoteRecorded event exists as a domain fact independent of how Results/Tallying ultimately processes it.

---

### Step 5: Domain Events

| Event Name | Classification | Condition Test |
|-----------|---------------|---------------|
| **VoteRecorded** | DISCOVERED | ✅ Business fact (constitutional act of casting a vote completed) ✅ Meaningful to domain (one valid ballot is now permanently part of the election record) ✅ Technology-independent (exists in paper-based systems as the act of submitting a ballot) |
| **VoteRejected** | PROVISIONAL | ✅ Business fact (attempted cast failed a domain constraint) ✅ Meaningful to domain ✅ Technology-independent — but rejection reasons (duplicate hash, integrity failure, voting not active) may represent distinct semantic categories. Requires refinement during Round 34B command analysis. |

**Events Not Included:**
- VoteHashGenerated — technical implementation detail of recording
- ReceiptHashCreated — technical; receipt hash is an element of VoteRecorded, not a separate event
- VoteSaved / VoteRowInserted — persistence events, not domain events

### Domain Event Definitions

**VoteRecorded**
- Trigger: All ballot validity constraints satisfied; vote permanently recorded
- Invariants satisfied: VO-1 (no voter linkage), VO-2 (checksum computed), VO-3 (receipt hash stored), VO-4 (atomic)
- Externally meaningful: YES
- **Anonymity constraint on event payload:** VoteRecorded must not carry voter identity in any form. If the event carries a receipt hash, that hash was generated at recording time and cannot be reversed to identify a voter.

**VoteRejected**
- Trigger: Submission failed a domain validity constraint (duplicate vote_hash, integrity failure, voting not active)
- Externally meaningful: YES (for audit and accountability)
- **Anonymity constraint on event payload:** Same as VoteRecorded — no voter identity may appear.

---

## Aggregate 3 — GovernanceState

### Step 1: Decision Ownership

**D4 — "What is the valid election lifecycle state? Can this transition be made?"**

Owned by Constitutional Governance Context. GovernanceState is the aggregate root.

**Governance caution:** D35, D37, and ADH-1 remain unresolved. Events in this aggregate can be discovered. Their behavioral semantics in response to resolution of D35/D37/ADH-1 must not be finalized here.

---

### Step 2: Business Facts That Can Occur

| Business Fact | Description |
|--------------|-------------|
| A lifecycle state transition is authorized and completed | Election moves from one constitutional state to another |
| A suspension overlay is imposed on an active election | Active state temporarily modified without destroying base state |
| A suspension overlay is lifted | Active state restored from suspension |

---

### Step 3: Facts That Change Lifecycle State

Governance lifecycle includes (provisional state list — ADR-GOV-STATE-001 pending):

| Fact | Lifecycle Change |
|------|----------------|
| Transition authorized and executed | State moves (e.g., SETUP → VOTING_ACTIVE) |
| Suspension overlay imposed | Active state overlaid with SUSPENDED |
| Suspension overlay lifted | SUSPENDED overlay removed; base state restored |

---

### Step 4: Facts That Are Externally Observable

| Fact | Who Observes |
|------|-------------|
| State transition completed | Authorization (capabilities change), Voting (voting_active gate), Results (publication gate), Audit |
| Suspension imposed | Authorization (capabilities change), Voting (voting blocked) |
| Suspension lifted | Authorization, Voting (voting may resume) |

---

### Step 5: Domain Events

| Event Name | Classification | Condition Test |
|-----------|---------------|---------------|
| **GovernanceTransitionCompleted** | DISCOVERED | ✅ Business fact (constitutional lifecycle state changed) ✅ Meaningful to domain (multiple contexts depend on lifecycle state) ✅ Technology-independent (parliamentary procedure equivalent exists) |
| **GovernanceSuspended** | DISCOVERED | ✅ Business fact (officer imposed suspension overlay) ✅ Meaningful to domain (election activity halted) ✅ Technology-independent (constitutional suspension exists in governance theory) |
| **GovernanceResumed** | DISCOVERED | ✅ Business fact (suspension overlay lifted; active state restored) ✅ Meaningful to domain (election activity may resume) ✅ Technology-independent (same rationale as GovernanceSuspended) |

**Events Not Included:**
- GovernanceStateUpdated — technical, not a domain fact
- LifecycleRowUpdated — persistence event

### Domain Event Definitions

**GovernanceTransitionCompleted**
- Trigger: Authorized state transition executed with all preconditions satisfied (CG-2)
- Invariants satisfied: CG-1 (determinism), CG-2 (preconditions)
- Externally meaningful: YES — affects Authorization, Voting, Results
- **Governance caution:** Semantics of transitions to states adjacent to EXPIRED status depend on D35/D37 resolution. The event exists; what happens when it is received may vary by resolution.

**GovernanceSuspended**
- Trigger: Officer imposes suspension overlay on active election state
- Invariants satisfied: CG-3 provisional (overlay does not contradict base state)
- Externally meaningful: YES
- **Governance caution:** Who may impose suspension depends on ADH-1 resolution. The event exists; the authority to trigger it remains governance-dependent.

**GovernanceResumed**
- Trigger: Suspension overlay lifted; base active state restored
- Externally meaningful: YES
- **Governance caution:** Same ADH-1 dependency as GovernanceSuspended.

---

## Aggregate 4 — RoleAssignment (Candidate)

### Governance Note

RoleAssignment is a candidate aggregate. Domain event discovery is exploratory. Events identified here are candidates, not approved events, until aggregate promotion is complete.

### Steps 1–5 (Condensed)

**Decision:** D3 (Authorization — indirectly; role assignment enables authorization decisions)

**Business Facts:**
- A role is assigned to a user within an election scope
- A role is revoked from a user

**Candidate Events:**

| Event Name | Classification | Notes |
|-----------|---------------|-------|
| **RoleAssigned** | CANDIDATE | ADC-1 (exclusivity) may affect whether this event carries exclusion information |
| **RoleRevoked** | CANDIDATE | ADC-2 (temporal validity, revocation authority) affects who may trigger this |

**Pending:** Neither event may be finalized until ADC-1, ADC-2, and ADH-1 are resolved.

---

## Aggregate 5 — ReplaySession (Candidate)

### Governance Note

ReplaySession is a candidate aggregate. D36 (invocation authority) and ADGR-1 (governance authority) are unresolved. Domain event discovery is exploratory only.

### Steps 1–5 (Condensed)

**Decision:** D7 ("Was evidence integrity preserved?")

**Business Facts:**
- A replay session is initiated and completed
- A divergence is detected during replay
- A replay session is certified

**Candidate Events:**

| Event Name | Classification | Notes |
|-----------|---------------|-------|
| **ReplayCompleted** | CANDIDATE | Whether completion triggers consequences depends on D35/D37 |
| **DivergenceDetected** | CANDIDATE | High governance sensitivity — consequences unresolved (D35) |
| **ReplayCertified** | CANDIDATE | Whether certification is advisory or binding depends on D35/D37 |

**Pending:** No candidate event may be finalized until ADGR-1, D35, D36, and D37 are resolved.

---

## Zero-Candidate Contexts — Event Assessment

### Eligibility (D2)

Eligibility is a stateless domain service. It computes results; it does not maintain lifecycle state.

**Assessment:** Eligibility does not own domain events. Eligibility evaluation results may be included in the payloads of events owned by other aggregates (e.g., as a pre-condition assertion), but Eligibility does not raise events itself.

---

### Audit (D6)

Audit is a receiver of domain events from other contexts. It records operational facts. It does not raise domain events of its own — it is the destination, not the source.

**Assessment:** No domain events owned by Audit.

---

### Results / Tallying (D39 unresolved)

D39 (counting state meaning) remains unresolved. Results/Tallying decision ownership is provisional.

**Assessment:** Domain event discovery for Results/Tallying is deferred pending D39 investigation. A ResultsPublished event candidate may exist, but its domain ownership (Vote aggregate vs. independent Results context) cannot be determined until D39 is resolved.

---

### Arbitration / Legitimacy (D8)

D35, D36, and D37 remain unresolved. Behavioral semantics of legitimacy determinations are unknown.

**Assessment:** Candidate event LegitimacyDetermined may exist, but cannot be classified or finalized until D35/D36/D37 are resolved. Discovery is deferred.

---

## Domain Event Summary

### Approved Events (Discovered)

| Event | Aggregate | External Observers |
|-------|-----------|-------------------|
| **VerificationGranted** | Verification | Eligibility, Authorization |
| **VerificationRevoked** | Verification | Eligibility, Authorization |
| **VoteRecorded** | Vote | Audit, Results/Tallying (pending D39) |
| **GovernanceTransitionCompleted** | GovernanceState | Authorization, Voting, Results, Audit |
| **GovernanceSuspended** | GovernanceState | Authorization, Voting, Audit |
| **GovernanceResumed** | GovernanceState | Authorization, Voting, Audit |

### Provisional Events — Resolved in Round 34B

| Event | Aggregate | Resolution |
|-------|-----------|-----------|
| **VoteRejected** | Vote | DISCOVERED. One command (CastVote) produces multiple rejection outcomes. Reason variants (VotingClosed, DuplicateHash, IntegrityFailure) are genuine domain distinctions — parametrization decision deferred to Round 36/38. |
| **VerificationRequested** | Verification | DISCOVERED. RequestVerification command (actor: Participant) produces VerificationRequested (lifecycle: → PENDING). Domain cares that request exists because it creates officer obligation. |

### Candidate Events (Not Yet Approved)

| Event | Aggregate | Blocked By |
|-------|-----------|-----------|
| RoleAssigned | RoleAssignment | ADC-1, ADC-2, ADH-1 |
| RoleRevoked | RoleAssignment | ADC-1, ADC-2, ADH-1 |
| ReplayCompleted | ReplaySession | ADGR-1, D35, D37 |
| DivergenceDetected | ReplaySession | D35 |
| ReplayCertified | ReplaySession | D35, D37 |

### Deferred

| Event | Context | Blocked By |
|-------|---------|-----------|
| ResultsPublished | Results/Tallying | D39 |
| LegitimacyDetermined | Arbitration/Legitimacy | D35, D36, D37 |

---

## Domain Event Integrity Rules

Every approved event in this document must satisfy:

1. **No voter identity may appear in any Vote aggregate event** — permanent VO-1 enforcement
2. **No governance-debt assumptions embedded in event payload** — GovernanceState events carry state facts, not consequence prescriptions
3. **No technical data in event names or payloads** — database IDs, row counts, and persistence artifacts are not part of domain events
4. **Externally meaningful** — if no external context cares about a fact, it is an internal state transition, not a domain event

---

## ARB Review

**Status: APPROVED — See ARB Formal Decision at top of document.**

Round 34B Command Discovery is authorized to begin.

**Round 34B Governing Rule:**

```
Commands are requests.
Events are facts.

GrantVerification    →   VerificationGranted
RecordVote           →   VoteRecorded
SuspendGovernance    →   GovernanceSuspended

Never reverse the relationship.
```

