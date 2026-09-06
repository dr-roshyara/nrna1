# Step 166 — Process Managers, Sagas, and Long-Running Consistency

We continue from Step 165.

We have now separated:

$$
\boxed{
Bounded\ Contexts
\rightarrow
Aggregates
\rightarrow
Invariants
}
$$

and:

$$
\boxed{
Commands
\rightarrow
State\ Transitions
\rightarrow
Domain\ Events
}
$$

The remaining architectural question is:

> **Who coordinates a business journey when it crosses several consistency boundaries?**

The answer must not be:

> “Put everything into one workflow.”

That would undo the work we have just done.

---

## 166.1 A process is not an aggregate

Consider:

$$
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
Action.
$$

This is a **process**.

But none of these objects necessarily belongs to one aggregate.

Therefore:

$$
\boxed{
Process \neq Aggregate
}
$$

A process coordinates.

An aggregate protects invariants.

---

# 166.2 Why we need coordination

Suppose:

$$
EvidenceValidated(E17)
$$

occurs.

This may trigger:

$$
EvaluateKnowledge(K12).
$$

If the evaluation establishes a new Knowledge version:

$$
KnowledgeConfirmed(K12.v7)
$$

then a Determination may become possible.

Eventually:

$$
DeterminationEstablished(D4).
$$

Governance may then need to decide.

There is therefore a chain of **causally related but independently owned state transitions**.

---

# 166.3 The coordination problem

We can represent the situation as:

```text id="6qk8t3"
Evidence BC
     │
     │ EvidenceValidated
     ▼
Knowledge BC
     │
     │ KnowledgeConfirmed
     ▼
Determination BC
     │
     │ DeterminationEstablished
     ▼
Governance BC
     │
     │ DecisionMade
     ▼
Authorization
     │
     │ AuthorizationGranted
     ▼
Operations
```

No single aggregate owns this entire chain.

---

# 166.4 Three possible mechanisms

DDD gives us several conceptual mechanisms:

1. **Domain events**
2. **Process Manager**
3. **Saga**

And we should also distinguish these from:

4. **Workflow engine**

They are not synonyms.

---

# 166.5 Domain events

Events communicate:

> Something happened.

For example:

$$
KnowledgeConfirmed.
$$

An interested component can react.

But an event by itself does not necessarily know:

> What should happen next?

That is where orchestration enters.

---

# 166.6 Process Manager

A Process Manager maintains the state of a long-running process.

Conceptually:

$$
PM:
Event^*
\rightarrow
NextCommand.
$$

For example:

$$
EvidenceValidated
\rightarrow
EvaluateKnowledge.
$$

Then:

$$
KnowledgeConfirmed
\rightarrow
EstablishDetermination.
$$

The Process Manager coordinates without owning the Knowledge invariant.

---

# 166.7 Important separation

The Process Manager may know:

$$
"After\ X,\ ask\ Y\."
$$

But it should not decide:

$$
"Knowledge\ is\ valid."
$$

That belongs to the Knowledge aggregate.

Therefore:

$$
\boxed{
ProcessManager \neq DomainAuthority
}
$$

---

# 166.8 Saga

A Saga is commonly used for a long-running business transaction involving multiple independently consistent operations.

The essential idea is:

$$
Step_1
\rightarrow
Step_2
\rightarrow
Step_3
$$

with compensation or alternative paths where necessary.

For KnowledgeOS, the question is not:

> “Can we use a Saga?”

but:

> **Do we actually have a distributed business transaction whose intermediate states are legitimate?**

---

# 166.9 This distinction matters

Suppose:

$$
DecisionMade
$$

occurs.

Then:

$$
AuthorizationRequested.
$$

If authorization is denied, we don't necessarily "roll back" the decision.

The decision remains historically valid.

Therefore this is not necessarily a traditional transactional Saga.

Instead:

$$
Decision
\rightarrow
AuthorizationDenied
$$

may simply be a legitimate terminal branch.

---

# 166.10 Compensation versus correction

This gives us another important distinction.

A compensation says:

> Undo or compensate a previous business action.

A correction says:

> The previous state/fact was incomplete or wrong, so establish a new state.

These are not the same.

For example:

$$
ActionExecuted
$$

followed by:

$$
ExecutionOutcomeObserved = Failure.
$$

The failure does not erase the execution.

It may create:

$$
CorrectiveActionRequested.
$$

---

# 166.11 Historical truth

This reinforces a principle from Step 164:

$$
\boxed{
Historical\ facts\ are\ not\ rolled\ back\ merely\ because\ their\ consequences\ are\ undesirable.
}
$$

If an action happened:

$$
ActionExecuted
$$

remains true.

A later action can compensate it.

---

# 166.12 Example: infrastructure change

Consider our Nexus scenario.

Decision:

> Upgrade Nexus.

Authorization:

> Administrator is authorized to perform the upgrade.

Action:

> Upgrade Nexus container.

Execution:

> Upgrade command executed.

Observation:

> Nexus version is now 3.x.

If the upgrade fails:

$$
ExecutionFailed.
$$

We do not pretend:

$$
ActionExecuted = false.
$$

The command was executed.

Its outcome was unsuccessful.

---

# 166.13 This is a crucial audit distinction

We can therefore have:

$$
ActionExecuted
$$

and:

$$
ExecutionOutcomeObserved(Failure).
$$

Both are true.

This is far more accurate than one mutable status field:

```text
action.status = FAILED
```

because that loses semantic distinctions.

---

# 166.14 Process state

A Process Manager may maintain something like:

$$
P =
(processID,
currentStep,
references,
status,
deadline).
$$

For example:

```text id="h5z0s3"
Process P17

Evidence validated
       ↓
Knowledge evaluation complete
       ↓
Determination established
       ↓
Awaiting governance
```

The Process Manager does not own the Knowledge or Governance state.

---

# 166.15 Process Manager as memory of coordination

This is an interesting conceptual role.

The aggregate remembers:

> What is true about **my domain object**?

The Process Manager remembers:

> **Where are we in this cross-boundary journey?**

Therefore:

$$
\boxed{
Aggregate = domain\ state
}
$$

while:

$$
\boxed{
ProcessManager = coordination\ state.
}
$$

---

# 166.16 Case as an alternative

There is another possibility:

$$
Case.
$$

A Case represents a unit of investigation/work.

For example:

> "Assess whether Nexus migration may proceed."

The Case could contain:

$$
Inquiry
+
EvidenceReferences
+
DeterminationReferences
+
DecisionReference.
$$

This might be closer to the actual KnowledgeOS domain than a generic Saga.

But we must derive this from the domain, not assume it.

---

# 166.17 Case versus Process Manager

They solve different problems.

### Case

Represents a business/investigative subject.

### Process Manager

Coordinates transitions between contexts.

Thus:

$$
Case \neq ProcessManager.
$$

A Case may be long-lived and contain many processes.

---

# 166.18 Inquiry

Our earlier Inquiry candidate becomes more interesting here.

Suppose the system receives:

> "Can Nexus be migrated safely?"

This is not merely a command.

It is an epistemic inquiry:

$$
I_1.
$$

It may generate:

$$
E_1,E_2,\ldots,E_n.
$$

Those feed:

$$
K_1,K_2,\ldots
$$

and eventually:

$$
D_1.
$$

This suggests that **Inquiry may be the root of the epistemic journey**, while not owning the Evidence or Knowledge itself.

---

# 166.19 A possible Case model

We could therefore have:

```text id="k7e0p8"
CASE C17
│
├── Inquiry I17
│
├── Evidence references
│    ├── E31
│    ├── E44
│    └── E52
│
├── Knowledge references
│    ├── K12.v3
│    └── K18.v1
│
├── Determination D7
│
└── Governance reference
     └── Decision D17
```

But these are **references**, not embedded ownership.

---

# 166.20 This gives us an important concept

The Case becomes:

$$
\boxed{
A\ navigational\ boundary\ across\ knowledge\ and\ governance.
}
$$

It answers:

> What are we trying to resolve?

rather than:

> What is true?

---

# 166.21 The three layers

We are beginning to see three different forms of state:

### Domain state

$$
Evidence,\ Knowledge,\ Decision,\ Authorization.
$$

### Process state

$$
CurrentStep,\ PendingAction,\ WaitingFor.
$$

### Historical lineage

$$
Event,\ Provenance,\ Causation.
$$

These should not be collapsed.

---

# 166.22 Why workflow engines can be dangerous

A workflow engine naturally wants to represent:

```text id="5h8k2a"
Step 1
  ↓
Step 2
  ↓
Step 3
```

But our domain is not necessarily linear.

We have:

$$
Branch,
Loop,
Defer,
Escalate,
Reject,
Reopen,
Contest.
$$

Therefore:

$$
Workflow
$$

must not become the source of domain meaning.

---

# 166.23 Workflow should be implementation of process

If we eventually use a workflow engine:

$$
DomainProcess
\rightarrow
WorkflowImplementation.
$$

Not:

$$
WorkflowDefinition
\rightarrow
DomainModel.
$$

This is another instance of our general architectural rule:

$$
\boxed{
Domain\ semantics\ precede\ technology.
}
$$

---

# 166.24 Deterministic workflow versus epistemic reasoning

There is also an important distinction.

A workflow can deterministically say:

> After event X, request Y.

But it should not necessarily determine:

> Whether proposition P is true.

That belongs to the Knowledge/Determination domain.

Therefore:

$$
WorkflowLogic
\neq
EpistemicLogic.
$$

---

# 166.25 Mathematical view

Let the process state be:

$$
P_t.
$$

An event \(e_t\) produces:

$$
P_{t+1}
=
F(P_t,e_t).
$$

The Process Manager may then issue:

$$
Command_t
=
G(P_{t+1}).
$$

But the target aggregate independently evaluates:

$$
Invariant(S,c).
$$

Thus:

$$
\boxed{
ProcessManager\ proposes;\ Aggregate\ decides\ legality.
}
$$

This is a very strong design rule.

---

# 166.26 Example

Process Manager:

> Knowledge has been confirmed; request determination.

Command:

$$
EstablishDetermination(K.v7).
$$

Determination aggregate checks:

$$
RequiredInputsPresent?
$$

$$
MethodSpecified?
$$

$$
ContextSpecified?
$$

If yes:

$$
DeterminationEstablished.
$$

If no:

$$
CommandRejected.
$$

The Process Manager cannot bypass the invariant.

---

# 166.27 This creates defense in depth

We now have:

$$
Policy
\rightarrow
Process
\rightarrow
Command
\rightarrow
AggregateInvariant.
$$

Even if process coordination is wrong, the aggregate protects itself.

This is a powerful architecture for AI-assisted systems.

---

# 166.28 AI and Process Managers

An AI agent may propose:

> "The next step should be to approve the architecture."

The Process Manager should not blindly execute that recommendation.

Instead:

$$
AIRecommendation
\rightarrow
CandidateCommand
\rightarrow
Governance/InvariantEvaluation.
$$

The system determines whether the transition is legitimate.

---

# 166.29 AI therefore operates inside the process

Not above it.

This is a crucial architectural principle:

$$
\boxed{
AI\ participates\ in\ the\ process;
AI\ does\ not\ own\ the\ process.
}
$$

---

# 166.30 The "Krishna" principle from Chapter 4

This also connects to the conceptual lesson you highlighted from Chapter 4.

You pointed out the idea that:

> the new state does not necessarily know the old state.

Architecturally, this becomes:

$$
CurrentState
\neq
CompleteHistoricalKnowledge.
$$

A current aggregate state may be insufficient to reconstruct why it became what it is.

Therefore:

$$
CurrentState
+
Lineage
+
HistoricalEvents
$$

may be necessary for reconstruction.

---

# 166.31 New state versus old state

Suppose:

$$
K.v7 = Current.
$$

It may not itself contain the entire reasoning history of:

$$
K.v1 \rightarrow K.v2 \rightarrow \cdots \rightarrow K.v7.
$$

Therefore:

$$
K.current
$$

is not equivalent to:

$$
KnowledgeHistory.
$$

This is a direct architectural consequence of the temporal principle.

---

# 166.32 "Only Krishna knows"

We should be careful not to literalize the theological metaphor into software architecture.

But as an architectural analogy, it is powerful:

> **The present state is not necessarily the complete witness of its own history.**

Therefore the platform requires an external lineage mechanism.

Formally:

$$
\boxed{
State(t)
\not\supseteq
History(0..t)
}
$$

in general.

---

# 166.33 Why this matters for agents

An agent starting a new session may see:

$$
CurrentState.
$$

It may not know:

$$
PreviousReasoning.
$$

Therefore:

$$
AgentMemory
\neq
SystemHistory.
$$

The system's authoritative lineage must live outside ephemeral agent context.

---

# 166.34 This validates our earlier architecture

This gives strong support to our distinction:

$$
\boxed{
Memory
\neq
Knowledge
\neq
Evidence
\neq
History.
}
$$

Agent memory can be useful.

But it cannot be the sole authority for organizational truth.

---

# 166.35 Process resumption

This also explains why process state must be durable.

Suppose:

$$
Process P17
$$

was waiting for governance.

The agent/session disappears.

The process should still exist:

$$
P17.status = WAITING\_FOR\_DECISION.
$$

A new agent/session can resume it from authoritative state.

This is a major requirement for a reliable AI engineering platform.

---

# 166.36 Session is not process

Therefore:

$$
\boxed{
AgentSession \neq BusinessProcess
}
$$

A session is a computational interaction.

A process is a domain coordination state.

A process may survive many sessions.

---

# 166.37 Session example

```text id="9jbx9p"
Process P17
│
├── Session S1
│    └── Agent investigates
│
├── Session S2
│    └── Verification
│
├── Session S3
│    └── Governance review
│
└── Session S4
     └── Operational execution
```

This is exactly why KnowledgeOS cannot equate agent context with system state.

---

# 166.38 Process identity

A process therefore needs its own identity:

$$
ProcessID.
$$

It may also reference:

$$
CaseID.
$$

and:

$$
CorrelationID.
$$

These identifiers should not be confused.

---

# 166.39 Four identities

We now potentially have:

$$
EntityID
$$

$$
ProcessID
$$

$$
CaseID
$$

$$
CorrelationID.
$$

They answer different questions:

| Identifier    | Question                               |
| ------------- | -------------------------------------- |
| EntityID      | Which domain object?                   |
| ProcessID     | Which coordination process?            |
| CaseID        | Which business/inquiry subject?        |
| CorrelationID | Which related interaction/event group? |

This distinction will be important later.

---

# 166.40 Long-running process

A process can exist for:

$$
minutes,
hours,
days,
months,
years.
$$

For architecture governance, this is normal.

For example:

$$
ArchitectureInquiry
$$

may remain open until:

* evidence is complete;
* stakeholders respond;
* governance decides;
* implementation occurs;
* outcome is verified.

---

# 166.41 Therefore process state must tolerate waiting

We need legitimate states such as:

$$
WAITING\_FOR\_EVIDENCE
$$

$$
WAITING\_FOR\_REVIEW
$$

$$
WAITING\_FOR\_AUTHORITY
$$

$$
WAITING\_FOR\_EXECUTION
$$

$$
WAITING\_FOR\_OBSERVATION.
$$

Waiting is not failure.

---

# 166.42 This is another important principle

$$
\boxed{
No\ event\ is\ not\ necessarily\ a\ negative\ event.
}
$$

Silence may mean:

$$
Waiting.
$$

The system should not invent a conclusion simply because a process has not progressed.

---

# 166.43 Timeout

A timeout is different.

If policy says:

$$
t > deadline
$$

then a new domain fact may occur:

$$
ProcessTimedOut.
$$

This is deterministic:

$$
t \geq Deadline
\Rightarrow
TimeoutEligible.
$$

Again:

$$
Unknown
\neq
Timeout.
$$

A timeout is itself an explicit rule-based state transition.

---

# 166.44 Escalation

Likewise:

$$
ProcessEscalated
$$

may occur when:

$$
Risk > Threshold
$$

or:

$$
AuthorityInsufficient.
$$

This can be deterministic if the rule is deterministic.

---

# 166.45 Statistical threshold caution

If escalation depends on:

$$
P(Risk > r)
$$

then the architecture must preserve the probabilistic basis.

It should not reduce:

$$
P=0.73
$$

to:

$$
Risk=true
$$

without a defined decision threshold.

This is where our mathematician/statistician lens continues to constrain the architecture.

---

# 166.46 Process state machine

A generic candidate:

```text id="c8v0zj"
OPEN
 │
 ├──► INVESTIGATING
 │       │
 │       ├──► WAITING
 │       │
 │       ├──► ESCALATED
 │       │
 │       └──► READY_FOR_DECISION
 │                    │
 │                    ├──► DEFERRED
 │                    │
 │                    ├──► REJECTED
 │                    │
 │                    └──► AUTHORIZED
 │                              │
 │                              ▼
 │                          EXECUTING
 │                              │
 │                              ▼
 │                          VERIFIED
 │
 └────────────────────────────► CLOSED
```

This is illustrative, not yet a frozen state machine.

---

# 166.47 Why the process model must remain separate

Notice:

$$
ProcessState = VERIFIED
$$

does not necessarily mean:

$$
Knowledge.status = CONFIRMED.
$$

These are different semantic dimensions.

The process can be complete even if the resulting knowledge is later contested.

---

# 166.48 Reopening

Suppose:

$$
ProcessClosed.
$$

New evidence:

$$
E_{new}.
$$

Then:

$$
ProcessReopened.
$$

This does not rewrite the original process.

It records a new transition.

---

# 166.49 The feedback loop

Now our architecture becomes:

$$
Process
\rightarrow
Action
\rightarrow
Observation
\rightarrow
Evidence
\rightarrow
Knowledge
\rightarrow
Process.
$$

This is a genuine feedback system.

---

# 166.50 Control-theoretic interpretation

At a higher abstraction:

$$
SystemState_t
$$

is observed through:

$$
Observation_t.
$$

The system derives:

$$
Knowledge_t.
$$

A controller/governance mechanism selects:

$$
Action_t.
$$

The environment responds:

$$
SystemState_{t+1}.
$$

Then:

$$
Observation_{t+1}.
$$

Thus:

$$
\boxed{
Observe
\rightarrow
Interpret
\rightarrow
Decide
\rightarrow
Act
\rightarrow
Observe.
}
$$

This is remarkably close to the architecture we have independently derived.

---

# 166.51 But we must avoid overclaiming

This does **not** mean KnowledgeOS is literally a control-theory system.

It means control theory provides a useful analytical lens.

The DDD model remains the primary architectural model.

---

# 166.52 Process Manager versus Agent

This distinction becomes crucial:

| Role            | Responsibility                       |
| --------------- | ------------------------------------ |
| Agent           | Generates/executes candidate work    |
| Aggregate       | Protects domain invariants           |
| Process Manager | Coordinates long-running transitions |
| Governance      | Exercises authority                  |
| Evidence        | Preserves epistemic basis            |
| Event Log       | Preserves historical facts           |

The agent is therefore only one participant.

---

# 166.53 The architecture is becoming self-correcting

Because:

$$
Action
\rightarrow
Observation
$$

and:

$$
Observation
\rightarrow
Evidence,
$$

the system can detect whether the result matches the expected outcome.

Then:

$$
ExpectedOutcome
\neq
ObservedOutcome
$$

becomes evidence for a discrepancy.

This can initiate:

$$
Investigation
$$

or:

$$
CorrectiveAction.
$$

---

# 166.54 Deterministic assurance loop

For a deterministic requirement:

$$
Expected = f(Input).
$$

After execution:

$$
Observed.
$$

Verification:

$$
V =
[Observed = Expected].
$$

Then:

$$
V \in \{0,1\}.
$$

The verification result itself can become Evidence.

This is one of the strongest bridges between your mathematical/statistical discipline and the engineering architecture.

---

# 166.55 AI-assisted assurance

AI can help generate:

$$
ExpectedCandidate
$$

or interpret:

$$
Observed.
$$

But where a deterministic oracle exists:

$$
Oracle(Observed)
$$

should remain authoritative.

Therefore:

$$
\boxed{
LLM\ interpretation\ must\ not\ replace\ deterministic\ verification.
}
$$

---

# 166.56 Step 166 architectural synthesis

We can now state:

$$
\boxed{
Aggregates\ protect.
}
$$

$$
\boxed{
Events\ record.
}
$$

$$
\boxed{
Commands\ request.
}
$$

$$
\boxed{
Processes\ coordinate.
}
$$

$$
\boxed{
Governance\ authorizes.
}
$$

$$
\boxed{
Evidence\ supports.
}
$$

$$
\boxed{
Knowledge\ represents\ governed\ claims.
}
$$

$$
\boxed{
Agents\ contribute.
}
$$

$$
\boxed{
Observations\ reconnect\ execution\ to\ knowledge.
}
$$

This is becoming a coherent architecture rather than a collection of patterns.

---

# 166.57 What we should NOT freeze yet

We should **not** yet decide:

* Saga versus Process Manager;
* workflow engine;
* BPMN;
* Kafka;
* event sourcing;
* microservices;
* exact process state machine.

Those require implementation/context evidence.

The conceptual conclusion is stronger than any specific technology choice.

---

# 166.58 Current target architecture

Our emerging target is:

```text
                         ┌─────────────────────┐
                         │       AGENTS        │
                         │ Claude / Codex / AI │
                         └──────────┬──────────┘
                                    │
                              candidate work
                                    │
                                    ▼
┌───────────┐   ┌───────────┐   ┌───────────────┐
│  EVIDENCE │──►│ KNOWLEDGE │──►│ DETERMINATION │
└───────────┘   └───────────┘   └───────┬───────┘
                                         │
                                         ▼
                                  ┌────────────┐
                                  │ GOVERNANCE │
                                  │  Decision  │
                                  └──────┬─────┘
                                         │
                                         ▼
                                  ┌─────────────┐
                                  │AUTHORIZATION│
                                  └──────┬──────┘
                                         │
                                         ▼
                                  ┌─────────────┐
                                  │ OPERATIONS  │
                                  │Action/Exec. │
                                  └──────┬──────┘
                                         │
                                         ▼
                                  ┌─────────────┐
                                  │ OBSERVATION │
                                  └──────┬──────┘
                                         │
                                         └──────► Evidence
```

Across the entire system:

```text
       ┌─────────────────────────────────────────┐
       │      PROCESS / CASE COORDINATION        │
       │                                         │
       │  waits • coordinates • escalates        │
       │  resumes • retries • closes             │
       └─────────────────────────────────────────┘

       ┌─────────────────────────────────────────┐
       │        LINEAGE / HISTORICAL FACTS       │
       │                                         │
       │ identity • provenance • causation       │
       │ correlation • versions • time           │
       └─────────────────────────────────────────┘
```

---

# 166.59 The deeper principle

The architecture now gives us a precise answer to a fundamental question:

> **How can a system act without forgetting why it acted?**

By maintaining three separate but connected structures:

$$
\boxed{
State
+
Process
+
Lineage
}
$$

State tells us:

> What is true now?

Process tells us:

> What are we doing / waiting for?

Lineage tells us:

> How did we get here?

And Evidence/Knowledge/Determination tell us:

> Why do we believe what we believe?

Governance tells us:

> Who had the authority to decide?

Operations tells us:

> What actually happened?

---

# 166.60 This is a major architectural milestone

We have now connected:

$$
\boxed{
DDD
+
Epistemology
+
Statistics
+
Governance
+
AI
+
Operational\ Assurance
}
$$

without requiring them to become one model.

That separation is itself one of the strongest architectural properties we have derived.

---

# Step 167 — Aggregate Invariants → Formal Verification Obligations

The next step should now become more mathematical.

We have identified candidate aggregates and processes.

Now we ask:

> **Can the important architectural rules be expressed formally enough that they can be independently verified?**

We will take the strongest invariants and classify them into:

1. **Structural invariants**
2. **Temporal invariants**
3. **Epistemic invariants**
4. **Governance invariants**
5. **Authorization invariants**
6. **Operational invariants**
7. **Lineage invariants**

Then we will derive a verification matrix:

$$
Invariant
\rightarrow
Predicate
\rightarrow
Evidence
\rightarrow
Verifier
\rightarrow
Verdict.
$$

This is where the architecture begins to connect directly to your established **deterministic assurance / verification / evidence ledger** model.

And this will be especially important because we must ultimately answer not merely:

> **“Is this architecture conceptually elegant?”**

but:

$$
\boxed{
Can\ we\ prove,\ test,\ or\ otherwise\ substantiate\ that\ the\ architecture's\ important\ claims\ actually\ hold?
}
$$

That is the next major threshold.
