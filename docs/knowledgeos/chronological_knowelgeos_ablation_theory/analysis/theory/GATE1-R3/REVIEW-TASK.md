# Independent review: effect of coding-manual r3 on inter-coder reliability (frozen)

**Setting.** Two fresh coders (C1, C2) coded both bundles under manual r3, which is the old manual plus section G. Two earlier coders (B1, B2) coded the same rows under the old manual. All four coders are the same model family.

**Files, per bundle (GATE1/, RESERVE/):** `MANUAL-r3.md`, `ROWS.md`, the four codings, the C notes, and `AGREEMENT-B1-vs-B2.json` / `AGREEMENT-C1-vs-C2.json`. Also `ADOPTION-AND-PREREG.md` (the frozen pre-registration, including the derived prediction) and `VERIFY.json` (the r3 verification). Use only these; you cannot run code.

**Observed (Gate 1):** V1 agreement 0.393 → 0.857 and V4 0.750 → 0.929 (improved); V2 0.964 → 0.821 and V3 0.964 → 0.857 (dropped; V3 κ 0.891 → 0.273). RESERVE: small changes; its V1 was already 0.923.

Check each of the following:
1. **Recompute** from the codings the Gate 1 V1, V2, V3 and V4 agreement for B1–B2 and C1–C2, and list the disagreeing rows.
2. **Diagnose the V2 and V3 drops row by row.** For each new C1–C2 disagreement, which rule caused it: G6 (AMBIGUOUS threshold), G7 (V2 applicability), G2 (V1 precedence), or something else? Which coder is right under MANUAL-r3? Quote the rule and the row.
3. **Diagnose the V1 and V4 gains.** Did G1 and G5 produce *true* agreement (both coders apply the rule correctly), or convergent error (both wrong the same way)? Spot-check at least 6 rows.
4. **Correlated priors.** Ops Jaccard is 1.0 between C1 and C2 again. Is inter-coder agreement within one model family evidence of instrument reliability, or partly shared priors? What can this design establish?
5. **Pre-registration outcome.** Under ADOPTION-AND-PREREG.md, which outcome row applies? Is the derived prediction (a larger gain on RESERVE) falsified?
6. **The smallest r4 fix** (RECOMMENDATION ONLY) for any rule shown to cause the regressions, and whether each fix is a clarification or a substantive rule change.

Classify each disagreement as: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive.

Write **REVIEW.md** and **REVIEW.json** ({recomputation_matches, regression_causes: [{row, judgement, rule, who_is_right, quote}], gain_validity: [{row, judgement, true_or_convergent}], correlated_prior_assessment, prereg_outcome, derived_prediction_falsified, r4_recommendations: [...], disagreements: [...]}).
