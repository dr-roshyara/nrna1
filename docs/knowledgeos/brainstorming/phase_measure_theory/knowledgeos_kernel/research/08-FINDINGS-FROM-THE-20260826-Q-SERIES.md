---
artifact: 08-FINDINGS-FROM-THE-20260826-Q-SERIES
date: 2026-08-31
source: 116 documents stamped `20260826-*` in `phase_measure_theory/`
status: **7 NEW FINDINGS · 3 open problems move · 1 refutation repaired by composition · 2 negative results**
---

# 08 · Findings from the 2026-08-26 Q-Series

**Why this day matters:** 116 documents, containing Questions 1–24 and the first complete formal
model. **It predates the entire verification programme.** Read against my seven open problems.

---

## F1 ⭐ `Σ` is five-dimensional, and the transition algebra exists

`CORPUS` — **Question 4A** (`20260826-173318`), §2.1 and §7.1:

$$\Sigma = (A, S, R, V, C)$$

| Axis | Values |
|---|---|
| **A** Acquisition | `Observed · Reported · Inferred · Calculated · Assumed · Hypothesized · Unknown` (7) |
| **S** Support | `None · Weak · Moderate · Strong · Very Strong` (5) |
| **R** Resolution | `Open · In Progress · Resolved · **Unresolvable**` (4) |
| **V** Validity | `Current · **Stale** · Expired · Unknown` (4) |
| **C** Conflict | `None · Potential · Active · Resolved` (4) |

**State space `𝒮 = 7×5×4×4×4 = 2240`.**

And the transition function is **componentwise** (§7.2):
$$\Sigma_{new} = T(\Sigma_{old}, E), \quad A_{new}=T_A(A_{old},E),\; S_{new}=T_S(\cdot),\; R_{new}=T_R(\cdot),\; V_{new}=T_V(\cdot),\; C_{new}=T_C(\cdot)$$

> **The `20260826-172247` mathematical review lists ❌ *"Epistemic Status Algebra — no transition
> rules"* as a formal gap. Question 4A supplies the transition rules the same day.** The gap was
> raised and answered within hours, and the answer never reached the verification lane.

---

## F2 ⭐ The ten-value status set is a FLATTENING of the five-axis product — and 3 of 10 are residue

I recorded that `Σ₀` cannot express 6 of `025d`'s 10 values. **With F1 I can now say where each
lives, and which are genuinely unaccounted for:**

| `025d` value | Axis in `Σ=(A,S,R,V,C)` |
|---|---|
| `Satisfied` · `PartiallySatisfied` | **S** (Strong/Very Strong · Moderate) |
| `Unknown` | **A**=`Unknown` **or** **S**=`None` — ⚠️ *two different Unknowns, see F7* |
| `Insufficient` | **S** (None/Weak) |
| `Conflicted` | **C**=`Active` |
| **`Stale`** | **V**=`Stale` ✅ *explicitly present* |
| `Invalid` | **V**=`Expired` (approximately) |
| **`NotApplicable`** | 🔴 **no axis** |
| **`Prohibited`** | 🔴 **no axis** |
| **`Missing`** | 🔴 **no axis** |

> **7 of 10 map onto the five axes. Three do not.** And the three residuals are exactly the ones my
> registers already flag as belonging elsewhere: `Missing` needs `D_t`/`Q_t` (the inquiry layer),
> `NotApplicable` is a scope predicate, `Prohibited` is a policy verdict. **None of the three is an
> epistemic-status value at all** — which is why no `Σ` shape has ever accommodated them.
>
> **The apparent 4-vs-10 conflict between Step 272B and `025d` dissolves: `025d` is a flattening of a
> product space, and Q4A gives the factorisation.**

---

## F3 ⭐⭐ The refutation of `(A,S,R,V,C)` is REAL — and repairable by composing two corpus results

`CORPUS` — `Σ=(A,S,R,V,C)` also appears in **Q14** (lines 247, 1002). The verification lane refuted it:
> *"Q14's `(A,S,R,V,C)` — **no negative pole**, cannot express refutation."*

**Verified: the refutation is correct.** `Support ∈ {None, Weak, Moderate, Strong, Very Strong}` is
**single-polarity** — every value is non-negative. Measured: `Refut*`/`Disconfirm*` appear **nowhere**
as a Support value in Q4A; `Contradictory` occurs only in *evidence* descriptions and on the
**Conflict** axis. **`Σ=(A,S,R,V,C)` genuinely cannot say "the evidence refutes this."**

**And Step 272B supplies precisely the missing piece:** `Σ₀ = 𝒫(\{Support, Refute\}) ≅ \{0,1\}^2` —
one axis, but **with both poles.**

> ### `VERIFIER RECOMMENDS` — a repair neither document contains
> $$\Sigma = \big(A,\; \mathcal P(\{\text{Sup},\text{Ref}\}),\; R,\; V,\; C\big)$$
> **Replace Q4A's single-polarity Support axis with 272B's polarity powerset.**
>
> - Q4A is refuted **only** on the missing negative pole — the other four axes were never challenged.
> - 272B is minimal **only** on the Support axis — it never claimed the other four.
> - **Each source repairs the other's exact defect.** State space becomes `7×4×4×4×4 = 1792`.
>
> **`DERIVED`, not `CORPUS`.** Neither document proposes this composition. It is offered because it
> **resolves a recorded refutation using only corpus material** — no invention. **Not promoted.**

---

## F4 ⭐ `≈` observational equality gets a DERIVABLE parameterisation

`D288-SCOPE` target 2 recorded `≈` as blocked because *"the permitted-observation set is undeclared"*
and classified the set as **NORMATIVE**.

`DERIVED` — **With a product state space, observational equality is projection onto an axis subset:**
$$\Sigma_1 \approx_X \Sigma_2 \iff \pi_X(\Sigma_1) = \pi_X(\Sigma_2), \quad X \subseteq \{A,S,R,V,C\}$$

> **The axes ARE the permitted observations.** So `≈`'s parameter need not be invented — it is a
> subset of an already-enumerated five-element set, giving **`2⁵ = 32` candidate relations** with a
> natural containment lattice (`≈_∅` = everything equal; `≈_{A,S,R,V,C}` = structural on `Σ`).
>
> **`≈` moves from "NORMATIVE parameter, undefined" to "DERIVED form + a bounded normative choice
> over 32 options."** That is a materially smaller decision than the one `D288` recorded.

---

## F5 ⚠️ NARROWED (2026-08-31) — `Σ` gets a candidate product order; `K_{t+1} ≻ K_t` does NOT

The `20260826-172933` Q4 response left this explicitly open:
> *"Later we can investigate whether it forms a partial order, a lattice, a bilattice, a belief
> revision structure, or something else. **That mathematical question should remain open.**"*

with the objection: *"`Observed ≮ Conflicting` in any obvious scalar sense."*

`DERIVED` — **A product-order CONSTRUCTION is available as a candidate, conditional on independently
establishing component relations for all five axes:**
$$\Sigma_1 \preceq \Sigma_2 \iff A_1 \preceq_A A_2 \;\wedge\; S_1 \preceq_S S_2 \;\wedge\; R_1 \preceq_R R_2 \;\wedge\; V_1 \preceq_V V_2 \;\wedge\; C_1 \preceq_C C_2$$

> ⚠️ **CORRECTED (closure audit B-2).** Previously: *"it is a partial order **by construction** and
> NOT total."* **That asserted partiality as an established property.** **A product-order construction
> is available ONLY ONCE every component relation is independently established — and ZERO OF FIVE
> ARE.** *(Per-axis audit: `REFINED-STEP-287` §4.)*
>
> **IF** they were established, non-totality would follow: `Observed` and `Conflicting` sit on
> *different axes*, hence **incomparable**, not mis-ordered — which satisfies the Q4 objection rather
> than refuting it.
>
> ⚠️ **Component-order status — SUPERSEDED caveats.** The earlier note (*"`S` plainly ordinal, `V`
> arguably is"*) is **weaker than the audit that followed it**: `V` is **NOT ordered** (`Unknown` is
> incomparable) · `C` is **NOT ordered** (*"Resolved"* is a different *kind* of state, not *"more
> conflicted"*) · `S`'s numeric bands are **ordinal labels, not a metric** (`no averaging`) · `R` must
> **not** be assumed ordered (`Unresolvable` is a terminal side-state) · `A` has **no order**
> (normative). **See `REFINED-STEP-287` §4 for the authoritative table.**
>
> A product gives a **lattice only if every component is a lattice** — **no lattice claim is made**.
> And *"More knowledge"* ≠ *"higher `Σ`"*.
>
> ## ⚠️ F5 AS ORIGINALLY WRITTEN IS SUPERSEDED — it was a category error, not a wording slip.
>
> **Original claim:** *"`K_{t+1} ≻ K_t` is no longer structureless. It is a product order awaiting five
> component orders."*
>
> **Corrected claim:** **`Σ` gains a candidate product-order structure, conditional on declaring the
> five component orders. `K_{t+1} ≻ K_t` gains nothing.**
>
> **Why:** Q4A orders **epistemic states**. KnowledgeOS **knowledge states** may involve content ·
> provenance · history · assertions · governance · deletion/retraction · contradiction resolution —
> **none of which is a `Σ` coordinate.** Lifting the `Σ` order to `K` requires an explicit mapping and
> proof obligations that **do not exist**.
>
> **`Σ`-ordering is not `K`-ordering.** *(Full treatment: `REFINED-STEP-287` §4.)*

---

## F6 · Negative result — the two missing parameters are absent from the whole day

`EXECUTED` — grep over all **116** `20260826-*` documents:

| Term | Hits |
|---|---|
| *"permitted observation"* | **0** |
| *"relevant provenance"* | **0** |
| *"semantic equality"* / *"equivalent propositions"* | **0** |

> **`≈`'s observation set and `≅_λ`'s relevance criterion are absent from the foundational day**, and
> so is any state-level semantic-equality notion. **This strengthens `E1-E7`'s verdict:** those
> parameters were never lost — **they were never posed.** They are genuinely `NORMATIVE`, not
> recoverable. *(F4 narrows `≈` nonetheless, by supplying the candidate set from a different route.)*

---

## F7 · Two collisions confirmed live on the same day

**(i) `Unknown` is overloaded across axes.** In `Σ=(A,S,R,V,C)`, `Unknown` is a value of **A**
(*"no information about how it was obtained"*), of **V** (*"validity unknown"*) — and `None` on **S**
means *"no evidence"*. **Three different senses of not-knowing, two of them sharing one label.**
Belongs on the UL register with `Ω` and `θ`.

**(ii) `Σ` notation collision, as predicted.** The `20260826-172247` review proposed `Σ_O` for an
observation's selection/projection, to free `S` for Statement. **Q4A, written 51 minutes later, uses
`Σ` for the epistemic state vector.** So the proposed fix collided with the very next document, and
**neither `S` nor `Σ` was ever disambiguated.**

---

## Movements to the open-problem register

| # | Problem | Before | After |
|---|---|---|---|
| 1 | `Σ` dimensionality | *"`Σ₀` minimal but cannot express 6 of 10"* | 🟢 **`G5` PROMOTION** — five-axis product (F1); 7 of 10 map, 3 are not status values (F2) |
| 2 | epistemic transition algebra | ❌ *"no transition rules"* | 🟢 **`G5`** — componentwise `T_A…T_C` exist (F1) |
| 3 | `(A,S,R,V,C)` refuted | `RX`, dead | 🟡 **REPAIRABLE** by composition with `Σ₀` (F3) — `DERIVED`, not promoted |
| 4 | `≈` observational equality | 🔴 NORMATIVE, parameter undefined | 🟡 candidate form `DERIVED`; **at most 32 candidate PROJECTIONS**; selection of `X` remains **NORMATIVE** (F4) |
| 5 | **`Σ`** ordering *(NOT `K`)* | 🔴 unregistered | 🟡 **candidate product order over `Σ`**, conditional on 5 component orders, `A` normative. ⚠️ **`K_{t+1} ≻ K_t` remains UNREGISTERED and unstructured** (F5 narrowed) |
| 6 | `≈` set · `≅_λ` relevance | NORMATIVE | 🔴 **CONFIRMED NORMATIVE** — 0 hits in 116 docs (F6) |
| 7 | `Unknown` · `Σ`/`S` overloads | not registered | 🔴 **NEW UL items** (F7) |
| — | `Qualify` | 🔴 `G1` irreducible | **UNCHANGED** — nothing in the 116 supplies a body |
| — | `𝒪` vs the ratified 8 primitives | 🔴 open | **UNCHANGED** |

## Standing position, revised

> **Two of my four `D288` targets narrow materially, and neither narrows by invention.**
> `≈` gains a candidate parameter set from the axis structure (F4); `≡` is untouched and still awaits
> Decision 3; `≅_λ` is confirmed normative (F6); **`Qualify` remains the single irreducible blocker.**
>
> **And the pattern holds for the sixth time: the corpus was richer than the model built from it.**
> The `20260826` day raised the epistemic-status-algebra gap and **closed it the same afternoon**;
> the closure never propagated. **`Σ` was five-dimensional from the beginning.**
