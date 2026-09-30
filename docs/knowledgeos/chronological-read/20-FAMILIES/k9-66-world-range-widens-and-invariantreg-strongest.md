# k9-66-world-range-widens-and-invariantreg-strongest

**Scope(s):** OBJECT · **Row count:** 2 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `15..23 of 29` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- G0641: this label's node_metadata lists group_id G0641 — relationship not yet decided (P3).

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0067, scope OBJECT: "The widened 66-world closure-size range and the confirmation that InvariantReg remains the single most robustly-blocked construct."

No `single_candidate_flags` recorded.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2803 §"review lane 14 worlds, range 15...22 of 29. extended 66 worlds, range 15...23 of 29. Their negative claim understates itself ... 9 of 29 constructs of spread. ... {Identity, InvariantReg, K, Proposition, Relation} identical over 66 worlds. This is the strongest positive result in the package, and it"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2923. Candidate lifecycle: ACTIVE.
Evidence: `lifecycle_evidence` (retracted_by/superseded_by/contested_by_own_contradiction_type) is entirely empty in the structured lifecycle-evidence field. However, the row content itself is important context: S2923 (a later row, type CORRECTION/LIMITATION) directly re-examines and undercuts the evidentiary weight of the S2803 "66/66 worlds" robustness finding, calling it circular (the un-enumerated status of InvariantReg was hard-coded as an input, not independently derived). This internal tension is not captured by the mechanical `lifecycle_evidence` fields but is visible in the rows themselves — see Notes for P3.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2803 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE (`rationale_evidence` is empty; `rationale_truncated_count` is 0).

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- [S2803] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Section 4.1-4.2: under the widened 66-world design (full product over the 5 perturbable rows x 2 edge variants, versus the original 14-world design), the closure-size range widens from 15..22 to 15..23 -- meaning the prior negative claim ('the kernel requirement set's size is not determined by the corpus') actually understated itself; the 5-item triply-robust blocked set {Identity, InvariantReg, K, Proposition, Relation} is unchanged even at 4.7x the world count, named the strongest positive result in the package, with InvariantReg (the un-enumerated invariant register, G-67) confirmed as blocked under literally every tested reading of every axis." (anchor as above)
- [S2923] types=[CORRECTION, LIMITATION] scope=OBJECT — "Critically re-examines the widely-cited '66 of 66 worlds' robustness finding (from a prior batch's k9-closure computation) supporting the InvariantReg-never-enumerated premise: finds the source code exec/extend_k9_worlds.py line 15 HARD-CODES the string 'NOT ENUMERATED' as an INPUT to all 66 world variants, so the 66/66 result measures only the robustness of the closure computation to world CHOICE, not the actual enumeration status of the invariant register -- the computation itself is sound, but its premise is exactly the claim it was being offered as evidence FOR. This is a circularity finding that undercuts the evidentiary weight of the prior 66-world robustness research thread on this specific premise." (anchor: "And 'blocked in 66 of 66 worlds' is not evidence of absence ... exec/extend_k9_worlds.py:15 hard-codes the premise: G_str['InvariantReg'] = (['K'], 'NOT ENUMERATED', 'D') ... 66/66 therefore measures the robustness of the closure computation to world choice — it does not measure the enumeration status of ℐ. ... the premise is the claim it is offered to support.") — note S2923 also carries two other labels not in this batch's 21: `theory-v1-0-def-register-findings-2026-09-10` and `k9-verification-settled-and-untouched`.

## Notes for P3
**Internal tension flagged:** row S2803 presents the "66 of 66 worlds" InvariantReg-blocking result as "the strongest positive result in the package," while row S2923 (a later source) directly identifies that result as circular — the "NOT ENUMERATED" status was hard-coded as an input to the closure computation rather than independently derived, so 66/66 robustness measures only the computation's robustness to world choice, not the actual enumeration status. This is a genuine within-label contradiction that the mechanical `lifecycle_evidence.contested_by_own_contradiction_type` field did NOT flag as true — P3 should treat this label's positive claim (from S2803) as significantly weakened, per S2923's own correction, and should investigate whether `contested_by_own_contradiction_type` should have fired here. S2923 also cross-references two labels outside this batch's 21 (`theory-v1-0-def-register-findings-2026-09-10`, `k9-verification-settled-and-untouched`) — those may carry more context on this same tension.
