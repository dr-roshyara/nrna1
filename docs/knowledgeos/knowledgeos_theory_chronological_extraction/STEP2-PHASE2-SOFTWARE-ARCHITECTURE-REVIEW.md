# Phase-2 Software Architecture — Design Review

| | |
|---|---|
| **Subject** | `prompts/knowledge_os_step2_theory_construction_protocol.md` §§3A–3B (v2.3) |
| **Commission** | Design review — Principal Architect · Senior DDD · research methodology · mathematics/statistics · Hexagonal · epistemic systems · provenance |
| **Date** | 2026-09-22 |
| ⛔ **Not done** | Phase 2 **not executed** · **no code written** · **protocol NOT modified** · **nothing frozen** |
| **Result** | **5 BLOCKING · 11 REQUIRED · 6 RECOMMENDED · 3 DEFERRED** |

---

## 1. Executive verdict

> ## ⛔ **NOT YET CLEAN ENOUGH.** The protocol contains a **direct self-contradiction** at the exact point where theory-neutrality is supposed to be guaranteed.

§3B.1 declares *"KnowledgeOS theory is the domain."* §3B.2, eight lines later, declares the domain must contain **only** methodological concepts and **never** theory concepts. **Both cannot hold.**

This is not a wording slip. Taken literally, §3B.1 licenses exactly the failure the whole section exists to prevent: if KnowledgeOS theory is the domain, the domain model must encode what that theory *is* — and every theory revision becomes a software rewrite.

Four further blocking issues follow from the same root: a mislayered hexagon, a type sentinel that smuggles in an ontology, and a derived value stored as a primitive.

**None is hard to fix.** The architecture is close. But it must not be built in its current form.

---

## 2. What is already correct — and should be preserved

| | Why it is right |
|---|---|
| ⭐ **Programming during Phase 2** (§3A) | Correct, and now **four times evidenced** in this project. Executable checks caught what prose review did not, including a defect in a check's own specification |
| ⭐ **The kernel expressiveness test** (§3B.3) | **Methodologically exemplary** — the model was tested against real data *before* code and found insufficient. Keep this as standing practice |
| ⭐ **`BaselineComparison` moved to an adapter** | Correct and non-obvious. P3A is an external system; comparing to it is an adapter concern |
| ⭐ **The firewall diagram** (§3B.6) | *"Software supports the research; it must never silently decide it"* — the right invariant, correctly stated |
| ⭐ **Package names deliberately unfrozen** (§3B.5) | Correct restraint, with the right justification |
| **The `Relationship` and `Event` findings** | Both correct. See §8 — they do not go far enough, but they are right |
| **`Q28`–`Q31`** | Sound as far as they go |

---

## 3. Blocking issues

### C-ARCH-01 · `BLOCKING` · The domain is misidentified

**Current (§3B.1):** *"KnowledgeOS theory is the domain. Reconstruction and theory construction are application capabilities."*

**Problem.** This is **wrong at this research stage**, and it contradicts §3B.2 directly.

**The decisive test — what are the invariants?** A DDD domain is identified by the rules that must always hold. In this system those are:

```
provenance is complete · Step-1 is immutable · L5 is unreachable
competing formulations coexist · failed tests do not disappear
```

**Every one is a research-process invariant.** None is a KnowledgeOS-theory invariant. The theory's own invariants are *unknown and under investigation* — that is the entire point of Phase 2.

**Second test — what changes when the theory changes?** Under §3B.1, a theory revision is a **domain model change**, i.e. a software rewrite. Under the corrected reading, a theory revision is **a data change**: new assertions, a new `TheoryEvolution` record. ⭐ *That difference is the whole architecture.*

**Verdict: the user's proposed correction is not merely preferable — it is architecturally necessary.**

> ### ✅ **The domain is `THEORY RECONSTRUCTION AND THEORY CONSTRUCTION`.**
> ### ⭐ **KnowledgeOS theory is an *aggregate within* that domain — a research outcome, mutable, epistemically graded — never the domain itself.**

**Dependency:** everything in §§5–8 of this review depends on this correction landing first.

### C-ARCH-02 · `BLOCKING` · The hexagon is mislayered

**Current (§3B.1):** `DOMAIN ←→ PORTS  Repository · Evidence · Inference · Validation · Storage`

**Problem.** Four distinct architectural categories are collapsed into one row:

| Item | Listed as | Actually is |
|---|---|---|
| `Repository` | domain/port | ⛔ **driven port** (interface owned by the core) |
| `Storage` | domain/port | ⛔ **adapter only** — not a port at all |
| `Evidence` | domain/port | ⛔ **domain entity/value** — not a port |
| `Inference` | domain/port | ⛔ **driven port**, never domain logic |
| `Validation` | domain/port | ⛔ **splits three ways** — see §5 |

A reader implementing this diagram would put repositories in the domain and evidence behind an interface — inverting the dependency rule the same section states.

### C-ARCH-03 · `BLOCKING` · `type = UNKNOWN` hides an ontology assumption

**Current (§3B.2):** *"a `TheoryObject` starts `type = UNKNOWN`, later becomes `CONCEPT`…"*

**Problem.** The sentinel presupposes three things the research has not established:

1. that an object has **exactly one** type;
2. that types come from a **closed set**;
3. that classification is a **property of the object** rather than an **assertion about it**, made by someone, on evidence, at a time, possibly contested.

**The corpus already falsifies all three.** `F0020` classifies `D-5` as *"a knowledge kind, NOT a bounded context"*; `D-7` as *"a context TYPE, not a context"*; `D-6` **splits** into two. `F0024` then **changes** several of those classifications. `T-0011`'s identity is recorded `UNCERTAIN`. **Classification in this corpus is contested, evidence-backed, revisable and historical.** A single mutable enum field can represent none of that.

⚠️ And `UNKNOWN` is epistemically wrong in a subtler way: it conflates *"nobody has classified this"* with *"we looked and cannot tell"* — the same absence-of-evidence vs evidence-of-absence error Step-1 §25 exists to prevent.

### C-ARCH-04 · `BLOCKING` · `Maturity` as a stored primitive defeats `Q22`

**Problem.** `Maturity` is listed as a kernel primitive. A stored field can be **written directly**. `Q22` forbids maturity rising by repetition or corpus volume — but nothing structural prevents `obj.maturity = M3`.

⭐ **`C-0007` is the live danger:** *"0 traversals"* appears in **7 files** and is **false**. Any code path that increments maturity on corroboration count would promote it.

**Maturity must be a computed value object produced by a policy over executed tests — never a writable field.** Same for `evidence_strength` and any confidence figure.

### C-ARCH-05 · `BLOCKING` · Absence-of-evidence is unrepresentable

**Problem.** The corrected kernel has **no primitive for a search**. Step 1 already distinguishes four absence states — `ABSENT_BY_CONTENT` / `NOT_SEARCHED` / `OUT_OF_WINDOW` / `UNCERTAIN` — and Step-1 §37 check 13 enforces that `ABSENT_BY_CONTENT` must be **earned by an actual search**.

Without a `SearchRecord` primitive (scope · method · query · outcome · provenance), *"we looked and found nothing"* and *"we never looked"* **collapse into the same record**. The 34 % test did not catch this because absence records are annotations in Step-1, not rows.

> ⛔ This is the single most consequential *epistemic* omission in the kernel.

---

## 4. Required changes

| # | Issue | Correction |
|---|---|---|
| **C-ARCH-06** | `TheoryThread` listed as a kernel primitive, but §0.1b already demoted it (threads cover 52 % of objects, 0 % of architecture objects) — **§3B.3 and §0.1b contradict each other** | **Demote to a projection/view over `Relationship`**, not an entity. Groupings are hypotheses; they must be cheap to create, merge, split and discard |
| **C-ARCH-07** | `Provenance` listed as a primitive alongside `TheoryObject` | **Demote to a value object + a global invariant.** A primitive invites detached "provenance objects"; provenance must be *carried by* every assertion, not stored beside it |
| **C-ARCH-08** | `Verification` is ambiguous — the *act* vs the *finding* | **Split:** `VerificationRun` (application service) and `Finding` (domain record). Conflating them makes "verified" unfalsifiable |
| **C-ARCH-09** | No `Assertion` primitive; the 11 emergent kinds would each need a class | **Introduce `Assertion`** — see §7. Closed structure, open vocabulary |
| **C-ARCH-10** | `Relationship` assumed binary; `Derivation` has **multiple premises** | **`Derivation` is n-ary** and cannot collapse into a binary edge. Either keep it separate or model hyperedges explicitly |
| **C-ARCH-11** | Contradictions modelled as `Relationship(CONTRADICTS)` | Insufficient: a contradiction carries **two claims + a discriminator + an outcome class**. Needs a `Competition` record referencing the edge |
| **C-ARCH-12** | "Engine should match" (§3A.5) is too strong | Replace with the seven-class ladder — §12 |
| **C-ARCH-13** | No anti-corruption boundaries | Required — §16 |
| **C-ARCH-14** | No architectural invariants expressed as tests | Required — §13 |
| **C-ARCH-15** | Freeze condition insufficient | Required — §19 |
| **C-ARCH-16** | 2A→2B stated as a sequence | Replace with the iterative loop — §18 |

---

## 5. Recommended

`C-ARCH-17` persistence: JSONL for the pilot, with justification (§17) · `C-ARCH-18` package structure by **bounded context**, not technical layer (§15) · `C-ARCH-19` human-judgement classification made explicit (§11) · `C-ARCH-20` port list without technology commitment (§14) · `C-ARCH-21` `Obligation` and `Gap` unified under an open-item abstraction · `C-ARCH-22` `Evidence` reconsidered as a value object (a locator + quotation), not an entity.

---

## 6. Deferred

`C-ARCH-23` package names (§3B.5 correctly leaves open) · `C-ARCH-24` whether `Architecture Alignment` becomes a context (premature — its directory is empty because no Reference Architecture exists) · `C-ARCH-25` graph-DB adapter (no pilot need).

---

## 7. Theory-neutral domain model

### 7.1 The corrected primitive set

| Primitive | Classification | Kind | Note |
|---|---|---|---|
| `Source` | CORE-METHODOLOGICAL | Entity | a corpus file; identity = registry id |
| ⭐ `Event` | CORE-METHODOLOGICAL | Entity | **distinct from `Source`** — 8 of 25 files carry >1 |
| `Evidence` | CORE-METHODOLOGICAL | **Value object** | locator + quotation; has no life of its own |
| ⭐ `Assertion` | CORE-METHODOLOGICAL | Entity | **the openness mechanism** — §7.3 |
| `TheoryObject` | RESEARCH-DOMAIN | **Aggregate root** | ⛔ carries **no** `type` field — §7.2 |
| ⭐ `Relationship` | CORE-METHODOLOGICAL | Entity | must be an entity: it evolves and carries provenance |
| `Derivation` | RESEARCH-DOMAIN | Entity (**n-ary**) | premises → inference → conclusion |
| ⭐ `SearchRecord` | CORE-METHODOLOGICAL | Entity | **absence-of-evidence** — C-ARCH-05 |
| ⭐ `Competition` | RESEARCH-DOMAIN | Entity | rival formulations + discriminator + outcome |
| `Gap` / `Obligation` | CORE-METHODOLOGICAL | Entity (`OpenItem`) | forward-looking; candidate for its own context |
| `Finding` | RESEARCH-DOMAIN | Record | verification output |
| `TheoryEvolution` | CORE-METHODOLOGICAL | **Append-only event** | |
| `Provenance` | — | **Value object + invariant** | ⛔ **not** a primitive |
| `Maturity` | — | **Computed value object** | ⛔ **never writable** |
| `TheoryThread` | — | **Projection** | ⛔ **not** an entity |
| `Verification` | — | **Application service** | split from `Finding` |
| `BaselineComparison` | ADAPTER CONCERN | — | correctly moved already |

### 7.2 Classification as assertion — replacing `type = UNKNOWN`

```
TheoryObject
  id
  ⛔ NO type field
  classifications: []   ← ClassificationAssertion, zero or many
```

```
ClassificationAssertion
  subject          the object
  scheme           which taxonomy (itself contestable)
  value            e.g. CONCEPT | MATHEMATICAL_STRUCTURE | ARCHITECTURE
  basis            the evidence
  asserted_by      expert | engine | source-file
  epistemic_level  L1..L4
  confidence
  provenance
  superseded_by    classification history
```

| Requirement | How it is met |
|---|---|
| *"we don't know what this is"* | ⭐ **zero classification assertions.** No sentinel needed — absence is the representation |
| multiple simultaneous | many assertions, different schemes |
| competing | two assertions + a `Competition` |
| history | `superseded_by` chain |
| confidence / epistemic status | fields on the assertion, not the object |

> ⭐ **The object never claims what it is. Others assert it, on evidence, revisably.** That is what theory-neutral actually requires.

### 7.3 `Assertion` — structural openness with semantic discipline

```
Assertion
  id · kind · subject(s) · statement · basis(Evidence[])
  epistemic_level · asserted_by · provenance · superseded_by
```

**Closed structure** (fixed fields, provenance mandatory) + **open vocabulary** (`kind` drawn from a **registered term list**, each term carrying a definition and a first-use provenance).

All eleven emergent kinds are **values**, not classes:

`NEW_CONCEPT` `NEW_RELATIONSHIP` `NEW_STRUCTURE` `ABSTRACTION` `UNIFICATION` `SPECIALIZATION` `DECOMPOSITION` `COMPETING_INTERPRETATION` `REJECTED_INTERPRETATION` `EMERGENT_PRINCIPLE` `TAXONOMY_INADEQUATE`

> ⭐ **Adding a kind is a recorded research act, not a code change** — and `TAXONOMY_INADEQUATE` means *"the taxonomy is wrong"* is expressible **inside** the taxonomy. ⛔ This is not an "anything" object: every assertion is typed, provenance-carrying and epistemically graded.

---

## 8. Corrected Hexagonal Architecture

```
  DRIVING ADAPTERS      CLI · batch runner · (later API/UI)
          ↓ calls
  DRIVING PORTS         use-case interfaces
          ↓
  APPLICATION           orchestration only — no domain rules
                        IngestCorpus · BuildOrdering · ConstructTheory
                        RunVerification · AssessMaturity · ComputeDistance
                        RecordEvolution · CompareEngineWithExpert
          ↓ uses
  DOMAIN                entities · value objects · domain services · POLICIES
                        ⛔ imports nothing from ports or adapters
          ↓ declares
  DRIVEN PORTS          SourceReader · *Repository · CandidateGenerator
                        SimilarityScorer · Clock · IdGenerator · Exporter
          ↑ implemented by
  DRIVEN ADAPTERS       JSONL · (Postgres) · (graph) · LLM · ML · P3A reader
```

**Dependency rule:** `adapters → ports → application → domain`. **The domain imports nothing.**

| Question | Answer |
|---|---|
| Repositories? | **driven ports.** Interfaces in the core, implementations outside |
| Storage? | **adapter only.** Not a port, not a layer |
| LLM/ML? | ⛔ **driven port, never domain.** Output is a **proposal**, never an assertion — §16 |
| Validation? | **splits three ways:** domain *policies* (invariants) · application *services* (orchestrating lenses) · driven *ports* (theorem prover, external expert) |
| Provenance? | **domain semantics** (invariant + value object) + **adapter** (persistence). Never one thing |
| Graph DB? | persistence technology → **adapter** |
| CLI/API/UI? | **outside the core**, always |
| Orchestration? | **application services** |
| Domain invariants? | **domain** — and expressed as executable policies (§13) |

---

## 9. Bounded contexts — four justified, two rejected

The protocol requires that *historical truth · reconstructed · candidate · validated · canonical* never collapse. They have **different invariants**, which is a genuine boundary, not an aesthetic one.

| Context | Responsibility | Owns | In | Out | Invariants | ⛔ Must NOT know |
|---|---|---|---|---|---|---|
| **Historical Evidence** | hold the record as found | `Source` `Event` `Evidence` `SearchRecord` | Step-1 registries | evidence refs | ⛔ **immutable**; L0/L1 only | anything about theory or verification |
| **Theory Construction** | build and evolve candidates | `TheoryObject` `Assertion` `Relationship` `Derivation` `Competition` `TheoryEvolution` | evidence refs | candidates | L2–L4 only; **never L5**; rivals coexist | persistence, inference technology, agenda priority |
| **Verification & Testing** | establish, refute, leave open | `Finding` `Test` `TestResult` `AdversarialFraming` | seed items | findings, demotions | ⛔ may demote, **never promote**; may not edit a seed | how candidates were constructed |
| **Research Agenda** | what to do next | `OpenItem` (`Gap`/`Obligation`) `Priority` | findings, gaps | ranked agenda | governance items **never** scheduled as research | theory content |

**Rejected:**

| Candidate | Verdict |
|---|---|
| **Provenance** | ⛔ **not a context — a cross-cutting invariant.** It has no independent lifecycle. Making it a context invites detached provenance |
| **Architecture Alignment** | ⛔ **premature.** Its directory is empty because no Reference Architecture exists (Step-1 §49). Creating a context for an empty capability repeats the speculative-structure error `ES-005.2` forbids |

⭐ **Canonical theory has no class anywhere.** The safest representation of *"Phase 2 cannot canonicalize"* is that the concept is **absent from the model**.

---

## 10–12. Application services · Ports · Adapters

**Application services (orchestration only):** `IngestCorpus` · `BuildEventOrdering` · `AdmitNodes` · `ConstructTheory` · `RecordEmergent` · `RegisterCompetition` · `StageFormalization` · `RunVerification` · `AssessMaturity` · `ComputeDistance` · `RecordEvolution` · `CompareEngineWithExpert` · `ExportPackage`.

**Driven ports (no technology named):** `SourceReader` · `EvidenceRepository` · `TheoryObjectRepository` · `RelationshipRepository` · `AssertionRepository` · `ProvenanceQuery` · `SearchRecorder` · `CandidateGenerator` · `SimilarityScorer` · `TestRunner` · `ExternalValidator` · `Clock` · `IdGenerator` · `Exporter`.

**Driving ports:** one interface per use case.

**Adapters:** JSONL reader/writer (pilot) · P3A baseline reader · LLM candidate generator · ML scorer · CLI · batch runner. ⛔ **No database, vendor, model or embedding is chosen at this stage** (§14).

---

## 13. Architectural invariants — revised, and expressed as tests

The user's I-1…I-14 are sound. Revisions: **I-5 split** (measurement vs selection are different failures), **I-7 strengthened** (absence, not a sentinel), **I-15/I-16 added**.

| # | Invariant | Executable test |
|---|---|---|
| **I-1** | Step-1 artifacts immutable | run engine; `git diff ../` is empty |
| **I-2** | Step-2 is an overlay | every Step-2 record references Step-1 by id; **zero copies** |
| **I-3** | Every derived item has provenance | no item with an empty early chain |
| **I-4** | L5 unproducible | ⭐ **no L5 value exists in the type system** |
| **I-5a** | ⭐ **Measurement is not selection** | ⛔ **no API returns `best`, `top`, or a score-sorted list** |
| **I-5b** | ⭐ **Selection requires explicit authority** | promotion requires an `AuthorityAct` record with a human actor |
| **I-6** | Competing formulations coexist | two rivals persist across a full run |
| **I-7** | ⭐ Unknown classification representable **as absence** | an object with **zero** classifications round-trips |
| **I-8** | Event ≠ Source | a source with 3 events yields 3 order positions |
| **I-9** | Relationships first-class | a relationship carries provenance and a version |
| **I-10** | Provenance survives transformation | chain intact after every application service |
| **I-11** | Failed tests cannot disappear | `FAILED-TESTS` is append-only; deletion impossible |
| **I-12** | Evolution append-only | old formulation byte-identical after revision |
| **I-13** | External comparison ≠ domain truth | P3A enters only through an ACL, tagged `EXTERNAL` |
| **I-14** | ⭐ Machine confidence ≠ epistemic authority | a 0.99-confidence LLM output is still `L2, asserted_by=ENGINE` |
| **I-15** | ⭐ Maturity computed, never assigned | no setter exists |
| **I-16** | ⭐ Absence-of-evidence ≠ evidence-of-absence | `ABSENT_BY_CONTENT` requires a linked `SearchRecord` |

---

## 14. `REPRESENTATIONAL_ADEQUACY_TEST`

**Procedure.** Take a research statement made by 2A. Attempt to represent it completely. Any element that cannot be represented is classified — ⛔ **never automatically as a programming problem**:

`PROTOCOL DEFECT` (the rule is imprecise) · `DOMAIN MODEL DEFECT` (a primitive is missing) · `APPLICATION MODEL DEFECT` (no use case) · `ADAPTER DEFECT` (persistence only) · `HUMAN-JUDGEMENT CASE` (not automatable — correct as-is).

**Worked example** — *"`T-0016` was reformulated because two of four grounds were falsified by `F0024`."*

| Element | Representable? |
|---|---|
| old / new formulation | ✅ `TheoryEvolution` |
| evidence · triggering event | ✅ `Evidence` → `F0024`; `Event` |
| reason | ✅ `PARTIAL_FALSIFICATION` |
| **test** | ⚠️ **no test was run** — a human judgement. `HUMAN-JUDGEMENT CASE`, not a defect |
| epistemic transition | ✅ STRONG → MEDIUM |
| affected objects / threads | ✅ / ⚠️ threads are a projection (C-ARCH-06) — recompute, don't store |
| **"two of four grounds"** | ⛔ **DOMAIN MODEL DEFECT** — a derivation's premises must be **individually addressable** so two can be falsified while two stand. Confirms C-ARCH-10 (n-ary derivation) |
| provenance | ✅ |

> ⭐ **One run of this test on one sentence found a real missing capability.** Run it on **≥ 10 statements spanning all 8 emergent kinds** before any freeze, and repeat at every kernel change (§19).

---

## 15. `THEORY_NON_DETERMINATION_TEST`

**Better formulation than a single invariant** — the failure has two distinct modes, and both need naming:

```
I-5a  MEASUREMENT_IS_NOT_SELECTION
I-5b  SELECTION_REQUIRES_EXPLICIT_AUTHORITY
```

**Test.** Construct rivals A and B where **every** listed heuristic points to A: more records, more evidence refs, encountered first, higher similarity, fewer contradictions, more supporting documents, more recent, higher ML probability, easier to formalize. Then assert:

1. the engine **reports** all nine measurements;
2. ⛔ the engine exposes **no** `best()`, `top()`, `rank()` or score-ordered accessor;
3. neither rival's status changes;
4. promotion fails without an `AuthorityAct`.

⭐ **The architectural enforcement is the absence of the API.** You cannot accidentally select what the interface will not rank.

⚠️ **The window supplies the real trap:** a rival with *more evidence but more contradictions* versus one with *less evidence and none*. Any scoring function resolves that silently — and thereby becomes the theory.

---

## 16. Human / machine judgement boundary

| Operation | Class | Why |
|---|---|---|
| file ingestion | **AUTOMATABLE** | deterministic |
| provenance linking | **AUTOMATABLE** | mechanical |
| graph traversal | **AUTOMATABLE** | deterministic |
| candidate extraction | **MACHINE-ASSISTED** | engine proposes a **superset**; human prunes |
| semantic similarity | **MACHINE-ASSISTED** | ⛔ a score is **never** an identity |
| **identity determination** | ⛔ **HUMAN-JUDGEMENT** | ⭐ `TH-0003` was kept `UNCERTAIN` by judgement; a similarity function would have merged it |
| theory unification / splitting | ⛔ **HUMAN-JUDGEMENT** | requires §19A's six criteria **evidenced** |
| mathematical validity | **EXTERNAL-EXPERT** (later: prover port) | `S-1`'s `bar` is a definitional judgement |
| statistical validity | **EXTERNAL-EXPERT** | selection mechanism cannot be inferred from data alone |
| DDD context assignment | **HUMAN-JUDGEMENT** | `HA-0007` — *"no document can settle it"* |
| canonicalization | ⛔ **GOVERNANCE-ACT** | ⛔ **never available to Phase 2** |

### Anti-corruption boundaries (C-ARCH-13)

Everything below enters through an **ACL that re-tags, never trusts**:

| Source | Enters as | Enforcement |
|---|---|---|
| **P3A** | `EXTERNAL_BASELINE`, never evidence | adapter; cannot mint an `Assertion` |
| **LLM / ML output** | `PROPOSAL`, `asserted_by=ENGINE`, level L2 | ⛔ **the port's return type is `Proposal`, not `Assertion`** |
| **External research** | `EXTERNAL_CLAIM`, not Track-A | tagged at ingest |
| **Step-1 records** | `EVIDENCE_INPUT` — read-only | repository exposes **no write method** |

> ⭐ **Type-level enforcement beats policy.** If `CandidateGenerator` returns `Proposal` and only a human act converts a `Proposal` into an `Assertion`, `I-14` cannot be violated by accident.

---

## 17. Persistence — JSONL for the pilot

> ✅ **JSONL. Deliberately, and not as a compromise.**

| Reason | |
|---|---|
| The pilot tests the **model**, not performance | 193 rows. A database would test nothing the model needs tested |
| **Append-only matches the invariants** | `I-11` failed tests cannot disappear · `I-12` evolution append-only. JSONL makes deletion *unnatural*; a table makes it a one-liner |
| ⭐ **The audit trail becomes the VCS** | every state change is a git diff — reviewable by the same discipline as the protocol |
| Step 1 is already JSONL | no translation layer; `I-2` overlay is trivially checkable |
| A schema now would be premature | ⛔ committing DDL before representational adequacy is established inverts the method |

⚠️ **Condition:** the **port must not leak JSONL semantics** — no line numbers as identity, no file-order dependence. If the port is clean, Postgres or a graph DB is a later adapter swap. ⛔ **Do not choose a database because the eventual corpus is large** — that is a Phase-4 concern about an engine whose model is not yet validated.

---

## 18. Revised Phase-2A/2B execution model

> ⛔ **"2A first, then 2B" is insufficient.** It implies theory construction must finish before software design begins — contradicting §3A's own premise that programming happens *during* Phase 2.

```
methodology hypothesis
   ↓
expert reconstruction (2A)  ──────┐
   ↓                              │
minimal executable model (2B)     │
   ↓                              │
REPRESENTATIONAL_ADEQUACY_TEST    │  ⭐ can fire before 2A completes
   ↓                              │
comparison ───────────────────────┘
   ↓
methodology AND model correction      ⭐ both, never only the model
   ↓
second run
   ↓
freeze
```

**The firewall, stated precisely:**

| Layer | May | ⛔ May not |
|---|---|---|
| **research discovery** | propose anything | be constrained by what the schema can hold |
| **software representation** | hold, link, version, audit | decide what is true |
| **software inference** | ⭐ **propose** candidates | assert, promote, or select |
| **human judgement** | assert, identify, unify, split, abandon | canonicalize |
| **theory construction** | evolve freely within L2–L4 | reach L5 |

⭐ **The load-bearing line:** *software inference proposes; only human judgement asserts.* Enforced at type level (§16), not by policy.

---

## 19. Revised freeze condition

⛔ **No numerical score.** Seven conditions, each a pass/fail with evidence:

| # | Condition | Evidence required |
|---|---|---|
| **1** | **Model adequacy** | `REPRESENTATIONAL_ADEQUACY_TEST` on ≥ 10 statements spanning all 8 emergent kinds; every failure classified |
| **2** | **Provenance preservation** | `I-3`/`I-10` hold after every application service |
| **3** | **Deterministic reproducibility** | two runs, identical inputs, **byte-identical** deterministic outputs; non-deterministic outputs explicitly marked |
| **4** | **Theory non-determination** | §15 test passes, including the absent-API assertion |
| **5** | **Human-judgement boundary** | every §16 operation classified; no HUMAN-JUDGEMENT operation automated |
| **6** | **Adversarial test** | Phase H0 framing recorded **before** verification; an independent pass run; overlap measured |
| **7** | **Second-run stability** | after correction, a full re-run produces **no new BLOCKING** finding |

⭐ **All seven, on two runs.** One agreeing run is not validation — ES-006.1's rule against promoting from a single occurrence applies to this judgement too.

---

## 20. Open architectural questions

| # | Question | Why it cannot be settled now |
|---|---|---|
| **OA-1** | Is `Assertion` one entity or a family (classification / relational / emergent)? | Depends on how many `kind` values the pilot actually exercises |
| **OA-2** | Should `Gap` and `Obligation` unify under `OpenItem`? | They differ in direction (backward vs forward); 27 rows is too few to tell |
| **OA-3** | Is `Evidence` a value object or an entity? | Turns on whether the same quotation is ever cited by two claims with different meaning |
| **OA-4** | Does `Derivation` need general hyperedges or is n-ary premises enough? | 14 derivation instances; none yet needs more |
| **OA-5** | Do the four contexts share one repository or four? | Deliberately deferred — premature separation costs more than it saves at this size |
| **OA-6** | Can `TheoryThread` stay a projection at 3,000 files, or does performance force materialization? | ⛔ a Phase-4 question; **must not** drive the Phase-2 model |
| **OA-7** | Where does the human act enter — CLI, review file, or a driving port? | Affects `I-5b` enforcement |

---

*Traceability: Phase-2 software architecture design review, 2026-09-22 · reviewed §§3A–3B of Step-2 protocol v2.3 against 20 commissioned criteria · 5 BLOCKING · 11 REQUIRED · 6 RECOMMENDED · 3 DEFERRED · findings grounded in the executed Step-1 reconstruction of F0001–F0025 · ⛔ **DESIGN REVIEW ONLY — Phase 2 not executed · no code written · protocol NOT modified · nothing frozen** · proposed edits listed in `PHASE2-SOFTWARE-ARCHITECTURE-DECISION-LOG.md`, awaiting explicit authorization.*
