---
artifact: KNOWLEDGE-STATE-MODEL-AUDIT-231
mandate: 20260830_2152 §5 — "What is K?" · capability matrix over the six candidate models
date: 2026-08-30
status: DELIVERED — capability matrix EXECUTED
authority: verifier session (adversarial, independent)
evidence_class: A (formal analysis) + B (executed matrix)
---

# What is `K`? — audit of the six candidate models

**Step 230 §230.49 states the problem honestly: *"We have `K` but have not yet defined what a knowledge
state actually is."* Step 231 proposes six candidate models. This audit tests all six against the
seventeen capabilities the mandate specifies.**

---

## 1. The executed capability matrix

`Y` = natively representable · `P` = representable only with an added construction · `N` = not
representable without becoming a different structure.

| capability | A set | B graph | C prob. | D typed | E lattice | F hybrid |
|---|:--:|:--:|:--:|:--:|:--:|:--:|
| assertions | Y | Y | P | **Y** | N | Y |
| evidence | N | P | P | **Y** | N | Y |
| provenance | N | **Y** | N | P | N | Y |
| context | N | P | N | **Y** | N | Y |
| contradiction | P | Y | P | **Y** | Y | Y |
| uncertainty | N | N | **Y** | P | P | Y |
| temporal evolution | P | P | P | **Y** | Y | Y |
| supersession | N | Y | N | P | **Y** | Y |
| **authority** | N | P | N | P | N | **P** |
| **validation** | N | P | N | P | N | **P** |
| unknown | P | P | Y | **Y** | Y | Y |
| missingness | P | P | P | **Y** | N | Y |
| non-identifiability | N | N | **Y** | N | N | P |
| **governance** | N | P | N | P | N | **P** |
| lineage | N | **Y** | N | P | N | Y |
| **replay** | P | P | N | P | N | **P** |
| state transition | P | P | P | **Y** | P | Y |

| Model | Y | P | N | score (Y + ½P) |
|---|--:|--:|--:|--:|
| A — `K ⊆ X` (set) | 1 | 6 | **10** | 4.0 |
| B — `K = (V,E)` (graph) | 5 | 10 | 2 | 10.0 |
| C — `K ~ P_θ` (probabilistic) | 3 | 6 | 8 | 6.0 |
| **D — `K = {(q,s,e,c,t)}` (typed propositions)** | **8** | 8 | **1** | **12.0** |
| E — `K₁ ⪯ K₂` (lattice) | 4 | 2 | **11** | 5.0 |
| F — hybrid (§230.51) | **12** | 5 | **0** | **14.5** |

---

## 2. **THE PRINCIPAL RESULT: four capabilities belong to no `K`-model at all**

**Executed test — capabilities that NO non-hybrid model handles natively:**

```
authority    A:N  B:P  C:N  D:P  E:N
validation   A:N  B:P  C:N  D:P  E:N
governance   A:N  B:P  C:N  D:P  E:N
replay       A:P  B:P  C:N  D:P  E:N
```

**And the hybrid model F — which absorbs everything else — still scores only `P` on all four.**

**This is not a deficiency of the models. It is evidence that these four are not properties of a knowledge
state.**

- **Authority** is a relation between an *actor* and an *action*, time-indexed (`A_t` in §230.10). It is a
  property of a **transformation**, not of a state.
- **Validation** is an *assessment of* a state, performed by something outside it. A state cannot contain
  its own validation without circularity.
- **Governance** is a relation between a *policy* and a *transformation*.
- **Replay** is a property of a **history of transformations**, not of any single state.

> **CONCLUSION (`VERIFIED`, evidence class A): `K` should NOT carry authority, validation, governance or
> replay. Every attempt to make a single structure represent them forces a `P`.**

**This vindicates the kernel's shape while refuting Model F.** `𝒦 = (K,C,T,E,A)` is *right* to keep `A`
separate from `K` — and **Model F is wrong to try to absorb everything into `K`.** The two proposals in
Steps 230 and 231 are in tension, and neither notices.

**It also confirms the mandate's own hint (§5):** *"It may be that the correct result is a typed family of
related structures, rather than one tuple."* **The matrix supplies the evidence for that.**

---

## 3. The smallest adequate structure — derived, not preferred

**Model D (`K = {(q,s,e,c,t)}` — typed proposition tuples) is the strongest single structure**: 8 native
capabilities, and **only one `N`**.

Its single failure is **non-identifiability**, which is the one capability Model C (probabilistic) handles
natively and D cannot express.

> **DERIVED ANSWER: the smallest adequate knowledge-state structure is Model D augmented with an
> uncertainty annotation:**
>
> ```
> K = { (q, s, e, c, t, u) }
> ```
> where `q` = proposition, `s` = epistemic status, `e` = evidence reference, `c` = context,
> `t` = temporal validity, **`u` = uncertainty representation** (carrying identifiability),
>
> **with authority, validation, governance and replay held OUTSIDE `K`** — in `A`, in assessment
> operations, in `Policy`, and in `History(T)` respectively.

**Status: `VERIFIER RECOMMENDS` / `PROPOSED RECONSTRUCTION`.** It is derived from the executed matrix, but
**it is not what the corpus says**, and it must not be promoted to `CORPUS ESTABLISHES`.

**Note it is close to Model D, which the corpus already proposes** — so this is a *refinement of a corpus
candidate*, not an invention. The added `u` is the minimum needed to cover the one capability D misses.

---

## 4. Model-by-model verdicts

| Model | Verdict | Reason |
|---|---|---|
| **A** `K ⊆ X` | **REFUTED as adequate** | 10 of 17 capabilities not representable. A bare set carries no provenance, no context, no uncertainty, no evidence. Adequate only for the simplest membership questions |
| **B** `K = (V,E)` | **PARTIALLY ADEQUATE** | Natively strong on provenance, lineage, supersession, contradiction — the *relational* concerns. **Cannot express uncertainty or non-identifiability at all** |
| **C** `K ~ P_θ` | **REFUTED as a general model; NECESSARY as a component** | Only model handling uncertainty AND non-identifiability natively — but 8 `N`s. It cannot represent provenance, context, authority, lineage. **Required as an annotation, not as the structure** |
| **D** `K = {(q,s,e,c,t)}` | **STRONGEST SINGLE MODEL** | 8 Y, 1 N. Natively carries assertion, evidence, context, contradiction, time, unknown, missingness, transitions |
| **E** `K₁ ⪯ K₂` | **REFUTED as adequate** | 11 `N`s. An order relation expresses *comparison* (supersession, contradiction, unknown-as-bottom) and almost nothing else. **It is a relation ON knowledge states, not a model OF one** |
| **F** hybrid (§230.51) | **UNDER-SPECIFIED** | 0 `N`s only because it is a *list of six structures with no integration rule*. `(Graph, Types, Propositions, Evidence, Probability, Time)` has no stated typing, no interaction semantics, no equality, no membership. **It scores highest by naming everything and defining nothing** |

**On Model F specifically:** it is the corpus's own preferred answer and it is the least well-defined.
Naming six mathematical structures in a tuple does not constitute a structure. **And it re-introduces the
`E` double-binding recorded in `KERNEL-AUDIT-230-232.md` §7** — `Evidence` appears inside `K` here while
also being kernel component `E`.

---

## 5. What the corpus establishes vs. what this audit establishes

| Claim | Class |
|---|---|
| `K` is currently undefined | **`CORPUS ESTABLISHES`** — §230.49, verbatim, explicitly |
| Six candidate models are worth testing | **`CORPUS ESTABLISHES`** — §231 |
| Model F is the answer | **`SOURCE CLAIM`** — asserted, not argued |
| No `K`-model can natively carry authority/validation/governance/replay | **`VERIFIED`** (executed matrix) |
| Those four belong outside `K` | **`VERIFIED`** (structural argument, §2 above) |
| Model D is the strongest single structure | **`VERIFIED`** (executed matrix) |
| `K = {(q,s,e,c,t,u)}` is the smallest adequate form | **`VERIFIER RECOMMENDS`** — not corpus |

---

## 6. Open

- **No integration rule for Model F.** Until one exists, F is not a mathematical object.
- **Equality and identity on `K` are undefined in every model.** The mandate asks (§3.E) whether equality
  and membership are defined; **for all six models the answer is no**. `K₁ = K₂` is never given a
  criterion, which blocks `Replay`, `Merge` and `Supersede` in Step 232's algebra.
- **Model D's `s` (status) has no declared value set** — and the corpus carries **eleven competing
  epistemic-status vocabularies** (TV-F-074). Model D cannot be instantiated until one is chosen.
