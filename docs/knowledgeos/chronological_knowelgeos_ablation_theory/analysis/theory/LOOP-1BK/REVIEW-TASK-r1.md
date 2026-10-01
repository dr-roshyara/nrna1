# Targeted re-review of the R4 formal theory r1 (frozen)

Use only the files here; CHANGELOG-r1.md maps every review item.

Check:
1. **Blocking S-1 and S-2:** are they resolved? Re-verify the sufficiency section in full, including Q*.
2. **D1 (Prop 4.1):** re-check the proof step by step, especially the P-1 intermediate history and the generic (O′, H′) statement.
3. **Countermodels:** does each one now violate exactly one assumption, and are the assumptions independent?
4. **Performative ambiguity (PA-1, PA-2):** is the definition well-formed, and is the conjecture falsifiable with pre-fixed falsifiers?
5. **Coverage:** is every REVIEW-r0 item addressed, with nothing strengthened?
6. **Regression:** any new invalid step, level error, base violation or neutrality issue?
7. **R5 spec:** is it now precise and complete? Check the expected outcomes by hand where feasible.

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** · **FAIL**, with every blocking issue. An invalid proof, a false labelled statement, a hypothesis used as an axiom, or a coding-dependent claim is blocking.

Write **REVIEW.md** and **REVIEW.json** ({verdict, coverage: {id: ok|missing|wrong}, proof_check: [...], countermodel_check: [...], sufficiency_check: [...], regressions: [...], r5_spec_ok, blocking: [...], nonblocking: [...]}).
