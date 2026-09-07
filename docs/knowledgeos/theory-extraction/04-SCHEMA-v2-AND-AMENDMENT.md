# Extraction Schema **v2** — with Amendment Note

**2026-09-07 · `[DEF]` · amends this project's own machinery only.**

> ⛔ **Scope of this amendment.** `three_model_convergence`, `dimension-registry.md`, **MD-017**,
> **MD-018** and every other external or governed artifact are **untouched**. Where this project needs
> a value MD-017 lacks, it defines it **in its own vocabulary** and says so.
>
> **No adjudication · no canonicalization · no carrier selection · no theory promotion.**
> **No historical evidence rewritten** — v1 records are **appended to**, never edited.

---

# Amendment Note — why A, B and C were required

Each defect was found **by contact** with a concept of a kind the `K` pilot did not contain, and
**two of the three would have produced factually wrong records** if left unfixed.

| | defect | what it would have produced | severity |
|---|---|---|:--:|
| **A** | implementation status implied theoretical status | **`Qualify` recorded as *"implemented"*, from which a reader concludes `G1` (*no body*) is closed** — while the code's own comment marks its behaviour as an **input** | 🔴 **wrong record** |
| **B** | `Concepts` absorbed operations and relations | an **operation filed as an object**, collapsing `Command ≠ Transformation` (§256.21) — the estate's own protected distinction | 🔴 **category error** |
| **C** | a repeated glyph became another definition | **`D-05: δ = 0.3099`** — asserting that `FR-001`'s **measured resolution constant** is a candidate definition of the **transition function** | 🔴 **wrong record** |

`[INF]` **`K` alone could not have exposed any of the three.** `K` is an object, all ten of its
definitions are of `K`, and its implementation question is about a data type. **The stress design was
what produced the defects — not review of the pilot.**

---

# 1. Defect A — implementation **provenance**

## The governing rule, recorded explicitly

$$\boxed{\begin{array}{c}\textbf{Running code is evidence of an implementation FACT; it does not ratify the theory.}\\ \textbf{Conversely, STIPULATED code does not refute a theory gap.}\end{array}}$$

## The field, in two parts

**`Implementation:` keeps its four levels, unchanged:**

`none` · `type-exists` · `operation-runs` · `result-produced-on-it`

**and now carries a mandatory provenance qualifier:**

| value | meaning | admissibility rule |
|---|---|---|
| **`derived`** | the body **follows from the theory**; no free choice was exercised | ⛔ **assert only on positive evidence that the theory fixes the body.** Never inferred from a body merely *matching* a definition |
| **`stipulated`** | the body embodies a **choice the theory does not make** — a policy, a projection, an encoding, a convention | the default when a choice is visible |
| **`unknown`** | provenance not determinable from the artifact | recorded, never guessed past |

**And a second mandatory sub-field where several definitions compete:**

```
selection: stipulated | forced | n/a      ← which DEFINITION the code implements
```

`[INF]` **`selection` is separate from provenance because they are separate choices.** A body may be a
faithful encoding of `D-01` (**provenance** question) while *the choice of `D-01` out of ten* is a
stipulation nobody recorded (**selection** question). **`K` is exactly that case.**

⚠️ **`derived` is defined and currently has ZERO instances** across all four records. **Recorded as
empty, not filled** — per the charter's *"emptiness is recorded, never filled."*

# 2. Defect B — **candidate kind**, separated from three other axes

$$\boxed{\textbf{candidate kind} \;\neq\; \textbf{epistemic status} \;\neq\; \textbf{implementation status} \;\neq\; \textbf{grounding}}$$

| axis | answers | vocabulary |
|---|---|---|
| **candidate kind** *(new)* | **what kind of thing is this?** | below |
| epistemic status | how well established? | 13-value + MD-018 chain *(borrowed)* |
| implementation status | is there code, and what warrants it? | 4 levels × provenance *(§1)* |
| grounding | where did the evidence come from? | 5-value *(borrowed)* |

## `kind:` — four values, and why the fourth

| value | test | instances |
|---|---|---|
| **`object`** | names a thing that can be in a state | `K` |
| **`operation`** | maps state(s) to state(s); has a signature and possibly a body | `δ`, `Qualify` |
| **`relation`** | holds *between* things; has an arity and properties | `≡_sem` |
| **`constant`** | a **measured or fixed quantity**, not a thing, operation or relation | ⭐ **`δ = 0.3099`** |

⭐ **The three named in the commission are NOT sufficient, and the evidence already shows it.**
Defect C turns `δ = 0.3099` into a **separate candidate**, and it is none of object / operation /
relation. **`constant` is therefore demanded by existing evidence, not anticipated.**

### Kinds observed inside definitions but **not yet demanded as candidate kinds**

**Recorded as a watch list; deliberately NOT added.** A kind is added when a *candidate* needs it, not
when a *definition component* exhibits it.

| observed | where |
|---|---|
| **value-set / enumeration** | `Qualify` `D-02`'s codomain `{Qualified, Unqualified, Undetermined, Terminus}`; `Σ`'s four values |
| **structure / family** | `≡_sem` `D-02`'s `𝔎 = (K, =_str, ≡_sem, ≈_obs, SameId, ≡_H, ≡_P)` — a 7-tuple **of relations** |
| **space / set-with-members-undefined** | `K` `D-09` `𝕂 = {admissible knowledge states}` |

⚠️ **If `Σ` is extracted next it will probably demand `value-set`.** Predicted, **not pre-added** —
adding it now would be designing the schema instead of discovering it.

## Reclassification discipline

> **A record is never silently reclassified to make the schema fit.** The v1 `Category` line stays;
> `kind:` is **added beside it**, with the original protest preserved verbatim.

# 3. Defect C — glyph **homonyms**

$$\boxed{\textbf{A repeated glyph is NOT automatically another definition of the same candidate.}}$$

### The containment criterion *(sub-rule, adopted 2026-09-07 — discovered while validating, see `05` §4-E)*

$$\boxed{\textbf{If } X\textbf{'s definition CONTAINS } Y \textbf{ as a component, then } X \neq Y \textbf{ — disposition 2, on evidence.}}$$

**It settles a homonym without appealing to an undeclared script distinction.** Applied to `K`:
`𝒦 = (K, H)` and `𝒦 = (K, C, T, E, A)` **contain `K`**, so `𝒦` is a structure that *has* `K`, not a
variant spelling of it. ⚠️ **The criterion decides only in the positive direction** — absence of
containment yields **disposition 3 (ambiguous)**, never disposition 1.

**Every repeated-glyph occurrence receives exactly one disposition:**

| # | disposition | consequence |
|---|---|---|
| **1** | **same candidate / additional definition** | enumerate as `D-nn` |
| **2** | **different candidate sharing the glyph** | ⛔ **not a `D-nn`.** Record under `homonyms:`; **reserve a separate candidate ID**; route the glyph to `H1` |
| **3** | **ambiguous candidate identity** | ⛔ **not confirmed as a `D-nn`.** Record under `ambiguous_identity:` with the reason the identity cannot be settled |
| **4** | **non-definition symbolic occurrence** | record under `symbolic_occurrences:`; **never** a `D-nn` — e.g. a framework name, a quantifier variable, a summation sign |

## Validation case — `δ`

| occurrence | disposition |
|---|---|
| `δ(K_t, e_t)` · `δ(K, o)` · `δ(K,o)=Reject(r)` | **1** — additional definitions |
| ⭐ **`δ = 0.3099`** *(`FR-001` resolution constant)* | **2** — **different candidate.** ID reserved **`KOS-T-0005`**, `kind: constant`. ⛔ **NOT enumerated as a definition of the transition function** |
| `δ = successor-state axioms` · `δ = situation calculus` | **4** — **naming a framework, not defining `δ`** |

**New record fields:** `homonyms:` · `ambiguous_identity:` · `symbolic_occurrences:`

# 3b. Empty-field discipline — **B-6, APPLIED 2026-09-07 (mechanical)**

**Source:** second stress pass, `07-STRESS-REPORT-2.md` §B-6, classified **mechanical** and therefore
charter-authorized.

$$\boxed{\mathtt{n/a} \neq \mathtt{never}}$$

| value | meaning | when it is correct |
|---|---|---|
| **`n/a`** | **structurally inapplicable** — the field cannot have a value for this record or at this level | e.g. `provenance` and `selection` where `Implementation = none`: **with no body there is no warrant to describe and no definition being selected** |
| **`never`** | **applicable · searched · no instance found** | e.g. `governed decision` — a concept *can* be decided; `governance/` was searched; nothing found |
| *a value* | found | — |

## Two prohibitions, stated as the amendment requires

- ⛔ **`n/a` may NOT be used to mean *"none found."*** That is `never`.
- ⛔ **`never` may NOT be used where the field is structurally inapplicable.** That is `n/a`.

## `never` carries its scope

**A `never` is a measurement and inherits the scope rule** (§"corpus scope"): it states *what was
searched.* `never` **without a declared scope is not admissible** — it would assert absence from the
corpus on the strength of a search of part of it.

## Clarification of the level `none` *(mechanical, same amendment)*

`Implementation = none` is a **level in the four-level vocabulary**, not an empty-field marker. It
means **searched, in the declared scope, and no implementation found** — it does **not** mean *not
looked for*. ⚠️ **A `none` that was never searched must not be recorded**; `[PROP] P-13` proposes a
third value for that case and **is not applied.**

## Where B-6 was applied

| record(s) | field | was | now |
|---|---|---|---|
| **all eight** | `latest governed decision` | `EMPTY` | **`never`** *(searched `governance/`)* |
| `KOS-T-0011` `ℐ` | `latest implementation` | `n/a` | **`never`** *(applicable, searched in scope, none found)* |
| `KOS-T-0009` `Zero` | `selection` for `D-02`/`D-06` | `—` | **`n/a`** *(no body ⇒ inapplicable)* |

⚠️ **Preserved unchanged, because they are correct:** every `n/a` on `provenance` and `selection`
where `Implementation = none`. **B-6 is as much about not converting a correct `n/a` as about fixing a
wrong one.**

# 4. Schema v2 — the record shape

```
ID · Concept
kind                object | operation | relation | constant          ← NEW (Defect B)
Category            one of the 19 + Operations/Relations if adopted   ← v1 line PRESERVED
Definitions         D-01 … D-0n   (disposition 1 only)                ← Defect C
homonyms            glyph → other candidate, ID reserved              ← NEW
ambiguous_identity  glyph → why identity is unsettled                 ← NEW
symbolic_occurrences                                                  ← NEW
Relationships       MD-017 six values + `misattribution` (local)      ← local addition, MD-017 untouched
Source · Latest     mention | refinement | implementation | governed-decision
Evidence
Implementation      level × {derived | stipulated | unknown}          ← Defect A
  selection         stipulated | forced | n/a                         ← Defect A
Dependencies · Conflicts · Gaps · Open questions
Status              13-value · MD-018 chain · Grounding
Confidence · Implementation consequence
```

## What v2 does **not** change

- the 19 categories are **not** yet extended — `Operations`/`Relations` remain `[PROP]` from `03` §2;
  **`kind:` records the fact without pre-empting that decision**
- **MD-017 is not amended** — `misattribution` is this project's local value
- **no v1 record is edited** — all four are **appended to** in `05`
- **nothing is adjudicated, canonicalized, promoted, or selected**
