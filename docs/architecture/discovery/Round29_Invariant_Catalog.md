# Round 29 — Invariant Catalog

**Date:** 2026-06-08

**Phase:** Strategic-to-Tactical Synthesis

**Status:** Authoritative Domain Invariant Inventory (Final)

**Purpose:** Catalog all domain invariants discovered across the 9 accepted bounded contexts. A domain invariant is a business rule that must always be true within a consistency boundary. This catalog reports discovered invariants only; implementation details and architectural enforcement mechanisms are not included.

**Governance Rule:** Every entry is a genuine domain invariant (DISCOVERED or PROVISIONAL). Implementation relationships, dependency rules, and architectural observations are cataloged separately.

---

## Trust Attestation Context Invariants

**TA-1: One Active Verification Per Participant Per Organization**

**Classification:** DISCOVERED

**Statement:** At any point in time, a participant can have at most one active (non-revoked) Verification in an organization.

**Evidence:** 
- VoterVerification model implements this rule
- ADR-001 defines verification as singular decision per identity
- Revocation is the only valid transition from active state

**Protected By:** Verification aggregate

**Confidence:** HIGH

---

**TA-2: Revocation Must Be Attributed to an Officer**

**Classification:** DISCOVERED

**Statement:** Any verification revocation must record which officer revoked it and when.

**Evidence:**
- VoterVerification model includes officer_id and revoked_at columns
- Attestation entity within Verification stores officer attribution
- No revocation without officer record observed

**Protected By:** Verification aggregate

**Confidence:** HIGH

---

**TA-3: Verification Decisions Are Append-Only**

**Classification:** DISCOVERED

**Statement:** Verification decisions are append-only; an active verification may be revoked, but historical decisions cannot be altered or deleted.

**Evidence:**
- Code review shows only active → revoked transition is possible
- No UPDATE operations on verification status observed
- Historical decisions are immutable records

**Protected By:** Verification aggregate

**Confidence:** HIGH

---

## Eligibility Context

**Context Characteristic 1: Eligibility Is Computed at Action Time**

**Classification:** DISCOVERED

**Statement:** Eligibility status for a participant is evaluated fresh at the moment of action, not stored or cached.

**Evidence:**
- Stream 5 investigation confirmed no stored eligibility state
- ADR-002 defines Eligibility as evaluation, not state
- ParticipationEligibilityEvidence created for audit only

**Note:** This describes when eligibility is evaluated (implementation strategy), not what must be true about eligibility (business invariant). The timing is a context characteristic, not a domain invariant.

---

**EL-1: Eligibility Evaluation Is Deterministic**

**Classification:** DISCOVERED

**Statement:** Given the same participant, same process, same rules, eligibility evaluation always produces the same result.

**Evidence:**
- Policy-driven evaluation observed
- No non-deterministic factors in eligibility rules identified
- Evaluation is pure computation

**Protected By:** Eligibility evaluation service

**Confidence:** HIGH

---

## Authorization Context Invariants

**AU-1: Authorization Resolution Is Deterministic**

**Classification:** DISCOVERED

**Statement:** Given the same user, same action, same context, the authorization decision is always the same.

**Evidence:**
- ADR-004 documents deterministic resolver pattern
- ConstitutionalTransitionGuard implements deterministic logic
- Central authority applies rules without discretion

**Protected By:** Authorization context

**Confidence:** HIGH

---

**AU-2: Authorization Decisions Must Be Made by Central Authority**

**Classification:** DISCOVERED

**Statement:** All authorization decisions are made by the central authority.

**Evidence:**
- Authorization checks are centralized in single resolver
- No privilege escalation logic observed
- No code paths circumvent authority

**Protected By:** Authorization context

**Confidence:** HIGH

---

**AU-3: Role Assignments Are Per-Election** (PROVISIONAL)

**Classification:** PROVISIONAL

**Statement:** Role assignments are scoped to specific elections, not global across organization.

**Evidence:**
- ConstitutionalTransitionGuard references officer roles per election context
- Current implementation patterns suggest per-election scoping

**Protected By:** RoleAssignment candidate aggregate

**Confidence:** MEDIUM-LOW

**Unresolved:**
- ADC-1 (Role exclusivity rules not established)
- ADC-2 (Temporal validity not established)

---

## Constitutional Governance Context Invariants

**CG-1: Lifecycle State Transitions Are Deterministic**

**Classification:** DISCOVERED

**Statement:** Given the same precondition state, a lifecycle transition always leads to the same resulting state.

**Evidence:**
- ConstitutionalTransitionGuard implements deterministic precondition checks
- ElectionConstitution defines fixed transition rules (immutable)
- No non-deterministic factors observed

**Protected By:** GovernanceState aggregate

**Confidence:** MEDIUM-HIGH

**Unresolved:**
- ADG-2, ADH-1 (Governance authority clarification needed)

---

**CG-2: State Transitions Require Valid Preconditions**

**Classification:** DISCOVERED

**Statement:** A lifecycle state transition is only valid if all preconditions are satisfied; invalid transitions are impossible.

**Evidence:**
- ConstitutionalTransitionGuard validates preconditions before allowing transition
- Precondition logic in ElectionConstitution enforces rules
- No state transitions occur without precondition satisfaction

**Protected By:** GovernanceState aggregate

**Confidence:** HIGH

---

**Context Characteristic 2: Preconditions Are Centralized**

**Classification:** DISCOVERED

**Statement:** All lifecycle transition preconditions are defined in a central location (ElectionConstitution.php) and cannot be bypassed.

**Evidence:**
- ElectionConstitution.php is single source of truth for rules
- D30 confirmed rules-in-code is intentional design

**Note:** The centralization is an architectural design choice, not a domain invariant. The true invariant is that preconditions must be satisfied (what), not that they are centralized (how).

---

**CG-3: Suspension Overlay Cannot Contradict Base Lifecycle** (PROVISIONAL)

**Classification:** PROVISIONAL

**Statement:** Suspension overlays can temporarily modify active state but cannot violate the base lifecycle structure.

**Evidence:**
- Suspension concept mentioned in governance artifacts
- Overlay pattern observed in implementation

**Protected By:** GovernanceState aggregate

**Confidence:** MEDIUM-HIGH

**Unresolved:**
- ADH-1 (Authority to impose suspension not finalized)

---

## Audit Context — Context Characteristic

**Characteristic 3: Audit Is Append-Only**

**Classification:** DISCOVERED

**Statement:** Audit log entries are appended only; no UPDATE or DELETE operations on recorded events.

**Evidence:**
- Stream 4 investigation confirmed fire-and-forget pattern
- ElectionAuditLog schema has no update/delete operations
- Logs are immutable historical records

**Note:** This is a context characteristic, not a domain invariant. It describes the architectural pattern of the Audit context, not a business rule that must hold within a consistency boundary.

**Confidence:** HIGH

---

## Voting Context Invariants

**VO-1: A Vote Must Not Be Linkable to the Voter Who Cast It**

**Classification:** DISCOVERED

**Statement:** The business rule that votes are anonymous; a vote cannot be traced back to the voter who cast it.

**Evidence:**
- Schema inspection confirms no user_id in votes table
- ADR_20260203 confirms anonymity as business requirement
- No linkage mechanism observed anywhere in code

**Protected By:** Vote aggregate

**Confidence:** HIGH

---

**VO-2: Recorded Vote Content Must Be Tamper Evident**

**Classification:** DISCOVERED

**Statement:** Vote data integrity is protected; any tampering is detectable.

**Evidence:**
- data_checksum (SHA256) computed at vote recording
- Checksum immutable after recording
- Stream 3 confirmed integrity strategy

**Protected By:** Vote aggregate

**Confidence:** HIGH

**Note:** Implementation uses checksums; other mechanisms (signatures, hash chains, ZK proofs) could fulfill this invariant while preserving the business rule.

---

**VO-3: Receipt Hash Is Generated and Stored**

**Classification:** DISCOVERED

**Statement:** Each vote generates and stores a receipt hash at the time of recording.

**Evidence:**
- receipt_hash column exists in votes table
- Hash generated at vote recording time
- Immutable after generation

**Protected By:** Vote aggregate

**Confidence:** HIGH

**Note:** Regarding who owns the guarantee that votes are verifiable to voters — see D42B in Uncertainty Register. Receipt hash existence (Vote) vs verifiability guarantee ownership (UNRESOLVED).

---

**VO-4: Ballot Selections Are Recorded Atomically**

**Classification:** DISCOVERED

**Statement:** All candidate selections for a single vote are recorded in one atomic operation; partial ballot updates are impossible.

**Evidence:**
- Ballot selections stored as JSON in single vote record
- No intermediate states observed
- Vote creation is atomic transaction

**Protected By:** Vote aggregate

**Confidence:** MEDIUM

**Note:** Atomic transaction behavior proves implementation atomicity. The business invariant (complete voter choice) is supported but would benefit from additional evidence.

---

## Governance Evidence Replay Context Invariants

**GR-1: Evidence Seals Are Immutable** (PROVISIONAL)

**Classification:** PROVISIONAL

**Statement:** Once evidence is sealed in an Evidence Envelope, the seal cannot be modified.

**Evidence:**
- EvidenceEnvelope pattern observed
- Sealing mechanism exists
- Immutability claimed in implementation comments

**Protected By:** ReplaySession candidate aggregate

**Confidence:** MEDIUM

**Unresolved:**
- ADGR-1 (Replay session governance not finalized)

---

**GR-2: Replay Outcomes Are Deterministic** (PROVISIONAL - WEAK EVIDENCE)

**Classification:** PROVISIONAL

**Statement:** Given the same evidence input, replaying a governance decision always produces the same outcome.

**Evidence:**
- Deterministic replay claimed in design comments
- No observed non-deterministic factors identified (requires deeper code analysis)

**Protected By:** ReplaySession candidate aggregate

**Confidence:** LOW-MEDIUM

**Note:** Evidence is limited to design claims, not direct code verification. Confidence is lower than other invariants pending stronger evidence.

**Unresolved:**
- ADGR-1 (Replay session governance not finalized)

---

## Arbitration / Legitimacy Context Invariants

**AR-1: Constitutional Rules Are Applied Deterministically** (PROVISIONAL)

**Classification:** PROVISIONAL

**Statement:** Constitutional decision review applies predetermined rules deterministically without discretion.

**Evidence:**
- ConstitutionalArbitrationKernel applies rules without discretion
- LegitimacyEvaluator implements deterministic logic
- Rules are immutable

**Protected By:** Arbitration context

**Confidence:** MEDIUM

**Unresolved:**
- D35, D36, D37 (Governance consequences unresolved)

---

## Invariant Confidence Summary

| ID | Invariant | Context | Classification | Confidence |
|---|-----------|---------|-----------------|-----------|
| TA-1 | One Active Verification | Trust Attestation | DISCOVERED | HIGH |
| TA-2 | Revocation Attribution | Trust Attestation | DISCOVERED | HIGH |
| TA-3 | Append-Only Decisions | Trust Attestation | DISCOVERED | HIGH |
| EL-1 | Eligibility Determinism | Eligibility | DISCOVERED | HIGH |
| AU-1 | Authorization Determinism | Authorization | DISCOVERED | HIGH |
| AU-2 | Central Authority | Authorization | DISCOVERED | HIGH |
| AU-3 | Roles Per-Election | Authorization | PROVISIONAL | MEDIUM-LOW |
| CG-1 | Transition Determinism | Constitutional Governance | DISCOVERED | MEDIUM-HIGH |
| CG-2 | Precondition Validation | Constitutional Governance | DISCOVERED | HIGH |
| CG-3 | Suspension Consistency | Constitutional Governance | PROVISIONAL | MEDIUM-HIGH |
| VO-1 | Vote Anonymity | Voting | DISCOVERED | HIGH |
| VO-2 | Tamper Evidence | Voting | DISCOVERED | HIGH |
| VO-3 | Receipt Hash | Voting | DISCOVERED | HIGH |
| VO-4 | Ballot Atomicity | Voting | DISCOVERED | MEDIUM |
| GR-1 | Evidence Seal Immutability | Governance Evidence Replay | PROVISIONAL | MEDIUM |
| GR-2 | Replay Determinism | Governance Evidence Replay | PROVISIONAL | LOW-MEDIUM |
| AR-1 | Deterministic Rule Application | Arbitration | PROVISIONAL | MEDIUM |

**Summary:**
- **DISCOVERED:** 11 invariants (HIGH or MEDIUM-HIGH confidence)
- **PROVISIONAL:** 6 invariants (MEDIUM or lower confidence, pending debt resolution)
- **Context Characteristics:** 3 (architectural patterns, not domain invariants)
- **Total Domain Invariants:** 17

---

## Key DDD Insight

The strongest domain invariants are those protecting core business decisions:

- **Verification:** Identity cannot have multiple active states; revocation requires attribution
- **Authorization:** Resolution is deterministic; all decisions pass through central authority
- **Governance:** State transitions are deterministic; preconditions are enforced
- **Voting:** Votes are anonymous, integrity-protected, and atomic

These 11 DISCOVERED invariants represent the heart of the domain model.

The six PROVISIONAL invariants (AU-3, CG-3, GR-1, GR-2, AR-1) remain pending governance debt resolution (ADC-1, ADC-2, ADGR-1, D35, D36, D37).

---

**Round 29 — Invariant Catalog FINAL**

**17 Domain Invariants Cataloged**
- 11 DISCOVERED (HIGH or MEDIUM-HIGH confidence)
- 6 PROVISIONAL (MEDIUM or lower confidence, pending debt resolution)

**3 Context Characteristics Documented** (architectural patterns, not domain invariants)

**Status:** APPROVED

**Next:** Round29_Remaining_Uncertainty_Register.md

