# LG-07R — Refinement Lattice and Minimal Information Acquisition

I continued from LG-07Q and also reviewed the relevant attached **“Ablation, Zero Lens and Yoni Lens”** document. Its central claim is that the current algebra is “ablation-sound, Zero-incomplete and Yoni-blind,” and it proposes adding a zero element, gap operator, coverage, field, interaction and reconstruction operators. 

I agree with the **diagnosis of the missing capabilities**, but I would **not yet accept its proposed extended algebra literally**. LG-07R gives us a better way to determine which of those concepts are genuinely required.

---

# 1. The question for LG-07R

We now have eight candidate dimensions:

$$
D=
\{
C,S,A,X,T,Dp,R,L
\}
$$

where:

| Symbol | KnowledgeOS meaning |
| ------ | ------------------- |
| \(C\)  | Content             |
| \(S\)  | Source              |
| \(A\)  | Authority           |
| \(X\)  | Context             |
| \(T\)  | Time                |
| \(Dp\) | Dependency          |
| \(R\)  | Representation      |
| \(L\)  | Lineage             |

The question is:

$$
\boxed{
\text{What is the smallest subset of dimensions that is sufficient for a given determination?}
}
$$

This is more precise than asking:

> “How complete is our knowledge?”

---

# 2. Definition: Dimension

A **dimension** is a typed semantic variable along which KnowledgeOS can distinguish states.

For example:

$$
Time(E)\in\{2024,2025,2026\}.
$$

A dimension is therefore not necessarily a database column.

It is a semantic distinction that may affect:

* validity,
* dependency,
* materiality,
* interaction,
* constraint satisfaction,
* determination.

---

# 3. Definition: Representation Signature

For a subset \(S\subseteq D\), define:

$$
\phi_S(x)
$$

as the projection of a complete state \(x\) onto the dimensions in \(S\).

Example:

$$
S=\{Content,Context\}.
$$

Then:

$$
\phi_S(x)
=
(Content(x),Context(x)).
$$

All complete states with the same pair are indistinguishable to that representation.

---

# 4. Definition: Representation Refinement

For:

$$
S_1\subseteq S_2
$$

we say:

$$
S_1\preceq S_2
$$

if \(S_2\) retains all distinctions available in \(S_1\) and possibly adds more.

This produces a **refinement lattice**:

$$
\mathcal L(D)=2^D.
$$

Since there are eight dimensions:

$$
|\mathcal L(D)|=2^8=256.
$$

So LG-07R can exhaustively examine **all 256 representations**.

This is ideal for a computer-exact benchmark.

---

# 5. Definition: Determination Sufficiency

A representation \(S\) is sufficient for task \(Q\) if every two complete states that look identical under \(S\) have the same determination.

Formally:

$$
\boxed{
Sufficient_Q(S)
\iff
\forall x,y:
\phi_S(x)=\phi_S(y)
\Rightarrow
D_Q(x)=D_Q(y)
}
$$

This is much stronger than:

> “The model has high accuracy.”

It is a logical property.

---

# 6. Definition: Representation Ambiguity

Define:

$$
Ambiguity_Q(S)
$$

as the existence of states:

$$
x,y
$$

such that:

$$
\phi_S(x)=\phi_S(y)
$$

but:

$$
D_Q(x)\neq D_Q(y).
$$

Thus:

$$
\boxed{
Ambiguity=0
\iff
Sufficient.
}
$$

This gives us an exact representation-sufficiency oracle.

---

# 7. Our ground truth

We use the previously established exact interaction:

$$
D(x)=
(C\land X)
\lor
(A\land Dp)
\lor
(S\land T).
$$

This deliberately contains three independent second-order interactions:

$$
(C,X)
$$

$$
(A,Dp)
$$

$$
(S,T).
$$

The remaining two dimensions:

$$
R,\quad L
$$

are deliberately irrelevant to this determination.

This is important because it tests whether our methodology can distinguish:

$$
\boxed{
Relevant
\neq
Available
}
$$

and:

$$
\boxed{
Available
\neq
Necessary.
}
$$

---

# 8. The complete 256-representation experiment

We exhaustively evaluated:

$$
2^8=256
$$

representations.

The result is very clean.

### Number of sufficient representations

$$
\boxed{4}
$$

They are:

$$
\{C,S,A,X,T,Dp\}
$$

$$
\{C,S,A,X,T,Dp,R\}
$$

$$
\{C,S,A,X,T,Dp,L\}
$$

$$
\{C,S,A,X,T,Dp,R,L\}.
$$

Therefore the unique **minimal sufficient representation** is:

$$
\boxed{
S^\star=
\{Content,Source,Authority,Context,Time,Dependency\}.
}
$$

Representation and Lineage are not required for this particular determination.

---

# 9. This is an important result

We can now formally distinguish:

### Necessary

A dimension belongs to every minimal sufficient representation.

Here:

$$
C,S,A,X,T,Dp
$$

are necessary.

### Redundant

A dimension can be removed without losing sufficiency.

Here:

$$
R,L
$$

are redundant for this task.

### Irrelevant

A dimension has no material effect on the determination under the declared regime.

Here:

$$
R,L
$$

are irrelevant **under this synthetic regime**.

We must not generalize that Representation or Lineage are universally irrelevant.

---

# 10. Definition: Minimal Sufficient Representation

$$
\boxed{
MSR_Q
=
\min_{\subseteq}
\{S:Sufficient_Q(S)\}
}
$$

This is the set-theoretic form.

For our benchmark:

$$
\boxed{
MSR_Q=
\{C,S,A,X,T,Dp\}.
}
$$

This is a very useful KnowledgeOS concept.

---

# 11. Every one of the six dimensions is actually necessary

Remove any one:

$$
S^\star-\{C\}
$$

or:

$$
S^\star-\{S\}
$$

or:

$$
S^\star-\{A\}
$$

etc.

and sufficiency disappears.

For every one of the six removals:

* 32 representation groups remain,
* 9 are ambiguous,
* 72 of the 256 complete states lie inside ambiguous groups,
* ambiguity affects \(28.125\%\) of the complete state space.

Thus:

$$
\boxed{
C,S,A,X,T,Dp
}
$$

are individually necessary **for this determination function**.

---

# 12. Why all six are necessary

The logical reason is elegant.

The determination contains:

$$
(C\land X)
$$

so both \(C\) and \(X\) are needed.

It contains:

$$
(A\land Dp)
$$

so both \(A\) and \(Dp\) are needed.

And:

$$
(S\land T)
$$

so both \(S\) and \(T\) are needed.

Therefore:

$$
\boxed{
MSR_Q
=
\{C,X\}
\cup
\{A,Dp\}
\cup
\{S,T\}.
}
$$

This is a direct example of **higher-order interaction determining information requirements**.

---

# 13. Definition: Minimal Information Acquisition

Suppose the current representation is:

$$
S.
$$

We define:

$$
MIA_Q(S)
$$

as the smallest set of additional dimensions required to reach sufficiency.

Formally:

$$
\boxed{
MIA_Q(S)
=
\arg\min_M
\{|M|:
S\cup M
\text{ is sufficient}\}.
}
$$

For the empty representation:

$$
MIA_Q(\varnothing)=S^\star.
$$

Therefore:

$$
|MIA_Q(\varnothing)|=6.
$$

---

# 14. Very useful result: exact acquisition distance

Across all 256 starting representations, the minimum number of additional dimensions required is:

| Relevant dimensions already known | Representations | Minimum additional dimensions |
| --------------------------------: | --------------: | ----------------------------: |
|                                 0 |               4 |                             6 |
|                                 1 |              24 |                             5 |
|                                 2 |              60 |                             4 |
|                                 3 |              80 |                             3 |
|                                 4 |              60 |                             2 |
|                                 5 |              24 |                             1 |
|                                 6 |               4 |                             0 |

This is exactly the Boolean lattice structure.

So:

$$
\boxed{
MinimumAcquisition(S)
=
6-|S\cap S^\star|
}
$$

for this benchmark.

That gives us an exact computational definition of the **distance to sufficiency**.

---

# 15. Definition: Representation Sufficiency Distance

Define:

$$
RSD_Q(S)
=
\min_{M}
\{|M|:S\cup M\text{ sufficient}\}.
$$

Then:

$$
RSD_Q(\varnothing)=6.
$$

If we already know:

$$
\{C,X,A,Dp\}
$$

then:

$$
RSD_Q=2
$$

and the missing dimensions are:

$$
\{S,T\}.
$$

This is highly actionable.

---

# 16. KnowledgeOS can now answer:

> “What do I need to know before I can determine this?”

That becomes:

$$
\boxed{
MissingInformation_Q(K)
=
MIA_Q(Representation(K)).
}
$$

This is much stronger than simply returning:

> Unknown.

KnowledgeOS can say:

> **Unknown because Source and Time are still required for this determination.**

That is a genuine epistemic capability.

---

# 17. Example

Suppose current knowledge contains:

```text
Content      ✓
Context      ✓
Authority    ✓
Dependency   ✓
Source       ?
Time         ?
```

Then:

$$
S=
\{C,X,A,Dp\}.
$$

The exact solver discovers:

$$
MIA_Q(S)=\{S,T\}.
$$

So the system should not ask for arbitrary additional evidence.

It should specifically seek:

1. Source
2. Time

This is **targeted inquiry**.

---

# 18. Definition: Knowledge Gap

The earlier attached document proposed:

$$
Gap(K)=D^\star\setminus D_K.
$$

That idea is useful, but there is an important correction.

We usually do **not know \(D^\star\)**.

Therefore we cannot generally compute:

$$
D^\star\setminus D_K.
$$

That would assume knowledge of the complete universe of relevant dimensions.

This conflicts with the Zero Lens's own principle:

$$
\boxed{
\text{Unknown dimension}\neq\text{unknown value}.
}
$$

The document correctly identifies this boundary problem. 

---

# 19. Better definition: Declared Knowledge Gap

We should instead define:

$$
Gap_{U,Q}(K)
=
U_Q\setminus D_K
$$

where:

$$
U_Q
$$

is an **explicitly declared dimension universe for task \(Q\)**.

Therefore:

$$
\boxed{
Gap_{U,Q}\neq D^\star\setminus D_K.
}
$$

This is a major architectural correction.

We can measure gaps relative to a declared inquiry universe, but must not pretend that this proves global completeness.

---

# 20. Definition: Coverage

Similarly:

$$
Coverage(K,U)
=
\frac{|D_{assessed}|}{|D_U|}
$$

is valid only relative to a declared \(U\).

Therefore:

$$
Coverage=100\%
$$

means:

> all dimensions in the declared scope were assessed.

It does **not** mean:

> the knowledge is complete.

Hence:

$$
\boxed{
ScopeCoverage\neq GlobalCompleteness.
}
$$

This should replace the stronger implication suggested in the attachment.

---

# 21. The "Zero element" needs correction

The attached document proposes:

$$
\mathbf 0=\text{epistemic void}
$$

and:

$$
K\sqcup\mathbf0=K.
$$

The algebraic identity law itself is fine if we define an appropriate mathematical structure.

But we should **not automatically make this a Kernel primitive**.

Why?

Because these are different concepts:

* no knowledge asserted,
* no evidence,
* no dimension assessed,
* unknown value,
* unresolved proposition,
* empty representation,
* logically false proposition.

They must not collapse into one "epistemic void."

Therefore:

$$
\boxed{
ZeroElement\neq Unknown\neq False\neq EmptyEvidence.
}
$$

The Zero Lens should become a **reasoning/assurance regime**, not necessarily a Kernel entity.

This is a significant architecture optimization.

---

# 22. Definition: Zero State

If we need it operationally, define:

$$
ZeroState_{\Gamma,Q}
$$

as:

> the explicitly specified initial epistemic state under regime \(\Gamma\) and task \(Q\), containing no validated task-relevant assertions.

That is safer than claiming there is a universal metaphysical "epistemic void."

---

# 23. The attached document's Yoni proposal also needs refinement

The document proposes:

$$
\mathcal Y_t\otimes K_t=K_{t+1}.
$$

The underlying idea is useful:

> knowledge is generated through interaction and inquiry.

But I would not introduce:

$$
\mathcal Y
$$

as a Kernel primitive yet.

We already have:

$$
Intervention
$$

$$
Interaction
$$

$$
CandidateGeneration
$$

$$
Validation
$$

$$
Assessment
$$

$$
Determination
$$

and:

$$
KnowledgeUpdate.
$$

These provide the executable process.

Therefore we should first test whether a separate "epistemic field" adds capability.

This follows our established methodology:

$$
\boxed{
CapabilityGap
\rightarrow
MathematicalHypothesis
\rightarrow
Experiment.
}
$$

Not:

$$
MathematicalTheory
\rightarrow
Architecture.
$$

---

# 24. LG-07R therefore gives us a new core principle

$$
\boxed{
\text{The next piece of information should be selected by its ability to eliminate material determination ambiguity.}
}
$$

This is much more precise than:

> acquire more information.

---

# 25. Connection to interaction theory

Our ground truth was:

$$
D=
(CX)\lor(ADp)\lor(ST).
$$

The minimal information set is:

$$
\{C,X,A,Dp,S,T\}.
$$

Notice something important:

The **same interactions** that determine the outcome also determine the **minimum information required to resolve the outcome**.

Therefore:

$$
\boxed{
InteractionStructure
\rightarrow
InformationRequirement.
}
$$

This is a major connection between LG-07J/M/N and LG-07R.

---

# 26. New concept: Information Interaction

Define an **InformationInteraction** as a set of dimensions whose joint availability is necessary to resolve a determination.

For example:

$$
II_1=\{C,X\}
$$

$$
II_2=\{A,Dp\}
$$

$$
II_3=\{S,T\}.
$$

This is related to `Interaction`, but should not be treated as automatically identical.

Why?

Because:

$$
Interaction
$$

describes how variables jointly affect an outcome, while:

$$
InformationInteraction
$$

describes how variables jointly affect **resolvability of a task**.

So:

$$
\boxed{
OutcomeInteraction\neq InformationInteraction
}
$$

although they may coincide in some regimes.

---

# 27. This is potentially very important

We now have two graphs/hypergraphs:

### Outcome interaction hypergraph

$$
H_O=(D,E_O)
$$

with:

$$
E_O=
\{
\{C,X\},
\{A,Dp\},
\{S,T\}
\}.
$$

### Information requirement hypergraph

$$
H_I=(D,E_I).
$$

In this synthetic world:

$$
E_O=E_I.
$$

But this is an **empirical result**, not a theorem.

A future benchmark should deliberately construct cases where:

$$
E_O\neq E_I.
$$

That will tell us whether these should be separate concepts in the architecture.

---

# 28. ML experiment

We also tested ML against this structure.

Using the full eight-dimensional representation:

* Logistic Regression achieved about **81.8%** on one held-out split.
* Random Forest achieved **100%** on that split.
* Gradient Boosting achieved **100%** on that split.
* A decision tree reached:

  * depth 2: 73.4%
  * depth 3: 82.8%
  * depth 4: 92.2%
  * depth 5: 98.4%
  * depth 6: **100%**

The depth-6 exact tree used:

$$
C,S,A,X,T,Dp
$$

and assigned zero importance to:

$$
R,L.
$$

This is encouraging, but it is **not a proof of minimality**.

The exact exhaustive lattice solver proves the minimal sufficient set.

So the correct architecture remains:

$$
\boxed{
ML\rightarrow Candidate
\rightarrow
ExactValidation
}
$$

not:

$$
ML\rightarrow Truth.
$$

---

# 29. Why Logistic Regression failed

This is theoretically useful.

Our function is:

$$
(CX)\lor(ADp)\lor(ST).
$$

A plain linear logistic model does not naturally represent these Boolean conjunctions and their OR composition without feature interactions.

Thus:

$$
ModelClassInsufficient
$$

is a valid diagnosis.

This reinforces another KnowledgeOS principle:

$$
\boxed{
PredictionFailure\neq KnowledgeFailure.
}
$$

The model may be inadequate while the underlying representation is sufficient.

---

# 30. Definition: Model-Class Sufficiency

A model class \(\mathcal M\) is sufficient for task \(Q\) over representation \(R\) if there exists:

$$
f\in\mathcal M
$$

such that:

$$
f(R(x))=D_Q(x)
$$

for every state in the declared benchmark.

Thus:

$$
RepresentationSufficiency
$$

and:

$$
ModelClassSufficiency
$$

are separate gates.

Our benchmark demonstrates why.

---

# 31. The new KnowledgeOS decision pipeline

We can now formalize the complete process:

$$
\boxed{
Task
\rightarrow
DeclareScope
\rightarrow
Represent
\rightarrow
TestSufficiency
\rightarrow
DetectMaterialGaps
\rightarrow
AcquireInformation
\rightarrow
Validate
\rightarrow
Recalculate
\rightarrow
Determine
}
$$

And if ML is used:

$$
CandidateDiscovery
$$

sits inside this pipeline rather than replacing it.

---

# 32. Architecture optimization

I would now **not** add `Gap` as a primitive Kernel operation.

Instead:

### L1 — Knowledge State

```text
KnowledgeState
KnowledgeFrontier
KnowledgeGap
```

### L0.5 — Representation

```text
Representation
RepresentationRefinement
RepresentationProjection
RepresentationSufficiency
InformationDimension
```

### L2 — Reasoning

```text
Materiality
Interaction
InformationInteraction [candidate]
KnowledgeRefinement
ReassessmentTrigger
AffectedRegion
IncrementalRecalculation
InformationAcquisition
```

### L4 — Assurance

```text
RepresentationSufficiencyValidation
MaterialGapValidation
MinimalSufficiencyValidation
RefinementValidation
RecalculationValidation
```

### L5 — Intelligence

```text
CandidateMissingDimension
CandidateInformationAcquisition
CandidateInteraction
```

This is cleaner than adding a huge:

$$
\mathfrak K_{complete}
$$

object.

---

# 33. Revised architecture

The key part now becomes:

```text
L0   SEMANTIC KERNEL
     Identity
     TypedRelation
     SemanticContract

L0.5 REPRESENTATION
     Representation
     FactorRepresentation
     RepresentationTransformation
     RepresentationRefinement
     RepresentationProjection
     SemanticNormalization
     ContextAlignment
     RepresentationSufficiency
     RepresentationCollision
     CanonicalRepresentation
     EquivalenceContract
     TaskRelativeEquivalence
     InformationDimension

L1   KNOWLEDGE STATE
     Assertion
     Evidence
     Context
     Provenance
     Validity
     History
     KnowledgeFrontier
     KnowledgeGap

L2   REASONING & EVOLUTION
     Assert
     Relate
     DerivedState
     Intervention
     Materiality
     Interaction
     InformationInteraction [candidate]
     KnowledgeRefinement
     KnowledgeUpdate
     ReassessmentTrigger
     InformationAcquisition
     AffectedRegion
     IncrementalRecalculation

L2IS SEARCH
     SearchScope
     CandidateGenerator
     CandidatePrioritizer
     PruningRule
     SearchStrategy
     SearchCertificate

L2LG STRUCTURAL REASONING
     Boundary
     BoundarySummary
     GlobalConsistency
     GlobalRealization
     StructuralInvariant
     StructuralEquivalence
     Obstruction
     StructuralDiagnosis

L2M MATHEMATICAL REGIMES
     Graph
     Hypergraph
     Boolean/Fourier
     SAT
     CSP
     XOR
     SMT
     LinearAlgebra
     Cohomology [experimental]
     Sheaf [experimental]

L3 ASSESSMENT
     Probability
     Fuzzy
     Conflict
     Fragility
     Robustness
     Uncertainty
     Calibration
     MaterialityAssessment
     InteractionAssessment

L4 ASSURANCE
     ExactValidation
     RepresentationValidity
     RepresentationSufficiencyValidation
     MinimalSufficiencyValidation
     EquivalenceValidation
     RefinementValidation
     MaterialGapValidation
     RecalculationValidation
     RepresentationInvarianceValidation
     StructuralCertificate
     InteractionCertificate
     SearchCertificate
     IndependentVerification
     Counterexample
     FaultInjection
     InvariantTesting

L5 INTELLIGENCE
     CandidateInteraction
     CandidateEquivalence
     CandidateInvariant
     CandidateObstruction
     CandidateMissingDimension
     CandidateInformationAcquisition
     CandidateScoring
     MLModel

L6 GOVERNANCE
     Authority
     Policy
     Accountability
```

---

# 34. One concept should remain outside the Kernel

The attached document's:

$$
\mathcal Y
$$

should currently remain **experimental**.

We already have a stronger executable abstraction:

$$
\boxed{
Inquiry
\rightarrow
Candidate
\rightarrow
Validation
\rightarrow
Assessment
\rightarrow
Determination
\rightarrow
Gap
\rightarrow
Inquiry
}
$$

If experiments show that this cannot represent some required interaction structure, then we can introduce an epistemic-field abstraction.

This follows our ablation methodology.

---

# 35. The most important new equation

I would now make this a candidate KnowledgeOS law:

$$
\boxed{
MIA_{\Gamma,Q}(K)
=
\min
\left\{
M:
Determination_{\Gamma,Q}(K\cup M)
\text{ becomes uniquely resolvable}
\right\}
}
$$

This is a genuine computational definition.

It tells KnowledgeOS:

> **What is the smallest additional information needed to resolve the current question?**

---

# 36. Connection to Value of Information

Then our earlier Bayesian concept fits naturally:

$$
MIA
\rightarrow
CandidateAcquisition
\rightarrow
VoI
\rightarrow
AcquisitionPriority.
$$

So:

$$
\boxed{
StructuralNecessity
\rightarrow
DecisionValue
}
$$

rather than using probability to determine structural necessity.

This is a very clean separation of concerns.

---

# 37. Where we are now

We have moved significantly beyond the original idea of a "knowledge graph."

KnowledgeOS can now be conceptualized as a system that asks:

1. **What distinctions are represented?**
2. **Are they sufficient for the task?**
3. **Which missing distinctions are materially relevant?**
4. **What is the minimum information needed?**
5. **Which acquisition has the highest value?**
6. **What must be recalculated after acquisition?**
7. **Which determination follows after validation?**
8. **Which new frontier remains?**

That is a much more rigorous epistemic architecture.

---

# 38. What LG-07R does NOT prove

We must be disciplined.

It does **not** prove:

* that all real-world knowledge has eight dimensions;
* that all tasks have a unique minimal representation;
* that information interactions always equal outcome interactions;
* that ML can discover minimal representations reliably;
* that a lattice is the universal mathematical model of knowledge;
* that category theory is necessary;
* that sheaf theory is necessary.

It proves only the corresponding properties of this exact synthetic benchmark.

That is exactly how the research should proceed.

---

# 39. Next step: LG-07S

The next experiment should be more difficult:

## **LG-07S — Non-Unique Minimal Representations and Information Substitution**

Construct a world where there are **multiple different minimal sufficient representations**.

For example:

$$
S_1=\{A,B,C\}
$$

and:

$$
S_2=\{X,Y,Z\}
$$

both independently determine the same result.

Then test:

$$
S_1\neq S_2
$$

but:

$$
Determination(S_1,Q)
=
Determination(S_2,Q).
$$

This will force us to formalize:

* `AlternativeSufficientRepresentation`
* `InformationSubstitution`
* `TaskRelativeSufficiency`
* `SufficiencyEquivalence`
* `MinimalRepresentationClass`
* `RepresentationLattice`
* `CanonicalSufficientRepresentation`.

This is also the point where **category/lattice theory may begin to add genuine mathematical value**, because we will have to reason about equivalence classes of sufficient representations rather than one unique representation.

If we eventually need formal category-theoretic research, I still want you to tell me **which category theory book you want us to use** rather than choosing one silently.

---

## Progress

* **LG-07R completed computationally:** all \(2^8=256\) representation subsets were evaluated.
* Found the unique minimal sufficient representation:

  $$
  \{Content,Source,Authority,Context,Time,Dependency\}.
  $$
* Representation/Lineage were proven redundant **for this declared benchmark**.
* Exact minimum acquisition distance ranges from 0 to 6 dimensions.
* Defined `MinimalSufficientRepresentation`, `MinimalInformationAcquisition`, `RepresentationSufficiencyDistance`.
* Connected interaction structure to information requirements.
* ML successfully discovered the relevant six-factor structure with tree/ensemble methods, while logistic regression exposed model-class insufficiency.
* Corrected the Zero Lens: **global completeness cannot be computed without a declared universe/scope**.
* Corrected the proposed universal `ZeroElement`, avoiding the collapse of unknown/empty/false/unassessed.
* The architecture is now much closer to an **active epistemic reasoning system**, not merely a knowledge repository.

## Remaining TODOs

* **LG-07S:** construct multiple non-equivalent minimal sufficient representations.
* Formalize information substitution and sufficiency equivalence.
* Determine whether minimal representations form useful equivalence classes/lattice structures.
* Test whether outcome-interaction and information-interaction hypergraphs can differ.
* Test active information acquisition with Bayesian VoI.
* Then perform the **category-theoretic capability test**.
* After that, return to the **sheaf/local-global capability test**.
* Keep `Yoni field`, `Zero algebra`, and advanced sheaf/category machinery **experimental until a demonstrated capability gap requires them**.
