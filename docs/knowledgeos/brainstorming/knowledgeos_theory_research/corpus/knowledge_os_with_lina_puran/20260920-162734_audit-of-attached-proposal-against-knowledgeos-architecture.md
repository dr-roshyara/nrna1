I have now treated the attached document as the **next research artifact** and audited it against the KnowledgeOS architecture and the exact experiments we have already performed. The attached proposal is the document *“Implementing Sheaves on Manifolds in the KnowledgeOS Algebra”*. 

My conclusion is important:

> **The proposal contains a valuable direction, but it is not yet mathematically safe to implement literally.**
>
> We should **not start Phase 1 exactly as written**. We should first convert it into an **axiom-gated Local–Global Structural Reasoning regime**, because several claims in the proposal are stronger than what its definitions actually establish.

This is actually good news: the corrections make the architecture more rigorous and prevent KnowledgeOS from embedding incorrect mathematics.

---

# 1. First: what the proposal is trying to do

The proposal maps:

$$
\text{Dependency Graph}
\rightarrow
\text{Topological Space}
\rightarrow
\text{Sheaf}
\rightarrow
\text{Cohomology}
\rightarrow
\text{Microsupport}
\rightarrow
\text{Derived/Perverse Machinery}.
$$

Its intended KnowledgeOS interpretation is approximately:

| Mathematical concept | Proposed KnowledgeOS interpretation      |
| -------------------- | ---------------------------------------- |
| Topological space    | Dependency context                       |
| Open set             | Epistemic neighborhood                   |
| Sheaf                | Local epistemic states                   |
| Stalk                | Local state plus dependencies            |
| \(H^0\)              | Global consistency                       |
| \(H^1\)              | Global obstruction                       |
| Microsupport         | Perturbation sensitivity                 |
| Constructibility     | Stable structural strata                 |
| Six operations       | Transformations of epistemic structures  |
| Verdier duality      | Dual reasoning perspective               |
| Perverse sheaf       | Higher-order structured epistemic object |

The proposal itself explicitly presents this as an axiom-gated research direction rather than simply inserting sheaves into the Kernel. 

That basic architectural instinct is correct.

But the mathematical mapping needs substantial tightening.

---

# 2. The most important correction

The proposal says, in effect:

$$
\boxed{
\text{Dependency Graph}
\Rightarrow
\text{Sheaf}
\Rightarrow
H^1
\Rightarrow
\text{Circular Reasoning}
}
$$

We should **not accept this chain**.

The correct architecture is:

$$
\boxed{
\text{Dependency Structure}
\rightarrow
\text{Compatibility Structure}
\rightarrow
\text{Local--Global Model}
\rightarrow
\text{Exact Analysis}
\rightarrow
\text{Obstruction}
\rightarrow
\text{Semantic Diagnosis}
}
$$

A sheaf/cohomology regime is one possible implementation of that analysis.

This distinction is now strongly supported by our previous exact experiments.

---

# 3. Why the distinction matters

We already demonstrated:

$$
\boxed{\text{Cycle}\neq\text{Contradiction}}
$$

and:

$$
\boxed{H^1\neq\text{Falsehood}}
$$

and:

$$
\boxed{UNSAT\neq\text{Semantic Diagnosis}}.
$$

Consider the triangle:

$$
A\oplus B=1
$$

$$
B\oplus C=1
$$

$$
C\oplus A=1.
$$

Each pairwise constraint is individually satisfiable.

But:

$$
1+1+1=1\pmod 2.
$$

Therefore no global assignment exists.

That is a **global obstruction**.

But it does not mean:

> one of the propositions is false.

It means:

> the specified collection of constraints cannot simultaneously be realized under the chosen mathematical regime.

That is much safer for KnowledgeOS.

---

# 4. Audit of the attached proposal

I would divide the proposal into four categories.

## A. Can be implemented now

These are sound or can be made sound with small changes:

* dependency-context abstraction;
* local sections;
* restriction maps;
* compatibility constraints;
* cellular/cochain representation;
* \(H^0/H^1\) computation for explicitly defined coefficient systems;
* obstruction certificates;
* exact local-global consistency testing;
* structural equivalence;
* perturbation sensitivity;
* axiom-gated experiments.

## B. Can be implemented, but needs a formal contract

Examples:

* epistemic topology;
* sheaf of epistemic states;
* stalks;
* constructibility;
* sheaf morphisms;
* six operations.

They need precise mathematical definitions before implementation.

## C. Research only for now

* genuine microsupport;
* cotangent-space interpretation;
* involutivity;
* Verdier duality;
* derived categories;
* perverse sheaves.

## D. Claims that should currently be rejected

Several statements in the proposal are too strong as written:

* \(H^1=\) circular reasoning;
* proposed stalk \(=\) node plus all reachable states;
* the product-state construction automatically being a sheaf of abelian groups;
* perturbation sensitivity automatically being Kashiwara–Schapira microsupport;
* graph coloring automatically producing an \(R\)-constructible sheaf;
* arbitrary KnowledgeOS dependency structures automatically being manifolds;
* the stated involutivity theorem for the discrete KnowledgeOS construction;
* six operations automatically having their KS properties;
* “perversity gives a closed algebra” without the necessary categorical hypotheses.

These should be removed from the implementation specification until proven.

The original proposal makes these mappings and claims explicitly. 

---

# 5. Term-by-term correction

This is important because KnowledgeOS needs operational definitions.

## 5.1 Dependency Graph

A dependency graph is:

$$
G_D=(V,E_D)
$$

where:

* \(V\) = knowledge objects;
* \(E_D\) = dependency relations.

Example:

```text
Source A ──→ Evidence A ──→ Model M ──→ Determination D
```

It answers:

> What depends on what?

It does **not** automatically answer:

> Are these objects mutually compatible?

Therefore:

$$
E_D\neq E_C
$$

where \(E_C\) represents compatibility constraints.

---

# 6. Compatibility Structure

This should become a more explicit KnowledgeOS concept.

A compatibility structure specifies conditions such as:

$$
C_{AB}(x_A,x_B)=1
$$

meaning:

> \(x_A\) and \(x_B\) satisfy their required compatibility condition.

Example:

```text
Evidence A
     \
      → must agree with → Model M
     /
Evidence B
```

Dependency answers:

> Does M depend on A?

Compatibility answers:

> Can A and M coexist under regime \(\Gamma\)?

These are different questions.

---

# 7. Local Constraint

A **local constraint** is a condition involving a limited neighborhood.

For example:

$$
x_A\oplus x_B=1.
$$

It can be locally satisfiable even when the entire system cannot be satisfied.

That distinction is precisely what our triangle experiment demonstrated.

---

# 8. Global Realization

A **global realization** is an assignment satisfying all relevant constraints simultaneously.

Formally:

$$
x\in X
$$

such that:

$$
C_i(x)=1
\quad\forall i.
$$

If such an \(x\) exists:

$$
GlobalRealization=TRUE.
$$

Otherwise:

$$
GlobalRealization=FALSE.
$$

But:

$$
GlobalRealization=FALSE
$$

does not itself tell us *why*.

That leads to diagnosis.

---

# 9. Obstruction

An **obstruction** is a formally validated condition showing that a specified global realization cannot be obtained under a specified regime.

For our GF(2) example:

$$
b\notin B^1
$$

is an obstruction to global realization.

But:

$$
Obstruction\neq Falsehood.
$$

This distinction should be a permanent KnowledgeOS invariant.

---

# 10. Cohomology

For the cellular model:

$$
C^0\xrightarrow{d^0}C^1\xrightarrow{d^1}C^2
$$

with:

$$
d^1d^0=0.
$$

Define:

$$
Z^1=\ker d^1
$$

and:

$$
B^1=\operatorname{im}d^0.
$$

Then:

$$
H^1=Z^1/B^1.
$$

Operationally:

### Case 1

$$
b\notin Z^1
$$

Local/higher-order compatibility failure.

### Case 2

$$
b\in Z^1\setminus B^1
$$

Locally compatible, but globally obstructed.

### Case 3

$$
b\in B^1
$$

Global realization exists.

This three-way distinction is much more useful to KnowledgeOS than the proposal's:

$$
H^1=\text{circular reasoning}.
$$

---

# 11. Exact demonstration

For the filled triangle:

$$
rank(d^0)=2
$$

and:

$$
rank(d^1)=1.
$$

Therefore:

$$
\dim H^1
=
3-2-1
=
0.
$$

For the triangle with a hole:

$$
rank(d^0)=2
$$

but there is no \(d^1\), so:

$$
\dim H^1=3-2=1.
$$

Thus:

```text
                 Filled triangle     Triangle with hole

1-skeleton             same                  same
faces                    1                     0
H¹                       0                     1
```

This is one of our strongest demonstrations that:

$$
\boxed{
1\text{-skeleton}\neq\text{higher-order structure}
}
$$

and that topology can carry information not contained in pairwise edges alone.

But it still does **not** prove that sheaf theory is computationally superior to a sufficiently informed exact solver.

That remains an open experiment.

---

# 12. The proposed "sheaf of epistemic states"

The proposal defines:

$$
\mathcal E(U)
=
\prod_{v\in U}EpistemicState(v).
$$

This is implementable.

But there is a conceptual problem.

Suppose:

```text
A = state 0
B = state 1
```

and the domain requires:

$$
A=B.
$$

Then:

$$
(0,1)\in
EpistemicState(A)\times EpistemicState(B)
$$

is still an element of the product.

So the product construction does not itself know that:

$$
(0,1)
$$

is invalid.

Therefore:

$$
\boxed{
StateSheaf\neq CompatibilityStructure
}
$$

We should introduce both.

---

# 13. Corrected formulation

Instead of only:

$$
\mathcal E(U)=\prod_{v\in U}S_v
$$

we use:

$$
\boxed{
\mathcal S(U)=\text{candidate local states}
}
$$

and:

$$
\boxed{
\mathcal C(U)=\text{compatible local states}
}
$$

with:

$$
\mathcal C(U)\subseteq\mathcal S(U).
$$

Then:

$$
GlobalSection
\in
\mathcal C(X).
$$

This is much closer to what KnowledgeOS actually needs.

---

# 14. Stalk

The proposal defines the stalk as:

$$
\mathcal E_v
=
\varinjlim_{v\in U}\mathcal E(U)
$$

and then identifies it with:

$$
EpistemicState(v)
\times
\prod_{w\in N(v)\setminus\{v\}}EpistemicState(w).
$$

The first formula is mathematically meaningful.

The second is **not generally justified merely from the first**.

A stalk is a **germ**.

### Germ

A germ records what a section looks like sufficiently locally around a point, identifying sections that agree on some smaller neighborhood.

Therefore:

$$
\boxed{
Stalk\neq Node+all\reachableDependencies
}
$$

unless we explicitly construct a topology and sheaf for which that identification can be proven.

This correction is essential.

---

# 15. Topology

The proposal defines:

$$
N(v)=\{w:\operatorname{DependsOn}^*(w,v)\}.
$$

This can lead to an Alexandrov-type topology when the dependency relation is treated as a preorder.

That is implementable.

But we must be precise about direction.

For example:

```text
A depends on B
B depends on C
```

means:

$$
A\to B\to C.
$$

The induced neighborhood can be:

$$
N(C)=\{A,B,C\}
$$

under one orientation convention.

Another convention reverses this.

Therefore the topology must specify:

$$
DirectionConvention_\Gamma.
$$

Otherwise two developers can implement opposite topologies while both believe they followed the specification.

This is exactly the sort of semantic ambiguity KnowledgeOS is supposed to prevent.

---

# 16. Microsupport — major correction

This is the most important part of the proposal that we should **not implement literally yet**.

The proposal defines:

$$
SS(\mathcal E)
=
\overline{
\{(v,\phi):
\text{perturbation in }\phi
\text{ changes }\mathcal E_v
\}
}.
$$

That is a perfectly reasonable **KnowledgeOS perturbation-sensitivity construct**.

But it is not automatically Kashiwara–Schapira microsupport.

We should therefore call the current construct:

$$
\boxed{
PerturbationSensitivityProfile
}
$$

or:

$$
PSP_\Gamma.
$$

For example:

```text
Compliance
   ↑
Remove Source A
   → determination changes
```

Then:

$$
PSP(\text{Compliance},RemoveSourceA)=1.
$$

This is extremely useful.

But we should not yet claim:

$$
PSP=SS.
$$

---

# 17. Why this distinction is crucial

True microlocal sheaf theory requires much more structure.

The KnowledgeOS dependency graph is not automatically:

$$
\text{smooth manifold}.
$$

There is no automatic:

$$
T^*X
$$

with its canonical symplectic structure.

Therefore we cannot simply say:

> dependency graph + perturbations = cotangent bundle.

That would be mathematical overreach.

---

# 18. New architecture decision

I recommend:

```text
L2LG
    LocalGlobalStructuralReasoning
        LocalConstraint
        GlobalConsistency
        StructuralEquivalence
        StructuralObstruction
        ObstructionSignature

L2M
    MathematicalRegime
        GraphRegime
        LinearAlgebraRegime
        CohomologyRegime
        SheafRegime [experimental]
        MicrolocalRegime [future]

L3
    Assessment
        PerturbationSensitivity
        Fragility
        Robustness

L4
    Assurance
        StructuralCertificate
        ObstructionCertificate
        IndependentVerification
```

This is cleaner than making microsupport an L2 primitive.

---

# 19. Constructibility

The proposal says graph coloring can create a dependency stratification and then claims \(R\)-constructibility.

The idea is useful, but the implication is too strong.

A **stratum** is a region in which the relevant structure behaves uniformly.

We can define a KnowledgeOS stratum operationally:

$$
v\sim_{stratum}w
$$

if they have equivalent relevant structural signatures under \(\Gamma\).

For example:

```text
same dependency profile
same semantic type
same context class
same perturbation profile
```

Then:

$$
V=\bigsqcup_\alpha V_\alpha.
$$

This is useful.

But:

$$
\boxed{
GraphStratification
\neq
R\text{-Constructibility}
}
$$

without the appropriate topological/geometric hypotheses.

---

# 20. Six operations

The proposal's mapping is intellectually interesting:

| KS                | Proposed KOS            |
| ----------------- | ----------------------- |
| \(Rf_*\)          | aggregation             |
| \(f^{-1}\)        | restriction             |
| \(Rf_!\)          | restricted aggregation  |
| \(f^!\)           | exceptional restriction |
| \(\otimes^L\)     | combination             |
| \(R\mathcal Hom\) | reasoning/mapping       |

But we should currently label these:

$$
\boxed{ConceptualMappings}
$$

rather than:

$$
\boxed{ValidatedKnowledgeOSOperations}.
$$

Why?

Because the mathematical properties of the KS operations depend on the actual categories, spaces, sheaves and derived constructions.

We have not constructed those fully for KnowledgeOS.

---

# 21. Tensor product is particularly dangerous

The proposal says:

$$
\otimes^L
$$

corresponds to:

> combining independent evidence.

That should **not** be accepted.

Independent evidence is an epistemic dependency concept.

Tensor product is an algebraic operation.

Therefore:

$$
\boxed{
TensorProduct\neq IndependentEvidence
}
$$

unless a formal translation contract is established.

This is exactly analogous to:

$$
\mu(x)\neq P(H|x).
$$

Same notation/domain does not imply same semantics.

---

# 22. Bayesian reasoning remains separate

KnowledgeOS already has:

$$
\Gamma_B
=
(H,Prior,LikelihoodModel,DependencyModel,\ldots).
$$

If two evidence items are dependent:

$$
P(E_1,E_2|H)
\neq
P(E_1|H)P(E_2|H).
$$

A sheaf/tensor construction cannot silently replace that dependency model.

Therefore:

$$
\boxed{
SheafReasoning\neq BayesianAggregation
}
$$

but the two can interact.

For example:

```text
Sheaf/structural regime
        ↓
detect dependency/compatibility structure
        ↓
Bayesian regime
        ↓
calculate posterior
```

This is a much safer architecture.

---

# 23. ML integration

The proposal's idea of using ML to generate perturbation directions is useful.

But the correct pipeline is:

$$
Evidence
\rightarrow ML
\rightarrow CandidatePerturbation
\rightarrow ExactValidation
\rightarrow PerturbationSensitivity
\rightarrow Assessment.
$$

Not:

$$
ML\rightarrow Microsupport.
$$

### Example

ML discovers:

> Removing Source A probably changes determination D.

Represent:

$$
CandidatePerturbation(D,Remove(A)).
$$

Then execute:

$$
D_{original}
$$

versus:

$$
D_{without A}.
$$

If:

$$
D_{original}\neq D_{without A}
$$

we establish material perturbation sensitivity.

This is excellent KnowledgeOS territory.

---

# 24. This gives us a stronger concept: Material Perturbation

Define:

$$
Material_\Gamma(p,Q)
$$

as:

> perturbation \(p\) is material to query/task \(Q\) if applying \(p\) can change the validated result of \(Q\) under regime \(\Gamma\).

Then:

$$
PSP_\Gamma(x,p)=1
$$

if:

$$
Result_\Gamma(Q,x)
\neq
Result_\Gamma(Q,Apply(p,x)).
$$

This is directly executable.

And unlike the proposed microsupport, it does not require us to pretend the KnowledgeOS graph is a smooth manifold.

---

# 25. The Nexus example

The proposal uses:

```text
Source A
Source B
   ↓
Evidence
   ↓
Model M
   ↓
Determination D
   ↓
Compliance
```

This is a good KnowledgeOS example. 

But I would model it as:

$$
G_D=(V,E_D)
$$

plus:

$$
G_C=(V,E_C)
$$

plus:

$$
P=\{\text{perturbations}\}.
$$

Then:

$$
D=f(E_1,E_2,M)
$$

and we experimentally test:

$$
D_{-A}
$$

versus:

$$
D.
$$

That gives:

$$
Materiality(A,D)
=
[D_{-A}\neq D].
$$

Now we have a real, executable epistemic property.

---

# 26. What about circular reasoning?

The proposal says:

$$
H^1=\text{circular reasoning}.
$$

We should replace this.

Suppose:

```text
A → B
B → C
C → A
```

That is a cycle.

But a cycle can be legitimate.

Example:

```text
compiler
 ↔ language specification
```

or:

```text
mutually recursive definitions
```

may be mathematically valid.

Therefore:

$$
Cycle\neq CircularReasoning.
$$

Instead:

$$
Cycle
\rightarrow
CandidateCircularDependency
$$

and then:

$$
Validation
\rightarrow
CircularReasoning
$$

only if the semantic/epistemic rules establish circular support without independent foundation.

This is a much stronger design.

---

# 27. New KnowledgeOS distinction

We should add:

$$
\boxed{
StructuralCycle
}
$$

and:

$$
\boxed{
EpistemicCircularSupport
}
$$

as separate concepts.

For example:

```text
A depends on B
B depends on A
```

is merely:

$$
StructuralCycle=true.
$$

But if:

```text
A is justified only by B
B is justified only by A
```

then:

$$
EpistemicCircularSupport=true.
$$

This distinction is extremely valuable.

---

# 28. What the sheaf regime genuinely contributes

After all these corrections, I believe the strongest potential value of sheaf theory is **not**:

> “detect cycles.”

Graph algorithms already do that.

Nor simply:

> “detect UNSAT.”

SAT/CSP/SMT already do that.

Nor:

> “calculate parity.”

Linear algebra already does that.

The potential unique value is:

$$
\boxed{
\text{canonical local-to-global structural reasoning}
}
$$

particularly where:

* local structures have compatible restrictions;
* higher-order compatibility matters;
* obstruction classes can be reused;
* structural equivalence matters;
* different local descriptions need to be identified;
* compositional reasoning matters.

That is the capability we should test.

---

# 29. Therefore the next benchmark changes

Instead of immediately implementing Phase 1–10 from the proposal, I recommend:

# LG-07 — Information-Matched Local–Global Capability Benchmark

Compare three systems.

### B2

Exact global solver.

### B2″

Explainable exact global solver:

$$
SolverResult=
(
SAT/UNSAT,
ConflictCore,
Witness,
Explanation
).
$$

### B3

Cellular/sheaf/cohomological solver:

$$
Complex
\rightarrow
C^0,C^1,C^2
\rightarrow
H^0,H^1,H^2.
$$

All three receive the **same information**.

This is crucial.

---

# 30. Capability matrix

We test:

| Capability                           |      B2 |      B2″ |        B3 |
| ------------------------------------ | ------: | -------: | --------: |
| SAT/UNSAT                            |       ✓ |        ✓ |         ✓ |
| Witness                              |       ✓ |        ✓ |         ✓ |
| Conflict core                        |       ? |        ✓ |         ? |
| Local incompatibility                |       ? |        ✓ |         ✓ |
| Global obstruction                   |       ? |        ✓ |         ✓ |
| Obstruction class                    |       — | possible |         ✓ |
| Structural equivalence               |       ? | possible |         ✓ |
| Canonical obstruction signature      |       ? | possible |         ✓ |
| Higher-order structure               | limited | possible |         ✓ |
| Compositional local-global reasoning |       ? |        ? | candidate |
| Independent verification             |       ✓ |        ✓ |         ✓ |

The important word is **candidate**.

We do not award B3 a capability merely because the mathematics gives it a name.

We test it.

---

# 31. Proposed decision rule

Define:

$$
CG_{B3}
=
Performance(B3)-Performance(B2'').
$$

But performance should be multidimensional.

Use:

$$
CG=
(
Accuracy,
DiagnosticResolution,
CertificateCompression,
Generalization,
Compositionality,
VerificationCost,
Runtime
).
$$

Then sheaf theory earns implementation priority only if it produces a material gain.

This is much stronger than:

> “sheaf theory is mathematically elegant.”

---

# 32. Architecture after this audit

I recommend the following final direction:

```text
                    KNOWLEDGEOS
                         │
              ┌──────────┴──────────┐
              │                     │
       SEMANTIC KERNEL       KNOWLEDGE STATE
              │                     │
              └──────────┬──────────┘
                         │
                 TRANSFORMATION
                         │
        ┌────────────────┼────────────────┐
        │                │                │
   DEPENDENCY       CONSTRAINT       EVIDENCE
        │                │                │
        └────────────────┼────────────────┘
                         │
             LOCAL–GLOBAL REASONING
                         │
       ┌─────────────────┼──────────────────┐
       │                 │                  │
     GRAPH         LINEAR ALGEBRA       SHEAF
     REGIME            REGIME           REGIME
       │                 │                  │
       └─────────────────┼──────────────────┘
                         │
                STRUCTURAL RESULTS
                         │
       ┌─────────────────┼─────────────────┐
       │                 │                 │
  Equivalence       Obstruction       Materiality
       │                 │                 │
       └─────────────────┼─────────────────┘
                         │
                     ASSURANCE
                         │
              Independent Verification
                         │
                     ASSESSMENT
                         │
                   DETERMINATION
```

The key architectural principle is:

$$
\boxed{
\text{Mathematics is a regime, not the Kernel.}
}
$$

---

# 33. Final verdict on the attached document

### Implementable now

**Yes**, but only after reducing it to:

$$
\boxed{
LocalGlobalStructuralReasoning
}
$$

with:

* explicit topology;
* explicit compatibility model;
* explicit coefficient system;
* exact cochain operators;
* exact obstruction certificates;
* regime-relative equivalence;
* independent verification.

### Not yet implementable as stated

The following should remain experimental:

$$
SS(\mathcal E)
$$

$$
T^*X_{KOS}
$$

$$
\text{involutivity}
$$

$$
R\text{-constructibility}
$$

$$
Rf_*,Rf_!,f^{-1},f^!,\otimes^L,R\mathcal Hom
$$

$$
D_X
$$

$$
\text{Verdier duality}
$$

$$
\text{perverse sheaves}.
$$

The proposal itself presents all of these as part of the intended roadmap, but the current KnowledgeOS evidence does not yet establish the required mathematical hypotheses. 

---

# 34. Do we need another book now?

**No, not for the immediate next experiment.**

We already have the uploaded *Categories and Sheaves* source available, and the attached proposal explicitly identifies Kashiwara–Schapira as its mathematical basis. 

I would **not** introduce another book yet.

First we should finish:

$$
\boxed{LG\text{-}07}
$$

and determine whether the sheaf/cohomological machinery produces a measurable capability gain.

If that experiment shows that we need deeper derived-category, constructibility, or microlocal mathematics, **then you can choose the next book**, rather than us expanding the theory prematurely.

---

# How far are we?

* ✅ KnowledgeOS Kernel boundary is now much clearer.
* ✅ Dependency ≠ compatibility is formally established.
* ✅ Cycle ≠ circular reasoning.
* ✅ UNSAT ≠ semantic diagnosis.
* ✅ Cohomological obstruction has been computationally demonstrated.
* ✅ Structural equivalence and obstruction signatures are strong architectural candidates.
* ✅ ML is correctly positioned as **candidate discovery**, not truth/determination.
* ✅ The attached sheaf proposal is **partially implementable**, but needs mathematical correction before coding.
* ⚠️ Genuine KS microsupport has **not** yet been established for KnowledgeOS.
* ⚠️ Verdier duality, six operations and perverse sheaves remain research hypotheses.
* ⚠️ Sheaf theory has **not yet demonstrated unique capability** over an equally informed exact solver.

### Remaining TODOs

* **LG-07:** information-matched B2″ vs B3 benchmark.
* **LG-07A:** canonical obstruction-signature comparison.
* **LG-07B:** structural-equivalence generalization.
* **LG-07C:** certificate size + independent verification.
* **LG-07D:** compositional local-global reasoning.
* **LG-07E:** adversarial cases where graph/SAT and sheaf representations receive identical information.
* **LG-08:** only if LG-07 shows genuine capability gain, formally investigate true sheaf/microlocal machinery.
* Keep **KS microsupport, Verdier duality and perverse sheaves outside the production architecture** until their mathematical hypotheses are explicitly satisfied.
