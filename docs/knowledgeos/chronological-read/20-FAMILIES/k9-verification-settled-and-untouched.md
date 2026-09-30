# k9-verification-settled-and-untouched

**Scope(s):** OBJECT · **Row count:** 2 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0067, scope OBJECT: "The final settled-vs-untouched summary and the two forward-looking recommendations (enumerate InvariantReg; don't run a minimality argument while O_core is a coin flip)."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2803 §"the two bases are incomparable ... K_4 stipulates 2 seeds no C-row forces ... InvariantReg is blocked in every world (66/66) ... InvariantReg (G-67) is now the single most evidenced blocker in the estate ... O_core's 51% is a reason NOT to run a minimality argument next."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2803 §"the two bases are incomparable ... K_4 stipulates 2 seeds no C-row forces ... InvariantReg is blocked in every world (66/66) ... InvariantReg (G-67) is now the single most evidenced blocker in the estate ... O_core's 51% is a reason NOT to run a minimality argument next."]

## Lifecycle
last_seen: S2923. Candidate lifecycle: ACTIVE. Evidence: retracted_by and superseded_by are both empty and no row is self-typed as a contradiction; this is a heuristic based on how recently (by source_id order) this label was last used (S2923), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2803 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2803] types=[RESTATEMENT, GOVERNANCE] scope=OBJECT — "Sections 6-7: tabulates what the execution settles (bases incomparable, K4's two unforced seeds, C-rows' eight K4-unnamed seeds, 4 of 8 Section-E exclusions being K4-relative not absolute, InvariantReg blocked in all 66 worlds, requirement-set size undetermined at 15..23) against what remains untouched (which reading is right as a governance act, sufficiency of either basis, the Reject/I-12/Article-8 contradiction, Replay's type-level contradiction, Qualify's closure under K9, and anything about minimality); explicitly states this does not authorize calling K9 the kernel, freezing O_core, or opening Theory v1.3; recommends enumerating InvariantReg (G-67) as the single most evidenced blocker in the estate (a derivation, not a decision) and treating O_core's 51% figure as a reason not to run a minimality argument next, since any such result would inherit a coin flip." (anchor: "the two bases are incomparable ... K_4 stipulates 2 seeds no C-row forces ... InvariantReg is blocked in every world (66/66) ... InvariantReg (G-67) is now the single most evidenced blocker in the estate ... O_core's 51% is a reason NOT to run a minimality argument next.")
- [S2923] types=[CORRECTION, LIMITATION] scope=OBJECT — "Critically re-examines the widely-cited '66 of 66 worlds' robustness finding (from a prior batch's k9-closure computation) supporting the InvariantReg-never-enumerated premise: finds the source code exec/extend_k9_worlds.py line 15 HARD-CODES the string 'NOT ENUMERATED' as an INPUT to all 66 world variants, so the 66/66 result measures only the robustness of the closure computation to world CHOICE, not the actual enumeration status of the invariant register -- the computation itself is sound, but its premise is exactly the claim it was being offered as evidence FOR. This is a circularity finding that undercuts the evidentiary weight of the prior 66-world robustness research thread on this specific premise." (anchor: "And 'blocked in 66 of 66 worlds' is not evidence of absence ... exec/extend_k9_worlds.py:15 hard-codes the premise: G_str['InvariantReg'] = (['K'], 'NOT ENUMERATED', 'D') ... 66/66 therefore measures the robustness of the closure computation to world choice — it does not measure the enumeration status of ℐ. ... the premise is the claim it is offered to support.")

## Notes for P3
- This label is ungrouped in P2a — no mechanical signal (token overlap, co-occurrence, or explicit cross-reference) connected it to any other label in this batch's normalization pass.
