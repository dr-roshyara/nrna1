---
artifact: 03-CANONICAL-K
date: 2026-08-30
status: **DERIVED — not adopted, not promoted. Semantic necessity established; representation necessity deliberately NOT claimed.**
---

# 03 · Canonical `K`, Derived

**Why this artifact is constructible while `04`–`13` are not:** `02` §5 proves `K` is **invariant
across every resolution of D-1** (16/16 subsets, identical components). The one open decision that
looked foundational does not reach `K`.

## 1. Method — derivation, not patching

Mandate §17 forbids starting from the old model and patching it. So `K` is derived from the **14
forced operations** (`02` §3): for each, the components it needs to be well-defined; then a
**removal test** per component.

## 2. Executed removal test

```
remove A   -> breaks 16 ops (12 FORCED)  => REQUIRED   Add, Assess, Authorize, Derive, Determine, Promote…
remove S   -> breaks  7 ops ( 6 FORCED)  => REQUIRED   Assess, Authorize, Determine, Promote, Reject, Withdraw
remove PI  -> breaks  6 ops ( 3 FORCED)  => REQUIRED   Derive, Determine, Revise
remove R   -> breaks  6 ops ( 3 FORCED)  => REQUIRED   Derive, Supersede, Validate
remove EL  -> breaks  5 ops ( 4 FORCED)  => REQUIRED   Assess, Qualify, Validate, Withdraw
remove T   -> breaks  4 ops ( 2 FORCED)  => REQUIRED   Revise, Supersede
remove Dt  -> breaks  1 op  ( 1 FORCED)  => REQUIRED   Add
remove H   -> breaks  1 op  ( 1 FORCED)  => REQUIRED   Replay
```

**Every component is load-bearing for at least one FORCED operation. Nothing is removable.**

## 3. The derived result

```
𝒦 = (K, H)                                    the SYSTEM state
K  = (D_t, 𝒜, ℛ, Σ_c, E_L)                    the KNOWLEDGE state
     with Π and t carried inside each assertion
H  external to K — reachable only by Replay, a class-4 audit operation
```

| Component | Forced by | Classification |
|---|---|---|
| `𝒜` assertions | 12 forced ops | **intrinsic** |
| `D_t` recognised dimensions | `Add`, via `UNKNOWN ≠ ABSENT` | **intrinsic** — *this is the component the terminal `K=(𝒜,ℛ)` lacks* |
| `ℛ` relations | `Derive`, `Supersede`, `Validate` | **intrinsic** |
| `Σ_c` epistemic-state carrier | `Assess, Authorize, Determine, Promote, Reject, Withdraw` | **intrinsic OR derived — undecided, see §4** |
| `E_L` evidence links | `Assess, Qualify, Validate, Withdraw` | **intrinsic OR derived — undecided, see §4** |
| `Π` origin | `Derive, Determine, Revise` | **intrinsic**, inside the assertion (`t=0` proof; step 265 `π ∈ K`) |
| `t` temporal | `Revise, Supersede` | **intrinsic**, inside the assertion |
| `H` history | `Replay` only | **EXTERNAL** — `𝒦=(K,H)`; `History(K) ≠ K` executed |

## 4. The limit of this result — stated, not hidden

`CORPUS` — Step 273 §273.31 supplies the safeguard, and it applies to my own result:

> *"Distinguish **semantic necessity** from **representation necessity**. `EvidenceLinks required`
> does not imply `EvidenceLinks primitive`. Likewise `Σ required` does not imply `Σ stored`. It may
> be derived."*

> **My removal test establishes SEMANTIC NECESSITY only.** It shows each component must be
> *expressible*. It does **not** show it must be a *stored top-level component*.
>
> **`Σ_c` and `E_L` are exactly the two where this bites.** `Σ` is policy-relative (executed:
> identical evidence → `Supported` at `min_support=2`, `Unknown` at `3`), so a *stored* `Σ` admits an
> update anomaly; a *derived* `Σ` cannot carry an accepted commitment not recoverable from current
> evidence. **The corpus poses this as `Model A / B / C` at §273.12 and explicitly says "do not
> decide beforehand — the operation tests decide."**
>
> **Those tests are Step 273's work, not mine. I do not pre-empt them.** → **D-4** (`19` §D-4).

## 5. Ontology vs representation — made explicit as mandate §6 requires

| | |
|---|---|
| **Ontology** | what must be *distinguishable*: assertions · relations · recognised dimensions · epistemic state · evidence attachment · origin · time · history. **This is what the removal test establishes.** |
| **Representation** | how those are *stored*: which are fields, which are indices, which are recomputed. **Undetermined, and deliberately so.** |

**The terminal `K = (𝒜, ℛ)` is a representation claim presented as an ontology claim.** That is why
it could lose `D_t` without any test failing — no test was quantified over the ontology.

## 6. What this changes relative to the terminal model

| Terminal | Derived here | Class |
|---|---|---|
| `K = (𝒜, ℛ)` | `K = (D_t, 𝒜, ℛ, Σ_c, E_L)`, `𝒦=(K,H)` | **PROMOTION** — `D_t` is corpus (Zero lens); `Σ`/`E_L` are corpus (Step 272/273) |
| `ℛ ⊆ 𝒜×𝒜×RelationType` | **unresolved** — the corpus's `r=(E₁,E₂,T,R,Q,E,Σ,τ)` is n-ary and 8-field | **REPAIR, blocked** → `19` §D-5 |
| History inside or outside | **outside, confirmed** | **REFINEMENT** |

**No INNOVATION was used.** Every component above is present in the corpus; the contribution is the
**derivation of which are forced**, not the invention of any.

## 7. Status

> **`DERIVED`. NOT adopted, NOT promoted, NOT canonical.**
> It contradicts no ratified artifact *(`v0.2` states no `K` tuple)*, and it is offered as input to
> Step 273's own deletion/replacement tests — which are already running and are the right place for
> it to be confirmed or refuted.
