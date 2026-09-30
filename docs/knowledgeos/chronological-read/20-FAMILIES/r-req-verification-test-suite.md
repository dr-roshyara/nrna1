# r-req-verification-test-suite

**Scope(s):** METHODOLOGICAL · **Row count:** 2 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Round-Trip Transformation Test`, `Separation Test`
**Aliases:** `R_req formal verification criteria`
**Candidate group membership (NOT an identity claim):**
- G0607: [`r-req-verification-criteria-critique` · `r-req-verification-test-suite`] — explicit agent-stated uncertainty: 'r-req-verification-criteria-critique' POSSIBLY relates to 'r-req-verification-test-suite' (batch B0061). Note: Reviews five verification criteria (more than the two-part suite seen in S2551), each with a strength, a weakness, and a fix: Distinction Preservation needs a context parameter; Transformation Invariance needs an exception for intentional distinction-modifying transformations; Projection Adequacy is 'too strong' and should allow Tier 2/3 loss but not Tier 1; Composition Preservation should be verified only for core compositions (full verification may be impossible); Zero Compliance should additionally require all Tier 1 distinctions to be represented.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0061, scope METHODOLOGICAL: Two-part verification suite for any proposed kernel/projection pi: the Separation Test (for every pair of distinct values in any distinction d, two minimal test states must map to different encodings, else verification FAILS with distinction collapsed) and the Round-Trip Transformation Test (for a projection f and reconstruction g, applying g after f must preserve every distinction in R_req).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2551 §"1. The Separation Test: For every pair of distinct values (v_i,v_j) in any distinction d in R_req, construct two minimal test states s_i,s_j. Criterion: pi(E(s_i)) != pi(E(s_j)). If equality holds, verification FAILS. ... 2. The Round-Trip Transformation Test: for f:K->K' and g:K'->K, forall d in R_req, s ~_d (g∘f)(s)."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: [S2551 §"1. The Separation Test: For every pair of distinct values (v_i,v_j) in any distinction d in R_req, construct two minimal test states s_i,s_j. Criterion: pi(E(s_i)) != pi(E(s_j)). If equality holds, verification FAILS. ... 2. The Round-Trip Transformation Test: for f:K->K' and g:K'->K, forall d in R_req, s ~_d (g∘f)(s)."]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2567. Candidate lifecycle: ACTIVE.
Evidence: none recorded (retracted_by and superseded_by both empty, no own-contradiction trigger). Since lifecycle_candidate is ACTIVE, this is a heuristic based on how recently (by source_id) this label was last used (S2567), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S2567 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2567 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2551 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2551] types=['VALIDATION', 'EXPERIMENT'] scope=METHODOLOGICAL — "Gives a formal two-part test suite for verifying any candidate KnowledgeOS kernel or projection preserves R_req: the Separation Test (minimal-state pairs per distinction value pair must map to distinct encodings, else FAIL) and the Round-Trip Transformation Test (a round-trip through a projection and reconstruction must preserve every distinction relation)." (anchor: "1. The Separation Test: For every pair of distinct values (v_i,v_j) in any distinction d in R_req, construct two minimal test states s_i,s_j. Criterion: pi(E(s_i)) != pi(E(s_j)). If equality holds, verification FAILS. ... 2. The Round-Trip Transformation Test: for f:K->K' and g:K'->K, forall d in R_req, s ~_d (g∘f)(s).")
- [S2567] types=['RESTATEMENT'] scope=METHODOLOGICAL — "Restates the R_req two-part verification suite (Separation Test: distinct-value pairs must map to distinct encodings or verification FAILS; Round-Trip Transformation Test: g-after-f must preserve every R_req distinction) and the three priority tiers (P1 Core Invariants: Epistemic Status, Justification Mode, Currency, Absence-vs-Evidence-of-Absence; P2 Required: Allen Relations, Resolution Status, Scope Boundary, Intensional Identity; P3 Extended: deontic distinctions, fine-grained probability)." (anchor: "Verification Criteria: The Separation Test ... The Round-Trip Transformation Test ... Prioritization: Tier P1 (Core Invariants) ... Tier P2 (Required) ... Tier P3 (Extended Domain-Specific)")

## Notes for P3
(none beyond what is noted above)
