# step289-degeneracy-audit-principle

**Scope(s):** METHODOLOGICAL · **Row count:** 4 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `1 block universal`, `block count`, `|carrier| blocks discrete` · **Aliases:** `the degeneracy audit rule`
**Candidate group membership (NOT an identity claim):**
- **G1748**: candidate group with `sigma-five-axis-epistemic-state-q4a` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus (mechanical signal only; relationship not yet decided, P3).



## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0052, scope METHODOLOGICAL): A promoted, standing methodological rule in Step 289: no algebraic property (e.g. reflexivity/symmetry/transitivity) may be accepted from a finite exhaustive zero-counterexample test without checking whether the tested relation is degenerate (universal, empty, singleton, identity, or otherwise collapsed) -- with a mandatory companion measurement (block count) required for every relation reported as satisfying the equivalence axioms. Originates from the Step-288 approx_S-composed-with-approx_V incident (zero transitivity violations because the relation had collapsed to the universal one) and is applied retrospectively to fourteen major Step-287/288 results, downgrading three (the two degenerate approx_X endpoints and the known composition artifact), narrowing the usable observational-equality choice space from 32 to 30.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2157] §"$$\boxed{\begin{array}{c}\textbf{No algebraic property may be accepted from a zero-counterexample result}\\ \textbf{without first checking whether the tested relation is degenerate.}\end{array}}$$ **Required companion measurement:** every relation reported as satisfying the equivalence axioms must also report **its block count**."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2169. Candidate lifecycle: DORMANT. Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | PRESENT | S2157, S2169 |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | NOT-EVIDENCED-IN-CAPTURE | — |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | PRESENT | S2157, S2169 |
| Dependencies | PRESENT | S2157, S2169 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S2157 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | PRESENT | S2169 |
| Experiments | PRESENT | S2157 |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Notes this is the third occurrence in the research programme of a headline cardinality needing narrowing after measurement (a prior '47 tests' narrowed to 4; 'at most 32' tightened to 'exactly 32'; now 'exactly 32' narrowed to '30 usable'), diagnosing the recurring pattern not as arithmetic error but as a habit of reporting a computed cardinality without examining what its extreme/boundary cases actually mean -- generalizing the degeneracy rule into a broader lesson about verification-suite reporting: a family of relations that passes 'reflexive/symmetric/transitive' uniformly across its whole range looks strongest exactly where the relations have degenerately collapsed, since a collapsed relation cannot produce a counterexample to anything, so degeneracy artificially inflates apparent pass rates [S2157]. Determines that the 30 non-degenerate approx_X candidates (carried forward from Step 289's degeneracy audit, excluding the universal approx_empty-set at 1 block and the discrete approx_full at 2240 blocks) demonstrate UNDER-SPECIFICATION of a single parameter X, not a genuine multiplicity of 30 canonical candidate relations -- a bounded candidate family is never the same thing as a selected canonical relation. Explicitly warns that citing '30' is seductive because it looks like progress toward answering 'which approx?' when it actually answers a different question, 'how many ways could one read approx at the Sigma level?' -- and that approx_X was built in Step 287 to bound an undefined mathematical universe down to 30 enumerated options, which is real work, but is not itself evidence about the corpus's actual approx [S2169].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2157] types=[PRINCIPLE, EXTENSION] scope=METHODOLOGICAL — "States the promoted, standing audit principle: no algebraic property may be accepted from a zero-counterexample (finite exhaustive) test without first checking whether the tested relation is degenerate -- universal, empty, singleton, the identity, degenerate along one or more axes, or otherwise incapable of meaningfully exercising the property. Requires a mandatory companion measurement: every relation reported as satisfying the equivalence axioms must also report its block count, since both '1 block' (universal) and '|carrier| blocks' (fully discrete) trivially satisfy every equivalence axiom while carrying zero information. Traces the rule's origin to the step-288 §J incident (approx_S composed with approx_V returned zero transitivity violations only because it had collapsed to the universal relation)." (anchor: "$$\boxed{\begin{array}{c}\textbf{No algebraic property may be accepted from a zero-counterexample result}\\ \textbf{without first checking whether the tested relation is degenerate.}\end{array}}$$ **Required companion measurement:** every relation reported as satisfying the equivalence axioms must also report **its block count**.")
- [S2157] types=[EXPERIMENTAL-RESULT, CORRECTION] scope=OBJECT — "Applies the degeneracy rule retrospectively to all fourteen major equality/observability results from Steps 287-288, downgrading exactly three: approx_empty-set (the trivial projection) is the UNIVERSAL relation (1 block), axiom-satisfying but information-free, and should not be counted as a genuine candidate approx; approx_{A,S,R,V,C} (the full projection, = structural equality on Sigma) is the fully DISCRETE relation (2240 blocks, every class a singleton), valid as structural equality but not as an independent equivalence candidate since it relates nothing to anything but itself; and the previously-known approx_S composed with approx_V composition result (already downgraded at discovery). All other eleven results (the exactly-32-distinct-partitions count, the X-subset-Y refinement theorem, all positive counterexamples for congruence/hash/finite-sample/withdrawal, the value-level and fold-level decidability results, and the 13-of-13 negative results) STAND, since positive counterexamples cannot be vacuous by construction and are therefore immune to this failure mode." (anchor: "| 2 | `≈_∅` (the trivial projection) | 🔴 **UNIVERSAL** | **1** | ⚠️ **DOWNGRADE** | 3 | `≈_{A,S,R,V,C}` = `=` on `Σ` | 🔴 **DISCRETE (identity)** | **2240** | ⚠️ **DOWNGRADE as an *equivalence candidate*** ... $$\boxed{\textbf{3 downgraded of 14. None of the substantive findings; all three are ENDPOINT degeneracies.}}$$")
- [S2157] types=[ANALYSIS, PRINCIPLE] scope=METHODOLOGICAL — "Notes this is the third occurrence in the research programme of a headline cardinality needing narrowing after measurement (a prior '47 tests' narrowed to 4; 'at most 32' tightened to 'exactly 32'; now 'exactly 32' narrowed to '30 usable'), diagnosing the recurring pattern not as arithmetic error but as a habit of reporting a computed cardinality without examining what its extreme/boundary cases actually mean -- generalizing the degeneracy rule into a broader lesson about verification-suite reporting: a family of relations that passes 'reflexive/symmetric/transitive' uniformly across its whole range looks strongest exactly where the relations have degenerately collapsed, since a collapsed relation cannot produce a counterexample to anything, so degeneracy artificially inflates apparent pass rates." (anchor: "> ⚠️ **This is the third time in this programme a count has needed narrowing after measurement** (`47 tests → 4`; `at most 32 → exactly 32`; `32 → 30 usable`). **The pattern is not arithmetic error — it is reporting a computed cardinality without asking what the extremes mean.**")
- [S2169] types=[ARGUMENT, WARNING] scope=OBJECT — "Determines that the 30 non-degenerate approx_X candidates (carried forward from Step 289's degeneracy audit, excluding the universal approx_empty-set at 1 block and the discrete approx_full at 2240 blocks) demonstrate UNDER-SPECIFICATION of a single parameter X, not a genuine multiplicity of 30 canonical candidate relations -- a bounded candidate family is never the same thing as a selected canonical relation. Explicitly warns that citing '30' is seductive because it looks like progress toward answering 'which approx?' when it actually answers a different question, 'how many ways could one read approx at the Sigma level?' -- and that approx_X was built in Step 287 to bound an undefined mathematical universe down to 30 enumerated options, which is real work, but is not itself evidence about the corpus's actual approx." (anchor: "| **5** Do 30 survivors show under-specification or multiplicity? | ⭐ **UNDER-SPECIFICATION** | 30 survivors of one parameter is **one unfilled parameter**, not 30 canonical relations ... $$\boxed{\textbf{bounded candidate family} \neq \textbf{selected canonical relation}}$$ ... $$\boxed{\text{30 is a measure of UNDER-SPECIFICATION, not a menu.}}$$")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
