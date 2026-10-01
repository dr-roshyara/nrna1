# Task (frozen): a minimal proof repair of the R4 formal theory (r2; human act F-LOG-0221)

Use ONLY:
- `FORMAL-THEORY-R4.md`: r1;
- `REVIEW-r1.md` / `.json`: FAIL, 1 blocking issue;
- the inputs `R3-CODING-INDEPENDENT.json`, `HYPOTHESES.md`, `H5.md`, `INSTRUMENT-FINDING.md`.

No commands, no web. Change **only** the following:

1. **B-1 / RG-1 (blocking).** The generic proofs of Prop 4.1(i′), Prop 4.1(iii) and Lem 5.2 presuppose F1/F2 in the starting history. Repair them with **one** of the reviewer's two fixes, and state which:
   - (a) define frames as subsets of H (check that every countermodel frame still qualifies); or
   - (b) add the preliminary steps h → h[σ, f = ∞] (via A5, A2) for 4.1(i′) and (iii). For Lem 5.2: drop all con (A9, A3), then set d(a) := r(ρ) (A9, A8), then set con(a) := ρ.

   Re-prove each affected statement in full.
2. **NB-1:** state μ_h(λ) = w1 only when w1 ∈ W_h (Ex 4.5a, Lem 4.8(a)).
3. **NB-2:** tie Def 4.9 to S3 only (Rem 4.9a).
4. **NB-3:** fix the changelog typo.
5. **NB-4 and NB-5:** pre-fix Conj 5.5's F-b decision rule (who reviews, what threshold), and resolve the scope conflict with F-c's S_mode. If F-c may have no power, say so.
6. **NB-6:** define "forced" in K11.2, and separate O3 from O3′ in K12.1.

Change nothing else. Statement ids stay stable.

Write **FORMAL-THEORY-R4.md** (r2) and **CHANGELOG-r2.md**.
