# Targeted re-review of Protocol A r1 (frozen)

A researcher repaired PROTOCOL-A.md / capture_a.sh against defects B1–B3 (blocking) and N1–N3 (REVIEW-r0). The repair is described in CHANGELOG-A-r1.md. Use only the files here, and read the script line by line.

Check **only**:
1. **B1–B3:** is classification now root-first? Test these paths and give the class each gets:
   - app/Models/Plan.php; resources/views/auth/register.blade.php; resources/js/Pages/Auth/Register.vue; app/Http/Controllers/Reports/X.php; tests/Feature/Plans/XTest.php;
   - .claude/hooks/x.sh; engineering/tools/x.py; developer_guide/examples/x.php; docs/plans/x.md;
   - README.md; composer.json; package.json; routes/web.php; database/migrations/x.php.

   Is each class correct?
2. **N1–N3:** fixed?
3. **Regression:** is anything else changed? Re-check that it is read-only, deterministic, that target matching is unchanged, M-TIME, absence semantics, hashing, neutrality and no identities.

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** (no blocking issue) · **FAIL**, with every blocking issue.

Write **REVIEW.md** and **REVIEW.json** ({verdict, path_tests: [{path, class, correct}], fixed: {B1, B2, B3, N1, N2, N3}, regressions: [...], blocking: [...], nonblocking: [...]}).
