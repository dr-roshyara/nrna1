# Gate 1 (SECONDARY, same-family): blind recoding of the 28 sampled rows

| | |
|---|---|
| Status | research record. **SECONDARY, not independent**: the blind coder is a headless Claude, the same model family as the main coder. A non-Claude recoding of the same bundle is still required for Gate 1 proper |
| Main coding | sealed at `080dc6d7b` **before** the blind run (`MAIN-CODING-SEALED.json`, `fa895238…`) |
| Bundle | README + frozen v1.1 manual + the 28 rows verbatim + a blank form. Manifest frozen at `ac1ad57b8` (`BUNDLE-MANIFEST.sha256`). No results, classifications or theory conclusions were included |
| Run | `claude -p` in a non-git scratch directory; tools limited to Read and Write |
| Transcript audit | **3 reads (MANUAL, ROWS, FORM) + 2 writes (CODING, NOTES); 0 paths outside the bundle** |
| Outputs | `BLIND-CODING.json` (`2c8d973e…`), `BLIND-NOTES.md`, `BLIND-TRANSCRIPT.jsonl`; `gate1_agreement.py` gives `AGREEMENT.json` (`2f89b428…`) |
| Log | F-LOG-0137 |

**What Gate 1 measures:** whether the operationalization can be reproduced. **It does not establish that the theory is true.**

## Results

| Item | Raw agreement | Cohen's κ |
|---|---|---|
| operation identification | Jaccard 0.933 (row mean) | per operation: ACCEPT 0.87 · ADOPT-DECISION 1.00 · ANNOTATE 0.84 · APPROVE 0.87 · AUTHORIZE 1.00 · CORRECT-TEXT 0.65 · DEFER 1.00 · WITHDRAW 1.00 |
| authority named | 1.00 | 1.00 |
| V6 authority | 1.00 | 1.00 |
| V2 four-act separation | 0.89 | 0.79 |
| V7 supersession | 0.93 | 0.47 |
| V5 reopening | 0.86 | 0.30 |
| V4 evidence persistence | 0.68 | 0.28 |
| V3 frames | 0.82 | 0.23 |
| V1 legality | 0.75 | 0.19 |
| coverage (row mean) | main 0.89 · blind 0.75 | — |
| **VIOLATED codes** | **main 0 · blind 0** | — |

**Reading the κ values.** The low values for V1, V3, V4, V5 and V7 sit alongside high raw agreement. That is a prevalence effect: almost every code is SUPPORTED or UNTESTABLE. More important, **every disagreement runs in one direction**: the main coder says SUPPORTED where the blind coder says AMBIGUOUS or UNTESTABLE.

## Sources of disagreement (classified from `BLIND-NOTES.md`)
1. **Main-coder bias: cross-row knowledge.** R-58 and R-65 are V1 SUPPORTED in the main coding only because the main coder knew from other rows that the predecessor slice had been accepted. The manual requires coding from the record's own text only.
   - **This is a confirmation bias in the main coding.** The SUPPORTED counts reported in F-LOG-0135 and 0136 are therefore **inflated**. The **absence of violations is robust**, because neither coder found any.
2. **Coding-rule ambiguity, applicability.**
   - V4: the main coder counts it applicable whenever evidence is recorded; the blind coder only when evidence could have changed something (9 rows).
   - V5: when is a withdrawal or reclassification a "reopening" (R-63, R-67, R-75)?
3. **Coding-rule ambiguity, act segmentation.** The blind coder segments many more acts ("closed", "named", "clarified", "settled", "flagged", "blocked", "referred", "stated").
   - **So coverage is coder-dependent:** 0.75 against 0.89. The held-out coverage of 0.708 (F-LOG-0136) is not reproducible without a segmentation rule.
4. **Ontology ambiguity.** "Adopting" an option from a *prepared package* (R-73, R-75, R-92) falls between ADOPT (a PREPARED ruling) and ADOPT-DECISION. The overload of "adopt" is confirmed by an independent reading.
5. **Source ambiguity:**
   - R-38: freeze vs assessment, self-corrected by the source;
   - R-45: "clarified" vs "no responsibility holder changes";
   - R-61: acceptance vs a standing change;
   - R-97: "replacing any broader claim" without naming what it replaces.
6. **A stricter use of v1.1's own bar.** The blind coder applied the new-category evidence bar to R-34 (three new review classes) and R-40 (a new knowledge type), coding V1 AMBIGUOUS. The main coder had passed them.

## Consequences (the theory is not changed)
- **Reproducible:** operation identification, authority, and the four-act separation.
- **Not reproducible as specified:** the applicability of V1, V3, V4 and V5, and act segmentation (hence coverage).
- **Robust across both coders:** zero violations.
- Before the reserve test, freeze **manual r2** with rules for record-only coding, act segmentation and applicability. That is a *coding-rule* revision, declared and separate from the theory. Then **double-code** the reserve (main + blind).
- **Gate 1 proper (non-Claude) is still open.** The same bundle can be handed to a human or non-Claude coder unchanged.
