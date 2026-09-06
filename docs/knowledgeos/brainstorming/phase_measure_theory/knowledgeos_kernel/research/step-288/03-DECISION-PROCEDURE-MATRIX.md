# 03 — Decision-Procedure Matrix (mandate §§4, 15)

## §4 LEVEL DISCIPLINE — every relation pinned to exactly one level

| Level | Relations resident at this level |
|---|---|
| **L-value** | `≡_D` |
| **L-assertion** | `≡_exact` `≡_struct` `≡_sem,C` · `⪯` refinement · the 7 `25J.26` edges |
| **L-entity/object** | `=_I` `=_V` `=_S` `=_E` · `≅_I` · `EntityID`/`KAID`/`RecordID` |
| **L-state** | `=` `≡` `≈` `≅_λ`/`≅_P` |
| **L-history** | `∼_H` `≅_H` `≡_𝒯` · `∼_F` (fold) |
| **L-operation** | — 🔴 **no relation defined; `𝒪` unratified** |
| **L-event** | — 🔴 **no schema** |
| **L-policy** | — 🔴 |
| **L-observation** | `𝒪_K` — 🔴 **not closed** (`261.21`) |
| **L-provenance** | `Π` identity · `≅_P` |
| **L-system/behaviour** | `∼_H` under all σ (`258.11`) · `Continuity` (entity×lineage) |

## §4b Promotion audit — SOURCE LEVEL → TARGET LEVEL

| # | Promotion | Verdict | Why |
|---|---|---|---|
| 1 | `v_1 ≡_D v_2` → `K_1 ≡ K_2` | 🔴 **INVALID** | L-value → L-state; `264.15` is dimensional, `K` is not |
| 2 | structural equality on `Σ` → structural equality on `K` | 🔴 **INVALID** | `K` carries content·prov·history·governance·events·retraction; none is a `Σ` coordinate |
| 3 | `Σ_1 ⪯ Σ_2` → `K_1 ⪯ K_2` / `K_{t+1} ≻ K_t` | 🔴 **INVALID** — **the F5 error, binding per mandate §2** | audited below |
| 4 | `≈_X` (my construction) → corpus `≈` | 🔴 **INVALID** | corpus `≈` is `∀O ∈ 𝒪_K` (`261.5`), not a Σ-axis projection |
| 5 | equal outputs on a finite sample → equality | 🔴 **INVALID** — **EXECUTED** `06 §I` | `f,g` agree on `0..9`, differ at `10` |
| 6 | `H(x)=H(y)` → semantic equality | 🔴 **INVALID** — **EXECUTED** `06 §H` | canonicalization-relative; key order flips it |
| 7 | `≡` decidable → `≡` a congruence | 🔴 **INVALID** — **EXECUTED** `06 §G` | `=` is Level-5 and **not** a congruence (`258.10`) |
| 8 | `∼_F` (equal folds) → `∼_H` (behavioural) | 🔴 **INVALID** | `258.11` *"this is insufficient"*; `258.14` biconditional undemonstrated |
| 9 | `≅_I` transitive in-context → transitive globally | 🔴 **INVALID** | `I_48` is context-scoped; `38.7` forbids `GlobalEntityIdentity` |
| 10 | `X ⊆ Y` → `≈_Y` refines `≈_X` | ✅ **VALID** — theorem, **EXECUTED** `06 §D` | `π_X` factors through `π_Y` |
| 11 | `≈_X` is an equivalence relation | ✅ **VALID** — theorem, **EXECUTED** `06 §A–C` | kernel of a function |
| 12 | corpus `≈` is an equivalence relation | 🟡 **UNPROVEN** | `∀O∈𝒪_K` would be a kernel too — **but `𝒪_K` is not closed, so there is no function to take the kernel of** |
| 13 | `≡_sem` transitive | 🟡 **UNPROVEN** | `25J.27`, `25S.6/29`, `38.16`, `38.47` flag it unsafe |
| 14 | `Similarity > θ` → identity | 🔴 **INVALID** | `38.17` fuzzy-matching / transitive-closure danger; `38.23`/`38.80` |
| 15 | `≡ ⇒ ≈` or `= ⇒ ≡` (a hierarchy) | 🟡 **UNPROVEN and FORBIDDEN as a chain** | `261.9` boxed; `258.19`; `25J.5`'s own `?` |

### §2 audit — every `Σ`-order and `K`-order occurrence in this programme

| Artifact | Occurrence | State |
|---|---|---|
| `08-FINDINGS…Q-SERIES` | *"partial order by construction"* | ✅ **REPAIRED at source** (earlier pass) |
| `REFINED-STEP-287` §4 | heading asserted `K_{t+1} ≻ K_t` | ✅ **REPAIRED at source** — now *"a CONDITIONAL product-order CONSTRUCTION · and NOT `K_{t+1} ≻ K_t`"* |
| `00-INDEX` | product-order headline | ✅ **REPAIRED** — scoped *"over `Σ` — not over `K`"* |
| `REFINED-STEP-288` v2 §7 | states the separation as binding | ✅ correct |
| **fresh sweep, this step** | no new `K`-order inference found | ✅ **CLEAN** |

$$\boxed{\Sigma\text{-order} \neq K\text{-order}} \qquad \textbf{0 of 5 } \Sigma \textbf{ component orders established}$$
`S` 🟡 ordinal bands, no metric · `R` 🟡 `Unresolvable` terminal · `V` 🔴 `Unknown` incomparable ·
`C` 🔴 `Resolved` is a different kind · `A` 🔴 no order, normative *(all seven `A` values are acquisition
**modes** — `Observed`/`Reported`/`Inferred`/`Calculated`/`Assumed`/`Hypothesized`/`Unknown` — and modes
do not order)*

## §15 THE RESULT DOMAIN — "decidable" vs "always returns a decision"

**Four rival codomains, unreconciled:**

| Locus | Codomain | Kind |
|---|---|---|
| `25S.3` | `{Same, Different, PossibleSame, Unknown}` | ⚠️ **CONFLATED** — relation + status |
| `38.10` | `{Unknown, Candidate, Probable, Confirmed, Rejected, Contextual}` | status (+`Contextual`) |
| `012 §35` | `{Determined, Supported, Candidate, Unknown}` | status |
| `012 §36/46` | `{Equivalent, Compatible, Contradictory, Related, Independent, Unknown}` | relation |

**`012 §36` states the principle the other three violate:**
> *"**The relation itself should not be confused with its epistemic certainty.**"*

`DERIVED` reconciliation *(mine, offered — not corpus, not recommended as architecture)*:
$$Decide_\equiv : \mathcal K \times \mathcal K \longrightarrow (Relation \times Status)$$
**Three of the four codomains are flattenings of this product** — the same defect shape as `Σ`'s
10-value flattening of five axes. ⚠️ *Second occurrence of that shape; still not a promoted finding.*

### The mandate's §15 distinction, applied

| | |
|---|---|
| **decidable relation** | there exists a total procedure returning TRUE/FALSE correctly |
| **procedure that always returns a decision** | ⚠️ a procedure returning `Unknown` is **total** without **deciding** |

$$\boxed{\text{A procedure that answers } Unknown \text{ is NOT a complete equality decision procedure.}}$$

**`012 §35` is decisive and is a limit, not a work item:** *"Semantic equivalence is generally **not
fully decidable**… we cannot promise KnowledgeOS will always determine whether two arbitrary sentences
mean the same thing."* → **`≡` can never reach a total Boolean procedure.** And `012 §35` draws the
right conclusion: the system must be able to say *"I don't know"* — *"**That is a feature, not a
failure**."* ✅ Agrees with `224.11`, `25S.4`, `38.22` — **four independent loci, one rule.**

## The completeness matrix — TWO orthogonal axes

**Procedure strength (§20 of the prior mandate) ⊥ semantic soundness (congruence).**
A relation can be maximally decidable and semantically useless — `=` is exactly that.

| Relation | Named | Defined | Typed | Param. | Decidable | Implementable | Gov.-auth. | **Congruent** |
|---|---|---|---|---|---|---|---|---|
| `=` structural | ✅ | ✅ | ✅ | ⚠️ canonicalization unbound | ⚠️ **CONDITIONAL** | 🔴 `id` conflict | 🔴 | 🔴 **NO** (EXECUTED) |
| `≡` semantic | ✅ | 🔴 **conflict `01 §3.3`** | 🔴 transitivity? | 🔴 `(C,t)` family | 🔴 **not fully decidable** | 🔴 | 🔴 | 🟡 required, unproven |
| `≈` observational | ✅ | ✅ `∀O∈𝒪_K` | ✅ | 🔴 **`𝒪_K` not closed** | 🔴 | 🔴 | 🔴 | 🟡 unproven |
| `≅_P` provenance | ✅ | ✅ two branches | ✅ | 🔴 `λ` | 🔴 | 🔴 | 🔴 | 🔴 |
| `≅_I` identity | ✅ | ✅ | ✅ | 🔴 *identity context* | 🟡 non-Boolean | 🔴 | 🔴 | ⚠️ operation-specific |
| `≅_H`/`≡_𝒯` | ✅ | ✅ | ✅ | 🔴 `𝒯` | 🔴 quotient (`260.11`) | 🔴 | 🔴 | — target |
| `∼_F` fold | ✅ | ✅ | ✅ | ✅ | ✅ | 🟡 | 🔴 | 🔴 **insufficient** (`258.11`) |
| `Continuity` | ✅ | ✅ functional | ✅ | 🔴 `Ω`, rules | 🔴 | 🔴 | 🔴 | 🔴 |
| `≡_D` value | ✅ | ✅ | ✅ | ✅ | ✅ | 🟡 | 🔴 | n/a — **wrong level** |
| **`≈_X`** *(mine)* | ✅ | ✅ | ✅ | ✅ **exactly 32** | ✅ | 🟡 | 🔴 | 🔴 not `K`-level |

$$\boxed{\text{ZERO unconditional Level-5 state-level relations. ZERO proven congruences. Implementable: empty. Governance-authorized: empty.}}$$

## STATUS
**ESTABLISHED** the level assignment; 15 promotion verdicts (6 INVALID-executed, 2 VALID-theorem);
`Σ`-order ≠ `K`-order re-audited **CLEAN**; `≡` not fully decidable; `Unknown`-returning ≠ deciding ·
**BOUNDED** `≈_X` at **exactly 32** distinct projections (EXECUTED, tightened from *"at most"*);
4 codomains enumerated · **NORMATIVE** which codomain; which canonicalization · **TECHNICALLY OPEN**
7 of 10 procedures · **BLOCKED** `𝒪_K`, `𝒯`, `δ` · **DEFERRED** codomain ratification
