# KnowledgeOS — **`Closure(𝒦₉)`: a Conditional Dependency Computation**

**Date:** 2026-09-06 · **Type:** computation + robustness analysis · **Status:** `[EXP]` + `[NEG]`
**Read-only w.r.t. canonical theory.** Theory v1.2 remains FROZEN · kernel NOT SELECTED ·
`𝒪_core` NOT FROZEN · no carrier declared. **Nothing here is adjudicated.**

Statements carry epistemic status per `docs/knowledgeos/governance/EPISTEMIC-STATUS-VOCABULARY.md`.

---

## 0. What this is — and the guardrail it was authorized under

This computes the transitive dependency closure of the **derived** capability basis
`𝒦₉ = {C-1 … C-9}` (GN-77) over the construct graph `G`, and compares it against the closure
of the **stipulated** basis `𝒦₄ = {HoldState, TransitionLegally, RejectIllegally, Replay}`.

> ### ⛔ This is **NOT** a proof of kernel minimality, and the result is **NOT** "the minimum kernel."
> The authorization was explicit: *"treats the result as a conditional dependency computation
> rather than as proof of kernel minimality"* and *"Do not call this 'the minimum kernel' yet.
> The computation should answer `Closure(𝒦₉)`, not `prove 𝒦₉ = the minimum kernel`."*
>
> `𝒦₉` has **necessity relative to the ratified canon** (each `C-i` is forced by a cited
> ratified element). Its **sufficiency is untested**, and 88 of the 99 contract cells in
> `synthesis/analysis/OPERATION-CONTRACT-GAP.md` are empty. A closure of a basis of unknown
> sufficiency is a closure of unknown sufficiency.

**`[NEG]` The headline is negative.** The computation does not converge on a kernel. It shows
that the previously reported closure was an artifact of the stipulated basis, and that the
**size** of the kernel's requirement set is not determined by the corpus at all.

---

## 1. Method and provenance

| | |
|---|---|
| graph `G` (29 constructs) | reproduced **verbatim** from `brainstorming/verification/gap-discovery/readiness/exec/minimum_implementable.py` |
| closure procedure | that program's own `closure(roots)` — unmodified |
| blocker marker | that program's own semantics: a construct is BLOCKED iff its class label (3rd tuple element) is non-`None` (its §B/§D use it exactly so) |
| **control** | `Closure(𝒦₄)` = **18 constructs, 15 blocked** — **reproduces the source program's published result exactly**, verifying the transcription |

Code produced here (**all new, none modifies the corpus**):

- `docs/knowledgeos/reviews/exec/k9_closure.py` — the closure and the basis comparison
- `docs/knowledgeos/reviews/exec/k9_sensitivity.py` — one-at-a-time mapping sensitivity
- `docs/knowledgeos/reviews/exec/k9_triple.py` — three-axis robustness + the §E re-test

---

## 2. `[DEF]` The one modelling act, declared

Mapping `C-1…C-9` onto seed constructs is a **reading**, and it is mine. Everything else in this
document is mechanical. Each seed is derived from the `C`-row's own text and its cited forcing
element; each row's recorded alternative is carried into §5's sensitivity.

| row | seed(s) | justification from the row's own text | alternative tested |
|---|---|---|---|
| **C-1** move an item one rung up the ladder | `Transformation`, `Sigma` | a transition (I-12 covering relation) over the status ladder | `Sigma` alone — if "move" is a status predicate, not a transition |
| **C-2** attach `Committed` by an authority act | `Authorization`, `Sigma` | canon fixes an **authority act** (A6, I-4); target is a ladder state | `Authority` alone |
| **C-3** make a Determination | `Determination`, `Policy` | the row **names** the construct and its governing policy | none — the row names it |
| **C-4** change an in-force policy version | `Policy`, `Authorization` | I-11: a governed approval over `Policy` | `Gamma` |
| **C-5** compose evidence (dups ↛ amplify) | `Evidence` | I-5/I-6 — the two **tested** invariants — are stated over Evidence | `Assessment` |
| **C-6** compute state↔requirement gap | `K` | `Zero(K,EC)` is a function **of the state**; `EC` is external | `Missingness` |
| **C-7** turn an observation into evidence | `Qualification` | the row **is** the qualification predicate | none — ⚠️ see §4 |
| **C-8** evaluate a decision contract | `InvariantReg`, `Authority`, `Evidence` | DC 6-tuple slots Inv/Auth/Evidence map to recorded constructs | — |
| **C-9** act, and observe the result | `Determination` | the decision interlock | — |

> **`[DEFECT]` Two rows are UNDER-SPECIFIED and are recorded as an evidence limitation, not
> repaired.** `C-8`'s `Pre`, `Post` and `Temporal` slots have **no construct in `G`**. `C-9`'s
> "act" and "observe" have **no construct in `G`** — its far side is OPEN BY RULING (OQ-4).
> Their seeds are therefore *lower bounds*; the true closure of `𝒦₉` can only be larger.

---

## 3. `[EXP]` Result 1 — **the two bases are INCOMPARABLE**

```
|Closure(𝒦₄)| = 18        |Closure(𝒦₉)| = 20        of 29 constructs

Closure(𝒦₄) ⊆ Closure(𝒦₉)  ?   FALSE
Closure(𝒦₉) ⊆ Closure(𝒦₄)  ?   FALSE
```

Neither contains the other. **This is the load-bearing finding.**

| | constructs |
|---|---|
| **𝒦₄ ∖ 𝒦₉** (3) | `History`, `Rejection`, `Replay` |
| **𝒦₉ ∖ 𝒦₄** (5) | `Assessment`, `Authority`, `Authorization`, `Determination`, `Qualification` |
| intersection (15) | `Assertion, Equality, Evidence, Identity, InvariantReg, K, O_core, Operations, Policy, Proposition, Provenance, Relation, RelationType, Sigma, Transformation` |
| union (23) | |
| reached by **neither** (6) | `Gamma`, `Lineage`, `Measurement`, `Missingness`, `Orphan`, `Q_t` |

### `[EXP]` The asymmetry, stated plainly

- **`𝒦₄` stipulates 2 seeds that no `C`-row forces: `Rejection`, `Replay`.**
- **The `C`-rows force 8 seeds that `𝒦₄` never names:** `Authority`, `Authorization`,
  `Determination`, `Evidence`, `InvariantReg`, `Policy`, `Qualification`, `Sigma`.

`[OPEN]` **Two readings, and this computation cannot decide between them:**
(a) `𝒦₄` **over-stipulates** — the canon does not force a rejection or a replay capability;
(b) GN-77's nine **under-derive** — rejection/replay are forced by ratified elements that GN-77
did not read. **Deciding this is a governance act, not a computation.** It is recorded as OPEN.

⚠️ Reading (a) must **not** be reported as "the canon does not require rejection." It is bounded
by *my* mapping and by *GN-77's* derivation.

---

## 4. ⚠️ `[DEFECT]` Degeneracy warning — `Qualification` is definitional, not discovered

`C-7` **is** the qualification capability, so its seed is `Qualification`. Its presence in
`Closure(𝒦₉)` is therefore **true by construction** and carries **zero evidential weight**.
Reporting "the derived basis requires Qualification!" would be exactly the degenerate-estimand
failure that `O-F*` exists to catch. The informative content is only in what *else* differs.

---

## 5. `[EXP]` Result 2 — robustness to the **mapping** (one-at-a-time, KR-BRIDGE design)

| perturbed row | alternative | size | change |
|---|---|---|---|
| C-1 | `Sigma` | **16** | **− `Equality`, `O_core`, `Operations`, `Transformation`** |
| C-2 | `Authority, Sigma` | 20 | no change |
| C-4 | `Gamma, Policy` | 21 | + `Gamma` |
| C-5 | `Assessment` | 20 | no change |
| C-6 | `Missingness` | 22 | + `Missingness`, `Q_t` |

> ### ⭐ `[NEG]` `𝒪_core` is **NOT** robust to the reading of `C-1`.
> `O_core`, `Operations` and `Transformation` enter `Closure(𝒦₉)` through **one seed of one row**.
> If `C-1`'s "move an item one rung up the ladder" is read as a **status predicate** rather than a
> **transition**, the entire transformation/operations/`𝒪_core` lane **drops out of the derived
> closure**.
>
> This is the **opposite** of the convenient answer. Under `𝒦₄`, `𝒪_core` is present by
> *stipulation*. Under `𝒦₉`, it is present only under *one reading of one row* — mine.

---

## 6. `[EXP]` Result 3 — two-axis and three-axis robustness

Axis 3 is **the source program's own §F sensitivity**: does the mandated invariant register hold
**epistemic** invariants (`InvariantReg → {K, Sigma, Policy}`) or only **structural** ones
(`→ {K}`)? The corpus does not settle it — G-67 has never been enumerated.

**14 worlds** = 2 register-variants × (`𝒦₄` + 6 mappings).

| survives | blocked constructs |
|---|---|
| **basis** (both closures) — 12 | `Equality, Evidence, Identity, InvariantReg, K, O_core, Operations, Policy, Proposition, Relation, Sigma, Transformation` |
| **mapping** (all 6 variants) — 13 | `Assessment, Authority, Authorization, Determination, Evidence, Identity, InvariantReg, K, Policy, Proposition, Qualification, Relation, Sigma` |
| **doubly robust** — 8 | `Evidence, Identity, InvariantReg, K, Policy, Proposition, Relation, Sigma` |
| **⭐ TRIPLY robust — 5** | **`Identity`, `InvariantReg`, `K`, `Proposition`, `Relation`** |

- basis-robust but **mapping-fragile** (4): `Equality`, **`O_core`**, **`Operations`**, **`Transformation`**
- mapping-robust but **basis-fragile** (5): `Assessment`, `Authority`, `Authorization`, `Determination`, `Qualification`
- present in **no** world (3): `Lineage`, `Measurement`, `Orphan`

> ### `[NEG]` Closure size ranges over **15 … 22** of 29 across the 14 worlds.
> ⚠️ **AMENDED — see §12:** independent verification over the full 66-world product widens
> this to **15 … 23**. The conclusion is unchanged in direction and stronger in magnitude.
> **The size of the kernel's requirement set is not determined by the corpus.** Any single number
> — including the source program's 18, and including this document's 20 — is a number *about a
> chosen world*, not a number about KnowledgeOS.

**`[EXP]` The five triply-robust blocked constructs**, with their recorded defects:

| class | construct | recorded status |
|---|---|---|
| `D?` | `Identity` | clear-all-lanes |
| `D` | **`InvariantReg`** | **NOT ENUMERATED** |
| `A` | `K` | formal + L5 |
| `D` | `Proposition` | formal |
| `D` | `Relation` | formal |

`InvariantReg` is the only one of the five carrying a *substantive* defect — and it is precisely
the register (G-67) that has never been enumerated. `[REC]` **This is the single item that is a
blocker under every reading of every axis tested.** That is a recommendation, not a ruling.

---

## 7. ⭐ `[NEG]` Result 4 — the source program's §E "justified exclusions" fail for 4 of 8

`minimum_implementable.py` §E asserts: *"Measurement, Missingness, Determination, Q_t, Assessment,
Qualification, Gamma and Authorization are all reachable ONLY from constructs the kernel does not
require — so a kernel can be built without them."*

| construct | excluded under `𝒦₄` | excluded under `𝒦₉` | |
|---|---|---|---|
| `Measurement` | yes | yes | holds |
| `Missingness` | yes | yes | holds (but enters under C-6's alternative — §5) |
| `Q_t` | yes | yes | holds (same caveat) |
| `Gamma` | yes | yes | holds (but enters under C-4's alternative — §5) |
| **`Determination`** | yes | **NO** | ❌ **claim fails** |
| **`Assessment`** | yes | **NO** | ❌ **claim fails** |
| **`Qualification`** | yes | **NO** | ❌ **claim fails** |
| **`Authorization`** | yes | **NO** | ❌ **claim fails** |

> **The exclusions were justified *relative to the stipulated `𝒦₄`*, not absolutely.** The §E
> sentence "a kernel can be built without them" is **withdrawn as unsupported** for those four.
> This does not make them required — it makes their exclusion **basis-dependent and undecided.**

⚠️ Scope: this refutes the *generality* of §E's claim. It does **not** establish that a kernel
**must** contain those four; that would require `𝒦₉` to be sufficient, which is untested.

---

## 8. `[EXP]` What this settles for the Level-A report §17 — and it is not what §17 hoped

§17 asked whether the `Qualify` blocker is robust to the choice of capability basis.

> ### **Answer: NO. `Qualify` is basis-fragile.**
> `Qualification ∉ Closure(𝒦₄)` · `Qualification ∈ Closure(𝒦₉)` — and its membership in the
> latter is **definitional** (§4). It is robust to the *mapping* axis but not to the *basis* axis.

**Consequence for the Nine-Survivor classification** (`2026-09-06-KOS-SURVIVING-BLOCKER-DEPENDENCY-ANALYSIS.md` §4):

| # | item | prior classification | status after this computation |
|---|---|---|---|
| 1 | `𝒪_core` not frozen | 🔴 BLOCKING (the ROOT) | **⚠️ basis-robust, MAPPING-FRAGILE** — root under `𝒦₄` and under my `C-1` reading; vanishes if `C-1` is a status predicate |
| 2 | `Π ∈ ≡ ?` | 🟠 BLOCKING | unchanged — `Π` is not a construct in `G`; **out of scope of this computation** |
| 3 | `ℐ` invariants (0 of 7) | 🔴 BLOCKING, downstream of 1 & 2 | **⬆️ STRENGTHENED — `InvariantReg` is the only substantive blocker present in all 14 worlds, and it is UPSTREAM of `𝒪_core`, not downstream** |
| 4 | `δ` commit case | 🔴 BLOCKING | unchanged (rides on `Transformation`, itself mapping-fragile) |
| 5 | `Reject ↔ I-12 ↔ Article 8` | 🟡 BLOCKING but INDEPENDENT | **⚠️ `Rejection` is forced by NO `C`-row** — its blocker status rests entirely on the stipulated basis |
| 6 | `Qualify` body | 🟢 PARALLEL — *"closure says Qualification NO"* | **❌ that justification is WITHDRAWN** — it was an artifact of `𝒦₄`. Reclassified **UNDECIDED (basis-dependent)**, not PARALLEL |
| 7 | `DECISION-02` / `φ` | 🟢 PARALLEL | unchanged — not a construct in `G` |
| 8 | `Sat_consistency` | 🟢 PARALLEL / conditional | unchanged |
| 9 | `Acknowledgment` | ⚪ FUTURE SCOPE | unchanged |

**Net: one reclassification (#6), one strengthening (#3), two robustness downgrades (#1, #5).**
The summary line *"5 blocking · 3 parallel · 1 future scope"* and *"four of five downstream of one
root `𝒪_core`"* are **no longer supported as stated** — `𝒪_core`'s root status is conditional on
the reading of `C-1`, and `InvariantReg` sits upstream of it.

---

## 9. Evidence limitations — stated, not repaired

1. **`C-8` and `C-9` are under-specified** (§2). `Pre`/`Post`/`Temporal`/"act"/"observe" have no
   construct in `G`. All `𝒦₉` closures here are **lower bounds**.
2. **The mapping is one person's reading.** Six variants were tested; the space of readings is
   not enumerated.
3. **`𝒦₉`'s sufficiency is untested** — 88 of 99 contract cells empty. Necessity ⇏ sufficiency.
4. **`G` itself is a modelling artifact** of `minimum_implementable.py`, inherited unexamined
   apart from its own §F edge. Its other 28 edge-sets were **not** subjected to sensitivity here.
5. **`[DEFECT]` inherited ambiguity:** `Policy` carries status `RATIFIED` yet a non-`None` class
   label, so the source program counts it as blocked. Flagged, propagated unchanged, **not fixed**
   — fixing it would modify the corpus's semantics.
6. **Constructs outside `G`** (`Π`, `≡`, `δ`, `φ`, `Zero`, `Sat_consistency`, `Acknowledgment`)
   are simply **not addressed**; silence about them here is not evidence about them.

---

## 10. `[REC]` What this does and does not authorize

**Does not authorize:** selecting a kernel · freezing `𝒪_core` · declaring a carrier · promoting
`𝒦₉` · calling any closure "the minimum kernel" · treating the 5 triply-robust constructs as a
kernel (they are a **lower bound on blockers**, not a capability set).

**`[REC]` The smallest legitimate next act** is a **governance** act, not a computation: rule on
the §3 disjunction — *does the ratified canon force a rejection/replay capability, or does GN-77's
reading under-derive?* Every downstream number moves with that ruling, and no amount of further
computation can settle it. ⚠️ **It is a recommendation and is NOT performed here.**

---

## 11. Files

| artifact | path |
|---|---|
| this report | `docs/knowledgeos/reviews/2026-09-06-KOS-K9-CLOSURE-CONDITIONAL-DEPENDENCY-COMPUTATION.md` |
| closure + basis comparison | `docs/knowledgeos/reviews/exec/k9_closure.py` |
| mapping sensitivity | `docs/knowledgeos/reviews/exec/k9_sensitivity.py` |
| triple robustness + §E re-test | `docs/knowledgeos/reviews/exec/k9_triple.py` |
| graph source (unmodified) | `docs/knowledgeos/brainstorming/verification/gap-discovery/readiness/exec/minimum_implementable.py` |
| `C-1…C-9` source | `docs/knowledgeos/reviews/synthesis/analysis/OPERATION-CONTRACT-GAP.md` |
| amended by this report | `docs/knowledgeos/reviews/2026-09-06-KOS-SURVIVING-BLOCKER-DEPENDENCY-ANALYSIS.md` §4 · `…-CAPABILITY-BASIS-LEVEL-A-FALSIFICATION.md` §17 |

---

## 12. ✅ INDEPENDENT VERIFICATION — reproduced, and **extended**, by a separate lane

`docs/knowledgeos/brainstorming/verification/gap-discovery/gap-update-2026-09-02/11-INDEPENDENT-VERIFICATION-K9-CLOSURE.md`
(+ `exec/verify_k9_closure.py`, `exec/extend_k9_worlds.py`) — produced independently of this
document, not by its author.

| | |
|---|---|
| graph transcription | **byte-identical across all 29 nodes — CONFIRMED** |
| published numbers re-executed | **15 of 15 reproduce.** Control, both closures, non-nesting, all three set differences, all five mapping perturbations, the §E 4-of-8 failure, the triply-robust set, and the 15…22 range |
| the §4 degeneracy warning | **tested the hard way** — removing `C-7` makes `Qualification` unreachable by *any* path, so its membership is definitional exactly as warned |

### `[EXP]` Three things the verification ADDS, and one correction to §6 of this document

1. **The world set was too small, and my negative claim UNDERSTATED itself.** This document crossed
   2 edge-variants × (`𝒦₄` + 6 one-at-a-time mappings) = **14 worlds**. The verification takes the
   **full product** over the 5 perturbable rows = **66 worlds**, everything else identical:

   $$\text{range } \mathbf{15 \ldots 22}\ (14\text{ worlds}) \;\longrightarrow\; \mathbf{15 \ldots 23}\ (66\text{ worlds})$$

   **⚠️ §6's "15…22" is superseded by 15…23** — a spread of **9 of 29 constructs** from modelling
   choices alone. The conclusion is unchanged in direction and **stronger** in magnitude.

2. **The triply-robust core is unchanged at 4.7× the world count.** `{Identity, InvariantReg, K,
   Proposition, Relation}` is **identical over 66 worlds**. `InvariantReg` is blocked in **66/66**.
   `[EXP]` This is the package's strongest positive result and it survived the harder test.

3. **`[EXP]` New number — `𝒪_core` is in the closure in 34 of 66 worlds (51 %).** This document
   established `𝒪_core` as mapping-fragile; the verification *quantifies* it.
   ⚠️ **Read precisely:** this is **not** "`𝒪_core` is 51 % likely to be in the kernel" — there is
   no distribution over worlds and none is claimed. It is: **half the admissible readings put it in,
   half leave it out, and the corpus does not choose.**

### `[INF]` The one apparent discrepancy was a scope difference, not a contradiction

The verification's first pass reported **16…23 over 32 worlds** against this document's 15…22, and
**correctly declined to call it a mismatch**: the designs differ — this document crosses the mapping
perturbations with the source program's own *structural-only* `InvariantReg` variant, and that
variant is what reaches 15. Both are right for their own design; the union is the 15…23 above.

> **The rule that produced the right outcome, worth keeping:** *a number that disagrees is a
> difference of design or scope until proven otherwise — the first move on a mismatch is to read
> the other design, not to report a contradiction.*

### What verification does **not** change

Every `🔴 untouched` item stands: which reading is right (**a governance act**), the sufficiency of
either basis (88 of 99 contract cells still empty), the `Reject ↔ I-12 ↔ Article 8` contradiction,
`Replay`'s type-level contradiction (capability in `𝒦₄`, property in GN-77), and **anything about
minimality**. Reproduction confirms the computation was performed correctly; it does not convert a
conditional dependency computation into a kernel.
