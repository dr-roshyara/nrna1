# 08 — Regression and progression

## Verified

> `[EXP]` **Regression and progression agree on 12/12 checks** across 6 action sequences × 2 fluents
> (`A3`). Executed, not asserted.

## What each offers KnowledgeOS

| | mechanism | candidate use | status |
|---|---|---|---|
| **Regression** | rewrite a query about `do(a,s)` back to `S₀` | **verification**: "was this determination warranted at the time?" — answered without storing every intermediate state | **SUPPORTED** |
| **Progression** | roll `S₀` forward | forward state update | **SUPPORTED**, **insufficient alone** |

## Why progression alone is insufficient — `P10`

**Progression computes `A_{t+1}` and discards the history.** `KR-HISTORY` established that the kernel
**writes** history (and reads it 0 times). Progression satisfies the *read* behaviour and **not the
write obligation.**

> **Both are needed, for different reasons** — and this is a genuine `NEW SYNTHESIS`:
> **progression for the state, history retained for audit, regression as the mechanism that makes
> retention useful rather than merely dutiful.**

**Caveat:** regression's guarantee is the **regression theorem**, which holds for *regressable*
queries over a *basic action theory*. **KnowledgeOS has no basic action theory**, so the guarantee is
**not currently available** — only the mechanism's shape is.
