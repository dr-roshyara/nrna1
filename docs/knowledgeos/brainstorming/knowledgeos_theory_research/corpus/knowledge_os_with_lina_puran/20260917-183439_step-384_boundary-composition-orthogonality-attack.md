# Step 384 — Boundary Composition and Orthogonality Attack

We now test the consequence of Step 383.

The candidate model is:

$$
B_\Gamma\subseteq
\prod_{d\in\mathcal D_\Gamma}D_d
$$

with boundary dimensions such as:

$$
Observation,\ Interpretation,\ Evidence,\ Determination,\ Scope,\ Temporal,\ Model.
$$

The critical question is:

$$
\boxed{
\text{Does a mathematically coherent composition operation exist for boundary findings?}
}
$$

If yes, we need to determine whether it is merely a **derived operation** over existing relations or whether it exposes another irreducible Kernel concept.

---

## 384.1 Hypotheses

We test four possibilities.

### \(H_0\) — Flat set union

Boundary composition is simply:

$$
B_1\oplus B_2=B_1\cup B_2.
$$

### \(H_1\) — Product-wise composition

Each dimension is combined independently:

$$
B_1\oplus B_2
=
(b_1^O\oplus_O b_2^O,\ldots,b_1^M\oplus_M b_2^M).
$$

### \(H_2\) — Context-dependent composition

Composition requires the semantic regime:

$$
\boxed{
B_1\oplus_\Gamma B_2.
}
$$

### \(H_3\) — New primitive required

Boundary composition cannot be reconstructed from:

$$
ID+\mathcal R^\star+\mathsf{Sem}+\Gamma
$$

and therefore requires a new universal Kernel capability.

The reduction programme strongly prefers \(H_1/H_2\), but we must attack them rather than assume them.

---

# 384.2 Test A — Uninterpreted + Conflict

Suppose two observations exist:

$$
o_1:\text{sensor reading }42
$$

$$
o_2:\text{sensor reading }45.
$$

The data are semantically uninterpreted with respect to the intended concept.

We therefore have:

$$
Uninterpreted(o_1)
$$

and:

$$
Uninterpreted(o_2).
$$

There may also be a raw-data disagreement:

$$
Conflict(o_1,o_2).
$$

Can both coexist?

Yes.

But there is an important distinction.

The conflict may exist at the **representation/data level** without constituting semantic conflict about the domain proposition.

Therefore:

$$
Conflict_{data}
\neq
Conflict_{semantic}.
$$

This is another instance of:

$$
Representation\neq Interpretation.
$$

### Result

$$
Uninterpreted+Conflict
$$

is meaningful, but the level of conflict must be typed.

---

# 384.3 Test B — Insufficient Evidence + Underdetermined

Consider:

$$
x+y=10
$$

and the inquiry asks:

$$
x=?
$$

The available information may be perfectly reliable but insufficient to determine \(x\).

Thus:

$$
Underdetermined(x).
$$

Now suppose additionally that one of the observations needed to distinguish candidate values is unreliable.

Then:

$$
InsufficientEvidence
$$

also holds.

Therefore:

$$
\boxed{
InsufficientEvidence+Underdetermined
}
$$

is a valid combination.

Neither dimension can be eliminated.

---

# 384.4 Important distinction

However:

$$
InsufficientEvidence
$$

does not necessarily **cause**:

$$
Underdetermined.
$$

Underdetermination could arise from structural non-identifiability even with perfect evidence.

Example:

$$
x+y=10
$$

is known exactly.

The problem remains underdetermined.

Hence:

$$
InsufficientEvidence
\not\Leftrightarrow
Underdetermined.
$$

---

# 384.5 Test C — Unobserved + Temporal Ambiguity

Suppose a transaction is known to have occurred, but:

* no direct observation of the exact timestamp exists;
* two possible intervals remain.

Then:

$$
Unobserved(Time)
$$

and:

$$
TemporalAmbiguity.
$$

Again, both coexist.

But notice that `Unobserved` may apply to one dimension of the target rather than to the entire target.

Therefore boundary findings should support:

$$
Concerns(b,x,d)
$$

where \(d\) identifies the affected semantic dimension.

This is better than:

```text
BoundaryFinding.unobserved = true
```

---

# 384.6 Test D — Evidence Sufficient + Model Inadequate

This is particularly important statistically.

Suppose we have a huge, high-quality dataset:

$$
EvidenceSufficient.
$$

But the model:

$$
M
$$

does not represent an important causal mechanism.

Then:

$$
ModelInadequate.
$$

Thus:

$$
\boxed{
EvidenceSufficient+ModelInadequate
}
$$

is entirely possible.

This demonstrates again:

$$
EvidenceAdequacy
\not\Rightarrow
ModelAdequacy.
$$

---

# 384.7 Test E — Conflict + Underdetermination

Suppose:

$$
x=1
$$

and:

$$
x=2
$$

are both represented, but neither source is authoritative.

Then we have:

$$
Conflict(x).
$$

Suppose additionally that the inquiry permits:

$$
x\in\{1,2,3\}.
$$

Then even after preserving the conflict:

$$
Underdetermined(x)
$$

can remain.

Thus:

$$
\boxed{
Conflict+Underdetermined
}
$$

is meaningful.

And importantly:

$$
Conflict\neq Underdetermined.
$$

---

# 384.8 Test F — Temporal expiry + epistemic uncertainty

Suppose a certificate was valid until:

$$
t_1.
$$

After \(t_1\):

$$
Expired.
$$

But we do not know whether a replacement certificate exists.

Then:

$$
Expired
$$

and:

$$
UnknownReplacement.
$$

This exposes another important distinction:

$$
TemporalValidity
$$

is not necessarily a **boundary reason**.

It may be an ordinary semantic fact from which another boundary follows.

For example:

$$
ValidUntil(c,t_1)
$$

plus current time \(t_2>t_1\) can yield:

$$
Expired(c,t_2).
$$

So we should not put every derived status directly into the Boundary taxonomy.

---

# 384.9 First reduction result

We therefore obtain:

$$
\boxed{
BoundaryReason\neq SemanticFact\neq EvaluationResult.
}
$$

A temporal fact may produce a boundary finding, but is not necessarily itself a boundary.

Likewise:

$$
Conflict
$$

can be a semantic relation whose presence causes a boundary in a particular inquiry.

This is a very useful architectural distinction.

---

# 384.10 Test G — Are boundary combinations commutative?

Consider:

$$
B_1=Uninterpreted
$$

and:

$$
B_2=InsufficientEvidence.
$$

Does:

$$
B_1\oplus B_2
=
B_2\oplus B_1?
$$

At the **set-of-findings** level, yes:

$$
\{b_1,b_2\}=\{b_2,b_1\}.
$$

But at the **semantic resolution** level, order may matter.

Example:

1. resolve interpretation;
2. reassess evidence.

The second evaluation may change because interpretation changed.

Thus:

$$
CompositionOfFindings
$$

may be commutative while:

$$
ResolutionProcess
$$

is not.

This is a crucial distinction.

$$
\boxed{
BoundaryComposition\neq BoundaryResolution.
}
$$

---

# 384.11 Test H — Associativity

For pure accumulation of immutable findings:

$$
(B_1\cup B_2)\cup B_3
=
B_1\cup(B_2\cup B_3).
$$

So set-like accumulation is associative.

But semantic normalization may not be.

For example, a regime could define:

$$
Conflict(a,b)
$$

and then derive a higher-level status after seeing \(c\).

Therefore:

$$
Normalize(B_1\cup B_2)\cup B_3
$$

need not equal:

$$
Normalize(B_1\cup(B_2\cup B_3)).
$$

unless the normalization contract guarantees associativity.

Therefore we cannot claim a universal boundary algebra with associative semantic reduction.

---

# 384.12 Test I — Identity element

For accumulation, an empty boundary set exists:

$$
\varnothing.
$$

Thus:

$$
B\cup\varnothing=B.
$$

But this does **not** mean:

$$
\varnothing
$$

is an epistemic state called “Zero”.

This is critical.

We must distinguish:

$$
\boxed{
EmptyBoundarySet\neq Zero.
}
$$

Zero asks what is not established.

An empty **reported finding set** merely says that the current operation produced no boundary findings under that projection.

It does not prove:

$$
NoBoundaryExists.
$$

---

# 384.13 Test J — Idempotence

For immutable finding accumulation:

$$
B\cup B=B.
$$

This is useful for distributed systems.

If the same finding arrives twice:

$$
Merge(B,b,b)=Merge(B,b).
$$

But again this is an implementation/representation property of the accumulation structure.

It does not create a `Boundary` primitive.

---

# 384.14 Distributed consequence

If boundary findings have stable identities:

$$
IID(b),
$$

then duplicate delivery can be eliminated by identity.

For replicas:

$$
B_A\cup B_B
$$

can preserve all findings.

This resembles a join-semilattice:

$$
(B,\subseteq,\cup).
$$

But we must be careful.

The **set of historical findings** may have this structure.

The **semantic interpretation of current boundaries** does not necessarily have it.

Therefore:

$$
\boxed{
BoundaryHistory\text{ may be semilattice-like}
}
$$

while:

$$
\boxed{
CurrentBoundarySemantics\text{ need not be}.
}
$$

---

# 384.15 This mirrors our earlier History result

We already established:

$$
H_{t+1}\supseteq H_t
$$

while current epistemic state can be non-monotonic.

The same pattern appears here:

$$
BH_{t+1}\supseteq BH_t
$$

for preserved historical findings, while:

$$
B_{t+1}
$$

may change in arbitrary directions after re-evaluation.

Thus:

$$
\boxed{
BoundaryHistory\neq CurrentBoundaryProjection.
}
$$

---

# 384.16 Test K — Can boundary findings contradict one another?

Yes.

Suppose:

$$
b_1=InsufficientEvidence(r)
$$

under:

$$
\Gamma_1.
$$

Later:

$$
b_2=EvidenceSufficient(r)
$$

under:

$$
\Gamma_2.
$$

These are not necessarily logical contradiction because:

$$
\Gamma_1\neq\Gamma_2.
$$

Even under the same regime, historical findings can legitimately differ after new evidence.

Therefore:

$$
b_1\neq b_2
$$

and:

$$
Supersedes(b_2,b_1)
$$

can preserve the evolution.

---

# 384.17 Boundary conflict versus logical contradiction

Suppose:

$$
b_1=InsufficientEvidence(r)
$$

and:

$$
b_2=EvidenceSufficient(r).
$$

Does:

$$
Conflict(b_1,b_2)
$$

necessarily follow?

No.

They may be temporally successive:

$$
b_1\prec_t b_2.
$$

Therefore:

$$
TemporalRevision
\neq
LogicalConflict.
$$

Again:

$$
Conflict\neq Invalidity.
$$

---

# 384.18 Test L — Does composition create a new primitive?

Suppose we define:

$$
B_3=B_1\oplus_\Gamma B_2.
$$

What is \(B_3\)?

It is another collection of relation instances.

For example:

$$
b_1=(IID_1,\rho_1,args_1)
$$

$$
b_2=(IID_2,\rho_2,args_2).
$$

Composition can produce:

$$
b_3=(IID_3,\rho_3,args_3)
$$

where \(\rho_3\) has a contract describing the derived relation.

Thus:

$$
\boxed{
B_3\in Inst(\mathcal R^\star).
}
$$

No new object ontology is required.

---

# 384.19 Derived composition

We can therefore define:

$$
\boxed{
Compose_B:
\mathcal P(Inst(\mathcal R^\star))
\times\Gamma
\rightharpoonup
\mathcal P(Inst(\mathcal R^\star))
}
$$

where the operation is partial because the semantic regime determines:

* compatibility;
* implication;
* aggregation;
* normalization;
* conflict;
* redundancy.

The partiality is important.

---

# 384.20 Why no universal Boolean algebra?

It is tempting to define:

$$
B_1\land B_2
$$

and:

$$
B_1\lor B_2.
$$

But this is unsafe.

`Uninterpreted` and `InsufficientEvidence` are not ordinary propositions with universal Boolean semantics.

For example:

$$
Uninterpreted\land InsufficientEvidence
$$

could be meaningful under one regime, but whether one finding implies another depends on semantic contracts.

Therefore:

$$
\boxed{
Boundary\text{ is not universally a Boolean algebra.}
}
$$

---

# 384.21 Why no universal lattice?

Could boundary states form a lattice?

Not generally established.

To have a lattice, every pair must have:

$$
meet
$$

and:

$$
join
$$

under a declared partial order.

But what should the order mean?

Possibilities include:

* more informative;
* more severe;
* more resolved;
* more specific;
* stronger evidence;
* narrower uncertainty.

These are different orders.

Therefore:

$$
\boxed{
There is no canonical universal boundary ordering.
}
$$

---

# 384.22 Different orders can coexist

For example:

$$
B_1\preceq_{information}B_2
$$

might mean \(B_2\) contains more diagnostic information.

But:

$$
B_1\preceq_{severity}B_2
$$

might mean \(B_2\) is more serious.

These orders need not agree.

Thus:

$$
\preceq_{information}
\neq
\preceq_{severity}.
$$

This is another argument against a scalar `boundaryScore`.

---

# 384.23 Statistician's perspective

A scalar score:

$$
s(B)\in\mathbb R
$$

would impose an ordering:

$$
B_1<B_2.
$$

But multidimensional boundary states generally form only a **partial order**, if any.

For example:

$$
B_1=(HighEvidence,LowDetermination)
$$

and:

$$
B_2=(LowEvidence,HighDetermination)
$$

cannot naturally be ranked without specifying a utility or decision criterion.

Therefore:

$$
\boxed{
BoundarySeverity\text{ requires an explicit evaluation regime.}
}
$$

---

# 384.24 Important consequence for ML

An ML model should not be trained against an implicit scalar:

```text
boundary_score = 0.73
```

unless a domain-specific loss function explicitly defines what that score means.

Otherwise we risk converting:

$$
multidimensional\ epistemic\ deficiency
$$

into:

$$
arbitrary\ numerical\ ranking.
$$

A classifier may legitimately predict:

$$
P(Uninterpreted\mid D)
$$

or:

$$
P(Conflict\mid D),
$$

but those probabilities are not themselves epistemic truth.

---

# 384.25 Test M — Boundary implication

Can one boundary type imply another?

Sometimes.

For example, under a specific regime:

$$
Unobserved(x)
\Rightarrow
NoDirectObservation(x).
$$

But:

$$
Unobserved(x)
\Rightarrow
Unknown(x)
$$

may depend on whether alternative indirect information exists.

Therefore implication is:

$$
\boxed{
\Gamma\vdash b_1\Rightarrow b_2
}
$$

not a universal ontology rule.

---

# 384.26 Test N — Boundary refinement

A coarse finding:

$$
Unknown(x)
$$

may later be refined to:

$$
Unobserved(x).
$$

Or:

$$
Uninterpreted(x).
$$

Or:

$$
Underdetermined(x).
$$

This means refinement is possible:

$$
b_2\preceq_{diag}b_1.
$$

But the direction depends on the chosen information order.

Again, there is no universal order.

---

# 384.27 Diagnostic refinement

A useful derived relation is:

$$
RefinesBoundary(b_2,b_1).
$$

Example:

$$
Unknown(x)
$$

becomes:

$$
InsufficientEvidence(x).
$$

This is not necessarily “more knowledge”; it is more specific diagnosis.

Therefore:

$$
DiagnosticRefinement
\neq
KnowledgeGain.
$$

---

# 384.28 Counterexample: more information, more boundary

Suppose initially:

$$
Unknown(x).
$$

After investigation we discover two contradictory sources:

$$
x=1,\qquad x=2.
$$

The boundary may change from:

$$
Unknown
$$

to:

$$
Conflict.
$$

The number of boundary findings may increase.

Yet epistemically we have learned something.

Therefore again:

$$
KnowledgeGain\not\Rightarrow |B_{t+1}|<|B_t|.
$$

---

# 384.29 Boundary resolution is not annihilation

Suppose:

$$
b_1=Uninterpreted(o).
$$

Later interpretation is established.

We should not delete \(b_1\).

Instead:

$$
b_2=Resolved(b_1)
$$

or:

$$
Supersedes(b_2,b_1).
$$

Thus:

$$
HistoricalBoundaryExistence
\neq
CurrentBoundaryStatus.
$$

This preserves auditability.

---

# 384.30 DDD consequence: do not build a Boundary Aggregate prematurely

The evidence currently supports:

$$
BoundaryFinding
$$

as a semantic relation occurrence.

It does **not** support a universal:

```text
BoundaryAggregate
```

with methods such as:

```text
resolve()
merge()
score()
prioritize()
classify()
validate()
```

That would combine unrelated semantic responsibilities.

---

# 384.31 Better DDD decomposition

Conceptually:

$$
\boxed{
BoundaryDetection
}
$$

produces findings.

$$
\boxed{
BoundaryClassification
}
$$

assigns typed diagnostic roles.

$$
\boxed{
BoundaryEvaluation
}
$$

assesses significance under a regime.

$$
\boxed{
BoundaryResolution
}
$$

performs domain-specific transitions.

$$
\boxed{
BoundaryHistory
}
$$

preserves occurrences.

These can all use the same Kernel representation without becoming Kernel primitives.

---

# 384.32 Boundary composition formal candidate

We can now define two distinct operations.

### Historical accumulation

$$
\boxed{
Merge_H(B_1,B_2)
=
B_1\cup B_2
}
$$

subject to identity and provenance rules.

This can be:

* associative;
* commutative;
* idempotent.

### Semantic composition

$$
\boxed{
Compose_\Gamma(B_1,B_2)
}
$$

which is partial and regime-dependent.

It need not be:

* commutative;
* associative;
* idempotent;
* lattice-forming.

This distinction is extremely important.

---

# 384.33 Why this matters for distributed KnowledgeOS

Distributed systems should merge **facts/findings/history**, not arbitrarily merge semantic interpretations.

Safe:

$$
H_M=H_A\cup H_B.
$$

Then:

$$
B_M=DeriveBoundary(H_M,\Gamma).
$$

Unsafe universal rule:

$$
B_M=B_A\oplus B_B
$$

without specifying semantic regime.

Thus:

$$
\boxed{
Merge\ History\ First,\ Derive\ Boundary\ Second.
}
$$

This aligns with our existing deterministic replay architecture.

---

# 384.34 Boundary derivation

The preferred pipeline becomes:

$$
\boxed{
H
\xrightarrow[\Gamma]{Derive}
K
\xrightarrow[Q,\Gamma]{ZeroLens}
B
\xrightarrow[\Gamma]{Evaluate}
V.
}
$$

Not:

$$
B\to K.
$$

And not:

$$
B_A\oplus B_B
$$

as a substitute for history reconciliation.

---

# 384.35 New architectural invariant

### Boundary-Derivation Principle

> Current boundary semantics should be derivable from sufficiently complete epistemic/history structures and explicit semantic dependencies; boundary findings must not become the hidden source of truth for the state from which they were derived.

Formally:

$$
\boxed{
B_t=DeriveBoundary(H_{\le t},\Gamma_t,Q_t)
}
$$

where reconstruction is subject to the declared contract.

---

# 384.36 New distributed invariant

### Boundary Merge Non-Semanticity

$$
\boxed{
Merge(B_A,B_B)
\neq
Resolve(B_A,B_B)
}
$$

in general.

Merge preserves represented findings.

Resolution requires an explicit semantic/governance contract.

---

# 384.37 New principle

### Boundary Composition Non-Promotion

$$
\boxed{
Compose_\Gamma(B_1,B_2)
\not\Rightarrow
BoundaryComposition\in B_K.
}
$$

If composition can be expressed through existing relation instances and semantic contracts, it is a derived capability.

---

# 384.38 New principle

### Boundary Order Relativity

$$
\boxed{
\preceq_{B,\Gamma_1}
\neq
\preceq_{B,\Gamma_2}
}
$$

may hold.

There is no universal “more boundary” or “less boundary” order without declaring what the order means.

---

# 384.39 New principle

### Boundary Merge–Resolution Separation

$$
\boxed{
Merge\neq Resolution.
}
$$

Historical accumulation can be algebraically simple even when semantic resolution is complex and regime-dependent.

---

# 384.40 New principle

### Boundary Projection Non-Monotonicity

Even when:

$$
H_{t+1}\supseteq H_t,
$$

it does not follow that:

$$
B_{t+1}\supseteq B_t
$$

or:

$$
B_{t+1}\subseteq B_t.
$$

Because:

$$
B_t=\Pi_B(Derive(H_{\le t},\Gamma_t),Q_t).
$$

---

# 384.41 Does a new primitive emerge?

We can now perform the decisive reduction test.

Candidate:

$$
BoundaryCompositionPrimitive.
$$

But:

$$
Compose_\Gamma
$$

is constructed from:

$$
ID,\mathcal R^\star,\mathsf{Sem},\Gamma
$$

plus derived evaluation rules.

Likewise:

$$
Merge_H
$$

is history/set-like aggregation over identity-bearing findings.

Therefore:

$$
\boxed{
H_3\text{ is not supported.}
}
$$

---

# 384.42 But \(H_0\) is also rejected

Pure union is insufficient for semantic interpretation.

Thus:

$$
H_0:\text{flat set union}
$$

is too weak.

The strongest current position is:

$$
\boxed{
H_2:
\text{history accumulation + regime-dependent semantic composition}.
}
$$

---

# 384.43 Refined Boundary model

The current model should therefore be:

$$
\boxed{
\mathcal B_\Gamma
=
\text{typed boundary findings derived under }\Gamma
}
$$

with:

$$
b=(IID_b,\rho_b,args_b)
$$

and:

$$
\Lambda_{\rho_b}=(C_b,T_b,M_b).
$$

Historical merge:

$$
\boxed{
Merge_H(B_1,B_2)=B_1\cup B_2
}
$$

under identity/provenance semantics.

Semantic composition:

$$
\boxed{
Compose_\Gamma(B_1,B_2)
}
$$

is partial and regime-dependent.

---

# 384.44 Strong result

We have now demonstrated something more precise than “boundary types exist.”

There are **three different algebraic levels**:

### Level 1 — Representation accumulation

$$
\boxed{
Set/identity-level merge
}
$$

### Level 2 — Semantic derivation

$$
\boxed{
BoundaryDerive_\Gamma
}
$$

### Level 3 — Evaluation/resolution

$$
\boxed{
Evaluate_\Gamma,\ Resolve_\Gamma
}
$$

These must not be collapsed.

---

# 384.45 Relation to the Kernel

All three can operate over:

$$
\mathfrak K_{\min}
=
(ID,\mathcal R^\star,\mathsf{Sem}).
$$

Therefore the current Kernel remains:

$$
\boxed{
B_K=\{ID,\mathcal R^\star,\mathsf{Sem}\}.
}
$$

No expansion is justified.

---

# 384.46 Step 384 verdict

| Question                                                                  | Result                                        |
| ------------------------------------------------------------------------- | --------------------------------------------- |
| Can multiple boundary dimensions coexist?                                 | **YES**                                       |
| Is simple union enough for semantics?                                     | **NO**                                        |
| Can historical findings be union-merged?                                  | **YES, under identity/provenance conditions** |
| Is semantic composition universally commutative?                          | **NO**                                        |
| Universally associative?                                                  | **NOT ESTABLISHED**                           |
| Universal lattice?                                                        | **NO**                                        |
| Universal Boolean algebra?                                                | **NO**                                        |
| Universal severity/order?                                                 | **NO**                                        |
| Can findings be contradictory historically?                               | **YES**                                       |
| Does conflict require automatic resolution?                               | **NO**                                        |
| Can boundary composition be represented using existing Kernel structures? | **YES**                                       |
| Does a BoundaryComposition primitive emerge?                              | **NO**                                        |
| Does distributed merge require semantic resolution?                       | **NO**                                        |
| Does semantic resolution require explicit regime?                         | **YES**                                       |

### Final verdict

$$
\boxed{
\textbf{PASS — Boundary Composition Reduction}
}
$$

More precisely:

$$
\boxed{
\text{Boundary history has a potentially semilattice-like accumulation algebra,}
}
$$

while:

$$
\boxed{
\text{current semantic boundary composition is partial and regime-dependent.}
}
$$

No new Kernel primitive is demonstrated.

---

# Step 385 — Next decisive attack

The next attack should now move one level deeper:

## **Boundary Dependency and Causality Irreducibility Attack**

We have shown that several boundary dimensions can coexist. But they are not necessarily independent.

For example:

$$
Uninterpreted
\rightarrow
InsufficientEvidence
\rightarrow
Underdetermined
$$

may appear as a causal chain.

But perhaps the apparent dependency is merely an artifact of a particular evaluation regime.

We therefore need to test:

$$
\boxed{
BoundaryDependency
\stackrel{?}{=}
ordinary\ typed\ relations
}
$$

against:

* causal dependency;
* logical implication;
* evidential dependency;
* temporal precedence;
* diagnostic refinement;
* resolution dependency;
* prerequisite dependency;
* circular boundary dependencies.

The critical question will be:

$$
\boxed{
Does KnowledgeOS need a universal
BoundaryDependency/Causality\ primitive,
or can every dependency remain a typed relation with
appropriate\ }(C,T,M)\text{ semantics?}
}
$$

This is especially important because **causal dependency is one of the places where the boundary between relational representation and external mathematical semantics becomes genuinely difficult**.
