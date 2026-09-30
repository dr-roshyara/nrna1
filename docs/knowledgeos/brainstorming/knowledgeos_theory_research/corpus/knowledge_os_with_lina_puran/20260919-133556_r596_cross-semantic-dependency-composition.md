# R596 — Cross-Semantic Dependency Composition

I continued directly from R595 and **read the attached R595 benchmark first**. It correctly establishes four dependency semantics and explicitly says that finite executable evidence is being used, not a universal theorem. 

R596 now tests the next question:

$$
\boxed{
Valid(D_1)\land Valid(D_2)
\stackrel{?}{\Longrightarrow}
Valid(D_2\circ D_1)
}
$$

The answer is:

$$
\boxed{\textbf{NO}}
$$

This is an important result because it prevents us from turning the dependency graph into an ordinary transitive-closure mechanism.

---

## 1. The central result

Two individually valid dependencies do **not** automatically form a valid composed dependency.

We need:

$$
\boxed{
Composition =
Typing+
Compatibility+
Preservation+
RegimeConditions
}
$$

and composition remains partial:

$$
\boxed{
D_2\circ D_1:
\mathcal D_1\times\mathcal D_2
\rightharpoonup
\mathcal D_3
}
$$

The \(\rightharpoonup\) is important.

It means:

> composition is permitted only for some pairs.

---

# 2. Logical → logical

Consider:

$$
A\rightarrow B
$$

and

$$
B\rightarrow C.
$$

Under a deterministic logical contract where:

$$
B=f(A)
$$

and

$$
C=g(B),
$$

we obtain:

$$
C=g(f(A)).
$$

The benchmark confirms:

$$
\boxed{
Logical\circ Logical = Logical
}
$$

**when the sufficiency and preservation contracts hold.**

So ordinary deterministic composition works here.

---

# 3. Probabilistic → probabilistic: the important counterexample

Suppose:

$$
P(B|A)=0.9
$$

and:

$$
P(C|B)=0.9.
$$

A naive system might calculate:

$$
P(C|A)=0.9\times0.9=0.81.
$$

That is **not generally valid**.

Suppose additionally:

$$
P(C|B=0,A=1)=0.8.
$$

Then:

$$
P(C|A=1)
=
P(C|B=1,A=1)P(B=1|A=1)
+
P(C|B=0,A=1)P(B=0|A=1)
$$

giving:

$$
=0.9(0.9)+0.8(0.1)
$$

$$
=0.81+0.08
$$

$$
=\boxed{0.89}.
$$

Therefore:

$$
\boxed{0.81\neq0.89}
$$

The missing conditional dependency matters.

### KnowledgeOS consequence

We cannot implement:

```text
A --probabilistic--> B
B --probabilistic--> C
```

as automatic probabilistic transitive closure.

We need the **joint/conditional model** or an explicit factorization assumption.

Thus:

$$
\boxed{
Probabilistic\ Composition
\neq
ordinary\ multiplication
}
$$

---

# 4. Causal → causal

Now consider:

$$
A\rightarrow B
$$

and

$$
B\rightarrow C.
$$

If the declared structural causal model says that **B completely mediates the causal path**, composition can be admitted.

But suppose:

$$
C=g(A,B,U_C).
$$

Now there is a direct path:

$$
A\rightarrow C
$$

in addition to:

$$
A\rightarrow B\rightarrow C.
$$

Then the second dependency:

$$
B\rightarrow C
$$

does not contain the complete causal mechanism from \(A\) to \(C\).

Therefore:

$$
\boxed{
Causal(D_1)\land Causal(D_2)
\not\Rightarrow
Causal(D_2\circ D_1)
}
$$

unless the relevant mediation/model conditions are established.

R596 therefore classifies the non-mediated case as **CONDITIONAL**, not automatically valid.

---

# 5. Epistemic → epistemic

This one is particularly important for KnowledgeOS.

Suppose:

$$
E_1\rightarrow Assessment(B)
$$

and:

$$
B\rightarrow Assessment(C).
$$

Sequential epistemic updating can be legitimate.

But consider:

$$
E_2=f(E_1).
$$

Then treating \(E_1\) and \(E_2\) as independent evidence can produce **double counting**.

This connects directly with the earlier evidence-dependency work:

$$
\boxed{
Repeated\ representation\neq independent\ evidence
}
$$

Therefore epistemic composition requires dependency accounting.

R596 found:

* independent/sequential assessment update → **ADMITTED**
* possible evidence duplication → **CONDITIONAL**

---

# 6. Cross-semantic composition

Now the most interesting case:

$$
A
\xrightarrow{\text{probabilistic}}
B
\xrightarrow{\text{logical}}
C.
$$

Can we automatically conclude:

$$
A
\xrightarrow{\text{probabilistic}}
C?
$$

No.

The logical relation may tell us:

$$
C=f(B),
$$

but the probabilistic relation only gives something about:

$$
P(B|A).
$$

To derive:

$$
P(C|A)
$$

we need the appropriate mapping/model.

For example, if:

$$
C=B,
$$

then perhaps the relationship can be propagated.

But if:

$$
C=f(B)
$$

under a regime whose semantics are not aligned with the probabilistic model, additional information is required.

Therefore:

$$
\boxed{
CrossSemanticComposition
=
Conditional
}
$$

unless an explicit bridge establishes:

1. typing,
2. semantic compatibility,
3. target preservation,
4. regime compatibility,
5. required assumptions.

---

# 7. The deeper distinction: function composition vs KnowledgeOS composition

This is extremely important.

For ordinary functions:

$$
f:X\rightarrow Y
$$

$$
g:Y\rightarrow Z
$$

$$
h:Z\rightarrow W
$$

we have:

$$
h\circ(g\circ f)
=
(h\circ g)\circ f.
$$

Function composition is associative.

But KnowledgeOS does not compose only functions.

It composes:

```text
Transformation
+ meaning
+ regime
+ scope
+ contract
+ provenance
+ preservation
+ validity
```

Therefore:

$$
\boxed{
Function\ Associativity
\neq
Semantic\ Composition\ Associativity
}
$$

This is a major result.

---

# 8. A KnowledgeOS dependency edge is therefore not just an edge

The conceptual model should now be:

$$
e=
(Source,
Target,
Kind,
Regime,
Context,
Scope,
Contract,
Provenance,
Validity)
$$

rather than merely:

$$
e=(Source,Target).
$$

Thus:

```text
A ───────────────→ B
```

is insufficient.

We need something conceptually closer to:

```text
A
│
│ Dependency
│ kind = causal
│ regime = SCM
│ scope = production
│ time = T1
│ contract = C17
│ preservation = P4
│ provenance = ...
▼
B
```

This explains why ordinary graph transitive closure is unsafe.

---

# 9. New invariant

R596 gives us a strong candidate invariant:

$$
\boxed{
I_D:
Valid(D_1)\land Valid(D_2)
\not\Rightarrow
Valid(D_2\circ D_1)
}
$$

unless:

$$
\boxed{
Comp(D_1,D_2,\Gamma,C,S)
}
$$

is established.

Where:

* \(Comp\) = composition admissibility
* \(\Gamma\) = regime/context
* \(C\) = contract
* \(S\) = scope

This should become part of the **Dependency Composition Calculus**.

---

# 10. Definitions for the KnowledgeOS vocabulary

### Dependency Composition

The construction of a new dependency claim from two or more existing dependency claims.

### Composition Admissibility

The conditions under which dependency composition is allowed.

### Semantic Bridge

An explicitly validated relation connecting two different semantic regimes.

Example:

$$
Probabilistic(B|A)
\rightarrow
Logical(C=f(B))
$$

with a contract establishing how the probabilistic information transfers through \(f\).

### Target Preservation

The property that the composed transformation still preserves the target property being reasoned about.

### Mediation

A causal condition in which an intermediate variable carries the relevant causal pathway between source and target.

### Factorization

A declared mathematical decomposition of a joint distribution into conditional components.

### Evidence Duplication

A situation where apparently separate evidence contains substantially the same underlying information.

### Constrained Transitive Closure

Transitive reasoning over dependency edges subject to semantic admissibility conditions.

This is different from ordinary graph transitive closure.

---

# 11. The optimized KnowledgeOS composition model

I would now formalize the conceptual operation as:

$$
\boxed{
Compose(D_1,D_2,\Gamma,C)
\rightarrow
\{
Admitted,
Conditional,
Rejected,
Undefined
\}
}
$$

### ADMITTED

All required conditions are established.

### CONDITIONAL

Composition may be valid, but additional assumptions/evidence/contracts are required.

### REJECTED

A known incompatibility or preservation failure exists.

### UNDEFINED

The system does not even have enough information to determine whether composition is meaningful.

This preserves our earlier critical distinction:

$$
\boxed{
Undefined\neq Rejected
}
$$

---

# 12. ML implications

This also improves the ML architecture.

An ML system may propose:

```text
A → B
B → C
```

and even assign:

$$
P(\text{valid composition})=0.97.
$$

But:

$$
\boxed{
ML\ confidence\neq compositional\ validity
}
$$

The ML system can propose:

```text
CandidateComposition
```

but the assurance pipeline must still execute:

```text
Type
 ↓
Scope
 ↓
Regime
 ↓
Contract
 ↓
Preconditions
 ↓
Preservation
 ↓
Composition
 ↓
Assessment
```

This preserves the existing ML firewall rather than creating another architecture.

---

# 13. DDD architecture after R596

Still **no new bounded context**.

Still **no new layer**.

Still **no new Kernel primitive**.

The existing structure is sufficient:

```text
L0 Kernel
    │
L1 Contract / Semantic Fabric
    │
    ├── DependencyContract
    ├── Regime
    ├── Scope
    ├── Context
    └── Validity Contract
    │
L2 Formal Fabric
    │
    ├── Dependency
    ├── Transformation
    ├── Composition
    ├── Compatibility
    └── Preservation
    │
L3 Epistemic Assessment
    │
    ├── Dependency Assessment
    ├── Support
    ├── Evidence
    └── Determination
    │
L4 Assurance
    │
    ├── Composition Verification
    ├── Counterexample Search
    ├── Completeness
    └── Certificates
    │
L5 Intelligence
    │
    └── ML Candidate Composition
    │
L6 Governance
```

This is another **architecture compression success**.

---

# 14. Executable result

The R596 benchmark has been created and executed:

[Download the R596 executable benchmark](sandbox:/mnt/data/knowledgeos_r596_cross_semantic_dependency_composition.py)

The executed benchmark passed:

* Logical → logical composition
* Probabilistic composition counterexample
* Probabilistic composition under explicit factorization
* Causal composition under mediation
* Causal non-mediation boundary
* Epistemic sequential composition
* Epistemic double-counting boundary
* Cross-semantic bridge boundary
* Target-preservation failure
* Function-composition associativity
* Semantic compatibility failure

Again, this is **finite executable evidence**, not a universal mathematical theorem.

---

# 15. What R596 has actually established

### Strong finite evidence

$$
\boxed{
Valid(D_1)\land Valid(D_2)
\not\Rightarrow
Valid(D_2\circ D_1)
}
$$

and:

$$
\boxed{
KnowledgeOS\ composition\ is\ partial.
}
$$

Also:

$$
\boxed{
Graph\ connectivity\neq epistemic\ derivability.
}
$$

and:

$$
\boxed{
Function\ composition\ associativity
\neq
KnowledgeOS\ semantic\ composition\ associativity.
}
$$

---

# 16. The next step should now be R597

I would **not** add another dependency type.

The next question is more fundamental:

$$
\boxed{
\textbf{R597 — Is KnowledgeOS semantic composition associative under explicit composition contracts?}
}
$$

We should construct three-stage chains:

$$
D_1:A\rightarrow B
$$

$$
D_2:B\rightarrow C
$$

$$
D_3:C\rightarrow D
$$

and compare:

$$
(D_3\circ D_2)\circ D_1
$$

with:

$$
D_3\circ(D_2\circ D_1).
$$

But we must compare **semantic witnesses**, not merely final values.

We should test:

1. logical/logical/logical;
2. probabilistic/probabilistic/probabilistic;
3. causal/causal/causal;
4. epistemic/epistemic/epistemic;
5. mixed-regime chains;
6. scope changes;
7. temporal changes;
8. hidden dependencies;
9. evidence duplication;
10. partial-domain composition;
11. witness equivalence;
12. whether associativity holds **strictly**, **up to equivalence**, or **not at all**.

That is the point where we can determine whether the earlier **Category Theory** hypothesis has genuine mathematical evidence behind it—or whether ordinary typed partial composition is already sufficient.

---

## Current progress

* **Kernel:** stable.
* **Semantic fabric:** highly mature.
* **Dependency semantics:** four distinct regimes established.
* **Dependency composition:** now shown to be partial.
* **Graph closure:** proven unsafe without semantic contracts.
* **ML:** correctly remains candidate-only.
* **DDD:** architecture continues to shrink rather than grow.
* **Major open mathematical question:** associativity of **semantic** composition.

## Remaining TODOs

* **R597:** three-stage semantic associativity.
* Determine strict vs witness-equivalence associativity.
* Test category-theoretic structure only after R597 evidence.
* Integrate dependency + acquisition + regime + composition.
* Global invariant engine.
* Cross-module adversarial testing.
* Final terminology freeze.
* KnowledgeOS Theory Specification v1.0.
