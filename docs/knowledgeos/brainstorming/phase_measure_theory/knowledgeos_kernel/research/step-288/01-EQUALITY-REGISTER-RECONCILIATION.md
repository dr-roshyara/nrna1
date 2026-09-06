# 01 — Equality Register Reconciliation (mandate §3)

> ⚠️ **Package placement.** The mandate names files `01-`…`08-`. `research/` already holds
> `01-GAP-UPDATE-…`…`13-…`. **This package therefore lives in `research/step-288/`** — mandate
> filenames preserved exactly, no collision. *(A placement decision, recorded; not a scope change.)*

## §3.1 The corpus has NINE registers, not one

| R | Locus | Date | Symbols | Carrier |
|---|---|---|---|---|
| R1 | `25I.11` | 08-28 09:37 | `≡_exact` `≡_struct` **`≡_sem,C`** | assertions |
| R2 | `25J.5` | 08-28 09:38 | `E_exact ⇒ E_struct ⇒?` `E_sem` | assertions |
| R3 | `25J.26` | 08-28 09:38 | 7 typed edges `{Equal, Equivalent, Refines, RefinedBy, Contradicts, EvolvesTo, Independent}` | assertions |
| R4 | `25S.36/43` | 08-28 10:07 | `EntityID ≠ KAID ≠ RecordID` | identities |
| R5 | **`246`** | 08-30 11:11 | `=` `≡` `≈` `≅_λ` | states |
| R6 | **`258.1`** | 08-30 19:09 | `=_X` `≡_K` `∼_H` *(by carrier)* | object/state/history |
| R7 | `258.4` | 08-30 19:09 | `=_I` `=_V` `=_S` `=_E` | objects |
| R8 | `260` | 08-30 19:19 | `≡_𝒯^cand` vs `≡_𝒯^prov` | histories |
| R9 | **`261.1`** | 08-30 19:24 | **`=` `≡` `≈` `≅_I` `≅_P` `≅_H` — SIX** | states |
| + | `264.15` | 08-30 | `≡_D` | values |
| + | `195.21/52` | **08-29** | **`Identity` · `Continuity` · `Similarity`** | entities/lineage |
| + | `012 §36/46` | **08-27** | `Relation ∈ {Equivalent, Compatible, Contradictory, Related, Independent, Unknown}` | propositions |

**`261.1` is the widest state-level register: six.** `012 §36` is the widest *relation* codomain: six.
**No corpus artifact reconciles them.**

## §3.2 The mandate's question: different relations, or overloaded notation?

| Verdict | Members |
|---|---|
| **Genuinely different relations** | `=` · `≡` · `≅_I` · `≅_P` · `≅_H`/`∼_H` · `Continuity` — different carriers or different information admitted |
| **Different descriptions of ONE relation** | `≡_struct`(R1) ≡ `=_S`(R7) ≡ `E_struct`(R2) ≡ `=`(R5,R9) — **four names, one relation** |
| **Relations at different LEVELS** | `≡_D` (value) vs `≡` (state); `=_I`(object) vs `≅_I`(reference); `∼_H`(history) vs `≡`(state) |
| 🔴 **INCOMPATIBLE definitions** | **`≡` and `≈` — see §3.3** |
| **Overloaded notation** | `≅` carries `≅_λ`(R5) · `≅_I` · `≅_P` · `≅_H` — **one glyph, four relations**; and `≡` carries `≡_exact`, `≡_struct`, `≡_sem`, `≡_K`, `≡_D`, `≡_𝒯` |
| **NOT an equality at all** | `Similarity` — *evidence for* identity/continuity (`195.21`, `38.77`); `⪯` refinement — an **order**; `Validate` — an **assessment** (`261.19`) |

## §3.3 🔴 The definitional conflict

| Locus | Definition |
|---|---|
| `261.1` · `246` | `≡` **semantic** and `≈` **observational** are **distinct, not interchangeable** |
| **`258.8`** | `K_1 ≡_K K_2 ⟺ ∀O ∈ 𝒪: O(K_1)=O(K_2)` |
| **`261.21`** | `K_1 ≡_K K_2 ⟺ ∀O ∈ 𝒪_K: O(K_1)=O(K_2)` — *"an observational/behavioral notion"* |
| **`261.5`** | `K_1 ≈ K_2 ⟺ ∀O ∈ 𝒪_K: O(K_1)=O(K_2)` — **the identical formula** |

$$\boxed{\equiv_K \text{ and } \approx \text{ share one formula while being listed as distinct.}}$$

**Either `≈` collapses into `≡` (violating the corpus's own non-interchangeability warning), or `≡` has
no definition at all — the observational one being the only one offered.** `NORMATIVE`, unresolved,
**no branch preferred here.**

## §3.4 The reconciled matrix (mandate §3 columns)

| | `=` structural | `≡` semantic | `≈` observational | `≅_λ`/`≅_P` provenance | `≅_I` identity | `≅_H`/`∼_H`/`≡_𝒯` historical | `Continuity` | `≡_D` value |
|---|---|---|---|---|---|---|---|---|
| **level** | L-state | L-state | L-state + L-history | L-state | L-entity/ref | L-history | L-entity×lineage | **L-value** |
| **corpus definition** | field/byte identity | ⚠️ **§3.3** | `∀O∈𝒪_K` | `261.8` two branches | `id(x)=id(y)` | all future σ agree (`258.11`) | `f(IdRel,Lineage,Rules,Ev)` | `3m ≡_D 300cm` |
| **source** | `246`·`261.1`·`258.4` | `246`·`261.1`·`25I` | `246`·**`261.5`**·`258.8` | `246`·`261.1`·`261.8` | **`261.1`**·`258.4`·`25S` | `261.1`·**`258.11`**·`260` | **`195.51`** | `264.15` |
| **decision procedure** | ✅ compare | 🔴 none | 🔴 none (`𝒪_K` open) | 🔴 none (`λ` unbound) | 🟡 4/6-valued | 🔴 none | 🔴 none | ✅ dimensional |
| **computability** | ✅ **⚠️ relative to canonicalization** | 🔴 **not fully decidable** (`012 §35`) | 🔴 | 🔴 | 🟡 | 🔴 quotient not implementable (`260.11`) | 🔴 | ✅ |
| **equivalence properties** | ✅ | 🔴 **transitivity unestablished** | ✅ *for `≈_X`; unknown for `∀O∈𝒪`* | 🔴 | ✅ **within a context** (`I_48`) | 🔴 | 🔴 | ✅ |
| **congruence** | 🔴 **NOT** (`258.10`, EXECUTED `06 §G`) | 🟡 required (`258.9`), unproven | 🟡 unproven | 🔴 | ⚠️ operation-specific (`258.29`) | — is the target | 🔴 | n/a |
| **provenance included?** | ✅ (`Π ∈ id`) | ⚠️ **Decision 3** | ⚠️ iff some `O` reads it | ✅ by construction | 🔴 | ✅ | ✅ | 🔴 |
| **governance included?** | ⚠️ via `Π` | ⚠️ **Decision 3** | ⚠️ iff `O` reads `A` | 🔴 | 🔴 | 🔴 | 🔴 | 🔴 |
| **identity involved?** | ✅ | 🔴 | 🔴 | 🔴 | ✅ definitionally | 🔴 | ✅ | 🔴 |
| **dependencies** | canonicalization | Decision 3 · transitivity | **`𝒪_K`** | `λ` · Decision 3 | *identity context* | **`𝒯`** · `δ` | `Ω` · lineage | dimensional model |
| **contradictions** | mutable `e.state` (EXECUTED `06 §H`) | 🔴 **§3.3 vs `≈`** | 🔴 **§3.3 vs `≡`** | — | — | `∼_F` ≠ `∼_H` (`258.11`) | in no register | wrong level for `K` |
| **normative decisions** | which canonicalization | **§3.3** · Decision 3 | which `𝒪_K` | which `λ` | define *identity context* | which `𝒯` | is it a kernel relation | — |

## §3.5 Implication lattice — OPEN, and positively forbidden as a chain

`25J.5` writes `E_exact ⇒ E_struct ⇒`**`?`**`E_sem` — the question mark is the corpus's.
`261.9`: $\boxed{\text{No universal equality hierarchy has yet been proven}}$
`258.19`: *"should **not** be interpreted as a strict mathematical hierarchy … **separate semantic
constructs connected by explicit rules**."*

> ⚠️ **`D285-5`'s `history ⊊ structural ⊊ semantic` chain is therefore not merely unmeasured — it is
> forbidden.** **PROPAGATION: corrected at source in `D285-5`; re-checked in `REFINED-STEP-287` §2.**

## STATUS
**ESTABLISHED** nine registers; four names for one structural relation; `≅`/`≡` glyph overloading;
`Similarity`/`⪯`/`Validate` are not equalities · **BOUNDED** the register is finite and now enumerated
· **NORMATIVE** §3.3 · **TECHNICALLY OPEN** every procedure but `=`/`≡_D` · **BLOCKED** `𝒪_K`, `𝒯`
· **DEFERRED** register ratification
