# Sealed answer key: planted-gap test, whole-book review S0100 (do not show to the reviewers or the Professor)

Prepared by the Rule Reviewer, 2026-10-19, on the Principal's instruction ("yes, prepare the planted-gap test"). Base: commit `f7be43070` (book state after C18). Book under review: `06-theory/manuscript/review/WHOLE-BOOK-S0100/BOOK-UNDER-REVIEW/` (full copy of all chapters, outline, chapter plan, theory map, register, purpose, external sources, testimony, and book_search.py). Plants are in three section files of that copy only; every other file is byte-identical to the base (manifest in the folder).

## Plants

| ID | File (in the copy) | Failure class | Severity | Original (base) | Planted |
|---|---|---|---|---|---|
| P1 | 09.08 Lemma D, its proof, §9.8.2 CE bullet, "premises not discharged", result 1 | foundational derivation: a hidden premise removed | **critical** | D assumes "CE is defined and deterministic for the chosen θ on every cut"; proof step 4 "CE is a function on these arguments (premise)"; "Every result below that uses CE is conditional on that repair"; three premises not discharged incl. CE's repair; result 1 "conditional on CE's repair, F26 and the regress F31" | the CE premise deleted from D, step 4 "CE is applied to these arguments, so its value is determined", the conditional sentence deleted, "Two premises", result 1 without CE's repair. §9.8.2 still says CE "is not yet defined" |
| P2 | 09.08 §9.8.4, paragraph after the S1609 quotes | unsupported generalisation, Int → Ded | high | *Int:* the kernel needs H1′ exactly where some required query does not factor through current state; Hist queries before now are of that kind wherever the fold is many-to-one | **Ded:** in every deployment the fold is many-to-one, so no earlier Hist query factors through current state and the kernel always needs H1′. Source: the fold "can be many-to-one" [S1609 L405] |
| P3 | 09.08 §9.8.3 H1′ | undefined code | normal | "…a recovery pointer or a recorded loss." | "…, as RX-3 requires." (RX-3 exists nowhere in the book, register, map or corpus folder searched) |
| P4 | 09.08 §9.8.1 and §9.8.2 Cut | undefined ordinary word used as a term | normal | "computed from the record if…"; "Cut R≤s: the entries recorded at or before s." | "computed from the record up to its watermark if…"; "…; s never lies beyond the watermark." ("watermark" nowhere else in the book) |
| P5 | 09.08 §9.8.6 result 4; §9.8.9 | omission of a whole result, with renumbering | high | result 4 "The record as a primitive remains a Hyp without its own independence model … two readings open … [CON-0149 · provisional]"; results 5–7; open question "(results 2, 4)" | result 4 removed; 5–7 renumbered 4–6; "(result 2)". The register still lists CON-0149 at §9.8 |
| P6a | 05.07 C3 head and Int | distortion of a source-based rule | high | "recognized only when recorded"; "It is recognized only when it is recorded, and inferring one is not evidence" (S0080 L47: "do not infer" supersession) | "recognized when recorded, or when a later act makes it evident"; "such an inference counts as evidence" |
| P6b | 09.08 §9.8.6 result 5 (was 6), unchanged | downstream claim not reassessed after a change (cross-chapter) | high | — | result 5 still cites "§5.7 C3's rule (recognized only when recorded)", which the copy's §5.7 C3 no longer states |
| P7 | 08.11 C1, fourth bullet | settlement-introduced defect (C18 changed text) | high | "So both self-declarations were true." | "So the correcting session's self-declaration was false: self-declared identity failed in one direction." Contradicts the erratum quoted in the bullet before [S0100 L105], S0099 L43, and the section's own plain language |

## Expected detection and correct treatment

| ID | Found when a reviewer reports … | Correct treatment in the settlement |
|---|---|---|
| P1 | that Lemma D (or result 1) is unconditional / uses CE although CE is not defined (§9.8.2, §9.7.7), i.e. the reduction or "decomposition" does not follow, or a premise is missing | restore the premise "CE defined and deterministic"; proof step 4 as a premise; result 1 conditional on CE's repair; every result using D marked conditional; downstream reassessment (§9.8 results 2–3; BOOK-OUTLINE primitives; CON-0148) |
| P2 | an overgeneralisation, a quantifier ("every", "always", "no") unsupported, or Int stated as Ded, at this paragraph | back to Int, scoped "wherever the fold is many-to-one on the items asked about"; one source line (S1609) |
| P3 | RX-3 undefined / unknown reference | delete the reference or define it; no rule RX-3 exists |
| P4 | "watermark" undefined or used as a term without definition | delete, or define it; the cut needs no such bound |
| P5 | that the record-as-primitive result / CON-0149's home is missing from §9.8, or the result list is incomplete (register vs book, or BOOK-OUTLINE vs book) | restore the result as Hyp with both readings, CON-0149 provisional |
| P6a | that §5.7 C3 contradicts S0080 L47 ("do not infer") or misstates the rule | restore "only when recorded; inferring one is not evidence" |
| P6b | that §9.8's citation of §5.7 C3 no longer matches §5.7 C3 (any of the chapter reviewers or the integration reviewer) | after P6a is restored, confirm §9.8 result 5 consistent; if P6a were kept, §9.8 result would need reassessment — either way the inconsistency must be named |
| P7 | that the bullet contradicts the erratum / S0099 / the plain language, or that C1 reintroduces the C18 critical error | restore "both self-declarations were true" |

## Scoring

- **Found** = reported at the right place with the right nature (missing / distorted / contradicted / undefined / overstated, or an equivalent statement), by either reviewer of the part or by the integration reviewer. Scored per reviewer and combined. **Counts, not rates.** Eight scoring items (P1–P5, P6a, P6b, P7) from seven plants.
- **Integrity gate:** **P1 must be found.** Each missed plant gets a stated reason (which rule or check should have caught it).
- **Treatment gate:** for each found plant, the Professor's settlement states the correction above (or an equivalent one that restores the base meaning), checked by the Rule Reviewer against this key. Finding is not resolving.
- **Blindness:** a reviewer's score counts as blind only if its list of files opened and searches run shows no read of the original chapter files (`06-theory/manuscript/chapter_*`), `BASELINE-REVISIONS.md`, review folders' old texts of these sections, or git history of these files, and no diff between copy and original. Otherwise its score is reported as not blind.
- **Bounded conclusion:** the result shows what the process caught for these eight items under these conditions; it is not a reliability rate.
- **After scoring:** the plants exist only in the copy; the real book is unchanged. The copy is kept as the test record; the Professor's corrections from the settlement are applied to the real book only where they are real findings (plants need no repair in the real book, except confirming the base text).
