# `SC-1` Preparation — Which dimensions describe where engineering knowledge applies?

### Each candidate as a developer scenario, and a recommended smallest set

| | |
|---|---|
| **Kind** | ⭐ **DECISION PREPARATION.** ⛔ *Not a ruling request from prior documents · no schema · no vocabulary adopted · no implementation* |
| ⭐ **LANE** | ⛔ **TRACK B — EXPLORATORY.** *Governs nothing* |
| **Commission** | ARB, 2026-08-16 — *"translate each candidate dimension into concrete developer scenarios and recommend the smallest useful set"* |
| **Method** | ⭐ **Every candidate must show a rule whose behaviour ALREADY varies by it.** ⛔ *A dimension that changes no existing behaviour is not evidenced* |
| **Preserved** | Decisions **A**, **B** · `RM-1`…`RM-4` · `AM-1`…`AM-5` · ⭐ **`SC-2` RULED** |
| **Date** | 2026-08-16 |

---

# 0 · The test applied

⭐ **A dimension earns its place only if it answers yes to both:**

> **1. Does some rule's behaviour already differ by this dimension, in a running mechanism?**
> **2. Would a developer get a wrong answer without it?**

⛔ **Candidates that merely sound useful are rejected.** One is.

---

# 1 · Candidate: **System location** *(product → component)*

## The developer scenario

> A developer is adding a cohesion check. A rule says *"every capability must be registered before it is implemented."*
>
> **Does that rule apply to their work?**
>
> It depends entirely on whether they are building **a reusable engineering capability** or **a product-specific mechanism** — and those two answers have different owners under Decision A.

## What breaks without it

⛔ **Decision A becomes unenforceable.** The ruling says *"reusable engineering capability → platform accountability; product-specific configuration → product accountability."* **You cannot apply an ownership split to statements that cannot say which side they are about.**

## Evidence it already varies behaviour

| Evidence | What it shows |
|---|---|
| ⭐ Three documentation domains, each with its own root | placement differs by product |
| ⭐ Placement rules keyed on `product-specific` vs `cross-product` | ⭐ **a rule whose outcome changes by this dimension, mechanically** |
| Twelve bounded contexts, each with a declared code path | the finer granularity is already enumerated |
| The tactical-DDD reminder fires on `app/Contexts/*/Domain/` and `app/Domain/`, silent elsewhere | ⭐ behaviour differs by location within a product |

## ⭐ Important: product and component are **one axis, not two**

A component belongs to exactly one product. They are **granularities of a single hierarchy**, not orthogonal dimensions:

```
   PublicDigit ──▶ Membership ──▶ Domain layer
   KnowledgeOS ──▶ observation runtime
```

⭐ **Recommending them as two flat dimensions would double the vocabulary while describing one thing.** Recommended as **one hierarchical dimension**.

> ### ✅ **PASSES both tests.**

---

# 2 · Candidate: **Environment**

## The developer scenario

> A developer runs a database refresh. The system **blocks it**.
>
> The same developer, the same command, the same code, five seconds later with `--env=testing` — **permitted**.

⭐ **Nothing changed except the environment, and the verdict inverted.**

## What breaks without it

⛔ **One of only two blocking gates in the organisation cannot state its own condition.** The rule *is* an environment condition; without the dimension it is unexpressible as a rule at all — it survives only as a hard-coded pattern match inside a script.

## Evidence it already varies behaviour

The destructive-command gate permits when the environment is testing — by explicit flag, by environment variable, or by database name — and blocks otherwise. ⭐ **This is an environment-scoped rule running in production today, and it is one of the two rules in the organisation that actually stops something.**

> ### ✅ **PASSES both tests.**

---

# 3 · Candidate: **Artifact kind**

## The developer scenario

> A developer writes an **aggregate design** document. Validation requires it to link to a discovery document, a state machine, **and** a test.
>
> The same developer writes a **domain-model** document. It requires a discovery link only.
>
> The same developer writes a **guide**. It requires none of them.

## What breaks without it

⛔ **Every document would carry every obligation.** The alternative to this dimension is not fewer rules — it is one maximal rule applied to everything, which no author could satisfy.

## Evidence it already varies behaviour

| Evidence | What it shows |
|---|---|
| ⭐⭐ Traceability obligations declared **per artifact kind** — `[ddd-discovery]` for one type, `[ddd-discovery, state-machine, test]` for another | ⭐ **differing obligations by dimension value, enforced by a linter** |
| The kind field is **required** on every governed document, validated against a controlled list of 31 values | the vocabulary already exists and is already enforced |

> ### ⭐⭐ **This is the most machine-enforced dimension in the organisation — and the only one where *differing obligations by dimension value* already run.**

> ### ✅ **PASSES both tests, most strongly of the five.**

---

# 4 · Candidate: **Lifecycle phase**

## The developer scenario

> A developer edits a document. A warning fires: *this document is frozen and your change carries no decision record.*
>
> The same developer edits a different document — **silence**.
>
> The only difference: one is `frozen`, the other is `draft`.

## What breaks without it

⛔ **Either every edit demands a decision record, or none does.** Both are wrong, and the organisation already knows it — which is why the check exists.

## Evidence it already varies behaviour

Two validation rules key on lifecycle: one fires only on frozen documents changed without an accompanying decision record; another fires only when a review date has passed. ⭐ **Eight lifecycle states are enumerated with an ordering and a terminal marker.**

> ### ✅ **PASSES both tests.**

---

# 5 · ⛔ Candidate REJECTED: **Audience**

⭐ **A controlled vocabulary of nine values already exists** — newcomer · developer · architect · reviewer · product-owner · committee · ai · operations · security — and it is a required-ish field on governed documents.

## ⛔ Why it is not a scope dimension

> **Audience answers *who should read this*. Scope answers *where this applies*.**

**A rule does not stop applying because an architect rather than a developer is looking at it.**

⛔ **Including audience would conflate applicability with relevance** — precisely the distinction already recorded as `RELEVANCE ≠ AUTHORITY`.

> ### ⭐ **Audience is real, useful, and belongs to delivery and routing — not to applicability.** ⛔ **Recommended: excluded from the scope dimension set.**

---

# 6 · ⭐⭐ The finding that changes the shape of `SC-1`

## 6.1 Dimensions are **not universal**

| Subject | System location | Environment | Artifact kind | Lifecycle |
|---|---|---|---|---|
| A governed document | ✅ | ⛔ **meaningless** | ✅ | ✅ |
| A running service | ✅ | ✅ | ⛔ **meaningless** | ⚠️ different sense |
| A code component | ✅ | ✅ | ⛔ | ⚠️ |

> ### ⛔⛔ **This collides directly with the `SC-2` ruling, and the collision is fatal if unhandled.**

**Worked:** a rule about documents leaves `environment` blank — because documents have no environment.

Under `SC-2`, blank means **unspecified**. Unspecified means **`CANNOT DETERMINE`**.

> ### ⛔ **Result: every document rule would be permanently undeterminable, on a dimension that could never meaningfully apply to it.** `SC-2` would make itself unusable.

## 6.2 ⭐ The resolution — three states per dimension, not two

| State | Meaning | Blocks determination? |
|---|---|---|
| ⭐ **SPECIFIED** | a value is given | ⛔ no |
| ⭐ **UNSPECIFIED** | the dimension **applies** to this subject, and no value was given | ⭐⭐ **YES — `SC-2`** |
| ⭐⭐ **NOT APPLICABLE** | the dimension **cannot apply** to this kind of subject | ⛔ **no — it is determinate** |

> ### ⭐⭐ **`NOT APPLICABLE` is determinate. `UNSPECIFIED` is not. Conflating them defeats `SC-2` in one direction or the other.**
>
> ⛔ Treat *not applicable* as *unspecified* → nothing is ever determinable.
> ⛔ Treat *unspecified* as *not applicable* → ⭐ **`SC-2` is silently reversed**, and missing scope broadens reach again.

## 6.3 ⭐ Consequence for what `SC-1` must decide

> **`SC-1` cannot rule only *which dimensions exist*. It must also rule *which kinds of subject each dimension applies to*.**

⛔ **Otherwise `SC-2` — already ruled — cannot be operated.**

---

# 7 · ⭐ Recommended smallest useful set

## ⭐ **Four dimensions**

| # | Dimension | Applies to | Why it is necessary |
|---|---|---|---|
| **1** | ⭐ **System location** *(hierarchical: product → component)* | everything | ⭐ **Decision A is unenforceable without it** |
| **2** | ⭐ **Environment** | running things only | ⭐ the organisation's blocking gate is an environment condition |
| **3** | ⭐ **Artifact kind** | knowledge objects only | ⭐⭐ already varies obligations, already enforced |
| **4** | ⭐ **Lifecycle phase** | everything | already varies obligations, already enforced |

⛔ **Excluded: audience** *(§5)* — real, but a delivery concern.

## 7.1 Why four and not five

⭐ **Product and component are one hierarchy, not two axes.** Counting them separately would double the vocabulary to describe a single containment relationship the organisation already models — twelve contexts each declaring the product path they sit under.

## 7.2 ⭐ Why start small, on the organisation's own principle

| | |
|---|---|
| ⭐ **Adding a dimension later** | **cheap** — a statement gains one facet; existing statements are unaffected |
| ⛔ **Removing a dimension later** | **expensive** — every statement that used it must be re-scoped, and every comparison recomputed |

> ⭐ **Asymmetric cost argues for the smallest evidenced set** — which is also the organisation's standing rule: *structure follows demonstrated need.*

⚠️ **Every one of the four is recommended because behaviour **already** differs by it in a running mechanism.** ⛔ **None is proposed on expectation.**

---

# 8 · ⭐ What ruling `SC-1` would unlock

| ⭐ Unlocked | ⛔ Still not available |
|---|---|
| ⭐ **`SC-2` becomes operable** — there are finally dimensions to be unspecified about | ⛔ conflict detection — still needs obligation, validity, strength |
| *"Which rules apply here?"* becomes a formable question | ⛔ any implementation — Track B governs nothing |
| ⭐ **The containment test behind `RM-3`** — is this authority wide enough for this rule? — becomes expressible | ⛔ any comparison algorithm |
| Exception scope ⊂ rule scope ⊂ authority scope becomes checkable in principle | ⛔ any vocabulary of values — see §9 |

---

# 9 · ⛔ What `SC-1` deliberately does **not** settle

| Not settled | Why |
|---|---|
| ⛔ **The values inside each dimension** | *which environments? which lifecycle states?* Some vocabularies exist already; whether they are the right ones is a separate question |
| ⛔ **How a scope is written down** | ⭐ a representation decision, not a semantic one — the ARB's caution against declaring a universal scope schema stands |
| ⛔ **Whether the existing placement model is reused** | `SC-5` — recommended as a *semantic pattern*, not as a data model |
| ⛔ **Precedence when two statements both apply** | ⭐ a separate concept — *"first match wins"* exists in one mechanism and has never been generalised |

---

# 10 · The decision, framed for ruling

> ## **`SC-1` — Which dimensions should EKS use to describe where engineering knowledge applies?**

**Evidence** → four dimensions each demonstrably change the behaviour of a rule that runs today; a fifth candidate changes none and is excluded.

**Business meaning** → these are the questions a developer must be able to answer to know whether a piece of engineering knowledge is about their situation: *where in the system · in which environment · what kind of thing · at what stage of its life.*

**Recommendation** →

> ⭐ **Adopt four dimensions — system location (hierarchical), environment, artifact kind, lifecycle phase — and rule which kinds of subject each applies to.** ⛔ **Exclude audience.**

⚠️ **And the part that cannot be deferred:** §6 shows `SC-1` must also settle **`NOT APPLICABLE` as distinct from `UNSPECIFIED`**, or the already-ruled `SC-2` cannot be operated at all.

**Your decision.**

---

# 11 · ⭐ The prepared ruling text — ARB formulation, 2026-08-16

> # ⭐⭐ **RULED — 2026-08-16 20:40**
>
> **The human act was performed.** Recorded verbatim below. ⚠️ **Ruled *inside Track B*** — it governs nothing outside the exploratory lane until the Architecture Baseline is reconstructed, independently verified and accepted.

> ### **`SC-1` — APPROVED:** EKS shall describe applicability using four dimensions: **system location (product → component hierarchy), environment, artifact kind, and lifecycle phase**. Audience is not an applicability dimension.
>
> ### For each subject kind, EKS shall also determine which of these dimensions are applicable. A scope dimension may then be **specified**, **unspecified**, or **not applicable**. ⭐ ***"Not applicable" is a governed property of the subject kind, not a declaration available to an individual statement author. "Unspecified" must not be treated as "not applicable."***

⭐ **The formulation folds the three-state model INTO `SC-1` rather than leaving it separate** — §6.3 showed `SC-1` is inoperable without it — **and the final two sentences close the §12 loophole.**

## 11.0 ⭐ The two questions the ruling separates

```
   SUBJECT KIND                          STATEMENT
   "which dimensions exist here?"        "what values apply?"
          ↓                                     ↓
   ⭐ GOVERNED CENTRALLY                  specified by the author
   once per kind                          per statement
```

```
   SPECIFIED       → DETERMINATE
   NOT APPLICABLE  → DETERMINATE      ⭐ governed, not declared
   UNSPECIFIED     → CANNOT DETERMINE  ⭐ SC-2 preserved
```

## 11.1 ⛔ What it explicitly does not decide

the allowed values inside each dimension · the storage format · the final scope syntax · precedence between multiple matching statements · any implementation.

## 11.2 ⭐⭐ What the ruling exposes immediately — three of four dimensions already have vocabularies; **one has none**

⭐ **Measured at the moment of ruling:**

| Ruled dimension | Controlled vocabulary today | Values |
|---|---|---|
| **System location** — product | ⭐ **exists** — registered documentation domains | **3** |
| **System location** — component | ⭐ **exists** — enumerated bounded contexts, each with a declared code path | **12** |
| **Artifact kind** | ⭐⭐ **exists and is lint-enforced** | **31** |
| **Lifecycle phase** | ⭐⭐ **exists, ordered, with a terminal marker** | **8** |
| ⛔ **Environment** | ⛔⛔ **NONE — no schema file, no controlled list** | ⛔ **0** |

> ### ⭐ **`SC-1` names four dimensions. Three arrive with governed vocabularies already running. The fourth has nothing.**

⛔ **And environment is the one dimension whose rule actually *blocks*.** The destructive-command gate decides by inspecting an environment variable, a command flag, or a database name — ⭐ **three ad-hoc detections standing in for a vocabulary that was never declared.**

⚠️ **Consequence, recorded not proposed:** whatever follows `SC-1`, the value-vocabulary work is **not evenly distributed**. Three dimensions need review of an existing list; **one needs a list to exist at all.**

## 11.3 ⭐ What is now unlocked

| ⭐ Newly possible | ⛔ Still not available |
|---|---|
| ⚠️ **`SC-2` becomes operable *in principle*** — there are finally dimensions for a statement to be unspecified *about*. ⛔ **Full operational applicability still depends on the dimension VALUES and the subject-kind applicability rules — neither exists yet** *(ARB refinement, 2026-08-16)* | ⛔ conflict detection — still needs obligation, validity, strength |
| ⭐ The containment test behind `RM-3` — *is this authority wide enough for this rule?* — becomes **expressible** | ⛔ any comparison algorithm |
| ⚠️ `exception scope ⊂ rule scope ⊂ authority scope` — ⛔ **a CANDIDATE RELATIONSHIP, semantic reasoning only.** *Not an adopted universal law; it holds only once the relevant business decisions are complete* **(ARB refinement, 2026-08-16)** | ⛔ any implementation — Track B governs nothing |
| A statement can distinguish *does not apply here* from *cannot tell* | ⛔ the values inside each dimension (§11.2) |

⭐ **The semantic chain Track B set out to build has completed its first pass: Rule → Authority → Scope.** ⚠️ *First pass — not a closed model.*

---

# 12 · ⛔⛔ One thing to settle BEFORE ruling — the `NOT APPLICABLE` loophole

⭐ **Folding the three-state model into `SC-1` creates an escape hatch that did not exist a moment ago, and it is worth naming now rather than discovering it in use.**

## 12.1 The mechanism of the escape

```
   UNSPECIFIED      blocks determination   →  CANNOT DETERMINE   ⭐ SC-2 working
   NOT APPLICABLE   determinate            →  does not block     ⛔ SC-2 bypassed
```

> ### ⛔ **If the author of a statement may declare a dimension "not applicable", then anyone who finds `CANNOT DETERMINE` inconvenient can escape it by assertion.**

**Worked:**

> A rule about production services omits `environment`. Under `SC-2` that is *unspecified* → **`CANNOT DETERMINE`** → someone must clarify.
>
> ⛔ The author instead writes `environment: not applicable`. Now it is **determinate**, nothing blocks, and the rule silently applies everywhere the other dimensions permit — ⭐ **which is exactly the "silence broadens reach" behaviour `SC-2` was ruled to prevent.**

## 12.2 ⭐ The distinction that closes it

| | Who may decide it | Why |
|---|---|---|
| ⭐ **Does this dimension apply to this KIND of subject?** | ⭐⭐ **governed centrally, once per subject kind** | *documents have no environment* is a fact about documents, not about one author's rule |
| **What VALUE does this dimension take here?** | the statement's author | a per-statement fact |

> ### ⭐⭐ **`NOT APPLICABLE` must be a property of the SUBJECT KIND, not a claim a statement may make about itself.**
>
> ⭐ *Documents have no environment — that is decided once, for all documents. An author may not declare it for their own rule.*

⭐ **Consequence for the ruling:** `SC-1` should state **who** determines applicability of a dimension to a subject kind. ⛔ **Without that, the three-state model reopens the hole `SC-2` closed** — and it reopens it in a way that looks compliant.

⚠️ **This is a small addition to the ruling text, not a change to its substance.** *The four dimensions, the exclusion of audience, and the three-state model all stand.*

## 12.3 ✅ **CLOSED** — ARB, 2026-08-16

⭐ **The two added sentences close it exactly:**

| The escape | What closes it |
|---|---|
| author declares `not applicable` to dodge `CANNOT DETERMINE` | ⭐ *"a **governed property of the subject kind**, not a declaration available to an individual statement author"* |
| `unspecified` quietly read as `not applicable` | ⭐ *"**must not be treated as**"* — an explicit prohibition, not an implication |

## 12.4 ⚠️ Residual, recorded — ⛔ not raised as a blocker

⭐ **The ruling governs *which dimensions apply to a subject kind*. It does not state whether the set of **subject kinds** is itself governed.**

> If a statement author may mint a new subject kind, they inherit the power to define its dimension-applicability profile — **the same escape, one level up.**

⚠️ **Low risk, and the practice already exists:** the organisation governs its artifact-kind vocabulary as a controlled list of 31 values, validated mechanically. ⭐ **The precedent is established; it is simply not stated in this ruling.**

⛔ **Recorded as a residual to watch, not a reason to delay.** *Vocabulary management is inside the zone the ARB deliberately deferred.*

---

*Prepared for `SC-1` on ARB instruction, Track B. Every dimension is justified by a running mechanism whose behaviour already differs by it; the rejected candidate is justified by the absence of any such mechanism. Structural claims are `OBSERVED`; the hierarchy argument and the three-state resolution are `INFERRED`. ⛔ **No schema · no vocabulary of values adopted · no implementation · nothing outside `docs/knowledge_tranfer/` touched.***

***PROPOSED — Track B exploratory. Governs nothing.***
