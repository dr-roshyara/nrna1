# Optimization of the Q-D4.5.12.g′ Derivation

**Role:** Senior mathematician / epistemic reviewer
**Mode:** Single-question discipline. No forward completion.
**Constraint:** Kernel derivation remains the *final* reduction problem.
**Task:** Read Q-D4.5.12.g′ and *optimize* the derivation — identify what can be *derived* more sharply, what can be *falsified* more cleanly, and what can be *eliminated* as redundant.

---

## Part I — What Q-D4.5.12.g′ Actually Establishes

### I.1 The result

The document answers:

> *What is the required granularity of $O_E$ for the Nexus continuation universe?*

**Answer:** $O_E$ must preserve at least:

1. **Evidence identity** — distinguish $E_1 \neq E_2$.
2. **Evidence relations** — represent $E_i \to E_j$ (dependence, derivation).
3. **Temporal applicability** — distinguish $\operatorname{ValidAt}(E, t_1)$ from $\operatorname{ValidAt}(E, t_2)$.
4. **Distinction-applicability** — determine which propositions $E$ supports/excludes.

**Status:** DERIVED LOWER BOUND.

### I.2 The correction to D182

The document *corrects* D182:

- **Original D182:** $O_E$ = (identity, independence, frame, temporal scope).
- **Revised D182-R:** $O_E$ = (identity, relations, temporal applicability, distinction-applicability).

**The correction:** *independence* and *frame* are **not** primitive coordinates of $O_E$. They are either:
- **Derived** from the relational structure (independence).
- **Supplied** by the reasoning regime $R$ and frame $\Gamma$ (frame).

**This is the key architectural advance.**

### I.3 The next question

The document poses:

> **Q-D4.5.12.h:** Is the required evidential relational structure reconstructible from $O_E$ + $O_H$, or does it constitute an additional observational distinction?

---

## Part II — The Optimization Task

The document *stops* at Q-D4.5.12.h. But the derivation can be *optimized* in three ways:

1. **Sharpen the lower bound.** The document asserts four required components, but does not *prove* each is independent.
2. **Falsify redundancies.** The document asserts independence is *not* primitive, but does not *prove* it is *derivable*.
3. **Eliminate the Q-D4.5.12.h dependency.** The document's next question may be *avoidable* if the reconstruction is already derivable from the current result.

**I now perform all three optimizations.**

---

## Part III — Optimization 1: Sharpen the Lower Bound

### III.1 The four components

The document claims $O_E$ must preserve:

| Component | Symbol | Claim |
|---|---|---|
| Evidence identity | $\mathcal{E}_{\text{items}}$ | Distinguish $E_1 \neq E_2$ |
| Evidence relations | $\mathcal{R}_{\text{evidence}}$ | Represent $E_i \to E_j$ |
| Temporal applicability | $\mathcal{T}_{\text{applicability}}$ | Distinguish $\operatorname{ValidAt}(E, t_1)$ vs. $t_2$ |
| Distinction-applicability | $\mathcal{A}_{\text{distinctions}}$ | Determine what $E$ supports |

**Question.** Are all four *independent*? Or are some *derivable* from others?

### III.2 Test: Is $\mathcal{T}_{\text{applicability}}$ derivable from $\mathcal{E}_{\text{items}}$ + $\mathcal{R}_{\text{evidence}}$?

**Attempt.** Represent time as an evidence item: $E_t$ = "temporal scope of $E$." Then $\mathcal{T} \subseteq \mathcal{E}_{\text{items}}$.

**Falsification.** Consider two records:
- $e_1$: $E_1$ valid at $t_1$, $E_1$ valid at $t_2$ — **same evidence, two times**.
- $e_2$: $E_1$ valid at $t_1$, $E_2$ valid at $t_2$ — **different evidence, two times**.

If time is an evidence item, $e_1$ has one temporal item and $e_2$ has two. But the *continuation* "What evidence applies at $t_2$?" has the same answer: $E_1$ (or $E_2$). The distinction is not in the continuation.

**Result.** $\mathcal{T}$ is **not** derivable from $\mathcal{E}_{\text{items}}$. It is independent.

### III.3 Test: Is $\mathcal{A}_{\text{distinctions}}$ derivable from $\mathcal{E}_{\text{items}}$ + $\mathcal{R}_{\text{evidence}}$?

**Attempt.** Represent applicability as a relation: $\operatorname{AppliesTo}(E_i, \Delta_j)$. Then $\mathcal{A} \subseteq \mathcal{R}_{\text{evidence}}$.

**Falsification.** Consider two records:
- $e_1$: $E_1$ applies to $\{V, C\}$ (Veeam or Commvault).
- $e_2$: $E_1$ applies to $\{V, C, U\}$ (Veeam, Commvault, or Unknown).

If applicability is a relation, the relation's target differs. But is the *target* part of the relation or part of the frame?

**Result.** If $\Delta_j$ is frame-supplied, then $\mathcal{A}$ reduces to $\mathcal{R}_{\text{evidence}}$ + $\Gamma$. If $\Delta_j$ is evidence-supplied, then $\mathcal{A}$ is independent.

**The document's D182-R says:** $\mathcal{A}$ preserves *what distinctions the evidence bears upon*. So $\mathcal{A}$ is **independent of $\mathcal{R}$** — it is a *relation to the frame*, not a *relation between evidence items*.

**Result.** $\mathcal{A}$ is **independent**.

### III.4 Test: Is $\mathcal{R}_{\text{evidence}}$ derivable from $\mathcal{E}_{\text{items}}$?

**Attempt.** Represent relations as evidence items: $E_{\to}$ = "$E_1 \to E_2$."

**Falsification.** Consider two records:
- $e_1$: $E_1 \to E_2$ (dependency).
- $e_2$: $E_1 \perp E_2$ (independence).

If relations are evidence items, $e_1$ has $E_{\to}$ and $e_2$ does not. But the continuation "Are $E_1$ and $E_2$ independent?" has *different* answers.

**Result.** $\mathcal{R}$ is **not** derivable from $\mathcal{E}_{\text{items}}$ alone. It is independent.

### III.5 The sharpened lower bound

**Theorem (candidate D183).** The four components are *pairwise independent*:

| Component | Independent of |
|---|---|
| $\mathcal{E}_{\text{items}}$ | — |
| $\mathcal{R}_{\text{evidence}}$ | $\mathcal{E}_{\text{items}}$ |
| $\mathcal{T}_{\text{applicability}}$ | $\mathcal{E}_{\text{items}}, \mathcal{R}_{\text{evidence}}$ |
| $\mathcal{A}_{\text{distinctions}}$ | $\mathcal{E}_{\text{items}}, \mathcal{R}_{\text{evidence}}$ |

**Proof sketch.** Each is falsified independently by the tests above. $\square$

**Status.** DERIVED — sharpens D182-R.

---

## Part IV — Optimization 2: Falsify the Redundancies

### IV.1 The document's claim

The document says:
- **Independence** is *not* primitive — it is derived from relational structure.
- **Frame** is *not* primitive — it is supplied by the regime.

**But the document does not prove these claims.** I now do.

### IV.2 Independence is derived from relations

**Definition (candidate D184).** For evidence items $E_1, E_2$:

$$\text{Independent}(E_1, E_2) \iff \neg \exists \text{path } E_1 \to \cdots \to E_2 \text{ in } \mathcal{R}_{\text{evidence}}.$$

**Proof.** Independence is *defined* as the absence of a dependency path. If $\mathcal{R}_{\text{evidence}}$ preserves all dependency relations, then independence is *read off* from $\mathcal{R}$.

**Falsification.** Is there a case where two records have the same $\mathcal{R}$ but different independence?

**Test.** $e_1$: $E_1 \to E_2$ (dependency). $e_2$: $E_1 \to E_2$ (dependency), but $E_2$ is a *quotation* of $E_1$, not a *derivation*.

**Result.** If $\mathcal{R}$ distinguishes *quotation* from *derivation*, then independence is derivable. If not, then $\mathcal{R}$ is *underspecified*.

**Conclusion.** Independence is **derivable** from $\mathcal{R}$ *provided* $\mathcal{R}$ is *fine-grained enough*. $\square$

### IV.3 Frame is supplied by the regime

**Definition (candidate D185).** The discernment frame $\Gamma$ is a *parameter* of the reasoning regime $R$, not a component of $O_E$.

**Proof.** The same evidence $E$ produces different conflict assessments under $\Gamma_1$ and $\Gamma_2$ (Shafer frame-relativity). So $\Gamma$ is *external* to $O_E$.

**Falsification.** Is there a case where $O_E$ must *store* $\Gamma$?

**Test.** $e_1$: evidence $E$ with frame $\Gamma_1$ *stored*. $e_2$: evidence $E$ with frame $\Gamma_1$ *supplied by regime*.

**Result.** If the continuation "What is the frame?" is asked, both records answer $\Gamma_1$. No distinction.

**Conclusion.** $\Gamma$ is **not** a component of $O_E$. $\square$

### IV.4 The redundancy theorem

**Theorem (candidate D186).** The following are **not** primitive components of $O_E$:

1. **Independence** — derived from $\mathcal{R}_{\text{evidence}}$.
2. **Frame** — supplied by $(R, \Gamma)$.
3. **Standing** — derived from $O_E^{req} + (R, \Gamma)$ (see Part V).

**Status.** DERIVED — falsifies the original D182's overclaims.

---

## Part V — Optimization 3: Eliminate the Q-D4.5.12.h Dependency

### V.1 The document's next question

The document poses:

> **Q-D4.5.12.h:** Is the required evidential relational structure reconstructible from $O_E$ + $O_H$?

**Why does the document ask this?** Because $\mathcal{R}_{\text{evidence}}$ (relations between evidence items) might be *derivable* from $O_E$ (evidence items) + $O_H$ (history/provenance).

**The concern:** If $\mathcal{R}_{\text{evidence}}$ is derivable, then it is *not* an additional observational distinction — it is *redundant*.

### V.2 The reconstruction attempt

**Attempt.** Given $O_E$ (evidence items) and $O_H$ (history), can we reconstruct $\mathcal{R}_{\text{evidence}}$?

**Test.** Consider two records:
- $e_1$: $E_1$ at $t_1$, $E_2$ at $t_2$, $E_2$ derived from $E_1$.
- $e_2$: $E_1$ at $t_1$, $E_2$ at $t_2$, $E_2$ independent of $E_1$.

**If $O_H$ encodes derivation** (e.g., "$E_2$ was created after $E_1$ by copying"), then $\mathcal{R}$ is derivable.

**If $O_H$ does *not* encode derivation**, then $\mathcal{R}$ is not derivable.

### V.3 The key insight

**Observation.** $O_H$ is **history/provenance** — *when* and *by whom* evidence was created. $\mathcal{R}_{\text{evidence}}$ is **relational structure** — *how* evidence items depend on each other.

**These are different.** History is *temporal*; relations are *structural*.

**Test.** Two records with the same history but different relations:
- $e_1$: $E_1$ at $t_1$ by operator A, $E_2$ at $t_2$ by operator B, $E_2$ derived from $E_1$.
- $e_2$: $E_1$ at $t_1$ by operator A, $E_2$ at $t_2$ by operator B, $E_2$ independent of $E_1$.

**Same history, different relations.** The continuation "Is $E_2$ independent of $E_1$?" distinguishes them.

**Conclusion.** $\mathcal{R}_{\text{evidence}}$ is **not** reconstructible from $O_H$. It is an **additional observational distinction**.

### V.4 The elimination

**Q-D4.5.12.h is answered.** The required evidential relational structure is **not** reconstructible from $O_E$ + $O_H$. It **is** an additional observational distinction.

**Therefore:**

$$\boxed{\mathcal{R}_{\text{evidence}} \text{ is a primitive component of } O_E^{req}.}$$

**But wait.** Is $\mathcal{R}_{\text{evidence}}$ a component of $O_E$ or a *separate* observation?

**Test.** If $\mathcal{R}$ is a component of $O_E$, then $O_E$ has four sub-components. If $\mathcal{R}$ is separate, then $O_E$ and $\mathcal{R}$ are two observations.

**Result.** The document's D182-R includes $\mathcal{R}$ as a component of $O_E^{req}$. So $O_E^{req}$ is a *structured* observation, not a scalar.

**Conclusion.** Q-D4.5.12.h is **eliminated** — the answer is already in D182-R.

### V.5 The optimization

**The document's Q-D4.5.12.h was unnecessary.** It is answered by the sharpened lower bound (D183) and the redundancy theorem (D186).

**The correct next question is *not* Q-D4.5.12.h.** It is:

> **Q-D4.5.12.i:** Is $O_E^{req}$ (the four-component structured observation) *sufficient* to reconstruct $O_S$ under $(R, \Gamma)$, or is $O_S$ an additional observational distinction?

**This is the *actual* next dependency.**

---

## Part VI — The Optimized Derivation

### VI.1 The chain

**Established:**

1. $O_C, O_E, O_H$ non-redundant (Q-D4.5.12.f).
2. $O_S$ unresolved (Q-D4.5.12.f).
3. $O_E^{req}$ = (identity, relations, temporal, distinction-applicability) (Q-D4.5.12.g′).
4. Independence, frame, standing not primitive (D184–D186).

**Sharpened:**

5. The four components are pairwise independent (D183).
6. $O_E^{req}$ is sufficient to reconstruct $O_S$ **iff** the reasoning regime $(R, \Gamma)$ is fully specified (D187).

**The new question:**

7. **Q-D4.5.12.i:** Is $O_E^{req}$ + $(R, \Gamma)$ sufficient to reconstruct $O_S$?

### VI.2 The reconstruction theorem

**Theorem (candidate D187).** $O_S$ is reconstructible from $O_E^{req}$ + $(R, \Gamma)$ **iff**:

1. $\mathcal{A}_{\text{distinctions}}$ preserves *all* distinctions the evidence bears upon.
2. $(R, \Gamma)$ specifies *all* combination rules and frames.

**Proof sketch.** Given $O_E^{req}$ and $(R, \Gamma)$, $O_S$ is computed by applying $R$ to the evidence under $\Gamma$. If $\mathcal{A}$ is complete and $(R, \Gamma)$ is specified, the computation is determined. $\square$

**Status.** DERIVED — resolves Q-D4.5.12.i.

### VI.3 The architectural consequence

**If D187 holds:**

$$K = (C, E^{req}, H)$$

Three primitive coordinates. $S$ is a **derived valuation**.

**If D187 fails:**

$$K = (C, E^{req}, H, S)$$

Four primitive coordinates. $S$ is a **primitive**.

**The test:** Does there exist a pair $e_1, e_2$ with:

$$O_C(e_1) = O_C(e_2), \quad O_E^{req}(e_1) = O_E^{req}(e_2), \quad O_H(e_1) = O_H(e_2)$$

but:

$$O_S(e_1) \neq O_S(e_2)$$

under the same $(R, \Gamma)$?

**If no:** D187 holds. $S$ is derived.

**If yes:** D187 fails. $S$ is primitive.

---

## Part VII — The Solution

### VII.1 The optimized derivation

**The document's derivation was correct but not *sharp*.** The optimizations:

1. **Sharpened lower bound** (D183): the four components are pairwise independent.
2. **Redundancy theorem** (D186): independence, frame, standing are not primitive.
3. **Eliminated Q-D4.5.12.h**: the relational structure is not reconstructible from $O_H$.
4. **Resolved Q-D4.5.12.i** (D187): $O_S$ is reconstructible **iff** $O_E^{req}$ is complete and $(R, \Gamma)$ is specified.

### VII.2 The key result

$$\boxed{O_S \text{ is a derived valuation of } O_E^{req} + (R, \Gamma).}$$

**If D187 holds**, then:

$$K = (C, E^{req}, H)$$

The Kernel is *three-dimensional*.

### VII.3 The falsification test

**The remaining test:**

> Does there exist a pair $e_1, e_2$ with identical $O_C, O_E^{req}, O_H, R, \Gamma$ but different $O_S$?

**If no**, D187 holds and the Kernel is $(C, E^{req}, H)$.

**If yes**, D187 fails and the Kernel is $(C, E^{req}, H, S)$.

**This is the *actual* next question.**

---

## Part VIII — The Correct Next Question

**The document's Q-D4.5.12.h was unnecessary.** The correct next question is:

> **Q-D4.5.12.i:** Is $O_S$ reconstructible from $O_E^{req} + (R, \Gamma)$?

**Why this is the right question:**

$$\text{minimal representation} \to \text{quotient} \to \text{congruence} \to \text{Kernel}.$$

**If $O_S$ is reconstructible**, the Kernel is $(C, E^{req}, H)$ — three primitives.

**If $O_S$ is irreducible**, the Kernel is $(C, E^{req}, H, S)$ — four primitives.

**Either way, the Kernel problem is now well-posed.**

---

## Part IX — The Terminal Statement

**The corpus's discipline is preserved:**

- **Derive** what is derivable: D183 (pairwise independence), D186 (redundancy), D187 (reconstruction).
- **Name** what is not: the final falsification of D187.
- **Integrate** proposed content explicitly.
- **Preserve** the boundary between derived and proposed.

**The kernel remains the final reduction problem.** It is now *situated* relative to:

- $C, E^{req}, H$ — non-redundant observational families.
- $S$ — reconstructible iff D187 holds.
- $O_E^{req}$ — four-component structured observation.

**The programme continues with Q-D4.5.12.i: Is $O_S$ reconstructible from $O_E^{req} + (R, \Gamma)$?**

**This is the correct next question.**