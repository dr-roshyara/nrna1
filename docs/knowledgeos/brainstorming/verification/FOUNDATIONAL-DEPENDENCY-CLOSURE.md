---
artifact: 8 · FOUNDATIONAL-DEPENDENCY-CLOSURE
mandate: 20260830_1931 §2, §11, §13
date: 2026-08-30
status: **STRUCTURALLY CLOSED · SEMANTICALLY CLOSED for 14 of 14 · falsification pass run**
---

# Foundational Dependency Closure

## 0. Two kinds of closure — a distinction I was not drawing sharply enough

> **STRUCTURAL closure** = the dependency graph is acyclic and every arrow is defined.
> **SEMANTIC closure** = every primitive the graph rests on is itself specified.
>
> **A theory can be structurally closed and semantically open** — an acyclic graph with an under-specified
> primitive sitting in it. That was exactly the state of the theory before this phase: acyclic, with
> `Policy` undefined. **"Acyclic" was never evidence of completeness, and I will not let it read that way.**

## 1. The dependency chain — each arrow interrogated

| Arrow | Source → Target | Defined? | Deterministic? | Total/Partial | Parameters | External | Computable? |
|---|---|---|---|---|---|---|---|
| `ℰ,𝒟,V_D → P` | primitives → Proposition | **YES** Q14 | yes | total | — | — | **YES** — `WellFormed` |
| `P → Assertion` | + `e,c,t,Π` | **YES** | yes | total | — | — | **YES** |
| `Assertion → K` | membership | **YES** | yes | total | — | — | **YES** `O(1)` |
| `K → K` | Transformation `T` | **YES** | yes | **PARTIAL** | Op, **Policy**, Authority | Policy, Authority | **YES** |
| **`→ Policy`** | **the former blocker** | **YES** — 57.47 + 42.9 | yes | partial (ValidityInterval) | — | — | **YES — executed** |
| `P,e,c,Policy → Σ` | Assessment | **YES** | yes | total | Policy | Policy | **YES — now computable** |
| `Σ → validation` | | **YES** — 4 predicates | yes | total | — | — | **YES** |
| `authority acts → Γ` | governance | **YES** | yes | partial | Authority | Authority, History | **YES given History** |

```
ℰ 𝒟 V_D ──► P ──► Assertion ──► K ──► T ──► K'
                      │           │     ▲
                      │           │     └── Policy ◄── (Gates, ValidityInterval, ResolutionBehavior)
                      │           │              │
                      └── e,c,t,Π ┘              ▼
                                            Assessment ──► Σ ──► Validation
   authority acts ────────────────────────────────────► Γ ──► Governance
   History (external) ─────────────────────────────────────► GovernanceValid
```
**No hidden dependencies. Verified: `Σ` does not feed `K`; `contradicts` reads `ℛ` but `ℛ` is stored;
`Valid` is structural and does not invoke `contradicts`; `T` does not read History.**

## 2. The fourteen objects

| Object | Definition | Type | Inputs | Outputs | Depends on | Equality | Computable | Evidence | Implemented |
|---|---|---|---|---|---|---|---|---|---|
| **K** | `(𝒜, ℛ)` | set × relation | assertions | — | Assertion | structural/semantic | **YES** | **FORMALLY DERIVED** | EKP partial |
| **P** | `(E,D,V)` | triple | ℰ,𝒟,V_D | — | primitives | structural | **YES** | **CORPUS ESTABLISHES** Q14 | no |
| **E** | Entity | element of ℰ | — | — | — | identity | **YES** | **CORPUS ESTABLISHES** | `knowledge_id` |
| **D** | Dimension `(ID,Name,ValueSpace,Type,Domain)` | record | — | value space | — | structural | **YES** | **CORPUS ESTABLISHES** | vocabulary files |
| **V** | Value | element of `V_D` | — | — | D | structural | **YES** | **CORPUS ESTABLISHES** | enum values |
| **ℛ** | 3 DAGs + 2 edge sets + 1 symmetric | relation | assertions | — | 𝒜 | set | **YES** | **FORMALLY DERIVED** | **IMPLEMENTED** typed edges |
| **T** | `𝕂×Op×Policy×Authority ⇀ 𝕂×Outcome` | partial fn | K,Op,Policy,Auth | K',Outcome | Policy, Authority | — | **YES** | **FORMALLY DERIVED** | no |
| **Policy** | `(id,ver,Gates,Interval,Resolution)` | object | — | verdict | — | **4 notions** | **YES** | **CORPUS ESTABLISHES** 57.47/42.9 | **IMPLEMENTED** as schema |
| **Assessment** | `P×Evidence×Context×Policy → Σ` | function | 4 | Σ | Policy | — | **YES — newly** | **CORPUS ESTABLISHES** 232.4 | no |
| **Σ** | `(dir, str)`, str ORDINAL | pair | assessment | — | Assessment | structural | **YES** | **FORMALLY DERIVED** | no |
| **Validation** | 4 predicates | predicates | K | Bool×Reason | K | — | **YES** (3 of 4) | **FORMALLY DERIVED** | **IMPLEMENTED** 18 rules |
| **Governance** | `Policy×Transformation → Admissible` | function | 2 | Bool | Policy, History | — | **YES given History** | **CORPUS ESTABLISHES** | **IMPLEMENTED** 3 rules |
| **History** | `𝕂 → Histories`, external | function | K | sequence | — | history-equality | **YES** | **PROVEN external** | **IMPLEMENTED** 47 tests |
| **Lineage** | reachability in `ℛ_der ∪ ℛ_ref` | derived relation | K | ancestors | ℛ | — | **YES** `O(n+m)` | **FORMALLY DERIVED** | **IMPLEMENTED** |

> **No undefined foundational object remains.** `Policy` was the last, and it is corpus-established.

## 3. §13 — Falsification pass. Sixteen attacks.

| # | Attack | Result |
|---|---|---|
| 1 | circular definitions | **FAILED** — graph re-checked, acyclic; `Σ` never feeds `K` |
| 2 | ambiguous notation | **SUCCEEDED** — `E` = Entity/Events, `V` = Value/Vertices. **Counterexample recorded (G-9)** |
| 3 | overloaded terms | **SUCCEEDED** — `Provenance` names 3 objects; `Authority` names 2. **Recorded** |
| 4 | undefined domains/codomains | **FAILED** — all 14 typed |
| 5 | non-computable operations | **PARTIAL** — `GovernanceValid` needs History (a boundary, not a defect); semantic policy equality **undecidable** — **recorded** |
| 6 | hidden external parameters | **FAILED** — Policy and Authority are explicit; actor/time confined to History |
| 7 | contradictory status semantics | **SUCCEEDED then RESOLVED** — PF-6 was a real contradiction; the `(Σ,Γ)` pair dissolves it |
| 8 | non-deterministic transformations | **FAILED** — determinism executed |
| 9 | history-sensitive equality | **FAILED** — `K` has no history field; congruence executed |
| 10 | **policy-sensitive identity errors** | **SUCCEEDED** — same `id`, different `version`, different verdict. **Fixed: version ∈ identity** |
| 11 | impossible states | **FAILED** — `StructuralValid` rejects all four fault classes, executed |
| 12 | unhandled contradictions | **FAILED** — `contradicts` total; unexplained contradictions retained, not suppressed |
| 13 | unrepresented evidence | **SUCCEEDED then FIXED** — `e` was `Set(ref)`; withdrawn≠invalidated forced `Set(ref×polarity×state)` |
| 14 | unrepresented authority | **FAILED** — a `T` parameter; executed rejection |
| 15 | unrepresented governance | **FAILED** — `Γ` + 3 EKP rules |
| 16 | **implementation/theory mismatch** | **SUCCEEDED** — `circular_dependency` is a warning over 2 of 6 relation families; **mis-typed invariant**. Also: EKP `status`/`authority` are **collinear in practice** though declared independent |

**Five attacks succeeded.** Three were fixed within this phase (7, 10, 13); two are recorded as open
(2/3 terminology, 16 engineering). **Two more produced partial results (5).**

> **A falsification pass in which nothing succeeds has not been run properly. Five did.**

## 4. Verdict

**STRUCTURALLY CLOSED** — acyclic, no hidden dependencies, all arrows defined.
**SEMANTICALLY CLOSED for all fourteen objects** — no undefined foundational object remains.

**But NOT COMPLETE.** See artifact 9: closure of the *foundational* graph is not the same as completeness of
the *theory*. Three gaps survive that are not foundational objects but are real.
