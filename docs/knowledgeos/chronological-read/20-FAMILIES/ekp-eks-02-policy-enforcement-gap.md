# ekp-eks-02-policy-enforcement-gap

**Scope(s):** OBJECT · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `EKS-02` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G0024**: [`capability-c14-policy-enforcement` · `ekp-eks-02-policy-enforcement-gap`] — explicit agent-stated uncertainty: 'ekp-eks-02-policy-enforcement-gap' POSSIBLY relates to 'capability-c14-policy-enforcement' (batch B0003). Note: Backlog item: doc-placement.php derives policy but does not enforce it; a coverage gap in existing enforcement machinery rather than a missing capability.
- **G1130**: [`capability-c14-policy-enforcement` · `ekp-eks-02-policy-enforcement-gap`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **PROPOSAL** batch `B0003`, scope `OBJECT`: Backlog item: doc-placement.php derives policy but does not enforce it; a coverage gap in existing enforcement machinery rather than a missing capability. (relation_to_existing: POSSIBLY:capability-c14-policy-enforcement)

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0091 §"EKS-02: three capabilities are routinely conflated -- policy authorship, policy derivation (C-13, live), policy enforcement (C-14, absent). Only the third is missing, and no role fixes it; a gate does."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0102. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S0102 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0091 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Original (later superseded) analysis: mapping the nine-stage policy-to-effect path (definition, derivation, evaluation, observation, admission control, blocking/halt, escalation, audit, advisory tripwires) against evidence shows every stage already performed by an owned capability (chiefly CAP-09/BC-3), so EKS-02's gap is a coverage gap within existing machinery, not a missing capability -- this categorical denial is itself withdrawn later by Amendment 1 as a category argument doing an existence test's job. [S0102]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0091] types=['DISTINCTION'] scope=OBJECT — "Finding F-7: policy authorship, policy derivation (C-13, live via doc-placement.php), and policy enforcement (C-14, initially claimed absent) are three distinct capabilities routinely conflated; the fix for a missing enforcement capability is a mechanical gate, not a named role." (anchor: "EKS-02: three capabilities are routinely conflated -- policy authorship, policy derivation (C-13, live), policy enforcement (C-14, absent). Only the third is missing, and no role fixes it; a gate does.")
- [S0102] types=['ANALYSIS', 'CORRECTION'] scope=OBJECT — "Original (later superseded) analysis: mapping the nine-stage policy-to-effect path (definition, derivation, evaluation, observation, admission control, blocking/halt, escalation, audit, advisory tripwires) against evidence shows every stage already performed by an owned capability (chiefly CAP-09/BC-3), so EKS-02's gap is a coverage gap within existing machinery, not a missing capability -- this categorical denial is itself withdrawn later by Amendment 1 as a category argument doing an existence test's job." (anchor: "the answer is NO: on current evidence there is no distinct C-14 capability. Every stage of the policy-to-effect path is already performed by an owned capability ... What EKS-02 actually records is not a missing capability but a missing COVERAGE")

## Notes for P3
- This label participates in 2 candidate group(s) (G0024, G1130) — per R5/R12 this is not an identity claim; P3 should review whether any group member denotes the same underlying object as this label.
- Thin evidence base (n=2 row(s)) — treat conclusions here as provisional.
