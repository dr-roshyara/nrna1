---
artifact: FORMAL-SYSTEM-RECONSTRUCTION-AUDIT-245-NEW
mandate: 20260830_1110 §19 (21 required sections)
date: 2026-08-30
status: **DELIVERED — FORMALLY INCOMPLETE (§17 verdict)**
authority: verifier session (adversarial, independent)
evidence_class: B (executed counterexamples) + A (formal) + real-code reads
headline: |
  Four of the five components of Step 245's K_0 are REFUTED AS STATE COMPONENTS by executed
  counterexample: pi, lambda, sigma and theta all belong outside K. Only C survives, and only as a
  representation choice. This CONFIRMS Step 245's own boxed hedge [07] that "K_5 may be a
  projection/packaging of a richer typed structure rather than a set of five primitives."
---

# Formal System Reconstruction — attacking Step 245's `K₀`

## 1. Reconciliation of previous findings (§2)

| Earlier finding | Source | Current status | Does Step 245 agree? |
|---|---|---|---|
| `K` is undefined | 230.49 | **STANDS** | Yes — 245 attempts to fix it |
| K5 candidate | 230/242 | **STANDS** | Yes — 245 builds on it |
| `I* = {Provenance}` | verification | **STANDS** | Not addressed |
| lineage implemented in real code | code audit | **STANDS** (65 files, 47 tests) | Not addressed |
| `KnowledgeState` absent from production | code audit | **STANDS** (0 files) | Not addressed |
| Validation is an Assessment | 232.4 | **STANDS** | **Yes — and §7 below now shows σ is too** |
| Policy external to transformation | prior audits | **STANDS — reinforced by §5 below** | **NO — 245 puts π inside `K₀`** |
| `K₀` can be constructed | 245 | **CONFIRMED, but see §5** | — |
| `K = (Content, Qualification, Governance)` | 245 | **PARTIALLY REFUTED** | — |

**Only one genuine conflict: policy placement.** Everything else is different abstraction level or
unaddressed. **Classification: `GENUINE CONTRADICTION` between the prior audits and Step 245 on `π`.**
Resolved below by executed counterexample, in favour of the prior audits.

**Feedback-loop check (mandatory):** Step 245 fingerprinted against all verifier artifacts —
`(q,s,e,c,t,u)` 0 hits · "equality family" 0 · "identity blocker" 0 · `I*` 0 · `integrityHash` 0.
**Classification: `SOURCE-INDEPENDENT`.** Step 245 continues Step 232's own inner tuple `(G,σ,θ,λ,π)`,
not this programme's proposal. **Admissible as independent evidence.**

---

## 2. Audit of Step 245 — what it actually claims (§4)

**To Step 245's credit, it already hedges three of the mandate's nine concerns**, boxed in its own text:

- `[01]` **`Lineage direction = OPEN DESIGN QUESTION`**
- `[07]` **`K₅ may be a projection/packaging of a richer typed structure rather than a set of five primitives`**
- `[09]` `FORMAL CLOSURE — NOT YET PROVEN` · `[10]` `FINAL KERNEL — NOT YET SELECTED`

**Only `[08]` `PASS — Candidate Knowledge State successfully constructed` is a strong claim, and it is
narrowly true**: a candidate *was* constructed. The question is whether it is the right category of object.

---

## 3–4. Type universe and object classification (§3)

| Term | Formal type | Category |
|---|---|---|
| Proposition | atomic term `(s,p,o)` | **object** |
| Assertion | `(Proposition, Interval, Context)` | **object** — *distinct from Proposition, see §8* |
| Evidence | referenced set of opaque ids | **object**, external to `K` |
| Assessment | `Prop × Evidence × Context × Policy → Status` | **function** |
| Epistemic status | codomain of Assessment | **derived value**, not a primitive |
| Knowledge State `K` | set of Assertions | **object** |
| History | `K → Histories` | **function** |
| Policy, Authority | external parameters of `T` | **objects**, not state components |
| Transformation `T` | `K × Input × Policy × Authority → K` | **function** |
| Validation | `K × X → Assessment` | **function** (232.4) |

**`Knowledge` vs `Knowledge State` — mathematically different.** `Knowledge` is the *content* (a set of
propositions/assertions); `Knowledge State` is that content *at a point in the transition system*. The
corpus uses them interchangeably; **the distinction is `PROPOSED`, not corpus-established.**

---

## 5. §5 — ATTACK `π` (POLICY) · **REFUTED as a state component**

**Model B executed** — `K = (C,σ,θ,λ)` with policy as an argument to `T`:

```
T_extern(k, {evidence:{E2}}, policy="strict", authority="architect")  ->  applied
```

**The transition runs with `π` outside `K`. The state needs no policy field.**

**Counterexample against Model A (π internal):**

> If `π ∈ K`, then **revoking or amending a policy changes every stored state**, while nothing that is
> *known* has changed. Two states holding identical knowledge under different policies would be unequal.
>
> **Policy governs the transition, not what is known.**

**VERDICT: `π` is EXTERNAL. `MATHEMATICALLY VERIFIED` by counterexample.** This upholds the prior audits
and refutes Step 245 on the one point where they genuinely conflict.

---

## 6. §6 — ATTACK `λ` (LINEAGE) · **REFUTED as a state component**

**Constructed `K₁`, `K₂` with identical content, status, temporality and policy — differing only in history:**

```
K1.λ = (obs, src-A, 2026-01-01)
K2.λ = (obs, src-B, 2025-06-01), (revise, src-A, 2026-01-01)

content-equal : True
structural-equal : False
```

**The four equalities (§6 requires all four):**

| Relation | `K₁` vs `K₂` |
|---|---|
| structural | **False** |
| semantic | **True** |
| provenance-sensitive | **False** |
| historical identity | **False** |

> **Semantic equality is TRUE while historical identity is FALSE. They are different relations, and a
> single tuple containing `λ` cannot express both.**

With `λ` inside `K`, **structural equality becomes history-sensitive**, which breaks **deduplication** and
**merge** — two of the nine operations.

**VERDICT: `History(K) ≠ K` is fundamental. `λ` belongs OUTSIDE `K`, as `History : K → Histories`.**
`MATHEMATICALLY VERIFIED`. **This confirms Step 245's own `[01]`** — the open question resolves against
inclusion.

**And it aligns with the implementation:** the real code has a lineage *graph* separate from any state
object (65 files, 47 tests), and no `KnowledgeState` at all.

---

## 7. §7 — ATTACK `σ` (EPISTEMIC STATUS) · **REFUTED as a primitive**

**Executed:**
```
same proposition, different evidence : Observed  vs  Supported
same proposition + evidence, different policy : Observed  vs  Unknown
```

| Model | Verdict |
|---|---|
| **1.** `σ : Proposition → Status` | **REFUTED** — one input yields three different values |
| **2.** `σ : Prop × Evidence × Context → Status` | **REFUTED (insufficient)** — policy changes the outcome and is not an argument |
| **3.** `Assessment : Prop × Evidence × Context × Policy → Assessment`, `Status` derived | **SURVIVES** |

> **`σ` is not a primitive of the Knowledge State. It is derived from an Assessment.**

**This is exactly Step 232.4's own `Validation : 𝕂 × X → Assessment`, extended to status.** The corpus
already had the right shape and Step 245 re-internalised it.

---

## 8. §8 — ATTACK `θ` (TEMPORAL VALIDITY) · **REFUTED on the proposition**

**Counterexample:** the same proposition `Nexus.version = 3.69` carries **two assertion instances**:

```
assert-1 : valid 2026-01-01 .. 2026-06-01
assert-2 : valid 2026-03-01 .. ∞
```

**A function `θ : Proposition → Interval` cannot return two values for one input.**

**VERDICT: temporal validity belongs to the ASSERTION instance, not the proposition.**
`MATHEMATICALLY VERIFIED`. The four time dimensions (**valid / known / record / decision**) must be
preserved and are **not** collapsible to one timestamp — which the real implementation does collapse
(`decidedAt` only), an **IMPLEMENTATION GAP**.

---

## 9. §9 — ATTACK `C` (CONTENT AS GRAPH) · **representation, not ontology**

**`K₀`'s own content has `edges = ∅`.** A **set** suffices for the constructed example. The graph is
required only when *relations between propositions* must be expressed.

**VERDICT: `C = (V,E)` is a `REPRESENTATION CHOICE` justified by relational requirements — not an
ontological fact and not an implementation necessity.** Step 245 assumes it without deriving it.
`PROPOSED`, not `CORPUS ESTABLISHES`.

---

## 10–11. §10 — The state space, and what survives

**After four refutations, `K₀ = (C₀,σ₀,θ₀,λ₀,π₀)` reduces to:**

```
K = a set of Assertions,  where  Assertion = (Proposition, Interval, Context)
```

with everything else **outside**:

```
Evidence      : referenced, external
Assessment    : Prop × Evidence × Context × Policy → Status     (σ derived)
History       : K → Histories                                   (λ external)
Policy        : parameter of T                                  (π external)
Authority     : parameter of T
T             : K × Input × Policy × Authority → K
Validation    : K × X → Assessment
```

> ### **This is a TYPED FAMILY, not a tuple — precisely what §20 anticipated and what Step 245's own `[07]` suspected.**
>
> **`K₅` is a packaging of five things that belong to five different categories.**

**`Valid(K)` — still not computable.** Its predicates would need a designated status vocabulary
(**underdetermined**) and a decidable context-membership test (**undefined**). **`𝕂` remains incompletely
defined**, and `K₀ ∈ 𝕂` is therefore still not demonstrated — Step 245's §245.12 asks this and does not
answer it.

---

## 12–15. Concrete states, equality and transition

`K_A`, `K_B` and a real transition were constructed and executed in
`BLOCKER-ANALYSIS-AND-EXECUTABLE-KERNEL.md` (`K₀ → T → K₁`, provenance generated, three equalities
computed). **Under the corrected typing above, that kernel remains executable** — its `Item` carried
`e`, `c`, `t` and `u` but treated policy and authority as arguments to `T`, which §5 now vindicates.

**The one correction required:** its `s` (status) field must become a **derived** value from an
Assessment, not a stored primitive (§7).

---

## 16–17. Transformation algebra and DDD (§13, §15)

**Confirmed typings:**
```
T          : K × Input × Policy × Authority → K        (total on the admissible subset; partial overall)
Validate   : K × X → Assessment                        (NOT K → K)
Replay     : History × Input → K                       (NOT K → K)
Authorize  : Actor × Action × Context → Authorization  (NOT a state operation)
Govern     : Policy × Transformation → Assessment
History    : K → Histories
```
**Five of the ten "operations" are not state transformations at all.** This vindicates Step 232.4's
distinction and extends it to four further operations.

**UL flags (§15):** `Knowledge` vs `Knowledge State` — **UNDEFINED distinction** · `Proposition` vs
`Assertion` — **now REQUIRED and absent from the corpus** · `Provenance` vs `Lineage` vs `History` —
**OVERLOADED, three words used for what §6 shows are at least two distinct objects** · `Policy` vs
`Authority` — **STABLE** (correctly separated) · `Epistemic status` vs `Truth` — **STABLE**.

---

## 18. §16 — Engineering representation test

Applied to the real `GovernanceLineageNode` (executed last cycle): it maps to **History**, not to `K` —
carrying `decisionId`, `integrityHash`, `decidedAt`. **Under the corrected typing this is no longer an
anomaly: the implementation implements the History component of the typed family, and does not implement
`K`.** The category error was in mapping it to `K`, which this audit now corrects structurally rather than
by exception.

---

## 19–20. Contradictions and remaining blockers

**CONTRADICTIONS DISCOVERED**
1. Step 245 places `π` inside `K`; prior audits and §5 place it outside. **Resolved against 245.**
2. Step 245 places `λ` inside `K`; §6 and the implementation place it outside. **Resolved against 245** —
   and 245's own `[01]` flags it as open.
3. Step 245 treats `σ` as a stored component; §7 and Step 232.4 make it derived. **Resolved against 245.**

**BLOCKERS**
- **B1 — status vocabulary.** Still `UNDERDETERMINED BY CORPUS`. Normative. **§18 STOP.**
- **B2 — `Valid(K)` not computable.** Depends on B1 and on an undefined context-membership test.
- **B3 — `Proposition` vs `Assertion` is required by §8 and does not exist in the corpus.** A new
  primitive is now demanded by the mathematics. **§18 STOP: *"a proposed construct contradicts an earlier
  established result"*** — the corpus has treated them as one.

**GAPS** — four time dimensions collapsed to one in the implementation · `Assessment` untyped.
**DESIGN CHOICE** — graph vs set for `C`. **Both valid; requirements decide.**

---

## 21. §17 — Final verdict

> ## **FORMALLY INCOMPLETE**
>
> Not *inconsistent*: no contradiction survives once the typed family replaces the tuple.
> Not *closed*: `Valid(K)` is not computable and `Proposition ≠ Assertion` is undefined.

| Completeness dimension | Status |
|---|---|
| Conceptual | **HIGH** — the typed family is coherent and derived from requirements |
| Mathematical | **INCOMPLETE** — `Valid(K)` uncomputable; one primitive missing |
| Computational | **PARTIAL** — a transition executes; validity does not |
| Statistical / epistemic | **INCOMPLETE** — no probability space; uncertainty untyped |
| DDD semantic | **INCOMPLETE** — `Knowledge`/`Knowledge State` and `Proposition`/`Assertion` undefined |
| Engineering specification | **PARTIAL** |
| Implementation conformance | **PARTIAL** — History implemented; `K` absent |
| Empirical validation | **PARTIAL** — 57 tests executed; one structure confirmed |

**What survives the attack:** `C` as content (representation choice open) · the transition rule with
external policy and authority · `Validation → Assessment` · `History` as an external function ·
bitemporality as a property of assertions.

**What does not:** `π`, `λ`, `σ` and `θ` as components of `K` — **four of five.**

**STOPPED per §18** on three conditions: an undefined type distinction (`Proposition`/`Assertion`), an
uncomputable validity predicate, and an unresolved normative vocabulary. **Nothing patched silently; no
kernel label assigned.**
