# Task (frozen): a targeted repair of the R4 formal-theory draft (r1)

Use ONLY:
- `FORMAL-THEORY-R4.md`: r0;
- `REVIEW-r0.md` / `.json`: verdict FAIL;
- the r0 inputs: `R3-CODING-INDEPENDENT.json`, `HYPOTHESES.md`, `H5.md`, `INSTRUMENT-FINDING.md`.

No commands, no web.

**Apply every item in REVIEW-r0.json**, in this order:
1. **Blocking issues first:**
   - **S-1:** fix or withdraw Lemma 4.8 and correct its ledger statement. State correctly what suffices: S1 on H-B, and where Q_e fails (S2, not S3).
   - **S-2:** withdraw the false remark "never excludes H-D". Include the reviewer's counterexample Q* as a labelled example.
2. **Proof issues:**
   - **P-1:** construct the intermediate history explicitly, so that joint non-dependence on σ and f is proved.
   - **P-2, P-3:** correct them.
   - **P-5:** state Prop 4.1 for a generic (O′, H′), with A1–A8 as its hypotheses. Then derive the base case.
   - **P-4, P-6:** add the side conditions, and add a cross-history identity definition.
   - **P-7:** correct the label.
3. **Performative ambiguity:**
   - **PA-1:** the relevant condition is "no c–con tie". Restate Lem 5.2 and Conj 5.5 accordingly.
   - **PA-2:** name the reliability rule in F-a (κ ≥ 0.67 per pair × field). Fix F-c's record selection *before* any coding.
4. **Countermodels C-1 to C-5:** fix them. Each countermodel must violate exactly one assumption. Where that is impossible because one assumption contains another (A4 ⊇ A2, A8), restructure the assumptions so they are independent.
5. **Base, neutrality and levels:**
   - BV-1 to BV-6 and N-1 to N-3: disclose the B-conf universal reading as a modelling choice, and the B-chg any-target vacuity;
   - L-1 to L-7: relabel; every statement gets a label.
6. **Minimality, M-1 to M-7:** delete §2 if it is unused, and remove the redundancies.
7. **R5 spec, R-1 to R-7:**
   - fix the enumeration order;
   - add the missing checks: veridicality on H-B, Q*, and Prop 4.6 directly;
   - state what cannot be tested in U.

**Rules:**
- **Weaken or withdraw, never strengthen.**
- No new primitives unless an item requires one.
- Keep every statement id stable. Withdrawn statements are marked WITHDRAWN with a reason.
- No coding-dependent claim.

Write **FORMAL-THEORY-R4.md** (r1) and **CHANGELOG-r1.md**, mapping every review item id to the change made.
