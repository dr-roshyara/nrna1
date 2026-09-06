---
artifact: REFINED STEP 290 · N-1 — `≡` / `≈` ADJUDICATION
mandate: `prompts/20260831-230100_step_290_reviewer-accepts-289-and-drafts-step-290-n1-equality-observational-adjudication-mandate.md`
scope: **N-1 ONLY**
package: `step-290/01`…`10` · `step-290/exec/t290_n1_audit.py`
date: 2026-08-31
verdict: **`N-1A — DISTINCT`** · and the "corpus contradiction" reported by Steps 288–289 is **WITHDRAWN**
---

# Refined Step 290 — N-1 Adjudication

## 1. Executive verdict

$$\boxed{\textbf{N-1A — DISTINCT}}$$

**The corpus explicitly distinguishes `≡` and `≈` as TYPED RELATION SLOTS, with `≡` labelled semantic
and `≈` labelled observational — at five independent loci and in two notation systems. This establishes
a DESIGN-LEVEL distinction; it does NOT establish that the two relations are semantically
distinguishable by an executable decision procedure** *(reviewer amendment, 23:31, making §1 and §3
consistent — §3 already said "not demonstrably semantically, because `≡` is an unfilled slot")*.
$$\boxed{\text{typed-slot distinction established} \neq \text{semantic non-equivalence proven}}$$ The apparent conflict that Steps 288 and 289 reported as a **corpus
contradiction** is not one — and this step withdraws that claim.

### 🔴 What Steps 288–289 got wrong

They reported: *"`258.8`/`261.21` define `≡_K` by the same formula `261.5` uses for `≈`, while
`261.1`/`246` list them as distinct. **Either `≈` collapses into `≡`, or `≡` has no definition at
all.**"* → registered as **`G-67`, CRITICAL, corpus contradiction**.

**The formula-sharing is real. The contradiction is not.** Both loci that supply `≡_K` with a
definition **self-label it as a candidate**:

| Locus | Self-label, verbatim |
|---|---|
| **`261.21`** | *"define **a candidate semantic equality**"* … and, boxed: *"`𝒪_K` is not yet completely closed. Therefore this is **a candidate formal definition, not a completed theorem**."* |
| **`258.8`** | *"**A stronger definition is** observational…"* — a proposal; and `258.37`: *"we still do not have the final `=_X` or `≡_K`"* |

$$\boxed{\text{The corpus does not ASSERT } \equiv_K = \approx. \text{ It PROPOSES filling the } \equiv_{sem} \text{ slot with the observational formula, and marks the proposal unratified.}}$$

> **A candidate proposal and an assertion are different speech acts. I read two candidates as two
> competing assertions and reported a contradiction that the corpus had already flagged as open.**
> **`G-67` is WITHDRAWN** and replaced by `N-1′` (§11) — a narrower, non-critical question.

## 2. Corpus relation register → `step-290/01`

**20 primary entries. Only 5 carry an actual definition; 15 merely NAME a relation.**

⭐ **New finding: Step 261 carries ONE register in TWO notations.**

| `261.1` | `=` · `≡` · `≈` · `≅_I` · `≅_P` · `≅_H` |
|---|---|
| **`261.20` / `261.25`** | `=_str` · `≡_sem` · `≈_obs` · `SameId` · `≡_H` · `≡_P` |

**Same six relations, different glyphs** (`≅_I`↔`SameId`, `≅_P`↔`≡_P`, `≅_H`↔`≡_H`). `261.25` states the
structure as a 7-tuple: `𝔎 = (K, =_str, ≡_sem, ≈_obs, SameId, ≡_H, ≡_P)`.

⚠️ **This is notation drift inside one document, not a second register** — so the earlier count of
**nine registers stands**; what is added is that `261` is internally inconsistent *in notation* while
perfectly consistent *in content*. **And the drift is itself a plausible cause of the confusion this
step is resolving.**

## 3–4. Definition comparison and distinguishability → `step-290/02`, `03`

| Q | Answer |
|---|---|
| **Q1** does the corpus explicitly state `≡` and `≈` are distinct? | ✅ **YES, EXPLICITLY** — `246` (non-interchangeable) · `261.1` (*"should therefore **not** be collapsed"*) · `261.20` (*"KnowledgeOS requires a typed **family** of relations"*, *"they should not be conflated"*) · `261.25` (the 7-tuple) |
| **Q2** does the corpus state `≈` is a special case / projection / synonym of `≡`? | 🔴 **NO** — the reverse: `261.21` proposes the observational formula **as a candidate for `≡`**, which is a proposal about `≡`, not a claim about `≈` |
| **Q3** do they differ in domain, codomain, parameters, procedure, congruence, use? | ⚠️ **Domain differs** — `≈` is defined for **histories AND states** (`261.5`); `≡` only for states. **Otherwise: not determinable — `≡` has no independent definition to compare** |
| **Q4** if they differ, how? | **Structurally, in the register's design** — different slots in a typed family. **Not demonstrably semantically**, because `≡` is an unfilled slot |
| **Q5** does any artifact use `≡_K` and `≈` interchangeably? | ⚠️ **Effectively yes, in ONE place** — `261.21`'s candidate formula is `261.5`'s `≈` formula verbatim. **But labelled a candidate** |
| **Q6** do two registers define identical relations under different symbols? | ✅ **YES** — `261.1` vs `261.20/25`, six-for-six (§2) |
| **Q7** enough evidence to establish the distinction without new normative assumptions? | ✅ **YES for the distinction itself** (Q1). 🔴 **NO for `≡`'s content** |
| **Q8** what would Governance have to declare? | **Not** *"are they distinct"* — that is answered. **Only: should `261.21`'s candidate identification be ratified?** → `N-1′` |

### The distinguishability test — **`UNDECIDABLE FROM CURRENT CORPUS`**

**EXECUTED, failing loudly as required** (`exec/OUT-t290_n1_audit.txt`):
```
FAIL LOUDLY: required definition ABSENT -> a decision procedure for ==  (semantic)
FAIL LOUDLY: required definition ABSENT -> a closed observation set O_K
```
Constructing either witness — `K_1 ≡ K_2 ∧ ¬(K_1 ≈ K_2)` or its converse — requires a procedure for
`≡` **independent of** the observational formula, or a closed `𝒪_K`. **Neither exists.**
**NO HYPOTHETICAL WAS CONSTRUCTED** (mandate §4).

> ⚠️ **So the distinction is established by the corpus's own design statements, and is NOT established
> by exhibiting a distinguishing pair.** Those are different strengths of evidence and must not be
> conflated. **`N-1A` rests on the former.**

## 5. `≈_X` audit → `step-290/04`

| Question | Answer |
|---|---|
| does `≈_X` instantiate the corpus's `≈`? | 🔴 **NO** — corpus `≈` is `∀O ∈ 𝒪_K` (`261.5`); `≈_X` projects `Σ`-axes |
| is it merely one formalization of *"observational"*? | ✅ **YES — that is exactly its status** |
| does any artifact specify which `X`? | 🔴 **NO** |
| is `≈_X` the same *kind* of relation as `≡_K`? | 🔴 **NO** — `≈_X` is `Σ`-level; `≡_K` is `K`-level, and **no `π: K → Σ` exists** (`289 §5`) |
| do all 30 remaining possibilities show under-specification or multiplicity? | ⭐ **UNDER-SPECIFICATION.** 30 survivors is not 30 canonical relations — it is **one unfilled parameter** |

$$\boxed{\textbf{bounded candidate family} \neq \textbf{selected canonical relation}}$$

**Carried from 289, not re-run** (mandate §5): `≈_∅` universal (1 block), `≈_{full}` discrete (2240
blocks) — both degenerate. **30 non-degenerate candidates.**

> ⚠️ **TERMINOLOGY CORRECTED by Step 291 §13:** this artifact called the endpoints *"not a
> candidate"*. **Too strong — the corpus supplies no validity criterion for them.** Correct wording:
> **32 mathematically distinct projections; 2 degenerate endpoints; 30 non-degenerate candidates.**
> `≈_{full}` is *exactly* structural equality on `Σ` — a real relation, degenerate only *as an
> observational abstraction*. **Calling it "not a candidate" deleted a real relation by mislabelling.**

## 6. Congruence audit → `step-290/05`

Does the corpus give an **independent** basis for requiring `K_1 ≡ K_2 ⇒ o(K_1) ≡ o(K_2)`?
✅ **YES — `258.9`**, which states it as *"the critical mathematical requirement"* and adds *"if it
fails, `≡_K` is too coarse."* **That is independent of `≈`.**

**Four things kept separate, as demanded:** equality itself · congruence of operations under equality ·
observational equivalence · transformation semantics.

> ⚠️ **The mandate's trap, and I checked I had not fallen into it:** *"Do not use the existence of a
> cycle as evidence that `≡` and `≈` are identical. A dependency relation is not a semantic identity."*
> **Step 289's cycles `≈ → ≡ → ≈` (Z-1) and `≈ → ≡ → bindings → ≈` (Z-3) are dependency structures.
> They were NOT used here as evidence of identity** — and Z-1 was already classed **CONTINGENT** on the
> supposed conflict. **With the conflict withdrawn, Z-1's status changes: see §9.**

## 7. Declaration vs derivation → `step-290/06`

| | **Branch A — Declaration** | **Branch B — Derivation** |
|---|---|---|
| **establishes** | a usable `≡`; breaks Z-2/Z-4; unblocks `δ`'s postconditions | a `≡` *justified* by congruence (`258.9`) |
| **does NOT establish** | ⚠️ that the declared `≡` is a **congruence** — `258.9` must still be *tested*; nor decidability (`012 §35`); nor `𝒪_K` | anything, yet |
| **missing constructs** | — | **`𝒯`** · **`δ`** · **`𝒪_K`** · a minimality proof (`259` forbids it pre-congruence) |

**Does the corpus close the fork?** 🔴 **NO.** `261.21` offers a candidate and declines to ratify it;
`258.37` says the final `≡_K` is not had; `259` blocks the derivation route on `𝒯`.
**No branch is selected.**

## 8. Step 261 §261.23 impact → `step-290/07`

> **Would adjudicating `≡` vs `≈` satisfy any of the six conditions?**

$$\boxed{\textbf{NO — not one of the six.}}$$

| # | Condition | Affected by N-1? |
|---|---|---|
| 1 | observation set not closed | 🔴 no — `N-1A` does not close `𝒪_K` |
| 2 | operation registry not closed | 🔴 no |
| 3 | provenance placement | 🔴 no — that is Decision 3 |
| 4 | assertion semantics | 🔴 no — **representation block** |
| 5 | temporal semantics | 🔴 no — **representation block** |
| 6 | identity semantics | 🔴 no |

**`N-1A` establishes that the two relations are distinct slots. It supplies neither slot's content.**
⚠️ **Explicitly: resolving equality does NOT close the `261.23` gate, and N-1 does not resolve equality
— it resolves only whether the register is self-contradictory.** *(It was not.)*

## 9. N-1 classification → `step-290/08`

$$\boxed{\textbf{N-1A — DISTINCT}}$$

**Not `N-1E` (corpus contradiction)** — the two definitional loci are self-labelled candidates.
**Not `N-1B` (same)** — five loci say otherwise.
**Not `N-1C` (derivable collapse)** — no established corpus rule forces it; `261.21` explicitly is *not
a theorem*.
**Not `N-1D` (governance-choice)** — *for the distinction itself*. ⚠️ **But a residual `N-1D`-shaped
question survives, and it is a different question:**

| | |
|---|---|
| **`N-1′`** | **Should `261.21`'s candidate identification `≡_sem := (∀O ∈ 𝒪_K)` be ratified?** |
| class | **NORMATIVE** — and **blocked on `𝒪_K`** (`261.23` cond. 1), so it cannot even be *evaluated* yet |
| priority | ⚠️ **NOT critical, and NOT the earliest act** — unlike `G-67`, which was mis-scored CRITICAL |

### Consequences for Step 289's cycle structure
**Z-1 (`≈ → ≡ → ≈`) was classed CONTINGENT on the conflict. With the conflict withdrawn, the edge
`≈ → ≡` survives only as `261.21`'s *candidate* — a proposal, not a definitional dependency.**
⚠️ **Z-1 therefore weakens to a candidate-dependency, and `{≡}`'s status as the minimal cut now rests on
Z-2 and Z-4** (`congruence ↔ ≡`, `congruence → ≡ → δ → congruence`), **which `02` already classed
NECESSARY and which survive every deletion test.** $\boxed{\{\equiv\} \text{ remains the minimal cut. Its justification narrows from 4 cycles to 3.}}$

## 10. What remains blocked

`𝒪` · `𝒯` · `𝒪_K` · `δ`'s commit case · `≡`'s content (`N-1′`, blocked on `𝒪_K`) · `≅_λ`'s `λ` ·
identity (5 of 11 kinds) · congruence (global) · the quotient · canonicalization · `Qualify`.
**`261.23`: still 0 of 6.**

## 11. What Governance would have to decide

**Nothing, for N-1.** The distinction is corpus-established.
**One narrowed item created:** **`N-1′`** — ratify or reject `261.21`'s candidate identification.
⚠️ **Step 291 §5 refines the block:** `N-1′` is blocked because `𝒪_K`'s **extension** is missing — but
**`≡_K` is *definable* without it** (`261.21`'s formula is well-formed for any `𝒪_K`). **Five of six
properties are blocked — definability is not**, which is why `N-1A` was decidable at all.
⚠️ **It cannot be put to Governance yet**: the candidate is defined over `𝒪_K`, and `𝒪_K` is not closed.
**So `N-1′` is downstream of `N-4`, not parallel to it.**

## 12. Explicit supersessions and corrections

| Claim | Where | Change | Class |
|---|---|---|---|
| *"corpus contradiction: either `≈` collapses into `≡`, or `≡` has no definition"* | `288 §3`, `289`, `00-INDEX` 17th/18th | **WITHDRAWN** — both loci are self-labelled candidates | **CORRECTION** (a false claim) |
| **`G-67`** *"`≡`/`≈` share one definition while listed as distinct — CRITICAL"* | `step-288/08` | **WITHDRAWN**, replaced by **`N-1′`** (non-critical, downstream of `N-4`) | **CORRECTION** |
| *"`N-1` is the earliest legitimate next act"* | `289 §22`, `00-INDEX` 18th | ⚠️ **N-1 is now PERFORMED and returns DISTINCT. The earliest act becomes `N-4` (`𝒪`/`𝒯`)** | **SUPERSESSION** |
| *"Z-1 is a cycle"* | `289 §2` | **weakened** to a candidate-dependency; `{≡}` still the cut, on 3 cycles not 4 | **REFINEMENT** |
| *"nine registers"* | `288 §2`, `289` | **stands** — plus `261` carries one register in **two notations** | **REFINEMENT** |

## 13. Evidence index → `step-290/10`

Primary: `246 §A–D` · `258.8` · `258.9` · `258.37` · `261.1` · `261.5` · `261.8` · `261.19` ·
**`261.20`** · **`261.21`** · `261.23` · **`261.25`** · `012 §35` · `264.15`.
Executed: `step-290/exec/t290_n1_audit.py` + transcript. Carried: `289`'s degeneracy result.
**Not used: any philosophical source; any governance preference.** *(mandate §§10–11)*

## STATUS

**ESTABLISHED** `≡` and `≈` are **distinct slots** in a typed family — 5 loci, 2 notations ·
`261` has one register in two notations · both `≡_K` definitional loci are **self-labelled candidates** ·
`≈_X` is not the corpus's `≈` · 30 survivors show **under-specification, not multiplicity** ·
congruence has an **independent** basis (`258.9`) · **N-1 satisfies 0 of `261.23`'s 6 conditions**
**BOUNDED** `≈_X` at 30 · the fork at exactly 2 branches
**NORMATIVE** **`N-1′`** only — and **not yet evaluable** (blocked on `𝒪_K`)
**TECHNICALLY OPEN** `≡`'s content; whether `≡` and `≈` are *semantically* distinguishable — **`UNDECIDABLE FROM CURRENT CORPUS`**
**BLOCKED** `N-1′` behind `N-4`
**REFUTED / WITHDRAWN** the `≡`/`≈` corpus contradiction · `G-67` · *"N-1 is the earliest act"*
**DEFERRED** everything downstream. **STOP.**

---

$$\boxed{\begin{array}{c}\textbf{The corpus HAS distinguished } \equiv \textbf{ and } \approx. \textbf{ It has NOT filled } \equiv.\\ \textbf{Choosing whether to collapse them requires authority — but not yet,}\\ \textbf{because the candidate is defined over an } \mathcal O_K \textbf{ that is not closed.}\end{array}}$$

> ### ⚠️ Before STOP — one statement that must not be lost
> $$\boxed{N\text{-}1A \textbf{ resolves the TAXONOMY / REGISTER question, NOT the equality-CONTRACT question.}}$$
> A later step could read `N-1A = DISTINCT` as *"`≡ ≠ ≈` has been mathematically demonstrated."*
> **It has not.** What is established is a **typed-slot distinction**; semantic distinguishability is
> **`UNDECIDABLE FROM CURRENT CORPUS`** (`03`).

**STOP.** No operation registry · no `𝒪_core` · no `𝒯` · no `δ` · no identity repair · no equality
decision procedure · no invariant enumeration · no kernel selection · no governance recommendation.

---

### Self-check log

| | Defect | Repair |
|---|---|---|
| **288 §3 / 289** | reported a **corpus contradiction** | 🔴 **WITHDRAWN.** Both loci self-label as candidates. **I read two candidate proposals as two competing assertions** — a speech-act error, not a reading error: the formulas *are* identical, and that was never the question |
| **`G-67` severity** | scored **CRITICAL**, *"adjudicate first"* | **withdrawn**; replacement `N-1′` is non-critical and **downstream of `N-4`** |
| **289 §22 answer 9** | *"earliest legitimate next act is `N-1`"* | N-1 performed; **the earliest act is now `N-4`** |

**Three corrections, all mine, all in the same direction: I over-read a documented openness as a
defect.** *The programme's standing hazard has been overclaiming; this was its mirror image —
**over-claiming a FAULT**, which inflates urgency exactly as an overclaimed result inflates progress.*
