# Phase-2 Software Architecture — Decision Log

| | |
|---|---|
| **Companion to** | `STEP2-PHASE2-SOFTWARE-ARCHITECTURE-REVIEW.md` |
| **Date** | 2026-09-22 |
| **Status** | ⚠️ **PROPOSALS — no edit applied.** Protocol §§3A–3B unmodified |
| **Classification** | `EVIDENCE-SUPPORTED` · `METHODOLOGICAL-DERIVATION` · `DESIGN-PROPOSAL` · `OPEN` |

> **Classification key.** `EVIDENCE-SUPPORTED` = follows from an observation in F0001–F0025 or from the Step-1 registries. `METHODOLOGICAL-DERIVATION` = follows from a protocol rule already adopted. `DESIGN-PROPOSAL` = sound practice, not forced by this project's evidence. `OPEN` = cannot be decided yet.

---

## Part A — Decision classification

| # | Decision | Class | Provenance |
|---|---|---|---|
| **D-01** | The domain is **Theory Reconstruction & Construction**; KnowledgeOS theory is an aggregate **within** it | ⭐ **EVIDENCE-SUPPORTED** | Every invariant the system must hold (`I-1`…`I-16`) is a research-process rule. **Zero** are KnowledgeOS-theory rules — the theory's own invariants are unknown and under investigation (`S-1`'s `bar` undefined; `HA-0007` unresolved) |
| **D-02** | Repositories are **driven ports**; storage is **adapter only** | METHODOLOGICAL-DERIVATION | Standard Hexagonal; §3B.1's own dependency rule already implies it |
| **D-03** | LLM/ML is a **driven port** returning `Proposal`, never `Assertion` | ⭐ **EVIDENCE-SUPPORTED** | `Q28` forbids theory in software; `I-14` forbids confidence = authority. Type-level enforcement is the only reliable mechanism |
| **D-04** | ⛔ Remove `type` from `TheoryObject`; classification becomes an **evidence-backed assertion** | ⭐ **EVIDENCE-SUPPORTED** | `F0020` classifies `D-5` as "a knowledge kind, NOT a bounded context", `D-7` as "a context TYPE, not a context", and **splits** `D-6`; `F0024` then **changes** several. `T-0011` is `UNCERTAIN`. Classification here is contested, revisable, historical |
| **D-05** | *"Unknown"* is represented by **zero classification assertions**, not by an `UNKNOWN` value | ⭐ **EVIDENCE-SUPPORTED** | Step-1 §25: `NOT_SEARCHED` ≠ `ABSENT_BY_CONTENT`. A sentinel conflates "nobody classified it" with "we cannot tell" |
| **D-06** | Add **`SearchRecord`** as a primitive | ⭐ **EVIDENCE-SUPPORTED** | Step-1 §37 check 13 requires `ABSENT_BY_CONTENT` to be **earned by an actual search**. Without the record the four absence states collapse |
| **D-07** | Add **`Assertion`** — closed structure, open vocabulary | METHODOLOGICAL-DERIVATION | §4A's eleven emergent kinds must not require eleven classes, nor an untyped "anything" object |
| **D-08** | **`Maturity` is computed, never stored** | ⭐ **EVIDENCE-SUPPORTED** | `C-0007`: *"0 traversals"* appears in **7 files** and is false. `Q22` forbids repetition raising maturity; a writable field defeats it structurally |
| **D-09** | **`Provenance`** demoted to value object + global invariant | METHODOLOGICAL-DERIVATION | §14 requires provenance **carried by** every item; a peer primitive permits detached provenance |
| **D-10** | **`TheoryThread`** demoted to a **projection** | ⭐ **EVIDENCE-SUPPORTED** | Dry run: threads cover **12 of 23** theory objects and **0 of 10** architecture objects. §0.1b already concedes this; §3B.3 contradicts it |
| **D-11** | Split **`Verification`** into `VerificationRun` (service) and `Finding` (record) | METHODOLOGICAL-DERIVATION | §10.1's finding schema is a record; §10.2's modes are an activity |
| **D-12** | **`Derivation` is n-ary**; premises individually addressable | ⭐ **EVIDENCE-SUPPORTED** | `T-0016`: *"two of four grounds falsified"* (`DI-0009`, `F0024`). A binary edge cannot express partial falsification |
| **D-13** | **`Competition`** as its own record | ⭐ **EVIDENCE-SUPPORTED** | §6.2 requires two claims + discriminator + one of four outcome classes — more than an edge |
| **D-14** | Four bounded contexts | METHODOLOGICAL-DERIVATION | Historical / candidate / validated have **different invariants** (immutable · mutable · demote-only) |
| **D-15** | ⛔ **Provenance is NOT a bounded context** | DESIGN-PROPOSAL | No independent lifecycle; cross-cutting |
| **D-16** | ⛔ **Architecture Alignment is NOT yet a context** | ⭐ **EVIDENCE-SUPPORTED** | `reference-alignment/` is empty because **no Reference Architecture exists** (Step-1 §49 item 1). `ES-005.2` forbids speculative structure |
| **D-17** | ⛔ **No class for canonical theory anywhere** | METHODOLOGICAL-DERIVATION | `I-4`: the safest representation of "unreachable" is **absent from the model** |
| **D-18** | **`I-5a` measurement ≠ selection**, enforced by **absence of a ranking API** | ⭐ **EVIDENCE-SUPPORTED** | The window supplies the trap: a rival with more evidence **and** more contradictions vs one with less of both. Any scoring function resolves it silently |
| **D-19** | **JSONL for the pilot** | ⭐ **EVIDENCE-SUPPORTED** | 193 rows; append-only matches `I-11`/`I-12`; audit trail becomes the VCS; Step 1 is already JSONL |
| **D-20** | Package structure by **bounded context**, not technical layer | DESIGN-PROPOSAL | Contexts are the stable axis; layers are an implementation detail |
| **D-21** | **Iterative 2A↔2B loop**, not a sequence | METHODOLOGICAL-DERIVATION | §3A requires programming *during* Phase 2; a strict sequence contradicts it |
| **D-22** | Seven-class comparison ladder replaces "engine should match" | METHODOLOGICAL-DERIVATION | Identity is `HUMAN-JUDGEMENT` (§16); demanding a match there would make correct expert behaviour an engine defect |
| **D-23** | Seven-condition freeze, **no numeric score** | METHODOLOGICAL-DERIVATION | ES-006.1: never promote from a single occurrence |
| **D-24** | ACLs for P3A / LLM / ML / external / Step-1 | ⭐ **EVIDENCE-SUPPORTED** | Step-1 §1: *P3A ≠ truth* — vindicated, P3A wrong in **7 of 25** with a uniform mechanism |
| **D-25** | `Assertion` family shape | ⭐ **OPEN** | `OA-1` — depends on how many `kind` values the pilot exercises |
| **D-26** | `Gap` / `Obligation` unification | ⭐ **OPEN** | `OA-2` — opposite temporal direction; 27 rows too few |
| **D-27** | `Evidence` entity vs value object | ⭐ **OPEN** | `OA-3` |
| **D-28** | Hyperedges vs n-ary premises | ⭐ **OPEN** | `OA-4` — 14 instances, none yet needs more |
| **D-29** | Repository granularity per context | ⭐ **OPEN** | `OA-5` |
| **D-30** | Package names | ⭐ **OPEN** | §3B.5 correctly leaves this open |

**Totals:** 13 `EVIDENCE-SUPPORTED` · 9 `METHODOLOGICAL-DERIVATION` · 2 `DESIGN-PROPOSAL` · 6 `OPEN`.

> ⚠️ **Only 2 decisions are pure design preference.** The rest are forced by evidence or by rules already adopted — which is the intended ratio for a system that must not let architecture decide theory.

---

## Part B — Proposed change list

⛔ **None applied. Awaiting explicit authorization.**

### C-ARCH-01
**Severity:** `BLOCKING`
**Current section:** §3B.1
**Problem:** *"KnowledgeOS theory is the domain"* is wrong at this stage and **contradicts §3B.2**, which forbids theory concepts in the domain.
**Evidence:** All 16 system invariants are research-process rules; none is a theory rule. Under §3B.1 a theory revision becomes a software rewrite; under the correction it is a data change.
**Proposed correction:** Replace with — *"The domain is **Theory Reconstruction and Theory Construction**. KnowledgeOS theory is an **aggregate within** that domain: a research outcome, mutable and epistemically graded. Reconstruction and theory construction are its **subject matter**, not application capabilities over a known theory. Persistence, LLM/ML, files and external tools are adapters."*
**Why:** Removes the contradiction and makes theory-neutrality structural rather than aspirational.
**Dependencies:** none — must land **first**; C-ARCH-02…05 depend on it.
**Test required:** `I-4` — no L5 type exists; `REPRESENTATIONAL_ADEQUACY_TEST` on ≥ 10 statements.

### C-ARCH-02
**Severity:** `BLOCKING`
**Current section:** §3B.1 diagram
**Problem:** `DOMAIN ←→ PORTS Repository · Evidence · Inference · Validation · Storage` collapses four categories. Repositories and inference are **driven ports**; storage is an **adapter**; evidence is a **domain concept**; validation **splits three ways**.
**Evidence:** Implementing the diagram literally puts repositories in the domain, inverting the dependency rule the same section states.
**Proposed correction:** Replace with the corrected hexagon (review §8), with the explicit rule `adapters → ports → application → domain` and *"the domain imports nothing."*
**Why:** The diagram is the part an implementer copies.
**Dependencies:** C-ARCH-01.
**Test required:** static import check — `domain/` imports no port or adapter type (`I-…`/`Q31`).

### C-ARCH-03
**Severity:** `BLOCKING`
**Current section:** §3B.2
**Problem:** `TheoryObject.type = UNKNOWN` presupposes one type, a closed set, and classification as a property rather than an assertion.
**Evidence:** `F0020` gives `D-5`, `D-7`, `D-6` three different non-simple classifications; `F0024` changes several; `T-0011` is `UNCERTAIN`.
**Proposed correction:** Remove `type`. Add `ClassificationAssertion` (subject · scheme · value · basis · asserted_by · epistemic_level · confidence · provenance · superseded_by). *"Unknown"* = **zero assertions**.
**Why:** The current field cannot represent contested, multiple, historical or evidence-graded classification — all of which the corpus already contains.
**Dependencies:** C-ARCH-09 (`Assertion`).
**Test required:** an object with zero classifications round-trips; two competing classifications coexist.

### C-ARCH-04
**Severity:** `BLOCKING`
**Current section:** §3B.3
**Problem:** `Maturity` as a stored primitive can be written directly, defeating `Q22`.
**Evidence:** `C-0007` — a false claim attested in 7 files would be promoted by any corroboration-count path.
**Proposed correction:** `Maturity` is a **computed value object** produced by a policy over executed tests. No setter. Same for `evidence_strength`.
**Why:** `Q22` is unenforceable against a writable field.
**Dependencies:** none.
**Test required:** `I-15` — no setter exists; maturity cannot rise when only corroboration count rises.

### C-ARCH-05
**Severity:** `BLOCKING`
**Current section:** §3B.3
**Problem:** No primitive represents a **search**, so absence-of-evidence and evidence-of-absence collapse.
**Evidence:** Step-1 §37 check 13 requires `ABSENT_BY_CONTENT` to be earned by an actual search; the kernel has nowhere to record one.
**Proposed correction:** Add `SearchRecord` (scope · method · query · outcome · provenance). `ABSENT_BY_CONTENT` requires a linked record.
**Why:** The most consequential epistemic omission in the kernel.
**Dependencies:** none.
**Test required:** `I-16`.

### C-ARCH-06 … C-ARCH-16 *(REQUIRED — summary form)*

| # | Section | Problem | Correction | Test |
|---|---|---|---|---|
| **06** | §3B.3 vs §0.1b | `TheoryThread` a primitive in one section, demoted in the other | Projection over `Relationship` | recompute threads; no stored thread state |
| **07** | §3B.3 | `Provenance` as a peer primitive permits detached provenance | Value object + global invariant | `I-3`, `I-10` |
| **08** | §3B.3 | `Verification` conflates act and finding | Split `VerificationRun` / `Finding` | a finding exists without a run? → defect |
| **09** | §3B.2/3B.3 | 11 emergent kinds would need 11 classes | `Assertion`: closed structure, **registered** open vocabulary | all 11 kinds representable without a code change |
| **10** | §3B.3 | `Derivation` assumed binary | **n-ary**, premises individually addressable | represent *"two of four grounds falsified"* |
| **11** | §3B.3 | `Contradiction` → `Relationship(CONTRADICTS)` insufficient | `Competition` record: 2 claims + discriminator + outcome class | all 9 Step-1 contradictions representable |
| **12** | §3A.5 | *"engine should match"* too strong | Seven-class ladder; defect = divergence in classes 1–3 or a **false negative** in class 4 | run both; classify every divergence |
| **13** | §3B (absent) | No anti-corruption boundaries | ACLs; `CandidateGenerator` returns `Proposal`, not `Assertion` | `I-13`, `I-14` |
| **14** | §3B (absent) | Invariants not executable | 16 invariants as tests (review §13) | all pass |
| **15** | §3A.6 | Freeze condition insufficient | Seven conditions, two runs, no numeric score | all seven |
| **16** | §3A.1 | *"2A first, 2B second"* contradicts *"programming during Phase 2"* | Iterative loop (review §18) | adequacy test fires before 2A completes |

### C-ARCH-17 … C-ARCH-22 *(RECOMMENDED)*

JSONL for the pilot with stated reasons · package structure by bounded context · human-judgement classification made explicit · ports declared without technology · `Gap`/`Obligation` unification considered · `Evidence` reconsidered as a value object.

### C-ARCH-23 … C-ARCH-25 *(DEFERRED)*

Package names · `Architecture Alignment` context · graph-DB adapter.

---

## Part C — Minimal pilot (review §17 companion)

**Smallest thing that answers the eleven architecture questions.** ⛔ Not the KnowledgeOS system.

| Build | Skip |
|---|---|
| 10 domain types + `Assertion` + `SearchRecord` | every use case beyond ingest → construct → evolve |
| 2 application services: `IngestCorpus`, `RecordEvolution` | verification lenses, agenda, distance |
| 2 driven ports: `SourceReader`, `Repository` | LLM, ML, similarity |
| 1 adapter: JSONL | database, graph, API, UI |
| 16 invariants as executable tests | performance, scale, concurrency |

**Success = the eleven questions answered**, in particular: can `F0001–F0025` be represented · does provenance survive · can rivals coexist · can events order **without a false total order** · does Step 1 stay immutable · can a theory be split/replaced/rejected without schema corruption.

⭐ **If the pilot cannot represent the corpus, the finding is about the model — and the model changes before anything larger is built.**

---

*Traceability: Phase-2 software architecture decision log, 2026-09-22 · 30 decisions classified; 13 EVIDENCE-SUPPORTED · 9 METHODOLOGICAL-DERIVATION · 2 DESIGN-PROPOSAL · 6 OPEN · 25 proposed changes (5 BLOCKING · 11 REQUIRED · 6 RECOMMENDED · 3 DEFERRED) · ⛔ **no edit applied to the Step-2 protocol; no code written; nothing frozen; Phase 2 not executed** · edits await explicit authorization.*
