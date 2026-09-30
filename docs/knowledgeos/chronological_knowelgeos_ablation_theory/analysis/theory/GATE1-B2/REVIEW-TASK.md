# Independent review: three-way coding comparison (frozen)

Two bundles, GATE1/ and RESERVE/. Each contains:
- the coding manual (`MANUAL.md`), records (`ROWS.md`) and form (`FORM.json`);
- three codings of the same records: `CODING-MAIN.json` (the main analyst, sealed; wrapped as {"rows": ...}), `CODING-B1.json` (an earlier blind coder), `CODING-B2.json` (a new blind coder);
- the blind coders' notes;
- the frozen scorer (`*_agreement.py`);
- its three outputs `AGREEMENT-*.json`.

**All three coders are the same model family.** Use only these files; you cannot run code.

Check each of the following:
1. **Scoring fidelity.** Does each AGREEMENT file follow from the scorer and the codings? Spot-check by hand, for each bundle and comparison:
   - at least V1, V3 and V4 agreement;
   - the kappa for one judgement;
   - two ops-Jaccard rows.

   Note any scorer normalization that could hide or create disagreement (e.g. `norm()` truncation, case folding).
2. **Main-coder bias.** Does the pattern "B1 and B2 agree with each other more than either agrees with MAIN" hold per judgement and for ops / coverage? For each judgement where it holds, examine the disagreeing rows. Is MAIN reading more into the record than its text states (e.g. cross-row knowledge, silent defaults), or are the blind coders under-reading? Cite rows and quote the text.
3. **Correlated same-family error.** Where B1 and B2 agree with each other but not with MAIN, could both blind coders share an error that MAIN avoids? Check the rows against MANUAL.md's rules and say who is right under the manual, row by row, for at least 6 rows.
4. **V1 and V4 instability.** Why is V1 (and V4) unstable across all three pairs? Read their definitions in MANUAL.md, and classify the disagreeing rows by cause: manual ambiguity · genuinely ambiguous record · coder error · normalization artifact. Propose the smallest manual clarification, **as a recommendation only**.
5. **Kappa paradoxes.** Flag judgements where agreement is high but kappa is near 0 or undefined (prevalence effects), and state how they should be reported.
6. **Scope.** What does this three-way result establish and not establish? It is same-family: not independent confirmation, not Gate 1 proper.

Classify each disagreement as: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive.

Write **REVIEW.md** and **REVIEW.json** ({scoring_fidelity_ok, normalization_issues, main_bias: {per_judgement: {...}, verdict}, correlated_blind_error: [{row, judgement, who_is_right_under_manual, quote}], v1_v4_causes: {...}, manual_clarification_recommendations: [...], kappa_paradox_flags: [...], establishes, does_not_establish, disagreements: [...]}).
