# Independent review: ACT / NORM coding (CODING.json, NOTES.md) against SCHEMA-r2

Inputs used: REVIEW-TASK.md, SCHEMA-r2.md, TASK.md, RECORDS.json, SOURCES.md, CODING.json, NOTES.md. No other material was consulted and no code was run.

## Summary

- **R-A1 (no ACT inferred from a NORM):** no violations. Every "authorized to / may / must not / does not begin" span has its ACT coded either as the authorizing act (R04, R32, R34, R36, under R-A3) or as NONE (R10, R12, R13, R14, R33). None of them codes the governed act as having happened.
- **R-A2:** no violations. There are two REFUSED/WITHDRAWN codes (R06, R29), and both have an identified proposal in the section. For R06 the coder's premise draws on the wrong section; a premise from the correct section exists and is quoted below.
- **R-A3:** no violations. Every register-row span that performs a norm-making act is coded ACT_OCCURRED, and each also carries its stated content as a NORM.
- **Independence:** no suppression between the two components. The only NORM=NONE values on ACT_OCCURRED records (R11, R29) are right on the span's content. R12 loses a second deontic *inside* the NORM component; that is a schema limitation, not suppression.
- **Epistemic status:** 7 fields labelled SOURCE_FACT are really INFERENCE (R01, R10, R12 ×2, R13, R14, R18). No INFERENCE lacks a premise, but 6 records rest their premises on material from **other** sections when the record's own section gives enough (R04, R06, R07, R15–R21).
- **Quotes:** all are verbatim fragments from the span or its section. Six act quotes (R15, R16, R17, R19, R20, R21) join non-adjacent fragments with an inserted "...", so they are not verbatim as a unit.
- **UNKNOWN:** used correctly. It appears for R24, R28 and R35 (no span), and for the unnamed actors in R06 and R25, where the source uses the passive and never names anyone. It is never used where the span decides.
- **No ACT.state or NORM.deontic value changes.** All corrections are to actors, premises, statuses or quotes. The dataset counts therefore match the coder's.

## R-A2 evidence (each REFUSED / WITHDRAWN)

| Record | State | Identified proposal / attempt (verbatim, markdown emphasis stripped) | Verdict |
|---|---|---|---|
| R06 | ACT_WITHDRAWN (R-90) | Section `WP-4C-2 — commission closed…`: "a ruling was put forward under a number already holding WP-4C-1's authorization; filed as R-90 with the collision recorded. Then R-90 itself withdrawn before adoption" | Satisfied. The coder's premise ("R-90 was filed as a PREPARED ruling") comes from the separate `## R-90` section, so it should be replaced by the in-section quote above. |
| R29 | ACT_REFUSED (supersede) | Section `R-94`: "Of the three engineering-presented outcomes (retain · annotate · supersede), ANNOTATE is adopted." | Satisfied. Supersede was one of the engineering-presented options, and the span is its issued ground for rejection. Whether an option that was not chosen counts as "refused" is listed as uncertain. |

## R-A3 check (register rows that perform a norm-making act)

| Record | Row | Act coded | NORM from content | OK |
|---|---|---|---|---|
| R01 | R-81 provenance annotation | ACT_OCCURRED decline/determination | PROHIBITED | yes |
| R02 | R-86 | ACT_OCCURRED adopt | STATUS_CONSTRAINT | yes |
| R03 | R-91 HELD annotation | ACT_OCCURRED hold | INELIGIBLE | yes |
| R04 | R-70 | ACT_OCCURRED authorize | AUTHORIZED | yes |
| R05 | R-89 (PREPARED) | ACT_OCCURRED prepared issuance | AUTHORIZED | yes; the operation choice is uncertain |
| R09 | R-60 recording note | ACT_OCCURRED file as ruling | STATUS_CONSTRAINT | yes |
| R23 | R-41 | ACT_OCCURRED adopt | REQUIRED | yes |
| R25 | R-90 withdrawal annotation | ACT_OCCURRED retire number | PROHIBITED | yes |
| R26 | R-77 | ACT_OCCURRED rule | STATUS_CONSTRAINT | yes |
| R27 | R-83 | ACT_OCCURRED apply §12 | STATUS_CONSTRAINT | yes (uncertain) |
| R30 | R-94 | ACT_OCCURRED adopt ANNOTATE | REQUIRED | yes |
| R32 | R-58 | ACT_OCCURRED authorize | AUTHORIZED | yes |
| R34 | R-65 | ACT_OCCURRED authorize | AUTHORIZED | yes |
| R36 | R-86 | ACT_OCCURRED authorize | AUTHORIZED | yes |

Spans that come from norm-making rows but are **not** the act clause (R10, R12, R13, R14, R31, R33) are coded ACT = NONE. That is consistent with the rule, which assigns the act to the span that records it. R13 is the borderline case.

## Per-record verdicts

| Rec | Coder ACT / NORM | Reviewer | Notes |
|---|---|---|---|
| R01 | OCCURRED / PROHIBITED | agree | Status error: norm.about_actor. The parenthetical "(here, rulings issued by the ARB Chief)" is INFERENCE. The coder's R-A2 claim that "nobody had proposed" the self-favouring reading is questionable: the section quotes "BATCH 7 IS RELEASED", and the original R-81 text reads "Batch 7 partially released". Value kept; listed as uncertain. |
| R02 | OCCURRED adopt / STATUS_CONSTRAINT | agree | — |
| R03 | OCCURRED hold / INELIGIBLE | agree | REFUSED (adopt) is arguable; uncertain. |
| R04 | OCCURRED authorize / AUTHORIZED | agree on states | norm.about_actor "engineering" rests on R-47/R-65, which are other sections. R-70 names no implementer, so the value should be null / NOT_EVIDENCED. |
| R05 | OCCURRED prepared issuance / AUTHORIZED | agree | Uncertain whether R-A3 wants the operation to be "authorize". |
| R06 | WITHDRAWN / STATUS_CONSTRAINT | agree on states | The R-A2 premise and norm.stated_by use the R-90 row. In-section: "the Authority". |
| R07 | OCCURRED rule / STATUS_CONSTRAINT | agree on states | act.actor and norm.stated_by "Decision Authority" come from the R-90 row. The span says "the Authority", which is a SOURCE_FACT. |
| R08 | OCCURRED / STATUS_CONSTRAINT | agree | The norm could also be NONE; uncertain. |
| R09 | OCCURRED file / STATUS_CONSTRAINT | agree | — |
| R10 | NONE / PROHIBITED | agree | Status error: norm.stated_by "ARB (R-72)" comes from the row heading, so it is INFERENCE. |
| R11 | OCCURRED commit / NONE | agree | NORM NONE is on content, not suppression. |
| R12 | NONE / PROHIBITED | agree | Status errors: stated_by (the span sits in "TWO DISTINCTIONS RECORDED", outside "the Board's own words") and about_operation ("implementation work" comes from the directive clause). The PERMITTED half has no field of its own; uncertain. |
| R13 | NONE / AUTHORIZED | agree | Status error: stated_by comes from the R-47 heading. |
| R14 | NONE / UNAUTHORIZED | agree | Status error: stated_by comes from the R-47 heading. |
| R15 | OCCURRED classify / REQUIRED | agree on states | The act quote is elided. The norm premises rely on the R-36 row, which is another section; the section heading "(adopted by R-36)" is sufficient. |
| R16 | OCCURRED / REQUIRED | agree on states | Elided act quote; the same premise issue as R15. |
| R17 | OCCURRED / REQUIRED | agree on states | Elided act quote; the same premise issue as R15. |
| R18 | OCCURRED / REQUIRED | agree on states | Status/scope error: norm.about_target adds the R-36 wording ("all architectural recommendations/reviews") under SOURCE_FACT. The span says "all AI findings, not only Completion Reviews". |
| R19 | OCCURRED / STATUS_CONSTRAINT | agree on states | Elided act quote; the same premise issue as R15. |
| R20 | OCCURRED / STATUS_CONSTRAINT | agree on states | Elided act quote; the same premise issue as R15. |
| R21 | OCCURRED / STATUS_CONSTRAINT | agree on states | Elided act quote; the same premise issue as R15. |
| R22 | NONE / PROHIBITED | agree | Fields are populated under NONE, which is a style inconsistency with R10/R13. Harmless. |
| R23 | OCCURRED adopt / REQUIRED | agree | The actor "Principal Architect (instructing)" treats the source of the instruction as the adopter. It is labelled INFERENCE, so acceptable. |
| R24 | UNKNOWN / UNKNOWN | agree | — |
| R25 | OCCURRED retire / PROHIBITED | agree | Uncertain: "IS RETIRED" may be a performative act or a status report. |
| R26 | OCCURRED rule / STATUS_CONSTRAINT | agree | — |
| R27 | OCCURRED apply / STATUS_CONSTRAINT | agree | Uncertain: NONE is defensible, since the span sits under "WHAT IT DELIBERATELY DOES NOT CHANGE". |
| R28 | UNKNOWN / UNKNOWN | agree | — |
| R29 | REFUSED / NONE | agree | Uncertain: REFUSED vs NONE. |
| R30 | OCCURRED adopt / REQUIRED | agree | — |
| R31 | NONE / UNAUTHORIZED | agree | Uncertain: the norm could also be STATUS_CONSTRAINT ("a SEPARATE act"). |
| R32 | OCCURRED / AUTHORIZED | agree | — |
| R33 | NONE / UNAUTHORIZED | agree | — |
| R34 | OCCURRED / AUTHORIZED | agree | — |
| R35 | UNKNOWN / UNKNOWN | agree | — |
| R36 | OCCURRED / AUTHORIZED | agree | — |

## Corrections (every disagreement)

| # | Record | Field | Coder | Reviewer | Class |
|---|---|---|---|---|---|
| 1 | R04 | norm.about_actor (+status) | "engineering" · INFERENCE (premise from R-47/R-65) | null · NOT_EVIDENCED | evidence scope |
| 2 | R06 | act.status.operation (premise) | "R-90 was filed as a PREPARED ruling awaiting adoption" | In-section premise: "a ruling was put forward under a number already holding WP-4C-1's authorization; filed as R-90 with the collision recorded" | evidence scope |
| 3 | R06 | norm.stated_by (+status) | "Decision Authority" · INFERENCE (R-90 row) | "the Authority" · SOURCE_FACT | evidence scope |
| 4 | R07 | act.actor (+status) | "Decision Authority" · INFERENCE (R-90 row) | "the Authority" · SOURCE_FACT | evidence scope |
| 5 | R07 | norm.stated_by (+status) | "Decision Authority" · INFERENCE | "the Authority" · SOURCE_FACT | evidence scope |
| 6 | R01 | norm.status.about_actor | SOURCE_FACT | "any self-issued act" SOURCE_FACT; "(here, rulings issued by the ARB Chief)" INFERENCE (premise: the annotation concerns the five rulings "filed by the Chief") | coding |
| 7 | R10 | norm.status.stated_by | SOURCE_FACT | INFERENCE (premise: R-72 heading "Authorization · ARB") | coding |
| 8 | R12 | norm.status.stated_by | SOURCE_FACT | INFERENCE (premise: R-79 heading "Directive · ARB"; the span is in "TWO DISTINCTIONS RECORDED", not the Board's quoted directive) | coding |
| 9 | R12 | norm.status.about_operation | SOURCE_FACT | INFERENCE ("implementation work" and "plan = PERMITTED" come from the directive "NO IMPLEMENTATION WORK FOR WP-8 SHALL COMMENCE") | coding |
| 10 | R13 | norm.status.stated_by | SOURCE_FACT | INFERENCE (premise: R-47 heading "Authorization · ARB") | coding |
| 11 | R14 | norm.status.stated_by | SOURCE_FACT | INFERENCE (premise: R-47 heading) | coding |
| 12 | R18 | norm.about_target (+status) | "AI findings (R-36 broadened this to all architectural recommendations/reviews)" · SOURCE_FACT | "all AI findings, not only Completion Reviews" · SOURCE_FACT | evidence scope |
| 13 | R15–R21 | norm.status premises (deontic, about_actor, stated_by) | cite the contents of the R-36 row | cite the in-section heading "(adopted by R-36)" plus the row's own recommendation cell. Values unchanged. | evidence scope |
| 14 | R15 | act.quote | "\| 2 \| Reuse Before Create \| A \| ... \| PROMOTE — one line in OPERATING_INSTRUCTIONS \|" | "PROMOTE — one line in OPERATING_INSTRUCTIONS" | wording |
| 15 | R16 | act.quote | "\| 3 \| Ownership Drives Reuse \| A \| ... \| PROMOTE — one line; the strongest new principle" | "PROMOTE — one line; the strongest new principle" | wording |
| 16 | R17 | act.quote | "\| 9 \| Surface gaps / Deferred ≠ skipped \| A \| ... \| PROMOTE — one line" | "PROMOTE — one line: gaps and deferrals are recorded with their resumption trigger, never silently absorbed" | wording |
| 17 | R19 | act.quote | "\| 5 \| Registration ≠ Delivery \| C (+ generalization → D) \| ... \| Stays in ADR-MP-06 (Messaging-scoped)." | "Stays in ADR-MP-06 (Messaging-scoped)." | wording |
| 18 | R20 | act.quote | "\| 14 \| Strangler reconstitution (…) \| D \| ... \| Remains Candidate" | "Remains Candidate; documented in PB-004 retro §9 + Handover §8" | wording |
| 19 | R21 | act.quote | "\| 17 \| `ChallengeResolvedIntegration` carrier \| D \| ... \| Remains candidate; replaced only on repeated evidence (ER-02) \|" | "Remains candidate; replaced only on repeated evidence (ER-02)" | wording |

For corrections 14–19: if the category cell is also needed as evidence, give it as a second verbatim fragment (for example "| 2 | Reuse Before Create | A |") rather than splicing fragments together with "...".

## Datasets under the reviewer's coding

No state or deontic value changed, so the counts equal the coder's.

- **ACT dataset** (act.state ∉ {NONE, UNKNOWN}), **n = 26**: R01, R02, R03, R04, R05, R06, R07, R08, R09, R11, R15, R16, R17, R18, R19, R20, R21, R23, R25, R26, R27, R29, R30, R32, R34, R36.
- **NORM dataset** (norm.deontic ∉ {NONE, UNKNOWN}), **n = 31**: R01–R10, R12–R23, R25, R26, R27, R30, R31, R32, R33, R34, R36.
- **Overlap, n = 24**: the ACT set minus R11 and R29.
- ACT = NONE: R10, R12, R13, R14, R22, R31, R33. NORM = NONE: R11, R29. UNKNOWN on both components: R24, R28, R35. These are counted and excluded (R-A4).

**Sensitivity to the uncertain calls:**

| Change | ACT n | NORM n | Overlap n |
|---|---|---|---|
| R27 → NONE | 25 | 31 | 23 |
| R25 → NONE | 25 | 31 | 23 |
| R29 → NONE | 25 | 31 | 24 |
| R13 → ACT_OCCURRED (authorize) | 27 | 31 | 25 |
| R08 NORM → NONE | 26 | 30 | 23 |
| R06 and R07 treated as one record (§1: "Events resolving to one span are one record") | 25 | 30 | 23 |

## Uncertain records

- **R01:** ACT_OCCURRED vs ACT_REFUSED. The section may contain the self-favouring reading as a proposal: "BATCH 7 IS RELEASED", and R-81's original "Batch 7 partially released".
- **R03:** HOLD (ACT_OCCURRED) vs ACT_REFUSED of adoption. The proposal is identified: R-91 is PREPARED ("One sentence of confirmation adopts it").
- **R05:** R-A3 operation: a prepared issuance vs "authorize".
- **R06 / R07:** identical span split by operation. The norm is counted twice, and §1 arguably requires a single record.
- **R08:** reported heading status: STATUS_CONSTRAINT vs NONE.
- **R12:** two deontics (PERMITTED plan / PROHIBITED start) share one field.
- **R13:** a scope clause of an authorizing row: NONE vs ACT_OCCURRED.
- **R15–R21:** whether a matrix row is itself an act, and whether its norm is binding without the R-36 row.
- **R25:** "IS RETIRED" as a performative act vs a status report.
- **R27:** "APPLIES §12" as an act vs a characterization of the ruling (NONE).
- **R29:** an option that was presented but not chosen, treated as ACT_REFUSED vs NONE.
- **R31:** UNAUTHORIZED vs STATUS_CONSTRAINT. The span is a recording note, "not part of this ruling".

## Observations not counted as disagreements

- RECORDS.json spans have markdown emphasis stripped compared with SOURCES.md (for example `**PROMOTE**` in R15–R17, and the italics in R06/R07). SCHEMA §1 requires that stripping to be disclosed, and it is not. This is a defect in the records, not in the coding.
- R22 and R31 fill operation, target and quote under ACT = NONE, while R10, R13 and others leave them null. This is inconsistent but harmless.
- R11 (a RED commit dated 2026-08-03) against R-72 ("no RED begins") and R-77 (WP-4B blocked): the coder correctly noted this tension and did not code it.
