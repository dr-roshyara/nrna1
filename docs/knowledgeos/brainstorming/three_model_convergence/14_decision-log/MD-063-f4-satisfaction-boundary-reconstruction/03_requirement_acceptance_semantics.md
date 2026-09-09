# MD-063 §03 — Q3: Requirement/Acceptance Semantics, and the DDD Discipline

## Reconstructed concrete requirement type

M0047 `[DEF]`, §5 "Formal epistemic requirement": `r=(id,type,scope,content,standard,priority,
validity)`, with **`standard` explicitly named "acceptance criterion"** — and, immediately following
(§6), used to derive the Ideal State: `I_t=𝓡_t` (the Ideal State **is** the set of such requirements).
This gives real, primary-sourced, typed structure to `Req(EC_t)`'s own members — a genuine positive
finding, not previously surfaced this precisely in MD-059–062's own text (which worked from `Req(EC_t)`
as an opaque set).

**What is still missing**: no formula anywhere maps `standard` to a computable acceptance rule.
`standard` is *named* as the acceptance criterion; its own internal structure/evaluation procedure is
never given — the same shape of gap MD-061/062 already found for `Accept_r`, now traced one level
deeper into a genuine, named corpus field rather than MD-061's own invented `Accept_r` symbol.

## DDD bounded-context test — six concepts

| Concept A | Concept B | Classification |
|---|---|---|
| `Requirement` (M0043's abstract `Req(EC_t)` member) | `r=(id,type,scope,content,standard,priority,validity)` (M0047) | **LIKELY THE SAME CONCEPT AT DIFFERENT ABSTRACTION LEVELS** — M0047 gives the concrete type M0043's own abstract set implicitly needs; no explicit cross-citation between the two files confirms this, but `I_t=𝓡_t`'s own role parallels M0043's `𝕀(EC_t)` closely enough to treat this as a strong, not certain, correspondence |
| `AcceptanceCondition`/`Accept_r` (MD-061's own construct) | `standard` (M0047, corpus-native) | **DIFFERENT ABSTRACTION LEVELS, NOT SHOWN EQUAL** — `standard` is corpus-native and named; `Accept_r` is MD-061's own invented formalization of an unspecified acceptance rule. Calling them the same object would be an unproven identity claim. |
| M0125's own informal "`Sat`" (§3.1) | M0043's formal `Sat(K_t,r)` | **UNRESOLVED HOMONYM-OR-IDENTITY QUESTION** — no formula connects them (`01`) |
| `Σ_t` field (M0125, a component of `K_t`) | `Boundary=(Facet,Condition,Context,Provenance)` (M0125, same document, different Part) | **DIFFERENT DOMAIN CONCEPTS, SAME DOCUMENT** — one is a `K_t` state component, the other an evaluation-boundary structure for propositions; no formula relates them |
| `Evidence` | `E_t` (a named V7 component, per MD-060's own census) | **PLAUSIBLE, NOT CONFIRMED** — `E_t` is named but never typed (MD-060/061), so its relation to any general "Evidence" concept remains open |

**No identity inferred from shared naming anywhere in this table** — every row lands at "likely/
plausible, not confirmed" or "different, not shown equal," per the authorizing prompt's own explicit
instruction.
