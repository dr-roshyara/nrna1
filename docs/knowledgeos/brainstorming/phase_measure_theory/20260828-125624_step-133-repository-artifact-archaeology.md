# Step 133 — Repository  Artifact Archaeology

We now enter the **evidence phase** of the KnowledgeOS reconstruction.

Until Step 132, we deliberately worked from the architectural concepts already established. From here onward, we should distinguish very strictly between:

* **FACT** — directly observable in the existing system;
* **DERIVED** — logically derived from facts;
* **HYPOTHESIS** — plausible but not yet proven;
* **TARGET** — desired future architecture.

The goal is not to redesign KnowledgeOS prematurely.

The goal is:

$$
\boxed{
Reconstruct\ what\ actually\ exists
}
$$

and then:

$$
\boxed{
Map\ it\ against\ the\ target.
}
$$

---

# 133.1 — Archaeology principle

The basic reconstruction equation is:

$$
\boxed{
Artifact
\rightarrow
Observation
\rightarrow
Semantic\ Interpretation
\rightarrow
Architecture\ Classification
}
$$

For example:

```text
.claude/memory/foo.md exists
        ↓
FACT: persistent local memory exists
        ↓
DERIVED: Claude has persistent contextual state
        ↓
QUESTION: Is that state authoritative?
        ↓
Architecture classification
```

We must **not** jump directly from the first line to:

> "Claude memory is the KnowledgeOS knowledge base."

That would be an unsupported architectural conclusion.

---

# 133.2 — Repository topology comes first

The repository is the physical starting point.

We need to establish:

```text
Repository
│
├── source
├── configuration
├── scripts
├── tests
├── documentation
├── agent integration
├── governance
├── registry
└── assurance
```

But the exact structure must come from the actual repository.

The first artifact is therefore:

$$
RepositoryTopology.
$$

---

# 133.3 — Why topology matters

Directory structure often reveals architectural intent, but it is not proof of architectural correctness.

For example:

```text
domain/
application/
infrastructure/
```

suggests architectural separation.

But if:

```text
domain/
   └── KubernetesClient.java
```

then the physical naming is misleading.

Therefore:

$$
DirectoryStructure
\neq
Architecture.
$$

We need dependency evidence.

---

# 133.4 — Second layer: dependency archaeology

After topology:

$$
DependencyGraph.
$$

We want:

$$
G=(V,E)
$$

where:

* \(V\) = modules/packages/components;
* \(E\) = dependency relationships.

Then we test forbidden relationships.

For example:

$$
Domain
\rightarrow
ClaudeSDK
$$

would be suspicious.

---

# 133.5 — Third layer: runtime topology

Static code is not enough.

We also need:

$$
RuntimeTopology.
$$

Questions:

* What actually runs?
* Which processes exist?
* Which containers?
* Which services?
* Which jobs?
* Which hooks?
* Which external systems?
* Which databases?
* Which APIs?

The runtime topology may differ substantially from repository topology.

---

# 133.6 — Fourth layer: data archaeology

We then identify where knowledge actually lives.

Potential stores:

```text
Markdown
JSON
YAML
SQL
Vector index
Git
Registry
Database
Logs
Memory files
Generated artifacts
```

For each store:

$$
Store
\rightarrow
ContentType
\rightarrow
Authority
\rightarrow
Lifecycle.
$$

---

# 133.7 — The critical data question

For every knowledge-bearing store:

> **Is this authoritative, derived, cached, temporary, or merely a pointer?**

This single classification will resolve many of the current KnowledgeOS architectural ambiguities.

---

# 133.8 — Knowledge authority matrix

We should eventually produce:

| Store             | Contains | Authority | Lifecycle | Consumer      |
| ----------------- | -------- | --------- | --------- | ------------- |
| KnowledgeOS store | ?        | ?         | ?         | Agents/humans |
| `.claude/memory`  | ?        | ?         | ?         | Claude        |
| `.codex`          | ?        | ?         | ?         | Codex         |
| `AGENTS.md`       | ?        | ?         | ?         | Agents        |
| Registry          | ?        | ?         | ?         | Platform      |
| Git               | ?        | ?         | ?         | Engineering   |
| Logs              | ?        | ?         | ?         | Assurance     |
| Governance docs   | ?        | ?         | ?         | Humans/agents |

This will become one of the most important reconstruction artifacts.

---

# 133.9 — Artifact classification

Every important artifact should receive one semantic classification.

### Authoritative

The artifact itself establishes binding organizational truth.

### Derived

Generated from authoritative information.

### Observational

Represents something observed in reality.

### Evidentiary

Supports a claim.

### Operational

Controls execution.

### Contextual

Helps an agent reason but is not authoritative.

### Temporary

Valid only for a limited workflow/session.

### Historical

Preserves previous state.

---

# 133.10 — Example: `AGENTS.md`

Possible classification:

$$
Operational + Contextual.
$$

It may say:

> Follow these repository instructions.

But it should not silently become:

$$
AuthoritativeArchitecture.
$$

If it contains a pointer:

```text id="j5v9q3"
Architecture rules:
→ KnowledgeOS/...
```

then the separation is clean.

---

# 133.11 — Example: `.claude/settings.json`

Likely classification:

$$
OperationalConfiguration.
$$

Its purpose is to control the agent.

It should not contain:

$$
EnterpriseGovernanceTruth.
$$

If it does, that creates semantic duplication.

---

# 133.12 — Example: hooks

A hook may be:

$$
Operational
$$

plus:

$$
Assurance.
$$

For example:

```text id="g2n7p4"
pre-commit
    ↓
architecture check
    ↓
PASS/FAIL
```

The hook executes a control.

The rule it enforces should have an identifiable authority.

---

# 133.13 — Example: memory

Memory may contain:

```text id="x4m8p1"
"Last session we concluded X."
```

This is contextual.

It should not automatically become:

```text id="f7q3r5"
"X is now the authoritative architecture."
```

That transition requires governance.

---

# 133.14 — Knowledge promotion

We can therefore define:

$$
ContextualKnowledge
\rightarrow
CandidateClaim
\rightarrow
Verification
\rightarrow
AuthoritativeKnowledge.
$$

This promotion process is a central KnowledgeOS capability.

---

# 133.15 — Agent-generated knowledge

Suppose Claude discovers:

> "Service A appears to use Database B."

The correct state is:

$$
Claim_{candidate}.
$$

Not:

$$
Fact_{authoritative}.
$$

The agent can attach evidence:

$$
Claim
\rightarrow
Evidence.
$$

Then a deterministic or human verification process can promote it.

---

# 133.16 — Evidence archaeology

We should identify every existing mechanism that records:

* commands;
* tool calls;
* test results;
* repository state;
* checks;
* runtime observations;
* agent changes;
* approvals.

These may already constitute much of the Evidence context.

The important question is whether they are **connected**.

---

# 133.17 — Fragmented evidence

A common existing architecture looks like:

```text id="q8v4m2"
Git log          → history
CI               → test results
Agent log        → AI activity
Hook log         → checks
Runtime log      → observations
Governance docs  → decisions
```

Each contains evidence.

But:

$$
Evidence_i
$$

may have no relationship to:

$$
Decision_j.
$$

That is the gap the Assurance Graph is designed to close.

---

# 133.18 — Evidence integration test

For a real engineering change, ask:

> Can we traverse from the change to its authorization, implementation, evidence, and verification?

For example:

$$
Change
\rightarrow
Decision
\rightarrow
Commit
\rightarrow
Deployment
\rightarrow
Verification.
$$

If the chain breaks, record:

$$
TraceabilityGap.
$$

---

# 133.19 — Registry archaeology

The registry deserves special attention because it may already be the beginning of the graph.

We need to determine whether registry entries have:

* stable IDs;
* types;
* ownership;
* provenance;
* relationships;
* lifecycle;
* status;
* versions.

If yes, the registry may already contain some graph semantics.

---

# 133.20 — Registry maturity

Possible levels:

$$
R0 = Name\ list
$$

$$
R1 = Structured\ catalog
$$

$$
R2 = Identity + metadata
$$

$$
R3 = Relationships
$$

$$
R4 = Provenance + lifecycle
$$

$$
R5 = Evidence-backed\ governance.
$$

We should measure the actual registry against this ladder.

---

# 133.21 — The registry may be more important than expected

If the existing registry already provides:

$$
Identity
+
Relationships
+
Lifecycle
+
Provenance,
$$

then creating a new "Knowledge Graph" could be unnecessary.

Instead:

$$
ExistingRegistry
\rightarrow
Strengthen
\rightarrow
AssuranceGraph.
$$

This is exactly why archaeology must precede implementation.

---

# 133.22 — Governance artifact archaeology

We should identify:

* architecture constitutions;
* ADRs;
* governance decisions;
* standards;
* implementation constraints;
* exceptions;
* review results;
* approval records.

Then map them:

$$
Artifact
\rightarrow
KnowledgeObject
$$

if appropriate.

---

# 133.23 — The important distinction

An ADR file does not automatically equal a Decision entity.

We need to determine whether the current system treats:

$$
ADR
$$

as:

1. the authoritative decision itself;
2. a document representing a decision;
3. evidence supporting a decision;
4. a historical artifact.

Those are architecturally different.

---

# 133.24 — Decision lifecycle archaeology

Look for evidence of:

```text id="r7m2x9"
Draft
 ↓
Review
 ↓
Approved
 ↓
Effective
 ↓
Superseded
```

If the system has only:

```text id="k5p8n3"
ADR-042.md
```

then we have documentation, but not necessarily a full governance model.

---

# 133.25 — Assurance archaeology

Existing checks should be classified:

```text id="s3v9q1"
Check
├── Rule
├── Implementation
├── Input
├── Execution
├── Result
└── Evidence
```

For each existing check, determine which fields actually exist.

---

# 133.26 — Example

Existing script:

```text id="e8m4q7"
verify-package-structure.sh
```

Fact:

$$
CheckerExists.
$$

Potentially:

$$
RuleExists.
$$

But unless there is a governed relationship:

$$
Checker
\rightarrow
Rule
$$

we should not assume that the check represents an authoritative architecture rule.

---

# 133.27 — Assurance lineage

The desired lineage is:

$$
Principle
\rightarrow
Rule
\rightarrow
Checker
\rightarrow
Execution
\rightarrow
Result
\rightarrow
Evidence.
$$

This is one of the primary things we need to look for in the current system.

---

# 133.28 — Session-change logger

The existing session-change logging mechanism is particularly interesting.

It may already provide:

$$
AgentSession
\rightarrow
Change.
$$

If so, this can become part of:

$$
AgentActionEvidence.
$$

But again:

$$
LoggerExists
\neq
CompleteTraceability.
$$

We need to determine whether the record contains enough information to reconstruct:

* actor;
* task;
* repository;
* action;
* authorization;
* resulting state.

---

# 133.29 — The agent execution chain

For Claude/Codex, the archaeology should seek:

```text id="h6q2w9"
Task
 ↓
Session
 ↓
Context
 ↓
Tool
 ↓
Action
 ↓
Change
 ↓
Evidence
 ↓
Verification
```

If some links are missing, those become explicit gaps.

---

# 133.30 — Claude/Codex symmetry test

The same questions must be asked of both.

| Capability              | Claude | Codex |
| ----------------------- | ------ | ----- |
| Operating contract      | ?      | ?     |
| Knowledge pointer       | ?      | ?     |
| Local memory            | ?      | ?     |
| Hooks                   | ?      | ?     |
| Tool controls           | ?      | ?     |
| Evidence logging        | ?      | ?     |
| Governance integration  | ?      | ?     |
| Deterministic assurance | ?      | ?     |

This will reveal whether the two harnesses really implement the same architectural contract.

---

# 133.31 — Symmetry does not mean identical files

For example:

Claude may use:

```text id="p9v4k2"
.claude/settings.json
.claude/commands/
.claude/hooks/
```

while Codex uses:

```text id="w3m8q5"
.codex/
AGENTS.md
```

This is acceptable.

The question is:

$$
SemanticContract_{Claude}
=
SemanticContract_{Codex}?
$$

---

# 133.32 — Agent pointer layer

The desired architecture is:

```text id="n2q7m4"
                 KnowledgeOS
                     ▲
                     │
              authoritative
                 knowledge
                     │
          ┌──────────┴──────────┐
          │                     │
       Claude                Codex
          │                     │
     .claude/              .codex/
          │                     │
          └──── operating ──────┘
                 contract
```

This preserves symmetry while allowing implementation differences.

---

# 133.33 — Current-state evidence model

At the end of archaeology we want something like:

```text id="b5x8r1"
FACTS
 │
 ├── Repository facts
 ├── Agent facts
 ├── Governance facts
 ├── Registry facts
 ├── Assurance facts
 └── Runtime facts
       │
       ▼
DERIVED ARCHITECTURE
       │
       ▼
CURRENT MODEL
       │
       ▼
TARGET DELTA
```

---

# 133.34 — The Current Architecture Record

The final result of this phase should be a formal:

$$
\boxed{
CurrentArchitectureRecord
}
$$

containing:

```text id="u8m3q6"
System
Version / Date
Repository
Components
Dependencies
Data Stores
Agent Interfaces
Governance
Assurance
Integrations
Runtime
Evidence
Known Constraints
Known Gaps
Confidence
```

Each major statement should link to evidence.

---

# 133.35 — Confidence

We should also record confidence:

$$
High
$$

$$
Medium
$$

$$
Low.
$$

For example:

> `.claude/` is an operational agent layer.

Confidence:

$$
High
$$

if directly observed.

> KnowledgeOS is intended to be the authoritative source for architecture.

Confidence:

$$
Medium
$$

unless explicitly established by governance.

---

# 133.36 — Architecture uncertainty is data

This is important.

Instead of hiding uncertainty:

> "The system probably does X."

we record:

```text id="r2m7v5"
Claim: System X is authoritative knowledge store
Status: UNKNOWN
Evidence required:
- repository implementation
- governance record
- API behavior
```

Thus:

$$
Unknown
$$

becomes a first-class reconstruction result.

---

# 133.37 — Reconstruction finding types

We can define:

$$
MissingCapability
$$

$$
SemanticAmbiguity
$$

$$
ArchitectureViolation
$$

$$
TraceabilityGap
$$

$$
DuplicateAuthority
$$

$$
StaleKnowledge
$$

$$
UnverifiedAssumption.
$$

These are much more useful than a generic "technical debt" label.

---

# 133.38 — Duplicate authority

One particularly dangerous finding is:

$$
Authority_1
\neq
Authority_2
$$

for the same semantic fact.

Example:

```text id="c4n8p1"
KnowledgeOS says:
Decision D42 is current.

AGENTS.md says:
Decision D17 is current.

Claude memory says:
Decision D31 is current.
```

This is not merely documentation inconsistency.

It is:

$$
\boxed{
AuthorityConflict.
}
$$

---

# 133.39 — Authority conflict resolution

The architecture must define precedence.

Potentially:

$$
GovernedAuthority
>
GeneratedContext
>
AgentMemory.
$$

But this must be explicitly defined and governed.

The agent should not decide which source "feels newer."

---

# 133.40 — The architecture archaeology invariant

We can formulate:

$$
\boxed{
AAI-01:
No\ architectural\ conclusion\ may\ be\ promoted\ from\ hypothesis\ to\ fact\ without\ supporting\ evidence.
}
$$

This is the discipline for the remainder of the reconstruction.

---

# 133.41 — The next concrete artifact

The immediate artifact after repository inspection should be:

# **KnowledgeOS Current Architecture Inventory**

with these sections:

```text
1. Repository Topology
2. Runtime Topology
3. Agent Topology
4. Knowledge Stores
5. Governance Stores
6. Evidence Stores
7. Assurance Mechanisms
8. Integration Boundaries
9. Dependency Graph
10. Authority Model
11. Traceability Model
12. Current Architecture Assessment
13. Unknowns
14. Risks
15. Target Delta
```

---

# 133.42 — What we must NOT do yet

We should not yet decide:

* PostgreSQL vs graph DB;
* microservices vs modular monolith;
* Kafka vs synchronous APIs;
* vector DB;
* new KnowledgeOS service;
* new evidence database;
* replacement of existing registry.

Those are downstream decisions.

First:

$$
\boxed{
Understand\ the\ existing\ system.
}
$$

---

# 133.43 — Step 133 verdict

The architecture reconstruction methodology is now established.

The KnowledgeOS investigation has transitioned from:

$$
Conceptual\ Design
$$

to:

$$
Evidence\ Based\ Architecture\ Archaeology.
$$

The fundamental chain is:

$$
\boxed{
Artifact
\rightarrow
Fact
\rightarrow
Relationship
\rightarrow
Current\ Architecture
\rightarrow
Fitness\ Assessment
\rightarrow
Target\ Delta.
}
$$

And the most important rule for the next phase is:

$$
\boxed{
Do\ not\ build\ a\ second\ KnowledgeOS\ while\ investigating\ the\ first.
}
$$

---

# Step 134 — Actual Repository Reconstruction

The next step is therefore no longer another conceptual model.

We need to **inspect the actual KnowledgeOS artifacts** and populate the Current Architecture Inventory.

For every major component we will produce:

```text id="n7p3m8"
COMPONENT
   ↓
What exists?
   ↓
What does it own?
   ↓
What does it depend on?
   ↓
What knowledge does it contain?
   ↓
What authority does it have?
   ↓
What evidence does it produce?
   ↓
Which bounded context does it belong to?
   ↓
Which target capability does it satisfy?
   ↓
What is missing/misaligned?
```

That will give us the first genuinely evidence-backed:

$$
\boxed{
CURRENT\ KNOWLEDGEOS\ ARCHITECTURE
}
$$

and from there we can construct the **Current → Target Delta Architecture**, rather than continuing to design in the abstract.
