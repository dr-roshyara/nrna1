# 01 — `𝒪` / `𝒪_K` / `𝒯` Reconciliation (mandate §2)

> **§2: *"Do not assume that `𝒪`, `𝒪_K`, and `𝒯` are identical."*** They are not — and Steps 289–290
> collapsed them into a single `{𝒪,𝒯}` cut candidate. **This is the correction.**

| Symbol | First/important locus | Corpus definition | Type | Scope | Closed? | Canonical? | Decision procedure? |
|---|---|---|---|---|---|---|---|
| **`𝒪`** | `256.2` · **`259.7`** · `272A` | the **operation family**; `259.7` splits it into **five kinds** | heterogeneous signatures | whole system | 🔴 **not enumerated** — ⚠️ see §7 | 🔴 | 🔴 |
| **`𝒯`** | `258.9` · **`259.8`** · `260` | the **state-transforming** operations, `T : K → K` | **subset of `𝒪`** (kind 1) | semantic state | 🔴 not enumerated | 🔴 | 🔴 |
| **`𝒪_K`** | **`261.21`** | *"the **closed set** of permitted state **observations**"* | observation functions on `K` | state observations | ⚠️ **asserted closed in the definition, denied in the same section** | 🔴 | 🔴 |
| **`𝒪` (at `261.5`)** | `261.5` | the index of `≈` over **histories**: `∀O ∈ 𝒪` | **observations** | histories | 🔴 | 🔴 | 🔴 |

## 🔴 COLLISION — the glyph `𝒪` carries two meanings

| reading | loci |
|---|---|
| the **OPERATION** family | `256.2` · `259.7` · `272A` |
| an **OBSERVATION** index | **`261.5`** (`∀O ∈ 𝒪` over histories) |

**`261.21` then introduces `𝒪_K` for *"permitted state observations"*.** So `𝒪` is overloaded across
operations and observations, and `𝒪_K` is a third object.
**Classification: `DOCUMENTARY`.** ⚠️ **And it is load-bearing:** Step 289's graph had edges
`𝒪 → 𝒯` and `𝒪 → 𝒪_K` as generic dependencies. **They are not generic — `𝒯 ⊆ 𝒪` is a *subset*
relation (kind 1 of five), and `𝒪_K` relates to kind 5.**

## `259.7` — the five kinds, verbatim

1. Knowledge-state transformations · 2. epistemic assessments · 3. governance operations ·
4. audit/history operations · 5. **observation operations**

> *"Not every operation has the form `T : K → K`."* — with signatures given:
> `Assess : K × X → Assessment` · `Authorize : Actor × Action × Policy → Decision`

## `259.8` — and an inconsistency I nearly reported

`259.8` names as state-transforming: **`Revise` · `Transform` · `Supersede` · `Merge` · `Split` ·
`Withdraw` · `Promote`** — *"**if and only if** the corpus establishes them as state-changing
operations."* Then, **separately**, `Assess` gets a *determination* test
(`K_1 ≡ K_2 ⇒ Assess(K_1,x)=Assess(K_2,x)`) and `Authorize` gets `(K_1,p,a) ↦ Decision`, with the
warning *"we must not manufacture a false universal algebra."*

> ⚠️ **A first extraction of §259.8 merged these into one nine-item list, which would have contradicted
> `259.7`'s typing. It does not — I checked the section verbatim before reporting.** `259.7` and `259.8`
> are consistent. *(Recorded because the near-miss is the same failure mode as `G-67`: an apparent
> inconsistency created by my own reading, not by the corpus.)*

## Consequence: congruence generalizes beyond `𝒯`

`Assess`'s test is a **determination condition on a non-state-transforming operation**, and it is
still stated in terms of `≡`. **So equality-dependence is wider than `𝒯`** — `04` and `08` carry this.

## STATUS
**CORPUS** the five-kind classification; `𝒯 ⊆ 𝒪`; `𝒪_K` a third object; `259.7`/`259.8` consistent ·
**MEASURED** the glyph collision · **DERIVED** Steps 289–290 conflated three objects into one cut
candidate · **DOCUMENTARY** the `𝒪` overload · **UNKNOWN** whether `𝒪_K ⊆ 𝒪` or is independent
