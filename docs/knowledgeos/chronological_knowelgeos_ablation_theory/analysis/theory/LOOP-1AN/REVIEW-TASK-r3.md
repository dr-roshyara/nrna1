# Independent review of minimization r3 (frozen)

Files:
- `minimize_1an_r3.py`: its docstring is the frozen specification;
- `RESULT-r3.json`;
- `OBSERVATIONS.json`: the exact data per variant;
- `PRIOR-REVIEW-r2.md`: the review that motivated r3;
- `RESULT-1AM-SUPERSEDE.json`: an earlier separate SUPERSEDE analysis;
- `SCHEMA.md`.

Use only these files. You cannot run code; recompute by hand.

Check each of the following:
1. **Spec fidelity.** Does the code implement the r3 docstring?
   - separating semantics D(p,q);
   - hitting sets;
   - the classes;
   - identity-lookup flags;
   - forced cluster pairs;
   - the operation test;
   - the coherent C2.

   Does r3 actually fix each prior-review item it claims to fix (D1, D2, D3, D4/D5, D7)? Which prior items remain open?
2. **Recomputation.** For every operation and variant, recompute by hand from OBSERVATIONS.json:
   - D(p,q) for each opposite-outcome pair;
   - the unseparated pairs;
   - the minimum and inclusion-minimal hitting sets;
   - the FORMAL-REQUIRED variables.

   Report mismatches.
3. **Instrument weaknesses.** In particular:
   - (a) Is the identity-lookup flag informative when an operation has only 2 legality events? Propose a better criterion.
   - (b) Do the "forced cluster pairs" establish *independent* support? A pair such as (R-60, R-60) is within one cluster. Count the **disjoint** cluster pairs per FORMAL-REQUIRED variable.
   - (c) **Data scope.** Is the SUPERSEDE result (signatures {k} or {r}) consistent with RESULT-1AM-SUPERSEDE.json? Does r4 lack an event used there?
4. **Minimality report.** For each variable (a, k, s, t, e, c, h, r, x, o), give:
   `WHY IT APPEARED · FORMAL MODEL · REMOVAL TEST · COUNTEREXAMPLE (the pair it is forced by) · RESULT · SCOPE · EMPIRICAL STATUS (imported) · INDEPENDENT SUPPORT (disjoint clusters)`.

   **Rule:** formal requirement or redundancy is model-relative and never empirical falsification.
5. **Candidate minimal theory.** State the smallest per-operation guard family consistent with all variants, and its variable union. Say which parts are:
   - robust across variants;
   - variant-dependent;
   - dependent on a single cluster.
6. **Model equivalences.** Where several minimum guards exist, identify the future observation that separates them.

Classify each disagreement as: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive.

Write **REVIEW.md** and **REVIEW.json** ({spec_fidelity_ok, prior_items_fixed: {...}, recomputation_matches, mismatches, identity_flag_assessment, disjoint_support: {var: n}, supersede_scope_issue, minimality_report: [...], candidate_theory: {...}, equivalences: [...], disagreements: [...]}).
