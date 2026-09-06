# 02 — Residual Gap Classification

**Primary classification is explicit for every gap. `F` Formal · `C` Computational · `E` Empirical ·
`M` Measurement · `G` Governance · `N` Normative · `S` Scope · `U` Undetermined.**

| Gap | Primary | Secondary | Theory-critical | Evidence | Status |
|---|---|---|---|---|---|
| **T-3** probability space | **M** | S | **NO** | `[E]` F21 | **OUT OF SCOPE** |
| **T-4** non-identifiability | **F** | — | **NO** | `[E]` F15 | **CLOSED — derived** |
| **I-1** 15/24 unobservable | **E** | C | **NO** | `[R]` re-verified | **OPEN — certification** |
| **I-2** dependency cycle | **F** | — | **NO** | `[E]` F19 | **CLOSED — 0 cycles** |
| **I-3** no `Authorize()` runtime | **C** | — | **NO** | `[F]`+`[E]` | **OPEN — engineering** |
| **E-1** L4 vs L5 evidence | **E** | — | **NO** | `[R]` | **OPEN — certification** |
| **E-2** no multi-node | **E** | C | **NO** | `[D]` | **OPEN — architecture** |
| **E-3** no measurement executor | **C** | S | **NO** | `[F]` | **OPEN — engineering** |
| **G-P1** policy runtime | **G** | C | **NO** | `[E]` F16 | **OPEN — governance** |
| **Q_t** replay/serialization | **F** | — | **NO** | `[E]` F17/F18 | **CLOSED — 10/10** |
| **Q_t** `unask` semantics | **N** | F | **NO** | `[E]` | **NORMATIVE** |
| **C-NEW** harness id collision | **C** | — | **NO** | `[E]` F21 + regression re-run | **CLOSED** — fixed in all 3 harnesses, suite re-run, no regression (see `13`) |

## Tally by primary classification
**F: 3** (all CLOSED) · **C: 3** — 1 now CLOSED (C-NEW), 2 open (engineering) · **E: 3** (open, certification) · **M: 1** (out of
scope) · **G: 1** (open) · **N: 1** · **S: 0 standalone** · **U: 0**

> ## **THEORY-CRITICAL GAPS REMAINING: ZERO.**
> Every `F`-classified gap is closed. Every remaining gap is `C`, `E`, `M`, `G` or `N` — none of which
> blocks the definition, typing, identity, transformation, invariants or falsifiability of the theory.
