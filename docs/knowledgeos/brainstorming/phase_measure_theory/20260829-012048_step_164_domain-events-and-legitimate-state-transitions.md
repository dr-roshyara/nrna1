# Step 164 — Domain Events and Legitimate State Transitions

We continue from Step 163.

We now have the semantic chain:

$$
Observation
\rightarrow
Evidence
\rightarrow
Knowledge
\rightarrow
Determination
\rightarrow
Decision
\rightarrow
Authorization
\rightarrow
Action
\rightarrow
Execution
\rightarrow
Observation
$$

The danger now is to jump directly to:

> “Let's create an event for every arrow.”

We will **not** do that.

As senior DDD architect, mathematician and statistician, I want to establish a stricter rule:

$$
\boxed{
A\ domain\ event\ represents\ a\ meaningful\ fact\ that\ has\ already\ occurred.
}
$$

It is not:

* a command;
* a method call;
* an API endpoint;
* a database update;
* an intention.

---

# 164.1 Event versus command

This distinction is fundamental.

### Command

$$
Command = Request\ for\ an\ action.
$$

Example:

```text
ApproveArchitectureChange
```

It means:

> Someone requests that the architecture change be approved.

### Event

$$
Event = Fact\ that\ the\ approval\ occurred.
$$

Example:

```text
ArchitectureChangeApproved
```

Therefore:

$$
\boxed{
Command \neq Event
}
$$

and:

$$
Command
\rightarrow
possibly\ Event.
$$

Not every command produces the requested event.

---

# 164.2 Event versus state

An event says:

> Something happened.

State says:

> What is true now.

For example:

```text
KnowledgeConfirmed
```

is an event.

Whereas:

```text
Knowledge.status = CONFIRMED
```

is state.

Therefore:

$$
\boxed{
Event \neq State
}
$$

The state may be derived from a sequence of events, but we must not assume event sourcing simply because events exist.

---

# 164.3 Event versus relationship

Similarly:

$$
EvidenceSupportsKnowledge
$$

could be represented as a relationship.

But:

$$
EvidenceAcceptedAsSupport
$$

could be an event if that acceptance is itself a meaningful domain occurrence.

Again:

> **Not every relationship needs an event.**

---

# 164.4 Our event derivation rule

We therefore use:

$$
\boxed{
Invariant
+
State\ transition
+
Domain\ significance
\Rightarrow
Candidate\ Event
}
$$

This is our event derivation method.

---

# 164.5 Evidence lifecycle

Let an Evidence object have conceptual states:

$$
E \in
\{
Captured,
Validated,
Accepted,
Rejected,
Superseded
\}.
$$

We should not yet freeze this exact state machine.

Instead we identify transitions that have domain significance.

For example:

$$
Captured \rightarrow Validated.
$$

Candidate event:

$$
\boxed{EvidenceValidated}
$$

---

# 164.6 But capture itself may already be an event

If acquisition has domain significance:

$$
\boxed{EvidenceCaptured}
$$

is meaningful.

The important point is that the event means:

> Evidence was captured.

It does **not** mean:

> The evidence is true.

That distinction must remain explicit.

---

# 164.7 EvidenceCaptured

Conceptually:

$$
EvidenceCaptured
=
(id, source, method, time, context, provenance).
$$

It establishes existence.

It does not establish validity.

Thus:

$$
EvidenceCaptured
\not\Rightarrow
EvidenceValid.
$$

---

# 164.8 EvidenceValidated

A later event may be:

$$
EvidenceValidated.
$$

That means a defined validation procedure has established that the evidence satisfies the relevant integrity criteria.

Again:

$$
Validated
\neq
True.
$$

This is a crucial epistemic distinction.

---

# 164.9 EvidenceRejected

Likewise:

$$
EvidenceRejected.
$$

This does not necessarily mean the underlying observation is false.

It may mean:

> The evidence does not satisfy the requirements for use in this context.

Therefore:

$$
RejectedEvidence
\neq
FalseObservation.
$$

---

# 164.10 Knowledge lifecycle

Now consider Knowledge.

A candidate lifecycle might be:

```text
Proposed
   ↓
Supported
   ↓
Confirmed
   ↓
Superseded
```

with alternative branches:

```text
Proposed → Rejected
Supported → Contested
Confirmed → Contested
Confirmed → Superseded
```

Again, this is a candidate model.

---

# 164.11 KnowledgeProposed

The event:

$$
\boxed{KnowledgeProposed}
$$

means a candidate knowledge claim has entered the governed epistemic lifecycle.

It does **not** mean:

$$
KnowledgeConfirmed.
$$

This distinction is fundamental for AI-generated knowledge.

---

# 164.12 KnowledgeConfirmed

The event:

$$
\boxed{KnowledgeConfirmed}
$$

should only occur when the relevant confirmation invariant is satisfied.

Therefore:

$$
KnowledgeConfirmed
\Rightarrow
ConfirmationCriteriaSatisfied.
$$

The criteria themselves belong to the applicable context/policy.

---

# 164.13 KnowledgeContested

If contradictory evidence emerges:

$$
K_1
\leftrightarrow
K_2
$$

we may produce:

$$
\boxed{KnowledgeContested}
$$

rather than silently replacing one claim.

This preserves epistemic history.

---

# 164.14 KnowledgeSuperseded

When a later claim becomes authoritative for the relevant context:

$$
K_1
\rightarrow
K_2
$$

we may record:

$$
\boxed{KnowledgeSuperseded}
$$

with:

$$
supersededBy = K_2.
$$

The historical existence of \(K_1\) remains.

---

# 164.15 Determination lifecycle

A Determination is different.

A possible lifecycle:

```text
Candidate
   ↓
Evaluated
   ↓
Established
   ↓
Superseded
```

Candidate event:

$$
DeterminationProposed.
$$

Established event:

$$
DeterminationEstablished.
$$

---

# 164.16 Why "DeterminationEstablished" matters

It distinguishes:

> an AI produced a conclusion

from:

> the organization/system accepted that conclusion as an established determination under its rules.

This gives us:

$$
CandidateDetermination
\neq
EstablishedDetermination.
$$

---

# 164.17 AI-generated determination

Suppose Claude produces:

> “The proposed architecture violates invariant I-07.”

We should represent this initially as something like:

$$
CandidateDetermination.
$$

Only after evaluation can it become:

$$
DeterminationEstablished.
$$

This is the epistemic equivalent of a pull request awaiting review.

---

# 164.18 Governance decision

Now we cross the major boundary.

A Decision could have:

```text
Proposed
   ↓
Reviewed
   ↓
Made
```

Potential events:

$$
DecisionProposed
$$

$$
DecisionMade
$$

$$
DecisionRejected
$$

But we must be careful.

A rejected **proposal** is not necessarily a rejected **decision**.

Therefore the language must reflect the actual domain.

---

# 164.19 Better event naming

Instead of:

```text
DecisionRejected
```

we may need:

```text
DecisionProposalRejected
```

if the event means that a proposal did not become a decision.

This is a good example of why **Ubiquitous Language** must control event naming.

---

# 164.20 DecisionMade

The important event is:

$$
\boxed{DecisionMade}
$$

It establishes:

$$
Decision
$$

as an authoritative fact.

The event must preserve at least conceptually:

$$
DecisionID
+
Authority
+
Scope
+
Choice
+
Time.
$$

---

# 164.21 Decision is not authorization

After:

$$
DecisionMade
$$

we do not automatically have:

$$
AuthorizationGranted.
$$

The latter requires its own rules.

Thus:

$$
DecisionMade
\not\Rightarrow
AuthorizationGranted.
$$

This is another invariant.

---

# 164.22 Authorization lifecycle

Candidate states:

```text
Requested
   ↓
Evaluated
   ↓
Granted
```

or:

```text
Requested
   ↓
Denied
```

and potentially:

```text
Granted
   ↓
Expired
```

Candidate events:

$$
AuthorizationRequested
$$

$$
AuthorizationGranted
$$

$$
AuthorizationDenied
$$

$$
AuthorizationExpired.
$$

---

# 164.23 AuthorizationGranted

This event means:

> The actor is authorized to perform the specified operation under the specified conditions.

It does not mean:

$$
ActionExecuted.
$$

Therefore:

$$
AuthorizationGranted
\not\Rightarrow
Execution.
$$

---

# 164.24 Action

An Action can be created only when its prerequisites are satisfied.

Conceptually:

$$
ActionCreated
$$

means:

> an operational action has been defined for execution.

It does not mean it happened.

---

# 164.25 ActionExecuted

The corresponding event:

$$
\boxed{ActionExecuted}
$$

means the execution actually occurred.

This distinction is essential.

$$
ActionCreated
\neq
ActionExecuted.
$$

---

# 164.26 Execution result

Execution may produce:

$$
Success
$$

$$
Failure
$$

$$
Partial
$$

$$
Unknown.
$$

The last one is especially important.

Suppose the system issued a command but lost connection before receiving confirmation.

Then:

$$
ExecutionStatus = UNKNOWN.
$$

It would be dangerous to automatically record:

$$
Failure.
$$

---

# 164.27 Statistical interpretation

This resembles a missing-data problem.

We have:

$$
ObservedResult = \varnothing.
$$

But:

$$
TrueResult \in \{Success,Failure\}.
$$

Therefore:

$$
ObservedResult\ missing
\not\Rightarrow
TrueResult = Failure.
$$

This is exactly the same epistemic principle we established earlier:

$$
Unknown \neq False.
$$

---

# 164.28 ExecutionObserved

Once the result is independently observed:

$$
Execution
\rightarrow
Observation.
$$

A candidate event:

$$
\boxed{ExecutionOutcomeObserved}
$$

may establish the operational fact.

This is particularly useful for deterministic assurance.

---

# 164.29 The complete event chain

We can now construct a **candidate** event sequence:

```text id="h1xv0e"
EvidenceCaptured
        ↓
EvidenceValidated
        ↓
KnowledgeProposed
        ↓
KnowledgeConfirmed
        ↓
DeterminationProposed
        ↓
DeterminationEstablished
        ↓
DecisionMade
        ↓
AuthorizationGranted
        ↓
ActionCreated
        ↓
ActionExecuted
        ↓
ExecutionOutcomeObserved
        ↓
EvidenceCaptured
```

But this is **not a mandatory sequence**.

Branches are possible.

---

# 164.30 Branching is fundamental

For example:

```text id="7fpk1u"
DeterminationProposed
        │
        ├── Rejected
        │
        ├── Deferred
        │
        ├── Escalated
        │
        └── Accepted
                │
                ▼
            Decision
```

Thus the event graph is not linear.

---

# 164.31 Deferred

A governance decision may legitimately produce:

$$
Disposition = DEFER.
$$

Event:

$$
DecisionDeferred.
$$

The meaning is:

> No operational decision has yet been authorized.

This is not failure.

---

# 164.32 Escalated

Likewise:

$$
DecisionEscalated.
$$

This means authority was insufficient or the case requires a higher governance level.

Again:

$$
Escalation \neq Failure.
$$

---

# 164.33 Refrain

One of the most important events may be:

$$
DecisionDispositionSetToRefrain.
$$

This represents a deliberate decision **not to act**.

This is particularly valuable in AI systems because the architecture must support:

$$
NoAction
$$

as a valid outcome.

---

# 164.34 Why this matters for AI

A conventional workflow often implicitly assumes:

$$
Input \rightarrow Action.
$$

KnowledgeOS should support:

$$
Input
\rightarrow
Determine
\rightarrow
DoNothing.
$$

The "do nothing" outcome must be intentional and auditable.

---

# 164.35 Event immutability

A domain event describes a fact that happened.

Therefore:

$$
\boxed{
Past\ events\ should\ not\ be\ rewritten\ merely\ because\ our\ interpretation\ changed.
}
$$

If our interpretation changes, we record a new event.

Example:

$$
K_1\ Confirmed
$$

later:

$$
K_1\ Contested.
$$

We do not rewrite history to pretend confirmation never occurred.

---

# 164.36 Correction versus deletion

Suppose an event contains incorrect metadata.

We should distinguish:

$$
Correction
$$

from:

$$
Erasure.
$$

The appropriate mechanism depends on legal and governance requirements.

But semantically:

> A historical fact should not be silently rewritten.

---

# 164.37 Event provenance

Every important event should itself have provenance:

$$
P(Event).
$$

For example:

```text id="1h3k9a"
KnowledgeConfirmed
   ├── actor
   ├── authority
   ├── method
   ├── timestamp
   ├── evidence references
   └── correlation
```

Thus events become part of the overall lineage graph.

---

# 164.38 Correlation

A single engineering activity may generate many events.

For example:

$$
CorrelationID = C17.
$$

Then:

```text id="4w5qir"
C17
 ├── EvidenceCaptured
 ├── KnowledgeProposed
 ├── DeterminationEstablished
 ├── DecisionMade
 ├── AuthorizationGranted
 └── ActionExecuted
```

This allows reconstruction of the entire case.

---

# 164.39 Causation versus correlation

We must distinguish:

### Correlation

Events belong to the same case/process.

### Causation

One event directly contributed to another.

Therefore:

$$
Correlation \neq Causation.
$$

This distinction is important in forensic and statistical reasoning.

---

# 164.40 Example

Suppose:

$$
E_1 = EvidenceCaptured
$$

and later:

$$
D_1 = DeterminationEstablished.
$$

They may share:

$$
CorrelationID=C17.
$$

But we should not automatically claim:

$$
E_1 \Rightarrow D_1
$$

unless the Determination explicitly identifies \(E_1\) as an input.

Thus causal lineage should be explicit.

---

# 164.41 Event causation graph

We can therefore model:

$$
Event_i
\xrightarrow{caused/contributed}
Event_j.
$$

while separately maintaining:

$$
Event_i
\xrightarrow{correlated}
Case.
$$

This is a stronger audit model.

---

# 164.42 Event envelopes

A generic event envelope may conceptually contain:

$$
Envelope =
\{
EventID,
EventType,
AggregateID,
Context,
Timestamp,
Actor,
Authority,
CorrelationID,
CausationID,
Payload
\}.
$$

This is not yet a database schema.

It is a semantic contract.

---

# 164.43 Why AggregateID matters

If an event changes an aggregate's state, we need to know which aggregate owns that state.

For example:

$$
KnowledgeConfirmed(K17)
$$

belongs to Knowledge \(K17\).

The event should not ambiguously affect multiple Knowledge objects.

---

# 164.44 Aggregate invariants and events

This gives us the DDD relationship:

$$
\boxed{
Aggregate
\rightarrow
protects\ invariants
\rightarrow
emits\ events
}
$$

The event is a consequence of a valid state transition.

Not the other way around.

---

# 164.45 Example

Suppose:

$$
Knowledge.status = SUPPORTED.
$$

A command arrives:

```text
ConfirmKnowledge(K17)
```

The aggregate checks:

$$
ConfirmationCriteriaSatisfied(K17).
$$

If false:

$$
Command \rightarrow Rejected.
$$

No:

$$
KnowledgeConfirmed
$$

event should be emitted.

If true:

$$
StateTransition:
SUPPORTED \rightarrow CONFIRMED
$$

then:

$$
KnowledgeConfirmed.
$$

---

# 164.46 This is where deterministic assurance enters

If the confirmation rule is deterministic:

$$
f(K,E,R) \in \{true,false\},
$$

then the result can be independently reproduced.

That gives us:

$$
\boxed{
Deterministic\ rule
\rightarrow
Deterministic\ verification
\rightarrow
Evidence.
}
$$

AI can assist interpretation, but should not be allowed to replace a deterministic rule when the rule is explicitly deterministic.

---

# 164.47 Probabilistic reasoning

Some domains cannot be deterministic.

Then we may have:

$$
P(H|E).
$$

But we must preserve the distinction between:

$$
Probability
$$

and:

$$
Certainty.
$$

For example:

$$
P(H|E)=0.87
$$

does not mean:

$$
H=True.
$$

The architecture should preserve uncertainty rather than flattening it into a Boolean.

---

# 164.48 Confidence is not probability

Another important statistical safeguard:

$$
ConfidenceScore \neq Probability
$$

unless the statistical semantics have actually been defined.

An LLM's "confidence" field is especially dangerous if interpreted mathematically without calibration.

Therefore the book should be conservative about the word **confidence**.

---

# 164.49 Event state reconstruction

Suppose we have:

$$
E_1,E_2,\ldots,E_n.
$$

Conceptually:

$$
State_n =
Fold(State_0,E_1,\ldots,E_n).
$$

This is the mathematical basis of event-sourced state reconstruction.

But:

$$
\boxed{
We\ have\ NOT\ yet\ decided\ to\ use\ Event\ Sourcing.
}
$$

Events can exist in a conventional state-based system.

---

# 164.50 Event log versus event sourcing

Important distinction.

### Event log

We preserve meaningful events for audit/history.

### Event sourcing

The event stream becomes the authoritative persistence model from which current state is reconstructed.

Therefore:

$$
EventLog \neq EventSourcing.
$$

We should not make the latter an architectural requirement unless the evidence and domain justify it.

---

# 164.51 This restraint is important

Our architecture should not say:

> "Because we have lineage, therefore everything must be event sourced."

That would be technology-driven design.

Instead:

$$
DomainRequirement
\rightarrow
ArchitectureDecision.
$$

---

# 164.52 The event taxonomy

We can now distinguish:

### Epistemic events

```text
EvidenceCaptured
EvidenceValidated
KnowledgeProposed
KnowledgeConfirmed
KnowledgeContested
KnowledgeSuperseded
DeterminationProposed
DeterminationEstablished
```

### Governance events

```text
DecisionProposed
DecisionMade
DecisionDeferred
DecisionEscalated
DispositionRefrain
```

### Authorization events

```text
AuthorizationRequested
AuthorizationGranted
AuthorizationDenied
AuthorizationExpired
```

### Operational events

```text
ActionCreated
ActionExecuted
ExecutionFailed
ExecutionOutcomeObserved
```

This taxonomy remains provisional.

---

# 164.53 Event naming rule

We should adopt:

$$
\boxed{
Event = Past\ Tense + Domain\ Fact
}
$$

Examples:

Good:

```text
KnowledgeConfirmed
DecisionMade
AuthorizationGranted
ActionExecuted
```

Bad:

```text
ConfirmKnowledge
MakeDecision
GrantAuthorization
ExecuteAction
```

Those are commands.

---

# 164.54 Commands

The corresponding commands might be:

```text
ProposeKnowledge
ConfirmKnowledge
EstablishDetermination
MakeDecision
RequestAuthorization
GrantAuthorization
CreateAction
ExecuteAction
```

Again:

$$
Command \neq Event.
$$

---

# 164.55 Rejection is also a fact

If a command is rejected because an invariant fails:

$$
CommandRejected
$$

may be recorded.

But we should distinguish technical rejection from domain rejection where meaningful.

For example:

```text
AuthorizationDenied
```

is domain-specific.

Whereas:

```text
MalformedRequestRejected
```

is technical.

The event taxonomy should not mix these carelessly.

---

# 164.56 Domain event versus audit event

This distinction is worth preserving.

### Domain event

Changes/records something meaningful in the domain.

### Audit event

Records that an operation occurred for accountability.

A system can have:

$$
DomainEvent
$$

without necessarily treating every:

$$
AuditLogEntry
$$

as a domain event.

---

# 164.57 This matters for KnowledgeOS

A huge amount of technical telemetry may exist:

```text
file opened
HTTP request received
process started
database query executed
```

Not all of these should become domain events.

Otherwise:

$$
EventVolume \rightarrow enormous
$$

and the domain model disappears inside telemetry.

---

# 164.58 Domain signal versus technical noise

Our criterion should be:

$$
\boxed{
Does\ this\ fact\ matter\ to\ the\ domain\ model\ or\ its\ invariants?
}
$$

If not, it belongs in telemetry/logging rather than necessarily in the domain event model.

---

# 164.59 Step 164 synthesis

We now have a disciplined event model:

$$
\boxed{
Command
\rightarrow
Invariant\ Evaluation
\rightarrow
State\ Transition
\rightarrow
Domain\ Event
}
$$

and:

$$
\boxed{
Domain\ Event
\rightarrow
Lineage
\rightarrow
New\ State/Observation
}
$$

This gives us a causal architecture rather than a collection of arbitrary messages.

---

# 164.60 The complete conceptual loop

We can now express KnowledgeOS as:

$$
\boxed{
\begin{aligned}
Observation
&\rightarrow Evidence\\
&\rightarrow Knowledge\\
&\rightarrow Determination\\
&\rightarrow Decision\\
&\rightarrow Authorization\\
&\rightarrow Action\\
&\rightarrow Execution\\
&\rightarrow Observation'
\end{aligned}
}
$$

with each meaningful transition governed by:

$$
\boxed{
Invariant + Authority + Provenance + Time
}
$$

and recorded through appropriate domain facts.

---

# 164.61 What Step 164 has NOT established

We have **not** yet established:

* that every concept is an aggregate;
* that every transition is an event;
* that all events must be persisted forever;
* that Kafka is required;
* that Event Sourcing is required;
* that CQRS is required;
* that microservices are required.

Those remain architecture decisions.

This is deliberate.

---

# 164.62 Architectural consequence

We can now make a much stronger statement:

> **KnowledgeOS should derive its event model from domain invariants and legitimate state transitions, rather than deriving its domain model from an infrastructure messaging technology.**

That principle should eventually become part of the architectural constitution.

---

# Step 165 — Aggregate and Consistency-Boundary Derivation

Now we reach the next critical DDD step.

We know:

$$
Event
\rightarrow
StateTransition.
$$

But **who owns that transition?**

Step 165 will therefore derive the candidate aggregates.

For each major concept we will ask:

$$
\boxed{
What\ must\ change\ atomically?
}
$$

and:

$$
\boxed{
What\ invariant\ must\ this\ boundary\ protect?
}
$$

Then we will test candidates such as:

* Evidence;
* Knowledge;
* Determination;
* Decision;
* Authorization;
* Action.

The objective is to avoid the two classic failures:

$$
\text{one giant aggregate}
$$

and

$$
\text{an aggregate for every noun}.
$$

We will derive the boundaries from **consistency requirements**, not from object-oriented intuition.
