# NOTES

Program: `verify.py`. Run it with `python3 -I verify.py OUTDIR`; it writes R5-RESULTS.json and V1B-RESULTS.json.
Language: Python 3.13.2 with numpy 2.5.0.
Runtime: about 81 s wall-clock for the whole run (R5 checks about 57 s, all 24 reading×frame runs about 24 s).
Only the files in the bundle were used.

## Method
- The raw assignment space is the mixed-radix product of the §7.1 coordinates in R-1 order. It has 4,478,976 codes.
- The integer code of a history is therefore its R-1 rank. "Least" means smallest code.
- The frame keeps the codes that satisfy F1, F2, the obj typing, Ref restricted to present items, and con only on present acts.
- **|U| = 665,920** (`n_frame_histories`). Other frame sizes: U5 416,200; U6 100,880; U7 147,648; U9 548,160.
- Every class is a boolean array over the code space, intersected with the frame.
- ≈ is tested by projecting out σ and the f values (and Cap for K4b). Slot kind, d, v, prep and the presence pattern are kept.
- (iii)-L is brute force. For each history in scope, the program tries every σ′ at t(w1), every f′ for a1, and a2 absent or one of the 12 added iss acts. It checks frame membership, membership in H-B^R, and the change-point condition.
- (iii)-L scope sizes are in the JSON. For R0 in U it is 480.

## Result format
- `result` is true iff the claim in §7.2 for that check is confirmed by the computation. All K checks are confirmed (true).
- Where a check has several parts, the parts are reported next to `result`.
- K1, K2, K4 (via K6, K7 and the others) and K9.4, K10.4, K11.2 give a dictionary of named witnesses in `least_witness_or_counterexample` instead of one history.
- Histories use the keys sigma, a1, a2, Cap, w1, Ref, con, r, c.
  - `f` is 0, 1 or "inf".
  - con is "none" or "rho1", or null for an absent slot.
- Extra keys beyond the required structure:
  - `scope_size` and `sizes`;
  - `named` and `images` under each reading/frame entry in V1B-RESULTS.json;
  - `frame_sizes` in R5-RESULTS.json.
- In `named`, each history has `in_frame`, `member`, `iS`, `K4b`, `iiO_c`, `iiiL`, `iiiL_in_scope` and `O`.
  - `iiiL` is null when the history is not in the (iii)-L scope.
  - `iiiL_h'_exists_ignoring_scope` gives the raw existence of h′ for histories with a2 absent and w1 present.
  - H_d2 also has `exists(H-D+, ~sigma,f H_d2)`.
- The `named` block is computed for every named history under every reading and both frames, a superset of what the spec ties to particular readings.
- A history outside the frame (`in_frame` false) has all memberships false.
- The V1B per-reading entries have only (i)-S, (ii)-S, (ii)-O and (iii)-L carrying `least_counterexample`; (i)-O has `holds` only, as in the required structure.
- The "(ii) holds" and "(iii) holds" combinations are not emitted as separate keys; they are the conjunction of the -S/-O and -L entries.

## Ambiguities and the literal reading chosen
1. **K1 / K2 witnesses.** K1 reports the least member of each of the five sets plus the Lem 3.6 check. K2 reports no counterexample to either implication and the least history for each converse (B-chg ∧ ¬D-chg; D-rec ∧ ¬B-rec).
2. **K8.1 "difference for every o".** I report the σ values of the difference O1(H-D∩R_B)∖O1(H-B), globally and per o. It is σ = (q,q) for each of the four o.
3. **K8.2.** I tested the observation value (o, F̂⁰ = {(a1,q,0),(a2,q,1)}) for membership in the O2 images. This is the observation value only; it does not insist on the history having both slots with prep 0, since O2 does not see anything else.
4. **K8.3.** `result` = (some O3 value without (ρ1,w1) lies in O3(H-D)∖O3(H-B)) ∧ (the (i) set-equality for O3 on R_B holds). Both parts are shown.
5. **K8.4, "(iii) fails under O4′".**
   - Literal: h′ must have O4′(h′) = O4′(I7), so a2 stays absent and nothing is added.
   - Then no h′ exists, because no act can enter into force at 1 with value p.
   - I report the O4′ version as fails (false) for all four (r,c).
   - I also report that with base O (added a2 allowed) h′ exists.
   - That shows the failure comes only from the added-act requirement. `result` is true when all of these hold.
   - I7 is evaluated for all four (r,c), since r, c are "arbitrary".
6. **K8.6.** `result` is true iff U6 has no h_v candidate and both (ii)-S and (ii)-O fail. P = V = {p}, so no work violates.
7. **K8.7.** Evaluated at I7 for all four (r,c). I also check the specific CM-A7(b) history (a2 iss v = p, prep 1, d = f = 1, referenced, σ′ = (q,p)). It is in U7, in H-B, has the same O, and w1 conforms.
8. **K9.3 "never takes a value outside P".** Read as: Qe ∈ {p, ⊥} everywhere. On H-D∩R_B ∪ H-B it is p only.
9. **K9.4.** Witnesses are required to lie in the stated differences: σ = (p,q) with F̂ = ∅ in O_Q2(H-B)∖O_Q2(H-D), and σ = (q,q) in O_Q2(H-D∩R_B)∖O_Q2(H-B). Q2 includes d, prep, kind and Cap of each act.
10. **K9.5–K9.7 value encodings.** Q1ʙ and Qm use q for histories outside the class, and ⊥ when w1 is absent. Q* is encoded as 0 = ∅, 1 = {(λ1,p)}, 2 = {(λ1,q)}, 3 = {(λ1,p),(λ2,p)}, 4 = {(λ1,q),(λ2,q)}.
11. **K10.1.** The class H-D uses the scope H-D∩R_B, and the fibres are those of H-D. Pairs are identified by slot. A pair (ρ1, s) needs s present and (ρ1, s) ∈ Ref in both h1 and h2. h1 and h2 may differ in everything else, including slot kind.
12. **K10.2.** Ambiguity under O⁺ is computed by brute force over the four classes. K4 under O⁺ is computed as the key and the O⁺ value both matching.
13. **K10.5.** `result` is true iff some referenced act pair in U is not ambiguous under O8. The example a1 iss, prep 0, d = 0, r = 1 is confirmed.
14. **K10.7.**
    - For c2 histories, I test that no referenced pair is ambiguous in U9 under O.
    - For c1, I run the four K10.1 scopes inside U9, restricted to c1 histories.
    - The classes H-D, H-B and so on are computed in U9.
15. **K11.1.** Closure is tested by membership in the enumerated U, not by re-deriving validity.
16. **K12.1.**
    - "Depends on group G" means two histories of U that are equal outside G, and both in U, have different observation values.
    - σ-only and f-only are separate. f-only changes the f values of present iss slots only.
    - d-only changes only the d of present slots (iss or adp).
    - Ref-only changes only the Ref set. con-only changes only con.
    - (+act) and (+work) are the fresh additions of §7.1 (iss, prep 1, any d, v and f, con none, Ref unchanged; w1 at any t, unreferenced).
    - (+ref) is covered by Ref-only.
    - `result` is true only because a report is produced.
17. **K12.2.**
    - A5 is closure under replacing σ and f (any σ and any f on present iss slots) when F1 holds.
    - A7 is closure under (+act), (+work) and (+ref) when F1 holds.
    - A9 is closure under changing only con, or only d, when F2 holds.
    - The check is for h in the frame with the changed history in the F1/F2-valid universe.
    - `result` is true because a report is produced. The failing properties are in `failed_properties`:
      - U: none;
      - U5: A5;
      - U7: A7 for (+act) and (+work);
      - U9: A9.
18. **Readings (V1B).**
    - R-b: "some w ∈ W conforms" means W non-empty, so with w1 absent it fails. This is the literal reading and equals P0.1's "w1 present and conforms".
    - R-g: B-chg ∧ (σ(0) ≠ σ(1) and w1 present with t = 0), so it requires a change.
    - R-g⁺ adds "some present iss slot has f = 1 and v = σ(1)".
    - R-h: B-rec = D-rec ∧ B-recW ∧ (every present act slot and w1 is in Ref).
    - R-i: B-rec = "some act slot present". It counts an adp slot as an act slot.
    - R-i′: B-rec is true for all histories.
    - R-j: D-chg ∧ σ(0) ≠ σ(1).
    - R-d: D-st⁺, where Adopted(s,t) for some t is tested via t = 1.
    - R-e drops the capability test from D-chg.
    - R-c = R-b plus B-rec := D-rec. R-f = B-rec := D-rec with universal B-conf.
    - R-a and R-k are not run.
19. **H-D⁺ for K4b(H).** The reading's D-chg and D-rec are used with D-st⁺, together with the reading's H-B.
20. **(i)-O.** The four-way set equality uses the O images of the four classes.
21. **(ii)-S / (ii)-O.** The scope is h ∈ R_B^R with (ρ1,w1) ∈ Ref. h_v needs σ constant at q with w1 present, h_v ∈ H-D^R∖H-B^R. h_c is in H-D^R∩H-B^R with w1 present and conforming.
22. **Observations O_notsigmaf and Cap.**
    - O_notsigmaf values are every coordinate except σ and the f values.
    - Its images are huge (hundreds of thousands of values), so I report size and a sha256 of the sorted int64 key codes. I do not list the values.
    - "Cap" is read literally as the observation Cap alone (its value is the Cap set), not (O, Cap).
    - O, O1, O3, O4 and Cap images and diffs are listed in full.
23. **U5 frame** is applied to the classes, the ≈ searches, the (iii)-L h′ and the named histories (membership in U5 is checked).
24. **H_U5i / H_U5i c1.**
    - H_U5i has the same coordinates as H_U5h (c = c2).
    - `H_U5i_c1` is the same history with c = c1. Both are in `named` for every reading.
    - The requested fields are: iiO_c(H_U5i) and iiO_c(H_U5i_c1) under R-i and R-i′ in U5; and iiiL(H_U5h) under R-h in U and in U5.
25. **Named histories.**
    - H_i has a1 with prep 1 and f = ∞, which is unreferenced.
    - H_∅ is called `H_empty`. H_g⁺ is called `H_g+`. H_g′ is `H_g'`.
    - W7 and H_U5h include r = 0.
    - I7 is evaluated with r = 0, c = c1, as C-h says.

## Not computed or not done
- Nothing required was left uncomputed.
- No expected values were available or used. Every result comes from the enumeration.
- The `result: true` for K12.1 and K12.2 only means a report was produced.
