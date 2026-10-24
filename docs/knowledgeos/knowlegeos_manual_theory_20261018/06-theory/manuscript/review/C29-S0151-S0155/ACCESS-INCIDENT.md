# C29: access incident (a file not on the allowed list was opened)

*2026-10-24, recorded by the Professor at discovery. Facts only.*

## What happened

| Time (UTC, approx.) | Fact |
|---|---|
| ~19:22 | The C29 step-1 brief named S0151–S0155 by index order and sent the window reviewer W (Opus) to read all five. The brief did not check each path against `CORPUS-ALLOWED.tsv` first. |
| ~19:23–19:33 | The Professor read S0151–S0154 (all four on the allowed list, closed and logged by `close_source.py`). |
| ~19:34 | The Professor opened and **read S0155 whole** (`docs/knowledgeos/brainstorming/20260820-123902-kos-design-patterns.md`, 893 lines). |
| ~19:36 | `close_source.py S0155` **refused**: "NOT on the allowed list". No read-log row was written. A `--start S0155` row exists in the uncommitted `START-TIMES.tsv`. |
| ~19:37 | The Professor stopped reviewer W's task. **Whether W had opened S0155 before the stop is not known.** Its hand-back was never received, and nothing from it is saved. |

## The rule broken

Rulebook v1.4 §7, quoted: "Only files on the corpus allowed list are read (D-C1). In `docs/knowledgeos/`, a file not on the list is never opened (it may be hold-out material)."

**Cause:** the window was formed from index order (`02-FILES.jsonl`) without checking each path against the allowed list before opening it. The tool's check runs at close, not at open.

## Measured

`02-FILES.jsonl` lists seven sources in S0001–S0400 whose paths are **not** on the allowed list: **S0155, S0156, S0157, S0161, S0170, S0174, S0233**. The earlier case, S0082, was removed from the index (THEORY-MAP: "the index has no S0082"). These seven were not removed. So `close_source.py` will demand S0155 as the next source and refuse it, and reading in order cannot pass S0155 without a decision.

## Handling

- Nothing from S0155 is written into the book, the register, the map, any brief or any report. Nothing will be.
- W's stopped task is treated as possibly exposed to S0155.
- If S0155 is hold-out material, this exposure is the Professor's, and the Rule Reviewer should know it before any test uses that file.
- Reading stops at S0154 until the Principal decides. Under consideration:
  - removing the seven from the index, as was done for S0082;
  - a different skip rule;
  - what to do about the exposure.
