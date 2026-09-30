# independence-factor-formula

**Scope(s):** OBJECT · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** IndependenceFactor = 1/(1+depth) · **Aliases:** Step 270 s270.1
**Candidate group membership (NOT an identity claim):**
- **G0422** [`graph-vs-statistical-independence-distinction` · `independence-factor-formula`] — explicit agent-stated uncertainty: 'graph-vs-statistical-independence-distinction' POSSIBLY relates to 'independence-factor-formula' (batch B0041). Note: Step 271's principle that a dependency-graph relationship (graph independence) does not establish statistical independence, and any function of graph depth (e.g. IndependenceFactor=1/(1+depth)) must be classified as a structural score rather than an independence measure absent a statistical derivation; later empirically confirmed by exp_measurement.py EXP-6.
- **G1706** [`aggregate-support-formula` · `independence-factor-formula`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus


## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch `B0041`, scope `OBJECT`: A corpus-proposed evidence-independence formula, executed and shown to conflate dependency-graph depth with statistical independence (assigns a verbatim copy the same weight as an independent observation).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1677] §"the formula assigns e2 (a verbatim copy) and e3 (an independent observation) the SAME weight, because depth is a graph property and independence is a statistical one. => IndependenceFactor is not a measure of independence. CONFIRMED."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1714. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used (S1714), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | PRESENT | S1677 |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1677, S1705, S1714 |
| assumptions | PRESENT | S1677 |
| semantics | PRESENT | S1714 |
| examples | PRESENT | S1677 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1677, S1705 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
| Statement | Stated | Source ID | Anchor |
|---|---|---|---|
| e2 verbatim-copies e1's content and therefore carries zero additional information | EXPLICIT | S1677 | "e2: a mirror that VERBATIM COPIES the vendor API, depth 1" |

## All rows (source_id order)
- [S1677] types=[EXPERIMENTAL-RESULT, COUNTEREXAMPLE] scope=OBJECT — "EXP-6 constructs a verbatim-copy evidence item (e2, depth 1) and a genuinely independent second-source item (e3, also depth 1) and shows IndependenceFactor=1/(1+depth) (Step 270 s270.1) assigns them the identical weight (0.5), while the ground truth is that e2 contributes zero additional information; concludes IndependenceFactor conflates a graph property (path depth) with a statistical property (independence) -- a category error." (anchor: "the formula assigns e2 (a verbatim copy) and e3 (an independent observation) the SAME weight, because depth is a graph property and independence is a statistical one. => IndependenceFactor is not a measure of independence. CONFIRMED.")
- [S1705] types=[EXPERIMENTAL-RESULT, CORRECTION] scope=OBJECT — "Finding MT-4: re-executes and re-confirms AggregateSupport=sum(s)/(1+log n) is unbounded (100 unit-strength items give 17.84) and non-idempotent (duplicating one evidence item raises support by 18.1%, directly rewarding exactly the duplication the corpus's own dependency-normalization work (025c-2, 025c-3, exp01_recheck.py) exists to prevent), and dimensionally undefined; and re-confirms IndependenceFactor=1/(1+depth) assigns a verbatim-copy item and a genuinely independent item the identical weight (0.5 each), naming this a category error (path-length discount mislabeled as independence) predicted in prose by Step 270 and here executed as a demonstration; both formulas remain usable only as declared engineering heuristics, never as measurements." (anchor: "Evidence duplication is precisely what the corpus's dependency-normalization work (025c-2, 025c-3, exp01_recheck.py) exists to prevent, and this formula rewards it. ... The formula assigns a verbatim copy and a genuinely independent observation the SAME weight ... It is a path-length discount. Naming it independence is a category error.")
- [S1714] types=[CORRECTION, RESTATEMENT] scope=THEORY-LEVEL — "Finding FR-4 (F-2, one of the three refutations that matter most): summarizes that averaging over sigma, AggregateSupport, IndependenceFactor, and confidence-as-probability all fail an executed measurement-admissibility check outright and none is salvageable as a measurement, but characterizes this as a naming-discipline problem fixable with a one-line relabelling per formula (as declared engineering heuristics), noting Step 270 was already moving in that direction." (anchor: "Four quantitative claims are refuted outright ... All four fail an executed admissibility check. None is salvageable AS A MEASUREMENT; all four are usable as declared engineering heuristics once relabelled. This is a naming discipline problem with a one-line fix per formula, and Step 270 was already heading there.")

## Notes for P3
- No unusual internal tensions or notable evidentiary anomalies observed while compiling this file.
