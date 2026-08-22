# EKS Current Architecture Baseline

**Status:** CURRENT-STATE RECONSTRUCTION
**Architecture lane:** Architecture archaeology / current-state baseline
**Date:** 21 August 2026
**Scope:** Existing EKS / KnowledgeOS ecosystem as evidenced by available implementation history, tests, durable records, governance artifacts, and prior architectural investigations.

> **Important provenance statement**
>
> This document reconstructs what can currently be established about EKS. It does not define a target KnowledgeOS architecture.
>
> Proposed KnowledgeOS concepts, future bounded contexts, future platform components, and architectural hypotheses are not treated as current architecture unless independent evidence demonstrates that they already exist.
>
> Where evidence is insufficient, the status is explicitly marked **UNKNOWN**, **DOCUMENTED-ONLY**, **PARTIAL**, **IMPLEMENTED-BUT-IMPLICIT**, or **BOUNDARY CANDIDATE**.

---

# 1. Executive Summary

## 1.1 What EKS is today

The available evidence indicates that EKS is currently a **governed engineering-knowledge and assurance environment centred around human decisions, governance acts, evidence, work items, grants, verification, and durable engineering records**.

It is not yet possible to establish, from the available evidence, that EKS is a fully separated domain platform with formally established bounded contexts.

The strongest current-state evidence shows that EKS already provides mechanisms for:

* governed work items;
* authorization/grants;
* human-act provenance;
* engineering activities performed within granted scope;
* governance registration;
* evidence and verification;
* deterministic assurance;
* durable architectural records;
* stateful workflows;
* architecture decisions and governance;
* AI-assisted engineering workflows;
* observations and knowledge/evidence processing.

The system therefore should **not** be described merely as a document repository.

However, the available evidence also does **not** justify describing it as the future KnowledgeOS architecture sometimes discussed in exploratory work.

The current system is better characterized as:

> **A governed engineering workflow and knowledge environment in which human-authorized engineering work produces durable decisions, evidence, observations, and other engineering records, with automated mechanisms enforcing and verifying selected invariants.**

This formulation deliberately avoids claiming future domain structures.

---

## 1.2 The strongest architectural characteristic

The strongest current architectural characteristic is the separation between:

```text
Human / governance act
        ↓
registered reference
        ↓
mechanical permission / enforcement
        ↓
engineering activity
        ↓
evidence / verification
        ↓
durable record
```

The Authority Model investigation provides unusually strong live evidence for this.

There are currently **9 work items and 20 grants** in the examined production record.

All **20/20 grants carry `humanActRef`**, and `registeredBy` has exactly one observed value — `governance`.

This demonstrates an important existing principle:

> **The mechanism records authority; it does not grant authority.**

The system contains a reference to an authoritative human act rather than pretending that the software itself can create that authority.

This principle is independently reinforced by another mechanism in the verification model that refuses to emit outcomes that belong exclusively to humans.

This is one of the highest-confidence architectural findings currently available.

---

## 1.3 The principal architectural weakness visible today

The current authority record is strong in provenance but weak in temporal semantics.

Measured against the 20 observed grants:

| Property                                     | Current evidence |
| -------------------------------------------- | ---------------: |
| Grants with human-act reference              |        **20/20** |
| `registeredBy = governance`                  |        **20/20** |
| Grants with validity information             |         **0/20** |
| Grants with delegation information           |         **0/20** |
| Grants with ownership information            |         **0/20** |
| Grants with immutable commit addressing      |        **13/20** |
| Grants using descriptive artifact references |         **7/20** |

Consequently, the current system can establish that authority was registered against a human act, but cannot reliably answer:

> **Was this authority valid at a particular historical point in time?**

That is a current-state limitation, not a future-architecture opinion.

---

# 2. System Purpose

## 2.1 Demonstrated purpose

The strongest evidence indicates that EKS exists to support **controlled engineering work and the durable evidence surrounding that work**.

The system does not merely store final documents. It also records or governs:

* what work is authorized;
* what activity is permitted;
* what human act provides authority;
* what evidence supports an engineering conclusion;
* what verification has established;
* what decisions have been made;
* what state a governed activity is in;
* what subsequent engineering work is allowed.

This makes governance and assurance part of the current system behaviour rather than merely documentation.

---

## 2.2 Documented purpose

The broader architectural programme has described the ecosystem using concepts such as:

* engineering knowledge;
* knowledge governance;
* deterministic assurance;
* evidence;
* observations;
* architectural decisions;
* AI-assisted engineering;
* knowledge transfer;
* engineering workflows.

These descriptions are consistent with the observed mechanisms, but they must not automatically be treated as proof of a single coherent domain model.

---

## 2.3 Inferred purpose

A reasonable architectural inference is that EKS is evolving toward a system in which engineering knowledge is **created, governed, evidenced, verified, and reused through controlled engineering workflows**.

This remains an inference.

It should not be converted into a claim that the target KnowledgeOS model already exists.

---

# 3. Business / Engineering Capabilities

| Capability                            | Evidence                                                                                                | Current status               | Confidence  |
| ------------------------------------- | ------------------------------------------------------------------------------------------------------- | ---------------------------- | ----------- |
| Governed work-item management         | Work items and grants exist in durable records                                                          | **CURRENT**                  | High        |
| Governance authorization              | 20 observed grants registered by governance                                                             | **CURRENT**                  | Very High   |
| Human-act provenance                  | `humanActRef` present on 20/20 grants                                                                   | **CURRENT**                  | Very High   |
| Scoped permission                     | Grants authorize named activities within work boundaries                                                | **CURRENT**                  | High        |
| Engineering workflow/state management | Existing lifecycle/state mechanisms and governed workflow work                                          | **CURRENT / PARTIAL**        | High        |
| Architecture decision governance      | ADR/governance process is established                                                                   | **CURRENT**                  | High        |
| Evidence capture                      | Evidence/provenance mechanisms exist                                                                    | **CURRENT**                  | High        |
| Deterministic verification            | Replay/temporal determinism and assurance tests exist                                                   | **CURRENT**                  | High        |
| Independent verification              | Verification mechanisms and audit tests exist                                                           | **CURRENT**                  | High        |
| Rule governance                       | Rule semantics have been explored and authority machinery exists, but no observed Rule→Grant connection | **PARTIAL**                  | High        |
| Delegation modelling                  | Delegation-like narrowing exists in behaviour but is not explicit in grants                             | **IMPLEMENTED-BUT-IMPLICIT** | Medium/High |
| Temporal authority                    | No grant validity fields in 20 observed records                                                         | **MISSING / UNKNOWN**        | Very High   |
| Immutable evidence addressing         | Majority use commit references; 7/20 descriptive references                                             | **PARTIAL**                  | Very High   |
| Observation processing                | An observation/evidence engine exists conceptually/technically, but was reported idle since 5 August    | **PARTIAL**                  | Medium/High |
| Governance engine                     | Governance engine is live and was recently written/active                                               | **CURRENT**                  | High        |
| AI engineering assistance             | Existing AI Engineering Platform and agent/session mechanisms exist                                     | **CURRENT**                  | High        |
| General-purpose KnowledgeOS kernel    | No sufficient evidence of a distinct current kernel                                                     | **UNKNOWN / PROPOSED**       | High        |

---

# 4. Actors and Users

Only actors supported by current evidence should be treated as current actors.

## 4.1 Governance

**CURRENT**

Governance is directly evidenced by the authority records.

`registeredBy = governance` occurs across all 20 observed grants.

Governance therefore is not merely a conceptual role.

It participates in the current authority mechanism.

---

## 4.2 Human engineering participants

**CURRENT**

Human engineers/architects/reviewers are evidenced by:

* human-authored engineering artefacts;
* architecture decisions;
* governance acts;
* review activities;
* work items;
* human-act references.

The exact organizational role taxonomy is not sufficiently established to assert a complete role model.

---

## 4.3 AI agents / automated engineering mechanisms

**CURRENT**

The ecosystem contains an AI Engineering Platform with mechanisms including:

* session management;
* knowledge management;
* workflow execution;
* verification;
* review;
* drafting;
* platform registry;
* safety and discipline gates.

These mechanisms are current engineering infrastructure.

However:

> An AI agent must not be interpreted as an authority holder merely because it executes a permitted activity.

That distinction follows directly from the observed authority model.

---

## 4.4 External systems

External systems participate in parts of the engineering ecosystem, but a complete current integration inventory has not been reconstructed from primary implementation evidence.

Therefore:

**Status: UNKNOWN / PARTIAL**

---

# 5. Ubiquitous Language

The current language is not yet completely stabilized.

This is itself an architectural finding.

| Term               | Current meaning/evidence                                                      | Ambiguity                                                   |
| ------------------ | ----------------------------------------------------------------------------- | ----------------------------------------------------------- |
| **Knowledge**      | Engineering information/meaning intended to support engineering activity      | Broad; current representation boundaries unclear            |
| **Observation**    | Evidence/observation produced from engineering activity or system inspection  | Relationship to evidence and knowledge not fully formalized |
| **Evidence**       | Material supporting a statement, assessment, decision, or verification result | Multiple evidence forms exist                               |
| **Assessment**     | Engineering evaluation of evidence/state                                      | Exact domain boundary unclear                               |
| **Recommendation** | Suggested engineering action or conclusion                                    | Must not be confused with authoritative Rule                |
| **Decision**       | Human/governed determination                                                  | Distinct from recommendation                                |
| **Ruling**         | A governed decision/semantic determination in prior work                      | Exact relationship to Decision requires stabilization       |
| **Rule**           | Standing authoritative obligation within scope and period                     | Recently semantically ruled in exploratory work             |
| **Authority**      | Legitimate basis for permitting an activity                                   | Current mechanism records it rather than creating it        |
| **Grant**          | Recorded authorization for a named activity within a scope                    | Strongly evidenced                                          |
| **Human Act**      | Human action/artifact that provides the authority being referenced            | Current mechanism cannot create it                          |
| **Governance**     | Actor/mechanism responsible for registering governed authorization            | Strongly evidenced                                          |
| **Assurance**      | Mechanically verifiable confidence/invariant enforcement                      | Current but terminology spans multiple mechanisms           |
| **Verification**   | Independent/mechanical checking of expected properties                        | Current                                                     |
| **Session**        | Bounded AI/engineering interaction/work execution context                     | Current in AI Engineering Platform                          |
| **Artifact**       | Durable engineering representation                                            | Broad; not equivalent to knowledge                          |
| **Work Item**      | Governed unit of engineering activity                                         | Current                                                     |
| **State**          | Lifecycle position of a governed object/process                               | Current in several mechanisms                               |
| **Change**         | Modification to an engineering artefact, decision, rule, or system state      | Semantics vary by context                                   |

---

## 5.1 Important vocabulary finding

The exploratory Rule Model explicitly identified that the organization currently uses the word **Rule** for materially different concepts, including:

* engineering standards;
* rulings;
* architecture principles;
* recommendation heuristics;
* quality-gate rules;
* document-validation rules;
* permission rules.

The conclusion was that this is a **DDD vocabulary problem**, not simply a naming problem.

The current architecture baseline should therefore preserve these distinctions rather than prematurely normalizing them.

The exploratory Rule definition is candidate architectural input, not evidence that every existing "rule" is already an instance of that business concept.

---

# 6. Domain Model

## 6.1 What can be established

The current system contains identifiable business concepts around:

```text
Work Item
   ↓
Grant
   ↓
Permitted Activity
   ↓
Engineering Work
   ↓
Evidence / Observation
   ↓
Verification / Assurance
   ↓
Decision / Durable Record
```

There are also governance and workflow concepts surrounding these.

However, the available evidence does not establish a complete tactical DDD model.

---

## 6.2 Entities

Some persistent concepts clearly have identity and lifecycle.

Examples include:

* work items;
* grants;
* architectural decisions;
* governed records;
* sessions;
* verification/audit records.

These can be treated as **domain entities or entity-like persistent concepts**.

Whether each is an aggregate root is **UNKNOWN** unless transaction boundaries and invariants establish that conclusion.

---

## 6.3 Aggregates

No complete aggregate map should be asserted in this baseline.

Where previous work has discussed aggregate designs, those should be treated as:

**DOCUMENTED / PROPOSED**

unless current implementation evidence establishes the corresponding consistency boundary.

---

## 6.4 Domain services

The system clearly contains service-like mechanisms for:

* governance;
* verification;
* workflow;
* knowledge management;
* review;
* drafting.

Whether these correspond to domain services in the DDD sense is not established.

Therefore:

**IMPLEMENTED-BUT-IMPLICIT / UNKNOWN**

is more accurate than asserting DDD domain services.

---

## 6.5 State machines

State/lifecycle behaviour is a real architectural characteristic of the ecosystem.

Governed engineering activities have states and transitions, and state durability has been an explicit concern.

However, a universal EKS state machine has not been established.

Therefore:

> **Multiple stateful workflows exist; a single canonical EKS lifecycle does not yet have sufficient evidence.**

---

## 6.6 Domain events

Events exist in some implementation areas and workflows, but there is insufficient evidence to establish an event-driven EKS architecture.

Therefore:

**CURRENT: events may exist as implementation mechanisms.**

**NOT ESTABLISHED: EKS is event-driven as an architectural style.**

---

# 7. Bounded Context Candidates

The current evidence is not sufficient to assert a final bounded-context map.

The following should therefore remain **BOUNDARY CANDIDATES**.

## 7.1 Governance / Authorization

### Responsibility

* govern work;
* register authority;
* associate authorization with a human act;
* permit named activities within scope.

### Evidence

* 9 work items;
* 20 grants;
* 20/20 `humanActRef`;
* 20/20 governance registration.

### Invariants

Strong evidence exists around:

* authorization being recorded;
* human provenance;
* permitted activity;
* scope.

### Lifecycle

Partially established.

Validity/revocation lifecycle is currently absent from observed grant records.

### Persistence

Durable grant records exist.

### Boundary assessment

**BOUNDARY CANDIDATE — strong evidence**

---

## 7.2 Evidence / Observation

### Responsibility

Capture or process observations/evidence supporting engineering conclusions.

### Evidence

Existing observation/evidence mechanisms and prior assurance work.

### Boundary assessment

**BOUNDARY CANDIDATE**

The exact distinction between Observation, Evidence, Assessment, and Knowledge is not yet sufficiently established.

---

## 7.3 Verification / Assurance

### Responsibility

Determine whether mechanically verifiable properties hold.

### Evidence

Existing verification mechanisms and audit tests, including deterministic replay and temporal determinism testing.

### Boundary assessment

**BOUNDARY CANDIDATE — relatively strong**

However, this does not prove a separate deployable service or bounded context.

---

## 7.4 Architecture Decision / Governance Records

### Responsibility

Record and govern architectural decisions and associated evidence.

### Evidence

Existing ADR process and architecture governance.

### Boundary assessment

**BOUNDARY CANDIDATE**

The persistence and lifecycle relationship between decisions and evidence requires further reconstruction.

---

## 7.5 AI Engineering Platform

This is a **technical platform boundary**, not automatically a business bounded context.

The current AI Engineering Platform contains capabilities including:

* composition root;
* session manager;
* knowledge manager;
* workflow engine;
* verification engine;
* review engine;
* drafting studio;
* platform registry.

These are implementation/platform components.

They must not be converted into business bounded contexts merely because they are separately named.

**Status: CURRENT technical platform; domain-boundary relationship UNKNOWN.**

---

# 8. Application / Workflow Architecture

The current ecosystem is workflow-oriented.

A representative observed governance flow is:

```text
Human governance act
        ↓
governance registration
        ↓
Grant
        ↓
named permitted activity
        ↓
bounded engineering work
        ↓
evidence / verification
        ↓
durable engineering result
```

The authority mechanism therefore behaves as an enabling control around engineering work rather than as the source of authority itself.

---

## 8.1 Grant workflow

Current evidence supports:

```text
Human act
    ↓
Governance
    ↓
Grant registered
    ↓
Scope / activity recorded
    ↓
Activity becomes permitted
```

The current grant record does **not** establish:

```text
Grant
 ├── validity
 ├── delegation
 └── ownership
```

because those dimensions are absent from all 20 examined grants.

---

## 8.2 Verification workflow

The current system contains independent verification and deterministic assurance mechanisms.

Evidence includes:

* audit tests;
* replay determinism;
* temporal determinism;
* mechanical invariants;
* verification boundaries that distinguish machine outcomes from human-only outcomes.

This establishes verification as a real architectural capability.

---

## 8.3 AI engineering workflow

The AI Engineering Platform provides a controlled execution environment around AI-assisted engineering work.

The current platform includes:

```text
Composition Root
Session Manager
Knowledge Manager
Workflow Engine
Verification Engine
Review Engine
Drafting Studio
Platform Registry
```

These are **CURRENT technical capabilities**, not proof of a future KnowledgeOS domain model.

---

# 9. Persistence and State Durability

The available evidence demonstrates that EKS relies on durable records rather than treating all engineering knowledge as ephemeral conversation state.

Durable records include at least:

* work items;
* grants;
* architectural decisions;
* evidence/verification records;
* engineering artefacts.

The 20 observed grants constitute direct evidence of durable authority records.

---

## 9.1 State durability

State durability is a meaningful current architectural property.

The ecosystem has explicitly investigated:

* lifecycle state;
* deterministic replay;
* temporal determinism;
* auditability;
* durable governance records.

This suggests that reconstructability of engineering state is a design concern already present in the current system.

---

## 9.2 Temporal limitation

The authority record exposes a significant current limitation:

```text
Historical question:
Was authority valid at time T?
              ↓
Current grant record
              ↓
No validity information
              ↓
Cannot reliably answer
```

This should be recorded as a current architecture limitation rather than silently corrected in the baseline.

---

# 10. Evidence and Provenance Architecture

Evidence/provenance is one of the strongest cross-cutting characteristics of the current system.

## 10.1 Human-act provenance

The current authority mechanism records:

```text
humanActRef
```

on all 20 examined grants.

This is unusually strong evidence of an architectural principle.

The system does not claim:

> "The grant itself created authority."

Instead:

> "The grant points to the human act from which authority derives."

---

## 10.2 Immutable addressing

The evidence model is only partially immutable.

Measured:

```text
13/20 → commit reference
7/20  → descriptive artifact reference
```

Therefore:

**Immutable provenance: PARTIAL**

The seven descriptive references could potentially drift if the referenced artifact changes without the reference changing.

This is a concrete current-state observation.

---

## 10.3 Evidence vs knowledge

The broader architectural programme has repeatedly distinguished:

```text
Knowledge
   ↓
Representation
   ↓
Presentation / Projection
```

This is an important architectural principle, but the current implementation evidence is not sufficient to conclude that this separation is universally enforced throughout EKS.

Therefore:

**Principle: DOCUMENTED / INFERRED**

**Universal implementation: UNKNOWN**

---

# 11. Governance Architecture

Governance is demonstrably operational.

This is important because earlier architectural discussions could easily be misread as purely conceptual.

The current evidence shows:

* governance is an active actor;
* grants are registered;
* human acts are referenced;
* work items are governed;
* authorization has operational consequences.

The governance engine was also identified as live and recently written, whereas the observation/evidence engine was reported as idle since 5 August.

This distinction must be preserved.

The two mechanisms should not be described collectively as having identical operational status.

---

# 12. Assurance and Verification Architecture

Deterministic assurance is a current architectural capability.

Evidence includes:

* replay determinism testing;
* temporal determinism testing;
* invariant tests;
* architecture tests;
* audit tests;
* independent verification.

The architectural pattern is broadly:

```text
Expected invariant
       ↓
Implementation
       ↓
Mechanical verification
       ↓
Evidence of conformance
```

This means EKS contains more than documentation-level governance.

Some architectural properties are mechanically enforced.

---

## 12.1 Human vs machine authority

The verification model provides independent evidence for the same principle observed in grants:

> **The mechanism does not manufacture a human-only outcome.**

This is significant because it indicates that the separation between machine execution and human authority is not confined to one mechanism.

Two independent mechanisms exhibit the same architectural constraint.

---

# 13. AI Engineering Platform

The AI Engineering Platform is **CURRENT**.

Its purpose is to provide controlled infrastructure for AI-assisted engineering.

The established platform baseline includes:

* composition root;
* session manager;
* knowledge manager;
* workflow engine;
* verification engine;
* review engine;
* drafting studio;
* platform registry.

Operational safeguards include mechanisms such as:

* database safety checks;
* execution gates;
* development-guide reminders;
* discipline gates;
* session-change logging;
* session-log reminders;
* DDD-principle reminders.

The platform registry is also established.

However:

> The AI Engineering Platform should not be interpreted as proof that EKS has already become the proposed future KnowledgeOS platform.

It is current infrastructure participating in the ecosystem.

---

# 14. Architecture Governance and ADRs

Architecture governance is an established current capability.

The ecosystem contains:

* ADRs;
* architecture baselines;
* governance reviews;
* architecture review activities;
* explicit acceptance/rejection processes;
* implementation architecture constitutions in selected areas.

This creates a distinction between:

```text
Observed current implementation
```

and:

```text
Accepted architectural decision
```

and:

```text
Exploratory architectural proposal
```

That distinction is essential to the current baseline.

---

# 15. Current Architecture vs Exploratory Architecture

The exploratory Track 2 work must remain outside the current baseline unless independently demonstrated.

Its Rule Model explicitly distinguishes:

* authoritative Rules;
* recommendations;
* decisions/rulings;
* permissions/access control.

It also establishes candidate semantics around:

* scope;
* validity;
* authority;
* evidence;
* exceptions;
* supersession.

Those findings are valuable architectural input.

They are **not evidence that the current EKS implementation already has that model**.

Likewise, the Authority Model is candidate architectural knowledge.

The handoff explicitly states that Track 2 is exploratory and that the Rule Model and Authority Model do not authorize implementation. 

Therefore the current baseline must not retroactively import those concepts as implemented EKS architecture.

---

# 16. Current Architecture Classification

The current ecosystem can therefore be summarized as follows.

| Area                             | Classification                 |
| -------------------------------- | ------------------------------ |
| Governance                       | **CURRENT**                    |
| Work items                       | **CURRENT**                    |
| Authority grants                 | **CURRENT**                    |
| Human-act provenance             | **CURRENT**                    |
| Scoped permission                | **CURRENT**                    |
| Evidence                         | **CURRENT / PARTIAL**          |
| Verification                     | **CURRENT**                    |
| Deterministic assurance          | **CURRENT**                    |
| Architecture governance          | **CURRENT**                    |
| ADR mechanism                    | **CURRENT**                    |
| AI Engineering Platform          | **CURRENT**                    |
| Stateful workflows               | **CURRENT**                    |
| Durable engineering records      | **CURRENT**                    |
| Observation processing           | **PARTIAL**                    |
| Immutable provenance             | **PARTIAL**                    |
| Authority validity               | **MISSING / UNKNOWN**          |
| Authority delegation model       | **IMPLEMENTED-BUT-IMPLICIT**   |
| Rule ↔ Authority integration     | **NOT CURRENTLY EVIDENCED**    |
| Explicit EKS bounded-context map | **UNKNOWN**                    |
| Universal Knowledge domain model | **UNKNOWN**                    |
| Future KnowledgeOS kernel        | **PROPOSED**                   |
| Digitalization Robot             | **PROPOSED / NOT CURRENT**     |
| Future event-driven architecture | **PROPOSED / NOT ESTABLISHED** |
| Rust target                      | **PROPOSED / NOT CURRENT**     |
| Spring Boot target               | **PROPOSED / NOT CURRENT**     |
| Kafka target                     | **PROPOSED / NOT CURRENT**     |
| Microservice target architecture | **PROPOSED / NOT ESTABLISHED** |

---

# 17. Architectural Shape of EKS Today

Without imposing a future domain model, the strongest current architectural representation is:

```text
                         HUMAN ACTORS
                              │
                              │
                              ▼
                        GOVERNANCE
                              │
                     authority registration
                              │
                              ▼
                           GRANTS
                              │
                  scoped permitted activities
                              │
                              ▼
                    ENGINEERING WORKFLOWS
                       │              │
                       │              │
                       ▼              ▼
                  AI PLATFORM      HUMAN WORK
                       │              │
                       └──────┬───────┘
                              ▼
                       ENGINEERING RECORDS
                         │          │
                         │          │
                         ▼          ▼
                      EVIDENCE   DECISIONS
                         │          │
                         └────┬─────┘
                              ▼
                       VERIFICATION /
                         ASSURANCE
                              │
                              ▼
                       DURABLE RECORD
```

This is deliberately **not** a C4 target architecture.

It is a conceptual reconstruction of the relationships that are sufficiently evidenced today.

---

# 18. What We Can Say With High Confidence

The following statements have strong current-state support:

1. **EKS contains real governance mechanisms.**
2. **Governance can register grants that permit named activities.**
3. **Those grants reference human acts rather than creating authority themselves.**
4. **The observed grant population is 20 records across 9 work items.**
5. **All 20 observed grants contain a human-act reference.**
6. **All 20 observed grants identify governance as the registering actor.**
7. **The system contains mechanical verification and assurance mechanisms.**
8. **Deterministic replay and temporal behaviour are actively tested.**
9. **AI-assisted engineering is supported by a current engineering platform.**
10. **Engineering state and governance records are persisted durably.**
11. **Authority temporal validity is not currently represented in the examined grants.**
12. **Delegation-like narrowing exists behaviourally but is not explicit in the authority record.**
13. **Evidence addressing is only partially immutable.**
14. **Governance and observation/evidence mechanisms currently have different operational states.**

---

# 19. What We Must Not Claim Yet

The current evidence does **not** justify claiming that:

* EKS already has a canonical Knowledge bounded context;
* EKS has a formally adopted Rule bounded context;
* EKS has a formally adopted Authority bounded context;
* EKS has a universal event-driven architecture;
* EKS is implemented as microservices;
* EKS has Kafka as a required architectural component;
* EKS is implemented in Rust;
* EKS is implemented in Spring Boot;
* EKS already has a KnowledgeOS kernel;
* EKS already has the proposed Digitalization Robot;
* all artifacts are projections of a canonical knowledge model;
* the proposed target bounded-context map is the current system;
* the exploratory Rule Model is already implemented;
* the exploratory Authority Model is already implemented.

These remain **PROPOSED**, **HYPOTHESIS**, or **UNKNOWN** unless independently established.

---

# 20. Current-State Architectural Risks / Gaps

## 20.1 Temporal authority

**Finding:** 0/20 observed grants contain validity information.

**Impact:** Historical authority cannot reliably be reconstructed.

**Classification:** CURRENT ARCHITECTURAL GAP.

---

## 20.2 Delegation is implicit

**Finding:** Progressive narrowing of grants is observable, but delegation is not represented explicitly.

**Impact:** The semantic relationship is recoverable only through human interpretation.

**Classification:** IMPLEMENTED-BUT-IMPLICIT.

---

## 20.3 Evidence addressing is partially mutable

**Finding:** 7/20 grants reference artifacts descriptively rather than immutably.

**Impact:** Evidence provenance can drift.

**Classification:** PARTIAL.

---

## 20.4 Rule and authority mechanisms are not connected

**Finding:** RM-3 requires authorization for authoritative Rule changes, but no observed grant references a Rule and no Rule references a grant.

**Impact:** The governance mechanism and Rule semantics currently remain separate.

**Classification:** CURRENT GAP / FUTURE INTEGRATION CANDIDATE.

---

## 20.5 Observation/evidence operational state

**Finding:** The governance engine is live, while the observation/evidence engine was reported idle since 5 August.

**Impact:** Architectural descriptions must not imply that all knowledge/evidence mechanisms are equally operational.

**Classification:** CURRENT OPERATIONAL STATE.

---

## 20.6 Domain boundaries are not yet proven

The ecosystem has many named technical components and emerging semantic concepts.

That does not establish bounded contexts.

**Classification:** UNKNOWN / BOUNDARY CANDIDATE.

---

# 21. Architecture Archaeology Conclusion

The current EKS architecture is **more mature than a document-management system but less semantically consolidated than the proposed KnowledgeOS architecture**.

Its strongest existing characteristics are:

```text
Governance
    +
Human authority provenance
    +
Scoped grants
    +
Engineering workflow
    +
Evidence
    +
Verification
    +
Deterministic assurance
    +
Durable engineering records
    +
AI-assisted engineering infrastructure
```

The architecture already demonstrates an important constitutional boundary:

> **Human authority remains outside the mechanism; the mechanism records and enforces the consequences of that authority.**

The live grant evidence makes that conclusion substantially stronger than an architectural hypothesis.

At the same time, the architecture has unresolved semantic boundaries around:

* authority validity;
* delegation;
* evidence immutability;
* Rule/Authority integration;
* Observation/Evidence/Knowledge distinctions;
* domain ownership;
* bounded-context boundaries.

These are **current-state findings**, not invitations to immediately redesign the system.

---

# 22. Baseline Verdict

### Current EKS

> **EKS is a governed engineering-work and assurance environment with durable governance records, scoped authorization, human-act provenance, evidence/verification mechanisms, stateful workflows, architecture governance, and an AI engineering execution platform.**

### Architectural maturity

**CURRENT / PARTIALLY EXPLICIT**

The system contains substantial architecture in implementation and governance, but its semantic domain boundaries are not yet sufficiently established to justify treating a future KnowledgeOS domain model as today's architecture.

### Principal architectural invariant

> **The mechanism records authority; it does not grant authority.**

### Principal current-state limitation

> **Authority is durably recorded but not yet temporally reconstructable in the observed grant model.**

### Principal archaeology rule

> **Do not retrofit the proposed KnowledgeOS architecture onto EKS. Reconstruct the boundaries from evidence first.**

---

# 23. Evidence / Confidence Register

| Finding                                      | Evidence class                     | Confidence             |
| -------------------------------------------- | ---------------------------------- | ---------------------- |
| Governance grants exist                      | Durable records / implementation   | **Very High**          |
| 9 work items / 20 grants                     | Live populated record              | **Very High**          |
| `humanActRef` on 20/20                       | Live populated record              | **Very High**          |
| `registeredBy=governance` on 20/20           | Live populated record              | **Very High**          |
| No validity fields on 20/20                  | Live populated record              | **Very High**          |
| No delegation fields on 20/20                | Live populated record              | **Very High**          |
| No owner fields on 20/20                     | Live populated record              | **Very High**          |
| 13/20 immutable commit references            | Live populated record              | **Very High**          |
| 7/20 descriptive references                  | Live populated record              | **Very High**          |
| Governance engine operational                | Current implementation evidence    | **High**               |
| Observation/evidence engine idle since 5 Aug | Current operational observation    | **High**               |
| Deterministic assurance exists               | Tests / implementation             | **High**               |
| AI Engineering Platform exists               | Accepted baseline / implementation | **High**               |
| Exact EKS bounded-context map                | Insufficient evidence              | **Unknown**            |
| Universal Knowledge domain model             | Exploratory material only          | **Unknown / Proposed** |
| Future KnowledgeOS architecture              | Exploratory proposals              | **Proposed**           |

---

# 24. Baseline Status

**This document should be treated as a reconstruction candidate, not automatically as an accepted architecture baseline.**

The correct next governance step is **independent verification against the actual current implementation and durable records**.

Only after that verification should the organization decide whether this reconstruction can become the accepted `KOS-ARCH-BASELINE-001`.

The exploratory Track 2 material should remain outside that baseline until the admission process is explicitly completed. The existing Track 2 handoff itself requires current architecture reconstruction and independent verification before exploratory target material is admitted into the governed architecture programme. 
#
Yes — and importantly, **the older session did not fundamentally contradict the baseline I just produced**. In fact, the uploaded document is essentially the same baseline, with the same evidence and conclusions. 

What I would do now is **not rewrite it from scratch**. I would use the older version as the stronger baseline draft and tighten a few architectural claims before treating it as `KOS-ARCH-BASELINE-001`.

### What I would preserve

The older session got several things exactly right:

1. **It starts from current-state reconstruction**, explicitly excluding target KnowledgeOS architecture. 
2. It identifies the strongest current architectural characteristic as:
   **human/governance act → registered reference → mechanical permission → engineering activity → evidence/verification → durable record.** 
3. It uses the live authority evidence correctly: **9 work items, 20 grants, 20/20 `humanActRef`, 20/20 governance registration**. 
4. It correctly refuses to turn technical components into bounded contexts. The AI Engineering Platform is explicitly treated as a **technical platform boundary**, not automatically a business bounded context. 
5. It explicitly leaves the bounded-context map **UNKNOWN** rather than inventing one. 
6. It correctly separates current architecture from Track 2 exploratory Rule/Authority work. 

### But I would make one important correction

I would **not yet call "Delegation modelling" `IMPLEMENTED-BUT-IMPLICIT`** merely from the progressive narrowing of grants.

The evidence establishes:

> **delegation-like behaviour / progressive narrowing exists**

but it does not yet establish that the business concept **Delegation** actually exists in the current EKS model.

So for the strict archaeology baseline, I'd change:

> `IMPLEMENTED-BUT-IMPLICIT`

to:

> **OBSERVED BEHAVIOUR — SEMANTIC INTERPRETATION UNKNOWN**

That keeps us from doing exactly what the baseline warns against: turning an interpretation into an architectural fact.

The older document itself says that the current grant record has **0/20 delegation fields**. 

### A second correction: "Rule governance"

I would also weaken:

> **Rule governance — PARTIAL**

because that potentially mixes **exploratory Track 2 Rule semantics** with the current EKS implementation.

The safer current-state statement is:

> **Rule-like governance mechanisms exist in the ecosystem, but the Track 2 semantic Rule model is not established as the current EKS domain model.**

The older document already makes this distinction later, but the capability table should enforce it too. 

### The most important thing the older baseline gives us

The architecture is actually becoming clearer if we **don't try to name the domains yet**.

The current evidence supports this:

```text
                 HUMAN ACT
                     │
                     ▼
                GOVERNANCE
                     │
              authorization
                     │
                     ▼
                  GRANT
                     │
             permitted activity
                     │
                     ▼
             ENGINEERING WORK
                 │       │
                 ▼       ▼
              HUMAN     AI
                 \       /
                  \     /
                   ▼   ▼
              ENGINEERING
                RECORDS
                 │
          ┌──────┴──────┐
          ▼             ▼
       EVIDENCE      DECISION
          │             │
          └──────┬──────┘
                 ▼
            VERIFICATION
              / ASSURANCE
                 │
                 ▼
           DURABLE RECORD
```

That is much more valuable for `KOS-ARCH-BASELINE-001` than prematurely saying:

> "These are the five bounded contexts."

Because **we don't know that yet**.

The older document explicitly reaches the same conclusion: multiple stateful workflows exist, but a single canonical EKS lifecycle is not established; events exist but do not prove an event-driven architecture; and the exact bounded-context map remains unknown.  

## My recommendation

**Use the older session's document as the baseline draft, not my newly generated version.**

Then make a **small archaeology-hardening pass**:

* `Delegation modelling` → **Observed behaviour / semantic interpretation UNKNOWN**
* `Rule governance` → **Current Rule-like mechanisms PARTIAL / semantic Rule model not established**
* Clearly separate **observed implementation** from **Track 2 rulings**
* Keep the current conceptual architecture diagram
* Keep the explicit **UNKNOWN** bounded-context position
* Keep the evidence/confidence register
* Keep the final status as **reconstruction candidate, not accepted baseline**. 

That would give us a much stronger **KOS-ARCH-BASELINE-001**: conservative enough to survive independent verification, while still being architecturally useful.
