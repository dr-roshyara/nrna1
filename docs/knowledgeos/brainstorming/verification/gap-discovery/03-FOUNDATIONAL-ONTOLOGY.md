# 03 — Foundational Ontology: Primitives and the Dependency Graph

**Mandate §8.** Executed evidence: `exec/exp_ontology.py`, transcript below is reproducible with
`python3 exec/exp_ontology.py`.

---

## 1. Method

The mandate asks seven questions of each candidate primitive. Rather than answer them narratively,
this session built the **definitional dependency graph** — an edge `X → Y` means *the corpus's own
definition of X mentions Y* — and ran three tests on it: cycle detection, reachability to Step 266's
non-computable class, and a removal test.

Every edge is sourced to a corpus definition; the source is recorded in the `DEPS` table of
`exec/exp_ontology.py`. **26 nodes, 42 edges.**

Where a definition is ambiguous, I chose the reading that makes the graph *more* acyclic — i.e. the
test is biased **against** finding a problem.

---

## 2. RESULT 1 — The dependency graph is cyclic

```
strongly-connected components with >1 node: 1

CYCLE: {Assertion, Assessment, Authority, EpistemicStatus, Evidence, Policy, Rule}

witness path:  Assertion → Evidence → Rule → Policy → Authority → Assertion
```

Read the witness edge by edge:

| Edge | Corpus source |
|---|---|
| `Assertion → Evidence` | Step 267 §267.6: `A = (id, P, e, c, t, Π)`, `e` is the evidence field |
| `Evidence → Rule` | Closure-04: `Evidence(O, P, C, R)` — `R` is the evaluation rule |
| `Rule → Policy` | Step 253 §253.10: a rule is an element of a policy |
| `Policy → Authority` | Step 270 §270.2 and Step 155: policy change is itself an authorized act |
| `Authority → Assertion` | Step 187 §187.23: `AuthorityState = (AS, B)` where `B` is the **recorded normative/provenance basis** — a grant is a recorded claim |

**This is `DERIVED`, and it is the mandate's §8 question 2 answered in the affirmative: yes, there is
circularity.**

### 2.1 The corpus *does* have an answer, and it is a stipulation, not a proof

Step 187 breaks the cycle explicitly:

$$\boxed{\text{Kernel enforces authority claims; Kernel does not originate authority.}}$$
$$Authority \rightarrow GovernanceRule \rightarrow TransitionContract \rightarrow \text{KnowledgeOS enforcement}$$

and defines `Authority ⊆ Actor × Action × Context × Time` — a relation over exogenous sorts, with no
`Assertion` in it.

**This session's assessment.** The move is legitimate and it is good architecture. But it must be
classified accurately:

- It is a **`NORMATIVE DECISION`**, not a mathematical result. Nothing in the model *forces*
  authority to be exogenous; the corpus *declares* it so in order to terminate the regress.
- The declaration is **not free**: it makes the theory acyclic only **relative to an external
  authority oracle**. Every theorem about `K` is then conditional on an oracle whose behaviour the
  theory does not specify.
- And Step 187 §187.23's own `B` — the "normative/provenance basis" of an authority state — is
  exactly the recorded artefact that re-enters the cycle. The stipulation cuts the edge in the
  *architecture* while `B` keeps it in the *representation*.

**Finding ON-1 (`DERIVED`, CRITICAL for §22's governance closure):** the theory is acyclic if and
only if authority is exogenous, and the corpus's grounds for exogeneity are normative. Semantic
closure and governance closure are therefore **not** both achieved; §10 of this programme
(`10-GOVERNANCE-REFLEXIVITY-GAP.md`) tests whether the running system honours the stipulation.
*(Answer, established there empirically: it does not.)*

---

## 3. RESULT 2 — 11 of 26 objects are transitively non-computable

Step 266 classifies eight objects as **Class C — not computable as currently defined**:
`Relevance, Truth, Adequacy, Authority, Assessment, EpistemicStatus, Minimality, Policy`.

Propagating that through the dependency graph:

```
nodes whose definition transitively reaches a class-C object: 11/26

  Assertion     via Assertion → Evidence → Rule → Policy
  Evidence      via Evidence → Rule → Policy
  Rule          via Rule → Policy
  Relation      via Relation → Assertion → …
  K             via K → Assertion → Evidence → Rule → Policy
  Operation     via Operation → K → …
  T_algebra     via T_algebra → Operation → …
  Equivalence   via Equivalence → T_algebra → …
  KStar         via KStar → Equivalence → …
  History       via History → K → …
  Governance    via Governance → Policy

nodes NOT blocked (10):
  Context, Dimension, Entity, Observation, Proposition,
  Provenance, RelationType, Source, Time, Value
```

**Finding ON-2 (`EXECUTED`, CRITICAL).** Step 266's audit table marks *individual* objects
green/yellow/red. It does not propagate. When the propagation is executed, **the central object `K`
is itself in the blocked set**, and so are `T`, `≡`, `K*` and `History`. Step 266's own candidate
principle —

$$Computability(T) \Rightarrow Computability(\text{all semantic dependencies of } T)$$

— is stated but never applied to the graph. Applying it yields: *the computable core is exactly the
ten structural objects above, and `K` is not one of them.*

This is not a contradiction of Step 266; it is its unstated consequence. Step 266 §266.34 says "the
final `K*` is still not proven" — this result says something sharper: **`K` as currently defined
cannot be computable, because `Assertion` carries an evidence field whose relation depends on a
relevance predicate with no decision procedure.**

### 3.1 The repair is available and it is small

Split the assertion:

$$A_{struct} = (\mathrm{id}, P, c, t, \Pi) \qquad\text{and}\qquad A \;=\; A_{struct} + e + \sigma$$

`A_struct` depends only on unblocked nodes, so `K_struct = (𝒜_struct, ℛ)` **is** in the computable
core — and it is exactly the object the EKP already runs (`exec/OUT-ekp-bridge.txt` EXP-11:
`|𝒜| = 40`, `|ℛ| = 59`). `e` and `σ` then attach as a **qualification layer** across the judgement
boundary Step 266 §266.31 already names. This session offers it as `PROPOSED`, not as a finding.

---

## 4. RESULT 3 — Removal test (Step 254's specified-but-unexecuted test)

Step 254 specified `Pᵢ → ∅` followed by "can every required capability be reconstructed?" and the
corpus never ran it. Executed (`exec/exp_ontology.py` EXP-18; 26 nodes total):

| Primitive removed | Surviving nodes | `K` survives? |
|---|---:|---|
| `Entity` | 6 | **no** |
| `Dimension` | 7 | **no** |
| `Value` | 8 | **no** |
| `Time` | 8 | **no** |
| `Source` | 8 | **no** |
| `Observation` | 9 | **no** |
| `Proposition` | 9 | **no** |
| `Context` | 9 | **no** |
| `Assertion` | 10 | **no** |
| `Relation` | 18 | **no** |
| `RelationType` | 17 | **no** |

**Finding ON-3 (`EXECUTED`).** Every one of the eleven candidates is load-bearing for `K`: removing
any of them destroys it. That is a *negative* minimality result in the useful direction — it says the
candidate set contains **no redundant member relative to `K` as defined**.

It says nothing about *sufficiency*, and it must not be read as "the primitive set is correct". It
also exposes the ordering: `Entity` is the most load-bearing (removal leaves 6 of 26 nodes) because
`Dimension → Entity` and `Value → Dimension` chain into `Proposition`. `Relation`/`RelationType`
are the least (18/17 survive) — they matter only from `K` upward.

---

## 5. Answering the mandate's seven questions

| Question | Answer | Class |
|---|---|---|
| 1. Definable without another undefined concept? | Ten objects yes (§3 list). Sixteen no. | `EXECUTED` |
| 2. Circular dependency? | **Yes** — one 7-node SCC (§2). Broken only by a normative stipulation. | `EXECUTED` |
| 3. Empirically observable? | `Observation`, `Source`, `Time`, `Provenance`, `Proposition`, `Entity/Dimension/Value` — yes, all instantiated in the EKP. `Assessment`, `Authority`, `EpistemicStatus` — **not observable in the running system at all** (`exec/OUT-ekp-bridge.txt` EXP-12/14). | `EMPIRICALLY OBSERVED` |
| 4. Mathematically typed? | The ten unblocked nodes, yes. `Context` is typed as a symbol with **no declared domain** anywhere — see below. | `DERIVED` |
| 5. Instantiable? | Yes for all ten; `|𝒜|=40`, `|ℛ|=59` live. | `EXECUTED` |
| 6. Computable? | Ten yes; eleven transitively blocked; five are class-C themselves. | `EXECUTED` |
| 7. Actually primitive? | Step 253's own verdict — *not established*. This session concurs and adds that `Entity` is not primitive-*independent*: `Dimension` and `Value` are defined relative to it, making `Entity` the root of the structural sub-graph. | `DERIVED` |

### 5.1 A gap the corpus does not list: `Context`

`c` appears in every assertion (`A = (id,P,e,c,t,Π)`) and in the evidence relation
(`Evidence(O,P,C,R)`). This session searched for a **type** for it. There is none: no domain, no
equality, no ordering, no composition rule, no relation to `bounded_context` in the EKP.

**Finding ON-4 (`UNRESOLVED`, HIGH).** `Context` is an untyped free variable occurring in the two
most load-bearing definitions in the theory. Step 266's audit table does not contain a row for it.
Because `Evidence(O,P,C,R)` is *the* qualification rule (ARC C), an untyped `C` means the rule is
parameterised by an object with no identity criterion — so `Evidence(O,P,C₁,R) = Evidence(O,P,C₂,R)?`
has no answer.

---

## 6. The dependency graph, drawn

```
        Entity ──▶ Dimension ──▶ Value                Source   Time
           └────────────┬───────────┘                    └──┬───┘
                        ▼                                   ▼
                   Proposition                          Provenance          Context(?)
                        │                                   │                   │
                        └───────────────┬───────────────────┴───────────────────┘
                                        ▼
                 ┌──────────────────  Assertion  ◀─────────────────┐
                 │                      │  ▲                        │
                 │                      ▼  │                        │
                 │                     Evidence                  Authority
                 │                      │                           ▲
       Relation ─┤                      ▼                           │
       RelationType                    Rule ──────▶ Policy ─────────┘
                 │                                    │
                 ▼                                    ▼
                 K ──▶ Operation ──▶ T_algebra ──▶ Equivalence ──▶ K*  ──▶ Minimality
                 │        ▲
                 └──▶ History

        ══ dashed region = the 7-node cycle ══      ══ shaded = class-C blocked ══
```

---

## 7. Findings

| ID | Finding | Class | Severity |
|---|---|---|---|
| **ON-1** | The definitional graph contains a 7-node cycle `Assertion → Evidence → Rule → Policy → Authority → Assertion`. It is broken only by Step 187's **normative stipulation** that authority is exogenous, whose own `B` (recorded basis) re-enters the cycle at the representation level. | `DERIVED` | **CRITICAL** |
| **ON-2** | Propagating Step 266's class-C set through the graph blocks **11 of 26** objects, `K`, `T`, `≡`, `K*` and `History` among them. Step 266 states the propagation principle and never applies it. | `EXECUTED` | **CRITICAL** |
| **ON-3** | Step 254's removal test, executed: all eleven candidate primitives are load-bearing for `K`; none is redundant. `Entity` is the structural root. | `EXECUTED` | MEDIUM (positive result) |
| **ON-4** | `Context` occurs in both the assertion tuple and the evidence relation and has **no type, no domain and no equality** anywhere in the corpus. It has no row in Step 266's audit. | `UNRESOLVED` | HIGH |
| **ON-5** | Splitting `A` into `A_struct = (id,P,c,t,Π)` plus a qualification layer `(e, σ)` puts `K_struct` inside the computable core, and `K_struct` is precisely what the EKP already runs. | `PROPOSED` | — |

---

**Next:** `04-K-GAP-ANALYSIS.md`.
