# Round 27F-ARB — Results/Tallying Challenge Review

**Date:** 2026-06-08

**Phase:** Tactical DDD — Aggregate Challenge Review (Pre-Acceptance)

**Status:** Complete — Ready for ARB Decision

---

## 1. Purpose

Challenge the conclusion from Round 27F that Results/Tallying has 0 aggregates and 0 independent decision ownership. Apply adversarial review using future variability and latent domain complexity considerations.

---

## 2. Challenge Q5: Electoral Formula Variability

**Source:** Round 24G (Electoral System Variability Notes)

### Background

Round 24G identified that electoral formulas (FPTP, STV, List PR, MMP, Borda, D'Hondt, Sainte-Laguë, Droop Quota) are potential sources of domain variability not yet present in the current system.

### Current Implementation

The current timestamp is simple SQL aggregation:
```sql
SELECT post_id, candidacy_id, COUNT(*) as vote_count FROM results GROUP BY post_id, candidacy_id
```

This works because the current system uses a single electoral model (First-Past-The-Post or equivalent simple plurality).

### Future Variability Consideration

If the system must support alternative electoral formulas:

| Formula | Tally Decision Required | Currently Owned By? |
|---------|------------------------|---------------------|
| FPTP | Count votes per candidate | Current SQL aggregation (VoteCount computation) |
| STV | Surplus vote transfer, elimination rounds, quota calculation | **Not currently owned** — no STV logic exists |
| List PR | Seat allocation by party vote share, threshold calculation | **Not currently owned** — no PR logic exists |
| MMP | Mixed vote allocation, seat adjustment, overhang seats | **Not currently owned** — no MMP logic exists |
| Borda | Preference weighting, point aggregation | **Not currently owned** — no preference weighting exists |
| D'Hondt/Sainte-Laguë | Divisor-based seat allocation | **Not currently owned** — no divisor logic exists |

### Analysis

Each alternative formula would require:
- **Formula selection logic** — what formula applies to this election?
- **Formula-specific invariants** — e.g., "surplus votes must be transferred before finalizing"
- **Formula-specific state** — e.g., round counts for STV, seat allocation tables for list PR

These are genuine business decisions that exist independently of vote recording. A D'Hondt divisor calculation is a different domain concept from "record this voter's candidate choice."

### Impact Assessment

| Outcome | Assessment |
|---------|------------|
| **Does formula variability create independent decision ownership?** | **Yes, potentially.** Formula selection, formula-specific calculation rules, and formula-specific invariants are distinct from vote recording. |
| **Does the current system need this?** | **No.** The current system supports a single formula. D39 asks whether counting has independent meaning — formula variability would answer "yes." |
| **Should this affect Round 27F aggregate inventory?** | **No.** Future variability does not create current aggregates. However, the conclusion "Results/Tallying has 0 decision ownership" should be qualified: "0 decision ownership **in the current implementation**." |

**Conclusion:** Formula variability could create independent decision ownership in Results/Tallying, but this is a future concern, not a current domain fact. The current conclusion (0 aggregates) stands, but should be qualified to acknowledge future potential.

---

## 3. Challenge Q6: Counting Meaning Challenge

**Source:** D39, ADR-003, Round 24G, Stream 3, Stream 5

### Background

D39 asks: "What is the domain meaning of the counting state?" The answer determines whether counting represents:
- A publication gate (current implementation evidence)
- A latent business capability (future potential)
- A governance policy (legal/regulatory requirement)
- A domain concept with independent decisions

### Current Evidence

| Evidence | Interpretation A: Publication Gate | Interpretation B: Latent Capability | Interpretation C: Governance Policy |
|----------|-----------------------------------|-------------------------------------|-------------------------------------|
| ADR-003 defines counting as state 8 of 10 | Stage after voting, before publication | Constitutional recognition of counting as distinct stage | Legal requirement: "counting must be a separate phase" |
| No counting logic observed at state entry | Confirms no operational meaning | Confirms counting is not yet implemented | Confirms enforcement is about timing, not computation |
| close_voting transitions to counting | Publishing must wait | Counting is a waiting period | Legal waiting period |
| publish_results requires counting state | Publication gated by lifecycle | Counting completion is a precondition | Governance precondition |
| Votes → Results immediately on vote save | Results computed at vote time | No separate counting step | Implementation detail — counting policy is about legal separation, not technical separation |

### Latent Business Capabilities

If counting is not merely a publication gate but a latent domain concept, the following capabilities might belong to it:

| Capability | Currently Owned By | Would Move To Counting If Concept Emerges |
|-----------|-------------------|-------------------------------------------|
| Invalid ballot handling | None observed | Counting (review and disposition of invalid ballots) |
| Tie resolution mechanism | None observed | Counting (formula-based or procedural) |
| Recount handling | None observed | Counting (repeat vote aggregation) |
| Certification result | Governance (publish_results) | Counting (verify and certify before publication) |
| Dispute window management | None observed (D33) | Counting (time-boxed challenge period) |

### Assessment

| Outcome | Assessment |
|---------|------------|
| **Does counting have latent business meaning?** | **Possible, but not evidenced.** D39 remains unresolved. ADR-003 defines counting as a constitutional state but the operational implementation is empty. |
| **Should counting be classified as Governance Policy?** | **Best current answer.** The evidence supports counting as a lifecycle gate (publication must wait). Whether counting represents a genuine business capability or a legal waiting period is unknown. |
| **Should this affect Round 27F aggregate inventory?** | **No.** Latent capabilities do not create current aggregates. However, the conclusion should acknowledge D39 uncertainty rather than definitively classifying counting as "just a policy." |

**Conclusion:** Counting is best classified as a Governance Policy based on current evidence. D39 remains open — if future implementation adds counting logic (invalid ballot handling, recount, certification), counting could become a domain concept with its own aggregate.

---

## 4. Revised Conclusions

### What Changes

| Original Conclusion | Revised Conclusion | Change |
|--------------------|-----------------|--------|
| Results/Tallying has 0 aggregates | Results/Tallying has 0 aggregates **in current implementation** | Qualified — acknowledges future variability (Q5) |
| Counting is a Governance Policy | Counting is a Governance Policy **based on current evidence**; D39 remains unresolved | Qualified — acknowledges latent potential (Q6) |
| Results/Tallying has 0 independent decision ownership | Results/Tallying has 0 independent decision ownership **in current implementation**; formula variability could create future ownership | Qualified — acknowledges future potential |

### What Does Not Change

| Conclusion | Status | Rationale |
|-----------|--------|-----------|
| Result (row) is a Derived Entity | Unchanged | Derived from Vote regardless of formula or counting meaning |
| VoteCount is a Computation | Unchanged | Even with complex formulas, counting is the algorithm — vote count is the input data |
| Projection Test passes | Unchanged | Future formula complexity does not change the fact that current Results are reconstructable from Votes |
| Merger with Voting is supported | Unchanged | Current evidence supports merger; future formula variability is a separate concern |

---

## 5. Revised Aggregate Inventory

| Concept | Type | Confidence | Qualification |
|---------|------|------------|---------------|
| Result (row) | Derived Entity (within Voting) | HIGH | Unchanged |
| VoteCount | Computation | HIGH | Unchanged |
| Counting | Governance Policy | MEDIUM | D39 remains open — current evidence supports policy; future formula variability could create domain meaning |
| ResultsPublication | Governance Policy | HIGH | Unchanged |
| ResultSet | Read Model | HIGH | Unchanged |

---

## 6. Updated Discovery Debt

| Debt | Question | Priority | Status |
|------|----------|---------|--------|
| ADR-2 (D39) | What is the domain meaning of the counting state? | MEDIUM | **Remains open** — challenge review confirms this is unresolved |
| ADR-4 (NEW) | Should the Results/Tallying aggregate inventory be reassessed if electoral formula variability becomes a requirement? | LOW | Future concern — not actionable now |

---

## 7. Summary

**Challenge review outcomes:**

1. **Formula variability (Q5):** Could create independent decision ownership in Results/Tallying, but this is a future concern. Current conclusion (0 aggregates) stands, qualified for future awareness.

2. **Counting meaning (Q6):** Counting is best classified as Governance Policy based on current evidence. D39 remains open — counting may have latent business meaning (invalid ballot handling, recount, certification) that could emerge in future implementation.

3. **No changes to aggregate inventory** — both challenges affirm the current inventory while qualifying it for future variability. The strongest merger evidence in the system remains intact.

---

**Round 27F-ARB Results Challenge Review — READY FOR ARB DECISION**

**0 aggregates in current implementation confirmed. Future formula variability could create independent decision ownership. D39 remains open. Aggregate inventory unchanged but qualified. Merger evidence remains strongest in the system.**
