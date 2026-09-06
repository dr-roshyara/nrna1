# 04 — Derivation / Governance Matrix (mandate §§6, 7)

## §6 Four-way separation — **D** derivable · **E** empirically establishable · **N** normative · **G1** irreducible

⚠️ **Categories are never collapsed.** Primary class first; secondary qualifiers in the notes.

| Question | Depends on | Can derive? | Can execute? | Requires governance? | Blocked by | Primary | Status |
|---|---|---|---|---|---|---|---|
| **`Π ∈ ≡` ?** | `𝒪`, `254`/`261.8` | ⚠️ **the technical half only** — `258.31` congruence experiment | ✅ **once `𝒪` closes** | ✅ the residue + which of `258.31`'s 3 repairs | `𝒪` | **N** *(with an E component)* | NORMATIVE |
| **state identity** | canonicalization, `258.2` | 🔴 | ⚠️ inconsistency **already executed** | ✅ authorize the repair | canonicalization | **N** | TECHNICALLY OPEN |
| **operation identity** | `𝒪` | 🔴 | 🔴 | ✅ | `𝒪` | **N** | TECHNICALLY OPEN |
| **authority-act identity** | `AuthorityAct` type | 🔴 | ⚠️ **collision already observed** (`grantId`) | ✅ | `G-57` | **N** | TECHNICALLY OPEN |
| **event identity** | event schema | 🔴 | 🔴 | ✅ | no schema | **N** | TECHNICALLY OPEN |
| **provenance identity** | — | ✅ **DERIVED** (`t=0` proof) | ✅ | 🔴 | — | **D** | **ESTABLISHED** |
| **`≈` observation set `𝒪_K`** | `𝒪` | 🔴 | 🔴 | ✅ | `𝒪`; `261.21` | **N** | NORMATIVE — space **BOUNDED at 30 usable** (`08`) |
| **`≡` decision procedure** | `N-1`, `Π`, transitivity | 🔴 — **`012 §35`: not fully decidable** | 🔴 | ✅ | the `N-1` conflict | **G1** *(with an N component)* | **G1** |
| **`≅_λ` decision procedure** | `λ` | 🔴 — a **principle**, not a predicate | 🔴 | ✅ | Decision 3 | **N** | TECHNICALLY OPEN |
| **`𝒪`** | — | 🔴 **no incoming derivation edge found** (`03`) | 🔴 | ✅ | — | **N** | NORMATIVE — **the derivation boundary** |
| **`𝒯`** | `𝒪` | 🔴 same | 🔴 | ✅ | `𝒪` | **N** | NORMATIVE |
| **`δ`** | `𝒯`, `≡` | 🔴 | 🔴 — commit case **executed** `K₁ is K₀` | ✅ | `𝒯`, `≡` | **N** | BLOCKED |
| **congruence under operations** | `≡`, `δ`, `𝒯` | ⚠️ **derivable once all three fixed** | ✅ **`258-A` is executable** | 🔴 **must NOT be given to governance** | `≡`, `𝒯` | **D** | BLOCKED |
| **equality under merge** | `≡`, `δ` | ✅ `25J.45–47` laws are **testable** | ✅ once `δ` exists | 🔴 | `δ` | **D** | BLOCKED |
| **equality under withdrawal** | canonicalization | ⚠️ | ✅ **already executed — it FAILS** | ✅ authorize repair | canonicalization | **E** | **EMPIRICALLY VERIFIED (negative)** |
| **equality under contradiction** | `C` axis, `Ω` | 🔴 — `25J.14` needs a domain ontology | 🔴 | ✅ which predicates are functional | `Ω` | **N** | TECHNICALLY OPEN |
| **equality under retraction** | `Article 8`, `I-12` | ⚠️ | ✅ **executed — monotone growth REFUTED** (`07`) | 🔴 | — | **E** | **REFUTED** *(the growth claim)* |
| **canonicalization** | evidence | 🔴 — `38.85`: must **follow** evidence | ⚠️ testable per candidate | ✅ | evidence policy | **N** | TECHNICALLY OPEN |
| **replay semantics** | `Π`, history | 🔴 | 🔴 | ✅ — `261.19`: Replay **requires** provenance | Decision 3 | **N** | TECHNICALLY OPEN |
| **policy composition** | — | ✅ **DERIVED + EXECUTED** — `(Policy,∧)` meet-semilattice, `∨` refuted | ✅ **done** | 🔴 | — | **D** | **ESTABLISHED** ⚠️ **does NOT transfer to `𝕂`** (`07`) |
| **verification semantics** | `𝒪_K`, `δ` | 🔴 | 🔴 — `261.29` row 13 stays 🔴 | ✅ | `𝒪_K` | **N** | TECHNICALLY OPEN |

## The count

| Class | n | |
|---|---:|---|
| **D** derivable | **5** | provenance identity ✅ · policy composition ✅ · congruence ⛔blocked · merge laws ⛔blocked |
| **E** empirically establishable | **2** | both already executed, **both negative** |
| **N** normative | **13** | |
| **G1** irreducible | **1** | `≡`'s decision procedure — `012 §35` |

## ⭐ §14's most important question: what must NOT be given to governance?

**Handing an authority a derivable question manufactures a decision that can no longer be revised by
evidence.** Four items must therefore be **withheld** from the governance surface:

| Item | Why it is DERIVABLE, not normative |
|---|---|
| **congruence under operations** | `258-A` (`F ∘ T_H = T̄ ∘ F`) is an **executable test** once `≡`, `𝒯`, `δ` are fixed. Governance fixes the inputs; **it must not declare the outcome** |
| **equality under merge** | `25J.45–47`'s commutativity/associativity/idempotence are **laws to be tested**, not chosen |
| **the `id`/mutable-`state` repair** | **forced**: if `StructuralValid`'s no-dangling clause holds and `e.state` is mutable, then `state ∉ id`. Governance **authorizes** the repair; it does not **select** it |
| **`≈`'s two degenerate endpoints** | `≈_∅` and `≈_{full}` are **excluded by measurement** (`08`), not by preference. The choice space is **30**, and offering 32 would present two non-options as options |

$$\boxed{\text{Governance decides } \mathcal O,\ \mathcal T,\ \equiv \text{ and the scoping parameters. It must NOT decide congruence, merge laws, or a forced repair.}}$$

⚠️ **And one item must not be given to governance because it is not decidable by anyone:** `≡`'s total
Boolean procedure — `012 §35` makes it a **theorem-shaped limit**. **An authority cannot ratify
decidability into existence.**

## STATUS
**ESTABLISHED** the 4-way classification of 21 questions; 4 items withheld from the governance surface
· **DERIVED** 5 D · 2 E · 13 N · 1 G1 · **EMPIRICALLY VERIFIED** both E items — both negative ·
**NORMATIVE** 13 questions · **G1** `≡` · **BLOCKED** congruence and merge laws behind `≡`/`𝒯` ·
**DEFERRED** all decisions
