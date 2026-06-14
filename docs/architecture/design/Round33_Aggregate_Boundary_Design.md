# Round 33 — Aggregate Boundary Design

**Date:** 2026-06-08

**Phase:** Aggregate Design

**Type:** Design Artifact

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 32 Design Governance Charter (APPROVED)
- Round 32B Design Baseline Consolidation (APPROVED)
- Round 32C Design Governance Addendum (APPROVED)

**Purpose:** Design aggregate boundaries from decision ownership, invariants, and consistency requirements. Domain events, commands, repositories, and persistence are explicitly deferred to subsequent rounds.

**Governance Rule:** Every boundary decision is traceable to a Round 32B discovery artifact. No boundary is derived from database structure, API shape, or implementation convenience.

---

## ARB Formal Decision

```
Round 33 — APPROVED WITH GOVERNANCE RESERVATIONS

Architecture Quality:   HIGH
DDD Quality:            HIGH
Governance Discipline:  HIGH
Confidence:             HIGH
```

### Aggregate Classification (Final)

**APPROVED AGGREGATE DESIGN**
- Verification (boundary, invariant ownership, consistency boundary, and justification approved)
- Vote (boundary, invariant ownership, consistency boundary, and justification approved)

**APPROVED AGGREGATE PATTERN — Governance Debt Active**
- GovernanceState

**AGGREGATE CANDIDATES**
- RoleAssignment
- ReplaySession

**Classification Note:** "APPROVED AGGREGATE DESIGN" means the boundary, invariant ownership, consistency boundary, and aggregate justification are approved. Domain events, cross-aggregate behavior, interaction semantics, and lifecycle completeness are NOT yet approved — those are the subject of Round 34.

### Authorization
Round 34 is authorized to begin after this document is accepted.

---

## Scope of This Document

This document covers **aggregate boundary design only**.

The following are explicitly OUT OF SCOPE and will be addressed in subsequent rounds:
- Domain events
- Commands
- Query handlers
- Repositories
- Services
- Persistence models
- API contracts
- Read models

---

## Aggregate Design Framework

For each aggregate, the design follows this sequence:

```
Constitutional Requirement
        ↓
Decision Owner (D1–D8)
        ↓
Protected Invariants
        ↓
Consistency Boundary
        ↓
Aggregate Root
        ↓
Internal Entities
        ↓
Value Objects
        ↓
Threat Assessment
        ↓
Governance Status
```

---

## Aggregate 1 — Verification

### Constitutional Requirement Traceability

```
Constitutional Requirement:
    Secret ballot requires verified identity.
    Only trustworthy identities may participate.

Decision Owner:
    D1 — "Is this identity trustworthy?"
    Owner: Trust Attestation Context

Protected Invariants:
    TA-1 — One active Verification per participant per organization
    TA-2 — Revocation must be attributed to an officer
    TA-3 — Verification decisions are append-only

Aggregate:
    Verification
```

### Consistency Boundary

**What must be atomic:**
- Identity verification decision (active → revoked) with officer attribution
- Attestation record creation is atomic with verification decision
- Revocation timestamp and officer identity are recorded as a single operation

**What must be excluded from this aggregate:**
- Eligibility evaluation (stateless; separate context — D2)
- Authorization decisions (separate context — D3)
- Vote recording (separate aggregate — Vote)

**Boundary Justification:**
The consistency boundary protects TA-1 (uniqueness per organization) and TA-2 (attribution). These two invariants require atomicity in the same transaction. TA-3 (append-only) prevents any operation that would modify a committed decision.

### Aggregate Root

**Root:** Verification

All interactions with identity trust decisions pass through the Verification root. No external actor may directly manipulate Attestation without going through Verification.

### Internal Entities

| Entity | Responsibility | Invariant Protected |
|--------|---------------|-------------------|
| **Attestation** | Records how and why an identity was verified; who attested and when | TA-2 (officer attribution); TA-3 (immutable once recorded) |

### Value Objects

| Value Object | Description | Immutable? |
|-------------|-------------|-----------|
| **Trust Level** | Classification of trust (e.g., basic, officer-attested) | Yes |
| **Revocation Reason** | Documented reason for revocation | Yes |
| **Officer Attribution** | Officer identity + timestamp for any decision | Yes |
| **Verification Status** | Current status (active / revoked) | No — changes via lifecycle |

### Lifecycle States

```
PENDING → ACTIVE → REVOKED
```

- PENDING: Verification request submitted, not yet attested
- ACTIVE: Officer has attested; identity is trusted
- REVOKED: Officer has revoked; attribution recorded

No reversal from REVOKED (TA-3 append-only).

### Threat Assessment (Round 32C Section 8)

| Threat | Assessment |
|--------|-----------|
| Authority Abuse | PROTECTED — Only officers may attest or revoke (TA-2); no self-attestation path |
| Privilege Escalation | PROTECTED — Verification root enforces officer attribution; no bypass observed |
| Evidence Tampering | PROTECTED — Attestation is append-only (TA-3); historical records immutable |
| Replay Abuse | LOW RISK — Verification does not participate in replay mechanism |
| Legitimacy Manipulation | LOW RISK — Verification feeds into legitimacy chain but does not determine it |
| Denial of Governance | WATCH — Single verification per participant (TA-1) means revocation is a governance action; revocation abuse is possible |

### Why This Is an Aggregate (Not an Entity or Service)

Verification is an aggregate — not a standalone entity — because:

1. **Consistency invariant TA-1** requires uniqueness enforcement across the entire history of verification decisions for a participant. A plain entity has no mechanism to enforce this uniqueness on its own.
2. **Consistency invariant TA-2** requires that revocation and officer attribution are always recorded together in one atomic operation. Without an aggregate root controlling the operation, partial writes are possible.
3. **Consistency invariant TA-3** requires that once a decision is recorded, it cannot be mutated. An aggregate root is the only mechanism that can enforce this prohibition on all access paths.
4. The Attestation entity inside the aggregate has no independent lifecycle outside the Verification decision — it exists only as part of a verification. This is a defining characteristic of aggregate membership.

A service could evaluate trust, but only an aggregate can enforce the consistency rules that protect trust decisions from corruption.

### Governance Status

**Status:** READY FOR DESIGN APPROVAL

**Active Debts:** None

**ADR Required:**
- ADR-VER-001: Verification lifecycle states (confirm PENDING → ACTIVE → REVOKED sequence)
- ADR-VER-002: Trust Level value object classification (confirm enumeration)

---

## Aggregate 2 — GovernanceState

### Constitutional Requirement Traceability

```
Constitutional Requirement:
    Election lifecycle must be governed by
    constitutional rules.
    State transitions require precondition
    satisfaction.

Decision Owner:
    D4 — "What is the valid election lifecycle state?"
    Owner: Constitutional Governance Context

Protected Invariants:
    CG-1 — Lifecycle state transitions are deterministic
    CG-2 — State transitions require valid preconditions
    CG-3 — Suspension overlays cannot contradict base lifecycle (PROVISIONAL)

Aggregate:
    GovernanceState
```

### Consistency Boundary

**What must be atomic:**
- State transition with precondition validation
- Suspension overlay creation with base state preservation
- Authorization check outcome with state change (no state change without authorized actor)

**What must be excluded from this aggregate:**
- Authorization resolution (separate context — D3, reads governance state)
- Voting operations (separate aggregate — Vote, reads governance state for gate check)
- Legitimacy evaluation (separate context — D8, reads governance decisions)
- Audit logging (separate context — D6, fire-and-forget)

**Boundary Justification:**
CG-1 and CG-2 together require that every state transition is (a) deterministic and (b) precondition-gated in the same atomic operation. CG-3 requires that suspension overlay creation cannot be separated from base state preservation — they must be consistent.

### Aggregate Root

**Root:** GovernanceState

All lifecycle state changes and suspension operations pass through the GovernanceState root. The constitutional rules (ElectionConstitution) are consulted by the root for precondition validation but do not live inside the aggregate — they are an external policy the root applies.

### Internal Entities

| Entity | Responsibility | Invariant Protected |
|--------|---------------|-------------------|
| **SuspensionOverlay** | Records a temporary suspension on active state without destroying base state | CG-3 (overlay cannot contradict base lifecycle) |

### Value Objects

| Value Object | Description | Immutable? |
|-------------|-------------|-----------|
| **LifecycleState** | Current constitutional lifecycle state | No — changes via governed transitions |
| **StateTransition** | Recorded from-state → to-state transition with timestamp | Yes |
| **Precondition** | Constitutional precondition that must be satisfied before transition | Yes |
| **SuspensionReason** | Documented reason for suspension overlay | Yes |

### Lifecycle States (Provisional — confirmed by D4)

Constitutional lifecycle states include at minimum:
- SETUP
- VOTING_ACTIVE
- COUNTING
- RESULTS_PUBLISHED
- SUSPENDED (overlay, not terminal)
- CLOSED

Exact state inventory is a governance documentation question (D4 partially unresolved regarding sequence details). Design may proceed on the pattern; final state inventory requires ADR.

### Threat Assessment (Round 32C Section 8)

| Threat | Assessment |
|--------|-----------|
| Authority Abuse | WATCH — ADH-1 unresolved; authority hierarchy for state transitions not fully established |
| Privilege Escalation | WATCH — CG-2 enforces preconditions; but ADH-1 means delegation rules incomplete |
| Evidence Tampering | PROTECTED — StateTransition value objects are immutable; historical transitions append-only |
| Replay Abuse | MEDIUM RISK — GovernanceState is a target for replay operations; ADGR-1 governs this |
| Legitimacy Manipulation | MEDIUM RISK — D35/D37 unresolved; consequence model for EXPIRED status not established |
| Denial of Governance | WATCH — Suspension overlay could be abused; ADH-1 governs who can impose suspension |

### Why This Is an Aggregate (Not an Entity or Service)

GovernanceState is an aggregate — not a plain lifecycle entity — because:

1. **Consistency invariant CG-2** requires that precondition validation and the state transition are inseparable. A service that reads state then transitions it would create a check-then-act race. Only an aggregate root can enforce the invariant atomically.
2. **Consistency invariant CG-1** (determinism) requires that no external actor can observe a partial state between precondition check and transition completion. Aggregate boundaries enforce this.
3. **CG-3 (provisional)** — the suspension overlay — must not be able to corrupt the base lifecycle. The aggregate root is the single gatekeeper that validates overlay operations against base state rules.
4. The SuspensionOverlay entity has no lifecycle independent of GovernanceState — it exists only as a governed modification to an active election state. This dependency defines aggregate membership.

A state machine service could implement transitions, but only an aggregate root can enforce that transitions and precondition validation are one indivisible operation.

### Governance Status

**Status:** AVAILABLE FOR DESIGN — GOVERNANCE-DEPENDENT ASPECTS DEFERRED

**Active Debts:** ADG-2, ADH-1, D35, D37

**Design May Proceed On:**
- Core lifecycle transition pattern (CG-1, CG-2)
- Suspension overlay pattern (CG-3 provisional)
- Atomic state change boundary

**Design May NOT Finalize:**
- Authority scope for each transition type (blocked by ADH-1)
- Enforcement integration when legitimacy = EXPIRED (blocked by D35, D37)
- Complete lifecycle state inventory (governance documentation required)

**ADR Required:**
- ADR-GOV-STATE-001: Lifecycle state inventory (confirm all states)
- ADR-GOV-STATE-002: Suspension overlay consistency rule (confirm CG-3 provisional → discovered)

---

## Aggregate 3 — Vote

### Constitutional Requirement Traceability

```
Constitutional Requirement:
    Votes must be secret, anonymous,
    and protected against tampering.
    No vote may be linked to the voter
    who cast it.

Decision Owner:
    D5 — "Is this vote valid and anonymous?"
    Owner: Voting Context

Protected Invariants:
    VO-1 — A vote must not be linkable to the voter who cast it
    VO-2 — Recorded vote content must be tamper evident
    VO-3 — Receipt hash is generated and stored at recording time
    VO-4 — Ballot selections are recorded atomically

Aggregate:
    Vote
```

### Consistency Boundary

**What must be atomic:**
- Ballot selection recording with vote hash generation
- Data checksum computation with ballot storage
- Receipt hash generation with vote record creation
- All candidate selections recorded together — no partial ballot

**What must be excluded from this aggregate:**
- Voter identity (VO-1 — no linkage permitted at any layer)
- Eligibility evaluation (separate context — D2)
- Authorization check (separate context — D3)
- Result computation (D39 unresolved — design deferred)
- Verifiability guarantee (D42B — design deferred to Stream C)

**Boundary Justification:**
VO-1 (anonymity) is the most critical invariant in the entire domain model — it is the constitutional foundation of secret ballot. The consistency boundary must ensure that no operation within Vote recording can create a linkage between the vote and the voter. VO-4 (atomicity) reinforces this: a partial ballot would expose selection order, which could enable statistical linkage attacks.

### Aggregate Root

**Root:** Vote

All vote recording operations pass through the Vote root. The Vote root is responsible for generating all cryptographic elements (vote_hash, data_checksum, receipt_hash) as part of a single atomic creation operation.

### Internal Entities

| Entity | Responsibility | Invariant Protected |
|--------|---------------|-------------------|
| **BallotSelection** | Records candidate selections per post | VO-4 (atomicity); VO-1 (no voter linkage in selection data) |

### Value Objects

| Value Object | Description | Immutable? |
|-------------|-------------|-----------|
| **VoteHash** | Uniqueness identifier preventing duplicate votes | Yes |
| **DataChecksum** | SHA-256 integrity protection of ballot content | Yes |
| **ReceiptHash** | Cryptographic element enabling voter to confirm participation | Yes |
| **VotingCode** | Anonymized participation code (not linked to voter identity in votes table) | Yes |

### D42B Placeholder

**Receipt Hash** is a discovered value object within Vote.

**Verifiability Guarantee** — whether voters can verify their vote was counted — is **D42B: DESIGN KNOWLEDGE GAP**.

Design Decision: The Vote aggregate owns receipt_hash generation and storage (VO-3). Whether the verifiability guarantee requires additional behavior (delivery mechanism, challenge interface, external verification protocol) is outside the current aggregate boundary until D42B is investigated and ARB approves.

**The Vote aggregate boundary does not expand to own verifiability until D42B is resolved.**

### Threat Assessment (Round 32C Section 8)

| Threat | Assessment |
|--------|-----------|
| Authority Abuse | PROTECTED — Vote recording does not involve authority decisions |
| Privilege Escalation | LOW RISK — Vote aggregate does not gate authority |
| Evidence Tampering | CRITICAL CONTROL — VO-2 (data_checksum), VO-3 (receipt_hash) are the primary tamper controls; must be generated atomically with ballot |
| Replay Abuse | PROTECTED — VoteHash unique constraint prevents duplicate submission |
| Legitimacy Manipulation | LOW RISK — Vote aggregate does not determine legitimacy |
| Denial of Governance | WATCH — If vote recording is unavailable during voting_active window, participation is denied; infrastructure resilience required |
| **VO-1 Specific Threat** | **CRITICAL** — Any design change that adds voter-linkage data to Vote aggregate violates the constitutional core. This is not a security threat to detect — it is a design prohibition to enforce. |

### Why This Is an Aggregate (Not an Entity or Service)

Vote is an aggregate — not a storage entity — because:

1. **Consistency invariant VO-1** (anonymity) is a constitutional prohibition that must be enforced at the aggregate boundary. No partial ballot state may exist that could allow statistical linkage to a voter. An aggregate root is the only mechanism that can prevent any internal operation from adding voter-linkage data.
2. **Consistency invariant VO-4** (atomicity) requires that all ballot selections, hashes, and checksums are created in one indivisible operation. Any architecture that allows partial write is a constitutional violation, not merely a data integrity issue.
3. **Consistency invariant VO-2** (tamper evidence) requires that the data_checksum is computed over the complete ballot at creation time and never changed after. An aggregate root controls the creation lifecycle and can enforce this immutability.
4. **VO-1 is not a security property to protect — it is a design constraint to never create.** This distinction requires an aggregate root as the gatekeeper: there must be no code path through which a voter identity could be attached to a Vote, regardless of how the surrounding system behaves.
5. BallotSelection has no independent lifecycle outside the Vote it belongs to — it is created and sealed as part of a single Vote operation.

A write service could record a vote, but only an aggregate root can enforce that voter anonymity is architecturally impossible to violate, not merely improbable.

### Governance Status

**Status:** AVAILABLE FOR DESIGN — D42B BOUNDARY DEFERRED

**Active Debts:** D42B (verifiability guarantee scope)

**Design May Proceed On:**
- Core vote recording (ballot + hashes + checksum)
- Anonymity protection pattern
- Duplicate prevention
- Atomic recording boundary

**Design May NOT Finalize:**
- Verifiability guarantee scope (blocked by D42B)
- Any behavior that delivers, challenges, or verifies receipt against voter identity

**ADR Required:**
- ADR-VOTE-001: VotingCode anonymization — confirm VotingCode is not a voter identity linkage
- ADR-VOTE-002: D42B boundary placeholder — formally document that verifiability guarantee scope is deferred

---

## Aggregate 4 — RoleAssignment (Candidate)

### Constitutional Requirement Traceability

```
Constitutional Requirement:
    Election authority must be
    role-based and election-scoped.
    Officers have defined roles
    within specific elections.

Decision Owner:
    D3 — "Is this action allowed?"
    Owner: Authorization Context

Protected Invariants:
    AU-3 — Role assignments are per-election (PROVISIONAL)
    (ADC-1, ADC-2 unresolved — exclusivity and temporal validity unknown)

Aggregate (Candidate):
    RoleAssignment
```

### Consistency Boundary (Candidate)

**What is likely atomic (pending ADC-1, ADC-2):**
- Role assignment to user within election scope
- Role revocation with officer attribution

**What remains unresolved:**
- Whether role exclusivity creates a consistency constraint (ADC-1)
- Whether temporal role validity requires date-bounded state management (ADC-2)
- Whether delegation creates nested role assignment relationships (ADH-1, ADG-2)

**Boundary Justification (Provisional):**
AU-3 suggests per-election scoping creates a natural consistency boundary. If ADC-1 resolves that some roles are mutually exclusive, the consistency boundary must also enforce uniqueness across roles within the assignment. Design must not assume this boundary is finalized.

### Aggregate Root (Candidate)

**Root (Candidate):** RoleAssignment

Candidate root for all role lifecycle operations within a specific election scope.

### Internal Entities (Candidate)

None currently identified with evidence.

### Value Objects (Candidate)

| Value Object | Description | Confidence |
|-------------|-------------|-----------|
| **Role** | Assigned role (chief, deputy, observer, etc.) | MEDIUM |
| **AssignmentStatus** | Active / revoked | CANDIDATE |
| **OfficerAttribution** | Who assigned or revoked, when | CANDIDATE |

### Threat Assessment (Round 32C Section 8)

| Threat | Assessment |
|--------|-----------|
| Authority Abuse | HIGH RISK — ADH-1 means delegation rules are incomplete; abuse surface is undefined |
| Privilege Escalation | HIGH RISK — Role assignment is the primary escalation vector; ADC-1 exclusivity rules not established |
| Evidence Tampering | MEDIUM — Officer attribution (candidate) provides some accountability |
| Replay Abuse | MEDIUM RISK — Role assignment replay could grant authority in incorrect contexts |
| Legitimacy Manipulation | MEDIUM RISK — Role assignment to unauthorized actors could invalidate governance decisions |
| Denial of Governance | HIGH RISK — Failure to assign required roles prevents governance from functioning |

### Why This Is a Candidate Aggregate (Not a Simple Entity)

RoleAssignment is a candidate aggregate — not a plain entity — because:

1. **Provisional invariant AU-3** (per-election scoping) suggests that the scope of a role assignment creates a consistency constraint: a role assignment is valid only within its election context. This scoping rule, if confirmed, cannot be enforced by a plain entity.
2. **ADC-1 (unresolved)** — if role exclusivity exists (some roles are mutually exclusive), then role assignment operations must enforce exclusivity atomically. That is an aggregate-level invariant, not an entity-level concern.
3. **ADC-2 (unresolved)** — if roles have temporal validity, the lifecycle of a role assignment (granted → active → expired or revoked) requires an aggregate root to enforce lifecycle transitions with officer attribution.

**This remains a CANDIDATE because the defining consistency invariants (ADC-1, ADC-2) are not yet resolved.** If investigation concludes that roles have no exclusivity constraints and no temporal lifecycle, a plain entity may be sufficient.

### Governance Status

**Status:** CANDIDATE — DESIGN INVESTIGATION REQUIRED

**Active Debts:** ADC-1, ADC-2, ADH-1

**Design May Investigate:**
- Core role assignment lifecycle pattern
- Per-election scoping constraint

**Design May NOT Finalize:**
- Role exclusivity rules (blocked by ADC-1)
- Temporal validity (blocked by ADC-2)
- Delegation model (blocked by ADH-1)

**ADR Required Before Promotion:**
- ADR-ROLE-001: Role uniqueness and exclusivity rules (resolves ADC-1)
- ADR-ROLE-002: Temporal validity and revocation authority (resolves ADC-2)

---

## Aggregate 5 — ReplaySession (Candidate)

### Constitutional Requirement Traceability

```
Constitutional Requirement:
    Governance decisions must be
    verifiable by replay.
    Evidence integrity must be
    confirmable after-the-fact.

Decision Owner:
    D7 — "Was evidence integrity preserved?"
    Owner: Governance Evidence Replay Context

Protected Invariants:
    GR-1 — Evidence seals are immutable (PROVISIONAL)
    GR-2 — Replay outcomes are deterministic (PROVISIONAL — LOW-MEDIUM confidence)

Aggregate (Candidate):
    ReplaySession
```

### Consistency Boundary (Candidate)

**What is likely atomic (pending ADGR-1):**
- Replay session initialization with evidence envelope sealing
- Assertion collection with session state
- Certification issuance with assertion finalization

**What remains unresolved:**
- Who may invoke a replay session (D36, ADGR-1)
- What happens after divergence is detected (D35, D37)
- Whether certification is advisory or has enforcement consequences (D35)

**Boundary Justification (Provisional):**
GR-1 (seal immutability) and GR-2 (deterministic replay) together suggest a session that moves from initialization through assertion to certification without allowing modification. Design must not assume governance consequences until D35/D36/D37 are resolved.

### Aggregate Root (Candidate)

**Root (Candidate):** ReplaySession

Candidate root for the lifecycle of a single evidence replay operation.

### Internal Entities (Candidate)

| Entity | Responsibility | Confidence |
|--------|---------------|-----------|
| **ReplayAssertion** | Records outcome of a single evidence check | CANDIDATE |
| **ReplayCertification** | Final certification result of the session | CANDIDATE |

### Value Objects (Candidate)

| Value Object | Description | Confidence |
|-------------|-------------|-----------|
| **EvidenceEnvelope** | Sealed, immutable evidence record | MEDIUM |
| **ReplayFingerprint** | Execution signature of the replay | CANDIDATE |
| **DivergenceType** | Classification of any detected divergence | CANDIDATE |

### Threat Assessment (Round 32C Section 8)

| Threat | Assessment |
|--------|-----------|
| Authority Abuse | HIGH RISK — D36 unresolved; unauthorized replay invocation could create false certifications |
| Privilege Escalation | MEDIUM RISK — Replay access could reveal governance information to unauthorized actors |
| Evidence Tampering | CRITICAL CONTROL — GR-1 (seal immutability) is the primary control; must hold under all conditions |
| Replay Abuse | **DOMAIN-SPECIFIC THREAT** — This aggregate is itself a replay mechanism; its own invocation must be governed |
| Legitimacy Manipulation | HIGH RISK — False replay certifications could be used to contest legitimate governance decisions |
| Denial of Governance | HIGH RISK — Unavailable replay prevents post-hoc governance verification |

### Why This Is a Candidate Aggregate (Not a Service)

ReplaySession is a candidate aggregate — not a stateless service — because:

1. **Provisional invariant GR-1** (seal immutability) requires that once evidence is sealed in an EvidenceEnvelope, no operation inside or outside the session may modify it. Stateless services have no mechanism to enforce this across the session lifecycle.
2. **Provisional invariant GR-2** (deterministic replay) requires that the same evidence always produces the same outcome. The replay session must maintain the evidence state across assertion collection and prevent any modification that would destroy determinism.
3. A replay session has a lifecycle: initialized → assertions collected → certified. This lifecycle with its invariants on intermediate states is a defining characteristic of aggregate structure, not service structure.
4. ReplayCertification (the terminal state) must be atomic with the final assertion collection — no partial certifications. Only an aggregate root can enforce this.

**This remains a CANDIDATE because the governance deployment model (ADGR-1) and the consequences of certification (D35, D37) are unresolved.** The aggregate lifecycle structure is plausible; the governance authority model that controls invocation and responds to divergence is not yet established.

### Governance Status

**Status:** CANDIDATE — GOVERNANCE RESOLUTION REQUIRED BEFORE FINALIZATION

**Active Debts:** ADGR-1, D35, D36, D37

**Design May Investigate:**
- Core replay session lifecycle pattern
- Evidence envelope sealing pattern

**Design May NOT Finalize:**
- Invocation authority (blocked by D36)
- Divergence consequences (blocked by D35, D37)
- Certification usage (blocked by D35)

**ADR Required Before Promotion:**
- ADR-REPLAY-001: Replay session invocation authority (resolves D36 dependency)
- ADR-REPLAY-002: Divergence consequence model (resolves D35, D37 dependency)

---

## Zero-Candidate Contexts — Design Assessment

### Eligibility (D2)

**Discovery Finding:** Stateless evaluation; no stored state required.

**Design Assessment:** No aggregate candidate identified. Eligibility continues as a Stateless Domain Service. Design investigation permitted. If future requirements introduce stored eligibility state (e.g., eligibility pre-computation for large elections), ADR + ARB approval required before introducing an aggregate.

**No ADR needed at this stage.**

---

### Audit (D6)

**Discovery Finding:** Fire-and-forget observability; append-only log; no consistency boundary.

**Design Assessment:** No aggregate candidate identified. Audit continues as an Append-Only Recording service. The append-only pattern (Characteristic 3, Round 29) means no aggregate protection is needed — the protection is the absence of mutation operations.

**No ADR needed at this stage.**

---

### Results / Tallying (D39 context)

**Discovery Finding:** Results derivable from Vote data. Final ownership model is a design concern (Round 32B).

**Design Assessment:** D39 remains unresolved. Design must not assume Results/Tallying either does or does not have an aggregate before investigating D39.

**Investigation Required:**
- Does counting_complete state create independent decision ownership?
- Does electoral formula variability (FPTP, STV, etc.) create independent aggregate invariants?

**If investigation concludes independent aggregate exists:** ADR-RESULTS-001 required.

**If investigation concludes Results remains derived:** ADR-RESULTS-001 required to record that conclusion.

---

### Arbitration / Legitimacy (D8)

**Discovery Finding:** Decision record pattern observed; aggregate candidate not yet established. D35/D36/D37 unresolved.

**Design Assessment:** Cannot finalize aggregate design for Arbitration/Legitimacy until D35/D36/D37 are resolved. Design may investigate the evaluation pattern. An aggregate candidate may emerge once governance consequences are known.

**If governance resolution reveals enforcement aggregate:** ADR-ARB-001 required.

---

## Aggregate Design Summary

### ARB Review Matrix

| Aggregate | Decision Owner | Invariants | Governance Debts |
|-----------|---------------|-----------|-----------------|
| **Verification** | D1 — Trust Attestation | TA-1, TA-2, TA-3 | None |
| **GovernanceState** | D4 — Constitutional Governance | CG-1, CG-2, CG-3 (provisional) | D35, D37, ADH-1, ADG-2 |
| **Vote** | D5 — Voting | VO-1, VO-2, VO-3, VO-4 | D42B |
| **RoleAssignment** | D3 — Authorization | AU-3 (provisional) | ADC-1, ADC-2, ADH-1 |
| **ReplaySession** | D7 — Governance Evidence Replay | GR-1, GR-2 (both provisional) | ADGR-1, D35, D36, D37 |

### Design Status Summary

| Aggregate | ARB Classification | Governance Sensitivity |
|-----------|-------------------|----------------------|
| Verification | APPROVED AGGREGATE DESIGN | LOW |
| GovernanceState | APPROVED AGGREGATE PATTERN — Governance Debt Active | HIGH |
| Vote | APPROVED AGGREGATE DESIGN — D42B boundary explicit | MEDIUM |
| RoleAssignment | AGGREGATE CANDIDATE | HIGH |
| ReplaySession | AGGREGATE CANDIDATE | VERY HIGH |

---

## ADR Inventory (Proposed)

| ADR ID | Topic | Aggregate | Priority |
|--------|-------|-----------|---------|
| ADR-VER-001 | Verification lifecycle states | Verification | HIGH |
| ADR-VER-002 | Trust Level value object classification | Verification | MEDIUM |
| ADR-GOV-STATE-001 | Lifecycle state inventory | GovernanceState | HIGH |
| ADR-GOV-STATE-002 | Suspension overlay consistency | GovernanceState | HIGH |
| ADR-VOTE-001 | VotingCode anonymization | Vote | HIGH |
| ADR-VOTE-002 | D42B boundary placeholder | Vote | HIGH |
| ADR-ROLE-001 | Role exclusivity rules (resolves ADC-1) | RoleAssignment | HIGH |
| ADR-ROLE-002 | Temporal validity and revocation (resolves ADC-2) | RoleAssignment | HIGH |
| ADR-REPLAY-001 | Replay invocation authority (resolves D36 dependency) | ReplaySession | VERY HIGH |
| ADR-REPLAY-002 | Divergence consequence model (resolves D35/D37) | ReplaySession | VERY HIGH |
| ADR-RESULTS-001 | Results/Tallying aggregate decision | Results/Tallying | HIGH |
| ADR-ARB-001 | Arbitration aggregate (pending D35/D36/D37) | Arbitration/Legitimacy | VERY HIGH |

---

## ARB Review

Round 33 is submitted for ARB review.

ARB must confirm:

- Are the five aggregate boundary designs consistent with Round 32B?
- Are the constitutional traceability chains correct?
- Are the threat assessments complete?
- Are governance-dependent aspects correctly deferred?
- Is the D42B placeholder for Vote appropriate?
- Are the zero-candidate context assessments appropriate?
- Is the ADR inventory appropriate?

**Explicitly Deferred to Subsequent Rounds:**
- Domain events
- Commands
- Repositories
- Services
- Persistence models
- Read models
- API contracts

