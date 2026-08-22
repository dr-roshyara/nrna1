# EKS Current Architecture Baseline

**Document type:** Current Architecture Reconstruction
**Architecture stance:** Evidence-first / architecture archaeology
**Status:** Reconstruction baseline — not a target architecture
**Authority:** Descriptive baseline; no future architecture is implied
**Scope:** Existing EKS / Engineering Knowledge System and its relationship to KnowledgeOS, AI Engineering Platform, PKS and surrounding engineering assets

---

# 1. Executive Summary

This document reconstructs the **current architecture of the Engineering Knowledge System (EKS)** as it exists in the accumulated implementation, documentation, governance records, verification mechanisms and architectural history.

The most important architectural conclusion is:

> **EKS should not currently be understood as a single conventional application or as a conventional bounded-context-oriented business system. It is better understood as an engineering knowledge and assurance ecosystem composed of persistent engineering knowledge, governance mechanisms, workflow mechanisms, deterministic verification, observation streams, AI-assisted engineering capabilities, and repository-local tooling.**

The current system has evolved incrementally rather than having been introduced as one completely specified architecture.

Consequently, several architectural layers are clearly implemented while their conceptual boundaries remain partially implicit.

The current architecture contains, with varying maturity:

* engineering knowledge and methodology;
* architectural governance;
* ADRs and architectural baselines;
* discovery and design records;
* deferred architectural decisions;
* engineering observations;
* deterministic quality measurements;
* recommendation generation;
* decision and outcome recording;
* assessment/effectiveness mechanisms;
* developer-facing observation triggers;
* commit-time and file-save feedback;
* AI-assisted engineering workflows;
* an AI Engineering Platform;
* repository-local bootstrap and diagnostic tooling;
* verification scripts and architectural guards;
* persistent provenance and evidence records.

The system is therefore best reconstructed as a **knowledge-and-assurance system surrounding software engineering activity**, rather than as another business application.

This distinction is essential.

EKS does not primarily execute the business capabilities of PublicDIGIT. Instead, it observes and governs the engineering process through which systems such as PublicDIGIT are designed, implemented, verified and evolved.

---

# 2. Scope and Architectural Boundary

## 2.1 What this baseline means by EKS

For this reconstruction:

> **CURRENT EKS means the architecture that can be supported by existing implementation, tests, persistence, accepted governance records, architecture documentation and demonstrated workflows.**

The following are therefore not automatically considered part of current EKS merely because they have appeared in architectural discussions:

* future KnowledgeOS kernel concepts;
* future bounded contexts;
* Digitalization Robot;
* future event-driven architecture;
* Kafka;
* Rust;
* Spring Boot;
* microservice decomposition;
* future Business Translator;
* future commercial platform structures;
* future reusable cross-repository distribution;
* speculative AI capabilities.

Those concepts may appear in historical notes, but they are explicitly classified as:

**PROPOSED**, **HYPOTHESIS**, **FUTURE**, or **UNKNOWN**.

---

# 3. Evidence Model

The architecture reconstruction follows the following evidence hierarchy:

| Priority | Evidence                                 | Architectural weight |
| -------- | ---------------------------------------- | -------------------- |
| 1        | Existing implementation/source code      | Highest              |
| 2        | Executable tests and enforced invariants | Very high            |
| 3        | Persistence and durable records          | Very high            |
| 4        | Accepted ADRs and governance decisions   | High                 |
| 5        | Existing architecture documentation      | Medium-high          |
| 6        | C4 / PlantUML / diagrams                 | Medium               |
| 7        | Proposals and brainstorming              | Low                  |
| 8        | Historical contextual knowledge          | Lowest               |

This produces an important rule:

> A documented architecture that is not implemented is not current architecture.

Likewise:

> An implemented capability whose conceptual boundary has not been documented is still current architecture, but it must be classified as **IMPLEMENTED-BUT-IMPLICIT**.

---

# 4. Architectural Identity of EKS

## 4.1 What EKS is

The current evidence supports the following characterization:

> **EKS is an engineering knowledge and assurance ecosystem that captures engineering knowledge, observes engineering changes and behaviour, produces deterministic assessments and recommendations, records decisions and outcomes, and feeds evidence back into engineering governance.**

Its central concern is therefore not merely software quality.

It is the transformation:

```text
Engineering Activity
        ↓
Evidence
        ↓
Observation
        ↓
Assessment / Recommendation
        ↓
Human Decision
        ↓
Engineering Outcome
        ↓
Effectiveness Evidence
        ↓
Knowledge
        ↓
Future Engineering Activity
```

This loop is one of the strongest architectural characteristics of the current system.

---

# 5. Architectural Layers

The current architecture can be reconstructed into the following logical layers.

```text
┌───────────────────────────────────────────────────────────────┐
│                    Engineering Governance                     │
│                                                               │
│ ADRs · Architecture Baselines · Deferred Decisions · Rules   │
└───────────────────────────────┬───────────────────────────────┘
                                │
                                ▼
┌───────────────────────────────────────────────────────────────┐
│                  Engineering Knowledge                        │
│                                                               │
│ Methodology · DDD · Architecture · Decisions · Evidence      │
└───────────────────────────────┬───────────────────────────────┘
                                │
                                ▼
┌───────────────────────────────────────────────────────────────┐
│                Engineering Workflow                           │
│                                                               │
│ Recommendation → Decision → Outcome → Assessment             │
└───────────────────────────────┬───────────────────────────────┘
                                │
                                ▼
┌───────────────────────────────────────────────────────────────┐
│                  Observation Runtime                           │
│                                                               │
│ Trigger → ChangeSet → Runtime → Collectors → Recommendations │
└───────────────────────────────┬───────────────────────────────┘
                                │
                                ▼
┌───────────────────────────────────────────────────────────────┐
│             Developer / Engineering Activity                  │
│                                                               │
│ Edit · Save · Commit · Review · Test · Merge                  │
└───────────────────────────────────────────────────────────────┘
```

These layers should not automatically be interpreted as separate deployable components or bounded contexts.

They are **architectural responsibilities**.

---

# 6. System Purpose

## 6.1 Demonstrated purpose

The implemented system demonstrates a capability to:

* record engineering knowledge;
* record architectural decisions;
* maintain engineering governance;
* observe engineering changes;
* calculate deterministic engineering metrics;
* generate recommendations;
* expose recommendations to developers;
* record decisions;
* record outcomes;
* assess effectiveness;
* maintain provenance;
* use accumulated evidence to inform future engineering decisions.

## 6.2 Documented purpose

The wider architectural documentation positions the system as a mechanism for turning engineering experience into durable organizational knowledge.

This is broader than conventional static documentation.

The system attempts to preserve:

```text
What happened?
Why did it happen?
What decision was made?
What was changed?
What was the result?
Did the intervention work?
What can we learn from it?
```

## 6.3 Inferred purpose

The strongest architectural inference is that EKS is attempting to create an **organizational engineering memory**.

This inference is supported by:

* durable decision records;
* observation streams;
* assessment history;
* architectural baselines;
* governance records;
* deferred architecture registers;
* session history;
* evidence/provenance;
* effectiveness measurements.

This should nevertheless remain classified as an **architectural interpretation**, not as a newly invented domain statement.

---

# 7. Engineering Capabilities

The following capabilities can be reconstructed from the current system.

| Capability                           | Evidence basis                                     | Status            | Confidence |
| ------------------------------------ | -------------------------------------------------- | ----------------- | ---------- |
| Engineering knowledge management     | methodology, architecture and knowledge structures | CURRENT           | High       |
| Architecture governance              | ADRs, baselines, review mechanisms                 | CURRENT           | High       |
| Architectural deferral management    | Deferred Architecture Register                     | CURRENT           | High       |
| Engineering observation              | observation runtime and collectors                 | CURRENT           | High       |
| Deterministic quality measurement    | LCOM4, test-presence and related observers         | CURRENT           | High       |
| Recommendation generation            | recommendation observer/engine                     | CURRENT           | High       |
| Developer feedback                   | commit/file-save/CLI presentation paths            | CURRENT           | High       |
| Decision recording                   | recommendation decision workflow                   | CURRENT           | High       |
| Outcome recording                    | engineering improvement workflow                   | CURRENT           | High       |
| Assessment                           | effectiveness/assessment mechanisms                | CURRENT / PARTIAL | High       |
| Evidence/provenance                  | durable records and evidence fields                | CURRENT           | High       |
| AI-assisted engineering              | Claude-oriented engineering platform               | CURRENT           | High       |
| Bootstrap/diagnostic tooling         | `init`, `doctor`, repository-local tooling         | CURRENT / PARTIAL | High       |
| Cross-repository platform extraction | BootstrapPort / reusable platform discussions      | PROPOSED / GATED  | High       |
| Adaptive learning                    | Evidence Analytics / Adaptive Recommendation       | GATED             | High       |

The distinction between current and gated capabilities is important.

For example, **Evidence Analytics** and **Adaptive Recommendation** have been deliberately separated from deterministic observation.

The current system therefore does not justify saying:

> "EKS is already an adaptive learning system."

The evidence supports:

> EKS contains the foundation from which adaptive learning could eventually emerge, but those capabilities remain evidence-gated.

---

# 8. Actors and Users

The current architecture contains several classes of actors.

## 8.1 Human engineers

Engineers are the primary producers of engineering activity.

They:

* edit code;
* create classes;
* write tests;
* commit changes;
* respond to recommendations;
* accept, ignore or defer recommendations;
* perform refactoring;
* make architectural decisions.

The engineer remains the final decision authority.

The recommendation system does not own engineering decisions.

---

## 8.2 Architects

Architects:

* establish architectural baselines;
* evaluate proposals;
* establish activation criteria;
* review architectural evidence;
* approve or reject architectural changes;
* maintain architectural boundaries.

---

## 8.3 Governance roles

Governance mechanisms establish:

* what may become architectural work;
* what remains deferred;
* what evidence is required;
* when an architectural decision can be activated;
* when a proposal is merely an idea.

---

## 8.4 AI engineering agents

AI agents operate as engineering participants.

Their responsibilities include:

* repository investigation;
* architecture reconstruction;
* implementation;
* test creation;
* verification;
* documentation;
* review;
* bounded engineering workflows.

However:

> AI agents are participants in the engineering workflow, not the architectural authority.

This distinction is important to the current governance model.

---

## 8.5 Automation

Automation includes:

* Git hooks;
* observation scripts;
* verification scripts;
* CI workflows;
* bootstrap commands;
* repository checks;
* developer-session tooling.

Automation executes deterministic rules.

It should not silently make architectural decisions.

---

# 9. Ubiquitous Language

The current EKS language is itself an architectural artifact.

The following concepts recur strongly.

## 9.1 Knowledge

Knowledge is durable engineering understanding.

It differs from raw observation.

For example:

```text
LCOM4 = 29
```

is an observation.

Whereas:

```text
LCOM4 warnings on orchestration classes are accepted
only under certain circumstances
```

would represent knowledge derived from multiple decisions and outcomes.

This distinction is central to the current architecture.

---

## 9.2 Observation

An observation is a recorded or computed fact about engineering activity or an artifact.

Examples include:

* test presence;
* LCOM4;
* code metrics;
* latency;
* changed files;
* recommendation counts.

Observations are not automatically decisions.

---

## 9.3 Recommendation

A recommendation is a deterministic or rule-derived proposal to the engineer.

It does not constitute an architectural decision.

This preserves human authority.

---

## 9.4 Decision

A decision represents the human response to a recommendation.

The system therefore distinguishes:

```text
Observation
    ↓
Recommendation
    ↓
Decision
```

These concepts must not collapse into one object.

---

## 9.5 Outcome

An outcome represents what actually happened after a decision.

This creates the necessary link between recommendation and effectiveness.

---

## 9.6 Assessment

An assessment evaluates whether an intervention was effective.

This is where the system begins transforming operational history into engineering knowledge.

---

## 9.7 Evidence

Evidence is the basis on which a claim, decision, assessment or architectural transition is justified.

The architecture increasingly treats evidence as first-class.

---

## 9.8 Change

A change is an engineering event capable of producing observations.

Examples:

```text
File saved
Commit created
Pull request created
Architecture decision changed
```

The current runtime architecture explicitly models changes through `ChangeSet` and trigger mechanisms.

---

## 9.9 State

State represents the current position of an engineering workflow or domain object.

The architecture has historically placed significant emphasis on explicit state and state transitions.

---

## 9.10 Session

A session represents a bounded period of AI-assisted or engineering activity.

The AI Engineering Platform records session context and associated engineering activity.

---

# 10. Domain Model

The current implementation does not justify claiming one monolithic DDD domain model for EKS.

Instead, several domain concepts and workflow models coexist.

The most significant current conceptual chain is:

```text
Recommendation
      ↓
Decision
      ↓
Outcome
      ↓
Assessment
      ↓
Effectiveness / Evidence
```

This appears to be the most mature workflow-oriented domain model.

---

# 11. Recommendation / Decision / Outcome / Assessment Model

This is one of the strongest current domain candidates.

## 11.1 Recommendation

A recommendation is generated from an engineering observation.

Example:

```text
Observation:
LCOM4 = 29

Rule:
R1

Recommendation:
review whether the class contains multiple
independent responsibilities
```

---

## 11.2 Decision

The engineer can respond to the recommendation.

The existing workflow distinguishes decision outcomes rather than assuming acceptance.

Conceptually:

```text
ACCEPT
IGNORE
DEFER
FALSE_POSITIVE
```

The exact persisted taxonomy must remain tied to implementation evidence.

---

## 11.3 Outcome

The decision is followed by engineering action.

For example:

```text
Recommendation
    ↓
Accept
    ↓
Refactor class
    ↓
Commit
    ↓
Measure result
```

---

## 11.4 Assessment

Assessment asks whether the intervention improved the situation.

This creates the feedback loop:

```text
recommendation
      ↓
decision
      ↓
engineering intervention
      ↓
outcome
      ↓
assessment
      ↓
future knowledge
```

This workflow is architecturally significant because it distinguishes **measurement** from **learning**.

---

# 12. Observation Architecture

The current observation architecture is one of the clearest implemented runtime structures.

The demonstrated pipeline is:

```text
ObservationTrigger
       ↓
ChangeSet
       ↓
ObservationRuntime
       ↓
Collectors
       ↓
Observations
       ↓
Recommendation Engine
       ↓
Presentation
```

The runtime has been demonstrated through multiple trigger paths.

---

# 13. ObservationTrigger

`ObservationTrigger` represents a capability boundary.

The current architecture distinguishes the capability from its adapters.

This is important.

For example:

```text
Capability:
CommitTrigger

Possible adapters:
    Husky
    native Git hook
    CI
```

Similarly:

```text
Capability:
ObservationTrigger

Adapters:
    commit
    file-save
    Claude Code
    VS Code
```

The capability therefore does not depend conceptually on Husky.

---

# 14. ChangeSet

The ChangeSet represents the set of engineering artifacts affected by a trigger.

This provides an important boundary between:

```text
event detection
```

and:

```text
observation execution
```

The runtime does not need to understand whether a file was changed by:

* VS Code;
* Claude Code;
* Git;
* another editor;
* another tool.

It receives a ChangeSet.

This is a strong architectural decoupling already present in the implementation.

---

# 15. ObservationRuntime

The runtime coordinates the execution of observations.

It is responsible for orchestrating:

```text
ChangeSet
    ↓
collector selection
    ↓
collector execution
    ↓
recommendation generation
```

The runtime does not itself represent domain knowledge such as LCOM4.

That belongs to collectors/rules.

---

# 16. Collectors

Collectors implement specific forms of engineering observation.

Known current examples include:

* LCOM4;
* test presence;
* recommendation observation.

The architectural advantage is that collectors are replaceable.

The runtime does not need to know the mathematical details of LCOM4.

---

# 17. Recommendation Engine

The recommendation engine transforms observations into engineering advice.

This is deliberately separated from raw metric calculation.

The conceptual distinction is:

```text
Metric
    ↓
Observation
    ↓
Rule
    ↓
Recommendation
```

Therefore:

> Measurement is not knowledge.

And:

> Measurement is not recommendation.

---

# 18. Presentation Architecture

The current architecture has a distinct presentation responsibility.

The runtime produces results.

Presentation adapters render them.

Current adapters include:

* terminal;
* Claude Code session output;
* VS Code presentation.

The architecture has explicitly named `PresentationPort` after multiple concrete adapters existed.

This is a current architectural abstraction supported by multiple implementations rather than speculative abstraction.

---

# 19. Live Developer Feedback

The current system supports live file-save observation.

The demonstrated runtime flow is:

```text
Developer edits PHP file
        ↓
File-save watcher detects change
        ↓
ChangeSet
        ↓
ObservationRuntime
        ↓
LCOM4 / test-presence collectors
        ↓
Recommendations
        ↓
Terminal / presentation adapter
```

A real execution demonstrated approximately:

```text
file saved
    ↓
ChangeSet = 1 file
    ↓
LCOM4 observation
    ↓
test-presence observation
    ↓
2 recommendations
    ↓
~100ms runtime
```

The important architectural qualification is that **terminal live feedback was proven**, while richer IDE presentation remained a separate operational qualification concern at the point of the documented review.

---

# 20. Commit Trigger

The commit trigger is another current observation adapter.

The historical implementation initially contained a legacy path in which the post-commit hook executed test-presence observation directly without passing through the unified ObservationRuntime.

That was subsequently corrected.

The current intended structure is:

```text
Git commit
   ↓
post-commit
   ↓
canonical observation hook
   ↓
ObservationRuntime
   ↓
collectors
   ↓
recommendations
   ↓
presentation
```

This historical correction is architecturally valuable because it demonstrates that the runtime became the canonical orchestration boundary.

---

# 21. Claude Code Integration

The current ecosystem also contains a Claude Code integration.

The relevant architecture is:

```text
Claude Code
     ↓
tool/session event
     ↓
KnowledgeOS observation capability
     ↓
ObservationRuntime
     ↓
recommendations
     ↓
Claude session presentation
```

This demonstrates that the observation capability is not intrinsically tied to Git.

It can be triggered by developer activity occurring inside an AI-assisted engineering session.

---

# 22. AI Engineering Platform

The AI Engineering Platform is a distinct but related part of the current ecosystem.

It should not be confused with the business domain.

Its purpose is to provide controlled infrastructure for AI-assisted engineering.

Known architectural responsibilities include:

* session management;
* composition;
* workflow execution;
* knowledge access;
* verification;
* review;
* drafting;
* platform registry;
* agent configuration;
* hooks;
* engineering discipline checks.

The platform was established through an accepted baseline and subsequently frozen against unnecessary conceptual redesign.

---

# 23. `.claude` as Agent Infrastructure

The `.claude` hierarchy represents agent behaviour and engineering-session configuration.

The current architecture deliberately distinguishes:

```text
Agent behaviour
```

from:

```text
Engineering knowledge
```

The latter belongs in canonical engineering knowledge structures rather than becoming embedded in agent configuration.

This distinction prevents:

> `.claude/` becoming a second KnowledgeOS.

The AI Engineering Platform therefore consumes engineering knowledge; it should not become the authoritative owner of that knowledge.

---

# 24. Engineering Knowledge

Engineering knowledge exists separately from runtime observation.

Relevant areas include:

```text
engineering/
    architecture
    governance
    methodology
    verification
    knowledge
```

This material provides:

* DDD principles;
* architectural methodology;
* governance principles;
* coding standards;
* architectural baselines;
* ADRs;
* verification knowledge;
* engineering procedures.

This represents the durable organizational memory layer.

---

# 25. Governance Architecture

Governance is not simply documentation.

The current system contains mechanisms for deciding:

* whether a proposal is legitimate;
* whether it is architectural;
* whether it should be deferred;
* what evidence activates it;
* whether an architectural change is justified.

The Deferred Architecture Register is particularly important.

Its current structure records:

```text
Deferred Concept
       ↓
Current Invariant
       ↓
Activation Criterion
       ↓
Evidence Required
       ↓
Status
```

This transforms architectural deferral from an informal backlog into an evidence-controlled mechanism.

---

# 26. Deferred Architecture Register

The register embodies a significant current governance principle:

> **No new architecture without an activation event.**

The standing rule is effectively:

```text
No row + no engineering event
        ↓
not architecture work
```

A proposal without an appropriate architectural activation criterion remains an idea rather than silently becoming architecture.

---

# 27. Three-Question Staging Principle

The current governance model distinguishes three questions:

```text
1. Can we build it?
       ↓
   determinism / engineering capability

2. Should we build it now?
       ↓
   object/use case exists

3. Should it join the platform?
       ↓
   cross-repository reuse
```

These questions are orthogonal.

This distinction is important because the system explicitly learned:

> **Deterministic does not mean demanded.**

A deterministic capability may be technically easy while having no current engineering need.

---

# 28. Evidence-Gated Evolution

The architecture is intentionally designed not to continuously expand.

Future capabilities such as:

* Evidence Analytics;
* Adaptive Recommendation;
* native filesystem events;
* IDE decision capture;
* CI/PR triggers;
* reusable platform extraction;

remain gated by explicit evidence.

This is therefore part of the current governance architecture even though the gated capabilities themselves are not current architecture.

---

# 29. Evidence and Provenance

Evidence is increasingly treated as a first-class architectural concern.

The system distinguishes:

```text
claim
    ↓
evidence
    ↓
decision
    ↓
outcome
```

The architecture therefore attempts to avoid:

```text
documentation → assumed truth
```

and instead favours:

```text
implementation
    +
test
    +
runtime evidence
    +
decision record
```

as increasingly strong evidence.

---

# 30. Verification Architecture

Verification exists at several levels.

## 30.1 Structural verification

Examples:

* directory/structure checks;
* architectural guards;
* domain purity checks;
* dependency checks.

## 30.2 Behavioural verification

Examples:

* unit tests;
* integration tests;
* architecture tests;
* audit tests;
* determinism tests.

## 30.3 Operational verification

Examples:

* runtime self-tests;
* observation-chain proof;
* live-save execution;
* hook execution;
* developer-session qualification.

This distinction is important.

A test proving that code exists is not equivalent to proving that a developer actually experiences the capability.

---

# 31. Determinism

Determinism is a foundational engineering principle in the current system.

Examples include:

* deterministic metric calculations;
* deterministic rule evaluation;
* deterministic verification;
* replay determinism;
* temporal determinism;
* deterministic bootstrap planning.

The system deliberately separates deterministic engineering measurement from future learning.

This creates:

```text
Deterministic measurement
        ↓
Operational evidence
        ↓
Human decision
        ↓
Assessment
        ↓
Potential future learning
```

---

# 32. Measurement vs Knowledge

One of the strongest architectural principles to emerge is:

> **Measurement is not knowledge.**

For example:

```text
LCOM4 = 29
```

is measurement.

It becomes knowledge only after engineering history provides contextual meaning:

```text
LCOM4 > threshold
    ↓
recommendation
    ↓
developer decision
    ↓
outcome
    ↓
assessment
    ↓
pattern across contexts
```

Only then can the system legitimately learn something such as:

> A particular warning is frequently accepted in one engineering context but ignored in another.

This distinction prevents premature machine learning.

---

# 33. Evidence Analytics

Evidence Analytics is a future capability, not current architecture.

It has been deliberately separated from Adaptive Recommendation.

Its intended responsibility is:

```text
"What happened?"
```

Potential activities include:

* trend analysis;
* anomaly detection;
* forecasting;
* clustering.

Its defining architectural constraint is:

> It reads historical evidence and does not change developer recommendations.

The implementation technology is intentionally not part of the domain definition.

---

# 34. Adaptive Recommendation

Adaptive Recommendation is also future/gated.

Its intended question is:

```text
"What should happen next?"
```

Potential capabilities include:

* confidence learning;
* threshold adaptation;
* contextual adaptation;
* developer-profile adaptation.

This is materially different from Evidence Analytics because it changes system behaviour.

The current governance requires substantially more evidence before activation.

---

# 35. Current DDD Interpretation

The current EKS architecture should **not** be described simply as:

> "Hexagonal architecture."

Nor simply as:

> "Clean Architecture."

Nor:

> "MVC."

Nor:

> "Microservices."

Those classifications operate at different abstraction levels.

A more accurate reconstruction is:

### Domain modelling

DDD principles are used to reason about:

* language;
* responsibility;
* invariants;
* boundaries;
* lifecycle;
* ownership.

### Application architecture

Workflow orchestration exists around recommendation, decision, outcome and assessment.

### Runtime architecture

The observation subsystem has a clearly decoupled:

```text
Trigger
→ ChangeSet
→ Runtime
→ Collector
→ Recommendation
→ Presentation
```

architecture.

### Adapter architecture

Concrete developer environments are represented by adapters:

* Git/Husky;
* file watcher;
* Claude Code;
* VS Code;
* terminal.

### Governance architecture

ADR, register and evidence mechanisms govern evolution.

Therefore the most accurate description is:

> **EKS is a DDD-informed, evidence-governed engineering knowledge system with a hexagonal/ports-and-adapters structure in its observation runtime and multiple workflow/governance subsystems.**

This statement describes the current architecture without forcing the entire ecosystem into one architectural pattern.

---

# 36. What EKS Is Not

The evidence does not justify describing current EKS as:

### A conventional MVC application

MVC is not an adequate description of the ecosystem.

### A microservice architecture

There is no sufficient evidence that EKS currently consists of independently deployed microservices.

### A pure Hexagonal Architecture

The observation runtime exhibits ports/adapters characteristics, but the entire ecosystem is larger than that runtime.

### A single DDD bounded context

The current architecture contains several responsibilities whose boundaries are still being reconstructed.

### An AI learning system

Adaptive learning remains gated.

### A self-governing autonomous system

Human decisions remain authoritative.

---

# 37. Bounded Context Candidates

Based on current evidence, the following are reasonable **boundary candidates**, not automatically confirmed bounded contexts.

| Candidate               | Responsibility                                   | Evidence              | Classification            |
| ----------------------- | ------------------------------------------------ | --------------------- | ------------------------- |
| Engineering Knowledge   | durable methodology and engineering knowledge    | knowledge structures  | BOUNDARY CANDIDATE        |
| Architecture Governance | ADRs, baselines, deferrals                       | governance mechanisms | BOUNDARY CANDIDATE        |
| Observation             | engineering change observation                   | runtime/collectors    | STRONG BOUNDARY CANDIDATE |
| Recommendation          | converting observations into advice              | recommendation engine | BOUNDARY CANDIDATE        |
| Engineering Improvement | recommendation → decision → outcome → assessment | workflow model        | STRONG BOUNDARY CANDIDATE |
| AI Engineering Platform | AI-assisted engineering orchestration            | `.claude` platform    | PLATFORM BOUNDARY         |
| Verification            | deterministic assurance                          | tests/scripts/gates   | BOUNDARY CANDIDATE        |

These should not be promoted to formal bounded contexts without additional evidence concerning:

* ownership;
* ubiquitous language;
* transaction boundaries;
* persistence;
* invariants;
* lifecycle;
* dependency direction.

---

# 38. Persistence Architecture

The current ecosystem uses several persistence forms.

These include:

* application databases;
* JSONL observation streams;
* architectural records;
* ADRs;
* session logs;
* knowledge documents;
* evidence records;
* configuration;
* Git history.

The important architectural distinction is that not all persistence represents domain state.

For example:

```text
JSONL observation stream
```

is not automatically an aggregate store.

Likewise:

```text
Git commit
```

is evidence of engineering activity, not necessarily an EKS domain entity.

---

# 39. Event Architecture

The current system uses event-like triggers.

Examples:

```text
FileSaved
Commit
Claude tool activity
```

However, this does not justify describing EKS as a full event-driven architecture.

The architecture explicitly rejected or deferred a generic event bus.

The current model is closer to:

```text
Trigger
    ↓
ChangeSet
    ↓
Runtime
```

than:

```text
Enterprise Event Bus
    ↓
Consumers
    ↓
Distributed event choreography
```

This distinction should be preserved.

---

# 40. Event Payload Principle

A current architectural decision is that events should carry **references**, not source code.

The repository remains the source of truth.

Therefore an observation event should conceptually contain:

```text
commit_id
file reference
change metadata
```

rather than embedding complete source code.

The collector can retrieve the relevant repository state using the reference.

This keeps event payloads small and maintains the repository as authoritative source.

---

# 41. Security and Safety Architecture

The current engineering platform contains explicit safety mechanisms.

Examples include:

* database safety checks;
* Git safety;
* repository verification;
* permission checks;
* disciplined hooks;
* deterministic guards;
* bounded AI actions.

The architectural principle is:

> Safety-critical constraints should be mechanically enforced wherever possible.

Prompt instructions alone are insufficient as a safety boundary.

---

# 42. AI and Human Authority

The current architecture establishes a significant asymmetry:

```text
AI
 ↓
analysis
 ↓
recommendation
 ↓
human decision
```

rather than:

```text
AI
 ↓
architecture decision
```

This is particularly important for:

* architectural changes;
* governance decisions;
* security changes;
* irreversible migrations;
* platform extraction.

AI can accelerate engineering reasoning without becoming the sovereign authority over the engineering system.

---

# 43. Current AI Engineering Architecture

The AI Engineering Platform can be reconstructed as:

```text
┌───────────────────────────────────────┐
│          AI Engineering Platform      │
│                                       │
│ Composition Root                      │
│ Session Manager                       │
│ Knowledge Manager                     │
│ Workflow Engine                       │
│ Verification Engine                   │
│ Review Engine                         │
│ Drafting Studio                       │
│ Platform Registry                     │
└───────────────────┬───────────────────┘
                    │
                    ▼
             Engineering Knowledge
                    │
                    ▼
              Repository / Code
```

These are current platform components from the established baseline.

However, their exact future packaging should not be inferred from this baseline.

---

# 44. KnowledgeOS Relationship

KnowledgeOS is best understood as the current name for the evolving engineering knowledge/observation platform emerging from the EKS ecosystem.

However, architecture archaeology requires an important distinction:

> **Historical EKS architecture and the later KnowledgeOS target architecture are not interchangeable.**

The current baseline therefore treats KnowledgeOS terminology carefully.

Where a capability existed before the KnowledgeOS architectural model was introduced, the historical implementation should be described on its own terms.

Where the current implementation has subsequently adopted a KnowledgeOS runtime model, that is recorded as current implementation evidence.

---

# 45. PKS Relationship

PKS / Product Knowledge System belongs to the wider knowledge architecture surrounding the engineering ecosystem.

However, the evidence available in the current reconstruction is insufficient to assert a fully separated PKS bounded context with independent runtime topology.

Therefore:

**PKS = RELATED ARCHITECTURAL SYSTEM / BOUNDARY CANDIDATE**

unless implementation and ownership evidence establishes a stronger boundary.

The same discipline applies to PKS Services.

---

# 46. Repository Composition

The wider repository should therefore not be interpreted as one application.

A conceptual landscape is:

```text
                    Engineering Ecosystem
                            │
          ┌─────────────────┼─────────────────┐
          │                 │                 │
          ▼                 ▼                 ▼
     PublicDIGIT       KnowledgeOS/EKS       PKS
     Business          Engineering           Knowledge
     system            knowledge             platform
          │                 │                 │
          └─────────────────┼─────────────────┘
                            │
                            ▼
                 Engineering Governance
                 Architecture Knowledge
                 Verification
                 AI Engineering Platform
```

This is an architectural landscape rather than a deployment diagram.

---

# 47. Current Architecture vs Target Architecture

The following distinction is mandatory for future reviewers.

| Concern                                | Current evidence    | Future concept          |
| -------------------------------------- | ------------------- | ----------------------- |
| Observation runtime                    | CURRENT             | —                       |
| Deterministic collectors               | CURRENT             | —                       |
| Recommendation workflow                | CURRENT             | —                       |
| Decision/outcome/assessment            | CURRENT / PARTIAL   | —                       |
| Commit trigger                         | CURRENT             | —                       |
| File-save trigger                      | CURRENT             | —                       |
| Presentation adapters                  | CURRENT             | richer IDE presentation |
| Evidence Analytics                     | GATED               | future                  |
| Adaptive Recommendation                | GATED               | future                  |
| Generic event bus                      | REJECTED / DEFERRED | not currently justified |
| Cross-repository platform              | GATED               | future                  |
| Native filesystem events               | STAGED              | future adapter          |
| Live decision capture                  | STAGED              | future                  |
| Reusable bootstrap across repositories | PARTIAL/GATED       | future                  |
| Full autonomous learning               | FUTURE              | not current             |

---

# 48. Architectural Invariants

The following invariants are strongly supported by the current architecture.

## Invariant 1 — Human engineering authority

Recommendations do not become decisions automatically.

## Invariant 2 — Evidence before architectural evolution

Architectural changes require evidence and/or an explicit activation criterion.

## Invariant 3 — Deterministic measurement precedes learning

The system first establishes reliable measurement and operational history.

## Invariant 4 — Trigger and runtime are separated

The runtime does not depend on a particular developer trigger.

## Invariant 5 — Capability and adapter are separate

A capability is not named after its current implementation technology.

## Invariant 6 — Measurement is distinct from knowledge

Raw metrics do not constitute organizational learning.

## Invariant 7 — Repository remains source of truth

Events reference engineering artifacts rather than embedding source code.

## Invariant 8 — Future evolution is evidence-gated

A technically possible capability is not automatically activated.

---

# 49. Known Architectural Strengths

The reconstructed architecture demonstrates several strong properties.

### Evidence orientation

Architectural claims increasingly have corresponding implementation or operational evidence.

### Explicit deferral

Unnecessary architecture is deliberately postponed.

### Deterministic runtime

The observation pipeline is deterministic and testable.

### Adapter separation

Trigger and presentation mechanisms are separated from runtime logic.

### Human authority

The system does not silently turn recommendations into decisions.

### Knowledge/evidence distinction

The architecture explicitly distinguishes measurements from learned knowledge.

### Self-observation

The runtime measures aspects of its own operational behaviour.

### Architectural restraint

The system explicitly rejects speculative abstractions.

---

# 50. Known Architectural Weaknesses / Open Areas

The reconstruction also exposes unresolved areas.

## 50.1 Some domain boundaries remain implicit

The implementation has stronger conceptual boundaries than the documentation always expresses.

## 50.2 EKS and KnowledgeOS terminology has evolved

Historical terminology can obscure the actual current implementation.

## 50.3 Some workflow concepts are more mature than others

Recommendation/decision/outcome/assessment is significantly more mature than adaptive learning.

## 50.4 Operational qualification is separate from implementation

A feature may be implemented but not yet proven as a seamless developer experience.

## 50.5 Cross-repository reuse is not yet sufficiently evidenced

The platform currently has one primary implementation environment.

Therefore cross-repository abstractions remain gated.

---

# 51. Architecture Maturity Assessment

A useful current-state maturity model is:

| Layer                       | Maturity    |
| --------------------------- | ----------- |
| Engineering knowledge       | HIGH        |
| Governance                  | HIGH        |
| Architectural documentation | HIGH        |
| Deterministic observation   | HIGH        |
| Recommendation generation   | HIGH        |
| Trigger architecture        | HIGH        |
| Terminal presentation       | QUALIFIED   |
| AI Engineering Platform     | HIGH        |
| Decision/outcome workflow   | MEDIUM-HIGH |
| Assessment/effectiveness    | MEDIUM      |
| Evidence Analytics          | GATED       |
| Adaptive Recommendation     | GATED       |
| Cross-repository platform   | GATED       |
| Fully autonomous learning   | NOT CURRENT |

This is a maturity description, not a roadmap.

---

# 52. Architectural Classification

The current EKS architecture can therefore be summarized as:

> **A DDD-informed engineering knowledge and assurance ecosystem, combining durable engineering knowledge and governance with a deterministic observation/recommendation runtime, workflow-based decision and assessment mechanisms, and an AI Engineering Platform that operates as an engineering participant under human governance.**

Its strongest implemented runtime pattern is:

```text
Trigger
   ↓
ChangeSet
   ↓
ObservationRuntime
   ↓
Collectors
   ↓
Recommendation
   ↓
Presentation
```

Its strongest knowledge workflow is:

```text
Engineering Activity
   ↓
Observation
   ↓
Recommendation
   ↓
Decision
   ↓
Outcome
   ↓
Assessment
   ↓
Evidence
   ↓
Knowledge
```

Its strongest governance mechanism is:

```text
Proposal
   ↓
Activation Criterion
   ↓
Evidence
   ↓
Decision
   ↓
Architecture
```

These three structures together explain much of the current architecture.

---

# 53. What Can Be Called Current With High Confidence

The following can be treated as current architecture:

1. Engineering knowledge and governance structures.
2. ADR-driven architectural decision management.
3. Deferred Architecture Register.
4. Deterministic engineering observation.
5. LCOM4 and test-presence observation.
6. Recommendation generation.
7. ObservationRuntime.
8. ChangeSet-based runtime orchestration.
9. Multiple observation triggers.
10. Commit-time observation.
11. File-save observation.
12. Developer-facing presentation.
13. Recommendation decision workflow.
14. Outcome/effectiveness concepts.
15. AI Engineering Platform.
16. Repository-local engineering verification.
17. Evidence/provenance mechanisms.
18. Evidence-gated architectural evolution.

---

# 54. What Must Not Be Mistaken for Current Architecture

The following remain outside the current baseline unless new implementation evidence establishes otherwise:

* generic event bus;
* Kafka;
* microservice decomposition;
* Rust runtime;
* Spring Boot implementation;
* fully autonomous recommendation learning;
* cross-repository distributed KnowledgeOS;
* complete adaptive developer profiling;
* commercial product platform;
* future Business Translator;
* Digitalization Robot;
* speculative deployment topology.

These belong to architectural history or future proposals.

---

# 55. Reviewer Reading Order

A reviewer entering the ecosystem should inspect the architecture in this order:

```text
1. Repository composition
        ↓
2. Engineering governance
        ↓
3. Architecture baselines / ADRs
        ↓
4. Engineering knowledge
        ↓
5. Observation runtime
        ↓
6. Recommendation workflow
        ↓
7. Decision / Outcome / Assessment
        ↓
8. AI Engineering Platform
        ↓
9. Verification evidence
        ↓
10. Deferred Architecture Register
```

This prevents the reviewer from interpreting isolated scripts or directories as complete architectural components.

---

# 56. Architecture Archaeology Findings

The most important archaeological finding is that the architecture did not emerge from one top-down design.

It evolved through repeated cycles of:

```text
Problem
 ↓
Observation
 ↓
Proposal
 ↓
Evidence
 ↓
Implementation
 ↓
Verification
 ↓
Correction
 ↓
Architectural clarification
```

This explains why some boundaries are explicit while others remain implicit.

It also explains why the Deferred Architecture Register became important: the system needed a mechanism to prevent every useful observation from immediately becoming architecture.

---

# 57. Current Architectural Principle

The current architecture can ultimately be summarized by one principle:

> **EKS turns engineering activity into evidence, evidence into bounded recommendations and decisions, and decisions into durable engineering knowledge — while keeping architectural authority with humans and keeping future architectural evolution evidence-gated.**

That is the strongest coherent description of the system that the current evidence supports.

---

# 58. Confidence and Unknowns

This baseline deliberately contains uncertainty.

The following areas require further implementation-level archaeology before stronger claims can be made:

* exact EKS persistence topology;
* exact ownership of every workflow concept;
* formal bounded-context boundaries;
* exact PKS/EKS runtime boundary;
* exact PKS Services responsibilities;
* complete historical migration from EKS terminology to KnowledgeOS terminology;
* deployment architecture;
* cross-repository runtime topology;
* whether every documented governance invariant is mechanically enforced.

These are not defects in the baseline.

They are explicitly preserved as **UNKNOWN** rather than being filled with architectural assumptions.

---

# 59. Final Architectural Verdict

The current EKS ecosystem is **not best understood as a conventional software application**.

It is an engineering system composed of:

```text
                 ┌─────────────────────────┐
                 │ Engineering Governance  │
                 └────────────┬────────────┘
                              │
                 ┌────────────▼────────────┐
                 │ Engineering Knowledge   │
                 └────────────┬────────────┘
                              │
                 ┌────────────▼────────────┐
                 │ Observation Runtime     │
                 │                         │
                 │ Trigger                 │
                 │   ↓                     │
                 │ ChangeSet               │
                 │   ↓                     │
                 │ Runtime                 │
                 │   ↓                     │
                 │ Collectors              │
                 │   ↓                     │
                 │ Recommendations         │
                 └────────────┬────────────┘
                              │
                 ┌────────────▼────────────┐
                 │ Engineering Workflow    │
                 │                         │
                 │ Decision                │
                 │   ↓                     │
                 │ Outcome                 │
                 │   ↓                     │
                 │ Assessment              │
                 └────────────┬────────────┘
                              │
                 ┌────────────▼────────────┐
                 │ Evidence / Knowledge    │
                 │ Feedback Loop            │
                 └─────────────────────────┘
```

Around this core sits the AI Engineering Platform, which enables AI agents to participate in the engineering process while remaining subject to the same governance and evidence principles.

The architecture therefore has three particularly important dimensions:

### 1. Engineering knowledge

The system remembers what the organization knows.

### 2. Engineering assurance

The system measures and verifies what engineers are doing.

### 3. Engineering learning

The system records decisions, outcomes and assessments so that repeated engineering experience can eventually become organizational knowledge.

The current implementation is strongest in the first two dimensions.

The third exists as a developing workflow but deliberately has not been allowed to become autonomous machine learning before sufficient evidence exists.

That restraint is itself part of the architecture.

---

# 60. Baseline Boundary

This document establishes a **CURRENT ARCHITECTURE BASELINE**.

It does not authorize:

* redesign;
* refactoring;
* bounded-context creation;
* service extraction;
* event-bus introduction;
* platform extraction;
* adaptive learning;
* technology migration.

Any future proposal must first be evaluated against this baseline and must explicitly identify:

```text
CURRENT
IMPLEMENTED-BUT-IMPLICIT
PARTIAL
DOCUMENTED-ONLY
HISTORICAL
PROPOSED
UNKNOWN
```

The purpose of this baseline is therefore not to prescribe what EKS should become.

Its purpose is to make it possible for a future architect to answer, with evidence:

> **"What did we actually have before we changed it?"**

That is the foundation required for responsible architectural evolution.
