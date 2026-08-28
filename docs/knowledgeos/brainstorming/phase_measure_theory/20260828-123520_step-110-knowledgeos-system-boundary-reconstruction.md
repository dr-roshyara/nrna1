# Step 110 — KnowledgeOS System Boundary Reconstruction

We now need to establish the **actual boundary of KnowledgeOS** before analyzing individual components.

The key question is:

> **Which artifacts, services, repositories, configurations, agents and workflows are actually part of KnowledgeOS, and which merely interact with it?**

This distinction is essential because otherwise the architecture will gradually absorb every tool that touches the platform.

---

## 110.1 — The system boundary rule

We define:

$$
K_{OS}=
\{x\mid x\text{ has an evidenced architectural role in KnowledgeOS}\}
$$

Not:

$$
K_{OS}=
\{x\mid x\text{ happens to interact with KnowledgeOS}\}.
$$

An external Git server, for example, may be essential to KnowledgeOS without becoming part of KnowledgeOS itself.

---

# 110.2 — Six boundary classifications

Every discovered artifact should receive one of six classifications:

| Classification | Meaning                                               |
| -------------- | ----------------------------------------------------- |
| **CORE**       | Directly constitutes KnowledgeOS                      |
| **SUPPORTING** | Provides infrastructure/control needed by KnowledgeOS |
| **AGENT**      | Agent-specific operating layer                        |
| **EXTERNAL**   | External system consumed by KnowledgeOS               |
| **LEGACY**     | Historical/obsolete mechanism                         |
| **UNKNOWN**    | Relationship not yet established                      |

This is intentionally conservative.

---

# 110.3 — CORE

A component is **CORE** when removing it would remove an essential KnowledgeOS capability.

Examples conceptually:

$$
KnowledgeModel
$$

$$
KnowledgePersistence
$$

$$
GovernanceEngine
$$

$$
EvidenceModel
$$

if these actually exist in the implementation.

But we must verify their existence.

---

# 110.4 — SUPPORTING

Supporting infrastructure enables KnowledgeOS but does not itself represent its semantic core.

Examples may include:

$$
Database
$$

$$
MessageBroker
$$

$$
ObjectStorage
$$

$$
CI/CD
$$

$$
Observability.
$$

Again:

> **Used by KnowledgeOS does not mean semantically part of KnowledgeOS.**

---

# 110.5 — AGENT

Agent-specific artifacts are separated:

```text
.claude/
.codex/
AGENTS.md
agent hooks
agent commands
agent-specific settings
```

Their purpose is:

$$
AgentBehavior.
$$

They become part of the broader ecosystem but should not automatically be classified as the KnowledgeOS semantic core.

---

# 110.6 — EXTERNAL

An external system may be:

$$
GitHub
$$

$$
GitLab
$$

$$
Jira
$$

$$
CI
$$

$$
CloudProvider
$$

or another organizational platform.

Its data may become KnowledgeOS evidence, but the external system retains its own system boundary.

---

# 110.7 — LEGACY

Legacy artifacts are especially dangerous because they can create false architectural impressions.

For example:

```text
old-memory-sync
old-registry
deprecated-indexer
legacy-agent-script
```

A repository containing an artifact does not mean the artifact belongs to the current architecture.

---

# 110.8 — UNKNOWN

This is a legitimate result.

$$
Unknown
$$

means:

> We have not yet established enough evidence to classify the artifact.

It does **not** mean:

$$
External.
$$

---

# 110.9 — Experiment 1

We discover:

```text
scripts/session-changes-logger
```

Question:

> Is it Core, Supporting, Agent, External or Legacy?

We inspect its actual usage.

If it is invoked by agent hooks to create action provenance:

$$
Agent
\rightarrow
Logger.
$$

Likely:

$$
AGENT/SUPPORTING.
$$

But classification awaits evidence.

### Result

$$
\boxed{\text{UNKNOWN until usage is established}}
$$

---

# 110.10 — Experiment 2

`.claude/settings.json` exists.

It controls Claude execution.

Expected:

$$
AGENT.
$$

It is not automatically:

$$
CORE\ KnowledgeOS.
$$

### Result

$$
\boxed{\text{AGENT}}
$$

---

# 110.11 — Experiment 3

A PostgreSQL database stores KnowledgeOS records.

Expected:

$$
SUPPORTING
$$

unless the architecture explicitly defines the database as part of the deployable KnowledgeOS product boundary.

### Result

$$
\boxed{\text{SUPPORTING}}
$$

---

# 110.12 — Logical boundary versus deployment boundary

This distinction is crucial.

A system may logically contain:

$$
KnowledgeService.
$$

while deployment uses:

$$
PostgreSQL
$$

as an external managed service.

Thus:

$$
LogicalBoundary
\neq
DeploymentBoundary.
$$

---

# 110.13 — Product boundary versus ecosystem boundary

We should actually maintain three boundaries.

### Product boundary

What is KnowledgeOS itself?

$$
K_{product}.
$$

### Ecosystem boundary

What participates in the KnowledgeOS operating ecosystem?

$$
K_{ecosystem}.
$$

### External environment

What KnowledgeOS interacts with?

$$
K_{environment}.
$$

Therefore:

$$
K_{product}
\subseteq
K_{ecosystem}
\subseteq
K_{environment}.
$$

---

# 110.14 — Example

Conceptually:

```text
┌─────────────────────────────────────────────┐
│              KnowledgeOS Ecosystem           │
│                                             │
│   ┌─────────────────────────────────────┐   │
│   │          KnowledgeOS Core           │   │
│   │                                     │   │
│   │ Knowledge │ Governance │ Assurance  │   │
│   └─────────────────────────────────────┘   │
│                                             │
│   Claude       Codex       CI/CD            │
│   Agent        Agent       tooling          │
│                                             │
└─────────────────────────────────────────────┘
          │            │             │
          ▼            ▼             ▼
       GitHub        Jira        Cloud/Infra
```

The outer systems interact with the ecosystem without becoming its core.

---

# 110.15 — Agent harness boundary

This resolves an earlier architectural question.

Claude and Codex are:

$$
EcosystemParticipants.
$$

Their harnesses are:

$$
AgentOperatingLayers.
$$

KnowledgeOS remains:

$$
SemanticKnowledge+
Governance+
Evidence+
Assurance
$$

according to the actual implementation.

---

# 110.16 — Experiment 4

Codex reads KnowledgeOS architecture.

Does that make Codex part of KnowledgeOS?

Not necessarily.

Expected:

$$
Codex=EcosystemParticipant.
$$

### Result

$$
\boxed{\text{PASS}}
$$

---

# 110.17 — But the relationship must be explicit

We should model:

$$
Codex
\overset{consumes}{\longrightarrow}
KnowledgeOS.
$$

And potentially:

$$
Codex
\overset{produces}{\longrightarrow}
Evidence.
$$

This is more informative than simply placing Codex inside the same directory.

---

# 110.18 — External knowledge sources

KnowledgeOS may ingest:

$$
Git
$$

$$
Jira
$$

$$
Confluence
$$

$$
documents
$$

$$
runtime systems.
$$

These should be modeled as:

$$
KnowledgeSources.
$$

Not automatically as KnowledgeOS components.

---

# 110.19 — Experiment 5

Jira contains a change ticket.

KnowledgeOS imports it as evidence.

Expected:

$$
Jira=EXTERNAL.
$$

$$
JiraTicket=ExternalEvidenceSource.
$$

### Result

$$
\boxed{\text{PASS}}
$$

---

# 110.20 — Source authority

External systems may remain authoritative for certain facts.

For example:

$$
Jira
\rightarrow
ChangeRequestAuthority.
$$

KnowledgeOS may preserve a representation:

$$
KnowledgeOS
\rightarrow
Reference/Projection.
$$

This distinction prevents duplicate authority.

---

# 110.21 — Experiment 6

Jira is authoritative for ticket status.

KnowledgeOS stores:

```text
ticket_status
```

Expected:

KnowledgeOS should not silently become a competing authority unless explicitly designed to do so.

### Result

$$
\boxed{\text{PASS}}
$$

---

# 110.22 — Projection versus ownership

This gives us:

$$
Projection
\neq
Ownership.
$$

KnowledgeOS may contain:

$$
Projection(X)
$$

without owning:

$$
Truth(X).
$$

This is particularly important for evidence.

---

# 110.23 — Evidence source graph

We can therefore model:

$$
Source
\rightarrow
Observation
\rightarrow
KnowledgeOS.
$$

For example:

```text
Git
 │
 ▼
Commit
 │
 ▼
Observation
 │
 ▼
KnowledgeOS Evidence
```

---

# 110.24 — Experiment 7

Git commit is imported.

Expected:

KnowledgeOS records:

* source;
* commit identity;
* timestamp;
* repository;
* relationship to observed change.

### Result

$$
\boxed{\text{PASS if implemented}}
$$

The architectural requirement is clear; implementation remains to be evidenced.

---

# 110.25 — Boundary discovery through dependency direction

One useful reconstruction technique is:

$$
DependencyDirection.
$$

If:

$$
A\rightarrow B
$$

we ask:

> Which system owns the contract?

The caller is not necessarily the owner.

---

# 110.26 — Experiment 8

KnowledgeOS calls GitHub API.

Expected:

$$
GitHub=External.
$$

KnowledgeOS is the consumer.

### Result

$$
\boxed{\text{PASS}}
$$

---

# 110.27 — Reverse integration

If GitHub webhook calls KnowledgeOS:

$$
GitHub
\rightarrow
KnowledgeOS.
$$

GitHub remains external.

The direction of invocation does not determine system ownership.

---

# 110.28 — Experiment 9

GitHub sends an event to KnowledgeOS.

Expected:

$$
GitHub=EXTERNAL
$$

$$
KnowledgeOS=CORE.
$$

### Result

$$
\boxed{\text{PASS}}
$$

---

# 110.29 — Infrastructure boundary

Infrastructure requires another distinction.

For example:

```text
Podman
Kubernetes
Docker
PostgreSQL
Redis
Kafka
```

may be essential to operation.

But:

$$
InfrastructureDependency
\neq
KnowledgeModel.
$$

---

# 110.30 — Experiment 10

KnowledgeOS cannot run without PostgreSQL.

Expected:

PostgreSQL is operationally essential.

But it is not automatically a KnowledgeOS semantic component.

### Result

$$
\boxed{\text{SUPPORTING}}
$$

---

# 110.31 — Runtime boundary

A runtime process may be part of KnowledgeOS even if its source resides elsewhere.

Conversely, source code may exist in the repository but never participate in the current runtime.

Therefore:

$$
RuntimeMembership
$$

must be separately established.

---

# 110.32 — Experiment 11

A repository contains:

```text
legacy-indexer/
```

It is not deployed.

Expected:

$$
LEGACY
$$

or:

$$
UNKNOWN.
$$

Not:

$$
CORE\ RuntimeComponent.
$$

### Result

$$
\boxed{\text{PASS}}
$$

---

# 110.33 — The "dead code" problem

A large repository can contain:

* experimental code;
* abandoned prototypes;
* old migrations;
* historical scripts;
* unused libraries.

Therefore:

$$
RepositoryMembership
\neq
SystemMembership.
$$

This is one of the most important findings of the reconstruction methodology.

---

# 110.34 — Experiment 12

A module has 5,000 lines of sophisticated knowledge code.

No build references it.

Expected:

It cannot be counted as operational KnowledgeOS functionality.

### Result

$$
\boxed{\text{PASS}}
$$

---

# 110.35 — Build graph

We therefore inspect:

$$
BuildGraph.
$$

Questions:

* Is the module compiled?
* Is it packaged?
* Is it included in deployment?
* Is it referenced?
* Is it tested?

This gives stronger evidence of actual membership.

---

# 110.36 — Experiment 13

Module is compiled but never deployed.

Expected:

$$
IMPLEMENTED
$$

but:

$$
NOT\ OPERATIONAL.
$$

### Result

$$
\boxed{\text{PASS}}
$$

---

# 110.37 — Deployment graph

We then construct:

$$
DeploymentGraph.
$$

Example:

$$
Repository
\rightarrow
BuildArtifact
\rightarrow
Container
\rightarrow
Deployment
\rightarrow
Runtime.
$$

Only components with an established path should be treated as operational.

---

# 110.38 — Experiment 14

Source component exists.

No build artifact contains it.

Expected:

$$
NonOperational.
$$

### Result

$$
\boxed{\text{PASS}}
$$

---

# 110.39 — Runtime graph

Finally:

$$
RuntimeGraph.
$$

A component is operationally part of the system if we can establish:

$$
Artifact
\rightarrow
Deployment
\rightarrow
RuntimeInstance.
$$

---

# 110.40 — Experiment 15

Deployment manifest contains service X.

Runtime never starts it.

Expected:

$$
DeploymentDefined
$$

but:

$$
RuntimeNotObserved.
$$

### Result

$$
\boxed{\text{PASS}}
$$

---

# 110.41 — Boundary confidence

Every boundary classification should have confidence:

$$
Confidence\in
\{
High,
Medium,
Low
\}.
$$

Example:

$$
CoreComponent
+
BuildEvidence
+
RuntimeEvidence
\Rightarrow
High.
$$

---

# 110.42 — Boundary evidence record

For each component:

$$
BoundaryEvidence=
(
Classification,
Reason,
Evidence,
Confidence,
Date
).
$$

This prevents later arguments based on memory.

---

# 110.43 — Experiment 16

Someone later asks:

> Why is `.claude/` classified as agent-specific?

Expected:

KnowledgeOS can point to:

* configuration semantics;
* invocation;
* hooks;
* agent-specific behavior.

### Result

$$
\boxed{\text{PASS}}
$$

---

# 110.44 — Context map

Once classification is complete, we can build a DDD-style context map:

```text
                  ┌─────────────────────┐
                  │     KnowledgeOS     │
                  │                     │
                  │ Knowledge           │
                  │ Governance          │
                  │ Evidence            │
                  │ Assurance           │
                  └─────────┬───────────┘
                            │
              ┌─────────────┼─────────────┐
              │             │             │
              ▼             ▼             ▼
           Claude         Codex          CI/CD
           Agent          Agent         Platform
              │             │             │
              └─────────────┼─────────────┘
                            │
                            ▼
                    External Sources
              Git / Tickets / Documents /
                   Runtime Systems
```

This is the **logical ecosystem map**, not yet the final physical topology.

---

# 110.45 — Upstream/downstream relationships

For each external interaction we should determine:

$$
Upstream
$$

and:

$$
Downstream.
$$

For example:

$$
Git
\rightarrow
KnowledgeOS
$$

may mean Git is upstream for source evidence.

KnowledgeOS may be downstream.

---

# 110.46 — Experiment 17

KnowledgeOS publishes a governance result into Jira.

Now:

$$
KnowledgeOS
\rightarrow
Jira.
$$

Jira is downstream for that interaction.

The same external system can therefore be:

$$
Upstream
$$

for one relationship and:

$$
Downstream
$$

for another.

---

# 110.47 — Relationship semantics

We should avoid generic:

> "integrates with."

Instead classify relationships:

$$
Consumes
$$

$$
Produces
$$

$$
Reads
$$

$$
Writes
$$

$$
Publishes
$$

$$
Subscribes
$$

$$
Verifies
$$

$$
Authorizes
$$

$$
Projects.
$$

This makes the context map executable.

---

# 110.48 — Experiment 18

Codex reads architecture knowledge and writes code.

Expected:

$$
Codex
\overset{consumes}{\rightarrow}
KnowledgeOS
$$

and:

$$
Codex
\overset{produces}{\rightarrow}
EngineeringChange.
$$

### Result

$$
\boxed{\text{PASS}}
$$

---

# 110.49 — System boundary invariants

### Boundary invariant

$$
\boxed{
I_{Boundary}:
A component must not be classified as Core solely because it is stored in the same repository.
}
$$

### Authority invariant

$$
\boxed{
I_{AuthorityBoundary}:
Data\ projection\ does\ not\ automatically\ transfer\ authority.
}
$$

### Runtime invariant

$$
\boxed{
I_{RuntimeMembership}:
Source\ existence\ does\ not\ establish\ runtime\ membership.
}
$$

### Agent invariant

$$
\boxed{
I_{AgentBoundary}:
Agent-specific\ operating\ artifacts\ must\ remain\ distinguishable\ from\ authoritative\ KnowledgeOS\ knowledge.
}
$$

### External-system invariant

$$
\boxed{
I_{External}:
External\ systems\ remain\ externally\ owned\ unless\ explicit\ system-boundary\ evidence\ establishes\ otherwise.
}
$$

---

# 110.50 — The first actual system map

At this point the target shape becomes:

```text
                         ┌───────────────────────┐
                         │   Organizational       │
                         │   Governance / Intent  │
                         └───────────┬───────────┘
                                     │
                                     ▼
                    ┌────────────────────────────────┐
                    │          KNOWLEDGEOS            │
                    │                                │
                    │  Knowledge                     │
                    │  Evidence                      │
                    │  Governance                    │
                    │  Assurance                     │
                    │  Semantic Relationships         │
                    └───────────────┬────────────────┘
                                    │
             ┌──────────────────────┼──────────────────────┐
             │                      │                      │
             ▼                      ▼                      ▼
        Claude Agent           Codex Agent            Engineering
        Operating Layer        Operating Layer         Systems
             │                      │                      │
             └──────────────────────┼──────────────────────┘
                                    │
                                    ▼
                         External Knowledge Sources
                                    │
             ┌──────────────────────┼─────────────────────┐
             ▼                      ▼                     ▼
            Git                   Jira                Runtime
```

The exact components inside each box remain an empirical question.

---

# 110.51 — What Step 110 has established

We have established the **boundary method**, not yet the final component list.

The important result is that we now know how to decide whether an artifact belongs to:

$$
Core
$$

versus:

$$
Ecosystem
$$

versus:

$$
External.
$$

---

# 110.52 — Step 110 verdict

$$
\boxed{
\textbf{STEP 110 — SYSTEM BOUNDARY MODEL: PASS}
}
$$

The conceptual boundary is coherent.

The empirical boundary remains:

$$
\boxed{
KnowledgeOS_{ActualBoundary}=TBD
}
$$

until the repository and operational evidence are mapped.

---

# 110.53 — The next step

## Step 111 — Actual Component Inventory

Now we go one level deeper.

For every **actual** component we will establish:

$$
\boxed{
Who owns what?
}
$$

More precisely:

$$
Component
\rightarrow
Responsibility
\rightarrow
Data
\rightarrow
Behavior
\rightarrow
Interface
\rightarrow
Dependency
\rightarrow
Evidence
\rightarrow
Runtime.
$$

This is where we begin reconstructing the **real internal architecture** of KnowledgeOS rather than the conceptual architecture.

And one particularly important question will be tested:

$$
\boxed{
\text{Is there actually one coherent KnowledgeOS core, or has the current implementation evolved into several partially overlapping subsystems?}
}
$$

That question will be central to Step 111.
