# Targeted re-review of Protocol C r1 (frozen)

A researcher repaired PROTOCOL-C.md / scope_c.sh against B1–B3 (blocking) and N1, N3–N7 (REVIEW-r0). CHANGELOG-C-r1.md describes the repair. Use only the files here; read the script line by line.

Check **only**:
1. **B1–B3:** trace these scenarios and give every channel's existence label and its derived fields:
   - (a) the pulls association returns 403;
   - (b) it returns 200 [];
   - (c) it returns 200 with one PR, but the PR GET returns 404;
   - (d) the protection call returns 404 (curl mode);
   - (e) the budget runs out before the events call;
   - (f) transport failure (000) on reviews.

   Is any non-200 mapped to absence, or any derived field false/0 instead of NA? Is rulesets always called?
2. **N1, N3–N7:** fixed?
3. **Regression:**
   - GET only (list every call);
   - the bound (one commit, PR cap 5, one base branch, the window, budget ≤ 40);
   - the identity policy (no logins, names, e-mails or URLs in OUT; raw responses in TMP only; the guard fails closed and is not self-tripped);
   - determinism and local safety; M-TIME labels; neutrality.

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** (no blocking issue) · **FAIL**, with every blocking issue.

Write **REVIEW.md** and **REVIEW.json** ({verdict, scenarios: [{id, channel_labels: {...}, ok}], fixed: {B1, B2, B3, N1, N3, N4, N5, N6, N7}, calls: [...], regressions: [...], blocking: [...], nonblocking: [...]}).
