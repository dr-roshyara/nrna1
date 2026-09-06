---
artifact: 06-ASSERTION-ATTACK
date: 2026-08-30
status: **NOT FORMALLY CLOSED — 1 internal contradiction · 2 untyped components · 1 open sub-test**
---

# 06 · Attack on the Assertion Model

`Assertion = (id, P, e, c, t, Π)`.

## 1. Component audit

| Component | Type defined? | Identity? | Equality? | Lifecycle? |
|---|---|---|---|---|
| `id` | ✅ `H(P,e,c,t,Π)` | ✅ self | ✅ | 🔴 **unstable — see §2** |
| `P` | 🟡 **CONTESTED** — `(E,D,V)` vs `(S,ρ,O,Γ)`; step 262.15 *"remains an open sub-test"* | ✅ | ✅ | ✅ immutable |
| `e` | ✅ 9-field (amended) | 🔴 **Evidence has NO identity** — `ref` points at the referent, not the item | 🔴 | 🟡 `state` mutable |
| `c` Context | 🔴 **never typed** — scoping is asserted, the type is not given | 🔴 | 🔴 | — |
| `t` | ✅ `[vf,vt)` bitemporal | — | ✅ interval | 🟡 `vt` may close |
| `Π` | ✅ intrinsic reference (step 265: `π ∈ K`, object outside) | ✅ | ✅ | ✅ immutable |

**Two components — `c` and `e`'s item identity — have no type.** `Context` appears in every
signature in the theory (`Assertion`, `Assessment`, `Auth(a,r,c,p)`, `E_q`) and **is never given a
type anywhere in the corpus.** It is the most-used untyped symbol in the theory.

## 2. The internal contradiction — executed

The field table declares `id` **derived** from `H(…,e,…)` **and** `e.state` **mutable**.

```
id before withdrawal : 86a0330e2b65
id after  withdrawal : a9d84e7a43d8
SAME? False
R edge ('86a0330e2b65', …) now dangles: True
```

> **A legal operation (withdrawing an evidence item) re-keys the assertion and puts `K` into a state
> that `StructuralValid` rejects.** The identity rule and the mutability rule cannot both hold.
> This defect was *introduced* by adding `state` to `e` after `id` was fixed to hash `e`.

## 3. The mandated counterexamples

| # | Question | Answer | Evidence |
|---|---|---|---|
| 1 | Can two assertions conflict? | ✅ yes, via `ℛ` | — |
| 2 | …and can that conflict be evidenced? | 🔴 **NO** | `ℛ` is a bare triple (`05` §2) |
| 3 | Can an assertion be uncertain? | 🔴 **NO** | no carrier; `id` excludes any `u`, so two assertions differing only in uncertainty are **the same assertion** |
| 4 | Can it be contested without contradictory evidence? | ✅ yes | `Γ=Contested` is independent of `Σ`; the corpus states the requirement at 271.20 |
| 5 | Can an assertion exist without evidence? | ✅ yes — `e` has cardinality `0..n` | and then `Σ=(Neutral,None)`, correctly ≠ Refuted |
| 6 | Can evidence exist without an assertion? | 🟡 **type-yes, semantically-no** | `E_q` is indexed **by claim `q`** (230.15); the amended 9-field record **dropped the index**, so the theory says yes where the corpus said no |
| 7 | Can evidence exist without provenance? | 🔴 **NO** in the corpus | `provenance` is a required field of `E_q` (230.15) |
| 8 | Can an assertion exist without lineage? | ✅ yes | `Lineage = Π ∘ ℛ_der*`; with no derivation edges it is just `Π` |
| 9 | Can an imported assertion exist at `t=0`? | ✅ **yes, and this is the load-bearing counterexample** | at `t=0` History is empty; an imported assertion has an origin and no transformation produced it ⇒ **`Π` must be intrinsic, and `Π ≠ History`**. Independently affirmed by step 265: `Provenance ≠ History(T)` |

**Counterexample 6 is the sharpest new one.** Dropping the claim index `q` from `E_q` makes evidence
free-floating. Two consequences: an evidence item can no longer say *what it is evidence for*, and
the **independence relation** needed for corroboration-style sufficiency has no domain to live on.

## 4. Is `P = (E,D,V)` adequate? — the corpus's own answer

Q14 supplies it and then, in §3 of the same file titled *"The biggest mathematical problem"*, refutes
it: it *"doesn't naturally represent … 'Assertion A contradicts Assertion B' … 'Evidence E supports
Assertion A'"* — and recommends `P = (S,ρ,O,Γ)` as *"a very important correction."*

**Never adopted.** `AttributeProposition` occurs in exactly one file. Step 262.15 reopens it and
leaves it as an **open sub-test** with two undecided models.

> **The assertion model rests on a proposition type that its own source calls the biggest
> mathematical problem in the model, and that the corpus's latest step still records as open.**

## 5. Verdict

> **NOT FORMALLY CLOSED.** One internal contradiction (`id` over mutable `e.state`), two untyped
> components (`Context`, evidence-item identity), one contested and openly-unresolved component
> (`P`), and one capability absent by construction (uncertainty).
>
> **What survives strongly:** `Π` intrinsic (the `t=0` argument is a genuine proof, independently
> re-affirmed by step 265), bitemporal `t`, content-addressed identity **as a concept**, and the
> five-equality hierarchy `history ⊊ structural ⊊ semantic`.
