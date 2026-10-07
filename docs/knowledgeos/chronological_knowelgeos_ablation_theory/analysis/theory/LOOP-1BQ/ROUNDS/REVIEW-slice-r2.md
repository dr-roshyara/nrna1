# Review of the slice comparator (independent subagent, read-only; Claude family)

**Verdict: FAIL** on tiering. **No silent false agreement.**

**Reviewer disclosure:** the reviewer read the sealed U_P2 least counterexamples, but only after deriving them.

## Findings
1. **Paths checked, all safe:**
   - the monkeypatch takes effect and loosens nothing beyond adding `r`;
   - the size-key bypass accepts only the exact keys;
   - collisions give INCOMPLETE;
   - missing or unknown keys are loud;
   - types are strict;
   - the counterexample gate is sound.
2. **Tiers.**
   - Nine least counterexamples follow by short argument: (i)-S under R-c, R-g, R-g+, R-i and R-i′; (ii)-S under R-g, R-g+, R-i and R-i′.
   - The R-i′ (i)-S scope duplicates n.
   - About 22 structural duplicates were counted as distinct.

   So 99 should be about 67.
3. **Calibration** is faithful. It should be reported as 161 substantive plus 20 null/null.
4. **Selftest** is meaningful but thin. Missing controls: frame aliases, missing frame, R_B+rb, holds disagreement, float int, unknown field, U history with r, calibration.

## Reconciliation (main analyst)
Every change is applied (PREREG-SLICE Amendment A1):
- 67 distinct BLIND quantities;
- calibration reported as 161 + 20;
- N7–N14 added; the selftest passes.
