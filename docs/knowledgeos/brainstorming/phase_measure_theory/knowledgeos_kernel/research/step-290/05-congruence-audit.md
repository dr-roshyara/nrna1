# 05 — Congruence Audit (mandate §6)

## Is there an INDEPENDENT corpus basis for requiring `K_1 ≡ K_2 ⇒ o(K_1) ≡ o(K_2)`?

$$\boxed{\textbf{YES — } \texttt{258.9}, \textbf{ and it is independent of } \approx.}$$

> `258.9`: *"For every valid transformation `T ∈ 𝒯` we require `K_1 ≡_K K_2 ⇒ T(K_1,c) ≡_K T(K_2,c)` for
> all admissible common contexts `c`. **This is the critical mathematical requirement. If it fails, then
> `≡_K` is too coarse.**"*

`258.30` **Proposition 258-A**: `F ∘ T_H = T̄ ∘ F` — every mandatory transformation must **factor
through** the abstraction. `258.23` gives the commuting diagram.

**None of these mentions `≈`.** The requirement is stated over `≡_K` and `𝒯` alone.

## The four things, kept separate

| | Status |
|---|---|
| **equality itself** (`≡`) | 🔴 no independent definition — two unratified candidates (`02`) |
| **congruence of operations under equality** | ✅ **required by `258.9`**; 🔴 **unproven globally** (`259`: `𝒯` not closed) — and **`=` is EXECUTED not a congruence** (`288/06 §G`) |
| **observational equivalence** (`≈`) | ✅ defined (`261.5`) 🔴 `𝒪_K` open |
| **transformation semantics** (`δ`/`𝒯`) | 🔴 commit case unspecifiable; `𝒯` unratified |

## ⚠️ The mandate's trap, and the check I ran against myself

> *"Do not use the existence of a cycle as evidence that `≡` and `≈` are identical. A dependency relation
> is not a semantic identity."*

**Step 289 found cycles `≈ → ≡ → ≈` (Z-1) and `≈ → ≡ → bindings → ≈` (Z-3).** A careless reading turns
*"they depend on each other"* into *"they are the same."*

$$\boxed{\text{A dependency edge is a claim about DERIVATION ORDER. Identity is a claim about EXTENSION. Neither implies the other.}}$$

**Not used as evidence here.** And the check cuts further: **Z-1's edge `≈ → ≡` came from `261.21`'s
candidate** — i.e. **the cycle existed in my graph *because of the candidate proposal*, which I had
mis-modelled as a definitional dependency.**

### Consequence for Step 289's cut
| | before | after |
|---|---|---|
| cycles | 4 | **3 necessary + 1 candidate-dependency** |
| Z-1 status | CONTINGENT on the "conflict" | **weakened** — a proposal, not a definition |
| minimal cut | `{≡}` | ✅ **still `{≡}`** — carried by **Z-2** (`congruence ↔ ≡`) and **Z-4** (`congruence → ≡ → δ → congruence`), both classed **NECESSARY** and both surviving all three deletion tests |

$$\boxed{\{\equiv\} \text{ survives. Its justification narrows from four cycles to three, and strengthens: the surviving three are all NECESSARY.}}$$

⚠️ **Note the direction of this correction: the cut is *better* evidenced after the withdrawal, because
the cycle that depended on a misreading is gone and the remaining ones do not.**

## STATUS
**ESTABLISHED** congruence has an independent basis (`258.9`, no `≈` involvement); the four notions are
distinct; a dependency is not a semantic identity · **REFINED** Z-1 weakened; `{≡}` still the cut, on 3
necessary cycles · **TECHNICALLY OPEN** global congruence · **BLOCKED** on `𝒯`, `δ`
