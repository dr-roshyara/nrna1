# Phase-2 Architecture — Authorized Changeset

| | |
|---|---|
| **Source** | `PHASE2-SOFTWARE-ARCHITECTURE-ADJUDICATION.md` |
| **Target** | `prompts/knowledge_os_step2_theory_construction_protocol.md` §§3A–3B |
| **Status** | ⚠️ **PROPOSED — not applied.** Awaiting explicit authorization |
| **Contents** | **13 edits** — only what survived adjudication (classes A and B, plus corrections this pass produced) |
| ⛔ **Excluded** | Everything classified `C`-only, `D`, `E` or `F` |

---

## E-01 · Domain correction

**Section** §3B.1 · **Severity** `BLOCKING` · **Class** `B · METHODOLOGICALLY-FORCED`

**Current**
> **KnowledgeOS theory is the domain. Reconstruction and theory construction are application capabilities. Persistence, LLM/ML, files and external tools are adapters.**

**Replacement**
> **The domain is THEORY RECONSTRUCTION AND THEORY CONSTRUCTION.** KnowledgeOS theory is an **aggregate within** that domain — a research outcome: mutable, epistemically graded, replaceable. ⛔ **It is not the domain.** Persistence, LLM/ML, files and external tools are adapters.

**Evidence basis** All five known system invariants (provenance completeness · Step-1 immutability · L5 unreachable · rivals coexist · failed tests persist) are research-process rules; **none** is a KnowledgeOS-theory claim. Under the current wording a theory revision is a domain-model change — a software rewrite — which contradicts the replacement requirement.

**Test** `I-4`; `THEORY_REPLACEMENT_TEST`. **Dependencies** none — **must land first**.

---

## E-02 · Dependency rule replaces diagram-as-law

**Section** §3B.1 diagram · **Severity** `BLOCKING` · **Class** `B` (rule) + `C` (taxonomy)

**Current** `DOMAIN ←→ PORTS   Repository · Evidence · Inference · Validation · Storage`

**Replacement** — state the **law**, mark the taxonomy revisable:

> **Two invariants, non-negotiable:**
> ```
> DOMAIN DEPENDS ON NO INFRASTRUCTURE OR TECHNOLOGY
> EXTERNAL CAPABILITIES ENTER THROUGH EXPLICIT PORTS
> ```
> **Dependency direction:** `adapters → ports → application → domain`. The domain imports nothing.
>
> ⚠️ **The port taxonomy below is one valid decomposition, not the only one — revisable:** repositories and inference are *driven ports*; storage is an *adapter*; evidence is a *domain concept*; validation splits into domain policy / application orchestration / external capability.

**Evidence basis** The current row collapses four categories; implemented literally it puts repositories in the domain, inverting the rule the same section states. ⛔ **But one exact diagram is not architectural law** — many decompositions satisfy the invariants.

**Test** static check: `domain/` imports no port or adapter type. **Dependencies** E-01.

---

## E-03 · Remove `type = UNKNOWN`; separate three kinds of classification

**Section** §3B.2 · **Severity** `BLOCKING` · **Class** `A` (rejection) + `C` (replacement)

**Current**
> **The mechanism that keeps it neutral:** a `TheoryObject` starts `type = UNKNOWN`, later becomes `CONCEPT`, later perhaps `MATHEMATICAL_STRUCTURE`…

**Replacement**
> ⛔ **`TheoryObject` carries no `type` field.** A single mutable enum presupposes one type, a closed set, and classification as an intrinsic property — **all three falsified by the corpus.**
>
> **Three kinds, never conflated:**
>
> | Kind | Example | Nature |
> |---|---|---|
> | **Structural identity** | *this record is a `Source`* | ⛔ intrinsic, not contestable |
> | **Interpretive classification** | *`T-0016` is a Proposition* | contestable, evidence-backed, revisable |
> | **Contested classification** | *`D-5` is a knowledge kind, **not** a bounded context* | two or more interpretive claims |
>
> *"We do not yet know what this is"* = ⭐ **zero interpretive classifications.** No sentinel.
>
> ⚠️ **The representation is REVERSIBLE** — a classification value-collection, classification-as-relationship, or a classification-assertion record all satisfy the requirement. **The pilot decides.**

**Evidence basis** `F0020` classifies `D-5` as *"a knowledge kind, NOT a bounded context"*, `D-7` as *"a context TYPE, not a context"*, and **splits** `D-6`; `F0024` then **changes** several. `T-0011` is `UNCERTAIN`.

**Test** an object with zero classifications round-trips; two competing classifications coexist. **Dependencies** none.

---

## E-04 · Maturity never writable — form left open

**Section** §3B.3 · **Severity** `BLOCKING` · **Class** `B` (rule) + `C` (form)

**Replacement**
> ⛔ **`Maturity` is never directly writable.** Whether it is a computed value object, a policy result, a derived assessment or a materialized read model is ⚠️ **open** — the maturity function is not yet specified. **The prohibition is forced; the mechanism is not.**
>
> The same applies to `evidence_strength` and every confidence figure.

**Evidence basis** `C-0007` — *"0 traversals"* is attested in **7 files** and is false. Any corroboration-count path would promote it. `Q22` is unenforceable against a setter.

**Test** `I-15`: no setter; maturity does not rise when only corroboration count rises. **Dependencies** none.

---

## E-05 · Absence distinction forced; its form open

**Section** §3B.3 · **Severity** `BLOCKING` · **Class** `A` (distinction) + `C` (form)

**Replacement**
> ⛔ **The model must distinguish `NOT_SEARCHED` · `SEARCHED_AND_NOT_FOUND` · `ABSENT_BY_CONTENT` · `OUT_OF_WINDOW` · `UNCERTAIN`.** Absence-of-evidence and evidence-of-absence must never collapse.
>
> ⚠️ **Form is open:** a dedicated `SearchRecord`, or `Event(kind=SEARCH)` reusing an existing primitive. **Whichever is chosen must carry** scope · method · query/criterion · execution time · actor · corpus state · outcome · provenance.

**Evidence basis** Step-1 §37 check 13 requires `ABSENT_BY_CONTENT` to be **earned by an actual search**; the kernel has nowhere to record one. ⛔ **But a new entity is not forced** — an existing primitive may suffice.

**Test** `I-16`. **Dependencies** none.

---

## E-06 · Derivation is n-ary

**Section** §3B.3 · **Severity** `REQUIRED` · **Class** ⭐ `A · EVIDENCE-FORCED`

**Replacement**
> **`Derivation` is n-ary and its premises are individually addressable**, so that some may be falsified while others stand.

**Evidence basis** ⭐ `T-0016`: *"two of four grounds falsified"* (`F0024`, `DI-0009`). **Unrepresentable with binary edges.** This is the single clearest evidence-forced modelling requirement in the corpus.

**Test** represent partial falsification of `T-0016` end to end. **Dependencies** none.

---

## E-07 · ⛔ Withdraw the thread-as-projection claim

**Section** §3B.3 · **Severity** `REQUIRED` · **Class** ⭐ `E · PREMATURE (self-correction)`

**Replacement**
> **`TheoryThread` remains a provisional research ENTITY.** ⛔ *An earlier proposal to demote it to a projection over relationships is **withdrawn — refuted by executed test**:*
>
> | Test | Result |
> |---|---|
> | Thread fields derivable from edges | **4 of 11** |
> | Thread members appearing as edge endpoints | ⛔⛔ **0 of 12** |
>
> ⛔ **Edges connect FILES (`F####`); thread members are OBJECTS (`T####`). There is no object-graph to project from.** And `identity_basis` is a **recorded human judgement** — the content that kept `TH-0003` `UNCERTAIN` where a similarity function would have merged it. A projection would delete it.

**Evidence basis** Executed against the real registries, 2026-09-22. **Test** `THEORY_MERGE_TEST`. **Dependencies** none.

---

## E-08 · Corrected invariants `I-4`, `I-5a`, `I-14`

**Section** §3B (new) · **Severity** `REQUIRED` · **Class** `B`

| # | Replacement | ⛔ Why the earlier form was wrong |
|---|---|---|
| **I-4** | **The Phase-2 bounded context has no operation capable of producing L5** | *"No L5 in the type system"* would block Phase-3/4 canonicalization. Constrain the **context**, not the global system |
| **I-5a** | **No ordering may silently carry epistemic standing.** Analytical ordering and research-priority ordering are **legitimate**; only **canonical selection** requires an `AuthorityAct` | Banning all ranking APIs would forbid *"similarity 0.82 vs 0.76"* and break the research agenda's prioritization |
| **I-14** | ⭐ **No machine-generated *inference* acquires epistemic authority by virtue of being machine-produced.** Six provenance classes: `SOURCE_ASSERTION` · `DETERMINISTIC_DERIVATION` · `MACHINE_PROPOSAL` · `HUMAN_ASSERTION` · `VALIDATED_FINDING` · `GOVERNANCE_ACT`. `asserted_by` mandatory and non-defaultable; a `MACHINE_PROPOSAL` cannot reach L3+ without a human assertion or validated finding referencing it | *"Software proposes; only humans assert"* is too restrictive — *"this file contains definition X"* is a deterministic extraction needing no human act |

**Test** each invariant as an executable check. **Dependencies** E-01.

---

## E-09 · Domain model ≠ record schema

**Section** §3B (new) · **Severity** `REQUIRED` · **Class** `B`

**Replacement**
> **For every proposed object, six questions are answered separately. Collapsing them is how a schema becomes an ontology.**
>
> `conceptual research object` · `domain role` · `identity` · `lifecycle` · `persistence representation` · `projection / read representation`
>
> ⛔ **A change at the persistence layer is never a change to the conceptual object.**

**Evidence basis** The architecture review moved from *conceptual primitive* straight to *entity / value object / aggregate root* — and produced two premature conclusions (thread-as-projection, `TheoryObject`-as-aggregate) that this discipline would have caught.

**Test** every primitive in the pilot answers all six. **Dependencies** none.

---

## E-10 · Four new architecture tests

**Section** §3B (new) · **Severity** `REQUIRED` · **Class** `B`

| Test | Requirement | Live case |
|---|---|---|
| ⭐ `EMERGENT_DISCOVERY_TEST` | a discovery unanticipated by Step 1, Step 2, the schema, the taxonomy or the architecture is representable **without modifying the software core** | `T-0023` — assembled from five files, none of which states the counter orders anything |
| ⭐ `THEORY_REPLACEMENT_TEST` | B replaces A **without** deleting A, mutating A into B, rewriting provenance, changing Step 1, or corrupting derivations. *A existed · had evidence · was rejected · B emerged later* all remain recoverable | `IFR-0012` — `F0020` stamped a falsification and left the body unchanged |
| ⭐ `THEORY_SPLIT_TEST` | `A → {B, C}` with A's identity preserved and split provenance recorded | `T-0015` — premise and conclusion refuted separately (`DI-0008`) |
| ⭐ `THEORY_MERGE_TEST` | both historical objects preserved, later relationship recorded; ⛔ **IDs never merged** | `TH-0003` — held `UNCERTAIN` to avoid a premature merge |

**Dependencies** E-01.

---

## E-11 · Reframe the 34 % result

**Section** §3B.3 · **Severity** `REQUIRED` · **Class** `B (self-correction)`

**Current** `⛔ **Coverage** | **34 %**` — presented as evidence of model inadequacy.

**Replacement**
> ⚠️ **An ADEQUACY DIAGNOSTIC for one candidate model — not a measure of architectural quality, and never to be quoted as one.**
>
> **Limits, stated:** the unit was **JSONL rows**, which are heterogeneous (one identifier-series row covers a whole series; one object row covers one object); several apparent gaps collapsed to a single missing primitive; the mapping was the reviewer's; and a richer use of existing primitives could have represented some of the 127.
>
> ⭐ **What it legitimately established: two real missing primitives — `Relationship` and `Event`.** That is the finding. The percentage is not.

**Also withdraw:** *"≥10 statements"* as a freeze criterion — arbitrary. Replace with **required case-type coverage**: boundary · contradiction · revision · missingness · multi-premise derivation · competing interpretation · emergent concept · abandoned formulation.

**Dependencies** none.

---

## E-12 · ⭐ The laboratory principle — and its hard consequence

**Section** §3B (new, leading) · **Severity** `REQUIRED` · **Class** `B`

**Replacement**
> ## **Phase-2 software is infrastructure for DISCOVERING the architecture of KnowledgeOS — not the architecture of KnowledgeOS.**
>
> It is a **Theory Laboratory**: an instrument, like a telescope, not the thing observed.
>
> ```
> Corpus → [ THEORY LAB ] → evidence · findings · experiments · candidate theories
>                              ↓
>                      DISCOVERED THEORY        ← the valuable output
>                              ↓
>                      FINAL KNOWLEDGEOS        ← a later phase
> ```
>
> ⭐ **The laboratory is disposable. The theory discovered through it is not.**
>
> ### The consequence that binds engineering
>
> ⛔ **If the code is disposable and the data is not, the DATA FORMAT MUST OUTLIVE THE CODE.**
>
> | Therefore | Rather than |
> |---|---|
> | self-describing, inspectable records | an ORM-shaped schema |
> | formats readable without the program | formats requiring the program |
> | fidelity and auditability | performance, reuse, elegance |
> | ⭐ a lab that can be thrown away without losing a finding | a lab whose rewrite loses the research |
>
> ⚠️ **This also lowers the cost of being wrong** about any `REVERSIBLE` decision — and raises the cost of over-engineering. **The correct architecture is the smallest one that satisfies the invariants.**

**Evidence basis** Follows from the user's framing plus `I-1`/`I-2` (Step 1 immutable, Step 2 an overlay): the overlay must remain interpretable after its producer is replaced.

**Test** delete the engine; every Phase-2 artifact remains readable and its provenance reconstructable. **Dependencies** E-01.

---

## E-13 · ⭐ The five-layer laboratory view — with one arrow inverted

**Section** §3B (new) · **Severity** `RECOMMENDED` · **Class** `C · REVERSIBLE`

**Replacement**
> **The conceptual view** (what the researcher sees):
>
> ```
> RESEARCHER            interpretation · judgement · theory construction
> EXPERIMENT WORKBENCH  hypotheses · experiments · comparisons · tests
> THEORY LAB CORE       evidence · relationships · derivations · gaps
>                       provenance · evolution · classifications
> EXPERIMENT ENGINES    mathematical · statistical · logical · ML · structural
> CORPUS / BASELINES    Step-1 evidence · source files · P3A · external research
> ```
>
> **The dependency view** (what the code must obey) — ⚠️ **the two are not the same, and one arrow inverts:**
>
> | Conceptual layer | Hexagonal role |
> |---|---|
> | Researcher | **driving actor** |
> | Experiment Workbench | **driving adapter + application services** |
> | Theory Lab Core | ⭐ **the domain** |
> | Experiment Engines | ⛔ **driven adapters** — the core *declares ports*; engines *implement* them |
> | Corpus / Baselines | **driven adapters**, behind ACLs |
>
> ⛔ **The stack diagram shows the Core depending downward on the Engines. It must not.** In dependency terms the arrow inverts: a mathematical or ML engine is an **external capability** the core calls through a port it owns. Otherwise a change of ML library becomes a change to the theory model — the failure §3B.1 exists to prevent.
>
> ⚠️ **Two of the boxes are not yet admissible:** `Competition` is ⛔ **PREMATURE** (it over-unifies three different corpus shapes) and `Assertion` is ⚠️ **OPEN** (3 of its 6 boundaries cannot be stated). **Both are held out of the Core pending the pilot.**

**Evidence basis** The five-layer picture is a sound conceptual view. The inversion is required by `E-02`. The two exclusions are adjudicated findings.

**Test** static import check on the Core. **Dependencies** E-02, adjudication §9.3–9.4.

---

## ⛔ Explicitly NOT in this changeset

| Excluded | Class | Why |
|---|---|---|
| `Assertion` as a primitive | `F · OPEN` | 3 of 6 boundaries unstatable; test **composition** (`Asserted` trait) instead |
| `Competition` entity | `E · PREMATURE` | over-unifies three shapes the corpus never calls competitions |
| `TheoryObject` as aggregate root | `E · PREMATURE` | no invariant requires it |
| Freezing four bounded contexts | `C` | only two are demonstrated by differing invariants |
| `SearchRecord` as a named entity | `C` | form deliberately left open in E-05 |
| `ClassificationAssertion` as *the* mechanism | `C` | rejection forced (E-03); replacement is not |
| JSONL as architectural truth | `C` | a **pilot decision**; the rule is *persistence must not leak through the port* |
| Package names and layout | `D` | unchanged |

---

*Traceability: authorized changeset, 2026-09-22 · 13 edits surviving adjudication (classes A and B plus this pass's corrections) · 8 proposals explicitly excluded · three self-corrections carried through (thread-projection withdrawn, `Assertion` deferred, 34 % reframed) · ⛔ **NOT APPLIED — protocol §§3A–3B unmodified; no code; Phase 2 not executed** · awaiting explicit authorization.*
