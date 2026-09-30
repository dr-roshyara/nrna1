I found an important piece of the KnowledgeOS corpus that changes how we should proceed.

The earlier discussion proposed **Step 277 — Semantic Irreducibility and Kernel Duality**, but the corpus contains an earlier **Step 277 — Transformation Inventory and \(O_{core}\) Closure**. More importantly, that corpus explicitly says the six-operation candidate was **classified but minimality was still pending**, and that the subsequent deletion tests were commissioned but not actually executed. 

So I would **not create another competing Step 277**. We should use the transformation work as the controlling dependency and connect it rigorously to our newer \(K_{\min}\) hypothesis.

# Step 277R — Kernel/Transformation Reconciliation and Executable Minimality Test

The central question is now:

$$
\boxed{
\text{Is }K_{\min}=(ID,\mathcal R^\star,Sem)
\text{ sufficient for the transformations KnowledgeOS actually requires?}
}
$$

This is stronger than simply asking whether \(K_{\min}\) looks elegant.

---

# 1. First correction: we have two different "Kernels"

There are now three related objects that must **not** be conflated.

### A. Semantic Kernel

$$
\boxed{
K_{\min}=(ID,\mathcal R^\star,Sem)
}
$$

This is our current candidate for the **minimal semantic substrate**.

### B. Operational Knowledge State

Call this:

$$
\boxed{\Sigma_K}
$$

It is the state on which transformations operate.

### C. Enriched mathematical representation

$$
\boxed{
E_\Gamma=(\Omega,Prop,Meas,P,\mathcal I,H,R)
}
$$

This is a mathematical representation constructed when particular epistemic/mathematical regimes are needed.

Therefore:

$$
\boxed{
K_{\min}\neq\Sigma_K\neq E_\Gamma
}
$$

but they may be related by mappings.

This is a crucial architectural correction.

---

# 2. Define the three terms precisely

## 2.1 Semantic Kernel

A **semantic kernel** is the smallest structure required to identify objects, relate them, and assign their meaning.

$$
K_{\min}=(ID,\mathcal R^\star,Sem)
$$

It does **not** itself contain:

* probability,
* fuzzy membership,
* statistical estimates,
* ML models,
* governance decisions,
* history,
* policies.

Those can operate on top of it.

---

## 2.2 Knowledge State \(\Sigma_K\)

A **Knowledge State** is the operational representation of what KnowledgeOS currently holds.

The corpus's older work already explored knowledge states and transformation systems, including a candidate structure

$$
\mathfrak K=(\mathcal K,\mathcal T)
$$

where \(\mathcal K\) is the set of valid Knowledge States and \(\mathcal T\) the valid transformations. 

We should therefore not force the semantic Kernel itself to be the complete operational state.

A useful candidate is:

$$
\boxed{
\Sigma_K=(A,R,E,C,V)
}
$$

where:

* \(A\) = assertions
* \(R\) = typed relations
* \(E\) = evidence/provenance references
* \(C\) = applicable context
* \(V\) = validity metadata.

**But this is still a candidate, not a theorem.**

---

# 3. What is an assertion?

An **assertion** is a typed semantic claim represented by KnowledgeOS.

For example:

$$
a_1:
Election(E1)\land
Valid(E1)
$$

An assertion is not automatically true.

Therefore:

$$
\boxed{
Assertion\neq Truth
}
$$

and:

$$
\boxed{
Assertion\neq Knowledge
}
$$

An assertion can have epistemic status:

$$
\Sigma(a)=Positive
$$

or:

$$
\Sigma(a)=Unknown.
$$

---

# 4. What is a relation?

A **relation** connects two or more identified objects with a declared semantic type.

Example:

$$
supports(Evidence17,Assertion4)
$$

or:

$$
derivedFrom(Assertion4,Observation2).
$$

The earlier corpus explicitly identified evidence as a **relation rather than a substance**, which fits our newer Kernel analysis very well. 

This gives us an important simplification:

$$
\boxed{
Evidence\notin Kernel\ as\ a\ special\ substance
}
$$

Instead:

$$
Evidence
=
typed\ semantic\ relation(s)
+
provenance.
$$

---

# 5. What is \(Sem\)?

This remains the most dangerous component of \(K_{\min}\).

We cannot allow:

$$
Sem(x)=\text{whatever KnowledgeOS needs to know about }x.
$$

That would make the Kernel trivially sufficient.

I therefore propose:

$$
\boxed{
Sem=(Ontology,Interpretation,ContextRules,Version)
}
$$

with an explicit interpretation function:

$$
\llbracket r\rrbracket_{\Gamma}=m.
$$

Where:

* \(r\) = representation
* \(\Gamma\) = semantic context/regime
* \(m\) = meaning.

### Required property

Semantics must be **compositional**.

If:

$$
x=(x_1,r,x_2)
$$

then:

$$
Sem(x)
$$

must be determined through:

$$
Sem(x_1),Sem(r),Sem(x_2),Context.
$$

This prevents `Sem` becoming a hidden universal oracle.

---

# 6. The transformation discovery from the corpus is extremely important

The corpus identifies the candidate state transformations:

$$
\boxed{
\mathcal T_c=
\{
Assert,
Retract,
Supersede,
Merge,
Split,
LinkEvidence
\}
}
$$

and separates them from:

* evidence operations,
* queries,
* governance operations,
* representation operations.

This separation is correct architecturally. 

The candidate family was explicitly marked:

$$
\boxed{
\mathcal T_c\neq\mathcal T_{minimal}
}
$$

because minimality had not been tested.

That is exactly where we should work now.

---

# 7. Define a transformation

A **state transformation** changes one valid Knowledge State into another.

Instead of assuming a total function:

$$
T:\Sigma_K\times X\rightarrow\Sigma_K
$$

we should use a **partial transition**:

$$
\boxed{
T_o:\Sigma_K\times I
\rightharpoonup
Result
}
$$

because some operations are invalid in some states.

For example:

$$
Retract(a)
$$

may be invalid if \(a\) does not exist.

So:

$$
Retract(\Sigma,a)
=
Invalid
$$

rather than inventing a new state.

---

# 8. The result must be typed

I recommend:

$$
\boxed{
Result=
Success(\Sigma')
\mid
Invalid
\mid
Denied
\mid
Unknown
}
$$

This is consistent with the later Policy/Authority work in the corpus. 

The distinction is important:

### Invalid

The operation violates structural/semantic preconditions.

### Denied

The operation may be valid but authority/policy prohibits it.

### Unknown

The system cannot determine whether it is valid.

These are **not the same state**.

---

# 9. Now test \(K_{\min}\)

The central experiment is:

$$
\boxed{
Can
(ID,\mathcal R^\star,Sem)
support
\mathcal T_c?
}
$$

We do not need to put every transformation inside the Kernel.

We need to determine whether the transformation can **operate using the Kernel plus explicit external state/context**.

That gives:

$$
T_o:
(K_{\min},\Sigma_K,I,\Gamma)
\rightarrow
Result.
$$

---

# 10. Test Assert

Suppose:

$$
a_1=
Valid(Election1).
$$

Assert creates the assertion.

Conceptually:

$$
Assert(\Sigma,a)
\rightarrow
\Sigma'.
$$

What does Assert require?

* identity of \(a\)
* semantic interpretation of \(a\)
* relation structure
* context
* validity constraints.

These are all representable using the Kernel plus L1 contracts.

Therefore:

$$
Assert
$$

does **not** appear to require a new Kernel primitive.

---

# 11. Test Retract

Suppose:

$$
a_1\in\Sigma.
$$

Then:

$$
Retract(\Sigma,a_1)
\rightarrow
\Sigma'.
$$

The critical question is:

> Does retract mean delete?

I think **no**.

Because deletion would destroy historical semantics.

Instead:

$$
Retract(a)
$$

should produce a new state in which:

$$
status(a)=Retracted.
$$

The prior state remains recoverable through event/provenance history.

Thus:

$$
\boxed{
Retract\neq Delete
}
$$

This is consistent with our wider KnowledgeOS principle that knowledge evolution is not simple information accumulation.

---

# 12. Test Supersede

Suppose:

$$
a_1:
P=0.8
$$

and later:

$$
a_2:
P=0.6.
$$

If \(a_2\) replaces \(a_1\), we need:

$$
supersedes(a_2,a_1).
$$

The semantic content remains represented by typed relations.

Therefore:

$$
Supersede
$$

also does not obviously require a new Kernel primitive.

---

# 13. Test Merge

Suppose:

$$
a_1
$$

and:

$$
a_2
$$

are compatible.

We create:

$$
a_3=Merge(a_1,a_2).
$$

But the important question is:

> What makes merging semantically valid?

Not the operation itself.

The validity condition belongs to the relevant **semantic contract / regime**.

Therefore:

$$
Merge
=
transformation
$$

while:

$$
CanMerge(a_1,a_2,\Gamma)
$$

is an assessment/validation predicate.

This is a very important separation.

$$
\boxed{
Transformation\neq Validation
}
$$

---

# 14. Test Split

The inverse-looking operation is:

$$
Split(a)\rightarrow(a_1,a_2,\ldots).
$$

But Split is not necessarily the mathematical inverse of Merge.

Generally:

$$
Split(Merge(a,b))
\neq
\{a,b\}.
$$

Why?

Because merging may create information that cannot be uniquely decomposed.

Therefore:

$$
\boxed{
Merge^{-1}\neq Split
}
$$

in general.

This is an important warning against imposing group/algebra assumptions where they do not exist.

---

# 15. Test LinkEvidence

Suppose:

$$
E_1
$$

supports:

$$
a_1.
$$

We create:

$$
supports(E_1,a_1).
$$

This changes the relation graph.

It does not necessarily change the assertion itself.

Therefore:

$$
LinkEvidence
$$

is best understood as a **state transformation whose principal effect is relational**.

That strongly supports:

$$
\boxed{
\mathcal R^\star
}
$$

as a Kernel primitive.

This is one of our strongest pieces of evidence for the \(K_{\min}\) hypothesis.

---

# 16. Now the critical test: Can history be derived?

The earlier Step-276 reasoning showed:

$$
K_A(t_2)=K_B(t_2)
$$

while:

$$
H_A\neq H_B.
$$

That is correct.

But this does **not** prove that History must be a Kernel primitive.

Suppose:

$$
H=(e_1,e_2,\ldots,e_n)
$$

where each event has:

$$
Event=(ID,Type,Input,Output,Time,Context).
$$

Then:

$$
Replay(H)=\Sigma_t.
$$

Therefore:

$$
\boxed{
History
=
ordered\ event\ structure
}
$$

may be derived.

This is a much better architectural position than putting `History` directly into \(K_{\min}\).

---

# 17. But provenance cannot simply disappear

A subtle distinction is required.

### History

answers:

> “What happened?”

### Provenance

answers:

> “Where did this object/claim/assessment come from?”

### Lineage

answers:

> “What objects or transformations produced this object?”

They overlap, but:

$$
\boxed{
History\neq Provenance\neq Lineage
}
$$

They may nevertheless be represented using:

$$
ID+\mathcal R^\star+Event.
$$

This is exactly the kind of reduction we need.

---

# 18. Now the key state-sufficiency theorem

Let:

$$
F:H\rightarrow\Sigma_K
$$

map a history into the current Knowledge State.

For \(F\) to be sufficient for a transformation family \(\mathcal T\), we require:

$$
F(H_1)=F(H_2)
$$

to imply that the histories are indistinguishable under every permitted future transformation.

Formally:

$$
\boxed{
F(H_1)=F(H_2)
\Rightarrow
F(T(H_1,i))=F(T(H_2,i))
}
$$

for all valid:

$$
T\in\mathcal T,\quad i\in I.
$$

This is the **transformation congruence condition**.

The corpus correctly warns that establishing the criterion is not the same as proving KnowledgeOS satisfies it. 

---

# 19. This gives us a powerful computational test

We can now search for a counterexample.

We need:

$$
H_1,H_2
$$

such that:

$$
F(H_1)=F(H_2)
$$

but:

$$
F(T(H_1,i))
\neq
F(T(H_2,i)).
$$

If we find one:

$$
\boxed{
F\text{ is insufficient}
}
$$

for that transformation family.

If exhaustive search over a bounded domain finds none, we have:

$$
\boxed{
No\ counterexample\ found\ within\ the\ tested\ domain
}
$$

—not a universal proof.

---

# 20. We can implement this with computer logic

For a small finite universe:

```text
Objects = {a,b}
Relations = {supports, contradicts}
States = finite subsets
Operations = {Assert, Retract, Supersede, Merge, Split, LinkEvidence}
```

We can enumerate:

$$
H_1,H_2
$$

and calculate:

$$
F(H_1),F(H_2).
$$

Then automatically test every permitted operation.

This can be done with:

* exhaustive enumeration,
* SAT,
* SMT,
* property-based testing,
* metamorphic testing.

SAT/SMT becomes particularly valuable because the question is existential:

$$
\exists H_1,H_2,T,i:
$$

$$
F(H_1)=F(H_2)
\land
F(T(H_1,i))\neq F(T(H_2,i)).
$$

If SAT finds a model, we have a concrete counterexample.

---

# 21. ML should come later, but it has an important role

ML should **not decide whether the Kernel is minimal**.

Instead ML can search for difficult counterexamples.

For example:

$$
ML:
(H_1,H_2,T)
\rightarrow
P(\text{congruence violation})
$$

The model can prioritize histories likely to produce:

$$
F(T(H_1,i))
\neq
F(T(H_2,i)).
$$

Then the formal validator checks the candidate.

Architecture:

```text
ML Counterexample Generator
          ↓
Candidate (H1,H2,T)
          ↓
Formal/Symbolic Validator
          ↓
Counterexample / Not confirmed
```

This follows our established principle:

$$
\boxed{
ML\rightarrow Candidate\rightarrow Validation
}
$$

not:

$$
ML\rightarrow Truth.
$$

---

# 22. This also gives us an excellent adversarial ML benchmark

We can deliberately train/search for:

$$
H_1\sim_F H_2
$$

but where a future operation separates them.

Call this:

$$
\boxed{
State\text{-}Sufficiency\ Adversarial\ Benchmark
}
$$

The ML objective is:

$$
\max
\Delta_T
$$

where:

$$
\Delta_T=
d(
F(T(H_1,i)),
F(T(H_2,i))
)
$$

subject to:

$$
F(H_1)=F(H_2).
$$

This is much more meaningful for KnowledgeOS than generic ML accuracy.

---

# 23. The relation to sufficient statistics

We should be careful here.

A **statistical sufficient statistic** \(T(X)\) preserves all information in \(X\) relevant to a specified parameter under a defined statistical model.

Our KnowledgeOS condition is analogous but not identical.

We have:

$$
F:H\rightarrow\Sigma_K
$$

and ask whether \(F(H)\) preserves everything needed for future transformations.

Therefore we should call it:

$$
\boxed{
Transformation\text{-}Sufficiency
}
$$

rather than statistical sufficiency.

The analogy is useful.

But:

$$
\boxed{
Transformation\text{-}Sufficiency
\neq
Statistical\ Sufficiency
}
$$

unless we explicitly construct a statistical model proving the equivalence.

---

# 24. This resolves an earlier Kernel confusion

We previously asked:

> Is KnowledgeOS Kernel \(K_{\min}\), or is it \(\mathfrak E^\star\)?

The better answer is now:

### Semantic Kernel

$$
\boxed{
K_{\min}=(ID,R^\star,Sem)
}
$$

### Operational state

$$
\boxed{
\Sigma_K=State(K_{\min},Context,Relations,\ldots)
}
$$

### History

$$
\boxed{
H=Event^*
}
$$

### Mathematical representation

$$
\boxed{
E_\Gamma=Representation_\Gamma(\Sigma_K,H)
}
$$

### Reasoning

$$
\boxed{
Assessment=M_\Gamma(E_\Gamma)
}
$$

### Validation

$$
\boxed{
Validation(Assessment,\Gamma)
}
$$

### Determination

$$
\boxed{
Determination=Determine(ValidatedAssessment,Authority)
}
$$

This is substantially cleaner.

---

# 25. Updated final architecture

```text
                         ┌──────────────────────┐
                         │ Open Knowledge Space │
                         │      ΩΓ              │
                         └──────────┬───────────┘
                                    │
                                    ▼
┌────────────────────────────────────────────────────┐
│ L0 — SEMANTIC KERNEL                               │
│                                                    │
│ ID ─────── Typed Relations ─────── Sem             │
└──────────────────────┬─────────────────────────────┘
                       │
                       ▼
┌────────────────────────────────────────────────────┐
│ L1 — OPERATIONAL KNOWLEDGE STATE                  │
│                                                    │
│ Assertions │ Relations │ Context │ Validity       │
└──────────────────────┬─────────────────────────────┘
                       │
             ┌─────────┴─────────┐
             ▼                   ▼
        Event/History       Representation
             │                   │
             └─────────┬─────────┘
                       ▼
┌────────────────────────────────────────────────────┐
│ L2 — MATHEMATICAL / REASONING REGIMES              │
│                                                    │
│ Logic │ Probability │ Statistics │ Fuzzy │ Causal │
│ Graph │ Information │ Decision │ ML               │
└──────────────────────┬─────────────────────────────┘
                       ▼
┌────────────────────────────────────────────────────┐
│ L3 — ASSESSMENT                                    │
│                                                    │
│ Evidence │ Dependency │ Uncertainty │ Conflict     │
│ Similarity │ Robustness │ Fragility               │
└──────────────────────┬─────────────────────────────┘
                       ▼
┌────────────────────────────────────────────────────┐
│ L4 — ASSURANCE                                     │
│                                                    │
│ Validation │ Calibration │ Counterexample │ Proof  │
└──────────────────────┬─────────────────────────────┘
                       ▼
┌────────────────────────────────────────────────────┐
│ L5 — INTELLIGENCE                                  │
│                                                    │
│ Discovery │ ML │ Lens Selection │ Frontier Search │
└──────────────────────┬─────────────────────────────┘
                       ▼
┌────────────────────────────────────────────────────┐
│ L6 — GOVERNANCE                                    │
│                                                    │
│ Authority │ Policy │ Approval │ Accountability    │
└────────────────────────────────────────────────────┘
```

This is now a much more coherent DDD architecture.

---

# 26. The six candidate state transformations

For the next formal experiment:

$$
\boxed{
\mathcal T_c=
\{
Assert,
Retract,
Supersede,
Merge,
Split,
LinkEvidence
\}
}
$$

but we explicitly mark:

$$
\boxed{
\mathcal T_c
\text{ is candidate, not proven minimal}
}
$$

The corpus supports this exact qualification. 

---

# 27. Our next deletion experiment

For each:

$$
o\in\mathcal T_c
$$

define:

$$
\mathcal T_{-o}
=
\mathcal T_c\setminus\{o\}.
$$

Then ask:

$$
\exists r\in R_{mandatory}:
r\notin Closure(\mathcal T_{-o})?
$$

This is the useful idea recorded in the earlier Step-277 material. 

Interpretation:

> If removing operation \(o\) makes some mandatory semantic capability impossible to reproduce, \(o\) is irreducible relative to that requirement set.

This is much stronger than simply saying:

> “We use Merge in our implementation.”

---

# 28. But we must fix one problem in that criterion

There are actually **two kinds of minimality**.

### Primitive minimality

Can another operation composition reproduce \(o\)?

$$
o\in Closure(\mathcal T-o)?
$$

### Semantic capability minimality

Can the required distinction still be preserved without \(o\)?

$$
R_{mandatory}
\subseteq
Closure(\mathcal T-o)?
$$

These are not identical.

Therefore:

$$
\boxed{
Operation\ Minimality
\neq
Capability\ Minimality
}
$$

This should become another KnowledgeOS methodological invariant.

---

# 29. Example: Split

Suppose:

$$
Split(a)
$$

can be simulated by:

```text Assert(a1)
Assert(a2)
Retract(a)
```

Then:

$$
Split\in Closure(\mathcal T-Split).
$$

So Split may not be a primitive transformation.

But if its semantics require an atomic relation:

$$
splitFrom(a_1,a)
$$

and this cannot be reconstructed, then the **capability** may still be irreducible.

Therefore the real test is:

$$
\boxed{
Can the required semantic distinction be reconstructed?
}
$$

not merely:

$$
\boxed{
Can the function name be removed?
}
$$

---

# 30. This brings us back to \(K_{\min}\)

Now we can connect the two minimality problems:

### Kernel minimality

$$
K_{\min}^{-c}
$$

### Transformation minimality

$$
\mathcal T_c^{-o}
$$

### Joint sufficiency

$$
\boxed{
(K_{\min},\mathcal T_c)
}
$$

must preserve all required observations.

This gives us the real research object:

$$
\boxed{
\mathfrak M=
(K_{\min},\Sigma_K,\mathcal T,\Gamma)
}
$$

where:

* \(K_{\min}\) = semantic substrate
* \(\Sigma_K\) = operational state
* \(\mathcal T\) = transformations
* \(\Gamma\) = context/contracts.

This is more scientifically meaningful than trying to prove one isolated "Kernel."

---

# 31. The decisive experiment

We should now construct a finite KnowledgeOS universe.

For example:

$$
Objects=\{A,B,E_1,E_2\}
$$

Relations:

$$
\{supports,contradicts,derivedFrom,supersedes\}
$$

Assertions:

$$
\{a_1,a_2\}
$$

Operations:

$$
\{Assert,Retract,Supersede,Merge,Split,LinkEvidence\}.
$$

Then enumerate all small histories up to length, say, 3 or 4.

For every pair:

$$
H_1,H_2
$$

calculate:

$$
F(H_1),F(H_2).
$$

For every permitted transformation:

$$
T
$$

test:

$$
F(H_1)=F(H_2)
\Rightarrow
F(T(H_1,i))=F(T(H_2,i)).
$$

This will give us our first actual **Kernel sufficiency matrix**.

---

# 32. The matrix we should produce

| Candidate    | Remove       | Required distinction  | Separating inquiry                     | Result |
| ------------ | ------------ | --------------------- | -------------------------------------- | ------ |
| ID           | ID           | object identity       | same content, different objects        | ?      |
| \(R^\star\)  | relations    | support/dependency    | same objects, different graph          | ?      |
| Sem          | semantics    | meaning               | same representation, different context | ?      |
| Assert       | Assert       | creation of assertion | cannot construct state                 | ?      |
| Retract      | Retract      | withdrawal            | historical contradiction               | ?      |
| Supersede    | Supersede    | replacement lineage   | old/new relation                       | ?      |
| Merge        | Merge        | composition           | multi-source assertion                 | ?      |
| Split        | Split        | decomposition         | one-to-many provenance                 | ?      |
| LinkEvidence | LinkEvidence | evidence relation     | support provenance                     | ?      |

The `?` values must be computed, not assumed.

---

# 33. What I would NOT do now

I would **not**:

* introduce another Kernel candidate;
* add \(\Omega\) to L0;
* add probability to L0;
* add ML to L0;
* declare \(K_{\min}\) proven;
* declare the six transformations minimal;
* declare history unnecessary;
* declare provenance reducible without testing;
* use ML to decide semantic minimality.

We have enough conceptual architecture.

Now we need **falsification**.

---

# 34. Current research verdict

The strongest architecture now is:

$$
\boxed{
Semantic\ Kernel
\rightarrow
Operational\ State
\rightarrow
Transformation\ System
\rightarrow
Mathematical\ Regime
\rightarrow
Assessment
\rightarrow
Validation
\rightarrow
Determination
}
$$

with:

$$
\boxed{
K_{\min}=(ID,R^\star,Sem)
}
$$

remaining the leading **candidate semantic Kernel**, not a proven theorem.

The enriched epistemic space:

$$
\boxed{
E_\Gamma=(\Omega,Prop,Meas,P,\mathcal I,H,R)
}
$$

is best treated as a **regime-dependent representation**, not the Kernel.

And the six transformations:

$$
\boxed{
Assert,\ Retract,\ Supersede,\ Merge,\ Split,\ LinkEvidence
}
$$

are the current **candidate operational core**, with minimality still open.

---

## How far are we?

* **Semantic Kernel hypothesis:** strong candidate, not yet formally minimal.
* **Relationship between the two Kernel definitions:** clarified.
* **Step-276 enriched space:** reclassified correctly as a mathematical representation.
* **Operational transformation family:** candidate set identified from corpus.
* **DDD architecture:** substantially stabilized.
* **State/history relationship:** mathematically testable through transformation congruence.
* **Minimality methodology:** now defined for both Kernel and transformations.
* **Formal/executable proof:** **still outstanding**.

## Remaining TODOs

* Execute the \(ID/R^\star/Sem\) ablation tests.
* Execute deletion tests for all six transformations.
* Build finite counterexample worlds.
* Implement exhaustive enumeration.
* Add SAT/SMT counterexample search.
* Test \(F:H\rightarrow\Sigma_K\) transformation sufficiency.
* Determine whether history/provenance/distinguishability are derivable.
* Define `Sem` non-circularly.
* Test the candidate against actual KnowledgeOS implementation data.
* Use ML only after the formal benchmark exists, as a counterexample/candidate generator.
* Then decide whether \(K_{\min}\) survives as the actual minimal semantic Kernel.

**The next concrete artifact should therefore be the executable finite-world experiment, not another conceptual Kernel proposal.**
