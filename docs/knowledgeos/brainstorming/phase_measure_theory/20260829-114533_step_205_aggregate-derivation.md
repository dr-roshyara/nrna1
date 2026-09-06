# Step 205 — Aggregate Derivation

We continue from Step 204.

We now have enough formal structure to attempt the first genuine DDD derivation:

$$
\boxed{
Which concepts must share a consistency boundary?
}
$$

The important methodological rule is:

> **We do not derive Aggregates from nouns. We derive them from invariants, transactional atomicity, identity, lifecycle and concurrency.**

So we will deliberately resist the temptation to create:

```text
EvidenceAggregate
AssessmentAggregate
DecisionAggregate
...
```

simply because those words exist.

---

## 205.1 What is an Aggregate in our model?

Let an Aggregate be:

$$
Agg=(I,O,B)
$$

where:

* \(I\) = identity;
* \(O\) = owned state;
* \(B\) = invariant boundary.

The key property is:

$$
\boxed{
B\text{ defines what must remain consistent atomically.}
}
$$

Therefore:

$$
Aggregate\neq DatabaseTable.
$$

And:

$$
Aggregate\neq Entity.
$$

And:

$$
Aggregate\neq BoundedContext.
$$

These distinctions must remain explicit.

---

# 205.2 Aggregate root

An Aggregate has one externally addressable root:

$$
Root(Agg).
$$

External actors should normally interact with the aggregate through:

$$
Root
$$

rather than directly manipulating internal objects.

Formally:

$$
ExternalCommand
\rightarrow
AggregateRoot
\rightarrow
InvariantValidation
\rightarrow
StateTransition.
$$

---

# 205.3 First derivation criterion — invariant ownership

For each invariant \(I_i\), ask:

> Which object has enough information and authority to guarantee this invariant?

Define:

$$
Owner(I_i)=Agg_j.
$$

If no aggregate can enforce the invariant locally, then one of three things is true:

1. the invariant is actually cross-context;
2. the model is missing a concept;
3. the proposed aggregate boundary is wrong.

This is much more rigorous than grouping objects by similarity.

---

# 205.4 Second criterion — atomicity

Suppose:

$$
A
$$

and:

$$
B
$$

must always change together.

Then:

$$
Atomic(A,B)=True.
$$

This is evidence that they may belong in the same aggregate.

But "usually change together" is insufficient.

We require:

$$
\boxed{
Atomicity\ must\ be\ required\ by\ an\ invariant.
}
$$

---

# 205.5 Third criterion — lifecycle

If object \(B\) cannot meaningfully exist without object \(A\), then:

$$
Lifecycle(B)\subseteq Lifecycle(A).
$$

This is evidence for containment.

But lifecycle dependency alone is not enough.

A child object may still belong to another aggregate if it has an independent consistency boundary.

---

# 205.6 Fourth criterion — concurrency

If two actors frequently modify:

$$
A
$$

and:

$$
B
$$

independently, placing them into one aggregate creates contention.

Therefore we must consider:

$$
ConcurrencyCost(A,B).
$$

An aggregate should be as small as possible while still protecting its invariants.

This gives us a useful optimization principle:

$$
\boxed{
Minimize\ aggregate\ size
\quad
subject\ to
\quad
Invariant\ preservation.
}
$$

---

# 205.7 Candidate 1 — Proposition

Consider:

$$
Proposition
$$

and:

$$
Assessment.
$$

Should they belong to the same aggregate?

At first glance:

$$
Proposition\rightarrow Assessment
$$

is a strong relationship.

But ask the invariant question.

Can a proposition exist before an assessment?

Yes.

Can a proposition receive multiple assessments over time?

Yes.

For example:

$$
A_1,A_2,A_3.
$$

Therefore:

$$
Lifecycle(Proposition)
\not\subseteq
Lifecycle(Assessment).
$$

---

# 205.8 Temporal assessment

We may have:

$$
P
\xrightarrow{Assessment_1}
A_1
$$

then:

$$
P
\xrightarrow{Assessment_2}
A_2.
$$

So the proposition has continuity independent of a particular assessment.

This strongly suggests:

$$
Proposition
$$

and:

$$
Assessment
$$

should not be treated as one mutable object.

---

# 205.9 Candidate result

A reasonable candidate is:

$$
\boxed{
PropositionAggregate
}
$$

with assessments represented as separate knowledge objects or references.

But we must be careful.

If the invariant is:

> "A proposition may have only one active assessment under a specific model/version"

then the aggregate may need to coordinate that invariant.

This could yield:

$$
PropositionAggregate
$$

owning:

$$
CurrentAssessmentReference.
$$

The historical assessments remain independently identifiable.

---

# 205.10 Candidate 2 — Evidence

Now:

$$
Observation
$$

and:

$$
Evidence.
$$

Can evidence exist independently?

Yes.

Can one observation generate multiple evidence interpretations?

Potentially.

Can evidence originate from non-observation sources?

Yes:

* documents;
* system records;
* external datasets;
* calculations.

Therefore:

$$
Evidence\neq Observation.
$$

This argues against an aggregate such as:

```text
ObservationWithEvidence
```

as a universal structure.

---

# 205.11 Candidate Evidence Aggregate

A stronger candidate is:

$$
EvidenceAggregate
$$

with:

$$
EvidenceSource
$$

and provenance information internally managed where the invariant requires it.

For example:

$$
Evidence.status=Verified
$$

may require:

$$
ProvenancePresent
$$

and:

$$
IntegrityValid.
$$

Then:

$$
I_E:
Verified\Rightarrow Provenance\land Integrity.
$$

That invariant belongs naturally inside the Evidence boundary.

---

# 205.12 Observation need not be owned by Evidence

This is subtle.

An Observation can be a source for evidence:

$$
O\rightarrow E.
$$

But it need not be a child entity inside the Evidence Aggregate.

Why?

Because:

$$
Observation
$$

may have its own lifecycle:

$$
Captured
\rightarrow
Corrected
\rightarrow
Superseded.
$$

That lifecycle may be independent.

Therefore:

$$
\boxed{
Reference\ is\ often\ preferable\ to\ containment.
}
$$

---

# 205.13 Candidate 3 — Assessment

Assessment has a stronger internal consistency requirement.

An assessment may need:

$$
Proposition
$$

$$
EvidenceReferences
$$

$$
ModelVersion
$$

$$
Uncertainty
$$

$$
AssessmentMethod
$$

and:

$$
ValidityPeriod.
$$

The key invariant is:

$$
Assessment
\Rightarrow
TraceableBasis.
$$

That is:

$$
I_A:
Assessment
\rightarrow
(P,E,M,U,L).
$$

---

# 205.14 But should Evidence be embedded?

No.

The assessment needs evidence **references**, not necessarily ownership.

Thus:

$$
AssessmentAggregate
$$

may contain:

$$
EvidenceRef_1,\ldots,EvidenceRef_n.
$$

This preserves aggregate independence.

---

# 205.15 Candidate 4 — Model

Should the statistical/semantic model belong inside Assessment?

Probably not.

A model has its own lifecycle:

$$
M_1\rightarrow M_2\rightarrow M_3.
$$

Multiple assessments can use the same model:

$$
M
\rightarrow
A_1,A_2,\ldots,A_n.
$$

Therefore:

$$
Model
$$

has independent identity and lifecycle.

Candidate:

$$
\boxed{
ModelRegistry/ModelContext
}
$$

rather than embedding models into assessments.

---

# 205.16 Model immutability

A major invariant emerges.

Once an assessment references:

$$
M_v
$$

we must not silently mutate that model into:

$$
M_{v+1}.
$$

Instead:

$$
M_v\neq M_{v+1}.
$$

Thus:

$$
\boxed{
VersionedModelIdentity
}
$$

is required for reproducibility.

This is especially important for AI models.

---

# 205.17 Candidate 5 — Uncertainty

Uncertainty is interesting because it may be:

* intrinsic to an assessment;
* derived from evidence;
* model-dependent.

We therefore should **not automatically create an Uncertainty Aggregate**.

Often:

$$
Uncertainty
$$

is part of the Assessment's semantic state.

For example:

$$
A=(status,U,basis).
$$

But if uncertainty has its own lifecycle, calibration, or governance, a separate model may be justified.

At present:

$$
\boxed{
Uncertainty\ is\ a\ value/semantic\ component\ of\ Assessment,
not\ automatically\ an\ Aggregate.
}
$$

---

# 205.18 Candidate 6 — Policy

Policy has independent lifecycle:

$$
Policy_v1
\rightarrow
Policy_v2.
$$

Multiple decisions may depend on one policy version.

Therefore:

$$
Policy
$$

should have identity/versioning independent from:

$$
Decision.
$$

A Decision should reference:

$$
PolicyVersion.
$$

It should not copy the whole mutable policy.

---

# 205.19 Policy invariant

For a governed action:

$$
ActionValid
\Rightarrow
ApplicablePolicyFound.
$$

Where the policy is versioned:

$$
ApplicablePolicy(t)=Policy_v.
$$

This means temporal policy selection matters.

---

# 205.20 Candidate 7 — Authority

Authority also has independent lifecycle.

An actor's authority can change:

$$
Authority_t
\neq
Authority_{t+1}.
$$

Therefore the decision record must preserve the authority basis at decision time.

We need:

$$
AuthorityVersion
$$

or equivalent historical lineage.

This is another instance of:

$$
\boxed{
PresentAuthority\neq HistoricalAuthority.
}
$$

---

# 205.21 Candidate 8 — Decision

Decision is a particularly strong Aggregate candidate.

A decision has:

* identity;
* decision status;
* decision basis;
* authority;
* policy;
* references to assessment;
* lifecycle.

Potential invariant:

$$
I_D:
FinalDecision
\Rightarrow
ValidDecisionBasis.
$$

Thus:

$$
DecisionAggregate
$$

is a strong candidate.

---

# 205.22 But Assessment is not inside Decision

A decision may depend on:

$$
AssessmentRef.
$$

It should not own the assessment.

Otherwise:

$$
Decision
$$

would become responsible for epistemic lifecycle.

That would violate bounded responsibility.

So:

$$
\boxed{
Decision\ references\ Assessment;\ it\ does\ not\ own\ Assessment.
}
$$

---

# 205.23 Candidate 9 — Action

Action is another strong candidate.

An Action represents an operational command/intention with:

* identity;
* target;
* authorization;
* status;
* execution information.

But Action should not own Decision.

Instead:

$$
Action
\xrightarrow{derivedFrom}
DecisionRef.
$$

This preserves the distinction:

$$
Decision\neq Action.
$$

---

# 205.24 Candidate 10 — Outcome

Outcome is more difficult.

An outcome may be produced by an external system.

Therefore it may not naturally belong to the Action Aggregate.

Instead:

$$
Action
\rightarrow
OutcomeObservation.
$$

The external world determines what happened.

This supports our earlier distinction:

$$
\boxed{
ExecutionState
\neq
Outcome.
}
$$

---

# 205.25 Candidate Aggregate map

Our current candidate is therefore:

```text id="j5p5t4"
┌───────────────────────┐
│ Proposition Aggregate │
└──────────┬────────────┘
           │ reference
           ▼
┌───────────────────────┐
│ Assessment Aggregate  │
└──────────┬────────────┘
           │ references
           ├──────────────► Evidence Aggregate
           │
           └──────────────► Model Version

Assessment
     │
     ▼
Decision Aggregate
     │
     │ references
     ├──────────────► Policy Version
     ├──────────────► Authority Version
     │
     ▼
Action Aggregate
     │
     ▼
Outcome / Observation
```

This is considerably cleaner than one giant Knowledge Aggregate.

---

# 205.26 Aggregate boundary principle

We can now formulate:

$$
\boxed{
An\ Aggregate\ owns\ invariants,\ not\ relationships.
}
$$

Two objects being related does not mean they belong together.

This should become one of our central DDD teaching principles.

---

# 205.27 Cross-aggregate references

The preferred pattern becomes:

$$
Aggregate_A
\rightarrow
ID_B
$$

rather than:

$$
Aggregate_A
\rightarrow
MutableObject_B.
$$

For example:

$$
Decision.AssessmentId.
$$

not:

$$
Decision.Assessment.
$$

This limits accidental coupling.

---

# 205.28 But IDs alone are insufficient

A reference must also identify the **semantic version** where required.

For example:

$$
AssessmentRef=
(AssessmentId,Version).
$$

Similarly:

$$
PolicyRef=
(PolicyId,Version).
$$

And:

$$
ModelRef=
(ModelId,Version).
$$

This is essential for historical reproducibility.

---

# 205.29 Decision Basis becomes concrete

We can now define:

$$
B_D=
\{
AssessmentRef,
PolicyRef,
AuthorityRef,
ContextRef
\}.
$$

The decision records the exact semantic inputs used.

Therefore:

$$
Decision
$$

is not dependent on today's mutable world to explain yesterday's decision.

This is a major architecture property.

---

# 205.30 Temporal consistency

Suppose:

$$
Decision_t
$$

was made under:

$$
Policy_{v1}.
$$

Later:

$$
Policy_{v2}
$$

becomes active.

We must not reinterpret the historical decision as if it used \(v2\).

Therefore:

$$
Decision_t.Basis.PolicyRef=Policy_{v1}.
$$

This gives us:

$$
\boxed{
Historical\ semantic\ references\ are\ immutable.
}
$$

---

# 205.31 This is where Chapter 4 becomes architecturally powerful

The "new state does not know the old state" insight translates into:

$$
CurrentPolicy
\neq
HistoricalPolicy.
$$

And:

$$
CurrentAuthority
\neq
HistoricalAuthority.
$$

And:

$$
CurrentModel
\neq
HistoricalModel.
$$

Therefore:

$$
\boxed{
Historical\ truth\ requires\ versioned\ semantic\ references.
}
$$

This is far more precise than simply keeping an audit log.

---

# 205.32 Audit versus lineage

We should distinguish:

$$
Audit
$$

from:

$$
Lineage.
$$

Audit asks:

> What happened and who did it?

Lineage asks:

> From what semantic inputs did this artifact arise?

Therefore:

$$
Audit\subsetneq Lineage
$$

in the conceptual sense.

Audit is one important projection of lineage.

---

# 205.33 Aggregate sizing test

For each candidate aggregate, we apply:

### Test A

Does it have an identity?

### Test B

Does it own an invariant?

### Test C

Does the invariant require atomic enforcement?

### Test D

Does it have an independent lifecycle?

### Test E

Would larger containment create unnecessary contention?

### Test F

Can other aggregates reference it without owning it?

If:

$$
A+B+C
$$

are true, we have strong evidence for an aggregate.

---

# 205.34 Current candidate assessment

| Candidate   | Aggregate confidence | Reason                          |
| ----------- | -------------------- | ------------------------------- |
| Proposition | High                 | identity + lifecycle            |
| Evidence    | High                 | provenance/integrity invariants |
| Assessment  | High                 | epistemic consistency           |
| Model       | High                 | versioned independent lifecycle |
| Policy      | High                 | normative lifecycle/versioning  |
| Authority   | Medium–High          | historical validity             |
| Decision    | Very High            | strong governance invariants    |
| Action      | High                 | execution lifecycle             |
| Outcome     | Low                  | externally determined           |
| Uncertainty | Low                  | likely Assessment component     |
| Lineage     | Low as aggregate     | cross-cutting concern           |

The low-confidence items are important.

We should **not force them into aggregates simply to make the diagram symmetrical**.

---

# 205.35 The symmetry trap

A beautiful architecture diagram might suggest:

$$
OneConcept=OneAggregate.
$$

That is aesthetically attractive but architecturally dangerous.

DDD does not optimize for symmetry.

It optimizes for:

$$
Cohesion
+
Consistency
-
Coupling.
$$

Therefore:

$$
\boxed{
Architectural\ asymmetry\ is\ acceptable.
}
$$

---

# 205.36 What about KnowledgeArtifact?

A KnowledgeArtifact may combine:

$$
PropositionRef
$$

$$
AssessmentRef
$$

$$
EvidenceRefs
$$

$$
LineageRef.
$$

It may be an aggregate **only if** it has its own invariants.

For example:

> A published knowledge artifact must have an approved assessment and complete provenance.

Then:

$$
I_{KA}:
Published
\Rightarrow
ApprovedAssessment
\land
CompleteLineage.
$$

Now it has a legitimate aggregate boundary.

---

# 205.37 Therefore KnowledgeArtifact is potentially important

It may represent the point where epistemic material becomes an organizationally reusable artifact.

Conceptually:

$$
Assessment
\rightarrow
KnowledgeArtifact
$$

but not automatically.

Publication itself is a domain decision.

This will be important later.

---

# 205.38 Aggregate commands

We can now begin deriving commands.

### Proposition

$$
CreateProposition
$$

$$
SupersedeProposition
$$

### Evidence

$$
RegisterEvidence
$$

$$
ValidateEvidence
$$

$$
SupersedeEvidence
$$

### Assessment

$$
CreateAssessment
$$

$$
ReassessProposition
$$

### Decision

$$
ProposeDecision
$$

$$
ApproveDecision
$$

$$
RejectDecision
$$

$$
SupersedeDecision
$$

### Action

$$
IssueAction
$$

$$
CancelAction
$$

$$
CompleteAction
$$

These are **candidate commands**, not final APIs.

---

# 205.39 Why commands matter

Commands represent intent:

$$
Command
\neq
Event.
$$

For example:

$$
ApproveDecision
$$

is intent.

If successful:

$$
DecisionApproved
$$

is the resulting event.

Therefore:

$$
\boxed{
Command\rightarrow Transition\rightarrow Event\rightarrow State.
}
$$

This is the next important layer of our architecture.

---

# 205.40 Aggregate and transition relationship

An aggregate should expose domain operations corresponding to valid transitions:

$$
AggregateRoot.command()
\rightarrow
Transition.
$$

The transition verifies:

$$
Pre
+
Authority
+
Policy
+
Invariant.
$$

Then emits the appropriate domain event.

---

# 205.41 The aggregate is therefore a guardian

A useful conceptual formulation:

$$
\boxed{
AggregateRoot=
Guardian\ of\ local\ invariants.
}
$$

It is not:

$$
RepositoryFacade.
$$

It is not:

$$
CRUDController.
$$

And it is not:

$$
WorkflowEngine.
$$

---

# 205.42 Where does orchestration belong?

Suppose the process is:

$$
EvidenceValidated
\rightarrow
AssessmentRequested
\rightarrow
AssessmentCompleted
\rightarrow
DecisionRequested.
$$

No single aggregate owns the entire sequence.

Therefore a process manager/application service can coordinate it.

Conceptually:

$$
ProcessManager:
Event
\rightarrow
Command.
$$

This is orchestration, not aggregate ownership.

---

# 205.43 Candidate architecture now has four levels

We can distinguish:

### Level 1 — Domain objects

$$
Entity,\ ValueObject.
$$

### Level 2 — Aggregates

$$
InvariantBoundaries.
$$

### Level 3 — Bounded Contexts

$$
ModelBoundaries.
$$

### Level 4 — Processes

$$
CrossContextOrchestration.
$$

This hierarchy is important.

---

# 205.44 Mathematical representation

Let:

$$
A_1,\ldots,A_n
$$

be aggregates.

Let:

$$
B_1,\ldots,B_m
$$

be bounded contexts.

Define:

$$
Owns(A_i,B_j)\in\{0,1\}.
$$

Normally:

$$
\sum_j Owns(A_i,B_j)=1.
$$

An aggregate should have one primary bounded-context owner.

But a bounded context may own:

$$
A_1,\ldots,A_k.
$$

Therefore:

$$
1\rightarrow N
$$

is natural:

$$
BoundedContext\rightarrow Aggregates.
$$

---

# 205.45 Aggregate-to-aggregate relationship

We should prefer:

$$
A_i
\xrightarrow{reference/event}
A_j
$$

rather than:

$$
A_i
\supset
A_j.
$$

Containment is reserved for true ownership.

---

# 205.46 Current derived structure

We can now state the candidate architecture as:

$$
\boxed{
\begin{aligned}
DomainContext
&\rightarrow Proposition/Evidence/Assessment\text{-related models}\\
GovernanceContext
&\rightarrow Policy/Authority/Decision\\
ExecutionContext
&\rightarrow Action\\
ObservationContext
&\rightarrow Outcome/Observation
\end{aligned}
}
$$

But this still leaves one major question:

> Which of these candidates genuinely deserve separate Bounded Contexts?

We cannot answer that solely from aggregates.

---

# 205.47 Step 205 verdict

### Aggregate derivation

$$
\boxed{\textbf{PASS}}
$$

We now have principled aggregate candidates.

### Strong candidates

$$
\boxed{
Proposition,\ Evidence,\ Assessment,\ Model,\ Policy,\ Decision,\ Action
}
$$

### Conditional candidates

$$
Authority,\ KnowledgeArtifact.
$$

### Probably not independent aggregates

$$
Uncertainty,\ Lineage,\ Outcome
$$

unless further domain invariants demonstrate otherwise.

---

# 205.48 Most important architectural result

We have derived a crucial rule:

$$
\boxed{
The unit of knowledge is not necessarily the unit of consistency.
}
$$

And:

$$
\boxed{
The unit of consistency is not necessarily the unit of process.
}
$$

And:

$$
\boxed{
The unit of process is not necessarily the unit of bounded context.
}
$$

This three-level distinction prevents a huge number of DDD design errors.

---

# Step 206 — Bounded Context Derivation

Now we are finally ready to derive the **Bounded Context Map**.

But we will do it properly.

We will test each candidate boundary using:

$$
\boxed{
UbiquitousLanguage
+
SemanticModel
+
InvariantOwnership
+
Team/Responsibility
+
Lifecycle
+
ChangeRate
+
IntegrationNeed
}
$$

and especially:

$$
\boxed{
Where\ does\ the\ meaning\ of\ a\ word\ change?
}
$$

That question is the true DDD boundary detector.

The next step should therefore not ask:

> "How many microservices should we build?"

It should ask:

> **"Where does KnowledgeOS need a different model of reality?"**

That is where our mathematical architecture and DDD architecture should converge.
