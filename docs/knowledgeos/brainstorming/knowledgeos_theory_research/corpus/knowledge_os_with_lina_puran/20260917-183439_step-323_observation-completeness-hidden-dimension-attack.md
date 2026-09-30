# Step 323 — Observation Completeness and the Hidden-Dimension Attack

We now attack the weakest point in the current theory.

We have:

$$
\mathfrak K_{\min}
=
(ID,\mathcal R^\star,\mathsf{Sem})
$$

and a provisional observation family:

$$
\mathcal O_K=
\{O_I,O_R,O_M,O_T,O_P,O_C,O_H,O_X,O_A,O_D\}.
$$

We defined:

$$
R_1\equiv_KR_2
\iff
O_K(R_1)=O_K(R_2).
$$

The danger is obvious:

> If \(\mathcal O_K\) omits a genuine semantic dimension, two genuinely different Kernel states could incorrectly be declared equivalent.

So this step is a deliberate **adversarial completeness test**.

---

# 323.1 The correct criterion

We need to search for:

$$
R_1,R_2
$$

such that:

$$
O_K(R_1)=O_K(R_2)
$$

but:

$$
R_1\not\equiv_{sem}R_2.
$$

If such a pair exists, then:

$$
\boxed{
\mathcal O_K\text{ is incomplete}.
}
$$

The missing distinction must then be identified.

This is preferable to simply adding concepts based on intuition.

---

# 323.2 Attack 1 — Relation arity

Consider:

$$
r_1=Knows(A,P)
$$

and:

$$
r_2=Knows(A,P,C).
$$

If the context \(C\) is semantically relevant, then a binary relation and a ternary relation cannot be treated as equivalent.

Our existing observation:

$$
O_R
$$

must therefore include **argument roles and arity**, not merely a relation name.

Hence:

$$
O_R(r)=
(\rho,\operatorname{Signature}_\rho,args_{\rho}).
$$

This resolves the potential hidden dimension.

### Result

$$
\boxed{\text{PASS}}
$$

provided relation signatures are semantic, not merely syntactic.

---

# 323.3 Attack 2 — Argument role permutation

Consider:

$$
Transfers(A,B,x)
$$

versus:

$$
Transfers(B,A,x).
$$

If the relation is directional, then:

$$
A\neq B
$$

cannot be treated as a simple unordered argument set.

Therefore the semantic observation must preserve:

$$
Role_\rho(arg_i).
$$

For example:

$$
Sender=A,\quad Receiver=B.
$$

Thus:

$$
O_R
$$

must preserve **role-labelled arguments**.

### Result

$$
\boxed{\text{PASS}}
$$

with the refined relation signature.

---

# 323.4 Attack 3 — Higher-order relations

Consider:

$$
Retracts(r_2,r_1).
$$

Here a relation instance is itself an argument of another relation.

Could this create a new primitive?

No.

We already have:

$$
args_\rho
$$

whose domain can include relation identities.

Therefore:

$$
Relation\rightarrow Relation
$$

is representable.

No new observation dimension is required beyond typed arguments and identity.

### Result

$$
\boxed{\text{PASS}}
$$

---

# 323.5 Attack 4 — Relation-instance versus proposition identity

Consider:

$$
r_1=Knows(A,P)
$$

and:

$$
r_2=Knows(A,P).
$$

Suppose they are two independent occurrences.

Then:

$$
SID(r_1)=SID(r_2)
$$

but:

$$
IID(r_1)\neq IID(r_2).
$$

Our observation vector distinguishes them through:

$$
O_I.
$$

Therefore proposition/semantic identity does not need to become another primitive.

### Result

$$
\boxed{\text{PASS}}
$$

---

# 323.6 Attack 5 — Occurrence time versus validity time

Construct:

$$
r_1:
OccursAt(t_1),\quad ValidDuring[t_1,t_3]
$$

and:

$$
r_2:
OccursAt(t_2),\quad ValidDuring[t_1,t_3].
$$

They have identical validity but different occurrence.

Conversely:

$$
OccursAt(r_1)=OccursAt(r_2)
$$

but:

$$
ValidDuring(r_1)\neq ValidDuring(r_2).
$$

Therefore:

$$
O_T
$$

must retain:

$$
\boxed{
T=(Occurrence,Validity,Order).
}
$$

### Result

$$
\boxed{\text{PASS}}
$$

---

# 323.7 Attack 6 — Temporal order without timestamps

Suppose:

$$
r_1\prec r_2.
$$

The system knows the ordering but not exact timestamps.

Compare with another representation that assigns:

$$
t_1<t_2.
$$

If both preserve the same ordering semantics, they should be equivalent with respect to order.

Therefore:

$$
Timestamp
$$

must not be treated as the complete temporal semantics.

This confirms:

$$
O_T
$$

should distinguish:

$$
\prec
$$

from:

$$
O(t).
$$

### Result

$$
\boxed{\text{PASS}}
$$

---

# 323.8 Attack 7 — Provenance multiplicity

Suppose:

$$
DerivedFrom(r,e_1)
$$

versus:

$$
DerivedFrom(r,e_1),
DerivedFrom(r,e_2).
$$

If provenance is semantically relevant, these are different.

Thus:

$$
O_P
$$

must preserve provenance structure, not merely:

```text
hasProvenance = true
```

The observation must retain:

$$
Lineage(r).
$$

### Result

$$
\boxed{\text{PASS}}
$$

---

# 323.9 Attack 8 — Provenance order

Now consider two derivation chains:

$$
e_1\rightarrow e_2\rightarrow r
$$

and:

$$
e_2\rightarrow e_1\rightarrow r.
$$

If the derivation relation is causal/temporal, these can differ.

Therefore provenance may itself have internal structure.

We need:

$$
O_P(r)=LineageGraph(r)
$$

or an equivalent semantic representation.

This does **not** require a `Provenance` primitive.

It requires a sufficiently expressive relation structure.

### Result

$$
\boxed{\text{PASS, conditional on structured lineage}}
$$

---

# 323.10 Attack 9 — Conflict versus inconsistency

Consider:

$$
Contradicts(r_1,r_2).
$$

Now compare:

$$
r_1,r_2
$$

with no explicit contradiction relation.

Both may currently have different support statuses.

If:

$$
O_C
$$

only records:

```text
consistent = false
```

then information is lost.

Therefore conflict observation must preserve the actual conflict structure:

$$
O_C(R)=
\{(r_i,r_j):Contradicts(r_i,r_j)\}.
$$

### Result

$$
\boxed{\text{PASS}}
$$

---

# 323.11 Attack 10 — Conflict resolution metadata

Suppose:

$$
Contradicts(r_1,r_2)
$$

and a later adjudication determines:

$$
Prefer(r_1,r_2).
$$

Could `Prefer` be hidden inside conflict?

No.

The semantic distinction is:

$$
Conflict
$$

versus:

$$
Resolution.
$$

Therefore:

$$
O_C
$$

must not collapse resolution into contradiction.

But resolution can itself be another typed relation.

Thus:

$$
\boxed{
Conflict\neq Resolution
}
$$

without introducing a new primitive.

### Result

$$
\boxed{\text{PASS}}
$$

---

# 323.12 Attack 11 — Context substitution

Consider:

$$
Knows(A,P,C_1)
$$

and:

$$
Knows(A,P,C_2).
$$

If:

$$
C_1\neq C_2
$$

changes the meaning, then:

$$
O_X
$$

must distinguish them.

However, if context is merely a representational reference and the relation's semantic identity already incorporates it, then storing context separately is unnecessary.

Thus:

$$
Context
$$

is an observation dimension, not necessarily a primitive.

### Result

$$
\boxed{\text{PASS}}
$$

---

# 323.13 Attack 12 — Ambient context

Now consider context that is not explicitly stored.

For example:

$$
r
$$

is interpreted differently depending on the active bounded context.

This is dangerous.

If:

$$
Interpret_{\Gamma_1}(r)
\neq
Interpret_{\Gamma_2}(r),
$$

then the context dependency must be explicit in the semantic environment:

$$
\Gamma.
$$

Otherwise replay is not deterministic.

Therefore:

$$
AmbientContext
$$

cannot be an invisible dependency.

We get:

$$
\boxed{
HiddenContext\rightarrow ReplayFailure.
}
$$

So the dependency must be reified or explicitly supplied.

### Result

$$
\boxed{\text{PASS}}
$$

---

# 323.14 Attack 13 — Contract version

Consider:

$$
\Lambda^{v_1}
$$

and:

$$
\Lambda^{v_2}.
$$

Suppose:

$$
Meaning_{\Lambda^{v_1}}(r)
\neq
Meaning_{\Lambda^{v_2}}(r).
$$

Then a representation without contract version cannot reproduce historical interpretation.

Therefore:

$$
O_D
$$

must preserve:

$$
ContractVersion.
$$

This is not a new semantic primitive.

It is an explicit dependency.

### Result

$$
\boxed{\text{PASS}}
$$

---

# 323.15 Attack 14 — Model version

Suppose:

$$
Assessment_{\mathcal M_1}(e,H)
\neq
Assessment_{\mathcal M_2}(e,H).
$$

If the model version is absent, replay becomes ambiguous.

Therefore:

$$
UsesModel(r,M_v)
$$

must remain observable where model-dependent semantics matter.

Again:

$$
Model
$$

is not Kernel ontology.

It is a dependency.

### Result

$$
\boxed{\text{PASS}}
$$

---

# 323.16 Attack 15 — Dependency availability

Now distinguish:

$$
UsesModel(r,M)
$$

from:

$$
ModelAvailable(M).
$$

These are not the same.

The first is a semantic dependency.

The second is infrastructure state.

Therefore:

$$
O_D
$$

should record dependency identity/version, while infrastructure decides whether the dependency can currently be resolved.

This prevents infrastructure state from leaking into Kernel semantics.

### Result

$$
\boxed{\text{PASS}}
$$

---

# 323.17 Attack 16 — Distributed concurrency

Consider:

$$
r_1\parallel r_2
$$

versus:

$$
r_1\prec r_2.
$$

If concurrency matters, temporal observation must distinguish:

$$
Concurrent(r_1,r_2)
$$

from:

$$
Before(r_1,r_2).
$$

But concurrency can be derived from a partial order:

$$
\neg(r_1\prec r_2)
\land
\neg(r_2\prec r_1)
$$

under an explicitly defined ordering model.

Therefore a new primitive `Concurrency` is not demonstrated.

### Result

$$
\boxed{\text{PASS, conditional on order semantics}}
$$

---

# 323.18 Attack 17 — Causal order versus temporal order

This is more difficult.

Suppose:

$$
r_1\prec_t r_2
$$

because of temporal order.

But:

$$
r_1\prec_c r_2
$$

because \(r_1\) causally enabled \(r_2\).

These need not coincide.

Therefore:

$$
TemporalOrder\neq CausalOrder.
$$

Could causal order be another Kernel primitive?

Not necessarily.

It can be represented as:

$$
Causes(r_1,r_2)
$$

with an external causal interpretation.

So the semantic relation family can represent it.

### Result

$$
\boxed{\text{PASS as representability}}
$$

but:

$$
\boxed{\text{Causal semantics remain external}}
$$

unless future experiments show otherwise.

---

# 323.19 Attack 18 — Access structure

Now the hard case.

Let two agents have:

$$
\mathcal F_A\neq\mathcal F_B.
$$

Yet:

$$
P_A=P_B.
$$

Their probability distributions are identical, while their accessible information differs.

A relation representation can express:

$$
AccessibleTo(A,x)
$$

but can it represent an arbitrary:

$$
\mathcal F_A\subseteq\mathcal F
$$

for an infinite measurable space?

We have not proved this.

Therefore:

$$
\boxed{
O_A\text{ remains an incompletely characterized dimension.}
}
$$

This is not a failure of the relational basis yet.

It is a failure to establish universal representation completeness for arbitrary mathematical accessibility structures.

### Result

$$
\boxed{\text{PARTIAL PASS}}
$$

---

# 323.20 Attack 19 — Epistemic partition

A deeper formulation is possible.

For agent \(a\), define an epistemic indistinguishability relation:

$$
x\sim_a y.
$$

Then:

$$
[x]_a
$$

is the information set containing all worlds indistinguishable to \(a\).

Can KnowledgeOS represent this?

Potentially:

$$
Indistinguishable_a(x,y).
$$

But for arbitrary infinite structures, we again need to determine whether finite/typed relational representation is sufficient.

Thus:

$$
\boxed{
Access\ capability\ is\ representable;
universal\ mathematical\ completeness\ is\ unproven.
}
$$

---

# 323.21 Attack 20 — Semantic compression

Suppose:

$$
r_{compressed}
$$

contains only:

```text
SecurityStatus = Secure
```

while:

$$
r_{expanded}
$$

contains:

$$
Authentication,\ Authorization,\ Encryption,\ Vulnerability,\ PatchStatus,\ldots
$$

If the inquiry asks only:

$$
SecurityStatus,
$$

the two may be equivalent.

But under:

$$
Q_{security-detail},
$$

they differ.

Therefore equivalence must remain inquiry-relative:

$$
R_1\equiv_{Q_1}R_2
$$

does not imply:

$$
R_1\equiv_{Q_2}R_2.
$$

This confirms the necessity of the separating inquiry family.

### Result

$$
\boxed{\text{PASS}}
$$

---

# 323.22 Attack 21 — Unknown unknowns

This exposes a deeper limit.

Suppose a dimension:

$$
D^\star
$$

is not represented and not known to the inquiry designer.

Then:

$$
O_K
$$

cannot observe it.

Thus:

$$
Zero(K)
$$

cannot automatically reveal every possible unknown unknown.

Formally:

$$
\boxed{
D^\star\notin\mathcal O_K
\Rightarrow
O_K\text{ cannot guarantee detection of }D^\star.
}
$$

This confirms the earlier decision not to define Zero as an omniscient detector.

The possibility of a separate:

$$
MetaZero
$$

remains legitimate as a research hypothesis, but it must not be smuggled into the Kernel.

---

# 323.23 Attack 22 — Semantic authority

Consider two contracts:

$$
\Lambda_1,\Lambda_2
$$

with identical semantics but different authority.

For example:

$$
Authority(\Lambda_1)=A_1
$$

and:

$$
Authority(\Lambda_2)=A_2.
$$

If authority determines whether the contract is permitted to govern execution, then authority is semantically relevant to governance.

But does it belong in Kernel semantic identity?

No.

It is an external authorization/governance observation:

$$
O_{Auth}.
$$

This reveals an important distinction:

$$
\boxed{
Kernel\ semantic\ meaning
\neq
governance\ authority.
}
$$

Authority must remain observable when the inquiry concerns authorization, but it need not become a Kernel primitive.

---

# 323.24 Attack 23 — Authorization versus meaning

Suppose:

$$
\Lambda_1
$$

and:

$$
\Lambda_2
$$

mean exactly the same thing but only \(\Lambda_1\) is authorized.

Then:

$$
O_M(\Lambda_1)=O_M(\Lambda_2)
$$

but:

$$
O_{Auth}(\Lambda_1)\neq O_{Auth}(\Lambda_2).
$$

Therefore a broader system observation family needs:

$$
O_{Auth}.
$$

But this does **not** imply:

$$
Authorization\in KernelPrimitive.
$$

It means:

$$
Authorization
$$

is a domain/governance concern that can be represented relationally.

This distinction is now very important for DDD.

---

# 323.25 Attack 24 — Epistemic qualification

Now:

$$
r
$$

may have the same Kernel semantics under two epistemic contracts:

$$
EC_1,\ EC_2.
$$

But:

$$
\Gamma_{EC_1}(E)
\neq
\Gamma_{EC_2}(E).
$$

Therefore:

$$
O_K
$$

must not pretend to capture all epistemic qualification.

This confirms:

$$
\boxed{
KI\neq\Gamma.
}
$$

The Kernel observation family should terminate at Kernel semantics unless the inquiry explicitly crosses the boundary.

---

# 323.26 Attack 25 — Satisfaction

The same argument applies to:

$$
Sat(K,r).
$$

Until satisfaction is formally instantiated, we cannot make:

$$
O_{Sat}
$$

part of the Kernel observation algebra.

Otherwise we would smuggle unresolved theory into the equivalence definition.

Therefore:

$$
\boxed{
Sat\ remains\ outside\ the\ Kernel\ quotient.
}
$$

This is a methodological hard boundary.

---

# 323.27 Observation-family stratification

The attack reveals that we actually need **observation layers**.

### Kernel observations

$$
\mathcal O_K
$$

include:

$$
ID,\ Relation,\ Meaning,\ Temporal,\ Provenance,\ Conflict,\ History,\ Context,\ Dependency.
$$

### Epistemic observations

$$
\mathcal O_E
$$

include:

$$
Qualification,\ Adequacy,\ Zero,\ Determination,\ EvidenceAssessment.
$$

### Governance observations

$$
\mathcal O_G
$$

include:

$$
Authority,\ Authorization,\ PolicyCompliance.
$$

### Mathematical regime observations

$$
\mathcal O_M
$$

include:

$$
Probability,\ Statistical,\ Causal,\ Optimization,\ldots
$$

This is much cleaner than placing everything into one universal observation vector.

---

# 323.28 The quotient must therefore be typed

Instead of:

$$
R_1\equiv R_2,
$$

we should use:

$$
\boxed{
R_1\equiv_{\mathcal O}R_2
}
$$

where:

$$
\mathcal O
$$

is explicitly declared.

For Kernel equivalence:

$$
\equiv_K.
$$

For a particular domain:

$$
\equiv_{Election}.
$$

For an epistemic inquiry:

$$
\equiv_{Q,EC}.
$$

Thus semantic equivalence is **typed by observational scope**.

---

# 323.29 This solves an important apparent contradiction

Suppose:

$$
R_1\equiv_KR_2
$$

but:

$$
R_1\not\equiv_{Governance}R_2.
$$

There is no contradiction.

They are equivalent under one observation regime and different under another.

Therefore:

$$
\boxed{
SemanticEquivalence
is\ scope-relative.
}
$$

This is consistent with the earlier inquiry-relative formulation.

---

# 323.30 New distinction: Kernel equivalence vs epistemic equivalence

We should now explicitly introduce:

$$
\boxed{
\equiv_K
}
$$

for Kernel semantic equivalence, and:

$$
\boxed{
\equiv_E
}
$$

for epistemic equivalence under an epistemic contract.

They must not be conflated.

For example:

$$
r_1\equiv_Kr_2
$$

may hold while:

$$
\Gamma_{EC_1}(r_1)
\neq
\Gamma_{EC_1}(r_2)
$$

because epistemic state/history around them differs.

---

# 323.31 What hidden dimensions survived the attack?

After the adversarial tests:

### Absorbed by existing observations

* relation arity;
* argument roles;
* higher-order relations;
* occurrence;
* temporal validity;
* provenance structure;
* conflict structure;
* context;
* dependencies;
* versioning;
* concurrency;
* causal relations as typed relations.

### Boundary remains

$$
\boxed{
Access/Distinguishability
}
$$

for arbitrary mathematical structures.

### External observation families

$$
\boxed{
Authority,\ Qualification,\ Adequacy,\ Zero,\ Sat,\ Probability,\ Statistics,\ Causality,\ Decision.
}
$$

These are not missing Kernel primitives.

They belong to higher-level observational regimes.

---

# 323.32 Important result: no new Kernel primitive discovered

This is significant.

The hidden-dimension attack was specifically designed to discover missing primitives.

It found:

$$
\boxed{
\text{no new Kernel primitive}
}
$$

within the tested family.

Instead, every discovered distinction was handled by:

$$
ID
$$

or:

$$
TypedRelation
$$

or:

$$
SemanticContract
$$

or explicitly moved to an external regime.

That strengthens the current candidate considerably.

---

# 323.33 Formal observation completeness condition

We can now define:

$$
Complete_{\mathcal C}(\mathcal O_K)
$$

iff:

$$
\forall R_1,R_2:
\left[
O_K(R_1)=O_K(R_2)
\Rightarrow
R_1\equiv_{\mathcal C}R_2
\right]
$$

where \(\mathcal C\) is the declared Kernel capability class.

This is deliberately **relative**.

We are not trying to prove equivalence for every conceivable interpretation of reality.

---

# 323.34 Current evidence

For the tested capability family:

$$
\mathcal C_K^{tested},
$$

we have not found:

$$
R_1,R_2
$$

with:

$$
O_K(R_1)=O_K(R_2)
$$

and a known Kernel-level distinction that remains hidden.

Therefore:

$$
\boxed{
Complete_{\mathcal C_K^{tested}}(\mathcal O_K)
}
$$

has strong empirical support.

But again:

$$
\boxed{
\text{not a formal universal theorem}.
}
$$

---

# 323.35 Statistical caution

This is analogous to finite experimental coverage.

If:

$$
N
$$

adversarial tests find no hidden dimension, we gain evidence.

But:

$$
P(\text{no hidden dimension})
$$

cannot be calculated without a probabilistic model over possible hidden dimensions.

Therefore we must not manufacture a confidence level.

The correct statement is:

> Extensive structural testing has not exposed a missing Kernel observation dimension within the declared test family.

That is scientifically honest.

---

# 323.36 DDD consequence: bounded observation contracts

Each bounded context should define its own observation family.

For example:

$$
\mathcal O_{Election}
$$

may include:

$$
Eligibility,\ VoteStatus,\ Mandate,\ Contestation.
$$

But these should map to Kernel semantics through an anti-corruption layer:

$$
ACL:
\mathcal O_{Election}
\rightarrow
\mathcal O_K.
$$

This prevents Election-specific semantics from contaminating the Kernel.

---

# 323.37 Architectural principle

We can now formulate:

$$
\boxed{
Observation\ scope\ belongs\ to\ the\ bounded\ context.
}
$$

The Kernel provides stable semantic infrastructure.

The bounded context determines:

> Which distinctions matter for this domain.

This is highly compatible with DDD.

---

# 323.38 Updated mathematical architecture

We now have:

$$
\boxed{
Representation
\rightarrow
Kernel\ Semantics
\rightarrow
Kernel\ Observations
\rightarrow
Domain/Epistemic\ Observations
}
$$

more formally:

$$
R
\overset{Sem}{\longrightarrow}
S_K
\overset{\mathcal O_K}{\longrightarrow}
O_K
$$

then:

$$
(S_K,Q,C,EC)
\overset{\Gamma}{\longrightarrow}
K_t
$$

and:

$$
(K_t,Q,C,EC)
\rightarrow
\{Zero,Sat,Det,EA,\ldots\}.
$$

This is now a strongly layered architecture.

---

# 323.39 Step 323 proposition

### Proposition \(P_{323}\) — Observation Completeness Boundary

For the current declared Kernel capability family, the adversarial hidden-dimension tests do not identify a new irreducible Kernel semantic primitive beyond:

$$
\boxed{
ID,\ TypedLawBearingRelation,\ SemanticInterpretation.
}
$$

The existing observation dimensions can represent the tested distinctions involving:

$$
identity,\ relation,\ role,\ temporal,\ provenance,\ conflict,\ context,\ history,\ dependencies,\ concurrency,\ replay.
$$

Epistemic access/distinguishability remains only partially characterized for arbitrary infinite structures.

Higher-order epistemic, governance and mathematical observations remain explicitly outside the Kernel observation algebra.

---

# 323.40 Verdict

## **PASS — Hidden-Dimension Attack**

Strong evidence supports:

$$
\boxed{
\mathcal O_K
}
$$

as separating for the currently established Kernel capability family.

But:

$$
\boxed{
\text{Universal observation completeness: IN PROGRESS}
}
$$

because arbitrary access/distinguishability structures and undiscovered semantic dimensions cannot be ruled out mathematically.

Most importantly:

$$
\boxed{
\textbf{No new Kernel primitive was discovered.}
}
$$

---

# Step 324 — Next decisive step: Kernel Semantic Completeness Theorem

We have now reached the point where another round of ad-hoc concepts would be counterproductive.

The next step should formalize what we have empirically discovered.

We need to construct a theorem of the form:

$$
\boxed{
\text{Kernel Completeness Theorem — relative form}
}
$$

with four ingredients:

### 1. Capability universe

$$
\mathcal C_K
$$

must be explicitly enumerated.

### 2. Semantic language

$$
\mathcal L_K
$$

must receive a formal grammar.

### 3. Compilation/reconstruction function

$$
Compile:
\mathcal C_K
\rightarrow
\mathcal L_K.
$$

### 4. Observation preservation

For every capability \(c\):

$$
O_K(Compile(c))
=
O_K(c).
$$

The target theorem is:

$$
\boxed{
\forall c\in\mathcal C_K^{tested},
\exists\Lambda_c\in\mathcal L_K:
O_K(Compile(\Lambda_c))
=
O_K(c).
}
$$

Then combine it with Step 318's soundness result:

$$
Sound(\Lambda_c).
$$

We will finally have:

$$
\boxed{
Relative\ Completeness
+
Relative\ Soundness
}
$$

for the Kernel semantic language.

Only after that should we freeze a **KnowledgeOS Kernel v0.x mathematical specification** and return to the still-open higher-order problem:

$$
\boxed{
Sat(K,r)
}
$$

because `Sat` is now clearly outside the Kernel substrate rather than an unresolved hole inside it.
