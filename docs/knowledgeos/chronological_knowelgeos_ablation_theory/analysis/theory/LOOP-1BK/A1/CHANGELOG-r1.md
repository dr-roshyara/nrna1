# CHANGELOG-r1: FORMAL-THEORY-R4 r0 → r1

| | |
|---|---|
| Inputs | `FORMAL-THEORY-R4.md` r0; `REVIEW-r0.md` / `REVIEW-r0.json` (verdict FAIL); `R3-CODING-INDEPENDENT.json`, `HYPOTHESES.md`, `H5.md`, `INSTRUMENT-FINDING.md`. Nothing else was used; no commands were run |
| Scope | Targeted repair. Every item of `REVIEW-r0.json` is mapped below, in the order TASK.md fixes. Statement ids are stable; withdrawn statements stay in place, marked WITHDRAWN with a reason |
| Status | r1 is **not re-reviewed**. The proofs were re-checked by hand by the same (same-family) analyst. The R5 checks have not been run |

## 1. Blocking issues

| Item | Change in r1 | Where |
|---|---|---|
| **S-1** (blocking) | r0's Lem 4.8 ("non-circularity is needed") and its ledger claim ("a Q veridical only within one class does not suffice") are **WITHDRAWN as false**. The suffix condition is corrected: S1 is now a per-history property, "veridical and mapped at h", and **S3 is restated as "S1 at every h ∈ H-B"**. Prop 4.5 now concludes from S2 + S3; its proof applies S1 only to an h′ ∈ H-B. The same id Lem 4.8 now carries a corrected, narrower statement with proof: **(a)** veridicality on H-D alone does not suffice (the norm-maker report Q_m, with witnesses Lem 3.5(a) and (b)); **(b)** the executor report Q_e fails **S2, not S3**. If P = {p} it satisfies S3, and it is not separating by 4.1(i). The ledger rows are corrected. Def 4.9's role (Rem 4.9a), Table 4.6 ("condition 2 is S1–S3" → "a candidate route to S2 + S3"; the F01, F03, F04 and F06 rows) and Rem 4.10 are updated to match. New R5 checks K9.5 (veridical on H-B suffices) and K9.6 (Q_m) cover this | §4.5 Prop 4.5, Lem 4.8, Rem 4.9a; §4.6; §6; §7 K9.3, K9.5, K9.6 |
| **S-2** (blocking) | The unlabelled remark "such a Q only ever excludes H-B; it never excludes H-D" is **WITHDRAWN** as Rem 4.5. The reasons given are its falsity and the two flaws in its justification: a veridicality frame axiom breaks A5, and |P| ≥ 2. The reviewer's counterexample is included as **Ex 4.5a (Q*)**, with a proof that it satisfies S1–S3 and S2 and that the outcome of Lem 3.5(b) excludes H-D. The link to E01's "H-D makes no prediction" is removed. The restricted variant the reviewer sketched is **not adopted**: TASK.md asks only to withdraw the remark, and adding the variant would be a new claim. New R5 check K9.7 covers Q* | §4.5 Rem 4.5 (WD), Ex 4.5a; §7 K9.7 |
| **P-ref** (blocking; points to S-1 and S-2) | Resolved by the two rows above | — |
| **L-7** (blocking; ledger Lem 4.8) | The false ledger statement is marked WITHDRAWN, and a new ledger row is added for Lem 4.8 (r1) | §6 |

## 2. Proof issues

| Item | Change in r1 | Where |
|---|---|---|
| **P-1** | New **Lem 4.0b (joint non-dependence on σ and f)** constructs the intermediate histories explicitly: h → h[σ, f ≡ ∞] → h[σ*, f ≡ ∞] → h[σ*, f*]. Each is in 𝓗′ by A5, and each step uses A2, A1 and A2 in turn. The proofs of 4.1(i), (ii) and (iii) step 1, and of Lem 5.2's H-D case, now call Lem 4.0b. Lem 5.2 and the additions in (i′) and (iii) use explicit single-coordinate chains as well | §4.1 Lem 4.0b; §4.3; §5 Lem 5.2 |
| **P-2** | Lem 4.2's gloss now reads "every coordinate except σ and f … all their attributes **other than f**" (O_¬σf). Cor 4.3's second sentence now reads "a function of coordinates other than σ and f … attributes other than f". It notes that O₂ in CM-A2 depends on f and does separate, consistently with Cor 4.3 | §4.2 Lem 4.2, Cor 4.3 |
| **P-3** | Prop 5.4(c): "so it is not constant on the fibre" is deleted. The conclusion now rests on falsity in h₁ alone, and a note says independence may also fail in h₂ (k(ρ) = α(a)) | §5 Prop 5.4(c) |
| **P-4** | Lem 3.7 now states the side conditions **SC1** (𝓗′ closed under Cap := ∅, i.e. no frame constraint on Cap) and **SC2** (O′ blind to Cap); the proof uses them, and the base satisfies them. Lem 5.2 now assumes **A9 (no c–con tie)**: 𝓗′ is closed under con-only and d-only changes that satisfy F2. So F2 is the only constraint on con or d, and no axiom ties c to con | §3.2 Lem 3.7; §4.1 A9; §5 Lem 5.2 |
| **P-5** | Prop 4.1 is restated for a **generic pair (O′, 𝓗′)** (frames: Def 1.8; primed classes: Def 3.2), with A1–A9 as hypotheses listed per part. **Cor 4.1b** derives the base case, and **Lem 4.0a** proves that (O, 𝓗) satisfies every hypothesis except A6. (ii) now asserts **O′(h_v) = O′(h_c) = O′(h)**. (i′) gains A7 as a hypothesis, because a generic frame needs closure under the additions; this is a weakening | §4.1, §4.2, §4.3 |
| **P-6** | New **Def 1.7a (cross-history identity)**: fixed identifier universes 𝔄, 𝔈, 𝔚, 𝔑, with identity meaning the same identifier. Def 1.7 now states how each coordinate is compared (attributes with their domains, con as its graph), which makes ≈_X precise. Def 5.1 refers to Def 1.7a. New **Def 1.7b** defines replacements and fresh additions, which A4 and A7 use | §1.7 |
| **P-7** | Cor 4.3 is relabelled "separation requires dependence on σ or f". A paragraph states that it does **not** derive H5 §5.2's independence or mapping conditions | §4.2 Cor 4.3 |

## 3. Performative ambiguity

| Item | Change in r1 | Where |
|---|---|---|
| **PA-1** | Lem 5.2 is restated with the hypothesis **A9 (no c–con tie)**. New Rem 5.2a: in the model even R-56 is ambiguous, and the relevant condition is the absence of a c–con tie, not A3. Conj 5.5 now says "the empirical counterpart of the **absence of a c–con tie (A9)** … not of A3". CM-A9 (frame 𝓗₉: "a record with text c★ constitutes no act") shows A9 is needed. Cor 5.3 is qualified "within the model" | §4.1 A9; §4.4 CM-A9; §5 |
| **PA-2** | F-a now names the rule: **IF's pre-registered κ ≥ 0.67 for every coder pair** on ACT_SELF_PERFORMED, over a sealed list of register and ruling records. A pre-registration requirement fixes the record list, the coder pairs and **F-c's set S_mode before any coding**. F-c says that the selection is itself a mode judgement, made once and not revised; that R-56 (A10) is the only such record named in the inputs; and that results are read conditional on the sealed S_mode. The IF evidence moved into a body statement, Obs IF-obs (see L-6) | §5 Conj 5.5, Obs IF-obs |

## 4. Countermodels

The hypotheses were restructured so that they are pairwise independent:
- **A4** is now *invariance under fresh additions* (+actor, +work, +act with prep 1, all unreferenced and unconstituted). It no longer covers f or d, so it no longer contains A2 or A8;
- **A5** is now a semantic closure property (closure under (σ, f) replacements that satisfy F1);
- **A7** is now a closure property (closure under the four fresh additions that satisfy F1);
- **A9** is new (closure under con-only and d-only changes that satisfy F2).

Each countermodel row now has a column showing why every other hypothesis holds.

| Item | Change in r1 | Where |
|---|---|---|
| **C-1** | A4 no longer contains A2 or A8, as described above. CM-A2 now uses O₂ = (O, F̂⁰), the entry-into-force data of **prep-0** acts only, so the A4 additions (prep-1 acts) leave it unchanged. It violates only A2 | §4.1 A4; §4.4 CM-A2 |
| **C-2** | The merged "A7 (and A4)" row is split. CM-A4b uses O₄′ = (O, E), the act identifiers and kinds **without attributes**, so it does not include f or d. R5's O₄′ is redefined the same way | §4.4 CM-A4b; §7 O₄′ |
| **C-3** | **CM-A7(b)** is new. It proves that in 𝓗₇ the O-level non-separation survives through a *referenced* added act, so A7 is needed only for H5's wording "without a recorded act". Rem 4.1a says so. K8.7 adds an existence check for this | §4.4 CM-A7(b); §4.2 Rem 4.1a; §7 K8.7 |
| **C-4** | The A3 and A8 countermodels of Lem 5.2 are now formal rows with instances, **CM-A3b** (O sees con) and **CM-A8** (O₈ sees d of prep-0 acts), with ledger rows. CM-A9 is added. R5 adds K10.5 (redefined to use O₈), K10.6 and K10.7 | §4.4; §6; §7 |
| **C-5** | The CM-A1, CM-A2 and CM-A5 rows now state that the same instances also break (ii) and (iii) | §4.4 |

## 5. Base, neutrality and levels

| Item | Change in r1 | Where |
|---|---|---|
| **BV-1** | O6 is rewritten. For con: within the Claude family, IF is not an R3 item, IF's consequence is "stated, not adopted", and IF shows nothing cross-family. For Ref: no unreliability is shown (capable coders reproduced ACT_REPORTED 1.000 and NORM_STATED 0.941, exploratory), and Ref is left out of O as a modelling choice. B09 is cited as a caveat only. A3's motivation is updated accordingly | §1.11 O6; §4.1 A3 |
| **BV-2** | O1 is weakened: recording times are second-hand and incomplete (A07: R-80), and spans are missing for R24, R28 and R35 (F13). Lem 1.12 now notes that every function of O inherits the non-dependence properties, so the results apply to the coarser available observation | §1.11 O1, Lem 1.12 |
| **BV-3** | A5's gloss now says it is a modelling choice motivated by B07 (no *independent* local channel). It is not a claim that no corpus text states a norm state (Rem 1.11a). O4 and Rem 4.10 are weakened to match: R4 "does not grant" S2 and S3 for ruling texts, and "does not decide" whether they are veridical | §1.11 O4, Rem 1.11a; §4.1 A5; Rem 4.10 |
| **BV-4** | F2's basis is now "modelling assumption; motivated by IF's candidate concept, an L2 hypothesis NOT established, which F2 does not assume". The text of Def 1.8 says the same | §1.8 F2; §6 |
| **BV-5** | The §8 H01 row is corrected. It now lists the L1 items actually used (E01 for B-conf's reading and in Rem 4.10, B06 in Table 4.6, and others as scope and limits), notes that H01 is a MAY, and notes that IF is not an R3 item | §8 |
| **BV-6** | Def 1.9 now says: "the frame equates d and r **only under constitution** (F2); it never equates f with r or d". The §8 H04 row matches | §1.9; §8 |
| **N-1** | New **Rem 3.1b** discloses that B-chg's work disjunct accepts any target at any earlier time, which makes it nearly vacuous after the first work event. The target-tied variant is not adopted. The Def 3.1 reading column notes "on any target" | §3.1 |
| **N-2** | New **Rem 3.1a** discloses B-conf's universal reading as a modelling choice taken from E01 (L1), not from HYP, and states the asymmetry it creates | §3.1 |
| **N-3** | No change was needed. The existing disclosures are kept as **Rem 3.1c**, so that they carry a label | §3.1 |
| **L-1** | A1–A9 are relabelled **Hyp** (hypotheses of the generic results). Lem 4.0a states that they are theorems for the base pair, except the parameter condition A6. F2 stays Asm, the only modelling assumption of the frame | Labels; §4.1 |
| **L-2** | Rem 4.10 is labelled **Rem**, a new declared label for interpretive remarks without formal claims | §4.6 |
| **L-3** | Table 4.6 is relabelled **Rem** (interpretive analysis), and its heading says so | §4.6 |
| **L-4** | The §8 rows are relabelled **Cmp** (compliance assertions). The H10 and H12 rows now cite Def 1.8 and Def 1.9 instead of K11.1 and K11.2. R5 checks are labelled **Spec**, and the labels paragraph states that no Lem, Prop or Cor rests on a Spec | Labels; §8 |
| **L-5** | Every previously unlabelled statement now has a label, or is withdrawn: <br>– remark after 4.5 → Rem 4.5, WITHDRAWN; <br>– note after 4.6 → Rem 4.6a; <br>– "Ad is monotone" → withdrawn with §2; <br>– the F2 claims in Def 1.9 → Def 1.9 (restated) and Rem 1.9a; <br>– the "In words" gloss → Rem 4.1a; <br>– the Lem 5.2 countermodels → CM-A3b, CM-A8, CM-A9 (Lem); <br>– the closing paragraph of Prop 5.4 → Rem 5.4a; <br>– the text-as-text paragraph → Rem 1.11a; <br>– the minimality list → Rem 0.1; <br>– the role sentence of Def 4.9 → Rem 4.9a; <br>– the external content of S3 → Rem 4.5b | throughout |
| **L-6** | O6 is corrected (BV-1). The ledger row IF-obs now has a matching body statement, **Obs IF-obs**, in §5 | §1.11; §5 |

## 6. Minimality

| Item | Change in r1 | Where |
|---|---|---|
| **M-1** | §2 (Def 2.1, Def 2.2, Lem 2.3) is **deleted**. The ids remain, marked WITHDRAWN because unused. Lem 3.3 now cites Lem 3.5 for the converses, and nothing else referred to §2 | §2 |
| **M-2** | F3 is **WITHDRAWN** as redundant with the typing in Def 1.4. The frame is now F1 ∧ F2, and the proofs and R5's frame are updated | §1.4, §1.8; §7 |
| **M-3** | The A4 restructure (C-1) removes the containment. A2 is now genuinely used in (i′) and (iii) | §4.1 |
| **M-4** | A5 is rewritten as a single closure property; r0's derivable second sentence is gone | §4.1 |
| **M-5** | S3 is no longer a restatement of S1, because S1 is per-history and S3 quantifies over H-B. S1′ is **WITHDRAWN** as a model hypothesis, being vacuous; its external content is in Rem 4.6a | §4.5 |
| **M-6** | Lem 3.5(b′) is **WITHDRAWN**: it added nothing beyond (b) | §3.2 |
| **M-7** | "𝓗 ∩ R_B" is replaced by R_B (R_B′ in the generic form). Qc is dropped from R5 and K9.3 | §4.2; §7 |

## 7. R5 specification

| Item | Change in r1 | Where |
|---|---|---|
| **R-1** | A full lexicographic **enumeration order** is fixed, together with the meaning of "first counterexample" and "least witness" | §7.1 |
| **R-2** | K8.2 now says "for every o". The value is also corrected to the two-act F̂⁰ value, because in U (T = {0, 1}) a single forced prep-0 act no longer forces violation under the restricted O₂; the reason is stated | §7.2 K8.2 |
| **R-3** | K8.1 now carries the derivation of "exactly σ = (q, q)" in U | §7.2 K8.1 |
| **R-4** | K10.1 is restricted to Lem 5.2's stated scope: h ∈ H-D ∩ R_B for 𝓒 = H-D. The lemma was not strengthened | §7.2 K10.1 |
| **R-5** | O₄′ is redefined without attributes (C-2). K8.4 and K8.7 state that the failure comes only from (iii)'s literal requirement | §7.1; §7.2 |
| **R-6** | Missing checks are added: **K9.5** (veridicality on H-B suffices), **K9.7** (Q*), **K9.8** (Prop 4.6 directly), and also K9.6 (Lem 4.8(a)), K10.6, K10.7, K12.1 and K12.2 (independence of the countermodels). New **§7.3** states what cannot be tested in U: Def 4.9 and Prop 5.4(c), since no record is ever independent when an act on τ is present; (+actor); the generic statements; phenomena needing |P| ≥ 2, more targets, records or times; and the external claims and Conj 5.5 | §7.2; §7.3 |
| **R-7** | K10.3's quantifiers are made explicit in parts (a) and (b). K4 is restated with ≈_{σ, f} only; the O and O_¬σf equalities are noted as consequences, so no separate run is needed | §7.2 |
| **R-8** (REVIEW-r0.md only) | Covered by M-7: Qc and its trivial K9.3 case are removed | §7 |
| **R-ok** | No change needed. The K-check outcomes the reviewer verified are kept, except where r1's redefinitions required an update (K8.2, K8.4, K8.7, K10.1, K10.5) | — |
| **S-3** | Rem 4.5b states that the content of S2 and S3 is an external realization claim. S1′ is withdrawn (see M-5) | §4.5 |
| **S-ok** | No change needed. Prop 4.5 and Prop 4.6 remain sufficient; the realizability witnesses Lem 3.5(a) and (b) are now named in the text | §4.5 |

## 8. Compliance with the TASK.md rules

- **Weaken or withdraw, never strengthen.** These changes alter what is claimed, and each is disclosed here:
  - **Generic Prop 4.1** (required by P-5). For the base pair the conclusions are those of r0, plus O-equality in (ii) (required by P-5). The restructured hypotheses are implied by r0's: r0's A4 implies the new A4, and the base frame satisfies the new A5, A7 and A9 (Lem 4.0a). So the generic form adds no conclusion about (O, 𝓗). As a statement about arbitrary pairs (O′, 𝓗′) it is new, because TASK.md requires it. (i′) gains A7 and Lem 5.2 gains A9; adding hypotheses weakens a result.
  - **Prop 4.5 with S3 on H-B only** is a weaker hypothesis than r0's S3. TASK.md requires it ("state correctly what suffices: S1 on H-B"), and the proof is unchanged.
  - **Lem 4.8 (r1)** replaces a two-sided false claim with a one-sided, proved claim (a) and a correctly located failure (b). This is narrower than r0's claim.
  - **CM-A7(b)** and **Ex 4.5a** are new claims. Items C-3 and S-2 require them, and both carry proofs.
  - **Lem 1.12's last sentence** (functions of O inherit non-dependence) is a one-line consequence added to support the weakened O1 (BV-2).
  - **K10.1** was narrowed to the lemma, not the other way round (R-4).
- **No new primitives.** The identifier universes (Def 1.7a) are required by P-6. Def 1.7b, the frame notion in Def 1.8, the primed classes and A9 are definitions and hypotheses, not primitives. The new labels Hyp, Ex, Rem, Cmp and Spec are required by L-1, L-2, L-4, L-5 and S-2.
- **Stable ids.** Every r0 id is kept. The withdrawn ones are F3, Def 2.1, Def 2.2, Lem 2.3, Lem 3.5(b′), Rem 4.5 (r0's unlabelled remark), S1′ (as a hypothesis) and r0's Lem 4.8 content; each is marked WITHDRAWN with a reason. New ids are listed in the §6 ledger with status "new".
- **No coding-dependent claim.** The IF κ values appear only as provenance (O6, Obs IF-obs, Conj 5.5). No Lem, Prop or Cor uses them.
