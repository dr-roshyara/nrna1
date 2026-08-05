# KnowledgeOS — Canonical Meta-Model

| | |
|---|---|
| **Kind** | ⭐ **STRATEGIC DDD SEMANTIC MODEL.** ⛔ ***No new principle · no new document class · no repository redesign · no implementation · no tooling · no ADR.*** |
| **Status** | ⚠️ **CANDIDATE — NOT ADOPTED** |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Commission** | Principal Knowledge Engineer / Strategic DDD Architect, 2026-08-02 — *"derive the canonical meta-model all future artifacts must conform to"* |
| ⭐ **Derived from, not invented** | **`RQ-002_Knowledge_Meta_Model.md`** *(COMPLETE, awaiting ARB)* · `Engineering_Platform_Knowledge_Metamodel` *(CANDIDATE, gate OQ-ENG-004)* · the executable **L-A** schema |
| **Success criterion** | ⭐ **every existing document classifiable without changing the model** — ⭐ **tested in §4** |

> ## ⛔ **TWO CONCESSIONS BEFORE THE MODEL**
>
> | # | | |
> |---|---|---|
> | **1** | ⛔ **`INTENDED` is WITHDRAWN as a fourth evidence grade** | ⭐ **Intent is not evidence.** *And the repository's own rule catches it: **CLAUDE.md** — "determine whether it is **a new dimension**, **an overloaded existing dimension**, or **merely another value**." **`INTENDED` was an overloaded existing dimension.** I added it inside a document about evidence discipline* |
> | ⭐ **2** | ⭐ ***"Mission is ENACTED"* replaces *"mission is implicit"*** | *Stronger and more accurate. **The platform is not describing its mission — it is already behaving according to it*** |
>
> ⭐ **And the reviewer's §8 is adopted structurally, not rhetorically:** *documents become the **lowest** layer of this model, never its centre — see §2, the `Knowledge ≠ Artifact` split.*

---

> # ⛔⛔ **ANNOTATED 2026-08-02 — THREE CORRECTIONS**
>
> **Record: `KnowledgeOS_Relationship_Ontology.md`.**
>
> | # | Correction |
> |---|---|
> | ⭐⭐ **1** | ⛔ **LEVEL MIXING — my third instance of that error class.** *§2's six concepts are **not siblings**: **Purpose** (Mission) · **Platform** (Capability, Knowledge) · **Representation** (Artifact) · **Application** (PKS, Product) are four ontological layers listed as one set* |
> | ⭐⭐ **2** | ⛔ **`PKS` is a CONTAINER, not a layer below `ARTIFACT`.** *A PKS **contains** artifacts. `KNOWLEDGE SPACE` is now admitted as the container concept, defined by a **declared boundary** (`scope.include`), not by a folder* |
> | ⭐ **3** | ⚠️ **This document conflates two levels — level mixing again.** *§1 (dimensions, orthogonality, derived functions) **is a meta-model**; §2 (concepts) **is an ontology**. Both are named rather than the whole renamed* |
>
> ⭐ **And the chain in §2 is incomplete:** `MISSION → STRATEGY → PRINCIPLE → **DESIGN POLICY (DP-n)** → CAPABILITY → KNOWLEDGE → ARTIFACT`. ⭐⭐ ***`DP-n` is the anchor that stops capabilities floating — observable in the Capability Catalog's 1:1 CAP→DP binding, and named by neither the reviewer nor me.***

## 1. Dimensions — tested, not asserted

⭐ **Each candidate is tested against CLAUDE.md's three-part rule: *orthogonal · necessary · sufficient*. ⛔ One is rejected.**

| Dimension | Source | Orthogonal? | Necessary? | Verdict |
|---|---|---|---|---|
| **D-I · NATURE** — *what kind of thing it is* | ⭐ **RQ-002** | ✅ *a rule stays a rule in any encoding* | ✅ | ⭐ **ADMIT — exists** |
| **D-II · REPRESENTATION** — *how it is encoded* | ⭐ **RQ-002** | ✅ ⭐ **proved by RQ-002's first smoking gun:** *"the same rule landed in different types depending on its encoding"* | ✅ | ⭐ **ADMIT — exists** |
| **D-III · GOVERNANCE-STATUS** — *lifecycle position* | ⭐ **RQ-002 + `statuses.yaml` (8 values, executable)** | ✅ | ✅ | ⭐ **ADMIT — exists AND enforced** |
| **D-IV · AUTHORITY-SCOPE** — *how far it reaches* | ⭐ **RQ-002 + ES-005.1's three concerns + the placement scopes** | ✅ | ✅ | ⭐ **ADMIT — exists in two places** |
| ⭐ **D-V · EVIDENCE-STATUS** — *observed · inferred · hypothesized* | ⚠️ **my working vocabulary; ⛔ NOT a field anywhere** | ✅ *a hypothesis can be prose, approved and platform-scoped* | ⭐⭐ **YES — ten rediscoveries and four falsifications happened because claims were not graded** | ⭐ **ADMIT — NEW** |
| ⭐ **D-VI · OPERATIONAL-STATE** — *enforced · advisory · unexercised* | ⚠️ **implied by `deny`/`ask`/hooks; ⛔ never formalized** | ✅ *a rule can be `frozen` **and** advisory* | ⭐⭐ **YES — it is what "0 traversals" and my "0 of 11 enforcing" error both measured badly** | ⭐ **ADMIT — NEW** |
| ⛔ **INTENT-STATUS** — *proposed · sponsor-intended · ratified · operational* | proposed by the reviewer | ⛔⛔ **NO** | — | ⛔ **REJECT — see below** |

### ⛔⛔ Why INTENT-STATUS is rejected — the reviewer's own principle, applied consistently

| | |
|---|---|
| **The test** | ⭐ *orthogonal · necessary · sufficient* |
| ⛔ **It fails orthogonality** | **`statuses.yaml` already runs `draft → discovery → reviewed → approved → baseline → frozen → superseded → archived`.** ⭐ ***"Ratified" and "approved" are the same axis.*** *"Proposed" is `draft`. "Operational" is `baseline`* |
| ⭐ **What it actually decomposes into** | ⭐⭐ **`INTENT = (GOVERNANCE-STATUS × OWNER)`** — *and `owner` is already a required-ish field. **"Sponsor intent" is simply governance-status `draft/approved` with `owner: sponsor`*** |
| ⭐ **The consistency point** | ⚠️ *The reviewer rightly rejected `INTENDED` for overloading the evidence dimension. **`INTENT-STATUS` overloads the governance dimension in exactly the same way.** Applying the rule to my error and not to the proposal would be selective* |

> ### ⭐ **THE CANONICAL MODEL — six dimensions, four existing, two new, one rejected**
>
> ```
> KnowledgeOS instance =
>       NATURE  ×  REPRESENTATION  ×  GOVERNANCE-STATUS  ×  AUTHORITY-SCOPE
>                ×  EVIDENCE-STATUS  ×  OPERATIONAL-STATE
>
>   DERIVED, never axes (RQ-002's rule, extended by one):
>       lifecycle regime   = f(NATURE)                       [RQ-002]
>       validation method  = f(REPRESENTATION, NATURE)       [RQ-002]
>       intent             = f(GOVERNANCE-STATUS, OWNER)     [this document]
> ```
>
> ⭐ **Evidence, Intent, Governance and Operational State ARE separated as the commission required — but Intent is separated by DERIVATION, not by adding an axis.**

## 2. The six concepts

⭐ **Defined by responsibility · lifecycle · ownership · dependencies. ⛔ No concept is defined by a folder.**

| Concept | Responsibility | Lifecycle | Owner | Depends on |
|---|---|---|---|---|
| ⭐ **MISSION** | state **why the platform exists** | ⭐ **enacted → candidate → ratified** *(currently: **enacted, unratified**)* | ⭐ **the sponsor** | ⛔ nothing — it is the root |
| **ENGINEERING KNOWLEDGE** | hold rules, methods and decisions **that transfer between products** | **ES-006.1**: research → pilot → qualification → standard | **DA** *(policy)* · **sponsor + ARB** *(method)* | MISSION |
| **ENGINEERING CAPABILITY** | ⭐ **protect exactly ONE invariant** | observed → candidate → designed → realized → **evidenced** → promoted | platform; shape **FROZEN** under PGP-01..05 | MISSION · ENGINEERING KNOWLEDGE |
| ⭐⭐ **ENGINEERING ARTIFACT** | ⭐ **CARRY knowledge in a representation** — ⛔ **it is never the knowledge** | `statuses.yaml`, 8 states, **lint-enforced** | its `owner` field | ENGINEERING KNOWLEDGE |
| **PRODUCT KNOWLEDGE SPACE** | hold **one product's** concepts, language, contexts and **bindings** | ⚠️ authored *(today)* · generated *(hypothesis, n=0)* | the product team | ENGINEERING KNOWLEDGE *(method)* · PRODUCT *(subject)* |
| **PRODUCT** | deliver business value | its own delivery lifecycle | the product team | PKS *(guides)* · CAPABILITY *(verifies)* |

### ⭐⭐⭐ The model's most important edge — and why it matters

> ## **`ENGINEERING KNOWLEDGE` ≠ `ENGINEERING ARTIFACT`. One is the thing; the other is a carrier of it.**
>
> ⭐⭐ **This single distinction makes my recurring error UNSTATABLE.**
>
> | My error, three times | Under this model |
> |---|---|
> | *"there is no strategic-DDD method"* | ⛔ **not expressible** — *you can only say **no ARTIFACT carries it**, which is a REPRESENTATION claim, not a NATURE claim* |
> | *"there is no capability pattern"* | ⛔ same |
> | *"the mission layer is vacant"* | ⛔ same |
>
> ### ⭐ **A meta-model earns its place by making a known error class impossible to phrase. This one does.**

**And the reviewer's §8 hierarchy, adopted:**

```
   MISSION  ▸  ENGINEERING CAPABILITY  ▸  ENGINEERING KNOWLEDGE
                        ▸  ENGINEERING ARTIFACT   ← the LOWEST reusable layer
                        ▸  PKS  ▸  PRODUCT
```

⛔ **Documents are no longer the centre of the model. They are the bottom of it.**

## 3. The four concerns, separated as required

| Concern | Dimension carrying it | Existing home |
|---|---|---|
| ⭐ **EVIDENCE** | **D-V EVIDENCE-STATUS** | ⛔ **none — new** |
| ⭐ **GOVERNANCE** | **D-III GOVERNANCE-STATUS** | ⭐ `statuses.yaml` — **executable** |
| ⭐ **OPERATIONAL STATE** | **D-VI OPERATIONAL-STATE** | ⛔ **none — new** |
| ⭐ **INTENT** | ⭐⭐ **DERIVED: `f(GOVERNANCE-STATUS, OWNER)`** | ⭐ both fields already exist |

⭐ **A fifth concern the repository already separates, worth naming so it is not re-added later:** **TRUST/PROVENANCE** = `authorities.yaml` *(authoritative · derived · generated · historical · provisional)* — ⭐ **and `statuses.yaml` already declares it orthogonal: *"status … is INDEPENDENT of authority."*** *The repository was doing orthogonal dimensions before this commission asked for them.*

## 4. ⭐ Success-criterion test — six real artifacts classified

⛔ **If any row required changing the model, the model would be wrong.**

| Artifact | NATURE | REPRESENTATION | GOV-STATUS | SCOPE | EVIDENCE | OPERATIONAL |
|---|---|---|---|---|---|---|
| **CAP-001 README** | capability spec | prose + code | realized | platform | observed | ⭐ **advisory** |
| **`Round39-MC`** | constitution | prose | ⭐ **frozen** *(ADOPTED)* | platform *(method)* | observed | ⭐ **enforced** *(void-overriding)* |
| **A verification report** | finding | prose record | approved | ⛔ **product** | observed | ⛔ **unexercised** |
| **`PKS_..._M4`** | model | prose | ⭐ *accepted-with-refinements* | product | inferred | unexercised |
| ⭐ **`settings.json` `deny`** | constraint | ⭐ **executable config** | approved | ⭐ **runtime** | observed | ⭐⭐ **ENFORCED** |
| ⭐ **This document** | model | prose | ⭐ **draft/candidate** | platform | **inferred** | unexercised |

> ### ⭐ **All six classify. ⭐⭐ And the test produced a finding on its own: `Round39-MC` is `frozen` yet `ENFORCED`, while `CAP-001` is `realized` yet only `advisory`.**
> ### ⛔ **GOVERNANCE-STATUS and OPERATIONAL-STATE vary independently — which is the orthogonality proof, obtained by using the model rather than by arguing for it.**

## 5. What this model does not do

| ⛔ |
|---|
| **It creates no principle, no document class, no folder** |
| **It does not adopt RQ-002** — ⚠️ *that is the ARB's act; this model **depends** on it and says so* |
| **It does not resolve MQ-1** *(mission ratification)* — it only supplies the vocabulary for stating it |
| **It adds nothing to `knowledge-types.yaml`** — ⛔ *L-A models **artifacts**; this models **all six concepts**, and D-1..D-7's key-collision proof still stands* |
| ⭐ **It admits only TWO new dimensions**, each passing all three tests, and **rejects one proposal** with the same rule |

## 6. Open questions

| # | Question | Authority |
|---|---|---|
| ⭐ **MM-1** | **Adopt RQ-002?** *This model rests on it, and it is COMPLETE but unadopted* | **ARB** |
| ⭐ **MM-2** | **Should EVIDENCE-STATUS and OPERATIONAL-STATE become frontmatter fields?** ⚠️ *That would make them enforceable — and would extend L-A's declared scope* | **ARB + PD-3's owner** |
| **MM-3** | Is `intent = f(governance-status, owner)` sufficient, or is a sponsor-intent value needed in `statuses.yaml`? | ARB |
| **MM-4** | Does OPERATIONAL-STATE belong to the artifact or to the **rule** the artifact carries? ⚠️ *`Round39-MC` is enforced because of what it **says**, not how it is stored* | ARB |
| **MQ-1** *(carried)* | **Ratify the enacted mission** | **sponsor** |

---

## ⭐ Closing

| | |
|---|---|
| ⛔ **Conceded** | **`INTENDED` withdrawn** — intent is not evidence, and the repository's own dimension rule condemned it |
| ⭐ **Adopted** | ***"Mission is ENACTED"*** — the platform behaves according to a mission it has never written |
| ⭐⭐ **Derived, not invented** | **four dimensions come from RQ-002 and the executable schema.** *Only two are new, each passing orthogonal · necessary · sufficient* |
| ⛔ **Rejected, on the same rule** | **INTENT-STATUS** — *it decomposes into `(governance-status × owner)`; adding it would overload governance exactly as `INTENDED` overloaded evidence* |
| ⭐⭐⭐ **The model's real value** | ⭐ **`Knowledge ≠ Artifact` makes my three-times-repeated error UNSTATABLE** — *you can no longer say "X does not exist" when you mean "no artifact carries X"* |
| ⭐ **Validated by use** | **six artifacts classified without altering the model**, and the test itself proved two dimensions independent |

> ### **The meta-model's job is not to describe KnowledgeOS. It is to make certain mistakes impossible to say.**
> ### ⭐ **By that measure it already works: the error I made three times cannot be phrased in it.**

---

*Traceability: meta-model commission 2026-08-02 · ⭐ **DERIVED from `RQ-002_Knowledge_Meta_Model` (COMPLETE, unadopted), the `Engineering_Platform_Knowledge_Metamodel` (CANDIDATE, gate OQ-ENG-004) and the executable L-A schema — not invented** · **six dimensions: 4 existing + 2 new (EVIDENCE-STATUS, OPERATIONAL-STATE), each passing CLAUDE.md's orthogonal/necessary/sufficient rule** · ⛔ **INTENT-STATUS REJECTED — it decomposes into `f(governance-status, owner)`, overloading governance exactly as `INTENDED` overloaded evidence; the reviewer's rule applied to their own proposal as well as to mine** · ⛔ **`INTENDED` WITHDRAWN** · ⭐ **six concepts defined by responsibility/lifecycle/ownership/dependencies, with `ENGINEERING KNOWLEDGE ≠ ENGINEERING ARTIFACT` as the load-bearing edge that makes the artifact-absence error unstatable** · ⭐ **success criterion TESTED on six real artifacts; all classified, and the test independently proved GOVERNANCE-STATUS ⟂ OPERATIONAL-STATE** · ⛔ **no principle · no document class · no folder · no implementation · no ADR.***

> **⛔ Submitted to the Decision Authority. Nothing in this document executes.**
