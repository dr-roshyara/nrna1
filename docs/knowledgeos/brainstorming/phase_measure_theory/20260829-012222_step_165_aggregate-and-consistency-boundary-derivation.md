# Step 165 — Aggregate and Consistency-Boundary Derivation

We now move from **events** to the question of **ownership**.

Step 164 established:

$$
Command
\rightarrow
Invariant\ Evaluation
\rightarrow
State\ Transition
\rightarrow
Domain\ Event
$$

Step 165 asks:

> **Which domain boundary is responsible for deciding whether that transition is legal?**

This is where we derive aggregates.

---

## 165.1 The DDD rule

The aggregate is not:

> a convenient collection of related objects.

It is:

> **a consistency boundary that protects invariants.**

So our fundamental test is:

$$
\boxed{
Aggregate = State + Invariants + Transactional\ Consistency
}
$$

The key question is:

$$
\boxed{
What\ must\ change\ together?
}
$$

---

# 165.2 First candidate: Evidence

Consider an Evidence object:

$$
E =
(id,source,observation,provenance,integrity,status)
$$

Possible invariants include:

$$
Source(E) \neq \varnothing
$$

$$
Provenance(E) \neq \varnothing
$$

and, once accepted:

$$
Accepted(E) \Rightarrow IntegrityVerified(E).
$$

The important observation is that Evidence has its **own lifecycle**.

Therefore:

$$
\boxed{
Evidence \text{ is a strong aggregate candidate.}
}
$$

---

# 165.3 What should Evidence own?

Potentially:

```text
Evidence
 ├── identity
 ├── source reference
 ├── observation reference
 ├── provenance
 ├── integrity metadata
 ├── epistemic status
 └── lifecycle
```

But it should **not** own the Knowledge claim that it supports.

Instead:

$$
EvidenceID
$$

is referenced by Knowledge.

---

# 165.4 Why?

Because one Evidence item may support several claims:

$$
E_1
\rightarrow
\{K_1,K_2,K_3\}.
$$

If Knowledge belonged inside Evidence, we would create unnecessary coupling.

Therefore:

$$
\boxed{
Evidence\ owns\ Evidence.
}
$$

Not Knowledge.

---

# 165.5 Candidate: Knowledge

Knowledge is more complicated.

A Knowledge claim may contain:

$$
K =
(id,claim,status,validity,authority,version).
$$

Its invariants include:

$$
Status(K)
$$

and relationships such as:

$$
supports(E,K)
$$

and:

$$
supersedes(K_1,K_2).
$$

This suggests:

$$
\boxed{
Knowledge \text{ is also a strong aggregate candidate.}
}
$$

---

# 165.6 Knowledge should not own Evidence

Suppose:

$$
E_1,E_2,E_3
$$

support:

$$
K_1.
$$

Later:

$$
E_4
$$

is added.

We should not necessarily mutate four separate domain lifecycles atomically.

Instead:

$$
Knowledge
\rightarrow
EvidenceReferences.
$$

The Evidence context remains authoritative for Evidence.

---

# 165.7 Aggregate references

This leads to a general rule:

$$
\boxed{
Across\ aggregate/context\ boundaries:
reference\ identity,\ not\ internal\ state.
}
$$

For example:

```text
Knowledge K17
  evidence:
    E31
    E42
```

rather than embedding complete Evidence objects.

---

# 165.8 Candidate: Determination

Now consider:

$$
D =
(conclusion,
method,
context,
rationale,
inputReferences).
$$

The crucial question:

> Does a Determination have invariants that must be protected independently?

Potentially yes.

For example:

$$
Established(D)
\Rightarrow
RequiredInputsPresent(D)
$$

and:

$$
Established(D)
\Rightarrow
MethodSpecified(D).
$$

Therefore:

$$
\boxed{
Determination \text{ is a plausible aggregate candidate.}
}
$$

But I would not yet certify it.

---

# 165.9 Why Determination is uncertain

There are two possible designs.

### Model A

Determination is a first-class domain object:

$$
Determination
$$

with its own lifecycle.

### Model B

Determination is a governed projection of reasoning over Knowledge and Evidence.

In Model B:

$$
D = f(K,E,C,M).
$$

It may not require an independent aggregate.

We need more evidence before choosing.

---

# 165.10 The mathematician's test

Ask:

> Can two valid Determinations coexist over the same Knowledge?

Certainly.

$$
D_1=f(K,E,C_1)
$$

and:

$$
D_2=f(K,E,C_2).
$$

They can both be valid because context differs.

Therefore Determination cannot simply be treated as "the truth derived from Knowledge."

It is:

$$
\boxed{
Contextual\ conclusion.
}
$$

That strengthens the argument for a distinct model.

---

# 165.11 Candidate: Decision

Decision is different.

A Decision contains:

$$
Dec =
(choice,
authority,
scope,
time,
rationale).
$$

Its central invariant is:

$$
DecisionMade
\Rightarrow
ValidAuthority.
$$

Therefore:

$$
\boxed{
Decision \text{ has a strong governance consistency boundary.}
}
$$

But this does not necessarily mean:

> Decision must be its own bounded context.

It may belong inside the existing Governance context.

---

# 165.12 Aggregate versus bounded context

This distinction must remain explicit:

$$
\boxed{
Aggregate \neq BoundedContext
}
$$

A bounded context can contain several aggregates.

For example:

```text
Governance BC
 ├── Policy
 ├── Decision
 ├── Authority
 └── Delegation
```

So our earlier candidate:

$$
Governance = BC
$$

does not imply:

$$
Governance = Aggregate.
$$

---

# 165.13 Candidate: Authorization

Authorization contains:

$$
Auth =
(actor,
action,
resource,
policy,
scope,
validity).
$$

Its key invariant is:

$$
Grant
\Rightarrow
PolicySatisfied.
$$

It may also have temporal validity:

$$
t \in [ValidFrom,ValidUntil].
$$

Therefore Authorization has a meaningful lifecycle.

It is therefore a strong **aggregate candidate**, even if it remains an integration boundary between Governance and Operations.

---

# 165.14 Candidate: Action

Action contains:

$$
A =
(intent,target,parameters).
$$

But ask:

> What invariant does Action itself protect?

Potentially:

$$
Action
\Rightarrow
AuthorizationReference.
$$

But if Authorization is owned elsewhere, Action may simply reference it.

This suggests:

$$
Action
$$

may be part of an **Operational aggregate**, rather than necessarily its own aggregate.

---

# 165.15 Candidate: Execution

Execution is even less likely to be an aggregate.

It is often a recorded occurrence:

$$
Execution =
(actionRef,actor,time,outcome,effects).
$$

Its state may be:

$$
Pending
\rightarrow
Running
\rightarrow
Completed.
$$

But whether this requires a domain aggregate depends heavily on the operational domain.

For KnowledgeOS itself, I currently classify:

$$
\boxed{
Execution = aggregate candidate,\ but\ not\ yet\ justified.
}
$$

---

# 165.16 The aggregate derivation table

Our current position:

| Concept       | Aggregate candidate        | Confidence |
| ------------- | -------------------------- | ---------- |
| Evidence      | Yes                        | Strong     |
| Knowledge     | Yes                        | Strong     |
| Determination | Yes                        | Medium     |
| Decision      | Yes                        | Strong     |
| Authorization | Yes                        | Strong     |
| Action        | Possibly                   | Medium/Low |
| Execution     | Possibly                   | Low        |
| Observation   | Probably not independently | Medium     |
| Inquiry       | Possibly                   | Medium     |

These are **hypotheses**, not architecture decisions.

---

# 165.17 Observation

Observation may be a value/entity within Evidence rather than its own aggregate.

Why?

An Observation often has little independent governance lifecycle.

The stronger invariant usually concerns the Evidence record created from it.

Therefore:

$$
Observation
\subseteq Evidence
$$

may be reasonable in some contexts.

But we must not globally assume this.

---

# 165.18 Inquiry

Inquiry is interesting.

It has:

$$
Question
+
Scope
+
Objective.
$$

An Inquiry may generate many Evidence items:

$$
I_1
\rightarrow
\{E_1,E_2,E_3\}.
$$

It may also have lifecycle:

$$
Open
\rightarrow
Investigating
\rightarrow
Resolved.
$$

This makes it a possible aggregate.

But it may belong within a broader **Investigation/Inquiry context** rather than being globally shared.

---

# 165.19 The aggregate root test

For each candidate we apply:

### Test 1 — Identity

Does it have an identity independent of its attributes?

### Test 2 — Lifecycle

Does it change through meaningful states?

### Test 3 — Invariants

Does it protect rules that must always hold?

### Test 4 — Ownership

Is there a clear owner responsible for those rules?

### Test 5 — Consistency

Must internal changes be transactionally consistent?

If most answers are "yes":

$$
AggregateCandidate = Strong.
$$

---

# 165.20 Evidence test

| Test        | Evidence |
| ----------- | -------- |
| Identity    | Yes      |
| Lifecycle   | Yes      |
| Invariants  | Yes      |
| Ownership   | Likely   |
| Consistency | Yes      |

Result:

$$
\boxed{Strong}
$$

---

# 165.21 Knowledge test

| Test        | Knowledge |
| ----------- | --------- |
| Identity    | Yes       |
| Lifecycle   | Yes       |
| Invariants  | Yes       |
| Ownership   | Yes       |
| Consistency | Yes       |

Result:

$$
\boxed{Strong}
$$

---

# 165.22 Determination test

| Test        | Determination |
| ----------- | ------------- |
| Identity    | Yes           |
| Lifecycle   | Probably      |
| Invariants  | Yes           |
| Ownership   | Unclear       |
| Consistency | Probably      |

Result:

$$
\boxed{Medium}
$$

This is exactly where further analysis is justified.

---

# 165.23 Decision test

| Test        | Decision   |
| ----------- | ---------- |
| Identity    | Yes        |
| Lifecycle   | Yes        |
| Invariants  | Strong     |
| Ownership   | Governance |
| Consistency | Strong     |

Result:

$$
\boxed{Strong}
$$

---

# 165.24 Authorization test

| Test        | Authorization                |
| ----------- | ---------------------------- |
| Identity    | Yes                          |
| Lifecycle   | Yes                          |
| Invariants  | Strong                       |
| Ownership   | Security/Governance boundary |
| Consistency | Strong                       |

Result:

$$
\boxed{Strong}
$$

---

# 165.25 What must never happen

We should never construct:

$$
KnowledgeAggregate
$$

containing:

$$
EvidenceAggregate
+
DeterminationAggregate
+
DecisionAggregate
+
AuthorizationAggregate.
$$

That creates the dreaded:

$$
\boxed{God\ Aggregate}
$$

or:

> Everything that matters in one transaction.

That would destroy the bounded-context model.

---

# 165.26 Why the God Aggregate is dangerous

Suppose a Knowledge update requires:

* Evidence retrieval;
* Determination recalculation;
* Governance decision;
* Authorization;
* operational execution.

If all are one aggregate, then:

$$
TransactionScope
\rightarrow
entire\ organization.
$$

This creates:

* high coupling;
* long transactions;
* concurrency problems;
* unclear ownership;
* difficult evolution.

It also contradicts our semantic distinctions.

---

# 165.27 The opposite failure

The other extreme is:

```text
ObservationAggregate
EvidenceAggregate
KnowledgeAggregate
InquiryAggregate
ClaimAggregate
DeterminationAggregate
DecisionAggregate
AuthorityAggregate
AuthorizationAggregate
ActionAggregate
ExecutionAggregate
```

This can produce:

$$
AggregateCount \rightarrow excessive.
$$

Every small state change becomes distributed coordination.

Therefore:

$$
\boxed{
Neither\ one\ aggregate\ nor\ aggregate\ per\ noun.
}
$$

---

# 165.28 Consistency before distribution

Our architecture should first establish:

$$
ConsistencyBoundary.
$$

Only then ask:

> Should this be a service?

This prevents microservice-driven DDD.

---

# 165.29 Local consistency

Inside an aggregate:

$$
Invariant(A)
$$

should be guaranteed synchronously.

Across aggregates:

$$
Invariant(A_i,A_j)
$$

may require:

* domain events;
* process coordination;
* eventual consistency;
* explicit governance.

---

# 165.30 Example: Knowledge and Evidence

Suppose:

$$
E_1
$$

is validated.

Knowledge:

$$
K_1
$$

may later be updated because of this.

We do not need:

$$
Transaction(E_1,K_1)
$$

to be atomic.

Instead:

$$
EvidenceValidated
\rightarrow
KnowledgeEvaluation
\rightarrow
KnowledgeUpdated.
$$

This is a cross-aggregate process.

---

# 165.31 Eventual consistency is acceptable here

The Knowledge state may temporarily be:

$$
K(t_1)
$$

while Evidence has already become:

$$
E(t_1).
$$

Then eventually:

$$
K(t_2).
$$

This is acceptable if the domain does not require immediate consistency.

The key question is:

> Does the domain permit the temporary discrepancy?

Not:

> Is eventual consistency fashionable?

---

# 165.32 Statistical analogy

This resembles asynchronous observation.

At time \(t_1\):

$$
E_{new}
$$

is observed.

But the derived estimator:

$$
\hat{\theta}
$$

may only update later.

We do not say the observation is invalid merely because the derived state has not yet been recomputed.

Similarly:

$$
Evidence
$$

can exist before:

$$
Knowledge
$$

reflects it.

---

# 165.33 Determination consistency

A Determination may depend on a **specific version** of Knowledge.

Therefore:

$$
D
\rightarrow
K.v_n.
$$

Not:

$$
D
\rightarrow
K.current.
$$

This is becoming a very strong architectural requirement.

---

# 165.34 Why version references matter

Suppose:

$$
K.v3
$$

supported:

$$
D_7.
$$

Later:

$$
K.v4
$$

supersedes \(v3\).

We must still be able to reconstruct:

$$
D_7
$$

using:

$$
K.v3.
$$

Otherwise historical reasoning changes retroactively.

---

# 165.35 Aggregate implication

Knowledge versions therefore likely belong within the Knowledge consistency model.

The external Determination references:

$$
KnowledgeVersionID.
$$

This keeps the historical dependency explicit.

---

# 165.36 Decision consistency

A Decision should similarly reference the Determination version used when the decision was made:

$$
Decision
\rightarrow
Determination.v_n.
$$

This gives:

$$
Decision
\rightarrow
ReasoningBasis.
$$

Now the governance decision becomes auditable.

---

# 165.37 The complete historical chain

We can eventually reconstruct:

$$
Decision
\rightarrow
Determination.v_3
\rightarrow
Knowledge.v_7
\rightarrow
Evidence\{E_4,E_9,E_{11}\}
\rightarrow
Observation.
$$

This is precisely the kind of lineage we were trying to capture earlier.

---

# 165.38 And the reverse operational chain

We can also reconstruct:

$$
Decision
\rightarrow
Authorization
\rightarrow
Action
\rightarrow
Execution
\rightarrow
Observation'.
$$

Together:

```text id="v3e8sy"
                 WHY?
                  │
                  ▼
Evidence → Knowledge → Determination
                              │
                              ▼
                          Decision
                              │
                              ▼
                         Authorization
                              │
                              ▼
                            Action
                              │
                              ▼
                          Execution
                              │
                              ▼
                         WHAT HAPPENED?
```

This is becoming a powerful architecture.

---

# 165.39 "Why?" and "What happened?"

The architecture now supports two fundamentally different questions.

### Epistemic question

> Why did we believe/decide this?

Trace:

$$
Decision
\rightarrow
Determination
\rightarrow
Knowledge
\rightarrow
Evidence.
$$

### Operational question

> What happened after the decision?

Trace:

$$
Decision
\rightarrow
Authorization
\rightarrow
Action
\rightarrow
Execution
\rightarrow
Observation.
$$

This dual trace is extremely important.

---

# 165.40 Governance assurance

We can therefore define:

$$
Assurance =
EpistemicTrace
+
AuthorityTrace
+
OperationalTrace.
$$

This is much richer than a conventional audit log.

---

# 165.41 The three proof obligations

For a consequential action, we may require:

### Epistemic obligation

$$
Why?
$$

### Authority obligation

$$
Who\ authorized?
$$

### Operational obligation

$$
What\ happened?
$$

Therefore:

$$
\boxed{
Trustworthy\ execution
=
Reason
+
Authority
+
Evidence\ of\ outcome.
}
$$

This is an important candidate principle for the book.

---

# 165.42 Aggregate boundaries from these obligations

This reinforces:

$$
Evidence
$$

as an epistemic consistency boundary.

$$
Knowledge
$$

as a governed knowledge consistency boundary.

$$
Decision
$$

as a governance consistency boundary.

$$
Authorization
$$

as a permission consistency boundary.

$$
Action/Execution
$$

as operational consistency.

---

# 165.43 Candidate aggregate map

Our current model:

```text id="6y9t0k"
┌───────────────────┐
│ EVIDENCE          │
│ Evidence          │
│ Observation*      │
└─────────┬─────────┘
          │ references
          ▼
┌───────────────────┐
│ KNOWLEDGE         │
│ Knowledge         │
│ KnowledgeVersion  │
└─────────┬─────────┘
          │ references
          ▼
┌───────────────────┐
│ DETERMINATION     │
│ Determination     │
└─────────┬─────────┘
          │ proposal/input
          ▼
┌───────────────────┐
│ GOVERNANCE        │
│ Decision          │
│ Policy            │
│ Authority         │
└─────────┬─────────┘
          │
          ▼
┌───────────────────┐
│ AUTHORIZATION     │
│ Authorization     │
└─────────┬─────────┘
          │
          ▼
┌───────────────────┐
│ OPERATIONS        │
│ Action            │
│ Execution         │
└───────────────────┘
```

`Observation*` remains deliberately provisional.

---

# 165.44 A crucial distinction: ownership versus reference

Suppose Determination references:

$$
K.v7.
$$

That does **not** mean Determination owns Knowledge.

Likewise:

$$
Decision
\rightarrow
D.v3
$$

does not mean Governance owns Determination.

Ownership means:

> Who is responsible for maintaining the invariants of the object?

This is the correct DDD interpretation.

---

# 165.45 Aggregate interaction

We therefore get:

$$
Aggregate_A
\xrightarrow{DomainEvent}
Aggregate_B.
$$

For example:

$$
EvidenceValidated
\rightarrow
KnowledgeEvaluationRequested.
$$

The second is a command, not necessarily an event.

This gives us:

$$
Event
\rightarrow
Command
\rightarrow
InvariantCheck
\rightarrow
Event.
$$

That is a legitimate cross-aggregate pattern.

---

# 165.46 Example

```text id="m3rj1j"
EvidenceValidated
        │
        ▼
EvaluateKnowledge
        │
        ▼
Knowledge aggregate
        │
        ├── criteria fail
        │
        └── criteria pass
                │
                ▼
        KnowledgeConfirmed
```

This is much more rigorous than:

```text
EvidenceValidated
→ automatically set Knowledge = confirmed
```

---

# 165.47 AI-specific aggregate boundary

AI output should probably be represented separately from authoritative Knowledge.

For example:

$$
CandidateArtifact.
$$

Its lifecycle:

$$
Generated
\rightarrow
Captured
\rightarrow
Evaluated
\rightarrow
Accepted/Rejected.
$$

This suggests a potential **AI Artifact / Contribution aggregate**.

But we must not prematurely add another bounded context.

---

# 165.48 Why?

Because the question is:

> Does the AI artifact have independent domain invariants?

Likely yes.

For example:

$$
CandidateArtifact
\Rightarrow
OriginKnown.
$$

and:

$$
Accepted
\Rightarrow
EvaluationRecorded.
$$

But whether this belongs in an **AI Contribution context** or the broader Knowledge context remains open.

---

# 165.49 This is where our existing KnowledgeOS architecture matters

Our established system already has concepts around:

* agents;
* sessions;
* artifacts;
* hooks;
* governance;
* verification;
* memory;
* registries.

We should therefore not invent a theoretical aggregate and assume it maps directly to the implementation.

The next verification stage must ask:

$$
\boxed{
Does\ the\ current\ KnowledgeOS\ implementation\ already\ embody\ this\ boundary?
}
$$

That is where our **Current ↔ Target ↔ Evidence** discipline returns.

---

# 165.50 Mathematical consistency model

Let aggregate \(A_i\) have state:

$$
S_i.
$$

Its invariant set:

$$
I_i(S_i).
$$

A command \(c\) produces:

$$
S_i'
=
Transition(S_i,c)
$$

only if:

$$
I_i(S_i') = true.
$$

Then:

$$
Event_i
$$

may be emitted.

This gives us the formal structure:

$$
\boxed{
c
\overset{I_i}{\longrightarrow}
S_i'
\overset{}{\longrightarrow}
Event_i
}
$$

---

# 165.51 Cross-aggregate invariant

Suppose:

$$
I_{AB}(A,B)
$$

must hold.

We have to ask whether it is truly a **strong consistency invariant**.

If yes, perhaps \(A\) and \(B\) belong in one aggregate.

If no, eventual coordination may be sufficient.

This gives us a mathematical test for aggregate boundaries.

---

# 165.52 Example

If the invariant is:

$$
AuthorizationGranted
\Rightarrow
DecisionExists
$$

does Authorization and Decision need one aggregate?

Not necessarily.

The domain may tolerate:

$$
Decision
$$

existing first and:

$$
Authorization
$$

being created afterward.

Then:

$$
Decision
\rightarrow
AuthorizationRequest
$$

can be asynchronous.

---

# 165.53 But if the invariant were stronger

Suppose:

$$
AuthorizationGranted
\iff
DecisionApproved
$$

must hold **at every instant**.

Then the two concepts may require tighter consistency.

But even then, we should first investigate whether the invariant belongs to a higher-level process rather than a single aggregate.

---

# 165.54 Process versus aggregate

This distinction is critical.

A long-running business process:

$$
Decision
\rightarrow
Authorization
\rightarrow
Execution
$$

does not imply:

$$
Decision + Authorization + Execution
$$

are one aggregate.

The process coordinates them.

Potentially:

$$
Saga/ProcessManager.
$$

But we should only introduce that pattern if needed.

---

# 165.55 The correct hierarchy

Our current conceptual hierarchy is:

$$
\boxed{
BoundedContext
\supset
Aggregates
\supset
Entities/ValueObjects
}
$$

and separately:

$$
\boxed{
Processes\ coordinate\ aggregates.
}
$$

Events communicate facts.

Commands request actions.

Policies constrain transitions.

This gives us a much cleaner architecture.

---

# 165.56 Step 165 result

We can now provisionally classify:

### Strong aggregate candidates

$$
\boxed{
Evidence,\ Knowledge,\ Decision,\ Authorization
}
$$

### Medium candidates

$$
\boxed{
Determination,\ Inquiry
}
$$

### Weak/provisional candidates

$$
\boxed{
Action,\ Execution,\ Observation
}
$$

This is **not final**.

It is the result of applying the invariant/consistency test.

---

# 165.57 What we have learned

The most important lesson is:

> **A domain concept earns an aggregate boundary by possessing invariants that require local consistency—not simply because it is an important noun.**

And:

> **A bounded context earns its boundary because its model, language, ownership and invariants differ—not because we want another service.**

These two principles should remain central to the architecture.

---

# 165.58 Step 166 — Process Managers, Sagas and Long-Running Consistency

We have now reached the point where one question remains:

> If Evidence, Knowledge, Determination, Governance, Authorization and Operations are separate consistency boundaries, **who coordinates the journey between them?**

That is the purpose of Step 166.

We will examine whether KnowledgeOS needs concepts such as:

$$
ProcessManager
$$

$$
Saga
$$

$$
Workflow
$$

$$
Case
$$

or whether the existing governance mechanisms already provide the required coordination.

And we will be especially careful about one principle:

$$
\boxed{
A\ process\ coordinates\ domain\ boundaries;\ it\ must\ not\ become\ a\ substitute\ for\ domain\ ownership.
}
$$

This is the next place where we can test whether the architecture we have derived from Chapters 1–4 and our established KnowledgeOS lenses remains internally coherent.
