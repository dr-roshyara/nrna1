# 1am-2: reconciliation (main analyst). Compact task report

| | |
|---|---|
| Question | Holding evidence, object kind, operation and initial state constant: does authority, target, their interaction, or another mechanism determine a standing-raising (RAISE) outcome? |
| Frozen scope | `TASK.md`: models M_E / M_A / M_T / M_AT / M_C with a monotone-evidence falsification rule. The task is byte-identical in both rounds. R1 used S1–S6 (already-read material); R2 (1am-2b, disclosed) added S7 = the R-39 row |
| Execution | Blind headless Claude subagents. Read/Write only, in a non-git bundle; tool paths audited in all four transcripts. No main-analyst conclusions shown to them |
| Independence | **SECONDARY**: same model family. The reviewers checked execution against the source, not agreement |

## Results

| Model | R1 A | R1 B (review) | R2 A | R2 B (review) | Reconciled |
|---|---|---|---|---|---|
| M_E evidence only | FALSIFIED (1 pair) | UNDETERMINED | FALSIFIED (4 pairs) | FALSIFIED (7 pairs) | **FALSIFIED**. Robust to the contested E25/E27 reading via (E02,R-39) and (E20,R-39), when evidence is compared in contexts. On a slice count, (E24,E25) still holds, but only on A's E25 reading |
| M_T target thresholds | UNDETERMINED | UNDETERMINED | FALSIFIED (E02,R-39) | FALSIFIED (+ (E25,E01)) | **FALSIFIED, conditionally.** Rescuing M_T needs *both* R-39 = CHOICE *and* ES-004.3 ≠ STANDARD-DOC; the text supports neither |
| M_A authority thresholds | UNDETERMINED | UNDETERMINED | UNDETERMINED | UNDETERMINED | **SURVIVES (untested at the decisive point)** |
| M_AT interaction | UNDETERMINED | UNDETERMINED | UNDETERMINED | UNDETERMINED | **SURVIVES (untested)** |
| M_C other | not needed | not needed | not needed | not needed | not needed on current coding |

## Disagreements (classified; never averaged)

1. **E27 (R1) = E25 (R2): the session author's "I have not created one".** A codes it as a legality refusal (actor = author). R1-B codes it as a pending DA decision item; R2-B accepts it, under TASK's "decides **or acts**". **Class: source interpretation.** Both readings survive. The verdicts do not depend on it once R-39 is in scope.
2. **ES-004.3 evidence (UNK vs 1 instance).** Class: **coding.** R2-B's reading is that the second instance came after adoption.
3. **Home vs item (the "Engineering Standards document" row).** Both reviewers exclude it. Class: **evidence scope.** No verdict effect.
4. **Cross-unit comparison** (occurrence, slice, context). Class: **formal reasoning.** The M_E / M_T falsifications survive conversion to contexts, but not to slices, for the R-39 pairs.
5. **The main analyst's sealed R1 expectation** (R-41 vs S6 deciding) **did not survive as stated.** The R2 sealed expectation matched. Bias caveat: I chose the S7 expansion after seeing R1.

## Substantive observation (not a verdict)

- **R-39 is the pair that falsifies M_T, and it carries a recorded exception** ("Governance exception … the normal promotion rule … requires evidence from more than one bounded context").
- **INFERENCE:** in this corpus, authority does not appear as a *different evidence threshold*. It appears as the **capability to grant a recorded exception to the one threshold**. This is M_C-shaped, "rule + authorized exception", and it is observationally confounded with M_A, because every below-threshold promotion observed is a DA act *with* an exception.
- **HYPOTHESIS H-X:** RAISE(e < θ) is legal iff there is an exception granted by an actor holding exception capability. It is not yet a frozen model.

## Surviving / eliminated

- **Eliminated (conditionally for M_T):** M_E; M_T.
- **Surviving:** M_A, M_AT, H-X (post hoc, labelled).
- **Uncertainty:** SECONDARY independence only. One exception event (R-39). The unit of evidence is unresolved; this is the 1ak-3 hypothesis, "evidence is measured in contexts".

## Next highest-information task: 1am-2c

The only open pair that separates M_A from M_AT is **(E02, R-39)**: the same target (methodology), the same evidence (1), with E02's actor UNK.
- **E02's actor = DA/PO:** M_A and M_AT are both FALSIFIED (the same actor both promotes and refuses at 1), which leaves exception-capability H-X.
- **E02's actor = the author/engineering:** consistent with M_A and H-X; does not discriminate them.

Task: a targeted read of the passage immediately around 2026-08-15 L483–495, coded under the same blind loop.
