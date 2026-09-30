I continued from **R604.3** and read the attached `knowledgeos_r604_3_reference_calculus.py`. I also used it as the baseline for the next executable step.

The correct next step is **R604.4 — Preservation Composition**.

One important point emerged while implementing it: the first draft of my R604.4 test suite contained an invalid counterexample. I caught it because the executable test failed, corrected the mathematical example, and only then obtained **10/10**. This is itself valuable evidence for the KnowledgeOS methodology.

# R604.4 — Preservation Composition

## 1. The central question

We currently know how to say:

$$
Preserve(T,Z)
$$

for an individual transformation.

The unresolved question is:

> If \(T_1\) preserves something and \(T_2\) preserves something, under what exact conditions does \(T_2\circ T_1\) preserve it?

We must not assume:

$$
Preserve(T_1)\land Preserve(T_2)
\Rightarrow
Preserve(T_2\circ T_1).
$$

That implication is generally **false** unless the preservation targets are appropriately related.

---

# 2. Definitions

## 2.1 Preservation Target

A **Preservation Target** is a property of a state/value that a transformation is required to leave invariant.

Write:

$$
Z:X\rightarrow V.
$$

A transformation

$$
T:X\rightarrow X
$$

preserves \(Z\) if:

$$
\forall x\in X:
Z(T(x))=Z(x).
$$

### Example

Let:

$$
Z(x)=x\bmod 2.
$$

Then:

$$
T(x)=x+2
$$

preserves parity because:

$$
(x+2)\bmod2=x\bmod2.
$$

---

# 3. Preservation is target-relative

This is fundamental.

The same transformation can preserve one target and destroy another.

For:

$$
T(x)=x+3
$$

we have:

$$
(x+3)\bmod3=x\bmod3.
$$

Therefore it preserves:

$$
Z_3(x)=x\bmod3.
$$

But it does **not** preserve parity:

$$
(x+3)\bmod2\neq x\bmod2.
$$

Therefore:

$$
\boxed{
Preservation(T,Z_1)
\not\Rightarrow
Preservation(T,Z_2)
}
$$

This is why `PreservationTarget` must remain explicit.

---

# 4. First composition theorem

There is a simple and important theorem.

Suppose:

$$
T_1:X\rightarrow Y
$$

and:

$$
T_2:Y\rightarrow Z.
$$

Suppose there is a common observable target \(P\) that is meaningful on all relevant states.

If:

$$
P(T_1(x))=P(x)
$$

and:

$$
P(T_2(y))=P(y),
$$

then:

$$
P(T_2(T_1(x)))=P(x).
$$

### Proof

From preservation by \(T_2\):

$$
P(T_2(T_1(x)))=P(T_1(x)).
$$

From preservation by \(T_1\):

$$
P(T_1(x))=P(x).
$$

Therefore:

$$
P(T_2(T_1(x)))=P(x).
$$

Hence:

$$
\boxed{
Preserve(T_1,P)\land Preserve(T_2,P)
\Rightarrow
Preserve(T_2\circ T_1,P)
}
$$

under the necessary domain/precondition assumptions.

This is a genuine mathematical result.

---

# 5. Why that theorem is not sufficient for KnowledgeOS

Usually:

$$
X\neq Y.
$$

Therefore the same target \(P\) may not even make sense on both domains.

Example:

```text
X = raw documents
Y = extracted facts
```

A target such as:

```text "exact character sequence"
```

may be meaningful in \(X\) but not in \(Y\).

So KnowledgeOS needs a way to **transport the target across the transformation boundary**.

This is the important new result of R604.4.

---

# 6. Preservation Bridge

I introduced an executable concept:

$$
\boxed{PreservationBridge}
$$

It connects:

$$
Z_X
$$

with:

$$
Z_Y.
$$

The bridge establishes that the target observed after transformation corresponds to the target observed before transformation.

For:

$$
T_1:X\rightarrow Y
$$

we need:

$$
Z_X(x)=Z_Y(T_1(x)).
$$

This allows us to reason across different representations.

---

# 7. The stronger composition condition

For:

$$
T_1:X\rightarrow Y
$$

and:

$$
T_2:Y\rightarrow Z,
$$

we can establish preservation of \(Z_X\) through the composition if we have:

### Condition 1

\(T_1\) preserves \(Z_X\):

$$
Z_X(T_1(x))=Z_X(x)
$$

where that expression is meaningful.

### Condition 2

There is a valid bridge:

$$
Z_X(x)=Z_Y(T_1(x)).
$$

### Condition 3

\(T_2\) preserves \(Z_Y\):

$$
Z_Y(T_2(y))=Z_Y(y).
$$

Then:

$$
\boxed{
Preserve(T_2\circ T_1,Z_X)
}
$$

under the declared scope, regime, and preconditions.

This is the correct general shape.

---

# 8. Concrete example

Suppose:

```text
Raw integer
      ↓
representation transformation
      ↓
normalized integer
```

Let:

$$
T_1(x)=x
$$

and:

$$
T_2(y)=y.
$$

Define:

$$
Z_X(x)=x
$$

and:

$$
Z_Y(y)=y.
$$

The bridge is:

$$
Z_X(x)=Z_Y(T_1(x)).
$$

Both operations preserve the target.

Therefore the composition preserves it.

The executable calculus confirmed this.

---

# 9. Counterexample: individual preservation does not automatically compose

Now construct:

$$
T_1(x)=x+2.
$$

It preserves parity:

$$
Z_2(x)=x\bmod2.
$$

Next:

$$
T_2(y)=y+3.
$$

It preserves modulo 3:

$$
Z_3(y)=y\bmod3.
$$

Thus individually:

$$
Preserve(T_1,Z_2)
$$

and:

$$
Preserve(T_2,Z_3).
$$

But their composition is:

$$
T_2(T_1(x))
=x+5.
$$

This does **not** preserve parity:

$$
(x+5)\bmod2\neq x\bmod2.
$$

It also does not preserve modulo 3:

$$
(x+5)\bmod3\neq x\bmod3.
$$

Therefore:

$$
\boxed{
Preserve(T_1,Z_1)
\land
Preserve(T_2,Z_2)
\not\Rightarrow
Preserve(T_2\circ T_1,Z_1)
}
$$

and likewise not for \(Z_2\).

This is a genuine counterexample.

---

# 10. This gives us an important KnowledgeOS invariant

I recommend freezing the following principle:

$$
\boxed{
Preservation\ is\ target\text{-}relative\ and\ composition\text{-}conditional.
}
$$

More formally:

> Preservation of separate targets by separate transformations does not establish preservation of either target by their composition unless the targets are connected by an explicit valid preservation bridge.

This is stronger and more precise than simply saying "preservation composes."

---

# 11. Precondition problem

There is another subtle issue.

Suppose:

$$
T(x)
$$

is defined only when:

$$
x\ge0.
$$

Then testing:

$$
x<0
$$

does not produce preservation evidence.

It produces:

$$
\boxed{UNDEFINED}
$$

or, depending on the test specification:

$$
NOT\_APPLICABLE.
$$

It must **not** produce:

$$
PASS.
$$

R604.4 explicitly tested this.

---

# 12. Empty test domain

Suppose the test domain contains only values for which the operation is undefined.

Then:

```text
defined samples = ∅
```

We must not say:

```text
PASS
```

because nothing was actually tested.

The correct result is:

$$
\boxed{UNKNOWN}
$$

unless the contract explicitly establishes another status.

This is another important assurance rule.

---

# 13. R604.4 executable result

I created:

**[Download R604.4 Reference Calculus](sandbox:/mnt/data/knowledgeos_r604_4_reference_calculus.py)**

The final executable suite produced:

```text
R604.4 preservation-composition tests: 10/10 passed
```

The tests cover:

1. same-target preservation composition;
2. preservation through a target bridge;
3. counterexample showing independent targets do not compose;
4. invalid preservation bridge detection;
5. explicit preservation counterexample;
6. undefined precondition region;
7. empty defined domain → UNKNOWN;
8. target-relative preservation;
9. type compatibility does not imply preservation;
10. directional nature of preservation bridges.

---

# 14. An important methodological finding

During implementation, the first version of the counterexample was wrong.

The attempted operation was:

$$
T_2(x)=2x
$$

with "evenness" as the supposed preserved target.

But:

$$
Even(2x)=True
$$

while:

$$
Even(x)
$$

is not always true.

Therefore it did **not** preserve evenness.

The executable test caught the mistake.

I replaced it with:

$$
T_2(x)=x+3
$$

and:

$$
Z_3(x)=x\bmod3.
$$

Now:

$$
Z_3(x+3)=Z_3(x)
$$

is genuinely true.

This is an excellent example of why KnowledgeOS should use:

$$
\boxed{
Formalization\rightarrow Execution\rightarrow Counterexample\rightarrow Correction
}
$$

rather than trusting apparently plausible reasoning.

---

# 15. What this means for the architecture

We do **not** need a new bounded context.

We also do not need a new Kernel primitive.

The existing architecture is sufficient.

### L2 — Formal Fabric

Owns:

* transformations;
* compatibility;
* preservation targets;
* preservation bridges;
* composition.

### L4 — Assurance

Owns:

* preservation verification;
* counterexample search;
* test validity;
* certificates;
* UNKNOWN/FAIL/CONDITIONAL handling.

That separation is clean.

---

# 16. One architectural refinement

We should distinguish:

$$
PreservationTarget
$$

from:

$$
PreservationAssessment
$$

and:

$$
PreservationBridge.
$$

They are three different things.

### PreservationTarget

"What property do we care about?"

### PreservationBridge

"How does that property correspond across a transformation boundary?"

### PreservationAssessment

"Did the transformation actually preserve it?"

Therefore:

$$
\boxed{
Target\neq Bridge\neq Assessment
}
$$

This fits the existing KnowledgeOS pattern:

$$
Specification\neq Execution\neq Result\neq Assessment\neq Certificate.
$$

---

# 17. ML implications

This is another area where ML can help, but only as a candidate generator.

Suppose an ML model observes two transformations and predicts:

```text
"These operations probably preserve the same business invariant."
```

It can produce:

$$
CandidatePreservationBridge
$$

with a score:

$$
p=0.94.
$$

But the system must not accept it as fact.

Correct pipeline:

$$
ML
\rightarrow CandidateBridge
\rightarrow TypeCheck
\rightarrow ScopeCheck
\rightarrow RegimeCheck
\rightarrow ContractCheck
\rightarrow Formal/EmpiricalVerification
\rightarrow Assessment.
$$

So ML can help discover preservation relationships, especially in large semantic models, but cannot establish them.

---

# 18. R604.5 should now become very specific

I would **not** move to another broad theoretical topic yet.

The next step should be:

# R604.5 — Loss Composition and Information Recovery

We already know:

$$
Loss(T_2\circ T_1)
\neq
Loss(T_1)\cup Loss(T_2)
$$

as a universal theorem.

Now we need to determine exactly what can be said.

The key questions are:

1. What is **information loss**?
2. What is **declared loss**?
3. What is **irreversible loss**?
4. What is **recoverable loss**?
5. What is **reconstruction**?
6. When does a later transformation genuinely recover information?
7. When is it merely introducing external information?
8. Can provenance distinguish the two?
9. Can TPP/identifiability characterize irreversible loss?
10. Can we derive a mathematically sound composition law?

This is potentially one of the most important mathematical pieces still missing.

---

# 19. A likely deep connection

We already have:

$$
TPP(\pi_F,Z)
$$

and:

$$
Identifiable(Z,F,W_O)
\iff
TPP(\pi_F,Z\mid W_O).
$$

This suggests that loss should **not** become another independent philosophical concept.

Instead, we may be able to characterize certain losses through identifiability.

For example:

$$
\pi:X\rightarrow Y
$$

loses information relevant to \(Z\) exactly when:

$$
\neg TPP(\pi,Z).
$$

That gives us a much stronger architecture:

$$
\boxed{
Loss\rightarrow TPP\rightarrow Identifiability
}
$$

rather than inventing a separate theory of "information loss."

But this needs to be tested carefully before we adopt it.

---

# 20. Current status

### We have now established/executed

* **Kernel:** stable.
* **State \(K=(X,H)\):** stable.
* **Operation algebra:** executable.
* **Class × Mutation:** executable.
* **Compatibility Witness:** executable.
* **Scope:** executable.
* **Regime:** executable.
* **Provenance:** executable.
* **Composition:** executable.
* **Associativity:** correctly tested at function/specification level.
* **Non-commutativity:** explicit counterexample.
* **Observational equivalence:** executable.
* **Preservation:** executable.
* **Preservation composition:** now has a mathematically justified sufficient-condition structure.
* **Preservation counterexamples:** executable.
* **UNKNOWN vs PASS:** explicitly protected.
* **ML firewall:** preserved.
* **No new BC:** justified.

### Remaining TODOs

* **R604.5:** Loss composition / information recovery.
* Test connection between **Loss ↔ TPP ↔ Identifiability**.
* Provenance-based distinction between **recovery** and **external augmentation**.
* History/event composition.
* Formal test-validity model.
* Complete semantic law for Class × Mutation composition.
* Adversarial ML transformation generation.
* Connect calculus to the **W1–W7 dependency benchmark**.
* Terminology freeze.
* Final executable reference calculus.
* **KnowledgeOS Theory Specification v1.0**.

### Distance to the goal

We are now moving beyond a conceptual theory toward a **small, executable formal calculus with explicit counterexamples and assurance boundaries**.

The important change is methodological:

$$
\boxed{
\text{We are no longer merely adding KnowledgeOS concepts;
we are eliminating concepts that cannot survive executable counterexample testing.}
}
$$

That is exactly the direction I recommend continuing.
