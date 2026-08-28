# Step 147 — Domain Events & Integration Contracts

We now have the domain objects and Aggregate boundaries.

The next architectural layer is:

$$
\boxed{
How\ do\ those\ boundaries\ communicate?
}
$$

The guiding principle is:

$$
\boxed{
Aggregates\ communicate\ through\ explicit\ contracts,\ not\ shared\ object\ graphs.
}
$$

---

## 147.1 — Four interaction mechanisms

KnowledgeOS needs four distinct interaction mechanisms:

```text
Command
Query
Domain Event
Integration Event
```

They have different meanings.

| Mechanism             | Meaning                                          | Direction           |
| --------------------- | ------------------------------------------------ | ------------------- |
| **Command**           | Please perform this operation                    | Request             |
| **Query**             | Tell me current information                      | Read                |
| **Domain Event**      | Something meaningful happened                    | Internal fact       |
| **Integration Event** | Another context needs to know something happened | Cross-boundary fact |

---

# 147.2 — Command

A command expresses intent.

Examples:

```text
CreateDecision
ApproveDecision
ActivatePolicy
SubmitClaim
RequestVerification
RequestAction
AuthorizeAction
ExecuteAction
```

A command is not an event.

$$
Command
=
Intent.
$$

$$
Event
=
Fact.
$$

---

# 147.3 — Query

A query asks for information.

Examples:

```text
GetDecision
GetApplicablePolicies
GetKnowledge
GetEvidence
GetApplicableRules
GetOpenFindings
GetGovernedContext
GetActionStatus
```

Queries should not change domain state.

Therefore:

$$
\boxed{
Query \rightarrow no\ domain\ mutation.
}
$$

---

# 147.4 — Domain event

A domain event is emitted after a meaningful state transition.

For example:

$$
DecisionBecameEffective.
$$

This means:

> The Governance context has successfully changed its state.

It does **not** mean:

> Every other context must immediately do something.

---

# 147.5 — Integration event

An integration event is deliberately published across a context boundary.

For example:

$$
EffectiveDecisionPublished.
$$

The consumer may translate it into its own model.

Thus:

```text id="q8m3p2"
Governance
    │
    ▼
Domain Event
    │
    ▼
Integration Event
    │
    ▼
Knowledge
```

---

# 147.6 — Why the distinction matters

Without this distinction, the event bus becomes a dumping ground.

Everything becomes:

```text
SomethingHappenedEvent
```

and consumers become coupled to internal implementation details.

The architecture should instead expose only meaningful cross-context contracts.

---

# 147.7 — Governance commands

Candidate commands:

```text
CreateDecision
SubmitDecisionForReview
ApproveDecision
RejectDecision
ActivateDecision
SupersedeDecision
CreatePolicy
ActivatePolicy
RequestException
ApproveException
RejectException
ExpireException
```

Each command belongs to Governance.

---

# 147.8 — Governance events

Corresponding events:

```text
DecisionCreated
DecisionSubmittedForReview
DecisionApproved
DecisionRejected
DecisionBecameEffective
DecisionSuperseded

PolicyCreated
PolicyActivated

ExceptionRequested
ExceptionApproved
ExceptionRejected
ExceptionExpired
```

The exact event vocabulary should eventually be derived from the actual governance lifecycle.

---

# 147.9 — Knowledge commands

Candidate commands:

```text
CreateClaim
UpdateClaimStatus
AssociateEvidence
VerifyClaim
DisputeClaim
SupersedeClaim
```

Potential events:

```text
ClaimCreated
EvidenceAssociated
ClaimVerified
ClaimDisputed
ClaimSuperseded
```

---

# 147.10 — Evidence commands

Evidence should generally be created through ingestion operations.

Examples:

```text
CaptureObservation
RegisterEvidence
LinkEvidence
```

Events:

```text
ObservationCaptured
EvidenceRegistered
EvidenceLinked
```

The evidence lifecycle should remain conservative.

---

# 147.11 — Assurance commands

Candidate commands:

```text
RegisterRule
ActivateRule
RequestVerification
ExecuteVerification
CreateFinding
ResolveFinding
```

Events:

```text
RuleActivated
VerificationRequested
VerificationCompleted
FindingRaised
FindingResolved
```

---

# 147.12 — Agent commands

The Agent context has:

```text
StartSession
CreateTask
RequestContext
CreateRecommendation
RequestAction
CancelAction
```

Events:

```text
SessionStarted
TaskCreated
ContextProvided
RecommendationCreated
ActionRequested
ActionCancelled
```

---

# 147.13 — Authorization commands

Authorization is its own controlled boundary.

Candidate commands:

```text
EvaluateAuthorization
ApproveAction
DenyAction
RevokeAuthorization
```

Events:

```text
ActionAuthorized
ActionDenied
AuthorizationRevoked
```

---

# 147.14 — Action commands

The Action boundary receives:

$$
RequestAction.
$$

But actual execution should be separated.

Candidate:

```text
PrepareAction
AuthorizeAction
ExecuteAuthorizedAction
CancelAction
```

This prevents:

$$
RequestAction
$$

from accidentally becoming:

$$
ExecuteAction.
$$

---

# 147.15 — Execution events

Execution should report facts:

```text
ExecutionStarted
ExecutionCompleted
ExecutionFailed
ExecutionCancelled
```

The source of these events may be an external engineering system adapter.

---

# 147.16 — Golden Trace event chain

The reference scenario becomes:

```text id="m7q3p8"
DecisionBecameEffective
        │
        ▼
PolicyActivated
        │
        ▼
RuleActivated
        │
        ▼
ContextRequested
        │
        ▼
ContextProvided
        │
        ▼
VerificationRequested
        │
        ▼
EvidenceCaptured
        │
        ▼
VerificationCompleted
        │
        ▼
RecommendationCreated
        │
        ▼
ActionRequested
        │
        ▼
AuthorizationEvaluated
        │
        ├── DENIED
        │
        └── AUTHORIZED
                 │
                 ▼
          ActionExecutionRequested
                 │
                 ▼
             Execution
                 │
                 ▼
          EvidenceCaptured
                 │
                 ▼
       VerificationCompleted
                 │
            ┌────┴────┐
            ▼         ▼
          PASS       FAIL
                      │
                      ▼
                  FindingRaised
```

---

# 147.17 — Synchronous versus asynchronous

Not every interaction should use events.

A useful rule:

### Synchronous

Use when the caller needs an immediate response.

Examples:

$$
GetContext
$$

$$
EvaluateAuthorization
$$

$$
GetVerificationStatus.
$$

### Asynchronous

Use when processing can happen independently.

Examples:

$$
EvidenceIngestion
$$

$$
GraphProjection
$$

$$
Notification
$$

$$
LongRunningVerification.
$$

---

# 147.18 — Authorization should normally be synchronous

For a high-risk action:

```text id="q6m8p2"
Request Action
      ↓
Evaluate Authorization
      ↓
ALLOW / DENY
```

The caller should know the authorization decision before execution.

Therefore:

$$
Action
\not\rightarrow
fire-and-forget\ authorization.
$$

---

# 147.19 — Execution can be asynchronous

Actual infrastructure execution may be asynchronous:

```text id="v8m3q2"
Authorized
   ↓
ExecutionRequested
   ↓
External System
   ↓
ExecutionCompleted
```

This is appropriate for:

* deployments;
* migrations;
* long-running verification;
* infrastructure changes.

---

# 147.20 — Context retrieval should normally be synchronous

The agent asks:

> What is the governed context for this task?

It needs an answer before reasoning.

Therefore:

$$
RequestContext
\rightarrow
ContextPackage.
$$

---

# 147.21 — Graph updates can be asynchronous

The graph does not generally need to block a domain transaction.

Therefore:

```text id="j4q8m2"
Domain State
    ↓
Outbox
    ↓
Projection
    ↓
Graph
```

This provides eventual consistency.

---

# 147.22 — Important consistency classification

We can now classify operations.

| Operation               | Consistency              |
| ----------------------- | ------------------------ |
| Decision activation     | Strong/local transaction |
| Authorization           | Strong/immediate         |
| Action state transition | Strong/local             |
| Evidence registration   | Strong/local             |
| Verification record     | Strong/local             |
| Graph projection        | Eventual                 |
| Search index            | Eventual                 |
| Notifications           | Eventual                 |
| Analytics               | Eventual                 |

This is a much healthier model than trying to make everything strongly consistent.

---

# 147.23 — Transaction boundary

A command should normally modify one Aggregate within one transaction.

Example:

```text id="p7m3q8"
ApproveDecision
      ↓
DecisionAggregate
      ↓
transaction commit
      ↓
DecisionApproved
```

The event then leaves the transaction boundary.

---

# 147.24 — Transactional Outbox

For important events:

```text id="x8m3q2"
┌─────────────────────────┐
│ Transaction             │
│                         │
│ Decision state change   │
│ +                       │
│ Outbox event            │
└──────────┬──────────────┘
           │ commit
           ▼
       Event Publisher
           │
           ▼
      Consumers
```

This prevents the classic dual-write problem.

---

# 147.25 — Example dual-write failure

Without an outbox:

```text id="r6m8q2"
DB updated
   ↓
publish event
   ↓
BROKER FAILURE
```

The Decision is effective, but downstream Knowledge/Graph never learns about it.

With the outbox:

$$
Decision
+
Event
$$

commit together.

---

# 147.26 — Idempotency

Consumers must assume events can potentially be delivered more than once.

Therefore:

$$
Consumer(EventID)
$$

must be idempotent.

Example:

```text id="n4q8m2"
Event E42 received
→ process

Event E42 received again
→ no duplicate semantic effect
```

---

# 147.27 — Event identity

Every integration event needs:

$$
EventID.
$$

Also:

$$
EventType
$$

$$
EventVersion
$$

$$
OccurredAt
$$

$$
Producer
$$

$$
CorrelationID.
$$

---

# 147.28 — Event envelope

Conceptually:

```text id="c7m3q8"
EventEnvelope
├── eventId
├── eventType
├── eventVersion
├── occurredAt
├── producer
├── correlationId
├── causationId
└── payload
```

This gives us traceability.

---

# 147.29 — Correlation versus causation

These should be distinct.

$$
CorrelationID
$$

answers:

> Which workflow does this belong to?

$$
CausationID
$$

answers:

> Which event/action caused this event?

For the Golden Trace:

```text id="w5q8m2"
Trace GT-NEXUS-001
      │
      ├── ContextRequested
      │
      ├── VerificationRequested
      │
      ├── ActionRequested
      │
      └── ActionExecuted
```

Each event can retain causation relationships.

---

# 147.30 — Event versioning

Contracts evolve.

Therefore:

$$
EventType
+
EventVersion.
$$

For example:

$$
VerificationCompleted:v1.
$$

Later:

$$
VerificationCompleted:v2.
$$

Consumers should not be forced to understand every new field immediately.

---

# 147.31 — Contract compatibility

The preferred evolution strategy:

$$
AdditiveChange
$$

before:

$$
BreakingChange.
$$

If a breaking semantic change is unavoidable:

$$
NewVersion.
$$

---

# 147.32 — Integration contract: Decision

A simplified published contract might be:

```text id="m8q2v4"
EffectiveDecision
├── decisionId
├── authority
├── scope
├── effectiveAt
└── policyReferences
```

Notice what is deliberately absent:

* internal persistence fields;
* internal workflow objects;
* UI data;
* implementation details.

---

# 147.33 — Integration contract: Evidence

```text id="q6m3p8"
EvidenceRegistered
├── evidenceId
├── subject
├── source
├── capturedAt
├── reference
└── provenance
```

The consumer does not need the internal Evidence aggregate.

---

# 147.34 — Integration contract: Verification

```text id="x7q4m2"
VerificationCompleted
├── verificationId
├── ruleId
├── ruleVersion
├── subject
├── verdict
├── evidenceReferences
└── completedAt
```

This is sufficient for downstream projections.

---

# 147.35 — Integration contract: Finding

```text id="p8m2q7"
FindingRaised
├── findingId
├── verificationId
├── ruleId
├── subject
├── severity
└── raisedAt
```

Governance can use this without importing Assurance internals.

---

# 147.36 — Integration contract: Action

```text id="j5m8q2"
ActionRequested
├── actionId
├── subject
├── operation
├── requestedBy
├── contextId
├── traceId
└── requestedAt
```

Authorization can then independently evaluate it.

---

# 147.37 — Integration contract: Authorization

```text id="n8q3m2"
ActionAuthorized
├── authorizationId
├── actionId
├── authority
├── scope
├── conditions
└── validUntil
```

The execution layer needs this contract, not the complete Authorization aggregate.

---

# 147.38 — External integration contract

The Nexus adapter should translate:

```text id="r3m8q2"
Nexus API response
```

into:

```text
NexusObservation
```

and ultimately:

```text
EvidenceRegistered.
```

The Nexus API contract therefore terminates at the adapter.

---

# 147.39 — No external model leakage

This gives us a strong rule:

$$
\boxed{
IC-001:
External API models must not cross into the domain layer unchanged.
}
$$

They must be translated.

---

# 147.40 — Query architecture

Queries can be optimized separately from write models.

For example:

```text id="u7m3q8"
GetNexusGovernedContext
       │
       ├── Governance read model
       ├── Knowledge read model
       ├── Evidence read model
       ├── Assurance read model
       └── Graph
               │
               ▼
        ContextPackage
```

This is a natural place for CQRS-style separation.

---

# 147.41 — But don't overengineer CQRS

We do not need:

$$
CQRS
+
EventSourcing
+
Microservices
$$

simply because we have events.

The architecture requires **separation of concerns**, not fashionable infrastructure.

A modular application can implement these concepts very effectively.

---

# 147.42 — Context query optimization

The Context Service may eventually need a specialized read model.

For example:

```text id="k5q8m2"
ContextIndex
├── subject
├── applicable decisions
├── applicable rules
├── current claims
├── recent evidence
└── open findings
```

This can dramatically improve agent latency.

---

# 147.43 — Context cache

A cache may be used:

$$
ContextCache.
$$

But:

$$
Cache
\neq
Authority.
$$

Every cached context must have:

$$
Validity.
$$

and potentially:

$$
SourceVersion.
$$

---

# 147.44 — Stale-context protection

Before a high-risk action:

$$
ContextAge < threshold
$$

may be required.

Or:

$$
ContextVersion
$$

must still match the applicable state.

---

# 147.45 — Action precondition

We can define:

$$
CanExecute(Action)
$$

as:

$$
AuthorizationValid
\land
ContextValid
\land
RequiredAssurancePassed
\land
SubjectAvailable.
$$

This is a powerful deterministic execution gate.

---

# 147.46 — Execution gate

```text id="v8q3m2"
             Action
               │
       ┌───────┼────────┐
       ▼       ▼        ▼
 Authorization Context Assurance
       │       │        │
       └───────┼────────┘
               ▼
            EXECUTE
```

This is where the previously separate architecture concepts converge.

---

# 147.47 — Failure semantics

If any required precondition fails:

$$
CanExecute=false.
$$

The platform should return a specific reason:

```text id="m4q8p2"
AUTHORIZATION_DENIED
CONTEXT_EXPIRED
ASSURANCE_FAILED
REQUIRED_EVIDENCE_MISSING
SUBJECT_UNAVAILABLE
```

Not simply:

```text
ERROR
```

---

# 147.48 — This is important for agents

The agent can then reason over a semantic failure:

> Execution was denied because authorization expired.

rather than:

> API returned 403.

The former is a domain-level explanation.

---

# 147.49 — Event-driven graph projection

The graph consumer listens to selected integration events:

```text id="q8m2v3"
DecisionApproved
PolicyActivated
EvidenceRegistered
VerificationCompleted
FindingRaised
ActionAuthorized
ActionExecuted
```

It transforms them into:

$$
GraphNodes
$$

and:

$$
GraphEdges.
$$

---

# 147.50 — Graph projection example

When:

$$
VerificationCompleted(V42)
$$

arrives:

```text id="n7m3q8"
V42
 │
 ├──evaluates──► R17
 ├──appliesTo──► Nexus
 └──supportedBy──► E81
```

is created/updated.

---

# 147.51 — Graph projection failure

If graph projection fails:

```text id="x4q8m2"
Verification
   ↓
authoritative state = safe
   ↓
Outbox = pending
   ↓
Graph = temporarily stale
```

The core domain remains correct.

This is exactly why the graph should not be the transactional source of truth.

---

# 147.52 — Eventual consistency boundary

We can now define:

$$
\boxed{
IC-002:
Cross-context projections may be eventually consistent unless a specific business invariant requires synchronous consistency.
}
$$

---

# 147.53 — Command authorization

Commands themselves should also be authorized.

For example:

$$
ApproveDecision
$$

requires Governance authority.

$$
RequestAction
$$

may require an authenticated agent.

$$
ExecuteProductionAction
$$

requires stronger authority.

Therefore:

$$
Command
\rightarrow
AuthorizationPolicy.
$$

---

# 147.54 — Agent command boundary

An agent should not invoke arbitrary domain commands.

Instead it has a defined capability set:

```text id="c8m3q2"
Agent Capabilities
├── QueryContext
├── RequestVerification
├── CreateRecommendation
├── RequestAction
└── ReadEvidence
```

Potentially:

```text
ExecuteProductionAction
```

is **not** generally available.

---

# 147.55 — Capability model

This creates:

$$
AgentCapability
\subseteq
PlatformCapability.
$$

The actual permission is:

$$
AuthorizedCapability
=
Capability
\cap
Policy
\cap
Scope.
$$

---

# 147.56 — Contract hierarchy

We now have a clean contract hierarchy:

```text id="p7q3m8"
                    AGENT
                      │
                  Agent API
                      │
                      ▼
                APPLICATION
                      │
               Domain Commands
                      │
                      ▼
                  DOMAIN
                      │
                Domain Events
                      │
                      ▼
              INTEGRATION LAYER
                      │
            Integration Events
                      │
          ┌───────────┼────────────┐
          ▼           ▼            ▼
       Knowledge    Assurance   Governance
          │
          ▼
       Projections
```

---

# 147.57 — The architecture now has three flows

We can now clearly distinguish:

### Command flow

$$
Intent
\rightarrow
Command
\rightarrow
Aggregate.
$$

### Event flow

$$
Aggregate
\rightarrow
Event
\rightarrow
OtherContexts.
$$

### Query flow

$$
Agent
\rightarrow
Query
\rightarrow
ReadModels.
$$

This separation is fundamental.

---

# 147.58 — Golden Trace as interaction architecture

The complete interaction model is now:

```text id="z6m8q2"
Human
  │
  ▼
Agent
  │
  │ QueryContext
  ▼
Context Service
  │
  ├──── Query Governance
  ├──── Query Knowledge
  ├──── Query Assurance
  └──── Query Evidence
  │
  ▼
ContextPackage
  │
  ▼
Agent reasoning
  │
  ▼
Recommendation
  │
  ▼
RequestAction
  │
  ▼
Authorization
  │
  ├──── DENY ───► stop
  │
  └──── ALLOW
          │
          ▼
     ExecuteAction
          │
          ▼
     External System
          │
          ▼
       Evidence
          │
          ▼
     Verification
          │
          ├── PASS
          │
          └── FAIL
                 │
                 ▼
              Finding
                 │
                 ▼
             Governance
```

---

# 147.59 — Interaction invariants

We can now add another set of architecture rules.

### IC-003

Commands express intent; events express facts.

### IC-004

Queries do not mutate domain state.

### IC-005

Cross-context communication uses published contracts.

### IC-006

External API models terminate at adapters.

### IC-007

Consumers of events must be idempotent.

### IC-008

High-risk authorization must be evaluated before execution.

### IC-009

The graph may be stale temporarily but must be rebuildable.

### IC-010

A recommendation cannot directly trigger an execution without the required action/authorization path.

---

# 147.60 — Step 147 verdict

We now have the interaction architecture needed to connect the domain model.

The essential architecture is:

$$
\boxed{
Commands
\rightarrow
Aggregates
\rightarrow
Events
\rightarrow
Context\ Projections
}
$$

and:

$$
\boxed{
Queries
\rightarrow
Governed\ Read\ Models
\rightarrow
Agent.
}
$$

For execution:

$$
\boxed{
Action
\rightarrow
Authorization
\rightarrow
Execution
\rightarrow
Evidence
\rightarrow
Verification.
}
$$

This gives us a complete interaction model without requiring premature commitment to a particular broker, database, or microservice architecture.

---

# Step 148 — API & Contract Architecture

The next step is to make these contracts concrete at the **API boundary**.

We need to define the external KnowledgeOS interfaces for:

* Claude;
* Codex;
* CI/CD;
* engineering systems;
* governance clients;
* assurance checkers;
* graph consumers.

The important architectural question will be:

> **What is the minimum stable KnowledgeOS contract that all agents can rely upon, independent of how KnowledgeOS is internally implemented?**

That will give us the **KnowledgeOS Platform API Contract v1** and establish the boundary between the existing `.claude` / `.codex` mechanisms and the future centralized KnowledgeOS platform.
