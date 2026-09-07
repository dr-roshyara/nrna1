# `P-07` — Identity, Addressing & Continuity Derivation

**2026-09-07 · decision-support only · baseline: [`16-P06-PERSISTENCE-IDENTITY-DERIVATION.md`](./16-P06-PERSISTENCE-IDENTITY-DERIVATION.md).**

> ⛔ **Schema v2 unmodified · v3 not begun · 0 stress records modified · proposal register unmodified ·
> `F-4`/`F-5` unrepaired · 3MC / `dimension-registry.md` / MD-017 / MD-018 untouched · `Q3`/`Q4` NOT
> closed · candidate identity not adjudicated · no excluded-lane evidence · code existence not treated
> as theoretical identity · no A/B/C/D selection · no fifth architecture invented · no DDD category
> treated as semantic truth · persistence identity NOT equated with a database primary key · stable
> address NOT equated with identity.**

`[EMP]` evidenced · `[DERIVED]` logically required · `[ARCH]` design choice · `[OPEN]` unresolved.

---

# 1. The vocabulary, ten notions kept apart

| # | notion | definition as used here | `[?]` |
|---|---|---|:--:|
| 1 | **semantic identity** | the fact that a thing **is the thing it is**, independent of any way of finding it | `[EMP]` — `𝒦 ≠ K` is a claim of this kind |
| 2 | **persistence identity** | ⭐ a **stable designator** that carries sameness across change. ⚠️ **NOT necessarily a surrogate** — see §1.1 | `[DERIVED]` |
| 3 | **reference** | any expression that picks the thing out **now** | `[EMP]` — `Zero.D-03` |
| 4 | **address** | a reference **whose form depends on the thing's container** | `[EMP]` — `K.D-03` changes if the container changes |
| 5 | **locator** | a reference **resolvable without knowing the container** | `[ARCH]` — no locator scheme exists in the corpus |
| 6 | **natural key** | a designator drawn from the thing's own content | `[EMP]` — a glyph's string; `(path, symbol)` |
| 7 | **surrogate identifier** | an assigned designator with no content | `[ARCH]` — none exists in the corpus today |
| 8 | **version** | a designator of **one state** of a thing that has several successive states | `[EMP]` — git over `research/**`; `Theory v1.1/v1.2` |
| 9 | **historical continuity** | the assertion *"same thing, changed state"* **as opposed to** *"new thing, related to old"* | ⭐ `[DERIVED]` — §7; **this is the load-bearing notion of `P-07`** |
| 10 | **provenance** | the record of **where an assertion came from** | `[EMP]` — `F-4`; scope declarations |

## 1.1 ⭐ The distinction `P-06` did not draw

$$\boxed{\textbf{identity} \neq \textbf{surrogate identifier.} \qquad \textbf{A stable natural key IS an identity.}}$$

`[DERIVED]` **`P-06` measured the need for a *surrogate*, and reported it as the need for *identity*.**
Those are different questions, and separating them changes the result — §13.

⚠️ **Unsupported by the corpus:** *locator* (5) and *surrogate identifier* (7) have **no instance
anywhere in the eight records or the corpus scope**. They are `[ARCH]` vocabulary, admitted only to
keep the ladder complete.

# 2. The `P-06` identity test, tested

> *Persistence identity is forced when a thing must be referenced from a context that cannot reach it
> by path, or across a change to its own containing structure.*

## Is it **sufficient**? 🔴 **No.**

`[DERIVED]` Being unreachable-by-path forces **a reference mechanism**, and identity is only one of
four candidates — a **locator**, a **natural key**, or a **version** would also serve. The test
concludes "identity" where the evidence supports only "some stable reference".

## Is it **necessary**? 🔴 **No.**

`[DERIVED]` Identity can be forced with **no external reference at all**: if the estate must be able
to say *"this definition's association changed"* rather than *"one definition vanished and another
appeared"*, that assertion **presupposes the definition persisted** — whether or not anyone else
points at it.

## The six probes, answered

| probe | answer | witness |
|---|---|---|
| independent reference without independent persistence identity? | ✅ **yes** | implementation artifacts — `(path, symbol)` references them, nothing carries their sameness |
| persistence identity without external reference? | ✅ **yes** | the continuity assertion above — **internal, not referential** |
| a stable locator sufficient? | ⚠️ **`[OPEN]`** | **no locator scheme exists**; untestable on present evidence |
| a natural key sufficient? | ⭐ ✅ **yes, for some things** | a glyph's string; `K.D-03` under glyph-addressing |
| a version identifier sufficient? | ✅ **yes, for one thing** | **implementation artifacts** — git already versions them |
| continuity forcing identity while addressing still works? | ⭐⭐ ✅ **yes** | **`K` `D-03`/`D-05` re-disposition** — the path stayed valid **and** a sameness claim was still required |

## ⭐ Replacement — two forcing conditions, not one

$$\boxed{\begin{array}{ll}\textbf{F-REF} & \textbf{a thing must be referenceable under change} \Rightarrow \textbf{a stable REFERENCE mechanism}\\ & \textbf{(identity } \textit{or} \textbf{ locator } \textit{or} \textbf{ natural key } \textit{or} \textbf{ version)}\\[6pt] \textbf{F-CONT} & \textbf{a thing must support } \textit{"same thing, changed state"} \textbf{ as distinct from}\\ & \textit{"new thing, related to old"} \Rightarrow \textbf{IDENTITY, and nothing weaker}\end{array}}$$

$$\boxed{\textbf{Identity is forced by CONTINUITY. Reference is satisfiable by ADDRESSING.}}$$

`[DERIVED]` **The `P-06` test is replaced, not weakened.** It conflated the two conditions, which is
why its results were expressed as "forced" and "conditional" rather than as two different kinds of
obligation.

# 3. Occurrence — the critical test

**Requirement, isolated from every solution:**

| option | verdict |
|---|---|
| **A** stable path | 🔴 **falsified** — 56 files renamed this session; every held path broke silently |
| **B** stable locator | ⚠️ `[OPEN]` — no locator scheme exists to test |
| **C** persistent occurrence identity | 🔴 **not forced** — see below |
| **D** immutable occurrence identity | 🔴 **not forced** |
| **E** provenance sufficient to **reconstruct** the occurrence | ⭐ ✅ **this is the actual requirement** |
| **F** combination | ✅ **E, plus a declared scope** |

## Why identity is not forced

`[DERIVED]` Apply **F-CONT**: does anything need to assert *"this is the same occurrence as before"*?
**Only if an individual occurrence carries state that would be lost.**

`[EMP]` **In the eight records, no individual occurrence carries state.** Dispositions are assigned to
**readings** (`Π` R1…R7) and to **aggregates** (`Σ`'s 143 symbolic sites as a *count*) — **never to a
single site.**

$$\boxed{\begin{array}{c}\textbf{Occurrences are RE-MEASURED, not stored. Continuity does not apply to a re-derivable observation.}\\ \textbf{The requirement is RE-MEASURABILITY under a declared scope — } \mathbf{E}\textbf{, not } \mathbf{C}\textbf{ or } \mathbf{D}.\end{array}}$$

⭐ **And this lane already operates that way**: `S-1`/`P-20` — *"re-measure, never re-cite."* `[EMP]`
**The 56 renames broke nothing that mattered, precisely because occurrences were always re-measured.**

⚠️ **Requirement vs solution, stated as demanded:** the **requirement** is that any occurrence claim be
**reconstructible from its declared scope and pattern**. **Stable paths, locators and identities are
three possible solutions.** ⛔ **None is chosen.**

⚠️ **This conclusion is conditional on the `[EMP]` finding.** If a future disposition is ever attached
to a **single site**, `F-CONT` applies and occurrence identity becomes forced. **Recorded as a
falsification condition.**

# 4. Candidate identity — why it is different

**Testing the strong form:** *a candidate must have identity independent of every current
representation.*

| must candidate identity survive… | `[?]` | witness |
|---|:--:|---|
| **glyph change / glyph-membership change** | ⭐ `[DERIVED]` | `ℐ` — **while `Q3` is open**, whether `ℐ`/`𝓘` index one candidate or two is undecided |
| **definition change** | ✅ `[EMP]` | `ℐ` — **0 of 7 established**, and the candidate persists regardless |
| **association change** | ✅ `[EMP]` | `K` `D-03`/`D-05` moved to `𝒦`; **`𝒦` did not begin to exist at that moment** |
| **status change** | ✅ `[DERIVED]` | §8 |
| **grounding change** | ✅ `[DERIVED]` | §8 |
| **implementation change** | ✅ `[EMP]` | `ℐ` has **no** in-scope implementation and exists |
| **source change** | ✅ `[EMP]` | `Σ` `D-05`'s source re-marking does not touch `Σ`'s candidacy |

## ⭐ The decisive argument, and it is new to `P-07`

`[DERIVED]` **`15` §1B established that distinction is DISCOVERED, not created.** If `𝒦`'s distinctness
from `K` was **discovered** by containment analysis, then **`𝒦` existed before it was distinguished** —
the analysis found it, it did not make it.

$$\boxed{\begin{array}{c}\textbf{A candidate must be assertable as the same candidate across its own DISCOVERY.}\\ \textbf{That is } \mathbf{F\text{-}CONT} \textbf{, and only identity satisfies it.}\end{array}}$$

⚠️ **This is stronger than `P-06`'s grounds** *(no natural key; open `Q3`)*, and it does **not** depend
on `Q3`. ⛔ **`Q3` remains open and is not used.**

**And the strong form holds:** ✅ candidate identity must be independent of **every** current
representation — glyph, definition, association, status, grounding, implementation and source have each
been shown to vary while the candidate persists.

# 5. Association identity — formalized, and four notions separated

## The argument from first principles

| # | step | `[?]` |
|---|---|:--:|
| 1 | **endpoint absence** — for the case `I-10` exists for, there is **no candidate endpoint**, so `(definition, candidate)` does not exist | `[EMP]` |
| 2 | **`0..N`** — one definition may carry **several competing** candidate hypotheses, so `(definition)` alone does not key it | `[OPEN]` upper bound, `[EMP]` that `0..1` is not established |
| 3 | **positional instability** — a list under the definition reorders on add/retire | `[DERIVED]` |
| 4 | **external reference** — a revisit trigger (`P-18`) must point at *what may be wrong*, which here is the association | `[DERIVED]` |
| 5 | ⭐ **continuity** — an association must persist **from unresolved to resolved**. Deleting the unresolved one and creating a resolved one **destroys the record that it was ever unresolved** | ⭐ `[DERIVED]` — **`F-CONT`** |
| 6 | ⚠️ **status change, ALONE** — 🔴 **does NOT force identity.** A status is a **value on** a thing; *"it has a status"* ⇒ *"it has identity"* is exactly the inference `I-10` refused. It forces identity **only in combination with step 5** — when the **pre-change state must remain recoverable** | ⭐ `[DERIVED]` |

$$\boxed{\begin{array}{c}\textbf{Steps 1–4 show NO stable natural key exists } (\mathbf{F\text{-}REF}).\\ \textbf{Step 5 shows identity is the RIGHT KIND of thing } (\mathbf{F\text{-}CONT}).\\ \textbf{Both are required; neither alone suffices.}\end{array}}$$

⚠️ `[DERIVED]` **Step 6 is the trap.** `P-06`'s `I-10` chose *"identity forced"* **not because the
association bears a status** — a value never forces identity — but because of steps 1–5. **The
distinction is preserved here and must not be lost downstream.**

## Four notions the corpus forces apart

| notion | what it is | is the association this? |
|---|---|:--:|
| **association identity** | a standing claim *"definition D bears relation R to candidate C"*, with **its own lifecycle** | ⭐ ✅ **yes** |
| **assertion identity** | a claim made at a time, ⭐ **superseded rather than updated** — see §8 | 🔴 **no** — associations are **updated** |
| **relationship-instance identity** | a link with **no independent lifecycle** | 🔴 **no** — ours goes unresolved → resolved |
| **graph edge identity** | ⚠️ **an artifact of a storage model, not a semantic notion** | 🔴 **no — a category error to equate** |

⚠️ `[DERIVED]` **The association is not an edge.** An edge presupposes both endpoints; **the case that
forces the association's identity is exactly the case where one endpoint is absent.**

# 6. Definition identity — the conditional boundary, formalized

`P-06`: *"definition identity is forced only under candidate-addressing."*

## The dependency, made explicit

```
addressing strategy  →  which REFERENCE form is available  →  whether a SURROGATE is needed
     glyph-addressing        K.D-03 stays valid under re-disposition        no surrogate
 candidate-addressing        K.D-03 → 𝒦.D-01 on re-disposition             surrogate needed
```

## ⭐ But `F-CONT` cuts across the conditional

`[EMP]` `K` `D-03`/`D-05` were **re-dispositioned**: the definition **text never changed** — only its
candidate association did. To record that as *"this definition's association changed"* rather than
*"a definition disappeared and another appeared"* requires a **sameness claim about the definition.**

$$\boxed{\begin{array}{c}\textbf{Definition IDENTITY is forced UNCONDITIONALLY } (\mathbf{F\text{-}CONT}).\\ \textbf{Only the need for a SURROGATE is conditional on addressing } (\mathbf{F\text{-}REF}).\end{array}}$$

`[DERIVED]` **This is not a repetition of `P-06` — it is a correction of its category.** `P-06` asked
whether a surrogate was needed and answered *conditionally*, correctly. **The identity question was
never asked.** Under glyph-addressing the natural key `K.D-03` **is** the definition's identity (§1.1).

## The commissioned trichotomy, answered

| is definition identity… | verdict |
|---|---|
| **genuinely conditional?** | ✅ **yes — for the SURROGATE only.** Under glyph-addressing none is needed; under candidate-addressing one is |
| **merely one implementation solution?** | 🔴 **no.** The sameness claim is made by the estate itself *(the re-disposition record)*, not by a storage layer |
| ⭐ **forced by another invariant not yet considered?** | ⭐⭐ **YES — `F-CONT`, which `P-06` did not have.** This is the operative answer |

⛔ **Neither glyph- nor candidate-addressing is selected.**

# 7. Continuity under reinterpretation — the central question

**The transformation sequence:** `D1` status changes · `A1` unresolved → resolved · `D1`
re-dispositioned · new `D2` introduced · glyph changes · implementation changes.

| thing | across this sequence | `[?]` |
|---|---|:--:|
| **candidate `C`** | ⭐ **same thing, changed state** — throughout | `[DERIVED]` §4 |
| **definition `D1`** | ⭐ **same thing, changed state** — its text is untouched by any of the six | `[EMP]` |
| **association `A1`** | ⭐ **same thing, changed state** — unresolved → resolved is a **state** transition | `[DERIVED]` §5.5 |
| **new definition `D2`** | **new thing, related to old** — `D2` is not a state of `D1` | `[EMP]` — `Zero`'s `D-04` did not supersede `D-01` |
| **glyph** | ⚠️ **neither** — a "changed" glyph is a **different glyph**; the relation is between glyphs, not a state of one | `[DERIVED]` |
| **implementation artifact** | ⭐ **VERSION, not identity** — git already carries this | `[EMP]` |
| **assertion / status value** | ⭐ **new thing, superseding** — §8 | `[DERIVED]` |

## ⭐ The foundational distinction, derived

$$\boxed{\begin{array}{ll}\textbf{same thing, changed state} & \Rightarrow \textbf{the thing has IDENTITY and its state is a field}\\ \textbf{new thing, related to old} & \Rightarrow \textbf{two identities and a RELATION between them}\end{array}}$$

`[DERIVED]` **The test that separates them:** *is the change a change **to** the thing, or a change **of
which thing** is present?* — `D-03`'s re-disposition changed **to** it *(its association)*; `Zero`'s
`D-04` is a change **of which** definitions are present.

⚠️ ⭐ **And v2's `append-never-edit` rule already encodes half of this** — it treats every record change
as *supersession*. `[DERIVED]` **That is correct for assertions and WRONG for candidates, definitions
and associations**, which are **updated in state**. **Recorded as a tension, not repaired.**

# 8. Identity vs epistemic status

**Question: does a status change make it a different object?**

| item | `OPEN → SOLVED`, `candidate → refuted`, `unresolved → resolved` | `[?]` |
|---|---|:--:|
| **candidate** | 🔴 **same object.** `Zero`-as-a-state is **`[RF]`** and `Zero` remains one candidate | `[EMP]` |
| **definition** | 🔴 **same object.** `Zero`'s two `[RF]` definitions **are still its definitions** | `[EMP]` |
| **association** | 🔴 **same object** — this is the whole content of `I-10` | `[DERIVED]` |
| **occurrence** | ⚠️ **vacuous** — carries no state (§3) | `[EMP]` |
| **assertion** | ⭐ **DIFFERENT object.** An assertion's **content includes its claim**; changing the claim makes a **new** assertion that **supersedes** | `[DERIVED]` |
| **implementation artifact** | ⭐ **a new VERSION**, not a new object and not the same state | `[EMP]` |

$$\boxed{\begin{array}{c}\textbf{Candidates, definitions and associations are UPDATED. Assertions are SUPERSEDED.}\\ \textbf{Artifacts are VERSIONED. Occurrences are RE-MEASURED.}\\ \boxed{\textbf{Status is never identity.}}\end{array}}$$

⭐ **Four different continuity regimes, derived — not one.** `[DERIVED]`

# 9. Identity vs provenance

**Provenance identifies neither the thing nor its identity — it identifies a *claim's origin*.** And
the corpus forces **three distinct provenance roles**:

| role | of what | witnesses |
|---|---|---|
| **definition source** | of a **definition** | ⭐ `Σ` `D-05` — `code@kosmodel.py:Sigma`, **no textual origin** (`F-4`) |
| **establishment act** | of a **candidate** | `ℐ` → `G-67` *(naming)* · ratified 8 → **`C-022`** *(stipulation, warrant: "50 attack classes")* · `δ=0.3099` → **`FR-001`** *(measurement)* |
| **evidence supporting an association** | of an **association** | the containment argument for `𝒦`; the disposition reasons for `Π`'s R5/R6 |

## The commissioned four-way question, answered

> **Does provenance identify the thing, the assertion about it, the source of the assertion, or the establishment act?**

| does provenance identify… | verdict | witness |
|---|---|---|
| **the thing itself** | 🔴 **no** | `Σ` exists whatever `D-05`'s origin turns out to be; repairing `F-4` would not change `Σ` |
| **the assertion about the thing** | ⚠️ **no — it is ATTACHED to an assertion without identifying it** | a definition keeps its identity while its `source` field is re-marked |
| **the source of the assertion** | ✅ **yes — role 1** | `Σ` `D-05` → `code@kosmodel.py:Sigma` |
| **the establishment act** | ✅ **yes — role 2, a DIFFERENT role** | `G-67`, `C-022`, `FR-001` |

$$\boxed{\textbf{Provenance identifies an ORIGIN, never a thing. And the corpus forces TWO KINDS of origin plus a third role (evidence-for-association).}}$$

## ⭐ The proof that the roles are distinct from the artifact

`[EMP]` **`FR-001` plays two roles at once** — it is the **establishment act** for the candidate
`δ = 0.3099` **and** the **definition source** for its value.

$$\boxed{\begin{array}{c}\textbf{One artifact, two provenance roles.}\\ \textbf{Therefore the ROLE is a property of the LINK, not of the artifact — and the three must not be collapsed.}\end{array}}$$

⛔ **Not collapsed:** *source of definition* ≠ *act establishing candidate* ≠ *evidence supporting
association*.

# 10. Mathematical / statistical sanity check

⚠️ **Used only to detect category errors. No external ontology is imported, and no statistical model is
asserted of KnowledgeOS.**

| distinction | mapping observed | error it detects | `[?]` |
|---|---|---|:--:|
| **observation vs latent object** | ⭐ occurrence = **observation of a glyph** · candidate = **latent object** · definition = **hypothesis about it** | ⭐⭐ **you re-measure observations; you do not re-measure latent objects** — which is exactly why §3 gives occurrence re-measurability and §4 gives candidate identity | `[DERIVED]` |
| **label vs entity** | glyph = **label** · candidate = **entity** | 🔴 **"same label ⇒ same object"** — refuted by `𝒦`/`𝕂`/`K`; and `ℐ`/`𝓘` is **`[OPEN]`**, which is the *absence* of the inference, not its denial | `[EMP]` |
| **estimate vs parameter** | definition = **estimate** · candidate = **parameter** | 🔴 **"an estimate becomes the parameter"** — many definitions, one candidate (`1:N`); ⭐ **and `F-4` is exactly this error committed**: an implementation *estimate* recorded as the thing | `[DERIVED]` |
| **sample occurrence vs underlying object** | 143 summation sites are **samples of a glyph**, not of a candidate | 🔴 **"more occurrences ⇒ more definitions"** — the homonym rule prevents it | `[EMP]` |
| **hypothesis vs measured value** | `δ` the operation = **hypothesis** · `δ = 0.3099` = **measured value** | ⚠️ **a measured value MAY establish a candidate** *(`kind: constant`)* — ⛔ **but it does not thereby acquire the standing of a theoretical entity.** Two candidates, different kinds, **not a hierarchy** | `[DERIVED]` |
| **equivalence vs identity** | `≡_sem` vs `SameId` | ⭐ **the corpus already separates them** — `261.25`'s seven relations | `[EMP]` |
| **proposition vs truth status** | definition vs its `[RF]`/live status | 🔴 **"refuted ⇒ not a definition"** — refuted by `Zero` keeping its two `[RF]` readings | `[EMP]` |

⭐ **`[DERIVED]` The one category error actually committed in this estate is `F-4`** — *estimate treated
as parameter*, i.e. **an implementation fact recorded as a definition.** ⛔ **Not repaired.**

# 11. DDD sanity check

⚠️ **DDD categories are used as a cross-check, never as ontology. No category is adopted because it is
convenient.**

| item | DDD reading | `[?]` |
|---|---|:--:|
| **candidate** | **Entity** — identity + lifecycle, independent of representation | `[DERIVED]` |
| **definition** | **Entity** — identity (possibly a natural key) + state | `[DERIVED]` |
| **occurrence** | ⭐ **neither Entity nor Value Object — a MEASUREMENT** | `[DERIVED]`; ⚠️ **DDD has no clean category, which is itself informative** |
| **glyph** | **Value Object** — compared by value, no lifecycle | `[DERIVED]` |
| **establishment act** | ⭐ **a reference to an existing artifact**, not a Domain Event created by us | `[DERIVED]` |
| **implementation artifact** | **an external Entity, versioned outside our boundary** | `[EMP]` |
| **realization vs model-of** | **Value Object** on a link | `[DERIVED]` |
| **definition source** | **Value Object** on a definition | `[DERIVED]` |
| **assertion / status** | ⭐ **immutable Value Object, superseded not updated** — §8 | `[DERIVED]` |

## ⭐ Aggregate, Lifecycle, Invariant — and why no aggregate emerges

| DDD notion | finding | `[?]` |
|---|---|:--:|
| **Aggregate** | ⭐⭐ **NO aggregate boundary is established, and the obvious one is REFUTED.** The candidate is the only plausible root, but (i) **an association exists with its candidate endpoint ABSENT**, so it cannot lie inside a candidate aggregate — an aggregate member must be reachable from its root; and (ii) `definition → candidate` is **`0..N`**, so a definition cannot lie inside *one* candidate's boundary | ⭐ `[DERIVED]` |
| **Lifecycle** | **four regimes, not one** — updated · superseded · versioned · re-measured (§8). ⚠️ **A single aggregate cannot host four lifecycles** | `[DERIVED]` |
| **Invariant** | ⭐ **every evidenced invariant is GLOBAL, not aggregate-local**: the homonym rule *(same glyph ⇏ same candidate)* spans candidates · *distinction is discovered, not created* spans the estate · `append-never-edit` spans assertions · *re-measure, never re-cite* spans occurrences | `[DERIVED]` |
| **Domain Assertion** | ✅ **the one category that fits something exactly** — immutable, superseded not updated (§8) | `[DERIVED]` |
| **Domain Event** | 🔴 **fits nothing we own.** Establishment acts are **references to pre-existing artifacts**, not events we emit | `[DERIVED]` |

$$\boxed{\begin{array}{c}\textbf{Two independent reasons no aggregate emerges: an association can outlive the absence of its root,}\\ \textbf{and the invariants worth protecting are GLOBAL. } \mathbf{[DERIVED]}\end{array}}$$

⚠️ ⛔ **This is NOT a ruling that KnowledgeOS has no aggregates** — it is the finding that **the present
evidence establishes none**, and that the intuitive *candidate-as-root* reading is refuted. **Recorded
as `[OPEN]`, not decided.**

## The commissioned question — is the association an Entity, VO, Assertion or Relationship instance?

| candidate category | verdict |
|---|---|
| **Value Object** | 🔴 **refuted** — VOs have no identity and are compared by value; §5 shows no stable value-key exists |
| **Domain Event** | 🔴 **refuted** — it is a **standing claim**, not a thing that happened |
| **Assertion** | 🔴 **refuted** — assertions are **superseded**; the association is **updated** (§8) |
| **Relationship instance** | ⚠️ **structurally closest and insufficient** — such instances have **no independent lifecycle**; ours goes unresolved → resolved |
| ⭐ **Entity** | ✅ **the semantic requirements (identity + independent lifecycle + a state that changes) are Entity requirements** | 

$$\boxed{\begin{array}{c}\textbf{The association meets ENTITY requirements — } \mathbf{[DERIVED]}\textbf{, from the semantics, not from DDD.}\\ \textbf{KnowledgeOS therefore has a first-class entity that is ABOUT two other entities.}\end{array}}$$

⚠️ **`[ARCH]`** whether to *call* it an Entity is a modelling-language decision and is **not taken here.**

# 12. The formal identity matrix

| semantic item | identity required? | stable address required? | continuity required? | natural key sufficient? | external reference required? | evidence |
|---|:--:|:--:|:--:|:--:|:--:|---|
| **Glyph** | 🔴 no | ✅ yes | 🔴 no | ✅ **yes — its string** | ✅ | `Σ`, `ℐ`/`𝓘` |
| **Occurrence** | 🔴 **no** | ⚠️ **re-measurability instead** | 🔴 **no** | 🔴 **no — 56 renames** | 🔴 no | §3 |
| **Definition** | ⭐ ✅ **YES** | ✅ | ✅ **yes** | ⭐ **yes, IF glyph-addressed** | ✅ | `K` `D-03`/`D-05` |
| **Candidate** | ⭐ ✅ **YES** | ✅ | ✅ **yes** | 🔴 **no** — `Π` R2/R3 unnamed | ✅ | §4 |
| **Establishment act** | 🔴 no | ✅ yes | ✅ **yes — the citation must remain resolvable** | ✅ **yes** — `G-67`, `C-022`, `FR-001` | ✅ | §9 |
| **Def→cand association** | ⭐ ✅ **YES** | ✅ | ✅ **yes** | 🔴 **no** — absent endpoint | ✅ | §5 |
| **Implementation artifact** | 🔴 no | ✅ yes | ⚠️ **version, not continuity** | ✅ `(path, symbol)` | ✅ | §7, §8 |
| **Realization vs model-of** | 🔴 no | 🔴 no | 🔴 no | ✅ a value | 🔴 no | `ℐ` |
| **Definition source** | 🔴 no | ✅ **the cited source must stay resolvable** | ✅ **yes** | ✅ a typed value | ✅ | `F-4` |
| **Assertion / status** | 🔴 no | 🔴 no | 🔴 **no — superseded** | ✅ a value | 🔴 no | §8 |

$$\boxed{\textbf{identity} \neq \textbf{address} \neq \textbf{continuity} \textbf{ — the three columns disagree on 5 of 10 rows.}}$$

# 13. The minimum **identity kernel**

$$\boxed{\textbf{IDENTITY KERNEL} = \{\ \textbf{candidate},\ \textbf{definition},\ \textbf{association}\ \} \qquad \mathbf{N = 3}$$

| | forced by | note |
|---|---|---|
| **candidate** | **F-CONT** — must be assertable as the same candidate across its own **discovery** (§4) | ⭐ **and no natural key exists** ⇒ a **surrogate** is also required |
| **definition** | **F-CONT** — re-disposition changes its association, not the definition (§6) | ⭐ **a natural key suffices IF glyph-addressed** ⇒ **no surrogate forced** |
| **association** | **F-CONT** *(unresolved → resolved)* **and F-REF** *(no stable key exists)* (§5) | ⇒ a **surrogate** is also required |

## ⭐ Why this differs from `P-06`'s **2**

$$\boxed{\begin{array}{c}P\text{-}06 \textbf{ counted SURROGATE identifiers. } P\text{-}07 \textbf{ counts IDENTITIES.}\\ \textbf{Definition was missing from } P\text{-}06\textbf{'s two because its identity can be a NATURAL KEY,}\\ \textbf{and } P\text{-}06 \textbf{ never asked the identity question — only the surrogate question.}\end{array}}$$

⚠️ **This is a refinement, not a contradiction — `P-06` is not reopened.** Both counts are correct
about their own question:

| question | answer |
|---|---:|
| how many things need **identity**? | **3** |
| how many need an **assigned surrogate**? | **2** — candidate, association |
| how many need only a **stable address**? | **7** |

# 14. The minimum **continuity kernel**

> **What must remain referentially continuous across corpus evolution?**

$$\boxed{\textbf{CONTINUITY KERNEL} = \textbf{the 3 identities} + \textbf{3 citation classes} \qquad \mathbf{N = 6}$$

| | class | requirement |
|---|---|---|
| 1–3 | **candidate · definition · association** | full identity continuity — §13 |
| 4 | **establishment-act citations** | `G-67`, `C-022`, `FR-001` must stay **resolvable** — otherwise `I-11`'s warrant is lost |
| 5 | **definition-source citations** | otherwise `F-4` becomes unfixable — the origin of `Σ` `D-05` would be unrecoverable |
| 6 | **implementation-artifact citations** | ⚠️ **versioned, not continuous** — continuity of the *citation*, not of the artifact's state |

**Explicitly NOT in the continuity kernel:** **occurrence** *(re-measured — §3)* · **assertion/status**
*(superseded — §8)* · **glyph** *(a value; a changed glyph is a different glyph)* ·
**realization-vs-model-of** *(a value)*.

$$\boxed{\textbf{Identity kernel } (3) \subsetneq \textbf{ Continuity kernel } (6) \subsetneq \textbf{ Addressing mechanism } (\textbf{all } 10)$$

⭐ `[DERIVED]` **The three are strictly nested and must not be collapsed** — the continuity kernel is
**twice** the identity kernel, and addressing applies to everything.

# 15. What a future persistence architecture must be capable of representing

⛔ **No tables, no keys, no schema, no A/B/C/D.** The capability list only:

1. **three things that keep their identity** across status, association, glyph-membership, source and implementation change;
2. **two of those three carrying an assigned designator**, because no natural key exists for them;
3. **one identity expressible as a natural key** *(definition, if glyph-addressed)* — ⚠️ **and the architecture must not make that impossible**;
4. **an association that exists with one endpoint absent**, and persists as the endpoint arrives;
5. **one definition bearing several competing association hypotheses** — `0..N`, `[OPEN]`;
6. **occurrence claims reconstructible from a declared scope and pattern**, rather than stored as durable objects;
7. **four distinct continuity regimes** — updated · superseded · versioned · re-measured;
8. **three provenance roles on links**, not on artifacts, one artifact able to play two;
9. **six citation classes remaining resolvable** across corpus evolution, including **56-rename events**;
10. ⛔ **no capability that presumes `Q3` or `Q4`** — in particular, **nothing that requires a candidate to be nested under a glyph.**

# 16. Decision boundary

## Settled `[EMP]`
`(file, site)` is unstable — **56 renames** · **no individual occurrence carries state** · a candidate
persists through definition, association, implementation and source change (`ℐ`, `𝒦`, `Σ`) ·
definitions keep their text across re-disposition (`K` `D-03`/`D-05`) · `Zero` keeps two **`[RF]`**
definitions · **`FR-001` plays two provenance roles** · the corpus already separates equivalence from
identity (`261.25`) · **no locator scheme and no surrogate exists anywhere in the corpus**.

## Derived `[DERIVED]`
⭐ **`identity ≠ surrogate identifier`; a stable natural key IS an identity** · ⭐ **the `P-06` test is
neither sufficient nor necessary and is REPLACED by `F-REF` + `F-CONT`** · ⭐ **identity is forced by
CONTINUITY, not by reference** · **occurrence requires RE-MEASURABILITY, not identity** · ⭐
**definition identity is forced UNCONDITIONALLY; only its surrogate is conditional** · **candidate
identity is forced by its own discovery** · **association identity needs both `F-REF` and `F-CONT`** ·
**four continuity regimes** · **status is never identity** · **the association meets Entity
requirements** · ⭐ **no aggregate boundary is established and *candidate-as-root* is refuted — an
association outlives the absence of its root, and every evidenced invariant is global** · **provenance
identifies an ORIGIN, never a thing, and the corpus forces two kinds of origin** · ⚠️ **a status alone
never forces identity — `I-10` turned on the absent endpoint, not on the presence of a status** · **`F-4` is the *estimate-as-parameter* category error** · **identity kernel 3 ⊊
continuity kernel 6 ⊊ addressing 10** · ⚠️ **v2's `append-never-edit` is right for assertions and wrong
for candidates, definitions and associations** *(tension recorded, not repaired)*.

## Architectural choices `[ARCH]`
glyph- vs candidate-addressing *(decides whether the definition needs a surrogate)* · the
occurrence re-measurability mechanism · whether to adopt a locator scheme · whether to *call* the
association an Entity · artifact keying · the physical persistence pattern.

## Open `[OPEN]`
**`Q3`** *(kept open; §4's argument deliberately does not use it)* · **`Q4`** ·
**`definition → candidate` upper bound: `1` or `N`** · **occurrence identity mechanism** ·
**implementation-artifact lifecycle** *(nothing evidenced)* · **establishment-act promotion** *(shown
NOT required; not refused)* · **physical persistence pattern** · ⭐ **whether any aggregate boundary exists at all** · ⚠️ **whether a
locator is sufficient anywhere** — untestable, no scheme exists.

---

**P-07 IDENTITY / ADDRESSING / CONTINUITY DERIVED — IDENTITY KERNEL: 3 — CONTINUITY KERNEL: 6 (= 3 identities + 3 citation classes; strictly nested inside a 10-item addressing obligation) — NO PERSISTENCE ARCHITECTURE SELECTED — SCHEMA v3 NOT BEGUN.**
