# Review of addendum r7 (independent subagent, read-only; Claude family)

**Verdict: FAIL.** The cause is an undisclosed BLIND-tier leak. No material silent false agreement remains.

**Reviewer disclosure:** the reviewer read sealed values. This review must never be routed to the checker.

## Findings
1. **REVIEW-r6's fixes: implemented.** The reviewer re-ran its own variants of X1, X6, the crash paths and a tier spot-check. All are loud.
2. **Audit for silent false agreement.**
   - Safe: every key path, every grammar, the markers, the counterexample gating, the scope rule, UNTESTABLE and the licence.
   - Unsafe, but minor:
     - the semantic alias `existshd+hd2`;
     - con `null` → none, an undisclosed default;
     - the string adp grammar does not check that obj is the other slot.
3. **Tiers.**
   - MONO_READINGS is correct, with no over-exposure.
   - About 200 derivable units were still BLIND:
     - R-b ≡ R0 (23);
     - the R-h/U units (18);
     - R-c/R-f U (ii)-S and (ii)-O (4);
     - U → U5 for c1 named histories (153);
     - SUPP iiO_c(H_U5i[c:=c1]) (2).
4. **Prose.**
   - "never a default" was false;
   - the K8.1 collision scope was misstated;
   - the §2 inclusion chain was garbled;
   - the BLIND list was inaccurate.

## Reconciliation (main analyst)
Every finding is accepted and implemented. The comparator is now `df3402f9…`:
- the tiers are now **closure rules**, not a list: the R-b alias, R-h/U, R-c/R-f (ii), U → U5 for named fields, and SUPP c1;
- the semantic alias is removed;
- con `null` is rejected;
- the string adp obj is checked.

The selftest is `94162116…` and passes, with Y1–Y4 added. P0 BLIND agreement falls from 1,413 to 1,211, and EXPOSED is 779.

The prose now states the residual uncertainty plainly: BLIND = "not shown derivable", not "proven underivable".
