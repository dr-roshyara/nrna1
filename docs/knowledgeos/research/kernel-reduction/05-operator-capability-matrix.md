# 05 — Operator × Capability Matrix (Part IV)

> **This matrix is not truth.** It is a model of the current hypothesis, and it is *computed*, not
> asserted: each cell is the outcome of removing that operator from `C0+` and re-evaluating.

**Cell codes**

| Code | Meaning |
|---|---|
| `D` | **directly realizes** — removing the operator destroys the capability, and it is the sole holder of the needed atom |
| `R` | **required in the current realization** — removing it destroys the capability, but the atom is shared |
| `0` | no contribution detectable by leave-one-out |

| Operator | C1 | C2 | C3 | C4 | C5 | C6 | C7 | C8 | C9 | C10 | C11 | C12 | C13 | C14 | C15 | C16 | C17 | C18 | C19 | C20 | C21 | C22 | C23 | C24 | C25 |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| Observe | D | D | D | D | 0 | D | D | 0 | D | D | 0 | 0 | 0 | D | 0 | D | D | 0 | D | D | D | D | 0 | D | D |
| Interpret | 0 | D | D | D | 0 | D | D | 0 | D | D | 0 | 0 | 0 | D | 0 | D | D | 0 | D | D | D | D | 0 | 0 | 0 |
| Represent | 0 | 0 | D | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 |
| Relate | 0 | 0 | 0 | D | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | D | 0 | 0 | 0 |
| Discriminate | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 |
| Hypothesize | 0 | 0 | 0 | 0 | 0 | D | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | D | D | 0 | 0 | 0 | 0 | 0 |
| Infer | 0 | 0 | 0 | 0 | 0 | 0 | D | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 |
| DetectGap | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 |
| Challenge | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | D | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 |
| Validate | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | D | 0 | 0 | 0 | 0 | 0 | D | D | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 |
| Revise | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | D | 0 | 0 | 0 | D | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | D | 0 |
| Determine | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | D | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | D | 0 | 0 |
| Select | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | D | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 |
| Qualify | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | D | 0 | 0 | 0 | 0 | 0 | D | D | 0 | 0 | 0 | 0 | 0 | 0 | 0 | D |

## Reading the zero rows — the point of the experiment

`Discriminate` and `DetectGap` are **all-zero rows**. Neither is required by *any* capability under
leave-one-out, because each covers the other's shared atoms. That is not evidence that the
capabilities are unneeded — it is evidence that the **operator packaging is redundant**.

Likewise `C5` (discriminate alternatives) and `C8` (detect insufficiency) are **all-zero columns**:
no single removal destroys them. Their necessity appears only under *pairwise* ablation (§11) and at
the **atom** level (§10):

| Capability | Lost when | Level at which it is irreducible |
|---|---|---|
| C5 | `{Discriminate, DetectGap}` both removed | the atom `difference-decision` |
| C8 | `{Discriminate, DetectGap}` **or** `{DetectGap, Determine}` removed | `difference-decision` + `norm-comparison` |

> `[EXP]` **A leave-one-out matrix alone would have declared C5 and C8 unnecessary. They are not.**
> This is the concrete reason the protocol's Part IX (pairwise ablation) is not optional, and the
> reason cardinality is treated as a secondary result.
