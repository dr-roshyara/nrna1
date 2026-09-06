# Step 171 — The Authority–Responsibility–Evidence Matrix

We now continue from Step 170.

The previous step established the end-to-end transformation:

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
Execution
\rightarrow
Outcome
\rightarrow
Observation.
$$

Step 171 asks a different question:

> **Who is allowed to cause each transition, who is responsible for its correctness, and what evidence makes the transition legitimate?**

This is where the mathematical/epistemic model meets **DDD and governance**.

---

## 171.1 The first separation

We need at least five different roles in the lifecycle:

$$
Initiator
$$

$$
Producer
$$

$$
Verifier
$$

$$
DecisionMaker
$$

$$
Executor
$$

and, separately:

$$
AccountableOwner.
$$

These roles may sometimes be held by the same person or system.

But that must be an **explicit domain decision**, not an accidental consequence of implementation.

---

# 171.2 Capability, responsibility and authority

We already established:

$$
Capability \not\Rightarrow Authority.
$$

Now we add:

$$
Responsibility \not\Rightarrow Authority.
$$

And:

$$
Authority \not\Rightarrow Capability.
$$

These are three distinct relations.

For actor \(a\) and action \(x\):

$$
Cap(a,x)
$$

means:

> \(a\) can technically perform \(x\).

$$
Resp(a,x)
$$

means:

> \(a\) is responsible for the relevant result/process.

$$
Auth(a,x)
$$

means:

> \(a\) is legitimately permitted to perform \(x\).

---

# 171.3 Why this distinction matters

Consider a system administrator.

They may have:

$$
Capability(Admin,Deploy)=true.
$$

But:

$$
Authority(Admin,Deploy)=false
$$

for a particular production change because approval is missing.

And:

$$
Responsibility(Admin,Deploy)=true
$$

may still mean they are responsible for operating the deployment mechanism.

So:

$$
Cap \neq Auth \neq Resp.
$$

---

# 171.4 The five questions

For every important transition we should therefore ask:

| Question                         | Concept        |
| -------------------------------- | -------------- |
| Who can initiate it?             | Initiator      |
| Who produces the artifact/state? | Producer       |
| Who verifies it?                 | Verifier       |
| Who authorizes/decides?          | Decision Maker |
| Who executes it?                 | Executor       |
| Who remains accountable?         | Owner          |

This matrix prevents role collapse.

---

# 171.5 Transition matrix

Our current model can therefore be represented as:

| Transition                | Primary concern | Typical authority question       |
| ------------------------- | --------------- | -------------------------------- |
| Observation → Evidence    | qualification   | Who may establish evidence?      |
| Evidence → Knowledge      | inference       | Who may establish knowledge?     |
| Knowledge → Determination | reasoning       | Who may make the determination?  |
| Determination → Decision  | governance      | Who may decide?                  |
| Decision → Authorization  | authority       | Who may authorize?               |
| Authorization → Execution | control         | Who may execute?                 |
| Execution → Outcome       | operation       | Who records/establishes outcome? |
| Outcome → Observation     | feedback        | Who/what observes the result?    |

The exact actors remain domain-specific.

The structure does not.

---

# 171.6 Observation

An observation can originate from:

* a human;
* a sensor;
* a software system;
* a log;
* an external source;
* an AI agent.

But:

$$
Producer(Observation)
$$

does not automatically establish:

$$
Truth(Observation).
$$

The observation must carry provenance.

---

# 171.7 Evidence

The Evidence context asks:

> Is this observation sufficiently qualified to participate in an epistemic process?

Possible responsibilities:

$$
EvidenceProducer
$$

and:

$$
EvidenceVerifier.
$$

They do not necessarily have to be the same actor.

---

# 171.8 Knowledge establishment

This is more sensitive.

If:

$$
Evidence \rightarrow Knowledge
$$

then some mechanism must establish why the evidence supports the knowledge claim.

Possible mechanisms include:

$$
RuleBasedInference
$$

$$
StatisticalInference
$$

$$
HumanAssessment
$$

$$
AI-assistedInference.
$$

But the mechanism must be explicit.

---

# 171.9 AI's position

The architecture should permit:

$$
AI
\rightarrow
Hypothesis
$$

and:

$$
AI
\rightarrow
Assessment.
$$

But whether:

$$
AI
\rightarrow
EstablishedKnowledge
$$

is permitted depends on the domain's promotion rule.

This is much more precise than declaring:

> "AI cannot create knowledge."

That statement would be unnecessarily absolute.

---

# 171.10 The promotion rule

We therefore introduce:

$$
PromotionRule(S_i,S_{i+1}).
$$

For example:

$$
PromotionRule(
Assessment,
EstablishedKnowledge
)
$$

could require:

$$
EvidenceSufficient
\land
MethodValid
\land
RequiredReviewComplete.
$$

Only then:

$$
Assessment
\rightarrow
EstablishedKnowledge.
$$

---

# 171.11 Determination

Determination asks:

> Given the established/relevant knowledge and applicable rule, what follows?

For example:

$$
Knowledge:
ConfigurationDeviation.
$$

Rule:

$$
NonComplianceRule.
$$

Then:

$$
Determination:
NonCompliant.
$$

The person/system performing this operation needs the appropriate authority for that determination.

---

# 171.12 Decision

Now the organization may decide:

$$
Decision:
Remediate.
$$

But another legitimate decision could be:

$$
Decision:
AcceptRisk.
$$

Both could follow from the same determination depending on governance context.

Therefore:

$$
Determination
\not\Rightarrow
unique\ Decision.
$$

---

# 171.13 This is where DDD becomes particularly useful

The domain language should distinguish:

* **Determine**
* **Decide**
* **Authorize**
* **Execute**

These are different verbs.

If our domain language uses one word such as:

> "Approve"

for all four, we have a semantic ambiguity.

That ambiguity will eventually become an architectural defect.

---

# 171.14 Decision versus authorization

Consider:

> "The Architecture Board decided that the migration should proceed."

This is a decision.

It does not necessarily mean:

> "The deployment operator is authorized to execute it now."

The authorization may require:

* a specific role;
* a defined scope;
* a time window;
* a prerequisite;
* an additional control.

Thus:

$$
Decision
\rightarrow
Authorization
$$

is a separate governed transition.

---

# 171.15 Authorization

Authorization should therefore be explicit:

$$
Auth=
\langle
Actor,
Action,
Scope,
Policy,
ValidityPeriod
\rangle.
$$

For example:

$$
Auth(
Architect,
ApproveMigration,
Nexus,
Policy_{v4},
[09:00,17:00]
).
$$

This is considerably stronger than:

```text id="n9s3o2"
approved = true
```

---

# 171.16 Execution

Execution consumes authorization.

We can state:

$$
Execute(a,x,t)
\Rightarrow
ValidAuthorization(a,x,t).
$$

This is one of our candidate architectural invariants.

Where the domain allows emergency execution or retrospective approval, that must be modeled as a specific exception path—not hidden inside the normal path.

---

# 171.17 Separation of normal and exceptional paths

This is important.

Suppose emergency execution is allowed.

We should not weaken:

$$
AuthorizationRequired
$$

to accommodate it.

Instead:

$$
NormalPath:
Decision
\rightarrow
Authorization
\rightarrow
Execution
$$

and:

$$
EmergencyPath:
EmergencyCondition
\rightarrow
EmergencyAuthority
\rightarrow
Execution
\rightarrow
RetrospectiveReview.
$$

The exception becomes explicit.

---

# 171.18 This is classic governance modeling

An exception is not the absence of governance.

It is:

$$
DifferentGovernancePath.
$$

That distinction will become important when we later model real organizational processes.

---

# 171.19 Separation of duties

We can now examine a dangerous configuration:

$$
Actor=A
$$

performs:

$$
Generate
\rightarrow
Verify
\rightarrow
Approve
\rightarrow
Execute.
$$

This creates a potential:

$$
SinglePointOfTrust.
$$

The risk is not automatically unacceptable.

But it must be recognized.

---

# 171.20 Independence

Suppose:

$$
Producer=P
$$

and:

$$
Verifier=V.
$$

If:

$$
P=V,
$$

then the verification may lack independence.

But again:

$$
P=V
$$

does not automatically mean verification is invalid.

For low-risk deterministic checks, self-verification may be perfectly appropriate.

Thus we need:

$$
RequiredIndependence(C)
$$

as a domain/governance property.

---

# 171.21 Statistical interpretation

This resembles the issue of correlated evidence.

If the same actor produces and verifies the evidence, the verification is not independent evidence.

We should not pretend:

$$
E_1
$$

and:

$$
V(E_1)
$$

are two independent observations.

They may represent the same epistemic source.

---

# 171.22 Independence is contextual

We therefore distinguish:

$$
TechnicalIndependence
$$

from:

$$
OrganizationalIndependence.
$$

And:

$$
EpistemicIndependence.
$$

A verifier might be technically separate but still organizationally dependent.

Conversely, the same automated verifier may be perfectly independent of a human producer for a deterministic predicate.

---

# 171.23 A stronger principle

Therefore:

$$
\boxed{
RequiredSeparation
=
f(Risk,Authority,Domain,ControlObjective).
}
$$

Not:

$$
RequiredSeparation=always.
$$

This prevents overengineering.

---

# 171.24 The Authority–Responsibility matrix

We can now create a conceptual matrix:

| Lifecycle stage | Producer            | Verifier               | Decision authority    | Executor | Accountable       |
| --------------- | ------------------- | ---------------------- | --------------------- | -------- | ----------------- |
| Observation     | source              | —                      | —                     | —        | domain owner      |
| Evidence        | evidence producer   | evidence verifier      | —                     | —        | evidence owner    |
| Knowledge       | analyst/AI/system   | knowledge verifier     | —                     | —        | knowledge owner   |
| Determination   | analyst/rule engine | determination verifier | —                     | —        | domain authority  |
| Decision        | —                   | —                      | decision authority    | —        | governance owner  |
| Authorization   | —                   | authorization check    | authorizing authority | —        | authority owner   |
| Execution       | —                   | execution controls     | —                     | executor | operational owner |
| Outcome         | system/operator     | outcome verifier       | —                     | —        | process owner     |

This is deliberately generic.

We must later instantiate it for each actual bounded context.

---

# 171.25 Why "accountable" must be separate

Suppose an automated system executes:

$$
Action.
$$

Who is accountable?

Not necessarily the machine.

The architecture must distinguish:

$$
Actor_{technical}
$$

from:

$$
AccountableEntity.
$$

This is especially important in AI-supported systems.

---

# 171.26 AI accountability

We should therefore never model:

```text id="j4l6d1"
AI = accountable
```

merely because AI performed the action.

Instead:

$$
AI
\rightarrow
TechnicalActor
$$

while:

$$
AccountableHuman/Organization
$$

is modeled separately where required.

The exact governance arrangement is domain-specific.

---

# 171.27 Responsibility propagation

If a process contains:

$$
A\rightarrow B\rightarrow C,
$$

we should not automatically assume responsibility transfers.

A system may delegate execution while retaining accountability.

Therefore:

$$
Delegation
\neq
AccountabilityTransfer.
$$

This is another useful governance invariant.

---

# 171.28 DDD implication: responsibility belongs to the domain

A technical service should not decide organizational accountability merely because it owns a database table.

The domain model should represent the business responsibility explicitly where it matters.

---

# 171.29 Authority as a domain concept

Authority should therefore not be hidden entirely inside infrastructure RBAC.

There may be domain-level concepts such as:

$$
Mandate
$$

$$
Delegation
$$

$$
Role
$$

$$
Jurisdiction
$$

$$
Scope.
$$

Infrastructure authentication answers:

> Who are you?

Authorization answers:

> What may you do?

Governance answers:

> Why are you legitimately entitled to make this decision?

These are related but distinct.

---

# 171.30 Authentication is not authorization

We should add another law:

$$
\boxed{
Identity
\neq
Authority.
}
$$

Knowing:

$$
Actor=A
$$

does not imply:

$$
Auth(A,x).
$$

This is basic security reasoning, but it becomes much more important when embedded into our broader epistemic architecture.

---

# 171.31 Authorization is not accountability

Likewise:

$$
Auth(A,x)
\not\Rightarrow
Accountable(A,x).
$$

A person may be authorized to execute an action while another organizational role remains accountable for the outcome.

---

# 171.32 Accountability is not correctness

And:

$$
Accountable(A,x)
\not\Rightarrow
Correct(x).
$$

Accountability concerns responsibility, not epistemic truth.

This prevents several categories from collapsing into one.

---

# 171.33 We now have a richer relation system

For actor \(a\):

$$
Identity(a)
$$

$$
Capability(a,x)
$$

$$
Authority(a,x)
$$

$$
Responsibility(a,x)
$$

$$
Accountability(a,x)
$$

are distinct predicates.

A mature KnowledgeOS architecture should resist representing all of them as:

```text
role = admin
```

---

# 171.34 The transition contract

We can now define a general transition contract:

$$
T=
\langle
SourceState,
TargetState,
Preconditions,
Producer,
Verifier,
Authority,
Evidence,
Time,
Accountability
\rangle.
$$

A transition is valid only when its applicable contract is satisfied.

Formally:

$$
Valid(T)
=
Preconditions
\land
EvidenceSufficient
\land
AuthorityValid
\land
TemporalConstraints
\land
VerificationSatisfied.
$$

The exact conjunction will vary by domain; the important point is that these dimensions are explicit.

---

# 171.35 This is the beginning of a constitutional model

We can now see how an architecture constitution could be expressed.

Not as:

> "Use microservices."

Not as:

> "Use event-driven architecture."

But as invariants such as:

$$
I_1:
Evidence\ must\ have\ provenance.
$$

$$
I_2:
Consequential\ decisions\ must\ have\ identifiable\ authority.
$$

$$
I_3:
Execution\ must\ respect\ applicable\ authorization.
$$

$$
I_4:
Verification\ scope\ must\ be\ explicit.
$$

$$
I_5:
Historical\ decision\ justification\ must\ be\ reconstructible\ where\ required.
$$

These are architectural laws.

---

# 171.36 Falsification of the authority model

Now we attack our own model.

Could a simple application work without explicit authority objects?

Yes.

For example:

```text id="v4h5hx"
if user.isAdmin():
    execute()
```

For a tiny low-risk application, that may be sufficient.

Therefore we must not claim:

> Every system needs a sophisticated Authority domain.

Instead:

$$
AuthorityModelComplexity
\propto
GovernanceComplexity.
$$

---

# 171.37 Another counterexample

Could decision and authorization be the same event?

Yes.

For example:

> A user with a specific role clicks "Approve and Execute."

In that domain:

$$
Decision
\land
Authorization
\rightarrow
Execution
$$

may be a single transaction.

But semantically the concepts still exist if the domain distinguishes them.

Implementation co-location does not eliminate conceptual distinction.

---

# 171.38 This is a recurring theme

We repeatedly find:

$$
ConceptualSeparation
\neq
PhysicalSeparation.
$$

Two concepts may share:

* a service;
* a transaction;
* a database;
* a UI.

Yet they can still have distinct semantics and invariants.

This is exactly why DDD is useful.

---

# 171.39 Bounded contexts emerge from semantic pressure

We should therefore not prematurely declare:

```text
Evidence Service
Knowledge Service
Decision Service
Authorization Service
...
```

as separate microservices.

First establish:

$$
BoundedContext.
$$

Then determine:

$$
DeploymentBoundary.
$$

The latter is an implementation decision.

---

# 171.40 The central architectural rule

This deserves explicit status:

$$
\boxed{
Do\ not\ derive\ domain\ boundaries\ directly\ from\ technical\ component\ boundaries.
}
$$

Instead:

$$
DomainMeaning
\rightarrow
Invariants
\rightarrow
Boundaries
\rightarrow
Architecture
\rightarrow
Technology.
$$

---

# 171.41 The complete authority flow

We can now enrich the original chain:

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
Execution
\rightarrow
Outcome.
$$

With responsibility:

$$
Actor
\rightarrow
Producer
\rightarrow
Verifier
\rightarrow
DecisionMaker
\rightarrow
Authorizer
\rightarrow
Executor
\rightarrow
AccountableOwner.
$$

The two dimensions intersect.

That intersection is the governance architecture.

---

# 171.42 A two-dimensional model

We can visualize:

```text
                  EPISTEMIC FLOW
                      │
Observation → Evidence → Knowledge → Determination
                                             │
                                             ▼
                                         Decision
                                             │
                                             ▼
                                       Authorization
                                             │
                                             ▼
                                          Execution
                                             │
                                             ▼
                                          Outcome
                                             │
                                             └──────► Observation


                  GOVERNANCE FLOW
                      │
                  Producer
                      │
                  Verifier
                      │
                Decision Maker
                      │
                  Authorizer
                      │
                   Executor
                      │
               Accountable Owner
```

These flows intersect but are not identical.

---

# 171.43 The profound consequence

We can now state:

$$
\boxed{
KnowledgeOS\ is\ not\ merely\ an\ information\ flow.
It\ is\ an\ information\ flow\ constrained\ by\ authority.
}
$$

And:

$$
\boxed{
Governance\ is\ not\ merely\ a\ set\ of\ approvals.
It\ is\ the\ control\ structure\ governing\ epistemic\ and\ operational\ transitions.
}
$$

This is a much deeper architecture.

---

# 171.44 The Chapter 4 connection

Your earlier Chapter 4 insight now enters again.

If the current state does not contain all previous state, then:

$$
Authority_{current}
$$

cannot necessarily tell us:

$$
Authority_{past}.
$$

Therefore historical authorization must be reconstructible when historical accountability matters.

For a past action:

$$
X_{t_0},
$$

we need to establish:

$$
Auth(a,x,t_0).
$$

Not merely:

$$
Auth_{now}(a,x).
$$

---

# 171.45 Historical authority

This gives us:

$$
Authority(t).
$$

An actor's authority is temporal.

For example:

$$
Authority(a,x,t_1)=true
$$

but:

$$
Authority(a,x,t_2)=false.
$$

Therefore:

$$
CurrentAuthority
\neq
HistoricalAuthority.
$$

This is another reason that versioned governance records matter.

---

# 171.46 The temporal authorization invariant

For a normal execution:

$$
\boxed{
Execution(a,x,t)
\Rightarrow
ValidAuthority(a,x,t).
}
$$

Not:

$$
ValidAuthority(a,x,t_{now}).
$$

This distinction is essential.

---

# 171.47 Step 171 verdict

The authority analysis confirms that our architecture requires **multiple orthogonal dimensions**:

$$
\boxed{
Identity
\neq
Capability
\neq
Responsibility
\neq
Authority
\neq
Accountability.
}
$$

And the lifecycle requires:

$$
\boxed{
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
Execution.
}
$$

The two structures must meet without being collapsed.

---

# Step 172 — The Separation-of-Duties Falsification Experiment

The next step should now be an actual **architectural experiment**.

We will construct several concrete scenarios:

### Scenario A

One human performs everything.

### Scenario B

AI produces the assessment; human verifies and approves.

### Scenario C

AI produces and verifies; human authorizes.

### Scenario D

Automated deterministic verifier establishes compliance; governance board decides.

### Scenario E

Emergency execution bypasses normal authorization.

### Scenario F

A later observation contradicts the knowledge used for an earlier decision.

For each scenario we will run the complete model and ask:

$$
\boxed{
Does\ the\ architecture\ remain\ coherent?
}
$$

If it fails in any scenario, we do not patch the example—we revise the model.

That is the next genuine experiment.
