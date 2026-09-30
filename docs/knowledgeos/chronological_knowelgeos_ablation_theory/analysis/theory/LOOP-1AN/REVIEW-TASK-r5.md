# Independent review of a single-guard diagnostic (frozen)

Files:
- `diag_1an_r5.py`: the docstring is the frozen specification, including its interpretation rule;
- `RESULT-r5.json`;
- `START-EVENTS.json`: the 11 START legality events it used;
- `START-CODING-LINES.txt`: the source-coding lines for START events in the SEMOBS instruments, with each event's quoted basis;
- `PRIOR-REVIEW-r4.md`: the review that proposed this diagnostic;
- `SCHEMA.md`.

Use only these files; you cannot run code.

Check each of the following:
1. **Spec fidelity.** Does the code implement the docstring (fixed guard, LOCO folds, tables, abstention reasons, baseline, verdict rule)? Does it implement what PRIOR-REVIEW-r4 proposed?
2. **Recomputation by hand** of every fold for G = {s} and G = {s, t}: predictions, correctness, abstentions, baseline. Report mismatches.
3. **CIRCULARITY (the most important check).** Is the state value `s` of any START event coded *from the outcome itself*? For example: a state named "authorized" because the start was performed, or "not-authorized" because it was refused. Or is it established independently of the outcome, by a source statement about the authorization before the act? Use `START-CODING-LINES.txt` and `SCHEMA.md` to decide this event by event: INDEPENDENT / OUTCOME-DERIVED / UNCLEAR, with the quote. Then recompute the diagnostic's verdict excluding the OUTCOME-DERIVED and UNCLEAR events.
4. **Independence.** How many of the 8 correct predictions come from distinct clusters? How many distinct *source decisions* stand behind the training rows that produced them?
5. **Baseline adequacy.** Is majority-class the right baseline here? Would a trivial rule, e.g. "s contains 'authorized' ⇒ performed", make the result tautological?
6. **Scope of the claim.** State the strongest claim the result supports and the claims it does not support. Formal, strict-data-only; not empirical replication; not independent confirmation.

Classify each disagreement as: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive.

Write **REVIEW.md** and **REVIEW.json** ({spec_fidelity_ok, faithful_to_prior_proposal, recomputation_matches, mismatches, circularity: [{event, class, quote}], verdict_excluding_circular, independent_clusters_behind_correct, baseline_assessment, strongest_supported_claim, unsupported_claims, disagreements: [...]}).
