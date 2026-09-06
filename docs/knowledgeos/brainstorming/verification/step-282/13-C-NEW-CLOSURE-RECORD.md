---
artifact: 13 · C-NEW-CLOSURE-RECORD
step: 282 (P0 remediation)
date: 2026-08-31
classification: C (Computational) — implementation defect in the verification harness
---

# C-NEW — Closure Record

## Defect
`kosmodel.py` computed assertion identity as
`H(P, {x.ref for x in e}, c, t, Π)` — hashing only the evidence **reference**, omitting `polarity` and
`state`. Two assertions with the same proposition and same evidence references but **opposite polarity**
therefore received the **same id** while being semantically opposite.

```
supports     ref=33989a8e  ->  id=c7b41c155d
contradicts  ref=33989a8e  ->  id=c7b41c155d      COLLISION
StructuralValid -> (False,'id collision')
```

**Reachable in practice:** `Qualify(obs, Policy)` assigns polarity *from policy*, so one observation under
two policies yields opposite polarity, identical ref, identical id.

## Fix applied
```python
",".join(sorted(f"{x.ref}:{x.polarity}:{x.state}" for x in self.e))
```
Applied to **all three harness copies** — `step-280/exec/`, `step-281/exec/`, `step-282/exec/`.
This restores the canonical definition, in which `id = H(P, e, c, t, Π)` hashes the **Evidence tuple**
and `polarity`/`state` are fields of `Evidence`.

## Regression re-run — full certification suite, post-fix

| Suite | Result | Change |
|---|---|---|
| Step 280 E1–E24 | **22 PASS · 1 FAIL (E4) · 1 BLOCKED (E20)** | **unchanged** |
| Step 280 F1–F13 (+F4b) | **14/14 PASS**, 13 at Level ≥4 | **unchanged** |
| Step 281 distinguishability | **M1–M7 all distinguishable = True** | **unchanged** |
| Step 281 E4-R1..R7 | **7/7 PASS**; not-asked ≠ absent | **unchanged** |
| Step 281 affected F-tests | **5/5 PASS** | **unchanged** |
| Step 281 minimality | **M1 ∧ M2 ∧ M3** | **unchanged** |
| Step 281 invariants | **ALL PRESERVED (8/8)** | **unchanged** |
| Step 282 F15 | instance constructed; derivable | **unchanged** |
| Step 282 F17/F18 | **10/10 PASS** | **unchanged** |
| Step 282 F16 / F19 | separation holds; **0 cycles / 26 nodes** | **unchanged** |

> **No prior conclusion depended on the defect.** E4's FAIL in Step 280 is the pre-repair missingness
> state and is expected; it is repaired in Step 281 and re-verified there.

## Status
**C-NEW: CLOSED.** Harness corrected, suite re-run, no regression, no conclusion revised.
The harness may now be used as computational evidence for onward certification.

**Note on scope:** this closes an implementation defect. It changes **no** closure dimension —
`FC` was never affected, `CC` is restored to its stated level, `EC` and `GC` are untouched.
