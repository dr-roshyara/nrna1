# 08 — Dependency Cycle Result
**`exec/f16_f19_f20.py` → F19** — **26 nodes, 0 definitional cycles**

## The definitional graph (what is needed to WRITE each definition)
```
E, D, Time, Origin, Context, Policy, Authority        [primitive, no dependencies]
V        -> D
P        -> E, D, V
Observation -> Time            Evidence -> Observation        Π -> Origin
id       -> P, Evidence, Context, Time, Π
Assertion-> id, P, Evidence, Context, Time, Π
𝒜 -> Assertion    ℛ -> Assertion    K -> 𝒜, ℛ
T        -> K, Policy, Authority
Assessment -> P, Evidence, Context, Policy      Σ -> Assessment
Invariant -> K     Valid -> K     History -> T     Lineage -> Π, ℛ     Q -> P     Γ -> Authority
```

## Every suspected cycle, tested
| Suspected | Verdict |
|---|---|
| `T → K → Invariant → T` | **NOT A CYCLE** — Invariant depends on `K`; `T` depends on `K`. Neither depends on the other. |
| `Σ → K` | **NOT A CYCLE** — `Σ` depends on Assessment, not on `K`. |
| `Policy → T → Policy` | **NOT DEFINITIONAL** — `T`'s definition mentions Policy; **Policy's definition does not mention `T`.** |
| `Q → K` | **NOT A CYCLE** — `Q` depends on `P` only. |

## The five dependency kinds, kept apart
`definitional` (write the definition) — **acyclic** · `operational` (`T` needs a policy *value*) ·
`runtime` · `governance` (who may change it) · `reference`.

> **The `Policy → T → Policy` loop is a GOVERNANCE dependency, not a definitional one.**
> Changing a policy is an **act** that `T` mediates; Policy's *definition* is independent of `T`.
> A conceptual loop is not a mathematical circular definition.

> ## **NO TRUE DEFINITIONAL CYCLE EXISTS. I-2 CLOSED with evidence.**
