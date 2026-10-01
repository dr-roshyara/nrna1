# Targeted re-review of FORMAL-THEORY-R4 r1 (frozen)

Inputs used, all from this directory: REVIEW-TASK.md, FORMAL-THEORY-R4.md (r1), CHANGELOG-r1.md, REVIEW-r0.md, REVIEW-r0.json, TASK.md, HYPOTHESES.md, INSTRUMENT-FINDING.md and R3-CODING-INDEPENDENT.json. No commands were run and the R5 enumeration was not executed. Every check below was done by hand.

## Verdict: **FAIL** (one blocking issue; the repair is narrow)

r1 resolves the two blocking items from r0:
- **S-1 is resolved.** S3 is now "S1 at every h ∈ H-B", and Lem 4.8 is correctly restated and proved.
- **S-2 is resolved.** Rem 4.5 is withdrawn, and Ex 4.5a (Q*) is correct.

The sufficiency section is now valid throughout.

r1 also introduces one new invalid proof step, and it sits in the generic (O′, 𝓗′) restatement that P-5 required:

- **B-1 (regression RG-1).** Def 1.8 lets a frame be *any* subset of 𝓢. Three generic proofs nevertheless assume that the starting history h satisfies F1 or F2: the proofs of **Prop 4.1(i′)**, **Prop 4.1(iii)** and **Lem 5.2**. Neither F1 nor F2 is a hypothesis, and A5, A7 and A9 are closure properties only: they put F1- or F2-*satisfying* modifications into the frame. They do not say that members of the frame satisfy F1 or F2. The steps that rely on F1 or F2 in h are:
  - (iii), Step 1: "an act still in force has (f(a), τ(a)) ∉ Vio, so σ′ = σ = v(a) there" ("By F1 in h");
  - (i′): "each step satisfies F1 (a₀ never enters into force)";
  - Lem 5.2: "F2 still holds, because a constitution was only dropped", and "F2 holds, because … no other act changed".

  If h violates F1 (or F2), the modified history violates it too. The closure hypotheses then do not apply, so the proof does not establish that the constructed history is in 𝓗′.

  *Concrete case.* Let 𝓗′ = 𝓗 ∪ {h₀} and O′ = O. Here h₀ ∈ H-D′ ∩ R_B′ violates F1 through an issue act b with f(b) = 1, v(b) = p and σ(1) = q, and its work w at 0 violates. This pair satisfies A1, A2, A4, A5 and A7. The (iii) construction keeps f′(b) = 1 but sets σ′(1) = q, so h[σ′, f′] fails F1. That history is not in 𝓗′, so Lem 4.0b cannot be invoked.

  The **statements** themselves are still true; I checked alternative proofs. For (i′) and (iii), first pass to h[σ, f ≡ ∞], or set f′ = ∞ also on every act that violates F1; this uses A5 and A2. For Lem 5.2, first drop *all* constitutions (A9, A3), then set d(a) := r(ρ) (A9, A8), then set con(a) := ρ. The **base-case results are also unaffected**: Cor 4.1b, and Lem 5.2 on 𝓗, hold because 𝓗 ⊆ F1 ∧ F2.

  The task rule makes an invalid proof of a labelled statement blocking. **Minimal fix:** in Def 1.8, define a frame as a subset of 𝓗, so that it satisfies F1 ∧ F2. Every countermodel frame (𝓗₅, 𝓗₇, 𝓗₉, and the CM-A6 frame) already is one, so no other result changes. Alternatively, insert the two preliminary steps above.

Apart from B-1, I found none of the following: a false labelled statement, a hypothesis used as an axiom, a coding-dependent claim, a base violation or a neutrality problem. Once B-1 is fixed, I would expect a **PASS_WITH_LIMITATIONS**; the remaining limitations are the non-blocking items in §8.

---

## 1. Blocking items S-1 and S-2: resolved. Full re-check of §4.5

| Statement | Check |
|---|---|
| Prop 4.5 (S2 + S3, with S3 = S1 at every h ∈ H-B) | **Valid.** The proof applies S1 only at the supposed h′ ∈ H-B. There, (λ, v) ∈ Q(h′) maps to a work event of h′ at which σ has value v ∉ P, which contradicts B-conf. S3 is no longer a restatement of S1, because S1 is now per-history (M-5) |
| Lem 4.8(a) (Q_m, veridical only on H-D) | **Valid.** At every h ∈ H-D: S1 holds via μ(λ₁) = w₁, or vacuously when w₁ ∉ W_h. S2 holds via h_a = Lem 3.5(a), where Q_m = {(λ₁, q)}. h_b = Lem 3.5(b) ∈ H-B ∖ H-D has Q_m = {(λ₁, q)} by construction and the same O (same ρ, r, c). So O_{Q_m}(h_a) ∈ O_{Q_m}(H-B). The claim is "the conclusion *can* fail", and one witness suffices for that |
| Lem 4.8(b) (Q_e) | **Valid.** It fails S2, because it never reports a value outside P. If P = {p}, it satisfies S3, because B-conf gives σ = p at every work point of a history in H-B. O_{Q_e} satisfies A1 and A2, and 𝓗 satisfies A5, so 4.1(i) gives non-separation |
| r0 Lem 4.8 and its ledger claim | Correctly marked **WITHDRAWN (false)** (L-7) |
| Rem 4.5 ("never excludes H-D") | Correctly **WITHDRAWN**. Both flaws are stated: a veridicality axiom breaks A5, and the claim fails when \|P\| ≥ 2 |
| Ex 4.5a (Q*) | **Valid.** (a) S1 holds at every h ∈ 𝓗, hence S3. (b) S2 holds via h_a, with Q* = {(λ₁, q)}. (c) h_b has Q* = {(λ₁, p), (λ₂, p)}, and every h′ with a λ₂ pair is outside H-D, so this outcome excludes H-D. Q* is correctly flagged as reading class membership (not an instrument). *Nit:* μ_h(λ) = w₁ is stated unconditionally, but it is defined only when w₁ ∈ W_h. The same applies to Lem 4.8(a). This is harmless, because Q = ∅ there |
| Rem 4.5b | Correct. The content of S2 and S3 is an external realization claim, consistent with H14 |
| Prop 4.6 (S2′) | **Valid.** A change in (t₁, t₂] requires, by D-chg and D-st, a capable act that is adopted when it enters into force, so X = 1. The witness is Lem 3.5(b). The withdrawal of S1′ (vacuous) is correct |
| Lem 4.7 | Unchanged and valid |
| Table 4.6, Rem 4.9a, Rem 4.10 | Consistent with the corrected S3 ("a candidate route to S2 + S3"; the ruling texts are "not granted"). *Nit:* Rem 4.9a calls Def 4.9 a criterion for S2 *and* S3. Independence bears on veridicality (S3), not on realizability (S2) |

## 2. D1 (Prop 4.1), step by step

| Step | Check |
|---|---|
| Lem 4.0b (P-1) | **Valid.** h¹ = h[σ, f^∞] and h² = h[σ*, f^∞] satisfy F1 vacuously; h³ satisfies it by assumption. All three are in 𝓗′ by A5, applied to the original h. The chain uses A2, then A1, then A2. Joint non-dependence is properly derived |
| Lem 4.0a | **Valid.** A5: F2 does not mention σ or f. A7: (+act) items are unconstituted, and (+ref) only adds pairs. A9: F1 does not mention con or d. A1–A4 and A8 follow from Lem 1.12 |
| 4.1(i) | **Valid** for a generic pair. h[p, ∞] satisfies F1 vacuously, so this step does not need F1 in h |
| 4.1(ii) | **Valid.** The O′-equality is now asserted. h_v = h[q, ∞] is in H-D′ ∖ H-B′ via the referenced w |
| 4.1(i′) | **Statement true; proof invalid for a generic 𝓗′** (B-1). "Each step satisfies F1" presupposes F1 in h. Repair: start from h[σ, f^∞] |
| 4.1(iii) | **Statement true; proof invalid for a generic 𝓗′** (B-1), at Step 1 ("By F1 in h"). With F1 in h, every remaining step checks: F1 of h[σ′, f′]; the added acts; B-chg; B-conf; Ref unchanged; 𝒜 ≠ ∅ |
| Cor 4.1b (base) | **Valid**, because 𝓗 ⊆ F1 ∧ F2 |
| Lem 4.2, Cor 4.3 (P-2, P-7) | **Valid.** "All attributes other than f" is now correct. CM-A2's O₂ depends on f and separates, consistent with Cor 4.3. The label is no longer overclaimed |
| Lem 3.7 (SC1, SC2) | **Valid** generically. h*[Cap := ∅] needs no F1 or F2 premise |
| Lem 5.2 (A9) | **Statement true; proof invalid for a generic 𝓗′** (B-1), because "F2 still holds" presupposes F2 in h. Valid on 𝓗 |

## 3. Countermodels and independence

Each row was checked: the instance has the stated property, the target conclusion fails, and every other hypothesis holds.

| CM | Violates | Others hold? | Conclusion fails? |
|---|---|---|---|
| CM-A1, O₁ = (O, σ) | A1 | yes. σ is unchanged by f-, Ref-, con- and d-changes and by additions | (i), (ii), (iii): yes |
| CM-A2, O₂ = (O, F̂⁰ of prep-0 acts) | A2 | yes. Additions are prep-1, so A4 holds. C-1 is fixed | yes (T = {0}; D-st holds) |
| CM-A3a, (O, Ref) | A3 | yes. The new items of the A4 additions are unreferenced | (i′): yes |
| CM-A3b, (O, con) | A3 | yes | Lem 5.2: yes |
| CM-A4a, (O, [W ≠ ∅]) | A4 | yes | (i′): yes |
| CM-A4b, (O, E with kinds only) | A4 | yes. No f or d, so C-2 is fixed | (iii) at I₇: yes, through the literal form only |
| CM-A5, 𝓗₅ | A5 | yes. A7 holds because additions keep σ and c; A9 holds | (i), (ii), (iii): yes |
| CM-A6 | A6 | yes | (ii): yes |
| CM-A7, 𝓗₇ | A7 | yes (A5, A9) | (a) the literal (iii) fails; (b) the O-level non-separation survives, and its proof is valid (C-3) |
| CM-A8, O₈ | A8 | yes | Lem 5.2: yes (d(a) = 0 ≠ r(ρ) = 1) |
| CM-A9, 𝓗₉ | A9 | yes (A5, A7) | Lem 5.2: yes |

**Each countermodel violates exactly one hypothesis.** The A4 restructure removes A4 ⊇ A2, A8 (C-1, M-3), so the hypotheses are now independent, in the full sense of one countermodel per hypothesis. K12.1 and K12.2 encode this correctly.

## 4. Performative ambiguity (PA-1, PA-2)

- **Def 5.1 is well-formed:** it is a fibre property, with identity fixed by Def 1.7a.
- **Lem 5.2 is now stated under A9 (no c–con tie).** CM-A9 shows that A9 is needed. Rem 5.2a, Cor 5.3 ("within the model") and Conj 5.5 ("counterpart of A9, not A3") are consistent. PA-1 is resolved, apart from the generic-proof gap in B-1.
- **Conj 5.5 is falsifiable, and F-a and F-c are pre-fixed:**
  - the record list, the coder pairs and S_mode are sealed before any coding;
  - F-a names κ ≥ 0.67 for every pair on ACT_SELF_PERFORMED, consistent with IF;
  - F-c's selection is made once and not revised.

  PA-2 is resolved.
- **Non-blocking issues:**
  - **NB-4.** F-b's decision rule is still not pre-fixed: who reviews, and what share of the disagreeing cells must be "deviations". The review protocol is also not among the sealed items.
  - **NB-5.** Conj 5.5 says, universally, that on register and ruling records the text does not determine the mode. F-c presupposes a subset S_mode of those records whose text states its own mode. The conjecture's scope should exclude S_mode, or be stated "in general". F-c may also have no power: if S_mode = {R-56}, it rests on a single record, and the inputs do not establish that R-56 is among the 36 coded records.
- **Obs IF-obs matches IF:** 2 of 9 cells; 0.598, 0.583 and 0.637; the exploratory parts are marked; and the result is "stated, not adopted".

## 5. Coverage of REVIEW-r0

Every item is mapped in CHANGELOG-r1, and the change is present in the text.

All of BV-1–6, N-1–3, P-1–4, P-6, P-7, PA-1, PA-2, P-ref, C-1–5, S-1–3, S-ok, L-1–7, M-1–7, R-1–8 and R-ok are **ok**.

**P-5 is wrong in part.** The generic restatement was made, but three of its proofs were not adapted to arbitrary frames (B-1).

**Nothing is strengthened beyond what the task required.** The new claims are:
- the generic Prop 4.1, required by P-5;
- Ex 4.5a, required by S-2;
- CM-A7(b), required by C-3;
- Lem 4.8(r1), which is narrower than r0's claim;
- the one-line addition to Lem 1.12.

Each is disclosed in CHANGELOG §8. The K8.2 value was changed, and I verified the change is correct (see §7). Changelog nit: the S-2 row says "satisfies S1–S3 and S2".

## 6. Regressions

| id | Kind | Issue | Blocking |
|---|---|---|---|
| RG-1 | invalid proof step | B-1 above: the generic proofs of 4.1(i′), 4.1(iii) and Lem 5.2 assume F1 or F2 in h, but frames are arbitrary subsets of 𝓢 | **yes** |
| RG-2 | wording | μ_h(λ) = w₁ is stated even when w₁ ∉ W_h (Ex 4.5a, Lem 4.8(a)) | no |
| RG-3 | wording | Rem 4.9a ties Def 4.9 to S2 as well as S3 | no |

The other regression checks found nothing:
- **Level errors:** none. The labels are consistent, and no Lem, Prop or Cor rests on a Spec.
- **Base:** no violation. O1, O4 and O6 match A07, F13, B07, B09 and IF; E01 is quoted correctly.
- **Neutrality:** no new issue. Rem 3.1a–c disclose the readings, and Q*, Q_m and Q1ʙ are explicitly class-reading examples.
- **Coding-dependent claims:** none.

## 7. R5 specification

**The spec is precise and complete enough for an independent implementer (r5_spec_ok = true).** It has:
- a total lexicographic order, with the meaning of "first counterexample" and "least witness" fixed;
- an explicit frame, classes, fresh additions and variants;
- the untestable items listed in §7.3.

I checked these expected outcomes by hand in U, and all are correct:
- **K8.1:** (q, q) is the exact difference; the derivation for (q, p) and (p, q) holds in both images.
- **K8.2:** the two-act F̂⁰ forces σ ≡ q. A single act escapes: at f = 1, through work at 0; at f = 0, through a prep-1 act at 1.
- **K8.4, K8.5 and K8.7:** the U₇ witness a2 (v = p, d = f = 1, referenced) satisfies F1, the U₇ axiom and B-chg, B-conf and B-rec.
- **K9.1–K9.8:** Q1ʙ = p on all of H-B, since B-recW forces w1; Qm = q on Lem 3.5(b); Q* as in Ex 4.5a; Q′ is (p, q, 0) on Lem 3.5(b), and X = 1 is forced in H-D.
- **K10.4:** the H-B counterexample is f = 0 < r = 1, with con = ρ1.
- **K10.5 and K10.7.**
- **K12.1 and K12.2:** O₂ and O₈ are invariant under prep-1 additions. U₅, U₇ and U₉ each fail exactly A5, A7 and A9 respectively.

Minor: K11.2's "forced acts" is undefined (presumably f ≠ ∞). K12.1 groups Ref and con together, so it does not distinguish O₃ from O₃′. Note that U enumerates only F1 ∧ F2 histories, so R5 cannot detect B-1 (§7.3 already says the generic statements are untested).

## 8. Blocking and non-blocking

**Blocking**
- **B-1 (RG-1).** The generic proofs of Prop 4.1(i′), Prop 4.1(iii) and Lem 5.2 use F1 or F2 in the starting history, which is not a hypothesis. Fix: restrict frames to subsets of 𝓗, or add the preliminary f ≡ ∞ / drop-all-con steps.

**Non-blocking**
- NB-1: μ_h is stated outside W_h (Ex 4.5a, Lem 4.8(a)).
- NB-2: Rem 4.9a ties Def 4.9 to S2.
- NB-3: Changelog typo "S1–S3 and S2".
- NB-4: F-b's decision rule is not pre-fixed or sealed.
- NB-5: Conj 5.5's universal scope versus F-c's S_mode; F-c may have no power.
- NB-6: K11.2's "forced" is undefined; K12.1 groups Ref and con together.
