# `KOS-T-0007` — `Σ`

**`[EXP]` · stress case: *multiple definitions · dependency structure · value-set semantics*.**
**Adjudicates nothing. No canonical `Σ`.**

**Corpus scope declared:** `docs/knowledgeos/**.md` **minus** `theory-extraction/` and
`verification/gap-discovery/` — the extracting lanes are not corpus. **4 245 files in scope.**
⚠️ **The same exclusion is applied to IMPLEMENTATION evidence** — new in this pass, stress report §F.

| | |
|---|---|
| **kind** | ⭐ **`value-set`** — `[PROP]` **new kind, demanded by evidence** (stress report §C) |
| **Category** (19-list) | 🔴 none fits — provisional `Concepts` **under protest** |
| **Status** | `[CT]` contradictory · `status_chain: candidate` |
| **Grounding** | **mixed** — `D-05` architecturally-grounded (exercised code); `D-01`–`D-04` corpus-textual |

## Definitions — enumerated, not merged

| ID | definition | shape | in-scope files |
|---|---|---|---:|
| `D-01` | `Σ = (A,S,R,V,C)` — 5 axes, `7×5×4×4×4 = 2240` states | 5-tuple of value-sets | **30** |
| `D-02` | `Σ = {Unknown, Supported, Refuted}` | 3-element set | 8 |
| `D-03` | `Σ = (Neutral, None)` | a pair | 6 |
| `D-04` | `Σ ≅ 𝒫({Support, Refute})` — 4 values, = FDE `(S⁺,S⁻)` | powerset lattice | 13 |
| `D-05` | ⭐ **the IMPLEMENTED `Σ`** — `(status, strength)`, `status ∈ {Supporting, Refuting, Contested, Neutral}`, `strength ∈ {None, Weak, Moderate, Strong, VeryStrong}` | a pair of value-sets | code |
| `Σ₀` | `Σ₀ = (D,S)` — **the superseded 2-tuple** (Step 275) | a pair | **31** |

## 🔴🔴 The finding — the implemented `Σ` implements the **superseded** definition

```python
def Sigma(a, policy_params=None):        # step-280/281/282/exec/kosmodel.py
    if sup and con: return ("Contested",  ORD[...])
    if sup:         return ("Supporting", ORD[...])
    if con:         return ("Refuting",   ORD[...])
    return ("Neutral", "None")
ORD = ["None","Weak","Moderate","Strong","VeryStrong"]
```

**`D-05` is a (direction, strength) pair.** It matches **none** of `D-01`–`D-04` and is
**structurally `Σ₀`, which the corpus records as superseded.**

$$\boxed{\begin{array}{c}\textbf{The running code implements a SUPERSEDED definition.}\\ \textbf{Neither } \mathtt{derived} \textbf{ nor } \mathtt{stipulated} \textbf{ records that, and } \mathtt{selection} \textbf{ has no value for it.}\end{array}}$$

⚠️ `[PROP]` **`selection: superseded`** — stress report §F. **Not applied in this pass.**

## Relationships

| pair | value | basis |
|---|---|---|
| `D-05` ~ `Σ₀` | **`unresolved_equivalence`** | **measured shape match**; `(D,S)`'s value domains are not stated — **not an identity claim** |
| `D-02` ~ `D-04` | **`unresolved_equivalence`** | ⚠️ 3 vs 4 values; **`Conflict` has no counterpart in `D-02`** |
| `D-01` ~ all others | **`unresolved_equivalence`** | ⚠️ **`Supported`/`Refuted` are values of NO `D-01` axis** *(recorded at `19 E2`)* |
| `D-03` ~ `D-05` | **`unresolved_equivalence`** | `(Neutral, None)` **is a value OF `D-05`** — possible containment, see below |

⚠️ **Containment criterion applied and DECLINED as decisive.** `D-03` looks like **an element of
`D-05`'s codomain rather than a definition of `Σ`** — which would make it disposition 4. **Evidence is
insufficient to settle it; `unresolved_equivalence` retained.** The criterion is an **extraction
identity rule, not a KnowledgeOS theorem.**

## Homonyms and symbolic occurrences

| disposition | occurrence | in-scope |
|---|---|---:|
| **4 · symbolic** | ⭐ **`Σ` as SUMMATION** (`\sum`, `Σ_{i}`) | **143 files** |
| **2 · different candidate** | `Σ_Λ` inside `𝔥_Λ = (𝓗, ℛ_sem, Λ, Σ_Λ)` | 8 |
| **3 · ambiguous** | subscripted `Σ_A`-style variants | 15 |

⭐ **143 files of pure symbolic occurrence — the largest disposition-4 class measured.** Without the
homonym rule these would have entered as definitions.

## Implementation

| ID | level | provenance | selection |
|---|---|:--:|:--:|
| **`D-05`** | **`result-produced-on-it`** | **`stipulated`** — `policy_params`, `require_trusted`, `trusted_sources` are inputs | 🔴 **no value fits** — implements a **superseded** definition |
| `D-01`–`D-04` | **`none`** | `n/a` | `n/a` |

## Latest — four independent

**mention** 2026-09-06 · **implementation** 2026-08-31 · **refinement** `D-04`/FDE 2026-09-02 ·
**governed decision** 🔴 **`never`** *(applicable · searched `governance/` · no instance found)*
⚠️ `brainstorming/verification/DECISION-SIGMA-EPISTEMIC-STATUS.md` is titled *"DECISION"* and sits in
**`brainstorming/`, not `governance/`** — recorded as a **naming** observation, **not** a decision.

## Dependencies — recorded, **not** a sequencing rule

`Evidence` (`TG-08`: no identity) · `Assertion` · **an ORDER on strength — `291` measured 0 of 5 axes
ordered** · `Contr` (OPEN) · `Policy`. ⚠️ **No extraction order follows from this list.**

## Proposal / decision / implementation fact — kept separate

| record type | content |
|---|---|
| **proposal** | *"`Σ` should be `𝒫({Support,Refute})`"* — `D-04`, offered |
| **decision** | 🔴 **none exists** |
| **implementation fact** | `def Sigma(...)` returns a `(direction, strength)` pair, exercised in 3 experiment sets |
