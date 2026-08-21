---
source:
  original_name: "design_patterns.md"
  original_path: "docs/knowledgeos/brainstorming/design_patterns.md"
  detected_timestamp: "2026-08-20 12:39:02"
  timestamp_source: filesystem-mtime
  timestamp_confidence: medium
classification:
  theme: "05-patterns-technology"
  type: analysis
status:
  authoritative: false
  proposed: false
---

Yes. For KnowledgeOS, **Decorator is useful**, but I would not make it a default pattern. With the DDD architecture we've developed, some GoF patterns fit extremely well, while others should be explicitly discouraged.

The key principle should be:

> **Use a design pattern only when it expresses a real KnowledgeOS domain or architectural problem. Do not build KnowledgeOS as a catalogue of patterns.**

## 1. My recommended pattern map for KnowledgeOS

I would divide them into four levels:

```text
KnowledgeOS Pattern Architecture

1. Domain Patterns
   ↓
2. Application Patterns
   ↓
3. Infrastructure / Integration Patterns
   ↓
4. Runtime / Platform Patterns
```

And the patterns should serve the DDD boundaries.

---

# 2. Decorator — YES, particularly valuable

Decorator could become one of the most useful patterns in KnowledgeOS.

Why?

KnowledgeOS frequently needs to add **cross-cutting responsibilities** around an existing capability without changing the capability itself.

For example:

```text
KnowledgeRepository
```

can be decorated with:

```text
AuditDecorator
EvidenceDecorator
AuthorizationDecorator
CachingDecorator
MetricsDecorator
```

So:

```text
Application
    |
    v
AuditedRepository
    |
    v
AuthorizedRepository
    |
    v
CachedRepository
    |
    v
PostgresRepository
```

This is much cleaner than putting everything into:

```text
KnowledgeRepository
```

---

# 3. Example: Knowledge Delivery

Suppose the core port is:

```java
interface KnowledgeReader {
    KnowledgeResult read(KnowledgeQuery query);
}
```

We can compose:

```text
KnowledgeReader
      |
      v
AuthorizationDecorator
      |
      v
EvidenceDecorator
      |
      v
AuditDecorator
      |
      v
CachingDecorator
      |
      v
KnowledgeReaderAdapter
```

Each decorator has one responsibility.

That fits extremely well with our **KnowledgeOS Runtime** idea.

---

# 4. But Decorator should NOT modify domain authority

This is critical.

Bad:

```text
KnowledgeProduct
    |
    v
Decorator
    |
    v
"make this knowledge approved"
```

A decorator should not secretly change domain meaning.

Good:

```text
KnowledgeReader
    |
    +-- authorization
    +-- audit
    +-- metrics
    +-- caching
```

The domain remains authoritative.

---

# 5. Adapter — absolutely fundamental

We already have Hexagonal Architecture.

Therefore Adapter is not optional decoration.

Example:

```text
KnowledgeRepository
        ^
        |
   Port / Interface
        ^
        |
+-------+-------+
|       |       |
Git   Postgres  API
```

KnowledgeOS can therefore replace:

```text
PostgreSQL
```

with:

```text
Git
S3
Event Store
Another DB
```

without changing the domain.

---

# 6. Strategy — extremely important

This is probably the second most valuable GoF pattern for KnowledgeOS.

KnowledgeOS will have many policies that vary by domain or installation.

For example:

```text
EvidenceQualificationStrategy
```

could have:

```text
ArchitectureEvidenceStrategy
SecurityEvidenceStrategy
ComplianceEvidenceStrategy
MigrationEvidenceStrategy
```

Or:

```text
AuthorityResolutionStrategy
```

could have:

```text
ArchitectureBoardStrategy
ProductOwnerStrategy
RegulatoryAuthorityStrategy
```

Then:

```text
Governance
     |
     v
Strategy
     |
     +-- Architecture
     +-- Security
     +-- Compliance
```

This fits DDD very well because the variation represents **business policy**, not merely technical configuration.

---

# 7. State — essential

KnowledgeOS has lifecycle everywhere.

For example:

```text
KnowledgeProduct

Draft
  ↓
Candidate
  ↓
Validated
  ↓
Approved
  ↓
Active
  ↓
Deprecated
```

State pattern can encapsulate behavior associated with lifecycle states.

However, I would be careful.

For simple lifecycle transitions, an explicit enum + domain transition policy may be better than a large State object hierarchy.

So:

> **State pattern: use when behavior genuinely changes by state; otherwise use a domain state machine/value object.**

---

# 8. Command — very important

KnowledgeOS is naturally command-oriented.

Examples:

```text
CreateKnowledgeProduct
ApproveKnowledgeProduct
AddEvidence
QualifyEvidence
ChangeAuthority
DeprecateKnowledgeProduct
ResolveEvidenceConflict
```

This fits perfectly with:

```text
Command
    ↓
Application Handler
    ↓
Aggregate
```

It also gives us:

* auditability
* queues
* retries
* authorization
* event generation

This becomes especially valuable when we later introduce the Digitalization Robot.

The robot can produce:

```text
Command
```

rather than directly manipulating the domain.

That is a **very important security boundary**.

---

# 9. Chain of Responsibility — useful for qualification

This one fits KnowledgeOS surprisingly well.

For example:

```text
Evidence Candidate

    ↓
Provenance Check

    ↓
Integrity Check

    ↓
Authority Check

    ↓
Freshness Check

    ↓
Policy Check

    ↓
Qualified Evidence
```

Each validator can be independent:

```text
EvidenceValidator
```

with:

```text
ProvenanceValidator
IntegrityValidator
AuthorityValidator
FreshnessValidator
PolicyValidator
```

This could implement our **Evidence Qualification Boundary**.

But there is a distinction:

If **all validators must run**, a pipeline is often clearer than classic Chain of Responsibility.

So I would call the domain concept:

> **Evidence Qualification Pipeline**

and implement it using Chain of Responsibility only if the semantics fit.

---

# 10. Observer / Domain Events — essential

KnowledgeOS should strongly use Domain Events.

Example:

```text
KnowledgeProductApproved
        |
        +--> Semantic Projection
        +--> Audit
        +--> Search Index
        +--> Notification
```

This is better expressed as:

```text
Aggregate
   |
   v
Domain Event
   |
   +---- Subscriber
```

I would use **Domain Events**, not generic Observer objects, as the domain abstraction.

---

# 11. Factory — useful

Factories are useful when creating valid domain objects is non-trivial.

For example:

```text
EvidenceCandidateFactory
KnowledgeProductFactory
GovernanceDecisionFactory
```

But don't create factories for trivial constructors.

Good:

```text
KnowledgeProduct.create(...)
```

may be sufficient.

Use a factory when construction requires:

* validation
* multiple objects
* policy
* initialization rules

---

# 12. Builder — mostly application/API concern

Builder can be useful for:

```text
KnowledgeQuery
EvidenceReport
KnowledgeContext
```

But I would avoid builders inside the core domain unless construction really is complex.

---

# 13. Facade — useful at boundaries

For example:

```text
KnowledgeOSFacade
```

could expose:

```text
createProduct()
approve()
query()
explain()
trace()
```

But the Facade should not become a **God Service**.

It is appropriate for:

* external APIs
* CLI
* SDK
* integration boundaries

---

# 14. Proxy — very useful for authorization

Example:

```text
KnowledgeReader
      |
      v
AuthorizedKnowledgeReaderProxy
      |
      v
RealKnowledgeReader
```

However, in a DDD/Hexagonal system I would usually prefer an explicit:

```text
Authorization Policy
```

or Decorator.

So Proxy is possible, but not my first choice.

---

# 15. Composite — useful for Knowledge Products

Knowledge can naturally form structures:

```text
Architecture Product
   |
   +-- Domain
   +-- Decisions
   +-- Methods
   +-- Rules
   +-- Evidence
```

Composite could be useful for hierarchical knowledge structures.

But don't confuse:

```text
Composite pattern
```

with:

```text
Knowledge Graph
```

The graph is a domain/semantic model; Composite is only an object composition mechanism.

---

# 16. Template Method — probably avoid

KnowledgeOS should favor composition over inheritance.

Instead of:

```text
BaseEvidenceValidator
       |
       +-- SecurityValidator
       +-- ArchitectureValidator
```

prefer:

```text
EvidenceQualificationPipeline

    +-- SecurityPolicy
    +-- ArchitecturePolicy
```

This works better with DDD and extension.

---

# 17. Singleton — strongly discourage

I would put this into the KnowledgeOS architecture rules:

> **Singleton is prohibited in the domain.**

Why?

It creates:

* hidden global state
* poor testability
* hidden dependencies
* concurrency problems

Use dependency injection and a Composition Root instead.

---

# 18. Flyweight — probably irrelevant

KnowledgeOS is not primarily a memory optimization problem.

Don't use it unless profiling demonstrates a genuine need.

---

# 19. Visitor — probably avoid initially

Visitor can be useful for complex knowledge structures, but it introduces coupling between operations and domain structure.

KnowledgeOS should first favor:

```text
Domain Services
Strategies
Policies
Queries
```

before introducing Visitor.

---

# 20. Memento — interesting for KnowledgeOS

This deserves special attention.

KnowledgeOS needs historical reconstruction.

But I would not call the domain abstraction a Memento.

Our domain requirement is:

```text
Knowledge History
Evidence History
Governance History
```

That could be implemented using:

* event sourcing
* immutable events
* snapshots

So:

> **Memento is conceptually related, but Event Sourcing is probably the stronger architectural mechanism for KnowledgeOS where historical reconstruction is a real requirement.**

---

# 21. The most important non-GoF patterns

These are actually more important than most GoF patterns.

## Aggregate Root

Essential.

```text
KnowledgeProduct
```

protects its invariants.

---

## Repository

Essential at aggregate boundaries.

```text
KnowledgeProductRepository
EvidenceRepository
GovernanceRepository
```

---

## Domain Event

Essential.

```text
KnowledgeApproved
EvidenceQualified
AuthorityChanged
```

---

## Domain Service

Use when behavior doesn't naturally belong to an entity/value object.

Examples:

```text
AuthorityResolver
EvidenceQualifier
ConflictResolver
```

---

## Specification

I would add this to our pattern catalogue.

Very useful for KnowledgeOS.

Example:

```text
EvidenceIsQualified
KnowledgeProductCanBeActivated
AuthorityCanApprove
```

Composed specifications:

```text
EvidenceIsQualified

AND

EvidenceHasAuthority

AND

EvidenceIsFresh
```

This is extremely useful for governance.

---

# 22. Policy is also extremely important

I would make **Policy** a first-class KnowledgeOS concept.

Examples:

```text
EvidenceQualificationPolicy

AuthorityResolutionPolicy

KnowledgeActivationPolicy

ConflictResolutionPolicy
```

And then:

```text
Policy
   |
   v
Strategy
```

Strategy becomes the implementation mechanism when policy varies.

---

# 23. My KnowledgeOS Pattern Catalogue

I would therefore create this:

### Tier 1 — Mandatory architectural/domain patterns

```text
DDD
Bounded Context
Aggregate Root
Value Object
Repository
Domain Event
Domain Service
Specification
Policy
Hexagonal Architecture
```

### Tier 2 — Frequently useful GoF patterns

```text
Adapter
Strategy
Decorator
Command
Factory
State
Facade
Composite
```

### Tier 3 — Selective patterns

```text
Chain of Responsibility
Proxy
Builder
Observer
Memento
Bridge
CQRS
Event Sourcing
Saga
Outbox
```

### Tier 4 — Avoid by default

```text
Singleton
Flyweight
Visitor
Template Method
```

Not because these patterns are "bad", but because **they don't currently solve a central KnowledgeOS problem**.

---

# 24. Where Decorator fits in our architecture

This is the part I would actually put into the architecture:

```text
                    KnowledgeOS Runtime

                          Request
                             |
                             v
                  +--------------------+
                  | Authorization      |
                  | Decorator           |
                  +--------------------+
                             |
                             v
                  +--------------------+
                  | Evidence           |
                  | Decorator           |
                  +--------------------+
                             |
                             v
                  +--------------------+
                  | Audit              |
                  | Decorator           |
                  +--------------------+
                             |
                             v
                  +--------------------+
                  | Core Port           |
                  +--------------------+
                             |
                             v
                     Infrastructure
```

This is a very clean use of Decorator.

But the **domain itself remains clean**:

```text
Domain
  |
  +-- no decorators
  +-- no Spring
  +-- no database
  +-- no HTTP
```

The runtime composes the behavior.

---

# 25. Final architecture principle

I would add this to the KnowledgeOS Architecture Constitution:

> **KnowledgeOS shall use design patterns as local solutions to demonstrated domain or architectural problems. Pattern selection shall follow DDD boundaries and invariant requirements; patterns shall not define the domain model.**

And specifically:

> **Decorator, Adapter, Strategy, Command, State, Factory, Domain Events, Specification, Repository, and Policy are first-class candidates for KnowledgeOS. Their use must remain subordinate to bounded-context ownership and domain invariants.**

That gives us something much better than "KnowledgeOS uses GoF patterns."

We get a **pattern language for the KnowledgeOS Kernel**—with DDD as the foundation, POA selecting the architectural patterns, and GoF patterns solving local implementation problems.
