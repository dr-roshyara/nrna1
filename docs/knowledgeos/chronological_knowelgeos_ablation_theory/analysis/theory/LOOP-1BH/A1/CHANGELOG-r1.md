# CHANGELOG r1: Empirical Theory Record (R3), r0 → r1

**Scope.** Every item in REVIEW-r0.json is applied, and nothing else (TASK.md):
- P1–P6, L-1..L-3, T-1..T-5, H-1..H-4;
- O-F1..O-F7 and O-R1..O-R9;
- the nonblocking symmetry items S-1 and S-2.

**Rules followed:**
- Weaken, never strengthen.
- RETURN-02 is within-family:
  - it enters as an L1 item (B09) with confidence "frozen comparator; same-family";
  - no R2_DEPENDENT tag is flipped to "no";
  - no cross-family claim is made.
- Item ids are stable. **No item is withdrawn** (`withdrawn: []`).
- The status stays **PROVISIONAL, pending R2 (cross-family)**.

Item count: 74 in r0 → 89 in r1. The 15 new items are A13–A16, B08–B10, D15, F14–F16, G07, G08, H18 and H19.

## Source-availability disclosure (affects several entries)
These files are **not present in this directory**:
- RETURN-02-REVIEW.md;
- CONFORMANCE-REVIEW.md;
- CODING-REVIEW.json;
- ADDENDUM.md;
- REVIEW-TASK.md.

The TASK lists RETURN-02-REVIEW.md as an input, but it is absent. Every r1 statement that depends on one of these files is therefore marked "as quoted by REVIEW-r0" or "as reported by REVIEW-r0", or "(not in this directory)". That applies to:
- the RETURN-02 coder description, the verbatim "does not show" / "not evidence" quotes, the seal `4bd42c3a5`, the comparator `178e86214`, the supplementary results and the open decisions;
- the 19-record uncertain count;
- CONFORMANCE-REVIEW §2/§5/§7.

RETURN-02-COMPARISON.json **is** present. All RETURN-02 numbers (36/36, agreement and κ 1.000, 24/7/2/3, 0.083, the 7 `*.status.*` unapplied corrections) were checked against it.

## Provenance errors
| id | Change |
|---|---|
| P1 | B01 and F12: "13 records flagged uncertain" → "13 entries covering 19 records (R15–R21 covers 7)", with the 19 ids listed. Count per REVIEW-r0 P1, because CODING-REVIEW.json is not in this directory |
| P2 | A08: the "Then R-90 itself withdrawn …" span is relabelled as session log S0804-L576, section "WP-4C-2 — commission closed, handed to the domain owner" (RECORDS.json R06/R07), and not the R-90 row. R25 is labelled as the R-90 register row and R03 as the R-91 row. R07 is added to the sources |
| P3 | A11 now discloses that its list is IDENTIFIABILITY P2/P6's, not H5 §3's, and that its R-86 and S0804 entries come from P2. The **new A15** holds the S0815 execution/remediation reports ("BINDING VOCABULARY RULING", "Applied immediately", "Recommended as its own work item", "E-2 is CORRECTED (seq 18)", "corrected, not defended"), quoted from IDENTIFIABILITY P3/P8. F04 is rewritten to map H5 §3's list onto A11 and A15 |
| P4 | A01–A07, A09 and A11 stay at L0 and each gains a `quoted_via` disclosure: "as quoted by CONFORMANCE-ANSWER.md / IDENTIFIABILITY.json / CODING.json / CONFORMANCE-CASE.json", or "as recorded in RECORDS.json" for span text. Items that cannot carry that disclosure move to L1: **A09**'s coder statements ("heading names the ARB CHIEF", "no PREPARED tag") go to the **new A14** (L1, R2 = yes), and **A04**'s R-79 heading, whose quoting file CODING-REVIEW.json is absent, goes to the **new A16** (L1, "not verifiable in this directory"). **H01** now cites the actual L0 set (A01–A13, A15, G04, G06) and the disclosure requirement. That is a consequential edit |
| P5 | E01: the phrase "under the primary text reading" is removed. E01 now uses the CONFORMANCE-RECONCILIATION wording ("NON-CONFORMS, moderate, text-robust"; "C1 is the first discriminating observation of type (ii)"), and the sources add CONFORMANCE-REVIEW §6, §7 (marked not in this directory) |
| P6 | B06: "(L1)" → "(Protocol A limitation L1; not epistemic level L1)" |

## Level errors
| id | Change |
|---|---|
| L-1 | B05 is reworded: "the rulings themselves declare that a Chief-prepared ruling gains force …", with the R-86 quote. It adds that force is "observed" only through these declarations (IDENTIFIABILITY P4). STATE.md is moved after P4 in the sources |
| L-2 | E04 changes from L1 to **L2**, matching D08. Both carry the same IDENTIFIABILITY P4 reviewed-analysis content. D08 points to E04 as L2 |
| L-3 | A01 and E01 add the adopted correction (CONFORMANCE-RECONCILIATION "Corrections adopted"; CONFORMANCE-REVIEW §2 per REVIEW-r0): R-72's "no RED begins" is a snapshot of 08-02, the standing norm is the conditional, and it is not a standing ban |

## Tag errors
| id | Change |
|---|---|
| T-1 | H05: no → **yes**, because it is derived from D01 (H5 reopening condition 3), like H06 |
| T-2 | F10: no → **yes**, to align with D12 (the same P8 content). This is the conservative direction. D12's reason now states that P8 is mainly a corpus-window limitation |
| T-3 | D10 stays yes. The reason is corrected to "kept yes conservatively: P6 rests mainly on uncoded execution reports (A11) and on the hypotheses' text, not on A′ codings" |
| T-4 | G02 stays yes as a **stated exception** to the rule: it is a status statement, but its content changes with the Gate 1 decision / R2. The rule text in the header and JSON now says that exceptions carry their reason, and defines `n/a` |
| T-5 | **No R2_DEPENDENT tag is flipped to "no"**, whether because of RETURN-02 or otherwise. The only tag changes are no → yes (H05, F10). The new RETURN-02 items B09 and B10 are `n/a` (they are checks of the coding), as REVIEW-r0 O-R2 allows. This is stated in the header and in `r2_dependent_rule` |

## Section H issues
| id | Change |
|---|---|
| H-1 | H04: "three distinct times" → "three time concepts that must not be equated (not a claim that they always differ)". G05 gets the same phrasing |
| H-2 | H02 adds the R-A3 caveat: act and after-state are the same record, so P1 cannot be tested through A′ (D05, G04 added to the sources) |
| H-3 | G04 adds the quote from SCHEMA-r2 §5: "Support is computed separately per dataset and never aggregated." This was verified in SCHEMA-r2-Aprime.md. **New H18** (MAY_NOT): aggregated support across the ACT and NORM datasets |
| H-4 | H16 extended: κ = 1.000 is not cross-family reliability, not evidence against correlated error and not Gate 1 closure, and free-text field reproducibility may not be assumed, even within family |

## Omissions: frozen audit
| id | Change |
|---|---|
| O-F1 | **New B08**: defects 3–6, the 1ay-n normalization (180 values; 16 corrections; "Usable for pairing only under stated conditions") and r3's NORM target 14 POSSIBLE (2 of them artifacts; r3 EXPLORATORY). B02–B04 now say that they rest on B08, and D14 points to B08 |
| O-F2 | **New G08**: the 1ba bias-check expectation failed, and the 1be main-analyst expectation NO was not confirmed (a prediction miss) |
| O-F3 | **New F14** (E2: author classes unknown, including S0804's). **New G07** (E4: membership shows access, not normative capability). **New H19** (MAY_NOT: host membership as normative capability) |
| O-F4 | **New A13**: the R-70 span (RECORDS.json R04) "WP-7B-R1 AUTHORIZED TO IMPLEMENT — Execution Governance · Authorization · ARB." F09 cross-references A13 |
| O-F5 | **New D15** (L2, confidence INFERENCE; the reviewer concurs on the substance): the strict pair-witness method is inadequate for this corpus, and the signal lies in transitions (norm dynamics). D14 points to D15 |
| O-F6 | E02 confound: C2 relies on R-89's self-certified "seven checks", which were not independently verified (CONFORMANCE-REVIEW §5, as reported by REVIEW-r0; the file is not in this directory) |
| O-F7 | G05: adds Protocol A L3, "file names are not timestamp sources", with the plan-file instance. The header conventions now list it. PROTOCOL-A-RECONCILIATION.md L3 is added to the sources |

## Omissions: RETURN-02 (required before R4 use)
| id | Change |
|---|---|
| O-R1 | Header and JSON `status`: "PROVISIONAL, pending R2 (cross-family Gate 1 coding)". Gate 1 proper is unsatisfied, and commissioning a non-Claude coder is open human decision 1 (F15). A new **"R2 means"** row / `R2_definition` defines R2 as the independent, cross-family (non-Claude) Gate 1 coding framed by H5. RETURN-02 is the within-family replacement, and it is not R2. G02 is rewritten: the coder was replaced by human decision, and Gate 1 is unsatisfied. F12 and H02 are rewritten the same way. COMMISSIONING.md is not cited as a source; it is listed as absent. RETURN-02-REVIEW.md is cited via REVIEW-r0 |
| O-R2 | **New B09** (L1, confidence "frozen comparator; same-family", R2 = n/a): 36/36 ids; act.state 1.000 / κ 1.000; norm.deontic 1.000 / κ 1.000; membership 24/7/2/3; UNKNOWN 0.083 (3 no-span records); REF-CR = REF-C, because the 7 `*.status.*` corrections are unapplied. Two quotes are added verbatim, *as quoted by REVIEW-r0*: "does not show cross-family independence" and "is not evidence against correlated error". The comparator's own label is also added: "Claude references are NOT ground truth" |
| O-R3 | **New B10**, labelled **NOT frozen, descriptive only**: the SOURCE_FACT ↔ INFERENCE disagreements (act.state 29/36, norm.deontic 35/36; schema class; no category changed) and free-text exact match 11–23/36, with no semantic comparison and no claim |
| O-R4 | Every tag is kept at yes, and field-level `return02_note` entries are added. **B01**: membership reproduced within family; cross-family untested. **B02, B03, B04, C00**: operation/actor/target fields and the 1ay-n normalization; no reproducibility evidence, even within family. **B05, D08, E04**: R02's categorical act.state, reproduced within family. **E02** (R05 operation) and **E03** (R29/R30): identical within-family codes do not resolve the uncertainty entries. **D01, D03, H02, H03, H06, H15**: RETURN-02 does not discharge the H5 §5.2 R2 condition; whether a within-family check suffices is human decision 1; the sources do not say whether it bears on reopening condition 3 |
| O-R5 | F12 is rewritten: cross-family reproduction is UNKNOWN (Gate 1 unsatisfied); within-family reproduction is exact on act.state, norm.deontic and membership; free-text fields are unassessed; the 13 entries (19 records) received identical within-family codes, which does not resolve them |
| O-R6 | **New F15** (open decision 1: the basis for Gate 1). **New F16** (open decision 2: a SCHEMA-r2 §3 SOURCE_FACT/INFERENCE rule, changeable only by human decision). F16 notes that the status labels, including most of the 25 corrections ("mostly epistemic status", SUPPORT-RECONCILIATION), lie on this boundary |
| O-R7 | H16 is extended (see H-4). H15 adds: RETURN-02 does not discharge the R2 condition on D1 |
| O-R8 | Nothing is withdrawn, because r0 made no positive reproducibility claim. The header row "Reproducibility" and JSON `reproducibility_ceiling` cap any such statement at "within-family reproducibility of act.state / norm.deontic / membership on these 36 records is high". B09 repeats the cap |
| O-R9 | RETURN-02-COMPARISON.json and RETURN-02-REVIEW.md are added to the header sources and JSON `sources_added_r1`, with seal `4bd42c3a5` and comparator `178e86214` (as reported by REVIEW-r0). Both are noted as same-family, and G02 still holds. RETURN-02-REVIEW.md is disclosed as absent from this directory |

## Nonblocking (symmetry)
| id | Change |
|---|---|
| S-1 | E02 direction now records both directions, each conditional and unresolved: (a) against H-B if R-89 took force; (b) a possible falsifier of H-D's status clause (IDENTIFIABILITY P4 falsifier_HD, quoted, with P4's caveat that the Authority's citation may itself be the adoption). P4 is added to E02's sources, and E04 cross-references E02(b) |
| S-2 | E03 direction adds "H-B is silent (P5: falsifier_HB 'None (H-B is silent)')" |

## Not applied (outside the TASK's list)
These REVIEW-r0 remarks were **not** applied, because TASK.md restricts r1 to the listed ids:
- the `regularity_check` remark that "successive rulings" is loose for R31 (a recording note);
- REVIEW-r0.md §4's E01 remark about R-86's "bear on the production producer path … not on batch 7's execution". It is in the md only, with no JSON id.

The H-ok entry and the `prohibitions` block in REVIEW-r0.json need no change, except the M_TIME omission, which is O-F7 above.
