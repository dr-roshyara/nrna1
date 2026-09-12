# `carrier-options/` — `V1`: The Carrier Options Paper

**2026-09-07 · input to `OQ-1`, the carrier decision.** Per the approved plan
(`docs/plans/20260907-1520-knowledgeos-forward-programme-plan.md`, Phase 1).

> ⛔ **This paper selects nothing.** It enumerates the carriers **actually in use**, measures them,
> and states what each existing result would inherit. **The decision is `OQ-1` and it is
> Governance's.** Theory v1.2 FROZEN · kernel NOT SELECTED · nothing adjudicated.

---

## 0. The headline — `OQ-1` is not what it says it is

`Theory 13 §1` states the problem as:

> *"KnowledgeOS has no declared mathematical carrier. Every result was produced on
> `r = (value, source, tag, time)` with `|D| ≤ 6`."*

**Measured against the code, both halves of that sentence need correction.**

| claim | measured |
|---|---|
| *"no declared carrier"* | 🔴 **THREE code estates run on three declared carriers** (corrected 2026-09-07 — this paper said two; see [`../oq1-decision-brief/`](../oq1-decision-brief/README.md) §0), and one is a **four-level class-parameterised ladder** |
| *"`r = (value, source, tag, time)`"* | ⚠️ **CORRECTED 2026-09-07** — see [`../oq4-witness/`](../oq4-witness/README.md) §5. The exact 4-tuple as written appears in no `.py`, **but `KR-REP-REDUCTION`'s `Rec(v, s, t, rank)` is plainly what it paraphrases.** My original wording was literal and misleading. **The sharper finding: the zero-algebra estate runs on at least TWO record types** — `Item(token, source, uncertainty, scope, polarity, node, edge_to)` in `KR-ZERO-ALGEBRA` and `Rec(v, s, t, rank)` in `KR-REP-REDUCTION`. `Theory 13` is not modified and is not called an error. |

$$\boxed{\begin{array}{c}\textbf{The carrier is not missing. It exists twice, in two shapes, and } Theory\ 13 \textbf{ UNDERSTATES the one it names.}\\ OQ\text{-}1 \textbf{ is therefore a RECONCILE-or-RATIFY decision, not a choose-from-nothing decision.}\end{array}}$$

`[INF]` **This changes the decision's cost in the estate's favour.** A carrier chosen from nothing
would orphan every `[EXP]`. A carrier **ratified from what the code already runs** orphans none of the
zero-algebra family, and the reconciliation question narrows to **one seam** — §3.

---

## 1. Carrier A — the zero-algebra estate ⭐ *the operative experimental carrier*

**`verification/zero-algebra/`** · **59 `.py` · 53 `.json` · 50 `.md`** · 22 experiment directories
(`KR-ZERO-ALGEBRA`, `-ORDER`, `-GROUP`, `KR-REP-REDUCTION`, `KR-BRIDGE-01/02/03`, `KR-ZOOM-01/02/03`,
`KR-ZOOM-OUT-01/02/03`, `KR-STATE-01`).

**Verbatim, from `KR-ZERO-ALGEBRA-2026-09/code/zero_algebra.py`:**

```python
@dataclass(frozen=True)
class Item:                               # one element of a representation
    token: str
    source:      Optional[str]   = None   # provenance
    uncertainty: Optional[float] = None
    scope:       Optional[str]   = None
    polarity:    Optional[bool]  = None   # for claim representations
    node:        Optional[str]   = None   # for graph representations
    edge_to:     Optional[str]   = None

@dataclass(frozen=True)
class Rep:                                # a representation D
    cls:   str                            # R1 | R2 | R3 | R4
    items: Tuple[Item, ...] = ()
```

$$\text{Carrier A} \;=\; Rep = \big(cls,\ (Item_1,\dots,Item_n)\big), \qquad Item \text{ has } \mathbf{7} \text{ fields}$$

**And it is a LADDER, not a single level** — from `KR-REP-REDUCTION-DESIGN`:

| level | content |
|---|---|
| **`R1`** | token only — *and the old generator **violated its own `R1` declaration in 40.2 % of cases*** |
| **`R2`** | token + **within-case rank** `∈ {1,2,3}` |
| **`R3`** | token + **2-significant-digit value** — *rounding creates ties; ranks cannot* |
| **`R4`** | fuller representation |

⚠️ **Consequence already proven on this carrier:** `E_S(D) = D ∖ S` is **invalid at `R2`** — removing
an item from a rank vector leaves ranks `{2,3}`, which is not a valid rank vector. **Set subtraction
is not the elimination primitive.** *(`PROGRAMME-STATUS-2026-09-03`, Correction 1.)*

**What runs on it:** the `Zero`, representation-reduction, bridge and zoom families — i.e. **the
`[EXP]` results `Theory 13` describes**, including the three separations, `FR-001`, and the
2026-09-07 finding that `Zero` is not an element property.

## 2. Carrier B — the v1.2 simulation estate

**`research/knowledgeos-sim/`** · **55 `.py` · 109 `.json`** · verified to run this session.

**No single record type. Per-concern frozen dataclasses:**

| class | fields |
|---|---|
| `EVal` | `value, reason, evaluator, dependencies, contradiction, provenance_state, temporal_state, operational_state, theory_status, domain_restricted, trace` |
| `Arg` | `id, rel` *(a typed relation — **not** a signed scalar)*, `weight, source, derived_from, about` |
| `State` | `args, hypotheses, admissible, truth` *(truth = **ORACLE ONLY**)* |
| `Boundary` | `kind, cls, reason, remediable_by, blocking` |
| `Situation`, `ActionTheory`, `World`, `Regime` | the Reiter, multiplicity and distinguishability families |

**Field frequency across the estate** — the honest measure of what this carrier actually carries:

```
provenance 41 · source 37 · context 28 · time 27 · status 23 · content 19 · evidence 14 · value 10
```

`[INF]` ⭐ **That field set is `37`'s structured epistemic object**
`k = (id, content, context, evidence, provenance, uncertainty, status)` — **the correction the corpus
made to itself on 2026-09-01, already implemented in code on 2026-09-02.** Carrier B is not a
proposal; it is running.

**What runs on it:** the 26 named v1.2 experiments — `Sat`, `Zero` readings, factivity, `Contr`/FDE,
closure, history, multiplicity, `N_eff`, distinguishability, Hilbert, composition.

## 3. ⭐ The one seam — and it is narrow

**Carrier A and Carrier B are not rivals; they sit at different points of the same pipeline:**

```
Carrier A            Rep(cls, Item…)          a REPRESENTATION being transformed / reduced
                            │
                            │   ← the seam: what does an evaluator receive?
                            ▼
Carrier B            EVal / Arg / State       an EVALUATION over content, with reasons
```

| | Carrier A | Carrier B |
|---|---|---|
| **object** | a representation `D` | an evaluation / argument / boundary |
| **parameterised by** | a **representation class** `R1…R4` | a **requirement class** (the 8 `Sat_c`) |
| **carries provenance?** | `Item.source` | `provenance_state`, `source` |
| **carries uncertainty?** | `Item.uncertainty` | `weight`, `status` |
| **carries a REASON?** | 🔴 **no** | ✅ **yes — `reason`, `remediable_by`, `trace`** |

$$\boxed{\begin{array}{c}\textbf{The seam is the } \textit{reason} \textbf{ channel.}\\ \textbf{Carrier A can say WHAT was removed. Only Carrier B can say WHY.}\end{array}}$$

⭐ **And that is the same channel three independent results demanded:** `KR-CONTR-FDE`'s two
**boundary** collapses (*"`Standing` has no channel for why"*) · `KR-CONTR-EVAL`'s theorem that
**no flat domain of any cardinality is adequate** and the minimum is a **pair with an indispensable
reason/boundary component** · and `Boundary.remediable_by` itself.

`[INF]` **A carrier decision that keeps A and B separate must declare the seam's contract. A carrier
decision that unifies them must give `Rep` a reason channel.** Those are the two shapes of `OQ-1`,
and this paper does not choose between them.

## 4. Carriers that are DEFINED but carry no executed result

| carrier | form | files | executed result? |
|---|---|---|:--:|
| **`KS = (𝒳,𝒜)`** measurable epistemic state space + filtration `ℱ_0 ⊆ ℱ_1 ⊆ ⋯` | 2026-09-02 *"the new central definition I recommend"*; law deferred — *"when uncertainty is probabilistically modeled"* | **20** | 🔴 none |
| **`K = (𝒜, ℛ)`** | the verification lane's kernel; `(𝒜,ℛ) =_semantic π_K(K_t)`, lossy, **definable not computable** | **98** | ✅ `kos_kernel.py`, 29 experiments |
| **ratified `K_t`** over the 8 primitives | `C-022`/step-049, *"50 attack classes, no counterexample"* | **41** | 🔴 no code |
| **`K_t = (𝒜_t,ℰ_t,ℛ_t,𝒞_t,ℋ_t,Γ_t)`** | the corpus's **first** `K_t` tuple, 6 fields | 7 | 🔴 none |
| **Theory-01** `D, T, R, Q, Π, O, C, E_S` | the current synthesis's primitives | 121 | ✅ **= Carrier A's vocabulary** |
| **`Σ`** 5-axis, `7×5×4×4×4 = 2240` states | fully verified structure, **0 of 5 axes ordered** | **112** | ✅ partly |
| **`𝒟_t`** varying dimension set | the missingness carrier; **population rule blocked** via `258.21` | 18 | 🔴 none |
| **`G_t = (V,E,Φ)`** attributed multigraph | the investigation-path formalization; its *"convergence theorem"* was **found invalid** | 5 | 🔴 none |
| **`𝔥_Λ = (𝓗, ℛ_sem, Λ, Σ_Λ)`** | `FR-001`'s `[PROP]` successor | 7 | 🔴 none |
| **Hilbert-space representation** | `KR-HILBERT` — **PARTIALLY CONFIRMED, weakest sense**; H1/H3/H7/H8 refuted | 37 | ✅ negative |

$$\boxed{\textbf{Ten candidates. THREE carry executed results. Two of those three are Carriers A and B.}}$$

## 5. What each existing result would inherit

| decision | inherits | orphans |
|---|---|---|
| **ratify Carrier A** (`Rep(cls, Item)`, ladder `R1…R4`) | the whole `Zero`/reduction/bridge/zoom family — **the three separations, `FR-001`, the 2026-09-07 `Zero` result** | the v1.2 evaluation family loses its `reason` channel |
| **ratify Carrier B** (per-concern typed objects) | the 26 v1.2 experiments, `Contr`/FDE, closure, history, multiplicity | the reduction ladder — and `R2`'s proven `E_S ≠ ∖` result has nowhere to live |
| **ratify both, declare the seam** | **everything currently executed** | nothing — **but the seam contract becomes a new obligation** |
| **ratify `KS = (𝒳,𝒜)`** | a clean measure-theoretic frame, and `Γ(K_t) ∈ 𝒜` | 🔴 **every executed result** — no code runs on it |
| **ratify the 8 primitives as the carrier** | the ratified surface and `C-022`'s 50 attack classes | ⚠️ **CORRECTED 2026-09-07** — *"0 lines of code"* is **WITHDRAWN**. `verification/**/exec/` (**54 `.py`, 5 723 LOC**) implements `class K`, `Assertion(id,P,e,c,t,σ)`, `Proposition(entity,dimension,value)`, `Observation`, `Evidence`. **The ratified vocabulary IS implemented** — it orphans the two experimental families, not everything |

⚠️ **`|D| ≤ 6` is a DESIGN BOUND, not a property.** `Theory 13 §OQ-6` says so of eliminability, and
the results record `n_cases = 1200` per family at that bound. **Any carrier ratified today inherits
the bound as a scope limit, and `OQ-3` (transfer) is the only thing that lifts it.**

## 5b. ⚠️ Correction of record, 2026-09-07

Building the `OQ-4` witness required reading `KR-REP-REDUCTION`'s `carrier.py`, which shows
`Rec(v, s, t, rank)` — the record `Theory 13`'s `r = (value, source, tag, time)` paraphrases.
**§0's second row is corrected above.** **This paper's conclusion is unaffected** — two implemented
carriers, `OQ-1` is reconcile-or-ratify — and it gains a finding: **the zero-algebra estate is itself
not single-carriered.** Full statement: [`../oq4-witness/README.md`](../oq4-witness/README.md) §5.

## 6. What this paper does not do

- **does not recommend a carrier** — `OQ-1` is a governance decision and *"no experiment can decide it"*
- **does not claim Carriers A and B are equivalent** — §3 states the seam and leaves it open
- **does not resolve** whether `Theory 13`'s `r = (value, source, tag, time)` is a simplification or a
  reference to code I did not find. **I searched every `.py` in the repository; no such record exists.**
  Recorded as a **discrepancy for Governance**, not as an error in `Theory 13`.

## 7. Reproduce

```bash
# the two estates
find verification/zero-algebra  -name '*.py' | wc -l    # 59
find research/knowledgeos-sim   -name '*.py' | wc -l    # 55

# Carrier A, verbatim
sed -n '17,40p' verification/zero-algebra/KR-ZERO-ALGEBRA-2026-09/code/zero_algebra.py

# Carrier B's field frequency
grep -rhoE "\b(provenance|source|context|time|status|content|evidence|value)\b *[:=]" \
     research/knowledgeos-sim/ | sort | uniq -c | sort -rn

# the absent record
grep -rln --include='*.py' -e value . | xargs grep -l source | xargs grep -l tag   # → empty
```
