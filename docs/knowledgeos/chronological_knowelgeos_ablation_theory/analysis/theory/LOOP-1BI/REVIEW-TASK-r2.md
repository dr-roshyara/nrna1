# Targeted re-review of SCHEMA-r3 r2 (frozen)

Use only the files here; CHANGELOG-r2.md maps the repair.

Check **only**:
1. **N-a, N-d:** are they fixed? Is `state_if_extended` defined for both polarities? Are mechanism refusals / failures handled symmetrically and disclosed?
2. **R18 / R20:** give their single answers.
3. **Items 4–7 of TASK-r2:** fixed?
4. **Re-test all G1–G6 example records.** List anything still ambiguous. Compare that list with KNOWN-AMBIGUITIES.md: is it complete and honest?
5. **Neutrality:**
   - Does any rule now build H-D or H-B into the observations, including by dropping material one hypothesis needs?
   - Is every theory choice listed and computable from tags?
6. **Regression:** any new conflict or non-minimal change?
7. **Codability:** is the schema still applicable by a fresh coder within the bundle? Flag excessive complexity as a risk.

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** · **FAIL**, with every blocking issue (a neutrality leak is blocking).

Write **REVIEW.md** and **REVIEW.json** ({verdict, fixed: {...}, gap_tests: [...], still_ambiguous: [...], known_ambiguities_complete, neutrality_issues: [...], theory_choices_complete, codability_risk, blocking: [...], nonblocking: [...]}).
