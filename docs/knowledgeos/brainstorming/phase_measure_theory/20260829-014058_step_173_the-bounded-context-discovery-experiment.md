# Step 173 — The Bounded-Context Discovery Experiment

We now move from the **epistemic lifecycle** to the actual **DDD structure**.

Our question is no longer:

> What concepts exist?

We already have a strong candidate set.

The question is:

> **Which concepts belong together, and where should the boundaries be?**

And we must be careful not to make the classic mistake:

$$
Concept \rightarrow Microservice
$$

That is backwards.

The correct direction is:

$$
\boxed{
DomainMeaning
\rightarrow
Invariants
\rightarrow
BoundedContext
\rightarrow
ArchitecturalBoundary
\rightarrow
DeploymentTechnology
}
$$

---

# 173.1 Our candidate domains

From Steps 170–172 we have identified:

$$
Observation
$$

$$
Evidence
$$

$$
Knowledge
$$

$$
Determination
$$

$$
Decision
$$

$$
Authorization
$$

$$
Execution
$$

$$
Outcome.
$$

At first sight, this looks like eight bounded contexts.

That would be premature.

We now try to **merge them**.

If two concepts can share:

* language;
* invariants;
* lifecycle;
* ownership;
* change pressure;

without creating semantic ambiguity, they may belong together.

---

# 173.2 The first candidate: Observation + Evidence

Could these be one context?

Potentially:

$$
Observation/Evidence.
$$

There is a natural relationship.

Observation is the raw captured fact.

Evidence is the qualified representation.

The lifecycle may be:

$$
Captured
\rightarrow
Qualified
\rightarrow
Validated
\rightarrow
Archived.
$$

This suggests a possible common boundary.

But there is a critical distinction:

$$
Observation
\neq
Evidence.
$$

An observation may exist without being accepted as evidence.

Therefore the context must preserve that distinction even if both live in the same bounded context.

### Preliminary verdict

$$
\boxed{
Observation + Evidence
\quad\text{can plausibly form one context.}
}
$$

But we do not freeze it yet.

---

# 173.3 Evidence + Knowledge

This is more difficult.

Both concern epistemic information.

But their invariants differ.

Evidence asks:

> What supports a claim?

Knowledge asks:

> What do we currently accept as sufficiently established?

Consider:

$$
E_1,E_2,E_3
$$

supporting:

$$
K_1.
$$

Then:

$$
K_1
$$

can be superseded while:

$$
E_1,E_2,E_3
$$

remain valid historical evidence.

Therefore:

$$
Lifecycle(E)\neq Lifecycle(K).
$$

This is a strong boundary signal.

### Preliminary verdict

$$
\boxed{
Evidence\ and\ Knowledge\ should\ remain\ semantically\ distinct.
}
$$

They may technically share infrastructure, but their conceptual boundary is significant.

---

# 173.4 Knowledge + Determination

This pair is more closely related.

Knowledge provides the basis.

Determination applies a method/rule.

For example:

$$
K:
"System contains unapproved dependency."
$$

Rule:

$$
ComplianceRule.
$$

Determination:

$$
"NonCompliant."
$$

The determination is dependent on:

$$
Knowledge
+
Rule
+
Context.
$$

Could both belong to one bounded context?

Possibly.

But they have different lifecycles.

Knowledge can exist for years.

A determination may be created for a specific assessment event.

Thus:

$$
PersistentKnowledge
\neq
AssessmentInstance.
$$

### Preliminary verdict

Keep them conceptually distinct, but investigate whether they form one broader **Epistemic/Assessment context**.

---

# 173.5 Determination + Decision

This is another dangerous merge.

Suppose:

$$
Determination=HighRisk.
$$

Possible decisions:

$$
Mitigate
$$

$$
AcceptRisk
$$

$$
Escalate.
$$

Therefore:

$$
Determination
\not\Rightarrow
Decision.
$$

The decision incorporates organizational objectives and authority.

So:

$$
EpistemicReasoning
\neq
GovernanceChoice.
$$

### Verdict

$$
\boxed{
Determination\ and\ Decision\ should\ not\ be\ collapsed.
}
$$

---

# 173.6 Decision + Authorization

This one is subtle.

A decision may be:

> Proceed with migration.

Authorization may be:

> The responsible role is permitted to execute the migration under defined scope.

In simple domains, one operation may perform both.

But semantically:

$$
Decision\neq Authorization.
$$

Decision asks:

> What shall we do?

Authorization asks:

> Who is permitted to do it?

Different questions.

### Verdict

$$
\boxed{
Separate\ semantic\ concepts.
}
$$

Whether they require separate bounded contexts remains open.

---

# 173.7 Authorization + Execution

Again:

$$
Authorization
\neq
Execution.
$$

Authorization is normative.

Execution is operational.

An authorized action can fail.

An unauthorized action can technically succeed.

Therefore:

$$
TechnicalSuccess
\not\Rightarrow
AuthorizedAction.
$$

### Verdict

Strong conceptual distinction.

---

# 173.8 Execution + Outcome

These are closely coupled operationally.

Execution asks:

> What did we attempt/do?

Outcome asks:

> What resulted?

Consider:

$$
Execution=SUCCESS
$$

but:

$$
Outcome=BUSINESS\_FAILURE.
$$

Therefore they cannot simply be represented as one status.

But they may naturally belong to one **Operational Execution context**.

### Preliminary verdict

$$
\boxed{
Execution + Outcome
\quad\text{are strong candidates for one operational context.}
}
$$

---

# 173.9 Outcome + Observation

This is interesting.

The outcome becomes an input into observation:

$$
Outcome
\rightarrow
Observation.
$$

But observation may also come from outside the execution process.

Therefore:

$$
Observation
$$

is broader than outcome.

We should not make:

$$
Observation=Outcome.
$$

Instead:

$$
Outcome
\subseteq
PotentialObservations.
$$

This is a useful relationship.

---

# 173.10 Candidate grouping

After the first merge experiment, we have something like:

### Context candidate A

$$
Observation + Evidence
$$

### Context candidate B

$$
Knowledge + Assessment/Determination
$$

### Context candidate C

$$
Decision + Governance
$$

### Context candidate D

$$
Authorization
$$

### Context candidate E

$$
Execution + Outcome.
$$

But now we need to test whether these boundaries actually hold.

---

# 173.11 Test 1 — Language

A bounded context should have coherent language.

For example, the word:

> "verified"

could mean:

* evidence verified;
* knowledge verified;
* architecture verified;
* deployment verified;
* authorization verified.

If the same word means materially different things, we should not assume one ubiquitous meaning.

This is classic DDD **bounded-context language**.

---

# 173.12 The word "knowledge"

Even "knowledge" itself may have different meanings.

In one context:

> established domain fact.

In another:

> engineering repository content.

In another:

> AI-generated candidate answer.

Therefore our architecture must define:

$$
Knowledge
$$

within a precise bounded-context meaning.

Otherwise KnowledgeOS itself risks becoming a semantic dumping ground.

---

# 173.13 This is a major danger

A system called **KnowledgeOS** can easily make the mistake:

> Everything that contains information is Knowledge.

That is wrong.

We have already established:

$$
Observation
\neq
Evidence
\neq
Knowledge
\neq
Decision.
$$

The name of the platform must not erase these distinctions.

---

# 173.14 Test 2 — Invariants

For each candidate boundary, ask:

> What must always remain true?

### Evidence

Possible invariant:

$$
Evidence
\Rightarrow
Provenance.
$$

### Knowledge

Possible invariant:

$$
EstablishedKnowledge
\Rightarrow
ValidBasis.
$$

### Determination

Possible invariant:

$$
Determination
\Rightarrow
ApplicableMethod.
$$

### Decision

Possible invariant:

$$
Decision
\Rightarrow
DecisionAuthority.
$$

### Authorization

Possible invariant:

$$
Authorization
\Rightarrow
ValidScopeAndActor.
$$

### Execution

Possible invariant:

$$
Execution
\Rightarrow
ExecutionContext.
$$

Different invariants are strong evidence for different models.

---

# 173.15 Test 3 — Lifecycle

Now compare lifecycle.

### Evidence

$$
Captured
\rightarrow
Qualified
\rightarrow
Validated
\rightarrow
Retained
$$

### Knowledge

$$
Candidate
\rightarrow
Supported
\rightarrow
Established
\rightarrow
Superseded/Invalidated
$$

### Decision

$$
Proposed
\rightarrow
Made
\rightarrow
Effective
\rightarrow
Closed
$$

### Authorization

$$
Requested
\rightarrow
Granted
\rightarrow
Active
\rightarrow
Expired/Revoked
$$

### Execution

$$
Requested
\rightarrow
Started
\rightarrow
Completed/Failed
$$

The lifecycles are clearly not identical.

---

# 173.16 Test 4 — Ownership

Now ask:

> Who owns the invariant?

Evidence may belong to an evidence/provenance owner.

Knowledge may belong to a domain knowledge owner.

Decision belongs to a governance authority.

Authorization belongs to an authorizing authority.

Execution belongs to an operational owner.

If ownership differs systematically, that is a strong bounded-context signal.

---

# 173.17 Test 5 — Change pressure

This is often overlooked.

Ask:

> What causes this model to change?

Evidence structures may change because:

* data sources change;
* collection mechanisms change;
* provenance requirements change.

Knowledge models may change because:

* domain concepts change;
* policies change;
* evidence changes.

Decision models may change because:

* governance changes;
* organizational responsibilities change.

Execution models may change because:

* technology changes;
* infrastructure changes.

Different change pressure strongly supports different boundaries.

---

# 173.18 Preliminary DDD score

We can make a qualitative matrix:

| Candidate               | Language |   Invariant | Lifecycle | Ownership | Change pressure |
| ----------------------- | -------: | ----------: | --------: | --------: | --------------: |
| Observation/Evidence    |     High |        High |      High |      High |            High |
| Evidence/Knowledge      |   Medium |        High |      High |      High |            High |
| Knowledge/Determination |   Medium | Medium/High |      High |    Medium |          Medium |
| Determination/Decision  |      Low |        High |      High |      High |            High |
| Decision/Authorization  |   Medium |        High |      High |      High |            High |
| Authorization/Execution |      Low |        High |      High |      High |            High |
| Execution/Outcome       |     High |        High |    Medium |    Medium |            High |

This is not a quantitative statistical score.

It is a **structured DDD discovery instrument**.

We should not pretend the numbers are objective measurements.

---

# 173.19 The important discovery

The strongest boundaries appear not between individual nouns but between **types of responsibility**.

We can see three broad zones:

$$
Epistemic
$$

$$
Governance
$$

$$
Operational.
$$

---

# 173.20 Zone 1 — Epistemic

$$
Observation
\rightarrow
Evidence
\rightarrow
Knowledge
\rightarrow
Determination.
$$

This zone answers:

> What do we know, and what follows from it?

---

# 173.21 Zone 2 — Governance

$$
Decision
\rightarrow
Authorization.
$$

This answers:

> What shall we do, and who is permitted to cause it?

---

# 173.22 Zone 3 — Operational

$$
Execution
\rightarrow
Outcome.
$$

This answers:

> What did we do, and what happened?

---

# 173.23 The three-zone architecture

We therefore have a powerful candidate model:

$$
\boxed{
Epistemic\ Domain
\rightarrow
Governance\ Domain
\rightarrow
Operational\ Domain
}
$$

with feedback:

$$
Operational
\rightarrow
Epistemic.
$$

So:

$$
\boxed{
Epistemic
\rightarrow
Governance
\rightarrow
Operational
\rightarrow
Epistemic.
}
$$

This is a much more meaningful architecture than eight independent services.

---

# 173.24 But we must not freeze three bounded contexts yet

Why?

Because a **domain zone** is not necessarily a bounded context.

We still need to investigate:

* subdomains;
* domain ownership;
* context maps;
* translation boundaries;
* consistency boundaries.

The three-zone model is currently a **strategic architectural hypothesis**.

---

# 173.25 Context map hypothesis

A first context map might therefore look like:

```text id="c9x2n1"
             ┌──────────────────────────┐
             │       EPISTEMIC          │
             │                          │
             │ Observation → Evidence   │
             │              ↓           │
             │           Knowledge      │
             │              ↓           │
             │         Determination    │
             └─────────────┬────────────┘
                           │
                    Determination
                           │
                           ▼
             ┌──────────────────────────┐
             │       GOVERNANCE         │
             │                          │
             │ Decision → Authorization │
             └─────────────┬────────────┘
                           │
                     Authorized
                           │
                           ▼
             ┌──────────────────────────┐
             │       OPERATIONAL        │
             │                          │
             │ Execution → Outcome      │
             └─────────────┬────────────┘
                           │
                        Outcome
                           │
                           ▼
                      Observation
```

This is our strongest architectural hypothesis so far.

---

# 173.26 Translation between contexts

DDD tells us something important here.

We should not simply pass the entire internal model of one context into another.

For example:

$$
Determination
$$

should not expose the entire internal Knowledge model to Governance.

Instead, Governance may receive a **translated domain representation**:

$$
DeterminationSummary.
$$

Likewise Operational may receive:

$$
AuthorizedAction.
$$

not the complete governance model.

---

# 173.27 Anti-corruption layers

This suggests potential translation boundaries:

$$
Epistemic
\xrightarrow{Translation}
Governance
$$

and:

$$
Governance
\xrightarrow{Translation}
Operational.
$$

These are candidates for anti-corruption layers or explicit integration contracts.

The exact implementation remains open.

---

# 173.28 Why this is important

Without translation, we could accidentally create:

$$
Governance
\rightarrow
KnowledgeDatabase.
$$

Then governance becomes coupled to epistemic internals.

Or:

$$
Execution
\rightarrow
DecisionDatabase.
$$

Then operations becomes coupled to governance persistence.

That would undermine bounded contexts.

---

# 173.29 The architecture should exchange meanings, not tables

A strong principle emerges:

$$
\boxed{
Bounded\ contexts\ should\ communicate\ through\ domain\ contracts,
not\ through\ accidental\ sharing\ of\ internal\ models.
}
$$

The contract may be:

* command;
* event;
* query;
* decision request;
* evidence package;
* authorization token.

But the semantics must be explicit.

---

# 173.30 Candidate integration contracts

We can identify:

### Epistemic → Governance

$$
DeterminationForDecision.
$$

### Governance → Operational

$$
AuthorizedAction.
$$

### Operational → Epistemic

$$
OutcomeObservation.
$$

These are not necessarily implementation events yet.

They are **semantic contracts**.

---

# 173.31 This creates a beautiful symmetry

Forward:

$$
Knowledge
\rightarrow
Determination
\rightarrow
Decision
\rightarrow
Authorization
\rightarrow
Execution.
$$

Backward:

$$
Execution
\rightarrow
Outcome
\rightarrow
Observation
\rightarrow
Evidence
\rightarrow
Knowledge.
$$

So the architecture contains:

$$
ForwardIntent
$$

and:

$$
BackwardEvidence.
$$

---

# 173.32 The architectural loop

We can express the system as:

$$
\boxed{
Knowledge
\overset{reasoning}{\longrightarrow}
Decision
\overset{authority}{\longrightarrow}
Action
\overset{observation}{\longrightarrow}
Knowledge'.
}
$$

The transformation:

$$
K\rightarrow K'
$$

is where learning, correction and evolution occur.

---

# 173.33 This is where AI naturally fits

AI can participate in several points:

$$
Observation
\rightarrow
Classification
$$

$$
Evidence
\rightarrow
Synthesis
$$

$$
Evidence
\rightarrow
Hypothesis
$$

$$
Knowledge
\rightarrow
Recommendation
$$

$$
Outcome
\rightarrow
AnomalyDetection.
$$

But AI does not become the bounded context.

AI is an **actor/capability crossing contexts**.

This is a very important distinction for our KnowledgeOS architecture.

---

# 173.34 AI is not the architecture

We should explicitly record:

$$
\boxed{
AI\ capability
\neq
Architecture.
}
$$

The architecture must remain meaningful if the AI model is replaced.

If replacing GPT/Claude/etc. destroys the domain model, the architecture is too tightly coupled to the technology.

---

# 173.35 This also validates our earlier Agent architecture

The agent harness can remain:

$$
Agent
\rightarrow
Tools
\rightarrow
KnowledgeOS.
$$

The agent should consume and produce governed artifacts.

It should not become the owner of the organization's knowledge model.

This preserves the symmetry we previously established between agent harnesses and KnowledgeOS.

---

# 173.36 Falsification attempt: one giant Knowledge context

Could everything be one bounded context called:

$$
KnowledgeOS.
$$

Yes.

Technically.

But then:

* Evidence;
* Knowledge;
* Decision;
* Authorization;
* Execution;

would share one model.

This creates enormous semantic coupling.

A change to authorization could potentially affect epistemic structures.

A governance policy change could contaminate evidence modeling.

Operational implementation changes could affect knowledge concepts.

That is strong evidence against a single context.

---

# 173.37 Falsification attempt: eight microservices

The opposite extreme is:

$$
8\ concepts
\rightarrow
8\ microservices.
$$

This is equally unjustified.

It would introduce distributed complexity before proving independent consistency boundaries.

Therefore both extremes are rejected:

$$
OneBigContext
$$

and:

$$
OneServicePerNoun.
$$

---

# 173.38 The likely architecture

Our current hypothesis is therefore:

$$
\boxed{
A\ small\ number\ of\ semantically\ coherent\ bounded\ contexts,
connected\ through\ explicit\ domain\ contracts.
}
$$

Not a noun-driven microservice architecture.

---

# 173.39 Step 173 verdict

The DDD experiment gives us our strongest architecture hypothesis so far:

### Strategic domains

$$
\boxed{
Epistemic
\rightarrow
Governance
\rightarrow
Operational
}
$$

### Feedback

$$
\boxed{
Operational
\rightarrow
Epistemic
}
$$

### Candidate semantic contracts

$$
DeterminationForDecision
$$

$$
AuthorizedAction
$$

$$
OutcomeObservation.
$$

### Strong conceptual invariants

$$
Evidence\neq Knowledge
$$

$$
Determination\neq Decision
$$

$$
Decision\neq Authorization
$$

$$
Authorization\neq Execution
$$

$$
Execution\neq Outcome.
$$

---

# Step 174 — Context Map and Domain-Contract Experiment

The next step should go one level deeper.

We should **simulate information exchange across the three zones**.

For each boundary we will ask:

1. What does the upstream context actually publish?
2. What does the downstream context actually need?
3. What must remain hidden?
4. Which model is authoritative?
5. What happens when the upstream knowledge changes later?
6. Can the downstream decision still be reconstructed?
7. What happens when the operational outcome contradicts the original determination?

This will let us determine whether our proposed context boundaries are truly **DDD boundaries** or merely attractive conceptual boxes.

Most importantly, we will test the **immutability/versioning question**:

$$
Determination_{v1}
\rightarrow
Decision_{v1}
$$

and later:

$$
Knowledge_{v2}
\neq
Knowledge_{v1}.
$$

We need to establish whether the governance context should see the new knowledge automatically, or whether the original decision must remain anchored to the historical determination.

That question will connect **DDD, statistics, temporal reasoning, governance, and the Chapter 4 insight** in one experiment.
