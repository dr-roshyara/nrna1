# NOTES — R5 independent verification

Files: `verify.py` (standard library only), `RESULTS.json` (produced by `python3 verify.py`).
Frame U: **665 920** histories satisfying F1 ∧ F2 (U₆: 100 880).

## Summary of outcomes
K1–K11.2: every check came out **TRUE**.
K12.1 (dependence over U):

| Observation | depends on |
|---|---|
| O | none |
| O₁ | σ-only |
| O₂ | f-only |
| O₃ | Ref-only |
| O₃′ | con-only |
| O₄ | (+work) additions |
| O₄′ | (+act) additions |
| O₈ | d-only |

K12.2 (failing closure properties): U: none. U₅: A5. U₇: A7 (+act) and A7 (+work). U₉: A9 (con-only).

## Encoding and order
Each history is a tuple `(σ0, σ1, a1, a2, Cap, w1, Ref, con(a1), con(a2), r, c)` of small integers. The encoding is chosen so that tuple order is exactly the §7.1 order. Slot code: 0 means absent. Codes 1–24 are iss, ordered by (d, v, prep, f) with f ordered 0 < 1 < ∞. Codes 25–26 are adp, ordered by d. U is generated in that order, and the program asserts this (`U == sorted(U)`). So every "least witness" and "first counterexample" is the minimum in §7.1 order.

## Ambiguities and the readings chosen
1. **adp slots.** obj is "the other slot, which must be a present iss act". So an adp slot is only enumerated when the other slot is a present iss act. Two adp slots never occur together. An adp act has no f, v or prep.
2. **Adopted(a, t), second disjunct.** The only possible b is the other slot when it is adp, since its obj is then a.
3. **D-st⁺.** "Adopted(a, t) for some t" is evaluated over t ∈ T = {0, 1}. In K4b the target class is H-D⁺ ∩ H-B, where H-D⁺ = D-chg ∧ D-st⁺ ∧ D-rec. H-B is unchanged, because it has no D-st. h* ≈_{σ,f,Cap} h.
4. **≈_{σ,f}.** Equal on every enumerated coordinate except σ(0), σ(1) and the f-values of iss slots. Kind, d, v, prep and presence stay fixed.
5. **K6.** "w1 referenced" is already implied by R_B (B-recW). h_c needs only H-D ∩ H-B, since conformance follows from B-conf.
6. **K7 / Prop 4.1(iii).** Candidates h′ have:
   - σ′ equal to σ except possibly at t(w1);
   - any f′ on a1;
   - a2 either still absent, or added as an iss with prep 1 (any d, v, f), unreferenced, con none;
   - every other coordinate unchanged.

   "Every change point of σ′ at which no act of h enters into force under f′ with the new value is f(a2)" is read as: if σ′(0) ≠ σ′(1), and no slot present in h has f′ = 1 with v = σ′(1), then a2 must be present with f(a2) = 1. Under F1 this already forces v(a2) = σ′(1). h′ must lie in the frame (F1, F2), be in H-B, have the same O, and have w1 conforming.
7. **K8.3.** I report the least h ∈ H-D whose O₃ value contains no (ρ1, w1) and lies outside O₃(H-B). "(i) still holds for O₃ on R_B" is checked as the set equalities O₃(H-D∩R_B) = O₃(H-B) = O₃(H-D∩H-B) = O₃(R_B). The per-h form follows from K4, because ≈_{σ,f} preserves Ref.
8. **K8.4.**
   - "The value 'w1 absent'" is checked for every o.
   - "(iii) fails at I7 under O₄′" means no h′ meets item 6 with O₄′(h′) = O₄′(I7).
   - "The failure comes only from the literal requirement of an added act" is read as three facts: (iii) holds at I7 under O; every O-witness adds a2, which changes O₄′; and without an added act no h′ ∈ H-B with the same O₄′ and conforming w1 exists at all. The reason is B-chg: w1 is at t = 1, and a1 has v = q.
   - I7 is run for all four (r, c), since these are "arbitrary".
9. **K8.7.** "(iii) fails at I7 in U₇" means the literal (iii), with an unreferenced added a2, has no witness inside U₇. The CM-A7(b) h′ is: I7 with a2 = iss(v = p, prep 1, d = f = 1), referenced, con none, σ′ = (q, p), and a1 unchanged (f = ∞). The spec does not fix con(a2) or f(a1); I chose none and unchanged. I also report the number of U₇ witnesses when the added a2 is allowed to be referenced.
10. **K8.6.** "No h_v exists" is checked strongly: in U₆ no history at all is in H-D∖H-B with constant σ and w1 violating, so no h_v exists for any h ∈ R_B₆. I also confirm that R_B₆ is non-empty.
11. **K9.3.** "Qe never takes a value outside P" is read as "never takes a value in V∖P", i.e. never q. ⊥ (w1 absent) occurs over U but is not a deontic value. On H-D∩R_B and H-B, w1 is always present, so Qe = p there, and under that scoping the literal reading also holds.
12. **K9.4.** The named witnesses are checked as the existence of a value in the first difference with σ = (p, q) and F̂ = ∅, and of a value in the second difference with σ = (q, q). The least witnessing histories are reported. Q2's "obj" is recorded as the other slot for adp acts.
13. **Q* labels** λ1 and λ2 are strings. Q1ʙ, Qm and Q* use H-B or H-D membership as computed in U.
14. **O⁺ = (O, Ref, con, d).** d is the function on present acts, so its domain (which slots are present) is part of the value. Kind is not part of it. O₃′ = (O, con graph). O₈'s set includes only present iss slots with prep 0.
15. **Ambiguity (K10.1, K10.5–K10.7).** A pair is ambiguous at o if the class contains, in the O-fibre (or O₈-, O₃′-, O⁺-fibre), histories with (ρ1, s) ∈ Ref and con(s) = ρ1, and histories with (ρ1, s) ∈ Ref and con(s) = none. The fibre is taken within the class 𝓒 or frame named. In K10.7 (c1), K10.1 is re-run in U₉ for all four (𝓒, scope) combinations, restricted to h with c = c1.
16. **K10.2.** "No pair is ambiguous" is checked over all of U, which implies it for every subclass. "K4 holds for O⁺" is checked as: every h ∈ R_B has some h* ∈ H-D∩H-B with O⁺(h*) = O⁺(h).
17. **K10.3(b)** needs one slot s that is present and referenced in both h and h′, with different d.
18. **K10.4.** "No adp act on s" means the other slot is not adp.
19. **K12.1.**
   - "Depends on group X" means: there exist h, h′ ∈ U that agree on everything outside X, with different observation values. For d-only, X is the d of every present act (iss and adp).
   - For additions, I check every (+act) and (+work) fresh addition h⁺ ∈ U of every h ∈ U. The witness reported is the least pair.
20. **K12.2.**
   - A5 and A9 (con-only, d-only) are decided by grouping U on the coordinates that the replacement leaves fixed. A replacement that satisfies F1/F2 stays within the coordinate ranges, so it is exactly a member of U in the same group. The property fails iff some group contains both a member and a non-member of the frame.
   - A7 is checked by generating every (+act), (+work) and (+ref) fresh addition of every frame member, and testing it against F1/F2 and the frame axiom.
   - (+actor) is not representable (§7.3). U₆ is not listed in K12.2, so it is not run there.

## Checks not implemented
None of K1–K12.2 was skipped. §7.3 items (Def 4.9/Prop 5.4(c), (+actor), the generic statements, phenomena needing larger parameters, and Conj 5.5) are out of scope by the spec and are not tested.

## Runtime
About 130 s wall-clock for one `python3 verify.py` run (129.2 s on the final run; Python 3, single process).
