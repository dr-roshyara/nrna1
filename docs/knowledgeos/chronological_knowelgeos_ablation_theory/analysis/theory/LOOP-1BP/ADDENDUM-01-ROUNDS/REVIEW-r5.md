# Review of addendum r5 (independent subagent, read-only; Claude family)

**Verdict: FAIL.**

**Reviewer disclosure:** the reviewer read sealed values. This review must never be routed to the checker.

## Findings
1. **REVIEW-r4's top-level fixes hold.** The reviewer re-ran A1–A5, A7 and A8, plus the field-alias and unknown-character cases.
2. **Blocking: nested silent false agreements.** In each case the licence was granted:
   - B1: a K12.1 row-alias collision;
   - B2: a K12.2 row-alias collision;
   - B3: a label collision inside a dict-of-boolean row;
   - B5: a K8.1 o-key collision (the union hid an empty claim);
   - B4: a coarse label plus an explicit fine `false` was hidden as NOT_A_UNIT;
   - the iiiL SCOPE_PENDING rule checked membership only. Where Claude's out-of-scope comes from coordinates (a2, w1 conformance), an in-scope `false` agreed silently.
3. **Nonblocking:**
   - B6: unknown keys inside a form dict were not recorded;
   - B7/B8: omissions → NOT_A_UNIT. This is acceptable under PREREG, because it is listed and disclosed;
   - type confusions are handled;
   - `f: null` → ∞ was undisclosed.
4. **Prose:**
   - §3 "form-body keys checked" overclaimed;
   - "1,516" was stale (it is now 1,508);
   - "conditional on the scope inputs agreeing" overclaimed.
5. **Selftest:** meaningful, but missing nested controls.
6. **Tiers:** no new derivable leak. `member(R_B)` under R-i′ is spec-given (R_B = U), so it should be reclassified or disclosed.

## Reconciliation (main analyst)
Every finding is accepted and implemented. The comparator is now `5ea41c05…`:
- `k12_rows` rejects row and label collisions, and returns present and mentioned sets;
- a coarse label applies only if no fine label is mentioned;
- K8.1 o-key collisions → MALFORMED;
- keys inside each form dict are whitelisted;
- the iiiL rule is split into (a) membership-decided, accepted iff both membership units agree, and (b) coordinate-decided, flagged AMBIGUOUS, which blocks the licence;
- R-i′ `member(R_B)` → EXPOSED.

The selftest is `9e7e6fa1…` and passes, with A9b and B1–B6 added. The prose is corrected in r6, which also discloses `f: null` → ∞.
