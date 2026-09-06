# 11 — Pairwise and Higher-Order Results (Part IX)

All **91 pairs** of `C0+` were ablated. **4 pairs show synergistic loss** — a capability lost by the
pair that neither member loses alone.

| Pair removed | Lost by A alone | Lost by B alone | Synergistic loss | Reading |
|---|---|---|---|---|
| `DetectGap + Determine` | — | C12 C23 | **C8** | joint ownership of `norm-comparison` |
| `DetectGap + Discriminate` | — | — | **C5 C8 C19 C20** | joint ownership of `difference-decision` |
| `Hypothesize + Infer` | C6 C19 C20 | C7 | **C9 C10 C16 C17** | structural: the critical lane has no upstream content |
| `Hypothesize + Represent` | C6 C19 C20 | C3 | **C7 C9 C10 C16 C17** | same, via a different upstream route |

## Interpretation

### 1. Shared-atom coupling — `{DetectGap, Discriminate}` and `{DetectGap, Determine}`

Neither pair is a "hidden dependency" in the usual sense. Both are the signature of **one atom held
by two operators**. `DetectGap` sits at the intersection of both pairs — it is the only operator that
appears in two synergistic pairs, and the only one holding no exclusive atom. Structurally,
`DetectGap` is the *union* of two powers already owned elsewhere.

### 2. The critical lane requires an upstream content producer — `[EXP]` the more interesting result

`{Hypothesize, Infer}` and `{Hypothesize, Represent}` both destroy C9 *challenge* and C10 *validate*,
which neither member touches alone.

The reason is structural, not about any of the three operators: a `Defeater` requires a `Claim` or a
`Hypothesis`; a `Verdict` requires a `Claim` or a `Hypothesis` plus `Evidence`. **There must be at
least one operator that produces a proposition-bearing artifact, but the system does not care which
one.** `Hypothesize` (non-entailed content) and `Infer` (entailed content) are *substitutable as
suppliers* to the critical lane while remaining *non-substitutable in warrant kind*.

> This is an **operator bundle**, not an operator: `{Hypothesize, Infer}` is jointly necessary and
> individually deletable-from-the-supply-role. It answers protocol question **Q8** affirmatively —
> yes, there are clusters that must stay separate despite being mutually dependent, because the
> warrant kinds they produce (DEDUCTIVE vs generated-and-tested) are not interchangeable even though
> their *supply function* to the critical lane is.

## Selected triples

| Triple removed | Lost | Note |
|---|---|---|
| `DetectGap + Discriminate + Determine` | C5 C8 C12 C19 C20 C23 | the entire normative/discriminative lane; **no third-order effect** beyond the union of the pairs |
| `Hypothesize + Infer + Represent` | C3 C6 C7 C9 C10 C16 C17 C19 C20 | the entire content lane; again no genuine third-order effect |

`[NEG]` **No third-order interaction was found.** All observed structure is second-order — pairs
sharing an atom, or pairs jointly feeding one downstream carrier. Higher-order ablation was run and
returned nothing new; that is a negative result worth recording, since it bounds how complex the
dependency structure of this model actually is.

## What pairwise ablation changed about the conclusions

Without it, the leave-one-out matrix (§05) would have reported that **C5 and C8 are needed by no
operator** — a false conclusion produced by a correct procedure applied at the wrong granularity.
