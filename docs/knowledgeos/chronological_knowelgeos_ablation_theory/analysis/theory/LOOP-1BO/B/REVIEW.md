# Review of FORMAL-THEORY-R4 r3 (frozen)

**Reviewed file:** `FORMAL-THEORY-R4.md` (r3; its title still reads "draft r2"; see NB-7)
**Compared against:** `FORMAL-THEORY-R4-r2.md`, `V1A-LEMMA.md` (r1), `V1-RECONCILIATION.md`, `CHANGELOG-r3.md`
**Method:** I read both versions in full. I checked every [r3] sentence and every row of Table 9.4 against the V1A statement it cites. I compared §§1–8 with r2 passage by passage. Only files in this directory were used, and no command was run.

## Verdict: **PASS_WITH_LIMITATIONS**

I found no blocking issue. §9 states Lemma M, the reading classification and the V1b results as V1A and V1-REC establish them. No finite result is presented as a general one. The weak form (Prop 9.6) is limited to the tested readings and proved by citation, and the V1b confirmation is kept apart from the proof. Apart from the five listed TASK items, I found no change in §§1–8. The limitations below are all non-blocking.

---

## 1. Fidelity of §9

### 1.1 Lemma M (Def 9.1, Lem 9.2, Cor 9.3)
- **Def 9.1** matches V1A Def V1.1, V1.2 and V2.1–V2.4. This covers reading classes, the record base, quiescence, RB1/RB2, M_p, M_p^W, M_q^D, N_q, "has M" (RB1 ∧ RB2 ∧ M_p for some p), act-explained and T1/T2. The S-form and O-form definitions are new, but they only name V1A's usage. ✔
- **Lem 9.2** (o), (i), (ii-c), (ii-v) and (iii) match V1A Lem V2.5 and V2.6, including the hypothesis lists. (ii) assumes RB1 and RB2 and has its "exactly the corresponding half" clause. (iii) needs T only and holds for every D-side reading. The proof note about where 𝒜 ≠ ∅ comes from is faithful to V1A. ✔
- **Cor 9.3.** The content is correct. Two citation issues (NB-4):
  - The generic first clause ("for every pair satisfying A1, A2, A5") is V1A Lem V2.6(i), not Cor V2.8. Cor V2.8 covers only O_¬σf and the Cor 4.3 analogue.
  - The (ii) clause is a small new inference from Lem V2.6(ii). It is valid: a referenced w gives W ≠ ∅, M_p implies M_p^W, and RB1 and RB2 are part of M. But it contradicts the §9 preamble's "proves nothing anew".

### 1.2 Reading table (Table 9.4)
I checked every row against V1A Table V3.0 and the lemma cited in the row:

| Reading | M | (i) | (ii) | (iii) | Agrees with V1A |
|---|---|---|---|---|---|
| R-a, R-b, R-e, R-f | M | holds | holds | holds | ✔ (V3.1, V3.2, V3.5, V3.8) |
| R-c | not-M | fails gen.; holds with A4 and A7 | holds | holds | ✔ (V3.3, V3.4) |
| R-d | not-M | fails gen.; SC1, SC2, ≈σ,f,Cap | fails gen.; SC1, SC2 | holds | ✔ (V3.6, V3.7) |
| R-g / R-g⁺ | not-M | fails gen. (O_¬σf; O_Cap middle equality); base pair with N ≥ 1, P ≠ V; inconsistent at N = 0 | h_v holds; h_c fails in S-form, holds in O-form for the base pair | fails | ✔ (V3.9, V3.10, V3.17) |
| R-h | M, not T | holds | holds | literal fails at I₇; holds with referenced acts and A3 | ✔ (V3.11, V3.12) |
| R-i / R-i′ | not-M (RB2) | fails gen.; base pair fails exactly at o = ∅ | S-form fails; O-form under A1, A2, A3, A5, A6, A7 (+A4 for R-i′) | holds | ✔ (V3.13, V3.14) |
| R-j | not-M | fails gen.; base pair with N ≥ 1, P ≠ V; inconsistent at N = 0 | h_v fails for every pair | holds | ✔, but the h_c half is not stated (NB-5) |
| R-k | M, not T; REJECTED | holds | holds | literal fails at I₇ | ✔ (V3.18, Rem V3.18a) |

I also checked the closing sentence, both parts:
- "Every M-reading also satisfies M_q^D and N_q" is true.
- "Every reading other than R-g, R-g⁺, R-h and R-k satisfies T" is true.

The HYP licences are faithful to V1A Def V1.3, including the disclosed caveats: R-c's asymmetry, R-e's strained derivation, R-g's contested licence and R-k's weak licence.

### 1.3 V1b results (Rem 9.5)
These are faithful to V1-REC:
- the integrity items (seals, isolation, re-run, U and U₅ sizes);
- all 8 controls VALID, with R-h, R-i and R-i′ VALID only after the blind supplementary evaluation;
- the spec-extraction error;
- every scorable prediction CONFIRMED, with 0 minor deviations;
- P1.7 as the only falsified prediction, identified as wrong in advance;
- the unscorable items;
- the scope caveat: U, one target, Claude-family, predictions not independent.

r3 reconciles V1-REC's "8 non-M readings" as the seven not-M readings plus R-h. This is correct and disclosed: V1-REC's own list of readings without M includes R-h for (iii). The per-reading "scorable predictions CONFIRMED" entries follow logically from V1-REC's global statement. r3 correctly declines to map P-numbers to readings.

The section is labelled Rem, and the text states that no Lem, Prop or Cor rests on it. ✔

### 1.4 Overstatement checks
- **"Proved for all readings":** not found. Every scope statement names the readings.
- **Finite results presented as general:** not found. Prop 9.6 is proved from V1A lemmas, and the V1b result is labelled "finite confirmation … not part of the proof". Rem 9.5 explicitly says that a finite result in U does not establish a generic statement.
- **Weak form beyond the tested readings:** not found.
  - Prop 9.6's scope is the base pair (O, 𝓗), with N ≥ 1, P ≠ V and o ≠ ∅, over the tested set 𝕋. 𝕋 excludes R-a (untested) and R-k (rejected).
  - The R-j exception to (b) is stated and correct (V3.15(d)).
  - The R-i/R-i′ failure at o = ∅ is stated.
  - I verified the proof reading by reading. One small step is new, at the h_c half for R-g/R-g⁺: "(a) applied to O(h), nonempty because w is referenced". It is valid, and V1A Lem V3.10(b) states the same conclusion.
- **Remaining soft overstatements:** these are non-blocking and are listed as NB-1 to NB-3.

## 2. Scope qualifiers (Prop 4.1, Lem 4.2, Cor 4.3)
- **Prop 4.1 (line 305).**
  - The sentence is correct and suitably cautious. It requires M plus Lem 9.2's further condition for each part, and it names R-h and R-k as the exceptions for (iii). It says (i′) is classified only for R-i/R-i′, and lists the failing readings "part by part as in Table 9.4".
  - The list of failing readings is correct: **R-c, R-d, R-g, R-g⁺, R-i, R-i′, R-j**, plus R-h for (iii)'s literal wording.
  - Minor point: for (iii) the sufficient condition is T alone; M is not needed. The sentence's "M and T" is sufficient, so it is not false. But it hides the fact that (iii) holds under the not-M readings R-c, R-d, R-i, R-i′ and R-j. Table 9.4 shows this.
- **Lem 4.2 (line 318).** Correct. Each failing reading's O_¬σf failure is backed by the cited V1A item: Rem V3.4a for R-c, V3.6(c) for R-d, V3.9(c) for R-g, V3.17 for R-g⁺, V3.13(b) with Rem V3.14a for R-i, and V3.15(b) for R-j. For R-c and R-i the backing is a V1A *Rem*, not a Lem, although the argument given there is complete.
- **Cor 4.3 (line 322).** Correct. Each separating observation reads neither σ nor f, and each separates H-D^𝐑 ∩ R_B^𝐑 from H-B^𝐑 on 𝓗, which is Cor 4.3's form:
  - O₄ for R-c (V3.3(c); here H-D ∩ R_B^c = H-D);
  - O_¬σf for R-d (V3.6(d)), R-g (V3.9(c)), R-g⁺ (V3.17) and R-j (V3.16(c));
  - O₃ for R-i and R-i′ (V3.13(d)).

  The decision not to cite O_Cap is correct, because V3.9(d) concerns the middle equality.

## 3. No proof or other change (§§1–8)
I compared r2 and r3 passage by passage for these sections:
- the header and Labels;
- Rem 0.1 and Defs 1.1–1.12, including the O1–O9 table;
- §2;
- Def 3.1, Rem 3.1a–c, Def 3.2 and Lem 3.3–3.7;
- §4.1, Lem 4.0a and 4.0b;
- Prop 4.1, Cor 4.1b, Rem 4.1a, Lem 4.2 and Cor 4.3;
- all §4.3 proofs, the §4.4 countermodels, and I₁ and I₇;
- §4.5, §4.6 and Rem 4.10;
- all of §5, including Conj 5.5 and F-a to F-c;
- every §6 ledger row;
- all of §7 (U, the observations, Q-table, frame variants and K1–K12);
- every §8 row.

Apart from the listed edits, the texts are identical. These are the edits:
- the Status line (item 5);
- the three *[r3] Scope.* sentences (item 2);
- the ledger legend sentence and eight new rows (item 3);
- the H01 F-range (item 4);
- §9 (item 1).

**Unlisted changes: none.** CHANGELOG-r3 discloses the things deliberately left unchanged: the title, the Revision and Inputs rows, the un-re-marked ledger rows, and the "F15 R2 pending" wording.

## 4. §8
H01 now reads "F01–F08, F11, F13–F16". It says F09, F10 and F12 are QUARANTINED and not cited. I read the full r3 text, and F09, F10 and F12 do not occur anywhere else. The other F-citations are F01–F06 in Table 4.6, F08, F11, F13, F14 and F15, all outside the quarantined set. **s8_ok = true.**

## 5. Labels
Every new numbered statement has a label:
- Def 9.1 (Def);
- Lem 9.2 (Lem);
- Cor 9.3 (Cor);
- Table 9.4 (Rem);
- Rem 9.5 (Rem);
- Prop 9.6 (Prop);
- Rem 9.7 (Rem);
- Rem 9.8 (Rem).

Each also has an [r3] ledger row. **Exception (NB-6):** the three *[r3] Scope.* sentences after Prop 4.1, Lem 4.2 and Cor 4.3 carry no label and have no ledger row. They make formal holds/fails claims about other readings, so they are new statements. The legend explains why the host rows are not re-marked, but the document's own rule says every statement carries exactly one label. Labelling them Rem, with each one citing Table 9.4 and Lem 9.2, would fix this.

---

## Non-blocking issues
- **NB-1 (header status).** "reviewed draft r3" is a listed edit, taken verbatim from TASK §5. But CHANGELOG-r3 states that the r3 additions had not been reviewed. The new line also drops r2's disclosure "Written by one same-family (Claude) analyst". The line becomes accurate only once this review is recorded. The same-family disclosure should be restored somewhere in the header.
- **NB-2 (V1A's review status).** §9's preamble cites V1A as "r1, reviewed". V1A's own header says "r1 is not re-reviewed". V1-REC says "2 review rounds, PASS_WITH_LIMITATIONS". §9 should carry the qualifier PASS_WITH_LIMITATIONS rather than a bare "reviewed".
- **NB-3 (Rem 9.7 wording).**
  - (a) "(i) and (ii) are recovered **only** under SC1 and SC2". V1A shows that SC1 and SC2 are *sufficient* and that the generic forms fail without them. It does not show that SC1 and SC2 are necessary.
  - (b) "Prop 9.6 depends on Obs O5". Formally, Prop 9.6's proof uses SC2 for O, which holds by Lem 1.12 (a theorem), not by Obs O5. What depends on O5 is the corpus *relevance* of using a Cap-blind O. As written, the sentence suggests a Prop resting on an Obs. A better wording is "its application to the corpus depends on O5".
- **NB-4 (Cor 9.3 citations).**
  - The generic clause should cite V1A Lem V2.6(i), not only Cor V2.8.
  - The (ii) clause is a new, correct inference, as are Prop 9.6's small steps (the RB2 ⇒ no ∅ argument, and R-g's h_c via (a)). So the §9 preamble's "proves nothing anew" is slightly inaccurate. It should say that the new steps are elementary and are given in full.
- **NB-5 (Table 9.4, R-j row).** The (ii) cell gives only the h_v half. V1A Table V3.0 records that ii-c also fails for R-j (M_p^W fails, so the S-form fails). By V3.16(a), the O-form of h_c holds for the base pair. The cell should state both.
- **NB-6 (unlabelled scope sentences).** See §5 above.
- **NB-7 (stale header).** The title still says "(draft r2)", and the Revision and Inputs rows describe r2. They do not list V1A, V1-REC or R3-FROZEN. This is disclosed in the changelog and was out of TASK scope. It should be fixed in the next permitted edit.
- **NB-8 ("not finite-verified", Rem 9.7).** V1-REC makes only the recovery under SC1 and SC2 (K4b and the ≈σ,f,Cap variants) unscorable. The O_¬σf separation under R-d may be covered by R-d's "scorable predictions CONFIRMED". The inputs do not settle this. The sentence should restrict "not finite-verified" to the recovery half.
- **NB-9 (Prop 4.1 scope sentence, (iii)).** T alone suffices for (iii) (Lem 9.2(iii)). One could add "and (iii) holds under every reading with T, including R-c, R-d, R-i, R-i′ and R-j".
