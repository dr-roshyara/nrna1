# Review of a specification repair and two search protocols (frozen)

A researcher repaired a specification against defects D1–D14 (REVIEW-1bb.md) and froze two search protocols (TASK.md, HUMAN-DECISIONS.md); the output is SPEC-r1.md / SPEC-r1.json / CHANGELOG.md. Use only these files.

Check each of the following:
1. **Each defect D1–D14:** is the correction real and sufficient, or cosmetic? Is any defect left unaddressed? Is any **new** defect introduced?
2. **D1 especially:** does the corrected spec avoid presenting any observation explainable by both hypotheses as a discriminator? Is the required independent norm-state observable specified per prediction?
3. **Six distinctions:** are they kept separate throughout (norm exists / in force / known in force / act according to / act contrary to / change without act)?
4. **Does the corrected spec now smuggle in H-B** (overcorrection), or does it still smuggle in H-D?
5. **Rank-0 protocol:** can it be executed deterministically and reproduced? Is the target matching over- or under-inclusive? Is "execution trace" vs "mention" operational? Is M-TIME respected (no timestamp used for decision or effectiveness)? Is absence handled as NOT-EVIDENCED-IN-CAPTURE?
6. **Scoping protocol:** existence-only, bounded, with explicit independence and completeness criteria?

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** · **FAIL**, with the exact defects. **A protocol with a FAIL must not be executed.**

Classify each issue as: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive.

Write **REVIEW.md** and **REVIEW.json** ({verdict, defects_status: {D1..D14: FIXED | PARTIAL | NOT_FIXED}, new_defects: [...], d1_assessment, smuggling: {HD, HB}, rank0_protocol: {executable, issues: [...]}, scoping_protocol: {bounded, issues: [...]}, disagreements: [...]}).
