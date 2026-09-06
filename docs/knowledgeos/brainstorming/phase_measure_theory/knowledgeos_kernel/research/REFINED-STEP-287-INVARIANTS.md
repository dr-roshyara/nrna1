---
artifact: REFINED STEP 287 · INVARIANTS `𝓘` — Candidate Invariants, Derivability, Dependencies, Open Conditions
mandates: `prompts/20260831-201424_step_287_reviewer-drafts-…-invariants-research-and-falsification-mandate.md` · `prompts/20260831-202424_step_287_reviewer-step-287-invariants-as-research-only-derivability-audit-not-normative.md`
date: 2026-08-31
status: **RESEARCH ARTIFACT — NOT NORMATIVE / NOT ARCHITECTURE**
verdict: **7 candidates · 0 ESTABLISHED · 2 DERIVED · 2 CORPUS-SUPPORTED · 2 CONDITIONALLY DERIVED · 1 G1 OPEN · 3 VACUITY RISKS**
---

# Refined Step 287 · Invariants `𝓘`

> ⚠️ **NUMBERING NOTICE.** Three sources place **equality at 285** and **Invariants at 287**; my
> existing `REFINED-STEP-287.md` is the **equality** artifact. **Both bodies of work are real; the
> identifier is contested and its resolution is a REGISTRY decision, not a research one.** Until
> resolved, cite by **subject**: *287-EQUALITY* and *287-INVARIANTS*. **Nothing is renumbered here.**

## 1. What an invariant is, in the current model

`CORPUS`+`DERIVED` — Not a property of one state. A property that **persists across an admissible
transition**:

$$\boxed{\;\mathcal I(P):\ \forall K,o.\ \textit{Admissible}(o,K)\ \Rightarrow\ \big(P(K)\Rightarrow P(\delta(K,o))\big)\;}$$

**And the schema itself needs five qualifications before `P` may be called an invariant:**
state domain · **equality relation** · transition class · whether `P` is structural / semantic /
provenance-sensitive / observational · scope (universal or transition-class-local).

$$\mathcal I=\{I_1,\dots,I_n\}\ =\ \textbf{the candidate set under investigation}\ \neq\ \text{a ratified architectural set}$$

## 2. ⚠️ Two schema-level blockers, before any candidate is assessed

| Blocker | Consequence |
|---|---|
| **`Admissible` is not evaluable** | 42.9 depends on `Assurance`, which the corpus has **`REFUTED as definable`**; 42.41's law belongs to the 7-tuple while the **ratified DC is a 6-tuple with no admissibility conjunction of its own.** ⇒ **the schema's antecedent cannot be decided** |
| **`δ` has no commit case** | `Γ` is derived and is not a component of `K`, so `δ` has nowhere to write a commitment. Executed: `K₁ is K₀`. ⇒ **the schema's consequent cannot be evaluated for the central operation** |

> ### **Every invariant below inherits both blockers.** No candidate can be `ESTABLISHED` while
> `Admissible` is undecidable and `δ`'s central case is a no-op. **This is a schema-level ceiling, and
> it is the single most important result of this step.**

## 3. Candidate invariant register

Classification per the mandate: `DERIVED` · `CORPUS-SUPPORTED` · `CONDITIONALLY DERIVED` ·
`NORMATIVE` · `G1 OPEN` · `REFUTED`.

> ⚠️ **M-6 correction (2026-08-31).** The single *"Vacuity"* column conflated two different
> properties: whether an invariant is **vacuity-safe** (its antecedent cannot be trivially satisfied)
> and whether it is **testable today** (§6). `I-𝒩` and `I-Π` are ✅ vacuity-safe **and** 🔴 UNTESTABLE
> — one column could not say both. **The column is now split; no verdict changed, only its reading.**

| # | Candidate | Formal statement | Class | Equality used | Vacuity-safe? | Testable today? (§6) |
|---|---|---|---|---|---|---|
| **I-𝒩** | **Knower persistence** | `Admissible(o,K_t) ⇒ 𝒩_t =_identity 𝒩_{t+1}` | **CORPUS-SUPPORTED** — FA-4 `Kṣetrajña` grade `[E]`; `I-1` goal-ownership across transitions | **`=_identity`**, ⚠️ *not* `=_structural` | ✅ safe | 🔴 no — needs `Admissible` + `𝒪` |
| **I-Π** | **Provenance persistence** | `Π(a)` immutable once created; `Lineage = Π ∘ ℛ_der*` | **DERIVED** — the `t=0` proof; refined by step 265 (`π ∈ K`, object outside) | provenance identity | ✅ safe | 🔴 no — needs `δ` |
| **I-A** | **Admissibility gate** | only `Admissible` operations may transition `K` | **G1 OPEN** | — | 🔴 **antecedent undecidable** (§2) | 🔴 no — `G1` |
| **I-C** | **Command ≠ Transformation** | `o ≠ δ(K,o)`; and `δ(K,o₁) ≡ δ(K,o₂) ⇏ o₁=o₂` | **DERIVED** — §256.21–22 boxed, from typing | ⚠️ **`=_semantic` only** | 🔴 **VACUOUS under `=_structural`** (`Π ∈ id`) and under `≅_λ` | 🔴 no — and conditional on Decision 3 |
| **I-V** | **Validity independent of outcome** | `Valid(o,K) ≢ Outcome(o)` — the admissibility predicate contains **no outcome-dependent conjunct** | **CORPUS-SUPPORTED** — inspection of 42.9/42.41 conjuncts | none (independence claim) | ✅ safe | ✅ **yes — static inspection** |
| **I-I** | **Identity is not state** | `Identity ≠ State` (23+18 corpus occurrences) | **CONDITIONALLY DERIVED** — ⚠️ collides with the **`id = H(P,e,c,t,Π)` / mutable `e.state`** contradiction | structural + identity | 🟠 **inherits a live contradiction** | 🔴 no — BLOCKED |
| **I-O** | **Observation ≠ Interpretation** | `W --Ω--> O`, `𝒩 ∉ O`; `Arjuna_K ≠ Sañjaya_K` | **CONDITIONALLY DERIVED** — Sañjaya layer is corpus-native; ⚠️ **`Ω` has no executable semantics** | layer-typed | ✅ safe | ✅ **yes — static** |

**0 of 7 `ESTABLISHED`.** 2 `DERIVED` · 2 `CORPUS-SUPPORTED` · 2 `CONDITIONALLY DERIVED` · 1 `G1 OPEN`.

## 4. Taxonomy — and the question of whether `𝓘` is one object

```
                              𝓘  (one object? — a RESEARCH QUESTION, not settled)
                              │
     ┌──────────────┬─────────┴─────────┬──────────────┐
     ▼              ▼                   ▼              ▼
 IDENTITY        TYPING            TRANSITION       LAYER
 I-𝒩, I-Π      I-C, I-I          I-A, I-V         I-O
 persistence   Command ≠ δ        P(K)→P(K')       Reality ≠ Observation ≠ Knower
               𝒩 ∉ K              admissibility     Observation ≠ Interpretation
```

> ⚠️ **The fourth category does not fit the schema.** **Layer invariants are not transition
> properties** — `Reality ≠ Observation ≠ Knower` holds *statically*, with no `δ` in sight. Forcing
> them into `Admissible(o,K) ⇒ (P(K) ⇒ P(δ(K,o)))` would make them **vacuously true**, because the
> antecedent never engages them.
>
> ### **Finding: `𝓘` is NOT one mathematical object.** At least two kinds are present — **transition
> invariants** (schema-shaped) and **layer/type invariants** (statically shaped). **Merging them under
> one symbol is a category error.** `DERIVED`, and it answers the mandate's own §11 research question.

## 5. Scope — no invariant without a transition class

**Mandate rule honoured:** *"No invariant may be stated without a transition scope."*

⚠️ **And the scope cannot currently be stated**, because the transition class is `𝒪`, and **`𝒪` has
never been enumerated against the ratified 8 primitives.** So every `∀o` in this register quantifies
over an **undetermined domain**.

> **Consequence:** `∀o` here has the same defect as `∀T ∈ 𝒯` at Step 259 §259.18 — *"the quantifier has
> no determinate meaning if `𝒯` is incomplete."* **The corpus already recorded this failure mode for
> congruence; it applies identically to invariants.**

## 6. Vacuity audit (mandate §18)

For each candidate: (1) can the antecedent hold? (2) is the transition class non-empty? (3) does the
equality relation permit meaningful comparison? — *only then* evaluate preservation.

| Candidate | (1) antecedent | (2) class non-empty | (3) equality meaningful | Verdict |
|---|---|---|---|---|
| I-𝒩 | 🔴 `Admissible` undecidable | 🔴 `𝒪` undetermined | ✅ `=_identity` | **UNTESTABLE, not vacuous** |
| I-Π | 🔴 | 🔴 | ✅ | **UNTESTABLE** |
| I-A | 🔴 **self-referential** — it *is* the antecedent | 🔴 | — | **G1 OPEN** |
| I-C | 🔴 | 🔴 | ⚠️ **only under `=_semantic`; VACUOUS under `=_structural` and `≅_λ`** | **CONDITIONALLY MEANINGFUL** |
| I-V | ✅ *(inspection, no transition needed)* | n/a | n/a | ✅ **testable today** *(with `I-O` — two, not one; M-7 corrected)* |
| I-I | 🔴 | 🔴 | ⚠️ contradiction | **BLOCKED** |
| I-O | ✅ *(static)* | n/a | n/a | ✅ **testable — because it is NOT a transition invariant** |

$$\boxed{\text{No vacuous invariant counts as substantively established.}}$$

> ⭐ **The two candidates that are testable today (`I-V`, `I-O`) are precisely the two that do NOT need
> `δ`.** Every schema-shaped candidate is untestable. **That is the shape of the result: the invariant
> programme is blocked exactly where it depends on the transition system.**

## 7. Dependency matrix — which invariants D288 blocks, and why

| Candidate | `≡` | `≈` | `≅_λ` | `Qualify` | `𝒪` | `Admissible` | `δ` |
|---|---|---|---|---|---|---|---|
| I-𝒩 | — | — | — | — | ✅ | ✅ | ✅ |
| I-Π | — | — | ⚠️ | — | ✅ | ✅ | ✅ |
| I-A | — | — | — | — | ✅ | ✅ **self** | ✅ |
| **I-C** | ✅ **decisive** | — | ✅ **flips it vacuous** | — | ✅ | ✅ | ✅ |
| I-V | — | — | — | — | — | ⚠️ inspection only | — |
| I-I | ✅ | — | ✅ | — | ✅ | ✅ | ✅ |
| I-O | — | — | — | ✅ **`Ω`→`E` blocked** | — | — | — |

**`I-C` is the most dependency-loaded:** it is substantive under `≡`-with-`Π`-excluded and **vacuous**
under `≅_λ`. **Decision 3 does not merely refine it — it decides whether it says anything.**

## 8. Independence (mandate §19)

| Candidate | KnowledgeOS-independent? | Philosophical corroboration |
|---|---|---|
| I-𝒩 | 🔴 **NO** — entered via the `Kṣetrajña` lineage (FA-4, `[E]`) | **contribution**, not corroboration |
| I-Π | ✅ yes — the `t=0` argument | none |
| I-C | ✅ yes — typing (§256.21) | Gītā `karma ≠ phala` **corroborates** |
| I-V | ✅ yes — conjunct inspection | Gītā 2.48 **corroborates** |
| I-I | ✅ yes | none |
| I-O | 🔴 **NO** — Sañjaya is corpus-native | — |
| I-A | ✅ yes | none |

**5 independent · 2 not — and both non-independent ones are corpus-native, not philosophical inference.**

## 9. What Step 287-INVARIANTS must NOT do — and did not

**No invariant declared canonical** · **no new invariant invented** · **`𝒪` not enumerated** ·
**`Admissible` not defined** · **`δ`'s commit case not supplied** · **Decision 3 not answered** ·
**`≈`'s `X` not selected** · **D288 not solved** · **no philosophical source used as architectural
evidence** · **nothing renumbered.**

## STATUS

**`RESEARCH ARTIFACT — NOT NORMATIVE / NOT ARCHITECTURE`**

### ESTABLISHED
- the invariant **schema**, with its five required qualifications
- **`𝓘` is not one mathematical object** — transition invariants and layer invariants have different shapes
- **a schema-level ceiling:** no candidate can be established while `Admissible` is undecidable and `δ`'s commit case is a no-op
- **`I-V` and `I-O` are the only candidates testable today — and neither needs `δ`**
- `∀o` inherits the Step 259 §259.18 quantifier defect

### BOUNDED
- 7 candidates, classified; 4 categories; dependency matrix complete

### NORMATIVE
- `Π ∈ ≡?` — **decides whether `I-C` is substantive or vacuous**
- `X ⊆ {A,S,R,V,C}?` · per-axis orders
- which categories legitimately belong to one `𝓘`

### TECHNICALLY OPEN
- `Admissible` · `δ` commit · `𝒪` enumeration · `Qualify` · `≡`/`≅_λ` procedures · the `id`/mutable-`e.state` contradiction

### DEFERRED
- all of the above; **D288 must consume this artifact and `REFINED-STEP-287.md` vNext together**

---

**Step 287-INVARIANTS establishes the candidate invariant space and its derivability status; it establishes no invariant of KnowledgeOS.**
