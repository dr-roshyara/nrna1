# NOTES

Program: `slice_enum.py` (run: `python3 -I slice_enum.py .` → writes `SLICE-RESULTS.json`).
Language: Python 3.13.2 (CPython, pyenv). Runtime: 58.0 s total (U ≈ 13 s, U_P2 ≈ 45 s).
Frame sizes: U = 665,920; U_P2 = 2,232,720 histories (F1/F2-valid, R-1 order).
Everything requested was computed. Nothing was left uncomputed. No expected values were used.

## Ambiguities and the literal reading chosen
1. **Adp slot validity.** An adp slot needs the *other* slot to be a present iss act. Two adp slots are therefore impossible. con=ρ1 on an adp slot is allowed, subject to F2 (ρ1 in Ref and d = r).
2. **R-1 order.** The tuple order is as in R5 §7.1, with p<q<r. iss slots are ordered by (d, v, prep, f) and adp slots come after all iss slots. Cap and Ref use their integer codes.
3. **Adopted(a,t)** is the R5 Def 1.10 text. The actor is always x. For an adp object, the other slot must be an adp act with d ≤ t and (x,adp) ∈ Cap. "Some t ∈ T" in D-st⁺ is tested for t = 0 and t = 1.
4. **Chg(h)** has the single possible change point t = 1. "Some w with t(w) < t" is w1 present with t(w1) = 0.
5. **R-b:** "some w ∈ W conforms" means w1 present and conforming. An empty W gives false. B-rec is unchanged (D-rec ∧ B-recW).
6. **R-g, R-g⁺:** B-chg^g = B-chg ∧ (σ0≠σ1 ∧ w1 present with t=0). So histories with no change are not in H-B under R-g, and R-g⁺ adds "some present iss slot has f=1 and v=σ(1)".
7. **R-h:** "every present act slot and w1 is in Ref" is checked on slots. D-rec ∧ B-recW is kept.
8. **R-i:** E≠∅. R-i′: B-rec is true everywhere, so R_B is the whole frame. R-j: D-chg ∧ σ0≠σ1.
9. **R_B** is the reading's B-rec clause, and H-B uses the reading's B-rec as well.
10. **(i)-S, (ii)-S key.** "≈_{σ,f}" is equality of everything except σ and the f values. The slot kinds, d, v, prep, Cap, w1, Ref, con, r and c are all equal.
11. **(ii)-S.** h_v ∈ H-D∖H-B with σ constant, w1 present and violating. h_c ∈ H-D∩H-B with w1 present and conforming. Both must share h's key. A counterexample is counted when either is missing. The scope is the R_B members with (ρ1,w1) ∈ Ref.
12. **(ii)-O** is the same with only (r,c) equal instead of the key.
13. **(iii)-L literal constraints.**
    - h′ keeps Cap, w1, Ref, con, r and c. The existing slots keep d, v and prep; only their f values are free.
    - σ′ equals σ at every time other than t(w1). σ′(t(w1)) can be any value in P, because w1 must conform. In U_P2 that is p or q, and both were tried.
    - a2 is optional. If added, it is iss, unreferenced, with con none and prep 1. Its d ∈ {0,1}, v ∈ all of V (p, q or r) and f ∈ {0,1,∞} are free, subject to F1.
    - **Last clause.** At the change point t=1, either (a) the existing act a1 has f′=1 and v=σ′(1), or (b) a2 is present with f′(a2)=1. Because of F1, (b) forces v(a2)=σ′(1). More value choices in U_P2 (r, and σ′ = q) are all enumerated. I added no extra restriction that a2's value be the new value or distinct from the old one.
    - h′ must be a valid frame history (F1, F2) and in H-B for the reading.
14. **(iii)-L scope.** h ∈ H-D∩R_B with a2 absent and w1 present and violating. Because a2 is absent, a1 cannot be adp.
15. In the (iii)-L search, the existing slot a1 can be absent, in which case no f change is applied.
