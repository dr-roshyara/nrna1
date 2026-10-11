# R7 · O3 proposal — PROPOSED; requests a Human ARB act on E3; draft r1.2

**Status:** PROPOSED, draft r1.2, 2026-10-11. Review history:
- r0, round 1 (fresh independent review): PASS_WITH_LIMITATIONS, no blocker; M1–M5 material, m1–m11 minor. r1 applies them (§11).
- r1, round 2 (fresh independent review): PASS_WITH_LIMITATIONS, no blocker; F1 material, F2–F9 minor. r1.1 applies them (§11).
- r1.1, round 3 (fresh independent review): PASS_WITH_LIMITATIONS, nothing blocking; wording findings R1–R4. r1.2 applies them (§11). **Round 3 PWL; r1.2 fixes not re-reviewed.**

Prepared under F-LOG-0280, which authorized "the **preparation** of an O3 proposal … under the binding conditions of `R7/O3-PREPARATION-AUTHORIZATION-PACKAGE.md` r1.1 §3", together with its review. **It decides nothing, derives no architecture and authorizes nothing.** It asks the Human ARB for one separate act on gate **E3** (`R7/ARB-DECISION-PACKAGE.md` r4.4 §E3). F-LOG-0280: "This authorizes preparation and review only. It does not authorize E3."

**Review regime (F-LOG-0280, verbatim):** "The review is performed by fresh independent `kos-theory-reviewer` subagents, one new reviewer per round. Stop rule: a FAIL at round 3 → human; otherwise PASS / PASS_WITH_LIMITATIONS with nothing blocking → present."

**Inputs** (T = `docs/knowledgeos/chronological_knowelgeos_ablation_theory/analysis/theory/`):
- `F-GOVERNANCE-LOG.md`: F-LOG-0248, F-LOG-0253 to F-LOG-0280;
- `T/R7/O3-PREPARATION-AUTHORIZATION-PACKAGE.md` r1.1 (§1d, §3, §4, §5);
- `T/R7/E2b-ADOPTION-TEXT.md` r1.2 (the adopted model; §A1 = Amendment 1);
- `T/R7/T3-CANDIDATE-MODEL.md` r1.8. Pinned trace sources: **r1.5 §2** for the 25 baseline elements (commit 7bccae4d8) and **r1.7 §2** for CB1 / CB23 (commit 6e0407db7);
- `T/R7/T2-RULE-CANDIDATES.md` r1.4; `T/R7/T1-CONCEPT-CANDIDATES.md` r1.6; `T/FORMAL-THEORY-R4.md` r3.1;
- `T/R7/VP-DECISION-REGISTER.md` r1.19 (§0, §3); `T/R7/ARB-DECISION-PACKAGE.md` r4.4 (§B, §C, §E3, §E3b, §F);
- `T/R7/E3-PREREQ-VP-DECISION-PACKAGE.md` r4.1 (§6); `T/R7-AUTHORIZATION-BRIEF.md` r3 (Λ1–Λ7).

**Tags:** text in quotation marks is verbatim from the cited source. **[E]** marks an analyst reading. Nothing here recommends an option. Outside verbatim quotations (which may contain such terms, e.g. "bounded-context-like", "aggregate-like clusters", B11), every DDD term (context, aggregate, value object, event, port) appears **only inside a question that E3 would answer**, and every such question is labelled [E].

**Standing qualifier (applies to every statement here):** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

---

## 1. Request

### 1a. What E3 would authorize (ARB r4.4 §E3, verbatim)

| field | ARB r4.4 §E3 text |
|---|---|
| scope | "derive an architecture proposal from the adopted bounded model only. **Relation to register O3:** O3 is the *preparation* of the authorization proposal for this gate; E3 is the gate itself (the derivation). Both use the same minimal VP set" |
| prerequisites | "E1; E2b (O2 adoption); E2 for VP10, VP12, VP11 (beyond E2b's set); a prepared authorization proposal (O3) **[r4.4 sync] For VP10: met by the re-affirmed (iii) (F-LOG-0263), ruled to count as VP10's decision for O3 (not on evidence; F-LOG-0264). Other prerequisites unchanged; this annotation authorizes nothing.**" |
| exclusions | "human-decided VP1a–d values as active optional policies, labelled "human-decided policy, not derived from research phases R1–R6" with their H-D / H-B label; undecided VP1a–d, and undecided VP2, VP13, as inactive slots (VP2, VP13 decided under E2: carried with their decision label); never invariants (N1, N2, N5); VP3, VP6, VP16, VP9 as evidence gaps unless they entered E2b under its condition (a) or (b), and then carried with that label; VP4, VP7, VP8 stated as unsupported unless decided under E2 (then carried with their decision label); no canonical terms (R8); no implementation; no generality claim" |
| acceptance criteria | "every architecture element traces to T3 elements and their §2 rows; standing qualifier carried; independent review [r4.4 sync] Recorded from F-LOG-0271 (human, verbatim): "Any O3/E3 proposal must present the relevant results under all three interpretations: S, S′ and S″. It must not silently privilege one interpretation or present the asymmetry as an accepted property of the cut."" |
| unresolved risks | "one-corpus primitives (VP5); low reversibility of VP15; R23 (nothing enforceable from records)" |
| permits | "an architecture **proposal** for human review; nothing built; the proposal is not accepted by this gate (see E3b)" |

**What E3 is asked to derive, by the register (r1.19 §3, O3 note):** "Boundaries, participant placement and groupings are what an architecture proposal would map onto." Register §3 names **only** these three; ARB §E3 names none ("derive an architecture proposal from the adopted bounded model only"). **[E] note:** package r1.1 §5 attributes this naming to "ARB §E3 and register §3"; the ARB §E3 text does not bear that out. Per the Authorize text (§9), package r1.1 §3 governs, not §5. Anything else (values, occurrences) is [E], and ports appear only inside a labelled question: Q7 [E], UNANCHORED (§5).

**Not requested:** E3b (acceptance of the architecture proposal), E4 / R8 (canonicalization), E5 (implementation), any evidence collection (V2, V5, V6, E-A5), any VP decision, any spanning-rule selection, any acceptance of the asymmetry, and any amendment of E2b, T3, the register or the ARB package.

### 1b. Prerequisite status (ARB §E3)

| prerequisite (verbatim item) | status | log ids |
|---|---|---|
| "E1" | MET | F-LOG-0253 |
| "E2b (O2 adoption)" | MET; amended | F-LOG-0261; Amendment 1 F-LOG-0275, recorded F-LOG-0276 (E2b r1.2; review PASS_WITH_LIMITATIONS; K1–K4 not re-reviewed) |
| "E2 for VP10, VP12, VP11 (beyond E2b's set)" | MET. **VP10 by human ruling, not on evidence** | VP10: F-LOG-0263, F-LOG-0264 (synced F-LOG-0277/0278). VP12: F-LOG-0268 (re-check F-LOG-0270; disposition F-LOG-0271). VP11: F-LOG-0269 (effect in E2b by F-LOG-0275 Act II) |
| "a prepared authorization proposal (O3)" | **this document**. MET only once it is reviewed under the F-LOG-0280 regime and presented | F-LOG-0280 |

**Register r1.19 §3 O3 minimal set** ("VP15, VP5, VP14, VP10, VP12, VP11"):

| VP | status | log id |
|---|---|---|
| VP15 | DECIDED (i) | F-LOG-0255 (supplement F-LOG-0256) |
| VP5 | DECIDED (i) | F-LOG-0257 |
| VP14 | DECIDED P-c (reopened; supersedes F-LOG-0259) | F-LOG-0265; T3 re-derived F-LOG-0266/0267 |
| VP10 | (iii) re-affirmed; counts for O3 by ruling. F-LOG-0264: "It was not resolved by evidence." | F-LOG-0263, F-LOG-0264 |
| VP12 | DECIDED (i) | F-LOG-0268 |
| VP11 | DECIDED (ii) | F-LOG-0269 |

**Other recorded conditions:** F-LOG-0268 "Re-check and document the H-D asymmetry before E3 proceeds" was satisfied by F-LOG-0270 + F-LOG-0271. F-LOG-0261 "prepare an E3 proposal and have it reviewed": open until this document is reviewed. The open items (b), (c) and (d) were closed by F-LOG-0278.

**For the human (not prerequisites by the log, see §6):**
- **A1:** the VP4 / VP8 edges, and ARB §C row 7, which lists VP4 as "needed for" O3. Against this, register r1.19 §3, O3 row, column "may stay open": "VP4, VP7, VP8 (stated as unsupported)".
- **A5:** E2b §A1.9b can be read as requiring (d′) and R11 before any O3/E3 proposal.

---

## 2. The adopted model E3 would start from

**Adopted (E2b r1.2 §A1.2, verbatim):** "**Element list (27):** CB1, CB23; the participant; CG1–CG6; CV1–CV9, CV-text; CE1–CE6; CS1; MW1. **CB4: explicit open slot**, not an element and not a side. No element is removed. Label of every adopted element, including CB1 and CB23: "[E] bounded model element, one corpus"."

**Trace basis (E2b §A1.2, verbatim):** "The 25 baseline elements keep their **T3 r1.5** §2 traces (unchanged in T3 r1.6, r1.7). CB1 and CB23 trace to **T3 r1.7** §2 (pinned; see the T3 version note above)."

**Verification [E]:** the analyst compared the §2 rows in git. The 25 baseline rows in T3 r1.8 are byte-identical to r1.5 (7bccae4d8). The CB1 and CB23 §2 rows in r1.8 are byte-identical to r1.7 (6e0407db7).

**Provenance chain [E]:**
- **The 25 baseline elements:** E2b r1.2 → T3 r1.5 §2 row → T2 r1.4 ids → T1 r1.6 ids → R4 r3.1 / FROZEN R3 ids, with the row's inherited assumptions and reading sensitivity.
- **CB1 and CB23:** E2b r1.2 → T3 r1.7 §2 row, **restricted** to its T1 ids and its R4 / FROZEN R3 ids not keyed to hosting. Its T2-ids and reading-sensitivity cells are not adopted. Its evidence-class, inherited-assumptions and open-questions cells are outside the adopted restriction ("restricted to the T1 ids and the R4 / FROZEN R3 ids").

Applying the F-LOG-0248 chain ("concept → R4 statement or R3 item → inherited assumptions (§4) → reading sensitivity") to O3 is an analyst reading (package B1). **For CB1 / CB23 this chain cannot be completed from the adopted record:** only its first link, concept → R4 / R3, is adopted. See §6, item **A2′**.

**Version note [E]:**
- T3 r1.5 states it "Builds **only** on `R7/T1-CONCEPT-CANDIDATES.md` r1.4 … and `R7/T2-RULE-CANDIDATES.md` r1.4".
- T1 is now r1.6. Its r1.5 changes are X25, X7 and the C7 gloss; its r1.6 change is X25 (F-LOG-0254).
- T3 r1.4 was accepted together with T1 r1.5 (F-LOG-0253). No record flags a T1 r1.5/r1.6 change as breaking a T3 §2 row. The analyst did not re-verify every chain.

### 2a. The 25 baseline elements (T3 r1.5 §2; columns quoted verbatim)

The remaining columns of each row (evidence class, inherited assumptions, reading sensitivity, open questions) apply in full by reference to T3 r1.5 §2. Package B1: "For these only, the full T3 r1.5 §2 row applies".

| element | T1 ids | T2 ids (tier) | R4 / FROZEN R3 ids |
|---|---|---|---|
| participant [r1] | C1 | R5, R6, R8 (typing of α, α_W, k), R22 (actor ids) (T-A) | Def 1.2, 1.4–1.6, 1.7a; Obs O8, O9; Lem 1.12; A05, A06 (role names in texts only) |
| CG1 | C1, C2 | R10, R22 (T-A) | Def 1.2, 1.4–1.6, 1.7a; Obs O5, O8, O9; Lem 1.12; G07, H19 |
| CG2 | C3, C5, C6, C7 | R11, R20, R1 (T-A); R25, R26, R30, R31, R37, R38 (T-B slot) | Def 1.1, 1.3, 1.10; Obs O4; Rem 0.1; Rem 9.8; A01, A10, B07, D04, F06, H14, D16 |
| CG3 | C8–C12 | R1, R2, R3, R4, R5, R14, R15, R19 (T-A); R27, R28 (T-B slot) | Def 1.4, 1.8, 1.9, 1.10; Obs O2, O3; Lem 3.7; A05–A08, B11, F07, F08, F17, G05, H04, H12 |
| CG4 | C13–C18 | R2, R3, R7, R8, R9, R21, R22 (T-A); R39 (T-B slot) | Def 1.6, 1.8, 1.11; Rem 1.11a; Obs O1, O6, O7; A02, A03, A05–A07, F02, F13, F14, F17, G01, B11 |
| CG5 | C19, C20 | R6, R15, R18 (T-A) | Def 1.5, 1.9; Obs O8; B06, A03, G05 |
| CG6 | C24 | R15 (T-A, guard) | Obs O8; B06, A11, A15, G03 |
| CV1 | C4 | R5, R18 (T-A) | Def 1.1; A6 |
| CV2 | C5 | R1, R5 (T-A) | Def 1.3; Rem 0.1 |
| CV3 | C22 (C11, C12, C14, C20) | R3, R14, R15 (T-A) | Def 1.9; Rem 1.9a; A07, G05, H04, H12 |
| CV4 | C18, C27 | R2, R9, R24 (T-A); R39 (T-B slot) | Def 1.6, 5.1; F2 (Asm); Lem 5.2; Rem 5.2a; Cor 5.3; Obs O6; F17, B11, G04 |
| CV5 | C10 | R19 (T-A); R27, R28 (T-B slot) | Def 1.4, 1.10; Lem 3.7; A05, A06, F07 |
| CV6 | C21 | R18 (T-A); R32, R33, R34 (T-B slot) | Def 1.10; Rem 3.1a; Rem 4.10; E01 (recorded bearing) |
| CV7 | C23 | R16 (T-A, guard) | Def 1.8; A7; Obs O9; F08, F11, F13, G01, H11 |
| CV8 | C25 | R70 (T-D meta); R62 (T-C, excluded) | Def 4.9; Rem 4.9a; Prop 5.4(c); Rem 5.4a; D04, F06, H14 |
| CV9 | C28 | R74, R82 (T-D); R23 (T-A meta, via MW1) | Lem 4.2; D16 (L3 formal pointer); F17 |
| CV-text | C15 | R21 (T-A) | Def 1.6; Rem 1.11a; A5; A02, A05, A06 |
| CE1 | C8, C11 | R5 (T-A); R25, R26, R38 (T-B slot) | Def 1.4, 1.9; A05, A06, A08, F17 |
| CE2 | C9, C11 | R4, R19 (T-A); R27, R28 (T-B slot) | Def 1.4, 1.10; A05, A08 |
| CE3 | C12 | R1, R14 (T-A); R27, R28 (T-B slot) | Def 1.4, 1.8 (F1), 1.9; Obs O3; G05, H12 |
| CE4 | C7 | R20 (T-A); R25, R26, R30, R31, R37, R38 (T-B slot) | Def 1.10 (Chg) |
| CE5 | C19, C20 | R6 (T-A); R32–R34 (T-B slot) | Def 1.5; Obs O8; B06, G05 |
| CE6 | C13, C14 | R7 (T-A) | Def 1.6, 1.9, 1.11; Obs O1; A07, G01 |
| CS1 | C26 | R66–R69, R82 (T-D meta) | Def 4.4; Props 4.5, 4.6; Rem 4.5b, 4.6a; Lem 4.7; Cor 4.3; F01–F03 |
| MW1 | all C-rows | R23, R12, R13 (T-A meta / implications) | Lem 1.12; Lem 3.3, 3.5, 3.6; Prop 3.4; Def 3.2; G06, H07 |

T-B slots in the T2 column are **inactive** (B4). R62 is excluded (T-C). T-D rows are open questions.

### 2b. CB1 and CB23: the restricted trace (B1)

**Act I (1), verbatim (F-LOG-0275):** "The traces are restricted to the T1 ids and the R4 / FROZEN R3 ids not keyed to hosting. Their T2 hosts per spanning reading are recorded as a report of T3 [E], not ratified, and no reading is selected. The stale T3 r1.7 CB1 / CB23 cells are not adopted."

| element | T1 ids (T3 r1.7 §2) | R4 / FROZEN R3 ids (T3 r1.7 §2 cell, verbatim **except that** the clause in the next column is omitted, because it is excluded from the adopted trace) | excluded clause (E2b §A1.2, verbatim) | T2 hosts |
|---|---|---|---|---|
| CB1 records side | C13–C18, C23, C24, C27 | "Def 1.6, 1.8, 1.11; Rem 1.11a; Obs O1, O6, O7, O9; Lem 1.12; Lem 5.2; Cor 5.3; A02, A03, A05–A07, A11, A15, B06, B09 (caveat), B11, F02, F11, F13, F14, F17, G01, G03, G04, H11 (as r1.5)" | "for the shared guards R14, R15: Def 1.9; G05, H04, H12 (as CB23)" | **only** the per-reading report, E2b §A1.3 / §A1.4 (§3 below) |
| CB23 norm-and-work side | C2–C12, C19, C20, C21 | "Def 1.1–1.5, 1.8–1.10; Rem 0.1; Rem 1.9a; Rem 3.1a; Rem 4.10; Obs O2–O5, O8; Lem 3.7; Rem 9.7, 9.8; A01, A03, A05–A08, A10, B06, B07, D04, E01 (recorded bearing), F06–F08, F17, G05, G07, H04, H10, H12, H14, H19 (union of r1.5 CB2 and CB3)" | "for the shared rows R8, R9, R16, R21, R22, as CB1" | as above |

**Not adopted (E2b §A1.2):** "The §1a CB1 and CB23 T-A / T-B cells, the §2 CB1 and CB23 "T2 ids" cells, the hosting-keyed R4 clauses (G6) and the §2 "reading sensitivity" cells." Nor, as constraints: "the P-c "Relations between boundaries" table and the 83-row K-c crossing table; they are carried as T3 [E] analysis."

**G6 disclosure (E2b §A1.2, verbatim):** "the CB1 cell carries no R4 ids for R5, R6, R10, R19, R25 and R38, although these rows cross under S." Fixing this is open item (d′).

**Stated exceptions carried (E2b §A1.2):**
- "C1 spans CB1 and CB23";
- "C22 is unplaced in the CB layout; CV3, under which it appears, straddles the cut";
- "in the adopted model, CV8, CV9 and CS1 stay adopted and ungrouped" (F-LOG-0263);
- "CG1 may straddle the P-c cut". T3 r1.8 §4: "**CG1 straddles the P-c cut through C1** … it is the only grouping that does."

**The participant (E2b §A1.2a):** "It is R4's single actor set 𝒜 (Def 1.2) and **spans CB1 and CB23**. "No additional role-separation assumption is introduced by this act" (F-LOG-0268, appended statement)."

---

## 3. Results under S, S′ and S″ (B2, B14)

**The readings (E2b §A1.9a, verbatim):** "S (C1 counts on both sides), S′ (on neither side), S″ (where the statement locates the actor). All are [E] (T3 r1.7 rule 2a)". **No reading is selected** (F-LOG-0271). **None is privileged here:** they are listed in the source order, which is not a ranking. T3 r1.7 [r1.7 review fix M3]: S is "reported first … **not because T1, T2 or R4 support it over the alternatives.**"

**S″ is reported as counting results only (B14 [E]).** It introduces no role concept and decides no VP4.

### 3a. Reading-dependent results relevant to E3 (sources: E2b §A1.3, §A1.4, §A1.9a; T3 r1.7/r1.8 §1a)

| result | S | S′ | S″ (counting only) |
|---|---|---|---|
| crossing totals, 83 T2 rows (cross / int. CB23 / int. CB1 / CB4 touch / none) | 46 / 29 / 1 / 3 / 4 (range 44–52 over the borderlines) | 36 / 38 / 2 / 3 / 4 | 36 / 38 / 2 / 3 / 4 (identical to S′) |
| T-A crossing (E2b §A1.3) | 17: R2, R3, **R5, R6**, R8, R9, **R10**, R14, R15, R16, **R19**, R21, R22; R12, R13, R23 at MW1; R24 at CV4 | 12: R2, R3, R9, R14, R15, R16, R21, R22; R12, R13, R23 at MW1; R24 at CV4 | as S′ |
| T-A internal CB1 / CB23 | R7 / R1, R4, R11, R17, R18, R20 | R7, **R8** / R1, R4, **R5, R6, R10**, R11, R17, R18, **R19**, R20 | as S′ |
| R5 (T-A) | crosses | internal CB23 | internal CB23 ("actor of an act") |
| R6 (T-A) | crosses | internal CB23 | internal CB23 ("actor of a work event") |
| R8 (T-A) | crosses | **internal CB1** | **internal CB1** ("keeper") |
| R10 (T-A) | crosses | internal CB23 | internal CB23 ("Cap holder") |
| R19 (T-A) | crosses | internal CB23 | internal CB23 |
| R25 (T-B, inactive, H-D D-chg) | crosses | internal CB23 | internal CB23 |
| R38 (T-B, inactive, H-D R-j) | crosses | internal CB23 | internal CB23 |
| T-B crossing (E2b §A1.4; all inactive) | 7: **R25**, R29, R35, R36, **R38**, R39 (VP2), R83 | 5: R29, R35, R36, R39 (VP2), R83 | as S′ |
| keeper typing R8 as a CB23 ↔ CB1 relation (T3 r1.8 §1a relations cell) | "a CB23 ↔ CB1 relation under the spanning reading S [E]" | "internal to CB1 and is not a relation between the sides" | as S′ |
| H-D asymmetry at 𝐑₀ (T3 r1.7 §1a (d)) | "**H-D-asymmetric**: change clauses asymmetric (D-chg crosses, B-chg does not); record clauses symmetric" | "**symmetric** (the r1.6 result; see the M2 disclosure in (a))" | as S′ |
| under R-i / R-i′ | "H-D-asymmetric in **both** the change and the record clauses" | "H-D-asymmetric in the record clauses only (as F-LOG-0265)" | as S′ |
| F-LOG-0265 premise "neutral under 𝐑₀" | does not hold (T3 r1.7 [E]) | holds (T3 r1.7 [E]) | holds (T3 r1.7 [E]) |
| disclosure on the reading (T3 r1.7 M2; E2b §A1.9a) | the F-LOG-0268 disclosure the human acted on "presented only S" (F-LOG-0270) | "S′ is in tension with "spanning CB23 and CB1"" (E2b §A1.9a) | "coincides with every VP12 (ii) result that r1.6 stated (indicatively; r1.6 left (ii) to a partial re-derivation)"; "may conflict with" F-LOG-0268's "No additional role-separation assumption is introduced by this act" |

**Asymmetry status (F-LOG-0271, verbatim):** "The H-D asymmetry associated with P-c remains documented, unresolved, and unaccepted." **Tier scope (E2b §A1.9a):** among the constraint tiers, it lies only in the inactive T-B VP1a slots R25 and R38, "these are the VP1a options whose neutrality the cut is meant to preserve". **Meaning (T3 r1.7 §1a (g), [E]):** it is not "evidence for or against H-D or H-B", nor "a reason, by itself, to reopen VP12 or VP14".

### 3b. What would differ for E3 under each reading (questions and consequences only; [E])

| E3 question | consequence if E3 counts under S | under S′ | under S″ (counting only) |
|---|---|---|---|
| Q-S1 [E]: which T-A rows would a CB1/CB23-derived boundary have to carry as constraints that span both sides? | 17 rows, including R5, R6, R10 and R19 (frame typing) | 12 rows | 12 rows |
| Q-S2 [E]: is keeper typing (R8) something between the sides, or something inside the records side? | between the sides | inside CB1 | inside CB1, because the actor is located as keeper. **Counting result only; no keeper role is introduced** (B14) |
| Q-S3 [E]: how would the participant, which spans both sides (VP12 (i)), be counted wherever E3 places it? | on both sides. **Cost:** the 𝐑₀ change-clause asymmetry (R25 (𝐑₀) and R38 (R-j), inactive T-B slots; outside the constraint tiers, T-D R71 also crosses under S via its D-chg disjunct) and the four T-A frame-typing rows (R5, R6, R10, R19) cross | on neither side. **Cost:** "S′ is in tension with "spanning CB23 and CB1"" (E2b §A1.9a), the wording of VP12 (i) | per statement; **no role split** (B14). **Cost:** S″ "may conflict with" F-LOG-0268's "No additional role-separation assumption is introduced by this act" (E2b §A1.9a), and "VP12 (ii) risks deciding VP4 by implication" (E3-PREREQ §6 item 12), which S″ coincides with indicatively |
| Q-S4 [E]: how would CG1, which straddles the cut through C1, relate to the sides? | **not derived in any source.** T3 r1.8 §4: "How the straddle **counts** for the crossing criterion is not settled: it follows the spanning readings S, S′ and S″ (§1a rule 2a), none of which is selected (F-LOG-0271), so results are reported under all three." Any per-reading cell here would be a new [E] derivation, which this proposal does not make | (as S) | (as S) |
| Q-S5 [E]: would any E3 output inherit an H-D asymmetry? | the inactive slots R25 (𝐑₀) and R38 (R-j) cross: the change-clause asymmetry (T-D R71 also crosses under S via its D-chg disjunct). **Also:** the R-i / R-i′ asymmetry, in the change and the record clauses | no 𝐑₀ change-clause asymmetry. **Still:** the R-i / R-i′ record-clause asymmetry holds (F-LOG-0265: "asymmetric under R-i and R-i′"). **Cost:** the S′ tension (Q-S3) | as S′: the R-i / R-i′ record-clause asymmetry holds. **Cost:** the S″ costs (Q-S3) |
| Q-S5, every reading | The F-LOG-0271 disclosure travels **under every reading**. The asymmetry stays "documented, unresolved, and unaccepted"; no reading makes it disappear from the record | (same) | (same) |
| Q-S6 [E]: could the F-LOG-0265 premise be cited for an E3 output? | no, it fails under S (T3 r1.7 [E]); the premise stays not withdrawn (B3) | yes, conditionally, at 𝐑₀ only (asymmetric under R-i and R-i′). **Cost:** the S′ tension (Q-S3) | yes, conditionally, at 𝐑₀ only. **Cost:** the S″ costs (Q-S3) |

**Consequence for the shape of an E3 output [E]:** no record says whether E3 delivers one reading-neutral output or a result per reading. The binding condition is only that results be presented "under all three interpretations". The human may say which (§6, item Q-R; §9, condition (3)).

**Note on a "reading-neutral" output [E] (applies to Q-R (i) and §9 condition (3)).** The crossing sets are nested: S′ = S″ ⊆ S (T3 r1.7 §1a "Full crossing list under S (46): the 37 r1.6 rows below, plus R5, R6, R10, R19 (T-A); R25, R38 (T-B, inactive slots); R63 (T-C, excluded); R71, R76 (T-D). **Under S′ / S″ (36):** the 37 r1.6 rows below minus R8", and the spanning-rule sensitivity paragraph "[r1.7] Spanning rule (rule 2a)"). The difference S \ S′ is 10 rows:
- T-A: R5, R6, R8, R10, R19;
- T-B: R25, R38;
- T-C: R63;
- T-D: R71, R76.

Hence an output built only on what crosses under **every** reading (the intersection) coincides with S′ / S″. One built on what crosses under **any** reading (the union) coincides with S. Neither construction is neutral in effect: each reproduces one reading's results. A reading-neutral output must therefore still show the per-reading results (B2), and must disclose any intersection- or union-based construction it uses.

---

## 4. Constraints on any E3 output (B1–B14, compressed; full text in the package r1.1 §3)

| # | constraint | source |
|---|---|---|
| B1 | Derive "from the adopted bounded model only": the 27 elements; the 25 with full T3 r1.5 §2 rows; CB1/CB23 with the restricted trace; T2 hosting for CB1/CB23 only from the per-reading report | ARB §E3; F-LOG-0275 Act I (1); E2b §A1.2–§A1.4 |
| B2 | Present results under S, S′ and S″; "must not silently privilege one interpretation or present the asymmetry as an accepted property of the cut" | F-LOG-0271 |
| B3 | Do not withdraw F-LOG-0265's premise. "T3 r1.7 records [E] that it holds under S′ and S″ and not under S" | F-LOG-0271; E2b Act I (4) |
| B4 | "All T-B rows (R25–R39, R79, R80, R83) remain **inactive** policy slots; R83 keeps its condition" | F-LOG-0261; F-LOG-0254 |
| B5 | "R1–R24 remain candidate model constraints, not domain invariants"; nothing enforceable from records (R23) | F-LOG-0261; F-LOG-0275 Act II |
| B6 | Consistency only: "a conceptual human design decision, without transaction semantics … the transaction part stays excluded" | F-LOG-0275 Act II (3a); E2b §A1.5 |
| B7 | Only the ten rows "R1, R2, R3, R5, R6, R8, R9, R18, R19 and R22" are labelled cross-grouping constraints (interim; C-γ returned) | F-LOG-0269; F-LOG-0275 Act II (3b) |
| B8 | The standing qualifier, verbatim | F-LOG-0248 |
| B9 | Limitations: ARB §B 1–8 (with the item 4 and item 8 notes), Λ1–Λ7, the F-LOG-0256 limitations, D1–D6 status, and the unratified analyst items | ARB §B; brief r3; E2b §3, §6, §A1.8–§A1.10 |
| B10 | The ARB §E3 exclusions (§1a) | ARB §E3 |
| B11 | "“grouping”, “value-like”, “occurrence”, “slot” stay structuring hypotheses, not aggregates, value objects, domain events or bounded contexts" (quote style per package B11) | E2b §3 |
| B12 | Carry open, do not resolve: (d′), C-γ, R11, H13, the cosmetic punctuation, and the CV9 / §3b tension | F-LOG-0278; E3-PREREQ §6 item 1; E2b §A1.5 |
| B13 | No mapping justified by any VP1a–d outcome (N1); encode neither H-D nor H-B | F-LOG-0265; F-LOG-0269; F-LOG-0275 |
| B14 [E] | S″ is counting only; no role concepts; VP4 is not decided by implication | F-LOG-0268 appended; E2b §A1.9a; E3-PREREQ §6 item 12 |

---

## 5. Questions E3 would answer (all [E]; none answered here)

Package §5 preamble (verbatim): "Every mapping to a DDD construct is **[E]**. … Any mapping is a candidate that E3 would derive, if E3 is ever authorized. The O3 proposal names these only as the questions E3 would answer; it derives no mapping."

| # | question [E] | evidence available | constraints | open items bearing on it |
|---|---|---|---|---|
| Q1 | **Boundaries.** Would CB1 and CB23 yield candidate context boundaries, and with what relation between them? *(named in register §3 as "Boundaries"; the "context" reading is [E]. Source: T3 §1a heading "bounded-context-like partitions")* | E2b adoption by membership and restricted trace (§2b); placement 9 + 14 + 5 = 28 C-ids (T3 r1.7 §1a); per-reading crossing results (§3). The relations table and the K-c table exist only as "T3 [E] analysis", not as constraints | B1, B2, B3, B11, B13; CB4 is "not a side … not an element"; P-a, P-b and P-d not adopted; C22 unplaced; CV8, CV9 and CS1 ungrouped and on no side | (d′); A2; A2′; spanning rule unselected; VP10 (iii) (CB4 could later become a side: E2b §A1.9b) |
| Q2 | **Participant placement.** Where would the shared participant sit relative to any candidate boundaries? *(named in register §3)* | VP12 (i): "spans CB1 and CB23" (E2b §A1.2a); participant trace T3 r1.5 §2 | B14; no role split (VP4 open, ARB §E3: "stated as unsupported unless decided under E2"); §3b "named roles / offices beyond Cap" excluded | spanning rule (Q-S3); A1 (VP4 edge); A2′ (where the output relates the participant to CB1/CB23) |
| Q3 | **Groupings.** Would CG1–CG6 yield candidate aggregate boundaries, kept separate, with only the ten labelled rows as cross-grouping constraints? *(named in register §3 as "groupings"; the "aggregate" reading is [E]. Source: T3 §1b heading "aggregate-like clusters")* | VP11 (ii) (F-LOG-0269); T3 §1b crossing evidence for the ten rows (e.g. "**R5** (acts in CG3 typed with actor α in CG1 and target/value in CG2)"); consistency admitted conceptually (B6); T3 §1b: "the groupings are conceptual clusters only, not units within which anything could be enforced" | B5, B6, B7, B11; R23; CG3 note "carries meaning mainly under D-st / D-st⁺ (VP1b, H-D clauses)", recorded with its H-B counterpart; no VP1a–d justification (B13) | C-γ (guard rows R14–R17, R21); R11; the implication / meta rows outside the §1b lists ("whether they belong there is not decided", T3 §6 item 1); CG1 straddle (Q-S4) |
| Q4 | **Value-like concepts.** Would CV1–CV9 and CV-text be value-object candidates? *(not named in ARB §E3 or register §3; source: T3 §1c "value-like concepts")* | T3 r1.5 §2 rows (§2a); CV5 and CV6 "derived"; CV3 straddles the cut; CV8 "unobs", CV9 "meta-level, not a record attribute" | B11; B12 (CV9); B4 for the slots on CV4, CV5 and CV6 (R39; R27, R28; R32–R34) | CV9 / §3b tension (E3-PREREQ §6 item 1); VP13 (P ≠ V unset); VP2 |
| Q5 | **Occurrences.** Would CE1–CE6 be domain-event candidates? *(not named in ARB §E3 or register §3; source: T3 §1d "domain-event-like")* | T3 §1d: "**No order among them is held** … **No occurrence is said to produce or cause another**" | B11; §3b "an order or sequence among occurrences" excluded; CE4 "Chg-constraint slot **empty**" (B4); the F-LOG-0256 limitation on "event order or causal relationships" | VP1a (inactive); VP16 (no trace-to-work link) |
| Q6 | **CS1 and MW1.** Would E3 map them at all? | T3 §1e. No DDD mapping is recorded; CS1 is an "**unrealized slot**", and MW1 "**is** the observability criterion" (R23) | B11; E-A5 not authorized | VP6 |
| Q7 | **Ports.** **UNANCHORED.** No source names ports. This question is included only if the human names it | none | — | — |

**Scope question for the human [E]:** ARB §E3 says "derive an architecture proposal from the adopted bounded model only", but register §3 names only boundaries, participant placement and groupings. Whether Q4–Q7 are inside E3 is not recorded (§6, item Q-R).

---

## 6. Open items and ambiguities, carried forward

Package r1.1 §4: "Whether any of them should be closed **before E3** is a question the O3 proposal must state for the human. It may not answer it." **The table therefore states the consequences of each timing [E] and recommends none.** "Before" means closed or ruled before, or at, the E3 act. "Carried" means carried open through E3. "After" means it may follow E3.

| item | content (source) | bears on | if closed / ruled before E3 [E] | if carried through E3 (or left until after) [E] |
|---|---|---|---|---|
| **A1** | VP4 / VP8 edges. "**A → B** means that A should be decided, or explicitly left open, before B" (register §2). "no act has ruled" whether the §2f defaults are "explicitly left open". ARB §C row 7 lists VP4 for O3. Register §3, O3 row, "may stay open": "VP4, VP7, VP8 (stated as unsupported)" | Q2, Q3; the validity of VP12 / VP11 under the edge rule | the edge question is settled. If it is ruled that the §2f defaults were not "explicitly left open", the edge order before VP12 and VP11 was not met. Package r1.1 §6 lists, for "a ruling on A1 (the VP4 / VP8 edges) or A5", the effect "§2 (prerequisite record); possibly VP12 and VP11, by human act only" | E3 states VP4 and VP8 "as unsupported" (ARB §E3). The edge question stays open over VP12, VP11 and any E3 output relying on them [E] |
| **A2** | ARB §E3 acceptance: "every architecture element traces to T3 elements and their §2 rows", against CB1/CB23 traces "restricted to the T1 ids and the R4 / FROZEN R3 ids not keyed to hosting". Also: "The stale T3 r1.7 CB1 / CB23 cells are not adopted" | E3 acceptance; E3b | see "A2 and E3 acceptance" below. **The disposition is a mandatory clause of the Authorize text (§9)**, so it is not left to the deriver | not available as a silent option. Option (c) in §9 is the explicit "carried open" disposition |
| **A2′** (new, r1) | The F-LOG-0248 chain (concept → R4 / R3 → inherited assumptions → reading sensitivity) **cannot be completed** for outputs derived from CB1 / CB23. Only the T1 and R4 / R3 links are adopted. The T2-ids and reading-sensitivity cells are not adopted, and the evidence-class, inherited-assumptions and open-questions cells are outside the restriction (§2) | Q1, Q2; V1 | completing the chain needs (d′) plus an E2b amendment adopting the whole synced CB1 / CB23 §2 rows (every cell other than the adopted T1 and R4 / R3 cells: T2 ids, evidence class, inherited assumptions, reading sensitivity, open questions), or a ruling that the partial chain suffices | CB-derived outputs carry a partial chain, disclosed as such |
| **A3** | Stale T3 r1.8 notes ("not made as of r1.8"): status line, §6 items 1 and 6 | none (text) | a T3 text sync, by its own act; no content change | the text stays stale. The proposal cites F-LOG-0275 for VP11's effect; no content effect |
| **A4** | Review regime | — | resolved for this proposal by F-LOG-0280 | for the E3 output: §8 and §9 condition (4) |
| **A5** | E2b §A1.9b: "Also required, each by its own act: … R11 open; … (d′) stays open." Is this a prerequisite, or a status list? | E3 prerequisites | if read as a prerequisite: E3 waits for (d′) and R11. If read as a status list: no effect | the contrary reading stays open, undisposed. If it were later upheld, the E3 act would have been taken with a prerequisite unmet [E] |
| **(d′)** | "T3 sync of the CB1 / CB23 cells (M5) and the hosting-keyed R4 clauses (G6), including the missing R4 ids under S" (E2b §A1.11) | Q1; A2; A2′ | (d′) is a T3 sync and adopts nothing. CB1/CB23 traces become complete only if an E2b amendment, by its own act, adopts the synced cells. E3 is delayed by two acts | the S-reading R4 gap stays for R5, R6, R10, R19, R25, R38. CB-derived outputs are at risk at E3b under A2 (b). Under A2 (a), only the S-reading results for those six rows stay exposed, until (d′) is made and the synced R4 ids are adopted by an E2b amendment (two separate acts, not authorized here) |
| **C-γ** | "whether guard rows that cross groupings (R14–R17, R21; R11 open) are cross-grouping constraints in the sense of the VP11 act" | Q3 | the cross-grouping constraint set is settled before E3 maps onto groupings | E3 carries the ten-row interim set (B7) and labels the guard rows "question returned". A later answer re-derives Q3 outputs (package r1.1 §6) |
| **R11** | "R11 … appears to cross groupings … adding it needs its own act" (T3 r1.8 §6 item 1). Kept separate from C-γ | Q3 | the guard list is fixed for E3 | R11 is reported as an open crossing question; a later act re-derives Q3 outputs |
| **CV9 / §3b** | "CV9's adoption may already engage the T3 §3b row "epistemic-status / evidential-limitation information in the model", which E2b §2d lists as excluded" | Q4 | CV9's status is known before any Q4 output | nothing is mapped on CV9 as resolved; any Q4 output on CV9 stays conditional |
| **H13** | stale register VP11 note "effect pending E2b amendment" (F-LOG-0277) | none (text) | a register text sync, by its own act | the text stays stale; no content effect |
| cosmetic punctuation | "cosmetic, deferred" (F-LOG-0278) | none | — | none |
| **spanning rule / asymmetry** | unselected; "documented, unresolved, and unaccepted" (F-LOG-0271) | Q1–Q3 | §3 collapses to one reading; E2b §A1.9b reopen row (per-reading hosts, D5, F-LOG-0265 premise; possibly VP14 / VP12) | E3 reports under S, S′ and S″ (B2), with the §3b costs and the §3b note on nested crossing sets |
| **Q-R** (new; this proposal) | (i) one reading-neutral output, or a result per reading (see the §3b note); (ii) whether Q4–Q7 are in E3's scope; (iii) **narrowed in r1:** the trace pins are already fixed by E2b §A1.2 (r1.5 for the 25; r1.7 for CB1/CB23). Only one thing remains open: whether E3 may also **cite** T3 r1.8 text that postdates the pins (the §1b guard list with R21, the §4 VP11 side note, the r1.8 R8 relations cell) as context, not as trace | E3 scope | settled in the E3 act (§9 conditions (2), (3)) | E3 would have to choose, which leaves scope to the deriver |

**A2 and E3 acceptance [E].** For any element derived from CB1 or CB23 (Q1, and Q2 in part), the acceptance criterion can be read three ways:
- **(a)** The restricted trace counts as "their §2 rows". **Exception:** the S-reading results involving R5, R6, R10, R19, R25 and R38 stay exposed until (d′) is made and the synced R4 ids are adopted by an E2b amendment (two separate acts, not authorized here). Per E2b §A1.2 G6, "the CB1 cell carries no R4 ids for R5, R6, R10, R19, R25 and R38, although these rows cross under S".
- **(b)** "Their §2 rows" means the whole row. That row contains T2-ids, reading-sensitivity and hosting-keyed cells that E2b did **not** adopt, so the criterion cannot be met consistently with B1. **(d′) alone does not cure this:** it is a T3 sync that adopts nothing. E2b pins the restricted r1.7 trace ("The stale T3 r1.7 CB1 / CB23 cells are not adopted"), and E2b §A1.9b reopens §A1.2 only "if the sync result differs from them". The criterion can be met under (b) only after (d′) **and** an E2b amendment, by its own act, adopting the whole synced CB1 / CB23 §2 rows (every cell other than the adopted T1 and R4 / R3 cells: T2 ids, evidence class, inherited assumptions, reading sensitivity, open questions). These are two separate acts. Any cell left unadopted keeps the criterion unmet. [E] Under (b), that E2b amendment would have to adopt the reading-dependent cells (T2 hosts, reading sensitivity) per reading, without selecting a reading (B2; F-LOG-0275 Act I (1): "no reading is selected"); the per-reading host lists are at present unratified analyst items (E2b §A1.10).
- **(c)** The element traces to the whole r1.7 row. That cites non-adopted cells, which B1 forbids. (c) is listed for completeness only.

**Effect on E3b.** E3b's criteria repeat the trace criterion ("every architecture element traces to T3 elements and their §2 rows"). A ruling on A2 for E3 therefore bears on E3b too. Whether it also governs E3b is a sub-choice in §9.

**Status.** No record resolves A2. It is the main point where the sources are insufficient for an unconditional E3 request, and §9 makes its disposition mandatory.

**Disposition of the other E3-PREREQ r4.1 §6 items** (items 1, 6 and 12 are handled above, at CV9 / §3b, A1 and B14 / Q-S3):

| item | point (short) | disposition (log) |
|---|---|---|
| 2 | VP10 at the O3 gate contested | resolved by ruling, F-LOG-0264; register/ARB synced F-LOG-0277/0278 |
| 3 | VP10 (i) excludes VP14 "no boundary layer" | moot: VP10 (iii) re-affirmed (F-LOG-0263); VP14 = P-c (F-LOG-0265). If VP10 (i) is later chosen: E2b §A1.9b reopen row |
| 4 | VP12 (ii) defined against P-a's sides only | moot: VP12 (i) (F-LOG-0268); reopen row "VP12 reopened as (ii)" (E2b §A1.9b) |
| 5 | VP11 (i) merges not enumerated | moot: VP11 (ii) (F-LOG-0269) |
| 7 | whether the E2b amendments precede O3, one act or several | resolved: Amendment 1 as Act I + Act II (F-LOG-0275), order C → A → B (F-LOG-0273), batch complete (F-LOG-0278) |
| 8 | "Preparing such a proposal needs its own human act" | resolved: F-LOG-0280 |
| 9 | interim state; "no O3 proposal should be prepared on a new value before its amendment" | satisfied: E2b amended (F-LOG-0275/0276) before this preparation (F-LOG-0280) |
| 10 | VP10 (ii) "qualifier only" | moot: VP10 (iii) |
| 11 | the F-LOG-0261 summary's unlabelled "VP10 revisited as (ii)" reading | carried as an unratified analyst item (E2b §6: "the VP10 (ii) "removed" reading"); not engaged under VP10 (iii) |
| 13 | label drift in the inputs (header) | "noted; not edited" there; no bearing on E3 [E] |

---

## 7. Risks and limitations

**Standing qualifier (verbatim):** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

| set | content | status / source |
|---|---|---|
| ARB §B items 1–8 | 1 one corpus, U / U_P2, R-a untested; 2 within-family, cross-family "**waived, not satisfied**"; 3 non-identifiable, H5 condition waived; 4 X25 / R41 exclusion; 5 T-C reading of "open" [I]; 6 T-A adequacy [E]/[I], R23; 7 "10 extensions beyond R4 (T3 §3b) are unsupported"; 8 VP status | item 4: "**[r4.2] superseded for the policy layer by F-LOG-0254**" (invariant exclusion stands). Item 7: one §3b row is now admitted in part (consistency, E2b §A1.5). Item 8 is stale; per E2b §A1.8: "7 items are now decided or recorded (VP1d-H, VP15, VP5, VP10, VP14, VP12, VP11), and 13 VPs are open" |
| Λ1–Λ7 (brief r3 §1) | Λ1 cross-family waived, not satisfied; Λ2 H-D and H-B neither falsified nor validated; Λ3 non-identifiability bounded to the corpus, H5 condition waived; Λ4 D1 strong form reading-relative; Λ5 weak form O-form only, R-j (ii)-O exception, R-a untested; Λ6 U, U_P2 and one corpus only; Λ7 every reviewer and successful run Claude-family | standing; "never strengthened" |
| F-LOG-0256 limitations | "one corpus and finite test universes; within-family validation, cross-family waived; the shared Claude interpretation untested; H-D and H-B non-identifiable; the theory does not establish aggregate or consistency boundaries, event order or causal relationships as empirical facts" | human-preserved. **[E] Direct bearing on Q1, Q3 and Q5:** any E3 boundary, grouping or occurrence output is a design hypothesis, not an empirical finding |
| D1–D6 (E2b §A1.9) | D1 resolved (R17 on CB23); D2 resolved (VP1d slots hosted); D3 replaced by D5; D4 unchanged; **D5** the spanning rule and the H-D asymmetry; **D6** stale T3 cells not adopted, (d′) open | D4, D5 and D6 carried |
| ARB §E3 unresolved risks | "one-corpus primitives (VP5); low reversibility of VP15; R23 (nothing enforceable from records)" | carried |
| unratified analyst items | E2b §6: "the T2 §0 [I] reading of "open"; the E2b VP1a–d rule …; the VP10 (ii) "removed" reading". E2b §A1.10: the K-c criterion and its rules; the per-reading host lists; the G6 restriction; "named roles … not engaged by VP12 (i)"; the T3 r1.7 "not … a reason, by itself, to reopen VP12 or VP14"; the C-β (27) reading | carried. **[E]** The §3 results rest on K-c, which is itself unratified |
| reopen conditions | ARB §F; E2b §6 / §A1.9b; package r1.1 §6 | ARB §F names E3 in two rows, in different words. The **V6** row ("**V6** shows the primitives, K6 or K7 are corpus-specific") reopens "E3's derivation and proposal". The **adequacy-failure** row ("a T-A constraint is found **not to hold in the domain**") reopens "any E3 / E4 / E5 output tracing to those elements" |
| this proposal | not yet reviewed; Claude-family authorship (Λ7) | §8 |

---

## 8. Proposed E3 review plan and acceptance evidence ([E]; for the human to adopt, amend or replace)

**Review of the E3 output (proposed [E], mirroring F-LOG-0280):** fresh independent `kos-theory-reviewer` subagents, one new reviewer per round. Stop rule: a FAIL at round 3 → human; otherwise PASS / PASS_WITH_LIMITATIONS with nothing blocking → present for a separate E3b act. ARB §E3b prerequisite: "an independent review of it completed". Λ7 applies: the review is Claude-family.

**Acceptance evidence the E3 output would carry (from ARB §E3 / §E3b and B1–B14):**

| # | evidence | criterion source |
|---|---|---|
| V1 | a trace table: every architecture element → **adopted** element(s), i.e. one or more of the 27 of E2b §A1.2 → pinned §2 row (r1.5 for the 25; r1.7 restricted for CB1/CB23). The A2 reading is applied **as ruled in the E3 act**, or readings (a) and (b) if carried open (option (c)). The partial F-LOG-0248 chain for CB-derived elements is disclosed (A2′). [E] ARB's "T3 elements" is read as the adopted elements: T3 r1.8 counts 28, including the CB4 slot, which E2b does not adopt as an element and which is therefore no trace target | ARB §E3; B1 |
| V2 | results under S, S′ and S″ side by side; none privileged; S″ counting only | F-LOG-0271; B2, B14 |
| V3 | the asymmetry carried as a disclosed limitation, marked not accepted | F-LOG-0271 |
| V4 | no T-B / T-C / X item as an invariant; T-B slots inactive; R83 condition | ARB §E3b; B4 |
| V5 | R1–R24 as candidate model constraints; R23 stated for every grouping-derived element | B5, B6 |
| V6 | only the ten labelled cross-grouping rows; guard rows and R11 labelled open | B7, B12 |
| V7 | the exclusions list (VP4, VP7 and VP8 unsupported; evidence gaps; no canonical terms; no generality) | ARB §E3; B10 |
| V8 | a neutrality statement: no VP1a–d reference (N1) | B13 |
| V9 | the standing qualifier verbatim; ARB §B, Λ1–Λ7, F-LOG-0256 limitations, D4–D6 | ARB §E3; B8, B9 |
| V10 | the open items of §6 carried, unresolved | B12 |
| V11 | the review record (rounds, verdicts, fixes, and what was not re-reviewed) | ARB §E3 "independent review" |

**Review of this O3 proposal:** per F-LOG-0280 (header). Suggested reviewer focus [E]: verbatim accuracy of the quotes; no mapping derived; the §3 table against E2b §A1.3/§A1.4 and T3 r1.7 §1a; the B1–B14 self-check.

---

## 9. Draft Human ARB act texts on E3 (select one; no default)

**Authorize** (clause (1) is **mandatory**: one option must be selected; the other bracketed choices have no default):
> "I, Human ARB, authorize E3: a bounded architecture derivation from the adopted bounded model (`R7/E2b-ADOPTION-TEXT.md` r1.2) under the scope, exclusions and acceptance criteria of `R7/ARB-DECISION-PACKAGE.md` r4.4 §E3, and under the binding conditions B1–B14 of `R7/O3-PREPARATION-AUTHORIZATION-PACKAGE.md` r1.1 §3. **The package r1.1 §3 text governs.** The compressed restatement in `R7/O3-PROPOSAL.md` r[n] §4, and that proposal's analyst advice [E], do not. The output is an architecture **proposal** for human review, with results under S, S′ and S″, none privileged.
>
> (1) **A2, mandatory; select one:**
> [(a) For the ARB §E3 acceptance criterion "every architecture element traces to T3 elements and their §2 rows", the CB1 / CB23 traces restricted by E2b r1.2 §A1.2 count as "their §2 rows". This is an interpretive ruling on an ARB criterion. Exception: S-reading results involving R5, R6, R10, R19, R25 and R38 stay exposed until (d′) is made and the synced R4 ids are adopted by an E2b amendment (two separate acts, not authorized here), because the CB1 cell carries no R4 ids for them under S (E2b §A1.2 G6).
> | (b) E3 does not begin until (d′) is made by its own act **and** the whole synced CB1 / CB23 §2 rows (every cell other than the adopted T1 and R4 / R3 cells: T2 ids, evidence class, inherited assumptions, reading sensitivity, open questions) are adopted by an E2b amendment by its own act. These are two separate acts, not authorized here. Any cell left unadopted keeps the criterion unmet. E3 then traces to the adopted synced rows.
> | (c) A2 is carried open. E3 reports traces under readings (a) and (b), and flags every output derived from CB1 / CB23 as at risk for E3b.]
>
> **Sub-choice** (applies if (a) or (b) is selected; mandatory then), **select one:** [this ruling also governs the identical trace criterion of E3b | this ruling governs E3 only; E3b's criterion is ruled at E3b].
>
> (2) Scope: [boundaries, participant placement and groupings only | also Q4–Q6 of the proposal]. Ports (Q7): [not included | included as named here: ___].
>
> (3) Output shape: [one reading-neutral proposal, still showing per-reading results and disclosing any intersection- or union-based construction | a result per reading].
>
> (4) Review regime: fresh independent `kos-theory-reviewer` subagents, one new reviewer per round. Stop rule: a FAIL at round __ → human; otherwise PASS / PASS_WITH_LIMITATIONS with nothing blocking → present for a separate act on E3b.
>
> It decides no variation point, selects no spanning rule, accepts no asymmetry, and does not withdraw F-LOG-0265's premise. It resolves no open item ((d′), the C-γ guard-row question, R11, the CV9 / §3b tension, H13) except as clause (1) states. It amends no adopted or governed record, authorizes no evidence collection (including V2, V5, V6, E-A5), and adopts no canonical terms. It is decided without reference to any VP1a–d outcome (N1). **It does not authorize E3b, E4 (R8) or E5.**"

**Notes to the Authorize text [E]:**
- Clause (1) is mandatory so that A2 is never left to the deriver (§6).
- Option (a) is an interpretive ruling on an ARB criterion, not an amendment of the ARB package. Precedent: F-LOG-0264 was a ruling whose consequential record amendment needed its own act (F-LOG-0277). Whether an ARB §E3 annotation should follow (a) is for the human.
- Option (b) needs two separate acts, neither authorized by this act: the (d′) T3 sync, and an E2b amendment adopting the whole synced CB1 / CB23 §2 rows (every cell other than the adopted T1 and R4 / R3 cells: T2 ids, evidence class, inherited assumptions, reading sensitivity, open questions). Any cell left unadopted keeps the criterion unmet. [E] Under (b), that E2b amendment would have to adopt the reading-dependent cells (T2 hosts, reading sensitivity) per reading, without selecting a reading (B2; F-LOG-0275 Act I (1): "no reading is selected"); the per-reading host lists are at present unratified analyst items (E2b §A1.10).

**Return:**
> "I, Human ARB, return `R7/O3-PROPOSAL.md` r[n] for [items]. E3 is not authorized; E3b, E4 and E5 are not authorized. All records stand unchanged."

**Reject:**
> "I, Human ARB, do not authorize E3 at this time. The adopted model (E2b r1.2) and all records stand unchanged. E3b, E4 and E5 are not authorized."

When recorded, the selected text becomes `F-LOG-[act]`. **[E]** Any ruling on A1, A5 or C-γ, and the (d′) sync, would be separate acts. They are not offered as conditions here, except under clause (1)(b), which names (d′) and the E2b amendment as preconditions to be made by separate acts.

---

## 10. Self-check

| check | result |
|---|---|
| B1: adopted model only, with provenance | yes. §2 gives 27 elements; the 25 with T3 r1.5 §2 cells quoted; CB1/CB23 restricted, with the excluded clauses shown separately; T2 hosting for CB1/CB23 only from E2b §A1.3/§A1.4; git check of the pinned rows [E]. The partial chain for CB1/CB23 is disclosed (A2′) |
| B2: S, S′, S″, none privileged | yes, §3. Source order is stated as not a ranking. **[r1]** The costs of each reading are shown symmetrically (Q-S3, Q-S5, Q-S6), and the nested-set note is added |
| B3: F-LOG-0265 premise not withdrawn | yes, §3a row and Q-S6 |
| B4: T-B inactive; R83 | yes, §2a note, §4, V4 |
| B5: R1–R24 candidates only; R23 | yes, §4, Q3, V5. No R-row promoted |
| B6: consistency only | yes, §4, Q3 |
| B7: ten rows interim | yes, §4, Q3, §6 C-γ |
| B8: standing qualifier verbatim | yes: header, §7 |
| B9: limitations | yes, §7 (ARB §B with notes, Λ1–Λ7, F-LOG-0256, D1–D6, unratified items) |
| B10: ARB §E3 exclusions | yes, §1a quoted; V7 |
| B11: structuring hypotheses | yes. Outside verbatim quotations, every DDD term appears only inside a question labelled [E] (§5, §3b) |
| B12: open items carried, not resolved | yes, §6. **[r1]** Consequences of each timing are stated; no timing is advised |
| B13: N1 | yes. No VP1a–d outcome is cited as a reason; the Authorize text carries N1 |
| B14: S″ counting only | yes, §3 header, §3a, Q-S2, Q-S3. The VP4-by-implication risk is shown in Q-S3 |
| Hard limit: no architecture derived; no mapping of any element to a context, aggregate, value object, event or port | yes. Q1–Q7 are questions; ports are UNANCHORED; the Q-S4 CG1 cells are not derived |
| No VP decided; no S / S′ / S″ selected; asymmetry not accepted | yes |
| Without reference to VP1a–d (N1) | yes |
| No record amended | yes: only this file is edited |
| Act texts exclude E3b, E4, E5 | yes, all three |
| A2 not left to the deriver | yes: §9 clause (1) is mandatory; V1 applies the A2 reading as ruled, or readings (a) and (b) if carried open. **[r1.1]** Option (b) names both acts it needs, the (d′) sync and an E2b amendment adopting the synced cells |
| Insufficiencies disclosed | A2 (trace criterion, including the S-reading exception), A2′ (partial F-LOG-0248 chain), Q-R (output shape, scope, citation of post-pin T3 text), Q7 ports, A1, A5 |

---

## 11. Change rows

| rev | change |
|---|---|
| r0 | initial draft under F-LOG-0280. Round 1 fresh review: PASS_WITH_LIMITATIONS, no blocker; M1–M5 material, m1–m11 minor |
| r1 | applies M1–M5 and m1–m11 (first table below). Round 2 fresh review: PASS_WITH_LIMITATIONS, no blocker; F1 material, F2–F9 minor |
| r1.1 | applies F1–F9 (second table below). Round 3 fresh review: PASS_WITH_LIMITATIONS, nothing blocking; wording findings R1–R4 |
| r1.2 | applies R1–R4 (third table below). Not re-reviewed |

| finding | fix in r1 | where |
|---|---|---|
| M1 | A2 reading (a) carries the exception: S-reading results for R5, R6, R10, R19, R25, R38 stay exposed until (d′) (E2b §A1.2 G6) **[r1.2, R1: now "until (d′) is made and the synced R4 ids are adopted by an E2b amendment (two separate acts, not authorized here)"]**. Repeated in §9 option (a) and in the (d′) row | §6 A2, (d′); §9 (1)(a) |
| M2 | A2 disposition made a **mandatory** Authorize clause with options (a) restricted trace suffices, (b) (d′) first by a separate act, (c) carried open (both traces reported; CB-derived outputs flagged at risk for E3b); an E3b sub-choice; (a) labelled an interpretive ruling on an ARB criterion; V1 reworded ("as ruled … or all readings if unruled"); the §6 A2 row states it is not left to the deriver | §9, §6 A2, §8 V1, §10 |
| M3 | Symmetric costs: S′ tension with "spanning CB23 and CB1"; S″ possible conflict with F-LOG-0268 and the VP4-by-implication risk (E3-PREREQ §6 item 12); the R-i / R-i′ record-clause asymmetry stated for every reading; the F-LOG-0271 disclosure travels under every reading | §3b Q-S3, Q-S5, Q-S6 |
| M4 | [E] note: S′ = S″ ⊆ S; S \ S′ = T-A R5, R6, R8, R10, R19; T-B R25, R38 (and T-C R63, T-D R71, R76); intersection = S′/S″, union = S; a reading-neutral output must still show per-reading results and disclose the construction | §3b note; §6 Q-R; §9 (3) |
| M5 | Timing advice removed. §6 now gives the consequences of closing before E3 and of carrying through E3, citing package r1.1 §4 ("It may not answer it") | §6 |
| m1 | Register §3 O3 "may stay open" quote ("VP4, VP7, VP8 (stated as unsupported)") added; the self-undercutting rationale removed | §1b, §6 A1 |
| m2 | Q-S4 cells replaced by "not derived in any source", quoting T3 r1.8 §4 on how the straddle counts | §3b Q-S4 |
| m3 | Q-R (iii) narrowed: the pins are fixed by E2b §A1.2. Open only: whether post-pin r1.8 text may be cited as context | §6 Q-R |
| m4 | New insufficiency A2′ (the F-LOG-0248 chain cannot be completed for CB-derived outputs); the §2 chain wording split into the 25 and CB1/CB23 | §2, §6 A2′, §5 Q1/Q2 |
| m5 | V1 traces to the 27 adopted elements only; CB4 slot is no trace target (T3 counts 28) | §8 V1 |
| m6 | Condition (4) carries the full stop-rule tail; a Q7 ports slot added to condition (2) ("included as named here") | §9 |
| m7 | Authorize text: package r1.1 §3 governs, not §4 or the [E] advice | §9 |
| m8 | §7 reopen row distinguishes the ARB §F V6 row ("E3's derivation and proposal") from the adequacy-failure row ("any E3 / E4 / E5 output tracing to those elements") | §7 |
| m9 | "(beyond E2b's set)" restored; §2b column header states the excluded clause is omitted from the verbatim cell | §1b, §2b |
| m10 | One-line dispositions for E3-PREREQ §6 items 2–5, 7–11 and 13 | §6 (end) |
| m11 | Header DDD-term claim qualified to exclude verbatim quotations | header; §10 B11 |

**r1 → r1.1** (round 2: PASS_WITH_LIMITATIONS; the F-fixes are not re-reviewed)

| finding | fix in r1.1 | where |
|---|---|---|
| F1 (material) | Option (b) reworded: E3 begins only after (d′) **and** an E2b amendment adopting the synced CB1/CB23 cells (and, if wanted, the assumptions cell). These are two separate acts, not authorized here. Aligned: §6 A2 reading (b) ((d′) alone adopts nothing; E2b §A1.9b "if the sync result differs"); the (d′) row ("delayed by two acts"); A2′; the §9 notes; §10 | §9 (1)(b); §6 A2, A2′, (d′); §10 |
| F2 | Exact source wording "spanning CB23 and CB1" restored; the r1 change to "CB1 and CB23" was wrong | §3a, Q-S3, §11 M3 row |
| F3 | "R25 (𝐑₀) and R38 (R-j)"; T-D R71 noted as crossing under S via its D-chg disjunct | Q-S3, Q-S5 |
| F4 | V1: "readings (a) and (b) if carried open (option (c))" | §8 V1 |
| F5 | Sub-choice: "(applies if (a) or (b) is selected; mandatory then)" | §9 |
| F6 | Closing line: "except under clause (1)(b)" | §9 |
| F7 | A1: "not recorded" replaced by package r1.1 §6 "possibly VP12 and VP11, by human act only" | §6 A1 |
| F8 | "Register §3 names only these three; ARB §E3 names none"; ports only as the labelled question Q7 | §1a |
| F9 | Stray period inside the E2b §A1.9a quote removed; the nested-sets pointer now cites the T3 r1.7 §1a "Full crossing list" and the spanning-rule sensitivity paragraph | §3, §3b note |

**r1.1 → r1.2** (round 3: PASS_WITH_LIMITATIONS, nothing blocking; the R-fixes are not re-reviewed)

| finding | fix in r1.2 | where |
|---|---|---|
| R1 | "until (d′)" → "until (d′) is made and the synced R4 ids are adopted by an E2b amendment (two separate acts, not authorized here)"; the (d′) "carried" cell and the M1 row annotated to match | §9 (1)(a); §6 A2 (a), (d′); §11 M1 row |
| R2 | Option (b): the E2b amendment adopts the whole synced CB1/CB23 §2 rows (all cells other than T1 and R4 / R3, including evidence class, inherited assumptions and open questions); "if wanted" dropped; any unadopted cell keeps the criterion unmet. Evidence class and open questions listed as outside the restriction | §9 (1)(b) and notes; §6 A2 (b), A2′; §2 |
| R3 | [E] sentence: under (b) the E2b amendment would adopt the reading-dependent cells per reading, selecting none (B2; Act I (1)); the per-reading hosts are unratified (E2b §A1.10) | §6 A2 (b); §9 notes |
| R4 | [E] note: package r1.1 §5's attribution to ARB §E3 is not borne out by the §E3 text; package §3 governs | §1a |


**Standing qualifier (verbatim):** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"
