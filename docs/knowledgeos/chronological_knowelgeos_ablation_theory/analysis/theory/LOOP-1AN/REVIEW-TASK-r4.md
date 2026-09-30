# Independent review of a generalization instrument (frozen)

Files:
- `minimize_1an_r4.py`: its docstring is the frozen specification;
- `minimize_1an_r3.py`: imported by r4 (separating semantics);
- `RESULT-r4.json`;
- `OBSERVATIONS.json`: the exact data for each of the six variants;
- `PRIOR-REVIEW-r3.md`: the review whose generalization criterion r4 implements.

Use only these files. You cannot run code; recompute by hand.

Check each of the following:
1. **Spec fidelity.** Does r4 implement its docstring exactly?
   - recurrence;
   - INSUFFICIENT;
   - LOCO (training, tables, prediction, abstention, voting across several minimum guards, stability);
   - disjoint support;
   - the operation test with shared-APPL comparability;
   - the R-44 event against its stated frozen coding.

   Does it faithfully implement the criterion proposed in PRIOR-REVIEW-r3? List every divergence.
2. **Recomputation by hand**, for variants BASE and REV+R44 at least:
   - (a) START recurrence classes for s and t;
   - (b) LOCO for RAISE, ADOPT, SUPERSEDE and START: per fold, the train minimum guards, the predictions and their correctness;
   - (c) disjoint support for a, k, s;
   - (d) the operation-test counts, spot-checking at least 5 comparable pairs.

   Report mismatches.
3. **Validity of the measures.**
   - (a) Is LOCO with minimum guards refitted per fold an appropriate generalization test for such small data? What does a 4/8 RAISE score mean against a chance baseline? Describe it only; do not do inferential statistics.
   - (b) Coverage and abstention: does START's low coverage (2/11) weaken the "s generalizes" reading, given that the fitted guard is {s,t} and t is a lookup? Would a guard-restricted LOCO (predict with {s} only, where it is consistent in training) be more informative? Assess; do not compute a new instrument.
   - (c) The operation test: does "comparable on shared APPL, 0 decisive" support MODEL-COND-REDUNDANT for operation, or is comparability trivially broken because shared variables almost always differ? Characterize what the 0 decisive pairs actually show.
   - (d) Does adding R-44 change anything other than SUPERSEDE?
4. **Scope of claims.** Formal results are model-relative. Generalization is measured on the strict observations only, never as empirical proof. Flag any overreach in how RESULT-r4 could be read.
5. **Summary table** per operation: `guard · recurrence · LOCO (correct / wrong / abstain) · stability · disjoint support · reviewer verdict` (GENERALIZES / DOES NOT GENERALIZE / INSUFFICIENT / UNCLEAR).

Classify each disagreement as: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive.

Write **REVIEW.md** and **REVIEW.json** ({spec_fidelity_ok, criterion_faithful, recomputation_matches, mismatches, loco_validity, start_coverage_assessment, operation_test_assessment, r44_effects, overreach_flags, summary: [...], disagreements: [...]}).
