# measurement-theory-admissibility-gap

**Scope(s):** THEORY-LEVEL · **Row count:** 6 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Roberts Measurement Theory`, `admissible transformation` · **Aliases:** `EXP-4..EXP-7`
**Candidate group membership (NOT an identity claim):**
- **G0418**: [`measurement-representation-stage-unestablished` · `measurement-theory-admissibility-gap`] — explicit agent-stated uncertainty: 'measurement-representation-stage-unestablished' POSSIBLY relates to 'measurement-theory-admissibility-gap' (batch B0041). Note: The deepest measurement-theory finding: Roberts' framework has four stages (empirical structure, representation, scale, meaningful operations); the corpus imports only the third and fourth (assigning scale types and admissible transformations) and never executes the first -- no observable relation like 'a is at least as well supported as b' is ever shown to satisfy the axioms (weak order, solvability, Archimedean for higher scales) that would license any numerical representation of an epistemic quantity at all.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0041, scope THEORY-LEVEL): Reference-standard-driven executable check (Roberts: numerical statement MEANINGFUL iff truth value invariant under every admissible scale transformation) applied to corpus epistemic formulas; grounds 07-MEASUREMENT-THEORY-GAP.md.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1677] §"mean(sigma) >= 2 -> PROCEED ... decision flips across admissible re-encodings? True ... MEAN over an ordinal status ladder is MEANINGLESS (Roberts). CONFIRMED by execution."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1714. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows) — this DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | PRESENT | S1677, S1705 |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | NOT-EVIDENCED-IN-CAPTURE | — |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | PRESENT | S1677, S1705, S1705 |
| Dependencies | PRESENT | S1677, S1677, S1705, S1705, S1705, S1714 |
| Assumptions | PRESENT | S1677, S1677 |
| Semantics | PRESENT | S1705, S1714 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | PRESENT | S1677, S1677, S1705, S1705 |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
EXP-7 shows two mutually exclusive per-assertion 'confidence' values summing to more than 1 (0.8+0.7=1.5), which is impossible for probabilities on a shared disjoint sample space, demonstrating that per-assertion confidence in [0,1] is not a probability unless a common (Omega,F,P) is declared; the corpus declares such a triple only in the abandoned measure-theory regime (25 Aug), never for Sigma or assertion confidence. [S1677] Builds a full inventory of eight quantitative constructs in the corpus (sigma epistemic status, EvidenceStrength, AggregateSupport, IndependenceFactor, Confidence, Relevance, Zero/gap count, Distance) against the mandate's required measurement-theory columns (scale type, admissible ops, unit, dimension, value space, uncertainty, estimand, estimator, probability model), finding the probability-model column empty for all eight -- a structural finding about the corpus's state, not a gap in the table's construction. [S1705]

## Assumption register
| Statement | Stated | Source id | Anchor |
|---|---|---|---|
| the ladder Candidate/Supported/Accepted is genuinely ordinal with no further numeric structure | EXPLICIT | S1677 | Step 264 s264.23 |
| if two confidence values are probabilities of mutually exclusive events on a shared sample space, they cannot sum to more than 1 | EXPLICIT | S1677 | If these were probabilities on a common (Omega,F,P) ... the sum could not exceed 1. |

## All rows (source_id order)
- [S1677] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Executing EXP-4 shows that taking the mean of an ordinal epistemic-status ladder (Candidate/Supported/Accepted) under three admissible (order-preserving) numeric encodings produces different PROCEED/no-PROCEED verdicts for the same portfolio, demonstrating the mean is not a meaningful (Roberts-admissible) operation on an ordinal scale; min, max and median are shown to be invariant controls." (anchor: "mean(sigma) >= 2 -> PROCEED ... decision flips across admissible re-encodings? True ... MEAN over an ordinal status ladder is MEANINGLESS (Roberts). CONFIRMED by execution.")
- [S1677] types=[EXPERIMENTAL-RESULT, ARGUMENT] scope=OBJECT — "EXP-7 shows two mutually exclusive per-assertion 'confidence' values summing to more than 1 (0.8+0.7=1.5), which is impossible for probabilities on a shared disjoint sample space, demonstrating that per-assertion confidence in [0,1] is not a probability unless a common (Omega,F,P) is declared; the corpus declares such a triple only in the abandoned measure-theory regime (25 Aug), never for Sigma or assertion confidence." (anchor: "'confidence in [0,1]' is NOT a probability unless a common (Omega,F,P) is declared. ... Therefore 'uncertainty' is currently a NUMBER, not a MEASUREMENT.")
- [S1705] types=[ANALYSIS, EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "Builds a full inventory of eight quantitative constructs in the corpus (sigma epistemic status, EvidenceStrength, AggregateSupport, IndependenceFactor, Confidence, Relevance, Zero/gap count, Distance) against the mandate's required measurement-theory columns (scale type, admissible ops, unit, dimension, value space, uncertainty, estimand, estimator, probability model), finding the probability-model column empty for all eight -- a structural finding about the corpus's state, not a gap in the table's construction." (anchor: "The Probability model column is empty for every row. This is not an omission in my table; it is the state of the corpus.")
- [S1705] types=[EXPERIMENTAL-RESULT, CORRECTION] scope=OBJECT — "Finding MT-3: re-executes the ordinal-ladder averaging test (three strictly-increasing, hence individually admissible, encodings of Candidate<Supported<Accepted) and confirms the mean(sigma)>=2 decision rule flips (False/True/True) across the three encodings while min/max/median remain invariant, generalizing to condemn any average/percentage/weighted-threshold rule over sigma as meaningless in Roberts' sense; notes the pre-existing ladder_dc_reference.py independently found the same defect class applied to conjunction rather than averaging ('a 95%-admissible decision is inadmissible')." (anchor: "Any KnowledgeOS rule of the form 'the portfolio is ready if average status >= X', 'confidence-weighted support exceeds a threshold', or '95% of gates passed' is MEANINGLESS in Roberts' precise sense. ... The pre-existing ladder_dc_reference.py found the same class of defect from the Decision-Contract side ... which is the same rule applied to conjunction rather than to averaging.")
- [S1705] types=[VALIDATION, RESTATEMENT] scope=THEORY-LEVEL — "Finding MT-6 (positive): credits three sound corpus achievements to be preserved -- the scale-type discipline (Step 264 §§264.3-264.10, 264.20-264.23), correctly derived from Roberts; distance being kept regime-relative (TV/Wasserstein/JS/KL not interchangeable, kernel/20260825-181719 §9), never violated because distance was never reused afterward; and the refusal to define a single scalar 'knowledge score', maintained from kernel/20260825-181038 §19 through Step 264 across six days despite repeated temptation." (anchor: "Three quantitative moves in the corpus are sound and should be kept: The scale-type discipline itself ... Distance is regime-relative ... The refusal to define a single "knowledge score" -- maintained from kernel/20260825-181038 §19 through Step 264. CORPUS ESTABLISHES, and the corpus deserves credit for holding this line for six days under repeated temptation.")
- [S1714] types=[CORRECTION, RESTATEMENT] scope=THEORY-LEVEL — "Finding FR-4 (F-2, one of the three refutations that matter most): summarizes that averaging over sigma, AggregateSupport, IndependenceFactor, and confidence-as-probability all fail an executed measurement-admissibility check outright and none is salvageable as a measurement, but characterizes this as a naming-discipline problem fixable with a one-line relabelling per formula (as declared engineering heuristics), noting Step 270 was already moving in that direction." (anchor: "Four quantitative claims are refuted outright ... All four fail an executed admissibility check. None is salvageable AS A MEASUREMENT; all four are usable as declared engineering heuristics once relabelled. This is a naming discipline problem with a one-line fix per formula, and Step 270 was already heading there.")

## Notes for P3
Carries 1 candidate group membership (G0418); P3 should decide whether it reflects the same underlying object as the other label(s) in that group, or merely a surface-signal coincidence. Lifecycle (DORMANT) rests on recency heuristics only — no explicit retraction, supersession, or self-contradiction signal was found in this label's own rows.
