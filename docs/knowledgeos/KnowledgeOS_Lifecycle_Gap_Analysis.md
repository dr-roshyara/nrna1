# KnowledgeOS — Lifecycle Gap Analysis

| | |
|---|---|
| **Kind** | ⭐ **LIFECYCLE GAP ANALYSIS.** ⛔ ***Not an ontology revision · not a lifecycle design · no new concept · no implementation · no ADR.*** |
| **Status** | ⚠️ **CANDIDATE — NOT ADOPTED** |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Commission** | Principal Knowledge Engineer / Strategic DDD Architect / Enterprise Software Architect, 2026-08-02 |
| ⛔ **Constraint honoured** | **THE ONTOLOGY IS FROZEN.** *Nothing added, removed or renamed* |
| ⭐ **Success criterion** | ⛔ **not repairing anything** — *identifying which failures belong to **ontology**, which to **lifecycle**, and which to **governance*** |

> ## ⛔⛔ **THE CORRECTION IS ACCEPTED IN FULL — AND IT IS MY FOURTH INSTANCE OF LEVEL CONFUSION**
>
> ### ⭐ **`I-4` is NOT falsified. I misdiagnosed a LIFECYCLE gap as an ONTOLOGY falsification.**
>
> **A discharge summary is not authoritative *because it was projected*. It became authoritative through:**
>
> ```
>    DERIVED  →  REVIEWED  →  ATTESTED  →  RELEASED
> ```
>
> ⭐⭐ **Governance created the authority, not the derivation.** *So the correct conclusion is not "DP-2 is wrong" but **"DP-2 conflates DERIVATION with ATTESTATION"** — a different and better claim.*
>
> | My four level confusions | |
> |---|---|
> | flat vs dimensional | *(taxonomy)* |
> | artifact vs concept | *(existence)* |
> | layer vs sibling | *(ontology)* |
> | ⭐ **lifecycle vs structure** | *(this one)* |
>
> ⭐ **And a fifth was avoided by checking:** *I was about to report "there is no lifecycle." **There is** — see §1.*

---

## 1. ⭐ What already exists — checked before analysing gaps

> ### ⛔ **`docs/knowledge/_meta/lifecycle.md` — `status: baseline` · `authority: authoritative` · v1.1 — titled *"Knowledge Lifecycle, Governance & Authority Model."***

**It is more complete than I assumed:**

| Section | Content |
|---|---|
| ⭐ **§1** | **Two independent dimensions** — *"Every document carries **both** at all times. **Most projects wrongly conflate them**"* |
| ⭐⭐ **§2** | **Governance roles — *who* moves a document.** *A full transition→performer table: Author · Peer reviewer · **ARB/Chief Architect** · Knowledge/Release Manager · **Governance Board** · **ADR-only** for `frozen → superseded`* |
| ⭐⭐⭐ **§3** | **Transition guards — *what must be true*.** *A guard per target state, and **"each transition has guards `knowledge-lint` can check"*** |
| **§4** | **Knowledge Quality Gates** — *"'Approved' is **objective**, not…"* |

**Eight further lifecycles exist elsewhere:**

| # | Lifecycle | States |
|---|---|---|
| **1** | `statuses.yaml` state machine | 8, with `order` + `settled` |
| **2** | **ES-006.1 promotion ladder** | research → pilot → qualification → standard |
| **3** | **EEP** | plan → review → approval → implement → verify → report → decide |
| **4** | **Capability** | observed → candidate → designed → realized → evidenced → promoted |
| **5** | ⭐ **ADR-M status** | Draft · Accepted · Controlled · Superseded · Retired |
| **6** | ⭐ **Methodology maturity** | Observed · Replicated · Reinforced · Working Principle · General Principle |
| **7** | **Identifier lifecycles** | four, one per series *(R · PMR · ADR · ES)* |
| **8** | **Observation Protocol** | ACTIVE → CONCLUDED *(confirmed \| falsified \| inconclusive)* |

> ### ⭐ **Nine lifecycles, with roles and machine-checkable guards. ⛔ The gap is NOT "no lifecycle."**

## 2. ⭐⭐ The precise gap — located mechanically

**`statuses.yaml` and `authorities.yaml` are declared orthogonal, and they are NOT equally equipped:**

| | `statuses.yaml` | `authorities.yaml` |
|---|---|---|
| Values | 8 | 5 |
| **Progression field** | ⭐ **`order`** | ⛔ **none** |
| **Terminal marker** | ⭐ **`settled`** | ⛔ **none** |
| **Who may move it** | ⭐ **§2 role table** | ⛔⛔ **NOT DEFINED** |
| **Transition guards** | ⭐ **§3 guard table, lint-checkable** | ⛔⛔ **NOT DEFINED** |
| Referenced in a guard | — | ⭐ yes — *`baseline` requires `authority: authoritative` or `derived`* |

> # ⭐⭐⭐ **THE GAP: STATUS HAS A GOVERNED STATE MACHINE. AUTHORITY DOES NOT.**
>
> **Authority *gates* a status transition, but nothing governs how AUTHORITY ITSELF changes.** ⛔ *There is no rule for **who may move `derived` → `authoritative`**, and no guard for it.*
>
> ### ⭐⭐ **AND THAT IS EXACTLY THE `I-4` PROBLEM.** *Attestation **is** an authority transition (`derived → authoritative`). **Because authority transitions are ungoverned, the model cannot express "attested" — so it collapses "derived" into "never authoritative."***
>
> ⭐ *The reviewer's diagnosis — "DP-2 conflates derivation and attestation" — now has a mechanical cause rather than an intuition.*

## 3. Classification — every gap from the projection

⭐ **Three categories as the review defines them: Structural *(what exists)* · Behavioral *(what acts)* · Evolutionary *(how things change)*.**

| # | Gap | ⭐ Category | Cause | ⭐ Belongs to |
|---|---|---|---|---|
| **G-1** | **`I-4` / `ATTESTS` / PROJECTION inert-vs-attested** | ⭐ **EVOLUTIONARY** | ⭐⭐ **MISSING LIFECYCLE** — *authority has no transitions* | ⭐⭐ **LIFECYCLE** |
| **G-2** | **`SPECIALIZES`** *(Engineering Knowledge → product knowledge)* | ⭐ **EVOLUTIONARY** | **MISSING RELATIONSHIP** | ⭐ **ONTOLOGY** |
| **G-3** | **External authority / `CONSTRAINS`** | ⭐ **BEHAVIORAL** *(an obligation acts on a principle)* | **MISSING RELATIONSHIP** — *obligation ≠ ownership* | ⭐⭐ **GOVERNANCE** |
| ⭐ **G-4** | **PRODUCT vs DEPLOYMENT / `INSTANTIATES`** | **STRUCTURAL** | ⭐⭐ **NOT MISSING — it DISSOLVES.** *A deployment has its own bindings and a declared boundary: **a deployment IS a Knowledge Space**. The ontology already permits `Knowledge Space contains other Knowledge Spaces`* | ⭐ **ONTOLOGY — resolved, no new concept** |
| **G-5** | **Runtime Adapter disappeared in both new domains** | STRUCTURAL | ⛔ **not a failure — a CONFIRMATION** *(Hexagonal behaving correctly)* | — |

> ### ⭐⭐ **G-4 answers `H-3` — the nesting question — using evidence rather than preference: Knowledge Spaces NEST, and a deployment is the nested case.**
> ⛔ *No concept introduced. The ontology explained the evidence, which is the test the commission set.*

## 4. ⭐ Answer to the success criterion

| Domain | Count | Which |
|---|---|---|
| ⭐⭐ **LIFECYCLE** | **1** | **G-1** — *the largest, and the one I mislabelled* |
| ⭐ **ONTOLOGY** | **1** *(+1 dissolved)* | **G-2 `SPECIALIZES`** · *G-4 resolved by nesting* |
| ⭐ **GOVERNANCE** | **1** | **G-3** — *obligation has no home in an ownership model* |
| **NOT A FAILURE** | **1** | G-5 |

> # ⭐⭐⭐ **ONLY ONE OF THE FOUR IS GENUINELY ONTOLOGICAL.**
>
> ### **The commission's hypothesis is confirmed: most of what looked like ontology failure is lifecycle and governance wearing ontology's clothes.**

## 5. ⚠️ A second lifecycle gap the check exposed

### ⚠️ 5.1 The authoritative lifecycle document and the executable enum disagree

| | |
|---|---|
| ⭐ **`_meta/lifecycle.md` §1 diagram** | **`idea → research → draft → discovery → reviewed → approved → baseline → frozen`** |
| ⛔ **`statuses.yaml` enum** | **`draft · discovery · reviewed · approved · baseline · frozen · superseded · archived`** — ⛔ **`idea` and `research` are ABSENT** |
| ⚠️ **Where `idea` and `research` DO live** | ⛔⛔ **as `knowledge_type` values** in `knowledge-types.yaml` |

> ### ⛔⛔ **The `baseline`/`authoritative` lifecycle document shows two states the executable enum does not define — and those two names exist as TYPES instead.**
> ⭐⭐ ***That is a type/status conflation inside the very document whose §1 warns "most projects wrongly conflate them."***
>
> ⚠️ **Recorded as an observation, not a defect claim** — *the diagram may be aspirational, or the enum may be behind it. **Either way the two do not agree, and `knowledge-lint` enforces the enum.***

### ⚠️ 5.2 Five lifecycle vocabularies — and only some multiplicity is healthy

| Pair | Verdict |
|---|---|
| `status` ⟂ `authority` | ⭐ **HEALTHY — declared orthogonal** *("independent of status")* |
| ADR-M status ⟂ methodology maturity | ⭐ **HEALTHY — declared** *("These are **independent** axes")* |
| ⚠️ `statuses.yaml` (8) vs ADR-M (5) | ⚠️ **possible duplication** — *both are document lifecycles, for different artifact classes* |
| ⚠️ ES-006.1 ladder (4) vs methodology maturity (5) | ⚠️ **possible duplication** — *both are promotion ladders* |

⭐ **The repository already practises orthogonal lifecycle axes deliberately. ⛔ What is unreconciled is whether the last two pairs are two axes or two names for one.**

## 6. What is NOT a gap

| ⛔ Not a gap | Because |
|---|---|
| *"There is no lifecycle"* | ⛔ **nine exist**, one with roles and lint-checkable guards |
| *"There is no transition governance"* | ⛔ **§2 names a performer for every status transition** |
| *"Quality gates are undefined"* | ⛔ **§4 defines "Approved" objectively** |
| *"`I-4` is false"* | ⛔ **it is UNDERSPECIFIED, not false** — *true until attestation, and attestation is ungoverned* |
| *"PRODUCT vs DEPLOYMENT needs a new concept"* | ⛔ **Knowledge-Space nesting covers it** |

## 7. Open questions

| # | Question | Authority |
|---|---|---|
| ⛔⛔ **LG-1** *(WITHDRAWN 2026-08-02 — ILL-POSED)* | ~~Should `authority` have a governed state machine?~~ ⭐⭐ **The question dissolved under discovery: `authority` is TWO dimensions in one field — immutable PROVENANCE (`generated`, `derived`) × act-changed STANDING (`authoritative`, `provisional`, `historical`). Provenance CANNOT have a state machine; standing changes by governance ACTS.** *Superseded by **PM-1/PM-2** — record: `KnowledgeOS_Engineering_Progression_Model.md` §4* | **ARB + PD-3's owner** |
| ⭐ **LG-2** | **Restate DP-2 to separate DERIVATION from ATTESTATION?** *This is CV-1, now with a mechanical cause* | **ARB** |
| ⭐ **LG-3** | **`lifecycle.md` shows `idea`/`research` as statuses; `statuses.yaml` defines them as types.** *Which is canonical?* | **PD-3's owner** |
| **LG-4** | Are ADR-M-status vs `statuses.yaml`, and ES-006.1 vs methodology maturity, **two axes or two names**? | ARB |
| **LG-5** | Admit `SPECIALIZES` — ⭐ **the one genuinely ontological gap** | ARB |
| **LG-6** | Where does **obligation** attach, given the model has only ownership? | ARB |
| **H-3** *(resolved)* | ⭐ **Knowledge Spaces NEST** — *closed by G-4* | — |

---

## ⭐ Closing

| | |
|---|---|
| ⛔ **Conceded** | ⭐ **`I-4` is not falsified. I misdiagnosed a lifecycle gap as an ontology falsification — my fourth level confusion** |
| ⭐ **Located mechanically** | ⭐⭐ **`status` has a governed state machine with roles and lint-checkable guards; `authority` has neither.** *That single asymmetry produces the `I-4`/DP-2 conflation* |
| ⭐⭐ **The commission's hypothesis** | ⭐ **CONFIRMED — only 1 of 4 gaps is ontological.** *One is lifecycle, one is governance, one dissolves* |
| ⭐ **Resolved without adding anything** | **PRODUCT vs DEPLOYMENT** — *a deployment **is** a nested Knowledge Space; `H-3` closed on evidence* |
| ⚠️ **Found while checking** | ⭐ **the `authoritative` lifecycle document and the executable enum disagree** — *and the disagreement is a type/status conflation inside the document that warns against exactly that* |
| ⭐ **Fifth error avoided** | *I was about to report "there is no lifecycle." **Checking first is now the habit that keeps paying*** |

> ### **The projection did not find a broken ontology. It found a missing state machine — for authority — and everything downstream of that absence looked like an ontology problem.**
> ### ⭐ **One asymmetry between two YAML files explains the single most consequential failure in the cross-product validation.**

---

*Traceability: lifecycle gap-analysis commission 2026-08-02 · ⛔ **the ontology was FROZEN throughout; nothing added, removed or renamed** · ⭐ **`I-4`'s falsification WITHDRAWN — the reviewer's diagnosis accepted: DP-2 conflates DERIVATION with ATTESTATION, and this is my fourth level confusion (lifecycle vs structure)** · ⭐⭐ **existing lifecycles CHECKED FIRST: `_meta/lifecycle.md` (`status: baseline`, `authority: authoritative`) supplies a status state machine, a who-may-move role table, and transition guards `knowledge-lint` can check — plus eight further lifecycles. A fifth "X does not exist" claim was avoided** · ⭐⭐⭐ **the gap located mechanically: `statuses.yaml` carries `order`/`settled`/roles/guards; `authorities.yaml` carries NONE — status has a governed state machine, authority does not, and that asymmetry makes "attested" inexpressible** · **4 gaps classified Structural/Behavioral/Evolutionary: 1 LIFECYCLE · 1 ONTOLOGY (+1 dissolved) · 1 GOVERNANCE · 1 confirmation — the commission's hypothesis CONFIRMED** · ⭐ **PRODUCT-vs-DEPLOYMENT dissolved: a deployment IS a nested Knowledge Space, closing `H-3` on evidence with no new concept** · ⚠️ **new observation: `lifecycle.md`'s diagram shows `idea`/`research` as statuses while `statuses.yaml` defines them as types** · ⛔ **no ontology revision · no lifecycle design · no implementation · no ADR.***

> **⛔ Submitted to the Decision Authority. Nothing in this document executes.**
