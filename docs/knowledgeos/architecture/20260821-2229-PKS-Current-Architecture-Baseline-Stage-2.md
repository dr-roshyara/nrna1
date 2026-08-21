# PKS Current Architecture Baseline — Stage 2 (Architecture Archaeology)

> **STATUS: PROPOSED · EVIDENCE-BASED · NON-AUTHORITATIVE · NOT ADOPTED.** Nothing here is frozen, adopted, or ratified. This document **describes what the PKS is today**, reconstructed from evidence in PKS's own language. It proposes no target architecture, no migration, no refactoring, no technology, no kernel, no KnowledgeOS mapping, and no EKS↔PKS comparison.
>
> **Deliverable:** Stage 2 / phase P2 of the approved EP-01 plan `docs/plans/20260821-2118-eks-pks-aip-current-architecture-landscape-knowledgeos-evolution-study-plan.md` (§5.2 PKS evidence inputs · §7 Stage 2 · §8 P2).
> **Identifier:** none minted. Per PMR-10 no new identifier is claimed; the governed PKS identifier series is itself a gap (**G-1**).
> **Placement:** instructed output location (binding) — `docs/knowledgeos/architecture/`. The `doc-placement.php` resolver returns `PENDING`/unruled for this KnowledgeOS-review-set location; the binding plan path governs.
> **Scope:** PKS only. ⛔ **No KnowledgeOS design · no EKS↔PKS integration · no shared-primitive extraction · no migration/replacement/refactoring proposal · no technology selection · no product/business decision.** The session STOPS after this document, awaiting Human Principal Architect review.
> **EKS/PKS identity ambiguity (AMENDMENT 4):** resolved here **from PKS evidence only** — never from EKS's meaning (see §24 U-02, §25 X-01).
>
> **ERRATUM (2026-08-21, HPA-authorized, P2 baseline review P2-F1):** `.claude/settings.json` re-measured at **19 deny / 22 ask / 8 allow** — matching the Runtime Adapter record's stated counts. X-07 withdrawn, U-12 closed, and the §11 / §15.3 / §22 #11 permission-adapter claims corrected. See `docs/knowledgeos/reviews/2026-08-21-KOS-EP01-P2-pks-baseline-hpa-review.md`.

**Method:** evidence-first archaeology. Every substantive claim carries an evidence tier (1–8) and a reality class (A–G). Measurements in this document were **executed in this session** against the working tree (tier 1 code inspection, tier 2 test/tree measurement), not copied from prior documents. Where a prior document and a direct measurement disagree, **both are recorded** and neither is silently reconciled.

---

## 0. How to read this document

### 0.1 Evidence hierarchy (tier 1 = strongest)

| Tier | Evidence |
|---|---|
| **1** | implementation / source code (measured in this session) |
| **2** | executable tests (tree measured in this session) |
| **3** | persisted / runtime records |
| **4** | accepted ADRs / governance decisions / PROMOTED artifacts |
| **5** | architecture documentation (non-promoted) |
| **6** | C4 / PUML / diagrams |
| **7** | brainstorming / transfer / legacy |
| **8** | historical conversation / context |

The PKS corpus itself declares a governing knowledge-sources hierarchy (AD-1 header, tier 4): *repository evidence = primary architectural evidence (highest) · governed decisions = binding baseline · external literature = advisory, commissioned only · engineering experience = interpretation, never authoritative by itself.*

### 0.2 Reality classification (mandatory, AMENDMENT 2 principle 8)

| Class | Meaning |
|---|---|
| **A** | IMPLEMENTED — running code and/or persisted records demonstrate it |
| **B** | IMPLEMENTED BUT IMPLICIT — the behaviour exists; the concept is not named or modelled anywhere |
| **C** | PARTIAL — some of it exists; the rest does not |
| **D** | WRONG-BOUNDARY / ARCHITECTURAL DEBT — it exists, and its placement or shape is a defect |
| **E** | MISSING — the concept is referenced but nothing implements it |
| **F** | CONTRADICTED — sources conflict and the conflict is unresolved |
| **G** | DOCUMENTED / HYPOTHESIS ONLY — documentation describes it; no implementation evidence |

⛔ **No B, C, or G finding in this document has been promoted to A.** Documentation describing a desired behaviour is never treated as evidence that it is implemented. In particular: the PROMOTED logical architecture (AD-1, C4-1) describes a **logical** system; its components are **not runtime components** unless tier-1 evidence shows them.

### 0.3 KnowledgeOS firewall

The proposed KnowledgeOS architecture (Review Set `00…07`, the kernel-extraction analyses, the Digitalization Robot, the KnowledgeOS proposal in `docs/knowledgeos/*`) is **quarantined**. It appears in this document **only** in the form: *"Not established by current PKS evidence."* No PKS finding is mapped into any proposed context, and no KnowledgeOS concept is used to fill a PKS evidence gap.

### 0.4 Evidence corpus (governed starting corpus §5.2, plus measured additions)

The plan's §5.2 list is the **governed starting corpus**, not exhaustive. This session additionally admitted, by role:

| Source | Role in this reconstruction |
|---|---|
| `docs/implementation/PKS_Phase_II_Methodology_Baseline_v1_2.md` | CURRENT/IMPLEMENTED — the governed SDM v1.2/EOP v1.2 baseline |
| `docs/implementation/PKS_Phase_II_M0…M8_*.md` | ACCEPTED ARCHITECTURE — strategic model (promoted M7/M8, accepted M0–M6) |
| `docs/implementation/PKS_Phase_IIB_AD1_Architecture_Definition.md` + review | ACCEPTED ARCHITECTURE — PROMOTED logical architecture |
| `docs/implementation/PKS_Phase_IIC_C4_Architecture_Views.md` + verification | ACCEPTED ARCHITECTURE — PROMOTED views |
| `docs/implementation/PKS_Phase_II*_review/record/*.md` (MCA, CDR, KBI, ER, AFV, CCP…) | ACCEPTED ARCHITECTURE / governance records |
| `docs/implementation/PKS_Phase_III_*.md` (EAD-1, Engineering Knowledge Model, Governance Runtime Adapter, Operational Validation Charter, Capability Catalog) | ACCEPTED ARCHITECTURE / observations |
| `docs/adr/PKS_ADR_001_*` | ACCEPTED ARCHITECTURE — ADR PROPOSED, ARB-approved w/ revisions, Authority Disposition PENDING |
| `docs/pks/*` (observations, process models, Stage-1A inputs) | TRANSFER/RECONSTRUCTION + observations |
| `docs/knowledge_tranfer/*` (what_is_pks_v0/v1, pks_progress, bootstrapping) | TRANSFER/RECONSTRUCTION |
| `docs/plans/20260728-1624-pks-strategic-modeling-plan.md` · `20260802-0015-pks-identifier-validation-capability-plan.md` | ACCEPTED ARCHITECTURE — approved plans |
| `scripts/lib/EngineeringKnowledge/**` · `scripts/{identifier-check,link-check,knowledge-lint,doc-placement,knowledge-graph}.php` · `.github/workflows/knowledge-lint.yml` · `.husky/*` · `composer.json` autoload | **CURRENT/IMPLEMENTED — tier 1 code, measured this session** |
| `architecture_legacy/ai_architecture/{pks,documentation/pks}/*` | BRAINSTORMING → EXCLUDED from implemented-state claims; used only as historical records of what was proposed vs what exists |
| `docs/knowledgeos/*`, `docs/implementation/Strategic_DDD_Discovery_…*` | KnowledgeOS proposal → **EXCLUDE from PKS evidence**; `Strategic_DDD_Discovery_…` Phase-I discovery package is PKS Phase I evidence |
| `docs/knowledgeos/reviews/*-KOS-EP01-P2-*` | the commissioning review — not PKS evidence, confirms the deliverable shape |

**Corpus-boundary admissions/exclusions are recorded as findings:** §24 U-02 (scripts/observations is KnowledgeOS-named and out of PKS corpus), §24 U-03 (Cohesion capability is in code but not in the catalog), §25 X-06 (git commits label the PKS capability layer "feat(knowledgeos)" while PKS docs own the scripts).

---

## 1. Executive Summary

**The PKS, as it exists on 2026-08-21, is a governed, human-and-AI-executed knowledge-governance system inside the PublicDigit repository: a large, meticulously-governed strategic model and document corpus, plus a small, real, implemented PHP capability layer that validates parts of that corpus.**

The single most important structural finding:

> **The PKS has no server, no database, no daemon. It is knowledge specifications (YAML + Markdown) plus a set of command-line validation programs. Its "runtime" is a human-and-AI governance process.**
>
> The PKS defines itself, in its own governing records: *"The PKS is software — ❌ No: it is **knowledge specifications** (YAML + Markdown)"* (`knowledge_tranfer/20260729_1812_pks_progress.md` §1.2, tier 7/5). AD-1 is "**deployment-neutral by construction**" (tier 4, PROMOTED).

The second finding is the sharpest gap between the documented system and the implemented one:

> **The PROMOTED logical architecture (two components AC-1 Knowledge Assessment, AC-2 Knowledge Projection) is NOT realized as runtime components. What is actually implemented is a different thing: a capability layer (`scripts/lib/EngineeringKnowledge/`) whose capabilities are realizations of the Phase III Capability Catalog (CAP-001/003/004/006), executed by CLI scripts, git hooks, and CI.**
>
> The governing model itself records this: whole-system conformance assessment, an AC-1 responsibility, is **"specified, not realized"** — *"nothing performs it, no role owns it"* (AD-1 §7, tier 4; T-17).

The third finding is the identity ambiguity that Stage 2 was commissioned to resolve from PKS evidence:

> **On PKS evidence alone, "PKS" names a product-specific engineering-knowledge domain whose strategic model is frozen and promoted. The corpus it governs is BIFURCATED (product-specific content + domain-free methodology), and whether it is ONE corpus or THREE is formally open (OQ-PKS-7). The PKS has no boundary claim over the EKS's mechanisms; whether EKS and PKS are the same system, different systems, or overlapping systems is UNRESOLVED by PKS evidence (§24 U-02).**

**What is genuinely strong (class A, tier 1–4):** a frozen, promoted strategic model with an unusually disciplined governance chain (review → Authority disposition → change control → promotion, each step separately owned); a closed verdict vocabulary enforced as an architectural boundary; a governed identity model with a per-kind register discipline; a capability layer with **real PHP implementations** (IdentifierIntegrity, ReferenceIntegrity, VocabularyIntegrity, Cohesion) wired into `knowledge-lint.php`, `link-check.php`, `identifier-check.php`, CI, and package scripts; and a demonstrated case that the governance changed real contributor behaviour (the Governance Runtime Adapter record).

**What is genuinely weak:** the implemented capability layer realises only a minority of the catalog (CAP-001/003/004/006; CAP-002 DEFERRED, CAP-005 Candidate); the whole-system conformance assessment is specified-but-absent; three concepts (Risk, Question, Exception record) are deliberately unallocated; two ownership vacuums are open; the identifier registers have no canonical enumeration (**G-1**) and a **curable** citation-damage (**G-10**: R-70/R-71 ambiguous after R-65/R-66); the `register` word carries three unresolved senses (**G-2**); the Implementation Boundary Contract IBC-1 does not exist as a repository artifact (**G-7**); and at least one governed artifact currently contains an **unresolved git merge-conflict block** (the Governance Runtime Adapter record — measured, tier 1).

**What is not established:** PKS's current C4 container model as a *runtime* topology (the C4 views are logical, with an explicit "containers carry no runtime semantics" rule); any timing/coupling/delivery mechanism (AD-1 §9.2 records the absence, Q-1); the location of the issuance trigger (Q-7); who owns the enclosed conformance capability (OQ-PKS-3); and the one-corpus/three-corpora question (OQ-PKS-7).

**Answer to the joining-architect test** — **Yes for the governance, Yes for the capability layer, No for the runtime.** From this document alone a Principal Architect could understand the governance chain and the four implemented capabilities well enough to run and extend them (§§8, 9, 16, 19). What they could **not** do is stand up a PKS "system": there is no deployment unit, and the domain model's boundaries are deliberately partial (two undefined regions, one candidate seam, one adjacent external domain).

---

## 2. System Purpose

### 2.1 Documented purpose — tier 4/5 · class **A** (the documents exist and say this; no implementation contradicts it)

The corrected, self-adopted definition (appears verbatim in `knowledge_tranfer/what_is_pks_v0.md`, `pks_progress.md`, and is echoed by AD-1):

> **"The PKS is a knowledge system that models engineering knowledge, governance, evidence, and their relationships."**

A primary capability, stated repeatedly: *"providing AI with deterministic guidance about which engineering artifacts to produce, when to produce them, where to store them, how to structure them, and how to verify them."*

The strategic model states the domain's existence sentence (M5 §8, tier 4):

> **"The PKS exists to keep engineering knowledge expressed (Decision, Term), governed (Rule, Invariant, Candidate, Charter grant), accountable (Ruling), judged (Verdict), and conformant to its own rules (Conformance) — because its primary collaborator has no memory and its decisions must outlive their sessions."**

### 2.2 What PKS is NOT (self-declared, tier 5/7)

| Claim | Source |
|---|---|
| NOT software | `pks_progress.md` §1.2: *"knowledge specifications (YAML + Markdown)"* |
| NOT a documentation system | `pks_progress.md` "What the PKS Is NOT" table |
| NOT an instruction manual for AI | `what_is_pks_v0.md` retracts the earlier "instruction manual" framing as too narrow |
| NOT a bounded context | it *contains* bounded contexts (Architecture Validation Report, tier 5) |
| NOT a knowledge corpus only | "corpus" is exactly what OQ-PKS-7 leaves open |
| NOT KnowledgeOS | the KnowledgeOS proposal is not PKS evidence (§0.3) |

### 2.3 Demonstrated purpose — tier 1 · class **A**

What the implemented capability layer demonstrably does (measured this session, tier 1–2):

1. **Checks identifiers for collision before minting** (`scripts/identifier-check.php`; CAP-001/DP-1/PMR-10). 36 test files exist under `scripts/lib/EngineeringKnowledge/`.
2. **Validates intra-document references and table consistency** (`link-check.php`, plus S2/S4 within `knowledge-lint.php`; CAP-004/DP-4).
3. **Validates vocabulary integrity and confusable identifiers** (S3 within `knowledge-lint.php`; CAP-003/DP-3).
4. **Lints governed documents** against a schema (13+ rules: frontmatter, `knowledge_id` uniqueness, enum validity, relationship target existence, link resolution, single-authoritative-per-topic, traceability completeness, orphan detection, circular dependencies, boundary consistency, review overdue, `code_refs` exist) (`knowledge-lint.php`, tier 1).
5. **Places documents** into governed roots by rule (`scripts/doc-placement.php`, tier 1; resolver, exit-code contract).
6. **Generates a knowledge graph** (`scripts/knowledge-graph.php`, tier 1).
7. **Measures static cohesion** (a Cohesion capability with an LCOM4 graph model, tier 1; see §24 U-03).
8. **Wires the linter into CI and hooks**: `.github/workflows/knowledge-lint.yml` (warn-only, `continue-on-error`), `package.json` (`knowledge-lint`, `knowledge-lint:strict`), `scripts/verify.sh` (structural profile over `docs/knowledgeos/architecture`).

### 2.4 Purpose confidence

| Statement | Class | Confidence |
|---|---|---|
| PKS models engineering knowledge, governance, evidence and their relationships | **A** (as a governed document model) / **G** (as an executable system) | high — docs + code |
| PKS gives AI deterministic artifact guidance | **C** — PARTIAL | medium — guidance exists in docs; the executable determinism is limited to the validation scripts |
| PKS provides a governed corpus and validation | **A** | high — measured |
| PKS runs as a system | **E** — MISSING | high — no runtime/deployment exists |
| PKS's AC-1/AC-2 components exist at runtime | **G** — documented only | high — the C4 views are explicitly non-runtime |

---

## 3. Business / Product-Knowledge Capabilities

The Phase III Capability Catalog (tier 5, DELIVERED — *"no capability herein is authorized for implementation"* at issuance) defines six capabilities with a governed lifecycle vocabulary **Candidate · Designed · Realized · Deferred · Rejected** (deliberately no "Active"). Measured against the working tree in this session:

| ID | Responsibility | Policy | Realization (as documented) | Realization (measured, tier 1) | Current class |
|---|---|---|---|---|---|
| **CAP-001** | Identifier Integrity — *"every identifier is unique within its register(ns), and checked before minting"* | **DP-1** ← PMR-10 · AP-4 | `identifier-check.php` | ✅ `scripts/identifier-check.php` + `Capabilities/IdentifierIntegrity/` (Domain, Application, Infrastructure, Tests) | **A** — IMPLEMENTED |
| **CAP-002** | Projection Integrity — *"every projection is regenerable from its sources, citable as authority by nothing"* | **DP-2** ← AP-2 · AP-9 · DR-1 | DEFERRED (no realization) | none | **E** — MISSING (deferred) |
| **CAP-003** | Vocabulary Integrity — *"a governed term carries one meaning per context; overloads qualified"* | **DP-3** ← SD-3 · F-BCP-4 | `knowledge-lint.php` S3 | ✅ `Capabilities/VocabularyIntegrity/` (16 classes + 10 test files) | **A** — IMPLEMENTED (catalog shows REALIZED 2026-08-21) |
| **CAP-004** | Reference Integrity — *"a reference resolves to an existing target, or is classified as evidence"* | **DP-4** ← the ≥99 confidence bar | `link-check.php` · `doc-placement.php` | ✅ `Capabilities/ReferenceIntegrity/` (12 classes + 7 test files); `link-check.php`; `knowledge-lint.php` S2/S4 | **A** — IMPLEMENTED |
| **CAP-005** | Assessment-Record Integrity — *"an assessment states what was checked, over what scope, with what instrument"* | **DP-5** ← negative-claim discipline | Candidate (no realization) | none | **E** — MISSING (candidate) |
| **CAP-006** | Knowledge-Card Integrity — *"a governed document carries a valid knowledge card"* | **DP-6** ← Knowledge-Constitution | `knowledge-lint.php` | ✅ `knowledge-lint.php` card rules | **A** — IMPLEMENTED |

**Additional implemented capability not in the catalog (measured):**

| Capability | Evidence | Class |
|---|---|---|
| **Cohesion** — static LCOM4/cohesion-graph analysis (`Capabilities/Cohesion/` with `Lcom4.php`, `CohesionGraph.php`, `PhpFactExtractor.php` etc., 21 classes) | tier 1; no dedicated test files, covered by the shared `Tests/BackTest/Phase0BackTest.php` and adapter tests | **A** (implemented) / **UNKNOWN** relative to the catalog — see §24 U-03 |

**Capability relationships (tier 5, from the catalog):** no hard dependencies between capabilities; CAP-003→CAP-001 is a **soft** prerequisite; shared vocabulary overlaps are only `Target/Resolved/Ambiguous/Missing` (CAP-001/004) and `Source/Regenerate` (CAP-002/006); shared policies: none (each DP maps 1:1). The catalog records H-CAT-1 (a parent "Validation Capability" abstraction) as **weakened, not adopted**, and H-CAT-4 (PKS evolving toward a knowledge *graph*) as "EXPLICITLY NOT introduced as an architectural layer today."

---

## 4. Actors and Users

The C4 Level 1 view **deliberately renders no actors** (C4-1 §4, KO-1, tier 4): *"AD-1 derives no actors, and the governed model records that in key places NO ROLE EXISTS (two ownership vacuums; no owner for the enclosed assessment capability)."* Drawing actors would invent roles the corpus records as absent.

The governance records nonetheless name **roles** (tier 4), all human or AI-process, none a runtime system:

| Actor | Role in PKS | Evidence |
|---|---|---|
| **Decision Authority / Principal Architect (DA/PA)** | The sole authority that issues decisions, rulings, approvals, promotions; *"the ARB can settle a statement's status; only the ruling authority can make it a decision"* | PKS_Phase_I_ARB_Rulings.md; PKS_Governance_Architecture_v1.md |
| **Architecture Review Board (ARB)** | Reviews, refines, recommends; never decides | ARB Review Discipline; Knowledge Contract Review Method |
| **Senior Knowledge Engineer / Chief Strategic Domain Architect** | Lead/constrain strategic modeling (M3 roles) | M3 Conformance Kind Decision |
| **Recorder / engineering participants** | Execute modeling, write artifacts, append register rows | PKS_Phase_I_ARB_Rulings.md |
| **AI agents (Claude Code)** | Execute governed work under `.claude/settings.json` permission mapping; *"AI surfaces governance questions; it does not resolve them"* | Governance Runtime Adapter Record; bootstrapping docs |
| **Human Principal Architect (reviewing this baseline)** | Gate owner for the Stage-2 review | this commission |

**Ownership vacuums (recorded as absent, not filled):** no owner for identity discipline (L1-14 / **G-3**); no owner for whole-system conformance (OQ-PKS-3); the U-2 translation obligation across the Work-Management edge is **unassigned**.

---

## 5. PKS Ubiquitous Language

PKS has a rich, governed, largely stable vocabulary. **Terminology has been frozen since M6** (M8, tier 4). Three layers (M8 §4):

1. **Layer 1 — observational glossary G-1…G-17** (M0, tier 4), each term "grounded in **observed behavior**, not aspiration":

| # | Term | Operational definition (M0, abridged) |
|---|---|---|
| G-1 | **Decision** | A selection among alternatives under explicit trade-offs, recorded with rationale, individually citable, superseded only forward |
| G-2 | **Rule** | A binding behavioral norm with a single canonical home; other documents reference, never restate |
| G-3 | **Invariant** | A condition that must always hold; violation is a defect, not a choice |
| G-4 | **Ruling** | A recorded governance act by an authority, smaller-grained than a decision record |
| G-5 | **Finding** | an evidenced defect (CBC-1 member) |
| G-6 | **Question** | an open item (contested membership) |
| G-7 | **Term** | a governed vocabulary item |
| G-8 | **Contract** | A versioned specification of expectations at a boundary; rows/versions evolve independently of their carrier (NARROWED in M1 to event-contract scope) |
| G-9 | **Model element** | (unpartitioned core member) |
| G-10 | **Observation** | recorded evidence carrying an epistemic class |
| G-11 | **Risk** | (contested membership) |
| G-12 | **Candidate** | a proposed norm/pattern, not yet adopted |
| G-13 | **Work item** | (boundary-flagged → adjacent domain) |
| G-14 | **Guide step** | a step in a guide (CBC-2 member; identity liability) |
| G-15 | **Verdict** | The outcome of evaluating evidence against a criterion (PASS/FAIL/…/CERTIFIED); issued by a gate or review, never by the author |
| G-16 | **Exception record** | (contested membership) |
| G-17 | **Charter grant** | An authorization permitting a named next activity and nothing else ("construction permit, not a design") |

2. **Layer 2 — the two-tier concept canon** (M2, tier 4): **14 operational** evolution operations (supersede · version/re-issue · amend-additively · split · consolidate · refine · promote · demote/void/reject-with-record · reclassify · retire · archive · migrate/relocate · generalize · derive/regenerate) + **3 hypothetical** (merge · automatic invalidation · expiry/aging), admitted only by first rule-governed precedent. **Requirement** and **Constraint** were NOT adopted as first-class concepts; **Policy** was validated as a projection pattern = {Invariants + Rules + scope statements}.

3. **Layer 3 — context-local UL statements** (M6 §7.4, tier 4): per-context statements (e.g. CBC-1: "Record evidence about the knowledge system, evaluate it against declared criteria, issue judgments that feed authority without being authority"; CBC-2: "Render governed knowledge into consumable forms… without acquiring identity or authority of its own").

**The closed verdict vocabulary** (AP-8, tier 4): `PASS · PASS AFTER CORRECTION · WARN · FAIL · INCONCLUSIVE · EMERGENT · CERTIFIED` — the interchange language; nothing richer crosses SB-1 outbound.

**Identity vocabulary** (M4, tier 4): semantic identity vs representational identity; three identity modes (1 durable-global/register-namespaced · 2 scoped-assigned · 3 intrinsic/name-as-identity); register-as-namespace (preferred explanatory model); derived artifacts carry **no independent semantic identity**.

**Epistemic-class discipline** (tier 4): **Observed · Measured · Derived · Synthesized · Recommendation · Assembly** (Phase III adds Hypothesis). A derivation commission may not classify anything Observed.

**Language liabilities (carried unresolved, M8 §4):** "register" (three senses — doc/ns/art, F-BCP-4, **G-2**); "Qualification" (two senses); "Policy" (normative package vs projection pattern). **Prefix collisions in use:** `DR-n` (ARB rulings DR-1..DR-6 vs dependency rules DR-1..DR-8); `DP-` (C4 diagram principles DP-1..DP-7 vs capability domain policies DP-1..DP-6); `ARB` (Architecture Review Board) vs `AR-1/AR-2` (undefined regions).

---

## 6. Domain Model

**The PKS's domain model is a strategic model, not a tactical one.** Every M-series artifact is self-declared "no bounded context / no architecture / no implementation" at M0, and "zero tactical DDD" throughout (verified: **no aggregate, entity, value object, repository, API, schema, deployment unit, or technology name** appears in AD-1 or the M-series).

### 6.1 Concepts and their status (tier 4)

- **M1 Concept Register:** 17 observed concepts validated (K-1..K-17); 7 of 13 candidates validated (Definition label NOT adopted; ADR validated "as a projection pattern"; Policy/Constraint/Requirement deferred/rejected; Component Specification, Runbook, Context Package rejected). Portability grades: **Essential · Portable · Local · Convention**.
- **M2 Collision Resolution:** Requirement → distributed across Invariant + acceptance criterion + conformance test; Merge/Invalidation → HYPOTHETICAL; Constraint → NOT ADOPTED as primitive; Policy → projection pattern.
- **M3 Conformance Kind:** Knowledge-System Conformance is **"(B) a derived assessment producing a Verdict"**, defined over per-object conformance observations (C's grain as substrate); *no new first-class concept enters the model.* Completeness (M2) = a state-assessed, derived, multi-dimensional assessment (Domain Coverage · Decision Traceability · Contract Completeness · Lifecycle Hygiene · Verifiability).
- **M5 Strategic Domain Classification:** 21 items classified **8 Core · 9 Supporting · 2 Generic**; F6 semantic responsibility verbs (expresses 3 · governs 5 · records 7 · derives 6). Core includes Decision, Rule, Invariant, Ruling, Term, Candidate, Charter grant, Verdict, **Conformance** — with the crucial note that **Conformance is "strategically Core while operationally Absent."**

### 6.2 Identity and lifecycle model (M4, tier 4)

- Three **state classes**: **Pre-authority** (exists, binds nothing) · **Authoritative** (binds) · **Superseded-class terminal** (supersession-shaped, never deletion-shaped).
- Four **orthogonal axes preserved**: authority ⊥ status ⊥ maturity ⊥ adoption.
- Identity survival under the evolution canon: supersede → predecessor keeps identity as history; version/re-issue → identity constant, version increments orthogonally; derived artifacts carry no independent semantic identity.
- Lifecycle exit from pre-authority: **only via a recorded human decision event.**
- The 8-vocabulary fragmentation of lifecycle states was resolved: "several vocabularies encode different **axes**, not different states."

### 6.3 Aggregate/consistency boundaries

**None are defined.** The strategic model explicitly defers all tactical boundaries; AD-1 §6.2 refuses to draw components over the undefined regions. The only consistency boundary that is *implemented* is at the capability layer: each capability is an independent vertical slice with its own Domain/Application/Infrastructure, and `Shared` must not know any capability exists (§18).

---

## 7. Bounded-Context Candidates

The strategic model's context map (M6 Discovery + M6 Authority Disposition + M7 + M8, tier 4, frozen/promoted). **These are candidates as named — the M6/M7 documents are Strategic DDD artifacts, and every context is a BOUNDED CONTEXT CANDIDATE unless the disposition text says otherwise.** Two are ACCEPTED as bounded contexts (M6 Authority Disposition), one is ACCEPTED as an adjacent domain, one is a candidate seam.

| ID | Name | Status | Grade | Definition (M6 §7.4 / M8) |
|---|---|---|---|---|
| **CBC-1** | **Knowledge Assessment** | ACCEPTED bounded context | Medium–High | "Record evidence about the knowledge system, evaluate it against declared criteria, issue judgments that feed authority without being authority." |
| **CBC-2** | **Knowledge Projection** | ACCEPTED bounded context | Medium–High | "Render governed knowledge into consumable forms — teaching, navigation, serialization, visualization — without acquiring identity or authority of its own." |
| **CBC-3** | **Normative Governance** | CANDIDATE SEAM (evidence-decided, not a context) | Low–Medium | a seam, not a context; preserved finer partition Norm Custody ∥ Authorization Acts |
| **CBC-4** | **Work Management** | ACCEPTED adjacent (outside PKS, "a boundary that excludes") | Medium | external domain; tracks units of work and their progress |
| — | **Expressed-knowledge core** | Intentionally unpartitioned region | — | Decision · Term · Model element · Contract; "may in fact be several contexts" (T-16) |

**Three concepts are recorded as contested and deliberately unassigned** (M6 §8; AD-1 §7): **Risk, Question, Exception record.** The architecture allocates their handling nowhere, and "may not allocate it by default."

**Confidence ceiling (MCR-5, binding):** every grade rests on **one corpus, one lineage** — at most **Medium–High**; never citable as independently confirmed.

---

## 8. Application / Workflow Architecture

### 8.1 The governance lifecycle (the PKS's primary "workflow")

PKS_Governance_Architecture_v1.md (tier 4) defines the responsibility chain and lifecycle sequence:

```
Authority Decision (GO) → Bootstrap Composition Root → Execution Governance →
M6 Execution Commission → Approved Plan → M6 Execution → Checkpoint Package →
Critical Review → Authority Disposition → Consolidated Baseline → MCA → CDR →
Authority certification/adoption → Certified SDM/EOP v1 baseline → M7 → M8 → Post-M8 Benchmark
```

The **Governance Invariant** (verbatim, tier 4):

> "Each governance artifact has exactly one primary responsibility. No artifact may redefine, duplicate, or assume the primary responsibility of another artifact. Governance authority, operational mandate, execution procedure, execution governance, execution activity, evaluation, and certification must remain explicitly separated."

Responsibility chain (one owner per row): Program Authority (PA/DA) — governance decisions (GO/Conditional GO/NO GO) · Bootstrap Composition Root — assemble artifacts + transfer operational control · Execution Governance — behavioral/constitutional constraints · M6 Execution Commission — operational mandate · Approved Execution Plan — operational procedure · M6 Execution — discovery only · Critical Review — execution evaluation · Authority Disposition — dispose M6 outputs · MCA — methodology evaluation · CDR — disposition analysis · PA/DA — certification/adoption decision.

### 8.2 The observed identifier-minting workflow (tier 5 observation, docs/pks)

The dominant manual process (measured in `operational-workflow-assessment.md`, tier 5):

```
1  a commission or package is prepared            date-named · no identifier
2  an identifier may be RESERVED in that text  ← THE IDENTIFIER ENTERS CIRCULATION HERE
3  the ARB decides
4  a record is written                            date-named · no identifier
5  a row is APPENDED to the register           ← THE MINTING ACT
6  committed as  docs(governance): R-nn — <subject>
```

> **"Identifier allocation is a manual human activity, performed by editing markdown."** (docs/pks observation, tier 5; confirmed tier 1: no identifier service/hook/gate exists beyond the CAP-001 validator itself.)

### 8.3 The implemented validation workflow (tier 1, measured)

```
git commit (pre-commit: lint-staged for .vue)  ·  git push (pre-push → scripts/verify.sh)
PR CI: .github/workflows/knowledge-lint.yml  →  php scripts/knowledge-lint.php  (warn-only)
dev:   npm run knowledge-lint[:strict]  ·  php scripts/identifier-check.php <ID>  ·  php scripts/link-check.php
```

`knowledge-lint.php` is the consolidated gate: it composes the S1–S5 structural profile (S1 document-local identifier uniqueness/ordering = CAP-001 · S2/S4 reference resolution + table consistency = CAP-004 · S3 vocabulary + confusable identifiers = CAP-003 · S5 unlabelled-superseded → WARN) plus the schema-driven lint rules and the Phase-1 handoff-assurance report.

---

## 9. Component Architecture

### 9.1 The PROMOTED logical architecture (AD-1, tier 4) — class **G** as a runtime topology

| Element | Strategic origin | Architectural role | Runtime evidence |
|---|---|---|---|
| **AC-1** Knowledge Assessment | CBC-1 | logical component; records evidence, evaluates against criteria, issues verdicts; holds NO authority, NO criteria; cannot self-issue | **G** — no runtime component exists |
| **AC-2** Knowledge Projection | CBC-2 | logical component; renders/regenerates; terminal sink (DR-1); no authority, no return path | **G** — no runtime component exists |
| **AR-1** Normative Governance region | CBC-3 | architecturally undefined region; placement contingent on OQ-PKS-7 | **G** — region |
| **AR-2** Expressed-knowledge core | unpartitioned core | architecturally undefined region | **G** — region |
| **XD-1** Work Management | CBC-4 | external domain, outside PKS | — |
| SB-1 / SB-2 / IB-1 / IB-2 | — | logical service boundaries | — |

Ten architectural principles **AP-1..AP-10** (tier 4, PROMOTED): AP-1 Knowledge feeds authority; it never holds authority · AP-2 Projections regenerable and non-authoritative · AP-3 Revision is forward-only (status **UNDETERMINED**) · AP-4 Identity per-kind and register-scoped · AP-5 Semantic ≠ representational identity · AP-6 Derived assessments carry no identity · AP-7 Criteria used here, owned elsewhere · AP-8 Verdict vocabulary is the interchange language · AP-9 Path toward projection is one-way by construction · AP-10 Work management is outside. AP-1/2/3/9 are prohibitive.

### 9.2 The implemented capability architecture (tier 1, measured) — class **A**

```
scripts/lib/EngineeringKnowledge/           (PHP 8.2, no framework; autoloaded via composer.json
│                                             "EngineeringKnowledge\\": "scripts/lib/EngineeringKnowledge/")
├── Shared/
│   ├── Domain/        Verdict.php (AP-8 closed vocabulary) · Assessment.php (verdict + evidence)
│   │                  CheckerVersion.php · AssuranceHandoffReport.php · HandoffContext.php
│   ├── Application/   GenerateHandoffAssuranceReport.php
│   └── Infrastructure/ StructuralCliReporter.php
├── Capabilities/
│   ├── IdentifierIntegrity/   Domain: Identifier · IdentifierSeries · SeriesContents ·
│   │                          IdentifierPolicy (DP-1) · AssessesIdentifierIntegrity ·
│   │                          AssessesDocumentLocalIntegrity · DocumentSectionIdentifier/Sequence
│   │                          Application: ValidateIdentifier · ValidateDocumentLocalIntegrity ·
│   │                          SeriesContentsReader (PORT) · DocumentContentsReader
│   │                          Infrastructure: GovernedRegisterMap · MarkdownSeriesContentsReader ·
│   │                          MarkdownDocumentContentsReader · CliOutputFormatter
│   ├── ReferenceIntegrity/    Domain: ReferencePolicy (DP-4) · SectionReference · StepReference ·
│   │                          TableContents/TableRow · AssessesIntraDocumentReferences · …
│   │                          Infrastructure: MarkdownIntraDocumentReader · MarkdownTableReader
│   ├── VocabularyIntegrity/   Domain: VocabularyPolicy (DP-3) · DeclaredVocabulary · ConfusableIdentifier ·
│   │                          HomoglyphMap · TermOccurrence · SplitDeclaration · …
│   │                          Infrastructure: YamlVocabularySource · MarkdownVocabularyReader ·
│   │                          MarkdownDispositionReader
│   └── Cohesion/              Domain: CohesionGraph · Lcom4 · GraphBuilder · FactSet · EdgeRules ·
│                              EdgeVerdict · MethodFacts · UnitIdentity · … (21 classes)
│                              Infrastructure: PhpFactExtractor
└── Tests/  (36 test files across capabilities + Shared + Adapters + BackTest)
```

**Layering rule enforced by construction (per capability READMEs):** `CLI → Infrastructure → Application → Domain → Shared`; **no arrow runs the other way**; `Shared` must not know a capability exists. The IdentifierIntegrity README is declared "the template for every future Engineering Knowledge capability."

### 9.3 CLI entry points (tier 1)

| CLI | Capability | Purpose |
|---|---|---|
| `scripts/identifier-check.php` | CAP-001 | mint-audit: `<ID>` check · `--audit <S>` · `--series` · `--document=<path>`; exit 0 PASS · 1 WARN/INCONCLUSIVE · 2 FAIL · 3 usage |
| `scripts/link-check.php` | CAP-004 | reference validation |
| `scripts/knowledge-lint.php` | CAP-003/004/006 + S1–S5 | consolidated lint; `--strict` · `--json` · `--profile=structural` · `--report=handoff` |
| `scripts/doc-placement.php` | (placement rule) | resolves documentation placement by rule; exit 2 = unruled |
| `scripts/knowledge-graph.php` | (projection) | regenerates the knowledge graph |

---

## 10. Data Architecture

**The PKS's "data" is knowledge specifications: Markdown documents + YAML controlled vocabularies, stored in git.** (tier 1, measured; tier 4 AD-1 deployment-neutral.)

- **Controlled vocabularies** in `docs/knowledge/schema/`: `bounded-contexts.yaml` · `statuses.yaml` · `authorities.yaml` · `knowledge-types.yaml` · `knowledge-relationships.yaml` · `knowledge-audiences.yaml` · `documentation-placement.yaml` · `repository-migrations.yaml` (tier 1).
- **Governed registers** (the identifier-bearing series): `R-nn` rulings (register rows) · `PMR-nn` (methodology candidates) · `ADR-T/MP/PL/UL-nn` (four ADR sub-series) · `ES-00n` standards · `CAP-nnn` capabilities · `CBC-n`, `AC-n`, `AP-n`, `DR-n`, `SB-n`, `IB-n`, `AR-n`, `XD-n`, `DP-n`, `G-n`, `K-n`, `OQ-n`, `U-n`, `X-n` (identifier namespaces declared in AD-1 §5, "local to that document").
- **Date-named majority**: verification reports, commissions, plans, session logs use `YYYY-MM-DD-<subject>` / `YYYYMMDD-HHMM-<what>-plan.md` (ES-004.2) and **mint no identifier** (docs/pks observation, tier 5).
- **No database, no schema, no API** anywhere in the PKS evidence. AD-1 §3: "Verified absent from this document: no aggregate, entity, value object, repository, API, schema, deployment unit, or technology name appears anywhere."

**Data-flow (implemented, tier 1):** Markdown/YAML sources → readers (Infrastructure) → domain policies (Domain) → verdicts (Shared/Verdict) → CLI reporters → exit codes/human reports. The inputs to a decision are "the governed registers themselves ⛔ never a cached index (DR-1)."

---

## 11. Persistence Architecture

| Aspect | PKS evidence | Class |
|---|---|---|
| Storage medium | Git-backed Markdown + YAML in the PublicDigit repository | **A** (tier 1) |
| Authoritative records | governed registers (R-nn rows, PMR-nn rows, ADR log rows, ES filenames) | **A** (tier 1–3) |
| Identifier minting | manual, human, editing markdown; no allocation service | **A** (tier 1 — no service exists) / **D** (debt: G-1, G-10) |
| Canonical register list | **G-1**: "no canonical enumeration of governed registers" — CAP-001 emits INCONCLUSIVE for unregistered series | **E/MISSING** |
| Projection persistence | projections must be regenerable from sources, never hand-edited to disagree (AP-2); `knowledge-graph.php` regenerates the graph | **A** for the script; the property is enforced by discipline, not mechanism |
| Artifact history | **NOT under version control** — AD-1 §11A and C4-1 §6B disclose superseded wordings are "reconstructed from replacement operations, not recovered from a committed baseline" | **F** (contradicted with git reality — files ARE in git, but the artifact text claims reconstruction) |
| .claude/settings.json permission mapping | **19 deny / 22 ask / 8 allow** (measured tier 1) — **matching** the Runtime Adapter record's stated counts (erratum P2-F1) | **A** — counts match; no delta evidenced |

**Consistency model:** the implemented validators enforce consistency *in documents* (frontmatter, references, vocabulary, identifiers) at CLI/CI time. There is **no transactional persistence**; atomicity is git-commit atomicity. The strategic model's consistency is a governed discipline (the Governance Invariant), not a mechanism.

---

## 12. Invariants

Invariants in the PKS are governed statements (tier 4) plus implemented checks (tier 1):

| Invariant | Statement | Where enforced |
|---|---|---|
| **Governance Invariant** | one primary responsibility per artifact; authority/operational-mandate/procedure/execution-governance/activity/evaluation/certification explicitly separated | governance discipline (tier 4) |
| **PMR-10** | an identifier must be checked for collision before it is minted | `identifier-check.php` (tier 1) |
| **AP-1 / DP-1** | knowledge feeds authority, never holds it; every identifier unique within its register(ns) | governance + CAP-001 |
| **AP-2 / DP-2 / DR-1** | projections regenerable and non-authoritative; nothing may depend on AC-2; no element may consume a projection as authority | governance; DR-1 also grounds "no cached index" in CAP-001 |
| **AP-8 / DR-8** | only the closed verdict vocabulary crosses SB-1 outbound | CAP-001 emittable subset: PASS/FAIL/WARN/INCONCLUSIVE; never PASS AFTER CORRECTION/EMERGENT/CERTIFIED (tier 1) |
| **R-3 constraint** | the relationship toward projection is unidirectional by constitutional rule — no return path in the authority/evidence direction | governance (tier 4) |
| **ES-005.1** | three-concern separation: Product = `docs/`+`architecture/`+`app/`+`tests/`, Engineering = `engineering/`, Runtime = `.claude/` | `doc-placement.php` + discipline (tier 1/4) |
| **Assembly Fidelity Rule** (SDM v1.1) | an assembly artifact shall not modify normative wording inherited from an authoritative source | governance (tier 4) |
| **Strategic/Tactical boundary** (SDM v1.1) | tactical elevation confers NO precedence in conflict | governance (tier 4) |
| **CI-6** | amendments are additive with a history row; originals not rewritten to look correct | governance (tier 4) |
| **Fail-closed** | absence of evidence is never PASS | CAP-001 README (tier 1/5) |
| **Knowledge-card** (DP-6) | a governed document carries a valid knowledge card | `knowledge-lint.php` (tier 1) |

**The strongest invariant set in the architecture is negative** (AD-1 §5, tier 4): AP-1, AP-2, AP-3, AP-9 are prohibitive — "the strongest evidence was always a rule stating what may not happen."

---

## 13. State and Lifecycle

### 13.1 Artifact lifecycle (tier 4, implemented in practice)

`PROPOSED / PRODUCED → ACCEPTED (w/ refinements) → PROMOTED (Authority act) → FROZEN → under Change Control (CCP-1)` — plus `DELIVERED`, `ISSUED`, `CLOSED` (review records), `REVISE` (review verdicts). Promotion is always a distinct Authority act; "generated — never authoritative without human review" appears on every generated artifact.

### 13.2 Domain lifecycle model (M4, tier 4)

Three state classes (Pre-authority / Authoritative / Superseded-class terminal) × four orthogonal axes (authority ⊥ status ⊥ maturity ⊥ adoption); transitions are the M2 evolution canon (14 operational, 3 hypothetical gated on first rule-governed precedent). **Forward-only** supersession; no in-place revocation (AP-3 — status UNDETERMINED whether constitutional or habit).

### 13.3 Capability lifecycle (tier 5 — unratified, G-9)

Candidate → Designed → Realized → Deferred → Rejected; "Active" deliberately absent (AIP-10). The catalog's own §4 shows the 2026-08-21 back-test statuses: CAP-003/004/006 REALIZED, CAP-001 DESIGNED⛔unauthorized (at catalog issuance) — while the 2026-08-02 Engineering Knowledge Model still shows CAP-003 "Candidate". **Live documents updated past their headers** (see §25 X-04).

### 13.4 Current governance state snapshot (tier 4/5)

- SDM v1.2 / EOP v1.2: **FROZEN**, reference-defined baseline (Methodology_Baseline_v1_2).
- Method Design: **PROVISIONALLY CERTIFIED**; Operational Evidence: **ZERO-INDEPENDENT** / "supported by one execution lineage".
- M6 Authority Disposition: CBC-1/CBC-2/CBC-4 ACCEPTED, CBC-3 CANDIDATE SEAM; baseline FROZEN under change control.
- M7, M8, AD-1, C4-1, RET-1, AFV-1: **PROMOTED**.
- PKS-ADR-001: ARB approved with minor revisions; **Authority Disposition PENDING**.
- M8 Gate #2 (ARB output review): **OUTSTANDING**.
- Phase II.D (implementation): **PENDING** — yet a capability layer now exists (tier 1), created under "feat(knowledgeos)" commits.

---

## 14. Events and Messaging

**None are evidenced.** This is an explicit, load-bearing negative:

- C4-1 §2 (tier 4): "**Verified absent from every diagram below:** synchronization, messaging, events, request/response, orchestration, choreography, deployment nodes, runtime topology, data stores, protocols, technologies."
- AD-1 §9.2 (tier 4, PROMOTED): "the governed model contains **no timing, coupling, delivery, or ordering evidence** whatsoever… **synchronous versus asynchronous communication is NOT derived here.**" Recorded as Exception-Protocol question **Q-1**; the recommended instrument is an Authority-commissioned discovery act about the practiced system, not literature.
- C4-1 arrows A1..A8 (tier 4): "direction and content only" — no arrow carries a mechanism label.

**The only cross-boundary "message" that exists** is the closed verdict vocabulary at SB-1 outbound (AP-8) — a published language, not a messaging mechanism. **In the implemented layer** (tier 1), the "events" are CLI invocations and their exit codes; there is no event bus, queue, or message contract.

---

## 15. Governance and Authority

The PKS's authority model is its most-developed architectural feature.

### 15.1 Who holds authority (tier 4)

- **Human DA/PA is the sole authority.** ARB reviews/recommends/refines; only the ruling authority makes a decision. EP-01 plan approval is the DA's act, not the ARB's (R-46 precedent). Execution authorization is a separate, later act.
- **Promotion** is a distinct Authority act, always separated from review/verification; nothing is authoritative without human review.
- **Change control** (CCP-1): amendments additive with a history row (CI-6); superseded wording retained (AD-1 §11A, C4-1 §6B); frozen ≠ immutable — amendment via Authority decision + approved change control.

### 15.2 The authority/evidence direction (tier 4)

R-3's constitutional rule: **"nothing may cite a view as authority"** — the relationship toward projection is unidirectional; no return path exists in the authority/evidence direction. R-2: CBC-1 → the seam's authorization acts is **Customer/Supplier over a Published Language** — "knowledge as input to authority, never as authority" (AP-1).

### 15.3 The runtime enforcement adapter (tier 1 + tier 5 record)

The Governance Runtime Adapter record documents the inversion `Governance Policy → Capability Mapping → Tool Adapter → Concrete Configuration`, with `.claude/settings.json` as an **adapter, not the governance model**. Capability mapping: never → `permissions.deny` (exact) · possible-after-authorization → `permissions.ask` (approximate) · free → `permissions.allow` (exact) · repository state → **unmapped** · commission-conditional → PreToolUse hook (not attempted) · protection by artifact class → filename patterns (approximate). Two documented limitations: protection is **filename-pattern based** (deferred) and enforcement is **tool-scoped** (a `Bash` heredoc bypasses the `ask` gate — the record's own writing path; deferred). **Demonstrated** (tier 5 record, 2026-07-31): governance changed contributor behavior; M7-CC1 validated `ask` over `deny`. **Current settings.json (measured tier 1): 19 deny / 22 ask / 8 allow** — **matching** the 19/22/8 the record states (erratum P2-F1: the earlier "1 allow / 1 ask / 1 deny block" reading counted the three permission *blocks*, not their entries; the counts agree).

### 15.4 Open governance questions (tier 4)

OQ-PKS-2/3/4/7/9/10/11 (ARB-owned, none resolved); the Surfacing Register carries six OQs, none resolved; OQ-PKS-7 (one corpus or three) is the load-bearing one for AR-1's placement.

---

## 16. Assurance / Validation Architecture

PKS has the most elaborate assurance apparatus of any system in this repository. All tier 4.

### 16.1 The review instruments

| Instrument | Role |
|---|---|
| **Knowledge Contract Review Method** | 11 objectives in mandated order; invariant sections + one interchangeable emphasis module; finding vocabulary (owned by the method); standing prohibitions; the honest-failure-direction question; negative-claim discipline; the Trustworthiness Test; "recognition ≠ agreement ≠ adoption." |
| **ARB Review Discipline** | reviewer playbook: scope/classification/source-of-truth/reporting discipline; admission ladder; canonical GOVERNANCE-STATE vocabulary (Control Admission axis + Artifact Lifecycle axis); contract admission rule; four separated concerns. |
| **MCA (Method Certification Assessment)** | evaluates the methodology (R1, R2 assessments). |
| **CDR (Commission Decision Review)** | disposes change decisions; CDR-R1/R2 recorded. |
| **KBI / ER / VF / AFV / CCP / CON / ERV / ISV / MC1** | baseline-integrity, execution-readiness, verification/fidelity, architecture-fidelity (AFV-1), controlled-change (CCP-1), consolidation-integrity, controlled-change-integrity, issuance-scope, certification-inference reviews. |
| **C4-2 (Representation Verification)** | verifies C4-1 is a faithful projection of AD-1; PASS WITH FINDINGS (1 Moderate VR-2, 3 Minor). |

### 16.2 The quality gates (AD-1, tier 4)

Strategic Fidelity (every element in the traceability matrix) · Governance Fidelity (no frozen decision modified) · Knowledge Fidelity (assembly ≠ discovery; derivation ≠ interpretation) · Boundary Fidelity (logical only; zero tactical content).

### 16.3 Assurance ceiling (tier 4, MCR-5)

All grades rest on **one corpus, one lineage**; confidence ceiling **Medium–High**; "the architecture cannot be more certain than the model it derives from" (AD-1 §13.8). **Operational Evidence stands at ZERO-INDEPENDENT** (Phase III charter): "sixteen reviews, five adoptions, and a frozen baseline establish that the methodology is INTERNALLY SOUND. They establish NOTHING about whether it WORKS."

### 16.4 Implemented assurance (tier 1)

36 test files under `scripts/lib/EngineeringKnowledge/`; `.github/workflows/knowledge-lint.yml` (warn-only); `composer.json` merge-gate runs Architecture/GreenfieldCore testsuites + Deptrac + PHPStan (PKS-adjacent, PublicDigit gates).

---

## 17. Integration Architecture

### 17.1 The strategic integration model (M7/DAR-1 + AD-1 §10, tier 4)

| Integration | Model | Pattern name |
|---|---|---|
| AR-1 → AC-1 (criteria) | upstream/downstream dependency — criteria supply; no coordination assumption | pattern **withdrawn** (DAR-1) |
| AC-1 → AR-1 (judgments) | Customer/Supplier over a **Published Language** (closed verdict set); supplier informs, never binds | **R-2 validated** |
| {AC-1 · AR-1 · AR-2} → AC-2 | **Conformist**, one-way, plus a constitutional constraint (no return path) | R-3 as disposed |
| {AC-1 · AR-1} ↔ XD-1 | cross-edge dependency; interchange = the DoD's five knowledge boxes; **translation obligation unassigned (U-2)** | pattern withdrawn |
| AC-2 ↔ XD-1 | **no integration** | R-5 Separate Ways (validated) |

The dependency graph is **acyclic as modeled** (M7 §8). **Derived — independent components: none** (AD-1 §10).

### 17.2 The implemented integration (tier 1, measured)

- **Tool integration:** the capability layer is invoked by CLI scripts, `npm run` scripts, `scripts/verify.sh`, `.husky/pre-push`, and `.github/workflows/knowledge-lint.yml`.
- **Governance runtime integration:** `.claude/settings.json` (permission adapter) + `.claude/scripts/*` hooks (`engineering-placement-guard.sh`, `project-knowledge-guard.sh`, `discipline-gate-reminder.sh`, `dev-guide-reminder.sh`, `inject-context.sh`, `workflow-state.php`, `session-resolve.php`).
- **Placement integration:** `scripts/doc-placement.php` resolves document placement by rule (exit 2 = unruled → PENDING).

### 17.3 Integration NOT evidenced

No PKS service, REST API, message bus, shared database, or runtime connection to any external system. XD-1's interior is deliberately unmodelled (outside PKS design authority).

---

## 18. Dependency Architecture

### 18.1 Dependency rules DR-1..DR-8 (tier 4, PROMOTED)

DR-1 Nothing may depend on AC-2 (pure sink) · DR-2 no element may substitute a projection for its source · DR-3 AC-1 depends only on AR-1 (criteria, read-only), AR-2 (knowledge assessed), and an external issuance trigger · DR-4 criteria dependencies are read-only · DR-5 no PKS element may depend on XD-1 (cross-edge runs inward only) · DR-6 dependencies on AR-1/AR-2 are dependency *contracts*, never component dependencies · DR-7 the dependency graph is acyclic (a description; the forward-reaching clause is a **reopening trigger**, NOT a rule, per AD-R1) · DR-8 only the closed verdict vocabulary crosses SB-1 outbound.

### 18.2 The implemented dependency graph (tier 1, measured)

- **Code layering:** `CLI → Infrastructure → Application → Domain → Shared`; no reverse arrows; `Shared` must not know capabilities exist.
- **Capability independence:** no hard dependencies between capabilities; only two shared-vocabulary overlaps; DP policies map 1:1 (each capability owns one policy restatement).
- **Tool wiring:** `knowledge-lint.php` depends on IdentifierIntegrity + ReferenceIntegrity + VocabularyIntegrity + Shared. `link-check.php` depends on ReferenceIntegrity + Shared. `identifier-check.php` depends on IdentifierIntegrity + Shared.
- **Acyclic:** the implemented graph is acyclic (no capability imports another capability; only `Shared` is shared).

---

## 19. Runtime / Deployment Architecture

### 19.1 There is no PKS runtime

AD-1 is deployment-neutral by construction (tier 4). C4-1 DP-6: "containers carry no runtime semantics — AC-1/AC-2 are *logical* containers." There is **no service, daemon, scheduler, queue, database, or deployment unit** with a PKS label. The PKS "runs" only when:

1. a **human or AI governance process** executes a governed lifecycle act (commission → execute → review → dispose → certify), or
2. a **CLI/CI program** is invoked: `identifier-check.php`, `link-check.php`, `knowledge-lint.php`, `doc-placement.php`, `knowledge-graph.php`, `metrics-report.php` (spike, advisory), or the `.claude/scripts/*` hooks.

### 19.2 Execution environment (measured, tier 1)

- **PHP 8.2+** (CI uses 8.3), no framework, composer autoload for `EngineeringKnowledge\`.
- **Git hooks**: `.husky/pre-commit` (lint-staged for `.vue`), `.husky/pre-push` (design governance via `verify.sh`; knowledge-lint structural profile on `docs/knowledgeos/architecture`).
- **CI**: `.github/workflows/knowledge-lint.yml` (warn-only, `continue-on-error`), plus PublicDigit gates (merge-gate, quality-tier, membership-architecture, regression-detector, role-permission-verification).
- **AI runtime adapter**: `.claude/settings.json` permission mapping (ask/deny/allow) — the documented "Governance Runtime Adapter."

### 19.3 The observation/assurance runtime (`scripts/observations/`)

A separate, **KnowledgeOS-named** runtime exists under `scripts/observations/` (observe.php, init.php, watch.php, AssessmentService.php, RecommendationEngine.php, KnowledgeOsDoctor.php, lcom4-collectors, etc.) and `scripts/metrics/`. It is **outside the PKS corpus** (see §24 U-02); its relationship to PKS is UNKNOWN. It is reported here only because it lives in the same working tree and shares the `EngineeringKnowledge` neighborhood — **it is not evidence for any PKS claim.**

---

## 20. Architectural Patterns Actually Present

| Pattern | Where | Reality |
|---|---|---|
| **Strategic DDD pattern names** (Customer/Supplier, Published Language, Conformist, Separate Ways) | M7 §5 / AD-1 §10 | tier 4, PROMOTED, but **"recommended relationship classifications, not established relationships"**; 3 of 5 carry no coordination pattern post-DAR-1 |
| **Candidate seam vs bounded context** (MCR-2) | M6 / AD-1 §6.2 | the discipline of *not* encapsulating what governance declined to bound |
| **Closed verdict vocabulary as a published language** (AP-8/DR-8) | AC-1 outbound | the only published interface in the model |
| **Regenerable, non-authoritative projections** (AP-2) | AC-2 | a projection is a function of its sources |
| **Terminal sink** (DR-1) | AC-2 | nothing may build on the projection layer |
| **Derived assessment over recorded substrate** (M3-B) | conformance/completeness | derived Observation(s) + a Verdict; no independent identity (AP-6) |
| **Portability rubric** (Essential/Portable/Local/Convention) | M1 §7 | the governed classification for cross-system portability |
| **Two-layer portability** (Governance → Capability Mapping → Runtime Adapter → Concrete Configuration) | Governance Runtime Adapter | one adapter exists; multi-runtime portability is asserted, not shown |
| **Hexagonal capability slicing** (Domain/Application/Infrastructure + ports) | `scripts/lib/EngineeringKnowledge/**` | **A** — implemented, tier 1 |
| **Epistemic-class discipline** | every PKS artifact | Observed/Derived/Synthesized/Recommendation/Assembly; never mixed |
| **Forward-only supersession** (AP-3) | identity/lifecycle | observed corpus-wide; constitutional status UNDETERMINED |
| **Fail-closed validation** | CAP-001 | absence of evidence is never PASS |
| **Warn-only advisory gates** | CI, `knowledge-lint`, `metrics-report` | "collectors never judge; developers decide" |

---

## 21. Architectural Strengths

1. **Governance Invariant and single-responsibility discipline** (tier 4): each artifact has exactly one primary responsibility; authority/operational-mandate/procedure/execution-governance/activity/evaluation/certification are explicitly separated. This is enforced throughout the Phase II corpus.
2. **Promotion as a distinct, separately-owned Authority act** (tier 4): review → disposition → change control → promotion, each step with its own owner; "generated — never authoritative without human review."
3. **Deliberate partiality** (tier 4): AD-1 *refuses* to draw components over the undefined regions; C4-1 *refuses* to render actors or mechanisms. The architecture does not close what evidence does not support — the anti-elegant-partition discipline.
4. **Closed verdict vocabulary as an architectural boundary** (AP-8/DR-8, tier 4; CAP-001 emittable subset, tier 1): the interchange language is a finite, governed set, and the implemented validator actually restricts its output to it.
5. **Regenerable, non-authoritative projections with a structurally-checkable correctness condition** (AP-2, DR-1, tier 4): AC-2's correctness is verifiable — "if any projection cannot be regenerated from its sources, or is cited as authority anywhere, the component is violated."
6. **Acyclic dependency discipline** (tier 4; tier 1 in code): the strategic graph is acyclic; the implemented code layer is acyclic and enforces `CLI→…→Shared` direction.
7. **Real, tested capability implementations** (tier 1): 36 test files; CAP-001/003/004/006 realized; wired into CI and package scripts. The capability layer is the strongest *executable* asset.
8. **Behavioral governance demonstrated** (tier 5 record, 2026-07-31): the governance changed real contributor behavior (a new artifact written instead of amending a frozen one) even where the automated gate was absent — "tool enforcement says you COULDN'T; behavioral governance says you COULD have, and you didn't."
9. **Identity/lifecycle model with forward-only supersession and orthogonal axes** (tier 4): a coherent answer to the 8-vocabulary lifecycle fragmentation.
10. **The M7/M8 assembly honesty** (tier 4): "A complete assembly of incomplete knowledge is exactly what this report is" — the model openly distinguishes assembly completeness from knowledge completeness.

---

## 22. Architectural Problems / Debt

1. **Specified-but-unrealized conformance** (tier 4, T-17): AC-1 encloses a whole-system conformance assessment that **no mechanism performs and no role owns** (OQ-PKS-3). The architecture "specifies it because the strategic model places it here; it does not thereby realize it."
2. **Three contested concepts deliberately unallocated** (tier 4, M6 §8): Risk, Question, Exception record — allocated nowhere; the absence is a governed decision, not a gap.
3. **Two ownership vacuums + U-2 unassigned translation** (tier 4): no owner for identity discipline (G-3); no owner for conformance; the Work-Management translation obligation is unassigned.
4. **OQ-PKS-7 unresolved** (tier 4): AR-1's placement is contingent on one-corpus-vs-three; under the three-corpora reading part of AR-1 falls outside PKS.
5. **Identifier-system debt**: no canonical register list (G-1); the `register` word carries three senses (G-2); **G-10 — R-70/R-71 citations damaged when R-65/R-66 were minted, "identity damage — UNCURABLE," accruing** (tier 5, catalog; tier 1 code comment "Existing collisions are uncurable").
6. **IBC-1 does not exist** (G-7, tier 5): the designed Implementation Boundary Contract — the handover firewall — has no repository artifact; last verdict REVISE.
7. **No ratified capability lifecycle** (G-9, tier 5): Candidate→Designed→Realized is defined only in the catalog; "Active" deliberately absent.
8. **Guide-step identity liability inside AC-2** (tier 4, M4 mode 3\*): representational-only identity by neglect, in tension with AP-5, inside an accepted boundary — carried, not repaired.
9. **AD-1 §9.1 retains superseded wording** (G-8, tier 5): the trigger-location claim C-02 struck is still present in a PROMOTED artifact.
10. **Unresolved merge-conflict block in a governed artifact** (tier 1, measured): the Governance Runtime Adapter record contains `<<<<<<< HEAD / ======= / >>>>>>> 56766c1f` in §4.1.x and the traceability footer.
11. ~~**Documentation/runtime delta on the permission adapter**~~ → **RESOLVED (erratum P2-F1):** the record states 19 deny/22 ask/8 allow and the current `.claude/settings.json` measures **19 deny/22 ask/8 allow — the counts match**; no delta is evidenced.
12. **Tool-scoped, filename-pattern enforcement** (tier 1/5): a governed file written via `Bash` heredoc does not fire the `ask` gate; protection by filename pattern misses differently-named artifacts.
13. **The capability catalog vs the code tree drift** (tier 1 vs tier 5): CAP-001 is catalogued "DESIGNED ⛔ unauthorized" while the code is implemented and committed; the Cohesion capability exists in code but is absent from the catalog (§24 U-03). Git labels these commits "feat(knowledgeos)" — the PKS corpus boundary around its own implementation is unresolved (§25 X-06).
14. **Placement deviations recorded**: PKS Phase III docs instructed to `docs/pks` by the resolver were written to `docs/implementation/` (deviation recorded, not silent); `docs/pks/2026-08-05-knowledgeos-distillation-principle-candidate.md` is `PENDING`-placement, acknowledged non-compliance.

---

## 23. Current Architecture Diagram

### 23.1 The PROMOTED logical architecture (C4-1 Level 2 — tier 4, class G as runtime)

```
╔══════════════════════════════════════════════════════════════════════╗
║  PRODUCT KNOWLEDGE SYSTEM                            [system boundary]║
║  ~  AR-1  NORMATIVE REGION  (***architecturally undefined region***) ║
║  ~  Not a container · not a service · not a BC · not a component.    ║
║  ~  Contents are depended upon; no boundary exists to enclose.       ║
║  ~~~╪────────────────────────────────────────────╪~~~~~~╪~~~~~~      ║
║     │ A1 criteria (read-only, DR-4)   A3 issuance trigger │ A5       ║
║     ▼   ◄────────────────────────────┘   verdicts │        │         ║
║  ┌────────────────────────────────────────────────────┐    │         ║
║  │  AC-1  KNOWLEDGE ASSESSMENT      [logical container]│   │         ║
║  │  records evidence · evaluates vs criteria · issues │    │         ║
║  │  judgments; holds NO authority/criteria; cannot    │    │         ║
║  │  self-issue; ⚠ one responsibility NOTHING realizes │    │         ║
║  │  ⚠ three contested concepts allocated NOWHERE      │    │         ║
║  │  outbound = closed verdict vocabulary only (DR-8)  │    │         ║
║  └──────────────────────┬─────────────────────────────┘    │         ║
║     ▲                   │ A4 renders from (ONE-WAY, AP-9)   │         ║
║  ~~~~╪~~~~~~~~~~~~~~~~~~│~~~~~~~~~~~~~~~~~~~~~~~~~~~╪~~~~~~~╪~~~~~   ║
║  ~  AR-2  EXPRESSED-KNOWLEDGE REGION (***undefined***) ~      │       ║
║  ~  What the containers act upon; no internal structure ~      │       ║
║  ~~~~~~~~~~~~~~~~~~~~~~~~~~~│~~~~~~~~~~~~~~~~~~~~ A6 ~~~~~~│~~~~~   ║
║                              ▼                            ▼         ║
║  ┌──────────────────────────────────────────────────────────┐       ║
║  │  AC-2  KNOWLEDGE PROJECTION      [logical container]      │       ║
║  │  renders governed knowledge; regenerable; NO source of    │       ║
║  │  truth · NO authority · NO return path · ★ TERMINAL SINK  │       ║
║  │  (DR-1) · ⚠ inherited identity liability                  │       ║
║  └──────────────────────────────────────────────────────────┘       ║
║        ✕  no relationship with Work Management (Separate Ways)       ║
╚══════════════════════════════════════════════════════════════════════╝
    ▲ A8 knowledge obligations (one-way inward; translation unassigned U-2)
 ····  XD-1  WORK MANAGEMENT  [external domain, outside PKS]  ····
 LEGEND: ┌───┐ logical container (NO runtime semantics) · ~~~~ undefined
 region · ──▶ dependency: DIRECTION AND CONTENT ONLY (no mechanism) · ★
 checkable constraint · ⚠ carried gap · ✕ explicit non-relationship
```

*Source: C4-1 §5 (PROMOTED, tier 4). Arrows A1..A8 and the non-relationship ✕ carry direction and content only; no arrow implies synchronicity, messaging, or delivery (AD-1 §9.2, Q-1).*

### 23.2 The implemented capability layer (tier 1, measured this session — class A)

```
┌──────────────────────────────────────────────────────────────────────┐
│  CLI / CI / Git hooks                                                │
│  identifier-check.php · link-check.php · knowledge-lint.php ·        │
│  doc-placement.php · knowledge-graph.php · .github/workflows/…yml ·  │
│  .husky/pre-push → scripts/verify.sh · npm run knowledge-lint[:strict]│
└──────────────┬───────────────────────┬───────────────────────────────┘
               ▼                       ▼
┌─────────────────────────────┐  ┌─────────────────────────────┐
│ Capabilities (vertical slices)│  │ Shared                      │
│ IdentifierIntegrity (CAP-001)│  │ Domain: Verdict (AP-8 closed│
│ ReferenceIntegrity  (CAP-004)│  │   vocabulary) · Assessment · │
│ VocabularyIntegrity (CAP-003)│  │   CheckerVersion · …         │
│ Cohesion (static metrics)    │  │ Application: HandoffReport   │
│  each: Domain · Application  │  │ Infrastructure: CliReporter  │
│  (port) · Infrastructure     │  └─────────────────────────────┘
│  · Tests (36 test files)     │        ▲ no capability knows Shared
└─────────────────────────────┘        └ Shared must not know any
        Direction: CLI→Infra→App→Domain→Shared  (no reverse arrows)
```

---

## 24. Unknowns / Evidence Gaps (stable IDs U-01…)

| ID | Gap | Evidence basis | Why unresolved |
|---|---|---|---|
| **U-01** | Is the PKS corpus **one corpus or three** (OQ-PKS-7)? | M6 §9; AD-1 C-01 | Open, ARB-owned; AR-1's placement is contingent on it |
| **U-02** | **PKS vs EKS identity**: same system, different systems, or overlapping? | this commission's AMENDMENT 4 | Resolved *only* to the extent PKS evidence allows: PKS evidence names no EKS; the EKS evidence is quarantined to Stage 1. Whether the `scripts/observations/` (KnowledgeOS-named) runtime is PKS, EKS, or neither is **UNKNOWN** — it is outside the PKS corpus |
| **U-03** | What is the **Cohesion** capability, and what is its status? | tier 1 code (`Capabilities/Cohesion/`, 21 classes, LCOM4/cohesion-graph) | Not in the Capability Catalog (CAP-001..006); no catalog row; 0 dedicated test files; its governance home and identity are UNKNOWN |
| **U-04** | Does the PKS capability layer belong to PKS or to the "knowledgeos"-labelled work? | tier 1 git log: `feat(knowledgeos): S1..S7`; PKS catalog claims the scripts as its realizations | Naming collision between git feature labels and PKS corpus ownership — see X-06 |
| **U-05** | Who operates the PKS? (no actors) | C4-1 KO-1 | AD-1 derives no actors; two ownership vacuums; no named operator role |
| **U-06** | Where is the **issuance trigger** located? | AD-1 §12 Q-7 | role separation governed; placement not governed |
| **U-07** | Who owns/realizes whole-system conformance? | AD-1 §7; OQ-PKS-3 | ownership vacuum; specified-not-realized |
| **U-08** | What is the **R-70/R-71** citation-damage disposition? | G-10; catalog §0A | "uncurable"; disposition left to Authority |
| **U-09** | Does the **capability lifecycle** ("Active" absent) survive contact with the implemented layer? | G-9; tier 1 code | CAP-001 is implemented yet catalogued DESIGNED⛔unauthorized — lifecycle state of a realized capability is undefined |
| **U-10** | What will **Phase II.D implementation** produce, and what is the status of the 20260802-0015 plan (RED complete, phases 1–2 unauthorized)? | plan; docs/pks | execution authorization split (DA vs ARB) never fully resolved; the code now exists anyway |
| **U-11** | **Persistence of history**: are superseded wordings reconstructable? | AD-1 §11A, C4-1 §6B | artifacts claim reconstruction, not git recovery — provenance disclosed but not independently attested |
| **U-12** | ~~Does the current `.claude/settings.json` (1 allow/1 ask/1 deny) reflect the Runtime Adapter record (19/22/8)?~~ → **CLOSED (erratum P2-F1):** current file measures **19 deny / 22 ask / 8 allow — reflecting the record exactly**. | tier 1 (re-measured) | resolved by HPA review — no longer an unknown |
| **U-13** | Any timing/coupling/delivery mechanism? | AD-1 §9.2 Q-1 | none derivable from the governed model |
| **U-14** | The **identity of PKS itself**: domain? corpus? method? platform? | Architecture Validation Report §1.1 | "Domain" is the governed reading; "corpus" is open (OQ-PKS-7); capability/method/platform are not governed classifications |

---

## 25. Contradictions (stable IDs X-01…)

| ID | Contradiction | Sources | Status |
|---|---|---|---|
| **X-01** | **PKS vs EKS naming overlap**: PKS evidence never names EKS; EKS evidence never names PKS as its identity; yet both live in the same repository and share lexical terms (governance, evidence, observation, verdict). Shared lexical terms do not establish semantic equivalence. | AMENDMENT 4; this session | UNRESOLVED — reported, not reconciled (deliberate) |
| **X-02** | **"PKS is not software" vs the implemented capability layer**: the governing records declare PKS is "knowledge specifications (YAML + Markdown)" and "does not require PHP/Python"; yet the PKS Capability Catalog names PHP scripts as its capability realizations, and the code exists (tier 1). | pks_progress §1.2; Capability Catalog; tier 1 | UNRESOLVED — the docs mean "the product-knowledge model is not software"; the capability layer is software *about* the knowledge. Both statements are true at different levels; no source reconciles them |
| **X-03** | **Live documents updated past their headers**: the Engineering Knowledge Model (2026-08-02) shows CAP-003 "Candidate"; the Capability Catalog §4 (2026-08-21 back-test) shows CAP-003/004/006 REALIZED. | EKM §3 vs catalog §4 | CONTRADICTED — the older doc is stale; both recorded |
| **X-04** | **`DR-n` overloaded**: ARB rulings DR-1..DR-6 vs dependency rules DR-1..DR-8; **`DP-` overloaded**: C4 diagram principles DP-1..DP-7 vs capability domain policies DP-1..DP-6. | Phase I ARB Rulings; AD-1 §10A; C4-1 §3; catalog §7 | known, recorded in corpus; collision persists |
| **X-05** | **Merge-conflict block inside a governed artifact**: the Governance Runtime Adapter record contains both `HEAD` and `56766c1f` variants of §4.1.x and the traceability footer. | tier 1 measured | CONTRADICTED / DEFECT — unmerged text present |
| **X-06** | **Git labels vs PKS corpus ownership**: commits creating the capability layer are `feat(knowledgeos): S1..S7`; the PKS Capability Catalog claims `identifier-check.php`/`link-check.php`/`knowledge-lint.php` as PKS realizations. The same files are simultaneously "knowledgeos" feature work and "PKS" capability work. | tier 1 git log; catalog | UNRESOLVED naming/ownership ambiguity |
| **X-07** | ~~**Permission-adapter counts**~~ → **WITHDRAWN (erratum P2-F1):** the earlier claim that current `.claude/settings.json` has 1 allow/1 ask/1 deny was a block-counting misreading; re-measured counts are **19 deny / 22 ask / 8 allow — matching the record**. | tier 1 re-measurement | **NOT A CONTRADICTION** — record and current file agree |
| **X-08** | **Artifact history provenance**: AD-1 §11A and C4-1 §6B state artifacts are "not under version control," so superseded wordings are "reconstructed, not recovered"; the files are in fact in git (tier 1). | AD-1 §11A; tier 1 | CONTRADICTED — the claim is false if taken literally; the intended meaning (no committed baseline for those exact sections) is plausible |
| **X-09** | **CAP-001 status**: the identifier-validation plan is "AUTHORIZED AND IN EXECUTION — RED COMPLETE" yet "Phases 1–2 proposed and unauthorized"; the catalog calls CAP-001 "DESIGNED ⛔ unauthorized" at 2026-08-02; the code is implemented and committed by 2026-08-21. | plan; catalog; tier 1 | CONTRADICTED — the governance state of the realized capability is undefined |
| **X-10** | **Conformance Core-while-Absent**: Conformance is strategically "Core" (M5) yet operationally "Absent" (T-17); AD-1 encloses it in AC-1 "as a specification for a capability no mechanism performs." | M5; M3; AD-1 §7 | not a contradiction in the corpus's own terms, but an unresolved tension between strategic classification and operational reality |
| **X-11** | **Terminology freeze vs continued minting**: "Terminology FROZEN since M6" (M8) yet the corpus continues to mint new terms and identifiers (CAP-001..006, EAD-1, AD-1 AP/DR, `S1..S5` adapters). | M8 §4; Phase III artifacts | tension — the freeze applies to the strategic model's vocabulary, not to downstream artifact labels; not reconciled by any source |
| **X-12** | **"0 traversals" vs "one closed chain"**: EAD-1 records Operational Evidence at ZERO-INDEPENDENT ("the arrow has never been traversed"), while the Governance Runtime Adapter record and observation chain describe at least one closed governance act (M7-CC1). | EAD-1; runtime adapter record | both are true at different scopes (independent operational evidence vs a single governed act); no source states the boundary |

---

## Traceability and corpus record

- **Sources:** all claims above trace to the §0.4 corpus. Direct tier-1 measurements this session: `scripts/lib/EngineeringKnowledge/**` (tree, 36 test files), `scripts/{identifier-check,link-check,knowledge-lint,doc-placement,knowledge-graph}.php`, `.github/workflows/knowledge-lint.yml`, `.husky/*`, `composer.json` autoload, `.claude/settings.json` (19 deny/22 ask/8 allow, re-measured per erratum P2-F1), `.claude/scripts/*`, `docs/knowledge/schema/*.yaml`, the merge-conflict block in `PKS_Phase_III_Governance_Runtime_Adapter_Record.md`, git log labels.
- **PROMOTED (tier 4, binding) sources:** M0–M8 (M7/M8 promoted), AD-1, C4-1, C4-2 (executed), AFV-1, RET-1, Governance Architecture v1, SDM v1.2/EOP v1.2, M6 Authority Disposition, M6 Consolidation.
- **Excluded (quarantined):** KnowledgeOS Review Set `docs/knowledgeos/architecture/00…07-*`, `docs/knowledgeos/brainstorming/*`, kernel-extraction analyses, the Digitalization Robot, EKS Stage-1 baseline, `scripts/observations/*` (KnowledgeOS-named runtime — outside PKS corpus; reported as present, not used as evidence).

**Quality test — every substantive claim in this document carries (a) an evidence tier, (b) a reality class A–G, or (c) a stable U-/X- register reference.** Where a claim is a direct measurement it is labelled "measured/tier 1"; where it is drawn from a governed artifact it cites the artifact (tier 4). No B/C/G finding is promoted to A. The architectural firewall held: no KnowledgeOS concept was used to fill a PKS gap; the EKS/PKS identity ambiguity is reported (X-01, U-02), not resolved by EKS's meaning.

---

*STOP. Stage 2 / P2 is complete. This baseline awaits Human Principal Architect review. P3 starts only after human review.*
