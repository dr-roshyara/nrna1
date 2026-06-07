# Round 20 — ARB Boundary Review

**Date:** 2026-06-07

**Phase:** Strategic DDD Boundary Review (Step 3 Checkpoint)

**Status:** Awaiting ARB Decision

**Purpose:** Review candidate domain areas from Round 19 and evaluate whether they should remain separate, be merged, or require additional evidence before acceptance as bounded context candidates.

---

## 1. Review Scope

**Inputs:**
- Round 19 Candidate Bounded Context Discovery (10 candidate areas)
- Round 18 Governance Evidence Review
- All Stream findings (1-6B, 3)
- ADRs (Trust Attestation, Verified ≠ Eligible ≠ Authorized, Governance Revocation, Constitutional Capability Sovereignty, Lifecycle vs Phase, Deterministic Resolver)

**Discipline:**
- Candidate areas are hypotheses, not final contexts
- Evaluations assess confidence, not correctness
- No architecture design, no aggregate discovery, no context maps

---

## 2. Candidate Area Assessment

Each candidate is assessed across five dimensions and assigned an overall confidence level.

### C1: Trust Attestation

| Dimension | Assessment | Evidence |
|-----------|-----------|----------|
| Evidence Strength | HIGH | ADR-001, ADR-002, ADR-003, UBIQUITOUS_LANGUAGE, TRUST_CHAIN — 5 governance sources |
| Ubiquitous Language Quality | HIGH | Distinct vocabulary: verified, trust level, attestation, officer, evidence, revocation, bootstrap trust |
| Decision Autonomy | HIGH | Owns verification decisions independently — does not depend on eligibility or authorization |
| Information Cohesion | HIGH | Verification status, trust level, evidence artifacts — all about identity trust, not about processes |
| Relationship Complexity | LOW | Simple enabling relationship with Eligibility (prerequisite); emits events to Governance on revocation |

**Boundary Confidence: HIGH**

**Decision Ownership:** Trust Attestation owns verification decisions (is this identity trustworthy?) that no other candidate owns. Eligibility and Authorization depend on this decision but do not make it.

**Rationale:** ADR-001 explicitly establishes Trust Attestation as a separate bounded context. ADR-002 confirms Verified ≠ Eligible ≠ Authorized. Ubiquitous language is distinct and non-overlapping with other candidates. Decisions (verification, trust level assignment, revocation) are autonomous.

---

### C2: Eligibility

| Dimension | Assessment | Evidence |
|-----------|-----------|----------|
| Evidence Strength | HIGH | ADR-002, TRUST_CHAIN, Stream 5 (preconditions), ParticipationEligibilityEvidence |
| Ubiquitous Language Quality | HIGH | Distinct vocabulary: eligible, membership, voting_rights, enrolled, fee_status, computed eligibility |
| Decision Autonomy | HIGH | Owns eligibility decisions independently — computed at action time from membership state |
| Information Cohesion | MEDIUM | Relies on membership data (status, fees, type) which may belong to a Membership/Party context outside current scope |
| Relationship Complexity | MEDIUM | Depends on Trust (verified identity required), enables Authorization (eligibility check prerequisite) |

**Boundary Confidence: HIGH**

**Decision Ownership:** Eligibility owns participation eligibility decisions (is this participant eligible for this specific process?) that no other candidate owns. Trust Attestation provides prerequisite input but does not determine eligibility.

**Rationale:** ADR-002 explicitly defines Eligibility as a separate decision from Verification and Authorization. Eligibility is process-specific (voting ≠ candidacy), computed at time of action, and independently evaluated.

---

### C3: Authorization

| Dimension | Assessment | Evidence |
|-----------|-----------|----------|
| Evidence Strength | HIGH | ADR-001 (Constitutional Capability Sovereignty), ADR-004 (Deterministic Resolver), Stream 2, ElectionConstitution, ConstitutionalTransitionGuard |
| Ubiquitous Language Quality | HIGH | Distinct vocabulary: authorized, role, permission, capability, action, scope, allowed, denied |
| Decision Autonomy | HIGH | Owns permission checking independently — backend is sole authority, pure function resolver |
| Information Cohesion | HIGH | Roles, permissions, capabilities, action definitions — all about "who can do what" |
| Relationship Complexity | MEDIUM | Depends on both Verified and Eligible (all three required for authorization), gates Voting and Results actions |

**Boundary Confidence: HIGH**

**Decision Ownership:** Authorization owns capability decisions (is this user allowed to perform this action in this context?) that no other candidate owns. It depends on Trust Attestation (verified) and Eligibility (eligible) as inputs but makes the final permission decision independently.

**Rationale:** ADR-001 and ADR-004 explicitly document authorization as a separate concern with centralized authority, deterministic resolution, and no frontend inference.

---

### C4: Constitutional Governance / Lifecycle

| Dimension | Assessment | Evidence |
|-----------|-----------|----------|
| Evidence Strength | HIGH | Stream 5, ADR-003 (Lifecycle vs Phase), ElectionConstitution, ConstitutionalTransitionGuard, GovernanceDecision |
| Ubiquitous Language Quality | HIGH | Distinct vocabulary: constitution, state, transition, lifecycle, preconditions, suspension, governance decision |
| Decision Autonomy | HIGH | Owns state definition, transition rules, precondition validation — no external dependencies for rule definitions |
| Information Cohesion | HIGH | State machine, rules, preconditions, governance decisions — all about election lifecycle governance |
| Relationship Complexity | MEDIUM | Interfaces with Authorization (capability resolution uses lifecycle state), interfaces with Arbitration (reviews governance decisions) |

**Boundary Confidence: HIGH**

**Decision Ownership:** Constitutional Governance owns state definition and transition decisions (what states exist, which transitions are allowed, what preconditions are required) that no other candidate owns. Authorization and Voting depend on lifecycle state but do not define it.

**Rationale:** Constitutional governance is the backbone of the system. Rules are centralized, enforcement is mandatory, and lifecycle states govern all progression.

---

### C5: Voting

| Dimension | Assessment | Evidence |
|-----------|-----------|----------|
| Evidence Strength | HIGH | Stream 3, BaseVote, ADR_20260203 (Voting Security) — well-documented vote recording flow |
| Ubiquitous Language Quality | HIGH | Distinct vocabulary: vote, cast, ballot, receipt, checksum, participation proof, anonymity, voting_code |
| Decision Autonomy | HIGH | Owns vote recording independently — anonymous, unique hash, integrity checksum |
| Information Cohesion | HIGH | Vote records, candidate selections, receipts, checksums, proofs — all about recording a single vote |
| Relationship Complexity | MEDIUM | Synchronous relationship with Results (coupled), depends on Authorization (gate), logged by Audit |

**Boundary Confidence: HIGH — but boundary confidence is separate from boundary decision confidence**

**Guarantee Sensitivity: HIGH (PROVISIONAL)**

**Decision Ownership:** Voting owns vote recording decisions (is this vote valid, unique, and correctly checksummed?) that no other candidate owns. Results do not own vote recording — they derive from it.

**Rationale:** Voting is a well-evidenced candidate with strong language and decision autonomy. The uncertainty is not about whether Voting exists as a domain concept — it clearly does. The uncertainty is about whether receipt verification and participation proof should remain inside Voting or become part of a separate Verification context, depending on verifiability guarantees (D42B).

---

### C6: Results / Tallying

| Dimension | Assessment | Evidence |
|-----------|-----------|----------|
| Evidence Strength | MEDIUM | Stream 3, Vote.php, ResultController — generation and count computation documented; coupling to Voting clear |
| Ubiquitous Language Quality | MEDIUM | Distinct vocabulary (result, count, tally, vote_count) but shares "candidate" and "post" with Voting |
| Decision Autonomy | LOW | Results are a derived projection of Vote data — no independent decisions; count computation is SQL aggregation |
| Information Cohesion | MEDIUM | Result rows, vote counts, abstentions — all derived from Vote data |
| Relationship Complexity | HIGH | Synchronous coupling to Voting (createResultsFromCandidates called on vote save), publication gated by Governance lifecycle state |

**Decision Ownership:** Results/Tallying does not own a unique decision that Voting does not already own. Vote counts are computed from Vote data via SQL aggregation. Results can be regenerated from Vote data at any time. The counting state has constitutional meaning but no operational counting process was observed. LOW decision autonomy is a significant signal that merger may eventually occur.

**Boundary Confidence: MEDIUM — results are derived from Vote data and exhibit low observed decision autonomy**

**Rationale:** Stream 3 found that Results are a derived projection of Vote data, can be regenerated at any time (syncResults), and are created synchronously during vote save. The "counting" state has constitutional meaning but no operational counting process was observed. This candidate has LOW decision autonomy — it does not make independent decisions, it computes aggregates from Vote data. The low decision autonomy suggests possible merger, but ARB should decide.

---

### C7: Audit (Operational)

| Dimension | Assessment | Evidence |
|-----------|-----------|----------|
| Evidence Strength | HIGH | Stream 4, ElectionAuditService, ElectionAuditLog, SecurityEventRecorder — well-documented |
| Ubiquitous Language Quality | HIGH | Distinct vocabulary: audit, log, event, action, record, trace, old_values, new_values |
| Decision Autonomy | HIGH | Owns recording decisions independently — fire-and-forget, never affects outcomes |
| Information Cohesion | HIGH | Log entries, audit trail, voter journey files, security events |
| Relationship Complexity | LOW | Consumes events from Voting, Governance, Authorization — no feedback loop to sources |

**Boundary Confidence: HIGH**

**Decision Ownership:** Audit owns recording decisions (what to log, when to rotate) that no other candidate owns. Fire-and-forget pattern ensures audit decisions never affect business outcomes.

**Rationale:** Operational audit is well-separated from governance replay (Stream 4 finding: no cross-references). Fire-and-forget pattern ensures no coupling to business logic. Clear recording responsibility.

---

### C8: Governance Replay / Verification

| Dimension | Assessment | Evidence |
|-----------|-----------|----------|
| Evidence Strength | MEDIUM | Stream 4, ReplayEvidenceEnvelope, ReplaySession, GovernanceDecisionSnapshot — implementation exists but operationally deferred |
| Ubiquitous Language Quality | HIGH | Distinct vocabulary: replay, evidence, envelope, seal, hash, certification, divergence, snapshot |
| Decision Autonomy | MEDIUM | Can verify evidence integrity independently, but operational invocation not observed (D36) |
| Information Cohesion | HIGH | Evidence envelopes, certification outcomes, divergence events, governance decision snapshots |
| Relationship Complexity | LOW | Consumes evidence from Governance and Arbitration; no feedback to sources |

**Boundary Confidence: MEDIUM**

**Decision Ownership:** Governance Replay owns evidence integrity verification decisions (was this evidence tampered with? does the replay outcome match the original?) that no other candidate owns. However, operational invocation is deferred (Phase 6) and no operational caller was observed (D36).

**Rationale:** Governance replay infrastructure exists and is well-documented. However, it is operationally deferred (Phase 6), and invocation path is unresolved (D36). The boundary with Operational Audit is clear (no coupling), but the operational status makes this candidate harder to confirm.

---

### C9: Arbitration / Legitimacy

| Dimension | Assessment | Evidence |
|-----------|-----------|----------|
| Evidence Strength | MEDIUM | Stream 6B, ConstitutionalArbitrationKernel, ConflictResolutionPolicy, LegitimacyEvaluator — implementation exists and is testable |
| Ubiquitous Language Quality | HIGH | Distinct vocabulary: arbitration, legitimacy, authority, conflict, resolution, precedent, doctrine, constitutional validity |
| Decision Autonomy | MEDIUM | Can determine legitimacy status independently, but operational invocation not observed (D36) |
| Information Cohesion | HIGH | Arbitration traces, legitimacy status, authority classifications, conflict resolution results |
| Relationship Complexity | MEDIUM | Evaluates Governance decisions, determines constitutional validity, legitimacy enforcement unknown (D35, D37) |

**Boundary Confidence: MEDIUM**

**Decision Ownership:** Arbitration owns constitutional validity determination decisions (is this governance decision constitutionally valid?) that no other candidate owns. However, operational invocation is unresolved (D36), legitimacy consequences are unknown (D35, D37), and no enforcement mechanism has been observed.

**Rationale:** Implementation exists and is testable. However, operational invocation path is unresolved (D36), consequences of legitimacy = EXPIRED are unobserved (D35, D37), and no enforcement mechanism was observed. Whether Arbitration is a separate context or part of Governance depends on these unknowns. Boundary is unresolved pending D35, D36, D37.

---

### C10: Challenge / Dispute Handling (Distributed)

| Dimension | Assessment | Evidence |
|-----------|-----------|----------|
| Evidence Strength | MEDIUM | Stream 6A — Outcome F (distributed); no explicit challenge mechanism found |
| Ubiquitous Language Quality | LOW | No distinct vocabulary — relies on arbitration, replay, and verification language |
| Decision Autonomy | LOW | No independent decision-making identified — function emerges from Arbitration + Governance Replay + Governance |
| Information Cohesion | LOW | Function distributed across multiple candidates — no cohesive information set |
| Relationship Complexity | HIGH | Touches Arbitration, Governance, Governance Replay — no single owner |

**Boundary Confidence: LOW — consistent with Outcome F (distributed)**

**Decision Ownership:** Challenge/Dispute does not own a unique decision. Its function (review, escalate, resolve disputes) emerges from Arbitration (constitutional validity), Governance Replay (verification), and Governance (decision consequences). No explicit challenge mechanism was observed.

**Rationale:** Stream 6A concluded that challenge capability is consistent with Outcome F (distributed) but not confirmed. No explicit challenge vocabulary or mechanisms were observed. The function may be organizational (humans resolve disputes) rather than software-based. This candidate should remain as a distributed capability, not a separate context.

---

## 3. Confidence Summary

| Candidate | Confidence | Key Factor |
|-----------|-----------|------------|
| C1 — Trust Attestation | **HIGH** | 5 governance sources, distinct language, independent decisions |
| C2 — Eligibility | **HIGH** | ADR-002 explicit separation, computed at action time |
| C3 — Authorization | **HIGH** | Centralized authority, deterministic resolver, explicit ADRs |
| C4 — Constitutional Governance | **HIGH** | Centralized rules, mandatory enforcement, well-documented lifecycle |
| C5 — Voting | **HIGH** | Well-documented, but boundary sensitive to D42B |
| C6 — Results/Tallying | **MEDIUM** | Strong merger candidate with Voting — derived, coupled, no independent decisions |
| C7 — Audit | **HIGH** | Fire-and-forget, clear separation from governance replay |
| C8 — Governance Replay | **MEDIUM** | Implementation exists but operationally deferred (Phase 6) |
| C9 — Arbitration/Legitimacy | **MEDIUM** | Implementation exists, invocation path unresolved |
| C10 — Challenge/Dispute | **LOW** | Distributed capability, no cohesive context, may be organizational |

---

## 4. Merge Candidate Evaluation

### Merge Candidate A: Trust Attestation + Eligibility + Authorization

**Arguments for separation (current state):**
- ADR-002 explicitly defines three orthogonal decisions: Verified ≠ Eligible ≠ Authorized
- Each has distinct language, information, and decision-making criteria
- Each can change independently (verification expires, eligibility changes, authorization can be revoked)
- Authorization formula requires ALL three — merging would obscure the composition
- Reviewing teams will encounter proven ADR evidence for separation

**Arguments for merger:**
- All three relate to "who can participate" — a single Identity & Access Management lens
- Some implementations merge them into a single User/Member aggregate
- Eligibility depends on Trust (prerequisite), Authorization depends on both (composition)

**Assessment:** Strong evidence supports separation. Each candidate area appears independently cohesive and decision-autonomous. ADR-002 explicitly defines three orthogonal decisions. ARB must decide whether to accept them as separate provisional contexts.

**Rationale:** The ADR evidence for separation is independently confirmed across multiple governance documents. Merging them would contradict explicit architectural decisions and reduce clarity. The dependency chain (Verified → Eligible → Authorized) is compositional, not ownership-based — each step is independently evaluated, independently stored, and independently changeable.

---

### Merge Candidate B: Voting + Results/Tallying

**Arguments for separation (current state):**
- Results table is separate from Votes table
- State machine defines "counting" as a separate constitutional state
- Publication is gated by lifecycle state
- ADR-003 lists counting as state 8 of 10 in constitutional progression

**Arguments for merger:**
- Stream 3 found synchronous coupling: results created immediately on vote save (BaseVote.saved event)
- Results are a derived projection of Vote data — can be regenerated at any time
- No separate counting process observed — results created at vote time, not at counting state entry
- Results have LOW decision autonomy — they compute aggregates from Vote data
- SymcResults deletes and recreates from Vote JSON (Vote is authoritative source)

**Assessment:** Evidence supports possible merger. Results are derived from Vote data and exhibit low observed decision autonomy. However, the "counting" state has documented constitutional meaning (ADR-003) that may imply a separate business activity. ARB must decide whether this boundary remains separate or is merged.

**Rationale:** The implementation evidence shows synchronous coupling, no independent decisions, and regeneration capability from Vote data. However, the "counting" state has documented constitutional meaning (ADR-003) that may imply a separate business activity. D39 remains unresolved. This boundary should remain PROVISIONAL pending ARB decision.

---

### Merge Candidate C: Governance + Arbitration

**Arguments for separation (current state):**
- Arbitration exists only as domain code with no operational invocation (D36)
- Arbitration determines constitutional validity — a different concern from rule definition
- Governance defines rules; Arbitration evaluates decisions against rules
- Different information sets (rules vs. traces, legitimacy decisions)
- Different language (constitution vs. arbitration, authority, conflict resolution)

**Arguments for merger:**
- ConstitutionalTransitionGuard and ConstitutionalArbitrationKernel both deal with constitutional constraints
- Governance Decisions are what Arbitration evaluates — close information relationship
- Both are conceptually about "governance"
- Arbitration is operationally deferred — may never become its own operational context

**Assessment:** Boundary unresolved pending D35, D36, D37. Arbitration has a distinct conceptual area (evaluate decisions against rules) but operational invocation path is unresolved (D36), legitimacy consequences are unknown (D35, D37), and no enforcement mechanism was observed. ARB must decide whether this is a separate context or a deferred Governance subdomain.

**Rationale:** The conceptual distinction (define rules vs. evaluate decisions against rules) is meaningful. However, Arbitration's operational status (test-only, no invocation observed) means it may never be an independent operational context. If Arbitration remains deferred (Phase 6), it could be part of Governance as a subdomain.

---

### Merge Candidate D: Audit + Governance Replay

**Arguments for separation (current state):**
- Stream 4 found no cross-references or coupling between operational audit and governance replay
- Different data models (ElectionAuditLog vs ReplayEvidenceEnvelope)
- Different purposes (operational accountability vs governance verification)
- Different audiences (administrators vs governance systems)
- Different operational status (active vs deferred)

**Arguments for merger:**
- Both are about "recording what happened for later review"
- GovernanceReplayService stores to database that Audit could theoretically read

**Assessment:** Evidence supports separation. Stream 4 found no cross-references or coupling between operational audit and governance replay. Different mechanisms, data models, purposes, audiences, and operational status. ARB must decide whether to accept them as separate provisional contexts.

**Rationale:** Stream 4 explicitly investigated this question and found implementation-level separation with no coupling. Different mechanisms, different data, different purposes, different operational status.

---

## 5. Merge Candidate Summary

| Candidate | Assessment | Confidence | ARB Decision Required |
|-----------|-----------|------------|----------------------|
| Trust + Eligibility + Authorization | Evidence supports separation | HIGH | Accept as separate provisional contexts |
| Voting + Results/Tallying | Evidence supports possible merger | MEDIUM | Decide: separate or merged |
| Governance + Arbitration | Boundary unresolved (D35, D36, D37) | MEDIUM | Decide: separate context or Governance subdomain |
| Audit + Governance Replay | Evidence supports separation | HIGH | Accept as separate provisional contexts |

---

## 6. Distributed Capabilities Review

### Challenge/Dispute Handling

**Current classification:** Distribution (Outcome F)

**Evidence from Stream 6A:** No explicit challenge submission mechanism found. Arbitration, Governance Replay, and Governance decisions together may provide challenge-like capability through constitutional validation.

**Assessment: REMAINS DISTRIBUTED — with MEDIUM confidence**

**Rationale:** No evidence has emerged since Stream 6A to change this conclusion. Arbitration exists but is operationally deferred (D36). Challenge initiation may be organizational. The distributed classification is the most accurate representation of current evidence.

---

## 7. Guarantee-Sensitive Boundaries (D42B)

The following boundaries depend on unresolved election-integrity guarantees:

| Boundary Decision | Sensitivity | Current Classification | If Verifiability Guarantee Exists |
|------------------|------------|----------------------|-----------------------------------|
| Voting vs Results | HIGH | Provisional (leaning merger) | May need to keep separate for independent verification |
| Voting vs Verification (receipt) | HIGH | Verification embedded in Voting | May need separate Verification context |
| Audit responsibility scope | MEDIUM | Operational audit only | May need to expand if auditability guarantee requires |

**Recommendation:** All above boundaries remain PROVISIONAL until D42B governance clarification is complete.

---

## 8. ARB Decision Options

### Option A: Accept Candidate Areas as Provisional Bounded Contexts

**Evidence for:**
- 7 of 10 candidates have HIGH or MEDIUM confidence
- Merge candidates evaluated with clear recommendations
- Guarantee-sensitive boundaries explicitly marked provisional
- ADR evidence provides strong foundation for Trust, Eligibility, Authorization separation

**Evidence against:**
- Voting/Results merger not yet finalized (D39 pending)
- Arbitration operational status unresolved (D36)
- Governance Replay is operationally deferred (Phase 6)
- Challenge/Dispute is distributed, not a defined context

---

### Option B: Merge Selected Candidate Areas

**Evidence for:**
- Voting and Results/Tallying have strong implementation coupling
- Results have low decision autonomy
- No independent counting process observed
- Merging would reduce from 10 to 8 candidates

**Evidence against:**
- "Counting" state has constitutional meaning (ADR-003) — merger may obscure domain intent
- Results publication is gated by lifecycle state (separate concern from vote recording)
- Merger could complicate future verification requirements

---

### Option C: Require Additional Evidence

**Evidence for:**
- D39 (counting state meaning) unresolved — affects Voting/Results boundary
- D36 (arbitration invocation) unresolved — affects Arbitration vs Governance boundary
- Governance Replay operational status unclear
- Challenge/Dispute distributed classification unconfirmed

**Evidence against:**
- 7 of 10 candidates have HIGH confidence and explicit ADR support
- Remaining unknowns may not change candidate structure (only boundary specificity)
- Additional repository analysis has diminishing returns on these questions

---

## 9. Summary

| Candidate | Confidence | Evidence for Separation | Key Uncertainty |
|-----------|-----------|----------------------|-----------------|
| Trust Attestation | HIGH | Distinct language, independent decisions, ADR-001/002/003 | None — well-documented |
| Eligibility | HIGH | ADR-002 explicit separation, computed at action time | None — well-documented |
| Authorization | HIGH | Centralized authority, deterministic resolver, ADR-001/004 | None — well-documented |
| Constitutional Governance | HIGH | Centralized rules, mandatory enforcement, ADR-003 | None — well-documented |
| Voting | HIGH | Distinct language, independent decisions, anonymity mandate | D42B (verifiability guarantee) |
| Results/Tallying | MEDIUM | Separate table, constitutional counting state | D39 (counting meaning), decision autonomy low |
| Audit | HIGH | Fire-and-forget, separate mechanisms from replay | None — well-documented |
| Governance Replay | MEDIUM | Distinct language, evidence sealing, deferred | Phase 6 deferral, D36 (invocation) |
| Arbitration/Legitimacy | MEDIUM | Distinct language, constitutional validity determination | D35/D36/D37 (invocation, enforcement) |
| Challenge/Dispute | LOW | Distributed capability (Outcome F) | No explicit mechanism; may be organizational |

---

## 10. Final ARB Question

**Does each candidate possess a unique decision authority sufficient to justify a separate bounded context?**

| Candidate | Unique Decision Owned | Justifies Separate Context? |
|-----------|---------------------|----------------------------|
| Trust Attestation | Is this identity trustworthy? | Evidence supports yes |
| Eligibility | Is this participant eligible for this process? | Evidence supports yes |
| Authorization | Is this user allowed to perform this action? | Evidence supports yes |
| Constitutional Governance | What state transitions are allowed? | Evidence supports yes |
| Voting | Is this vote valid and anonymous? | Evidence supports yes |
| Results/Tallying | *(no unique decision identified)* | **ARB must decide** — low decision autonomy suggests possible merger |
| Audit | What should be recorded for accountability? | Evidence supports yes |
| Governance Replay | Was evidence integrity preserved? | Evidence supports yes (deferred) |
| Arbitration/Legitimacy | Is this governance decision constitutionally valid? | Evidence supports yes, but invocation unresolved |
| Challenge/Dispute | *(no unique decision identified)* | Evidence supports distributed capability, not separate context |

**ARB Decision Required On:**
1. Accept Trust, Eligibility, Authorization as separate provisional contexts?
2. Accept Voting and Results as separate or merged?
3. Classify Arbitration as separate context or Governance subdomain?
4. Accept Challenge/Dispute as distributed capability?

---

**Round 20 ARB Boundary Review — READY FOR ARB DECISION**

**Next step after ARB decision:** Determine whether candidate areas are accepted as provisional bounded contexts, merged, or require additional evidence. Aggregate discovery is NOT authorized until this review is complete.
