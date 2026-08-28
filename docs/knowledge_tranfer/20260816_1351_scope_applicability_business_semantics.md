# Step 5 — Scope & Applicability

### The dimension both prior models exposed independently

| | |
|---|---|
| **Kind** | ⭐ **BUSINESS SEMANTICS.** ⛔ *No classes · no aggregates · no schema · no implementation · no vocabulary adopted* |
| ⭐ **LANE** | ⛔⛔ **TRACK B — EXPLORATORY. OUTSIDE THE GOVERNED ARCHITECTURE LANE.** *Candidate input only; governs nothing* |
| ⭐ **Lane gate** | May become a candidate architectural input only after the current Architecture Baseline is **reconstructed · independently verified · accepted** |
| **Commission** | ARB, 2026-08-16 — *"Scope exists as prose, but the system cannot reason about it… likely the next fundamental concept before conflict analysis becomes meaningful"* |
| **Method** | ⭐ Measure the scope expressions that already run |
| **Preserved, not revisited** | Decisions **A**, **B** · `RM-1`…`RM-4` · `AM-1`…`AM-5` |
| **Date** | 2026-08-16 |

---

# 0 · Why this step exists

⭐ **Two independent models reached the same wall from opposite directions:**

| Model | What it found |
|---|---|
| **Rule** (Step 3 §4) | *"No rule anywhere states where it applies."* Applicability exists only as an accident of implementation |
| **Authority** (Step 4 §5) | All 20 grants carry a scope. ⭐ **Every one is prose.** *"Does this act fall inside that grant?"* is a human judgement every time |

> ### ⭐ **One gap, reached twice. That is the strongest signal in Track B so far** — and it is why conflict analysis has been correctly deferred at every step: **you cannot compare two things that cannot state where they apply.**

---

# 1 · The surprise: the organisation already reasons about scope — just not where it matters

⭐ **Scope is not uniformly prose. Measured, there are five distinct scope mechanisms, and two of them are fully machine-comparable.**

| # | Mechanism | Form | Comparable? |
|---|---|---|---|
| **1** | ⭐ **Document placement** | ⭐ **structured facets + precedence** | ⭐⭐ **YES** |
| **2** | ⭐ **Governed-document boundary** | ⭐ **include globs − exclude globs** | ⭐⭐ **YES — set arithmetic** |
| **3** | Tool permissions | pattern lists at three strengths | ⚠️ partly |
| **4** | Quality-gate rules | one regex + an allowlist of regexes | ⚠️ partly |
| **5** | ⛔ **Rules and Authority grants** | ⛔ **prose** | ⛔ **NO** |

## 1.1 ⭐⭐ The one that already works

**Document placement** expresses scope as **named facets with values**, and resolves competition with an explicit precedence rule:

```
   match { scope: session-state }                                → .claude
   match { scope: cross-product, maturity: [qualified,adopted] } → engineering
   match { scope: cross-product, maturity: research }            → PENDING
   match { scope: product-specific }                             → domain root

   ⭐ "First match wins."
```

> ### ⭐⭐ **This is, in miniature, everything the Rule Model said did not exist.**
>
> A set of rules · each with a **structured applicability condition** · **evaluated mechanically** · with a **declared precedence** when more than one matches · and an explicit **`PENDING`** outcome when the rules do not decide.

⛔ **And it governs where documents are filed. Nothing else uses it.**

## 1.2 ⭐ The other one

The **governed-document boundary** expresses scope as **set arithmetic** — an include set minus an exclude set:

```
   include:  docs/knowledge/**/*.md
   exclude:  docs/knowledge/archive/**
             docs/knowledge/**/README.scaffold.md
```

⭐ **Containment, intersection and difference are all computable over this form.** It is used to decide which documents a validator reads — and nothing else.

---

# 2 · What is scope, in business terms?

⭐ **From the five mechanisms, scope is consistently answering one question — but about different subjects:**

> **Scope is the set of things a statement is about.**

| Subject | *"about what?"* |
|---|---|
| A rule | the work, artifacts or behaviour it obliges |
| An authority grant | the activities it permits |
| A document boundary | the files a validator considers |
| A placement rule | the artifacts it files |
| A permission | the operations a tool may perform |

⭐ **Same shape, five subjects.** That is why the concept keeps reappearing — and why it has been solved locally five times and shared zero times.

---

# 3 · ⭐⭐ Scope is not one concept — it is at least three

⭐ **Applying Decision B's rule *(different questions ⇒ different concepts)* to scope, as it was applied to *verdict* and *rule*:**

| # | Concept | Answers | Example |
|---|---|---|---|
| ⭐ **S-1** | **Subject scope** | *what is this statement about?* | *production APIs in the payments product* |
| ⭐ **S-2** | **Authority scope** | *what may this actor act upon?* | *architecture design of the discovery capability* |
| ⭐ **S-3** | **Evaluation scope** | *what did the mechanism actually look at?* | *these 132 documents, excluding the archive* |

> ### ⭐⭐ **These are routinely conflated, and the conflation is invisible because all three are called "scope."**

**Why the distinction is load-bearing:**

```
   A rule's SUBJECT scope        ─┐
                                  ├─ must be COMPARED   → is the authority wide enough?
   An authority's SCOPE          ─┘

   A mechanism's EVALUATION scope ── determines what was CHECKED
                                     ⛔ never what the rule MEANS
```

⛔ **Conflating S-1 with S-3 is the more dangerous error:** it makes *"the checker did not look there"* indistinguishable from *"the rule does not apply there."* **One is a coverage gap; the other is a deliberate boundary. Today nothing distinguishes them.**

---

# 4 · The ARB's question, answered

> *"Who is responsible for a Rule, who is authorized to approve it, where that authority applies, and how that differs from the Rule's own scope?"*

⭐ **Four distinct things, and the record holds two of them:**

| Question | Concept | Recorded today? |
|---|---|---|
| Who is responsible for maintaining it? | **ownership** | ⛔ **no** — `AM-4` ruled it must be |
| Who may approve it? | **authority holder** | ⚠️ prose, 8 spellings of 2 actors |
| Where does that authority apply? | ⭐ **S-2 authority scope** | ⚠️ prose, 20/20 present |
| Where does the rule apply? | ⭐ **S-1 subject scope** | ⛔ **not expressible** |

## 4.1 ⭐⭐ The relationship the ARB was pointing at

```
      AUTHORITY SCOPE  (S-2)                 RULE SUBJECT SCOPE  (S-1)
      "may act upon…"                        "applies to…"
              │                                       │
              └───────────── must CONTAIN ────────────┘

   ⭐ An authority can only authorise a rule whose subject scope
      lies WITHIN the authority's own scope.
```

> ### ⭐⭐ **This is the check that makes `RM-3` real.**
>
> `RM-3` ruled that changing an authoritative rule requires authorization. **But an authorization is only meaningful if the authority's reach covers the rule's subject.** Otherwise a narrowly-scoped grant could authorise an organisation-wide rule and nothing would notice.

⛔ **Today that check is impossible** — not because the mechanism is missing, but because **neither side states its scope in a comparable form.**

---

# 5 · What "comparable" actually requires

⭐ **Business meaning, not mathematics. To ask *"is A inside B?"* the organisation must be able to answer three things about any scope:**

| # | Requirement | Business form |
|---|---|---|
| **1** | ⭐ **Named dimensions** | *product · environment · component · artifact kind · lifecycle phase* — an agreed list |
| **2** | ⭐ **Values from a known set** | *"production"* means the same thing in two statements |
| **3** | ⭐ **A rule for absence** | ⛔ **the hardest.** Does an unstated dimension mean *all*, or *unspecified*? |

## 5.1 ⛔ The absence question is not academic

**Both readings appear in the running mechanisms:**

| Mechanism | Unstated dimension means | Consequence |
|---|---|---|
| ⭐ Quality-gate rules | ⭐ **everything** — a pattern with no path restriction matches the whole tree | *silently broad* |
| ⭐ Document placement | ⭐ **no match** — falls through to the next rule, and possibly to `PENDING` | *safely narrow* |

> ### ⭐⭐ **The same silence means "everywhere" in one mechanism and "nowhere" in another.**
>
> ⛔ **Until the organisation rules which it means, no two scopes from different mechanisms can be compared at all** — and a comparison built without settling it would be *confidently wrong* rather than merely incomplete.

⭐ **And the corpus's own invariant already leans one way:** *absence is never permission.* **Applied to scope, that argues for *unspecified*, not *all*** — but that is a business ruling, not an inference I should make.

---

# 6 · The observed minimum semantic gap

| # | Scope must be able to state | Today | Nearest existing |
|---|---|---|---|
| **1** | ⭐ **which dimensions it constrains** | ⛔ **no** | ⭐ placement rules use named facets — for one purpose |
| **2** | ⭐ **values from an agreed set** | ⛔ **no** | ⭐ bounded contexts are enumerated — never referenced by a rule |
| **3** | ⭐ **what an unstated dimension means** | ⛔ **contradictory** | two mechanisms, two opposite conventions |
| **4** | ⭐ **containment — is A inside B?** | ⛔ **no** | ⭐ include/exclude globs support it — for one purpose |
| **5** | ⭐ **precedence when two apply** | ⛔ **no** | ⭐⭐ *"first match wins"* — for one purpose |
| **6** | **that scope is subject, authority, or evaluation** | ⛔ **no** | all three are called "scope" |

> ### ⭐ **Five of six already exist somewhere in the organisation. None exists where Rules and Authority need it.**
>
> ⛔ **This is not a design problem. It is a *reuse* problem** — the same shape as Decision A's finding about ownership, and the same shape as `AM-5`'s finding that the authority machinery has simply never been pointed at a rule.

---

# 7 · What this unblocks — and what it does not

| ⭐ Once scope is comparable | ⛔ Still not available |
|---|---|
| *"Which rules apply here?"* becomes answerable | ⛔ conflict detection — it also needs obligation, validity and strength |
| ⭐ *"Is this authority wide enough for this rule?"* becomes checkable — **`RM-3` becomes real** | ⛔ any implementation — Track B governs nothing |
| Coverage gaps become distinguishable from deliberate boundaries | ⛔ any vocabulary — `SC-1` below is unruled |
| Exceptions become boundable — **`RM-4` needs a scope narrower than the rule's** | ⛔ any new mechanism |

⭐ **Note the dependency chain that has now closed:**

```
   RM-4 exception scope ⊂ RM-1 rule scope ⊂ AM-5 authority scope
   ⛔ none of the three comparisons is possible today
```

---

# 8 · ⭐ `SC-2` RULED · the rest recommended, not ruled

> ## ⭐⭐ **`SC-2` — APPROVED, 2026-08-16**
>
> # **When a scope dimension is not specified, it is treated as UNSPECIFIED — not as "all". Missing scope must never silently broaden the applicability of an engineering Rule.**

**Business form:** ⭐ *EKS must not manufacture applicability from missing information.*

⚠️ **Two qualifications carried, both load-bearing:**

| # | Qualification |
|---|---|
| **1** | ⛔ **This does NOT require every Rule to restate every dimension.** It requires that, **for any dimension that matters to determining applicability**, absence must not expand reach |
| **2** | ⭐ **Controlled defaults remain permitted** — a Rule *type* may declare a default for a dimension — ⛔ **but such a default must itself be explicit and governed**, never implicit |

⭐ **It aligns with the corpus's own standing principle:** *absence is never permission.*

## 8.1 Recommended, ⛔ **not ruled**

| # | Question | ARB recommendation | Status |
|---|---|---|---|
| **SC-1** | Which dimensions describe where engineering knowledge applies? | ⭐ *establish a **small controlled set**, rather than letting each Rule invent its own* | ⛔ **not ruled** |
| **SC-3** | Distinguish subject · authority · evaluation scope? | ⭐ *"Yes, absolutely"* | ⛔ **not ruled** |
| **SC-4** | Must an authority cover the scope of what it authorises? | ⭐ *"Yes — otherwise `RM-3` cannot be reliably enforced"* | ⛔ **not ruled** |
| **SC-5** | Reuse the working placement scope model? | ⭐ *"Yes as a starting point — but reuse the **semantic idea**, not blindly the existing implementation.* ⛔ *Its data model is not yet proven sufficient for engineering knowledge"* | ⛔ **not ruled** |

⛔ **Recorded as recommendations so the distinction survives.** A recommendation carried forward as though ruled is the failure the whole provenance discipline exists to prevent.

---

# 8A · ⭐ Consequences of the `SC-2` ruling

## 8A.1 ⭐⭐ The hazard the ruling creates — **unspecified is not empty**

⭐ **The ruling inverts the risk profile of every future comparison, and the new risk is silent.**

| If silence meant… | Two scopes overlap? | Failure mode |
|---|---|---|
| ⛔ *"everywhere"* (rejected) | over-computed | **false conflicts** — noisy, but visible |
| ⭐ *"unspecified"* (ruled) | **cannot be computed** | ⛔⛔ **false NON-conflicts — silent** |

> ### ⛔⛔ **The specific hazard, named now so it is not discovered later:**
>
> An implementation that treats an unspecified dimension as an **empty set** will compute *no intersection*, conclude *disjoint*, and report **NO CONFLICT** — where a conflict may well exist.
>
> ### ⭐ **In set terms: `unknown ≠ ∅`.** Unspecified means *the set is not known*, never *the set is empty*.

⭐ **Under `SC-2`, a false negative is the failure to guard against — and false negatives in conflict detection are worse than false positives, because nothing surfaces them.**

### ⭐⭐ Architecture invariant CANDIDATE — elevated by the ARB, 2026-08-16

> # **Unknown applicability must never be represented as non-applicability.**
>
> ```
>    UNKNOWN  ≠  DOES NOT APPLY
>    UNKNOWN  ≠  EMPTY SCOPE
> ```

⭐ **Stated this way it tells an implementer what the system must never *do*, rather than what a field must *mean*** — which is the difference between a definition and an invariant.

⚠️ **Status: CANDIDATE.** ⛔ *Track B. It becomes a canonical invariant only through the maturation chain and an adoption act — the same path the one fully-realised invariant took.*

⭐ **It binds five future capabilities at once:** rule applicability · authorization checks · exception handling · conflict detection · AI answers.

## 8A.2 ⭐ Applicability now has three outcomes, not two

**Direct consequence.** Evaluating a Rule against a context can no longer answer yes/no:

```
   APPLIES              the scope determines it applies
   DOES NOT APPLY       the scope determines it does not
   ⭐ CANNOT DETERMINE   a dimension that matters is unspecified
```

⛔ **And `CANNOT DETERMINE` must never satisfy a gate, and must never be reported as `DOES NOT APPLY`.** *Same posture the corpus already takes toward unknown outcomes elsewhere.*

⭐ **This is the behaviour the ARB described as the point of the ruling:** *"I cannot safely determine whether this rule applies to your environment"* — an honest answer, rather than a confident wrong one.

## 8A.3 ⚠️ One running mechanism is non-conformant, and one already conforms

| Mechanism | Absence means | Under `SC-2` |
|---|---|---|
| ⭐ **Document placement** | no match → next rule → possibly `PENDING` | ⭐ **already conformant** |
| ⛔ **Quality-gate rules** | a pattern with no path restriction matches the whole tree | ⛔ **non-conformant — silence broadens reach** |

⛔ **Recorded as an observation only.** Track B governs nothing; **no change to any running mechanism is proposed, requested, or authorised by this record.**

## 8A.4 ⚠️ The ruling is sound and **not yet executable**

⭐ **`SC-2` binds "any dimension that matters to determining applicability." Which dimensions matter is `SC-1` — and `SC-1` is not ruled.**

> **So `SC-2` is decided and cannot yet be applied.** ⛔ *That is sequencing, not a defect* — it names the next decision precisely: **without an agreed dimension set, "unspecified" has no dimensions to be unspecified about.**

---

# 9 · Bottom line

**The organisation can already reason about scope. It does so in two places, well, and neither is where it matters.**

Document placement has structured conditions, mechanical evaluation, an explicit precedence rule and an honest *undecided* outcome. The governed-document boundary supports containment and difference. **Rules and authority grants — the two things that most need to be compared — have prose.**

> ### ⭐ **Three findings for the sequence:**
>
> **One.** *Scope* is at least three concepts wearing one word — subject, authority, evaluation. **Decision B's rule applies here as it applied to *verdict* and *rule*.**
>
> **Two.** The most consequential unanswered question is the smallest-sounding: ⭐ **what does silence mean?** Two running mechanisms answer it in opposite directions, and no comparison is safe until it is ruled.
>
> **Three.** ⭐⭐ **The check that makes `RM-3` operational is a containment test between an authority's scope and a rule's subject scope.** Neither side can state its scope comparably, so the check cannot exist — which is why authorisation of rules is, today, a declaration rather than a verification.

**This closes the semantic chain Track B set out to establish: Rule → Authority → Scope.** ⛔ **Conflict analysis remains correctly downstream, and correctly not started.**

---

*Step 5 of the ARB sequence, Track B. Derived by measuring the five scope mechanisms in use: document placement · the governed-document boundary · tool permissions · quality-gate patterns · authority grants. Structural claims are `OBSERVED`; the three-concept split and the containment relation are `INFERRED`. Decisions A, B, `RM-1`…`RM-4` and `AM-1`…`AM-5` preserved and not revisited. ⛔ **No design · no schema · no vocabulary adopted · no running mechanism changed · nothing outside `docs/knowledge_tranfer/` touched.***

***PROPOSED — Track B exploratory. Governs nothing.***
