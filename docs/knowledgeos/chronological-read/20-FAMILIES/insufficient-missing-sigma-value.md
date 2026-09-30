# insufficient-missing-sigma-value

**Scope(s):** OBJECT · **Row count:** 5 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "Insufficient", "evidential sufficiency vs policy bar" · **Aliases:** "PF-1 loss", "SG-4"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0041, scope OBJECT: "A sixth Sigma requirement surviving decomposition into five orthogonal axes: distinguishing 'one supporting item' from 'meets the policy bar' requires an evidential-sufficiency-relative-to-a-declared-bar value (Insufficient) distinct from Unknown and Supported; independently converged upon by zero_reference.py's PF-1 finding that the ratified four-arm status summary has no arm for Insufficient, Stale, or Prohibited."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1703 §"Note the collision that survives even with all five axes: 'one supporting item' and 'meets the policy bar' are indistinguishable ... Therefore the evidence axis is not {none, support, refute, both}. It must carry SUFFICIENCY relative to a policy, i.e. an Insufficient value distinct from both Unknown and Supported. ... Two independent methods ... reach the same conclusion: the ratified status set drops Insufficient, and it is necessary."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1727. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no row is self-typed as a contradiction; this is a heuristic based on how recently (by source_id order) this label was last used (S1727), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1703, S1704 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1703, S1704, S1720, S1727, S1727 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1703] types=[EXPERIMENTAL-RESULT, CORRECTION] scope=OBJECT — "Finding SG-4: even after decomposing into five axes, one harmful collision survives ('one supporting item' vs 'meets the policy bar'), showing the evidence axis needs a sufficiency-relative-to-policy value (Insufficient) distinct from Unknown and Supported; this converges independently with zero_reference.py's PF-1 finding (that script run separately, reporting the ratified four-arm summary unknown/conflicting/missing/invalid has no arm for Insufficient, Stale, or Prohibited), though per finding EV-0 both are Claude-session artifacts, so this is convergence between two independent methods within the same review programme, not corpus-vs-reviewer corroboration; the session's own construction is noted to have been built before that script's output was read." (anchor: "Note the collision that survives even with all five axes: 'one supporting item' and 'meets the policy bar' are indistinguishable ... Therefore the evidence axis is not {none, support, refute, both}. It must carry SUFFICIENCY relative to a policy, i.e. an Insufficient value distinct from both Unknown and Supported. ... Two independent methods ... reach the same conclusion: the ratified status set drops Insufficient, and it is necessary.")
- [S1704] types=[EXPERIMENTAL-RESULT, VALIDATION] scope=OBJECT — "Finding EG-5: running reviews/synthesis/analysis/mathematical-tests/exp01_recheck.py (from the Claude review thread) shows the corpus's central negative result ('no simple scalar operator is sufficient as the epistemic foundation') does not follow from its own cited 7-column matrix alone (since a formula called SATURATING passes all seven columns), but does follow once criterion E (no scalar can retain the pair (S+,S-)) plus pre-operator dependency/duplicate-work considerations plus a calibration caution are added; the negative conclusion is thus CONFIRMED as provable but for a different reason than originally given -- converging with 06-SIGMA-GAP-ANALYSIS.md's independent finding that the evidence axis needs at least two dimensions (support, refutation) plus a sufficiency judgement, refuting any single-scalar formula such as AggregateSupport=sum(s)/(1+log n)." (anchor: "The published negative verdict ... does NOT follow from the 7-column matrix alone ... It DOES follow from the matrix PLUS criterion E (no scalar can retain (S+,S-)) PLUS the pre-operator dependency/duplicate work PLUS the calibration caution ... the negative conclusion is CONFIRMED and is in fact provable. ... Evidence standing is at least two-dimensional (support and refutation), plus a sufficiency judgement relative to a bar.")
- [S1720] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "G-07 (CRITICAL): averaging over the ordinal status ladder is meaningless by Roberts' measurement theory — a decision flips across three admissible re-encodings of the same ladder; any average/percentage/weighted-threshold rule over σ is invalid and every such rule in the corpus/implementation must be audited and relabelled." (anchor: "| **G-07** | **Averaging over the ordinal status ladder is meaningless.**")
- [S1727] types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "EXP-4: taking the mean of ordinal status values (Candidate/Supported/Accepted) and thresholding it for a PROCEED/no decision flips the decision across three admissible re-encodings of the same ordinal ladder (encoding A: PROCEED=False; encoding B and C: PROCEED=True), confirming by execution that mean is not an admissible operation on an ordinal scale (Roberts); min, max and median are the ordinal-admissible controls and are shown invariant." (anchor: "=> MEAN over an ordinal status ladder is MEANINGLESS (Roberts). CONFIRMED by execution.")
- [S1727] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "EXP-7: per-assertion confidence values over mutually exclusive propositions can and do sum to more than 1 (0.8+0.7=1.5) because the corpus assigns confidence with no shared declared sample space (Ω,F,P) for Σ or assertion confidence outside the abandoned measure-theory regime; therefore assertion 'confidence' is currently a number, not a measurement, in the probabilistic sense." (anchor: "=> 'confidence in [0,1]' is NOT a probability unless a common (Omega,F,P) is
     declared.")

## Notes for P3
- This label is ungrouped in P2a — no mechanical signal (token overlap, co-occurrence, or explicit cross-reference) connected it to any other label in this batch's normalization pass.
- Rows for this label were captured under more than one scope tag (['OBJECT', 'THEORY-LEVEL']) — this may reflect genuine cross-scope relevance (e.g. an OBJECT used at THEORY-LEVEL) rather than a labeling error, but P3 may want to confirm.
