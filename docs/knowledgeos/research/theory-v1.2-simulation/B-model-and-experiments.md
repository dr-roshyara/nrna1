# B — Formal Model, Experiments, and What Was Reused

## What was reused, and why that matters

The v1.2 run **reuses the entire v1.1 pipeline** (`kos/world.py`, `kos/state.py`,
`kos/transitions.py`) unchanged, and replaces only the *evaluation* layer (`kos12/`). That is a
deliberate control: **any difference between the v1.1 and v1.2 results is attributable to the theory,
not to the simulator.** The lifecycle, the oracle independence guarantee (zero agent-side reads of
`World.truth`), and the transitions are byte-identical across both runs.

## Three-valued class-indexed `Sat`

```
Sat : 𝒦 × ℛ → {⊤, ⊥, U}          ℛ = ℛ_content ∪ ℛ_evidence ∪ ℛ_provenance ∪ ℛ_status
                                     ∪ ℛ_consistency ∪ ℛ_governance ∪ ℛ_temporal ∪ ℛ_operational
```

Kleene conjunction: `⊥ ∧ x = ⊥` · `⊤ ∧ ⊤ = ⊤` · `⊤ ∧ U = U` · `U ∧ U = U`.

| class | `⊤` | `⊥` | `U` |
|---|---|---|---|
| content | `p ∈ Content(K)` | `¬p ∈ Content(K)` | otherwise — **absence is not negation** |
| evidence | `Evidence(K,p) ⊨ E_min` | `⊨ ¬E_min` | otherwise — **existence ≠ sufficiency** |
| provenance | `Π(p) ⊨ π_min` | `⊨ ¬π_min` | `Π(p)` insufficient to decide |
| status | `ES(p) ⪰ s_min` | `ES(p) ≺ s_min` | undetermined — **`⪰` is itself undefined in the theory** |
| consistency | `¬Contr(K,p)` | `Contr(K,p)` | cannot be evaluated — **unknown contradiction ≠ no contradiction** |
| governance | authority grants | authority denies | **no authority supplied** — cannot be inferred from content |
| temporal | established over `I` | established false over `I` | temporal evidence insufficient |
| operational | `δ` defined ∧ postcondition met | `δ` defined ∧ not met | **`δ` undefined ⇒ `U`, never `⊥`** |

### The consequence that drives everything

`Gap` is no longer a set. It is a **partition**:

```
GapPartition(K, Req) = ( violated , undetermined , satisfied )
```

and therefore `Zero ⟺ Δ = ∅` becomes three distinct predicates:

```
Zero_strict(g)  ⟺  violated = ∅  ∧  undetermined = ∅
Zero_weak(g)    ⟺  violated = ∅
Zero_kleene(g)  ∈  {⊤, ⊥, U}          ⊥ if violated, U if undetermined, else ⊤
```

## The five experiments

| ID | Question | Result |
|---|---|---|
| **E1** | Do the three `Zero` readings ever disagree? | **Yes — on the fully determined case.** `strict=false`, `weak=true`, Kleene `=U` |
| **E2** | Are the five relations really non-substitutable? | 13/20 separable; the other 7 form a refinement order, and the order is **λ-relative** |
| **E3** | Do CE-1 (factivity) and CE-3 (revision) persist under v1.2? | **Both persist.** CE-3's *report* improves; neither failure is repaired |
| **E4** | Which results depend on the OPEN observation concept? | Dropping the **source** component flips determination, attribution and `Zero` |
| **E5** | What kind of thing is `Zero`, of the seven v1.2 candidates? | 2 refuted, 1 partial, 4 surviving |

## Declared assumptions

| Assumption | Effect if changed |
|---|---|
| the 8 requirement classes are those of the Sat document | a 9th class could add further `U` sources |
| `⪰` for epistemic status is `cannot-determine < underdetermined < unique` | **the theory does not define `⪰`**; a different order changes `Sat_status` |
| governance requires an explicit authority; none was supplied | supplying one moves `rg` from `U` to `⊤`/`⊥` and **would make `Zero_strict` reachable** |
| `λ = (status, value)` unless stated | the equality lattice moves — demonstrated in E2 |
| `δ` is undefined (Step 290 open) | defining it moves `ro` out of `U` |
