# 04 — T-3 Probability Necessity Result
**`exec/f21_probability_necessity.py` → `OUT-F21.txt`**

## The seven questions
| Question | Answer |
|---|---|
| What random experiment exists? | **NONE** — assertions are made by actors; no repeatable trial is defined |
| What is `Ω`? | **UNDEFINED** |
| What is `ℱ`? | **UNDEFINED** |
| What is `P`? | **UNDEFINED** — `str` is ordinal; a measure needs at least interval structure |
| Random variables? | **NONE** |
| Estimand? | **NONE** |
| Estimator? | **NONE** |

## The critical test — executed
**Remove probability. 13 mandatory constructs checked: `K`, Assertion, `WellFormed(P)`, `Σ`, `Σ`-ordering,
`contradicts`, `StructuralValid`, `T`, Replay, Identity/Equality, Lineage, Policy `Apply`, `Authorize`.**

> **BROKEN: ZERO.**

Two lines initially printed `BREAK` and both were investigated rather than explained away:
- **`Σ`** — my own expected value was wrong (`|sup|=3 → Strong`, not `Moderate`). **Test-author error.**
- **`StructuralValid`** — a **real defect in the harness**, not the theory. See `01`, gap **C-NEW**.

**Neither involved probability.**

## Which claims would need it?
Only step 246's `KnowledgeOS = Probability Distribution` — **and that step constructs no space.**
`Σ`, Assessment, JustificationStrength, `Admissible`, Risk are all **ordinal**, and the corpus's own
**no-averaging law (42.10)** forbids the arithmetic a measure would license.

> ## VERDICT: probability is **NOT** a foundational dependency.
> `Primary classification: M (Measurement) + S (Scope).` **T-3 is NOT theory-critical.**
