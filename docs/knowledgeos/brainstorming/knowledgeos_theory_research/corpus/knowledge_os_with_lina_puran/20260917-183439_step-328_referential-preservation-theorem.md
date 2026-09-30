# Step 328 — Referential Preservation Theorem

We continue directly from Step 327.

We have established the structural candidate:

$$
\boxed{
\mathcal K_F=(ID,\mathcal R^\star,\mathcal L_K,\vdash)
}
$$

with:

$$
r=(IID,\rho,args)
$$

and have a type-preservation target:

$$
TypeOK(K)\land
\Gamma\vdash K\xrightarrow rK'
\Rightarrow
TypeOK(K').
$$

The next question is deeper:

> **Does every admissible Kernel transformation preserve the ability to identify and resolve the relation instances on which the semantic structure depends?**

This is the formal version of the identity irreducibility result from Steps 308 and 321.

---

# 328.1 Referential integrity is not ordinary database referential integrity

We must be precise.

A database foreign key usually means:

$$
FK\rightarrow PK.
$$

KnowledgeOS requires more.

A relation may refer to:

* another relation;
* an assertion;
* an evidence artifact;
* a participant;
* a provenance source;
* a superseded relation;
* a retracted relation;
* a historical occurrence.

The target may no longer be currently active, but it must remain historically referable.

Therefore:

$$
\boxed{
Resolvable\neq CurrentlyActive.
}
$$

This is essential.

---

# 328.2 Define referential closure

Let:

$$
Ref(K)
$$

be the set of identities referenced by relations in \(K\).

Let:

$$
Dom(K)
$$

be the set of relation identities actually available in the Kernel's semantic history.

Define:

$$
\boxed{
RefClosed(K)
\iff
Ref(K)\subseteq Dom(K).
}
$$

This is the basic referential-closure property.

But it is still incomplete because a historical reference may refer to an object that is no longer current.

So we need:

$$
Dom_H(K)
$$

for the historical identity domain.

Then:

$$
\boxed{
RefClosed_H(K)
\iff
Ref(K)\subseteq Dom_H(K).
}
$$

---

# 328.3 Current existence versus historical existence

We therefore distinguish:

$$
Exists_{current}(r)
$$

from:

$$
Exists_H(r).
$$

A retracted relation satisfies:

$$
Exists_H(r)=1
$$

while:

$$
Active(r)=0.
$$

Thus:

$$
\boxed{
Exists_H(r)\not\Rightarrow Active(r).
}
$$

And:

$$
\boxed{
Retracted(r)\not\Rightarrow Unreferable(r).
}
$$

This is a direct consequence of the historical model.

---

# 328.4 Referential closure invariant

Define:

$$
\boxed{
I_{ref}(K):
\forall r\in K,\ 
\forall x\in Ref(r):
x\in Dom_H(K).
}
$$

This means every required reference resolves to a stable identity in the historical domain.

This invariant is independent of semantic truth.

---

# 328.5 Why identity is necessary

Consider:

$$
r_1=Assert(A,P)
$$

and:

$$
r_2=Retracts(B,r_1).
$$

The second relation requires a stable target:

$$
IID(r_1).
$$

If identity is removed, we may have only:

$$
Assert(A,P).
$$

But if two identical assertions exist:

$$
r_1,r_2
$$

with identical content, which one is retracted?

Without identity:

$$
Retracts(B,?)
$$

is ambiguous.

Therefore:

$$
\boxed{
ID\text{ is required for referential closure.}
}
$$

---

# 328.6 Formal counterexample

Let:

$$
r_1=(i_1,Assert,(A,P))
$$

$$
r_2=(i_2,Assert,(A,P))
$$

with:

$$
i_1\neq i_2.
$$

Suppose:

$$
x=Retracts(B,r_1).
$$

If the system stores only:

$$
Assert(A,P)
$$

without \(IID\), then:

$$
x
$$

cannot uniquely resolve its target.

Hence:

$$
\boxed{
\mathcal R^\star
\not\Rightarrow
ReferentialIdentity
}
$$

without the identity capability.

This strengthens Step 308.

---

# 328.7 Retraction preservation theorem

Suppose:

$$
K\xrightarrow{Retracts(x,r)}K'.
$$

Then:

$$
Exists_H(r,K')
$$

must remain true.

Therefore:

$$
RefClosed_H(K')
$$

requires that the identity of \(r\) remain resolvable.

So:

$$
\boxed{
Retract
\text{ must preserve referential identity.}
}
$$

---

# 328.8 Supersession

Suppose:

$$
Supersedes(r_2,r_1).
$$

We require:

$$
Exists_H(r_1).
$$

The predecessor must remain referable because future inquiries may ask:

* What was superseded?
* Why?
* By what?
* Under which contract?
* What evidence supported the predecessor?

Therefore:

$$
\boxed{
Supersession\ preserves\ historical\ references.
}
$$

---

# 328.9 Provenance

Consider:

$$
DerivedFrom(r_2,r_1).
$$

Even if \(r_1\) is later retracted:

$$
Exists_H(r_1)
$$

must remain true.

Otherwise:

$$
DerivedFrom(r_2,r_1)
$$

becomes an orphaned provenance edge.

This would destroy lineage.

Therefore:

$$
\boxed{
Provenance\ depends\ on\ referential\ preservation.
}
$$

---

# 328.10 Higher-order relations

Consider:

$$
Retracts(r_3,r_2)
$$

where:

$$
r_2=DerivedFrom(r_1,e).
$$

We now have:

$$
Relation
\rightarrow
Relation
\rightarrow
Relation.
$$

Referential closure must work recursively.

Define the reference graph:

$$
G_R(K)=(V,E)
$$

where:

$$
V=Dom_H(K)
$$

and:

$$
(x,y)\in E
$$

iff \(x\) references \(y\).

Then:

$$
\boxed{
RefClosed_H(K)
}
$$

means every semantic reference edge terminates in a valid historical identity.

No new primitive is required.

---

# 328.11 Referential preservation under Assert

For:

$$
K'=K\cup\{r\},
$$

if:

$$
r
$$

contains only references already in:

$$
Dom_H(K),
$$

then:

$$
RefClosed_H(K)
\Rightarrow
RefClosed_H(K').
$$

If \(r\) introduces a new relation identity:

$$
IID(r)
$$

then that identity itself becomes part of:

$$
Dom_H(K').
$$

Therefore:

$$
\boxed{\text{PASS}}
$$

---

# 328.12 Referential preservation under Retraction

Retraction adds a relation referring to an existing relation.

Because:

$$
IID(r_{target})\in Dom_H(K),
$$

and the retraction relation receives its own identity:

$$
IID(r_{retract}),
$$

both remain resolvable.

Therefore:

$$
RefClosed_H(K')
$$

holds.

$$
\boxed{\text{PASS}}
$$

---

# 328.13 Referential preservation under Supersession

Exactly the same argument applies.

Both:

$$
IID(r_{old})
$$

and:

$$
IID(r_{new})
$$

remain in:

$$
Dom_H(K').
$$

Thus:

$$
\boxed{\text{PASS}}
$$

---

# 328.14 Referential preservation under Conflict

For:

$$
Contradicts(r_1,r_2),
$$

both targets must remain referable.

Even if one is later rejected by an external adjudication regime:

$$
Exists_H(r_i)
$$

remains preserved.

Thus:

$$
\boxed{\text{PASS}}
$$

---

# 328.15 Referential preservation under provenance

For:

$$
DerivedFrom(r_2,r_1),
$$

both relation identities remain in the historical domain.

Therefore:

$$
Lineage(r_2)
$$

remains reconstructible.

$$
\boxed{\text{PASS}}
$$

---

# 328.16 Merge

Now the distributed case is more interesting.

Suppose:

$$
K_A
$$

and:

$$
K_B
$$

are replicas.

We require:

$$
Merge(K_A,K_B)=K_M.
$$

The merge must preserve the identity mapping:

$$
IID_A(r)\leftrightarrow IID_B(r).
$$

If the same event is delivered twice:

$$
IID_A(r)=IID_B(r),
$$

then it is one logical instance.

If they represent independent occurrences:

$$
IID_A(r_1)\neq IID_B(r_2),
$$

they must remain distinct.

Therefore:

$$
\boxed{
IID
\text{ is the distributed referential key.}
}
$$

---

# 328.17 Duplicate delivery

Suppose:

$$
K_A=\{r\}
$$

and:

$$
K_B=\{r\}.
$$

with the same:

$$
IID(r).
$$

Then merge should produce:

$$
K_M=\{r\}.
$$

Thus:

$$
Merge(K,K)=K
$$

with respect to duplicate identity.

This is **idempotence of duplicate identity**, not yet a universal theorem that all KnowledgeOS merge operations form a semilattice.

---

# 328.18 Independent identical assertions

Now:

$$
IID(r_1)\neq IID(r_2)
$$

even though:

$$
SID(r_1)=SID(r_2).
$$

Then merge must preserve:

$$
\{r_1,r_2\}.
$$

Otherwise evidence multiplicity and independent observation would be lost.

Therefore:

$$
\boxed{
IID\text{ must dominate duplicate elimination;}
}
$$

while:

$$
SID
$$

can support semantic grouping.

---

# 328.19 Referential preservation under representation translation

Let:

$$
f:R_1\rightarrow R_2
$$

be a representation transformation.

For identity-preserving translation we require:

$$
\boxed{
IID(f(r))=Map_{ID}(IID(r)).
}
$$

If \(Map_{ID}\) is bijective over the transformed domain, then referential structure is preserved.

Thus:

$$
RefClosed_H(R_1)
\Rightarrow
RefClosed_H(R_2).
$$

This gives a concrete correctness criterion for migrations.

---

# 328.20 Identity renaming

A representation can legitimately change the physical identifier:

$$
UUID_1
\rightarrow
UUID_2.
$$

That does not necessarily violate semantic identity.

We need:

$$
\phi:
ID_1\rightarrow ID_2
$$

with:

$$
\phi
$$

bijective over the relevant identity domain.

Then:

$$
r_1
$$

and:

$$
r_2
$$

remain referentially equivalent under:

$$
\phi.
$$

Thus:

$$
\boxed{
PhysicalID\ equality
\neq
semantic\ identity.
}
$$

---

# 328.21 Referential translation theorem

If:

1. \(f\) maps every identity through a bijection \(\phi\);
2. all relation references are transformed consistently;
3. no historical relation is silently dropped;
4. relation typing is preserved;

then:

$$
\boxed{
RefClosed_H(R)
\Rightarrow
RefClosed_H(f(R)).
}
$$

This is a strong practical theorem for storage migration.

---

# 328.22 Replay

Suppose:

$$
K_t=Fold(H_{\leq t},\Gamma).
$$

Every relation in the history has stable identity.

During replay:

$$
IID(r_i)
$$

must remain invariant.

Therefore:

$$
\boxed{
Replay\ does\ not\ regenerate\ historical\ identity.
}
$$

This is crucial.

If replay generated a new identity each time:

$$
IID_{replay}(r)\neq IID_{original}(r),
$$

then provenance and retraction references would break.

---

# 328.23 Replay identity invariant

Formally:

$$
\boxed{
Replay(H,\Gamma)=K
\Rightarrow
\forall r\in H:
IID_{replay}(r)=IID_H(r).
}
$$

This is stronger than ordinary deterministic replay.

It is **referentially deterministic replay**.

---

# 328.24 Historical immutability

The identity:

$$
IID(r)
$$

must not be reused for a semantically unrelated relation.

Otherwise:

$$
Retracts(IID(r))
$$

could later refer to a completely different object.

Therefore:

$$
\boxed{
IID\ reuse\ across\ semantic\ instances
\text{ is prohibited.}
}
$$

This is a Kernel invariant.

---

# 328.25 Identity reuse counterexample

Suppose:

$$
r_1=Assert(A,P)
$$

has:

$$
IID=42.
$$

Later \(r_1\) is retired.

If:

$$
IID=42
$$

is reused for:

$$
r_2=Assert(B,Q),
$$

then historical:

$$
Retracts(42)
$$

becomes ambiguous.

Therefore stable identity requires:

$$
\boxed{
IID(r_1)=IID(r_2)
\Rightarrow
r_1=r_2
}
$$

within the relevant identity domain.

---

# 328.26 Referential preservation and semantic identity

We now see a useful hierarchy:

$$
IID
\rightarrow
Reference
\rightarrow
SemanticStructure
$$

but not:

$$
SemanticIdentity
\rightarrow
InstanceIdentity.
$$

Semantic identity may group multiple instances:

$$
SID(r_1)=SID(r_2)
$$

while:

$$
IID(r_1)\neq IID(r_2).
$$

Thus:

$$
\boxed{
SID
\text{ is insufficient for referential closure.}
}
$$

---

# 328.27 Referential preservation and provenance

Likewise:

$$
Provenance
$$

cannot replace identity.

A provenance edge:

$$
DerivedFrom(r_2,r_1)
$$

must identify its endpoints.

Therefore:

$$
\boxed{
Provenance\ presupposes\ reference.
}
$$

This strengthens the earlier irreducibility argument.

---

# 328.28 Referential preservation and context

Context can be represented as:

$$
InContext(r,C).
$$

The context reference must itself resolve.

Thus:

$$
Context
$$

does not replace identity; it uses identity.

This is another example of:

$$
\boxed{
Capability\ dependency
\neq
primitive\ ontology.
}
$$

---

# 328.29 Referential preservation and temporal structure

Likewise:

$$
Before(r_1,r_2)
$$

requires identifiable endpoints.

Therefore:

$$
TemporalOrder
$$

depends on stable referential identity.

But temporal order cannot reconstruct identity.

Hence:

$$
IID\not\Leftarrow TemporalOrder.
$$

---

# 328.30 Main theorem

We can now formulate the central result.

### Theorem \(T_{328}\) — Referential Preservation

Let \(K\) be a referentially closed Kernel state:

$$
RefClosed_H(K).
$$

Let:

$$
\Gamma\vdash K\xrightarrow rK'
$$

be an admissible Kernel transition satisfying:

1. every new relation has a fresh or explicitly preserved IID;
2. every reference target is either newly created or already historically resolvable;
3. no IID is silently reused;
4. relation transformations preserve reference mappings.

Then:

$$
\boxed{
RefClosed_H(K').
}
$$

---

# 328.31 Proof

Consider any relation:

$$
r'\in K'.
$$

There are two cases.

### Case 1 — \(r'\in K\)

By the induction hypothesis:

$$
RefClosed_H(K)
$$

and the transition does not invalidate existing historical identities.

Therefore every reference of \(r'\) remains resolvable.

### Case 2 — \(r'\) is newly generated

By condition 1:

$$
IID(r')
$$

is valid.

By condition 2, every referenced identity is either:

$$
\in Dom_H(K)
$$

or belongs to the newly generated identity set.

Thus:

$$
Ref(r')\subseteq Dom_H(K').
$$

Therefore:

$$
RefClosed_H(K').
$$

Hence:

$$
\boxed{
RefClosed_H(K')
}
$$

follows.

---

# 328.32 Proof status

Unlike some earlier conceptual results, this theorem is now structurally provable by induction once the primitive transition contracts satisfy the stated premises.

So:

$$
\boxed{
Referential\ Preservation:
PASS\ at\ theorem\ schema\ level.
}
$$

The remaining work is to instantiate every primitive transition and verify the premises.

---

# 328.33 Combine with type preservation

We now have:

$$
TypeOK(K)
\Rightarrow
TypeOK(K')
$$

and:

$$
RefClosed_H(K)
\Rightarrow
RefClosed_H(K').
$$

Therefore define the combined structural invariant:

$$
\boxed{
I_{struct}(K)
=
TypeOK(K)
\land
RefClosed_H(K).
}
$$

Then:

$$
I_{struct}(K)
\land
Admissible(r)
\Rightarrow
I_{struct}(K').
$$

This is the first integrated Kernel invariant.

---

# 328.34 DDD interpretation

This has a direct DDD consequence.

An entity's identity is not merely an ORM/database convenience.

It is necessary because domain relationships may refer to historical instances.

Thus the Kernel's identity invariant is stronger than:

```text id="7t1c5m"
primary key exists
```

It means:

> Every semantic reference remains resolvable across the lifecycle of the knowledge-bearing object.

That is a genuine domain invariant.

---

# 328.35 Aggregate boundary implication

We should **not** conclude that all referenced relations belong to one aggregate.

That would violate DDD aggregate principles.

Instead:

$$
AggregateIdentity
$$

and:

$$
KernelReference
$$

remain distinct.

A relation may reference another aggregate by stable identity without owning its lifecycle.

Therefore:

$$
\boxed{
Reference\neq\AggregateOwnership.
}
$$

This is important for a scalable KnowledgeOS implementation.

---

# 328.36 Distributed architecture implication

The same principle applies across nodes:

$$
Node_A
\leftrightarrow
Node_B.
$$

A relation can reference an instance located elsewhere provided:

$$
IID
$$

remains globally resolvable under the declared identity namespace.

The Kernel does not need centralized storage to preserve identity.

Therefore:

$$
\boxed{
Distributed\ storage
\not\Rightarrow
distributed\ identity\ ambiguity.
}
$$

---

# 328.37 Important boundary: resolvability versus availability

A referenced object might temporarily be unavailable because:

* network failure;
* service outage;
* replica lag;
* external dependency failure.

That does not necessarily mean:

$$
RefInvalid.
$$

We must distinguish:

$$
ResolvableInPrinciple
$$

from:

$$
CurrentlyAvailable.
$$

Thus:

$$
\boxed{
AvailabilityFailure\neq ReferentialInvalidity.
}
$$

This prevents infrastructure failures from corrupting epistemic semantics.

---

# 328.38 New invariant

We can therefore refine:

$$
I_{ref}
$$

into:

$$
\boxed{
\begin{aligned}
I_{ref1}&:\text{stable identity}\\
I_{ref2}&:\text{no identity reuse}\\
I_{ref3}&:\text{reference closure}\\
I_{ref4}&:\text{historical resolvability}\\
I_{ref5}&:\text{representation-preserving identity mapping}.
\end{aligned}
}
$$

These are related but not identical requirements.

---

# 328.39 What we have actually proven

The strongest defensible result is:

$$
\boxed{
ID
$$

is not merely necessary for creating relation instances.

It is necessary for preserving **referential continuity** across:

$$
\boxed{
creation,\ retraction,\ supersession,\ provenance,\ conflict,\ replay,\ merge,\ migration.
}
$$

This is stronger than the earlier Step 308 result.

---

# 328.40 Updated Kernel structure

The current candidate becomes:

$$
\boxed{
\mathcal K_F=
(
ID,
\mathcal R^\star,
\mathcal L_K,
\vdash
)
}
$$

with structural invariants:

$$
\boxed{
I_{struct}
=
I_{type}\land I_{ref}.
}
$$

The next layer is:

$$
I_{semantic}
$$

covering:

* historical preservation;
* provenance preservation;
* conflict preservation;
* transition soundness;
* dependency integrity.

---

# 328.41 Verdict

## **PASS — Referential Preservation**

We have a general theorem schema:

$$
\boxed{
RefClosed_H(K)
\land
Admissible(r)
\Rightarrow
RefClosed_H(K').
}
$$

under explicitly stated identity/reference premises.

Combined with Step 327:

$$
\boxed{
TypeOK(K)\land RefClosed_H(K)
\Rightarrow
TypeOK(K')\land RefClosed_H(K').
}
$$

No new Kernel primitive was discovered.

The result strengthens the current minimal basis:

$$
\boxed{
ID+\text{Typed Law-Bearing Relations}+\text{Semantic Interpretation}.
}
$$

---

# Step 329 — Integrated Kernel Invariant Preservation

We are now ready for the first genuinely significant soundness test.

Define:

$$
\mathcal I_K=
\{
I_{type},
I_{ref},
I_{hist},
I_{prov},
I_{conf},
I_{trans},
I_{dep}
\}.
$$

The target is:

$$
\boxed{
I_K(K)
\land
\Gamma\vdash K\xrightarrow rK'
\Rightarrow
I_K(K').
}
$$

This should **not** be attacked as one giant theorem immediately.

The correct proof decomposition is:

$$
\boxed{
\begin{aligned}
Type &\rightarrow Preserve\\
Reference &\rightarrow Preserve\\
History &\rightarrow Preserve\\
Provenance &\rightarrow Preserve\\
Conflict &\rightarrow Preserve\\
Transition &\rightarrow Preserve\\
Dependencies &\rightarrow Preserve.
\end{aligned}
}
$$

Then use conjunction:

$$
I_K=
I_{type}\land I_{ref}\land I_{hist}\land\cdots
$$

to obtain the integrated theorem.

The critical question in Step 329 will be:

> **Are these invariants genuinely independent, or can some of them be reconstructed from the others?**

That is the next reduction test—and it is important because we should not turn the invariant register itself into another accidental collection of primitives.
