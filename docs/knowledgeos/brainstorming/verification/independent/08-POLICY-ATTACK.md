---
artifact: 08-POLICY-ATTACK
date: 2026-08-30
status: **Policy internal structure OPEN — declared open by the corpus's own highest step**
---

# 08 · Attack on Policy

## 1. The corpus's own latest word — which settles the question before any attack

**Step 271 (`20260830-204917`) is the highest step in the corpus.** It is titled *POLICY SEMANTIC
MINIMALITY AND ASSESSMENT BOUNDARY*, it is entirely about Policy, and it says:

> *"Therefore **Step 271 does not declare closure.** It narrows the problem."*

Its own closure table:

| Area | Step-271 status |
|---|---|
| Policy existence | 🟢 |
| **Policy semantics** | 🟡 |
| **Policy internal structure** | 🔴 **open** |
| **Policy conflict semantics** | 🔴 |
| Assessment semantics | 🟡 |
| **Numerical assessment** | 🔴 audit required |
| **Statistical independence** | 🔴 not established |
| **Epistemic status Σ** | 🔴 |
| Missingness | 🟡/🔴 |

It records `H₂₇₁` explicitly as **`HYPOTHESIS`, not theorem**, and commissions a **Step 272** —
*"Derive the minimum epistemic-status structure…"* — **which does not exist.**

> **The research track's own final act was to declare Policy internal structure and Σ open and
> commission the next step. It was mid-flight when it stopped.**
>
> *(Admissibility note: step 271 postdates the closure audit and has read it (`01` §3). That makes it
> inadmissible as corroboration and **admissible as disagreement** — a downstream source that has
> read a closure claim and still records "does not declare closure" is not echoing.)*

## 2. The claimed `Policy = (id, version, Gates, ValidityInterval, ResolutionBehavior)`

| Component | Corpus support |
|---|---|
| `Gates` as a **set** | ✅ executed — order-permuted policies structurally unequal, extensionally equal |
| three-valued conjunction, `Unknown → ResolutionBehavior` | ✅ `42.9` + `42.12`, executed |
| `no averaging` | ✅ `42.10`, executed — *"a 95%-admissible decision is inadmissible"* |
| `(Policy, ∧)` meet-semilattice, `∨` refuted | ✅ derivation sound |
| `version` part of identity | ✅ and independently required by `v0.2 R-1` |
| **`ValidityInterval`** | 🟡 **3 files corpus-wide** — thin |
| **the CONTENT of a gate** | 🔴 **no evidence→strength rule anywhere** |

**The shell is well-supported. The interior is empty**, exactly as step 271 says.

## 3. The two admissibility laws — **NOT a clean refinement pair**

Read directly from `step-042`:

```
42.9   Admissible(d,K,t) ⟺ Pre(d,K,t) ∧ Invariant(d,K,t) ∧ Assurance(d,K,t) ∧ Authorization(d,K,t)
42.40  DC(d) = ⟨P, I, A, E, Q, T, O⟩     Q = epistemic sufficiency, O = postconditions
42.41  Admissible(d,K,t) ⟺ P(K,t) ∧ I(K,t) ∧ A(K,t) ∧ E(K,t) ∧ Q(K,t) ∧ T(K,t)
```

**Same predicate name. Same signature `Admissible(d,K,t)`. Different bodies.** Classification
(mandate §15) must be by evidence, not by "the newer is better":

| Candidate reading | Holds? |
|---|---|
| equivalent | **NO** — 42.41 has `E` (evidence) and `Q` (sufficiency); 42.9 has neither |
| **refinement** (42.41 refines 42.9) | **PARTLY** — `Pre→P`, `Invariant→I`, `Authorization→A` map cleanly; **`Assurance` maps to nothing**, and `E`,`Q`,`T` are new |
| different abstraction levels | **NO** — identical signature, identical name |
| **unresolved contradiction** | ✅ **this is the honest classification** |

**Three hard facts the "reconciled as gate sets" reading does not survive:**

1. **42.9 depends on `Assurance`, which the corpus has `REFUTED as definable`** (CB-1; ≥5
   incompatible definitions, one self-referential, measured this pass). **A law whose conjunct is
   refuted is not evaluable.** 42.9 is uncomputable, not merely older.
2. **Neither law matches the ratified artifact.** The ratified DC is a **6-tuple**
   `(Pre,Inv,Auth,Post,Temporal,Evidence)`; 42.41's law belongs to the **7-tuple**; 42.9 omits
   Temporal. The corpus's own witness states it: *"the ratified 6-tuple has **NO ratified
   admissibility conjunction** (42.9 omits Temporal; 42.41 is the 7-tuple's law)."*
3. **`Admissibility` is never defined as a term** — 0 definitional occurrences corpus-wide (`02` §1).
   Two laws, no definiendum.

> **Classification: `G6 — CONTRADICTION`, plus a ratification gap. The ratified decision contract has
> no admissibility law of its own, and both candidate laws fail — one on a refuted conjunct, one on
> arity.**

## 4. Sufficiency — **typed, then removed**

**`Q` = epistemic sufficiency is a named, typed component of `DC(d)` (42.40) and a conjunct of
admissibility (42.41).**

The ratified 6-tuple has **no `Q`**. The witness names the consequence:
> *"there is no component typed as epistemic sufficiency, so this block is INEXPRESSIBLE without
> conflating Q into Evidence — PF-7's loss, executable form."*

> **This corrects a claim both prior passes made.** Sufficiency is **not** an undefined symbol and is
> **not** merely a constitutional parameter. **It was typed as `Q` in the research corpus and dropped
> in ratification.** The gap is a `G4 — governance gap`, not a `G3 — theoretical gap`.
>
> *What remains genuinely open is `Q`'s **body** — no corpus passage says how `Q(K,t)` is evaluated.*

## 5. Who authorises a Policy change? — the regress terminates **twice**, in the corpus

**No constitutional layer needs inventing. Both terminations already exist:**

1. **Stratification, ratified.** `v0.2` `R-1`/`I-11` (GN-19, 2026-08-28) — `Policy-as-content ∈ K_t`
   vs `Policy-in-force (versioned)`; *"Self-modification of the in-force policy without governed
   approval is excluded by construction."*
2. **Externalisation, implemented.** *"The mechanism records authority; it does not grant
   authority."* — measured: **132/132 grants carry `humanActRef`.**

**The exact termination point:** an act **outside** the system, referenced **inside** it.
Typed as `EXTERNALLY GOVERNED`.

⚠️ **But it is a policy-change closure, not a policy-use closure.** The cycle
`Evidence → Policy → K → Assertion → Evidence` (`03` §3.2) is about *using* a policy to qualify an
observation, and **neither termination touches it**. A human act authorises a policy; it does not
qualify an observation.

## 6. Verdict

| Question | Verdict |
|---|---|
| Is `Policy` defined? | 🟡 **shell yes, interior no** — and the corpus's highest step says so itself |
| Are the two admissibility laws reconcilable? | 🔴 **NO** — `G6 CONTRADICTION`; and the ratified artifact has neither |
| Is sufficiency undefined? | 🔴 **NO — it was defined as `Q` and removed.** `G4`, not `G3` |
| Who authorises a policy change? | ✅ **answered, twice, in the corpus** — no invention required |
| Is the authority regress terminated? | ✅ for policy **change**; 🔴 **NOT** for policy **use** in qualification |
