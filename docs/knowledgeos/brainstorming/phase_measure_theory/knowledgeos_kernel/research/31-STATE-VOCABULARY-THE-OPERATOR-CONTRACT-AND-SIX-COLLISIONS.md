---
artifact: 31 — STATE VOCABULARY vs FIELDS · the OPERATOR CONTRACT · and SIX collisions in one table
sources: 3 newly renamed prompts (1 byte-identical duplicate)
date: 2026-09-01
verdict: **A 7-slot OPERATOR CONTRACT that shapes five of the pieces `291` found missing. A SEVENTH independent arrival at non-monotonicity — with a real sharpening: the transformation law belongs to the OPERATOR, not to `K`. And SIX new glyph collisions in a single table, one of which would corrupt Decision 3. Plus a correction to my own artifact `30`.**
---

# 31 — Vocabulary, Contract, and Collisions

## 1. ⚠️ A correction to `30` — *vocabulary* is not *fields*, and neither is a tuple

**The source states the right distinction:**
> *"**the eight primitives are the state VOCABULARY; they are not eight equal 'fields'.**"*

**…and then writes them as an 8-tuple:**
$$K_t = \langle E_t, S_t, V_t, O_t, P_t, R_t, \Pi_t, A_t \rangle$$

$$\boxed{\begin{array}{c}\textbf{A tuple IS field structure. The notation contradicts the caution one line above it.}\\ \textbf{And } 258.35 \textbf{ forbids exactly this: } K \textbf{ must be defined EXTENSIONALLY through behavioural}\\ \textbf{sufficiency, "not compositionally through an arbitrary tuple."}\end{array}}$$

### ⚠️ And my own `30` carries a milder version of the same defect
I wrote `K_t = {Entity, State, Event, Observation, Proposition, Relation, Policy, Action}` — **a set**.
A set is better than a tuple (no ordering, no field positions) **but it still reads as *"the state IS
these eight things"*, which is compositional.**

$$\boxed{\begin{array}{c}\textbf{CORRECTED: the eight are the VOCABULARY IN WHICH state is expressed —}\\ \textbf{not the state's components, and not its fields.}\end{array}}$$

✅ **The source's instinct is right and its notation is wrong; my notation was less wrong and still
wrong.** *(Recorded at source in `30`.)*

## 2. 🔴 SIX glyph collisions in ONE table

The 8-primitive table assigns single letters — **and six of them are already taken:**

| assigned | to | **already means** |
|---|---|---|
| **`S`** | State | 🔴 **`Σ`'s SUPPORT axis** `{None…VeryStrong}` |
| **`V`** | Event | 🔴 **`Σ`'s VALIDITY axis** `{Current, Stale, Expired, Unknown}` |
| **`R`** | Relation | 🔴 **`Σ`'s RESOLUTION axis** `{Open…Unresolvable}` |
| **`A`** | Action | 🔴 **`Σ`'s ACQUISITION axis** (7 modes) |
| **`O`** | Observation | 🔴 **the OPERATION set `𝒪`** — already `G-78` |
| **`Π`** | **Policy** | 🔴🔴 **PROVENANCE** |

### ⭐ `Π` is the dangerous one

**`Π` = provenance is load-bearing in three places:**
$$id = H(P,e,c,t,\mathbf{\Pi}) \qquad Lineage = \mathbf{\Pi} \circ \mathcal R_{der}^* \qquad \textbf{Decision 3: } \mathbf{\Pi} \in\ \equiv\ ?$$

$$\boxed{\begin{array}{c}\textbf{A reader importing } \Pi \textbf{ as POLICY would silently re-read Decision 3 —}\\ \textbf{the programme's central normative question — as "is POLICY part of semantic equality?"}\\ \textbf{It is not. It is provenance.}\end{array}}$$

⚠️ **And `Σ` itself is `Q4A`-verified with those exact axis letters** (`7×5×4×4×4 = 2240`, five corpus
files). **Four of the six collisions hit the one fully-verified structure in the theory.**

**Collision count: `𝒪` · `≅`/`≡` · `δ` · `ℐ` · `M` · **+6** = ELEVEN.** **`G-102`.**
✅ **Consistent with `G-101`'s root cause** — parallel minting, no shared registry — **and this is the
first instance where a single artifact produced six at once.**

## 3. ⭐ The OPERATOR CONTRACT — shaping five pieces `291` found missing

$$\boxed{\mathsf{Op} = \langle Name,\ Input,\ Pre,\ Transform,\ Post,\ Inv,\ Evidence \rangle}$$
> *"We should **not** define operators merely because a Gītā concept sounds analogous. We need to define
> them so that every transformation is **well-typed, mathematically valid, traceable, and preserves the
> invariants**."*

**Mapped against `291 §Audit A`'s nine closure senses:**

| `291` found | this contract supplies |
|---|---|
| signatures — **8 of 22** | `Input` · `Transform` |
| **semantic bodies — 0 of 22** | `Transform` — ⚠️ **a slot for the body, not a body** |
| pre/postconditions — *"to be determined"* (`20`) | ✅ **`Pre` · `Post`** |
| invariants — *"to be determined"* | ✅ **`Inv`** |
| — | ⭐ **`Evidence`** — a slot with no counterpart in the corpus registry |
| identity — **0 of 22** | 🔴 **absent** — ⚠️ `Name` is a label, not an identity |
| ratification — 0 of 22 | 🔴 absent |

$$\boxed{\begin{array}{c}\textbf{A FIFTH } T4\text{-SHAPE. Seven slots, and the corpus can fill } Name \textbf{ and part of } Input.\\ \textbf{It shapes what an operation must DECLARE; it declares nothing.}\end{array}}$$

⚠️ **Orthogonal to `22 §5`'s five-slot operation-IDENTITY schema** (doer · instrument · means · endeavour
· enabling source): **that asks *what an operation is made of*; this asks *what it must declare*.**
**Two shapes, two axes — and `291` needs both.** **`O-Q5`.**

## 4. ⭐⭐ A SEVENTH arrival at non-monotonicity — with a genuine sharpening

> *"We should **NOT** impose *knowledge can only increase*… We should not assume `K_t ⊆ K_{t+1}` for
> every operation. Instead `K_{t+1} = op(K_t,x)` and **the operator declares its transformation law**."*

**Prior arrivals:** `Σ`-axis measurement · executed retraction · `060 §60.71` · `274.32` · the Pareto
refusal (`21`) · the simulation (`26`). **This is the seventh.**

### ⭐ And it says something none of the six said
$$\boxed{\begin{array}{c}\textbf{Monotonicity is not a property of the STATE SPACE.}\\ \textbf{It is a per-OPERATOR declaration.}\end{array}}$$

**That reframes the whole join-semilattice dispute.** `060 §60.71` asked *"is `𝕂` a semilattice?"* and
answered *"not proven"*; `17` explained the overclaim as a **filtration/state conflation**. **This says
the question was mis-addressed: the transformation law belongs to `op`, so `merge` may be inflationary
while `retract` is not — and neither fact is a property of `𝕂`.**

✅ **Converges with `261.19`'s *"no single equality relation is adequate for all operations"*:
**per-operator, again.** **Two independent per-operator results — equality and monotonicity — pointing
at the same structural conclusion: `𝕂`'s algebra is not global.** **`M-Q7`.**

## 5. Tally

| Tier | `30` | **`31`** | new |
|---|---:|---:|---|
| **T4 KERNEL** | 3 | **3** | **0** |
| **T4-SHAPE** | 4 | **5** | — |
| T3 | 17 | **18** | 0 |
| T2 | 26 | **27** | 0 |
| T0 | 20 | **21** | — |
| constraints | `C1`–`C14` | **`C1`–`C15`** | ⭐ `C15` |

$$\boxed{C15:\ \text{Monotonicity is a per-OPERATOR declaration, not a property of } \mathbb K}$$
**New T0:** `K_t` as an 8-tuple — forbidden by `258.35`, and contradicted by its own source one line above.
$$\boxed{\textbf{Still ZERO new primitives. Still ZERO new canonical relations.}}$$

## 6. Register

| ID | Item | Class |
|---|---|---|
| **`G-102`** | 🔴🔴 **SIX glyph collisions in one table** — `S`·`V`·`R`·`A` against `Σ`'s verified axes, `O` against `𝒪`, **`Π` against PROVENANCE**. ⚠️ **`Π`-as-Policy would silently re-read Decision 3.** **Eleven collisions total** | **DOCUMENTARY — now urgent** |
| **`O-Q5`** | ⭐ The **7-slot operator contract** `⟨Name, Input, Pre, Transform, Post, Inv, Evidence⟩` — shapes pre/post/invariants, adds an **`Evidence`** slot with no corpus counterpart; **no identity slot** | **T4-SHAPE (fifth)** |
| **`M-Q7`** | ⭐⭐ **Monotonicity is per-operator, not a property of `𝕂`** — reframes the join-semilattice dispute; converges with `261.19`'s per-operation equality | **DERIVED** |
| **`V-Q1`** | The eight are a **state VOCABULARY**, not fields and not a tuple (`258.35`); ⚠️ **`30` corrected at source** | **CORRECTION** |

## 7. What this does not license
⚠️ **The operator contract is not adopted** — it has no filled slot. ⚠️ **`M-Q7` does not establish any
operator's law** — it relocates where such a law would live. ⚠️ **No glyph is renamed**; `G-102` is for
the registry owner, and it is now the most urgent registry item. 🔴 **Steps 288–291 unchanged; all `R6`.**
