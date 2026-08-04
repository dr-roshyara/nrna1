# KnowledgeOS — Ontology Architecture Classification

| | |
|---|---|
| **Kind** | ⭐ **CLASSIFICATION OF WHAT EXISTS.** ⛔ ***No YAML modified · no graph generation changed · no document type added · no implementation · no repository redesign.*** |
| **Status** | ⚠️ **CANDIDATE — NOT ADOPTED.** Submitted to the Decision Authority |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Commission** | Principal Knowledge Engineer / Strategic DDD Architect / Enterprise Information Architect, 2026-08-02 |
| **Placement** | ⭐ **DERIVED** → `docs/knowledgeos` |
| **Method** | ⭐ **Every schema file was parsed, not skimmed.** *Complete vocabularies extracted via the repository's own YAML loader* |

> ## ⛔ **THE CRITIQUE CORRECTS MY OWN RECOMMENDATION, AND IT IS RIGHT**
>
> **I recommended *"express D-1..D-7 in `knowledge-types.yaml`."* That mixes abstraction levels: `Engineering Governance` is not a document.**
>
> ### ⭐ **And the repository supplies MECHANICAL proof, not merely aesthetic objection — see §3.**

---

## 1. Ontology scope — per schema file, as parsed

| File | Semantic objects modelled | Abstraction level | ⛔ Deliberately outside scope |
|---|---|---|---|
| ⭐⭐ **`knowledge-schema.yaml`** | ⭐ **THE METAMODEL** — 16 frontmatter fields · **18 validation rules** *(incl. `single_authoritative`, `boundary_consistency`, `traceability_complete`, `orphan_document`, `circular_dependency`, `frozen_changed_without_adr`)* | **meta** — it defines what a knowledge card *is* | ⭐⭐ **DECLARED: `scope.include = docs/knowledge/**/*.md` only**, excluding `archive/**` |
| **`knowledge-types.yaml`** | ⭐ **31 DOCUMENT types** | **document** | anything that is not a document |
| **`knowledge-relationships.yaml`** | **11 typed directed edges + 4 shortcuts** — `implements · requires · depends_on · derived_from · supersedes · superseded_by · related_to · documents · verified_by · tested_by · reviewed_by` | **document→document** | ⭐ **no edge expresses `governs`, `produces`, `consumes` or `observes`** |
| **`statuses.yaml`** | **8 lifecycle states** — draft → discovery → reviewed → approved → baseline → frozen → superseded → archived | document lifecycle | authority *(explicitly separated)* |
| **`authorities.yaml`** | **5 trust levels** — authoritative *(rank 1, `single_per_topic`)* · **derived** · generated · historical · provisional | document trust | lifecycle *(explicitly separated)* |
| **`knowledge-audiences.yaml`** | **9 audiences** — newcomer · developer · architect · reviewer · product-owner · committee · ⭐ **`ai`** · operations · security | reader | — |
| ⭐ **`bounded-contexts.yaml`** | ⭐⭐ **12 PRODUCT contexts mirroring `app/Contexts/`** — global · membership · **governance** · adjudication · contestation · elections · election · geography · committee · finance · trust · shared | **product domain** | ⛔ **any platform-side domain** |

### ⭐ Two findings that change the diagnosis

> **1 · THE 13% COVERAGE IS A DECLARED BOUNDARY, NOT AN OVERSIGHT.**
> `scope.include` is **`docs/knowledge/**/*.md`**. ⛔ *Extending the graph would amend a **declared scope**, not fix a bug. My earlier framing ("a coverage problem") understated this: it is a **scope decision** that someone made.*

> **2 · `knowledge-types.yaml` IS a documentation ontology — I checked the temptation to call it a hybrid, and it is not one.**
> Types like `aggregate`, `event`, `policy` *look* like domain objects, but their own descriptions are documentary: **`aggregate` = *"A single aggregate's **design**, invariants, and boundary"*** · **`event` = *"A domain event — payload, producers, consumers"***. ⭐ **These are documents ABOUT domain objects.** *The reviewer's classification holds.*

## 2. Ontology layers — ⭐ **THREE, not four**

⛔ **The commission says "do not invent new layers unless repository evidence requires them." One proposed layer is therefore declined.**

| Layer | Status | Evidence |
|---|---|---|
| ⭐⭐ **L-A · Documentation Ontology** | ✅ **EXISTS · EXECUTABLE · ENFORCED** | 31 types × 8 statuses × 5 authorities × 9 audiences × 12 contexts × 11 edges, validated by **18 lint rules**, populated into a generated graph |
| ⛔ **L-B · Platform Ontology** | ⛔ **DOES NOT EXIST** | D-1..D-7 is its **seed**. Three independent proofs of distinctness in §3 |
| ⚠️ **L-C · Product Ontology** | ⚠️ **PARTIALLY EXISTS** | `bounded-contexts.yaml` **is** a product-context vocabulary; `Round29` **Aggregate · Bounded-Context · Invariant · Decision-Ownership catalogs** are its unindexed remainder |
| ⛔ **"Engineering Knowledge Ontology"** *(the reviewer's middle layer)* | ⛔ **NOT EVIDENCED AS A SEPARATE LAYER** | ⭐ `knowledge-types.yaml` **already covers engineering-knowledge kinds** — `adr · ddd-discovery · research · review · playbook · checklist · template · decision · quality`. **L-A *is* the engineering-knowledge ontology, at document granularity.** *Inserting a fourth layer would name a distinction the evidence does not force* |

> ### ⭐ **Corrected map: `L-B Platform` → `L-A Documentation` → Graph → Projections`, with `L-C Product` beside L-A — not a four-tier stack.**

## 3. Placement of D-1…D-7 — five questions each

| Domain | A document type? | A knowledge domain? | A bounded context? | Platform architecture? | ⛔ Belongs in `knowledge-types.yaml`? |
|---|---|---|---|---|---|
| **D-1 Engineering Governance** | ⛔ **no** | ✅ yes | ✅ **yes** (platform-side) | ✅ yes | ⛔ **NO** |
| **D-2 Engineering Method** | ⛔ no | ✅ yes | ✅ **yes** (strongest) | ✅ yes | ⛔ **NO** |
| **D-3 Engineering Runtime** | ⛔ no | ✅ yes | ✅ **yes** (generic) | ✅ yes | ⛔ **NO** |
| **D-5 Engineering Capability** | ⛔ no | ✅ yes | ⚠️ **no — a knowledge kind** (H-CAT-1 stands) | ✅ yes | ⛔ **NO** |
| **D-6a Evidence Protocol** | ⛔ no | ✅ yes | ✅ **yes** (never executed) | ✅ yes | ⛔ **NO** |
| **D-6b Product Evidence** | ⛔ no | ✅ yes | ⛔ no — product-side records | ⛔ no | ⛔ **NO** |
| **D-7 PKS** | ⛔ no | ⚠️ **a context TYPE** | ⛔ no — one context per instance | ✅ yes *(the type)* | ⛔ **NO** |

### ⭐⭐ Three independent proofs that D-1..D-7 do not belong in the existing schemas

| # | Proof | Force |
|---|---|---|
| **1** | ⭐⭐ **KEY COLLISION — mechanical, not aesthetic.** `bounded-contexts.yaml` **already contains `governance`** = *"Authority models, approval workflows, committee hierarchy"*, `code_path: app/Contexts/Governance`. ⛔ **Adding D-1 "Engineering Governance" would collide with an existing key in an enum that `knowledge-lint` enforces** | ⛔ **DECISIVE** |
| **2** | ⭐ **VOCABULARY INSUFFICIENCY.** The Domain Model's verbs are *governs · produces · consumes · validates · derives · depends on · observes*. The edge vocabulary supplies `depends_on`, `derived_from`, `verified_by` — ⛔ **and has no way to express `governs`, `produces`, `consumes` or `observes`** | ⭐ **STRONG** |
| **3** | ⭐ **SUBJECT MISMATCH.** Every L-A field is a property **of a document** (`knowledge_id`, `last_review`, `code_refs`). ⛔ *`Engineering Method` has no reviewer, no next-review date and no code refs — it is not a document and cannot carry a knowledge card* | ⭐ **STRONG** |

> ### ⛔ **VERDICT: D-1..D-7 belong to L-B, which does not yet exist. ⛔ They must not be added to `knowledge-types.yaml` or `bounded-contexts.yaml`.**
> ⚠️ *If they were ever expressed as data, it would require a **separate** vocabulary file with its own enum namespace — and that is an ARB decision, not a classification outcome. **Nothing is proposed here.***

## 4. PKS as a projection — validated

> ### ⭐ **VERDICT: SUPPORTED IN PRINCIPLE by three pieces of EXISTING vocabulary. ⛔ BLOCKED IN PRACTICE by a declared scope.**
>
> ⛔⛔ **SHARPENED 2026-08-02 by falsification test W-3: it is STRONGER THAN “blocked”.** **Existing PKS artifacts are POSITIVELY AUTHORITATIVE** — they carry `Status: ACCEPTED WITH REFINEMENTS`, a `Commission`, and a `Disposition History` recording an **ARB endorsement of 9.9/10**. ⭐ *That is the lifecycle of an authoritative artifact and is incompatible with `derived`.* **PKS-as-projection is therefore FALSIFIED for every artifact that exists today**, and survives only as a claim about artifacts that do not. Record: `KnowledgeOS_Domain_Model_Falsification_Report.md` §1.

**✅ Supporting evidence — all pre-existing, none introduced here:**

| # | Evidence |
|---|---|
| **1** | ⭐⭐ **`authorities.yaml` already carries `derived`** — *"Derived from an authoritative source (**e.g. a generated index, a summary**)"*. **The projection concept is already a first-class authority level** |
| **2** | ⭐ **`knowledge-relationships.yaml` already carries `derived_from`** — a typed, directed edge for exactly this relation |
| **3** | ⭐⭐ **The repository already projects.** `knowledge-graph.php` generates `portal/graph/knowledge-graph.md`; the portal carries generated `adr-index`, `by-role`, `by-type`. **Projection is not a hypothesis — it is operating** |
| **4** | ⭐⭐ **CAP-002's DP-2 and the authority ranks say the same thing twice.** DP-2: *"every projection regenerates from its sources and is **cited as authority by nothing**."* `authorities.yaml`: `authoritative` = rank 1, `single_per_topic: true`; `derived` = rank 2. **"Cited as authority by nothing" is precisely what the authority rank encodes** |

**⛔ Refuting / limiting evidence:**

| # | Limit |
|---|---|
| **1** | ⛔ **n=0.** No PKS has been projected. PublicDigit's PKS was hand-built — it would carry `authority: authoritative`, ⛔ **not `derived`** |
| **2** | ⛔⛔ **THERE IS NOTHING TO PROJECT FROM.** The graph's declared scope is `docs/knowledge/**` — which **excludes `docs/pks/` and all 92 `PKS_*` files in `docs/implementation/`.** *A projection needs a source; the source is outside the graph's declared boundary* |

> ### ⭐ **Does CAP-002 naturally govern PKS regeneration? YES — and this has a consequence for CAP-002's status.**
>
> **CAP-002 was DEFERRED on AD-1's ground that *"its correctness is verified differently."* ⚠️ That deferral was decided before PKS-as-projection was on the table.**
>
> ### ⭐⭐ **If a PKS is a projection, CAP-002 is no longer a documentation-hygiene capability — it becomes the capability that answers *"can every PKS be regenerated from the graph?"* ⛔ Whether that reopens the deferral is the ARB's call, not mine.**

## 5. The ontology map

```
  ┌──────────────────────────────────────────────────────────────────┐
  │  L-B  PLATFORM ONTOLOGY                        ⛔ DOES NOT EXIST │
  │  Engineering Method · Governance · Runtime · Evidence Protocol    │
  │  Capability (kind) · PKS Type · Projection · Knowledge Domain     │
  │  seed = D-1..D-7 ·  verbs: governs · produces · consumes ·        │
  │                            observes ·  ⛔ none in L-A             │
  └───────────────────────────────┬──────────────────────────────────┘
                                  │ would own / classify
                                  ▼
  ┌──────────────────────────────────────────────────────────────────┐
  │  L-A  DOCUMENTATION ONTOLOGY          ✅ EXISTS · EXECUTABLE      │
  │  31 types × 8 statuses × 5 authorities × 9 audiences × 11 edges   │
  │  metamodel: 16 fields · 18 lint rules                            │
  │  ⛔ DECLARED SCOPE: docs/knowledge/**/*.md   (~132 of ~1,000+)    │
  └───────────────────────────────┬──────────────────────────────────┘
        ┌───────────────────────── │ ─────────────────────────┐
        │ beside, not beneath      │ populates               │
        ▼                          ▼                          │
  ┌──────────────────────┐  ┌─────────────────────────┐       │
  │ L-C PRODUCT ONTOLOGY │  │  KNOWLEDGE GRAPH        │       │
  │ ⚠️ PARTIAL           │  │  ✅ GENERATED           │       │
  │ bounded-contexts.yaml│  │  portal/graph/*.md      │       │
  │ + Round29 catalogs   │  └───────────┬─────────────┘       │
  │   (unindexed)        │              │ projects            │
  └──────────────────────┘              ▼                     │
                     ┌────────────────────────────────────────┴───┐
                     │  PROJECTIONS   authority: derived           │
                     │  ✅ portal indexes · graph                  │
                     │  ⛔ PKS  (n=0 — source outside scope)       │
                     │  ⛔ prompt context (audience `ai` exists)   │
                     └────────────────────────────────────────────┘
```

## ⭐ The five commissioned deliverables

| # | | |
|---|---|---|
| **1** | **Ontology layer classification** | ⭐ **THREE layers: L-A Documentation (exists) · L-B Platform (absent) · L-C Product (partial).** ⛔ *A fourth "Engineering Knowledge Ontology" layer is **declined** — L-A already is it* |
| **2** | **Evidence per layer** | §1 per-file parse · §2 table · §3 three proofs. **L-A: 18 enforced rules + a generated graph. L-B: zero artifacts. L-C: one enum + four unindexed catalogs** |
| **3** | **Placement of D-1..D-7** | ⛔ **ALL SEVEN belong to L-B. NONE belongs in `knowledge-types.yaml`.** ⭐ *Decisive proof: `bounded-contexts.yaml` already has a `governance` key — a **product** context — so D-1 would collide in a lint-enforced enum* |
| **4** | **L-B ↔ L-A relationship** | ⭐ **L-B would OWN and CLASSIFY L-A; L-A would never contain L-B.** *L-A's subject is the document; L-B's subject is the platform. **One is the metadata of artifacts, the other the architecture of the thing that produces them*** |
| **5** | ⭐ **Should the executable ontology change?** | ⛔ **NO — not to accommodate L-B.** ⚠️ **Two changes are separately warranted and both are ARB matters:** *(a)* whether `scope.include` should extend beyond `docs/knowledge/**` — ⭐ **a declared-scope amendment, not a bug fix**; *(b)* whether the edge vocabulary needs `governs`/`produces`/`consumes`/`observes`, which is only answerable **after** L-B exists |

---

## ⭐ Closing

**The reviewer's central claim is confirmed by parsing rather than by argument: there are two ontologies, one of which does not exist.**

| | |
|---|---|
| ⭐ **What exists** | a complete, executable, lint-enforced **documentation** ontology — 31 types, 18 rules, a generated graph, a portal |
| ⛔ **What does not** | a **platform** ontology. *D-1..D-7 is its seed and has no home* |
| ⭐ **Why they must stay apart** | ⛔ **a key collision (`governance`), a verb gap (`governs`/`produces`/`consumes`/`observes`), and a subject mismatch (`Engineering Method` has no `next_review`)** |
| ⭐ **What I got wrong** | *"express D-1..D-7 in `knowledge-types.yaml`"* — **withdrawn.** It would have put platform architecture into a document-type enum |
| ⭐ **What the parse added** | ⭐⭐ **the 13% coverage is a DECLARED SCOPE, not an oversight** — which makes extending it a governance act, not maintenance |

> ### **The ontology is not missing and not wrong. It is complete for its declared subject — documents — and silent about the platform that governs it.**

---

*Traceability: Ontology Architecture Classification commission 2026-08-02 · ⭐ **all seven schema files parsed with the repository's own YAML loader; complete vocabularies extracted** · **THREE layers classified; a fourth DECLINED for lack of evidence** · **D-1..D-7 placement refused on three independent grounds, one of them a mechanical key collision in a lint-enforced enum** · **PKS-as-projection SUPPORTED by `authorities.derived`, `derived_from`, and operating projections; BLOCKED by the graph's declared scope; CAP-002 confirmed as its natural governor with the deferral question referred to the ARB** · ⭐ **my own recommendation to place D-1..D-7 in `knowledge-types.yaml` is WITHDRAWN** · ⛔ **no YAML modified · no graph changed · no document type added · nothing implemented · nothing redesigned.***

> **⛔ Submitted to the Decision Authority. Nothing in this document executes.**
