# Round 29 — Bounded Context Catalog

**Date:** 2026-06-08

**Phase:** Strategic-to-Tactical Synthesis

**Status:** Authoritative Domain Model Reference

**Purpose:** Consolidate all accepted bounded contexts from Rounds 17–28A into the authoritative domain model. This catalog is the source of truth for all domain context boundaries.

---

## Context Catalog

### 1. Trust Attestation

**Purpose:** Determine whether an identity is trustworthy through formal officer attestation.

**Decision Ownership:**
- Primary Decision: "Is this identity verified and trustworthy?"
- Owned by Trust Attestation
- Core Invariant: One active verification per participant per organization; revocation must be attributed; verification status is immutable once recorded

**Key Concepts:**
- Verification (aggregate root)
- Attestation (entity within Verification)
- Trust Level (value object)
- Revocation Reason (value object)
- Officer Attribution (value object)

**Relationships:**
- **Upstream:** Eligibility reads verification status (read-only)
- **Upstream:** Authorization reads verification status (read-only)
- Boundary Relationship: Verification ≠ Eligibility (ADR-002)

**Boundary Stability:** STABLE
- No provisional concerns
- Evidence Strength: HIGH

**Open Debts:** None affecting this context

**Source Evidence:** ADR-001, ADR-002, UBIQUITOUS_LANGUAGE.md, TRUST_CHAIN.md, Round 25 C1, Round 27A

---

### 2. Eligibility

**Purpose:** Determine whether a participant is eligible to vote in a specific election or governance process.

**Decision Ownership:**
- Primary Decision: "Is this participant eligible for this specific process?"
- Owned by Eligibility
- Core Invariant: Eligibility is computed at action time; no stored eligibility state required

**Key Concepts:**
- Eligibility Evaluation (policy-driven, stateless)
- Participation Eligibility Evidence (value object)
- Membership Status (value object)
- Fee Status (value object)
- Enrollment Status (value object)

**Relationships:**
- **Downstream:** Requires Verification status (read-only at evaluation time)
- **Upstream:** Authorization reads eligibility evaluation result (read-only)
- Boundary Relationship: Eligibility ≠ Verification (ADR-002), Eligibility ≠ Authorization (ADR-002)

**Boundary Stability:** STABLE
- No provisional concerns
- Evidence Strength: HIGH

**Open Debts:** None affecting this context

**Source Evidence:** ADR-002, TRUST_CHAIN.md, Stream 5 preconditions, Round 25 C2, Round 27B

---

### 3. Authorization

**Purpose:** Determine what capabilities a user is allowed to exercise in a specific context.

**Decision Ownership:**
- Primary Decision: "Is this user allowed to perform this action in this context?"
- Owned by Authorization
- Core Invariant: Authorization resolution is deterministic; central resolver is sole authority; no bypass mechanisms exist

**Key Concepts:**
- Capability Resolver (central authority)
- Authorization Classification (value object)
- Role (value object)
- Permission (value object)
- Scope (value object)

**Relationships:**
- **Downstream:** Requires Verification status (read-only)
- **Downstream:** Requires Eligibility evaluation (read-only)
- **Downstream:** Requires Constitutional Governance state (read-only)
- Tight integration with Constitutional Governance — governance rules constrain authority

**Boundary Stability:** STABLE
- Integration with Constitutional Governance is a dependency, not a merger signal
- Evidence Strength: HIGH

**Open Debts:** 
- ADH-1 (Authority hierarchy governance gaps; **Debt Type:** Model Refinement)

**Source Evidence:** ADR-001, ADR-004, Stream 2, ElectionConstitution.php, ConstitutionalTransitionGuard.php, Round 25 C3, Round 27C

---

### 4. Constitutional Governance

**Purpose:** Define and enforce the election lifecycle — what states exist, which transitions are allowed, what preconditions must be satisfied.

**Decision Ownership:**
- Primary Decision: "What is the valid election lifecycle state? Can this transition be made?"
- Owned by Constitutional Governance
- Core Invariant: State transitions are deterministic and enforced centrally; preconditions are immutable; suspension overlays cannot contradict base lifecycle

**Key Concepts:**
- Governance State (aggregate root)
- Lifecycle State (value object)
- Precondition (value object)
- State Transition (value object)
- Suspension Overlay (entity within GovernanceState)

**Relationships:**
- **Upstream:** Authorization is constrained by lifecycle state
- **Downstream:** Voting requires voting_active state
- **Downstream:** Results publication gated by counting_complete state
- Serves as constraint authority for Authorization and all voting operations

**Boundary Stability:** STABLE
- Rules-in-code is intentional (D30 resolved)
- Evidence Strength: HIGH

**Open Debts:**
- ADG-2 (Delegated authority governance gaps; **Debt Type:** Governance)
- ADH-1 (Authority hierarchy governance gaps; **Debt Type:** Governance)

**Source Evidence:** ADR-003 (Lifecycle vs Phase), Stream 5, Round 18 Governance Review, Round 22 relationship analysis, Round 25 C4, Round 27D

---

### 5. Audit

**Purpose:** Record operational events for auditability and officer accountability.

**Decision Ownership:**
- Primary Decision: "What operational evidence must be preserved?"
- Owned by Audit
- Core Invariant: Fire-and-forget; audit never affects business outcomes; no feedback loop from audit to operational contexts

**Key Concepts:**
- Audit Log (append-only record)
- Security Event Recorder (observability)
- Event (value object)
- Trace (immutable record)

**Relationships:**
- **Receives from:** All contexts (one-way, fire-and-forget)
- **Affects:** None (observability context)
- Separated from Governance Evidence Replay (different purpose; Stream 4 confirmed)

**Boundary Stability:** STABLE
- Fire-and-forget pattern is well-established and legitimate
- Evidence Strength: HIGH

**Open Debts:** None affecting this context

**Source Evidence:** Stream 4, ElectionAuditService, ElectionAuditLog, SecurityEventRecorder, Round 22 R7/R8/R9, Round 25 C5, Round 27F

---

### 6. Voting

**Purpose:** Record votes in a manner that is anonymous, verifiable, and protected by checksums.

**Decision Ownership:**
- Primary Decision: "Is this vote valid and anonymous?"
- Owned by Voting
- Core Invariants: Vote anonymity (no user_id column); vote uniqueness (vote_hash); vote integrity (data_checksum); vote verifiability (receipt_hash)

**Key Concepts:**
- Vote (aggregate root)
- Ballot Selection (entity within Vote)
- Vote Receipt (value object)
- Vote Hash (value object)
- Data Checksum (value object)

**Relationships:**
- **Requires:** Verification status (read-only)
- **Requires:** Eligibility evaluation (read-only)
- **Requires:** Authorization check (read-only)
- **Requires:** Constitutional Governance state: voting_active (read-only)
- **Observed:** Vote recording triggers result-related computations in current implementation; final responsibility boundary remains provisional pending D39 resolution

**Boundary Stability:** PROVISIONAL
- Boundary with Verification unresolved pending D42B
- D42B: Verifiability guarantee scope unclear — does Verification own verifiability, or Voting?
- Evidence Strength: HIGH

**Open Debts:**
- D42B (Verifiability guarantee scope; **Debt Type:** Discovery)

**Source Evidence:** Stream 3, BaseVote.php, Vote.php, ADR_20260203 (Voting Security), Round 22 R4/R5/R6, Round 25 C6, Round 27E

---

### 7. Results / Tallying

**Purpose:** Display election results and count computations.

**Decision Ownership:**
- Observed Decision: Results are currently a derived projection from Vote data
- Unique Decision Ownership: Not independently observed in current implementation
- Core Invariant: All Results state is reconstructable from Vote data without loss of business decisions (Projection Test, Round 25)

**Key Concepts:**
- Result (derived projection in current implementation)
- Vote Count (computed value)
- No independent aggregate identified

**Relationships:**
- **Derived from:** Vote data (synchronous projection)
- **Gated by:** Constitutional Governance results_published flag
- **Boundary Status:** Current evidence does not demonstrate independent aggregate ownership; boundary remains provisional pending D39 resolution

**Boundary Stability:** PROVISIONAL
- Projection Test (Round 25) passed: all Results state reconstructable from Vote data
- Low observed decision ownership in current implementation
- Evidence Strength: MEDIUM

**Open Debts:**
- D39 (Counting state meaning; **Debt Type:** Discovery)

**Source Evidence:** Stream 3, Vote.php, ResultController.php, ADR-003 (counting state), Projection Test (Round 25), Round 25 C7, Round 27I

---

### 8. Governance Evidence Replay

**Purpose:** Verify evidence integrity and replay governance decisions to certify outcomes.

**Decision Ownership:**
- Primary Decision: "Was evidence integrity preserved? Does replay outcome match original?"
- Owned by Governance Evidence Replay
- Core Invariant: Evidence seals are immutable; replay outcomes are deterministic; divergence detection is reliable

**Key Concepts:**
- Replay Session (provisional aggregate)
- Evidence Envelope (immutable value object)
- Replay Assertion (entity within ReplaySession)
- Replay Certification (entity within ReplaySession)
- Replay Fingerprint (value object)

**Relationships:**
- **Receives from:** Constitutional Governance (evidence to replay)
- **Creates:** Certification records
- **Supports:** Audit trail verification
- Separated from Audit (different purpose; distinct decision ownership)

**Boundary Stability:** PROVISIONAL
- Boundary valid but operational deployment unresolved
- Evidence Strength: MEDIUM

**Open Debts:**
- D36 (Invocation path unresolved; **Debt Type:** Governance)
- ADGR-1 (Replay Session governance gaps; **Debt Type:** Model Refinement)

**Source Evidence:** Stream 4, ReplayEvidenceEnvelope, GovernanceReplayService, GovernanceDecisionSnapshot, Replay Classification Test (Round 25), Round 25 C8, Round 27G, Round 28A

---

### 9. Arbitration / Legitimacy

**Purpose:** Evaluate governance decisions for constitutional validity and determine legitimacy status.

**Decision Ownership:**
- Primary Decision: "Is this governance decision constitutionally valid?"
- Owned by Arbitration / Legitimacy
- Core Invariant: Constitutional Decision is a Decision Record that evaluates but does not create truth

**Key Concepts:**
- Legitimacy Evaluator (stateless policy)
- Constitutional Arbitration Kernel (decision review mechanism)
- Constitutional Decision (decision record)
- Governance Legitimacy (enum: LEGITIMATE, EXPIRED, PENDING, SUSPENDED, EMERGENCY, CARETAKER, REVOKED)

**Relationships:**
- **Evaluates:** Governance decisions from Constitutional Governance
- **Boundary with Governance:** Remains unresolved pending D35/D36/D37 clarification

**Boundary Stability:** UNRESOLVED
- Boundary classification with Governance context uncertain
- Evidence Strength: MEDIUM

**Open Debts:**
- D35 (Legitimacy consequences; **Debt Type:** Governance)
- D36 (Invocation authority; **Debt Type:** Governance)
- D37 (Enforcement mechanisms; **Debt Type:** Governance)
- ADH-1 (Authority hierarchy governance gaps; **Debt Type:** Governance)

**Source Evidence:** ConstitutionalArbitrationKernel.php, ConflictResolutionPolicy.php, LegitimacyEvaluator.php, GovernanceLegitimacy.php, Round 18 Governance Review, Round 25 C9, Round 27H, Round 28A

---

## Reclassified Candidates

### Challenge / Dispute Handling (C10)

**Classification:** Distributed Domain Capability (not a bounded context)

**Rationale:** 
- No unique business purpose identified
- No unique decision ownership observed
- Function emerges from combination of:
  - Arbitration + Governance Evidence Replay (verification capability)
  - Constitutional Governance (reversal/blocking capability)
  - Authorization (enforcement capability)
- No distinct language; relies on arbitration, replay, and verification vocabulary

**Remains:** Tracked in requirements but not as a separate bounded context

**Source Evidence:** Stream 6A, D33

---

## Context Summary Table

| # | Context | Decision Ownership | Stability | Confidence | Primary Debt |
|---|---------|-------------------|-----------|-----------|------------|
| 1 | Trust Attestation | "Is identity trustworthy?" | STABLE | HIGH | None |
| 2 | Eligibility | "Is participant eligible?" | STABLE | HIGH | None |
| 3 | Authorization | "Is action allowed?" | STABLE | HIGH | ADH-1 |
| 4 | Constitutional Governance | "Is transition allowed?" | STABLE | HIGH | ADG-2, ADH-1 |
| 5 | Audit | "What to log?" | STABLE | HIGH | None |
| 6 | Voting | "Is vote valid?" | PROVISIONAL | HIGH | D42B |
| 7 | Results/Tallying | (Derived projection) | PROVISIONAL | MEDIUM | D39 |
| 8 | Governance Evidence Replay | "Was evidence preserved?" | PROVISIONAL | MEDIUM | D36, ADGR-1 |
| 9 | Arbitration/Legitimacy | "Is decision valid?" | UNRESOLVED | MEDIUM | D35, D36, D37, ADH-1 |

---

## Debt Classification Reference

Debts referenced in this catalog are classified as follows:

| Type | Definition | Examples from Catalog |
|------|-----------|----------------------|
| **Governance Debt** | Governance rules, authority chains, or decision scope unresolved | D35, D36, D37, ADG-2, ADH-1 |
| **Discovery Debt** | Repository investigation questions remaining | D39, D42B |
| **Model Refinement Debt** | Tactical model boundary or aggregate definitional gaps | ADGR-1, ADH-1 (cross-classified) |

---

**Round 29 — Bounded Context Catalog APPROVED**

**ARB Status:** Ready for Aggregate Catalog

**Next:** Round29_Aggregate_Catalog.md

