---
artifact: REFINED STEP 287 — EQUALITY, IDENTITY, SEMANTICS, OBSERVABILITY
supersedes: `20260831-190114_step_287_reviewer-b-equality-identity-semantics-observability-research-mandate.md`
date: 2026-08-31
version: **vNext** (precision correction, not conceptual expansion)
verdict: **`OPEN` — four named corpus relations · TWO specified — `=` (**state** level) and `≡_D` (**value** level) · THREE partially specified. Equality relations are corpus CONSTRUCTS, not ratified primitives.**
audits: `11-STEP-287-AUDITS-P1-P9.md` (9 audits) · `12-FINAL-STEP-287-CLOSURE-AUDIT.md` (**8 findings, all applied**)
numbering: ⚠️ **CONTESTED — see §0a.** Three sources place equality at **285** and Invariants at **287**
amended: 2026-08-31 (a) — phrasing aligned with the 285/286 joint review: *not established* ≠ *proven impossible*
amended: 2026-08-31 (b) — **3 reviews applied: 4 RED + 4 ORANGE issues + 4 surgical edits.** ⚠️ **Issue 4 was a real error of mine: `Σ`-order and `K`-order conflated**
freeze_status: **NOT FROZEN — reviewer: "I would not freeze Step 287 yet." Remains `OPEN`**
---

# Refined Step 287 vNext — Equality, Identity, Semantics, Observability

## 0a. ⚠️ NUMBERING NOTICE — the identifier is contested

**Three sources** (`…201424`, `…202424`, and the 20:38 sequencing review) place **equality at Step 285**
and **Invariants `𝓘` at Step 287**. **This artifact is the EQUALITY analysis.** The Invariants work is
`REFINED-STEP-287-INVARIANTS.md`.

> **Both bodies of work are real. Neither absorbs the other. The identifier collision is a REGISTRY
> decision, not a research one — and it is the same defect class as the `GK-16` collision (`07`):
> one identifier, two referents.**
>
> **Until resolved, cite by SUBJECT:** *287-EQUALITY* (this file) · *287-INVARIANTS*.
> **Nothing is renumbered.**

## CHANGE CONTROL — every substantive change from the previous version, and why

| # | Change | Why necessary |
|---|---|---|
| **1** | §4 **heading**: *"`K_{t+1} ≻ K_t` — a product partial order"* → *"`Σ` — a CANDIDATE product partial order · and NOT `K_{t+1} ≻ K_t`"* | 🔴 **the heading asserted exactly what its own body refutes.** A live self-contradiction (P1 issue 1) |
| **2** | §3: *"32 candidate relations"* → *"32 candidate **projections**, inducing **at most** 32 distinct relations"* | different `X` can induce the same partition when an axis is degenerate; *"32 relations"* overcounts (P5) |
| **3** | §3: added that `≈_X` **is** an equivalence relation for every `X`, with the reason | previously asserted without proof; it is a pullback of equality along `π_X`, hence automatically reflexive/symmetric/transitive (P2) |
| **4** | §3: *"observations"* → *"candidate observable **labels / projections**"* | the five axes **bound the search space**; they do not establish that KnowledgeOS defines an observation as reading `Σ` coordinates (P5) |
| **5** | §4: added the **per-axis order audit** — **zero of five established**, `V` and `C` not even plausibly total | *"conditional on component orders"* was true but unquantified. **`Unknown` is incomparable on `V`; `Resolved` is a different kind of state on `C`, not "more conflicted"** (P6) |
| **6** | §8: *"UNDER-SPECIFIED"* → the **six-column decomposition**, and the finding that it merges two deficits | *"under-specified"* is fair but too coarse: `≡`/`≈`/`≅_λ` are simultaneously **normative-unresolved** *and* **technically incomplete** — different repairs (P8) |
| **7** | §8: explicit warning that **value-level `≡_D` is not evidence that state-level `≡` is specified** | the only relation with a procedure is at the wrong level; citing it upward would be a level error (P3) |
| **8** | new §13 · **Step-288 boundary** — CLOSED / BOUNDED / DEFERRED | prevents 288 solving what 287 deliberately leaves open (P9) |
| **9** | new §14 · **explicit non-closure statement** | mandated: 287 must state that it does not close the equality contract |
| **10** | §12: two rows added (`32 relations`, `observations`) | the discipline table must cover the new corrections |

**Fixed OUTSIDE this artifact, in the same pass** *(P7 propagation sweep)*: `08` F5 heading + body +
register row · `00-INDEX` sixth headline. **Two files had contradicted themselves.**

**Not done, deliberately:** no new primitives · no new canonical relations · no normative decisions ·
no external literature as architectural evidence · **Step 288 not solved** · no claim added to make the
document look complete.

> **Refined against three things the original mandate did not have:** the corpus's own four relations
> (Step 246), the five-axis `Σ` (Q4A), and the corpus's own stop-gate (Step 261 §261.23).

## 1. The four corpus relations

`CORPUS` — **Step 246**, at state level, with *"These are not interchangeable"* — ⚠️ **but Step 246 is NOT the origin: `25I.11` (2026-08-28) states the same warning over three relations, `≡_exact`/`≡_struct`/`≡_sem,C`, and indexes semantic equality by context. See `14-GAP-UPDATE-FROM-THE-025-ALGEBRA-SEAM.md` §1.**

| | Relation | Corpus definition | Decision procedure? |
|---|---|---|---|
| **A** | `K₁ = K₂` **structural** | *"byte-for-byte or structurally identical"* | ✅ **yes** |
| **B** | `K₁ ≡ K₂` **semantic** | *"encode the same knowledge semantics"* | 🔴 **no — a name only** |
| **C** | `K₁ ≈ K₂` **observational** | *"every permitted observation produces the same result"* | 🔴 **no set declared** |
| **D** | `K₁ ≅_λ K₂` **provenance-sensitive** | *"content and relevant provenance are equivalent"* | 🔴 ***"relevant"* undefined** |

plus `CORPUS` **264.15** at value level: `v₁ ≡_D v₂`, *"equivalence under the semantics of dimension
`D`"* — `3m ≢_str 300cm` but `3m ≡_D 300cm`. **The only relation in the whole family with a working
procedure, and it is not at state level.**

⚠️ **`history ⊊ structural ⊊ semantic` is NOT corpus.** Measured: it occurs **only** in
verification-lane artifacts. **`history` is not among the four.** My earlier citation is withdrawn.

## 2. The blocking decision, named

`CORPUS` — **Step 254 Decision 3, verbatim OPEN:**
> *"Is governance/authority part of semantic equality? Should `K₁ = K₂` require the same
> Authority/Policy/Governance, or only the same epistemic content?"*

| | `Π` **INSIDE** `≡` | `Π` **OUTSIDE** `≡` |
|---|---|---|
| `D285-5`'s property | **VACUOUS** | **SUBSTANTIVE** |
| `≅_λ` vs `≡` | collapse into one | remain distinct |
| `D285-6`'s projection | **illegitimate as stated** | legitimate |
| provenance-blindness | impossible by construction | needs another mechanism |

> **Both branches are formally consistent. The choice is about what KnowledgeOS wants equality to
> *mean* — which is exactly why the corpus left it open. No recommendation is offered.**

## 3. ⭐ `≈` narrows — the five axes bound a CANDIDATE observation space

`DERIVED` — With `Σ = (A,S,R,V,C)` (Q4A, 2240 states), observational equality is **projection onto an
axis subset**:

$$\Sigma_1 \approx_X \Sigma_2 \iff \pi_X(\Sigma_1) = \pi_X(\Sigma_2), \qquad X \subseteq \{A,S,R,V,C\}$$

> ⚠️ **CORRECTED (Issue 1).** I wrote *"the axes ARE the observations."* **That is not established by
> Q4A.** What Q4A establishes: `Σ = (A,S,R,V,C)` exists; these are **dimensions of epistemic state**; a
> projection onto selected axes is **mathematically definable**. What it does **not** establish: that
> **KnowledgeOS defines an "observation" as observing one or more `Σ` coordinates.** Different
> propositions.
>
> **Corrected statement:** *the five axes provide a **bounded candidate space** for state-level
> observations.* **`≈_X` is a `DERIVED` candidate form for ONE possible label-level interpretation of
> observational equivalence — not a derivation of the corpus's `≈` itself.**
>
> **The selection of `X` remains NORMATIVE.**

**Two boundary cases, qualified (Issues 2 and 3):**

| Case | ⚠️ What it is NOT | What it is |
|---|---|---|
| `≈_∅` | **not** *"all equal"* in any useful sense | the **universal relation** induced by an empty observation set — mathematically true, **not a useful observational equivalence** |
| `≈_{A,S,R,V,C}` | **not** *"structural equality"* — the corpus defines structural equality at **`K` level**, not `Σ` level | **componentwise equality on the five-axis `Σ` representation.** Do not identify the two unless the corpus establishes it |

**Formal check (P2):** `≈_X` **is** an equivalence relation for **every** `X` — it is the pullback of
equality along `π_X`, hence automatically reflexive, symmetric and transitive. **No `X` needs checking
individually.**

⚠️ **Counting precision (P5):** `|𝒫({A,S,R,V,C})| = 32` **subsets**, so **32 candidate projections** —
but **not necessarily 32 distinct relations**, since different `X` induce the same partition when an
axis is degenerate. **Correct: "32 candidate projections, inducing at most 32 distinct relations."**

⚠️ **And `δ` is not required** to define `≈_X` — it is label-level. *(Behavioural equivalence is a
**different relation**, over **histories**, and it is **corpus-native at Step 260** — see the
correction below.)*

**Net:** from *"NORMATIVE parameter, undefined"* to *"a `DERIVED` candidate form plus a bounded
normative choice over at most 32 options"* — **and the form is one candidate interpretation of
observable labels, not the corpus's relation itself.**

⚠️ **External-literature precision** (classification only, **not** imported as architecture): in
process algebra, *observational equivalence* is characterised by **weak bisimulation** — equality of
observable **behaviour under transitions**. **`≈_X` as defined here compares observable *labels*, not
behaviour.** So it is the weaker, label-projection sense. **`≈_X` will not supply behavioural
equivalence** — the two senses must never be conflated.

> ### ⚠️ CORRECTION (2026-08-31, `REFINED-STEP-288.md` §1) — behavioural equivalence IS in the corpus
>
> The sentence this section previously carried — *"if KnowledgeOS ever wants behavioural
> equivalence"* — **treated it as absent. It is not.** `CORPUS` **Step 260** (*minimality of the
> knowledge state*) makes it **the foundation of minimality**:
>
> - it distinguishes **candidate** `≡_𝒯^cand` from **proven** `≡_𝒯^prov` behavioural equivalence, and
>   states *"At this stage we have the former"*;
> - *"the candidate state is therefore **an equivalence class of histories**"* — so the relation is
>   over `ℋ`, **not over `𝕂`**, which is why none of Step 246's four state-level relations is it;
> - §260.11: *"**But the quotient is not automatically implementable**."*
>
> **Consequence for this artifact:** there are **five** relations in play, not four, and the fifth is
> **upstream of `K`** (`K` is proposed as `ℋ/≡_𝒯`) rather than downstream of `δ`. It inherits `𝒪`'s
> defect: `𝒯` has never been enumerated against the ratified 8 primitives.
> *The label-vs-behaviour distinction above stands unchanged; only the claim of absence is withdrawn.*

## 4. `Σ` — a CONDITIONAL product-order CONSTRUCTION · and NOT `K_{t+1} ≻ K_t`

The corpus left the structure explicitly open: *"partial order, lattice, bilattice, belief-revision
structure, or something else — that mathematical question should remain open."*

`DERIVED` — **A product-order CONSTRUCTION over `Σ` is available as a candidate, conditional on
independently establishing suitable component relations for all five axes:**

$$\Sigma_1 \preceq \Sigma_2 \iff \bigwedge_{i \in \{A,S,R,V,C\}} \big(\, \Sigma_1[i] \preceq_i \Sigma_2[i] \,\big)$$

> ⚠️ **CORRECTED (Issue 5).** *"Partial by construction"* was premature. **A product partial order is
> available ONCE PER-AXIS PARTIAL ORDERS ARE DECLARED** — and `A` is not obviously ordered. What
> survives unchanged: **non-totality SATISFIES rather than refutes the objection that
> `Observed ≮ Conflicting`** — they sit on different axes, hence **incomparable**, not mis-ordered.

### Per-axis order audit — ZERO of five are established (P6)

| Axis | Order established? |
|---|---|
| **S** Support | 🟡 plausibly total — numeric bands exist, **but `no averaging` makes them ordinal labels, not a metric** |
| **R** Resolution | 🟡 partial at best — **`Unresolvable` is a terminal side-state, not "more resolved"** |
| **V** Validity | 🔴 **NOT ordered** — `Current > Stale > Expired` is plausible; **`Unknown` is incomparable to all three** |
| **C** Conflict | 🔴 **NOT ordered** — *"Resolved"* is not *"more conflicted"* than *"Active"*; it is a **different kind** of state |
| **A** Acquisition | 🔴 **NO ORDER.** Is `Observed ⪰ Reported`? **NORMATIVE** — the hard one |

> **Zero of five established; two not even plausibly total.** So *"partiality follows"* is itself
> conditional: **a product is a partial order only once every component is shown to be one**, and a
> lattice only if every component is a lattice. **No lattice claim is made here.**
>
> And *"more knowledge"* ≠ *"higher `Σ`"* — the warning against equating knowledge growth with counting
> stands. **Nothing in this section bears on monotonicity of `K` evolution.**

### ⚠️⚠️ Issue 4 — A REAL ERROR OF MINE: two different orderings were conflated

**I moved from `Σ₁ ⪯ Σ₂` to `K_{t+1} ≻ K_t` as though they were one object. They are not.**

| | Object | Established? |
|---|---|---|
| **candidate epistemic-state order** | `⪯` over `Σ = (A,S,R,V,C)` | 🟡 **available once component orders are declared** |
| **knowledge-state growth order** | `K_{t+1} ≻ K_t` over KnowledgeOS **knowledge states** | 🔴 **NOT established, and NOT reducible to the above** |

**Q4A gives a possible ordering over EPISTEMIC STATES. It establishes no ordering over knowledge
states `K`** — which may involve content · provenance · history · assertions · governance · deletion
and retraction · contradiction resolution. **None of those is a `Σ` coordinate.**

> **Corrected conclusion:** *a product partial-order structure is mathematically **available** over
> `Σ`; the KnowledgeOS **knowledge-state** order remains **under-specified**, and the two must not be
> identified.*
>
> **This was my error, not a reviewer's tightening.** My `08` finding F5 claimed `K_{t+1} ≻ K_t` "gains
> a structure." **It does not — `Σ` does.** `08` F5 is hereby narrowed.

⚠️ **Not AGM either.** AGM orders **beliefs** by entrenchment; the product order orders **epistemic
states** componentwise. **Different objects — the AGM postulates do not transfer.** Recorded so the
standard theory is not cited as support it does not give.

## 5. `D285-5` revalidated under all four

| Relation | Antecedent can hold? | Property | Status |
|---|---|---|---|
| structural | 🔴 no (`Π ∈ id`) | — | **VACUOUS** |
| semantic | ✅ yes | yes | **SUBSTANTIVE, conditional on Decision 3** |
| observational | ✅ yes | relative | **UNDEFINED as stated** |
| **provenance-sensitive** | 🔴 no | — | **VACUOUS — and here `δ` IS injective** |
| *history* | — | — | **NOT A CORPUS RELATION** |

**What `D285-5` establishes, exactly:** ∃ `o₁ ≠ o₂`, ∃ `K₀` with `δ(K₀,o₁) ≡ δ(K₀,o₂)` under a
projection discarding `Π`. **Non-injectivity MODULO A QUOTIENT** — a property of `δ ∘ q`, **not of
`δ`.** The strong wording is **SUPERSEDED**.

## 6. Five identity notions, one defined

`state identity` ✅ (`id = H(P,e,c,t,Π)`; **`K_t` itself has none**) · `operation identity` 🔴 ·
`authority-act identity` 🔴 (`Grant` has `grantId`; the **act** has none) · `event identity` 🔴 (no
schema) · `provenance identity` ✅ (`Π`, `t=0`-safe).

> **`D285-5` does NOT imply a general identity rule.** Its property concerns an equivalence on
> **states**; the `grantId` collision is a key collision on **authority acts**. **Different layers —
> the citation linking them is withdrawn.**

## 7. Independence

| Route | Reaches the equality result? |
|---|---|
| **KnowledgeOS** | ✅ **entirely** — from `id = H(P,e,c,t,Π)` and Step 246. **Derivable from typing alone** |
| **Gītā** | 🔴 **NO** — 2.47–2.48 says nothing about equality relations on states |

> **`R6` — independently required, with ZERO philosophical content.** The cleanest case of
> `corroboration ≠ derivation` in the programme: here there is not even corroboration.

## 8. The final question, answered from the corpus

> **Is equality a missing primitive, or derivable from the corpus?**
>
> ## **NEITHER. The corpus contains the RELATIONS and not their DECISION PROCEDURES.**
>
> Four relations, declared non-interchangeable ⇒ **not missing**. Three of four unevaluable ⇒ **not
> derivable as stated**.
>
> ⚠️ **CORRECTED terminology:** **corpus CONSTRUCTS**, *not* *"primitive."* Calling equality a
> **primitive** would imply membership of the **ratified primitive ontology**, which is not
> established. *(The eight are `{Entity, State, Event, Observation, Proposition, Relation, Policy,
> Action}` — equality is not among them.)*

### ⚠️ *"Under-specified"* is fair but TOO COARSE — it merges two different deficits (P8)

| Relation | exists | defined | decision proc. | semantic commitment | normative selection | impl. semantics |
|---|---|---|---|---|---|---|
| **`=`** structural (K) | ✅ | ✅ | ✅ | ✅ | n/a | 🟡 |
| **`≡`** semantic (K) | ✅ | 🟡 name only | 🔴 | 🔴 **Decision 3** | 🔴 | 🔴 |
| **`≈`** observational (K) | ✅ | 🟡 schema only | 🔴 | 🔴 | 🔴 **`X` unselected** | 🔴 |
| **`≅_λ`** provenance (K) | ✅ | 🟡 `λ` unbound | 🔴 | 🔴 | 🔴 | 🔴 |
| **`≡_D`** value-level | ✅ | ✅ | ✅ | ✅ | n/a | 🟡 |

> ### The strongest formulation that does not overclaim
> **`=` and `≡_D` are SPECIFIED. `≡`, `≈` and `≅_λ` are PARTIALLY SPECIFIED — each exists and is named
> with a schema, and each is simultaneously (i) NORMATIVE-UNRESOLVED in its semantic commitment and
> (ii) TECHNICALLY INCOMPLETE in its decision procedure.**
>
> **Nothing is missing. Nothing is absent.** Those are two different repairs and they must not be
> merged under one label.
>
> ⚠️ **And `≡_D` (value level) is NOT evidence that `≡` (state level) is specified.** The only relation
> with a procedure sits at the wrong level; citing it upward is a level error (P3).

**Structurally the same shape of gap as `Qualify`** — a named construct without a body.

## 9. Corroboration the original mandate did not have

`CORPUS` — **Step 261 §261.23** declines to declare `≡_K` final and lists six reasons, including
*"the observation set is not fully closed"* and *"the operation registry is not fully closed"*, then:
> *"final kernel selection must stop while equality remains ambiguous. **We therefore obey that gate.**"*

> **The research lane reached this same stop-gate by its own route, before this programme existed.**
> The equality gap is **not** a verification-lane discovery. **What this pass adds is the per-relation
> decision-procedure audit — and, new, the `≈_X` and product-order narrowings.**

## 10. What closes it

### The three normative dependencies, explicit (Edit 4)

```
1. Π ∈ ≡ ?                      — Governance
2. X ⊆ {A,S,R,V,C} ?            — Governance
3. per-axis orders ⪯_A … ⪯_C ?  — Governance (A is the hard one)
```

### ⚠️ These do NOT close equality (review 2's correction, accepted)

> **They close the currently identified branches. They are NOT, by themselves, proof that the complete
> equality implementation contract is closed.**

**Still outstanding after all three:** decision procedures for `≡` and `≅_λ` · precise **state
identity** · equality **implementation semantics** · the component orders themselves · and eventual
interaction with `δ`/operations.

> **Recorded so a later Step 288 cannot prematurely declare the equality blocker solved.**

### `≅_λ` — principle ≠ predicate (Issue 8)

`≅_λ`'s *"relevant"* has one corpus-supported constraint — `CORPUS` step-105: *"what matters is
**decision-relevant** provenance, not private chain-of-thought."*

> ⚠️ **That is a relevance PRINCIPLE, not a relevance PREDICATE.** A future researcher must not treat
> *"decision-relevant"* as a definition of `λ`. **It constrains the answer; it is not the answer.**

## 11. Discipline

No relation invented · nothing promoted · **`=_semantic` explicitly NOT elevated** · `Sañjaya = Ω` not
treated as canonical · `Qualify` not solved by assumption · external literature used for
**classification only**, never as architecture.

---

## 12. Phrasing discipline carried from the 285/286 joint review

Three wordings are now binding across the 285–287 package, because each states something **weaker**
than the earlier form and the weaker form is what the evidence supports:

| ❌ Do not write | ✅ Write |
|---|---|
| *"`π_K` is not computable"* | *"the projection relation is **definable**; a computable projection is **NOT ESTABLISHED** and is currently blocked by `Qualify`"* — the first reads as a **non-computability theorem** |
| *"a total `Qualify` cannot exist"* | *"`Qualify` **may** legitimately terminate at an explicit boundary rather than being total"* — `[H]`, not a proof |
| *"three candidate primitives"* | *"three **innovation hypotheses**; none is a candidate primitive yet"* |
| *"the axes ARE the observations"* | *"the five axes provide a **bounded candidate space** for state-level observations; selection of `X` remains **normative**"* |
| *"`≈_{A,S,R,V,C}` = structural on `Σ`"* | *"**componentwise equality** on the five-axis `Σ` representation"* — corpus structural equality is at **`K`** level |
| *"partial by construction"* | *"a product partial order is **available once per-axis orders are declared**"* |
| *"`K_{t+1} ≻ K_t` gains a structure"* | *"**`Σ`** gains a candidate order; the **knowledge-state** order remains under-specified"* — ⚠️ **two different objects** |
| *"equality is an under-specified corpus **primitive**"* | *"equality relations are under-specified corpus **constructs**"* — *primitive* would imply ratified-ontology membership |
| *"32 candidate relations"* | *"32 candidate **projections**, inducing **at most** 32 distinct relations"* — different `X` can induce the same partition |
| *"the axes are the **observations**"* | *"candidate observable **labels / projections**"* |
| *"partiality follows"* | *"partiality follows **once every component is shown to be a partial order**"* — 0 of 5 are |
| *"a ruling on Decision 3 + an axis subset **closes it**"* | *"closes the **currently identified branches** — not proof that the complete equality contract is closed"* |

> **The pattern is one rule:** *"not established"* ≠ *"proven impossible"*, and *"a source motivates a
> hypothesis"* ≠ *"a hypothesis is established."* **This is the equality discipline generalised from
> relations to claims** — which is what `D285-5` was really about all along.

**And one separation, binding:** the **`Acknowledgment`** hypothesis and the **qualification-terminus**
hypothesis are **two tracks with no shared object** (`REFINED-STEP-286` §6b). Merging them would let a
philosophical argument smuggle `Acknowledgment` into `Qualify`'s implementation.

---

## 13. Step-288 boundary (P9)

| **CLOSED by 287** | **BOUNDED but OPEN** | **DEFERRED to 288+** |
|---|---|---|
| four relations exist, non-interchangeable | `≈`: candidate family `≈_X`, **`X` unselected** | `≡` decision procedure |
| `history` is **not** a corpus relation | `Σ` order: conditional on **5** component orders, **0 established** | `≅_λ` decision procedure |
| `structural ⊊ semantic` witnessed strict | `≅_λ`: relevance **principle**, no predicate | **`Π ∈ ≡?`** *(Governance)* |
| `D285-5` scoped to a quotient | `≡`: named, no procedure | **`X ⊆ {A,S,R,V,C}?`** *(Governance)* |
| **`Σ`-order ≠ `K`-order** | | **per-axis orders** *(Governance — `A` hardest)* |
| equality independent of the Gītā (`R6`) | | state · operation · authority-act · event identity |
| `≡_D` has a procedure — **value level only** | | implementation semantics · interaction with `δ` · behavioural observational equivalence · **`K`-order** |

## 14. Explicit non-closure

> **Step 287 does NOT close equality.** It establishes exactly what is known, what is bounded, what is
> normative, and what remains technically unspecified.
>
> **The corpus has named equality-related constructs but has not supplied the decision procedures and
> semantic commitments required to make them executable.**

---

## STATUS

**`OPEN`**

### ESTABLISHED
- four corpus relations exist at state level and are **declared non-interchangeable** (`CORPUS` 246)
- `=` structural (**state** level) and `≡_D` (**value** level) are **specified**, with decision procedures — ⚠️ **different levels; neither implies the other**
- `structural ⊊ semantic` — **witnessed strict**
- `history` is **not** a corpus relation; the three-element chain is a verification-lane construct
- `D285-5` is **quotient-scoped**: non-injectivity of `δ ∘ q`, **not of `δ`**
- **`Σ`-ordering is not `K`-ordering**
- equality is **`R6`** — independently required by KnowledgeOS, **zero philosophical content**
- Step 261 §261.23 **stop-gate** stands: kernel selection halts while equality is ambiguous

### BOUNDED
- `≈` → the candidate family `≈_X`, an equivalence relation for every `X`, over **at most 32** projections
- `Σ` order → a **conditional** product construction over five axes
- `≅_λ` → constrained by the **decision-relevance principle**

### NORMATIVE
- `Π ∈ ≡ ?` *(Step 254 Decision 3)*
- `X ⊆ {A,S,R,V,C}` — which axes are observable
- the five per-axis orders — **`A` (Acquisition) hardest; zero established**

### TECHNICALLY OPEN
- decision procedures for `≡` and `≅_λ`
- precise **state identity**; and operation · authority-act · event identity
- equality **implementation semantics**
- interaction with `δ`
- **`≡_𝒯^prov`** — *proven* behavioural equivalence; the corpus holds only `≡_𝒯^cand` (Step 260), and `𝒯` is unenumerated
- **`K`-order** — no candidate structure at all

### DEFERRED
- all of the above to **Step 288**, which must consume **this corrected version**, not the previous one

---

**Step 287 establishes the boundary of the equality problem; it does not establish the equality contract.**
