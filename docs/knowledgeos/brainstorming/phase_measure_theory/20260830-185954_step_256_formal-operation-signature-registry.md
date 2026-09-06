# Step 256 — Formal Operation Signature Registry

Step 255 established that the final Knowledge State cannot be derived by minimizing a tuple before the **mandatory transformation language** is known.

Therefore Step 256 fixes the next object of study:

$$
\boxed{\mathcal T=\text{the formally typed operation family of KnowledgeOS}}
$$

The purpose is to determine exactly what operations the theory must support and, for each operation, **what information it requires**.

This is a prerequisite for the congruence test.

---

## 256.1 The basic form

Every KnowledgeOS transformation must eventually have a signature of the form:

$$
T_i:
D_i
\rightarrow
K_i
$$

or, where external context is required,

$$
T_i:
K\times P_i
\rightarrow
K'.
$$

Here:

* \(K\) = Knowledge State;
* \(D_i\) = admissible domain for operation \(i\);
* \(P_i\) = explicit transformation context/parameters;
* \(K'\) = resulting state.

We must **not** force every operation into:

$$
T:K\rightarrow K
$$

because Step 255 already demonstrated that some operations may depend on external policy, authority, time, evidence or other typed context.

---

# 256.2 The operation registry

The current historical corpus gives us the following candidate operation vocabulary:

$$
\boxed{
Add,\ Remove,\ Revise,\ Transform,\ Supersede,\ Merge,\ Split,\ Reject,\ Withdraw
}
$$

However, the previous audit established that only a subset currently has sufficiently explicit signatures. Therefore this table is a **reconstruction target**, not a claim that all nine are already formally defined.

| Operation | Intended role                  | Formal status |
| --------- | ------------------------------ | ------------- |
| Add       | introduce knowledge            | 🟡            |
| Remove    | eliminate knowledge            | 🟡            |
| Revise    | alter existing knowledge       | 🟡            |
| Transform | derive/change state            | 🟢 strongest  |
| Supersede | replace prior knowledge        | 🟡            |
| Merge     | combine knowledge              | 🟡            |
| Split     | decompose knowledge            | 🟡            |
| Reject    | mark inadmissible/non-accepted | 🟡            |
| Withdraw  | retract availability/validity  | 🟡            |

The historical audit specifically noted that five of nine operations lacked typed signatures in the latest formal pass. 

Therefore we must resist the temptation to treat the names themselves as formal definitions.

---

# 256.3 Operation taxonomy

The nine operations should first be separated by semantic role.

### Creation

$$
Add
$$

### Destruction / removal

$$
Remove
$$

### Modification

$$
Revise
$$

### Derivation

$$
Transform
$$

### Replacement

$$
Supersede
$$

### Combination

$$
Merge
$$

### Decomposition

$$
Split
$$

### Epistemic disposition

$$
Reject
$$

### Lifecycle disposition

$$
Withdraw
$$

This classification is useful because the operations are unlikely to have identical mathematical structure.

---

# 256.4 Add

The simplest candidate is:

$$
Add:
K\times x
\rightarrow
K'.
$$

But even this is not mathematically complete.

We need:

$$
Precondition_{Add}(K,x).
$$

For example:

$$
x\notin K
$$

might be required.

Or perhaps duplicates are allowed but produce an identity-equivalent state.

Thus two possibilities exist:

### Set-like semantics

$$
Add(K,x)=K\cup\{x\}.
$$

### Identity-aware semantics

$$
Add(K,x)
$$

may reject an object whose identity already exists.

Therefore:

$$
\boxed{
Add\text{ requires a definition of identity/equality.}
}
$$

This directly links Step 256 back to the unresolved \(Equality_K\).

---

# 256.5 Remove

Candidate:

$$
Remove:
K\times id
\rightarrow
K'.
$$

But removal immediately raises another question:

Does removal mean:

$$
x\notin K'
$$

or:

$$
x\in K'
\quad\text{with status }Withdrawn?
$$

These are radically different semantics.

If:

$$
Remove(x)
$$

destroys the object, then history may be required for audit.

If it changes epistemic/lifecycle state, then the object remains part of the state.

Therefore:

$$
\boxed{
Remove\neq Withdraw
}
$$

unless the vocabulary explicitly decides otherwise.

---

# 256.6 Revise

Candidate:

$$
Revise:
K\times x'
\rightarrow
K'.
$$

But revision requires identity preservation:

$$
id(x')=id(x)?
$$

If yes:

$$
Revision(x,x')
$$

is a state transition on one entity.

If no:

$$
Revision
$$

is actually:

$$
Remove(x)+Add(x').
$$

Therefore the operation cannot be formally defined until the theory answers:

$$
\boxed{
\text{What makes two knowledge objects "the same object" across revision?}
}
$$

This is another reason why equality and identity cannot be postponed indefinitely.

---

# 256.7 Transform

Transformation currently has the strongest historical support.

The historical analysis found Transformation in eight of nine kernel phases. 

The candidate form is:

$$
Transform:
K\times p
\rightarrow
K'.
$$

But the parameter \(p\) must be typed.

For example:

$$
p=(Rule,Evidence,Policy,Authority,Time,\ldots).
$$

We must not simply write:

$$
Transform(K)
$$

if actual transformations require contextual information.

The formal question is:

$$
\boxed{
What is the minimum typed input required by Transform?
}
$$

---

# 256.8 Supersede

Candidate:

$$
Supersede:
K\times x_{old}\times x_{new}
\rightarrow
K'.
$$

The operation must establish:

$$
x_{new}\succ x_{old}.
$$

But this relation may require:

* identity;
* provenance;
* authority;
* effective time;
* reason.

Thus Supersede is a particularly valuable test for whether provenance/history belongs in the semantic state.

---

# 256.9 Merge

Candidate:

$$
Merge:
K\times x_1\times x_2
\rightarrow
K'.
$$

The result:

$$
x_3=Merge(x_1,x_2).
$$

But a complete representation may need:

$$
Provenance(x_3)=
\{Provenance(x_1),Provenance(x_2)\}.
$$

If so, Merge is inherently provenance-preserving.

This means that the operation registry itself can reveal whether provenance is operationally essential.

---

# 256.10 Split

Candidate:

$$
Split:
K\times x
\rightarrow
K'.
$$

with:

$$
Split(x)=\{x_1,\ldots,x_n\}.
$$

The key invariant is:

$$
\bigcup_i Meaning(x_i)
$$

must preserve whatever semantics are required from \(x\).

This is not simply:

$$
x\rightarrow\{x_1,x_2\}
$$

because we need a semantic preservation criterion.

Thus:

$$
\boxed{
Split\text{ requires a preservation invariant.}
}
$$

---

# 256.11 Reject

Reject is particularly dangerous because it may be confused with deletion.

Candidate:

$$
Reject:
K\times x\times Reason
\rightarrow
K'.
$$

Possible semantics:

$$
Status(x):Proposed\rightarrow Rejected.
$$

Then \(x\) remains represented.

Therefore:

$$
Reject(x)\neq Remove(x).
$$

This is an important Ubiquitous Language distinction.

---

# 256.12 Withdraw

Candidate:

$$
Withdraw:
K\times x\times Reason
\rightarrow
K'.
$$

Potentially:

$$
Status(x):Accepted\rightarrow Withdrawn.
$$

Again, this means:

$$
x\in K'
$$

but its lifecycle/availability changes.

Consequently:

$$
Withdraw\neq Remove.
$$

If the theory intends otherwise, that must be explicitly decided rather than inferred.

---

# 256.13 The registry must include more than signatures

A valid operation registry therefore needs at least:

$$
R(T_i)=
(Name,
Domain,
Codomain,
Preconditions,
Postconditions,
Invariants,
Parameters,
Dependencies,
Determinism,
ProvenanceDependency,
HistoryDependency).
$$

We should add:

$$
PolicyDependency
$$

and:

$$
IdentityDependency.
$$

Thus:

$$
\boxed{
R(T_i)=
(Name,Domain,Codomain,Pre,Post,Inv,Params,Deps,Det,Prov,Hist,Policy,Identity)
}
$$

becomes the working registry schema.

---

# 256.14 Why determinism matters

For:

$$
T:K\rightarrow K'
$$

to be deterministic:

$$
T(k)=k'
$$

must produce one result.

But if:

$$
T(k,\text{current time})
$$

is used without time being an explicit input, the apparent transformation may be nondeterministic from the mathematical model's perspective.

Therefore:

$$
\boxed{
\text{Every semantically relevant external variable must be explicit.}
}
$$

For example:

$$
T:
K\times Policy\times Time
\rightarrow
K'.
$$

This is preferable to hiding:

$$
Policy
$$

or:

$$
Time
$$

inside an implicit global context.

---

# 256.15 Determinism does not mean immutability

A transformation may be deterministic:

$$
T(k,p)=k'
$$

while the overall system remains mutable.

Determinism concerns:

$$
\text{same inputs}\Rightarrow\text{same result}.
$$

It does not imply:

$$
K_t=K_{t+1}.
$$

Thus:

$$
\boxed{
Deterministic\ Transformation
\neq
Immutable\ Knowledge.
}
$$

---

# 256.16 Preconditions

Every partial operation needs a domain.

Instead of:

$$
Add:K\times X\rightarrow K,
$$

we may need:

$$
Add:
D_{Add}\subseteq K\times X
\rightarrow K.
$$

Likewise:

$$
Merge:
D_{Merge}\subseteq K\times X\times X
\rightarrow K.
$$

This gives a mathematically cleaner representation of invalid operations.

For example:

$$
(x_1,x_2)\notin D_{Merge}
$$

means the merge is not admissible.

This is preferable to pretending that every operation is total.

The corpus already identified the governed transformation family as potentially **partial**, rather than a total algebra. 

---

# 256.17 Partiality is not failure

A partial operation means:

$$
T(x)
$$

is undefined outside its admissible domain.

This is different from:

$$
T(x)=Error.
$$

Mathematically:

$$
x\notin Dom(T)
$$

may be the correct representation.

Operationally, the implementation may return an error.

Thus:

$$
\boxed{
\text{Domain restriction is part of the mathematical semantics.}
}
$$

---

# 256.18 Operation composition

Once operations are typed, composition becomes meaningful.

If:

$$
T_1:K_1\rightarrow K_2
$$

and:

$$
T_2:K_2\rightarrow K_3,
$$

then:

$$
T_2\circ T_1
$$

is potentially defined.

But only if:

$$
Codomain(T_1)\cap Domain(T_2)\neq\varnothing.
$$

Therefore we should **not** assume closure:

$$
T_i,T_j\in\mathcal T
\Rightarrow
T_j\circ T_i\in\mathcal T.
$$

The earlier audit already found that the governed transformation subset is not demonstrated closed under composition. 

---

# 256.19 Typed operations may have different state spaces

This is an important refinement.

Instead of:

$$
T:K\rightarrow K,
$$

we may require:

$$
T_{Add}:K\times X\rightarrow K
$$

$$
T_{Merge}:K\times X\times X\rightarrow K
$$

$$
Validate:K\times E\rightarrow Result
$$

$$
Approve:Candidate\times Authority\rightarrow ApprovedCandidate.
$$

Therefore KnowledgeOS may be better modeled as a **typed transition system** than as a single algebra.

This is consistent with the corpus's current strongest mathematical candidate. 

---

# 256.20 Validation is not Transformation

This distinction must remain explicit.

For example:

$$
Validate:
K\times Evidence
\rightarrow
ValidationResult.
$$

while:

$$
Transform:
K\times Rule
\rightarrow
K'.
$$

Validation may determine:

$$
Valid/Invalid
$$

without changing:

$$
K.
$$

Therefore:

$$
\boxed{
Validate\not\equiv Transform.
}
$$

This prevents a common category error in the kernel.

---

# 256.21 Command is not Transformation

Likewise:

$$
Command
$$

is an intent/request.

Transformation is the semantic state change.

Thus:

$$
Command
\rightarrow
Decision
\rightarrow
Transformation
\rightarrow
NewState
$$

may be an appropriate workflow.

The command itself should not automatically be treated as part of \(K\).

---

# 256.22 Event is not Transformation

Likewise:

$$
Event
$$

records an occurrence.

Transformation:

$$
T
$$

defines state evolution.

Therefore:

$$
Event\neq T.
$$

The corpus already distinguishes:

$$
Command\rightarrow Transformation\rightarrow Event.
$$



This distinction becomes essential when reconstructing provenance.

---

# 256.23 Provenance dependency flag

Every operation should explicitly declare:

$$
ProvDep(T_i)\in\{0,1,?\}.
$$

Where:

* \(0\) = provenance irrelevant;
* \(1\) = provenance semantically required;
* \(?\) = unresolved.

Likewise:

$$
HistDep(T_i)
$$

and:

$$
PolicyDep(T_i).
$$

This gives us a way to empirically determine where history must survive.

---

# 256.24 The critical operation

The most important operation for the next stage is:

$$
\boxed{
Supersede
}
$$

because it naturally tests:

* identity;
* temporal ordering;
* provenance;
* epistemic status;
* state replacement;
* history.

If two histories produce the same visible state but different supersession lineage, we can determine whether the difference is semantically observable.

---

# 256.25 The second critical operation

The second is:

$$
\boxed{
Merge
}
$$

because Merge forces us to ask whether the result retains:

$$
Source_1,\ Source_2.
$$

If yes, provenance becomes operationally relevant.

If no, we need a principled reason why source identity may be discarded.

---

# 256.26 The third critical operation

The third is:

$$
\boxed{
Revise
}
$$

because it forces the identity question:

$$
x_t=x_{t+1}\ ?
$$

If revision preserves identity:

$$
id(x_t)=id(x_{t+1}),
$$

then identity survives state evolution.

If not, revision becomes replacement.

This distinction affects the mathematical representation of Knowledge State.

---

# 256.27 Candidate registry — current version

We can now record a provisional registry.

| Operation | Candidate signature              | Key unresolved dependency     |
| --------- | -------------------------------- | ----------------------------- |
| Add       | \(K\times X\to K'\)              | identity, duplicate semantics |
| Remove    | \(K\times ID\to K'\)             | deletion vs lifecycle         |
| Revise    | \(K\times X'\to K'\)             | identity preservation         |
| Transform | \(K\times P\to K'\)              | exact \(P\)                   |
| Supersede | \(K\times X\times X\to K'\)      | identity, provenance, time    |
| Merge     | \(K\times X\times X\to K'\)      | provenance preservation       |
| Split     | \(K\times X\to K'\)              | semantic preservation         |
| Reject    | \(K\times X\times Reason\to K'\) | epistemic status              |
| Withdraw  | \(K\times X\times Reason\to K'\) | lifecycle semantics           |

This is **not yet an accepted mathematical specification**.

It is the working reconstruction.

---

# 256.28 Operation-to-information dependency matrix

The next useful artifact is:

| Operation | Identity | Evidence | Provenance | Authority | Policy | History |
| --------- | -------: | -------: | ---------: | --------: | -----: | ------: |
| Add       |        ? |        ? |          ? |         ? |      ? |       ? |
| Remove    |        ✓ |        – |          ? |         ? |      ? |       ? |
| Revise    |        ✓ |        ? |          ? |         ? |      ? |       ? |
| Transform |        ✓ |        ? |          ? |         ? |     ✓? |       ? |
| Supersede |        ✓ |        ? |         ✓? |         ? |      ? |      ✓? |
| Merge     |        ✓ |        ? |         ✓? |         ? |      ? |      ✓? |
| Split     |        ✓ |        ? |          ? |         ? |      ? |       ? |
| Reject    |        ✓ |        ? |          ? |         ? |     ✓? |       ? |
| Withdraw  |        ✓ |        ? |          ? |        ✓? |     ✓? |       ? |

The `?` values are intentional.

They are unresolved research questions, not assumptions.

---

# 256.29 Why this matrix is important

This matrix allows us to transform the vague question:

> "Does provenance belong in \(K\)?"

into an operational question:

$$
\exists T\in\mathcal T:
ProvDep(T)=1?
$$

If yes, then provenance-sensitive information must be available to that operation.

We can then determine whether it must be:

$$
K\text{-resident},
$$

$$
T\text{-parameter},
$$

or:

$$
External\ Context.
$$

This is much more rigorous.

---

# 256.30 Kernel reconstruction now becomes constrained

The eventual kernel must satisfy:

$$
K
\supseteq
RequiredInformation(\mathcal T).
$$

But:

$$
RequiredInformation(\mathcal T)
$$

is not simply the union of every parameter.

Instead, we need to distinguish:

$$
StateInformation
$$

from:

$$
ContextInformation.
$$

Thus:

$$
Required(T)
=
StateReq(T)
\cup
ContextReq(T).
$$

Only:

$$
StateReq(T)
$$

constrains \(K\).

---

# 256.31 The key optimization problem

We can now formulate the eventual minimization problem:

Find:

$$
K^*
$$

such that:

$$
\forall T_i\in\mathcal T:
\quad
T_i
$$

is well-defined using:

$$
K^*
$$

and its explicitly declared external parameters.

Among all such states:

$$
\boxed{
K^*=\arg\min_{K}
Complexity(K)
}
$$

subject to semantic sufficiency.

This is the proper mathematical target.

---

# 256.32 Step-256 result

We have therefore established the following:

### Established

$$
\boxed{
\mathcal T\text{ must be typed before }K\text{ can be minimized.}
}
$$

### Established

$$
\boxed{
Operations may be partial.
}
$$

### Established

$$
\boxed{
Operations need not share one homogeneous signature.
}
$$

### Established

$$
\boxed{
Validation, Command, Event and Transformation must not be conflated.
}
$$

### Established

$$
\boxed{
Provenance/History/Policy dependence must be explicitly tested per operation.
}
$$

### Not established

$$
\boxed{
\text{the nine candidate operations are the final operation set}.
}
$$

### Not established

$$
\boxed{
\text{any particular operation signature is final}.
}
$$

---

# 256.33 Step-256 Gate

The operation registry is now sufficiently structured to proceed, but it is **not yet closed**.

The most important unresolved signatures are:

$$
Supersede
$$

$$
Merge
$$

$$
Revise
$$

$$
Transform.
$$

These operations have the highest potential to distinguish competing state/history models.

Therefore the next step should not yet return to general kernel theory.

---

# Step 257 — Formalize the Four Discriminating Operations

The next step should construct complete mathematical definitions for:

$$
\boxed{
Revise,\quad Transform,\quad Supersede,\quad Merge
}
$$

including for each:

1. domain;
2. codomain;
3. preconditions;
4. postconditions;
5. identity behavior;
6. provenance behavior;
7. history dependence;
8. policy dependence;
9. determinism;
10. composability.

Then we can run the first **real congruence experiment**:

$$
H_1\sim_F H_2
$$

and determine whether:

$$
T(H_1)\neq T(H_2).
$$

That will provide substantially stronger evidence about the final form of \(K\) than adding another candidate kernel tuple.
