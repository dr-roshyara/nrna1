# 06 — Register Reconciliation (mandate §9) — authority · duplication · conflict

**Nine registers, audited entry by entry. No choice is made between conflicting definitions.**

| Reg | Source | Date | Relation(s) | Level | Authority status | Conflict? | Duplicate terminology? | Normative? | Merely historical? |
|---|---|---|---|---|---|---|---|---|---|
| R1 | `25I.11` | 08-28 | `≡_exact` `≡_struct` **`≡_sem,C`** | assertion | research, self-verdicted PASS | 🔴 no | ✅ `≡_struct` = `=_S` = `E_struct` = `=` | 🔴 | ⚠️ **earliest; superseded in coverage, not refuted** |
| R2 | `25J.5` | 08-28 | `E_exact ⇒ E_struct ⇒?` `E_sem` | assertion | research | 🔴 | ✅ duplicates R1 | 🔴 | ⚠️ historical |
| R3 | `25J.26` | 08-28 | 7 typed edges | assertion | research | 🔴 | 🔴 — **distinct object** (edges, not equalities) | 🔴 | 🔴 **live** — `ℛ`'s ancestor |
| R4 | `25S.36/43` | 08-28 | `EntityID` `KAID` `RecordID` | identity | research, **explicitly "provisional"** | 🔴 | 🔴 | 🔴 | 🔴 **live** |
| R5 | `246` | 08-30 | `=` `≡` `≈` `≅_λ` | state | **most-cited** | 🔴 **directly**; ⚠️ inherits the `≡`/`≈` issue | ⚠️ subset of R9 | 🔴 | 🔴 |
| R6 | `258.1` | 08-30 | `=_X` `≡_K` `∼_H` | **by carrier** | research | ⚠️ **`≡_K`'s definition conflicts — see below** | 🔴 — an **orthogonal axis** (carrier, not relation) | 🔴 | 🔴 **live** |
| R7 | `258.4` | 08-30 | `=_I` `=_V` `=_S` `=_E` | object | research | 🔴 | ✅ `=_S`↔`=`; `=_E`↔`≡` | 🔴 | 🔴 |
| R8 | `260` | 08-30 | `≡_𝒯^cand` / `≡_𝒯^prov` | history | research | 🔴 | ⚠️ `≅_H`/`∼_H` name the same idea | 🔴 | 🔴 **live** |
| **R9** | **`261.1`** | 08-30 | **`=` `≡` `≈` `≅_I` `≅_P` `≅_H`** | state | **widest; latest** | 🔴 **CRITICAL — see below** | ⚠️ `≅_P`↔`≅_λ`; `≅_H`↔`∼_H` | 🔴 | 🔴 **live** |
| + | `264.15` | 08-30 | `≡_D` | **value** | research | 🔴 | 🔴 | 🔴 | 🔴 |
| + | `195.21/52` | **08-29** | `Identity` `Continuity` `Similarity` | entity×lineage | research | 🔴 | 🔴 — **`Continuity` in no other register** | 🔴 | 🔴 **live** |
| + | `012 §36/46` | **08-27** | `Relation ∈ {Equivalent, Compatible, Contradictory, Related, Independent, Unknown}` | proposition | research | 🔴 | ⚠️ overlaps R3 | 🔴 | 🔴 **live** |

## 🔴 The conflict — `258.8` and `261.21` vs `261.1` and `246`

| Locus | Text |
|---|---|
| **`258.8`** | `K_1 ≡_K K_2 ⟺ ∀O ∈ 𝒪: O(K_1)=O(K_2)` |
| **`261.21`** | `K_1 ≡_K K_2 ⟺ ∀O ∈ 𝒪_K: O(K_1)=O(K_2)` — *"an observational/behavioral notion"* |
| **`261.5`** | `K_1 ≈ K_2 ⟺ ∀O ∈ 𝒪_K: O(K_1)=O(K_2)` — **the identical formula** |
| `261.1`·`246` | `≡` and `≈` are **distinct and not interchangeable** |

$$\boxed{\text{Two of the six state-level relations cannot both stand as written.}}$$

| Reading | Consequence |
|---|---|
| **(a)** `≈` collapses into `≡` | register drops to **5**; violates the corpus's own *"not interchangeable"* |
| **(b)** `≡` needs a new, non-observational definition | then **`≡` currently has NO definition** — the observational one is the only one offered |
| **(c)** notation clash only; rename one | ⚠️ **cheapest, and it must be *established*, not assumed** — `261.1` and `261.21` are in **one document**, so an author-level slip is plausible but **not demonstrable from the text** |

> ### Is the duplication **documentary** or **normative**?
> $\boxed{\textbf{NOT DETERMINABLE from the corpus.}}$ `261` states both the distinction (§261.1) and the
> shared definition (§261.5, §261.21) **without noting any tension** — so the text supports neither
> "deliberate identification" nor "editorial slip". **This is precisely why it needs an owner, not an
> inference.** **No reading is chosen here.** → **`N-1`**

## Which registers are authoritative?

$$\boxed{\textbf{NONE. No register carries a ratification record.}}$$
All nine are **research-lane, self-verdicted**, and R4 marks itself *"provisional"*. **`261.1` is the
widest and latest, which is not the same as authoritative** — and treating recency as authority would be
exactly the promotion this programme forbids. **Register ownership is itself an open governance item.**

## STATUS
**ESTABLISHED** 9 registers audited; **no register is authoritative**; 5 duplicate-terminology
identifications; `Continuity` unique to `195` · **REFUTED** *"246 is the origin"* · **TECHNICALLY OPEN**
whether the `≡`/`≈` duplication is documentary or normative — **not determinable from the text** ·
**NORMATIVE** `N-1` (adjudicate) and register ownership · **DEFERRED** all adjudication
