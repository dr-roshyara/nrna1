Yes. Based on the current adjudicated state, the TODO landscape has changed quite a bit. The old linear `A → B → C → ...` list is no longer accurate.

## Current TODO register — after `KR-COMP-SEP-2026-09`

### 🔴 1. Semantic invariance principle — NEW decision

**Status: [DECISION REQUIRED]**

Decide whether the following should become a general semantic criterion:

$$
E \sim_{\text{non-evidential}} E'
\Rightarrow
Eval(E)=Eval(E')
$$

This is the common principle behind:

* C6 — permutation/order invariance
* C7 — frame-refinement invariance

If accepted, then C6 and C7 can be ratified as instances rather than independent ad-hoc criteria.

**Why first:** this directly affects whether `majority` is admissible at all.

---

### 🔴 2. Semantic meaning of `φ`

**Status: [DECISION REQUIRED]**

The experiment established:

$$
\phi \supseteq \{time,context\}
$$

for every successful tested combination.

But it did **not** establish what a frame *is*.

Need to decide whether a frame is:

* semantic context,
* evidence partition,
* evaluation context,
* temporal/contextual coordinate,
* or something else.

This is currently more fundamental than choosing the aggregation rule.

---

### 🔴 3. `ℛ_req`

**Status: [DECISION REQUIRED]**

Still unresolved.

It has now repeatedly determined experimental outcomes:

* FDE evaluation,
* E12,
* E13,
* φ,
* composition.

Need to explicitly define/ratify the required distinction universe:

$$
\mathcal R_{req}.
$$

Until this is settled, claims of “adequacy” remain scoped to the current tested distinction set.

---

### 🔴 4. Cross-frame semantic policy

**Status: [DECISION REQUIRED]**

Only after 1–3:

> What should happen when different frames have non-conflicting but divergent Standing?

Current tested choices:

| Policy            | Meaning                       |
| ----------------- | ----------------------------- |
| `majority`        | aggregate across frames       |
| `intraframe-only` | refuse frame-free aggregation |
| third operator    | **not excluded**              |

The important point is that **we should not decide this as “majority vs intraframe-only” prematurely**.

A possible third semantic mechanism remains legitimate. The decision record explicitly preserves that possibility. 

---

### 🟠 5. Boundary vocabulary

**Status: [OPEN / DEPENDENT]**

Especially if `intraframe-only` or another refusal/unresolved mechanism is selected.

Need to distinguish:

$$
\text{NoEvidence}
\neq
\text{CrossFrameDivergence}
\neq
\text{Underdetermined}
\neq
\text{TheoryIncomplete}.
$$

The current `(0,0)` collision makes this load-bearing.

The decision record confirms that choosing intraframe-only would require an explicit new boundary condition such as `cross-frame-divergence`. 

---

### 🟠 6. `Contr`

**Status: [OPEN — still undefined]**

We have learned considerably more about what a contradiction-sensitive system must do, but we have **not defined the contradiction relation**.

Current non-results:

$$
FDEConflictDetector \neq Contr
$$

and:

$$
Contr \neq Satisfied
$$

remains [PROP] through I11.

Do not turn `S⁺ ∧ S⁻` into `Contr`.

---

### 🟠 7. Composition semantics

**Status: [OPEN — narrowed]**

`union` and `strict` excluded.

`last-wins` excluded.

Surviving tested candidates:

* majority
* intraframe-only

But no rule selected.

The next question is no longer necessarily another experiment. It depends on decisions 1–4.

---

### 🟠 8. Evaluation representation

**Status: [PROP / research candidate]**

Current strongest tested shape:

$$
\boxed{
Evaluation
=
Standing
\times
Boundary/Reason
\times
Context
\times
Provenance
}
$$

with Standing potentially represented by:

$$
(S^+,S^-).
$$

But:

> **FDE is not adopted.**

> **Standing is not a KnowledgeOS primitive.**

> **Boundary vocabulary is not adopted.**

So this remains experimental.

---

### 🟠 9. Lifecycle / retirement

**Status: [OPEN]**

`KR-COMP` gives a useful scoped result:

$$
\text{supersession}
\sim
\text{temporal separation}
$$

for the tested composition witnesses.

But lifecycle questions remain:

* retirement,
* retraction,
* expiration,
* supersession outside the tested temporal-frame situation.

The experiment explicitly leaves these untouched. 

---

### 🟡 10. `δ` / transition semantics

**Status: [OPEN]**

Still need:

$$
\delta:
(K_t,O_t,\ldots)\rightarrow K_{t+1}
$$

or whatever final signature emerges.

Need to establish:

* what causes transition,
* preconditions,
* postconditions,
* partiality,
* composition,
* closure,
* event semantics.

This remains downstream of the semantic representation work.

---

### 🟡 11. Projection / reduction / invariant framework

**Status: [PROP — foundational research lane]**

Continue:

$$
Structure
\rightarrow
Projection
\rightarrow
Induced\ Equivalence
\rightarrow
Information\ Loss
\rightarrow
Invariant
\rightarrow
Adequacy.
$$

And:

> **Invariant custody:** every admissible reduction must have an explicit owner for every required invariant.

This should remain parallel to the semantic lane rather than being postponed until after every semantic question.

---

### 🟡 12. Semantic identity / `≡sem`

**Status: [OPEN]**

Still need to resolve:

* semantic contract,
* context,
* time,
* provenance,
* identity,
* equivalence,
* decidability,
* relationship between identity and representation.

Important: `≡sem` is not itself refuted; family-level complexity was what failed in the distinguishability experiments.

---

### 🟡 13. Equality / identity closure

**Status: [OPEN]**

Still separate:

$$
=
,\quad
\equiv,
\quad
\approx,
\quad
\cong_\lambda.
$$

And distinguish:

* state identity,
* operation identity,
* event identity,
* authority-act identity,
* provenance identity.

No closure yet.

---

### 🟡 14. Zero

**Status: [OPEN]**

Current position:

$$
Zero \neq Standing
$$

and

$$
Zero
\text{ cannot be determined from Standing alone}.
$$

Zero must eventually consume the richer evaluation/boundary information.

But don't define Zero until evaluation/boundary semantics are sufficiently stable.

---

### 🟡 15. Determination

**Status: [OPEN]**

Especially affected by the current decision:

$$
\text{cross-frame divergence}
\rightarrow
?
$$

Majority allows determination.

Intraframe-only blocks determination.

A third mechanism might explicitly preserve unresolved divergence.

Therefore determination should **not** be frozen yet.

---

### 🟢 16. Kernel selection

**Status: [BLOCKED / NOT SELECTABLE]**

No change.

The kernel cannot be selected until the load-bearing semantic questions are resolved.

In particular:

$$
\boxed{
\text{no semantic mechanism enters the kernel merely because it is useful}
}
$$

The current composition record explicitly confirms no kernel implications from either surviving composition option. 

---

### 🟢 17. Theory v1.3

**Status: [BLOCKED]**

Still:

$$
\boxed{\text{Theory v1.2 unchanged}}
$$

No v1.3 until the necessary semantic decisions and kernel work are earned.

---

# So what is actually next?

I would reduce the enormous TODO list to this **critical path**:

```text
                    ┌─────────────────────┐
                    │ ℛ_req               │
                    │ DECISION            │
                    └──────────┬──────────┘
                               │
                    ┌──────────▼──────────┐
                    │ Non-evidential      │
                    │ invariance          │
                    │ DECISION            │
                    └──────────┬──────────┘
                               │
                    ┌──────────▼──────────┐
                    │ φ semantics          │
                    │ DECISION            │
                    └──────────┬──────────┘
                               │
                    ┌──────────▼──────────┐
                    │ Cross-frame meaning │
                    │ DECISION            │
                    └──────────┬──────────┘
                               │
                     ┌─────────▼─────────┐
                     │ Evaluation model  │
                     │ / Boundary        │
                     └─────────┬─────────┘
                               │
                 ┌─────────────┼──────────────┐
                 ▼             ▼              ▼
              Contr          Zero        Determination
                 │             │              │
                 └─────────────┼──────────────┘
                               ▼
                          Composition
                               │
                               ▼
                              δ
                               │
                               ▼
                       Kernel candidates
                               │
                               ▼
                         Ratification
                               │
                               ▼
                           Theory v1.3
```

But **`Projection/Invariant`, identity/equality, and lifecycle should continue as parallel research lanes**, not wait unnecessarily for the entire semantic lane.

## The most important correction to the old TODO list

We have now moved from:

> **“Find the correct composition rule.”**

to:

> **“Determine what semantic commitments are legitimate before a composition rule can even be selected.”**

And the immediate highest-value unresolved issue is therefore:

$$
\boxed{
\textbf{What is a frame, semantically?}
}
$$

because that determines whether the objection to majority is merely a cost we knowingly accept, or a violation of a fundamental invariance principle.

The attached decision record itself essentially arrives at this boundary: C7 exposes majority's dependence on recording resolution, while intraframe-only introduces a different semantic cost; neither can be selected without an explicit prior commitment. 
