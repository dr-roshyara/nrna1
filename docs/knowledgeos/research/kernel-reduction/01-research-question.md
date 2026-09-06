# 01 — Research Question

**Experiment ID:** `KR-2026-09-01` · **Model version:** `kr-model-1.0` · **Date:** 2026-09-01
**Status:** `[EXP]` research experiment. **NOT** architecture, **NOT** governance, **NOT** canonical KnowledgeOS law.

> **Authorization boundary.** This lane is *not* authorized to canonize a KnowledgeOS kernel.
> No result below may be promoted to invariant, theorem, ADR or standard by this document.

---

## Primary research question

> What is the smallest set of genuinely irreducible epistemic capabilities required for
> KnowledgeOS to support the required knowledge lifecycle?

Operationally, for each operator `o` in the candidate set `C0`:

> Does removing `o` necessarily destroy a required epistemic capability, **or** can that
> capability be reconstructed by composition of the remaining operators **without smuggling
> the removed semantics back into another operator**?

## Candidate operator set C0 (13)

```
C0 = { Observe, Interpret, Represent, Relate, Discriminate, Hypothesize,
       Infer, DetectGap, Challenge, Validate, Revise, Determine, Select }
```

## What this experiment is NOT

| Not | Why it matters here |
|---|---|
| a naming exercise | operator *names* are excluded from the reach engine by construction (§06) |
| an aesthetic minimization | semantic irreducibility is optimized **before** cardinality |
| a vote | every verdict is computed from a declared model, and every model choice is exposed |
| a universal-necessity proof | scope is *this* capability model under *these* composition rules |
| a retrofit of the current architecture | Part XXIV: "existing class ≠ domain primitive" — no architectural circularity |
| a canonization | statuses are drawn only from the permitted provisional vocabulary |

## Evidence tags used throughout

`[CORPUS]` found in the KnowledgeOS primary corpus · `[EXT]` external source ·
`[INF]` inference by this lane · `[PROP]` proposal · `[EXP]` experimental result ·
`[NEG]` negative result · `[OPEN]` unresolved.

## Prior constraints preserved (verified, not assumed)

1. `K_t ≠ K_{t+1}` in general — modelled: `state-mutation` produces `EpistemicState'`.
2. historical identity ≠ current-state equality — modelled: C15 requires mutation, not overwrite.
3. Kernel `𝒦` / current state `K_t` / Knowledge Space `𝓜` distinct — `K_t` is an *ambient carrier*, never an operator.
4. **`Zero(K_t, I_Q, EC)` is a state predicate / gap condition, NOT automatically a primitive** —
   tested directly: see §10, `DetectGap`.
5. Ideal State is inquiry/purpose/context dependent — `IdealState` and `Inquiry` are *separate ambient carriers*;
   a `Determination` is unreachable without both.

## Three distinct notions of "necessary" (kept separate, never collapsed)

| Notion | Instrument | Where reported |
|---|---|---|
| **Empirically necessary** | scenario + randomized simulation fails without it | §10, §12 |
| **Formally necessary** | no valid composition of the rest yields the capability | §10 (reach algebra) |
| **DDD-necessary** | distinct domain responsibility that should not be collapsed | §13 |

They disagree for `Select`, `Discriminate`, and `Revise`. Those disagreements are results, not defects.
