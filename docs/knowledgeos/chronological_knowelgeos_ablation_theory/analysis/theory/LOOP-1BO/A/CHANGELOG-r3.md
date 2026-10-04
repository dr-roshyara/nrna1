# CHANGELOG r3: FORMAL-THEORY-R4, r2 → r3 (human act F-LOG-0232)

**Inputs (only):** `FORMAL-THEORY-R4.md` (r2), `V1A-LEMMA.md` (r1), `V1-RECONCILIATION.md`, `R3-FROZEN.md`. No command was run and no web source was used.

**Rule:** TASK.md items 1–5 only. Every change is marked `[r3]` in the text. No proof in §1–§8 is changed. Existing statement ids are unchanged. The new statement ids are all in §9.

## Changes, mapped to the TASK items

| TASK item | Location in r3 | Change |
|---|---|---|
| 1. New §9, "Reading robustness" | §9 (appended after §8) | New section with these statements: **Def 9.1** (readings, reading classes, quiescence, RB1, RB2, M_p, M_p^W, M_q^D, N_q, property M, T, S-form and O-form; restated from V1A Def V1.1, V1.2, V2.1–V2.4). **Lem 9.2** (Lemma M: statement, with the proof given as an exact reference to V1A Lem V2.5 and V2.6). **Cor 9.3** (Lem 4.2 and Cor 4.3 transfer with M; V1A Cor V2.8 and Lem V2.6(ii)). **Table 9.4** (Rem: R-a … R-k, plus R-g⁺ and R-i′, each with a one-line HYP licence, M / not-M, and the status of (i), (ii) and (iii), from V1A Def V1.3, Table V3.0 and §3). **Rem 9.5** (V1b finite results per reading, with the scope caveat: U, one target, Claude-family, predictions not independent). **Prop 9.6** (the weak form, a labelled proposition with its exact scope: (O, 𝓗), N ≥ 1, P ≠ V, o ≠ ∅, the tested readings; the R-j exception for (ii)-O). **Rem 9.7** (under R-d, non-identifiability also rests on Cap being unobserved; not finite-verified). **Rem 9.8** (R-a untested) |
| 2. Scope qualifiers | §4.2, after Prop 4.1 (before Cor 4.1b); after the proof of Lem 4.2; after the proof of Cor 4.3 | One `*[r3] Scope.*` sentence each, in the form "holds for the Def 3.1 readings and every reading with property M (§9); fails generically under …". The statements and proofs themselves are unchanged |
| 3. §6 ledger | §6 legend; eight new rows at the end of the ledger | Rows for Def 9.1, Lem 9.2, Cor 9.3, Table 9.4, Rem 9.5, Prop 9.6, Rem 9.7 and Rem 9.8, each with its label and marked **[r3]**. One legend sentence defines the [r3] marker |
| 4. §8 compliance | §8, row H01 | "F01–F16" is replaced by "F01–F08, F11, F13–F16", with a note that F09, F10 and F12 are QUARANTINED in `R3-FROZEN.md` and are not cited |
| 5. Header status line | header table, row "Status" | Replaced by "reviewed draft r3; R5 finite-verified (r2 content); robustness per §9", verbatim from TASK §5 |

## Decisions made while applying the items

- **Scope sentences are precise about M.** Lemma M gives Prop 4.1(i) from M alone. (ii) also needs M_q^D and N_q, and (iii) needs T (Lem 9.2). V1A does not classify (i′) for any M-reading. So the Prop 4.1 sentence adds these conditions and names the exceptions: R-h and the rejected R-k for (iii), and (i′) classified only for R-i and R-i′. Without these conditions, "holds for every reading with property M" would be false for (iii) under R-h. The Lem 4.2 sentence notes that its (ii) part also needs M_q^D and N_q (Cor 9.3).
- **The Cor 4.3 sentence names a separating observation for each failing reading** (O₄, O_¬σf or O₃), each taken from a V1A lemma. O_Cap is deliberately not cited for Cor 4.3: V1A Lem V3.9(d) shows only that the *middle* equality fails under O_Cap, which is not Cor 4.3's form.
- **Prop 9.6 is proved formally by citation, not from V1b.** Its proof cites V1A's base-pair lemmas reading by reading. The V1b confirmation is recorded separately as finite confirmation and is not part of the proof. R-a and R-k also satisfy (a) and (b) formally (they have M, M_q^D and N_q), but they are kept outside the stated scope because they were not tested.
- **"8 non-M readings".** V1-REC uses this phrase for the readings with sealed predictions. By V1A Table V3.0 these are the seven not-M readings and R-h (which has M but not T). Rem 9.5 states this explicitly.
- **V1b per-reading detail is limited to what V1-REC states.** V1-REC does not map P1.1–P1.6 and P1.10–P1.13 to readings, so Rem 9.5 reports "scorable predictions CONFIRMED" for each predicted reading and does not attribute prediction numbers, except P1.7 (R-h). R-k has no reported V1b result.
- **Rem 9.7 adds one sentence beyond R-d.** V1A shows the same Cap-based separation under R-g, R-g⁺ and R-j (Lem V3.9(d), V3.16(c)). This is cited, not extended.
- **Labels.** The V1b results are labelled Rem (a report of finite results taken from an input), not Obs or Spec. No Lem, Prop or Cor rests on them.
- **§9's extra inputs.** §9 states its inputs (V1A, V1-REC) in its opening paragraph. §8 cites R3-FROZEN. The header's Inputs row is not changed (see below).

## Not changed, deliberately (flagged for the human reviewer)

TASK says "Nothing else changes", so the following were left as they are:
- The **title** still reads "(draft r2)", and the header's **Revision** and **Inputs** rows still describe r2. Only the Status line was in scope.
- The **ledger rows** of Prop 4.1, Lem 4.2 and Cor 4.3 are not re-marked for the added scope sentences. The legend says so.
- **O6 and Conj 5.5** describe F15 as "R2 pending", but R3-FROZEN marks F15 and F16 RESOLVED. **B09** is cited in O6 as a caveat only; R3-FROZEN marks it SUPERSEDED as reproducibility evidence. **E01** is used in Rem 3.1a and Rem 4.10; R3-FROZEN's annotation (same-family, n = 1, sealed expectation failed, depends on veridical texts) is consistent with Rem 4.10. None of these is a quarantined item, and none is in a TASK item.
- **Other quarantined ids.** The r2 text was checked by reading for the other quarantined ids in R3-FROZEN (A14, B01–B05, B08, C00, D01, D03, D05–D15, E02–E04, G02, H02, H03, H05, H06, H08, H09, H13, H15–H17). Only A14 occurs, in §8 H01, where it is stated to be "not used". Nothing was changed. B10 (NOT FROZEN) does not occur.

## Review status

- The Status line says "reviewed draft r3", verbatim from TASK §5. The **r3 additions themselves (§9, the scope sentences, the §8 edit) have not been reviewed**. "Reviewed" applies to the r2 content and to V1A r1, from which §9 is taken.
- Written by one same-family (Claude) analyst.

## Post-review analyst edits (main analyst; disclosed; wording only; all weaken or clarify; no proof changed)
Applied from REVIEW (LOOP-1BO/B, PASS_WITH_LIMITATIONS, 0 blocking, 0 unlisted changes):

| Item | Edit |
|---|---|
| NB-1 | Status line: "reviewed PASS_WITH_LIMITATIONS"; same-family disclosure restored |
| NB-2 | V1A cited as "PASS_WITH_LIMITATIONS, same-family" |
| NB-3 | Rem 9.7: SC1 and SC2 stated as **sufficient** (necessity not shown); "depends on O5" reworded as the corpus relevance of a Cap-blind observation |
| NB-4 | Cor 9.3's proof cites Lem V2.6(i) (with Cor V2.8). "Proves nothing anew" replaced by a disclosure of the elementary new steps |
| NB-5 | Table 9.4 R-j (ii): the S-form h_c half also fails; the base-pair O-form h_c holds |
| NB-6 | the scope sentences are now labelled Rem 4.1s / 4.2s / 4.3s, with ledger rows |
| NB-7 | the title and Revision row are updated to r3 |
| NB-8 | "not finite-verified" is restricted to the SC1/SC2 recovery |
| NB-9 | Rem 4.1s states that (iii) holds under every reading with T |
