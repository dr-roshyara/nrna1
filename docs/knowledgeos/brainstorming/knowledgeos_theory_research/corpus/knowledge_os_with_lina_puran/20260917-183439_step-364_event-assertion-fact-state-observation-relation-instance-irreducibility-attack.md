# Step 364 — Event / Assertion / Fact / State / Observation / Relation-Instance Irreducibility Attack

We now continue the reduction program at the next foundational boundary.

The current candidate remains:

$$
\boxed{\mathfrak K_{\min}=(ID,\mathcal R^\star,\mathsf{Sem})}
$$

with:

$$
\boxed{\mathsf{Sem}(\rho)=(C_\rho,T_\rho,M_\rho)}.
$$

The question is:

$$
\boxed{
Event\stackrel{?}{=}
RelationInstance+\text{temporal/provenance/causal relations}
}
$$

This is important because KnowledgeOS uses history and event-driven reconstruction, but **event sourcing as an implementation pattern must not accidentally become an ontological primitive**.

---

# 364.1 First establish the distinctions

We must prevent the following collapse:

$$
\boxed{
Event\neq RelationInstance\neq Assertion\neq Observation\neq Fact\neq State.
}
$$

These objects can be related, but they perform different semantic roles.

A useful preliminary structure is:

$$
Reality
\rightarrow Observation
\rightarrow Representation
\rightarrow Assertion
\rightarrow Determination
\rightarrow Knowledge.
$$

Meanwhile:

$$
Event
$$

describes something that occurs in a temporal/history structure.

And:

$$
State
$$

describes a configuration at a point or interval.

---

# 364.2 What is an event?

We should avoid an overly metaphysical definition.

For the current architecture, an event can be treated provisionally as:

> an identity-bearing occurrence represented within a temporal/history structure.

Thus:

$$
e\in ID
$$

with relations such as:

$$
OccurredAt(e,t)
$$

$$
Before(e_1,e_2)
$$

$$
CausedBy(e_2,e_1).
$$

This definition is intentionally relational.

---

# 364.3 What is an assertion?

An assertion is not simply an event.

For example:

$$
Assert(A,p)
$$

is an assertion relation instance.

It may occur at:

$$
t_1.
$$

The event of making that assertion and the semantic content asserted are distinct:

$$
\boxed{
AssertionOccurrence\neq Proposition.
}
$$

This repeats Step 360.

---

# 364.4 What is an observation?

An observation is a relation between an observer/instrument/context and some observed phenomenon:

$$
Observed(o,a,x,c,t).
$$

It may produce information:

$$
Observation\rightarrow Information.
$$

But the observation itself is not necessarily a fact.

Thus:

$$
\boxed{
Observation\neq Fact.
}
$$

---

# 364.5 What is a fact?

The word "fact" is dangerous because it can mean several things.

Possible meanings include:

1. a proposition that is actually true;
2. an asserted proposition accepted under a contract;
3. a recorded data item;
4. an established determination.

KnowledgeOS should not choose among these silently.

Therefore `Fact` should remain a semantic type whose meaning is supplied by an epistemic/domain contract.

In particular:

$$
Recorded(p)\not\Rightarrow True(p).
$$

---

# 364.6 What is state?

A state is a configuration:

$$
K_t.
$$

For example:

$$
Balance=100.
$$

An event:

$$
Deposit(100)
$$

may produce:

$$
Balance:0\rightarrow100.
$$

Thus:

$$
\boxed{
Event\neq State.
}
$$

This distinction is already strongly supported.

---

# 364.7 What is a relation instance?

A relation type:

$$
\rho
$$

has an instance:

$$
r=(IID,\rho,args).
$$

For example:

$$
r_1=(i_1,Deposit,A,100).
$$

The question is:

> Can \(r_1\) itself be the event?

Sometimes yes.

But not always.

That distinction is the heart of this attack.

---

# 364.8 Relation instance can be event-bearing

Consider:

$$
Deposit(A,100).
$$

Its occurrence is naturally temporal:

$$
OccurredAt(r,t).
$$

So this relation instance can function as an event occurrence.

Therefore:

$$
Event
$$

does not require an object entirely separate from relation instances.

---

# 364.9 But every relation instance is not an event

Consider:

$$
MemberOf(A,Organization).
$$

This may describe a persistent relation.

It need not denote a discrete occurrence.

Similarly:

$$
HasType(A,Person).
$$

There is no necessity for these to be events.

Therefore:

$$
\boxed{
RelationInstance\not\Rightarrow Event.
}
$$

---

# 364.10 Eventhood as semantic typing

We can therefore define:

$$
Event(e)
$$

through a semantic contract:

$$
C_{Event}(e).
$$

For example:

$$
C_{Event}(e)
\Rightarrow
OccurredAt(e,t)
$$

for an appropriate temporal regime.

Thus eventhood can be a semantic type.

No primitive is immediately required.

---

# 364.11 Event identity

Suppose:

$$
Deposit(A,100)
$$

occurs twice:

$$
e_1,e_2.
$$

Then:

$$
ID(e_1)\neq ID(e_2).
$$

Even if:

$$
args(e_1)=args(e_2).
$$

Thus:

$$
\boxed{
PayloadEquality\neq EventIdentity.
}
$$

This is particularly important for event sourcing and distributed systems.

---

# 364.12 Same event payload, different occurrences

Consider:

$$
e_1=(Deposit,A,100,t_1)
$$

$$
e_2=(Deposit,A,100,t_2).
$$

They have identical semantic payload but distinct occurrence identity:

$$
e_1\not=e_2.
$$

Therefore an event ID is justified as a **relation-instance identity**, not necessarily as a new ontological primitive.

---

# 364.13 Same occurrence, different representations

Suppose:

$$
r_1
$$

is represented in JSON and:

$$
r_2
$$

in Avro.

We may have:

$$
r_1\equiv_{sem}r_2.
$$

Yet:

$$
Rep(r_1)\neq Rep(r_2).
$$

Thus:

$$
\boxed{
EventRepresentation\neq EventIdentity.
}
$$

---

# 364.14 Temporal position

An event may have:

$$
OccurredAt(e,t).
$$

But this relation is distinct from the event itself.

Therefore:

$$
EventIdentity
\neq
EventTime.
$$

Two identical event types can occur at different times.

---

# 364.15 Event order

Similarly:

$$
Before(e_1,e_2).
$$

The order relation is not the event.

Thus:

$$
\boxed{
Event\neq TemporalOrder.
}
$$

This supports the earlier temporal reduction:

$$
Time
$$

does not need to be a Kernel primitive.

---

# 364.16 Causal relation

Suppose:

$$
CausedBy(e_2,e_1).
$$

Again:

$$
e_1,e_2
$$

remain identities, while causality is a relation/semantic interpretation.

Therefore:

$$
\boxed{
Event\neq Cause.
}
$$

Causal semantics remain external.

---

# 364.17 Provenance

Suppose:

$$
SourceOf(s,e).
$$

The source is distinct from the event.

Thus:

$$
\boxed{
Event\neq Provenance.
}
$$

Provenance remains relational.

---

# 364.18 Event versus assertion

Consider:

$$
Assert(A,p)
$$

at:

$$
t_1.
$$

There are at least three things:

$$
p
$$

the content,

$$
a
$$

the assertion occurrence,

and:

$$
t_1
$$

the temporal context.

We should therefore model:

$$
a=(IID,Assert,A,p).
$$

Then:

$$
OccurredAt(a,t_1).
$$

This makes the assertion occurrence itself event-like.

But:

$$
p
$$

is not the event.

---

# 364.19 Retraction event

Suppose:

$$
Retracts(B,a_1)
$$

at:

$$
t_2.
$$

The retraction is itself an occurrence:

$$
r_2.
$$

We therefore have:

$$
a_1
$$

and:

$$
r_2.
$$

with:

$$
Retracts(r_2,a_1).
$$

This is an elegant demonstration that event identity can be represented using relation-instance identity.

---

# 364.20 Correction event

Similarly:

$$
Corrects(c_2,c_1).
$$

The correction occurrence is distinct from the corrected assertion.

Thus:

$$
Correction\neq CorrectedContent.
$$

Again no new primitive.

---

# 364.21 Supersession event

Suppose:

$$
Supersedes(v_2,v_1).
$$

The supersession occurrence can itself have:

$$
IID.
$$

Its semantics are captured by:

$$
T_{Supersedes}
$$

and:

$$
M_{Supersedes}.
$$

Therefore event identity remains relational.

---

# 364.22 Current state versus history

Now construct the classical counterexample.

History A:

$$
x=0
\xrightarrow{e_1}
1
\xrightarrow{e_2}
2.
$$

History B:

$$
x=0
\xrightarrow{e_3}
2.
$$

Both produce:

$$
K_{current}=\{x=2\}.
$$

But:

$$
H_A\neq H_B.
$$

Therefore:

$$
\boxed{
CurrentState\not\Rightarrow History.
}
$$

This does not mean `History` is primitive.

It means history must be preserved somewhere.

---

# 364.23 Can history be reconstructed from relation instances?

If:

$$
e_1,e_2,e_3
$$

have stable identities and temporal/causal relations, yes.

Define:

$$
H=\Pi_H(ID,\mathcal R^\star,\mathsf{Sem}).
$$

Then:

$$
Decode_H(H)\equiv_H H.
$$

This is exactly the result from Step 356.

---

# 364.24 Event sourcing reconsidered

Event sourcing stores events and derives state:

$$
K_t=Derive(H_{\le t}).
$$

But this is an **implementation strategy**.

KnowledgeOS should not conclude:

$$
EventSourcing\Rightarrow EventPrimitive.
$$

Instead:

$$
EventSourcing
=
\text{one representation of identity-bearing relation occurrences and history}.
$$

---

# 364.25 State sourcing

An alternative stores current state and reconstructs fewer historical distinctions.

This can be valid for domains that do not require complete history.

Thus:

$$
EventSourcing
$$

is not universally required.

The semantic requirement is:

$$
\boxed{
Preserve\ whatever\ historical\ distinctions\ the\ contract\ requires.
}
$$

---

# 364.26 Observation occurrence

An observation can similarly be represented:

$$
o=(IID,ObservedBy,a,x,c).
$$

Then:

$$
OccurredAt(o,t).
$$

Its provenance:

$$
SourceOf(s,o).
$$

Its interpretation:

$$
InterpretedAs(o,p).
$$

Thus:

$$
Observation
$$

is naturally an identity-bearing relation instance.

---

# 364.27 But observation ≠ information

The observation may produce a representation:

$$
Produces(o,i).
$$

Then:

$$
i
$$

can be interpreted as information.

Thus:

$$
\boxed{
Observation\neq Information.
}
$$

This protects the lifecycle.

---

# 364.28 Information ≠ evidence

An information artifact may become evidence only under a relevance/assessment contract:

$$
Supports(i,h).
$$

Thus:

$$
Information\not\Rightarrow Evidence.
$$

Again the distinction is relational/semantic.

---

# 364.29 Evidence ≠ determination

Evidence may support multiple hypotheses:

$$
Supports(e,h_1)
$$

$$
Supports(e,h_2).
$$

Determination requires an assessment regime:

$$
Det(E,Q,C,S).
$$

Therefore:

$$
Evidence\neq Determination.
$$

---

# 364.30 Determination ≠ knowledge

Even a determination may not automatically qualify as knowledge unless:

$$
\Gamma
$$

establishes the appropriate epistemic conditions.

Therefore:

$$
Determination\neq Knowledge.
$$

The lifecycle remains intact.

---

# 364.31 Fact as a semantic projection

We can define:

$$
Fact_\Gamma
=
\Pi_{Fact,\Gamma}(\mathfrak K_{\min},E_t).
$$

This is analogous to:

$$
Agent_\Gamma
=
\Pi_{Agent,\Gamma}(\mathfrak K_{\min}).
$$

Thus facthood is context/contract-relative.

---

# 364.32 State as a derived configuration

A state can be:

$$
K_t=Derive(H_{\le t},\Omega_v,EC_v,M_v).
$$

Therefore state is derived from history under a semantic regime.

Conversely:

$$
K_t\not\Rightarrow H_{\le t}.
$$

This asymmetry is fundamental.

---

# 364.33 Event identity versus state transition

A critical distinction:

$$
e
$$

is an occurrence.

$$
T(K,e,K')
$$

is its semantic effect.

Therefore:

$$
\boxed{
Event\neq Transition.
}
$$

This reinforces Step 362.

---

# 364.34 Event identity versus transition identity

Two events may have the same transition semantics:

$$
T(e_1)=T(e_2)
$$

but remain distinct:

$$
ID(e_1)\neq ID(e_2).
$$

For example, two separate deposits of €100.

Thus:

$$
\boxed{
BehavioralEquality\neq EventIdentity.
}
$$

---

# 364.35 Event replay

If:

$$
Apply(K,e)=K',
$$

then replaying:

$$
e
$$

should be governed by:

$$
T_e.
$$

Idempotency, if required:

$$
Apply(Apply(K,e),e)\equiv_KApply(K,e).
$$

But not every event is naturally idempotent.

Therefore:

$$
Idempotency
$$

is a contract property, not an event primitive.

---

# 364.36 Duplicate events

Suppose distributed nodes receive the same event:

$$
e.
$$

Stable:

$$
IID(e)
$$

allows duplicate detection.

Thus:

$$
Duplicate(e,e)
$$

can be derived from identity.

No special `DuplicateEvent` primitive is required.

---

# 364.37 Two distinct identical events

Now:

$$
e_1\neq e_2
$$

but:

$$
Payload(e_1)=Payload(e_2).
$$

They must not be deduplicated merely because their payloads are equal.

This yields:

$$
\boxed{
PayloadEquality\neq EventDeduplication.
}
$$

The event identity is essential for distributed correctness.

---

# 364.38 Concurrent events

Suppose:

$$
e_1,e_2
$$

are concurrent.

There may be:

$$
\neg(e_1\prec e_2)
$$

and:

$$
\neg(e_2\prec e_1).
$$

But absence of order must not automatically mean proven concurrency.

Thus:

$$
\boxed{
NoKnownOrder\neq ProvenConcurrency.
}
$$

This repeats the epistemic discipline.

---

# 364.39 Conflict between events

Suppose:

$$
e_1:
x=1
$$

and:

$$
e_2:
x=2
$$

under a regime where they conflict.

We preserve:

$$
Conflict(e_1,e_2).
$$

Arrival order must not silently select one.

This follows:

$$
Conflict\text{-Preservation}.
$$

---

# 364.40 Can an event be represented without time?

Yes.

An event identity can exist before temporal interpretation is available:

$$
ID(e).
$$

Later:

$$
OccurredAt(e,t)
$$

may be added.

Therefore:

$$
\boxed{
EventIdentity\neq Timestamp.
}
$$

This is important for incomplete data.

---

# 364.41 Can time exist without an event?

Yes.

A temporal interval:

$$
[t_1,t_2]
$$

can exist without a specific event.

Thus:

$$
Time\neq Event.
$$

---

# 364.42 Can an event have uncertain time?

Yes.

Instead of:

$$
OccurredAt(e,t),
$$

we can have:

$$
OccurredWithin(e,I)
$$

or:

$$
TimeDistribution(e,P_t).
$$

The latter belongs to an external probabilistic regime.

Therefore:

$$
TemporalUncertainty
$$

does not require an event primitive beyond identity and relations.

---

# 364.43 Can an event be instantaneous?

Depending on the temporal regime:

$$
Duration(e)=0.
$$

This is a semantic property.

No problem.

---

# 364.44 Can an event be an interval?

Some domains use "event" for an occurrence with duration:

$$
Start(e,t_1)
$$

$$
End(e,t_2).
$$

Again:

$$
Event
$$

is a semantic type over relation instances.

No universal temporal ontology is required.

---

# 364.45 The relation/event duality

We now encounter an important pattern.

A relation instance can be:

1. **atemporal structural relation**, or
2. **temporal occurrence/event**.

The difference is not necessarily its storage representation.

It is determined by:

$$
M_\rho
$$

and:

$$
C_\rho.
$$

For an event relation:

$$
C_\rho
$$

may require occurrence semantics.

Thus:

$$
\boxed{
Eventhood
\text{ can be a semantic interpretation of a relation instance.}
}
$$

This is stronger than merely saying events can be stored as relations.

---

# 364.46 Event reification

Suppose:

$$
Before(e_1,e_2).
$$

The event must be referable.

Therefore it needs an identity-bearing reference.

This supports reification:

$$
e\in ID.
$$

But reification is triggered by required relations, not by a universal event primitive.

Thus:

$$
\boxed{
EventReification\neq EventPrimitive.
}
$$

---

# 364.47 The RDF-like lesson, cautiously

Graph systems often reify statements so that statements themselves can become relation targets.

KnowledgeOS needs the same **capability**, but we should not import RDF semantics as an architectural foundation.

The relevant principle is simply:

> If a relation occurrence must itself participate in other relations, it requires an identity-bearing reference.

That is already part of:

$$
r=(IID,\rho,args).
$$

---

# 364.48 Assertion occurrence

This yields a clean structure:

$$
a=(IID_a,Assert,A,p).
$$

Then:

$$
OccurredAt(a,t).
$$

Then:

$$
Retracts(b,a).
$$

Then:

$$
Supersedes(c,a).
$$

Then:

$$
SourceOf(s,a).
$$

This demonstrates that assertion lifecycle is naturally supported without a separate `AssertionPrimitive`.

---

# 364.49 Observation occurrence

Similarly:

$$
o=(IID_o,Observe,A,x).
$$

Then:

$$
OccurredAt(o,t)
$$

$$
SourceOf(sensor,o)
$$

$$
InterpretedAs(o,p).
$$

Again the same substrate works.

---

# 364.50 Fact occurrence

A "fact" need not itself be an occurrence.

It may instead be:

$$
p
$$

plus an epistemic determination:

$$
EstablishedUnder(p,\Gamma).
$$

This suggests:

$$
Fact
$$

is not naturally equivalent to event.

---

# 364.51 State transition example

Let:

$$
K_0:
Balance=0.
$$

Event:

$$
e_1=Deposit(A,100).
$$

Transition:

$$
T(K_0,e_1,K_1)
$$

where:

$$
K_1:
Balance=100.
$$

The three objects are:

$$
e_1,\quad T,\quad K_1.
$$

They must not collapse.

---

# 364.52 Alternative interpretation

Suppose a bank's semantic contract interprets:

$$
Deposit(A,100)
$$

as pending rather than immediately applied.

Then:

$$
T_1(K,e,K_1)
$$

may differ from:

$$
T_2(K,e,K_2).
$$

The event identity remains the same.

Thus:

$$
\boxed{
EventIdentity\neq TransitionSemantics.
}
$$

---

# 364.53 Model versioning

With:

$$
M_1
$$

and:

$$
M_2,
$$

the same event:

$$
e
$$

may derive different states:

$$
Derive(H,M_1)\neq Derive(H,M_2).
$$

Therefore history remains stable while semantic interpretation changes.

This is one of the strongest reasons to keep:

$$
Event
$$

separate from:

$$
State.
$$

---

# 364.54 Historical truth

Suppose an event was recorded incorrectly.

We should preserve:

$$
Recorded(e).
$$

Later:

$$
Corrects(e_2,e).
$$

The existence of \(e\) in history is not erased.

Thus:

$$
\boxed{
HistoricalExistence\neq CurrentValidity.
}
$$

This is central to auditability.

---

# 364.55 Retraction does not erase event identity

If:

$$
Retracts(e_2,e_1),
$$

then:

$$
ID(e_1)
$$

remains meaningful.

Therefore:

$$
Retracted\neq Deleted.
$$

Again:

$$
History
$$

is preserved.

---

# 364.56 The strongest irreducibility attack

Now construct two candidate models:

### Model A

$$
e_1
$$

is a primitive Event.

### Model B

$$
r_1=(IID,\rho,args)
$$

is a relation instance with:

$$
OccurredAt(r_1,t).
$$

Can every required event observation distinguish them?

If:

$$
ID,\rho,args,OccurredAt,\Before,CausedBy,SourceOf,\ldots
$$

are identical, then:

$$
e_1\equiv_{sem}r_1.
$$

No event-specific semantic distinction remains.

Therefore the primitive is not justified.

---

# 364.57 Could there be an event distinction invisible in relations?

To establish irreducibility, we need:

$$
e_1,e_2
$$

with identical:

$$
ID+\mathcal R^\star+\mathsf{Sem}
$$

but some required event observation:

$$
O_E(e_1)\neq O_E(e_2).
$$

We have not found such a distinction.

Temporal occurrence, provenance, causal position, branch membership, replay identity and lifecycle are all representable relationally.

Thus the attack fails.

---

# 364.58 But an important qualification

The result is **not**:

> Events do not exist.

Nor:

> Event sourcing is unnecessary.

The result is:

$$
\boxed{
Event\text{ need not be an independent Kernel primitive.}
}
$$

Event remains a first-class semantic concept.

---

# 364.59 Formal event projection

Define:

$$
\boxed{
Event_\Gamma
=
\Pi_{Event,\Gamma}(\mathfrak K_{\min})
}
$$

where the projection selects identity-bearing relation instances satisfying the event contract.

Thus:

$$
e\in Event_\Gamma
$$

when:

$$
C_{Event,\Gamma}(e).
$$

---

# 364.60 History projection

Then:

$$
\boxed{
H
=
\Pi_H(\mathfrak K_{\min})
}
$$

where the history projection selects event-bearing relation instances plus relevant ordering, causal and revision relations.

This gives a clean two-step structure:

$$
Kernel
\rightarrow
EventView
\rightarrow
HistoryView.
$$

---

# 364.61 State derivation

Then:

$$
\boxed{
K_t
=
Derive(H_{\le t},\Omega_v,EC_v,M_v).
}
$$

Therefore:

$$
Kernel
\rightarrow
History
\rightarrow
State
$$

is a derivation path.

But:

$$
State
\not\Rightarrow
History.
$$

This asymmetry should be preserved.

---

# 364.62 Observation lifecycle

We can now represent the epistemic lifecycle more rigorously:

$$
ObservationOccurrence
$$

is an identity-bearing relation instance.

It can generate:

$$
InformationArtifact.
$$

An interpretation relation produces:

$$
InterpretedContent.
$$

An assertion relation can then state:

$$
Assert(a,p).
$$

Evidence assessment can establish:

$$
Supports(e,h).
$$

Determination then operates over hypotheses.

Thus the entire lifecycle can remain above the Kernel without adding an Event primitive.

---

# 364.63 Event versus fact

A useful formal distinction is:

$$
Event(e)
$$

concerns occurrence.

Whereas:

$$
Fact_\Gamma(p)
$$

concerns epistemic status.

Thus:

$$
\boxed{
Occurrence\neq EpistemicStatus.
}
$$

An event can be uncertain.

A fact can refer to a timeless mathematical proposition.

---

# 364.64 Event versus observation

An observation event may itself be uncertain or erroneous.

Therefore:

$$
Observed(e,x)
$$

does not establish:

$$
True(x).
$$

This preserves:

$$
Observation\neq Truth.
$$

---

# 364.65 Event versus reality

A recorded event:

$$
Recorded(e)
$$

does not guarantee the real-world occurrence:

$$
OccurredInReality(e).
$$

These are different semantic relations.

Thus:

$$
\boxed{
RecordedOccurrence\neq RealityOccurrence.
}
$$

This is a crucial epistemic boundary.

---

# 364.66 Event versus evidence

An event record may be evidence about an occurrence:

$$
EvidenceOf(record,e).
$$

But the record is not necessarily the event itself.

Therefore:

$$
\boxed{
Event\neq EvidenceOfEvent.
}
$$

---

# 364.67 Statistical perspective

In statistics, a sample outcome:

$$
X=x
$$

is distinct from:

$$
P(X=x).
$$

The observation is distinct from its probability model.

Likewise:

$$
Event
\neq
ProbabilityOfEvent.
$$

Probability remains external.

---

# 364.68 DDD perspective

An event should therefore not automatically become:

```text
EventAggregate
```

inside the Kernel.

Instead, depending on domain requirements:

```text
DomainEvent
```

can be a semantic projection over an identity-bearing relation occurrence.

A bounded context may choose to persist events explicitly, but that is an implementation/design decision justified by its history requirements.

---

# 364.69 Important DDD distinction

DDD's `Domain Event` often means:

> something that happened in the domain and is relevant to other parts of the system.

That is already a **domain-specific semantic concept**.

KnowledgeOS should not equate:

$$
DDDDomainEvent
$$

with:

$$
UniversalEventOntology.
$$

The latter has not been demonstrated.

---

# 364.70 Event contract

For an event relation \(\rho_E\):

$$
\Lambda_E=(C_E,T_E,M_E).
$$

Possible constraints:

$$
C_E:
IID(e)\text{ stable}.
$$

Transition:

$$
T_E:
Apply(e,K)\to K'.
$$

Meaning:

$$
M_E:
e\text{ denotes an occurrence of }\rho_E.
$$

This fits the canonical semantic contract basis exactly.

---

# 364.71 Event pairwise ablation

Can event semantics survive without \(C_E\)?

No: we lose requirements such as occurrence identity or admissible event structure.

Without \(T_E\)?

We lose the semantic effect of the event.

Without \(M_E\)?

We lose the distinction between a persistent relation and an occurrence.

Therefore:

$$
\boxed{
EventSemantics=(C_E,T_E,M_E).
}
$$

No fourth event-specific law layer appears.

---

# 364.72 Event identity lower bound

There is nevertheless a genuine lower bound:

$$
\boxed{
Every independently referable occurrence requires identity.
}
$$

But that identity is already supplied by:

$$
ID.
$$

Therefore the event attack strengthens rather than expands the Kernel.

---

# 364.73 Step 364 theorem candidate

### **Event Representation Theorem — Relative Form**

For the tested event families, an event can be represented as an identity-bearing relation instance together with semantic contracts and temporal/provenance/causal relations:

$$
\boxed{
e
\in
\Pi_{Event}
(ID,\mathcal R^\star,\mathsf{Sem}).
}
$$

Historical distinctions can be reconstructed through:

$$
\boxed{
H=\Pi_H(ID,\mathcal R^\star,\mathsf{Sem}).
}
$$

No independent `Event` primitive is required.

---

# 364.74 Step 364 verdict

## **PASS — Event / Relation-Instance Reduction**

The adversarial tests did **not** establish an irreducible Event primitive.

The strongest current conclusion is:

$$
\boxed{
Event\text{ is semantically essential but primitively reducible.}
}
$$

Specifically:

$$
\boxed{
Event
=
\text{typed/contract-governed relation occurrence}
}
$$

over:

$$
\boxed{
\mathfrak K_{\min}=(ID,\mathcal R^\star,\mathsf{Sem}).
}
$$

---

# 364.75 Important result for the whole ontology

We have now reduced a very large collection of apparently fundamental concepts:

$$
\begin{aligned}
Agent &\rightarrow \Pi_A(K)\\
Participant &\rightarrow \Pi_P(K)\\
Content &\rightarrow \Pi_C(K)\\
Proposition &\rightarrow \Pi_{Prop}(K)\\
Value &\rightarrow \text{typed argument/value domain}\\
Expression &\rightarrow \Pi_E(K)\\
History &\rightarrow \Pi_H(K)\\
Time &\rightarrow \Pi_T(K,\Gamma)\\
Context &\rightarrow \Pi_\Gamma(K)\\
Event &\rightarrow \Pi_{Event}(K).
\end{aligned}
$$

The Kernel candidate remains:

$$
\boxed{
\mathfrak K_{\min}=(ID,\mathcal R^\star,\mathsf{Sem}).
}
$$

This is becoming a meaningful lower-bound result rather than merely an architectural preference.

---

# 364.76 But a new issue has appeared

We have repeatedly used:

$$
r=(IID,\rho,args)
$$

where the relation instance can itself be:

* an event;
* an assertion;
* an observation;
* a proposition-bearing structure;
* a state-changing occurrence.

This raises a deeper question:

$$
\boxed{
\text{What is the ontological status of a relation instance itself?}
}
$$

Are we quietly assuming a hidden fourth primitive:

$$
RelationInstance?
$$

Perhaps:

$$
r
$$

is merely:

$$
ID+\text{typed relation}.
$$

But this must now be attacked explicitly.

---

# Step 365 — Relation-Instance Irreducibility Attack

The next attack should therefore test:

$$
\boxed{
RelationInstance
\stackrel{?}{=}
ID+\text{relation type}+\text{arguments}.
}
$$

We need to test whether an independently referable relation occurrence requires something beyond:

$$
IID,
$$

$$
\rho,
$$

$$
args,
$$

and:

$$
\mathsf{Sem}.
$$

The critical adversarial cases should include:

$$
\begin{aligned}
&\text{same relation, different occurrences}\\
&\text{relation about a relation}\\
&\text{reified assertion}\\
&\text{reified event}\\
&\text{relation identity under revision}\\
&\text{relation occurrence without explicit event semantics}\\
&\text{higher-order relations}\\
&\text{self-relations}\\
&\text{distributed duplicate detection}.
\end{aligned}
$$

The decisive question is:

$$
\boxed{
\text{Does }(IID,\rho,args)\text{ already completely account for relation-instance identity?}
}
$$

If yes, we may be able to sharpen the Kernel even further:

$$
\mathcal R^\star
$$

would not need a hidden `RelationInstance` ontology.

If no, this could reveal the first genuine missing representational layer beneath our current notation.

That is the correct next lower-bound test.
