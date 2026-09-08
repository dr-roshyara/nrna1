# `P-49` — Persistence Kernel Representation Algebra & Complexity Admissibility Audit

**2026-09-08 · Lane T.** After [`66` `P-48`](./66-P48-KERNEL-AXIS-DETERMINACY-AUDIT.md).

# 1. Executive verdict

$$\boxed{\begin{array}{c}\mathbf{C\ —\ [OPEN].} \textbf{ Neither an admissible representation algebra nor a } \mathit{Complexity} \textbf{ function is determined for R.}\\[6pt] \boxed{\textbf{⭐⭐⭐ And the blocker is } \mathbf{NOT\ R\text{-}specific:}\ \mathit{Complexity} \textbf{ is } \mathbf{never\ given\ a\ formula\ ANYWHERE\ in\ the\ corpus.}}\end{array}}$$

⭐⭐ **Measured:** `Complexity =` / `:=` / `\equiv` → **0 hits**. `Complexity(·)` appears ~**12** times,
always inside `\arg\min`, across **four different argument types** — `Complexity(A)`, `(K)`, `(M)`,
`(R)`. ⭐⭐⭐ **`\arg\min Complexity` is a recurring rhetorical FORM, not a defined FUNCTION.**

⭐ **And `admissible algebra` occurs in exactly ONE file** — the `[DEF-33]` source itself. **Theorem 10
quantifies over a class that is never defined.**

$$|K| = 11 \textbf{ — unchanged, still } \mathbf{[REC]}. \textbf{ ⛔ No cell added, removed, merged or renamed. No minimality claim attempted.}$$

# 2. ⚠️ Two corrections to `P-48` carried in before execution

**(a)** ⭐⭐ **`P-48`'s executive box said *"several representations are admissible"* while its §9 said
exactly ONE is** — ⭐ **an internal contradiction of the same shape I caught in `P-37`, repeated in my
own artifact.** **Corrected reading: one persistence representation is CONSTRUCTED; whether alternatives
exist is `[OPEN]`.**

**(b)** ⭐⭐ **`P-48` §12 over-generalised.** `M-2`'s standard bites on **cardinality minimality under
`[DEF-33]`'s optimization framework** — ⛔ **it does not erase `P-08`'s lower bound or `P-47`'s
set-inclusion/irreducibility results, which carry their own stated scopes.** **Restated:** *cardinality
minimality was never well-formed under `[DEF-33]`; the earlier results retain their scopes and
evidential status.*

⛔ **No `three_model_convergence/` · no cell change · no canonicalization · no Schema v3 · no
architecture · no governance change · `EKS-12` not reopened · `[REC]` not upgraded.**
⚠️ **Pre-registered `C`. Correct — record 7 of 13.**

# 3. Five claim-types, kept apart *(§2 of the commission)*

| | claim type | source | survives `P-48`/`P-49`? |
|---|---|---|---|
| 1 | **lower bound** | `P-08` §18 | ⭐⭐ **YES — with its own hedge intact** |
| 2 | **cell-wise irreducibility** | `P-09.2`, `P-19`, `P-47` | ⭐ **YES — within the constructed representation** |
| 3 | **set-inclusion minimality** | `P-47` | ⭐ **YES — relative to that representation** |
| 4 | ⭐ **cardinality minimality** | never asserted by `P-08` | ⭐⭐⭐ **never well-formed — §5** |
| 5 | **representation-relative minimality** | `[DEF-33]`, Theorem 10, `M-2` | ⚠️ **`[EXP]` in the operator track** |

⛔ **These were not collapsed into one proposition, and 1–3 are not retroactively invalidated.**

# 4. ⭐⭐ Scope of `[DEF-33]` / `M-2` / Theorem 10 — the transfer

| question | answer |
|---|---|
| what is *"kernel"* there? | ⭐ **the OPERATOR kernel** — `13` operators under `𝒜₀`, `8` under another |
| what is the algebra? | ⭐⭐⭐ **never stated** — `representation algebra` appears once, `admissible algebra` once |
| what is `Complexity`? | ⭐⭐⭐ **never defined — §5** |
| what is admissibility? | ⭐⭐ **never defined** |
| declared transferable to R? | ⛔ **NO statement of transfer exists** |

$$\boxed{\textbf{Transfer classification: } \mathbf{[ANALOGY\ ONLY]} \textbf{ for the } 13/8 \textbf{ numbers; } \mathbf{[DERIVED]}\textbf{, weakly, for } [DEF\text{-}33]\textbf{'s generic phrasing.}}$$

⛔ **The generic word *"kernel"* was not treated as evidence of transfer** — the `P-38`/`P-39`
level-error is not repeated.

# 5. ⭐⭐⭐ `Complexity` — the decisive measurement

| pattern | hits |
|---|---:|
| `Complexity =` / `:=` / `\equiv` *(a formula)* | ⭐⭐⭐ **0** |
| `Complexity(·)` in `\arg\min` | ~12 |
| distinct argument types | ⭐⭐ **4** — `A`, `K`, `M`, `R` |
| `admissible algebra` | ⭐ **1 file** |

⭐⭐ **The same expression is applied to an assertion/algebra `A`, a kernel `K`, a model `M` and a
representation `R`** — *"inquiry as the missing top-level entry point"*, *"the central research
question — stop adding concepts"*, `[DEF-33]`, and others.

$$\boxed{\begin{array}{c}\textbf{⭐⭐⭐ } \arg\min \mathit{Complexity} \textbf{ is a } \mathbf{RECURRING\ FORM\ OF\ WORDS}\textbf{, ⛔ not a mathematical apparatus.}\\ \boxed{\textbf{So } P\text{-}48 \textbf{ understated it: } \mathit{Complexity} \textbf{ is undefined } \mathbf{not\ merely\ for\ R\ but\ for\ every\ object\ the\ corpus\ applies\ it\ to.}}\end{array}}$$

# 6. R-native structure — is there an algebra?

| component of `𝒜_R = (C, O, …)` | present in R? |
|---|---|
| **`C`** primitive capabilities | ⭐ **`[EMP]`** — the 11 cells, each with a forcing witness |
| ⭐ **`O`** admissible operations / compositions | ⭐⭐⭐ **`[UNWITNESSED]` — no artifact defines an operation over capabilities** |
| **equivalence relation** | ⚠️ **`[DERIVED]`, implicit** — *same forcing witness* *(`P-48` §7)*; ⛔ never stated as a relation |
| **closure** | ⚠️ ⭐ **`P-09.3`'s "closure" is DECOMPOSITION-STABILITY**, ⛔ **not closure under operations** — its own subtitle says so |
| **representation transformations** | ⛔ **`[UNWITNESSED]`** |

$$\boxed{\textbf{⭐⭐ R has a } \mathbf{SET\ with\ a\ decomposition\text{-}stability\ property} \textbf{ — ⛔ it does } \mathbf{not} \textbf{ have an algebra. Two of five components are absent outright.}}$$

⭐ **My own §4 note was worth checking and came out negative:** *closure derivation* sounded algebraic,
but `P-09.3` establishes **stability under decomposition**, not closure under operations. ⛔ **A list of
cells with a stability property is not an algebra**, exactly as the commission warned.

# 7. Representation · capability · algebra — kept apart
$$\boxed{\begin{array}{ll}\textbf{capability} & \textbf{what obligation must be satisfied — ⭐ } \mathbf{[EMP]}\textbf{, 11 of them}\\ \textbf{representation} & \textbf{how the obligations are encoded — ⭐ } \mathbf{ONE} \textbf{ constructed}\\ \textbf{algebra} & \textbf{which transformations between representations are admissible — ⭐⭐ } \mathbf{[UNWITNESSED]}\end{array}}$$
⛔ **A different grouping was not treated as a different algebra; a different notation not as a
different representation; a different representation not as a different capability set.**

# 8. `Complexity` candidates — **not evaluated**
⭐ **§7 of the commission is conditional on an admissible algebra being established.** ⛔ **It was not,
so no candidate measure was scored** — ⭐⭐ **and scoring them would have been the *"select the one that
produces 11"* failure the commission forbids.** ⚠️ **Recorded: any of the seven candidates would
require corpus-absent weights, hence `[STIPULATED]` modelling choices.**

# 9. Identifiability — **not reached**
⭐ Two admissible representations cannot be compared on `Complexity` when **neither the admissibility
class nor the function exists**. ⇒ $\mathtt{[UNINFORMATIVE\ FOR\ THIS\ TEST]}$, ⛔ **not evidence
either way.**

# 10. ⭐⭐ Attack on `P-48`

| | claim | verdict |
|---|---|---|
| ⭐⭐ **A** | *"several representations are admissible"* | ⭐⭐⭐ **`[REFUTED]` — my own artifact's §9 contradicts its own executive box.** Corrected: **one constructed; alternatives `[OPEN]`** |
| **B** | *"the 11/7/5 problem IS the 13/8 phenomenon"* | ⭐⭐ **`[ANALOGY ONLY]`** — ⛔ **no structural mapping was ever proven**, and the objects differ *(capabilities vs operators)* |
| **C** | *"the corpus says minimality requires an admissible algebra"* | ⭐ **`[QUALIFIED]` — true of the transferred `[DEF-33]` framework only; ⛔ no R-native requirement exists** |
| ⭐⭐ **D** | *"`Complexity` is the precise blocker"* | ⭐⭐ **`[QUALIFIED]` — the blocker is THREEFOLD:** ⛔ no algebra *(`O` absent)* · ⛔ no `Complexity` *(never defined, anywhere)* · ⛔ no corpus-selected optimization criterion |
| **E** | *"the subject-wise 5 is inadmissible"* | ⭐ **INDEPENDENTLY RE-CHECKED and upheld** — `P-17`'s primary: *"`(link,value)` yields a JUSTIFICATION while `(link,reference)` yields a RESOLVABLE RELATION WITH CARDINALITY"* ⇒ collapsing them fails **`SemanticAdequacy`**. ⛔ **Not inherited from `P-48`** |

# 11. ⭐⭐⭐ The category question — R may not be the kind of object `[DEF-33]` presupposes

⭐ **What R actually is, on the evidence:** an **obligation model** — a set of *(item, transition, lost
datum)* triples *(`P-08` §1: "the persistence kernel is a RELATION, not a set")* — with **no
operations, no composition, no transformation calculus**.

⭐⭐ **`[DEF-33]` presupposes a *representation space* with an admissibility class and a cost function.**
⇒ ⭐⭐⭐ **Applying it to R may be a CATEGORY ERROR, not merely an unfilled gap.**

⚠️ **This is `[DERIVED]` and is recorded, ⛔ not asserted** — and none of the commission's four verdicts
states it cleanly, so it is reported here inside `C` rather than forced into `D`.

# 12. Discipline
⛔ **Frequency was never used to infer admissibility.** ⭐ Corpus evidence, mathematical derivation,
experimental observation *(`[EXP]`)* and modelling stipulation kept apart throughout. ⛔ **No bounded
context, aggregate, actor, capability object or lifecycle manufactured to construct an algebra.** ⭐
**Nothing in this audit rests on a chosen equivalence relation or cost function** — because none was
chosen.

# 13. What remains undefined
⭐⭐ `Complexity` *(corpus-wide)* · the admissibility class · operations `O` over R capabilities · a
corpus-selected optimization criterion · whether a second admissible R representation exists · whether
`[DEF-33]` governs R at all.

# 14. Status register
**`[EMP]`** ⭐⭐ `Complexity` has **0** formulas corpus-wide · `admissible algebra` in **1** file ·
`[DEF-33]`'s five constraints · Theorem 10's `13`/`8` *(operator kernel)* · `M-2` · `P-09.3`'s closure
is **decomposition-stability** · `P-08` §1 *"a RELATION, not a set"*.
**`[DERIVED]`** ⭐⭐⭐ **`\arg\min Complexity` is a form, not a function** · R lacks `O` and
transformations ⇒ **not an algebra** · the possible **category error**.
**`[ANALOGY ONLY]`** the `13`/`8` numbers · Vedic-math *"Representation Algebra"* material *(a
different strand, not consumed)*.
**`[REFUTED]`** ⭐⭐ **`P-48` Claim A** *(and its executive/§9 contradiction)*.
**`[QUALIFIED]`** `P-48` Claims C and D · **`P-48` §12's over-generalisation**.
**`[UNWITNESSED]`** operations over capabilities · admissibility · representation transformations · any
transfer statement to R.
**`[OPEN]`** ⭐ everything in §13 · witness-set completeness *(`τ4`/`Q3`)* · all items carried from
`P-29`–`P-48` · ⛔ **well-foundedness / terminality / acyclicity — DEFERRED.**
**Governance:** `|K| = 11` remains **`[REC]`** — unchanged by this audit.

# 15. ⛔ Backlog — no new item
⭐ **A research under-specification, not an operational or business exposure.** `EKS-13` remains the one
live operational ticket. ⛔ **No ticket merely because a mathematical question is open** *(§14)*.

### 16. ONE NEXT UNRESOLVED QUESTION

$$\boxed{\textbf{Is } \mathbf{minimality} \textbf{ even the right question for an } \mathbf{OBLIGATION\ MODEL} \textbf{ — or does } [DEF\text{-}33] \textbf{ presuppose an object that R is not?}}$$

⭐⭐ **`P-08` says the kernel is *"a RELATION, not a set"* — a set of `(item, transition, lost datum)`
triples with no operations.** ⭐⭐⭐ **If minimality is a property of representation spaces and R is an
obligation relation, then the right question may be COMPLETENESS (does every obligation have a cell?)
and SOUNDNESS (does every cell have an obligation?) — both of which R *can* answer — rather than
cardinality minimality, which it may never be able to.**

```
C — [OPEN]. NEITHER AN ADMISSIBLE REPRESENTATION ALGEBRA NOR A COMPLEXITY FUNCTION IS DETERMINED FOR R.
|K| = 11 UNCHANGED, STILL [REC]. NO MINIMALITY CLAIM ATTEMPTED.

AND THE BLOCKER IS NOT R-SPECIFIC. Complexity is NEVER GIVEN A FORMULA ANYWHERE IN THE CORPUS: zero
hits for "Complexity =" or ":=", against roughly twelve uses, always inside argmin, across FOUR
different argument types — Complexity(A), (K), (M), (R). So argmin Complexity is a RECURRING FORM OF
WORDS, not a mathematical apparatus. P-48 understated this: the function is undefined not merely for
the persistence kernel but for every object the corpus applies it to. "Admissible algebra" occurs in
exactly ONE file — the DEF-33 source itself — so Theorem 10 quantifies over a class that is never
defined.

R IS NOT AN ALGEBRA. Of the five components an algebra needs, two are absent outright: there are no
operations over capabilities and no representation transformations. P-09.3's "closure" turns out to be
DECOMPOSITION-STABILITY, not closure under operations — its own subtitle says so. That check was worth
running, because "closure derivation" sounded algebraic and is not.

TWO CORRECTIONS TO P-48 WERE CARRIED IN BEFORE EXECUTION, AND BOTH WERE THE COMMISSIONER'S. Its
executive box said "several representations are admissible" while its own §9 said exactly one is — the
same internal contradiction I had caught in P-37, repeated in my own artifact; Claim A is REFUTED. And
its §12 over-generalised: M-2's standard bites on cardinality minimality under DEF-33's framework and
does NOT erase P-08's lower bound or P-47's set-inclusion results, which retain their own scopes.

THE BLOCKER IS THREEFOLD, not single: no algebra, no Complexity, and no corpus-selected optimization
criterion. P-48's Claim B — "the 11/7/5 problem IS the 13/8 phenomenon" — is downgraded to [ANALOGY
ONLY], since no structural mapping was ever proven and the objects differ (capabilities versus
operators). Claim E was re-checked independently against P-17's primary and upheld.

THE COMPLEXITY CANDIDATES WERE DELIBERATELY NOT SCORED. §7 is conditional on an admissible algebra, and
none exists; scoring them would have been precisely the "select the one that produces 11" failure the
commission forbids.

A CATEGORY QUESTION IS RECORDED RATHER THAN ASSERTED: P-08 says the kernel is "a RELATION, not a set" —
a set of (item, transition, lost datum) triples with no operations — while DEF-33 presupposes a
representation space with an admissibility class and a cost function. Applying one to the other may be
a category error rather than an unfilled gap.

Pre-registered C. Correct — record 7 of 13.

NO NEW BACKLOG ITEM: a research under-specification, not an operational exposure; EKS-13 remains the one
live ticket.

ONE NEXT UNRESOLVED QUESTION: Is MINIMALITY even the right question for an OBLIGATION MODEL — or does
  DEF-33 presuppose an object that R is not? If minimality is a property of representation spaces and R
  is an obligation relation, the right questions may be COMPLETENESS (does every obligation have a
  cell?) and SOUNDNESS (does every cell have an obligation?) — both of which R can answer — rather than
  cardinality minimality, which it may never be able to.

NO CELL CHANGE — NO MINIMALITY CLAIM — NO CANONICALIZATION — NO SCHEMA v3 — NO ARCHITECTURE — NO
GOVERNANCE CHANGE — NO three_model_convergence INSPECTION.
```
