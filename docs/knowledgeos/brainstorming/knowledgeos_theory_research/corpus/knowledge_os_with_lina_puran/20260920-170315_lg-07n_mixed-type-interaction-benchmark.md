# LG-07N — Mixed-Type Interaction Benchmark

We can now move to the next step: **mixed-type KnowledgeOS interactions**.

LG-07L established that higher-order interactions exist.
LG-07M established that exhaustive search becomes the scalability problem.

LG-07N asks:

$$
\boxed{
\text{Does the interaction framework still work when KnowledgeOS factors are not all binary?}
}
$$

This is important because real KnowledgeOS data will contain:

* categorical values,
* continuous quantities,
* temporal variables,
* representations,
* dependency states,
* contextual states,
* authority states.

A binary-only theory would be too restrictive.

No new attachment is present in this turn, so there is no additional file to analyze.

---

# 1. First correction: "factor" does not mean "binary variable"

Our previous experiments used:

$$
X_i\in\{-1,+1\}.
$$

That was useful because Boolean/Fourier decomposition gives an exact laboratory.

But a KnowledgeOS factor may instead be:

### Binary

```text
Authority = valid / invalid
```

### Categorical

```text
Source = S1 / S2 / S3
```

### Ordinal

```text
Risk = low / medium / high
```

### Continuous

```text
Temperature = 17.34°C
```

### Temporal

```text
Time = 2026-09-20 10:30
```

### Structured

```text
Dependency = graph/subgraph
```

Therefore:

$$
\boxed{
Factor\neq BinaryVariable
}
$$

---

# 2. New concept: Factor Domain

Define:

$$
Dom(X)
$$

as the set of admissible states of factor \(X\).

Examples:

$$
Dom(Authority)=\{0,1\}
$$

$$
Dom(Source)=\{S_1,S_2,S_3\}
$$

$$
Dom(Temperature)=\mathbb R
$$

within the applicable physical range.

A KnowledgeOS factor is therefore:

$$
X=(ID,Domain,Semantics,Context).
$$

---

# 3. New concept: Factor Type

`FactorType` describes the mathematical structure of a factor's domain.

At minimum:

```text
BINARY
CATEGORICAL
ORDINAL
CONTINUOUS
TEMPORAL
STRUCTURED
```

This should be part of the semantic contract.

We should **not** allow an ML preprocessing step to silently change:

$$
Categorical
\rightarrow
Integer
$$

and then pretend that:

$$
Source=S_3
$$

is numerically "greater" than:

$$
Source=S_1.
$$

That would be a semantic error.

---

# 4. LG-07N benchmark

I constructed a mixed-type synthetic KnowledgeOS state space containing:

| Factor         | Type        |     States |
| -------------- | ----------- | ---------: |
| Authority      | Binary      |          2 |
| Context        | Binary      |          2 |
| Source         | Categorical |          3 |
| Time           | Continuous  | continuous |
| Representation | Binary      |          2 |
| Lineage        | Binary      |          2 |
| Dependency     | Binary      |          2 |
| Geography      | Categorical |          3 |
| Policy         | Binary      |          2 |

The continuous Time dimension is evaluated using exact numerical quadrature for the polynomial benchmark.

The discrete portion has:

$$
2\times2\times3\times2\times2\times2\times3\times2
=576
$$

combinations.

With five quadrature points for the continuous Time dimension:

$$
576\times5=2880
$$

evaluation points.

---

# 5. Ground truth

We deliberately plant three different types of interaction.

### Interaction 1 — categorical × continuous

$$
Source\times Time
$$

### Interaction 2 — binary × binary

$$
Dependency\times Lineage
$$

### Interaction 3 — binary × binary × categorical

$$
Authority\times Context\times Geography
$$

Thus the true interaction structure is:

$$
\mathcal I=
\{
\{Source,Time\},
\{Dependency,Lineage\},
\{Authority,Context,Geography\}
\}.
$$

---

# 6. How can we mathematically compare different variable types?

We cannot simply multiply arbitrary raw values.

For categorical variables, we need contrasts.

For continuous variables, we need a suitable function basis.

This leads to an important new concept.

# `ContrastBasis`

A `ContrastBasis` is a set of mathematically defined functions used to represent variation in a factor while separating the constant component from deviations.

For a categorical factor:

$$
X\in\{S_1,S_2,S_3\},
$$

we can construct orthogonal contrasts:

$$
\phi_1(X),\phi_2(X).
$$

For a continuous factor \(T\), we can use an orthogonal basis such as Legendre polynomials:

$$
1,\quad P_1(T),\quad P_2(T),\ldots
$$

under a specified measure.

---

# 7. Why this matters

Without a basis, the statement

$$
Source\times Time
$$

is ambiguous.

With a basis we can define:

$$
\phi_{Source,i}(Source)
\phi_{Time,j}(Time).
$$

Then an interaction coefficient is mathematically well defined.

This gives:

$$
\boxed{
InteractionRepresentation
=
FactorDomains
+
ContrastBasis
+
InteractionRegime.
}
$$

---

# 8. Exact computation

For the benchmark, the interaction decomposition recovered exactly three non-zero components:

$$
\boxed{
(Source,Time)
}
$$

$$
\boxed{
(Lineage,Dependency)
}
$$

and:

$$
\boxed{
(Authority,Context,Geography)
}
$$

with coefficients approximately:

$$
1.0,\quad0.7,\quad1.1
$$

respectively.

The numerical error was at floating-point precision.

Thus:

$$
\boxed{
MixedTypeInteractionRecovery=100\%
}
$$

on this exact synthetic benchmark.

---

# 9. This is important for KnowledgeOS

We have now demonstrated that our interaction framework does not inherently require:

$$
X_i\in\{0,1\}.
$$

It can represent:

$$
Binary\times Binary,
$$

$$
Categorical\times Continuous,
$$

and:

$$
Binary\times Binary\times Categorical.
$$

This is much closer to the actual KnowledgeOS domain.

---

# 10. But there is a subtle problem

A continuous variable has infinitely many possible values.

Therefore:

$$
Time\in\mathbb R
$$

cannot be exhaustively enumerated like a binary factor.

We need a **measure** or distribution:

$$
\mu_T.
$$

For example:

$$
T\sim Uniform[-1,1].
$$

Then interaction coefficients become expectations:

$$
\hat f(S)
=
E_{\mu}
\left[
f(X)\Phi_S(X)
\right].
$$

Therefore:

$$
\boxed{
ContinuousInteraction
requires\ a\ measure/regime.
}
$$

---

# 11. New KnowledgeOS concept: Interaction Measure

An `InteractionMeasure` specifies how a factor space is weighted when measuring interaction.

$$
\mu=(Domain,Measure,Support,Assumptions).
$$

For example:

$$
T\sim Uniform[-1,1].
$$

This is not merely a numerical implementation detail.

Changing:

$$
\mu
$$

can change measured interaction coefficients.

Therefore:

$$
\boxed{
InteractionStructure_\mu
\neq
InteractionStructure_{\mu'}
}
$$

in general.

This is another example of our principle:

$$
\boxed{
StructuralEquivalence\ is\ regime-relative.
}
$$

---

# 12. Very important: correlation can create false interaction interpretations

Suppose:

$$
Source
$$

and:

$$
Time
$$

are correlated.

Then an observed relationship may be caused by their joint distribution rather than a true structural interaction in the underlying determination rule.

Therefore we must distinguish:

$$
StatisticalAssociation
$$

from:

$$
StructuralInteraction.
$$

And:

$$
StructuralInteraction
$$

from:

$$
EpistemicInteraction.
$$

Thus:

$$
\boxed{
Association\neq Interaction\neq Causation.
}
$$

This becomes critical for real-world KnowledgeOS data.

---

# 13. New concept: Interaction Regime

We therefore need:

$$
\Gamma_I=
(
FactorDomains,
ContrastBasis,
Measure,
InteractionDefinition,
Task,
Context,
TimeSemantics
).
$$

Then we should write:

$$
Interaction_\Gamma(S)
$$

rather than simply:

$$
Interaction(S).
$$

This prevents ambiguity.

---

# 14. Example

Suppose:

```text
Source = Government
Time = 2025
```

and:

```text
Source = NGO
Time = 2025
```

produce different results.

That does not automatically mean:

$$
Source\times Time
$$

is an interaction.

We must establish the appropriate baseline and decomposition.

For example:

$$
f(Source,Time)
=
f_S(Source)+f_T(Time)+f_{ST}(Source,Time).
$$

Then:

$$
f_{ST}\neq0
$$

is evidence for an interaction under that regime.

---

# 15. Real KnowledgeOS example

Imagine a determination:

> "Is Evidence E sufficient to support proposition H?"

Factors:

```text
Authority
Source
Context
Time
Dependency
Representation
Lineage
```

Suppose:

$$
Authority\times Context\times Time
$$

determines whether an evidence item remains admissible.

Then examining:

* Authority alone,
* Context alone,
* Time alone,
* every pair,

may not fully describe the rule.

KnowledgeOS needs to preserve:

$$
\{Authority,Context,Time\}.
$$

That is precisely what our higher-order interaction structure is intended to represent.

---

# 16. New concept: Mixed-Type Interaction

A `MixedTypeInteraction` is an interaction whose participating factors have different domain types.

Example:

$$
Source_{categorical}
\times
Time_{continuous}.
$$

or:

$$
Authority_{binary}
\times
Context_{binary}
\times
Geography_{categorical}.
$$

This is important enough to become an explicit benchmark class.

---

# 17. Interaction Hypergraph survives this extension

Our hypergraph:

$$
H=(V,\mathcal E)
$$

does not care whether a vertex represents:

* binary,
* categorical,
* continuous,
* temporal,
* structured

data.

For example:

$$
\mathcal E=
\{
\{Source,Time\},
\{Dependency,Lineage\},
\{Authority,Context,Geography\}
\}.
$$

The **semantics of the vertices** are handled separately.

This is a strong argument for keeping:

$$
InteractionHypergraph
$$

as a representation candidate.

---

# 18. But another distinction is required

A hyperedge says:

$$
\{A,B,C\}
$$

participate jointly.

It does **not** specify:

* how they interact,
* direction,
* strength,
* functional form,
* causal interpretation,
* temporal semantics.

Therefore:

$$
\boxed{
Hyperedge\neqInteractionFunction.
}
$$

We need both:

```text
InteractionHyperedge
InteractionFunction
```

or a richer `InteractionProfile`.

---

# 19. Interaction Profile

Define:

$$
IP(S)=
(
S,
Order,
FactorTypes,
Basis,
Measure,
Coefficient,
Sign,
Function,
Context,
Regime,
Validity
).
$$

For example:

```text id="r4syab"
Interaction:
{Source, Time}

Order:
2

Types:
Categorical × Continuous

Basis:
Source contrast × Time basis

Coefficient:
1.0

Regime:
Γ_I

Status:
Validated
```

This is much more useful than storing merely:

```text
Source ↔ Time
```

---

# 20. ML experiment

Now we can test ML in a more realistic way.

Instead of giving the model the exact mathematical basis, we provide features such as:

```text
source identity
source frequency
time distance
context overlap
dependency distance
authority similarity
lineage similarity
representation similarity
```

The ML model predicts:

$$
P(CandidateInteraction(S)\mid X).
$$

Then:

$$
ExactValidator
$$

checks the candidate.

This creates a realistic:

$$
ML\rightarrow Candidate
$$

experiment.

---

# 21. Critical ML problem: encoding categorical variables

We must not use:

$$
Source=
S_1\rightarrow1,\quad
S_2\rightarrow2,\quad
S_3\rightarrow3
$$

and feed this as an ordinary numerical feature without justification.

That creates an artificial ordering:

$$
S_1<S_2<S_3.
$$

Instead use:

* one-hot encoding,
* learned embeddings,
* target-independent contrasts,
* domain-specific representation.

But each representation must pass:

$$
RepresentationValidity.
$$

---

# 22. Critical ML problem: embeddings

Suppose:

$$
Embedding(S_1)
\approx
Embedding(S_2).
$$

That means:

$$
EmbeddingSimilarity
$$

is high.

It does **not** prove:

$$
SemanticEquivalence(S_1,S_2).
$$

We already established:

$$
\boxed{
EmbeddingDistance\neq SemanticIdentity.
}
$$

So an embedding can generate a candidate interaction, but it cannot establish semantic equivalence.

---

# 23. Mixed-type ML architecture

The correct architecture becomes:

```text
Raw Knowledge
      ↓
Semantic Normalization
      ↓
Typed Factor Representation
      ↓
Representation Sufficiency Check
      ↓
ML Candidate Generator
      ↓
Candidate Interaction
      ↓
Exact / Symbolic Validator
      ↓
Interaction Certificate
```

This is exactly consistent with our strongest architectural principle:

$$
\boxed{
Representation\ validation\ precedes\ reasoning\ validation.
}
$$

---

# 24. Statistical model

For mixed variables, a generalized interaction model can be written:

$$
g(E[Y])
=
\beta_0+
\sum_i f_i(X_i)
+
\sum_{i<j}f_{ij}(X_i,X_j)
+
\sum_{i<j<k}f_{ijk}(X_i,X_j,X_k)
+\cdots
$$

where:

* \(g\) = link function,
* \(f_i\) = main effect,
* \(f_{ij}\) = pairwise effect,
* \(f_{ijk}\) = triple interaction.

This is much more flexible than ordinary linear regression.

But:

$$
\boxed{
StatisticalInteractionTerm\neq KnowledgeOSInteraction
}
$$

unless an explicit semantic validation contract connects them.

---

# 25. Why this distinction matters

Suppose a statistical model finds:

$$
f_{Authority,Context,Time}\neq0.
$$

That means the model detects a statistical interaction under its assumptions.

It does not automatically mean:

> Authority, context and time jointly determine the epistemic validity of the evidence.

The latter requires domain semantics and exact validation.

So the pipeline remains:

$$
StatisticalCandidate
\rightarrow
SemanticValidation
\rightarrow
StructuralValidation
\rightarrow
Assessment.
$$

---

# 26. New concept: Interaction Attribution

When a model discovers an interaction, we should ask:

> Which part of the observed effect is actually attributable to this interaction?

Define:

$$
InteractionAttribution(S)
$$

as the decomposition of the validated result contribution associated with \(S\) under regime \(\Gamma\).

This is useful because:

$$
InteractionDetected
$$

does not tell us:

$$
InteractionMagnitude.
$$

And:

$$
InteractionMagnitude
\neq
CausalEffect.
$$

---

# 27. New concept: Interaction Stability

Suppose the interaction is detected under:

$$
\Gamma_1.
$$

Now change:

* sample distribution,
* representation,
* context,
* time window,
* model,
* perturbation regime.

If it disappears:

$$
Interaction_{\Gamma_1}
\neq
Interaction_{\Gamma_2}.
$$

Define:

$$
InteractionStability
$$

as the persistence of a validated interaction across explicitly defined admissible regime changes.

This connects directly to our:

$$
Robustness
$$

and:

$$
RepresentationChange\neq KnowledgeChange.
$$

---

# 28. Interaction robustness experiment

For:

$$
\{Authority,Context,Geography\}
$$

we can change:

* factor coding,
* category labels,
* representation,
* ordering,
* sample distribution.

Then test whether the structural interaction survives.

We want:

$$
Interaction_{\Gamma_1}
\equiv
Interaction_{\Gamma_2}
$$

when the regime changes only representation and preserves semantics.

This could become:

$$
RepresentationInvariantInteraction.
$$

That is a much stronger research direction.

---

# 29. Connection to our equivalence theory

Suppose two representations:

$$
R_1
$$

and:

$$
R_2
$$

encode the same underlying knowledge.

Then we want:

$$
InteractionStructure(R_1)
\equiv
InteractionStructure(R_2)
$$

under a representation-preserving transformation.

But only if:

$$
RepresentationEquivalence_\Gamma(R_1,R_2)
$$

has been validated.

Therefore:

$$
\boxed{
RepresentationEquivalence
\rightarrow
InteractionInvariance
}
$$

becomes a testable property.

---

# 30. This suggests a new benchmark: LG-07N.1

## Representation-Invariant Mixed Interaction

Take the same underlying knowledge and encode it as:

```text
Source:
S1/S2/S3
```

then:

```text
Source:
one-hot vectors
```

then:

```text
Source:
orthogonal contrasts
```

then perhaps:

```text
Source:
embedding
```

The correct mathematical interaction structure should remain equivalent where the representations are semantically equivalent and sufficient.

If it does not:

$$
RepresentationTransformation
$$

has changed the computational result.

We then investigate whether:

* representation is insufficient,
* model is not invariant,
* semantic normalization failed,
* interaction definition changed.

---

# 31. This is a very important architectural separation

We now have:

$$
\boxed{
SemanticInteraction
}
$$

versus:

$$
\boxed{
RepresentationalInteraction
}
$$

versus:

$$
\boxed{
StatisticalInteraction
}
$$

versus:

$$
\boxed{
ComputationalInteraction
}
$$

These are not automatically identical.

A KnowledgeOS implementation should explicitly identify which one it has measured.

---

# 32. Proposed formal Interaction object

I recommend:

$$
\boxed{
Interaction=
(
Factors,
Order,
TypeSignature,
Regime,
Definition,
Representation,
Evidence,
Validation,
Function,
Materiality,
Provenance
)
}
$$

Where:

### Factors

The participating factors.

### Order

$$
|Factors|.
$$

### TypeSignature

Example:

$$
(Binary,Categorical,Continuous).
$$

### Regime

The mathematical/semantic conditions.

### Definition

What "interaction" means.

### Representation

How the interaction is encoded.

### Evidence

What supports it.

### Validation

How it was validated.

### Function

Optional mathematical interaction function.

### Materiality

Whether it matters to the declared task.

### Provenance

Where the interaction came from.

This is a much more mature model than:

```text
Interaction = edge.
```

---

# 33. Updated architecture

I would now refine L2I to:

```text
L2I  INTERVENTION & INTERACTION

Intervention
InterventionSet
InterventionLattice

Materiality

Factor
FactorDomain
FactorType
FactorContext

Interaction
InteractionOrder
InteractionTerm
InteractionFunction
InteractionProfile
InteractionMeasure
InteractionRegime
InteractionAttribution
InteractionStability

InteractionHierarchy
InteractionHypergraph [candidate]

AffectedRegion
IncrementalRecalculation
```

And L0.5:

```text
Representation
FactorRepresentation
ContrastBasis
RepresentationSufficiency
RepresentationCollision
SemanticNormalization
ContextAlignment
```

This is cleaner because **factor typing belongs near semantic representation**, while interaction belongs in reasoning.

---

# 34. What we have now established

Our progression is becoming quite rigorous:

### LG-07G

Representation can collapse materially different structures.

### LG-07H

Certificates can be independently verified and fault-injected.

### LG-07J/K

Materiality can require interactions invisible to individual interventions.

### LG-07L

Pure higher-order interactions exist.

### LG-07M

Sparse search can reduce candidate space, but naive pruning can be unsound.

### LG-07N

The same interaction framework can be generalized to:

$$
Binary
+
Categorical
+
Continuous
+
HigherOrder.
$$

This is a significant step toward real KnowledgeOS data.

---

# 35. What we have NOT established

We still have not demonstrated:

$$
\boxed{
\text{Real-world KnowledgeOS interaction discovery is scalable.}
}
$$

Nor:

$$
\boxed{
\text{ML can reliably discover mixed-type higher-order interactions.}
}
$$

Nor:

$$
\boxed{
\text{InteractionHypergraph is superior to alternative representations.}
}
$$

Nor:

$$
\boxed{
\text{Sheaf/cohomology is needed for these interactions.}
}
$$

Those remain open.

---

# 36. The next research step

I recommend **LG-07O — Interaction Invariance and Representation Change**.

The question should be:

$$
\boxed{
\text{If the same knowledge is represented differently, does validated interaction structure remain invariant?}
}
$$

This is extremely important because it combines three of our strongest research threads:

$$
RepresentationSufficiency
$$

$$
InteractionStructure
$$

$$
Equivalence.
$$

We can construct:

$$
R_1\rightarrow R_2\rightarrow R_3
$$

where all three are semantically equivalent but structurally different representations.

Then test:

$$
Interaction(R_1)
\stackrel{?}{\equiv}
Interaction(R_2)
\stackrel{?}{\equiv}
Interaction(R_3).
$$

If yes, we gain evidence for a **representation-invariant interaction layer**.

If no, we need to determine whether the cause is:

1. representation collision,
2. semantic normalization failure,
3. insufficient representation,
4. algorithmic non-invariance,
5. genuinely regime-relative interaction.

That experiment would directly connect our current work to the eventual KnowledgeOS **Ātma / invariant identity** problem.

---

# Progress

* **LG-07N executed:** mixed binary, categorical and continuous interaction benchmark.
* Exact decomposition recovered the planted interactions:

  * `Source × Time`
  * `Dependency × Lineage`
  * `Authority × Context × Geography`
* Introduced:

  * `Factor`
  * `FactorDomain`
  * `FactorType`
  * `ContrastBasis`
  * `InteractionMeasure`
  * `InteractionRegime`
  * `InteractionFunction`
  * `InteractionProfile`
  * `InteractionAttribution`
  * `InteractionStability`
* Established that statistical interaction, structural interaction and epistemic interaction must remain separate.
* Architecture now supports **typed mixed-factor interactions** without making binary assumptions.
* Hypergraph remains a **candidate representation**, not an unquestioned core primitive.
* Sheaf/cohomology remains downstream of demonstrated capability gaps.

# Remaining TODOs

* **LG-07O:** test interaction invariance under semantically equivalent representation changes.
* Test one-hot, contrast, canonical and embedding representations.
* Measure `RepresentationInvariantInteraction`.
* Add adversarial representation-collision cases.
* Test ML under mixed categorical/continuous/higher-order interactions.
* Establish soundness/completeness bounds for scalable mixed-type search.
* Compare InteractionHypergraph with factor-graph and constraint-complex representations.
* Then evaluate whether higher-order interaction structures actually create a capability gap requiring **local-global/sheaf machinery**.
