# Review of addendum r6 (independent subagent, read-only; Claude family)

**Verdict: FAIL.**

**Reviewer disclosure:** the reviewer read sealed values. This review must never be routed to the checker.

## Findings
1. **REVIEW-r5's changes: implemented.** The reviewer re-ran B1–B6 and both iiiL scope cases with its own variants.
2. **Silent false agreements:**
   - **X1 (blocking):** K8.1 accepted a dict of booleans and ignored the values. With `{o: {"(q,q)": false}}`, the result was AGREEMENT and the licence was granted.
   - **X6 (minor):** a checker string "COARSE" matched the internal marker and became NOT_A_UNIT.
   - **Crash paths (loud):** K8.1 `{o: null}`; unhashable con, Ref or obj values.
   - **Disclosed limitation:** 52 membership-decided iiiL units where Claude's `iiiL_exists_ignoring_scope` is true.
3. **Prose:**
   - the §3 A7/A9 statements contradict each other;
   - the K12/K8.1 collision wording ("recorded") is inaccurate;
   - "strict grammars" overclaims;
   - "mostly" should be "all";
   - the (i)-O counterexample exclusion is undisclosed;
   - the docstring is stale.
4. **Tiers:** about 81 BLIND units are derivable by monotonicity. Under R-c, R-e, R-f, R-i and R-i′: (i)-O in U; iS at H_g⁺, W7 and I7 (and under R-d); iiO_c at every named history in U; and R-e/U (i)-S, (ii)-S and (ii)-O.

## Reconciliation (main analyst)
Every finding is accepted and implemented. The comparator is now `991ce287…`:
- strict K8.1 per-o values;
- an `object()` marker;
- checker-side TypeError, ValueError and AttributeError → MALFORMED;
- the monotonicity tier rule (`MONO_READINGS`).

The selftest is `89754a21…` and passes, with X1, X1b, X2, X6 and X7 added. P0: BLIND 1,413 AGREEMENT; EXPOSED 577. All the prose items are fixed in r7.
