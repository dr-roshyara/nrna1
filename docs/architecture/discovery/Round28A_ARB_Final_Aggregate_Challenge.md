# Round 28A — ARB Final Aggregate Challenge

**Date:** 2026-06-08

**Phase:** Tactical DDD — Final Aggregate Challenge Review

**Status:** Complete — Ready for ARB Decision

---

## 1. Purpose

Challenge the aggregate inventory itself — not individual aggregates, but the completeness and legitimacy of the inventory as a whole. This is the final tactical DDD governance checkpoint before closing tactical discovery.

---

## 2. Challenge Q16: Aggregate Density Review

### Question

Are there any contexts where aggregate discovery produced zero aggregates because no aggregate exists (legitimate), or because aggregate discovery was incomplete?

### Contexts Under Challenge

**Eligibility — 0 aggregates**

| Evidence for "No aggregate exists" | Evidence for "Discovery incomplete" |
|-----------------------------------|-------------------------------------|
| Core decision (eligibility evaluation) is stateless computation | EligibilityPolicy rules may have state (thresholds, overrides) not yet examined |
| Eligibility is computed at action time — no stored state | ElectionEnrollment boundary with Voting was re-examined and resolved |
| ADR-002 confirms Eligibility as an evaluation, not a state owner | Manual eligibility overrides (exception cases) not observed — may require aggregate |
| Consistency boundary test: no invariant requires aggregate | Membership data dependency may hide a Membership/Party aggregate outside current scope |

**Verdict: No aggregate is legitimate.** The stateless evaluation pattern (eligibility = computation at action time) is a legitimate DDD pattern for policy-driven contexts. Manual overrides would be the only justification for an aggregate, and none were observed. **Confirmed: 0 aggregates.**

---

**Audit — 0 aggregates**

| Evidence for "No aggregate exists" | Evidence for "Discovery incomplete" |
|-----------------------------------|-------------------------------------|
| Fire-and-forget: audit never affects business outcomes | SecurityEventRecorder may need aggregate if trust events require transactional consistency |
| No feedback loop from Audit to any operational context | Governance replay integration (Phase 6) may change audit requirements |
| ElectionAuditLog is an append-only record with no invariants beyond recording | |
| Stream 4 confirmed separation from governance replay — different purposes | |

**Verdict: No aggregate is legitimate.** The fire-and-forget observability pattern is a well-established DDD pattern (often called a "Reporting Context" or "Observability Context"). Append-only records with no business feedback loop do not require aggregates. **Confirmed: 0 aggregates.**

---

**Results/Tallying — 0 aggregates**

| Evidence for "No aggregate exists" | Evidence for "Discovery incomplete" |
|-----------------------------------|-------------------------------------|
| Projection Test passed — all Results state reconstructable from Vote data | Future electoral formula variability (FPTP, STV, MMP, D'Hondt) could create independent decision ownership |
| No unique decision ownership observed | Counting state (D39) may have latent business meaning not yet operationalized |
| Counting is a Governance Policy, not an operational process | Round 24G identified future formula variability as a potential source of domain complexity |
| Vote creation + Result creation are currently synchronous — same transaction | |

**Verdict: No aggregate is legitimate in current implementation.** The Projection Test is definitive: all current Results state is reconstructable from Vote data. Future formula variability (Round 24G) could create independent decision ownership, but that is a future concern, not a current domain fact. Current evidence does not require an independent aggregate. **Confirmed: 0 aggregates in current implementation; future formula variability should be reassessed if independent decision ownership emerges.**

---

**Arbitration/Legitimacy — 0 aggregates**

| Evidence for "No aggregate exists" | Evidence for "Discovery incomplete" |
|-----------------------------------|-------------------------------------|
| ConstitutionalDecision is a Decision Record — evaluates but does not create truth | D35/D36/D37 unresolved — if legitimacy consequences or enforcement emerge, aggregate may be needed |
| Legitimacy originates from Governance temporal windows, not Arbitration | GEO-3.2+ planned extension for administrative signals (suspension, emergency) |
| Q14 (Aggregate Root Test): no invariant requires aggregate boundary | |
| Q12: records truth, doesn't create it | |
| All invariants protectable through stateless evaluation + record keeping | |

**Verdict: No aggregate is legitimate.** The Decision Record pattern is a legitimate DDD outcome. ConstitutionalDecision documents the application of predetermined rules; it does not create or own business truth. Future extension (GEO-3.2+) may change this, but current evidence supports 0 aggregates. **Confirmed: 0 aggregates.**

---

## 3. Challenge Q17: Missing Aggregate Review

### Question

Is there any concept across all 9 contexts that feels like it should be an aggregate but was not identified as one?

| Concept | Context | Reason Not an Aggregate | Confidence |
|---------|---------|------------------------|------------|
| ParticipationEligibilityEvidence | Eligibility | Immutable Value Object — frozen, hashed snapshot | HIGH |
| Precondition evaluation | Authorization/Governance | Stateless specification — evaluated at runtime | HIGH |
| Suspension | Governance | Entity within GovernanceState — modifies lifecycle state | HIGH |
| GovernanceDecision | Governance | Entity/Record — may be GovernanceState sub-entity | MEDIUM |
| ReplayAssertion | Governance Evidence Replay | Entity within ReplaySession — created during session lifecycle | HIGH |
| ReplayCertification | Governance Evidence Replay | Entity within ReplaySession — produced at session end | HIGH |
| EvidenceEnvelope | Governance Evidence Replay | Immutable Value Object — sealed at creation | HIGH |
| Counting | Results/Tallying | Governance Policy — publication gate, not operational process | MEDIUM |
| ConstitutionalDecision | Arbitration | Decision Record — evaluates truth, does not create it | HIGH |

**No missing aggregates identified.** Every concept that could be mistaken for an aggregate was examined during context-level aggregate discovery and classified correctly.

---

## 4. Challenge Q18: Aggregate Completeness Review

### Question

Does the aggregate inventory cover all business invariants that require transactional consistency?

| Invariant | Aggregate | Covered? |
|-----------|-----------|----------|
| Vote anonymity (no user_id) | Vote | ✅ |
| Vote uniqueness (vote_hash) | Vote | ✅ |
| Vote integrity (data_checksum) | Vote | ✅ |
| Vote verifiability (receipt_hash) | Vote | ✅ |
| Identity trust decision | Verification | ✅ |
| Lifecycle state consistency | GovernanceState | ✅ |
| Suspension overlay consistency | GovernanceState | ✅ |
| Role assignment uniqueness | RoleAssignment (provisional) | ⚠️ Partial — ADC-1 |
| Replay session lifecycle | ReplaySession (provisional) | ⚠️ Partial — ADGR-1 |
| Eligibility computation | (Domain Service — no aggregate needed) | ✅ Stateless |
| Audit recording | (Observability — no aggregate needed) | ✅ Fire-and-forget |
| Result computation | (Projection — no aggregate needed) | ✅ Reconstructable from Vote |
| Constitutional validity | (Decision Record — no aggregate needed) | ✅ Evaluated, not created |

**All invariants are covered.** Either by an aggregate, a provisional aggregate, or a legitimate non-aggregate pattern (Domain Service, Observability, Projection, Decision Record).

---

## 5. Challenge Q19: Cross-Context Consistency Review

### Question

Are there any cross-context invariants that should be protected by a shared aggregate or a different aggregate boundary?

| Cross-Context Relationship | Invariant | Current Protection | Issue? |
|---------------------------|-----------|-------------------|--------|
| Verification → Eligibility | Eligibility requires Verified | EligibilityEvaluation reads Verification status at runtime | ✅ No shared aggregate needed |
| Eligibility → Authorization | Authorization requires Eligibility | CapabilityResolution reads eligibility at runtime | ✅ No shared aggregate needed |
| Governance → Authorization | Authorization requires lifecycle state | CapabilityResolution reads GovernanceState at runtime | ✅ No shared aggregate needed |
| Governance → Voting | Voting requires voting_active | Vote creation checks lifecycle state at runtime | ✅ No shared aggregate needed |
| Authorization → Voting | Voting requires authorization | Vote creation checks capability at runtime | ✅ No shared aggregate needed |
| Voting → Results | Results derived from Vote data | Synchronous createResultsFromCandidates (Vote aggregate) | ⚠️ Coupling is within Vote's consistency boundary, not cross-context |

**No cross-context invariants require a shared aggregate.** All cross-context dependencies are read-only or synchronous within an existing aggregate boundary.

---

## 6. Challenge Q20: Tactical Discovery Closure Review

### Question

Is tactical DDD discovery (aggregate discovery) complete?

### Completion Criteria

| Criterion | Status | Evidence |
|-----------|--------|----------|
| All accepted contexts investigated | ✅ Complete | 9/9 contexts completed (27A-27I) |
| Aggregate candidates evaluated | ✅ Complete | Per-context evaluation + challenge reviews |
| Aggregate boundary validation | ✅ Complete | Boundary validation matrix per context |
| Consistency boundary analysis | ✅ Complete | Per-context + cross-context |
| Invariant coverage verified | ✅ Complete | Q18 — all invariants covered |
| Missing aggregate review | ✅ Complete | Q17 — no missing aggregates |
| Cross-context consistency | ✅ Complete | Q19 — no shared aggregate needed |
| Aggregate inventory consolidation | ✅ Complete | Round 28 — no consolidation required |
| Final challenge review | ✅ Complete | Round 28A |

### Conclusion

**Tactical DDD discovery (aggregate discovery) is COMPLETE.**

5 aggregates across 9 contexts. 3 stable (Verification, GovernanceState, Vote). 2 provisional (RoleAssignment, ReplaySession). 4 contexts legitimately have no aggregates (Eligibility, Audit, Results/Tallying, Arbitration). No missing aggregates. No cross-context issues requiring resolution before proceeding.

---

## 7. Approved Aggregate Inventory

| # | Aggregate | Context | Status | Confidence | Key Decision |
|---|-----------|---------|--------|------------|--------------|
| 1 | **Verification** | Trust Attestation | ✅ Stable | HIGH | Is this identity trustworthy? |
| 2 | **GovernanceState** | Constitutional Governance | ✅ Stable | MEDIUM-HIGH | Is this lifecycle transition allowed? |
| 3 | **Vote** | Voting | ✅ Stable | HIGH | Is this vote valid and anonymous? |
| 4 | **RoleAssignment** | Authorization | ⚠️ Provisional | MEDIUM-LOW | Which user has which role for which election? |
| 5 | **ReplaySession** | Governance Evidence Replay | ⚠️ Provisional | MEDIUM | Did replay outcome match expected outcome? |

---

## 8. Remaining Discovery Debts

| Debt | Affects | Priority | Recommendation |
|------|---------|----------|---------------|
| ADC-1/ADC-2 | RoleAssignment | MEDIUM | Carry forward to future discovery or design phases |
| ADGR-1 | ReplaySession | MEDIUM | Carry forward to future discovery or design phases |
| ADG-2 | GovernanceDecision | MEDIUM | Carry forward to future discovery or design phases |
| ADH-1 | GovernanceDecision/ConstitutionalDecision | MEDIUM | Carry forward to future discovery or design phases |

---

## 9. Summary

| Challenge | Finding | Verdict |
|-----------|---------|---------|
| Q16: Aggregate Density | 4 contexts with 0 aggregates — all legitimate patterns | ✅ Confirmed |
| Q17: Missing Aggregate | No concept appears to be a missed aggregate | ✅ Confirmed |
| Q18: Aggregate Completeness | All invariants covered by aggregate or legitimate pattern | ✅ Confirmed |
| Q19: Cross-Context Consistency | No shared aggregate required | ✅ Confirmed |
| Q20: Tactical Discovery Closure | All criteria met | ✅ Confirmed |

---

**Round 28A Final Aggregate Challenge — COMPLETE**

**Tactical DDD discovery is certified complete. 5 aggregates (3 stable, 2 provisional) across 9 contexts. 4 contexts with no aggregate — all legitimate patterns. No missing aggregates. No cross-context issues. Discovery debts carried forward. Ready for next governance phase.**
