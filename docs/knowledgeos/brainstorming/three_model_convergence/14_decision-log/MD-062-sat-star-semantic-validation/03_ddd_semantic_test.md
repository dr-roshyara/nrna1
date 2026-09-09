# MD-062 §03 — DDD Bounded-Context Test (Phase D)

## Concept map, each edge provenance-tagged, no identity inferred from shared terminology

```
Requirement (corpus, M0043, [DEF-19], abstract member of Req(EC_t))
   │  MD-061's own narrowing — DESIGN CHOICE, a PROJECTION/REFINEMENT
   ▼
r = (component_r, Accept_r)   (MD-061's own construction)

EpistemicContract EC_t (corpus, M0043, [DEF-19], 4-argument: S_t,G_t,Q_t,C_t)
   │  NO STATED MAP — absent from Sat*, per §02 Phase B(4)
   ✗
AcceptanceCondition Accept_r (MD-061's own construction)

Σ_t field (corpus, M0125, a component OF K_t)
   │  π_component_r projection — MD-061's own construction, disclosed
   ▼
input to Sat*

Satisfaction Sat (corpus, M0043, [DEF-20], opaque boolean predicate over K_t×EC_t)
   │  MD-061's own construction — DESIGN CHOICE
   ▼
Sat* (K_t×Req_Σ → {0,1}, EC_t-blind)

Gap Δ_t (corpus, M0043, [DEF-21], frozen shape, over the FULL Req(EC_t))
   │  REFINEMENT/PROJECTION — MD-061's own narrowing to Req_Σ
   ▼
Δ_t^Σ (MD-061's own construction)
```

## Classification, per pair

| Pair | Classification | Basis |
|---|---|---|
| `Requirement` ↔ `r=(component_r,Accept_r)` | **DIFFERENT ABSTRACTION LEVELS** — `r` is a PROJECTION of the corpus's own abstract `Requirement` notion, restricted to what `Σ_t` can express | §01/§02 |
| `EpistemicContract` ↔ `AcceptanceCondition` | **DIFFERENT BOUNDED CONTEXTS, no stated connection** | §02 Phase B(4) — the single most consequential DDD finding of this phase |
| `Σ_t field` ↔ `Satisfaction` | **DIFFERENT DOMAIN CONCEPTS** — a state attribute vs. a state-requirement relation, connected only via a disclosed projection, not an identity | — |
| `Gap Δ_t` ↔ `Δ_t^Σ` | **REFINEMENT/PROJECTION** — a genuine sub-case in SHAPE, not shown to equal or approximate the full `Δ_t` | — |

## The decisive finding, stated plainly

**`EpistemicContract` and `AcceptanceCondition` are not the same domain concept, not a refinement of
one another, and not even connected by a stated map.** Calling `Accept_r` "the acceptance condition
`EC_t` implies" would be a homonymy trap — nothing in the corpus or in MD-061's own construction
supports that reading. This is the formal DDD statement of Phase B's own decisive negative finding
(question 4).
