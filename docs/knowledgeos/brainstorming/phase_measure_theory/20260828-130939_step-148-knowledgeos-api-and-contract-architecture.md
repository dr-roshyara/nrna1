# Step 148 — KnowledgeOS API & Contract Architecture

We now cross another important boundary.

The domain model tells us **what KnowledgeOS means**.

The event model tells us **how bounded contexts communicate**.

Now we define:

$$
\boxed{
What\ external\ actors\ are\ actually\ allowed\ to\ ask\ KnowledgeOS\ to\ do?
}
$$

The API is therefore **not a mirror of the database**.

It is the stable contract between KnowledgeOS and its consumers.

---

# 148.1 — API architecture principle

The first rule is:

$$
\boxed{
API \neq Domain\ Model
}
$$

and:

$$
\boxed{
API \neq Database\ Schema
}
$$

The API should expose **capabilities and governed interactions**, not internal implementation structures.

---

# 148.2 — External actors

The initial consumers are:

```text
                    KnowledgeOS
                         │
       ┌─────────────────┼─────────────────┐
       │                 │                 │
       ▼                 ▼                 ▼
    Claude             Codex          Human/UI
       │                 │                 │
       └─────────────────┼─────────────────┘
                         │
                         ▼
                 KnowledgeOS API
                         │
       ┌─────────────────┼─────────────────┐
       ▼                 ▼                 ▼
      CI/CD        Engineering Systems   Governance
```

There may later be many more consumers.

The semantic contract should remain stable.

---

# 148.3 — API domains

The API should initially be organized around capabilities:

```text
Knowledge
Governance
Context
Assurance
Evidence
Agent
Action
Authorization
```

Not around database tables such as:

```text
/decisions
/policies
/evidence
/actions
```

alone.

---

# 148.4 — The most important API

The most important initial API is:

$$
\boxed{
Context API
}
$$

because this is the principal interface between KnowledgeOS and AI agents.

---

# 148.5 — Context request

Conceptually:

```text id="q3m8p2"
GetGovernedContext
```

Input:

```text
Agent
Task
Subject
Scope
Time
```

Output:

```text
ContextPackage
```

---

# 148.6 — Context request model

Conceptually:

```text id="j8q3m2"
ContextRequest
├── traceId
├── agentId
├── taskId
├── subject
├── scope
├── requestedAt
└── purpose
```

The `purpose` is important.

The same subject may require different context depending on the task.

---

# 148.7 — Context response

The response should not simply be:

```text
List<KnowledgeObject>
```

Instead:

```text id="m7q2v8"
ContextPackage
├── contextId
├── generatedAt
├── validUntil
├── governance
├── knowledge
├── rules
├── evidence
├── findings
├── permissions
└── provenance
```

---

# 148.8 — Context provenance

Every important context element should allow the agent to determine:

```text id="x8m3q2"
Where did this come from?
Why is it included?
What is its authority?
When was it valid?
```

Therefore:

$$
ContextItem
\rightarrow
SourceReference.
$$

---

# 148.9 — Context should be scoped

A request such as:

> Give me everything you know about Nexus.

should **not** become the standard interface.

Instead:

> Give me the governed context necessary to assess Nexus migration.

This creates:

$$
TaskScopedContext.
$$

---

# 148.10 — Context minimization

The Context Service should follow:

$$
\boxed{
Minimum\ sufficient\ governed\ context.
}
$$

Not:

$$
Maximum\ available\ context.
$$

This improves:

* agent reasoning;
* performance;
* security;
* explainability.

---

# 148.11 — Context API contract

A conceptual request:

```text
GET /context

subject=NEXUS-001
task=ASSESS-MIGRATION
scope=INFRASTRUCTURE
```

The actual protocol is not yet fixed.

The important architectural contract is the semantic request/response.

---

# 148.12 — Why we should not freeze REST yet

The architecture should not prematurely decide:

$$
REST
$$

versus:

$$
GraphQL
$$

versus:

$$
gRPC.
$$

The stable architectural decision is:

$$
\boxed{
A governed Context capability must exist.
}
$$

Protocol is an implementation decision.

---

# 148.13 — Agent API

Claude and Codex should have a common semantic capability set.

Initial contract:

```text id="p4m8q2"
Agent API
├── getContext
├── getKnowledge
├── getEvidence
├── requestVerification
├── createRecommendation
├── requestAction
└── getActionStatus
```

Some of these may eventually collapse into fewer endpoints.

The capability boundary matters more than endpoint count.

---

# 148.14 — Agent registration

Agents need stable identity.

Conceptually:

```text id="r7m3q8"
RegisterAgent
```

with:

```text
agentId
agentType
provider
capabilities
version
environment
```

---

# 148.15 — Agent identity

The system should distinguish:

$$
AgentType
$$

from:

$$
AgentInstance.
$$

For example:

```text
AgentType = CODE_AGENT
Provider = Codex
```

while:

```text
AgentInstance = specific execution environment
```

This distinction becomes important for audit.

---

# 148.16 — Agent capability declaration

An agent may declare:

```text id="n8q3m2"
QUERY_CONTEXT
REQUEST_VERIFICATION
CREATE_RECOMMENDATION
REQUEST_ACTION
```

The declaration is not itself permission.

It says:

> The agent implementation is capable of requesting this operation.

---

# 148.17 — Capability versus authorization again

The platform evaluates:

$$
MayPerform
=
DeclaredCapability
\land
Policy
\land
Principal
\land
Scope
\land
Context.
$$

Therefore:

$$
Capability \neq Permission.
$$

---

# 148.18 — Verification API

The agent or CI system can request:

$$
Verify.
$$

Conceptually:

```text id="v5m8q2"
RequestVerification
├── subject
├── ruleId
├── scope
├── requestedBy
└── traceId
```

The result:

```text
VerificationReference
```

---

# 148.19 — Verification response

For a synchronous verification:

```text
VerificationResult
├── verificationId
├── rule
├── verdict
├── evidenceReferences
└── completedAt
```

For long-running checks:

```text
VerificationJob
├── verificationId
└── status
```

followed by:

$$
GetVerification.
$$

---

# 148.20 — Evidence API

External systems and adapters should be able to register evidence.

Conceptually:

```text id="q8m3p2"
RegisterEvidence
```

Input:

```text
source
subject
observedAt
reference
provenance
```

Output:

$$
EvidenceID.
$$

---

# 148.21 — Evidence should not be freely mutable

We should avoid:

```text
PUT /evidence/123
```

as a generic update mechanism.

Evidence has historical significance.

Prefer:

$$
RegisterEvidence
$$

and:

$$
SupersedeEvidence
$$

where required.

---

# 148.22 — Governance API

Governance clients need operations such as:

```text id="m7q3p8"
CreateDecision
SubmitDecision
ApproveDecision
ActivateDecision
SupersedeDecision
```

The API should enforce governance lifecycle.

It should not allow:

```text
PATCH decision.status = EFFECTIVE
```

as a generic mutation.

---

# 148.23 — This is an important API principle

$$
\boxed{
State transitions should be expressed as domain operations,
not arbitrary field updates.
}
$$

Therefore:

```text
ActivateDecision
```

is architecturally preferable to:

```text
UpdateDecision(status="effective")
```

---

# 148.24 — Action API

The action boundary should expose:

```text id="c8m2q7"
RequestAction
GetAction
CancelAction
```

Potentially:

```text
RetryAction
```

but only if the semantics are well-defined.

---

# 148.25 — Action request

Conceptually:

```text id="j5q8m2"
ActionRequest
├── actionId
├── subject
├── operation
├── parameters
├── requestedBy
├── contextId
└── traceId
```

---

# 148.26 — No direct execution endpoint for agents

We should be very careful with an API such as:

```text
POST /execute
```

because it collapses:

$$
Request
$$

and:

$$
Execution.
$$

Instead:

$$
RequestAction
\rightarrow
Authorization
\rightarrow
Execution.
$$

---

# 148.27 — Authorization API

The authorization boundary should expose something like:

```text id="p8m3q2"
EvaluateActionAuthorization
```

Input:

```text
actionId
principal
scope
context
```

Output:

```text
AuthorizationResult
├── authorizationId
├── decision
├── conditions
└── validity
```

---

# 148.28 — Authorization must be independently auditable

The system must be able to answer:

> Why was this action allowed?

Traversal:

$$
Authorization
\rightarrow
Policy
\rightarrow
Decision
$$

and potentially:

$$
Authorization
\rightarrow
Context.
$$

---

# 148.29 — Authorization conditions

An authorization may be conditional.

For example:

```text
ALLOW
condition:
  stagingOnly
```

or:

```text
ALLOW
condition:
  humanApprovalRequiredForProduction
```

Therefore:

$$
Authorization
\neq
Boolean.
$$

---

# 148.30 — Action execution gate

The execution service should evaluate:

```text id="w6q3m8"
Authorization valid?
Context valid?
Required verification passed?
Subject available?
Action still unchanged?
```

Only if all required predicates are true:

$$
Execute.
$$

---

# 148.31 — Action fingerprint

This suggests an additional useful concept:

$$
ActionFingerprint.
$$

The authorization can bind to the exact action parameters.

For example:

$$
Hash(ActionDefinition).
$$

Then:

$$
AuthorizedAction
\neq
ModifiedAction.
$$

---

# 148.32 — Why this matters

Without action binding:

```text
Authorize:
Upgrade Nexus in staging

Execute:
Upgrade Nexus in production
```

could theoretically happen if the implementation is poorly designed.

An action fingerprint makes the authorization binding explicit.

---

# 148.33 — Context fingerprint

Likewise, high-risk actions may reference:

$$
ContextFingerprint.
$$

This can establish:

> The action was authorized against the context that was actually evaluated.

---

# 148.34 — High-risk action rule

For high-risk operations:

$$
\boxed{
Authorization
must bind to
Action
+
Context
+
Scope.
}
$$

---

# 148.35 — CI/CD integration

CI/CD is another important consumer.

It may request:

```text id="m8q3p2"
GetApplicableRules
RunVerification
RegisterEvidence
GetFindings
```

This allows pipelines to participate in the same assurance model as agents.

---

# 148.36 — CI and agents become symmetric consumers

The architecture becomes:

```text id="q7m3p8"
             KnowledgeOS
                 │
       ┌─────────┴─────────┐
       ▼                   ▼
     Agent                CI/CD
       │                   │
       └─────────┬─────────┘
                 ▼
          Governed APIs
```

This is important.

KnowledgeOS should not become an "AI-only" system.

---

# 148.37 — Engineering adapters

External engineering systems connect through adapters:

```text id="v8m3q2"
KnowledgeOS
     │
     ▼
Engineering Port
     │
     ├── Nexus Adapter
     ├── Git Adapter
     ├── Kubernetes Adapter
     └── CI Adapter
```

The domain never depends directly on their APIs.

---

# 148.38 — Adapter contract

For example:

```text id="c4q8m2"
NexusPort
├── getCurrentState()
├── getRepositories()
├── getBlobStores()
└── executeReadOperation()
```

The exact operations must be derived from actual Nexus use cases.

---

# 148.39 — Adapter responsibility

The adapter translates:

$$
ExternalModel
\rightarrow
KnowledgeOSContract.
$$

For example:

$$
NexusResponse
\rightarrow
Observation.
$$

It also translates:

$$
KnowledgeOSAction
\rightarrow
NexusCommand.
$$

---

# 148.40 — No adapter leakage

The following should never happen:

```text
Domain
   ↓
Nexus REST DTO
```

Instead:

```text
Domain
   ↓
Port
   ↓
Adapter
   ↓
Nexus DTO
```

---

# 148.41 — API security boundary

All external APIs need:

$$
Authentication.
$$

But authentication alone is insufficient.

We need:

$$
Authentication
+
Authorization
+
Scope
+
Audit.
$$

---

# 148.42 — Principal

Every request should have an identifiable principal:

```text id="n7m4q2"
Human
Agent
Service
CI/CD
System
```

This is different from:

$$
requestedBy.
$$

---

# 148.43 — Delegation

An agent may operate on behalf of a human.

Therefore we may need:

$$
Principal
$$

and:

$$
DelegatedBy.
$$

For example:

```text id="p8m3q2"
Principal = Codex
DelegatedBy = HumanUser
```

This makes the authority chain explicit.

---

# 148.44 — Delegation invariant

$$
\boxed{
An agent cannot acquire more authority through delegation than the delegating principal possesses.
}
$$

Formally:

$$
Authority_{agent}
\subseteq
Authority_{delegator}.
$$

Subject to policy.

---

# 148.45 — API audit

Every material API interaction should be traceable:

$$
RequestID
$$

$$
Principal
$$

$$
TraceID
$$

$$
Timestamp
$$

$$
Operation.
$$

This becomes part of the evidence/provenance chain where relevant.

---

# 148.46 — API idempotency

Commands such as:

$$
ActivateDecision
$$

or:

$$
RegisterEvidence
$$

may be retried.

Therefore important commands should support:

$$
IdempotencyKey.
$$

This is especially important when network failures occur after the server has already committed.

---

# 148.47 — Example

Without idempotency:

```text
Request
 ↓
Server commits
 ↓
Response lost
 ↓
Client retries
 ↓
Duplicate operation
```

With idempotency:

```text
Request(key=K42)
 ↓
commit
 ↓
response lost
 ↓
retry(key=K42)
 ↓
same semantic result
```

---

# 148.48 — API error model

Errors should be semantic.

Avoid:

```text
500 Something went wrong
```

where possible.

Prefer:

```text
CONTEXT_EXPIRED
AUTHORIZATION_DENIED
RULE_NOT_APPLICABLE
EVIDENCE_UNAVAILABLE
ACTION_ALREADY_EXECUTED
INVALID_STATE_TRANSITION
```

---

# 148.49 — API versioning

The API needs explicit semantic versioning.

Potential:

$$
/v1
$$

but versioning should be applied at the contract level, not used as an excuse for uncontrolled breaking changes.

---

# 148.50 — Contract evolution principle

$$
\boxed{
Backward-compatible additions should be preferred.
Breaking semantic changes require a new contract version.
}
$$

---

# 148.51 — API boundary architecture

We can now define:

```text id="x8m4q2"
              External Consumers
                     │
          ┌──────────┼──────────┐
          ▼          ▼          ▼
       Agent API  Governance   CI/API
          │          │          │
          └──────────┼──────────┘
                     ▼
              Application Layer
                     │
          ┌──────────┼──────────┐
          ▼          ▼          ▼
      Commands     Queries    Actions
          │          │          │
          ▼          ▼          ▼
       Domain      Read       Authorization
      Aggregates   Models        │
          │                      ▼
          └────── Events ───► Execution
                     │
                     ▼
               Integration
                     │
          ┌──────────┼──────────┐
          ▼          ▼          ▼
        Graph      Evidence   External Systems
```

---

# 148.52 — API does not own domain state

This distinction is critical.

The API layer:

$$
translates.
$$

The Application layer:

$$
orchestrates.
$$

The Domain:

$$
protects\ invariants.
$$

The Infrastructure:

$$
integrates.
$$

---

# 148.53 — Hexagonal structure

This fits naturally with the architecture principles already established:

```text id="j7m3q8"
             ┌──────────────────────┐
             │   Driving Adapters   │
             │ API / Agent / CI     │
             └──────────┬───────────┘
                        │
                        ▼
             ┌──────────────────────┐
             │   Application Core   │
             │ Commands / Queries   │
             └──────────┬───────────┘
                        │
                        ▼
             ┌──────────────────────┐
             │    Domain Model      │
             │ Aggregates / Rules   │
             └──────────┬───────────┘
                        │
                        ▼
             ┌──────────────────────┐
             │      Ports           │
             └──────────┬───────────┘
                        │
              ┌─────────┼─────────┐
              ▼         ▼         ▼
           Database   Graph    External APIs
```

This is a natural continuation of the previously frozen hexagonal architecture principles.

---

# 148.54 — KnowledgeOS API principle

The stable interface is therefore:

$$
\boxed{
Capability\ Contract
}
$$

rather than:

$$
CRUD\ Contract.
$$

---

# 148.55 — CRUD versus capability

Weak:

```text
PATCH /actions/42
{
  "status": "AUTHORIZED"
}
```

Strong:

```text
AuthorizeAction(42)
```

The second forces the system to execute authorization semantics.

---

# 148.56 — Same for verification

Weak:

```text
POST /verification
{
  "status": "PASS"
}
```

Strong:

```text
ExecuteVerification(ruleId, subject)
```

The verdict is **produced**, not supplied by the caller.

---

# 148.57 — Same for evidence

Weak:

```text
POST /claims
{
  "verified": true
}
```

Strong:

```text
RegisterEvidence(...)
VerifyClaim(...)
```

Verification becomes a domain operation.

---

# 148.58 — This is the anti-hallucination architecture

The platform should make it structurally difficult for an agent to manufacture authoritative state.

For example:

$$
Agent
\not\rightarrow
SetClaimVerified(true).
$$

Instead:

$$
Agent
\rightarrow
RequestVerification
\rightarrow
DeterministicChecker
\rightarrow
Verification.
$$

---

# 148.59 — API trust boundary

This gives us a clear trust hierarchy:

```text id="w7m3q2"
Agent assertion
       ↓
      LOW
       ↓
Candidate knowledge
       ↓
Evidence
       ↓
Deterministic verification
       ↓
Governed state
       ↓
HIGH
```

The API architecture reinforces this hierarchy.

---

# 148.60 — The API is therefore part of governance

This is an important conclusion.

API design is not merely technical interface design.

The API determines:

> **Which state transitions are structurally possible.**

Therefore:

$$
\boxed{
API\ design
=
Governance\ enforcement\ boundary.
}
$$

---

# 148.61 — Minimal KnowledgeOS v1 API

For the first Golden Trace, we need only a small API surface:

### Context

```text
GetGovernedContext
```

### Assurance

```text
RequestVerification
GetVerification
```

### Evidence

```text
RegisterEvidence
GetEvidence
```

### Agent

```text
CreateTask
CreateRecommendation
```

### Action

```text
RequestAction
GetAction
```

### Authorization

```text
EvaluateAuthorization
```

### Execution

```text
ExecuteAuthorizedAction
GetExecution
```

This is enough to prove the architecture.

---

# 148.62 — Do not build the entire API catalog first

This is another deliberate constraint.

We should not create:

> 150 endpoints before the first vertical slice works.

Instead:

$$
GoldenTrace
\rightarrow
MinimalContracts
\rightarrow
ReferenceImplementation.
$$

Then expand.

---

# 148.63 — API acceptance test

The first implementation should prove:

```text id="c6q8m2"
Agent
  ↓
GetGovernedContext
  ↓
RequestVerification
  ↓
RegisterEvidence
  ↓
CreateRecommendation
  ↓
RequestAction
  ↓
EvaluateAuthorization
  ↓
ExecuteAuthorizedAction
  ↓
GetExecution
```

and all records must remain traceable by:

$$
TraceID.
$$

---

# 148.64 — Claude/Codex compatibility test

The same sequence should work for:

```text
Claude → KnowledgeOS
```

and:

```text
Codex → KnowledgeOS
```

without changing:

* governance;
* rules;
* evidence;
* authorization;
* domain semantics.

Only the agent adapter differs.

---

# 148.65 — API architecture invariant

We can now add:

$$
\boxed{
API-001:
External consumers cannot directly mutate authoritative domain state.
}
$$

They must invoke domain operations.

---

# 148.66 — API-002

$$
\boxed{
API-002:
A verification verdict is produced by the assurance mechanism, not supplied by an untrusted caller.
}
$$

---

# 148.67 — API-003

$$
\boxed{
API-003:
A material action cannot bypass authorization.
}
$$

---

# 148.68 — API-004

$$
\boxed{
API-004:
Every material operation has trace/correlation identity.
}
$$

---

# 148.69 — API-005

$$
\boxed{
API-005:
External system models do not cross the port/adapter boundary.
}
$$

---

# 148.70 — API-006

$$
\boxed{
API-006:
Agent capability does not imply execution authority.
}
$$

---

# 148.71 — API-007

$$
\boxed{
API-007:
Authoritative state transitions occur through explicit domain operations.
}
$$

---

# 148.72 — API-008

$$
\boxed{
API-008:
Graph and search projections are not authoritative mutation interfaces.
}
$$

This prevents clients from treating the graph as the master state.

---

# 148.73 — The complete contract architecture

We can now see the platform boundary clearly:

```text id="g8m3q2"
                        ┌───────────────────┐
                        │   Claude / Codex  │
                        └─────────┬─────────┘
                                  │
                         Governed Agent API
                                  │
                                  ▼
                        ┌───────────────────┐
                        │ KnowledgeOS       │
                        │ Application Layer │
                        └─────────┬─────────┘
                                  │
                    ┌─────────────┼─────────────┐
                    ▼             ▼             ▼
                Governance     Assurance      Action
                    │             │             │
                    └──────┬──────┘             │
                           ▼                    ▼
                       Evidence            Authorization
                           │                    │
                           └────────┬───────────┘
                                    ▼
                               Execution
                                    │
                                    ▼
                          External Engineering
                                    │
                                    ▼
                                 Evidence
                                    │
                                    ▼
                              Verification
                                    │
                                    ▼
                                 Graph
```

---

# 148.74 — Step 148 verdict

The API architecture now establishes a crucial boundary:

$$
\boxed{
Agents\ consume\ governed\ capabilities;
they do\ not\ manipulate\ KnowledgeOS\ state\ directly.
}
$$

The first API does not need to be large.

It needs to be **semantically correct**.

The Golden Trace therefore gives us the minimum contract set required to build the first real KnowledgeOS vertical slice.

---

# Step 149 — Persistence & Data Architecture

The next step is to answer the infrastructure question that naturally follows:

> **Where does all of this state live, and which storage mechanism is authoritative for which kind of information?**

We need to distinguish:

$$
Transactional\ State
$$

$$
Evidence
$$

$$
Graph
$$

$$
Search
$$

$$
Event\ History
$$

$$
Agent\ Memory.
$$

The important architectural decision will be to avoid the common mistake of choosing **one database for everything**.

The target should instead define a clear **polyglot persistence boundary**, while retaining one authoritative transactional model per bounded context.
