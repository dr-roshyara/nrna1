# Adversarial review of an evidence-acquisition specification (frozen)

A researcher specified minimum new evidence (TASK.md; inputs HYPOTHESES.md, IDENTIFIABILITY.json, CONFORMANCE-CASE.json, STATE.md); the output is SPEC.md / SPEC.json. Use only these files.

Attack each of the following:
1. **Discrimination:** does each spec really separate H-D from H-B? Or would both hypotheses predict the same observation? Check against IDENTIFIABILITY.json.
2. **Sufficiency and minimality:** is any field unnecessary? Is any necessary field missing, e.g. the norm in force at the time of the act, established independently?
3. **Attestation:** does any field still rest on self-report or on recording time?
4. **Episode minimums:** is each reason valid, or arbitrary?
5. **Falsifiers:** testable, with time windows, and not requiring proof of absence? Is the closed-world capability register well defined?
6. **Feasibility claims:** stated as candidates only? Is anything claimed to exist without evidence?
7. **The contested case and status mediation:** would the proposals actually resolve them?
8. **Ranking:** is the "smallest discriminating acquisition" justified?

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** · **FAIL**, with the exact defects.

Classify each issue as: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive.

Write **REVIEW.md** and **REVIEW.json** ({verdict, non_discriminating_specs: [...], missing_fields: [...], self_report_residue: [...], arbitrary_minimums: [...], untestable_falsifiers: [...], feasibility_overclaims: [...], smallest_acquisition_assessment, corrected_ranking: [...], disagreements: [...]}).
