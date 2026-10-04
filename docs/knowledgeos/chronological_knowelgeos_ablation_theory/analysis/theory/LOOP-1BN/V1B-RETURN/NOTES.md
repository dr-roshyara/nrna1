# NOTES — V1b independent enumeration

Files: `verify_v1b.py` (Python 3, standard library only), `V1B-RESULTS.json`
(`{reading: {frame: {class_sizes, checks, images, diffs, named}}}` plus `_meta`).

## Runtime
`python3 verify_v1b.py`: **47.5 s** wall clock on this machine (single process). Enumerating U takes about 5 s;
each reading×frame takes 1–3 s. |U| = 665 920 and |U₅| = 416 200.

## Sources and cross-references
- V1B-SPEC cites "R4 §7.1" and "V1A Def V1.3". I take these to mean R5-SPEC §7.1 and V1B-SPEC §A,
  because only these two files are available.
- "Common controls (every reading in §1)": I read "§1" as the list of readings that are run.

## Universe, order, frame
- Coordinates, ranges and order R-1 are as in R5 §7.1. The tuple is encoded as
  (σ0, σ1, a1, a2, Cap, w1, Ref, con(a1), con(a2), r, c). Each component's integer code is
  monotone in R-1's component order, so enumeration order equals R-1 order. Present slot codes run
  iss(d, v, prep, f) in that lexicographic order, then adp(d).
- adp slot: obj = the other slot, which must be a present iss act. Two adp slots cannot occur.
- Ref and con exist only for present items. con ranges over **all** present acts, including adp acts.
  F2 is applied to adp acts too (con(b) = ρ1 ⇒ (ρ1, b) ∈ Ref and d(b) = r).
- F1 applies to iss acts with f ∈ T. The frame is "well-formed ∧ F1 ∧ F2". U₅ adds
  "c = c2 ⇒ σ(0) = σ(1) = q".
- **In U₅, all classes and all witnesses are restricted to U₅.** This covers h*, h_v, h_c, the
  (iii)-L h′, and the K4b and H-D⁺ witnesses.

## Clauses (R0) as implemented in U
- Chg(h) ≠ ∅ iff σ(0) ≠ σ(1). The only possible change point is (1, τ).
- D-chg: no change, or some iss slot has f = 1, v = σ(1), and (x, iss) ∈ Cap.
- D-st: every iss slot with f ∈ T satisfies Adopted(s, f(s)). Adopted is taken literally: either
  prep 0, (x, adp) ∈ Cap and d ≤ t; or the other slot is adp, (x, adp) ∈ Cap and d(b) ≤ t.
- D-rec: Ref contains a pair (ρ1, act). Acts of either kind count, since D-rec says a ∈ E.
- B-chg: no change, or some iss slot has f = 1 and v = σ(1), or w1 is present with t(w1) = 0.
- B-conf (universal): w1 absent, or σ(t(w1)) = p. B-recW: (ρ1, w1) ∈ Ref.
- R_B^𝐑 = {h ∈ frame : B-rec^𝐑}.

## Ambiguities and the literal reading chosen
1. **B-conf^b (R-b, R-c)** "some w ∈ W conforms": this needs W ≠ ∅, so it means "w1 present and
   conforms". P0.1 states exactly this for R-c, and I use it for R-b as well.
2. **R-c** = B-conf^b together with B-rec := D-rec. **R-f** = B-rec := D-rec with universal B-conf.
   For both, R_B = D-rec.
3. **R-d** D-st⁺: "Adopted(a, t) for some t ∈ T". Adopted is monotone in t, so I evaluate it at t = 1.
   The clause applies to iss acts only.
4. **R-g**: I use P0.1's U-form: B-chg ∧ σ(0) ≠ σ(1) ∧ w1 present with t(w1) = 0.
5. **R-g⁺**: R-g's clause ∧ "some present iss slot has f = 1 and v = σ(1)". There is no Cap or
   adoption condition, because none is stated.
6. **R-h**: "every present act slot" includes adp acts.
7. **R-i**: "some act slot is present", of either kind. **R-i′**: R_B = the whole frame, and
   H-B = B-chg ∧ B-conf.
8. **R-j**: D-chg ∧ σ(0) ≠ σ(1). This makes H-D^j contain only changing histories.
9. **(i)-O counterexample.** The check is a set equality, so there is no single h. I report the four
   O-images. As the "least counterexample" I report the least h, in R-1 order, that lies in any of
   the four classes and whose O-value is missing from at least one image.
10. **(ii)-S / (ii)-O.** Scope: h ∈ R_B^𝐑 with (ρ1, w1) ∈ Ref.
    - h_v: H-D^𝐑 ∖ H-B^𝐑, σ constant, w1 present and violating. Together these force σ = (q, q).
    - h_c: H-D^𝐑 ∩ H-B^𝐑 with **w1 present and conforming**, required explicitly. In the O-form this
      adds a real condition, because w1 is not inherited through ≈.
    - The report says which of the two witnesses is missing at the least counterexample.
11. **"(ii) and (iii) hold iff the S-form and the L-form hold."** (ii) has no L-form. I report
    `(ii)_holds` as (ii)-S ∧ (ii)-O, and `(iii)_holds` as (iii)-L. Every individual form is also
    reported, so other combinations can be read off.
12. **(iii)-L.** Scope: h ∈ H-D^𝐑 ∩ R_B^𝐑 ∩ frame, a2 absent, w1 present and violating. A candidate
    h′ must satisfy all of the following:
    - σ′ equals σ except at t(w1). Because w1 must conform, σ′(t(w1)) = p is forced.
    - f of the existing iss act a1 is free in {0, 1, ∞}.
    - a2 is either absent or added as an iss act with prep 1, unreferenced and con none. Its d, v
      and f are free, and its actor is x.
    - Every other coordinate is unchanged, so O is the same.
    - h′ is in the frame, h′ ∈ H-B^𝐑, and w1 conforms.
    - If σ′(0) ≠ σ′(1) and the act of h (a1, under f′) does not have f′ = 1 with v = σ′(1), then
      a2 must be present with f(a2) = 1. F1 then forces v(a2) = σ′(1).

    h′ is **not** separately required to be in R_B^𝐑. I report the scope size, the number of
    failures and the least failing h.
13. **K4b(H)**: Def P0.3 writes H-D⁺ ∩ H-B with no reading superscript. Literal choice: R0's H-D with
    D-st⁺, and R0's H-B, within the frame, matched by ≈_{σ,f,Cap}. As an extra I report
    `K4b_with_reading_classes`, which uses H-D^𝐑 with D-st⁺ and H-B^𝐑.
14. **exists(H-D⁺, ≈_{σ,f} H_d2)**: literal choice is R0's H-D with D-st⁺, within the frame. A
    reading-class variant is also reported. Both appear under the `H_d2` entry of every reading.
15. **iiiL(H)** requires H to be in scope. Out of scope it is `null`. `iiiL_exists_ignoring_scope`
    gives whether the K7 construction succeeds anyway, defined when w1 is present and a2 absent.
    The least h′ is also given.
16. **Named histories.** Every named history is evaluated under **every** reading and both frames.
    This is a superset of what §D requires. `designated_for` and `designated_here` mark where the
    spec asks for each one; I7 and W7 are designated for R-h and R0.
    - Unstated con defaults to none. This applies to H_∅, H_i, H_d, H_d2 and W7's a2.
    - I7 uses r = 0, c = c1, as C-h specifies.
    - All named histories satisfy F1, F2 and the U₅ axiom. `in_frame` is reported.
    - `member(·)` means "in the frame and satisfies the class clauses".
    - iS, K4b and iiO_c do not require H itself to be in any class.
17. **Observations.** O = (r, c); O1 = (O, σ); O3 = (O, Ref); O4 = (O, presence of w1);
    O_notsigmaf = every enumerated coordinate except σ and f.
    - "Cap" in §D.2 is read literally as **Cap alone**. Because the other observations are of the
      form (O, X), I also report `O_Cap` = (O, Cap).
    - C-O1 is covered by the O1 images and diffs.
18. **"Least counterexample"** is in R-1 order over the frame. For (iii)-L it is the least h in scope.
    Each history is printed in readable form together with its R-1 tuple, using these codes:
    - values: p = 0, q = 1;
    - slots: 0 = absent, 1–24 = iss, 25–26 = adp;
    - Cap: 0–3;
    - w1: 0 = absent, 1 = t0, 2 = t1;
    - Ref: bit code;
    - con: 0/1;
    - r;
    - c: 0 = c1, 1 = c2.

## Not computed / not fully reported
- **O_notsigmaf images and diffs are not listed in full** when they have more than 300 values. They
  usually have thousands to tens of thousands. For these the JSON gives the exact `count` and the
  first 20 values in R-1 order (`"truncated": true`). Full sets of all sizes are computed internally.
  Every other image and diff is listed in full.
- R-a and R-k are not run, as V1B-SPEC instructs.
- No reading was adjusted after seeing results. Each clause was coded once from the text above.
