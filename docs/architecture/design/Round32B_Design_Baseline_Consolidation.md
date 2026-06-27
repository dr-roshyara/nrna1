# Round 32B — Design Baseline Consolidation

**Date:** 2026-06-08

**Phase:** Design Baseline

**Type:** Authoritative Design Baseline

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 32 Design Governance Charter (APPROVED)
- Round 32A Design Work Program (APPROVED)

**Purpose:** Freeze what design is allowed to assume. All entries are derived from Round 29 discovery catalogs. No new discovery, no design decisions.

**Governance Rule:** This baseline records what discovery found. It does not make design decisions. Any design decision that modifies a baseline entry requires ADR + ARB approval.

---

## 1. Accepted Context Map Baseline

Nine bounded contexts are accepted. Boundaries, ownership, and stability classifications are as discovered in Round 29.

| # | Context | Decision Ownership | Boundary Stability | Confidence |
|---|---------|-------------------|-------------------|-----------|
| 1 | **Trust Attestation** | "Is this identity trustworthy?" | STABLE | HIGH |
| 2 | **Eligibility** | "Is this participant eligible?" | STABLE | HIGH |
| 3 | **Authorization** | "Is this action allowed?" | STABLE | HIGH |
| 4 | **Constitutional Governance** | "Is this transition allowed?" | STABLE | HIGH |
| 5 | **Audit** | "What must be recorded?" | STABLE | HIGH |
| 6 | **Voting** | "Is this vote valid and anonymous?" | PROVISIONAL | HIGH |
| 7 | **Results / Tallying** | "What are the vote counts?" (ownership model: see below) | PROVISIONAL | MEDIUM |
| 8 | **Governance Evidence Replay** | "Was evidence integrity preserved?" | PROVISIONAL | MEDIUM |
| 9 | **Arbitration / Legitimacy** | "Is this governance decision constitutionally valid?" | UNRESOLVED | MEDIUM |

### 1.1 Results / Tallying Ownership Note

Current discovery evidence indicates results are derivable from recorded votes (Projection Test, Round 25). Final ownership model remains a design concern. Discovery did not conclude that Results/Tallying is a projection pattern — it observed that results are reconstructable from Vote data. Whether an independent aggregate or independent decision ownership exists in Results/Tallying is a question for design investigation.

### 1.2 Context Relationships

The following relationships were directly observed in discovery artifacts. Where inferred, they are marked PROVISIONAL.

| Relationship | Evidence | Status |
|-------------|---------|--------|
| Trust Attestation upstream of Eligibility (read-only) | ADR-002, Stream 5 | ACCEPTED |
| Trust Attestation upstream of Authorization (read-only) | ADR-001, ADR-004 | ACCEPTED |
| Eligibility upstream of Authorization (read-only) | ADR-002 | ACCEPTED |
| Constitutional Governance constrains Authorization | ElectionConstitution, ConstitutionalTransitionGuard | ACCEPTED |
| Constitutional Governance gates Voting (voting_active required) | Stream 3, lifecycle rules | ACCEPTED |
| Constitutional Governance gates Results publication | ADR-003, counting state | PROVISIONAL |
| Audit receives from all contexts (fire-and-forget) | Stream 4 | ACCEPTED |
| Governance Evidence Replay receives from Constitutional Governance | Stream 4, GovernanceReplayService | PROVISIONAL |
| Arbitration / Legitimacy evaluates Constitutional Governance decisions | Stream 6B | PROVISIONAL |

### 1.3 Challenge / Dispute Handling

Classified as a distributed domain capability, not a bounded context. Discovery did not identify independent decision ownership. Capability emerges from Arbitration, Governance Evidence Replay, Constitutional Governance, and Authorization. Design may revisit this classification with ADR + ARB approval.

---

## 2. Accepted Aggregate Baseline

Five aggregates are identified. Status classifications are as discovered in Round 29.

### 2.1 Verification (DISCOVERED)

**Context:** Trust Attestation

**Aggregate Root:** Verification

**Core Responsibility:** Protect the identity trust decision — whether an identity is trustworthy within an organization.

**Consistency Boundary:** Entire Verification lifecycle (active → revoked) is atomic; Attestation changes are linked to status changes; revocation attribution is atomic.

**Key Entities:** Attestation

**Key Value Objects:** Trust Level, Revocation Reason, Officer Attribution

**Status:** DISCOVERED — HIGH confidence

**Active Debts:** None.

---

### 2.2 GovernanceState (DISCOVERED)

**Context:** Constitutional Governance

**Aggregate Root:** GovernanceState

**Core Responsibility:** Protect the election lifecycle state and enforce constitutional preconditions for state transitions.

**Consistency Boundary:** State transition is atomic with precondition validation; suspension overlays are atomic with base state operations.

**Key Entities:** Suspension Overlay

**Key Value Objects:** Lifecycle State, State Transition, Precondition

**Status:** DISCOVERED — MEDIUM-HIGH confidence

**Active Debts:** ADG-2 (delegation authority), ADH-1 (authority hierarchy).

---

### 2.3 Vote (DISCOVERED)

**Context:** Voting

**Aggregate Root:** Vote

**Core Responsibility:** Protect vote recording with anonymity, uniqueness, integrity, and verifiability guarantees.

**Consistency Boundary:** Entire vote recording (selections + hashes + checksums) is atomic; no partial ballot updates.

**Key Entities:** Ballot Selection

**Key Value Objects:** Vote Hash, Data Checksum, Receipt Hash, Voting Code

**Status:** DISCOVERED — HIGH confidence

**Active Debts:** D42B (verifiability guarantee scope).

---

### 2.4 RoleAssignment (CANDIDATE)

**Context:** Authorization

**Aggregate Root (Candidate):** RoleAssignment

**Core Responsibility (Candidate):** Protect role assignments for users within election contexts.

**Status:** CANDIDATE — MEDIUM-LOW confidence

**Active Debts:** ADC-1 (role exclusivity rules), ADC-2 (temporal validity and revocation authority).

---

### 2.5 ReplaySession (CANDIDATE)

**Context:** Governance Evidence Replay

**Aggregate Root (Candidate):** ReplaySession

**Core Responsibility (Candidate):** Protect evidence replay sessions for governance decision certification.

**Status:** CANDIDATE — MEDIUM confidence

**Active Debts:** ADGR-1 (replay governance authority), D35/D36/D37 (governance consequences).

---

### 2.6 Contexts Without Identified Aggregate Candidates

Discovery did not identify aggregate candidates in the following contexts. Design investigation remains permitted.

| Context | Discovery Finding |
|---------|-----------------|
| Eligibility | No stored state observed; stateless evaluation pattern |
| Audit | Fire-and-forget observability pattern; no consistency boundary identified |
| Results / Tallying | Results derivable from Vote data; independent aggregate candidate not yet established |
| Arbitration / Legitimacy | Decision record pattern observed; aggregate candidate not yet established |

**Note:** The absence of discovered aggregate candidates does not preclude design from identifying one. Design investigation of these contexts is explicitly permitted.

---

## 3. Accepted Decision Ownership Baseline

Eight core business decisions were identified in discovery. Ownership classifications are as discovered in Round 29.

| Decision | Owner | Classification | Confidence |
|----------|-------|-----------------|-----------|
| **D1: Is this identity trustworthy?** | Trust Attestation | DISCOVERED | HIGH |
| **D2: Is this participant eligible?** | Eligibility | DISCOVERED | HIGH |
| **D3: Is this action allowed?** | Authorization | DISCOVERED | HIGH |
| **D4: What is the valid lifecycle state?** | Constitutional Governance | DISCOVERED | MEDIUM-HIGH |
| **D5: Is this vote valid and anonymous?** | Voting | DISCOVERED | HIGH |
| **D6: What evidence must be preserved?** | Audit | DISCOVERED | HIGH |
| **D7: Was evidence integrity preserved?** | Governance Evidence Replay | PROVISIONAL | MEDIUM |
| **D8: Is this governance decision valid?** | Arbitration / Legitimacy | PROVISIONAL | MEDIUM |

### Decision Dependency (Discovered)

D1 → D2 → D3 → D4 → D5 must all succeed before a vote is recorded.

D6 receives from all decisions (fire-and-forget, no coupling).

D7 ↔ D8 relationship and operational consequences are UNRESOLVED (ADGR-1, D35, D36, D37).

### Unresolved Affecting D5

- D39: Whether result computations triggered by vote recording belong inside Voting (D5) or a separate Results decision context.
- D42B: Whether the verifiability guarantee is owned by Voting (D5) or another decision owner.

---

## 4. Accepted Invariant Baseline

Seventeen domain invariants are accepted. Classification and confidence are as discovered in Round 29.

### 4.1 DISCOVERED Invariants (11)

| ID | Invariant Statement | Context | Confidence |
|----|---------------------|---------|-----------|
| **TA-1** | One active Verification per participant per organization at any time | Trust Attestation | HIGH |
| **TA-2** | Revocation must be attributed to an officer with timestamp | Trust Attestation | HIGH |
| **TA-3** | Verification decisions are append-only; historical decisions are immutable | Trust Attestation | HIGH |
| **EL-1** | Eligibility evaluation is deterministic for identical inputs | Eligibility | HIGH |
| **AU-1** | Authorization resolution is deterministic for identical inputs | Authorization | HIGH |
| **AU-2** | All authorization decisions pass through the central authority | Authorization | HIGH |
| **CG-1** | Lifecycle state transitions are deterministic for identical precondition states | Constitutional Governance | MEDIUM-HIGH |
| **CG-2** | A state transition is only valid if all preconditions are satisfied | Constitutional Governance | HIGH |
| **VO-1** | A vote must not be linkable to the voter who cast it | Voting | HIGH |
| **VO-2** | Recorded vote content must be tamper evident | Voting | HIGH |
| **VO-3** | Each vote generates and stores a receipt hash at recording time | Voting | HIGH |
| **VO-4** | All ballot selections for a single vote are recorded atomically | Voting | MEDIUM |

### 4.2 PROVISIONAL Invariants (6)

| ID | Invariant Statement | Context | Confidence | Blocked By |
|----|---------------------|---------|-----------|-----------|
| **AU-3** | Role assignments are scoped to specific elections | Authorization | MEDIUM-LOW | ADC-1, ADC-2 |
| **CG-3** | Suspension overlays cannot violate the base lifecycle structure | Constitutional Governance | MEDIUM-HIGH | ADH-1 |
| **GR-1** | Once evidence is sealed in an Evidence Envelope, the seal is immutable | Governance Evidence Replay | MEDIUM | ADGR-1 |
| **GR-2** | Given the same evidence input, replay always produces the same outcome | Governance Evidence Replay | LOW-MEDIUM | ADGR-1 |
| **AR-1** | Constitutional rules are applied deterministically without discretion | Arbitration / Legitimacy | MEDIUM | D35, D36, D37 |

### 4.3 Invariant Notes

- **VO-3:** Receipt hash existence is DISCOVERED (Vote owns this). The verifiability guarantee — whether votes are verifiable to voters and who owns that guarantee — is D42B (UNRESOLVED).
- **VO-2:** Discovered implementation uses checksums. Other integrity mechanisms could fulfill this invariant while preserving the business rule.

---

## 5. Active Design Constraints

Active constraints on all design decisions. No constraint may be treated as resolved until ARB confirms resolution.

### 5.1 Governance Constraints

| Item | Question | Status | Design Impact |
|------|----------|--------|---------------|
| **D35** | What happens when legitimacy = EXPIRED? | CONFIRMED — consequences unknown | Blocks finalization of legitimacy-consequence design |
| **D36** | Who may invoke ConstitutionalArbitrationKernel? | UNRESOLVED | Blocks finalization of arbitration-invocation design |
| **D37** | How is legitimacy determination enforced? | PARTIALLY RESOLVED | Blocks finalization of enforcement-integration design |
| **ADH-1** | What is the complete authority hierarchy? | PARTIALLY RESOLVED | Blocks finalization of authority-scope design |
| **D42B** | Nature, responsibility, and implications of verifiability | DESIGN KNOWLEDGE GAP | Blocks finalization of verifiability-related design |

### 5.2 Model Refinement Constraints

| Item | Question | Status | Refinement Scope |
|------|----------|--------|-----------------|
| **D39** | What is the business meaning of counting_complete state? | DISCOVERY DEBT | May affect Results/Tallying decision ownership |
| **ADG-2** | How is authority delegated within Constitutional Governance? | GOVERNANCE DEBT | May refine GovernanceState aggregate |
| **ADC-1** | What are the role uniqueness and exclusivity rules? | MODEL REFINEMENT DEBT | May refine RoleAssignment aggregate |
| **ADC-2** | Do roles have temporal validity and who can revoke them? | MODEL REFINEMENT DEBT | May refine RoleAssignment aggregate |
| **ADGR-1** | What are the governance scope and operational authority for ReplaySession? | MODEL REFINEMENT DEBT | May refine ReplaySession aggregate |

### 5.3 Master Constraint Rule

> Discovery is evidence. Design is hypothesis.
>
> Discovery may constrain design.
>
> Design may not rewrite discovery without ADR + ARB approval.

---

## 6. What Discovery Did NOT Establish

The following were not discovered during Rounds 17–29. Design must not assume these as established.

### Architecture Patterns Not Discovered

- Microservices (service decomposition not discovered)
- CQRS (command/query separation not discovered as domain requirement)
- Event Sourcing (event store not discovered as domain requirement)
- Blockchain / distributed ledger (not evidenced in domain)
- ElectionGuard architecture (not evidenced in domain)

### Domain Concepts Not Discovered

- Analytical Results Context (no independent ownership observed)
- Verifiability Context (D42B — design knowledge gap)
- Coercion Resistance requirements (not evidenced in domain)
- Challenge Submission mechanism (D36 — unresolved)
- Voter notification requirements (not evidenced in domain)

### Design Decisions Not Discovered

- Domain events (catalog deferred to design)
- Commands (catalog deferred to design)
- Query handlers (catalog deferred to design)
- API contracts (deferred to design)
- Read model definitions (deferred to design)
- Service definitions (deferred to design)

**Governance Rule:** The absence of an item from this list does not imply it was discovered. If a design decision is not traceable to a discovery artifact, it requires ADR justification before being treated as baseline.

---

## 7. Design Risk Register

Active risks to governance discipline during the design phase.

| Risk ID | Risk | Mitigation |
|---------|------|-----------|
| **R1** | Design decisions treat provisional items as stable | Invariant and context stability classifications must be explicitly checked before design decisions are finalized |
| **R2** | Literature patterns override discovered domain model | Literature governance rules (Round 32 Charter) must be enforced at every design review |
| **R3** | Aggregate boundaries become implementation-driven rather than domain-driven | All boundary decisions require ADR with explicit reference to discovery evidence |
| **R4** | Voting context absorbs neighboring context responsibilities | Voting decision ownership (D5) is scoped to vote validity and anonymity; expansions require ADR + ARB approval |
| **R5** | D42B investigation presupposes verifiability ownership | D42B investigation must produce candidate options with evidence; ownership may not be assumed before ARB review |
| **R6** | Governance items (D35/D36/D37/ADH-1) are assumed resolved and designed around | All governance-dependent design conclusions must be marked PENDING until ARB confirms resolution |
| **R7** | Results/Tallying boundary absorbs domain concepts beyond current evidence | Results/Tallying remains provisional; any ownership expansion requires ADR |
| **R8** | Premature decomposition into technical services before domain model is stable | Service decomposition is prohibited until aggregate design is approved |
| **R9** | Performance, scalability, or optimization concerns drive aggregate boundaries | Aggregate boundaries are driven by invariants and decision ownership, not technical concerns |

---

## Baseline Governance

**This baseline freezes discovered knowledge.**

It does not make design decisions. It does not prevent legitimate design work. It does not assume governance items are resolved.

### What Requires ADR + ARB Approval

- Changing a context boundary, stability, or ownership
- Changing an aggregate responsibility or consistency boundary
- Changing an invariant statement or classification
- Promoting a PROVISIONAL item to DISCOVERED
- Identifying an aggregate candidate in a currently zero-candidate context
- Reinterpreting a baseline entry

### What Design May Do Without Prior ARB Approval

- Create PROPOSED ADRs for review
- Investigate D42B per Stream C governance rules
- Prepare integration patterns for governance-dependent items (not finalize them)
- Refine model-refinement debts (ADG-2, ADC-1, ADC-2, ADGR-1) via ADR process

---

## ARB Review

Round 32B is submitted for ARB review.

ARB must confirm:

- Are the nine contexts and their ownership statements accurately recorded from Round 29?
- Are the five aggregates accurately recorded from Round 29?
- Are the eight decisions and ownership assignments accurately recorded from Round 29?
- Are the seventeen invariants accurately recorded from Round 29?
- Are the active design constraints complete?
- Is the "Discovery Did Not Establish" section accurate?
- Is the Design Risk Register sufficient?
- Is this baseline ready to serve as the authoritative foundation for design execution?

**Upon ARB approval of Round 32B: design execution may reference this baseline.**

