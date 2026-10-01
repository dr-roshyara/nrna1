# Targeted re-review: Empirical Theory Record r1 (frozen)

| | |
|---|---|
| Inputs | Only files in this directory: REVIEW-TASK.md, TASK.md (the r1 task; `TASK-r1.md` named in REVIEW-TASK.md does not exist), REVIEW-r0.json / .md, CHANGELOG-r1.md, EMPIRICAL-THEORY-RECORD.md / .json (r1), RETURN-02-COMPARISON.json, plus the source files I spot-checked: SUPPORT-RECONCILIATION.md, READINESS-RECONCILIATION.md, CONFORMANCE-RECONCILIATION.md, IDENTIFIABILITY.json, H5-ADOPTED.md, SCHEMA-r2-Aprime.md, PROTOCOL-A-RECONCILIATION.md, RECORDS.json, STATE.md |
| Not present (confirmed) | RETURN-02-REVIEW.md, CODING-REVIEW.json, CONFORMANCE-REVIEW.md, ADDENDUM.md. The record discloses all four as absent |
| Scope | Only checks 1–5 of REVIEW-TASK.md |
| Reviewer family | same-family (Claude) |
| **Verdict** | **PASS_WITH_LIMITATIONS**. **Blocking issues: none** |

**Why this verdict.**
- Every REVIEW-r0.json item id is addressed, and none is wrong.
- No R2_DEPENDENT tag is flipped to "no".
- Ids are stable (74 → 89, with 15 new ids), nothing is withdrawn, and the status is still PROVISIONAL.
- Section H gains only two prohibitions (H18, H19). It gains no hypothesis, aggregate or axiom.

The limitations:
- Several r1 changes (P1's 19-record count, B09's coder description and verbatim quotes, the seal and comparator hashes, B10, F15, F16) rest on files that are not in this directory. I could check them only against REVIEW-r0's report of those files. The record discloses this correctly.
- Six minor wording or strength points remain (N1–N6 below).

---

## 1. Coverage

Every id in REVIEW-r0.json is mapped in CHANGELOG-r1.md, and I checked each one in the r1 .md and .json.

| Group | Ids | Result | Notes from checking against the sources |
|---|---|---|---|
| Provenance | P1–P6 | all **ok** | P2 checked against RECORDS.json R06/R07 (section "WP-4C-2 … within ## S0804-L576"), R25 (`## R-90`) and R03 (`## R-91`). P3: the A15 quotes match IDENTIFIABILITY P3/P8 verbatim. P4: every L0 item in A carries a `quoted_via` disclosure, and the items that could not carry one were moved to L1 (A14, A16). P1 could only be checked against REVIEW-r0, because CODING-REVIEW.json is absent; this is disclosed |
| Level | L-1, L-2, L-3 | all **ok** | B05 now says "the rulings themselves declare …", which matches IDENTIFIABILITY P4. E04 is now L2, the same as D08. The snapshot correction matches CONFORMANCE-RECONCILIATION "Corrections adopted" verbatim ("R-72's 'no RED begins' is a snapshot of 08-02") |
| Tags | T-1 to T-5 | all **ok** | H05 and F10 changed from no to yes (the conservative direction). The reason for D10 is corrected. G02 is stated as an exception to the rule. T-5 is respected (§3) |
| Section H | H-1 to H-4 | all **ok** | H18 uses the wording of SCHEMA-r2 §5, "Support is computed separately per dataset and never aggregated", verbatim. H-ok needed no change |
| Frozen omissions | O-F1 to O-F7 | all **ok** | B08 matches SUPPORT-RECONCILIATION defects 3–6 and the 1ay-n row verbatim. D15 matches the methodological conclusion. F14 and G07 match READINESS E2 and E4. G08 matches CONFORMANCE-RECONCILIATION "Bias check" and READINESS. A13 matches RECORDS.json R04. G05 matches PROTOCOL-A L3 |
| RETURN-02 | O-R1 to O-R9 | all **ok**; O-R1 has note N1 | B09's numbers match RETURN-02-COMPARISON.json: 36 external records, none missing or extra; agreement and κ 1.0 for act.state and norm.deontic; membership 24/7/2/3; UNKNOWN share 0.083; 7 unapplied corrections, all `*.status.*`. The confidence string is exactly "frozen comparator; same-family". B10 is labelled NOT frozen. Every O-R4 note is present (B01–B05, C00, D01, D03, D08, E02–E04, H02, H03, H06, H15) |
| Symmetry | S-1, S-2 | both **ok** | E02(b) quotes P4 falsifier_HD verbatim, together with P4's critique that "cited as governing" may itself be the adoption. E03 quotes P5 falsifier_HB "None (H-B is silent)" verbatim |

**No change is weaker than required, apart from note N1.** The CHANGELOG's "Not applied" list (the "successive rulings" remark and REVIEW-r0.md's E01 R-86 remark) has no REVIEW-r0.json id. Leaving those items out is consistent with TASK.md.

## 2. Strength

- **No changed or new item exceeds its source in a way that matters. No strength error was found.**
- **RETURN-02 claims stay within the ceiling** ("within-family reproducibility of act.state / norm.deontic / membership on these 36 records"):
  - B09, F12 and B01 speak only of act.state, norm.deontic and membership;
  - B01 and F12 say "exact", which describes the observed agreement of 1.000. It is O-R5's own wording, and it is not a reliability claim;
  - free-text fields are explicitly marked unassessed (B10, H16), and B02–B04 and C00 get "no reproducibility evidence, even within family";
  - every RETURN-02 mention is labelled within-family / same-family, and no cross-family claim is made anywhere;
  - B05, D08 and E04 say only that R02's categorical act.state was reproduced within family.
- Minor overreach is listed under N2, N3 and N5. None of it raises a level or a confidence.

## 3. Tags

**No tag was flipped to "no" (tag_flips: none).**
- The only tag changes are H05 and F10, both from no to yes.
- The new RETURN-02 items B09 and B10 are `n/a`, which O-R2 allows ("n/a or no"). They are new items, not flips.
- A14 (split off from A09) is yes. A16 (split off from A04) is no, and it is not coding-based.
- One limitation: the r0 record itself is not in this directory, because r1 overwrote the same file names. The check that no other tag changed therefore rests on reading every r1 tag against the tags REVIEW-r0 names (D01, D10, D12, F10, H05, H06, G02) and against the CHANGELOG. No item that rests on A′ codings carries "no".

## 4. Integrity

- **Ids are stable.** I counted A01–A16, B01–B10, C00, D01–D15, E01–E04, F01–F16, G01–G08 and H01–H19: 89 ids in total. That is r0's 74 ids plus the 15 new ids the CHANGELOG lists, and no id was reused or renumbered.
- The content moved from A04 and A09 went to the new ids A16 and A14. The old ids keep the remaining content and a pointer to the new items.
- **Withdrawn:** `[]`. Nothing needed withdrawing (O-R8), so the rule "withdrawn with a reason" is not exercised.
- **Status:** the record is still **PROVISIONAL**: "PROVISIONAL, pending R2 (cross-family Gate 1 coding)", in both the .md header and the JSON `status`. The status line also names open human decision 1.

## 5. Regression

- **No new level error.** The new L0 items (A13, A15) are verbatim spans or second-hand quotes with disclosure. Coder statements and unverifiable quotes went to L1 (A14, A16). B08, B09, B10 and G07 are L1, and D15 is L2 with INFERENCE confidence.
- **No new provenance error that blocks.** Two small points are listed under N4 and N5.
- **Section H:** nothing was smuggled in.
  - H18 *forbids* aggregation, and H19 *forbids* reading membership as capability.
  - H02, H03, H06, H15 and H16 add only conditions and prohibitions.
  - H05 is still "unresolved alternatives", and H07 still forbids axioms.
  - There is no H-D, no H-B, no aggregate and no axiom.

## Nonblocking notes

- **N1 (O-R1, status wording).** O-R1 asks for the status "PROVISIONAL, pending the Gate 1 decision". TASK.md asks to keep "PROVISIONAL pending R2 (cross-family)". r1 follows TASK.md and puts the Gate 1 decision (F15) next to it. The CHANGELOG does not disclose this conflict.
  - The two readings can diverge. If decision 1 closes Gate 1 on the within-family basis, a cross-family R2 may never happen.
  - F15's "Until this is decided, R2 (cross-family) is outstanding" blurs the two.
  - Suggested status: "PROVISIONAL, pending R2 (cross-family) or the Gate 1 decision (F15)".
- **N2 (header and `R2_definition`).** "RETURN-02 … discharges no R2 condition" is flat. REVIEW-r0 O-R4 is flat only for H5 §5.2. On reopening condition 3 it says "the sources do not say", and it leaves sufficiency to human decision 1. D01 states this correctly; the header should match D01.
- **N3 (A15).** "These are the S0815 reports referred to in H5 §3 and READINESS E3" is an identification by the record. H5 §3 and E3 say only "S0815" and do not list which reports they mean. In addition, "per IDENTIFIABILITY P8 all of them go through an act" also covers the P3 items ("BINDING VOCABULARY RULING", "Applied immediately"). P3 calls those act-mediated, so this is harmless, but strictly it is P3, not P8. An L0 item also carries this interpretive sentence.
- **N4 (F14).** "(A4, B1)" are READINESS channel names, but they read like the record ids A04 and B01. This is the same kind of ambiguity that P6 fixed for "L1".
- **N5 (CHANGELOG).** It lists REVIEW-TASK.md as absent. It is present now; this is probably a timing artefact and has no effect on the record. The task file is TASK.md; REVIEW-TASK.md calls it "TASK-r1.md".
- **N6 (B04, not an id).** REVIEW-r0's regularity_check remark that "successive rulings" is loose, because R31 is a recording note, is still open. Its exclusion is disclosed. A10 already marks R-56 as a recording note.
