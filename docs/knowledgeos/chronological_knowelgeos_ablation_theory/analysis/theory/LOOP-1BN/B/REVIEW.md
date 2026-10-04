# Review of V1a (V1A-LEMMA.md) and V1b predictions (V1B-PREDICTIONS.md)

**Inputs used (only):** `REVIEW-TASK.md`, `V1A-LEMMA.md`, `V1B-PREDICTIONS.md`, `HYPOTHESES.md` (HYP), `FORMAL-THEORY-R4.md` (R4), `R6-VALIDATION-MATRIX.md`. No command was run. Every proof and every U-level prediction was checked by hand against R4's definitions (Def 1.7b, 1.10, 3.1, 3.2), R4's proofs (§4.3) and the R-1 order (§7.1).

## Verdict: **PASS_WITH_LIMITATIONS**

- I found no invalid proof of a stated Lem, Prop or Cor that the classification depends on.
- No included reading is outright unlicensed: R-c and R-e are within the matrix's V1b scope, and the others have a defensible HYP derivation.
- Nothing silently revises R4.
- Lemma M is precise, valid and sufficient for D1(i). Its (ii) and (iii) companions are also valid.
- Every M / not-M classification is correct.
- Every sealed prediction I recomputed in U matches, including all seven least-counterexample and least-witness characterizations.

The limitations are:
- the reading set has gaps and one mis-cited derivation, which matter most for the record clause, where RB2 is never exercised;
- one control (C-h) is worded so that a *correct* enumerator could fail it;
- one revision flag is applied inconsistently;
- a few labelling and proof-detail issues remain.

---

## 1. Readings

| Id | Licensed? | Comment |
|---|---|---|
| R-a | yes | A target-tied B-chg. R4 Rem 3.1b names it as the non-adopted variant, and the HYP phrase "in response to work" supports it. |
| R-b | yes | "Is expected to conform", read existentially. **Overstated:** it is called "the weakest non-vacuous" reading, but Rem V3.4b's "not every work event violates" is weaker (vacuous on W = ∅) and still non-vacuous. |
| R-c | yes (the matrix requires it) | **The derivation is mis-cited, and the two halves are inconsistent.** (1) The record clause B-rec^c := D-rec is justified by "both hypotheses accept that norm-making acts exist", "which D-rec already formalizes". R4 formalizes that sentence as **E ≠ ∅** (Lem 3.6, G06). D-rec comes from H-D's "primarily a record of norm-making" (Def 3.1). (2) The same "expected to" is read existentially for conformance (it constrains histories) but defeasibly for the record (it constrains nothing). The reading is still a reasonable partial weakening, and V1b requires it, so this does not block. |
| R-d | yes | "Depends on" read as a biconditional. R4 Lem 3.7 names it. |
| R-e | yes (the matrix requires it); the HYP derivation is strained | HYP says "by actors holding the capability to perform them". Reading this as a description of the actors rather than a condition is a stretch: describing the actors as capable would, if anything, strengthen the clause. This is harmless, because R-e is a weakening and has M. |
| R-f | yes | The R-c record weakening with universal B-conf. It carries the same derivation caveat as R-c (1). |
| R-g (added) | licensed but **contested** | It turns a generic sentence ("norms change through acts and in response to work") into an existential demand on every history. Read modally ("may change"), which is R4's reading, the sentence demands nothing. If R-g is kept, the parallel D-side reading must be added (see the missing readings below). At N = 0, R-g makes H-B^g empty. |
| R-h (added) | licensed | "Carry, or reference, both" read universally. This is the class-level analogue of 𝓗₇. |

**Missing readings (none blocking, but they should be added or explicitly rejected in Rem V1.4):**
- **M1 (record clause, existential import of acts).** Apply R-c's own "defeasible" argument consistently and keep only what R4 itself derives from "both accept that norm-making acts exist". That gives B-rec := E ≠ ∅, or no B-side record clause at all.
  - It **violates RB2**: R_B ⊄ D-rec.
  - For the base pair it breaks (i): H-B then contains histories with R = ∅, so ∅ ∈ O(H-B) ∖ O(H-D).
  - The failure is degenerate, but it is a real failure. No reading in the set tests the RB2 hypothesis of Lemma M at all.
- **M2 (D-side existential, the analogue of R-g).** R-g's own justification invokes the parallel with H-D's "norms change only through such acts". The descriptive reading of that sentence (Chg ≠ ∅, with every change made by an act) is equally licensed and is not-M. The same descriptive reading of H-B's "and" also yields an act-existential conjunct, which R-g omits. Adding only the B-side existential reading biases the set.
- **M3 (status or capability variant of B-chg's act disjunct).** Here the act that explains a change must be adopted, or must be capable. Its licence is weak, since the status clause belongs to H-D. But TASK asks for status variants. This variant fails T, because R4's (iii) adds prep-1, unadopted acts. So the literal (iii) would fail as it does under R-h. It should at least be listed and rejected in Rem V1.4.

## 2. Lemma M

- **Statement.** It is precise. Defs V2.1–V2.4 separate RB1, RB2, M_p, M_p^W, M_q^D, N_q and T cleanly. M is defined relative to the reading's *own* record base. This is the right refinement of the matrix's informal M, and it is exactly what catches R-c.
- **Comparison with R4.** Rem V2.0 matches R4 §4.3 clause by clause: (i) uses h* = h[σ ≡ p, f ≡ ∞]; (ii) reuses it with q; (iii) uses a different construction.
- **(i) proof.** Valid.
  - Lem 4.0b gives h* ∈ 𝓗′ with the same O′.
  - RB1 gives 𝔅_rec, RB2 gives 𝔇_rec, and M_p gives the dyn clauses.
  - The image chain follows from H-B^𝐑 ⊆ R_B^𝐑.
- **(ii) proof.** Valid. (ii-c) does not actually use A6. (ii-v) uses only w ∈ W_h, which is a correct and slightly more general reading of R4.
- **(iii) proof.** Valid.
  - The substitution of "α(w) ∈ 𝒜 because w ∈ W_h" for R4's B-recW argument is correct. It changes nothing in R4.
  - R4's B-chg check is exactly "act-explained", which is what T1 needs.
  - T2 covers the σ/f step and the (+act) step.
- **Sufficiency.** M (RB1 + RB2 + M_p) is sufficient for D1(i) under A1, A2 and A5. (ii) also needs M_p^W, M_q^D and N_q, and (iii) needs T. The lemma states this correctly and does not claim that M is necessary.
- **Readings that change R_B (Rem V2.7).** The treatment is correct:
  - dropping B-recW enlarges the domain (this is where R-c breaks);
  - H-D ⊆ D-rec turns (i) into the unrestricted form;
  - (iii) needs T2, which R-h fails.

  **The limitation:** no reading violates RB2 (see M1). So the claim that "RB1 and RB2 hold for all readings here" is true, but only because the set omits such a reading.
- **Cor V2.8 and Cor V2.9.** Both are correct. V2.9(c) is correctly restricted to readings that change only dyn clauses, with the record base fixed.

## 3. Classification

Each classification was checked against the definitions. All are correct.

- **R-a, R-b, R-e, R-f, R-h: M is correctly shown.**
  - R-b's M rests on B-recW supplying W ≠ ∅.
  - R-f's universal B-conf is vacuous on W = ∅.
  - R-h fails T (Lem V3.12). Its I₇ argument is correct, and so is the referenced-act variant under A3.
- **R-c: not-M.** The witness is Lem 3.5(a′).
  - The generic failure through O₄ is valid: O₄ satisfies A1, A2 and A5.
  - The repair with A4 and A7 (Lem V3.4(c)) is valid. *Minor:* it cites (ii-c), whose statement assumes A6. The proof does not use A6, so this should be said.
- **R-d: not-M.** I checked h₁, h₂ and h₃ by hand: F1, the adoption condition, and the absence of iss-capability all work out. The SC1/SC2 repair (Lem V3.7) is valid.
- **R-g: not-M.**
  - (b), (c) and (e) are valid.
  - **(d) has a gap.** The proof exhibits O_Cap(h) ∈ O_Cap(R_B) ∖ O_Cap(H-D ∩ H-B^g), which breaks the *last* equality. The claim is that the *middle* equality fails. That also needs O_Cap(h) ∈ O_Cap(H-B^g). This is true (take Lem 3.5(b) with Cap = ∅ and h's records) but not argued.
  - The phrase "Cap ⊇ {(x,iss),(x,adp)} (with 𝒜 = {x})" is a U-specific statement inside a claim about 𝓗. The generic fact is "Cap contains some iss pair and some adp pair".
  - Lem V3.10(a)'s direct construction is valid.
- **Does any not-M classification wrongly imply that D1 fails?**
  - The text consistently separates three things: the generic failure under A1/A2/A5, the S-form failure, and the base-pair O-form claim. For R-c and R-d, the base-pair O-level non-separation is correctly stated to survive.
  - **Two overstatements:**
    - **R-g at N = 0.** The "failure" is H-B^g = ∅: the reading cannot be satisfied at N = 0. Calling this a D1 failure that requires revising Cor 4.1b overstates it. It is better stated as "R-g is inconsistent at N = 0".
    - **Rem V4.1's "R-d ∘ R-e: no interaction".** This is true for Lem 3.7's Cap repair. But under D-chg^e, h₁ and h₃ stop being S-form counterexamples, because the adopted act itself explains the change. So the S-form failure set does interact.
- **Revision flags (Item 6).** R-c is flagged "R4 would need revising if adopted". R-d is flagged "no revision". But if D-st⁺ were adopted *as the reading*, three parts of R4 would fail as stated, exactly as for R-c:
  - Prop 4.1(i)'s hypothesis list would need SC1 and SC2;
  - Lem 4.2 would fail;
  - Cor 4.3 would fail.

  The criterion "R4 already names it in Lem 3.7" is not applied symmetrically. This is not a silent revision, but the flag should be "yes, if adopted" for both, or the criterion should be stated.

## 4. Predictions (V1B)

I recomputed every row in U with the R-1 order. All of them match.

- **R-c.**
  - (i)-S FAILS. P1.1, the least member of the D-rec class, is correct: σ = (p,p), a1 absent, a2 = (iss, 0, p, 0, 0), Cap = ∅, w1 absent, Ref = {a2}.
  - (i)-O, (ii) and (iii)-L HOLD.
  - The O_¬σf difference is exactly the values with w1 absent.
- **R-d.**
  - P1.2: a2 has v = q and f = ∞, Cap = {adp}, and w1 is at 0. This is correct, and every earlier candidate is excluded.
  - P1.3 (v = p) precedes it and fails only the h_v half. Correct.
  - P1.4 is correct, including "no single-act witness".
  - The O_¬σf strictness is correct.
- **R-g.**
  - The characterization of H-B^g in U is correct.
  - P1.5 (the least member of R_B) and P1.6 (σ = (p,q), w1 at 1) are correct. Every σ = (p,·) candidate below P1.6 is excluded.
  - The exact difference (t(w1) = 1) is correct.
  - The Cap-only-{iss, adp} claim on the intersection is correct.
- **R-h.**
  - P1.7 is correct. Candidates with σ = (p,q) or (q,p) are repairable by f′ := ∞. With σ = (q,q) and w1 at 0, the work disjunct repairs it. With σ = (q,q) and an a1 of value p, retiming repairs it. The first failure is a1 = (iss, 0, q, 0, 0) with Cap = {adp}, and it precedes I7.
- **UNTESTABLE IN U.** These claims are correct: R-a and R-a ∘ R-g need two targets, and R-g at N = 0 needs N = 0, while U has N = 1.
- **Falsifiability.** The predictions are specific enough to be falsified: they give HOLDS/FAILS verdicts, exact difference sets, and essential least-counterexample features.
- *Minor:* P1.8's parenthetical reason ("w1 absent precedes every case of P1.2") misstates the order, since w1 comes after a2 and Cap. The conclusion is right, because P1.1 is the least member of R_B^c.
- *Disclosure carried over:* the predictions are sealed by the same analyst, so they are not independent (matrix risk 3).

## 5. Controls

| Control | Detected by a correct enumerator? | Missed by a broken one? |
|---|---|---|
| C-U5 for R-c, R-d, R-g | yes | yes: if U₅ is not enforced, no (r,c2) appears in the difference. If H-B is mis-implemented as empty, the (r,c1) exclusion catches it. For R-c it also catches a universal B-conf, because B-conf is vacuous with w1 absent |
| C-U5 for R-h | yes: the least counterexample in U₅ has c2 and precedes P1.7. I verified it | only under least-counterexample reporting. In plain U there are also c2 counterexamples (P1.7 with c = c2), so a "list all" enumerator would not reveal a broken U₅. The control should require the *least* counterexample |
| C-O1 | yes (all four sets verified) | it catches an observation that ignores σ. For R-g, the presence of (p,p) diagnoses a missing existential conjunct. For R-c, R-d and R-h the outputs equal R₀'s, so the control is not diagnostic of the reading |
| C-c | yes | yes: R_B wrongly keeping B-recW, or B-conf wrongly kept universal, are both caught |
| C-d (H_d, H_d2) | yes | yes: it distinguishes D-st⁺ from D-st and checks the Cap repair |
| C-g | yes | yes: the t(w1) = 1 rule catches a missing conjunct, and the H_g membership checks catch over-strict implementations |
| C-h | **at risk** | yes: under R₀, I7 satisfies (iii) |

**The C-h defect.** C-h requires that "(iii)-L under R-h reports **I7** as a counterexample". Under R4 §7.2's convention, a universal check reports the *first* counterexample. Here that is P1.7, which precedes I7. So a correct enumerator could fail this control, and under the §3 scoring rule the R-h run would then be declared invalid. The control should be reworded as a per-history evaluation: "(iii)-L evaluated at I7 finds no h′". The witness part of C-h is correct.

## 6. Levels, and no R4 revision

- **V1A.** Every numbered statement is labelled, and the ledger is complete. Three issues remain:
  - **Rems that assert unproved formal claims:**
    - Rem V4.1: "the only combination of two M-readings that loses M"; the R-d ∘ R-c image equalities; the R-g ∘ R-f failure set;
    - Rem V3.4b: the "not every violates" variant "has M".

    These are true or easy, but the Rem label forbids them. They should be relabelled Lem/Cor with proofs, or moved.
  - Table V3.0 has no in-text label.
  - The lemma-numbering rule as stated ("follows Table V3.0's order") is not what the document does: R-e's V3.5 precedes R-d's V3.6. This is cosmetic.
- **V1B.** Predictions are Conj and definitions are Def. But the §2 control "required outputs", which are themselves claims (for example, C-O1's exact sets), and the §3 scoring rule carry no label.
- **No R4 revision.** None is made. V2.6(iii)'s substitution is explicitly non-substantive, and every needed revision is flagged. The only problem is the asymmetric R-c / R-d flag in §3.

## Blocking
None.

## Non-blocking (ordered by weight)
1. **Missing reading M1.** The record clause B-rec := E ≠ ∅ (or none) violates RB2 and breaks (i) through ∅ ∈ O(H-B). It also exposes the mis-cited derivation of R-c and R-f.
2. **Missing reading M2.** The D-side existential reading is the analogue of R-g. R-g also omits its own act-existential conjunct, so the set is asymmetric.
3. **The C-h wording** conflicts with least-counterexample reporting, so a correct run could be invalidated.
4. **The R-d revision flag** is inconsistent with R-c's.
5. **Lem V3.9(d)** shows the last equality failing, not the middle one. It also states a U-specific Cap claim inside a claim about 𝓗.
6. **R-g's licence is contested, and the N = 0 failure is H-B^g = ∅.** It should be framed as the reading being inconsistent at N = 0.
7. **Missing status variant M3** of B-chg's act disjunct. It should be listed and rejected.
8. **Wording and rigour issues in the readings:** R-b's "weakest non-vacuous" is overstated; R-e's derivation is strained; R-c reads "expected to" inconsistently.
9. **Rem V4.1's "R-d ∘ R-e: no interaction"** is misleading for the S-form.
10. **Lem V3.4(c)** cites (ii-c) under A6, which it does not assume. It should note that A6 is unused.
11. **Labelling:** Rems that carry formal claims; Table V3.0 has no label; the V1B controls and scoring are unlabelled; the numbering rule is not followed.
12. **C-U5 for R-h** depends on least-counterexample reporting. C-O1 is not diagnostic of the reading for R-c, R-d and R-h.
13. **P1.8's** parenthetical reason misstates the order.
