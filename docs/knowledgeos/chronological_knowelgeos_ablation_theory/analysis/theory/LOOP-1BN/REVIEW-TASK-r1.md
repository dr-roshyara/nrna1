# Targeted re-review of V1a r1 (frozen)

Use only the files here. Check **only**:
1. **Controls:** is each control now independent of least-counterexample reporting? Would each be detected by a correct enumerator and missed by a broken one? Is each detection criterion an exact output field and value?
2. **M1–M3:** are the licences valid? Is each classification proven? Are the new predictions derivable and falsifiable?
3. **The listed rigour fixes:** applied correctly? In particular, is the Lem V3.9(d) proof now valid?
4. **Regression:** any other change? Any R4 revision?

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** · **FAIL** (an invalid proof, or a control that a correct run could fail, is blocking).

Write **REVIEW.md** and **REVIEW.json** ({verdict, control_check: [...], new_readings_check: [...], fixes: {...}, regressions: [...], blocking: [...], nonblocking: [...]}).
