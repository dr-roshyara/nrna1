# Whole-book review S0100: record of the stage-1 result

*2026-10-19. By the Professor, on the Principal's instruction ("record the stage-1 result accurately"). This file records the result. It does not revise or re-score the completed stage-1 review: the settlement `4c81960ba` stays as committed.*

## 1. The result, as scored

- **Scored by** the Rule Reviewer, commit **`88b6e3890`** (2026-10-19 22:39): `PLANTED-GAP-RESULT.md`, with the key released (`PLANTED-GAP-ANSWER-KEY.md`, sha256 `cd58fe22d367d1b47292a1bfb151a6a8e133db6e29e9f8fb69c82ad3153a454c`, unchanged since sealing) and the copy manifest (`PLANTED-GAP-COPY-MANIFEST.txt`, sha256 `6c164d80c28cfeb9a8d61eb52a42aa380292262770c71f91f7579b6fabf05976`).
- **Detection: 4 of 8 planted items found, including the critical item P1.** The integrity gate passes.
  - **Found:** P1 (Lemma D's hidden premise; P4-B and the integration review; not P4-A), P2 (§9.8.4 overgeneralisation; both P4 reviewers), P6a (§5.7 C3 distortion; both P3 reviewers), P7 (§8.11 C1 settlement-introduced defect; both P5 reviewers and the Professor's PS8-1).
  - **Missed:** P3 (the undefined code "RX-3"), P4 (the undefined term "watermark"), P5 (a whole result removed, with renumbering; CON-0149), P6b (§9.8's citation of §5.7 C3 left unreassessed).
- **Treatment: 3 correct, 1 partial, of the 4 found.**
  - Correct: P2 (N4-01), P6a (P3-1), P7 (P5-1).
  - Partial: P1 (P4-7). The condition was placed in the register instead of the lemma, and it was rated high, not critical.
  - P6b was not treated.
- **Severity misjudgements recorded by the Rule Reviewer:**
  - P1 was rated high where the key says critical. Both P4 falsification tables judged Lemma D "adequate".
  - P2 was rated normal where the key says high.
- **Per reviewer:** P4-A 1 of 5 · P4-B 2 of 5 · P3-A 1 of 1 · P3-B 1 of 1 · P5-A 1 of 1 · P5-B 1 of 1 · integration 1 of 2 · the Professor's step-8 check 1 of 1 in its scope.
  - The Professor's step-9 list also contained P5, as "§9.8 result 6", and did not notice it.
- **Validity:** all detections are blind. The copy matched its manifest at scoring (119 of 119), and it still matches after this record's test runs (re-checked 2026-10-19: 119 of 119 against `PLANTED-GAP-COPY-MANIFEST.txt`). Isolation: 19 passes and 2 B2 breaches, neither touching a plant (`ISOLATION-AUDIT.md`).
- **Bounded conclusion** (the Rule Reviewer's, kept): for these eight items, the review catches what contradicts a source or its surrounding text. It does not catch what is absent: undefined terms, a removed claim, a stale citation elsewhere. Counts, not rates.

## 2. Audit trail

| Stage | Record | Commit |
|---|---|---|
| plan approval, grant, sampling, the Professor's step 8 | `00-access/ACCESS-RECORD.md`, `PROFESSOR/SAMPLING.md`, `PROFESSOR-STEP8-FINDINGS.md` | `84789a7fe` |
| briefs, commissions | `P1…P5/BRIEF.md`, `PROFESSOR/commissions/` | `c22f1801b` |
| blind inventories | `P1…P5/STEP1-INVENTORY-A|B.md` | `155d46c81` |
| part reports | `P1…P5/REPORT-A|B.md` | `853e3030e` |
| isolation audit | `ISOLATION-AUDIT.md` | `853e3030e`, `0471ff7ec`, `4c81960ba` |
| integration report; stage-1 settlement (final) | `INTEGRATION/REPORT.md`; `SETTLEMENT-STAGE1.md` | `4c81960ba` (first draft `f6d19baee`) |
| scoring, key released | `PLANTED-GAP-RESULT.md`, `PLANTED-GAP-ANSWER-KEY.md`, `PLANTED-GAP-COPY-MANIFEST.txt` | `88b6e3890` |

## 3. Planted items and real findings kept apart

The plants exist only in the copy, in three files: 05.07, 08.11 and 09.08. A settlement row that rests on planted text is **not** a defect of the working book unless independent evidence shows the same defect there. The key states the base text of every plant, so this separation needs no comparison of files.

| Settlement row | Rests on plant | Status for stage 2 |
|---|---|---|
| P3-1 (§5.7 C3 supersession rule) | P6a | **Plant only.** The base text states "recognized only when recorded; inferring one is not evidence" (key). No repair; stage 2 confirms the base text. |
| P5-1 (§8.11 C1 L32 "self-declaration was false") | P7 | **The L32 part is plant only.** The base says "so both self-declarations were true". **Real residue, kept:** the plain-language "every self-declared identifier … was true" and CON-0157's "every" overclaim, given the erratum's unchecked declaration (PS8-2; not planted text). Stage 2 checks it against the working book. |
| PS8-1 (the Professor's step 8) | P7 | **Plant only** (the Professor's detection of P7). |
| N4-01 (§9.8.4 "always H1′") | P2 | **Plant only.** The base is an Int scoped "wherever the fold is many-to-one" on a required query. The extra conditions in N4-01 restate what the base already says. |
| P4-7 (Ch. 9 reductions conditional on CE) | P1 (the §9.8 Lemma D part only) | **Partly plant.** The base states the CE premise, step 4 "(premise)" and result 1 "conditional on CE's repair" (key). **Real residue, kept, to be checked in stage 2:** whether §9.9 T1 (CON-0160) carries the condition; P4-B F6 (necessity is definitional) and F8 (candidates overlap by definition) are on unplanted text. |
| I-02 (integration f2) | P1 (only its "chain cut at CE" example for §9.8 Lemma D) | **Real.** The missing primitive set does not rest on the plant. |
| Settlement §5, "§9.8 M5 and result 6 hold" (TT4 check) | P5 (renumbering) | **An error of the settlement, recorded, not revised.** After the plant's renumbering, the copy's "result 6" is the base result 7. The TT4-changed result 6 of the base (the copy's result 5) was not the result the statement checked. Stage 2 re-checks the TT4-changed result in the working book. |
| P5, P3, P4, P6b | missed | **No settlement row.** The base text has none of these defects (key). No repair. |

**Every other row of the settlement rests on unplanted text and stays a real finding**, with its own evidence, severity and resolution. The four critical real findings are among them: P2-1 (§3.1/§3.4), P2-2 (§2.3), P4-1 (§9.1), P4-4 (§9.9).

## 4. What this record does not do

- It does not change the settlement, the score or any severity.
- It does not repair the working book to mirror plants.
- It does not use the released key to improve any row.

The lessons go into the proposed rule changes (`RULEBOOK-REVISION-PROPOSAL-S0100.md`, **not in force**).
