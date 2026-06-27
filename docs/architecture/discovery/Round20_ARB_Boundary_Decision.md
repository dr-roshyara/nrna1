# Round 20 — ARB Boundary Decision

**Date:** 2026-06-07

**Phase:** Strategic DDD Boundary Decision (Step 3 Decision Gate)

**Status:** Decision Recorded

---

## 1. Decision Context

**Purpose:** Formally decide which candidate areas from Round 19-20 are authorized for Context Mapping. This is NOT final bounded context acceptance — it is candidate authorization for the next discovery phase.

**Decision Authority:** Architecture Review Board (ARB)

**Inputs Considered:**

| Artifact | Status |
|----------|--------|
| Round 17 Repository Discovery (Streams 1-6B, 3) | Complete |
| Round 18 Governance Clarification | Complete |
| Round 19 Candidate Context Discovery | Complete |
| Round 20 ARB Boundary Review | Complete |
| ADRs (Trust Attestation, Verified ≠ Eligible ≠ Authorized, Governance Revocation, Constitutional Capability Sovereignty, Lifecycle vs Phase, Deterministic Resolver) | Reviewed |
| Discovery Debt Register (D1-D42) | Referenced |
| Hypothesis Register (H1-H23) | Referenced |

**Governance Constraint:** Any guarantee-sensitive boundary marked PROVISIONAL in the Boundary Review remains provisional pending D42B clarification.

---

## 2. Candidate Decisions

### Decision 2.1: Trust Attestation

**Decision: ACCEPTED as Candidate Context for Context Mapping**

**Evidence supporting acceptance:**
- 5 governance sources (ADR-001, ADR-002, ADR-003, UBIQUITOUS_LANGUAGE, TRUST_CHAIN)
- Distinct ubiquitous language: verified, trust level, attestation, officer, evidence, revocation
- ADR-002 explicitly defines Verified ≠ Eligible ≠ Authorized as three orthogonal decisions
- Bootstrap trust model (officer authority from role, not from verification) is documented

**Decision Ownership Analysis:**
Trust Attestation appears to own verification decisions (is this identity trustworthy?) that no other candidate appears to own. Eligibility and Authorization depend on this decision as input but do not appear to make it.

**Evidence supporting caution:**
- No operational changes required — Trust Attestation is already implicitly implemented

**Remaining uncertainty:** None significant. Trust model is well-documented across multiple governance sources.

**Confidence:** HIGH

---

### Decision 2.2: Eligibility

**Decision: ACCEPTED as Candidate Context for Context Mapping**

**Evidence supporting acceptance:**
- ADR-002 explicitly defines Eligibility as a separate decision from Verification and Authorization
- Eligibility is computed at action time, not stored — distinct from Verification (state) and Authorization (permission)
- Process-specific: voting eligibility ≠ candidacy eligibility ≠ delegation eligibility
- ParticipationEligibilityEvidence provides frozen, hashed, replay-addressable evidence for governance replay

**Decision Ownership Analysis:**
Eligibility appears to own participation eligibility decisions (is this participant eligible for this specific process?) that no other candidate appears to own. Trust Attestation provides prerequisite input but does not appear to determine eligibility.

**Evidence supporting caution:**
- Membership data (status, fees, type) may belong to a Membership/Party domain not yet within scope
- Eligibility rules may be influenced by organizational governance (medium sensitivity)

**Remaining uncertainty:** Source of eligibility policy rules remains unknown (D22 partially resolved). Does not affect acceptance for mapping.

**Confidence:** HIGH

---

### Decision 2.3: Authorization

**Decision: ACCEPTED as Candidate Context for Context Mapping**

**Evidence supporting acceptance:**
- ADR-001 (Constitutional Capability Sovereignty): "Backend is the sole authority"
- ADR-004 (Deterministic Resolver): Pure function, no infrastructure calls, deterministic and side-effect-free
- Centralized capability resolution with no bypass mechanisms
- ElectionConstitution.RULES defines all roles and allowed actions
- ConstitutionalTransitionGuard enforces all authorization checks

**Decision Ownership Analysis:**
Authorization appears to own capability decisions (is this user allowed to perform this action in this context?) that no other candidate appears to own. It depends on Trust Attestation (verified) and Eligibility (eligible) as inputs but appears to make the final permission decision independently.

**Evidence supporting caution:**
- Authorization depends on Verified AND Eligible (three conditions must all be true)
- Tight integration with Constitutional Governance (lifecycle state used in capability resolution)

**Remaining uncertainty:** None significant. Authorization model is well-documented across multiple ADRs.

**Confidence:** HIGH

---

### Decision 2.4: Constitutional Governance / Lifecycle

**Decision: ACCEPTED as Candidate Context for Context Mapping**

**Evidence supporting acceptance:**
- ADR-003 (Lifecycle vs Phase Projection): 12 constitutional lifecycle states defined
- Centralized rules in ElectionConstitution.RULES — single source of truth
- ConstitutionalTransitionGuard enforces all transitions — no bypass mechanisms
- Suspension is defined as operational governance overlay (ADR-003, code comment)

**Decision Ownership Analysis:**
Constitutional Governance appears to own state definition and transition decisions (what states exist, which transitions are allowed, what preconditions are required) that no other candidate appears to own. Authorization and Voting depend on lifecycle state but do not appear to define it.

**Evidence supporting caution:**
- Suspension authority origin remains unknown (D26 — partially resolved)
- Rules are embedded in code — intentional (D30 resolved) but change requires deployment

**Remaining uncertainty:** None significant for mapping purposes. Rule origin is architectural (ADR-documented), not external governance.

**Confidence:** HIGH

---

### Decision 2.5: Audit (Operational)

**Decision: ACCEPTED as Candidate Context for Context Mapping**

**Evidence supporting acceptance:**
- Stream 4 explicitly confirmed separation from governance replay (no cross-references, no coupling)
- Fire-and-forget pattern ensures audit never affects business outcomes
- Well-documented implementation: ElectionAuditService, ElectionAuditLog, SecurityEventRecorder
- Different data models, purposes, and audiences from governance replay

**Decision Ownership Analysis:**
Audit appears to own recording decisions (what to log, when to rotate) that no other candidate appears to own. Fire-and-forget pattern ensures audit decisions never affect business outcomes.

**Evidence supporting caution:**
- SecurityEventRecorder design intent (support replay?) — D20 partially resolved
- Boundary is well-defined regardless of D20 outcome

**Remaining uncertainty:** Low. Operational audit is well-separated and independently functioning.

**Confidence:** HIGH

---

### Decision 2.6: Voting

**Decision: ACCEPTED as Candidate Context for Context Mapping — PROVISIONAL pending D42B**

**Evidence supporting acceptance:**
- Stream 3 provided well-documented vote recording flow with implementation evidence
- Vote anonymity is a fundamental, explicitly documented requirement (ADR_20260203)
- Distinct ubiquitous language: vote, cast, ballot, receipt, checksum, participation proof, voting_code
- Strong information cohesion: vote records, candidate selections, receipts, checksums, proofs

**Decision Ownership Analysis:**
Observed decision ownership appears to include vote acceptance and vote recording decisions. However, whether receipt verification and participation proof remain within Voting or belong to a separate Verification concern is unresolved pending D42B.

**Evidence supporting caution:**
- Receipt verification and participation proof boundaries depend on verifiability guarantees (D42B)
- Synchronous coupling to Results/Tallying — boundary not yet finalized
- Whether Voting and Results should be merged depends on D39 resolution

**Remaining uncertainty:**
- Whether receipt verification belongs to Voting or a separate Verification context (D42B)
- Whether Voting and Results should be merged (D39 — counting state meaning)

**Conditional Acceptance:** Voting is accepted as a candidate context for mapping. Its boundary with Verification and Results remains subject to D42B and D39 resolution.

---

### Decision 2.7: Results / Tallying

**Decision: ACCEPTED as Candidate Context for Context Mapping — with MERGER CONSIDERATION**

**Evidence supporting acceptance:**
- Separate results table from votes table
- Constitutional counting state (ADR-003, state 8 of 10 in linear progression)
- Observed decision ownership: Results/Tallying does not appear to own a unique decision that Voting does not already own

**Evidence supporting caution:**
- Results are a derived projection of Vote data — can be regenerated at any time
- Synchronous coupling to Voting (createResultsFromCandidates on vote save)
- LOW decision autonomy — no unique independent decisions identified
- No separate counting process observed within examined implementation
- Stream 3 found implementation-level coupling with no temporal or architectural separation

**Merger Consideration:** Results/Tallying has LOW decision autonomy and no unique decision ownership observed. The evidence supports possible merger with Voting. However, the constitutional "counting" state (ADR-003) may imply a separate business activity that is not yet operationally realized.

**Condition:** Merger decision deferred until D39 resolution. In the interim, Results is a candidate context for mapping — not a final bounded context.

---

### Decision 2.8: Governance Replay / Verification

**Decision: Candidate Context — Operational Status Unresolved**

**Evidence supporting provisional classification:**
- Stream 4 found clear separation from operational audit
- Distinct ubiquitous language: replay, evidence, envelope, seal, hash, certification, divergence, snapshot
- Implementation exists: ReplayEvidenceEnvelope, ReplaySession, GovernanceReplayService, GovernanceDecisionSnapshot
- Deterministic SHA256 hashing ensures cross-runtime verification

**Decision Ownership Analysis:**
Governance Replay appears to own evidence integrity verification decisions. However, operational invocation is deferred (Phase 6) and no operational caller was observed (D36).

**Reason for non-acceptance:**
- Operationally deferred to Phase 6 (GovernanceStateReconstructionService interface-only)
- Invocation path unresolved (D36)
- Governance Decision Store IS operationally wired, but kernel is not

**Classification:** Candidate Context — Operational Status Unresolved. Not accepted as a final candidate context for mapping until operational status is clarified.

---

### Decision 2.9: Arbitration / Legitimacy

**Decision: Candidate Context — Boundary Unresolved**

**Evidence supporting provisional classification:**
- Distinct ubiquitous language: arbitration, legitimacy, authority, conflict, resolution, precedent, doctrine, constitutional validity
- Implementation exists and is testable: ConstitutionalArbitrationKernel, ConflictResolutionPolicy, LegitimacyEvaluator
- GovernanceLegitimacy enum defines 7 legitimacy states with temporal determination

**Decision Ownership Analysis:**
Arbitration appears to own constitutional validity determination decisions. However, operational invocation is unresolved (D36), legitimacy consequences are unknown (D35, D37), and no enforcement mechanism has been observed.

**Reason for non-acceptance:**
- Operational invocation path unresolved (D36)
- Consequences of legitimacy = EXPIRED not observed (D35)
- Enforcement mechanism not observed (D37)
- May be part of Constitutional Governance rather than a separate context

**Classification:** Candidate Context — Boundary Unresolved pending D35, D36, D37 resolution. Requires additional review during Context Mapping to determine whether this is a separate context or a Governance subdomain.

---

### Decision 2.10: Challenge / Dispute Handling

**Decision: NOT ACCEPTED as a candidate context**

**Classification:** Distributed Capability (Outcome F from Stream 6A)

**Evidence supporting rejection:**
- No explicit challenge, appeal, or dispute mechanisms found in examined implementation
- No distinct vocabulary — relies on arbitration, replay, and verification language
- No unique decision ownership observed — function emerges from Arbitration + Governance Replay + Governance
- Stream 6A concluded Outcome F (distributed) with multiple alternative interpretations possible

**Evidence supporting distributed classification:**
- ConstitutionalArbitrationKernel can review governance decisions
- ReplayDivergenceDetected can identify when evaluation outcomes change
- Governance owns consequence decisions (ADR-003)
- Together, these provide review capability without a dedicated challenge subsystem

**Remaining uncertainty:** Whether challenge initiation is organizational (humans trigger review) or software-based (future implementation). D33 remains unresolved but does not affect context classification.

---

## 3. Decision Summary

| Candidate | Decision | Confidence | Condition |
|-----------|----------|------------|-----------|
| C1 — Trust Attestation | ✅ Candidate Context for Mapping | HIGH | None |
| C2 — Eligibility | ✅ Candidate Context for Mapping | HIGH | None |
| C3 — Authorization | ✅ Candidate Context for Mapping | HIGH | None |
| C4 — Constitutional Governance | ✅ Candidate Context for Mapping | HIGH | None |
| C5 — Audit (Operational) | ✅ Candidate Context for Mapping | HIGH | None |
| C6 — Voting | ✅ Candidate Context for Mapping | HIGH | Pending D42B (provisional boundary) |
| C7 — Results/Tallying | ✅ Candidate Context for Mapping | MEDIUM | Pending D39 (merger consideration) |
| C8 — Governance Replay | ◐ Candidate Context — Status Unresolved | MEDIUM | Phase 6 operational deferral |
| C9 — Arbitration/Legitimacy | ◐ Candidate Context — Boundary Unresolved | MEDIUM | Pending D35, D36, D37 |
| C10 — Challenge/Dispute | ❌ Distributed Capability | MEDIUM | Not a distinct context |

---

## 4. Deferred Questions

| Question | Debt | Affects | Resolved By |
|----------|------|---------|-------------|
| Voting vs Verification boundary | D42B | C6 | Governance clarification on verifiability |
| Voting vs Results merger decision | D39 | C6, C7 | Counting state meaning resolution |
| Arbitration context vs Governance subdomain | D35, D36, D37 | C9 | Legitimacy enforcement, invocation resolution |
| Governance Replay operational activation | Phase 6 | C8 | Phase 6 implementation |

---

## 5. Authorization Decision

**Next Authorized Phase: Round 21 — Context Mapping**

7 candidate contexts are authorized for Context Mapping review. Governance Replay and Arbitration/Legitimacy are not accepted as candidate contexts at this stage — they require further investigation before mapping.

**Authorized for Context Mapping:**
- Trust Attestation
- Eligibility
- Authorization
- Constitutional Governance
- Audit (Operational)
- Voting (provisional boundary)
- Results/Tallying (merger consideration)

**Not Authorized for Context Mapping at this stage:**
- Governance Replay — operational status unresolved
- Arbitration/Legitimacy — boundary unresolved

**Also carried forward as architectural consideration:**
- Challenge/Dispute — distributed capability (not a context)

**Not Authorized for any phase:**
- Aggregate discovery
- Tactical design
- Event discovery
- Service decomposition
- Architecture diagrams
- Implementation planning

**Boundaries remain provisional for:** Voting, Results/Tallying — no final boundaries will be locked until D42B and D39 are resolved.

---

**Round 20 ARB Boundary Decision — RECORDED**

**7 candidate contexts authorized for Context Mapping. 2 candidate contexts deferred (Governance Replay operational status unresolved, Arbitration boundary unresolved). 1 distributed capability. 2 contexts with provisional boundaries pending D42B and D39.**
