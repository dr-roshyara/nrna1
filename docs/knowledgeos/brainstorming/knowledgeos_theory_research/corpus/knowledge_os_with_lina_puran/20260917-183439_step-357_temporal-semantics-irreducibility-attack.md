# Step 357 — Temporal Semantics Irreducibility Attack

We continue from Step 356.

The target is now:

$$
\boxed{
\text{Does Time need to be a Kernel primitive?}
}
$$

Our current minimal candidate is:

$$
\boxed{
\mathfrak K_{\min}=(ID,\mathcal R^\star,\mathsf{Sem})
}
$$

If temporal structure can be represented entirely through typed relations and semantic contracts, then adding a primitive `Time` would violate our reduction discipline.

The competing hypotheses are:

$$
H_0:\quad TemporalStructure
\preceq
(ID,\mathcal R^\star,\mathsf{Sem})
$$

versus:

$$
H_1:\quad
TemporalStructure
\not\preceq
(ID,\mathcal R^\star,\mathsf{Sem}).
$$

We need adversarial tests, not intuition.

---

## 357.1 What exactly is "time"?

We must first avoid a category error.

There are several distinct objects:

$$
Instant
$$

$$
Interval
$$

$$
Duration
$$

$$
Order
$$

$$
Occurrence
$$

$$
Validity
$$

$$
TemporalGranularity
$$

$$
TemporalUncertainty.
$$

They should not automatically be collapsed into one primitive called `Time`.

The attack therefore asks whether each can be represented relationally.

---

# 357.2 Instants

Suppose:

$$
t_1=2026\text{-}09\text{-}15T10:00.
$$

An instant is simply a typed value:

$$
v_t\in Val.
$$

We can relate an event:

$$
OccurredAt(e,t_1).
$$

Thus:

$$
e
\xrightarrow{OccurredAt}
t_1.
$$

No temporal primitive is required merely to represent the value.

Therefore:

$$
\boxed{
Instant\not\Rightarrow TemporalPrimitive.
}
$$

---

# 357.3 But a timestamp value is not temporal semantics

This distinction is important.

The value:

$$
10:00
$$

does not itself tell us:

$$
10:00<11:00.
$$

That requires interpretation of the value domain.

Thus:

$$
TemporalOrder
$$

belongs to:

$$
M_\rho
$$

or a semantic contract over the temporal value type.

Therefore:

$$
TimestampValue
\neq
TemporalSemantics.
$$

---

# 357.4 Before relation

Define:

$$
Before(x,y).
$$

Its contract may impose:

$$
Before(x,x)=False
$$

and:

$$
Before(x,y)\land Before(y,z)
\Rightarrow
Before(x,z)
$$

for a strict-order interpretation.

Thus temporal ordering is simply a typed relation with laws.

This is a direct fit with:

$$
\mathcal R^\star.
$$

Therefore:

$$
\boxed{
TemporalOrder
\text{ is relationally representable.}
}
$$

---

# 357.5 But not every temporal relation is a total order

Consider:

$$
e_1,e_2
$$

occurring concurrently.

Then:

$$
e_1\nprec e_2
$$

and:

$$
e_2\nprec e_1.
$$

A partial order is sufficient.

Therefore KnowledgeOS should not impose:

$$
TotalOrder.
$$

Instead:

$$
\prec_\tau
$$

is contract-dependent.

This is consistent with the general rule:

$$
\boxed{
Structure\ is\ declared\ by\ contract,\ not\ assumed\ universally.
}
$$

---

# 357.6 Simultaneity

Can we represent:

$$
Simultaneous(x,y)?
$$

Yes, as a relation:

$$
Simultaneous(x,y).
$$

Alternatively:

$$
OccurredAt(x,t)
\land
OccurredAt(y,t).
$$

But these are not necessarily semantically equivalent.

The first may mean explicit simultaneity.

The second means shared recorded timestamp.

Thus:

$$
SameTimestamp
\neq
ProvenSimultaneity.
$$

This is another excellent example of why meaning belongs in:

$$
M.
$$

---

# 357.7 Duration

Suppose:

$$
Duration(e)=5min.
$$

We can represent:

$$
StartsAt(e,t_1)
$$

$$
EndsAt(e,t_2).
$$

Then under an external metric:

$$
Duration(e)=t_2-t_1.
$$

Thus duration can be derived from temporal values plus a temporal metric.

Therefore:

$$
\boxed{
Duration
\text{ need not be a Kernel primitive.}
}
$$

But note:

$$
Duration
$$

requires a mathematical regime if actual subtraction/measurement is desired.

That regime remains external.

---

# 357.8 Temporal intervals

Represent an interval:

$$
I=[t_1,t_2].
$$

We can encode:

$$
StartsAt(I,t_1)
$$

$$
EndsAt(I,t_2).
$$

The interval itself can have:

$$
ID_I.
$$

Therefore:

$$
Interval
$$

is an identity-bearing object connected by typed relations.

No new primitive.

---

# 357.9 Interval relations

Relations such as:

$$
Overlaps(I_1,I_2)
$$

$$
Contains(I_1,I_2)
$$

$$
Meets(I_1,I_2)
$$

can all be represented as typed relations.

Their logical laws belong to:

$$
C_\rho.
$$

Their interpretation belongs to:

$$
M_\rho.
$$

Their changes belong to:

$$
T_\rho.
$$

Thus:

$$
\boxed{
IntervalAlgebra
\subseteq
RelationContractSemantics.
}
$$

---

# 357.10 Temporal validity

Suppose an assertion is valid between:

$$
[t_1,t_2).
$$

Represent:

$$
ValidFrom(r,t_1)
$$

$$
ValidUntil(r,t_2).
$$

Then:

$$
Valid(r,t)
$$

can be derived:

$$
Valid(r,t)
\iff
t_1\le t<t_2.
$$

The inequality belongs to the temporal mathematical regime.

The relation structure itself remains Kernel-compatible.

Therefore:

$$
\boxed{
TemporalValidity
\text{ is derivable.}
}
$$

---

# 357.11 Occurrence time versus validity time

This distinction must be preserved.

An event may occur at:

$$
t_o
$$

while its assertion becomes valid at:

$$
t_v.
$$

Therefore:

$$
OccurredAt(e,t_o)
$$

and:

$$
ValidFrom(r,t_v)
$$

are different relations.

Hence:

$$
\boxed{
EventTime\neq ValidityTime.
}
$$

This is exactly the kind of semantic distinction a simplistic temporal primitive would tend to collapse.

---

# 357.12 Knowledge time

An agent can acquire knowledge at:

$$
t_k.
$$

The underlying event may have happened at:

$$
t_e<t_k.
$$

Thus:

$$
OccurredAt(e,t_e)
$$

and:

$$
AcquiredAt(K,t_k).
$$

Therefore:

$$
\boxed{
WorldTime\neq EpistemicAcquisitionTime.
}
$$

Again, relation semantics preserve the distinction.

---

# 357.13 Decision time

Similarly:

$$
DecisionAt(d,t_d)
$$

may follow:

$$
KnowledgeAcquiredAt(k,t_k)
$$

and:

$$
EvidenceAvailableAt(e,t_e).
$$

We therefore have a temporal graph:

$$
t_e
\rightarrow
t_k
\rightarrow
t_d.
$$

No temporal primitive has emerged.

---

# 357.14 Temporal causality

Suppose:

$$
CausedBy(e_2,e_1).
$$

Causality often implies a temporal condition:

$$
e_1\prec e_2.
$$

But:

$$
Before
$$

does not imply:

$$
Causes.
$$

Thus:

$$
\boxed{
TemporalOrder\neq Causality.
}
$$

Causal semantics remain external unless explicitly represented as a relation.

---

# 357.15 Dense time

Now attack a harder case.

Suppose:

$$
t\in\mathbb R.
$$

There are infinitely many instants between any two distinct instants.

Can the Kernel represent this?

Yes, if temporal values are represented by a mathematical value domain:

$$
Val_{\mathbb R}.
$$

Relations can reference them:

$$
OccurredAt(e,t).
$$

The real-number structure belongs to the mathematical regime.

Therefore:

$$
\boxed{
DenseTime
\text{ does not require a temporal Kernel primitive.}
}
$$

---

# 357.16 Discrete time

Likewise:

$$
t\in\mathbb Z.
$$

Or:

$$
t\in\mathbb N.
$$

Same representation:

$$
OccurredAt(e,t).
$$

Only the external value-domain semantics change.

Therefore:

$$
DiscreteTime
$$

and:

$$
DenseTime
$$

can share the same Kernel relation structure.

This is powerful evidence for representation independence.

---

# 357.17 Continuous processes

Suppose:

$$
x:\mathbb R\to X.
$$

The entire trajectory cannot be explicitly enumerated.

But it can be represented intensionally by a semantic dependency:

$$
Trajectory(x,f)
$$

where:

$$
f:\mathbb R\to X.
$$

The mathematical function belongs to an external regime.

Therefore:

$$
\boxed{
Continuity
\text{ is not a Kernel primitive.}
}
$$

This mirrors our cardinality-neutrality principle.

---

# 357.18 Temporal uncertainty

Suppose event time is uncertain:

$$
T_e\sim P.
$$

We could represent:

$$
TimeDistribution(e,P).
$$

Probability:

$$
P
$$

belongs to an external probabilistic regime.

Thus:

$$
UncertainTime
$$

does not require a new Kernel primitive.

We simply have:

$$
Relation(e,P)
$$

with probabilistic interpretation.

---

# 357.19 Temporal interval uncertainty

Suppose:

$$
t_e\in[t_1,t_2].
$$

Represent:

$$
OccursWithin(e,[t_1,t_2]).
$$

Or:

$$
LowerBound(e,t_1)
$$

$$
UpperBound(e,t_2).
$$

Again relational.

---

# 357.20 Temporal granularity

Consider:

* year;
* month;
* day;
* hour;
* millisecond.

Granularity can be represented as a typed semantic value:

$$
Granularity(day).
$$

Then:

$$
OccurredAt(e,t,g).
$$

No primitive needed.

More importantly:

$$
2026\text{-}09
$$

does not necessarily identify a precise instant.

Therefore:

$$
TemporalPrecision
\neq
TemporalValue.
$$

---

# 357.21 Approximate timestamps

Suppose:

$$
t\approx10:00.
$$

We can represent:

$$
ApproxTime(e,t,\epsilon).
$$

The semantics of:

$$
\epsilon
$$

are external.

Again:

$$
Approximation
$$

is a semantic relation, not a primitive.

---

# 357.22 Cyclic time

Consider:

$$
Monday\rightarrow Tuesday\rightarrow\cdots\rightarrow Sunday\rightarrow Monday.
$$

A cyclic temporal structure can be represented by relations:

$$
Next(d_1,d_2).
$$

Its topology/algebra belongs to an external regime.

Therefore:

$$
CyclicTime
$$

does not force a new primitive.

---

# 357.23 Relative time

Suppose:

$$
Before(e_1,e_2)
$$

without knowing exact timestamps.

This is especially important.

KnowledgeOS can represent:

$$
e_1\prec e_2
$$

without requiring:

$$
t(e_1),t(e_2).
$$

Therefore:

$$
\boxed{
TemporalRelation
\text{ can exist without absolute timestamps.}
}
$$

This is strong evidence that:

$$
TimeValue
$$

and:

$$
TemporalOrder
$$

should not be conflated.

---

# 357.24 Can absolute time be reconstructed from relative order?

No.

Consider:

$$
e_1\prec e_2.
$$

This does not determine:

$$
t(e_1)=10:00,
\quad
t(e_2)=11:00.
$$

Many embeddings exist.

Thus:

$$
RelativeOrder
\not\Rightarrow
AbsoluteTime.
$$

This is a non-reconstructibility result—but it does **not** imply that both must be Kernel primitives.

It means they are different semantic relations/value structures.

---

# 357.25 Conversely, timestamps can induce order

Given a suitable total-order value domain:

$$
t_1<t_2
$$

can induce:

$$
Before(e_1,e_2).
$$

But this depends on interpretation.

Therefore:

$$
AbsoluteTime
\Rightarrow
TemporalOrder
$$

only under an appropriate semantic regime.

---

# 357.26 Historical ordering versus physical time

This is crucial for distributed KnowledgeOS.

Suppose:

$$
e_1
$$

has timestamp:

$$
10:01
$$

and:

$$
e_2
$$

has timestamp:

$$
10:00.
$$

It does not necessarily follow that:

$$
e_2
$$

caused:

$$
e_1.
$$

Clock skew may exist.

Thus:

$$
TimestampOrder
\neq
CausalOrder.
$$

KnowledgeOS should preserve both if available.

---

# 357.27 Lamport-style logical time

A logical clock can represent:

$$
L(e_1)<L(e_2).
$$

This is a causal-order mechanism.

But again it is an implementation/algorithmic strategy.

The semantic relation remains:

$$
Before(e_1,e_2)
$$

or:

$$
CausallyBefore(e_1,e_2).
$$

Therefore:

$$
\boxed{
LogicalClock
\neq
KernelPrimitive.
}
$$

---

# 357.28 Vector-clock information

A vector clock:

$$
V(e)
$$

can encode causal knowledge.

But the semantic information is still relational:

$$
e_1\prec e_2.
$$

Vector clocks are one representation.

Therefore:

$$
\boxed{
VectorClock
\text{ is a representation mechanism, not ontology.}
}
$$

---

# 357.29 Temporal deletion

Suppose:

$$
ValidUntil(r,t_2).
$$

At:

$$
t>t_2,
$$

the assertion may be expired.

But:

$$
Expired(r)
\not\Rightarrow
False(r).
$$

Thus:

$$
\boxed{
Expiration\neq Refutation.
}
$$

This reinforces our status factorization.

---

# 357.30 Temporal supersession

Suppose:

$$
r_2
$$

supersedes:

$$
r_1
$$

at:

$$
t_2.
$$

The old relation remains historically true as an occurrence:

$$
Supersedes(r_2,r_1).
$$

Therefore:

$$
HistoricalExistence
$$

and:

$$
CurrentValidity
$$

are distinct projections.

Again no temporal primitive is required.

---

# 357.31 The strongest temporal counterexample

We should now attempt to force a temporal primitive by constructing a structure that cannot be represented through relations.

Consider an arbitrary temporal structure:

$$
\mathcal T=(X,\prec,V)
$$

where:

* \(X\) = temporal entities;
* \(\prec\) = temporal order;
* \(V\) = temporal valuation.

Encode:

$$
X
$$

as identity-bearing entities.

Encode:

$$
\prec
$$

as a typed relation.

Encode:

$$
V
$$

as typed values/relations.

Then:

$$
\mathcal T
\hookrightarrow
(ID,\mathcal R^\star,\mathsf{Sem}).
$$

Thus the attempted counterexample fails.

---

# 357.32 What about temporal topology?

Suppose we need:

$$
Topology(X,\tau).
$$

Open sets:

$$
U\in\tau.
$$

Can this be represented?

Yes, potentially:

$$
OpenSet(U)
$$

and:

$$
Contains(U,x).
$$

The topology's axioms belong to:

$$
C_\rho
$$

or an external topological regime.

Therefore:

$$
Topology
$$

does not force a Kernel primitive.

This is consistent with the earlier rejection of:

$$
\mathcal K=\text{complete metric space}.
$$

---

# 357.33 What about metric time?

Suppose:

$$
d(t_1,t_2)=|t_1-t_2|.
$$

The metric:

$$
d
$$

is an external mathematical structure over temporal values.

The Kernel needs only represent:

$$
Distance(t_1,t_2,d)
$$

or reference a metric model.

Thus:

$$
\boxed{
MetricTime
\text{ is an external mathematical regime.}
}
$$

---

# 357.34 What about temporal probability?

Suppose:

$$
P(T_e\in A).
$$

This requires a probability measure.

The temporal event itself remains:

$$
OccursAt(e,T_e).
$$

The probability model is external:

$$
P.
$$

Therefore:

$$
TemporalProbability
\neq
TemporalKernel.
$$

---

# 357.35 Temporal semantics and \(M\)

We now have a useful decomposition.

For:

$$
Before
$$

the semantic contract may be:

$$
\Lambda_{Before}
=
(C_{Before},T_{Before},M_{Before}).
$$

For:

$$
OccurredAt
$$

we have:

$$
\Lambda_{Occ}
=
(C_{Occ},T_{Occ},M_{Occ}).
$$

For:

$$
ValidFrom
$$

we have:

$$
\Lambda_{Valid}
=
(C_{Valid},T_{Valid},M_{Valid}).
$$

The temporal meaning is therefore not stored in a global `Time` primitive.

It is attached to typed relation semantics.

---

# 357.36 Temporal structure as a sublanguage

This suggests:

$$
\mathcal R_\tau
\subseteq
\mathcal R^\star
$$

where:

$$
\mathcal R_\tau=
\{
Before,
After,
OccurredAt,
StartsAt,
EndsAt,
ValidFrom,
ValidUntil,
Overlaps,
Simultaneous
,\ldots
\}.
$$

Then:

$$
\mathsf{Sem}_\tau
$$

defines their contracts.

Thus temporal semantics form a **domain-specific semantic sublanguage** of the Kernel.

This is cleaner than adding:

$$
Time
$$

to the Kernel tuple.

---

# 357.37 Important DDD implication

Do not create:

```text
TimeBoundedContext
```

merely because temporal semantics are important.

Temporal semantics may cut across many domains:

* Membership;
* Voting;
* Governance;
* Evidence;
* Authorization;
* Decision.

This is a cross-cutting semantic capability.

DDD should therefore distinguish:

$$
CrossCuttingSemanticCapability
$$

from:

$$
BoundedContext.
$$

---

# 357.38 Temporal policy belongs elsewhere

For example:

> A vote is valid for 20 minutes.

That is not generic temporal semantics.

It is:

$$
VotingPolicy
$$

using:

$$
TemporalRelations.
$$

Therefore:

$$
20min
$$

should not become a Kernel constant.

It belongs to the relevant domain contract.

---

# 357.39 Temporal identity attack

Could two temporal values be semantically identical while represented differently?

For example:

$$
2026-09-15T10:00+02:00
$$

and:

$$
2026-09-15T08:00Z.
$$

Under an absolute-instant interpretation:

$$
t_1\equiv_{sem}t_2.
$$

Thus:

$$
RepresentationEquality
\neq
TemporalIdentity.
$$

This is another example supporting semantic identity as contract-relative.

---

# 357.40 Time zones

A time-zone identifier:

$$
Europe/Berlin
$$

is not itself an instant.

Therefore:

$$
LocalDateTime
$$

requires:

$$
TimeZone
$$

to be interpreted as an instant.

These can be represented as typed values and relations.

No Kernel primitive is required.

---

# 357.41 Calendar semantics

Gregorian, Julian, ISO week-date, lunar calendars, etc. may map values differently.

Thus:

$$
Calendar
$$

is an external semantic regime.

KnowledgeOS should not encode one calendar as universal truth.

This is an important architectural boundary.

---

# 357.42 Temporal ontology versus temporal mathematics

We now have a clean distinction:

### Kernel

Represents:

$$
ID
$$

and:

$$
TemporalRelation.
$$

### Mathematical regime

Provides:

$$
Order,\ Metric,\ Topology,\ Probability,\ Continuity,\ Calendar.
$$

### Domain contract

Specifies:

> which temporal semantics matter for this relation.

This is exactly our intended architecture:

$$
\boxed{
OntologicalCore
\rightarrow
RelationalSemantics
\rightarrow
MathematicalRegime.
}
$$

---

# 357.43 Attempted fourth primitive

The obvious candidate would be:

$$
Time.
$$

But every tested requirement reduces to:

$$
ID+\mathcal R^\star+\mathsf{Sem}
$$

plus external value/math regimes.

Thus:

$$
\boxed{
Time\text{ fails the non-reconstructibility test.}
}
$$

---

# 357.44 But there is a subtle caveat

We must not conclude:

> "Time does not exist."

The correct conclusion is:

$$
\boxed{
Time\text{ is not demonstrated to be an irreducible Kernel primitive.}
}
$$

Temporal structures remain extremely important.

They are simply represented rather than primitive.

---

# 357.45 Formal reduction theorem candidate

### \(T_{357}\) — Temporal Representation Reduction

For the tested temporal structure family:

$$
\mathcal T^\dagger=
\{
Instant,
Interval,
Order,
Occurrence,
Duration,
Validity,
Granularity,
Concurrency,
TemporalUncertainty,
Causality
\},
$$

there exists a representation:

$$
\boxed{
\mathcal T
\hookrightarrow
(ID,\mathcal R^\star,\mathsf{Sem})
}
$$

such that the required temporal observations are reconstructible up to declared semantic equivalence.

No independent temporal Kernel primitive is required.

---

# 357.46 Observation family

Define:

$$
\mathcal O_T=
\{
O_{instant},
O_{interval},
O_{order},
O_{occurrence},
O_{duration},
O_{validity},
O_{granularity},
O_{concurrency},
O_{uncertainty},
O_{causal}
\}.
$$

The reduction criterion is:

$$
\forall O\in\mathcal O_T:
O(\mathcal T)
=
O(\Pi_T(ID,\mathcal R^\star,\mathsf{Sem})).
$$

This is our temporal analogue of the history reconstruction criterion.

---

# 357.47 Result matrix

| Temporal capability      | Representation                  | Status |
| ------------------------ | ------------------------------- | ------ |
| Instant                  | typed value + relation          | PASS   |
| Interval                 | identity + endpoint relations   | PASS   |
| Before/After             | typed relation                  | PASS   |
| Occurrence               | `OccurredAt`                    | PASS   |
| Duration                 | endpoints + external metric     | PASS   |
| Validity                 | temporal relations              | PASS   |
| Granularity              | typed value/contract            | PASS   |
| Simultaneity             | relation / shared value         | PASS   |
| Dense time               | external value domain           | PASS   |
| Discrete time            | external value domain           | PASS   |
| Cyclic time              | relation + external algebra     | PASS   |
| Temporal uncertainty     | relation + probability regime   | PASS   |
| Causal order             | typed relation                  | PASS   |
| Distributed logical time | relation + implementation model | PASS   |
| Temporal topology        | relation + external topology    | PASS   |
| Temporal metric          | relation + external metric      | PASS   |

No separating counterexample has produced a new primitive.

---

# 357.48 DDD result

Temporal semantics should therefore be modeled approximately as:

```text
Semantic Contract
    │
    ├── temporal relation types
    │      ├── Before
    │      ├── OccurredAt
    │      ├── StartsAt
    │      ├── EndsAt
    │      ├── ValidFrom
    │      └── ValidUntil
    │
    └── temporal semantic laws
```

rather than:

```text
KnowledgeOS Kernel
    ├── Identity
    ├── Relations
    ├── Semantics
    └── Time   ← NOT justified
```

---

# 357.49 Important methodological result

Steps 356 and 357 together reveal a broader pattern.

We have reduced:

$$
History
$$

and:

$$
Time
$$

without eliminating their semantic importance.

They become **reconstructible projections over the relational substrate**.

This suggests:

$$
\boxed{
Many apparently fundamental dimensions may be projections rather than primitives.
}
$$

But we must continue testing rather than generalize prematurely.

---

# 357.50 Current Kernel status

The strongest current candidate remains:

$$
\boxed{
\mathfrak K_{\min}
=
(ID,\mathcal R^\star,\mathsf{Sem})
}
$$

with:

$$
\boxed{
\mathsf{Sem}(\rho)
=
(C_\rho,T_\rho,M_\rho).
}
$$

History:

$$
H=\Pi_H(\mathfrak K_{\min})
$$

Temporal structure:

$$
\mathcal T=\Pi_T(\mathfrak K_{\min}).
$$

Conflict:

$$
X=\Pi_X(\mathfrak K_{\min}).
$$

Provenance:

$$
P=\Pi_P(\mathfrak K_{\min}).
$$

Access:

$$
A=\Pi_A(\mathfrak K_{\min}).
$$

These are projections/capabilities, not yet proven primitives.

---

# 357.51 Step 357 verdict

## **PASS — Temporal Semantics Reduction**

The adversarial temporal attack did **not** demonstrate an irreducible `Time` primitive.

The current evidence supports:

$$
\boxed{
TemporalStructure
\preceq
(ID,\mathcal R^\star,\mathsf{Sem})
}
$$

for the tested temporal family.

The correct architectural statement is:

> **KnowledgeOS does not need a universal temporal primitive; it needs the ability to represent typed temporal relations and interpret them under explicit temporal regimes.**

And importantly:

$$
\boxed{
TemporalSemantics\neq TemporalMathematics.
}
$$

The Kernel can represent temporal structure without committing to:

* discrete time;
* continuous time;
* real-valued time;
* one calendar;
* one clock;
* one metric;
* probability;
* causality.

---

# Step 358 — Next decisive reduction

We have now reduced:

$$
History
$$

and:

$$
Time.
$$

The next candidate that appears fundamental is:

$$
\boxed{Context}
$$

because almost every KnowledgeOS definition currently depends on:

$$
C_t,\Gamma,Q,EC.
$$

The critical question is:

$$
\boxed{
\textbf{Is Context an irreducible Kernel primitive, or can Context also be represented as typed relations and semantic dependencies?}
}
$$

This is potentially more important than the Time attack.

We need to test:

* participant context;
* inquiry context;
* temporal context;
* epistemic context;
* governance context;
* model context;
* jurisdiction;
* observation context;
* semantic regime;
* version context;
* scope;
* local versus global context;
* context inheritance;
* nested contexts;
* context conflicts;
* context switching.

The key ablation is:

$$
\boxed{
Context
\stackrel{?}{\cong}
TypedRelations(ID,\mathcal R^\star,\mathsf{Sem})
}
$$

versus a genuine non-reconstructibility counterexample.

If Context also reduces, the Kernel becomes even more strikingly small:

$$
\boxed{
\mathfrak K_{\min}=(ID,\mathcal R^\star,\mathsf{Sem})
}
$$

with much of what traditionally appears as "metadata" becoming explicit semantic structure rather than independent ontology.
