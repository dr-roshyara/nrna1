# R594 — Minimal Support, Hypergraph and Semantic Equivalence

I first reviewed the attached R593 implementation. There is an important correction to make before we build further on it. 

R593 was useful, but its XOR example exposed a semantic problem in the definition of **support**. I therefore treated R594 as an adversarial review of R593 rather than simply extending it.

[Download the R594 executable benchmark](sandbox:/mnt/data/knowledgeos_r594_minimal_support_hypergraph_semantics.py)

## 1. The important correction

R593 effectively allowed:

$$
\{A\}
$$

to be a support for:

$$
Z=A\oplus B.
$$

That is not correct if \(B\) is unknown.

For example:

$$
A=0,B=0\Rightarrow Z=0
$$

but:

$$
A=0,B=1\Rightarrow Z=1.
$$

Both states have the same value of \(A\), but different values of \(Z\).

Therefore:

$$
\boxed{\{A\}\text{ does not determine }A\oplus B.}
$$

The R594 executable test explicitly refutes the R593 interpretation.

This is exactly the kind of correction we want from the KnowledgeOS methodology:

$$
Discover\rightarrow Formalize\rightarrow Test\rightarrow Refute\rightarrow Reduce.
$$

---

# 2. We discovered that "support" actually has two meanings

This is the most important result of R594.

### A. Factor support

A set of variables/factors:

$$
S=\{A,B\}.
$$

It tells us **which factors participate**.

### B. Conditional support

A set of value-bearing conditions:

$$
S=\{A=1,B=1\}.
$$

It tells us **which factors and which states jointly suffice**.

These are not equivalent.

---

# 3. New precise term: Literal

A **Literal** is a variable together with an asserted value.

For example:

$$
A=1
$$

is a literal.

Likewise:

$$
B=0.
$$

A conditional support is then a set of literals:

$$
S=\{A=1,B=1\}.
$$

This is much more expressive than:

$$
\{A,B\}.
$$

---

# 4. New precise term: Sufficient Condition

A condition \(S\) is sufficient for target value \(z\) when:

$$
\boxed{
\forall x:
S(x)\Rightarrow Z(x)=z
}
$$

within the declared:

$$
(\Gamma,C,\Sigma)
$$

regime, contract and scope.

Example:

$$
Z=A\land B.
$$

Then:

$$
A=1\land B=1
$$

is sufficient for:

$$
Z=1.
$$

Similarly:

$$
A=0
$$

is sufficient for:

$$
Z=0.
$$

because if \(A=0\):

$$
A\land B=0
$$

regardless of \(B\).

---

# 5. Minimal sufficient condition

A sufficient condition is **minimal** if removing any literal destroys sufficiency:

$$
Minimal(S,Z=z)
\iff
Sufficient(S,Z=z)
\land
\forall S'\subsetneq S:
\neg Sufficient(S',Z=z).
$$

This is more precise than the previous factor-only formulation.

---

# 6. OR example

Consider:

$$
Z=(A\land B)\lor(C\land D).
$$

For:

$$
Z=1
$$

we have two alternative minimal sufficient conditions:

$$
S_1=\{A=1,B=1\}
$$

and:

$$
S_2=\{C=1,D=1\}.
$$

Therefore:

$$
MinSupports(Z=1)=\{S_1,S_2\}.
$$

This correctly preserves the alternative explanations.

---

# 7. XOR example

For:

$$
Z=A\oplus B
$$

the minimal sufficient conditions are:

For \(Z=0\):

$$
\{A=0,B=0\}
$$

and:

$$
\{A=1,B=1\}.
$$

For \(Z=1\):

$$
\{A=0,B=1\}
$$

and:

$$
\{A=1,B=0\}.
$$

So:

$$
\boxed{
XOR
\text{ requires value-bearing support.}
}
$$

This is a significant correction.

---

# 8. Why an ordinary hypergraph is not enough

Suppose we represent:

$$
Z=A\land B
$$

with the hyperedge:

$$
\{A,B\}\rightarrow Z.
$$

Now consider:

$$
Z=\neg A\land B.
$$

The factor hyperedge is still:

$$
\{A,B\}\rightarrow Z.
$$

But the semantics are completely different.

Therefore:

$$
Hypergraph_{factor}(Z_1)
=
Hypergraph_{factor}(Z_2)
$$

does **not** imply:

$$
Semantics(Z_1)=Semantics(Z_2).
$$

The benchmark proves this finite counterexample.

Hence:

$$
\boxed{
\text{Factor hypergraph is a dependency projection, not the epistemic semantics.}
}
$$

This directly reinforces the KnowledgeOS invariant:

$$
Representation\neq Reality.
$$

---

# 9. New concept: Conditional Hyperedge

A **Conditional Hyperedge** can therefore be represented conceptually as:

$$
H=(L,T,v)
$$

where:

* \(L\) = set of literals;
* \(T\) = target;
* \(v\) = target value/conclusion.

Example:

$$
(\{A=1,B=1\},Z,1).
$$

This preserves information that the ordinary hyperedge:

$$
(\{A,B\},Z)
$$

loses.

---

# 10. But we should NOT immediately make ConditionalHyperedge a Kernel primitive

This is an important architectural optimization.

We have demonstrated that **factor-only hypergraphs are insufficient** for the tested Boolean semantics.

But that does not mean the Kernel should now contain:

```text
ConditionalHyperedge
```

That would be premature.

The correct architecture remains:

$$
L0\ Kernel
$$

minimal.

The richer representation belongs in the formal/epistemic fabric.

A possible implementation model is:

```text
Dependency
    ↓
SupportCondition
    ↓
MinimalSupport
    ↓
DependencyAssessment
```

where:

```text
SupportCondition
    ├── factors
    ├── values / predicates
    ├── target
    ├── regime
    └── contract
```

A hypergraph can then be an **index/optimization representation**, not the semantic authority.

---

# 11. Major conceptual refinement

We now have three different things:

### Dependency

> There is some relevant relationship between factors and a target.

### Sufficient condition

> Under these conditions, the target follows.

### Minimal sufficient condition

> These conditions are sufficient, and none can be removed.

Thus:

$$
\boxed{
Dependency
\neq
SufficientCondition
\neq
MinimalSufficientCondition.
}
$$

This separation is essential for real-world application.

---

# 12. Relation to KnowledgeOS dependency order

We can now improve R592.

Previously:

$$
ord(D)=|\operatorname{MinSupport}(D)|.
$$

We should refine this to:

$$
\boxed{
ord(D\mid Z,\Gamma,C,\Sigma)
=
\max_{S\in MS(Z)}
|Factors(S)|
}
$$

where:

$$
MS(Z)
$$

is the set of verified minimal sufficient conditions relevant to the target.

Why maximum?

Because if:

$$
MS(Z)=
\left\{
\{A,B\},
\{C,D,E\}
\right\},
$$

then the system must not claim:

$$
ord(D)\le2.
$$

There exists a relevant three-factor minimal condition.

Therefore:

$$
ord(D)=3
$$

for the conservative completeness interpretation.

---

# 13. New problem: context dependence

This now exposes another important issue.

Suppose:

$$
Z=A\land B.
$$

Globally:

$$
\{A=1,B=1\}
$$

is required for \(Z=1\).

But suppose our current context already establishes:

$$
A=1.
$$

Then relative to the current epistemic state:

$$
B=1
$$

is sufficient to establish:

$$
Z=1.
$$

Therefore:

$$
MinimalSupport_{global}
\neq
MinimalSupport_{context}.
$$

This means minimal support must be indexed by:

$$
Context,\ Regime,\ Contract,\ Target,\ Scope.
$$

This fits perfectly with the existing KnowledgeOS principle:

$$
\boxed{
\text{Every nontrivial epistemic claim is relative to explicit target, context, contract, regime and scope.}
}
$$

---

# 14. Statistical interpretation

This also matters for ML.

Suppose an ML model finds:

$$
A,B\rightarrow Z.
$$

That may represent:

* correlation;
* predictive sufficiency;
* causal sufficiency;
* conditional sufficiency;
* merely a useful feature subset.

These are not equivalent.

Therefore the feature importance of a model cannot automatically become:

$$
MinimalSupport.
$$

For example:

$$
SHAP(A)>0
$$

does not establish:

$$
A\rightarrow Z.
$$

Likewise:

$$
FeatureSelection(A,B)
$$

does not establish:

$$
MinimalSufficientCondition(A,B).
$$

The ML firewall remains essential.

---

# 15. ML's correct role after R594

A good architecture is:

```text
Raw Evidence
      ↓
ML Candidate Generation
      ↓
Candidate Support / Interaction
      ↓
Deterministic Semantic Validation
      ↓
Sufficiency Assessment
      ↓
Minimality Assessment
      ↓
Completeness Assessment
      ↓
Certificate
```

ML can dramatically reduce the candidate search space.

But the final negative claim:

> "No additional relevant minimal support exists"

requires a completeness argument.

This is the same recurring KnowledgeOS pattern:

$$
\boxed{
Candidate\neq Assessment\neq Certificate.
}
$$

---

# 16. New candidate invariants

### I-C44 — Factor Hypergraph Is Not Semantic Complete

$$
Hypergraph_{factor}(K_1)=Hypergraph_{factor}(K_2)
\not\Rightarrow
Sem(K_1)=Sem(K_2).
$$

### I-C45 — Minimal Support Is Context/Regime/Contract Indexed

$$
MS\neq MS(Z)
$$

alone.

More correctly:

$$
MS(Z\mid Context,\Gamma,C,\Sigma).
$$

### I-C46 — Conditional Information Must Be Preserved

If target semantics depend on factor values, a representation containing only factor identities is insufficient.

### I-C47 — ML Support Candidate Is Not Verified Support

$$
MLCandidateSupport
\not\Rightarrow
VerifiedMinimalSupport.
$$

These should remain candidate invariants for now.

---

# 17. Important consequence for the final architecture

R594 suggests we should **not** build KnowledgeOS around:

> "dependency graph"

or:

> "dependency hypergraph."

Those are representations.

The deeper abstraction is:

$$
\boxed{
\textbf{Target-relative sufficient information conditions}
}
$$

A graph/hypergraph can be an implementation optimization.

This is a substantial architectural simplification.

Instead of:

```text
Graph BC
Hypergraph BC
HigherOrderDependency BC
Interaction BC
```

we retain one conceptual dependency model and allow multiple representations.

---

# 18. What R594 has actually established

Finite executable evidence now shows:

* factor-only support is insufficient for tested value-sensitive dependencies;
* R593's XOR singleton interpretation was incorrect;
* OR requires alternative sufficient conditions;
* XOR requires value-bearing conditions;
* factor-only hypergraphs can collapse different semantics;
* conditional/value-bearing representation preserves the tested semantic distinction;
* ML remains candidate-only.

The benchmark passes all these tests.

---

# 19. The next step is now deeper

R594 has exposed something more fundamental than another dependency type.

We need to determine whether **minimal sufficient conditions themselves are complete enough to represent dependency semantics**.

Consider a probabilistic relationship:

$$
P(Z\mid A,B)>P(Z\mid A)
$$

There may be no deterministic sufficient condition at all.

Or consider:

$$
Z=f(A,B,C)
$$

where the relationship is probabilistic, causal, logical, or merely evidential.

Therefore we must not silently force every KnowledgeOS dependency into Boolean logic.

---

# R595 — Dependency Semantics Beyond Deterministic Sufficiency

The next research question should be:

$$
\boxed{
\text{Is deterministic minimal sufficient support sufficient for the full KnowledgeOS dependency model?}
}
$$

We should test four regimes:

1. **Deterministic logical dependency**

   $$
   Z=f(A,B)
   $$

2. **Probabilistic dependency**

   $$
   P(Z\mid A,B)\neq P(Z)
   $$

3. **Causal dependency**

   $$
   P(Z\mid do(A))\neq P(Z)
   $$

4. **Epistemic/evidential dependency**
   where evidence changes an assessment without changing the underlying world state.

The crucial objective is to prevent us from making another premature universal abstraction.

We should determine whether these can share one typed dependency contract or whether they require distinct dependency semantics.

---

## Current progress

* **Foundational KnowledgeOS theory:** ~90% structurally mature.
* **Dependency/completeness theory:** now one of the strongest parts of the program.
* **Higher-order dependency:** demonstrated and formally constrained.
* **Minimal-support theory:** refined; R593's XOR issue corrected.
* **Representation architecture:** simplified; hypergraph should remain an implementation projection, not a Kernel primitive.
* **ML:** properly constrained to candidate discovery.
* **DDD architecture:** still **no new BC or Kernel primitive justified**.

### Remaining TODOs

* **R595:** deterministic vs probabilistic vs causal vs epistemic dependency.
* Establish the canonical typed dependency semantics.
* Revisit R592 order bounds under the richer semantics.
* Integrate dependency semantics with revalidation and VoI.
* Test larger W1–W7/higher-order benchmark.
* Finalize completeness certificates for `NOT_AFFECTED`.
* Architecture freeze only after the dependency semantics boundary is settled.
