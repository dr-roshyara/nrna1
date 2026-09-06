# `KR-ZOOM-01` — Recursive Epistemic Zoom

**Status:** research experiment · **experimental / non-adjudicated**
**Theory v1.2 FROZEN · kernel NOT SELECTED · no carrier declared · no algebra declared**

Tests whether an observed value at one epistemic resolution can become a new epistemic substrate
exposing structure at a finer resolution — in a small, controlled, reproducible, falsifiable
synthetic regime.

## Read in this order

1. **`DESIGN.md`** — question, definitions, the four traversal regimes, hypotheses, and the four
   decisions taken before execution (`D1`–`D4`).
2. **`AUDIT.md`** — **read before citing any number.** 14/14 gates pass; **7 metrics are degenerate.**
3. **`RESULTS.md`** — hypothesis table, the order-dependence result, the counterfactual.
4. **`CONCLUSIONS.md`** — what was and was not established; governance recommendation.

## Reproduce

```bash
cd code
python3 run_zoom01.py     # regenerates data/ledger.jsonl + manifests/manifest.json
python3 analyse.py        # -> data/analysis.json, prints all metrics with numerator/denominator
python3 audit.py          # -> data/audit.json, gates A-P + adversarial findings
python3 replay.py         # asserts the ledger is byte-identical on re-execution
```

No arguments, no environment, no network. Python 3 standard library only.

| | |
|---|---|
| seeds | `train = 20260904`, `test = 88020260904` |
| roots | 300 per split · `MAX_DEPTH = 4` |
| nodes generated | 42 071 (train) · 44 511 (test) |
| ledger rows | 18 042 |
| replay | byte-identical (`4b93d8f2…`) |

## Layout

```
KR-ZOOM-01-2026-09/
  README.md DESIGN.md AUDIT.md RESULTS.md CONCLUSIONS.md
  code/     kzoom.py  run_zoom01.py  analyse.py  audit.py  replay.py
  data/     ledger.jsonl  analysis.json  audit.json
  schemas/  ledger.schema.json
  manifests/manifest.json
```

## The one-line result

> Traversal order **never** produced the same final state (0 of 518 admissible pairs) while
> producing the same contract observable in **~30 %** of them — so a commutativity test run on the
> observable alone would have concluded the operators commute.

## What this experiment does not claim

Not that knowledge is fractal, infinite-dimensional, or recursively topological. Not that Zoom is
an operator, an inverse of compression, or a kernel primitive. Not that Zoom differs from
decomposition — the relation kinds are generator-assigned and **the claim is not made.**
