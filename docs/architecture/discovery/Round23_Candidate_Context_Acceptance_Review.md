# Round 23 — Candidate Context Acceptance Review

**Date:** 2026-06-07

**Phase:** Strategic DDD Candidate Context Acceptance (Step 3 Decision Gate)

**Status:** Decision Recorded

---

## 1. Decision Context

**Purpose:** Formally decide which candidate bounded contexts are accepted, accepted provisionally, deferred, or rejected, based on evidence collected across Rounds 17-22.

**Inputs Considered:**

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

**Decision Authority:** Architecture Review Board (ARB)

---

## 2. Acceptance Criteria

Each candidate is evaluated against four criteria:

| Criterion | Question | Source |
|-----------|----------|--------|
| Decision Ownership | Does this candidate own a unique decision that no other candidate owns? | Rounds 19-22 |
| Language Boundaries | Does this candidate have distinct, non-overlapping ubiquitous language? | Rounds 19-21 |
| Relationship Strength | Are relationships with other candidates loose enough to justify separation? | Rounds 21-22 |
| Governance Constraints | Are there unresolved governance questions that could affect boundaries? | Round 18 |

**Decision categories:**
- **ACCEPTED** — All criteria met with HIGH confidence. Candidate is a bounded context.
- **ACCEPTED (Provisional)** — Most criteria met, but some uncertainty remains. Accept with conditions.
- **DEFERRED** — Significant unknowns remain. Not accepted at this stage.
- **REJECTED** — Evidence does not support context status. May be a subdomain, distributed capability, or out of scope.

---

## 3. Candidate Decisions

### C1: Trust Attestation

**Decision: ACCEPTED**

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

**Decision: ACCEPTED**

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

**Decision: ACCEPTED**

| Criterion | Assessment | Evidence |
|-----------|-----------|----------|
| Decision Ownership | ✅ Owns capability decisions (is this user allowed to perform this action?) | ADR-001 Const., ADR-004, Rounds 19-20 |
| Language Boundaries | ✅ Distinct language: authorized, role, permission, capability, allowed, denied | ADR-001, Round 19 |
| Relationship Strength | ✅ Strong separation from neighbors (R2, R3, R5: all support separation) | Round 22 R2-R5 analysis |
| Governance Constraints | ✅ None — authorization model is well-documented across multiple ADRs | Round 18 |

**Conditions:** None.

**Rationale:** Authorization is centralized, deterministic, and independently enforceable. The backend is the sole authority with no bypass mechanisms. Multiple ADRs document the design rationale. The compositional dependency on Eligibility strengthens separation by clarifying the dependency direction.

---

### C4: Constitutional Governance / Lifecycle

**Decision: ACCEPTED**

| Criterion | Assessment | Evidence |
|-----------|-----------|----------|
| Decision Ownership | ✅ Owns lifecycle state and transition decisions (what states exist, which transitions are allowed) | ADR-003, Stream 5, Rounds 19-20 |
| Language Boundaries | ✅ Distinct language: constitution, state, transition, lifecycle, preconditions, suspension | Stream 5, Round 19 |
| Relationship Strength | ✅ Moderate-to-strong separation from neighbors (R3, R4, R8: all support separation) | Round 22 R3-R4 analysis |
| Governance Constraints | ✅ None — rules-in-code is intentional design (D30 resolved); suspension authority pending (D26) but does not affect boundary | Round 18 |

**Conditions:** None.

**Rationale:** Constitutional Governance defines the election lifecycle independently of any specific election. Rules are centralized, enforcement is mandatory, and the state machine governs all progression. Suspension authority origin (D26) is unresolved but does not affect boundary separation.

---

### C5: Audit (Operational)

**Decision: ACCEPTED**

| Criterion | Assessment | Evidence |
|-----------|-----------|----------|
| Decision Ownership | ✅ Owns operational recording decisions (what to log, when to rotate) independently | Stream 4, Rounds 19-20 |
| Language Boundaries | ✅ Distinct language: audit, log, event, action, record, trace, old_values, new_values | Stream 4, Round 19 |
| Relationship Strength | ✅ Strong separation from all neighbors (R7, R8, R9: all observability, fire-and-forget) | Round 22 R7-R9 analysis |
| Governance Constraints | ✅ None — audit is independently functioning with no business coupling | Stream 4 |

**Conditions:** None.

**Rationale:** Audit is the strongest separation case in the map. It receives from all contexts but affects none. Fire-and-forget pattern ensures no coupling. Stream 4 explicitly confirmed separation from governance replay. No governance constraints affect its boundary.

---

### C6: Voting

**Decision: ACCEPTED (Provisional)**

| Criterion | Assessment | Evidence |
|-----------|-----------|----------|
| Decision Ownership | ✅ Owns vote recording decisions (is this vote valid, anonymous, and correctly checksummed?) — boundary with Verification unresolved | Stream 3, ADR_20260203, Rounds 19-20 |
| Language Boundaries | ✅ Distinct language: vote, cast, ballot, receipt, checksum, participation proof | Stream 3, Round 19 |
| Relationship Strength | ✅ Strong separation from most neighbors (R4, R5, R7: support separation). R6 suggests merger with Results. | Round 22 R4-R7 analysis |
| Governance Constraints | ⚠️ HIGH — D42B (verifiability guarantee) may affect whether receipt verification belongs to Voting or a separate Verification context | Round 18, D42B |

**Provisional Conditions:**
1. Boundary with Verification (receipt verification, participation proof) remains unresolved pending D42B
2. Relationship with Results/Tallying remains unresolved pending D39
3. Voting is accepted as a bounded context — its external boundaries are provisional, not its internal existence

**Rationale:** Voting is a clear domain concept with strong evidence, unique decision ownership, and an explicit architectural mandate (vote anonymity). The uncertainty is about what belongs inside Voting versus outside, not about whether Voting exists as a context.

---

### C7: Results/Tallying

**Decision: PROVISIONALLY ACCEPTED (with merger consideration)**

| Criterion | Assessment | Evidence |
|-----------|-----------|----------|
| Decision Ownership | ❌ No unique decision observed within examined evidence — results are derived from Vote data | Stream 3, Round 22 R6 analysis |
| Language Boundaries | ⚠️ Partial — shares "candidate" and "post" vocabulary with Voting | Round 19 |
| Relationship Strength | ⚠️ Boundary-Collapse Indicator — synchronous coupling, regenerable data, no independent decisions | Round 22 R6 (strongest merger signal) |
| Governance Constraints | ⚠️ MEDIUM — D39 (counting state meaning) unresolved; constitutional counting state may or may not imply independent business activity | ADR-003, D39 |

**Provisional Conditions:**
1. Strong merger candidate with Voting — accept provisionally pending D39 resolution
2. If D39 determines counting has no independent business activity, Results should merge into Voting
3. If D39 determines counting is a distinct domain activity (e.g., manual verification period), Results may remain separate

**Rationale:** Results/Tallying has the weakest evidence for independence of any accepted candidate. No unique decision ownership was observed. The constitutional "counting" state is the primary argument for separation. The implementation evidence strongly supports merger. This boundary is the highest-priority item to resolve at the earliest appropriate time.

---

### C8: Governance Replay / Verification

**Decision: DEFERRED**

| Criterion | Assessment | Evidence |
|-----------|-----------|----------|
| Decision Ownership | ✅ Owns evidence integrity verification decisions — but operational path not observed | Stream 4, Round 19-20 |
| Language Boundaries | ✅ Distinct language: replay, evidence, envelope, seal, hash, certification, divergence | Stream 4, Round 19 |
| Relationship Strength | ⚠️ Strong separation from Audit (R8 confirmation) but operational coupling unknown | Round 22 R8 analysis |
| Governance Constraints | ⚠️ HIGH — Phase 6 deferral, D36 invocation unresolved | Round 18, D36 |

**Reason for Deferral:** Governance Replay has clear conceptual boundaries but zero operational integration. It is phase-6 deferred, invocation path unresolved, and no operational caller was observed (D36). The boundary is well-defined but the context does not yet exist operationally.

**Recommendation:** Revisit when Phase 6 implementation begins or when invocation path is resolved.

---

### C9: Arbitration/Legitimacy

**Decision: DEFERRED**

| Criterion | Assessment | Evidence |
|-----------|-----------|----------|
| Decision Ownership | ✅ Owns constitutional validity decisions — but operational path not observed | Stream 6B, Rounds 19-20 |
| Language Boundaries | ✅ Distinct language: arbitration, legitimacy, authority, conflict, resolution, precedent, doctrine | Stream 6B, Round 19 |
| Relationship Strength | ⚠️ Boundary unresolved — may be separate context or Governance subdomain | Round 22 R10 analysis |
| Governance Constraints | ⚠️ HIGH — D35, D36, D37 unresolved; enforcement unknown | D35, D36, D37 |

**Reason for Deferral:** Arbitration has implementation evidence and distinct language, but its operational status (D36), legitimacy consequences (D35), and enforcement mechanism (D37) are all unresolved. The boundary with Governance may dissolve if Arbitration is a deferred Governance subdomain.

**Recommendation:** Revisit when D35, D36, D37 are resolved. In the interim, consider Arbitration a Governance subdomain candidate.

---

### C10: Challenge/Dispute Handling

**Decision: REJECTED as bounded context**

**Classification:** Distributed Capability (Outcome F from Stream 6A)

| Criterion | Assessment | Evidence |
|-----------|-----------|----------|
| Decision Ownership | ❌ No unique decision observed — function emerges from Arbitration + Governance Replay + Governance | Stream 6A, Round 19-20 |
| Language Boundaries | ❌ No distinct vocabulary — relies on arbitration, replay, and verification language | Stream 6A |
| Relationship Strength | ❌ Low cohesion — distributed across multiple candidates with no single owner | Round 22 |
| Governance Constraints | ⚠️ Challenge initiation may be organizational — D33 unresolved | D33 |

**Rationale:** Challenge/Dispute Handling fails all four criteria for bounded context status. It has no unique decision ownership, no distinct language, low cohesion, and the initiation mechanism may be entirely organizational. The distributed capability model (Outcome F) remains the best classification.

---

## 4. Decision Summary

| Candidate | Decision | Confidence | Key Condition |
|-----------|----------|------------|--------------|
| C1 — Trust Attestation | ✅ **ACCEPTED** | HIGH | None |
| C2 — Eligibility | ✅ **ACCEPTED** | HIGH | None |
| C3 — Authorization | ✅ **ACCEPTED** | HIGH | None |
| C4 — Constitutional Governance | ✅ **ACCEPTED** | HIGH | None |
| C5 — Audit (Operational) | ✅ **ACCEPTED** | HIGH | None |
| C6 — Voting | ✅ **ACCEPTED (Provisional)** | HIGH | Pending D42B boundary clarification |
| C7 — Results/Tallying | ⚠️ **PROVISIONALLY ACCEPTED** | MEDIUM | Pending D39 merger decision |
| C8 — Governance Replay | 🔄 **DEFERRED** | MEDIUM | Phase 6, D36 invocation |
| C9 — Arbitration/Legitimacy | 🔄 **DEFERRED** | MEDIUM | D35, D36, D37 |
| C10 — Challenge/Dispute | ❌ **REJECTED** | MEDIUM | Distributed capability (Outcome F) |

---

## 5. Authorized Next Phase

**Accepted contexts (7):**
- Trust Attestation
- Eligibility
- Authorization
- Constitutional Governance
- Audit
- Voting (provisional boundary)
- Results/Tallying (provisional, merger consideration)

**Authorized: Aggregate Discovery** within accepted contexts.

Aggregate discovery may begin for Trust Attestation, Eligibility, Authorization, Constitutional Governance, and Audit without restrictions.

Aggregate discovery for Voting and Results/Tallying may begin WITHIN their current boundaries, but aggregate candidates must not assume final boundary lines until D39 and D42B are resolved. If a discovered aggregate spans the provisional Voting/Results boundary, it should be flagged for review.

**Not yet authorized:**
- Aggregate discovery within Governance Replay or Arbitration (deferred)
- Tactical design
- Event storming / event discovery
- Service decomposition
- Microservice design
- Implementation planning

---

**Round 23 Candidate Context Acceptance Review — RECORDED**

**7 contexts accepted (5 unconditionally, 2 provisionally). 2 contexts deferred. 1 context rejected (distributed capability). Aggregate discovery authorized for all 7 accepted contexts.**
