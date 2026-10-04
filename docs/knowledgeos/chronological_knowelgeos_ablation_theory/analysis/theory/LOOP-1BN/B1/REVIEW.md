# Targeted re-review of V1a r1 (V1A-LEMMA.md r1, V1B-PREDICTIONS.md r1, CHANGELOG-r1.md)

**Inputs used (only):** `REVIEW-TASK.md`, `TASK.md`, `CHANGELOG-r1.md`, `V1A-LEMMA.md`, `V1B-PREDICTIONS.md`, `REVIEW-r0.md` / `.json`, `FORMAL-THEORY-R4.md` (R4), `HYPOTHESES.md` (HYP), `R6-VALIDATION-MATRIX.md`. No command was run on the documents. Every control output, every new classification proof and Lem V3.9(d) were checked by hand against these R4 parts:
- Def 1.7b, Def 1.10 and Def 3.1;
- Prop 4.1(iii) and K7 (all three constraints);
- U, U₅, the R-1 order and I7 (§7.1).

**Limitation of this review:** the r0 copies of V1A and V1B are not in the directory. So the regression check (§4) compares r1 with what `REVIEW-r0` quotes and with what `CHANGELOG-r1` declares. It is not a textual diff.

## Verdict: **PASS_WITH_LIMITATIONS**

- **Controls.** Every control is now a per-history or whole-class evaluation. None depends on which counterexample is least.
  - I recomputed every required value in U and U₅. Each is what a correct enumerator reports, so no control can be failed by a correct run.
  - Each control catches the break it targets. There is one gap: C-O1 under R-i and R-i′ cannot see a σ-blind O₁.
- **M1–M3.**
  - The licences are acceptable.
  - Each classification is proved: R-i, R-i′, R-j and R-g⁺ are not-M, and R-k is M but not T.
  - The new sealed predictions follow from the lemmas and from computation in U, and they can be falsified.
  - R-k is rejected with a stated reason.
- **Lem V3.9(d).** The proof is now **valid**.
- **Other fixes.** All the listed fixes are applied correctly.
- **R4.** It is not revised.
- **Blocking.** None. No proof is invalid, and no control can be failed by a correct run.

**Most important limitation:** sealed **Conj P1.7** (R-h, least counterexample to (iii)-L) is wrong.
- r1 flags this itself (CHANGELOG, "Found during r1") and leaves it unchanged, as TASK requires.
- I confirm the error. A correct V1b run will report a least counterexample that lacks both of P1.7's bold features, so the R-h (iii) row will be scored **falsified**.
- The r0 review checked P1.7 wrongly: it left out K7's third constraint.
- This is a prediction, not a control or a proof, so it is not blocking under this task's rule. But it **must be decided by the human before V1b runs**.

---

## 1. Controls

The criterion for each control: it is independent of least-counterexample reporting, a correct enumerator detects it, a broken one does not, and its required output is an exact field and value.

| Control | Independent of the least counterexample? | Correct run detects? (checked in U) | Broken run caught? | Exact field and value? |
|---|---|---|---|---|
| C-U5, R-c / R-d / R-g / R-g⁺ | yes (image difference) | yes: `diff(O; H-D^𝐑∩R_B^𝐑, H-B^𝐑) = {(0,c2),(1,c2)}`. At c2, H-B^c needs a conforming w1, H-B needs w1 (B-recW), and H-B^g and H-B^g⁺ need a change; at c1 every class is realized | yes: if U₅ is not enforced, diff = ∅ | yes |
| C-U5, R-j | yes | yes: H-D^j needs a change, which is impossible at c2; R_B has c2 members | yes | yes |
| C-U5, R-h (rewritten) | **yes** (iiiL at the named history H_U5h) | yes. H_U5h is in U₅ and in the (iii)-L scope under R-h (H-D, all items referenced, w1 at 0 violating). In U, σ′ = (p,q) is forced, and f′(a1) = 1 satisfies F1 and K7's third constraint with no added act, so `iiiL = true`. In U₅, σ′ = (p,q) at c2 is excluded, so `iiiL = false` | yes: if U₅ is not enforced, `[R-h\|U₅] iiiL(H_U5h) = true` | yes |
| C-U5, R-i / R-i′ | yes (iiO_c at H_U5i and at H_U5i[c:=c1]) | yes. At c2, every member of H-D ∩ H-B^𝐑 with w1 conforming would need σ(t(w1)) = p, which is impossible. At c1 such a member exists. The reason for using (ii)-O rather than (i)-O is correct: H-B^𝐑 contains σ ≡ q with w1 absent | yes, both when U₅ is not enforced and when H-D ∩ H-B^𝐑 is implemented as empty | yes |
| C-O1 (all readings) | yes (images) | yes, every listed set verified:<br>• R-c, R-d, R-h: {(q,q)};<br>• R-d intersection: {(p,p),(p,q),(q,p)};<br>• R-g, R-g⁺: H-B^g = {(p,q)};<br>• R-i, R-i′: diff = ∅;<br>• R-j: {(p,q),(q,p)} | R-c, R-d, R-h: these outputs equal R₀'s, and r1 now **marks them as not diagnostic of the reading** (TASK item 1). **Gap: R-i, R-i′.** A σ-blind O₁ also gives diff = ∅, so the A1 plant there is not detected (NB-2). R-g, R-g⁺, R-j: yes | yes |
| C-c | yes | yes: H_c ∈ D-rec; iS = false (w1 is absent in the whole ≈_{σ,f} class); (o, absent) is in the diff | yes: B-recW kept gives member = false; a universal B-conf gives iS = true | **almost.** The diff is stated as "contains … and not …"; the exact value is {(o, absent) : every o} (NB-3) |
| C-d | yes | yes: iS(H_d) = false (P1.2 argument); K4b(H_d) = true (Cap := ∅); H_d2 ∈ H-B; no member of H-D⁺ is ≈_{σ,f} H_d2 (both acts adopted, values differ, and the change at 1 has no iss-capability) | yes: D-st in place of D-st⁺ gives iS = true | **almost.** `exists(H-D⁺, ≈_{σ,f} H_d2)` is not a field of Def P0.3 (NB-3) |
| C-g | yes | yes: diff(O_¬σf; R_B, H-B^g) = exactly the values with t(w1) = 1 (in U, H-B^g = σ (p,q), w1 at 0 referenced, an act referenced); H_g ∈ H-D ∩ H-B^g; H_g′ ∈ H-B^g ∖ H-D | yes: a missing conjunct gives diff = ∅; an over-strict implementation fails the membership checks | yes (an intensionally exact set) |
| C-h (rewritten) | **yes** (iiiL at I7; "no output about the least counterexample") | yes:<br>• `[R-h] iiiL(I7) = false`: σ′ = (q,p) is forced, no act of I7 has v = p, and an added a2 is unreferenced;<br>• `[𝐑₀] iiiL(I7) = true` (K8.7);<br>• W7 ∈ H-B^h: F1 holds (σ(1) = p = v(a2)), B-chg holds through a2, w1 conforms, and every item is referenced | yes: R₀'s classes in place of R-h give iiiL(I7) = true; an over-strict H-B^h fails the W7 check | yes. "O(W7) = O(I7)" holds by construction and is not a reported field (cosmetic) |
| C-i | yes | yes:<br>• H_i ∈ R_B^𝐑 ∩ H-B^𝐑 and H_i ∉ H-D; iS = false (Ref = {w1} throughout the class);<br>• H_i ∉ R_B under 𝐑₀;<br>• H_∅ ∉ R_B^i and H_∅ ∈ H-B^{i′} | yes, and it also separates R-i from R-i′ | yes |
| C-j | yes | yes:<br>• H_j ∈ H-D (D-st holds through prep 0 and adp);<br>• H_j ∉ H-D^j;<br>• H_g ∈ H-D^j ∩ H-B;<br>• Cap image = {{(x,iss),(x,adp)}} | yes | yes |
| C-g⁺ | yes | yes: H_g⁺ ∈ H-B^g and H_g⁺ ∉ H-B^g⁺ (no iss slot with f = 1); H_g ∈ H-D ∩ H-B^g⁺ | yes: if the act conjunct is omitted, member = true | yes |

**Conclusion.**
- The C-h defect from r0 is fixed: the evaluation at I7 cannot be failed by a correct run.
- The r0 dependence of C-U5 for R-h on the least counterexample is removed. H_U5h is well chosen, because its U-side repair needs no added act, so it does not depend on the P1.7 issue.
- Some required outputs read fields from other runs ([𝐑₀ | U] in C-h, C-i and C-j; [R-g | U] in C-g⁺). Def P0.3 does not list 𝐑₀ as a reported run (NB-3).

## 2. M1–M3 (the new readings)

| Reading | Licence | Classification and proof | Predictions |
|---|---|---|---|
| **R-i** (B-rec := E ≠ ∅) | Valid. It applies R-c's "defeasible" argument to both halves of the record sentence, and keeps the shared acceptance that R4 formalizes as E ≠ ∅ (Lem 3.6) | **not-M, proved.**<br>• RB2 fails: E = {a}, Ref = ∅ is in R_B^i but not in D-rec.<br>• (b) The S-form fails.<br>• (c) Checked: O(H-D ∩ R_B^i) = O(H-D ∩ H-B^i) = {o ≠ ∅}, and O(H-B^i) = O(R_B^i) = O(𝓗) through h₀ (R = ∅). Statement (c) names only the first equality as failing, but at o = ∅ all three fail; this is incomplete, not wrong.<br>• (d) O₃ separates at a nonempty o | P1.10 and P1.11 are least in R-1 order and are counterexamples (Ref has no act pair). The O₃ exact difference holds. (ii)-O HOLDS in U ((+act), (+ref) under A3, A4, A7). The o = ∅ failure is **UNTESTABLE IN U** (ρ1 is always present). All derivable and falsifiable |
| **R-i′** (no B-side record clause) | Valid (the joint reading of the shared-acceptance sentence) | **not-M** (RB2 fails), as R-i | P1.12: the least history of U, and the least with w1 referenced. Correct |
| **R-j** (D-chg ∧ Chg ≠ ∅) | Valid. It is the descriptive reading of "norms change **only** through such acts", exactly parallel to R-g | **not-M, proved.**<br>• M_p^W and M_q^D fail.<br>• The S-form fails, and (O_¬σf, 𝓗) also fails (Cap = ∅).<br>• It is inconsistent at N = 0.<br>• The h_v half fails for every pair.<br>• V3.16(a) reuses V3.10(a)'s capable, adopted act.<br>• (c): the Cap property and strict O_¬σf hold | P1.13 = the P1.5 history: least in R_B, Cap = ∅, w1 referenced. Correct for (i)-S, (ii)-S and (ii)-O. (ii)-O FAILS, (iii)-L HOLDS, and the Cap image is {both}. All verified |
| **R-g⁺** (R-g ∧ an act-made change) | Valid. It is the descriptive reading of both conjuncts of H-B's sentence | **not-M, proved** (Lem V3.17). The modified witness h_b holds: a has (τ,q), prep 1 and f = N, F1 holds, Cap = ∅, and the act and work conjuncts both hold, so the middle equality fails for R-g⁺ too | The (i)-S, (ii)-S and (iii)-L least counterexamples are P1.5 and P1.6. I verified that nothing earlier is in scope. The O_¬σf difference, "t(w1) = 1 or no iss slot with v = q", is exact |
| **R-k** (status- or capability-tied act disjunct) | Weak, and stated as weak | **M, not T, proved** (I₇: the added acts are prep 1 with no adoption act and Cap = ∅; T fails by the contrapositive of Lem V2.6(iii)) | **REJECTED** (Rem V3.18a). The reason is sound: the qualifiers occur only in H-D's list. Not predicted, which is consistent with TASK |

Every not-M reading that is testable in U now has sealed predictions and a planted control (C-i, C-j, C-g⁺).

## 3. The listed rigour fixes

| Fix | Applied correctly? |
|---|---|
| R-d revision flag | **Yes.** There is now a single stated criterion (any listed R4 statement is false as stated with the reading's classes), and it is applied to every row. R-d is "yes", for the same reasons as R-c (Rem V3.7a). |
| **Lem V3.9(d)** | **Yes. The proof is now valid.**<br>• **Which equality fails:** the middle one, O_Cap(H-B^g) ≠ O_Cap(H-D ∩ H-B^g). This is identified correctly.<br>• **Cap claim:** for g ∈ H-D ∩ H-B^g, Chg ≠ ∅ (B-chg^g). D-chg then gives an iss pair, and D-st gives Adopted(a, f(a)), hence an adp pair. Correct, and stated in base-frame form.<br>• **Cap-empty witness:** h_b ∈ H-B^g. F1 holds vacuously and F2 holds. Chg(h_b) = {(N,τ)}, explained by w at 0 < N. B-conf holds because σ(0)(τ) = p (N ≥ 1). B-rec holds. Correct.<br>• **Hypotheses:** O_Cap satisfies A1–A4 and A8, because σ, f, Ref, con, d and the fresh additions leave R, r, c and Cap unchanged. 𝓗 gives A5, A7 and A9, and A6 is P ≠ V. Correct.<br>• **U-specific form:** moved to V1B, where it is now stated as a specialization.<br>• *Cosmetic (NB-6):* "𝒜 ≠ ∅" is not a frame parameter, because 𝒜 is a per-history coordinate; the intended meaning is "an actor identifier x". Also, h_b leaves α(w), r, c and k implicit. |
| R-g at N = 0 | **Yes.** It is framed as inconsistency (H-B^g = ∅) in Lem V3.9(b), Table V3.0, Rem V3.10a, Prop V5.1 and P1.9, with N ≥ 1 as a parameter condition. *Minor:* by the stated revision criterion, Cor 4.1b and (i′) are also false as stated at N = 0. The flag value ("yes") is unaffected (NB-7). |
| Lem V3.4(c): A6 unused | **Yes.** (ii-c)'s proof uses only A1, A2, A5, RB1, RB2 and M_p^W. |
| R-d ∘ R-e qualified | **Yes, and proved** (Lem V4.1(c)). I checked h₁ with f*(a) = 1 and σ* = (p,q): F1 holds; D-chg^e holds without capability; a is adopted at 1; w conforms; B-chg holds through a. So h₁ escapes. |
| R-b, R-c, R-e wording | **Yes.**<br>• R-b: "a weak" reading.<br>• R-c: D-rec is sourced from "primarily a record of norm-making", and the asymmetry is disclosed.<br>• R-e: marked as strained.<br>• R-f now points to R-c's corrected row. |
| P1.8 parenthetical | **Yes.** P1.1 is the least member of D-rec (Ref = {a2}, w1 absent) and fails B-conf^b. |
| Labels | **Yes.**<br>• Table V3.0 is labelled Rem.<br>• Lem V4.1 and Lem V3.4b now have proofs (checked: R-c′ has M; V4.1(a)–(d)).<br>• Each control is a Def with a ".R" Conj.<br>• The scoring rule is Def P3.1. |
| Items 1 and 2 (controls; M1–M3) | Applied; see §1 and §2. |

**Rigour issue in new r1 text (NB-5):** the statement of Lem V3.14(b) gives (ii)'s O-form under A1, A2, A3, A5, A6 and A7. For R-i′, the proof also needs **A4**, because of the (+act) step. The proof and the ledger say this, but the statement does not. U satisfies A4, so the prediction is not affected.

## 4. Regressions and R4

- **Declared changes.** Every r1 change I found in V1A and V1B is marked "(r1)" and is mapped in the CHANGELOG. The consequential edits listed there are the following, and each is consistent with the new readings: Def V1.1 note, Rem V1.4, Rem V2.7, Table V3.0, Prop V5.1, Rem V5.2, Conj V5.3 (restricted to (i)), Lem V4.1(a), the ledger, and V1B's scope, P0.1, P1.9 and §3.
- **Out-of-scope changes.** None found. Statement ids are stable. The numbering note (V3.6 before V3.5) is left as is, as declared.
- **R4.** No revision. Every reading that would need one is flagged under the single criterion, and nothing is edited. The R4 file and its statement ids are consistent with every citation in REVIEW-r0. (There is no r0 copy to diff; see the limitation above.)
- **Known defect carried unchanged: Conj P1.7 is wrong (confirmed).**
  - Take h₀: σ = (q,q); a1 iss with d 0, v p, prep 0 and f ∞; a2 absent; Cap = ∅; w1 at 0, referenced; Ref = {a1, w1}; r = 0, c = c1.
  - h₀ is in H-D ∩ R_B^h, and w1 violates.
  - K7 forces σ′ = (p,q). Its third constraint requires the change at 1 to be carried by an act of h entering with value q, or by f(a2). a1 has v = p, so a2 must be added, unreferenced, and B-rec^h then fails. So h₀ is a counterexample.
  - h₀ precedes P1.7 in R-1 order: it has the same σ, and a1's v = p comes before q.
  - Every earlier candidate is either outside the scope or repairable: σ = (p,p) has no violation, σ = (p,q) and (q,p) repair with σ′ constant, and with σ = (q,q), slot a1 must be present.
  - So the least counterexample has **w1 at t = 0 and an act with value p**. That contradicts both bold features of P1.7, and P1.7's closing sentence ("histories whose only act has value p are not counterexamples") is false when w1 is at 0.
  - A correct run will therefore score the R-h (iii) row as falsified. V1A's formal claim (Lem V3.12, "(iii)-L fails, at I7") remains true.
  - The r0 review's statement that "with w1 at 0 the work disjunct repairs it" was wrong for the same reason. It ignored K7's third constraint.
  - No control depends on P1.7.

## Blocking
None.

## Non-blocking (ordered by weight)
1. **Conj P1.7 (sealed) is wrong.** The least counterexample has w1 at 0 and an act with value p. A correct V1b run would mark R-h's (iii) row as falsified because of a prediction error, not a theory error. This needs a human decision before V1b runs. Options: re-seal P1.7 with h₀'s characteristics, or withdraw it and score R-h's (iii) row by the "fails at I7" evaluation only. Either way, the decision should be logged as a post-seal correction.
2. **C-O1 under R-i and R-i′ does not detect a σ-blind O₁.** The required `diff = ∅` is also what a broken O₁ reports. Fix: also require `image(O₁; H-B^𝐑)` = all four σ values, for every o. Predictions for these readings do not use O₁, so the impact is low.
3. **Field exactness.** C-d uses `exists(...)`, which Def P0.3 does not define. C-c states "contains … and not …" instead of the exact set {(o, absent) : every o}. C-h's "O(W7) = O(I7)" is not a reported field. Runs under 𝐑₀ (C-h, C-i, C-j) and cross-reading fields (C-g⁺ reads an R-g field) are used without declaring 𝐑₀ as a reported run in Def P0.3.
4. **Lem V3.13(c)** names only the first equality of (i) as failing. At o = ∅ all three fail.
5. **Lem V3.14(b)'s statement** omits A4, which the R-i′ case needs. Only the proof and the ledger have it.
6. **Lem V3.9(d)** has the cosmetic issues listed in §3: "𝒜 ≠ ∅" is not a parameter, and h_b leaves α(w), r, c and k implicit.
7. **Revision criterion vs. inconsistency at N = 0 (R-g, R-j).** Under the stated criterion, Cor 4.1b and (i′) are false as stated at N = 0. The text calls this a "parameter condition". The flag value is unchanged, but the criterion's wording and the N = 0 framing should be reconciled.
8. **Carry-over.** The predictions are sealed by the same analyst and are not independent (matrix, risk 3). The r1 documents are not re-reviewed by a non-Claude reviewer.
