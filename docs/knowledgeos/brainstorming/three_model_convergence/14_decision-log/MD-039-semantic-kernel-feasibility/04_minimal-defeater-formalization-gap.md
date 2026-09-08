# Minimal Defeater-Formalization Gap

> **This section presents a labeled hypothetical diagnostic only, per the authorization's own §9.**
> No component below is proposed as a definition, a research result, or a candidate to adopt. Its
> only purpose is to show precisely which slots are missing information, using the smallest possible
> skeleton the admissible evidence's own vocabulary supports.

## The skeleton

A predicate of the rough shape `Survives(d, …)`, where `d` ranges over `Defeater` — this much, and
no more, is directly supported by admissible source vocabulary.

| Candidate argument | Status | Reasoning |
|---|---|---|
| `d : Defeater` | **SOURCE-GROUNDED** | `Defeater` is an admitted carrier kind, produced via `adversarial-negation` from `Claim`\|`Hypothesis` (`06`, already admitted) |
| The target `Claim`/`Hypothesis` being defeated | **SOURCE-GROUNDED (as a relationship, not as an argument to *this* predicate)** | `06`'s own derivation rule already establishes `Defeater`'s own input is `Claim`\|`Hypothesis` — a defeater is *of* something, source-stated; whether "surviving" needs the target as a separate argument is not addressed |
| `Evidence` | **NECESSARY BUT UNSPECIFIED** | `Evidence` is central to `Verdict`'s own derivation (`06`) and to `V6`'s own domain (`{Claim,Evidence,Defeater}`); whether "survival" is itself a function of `Evidence` (e.g., "survives if unrebutted by available evidence") is never stated anywhere in the admissible population |
| A counter-response / "defeat of the defeater" | **PURELY HYPOTHETICAL** | Nothing in any admitted source discusses what would defeat a `Defeater` itself, or whether such a thing is even representable in this carrier system; introducing it would be inventing structure, not reconstructing it |
| A boolean/graded outcome ("survived") | **NECESSARY BUT UNSPECIFIED** | Every admitted use of "surviving" treats it as a binary gate on whether `Verdict` is reachable (`12`'s own reachability table); no admitted source states whether survival is itself binary, graded, or something else |
| A temporal/ordering component | **PURELY HYPOTHETICAL** | `K_t`'s own temporal structure is admitted (via `state-mutation`, `06`), but no admitted source connects it to defeater-survival specifically |

## What this diagnostic shows

Of six plausible slots, only one (`d : Defeater` itself) is fully source-grounded; one more (the
target relationship) is grounded only as a *fact about `Defeater`'s own derivation*, not as
established structure for a *survival* predicate specifically; the remaining four are either
unspecified-but-plausible or purely hypothetical. **The minimum information deficit is therefore not
a single missing field — it is the entire predicate body**: admissible evidence supports that
something called `Defeater` exists and how it is produced, and supports that "surviving" one is
somehow a gate on `Verdict`'s reachability under `V6` — and nothing about *what makes a defeater
survive* beyond that gate itself.

## Explicit non-conclusion

This diagnostic is not evidence that a definition is close, feasible, or impossible in principle — it
only shows, mechanically, where the gap sits once the vocabulary is laid out. No component here is
carried forward as a proposal in any later section of this study.
