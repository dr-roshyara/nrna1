# `KOS-T-0011` — `ℐ` (the invariant register)

**`[EXP]` · stress case: *structural / interface-like classification and implementation mapping*.**
**Adjudicates nothing.**

**Scope:** as `KOS-T-0007` — **and the implementation exclusion is decisive here.**

| | |
|---|---|
| **kind** | 🔴 ⭐ **`register` / `interface`** — `[PROP]` **new kind demanded.** `ℐ` is **a named collection whose members are the contract** — not an object, not an operation, not a relation, not a constant, not a value-set *(its members are **predicates**, not values)* |
| **Status** | `[UN]` · `status_chain: candidate` · ⚠️ **`0 of 7 candidates established`** |
| **Grounding** | 🔴 **contested/ungrounded** — the register is **named but never enumerated** (`G-67`) |

## Definitions

| ID | definition | in-scope |
|---|---|---:|
| `D-01` | `ℐ = {I_1, …, I_n}` — **the candidate set under investigation**, explicitly `≠ a ratified architectural set` | **5** |
| `D-02` | the **schema**: `ℐ(P) : ∀K,o. Admissible(o,K) ⇒ (P(K) ⇒ P(δ(K,o)))` | **2** |
| `D-03` | `ℛ_req` — the **Required Distinction Universe**, `∀d ∈ ℛ_req ∀s₁,s₂ : s₁ ≁_d s₂ ⇒ E(s₁) ≠ E(s₂)` | **54** |
| `D-04` | `ℐ → 𝒫_c` — a mapping into a capability set | **3** |

⚠️ **`𝓘` (33 in-scope files) and `ℐ` (86) are two scripts.** Disposition **3 — ambiguous identity**;
the corpus declares no rule. **Not merged.**

## 🔴 `D-01` ~ `D-03` — a relationship the vocabulary cannot express

`ℐ` is a set of **invariants** (predicates preserved across transitions). `ℛ_req` is a set of
**required distinctions** (equivalence relations that must not collapse). Measured, they are
**differently typed** — yet the corpus's own `ℛ_req` spec and the `𝓘` schema are addressed to the
same obligation, and `ℛ_req` has **54 in-scope files against `ℐ`'s 5**.

| candidate value | why it fails |
|---|---|
| `new_representation` | asserts **same referent** — the types differ (predicates vs relations) |
| `refinement` | neither adds semantics to the other |
| `unresolved_equivalence` | **used** — but it understates: they are **not plausibly the same object**, they are **plausibly two encodings of one obligation** |

$$\boxed{\begin{array}{c}\textbf{Needed: "different formalisations of the same OBLIGATION".}\\ \textbf{Not equivalence of referents; equivalence of what they are FOR.}\end{array}}$$

⚠️ `[PROP]` **`co-obligation`** — stress report §D. **Not applied.** ⛔ **And note this is precisely
where an earlier gap-discovery finding claimed a contrapositive identity; that finding is in the
EXCLUDED lane and is NOT admitted as corpus evidence here** — stress report §G.

## 🔴🔴 Implementation — **`none` in scope**, and the exclusion is what reveals it

Every occurrence of `ℐ` in code is in the **excluded** lane:

```
gap-discovery/readiness/exec/minimum_implementable.py:45
  "InvariantReg": (["K","Sigma","Policy"], "NOT ENUMERATED", "D")   # G-67 = ℐ
gap-discovery/second-order/exec/so_exp02_invariant_expressibility.py
gap-discovery/exec/exp_ekp_bridge.py     · readiness/exec/verify_readiness_claims.py
```

$$\boxed{\begin{array}{c}\textbf{In-scope implementation of } \mathcal I : \mathbf{none}.\\ \textbf{Its only "implementation" is the EXTRACTING lane's own model of it.}\end{array}}$$

⭐ **Had the implementation-scope exclusion not been applied, this record would have read
`type-exists` on the strength of my own graph node** — and that node's own label is
**`"NOT ENUMERATED"`**. ⚠️ **A placeholder asserting the thing is empty would have counted as evidence
that it exists.** Stress report §G — **the clearest manufactured-claim case in either pass.**

⚠️ `[PROP]` a level **below `type-exists`** — `modelled-as-placeholder` — for a construct that appears
in a model **only as a named absence**. **Not applied.**

## Latest — four independent

**mention** 2026-09-06 · **implementation** 🔴 **`never`** *(applicable · searched in scope · no instance found)* ·
**refinement** `D-03`'s `ℛ_req` spec, 2026-09-02 · **governed decision** 🔴 **`never`** *(applicable · searched `governance/` · no instance found)*

✅ **B-6 APPLIED here** *(mechanical, 2026-09-07)*: this field now reads **`never`**, not `n/a`.
The distinction the record identified is now the rule — `n/a` and `never` are different claims — `ℐ` was **never implemented in scope**, which is
not the same as *"not implemented yet"*. Stress report §E.

## Dependencies — recorded, **not** a sequencing rule

**`Admissible`** — ⚠️ **undecidable** (depends on `Assurance`, which the corpus records as
**REFUTED as definable**) · **`δ`** (`KOS-T-0002`, OPEN) · `K` (`KOS-T-0001`, 4 definitions) ·
`Σ`, `Policy` *(per `InvariantReg`'s modelled edges — **excluded-lane evidence, recorded as such**)*.

⚠️ **`ℐ`'s schema cannot be evaluated while `Admissible` is undecidable.** **That is a measured
dependency, not an instruction about extraction order.**

| record type | content |
|---|---|
| **proposal** | `D-03`'s `ℛ_req` spec, self-stamped `[PROPOSED RATIFICATION]` |
| **decision** | 🔴 **none in `governance/`** |
| **implementation fact** | 🔴 **none in scope** |
