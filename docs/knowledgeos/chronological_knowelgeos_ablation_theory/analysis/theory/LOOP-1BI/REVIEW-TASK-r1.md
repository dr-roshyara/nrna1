# Targeted re-review of SCHEMA-r3 r1 (frozen)

Use only the files here; CHANGELOG-r1.md maps the repair.

Check **only**:
1. **B1:** are positive and negative self-performed dispositions now scoped symmetrically? Test R01, R26, R06/R07 and R15–R21, and any "NOT promoted" case in SOURCES.md.
2. **B2:** R01. Give its single answer, or UNKNOWN.
3. **Minimality:** is every listed non-minimal change reverted, or moved to THEORY-CHOICES?
4. **Conflicts:** is each of the 8 resolved?
5. **Re-test all G1–G6 example records** (as in REVIEW-r0 `gap_tests`). List anything still ambiguous.
6. **Neutrality:** does any rule now build H-D or H-B into the observations? Is every theory choice listed and computable from tags?
7. **Regression:** any new conflict?

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** · **FAIL**, with every blocking issue (a neutrality leak is blocking).

Write **REVIEW.md** and **REVIEW.json** ({verdict, fixed: {B1, B2}, gap_tests: [...], still_ambiguous: [...], minimality_ok, conflicts_resolved: {...}, neutrality_issues: [...], theory_choices_complete, blocking: [...], nonblocking: [...]}).
