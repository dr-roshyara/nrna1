## R593 — Minimal Support and Dependency Order

I first read the attached R592 implementation. Its architecture is usable for KnowledgeOS, and the main weakness is exactly the one identified at the end of R592: **`support` cannot automatically be treated as a minimal support**. 

So I continued with R593 rather than adding another architectural concept prematurely.

[Download the R593 executable benchmark](sandbox:/mnt/data/knowledgeos_r593_minimal_support_dependency_order.py)

---

# 1. The fundamental problem

R592 defined:

$$
ord(D)=|\operatorname{MinSupport}(D)|.
$$

But we must answer:

> How do we know that a proposed support is actually minimal?

This is crucial because an incorrect minimal-support calculation can produce an incorrect dependency order, which can then produce an incorrect completeness claim.

The dangerous chain is:

$$
FalseMinimalSupport
\rightarrow
FalseLowOrder
\rightarrow
FalseCompleteness
\rightarrow
FalseNegativeAssurance.
$$

Therefore R593 is directly relevant to the safety of:

$$
NOT\_AFFECTED.
$$

---

# 2. New term: Support

### Support

A **support** is a set of factors/entities whose jointly available state is sufficient, under a specified evaluator/contract, to determine the target property.

Formally:

$$
S\text{ supports }Z
\iff
Sufficient(S,Z\mid\Gamma,C,\Sigma).
$$

Real-world example:

```text
A = customer type
B = transaction pattern
Z = risk classification
```

If:

$$
A\land B\Rightarrow Z
$$

then:

$$
\{A,B\}
$$

is a support for Z.

---

# 3. New term: Sufficient Support

A support \(S\) is **sufficient** when adding the missing factors is unnecessary for determining the declared target under the specified contract.

In simplified form:

$$
Sufficient(S,Z)
$$

means:

$$
Z
$$

is determined by the information represented by \(S\).

This is target-relative.

The same support may be sufficient for one target and insufficient for another:

$$
Sufficient(S,Z_1)
$$

does not imply:

$$
Sufficient(S,Z_2).
$$

That is another reason KnowledgeOS cannot store dependency order without its target and contract context.

---

# 4. New term: Minimal Support

A sufficient support \(S\) is **minimal** when no proper subset is also sufficient:

$$
\boxed{
Minimal(S,Z)
\iff
Sufficient(S,Z)
\land
\forall S'\subsetneq S:
\neg Sufficient(S',Z)
}
$$

This is **inclusion-minimality**, not necessarily minimum cardinality.

That distinction matters.

---

# 5. Example: simple AND

Suppose:

$$
Z=A\land B.
$$

Then:

$$
\{A,B\}
$$

is sufficient.

But:

$$
\{A\}
$$

is not sufficient, and:

$$
\{B\}
$$

is not sufficient.

Therefore:

$$
MinSupport(Z)=\{\{A,B\}\}.
$$

and:

$$
ord(Z)=2.
$$

R593 verifies this computationally.

---

# 6. Example: redundant information

Suppose:

$$
Z=A\land B
$$

and we additionally have:

$$
C.
$$

Then:

$$
\{A,B,C\}
$$

is sufficient, but it is **not minimal**.

Because:

$$
\{A,B\}\subset\{A,B,C\}
$$

and:

$$
\{A,B\}
$$

is already sufficient.

Therefore:

$$
\boxed{
\{A,B,C\}\neq MinimalSupport
}
$$

This prevents us from incorrectly calculating:

$$
ord(Z)=3.
$$

The true order is:

$$
ord(Z)=2.
$$

---

# 7. Multiple minimal supports

This is more interesting.

Suppose:

$$
Z=(A\land B)\lor(C\land D).
$$

Then there are **two different minimal explanations**:

$$
S_1=\{A,B\}
$$

and:

$$
S_2=\{C,D\}.
$$

Neither contains the other.

Therefore:

$$
MinSupports(Z)=
\left\{
\{A,B\},
\{C,D\}
\right\}.
$$

This connects directly to the earlier R604.9 work on alternative minimal explanations.

The dependency is therefore not adequately represented by one ordinary edge.

---

# 8. Dependency order with multiple explanations

We need to be careful here.

If:

$$
MinSupports(Z)=
\{\{A,B\},\{C,D,E\}\}
$$

then the minimal supports have sizes:

$$
2,\quad3.
$$

What should:

$$
ord(Z)
$$

mean?

For the current completeness problem, the **safe choice** is:

$$
\boxed{
ord(Z)=\max_{S\in MinSupports(Z)}|S|
}
$$

giving:

$$
ord(Z)=3.
$$

Why?

Because claiming:

$$
ord(Z)\le2
$$

would ignore the valid three-factor explanation.

For negative assurance, the maximum relevant minimal-support order is the conservative quantity.

This is an important refinement of R592.

---

# 9. Three-way example

Suppose:

$$
Z=A\land B\land C.
$$

Then:

$$
MinSupports(Z)=
\{\{A,B,C\}\}.
$$

Therefore:

$$
ord(Z)=3.
$$

A pairwise detector might observe:

$$
A-B,\quad A-C,\quad B-C
$$

but that does not establish the true minimal support.

R593 explicitly tests this distinction.

---

# 10. XOR is especially important

Consider:

$$
Z=A\oplus B.
$$

Under the appropriate state semantics, either factor together with the known state of the other factor can determine the result.

The benchmark therefore produces two minimal supports:

$$
\{A\}
$$

and:

$$
\{B\}.
$$

Hence:

$$
ord(Z)=1.
$$

This demonstrates why we cannot infer dependency order merely from the visual complexity of a formula or the number of factors mentioned in it.

The **semantic sufficiency relation** determines the support.

---

# 11. The adversarial case

This is the most important R593 test.

Ground truth:

$$
Z=(A\land B)\lor(C\land D\land E).
$$

True minimal supports:

$$
\{A,B\}
$$

and:

$$
\{C,D,E\}.
$$

Therefore:

$$
ord(Z)=3.
$$

Now imagine an ML system incorrectly learns:

$$
Z=(A\land B)\lor(C\land D).
$$

It has effectively dropped \(E\).

It concludes:

$$
ord(Z)=2.
$$

The benchmark confirms:

$$
\boxed{
FalseMinimality
\Rightarrow
FalseLowOrder.
}
$$

This is exactly the failure mode we needed to expose.

---

# 12. ML's correct role

This gives us a very clean ML architecture.

ML may propose:

$$
\widehat{S}_{ML}
$$

as a candidate minimal support.

For example:

```text
ML candidate:
{customer_type, transaction_pattern}
```

But KnowledgeOS must not immediately accept it.

Instead:

$$
ML
\rightarrow CandidateSupport
\rightarrow SufficiencyValidation
\rightarrow MinimalityValidation
\rightarrow Assessment
\rightarrow Certificate
$$

Only after validation can it participate in a completeness claim.

The benchmark explicitly tests:

```text
ML candidate remains candidate-only: PASS
```

Therefore:

$$
\boxed{
MLMinimalSupport\neq VerifiedMinimalSupport
}
$$

---

# 13. A deeper logical distinction

R593 reveals that there are actually **three different questions**:

### Question 1 — Sufficiency

Does:

$$
S
$$

suffice for \(Z\)?

### Question 2 — Minimality

Is there a proper subset:

$$
S'\subsetneq S
$$

that also suffices?

### Question 3 — Completeness of minimal supports

Have **all** relevant minimal supports been discovered?

These are different properties.

Therefore:

$$
Sufficient
\neq
Minimal
\neq
CompleteMinimalSupportSet.
$$

This distinction is important enough that it should become part of the KnowledgeOS formal vocabulary.

---

# 14. New term: Minimal-Support Completeness

### Minimal-Support Completeness

A set \(M\) is minimally-support complete if:

$$
M=MinSupports(Z\mid\Gamma,C,\Sigma)
$$

for the declared scope.

It requires both:

1. **Soundness**

Every reported support really is minimal and sufficient.

2. **Completeness**

No relevant minimal support has been omitted.

Therefore:

$$
\boxed{
MSC=
MinimalSupportSoundness
\land
MinimalSupportCoverage
}
$$

This is another place where ML alone cannot establish the required negative statement:

$$
\text{No additional minimal support exists.}
$$

---

# 15. DDD interpretation

I would **not** introduce a new bounded context.

The concepts belong to the existing Dependency/Assurance machinery.

Minimal model:

```text
Dependency
   │
   ├── Target
   ├── SupportCandidate
   └── Kind
        │
        ↓
SupportAssessment
        │
        ├── Sufficient
        ├── Insufficient
        └── Unknown
        │
        ↓
MinimalityAssessment
        │
        ↓
MinimalSupportSetAssessment
        │
        ↓
DependencyOrderAssessment
```

L4 can then hold:

```text
MinimalSupportCertificate
DependencyOrderCertificate
```

only if the relevant evidence is strong enough.

This keeps the architecture small.

---

# 16. Architecture optimization

After R593, the dependency/completeness architecture can be simplified conceptually to:

```text
Evidence
   ↓
Candidate Dependency
   ↓
Dependency Structure
   ↓
Support Analysis
   ↓
Minimal Support
   ↓
Dependency Order
   ↓
Higher-Order Coverage
   ↓
Universe Closure
   ↓
Completeness
   ↓
Negative Impact Assurance
```

This is better than treating:

```text
ordinary dependency
multi-factor dependency
higher-order dependency
order bound
hyperdependency
```

as unrelated concepts.

They form one hierarchy.

---

# 17. Important architectural rule

We should **not yet introduce Hyperdependency as a permanent domain primitive**.

R591 showed that an ordinary graph is insufficient to represent certain higher-order interactions.

R593 now shows that the deeper semantic object is actually:

$$
\boxed{\text{Minimal Support}}
$$

rather than merely:

$$
\boxed{\text{Hyperedge}}
$$

A hypergraph may be an implementation representation, but we should not mistake the representation for the epistemic concept.

This is consistent with the KnowledgeOS invariant:

$$
\boxed{
Representation\neq Reality.
}
$$

So the current optimal architecture is:

$$
\text{Dependency}
+
\text{Support Relation}
$$

with hypergraph representation available at L2 if useful.

---

# 18. New candidate invariants

### I-C39 — Minimality Requires Proper-Subset Exclusion

$$
Minimal(S,Z)
\Rightarrow
Sufficient(S,Z)
\land
\forall S'\subsetneq S:
\neg Sufficient(S',Z).
$$

### I-C40 — Dependency Order Is Minimal-Support Relative

$$
ord(D)
$$

must be computed from validated minimal supports, not arbitrary dependency representation.

### I-C41 — Alternative Minimal Supports Must Be Preserved

If:

$$
S_1,S_2\in MinSupports(Z)
$$

and neither contains the other, both must remain represented.

### I-C42 — ML Minimal Support Is Candidate-Only

$$
ML\_Support
\not\Rightarrow
VerifiedMinimalSupport.
$$

### I-C43 — False Minimality Invalidates the Order Bound

$$
FalseMinimality
\Rightarrow
OrderBoundAssessment\neq ESTABLISHED.
$$

These should remain **candidate invariants** until we test them in the broader benchmark.

---

# 19. Connection to the earlier theory

R593 actually connects several previously separate KnowledgeOS strands:

$$
\boxed{
R604.9
\rightarrow
R592
\rightarrow
R593
\rightarrow
HigherOrderClosure
}
$$

Specifically:

```text
Alternative minimal explanations
             ↓
       Minimal supports
             ↓
       Dependency order
             ↓
   Higher-order interaction
             ↓
     Universe closure
             ↓
    Completeness assurance
```

This is a sign that the theory is becoming more coherent rather than accumulating disconnected concepts.

---

# 20. What R593 does NOT prove

We must keep the epistemic boundary clear.

The benchmark establishes finite executable examples for:

* minimal support;
* redundant factors;
* alternative supports;
* three-way interaction;
* XOR;
* false minimality;
* ML candidate separation.

It does **not** yet prove a universal theorem that the chosen evaluator model captures every possible dependency semantics.

That question remains open.

---

# R594 — next step

The next step should therefore be:

$$
\boxed{\textbf{R594 — Minimal-Support Completeness and Hypergraph Equivalence}}
$$

The key research question:

> **Can every relevant dependency structure needed by KnowledgeOS be represented soundly as a family of minimal supports, and when is that representation equivalent to a hypergraph?**

We should test:

1. Ordinary graph vs hypergraph vs minimal-support representation.
2. AND, OR, XOR, NAND and more complex Boolean structures.
3. Multiple alternative explanations.
4. Redundant factors.
5. Cancellation.
6. Monotone vs non-monotone dependencies.
7. Whether minimal-support representation loses information.
8. Whether a hypergraph loses semantic information.
9. Whether minimal supports can be generated without exponential enumeration.
10. Whether ML can propose minimal supports efficiently while a deterministic verifier establishes them.
11. Whether this can become the **single canonical dependency representation** rather than adding several competing dependency primitives.

That last question is particularly important for the final architecture.

---

## KnowledgeOS status

* **Foundational theory:** ~90% structurally stabilized.
* **Dependency theory:** ordinary → multi-factor → higher-order now connected.
* **Completeness theory:** strong and increasingly executable.
* **Negative assurance:** explicitly protected against hidden dependencies.
* **ML:** correctly constrained to candidate generation.
* **DDD architecture:** still getting **smaller**, not larger.
* **New architectural primitive justified:** **none yet**.
* **Most important unresolved mathematical issue:** minimal-support completeness.

### Remaining TODOs

* **R594:** minimal-support completeness / hypergraph equivalence.
* Prove or refute a canonical dependency representation.
* Establish sound higher-order closure composition.
* Integrate dependency order with revalidation and VoI.
* Build larger W1–W7 + higher-order benchmark.
* Adversarial ML dependency discovery.
* Finalize certificate conditions for `NOT_AFFECTED`.
* Only then perform the final KnowledgeOS architecture freeze.
