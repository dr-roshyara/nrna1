# KnowledgeOS Research Programme — Q-D4.5.12.e

## What is a minimal generating set for the Nexus admissible fragment?

I proceed as senior mathematician, statistician, DDD architect, and epistemic reviewer. I answer only this question. I use proof and falsification. I use the Nexus Repository example throughout. I apply DDD only after the mathematical meaning is clear. I maintain explicit architectural levels. Kernel derivation remains the final reduction problem.

---

# Part A — Precondition Check

Before deriving anything, I verify that the corpus is sufficient. Q-D4.5.12.e requires:

1. The admissible operation set $\Sigma_{\mathrm{adm}}^{\text{Nexus}}$
2. The admissible continuation set $\mathcal{C}_{\text{Nexus}}$
3. The observable semantics $\mathsf{Obs}_{\text{Nexus}}$
4. The congruence $\equiv_{\text{Nexus}}$

If any of these are incomplete, the derivation cannot be completed, and the next question changes.

### A.1 What the corpus supplies

From the established KnowledgeOS baseline:

**Admissible operations (candidates):**
$$
\Sigma_{\text{candidate}}^{\text{Nexus}} = \{\texttt{Assert}, \texttt{Link}, \texttt{Retract}, \texttt{Merge}, \texttt{Supersede}, \texttt{Isolate}, \texttt{Transition}\}
$$

**Admissible continuations (examples):**
- Version query
- Backup audit
- Inspection check
- Migration assessment
- Provenance trace
- Operation audit
- Conflict status query
- Replay
- Atomicity query

**Observable semantics:** Each continuation returns a structured output (version string, audit result, provenance record, etc.), with an equivalence relation $\equiv_{\text{Nexus}}$ on outputs.

### A.2 What the corpus does not supply

The corpus supplies **examples**, not **exhaustive specifications**. It does not give:

- A complete enumeration of all admissible continuations
- A complete enumeration of all operations
- A formal definition of $\equiv_{\text{Nexus}}$ on outputs

### A.3 The consequence

The derivation of a minimal basis is **partially possible** from the corpus: we can derive a **candidate minimal basis relative to the tested continuations**, but we cannot prove it is minimal relative to **all** admissible continuations, because we do not have them.

This is not a defect. It is a **structural property** of the KnowledgeOS programme: minimality is relative to $\Pi$, and $\Pi$ is not yet fully specified for Nexus.

### A.4 Decision

I proceed with the **tested fragment**:
$$
\Pi_{\text{Nexus}}^{\text{tested}} = (Q, \Gamma, R, \mathcal{C}_{\text{Nexus}}^{\text{tested}}, \mathsf{Obs}_{\text{Nexus}}^{\text{tested}})
$$
and derive a candidate minimal basis relative to it. The candidate is falsifiable: any new admissible continuation that distinguishes two operations we declared equivalent, or that uses a capability we declared derivable, falsifies the candidate.

---

# Part B — Set Up

## B.1 The tested admissible operation set

$$
\Sigma_{\text{adm}}^{\text{tested}} = \{\texttt{Assert}, \texttt{Link}, \texttt{Retract}, \texttt{Merge}, \texttt{Supersede}, \texttt{Isolate}, \texttt{Transition}\}
$$

## B.2 The tested continuation set

$$
\mathcal{C}^{\text{tested}} = \{c_{\text{ver}}, c_{\text{bak}}, c_{\text{insp}}, c_{\text{mig}}, c_{\text{prov}}, c_{\text{op}}, c_{\text{conf}}, c_{\text{replay}}, c_{\text{atom}}\}
$$

where each $c$ is as follows:

| $c$ | Continuation | Observes |
|---|---|---|
| $c_{\text{ver}}$ | Version query | Current version |
| $c_{\text{bak}}$ | Backup audit | Backup mechanism status |
| $c_{\text{insp}}$ | Inspection check | Whether production was inspected |
| $c_{\text{mig}}$ | Migration assessment | Readiness determination |
| $c_{\text{prov}}$ | Provenance trace | Source lineage of a claim |
| $c_{\text{op}}$ | Operation audit | Which operation produced a claim |
| $c_{\text{conf}}$ | Conflict status | Whether two claims conflict |
| $c_{\text{replay}}$ | Replay | Reproduction of state from history |
| $c_{\text{atom}}$ | Atomicity query | Whether a state was introduced atomically |

## B.3 Tested observable semantics

$\mathsf{Obs}_{\text{Nexus}}^{\text{tested}}(e, c)$ returns a structured result for each $(e, c)$. Two outputs are $\equiv_{\text{Nexus}}$-equivalent iff they agree on the fields the continuation is defined to observe.

---

# Part C — Step 1: Operation Equivalence Testing

For each pair $(o_i, o_j) \in \Sigma_{\text{adm}}^{\text{tested}} \times \Sigma_{\text{adm}}^{\text{tested}}$, test:
$$
o_i \equiv_{\text{Nexus}} o_j \iff \forall e, \forall c \in \mathcal{C}^{\text{tested}}: \mathsf{Obs}(o_i(e), c) \equiv_{\text{Nexus}} \mathsf{Obs}(o_j(e), c)
$$

## C.1 $\texttt{Assert}$ vs $\texttt{Link}$

$\texttt{Assert}(p)$ adds a proposition. $\texttt{Link}(p, r, q)$ adds a relation.

**Test $c_{\text{op}}$ (operation audit):**
$$
\mathsf{Obs}(\texttt{Assert}(p)(e), c_{\text{op}}) = \text{"Assert"}(p)
$$
$$
\mathsf{Obs}(\texttt{Link}(p, r, q)(e), c_{\text{op}}) = \text{"Link"}(p, r, q)
$$

**Different outputs.** Therefore:
$$
\texttt{Assert} \not\equiv_{\text{Nexus}} \texttt{Link}
$$

## C.2 $\texttt{Assert}$ vs $\texttt{Retract}$

$\texttt{Assert}(p)$ adds $p$. $\texttt{Retract}(p)$ removes $p$ from maintained assertions.

**Test $c_{\text{op}}$:** Different operation labels. Already distinct.

**Test $c_{\text{ver}}$ on a state asserting version 3.69:**

- $\texttt{Assert}(v = 3.69)(e)$: version $= 3.69$
- $\texttt{Retract}(v = 3.69)(e)$: version may be unknown or different

**Different outputs.** Therefore:
$$
\texttt{Assert} \not\equiv_{\text{Nexus}} \texttt{Retract}
$$

## C.3 $\texttt{Assert}$ vs $\texttt{Supersede}$

$\texttt{Assert}(p_2)$ adds $p_2$. $\texttt{Supersede}(p_1, p_2)$ adds $p_2$ and marks $p_1$ as superseded.

**Test $c_{\text{prov}}$ on $p_1$:**

- After $\texttt{Assert}(p_2)$: $p_1$ has no supersession record
- After $\texttt{Supersede}(p_1, p_2)$: $p_1$ has a supersession record

**Different outputs.** Therefore:
$$
\texttt{Assert} \not\equiv_{\text{Nexus}} \texttt{Supersede}
$$

## C.4 $\texttt{Link}$ vs $\texttt{Supersede}$

$\texttt{Link}(p_2, \text{Supersedes}, p_1)$ adds a relation. $\texttt{Supersede}(p_1, p_2)$ adds the same relation and adds semantic supersession.

**Test $c_{\text{prov}}$ on $p_1$:**

- After $\texttt{Link}(p_2, \text{Supersedes}, p_1)$: $p_1$ has a relation to $p_2$, but no supersession semantics
- After $\texttt{Supersede}(p_1, p_2)$: $p_1$ has supersession semantics

If $c_{\text{prov}}$ observes the semantic distinction, they differ. If $c_{\text{prov}}$ observes only the relation, they may coincide.

**In the tested fragment, $c_{\text{prov}}$ observes the relation only.** Therefore in the tested fragment:
$$
\texttt{Link}(p_2, \text{Supersedes}, p_1) \equiv_{\text{tested}} \texttt{Supersede}(p_1, p_2)
$$

But this is **fragile** — a stronger provenance continuation would distinguish them.

## C.5 $\texttt{Assert}$ vs $\texttt{Merge}$

$\texttt{Merge}(K_1, K_2)$ combines two states. $\texttt{Assert}$ adds a single proposition.

**Test $c_{\text{atom}}$ (atomicity query):**

- After $\texttt{Merge}$: atomic
- After a sequence of $\texttt{Assert}$: not atomic

If $c_{\text{atom}}$ is admissible, they differ. If not, they may coincide in the tested fragment.

**In the tested fragment, $c_{\text{atom}}$ is admissible.** Therefore:
$$
\texttt{Assert} \not\equiv_{\text{Nexus}} \texttt{Merge}
$$

But $\texttt{Merge}$ is **not derivable from Assert alone**; it requires the atomic composition of assertion and linking.

## C.6 $\texttt{Retract}$ vs $\texttt{Assert}(\neg p)$

This is the key falsification from Q-D4.5.12.b.

**Test $c_{\text{prov}}$:**

- After $\texttt{Retract}(p)$: history records a retraction
- After $\texttt{Assert}(\neg p)$: history records an assertion of $\neg p$

**Different outputs.** Therefore:
$$
\texttt{Retract} \not\equiv_{\text{Nexus}} \texttt{Assert}(\neg p)
$$

## C.7 $\texttt{Isolate}$ vs any other operation

$\texttt{Isolate}(p)$ marks $p$ as isolated for conflict containment.

**Test $c_{\text{conf}}$ (conflict status):**

- After $\texttt{Isolate}(p)$: $p$ is isolated
- After $\texttt{Assert}(p)$: $p$ is not isolated

**Different outputs.** Therefore $\texttt{Isolate}$ is not equivalent to `Assert` or `Link` in the tested fragment.

## C.8 $\texttt{Transition}$ vs specialized operations

$\texttt{Transition}(K, e)$ applies an event $e$. If the event algebra is rich enough, $\texttt{Transition}$ can encode $\texttt{Assert}$, $\texttt{Link}$, $\texttt{Retract}$, etc.

**In the tested fragment:** We assume the event algebra is rich enough, so:
$$
\texttt{Assert}(p) \equiv_{\text{tested}} \texttt{Transition}(K, \texttt{assert}(p))
$$
$$
\texttt{Link}(p, r, q) \equiv_{\text{tested}} \texttt{Transition}(K, \texttt{link}(p, r, q))
$$
etc.

**This is a design decision.** $\texttt{Transition}$ is a universal presentation, not a primitive.

## C.9 Summary of equivalence tests

| $o_i$ | $o_j$ | Equivalent in tested fragment? |
|---|---|---|
| $\texttt{Assert}$ | $\texttt{Link}$ | No |
| $\texttt{Assert}$ | $\texttt{Retract}$ | No |
| $\texttt{Assert}$ | $\texttt{Supersede}$ | No |
| $\texttt{Link}$ | $\texttt{Supersede}$ | Yes (via $\texttt{Link}(p_2, \texttt{Supersedes}, p_1)$) |
| $\texttt{Assert}$ | $\texttt{Merge}$ | No |
| $\texttt{Retract}$ | $\texttt{Assert}(\neg p)$ | No |
| $\texttt{Isolate}$ | others | No |
| $\texttt{Transition}$ | specialized | Yes (by event encoding) |

---

# Part D — Step 2: Derivability Testing

For each $o \in \Sigma_{\text{adm}}^{\text{tested}}$, test whether $o$ is derivable from $\Sigma_{\text{adm}}^{\text{tested}} \setminus \{o\}$.

## D.1 $\texttt{Supersede}$

**Claim:** $\texttt{Supersede}(p_1, p_2) \equiv_{\text{tested}} \texttt{Assert}(p_2) \circ \texttt{Link}(p_2, \texttt{Supersedes}, p_1)$.

**Verification:** Test all $c \in \mathcal{C}^{\text{tested}}$.

- $c_{\text{ver}}$: both produce version from $p_2$. ✓
- $c_{\text{bak}}$: both unaffected. ✓
- $c_{\text{insp}}$: both unaffected. ✓
- $c_{\text{mig}}$: both use $p_2$ as current. ✓
- $c_{\text{prov}}$: both record $p_1$ as superseded via the link. ✓ (in tested fragment)
- $c_{\text{op}}$: $\texttt{Supersede}$ returns "Supersede"; the composition returns "Assert, Link". **Different.**
- $c_{\text{conf}}$: both unaffected. ✓
- $c_{\text{replay}}$: both reproduce. ✓
- $c_{\text{atom}}$: both non-atomic. ✓

**Counterexample:** $c_{\text{op}}$ distinguishes them.

**Conclusion:** $\texttt{Supersede} \not\equiv_{\text{tested}} \texttt{Assert} \circ \texttt{Link}$ in the tested fragment **if $c_{\text{op}}$ is admissible.**

Since $c_{\text{op}}$ is admissible in the tested fragment, $\texttt{Supersede}$ is **not derivable** from $\texttt{Assert} \circ \texttt{Link}$.

**However:** If $c_{\text{op}}$ is removed from the tested fragment (e.g., because operation-level audit is not required for the Nexus migration problem), then $\texttt{Supersede}$ **is derivable**.

**This is the relativity of minimality in action.**

## D.2 $\texttt{Merge}$

**Claim:** $\texttt{Merge}(K_1, K_2) \equiv_{\text{tested}} \texttt{Assert}(K_2\text{'s contents}) \circ \texttt{Link}(\text{relationships})$.

**Verification via $c_{\text{atom}}$:**

- $\texttt{Merge}$: atomic
- Sequence of $\texttt{Assert}$: not atomic

**Different outputs.** Therefore:
$$
\texttt{Merge} \not\equiv_{\text{tested}} \texttt{Assert} \circ \texttt{Link}
$$

$\texttt{Merge}$ is **not derivable** in the tested fragment.

## D.3 $\texttt{Isolate}$

**Claim:** $\texttt{Isolate}(p) \equiv_{\text{tested}} \texttt{Link}(p, \texttt{isolated}, \top)$ where $\top$ is a top element.

**Verification via $c_{\text{conf}}$:**

- $\texttt{Isolate}(p)$: $p$ is isolated
- $\texttt{Link}(p, \texttt{isolated}, \top)$: $p$ is linked to isolation marker

If $c_{\text{conf}}$ checks the isolation marker, they coincide. If $c_{\text{conf}}$ checks semantic isolation, they differ.

**In the tested fragment, $c_{\text{conf}}$ checks the marker.** Therefore:
$$
\texttt{Isolate} \equiv_{\text{tested}} \texttt{Link}(p, \texttt{isolated}, \top)
$$

$\texttt{Isolate}$ is **derivable** in the tested fragment.

But this is **fragile** — a stronger conflict continuation would distinguish them.

## D.4 $\texttt{Retract}$

**Claim:** $\texttt{Retract}(p) \equiv_{\text{tested}} \texttt{Transition}(K, \texttt{retract}(p))$.

**Verification:** All tested continuations treat them the same (assuming the event algebra is rich enough).

$\texttt{Retract}$ is **derivable** from $\texttt{Transition}$.

But this is a **presentation choice**, not a minimization. $\texttt{Transition}$ is a universal presentation, and the choice to encode `Retract` as an event is a design decision.

## D.5 $\texttt{Assert}$

**Claim:** $\texttt{Assert}(p) \equiv_{\text{tested}} \texttt{Transition}(K, \texttt{assert}(p))$.

Same reasoning as `Retract`. $\texttt{Assert}$ is derivable from $\texttt{Transition}$ via the event encoding.

## D.6 $\texttt{Link}$

**Claim:** $\texttt{Link}(p, r, q) \equiv_{\text{tested}} \texttt{Transition}(K, \texttt{link}(p, r, q))$.

Same as above. $\texttt{Link}$ is derivable from $\texttt{Transition}$.

## D.7 Summary of derivability tests

| Operation | Derivable in tested fragment? | Caveat |
|---|---|---|
| $\texttt{Assert}$ | Yes (via $\texttt{Transition}$) | Presentation choice |
| $\texttt{Link}$ | Yes (via $\texttt{Transition}$) | Presentation choice |
| $\texttt{Retract}$ | Yes (via $\texttt{Transition}$) | Presentation choice |
| $\texttt{Supersede}$ | No (if $c_{\text{op}}$ admissible) | Fragile |
| $\texttt{Merge}$ | No | $c_{\text{atom}}$ distinguishes |
| $\texttt{Isolate}$ | Yes (via $\texttt{Link}$) | Fragile |
| $\texttt{Transition}$ | N/A (universal) | Not a primitive |

---

# Part E — Step 3: Candidate Minimal Basis

From the derivability tests:

## E.1 Operations that are not derivable (candidates for primitivity)

- $\texttt{Supersede}$ (because $c_{\text{op}}$ distinguishes)
- $\texttt{Merge}$ (because $c_{\text{atom}}$ distinguishes)

## E.2 Operations that are derivable

- $\texttt{Assert}$, $\texttt{Link}$, $\texttt{Retract}$ (via $\texttt{Transition}$)
- $\texttt{Isolate}$ (via $\texttt{Link}$)

## E.3 But wait — $\texttt{Transition}$ subsumes everything

If $\texttt{Transition}$ can encode all operations via the event algebra, then **nothing is primitive except $\texttt{Transition}$**. This is the "universal presentation" problem.

**Resolution:** $\texttt{Transition}$ is a **presentation**, not a primitive. The primitive basis must be chosen from the **semantic capabilities**, not from the syntactic operations.

## E.4 Semantic capabilities

From the established capability lower bound:
$$
\mathfrak{C}_{\text{Nexus}} \supseteq \{\texttt{Introduce}, \texttt{Relate}, \texttt{ChangeStanding}, \texttt{ConflictContainment}\}
$$

Each capability must be realized by at least one primitive operation.

## E.5 Mapping capabilities to operations

| Capability | Candidate primitive(s) |
|---|---|
| $\texttt{Introduce}$ | $\texttt{Assert}$ or $\texttt{Transition}(\texttt{assert})$ |
| $\texttt{Relate}$ | $\texttt{Link}$ or $\texttt{Transition}(\texttt{link})$ |
| $\texttt{ChangeStanding}$ | $\texttt{Retract}$, $\texttt{Supersede}$, or $\texttt{Transition}(\texttt{retract}/\texttt{supersede})$ |
| $\texttt{ConflictContainment}$ | $\texttt{Isolate}$ or $\texttt{Link}(\texttt{isolated})$ |

## E.6 The minimal basis (relative to the tested fragment, without universal `Transition`)

Choosing `Transition` as the sole primitive makes the basis trivially minimal but moves all complexity into the event algebra. This is **not** the right choice for mathematical minimality.

Excluding `Transition`, the candidate minimal basis is:

$$
\Omega_{\text{req}}^{\text{tested}} = \{\texttt{Assert}, \texttt{Link}, \texttt{Retract}, \texttt{Supersede}, \texttt{Merge}, \texttt{Isolate}\}
$$

But wait — we must test **irredundancy**.

## E.7 Irredundancy test

**Remove $\texttt{Supersede}$:** Is it derivable from the rest?

- Test $c_{\text{op}}$: $\texttt{Supersede}$ returns "Supersede"; composition of others returns "Assert, Link".
- **Not derivable.** Keep $\texttt{Supersede}$.

**Remove $\texttt{Merge}$:** Is it derivable from the rest?

- Test $c_{\text{atom}}$: $\texttt{Merge}$ atomic; composition non-atomic.
- **Not derivable.** Keep $\texttt{Merge}$.

**Remove $\texttt{Isolate}$:** Is it derivable from the rest?

- Yes, via $\texttt{Link}(\texttt{isolated})$.
- **Derivable.** Remove $\texttt{Isolate}$.

**Remove $\texttt{Retract}$:** Is it derivable from the rest?

- Test $c_{\text{prov}}$: $\texttt{Retract}$ has a distinct history from $\texttt{Assert}(\neg p)$.
- If $c_{\text{prov}}$ observes retraction history, $\texttt{Retract}$ is not derivable.
- **Not derivable.** Keep $\texttt{Retract}$.

**Remove $\texttt{Assert}$:** Is it derivable from the rest?

- Without $\texttt{Assert}$, no operation introduces new propositions.
- **Not derivable.** Keep $\texttt{Assert}$.

**Remove $\texttt{Link}$:** Is it derivable from the rest?

- Without $\texttt{Link}$, no operation creates relations.
- **Not derivable.** Keep $\texttt{Link}$.

## E.8 Final candidate minimal basis

$$
\boxed{
\Omega_{\text{req}}^{\text{tested}} = \{\texttt{Assert}, \texttt{Link}, \texttt{Retract}, \texttt{Supersede}, \texttt{Merge}\}
}
$$

This is a minimal generating set for the tested Nexus fragment, **excluding `Transition` as a universal presentation**.

---

# Part F — Verification

## F.1 Generation

Every operation in $\Sigma_{\text{adm}}^{\text{tested}}$ must be derivable from $\Omega_{\text{req}}^{\text{tested}}$.

- $\texttt{Assert}$: in basis ✓
- $\texttt{Link}$: in basis ✓
- $\texttt{Retract}$: in basis ✓
- $\texttt{Supersede}$: in basis ✓
- $\texttt{Merge}$: in basis ✓
- $\texttt{Isolate}$: derivable via $\texttt{Link}(\texttt{isolated})$ ✓
- $\texttt{Transition}$: presentation of basis operations ✓

**Generation holds.**

## F.2 Irredundancy

Removing any operation breaks generation. Tested in E.7. **Irredundancy holds.**

## F.3 Congruence

The basis must be closed under $\equiv_{\text{Nexus}}$. If $o \in \Omega$ and $o' \equiv_{\text{Nexus}} o$, then $o'$ is identified with $o$.

**Congruence holds.**

## F.4 Minimality

By D4.5.12.d, minimality is relative to $\Pi$ and congruence. The candidate $\Omega_{\text{req}}^{\text{tested}}$ is minimal relative to the tested fragment.

**Minimality holds relative to tested fragment.**

---

# Part G — Falsification Tests

## G.1 Falsifier 1: New admissible continuation

If a new continuation $c^\star$ distinguishes two operations we declared equivalent, or uses a capability we declared derivable, the candidate is falsified.

**Candidate falsifier:** $c^\star = $ "query the reason for supersession."

- After $\texttt{Supersede}(p_1, p_2)$: reason may be recorded
- After $\texttt{Assert}(p_2) \circ \texttt{Link}(\texttt{Supersedes})$: reason may not be recorded

If so, $\texttt{Supersede} \not\equiv_{\text{tested}} \texttt{Assert} \circ \texttt{Link}$, confirming $\texttt{Supersede}$'s primitivity.

## G.2 Falsifier 2: New admissible operation

If a new operation $o^\star$ is not derivable from $\Omega_{\text{req}}^{\text{tested}}$, the candidate is falsified.

**Candidate falsifier:** $o^\star = \texttt{Annotate}(p, \text{note})$ — attach a note to a proposition.

- Not derivable from `Assert`, `Link`, `Retract`, `Supersede`, `Merge`.
- **Falsifies** the candidate.

**Resolution:** Add `Annotate` as a primitive, or define `Annotate` via `Link(p, note, ...)`.

## G.3 Falsifier 3: Weakening of congruence

If the congruence is weakened (e.g., a new operation is discovered that $\texttt{Isolate}$ is not derivable from $\texttt{Link}$), the candidate may fail irredundancy.

**Candidate falsifier:** A conflict continuation that observes isolation as a distinct semantic state.

- If so, `Isolate` must be added back.

## G.4 Summary of falsification

The candidate basis is **falsifiable** by:

1. New continuations distinguishing "equivalent" operations.
2. New operations not derivable from the basis.
3. Strengthened congruence making "derivable" operations non-derivable.

This is the correct structure: minimal bases are **provisional relative to $\Pi$**, not absolute.

---

# Part H — Architectural Consequences

## H.1 DDD (only after math)

The mathematical result is:

$$
\Omega_{\text{req}}^{\text{tested}} = \{\texttt{Assert}, \texttt{Link}, \texttt{Retract}, \texttt{Supersede}, \texttt{Merge}\}
$$

This **does not** mean these operations become DDD aggregates. DDD follows after the math.

**Architectural consequence:** The minimal operation basis determines which **capabilities** must exist at the aggregate level. But aggregates are formed by:

- Lifecycle
- Consistency boundaries
- Transactional requirements
- Domain semantics

These are **not yet derivable** from the operation basis alone.

## H.2 Levels

The operation basis sits at **Level 8** (minimal operation presentation) in the architectural hierarchy:

```text
Level 0   World domain
Level 1   Epistemic record 𝓔
Level 2   Admissible operations Σ
Level 3   Continuation semantics 𝓒Π
Level 4   Observable consequences ObsΠ
Level 5   Operation equivalence ≡Π
Level 6   Derivable / primitive classification
Level 7   Congruence
Level 8   Minimal operation presentation ← here
Level 9   Minimal representation
Level 10  DDD domain boundaries
Level 11  Kernel reduction
```

## H.3 Kernel

The Kernel is **not yet derivable**. It requires:

1. Minimal operation basis (now derived for tested fragment)
2. Minimal representation (open)
3. Kernel reduction (open)

The Kernel remains the final reduction problem.

---

# Part I — Nexus Worked Example

## I.1 Scenario

Nexus migration readiness. Evidence arrives:

- $e_1$: Direct production inspection confirms version 3.69.0-02
- $e_2$: Confluence document claims version 3.69.0-02
- $e_3$: Backup mechanism Veeam suspected, not verified

## I.2 State construction with basis

Using $\Omega_{\text{req}}^{\text{tested}}$:

$$
K = \texttt{Assert}(v = 3.69.0\text{-}02, \text{source} = \text{prod}) \circ \texttt{Link}(\text{source}, e_1)
$$
$$
\circ \texttt{Assert}(v = 3.69.0\text{-}02, \text{source} = \text{confluence}) \circ \texttt{Link}(\text{source}, e_2)
$$
$$
\circ \texttt{Assert}(\text{backup} = \text{suspected Veeam}) \circ \texttt{Link}(\text{backup}, e_3)
$$

## I.3 Continuations and observable consequences

**$c_{\text{ver}}$ (version query):**
- Returns 3.69.0-02 ✓

**$c_{\text{prov}}$ (provenance trace):**
- For $e_1$: `prod`
- For $e_2$: `confluence`
- **Different outputs**, so $e_1 \not\equiv e_2$. This is the Q74.4 result.

**$c_{\text{bak}}$ (backup audit):**
- Returns "suspected Veeam, unverified"

**$c_{\text{mig}}$ (migration assessment):**
- Returns readiness based on observable fields

## I.4 Substitution test

Can `Supersede(p_1, p_2)` be replaced by `Assert(p_2) ∘ Link(Supersedes)`?

- $c_{\text{ver}}$: equivalent ✓
- $c_{\text{prov}}$: equivalent ✓
- $c_{\text{mig}}$: equivalent ✓
- $c_{\text{op}}$: **different** (Supersede vs Assert,Link)

**Substitution fails** if $c_{\text{op}}$ is admissible. **Succeeds** if not.

**Conclusion:** The substitution is **relative to the tested continuation set**.

---

# Part J — Derived Results

**R1 (Candidate minimal basis for tested Nexus fragment).**

$$
\Omega_{\text{req}}^{\text{tested}} = \{\texttt{Assert}, \texttt{Link}, \texttt{Retract}, \texttt{Supersede}, \texttt{Merge}\}
$$

**R2 (Relativity).** The candidate is minimal relative to the tested continuation set. It is falsifiable by new continuations or operations.

**R3 (Derivability of Isolate).** $\texttt{Isolate}$ is derivable from $\texttt{Link}$ in the tested fragment.

**R4 (Primitivity of Supersede and Merge).** $\texttt{Supersede}$ and $\texttt{Merge}$ are primitive **if** the tested continuation set includes $c_{\text{op}}$ and $c_{\text{atom}}$ respectively. If not, they may be derivable.

**R5 (Universal Transition).** $\texttt{Transition}$ is a universal presentation, not a primitive. Its use as the sole primitive moves complexity into the event algebra, violating the spirit of mathematical minimality.

**R6 (Non-uniqueness).** The minimal basis is not unique. If $c_{\text{op}}$ is removed, $\texttt{Supersede}$ becomes derivable and the basis shrinks.

---

# Part K — Status Update

| Concept | Status |
|---|---|
| Required distinctions | Derived |
| Observational quotient | Derived |
| Operation equivalence | Derived |
| Derivability | Derived |
| Primitivity | Derived |
| Congruence | Derived |
| Minimality | Derived |
| **Candidate Nexus minimal basis** | **Derived (Q-D4.5.12.e)** |
| **Relativity of candidate** | **Derived (Q-D4.5.12.e)** |
| Minimal representation | Open |
| Kernel | Open |

---

# Part L — The Next Question

The candidate minimal basis is derived relative to the tested Nexus fragment. But the **tested fragment is not the full Nexus problem specification**. The derivation is falsifiable by:

1. New continuations
2. New operations
3. Stronger congruence

The next question is:

$$
\boxed{
\textbf{Q-D4.5.12.f — What is the minimal representation of a state in terms of } \Omega_{\text{req}}^{\text{tested}}?
}
$$

More precisely:

> Given the minimal basis $\Omega_{\text{req}}^{\text{tested}}$, what is the minimal state representation $K_{\min}$ such that every admissible continuation can be expressed as a composition of basis operations on $K_{\min}$?

This must come after the minimal basis, because the representation depends on the operations. It must come before the Kernel, because the Kernel is the final reduction of the representation.

---

# Part M — Why Q-D4.5.12.f Must Precede Q-D4.5.12.g (Kernel)

## M.1 The dependency

The Kernel is the **final reduction** of the state representation under observable semantics. Without a minimal representation, there is nothing to reduce.

## M.2 The falsification dependency

The Kernel claim must be falsifiable. Falsification requires a precise representation to test against. Without $K_{\min}$, Kernel claims are unfalsifiable.

## M.3 The architectural dependency

DDD boundaries depend on the state representation. Without $K_{\min}$, aggregate boundaries are speculative.

## M.4 The Nexus consequence

For Nexus, the minimal representation is the **smallest state structure** that supports all admissible continuations via the basis operations. This is not yet known.

---

# Part N — Reflection

## N.1 What has been achieved

1. A **candidate minimal basis** for the tested Nexus fragment is derived.
2. **Relativity** of the basis to the tested fragment is established.
3. **Falsification tests** for the basis are established.
4. The **derivability of Isolate** is demonstrated.
5. The **primitivity of Supersede and Merge** is demonstrated (conditionally).

## N.2 What this changes

Previously, the programme had a **proposed** operation set without a derivation. Now it has a **derived** candidate basis for the tested Nexus fragment, with explicit falsification conditions.

## N.3 What remains

1. Minimal representation
2. Kernel
3. DDD boundaries

These remain downstream. The programme continues one question at a time.

## N.4 Final statement

$$
\boxed{
\Omega_{\text{req}}^{\text{tested}} = \{\texttt{Assert}, \texttt{Link}, \texttt{Retract}, \texttt{Supersede}, \texttt{Merge}\}
}
$$

**relative to the tested Nexus fragment.**

This is the central result of Q-D4.5.12.e. It is **provisional** and **falsifiable**, but it is **derived**, not assumed. The next question is Q-D4.5.12.f: minimal representation.