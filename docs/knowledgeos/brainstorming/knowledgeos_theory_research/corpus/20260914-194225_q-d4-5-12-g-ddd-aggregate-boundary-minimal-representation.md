# KnowledgeOS Research Programme — Q-D4.5.12.g

## What is the DDD aggregate boundary for the minimal representation?

I proceed as senior mathematician, statistician, DDD architect, and epistemic reviewer. I answer only this question. I use proof and falsification. I use the Nexus Repository example throughout. I apply DDD **only after** the mathematical meaning is clear. I maintain explicit architectural levels. Kernel derivation remains the final reduction problem.

---

# Part A — Precondition Check

Q-D4.5.12.g requires:

1. The minimal representation $K_{\min}^{\text{tested}} = (P, S, R, O)$ (derived in Q-D4.5.12.f)
2. The minimal basis $\Omega_{\text{req}}^{\text{tested}}$ (derived in Q-D4.5.12.e)
3. The tested continuation set $\mathcal{C}^{\text{tested}}$
4. The congruence $\equiv_{\text{Nexus}}$ on operations

All four are available. I proceed.

However, a **critical clarification** is required before the investigation.

---

# Part B — What "DDD Aggregate Boundary" Means Mathematically

## B.1 The danger of importing DDD terminology prematurely

DDD terminology ("aggregate", "entity", "value object", "invariant", "consistency boundary") is **domain design vocabulary**. It is not mathematical. If we import it before the mathematics is settled, we will either:

- **Over-constrain** the math (by imposing boundaries not derivable from the structure), or
- **Under-specify** the math (by leaving the boundary implicit).

This is precisely the trap the programme's rule warns against:

$$
\text{Mathematical distinction} \rightarrow \text{semantic concept} \rightarrow \text{domain boundary} \rightarrow \text{aggregate}
$$

## B.2 The mathematical precursor of the aggregate boundary

The DDD aggregate boundary has a **mathematical precursor**:

> **Consistency closure under operations.**

That is, given a state $K_{\min} = (P, S, R, O)$ and an operation $o \in \Omega_{\text{req}}^{\text{tested}}$, the operation $o$ may modify **several components at once**. The **minimal set of components that must be modified atomically** by $o$ is the mathematical precursor of the aggregate boundary.

## B.3 The mathematical formalization

Let $K = (P, S, R, O)$. Define the **footprint** of an operation $o$:

$$
\mathsf{Foot}(o) \subseteq \{P, S, R, O\}
$$

as the set of components $o$ reads or writes.

The **consistency requirement** is:

> If $o$ modifies multiple components, they must be modified **atomically** — no admissible continuation can observe an intermediate state.

## B.4 The mathematical definition

**Definition (Atomic footprint).** An operation $o$ has **atomic footprint** $F \subseteq \{P, S, R, O\}$ if:

1. $o$ modifies only components in $F$.
2. Every admissible continuation observes either the **pre-state** or the **post-state** of $o$, never an intermediate state.

**Definition (Aggregate candidate).** The **aggregate candidates** for $K$ are the **minimal subsets** $F \subseteq \{P, S, R, O\}$ such that every operation in $\Omega_{\text{req}}^{\text{tested}}$ has atomic footprint within $F$.

**Theorem (Boundary existence).** If $\Omega_{\text{req}}^{\text{tested}}$ is finite and each operation has a finite footprint, then the set of aggregate candidates is non-empty.

---

# Part C — Deriving the Footprints

## C.1 $\texttt{Assert}(p)$

**Reads:** $P$ (to check for duplicates), $O$ (to record history)

**Writes:** $P$ (adds $p$), $S$ (sets $S(p) = \text{asserted}$), $O$ (records the operation)

**Footprint:** $\{P, S, O\}$

## C.2 $\texttt{Link}(p, r, q)$

**Reads:** $P$ (checks $p, q$ exist), $O$

**Writes:** $R$ (adds relation), $O$

**Footprint:** $\{R, O\}$

## C.3 $\texttt{Retract}(p)$

**Reads:** $P$ (checks $p$ exists), $S$ (checks current standing), $O$

**Writes:** $S$ (sets $S(p) = \text{retracted}$), $O$

**Footprint:** $\{S, O\}$

## C.4 $\texttt{Supersede}(p_1, p_2)$

**Reads:** $P$ (checks both exist), $S$ (checks current standing), $O$

**Writes:** $S$ (sets $S(p_1) = \text{superseded}$), $R$ (adds supersession relation), $O$

**Footprint:** $\{S, R, O\}$

## C.5 $\texttt{Merge}(K_1, K_2)$

**Reads:** $P_1, S_1, R_1, O_1$ and $P_2, S_2, R_2, O_2$

**Writes:** $P_1 \cup P_2$, $S_1 \cup S_2$, $R_1 \cup R_2$, $O_1 \cup O_2$

**Footprint:** $\{P, S, R, O\}$

## C.6 Summary of footprints

| Operation | Footprint |
|---|---|
| `Assert` | $\{P, S, O\}$ |
| `Link` | $\{R, O\}$ |
| `Retract` | $\{S, O\}$ |
| `Supersede` | $\{S, R, O\}$ |
| `Merge` | $\{P, S, R, O\}$ |

---

# Part D — Computing Aggregate Candidates

## D.1 The coverage condition

Every operation's footprint must be contained in some aggregate candidate. This is the **coverage condition**.

## D.2 The atomicity condition

If two components appear together in the footprint of some operation, they must appear together in every aggregate candidate covering that operation. This is the **atomicity condition**.

## D.3 Computing the candidates

**From `Merge`:** The footprint is $\{P, S, R, O\}$. Therefore **any aggregate candidate covering `Merge` must contain all four components**.

**From `Assert`:** The footprint is $\{P, S, O\}$. Therefore any aggregate candidate covering `Assert` must contain $\{P, S, O\}$.

**From `Link`:** The footprint is $\{R, O\}$. Therefore any aggregate candidate covering `Link` must contain $\{R, O\}$.

**From `Retract`:** The footprint is $\{S, O\}$. Therefore any aggregate candidate covering `Retract` must contain $\{S, O\}$.

**From `Supersede`:** The footprint is $\{S, R, O\}$. Therefore any aggregate candidate covering `Supersede` must contain $\{S, R, O\}$.

## D.4 The minimal aggregate candidate

Since `Merge` requires all four components $\{P, S, R, O\}$ to be in the same aggregate candidate, the **only candidate** is:

$$
F_{\text{aggregate}}^{\text{tested}} = \{P, S, R, O\}
$$

## D.5 Conclusion

The aggregate boundary for the tested Nexus fragment is:

$$
\boxed{
K_{\min}^{\text{tested}} = (P, S, R, O) \text{ is a single aggregate.}
}
$$

There is **no proper decomposition** of $K_{\min}^{\text{tested}}$ into smaller aggregates, because `Merge` requires all components to be atomically modifiable together.

---

# Part E — Verification

## E.1 Coverage

Every operation's footprint is contained in $\{P, S, R, O\}$. **Coverage holds.**

## E.2 Atomicity

If $o$ modifies $\{P, S, R, O\}$ (like `Merge`), the whole set must be atomic. **Atomicity holds.**

## E.3 Minimality

No proper subset of $\{P, S, R, O\}$ covers all operations, because `Merge` requires all four. **Minimality holds.**

## E.4 Congruence

If $K_1, K_2$ are represented by the same $(P, S, R, O)$, they are equivalent under $\equiv_{\text{Nexus}}$. **Congruence holds.**

---

# Part F — Falsification Tests

## F.1 Falsifier 1: Removal of `Merge`

If `Merge` is removed from $\Omega_{\text{req}}^{\text{tested}}$, the aggregate boundary **shrinks**. Specifically:

- Without `Merge`, the maximal footprint is $\{S, R, O\}$ (from `Supersede`).
- Aggregate candidate: $\{S, R, O\}$ plus any component that must be atomic with $S$.

**Testing without `Merge`:**

| Operation | Footprint |
|---|---|
| `Assert` | $\{P, S, O\}$ |
| `Link` | $\{R, O\}$ |
| `Retract` | $\{S, O\}$ |
| `Supersede` | $\{S, R, O\}$ |

**Coverage:** Every operation covered.

**Atomicity:** `Assert` requires $\{P, S, O\}$ atomic. `Supersede` requires $\{S, R, O\}$ atomic. Union: $\{P, S, R, O\}$.

**Therefore: even without `Merge`, the aggregate boundary is still $\{P, S, R, O\}$.**

**Falsifier 1 fails.**

## F.2 Falsifier 2: Change of representation

If $K_{\min}^{\text{tested}}$ is redefined (e.g., $S$ is folded into $P$, or $O$ is removed), the aggregate boundary may change.

**Re-representing $O$ as a relation in $R$:**

Suppose we redefine $O$ as a relation $(p, \text{OperationHistory}, o) \in R$. Then $K_{\min}' = (P, S, R)$.

Footprints:

| Operation | Footprint |
|---|---|
| `Assert` | $\{P, S, R\}$ |
| `Link` | $\{R\}$ |
| `Retract` | $\{S, R\}$ |
| `Supersede` | $\{S, R\}$ |
| `Merge` | $\{P, S, R\}$ |

**Aggregate candidate:** $\{P, S, R\}$ (single aggregate). **No smaller decomposition.**

**Falsifier 2 fails.**

## F.3 Falsifier 3: New operation with narrow footprint

If a new operation requires only, say, $P$ (read-only), it does not change the boundary. But if a new operation requires only $P$ and $O$ (excluding $S, R$), it might suggest a smaller aggregate — **but only if all operations with that footprint can be isolated**.

**Candidate falsifier:** $\texttt{Query}(p)$ — read-only, footprint $\{P\}$.

Read-only operations do not force atomicity. They can be added without changing the aggregate boundary. **Falsifier 3 fails.**

## F.4 Falsifier 4: New operation with a strict subset footprint

**Candidate falsifier:** $\texttt{Annotate}(p, note)$ — footprint $\{P, O\}$ (adds a proposition, records history). Does **not** modify $S$ or $R$.

**Testing:** With `Annotate`, we have footprints $\{P, O\}$ and $\{R, O\}$. Could we aggregate $\{P, O\}$ separately from $\{R\}$?

**Answer:** No, because `Link` requires $\{R, O\}$ atomic. So $O$ is needed by both. And `Assert` requires $\{P, S, O\}$, so $S$ is needed by $\{P, O\}$. Union is still $\{P, S, R, O\}$.

**Falsifier 4 fails.**

## F.5 Summary of falsification

The aggregate boundary $F_{\text{aggregate}}^{\text{tested}} = \{P, S, R, O\}$ is **robust** under the tested fragment. It fails only if:

1. All operations with wide footprints are removed.
2. The representation is radically changed.
3. New operations permit smaller atomic groupings.

None of these are currently the case.

---

# Part G — Mathematical Interpretation

## G.1 The aggregate boundary is a closure

The aggregate boundary $F_{\text{aggregate}}^{\text{tested}}$ is the **closure** of the union of all operation footprints under the atomicity condition. Formally:

$$
F_{\text{aggregate}} = \bigcup_{o \in \Omega_{\text{req}}} \mathsf{Foot}(o)
$$

when the union is coherent (all components can be atomically modified together). In the tested Nexus fragment, it is coherent.

## G.2 The aggregate boundary is not always a single aggregate

In the tested fragment, the boundary is $\{P, S, R, O\}$ — a **single aggregate**. But this is a **contingent** result, not a universal one. If a future domain had:

- A read-only component $X$
- Operations on $\{P, S\}$ and $\{R, O\}$ separately
- No operation crossing the boundary

Then $K_{\min} = (P, S, R, O, X)$ would decompose into two aggregates:

$$
A_1 = (P, S), \quad A_2 = (R, O)
$$

**The single-aggregate result is specific to Nexus.**

## G.3 The reconstruction interpretation

The aggregate boundary is the **minimal closure** of the operation footprints. It is not a design choice — it is **derived** from the operations. The only design choice is the **representation** $K_{\min}$, which was already derived in Q-D4.5.12.f.

## G.4 Connection to the book

The book's reconstruction lemmas (A.1, A.2) have the structure:

> Functional $g$ uniquely determines object $f$.

In KnowledgeOS:

> Operations $\Omega_{\text{req}}$ uniquely determine aggregate boundary $F_{\text{aggregate}}$.

This is the same reconstruction structure: the operations are the functional; the aggregate boundary is the reconstructed object.

---

# Part H — Nexus Worked Example

## H.1 Scenario

Nexus migration readiness. Consider the workflow:

$$
K \xrightarrow{\texttt{Assert}(v = 3.69)} K_1
\xrightarrow{\texttt{Link}(v, \text{source}, e_1)} K_2
\xrightarrow{\texttt{Retract}(v = 3.68)} K_3
\xrightarrow{\texttt{Supersede}(v = 3.68, v = 3.69)} K_4
$$

## H.2 Atomicity of each operation

**`Assert`:** Requires $P, S, O$ to be modified atomically. An intermediate state where $p \in P$ but $S(p)$ is undefined would be observable by `Retract`. **Atomic.**

**`Link`:** Requires $R, O$ to be modified atomically. An intermediate state where $r \in R$ but $O$ is missing the link operation would be observable by $c_{\text{op}}$. **Atomic.**

**`Retract`:** Requires $S, O$ to be modified atomically. An intermediate state where $S(p) = \text{retracted}$ but $O$ is missing would be observable by $c_{\text{op}}$. **Atomic.**

**`Supersede`:** Requires $S, R, O$ to be modified atomically. An intermediate state where $S(p_1) = \text{superseded}$ but $R$ is missing the supersession relation would be observable by $c_{\text{prov}}$. **Atomic.**

## H.3 The aggregate

All operations require atomic modifications across $\{P, S, R, O\}$. Therefore the aggregate boundary is the full set.

$$
F_{\text{aggregate}}^{\text{Nexus}} = \{P, S, R, O\}
$$

## H.4 DDD interpretation

The DDD aggregate is:

$$
\text{Aggregate}: K_{\min}^{\text{tested}} = (P, S, R, O)
$$

**Transactional boundary:** Every operation in $\Omega_{\text{req}}^{\text{tested}}$ must be applied transactionally to the entire aggregate.

**Invariants:**
- $S(p)$ is defined for every $p \in P$
- $R$ is consistent with $S$
- $O$ records every operation

**Identity:** Each state $K$ is identified by its content $(P, S, R, O)$.

**Lifecycle:** States are created by operations in $\Omega_{\text{req}}^{\text{tested}}$, combined by `Merge`, and never deleted (history is preserved).

---

# Part I — Derived Results

**R1 (Aggregate boundary for tested Nexus fragment).**

$$
\boxed{
F_{\text{aggregate}}^{\text{tested}} = \{P, S, R, O\}
}
$$

The minimal representation is a **single aggregate**.

**R2 (Derivation principle).** The aggregate boundary is the **closure** of the union of all operation footprints:

$$
F_{\text{aggregate}} = \bigcup_{o \in \Omega_{\text{req}}} \mathsf{Foot}(o)
$$

**R3 (Single-aggregate condition).** The single-aggregate result is **contingent** on `Merge` (or any operation with footprint $\{P, S, R, O\}$) being in the basis.

**R4 (Falsification conditions).** The aggregate boundary is falsified by:

1. Removal of all wide-footprint operations
2. Radical change of $K_{\min}$
3. New operations permitting smaller atomic groupings

**R5 (DDD interpretation).** In DDD terms, $K_{\min}^{\text{tested}}$ is a single aggregate with:

- Transactional boundary: the whole aggregate
- Invariants: standing-well-defined, relation-consistency, history-preservation
- Identity: content
- Lifecycle: created by operations, never deleted

---

# Part J — Status Update

| Concept | Status |
|---|---|
| Required distinctions | Derived |
| Observational quotient | Derived |
| Operation equivalence | Derived |
| Congruence | Derived |
| Minimal basis | Derived (tested) |
| Minimal representation | Derived (tested) |
| **Aggregate boundary** | **Derived (tested)** |
| **DDD interpretation** | **Derived (tested)** |
| Full Nexus specification | Open |
| Kernel | Open |

---

# Part K — The Next Question

The aggregate boundary is derived for the tested Nexus fragment. The DDD interpretation follows. But the **Kernel** remains the final reduction problem.

The next question is:

$$
\boxed{
\textbf{Q-D4.5.12.h — What is the Kernel for the tested Nexus fragment?}
}
$$

More precisely:

> Given the minimal representation $K_{\min}^{\text{tested}} = (P, S, R, O)$ and the aggregate boundary $F_{\text{aggregate}}^{\text{tested}} = \{P, S, R, O\}$, what is the **minimal Kernel** — the smallest set of principles, distinctions, and structures that survive every valid reduction?

---

# Part L — Why Q-D4.5.12.h Is Now Correct

## L.1 The dependency chain is complete for the tested fragment

We have derived:

1. Observable equivalence
2. Operation equivalence
3. Congruence
4. Minimal basis
5. Minimal representation
6. Aggregate boundary

The Kernel is the **final reduction** of this structure. All prerequisites for the Kernel derivation are in place.

## L.2 The Kernel derivation is the final reduction problem

The Kernel must contain only what **survives every valid reduction**. Given the tested Nexus fragment, we can now ask:

- Which principles are **necessary** for the operations to be well-defined?
- Which distinctions are **preserved** under every admissible continuation?
- Which structures are **irreducible**?

## L.3 The Nexus consequence

For Nexus, the Kernel is the **minimal set of principles** that any KnowledgeOS state must satisfy:

- Every proposition must have a standing
- Every relation must be between propositions
- Every operation must be recorded in history
- Every state must be atomically modifiable

These are candidates. The Kernel derivation will test them.

## L.4 The falsification dependency

A Kernel claim must be falsifiable. Falsification requires a precise structure to test against — which is now available.

---

# Part M — Reflection

## M.1 What has been achieved

1. A **candidate aggregate boundary** for the tested Nexus fragment is derived.
2. The **derivation principle** is established: aggregate boundary = closure of operation footprints.
3. The **DDD interpretation** is given.
4. **Falsification tests** are established.
5. The **contingent nature** of the single-aggregate result is noted.

## M.2 What this changes

Previously, the aggregate boundary was **proposed** ("$K = (A, R, E)$" or similar). Now it is **derived** for the tested Nexus fragment:

$$
F_{\text{aggregate}}^{\text{tested}} = \{P, S, R, O\}
$$

## M.3 What remains

1. Kernel (final reduction)
2. Full Nexus specification (not just tested fragment)
3. Cross-domain generalization

## M.4 Final statement

$$
\boxed{
F_{\text{aggregate}}^{\text{tested}} = \{P, S, R, O\}
}
$$

**relative to the tested Nexus fragment.**

This is the central result of Q-D4.5.12.g. The next question is Q-D4.5.12.h: Kernel derivation.

---

# Part N — The Kernel Awaits

The Kernel derivation is now the highest-priority question. It requires:

- The minimal basis
- The minimal representation
- The aggregate boundary

All are available. The Kernel is the **final reduction** of the structure under observable semantics.

The Kernel derivation must:

1. Identify the **necessary principles** (those that survive every reduction)
2. Exclude the **contingent principles** (those that depend on the tested fragment)
3. Present the Kernel as a **minimal axiomatic structure**

For Nexus, the Kernel is the **minimal set of principles** that any KnowledgeOS state must satisfy to support the derived operations.

The next research step is to derive this Kernel. But that is the **final reduction problem** — and the programme is not yet there. The tested fragment is not the full Nexus specification. The Kernel may change when the full specification is derived.

**The Kernel must remain the final reduction problem.**