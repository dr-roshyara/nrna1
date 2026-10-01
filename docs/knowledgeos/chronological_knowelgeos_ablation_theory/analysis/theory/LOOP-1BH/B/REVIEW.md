# Review of EMPIRICAL-THEORY-RECORD.md / .json (R3, PROVISIONAL)

| | |
|---|---|
| Inputs | the files in this directory only: TASK.md, REVIEW-TASK.md, ADDENDUM.md, the record (.md and .json), and every source listed in TASK.md. Added by the addendum: RETURN-02-COMPARISON.json and RETURN-02-REVIEW.md |
| Items checked | **74**: A01–A12, B01–B07, C00, D01–D14, E01–E04, F01–F13, G01–G06, H01–H17 |
| Reviewer family | same-family (Claude), like the record and every source |
| **Verdict** | **PASS_WITH_LIMITATIONS** |

**Why this verdict.** The frozen audit finds **no blocking issue**:
- every item traces to a file in the directory;
- CONTINUOUS-REPORT.md is never the sole source of a fact;
- Section C is correctly empty;
- the H5 scope qualifier and waiver are kept, and so is D1's "necessary, not sufficient";
- no anomaly is turned into theory;
- Section H smuggles in no hypothesis, aggregate or axiom;
- all prohibitions are respected.

There are non-blocking errors: one count error, one mislabelled source, some inconsistent R2 tags, one asymmetric anomaly direction, and several omissions.

The addendum files require **status qualifications** (listed under Omissions, O-R1..O-R9). They are **required before the record is used as input to R4**. They do not make the record FAIL, because:
- no item in the record claims reproducibility;
- every item that rests on the A′ coding is already tagged R2_DEPENDENT = yes and kept conditional;
- RETURN-02 neither discharges nor contradicts any item.

The worker is not at fault for the addendum omission (ADDENDUM.md).

---

## 1. Provenance

The quoted texts were checked against RECORDS.json, CODING.json, CODING-REVIEW.json, CONFORMANCE-ANSWER.md, IDENTIFIABILITY.json, H5-ADOPTED.md and the reconciliation files. The A, D, E and G quotes are verbatim, apart from the disclosed emphasis stripping.

| # | Item | Finding | Class |
|---|---|---|---|
| P1 | B01, F12 | "13 records are flagged uncertain" is wrong. CODING-REVIEW.json `uncertain` has **13 entries**, but one entry, "R15-R21", covers 7 records. So **19 records** are flagged (R01, R03, R05, R06, R07, R08, R12, R13, R15–R21, R25, R27, R29, R31) | count error |
| P2 | A08 | The span labelled "R-90:" ("Then R-90 itself withdrawn before adoption …") is RECORDS.json R06/R07. Its section is **"WP-4C-2 — commission closed … (within ## S0804-L576)"**, a session log, not the R-90 register row. Only R25 ("THE NUMBER R-90 IS RETIRED …") is from `## R-90` | source mislabel |
| P3 | A11 / F04 | F04 (md) says the reports "in A11" are the uncoded ones that H5 §3 lists. H5 §3 lists R-83 grep, "Container resolution executed", R-41 and **S0815**. A11 lists **S0804** and R-86 instead, which come from IDENTIFIABILITY P2. The S0815 execution and remediation reports (P8: "E-2 is CORRECTED (seq 18)", "Applied immediately") are in neither A11 nor A12 | partial mismatch |
| P4 | A01–A07, A09, A11 | These are labelled L0 "verbatim", but they are verbatim **as quoted by** 1ba (CONFORMANCE-ANSWER.md, from SOURCES.md) or 1bb (IDENTIFIABILITY.json). The primary texts are not in this directory. A09's "heading names the ARB CHIEF" is a coder's statement (CODING.json R29/R30 status), not a quoted heading. The record should state that these are second-hand quotes | disclosure |
| P5 | E01 | The phrase "NON-CONFORMS under the primary text reading" reproduces the wording of the F-LOG-0174 restatement. That restatement appears only in CONTINUOUS-REPORT.md. The substance is supported by CONFORMANCE-REVIEW.md §6–§7 and CONFORMANCE-CASE.json, so this is **not** a sole-source violation. Citing CONFORMANCE-REVIEW §7 for the phrase would be cleaner | note |
| P6 | B06 (md) | "`cc1a3394` is substantively WP-6 work (L1)": here "L1" means Protocol A limitation L1, not epistemic level L1. Ambiguous | wording |

**CONTINUOUS-REPORT.md as sole source: no violation found.** One consequence: the 1az finding "CI1: the A′ R-A3 convention builds H-D into the observations" and the 1bb finding "the spec partially smuggled in H-D" appear **only** in CONTINUOUS-REPORT.md. They are correctly absent from the record. The related primary fact is in IDENTIFIABILITY P1 (R-A3 makes the act and the after-state the same record). The record carries it in D05 and G04, but not in H02 (see H-2).

## 2. Epistemic level

No L1 item is stated as L0 except the second-hand quotes noted in P4. No L2 hypothesis is stated as fact. No confidence is raised above its source. Three points need attention:

| # | Item | Finding |
|---|---|---|
| L-1 | B05 | "a Chief-prepared ruling **gains force** when the Decision Authority adopts it" is stated flatly at L1. IDENTIFIABILITY P4 says force is "observed" only through declarations inside the norm-making acts, the same kind of evidence that was rejected for P1. The statement is also the content of H-D's status clause. STATE.md uses the same wording, so the level is not raised against STATE.md. It is stronger than P4, though. Suggested wording: "the rulings themselves declare that … (2 self-reported episodes)" |
| L-2 | D08 vs E04 | The same P4 content is L2 in D08 and L1 in E04. Make the levels consistent |
| L-3 | A01 / E01 | A01 quotes R-72's "IMPLEMENTATION DOES NOT BEGIN" without the adopted correction (CONFORMANCE-RECONCILIATION "Corrections adopted"; CONFORMANCE-REVIEW §2). The correction says this is a **snapshot of 08-02**, and the standing norm is the conditional. Without the correction, R4 could read it as a standing ban |

## 3. Regularities (C)

**C is correctly empty (0 qualifying clusters).** The candidates were checked against the sources:
- **B04:** the two flips are R31→R32 (target 7B, R-56 note → R-58) and R33→R34 (target 7C, R-58 → R-65). Both run through R-58, which is the changing act in one and the prior state in the other. So they are **not** independent clusters. The record's reason is correct. R31 is also a recording note "not part of the ruling" (CODING.json R31), so "successive rulings" (copied from SUPPORT-RECONCILIATION) is loose.
- **B05/E04:** n=2, self-reported. IDENTIFIABILITY P4 requires ≥3 different adoption acts. Correct.
- **B03:** confounded with time and the authorizing ruling; 2 pairs are within one ruling. Correct.

Filing all three under B/E rather than C is right. **No regularity errors.**

## 4. D and E

- **Kept:** the H5 scope qualifier (D01), the waiver, the skipped H1 and the unevaluated Q4 (D02), D1 "necessary, not sufficient" plus "not validated" (D03), NOT_DETERMINABLE (D04), and P1–P8 (D05–D12), each with its reviewed status.
- **No anomaly is turned into theory.** H13 explicitly forbids fixing the status of R-89 or R-94, and C1.
- **Symmetry: two defects.**
  - **S-1 (E02):** the direction is given only as "against H-B, **if** R-89 took force" (copied from H5 §4). IDENTIFIABILITY P4 also names R-89 as an **unresolved possible falsifier of H-D's status clause**: `falsifier_HD`, "a ruling that stays PREPARED … yet is cited as governing authority … (R-89 is the candidate)". E02 should record both directions. The record mentions the H-D direction only inside E04's alternative reading.
  - **S-2 (E03):** E01 states "H-D makes no prediction", but E03 does not state the mirror fact "H-B is silent (P5)". That fact is in D09 only.
- **E01:** n, confound and alternative reading are complete. Missing: R-86's "bear on the production producer path … not on batch 7's execution" (CONFORMANCE-CASE alternative) and the R-72 snapshot correction (L-3).
- **E02:** missing the independence caveat from CONFORMANCE-REVIEW §5: C2 relies on R-89's **self-certified** "seven checks", which were not independently verified.

## 5. R2_DEPENDENT tags

**No item that rests on the same-family A′ coding is tagged no.** Some tags are inconsistent:

| # | Item(s) | Finding |
|---|---|---|
| T-1 | H05 (no) vs D01 (yes) | H05 is derived from D01. D01 is tagged yes through H5 reopening condition 3, and H06 (also derived from D01) is tagged yes. H05 should be yes, or should give a reason for differing |
| T-2 | D12 (yes) vs F10 (no) | Both rest on IDENTIFIABILITY P8, and P8's point is a corpus-window limitation. The tags conflict |
| T-3 | D10 (yes) | P6 rests mainly on **uncoded** execution reports (A11) and on the hypotheses' text, not on A′ codings. "Rests on coded records" is doubtful. Keeping yes is conservative, but the reason should be corrected |
| T-4 | G02 (yes) | Under the record's own rule (yes = rests on A′ codings), G02 is a provenance and status statement and does not rest on codings. The tag is harmless, but it does not fit the rule |

The addendum adds a further point: **no tag may be flipped to "no" because of RETURN-02** (see O-R4).

## 6. Section H

H follows from A–G. **It smuggles in no H-D, no H-B, no aggregate and no axiom.** H05 admits the hypotheses only as unresolved alternatives, and H07 forbids using them as axioms. Remaining issues:
- **H-1 (H04):** "three distinct times" can be read as saying the three times always differ. G05 and STATE.md say only that they must not be equated. Suggested wording: "three time concepts that must not be equated".
- **H-2 (H02):** carrying the A′ ACT/NORM separation forward should come with the R-A3 caveat (D05, G04). Act and after-state are the same record, so P1 cannot be tested through A′ observations.
- **H-3:** SCHEMA-r2 §5 ("Support is computed separately per dataset and **never aggregated**") is not carried into H as a "may not". Add it, with G04 extended to cite §5.
- **H-4 (H16), addendum:** must add that RETURN-02's κ = 1.000 is not cross-family reliability, not evidence against correlated error, and not Gate 1 closure (O-R7).

## 7. Prohibitions

- **Absence semantics:** respected. D13, F09 and F10 use NOT-EVIDENCED-IN-CAPTURE. F07 and F13 use UNKNOWN. "Not observed" (D05) and "no PREPARED tag" (A09) are statements about the texts, not claims that something did not occur.
- **Norm → act:** respected. A05, A10 and F09 never infer execution from authorization, and H10 states the rule.
- **M-TIME:** respected. A07, B06 and F06 are labelled recording time or RECORDING_ORDER, E02 keeps text order separate from timeline order, and H12 states the rule. Omitted: Protocol A's L3, "file names are not timestamp sources" (see O-F7).
- **No DDD, architecture or formal model:** respected.

## 8. Omissions

### Frozen audit (non-blocking)
- **O-F1.** SUPPORT-RECONCILIATION defects 3–6 are missing: corrections applied to junk keys; the R04 overwrite; composite targets, so that 2 of the 14 POSSIBLE are artifacts; duplicate and level-mismatched fields. Also missing are the 1ay-n normalization (180 values; 16 corrections; "usable for pairing only under stated conditions") and r3's "NORM target 14 POSSIBLE". B02–B04 rest on these. D14 cites defects 1, 2 and 7 only.
- **O-F2.** Two sealed expectations that failed are missing: 1ba's bias check (the C1 expectation "CONFORMS / UNDETERMINED" failed, CONFORMANCE-RECONCILIATION) and the 1be main-analyst expectation (NO was not confirmed, a prediction miss, READINESS-RECONCILIATION). Both belong in G (provenance).
- **O-F3.** READINESS errata E2 and E4 are missing. E2: S0804's author class and the author classes assigned by path construction are unknown. E4: host membership shows repository access, **not** normative capability. E4 should also become an H "may not".
- **O-F4.** R04 (R-70, "WP-7B-R1 AUTHORIZED TO IMPLEMENT") is cited in F09 but appears in no A item.
- **O-F5.** The methodological conclusion of SUPPORT-RECONCILIATION (INFERENCE; reviewer concurs) is missing: the strict pair-witness method is inadequate for this corpus, and the information lies in norm dynamics. It could be an L2 item with INFERENCE confidence, or a note under D14.
- **O-F6.** The C2 self-certification caveat (CONFORMANCE-REVIEW §5) is missing; see §4.
- **O-F7.** Protocol A's L3 is missing: "file names are not timestamp sources" (M-TIME).

### Required by the addendum (RETURN-02-COMPARISON.json, RETURN-02-REVIEW.md)
**What these files establish.** A context-isolated fresh **Claude** subagent (Opus 5.5) re-coded the 36 records, and a frozen comparator (178e86214) compared the result with CODING.json. Results:
- act.state: agreement 1.000, κ 1.000.
- norm.deontic: agreement 1.000, κ 1.000.
- Membership 36/36: 24 both, 7 NORM-only, 2 ACT-only, 3 neither.
- UNKNOWN share 0.083, which is the 3 no-span records.
- REF-CR = REF-C, because the 7 reviewer corrections target `*.status.*` fields, which the comparator does not apply.
- **"Human decision replaced the non-Claude coder."** Gate 1 proper (a non-Claude coder) "remains **unsatisfied**". The perfect agreement is "**not** evidence against correlated error".
- Supplementary results, **not frozen** and written after opening the results:
  - all status-tag disagreements lie on the SOURCE_FACT ↔ INFERENCE boundary (act.state 29/36, norm.deontic 35/36). They are classified **schema** and change no category;
  - free-text fields match exactly in only 11–23/36. **No semantic comparison was done, and no claim is made** about these fields.
- Open human decisions: (1) close Gate 1 on the within-family basis, or still commission a non-Claude coder; (2) whether SCHEMA-r2 §3 needs a SOURCE_FACT/INFERENCE rule.

**Required changes:**
- **O-R1 (header, G02, H5-derived wording).** "The non-Claude Gate 1 coding (R2) is pending" is no longer accurate. The record copied it correctly from H5-ADOPTED.md, but RETURN-02 shows that the commissioned non-Claude coder was **replaced by human decision** with a fresh Claude subagent. Gate 1 proper is unsatisfied. Whether a non-Claude coder will still be commissioned is **open human decision 1**. Restate the status this way. Define what "R2" denotes from now on, and change "PROVISIONAL, pending R2" to "PROVISIONAL, pending the Gate 1 decision (RETURN-02-REVIEW, open decision 1)". The deviation record (COMMISSIONING.md) is not in this directory; cite RETURN-02-REVIEW.md only.
- **O-R2 (new B item, L1).** Add the frozen within-family result as above, with confidence "frozen comparator; same-family". State verbatim that it "does not show cross-family independence" and that perfect agreement "is **not** evidence against correlated error". R2_DEPENDENT: n/a or no, with the reason that it is itself a check of the coding and not a result built on it.
- **O-R3 (the same B item, or F).** Add the supplementary results, labelled **NOT frozen, descriptive only**: the SOURCE_FACT/INFERENCE boundary disagreements (schema class) and the free-text exact-match range of 11–23/36 with no semantic comparison.
- **O-R4 (qualify the R2_DEPENDENT items by the fields they rest on).** Keep every tag at yes and add field-level notes:
  - **B01** dataset counts (26/31/24): reproduced exactly **within family**; untested across families.
  - **B02, B03, B04 and C00's candidates:** these rest on operation, actor and target fields and on the 1ay-n normalization. RETURN-02 gives **no reproducibility evidence for these fields even within family** (exact match 11–23/36, no semantic comparison).
  - **B05 / D08 / E04:** these rest on R02's categorical act.state, which was reproduced within family.
  - **E02 (R05 operation, a free-text field and an uncertain entry) and E03 (R29/R30):** identical within-family categorical codes **do not resolve** the CODING-REVIEW uncertainty entries. Shared priors would produce the same agreement.
  - **D01, D03, H02, H03, H06, H15:** RETURN-02 does **not** discharge "conditional on R2 reproducing the coding" (H5 §5.2), because H5 frames R2 as the independent (non-Claude) Gate 1 coding. The sources do not say whether it bears on reopening condition 3. Only human decision 1 can settle on what basis the condition counts as met.
- **O-R5 (F12).** Rewrite as follows. Cross-family reproduction is **unknown** (Gate 1 proper unsatisfied). Within-family reproduction is exact on act.state, norm.deontic and membership, and unassessed on free-text fields. The 13 uncertainty entries cover 19 records (P1), and the within-family coder gave them identical categorical codes, which does not resolve them.
- **O-R6 (new F items).** Add open human decision 1 (the basis for Gate 1) and open human decision 2 (a SCHEMA-r2 §3 rule for the SOURCE_FACT/INFERENCE boundary, which can change only by human decision). Point out that the epistemic-status labels, including most of CODING-REVIEW's 25 corrections, lie on this underspecified boundary.
- **O-R7 (H16, or a new "may not").** R4 may not read κ = 1.000 as cross-family reliability, as evidence against correlated error, or as Gate 1 closure. It may not assume that free-text coded fields reproduce.
- **O-R8 (reproducibility claims).** The record makes **no** positive reproducibility claim, so none needs to be withdrawn. The record must still not add one on the strength of RETURN-02 beyond "within-family reproducibility of act.state / norm.deontic / membership on these 36 records is high" (RETURN-02-REVIEW, "Shows").
- **O-R9 (provenance).** Add RETURN-02-COMPARISON.json and RETURN-02-REVIEW.md to the record's sources, with the seal `4bd42c3a5` and the comparator `178e86214`. Note that both files are same-family (G02 still holds).

## Verdict

**PASS_WITH_LIMITATIONS.**
- **Blocking: none.**
- **Required before the record is used for R4:** O-R1..O-R9.
- **Should be fixed:** P1, P2, S-1, T-1, T-2, L-1, L-3 and H-3. The rest are minor.
