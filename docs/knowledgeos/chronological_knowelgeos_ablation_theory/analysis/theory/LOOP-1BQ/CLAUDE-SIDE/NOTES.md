# NOTES — independent enumeration for SLICE-SPEC

## Files
- `slice_enum.py`: the main program. It writes `SLICE-RESULTS.json`.
- `crosscheck.py`: a separate naive check. It enumerates every raw assignment in R-1 order, filters by F1 and F2, and recomputes two values:
  - the frame sizes;
  - (i)-S for R-c and R-d, in both frames: the counterexample counts and the least counterexamples.

  It also asserts that the generated order is strictly increasing in the R-1 rank. Every value it computed matches `slice_enum.py`.

## Language, version, runtime
- Python 3.13.2 (CPython), standard library only.
- `python3 slice_enum.py` takes about 30.5 s wall-clock: about 7 s for U and about 23.5 s for U_P2.
- `crosscheck.py` takes about 1 minute.
- Frame sizes: |U| = 665,920 and |U_P2| = 2,232,720.

## What could not be computed
Nothing. Every field in TASK.md was computed for both frames and all 12 readings.

## Ambiguities and the literal readings chosen
1. **Change points.** With T = {0, 1} and one target, Chg(h) = {(1, τ)} if σ(0) ≠ σ(1), and ∅ otherwise. B-chg's work disjunct "some w has t(w) < 1" means "w1 is present with t(w1) = 0".
2. **Acts entering into force.** Only iss acts enter into force. In D-chg, B-chg, R-g⁺ and (iii)-L, "an act enters into force at 1 with the new value" means: an iss slot with f = 1 and v = σ(1).
3. **D-rec** ("some (ρ, a) ∈ Ref with a ∈ E") counts a referenced act of either kind, iss or adp.
4. **Adopted(a, t)** follows Def 1.10 literally. The only possible adoption act on a is the other slot, when that slot is adp (its obj is fixed to be the other slot). Adopted is only defined for iss acts.
5. **D-st⁺ (R-d).** "Adopted(a, t) for some t ∈ T" is applied to iss slots only. Adopted is monotone in t, so it is evaluated at t = 1.
6. **R-b.** B-conf^b = "some w ∈ W conforms". In U this is "w1 is present and conforms", the same restatement that P0.1 gives for R-c.
7. **R-c.** B-conf := "w1 present and conforms" and B-rec := D-rec, so R_B^c = D-rec.
8. **R-f.** B-rec := D-rec, so R_B^f = D-rec. B-conf stays universal, so it holds vacuously when w1 is absent.
9. **R-e, R-g, R-g⁺, R-h, R-i, R-i′, R-j.** These follow the P0.1 restatements exactly:
   - R-g⁺ accepts an iss slot in either position, a1 or a2.
   - R-i is "some act slot is present", of either kind.
   - R-i′ has R_B = the whole frame and H-B = B-chg ∧ B-conf.
   - R-e drops only the Cap condition of D-chg.
10. **Typing of adp slots.** An adp slot is allowed only if the other slot is a present iss act. So two adp slots never occur together. An adp act may have con = ρ1, subject to F2.
11. **The relation ≈_{σ,f}.** Two histories are related iff they are equal on every enumerated coordinate except σ(0), σ(1) and the f-values of the iss slots. Slot kind, d, v and prep, Cap, w1, Ref, con, r and c are all equal. Witnesses must be frame members, so F1 holds for them.
12. **(ii)-S.**
    - h_v must be in H-D ∖ H-B, with σ(0) = σ(1) and w1 present and violating.
    - h_c must be in H-D ∩ H-B, with w1 present and conforming. w1 is present in both, because ≈ keeps w1.
    - A scope h counts once as a counterexample if either witness is missing.
13. **(ii)-O.** This uses the same scope and witness conditions as (ii)-S, with "same O = (r, c)" in place of ≈_{σ,f}.
    - For "w1 violating" and "w1 conforming", w1 must be present in the witness. It need not be referenced.
    - The check holds iff every O-value in the scope is reached by some h_v and by some h_c.
14. **(i)-O.** This compares the sets of (r, c) values. O(H-D ∩ R_B), O(H-B), O(H-D ∩ H-B) and O(R_B) must all be equal.
15. **(iii)-L scope.** h ∈ H-D ∩ R_B, a2 absent, and w1 present with σ(t(w1)) ∉ P.
16. **(iii)-L witness h′.** h′ is in the frame and in H-B^𝐑, and it satisfies all of the following:
    - σ′ equals σ except possibly at t(w1). There, σ′(t(w1)) ranges over all of V, and leaving it unchanged is allowed. "Only at violation points" was taken in K7's literal form "only at t(w1)": the other time point is kept even if it is also a violation point.
    - w1 conforms, so σ′(t(w1)) ∈ P. In U_P2 both p and q are allowed.
    - f′(a1) is free in {0, 1, ∞}, if a1 is iss.
    - a2 either stays absent or is added as an iss act with prep 1, con none and Ref unchanged (so it is unreferenced). Its d ∈ T, v ∈ V and f ∈ {0, 1, ∞} are all free.
    - Every other coordinate is unchanged: a1's kind, d, v, prep and con, Cap, w1, Ref, r and c. So O is the same automatically.
    - **Last clause.** If σ′(0) ≠ σ′(1), and a1 is not an iss act with f′(a1) = 1 and v(a1) = σ′(1), then a2 must be added with f(a2) = 1. This reads "the change point is f(a2)" literally, with t = f(a2). F1 then forces v(a2) = σ′(1).
    - **This clause in U_P2.** "The new value" is whatever σ′(1) is. No restriction is placed on which value of V it is, beyond F1 and w1's conformance. If no change point is uncovered, the clause puts no constraint on a2: a2 may still be added, with any value allowed by F1.
17. **Order R-1.** Histories are compared by the tuple
    (σ(0), σ(1), slot a1, slot a2, Cap, w1, Ref, con(a1), con(a2), r, c), where:
    - values are ordered p < q < r;
    - a slot ranks absent < iss (ordered by d, v, prep, f with f: 0 < 1 < ∞) < adp (ordered by d);
    - Cap ranks ∅ < {iss} < {adp} < {iss, adp};
    - w1 ranks absent < t = 0 < t = 1;
    - Ref is ordered by 4·a1 + 2·a2 + w1;
    - con ranks none < ρ1.
18. **Naming.** The deontic value "r" (in U_P2) and the recording time r(ρ1) are unrelated. In the JSON, `r` is always the recording time.
19. **JSON lists.** Cap and Ref are written in the fixed order (x,iss), (x,adp) and (rho1,a1), (rho1,a2), (rho1,w1).
20. **Least counterexamples.** These are reported only where a check fails. Otherwise the field is null.
