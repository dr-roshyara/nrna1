# 1ak-3d: reconciliation. Compact task report

| | |
|---|---|
| Question | What was the decision-time evidence of ES-004.3 (E01), the pivot of F-LOG-0150? |
| Frozen | `PREREG.md` (`550469df8`); sources: commit `7632b5685`, the R-41 row, session log 2026-07-30 L1–41 |
| Result | A: **SECOND-AFTER-DECISION, evidence 1**. Reviewer: **the same**. There is no disagreement on the output |
| Strength | **INFERENCE, not SOURCE FACT.** The reviewer: the order rests on the implicature that the PA instruction preceded the rule text ("PA instruction institutionalized"; the checklist is part of the rule). A *later-ratification* alternative is not excluded (D9). The corroboration across G, S1 and S2 is **not independent**: one author, one commit, written after the run (D6) |
| Disagreements | none on the output. Formal reasoning: bullet order is not chronology (D1); the R-41 text postdates the run (D5); argument from silence (D9). Source interpretation: an ellipsis (D2). Coding: category confusions (D3, D4, D7). Evidence scope: missed quotes (D8). Wording: D10 |
| Bias check | The sealed expectation (SECOND-AFTER-DECISION) matched. The reviewer's independent path reached it too, but only as an inference |

## Consequence (per the pre-registration)

- E01 = 1 → a PA raise into a standards home at 1 instance.
- No exception is recorded in the decision record (R-41) or its session.
  - **INFERENCE:** H-X's criterion is a *recorded* exception, and R-39 shows that exceptions are recorded explicitly.
  - NOT-RECORDED ≠ FALSE still applies to "exception" as a fact, but not to "recorded exception".
- **G_RAISE (a single evidence threshold ∨ an authorized recorded exception) is FALSIFIED, at inference strength.**
  - E01 (PA · STANDARD-DOC · 1 · RAISED) vs E02 (UNK · METHODOLOGY · 1 occurrence · refused by RULE ES-006.1).
  - E01 vs E20 (ARB · OPERATING-INSTRUCTIONS · 1 slice · refused by RULE).
  - Equal evidence, opposite outcomes, no recorded exception.

## Surviving models (all "+ recorded exception", H-X)

| Model | Status | Why |
|---|---|---|
| M_A + X (actor-specific threshold) | survives | the PA raises at 1; ARB-scoped and ES-006.1 items are refused at 1 |
| M_T + X (target-specific threshold) | survives | the only no-exception M_T falsifier is (E25, E01), which needs the contested E25 legality reading. The F-LOG-0146 M_T falsification used R-39, i.e. an exception |
| M_AT + X | survives | — |
| **M_K + X** (raise *kind*: **extending an existing standard** vs raising a *new* item; E01 was "hosted in ES-004", "not a new standard document") | **new alternative, post hoc**; consistent with the supported **kind** dimension | E02 and E20 are new items; E01 is an extension |

- **Eliminated:** G_RAISE (inference strength); M_E, including its vector form, once exceptions are separated.
- **Actor, target and raise-kind are confounded** in every remaining pair: E01 differs from E02 and E20 in all three.

## Next discriminating observation (smallest)

One no-exception raise that breaks the confound. **Any one** of these separates at least two of M_A / M_T / M_K:
- a **PA** raise at evidence 1 of a **new** item, i.e. not an extension (M_A predicts raised; M_K predicts refused);
- an **ARB / DA** raise at 1 that **extends** an existing standard (M_K predicts raised; M_A predicts refused).

Locate target: other `(PA instruction)` rows in the register; "extends ES-00x" rulings.
