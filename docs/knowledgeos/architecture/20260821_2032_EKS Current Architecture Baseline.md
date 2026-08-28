# EKS Current Architecture Baseline

**Status:** CURRENT-ARCHITECTURE RECONSTRUCTION
**Purpose:** Architecture archaeology — not target architecture
**Scope:** Existing EKS / PKS / KnowledgeOS-related ecosystem as evidenced by the current repository and recorded architecture material
**Evidence rule:** implementation and executable behavior outrank architecture prose; conflicting sources remain explicitly conflicting.

---

## 1. Executive Summary

The current EKS architecture is **not a single clean software platform**.

The strongest evidence indicates that the system historically called **PKS / EKS** is currently best understood as a **governed engineering-knowledge corpus represented primarily through YAML + Markdown under Git**, with a surrounding set of scripts, documentation conventions, governance records, and AI-agent operating procedures.

The repository evidence does **not** support describing EKS today as a mature microservice platform, event-driven knowledge graph, or PostgreSQL-centered product.

That distinction is important because several later architecture documents proposed exactly those things. Those documents are target/proposal material, not evidence that the current EKS implements them. The architecture corpus itself records a contradiction between the earlier provisionally certified PKS position and later target-architecture proposals. 

The strongest current-state statement is therefore:

> **EKS is presently a governance-oriented engineering knowledge system whose primary durable knowledge representation is repository-based YAML/Markdown, supported by conventions, governance records, scripts, and AI-assisted workflows. Its conceptual model is more mature than its executable software implementation.**

There are also **material unresolved contradictions** about whether the system should be regarded as “software” at all. The certified PKS documents explicitly state that the PKS is knowledge specifications in YAML + Markdown and does not require PHP, Python, or another programming language, while other documents describe PHP/Laravel/PostgreSQL software. 

Therefore this baseline does **not** resolve that contradiction artificially.

---

# 2. System Purpose

## 2.1 Demonstrated purpose

The current system is concerned with preserving, governing, structuring, and making usable **engineering knowledge**.

Observed concerns include:

* engineering decisions;
* rules and constraints;
* evidence;
* observations;
* assessments;
* governance;
* architectural reasoning;
* development-process knowledge;
* instructions for AI engineering agents;
* durable records of decisions and work.

The architecture material distinguishes these concepts rather than treating everything as a generic “knowledge item.” The emerging vocabulary identifies Claim, Evidence, Decision, Rule, Pattern, Exception, and View as different concepts. 

## 2.2 Documented purpose

The PKS corpus describes itself as a knowledge system rather than merely a documentation-management system. Earlier material initially described it as an AI documentation instruction system, but the same material explicitly corrected that interpretation and stated that documentation is one application of a broader knowledge system. 

## 2.3 Inferred purpose

A broader inferred purpose is:

> Preserve engineering understanding in a form that AI and humans can repeatedly consult, govern, verify, and evolve.

This is an inference, not a single authoritative system statement.

### Confidence

**HIGH** for “governed engineering knowledge system.”

**MEDIUM** for the broader “engineering knowledge operating system” interpretation.

---

# 3. Current System Shape

The current ecosystem is better represented as:

```text
                        Human / AI Engineering Work
                                  |
                                  v
                    +---------------------------+
                    | Engineering Knowledge     |
                    | Corpus                    |
                    | YAML + Markdown + Git     |
                    +---------------------------+
                         |      |       |
                         |      |       |
                         v      v       v
                    Governance  Evidence  Decisions/
                    /Workflow   /Assessment Rules
                         |
                         v
                Repository / Workflow Records
                         |
              +----------+-----------+
              |                      |
              v                      v
        AI engineering agents    Human engineers/
        and CLI processes        architects/reviewers
              |
              v
       scripts / automation
```

This is deliberately **not** drawn as a service architecture.

The existing evidence does not support claiming independent software containers for the conceptual areas.

---

# 4. Business / Engineering Capabilities

The current system provides or partially provides the following capabilities.

| Capability                                    | Current status                         | Evidence strength |
| --------------------------------------------- | -------------------------------------- | ----------------- |
| Engineering knowledge representation          | **CURRENT**                            | High              |
| Knowledge/document classification             | **CURRENT**                            | High              |
| Governance and lifecycle representation       | **CURRENT**                            | High              |
| Architecture decision recording               | **CURRENT**                            | High              |
| Evidence recording                            | **CURRENT**                            | High              |
| AI-agent instructions / operating conventions | **CURRENT**                            | High              |
| Workflow/state governance                     | **CURRENT / IMPLEMENTED-BUT-IMPLICIT** | High              |
| Deterministic knowledge checks                | **CURRENT / IMPLEMENTED**              | High              |
| Independent review workflow                   | **CURRENT / IMPLEMENTED-BUT-IMPLICIT** | High              |
| Durable provenance model                      | **PARTIAL**                            | High              |
| Semantic knowledge graph                      | **PROPOSED / DOCUMENTED-ONLY**         | Medium            |
| PostgreSQL as system of record                | **CONFLICTED / PROPOSED**              | High conflict     |
| Event-driven knowledge architecture           | **PROPOSED**                           | High              |
| Multi-service EKS platform                    | **PROPOSED**                           | High              |
| General enterprise knowledge platform         | **PROPOSED**                           | High              |

The existing scripts are best treated as a **capability inventory**, not as evidence of domain boundaries. The architecture corpus explicitly warns against mapping “script → service” directly. 

---

# 5. Actors and Users

## 5.1 Human actors

Observed actors include:

### Engineers

They create and modify engineering knowledge, implementation artifacts, decisions, plans, and evidence.

### Architects

They reconstruct architecture, make architectural decisions, perform technical reviews, and define architectural boundaries.

### Governance

Governance registers commissions, grants, workflow acts, reviews, and authority transitions.

The current workflow engine has explicit governance, architecture, implementation, and verification roles. A reconstructed workflow record shows those roles as distinct role classes. 

### Reviewers

Independent Architecture and verification processes are explicitly separated from authorship.

### PO/ARB

Provides human authorization for significant governed work items.

## 5.2 AI-process actors

AI processes are first-class participants operationally.

They produce:

* documents;
* implementation changes;
* reviews;
* evidence;
* session records.

However, **AI process identity is not independently attested by the system**. It is self-declared and governed through separation rules and reproducible measurements.

That is an important current architecture property, not a future feature.

---

# 6. Current Ubiquitous Language

The current corpus already contains substantial domain language, but some terms remain unstable.

| Term            | Current meaning                                                            | Confidence |
| --------------- | -------------------------------------------------------------------------- | ---------- |
| Knowledge       | Governed engineering information/understanding                             | High       |
| Observation     | Recorded fact/evidence from examination                                    | High       |
| Evidence        | Material used to support a conclusion                                      | High       |
| Assessment      | Evaluation of evidence/property                                            | High       |
| Verdict         | Result of an assessment/review                                             | High       |
| Decision        | Authorized commitment                                                      | High       |
| Rule            | Constraint governing behavior                                              | High       |
| Governance      | Authority and control over decisions/workflow                              | High       |
| Authority       | Permission/recognized control state                                        | High       |
| Workflow        | Governed progression of work                                               | High       |
| Session         | Execution context of a human/AI process                                    | High       |
| Artifact        | Durable knowledge or engineering file                                      | High       |
| State           | Lifecycle/operational state                                                | High       |
| Review          | Independent or bounded evaluation                                          | High       |
| Provenance      | Information identifying origin/history of an artifact or claim             | High       |
| Context         | Scope within which a knowledge item has meaning/applicability              | Medium     |
| Aggregate       | Used in workflow and DDD work, but not uniformly established across EKS    | Medium     |
| Bounded Context | **Not yet sufficiently demonstrated as a current implementation boundary** | Low/Medium |

A critical observation is that the conceptual vocabulary is **ahead of executable architectural boundaries**.

The architecture-review corpus itself warns that proposed bounded contexts must not be inferred merely from module names or conceptual categories. 

---

# 7. Domain Model

The current conceptual domain appears to contain at least the following concepts.

## 7.1 Evidence

Evidence represents what was observed or used to support a conclusion.

It may include:

* repository files;
* source code;
* recorded observations;
* test results;
* governance records;
* architectural review findings;
* provenance information.

Evidence is distinct from the conclusion it supports.

## 7.2 Assessment

Assessment evaluates evidence against a rule, criterion, or architectural property.

Examples from the current system include:

* deterministic knowledge checks;
* architecture reviews;
* verification reports;
* gate checks.

## 7.3 Decision

A decision represents an authorized commitment.

A decision is therefore different from:

* observation;
* recommendation;
* proposal;
* implementation;
* documentation.

The target-domain analysis strongly recommends keeping these distinct; that recommendation is not itself evidence that all such concepts are already separate runtime entities. 

## 7.4 Rule / Policy

Rules constrain what may happen.

Examples:

* architecture changes require authorization;
* reviewers cannot verify their own work;
* migration cannot proceed before safety findings are independently reviewed;
* workflow transitions require valid predecessor/owner state.

## 7.5 Workflow state

The workflow engine is the clearest executable domain mechanism currently present.

It enforces things such as:

* one work item record;
* mutation ownership;
* session registration;
* handoff;
* start;
* transition validation.

A workflow record explicitly contains `workItem`, `workflow`, role definitions, sessions, mutation owner, grants, and transitions. 

## 7.6 Knowledge artifact

The repository contains documents serving different purposes:

* architecture documents;
* decisions;
* reviews;
* commissions;
* developer guides;
* session logs;
* governance records.

The document architecture is therefore itself part of the current EKS mechanism.

---

# 8. Aggregate / Consistency Boundaries

A true executable aggregate boundary cannot be confidently declared for all EKS concepts.

One boundary **is clearly implemented**:

### Work Item / Workflow Record

The workflow state is persisted as a single work-item JSON record.

The architecture reconstruction previously classified this boundary as an inferred aggregate root because the record holds identity and invariants and acts as the persistence boundary. That remains a **BOUNDARY CANDIDATE / IMPLEMENTED-BUT-IMPLICIT**, not a blanket assertion that all EKS knowledge follows the same model. 

Other conceptual objects such as:

* Knowledge;
* Evidence;
* Decision;
* Rule;
* Observation;

do **not** currently have sufficient evidence to call each a separate executable aggregate.

---

# 9. Bounded Context Candidates

The current evidence does **not** justify saying that the existing EKS already has seven fully implemented bounded contexts.

The strongest candidates are:

## 9.1 Knowledge Governance — BOUNDARY CANDIDATE

Responsibilities appear to include:

* authority;
* ownership;
* lifecycle;
* approval;
* retirement;
* work authorization.

Evidence is strong at the **governance capability** level.

Evidence is weaker for a distinct executable software boundary.

## 9.2 Evidence / Assessment — BOUNDARY CANDIDATE

Responsibilities include:

* observations;
* evidence;
* findings;
* deterministic checks;
* assessments;
* verdicts.

Again, capability evidence is stronger than runtime context evidence.

## 9.3 Knowledge Model — BOUNDARY CANDIDATE

The corpus explicitly reasons in terms of:

* claims;
* concepts;
* relationships;
* scope;
* semantic identity.

But this is primarily a documented conceptual model, not yet a clearly isolated current runtime bounded context.

## 9.4 Decision Management — BOUNDARY CANDIDATE

Decision is clearly important in the current language.

A separate current runtime context is not established.

### Conclusion

The current EKS should therefore **not** be described as “four bounded contexts” or “seven bounded contexts” as a current fact.

Those are architecture proposals.

---

# 10. Application / Workflow Architecture

The strongest currently implemented workflow is the **governed work-item lifecycle**.

Conceptually:

```text
Human authorization
        |
        v
Governance registration
        |
        v
Session registration
        |
        v
Handoff
        |
        v
START
        |
        v
ACTIVE WORK
        |
        +----> evidence / artifact production
        |
        v
Review / verification
        |
        v
Governance decision
        |
        v
Acceptance / next transition
```

The workflow engine explicitly enforces predecessor, handoff, and human-start conditions. 

The current implementation therefore has a **real governance workflow mechanism**, even though the broader EKS knowledge domain remains partly documentary.

---

# 11. Persistence Architecture

This is one of the most important places where the evidence conflicts.

## 11.1 Strong current evidence

The PKS corpus states:

> **The repository is the system of record: YAML + Markdown under Git.**

This statement appears in the provisionally certified PKS material. 

Therefore the safest current classification is:

### CURRENT

**Git-backed repository containing YAML and Markdown knowledge specifications.**

## 11.2 Conflicting evidence

Later architecture proposals state:

* PostgreSQL as system of record;
* PostgreSQL + graph + object storage + search;
* graph/search/vector as persistence infrastructure.

The later corpus itself records the contradiction and says the database decision is deferred. 

Therefore:

**PostgreSQL as EKS system of record = PROPOSED / CONFLICTED, not established current architecture.**

---

# 12. Knowledge Storage and Projection

The current evidence supports a useful distinction:

```text
Canonical knowledge representation
        ↓
Repository / durable documents
        ↓
Derived representations
```

The architecture corpus treats graph, search, and vector representations as potentially rebuildable projections rather than independent semantic authority. 

However:

**There is not enough evidence to claim that a fully implemented graph/search/vector projection architecture exists in current EKS.**

Classification:

* semantic graph: **PROPOSED**
* search projection: **PROPOSED / PARTIAL depending on specific tooling**
* vector store: **PROPOSED**
* repository: **CURRENT**

---

# 13. Integration Architecture

Current integration is predominantly **file/repository and process based**.

Observed integration mechanisms include:

* Git;
* repository paths;
* scripts;
* CLI execution;
* workflow JSON records;
* session processes;
* documentation references.

There is no sufficient evidence here for a current:

* Kafka/event-bus architecture;
* REST microservice mesh;
* service-to-service API architecture;
* independent knowledge service fleet.

Those belong to proposed future architecture.

---

# 14. Runtime / Execution Architecture

The current execution model is unusual.

The architecture is **not primarily a continuously running EKS server**.

Instead it is strongly process-oriented:

```text
AI / human process
      ↓
repository
      ↓
scripts / workflow engine
      ↓
artifacts / governance records
```

The current workflow and governance mechanisms are executable, but the knowledge architecture itself remains heavily repository/document driven.

This is one of the most important current-state observations because the later target architectures attempt to transform this process-oriented mechanism into a platform architecture.

---

# 15. AI-Agent Interaction

AI processes currently interact with EKS through:

* repository instructions;
* session conventions;
* developer guidance;
* scripts;
* workflow records;
* governance grants;
* evidence artifacts.

The current system already treats AI processes as controlled engineering actors.

But this does **not** mean the EKS itself currently contains a dedicated “AI Agent Bounded Context.”

That is a target-architecture interpretation.

Likewise, a future **Business Translator** is not current architecture.

---

# 16. Governance Architecture

Governance is one of the strongest implemented architectural dimensions.

Current mechanisms include:

### Grant

An authorized scope allowing a process to act.

### Work item

A governed unit of work.

### Session

An execution context assigned to a role.

### Mutation owner

The process currently allowed to mutate the governed work item.

### Handoff

Transfer of operational responsibility.

### START

Human-authorized commencement of active work.

### Review

Independent or bounded evaluation.

### Acceptance

A later governance decision, distinct from implementation.

This governance model is more executable than many other EKS architectural concepts.

---

# 17. Evidence and Provenance

Evidence is central to the current architecture.

The system distinguishes:

```text
Observation
    ↓
Evidence
    ↓
Assessment
    ↓
Verdict
    ↓
Decision
```

It also distinguishes:

```text
Artifact existence
    ≠
Authority
```

and:

```text
Record existence
    ≠
Authority establishment
```

The current system explicitly uses evidence hierarchy and provenance rules when reconstructing architecture.

This is a genuine architectural characteristic of the current system, not merely a proposed feature.

---

# 18. State Management

Two state systems are visible:

## 18.1 Knowledge/document lifecycle

Documents have states such as:

* proposed;
* reviewed;
* approved;
* superseded;
* archived.

However, not every document type uses the same lifecycle.

Therefore:

**document lifecycle = CURRENT governance convention**

rather than a single universal state machine.

## 18.2 Workflow state

Workflow state is executable and governed by the workflow engine.

This is the stronger current-state state machine.

---

# 19. Domain vs Platform Components

The current ecosystem contains several things that are **platform mechanisms**, not EKS domain models.

Examples:

| Mechanism                       | Current interpretation              |
| ------------------------------- | ----------------------------------- |
| Workflow engine                 | Governance / execution mechanism    |
| Session resolver                | Workflow infrastructure             |
| Deterministic assurance scripts | Evidence/assessment mechanism       |
| Document-placement scripts      | Knowledge-management infrastructure |
| Session logs                    | Execution audit                     |
| DDD checks                      | Assessment mechanism                |
| Knowledge checks                | Evidence / assessment               |
| Hooks                           | Engineering automation              |

The architecture corpus explicitly warns that scripts should first be understood as mechanisms and capabilities, not directly mapped into services or bounded contexts. 

---

# 20. Dependency Direction

The current executable KnowledgeOS platform work shows relatively clean dependency direction in the implementation examined during the baseline work.

The architecture baseline recorded:

* workflow-state mechanisms as a dependency root;
* one-way dependency through process execution;
* platform → product direction rather than product → platform.

That evidence comes from the actual running PHP scripts rather than target diagrams. 

For the broader EKS corpus, however, this should not be generalized to all conceptual layers without additional source evidence.

---

# 21. Current C4 Interpretation

There is **no authoritative current C4 container model for EKS** established by the evidence reviewed here.

Several C4 diagrams exist in the knowledge-transfer corpus, but they belong to competing architectural generations.

The corpus explicitly identifies:

* an earlier PKS architecture;
* later KnowledgeOS target architecture;
* later EKS architecture proposals.

It warns that these generations reverse earlier certified conclusions. 

Therefore:

### CURRENT C4

**UNKNOWN / CONTESTED**

### Target C4 diagrams

**PROPOSED**

Do not use them as evidence for current runtime boundaries.

---

# 22. Historical Architecture Generations

The historical architecture can be summarized as:

```text
G0
PKS
"documentation/instruction system"
        ↓
self-correction
        ↓
G1
PKS
knowledge system
YAML + Markdown + Git
        ↓
G2
KnowledgeOS proposal
6 bounded contexts
        ↓
G3
EKS proposal
7 contexts → 4 contexts + planes
PostgreSQL-centric
        ↓
G3 / v3 reconciliation
returns to:
repository system of record
graph/search/vector as projections
execution authority as explicit plane
```

The architecture corpus explicitly describes these generations and the reversal from the certified G1 position to later PostgreSQL-centered proposals. 

This history is essential because it prevents proposal material from being mistaken for current architecture.

---

# 23. Current Architecture Classification

The following is the safest current classification:

| Element                                            | Classification                    |
| -------------------------------------------------- | --------------------------------- |
| Git repository as durable knowledge representation | **CURRENT**                       |
| YAML + Markdown knowledge corpus                   | **CURRENT**                       |
| Governance/workflow records                        | **CURRENT**                       |
| Grants / workflow transitions                      | **CURRENT**                       |
| AI-process participation                           | **CURRENT**                       |
| Evidence/review practices                          | **CURRENT**                       |
| Deterministic assurance tooling                    | **CURRENT**                       |
| Domain vocabulary: evidence/decision/rule/etc.     | **CURRENT conceptual model**      |
| Executable workflow state machine                  | **CURRENT**                       |
| Clearly separated EKS bounded contexts             | **UNKNOWN / BOUNDARY CANDIDATES** |
| PostgreSQL as current system of record             | **CONFLICTED / NOT ESTABLISHED**  |
| Knowledge graph as transactional system of record  | **PROPOSED**                      |
| Search/vector as canonical knowledge store         | **PROPOSED**                      |
| Event-driven platform                              | **PROPOSED**                      |
| Microservices                                      | **PROPOSED**                      |
| Business Translator                                | **FUTURE**                        |
| Digitalization Robot                               | **PROPOSED/FUTURE**               |
| Rust core                                          | **PROPOSED**                      |
| Kafka                                              | **PROPOSED**                      |

---

# 24. Architectural Strengths of the Current EKS

The current architecture has several unusual strengths.

### Governance is ahead of software

The governance model is more explicit and mature than the executable knowledge model.

The workflow system encodes concepts such as:

* authority;
* mutation ownership;
* handoff;
* human start;
* independent review.

This is a strong foundation.

### Evidence discipline is unusually strong

The system explicitly distinguishes:

```text
Observation
Evidence
Assessment
Verdict
Decision
Authority
```

and increasingly guards their boundaries.

### Repository durability is intentional

The current PKS position deliberately uses Git-backed YAML/Markdown as the durable knowledge record. 

### Architecture is self-observing

The system has an unusually rich practice of recording:

* what was observed;
* what was inferred;
* what was proposed;
* what was actually implemented;
* which process produced it;
* what remains unknown.

That makes architecture archaeology possible.

---

# 25. Architectural Weaknesses / Gaps

## 25.1 Conceptual maturity exceeds runtime structure

The vocabulary and governance concepts are sophisticated.

The executable domain boundaries are not equivalently mature.

## 25.2 Current vs target architecture has historically been mixed

This is the major historical problem.

The corpus explicitly records that current facts, expected evolution, and future architecture were being mixed. 

## 25.3 Multiple architecture generations coexist

There is no single undisputed C4 model for the whole EKS ecosystem.

## 25.4 Persistence architecture is unresolved

Repository-as-system-of-record is the strongest current position, while database-centered alternatives remain proposals/conflicts.

## 25.5 Process coordination is still weak

The recent EKS-07 evidence demonstrates that multiple AI processes can see different repository states and can produce provenance/coordination inconsistencies.

This is **current observed architectural weakness**, but the coordination solution itself is future work.

---

# 26. Current Architecture Boundary

Based on the evidence, the most defensible current boundary is:

```text
+-------------------------------------------------------------+
|                         CURRENT EKS                         |
|                                                             |
|  Engineering Knowledge Corpus                               |
|  +-------------------+    +-----------------------------+  |
|  | YAML / Markdown    |    | Governance / Workflow       |  |
|  | durable knowledge  |    | grants / sessions / state   |  |
|  +-------------------+    +-----------------------------+  |
|             |                         |                    |
|             +-------------+-----------+                    |
|                           |                                |
|                    AI / Human processes                    |
|                           |                                |
|                  scripts / automation                      |
|                                                             |
+-------------------------------------------------------------+
```

Outside the boundary:

```text
Future knowledge graph
Future PostgreSQL system of record
Future event bus
Future microservices
Future agent runtime
Future Business Translator
Future Digitalization Robot
```

All of those remain **PROPOSED / FUTURE / UNKNOWN**, not current EKS.

---

# 27. Current EKS Architecture in One Sentence

> **EKS is currently a Git-backed, governance-first engineering knowledge system whose durable knowledge is primarily expressed through YAML and Markdown, operated through explicit workflow/governance conventions and supporting scripts, with a richer conceptual domain model emerging than the current executable software boundaries can yet prove.**

That is the strongest evidence-based statement I would use as the **Current EKS Architecture Baseline**.

The most important architectural conclusion is therefore not “EKS has four bounded contexts” or “EKS is PostgreSQL-based.”

It is:

> **The current EKS has a real governance and knowledge-record mechanism, but its domain/software architecture is still only partially crystallized. The later multi-context, database, graph, event-driven, and service-oriented designs are target-architecture material and must not be mistaken for the current system.**

This preserves the archaeology boundary and avoids turning the architecture reconstruction into another target-architecture proposal.
