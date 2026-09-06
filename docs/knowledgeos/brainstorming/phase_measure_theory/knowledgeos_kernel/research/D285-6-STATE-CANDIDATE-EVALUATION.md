---
artifact: D285-6 · STATE CANDIDATE EVALUATION
status: **OUTCOME B — LOSSY SEMANTIC PROJECTION.** Restated 2026-08-31 under the HPA equality mandate.
format: 7-section D285-x template (HPA review, 2026-08-31)
---

# D285-6 · Models A–F Evaluated

## 1. Property Statement

$$(\mathcal{A},\mathcal{R}) \;=_{\text{semantic}}\; \pi_K(K_t)$$

after unpacking `Assertion` into its fields, and modulo the declared drop of
`{Event, Policy, Action}`.

⚠️ **Previously stated as an unqualified `=`. That was not a well-formed proposition** — see §5.

## 2. Trivial vs Substantive

| | |
|---|---|
| **Trivial** | that *some* relation exists between the two models — both mention `Relation` and `Proposition`, so Model F (separate products) is refuted by inspection |
| **Substantive** | **which** relation, **under which equality**, and **what it loses**. This is the whole result |

## 3. Execution

`exec/t285_reconcile.py` (T-A, T-B) and `exec/t285_equality.py`.

| Model | Verdict | Ground |
|---|---|---|
| **A** `K_t = (𝒜,ℛ,Σ,…)` complete | 🔴 **REFUTED** | ratified `K_t` contains `Action/Event/Policy`; the verification lane puts all three **outside** `K` |
| **B** `K_t = (𝒜,ℛ)` | 🔴 **REFUTED** | FA-4: `K_t` is *"state over 8 primitives"*, not 2 |
| **C** `(𝒜,ℛ) = π_K(K_t)` | ✅ **HOLDS**, qualified | every verification-only item is a **field of an assertion**, not a new primitive |
| **D** `K_t = π_knowledge(S_t)` | ✅ holds | compatible with C; reverses the vantage |
| **E** `S_t = (K_t,E_t,H_t,G_t,Q_t)` | ✅ holds | compatible with C; **names the complement** |
| **F** separate products | 🔴 **REFUTED** | `Relation` and `Proposition` shared |

**The projection, primitive by primitive:**

| Ratified primitive | `π_K` image |
|---|---|
| `Proposition` → `P` inside `Assertion` · `Entity` → `E` inside `P` · `Relation` → `ℛ` · `State` → the carrier | mapped |
| **`Observation`** | **→ `e`, *after* `Qualify`** |
| `Event` · `Policy` · `Action` | **DROPPED — declared external** |

## 4. Qualification

**Two, and both are load-bearing.**

**(a) `π_K` is DEFINABLE and NOT COMPUTABLE.** `π(Observation) → e` requires
`Qualify : Observation × Policy → Evidence`, which **has no body** in the corpus (one undefined hit,
Step 170). `G1`, irreducible.

**(b) The observation layer is NOT missing** *(revised 2026-08-31)*. `W` = **Domain reality**,
`Ω` = **Sañjaya**, the state-observation capability — corpus-formalised 2026-08-26 with six typed
principles. **The structural gap is closed; only the function body remains.**

## 5. Equality Specification ⭐

| Equality | Holds? | Why |
|---|---|---|
| **structural** | 🔴 **FALSE** | `π` image = `{Entity, Observation, Proposition, Relation, State}`; target = `{Assertion, Relation}`. **`Assertion` is not a ratified primitive** |
| **semantic** | ✅ **TRUE** | only after unpacking `Assertion` → `{Proposition, Entity, Observation}` + `{id,c,t,Π}`, modulo the declared drop |
| **observational** | 🔴 **FALSE** | answerable in `(𝒜,ℛ)`: `member`, `contradicts`, `supersede`, `lineage`. **Not answerable: `replay`, `policy-eval`, `authorize`** |

> **`=_observational` failing IS what "lossy" means.** It is not an aside — it is the content of the
> qualification. **Three primitives and three query classes are lost**, by design.

## 5b. Scope of the projection claim (reviewer A's tension, resolved)

```
semantic state projection   = ESTABLISHED
operational equivalence     = NOT ESTABLISHED
observational equivalence   = REFUTED
computable projection       = BLOCKED (Qualify)
```

**A asked:** *"If the projection is established but the operations over the source state have not
been, in what sense is the projected model operationally equivalent?"* **Answer: it is not.** The
claim is a **state-level semantic projection only**, and the four lines above are now part of it.

## 6. Independence

**No Gītā input.** Models A–F are discriminated by **primitive-set arithmetic** over FA-4 and the
verification lane's own externality declarations. `INDEPENDENT`.

The Gītā lens contributed nothing to this result **except** the `Sañjaya` recovery in §4(b) — which is
corpus archaeology, not philosophical inference.

## 7. Classification

| Component | Verdict |
|---|---|
| Models A, B, F refuted | **R5** — formally supported, executed |
| Model C (lossy semantic projection) | **R5** — constructed and equality-typed |
| `π_K` non-computability | **R5** — the blocker is named, not inferred |
| Blocker 1 needs governance? | 🔴 **NO** — **it needs `Qualify`.** Mathematical evidence discriminates; no normative choice is required |

> **Outcome B achieved, not computably, and lossy at the observational level.**
> **This is a stronger and narrower claim than the previous unqualified one.**
