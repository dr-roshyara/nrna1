# MD-061 §06 — Final Determination

| Item | Answer |
|---|---|
| **Selected variant** | V7 (M0125/M0126's 11-component `K_t`), via its typed sub-structure `Σ_t=(A,S,R,V,C)` |
| **Exact corpus basis** | `Σ_t`'s own enumerated field domains (M0125); `Sat`/`Δ_t`'s frozen shape (`[DEF-19]`–`[DEF-21]`, M0043, ratified M0132) |
| **Candidate `Sat` definition** | `Sat^*(K_t,r):=1` iff `π_{\text{component}_r}(Σ_t(K_t))∈\text{Accept}_r`, for `r=(\text{component}_r,\text{Accept}_r)`, `\text{component}_r∈\{A,S,R,V,C\}` |
| **Every non-corpus assumption** | (1) `K_t` restricted to V7-shaped instances; (2) `Req(EC_t)` restricted to its `Σ_t`-shaped subtype `Req_Σ`; (3) `Σ_t`'s enumerated domains treated as flat sets, no invented order relation |
| **Falsification results** | T1/T2/T3/T7 **PASS**; T5 flagged inherent-not-defective; T6 no contradiction found, transition-interaction untestable given scope; T8 no counterexample found (not proof); T4 representation-dependent across variants (undefined "same state" notion), invariant within-variant to field reordering |
| **Gate** | **C — CONDITIONAL CANDIDATE** — mathematically coherent, corpus-grounded in its structure, dependent on three explicit, disclosed design choices |
| **Is `Δ_t` computable?** | **Partially** — `Δ_t^Σ` (restricted to `Σ_t`-shaped requirements) is genuinely computable, given the three assumptions; full `Δ_t` (over all of `Req(EC_t)`) remains not computable |
| **Smallest next research input** | Typed semantics for V7's other ten components (`A_t,R_t,E_t,H_t,Z_t,L_t,T_t,G_t,C_t,M_t`) — the same kind of work `Σ_t` alone already received, applied component by component |

## Why C, not A/B/D — stated plainly

**Not A (CORPUS-COMPUTABLE)**: `Sat^*` required this phase's own construction — no source states it
directly.
**Not B (DERIVABLE without a substantive modelling choice)**: three explicit, non-trivial narrowings
were required (variant restriction, requirement-type restriction, the deliberate choice to avoid an
ordinal rule) — none of these follows from corpus requirements alone.
**Not D (NOT CONSTRUCTIBLE)**: a genuinely coherent, corpus-grounded candidate *was* constructed and
survives every falsification test that could actually be run.

**C is not reported as a failure** — per the authorizing prompt's own instruction, this locates the
epistemic boundary precisely: **the corpus supplies just enough typed structure (in exactly one
component, of exactly one of twelve variants) to build a real, working, narrow `Sat`, and no more.**

## MD-061 STATUS: COMPLETE / HARD STOP

No MD-062 opened by this completion. No F3↔F4 comparison entered. Named, unauthorized next action:
extend `Σ_t`'s own typing approach to V7's other ten components, to enlarge `Req_Σ`'s own coverage of
`Req(EC_t)` — not attempted here.
