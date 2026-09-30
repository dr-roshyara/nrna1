# 1ab: T-min v1 out-of-sample test on a seeded random sample of register rows

| | |
|---|---|
| Status | research record. Not canonical. Authority: none |
| Pre-registration | `prompts/KNOWLEDGEOS-T-MIN-V1-OUT-OF-SAMPLE-PREREGISTRATION.md`, frozen with `SAMPLE.json` at `d35011d2e` before any read |
| Sample | **15 of the 41 never-coded rows**, chosen by sha256 of seed + ID: R-30, R-32, R-34, R-35, R-40, R-45, R-52, R-56, R-62, R-63, R-64, R-65, R-68, R-75, R-97 (2026-07-08 … 08-04). The programme's **first random (not selected) sample** |
| Files | coding `CODING.json` (`ca41c3e4…`) · tallies `RESULT.json` (`39be6884…`) · intervals `INTERVALS.json` |
| Coder | the same agent that built v1, so not blind. The mitigations were pre-registration, coding source facts first, and flagging ambiguity |
| Log | F-LOG-0135 |

## 1. Predictions: out-of-sample estimates (Wilson 95%)

| Prediction | Supported / applicable | Estimate [95% CI] | Violations | Ambiguous |
|---|---|---|---|---|
| V1 legality | 5/5 | 1.00 [0.57, 1.00] | 0 | R-64 |
| V2 four-act separation | 4/4 | 1.00 [0.51, 1.00] | 0 | R-40 |
| V3 frames (modelled operations) | 5/5 | 1.00 [0.57, 1.00] | 0 | — |
| V4 evidence persistence | 6/6 | 1.00 [0.61, 1.00] | 0 | — |
| V5 reopening needs evidence | 1/1 | 1.00 [0.21, 1.00] | 0 | — |
| **V6 authority named** | **13/15** | **0.87 [0.62, 0.96]** | **R-30, R-32** | — |
| V7 supersession explicit, old record kept | 2/2 | 1.00 [0.34, 1.00] | 0 | — |
| **Coverage (acts v1 can model)** | **7/24** | **0.29 [0.15, 0.49]** | — | — |

Caveats:
- The intervals ignore the finite-population correction (n/N = 0.37, which would narrow them) and the clustering of acts within rows (which makes the coverage interval optimistic).
- The sample comes from **one register**.

## 2. What the test shows
1. **No substantive v1 guard was contradicted out of sample.** There was no violation of legality, the four-act separation, frames, evidence persistence, reopening or supersession wherever they applied.
   - The applicable n is small, so the lower bounds are only about 0.5–0.6.
2. **The first genuine out-of-sample falsification: V6.** R-30 and R-32 (2026-07-08) name no authority.
   - This is a **regime effect**: the authority triple convention appears later (by R-43).
   - "Every ruling names its authority" holds only from that convention onward. Host-level attribution (the R-81 annotation: R-1…R-80 human-issued) is a different, weaker fact.
3. **v1's operation vocabulary covers only 29% of the acts in random rows.** This is the **main out-of-sample result**.
   - The missing operations, by frequency:
     - **ADOPT-DECISION** (first-instance adoption of an option or path, R-35 and R-75). This is a **term overload**: "adopt" also names status-adoption of a PREPARED ruling;
     - **APPROVE** (R-40 ×2, plus APPROVE-PLAN in R-56);
     - **DEFER** (R-35, R-40, R-64);
     - OPEN-WORK, RATIFY, DECLARE-SCOPE, CONFIRM, SEAL and REFINE-CANDIDATE.
   - v1 was learned from a selected set weighted toward the template era. Its vocabulary does not generalize.
4. **The evidence bar reaches beyond standing-changing operations.** R-64 says "requires sustained operational demand, not a single validation" (refusing a methodology extension). With R-72 (authorization from implementation evidence) and R-80 (new terms need *demonstrated* ambiguity), that makes **three occurrences**.
   - v1's guard restricts the bar to RAISE/SUPERSEDE. Under the RULE reading of R-64, **v1 is violated**. The case is recorded as ambiguous because R-64 also states a parsimony principle, which would be a CHOICE ground.
5. **New source facts** (one occurrence each unless noted):
   - opened ≠ delivered (R-52);
   - plan approval ≠ execution authorization (R-56);
   - authorization ≠ release (R-65);
   - "No rule permits a work package to be partially accepted under its own name" (R-68);
   - "the Board recorded a selection and did not state reasons; none are invented here" (R-75): the corpus practises NOT-RECORDED ≠ FALSE;
   - counter-evidence recorded as an annotation while "the DECISION stands as issued" (R-75);
   - **"the validation demonstrated a scope boundary, not a model defect"** (R-63): the corpus draws the same NOT-MODELLED vs VIOLATED distinction this programme uses;
   - "a model should be as simple as the problem it currently solves" (R-64).
6. **Status is act-type-specific (second witness).** R-97, a Chief *confirmation*, carries no PREPARED marker, like R-96…R-99.

## 3. Verdict
- **v1's guards: not contradicted out of sample,** with weak precision because the applicable n is small.
- **v1's evidence-bar scope: probably too narrow** (three occurrences, one ambiguous out-of-sample case).
- **v1's vocabulary: fails to generalize** (29% coverage).
- **Authority-naming (V6): falsified for the early regime.**

## 4. Next (information per exposure)
1. **Vocabulary induction on the remaining 26 uncoded rows.**
   - Freeze the extended operation list derived here (ADOPT-DECISION, APPROVE, DEFER, OPEN-WORK, RATIFY, DECLARE-SCOPE, CONFIRM, SEAL) with declared frames.
   - Predict that the extended vocabulary's coverage on the next seeded sample exceeds a pre-set threshold. This is a genuine held-out test of vocabulary generalization, because these rows were not used to derive the list.
2. **The evidence-bar scope:** revise v1's guard to "operation-specific bars", as a candidate for v1.1, and test it on the same held-out sample.
3. **Gate 1:** an independent re-coding of these 15 rows by a non-Claude coder gives an **inter-coder agreement** estimate (Cohen's κ on the prediction classes). That addresses the not-blind coder.
