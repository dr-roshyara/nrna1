---
artifact: 02-GAP-UPDATE-FROM-HPA-REVIEW-D285-5
date: 2026-08-31
input: `prompts/20260831-184644_…-d285x-template-mandate.md` **+** `prompts/20260831-184950_…-seven-stage-typing-mandate.md`
status: **2 mandates ADOPTED · 1 DEFECT FOUND IN MY OWN STRONGEST CLAIM · protocol applied to 8 hypotheses + 2 queue items**
---

# 02 · Gap Update from the HPA Supervisory Review of D285-5

## 1. What the review establishes

`D285-5` is **accepted as written** and made the **template** for all `D285-x` tests, with a 7-section
structure and five standing obligations (Part 7):

1. adopt as template · 2. execute remaining tests in the format ·
3. **ensure each test specifies its equality relation** · 4. document independence per property ·
5. classify each property `R0–RX`.

The review's own general principle:

> $$\text{A property stated without naming its equality relation is not a well-formed proposition.}$$

## 2. ⚠️ Applying obligation 3 to my own work found a defect — in `D285-6`

`D285-5` caught the missing-equality defect **in the action/result property**. The review elevated it
to a general rule. **I then applied the rule to my own strongest claim and it failed.**

`D285-6` asserted, unqualified:
$$(\mathcal{A},\mathcal{R}) = \pi_K(K_t)$$

**Executed** (`exec/t285_equality.py`):

| Equality | Holds? | Why |
|---|---|---|
| **structural** | 🔴 **FALSE** | `pi` image = `{Entity, Observation, Proposition, Relation, State}`; target = `{Assertion, Relation}`. **`Assertion` is not a ratified primitive at all** |
| **semantic** | ✅ **TRUE** | but **only after unpacking `Assertion`** into `{Proposition, Entity, Observation}` + `{id, c, t, Π}`, and **modulo the declared drop** of `{Event, Policy, Action}` |
| **observational** | 🔴 **FALSE** | `member`/`contradicts`/`supersede`/`lineage` are answerable in `(𝒜,ℛ)`; **`replay`, `policy-eval`, `authorize` are not** — they need `Event`/`Policy`/`Action` |

### The corrected statement

$$(\mathcal{A},\mathcal{R}) \;=_{\text{semantic}}\; \pi_K(K_t)$$
after unpacking `Assertion`, modulo the declared drop of `{Event, Policy, Action}` —
**and explicitly NOT `=_structural` nor `=_observational`.**

> **Outcome B stands, and it is weaker than an unqualified `=` implied.** *"Projection"* is the right
> word only for a **lossy, semantic-level** map, and the loss is now named: **three primitives and
> three query classes.**
>
> **This is the same defect class `D285-5` identified. It caught it in the action/result property; it
> was present in my own reconciliation result and I had not seen it.** The review's rule earned its
> keep on the first application.

## 3. Gap movements

| # | Gap | Movement |
|---|---|---|
| 1 | `π_K` stated without an equality relation | 🔴 **NEW, and CLOSED in the same pass** — restated as `=_semantic`, with the loss enumerated |
| 2 | *"the projection is lossy for replay"* (was asymmetry #3, a footnote) | 🔺 **PROMOTED to part of the claim itself** — it is not an aside, it is what `=_observational` failing *means* |
| 3 | `D285-x` artifacts lack a uniform test structure | 🟢 **CLOSED** — 7-section template adopted; 5 artifacts reformatted (§5) |
| 4 | equality relation unnamed in `D285-1`, `-2`, `-4`, `-7` | 🟢 **CLOSED** — each now carries an explicit §5 (§4 below) |
| 5 | `Qualify` has no body | 🔴 **UNCHANGED — `G1`, irreducible.** Still the sole blocker on computability |

## 4. Obligation 3 applied to every remaining artifact

| Artifact | Equality relation it actually needs | Result of naming it |
|---|---|---|
| **D285-1** state ontology matrix | **set equality on primitive NAMES** | ⚠️ **and that is a weak relation.** GN-75 measured `𝒜·ℛ·Σ·Q_t·𝒪` at **0 occurrences** in ratified artifacts — the lanes **do not share names**. So name-level comparison establishes *disjointness of vocabulary*, **not** disjointness of concepts. The 3 "shared" primitives are shared **by name**; conceptual identity is a separate, untested claim. **Recorded as a limit on D285-1.** |
| **D285-2** Knower ∉ K | **identity persistence** `𝒩(t) =_identity 𝒩(t+1)` | holds — `I-1` gives goal-ownership across transitions. **Note it is `=_identity`, not `=_structural`**: the Knower's *state* may change while the Knower persists. Naming this removes an ambiguity |
| **D285-4** transformation taxonomy | **none — type equality only** | the Θ rejection is a **type-checking** result (four carriers), not an equality claim. **Correctly requires no equality relation**, and saying so is part of the discipline |
| **D285-5** action/result | `=_semantic` | already specified — this is the template |
| **D285-6** projection | `=_semantic` | **corrected this pass** (§2) |
| **D285-7** kernel consequence matrix | **none — it is a dependency trace** | no equality claim asserted |

> **Two of six needed a correction; one needed an explicit "none required"; one carried a new limit.**
> Obligation 3 is not bookkeeping — it changed the strength of two results.

## 5. Reformatting performed

`D285-1`, `D285-2`, `D285-4`, `D285-6`, `D285-7` restructured to the mandated 7 sections:
**Property Statement · Trivial vs Substantive · Execution · Qualification · Equality Specification ·
Independence · Classification.**

`D285-8` (Gītā appendix) is **deliberately not reformatted** — it is not a test artifact and asserts
no property. Forcing it into a test template would misrepresent it. **Recorded as a declared
exception, not an omission.**

## 6. What was NOT done

The review's Part 7 says *"execute remaining D285-x tests using this format."* **All D285-x tests were
already executed** in the previous pass; this pass **reformats and re-qualifies** them. **No new
verdict was manufactured to fill a template slot**, and where a section does not apply (`D285-4`,
`D285-7` equality) it is marked *not applicable* rather than filled.

---

## 7. The second prompt — the D285 research protocol, frozen

`20260831-184950_step_285_d285-research-protocol-frozen-seven-stage-typing-mandate.md` freezes a
**7-stage protocol** distinct from the review's 7-section *artifact* template:

> source proposition → KnowledgeOS translation → formal hypothesis → **type/equality audit** →
> independent KOS derivation → executable/falsifiable test → **7-way classification**

and states the standard:

> $$\text{Every D285 hypothesis must be typed before it can be tested.}$$
> $$\text{Gītā provides candidate distinctions; KnowledgeOS research determines whether they survive.}$$
> $$\text{corroboration} \neq \text{derivation}$$

### Two differences from the first mandate, and both matter

| | First mandate (review) | Second mandate (protocol) |
|---|---|---|
| **scope** | how a **D285-x artifact** is structured | how a **hypothesis** is processed |
| **classification** | `R0–RX`, one axis | **7-way**: mathematical · architectural · DDD · philosophical analogy · Gītā corroboration · unresolved · governance |

**The 7-way classification is finer and it separates things `R0–RX` conflates** — notably
*architectural result* from *Gītā corroboration*. Applied in
`03-D285-PROTOCOL-CONFORMANT-HYPOTHESIS-DOSSIER.md`.

### The two items the prompt sent to the queue — resolved, not parked

`𝒦_ātma` and `Θ_total` were named as belonging *"in the D285 hypothesis queue, rather than the canon."*
**Both entered and both failed under stage 4/6:**

| Item | Failure |
|---|---|
| `𝒦_ātma` | every candidate invariant `P` **reduces to `Π` or `ℛ_der*`**; `Lineage = Π ∘ ℛ_der*` already supplies persistence ⇒ **`RX`** |
| `Θ_total` | the composition **does not type-check** — four carriers (`𝕂`, `𝒩`, `Q_t`, `W`) in one chain ⇒ **`RX`** |

> **A queue whose items are never adjudicated is a backlog.** Both are recorded as resolved with
> evidence, not as pending.

## 8. Additional gap movements from the second prompt

| # | Gap | Movement |
|---|---|---|
| 6 | hypotheses processed without a uniform protocol | 🟢 **CLOSED** — 7-stage protocol applied to 8 hypotheses + 2 queue items |
| 7 | classification too coarse (`R0–RX` conflates architecture with corroboration) | 🟢 **CLOSED** — 7-way classification adopted |
| 8 | `𝒦_ātma` / `Θ_total` status ambiguous ("queued" vs "rejected") | 🟢 **CLOSED** — both `RX` **with the failing test named** |
| 9 | *"Does the Gītā distinction correspond to a property KnowledgeOS independently requires?"* — the governing question | 🟢 **ANSWERED per hypothesis**: **4 yes (corroboration) · 2 no (both corpus-native, not philosophical) · 3 RX** |
