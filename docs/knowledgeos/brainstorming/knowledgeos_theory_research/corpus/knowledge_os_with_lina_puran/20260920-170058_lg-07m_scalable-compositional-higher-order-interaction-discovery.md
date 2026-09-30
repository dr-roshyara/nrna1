# LG-07M — Scalable and Compositional Higher-Order Interaction Discovery

We can now advance from **LG-07L (existence of higher-order interactions)** to the harder question:

$$
\boxed{
\text{Can KnowledgeOS discover higher-order interactions without exhaustive }2^n\text{ search?}
}
$$

This is an important step because LG-07L established that pairwise representations can lose real information. But if the only solution were to enumerate every subset of factors, the approach would not scale.

I therefore treated LG-07M as a **search/complexity/ML benchmark**, not merely another representation experiment.

---

# 1. First principle: the exponential problem is real

For \(n\) factors, the number of non-empty intervention sets is:

$$
2^n-1.
$$

| Factors \(n\) |      Candidate subsets |
| ------------: | ---------------------: |
|            10 |                  1,023 |
|            20 |              1,048,575 |
|            30 |          1,073,741,823 |
|            50 | \(1.126\times10^{15}\) |
|           100 | \(1.268\times10^{30}\) |

So:

$$
\boxed{
\text{Exhaustive interaction discovery is exponential in the worst case.}
}
$$

This is not merely an implementation problem.

For an arbitrary Boolean function of \(n\) binary variables, there are \(2^n\) possible input states and \(2^{2^n}\) possible Boolean functions.

Without structural assumptions, we cannot generally infer all higher-order structure from a small number of observations.

This gives us an important KnowledgeOS principle:

$$
\boxed{
Scalability requires additional structure, assumptions, sparsity, or approximation.
}
$$

---

# 2. Define `InteractionSparsity`

A KnowledgeOS interaction structure is **sparse** when only a small fraction of possible factor combinations are materially interacting.

For order \(k\):

$$
IS_k=
\frac{
|\mathcal I_k|
}{
\binom nk
}
$$

where:

* \(\mathcal I_k\) = validated interactions of order \(k\),
* \(n\) = number of factors.

For our previous 10-factor example:

$$
|\mathcal I_3|=1
$$

while:

$$
\binom{10}{3}=120.
$$

Therefore:

$$
IS_3=\frac1{120}\approx0.833\%.
$$

This is strong sparsity.

---

# 3. Define `SparseInteractionStructure`

A **SparseInteractionStructure** is an interaction structure in which:

$$
|\mathcal I|\ll 2^n.
$$

Example:

```text
100 factors
↓
millions/billions of possible subsets
↓
only 20 validated material interactions
```

KnowledgeOS should exploit this **only when sparsity is validated or explicitly assumed**.

We must not silently assume:

$$
\text{real KnowledgeOS data is sparse}.
$$

That is an empirical question.

---

# 4. The first important negative result

A tempting algorithm is:

```text
Find important single factors
       ↓
extend important factors to pairs
       ↓
extend important pairs to triples
       ↓
continue
```

This looks computationally attractive.

But it is **not sound**.

Consider:

$$
Y=ABC.
$$

The first- and second-order coefficients are all zero:

$$
\hat f(A)=\hat f(B)=\hat f(C)=0
$$

and:

$$
\hat f(AB)=
\hat f(AC)=
\hat f(BC)=0.
$$

But:

$$
\hat f(ABC)=1.
$$

Therefore a search rule saying:

> "If all lower-order interactions are zero, discard the higher-order candidate"

would prune the true interaction.

Hence:

$$
\boxed{
LowerOrderNonMateriality
\not\Rightarrow
HigherOrderNonMateriality.
}
$$

This is an important falsification of naive hierarchical search.

---

# 5. New KnowledgeOS concept: Hierarchical Screening

### Definition

**Hierarchical Screening** is a search strategy that uses lower-order information to decide which higher-order candidates should be investigated.

Example:

$$
A,B,C
$$

are considered for a triple only if some pair involving them passes a preliminary test.

This can be extremely efficient.

But our XOR/parity example proves:

$$
\boxed{
NaiveHierarchicalScreening
\text{ is not universally sound.}
}
$$

This must become an explicit architectural warning.

---

# 6. Search Soundness

Define:

$$
SearchSoundness
$$

as:

$$
\boxed{
\text{Every interaction reported by the search is actually validated.}
}
$$

Formally:

$$
Reported(S)
\Rightarrow
ValidatedInteraction(S).
$$

A search with:

$$
SearchSoundness=100\%
$$

does not necessarily find everything.

---

# 7. Search Completeness

Define:

$$
SearchCompleteness_k
$$

as:

$$
TrueInteraction(S),\ |S|\le k
\Rightarrow
Reported(S).
$$

So:

$$
SearchSoundness\neq SearchCompleteness.
$$

A conservative search could have:

$$
Soundness=100\%
$$

but:

$$
Completeness=20\%.
$$

This distinction is essential for KnowledgeOS.

---

# 8. New concept: Safe Pruning

A candidate may be discarded only if we possess a valid reason that it cannot contain the target property.

Formally, a pruning rule \(P\) is **sound** if:

$$
P(S)=Discard
\Rightarrow
\neg ValidInteraction(S).
$$

This is much stronger than:

> "The ML model thinks it is unlikely."

Therefore:

$$
\boxed{
MLProbability\neq SafePruning.
}
$$

ML can prioritize candidates, but it should not silently eliminate candidates unless a formally validated pruning rule exists.

---

# 9. Exact benchmark: sparse 20-factor world

I constructed a 20-factor synthetic world:

$$
X_1,\ldots,X_{20}\in\{-1,+1\}.
$$

The true function contains five interactions:

$$
\begin{aligned}
f(X)=
&\;X_1X_4X_8\\
&-0.7X_3X_6\\
&+1.2X_{10}X_{12}X_{15}\\
&-0.9X_2X_{14}\\
&+0.5X_5X_7X_{19}.
\end{aligned}
$$

So the true interaction support is:

$$
\mathcal I=
\{
\{1,4,8\},
\{3,6\},
\{10,12,15\},
\{2,14\},
\{5,7,19\}
\}.
$$

There are only five interactions.

---

# 10. Exact ground-truth computation

The full state space contains:

$$
2^{20}=1,048,576
$$

states.

I computed the complete Boolean/Fourier transform.

The exact non-zero coefficients recovered were:

$$
\begin{array}{c|c}
Interaction & Coefficient\\
\hline
\{3,6\}&-0.7\\
\{1,4,8\}&1.0\\
\{2,14\}&-0.9\\
\{10,12,15\}&1.2\\
\{5,7,19\}&0.5
\end{array}
$$

and all other coefficients were zero within numerical precision.

So:

$$
\boxed{
ExactInteractionRecovery=100\%
}
$$

for this synthetic world.

This is our ground-truth oracle.

---

# 11. Candidate-space reduction

Suppose we know only that interactions have order at most 3.

Then the number of candidate interaction sets is:

$$
\binom{20}{1}
+
\binom{20}{2}
+
\binom{20}{3}.
$$

Therefore:

$$
20+190+1140=1350.
$$

Only five are true.

So:

$$
InteractionSparsity=
\frac5{1350}
\approx0.370\%.
$$

This is extremely sparse.

---

# 12. ML candidate discovery

I then used a sparse regression model as a **candidate generator**, not as a validator.

The model received 8,000 randomly sampled states and all order-\(\le3\) Boolean interaction features.

The true five interactions were recovered.

Candidate result:

$$
CandidateRecall=100\%
$$

$$
CandidatePrecision=100\%
$$

on this particular synthetic benchmark.

This is encouraging but **not evidence that ML has solved higher-order interaction discovery**.

Why?

Because the benchmark was deliberately clean and used the same Boolean basis as the generating mechanism.

So this result demonstrates:

$$
\boxed{
ML\ can efficiently propose sparse interaction candidates under a favorable representation.
}
$$

It does not demonstrate universal structural generalization.

---

# 13. Candidate reduction

The ML generator proposed five candidates from the 1,350 order-\(\le3\) possibilities.

Therefore:

$$
CandidateReduction
=
1-\frac5{1350}
$$

giving:

$$
\boxed{
99.63\%
}
$$

candidate-space reduction.

This is potentially valuable.

But again:

$$
CandidateReduction\neq Proof.
$$

---

# 14. Exact validation remains necessary

The correct pipeline is therefore:

```text
All possible interactions
          ↓
Candidate generation
          ↓
ML / symbolic search
          ↓
CandidateInteraction
          ↓
ExactInteractionValidator
          ↓
InteractionCertificate
          ↓
IndependentVerification
```

This gives us the same architecture principle that emerged from dependency and certificate research:

$$
\boxed{
Discovery\neq Validation.
}
$$

And:

$$
\boxed{
Prediction\neq Determination.
}
$$

---

# 15. A much stronger ML negative test

We should ask:

> Can ML recover a triple interaction if it is given only lower-order information?

For:

$$
f_1=ABC
$$

and:

$$
f_0=0,
$$

all first- and second-order coefficients are identical:

$$
\phi_{\le2}(f_1)
=
\phi_{\le2}(f_0).
$$

I constructed a balanced classification problem where the ML model received only this lower-order representation.

A logistic model achieved:

$$
\boxed{50\%}
$$

accuracy.

That is exactly chance level.

This is not an ML weakness.

It is an **information-theoretic representation limitation**.

No deterministic classifier can recover information that has been removed from its input.

Therefore:

$$
\boxed{
ML\ cannot compensate for an information-destroying representation.
}
$$

This reinforces our earlier:

$$
RepresentationSufficiency
\prec
ModelSelection.
$$

---

# 16. This is one of the most important KnowledgeOS results

We now have three distinct failure modes:

### Failure A — insufficient representation

$$
\phi(x)=\phi(y)
$$

while:

$$
Result(x)\neq Result(y).
$$

No model can recover the missing distinction.

---

### Failure B — insufficient model class

Representation contains the information, but the algorithm cannot represent the relationship.

Example:

$$
ABC
$$

with a model restricted to order \(\le2\).

---

### Failure C — insufficient search

The representation and model are capable, but the algorithm does not explore the right candidate.

This gives:

$$
\boxed{
RepresentationFailure
\neq
ModelFailure
\neq
SearchFailure.
}
$$

This distinction should become part of KnowledgeOS assurance.

---

# 17. New concept: SearchFailureDiagnosis

Define:

$$
SearchFailureDiagnosis
$$

as the classification of why an interaction was not discovered.

Possible values:

```text
REPRESENTATION_INSUFFICIENT
MODEL_CLASS_INSUFFICIENT
SEARCH_INCOMPLETE
PRUNING_UNSOUND
VALIDATION_FAILURE
DATA_INSUFFICIENT
UNKNOWN
```

This is far more useful than simply saying:

> "The model missed it."

---

# 18. New concept: Search Certificate

A search algorithm should produce:

$$
SearchCertificate
$$

containing:

```text
Search regime
Candidate universe
Search strategy
Pruning rules
Candidates examined
Candidates pruned
Pruning justification
Validation results
Completeness claim
```

For example:

```text
Completeness:
all interactions of order <= 3

Soundness:
validated

Pruning:
none

Result:
5 interactions
```

Or:

```text
Completeness:
not established

Search:
ML-guided

Pruning:
probabilistic

Result:
5 candidates
```

These are epistemically very different.

---

# 19. Interaction discovery should therefore have three states

### `Candidate`

ML/symbolic algorithm proposes it.

### `Validated`

Exact method confirms it.

### `Complete`

We have a proof or exhaustive argument that the relevant candidate space has been fully covered.

Therefore:

$$
Candidate
\neq
Validated
\neq
Complete.
$$

This is an important new distinction.

---

# 20. New concept: Completeness Scope

A statement like:

> "All interactions have been found."

is too strong.

Instead say:

$$
Complete(\Gamma,Q,\mathcal U)
$$

where:

$$
\mathcal U
$$

is the explicitly covered universe.

Example:

> Complete for all interactions of order \(\le3\) over the 20 declared factors under the Boolean interaction regime.

This is scientifically defensible.

---

# 21. Complexity insight

For \(n=100\):

$$
\binom{100}{3}=161,700.
$$

That is actually manageable.

But:

$$
\binom{100}{10}
$$

is enormous.

So we should not automatically search all orders.

A practical architecture should use:

$$
\boxed{
OrderBound
}
$$

as an explicit experimental parameter.

---

# 22. New concept: OrderBound

An `OrderBound` specifies the maximum interaction order for which completeness is claimed.

Example:

$$
OrderBound=3.
$$

Then:

$$
SearchCompleteness_3
$$

can potentially be established.

But we must never infer:

$$
SearchCompleteness_3
\Rightarrow
SearchCompleteness_{all}.
$$

---

# 23. Important discovery about sparse search

The 20-factor benchmark suggests:

$$
SparseInteraction
$$

can make ML-guided discovery extremely effective.

But the XOR counterexample proves:

$$
SparseInteraction
\not\Rightarrow
HierarchicalPruningSafe.
$$

Therefore the correct architecture is not:

```text
low-order screening → prune
```

but:

```text
structural candidate generation
        ↓
possibly probabilistic prioritization
        ↓
exact validation
        ↓
certificate
```

If pruning is used, it needs a **mathematical bound**.

---

# 24. Branch-and-bound

### Definition

**Branch-and-bound** recursively divides the candidate space into regions and discards a region only when a valid bound proves that no candidate inside can satisfy the target condition.

For example:

$$
UpperBound(R)<Threshold
\Rightarrow
Discard(R).
$$

This is potentially useful for KnowledgeOS.

But:

$$
\boxed{
A heuristic score is not a bound.
}
$$

An ML probability such as:

$$
P(Interaction|X)=0.001
$$

does not justify:

$$
Discard.
$$

It just justifies:

$$
LowPriority.
$$

---

# 25. New concept: Candidate Prioritization

Define:

$$
Priority(S)
$$

as the order in which candidate \(S\) is examined.

This does **not** change completeness.

If we examine:

```text
A first
B second
C third
```

rather than:

```text
C first
A second
B third
```

we have changed efficiency, not logical validity.

This gives us:

$$
\boxed{
Prioritization\neq Pruning.
}
$$

This is a very useful architectural separation.

---

# 26. ML should primarily prioritize

Therefore our preferred ML role is:

$$
ML
\rightarrow
Priority(Candidates)
$$

rather than:

$$
ML
\rightarrow
Delete(Candidates).
$$

This allows us to obtain:

* faster discovery,
* early high-confidence candidates,
* adaptive search,

without sacrificing completeness when exhaustive validation eventually occurs.

---

# 27. New architecture: Search Engine

I recommend adding a dedicated L2 reasoning component:

```text
InteractionSearchEngine
```

with:

```text
CandidateGenerator
CandidatePrioritizer
SearchStrategy
PruningRule
ExactValidator
SearchCertificate
```

The components have different responsibilities.

### CandidateGenerator

Creates candidates.

### CandidatePrioritizer

Ranks candidates.

### PruningRule

Removes candidates only with justified bounds.

### ExactValidator

Determines whether candidate is valid.

### SearchCertificate

Records what was and wasn't searched.

---

# 28. ML architecture

Then:

```text
                ┌─────────────────────┐
                │  Knowledge State    │
                └──────────┬──────────┘
                           ↓
                Candidate Generation
                     ↙           ↘
              Symbolic           ML
                 ↓                ↓
                 └──────┬─────────┘
                        ↓
                Candidate Prioritizer
                        ↓
                Exact Interaction
                    Validator
                        ↓
                Interaction Certificate
                        ↓
                Independent Verification
```

This is significantly safer than putting ML directly into determination.

---

# 29. DDD architecture refinement

The domain should not contain:

```text
LassoModel
RandomForest
FourierTransform
BranchAndBound
```

Those are infrastructure/reasoning mechanisms.

The domain concepts remain:

```text
Interaction
InteractionOrder
InteractionProfile
Materiality
CandidateInteraction
ValidatedInteraction
SearchScope
SearchCertificate
```

The mathematical implementations belong in a reasoning/application layer.

Therefore:

$$
\boxed{
DDD\ DomainLanguage\ remains\ independent\ of\ algorithmic\ realization.
}
$$

---

# 30. New distinction: Search Scope

Define:

$$
SearchScope=
(Factors,OrderBound,Regime,Task,Constraints)
$$

Example:

$$
SearchScope=
(
20\ factors,
order\le3,
Boolean/Fourier,
Task\ Q,
deterministic
).
$$

A result is only complete relative to this scope.

This fits perfectly with our existing `ConstraintRegime` and `EquivalenceContract`.

---

# 31. Connection to KEAP-1

The 11 Critical Questions now have a direct computational role.

### Q1

What are we trying to prove?

> Sparse higher-order interaction discovery can be accelerated without losing validated interactions.

### Q2

What evidence?

* exact oracle,
* sparse search,
* ML prioritization,
* adversarial interactions.

### Q3

What does "discovered" mean?

$$
Candidate
$$

or:

$$
Validated
$$

or:

$$
Complete?
$$

We now explicitly distinguish them.

### Q4

Trade-offs:

$$
Recall,\ Precision,\ Runtime,\ Completeness.
$$

### Q5

Assumptions:

* sparsity,
* maximum order,
* Boolean representation,
* exact validator availability.

### Q6

Fallacy:

> "ML did not find it, therefore it doesn't exist."

Invalid.

### Q7

Evidence quality:

Exact benchmark + adversarial corpus.

### Q8

Rival explanation:

Maybe the representation, not the search algorithm, is insufficient.

### Q9

Statistics:

ML performance must be reported separately from exact logical correctness.

### Q10

Omission:

Orders \(>3\), noisy data, continuous variables, real-world corpora.

### Q11

Conclusion:

Qualified and scope-bound.

---

# 32. A deeper theoretical result

We can now formulate:

## KnowledgeOS Search Trilemma

For unrestricted interaction discovery, we cannot simultaneously assume:

1. arbitrary number of factors,
2. arbitrary interaction order,
3. polynomial-scale exhaustive certainty.

Some structural restriction must enter.

For example:

$$
\text{bounded order}
$$

or:

$$
\text{sparsity}
$$

or:

$$
\text{factorization}
$$

or:

$$
\text{known algebraic structure}.
$$

This is not a weakness of KnowledgeOS.

It is a fundamental computational constraint.

---

# 33. This changes our interpretation of ML

ML is particularly useful because it can exploit statistical regularities to make:

$$
CandidateSearch
$$

more efficient.

But ML does not remove the underlying complexity.

It changes:

$$
\text{where we search first}
$$

rather than guaranteeing:

$$
\text{what exists}.
$$

Thus:

$$
\boxed{
ML\ improves\ search\ efficiency;\ exact\ reasoning\ establishes\ validity.
}
$$

---

# 34. What about real-world KnowledgeOS?

The synthetic Boolean benchmark is deliberately simple.

Real KnowledgeOS factors may include:

```text
Source
Authority
Context
Time
Representation
Dependency
Lineage
Transformation
Policy
Geography
Evidence type
Observation method
```

and these may have:

* categorical values,
* continuous values,
* missing values,
* uncertainty,
* temporal states,
* conditional dependencies.

Therefore our Boolean benchmark is a **mathematical laboratory**, not a model of the entire real world.

Its purpose is to isolate one capability.

---

# 35. We should therefore create a second benchmark family

After LG-07M:

## LG-07N — Mixed-Type Interaction Benchmark

Factors:

$$
X=
(Categorical,
Binary,
Continuous,
Temporal,
Graph)
$$

and interactions such as:

$$
Authority\times Context\times Time
$$

or:

$$
Source\times Dependency\times Representation.
$$

Then compare:

* symbolic,
* statistical,
* graph,
* hypergraph,
* ML,
* exact constraint reasoning.

This will be much closer to real KnowledgeOS.

---

# 36. But not yet

We should not jump to LG-07N before finishing the logical question:

$$
\boxed{
Can the search machinery preserve soundness and completeness while becoming scalable?
}
$$

That is still the central LG-07M question.

---

# 37. Updated final architecture

The current architecture should now be:

```text
L0   SEMANTIC KERNEL
     Identity
     TypedRelation
     SemanticContract

L0.5 REPRESENTATION
     Representation
     SemanticNormalization
     ContextAlignment
     RepresentationSufficiency
     RepresentationCollision
     CanonicalRepresentation
     CollapseGuard

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
     InteractionHierarchy
     InteractionProfile
     AffectedRegion
     IncrementalRecalculation

L2IS INTERACTION SEARCH
     SearchScope
     InteractionSearchEngine
     CandidateGenerator
     CandidatePrioritizer
     PruningRule
     SearchStrategy
     SearchCertificate
     SearchFailureDiagnosis

     InteractionHypergraph [candidate]
     InteractionClosure [candidate]

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

L5   INTELLIGENCE
     CandidateInteraction
     CandidateConstraint
     CandidateInvariant
     CandidateEquivalence
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

# 38. One architectural cleanup

I would **not** make `SearchCertificate` both an L2 and L4 concept.

Its creation belongs to the search subsystem, but its assurance/verification belongs in L4.

So conceptually:

$$
L2IS:
SearchCertificateDraft
$$

and:

$$
L4:
ValidatedSearchCertificate.
$$

This maintains our fundamental separation:

$$
\boxed{
Production\ of\ evidence
\neq
Verification\ of\ evidence.
}
$$

The same pattern already worked for our certificate fault-injection experiments.

---

# 39. Current KnowledgeOS theorem stack

We are now building something much more coherent.

### Representation

$$
\phi(x)=\phi(y)
\not\Rightarrow
x=y.
$$

### Representation sufficiency

$$
\phi(x)=\phi(y)
\Rightarrow
Result(Q,x)=Result(Q,y)
$$

must be established relative to the task.

### Interaction

$$
Materiality\neq InteractionOrder.
$$

### Higher order

$$
InteractionOrder>2
$$

is real and computationally demonstrable.

### Search

$$
SearchSoundness\neq SearchCompleteness.
$$

### ML

$$
MLCandidate\neq ValidatedInteraction.
$$

### Pruning

$$
Prioritization\neq Pruning.
$$

### Assurance

$$
Detection\neq Validation\neq Verification\neq Assessment\neq Determination.
$$

These are becoming the core logical invariants of KnowledgeOS.

---

# 40. Where we are now

### Progress

* **LG-07L:** demonstrated genuine third-order interaction.
* **LG-07M:** first scalable-search experiments completed.
* Exact 20-factor sparse benchmark successfully recovered all five planted interactions.
* Demonstrated that naive hierarchical pruning can miss pure higher-order interactions.
* Demonstrated that ML can dramatically reduce candidate space in a favorable sparse regime.
* Demonstrated that ML cannot recover information destroyed by a lower-order representation.
* Formalized:

  * `InteractionSparsity`
  * `SearchSoundness`
  * `SearchCompleteness`
  * `SafePruning`
  * `CandidatePrioritization`
  * `SearchScope`
  * `SearchFailureDiagnosis`
  * `SearchCertificate`
  * `OrderBound`.
* Architecture now separates **candidate generation, prioritization, pruning, validation and verification**.

### Remaining TODOs

* **Finish LG-07M:** test sound pruning rules and mathematically justified branch-and-bound.
* Test whether sparse discovery remains reliable under noisy/incomplete evidence.
* Compare ML prioritization against symbolic search on unseen interaction structures.
* Measure actual runtime scaling as \(n\) and interaction order increase.
* Compare `InteractionHypergraph` against factor graphs and other exact representations.
* **LG-07N:** mixed categorical/continuous/temporal/graph-valued interactions.
* Only afterward revisit whether higher-order structures provide a genuine reason to introduce more sophisticated cellular/sheaf machinery.
* Keep the permanent architectural rule: **no mathematical machinery enters the KnowledgeOS core merely because it can represent something; it enters only after a demonstrated capability gap.**
