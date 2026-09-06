# 04 — Can `δ` be a successor-state construction?

## The candidate

```
F_{t+1}  =  γ⁺_F(a, A_t)  ∨  ( F_t ∧ ¬γ⁻_F(a, A_t) )
```

## What executes correctly — `A2`

| property | result |
|---|---|
| **deterministic** | ✔ |
| **persistence** — a fluent no effect axiom mentions is unchanged | ✔ |
| **regression ≡ progression** | ✔ **12/12 checks** (`A3`) |
| **`Poss` gates non-executable histories** | ✔ (`A4`) |

**The machinery works. That is not the question.**

## The boundary — and it is decisive

> `[NEG]` **When `γ⁺` and `γ⁻` both name `F`, the SSA yields `F = TRUE` with no signal that the effect
> axioms were inconsistent.**
>
> Measured: from `F` false **and** from `F` true, the conflicting action yields `{F}` in both cases.

**The frame solution presupposes consistent effect axioms; it does not detect their inconsistency.**
And the **causal completeness assumption** — that the given effect axioms enumerate *all* the ways a
fluent can change — is an assumption about **the axiomatiser's knowledge**, not a theorem.

### Why this blocks adoption specifically for KnowledgeOS

KnowledgeOS treats **contradiction as a first-class epistemic condition**. The whole `KR-CONTR-*`
line exists because contradiction **must not be silently collapsed** — and `I1`–`I5`, plus the
proposed `I11`, exist to forbid exactly that.

> **A transition function that resolves `γ⁺ ∧ γ⁻` silently in favour of `γ⁺` is the same failure mode
> the programme has spent four experiments eliminating.** It is `Candidate C` of `KR-CONTR-EVAL`,
> relocated into the transition.

**`P3` is therefore REFUTED**, and the correct statement is conditional:

> `[PROP]` **The SSA is an available shape for `δ` IF AND ONLY IF contradiction is detected and
> handled BEFORE the transition** — i.e. `Contr` must be defined first, and the composition/frame
> questions (`DECISION-02`) settled first. **`δ` cannot be resolved ahead of them.**

## What `δ` must handle that the SSA does not address

| | |
|---|---|
| contradictory effects | **silently collapsed** — above |
| provenance of the effect | absent from the construct |
| the evaluation frame (`φ`) | absent |
| boundary/reason (why a fluent has no value) | absent — Reiter's fluents are two-valued |
| non-evidential invariance (`DECISION-01`) | **untested against the SSA** — `OPEN` |
