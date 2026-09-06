# 10 — Golog, RGolog, composition

## What they are

`[EXT]` **Golog** — complex actions built by the `Do` macro over a basic action theory: sequence,
test, nondeterministic choice, iteration, procedures. **RGolog** — interrupts as condition-action
rules with priorities.

## Verdict

> `[NEG]` **`P9` REFUTED as stated.** Golog's semantics is defined **relative to a basic action
> theory** — SSA + preconditions + unique names + `S₀`. **KnowledgeOS has none of these**, and `04`
> shows the SSA is blocked behind `Contr` and `DECISION-02`.
>
> **Golog is not "not useful". It is UNREACHABLE from the current state**, and the distance is
> measurable: it needs `δ`, which needs `Contr`, which needs the evaluation representation, which
> needs `ℛ_req`.

## The one thing that is directly comparable

KnowledgeOS ran its **own** composition experiment (`KR-COMP`), and its result is **stronger and
independent**:

| | Reiter/Golog | `KR-COMP` |
|---|---|---|
| composition over | actions in a history | **evidence within and across frames** |
| the load-bearing parameter | the action theory | **the frame qualifier `φ ⊇ {time, context}`** |
| result | composition is definable given a theory | **the criteria select a FRAME QUALIFIER, not a rule** |

> **These are not competing answers to one question. They are answers to different questions**, and
> conflating them would import a sequencing model where the corpus has an evidential one.
> **`KnowledgeOS-DERIVED`; Reiter neither corroborates nor challenges it.**

## RGolog

Interrupts-with-priority is a recognizable shape for governance triggers. **No corpus evidence bears
on it. `RESEARCH ONLY`.**
