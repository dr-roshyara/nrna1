# KnowledgeOS — Engineering Knowledge Domain Model

| | |
|---|---|
| **Kind** | ⭐ **STRATEGIC KNOWLEDGE ARCHITECTURE.** ⛔ ***Not an inventory · not a documentation cleanup · not a folder reorganization. No ADR · no file moved · no implementation.*** |
| **Status** | ⚠️ **CANDIDATE — NOT ADOPTED.** Submitted to the Decision Authority |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Commission** | Principal Knowledge Engineer / Strategic DDD Architect / Enterprise Information Architect + ARB Chair's Addendum, 2026-08-02 |
| **Input accepted as** | ⭐ **the Knowledge Landscape is OBSERVATIONAL EVIDENCE ONLY.** ⛔ *It is not extended here* |
| **Method** | ⭐ **Meaning first.** ⛔ *No domain is derived from a folder, a filename, or a markdown type. Every candidate is validated against **`Round47-OP`'s nine admissible justifications** and graded with the **R16 Workbook's Strong/Medium/Weak** signal — the repository's own instruments* |

> # ⛔⛔⛔ **A DOMAIN IS MISSING FROM THIS MODEL — found 2026-08-02**
>
> ⭐⭐ **PD-3 · the ENGINEERING KNOWLEDGE PLATFORM.** *`Knowledge-Constitution.md` carries `status: frozen`, `authority: authoritative`, and ⭐ **`owner: nab.raj.sharma` — a NAMED INDIVIDUAL**, not the DA, not the ARB, not the sponsor. Seven schemas, 18 enforced lint rules, a generated graph, a portal.*
>
> ⛔ **This model treated the EKP as an ONTOLOGY LAYER (L-A) and never asked WHO OWNED IT. That is a category error** — a governed domain with its own constitution was classified as a documentation substrate. **It scores 6 of 9 criteria, tying D-1 as the most strongly evidenced domain in the repository.**
>
> Record: `KnowledgeOS_Strategic_Architecture_Discovery.md` §3.

> # ⛔⛔ **FALSIFICATION TESTED 2026-08-02 — THIS MODEL DID NOT SURVIVE INTACT**
>
> **Report: `KnowledgeOS_Domain_Model_Falsification_Report.md`. ⛔ VERDICT: REVISE, then re-test. NOT canonical.**
>
> | Domain | Confidence after test | What happened |
> |---|---|---|
> | **D-1 Governance** | ⭐ **STRONG** | survived all four falsifiers |
> | **D-2 Method** | ⚠️ **Strong → MEDIUM** | ⛔ *“own ADR series” is a **shared authority** — `Round39-D6`: **“sponsor + ARB”***; ⛔ *the series was **authored in one day, retrospectively*** — so independent **evolution** is falsified. ⭐ **Survives on a BETTER ground: `Round39-D6`'s explicit scope exclusion — *“Governance discoveries MUST NOT appear here”*** |
> | **D-3 Runtime** | ⛔ **Strong → WEAK** | ⛔⛔ **no Capability Mapping artifact exists**; cited by **one** source record + my own documents; `settings.json` uses `deny/ask/allow` — ⭐ **the mapping is nearly the IDENTITY function, and an ACL that performs an identity mapping is not an ACL** |
> | **D-4 Workflow (rejected)** | ⭐ **STRONG** | ⭐ *revival attempted and failed — the EEP declares `Owner: Decision Authority`* |
> | **D-6a Protocol** | ⚠️ **MEDIUM** | motivated, **0 traversals** — *unevidenced, not falsified* |
> | **D-7 PKS type** | ⛔ **WEAK** | inferred from **n=1**; ⛔ **PKS-as-projection FALSIFIED for existing artifacts** — they carry `Status: ACCEPTED WITH REFINEMENTS` and an ARB endorsement of **9.9/10**. *You do not endorse a projection at 9.9/10; you regenerate it* |
>
> ⭐ **Required revisions: D-2 restated on scope exclusion only · D-7 restated as “an observation of one instance”.**

> ## ⛔ **THE CRITIQUE IS ACCEPTED, AND IT NAMES A REAL ERROR IN MY WORK**
>
> **The Landscape classified by *artifact kind* — workbook, constitution, playbook, report, audit. That is filename thinking wearing a taxonomy.**
>
> ### ⭐ *Classification follows the domain model. The Landscape found the symptom; it could not supply the ontology.*
>
> ⚠️ **And its own recommendation would have frozen the accidental structure:** an index keyed to today's arrangement inherits today's arrangement. **The model must come first.**

---

## 1. Candidate knowledge domains — validated, not transcribed

⭐ **Four of eight candidates survive as offered. Four do not.** *The rejections and splits are the substance.*

### ✅ D-1 · Engineering Governance — **STRONG**

| Criterion | Evidence |
|---|---|
| **Semantic ownership** | ⭐ Decision Authority / ARB — sole authority to adopt, ratify, freeze |
| **Transactional consistency** | ES-001..006 change as a set under `STANDARDS_INDEX`; ES-001.1 rule parsimony binds them |
| **Lifecycle independence** | ratification, freeze (R-27/R-37/R-38) proceed independently of any product release |
| **Invariants** | rulings are **append-only**; **R-46** — plan approval ≠ execution authorization |
| **UL divergence** | *Decision Authority · ruling · commission · ratification · disposition · authority tier* |

> ### ⭐ **VERDICT: a bounded context.** Five criteria, independently satisfied.

### ✅ D-2 · Engineering Method — **STRONG, and it is NOT the same context as D-1**

> ## ⭐⭐ **THIS IS THE MODEL'S PRINCIPAL DISCOVERY.**

| Criterion | Evidence |
|---|---|
| ⭐⭐ **Its own constitution** | **`Round39-MC` Methodology Constitution (MC-01..08), ADOPTED** — *"sits **above** the Specification, ADRs, Validator, and Baseline"* |
| ⭐⭐ **Its own decision series** | **ADR-M** *(`Round39-D6`)* — **distinct from ADR-AIP**, which serves D-1 |
| ⭐⭐ **Its own validator and baseline** | Integrity Validator *(`Round39-02`)* → **Baseline MB-39.1** |
| **Transactional consistency** | *"an ADR, Spec clause, validator rule, or baseline that conflicts with any constitutional principle is **void to the extent of the conflict**"* — a self-contained consistency rule |
| **Lifecycle independence** | its own stabilization charter and amendment path |
| **UL divergence** | *family execution · F-AUTH · F-PROC · F-THR · F-REV · prediction register · methodology baseline · hostile replication* — ⛔ **none of these terms appears in D-1's language** |

> ### ⭐⭐ **VERDICT: a bounded context, and the most strongly evidenced boundary in the model.**
> **Two constitutions · two ADR series · two baselines · two vocabularies.** ⛔ *Treating governance and method as one domain would merge two independently governed models — the classic conflation Strategic DDD exists to prevent.*

### ✅ D-3 · Engineering Runtime — **STRONG**

| Criterion | Evidence |
|---|---|
| ⭐ **UL divergence — deliberately engineered** | the Capability Mapping exists *"so that Claude-specific vocabulary — `ask`, `deny`, `permissions` — **cannot LEAK UPWARD**"*. **A boundary built to hold a language apart is a bounded context by construction** |
| **Lifecycle + deployment autonomy** | *"another runtime could replace Claude Code and **only the adapter would be rewritten**"* |
| **Integration characteristics** | an **ACL** in function — the clearest in the model |
| **Invariants** | the mount *"never moves and is never 'the architecture'"* |

> ### ⭐ **VERDICT: a bounded context — a Generic Subdomain, replaceable by design.** ⚠️ *n=1 runtime: the ACL has never been exercised against a second.*

### ⛔ D-4 · Engineering Workflow — **REJECTED**

| Test | Result |
|---|---|
| Ownership | ⛔ **none of its own** — the EEP is hosted by **ES-002**, inside D-1 |
| UL divergence | ⛔ **none** — *stage · slice · gate · plan* are D-1's words |
| Transactional consistency | ⛔ changes with ES-002, not independently |

> ### ⛔ **VERDICT: NOT a domain. A set of responsibilities inside D-1 (Engineering Governance) and D-2 (Engineering Method).**
> ⚠️ *The Round14 workbooks are D-2 **instruments**; EP-01/02/03 are D-1 **procedures**. Naming their union "Workflow" creates a third label for two existing owners.*

### ⚠️ D-5 · Engineering Capability — **MEDIUM — a knowledge kind, not yet a context**

| For | Against |
|---|---|
| ⭐ `Platform_Capability_Pattern` is **FROZEN** and *"capability-agnostic"*, governed by **PGP-01..05** | ⛔⛔ **`H-CAT-1` explicitly REFUSED the parent abstraction — *"not yet evidenced"* — and the refusal has never been overturned** |
| CAP-001 realized; per-capability invariants DP-1..DP-6 | ⚠️ the pattern is **PROVISIONALLY STABLE at n=2 *conceptual* instances**, *"not yet proven by multiple production capabilities"* |

> ### ⚠️ **VERDICT: a KNOWLEDGE KIND with a governing pattern — NOT a bounded context.**
> ⭐ *Promoting it would overturn H-CAT-1 by preference rather than by evidence, which is the exact move the nine criteria forbid.* ⛔ **Only the ARB may overturn it.**

### ⛔⭐ D-6 · Engineering Evidence — **SPLITS. It is not one domain.**

> ## ⭐⭐ **The boundary runs THROUGH the concept, not around it.**

| Half | Owner | Nature |
|---|---|---|
| ⭐ **Evidence Protocol** — ES-006.4 harvest question · ES-006.1 ladder · Observation Protocol *(with a clock)* · saturation gate | **KnowledgeOS** | ⭐ **reusable — a hospital project inherits it entirely** |
| ⛔ **Evidence Records** — 106 reports · session logs · CAP-001 §9 rows · H1–H23 · D1–D38 | ⛔ **the producing product** | ⛔ **never reusable** |

**The split is not my inference — canon states it:** *owner = **"the producing track; consumed by DA"***. ⚠️ **A single owner cannot be "the producing track" *and* the platform. Split ownership is a boundary, not an ambiguity.**

> ### ⭐ **VERDICT: TWO domains — `D-6a Evidence Protocol` (platform, a bounded context) and `D-6b Product Evidence` (product-side records, not a platform context at all).**
> ⛔ **D-6a has 0 traversals: it is a bounded context that has never executed.**

### ⭐ D-7 · Product Knowledge Space — **RECLASSIFIED: a CONTEXT TYPE, not a context**

| | |
|---|---|
| ⛔ **Why not a context** | it has **one instance per product**. *"Product Knowledge" is not a boundary in KnowledgeOS — it is the **shape** of a boundary that each product instantiates* |
| ⭐ **What it is** | a **context type**: `PublicDigit PKS`, `Hospital PKS`, `ERP PKS` are each bounded contexts; the *type* is platform knowledge, the *instances* never are |
| **Evidence** | *"one per product… never reusable — an instance"*; three registered domain roots |

> ### ⭐ **VERDICT: a context TYPE owned by the platform; each instance is a product-side bounded context.** ⚠️ *Conflating the type with an instance is what let PublicDigit's methodology end up inside its own PKS paths.*

## 2. Domain → owner, with P1 applied

| Domain | Belongs to | METHOD | BINDING | EVIDENCE | Why |
|---|---|---|---|---|---|
| **D-1 Engineering Governance** | ⭐ **KnowledgeOS** | ✅ ES-001..006, EEP | ⚠️ **ES-005.1 names PublicDigit** | ⛔ rulings are this programme's | authority framework survives PublicDigit's disappearance |
| **D-2 Engineering Method** | ⭐ **KnowledgeOS** | ✅ MC-01..08 · nine criteria · workbook procedure | ⛔⛔ **SD-1** | ⛔ Rounds 14–31 findings | the method is general; its intake contract is not |
| **D-3 Engineering Runtime** | ⭐ **KnowledgeOS** *(generic)* | ✅ the four layers | ✅ none — Capability Mapping is tool-neutral | ⛔ `.claude/settings.json` | adapters are replaceable |
| **D-5 Engineering Capability** *(kind)* | ⭐ **KnowledgeOS** | ✅ Capability Pattern · CAP-001 shape | ⛔ `governed-registers.yaml` | ⛔ CAP-001 §9 rows | pattern reusable; config never |
| **D-6a Evidence Protocol** | ⭐ **KnowledgeOS** | ✅ ES-006.1/.4 · Observation Protocol | ✅ none | — | the protocol is inheritable |
| **D-6b Product Evidence** | ⛔ **Product** | — | — | ⛔ all of it | records are the producing track's |
| **D-7 PKS** *(type)* | ⭐ **type: KnowledgeOS** · ⛔ **instances: Product** | ✅ the shape | ⛔ each product's own | ⛔ each product's own | one instance per product |
| ⛔ **Product Domain Knowledge** | ⛔ **Product** | — | — | ⛔ Election, Voter, Adjudication | cannot be extracted, ever |

## 3. Domain relationships

⚠️ **Verbs as commissioned. ⭐ Evidenced edges solid; hypothesized edges marked. No Evans/Vernon pattern is assigned** — the ARB ruling holds: *the relationship is the conclusion, not the starting point.*

```
   D-1 ENGINEERING GOVERNANCE ──authorizes──▶ D-2 ENGINEERING METHOD
        │  (ARB commissions; ADR-AIP)              │  (own constitution; ADR-M)
        │ governs                                  │ produces
        ▼                                          ▼
   D-5 CAPABILITY (kind) ──instantiates──▶ capability instances
        │ realized as                             │
        ▼                                          │ derives
   D-3 RUNTIME ◀──isolates(ACL)── Capability Mapping
        │                                          ▼
        │ executes                          D-7 PKS (type)
        ▼                                          │ instantiates
   product engineering ──────────────────▶ PublicDigit PKS  ⚠️ hand-built
        │ produces                                 │ guides
        ▼                                          ▼
   D-6b PRODUCT EVIDENCE ──observed by──▶ D-6a EVIDENCE PROTOCOL
                                                   │ harvests
                                                   ▼
                                          ⛔ back to D-1 / D-2
                                          0 TRAVERSALS
```

| Edge | Status |
|---|---|
| D-1 **authorizes** D-2 | ⚠️ **HYPOTHESIZED.** ⭐ *`Round39-MC` was adopted **by sponsor authority (ADR-M-012)**, not by the ARB. **Whether D-1 authorizes D-2 or they are peers under a common sponsor is UNRESOLVED — and it is the model's most consequential open question*** |
| D-2 **produces** instruments | ✅ EVIDENCED — workbooks, criteria, registers |
| D-5 **instantiates** capabilities | ✅ EVIDENCED — CAP-001 |
| Capability Mapping **isolates** D-3 | ✅ EVIDENCED — *"cannot leak upward"* |
| D-7 type **instantiates** per product | ⚠️ PARTIAL — one instance, hand-built |
| D-6b **observed by** D-6a | ⚠️ PARTIAL — records exist, protocol unexercised |
| D-6a **harvests into** D-1/D-2 | ⛔⛔ **EMPTY — 0 traversals** |

## 4. Bounded contexts vs classifications

| Candidate | Bounded context? | Basis |
|---|---|---|
| **D-1 Engineering Governance** | ✅ **YES** | 5 criteria |
| **D-2 Engineering Method** | ✅ **YES — strongest** | own constitution · own ADR series · own baseline · own language |
| **D-3 Engineering Runtime** | ✅ **YES** | UL divergence engineered deliberately + deployment autonomy |
| **D-6a Evidence Protocol** | ✅ **YES** *(never executed)* | own lifecycle, own invariants, distinct ownership |
| **D-5 Engineering Capability** | ⛔ **NO — a knowledge kind** | ⛔ H-CAT-1's refusal stands |
| **D-4 Engineering Workflow** | ⛔ **NO — responsibilities of D-1/D-2** | no ownership, no language of its own |
| **D-7 PKS** | ⛔ **NO — a context TYPE** | one instance per product |
| **D-6b Product Evidence** | ⛔ **NO — product-side records** | not a platform context |
| **Product Domain Knowledge** | ✅ **YES — but product-side** | one bounded context per product |

> ### ⭐ **FOUR platform-side bounded contexts. Not eight.**
> ⛔ **Nothing here is created; four candidates are declined and two reclassified.** *Each rejection cites the criterion it failed, so it cannot be re-proposed on preference.*

## 5. Ubiquitous language — canonical meanings and live collisions

⭐ **The overloads are measured, not asserted.**

| Term | ⛔ Senses in use | Canonical meaning proposed | Severity |
|---|---|---|---|
| ⭐⭐ **Baseline** | ⛔ **FIVE** — sealed genesis corpus · **Methodology Baseline MB-39.1** · Architecture Baseline 1.1 · **Operational Baseline** (CAP-001 §6) · PKS Phase II Baseline | *an artifact set frozen at a point in time, **always qualified by its domain*** | ⛔⛔ **CRITICAL — new finding** |
| ⭐ **Constitution** | ⛔ **FIVE+** — Methodology · Strategic DDD · Knowledge · Implementation Architecture · Project | *the entrenched principles of **one** domain, no ADR may override* | ⛔ **HIGH** |
| ⭐ **Capability** | ⛔ **THREE** — Platform Capability *(Messaging, Replay)* · **Engineering** Capability *(CAP-001)* · business capability | **D-5:** *a reusable engineering responsibility with one invariant* | ⛔ **HIGH** |
| **Platform** | ⛔ FOUR — Engineering Platform · AI Engineering Platform · Platform Capability · KnowledgeOS | *already an ARB-recorded naming drift* | ⚠️ **known** |
| **Register** | ⛔ THREE | ⭐ *always written **register(ns)*** when the namespace is meant | ⚠️ mitigated |
| **Evidence** | ⛔ THREE — operational · citation · Pattern Evidence Register | **D-6a** protocol vs **D-6b** records | ⚠️ **now resolved by the split** |
| **Governance** | ⛔ THREE — engineering governance · **constitutional governance** *(a product domain!)* · knowledge-release governance | **D-1** only | ⛔ **HIGH — one sense is product-side** |
| **Method** | ⛔ THREE — engineering method · research method · PHP method | **D-2** | ⚠️ medium |
| **Model** | ⛔ FOUR — domain · meta · reference · read model | qualify always | ⚠️ medium |
| **Pattern** | ⛔ THREE — architecture pattern · Pattern Card EPC · DDD pattern | qualify always | ⚠️ medium |
| **Qualification** | ⛔ TWO | *already an open UL collision* | ⚠️ recorded |
| **Verdict** | ⛔ TWO closed sets, colliding | one scoping rule needed | ⛔ **HIGH** |

> ### ⭐ **"Baseline" with five senses is the most severe unrecorded collision in the repository — and one of them (MB-39.1) belongs to D-2, whose existence was itself unindexed.**

## 6. Does the folder structure follow the model?

⛔ **Assessed last, as commissioned. Folders are projections. ⛔ No folder is proposed.**

| Domain | Where its knowledge physically lives | Follows the model? |
|---|---|---|
| **D-1 Governance** | `engineering/governance/` · `engineering/architecture/adr/` | ✅ **YES** |
| ⭐ **D-2 Method** | ⛔⛔ **`docs/architecture/design/`** — a **PRODUCT** path under ES-005.1 | ⛔⛔ **NO — the model's largest misfit** |
| **D-3 Runtime** | `.claude/` + the four-layer record in `docs/implementation/` | ⚠️ **SPLIT** — mount correct, model product-side |
| **D-5 Capability** *(kind)* | `docs/architecture/patterns/` + `scripts/lib/…` | ⚠️ **SPLIT** — pattern product-side, instance platform-side |
| **D-6a Evidence Protocol** | `engineering/governance/ES-006` | ✅ YES |
| **D-6b Product Evidence** | `engineering/verification/` | ⛔ **INVERTED** — product records inside the platform tree |
| **D-7 PKS instances** | `docs/pks` · `docs/implementation/PKS_*` | ⚠️ split across two roots |

> ## ⭐⭐ **THE SAME MISFIT, NOW DERIVED FROM MEANING RATHER THAN FROM A GREP**
>
> **Earlier I found "domain-free methodology sits in a product path" by *searching for election vocabulary*. The domain model reaches it by *asking who owns the knowledge* — and reaches further: it also exposes D-6b (product records inside `engineering/`), which no vocabulary search could find, because records contain no methodology terms at all.**
>
> ### ⭐ **That is the demonstration that the model had to precede the index.**

**On the three existing roots:** `docs/knowledgeos` · `docs/pks` · `docs/publicdigit` project **product ownership**, a valid axis — ⚠️ **but the model has FOUR platform-side contexts and they are not co-located.** ⛔ *Reconciling that is an ARB matter and requires R-37 to be lifted; nothing is proposed here.*

## 7. Architectural recommendations

| # | |
|---|---|
| **R-1** | ⭐⭐ **Resolve the D-1 ↔ D-2 authority question first.** *`Round39-MC` was adopted by **sponsor authority**, not the ARB. Are Governance and Method peers, or is Method subordinate? **Every extraction decision depends on the answer, and no document can settle it*** |
| **R-2** | ⭐ **Put the `Baseline` five-way collision to the ARB** — cheapest high-severity UL fix; ⛔ *renaming is forbidden, qualification is the instrument* |
| **R-3** | ⭐ **Record `D-6a` / `D-6b` as the canonical reading of "Evidence"** — it resolves a known three-way overload and explains why evidence has never flowed |
| **R-4** | ⚠️ **Do not promote D-5 to a bounded context** until H-CAT-1 is overturned **on evidence** by the ARB |
| **R-5** | ⛔ **Do not build the index yet** — ⭐ *but the model now supplies its ontology: **index by DOMAIN (D-1..D-7) and by P1 layer (method/binding/evidence)**, never by artifact kind* |
| **R-6** | ⭐ **Permanent to KnowledgeOS:** D-1 · D-2's method · D-3's four layers + Capability Mapping · D-5's pattern · D-6a · D-7's type |
| **R-7** | ⛔ **Permanent to a product, never extractable:** all product domain knowledge · D-6b records · every register's contents · SD-1's replacement · each PKS instance |
| **R-8** | ⭐ **Emerged reusable assets** *(from the Landscape, not re-inventoried)*: the methodology governance stack · the discovery instruments · two decision templates · six uncertainty registers · the adversarial challenge pattern · the drift-vs-consequence detector |

---

## ⭐ Closing

**The commission asked for a semantic architecture instead of another inventory. The model yields five things the inventory could not:**

| | |
|---|---|
| ⭐⭐ **Engineering Method is a bounded context distinct from Engineering Governance** | two constitutions · two ADR series · two baselines · two vocabularies |
| ⭐ **"Engineering Evidence" is not one domain** | the boundary runs **through** it — protocol vs records — and canon already said so |
| ⛔ **"Engineering Workflow" is not a domain** | responsibilities of D-1 and D-2 wearing a third name |
| ⭐ **PKS is a context TYPE, not a context** | one instance per product; conflating the two is how methodology entered a product path |
| ⭐ **`Baseline` carries FIVE meanings** | the most severe unrecorded collision found |

> ### **Four platform-side bounded contexts. Not eight candidates, and not a folder tree.**
> ### ⭐ **And the index now has an ontology to be keyed to — which is precisely what it lacked yesterday.**

---

*Traceability: Engineering Knowledge Domain Model commission + ARB Chair's Addendum, 2026-08-02 · ⭐ **the Landscape accepted as observational evidence only and NOT extended** · every candidate validated against **`Round47-OP`'s nine admissible justifications**, graded with the **R16 Workbook's Strong/Medium/Weak** scale · ⭐ **4 of 8 candidates survive as offered: D-4 REJECTED · D-5 demoted to a knowledge kind (H-CAT-1 stands) · D-6 SPLIT into protocol and records · D-7 reclassified as a context TYPE** · **D-2 established as a bounded context distinct from D-1 on four independent grounds** · **UL: 12 terms, `Baseline`×5 recorded as a new critical collision** · ⚠️ **the D-1 ↔ D-2 authority relationship is marked HYPOTHESIZED and referred to the ARB** · folders assessed **last**, as projections · ⛔ **no pattern assigned · no folder proposed · no file moved · no ADR · no inventory extended · no domain invented.***

> **⛔ Submitted to the Decision Authority. Nothing in this document executes.**
