---
artifact: E1–E7 · EQUALITY, IDENTITY, SEMANTICS, OBSERVABILITY
mandate: `prompts/20260831-190114_step_287_reviewer-b-…-mandate.md`
date: 2026-08-31
verdict: **OPEN — the corpus names four equality relations; three have no decision procedure**
---

# E1–E7 · Equality Investigation

**Discipline:** `[S]`/`[C]`/`[P]`/`[R]`/`[H]` · `CORPUS` · `DERIVED` · `EXECUTED` · `IMPLEMENTATION` ·
`NORMATIVE`. Falsification-first, per §12: *"Do not try to preserve D285-5. Try to destroy it."*

## E1 · Equality Evidence Register

| Locus | Content | Class |
|---|---|---|
| **246 §A–D** | four state-level relations; *"These are not interchangeable"* | **`CORPUS`** |
| **264.15** | `v₁ ≡_D v₂` — *"equivalence under the semantics of dimension `D`"*; `3m ≢_str 300cm`, `3m ≡_D 300cm` | **`CORPUS`** — the **only** relation with a decision procedure |
| **254 Decision 3** | *"Is governance/authority part of semantic equality?"* | **`CORPUS`, EXPLICITLY OPEN** |
| **261** | equality and identity | `CORPUS` |
| `id = H(P,e,c,t,Π)` | assertion identity; `Π` **inside** the hash | `CORPUS` (verification-lane reconstruction) |
| **262** | *"structural equality and semantic equality were distinguished… structural state equality reflexive, symmetric, transitive"*; *"Proposition semantic equality 🟡 Candidate"* | `CORPUS` |
| `history ⊊ structural ⊊ semantic` | ⚠️ **verification-lane only** — 0 corpus hits | **`DERIVED`, NOT `CORPUS`** |

## E2 · Equality Formalization

| Relation | Carrier | Equivalence relation? | Preserves | Discards | Decision procedure? |
|---|---|---|---|---|---|
| **A `=` structural** | `𝕂` | ✅ (262: reflexive, symmetric, transitive `EXECUTED`) | representation | nothing | ✅ **yes** — set/byte comparison |
| **B `≡` semantic** | `𝕂` | presumed | *"knowledge semantics"* | **UNSPECIFIED** | 🔴 **NO — a name only** |
| **C `≈` observational** | `𝕂` | ✅ if the observation set is fixed | observable answers | everything unobserved | 🔴 **NO — no permitted-observation set is declared** |
| **D `≅_λ` provenance-sensitive** | `𝕂` | presumed | content **+ relevant provenance** | irrelevant provenance | 🔴 **NO — *"relevant"* undefined** |
| **`≡_D` value-semantic** | `V_D` | ✅ | dimensional meaning | representation/units | ✅ **yes** — the unit-conversion example |

> **One of five relations is evaluable. `≡_D` is at the value level, not the state level, so it cannot
> stand in for `≡`.** RQ3's mandated answer:
>
> ### `=_semantic` as used in `D285-5` is a research-level construct, NOT a corpus-defined primitive — and it silently resolved open Decision 3 by discarding `Π`.

## E3 · Hierarchy test `EXECUTED`

```
structural => semantic ?  True    (witness holds)
semantic  => structural?  False   => the inclusion is STRICT
provenance => semantic ?  True    (D is defined AS semantic + provenance)
```

| Claim | Verdict |
|---|---|
| `structural ⊊ semantic` | ✅ **WITNESSED, strict** |
| `≅_λ ⊆ ≡` | ✅ **by definition** |
| `≈` comparable to the others | 🔴 **NOT** without a declared observation set |
| `history ⊊ structural` | 🔴 **UNDEFINED — `history` is not a corpus relation** |

**The corpus hierarchy is a partial order over four relations, not the three-element chain previously
cited.**

## E4 · `D285-5` revalidated

| Equality | Antecedent can hold? | Substantive? | Status |
|---|---|---|---|
| history | — | — | **UNDEFINED — not a corpus relation** |
| **structural** | 🔴 no (`Π ∈ id`) | no | **VACUOUS** |
| **semantic** | ✅ yes | yes | **SUBSTANTIVE, CONDITIONAL on open Decision 3** |
| **observational** | ✅ yes | relative | **UNDEFINED as stated** — needs the observation set |
| **provenance-sensitive** | 🔴 no | no | **VACUOUS — and here `δ` IS injective** |

## E5 · Injectivity report — **the over-claim, named**

> `D285-5` establishes **only**: ∃ `o₁ ≠ o₂`, ∃ `K₀` with `δ(K₀,o₁) ≡ δ(K₀,o₂)` under a semantic
> projection discarding `Π`.
>
> **This is non-injectivity MODULO A CHOSEN QUOTIENT** — a property of `δ ∘ q`, not of `δ`.
> **It does not establish:** non-injectivity of `δ` · non-injectivity in `o` · non-injectivity modulo
> observational equality (undefined).
> **Under corpus relation D the same `δ` is injective on the same witness.**

## E6 · Identity boundary

| Identity | Corpus support | Same as the others? |
|---|---|---|
| **state identity** | `id = H(P,e,c,t,Π)` per assertion; **`K_t` has no identity rule** | no |
| **operation identity** | 🔴 **not defined** — `𝒪` membership itself unratified | no |
| **authority-act identity** | 🔴 **not defined** — `Grant` has `grantId`, the *act* has none | no |
| **event identity** | 🔴 **not defined** — `Event` has no schema | no |
| **provenance identity** | `Π` intrinsic, `t=0`-safe | distinct |

> **Five identity notions, one defined.** And the answer to §7's question:
> **`D285-5` does NOT imply a general identity rule.** Its property is about an equivalence relation
> on **states**; the `grantId` collision is a key collision on **authority acts**. **Different
> layers — my earlier citation linking them is withdrawn.**

## E7 · Verdict

**`OPEN — unresolved.`**

## §12 Falsification-first — the eight attacks, honestly

| # | Attack | Outcome |
|---|---|---|
| 1 | semantic equality is unnecessary | 🟡 **PARTLY SUCCEEDS** — under `≅_λ` the property is vacuous, and `≅_λ` is arguably the *right* relation for a provenance-bearing system |
| 2 | the two operations are identical under a stronger identity rule | ✅ **SUCCEEDS** — under structural/`≅_λ` they are distinguishable, so "identical" never arises |
| 3 | the example is an implementation artefact | 🔴 fails — it follows from `Π ∈ id`, a definitional choice |
| 4 | semantic equality cannot be defined consistently | 🟡 **UNRESOLVED** — the corpus never defines it; Decision 3 is open |
| 5 | observational equality collapses the distinction | 🟡 **CANNOT BE TESTED** — no observation set declared |
| 6 | non-injectivity does not follow | ✅ **SUCCEEDS** — see E5 |
| 7 | operation identity = authority identity | 🔴 fails — different layers (E6), but my *citation* linking them does fail |
| 8 | the hierarchy is not strict | 🟡 **PARTLY** — `structural ⊊ semantic` is strict; the *three-element chain* is not corpus at all |

**Three attacks succeed or partly succeed. `D285-5` survives — as a narrower claim.**

## §9 Independence

| Route | Reaches the equality result? |
|---|---|
| **KnowledgeOS** | ✅ **YES, entirely** — from `id = H(P,e,c,t,Π)` and Step 246. **Derivable from typing alone** |
| **Gītā** | 🔴 **NO.** 2.47–2.48 distinguishes action from fruit; it says **nothing** about equality relations on states |

> **The equality result is `R6` — independently required by KnowledgeOS, with NO Gītā content.**
> The lens raised the action/result question; **the equality problem is purely formal and the Gītā
> contributes nothing to it.** Recorded because it is the cleanest case of `corroboration ≠ derivation`
> in the programme: here there is not even corroboration.

## §10 The three questions kept separate

**A — what is an operation?** unresolved (`𝒪` unratified).
**B — what state results?** `δ(K,o)`, commit case unbodied.
**C — when are two states equal?** **four named relations, one evaluable.**
**C was not answered by repeating A or B.**

## §14 Final research question

> **Is equality a missing formal primitive, or is it derivable from the corpus?**
>
> ## **NEITHER. The corpus contains the RELATIONS and not their DECISION PROCEDURES.**
>
> Four relations, declared non-interchangeable ⇒ **not missing**. Three of four unevaluable ⇒
> **not derivable as stated**. **Equality is an UNDER-SPECIFIED corpus primitive** — structurally the
> same gap as `Qualify`, and in the same class (`G1`).
>
> **What would close it: a decision on `254 Decision 3` (is `Π` inside `≡`?) and a declared
> permitted-observation set for `≈`. Both are `NORMATIVE`. Neither is taken here.**

## §15 Discipline

No canonical selection · no Constitution change · no governance decision · no architecture promotion ·
no new primitive · `Qualify` not solved by assumption · `Sañjaya = Ω` **not** treated as canonical ·
**`=_semantic` explicitly NOT promoted** — it is recorded as a research construct that resolved an
open decision, which is the defect this pass was sent to find.
