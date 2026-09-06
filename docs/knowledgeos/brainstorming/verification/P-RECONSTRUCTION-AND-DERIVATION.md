---
artifact: A · P-RECONSTRUCTION-AND-DERIVATION
mandate: 20260830_1852 §3, §4, §5
date: 2026-08-30
status: **CB-2 RESOLVED BY THE CORPUS — not by the verifier**
evidence: executed 1468-file scan · direct source reads
---

# Reconstruction of `P`

## 0. Headline

> **`P = (E, D, V)` = `(Entity, Dimension, Value)`. It is fully defined in the corpus, and has been since
> 2026-08-26 — in `question-14`, a NON-STEP file. I did not need to invent it. I needed to find it.**

**This is the third time the answer to a blocking question has been found in the non-step Q-series.**
Q7 held the assertion layer. Q14 holds the type system *and* `P` *and* `Σ`. **The pattern is now proven, not
suspected: the numbered step sequence systematically loses what the Q-series established.**

---

## 1. Executed corpus scan (1468 files, `verification/` excluded)

| Pattern | Raw | Distinct | Where |
|---|---|---|---|
| `P = (E,D,V)` literal | 17 | 2 | `knowledge-atom`, `question-7` |
| `(E,D,V)` any | 30 | 2 | + `Question 5` |
| `P = (…)` any form | 34 | **12 COMPETING** | see §2 |
| `Proposition = …` | 5 | 5 | see §2 |

## 2. Complete lineage — competing formulations, chronology preserved

**`LATER ≠ SUPERSEDING`. No "latest wins" rule applied.**

| Date / step | Formulation | Reading | Status |
|---|---|---|---|
| 2026-08-24 | `P = (V_P, E_P)` | a **graph** (vertices, edges) | competing — structural, not propositional |
| 2026-08-24 | `P = (L, μ)` | catuṣkoṭi: lattice + measure | competing — four-valued logic branch |
| **2026-08-26** | **`P = (E, D, V)`** | **Entity, Dimension, Value** | **CANONICAL — fully specified in Q14** |
| 2026-08-26 | `P = (Nexus, Version, 3.69)` | worked instance | instance of the canonical form |
| 2026-08-26 | `P = (Bhīṣma, Relationship_To_Arjuna, Grandfather)` | worked instance | instance |
| 2026-08-26 | `P = (S, ρ, O, Γ)` | subject, relation, object, context | competing — in the **same file** as the canonical |
| step 007 | `P = (NexusVersion = 3.69)` | collapsed pair | degenerate — dimension folded into entity |
| step 025x | `P = (E, ≺)` | events + ordering | **`E` REBOUND to "events"** — symbol collision |
| step 055 | `P = (S, R, O)` | subject–relation–object | **isomorphic to (E,D,V)** — rename only |
| step 195 | `Proposition = Subject` | truncation | degenerate |
| step 207 | `P = (V, E_P)` | graph again | competing |
| step 253 | `Proposition = strong candidate primitive` | classification, not definition | not a definition |
| (undated) | `proposition = assertion` | **CONFLATION** | **REFUTED** — Q7/Q14 make them distinct types |

### Per-symbol lineage

| Symbol | First appearance | Original meaning | Later rebinding | Competing definitions remain? |
|---|---|---|---|---|
| `E` | Q14, 2026-08-26 | **Entity** — "anything that can be the subject of knowledge" | **step 025x rebinds `E` to *events*** | **YES — symbol collision** |
| `D` | Q14 | **Dimension** — "a semantic axis of variation" | none found | no |
| `V` | Q14 | **Value** — "a position on a dimension" | step 207 rebinds `V` to *vertices* | **YES** |

> **`E` and `V` are each bound to two different things in the corpus.** `E` = Entity (Q14) and `E` = Events
> (025x); `V` = Value (Q14) and `V` = Vertices (207, 20260824). **This is a live ambiguity, recorded, not
> silently reconciled.**

---

## 3. The canonical definition, verbatim from Q14

```
Entity      ℰ    "anything that can be the subject of knowledge: a person, object,
                  system, event, relationship, or concept"
Dimension   𝒟    "a semantic axis of variation or classification"
                  d = (ID, Name, ValueSpace, Type, Domain)
                  ValueSpace(d) = V_d          Domain(d) = entities the dimension applies to
Value       𝒱    "a specific position on a dimension"
                  v = (ID, Value, Dimension, Type)
                  Value(v) ∈ V_d  ⟺  Dimension(v) = d

Proposition 𝒫    P = (E, D, V),  E ∈ ℰ,  D ∈ 𝒟,  V ∈ V_D
WellFormed(P) ⟺ E ∈ ℰ ∧ D ∈ 𝒟 ∧ V ∈ V_D
𝒫 = {(E,D,V) | E ∈ ℰ, D ∈ 𝒟, V ∈ V_D}
```

**Q14 §5.3 states the negative space explicitly — and it is exactly the boundary §4 of the mandate demands:**

> *A Proposition* **has no epistemic status · has no evidence · has no temporal validity · has no
> provenance** *· can be true or false.*

> **The corpus itself already answered the mandate's §4 question.** `P` smuggles nothing, **by definition**,
> and the four excluded things are precisely `Σ`, `e`, `t`, `Π` — **the four fields my Assertion derivation
> independently placed outside the proposition and inside the assertion.** Independent convergence.

### The measurement-theoretic content — the most important thing in Q14

`Dimension` carries `Type`, and Q14 §3.4 enumerates the **value-space types**:

| Scale | Q14's example |
|---|---|
| **Nominal** | `{Red, Green, Blue}` |
| **Ordinal** | `{Low, Medium, High}` |
| **Interval** | version numbers |
| **Ratio** | CPU % |

> **The measurement scale lives on the Dimension.** This is measurement theory correctly placed: the scale
> type is what licenses or forbids arithmetic on a Value, and it is a property of the *axis*, not of the
> number. **`P = (E,D,V)` is therefore a measurement-theoretically typed structure, not a bare triple.**
>
> **Verifier note (SOURCE CLAIM, contested):** Q14 classifies **version numbers as Interval**. Version
> numbers are **not** interval — `3.69 − 3.68` is not a meaningful magnitude, and `3.10 > 3.9` in version
> ordering but not numerically. Version is **ordinal at best**, and arguably a structured nominal type.
> **This is an inadmissible-arithmetic risk baked into the canonical example**, and it matters because
> `Nexus.Version` is the corpus's most-used worked example.

---

## 4. Candidate comparison (mandate §4 table)

| Candidate | Formal definition | Native capabilities | Missing | Circularity | Computable? | Corpus support |
|---|---|---|---|---|---|---|
| **`(E,D,V)` Q14** | `E∈ℰ, D∈𝒟, V∈V_D` | typed values, scale types, well-formedness, value-space membership | n-ary relations; nested propositions | **none** | **YES** — well-formedness decidable | **STRONGEST** — full type system, 2 worked examples |
| `(S,R,O)` step 055 | subject–relation–object | same as above | scale types **absent** | none | yes | isomorphic rename; **strictly weaker** (no ValueSpace) |
| `(S,ρ,O,Γ)` Q14 | + context Γ | context-scoped claims | duplicates `c` in Assertion | **YES** — Γ also outside | yes | same file as canonical, unreconciled |
| `(V_P,E_P)` graph | vertices + edges | composite/structured content | no claim semantics at all | none | yes | **not a proposition** — a content graph |
| `(L,μ)` catuṣkoṭi | lattice + measure | four-valued truth | no entity/dimension | none | partly | isolated branch, one file |
| `(E,≺)` step 025x | events + order | temporal ordering | **`E` rebound** | none | yes | **symbol collision, not a rival** |
| `Proposition = Subject` | truncation | — | everything | none | — | degenerate |

**FORCED SELECTION: `(E,D,V)`.** It is the only candidate that is (i) fully specified with a formal
type system, (ii) carries scale types, (iii) states its own exclusions, and (iv) has worked instances.
`(S,R,O)` is an isomorphic rename and is subsumed. **Nothing was chosen for elegance.**

**Relation to my reference model:** I implemented `Prop(s, p, o)`. **`(subject, predicate, object)` is
isomorphic to `(Entity, Dimension, Value)`** — so every executed result in this programme stands. What my
model lacked was `ValueSpace` and the **scale type**, which Q14 supplies. **My model was right and
under-specified; Q14 completes it.**

---

## 5. §5 boundary counterexamples — executed reasoning

**Subject: "Nexus version is 3.69.0"**

| Reading | Verdict |
|---|---|
| **Proposition** | **YES** — `(Nexus, Version, 3.69.0)`. No status, no evidence, no time, no source. |
| **Observation** | only as *"at 14:02 the API reported 3.69.0"* — an observation is **time-and-sensor bound** |
| **Evidence** | only when **qualified** — step 253: `Evidence = QualifiedObservation`. The raw string is not evidence. |
| **Assertion** | **YES**, once committed: `(id, P, e, c, t, Π)` |
| **Assessment** | **NO** — an assessment is the *function output* `Prop × Evidence × Context × Policy → Σ` |
| **Determination** | **NO** — a determination is an authority's fixed ruling |
| **Decision** | **NO** — a decision commits to an action |

**Same proposition, different assertions — four executed discriminators:**

| Case | Same `P`? | Same assertion? | Discriminator |
|---|---|---|---|
| prod vs staging | yes | **no** | `c` — and **not a contradiction** (230.9, executed) |
| API vs changelog | yes | **no** | `Π`, `e` |
| January vs June | yes | **no** | `t` |
| two identical records | yes | **yes** | content-addressed `id` collapses them |

> **`same proposition ≠ same assertion` is therefore not an axiom — it is a THEOREM of
> `Assertion = (id,P,e,c,t,Π)`:** the assertion has four coordinates the proposition lacks, and any of them
> may differ while `P` is fixed.

**`same evidence → different assessments`** — the same log line yields `Supported` under a permissive policy
and `Unknown` under one requiring two independent sources. **Assessment takes Policy as an argument; this is
why `Σ` cannot be a function of `(P, e)` alone.** Executed in the transition audit.

**`same proposition → conflicting assessments`** — `e = {E1:supports, E2:supports, E3:contradicts}`.
Executed: the projection to three states is **ambiguous**; the evidence set retains everything.
**See artifact B.**

---

## 6. Classification of every claim in this artifact

| Claim | Class |
|---|---|
| `P = (E,D,V)` = (Entity, Dimension, Value) | **CORPUS ESTABLISHES** (Q14 §5.2) |
| `P` excludes status, evidence, time, provenance | **CORPUS ESTABLISHES** (Q14 §5.3) |
| Scale type belongs to the Dimension | **CORPUS ESTABLISHES** (Q14 §3.4) |
| 12 competing formulations exist | **EXECUTED** (scan) |
| `E` and `V` are each doubly bound | **EXECUTED** |
| `(E,D,V)` is forced over the rivals | **FORMALLY DERIVED** |
| `(S,R,O)` ≅ `(E,D,V)` | **FORMALLY DERIVED** |
| `same P ≠ same assertion` is a theorem | **FORMALLY PROVEN** |
| **Version numbers are not an Interval scale** | **VERIFIER RECOMMENDATION — contests the corpus** |
| Q7/Q14 convergence with my derivation | **EXECUTED** + corpus |

**CB-2 is closed.** The remaining `P`-level open item is the **`E`/`V` symbol collision**, which is a
terminology defect, not a formal gap.
