# 05 — The frame problem

## What Reiter establishes

**A genuine solution to the representational frame problem**: instead of writing `O(A×F)` frame
axioms, quantify over the *causes* of change and invert. The SSA is compact, and the persistence
behaviour verifies (`A2`).

## What transfers

> `[EXP]` **The METHOD transfers: "state what changes a thing; everything else persists by
> construction."**

This is genuinely useful and is the strongest positive result of this step.

## What does not transfer

| assumption Reiter requires | does KnowledgeOS supply it? |
|---|---|
| **causal completeness** — the effect axioms enumerate every cause of change | **NO.** An epistemic system's evidence arrives from channels it does not control. `KR-M2O` measured what happens when an unmodelled channel misleads: **99.3 % vs 0.75 % detection** |
| **consistent effect axioms** | **NO** — contradiction is first-class |
| **two-valued fluents** | **NO** — `KR-CONTR-EVAL`: no flat domain of any cardinality is adequate; the surviving factorization is `Status × Typed Boundary` |

> **The third row is the deepest.** Reiter's fluents are **two-valued**. KnowledgeOS established that
> **a flat value domain is insufficient regardless of cardinality** — the obstruction is structural,
> not cardinal. **An SSA over two-valued fluents cannot carry the boundary component that
> `KR-CONTR-FDE` showed is indispensable.**

**Consequence:** a KnowledgeOS successor-state construction would need to be an SSA over
**`(Standing, Boundary)` pairs**, not over booleans. **Whether the frame solution survives that
generalization is untested and is a well-posed next question.** `OPEN`.
