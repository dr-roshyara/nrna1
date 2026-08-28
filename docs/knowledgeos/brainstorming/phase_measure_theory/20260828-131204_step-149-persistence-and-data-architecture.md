# Step 149 — Persistence & Data Architecture

We now have:

* the current-state model;
* the target architecture;
* the delta map;
* the Golden Trace;
* the domain model;
* the interaction/event model;
* the API boundary.

The next question is:

> **Where does each piece of knowledge and state actually live?**

This is where we need to be particularly disciplined.

The answer should **not** be:

$$
KnowledgeOS = OneDatabase.
$$

Nor should it be:

$$
KnowledgeOS = GraphDatabase.
$$

The correct architecture is:

$$
\boxed{
Each\ bounded\ context\ owns\ its\ authoritative\ transactional\ state.
}
$$

Other representations are projections.

---

# 149.1 — Persistence principle

The fundamental rule is:

$$
\boxed{
One\ authoritative\ owner\ per\ state.
}
$$

For example:

```text id="p8m3q2"
Decision
   ↓
Governance owns it

Rule
   ↓
Assurance owns it

Evidence
   ↓
Evidence context owns it

Action
   ↓
Action context owns it

Execution fact
   ↓
External engineering system may own it
```

The Graph does not own any of these merely because it can represent them.

---

# 149.2 — Storage is not authority

This distinction is essential.

Suppose the same Decision exists in:

```text id="m4q8p2"
Governance DB
Graph
Search index
Cache
Document
```

These are not five authorities.

There is:

$$
One\ authority
+
Four\ representations.
$$

---

# 149.3 — Persistence topology

The target topology becomes:

```text id="x7m3q8"
                  KnowledgeOS
                       │
        ┌──────────────┼───────────────┐
        ▼              ▼               ▼
  Transactional     Evidence        Read Models
     State            Store             │
        │              │                │
        └──────┬───────┘                │
               ▼                        ▼
             Events                  Search
               │
               ▼
              Graph
               │
               ▼
            Context
```

---

# 149.4 — Transactional persistence

The transactional store contains:

* aggregate state;
* lifecycle state;
* invariant-protected data;
* identifiers;
* references.

For example:

```text id="n8q3m2"
Decision
Policy
Rule
Claim
Verification
Finding
Action
Authorization
```

The exact database technology is not yet an architecture decision.

---

# 149.5 — Relational database as default

For the initial implementation, a relational database is a strong default because the first Golden Trace requires:

* transactional consistency;
* constraints;
* lifecycle state;
* audit references;
* structured relationships;
* deterministic queries.

Therefore:

$$
\boxed{
Relational\ persistence\ should\ be\ the\ default\ transactional\ foundation.
}
$$

This does not prohibit other stores later.

---

# 149.6 — Do not start with multiple databases

Although the target architecture is polyglot-capable, the first implementation should avoid:

```text id="q7m3m8"
PostgreSQL
+
MongoDB
+
Neo4j
+
Elasticsearch
+
Redis
+
Kafka
```

from day one.

That would introduce infrastructure complexity before the domain has been proven.

---

# 149.7 — Initial persistence architecture

A sensible first implementation is:

```text id="v8q3m2"
                PostgreSQL
                    │
       ┌────────────┼────────────┐
       ▼            ▼            ▼
   Governance    Assurance     Agent/Action
       │            │            │
       └────────────┼────────────┘
                    ▼
                  Outbox
                    │
                    ▼
              Projection Layer
                    │
          ┌─────────┼─────────┐
          ▼         ▼         ▼
        Graph     Search     Cache
```

Whether these are physically separate schemas, databases or services is a later deployment decision.

---

# 149.8 — Bounded-context ownership

A candidate ownership matrix:

| Concept            | Owner                                       |
| ------------------ | ------------------------------------------- |
| Decision           | Governance                                  |
| Policy             | Governance                                  |
| Exception          | Governance                                  |
| Claim              | Knowledge                                   |
| Rule               | Assurance                                   |
| Verification       | Assurance                                   |
| Finding            | Assurance                                   |
| Evidence           | Evidence                                    |
| Agent              | Agent                                       |
| Session            | Agent                                       |
| Task               | Agent                                       |
| Recommendation     | Agent                                       |
| Action             | Action/Agent                                |
| Authorization      | Authorization                               |
| Execution fact     | Engineering integration / execution context |
| Graph relationship | Graph projection                            |

The final ownership needs validation against the existing system.

---

# 149.9 — Why Graph is last

The graph consumes state from the authoritative contexts.

Therefore:

$$
DomainState
\rightarrow
Events
\rightarrow
GraphProjection.
$$

Not:

$$
Graph
\rightarrow
DomainState.
$$

This keeps graph availability from becoming a domain consistency dependency.

---

# 149.10 — Graph storage

The target graph may eventually use a graph database.

But that is an implementation choice.

The architectural requirement is:

$$
Graph
=
relationship\ projection.
$$

We should first prove the relationship model using the simplest viable technology.

---

# 149.11 — Could PostgreSQL initially provide the graph?

Yes.

For the first Golden Trace, explicit relationship tables may be sufficient:

```text id="x3m8q2"
relationship
├── source_id
├── source_type
├── relationship_type
├── target_id
└── metadata
```

This allows us to validate:

$$
Decision
\rightarrow
Rule
\rightarrow
Verification
\rightarrow
Evidence.
$$

before introducing specialized graph infrastructure.

---

# 149.12 — Important architectural consequence

Therefore:

$$
\boxed{
Graph\ semantics\ first,\ graph\ technology\ second.
}
$$

This avoids technology-driven architecture.

---

# 149.13 — Evidence persistence

Evidence requires a different strategy.

We should distinguish:

$$
EvidenceMetadata
$$

from:

$$
EvidencePayload.
$$

For example:

```text id="m8q2p3"
EvidenceMetadata
├── evidenceId
├── source
├── subject
├── capturedAt
├── provenance
└── externalReference
```

while the actual payload might remain in:

* external system;
* object storage;
* log system;
* artifact repository.

---

# 149.14 — Evidence references

The preferred pattern is:

$$
KnowledgeOS
\rightarrow
EvidenceReference
\rightarrow
ExternalArtifact.
$$

This avoids turning KnowledgeOS into a universal data lake.

---

# 149.15 — Evidence immutability

Evidence should behave as append-oriented state:

$$
E_1
\rightarrow
E_2
\rightarrow
E_3.
$$

not:

$$
E_1
\leftarrow
overwrite.
$$

This preserves historical reconstruction.

---

# 149.16 — Content-addressable evidence

Where practical, evidence can include:

$$
ContentHash.
$$

For example:

```text id="q6m8p2"
Evidence E42
hash = H(payload)
```

Then later:

$$
H(payload_{current}) = H_{stored}
$$

can establish integrity.

---

# 149.17 — Evidence does not have to be blockchain

A cryptographic hash plus controlled provenance can often provide the required integrity semantics without introducing unnecessary distributed-ledger infrastructure.

Thus:

$$
\boxed{
Integrity\ requirement
\neq
Blockchain\ requirement.
}
$$

---

# 149.18 — Agent memory persistence

Agent memory should remain separate from authoritative knowledge.

Potentially:

```text id="v4q8m2"
Agent Memory Store
├── session context
├── working notes
├── preferences
└── candidate knowledge
```

But:

$$
Memory
\neq
GovernedKnowledge.
$$

---

# 149.19 — Memory authority

A memory record can reference:

$$
ClaimID.
$$

But it should not redefine the Claim.

For example:

```text id="p7m3q8"
Memory:
"Nexus uses version X"

Claim:
"Nexus version = X"
```

The memory points toward the Claim.

It does not establish the Claim.

---

# 149.20 — Memory promotion

The persistence flow becomes:

```text id="n8q2m4"
Agent Memory
      │
      ▼
Candidate Claim
      │
      ▼
Evidence
      │
      ▼
Verification
      │
      ▼
Governed Claim
```

This is the persistent version of the memory-promotion architecture.

---

# 149.21 — Search index

Search is another projection.

For example:

```text id="x5m8q2"
Governance
Knowledge
Evidence
Assurance
       │
       ▼
    Indexer
       │
       ▼
 Search Index
```

The index is optimized for retrieval.

It is not authoritative.

---

# 149.22 — Search consistency

Search may therefore be:

$$
EventuallyConsistent.
$$

If a Decision changes:

```text id="g7q3m8"
Governance DB
    ↓
commit
    ↓
event
    ↓
search index
```

A short delay is acceptable unless the use case explicitly requires immediate consistency.

---

# 149.23 — Cache

Likewise:

$$
Cache
\neq
SourceOfTruth.
$$

A Context cache may be useful:

```text id="j8m3q2"
ContextID
ContextVersion
ExpiresAt
```

But high-risk actions must validate freshness.

---

# 149.24 — Cache invalidation

The event stream can invalidate or refresh caches:

```text id="r7m3q8"
PolicyActivated
     ↓
invalidate affected contexts
```

This is much safer than relying purely on time-to-live.

---

# 149.25 — Event persistence

The transactional outbox provides the minimum event durability.

```text id="q3m8p2"
Aggregate State
      +
Outbox Event
      │
      ▼
Atomic Commit
```

Then:

$$
Outbox
\rightarrow
Publisher.
$$

---

# 149.26 — Event history versus event sourcing

We should distinguish:

$$
EventHistory
$$

from:

$$
EventSourcing.
$$

KnowledgeOS needs the ability to reconstruct important historical actions.

It does **not yet follow** that every Aggregate should be event-sourced.

---

# 149.27 — Recommended initial approach

Use:

$$
StateModel
+
DomainEvents
+
Outbox
+
AuditHistory.
$$

Only introduce full event sourcing where a specific Aggregate genuinely benefits from it.

---

# 149.28 — Audit persistence

Audit data should answer:

> Who changed what, when, and through which operation?

For material state transitions:

```text id="v5q8m2"
AuditRecord
├── actor
├── operation
├── subject
├── before
├── after
├── timestamp
├── traceId
└── correlation
```

The exact retention model remains to be defined.

---

# 149.29 — Audit versus evidence

These are different.

$$
Audit
=
who\ changed\ system\ state.
$$

$$
Evidence
=
what\ was\ observed\ or\ produced.
$$

An audit record may itself become evidence in some contexts, but the concepts should remain separate.

---

# 149.30 — Audit versus event

Likewise:

$$
Event
=
domain\ fact.
$$

$$
Audit
=
accountability\ record.
$$

They may be generated together but serve different purposes.

---

# 149.31 — Persistence consistency matrix

We can now define the initial model:

| Data              | Authority           | Consistency        |
| ----------------- | ------------------- | ------------------ |
| Decision          | Governance DB       | Strong/local       |
| Policy            | Governance DB       | Strong/local       |
| Rule              | Assurance DB        | Strong/local       |
| Verification      | Assurance DB        | Strong/local       |
| Finding           | Assurance DB        | Strong/local       |
| Evidence metadata | Evidence store      | Strong/local       |
| Evidence payload  | External/reference  | Varies             |
| Action            | Action store        | Strong/local       |
| Authorization     | Authorization store | Strong/immediate   |
| Execution fact    | External/Execution  | External authority |
| Graph             | Projection          | Eventual           |
| Search            | Projection          | Eventual           |
| Context cache     | Projection          | Eventual           |
| Agent memory      | Agent-local         | Contextual         |

---

# 149.32 — Persistence boundary

The architecture therefore becomes:

```text id="c8m3q2"
               AUTHORITATIVE
                    │
        ┌───────────┼───────────┐
        ▼           ▼           ▼
   Governance    Assurance     Action
       DB            DB          DB
        │             │          │
        └─────────────┼──────────┘
                      ▼
                    Events
                      │
        ┌─────────────┼─────────────┐
        ▼             ▼             ▼
      Graph         Search        Context
    Projection    Projection     Projection
```

---

# 149.33 — One database or multiple?

There are two valid implementation options.

### Modular monolith

```text id="f5q8m2"
One PostgreSQL
├── governance schema
├── knowledge schema
├── assurance schema
├── evidence schema
└── agent schema
```

### Distributed deployment

```text id="m8q3v2"
Governance DB
Assurance DB
Evidence Store
Agent DB
```

Both can implement the same domain architecture.

---

# 149.34 — Recommended first implementation

For the Golden Trace:

$$
\boxed{
Modular\ monolith\ +\ relational\ persistence
}
$$

is likely the lowest-risk starting point.

This allows us to prove:

* Aggregate boundaries;
* contracts;
* events;
* authorization;
* evidence;
* verification.

without prematurely introducing distributed-system complexity.

---

# 149.35 — Why this is compatible with future decomposition

If bounded contexts are clean:

```text id="x6q2m8"
Governance Module
       ↓
Integration Event
       ↓
Assurance Module
```

can later become:

```text id="r8m3q2"
Governance Service
       ↓
Message Broker
       ↓
Assurance Service
```

without changing the semantic architecture.

That is exactly what we want.

---

# 149.36 — Persistence anti-pattern

Avoid:

```text id="v7m4q2"
KnowledgeOSEntity
├── governance
├── knowledge
├── evidence
├── assurance
├── agent
├── action
└── graph
```

This creates the giant shared model we have deliberately avoided.

---

# 149.37 — Another anti-pattern

Avoid:

```text id="p8m2q3"
Graph
  ↓
all domain state
```

because then every transaction becomes a graph transaction.

That would couple all contexts to graph technology.

---

# 149.38 — Another anti-pattern

Avoid:

```text id="q6m8p2"
Agent memory
      ↓
automatic promotion
      ↓
authoritative knowledge
```

The promotion process must remain governed.

---

# 149.39 — Another anti-pattern

Avoid:

```text id="m4q8p2"
Search index
      ↓
authoritative answer
```

Search tells us:

> What appears relevant.

Authority tells us:

> What is governed/valid.

---

# 149.40 — Persistence architecture invariant

We can now add:

$$
\boxed{
DATA-001:
Every authoritative state has exactly one logical owner.
}
$$

---

# 149.41 — DATA-002

$$
\boxed{
DATA-002:
Projections may duplicate data but cannot redefine authoritative state.
}
$$

---

# 149.42 — DATA-003

$$
\boxed{
DATA-003:
Historical evidence used for assurance must remain reconstructable.
}
$$

---

# 149.43 — DATA-004

$$
\boxed{
DATA-004:
Cross-context state changes are propagated through explicit contracts/events rather than shared transactional ownership.
}
$$

---

# 149.44 — DATA-005

$$
\boxed{
DATA-005:
Caches and search indexes are non-authoritative.
}
$$

---

# 149.45 — DATA-006

$$
\boxed{
DATA-006:
Graph state must be rebuildable from authoritative state/events.
}
$$

This is one of the most important graph requirements.

---

# 149.46 — Rebuildability

Suppose the graph is completely lost.

The architecture should allow:

```text id="x8q3m2"
Authoritative State
      +
Event History
      ↓
Graph Rebuild
```

Therefore:

$$
GraphLoss
\not\Rightarrow
KnowledgeLoss.
$$

---

# 149.47 — Search rebuildability

Likewise:

$$
SearchIndexLoss
\not\Rightarrow
KnowledgeLoss.
$$

The search index can be rebuilt.

---

# 149.48 — Context rebuildability

A Context Package should also be reproducible from:

$$
Authority
+
Knowledge
+
Evidence
+
Rules
+
Time
+
Scope.
$$

Thus:

$$
ContextID
$$

should ideally be explainable as a projection of those inputs.

---

# 149.49 — Context fingerprint

We can therefore derive:

$$
ContextFingerprint
=
Hash(
RelevantKnowledgeVersions,
Rules,
Governance,
Evidence,
Scope,
Time
).
$$

This does not need to be implemented in the first version, but it is a valuable architectural capability.

---

# 149.50 — Reproducibility

The stronger goal becomes:

> Given the same governed inputs and temporal context, KnowledgeOS can reconstruct the context that an agent received.

Formally:

$$
Context(t,S,A)
=
f(
Governance,
Knowledge,
Evidence,
Rules,
Scope,
Time,
Actor
).
$$

---

# 149.51 — This is the foundation of audit

If someone asks:

> Why did the agent recommend this on August 28?

we should be able to reconstruct:

$$
Context_{Aug28}.
$$

Not merely today's context.

---

# 149.52 — Persistence and auditability

The full audit chain therefore becomes:

```text id="n7m3q8"
Governance State
      ↓
Context Version
      ↓
Agent Recommendation
      ↓
Authorization
      ↓
Action
      ↓
Execution
      ↓
Evidence
      ↓
Verification
```

This is much stronger than a conventional application log.

---

# 149.53 — The "KnowledgeOS Ledger"

At this point we can introduce a useful conceptual term:

$$
\boxed{
KnowledgeOS\ Ledger
}
$$

Not a blockchain.

Rather:

> The set of authoritative state transitions, evidence references, events and provenance necessary to reconstruct governed engineering activity.

The Ledger is therefore a **semantic audit concept**, not necessarily a physical database.

---

# 149.54 — Ledger components

```text id="m8q2v3"
KnowledgeOS Ledger
├── Domain state
├── Domain events
├── Audit records
├── Evidence references
├── Authorization decisions
└── Trace relationships
```

The graph is a projection of this information.

---

# 149.55 — Why this matters

The platform's long-term differentiator may not be:

$$
LLM.
$$

It may be:

$$
\boxed{
Reconstructable\ governed\ engineering\ history.
}
$$

The AI becomes the actor that uses this substrate.

---

# 149.56 — Persistence architecture summary

We can now describe the target persistence model in one sentence:

$$
\boxed{
Authoritative\ bounded-context\ state
+
append-oriented\ evidence/events
+
rebuildable\ projections
+
governed\ context.
}
$$

---

# 149.57 — Step 149 verdict

The persistence architecture should therefore start conservatively:

$$
\boxed{
Modular\ relational\ core
}
$$

with:

$$
\boxed{
Outbox + Evidence + Projection
}
$$

and only later introduce specialized graph/search/storage technologies when the actual scale or query characteristics justify them.

The key invariant is:

$$
\boxed{
Storage\ technology\ must\ follow\ semantic\ ownership,
not\ define\ it.
}
$$

---

# Step 150 — KnowledgeOS Runtime Architecture

We now have the semantic model and the persistence model.

The next question is operational:

> **How does a real request travel through the running system?**

We will construct the **runtime architecture** from:

$$
Agent
\rightarrow
API
\rightarrow
Application
\rightarrow
Domain
\rightarrow
Persistence
\rightarrow
Events
\rightarrow
Projection
$$

and then connect it to:

$$
External\ Engineering\ Systems.
$$

This will let us distinguish three things that are currently easy to conflate:

$$
\boxed{
KnowledgeOS\ Platform
}
$$

$$
\boxed{
Agent\ Harness
}
$$

$$
\boxed{
Engineering\ Environment.
}
$$

That separation is especially important for the existing `.claude` / `.codex` architecture.
