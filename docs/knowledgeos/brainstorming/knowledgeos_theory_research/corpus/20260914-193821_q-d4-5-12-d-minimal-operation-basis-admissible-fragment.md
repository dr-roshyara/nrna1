# KnowledgeOS Research Programme — Q-D4.5.12.d

## What is the minimal operation basis for the admissible fragment?

I have audited the attached Q-D4.5.12.c research against the established KnowledgeOS baseline. The attachment has produced a genuine mathematical result — the congruence characterization — and correctly identified that minimality is the next dependency. But I must audit it carefully, because the attachment makes a subtle claim about the *nature* of the minimization problem that affects how we should approach it.

---

# Part A — Audit of Q-D4.5.12.c

## A.1 What is genuinely derived

**Result 1 (Congruence characterization).** For a problem specification $\Pi$ with admissible continuation set $\mathcal{C}_\Pi$ and operation signature $\Sigma$:

$$
\equiv_\Pi \text{ is a congruence on } \Sigma_{\mathrm{adm}}^\Pi \iff \mathcal{C}_\Pi \text{ is closed under composition of operations in } \Sigma_{\mathrm{adm}}^\Pi
$$

**Result 2 (Non-congruence in general).** For general KnowledgeOS problem specifications, $\equiv_\Pi$ is not a congruence on the full signature $\Sigma$.

**Result 3 (Relative congruence).** Congruence is always relative to $\Pi$ and $\mathcal{C}_\Pi$.

**Result 4 (Admissible operation set).**

$$
\Sigma_{\mathrm{adm}}^\Pi = \{o \in \Sigma \mid o \text{ can appear in some admissible continuation of } \Pi\}
$$

These are correct. I have no mathematical objection.

## A.2 What the attachment correctly recognizes

The attachment correctly notes that congruence is not a universal property but a **relative** property. This is consistent with the Q-D4.5.12.b result that operation equivalence is inquiry-relative.

## A.3 The subtle claim I must audit

The attachment states:

> "The next question is Q-D4.5.12.d: derive the minimal operation basis for the admissible fragment."

This is correct as the **next question**. But there is a **prior sub-question** hidden inside it that the attachment does not address:

> **What does "minimal" mean for a partial signature with relative congruence?**

In classical algebra, "minimal generating set" is well-defined for total operations with a universal congruence. In KnowledgeOS:

- Operations are **partial**.
- Congruence is **relative to $\Pi$**.
- The admissible fragment $\Sigma_{\mathrm{adm}}^\Pi$ depends on $\Pi$.

So the minimization problem itself must be **re-derived** before it can be solved.

## A.4 What the attachment gets right

The attachment correctly identifies the dependency chain:

$$
\text{Congruence} \rightarrow \text{Minimal basis} \rightarrow \text{Minimal representation} \rightarrow \text{Kernel}
$$

And it correctly notes that without congruence, minimization is unsafe.

## A.5 What the attachment under-develops

The attachment does not formalize:

- What "generating set" means for a **partial** signature.
- How relative congruence affects minimality.
- What the **cost** of a generating set is (cardinality? total arity? something else?).

These must be addressed before the minimization problem can be posed precisely.

---

# Part B — Current Baseline (Post-Q-D4.5.12.c)

## B.1 Derived

| Result | Status |
|---|---|
| Required distinction preservation | Derived (D1) |
| Dynamic preservation | Derived (D2) |
| Minimal epistemic polarity lower bound | Derived (D3) |
| Evaluation quotient | Derived (D4) |
| Observational quotient | Derived (D4.5) |
| Operation equivalence | Derived (Q-D4.5.12.b) |
| Derivability | Derived (Q-D4.5.12.b) |
| Primitivity | Derived (Q-D4.5.12.b) |
| Congruence characterization | Derived (Q-D4.5.12.c) |
| Non-congruence in general | Derived (Q-D4.5.12.c) |
| Relative congruence | Derived (Q-D4.5.12.c) |

## B.2 Proposed

- Observable
- Observation family
- Minimal operation basis
- Minimal representation

## B.3 Open

- Minimal operation basis
- Minimal representation
- Kernel
- DDD boundaries

---

# Part C — Missing Architecture

The dependency chain is now:

$$
\underbrace{\text{Congruence}}_{\text{derived}}
\longrightarrow
\underbrace{\text{Minimal basis}}_{\textbf{next}}
\longrightarrow
\underbrace{\text{Minimal repr.}}_{\text{open}}
\longrightarrow
\underbrace{\text{Kernel}}_{\text{final}}
$$

The bottleneck is minimal basis. But before we can derive it, we must formalize **what minimality means** in the KnowledgeOS setting.

---

# Part D — Why Minimality Must Be Re-Derived Before It Is Solved

## D.1 The classical notion fails

In classical universal algebra, given a signature $\Sigma$ with total operations and a variety $\mathcal{V}$, a **generating set** is a subset $\Omega \subseteq \Sigma$ such that every operation in $\Sigma$ is a term in $\Omega$. Minimality means no proper subset of $\Omega$ generates $\Sigma$.

This **does not directly apply** to KnowledgeOS because:

1. **Operations are partial** — composition requires definedness care.
2. **Congruence is relative** — $\equiv_\Pi$ depends on $\Pi$.
3. **The admissible fragment depends on $\Pi$** — $\Sigma_{\mathrm{adm}}^\Pi$ varies.
4. **Primitivity is relative** — an operation can be primitive in one $\Pi$ and derivable in another.

## D.2 The KnowledgeOS-specific problem

For a problem specification $\Pi$:

- **Input:** the admissible operation set $\Sigma_{\mathrm{adm}}^\Pi$, the continuation set $\mathcal{C}_\Pi$, the observable semantics $\mathsf{Obs}_\Pi$, and the congruence relation $\equiv_\Pi$.
- **Output:** a minimal subset $\Omega_{\mathrm{req}}^\Pi \subseteq \Sigma_{\mathrm{adm}}^\Pi$ such that every $o \in \Sigma_{\mathrm{adm}}^\Pi$ is derivable from $\Omega_{\mathrm{req}}^\Pi$.

"Derivable" here means:

$$
o \equiv_\Pi \widehat{o}
$$

for some composition $\widehat{o} \in \langle \Omega_{\mathrm{req}}^\Pi \rangle$.

## D.3 The falsification dependency

Without a formal notion of minimality:

- We cannot prove that any set is minimal.
- We cannot falsify a proposed minimal set.
- We cannot compare two candidate minimal sets.

Therefore the **falsification protocol** for Q-D4.5.12.d requires a formal definition of minimality first.

## D.4 The Nexus illustration

For Nexus:

- $\Sigma_{\mathrm{adm}}^{\text{Nexus}} \supseteq \{\text{Assert}, \text{Link}, \text{Retract}, \text{Transition}\}$ (tentative)
- Candidate primitives: `Assert`, `Link`, `Retract`, `Transition`
- Candidate derivables: `Supersede`, `Merge`, `Isolate`, `Revise`

But without a formal minimality criterion, "primitive" vs "derivable" is a matter of definition, not derivation.

## D.5 The order-of-derivation principle

The programme's rule:

$$
\text{Mathematical distinction} \rightarrow \text{semantic concept} \rightarrow \text{domain boundary} \rightarrow \text{aggregate}
$$

Formal minimality is a **mathematical distinction** about the operation basis. It must precede the semantic concept "minimal operation basis."

---

# Part E — Research Question

$$
\boxed{
\textbf{Q-D4.5.12.d — What is the correct formalization of minimality for a partial operation signature with relative congruence?}
}
$$

More precisely:

> Given a problem specification $\Pi$ with admissible operation set $\Sigma_{\mathrm{adm}}^\Pi$, congruence $\equiv_\Pi$, and admissible continuation set $\mathcal{C}_\Pi$, what does it mean for a subset $\Omega \subseteq \Sigma_{\mathrm{adm}}^\Pi$ to be a **minimal generating set** for $\Sigma_{\mathrm{adm}}^\Pi$?

This is **not** the same as deriving the minimal set for Nexus. It is the **prior** question of what "minimal" should mean.

---

# Part F — Investigation

## F.1 Formal setup

Let:

$$
\Pi = (Q, \Gamma, R, \mathcal{C}_\Pi, \mathsf{Obs}_\Pi)
$$

be a problem specification.

Let:

$$
\Sigma_{\mathrm{adm}}^\Pi
$$

be the admissible operation set.

Let:

$$
\equiv_\Pi
$$

be the congruence on $\Sigma_{\mathrm{adm}}^\Pi$ (from Q-D4.5.12.c).

## F.2 Composition closure

Define $\langle \Omega \rangle$ as the **composition closure** of $\Omega \subseteq \Sigma_{\mathrm{adm}}^\Pi$:

$$
\langle \Omega \rangle = \{ \widehat{o} \mid \widehat{o} \text{ is a composition of operations in } \Omega \}
$$

Composition is **partial**: if $o_1(e)\uparrow$ or $o_2(o_1(e))\uparrow$, then $o_2 \circ o_1$ is not defined at $e$.

## F.3 Generating set

**Definition (Generating set).** $\Omega \subseteq \Sigma_{\mathrm{adm}}^\Pi$ is a **generating set** for $\Sigma_{\mathrm{adm}}^\Pi$ if:

$$
\forall o \in \Sigma_{\mathrm{adm}}^\Pi, \exists \widehat{o} \in \langle \Omega \rangle: o \equiv_\Pi \widehat{o}
$$

That is, every admissible operation is equivalent (in the sense of $\equiv_\Pi$) to some composition of operations in $\Omega$.

**Important:** The composition closure $\langle \Omega \rangle$ must be interpreted **modulo $\equiv_\Pi$**. That is, we identify compositions that are observationally equivalent.

## F.4 The problem with cardinality as a minimality criterion

The naive definition would be:

> $\Omega$ is minimal if no proper subset of $\Omega$ is a generating set.

This fails because:

1. **Two generating sets of the same cardinality may be incomparable.** $\Omega_1$ and $\Omega_2$ may both be minimal but neither contains the other.
2. **Cardinality does not capture complexity.** A single `Transition` with a complex event algebra may be "smaller" than four specialized operations.
3. **Relative congruence changes the comparison.** For different $\Pi$, the same set may or may not be minimal.

## F.5 The need for a partial order

To define minimality properly, we need a **partial order** on generating sets.

Candidate orders:

**(a) Cardinality order:**

$$
\Omega_1 \preceq \Omega_2 \iff |\Omega_1| \le |\Omega_2|
$$

This is a total preorder, not a partial order (many sets have the same cardinality).

**(b) Subset order:**

$$
\Omega_1 \preceq \Omega_2 \iff \Omega_1 \subseteq \Omega_2
$$

This is a partial order, but it is not total (many generating sets are incomparable).

**(c) Derivability order:**

$$
\Omega_1 \preceq \Omega_2 \iff \langle \Omega_1 \rangle \subseteq \langle \Omega_2 \rangle / {\equiv_\Pi}
$$

That is, $\Omega_1$ is weaker than $\Omega_2$ if every composition of $\Omega_1$ is equivalent to some composition of $\Omega_2$.

**The correct order depends on what we are minimizing.**

## F.6 The KnowledgeOS-specific minimality criterion

I propose:

**Definition (KnowledgeOS minimality).** $\Omega \subseteq \Sigma_{\mathrm{adm}}^\Pi$ is **KnowledgeOS-minimal** for $\Pi$ if:

1. **(Generating)** $\Omega$ generates $\Sigma_{\mathrm{adm}}^\Pi$: every $o \in \Sigma_{\mathrm{adm}}^\Pi$ is $\equiv_\Pi$-equivalent to some composition in $\langle \Omega \rangle$.
2. **(Irredundant)** No proper subset of $\Omega$ generates $\Sigma_{\mathrm{adm}}^\Pi$.
3. **(Congruence-preserving)** $\Omega$ is closed under $\equiv_\Pi$: if $o \in \Omega$ and $o' \equiv_\Pi o$, then $o' \in \Omega$ (or $o'$ is explicitly identified with $o$).

Condition 3 is the KnowledgeOS-specific addition. It ensures that the minimal basis is **stable** under the congruence, not just a syntactic representative.

## F.7 The problem with the subset order

The subset order is too strict: two minimal generating sets may be incomparable, and neither contains the other. This means "the minimal set" may not be unique.

**Example.** Consider:

$$
\Sigma_{\mathrm{adm}}^\Pi = \{a, b, c\}
$$

with:

$$
c \equiv_\Pi a \circ b
$$

Then both $\{a, b\}$ and $\{a, c\}$ are generating sets. Neither contains the other. Both are irredundant (removing any element breaks generation).

**Therefore "the minimal set" is not unique in general.**

## F.8 The correct statement

The correct statement is:

> There is a **set of minimal generating sets**, and the KnowledgeOS operation basis is one of them.

Formally:

$$
\mathcal{M}^\Pi = \{\Omega \subseteq \Sigma_{\mathrm{adm}}^\Pi \mid \Omega \text{ is KnowledgeOS-minimal}\}
$$

The choice of a particular $\Omega \in \mathcal{M}^\Pi$ is a **design decision**, not a mathematical necessity.

## F.9 The Nexus instantiation

For Nexus, the candidate minimal generating sets might be:

$$
\mathcal{M}^{\text{Nexus}} = \{\{Assert, Link, Retract, Transition\}, \{Assert, Link, Retract, Supersede\}, \ldots\}
$$

Each is minimal, but they differ in which operations are chosen as primitive. The choice depends on:

- Implementation convenience
- Domain requirements
- Complexity of the event algebra

## F.10 Falsification test

To falsify the claim that $\Omega$ is a minimal generating set:

- **Test 1 (Generation):** Find $o \in \Sigma_{\mathrm{adm}}^\Pi$ such that no composition in $\langle \Omega \rangle$ is $\equiv_\Pi$-equivalent to $o$.
- **Test 2 (Irredundancy):** Find a proper subset $\Omega' \subsetneq \Omega$ that generates $\Sigma_{\mathrm{adm}}^\Pi$.

If either test succeeds, $\Omega$ is not minimal.

## F.11 What this means for the programme

The programme cannot derive **the** minimal operation basis. It can derive:

- A **family** of minimal bases.
- **Criteria** for choosing among them.
- **Falsification tests** for proposed bases.

The final basis is a **design decision**, not a mathematical result.

## F.12 The DDD consequence

This has an important DDD consequence:

- **Aggregate boundaries** depend on the chosen minimal basis.
- Two different minimal bases may lead to different aggregate decompositions.
- The choice of basis is a **domain design decision**, not a mathematical consequence.

This is consistent with the programme's rule that DDD follows mathematics, not precedes it.

## F.13 The Kernel consequence

The Kernel cannot yet be derived. It requires:

- A chosen minimal basis $\Omega \in \mathcal{M}^\Pi$
- Verification that $\Omega$ is minimal
- Reduction of $\Omega$ under the observable semantics

Since $\Omega$ is not unique, the Kernel may depend on the choice. This is an **open question**: is the Kernel invariant under the choice of minimal basis?

## F.14 The relative nature of minimality

Minimality is relative to:

- The problem specification $\Pi$
- The admissible continuation set $\mathcal{C}_\Pi$
- The observable semantics $\mathsf{Obs}_\Pi$
- The congruence $\equiv_\Pi$

Therefore:

$$
\boxed{
\text{Minimality is } \Pi\text{-relative, not universal.}
}
$$

## F.15 The composition cost

A further complication: composition has a **cost**. If $o \equiv_\Pi o_1 \circ o_2 \circ o_3$, but the composition is computationally expensive, then $o$ may be preferable even if it is derivable.

This suggests that minimality should account for **cost**, not just cardinality.

But **we should not introduce a formal cost metric yet**. That is a later research question. For now:

$$
\boxed{
\text{Minimality is defined without cost; cost is a refinement.}
}
$$

---

# Part G — Derived Results

**R1 (KnowledgeOS minimality).** A generating set $\Omega$ for $\Sigma_{\mathrm{adm}}^\Pi$ is KnowledgeOS-minimal if it is irredundant and closed under $\equiv_\Pi$.

**R2 (Non-uniqueness).** The set of minimal generating sets $\mathcal{M}^\Pi$ may contain multiple incomparable elements.

**R3 (Relative minimality).** Minimality is relative to $\Pi$ and its congruence.

**R4 (Design choice).** The choice of a particular minimal basis is a design decision, not a mathematical necessity.

**R5 (Falsification protocol).** A proposed basis $\Omega$ is falsified if either (a) it fails to generate $\Sigma_{\mathrm{adm}}^\Pi$, or (b) a proper subset also generates.

---

# Part H — Architectural Consequences

## H.1 DDD (only after math)

Aggregate boundaries depend on the chosen minimal basis. Different bases yield different boundaries. This is a **design decision** constrained by mathematics.

## H.2 Kernel

The Kernel is the **final reduction** of the chosen minimal basis under observable semantics. It is not yet derivable.

---

# Part I — Status Update

| Concept | Status |
|---|---|
| Required distinctions | Derived |
| Observational quotient | Derived |
| Operation equivalence | Derived |
| Derivability | Derived |
| Primitivity | Derived |
| Congruence characterization | Derived |
| **KnowledgeOS minimality** | **Derived (Q-D4.5.12.d)** |
| **Non-uniqueness of minimal basis** | **Derived (Q-D4.5.12.d)** |
| **Relative minimality** | **Derived (Q-D4.5.12.d)** |
| Minimal operation basis for Nexus | Open |
| Minimal representation | Open |
| Kernel | Open |

---

# Part J — The Next Question

Now that minimality is formalized, the next question is:

$$
\boxed{
\textbf{Q-D4.5.12.e — What is a minimal generating set for the Nexus admissible fragment?}
}
$$

More precisely:

> Given the Nexus problem specification $\Pi_{\text{Nexus}}$, the admissible operation set $\Sigma_{\mathrm{adm}}^{\text{Nexus}}$, and the congruence $\equiv_{\text{Nexus}}$, derive at least one minimal generating set $\Omega_{\mathrm{req}}^{\text{Nexus}} \in \mathcal{M}^{\text{Nexus}}$.

This must come after minimality is formalized, because the derivation requires a criterion for "minimal."

---

# Part K — Reflection

## K.1 What has been achieved

1. **Minimality** is now formalized for the KnowledgeOS setting.
2. **Non-uniqueness** of minimal bases is established.
3. **Relativity** of minimality is established.
4. **Falsification protocol** is established.

## K.2 What this changes

Previously, the programme assumed that minimization was a well-defined problem with a unique solution. It is not — the solution is a **family** of minimal bases, and the choice among them is a design decision.

## K.3 What remains

The programme still needs:

- A specific minimal basis for Nexus
- The minimal representation
- The Kernel
- DDD boundaries

These are downstream of the minimality formalization.

## K.4 Final statement

$$
\boxed{
\begin{aligned}
&\text{Minimality is relative to } \Pi. \\
&\text{There is no unique minimal basis.} \\
&\text{The choice is a design decision.}
\end{aligned}
}
$$

This is the central result of Q-D4.5.12.d.

The next question is Q-D4.5.12.e: derive a minimal generating set for the Nexus admissible fragment.