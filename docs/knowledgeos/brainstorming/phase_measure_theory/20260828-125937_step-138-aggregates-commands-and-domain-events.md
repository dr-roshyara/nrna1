# Step 138 — Aggregates, Commands and Domain Events

We now move from the **semantic domain model** to the **behavioral DDD model**.

The previous step identified the concepts. This step answers:

> **What is allowed to change, who may change it, and what events result from that change?**

The key principle remains:

$$
\boxed{
Aggregate\ boundaries\ follow\ invariants,\ not\ nouns.
}
$$

We should therefore not create one aggregate for every entity.

---

# 138.1 — Candidate aggregate structure

The current target hypothesis is:

```text
Governance
├── Decision
├── Policy
└── Exception

Knowledge
└── KnowledgeClaim

Evidence
└── EvidenceRecord

Assurance
├── FitnessRule
├── Verification
└── Finding

Agent Platform
└── AgentSession

Execution
└── Action
```

But the final aggregate boundaries depend on which invariants must be transactional.

---

# 138.2 — Aggregate #1: Decision

The `Decision` is the central Governance aggregate.

Its invariant is approximately:

$$
\boxed{
A\ Decision\ cannot\ become\ effective\ without\ valid\ authority.
}
$$

Possible lifecycle:

```text id="h5m9q2"
DRAFT
  ↓
UNDER_REVIEW
  ↓
APPROVED
  ↓
EFFECTIVE
  ↓
SUPERSEDED
```

Potential invalid transitions:

```text id="p7q3m8"
DRAFT → EFFECTIVE
DRAFT → SUPERSEDED
```

unless explicitly permitted by policy.

---

# 138.3 — Decision commands

Candidate commands:

```text
ProposeDecision
SubmitDecisionForReview
ApproveDecision
RejectDecision
ActivateDecision
SupersedeDecision
WithdrawDecision
```

The important point is that these are **domain commands**, not HTTP endpoints.

For example:

$$
ApproveDecision(D42,AuthorityA)
$$

is a domain operation.

---

# 138.4 — Decision events

Successful commands produce domain events:

$$
DecisionProposed
$$

$$
DecisionSubmitted
$$

$$
DecisionApproved
$$

$$
DecisionActivated
$$

$$
DecisionSuperseded.
$$

The event records the fact that the transition happened.

---

# 138.5 — Decision invariant

A strong invariant:

$$
Effective(Decision)
\Rightarrow
ValidAuthority(Decision)
$$

and:

$$
Effective(Decision)
\Rightarrow
ValidEffectivePeriod.
$$

This means the state machine itself protects governance integrity.

---

# 138.6 — Policy aggregate

Policy should not necessarily be embedded inside Decision.

Instead:

$$
Decision
\rightarrow
establishes
\rightarrow
Policy.
$$

This permits a policy to have its own lifecycle.

Candidate commands:

```text
CreatePolicy
ActivatePolicy
ChangePolicy
RetirePolicy
SupersedePolicy
```

---

# 138.7 — Policy events

Potential events:

$$
PolicyEstablished
$$

$$
PolicyActivated
$$

$$
PolicyChanged
$$

$$
PolicySuperseded.
$$

The exact event granularity should be validated against the current implementation.

---

# 138.8 — Exception aggregate

An exception represents a controlled deviation.

Its key invariant:

$$
\boxed{
Exception
\Rightarrow
Authorized
+
Scoped
+
TimeBound
}
$$

unless a particular governance regime explicitly allows otherwise.

Candidate commands:

```text
RequestException
ApproveException
RejectException
ActivateException
ExpireException
RevokeException
```

---

# 138.9 — Exception events

```text
ExceptionRequested
ExceptionApproved
ExceptionActivated
ExceptionExpired
ExceptionRevoked
```

The critical event is:

$$
ExceptionApproved.
$$

This changes the effective expected state.

---

# 138.10 — Effective policy calculation

We can now formalize:

$$
E(t)=
Policy(t)
+
ApplicableExceptions(t).
$$

Where:

$$
ApplicableExceptions(t)
=
\{e \mid scope(e), authority(e), validity(e,t)\}.
$$

Assurance evaluates:

$$
E(t)
$$

rather than simply the base policy.

---

# 138.11 — Knowledge Claim aggregate

A claim should have a controlled lifecycle.

Potential states:

```text id="r8m3q5"
PROPOSED
   ↓
SUPPORTED
   ↓
VERIFIED
   ↓
AUTHORITATIVE
   ↓
SUPERSEDED
```

Potentially:

$$
DISPUTED
$$

can occur from multiple states.

---

# 138.12 — Claim commands

```text
ProposeClaim
AttachEvidence
RequestVerification
VerifyClaim
DisputeClaim
SupersedeClaim
```

But note:

$$
AttachEvidence
$$

does not necessarily mean:

$$
VerifyClaim.
$$

Evidence supports a claim; it does not automatically establish it.

---

# 138.13 — Knowledge events

Potential events:

$$
ClaimProposed
$$

$$
EvidenceLinked
$$

$$
ClaimVerified
$$

$$
ClaimDisputed
$$

$$
ClaimSuperseded.
$$

---

# 138.14 — Evidence aggregate

Evidence has a fundamentally different lifecycle.

Once captured:

$$
Evidence
$$

should generally be immutable.

Candidate lifecycle:

$$
CAPTURED
\rightarrow
VALIDATED
\rightarrow
RETAINED.
$$

Potentially:

$$
REJECTED
$$

if integrity/provenance validation fails.

---

# 138.15 — Evidence commands

```text
CaptureEvidence
ValidateEvidence
LinkEvidence
ArchiveEvidence
```

But an important principle:

$$
Evidence
$$

should not be rewritten simply because the interpretation changes.

The evidence remains historical.

---

# 138.16 — Evidence events

```text
EvidenceCaptured
EvidenceValidated
EvidenceLinked
EvidenceArchived
```

A later interpretation creates new domain state; it should not rewrite the original evidence.

---

# 138.17 — Observation

Observation can be treated as a specialized evidence-producing object.

For example:

$$
Observation
=
Evidence
+
ObservedSubject
+
ObservedAt.
$$

Whether Observation becomes its own aggregate or a value/object within Evidence depends on actual scale and lifecycle requirements.

At this stage we should **not over-model it**.

---

# 138.18 — Fitness Rule aggregate

A Fitness Rule is governed assurance knowledge.

It should have:

$$
Identity
+
Version
+
Scope
+
Expression
+
Applicability.
$$

Candidate lifecycle:

```text id="j6v2q9"
DRAFT
 ↓
APPROVED
 ↓
ACTIVE
 ↓
SUPERSEDED
```

---

# 138.19 — Rule commands

```text
DefineRule
SubmitRule
ApproveRule
ActivateRule
SupersedeRule
```

A checker implementation should reference the rule.

It should not define the authoritative semantics itself.

---

# 138.20 — Rule event

For example:

$$
FitnessRuleActivated.
$$

This event can trigger:

$$
AssuranceScheduling
$$

or:

$$
AffectedSubjectReverification.
$$

---

# 138.21 — Verification

Verification is best understood as an **execution record**, not a mutable configuration object.

It is approximately:

$$
V=
(Rule,\ Subject,\ Time,\ Evidence,\ Verdict).
$$

Once completed:

$$
Verification
$$

should generally be immutable.

---

# 138.22 — Verification command

The command is conceptually:

$$
ExecuteVerification.
$$

But the actual checker execution may occur outside the domain.

Architecture:

```text id="n4m8q2"
Application
   ↓
Verification Request
   ↓
Checker Port
   ↓
External Checker
   ↓
Evidence
   ↓
Verification Result
```

The domain records the semantic result.

---

# 138.23 — Verification events

$$
VerificationStarted
$$

$$
EvidenceProduced
$$

$$
VerificationCompleted.
$$

Potentially:

$$
VerificationFailedToExecute.
$$

This distinction is important.

A checker that crashes has not necessarily produced:

$$
FAIL.
$$

It may have produced:

$$
UNKNOWN.
$$

---

# 138.24 — Finding

A Finding can be created from a verification result.

For example:

$$
Verification=FAIL
$$

may produce:

$$
FindingRaised.
$$

But not every failure necessarily requires a persistent Finding.

That depends on policy.

Therefore:

$$
FAIL
\not\Rightarrow
Finding
$$

universally.

Instead:

$$
Policy(FAIL)
\rightarrow
Disposition.
$$

---

# 138.25 — Finding lifecycle

Potentially:

```text id="f8m3q6"
OPEN
 ↓
ACKNOWLEDGED
 ↓
REMEDIATION
 ↓
VERIFIED
 ↓
CLOSED
```

Or:

$$
OPEN
\rightarrow
ACCEPTED\_RISK
$$

if governance explicitly permits it.

---

# 138.26 — Finding ownership

The important boundary:

$$
Assurance
\rightarrow
identifies\ the\ finding.
$$

Governance:

$$
\rightarrow
determines\ disposition.
$$

Engineering:

$$
\rightarrow
performs\ remediation.
$$

Therefore the lifecycle crosses contexts.

---

# 138.27 — Agent Session aggregate

The Agent Session is fundamentally operational.

It may contain:

```text id="x7q2m9"
Session
├── agent
├── task
├── context reference
├── recommendations
├── actions
└── session status
```

But it should not contain copies of authoritative decisions.

It references them.

---

# 138.28 — Session commands

```text
StartSession
AttachContext
RecordRecommendation
RequestAction
CompleteSession
AbortSession
```

---

# 138.29 — Agent session events

```text
SessionStarted
ContextProvided
RecommendationProduced
ActionRequested
SessionCompleted
```

These provide the execution history.

---

# 138.30 — Recommendation

Recommendation is usually not an aggregate requiring extensive lifecycle management.

It can be a domain object associated with a session/action workflow.

The key relationship:

$$
Recommendation
\rightarrow
basedOn
\rightarrow
Context.
$$

And:

$$
Recommendation
\neq
Decision.
$$

---

# 138.31 — Authorization

Authorization is a critical cross-context object.

It establishes:

$$
May(Actor,Action,Scope,t).
$$

It may be produced by Governance or an authorization service.

The exact aggregate boundary should depend on the existing enterprise authorization architecture.

Therefore we mark:

$$
\boxed{
Authorization\ Context = TO\ BE\ CONFIRMED
}
$$

rather than prematurely creating another bounded context.

---

# 138.32 — Action aggregate

An Action represents actual execution.

Potential state:

```text id="b3n8m2"
REQUESTED
 ↓
AUTHORIZED
 ↓
STARTED
 ↓
COMPLETED
```

with failure paths:

$$
FAILED
$$

$$
CANCELLED.
$$

---

# 138.33 — Action invariant

The critical invariant:

$$
\boxed{
MaterialAction
\land
Executed
\Rightarrow
AuthorizationExists
}
$$

unless an explicit emergency policy defines a different path.

---

# 138.34 — Action events

```text
ActionRequested
ActionAuthorized
ActionStarted
ActionCompleted
ActionFailed
```

These events can feed the evidence pipeline.

---

# 138.35 — Agent does not directly own Action authorization

This is worth emphasizing.

The agent can request:

$$
ActionRequested.
$$

A policy mechanism determines:

$$
Authorized?
$$

Only then:

$$
ActionStarted.
$$

---

# 138.36 — Cross-context event flow

We now have:

```text id="w9m4q2"
Governance
   │
   │ DecisionApproved
   ▼
Knowledge
   │
   │ Knowledge / expected state
   ▼
Assurance
   │
   │ VerificationRequest
   ▼
Engineering / Agent
   │
   │ ActionExecuted
   ▼
Evidence
   │
   │ EvidenceCaptured
   ▼
Assurance
   │
   │ VerificationCompleted
   ▼
Finding
   │
   ▼
Governance
```

This is the event-oriented view of the closed loop.

---

# 138.37 — Commands versus events

We must maintain the distinction:

### Command

> Please perform this operation.

### Event

> This operation happened.

For example:

$$
ApproveDecision
$$

is a command.

$$
DecisionApproved
$$

is an event.

The agent may issue commands.

It cannot manufacture authoritative events.

---

# 138.38 — Event authenticity

An event must be emitted by the component that owns the transition.

For example:

$$
DecisionApproved
$$

must originate from Governance.

A Claude session saying:

> "Decision approved."

is not the same event.

---

# 138.39 — Agent transcript versus domain event

This is a subtle but important distinction.

```text
Claude transcript:
"I approve this decision."
```

is:

$$
AgentStatement.
$$

It is not:

$$
DecisionApproved.
$$

The latter requires the governed transition.

---

# 138.40 — This prevents semantic spoofing

The architecture therefore prevents an agent from creating authority through language alone.

$$
\boxed{
Natural\ language\ assertion
\neq
Domain\ state\ transition.
}
$$

This is a foundational principle for AI-native systems.

---

# 138.41 — Cross-context commands

A command may initiate another context's workflow.

Example:

$$
RaiseFinding
$$

can trigger:

$$
GovernanceDispositionRequested.
$$

But Assurance should not directly mutate Governance state.

Instead:

$$
Event
\rightarrow
GovernanceApplicationService.
$$

---

# 138.42 — Context integration patterns

We can use:

### Synchronous query

For current knowledge retrieval.

$$
Agent
\rightarrow
KnowledgeQuery.
$$

### Command

For state-changing request.

$$
Agent
\rightarrow
ActionRequest.
$$

### Domain event

For asynchronous propagation.

$$
VerificationCompleted
\rightarrow
FindingProcess.
$$

This gives us a clean integration vocabulary.

---

# 138.43 — Avoid event-driven everything

We should not turn every interaction into an event.

For example:

> "What rules apply to Nexus?"

is naturally a query.

There is no need to create:

$$
RuleQueryRequestedEvent.
$$

Use events when the fact of a state change needs to be propagated.

---

# 138.44 — Query model

This suggests a read-side projection:

```text id="p4m8q2"
Domain State
     │
     ▼
Knowledge / Graph Projection
     │
     ├── Current decisions
     ├── Applicable policies
     ├── Evidence
     ├── Verification
     └── Findings
```

Agents can query this projection efficiently.

---

# 138.45 — Graph projection

The Assurance Graph may therefore be a **projection of bounded-context state**, rather than the transactional source of truth for every context.

This is a very important refinement.

$$
\boxed{
Bounded\ Contexts\ own\ state;
Assurance\ Graph\ connects\ it.
}
$$

---

# 138.46 — This resolves a major architectural risk

We do not need:

$$
One\ giant\ GraphAggregate.
$$

Instead:

```text id="v2m7q5"
Governance State ─────┐
Knowledge State ──────┤
Evidence State ───────┼──► Assurance Graph Projection
Assurance State ──────┤
Agent State ──────────┘
```

The graph provides cross-context navigation.

---

# 138.47 — Transaction boundary

Each bounded context can maintain its own transaction boundary.

For example:

$$
ApproveDecision
$$

is transactional inside Governance.

Then:

$$
DecisionApproved
$$

is propagated to other contexts.

This avoids distributed transactions wherever possible.

---

# 138.48 — Eventual consistency

Cross-context projections may therefore be:

$$
EventuallyConsistent.
$$

This is acceptable provided the architecture distinguishes:

$$
AuthoritativeSource
$$

from:

$$
Projection.
$$

---

# 138.49 — Source-of-truth rule

For each semantic concept:

$$
\boxed{
Exactly\ one\ authoritative\ owner.
}
$$

Other representations are:

* projections;
* caches;
* indexes;
* references;
* evidence.

This is one of the most important architectural invariants.

---

# 138.50 — No duplicate authority

Therefore:

$$
Decision_{Governance}
$$

is authoritative.

A copied:

$$
Decision_{AgentMemory}
$$

is not another authority.

Likewise:

$$
RuntimeState_{Kubernetes}
$$

is authoritative for runtime.

KnowledgeOS's observation is not.

---

# 138.51 — Command authorization

Before a command executes:

$$
Authorize(Command).
$$

But the command's authorization must be evaluated according to its **semantic owner**.

For example:

$$
ApproveDecision
$$

requires Governance authority.

While:

$$
RunRepositoryScan
$$

may only require agent/tool capability.

---

# 138.52 — Risk-based authorization

A more mature model:

$$
RequiredApproval
=
f(
ActionType,
Scope,
Environment,
Risk,
Authority
).
$$

This allows the same technical tool to be used under different governance conditions.

---

# 138.53 — The agent execution contract

The agent's application contract can now be:

```text id="q8m3v5"
1. Request context
2. Receive governed context
3. Reason
4. Produce recommendation
5. Request action
6. Receive authorization result
7. Execute through tool boundary
8. Return evidence
9. Receive verification
```

This becomes the common contract for Claude and Codex.

---

# 138.54 — Agent architecture becomes replaceable

Claude:

$$
AgentAdapter_{Claude}
$$

Codex:

$$
AgentAdapter_{Codex}
$$

Future agent:

$$
AgentAdapter_{X}.
$$

All consume:

$$
AgentApplicationContract.
$$

This gives KnowledgeOS model-level independence from a specific AI vendor.

---

# 138.55 — The domain event catalog

Our initial catalog is now:

### Governance

$$
DecisionApproved
$$

$$
DecisionActivated
$$

$$
DecisionSuperseded
$$

$$
PolicyActivated
$$

$$
ExceptionApproved
$$

### Knowledge

$$
ClaimProposed
$$

$$
ClaimVerified
$$

$$
ClaimSuperseded
$$

### Evidence

$$
EvidenceCaptured
$$

$$
EvidenceValidated
$$

### Assurance

$$
VerificationCompleted
$$

$$
FindingRaised
$$

$$
FindingClosed
$$

### Agent/Execution

$$
SessionStarted
$$

$$
RecommendationProduced
$$

$$
ActionAuthorized
$$

$$
ActionExecuted.
$$

This is a **candidate event catalog**, not yet frozen.

---

# 138.56 — Event naming rule

Events should describe facts in the past tense:

$$
DecisionApproved
$$

rather than:

$$
ApproveDecisionEvent.
$$

Commands should describe requested actions:

$$
ApproveDecision.
$$

This keeps the semantic model clear.

---

# 138.57 — The most important event chain

For governed engineering:

$$
\boxed{
DecisionApproved
\rightarrow
ContextBuilt
\rightarrow
RecommendationProduced
\rightarrow
ActionAuthorized
\rightarrow
ActionExecuted
\rightarrow
EvidenceCaptured
\rightarrow
VerificationCompleted
}
$$

Then:

$$
VerificationCompleted(FAIL)
\rightarrow
FindingRaised.
$$

This is effectively the **KnowledgeOS assurance event chain**.

---

# 138.58 — The graph is rebuilt from facts

The Assurance Graph can now consume these events and create relationships.

For example:

```text id="m6q2p8"
DecisionApproved
       ↓
Decision D42
       ↓
governs
       ↓
Rule R17
       ↓
evaluates
       ↓
Verification V91
       ↓
supportedBy
       ↓
Evidence E93
       ↓
observed
       ↓
Runtime R44
```

The graph is therefore continuously reconstructed from governed state changes.

---

# 138.59 — Event sourcing?

We should **not yet conclude** that KnowledgeOS must be event-sourced.

Events can simply be integration/audit events.

Possible persistence strategies remain:

* state-oriented persistence;
* event-oriented persistence;
* hybrid.

The domain model does not force one.

---

# 138.60 — What the domain model has now achieved

We can now answer four critical questions:

### Who owns a concept?

Bounded context.

### Who can change it?

Authorized command.

### What proves the change?

Evidence.

### How does the rest of the platform learn about it?

Domain event / projection.

This is enough to move toward the implementable architecture.

---

# 138.61 — Step 138 verdict

We now have a first behavioral DDD model for KnowledgeOS.

The most important principles are:

$$
\boxed{
One\ authoritative\ owner\ per\ semantic\ concept.
}
$$

$$
\boxed{
Commands\ request\ state\ changes.
}
$$

$$
\boxed{
Only\ the\ owning\ context\ performs\ authoritative\ transitions.
}
$$

$$
\boxed{
Events\ record\ completed\ transitions.
}
$$

$$
\boxed{
Evidence\ records\ what\ happened\ in\ the\ engineering\ world.
}
$$

$$
\boxed{
The\ Assurance\ Graph\ connects\ these\ independently\ owned\ states.
}
$$

And the overall flow is:

$$
\boxed{
Governance
\rightarrow
Knowledge
\rightarrow
Agent
\rightarrow
Authorization
\rightarrow
Execution
\rightarrow
Evidence
\rightarrow
Assurance
\rightarrow
Governance.
}
$$

---

# Step 139 — Context Map & Integration Contracts

The next step is to move from **inside each bounded context** to the relationships **between them**.

We now need to define:

* which context is upstream;
* which is downstream;
* which relationships are queries;
* which are commands;
* which are events;
* where translation occurs;
* where the Anti-Corruption Layer is required;
* where the Assurance Graph acts as a projection.

The resulting artifact will be the first formal:

$$
\boxed{
KnowledgeOS\ Context\ Map
}
$$

and will answer a particularly important question:

> **How can KnowledgeOS connect Governance, Knowledge, Evidence, Assurance, Agents, Git, CI/CD, Kubernetes and Nexus without allowing any of them to become the accidental owner of another context's semantics?**
