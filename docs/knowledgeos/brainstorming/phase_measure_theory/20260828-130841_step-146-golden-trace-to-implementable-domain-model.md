# Step 146 — Golden Trace → Implementable Domain Model

We now translate the Golden Trace into the **actual DDD building blocks**.

The purpose is to answer a much more precise question:

> **Which object owns which invariant, and which object is allowed to change which state?**

This prevents the most dangerous architectural failure at this stage: creating a technically elegant KnowledgeOS model that nevertheless becomes one giant shared domain object.

---

## 146.1 — First principle: Aggregates are consistency boundaries

An Aggregate is not simply:

> "an important entity."

It is a boundary around invariants that must be consistent together.

Therefore:

$$
\boxed{
Aggregate = ConsistencyBoundary
}
$$

and:

$$
Entity \neq Aggregate.
$$

---

# 146.2 — Candidate aggregate map

From the Golden Trace, the first candidate aggregates are:

```text id="m8q3v1"
GOVERNANCE
├── Decision
├── Policy
├── Exception
└── Disposition

KNOWLEDGE
├── Claim
└── KnowledgeState

ASSURANCE
├── Rule
├── Verification
└── Finding

EVIDENCE
├── Evidence
└── Observation

AGENT
├── Agent
├── Session
├── Task
├── Recommendation
├── Action
├── Authorization
└── Execution
```

But we should **not** assume that each line automatically represents one Aggregate.

We need to determine the actual consistency boundaries.

---

# 146.3 — Governance Aggregate candidates

A likely first boundary is:

$$
DecisionAggregate.
$$

It protects invariants such as:

$$
DecisionID
$$

must remain stable.

A decision cannot arbitrarily transition:

$$
Effective
\rightarrow
Draft.
$$

And supersession must preserve historical meaning.

---

# 146.4 — Decision lifecycle

A candidate state machine:

```text id="q7m3p8"
DRAFT
  │
  ▼
UNDER_REVIEW
  │
  ▼
APPROVED
  │
  ▼
EFFECTIVE
  │
  ▼
SUPERSEDED
```

Potential terminal state:

$$
RETIRED.
$$

The exact lifecycle remains subject to existing governance rules.

---

# 146.5 — Decision invariants

Candidate invariants:

### D-INV-001

A Decision has exactly one stable identity.

### D-INV-002

Only an authorized authority can make it effective.

### D-INV-003

An effective Decision cannot be silently modified.

### D-INV-004

Supersession preserves historical traceability.

These are strong candidates for deterministic domain tests.

---

# 146.6 — Policy Aggregate

A Policy may be separate from a Decision.

Why?

Because:

$$
Decision
$$

answers:

> What was decided?

while:

$$
Policy
$$

answers:

> What rule or expectation governs behavior?

Thus:

$$
Decision
\neq
Policy.
$$

---

# 146.7 — Decision → Policy

The relationship can therefore be:

```text id="w4m8q2"
Decision
   │ establishes
   ▼
Policy
```

The graph can represent this relationship.

The Policy aggregate does not need to contain the entire Decision aggregate.

---

# 146.8 — Exception Aggregate

An Exception deserves its own lifecycle.

```text id="p8m3q2"
REQUESTED
   ↓
REVIEWED
   ↓
APPROVED
   ↓
ACTIVE
   ↓
EXPIRED
```

Potentially:

$$
REJECTED.
$$

---

# 146.9 — Exception is not a bypass

A critical invariant:

$$
Exception
\neq
DeleteRule.
$$

Instead:

$$
Rule
+
Exception
\rightarrow
ModifiedApplicability.
$$

The original rule remains historically visible.

---

# 146.10 — Knowledge Aggregate

A candidate:

$$
KnowledgeClaimAggregate.
$$

It represents a governed claim.

Example:

> Nexus currently runs version X.

The claim should not simply be:

```text id="v7q3m1"
text = "Nexus runs X"
```

It requires semantic metadata.

---

# 146.11 — Claim structure

```text id="c5m8q2"
Claim
├── ClaimID
├── subject
├── predicate
├── value
├── status
├── validity
├── provenance
└── evidence references
```

This gives the claim machine-addressable meaning.

---

# 146.12 — Claim lifecycle

A candidate:

```text id="n8m3q2"
PROPOSED
   ↓
SUPPORTED
   ↓
VERIFIED
   ↓
EFFECTIVE
```

Potential states:

$$
DISPUTED
$$

$$
SUPERSEDED
$$

$$
REJECTED.
$$

The exact lifecycle needs validation.

---

# 146.13 — Important separation

A claim being:

$$
VERIFIED
$$

does not automatically make it:

$$
GOVERNANCE\ AUTHORITY.
$$

For example:

> Nexus currently reports version 3.69.

may be verified observation.

It does not establish:

> Nexus must run version 3.70.

The latter comes from Governance.

---

# 146.14 — Assurance Rule Aggregate

A Rule is a strong candidate for its own Aggregate:

$$
RuleAggregate.
$$

It owns:

```text id="x4q8m2"
RuleID
Version
Applicability
ExpectedCondition
Severity
CheckerReference
Lifecycle
```

---

# 146.15 — Rule versioning

Rules should be immutable by version.

Thus:

$$
R42:v1
$$

and:

$$
R42:v2
$$

are distinct semantic versions.

A verification references exactly one version.

---

# 146.16 — Verification Aggregate

Verification is a record of an assurance execution.

It should establish:

$$
What\ was\ checked?
$$

$$
Against\ which\ rule?
$$

$$
Using\ which\ checker?
$$

$$
Using\ which\ evidence?
$$

$$
With\ what\ result?
$$

---

# 146.17 — Verification structure

```text id="j8m3q2"
Verification
├── VerificationID
├── RuleID + Version
├── Subject
├── CheckerVersion
├── EvidenceReferences
├── ExecutedAt
├── Verdict
└── ExecutionContext
```

---

# 146.18 — Verification immutability

Once completed:

$$
Verification
$$

should normally be immutable.

If the check must be repeated:

$$
Verification_{new}.
$$

not:

$$
Verification_{old}
\rightarrow
overwrite.
$$

This preserves auditability.

---

# 146.19 — Finding Aggregate

A Finding represents a discovered deviation.

It should not simply be a text field attached to Verification.

Candidate structure:

```text id="q4m8m2"
Finding
├── FindingID
├── sourceVerification
├── severity
├── status
├── owner
├── disposition
└── lifecycle
```

---

# 146.20 — Finding lifecycle

```text id="m7q3p8"
OPEN
 ↓
ACKNOWLEDGED
 ↓
IN_REMEDIATION
 ↓
RESOLVED
```

Alternative:

$$
ACCEPTED
$$

if governance accepts the risk.

---

# 146.21 — Evidence Aggregate

Evidence should have particularly strong integrity semantics.

Candidate:

```text id="r8m2q4"
Evidence
├── EvidenceID
├── source
├── subject
├── capturedAt
├── capturedBy
├── externalReference
├── content/reference
└── integrity
```

---

# 146.22 — Evidence immutability

A key invariant:

$$
\boxed{
Evidence\ already\ used\ for\ verification\ must\ not\ be\ silently\ altered.
}
$$

If a new observation occurs:

$$
E_{new}.
$$

not:

$$
E_{old} \leftarrow newState.
$$

---

# 146.23 — Observation versus Evidence

We need to be careful here.

An:

$$
Observation
$$

is what an external system reports.

An:

$$
Evidence
$$

is the retained/provenance-bearing representation used to support an assertion.

Thus:

```text id="x3m8q1"
Nexus
 ↓
Observation
 ↓
Evidence
```

One observation may become one evidence item, but the semantic distinction remains.

---

# 146.24 — Agent Aggregate candidates

The Agent context introduces several objects, but they should not all become one Aggregate.

A reasonable first decomposition is:

$$
AgentAggregate
$$

for stable agent identity/configuration.

$$
SessionAggregate
$$

for one agent interaction lifecycle.

$$
ActionAggregate
$$

for a proposed/executed engineering action.

---

# 146.25 — Agent identity

The Agent aggregate owns:

```text id="g7q3m8"
AgentID
AgentType
Provider
Capabilities
Status
```

For example:

$$
Claude
$$

or:

$$
Codex.
$$

The implementation-specific configuration stays outside the domain.

---

# 146.26 — Session Aggregate

A Session represents one continuous agent interaction context.

Candidate:

```text id="h8m3q2"
Session
├── SessionID
├── AgentID
├── HumanPrincipal
├── startedAt
├── endedAt
├── ContextReferences
└── Actions
```

But whether Actions are actually contained inside Session must be decided carefully.

---

# 146.27 — Do not make Session a giant Aggregate

A long-running agent session could contain dozens or hundreds of actions.

Therefore:

$$
Session
$$

should probably **reference** actions rather than transactionally contain them all.

This is an important Aggregate boundary decision.

---

# 146.28 — Task

A Task represents the intended work.

Example:

> Assess whether Nexus migration can proceed.

Candidate:

```text id="n7m4q2"
Task
├── TaskID
├── objective
├── subject
├── scope
├── requester
└── status
```

---

# 146.29 — Recommendation

Recommendation is an output of reasoning.

It should remain distinct from Action.

$$
Recommendation
\neq
Action.
$$

The agent can recommend:

> Upgrade Nexus.

without actually requesting:

$$
ExecuteUpgrade.
$$

---

# 146.30 — Recommendation structure

```text id="v5q8m2"
Recommendation
├── RecommendationID
├── TaskID
├── ContextID
├── proposedOutcome
├── supportingReferences
├── agent
└── timestamp
```

The detailed private reasoning process does not need to be persisted as part of the domain model.

---

# 146.31 — Action Aggregate

The Action is a stronger domain object.

It represents:

> Something the engineering system is being asked to do.

Candidate lifecycle:

```text id="c8m3q2"
REQUESTED
   ↓
AUTHORIZED
   ↓
EXECUTING
   ↓
EXECUTED
```

Failure paths:

$$
DENIED
$$

$$
FAILED
$$

$$
CANCELLED.
$$

---

# 146.32 — Action invariants

### A-INV-001

Action has a stable identity.

### A-INV-002

Action references the originating context.

### A-INV-003

Action cannot execute without required authorization.

### A-INV-004

Execution result cannot be represented as authorization.

### A-INV-005

Execution produces traceable evidence where applicable.

---

# 146.33 — Authorization Aggregate

Authorization is arguably a separate consistency boundary.

It answers:

> Is this specific action allowed under the applicable authority and conditions?

Candidate structure:

```text id="q7m4p2"
Authorization
├── AuthorizationID
├── ActionID
├── Principal
├── authority
├── scope
├── conditions
├── validFrom
├── validUntil
└── decision
```

---

# 146.34 — Authorization immutability

Once an authorization has been consumed:

$$
Authorization
$$

should remain historically stable.

If conditions change:

$$
NewAuthorization.
$$

or:

$$
AuthorizationRevoked.
$$

---

# 146.35 — Execution

Execution represents what actually happened.

This is different from Action.

$$
Action
=
intent.
$$

$$
Execution
=
fact.
$$

That distinction is fundamental.

---

# 146.36 — Execution example

```text id="x5m8q2"
Action A42
operation = ReadNexusConfiguration

Execution E42
status = SUCCESS
startedAt = T1
completedAt = T2
externalReference = ...
```

The Action may have been valid even if Execution failed.

---

# 146.37 — Candidate aggregate relationship

The model becomes:

```text id="j8q3m1"
Recommendation
      │
      ▼
Action
      │
      ▼
Authorization
      │
      ▼
Execution
      │
      ▼
Evidence
```

But these should not necessarily be one Aggregate.

---

# 146.38 — Cross-aggregate references

DDD rule:

$$
\boxed{
Reference\ other\ Aggregates\ by\ identity.
}
$$

Therefore:

```text id="m6q2p8"
Action
authorizationId = AUTH-42
```

rather than:

```text id="v3m8q2"
Action
authorization = huge Authorization object
```

This keeps boundaries clean.

---

# 146.39 — Cross-context references

Even more strongly:

$$
\boxed{
Reference\ other\ Bounded\ Contexts\ through\ stable\ contracts/IDs,
not\ shared\ domain\ object\ graphs.
}
$$

For example:

$$
Verification
\rightarrow
RuleID.
$$

not:

$$
Verification
\rightarrow
Governance.RuleEntity.
$$

---

# 146.40 — Value Objects

The domain also requires Value Objects.

Candidates:

```text id="q8m3p2"
DecisionID
PolicyID
RuleID
ClaimID
EvidenceID
VerificationID
FindingID
AgentID
SessionID
TaskID
ActionID
AuthorizationID
ExecutionID
ContextID
TraceID
```

These should ideally have semantic types rather than raw strings throughout the domain.

---

# 146.41 — Why typed IDs matter

This prevents accidental relationships such as:

```text id="x7q3m8"
EvidenceID = RuleID
```

at compile time where the implementation language supports strong typing.

It also makes APIs self-documenting.

---

# 146.42 — Validity as a Value Object

Temporal validity is another candidate:

$$
ValidityPeriod.
$$

```text id="r4m8q2"
ValidityPeriod
├── validFrom
└── validUntil
```

It can enforce:

$$
validFrom < validUntil.
$$

---

# 146.43 — Scope as a Value Object

Likewise:

$$
Scope.
$$

Examples:

```text id="n8m3q2"
Scope
├── organization
├── system
├── environment
└── resource
```

The exact dimensions need to come from the actual domain.

---

# 146.44 — Provenance as a Value Object

Candidate:

```text id="p6q2m8"
Provenance
├── source
├── capturedBy
├── capturedAt
└── reference
```

This is likely shared conceptually across contexts but should not necessarily be implemented as one shared domain class.

---

# 146.45 — Shared Kernel?

At this stage we should resist creating a large:

$$
SharedKernel.
$$

A tiny shared kernel might eventually contain:

* primitive identity conventions;
* timestamps;
* correlation identifiers.

But:

$$
Governance
$$

and:

$$
Assurance
$$

should not share rich domain classes simply because they have similar words.

---

# 146.46 — Published Language instead

For cross-context communication, prefer:

$$
PublishedLanguage.
$$

Example:

```text id="w8m3q2"
DecisionReference
├── id
├── status
├── validity
└── authority
```

Assurance consumes this contract.

It does not import Governance's internal entity.

---

# 146.47 — Domain events

The Golden Trace naturally produces domain events.

Governance:

$$
DecisionBecameEffective.
$$

Knowledge:

$$
ClaimVerified.
$$

Evidence:

$$
EvidenceCaptured.
$$

Assurance:

$$
VerificationCompleted.
$$

$$
FindingRaised.
$$

Agent:

$$
ActionRequested.
$$

$$
ActionExecuted.
$$

Authorization:

$$
ActionAuthorized.
$$

---

# 146.48 — Event ownership

Each event belongs to the context that owns the state transition.

For example:

$$
Governance
\rightarrow
DecisionBecameEffective.
$$

not:

$$
KnowledgeOS
\rightarrow
DecisionBecameEffective.
$$

The platform transports the event.

The domain owns its meaning.

---

# 146.49 — Golden Trace event stream

The reference event stream becomes:

```text id="y5q8m2"
DecisionBecameEffective
        ↓
ContextRequested
        ↓
ContextProvided
        ↓
VerificationRequested
        ↓
EvidenceCaptured
        ↓
VerificationCompleted
        ↓
RecommendationCreated
        ↓
ActionRequested
        ↓
AuthorizationGranted
        ↓
ActionExecuted
        ↓
EvidenceCaptured
        ↓
VerificationCompleted
        ↓
FindingRaised
```

Not every event necessarily needs to be externally published.

Some may remain internal.

---

# 146.50 — Domain event versus integration event

This distinction is important.

$$
DomainEvent
$$

describes a domain state transition.

$$
IntegrationEvent
$$

is a message deliberately published to another context/system.

They can have the same conceptual source but should not be assumed identical.

---

# 146.51 — Example

Internal:

$$
DecisionBecameEffective.
$$

External integration contract:

$$
EffectiveDecisionPublished.
$$

The latter can be versioned independently.

---

# 146.52 — Aggregate ownership map

The current candidate model becomes:

```text id="k7m3q8"
GOVERNANCE
 ├── DecisionAggregate
 ├── PolicyAggregate
 └── ExceptionAggregate

KNOWLEDGE
 └── ClaimAggregate

EVIDENCE
 └── EvidenceAggregate

ASSURANCE
 ├── RuleAggregate
 ├── VerificationAggregate
 └── FindingAggregate

AGENT
 ├── AgentAggregate
 ├── SessionAggregate
 ├── TaskAggregate
 ├── RecommendationAggregate
 ├── ActionAggregate
 └── AuthorizationAggregate

EXECUTION
 └── ExecutionRecord
```

This is still a **candidate model**, not yet a final implementation contract.

---

# 146.53 — Execution may belong elsewhere

There is an unresolved architectural question:

> Is Execution part of Agent/Action, or an Engineering Execution context?

There is a strong argument for:

$$
Execution
$$

being its own context because the actual fact of execution belongs to the engineering environment.

For example:

$$
Kubernetes
$$

or:

$$
Nexus
$$

is authoritative for what happened operationally.

KnowledgeOS records the execution reference and evidence.

---

# 146.54 — Recommended interpretation

For now:

$$
Action
$$

belongs to Agent/Action management.

$$
ExecutionFact
$$

is produced by the Engineering integration.

Thus:

```text id="v4m8q2"
Action
   ↓ requests
Engineering System
   ↓ produces
Execution Fact
   ↓
Evidence
```

This preserves external authority.

---

# 146.55 — Aggregate boundary test

For each candidate Aggregate we should ask:

> **What invariant would be broken if these objects were updated separately?**

If the answer is:

> None.

then they probably do not belong in the same Aggregate.

This is a practical DDD test.

---

# 146.56 — Example: Action + Authorization

Should they be one Aggregate?

Probably not.

Authorization may have:

* separate lifecycle;
* separate authority;
* separate expiration;
* separate audit requirements.

Therefore:

$$
Action
\rightarrow
AuthorizationID.
$$

---

# 146.57 — Example: Rule + Verification

Definitely separate.

A Rule persists over time.

A Verification is an execution against one version of that Rule.

Therefore:

$$
Rule
\neq
Verification.
$$

---

# 146.58 — Example: Evidence + Verification

Also separate.

The same evidence can potentially support multiple verifications.

Therefore:

$$
Evidence
\rightarrow
Verification_1
$$

and:

$$
Evidence
\rightarrow
Verification_2.
$$

The graph captures this relationship.

---

# 146.59 — Example: Decision + Policy

Likewise potentially separate.

One Decision may establish multiple policies.

One Policy may remain effective beyond the specific document representing the decision.

Therefore:

$$
Decision
\rightarrow
PolicyReference.
$$

---

# 146.60 — The Aggregate rule

We can establish:

$$
\boxed{
DM-001:
An Aggregate exists to protect a consistency boundary, not to model every semantic relationship.
}
$$

---

# 146.61 — Graph versus Aggregate

This is perhaps the most important DDD distinction of this step.

The Aggregate answers:

> **What must change consistently?**

The Graph answers:

> **What is related to what?**

Therefore:

$$
\boxed{
Aggregate \neq Graph.
}
$$

---

# 146.62 — Example

The graph can contain:

```text id="q7m3p2"
Decision D42
 ├── governs → Policy P8
 ├── appliesTo → Nexus
 └── motivatedBy → Finding F2
```

But the Decision Aggregate does not need to contain:

$$
Policy
+
Nexus
+
Finding.
$$

Those are cross-context relationships.

---

# 146.63 — Transaction boundary

The first implementation should therefore favor transactions like:

```text id="j8m4q2"
Transaction
 └── one Aggregate
```

rather than:

```text id="v6q2m8"
Transaction
 └── Governance
      ├── Knowledge
      ├── Evidence
      ├── Assurance
      └── Agent
```

The latter destroys bounded-context autonomy.

---

# 146.64 — Cross-context transaction

Instead:

$$
Transaction_{Governance}
$$

commits:

$$
Decision.
$$

Then:

$$
DecisionBecameEffective
$$

propagates.

Knowledge and Assurance react independently.

---

# 146.65 — Eventual consistency

This naturally gives:

$$
Governance
\rightarrow
Event
\rightarrow
Knowledge
$$

and:

$$
Governance
\rightarrow
Event
\rightarrow
Assurance.
$$

The contexts do not need one distributed transaction.

---

# 146.66 — This also supports graph rebuilding

Events or authoritative state changes update:

$$
GraphProjection.
$$

If the projection fails:

$$
Outbox
\rightarrow
Replay.
$$

This is consistent with the previous runtime architecture.

---

# 146.67 — Domain model summary

The Golden Trace has therefore produced a much more precise model:

```text id="x3m8q2"
             GOVERNANCE
        ┌───────┴────────┐
        ▼                ▼
    Decision           Policy
        │                │
        └───────┬────────┘
                │
                ▼
             KNOWLEDGE
                │
              Claim
                │
                ▼
             ASSURANCE
          ┌─────┴──────┐
          ▼            ▼
        Rule      Verification
                       │
                       ▼
                    Finding


             AGENT
        ┌──────┼────────────┐
        ▼      ▼            ▼
      Task  Recommendation Action
                              │
                              ▼
                        Authorization
                              │
                              ▼
                         Execution
                              │
                              ▼
                           Evidence
                              │
                              ▼
                         Verification
```

---

# 146.68 — Core domain invariants

The most important invariants emerging from the model are:

$$
\boxed{
DM-002:
An agent recommendation is never itself an authorization.
}
$$

$$
\boxed{
DM-003:
An authorization is never evidence that an action succeeded.
}
$$

$$
\boxed{
DM-004:
An execution fact is never equivalent to a governance decision.
}
$$

$$
\boxed{
DM-005:
A verification result must identify the exact rule version evaluated.
}
$$

$$
DM-006:
Evidence used by assurance must remain historically reconstructable.
}
$$

$$
\boxed{
DM-007:
Cross-context relationships do not imply aggregate ownership.
}
$$

---

# 146.69 — The semantic chain

We can now distinguish the complete chain:

$$
\boxed{
Intent
\rightarrow
Recommendation
\rightarrow
Authorization
\rightarrow
Execution
\rightarrow
Observation
\rightarrow
Evidence
\rightarrow
Verification
\rightarrow
Finding
\rightarrow
Disposition.
}
$$

And the governance side:

$$
\boxed{
Decision
\rightarrow
Policy
\rightarrow
Rule
\rightarrow
ExpectedState.
}
$$

The two chains meet at:

$$
Verification.
$$

---

# 146.70 — This is the core KnowledgeOS semantic architecture

The entire platform can therefore be described as the intersection of two flows:

### Governance flow

$$
\boxed{
Decision
\rightarrow
Policy
\rightarrow
Rule
\rightarrow
Expectation
}
$$

### Engineering flow

$$
\boxed{
Intent
\rightarrow
Action
\rightarrow
Execution
\rightarrow
Observation
\rightarrow
Evidence
}
$$

### Assurance bridge

$$
\boxed{
Expectation
+
Evidence
\rightarrow
Verification
\rightarrow
Finding.
}
$$

That is a much stronger definition of KnowledgeOS than simply "AI engineering knowledge management."

---

# 146.71 — Step 146 verdict

The Golden Trace has now produced an **implementable candidate domain model**.

The central design decision is:

$$
\boxed{
Aggregates\ protect\ local\ invariants;
Events/contracts\ connect\ contexts;
the\ Assurance\ Graph\ represents\ cross-context\ relationships.
}
$$

This gives us a clean answer to the question:

> **Where should KnowledgeOS put the boundaries?**

---

# Step 147 — Domain Events & Integration Contracts

The next step is to define exactly how these aggregates communicate.

We will therefore turn the Golden Trace into:

$$
\boxed{
Commands
+
DomainEvents
+
IntegrationEvents
+
Queries
+
Contracts
}
$$

This will expose another crucial architectural issue:

> **Which communication must be synchronous, which can be asynchronous, and which must never cross a boundary at all?**

That will give us the interaction architecture needed before we design the actual APIs and persistence model.
