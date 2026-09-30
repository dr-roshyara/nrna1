I read the attached proposal **“Implementing Sheaves on Manifolds in the KnowledgeOS Algebra”** in full. The proposal contains several genuinely useful ideas, but it also makes a number of mathematical claims that are too strong for KnowledgeOS at its current stage.

The right next step is **not Phase 1 of that proposal as written**. We should first perform a **mathematical correction and minimal executable formulation**, then run S1.2.

# 1. Main conclusion

The proposal contains an important idea:

$$
\boxed{\text{KnowledgeOS can use sheaf theory as a local-to-global reasoning regime.}}
$$

But we should **not** currently claim:

$$
\text{Dependency Graph}
\Rightarrow
\text{Sheaf}
\Rightarrow
H^1=\text{circular reasoning}
\Rightarrow
SS=\text{fragility}
$$

That chain is not mathematically justified yet.

The corrected architecture is:

$$
\boxed{
\text{Semantic Knowledge}
\rightarrow
\text{Context/Locality}
\rightarrow
\text{Compatibility Constraints}
\rightarrow
\text{Cellular Sheaf}
\rightarrow
\text{Cochains}
\rightarrow
\text{Cohomology}
\rightarrow
\text{Obstruction Assessment}
}
$$

and only **after this has empirical value**:

$$
\rightarrow
\text{Constructibility}
\rightarrow
\text{Microsupport}
\rightarrow
\text{Derived/Six Operations}.
$$

This is a much safer and mathematically stronger KnowledgeOS architecture.

---

# 2. First important correction: the proposed “epistemic sheaf” is too trivial

The proposal defines

$$
\mathcal E(U)=\prod_{v\in U} EpistemicState(v).
$$

with restriction simply being projection.

That construction is certainly useful as a **product sheaf of states**, but it does **not** automatically encode epistemic compatibility.

### Why?

Suppose we have:

```text
A ──→ B
```

and A says:

> transformation T has value 0

while B says:

> transformation T has value 1.

The product

$$
\mathcal E(U)=\mathcal E(A)\times\mathcal E(B)
$$

can happily contain both states.

It does not know that they are incompatible.

Therefore:

$$
\boxed{
\text{State Sheaf} \neq \text{Constraint/Compatibility Sheaf}
}
$$

This is a major architectural distinction.

### KnowledgeOS needs both

We should introduce:

$$
\mathcal S
=
\text{State Sheaf}
$$

and

$$
\mathcal C
=
\text{Compatibility/Constraint Sheaf}.
$$

The first describes **what is locally known**.

The second describes **which local combinations are admissible**.

That is much closer to the actual KnowledgeOS problem.

---

# 3. Second correction: “sheaf of abelian groups” is not automatic

The proposal states:

> \(\mathcal E\) is a sheaf of abelian groups.

That does not follow merely because we have epistemic states.

An epistemic state such as

```text
Assessment = supported
Confidence = 0.83
Source = Document123
Context = Production
```

does not naturally form an abelian group.

Therefore we should distinguish:

### Sheaf of sets

$$
\mathcal F:\mathrm{Open}(X)^{op}\rightarrow \mathbf{Set}
$$

for arbitrary structured knowledge states.

### Sheaf of abelian groups

$$
\mathcal F:\mathrm{Open}(X)^{op}\rightarrow \mathbf{Ab}
$$

only when the local objects genuinely possess an abelian-group structure.

### Cellular cochain model

For our first computational benchmark we can deliberately choose

$$
\boxed{\mathbb F_2}
$$

because XOR-based compatibility constraints are simple, exact and computationally tractable.

This does **not** mean all KnowledgeOS knowledge is binary.

It means:

> We use \(\mathbb F_2\) as the first mathematical laboratory.

---

# 4. Third correction: the proposed stalk interpretation is not correct

The proposal says approximately:

$$
\mathcal E_v
\cong
State(v)\times
\prod_{w\in N(v)\setminus\{v\}}State(w).
$$

This is not generally the right interpretation of a stalk.

A **stalk** is the colimit

$$
\mathcal F_v
=
\varinjlim_{v\in U}\mathcal F(U).
$$

It represents the information available arbitrarily locally around \(v\).

If the topology has a minimal neighborhood \(N(v)\), as happens in an appropriate Alexandrov setting, then the stalk can often be represented by the section over that minimal neighborhood:

$$
\mathcal F_v\simeq\mathcal F(N(v)).
$$

But we should not equate “stalk” with “node plus all reachable dependencies.”

Therefore KnowledgeOS terminology should be:

> **Local context** = dependency neighborhood.

and separately:

> **Stalk** = germ-level local information in the chosen sheaf model.

This avoids mixing graph reachability with sheaf theory.

---

# 5. Fourth correction: \(H^1\neq\) circular reasoning

This is probably the most important correction to the proposal.

The document states:

$$
H^1=\text{circular reasoning}.
$$

That is too strong.

Mathematically,

$$
H^1
$$

measures a certain class of **global obstructions to locally compatible structures being globally realizable**, depending on the particular complex, sheaf and coefficient system.

It does not intrinsically mean:

> circular reasoning.

For example, an \(H^1\) obstruction might represent:

* incompatible transformations,
* inconsistent version constraints,
* incompatible temporal states,
* coordinate mismatch,
* semantic alignment failure,
* contradictory local assumptions,
* missing transition information,
* or, in a particular model, circular reasoning.

Therefore:

$$
\boxed{
H^1\text{ obstruction}
\neq
\text{semantic diagnosis}
}
$$

Instead:

$$
H^1
\rightarrow
Obstruction
\rightarrow
SemanticDiagnosis.
$$

Possible diagnosis:

```text
Obstruction
    │
    ├── incompatible assumptions
    ├── transformation conflict
    ├── temporal mismatch
    ├── circular dependency
    ├── missing bridge
    ├── model inadequacy
    └── unresolved semantic alignment
```

That is much more powerful.

---

# 6. Our S1.2 formulation is mathematically cleaner

We should therefore use a finite cellular complex:

$$
C^0
\xrightarrow{d^0}
C^1
\xrightarrow{d^1}
C^2
$$

with the fundamental condition

$$
\boxed{d^1d^0=0}.
$$

This is the first thing we can actually test.

## Definitions

### Cell

A structural unit of the model.

Examples:

* 0-cell = knowledge node
* 1-cell = relation/compatibility constraint
* 2-cell = higher-order compatibility among relations

### Cochain

An assignment of values to cells.

For example:

$$
x\in C^0
$$

assigns values to knowledge nodes.

### Coboundary

Applying \(d\) to a cochain:

$$
b=d^0x.
$$

Interpretation:

> What relation values are induced by a global node assignment?

### Cocycle

A cochain \(b\) satisfying

$$
d^1b=0.
$$

Interpretation:

> The local constraints satisfy the higher-order compatibility condition.

### Coboundary

$$
b=d^0x.
$$

Interpretation:

> The local constraints actually arise from some global state \(x\).

### Cohomology

$$
H^1=
\frac{\ker d^1}{\operatorname{im}d^0}.
$$

It distinguishes:

> locally compatible constraints that cannot be generated by the available global representation.

That is exactly the phenomenon KnowledgeOS needs to investigate.

---

# 7. Computational proof with the triangle

Consider:

```text
       A
      / \
     /   \
    B-----C
```

with

$$
C^0=\mathbb F_2^3
$$

and

$$
C^1=\mathbb F_2^3.
$$

Let

$$
x=(x_A,x_B,x_C).
$$

Define

$$
d^0x=
\begin{pmatrix}
x_A+x_B\\
x_B+x_C\\
x_C+x_A
\end{pmatrix}.
$$

For the filled triangle:

$$
d^1(b)=b_{AB}+b_{BC}+b_{CA}.
$$

We computationally verified:

$$
\boxed{d^1d^0=0}.
$$

That is the essential chain-complex condition.

### Example 1 — globally realizable

Take

$$
b=(0,1,1).
$$

Then

$$
d^1b=0+1+1=0\pmod2.
$$

So \(b\) is a cocycle.

And there exists a global assignment:

$$
x=(0,0,1)
$$

because

$$
d^0x=(0,1,1).
$$

Therefore:

$$
b\in\operatorname{im}d^0.
$$

So:

$$
[b]=0.
$$

---

### Example 2 — locally incompatible

Take

$$
b=(0,1,0).
$$

Then

$$
d^1b=1.
$$

Therefore:

$$
b\notin Z^1.
$$

There is no global realization.

This is **not yet an \(H^1\) obstruction**.

It is an earlier failure:

$$
\boxed{\text{local compatibility failure}}
$$

because \(b\) is not even a cocycle.

This gives KnowledgeOS a very useful three-level diagnosis:

| Mathematical condition  | KnowledgeOS interpretation  |
| ----------------------- | --------------------------- |
| \(b\notin Z^1\)         | Local compatibility failure |
| \(b\in Z^1,\ [b]\neq0\) | Genuine global obstruction  |
| \(b\in B^1\)            | Globally realizable         |

This is considerably more precise than the original proposal.

---

# 8. Genuine \(H^1\) obstruction

Now remove the filled face:

```text
A
|\ 
| \
|  \
B---C
```

We have a cycle but no 2-cell.

Then

$$
C^2=0
$$

so

$$
d^1=0.
$$

Therefore every 1-cochain is a cocycle:

$$
Z^1=C^1.
$$

But:

$$
\dim C^1=3
$$

and

$$
\operatorname{rank}(d^0)=2.
$$

Therefore:

$$
\dim H^1
=
3-2
=
1.
$$

Our computation confirmed this.

Thus:

$$
\boxed{\dim H^1=1}
$$

for the triangle cycle without a filled face.

Now we can select a non-exact \(b\).

That is the proper mathematical example of a genuine global obstruction.

---

# 9. Very important result

This produces a distinction we should make fundamental in KnowledgeOS:

$$
\boxed{
\text{Graph Cycle}
\neq
\text{Logical Contradiction}
}
$$

and also:

$$
\boxed{
H^1\neq0
\neq
\text{Circular Reasoning}.
}
$$

Instead:

$$
\boxed{
H^1\neq0
\Rightarrow
\text{there exists a class of unresolved global compatibility}.
}
$$

Then KnowledgeOS investigates *why*.

This is a much stronger scientific formulation.

---

# 10. The Nexus example should therefore change

The proposal currently has:

```text
Source A ─→ Evidence 1 ─→ Model ─→ Determination
Source B ─→ Evidence 2 ────────────────┘
```

This is a dependency graph.

It is **not yet a sheaf**.

We should transform it into:

### Layer 1 — semantic objects

```text
Source
Evidence
Model
Determination
Compliance
```

### Layer 2 — dependency relations

```text
derivedFrom
supports
dependsOn
transforms
```

### Layer 3 — local compatibility constraints

For example:

$$
C_{E1,M}
$$

means:

> Evidence 1 and Model M must satisfy transformation rule T.

and

$$
C_{E2,M}
$$

means:

> Evidence 2 and Model M must satisfy transformation rule T.

### Layer 4 — higher compatibility

A 2-cell may encode:

$$
E1\rightarrow M
\rightarrow D
$$

being compatible with another path.

Now the sheaf structure has something mathematically meaningful to operate on.

---

# 11. Dependency edges and compatibility edges must be separated

This is another important architectural improvement.

We currently have:

$$
G_D=(V,E_D)
$$

for dependency.

We now introduce:

$$
G_C=(V,E_C)
$$

for compatibility constraints.

They are not the same thing.

### Dependency

> B depends on A.

### Compatibility

> A and B can consistently participate in the same model.

For example:

```text
Source A ──dependsOn──→ Source B
```

does not mean:

```text
Source A ──compatibleWith──→ Source B
```

Therefore:

$$
\boxed{
E_D\neq E_C
}
$$

This is an important DDD boundary.

---

# 12. Updated KnowledgeOS architecture

I recommend replacing the proposal's architecture with:

```text
L0  SEMANTIC KERNEL
    │
    ├── Identity
    ├── Typed Relations
    └── Semantic Contract
    │
    ▼
L1  KNOWLEDGE STATE
    │
    ├── Assertion
    ├── Evidence
    ├── Context
    ├── Provenance
    ├── Validity
    └── History
    │
    ▼
L2  TRANSFORMATION ALGEBRA
    │
    ├── Assert
    ├── Relate
    └── Derived transformations
    │
    ▼
L2C CONTEXT / LOCALITY
    │
    ├── Dependency
    ├── Preorder
    ├── Cover
    └── Overlap
    │
    ▼
L2S LOCAL–GLOBAL REGIME
    │
    ├── Local Section
    ├── Restriction
    ├── Compatibility
    └── Gluing
    │
    ▼
L2A COCHAIN ALGEBRA
    │
    ├── C⁰
    ├── C¹
    ├── C²
    ├── d⁰
    ├── d¹
    └── d¹d⁰ = 0
    │
    ▼
L3 EPISTEMIC ASSESSMENT
    │
    ├── Obstruction
    ├── Dependency
    ├── Conflict
    ├── Fragility
    └── Robustness
    │
    ▼
L4 ASSURANCE
    │
    ├── Exact Solver
    ├── Counterexample
    ├── Invariant Checking
    ├── Calibration
    └── Metamorphic Testing
    │
    ▼
L5 INTELLIGENCE
    │
    ├── ML Candidate Discovery
    ├── Conflict Discovery
    ├── Perturbation Discovery
    └── Frontier Discovery
    │
    ▼
L6 GOVERNANCE
```

This is better than putting micro-support directly after the sheaf.

---

# 13. Micro-support must remain deferred

The proposal defines:

$$
SS(\mathcal E)
=
\overline{\{(v,\phi):
\phi\text{ changes }\mathcal E_v\}}.
$$

This is an interesting **KnowledgeOS fragility candidate**, but it is not yet Kashiwara–Schapira microsupport.

Why?

True KS microsupport requires substantially more structure:

* a suitable topological space,
* sheaves/derived sheaves,
* cotangent geometry,
* local cohomological definition,
* conic structure,
* and, for the classical involutivity theorem, the relevant smooth-manifold framework.

Our discrete graph does not automatically have a cotangent bundle.

Therefore:

$$
\boxed{
\text{Perturbation Sensitivity}
\neq
\text{KS Microsupport}
}
$$

We should first create:

$$
PS(v,\phi)
$$

= **Perturbation Sensitivity**

and only later investigate whether it admits a genuine microlocal interpretation.

This is an excellent place for ML.

---

# 14. ML should not calculate truth

The proposal's ML direction is useful, but we should enforce our existing KnowledgeOS separation:

$$
\boxed{
ML\rightarrow Candidate
\rightarrow ExactValidation
\rightarrow Assessment
}
$$

For example, ML may predict:

$$
P(\text{compatibility conflict}\mid X)=0.94.
$$

That means:

> the model predicts a candidate conflict.

It does **not** mean:

$$
Conflict=true.
$$

The exact solver can then test:

$$
d^1b\neq0
$$

or:

$$
[b]\neq0.
$$

This gives us an excellent hybrid architecture:

```text
              ML
               │
               ▼
      Candidate Conflict
               │
               ▼
       Exact Validator
          /         \
       valid       invalid
        │             │
        ▼             ▼
   Assessment      Reject
```

---

# 15. ML features for KnowledgeOS

For candidate conflict discovery we can use:

$$
X=
(
SourceOverlap,
SemanticSimilarity,
TemporalOverlap,
ContextOverlap,
ModelLineage,
TransformationLineage,
DependencyDistance,
CitationOverlap,
VersionDifference
).
$$

The target can be:

$$
Y=
\begin{cases}
0 & \text{compatible}\\
1 & \text{incompatible}
\end{cases}
$$

but the ground truth must come from the exact synthetic generator.

Suitable initial models:

* Gradient Boosting
* XGBoost/LightGBM
* Random Forest as interpretable baseline
* small neural network later

The important benchmark is not merely accuracy.

We need:

$$
Precision,\ Recall,\ FPR,\ FNR
$$

and especially:

$$
\boxed{FalseDependencyRate}
$$

and

$$
\boxed{FalseConflictRate}.
$$

Because a KnowledgeOS system that invents conflicts can be more dangerous than one that misses some.

---

# 16. A better S1.2 benchmark

I recommend replacing the proposal's Phase 1–3 roadmap with this much tighter experiment.

## S1.2 — Cellular KnowledgeOS Sheaf Benchmark

### Dataset

Generate:

$$
N=10^4\text{–}10^6
$$

small synthetic knowledge complexes, depending on computational capacity.

Each instance contains:

```text
Knowledge Nodes
Dependency Relations
Compatibility Relations
Context
Transformations
Local constraints
Ground-truth global state
```

### Mathematical regime

Initially:

$$
\mathbb F_2.
$$

Then later:

$$
\mathbb Z,
\quad
\mathbb R,
\quad
\text{finite fields},
\quad
\text{probabilistic coefficients}.
$$

Do not introduce all of them simultaneously.

---

# 17. Ground-truth classes

Every instance should receive one of:

### Class A

$$
b\notin Z^1
$$

Local incompatibility.

### Class B

$$
b\in Z^1,\quad[b]\neq0
$$

Global obstruction.

### Class C

$$
b\in B^1
$$

Global realization exists.

### Class D

Semantic ambiguity.

### Class E

Insufficient information.

This gives us a richer benchmark than simply:

```text
true / false
```

---

# 18. The most important benchmark comparison

We should compare:

### S0

Evidence counting.

### S1

Source/dependency deduplication.

### S2

Dependency graph.

### S3

Dependency + perturbation.

### S4

Cellular compatibility model.

### S5

Cellular model + exact cohomology.

### S6

ML candidate discovery + exact S5 validation.

Then measure:

$$
\Delta_{sheaf}
=
Performance(S5)-Performance(S3).
$$

and:

$$
\Delta_{ML}
=
Performance(S6)-Performance(S5).
$$

This gives us an objective answer to:

> Does sheaf theory actually add something?

---

# 19. Gate for admitting sheaf theory

We should not admit the sheaf layer merely because the mathematics is elegant.

Define:

$$
Gate_{Sheaf}
=
CapabilityGain
\land
CorrectnessGain
\land
AcceptableCost
\land
Interpretability
\land
Reproducibility.
$$

A practical version:

$$
G_S=
\begin{cases}
ADMIT & \Delta_{sheaf}>\tau
\land Error_{sheaf}<Error_{baseline}\\
DEFER & \text{otherwise}
\end{cases}
$$

where \(\tau\) is fixed **before** seeing the final benchmark.

That prevents confirmation bias.

---

# 20. What we should NOT implement yet

The proposal moves too quickly into:

* microsupport,
* involutivity,
* Lagrangian geometry,
* six operations,
* Verdier duality,
* derived categories,
* perverse sheaves.

These are mathematically real and important, but KnowledgeOS has not yet demonstrated that they solve a problem that simpler structures cannot solve.

Therefore:

$$
\boxed{
\text{Do not implement KS Level 2 before S1.2 earns Level 1.}
}
$$

Our research hierarchy becomes:

```text
Level 0
Graph / Dependency

       ↓

Level 1
Locality + Compatibility + Gluing

       ↓

Level 2
Cochains + H⁰/H¹/H²

       ↓

Level 3
Constructibility

       ↓

Level 4
Perturbation Sensitivity

       ↓

Level 5
Microsupport

       ↓

Level 6
Derived / Six Operations

       ↓

Level 7
Perverse / Advanced KS
```

Each level must earn the next.

---

# 21. Important DDD interpretation

From the DDD perspective, I would **not** make `Sheaf` an Entity or Aggregate in the domain model.

It belongs to a **Mathematical Reasoning Context**.

For example:

```text
Knowledge Context
    │
    ├── Assertion
    ├── Evidence
    ├── Context
    └── Determination

Dependency Context
    │
    ├── Dependency
    └── DependencyGraph

Locality Context
    │
    ├── Cover
    ├── Section
    ├── Compatibility
    └── Gluing

Cohomology Context
    │
    ├── Cochain
    ├── Cocycle
    ├── Coboundary
    └── Obstruction

Intelligence Context
    │
    └── ML Candidate

Assurance Context
    │
    └── Exact Validation
```

This is much cleaner than making “epistemic sheaf” part of the Kernel.

---

# 22. Terms we have now formally separated

| KnowledgeOS term             | Definition                                                                                        |
| ---------------------------- | ------------------------------------------------------------------------------------------------- |
| **Context**                  | A bounded semantic/operational frame in which statements are interpreted                          |
| **Locality**                 | The property that reasoning can be performed on bounded contexts                                  |
| **Cover**                    | A collection of local contexts whose union represents a larger context                            |
| **Overlap**                  | The shared part between local contexts                                                            |
| **Section**                  | A locally valid assignment over a context                                                         |
| **Restriction**              | Mapping a section to a smaller context                                                            |
| **Compatibility**            | Agreement of local sections on overlaps according to a specified rule                             |
| **Gluing**                   | Constructing a global section from compatible local sections                                      |
| **Cochain**                  | An algebraic assignment to cells of a given dimension                                             |
| **Coboundary**               | The result of applying a differential to a lower-dimensional cochain                              |
| **Cocycle**                  | A cochain whose differential is zero                                                              |
| **Cohomology**               | Quotient distinguishing compatible structures that are globally generated from those that are not |
| **Obstruction**              | A mathematically detected failure of a specified local-to-global realization                      |
| **Dependency**               | A relation indicating that one knowledge object relies on another                                 |
| **Compatibility Constraint** | A rule determining whether local objects can coexist consistently                                 |
| **Perturbation**             | A controlled change to knowledge, assumptions, evidence or context                                |
| **Perturbation Sensitivity** | Measured change in a target state caused by a perturbation                                        |
| **Microsupport**             | A specific KS microlocal invariant; not yet equivalent to our perturbation sensitivity            |
| **Constructibility**         | Local regularity of a sheaf relative to a specified stratification                                |
| **Stratum**                  | A region/group on which the relevant mathematical structure behaves uniformly                     |
| **Determination**            | A KnowledgeOS conclusion produced under a specified regime and evidence state                     |
| **Candidate**                | A proposed object/relation generated but not yet validated                                        |
| **Validation**               | An explicit test against a defined criterion                                                      |
| **Assessment**               | A qualified epistemic evaluation                                                                  |
| **Ground Truth**             | The known target state generated or independently established for a benchmark                     |
| **Regime**                   | The mathematical/logical rules under which an operation is interpreted                            |
| **Obstruction Diagnosis**    | Semantic interpretation of a mathematically detected obstruction                                  |

The crucial principle is:

$$
\boxed{
\text{Mathematical obstruction}
\neq
\text{semantic interpretation}
}
$$

---

# 23. Final optimized architecture

I would now freeze the following as the **candidate KnowledgeOS research architecture**:

$$
\boxed{
L0\ Kernel
\rightarrow
L1\ KnowledgeState
\rightarrow
L2\ Transformation
\rightarrow
L2C\ Locality
\rightarrow
L2S\ Sheaf/Compatibility
\rightarrow
L2A\ Cohomology
\rightarrow
L3\ EpistemicAssessment
\rightarrow
L4\ Assurance
\rightarrow
L5\ Intelligence
\rightarrow
L6\ Governance
}
$$

with:

$$
\boxed{
\text{ML is outside the truth-producing path.}
}
$$

and:

$$
\boxed{
\text{Sheaf theory is outside the semantic Kernel.}
}
$$

and:

$$
\boxed{
\text{Microsupport is outside the current implementation until experimentally justified.}
}
$$

This is a significantly more defensible architecture than the attached proposal.

---

# 24. Do we need another book now?

**No.**

The attached proposal plus the already supplied Kashiwara–Schapira material are sufficient for the next mathematical experiment.

I would **not** start another book yet. We should first execute S1.2 and see what problem remains unresolved.

If S1.2 reveals a genuine need for deeper sheaf/cohomology machinery, then **you choose the next book** and I will work from that source.

---

## Progress toward the KnowledgeOS goal

* ✅ Semantic Kernel remains minimal.
* ✅ Dependency reasoning is established.
* ✅ State and compatibility have been separated.
* ✅ Locality/cover/section/gluing model identified.
* ✅ Cellular formulation \(C^0\to C^1\to C^2\) established.
* ✅ \(d^1d^0=0\) computationally verified.
* ✅ Local incompatibility distinguished from genuine \(H^1\) obstruction.
* ✅ \(H^1\neq\) “circular reasoning” corrected.
* ✅ Dependency edge ≠ compatibility edge established.
* ✅ ML → candidate → exact validation architecture established.
* ⚠️ Sheaf theory is **still a candidate mathematical regime**, not yet a proven essential part of KnowledgeOS.
* ❌ Microsupport, involutivity, six operations and perverse machinery are **not yet justified**.

## Remaining TODOs

* ⏳ Build the full **S1.2 Cellular KnowledgeOS Benchmark**.
* ⏳ Exhaustively test bounded complexes.
* ⏳ Compute \(H^0,H^1,H^2\) exactly.
* ⏳ Add W1–W7 dependency worlds.
* ⏳ Add temporal, semantic and transformation-conflict worlds.
* ⏳ Compare graph vs dependency vs cellular/cohomological approaches.
* ⏳ Establish a quantitative **Sheaf Admission Gate**.
* ⏳ Only then investigate constructibility.
* ⏳ Only if justified, investigate perturbation sensitivity → genuine microsupport.
* ⏳ ML conflict/obstruction discovery comes **after exact ground truth exists**.
