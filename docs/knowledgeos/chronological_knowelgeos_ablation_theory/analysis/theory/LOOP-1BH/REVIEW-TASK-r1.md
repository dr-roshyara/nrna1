# Targeted re-review of the Empirical Theory Record r1 (frozen)

A researcher applied REVIEW-r0.json to the record (TASK-r1.md); CHANGELOG-r1.md maps each item. Use only the files here.

Check **only**:
1. **Coverage:** is every review item id in REVIEW-r0.json addressed? Is any change weaker than required, or wrong?
2. **Strength:** does any changed or new item exceed its source? Do the RETURN-02 items stay within "within-family reproducibility of act.state / norm.deontic / membership on these 36 records"?
3. **Tags:** was any R2_DEPENDENT tag flipped to "no" (forbidden by T-5)?
4. **Integrity:** are ids stable? Are withdrawn items kept with a reason? Is the record still PROVISIONAL?
5. **Regression:** any new provenance or level error? Anything smuggled into section H (H-D, H-B, an aggregate, an axiom)?

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** · **FAIL**, with every blocking issue.

Write **REVIEW.md** and **REVIEW.json** ({verdict, coverage: {item_id: ok|missing|wrong}, strength_errors: [...], tag_flips: [...], integrity_ok, regressions: [...], blocking: [...], nonblocking: [...]}).
