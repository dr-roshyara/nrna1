# Review of addendum r4 (independent subagent, read-only; Claude family)

**Verdict: FAIL.**

**Reviewer disclosure:** the reviewer read sealed values. This review must never be routed to the checker.

## Findings
1. **REVIEW-r3's changes: implemented.** T1–T7 and the loud defects were re-run independently.
2. **Blocking: silent false agreement through key collisions.** A wrong value sent first, under an alias of the right key, was overwritten. Result: 0 disagreements and the licence granted. Cases:
   - A1: `R-i'` vs `R-i′`;
   - A2: `H_g+` vs `H_g⁺`;
   - A3: `(i)S` vs `(i)-S`;
   - A4: `K 5` vs `K5`;
   - A5: a duplicate JSON key.

   `norm()` also silently dropped unknown characters (U+02BC, `²`, `*`). Prefix matching in `--untestable` was too broad, and UNTESTABLE disagreements bypassed the gate.
3. **Fail-closed in practice.**
   - Spec-literal dict histories: OK.
   - Coarse K12.2 labels (`A7`/`A9`): a literal checker could never reach the licence. Blocking, or at least it must be disclosed.
   - iiiL `false` out of scope: not pre-declared.
   - Ref keyed by record, `referenced` in a slot, and `C-U5` as a history: loud, and the adapter carries weight.
4. **Tiers.** `iiO_c` depends on H only through O(H), so `iiO_c` at every O = (0, c1) history under R0/U is exposed. Units are not independent; this must be disclosed.
5. **Prose.** §3 "every key" overclaims; §7 "never silent" was false; §4's NOT_A_UNIT definition was wrong; §8 omitted non-designated tuples and the UNTESTABLE gate bypass.
6. **Selftest: PASS, non-vacuous.** It had gaps: collisions, prefixes, coarse labels.

## Reconciliation (main analyst)
Every finding is accepted and implemented. The comparator is now `a5d900f9…`:
- duplicate JSON keys make the file MALFORMED;
- canonical-id collisions are recorded, and the id becomes MALFORMED;
- `norm()` is closed (it raises on unknown characters);
- exact R5 check keys, and whitelists of ignored keys at every level;
- prefix matching only at a `/` boundary;
- UNTESTABLE disagreements are routed to the gate;
- K12.2 coarse labels: coarse units are scored, and fine splits are listed as NOT_A_UNIT;
- the iiiL out-of-scope rule is pre-declared, conditional on the scope inputs agreeing;
- `iiO_c` R0/U is EXPOSED.

The selftest is `2feac34e…` and passes 29 controls (P0, N1–N6, T1–T11, A1–A11). The prose is corrected in r5.
