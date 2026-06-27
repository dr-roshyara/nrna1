# Round 23 — Candidate Context Evidence Assessment

**Date:** 2026-06-07

**Phase:** Strategic DDD Candidate Context Evidence Assessment (Step 3 Evidence Review)

**Status:** Evidence Assessment Complete

---

## 1. Purpose

Present evidence for ARB deliberation on each candidate bounded context's readiness for acceptance. This document provides evidence assessments — it does NOT make final ARB decisions. The ARB will decide acceptance in Round 24.

---

## 2. Inputs Considered

| Round | Artifact | Purpose |
|-------|----------|---------|
| 17 | Repository Discovery (Streams 1-6B, 3) | Implementation evidence |
| 18 | Governance Clarification | ADR + governance evidence |
| 19 | Candidate Context Discovery | Initial candidates |
| 20 | ARB Boundary Review + Decision | Boundary evidence + pre-decision |
| 21 | Context Mapping | Relationship inventory |
| 22 | Relationship Strength Analysis | Relationship classification |
| — | Discovery Debt Register (D1-D42) | Known unknowns |
| — | Hypothesis Register (H1-H23) | Active hypotheses |

---

## 3. Assessment Criteria

Each candidate is evaluated against four criteria:

| Criterion | Question | Source |
|-----------|----------|--------|
| Decision Ownership | Does this candidate own a unique decision that no other candidate owns? | Rounds 19-22 |
| Language Boundaries | Does this candidate have distinct, non-overlapping ubiquitous language? | Rounds 19-21 |
| Relationship Strength | Are relationships with other candidates loose enough to justify separation? | Rounds 21-22 |
| Governance Constraints | Are there unresolved governance questions that could affect boundaries? | Round 18 |

**Assessment categories (for ARB deliberation, not final decisions):**
- **Evidence Supports Acceptance** — All criteria met with HIGH confidence
- **Evidence Supports Provisional Acceptance** — Most criteria met, some uncertainty remains
- **Provisional Evidence** — Concept boundaries clear, but operational or governance questions remain
- **Unresolved** — Current evidence insufficient to determine bounded context status

---

## 3. Candidate Evidence Assessments

### C1: Trust Attestation

**Assessment: Evidence Supports Acceptance**

| Criterion | Assessment | Evidence |
|-----------|-----------|----------|
| Decision Ownership | ✅ Owns verification decisions (is this identity trustworthy?) that no other candidate owns | ADR-001, ADR-002, Rounds 19-22 |
| Language Boundaries | ✅ Distinct language: verified, trust level, attestation, officer, evidence, revocation | UBIQUITOUS_LANGUAGE.md, Rounds 20-21 |
| Relationship Strength | ✅ Strong separation from all neighbors (R1: supports separation) | Round 22 R1 analysis |
| Governance Constraints | ✅ None — trust model is well-documented across 5 governance sources | Round 18 governance review |

**Conditions:** None.

**Rationale:** Trust Attestation is the most well-evidenced candidate in the entire corpus. Five governance sources (ADR-001/002/003, UBIQUITOUS_LANGUAGE, TRUST_CHAIN) independently confirm its separation. Decision ownership is unique and autonomous. No governance constraints affect its boundary.

---

### C2: Eligibility

**Assessment: Evidence Supports Acceptance**

| Criterion | Assessment | Evidence |
|-----------|-----------|----------|
| Decision Ownership | ✅ Owns participation eligibility decisions (is this participant eligible for this process?) | ADR-002, Round 19-20 |
| Language Boundaries | ✅ Distinct language: eligible, membership, voting_rights, enrolled, fee_status | Round 19, TRUST_CHAIN |
| Relationship Strength | ✅ Strong separation from neighbors (R1, R2: both support separation) | Round 22 R1-R2 analysis |
| Governance Constraints | ⚠️ Low — eligibility rules may be influenced by organizational governance; does not affect boundary | Round 18 |

**Conditions:** None. Eligibility rules source (D22 partially resolved) does not affect boundary separation.

**Rationale:** ADR-002 explicitly defines Eligibility as a separate decision from Verification and Authorization. Eligibility is computed at action time, process-specific, and independently evaluated. The compositional dependency (Verified → Eligible → Authorized) strengthens rather than weakens the boundary.

---

### C3: Authorization

**Assessment: Evidence Supports Acceptance**

| Criterion | Assessment | Evidence |
|-----------|-----------|----------|
| Decision Ownership | ✅ Owns capability decisions (is this user allowed to perform this action?) | ADR-001 Const., ADR-004, Rounds 19-20 |
| Language Boundaries | ✅ Distinct language: authorized, role, permission, capability, allowed, denied | ADR-001, Round 19 |
| Relationship Strength | ✅ Strong separation from neighbors (R2, R3, R5: all support separation) | Round 22 R2-R5 analysis |
| Governance Constraints | ✅ None — authorization model is well-documented across multiple ADRs | Round 18 |

**Conditions:** None.

**Rationale:** Authorization is centralized, deterministic, and independently enforceable. The backend is the sole authority with no bypass mechanisms. Multiple ADRs document the design rationale.

---

### C4: Constitutional Governance / Lifecycle

**Assessment: Evidence Supports Acceptance**

| Criterion | Assessment | Evidence |
|-----------|-----------|----------|
| Decision Ownership | ✅ Owns lifecycle state and transition decisions (what states exist, which transitions are allowed) | ADR-003, Stream 5, Rounds 19-20 |
| Language Boundaries | ✅ Distinct language: constitution, state, transition, lifecycle, preconditions, suspension | Stream 5, Round 19 |
| Relationship Strength | ✅ Moderate-to-strong separation from neighbors (R3, R4, R8: all support separation) | Round 22 R3-R4 analysis |
| Governance Constraints | ✅ None — rules-in-code is intentional design (D30 resolved); suspension authority pending (D26) but does not affect boundary | Round 18 |

**Conditions:** None.

**Rationale:** Constitutional Governance defines the election lifecycle independently of any specific election. Rules are centralized, enforcement is mandatory, and the state machine governs all progression.

---

### C5: Audit (Operational)

**Assessment: Evidence Supports Acceptance**

| Criterion | Assessment | Evidence |
|-----------|-----------|----------|
| Decision Ownership | ✅ Owns operational recording decisions (what to log, when to rotate) independently | Stream 4, Rounds 19-20 |
| Language Boundaries | ✅ Distinct language: audit, log, event, action, record, trace, old_values, new_values | Stream 4, Round 19 |
| Relationship Strength | ✅ Strong separation from all neighbors (R7, R8, R9: all observability, fire-and-forget) | Round 22 R7-R9 analysis |
| Governance Constraints | ✅ None — audit is independently functioning with no business coupling | Stream 4 |

**Conditions:** None.

**Rationale:** Audit is the strongest separation case in the map. It receives from all contexts but affects none. Fire-and-forget pattern ensures no coupling. Stream 4 explicitly confirmed separation from governance replay.

---

### C6: Voting

**Assessment: Evidence Supports Provisional Acceptance**

| Criterion | Assessment | Evidence |
|-----------|-----------|----------|
| Decision Ownership | ✅ Owns vote recording decisions (is this vote valid, anonymous, and correctly checksummed?) — boundary with Verification unresolved | Stream 3, ADR_20260203, Rounds 19-20 |
| Language Boundaries | ✅ Distinct language: vote, cast, ballot, receipt, checksum, participation proof | Stream 3, Round 19 |
| Relationship Strength | ✅ Strong separation from most neighbors (R4, R5, R7: support separation). R6 suggests merger with Results. | Round 22 R4-R7 analysis |
| Governance Constraints | ⚠️ HIGH — D42B (verifiability guarantee) may affect whether receipt verification belongs to Voting or a separate Verification context | Round 18, D42B |

**Provisional Considerations:**
1. Boundary with Verification (receipt verification, participation proof) remains unresolved pending D42B
2. Relationship with Results/Tallying remains unresolved pending D39
3. Voting is a clear domain concept — the uncertainty is about its external boundaries, not its internal existence

**Rationale:** Voting is a clear domain concept with strong evidence, unique decision ownership, and an explicit architectural mandate (vote anonymity). The uncertainty is about what belongs inside Voting versus outside, not about whether Voting exists as a context.

---

### C7: Results/Tallying

**Assessment: Evidence Supports Provisional Acceptance (with merger consideration)**

| Criterion | Assessment | Evidence |
|-----------|-----------|----------|
| Decision Ownership | ❌ No unique decision observed within examined evidence — results are derived from Vote data | Stream 3, Round 22 R6 analysis |
| Language Boundaries | ⚠️ Partial — shares "candidate" and "post" vocabulary with Voting | Round 19 |
| Relationship Strength | ⚠️ Boundary-Collapse Indicator — synchronous coupling, regenerable data, no independent decisions observed | Round 22 R6 (strongest merger signal) |
| Governance Constraints | ⚠️ MEDIUM — D39 (counting state meaning) unresolved; constitutional counting state may or may not imply independent business activity | ADR-003, D39 |

**Provisional Considerations:**
1. Strong merger candidate with Voting — evidence currently suggests possible merger; ARB deliberation required pending D39 resolution
2. If D39 determines counting has no independent business activity, merger with Voting is supported by current evidence
3. If D39 determines counting is a distinct domain activity (e.g., manual verification period), Results may remain separate

**Rationale:** Results/Tallying has the weakest evidence for independence of any candidate assessed. Evidence currently suggests Results/Tallying may not possess independent decision ownership within examined implementation. Further clarification of D39 (counting state meaning) may strengthen or weaken the case for separation. The constitutional "counting" state remains the primary open consideration for independence.

---

### C8: Governance Evidence Replay

**Assessment: Provisional Evidence**

| Criterion | Assessment | Evidence |
|-----------|-----------|----------|
| Decision Ownership | ✅ Owns evidence integrity verification decisions | Stream 4, Rounds 19-20 |
| Language Boundaries | ✅ Distinct language: replay, evidence, envelope, seal, hash, certification, divergence | Stream 4, Round 19 |
| Relationship Strength | ✅ Strong separation from Audit (R8 confirmation) | Round 22 R8 analysis |
| Governance Constraints | ⚠️ Operational path not observed — Phase 6 deferral, D36 invocation unresolved | Round 18, D36 |

**Rationale:** Governance Evidence Replay owns unique decisions (evidence integrity verification, replay certification, divergence detection) that no other candidate owns. Its boundary is well-defined. Its operational status is unresolved — but operational status is deployment information, not a strategic boundary consideration. Evidence supports provisional acceptance for the domain concept. Operational activation belongs to implementation planning.

**Consideration for ARB:** A bounded context is defined by ownership of unique domain knowledge and decisions, not by whether it is currently being called operationally.

**Consideration for ARB:** A bounded context is defined by ownership of unique domain knowledge and decisions, not by whether it is currently being called operationally.

---

### C9: Arbitration/Legitimacy

**Assessment: Provisional Evidence**

| Criterion | Assessment | Evidence |
|-----------|-----------|----------|
| Decision Ownership | ✅ Owns constitutional validity decisions | Stream 6B, Rounds 19-20 |
| Language Boundaries | ✅ Distinct language: arbitration, legitimacy, authority, conflict, resolution, precedent, doctrine | Stream 6B, Round 19 |
| Relationship Strength | ⚠️ Boundary unresolved — may be separate context or Governance subdomain | Round 22 R10 analysis |
| Governance Constraints | ⚠️ D35, D36, D37 unresolved; enforcement unknown | D35, D36, D37 |

**Rationale:** Arbitration owns unique decisions (constitutional validity determination, authority conflict resolution, legitimacy evaluation) that no other candidate owns. Its operational invocation path is unresolved (D36), and legitimacy consequences are unknown (D35, D37). However, the decision ownership exists regardless of operational status. Evidence supports provisional acceptance pending boundary resolution — whether it is a separate context or a Governance subdomain.

**Consideration for ARB:** The conceptual distinction (define rules vs. evaluate decisions against rules) is meaningful. Whether this separation warrants distinct bounded contexts is an ARB judgment call.

---

### C10: Challenge/Dispute Handling

**Assessment: Unresolved**

**Classification:** Distributed Capability (Outcome F from Stream 6A)

| Criterion | Assessment | Evidence |
|-----------|-----------|----------|
| Decision Ownership | ❌ No unique decision observed — function emerges from Arbitration + Governance Evidence Replay + Governance | Stream 6A, Rounds 19-20 |
| Language Boundaries | ❌ No distinct vocabulary — relies on arbitration, replay, and verification language | Stream 6A |
| Relationship Strength | ❌ Low cohesion — distributed across multiple candidates with no single owner | Round 22 |
| Governance Constraints | ⚠️ Challenge initiation may be organizational — D33 unresolved | D33 |

**Rationale:** Current evidence does not support bounded context status. No unique decision ownership, no distinct vocabulary, and low cohesion were observed. Challenge/Dispute may be a distributed capability (Outcome F) or an organizational process operating outside software. However, evidence does not rise to the level of rejection — classification remains unresolved, not rejected.

---

## 4. Evidence Summary

| Candidate | Assessment | Confidence | Key Consideration for ARB |
|-----------|-----------|------------|--------------------------|
| C1 — Trust Attestation | Evidence Supports Acceptance | HIGH | None |
| C2 — Eligibility | Evidence Supports Acceptance | HIGH | None |
| C3 — Authorization | Evidence Supports Acceptance | HIGH | None |
| C4 — Constitutional Governance | Evidence Supports Acceptance | HIGH | None |
| C5 — Audit (Operational) | Evidence Supports Acceptance | HIGH | None |
| C6 — Voting | Evidence Supports Provisional Acceptance | HIGH | Pending D42B boundary clarification |
| C7 — Results/Tallying | Evidence Supports Provisional Acceptance | MEDIUM | Pending D39 merger decision |
| C8 — Governance Evidence Replay | Provisional Evidence | MEDIUM | Operational status vs. domain ownership question |
| C9 — Arbitration/Legitimacy | Provisional Evidence | MEDIUM | D35, D36, D37; boundary with Governance |
| C10 — Challenge/Dispute | Unresolved | MEDIUM | Distributed capability or organizational process |

---

## 5. Questions for ARB Deliberation

The following questions are presented for ARB consideration in Round 24:

**Q1:** Does unique decision ownership justify acceptance of Trust Attestation, Eligibility, Authorization, Constitutional Governance, and Audit as bounded contexts?

**Q2:** Does D42B (unresolved verifiability guarantees) prevent acceptance of Voting, or is provisional acceptance with a flagged boundary sufficient?

**Q3:** Does Results/Tallying possess independent decision authority, or does evidence currently suggest merger with Voting? How does D39 (counting state meaning) affect this?

**Q4:** Is Governance Evidence Replay a bounded context (defined by decision ownership) or a future operational capability (defined by deployment status)?

**Q5:** Is Arbitration/Legitimacy a bounded context or a Governance subdomain? How do D35, D36, and D37 affect this classification?

**Q6:** Is Challenge/Dispute a distributed capability (Outcome F), an organizational process, or a future bounded context?

---

## 6. Next Step

This document presents evidence for ARB deliberation. The following should occur before aggregate discovery is authorized:

1. **Round 24 — ARB Context Acceptance Review**: ARB formally reviews evidence and decides acceptance, provisional acceptance, deferral, or rejection for each candidate
2. Only after Round 24 decisions are recorded should aggregate discovery be authorized within accepted contexts

---

**Round 23 Candidate Context Evidence Assessment — READY FOR ARB DELIBERATION**

**5 candidates with evidence supporting acceptance. 2 candidates with evidence supporting provisional acceptance. 2 candidates with provisional evidence. 1 candidate unresolved. No final decisions made. ARB deliberation required in Round 24.**
