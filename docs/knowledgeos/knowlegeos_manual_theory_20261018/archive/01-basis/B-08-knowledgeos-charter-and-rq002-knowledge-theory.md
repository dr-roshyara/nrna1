# B-08 — The KnowledgeOS charter stack and the RQ-002 knowledge theory (pre-corpus origin)

| Field | Value |
|---|---|
| Read (full, 12 files, `docs/implementation/`) | `KnowledgeOS_Product_Discovery_Charter.md` · `KnowledgeOS_Development_Constitution.md` · `KnowledgeOS_Governance_Execution_Plan.md` · `Project_Knowledge_Architecture_Charter.md` · `Project_Knowledge_Strategic_Model.md` · `RQ-002_Project_Knowledge_Domain.md` (charter) · `RQ-002_Research_Findings_Raw.md` · `RQ-002_Strategic_Discovery_Report.md` · `RQ-002_Architectural_Synthesis.md` · `RQ-002_Knowledge_Taxonomy.md` · `RQ-002_Knowledge_Meta_Model.md` · `RQ-002_General_Knowledge_Architecture_Constitutional_Model.md` |
| Date evidence | git first add: RQ-002 chain and Project Knowledge **2026-07-11 17:00 +0200** (`d7e9763b0`) · Product Discovery Charter **2026-07-12 21:21** (`cbc6cd7f2`) · Development Constitution and Execution Plan **2026-07-28** (in-text: drafted 2026-07-27). All **before** the KnowledgeOS corpus (Aug–Sep 2026) |
| Standing | not in the corpus registry (0 rows). These are the **original founding documents of KnowledgeOS-as-product** and of the **knowledge theory that preceded the corpus** |
| Labels | [SRC] stated in the source · [I] interpretation · [?] open |

---

## 1. Why KnowledgeOS was proposed (the product charter, 2026-07-12)

| # | Statement [SRC] | Anchor |
|---|---|---|
| C-1 | **Working hypothesis (to be validated or falsified):** *"there is a market for an AI-native Knowledge Operating System for software engineering whose core promise is preserving architectural knowledge, enforcing engineering governance, and enabling AI agents to reason consistently across complex software systems — and the governance/knowledge machinery built in this repository is evidence the approach works."* | Charter, header |
| C-2 | **The problem to be tested in Stage 1:** *architectural-knowledge loss* — **departing seniors, stale ADRs, AI ignoring standards, drifting docs, inconsistent reviews** | Charter, Stage 1 |
| C-3 | **Target segments (hypothesis):** software companies · consultancies · enterprise architecture · regulated industries | Charter, Stage 1 |
| C-4 | **"KnowledgeOS" is a placeholder, not a decision;** the concept is called "AI Knowledge Platform"; naming is a business-model-stage output | Charter, header |
| C-5 | **Five kill-gated stages:** Product Discovery → Market Validation → Domain Discovery → Business Model → Architecture. *"Finding 'no viable product here' is a success of the process, not a failure"* | Charter |
| C-6 | **Honest limit:** this repository is *"a single project's evidence that governance-first AI engineering can work — one retrospective data point, zero market data points"*: an existence proof, not validation | Charter, constraint 3 |
| C-7 | **Status:** PROPOSED; *"nothing in this charter executes until approved"*; a third track, outside both the Engineering Platform and the PublicDigit backlog | Charter |

## 2. What KnowledgeOS is meant to be (Development Constitution, 2026-07-27; PROPOSED · INERT)

| # | Statement [SRC] | Anchor |
|---|---|---|
| D-1 | **Mission:** an **"Evidence-Governed Engineering Knowledge Operating System":** *"a system that helps organizations govern how AI participates in engineering work through governed engineering knowledge, evidence, methodologies, and organizational decision processes."* | Mission |
| D-2 | **"AI is a capability inside the system. It is not the system."** | Mission |
| D-3 | **Knowledge is not truth:** knowledge is *"proposed · researched · qualified · adopted · revised — never 'true'… KnowledgeOS governs engineering knowledge, not engineering truth"* | Part 1 |
| D-4 | **Decision process (no state skipped):** Observation → Evidence → Research → Candidate → Qualification → Decision → Adoption → Implementation → Operational Evidence → Revision | §2.2 |
| D-5 | **Methodologies are modular:** registered, qualified, activated when appropriate, retired; *"never inject entire methodologies"* | §2.10 |
| D-6 | **AI context principle:** the AI receives *"only the minimum governed context necessary"* | §2.11 |
| D-7 | **Rules:** Challenge (a well-evidenced "no change" is success) · Capability Discovery · Inheritance (*"inherit before extending"*) · Discovery (*"products evolve through customer evidence"*) · Uncertainty (*"'Evidence not found' is a complete and respectable answer"*) · Anti-Expansion | §2.3–2.13 |
| D-8 | **Every rule tagged `[PLATFORM]` (bound from the Engineering Platform) or `[PRODUCT]`.** The lineage annex shows nearly all rules are platform rules **bound, not invented** | Annex |
| D-9 | **Execution plan:** activation gates G-1 (charter approval) and G-2 (adoption act). Finding **GEP-F1**: binding lifecycle ≠ programme stage lifecycle | Execution Plan |

## 3. The knowledge theory before the corpus: RQ-002 (2026-07-11)

### 3.1 How it was produced [SRC]

- A research charter: *"How is project knowledge created, validated, evolved, discovered, consumed, challenged, forgotten, and retired?"* plus *"what is the smallest unit of project knowledge?"*
- Role: *"think like a researcher, not an architect."*
- Litmus: *"if Markdown were removed completely, would the finding still exist?"*
- Four parallel executors across seven disciplines: organizational knowledge, KM, cognitive science, information architecture, software engineering/DDD, AI, information quality.
- About 120 sources, 55 normalized knowledge models, 33 negative findings, 35 contradictions (Raw Findings; Discovery Report).

### 3.2 The General Knowledge Architecture Constitutional Model (the theory) [SRC]

Status: *"ACCEPTED AS RESEARCH (ARB) · RESEARCH FREEZE 2026-07-11: complete enough to be falsified."*

**Scope:** *constitutional within software project knowledge*; cross-industry validity *"untested"*.

```
KNOWLEDGE SPACE = NATURE × REPRESENTATION × GOVERNANCE-STATUS ( × AUTHORITY-SCOPE )
     lifecycle regime  = f(Nature)          validation method = f(Representation, Nature)
```

| Concept | Definition (condensed) |
|---|---|
| **Nature** | Decision · Definition · Constraint/Rule · Description-of-state · Finding/Experience · Measurement/Observation (· Skill, tacit-only) |
| **Representation** | Executable · Recorded · Conversational/Social · Tacit/Embodied |
| **Governance status** | Draft/Candidate · Canonical · Superseded/Historical · Deprecated (with reason) · Archived |
| **Authority scope** (provisional) | Context-local · Project · Platform · Constitutional |
| **Knowledge Claim** | **the atomic unit:** content + Coordinate + Provenance + Status + Scent (four-way convergence: nanopublication · knowledge claim · smallest true span · decision-or-definition) |
| **Knowledge Instance** | an artifact at a coordinate: a single claim, or a composite governed claim by claim |
| **Knowledge Flow / Transition / Query / Specialization** | flows = nature derivations + representation transitions, closed by **consumption feedback**; queries select regions (*Purpose* lives in the query, not the instance); specializations = **weights, never new rules** |

**The invariants:**
1. Knowledge never silently disappears.
2. Status transitions are explicit, reasoned, and visible at discovery.
3. Provenance is mandatory.
4. Contradictions remain visible (they coexist at rest and are resolved at consumption).
5. Authority is scoped; nothing claims global truth.
6. Freshness is observable at discovery.
7. Supersession is a link, never an edit.
8. No knowledge capability may create a second-population artifact class.
9. Knowledge quality is evidenced, never asserted.
10. Representation transitions are deliberate and preserve nature and provenance.
11. **Telos:** *"Knowledge exists only to satisfy Knowledge Needs. Documentation is merely one possible representation."*

**Lifecycle (a cycle, not a pipeline):** Creation → Qualification → Publication → Discovery & Consumption → Evolution (regime = f(Nature)) → Supersession → Historical Retention → Staged Retirement; *"validation-is-use"* closes the loop.

**Principles PK-P1…P10:**
- same-population;
- status-at-discovery;
- claim granularity;
- execution proximity;
- two-regime evolution (decisions immutable and superseded; descriptions evolve);
- minimal sufficient context;
- coexistence with status;
- staged forgetting;
- succession over capture;
- scent.

**Appendix B:** the Engineering Platform's artifacts decompose into the space *"with no residue and no new dimensions"*; *"the Engineering Platform is the first implemented bounded context of this constitutional model"*. The platform *"independently converged"* on invariants 1, 2, 7 and 9 before the research existed.

### 3.3 The Project Knowledge specialization (2026-07-11) [SRC]

- **Mission:** *"How does project knowledge become available to an engineer or AI exactly when needed, with the minimum sufficient context, while remaining canonical, traceable, and evolvable?"*
- **Central act:** **Context Assembly**, in this chain: Task → **Knowledge Need** → concepts → predicate → discovery over live sources → contradiction surfacing → **Working Context** (minimal, statuses visible, provenance, **ephemeral**) → validation by use → feedback capture.
- **Knowledge Need lifecycle:** expressed → assembly attempted → **satisfied | unsatisfiable** → superseded. *"Unmet needs accumulate as first-class evidence of knowledge gaps."*
- **Context invariant:** *"Project Knowledge never becomes a copy of the project."*
- **Structure:** Engineering Platform and Project Knowledge are **sibling bounded contexts under one General Knowledge Constitution**, serving the Product Domain.
- **Recorded ARB observations:**
  - **"Context Assembly as kernel-domain candidate"** of the knowledge ecosystem;
  - a **"fourth theory: Knowledge Evolution"** watch: *Knowledge Need → Working Context → Context Evaluation → Gap Evidence → Knowledge Improvement*;
  - *"The model is now rich enough to generate architectural rules, but not yet rich enough to require them."*

## 4. What this changes in the basis

**[I] The origin is now documented, with dates (git):**

```
2026-07-07/08  Engineering Platform baseline (governance practice; B-01/B-02)
2026-07-11     RQ-002: literature-based research on project knowledge
               → General Knowledge Constitution (Knowledge Space, 11 invariants, lifecycle)
               → Project Knowledge Strategic Model (Knowledge Need, Context Assembly)
               → "the Engineering Platform is the first implemented bounded context"
2026-07-12     KnowledgeOS Product Discovery Charter (market hypothesis; "KnowledgeOS" a placeholder)
2026-07-27/28  Development Constitution + Execution Plan (Evidence-Governed Engineering Knowledge OS);
               EKS Reference Model; Engineering Theory Reference
2026-08-01     OQ-5 raised: is KnowledgeOS the Engineering Platform?
2026-08 … 09   the KnowledgeOS corpus (kernel, K, Zero/Gap, invariants, lenses), read so far only second-hand
```

**[I] Hypotheses revised:**
- **H-B1 (generalized engineering governance) is too narrow.**
  - The knowledge theory has **two documented sources**: a literature research programme (RQ-002) **and** the platform's practice, which RQ-002 records as *independent convergence*.
  - Revised **H-B1″**: *KnowledgeOS theory = RQ-002's General Knowledge Constitution, with the Engineering Platform as its first instance, offered as a product.* Supported by explicit statements (§3.2 Appendix B; charter C-1).
- **H-B3 (governance → general knowledge theory → product → corpus):** the first three links are now **documented**. The last link (to the corpus theory) is **untested** until the corpus is read.

**[I] Leads for the corpus reading (to be verified against the corpus; not claims):**

| Corpus construct (second-hand) | Possible pre-corpus antecedent |
|---|---|
| "the smallest state that contains everything the kernel must know" (step 285); the **kernel** | **minimal sufficient context** (PK-P6) and "Context Assembly as kernel-domain candidate" |
| **Gap(K, EC)** = unmet requirements of an epistemic contract; **Zero** = no gap | **Knowledge Need** satisfied / *unsatisfiable → gap evidence*; telos invariant 11 |
| AX-1 non-collapse (Evidence ≠ Knowledge, Claim ≠ Truth…) | the Nature dimension + "knowledge, not truth" (D-3) |
| AX-4 non-monotonicity; AX-5 provenance | invariants 1, 3, 7; two-regime evolution |
| H-ZERO-001 (UNKNOWN preserved) | invariants 2 and 4; Uncertainty Rule (D-7) |
| Conceptual Foundation dimension model | Knowledge Space dimensions |

**[?] Open:**
- whether the corpus theory **continues**, **replaces** or **diverges from** the RQ-002 constitution;
- OQ-5 (which KnowledgeOS is meant);
- whether the KnowledgeOS charter was ever approved (no approval record found in what was read).
