# aggregate-support-formula

**Scope(s):** OBJECT · **Row count:** 6 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `AggregateSupport = sum(s_i)/(1+log n)` · **Aliases:** `Step 270 s270.28`
**Candidate group membership (NOT an identity claim):**
- **G1706** [`aggregate-support-formula` · `independence-factor-formula`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0041, scope OBJECT): A corpus-proposed evidence-aggregation formula, executed and shown unbounded, non-idempotent, dimensionally undefined.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1677] §"unbounded: 100 unit-strength items give ... not a support DEGREE. ... not idempotent ... dimensionally undefined ... n=1 gives sum/1 -- so the formula reduces to the raw strength only in the singleton case, i.e. it is a DISCOUNT on plurality, not an aggregation rule."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1727. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | NOT-EVIDENCED-IN-CAPTURE | — |
| Type signature | PRESENT | S1677 |
| Invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| Dependencies | PRESENT | S1677, S1704, S1705, S1714 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S1714 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | PRESENT | S1677, S1704, S1705, S1727 |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1677] types=[EXPERIMENTAL-RESULT, CORRECTION] scope=OBJECT — "EXP-5 executes AggregateSupport=sum(strengths)/(1+log n) (Step 270 s270.28) over several cases and finds it unbounded (100 unit-strength items give ~21.7, not a bounded support degree), not idempotent (duplicating identical evidence changes the value), dimensionally undefined, and reducing to raw strength only at n=1 -- i.e. it functions as a plurality discount, not a measurement of support under any declared scale." (anchor: "unbounded: 100 unit-strength items give ... not a support DEGREE. ... not idempotent ... dimensionally undefined ... n=1 gives sum/1 -- so the formula reduces to the raw strength only in the singleton case, i.e. it is a DISCOUNT on plurality, not an aggregation rule.")
- [S1704] types=[EXPERIMENTAL-RESULT, VALIDATION] scope=OBJECT — "Finding EG-5: running reviews/synthesis/analysis/mathematical-tests/exp01_recheck.py (from the Claude review thread) shows the corpus's central negative result ('no simple scalar operator is sufficient as the epistemic foundation') does not follow from its own cited 7-column matrix alone (since a formula called SATURATING passes all seven columns), but does follow once criterion E (no scalar can retain the pair (S+,S-)) plus pre-operator dependency/duplicate-work considerations plus a calibration caution are added; the negative conclusion is thus CONFIRMED as provable but for a different reason than originally given -- converging with 06-SIGMA-GAP-ANALYSIS.md's independent finding that the evidence axis needs at least two dimensions (support, refutation) plus a sufficiency judgement, refuting any single-scalar formula such as AggregateSupport=sum(s)/(1+log n)." (anchor: "The published negative verdict ... does NOT follow from the 7-column matrix alone ... It DOES follow from the matrix PLUS criterion E (no scalar can retain (S+,S-)) PLUS the pre-operator dependency/duplicate work PLUS the calibration caution ... the negative conclusion is CONFIRMED and is in fact provable. ... Evidence standing is at least two-dimensional (support and refutation), plus a sufficiency judgement relative to a bar.")
- [S1705] types=[EXPERIMENTAL-RESULT, CORRECTION] scope=OBJECT — "Finding MT-4: re-executes and re-confirms AggregateSupport=sum(s)/(1+log n) is unbounded (100 unit-strength items give 17.84) and non-idempotent (duplicating one evidence item raises support by 18.1%, directly rewarding exactly the duplication the corpus's own dependency-normalization work (025c-2, 025c-3, exp01_recheck.py) exists to prevent), and dimensionally undefined; and re-confirms IndependenceFactor=1/(1+depth) assigns a verbatim-copy item and a genuinely independent item the identical weight (0.5 each), naming this a category error (path-length discount mislabeled as independence) predicted in prose by Step 270 and here executed as a demonstration; both formulas remain usable only as declared engineering heuristics, never as measurements." (anchor: "Evidence duplication is precisely what the corpus's dependency-normalization work (025c-2, 025c-3, exp01_recheck.py) exists to prevent, and this formula rewards it. ... The formula assigns a verbatim copy and a genuinely independent observation the SAME weight ... It is a path-length discount. Naming it independence is a category error.")
- [S1714] types=[CORRECTION, RESTATEMENT] scope=THEORY-LEVEL — "Finding FR-4 (F-2, one of the three refutations that matter most): summarizes that averaging over sigma, AggregateSupport, IndependenceFactor, and confidence-as-probability all fail an executed measurement-admissibility check outright and none is salvageable as a measurement, but characterizes this as a naming-discipline problem fixable with a one-line relabelling per formula (as declared engineering heuristics), noting Step 270 was already moving in that direction." (anchor: "Four quantitative claims are refuted outright ... All four fail an executed admissibility check. None is salvageable AS A MEASUREMENT; all four are usable as declared engineering heuristics once relabelled. This is a naming discipline problem with a one-line fix per formula, and Step 270 was already heading there.")
- [S1727] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "EXP-5: AggregateSupport = sum(s_i)/(1+log n) is refuted as a measurement: it is unbounded (100 unit-strength items give 17.84), non-idempotent (duplicating identical evidence increases support 18.1%), and dimensionally undefined (the quotient has no interpretation as a probability or degree of belief); it reduces to raw strength only in the singleton case, so it functions as a discount on plurality rather than an aggregation rule; Step 270's own suspicion ('elegance is not derivation') is confirmed by execution." (anchor: "=> The formula is computable (Level B) but is NOT a measurement of support      under any declared scale (Level C fails).")
- [S1727] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "EXP-6: IndependenceFactor=1/(1+depth) assigns a verbatim-copy mirror source (depth 1, carrying zero additional information) the identical weight (0.5) as a genuinely independent second source at the same depth, because depth is a graph property while independence is a statistical one; naming it 'independence' is confirmed to be a category error Step 270 itself predicted." (anchor: "=> IndependenceFactor is not a measure of independence. CONFIRMED.")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
