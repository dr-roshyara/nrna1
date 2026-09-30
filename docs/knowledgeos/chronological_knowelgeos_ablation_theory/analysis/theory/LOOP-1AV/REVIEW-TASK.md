# Independent review of a blind coding pilot (frozen)

**Setting.** Two fresh coders (P1, P2), of the same model family, coded 36 events under `TASK.md`, using `SOURCES.md` and an events list (ids, operations, clusters, targets only). The frozen scorer (`score_1av.py`) reports `SCORE.json`:
- `observation_type` agreement **1.000** (κ 1.000);
- `source_act` 0.972;
- dual = YES on 27/36 by both coders;
- convergence with an earlier audit 0.972.

`PREREG.md` states what the pilot measures. `PACKAGE-REVIEW.json` holds a prior review of the schema proposal (the dual-nature problem).

Use only the files here; you cannot run code.

Check each of the following:
1. **Scoring.** Recompute the type agreement, source_act agreement and dual counts by hand from the two codings.
2. **Degenerate agreement.** Are P1 and P2 near-identical beyond their labels: `type_quote` wording, `source_act` anchors, notes? Estimate the textual overlap on at least 10 events. Does perfect agreement show that the distinction is clear, or that two runs of one model on one prompt are near-deterministic? What would discriminate these?
3. **Validity, not just agreement.** For at least 10 events (including every NORM_STATEMENT, every UNKNOWN, and the event where the coders differ from the audit), check the label against `SOURCES.md`. Is each ACT_OBSERVATION really an attested act *of the event's operation on its target*? Are both coders wrong in the same way anywhere?
4. **The one audit difference.** Which event is ACT in the pilot but not in the audit (or the reverse)? Who is right?
5. **Dual nature (F4).** 27/36 are dual. Is that plausible? Or is `dual` coded YES too liberally, e.g. for every ruling row? Check 5 YES and all NO cases. What does the frequency imply for a single-field vs a two-level schema?
6. **UNKNOWN (F5).** Are the 3 UNKNOWN cases the ones with no source text?
7. **What this pilot can and cannot establish** for the human's choice between a two-level r2 (A) and minimal v1 rules (B). The adoption threshold is the human's; do not set it.

Classify each issue as: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive.

Write **REVIEW.md** and **REVIEW.json** ({recomputation_matches, textual_overlap_estimate, degenerate_agreement_assessment, validity_checks: [{event, label, correct, quote}], shared_errors: [...], audit_difference: {...}, dual_assessment, unknown_assessment, implication_for_A_vs_B, disagreements: [...]}).
