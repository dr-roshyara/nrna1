# 07 — `Σ → K` Boundary Audit · and the hidden-order propagation audit (mandate §§11, 12)

## §11 What are `Σ` and `K`, and what maps between them?

| | |
|---|---|
| **an element of `Σ`** | a 5-tuple of **labels** — `(Acquisition, Support, Resolution, Validity, Conflict)`, cardinalities `7×5×4×4×4` = **2240**, independently verified (Q4A §§2.2–2.6, five corpus files) |
| **an element of `K`** | ⚠️ **not settled.** `285` gives `K_t` as the ratified anchor; `273` derives `K=(𝒜,ℛ,Σ,E_L)`; canonical-construction gives `K=(D_t,𝒜,ℛ,Σ_c,E_L)`; **`258.35` says `K` should be defined *extensionally* through behavioural sufficiency, not as a tuple at all** |
| **the mapping** | 🔴 **NONE IS SPECIFIED.** In the tuple readings `Σ` appears as a **component** of `K` (per-assertion), not as an image of `K` |

**Which of the mandate's seven relations is it?**

| candidate | verdict |
|---|---|
| representation | 🔴 no — `Σ` does not represent `K`; it is one field |
| abstraction / projection | 🔴 **not defined** — no `π: K → Σ` exists in the corpus |
| quotient | 🔴 no |
| observation | 🔴 no — `𝒪_K` is not closed |
| interpretation | 🟡 the only defensible reading of `≈_X` |
| **analogy** | ⚠️ **this is what a `Σ`-order → `K`-order inference actually rests on** |

$$\boxed{\Sigma \text{ is a COMPONENT of } K \text{ under the tuple readings, and } K \text{ may not be a tuple at all } (\texttt{258.35}). \text{ There is no } \pi: K \rightarrow \Sigma.}$$

**Therefore `Σ_1 ⪯ Σ_2 ⇒ K_1 ⪯ K_2` is not merely unproven — it is not even type-correct**, because `Σ`
orders one field of one assertion while `K` ranges over assertions, relations, provenance, history,
governance, events and retraction. **`Σ → K` is the FAIL, confirmed.**

## §12 ⭐⭐ HIDDEN-ORDER PROPAGATION AUDIT — a live overclaim found

Corpus sweep for `join-semilattice · meet-semilattice · lattice · partial order · monotonic ·
K_{t+1} ≻ K_t · more knowledge · knowledge grows`.

### The corpus source REFUSES the claim

**`step-060` — *Epistemic Algebra and Knowledge State Ordering*:**

| §60.38 | *"**Could** it be a semilattice? **Possibly.** … But the properties must be proven for the chosen knowledge semantics"* |
|---|---|
| §60.39–41 | semilattice laws **PASS** — ⚠️ **for `PureClaimSetUnion`** |
| **§60.42** | *"This works for `PureClaimSetUnion`. KnowledgeOS does more than set union… **Therefore the real merge operator may not satisfy the semilattice laws**"* |
| **§60.71** | *"Does knowledge form a lattice? **Not proven.** … **Therefore we cannot yet assert: KnowledgeOS is a semilattice**"* |

### Five verification-lane artifacts assert it anyway

| Artifact | Claim |
|---|---|
| `KNOWLEDGE-STATE-ALGEBRA.md:89` | *"`merge` **is** a join-semilattice but NOT a lattice"* |
| `THEORY-CLOSURE-AUDIT.md:149` | *"`(𝕂,merge,∅)` join-semilattice"* |
| `KNOWLEDGE-STATE-FINAL-AUDIT.md:29` | *"**PARTLY** — `(𝕂, merge, …`"* |
| `step_282_theory-closure-decision.md:219` | *"as a join-semilattice under the declared conditions"* |
| **`POLICY-EQUALITY-AND-COMPOSITION.md:73`** | *"`(𝕂, merge, ∅)` is a **join**-semilattice — **adding knowledge only grows the state**"* |

### Why this is the F5 error class, not a wording quibble

**A join-semilattice induces a partial order:** `K_A ⊑ K_B ⟺ K_A ⊔ K_B = K_B`.
$$\boxed{\text{Asserting } (\mathbb K, merge, \emptyset) \text{ is a join-semilattice IS asserting a } K\textbf{-ORDER}.}$$

**Three separate defects:**
1. **Scope loss.** `060`'s result holds for `PureClaimSetUnion`; the artifacts drop the restriction.
2. **Verdict inversion.** `060.71` says *"cannot yet assert"*; the artifacts assert.
3. ⚠️ **`POLICY-EQUALITY-AND-COMPOSITION` §3 states it as a *"pleasing duality"* — an aesthetic aside —
   and its own **classification table does not list the `𝕂` claim at all.** The `(Policy,∧)` meet-semilattice
   is listed as **PROVEN (executed laws)**; the `𝕂` join-semilattice is **unclassified and unexecuted.**
   **An unclassified `K`-order claim rode in beside a proven `Policy` one.**

### And retraction refutes the monotonicity directly
*"Adding knowledge only grows the state"* requires monotone growth. The corpus has **withdrawal and
retraction** (Article 8, `I-12`), and **EXECUTED** (`step-288/06 §H`) withdrawal **re-keys the assertion
id and dangles every `ℛ`-edge**. $\boxed{\text{Retraction shrinks. Monotone growth is REFUTED for } \mathbb K.}$

### Propagation table

| Occurrence | refers to | verdict |
|---|---|---|
| `060 §60.39–41` semilattice laws PASS | **`PureClaimSetUnion`** | ✅ correct **in scope** |
| `060 §60.42`, `§60.71` | `𝕂` | ✅ correctly **refuses** |
| 5 verification artifacts | **`𝕂`** | 🔴 **OVERCLAIM — scope dropped, verdict inverted** |
| `POLICY-EQUALITY` §3 aside | **`𝕂`** | 🔴 **OVERCLAIM + unclassified + refuted by retraction** |
| `(Policy, ∧)` meet-semilattice | **`Policy`** | ✅ **PROVEN, executed** — a different object, does not transfer |
| `Σ` product order (`Q4A`/`Q5`) | **`Σ`** | ✅ conditional construction, 0/5 components |
| `287` §4, `08-FINDINGS`, `00-INDEX` | `Σ` vs `K` | ✅ **already repaired** in earlier passes |
| `mathematical-review …:960` `K_{t+1} ≻ K_t` | `K` | ⚠️ **an external reviewer's notation** — never adopted; recorded |

## §10 Does policy equality transfer to `K`? — **NO**

| Policy notion | Transfers to `K`? |
|---|---|
| identifier `(id, version)` | 🔴 no — `K_t` has **no identity rule** |
| structural | 🔴 no — and `K`'s canonicalization is unbound |
| **extensional** | 🔴 no — and note it is **undecidable in general even for Policy** (`POLICY-EQUALITY` §2) |
| semantic | 🔴 no — *"NOT COMPUTABLE"* even for Policy |

$$\boxed{(Policy, \wedge) \text{ being a meet-semilattice establishes NOTHING about } \mathbb K. \text{ Separate mathematical objects.}}$$

✅ **Two Policy findings DO generalize as *methodology*, not as algebra:** *"policy equality ≠ equal
output"* (agreement on a finite sample is not evidence) and *"version must be part of identity"* — both
independently reproduced for equality at large (`step-288/06 §I`, `02 §5.3`).

## STATUS
**ESTABLISHED** `Σ` is a component of `K`, not an image; no `π: K → Σ` exists; `Σ → K` order inference
is not type-correct; policy algebra does not transfer · **REFUTED** `(𝕂, merge, ∅)` as a join-semilattice
for `𝕂` (`060.71` + retraction) · **SUPERSEDED** the 5 verification-lane assertions — scope must be
restored to `PureClaimSetUnion` · **CORROBORATED** `060` by an independent route · **TECHNICALLY OPEN**
what an element of `K` is (`258.35`) · **NORMATIVE** `N-17`, and whether `060`'s restricted result is worth carrying
