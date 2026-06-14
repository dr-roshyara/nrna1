# Round 29 — Aggregate Catalog

**Date:** 2026-06-08

**Phase:** Strategic-to-Tactical Synthesis

**Status:** Authoritative Aggregate Inventory (Evidence-Based Discovery)

**Purpose:** Catalog all aggregates identified through rounds of discovery (Rounds 17–28A). This report documents what discovery found, not what architecture will design. Distinction between discovered facts, candidate models, and unresolved questions is explicit.

---

## Aggregate Inventory Summary

| # | Aggregate | Context | Status | Confidence | Key Decision | Debts |
|---|-----------|---------|--------|-----------|--------------|-------|
| 1 | Verification | Trust Attestation | ✅ DISCOVERED | HIGH | Is identity trustworthy? | None |
| 2 | GovernanceState | Constitutional Governance | ✅ DISCOVERED | MEDIUM-HIGH | Is transition allowed? | ADG-2, ADH-1 |
| 3 | Vote | Voting | ✅ DISCOVERED | HIGH | Is vote valid & anonymous? | D42B |
| 4 | RoleAssignment | Authorization | ⚠️ CANDIDATE | MEDIUM-LOW | Which user has role X? | ADC-1, ADC-2 |
| 5 | ReplaySession | Governance Evidence Replay | ⚠️ CANDIDATE | MEDIUM | Did replay match original? | ADGR-1 |

**Contexts with Zero Aggregates (Legitimate Non-Aggregate Patterns):**
- Eligibility (stateless evaluation)
- Audit (fire-and-forget observability)
- Results/Tallying (observed projection behavior)
- Arbitration/Legitimacy (decision record, no creation)

---

## Detailed Aggregate Specifications

### 1. Verification (DISCOVERED)

**Context:** Trust Attestation

**Responsibility:** Protect the core trust verification decision — whether an identity is trustworthy within an organization.

**Aggregate Root:** Verification

**Discovered Characteristics:**
- Observed: One active Verification per (participant, organization)
- Observed: Lifecycle states include active and revoked
- Observed: Revocation requires officer attribution
- Implementation: VoterVerification model with status tracking

**Core Invariants (Discovered):**
1. **One Active Verification Per Participant Per Organization** — Implementation enforces uniqueness constraint; observed in model design
2. **Revocation Attribution** — Observed requirement in ADR-001; revocation records officer and timestamp
3. **Verification Status Immutability** — Code review shows no UPDATE operations on verification decision after recording
4. **Attestation Consistency** — Attestation entity moves with verification status changes atomically
5. **Trust Level Progression** — UBIQUITOUS_LANGUAGE.md documents progression concept; implementation not yet examined

**Consistency Boundary (Discovered):**
- Entire Verification lifecycle (active → revoked) must be atomic
- Attestation changes are linked to verification status changes
- Revocation attribution is atomic with status change

**Entities within Aggregate:**
- Attestation (records how/why verified; entity)

**Value Objects within Aggregate:**
- Trust Level (progression state; value object)
- Revocation Reason (value object)
- Officer Attribution (officer ID, timestamp; value object)

**Confidence:** HIGH
- Evidence from multiple ARD sources (ADR-001, ADR-002)
- Implementation confirms aggregate pattern
- Invariants are clear and testable

**Unresolved Questions Affecting This Aggregate:** None

**Evidence Sources:** ADR-001, ADR-002, UBIQUITOUS_LANGUAGE.md, TRUST_CHAIN.md, VoterVerification model code review, Round 27A aggregate discovery, Round 28A challenge review

---

### 2. GovernanceState (DISCOVERED)

**Context:** Constitutional Governance

**Responsibility:** Protect the election lifecycle state and enforce constitutional preconditions for state transitions.

**Aggregate Root:** GovernanceState

**Discovered Characteristics:**
- Observed: ElectionConstitution contains explicit state transition rules
- Observed: ConstitutionalTransitionGuard enforces preconditions
- Observed: State changes gated by authorization checks
- Implementation: Lifecycle state stored in elections table

**Core Invariants (Discovered):**
1. **State Transition Determinism** — Same context always leads to same transition outcome; ConstitutionalTransitionGuard implements deterministic logic
2. **Precondition Enforcement** — Observed: all state transitions have preconditions that are validated before transition
3. **Precondition Immutability** — Constitutional rules are immutable; D30 confirmed this is intentional design
4. **Single Authority** — Only authorized officers can trigger state transitions
5. **Suspension Overlay Consistency** — Suspension can overlay active state without contradicting base state

**Consistency Boundary (Discovered):**
- Entire state transition must be atomic with precondition validation
- Suspension overlays must be atomic with base state operations
- No partial state updates observed

**Entities within Aggregate:**
- Suspension Overlay (temporary state modification; entity)

**Value Objects within Aggregate:**
- Lifecycle State (immutable state enum)
- State Transition (from → to; immutable)
- Precondition (immutable business rule)

**Tactical Modeling Questions (Not Resolved by Discovery):**
- Exact lifecycle sequence (how many states, in what order) — Election workflow varies by organization type
- One instance per election vs multiple instances — Architecture decision
- How suspension overlays integrate — Detailed design question

**Confidence:** MEDIUM-HIGH
- Strong evidence from constitutional rules and implementation
- Governance integration confirmed
- Tactical model structure remains to be designed

**Unresolved Questions Affecting This Aggregate:**
- ADG-2 (Delegated authority governance; **Type:** Governance)
- ADH-1 (Authority hierarchy governance gaps; **Type:** Governance)

**Evidence Sources:** ADR-003 (Lifecycle vs Phase), Stream 5 (preconditions), ElectionConstitution.php, ConstitutionalTransitionGuard.php, Round 22 relationship analysis, Round 27D aggregate discovery, Round 28A challenge review

---

### 3. Vote (DISCOVERED)

**Context:** Voting

**Responsibility:** Protect vote recording with anonymity, uniqueness, integrity, and verifiability guarantees.

**Aggregate Root:** Vote

**Discovered Characteristics:**
- Observed: No user_id column in votes table (anonymity protection)
- Observed: vote_hash used for uniqueness enforcement
- Observed: data_checksum for integrity protection
- Observed: receipt_hash for verifiability
- Implementation: BaseVote and Vote models implement aggregate pattern

**Core Invariants (Discovered):**
1. **Vote Anonymity** — No user_id or voter linkage exists; observed in schema and code
2. **Vote Uniqueness** — vote_hash column with unique constraint; prevents duplicate votes
3. **Vote Integrity** — data_checksum computed at recording time and immutable thereafter
4. **Vote Verifiability** — receipt_hash enables voter to verify participation
5. **Ballot Atomicity** — All candidate selections for single vote are recorded together; no partial updates observed

**Consistency Boundary (Discovered):**
- Entire vote recording (selections + hashes + checksums) is atomic operation
- Ballot selections cannot be partially updated

**Entities within Aggregate:**
- Ballot Selection (candidate selections per post; entity)

**Value Objects within Aggregate:**
- Vote Hash (uniqueness identifier; value object)
- Data Checksum (SHA256 integrity protection; value object)
- Receipt Hash (verifiability proof; value object)
- Voting Code (anonymized participation code; value object)

**Tactical Modeling Questions (Not Resolved by Discovery):**
- Relationship between Vote aggregate and Results computations — **UNRESOLVED (D39)**
- Whether Result computations belong inside Vote or Results/Tallying boundary — **UNRESOLVED (D39)**
- Verifiability guarantee ownership (Verification context or Voting context) — **UNRESOLVED (D42B)**

**Confidence:** HIGH
- Evidence from security analysis and code review
- Invariants directly observable in schema and implementation
- Pattern is mature and stable

**Unresolved Questions Affecting This Aggregate:**
- D42B (Verifiability guarantee scope; **Type:** Discovery) — Does Verification or Voting own the guarantee?

**Evidence Sources:** Stream 3 (voting investigation), BaseVote.php, Vote.php, ADR_20260203 (Voting Security), Round 22 relationship analysis, Round 24B/C literature review, Round 27E aggregate discovery, Round 28A challenge review

---

### 4. RoleAssignment (CANDIDATE AGGREGATE)

**Context:** Authorization

**Responsibility:** [Candidate] Protect role assignments for users within election contexts.

**Aggregate Root:** [Candidate] RoleAssignment

**Observed Characteristics (Candidate Evidence):**
- Observed: Authorization enforces role-based access control
- Observed: ConstitutionalTransitionGuard references officer roles (chief, deputy)
- Observed: Role changes require officer action
- Implementation: Role references exist in authorization code

**Observed Candidate Invariants (Provisional - Requires ADC-1, ADC-2 Resolution):**
1. **Role Assignment Uniqueness** — Pattern observed: role assignments appear to be per-user, per-election
2. **Role Scope** — Observed: roles are election-scoped, not global organization roles
3. **Officer Attribution** — Assumed: role changes require officer attribution (not yet confirmed in code)
4. **Role Exclusivity** — **Unresolved (ADC-1)** — Do some roles exclude others? Discovery did not establish
5. **Temporal Validity** — **Unresolved (ADC-2)** — Do roles have start/end dates? Not observed in current implementation

**Consistency Boundary (Candidate - Not Yet Confirmed):**
- Candidate: entire role assignment lifecycle must be atomic

**Entities within Aggregate (Candidate):**
- (None confirmed)

**Value Objects within Aggregate (Candidate):**
- Role (assigned role; value object)
- Assignment Status (candidate enum: assigned, active, revoked)
- Officer Attribution (candidate: who assigned, when)

**Confidence:** MEDIUM-LOW
- Core pattern recognized but incomplete
- Candidate classification appropriate until ADC-1 and ADC-2 resolved
- May require redesign pending discovery

**Unresolved Questions Blocking Aggregate Finalization:**
- ADC-1 (Role uniqueness and exclusivity rules; **Type:** Model Refinement) — Are some roles mutually exclusive?
- ADC-2 (Temporal validity and revocation authority; **Type:** Model Refinement) — Do roles have start/end dates? Who can revoke?

**Evidence Sources:** ConstitutionalTransitionGuard authorization patterns, Round 22 relationship analysis, Round 27C aggregate discovery (candidate status), Round 28A challenge review

---

### 5. ReplaySession (CANDIDATE AGGREGATE)

**Context:** Governance Evidence Replay

**Responsibility:** [Candidate] Protect evidence replay sessions for verification of governance decision outcomes.

**Aggregate Root:** [Candidate] ReplaySession

**Observed Characteristics (Candidate Evidence):**
- Observed: GovernanceReplayService orchestrates replay operations
- Observed: ReplayCertification records exist as outcome
- Observed: Evidence envelopes seal data
- Implementation: Replay classes exist in codebase

**Observed Candidate Lifecycle (Provisional - Requires ADGR-1 Resolution):**
- Possible states observed: initialized, assertions_collected, certified
- Note: Finalization pending governance authority resolution (ADGR-1)

**Observed Candidate Invariants (Provisional - Requires ADGR-1 Resolution):**
1. **Session Immutability Once Certified** — Assumed pattern; not yet confirmed in code
2. **Assertion Determinism** — Same evidence should produce same assertions; determinism claimed but not verified
3. **Evidence Seal Integrity** — Sealing observed; integrity property not yet confirmed
4. **Divergence Detection** — Divergence detection mechanism exists; reliability not yet established

**Consistency Boundary (Candidate - Not Yet Confirmed):**
- Candidate: entire replay session lifecycle must be atomic
- Candidate: certification is final

**Entities within Aggregate (Candidate):**
- Replay Assertion (candidate entity)
- Replay Certification (candidate entity)

**Value Objects within Aggregate (Candidate):**
- Evidence Envelope (immutable sealed evidence; value object)
- Replay Fingerprint (candidate: execution signature)
- Divergence Type (candidate: divergence classification)

**Confidence:** MEDIUM
- Core replay pattern observed
- Governance deployment unresolved
- Provisional status appropriate

**Unresolved Questions Blocking Aggregate Finalization:**
- ADGR-1 (Replay session governance and operational authority; **Type:** Model Refinement) — How are replay sessions triggered? Who authorizes replays? What happens after certification?

**Evidence Sources:** Stream 4 (audit investigation), ReplayEvidenceEnvelope, GovernanceReplayService, GovernanceDecisionSnapshot, Round 22 relationship analysis, Round 27G aggregate discovery (candidate status), Round 28A challenge review

---

## Contexts with Zero Aggregates (Legitimate Non-Aggregate Patterns)

### Eligibility Context

**Why No Aggregate (Discovered Pattern):**

Eligibility decisions are computed at action time without stored state.

**Evidence:**
- ADR-002: Eligibility is evaluation, not state
- Stream 5: Eligibility evaluated for each action, not cached
- No eligibility mutation operations observed
- ParticipationEligibilityEvidence is immutable snapshot created for audit, not transactional state

**Pattern Classification:** Stateless Domain Service
- Eligibility evaluation as pure function
- No consistency boundary needed
- No invariant requires aggregate protection

**Source Evidence:** ADR-002, Stream 5, Round 27B aggregate discovery, Round 28A challenge Q16

---

### Audit Context

**Why No Aggregate (Discovered Pattern):**

Audit is fire-and-forget observability; no feedback to operational contexts.

**Evidence:**
- Stream 4: Audit confirmed as append-only with no feedback loop
- ElectionAuditLog has no UPDATE or DELETE operations
- Audit never affects business outcomes
- No context depends on audit state for decisions

**Pattern Classification:** Reporting/Observability Context
- Append-only logging
- No consistency boundary
- No business decision ownership

**Source Evidence:** Stream 4, Round 22 relationship analysis, Round 27F aggregate discovery, Round 28A challenge Q16

---

### Results/Tallying Context

**Why No Aggregate (Discovered Pattern):**

Results are observed as derived projection from Vote data.

**Evidence:**
- Projection Test (Round 25): All Results state reconstructable from Vote data without loss
- Stream 3: Vote.createResultsFromCandidates() triggers result computation
- No update-after-creation operations on results observed
- ResultController reads results by recalculating from votes

**Unresolved:** 
- D39: Counting state meaning unclear — does counting state have business meaning beyond publication gate?
- Whether result computations belong inside Voting aggregate or separate Results context — **UNRESOLVED**

**Observed Implementation:** 

Vote recording currently triggers result-related computation. Discovery has not established whether this represents aggregate ownership, projection maintenance, or implementation convenience.

- Reconstructable at any time from Vote data
- No independent aggregate ownership observed
- D39 unresolved

**Tactical Design Question (Not Discovery):**
Whether future electoral formula variability (Round 24G noted: FPTP, STV, MMP, D'Hondt) could create independent Results aggregate — deferred to future discovery if formula variability is implemented.

**Source Evidence:** Stream 3, Projection Test (Round 25), Round 27I aggregate discovery, Round 28A challenge Q16, Round 24G electoral formula notes

---

### Arbitration/Legitimacy Context

**Why No Aggregate (Discovered Pattern):**

Constitutional Decision is a Decision Record — evaluates predetermined rules without creating business truth.

**Evidence:**
- Stream 6B: ConstitutionalArbitrationKernel applies policy; does not own decision outcome
- ConstitutionalDecision records legitimacy status but doesn't originate legitimacy
- Legitimacy originating from Governance temporal windows (StateWindow, TemporalGovernanceState)
- No mutation of legitimacy by Arbitration — evaluation only

**Unresolved (Strategic Questions):**
- D35: What are consequences when legitimacy = EXPIRED? (Reversal, blocking, advisory?)
- D36: Who is authorized to invoke ConstitutionalArbitrationKernel?
- D37: How is legitimacy determination enforced?

**Pattern Classification:** Decision Record
- Records application of predetermined constitutional rules
- No aggregate state ownership
- Evaluation mechanism, not decision authority

**Note:** GEO-3.2+ planned extension for administrative signals (suspension, emergency declarations) may change this classification; currently deferred.

**Source Evidence:** Round 18 Governance Review, ConstitutionalArbitrationKernel.php, LegitimacyEvaluator.php, GovernanceLegitimacy.php, Round 27H aggregate discovery, Round 28A challenge Q16

---

## Aggregate Coverage Assessment

| Category | Status | Assessment |
|----------|--------|------------|
| **Discovered Aggregates** | 3 (Verification, GovernanceState, Vote) | All three have HIGH or MEDIUM-HIGH confidence |
| **Candidate Aggregates** | 2 (RoleAssignment, ReplaySession) | Both marked provisional; unresolved questions documented |
| **Legitimate Non-Aggregates** | 4 (Eligibility, Audit, Results, Arbitration) | Correct application of DDD discipline |
| **All Discovered Invariants** | Cataloged | 100% of currently discovered invariants have protective mechanism |
| **Confirmed Cross-Context Issues** | 0 | No violations observed in current evidence |

**Important:** Coverage statistics are qualified by open debts (D39, D42B, ADGR-1, ADC-1, ADC-2). These debts do not invalidate current aggregate findings but may require adjustments during model refinement.

---

## Key DDD Discoveries (Evidence-Based)

### 1. Important Domain Concepts ≠ Aggregates

**Discovery:** Many strategically important concepts do not require aggregate boundaries:
- **Eligibility** is critical for fairness, but computed stateless
- **Audit** is essential for accountability, but fire-and-forget
- **Arbitration** is important for legitimacy, but evaluates rather than creates

**Implication:** DDD discipline requires protecting business invariants, not creating aggregates for every important concept.

### 2. Projection Pattern Legitimacy

**Discovery:** Results/Tallying exhibits pure projection behavior — all state reconstructable from Vote source.

**Implication:** Not all domain concepts require independent aggregates; derived projections are legitimate DDD patterns.

### 3. Candidate Aggregates Are Known Unknowns

**Discovery:** RoleAssignment and ReplaySession are recognized as aggregate candidates but remain provisional pending model refinement.

**Implication:** Incomplete knowledge is documented explicitly, not hidden. Provisional aggregates support future refinement without blocking current model.

---

## Remaining Discovery Debt by Category

| Debt | Type | Aggregate Affected | Priority | Resolution Phase |
|------|------|-------------------|----------|------------------|
| D39 | Discovery | Results/Tallying | MEDIUM | Model Refinement |
| D42B | Discovery | Voting | MEDIUM | Model Refinement |
| ADGR-1 | Model Refinement | ReplaySession | MEDIUM | Future Design |
| ADC-1 | Model Refinement | RoleAssignment | MEDIUM | Future Design |
| ADC-2 | Model Refinement | RoleAssignment | MEDIUM | Future Design |
| ADG-2 | Governance | GovernanceState | MEDIUM | Governance Review |
| ADH-1 | Governance | GovernanceState, Authorization, Arbitration | MEDIUM | Governance Review |

---

**Round 29 — Aggregate Catalog COMPLETE**

**Evidence Discipline:** All aggregate claims are evidence-based. Tactical modeling decisions are deferred. Unresolved questions are explicit.

**Next Deliverables:**
1. Round29_Decision_Ownership_Catalog.md
2. Round29_Invariant_Catalog.md
3. Round29_Remaining_Uncertainty_Register.md

(Architecture Readiness Assessment deferred until after synthesis completion and ARB acceptance.)

