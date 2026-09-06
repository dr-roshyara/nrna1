# 08 — Degeneracy Audit (mandate §13) · retrospective over Steps 287–288

## The rule, promoted to a standing audit principle

$$\boxed{\begin{array}{c}\textbf{No algebraic property may be accepted from a zero-counterexample result}\\ \textbf{without first checking whether the tested relation is degenerate.}\end{array}}$$

**Before accepting any property from a finite exhaustive test, check whether the relation is:**
universal · empty · singleton · the identity · degenerate along one or more axes · or otherwise
**incapable of meaningfully exercising the property.**

**Required companion measurement:** every relation reported as satisfying the equivalence axioms must
also report **its block count**. `1 block` (universal) and `|carrier| blocks` (discrete) both satisfy
every axiom and both carry no information.

**Originating instance** (`step-288/06 §J`): `≈_S ∘ ≈_V` returned **0 transitivity violations** and is
the **universal relation** — `m` can always be chosen with `m.S = s_1.S` and `m.V = s_2.V`, so every
pair is related. **Zero violations because nothing was distinguished.**

## Retrospective application to every 287–288 equality/observability result

| # | Result | Degenerate? | Block count | Verdict |
|---|---|---|---|---|
| 1 | `≈_X` reflexive/symmetric/transitive, **all 32 `X`** | ⚠️ **2 of 32 ARE degenerate** — see below | `∅→1`, `{A,S,R,V,C}→2240`, 30 others between | ✅ **STANDS — with 2 endpoints flagged** |
| 2 | `≈_∅` (the trivial projection) | 🔴 **UNIVERSAL** | **1** | ⚠️ **DOWNGRADE: axiom-satisfying, information-free. Not a candidate `≈`** |
| 3 | `≈_{A,S,R,V,C}` = `=` on `Σ` | 🔴 **DISCRETE (identity)** | **2240** | ⚠️ **DOWNGRADE as an *equivalence candidate*: it relates nothing but self.** Still valid as *structural equality* |
| 4 | **exactly 32 distinct partitions** | ✅ no — measured over the real 2240 space | 32 distinct partitions | ✅ **STANDS** |
| 5 | `X ⊆ Y ⇒ ≈_Y` refines `≈_X` | ⚠️ **sampled**, 211 pairs — but it is a **theorem** (`π_X` factors through `π_Y`) | — | ✅ **STANDS on the theorem, not the sample** |
| 6 | `=` is **not** a congruence | ✅ no — a **positive** counterexample, not a zero-count | — | ✅ **STANDS** (a found counterexample cannot be vacuous) |
| 7 | hash canonicalization-dependence | ✅ no — positive counterexample | — | ✅ **STANDS** |
| 8 | `id` re-keys on withdrawal | ✅ no — positive counterexample | — | ✅ **STANDS** |
| 9 | finite-sample equality fails | ✅ no — positive counterexample | — | ✅ **STANDS** |
| 10 | `≈_S ∘ ≈_V` composition | 🔴 **UNIVERSAL** | **1** | 🔴 **ALREADY DOWNGRADED** at discovery |
| 11 | `≡_D` decidable | ✅ no | — | ✅ STANDS ⚠️ wrong level |
| 12 | `∼_F` (fold) decidable | ✅ no | — | ✅ STANDS ⚠️ and **insufficient** (`258.11`) |
| 13 | `≅_I` transitive within a context (`I_48`) | ⚠️ **UNTESTED — no context is defined, so no partition can be computed** | **unmeasurable** | ⚠️ **cannot be degeneracy-checked at all.** Confirms **BOUNDED**, not established |
| 14 | 13 of 13 negative results falsified | ✅ no — each has a named obstruction | — | ✅ **STANDS** |

## Results requiring downgrade

$$\boxed{\textbf{3 downgraded of 14. None of the substantive findings; all three are ENDPOINT degeneracies.}}$$

| Downgraded | From | To |
|---|---|---|
| `≈_∅` | *"one of 32 candidate relations"* | **the universal relation — axiom-satisfying, information-free; not a candidate** |
| `≈_{full}` | *"one of 32 candidate relations"* | **the discrete relation — identical to `=` on `Σ`; not an independent candidate** |
| `≈_S ∘ ≈_V` | *"composition passes transitivity"* | **universal; a vacuous pass** |

> ### ⭐ The consequence for the headline number
> **"exactly 32 distinct `≈_X` relations" stands as a count of distinct partitions.** But as a count of
> ***usable candidate observational relations* it is 30**, because two endpoints are degenerate.
> **The normative choice space `N-5` is 30, not 32.**
>
> ⚠️ **This is the third time in this programme a count has needed narrowing after measurement**
> (`47 tests → 4`; `at most 32 → exactly 32`; `32 → 30 usable`). **The pattern is not arithmetic error
> — it is reporting a computed cardinality without asking what the extremes mean.**

## What the rule protects against, stated generally

A verification suite reporting *"reflexive ✅ symmetric ✅ transitive ✅"* across a family of relations
**looks strongest exactly where the relations have collapsed** — because a degenerate relation cannot
produce a counterexample to anything. **Degeneracy inflates a pass rate.** The block-count companion
measurement is the cheapest available guard, and it is now mandatory in this programme.

## STATUS
**ESTABLISHED** the degeneracy rule; 3 of 14 results downgraded; the usable `≈_X` space is **30, not 32**
· **EMPIRICALLY VERIFIED** block counts for all 32 projections · **DERIVED** positive counterexamples
cannot be vacuous, so 6 of 14 results are immune by construction · **BOUNDED** `N-5`'s choice space at 30
· **TECHNICALLY OPEN** `I_48` cannot be degeneracy-checked until *identity context* is defined
