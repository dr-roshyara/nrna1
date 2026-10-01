# Review of FORMAL-THEORY-R4.md (frozen draft)

Inputs used: FORMAL-THEORY-R4.md, R3-CODING-INDEPENDENT.json, HYPOTHESES.md, H5.md, INSTRUMENT-FINDING.md (IF). Nothing outside this directory was used.

## Verdict: **FAIL**

The core of the D1 result is valid: Prop 4.1 (i), (i′), (ii) and (iii), Lem 4.2's main statement, the first sentence of Cor 4.3, Props 3.4 and 4.5 to 4.6, and Lems 3.3, 3.5, 3.6, 3.7, 4.7 and 5.2. I checked every one by hand. The neutrality discipline holds: neither hypothesis is built into the primitives, into the frame or into O. I found no coding-dependent claim and no violated section-H "may not".

The draft still fails under the task rule "an invalid proof is blocking". There are two blocking defects, both in §4.5 (the sufficient conditions):

- **B1 (S-1). Lem 4.8 does not prove its claim, and the claim is false.** Its executor example fails S2, not S3. In fact S1 holding on H-B alone is enough to exclude H-B.
- **B2 (S-2). The unlabelled remark after Prop 4.5 is false, and its justification is invalid.** The remark says such a Q "only ever excludes H-B; it never excludes H-D".

These errors matter beyond §4.5. Table 4.6 tells future work that H5 §6 reopening condition 2 "is S1–S3", and it gives Def 4.9 the role of a criterion for S3. Both fixes are local; the D1 core does not change.

---

## 1. Base discipline

**Item ids.** Every base id that R4 cites exists in R3. R4 does not use A14 (absent from R3) or A16 (L1).

**Coding-dependent claims: none found.** R4 states no act or norm counts and uses no A′ codings. The "23 performed vs 1 refused" count from H5 §2 and the "< 10%" estimate are not used. Conj 5.5 cites IF's disagreement anatomy, which comes from instrument codings. That material is labelled exploratory and kept inside the conjecture, so it is acceptable, but it is not an R3 item (see BV-1).

**Section-H "may not": none violated.** I checked each one:
- H07: hypotheses never appear as axioms.
- H10: the frame has no norm→act or norm→work axiom.
- H11: Prop 4.6 asks for a positive attestation.
- H12: nothing is read from r, because con is unobserved.
- H14: R4 states sufficient conditions only.
- H18: no aggregation.
- H19: Cap is never taken from host membership.

**Non-blocking base issues**

| id | Location | Issue |
|---|---|---|
| BV-1 | O6, ledger row "IF-obs" | The real support is IF, which is not an R3 item. B09 reports *perfect* within-family agreement and supports only the caveat. F15, F16 and G04 do not show unreliability. O6 also goes further than IF in three ways. (a) It extends the mode result to Ref ("whether a text refers to an act, a work event or a norm"), but IF reports that the capable coders reproduced ACT_REPORTED (1.000) and NORM_STATED (0.941). (b) It drops IF's qualifier "within the Claude family". (c) It omits that IF's consequence is "stated, not adopted". A3 holds anyway because of how O is defined, so no proof is affected. |
| BV-2 | O1 | "O sees record identities, recording times r, verbatim texts c" overstates the base. A07: R-80 has no timeline entry, and the timeline is second-hand. F13: R24, R28 and R35 have no source span. A coarser O only strengthens D1, but the claim should be weakened. |
| BV-3 | A5 gloss, Rem 4.10 | "No corpus record is an attestation of the norm state" is stronger than B07. B07 only says no local channel is an *independent* norm-in-force record. Rem 4.10 dismisses E01 by appeal to "S1 and S3 … which A5 excludes", so it relies on that stronger gloss. |
| BV-4 | F2 (ledger) | F2 is grounded in IF's quote "the record of an act and the act itself coincide". IF labels that quote an *L2 hypothesis, NOT established*. F2 does not assume coincidence (con may be undefined), so this is not a hypothesis used as an axiom. The citation is still misattributed. The real content is the modelling assumption d(a) = r(ρ) under constitution. |
| BV-5 | §8, H01 row | "L1 items are cited for scope and limits only" is inaccurate. E01 (L1) fixes B-conf's universal reading (Def 3.1) and is used in Rem 4.10. B06 (L1) supplies a time in Table 4.6. This is not a violation, because H01 is a MAY, but the compliance claim is wrong. |
| BV-6 | Def 1.9 | "The frame never equates them" contradicts F2, which sets d(a) = r(ρ) whenever con(a) = ρ. This creates a tension with H04, though not a violation of H12, because con is unobserved (O6). Prop 5.4(b)'s lower bound is conditional on con and on H-D, and §8 discloses it. Restate as: "the frame equates d and r only under con". |

## 2. Neutrality

**Faithfulness to HYPOTHESES.md**
- D-chg, D-st (with its alternative D-st⁺ in Lem 3.7), D-rec, B-chg, B-conf and B-rec are reasonable, disclosed readings of HYPOTHESES.md.
- σ is primitive rather than computed from acts. That avoids building H-D into the frame.
- O reads only (R, r, c), which is symmetric between the classes.
- F1 to F3 carry no hypothesis content.

**Is either hypothesis built into the primitives or into O?** No.

**Non-blocking neutrality issues**
- **N-1. B-chg's work disjunct is not tied to a target.** "some w ∈ W has t(w) < t" accepts work on *any* target at *any* earlier time. Once any work comes first, B-chg is almost vacuous for every target. The table says only "temporal precedence" and does not disclose that the work can be on another target. Either require τ(w) = τ or disclose the choice.
- **N-2. B-conf's universal reading comes from E01, not from HYPOTHESES.md.** E01 is L1 data; HYPOTHESES.md says "expected to conform". The reading makes H-B refutable by a single violation, while H-D has no work clause of comparable strength. This asymmetry should be stated as a choice.
- **N-3. Two clauses are left unformalized.** B-fb is unformalized, which weakens H-B; "primarily" is unformalized, which weakens D-rec. Both are disclosed and roughly symmetric, so this is acceptable.

## 3. Logic

### 3.1 Is D1 stated precisely? Mostly. These points need fixing (non-blocking)

- **P-5.** Prop 4.1 is stated for the fixed O and 𝓗. There, A1–A5, A7 and A8 are *theorems* (Lem 1.12, Def 1.8). They act as assumptions only for a generic pair (O′, 𝓗′), and the countermodels presuppose that generic form. State 4.1 for such a generic pair, as Lem 4.2 already does for (i) and (ii). Also, (ii) as written does not assert O(h_v) = O(h_c) = O(h). Its existence claim holds without A1 and A2, so the A1/A2 countermodels do not bear on it as stated. Add the O-equality.
- **P-6.** The relation ≈_X (Def 1.7) and Def 5.1 compare acts and records *across* histories. That needs a fixed universe of identifiers for acts, work events and records, which R4 does not define. R5's slots supply one; the main text should too.
- **P-7.** Cor 4.3 carries the label "D1 as a necessary condition (H5 §5.2)". It derives only "O′ depends on σ or f". It derives neither independence nor the mapping to target and time, which H5 §5.2 includes. Those appear only in the sufficiency part (S1, Def 4.9). Soften the label.

### 3.2 Is the proof valid? Step by step

| Result | Status |
|---|---|
| Lem 3.3, Prop 3.4, Lem 3.6 | valid |
| Lem 3.5 (a), (a′), (b), (b′) | valid witnesses. In (b′), B-chg also holds through the work disjunct, so (b′) adds nothing beyond (b) (M-6) |
| Lem 3.7 | valid for the base 𝓗. For variant frames it needs "no frame axiom mentions Cap" (P-4) |
| 4.1(i) | valid. h* = (σ ≡ p, f ≡ ∞) satisfies F1 vacuously and both classes. **P-1:** the step "O(h*) = O(h) by A1 and A2" turns *separate* non-dependence on σ and on f into *joint* non-dependence. That needs an intermediate history in 𝓗: h → (σ, f ≡ ∞) → (p, ∞). Such a history exists under A5. State this step |
| 4.1(i′) | valid. Adding a₀ and w₀ keeps F1–F3, and O is unchanged by A3 and A4. The empty corpus is correctly excluded |
| 4.1(ii) | valid, given the restatement in P-5 |
| 4.1(iii) | valid. Every step checks: F1 for the remaining acts and the added acts, B-chg by construction, B-conf at Vio, Ref unchanged, and O unchanged by A1, A2 and A4. 𝒜 ≠ ∅ because R_B gives a work event with an executor |
| Lem 4.2 | the main statement is valid. **P-2:** the gloss "includes an O′ that also sees … E, W and all their attributes" contradicts the hypothesis, because f is an attribute of issue acts (Def 1.4). Read literally, the last sentence of Cor 4.3 is then false: O₂ = (O, F̂) in CM-A2 is computed from the attributes of referenced acts, and it does separate the classes. Write "all attributes except f" |
| Cor 4.3 | the first sentence is valid as the contrapositive of 4.2. The second sentence needs the P-2 fix |
| Prop 4.5, Prop 4.6 | valid (see §3.4) |
| Lem 4.7 | valid. Each (o, v) is realized in H-D ∩ H-B. I checked F1, D-chg, D-st, B-chg and B-conf for v = p and for v = q |
| **Lem 4.8** | **invalid as a proof of its claim (B1, see S-1)** |
| **Remark after 4.5** | **false; the justification is invalid (B2, see S-2)** |
| Lem 5.2 | valid for the base frame. For variant frames it needs "F2 is the only axiom mentioning con or d, and no axiom ties c to con" (P-4, PA-1) |
| Prop 5.4(a), (b) | valid |
| Prop 5.4(c) | **P-3:** "independence is false in h₁, so it is not constant on the fibre" does not follow. Independence can also be false in h₂, for example when k(ρ) = α(a); in R5's universe U this holds everywhere, since 𝒜 = {x}. The conclusion, that O cannot certify independence, still follows from the falsity in h₁ alone. Delete "so it is not constant" |

### 3.3 Countermodels

Each countermodel breaks its target conclusion. I checked CM-A1, A2, A3, A4, A5, A6 and A7, and the inline A3 and A8 cases of Lem 5.2. The claim "exactly one assumption violated" does not hold everywhere:

- **C-1.** A4 ("no dependence on E or *its attributes*") contains A2 (f) and A8 (d). So **no** observable can violate A2 without also violating A4. O₂ = (O, F̂) exposes act ids, τ and v. This is harmless for (i), which does not assume A4. But the claim of one assumption per countermodel cannot hold until A4 is restricted to the attributes other than f and d.
- **C-2.** The alternative in CM-A7, O₄′ = (O, E with attributes), and R5's O₄′ both include f and d. They therefore violate A2, A4 and A8 together. The table row merges A7 and A4.
- **C-3.** CM-A7 breaks only the literal wording of (iii), "acts that no record references". In 𝓗₇, O does not see Ref (A3). An added act that *is* referenced therefore gives an O-equivalent h′ ∈ H-B in which w conforms. The O-level non-separation in the "In words" gloss of (iii) survives in 𝓗₇. A7 is needed for H5's wording "without a recorded act", not for the non-separation. Say so.
- **C-4.** The countermodels for A3 and A8 in Lem 5.2 are informal and have no ledger row; the ledger stops at "CM-A1 … CM-A7".
- **C-5 (minor).** For (ii) and (iii), the table shows ¬A1, ¬A2 and ¬A5 only through (i). The same instances work, but this should be said.

### 3.4 Are the sufficient conditions in (e) sufficient?

**Prop 4.5 (S1–S3) and Prop 4.6 (S1′–S2′) are sufficient.** Both proofs are valid. Their realizability witnesses exist: Lem 3.5(a) for S2 and Lem 3.5(b) for S2′.

**S-1 (BLOCKING, B1). Lem 4.8 ("non-circularity is needed") is not established, and as stated in the ledger it is false.** The ledger says "a Q veridical only within one class does not suffice".
- The proof of 4.5 applies S1 only to a history h′ that is supposed to be in H-B. So **S1 on H-B alone suffices** for an outcome that excludes H-B.
- *Counterexample to the lemma.* Take Q₁ from R5: σ(t(w1)) if w1 is present, otherwise ⊥. Impose veridicality as a frame axiom only on H-B. Take h = Lem 3.5(a) ∈ H-D, which gives the outcome (o, q). Any h′ ∈ H-B with the same outcome would have a violating w1, which contradicts B-conf. So the outcome excludes H-B without S3.
- The executor example Q_e ≡ p fails because it **never reports a value ∉ P**, so S2 fails. It does not isolate S3. It is also not of Prop 4.5's form (a set of (λ, v) pairs with a map μ).
- *Fix.* Restate S3 as "S1 holds on every h ∈ H-B, not merely on H-D". Prove necessity with the symmetric example R4 itself mentions: a norm-maker report that is veridical only on H-D. In that case the H-B ∖ H-D histories in the fibre may misreport, and exclusion fails. Update Def 4.9's role and Table 4.6 ("condition 2 is S1–S3") to match.

**S-2 (BLOCKING, B2). The remark "Such a Q only ever excludes H-B. It never excludes H-D, because the trivial construction of 4.1(i) keeps any Q-value equal to p" is false, and its justification is invalid.**
- *Why the justification fails.* (a) S3 is a frame axiom that mentions σ. So A5 fails and 4.1(i) does not apply. (b) If |P| ≥ 2, a history in H-B can report p′ ∈ P ∖ {p}. Setting σ* ≡ p then changes Q's value under S1.
- *Counterexample (in U).* Let Q*(h) = {(λ₁, σ(t(w1)))} ∪ {(λ₂, σ(t(w1))) : h ∉ H-D} when w1 is present, with μ(λ₁) = μ(λ₂) = w1, and Q*(h) = ∅ otherwise.
  - S1 and S3 hold, because Q* is veridical and mapped on all of 𝓗.
  - S2 holds through Lem 3.5(a), where Q* = {(λ₁, q)}.
  - For h_B = Lem 3.5(b) ∈ H-B ∖ H-D, Q*(h_B) = {(λ₁, p), (λ₂, p)}. Every history with that outcome lacks D-chg ∧ D-st, so the outcome **excludes H-D**.
- *Fix.* Delete the remark, or restrict it to a Q whose value depends only on σ at the mapped work points and on W. The restricted claim holds under A2, A4 and A7, provided O and Q do not depend on Cap, E or f. Then any h ∈ H-B can be matched in H-D by adding capable, adopted acts that no record references, and by setting the f of unadopted acts to ∞. Label the result and give its proof. The link to E01 ("H-D makes no prediction") must not rest on the unrestricted claim.

**S-3 (non-blocking).** As written, S1 already quantifies over every h, so S3 restates it. In Prop 4.6, S1′ is vacuous because Q′ is defined directly from coordinates. The real content of both is the *external* claim that an instrument realizes Q, and R4 should say this.

## 4. Performative ambiguity

**Def 5.1 is well-formed,** once cross-history identity of acts and records is fixed (P-6). It formalizes IF's concept faithfully as a property of a fibre.

**Conj 5.5 is labelled a conjecture, is falsifiable (F-a, F-b, F-c), and is kept separate from the evidence.** The evidence is marked "exploratory, one reviewer's judgement", and R5 does not test the conjecture.

**Non-blocking issues**
- **PA-1.** In the model, Lem 5.2 makes every referenced pair ambiguous, because O excludes con and no axiom ties the text c to con. So in the model even R-56, whose text states its own mode, is ambiguous. The relevant condition is "no frame axiom ties c to con" (an analogue of A5 for con), not A3. Conj 5.5's phrase "the empirical counterpart of A3" points to the wrong assumption. Fix the phrase and add that condition to Lem 5.2.
- **PA-2.** Two falsifiers are under-specified.
  - F-a refers to "the pre-registered reliability rule" without naming it. IF's rule is "κ ≥ 0.67 per pair × field"; name it and the population of records.
  - F-c needs records "whose text states its own mode", and picking those records out is itself a mode coding. Fix that set before any coding happens.

## 5. Levels

**No conjecture is presented as a proposition. No empirical observation is presented as a proposition in the strict sense.** The labelling has these errors (all non-blocking except where they carry B1 or B2):

- **L-1.** A1–A5, A7 and A8 are labelled Asm, but they are theorems for the base (O, 𝓗). Relabel them as hypotheses of the generic theorem (P-5).
- **L-2.** Rem 4.10 is labelled "Lem (remark)". That is not one of the declared labels, and it has no proof. It is interpretive.
- **L-3.** Table 4.6 is labelled Obs. It contains analysis, for example "this is exactly S1's map μ", and it applies Cor 4.3 to real corpus items under A5.
- **L-4.** The §8 rows are labelled Lem, but they are compliance assertions without proofs, and the H01 row is inaccurate (BV-5). **Finite checks presented as support:** the H10 and H12 rows cite K11.1 and K11.2, which are R5 checks that have not been run. The properties they support follow directly from Def 1.8; cite that instead. No proposition anywhere rests on a finite check. Lem 3.5 and Lem 4.7 are legitimate existence constructions.
- **L-5. Statements with no label:**
  - the remark after Prop 4.5 (B2);
  - the note after Prop 4.6;
  - "Ad is monotone" in Def 2.2;
  - the claims about F2 inside Def 1.9;
  - the "In words" gloss of 4.1;
  - the Lem 5.2 countermodels;
  - the closing paragraph of Prop 5.4, which applies the model to "the corpus".
- **L-6.** O6 overstates its source (BV-1). The ledger row "IF-obs" has no matching statement in the body.
- **L-7.** The ledger statement for Lem 4.8 is false (B1).

## 6. Minimality

All primitives are used:
- k: Def 4.9;
- Σ and c: O;
- prep and E^adp: Adopted;
- d: F2 and Adopted;
- con: §5;
- P: B-conf.

**Unused or unneeded**
- **M-1.** §2 (Def 2.1, Def 2.2, Lem 2.3) is used by no proof, no result and no R5 check.
- **M-2.** F3 repeats the typing already stated in Def 1.4.
- **M-3.** A4 contains A2 and A8 (C-1), so A2 is redundant in (i′) and (iii).
- **M-4.** The second sentence of A5 follows from the first.
- **M-5.** S3 and S1′ are redundant as written (S-3).
- **M-6.** Lem 3.5(b′) adds nothing beyond (b).
- **M-7.** "𝓗 ∩ R_B" is redundant, since R_B ⊆ 𝓗. Qc and K9.3 follow trivially from K4, because Qc is a function of O.

**Missing.** Variant frames need explicit side conditions:
- for Lem 3.7: no axiom on Cap;
- for Lem 5.2: no axiom on con or d other than F2, and no c–con tie.

(P-4.)

## 7. R5 specification

I checked these expected outcomes by hand in U, and all of them are correct: K1–K7, K8.1 (including that the difference is exactly σ = (q, q)), K8.2–K8.7, K9.1–K9.4 and K10.1–K10.5. The raw enumeration size is about 4.5 × 10⁶, which is consistent with "a few million". The spec still has gaps an independent implementer would hit:

- **R-1.** "Report the first counterexample" does not fix an enumeration order.
- **R-2.** K8.2 does not say which (r, c) "the value" has. It should say "for every (r, c)".
- **R-3.** K8.1's exact characterization cannot be derived from the text alone, because CM-A1 uses T = {0}. The derivation should be added: σ = (q, p) lies in O₁(H-B) through an act with value p entering into force at 1, and σ = (p, q) lies there through work at 0.
- **R-4.** K10.1 runs 𝓒 = H-D for every h ∈ H-D. Lem 5.2 states the H-D case only for h ∈ R_B. The outcome is true (only D-rec is needed), but it cannot be derived from the lemma as stated.
- **R-5.** O₄′ includes f and d (C-2). The failure in K8.7 comes only from the literal "unreferenced" clause (C-3).
- **R-6. Missing checks:**
  - that veridicality on H-B suffices (would expose B1);
  - the counterexample Q* (would expose B2);
  - Prop 4.6 directly;
  - Def 4.9 and Prop 5.4(c). These cannot be tested in U at all: with 𝒜 = {x}, no record is ever independent. State this.
- **R-7.** The quantifiers in K10.3 are unclear: which class, and for which slots? K4's phrase "run this for O, and as O_¬σf(h*) = O_¬σf(h)" is ambiguous, because the condition "differs at most in σ and f" already implies both.
- **R-8.** K9.3 with Qc is trivial (M-7).

## 8. Required to pass

1. Fix B1: restate S3 as veridicality on H-B, correct Lem 4.8, and update Table 4.6 and Def 4.9's role.
2. Fix B2: delete or restrict the remark after Prop 4.5, label it, and prove it.
3. Recommended in the same revision:
   - restate Prop 4.1 for a generic (O′, 𝓗′), add O-equality to (ii), and spell out the step to joint non-dependence (P-5, P-1);
   - restrict A4 to attributes other than f and d (C-1, M-3);
   - fix the gloss of Lem 4.2 and Cor 4.3 (P-2) and the step in Prop 5.4(c) (P-3);
   - fix the labels (L-1 to L-6);
   - fix the R5 gaps (R-1 to R-7).
