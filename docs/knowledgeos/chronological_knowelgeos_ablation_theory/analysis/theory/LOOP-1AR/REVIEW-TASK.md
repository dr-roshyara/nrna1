# Independent review (frozen)

Researcher A classified 36 legality events (TASK.md) as ACT-PERFORMED / ACT-REFUSED / PERMISSION-STATEMENT / GENERIC-PRACTICE / UNCLEAR, and marked the recorded strict witness pairs (WITNESSES.md) as SURVIVES / FAILS. The outputs are ANSWER.json and ANSWER.md. Use only the files in this directory.

Check each of the following:
1. Only permitted evidence was used; the quotes are verbatim and from the stated anchors.
2. The class of every event. In particular:
   - (a) a ruling that performs a norm-making act (adopt, authorize, register, supersede, annotate) *is* an ACT-PERFORMED of that operation;
   - (b) ACT-REFUSED requires an identified proposal or attempt;
   - (c) prohibitions and "does not begin" statements are PERMISSION-STATEMENTs unless an attempt is recorded;
   - (d) UNCLEAR where no source is provided.

   List every event where you disagree, with the class you would assign.
3. Whether each witness pair's SURVIVES / FAILS follows from the classes; recompute it under your corrected classes.
4. Systematic patterns: which operations are mostly attested acts, and which mostly permission statements? Is the pattern an artifact of how operations were defined (e.g. START defined over permissions)?
5. The consequence for each variable's empirical status: which dimensions retain strict support on **attested acts**?

Classify each disagreement as: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive. Do not average.

Write **REVIEW.md** and **REVIEW.json** ({classes_reviewer: {...}, disagreements: [...], witnesses_reviewer: [...], surviving_support_reviewer: {...}, pattern_by_operation, artifact_assessment}).
