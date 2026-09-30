# Independent review of a formal minimization (frozen)

Files:
- `minimize_1an.py`: the script. Its docstring is the frozen specification; r2 fixes a disclosed label defect, see the comment in `bisim_components`.
- `RESULT.json`: r2 output.
- `RESULT-r1-defective-fulllabels.json`: r1 output, kept.
- `OBSERVATIONS.json`: the exact event data per variant.
- `SCHEMA.md`: the coding schema.
- `m3_check.py`: the LTS used for bisimulation.

Use only these files. You cannot run code. Recompute by hand where feasible.

Check each of the following:
1. **Spec fidelity.** Does the code implement the docstring?
   - models M0/M1/M2/M4/MF/M3;
   - variable classes;
   - the global minimum;
   - the data variants REV and REV-TYPE, against their cited corrections;
   - bisimulation and the component test.

   List every divergence. Assess the disclosed r1 → r2 label fix: is r2 now faithful, and does the fix change any family class?
2. **Recomputation.** From OBSERVATIONS.json, recompute by hand, for each operation and each variant:
   - the minimal consistent signatures under M0 and MF;
   - the MODEL-INADEQUATE conflicts.

   Report any mismatch with RESULT.json.
3. **Degenerate discriminators.**
   - Does any variable act as an **object-identity lookup**, one distinct value per object, so that its "requirement" memorizes the data rather than generalizing? Check `t` for ADOPT and `r` for ASSIGN-ID in particular.
   - Does any VACUOUS / REDUNDANT classification rest on a coding artifact?
4. **Scope of claims.** Check every statement a reader could draw from RESULT.json against this rule: **formal redundancy is model-relative and is NOT empirical falsification.** For each variable (a, k, s, t, e, c, o, r, x, h), produce:
   `VARIABLE · WHY IT APPEARED · FORMAL MODEL · REMOVAL TEST · COUNTEREXAMPLE · RESULT · SCOPE · EMPIRICAL STATUS`,
   where empirical status is as imported in the script.
5. **Model equivalence.** Where several minimal signatures exist (e.g. ADOPT {c} vs {k} vs {a,t}), say whether the models are observationally equivalent on the data, and which future observation would separate them.
6. **Bisimulation.** Audit the logic. Check that the reported counterexample pairs really differ only in the stated family. Recompute at least one counterexample's distinguishing behaviour by reading the transition rules in m3_check.py.

Classify each disagreement as: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive.

Write **REVIEW.md** (summary · spec fidelity · recomputation table · degenerate discriminators · minimality report per variable · model equivalences · bisimulation audit · disagreement register) and **REVIEW.json** ({spec_fidelity_ok, r2_fix_faithful, recomputation_matches, mismatches, degenerate_variables, minimality_report: [...], model_equivalences: [...], bisim_audit_ok, disagreements: [...]}).
