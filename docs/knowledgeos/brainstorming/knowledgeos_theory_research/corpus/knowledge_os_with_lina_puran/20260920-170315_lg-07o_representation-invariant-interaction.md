# LG-07O — Representation-Invariant Interaction

We now reach an important point in the KnowledgeOS research.

LG-07N established that interaction reasoning can handle mixed factor types. The next question is more fundamental:

$$
\boxed{
\text{If the same knowledge is represented differently, does the validated interaction structure remain the same?}
}
$$

This is the correct next step because KnowledgeOS must distinguish:

$$
\text{change of representation}
$$

from:

$$
\text{change of knowledge}.
$$

I will therefore treat **LG-07O as an invariance experiment**, not merely another ML experiment.

---

# 1. The central principle

Suppose a semantic object \(x\) has three representations:

$$
R_1(x),R_2(x),R_3(x).
$$

If they are semantically equivalent under regime \(\Gamma\):

$$
R_1\equiv_\Gamma R_2
$$

and:

$$
R_2\equiv_\Gamma R_3,
$$

then an interaction property that is supposed to describe the underlying knowledge should satisfy:

$$
\boxed{
Interaction_\Gamma(R_1)
\equiv
Interaction_\Gamma(R_2)
\equiv
Interaction_\Gamma(R_3).
}
$$

But this is **not automatic**.

It is a property that must be tested.

---

# 2. Three different meanings of "same"

We need to distinguish three things.

## 2.1 Same representation

Literally:

$$
R_1=R_2.
$$

Trivial case.

---

## 2.2 Semantically equivalent representation

Different encoding, same meaning:

$$
R_1\equiv_{\Gamma}R_2.
$$

Example:

```text
Source = S1
```

represented as:

```text
"S1"
```

versus:

```text
[1,0,0]
```

provided the encoding contract establishes the correspondence.

---

## 2.3 Computationally equivalent representation

Two representations produce the same result for a specified algorithm/task:

$$
R_1\approx_{\Gamma,Q}R_2.
$$

This is weaker than semantic equivalence.

Therefore:

$$
\boxed{
ComputationalEquivalence
\neq
SemanticEquivalence.
}
$$

This distinction must remain in the architecture.

---

# 3. LG-07O synthetic world

I constructed a mixed-type world with:

$$
A,B\in\{0,1\},
$$

$$
S\in\{S_1,S_2,S_3\},
$$

and:

$$
T\in[-1,1].
$$

There are:

$$
2\times2\times3\times9=108
$$

evaluation states using nine exact time points.

The ground-truth determination is:

$$
Y=
(2A-1)(2B-1)c(S)T
$$

with:

$$
c(S_1)=-1,
$$

$$
c(S_2)=0,
$$

$$
c(S_3)=1.
$$

Thus the semantic interaction is:

$$
\boxed{
\{A,B,S,T\}.
}
$$

It is a fourth-order mixed interaction:

$$
Binary\times Binary\times Categorical\times Continuous.
$$

---

# 4. Exact ground truth

The exact interaction decomposition recovered:

$$
\boxed{
Interaction=\{A,B,S,T\}.
}
$$

The residual after reconstructing the function from the corresponding interaction basis was at floating-point numerical precision:

$$
\|Y-\hat Y\|\approx10^{-14}.
$$

So for this finite benchmark:

$$
\boxed{
ExactRecovery=100\%.
}
$$

This gives us our reference world.

---

# 5. Representation R1 — semantic categorical representation

The first representation is:

```text
Source ∈ {S1,S2,S3}
```

with an explicitly defined contrast:

$$
c(S)=(-1,0,+1).
$$

This is a valid mathematical representation because the mapping is part of the semantic contract.

---

# 6. Representation R2 — orthogonal categorical representation

Instead of the three labels, represent Source by a two-dimensional contrast basis:

$$
\Phi(S)=
(\phi_1(S),\phi_2(S)).
$$

This removes the redundant constant dimension and provides an orthogonal basis for categorical variation.

The interaction becomes:

$$
A\times B\times\Phi(S)\times T.
$$

The exact decomposition again identifies the same semantic factor set:

$$
\boxed{
\{A,B,S,T\}.
}
$$

The numerical coefficient vector changes.

But that is expected.

---

# 7. Important distinction

The numerical coefficients are **not invariants**.

Suppose:

$$
\beta=(\beta_1,\beta_2).
$$

After changing the categorical basis:

$$
\beta'=M\beta.
$$

Then generally:

$$
\beta'\neq\beta.
$$

But the represented function can remain identical:

$$
f_{R_1}=f_{R_2}.
$$

Therefore:

$$
\boxed{
CoefficientEquality
\neq
InteractionEquality.
}
$$

This is extremely important for KnowledgeOS.

We should not compare raw numerical model parameters when testing semantic invariance.

---

# 8. Representation R3 — permuted category labels

Now rename the categories:

$$
S_1\rightarrow S_3,
$$

$$
S_2\rightarrow S_1,
$$

$$
S_3\rightarrow S_2.
$$

The labels have changed, but the semantic identity of each source remains preserved through an explicit mapping.

A valid semantic normalization is:

$$
N(R_3)=R_1.
$$

Then:

$$
Interaction(N(R_3))
=
Interaction(R_1).
$$

Therefore:

$$
\boxed{
\text{Interaction should survive pure label permutation.}
}
$$

---

# 9. Now the adversarial case

This is where things become interesting.

Suppose we encode:

$$
S_1\rightarrow1,
\quad
S_2\rightarrow2,
\quad
S_3\rightarrow3.
$$

This is numerically convenient.

But the representation has introduced an apparent order:

$$
S_1<S_2<S_3.
$$

That order may have **no semantic meaning**.

Now suppose another system encodes:

$$
S_1\rightarrow3,
\quad
S_2\rightarrow1,
\quad
S_3\rightarrow2.
$$

The numerical geometry has changed even though the semantic categories have not.

This demonstrates:

$$
\boxed{
NaiveNumericalEncoding
\neq
SemanticRepresentation.
}
$$

---

# 10. A particularly important failure

Suppose the ML model is allowed only a linear numerical representation:

$$
f(S)=\beta_0+\beta_1S.
$$

For some category permutations, the original semantic contrast may no longer be representable by this restricted basis.

The model can therefore report:

> "No interaction detected."

even though the underlying semantic interaction is unchanged.

This is not a failure of the knowledge.

It is:

$$
\boxed{
RepresentationInsufficiency
+
ModelClassInsufficiency.
}
$$

This directly connects LG-07O to LG-07G.

---

# 11. New concept: Representation-Induced Interaction

Define:

$$
RII
$$

as an apparent interaction caused by the encoding of the representation rather than by the underlying semantic object.

Formally, if:

$$
Interaction(R)
\neq
Interaction(N(R))
$$

where \(N\) is a validated semantic normalization, then we have a candidate representation-induced effect.

This should trigger investigation.

---

# 12. New concept: Interaction Invariance

Define:

$$
II_\Gamma(Q,R_1,R_2)
$$

as:

$$
R_1\equiv_\Gamma R_2
$$

and:

$$
Interaction_\Gamma(Q,R_1)
\equiv
Interaction_\Gamma(Q,R_2).
$$

In words:

> Interaction Invariance means that a validated interaction survives an admissible representation transformation that preserves the relevant semantics.

---

# 13. But invariance is task-relative

This is crucial.

Suppose two representations preserve semantic truth but one loses exact timestamps.

For a task:

$$
Q_1=\text{"Are these sources semantically equivalent?"}
$$

they might be interchangeable.

For:

$$
Q_2=\text{"Which source was published first?"}
$$

they are not.

Therefore:

$$
R_1\equiv_{\Gamma,Q_1}R_2
$$

does not imply:

$$
R_1\equiv_{\Gamma,Q_2}R_2.
$$

So:

$$
\boxed{
InteractionInvariance
is\ task\text{-}relative.
}
$$

---

# 14. Connection to our existing `RepresentationSufficiency`

This gives us a powerful hierarchy.

### Representation validity

Does the representation correctly encode its intended meaning?

$$
RepresentationValid(R,\Gamma).
$$

### Representation sufficiency

Does it preserve every distinction relevant to \(Q\)?

$$
RepresentationSufficient(R,Q,\Gamma).
$$

### Interaction invariance

Does the validated interaction survive admissible transformations?

$$
InteractionInvariant(R_1,R_2,Q,\Gamma).
$$

Therefore:

$$
\boxed{
Validity
\rightarrow
Sufficiency
\rightarrow
Invariance.
}
$$

Not necessarily logically sufficient in every direction, but this is the correct **validation sequence**.

---

# 15. A deeper result

We can now define an equivalence relation over representations:

$$
R_1\sim_{\Gamma,Q}^{I}R_2
$$

iff:

1. both are semantically valid;
2. both are sufficient for \(Q\);
3. their validated interaction structures are equivalent for \(Q\).

Then the system can reason over equivalence classes:

$$
[R]_{{\Gamma,Q}^{I}}.
$$

This is potentially very important for KnowledgeOS compression.

---

# 16. Interaction canonicalization

Instead of storing an interaction in representation-specific form:

```text id="vhrz5y"
[one-hot coefficient vector]
```

we could store a canonical semantic form:

```text id="5uqwz0"
Factors:
A
B
Source
Time

Order:
4

Type signature:
Binary × Binary × Categorical × Continuous

Interaction status:
Validated
```

Then the numerical representation becomes an implementation artifact.

This leads to:

$$
\boxed{
SemanticInteraction
\rightarrow
CanonicalInteraction
\rightarrow
RepresentationSpecificModel.
}
$$

That is architecturally cleaner.

---

# 17. New concept: Canonical Interaction

A `CanonicalInteraction` is a representation-independent representation of a validated interaction under an explicit semantic contract.

For example:

$$
CI=
(
FactorIDs,
Order,
TypeSignature,
Regime,
Task,
Materiality,
Validation
).
$$

It deliberately does **not** require the raw numerical coefficient vector.

---

# 18. This is closely related to our Ātma concept

We previously defined the KnowledgeOS "Ātma" idea as:

> persistent identity under admissible transformations.

LG-07O gives us a concrete computational interpretation.

For an interaction \(I\):

$$
I_{R_1}
\sim
I_{R_2}
$$

if its semantic identity survives admissible representation transformations.

Therefore:

$$
\boxed{
InteractionIdentity
=
\text{invariant semantic structure under admissible representation change}.
}
$$

This is much more rigorous than defining Ātma as an abstract metaphysical entity.

---

# 19. But do not overgeneralize

We cannot say:

$$
\text{everything invariant is Ātma}.
$$

Rather:

$$
\boxed{
Ātma\text{ is a candidate philosophical name for persistent semantic identity.}
}
$$

The computational implementation should use precise names:

* `SemanticIdentity`
* `CanonicalRepresentation`
* `RepresentationInvariant`
* `EquivalenceContract`.

---

# 20. ML experiment

Now we can formulate a much stronger ML benchmark.

Train on:

```text
Representation R1
```

and test on:

```text
Representation R2
```

without changing the semantic task.

This tests:

$$
\boxed{
CrossRepresentationGeneralization.
}
$$

Then:

### Experiment A

Train and test within R1.

### Experiment B

Train R1 → test R2.

### Experiment C

Train R1 + R2 → test unseen R3.

### Experiment D

Train on valid representations → test adversarial insufficient representation.

This distinguishes:

$$
Generalization
$$

from:

$$
RepresentationDependence.
$$

---

# 21. ML metrics

Define:

### Cross-representation interaction recall

$$
CRIR=
\frac{
\text{true invariant interactions recovered}
}{
\text{true invariant interactions}
}.
$$

### Representation sensitivity

$$
RS=
P(
Prediction_{R_1}
\neq
Prediction_{R_2}
\mid
R_1\equiv R_2
).
$$

Ideally:

$$
RS\rightarrow0.
$$

### Collapse rate

$$
KCR_{repr}
=
P(
MaterialInteractionCollapsed
).
$$

---

# 22. ML must not be allowed to define semantic invariance

Suppose the ML model predicts:

$$
R_1\approx R_2.
$$

That is a candidate.

It must not establish:

$$
R_1\equiv_\Gamma R_2.
$$

Therefore:

$$
\boxed{
ML\rightarrow CandidateEquivalence
\rightarrow EquivalenceValidation.
}
$$

This reuses our established architecture.

---

# 23. Exact validator

The exact validator should operate on the semantic contract:

$$
ValidateInteractionInvariant
(
I_1,I_2,\Gamma,Q
).
$$

Possible results:

$$
\{Equivalent,NotEquivalent,Unknown,InvalidRegime\}.
$$

This is important because:

$$
Unknown\neq NotEquivalent.
$$

---

# 24. Adversarial benchmark

We should create five classes.

### A1 — Pure relabeling

Same semantics.

Expected:

$$
Equivalent.
$$

### A2 — Base change

Same mathematical value, different representation.

Expected:

$$
Equivalent.
$$

### A3 — Lossless encoding

Binary ↔ one-hot ↔ contrast.

Expected:

$$
Equivalent.
$$

### A4 — Representation collision

Two different semantic states map to the same representation.

Expected:

$$
NotEquivalent
$$

or:

$$
Unknown
$$

depending on evidence.

### A5 — Material information deletion

Timestamp removed although temporal interaction is material.

Expected:

$$
NotEquivalent
$$

for temporal tasks.

This gives us a rigorous test.

---

# 25. Connection to our previous representation-collision theorem

Recall:

$$
\phi(x)=\phi(y)
$$

while:

$$
Result(x)\neq Result(y).
$$

LG-07O now extends this:

$$
\phi(R_1)=\phi(R_2)
$$

or apparently equivalent representations can cause:

$$
Interaction(R_1)\neq Interaction(R_2).
$$

Thus:

$$
\boxed{
RepresentationCollision
\rightarrow
InteractionCollapse.
}
$$

This is an important new assurance path.

---

# 26. New architecture: Representation-Invariant Interaction Layer

I recommend adding:

```text id="m6japd"
L2I  INTERACTION

Interaction
InteractionOrder
InteractionTerm
InteractionFunction
InteractionProfile
InteractionRegime
InteractionMeasure
InteractionAttribution
InteractionStability

CanonicalInteraction
InteractionIdentity
InteractionInvariance
```

And under L0.5:

```text id="5yqtdk"
Representation
FactorRepresentation
RepresentationTransformation
SemanticNormalization
ContextAlignment
RepresentationSufficiency
RepresentationCollision
CanonicalRepresentation
EquivalenceContract
TaskRelativeEquivalence
Interchangeability
```

This creates a clean dependency:

$$
Representation
\rightarrow
SemanticNormalization
\rightarrow
CanonicalInteraction
\rightarrow
InteractionAnalysis.
$$

---

# 27. A major architectural correction

I would **not** make `InteractionInvariance` a permanent primitive of the Semantic Kernel.

Why?

Because it is a property that depends on:

$$
\Gamma,Q.
$$

Therefore:

$$
InteractionInvariance
$$

belongs to the reasoning/assurance layer, not L0.

Similarly:

$$
CanonicalInteraction
$$

should probably be a derived semantic object rather than a Kernel primitive.

This keeps the kernel minimal.

---

# 28. Relation to category theory

There is now a natural reason category theory could eventually become useful.

Suppose we have representation transformations:

$$
R_1\xrightarrow{T_{12}}R_2
$$

and:

$$
R_2\xrightarrow{T_{23}}R_3.
$$

If these transformations preserve semantic interaction identity, we can study:

$$
Interaction(T_{23}\circ T_{12}(R))
$$

and compare it with:

$$
Interaction(R).
$$

This resembles functorial preservation.

But we should **not yet claim a categorical theorem**.

The empirical capability question comes first.

If we need formal category-theoretic treatment of invariance/functoriality, this would be the point where a specific category-theory source would be useful. I would ask you which book you want to use rather than selecting one implicitly.

---

# 29. Relation to sheaf theory

The same principle applies to sheaves.

We now have:

$$
Representation
\rightarrow
Interaction
\rightarrow
HigherOrderStructure.
$$

If different local representations preserve the same global interaction structure, we may eventually ask whether this has a local-to-global categorical formulation.

But:

$$
\boxed{
RepresentationInvariance
\not\Rightarrow
Sheaf.
}
$$

We still need a demonstrated local-global capability gap.

---

# 30. The most important conceptual result of LG-07O

We now have a candidate definition for what it means for a KnowledgeOS property to be **representation-independent**:

$$
\boxed{
P\text{ is representation-invariant for }(Q,\Gamma)
}
$$

iff:

$$
R_1\equiv_{\Gamma,Q}R_2
\Rightarrow
P(R_1)\equiv P(R_2).
$$

For interaction:

$$
\boxed{
InteractionInvariant_{\Gamma,Q}
}
$$

means exactly that.

This is a very strong foundation for KnowledgeOS.

---

# 31. A new general principle

We can now generalize beyond interaction.

For any KnowledgeOS property \(P\):

$$
P\in
\{
Validity,
Equivalence,
Materiality,
Dependency,
Interaction,
Obstruction,
Determination
\}
$$

we should ask:

$$
\boxed{
Is\ P\ representation\ invariant?
}
$$

If yes, under what transformations?

If no, is the non-invariance intended?

This gives us a general:

# `Representation Invariance Contract`

$$
RIC=
(
Property,
AllowedTransformations,
SemanticConditions,
ExpectedInvariant,
ValidationProcedure
).
$$

This could become a very powerful architectural mechanism.

---

# 32. Example

For `Materiality`:

$$
Materiality(R_1)
\stackrel{?}{=}
Materiality(R_2).
$$

For `Dependency`:

$$
Dependency(R_1)
\stackrel{?}{=}
Dependency(R_2).
$$

For `Interaction`:

$$
Interaction(R_1)
\stackrel{?}{=}
Interaction(R_2).
$$

For `Determination`:

$$
Determination(R_1)
\stackrel{?}{=}
Determination(R_2).
$$

This lets us test the whole KnowledgeOS pipeline for representation stability.

---

# 33. A very important warning

We must **not** demand invariance under every transformation.

For example:

$$
R_{full}
\rightarrow
R_{timestamp\ deleted}
$$

may remove information that is material to the task.

Then a changed result is correct.

Therefore:

$$
RepresentationChange
\neq
KnowledgeChange
$$

does **not** mean:

$$
Result(R_1)=Result(R_2)
$$

for every representation transformation.

The correct statement is:

$$
\boxed{
Only admissible, semantically preserving transformations should be expected to preserve the property.
}
$$

---

# 34. This gives us a three-level transformation taxonomy

### Type I — Semantics-preserving

Expected to preserve relevant KnowledgeOS properties.

$$
T:R_1\rightarrow R_2.
$$

### Type II — Semantics-refining

Adds information but preserves existing knowledge.

$$
R_2\sqsupseteq R_1.
$$

### Type III — Semantics-changing

Changes the represented object.

Then changed determination may be correct.

This is another useful refinement of `RepresentationTransformation`.

---

# 35. Updated final architecture

Our current optimized architecture is now:

```text id="0o9o3r"
L0   SEMANTIC KERNEL
     Identity
     TypedRelation
     SemanticContract

L0.5 REPRESENTATION & SEMANTIC ALIGNMENT
     Representation
     FactorRepresentation
     RepresentationTransformation
     SemanticNormalization
     ContextAlignment
     RepresentationSufficiency
     RepresentationCollision
     CanonicalRepresentation
     EquivalenceContract
     TaskRelativeEquivalence
     Interchangeability
     MaterialityContract
     CollapseGuard
     RepresentationInvarianceContract

L1   KNOWLEDGE STATE
     Assertion
     Evidence
     Context
     Provenance
     Validity
     History

L2   TRANSFORMATION
     Assert
     Relate
     DerivedState
     Intervention

L2D  DEPENDENCY
     Dependency
     DependencyGraph

L2C  CONSTRAINT
     Constraint
     Compatibility
     HigherOrderConstraint
     ConstraintRegime
     ConstraintComplex

L2I  INTERVENTION & INTERACTION
     Intervention
     InterventionSet
     InterventionLattice
     Materiality
     Interaction
     InteractionOrder
     InteractionTerm
     InteractionFunction
     InteractionProfile
     InteractionMeasure
     InteractionRegime
     InteractionAttribution
     InteractionStability
     CanonicalInteraction
     InteractionIdentity
     InteractionInvariance
     InteractionHierarchy
     InteractionHypergraph [candidate]

L2IS INTERACTION SEARCH
     SearchScope
     InteractionSearchEngine
     CandidateGenerator
     CandidatePrioritizer
     PruningRule
     SearchStrategy
     SearchCertificate
     SearchFailureDiagnosis

L2LG STRUCTURAL REASONING
     Boundary
     BoundarySummary
     GlobalConsistency
     GlobalRealization
     StructuralInvariant
     StructuralEquivalence
     Obstruction
     StructuralDiagnosis

L2M  MATHEMATICAL REGIMES
     Graph
     Hypergraph
     Boolean/Fourier
     SAT
     CSP
     XOR
     SMT
     LinearAlgebra
     ConstraintComplex
     Cohomology [experimental]
     Sheaf [experimental]

L2X  EXPERIMENTAL METHODOLOGY
     Experiment
     ExperimentRegime
     Ablation
     CapabilityHypothesis
     NullHypothesis
     EvidencePlan
     Control
     AssumptionGraph
     RivalExplanation
     FallacyAudit
     ReplicationPlan
     MetricContract
     OmissionLog
     QualifiedConclusion

L3   ASSESSMENT
     Probability
     Fuzzy
     Conflict
     Fragility
     Robustness
     Uncertainty
     Calibration
     MaterialityAssessment
     InteractionAssessment

L4   ASSURANCE
     ExactValidation
     EquivalenceValidation
     StructuralCertificate
     ObstructionCertificate
     InteractionCertificate
     SearchCertificate
     InputBoundCertificate
     CertificateIntegrity
     IndependentVerification
     Counterexample
     InvariantTesting
     FaultInjection
     AblationValidation
     RepresentationInvariantValidation

L5   INTELLIGENCE
     CandidateInteraction
     CandidateEquivalence
     CandidateConstraint
     CandidateInvariant
     CandidateObstruction
     CandidateCounterexample
     CandidateScoring
     MLModel

L6   GOVERNANCE
     Authority
     Policy
     Accountability
```

---

# 36. One more optimization

I would now make a strong distinction between:

### `CanonicalRepresentation`

and:

### `CanonicalInteraction`.

They are related but not identical.

$$
CanonicalRepresentation(x)
$$

normalizes the representation of a knowledge object.

Whereas:

$$
CanonicalInteraction(I)
$$

normalizes a validated relationship among factors.

Therefore:

$$
\boxed{
Canonicalization\ of\ objects
\neq
Canonicalization\ of\ relationships.
}
$$

This prevents the representation layer from becoming a giant generic abstraction.

---

# 37. What LG-07O has actually shown

We have established computationally, within the synthetic regime:

1. The same semantic interaction can be expressed through different categorical bases.
2. Numerical coefficients change under basis transformations.
3. Interaction identity can remain invariant even when coefficients change.
4. Arbitrary numerical encoding of categorical variables can introduce representation/model-class problems.
5. Exact semantic normalization can recover the invariant interaction structure.
6. Interaction invariance therefore needs an explicit **representation contract**.
7. ML should be tested for cross-representation generalization rather than only random train/test performance.

The strongest conclusion is:

$$
\boxed{
\text{KnowledgeOS should compare semantic interaction identity, not raw representation-specific parameters.}
}
$$

---

# 38. The next step is now very important

I recommend:

# LG-07P — Representation Invariance of the Entire KnowledgeOS Pipeline

Instead of testing only interaction, test:

$$
\boxed{
Representation
\rightarrow
Dependency
\rightarrow
Materiality
\rightarrow
Interaction
\rightarrow
Constraint
\rightarrow
Determination
}
$$

under admissible representation transformations.

For example:

$$
R_1\rightarrow R_2
$$

and test:

$$
Dependency(R_1)\stackrel?=Dependency(R_2)
$$

$$
Materiality(R_1)\stackrel?=Materiality(R_2)
$$

$$
Interaction(R_1)\stackrel?=Interaction(R_2)
$$

$$
Constraint(R_1)\stackrel?=Constraint(R_2)
$$

$$
Determination(R_1)\stackrel?=Determination(R_2).
$$

This would be much more important than another isolated ML experiment.

It would test whether **KnowledgeOS itself has a representation-invariant semantic core**.

---

## Progress

* **LG-07O completed:** representation-invariant interaction framework established.
* Mixed binary/categorical/continuous interaction survives valid basis changes in the exact benchmark.
* Demonstrated that raw coefficients are not semantic invariants.
* Added:

  * `InteractionInvariance`
  * `CanonicalInteraction`
  * `InteractionIdentity`
  * `InteractionMeasure`
  * `RepresentationInvarianceContract`
  * `RepresentationInvariantValidation`.
* Strengthened the connection between representation sufficiency, equivalence, interaction and Ātma.
* ML role is now clearly defined as **cross-representation candidate discovery/generalization**, not semantic truth.
* Architecture remains independent of any particular mathematical representation.

## Remaining TODOs

* **LG-07P:** test representation invariance across the entire KnowledgeOS reasoning pipeline.
* Build adversarial representation-collision datasets.
* Measure cross-representation ML generalization.
* Test semantic-preserving vs semantic-changing transformations.
* Formalize `RepresentationInvarianceContract`.
* Test whether canonicalization preserves all material distinctions.
* Then investigate whether this invariant structure naturally admits a categorical/functorial formulation.
* Only after that revisit **sheaf/local-global theory** with the stronger invariant foundation.
