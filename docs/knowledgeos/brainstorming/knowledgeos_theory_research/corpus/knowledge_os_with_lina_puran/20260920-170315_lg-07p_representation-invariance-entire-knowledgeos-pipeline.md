# LG-07P — Representation Invariance of the Entire KnowledgeOS Pipeline

We have reached a more fundamental question than interaction discovery.

LG-07O showed that an interaction can survive a change of representation. The next question is:

$$
\boxed{
\text{Does representation invariance survive the whole KnowledgeOS reasoning pipeline?}
}
$$

That means testing not only `Interaction`, but:

$$
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
Determination.
$$

No new attachment is present in this turn. I therefore continue from the established KnowledgeOS corpus. The previously loaded *Categories and Sheaves* material remains relevant later, but I do **not** use its categorical/sheaf claims as premises for this experiment.

---

# 1. The central hypothesis

Let:

$$
R_1,R_2
$$

be two representations of the same semantic KnowledgeOS state.

Let:

$$
T:R_1\rightarrow R_2
$$

be an admissible, semantics-preserving representation transformation.

Our hypothesis is:

$$
\boxed{
P(R_1)\equiv P(R_2)
}
$$

for every KnowledgeOS property \(P\) that is declared invariant under \(T\).

The important phrase is **declared invariant**.

We must not demand invariance under information-destroying transformations.

---

# 2. New concept: Representation Invariance Contract

Define:

$$
RIC=
(
P,
T,
\Gamma,
Q,
Preconditions,
ExpectedInvariant,
Validator
)
$$

where:

* \(P\) = KnowledgeOS property,
* \(T\) = allowed transformation,
* \(\Gamma\) = reasoning regime,
* \(Q\) = task,
* `Preconditions` = conditions under which invariance is expected,
* `ExpectedInvariant` = property that should remain unchanged,
* `Validator` = exact procedure testing it.

Example:

```text
Property:
Interaction

Transformation:
categorical label → one-hot encoding

Task:
determine evidence sufficiency

Expected:
same semantic interaction structure

Validator:
exact semantic normalization + interaction analysis
```

This is much stronger than simply saying:

> "The representations are equivalent."

---

# 3. The test world

We use:

$$
A,B\in\{0,1\}
$$

$$
S\in\{S_1,S_2,S_3\}
$$

and:

$$
T\in\{-1,-0.5,0,0.5,1\}.
$$

Therefore:

$$
2\times2\times3\times5=60
$$

exhaustive states.

The ground-truth determination function is:

$$
Y(A,B,S,T)
=
(2A-1)(2B-1)c(S)T
$$

with:

$$
c(S_1)=-1,\qquad
c(S_2)=0,\qquad
c(S_3)=1.
$$

This deliberately creates a mixed:

$$
Binary\times Binary\times Categorical\times Continuous
$$

interaction.

---

# 4. Exact ground truth

The semantic interaction is:

$$
\boxed{
I^\star=\{A,B,S,T\}
}
$$

with order:

$$
Order(I^\star)=4.
$$

The exact calculation over all 60 states reconstructs the function with numerical residual approximately:

$$
10^{-14}.
$$

So our oracle is effectively exact.

---

# 5. Four representations

We now create four representations of the **same semantic world**.

### R1 — categorical label

```text
S ∈ {S1,S2,S3}
```

### R2 — one-hot

$$
S_1=(1,0,0)
$$

$$
S_2=(0,1,0)
$$

$$
S_3=(0,0,1)
$$

### R3 — contrast representation

$$
c(S)=(-1,0,+1)
$$

plus an additional categorical contrast.

### R4 — permuted labels

For example:

$$
S_1\rightarrow S'_3
$$

$$
S_2\rightarrow S'_1
$$

$$
S_3\rightarrow S'_2.
$$

The permutation changes representation but not semantic identity because the mapping is explicitly known.

---

# 6. Exact result

After semantic normalization:

$$
N(R_1)=N(R_2)=N(R_3)=N(R_4).
$$

Therefore:

$$
\boxed{
Interaction(R_1)
=
Interaction(R_2)
=
Interaction(R_3)
=
Interaction(R_4)
=
\{A,B,S,T\}.
}
$$

This is our first important result.

---

# 7. But the numerical coefficients differ

This is crucial.

The one-hot representation and contrast representation produce different numerical coefficient vectors.

In general:

$$
\beta_{R_1}\neq\beta_{R_2}.
$$

Yet:

$$
f_{R_1}=f_{R_2}.
$$

Therefore:

$$
\boxed{
ParameterEquality
\neq
SemanticEquality.
}
$$

And:

$$
\boxed{
CoefficientInvariance
\neq
InteractionInvariance.
}
$$

This means a KnowledgeOS validator must never compare ML/model coefficients as if they were semantic identity.

---

# 8. Entire pipeline test

Now we test each layer.

## Layer 1 — Dependency

Assume:

$$
A\rightarrow S
$$

is an established semantic dependency.

A representation transformation must not change the dependency merely because:

```text
S1
```

became:

```text
[1,0,0].
```

Therefore:

$$
Dependency(R_1)
=
Dependency(R_2).
$$

---

# 9. Layer 2 — Materiality

A factor is materially relevant to task \(Q\) if an admissible intervention can change the validated result:

$$
Material_\Gamma(X,Q)
\iff
\exists I_X:
Result_\Gamma(Q,I_X(x))
\neq
Result_\Gamma(Q,x).
$$

For the benchmark:

$$
Material(A)=Material(B)=Material(S)=Material(T)=1.
$$

This remains true under \(R_1\)–\(R_4\).

Therefore:

$$
\boxed{
MaterialityInvariant=TRUE
}
$$

for these transformations.

---

# 10. Layer 3 — Interaction

Exact analysis gives:

$$
I^\star=\{A,B,S,T\}.
$$

All four valid representations recover the same semantic factor set.

Therefore:

$$
\boxed{
InteractionInvariant=TRUE
}
$$

for the declared representation transformations.

---

# 11. Layer 4 — Constraint

Now define a simple determination constraint:

$$
C:
Y>0.
$$

This is a constraint on the semantic result, not on the representation.

For every valid representation:

$$
C(R_i)
$$

is equivalent after normalization.

Therefore:

$$
\boxed{
ConstraintInvariant=TRUE.
}
$$

---

# 12. Layer 5 — Determination

Define:

$$
D=
\begin{cases}
H,&Y>0\\
U,&Y\le0
\end{cases}
$$

where:

* \(H\) = determined/positive condition,
* \(U\) = unresolved/non-positive condition.

Because the representation changes preserve \(Y\):

$$
D(R_1)=D(R_2)=D(R_3)=D(R_4).
$$

Therefore:

$$
\boxed{
DeterminationInvariant=TRUE.
}
$$

---

# 13. The complete invariant chain

For admissible transformations:

$$
\boxed{
R_1\equiv R_2
}
$$

leads experimentally to:

$$
\boxed{
Dependency_1=Dependency_2
}
$$

$$
\boxed{
Materiality_1=Materiality_2
}
$$

$$
\boxed{
Interaction_1\equiv Interaction_2
}
$$

$$
\boxed{
Constraint_1\equiv Constraint_2
}
$$

$$
\boxed{
Determination_1=Determination_2.
}
$$

This is the first useful empirical version of a **representation-invariant KnowledgeOS pipeline**.

---

# 14. Now the adversarial test

We must deliberately break the representation.

Take:

$$
R_5=(A,B,S)
$$

and remove:

$$
T.
$$

This is not merely an encoding change.

It is an **information-reducing transformation**.

---

# 15. Representation collision appears immediately

Consider:

$$
(A,B,S)=(0,0,S_1).
$$

There are five possible times:

$$
T\in\{-1,-0.5,0,0.5,1\}.
$$

But:

$$
Y=
(2A-1)(2B-1)(-1)T.
$$

So different \(T\) values produce different determinations.

Thus:

$$
\phi(R_5(x_1))=\phi(R_5(x_2))
$$

while:

$$
Result(x_1)\neq Result(x_2).
$$

We found:

$$
\boxed{8}
$$

such representation-collision groups in the exhaustive benchmark.

Therefore:

$$
\boxed{
R_5\text{ is not sufficient for this task.}
}
$$

---

# 16. This is a critical distinction

We must not conclude:

> "Representation invariance failed."

That would be wrong.

Instead:

$$
R_5
$$

is **not an admissible semantics-preserving transformation** for this task.

Therefore the correct diagnosis is:

$$
\boxed{
RepresentationSufficiencyFailure
}
$$

rather than:

$$
InteractionInvarianceFailure.
$$

This distinction is extremely important.

---

# 17. New concept: Transformation Classification

Every representation transformation should be classified as one of:

### Type I — Semantics-preserving

$$
R_1\equiv_{\Gamma,Q}R_2
$$

and expected to preserve relevant properties.

### Type II — Refining

$$
R_2\sqsupseteq_\Gamma R_1
$$

adds information without changing the existing semantic object.

### Type III — Information-reducing

Removes information that may or may not be material.

### Type IV — Semantics-changing

Actually changes the represented object.

This classification should precede invariance testing.

---

# 18. This gives us a diagnostic decision tree

When two representations produce different results:

```text
Different result
      │
      ↓
Are transformations semantically equivalent?
      │
   ┌──┴──┐
   No    Yes
   │      │
   ↓      ↓
Expected  Investigate
difference
         │
         ├─ representation insufficiency?
         ├─ normalization failure?
         ├─ algorithmic non-invariance?
         ├─ model-class failure?
         └─ genuine regime difference?
```

This is much better than simply reporting:

> "The models disagree."

---

# 19. New concept: Invariance Failure Diagnosis

Define:

$$
IFD
$$

as the process that determines why a supposedly invariant property changed.

Possible diagnoses:

```text
SEMANTIC_MISMATCH
REPRESENTATION_COLLISION
REPRESENTATION_INSUFFICIENCY
NORMALIZATION_FAILURE
MODEL_CLASS_FAILURE
SEARCH_FAILURE
VALIDATION_FAILURE
REGIME_CHANGE
UNKNOWN
```

This belongs in L4 assurance.

---

# 20. ML experiment

Now we test ML.

### Training representation

$$
R_1
$$

### Test representation

$$
R_2.
$$

The semantic task remains unchanged.

This measures:

$$
CrossRepresentationGeneralization.
$$

But there is an important methodological rule:

> We must evaluate the semantic output, not the raw numerical model output.

So the metric is:

$$
CRG=
\frac{
\#\text{correct semantic determinations}
}{
\#\text{test cases}
}.
$$

---

# 21. Why this is different from ordinary ML generalization

Ordinary ML asks:

$$
P(Y|X_{train})\rightarrow P(Y|X_{test}).
$$

Our question is:

$$
P(Y|R_1)
\rightarrow
P(Y|R_2)
$$

where:

$$
R_1\equiv_{\Gamma,Q}R_2.
$$

Therefore the challenge is:

$$
\boxed{
\text{generalization across representations, not merely across samples.}
}
$$

This is a much stronger test of whether a model has learned the underlying structure rather than encoding.

---

# 22. Representation shortcut learning

Suppose training always uses:

$$
S_1=1,\quad S_2=2,\quad S_3=3.
$$

The ML model might learn:

$$
S=3
$$

as a meaningful numerical condition.

Then we permute the labels.

Performance collapses.

The model did not learn:

$$
SourceIdentity.
$$

It learned:

$$
RepresentationShortcut.
$$

Define:

$$
ShortcutDependence
$$

as reliance on representation-specific patterns that are not semantically invariant.

---

# 23. New ML metric

Define:

$$
RSD=
1-
\frac{
Performance_{cross-representation}
}{
Performance_{within-representation}
}.
$$

where:

* \(RSD\) = Representation Sensitivity Drop.

Ideally:

$$
RSD\approx0.
$$

A large:

$$
RSD
$$

is evidence of representation dependence.

It does not by itself prove shortcut learning, but it is a diagnostic signal.

---

# 24. ML pipeline

Our architecture becomes:

$$
\boxed{
RawKnowledge
\rightarrow
SemanticNormalization
\rightarrow
TypedRepresentation
\rightarrow
RepresentationValidation
\rightarrow
MLCandidate
\rightarrow
ExactValidation
\rightarrow
Certificate
\rightarrow
Assessment
\rightarrow
Determination
}
$$

The key new gate is:

$$
\boxed{
RepresentationValidation
}
$$

before ML reasoning.

---

# 25. This gives us a much stronger architecture than "ML + Knowledge Graph"

A conventional architecture might be:

```text
Data
 ↓
Embedding
 ↓
ML
 ↓
Answer
```

KnowledgeOS now has:

```text
Data
 ↓
Semantic interpretation
 ↓
Representation validation
 ↓
Candidate generation
 ↓
Exact structural reasoning
 ↓
Independent verification
 ↓
Assessment
 ↓
Determination
```

This is fundamentally different.

---

# 26. New concept: Semantic Determination Invariance

For a task \(Q\):

$$
SDI_\Gamma(Q,R_1,R_2)
$$

holds iff:

$$
R_1\equiv_{\Gamma,Q}R_2
$$

and:

$$
Determination_\Gamma(Q,R_1)
=
Determination_\Gamma(Q,R_2).
$$

This should become one of our highest-level assurance properties.

---

# 27. New concept: Pipeline Invariance

Define:

$$
PI_\Gamma(Q,R_1,R_2)
$$

iff every declared invariant layer agrees:

$$
Dependency
$$

$$
Materiality
$$

$$
Interaction
$$

$$
Constraint
$$

$$
Determination.
$$

Formally:

$$
PI=
\bigwedge_{P\in\mathcal P}
Invariant(P).
$$

where:

$$
\mathcal P=
\{
Dependency,
Materiality,
Interaction,
Constraint,
Determination
\}.
$$

This is a candidate **KnowledgeOS Pipeline Invariance Contract**.

---

# 28. Important: pipeline invariance is not universally required

Suppose we intentionally change the task from:

> "Is this evidence valid?"

to:

> "How is this evidence represented?"

Then the determination may legitimately change.

Therefore:

$$
PI(Q_1)\not\Rightarrow PI(Q_2).
$$

Again:

$$
\boxed{
TaskRelativeEquivalence
}
$$

is essential.

---

# 29. Connection to Ātma

This experiment gives us a much more concrete interpretation of our earlier Ātma work.

Let:

$$
K
$$

be a semantic KnowledgeOS object.

An admissible representation transformation:

$$
T:K_R\rightarrow K_{R'}
$$

should preserve the object's identity:

$$
Identity(K_R)=Identity(K_{R'}).
$$

The operational version is:

$$
\boxed{
SemanticIdentity
=
\text{persistence of all declared material properties under admissible transformations}.
}
$$

This is much more useful than:

$$
K_{\text{ātma}}=\lim A_t.
$$

We should retain the former and reject the latter as a general definition.

---

# 30. A possible formal definition

Define the **material invariant set**:

$$
Inv_{\Gamma,Q}(K)
=
\{P:
P\text{ is material and invariant for }(K,\Gamma,Q)\}.
$$

Then:

$$
K_1\equiv_{\Gamma,Q}K_2
$$

requires agreement over the declared invariant set.

This is a promising formalization of semantic identity.

---

# 31. Connection to category theory

Now we have a legitimate reason to investigate categorical structure.

We have:

$$
R_1\xrightarrow{T_{12}}R_2
$$

and a property-preserving map:

$$
I_{R_1}\rightarrow I_{R_2}.
$$

We can ask whether representation transformations form a category and whether semantic properties behave functorially.

But this is where we must be disciplined.

The current result shows:

$$
\text{representation-preserving transformations exist}.
$$

It does **not** prove that the correct mathematical formalism is category theory.

If we want to formalize the categorical aspect next, tell me **which category-theory book you want us to use**. I will not silently select one.

---

# 32. Connection to the Kashiwara–Schapira material

The previously supplied *Categories and Sheaves* extraction suggested mappings such as:

> category → knowledge states and transformations,

and:

> sheaf → local-to-global reasoning.

Those mappings are useful as **modelling hypotheses**, but they are not consequences of the book itself. 

Our LG-07P result gives us a more rigorous prerequisite:

Before asking whether KnowledgeOS has a categorical/sheaf structure, we can first establish:

$$
RepresentationTransformation
\rightarrow
InvariantSemanticProperty.
$$

Only then should we investigate whether that preservation has a useful categorical formulation.

In particular, we should **not** yet claim that Yoneda proves the dependency graph is KnowledgeOS's primary representation.

---

# 33. Architecture optimization

I would now introduce a dedicated assurance concept:

```text
L4 Representation Assurance

RepresentationValidity
RepresentationSufficiencyValidation
RepresentationEquivalenceValidation
RepresentationInvarianceValidation
InvarianceFailureDiagnosis
CollapseDetection
CanonicalizationValidation
```

And:

```text
L0.5 Representation & Semantic Alignment

Representation
FactorRepresentation
RepresentationTransformation
SemanticNormalization
ContextAlignment
CanonicalRepresentation
EquivalenceContract
TaskRelativeEquivalence
Interchangeability
MaterialityContract
```

This separation is clean:

$$
L0.5=
\text{"What does this representation mean?"}
$$

while:

$$
L4=
\text{"Did the representation preserve what it was supposed to preserve?"}
$$

---

# 34. Revised full architecture

```text
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
     CanonicalRepresentation
     EquivalenceContract
     TaskRelativeEquivalence
     Interchangeability
     MaterialityContract

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

     RepresentationValidity
     RepresentationSufficiencyValidation
     RepresentationEquivalenceValidation
     RepresentationInvarianceValidation
     InvarianceFailureDiagnosis
     CollapseDetection

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

# 35. One important simplification

I would **not** create:

```text
PipelineInvariant
```

as a giant domain object yet.

It is currently better represented as:

$$
RepresentationInvarianceContract
$$

plus:

$$
RepresentationInvarianceValidation.
$$

Why?

Because the pipeline itself consists of different semantic properties, and each property may have different invariance conditions.

This avoids another "god object."

---

# 36. The emerging KnowledgeOS meta-law

Across LG-07G through LG-07P we can now formulate a very general principle:

$$
\boxed{
A KnowledgeOS property may be treated as semantic knowledge only after its dependence on representation has been explicitly characterized.
}
$$

More formally:

$$
Property(P)
$$

must specify:

$$
RepresentationSensitivity(P,\Gamma,Q).
$$

Then we can classify \(P\) as:

```text
INVARIANT
CONDITIONALLY_INVARIANT
REPRESENTATION_DEPENDENT
UNKNOWN
```

This is potentially one of the most important additions to the architecture.

---

# 37. New concept: Representation Sensitivity Profile

Define:

$$
RSP(P)=
(
P,
AllowedTransformations,
InvariantConditions,
SensitiveDimensions,
FailureModes
).
$$

For example:

```text
Property: Determination

Allowed:
binary ↔ one-hot

Invariant:
Yes

Sensitive:
loss of Time

Failure:
RepresentationInsufficiency
```

This allows KnowledgeOS to reason explicitly about representation risk.

---

# 38. Why this is important for real-world KnowledgeOS

Consider evidence:

```text
Original:
Source + Authority + Context + Time + Lineage
```

A downstream ML system might reduce it to:

```text
EmbeddingVector[768]
```

The embedding may preserve:

* semantic similarity,

but lose:

* exact authority,
* provenance,
* temporal ordering,
* dependency identity.

The embedding can therefore be useful for:

$$
CandidateDiscovery
$$

but unsafe as the canonical KnowledgeOS representation.

That is precisely what our experiments are formalizing.

---

# 39. ML should therefore never silently become the canonical representation

Our architecture should enforce:

$$
\boxed{
Embedding
\neq
CanonicalKnowledgeRepresentation
}
$$

unless:

$$
RepresentationSufficiency
$$

has been validated for the task.

This is an important architectural guardrail.

---

# 40. Where this takes us

We started with:

> "Can category theory, sheaf theory, topology, Bayesian reasoning and ML help KnowledgeOS?"

The research question has evolved into something much stronger:

$$
\boxed{
What mathematical structures are actually necessary to preserve semantic identity, detect dependencies, represent higher-order interactions, validate constraints, and make reliable determinations?
}
$$

We are no longer selecting mathematics first.

We are discovering the required mathematics from capability gaps.

That is the correct research methodology.

---

# 41. Next step: LG-07Q

The natural next experiment is now:

# **LG-07Q — Invariance vs Refinement**

We have tested **equivalent representations**.

Now we need to test **representations that add information**.

Suppose:

$$
R_1
$$

contains:

```text
Source + Context
```

and:

$$
R_2
$$

adds:

```text
Time + Dependency + Lineage.
```

Then:

$$
R_2\sqsupseteq R_1.
$$

We must determine:

1. What remains invariant?
2. What new interactions become discoverable?
3. Which old determinations must be recalculated?
4. When is refinement merely representational?
5. When does it expose previously hidden KnowledgeOS structure?

This connects directly to the earlier **Lord Lens / Knowledge Frontier** principle:

$$
\boxed{
NewDimension\land Material
\Rightarrow
Reassessment.
}
$$

And it will let us experimentally distinguish:

$$
RepresentationRefinement
$$

from:

$$
KnowledgeChange.
$$

That is probably the next most important experiment before returning to category/sheaf theory.

---

## Progress

* **LG-07P completed:** entire pipeline representation-invariance benchmark.
* Exact test showed invariant:

  * dependency,
  * materiality,
  * interaction,
  * constraint,
  * determination
    under admissible representation changes.
* Deliberately lossy representation produced **8 representation-collision groups**, proving why sufficiency must precede invariance.
* Added:

  * `RepresentationInvarianceContract`
  * `RepresentationInvarianceValidation`
  * `InvarianceFailureDiagnosis`
  * `RepresentationSensitivityProfile`
  * `SemanticDeterminationInvariance`
  * `PipelineInvariance`.
* Established an important distinction:

  $$
  InvarianceFailure\neq RepresentationInsufficiency.
  $$
* ML research now explicitly tests **cross-representation generalization** and representation shortcut learning.
* The architecture is becoming representation-first, validation-first, and regime-relative.

## Remaining TODOs

* **LG-07Q:** test representation refinement and newly exposed dimensions.
* Measure when added information requires recalculation.
* Connect refinement to `KnowledgeFrontier`.
* Test whether new dimensions create new interactions/dependencies.
* Build adversarial lossy/refining representation corpora.
* Formalize the `RepresentationSensitivityProfile`.
* Then investigate whether the resulting transformation/invariance system benefits from a **categorical formulation**.
* Only after that return to the stronger **sheaf/local-global capability test**.
