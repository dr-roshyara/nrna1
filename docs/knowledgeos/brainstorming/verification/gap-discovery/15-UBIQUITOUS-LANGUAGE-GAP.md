# 15 — Ubiquitous Language Audit (DDD)

**Mandate §20.** Independent audit. `verification/CANONICAL-UBIQUITOUS-LANGUAGE.md` and
`UL-CANONICAL-GLOSSARY.md` were **not read** for this document (INV-7); this table was built from the
primary corpus and the running implementation.

---

## 1. The audit table

| Term | Meaning(s) in the corpus | Mathematical type | DDD type | Implementation term | Conflicting meanings |
|---|---|---|---|---|---|
| **Knowledge** | (a) the whole field; (b) a JTB atom `(A,J,T_A)`; (c) a state `K`; (d) a capacity to select | none consistent | — | none | **3-way collision.** Step 253 §253.40 leaves it unresolved |
| **Knowledge State `K`** | 28 tuples across 7 mathematical kinds | contested | Aggregate? Read model? | the EKP graph | see `04` KG-1 |
| **`𝒦`** | (a) `(Ω,𝓕)` a measurable space; (b) `(K,C,T,E,A)` a meta-structure; (c) `(K_t,H_t)` | two unrelated | — | none | **collision on one glyph** |
| **Assertion** | `(id,P,e,c,t,Π)` | product type | **Entity** (has identity) | knowledge card | consistent |
| **Proposition** | `(E,D,V)` | product type | **Value Object** | the card's claim | consistent |
| **Claim** | used ~everywhere | **undefined** | — | — | **synonym of Assertion, never stated** |
| **Evidence** | (a) the relation `Evidence(O,P,C,R)`; (b) an object with strength; (c) `e`, a set of ids | relation *and* object | Value Object? Entity? | `code_refs`, `test_refs` | **relation/object collision** |
| **Observation** | primitive `(source,method,time)` | primitive | Value Object | — | consistent |
| **Assessment** | `(Evidence*, Policy) → σ` | function | **Domain Service** | — | consistent, unimplemented |
| **Status / `σ` / `Σ`** | ≥5 orthogonal axes in one word | should be a product of ≥5 | — | `status` + `authority` | **≥5-way collision** (`06` SG-2) |
| **Authority** | (a) `Actor×Action×Context×Time`; (b) a trust rank | relation *and* ordinal | Policy / Value Object | `authorities.yaml` **rank** | **collision: permission vs. trust** |
| **Authorization** | an act | event | **Domain Event** | — | conflated with Authority (`10` GR-2) |
| **Policy** | rules governing admission/assessment/transition | class C | **Policy / Specification** | the linter + schema | consistent, unformalized |
| **Rule** | element of a policy | — | Specification | a lint rule | consistent |
| **Invariant** | property across all states | predicate | Invariant | 16 lint rules | consistent |
| **Verdict** | used throughout | **undefined** | — | — | **undefined** |
| **Provenance `Π`** | (a) `(source,method,time)` of a claim; (b) ancestry | product type | Value Object | `owner`,`authority` | **collision with Lineage** |
| **Lineage** | reachability in `ℛ_der ∪ ℛ_ref` | derived relation | derived | `derived_from`, `GovernanceLineageGraph` | distinct from Provenance — but the words are swapped in several steps |
| **History `H`** | `(K₀,T₁…T_t)` | sequence | Event stream | git only | consistent, unimplemented |
| **Context `c` / `C`** | in `A` and in `Evidence(O,P,C,R)` | **untyped** | Bounded Context? | `bounded_context` | **untyped, and possibly two different things** (`03` ON-4) |
| **Determination** | *"the missing mathematical object"* (2026-08-25) | — | — | — | **0 occurrences in Steps 262–267** |
| **Zero** | the absent/unasked/unrepresented | a status set of 9 (`025d`) | Domain Service | — | reduced to 4 arms downstream (PF-1) |
| **Superseded** | a status | should be relational | — | `superseded_by` (**0 in use**) | status/relation collision |
| **Contested** | a status | a process state | should be an Aggregate | — | status/process collision (`06` SG-5) |
| **Conflicted** | a status | relational (needs ≥2) | — | — | status/relation collision |
| **Relevance** | a predicate in the qualification rule | class C | — | — | undefined procedure |
| **Regime** | a pluggable mathematical framework | — | Anti-corruption layer | — | **appears 2026-08-25, vanishes after 08-26** |

---

## 2. The mandate's two directions, both instantiated

### 2.1 One word, several DDD concepts

**UL-1 (`DERIVED`, CRITICAL).** `Status` is the worst case: five orthogonal facts (asked, evidence,
authority, supersession, validity) plus a process fact, in one word. `06` SG-2 shows a single enum
would need 96 values. **This is not a naming preference — it is a modelling error that a Ubiquitous
Language audit exists to catch, and it went uncaught for 270 steps** because the audits looked for
*inconsistent usage* rather than for *dimensional overload*.

**UL-2 (`DERIVED`, HIGH).** `Authority` names two different things: a **permission relation**
(Step 187, `Actor×Action×Context×Time`) and a **trust rank** (the EKP's `authorities.yaml`:
`authoritative > derived > generated > historical > provisional`). Step 267 §267.16 maps
`Governance status → authorities.yaml` as `IMPLEMENTED` — **mapping the theory's permission concept
onto the implementation's trust concept.** They are unrelated. This is a false implementation
correspondence, and it is the same kind of error as `11` PL-8.

**UL-3 (`DERIVED`, HIGH).** `Evidence` is used as both a **relation** (Closure-04's central result)
and an **object with strength and identity** (the `025c`/`025n` algebras). The terminal model keeps
neither — `e` is a set of bare ids.

### 2.2 Several words, one concept

**UL-4 (`DERIVED`, MEDIUM).** `Claim` and `Assertion`. `Claim` has no definition; the corpus should
retire the word rather than define it (`ES-005.4` — extend, do not duplicate).

**UL-5 (`DERIVED`, MEDIUM).** `Update`, `Revise`, `Revision`, `Recalculate`, `Derive`, `Transition`,
`Learn`, `Improve`, `T`, `T_t`, `T_K` — **eleven names** for the state transition (`09` TG-1). Some
may denote genuinely different operations; none is distinguished from the others anywhere.

---

## 3. DDD structural findings

**UL-6 (`DERIVED`, HIGH) — the aggregate boundary is never fixed.** Is `K` an **Aggregate** (one
consistency boundary, transactional) or a **read model** over an event stream? The corpus argues both:
Step 205 "Aggregate Derivation" treats `K` as an aggregate; Step 247's `𝒦=(K,H)` and the replay
machinery treat it as a projection of `H`. These have opposite consequences for concurrency
(Step 072), merge (`025l`) and invariant enforcement. **`04` KG-8's reframing resolves it: `K` is a
projection — a sufficient statistic of `H` relative to `𝒪` — and the aggregate reading is what
produced the tuple proliferation.**

**UL-7 (`DERIVED`, HIGH) — `ℛ` edges are not modelled as anything.** They are bare triples: no
identity, no provenance, no status, no time (`05` CS-3). In DDD terms they are neither Entities nor
Value Objects with a lifecycle, yet `exp_congruence` EXP-2 shows `ℛ_der` is the component that makes
the state sufficient. **The most load-bearing element of the model has no DDD classification at all.**

**UL-8 (`EXECUTED`, MEDIUM) — the implementation's language is cleaner than the theory's.** The EKP
distinguishes `status` (lifecycle) from `authority` (source trust) in its schema comments, names its
relations with an explicit `inverse` and `directed` flag per type
(`knowledge-relationships.yaml`), and pins each to a controlled vocabulary. On the two axes it has,
it is unambiguous. **The theory should adopt the implementation's vocabulary discipline, not the
reverse.**

---

## 4. The term that disappeared

**UL-9 (`EXECUTED`, HIGH).** `Regime` — the 2026-08-25 concept that measure theory, logic,
non-monotonic reasoning, causality and statistics are **pluggable frameworks over a common
substrate** — is the corpus's best strategic idea and the cleanest anti-corruption-layer boundary it
ever drew.

It appears on 2026-08-25, survives into 2026-08-26, and then **vanishes**. `RegimeReferences` never
appears in any of the 28 `K` tuples. Step 270 (2026-08-30 20:28) reinvents it without the word:
*"AssessmentPolicy as an external pluggable regime, while the KnowledgeOS core only defines the
structural interfaces and invariants."*

**A term that vanishes takes its distinctions with it.** This is the UL counterpart of `01` EV-A2 and
`14` FR-5: the corpus has no mechanism that notices a concept ceasing to be used.

---

## 5. Findings

| ID | Finding | Class | Severity |
|---|---|---|---|
| **UL-1** | `Status` overloads ≥5 orthogonal facts plus a process fact in one word. UL audits looked for inconsistent usage, not dimensional overload, so it went uncaught for 270 steps. | `DERIVED` | **CRITICAL** |
| **UL-2** | `Authority` names both a permission relation and a trust rank; Step 267 maps the theory's permission concept onto the implementation's trust concept and calls it IMPLEMENTED. | `DERIVED` | HIGH |
| **UL-3** | `Evidence` is used as both relation and object; the terminal model keeps neither. | `DERIVED` | HIGH |
| **UL-4** | `Claim` is undefined and is a synonym of `Assertion`; `Verdict` is undefined. | `UNRESOLVED` | MEDIUM |
| **UL-5** | Eleven names for one transition, none distinguished from the others. | `EXECUTED` | MEDIUM |
| **UL-6** | The aggregate boundary is never fixed: `K` is argued as both Aggregate and projection, with opposite consequences for concurrency, merge and invariants. | `DERIVED` | HIGH |
| **UL-7** | `ℛ` edges have no DDD classification, no identity, no provenance, no status — and are the load-bearing component for state sufficiency. | `DERIVED` | HIGH |
| **UL-8** | *Positive:* the implementation's vocabulary discipline (controlled vocabularies, typed relations with declared inverses, explicit orthogonality) is cleaner than the theory's. | `EXECUTED` | — |
| **UL-9** | `Regime` — the strongest strategic concept in the corpus — vanished after 2026-08-26 and was reinvented without the word on 2026-08-30. | `EXECUTED` | HIGH |

---

**Next:** `16-MASTER-GAP-REGISTER.md`.
