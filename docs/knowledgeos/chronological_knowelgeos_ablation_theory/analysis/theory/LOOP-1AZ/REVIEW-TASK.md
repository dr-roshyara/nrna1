# Adversarial review of an identifiability analysis (frozen)

A researcher analysed two hypotheses (HYPOTHESES.md) against a governance record (TASK.md; RECORDS, CODING, CODING-REVIEW, SOURCES); the output is ANSWER.json / ANSWER.md. Use only these files.

Attack each of the following:
1. **Sequences:** is the temporal order correct (dates, register order)? Are polarity changes real (the same target, a genuine norm), or artifacts (composite targets; different scopes)?
2. **Intervening acts:** is each claimed act really attested, and really norm-making? Or was it inferred?
3. **Status mediation:** are the episodes truly independent? Are repetitions of one decision counted separately?
4. **Predictions:**
   - Do H-D and H-B really predict differently on each one, or are they observationally equivalent there?
   - Is any prediction scored as supporting H-D merely because the corpus lacks execution records? That is the **corpus-limitation vs domain-property** error. Flag every instance.
5. **Circularity:** does any argument run "few execution records → the corpus is about rules → H-D"?
6. **Minimum evidence:** is each specification sufficient to discriminate, and minimal? Is any falsifier untestable?
7. **What survives:** which predictions are genuinely IDENTIFIED on this corpus, and which hypothesis (if any) do they favour?

Classify each issue as: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive.

Write **REVIEW.md** and **REVIEW.json** ({sequence_issues: [...], act_issues: [...], status_mediation_reviewer: {independent_n, episodes}, predictions_reviewer: [{id, status, favours, reason}], limitation_vs_property_errors: [...], circularity: [...], min_evidence_assessment, verdict: {identified: [...], favours, overall}, disagreements: [...]}).
