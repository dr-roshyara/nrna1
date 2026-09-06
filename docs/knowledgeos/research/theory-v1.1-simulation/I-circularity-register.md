# I — Circularity Register (§26)

No circularity was repaired silently. Five checks, one genuine finding, one redundancy.

## CIRC-1 — Knowledge via Truth / Truth via Knowledge → **NOT CIRCULAR**

| | |
|---|---|
| Definition A | `Knows(a,p,c,t) → True(p,c,t)` (DEF-1) |
| Definition B | Truth inferred from Knowledge |
| Circular? | **No** |
| Finding | Truth exists **only** in `World`, and no agent transition reads it (verified, SMUG-0). There is no back-edge from knowledge to truth. |
| Independent grounding | `World.truth` is exogenous; the evaluator alone reads it. |

> **The non-circularity and the impossibility are the same fact.** Because truth never re-enters the
> agent's state, `Γ` cannot consult it — which is exactly why CE-1 occurs. A theory that made this
> circular would have "solved" factivity by cheating.

## CIRC-2 — Zero via Gap / Gap via Zero → **NOT CIRCULAR**

One-directional. `Gap` is primitive (`Δ = {r : ¬Sat(K,r)}`, DEF-21); `Zero := (Δ = ∅)` (DEF-22).
`Sat(K,r)` is defined per requirement kind, independently of both.

## CIRC-3 — Adequacy via Satisfaction / Satisfaction via Adequacy → **REDUNDANCY, not circularity** `[NEG]`

| | |
|---|---|
| Circular? | No |
| Finding | **`Adequate` (DEF-20) and `Zero` (DEF-22) are extensionally identical.** Both reduce to `Δ_t = ∅`. In the implementation `Adequate` is literally `return Zero(...)`. |
| Impact | One of the two definitions does **no independent work**. The theory presents them as separate concepts with separate section numbers, but they have the same extension in every model. |
| Recommendation | Derive one from the other and say so, **or** give `Adequate` content that `Zero` lacks — e.g. a *degree* of adequacy, or adequacy relative to a *subset* of requirements. `[OPEN]` |

## CIRC-4 — Determination via Knowledge / Knowledge via Determination → **NOT CIRCULAR**

One-directional. `Determine` reads assessments and the standard only, and **never** consults
`E.attributions`; `Γ` reads determinations. Verified by inspection of `kos/transitions.py`.

## CIRC-5 — Semantic equivalence via behaviour / behaviour via semantic equivalence → **CIRCULAR, UNRESOLVED** `[OPEN]`

| | |
|---|---|
| Circular? | **YES** |
| Finding | `P7` compares two representations' *behaviour* under a semantic projection `sem(K) = (status, A, attributed, value)` — **chosen by the experimenter**. A different projection yields a different equivalence relation. The theory supplies no independent criterion for which projection is *the* semantic one. |
| Harmless or fatal? | **Not harmless.** It is the same obstruction the previous lane hit as "what is the correct semantic granularity of an epistemic primitive", and it is why that lane's minimal kernel was 13 under one algebra and 8 under another. |
| Possible independent grounding | The theory's own **epistemic preservation vector** `𝒫 = (Meaning, Evidence, Warrant, Uncertainty, Alternatives, History, Identity, Context, Inquiry, Authorization)` (§66) is a candidate: fix `𝒫` *first*, by contract, and define `≡_sem` as agreement on the dimensions the contract declares essential. **Not tested here.** |

## Summary

| ID | Circular | Severity |
|---|---|---|
| CIRC-1 | no | — (and its absence causes CE-1) |
| CIRC-2 | no | — |
| CIRC-3 | no | **redundancy — two definitions, one extension** |
| CIRC-4 | no | — |
| **CIRC-5** | **yes** | **blocking for any minimality claim** |
