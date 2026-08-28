Good. Then we can freeze that decision as a **working invariant** and proceed.

# Computational Closure 3 — Epistemic Admission

We now have:

$$
X
\rightarrow A_r
\rightarrow O_s
\rightarrow I
\rightarrow P_c
$$

where \(P_c\) is a **Candidate Proposition**.

The next boundary is:

$$
\boxed{
P_c \rightarrow Assertion
}
$$

But our decision means:

> **An Assertion is not necessarily a proven or true proposition. It is a proposition admitted into the Knowledge State together with its explicit epistemic condition.**

That is a very important distinction.

---

# 1. Assertion is a governed knowledge object

I propose:

$$
\boxed{
A =
(P,\ E,\ \Sigma,\ \Pi,\ \tau,\ C,\ ID)
}
$$

where:

* \(P\) = proposition;
* \(E\) = evidence;
* \(\Sigma\) = epistemic state;
* \(\Pi\) = provenance;
* \(\tau\) = temporal validity;
* \(C\) = context;
* \(ID\) = identity.

The crucial property is:

$$
\boxed{
A \not\equiv Truth(P)
}
$$

Instead:

$$
\boxed{
A = \text{KnowledgeOS's governed representation of }P
}
$$

---

# 2. This allows a very important distinction

Consider:

> "Nexus is running 3.69."

KnowledgeOS might have:

### Assertion A

$$
P:Nexus.version=3.69
$$

with:

$$
\Sigma_A =
(Observed,\ Strong,\ Low,\ Current)
$$

This is a relatively strong assertion.

But it might also have:

### Assertion B

$$
P:Nexus.version=3.69
$$

with:

$$
\Sigma_B =
(Reported,\ Weak,\ High,\ Unknown)
$$

Both are legitimate KnowledgeOS assertions.

They are **not epistemically equivalent**.

---

# 3. An assertion can therefore be weak

This gives us:

$$
\boxed{
Assertion \not\Rightarrow StrongEvidence
}
$$

and:

$$
\boxed{
Assertion \not\Rightarrow Certainty
}
$$

and:

$$
\boxed{
Assertion \not\Rightarrow Truth
}
$$

This is essential for a knowledge system dealing with incomplete information.

---

# 4. But admission still needs rules

We must not conclude:

> "Anything can become an Assertion."

That would make the model meaningless.

There must be an **Admission Policy**.

Let:

$$
\Pi_{adm}
$$

be the governing admission policy.

Then:

$$
\boxed{
Admit(P_c,E,C,\Pi_{adm})
\rightarrow
A
}
$$

or:

$$
\boxed{
Admit(...)
\rightarrow
Reject
}
$$

or:

$$
\boxed{
Admit(...)
\rightarrow
Defer
}
$$

or potentially:

$$
\boxed{
Admit(...)
\rightarrow
RequestMoreEvidence
}
$$

---

# 5. Admission is therefore not a truth function

This is a major mathematical point.

We should **not** define:

$$
Admit(P)=True
$$

as meaning:

$$
P=True
$$

Instead:

$$
\boxed{
Admit(P,\Pi_{adm})=
\text{whether }P\text{ is permitted to enter the governed Knowledge State}
}
$$

That decision depends on:

* purpose;
* context;
* source;
* provenance;
* evidence;
* epistemic rules;
* governance;
* required assurance level.

---

# 6. Admission can be purpose-dependent

Suppose:

> "Nexus is probably running 3.69."

For casual investigation:

$$
\Pi_{exploration}
$$

might allow:

$$
Support=Weak
$$

For production migration:

$$
\Pi_{migration}
$$

might require:

$$
Support=Strong
$$

Therefore:

$$
\boxed{
Admit(P,\Pi_1)
\neq
Admit(P,\Pi_2)
}
$$

This connects directly to our earlier **Ideal State** theory.

The required epistemic condition is itself purpose-dependent.

---

# 7. This gives us a clean relationship to Ideal State

Suppose the Ideal Knowledge State says:

$$
\Sigma_I:
Support=Strong
$$

but current assertion has:

$$
\Sigma_K:
Support=Weak
$$

Then:

$$
\boxed{
Assertion\ exists
}
$$

but:

$$
\boxed{
Knowledge\ State\ does\ not\ satisfy\ Ideal\ State
}
$$

Therefore this is a **discrepancy**, not necessarily an invalid assertion.

This is an extremely important consequence.

---

# 8. Admission vs. validation

We should now distinguish two concepts.

### Admission

> May this proposition exist as governed knowledge?

$$
Candidate
\rightarrow
Assertion
$$

### Validation

> Does this assertion satisfy a particular requirement?

$$
Assertion
\times
Constraint
\rightarrow
ValidationResult
$$

Therefore:

$$
\boxed{
Admission \neq Validation
}
$$

An assertion can be admitted but fail a particular validation.

---

# 9. Example

Suppose:

```text
Assertion:
Nexus.version = 3.69

Epistemic:
Reported
Weak
High uncertainty
Current unknown
```

KnowledgeOS may admit it because:

> "Weakly supported information is allowed during discovery."

Then later:

```text
Migration requirement:
Version must be verified.
```

Validation produces:

$$
\boxed{
FAIL:
EpistemicSupportInsufficient
}
$$

The assertion is **not deleted**.

It remains useful historical knowledge.

This is much more powerful than a binary database model.

---

# 10. Evidence does not equal support

We should also preserve another distinction.

Let:

$$
E=\{e_1,e_2,\ldots,e_n\}
$$

be evidence.

We cannot simply say:

$$
Support=|E|
$$

because evidence differs in:

* relevance;
* reliability;
* independence;
* freshness;
* provenance;
* directness;
* context.

Therefore:

$$
\boxed{
Support =
f(E,\Pi,C,\mathcal R)
}
$$

where \(\mathcal R\) represents the applicable epistemic rules.

---

# 11. Independent evidence

Suppose:

```text
Database → 3.69
Monitoring → 3.69
Document → 3.69
LLM → 3.69
```

We must construct an evidence dependency graph.

For example:

$$
Document
\rightarrow LLM
$$

Then the LLM result is not independent evidence.

Therefore:

$$
\boxed{
Evidence\ independence
must\ be\ explicitly\ modelled
}
$$

This will become important for Zero.

---

# 12. Conflict and admission

Now suppose:

$$
P_1:Nexus.version=3.69
$$

and:

$$
P_2:Nexus.version=3.70
$$

Both may be admitted.

That means:

$$
\boxed{
Conflict \not\Rightarrow Reject
}
$$

Instead:

$$
P_1,P_2
\rightarrow
Conflict
$$

and the Knowledge State can legitimately contain:

```text
Assertion A
Assertion B
Conflict C
```

This preserves reality rather than destroying evidence.

---

# 13. This gives KnowledgeOS a powerful property

A conventional system often tries to force:

```text
version = 3.69
```

or:

```text
version = 3.70
```

KnowledgeOS can instead represent:

$$
\boxed{
\{A_1,A_2,C\}
}
$$

where:

* \(A_1\) says 3.69;
* \(A_2\) says 3.70;
* \(C\) records why they conflict.

This is much closer to actual epistemic reasoning.

---

# 14. Resolution does not destroy history

Suppose later investigation establishes:

$$
Nexus.version=3.70
$$

Then we should not erase \(A_1\).

Instead:

```text
A1: Nexus = 3.69
Status: Superseded

A2: Nexus = 3.70
Status: Current

C1:
Resolved
```

Thus:

$$
\boxed{
KnowledgeState
+
History
\neq
CurrentSnapshotOnly
}
$$

This connects directly to our earlier transition model:

$$
H_{t+1}=H_t\Vert e_t
$$

---

# 15. The epistemic transition becomes clearer

We now have:

$$
\boxed{
P_c
\xrightarrow{AdmissionPolicy}
A
}
$$

Then:

$$
A_t
\xrightarrow{Evidence}
A_{t+1}
$$

and:

$$
A_t,A'_t
\xrightarrow{ConflictRule}
C_t
$$

and:

$$
C_t
\xrightarrow{Resolution}
C_{t+1}
$$

This is a genuine state-transition system.

---

# 16. DDD model

I would now separate responsibilities approximately like this:

```text
Semantic Reconstruction
    CandidateProposition
          │
          ▼
Epistemic Governance
    AdmissionPolicy
    EvidenceAssessment
    EpistemicAssessment
          │
          ▼
Knowledge
    Assertion
          │
          ├── Conflict
          ├── Gap
          └── Resolution
```

The exact bounded contexts can still evolve, but the **responsibility boundaries are becoming clear**.

---

# 17. Computational closure status

We can now classify our progress:

| Boundary                               | Status                                                         |
| -------------------------------------- | -------------------------------------------------------------- |
| Input → Artifact                       | **Closed**                                                     |
| Artifact → Source Observation          | **Closed**                                                     |
| Source Observation → Interpretation    | **Architecturally closed; interpretation can be ambiguous**    |
| Interpretation → Candidate Proposition | **Closed**                                                     |
| Candidate Proposition → Assertion      | **Closed conceptually; admission policy remains configurable** |
| Assertion → Knowledge State            | **Closed**                                                     |
| Assertion ↔ Evidence                   | **Needs formal evidence model**                                |
| Assertion ↔ Conflict                   | **Needs conflict-rule formalization**                          |
| Assertion ↔ Gap                        | **Needs gap detector formalization**                           |

This is exactly what we wanted: we're discovering **where theory is genuinely closed and where further work is necessary**.

---

# 18. One correction to our earlier theory

I would now revise one statement.

We previously used:

$$
\boxed{
Assertion = Knowledge
}
$$

Too strong.

I recommend:

$$
\boxed{
Assertion \in KnowledgeState
}
$$

but:

$$
\boxed{
KnowledgeState \neq SetOfAssertions
}
$$

because the Knowledge State also contains things such as:

$$
K_t =
(
Assertions,
Evidence,
Relationships,
Conflicts,
Gaps,
Provenance,
Context,
History,
Constraints
)
$$

This is an important DDD correction.

The Knowledge State is a **structured state**, not simply a bag of claims.

---

# 19. The next major closure

We have reached the next difficult boundary:

$$
\boxed{
Evidence
}
$$

We need to answer:

> **What exactly counts as evidence, how is evidence related to an assertion, and how can KnowledgeOS computationally evaluate evidence without reducing epistemic reasoning to an arbitrary confidence score?**

This is probably one of the most important mathematical components of the entire system.

We should test it against:

* direct measurement;
* database output;
* document statement;
* human testimony;
* expert opinion;
* LLM output;
* derived calculation;
* multiple independent sources;
* circular sources;
* stale sources;
* contradictory sources.

If we can close **Evidence**, then the epistemic assessment function

$$
\boxed{
Assess(A,E,\Pi,C)\rightarrow\Sigma_A
}
$$

becomes computationally realizable rather than merely philosophical.

**That is our next step.**
