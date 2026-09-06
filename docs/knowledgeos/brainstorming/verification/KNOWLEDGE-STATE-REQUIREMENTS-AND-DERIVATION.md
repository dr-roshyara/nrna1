---
artifact: KNOWLEDGE-STATE-REQUIREMENTS-AND-DERIVATION
mandate: 20260830_1828 — execution order A→O
date: 2026-08-30
status: **DELIVERED — STOP AT O for supervision**
authority: verifier session (adversarial, independent)
evidence_class: B (four executed programs) + A (formal) + corpus sources
headline: |
  BOTH "normative" questions are DERIVED, not chosen. No user decision is required for either.
  K = (𝒜, ℛ) with Assertion = (id, P, e, c, t, Π), minimality PROVEN by removal counterexample.
  Σ = {Unknown, Supported, Refuted}, derived from Assessment, with Γ orthogonal.
  Valid(K) is now COMPUTABLE. Equality is a proven equivalence relation.
---

# Knowledge-State Requirements and Derivation

## 0. Headline

> **The mandate asked how much of the two "normative" choices could still be derived. The answer is: all of
> it. Neither `Σ` nor `K` requires a user decision.**

| Question | Status before | Status now |
|---|---|---|
| `Σ` — status vocabulary | *appeared normative* | **DERIVED** — `{Unknown, Supported, Refuted}` |
| `K_t` — state shape | *appeared normative* | **DERIVED** — `K = (𝒜, ℛ)` |
| `Valid(K)` | **UNCOMPUTABLE** (the programme's longest-standing blocker) | **COMPUTABLE** — executed |
| Equality on `K` | **UNDEFINED** | **PROVEN EQUIVALENCE RELATION** — executed |

---

## A. Frozen state (§1)

| Class | Content |
|---|---|
| **CORPUS ESTABLISHES** | Q7's `P → A → K_t` chain (2026-08-26) · `A = (P,Σ,E,τ,Π)` · assertion `id` (025a-2) · `Superseded` is a relation (218.21) · `Deprecated ≠ Falsified` (218.22) · `EpistemicStrength ≠ GovernanceStatus` (194.19) · bitemporality (185) · `Validation → Assessment` (232.4) · `𝒯_G` not closed (232.19) |
| **FORMALLY PROVEN** (executed) | `π`,`λ`,`σ`,`θ` are not state components · `History(K) ≠ K` · Σ minimal at 3 · Σ ⊥ Γ · asserted relations not reconstructible · equality is an equivalence relation |
| **EMPIRICALLY SUPPORTED** | typed provenance graph implemented, 47 tests passing · 57 tests / 159 assertions executed |
| **DESIGN CHOICE** | none outstanding for `K` or `Σ` |
| **UNRESOLVED** | uncertainty representation · `Γ`'s value set · `P = (E,D,V)` internals |
| **REFUTED** | Step 245's `K₀ = (C,σ,θ,λ,π)` — four of five components |

---

## B. Knowledge-State Requirements Register (§2) — EXECUTED

25 capabilities, each answered against the eight mandated questions. **Placement is derived, not assigned.**

| Placement | Capabilities |
|---|---|
| **IN `K`** | assertion representation · evidence *association* · provenance `Π` · context `c` · temporal validity `t` · identity `id` · membership · missingness · **relations between assertions** |
| **EXTERNAL ASSESSMENT** | epistemic status `Σ` · uncertainty `u` · unknown · validation |
| **RELATION over `K` (derived)** | contradiction · equality · merge |
| **PROPERTY OF `T`** | state transition · authority · governance `Γ` · determinism · composability |
| **PROPERTY OF HISTORY** | replay · lineage `λ` |

**Circularity check — five capabilities would create a cycle if placed inside `K`:**
`supersession · replay · authority · governance · lineage`.
**This is the same cycle set found by the earlier dependency analysis, reached independently.**

---

## C. Kernel-lineage reconciliation (§3)

| Primitive | Required in `K`? | Required outside? | Relationship between the two lineages |
|---|---|---|---|
| artifact / assertion | **YES** | — | **ALIASES** — the artifact lineage's *artifact* is the state lineage's content-bearer |
| type | no | in `P` | **ABSTRACTION LEVEL** — typing of the proposition |
| event | no | History | **PROJECTION** of `T` |
| invariant | no | predicate over `𝕂` | **DIFFERENT LEVEL** — a predicate, not a component |
| provenance | **YES** (`Π`) | also History | **GENUINELY DIFFERENT**: `Π` = origin of an assertion; History = sequence of `T` |
| transformation | no | `T` | shared by both lineages |
| policy · authority | no | `T` args | **ORTHOGONAL DIMENSION** (Γ) |
| context | **YES** (`c`) | — | state-lineage only |
| evidence | **YES** (as reference) | objects external | **INCORRECTLY CONFLATED** in Model F — reference vs object |
| identity | **YES** (`id`) | — | present in 073 and in the implementation; absent from 230/232 |
| status | no | Assessment | **INCORRECTLY CONFLATED** — five kinds in one field |

> **The two lineages are not competing theories. The artifact lineage names the *content-bearers*; the
> state lineage names the *governing structures*. They are complementary, and the reconciliation is
> `artifact ↦ assertion`, `invariant ↦ predicate`, `event ↦ projection of T`.**

---

## D–F. The derivation of `K` (§4, §5) — EXECUTED

### Stage 0 → 1 · FORCED
`K = Set(Proposition)` fails: the same proposition carries **two validity intervals** (executed
counterexample). **Forced: wrap in Assertion.**

### Stage 1 · removal test on all six assertion fields — every one NECESSARY

| Remove | Breaks | Why irrecoverable |
|---|---|---|
| `id` | supersession, dedup, replay, merge | two assertions with identical fields are indistinguishable |
| `P` | everything | the content |
| `e` | provenance link, Assessment input | not derivable from the proposition |
| `c` | context scoping, contradiction | 230.9: same prop, different context is **not** a contradiction |
| `t` | supersession, replay, temporal query | two intervals per proposition impossible without it |
| `Π` | lineage anchor at `t=0` | external provenance not derivable from `History(T)` |

### Stage 2 · **ℛ is FORCED** — the decisive counterexample

> `A₁ = "v = 3.69"`, `A₂ = "v = 3.70"`.
> **Is `A₂` a supersession of `A₁`, or an independent observation in another context?**
> **Both readings are consistent with the same two assertions.**

**Asserted relations carry information not present in the assertions.** `K = Set(Assertion)` is therefore
**INADEQUATE**. Derived relations (`contradicts`, `equals`) are computable; **asserted** ones
(`supports`, `supersedes`, `derivedFrom`, `refines`, `resolves`) are not.

### Stage 3 · nothing further is forced
Evidence objects (**no** — re-creates Model F's double-binding) · History (**no** — executed
counterexample) · Zero (**no** — a lens, 216.9/230.45) · lineage (**no** — History) · policy (**no** —
executed) · status (**no** — derived).

### RESULT

```
K = (𝒜, ℛ)
    𝒜 = Set(Assertion),   Assertion = (id, P, e, c, t, Π)
    ℛ ⊆ 𝒜 × 𝒜 × RelationType     (ASSERTED relations only)
```

> **MINIMALITY: PROVEN.** Each of the six fields has a removal counterexample; `ℛ` has a
> non-reconstructibility counterexample; and each of the six candidate additions is shown *not* forced.
> **This is the first "minimal" claim in the programme that is not merely plausible.**

**Relation to Q7:** Q7's `K_t = (𝒜,ℛ,ℰ,ℋ,𝒵,ℒ)` **reduces to `(𝒜,ℛ)`. Q7 was over-specified by four
components** — evidence-objects, history, Zero and lineage all belong outside. **Q7 got the *levels* right
and the *container* too large.**

---

## E. Equality, membership, transition (§4.3–4.5) — EXECUTED

```
order-independence : True      (frozenset — insertion order structurally cannot matter)
reflexive=True  symmetric=True  transitive=True   => GENUINE EQUIVALENCE RELATION
history-independence : structural — K has no history field
membership a ∈ K : decidable, O(1)
δ(K,o) : PARTIAL (rejects), DETERMINISTIC, CLOSED
   assert a3                    -> ok,       |𝒜|=2
   relate a3 supersedes a1      -> ok,       |ℛ|=1
   relate to missing endpoint   -> REJECTED: endpoint not in K
```

**Two equalities, both computable and both needed:**
- **structural** — includes `Π`, so two assertions differing only in provenance are unequal. *A deliberate
  consequence of `Π ∈ Assertion`, not an accident.*
- **semantic** — ignores `id`, `e`, `Π`; equates them. Required for dedup and replay.

### **`Valid(K)` is now COMPUTABLE** — the programme's longest-standing blocker

```
Valid(K) := no duplicate assertion ids
          ∧ no dangling relation endpoints
          ∧ no inverted validity intervals
executed: Valid(K5) = (True, 'valid')
```

**Three decidable predicates. None requires `Σ`, policy, or history.** The blocker existed only because
`Valid` was being asked of the wrong object — the corpus's own `Valid(A,t,C,M)` (025o §25O.29) is on
**assertions**, and state-validity is structural.

---

## G. Status algebra (§6, §7)

**Delivered in `DECISION-SIGMA-EPISTEMIC-STATUS.md`.** Against the mandate's four hypotheses:

| Hypothesis | Verdict |
|---|---|
| **H1** one canonical finite status set | **REFUTED** — the corpus does not require one; the 24 terms are five different kinds of thing |
| **H2** status is multidimensional | **CONFIRMED** — `Σ ⊥ Γ`, all four quadrants meaningful and constructed |
| **H3** context-dependent status algebra | **NOT REQUIRED** — context lives in the assertion, not in `Σ` |
| **H4** status is partly derived | **CONFIRMED** — `Σ` derives from Assessment; `Conflicted` derives from the state |

**Transition relation `Σ × Event → Σ`:** total and deterministic over three states; `Unknown → Supported`
on supporting evidence, `→ Refuted` on contradicting, and back to `Unknown` on retraction.
**No artificial exceptions are needed — which is the mathematical criterion §7 specifies.**
The six-state historical vocabulary **cannot** achieve this: `Superseded` has no `Σ`-transition because it
is a relation, and `Invalidated` has none because it is a governance act.

---

## H–L. UL, transformations, computability, implementation

**UL — the mathematics now respects the required distinctions:**
`observation ≠ evidence` (evidence is a reference in `e`) · `evidence ≠ determination` (Assessment) ·
`determination ≠ decision` · `validation ≠ transformation` (232.4) · **`knowledge state ≠ transformation
history`** (executed) · **`provenance ≠ lineage`** — `Π` is an assertion's origin, lineage is `History(T)`;
**this programme previously conflated them and the register now separates them.**

**Implementation mapping:**

| Mathematical object | KnowledgeOS | Status |
|---|---|---|
| Assertion | — | **THEORETICALLY DEFINED / IMPLEMENTATION MISSING** |
| `K = (𝒜,ℛ)` | — | **IMPLEMENTATION MISSING** (0 files) |
| History | `GovernanceLineageGraph/Node/Edge` | **IMPLEMENTED, 47 tests passing** |
| identity | `decisionId` | **IMPLEMENTED** |
| integrity | `integrityHash` | **IMPLEMENTED — no theory slot** |
| Assessment | — | **IMPLEMENTATION MISSING** |

---

## M. Theory Completion Gap Register (§12)

| GAP | Question | Blocking? | Derivable? | Needs user? |
|---|---|---|---|---|
| **G-A** | `P = (E,D,V)` — what are `E`, `D`, `V`? | **YES** for full formalisation | **unknown** — corpus flags it undefined (20260826-221512) | no |
| **G-B** | uncertainty representation | no — external assessment | probably (199.19 constrains it) | no |
| **G-C** | `Γ` value set | no | no — governance-process question | **eventually** |
| **G-D** | integrity's theory slot | no | yes — belongs to History | no |
| **G-E** | `RelationType` vocabulary | no | partly — 126.32 lists 15 | possibly |
| **G-F** | assertion-level `Valid(A,t,C,M)` | no | yes — 025o defines it | no |

---

## N. USER DECISIONS REQUIRED (§14)

> ## **NONE for `Σ` or `K`. Both are derived.**

**I am not asking you to choose anything.** The two questions I put to you last turn were premature, and
your refusal was correct — both were derivable and have now been derived.

**The only decision that will eventually be genuinely normative is `G-C`: which governance statuses the
domain recognises.** It does **not** block the formal theory (`Γ` can remain a parameter), and it becomes
live only when the governance model is specified. **I am not asking it now.**

---

## Final status

**THEORY UNDER RECONSTRUCTION.** Two blockers closed by derivation; `Valid(K)` computable; equality proven;
minimality proven for the first time. **`G-A` (proposition internals) is now the first load-bearing gap** —
and unlike its predecessors it may not be derivable, because the corpus flags `P` as undefined and never
returns to it.

**STOP AT O for supervision.** No final theory written. Nothing promoted.
