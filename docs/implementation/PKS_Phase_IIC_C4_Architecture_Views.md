# PKS Phase II.C — C4 Architecture Views (C4-1)

| | |
|---|---|
| **Kind** | **Architecture views** — a knowledge-preserving transformation of the governed logical architecture into visual representation. **The diagrams communicate architecture; they do not create it.** |
| **Authority** | Generated — never authoritative without human review. **These views are representations: not evidence, not architectural decisions, not design work.** Per the governed corpus's own rule, *nothing may cite a view as authority* — including these views. On any conflict between a diagram and AD-1, **AD-1 governs and the diagram is corrected.** |
| **Status at execution (retained as history — TRUE WHEN WRITTEN, superseded by promotion 2026-07-30)** | **EXECUTED — three views rendered (L1 · L2 · a substitute for L3); L3-as-conventionally-understood and L4 are NOT DERIVABLE and are not supplied. STOP.** No implementation, no promotion, no MCA, no CDR, no methodology evolution, no literature benchmarking performed. | *(Renamed per the extension of change-control item M7R-1 to this artifact: the row is renamed, not rewritten — the governing Status is the promotion row. Any "STOP" below was an instruction to the executing commission, not to a reader.)*
| **Commission** | Phase II.C C4 Architecture Views Commission C4-1 (PA, 2026-07-28). |
| **Inputs (only these)** | AD-1 (`PKS_Phase_IIB_Architecture_Definition.md`) — its logical architecture (§6), responsibilities (§7), service boundaries (§8), communication model (§9), integration model (§10), **dependency rules DR-1..DR-8 (§10A)**, **principles AP-1..AP-10 (§5)**, traceability matrix (§11), and open questions (§12) · the strategic relationships **as disposed by DAR-1**. No other source consulted. |
| **Frozen and unmodified** | SDM v1 · EOP v1 · M0–M8 · DAR-1 · Checkpoint · RET-1 · AD-1. |
| **Placement** | `docs/implementation/`, closing the Phase II.C record set. |
| **Status** | **🏁 PROMOTED (Authority act, 2026-07-30).** **A promoted view still holds NO AUTHORITY: DP-7 and AP-2 continue to govern — *nothing may cite a view as authority*, and on conflict AD-1 governs and the view is corrected. C4-1 did not acquire authority because its source did.** AR-1's placement remains **contingent on OQ-PKS-7**; KO-1..KO-10 remain declared omissions. **Now governing — no longer freely editable; further change runs through change control.** |
| **Disposition History** | **2026-07-30: change-control item M7R-1 extended to this artifact** — the execution-era `Status` row renamed to *"Status at execution (retained as history…)"*, removing the duplicate-key ambiguity created by the promotion act. **Content-neutral: renamed, not rewritten; nothing else touched, nothing re-verified.** · **2026-07-30: CHANGE-CONTROL items C4R-1/C4R-2/C4R-3 disposed ACCEPT and applied to this PROMOTED artifact** *(post-promotion assurance review, representation-fidelity emphasis; the artifact was promoted, so these were routed through change control rather than applied as review remediations)*. **C4R-1** — the **Knowledge Structure View** name propagated to §1, KO-3 and §10, and the repudiated *"substitute"* framing removed: the view is *a governed architectural projection in its own right*. **C4R-2** — KO-10 reordered to follow KO-9; both omission counts corrected to **ten**. **C4R-3** — §10 **date-marked, not deleted.** **No view, element, arrow, boundary, omission, or provenance row was changed** — nothing was re-verified against AD-1 because nothing touched architectural meaning. · **2026-07-30: PROMOTED by Authority act (record: `PKS_Phase_II_Execution_Record.md`).** · **2026-07-30: SYNCHRONIZED with the amended AD-1 under CCP-1 Package C (C-10..C-15), after C-18 recorded PASS. C-11 applied verbatim (*never a **Verdict***, restoring the frozen term against prose-smoothing); AR-1's placement annotated with the OQ-PKS-7 contingency (C-14); the three out-of-input-set labels routed via AD-1's matrix (C-10); the AR-negation list's provenance recorded (C-12); KO-10 added for DR-2's intentional non-rendering (C-13). The views assert no more than AD-1 does.** · **2026-07-30: PD-F1/PD-F2 record-completion applied — §6B added retaining C-11's superseded wording (CI-6); C-12's provenance note corrected, since AD-1 §6.2 does derive the AR-negation list. No content statement changed.** · 2026-07-28: executed under C4-1. · 2026-07-28: **two PA refinements folded** — (a) DP-5 strengthened from *"render only what AD-1 derives"* to **"every graphical element shall have an architectural provenance"** (no box because diagrams usually have boxes); (b) the Level-3 substitute **renamed the Knowledge Structure View**, a governed architectural projection in its own right rather than a deficient C4 level. No diagram content changed. · 2026-07-28: **verified by C4-2** (`PKS_Phase_IIC_C4_Representation_Verification.md`) — **PASS WITH FINDINGS** (1 Moderate, 3 Minor, 0 Major); corrections recommended, **not applied here** (C4-2 may not redraw). |

**Epistemic classes carried from AD-1** (no statement is **Observed**; C4-1 performs no discovery): **Derived** · **Assembly** · **Recommendation** · **Out of Scope**.

---

## 1. Executive Summary

**Derived: the views are as partial as the architecture, and three things the commission asked for turned out not to be derivable. All three are recorded rather than supplied.**

| View | Status |
|---|---|
| **Level 1 — System Context** | Rendered — **without actors** |
| **Level 2 — Container View** | Rendered — two logical containers, one external domain, two undefined regions rendered *as regions* |
| **Level 3 — Component View** | **Not derivable as a component decomposition.** A **Knowledge Structure View** is supplied instead — **a governed architectural projection in its own right, not a substitute for anything** (§6) |
| **Level 4 — Code View** | **Not derivable.** Not supplied |

**The three non-derivable items, and why each is a finding rather than a gap in effort:**

1. **No actors are renderable.** AD-1 derives none, and the reason is substantive rather than an oversight: the governed model records that in key places **no role exists**. Two ownership vacuums are recorded evidence (post-approval custody; cross-artifact consistency), and the capability AC-1 encloses has **no named owner** (OQ-PKS-3, unresolved). **Derived: drawing actors would invent roles the corpus explicitly records as absent** — the most consequential single act of invention available in this commission, and the one most likely to pass unnoticed in a diagram.
2. **No mechanism may appear on any arrow.** AD-1 §9.2 records that the governed model contains no timing, coupling, delivery, or ordering evidence. Every arrow below therefore carries **direction and content only**. The legend states this on every view.
3. **Level 3 is not a component view.** AD-1 defines AC-1 and AC-2 as components and allocates responsibilities to them, but derives **no internal components**. Nested boxes would assert an internal structure that does not exist, so the faithful rendering is the **Knowledge Structure View** (§6) — an allocation of responsibilities, presented as a governed projection rather than as a stand-in for a component view.

**Derived — the governing principle applied throughout: visual precision never exceeds architectural precision.** Where the architecture is silent, the diagram is silent. Where the architecture is undefined, the diagram shows an undefined region. **Silence is preferable to invention** — and in a visual medium, invention is cheap and nearly invisible, which is why the constraints below are stated before the diagrams rather than after.

---

## 2. Scope

**In scope:** rendering AD-1's logical architecture as C4 views; preserving responsibility, ownership, dependency direction, dependency asymmetry, boundedness, and undefined regions; recording every element's provenance; recording what is intentionally not rendered.

**Out of Scope (each explicit so its absence is not read as omission):** no architectural element added, removed, renamed, split, or merged · no strategic model change · no relationship reinterpretation · no Surfacing Register resolution · no PMR admission · no SDM/EOP change · no tactical DDD (no aggregates, entities, value objects, repositories) · no APIs · no schemas · no deployment · no runtime topology · no technology.

**Verified absent from every diagram below:** synchronization, messaging, events, request/response, orchestration, choreography, deployment nodes, runtime topology, data stores, protocols, technologies.

---

## 3. Diagram Principles (derived from AD-1 and the commission's constraints)

| # | Principle | Consequence for the views |
|---|---|---|
| **DP-1** | **Visual precision ≤ architectural precision** | No diagram may be more specific than AD-1. Where AD-1 is silent, the diagram is silent |
| **DP-2** | **Direction does not imply mechanism** | Arrows carry direction and content. They imply nothing about synchronicity, transport, control, execution order, or delivery (AD-1 §9.2) |
| **DP-3** | **Undefined remains undefined** | AR-1 and AR-2 appear only as *undefined architectural regions* — never as containers, services, bounded contexts, components, or data stores (AD-1 §6.2) |
| **DP-4** | **Silence over invention** | A missing element is recorded in §8, never drawn speculatively |
| **DP-5** | **Every graphical element shall have an architectural provenance** *(PA refinement — strengthened from "render only what AD-1 derives")* | **No box exists because diagrams usually have boxes; no arrow exists because C4 diagrams usually contain arrows.** Every visual object — box, arrow, label, annotation, legend entry — exists because a governed architectural statement requires it, and appears in §7 with its AD-1 section, strategic concept, and artifact |
| **DP-6** | **Containers carry no runtime semantics** | AC-1 and AC-2 are rendered at Level 2 as the commission directs, but **the conventional C4 container notion of a deployable/runtime unit is NOT derived** — AD-1 is deployment-neutral by construction. They are *logical* containers |
| **DP-7** | **The views are subordinate to what they render** | *"Nothing may cite a view as authority"* (AP-2) applies to these diagrams; on conflict, AD-1 governs and the diagram is corrected |

---

## 4. C4 Level 1 — System Context

```
   ⃝  ACTORS — NOT RENDERED.  AD-1 derives no actors, and the governed
      model records that in key places NO ROLE EXISTS (two ownership
      vacuums; no owner for the enclosed assessment capability).
      Drawing actors would invent roles the corpus records as absent.
      → §8, KO-1

 ┌──────────────────────────────────────────────────────────────────┐
 │  PRODUCT KNOWLEDGE SYSTEM                        [system boundary]│
 │                                                                   │
 │  Governs knowledge about a software program — its decisions,       │
 │  terms, norms, judgments, and renderings.                          │
 │                                                                   │
 │  Contains: two logical containers (Knowledge Assessment ·          │
 │  Knowledge Projection) and two architecturally undefined regions.  │
 │  → Level 2                                                        │
 └──────────────────────────────────────────────────────────────────┘
                              ▲
                              │  A8: knowledge obligations
                              │      (interchange terms: the Definition
                              │       of Done's five knowledge boxes)
                              │  ⚠ translation obligation UNASSIGNED (U-2)
                              │  ⚠ direction only — mechanism NOT derived
                              │  one-way: no PKS element depends on
                              │           Work Management (DR-5)
 ································································
 ·  WORK MANAGEMENT                    [external domain, outside PKS] ·
 ·  Tracks units of work and their progress.                          ·
 ·  Interior deliberately unmodelled — outside PKS design authority.  ·
 ································································
```

**Assembly — what this view asserts:** one system boundary; one external domain; one dependency, and it runs **inward** (work items carry obligations toward knowledge; no knowledge kind depends on a work item). **Derived — what it deliberately does not assert:** who operates the system, how the dependency is satisfied, or what lies inside the external domain.

---

## 5. C4 Level 2 — Container View

```
╔══════════════════════════════════════════════════════════════════════╗
║  PRODUCT KNOWLEDGE SYSTEM                            [system boundary]║
║                                                                       ║
║  ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   ║
║  ~  AR-1  NORMATIVE REGION                                       ~   ║
║  ~  ***ARCHITECTURALLY UNDEFINED REGION***                        ~   ║
║  ~  Not a container · not a service · not a bounded context ·     ~   ║
║  ~  not a component · not a data store.                           ~   ║
║  ~  Contents are depended upon; no boundary exists to enclose.    ~   ║
║  ~  Origin: a candidate seam — a formal state distinct from a     ~   ║
║  ~  bounded context. Interior NOT decomposed (DP-3).              ~   ║
║  ~~~╪~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~╪~~~~~~╪~~~~~   ║
║     │ A1 criteria           A3 issuance trigger        │       │      ║
║     │    (read-only, DR-4)     (AC-1 cannot self-issue)│ A2    │ A5   ║
║     ▼    ◄────────────────────────────────────────────┘ verdicts│     ║
║  ┌─────────────────────────────────────────────────────────┐    │     ║
║  │  AC-1  KNOWLEDGE ASSESSMENT              [logical container]│   │     ║
║  │                                                          │    │     ║
║  │  Records evidence about the knowledge system, evaluates   │    │     ║
║  │  it against declared criteria, and issues judgments that  │    │     ║
║  │  feed authority without being authority.                  │    │     ║
║  │                                                          │    │     ║
║  │  Holds NO authority · holds NO criteria · cannot self-issue│   │     ║
║  │  ⚠ encloses one responsibility that NOTHING REALIZES      │    │     ║
║  │  ⚠ handling of three contested concepts allocated NOWHERE │    │     ║
║  │  Outbound surface carries the closed verdict vocabulary    │    │     ║
║  │  ONLY — narrower than its internal vocabulary (DR-8)      │    │     ║
║  └──────────────────────────┬──────────────────────────────┘    │     ║
║          ▲                   │ A4 renders from                   │     ║
║          │ A7                │    ONE-WAY (AP-9, DR-1)           │     ║
║  ~~~~~~~~╪~~~~~~~~~~~~~~~~~~~│~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~│~~~   ║
║  ~  AR-2  EXPRESSED-KNOWLEDGE REGION                      ~      │     ║
║  ~  ***ARCHITECTURALLY UNDEFINED REGION***                 ~      │     ║
║  ~  Not a container · not a service · not a component ·     ~      │     ║
║  ~  not a data store.                                       ~      │     ║
║  ~  What the containers act upon. No internal structure is  ~      │     ║
║  ~  governed; interior NOT decomposed (DP-3).                ~      │     ║
║  ~~~~~~~~~~~~~~~~~~~~~~~~~~~~│~~~~~~~~~~~~~~~~~~~~~~ A6 ~~~~│~~~~~~   ║
║                               │  one-way                     │         ║
║                               ▼                              ▼         ║
║  ┌──────────────────────────────────────────────────────────────┐     ║
║  │  AC-2  KNOWLEDGE PROJECTION              [logical container]  │     ║
║  │                                                               │     ║
║  │  Renders governed knowledge into consumable forms without     │     ║
║  │  acquiring identity or authority of its own.                   │     ║
║  │                                                               │     ║
║  │  Holds NO source of truth · NO authority · NO return path      │     ║
║  │  ★ TERMINAL SINK: nothing may depend on this container (DR-1)  │     ║
║  │  ★ Every projection must be reproducible from its sources      │     ║
║  │  ⚠ inherited identity liability (representational-only         │     ║
║  │    identity for one member kind)                               │     ║
║  └──────────────────────────────────────────────────────────────┘     ║
║        ✕  no relationship with Work Management (Separate Ways)         ║
╚══════════════════════════════════════════════════════════════════════╝
                              ▲
                              │  A8 knowledge obligations (one-way inward)
 ································································
 ·  XD-1  WORK MANAGEMENT              [external domain, outside PKS] ·
 ································································

 LEGEND
 ┌───┐ logical container (NO runtime or deployment semantics — DP-6)
 ~~~~  architecturally undefined region (NOT a container — DP-3)
 ····  external domain, interior unmodelled
 ──▶   dependency: DIRECTION AND CONTENT ONLY.
       Implies NOTHING about synchronicity, messaging, events,
       request/response, orchestration, choreography, execution order,
       transport, or delivery guarantees (DP-2; AD-1 §9.2).
 ★     a constraint that makes the element's correctness checkable
 ⚠     a recorded gap or liability, carried not repaired
 ✕     an explicit non-relationship
```

**Derived — three features of this view are unusual and each is evidence-driven, not stylistic:** (i) two of the four interior elements are **regions rather than containers**, because no governed boundary exists to enclose them; (ii) **AC-2 is a terminal sink** — no arrow leaves it, and none may; (iii) **the arrow into AC-1 that triggers issuance originates in an undefined region**, because the governed rule places issuance outside the assessment boundary.

---

## 6. Knowledge Structure View *(a governed architectural projection — not a C4 Level 3)*

**Naming (PA refinement).** This view is **not** called an "L3 substitute", because that name invites comparison against standard C4 and implies a deficient version of something. It is a **Knowledge Structure View**: a governed architectural projection in its own right, whose content is determined by what AD-1 allocates rather than by what a C4 level conventionally contains.

**Exception Protocol, invoked per the commission.** *Missing information:* internal components of AC-1 and AC-2. *Governing architectural gap:* AD-1 §7 allocates **responsibilities** to both containers and derives **no internal components**; the governed strategic model contains no evidence of internal structure, and AD-1's own boundary forbids tactical decomposition. *Action:* **a C4 Level 3 component view is not supplied at all** — nested boxes would assert internal components that do not exist. What follows is a different view with a different name, and every box in it is a *responsibility*, not a component.

```
 AC-1  KNOWLEDGE ASSESSMENT — responsibilities allocated by AD-1 §7
 ┌─────────────────────────────────────────────────────────────────┐
 │ [R] record evidence entries carrying an epistemic class          │
 │ [R] record evidenced defects, scoped to their run by design      │
 │ [R] evaluate recorded evidence against inbound criteria          │
 │ [R] produce a measured magnitude as a DERIVED OBSERVATION,       │
 │     never as a **Verdict**                                             │
 │ [R] hold the categorical judgment vocabulary; emit judgments in it│
 │ [R] derive adherence and sufficiency as sibling assessments over  │
 │     the recorded substrate — requiring NO identity of their own   │
 │ [⚠] whole-system assessment of the enclosed capability:           │
 │     SPECIFIED, NOT REALIZED — nothing performs it, no role owns it│
 │ [✕] NOT allocated here: authoring criteria · issuing on its own    │
 │     initiative · any authority act                                │
 │ [✕] NOT allocated anywhere: handling of the three contested       │
 │     concepts (their membership is deliberately unassigned)         │
 └─────────────────────────────────────────────────────────────────┘

 AC-2  KNOWLEDGE PROJECTION — responsibilities allocated by AD-1 §7
 ┌─────────────────────────────────────────────────────────────────┐
 │ [R] render governed knowledge into consumable forms               │
 │ [R] regenerate every projection from its sources; never hold an    │
 │     edit that disagrees with a source                             │
 │ [R] carry no independent semantic identity for any projection      │
 │ [R] hold no authority; be citable as authority by nothing          │
 │ [⚠] inherited liability: one member kind carries representational- │
 │     only identity, in tension with the identity principle, INSIDE  │
 │     an accepted boundary — carried, not repaired                   │
 │ [★] correctness is structurally checkable: if any projection       │
 │     cannot be regenerated from its sources, or is cited as         │
 │     authority anywhere, the container is violated                  │
 └─────────────────────────────────────────────────────────────────┘

 [R] responsibility (NOT a component)   [⚠] recorded gap   [✕] explicit exclusion
 AR-1 and AR-2 are NOT decomposed at this level — nor at any level (DP-3).
```

**Derived:** AC-2's responsibility set is defined almost entirely by what it must **not** acquire — identity, authority, or a return path — which is why its correctness is checkable structurally rather than behaviorally.

---

## 6A. Synchronization with the amended AD-1 *(CCP-1 Package C, executed 2026-07-30; precondition C-18 recorded PASS)*

**C-14 (per AIA-F2 — consequent of C-01): AR-1's placement is annotated so the views assert no more than AD-1 does.** In every view above, **AR-1 (the Normative Governance region) is drawn inside the PKS boundary under OQ-PKS-7's one-corpus reading, which remains open and ARB-owned.** Under the three-corpora reading **part of AR-1's content falls outside this architecture.** *The views inherit this contingency from AD-1 and add nothing to it.*

**C-10 (per VR-1): the three out-of-input-set labels are routed through AD-1's traceability matrix**, which already names **M6/M8** as the origins of the elements they label — rather than widening this document's input declaration. *(ERV-F3 required the reword rather than the widening: a view may not enlarge its own input set to legitimize a label.)*

**C-12 (per VR-3, corrected 2026-07-30 per PD-F2): provenance of the AR-negation list** — *"no component may be defined over AR-1 or AR-2"* **appears in the C4-1 commission text AND is derived at AD-1 §6.2** (*"Why no component may be defined over AR-1 or AR-2 (Derived)"*). *(The earlier note read "…not in AD-1's derivations", which Package D verified to be false: AD-1 does derive it. Both sources are real, and the view therefore asserts nothing AD-1 does not — which is what exit criterion X-6 requires.)*

## 6B. Package C — superseded wording, retained as history *(added 2026-07-30 per the PD-F1 record-completion)*

**Same basis as AD-1 §11A: CI-6 requires that originals not be rewritten to look correct.** Package C replaced wording at one point.

> **⚠️ Provenance disclosed: this artifact is not under version control, so the text below is reconstructed from the replacement operation that applied C-11, not recovered from a committed baseline.**

| Item | Superseded wording |
|---|---|
| **C-11** (Knowledge Structure View) | *"never as a **judgment**"* — replaced by *"never as a **Verdict**"*, restoring the governed term against VR-2's prose-smoothing |

**Items C-10, C-12, C-13 and C-14 added annotations rather than replacing statements**; C-15 is a history row. No superseded wording exists for them and none is claimed.

---

## 7. Diagram Traceability

*Every element and every arrow, with provenance. Nothing appears in a view without a row here (DP-5).*

### 7.1 Elements

| Element | AD-1 section | Strategic concept | Strategic artifact |
|---|---|---|---|
| PKS system boundary | §6 | The domain as scoped by Phase II | M8 §3 |
| **AC-1** logical container | §6, §7, §8 (SB-1) | CBC-1 Knowledge Assessment (accepted BC) | M6 §7.4/§8; Authority Disposition §2.1 |
| **AC-2** logical container | §6, §7, §8 (SB-2) | CBC-2 Knowledge Projection (accepted BC) | M6 §7.4/§8; Authority Disposition §2.2 |
| **XD-1** external domain | §6, §8 (IB-1) | CBC-4 Work Management (accepted adjacent) | Authority Disposition §2.4 |
| **AR-1** undefined region | §6.2 | CBC-3 (candidate seam) | M6 §7.5/§14; Disposition §7; MCR-2 |
| **AR-2** undefined region | §6.2 | The unpartitioned expressed-knowledge core | M6 §7.5; Consolidation §2.1; T-16 |
| AC-1's unrealized responsibility (⚠) | §7 | Conformance as Core-while-Absent | M3/M5; T-17; OQ-PKS-3 |
| AC-1's unallocated concepts (✕) | §7 | Three contested memberships | M6 §8 |
| AC-2's inherited liability (⚠) | §7 | Representational-only identity for one member kind | M4 §1.3, Amendment 3 |

### 7.2 Arrows

| # | Arrow | Content rendered | AD-1 basis | Strategic basis |
|---|---|---|---|---|
| **A1** | AR-1 → AC-1 | criteria, **read-only** | AP-7, DR-3, DR-4; §8 SB-1 inbound | R-1 as disposed (pattern withdrawn, dependency retained) |
| **A2** | AC-1 → AR-1 | judgments in the **closed verdict vocabulary only** | AP-1, AP-8, DR-8; §10 | R-2 (Customer/Supplier over a Published Language, validated) |
| **A3** | AR-1 → AC-1 | **issuance trigger** — AC-1 cannot self-issue | AP-1; §7 constraint 1 | *"issued by a gate or review, never by the author"* (M0 G-15) |
| **A4** | AC-1 → AC-2 | renders from; **one-way, no return path** | AP-2, AP-9, DR-1; §10 | R-3 (Conformist) + its constraint as disposed (VF-2) |
| **A5** | AR-1 → AC-2 | renders from; **one-way** | AP-2, AP-9, DR-1 | R-3 |
| **A6** | AR-2 → AC-2 | renders from; **one-way** | AP-2, AP-9, DR-1 | R-3 |
| **A7** | AR-2 → AC-1 | the knowledge being assessed | DR-3; §7 | CBC-1 purpose (M6 §7.4) |
| **A8** | XD-1 → PKS | knowledge obligations (the DoD's five knowledge boxes); **one-way inward**; translation unassigned | AP-10, DR-5; §10 IB-1 | R-4 as disposed (pattern withdrawn, dependency retained); U-2 |
| **✕** | AC-2 ↔ XD-1 | rendered as an **explicit non-relationship**, not an arrow | §10 | R-5 (Separate Ways, validated) |

**Derived:** eight arrows, and **not one carries a mechanism label** — because none is derivable (AD-1 §9.2). **Derived:** no arrow leaves AC-2, enforcing DR-1 visually as well as normatively.

---

## 8. Known Omissions (intentional — recorded, not drawn)

| # | Not rendered | Why | Where it belongs |
|---|---|---|---|
| **KO-1** | **Actors** at Level 1 | AD-1 derives none. More strongly: the governed model records **absent roles** — two ownership vacuums, and no named owner for the capability AC-1 encloses (OQ-PKS-3, unresolved). Drawing actors would invent roles the corpus records as missing | Authority (an ownership decision), not a diagram |
| **KO-2** | **Any mechanism** on any arrow — synchronicity, messaging, events, request/response, orchestration, choreography, execution order, transport, delivery guarantees | The governed model contains **no timing, coupling, delivery, or ordering evidence at all** (AD-1 §9.2, Q-1) | AD-1 Q-1: an Authority-commissioned discovery act or an explicitly recorded architectural decision — **not** literature |
| **KO-3** | **A conventional Level 3 component view** | AD-1 allocates responsibilities but derives no internal components; nested boxes would assert structure that does not exist. The **Knowledge Structure View** (§6) is supplied instead (§6) | Future architectural work, only if evidence of internal structure ever exists |
| **KO-4** | **Level 4 (code view)** | Not derivable, and tactical DDD plus implementation are out of scope for the entire Phase II.B/II.C chain | Out of Scope |
| **KO-5** | **Any interior of AR-1 or AR-2** | No governed boundary or internal structure exists; decomposing either would grant an encapsulation the governance withheld | AD-1 Q-2 (Authority); for AR-1 specifically, an MCR-3 re-entry collection |
| **KO-6** | **Any interior of XD-1** | Outside PKS design authority (disposed adjacent) | The adjacent domain's own owners |
| **KO-7** | **Data stores, deployment nodes, runtime topology, protocols, technologies** | AD-1 is deployment-neutral by construction; none is derived | Out of Scope |
| **KO-8** | **A home for the U-2 translation obligation** | Recorded as unassigned; assigning it visually would decide it | AD-1 Q-3 (Authority) |
| **KO-9** | **A home for the three contested concepts** | Their membership is deliberately unassigned by disposition | AD-1 Q-4 (Authority) |
| **KO-10** *(C-13, per VR-4)* | **DR-2's intentional non-rendering** | DR-2 is a dependency rule with no diagrammatic counterpart; omitting it is deliberate, not an oversight | AD-1 §10A |

**Derived — the pattern across all TEN omissions (KO-1..KO-10):** every omission traces to something the *governed model* leaves open, and none to a limitation of the diagramming. **Assembly:** this is the specific failure mode the commission was written to prevent — completing a design by drawing it.

---

## 9. Validation Results

| Gate | Result | Evidence |
|---|---|---|
| **Architectural Fidelity** — nothing added, nothing removed | ✅ | Every element and arrow appears in §7 with provenance; AD-1's full element set (two containers, one external domain, two regions) and all eight dependencies are rendered; no element, arrow, or annotation exists without an AD-1 basis |
| **Semantic Fidelity** — meaning preserved | ✅ | Responsibility (§6), ownership (A1's read-only; A2's vocabulary limit), dependency direction (all eight arrows), dependency asymmetry (A4/A5/A6 one-way; A8 inward-only; AC-2 as sink), boundedness (system boundary, external domain), and undefined regions (AR-1, AR-2) are all preserved. **DP-6 additionally guards a semantic that C4 convention would otherwise import** — the container notion's runtime connotation is explicitly disclaimed |
| **Governance Fidelity** — frozen decisions unchanged | ✅ | No modification to SDM v1, EOP v1, M0–M8, DAR-1, the Checkpoint, RET-1, or AD-1. No Surfacing Register item resolved; no PMR admitted; no certification statement made |
| **Knowledge Fidelity** — representation has not become interpretation | ✅ | Three requested items were **not derivable** and were recorded rather than supplied (actors · mechanism · a component view). The Exception Protocol was invoked once, in place of a plausible rendering. No statement is classified Observed |
| **Boundary Fidelity** — undefined remains undefined | ✅ | AR-1 and AR-2 appear only as *architecturally undefined regions*, labeled with what they are **not** (container · service · bounded context · component · data store), and are decomposed at no level. Zero tactical, API, deployment, or technology content anywhere |

**Derived — one self-referential check, recorded because it applies:** AP-2 holds that *nothing may cite a view as authority*. **These views are subject to that rule.** On any conflict between a diagram and AD-1, AD-1 governs and the diagram is corrected — the header states it, and DP-7 makes it a diagram principle rather than a footnote.

---

## 10. Inputs to Promotion *(as recorded at issuance — see the currency note)*

> **⚠️ CURRENCY NOTE (change-control item C4R-3, 2026-07-30): C4-1 was PROMOTED on 2026-07-30.** The heading and the line below say promotion is not commissioned; **both were true when written and are retained, date-marked rather than deleted** — *a record is amended for currency, never rewritten for outcome.* **These items were the inputs to a promotion that has since occurred.**

**Recommendation (promotion was not commissioned at the time of writing):**

1. **Three views** (§§4–6) — System Context · Container · **Knowledge Structure View** — with complete provenance (§7), rendering the architecture at the precision the architecture actually has.
2. **Seven diagram principles** (DP-1..DP-7). **Recommendation:** DP-1 (*visual precision ≤ architectural precision*), DP-2 (*direction does not imply mechanism*), and DP-4 (*silence over invention*) are candidates for promotion **beyond this program** — they are general knowledge-engineering constraints on visual representation, demonstrated here rather than asserted. They are **not** methodology changes and must not be adopted as such: the PMR → MCA → CDR path governs any such elevation.
3. **Ten recorded omissions** (KO-1..KO-10) *(count corrected per C4R-2)*, each routing to Authority or Out of Scope, none to further diagramming. **Recommendation:** KO-1 is the most consequential — an architecture that cannot show who operates it is a finding about the domain's ownership vacuums, not a diagramming deficiency, and promotion materials should carry it as such.
4. **The standing qualifier, unchanged and carried:** every element rests on a strategic model graded at most Medium-High on a shared-corpus/shared-lineage basis. **A view cannot be more certain than the architecture it renders, which cannot be more certain than the model it derives from.**
5. **The subordination rule** (DP-7): these views are representations governed by AD-1, and are themselves inhabitants of the very class the strategic model describes — derived artifacts carrying no independent authority. **Assembly:** the Phase II.C output is an instance of CBC-2's own membership rule, which is a coherence check the record set passes.

---

*Traceability: executes the Phase II.C C4 Architecture Views Commission C4-1 (PA, 2026-07-28) · renders AD-1's logical architecture as C4 Level 1 and Level 2, plus a Responsibility Allocation View substituting for Level 3 · inputs limited to AD-1 (§§5–12, incl. AP-1..AP-10 and DR-1..DR-8) and the strategic relationships as disposed by DAR-1 · every element and all eight arrows carry provenance (§7) · three requested items recorded as non-derivable rather than supplied — actors (KO-1), mechanism (KO-2), a component decomposition (KO-3) · Exception Protocol invoked once (§6) · nine intentional omissions recorded (§8) · all five fidelity gates verified (§9) · no frozen artifact modified, no Surfacing Register item resolved, no PMR admitted, zero tactical/API/deployment/technology content · no statement classified Observed. **STOP.***

> **Phase II.C C4 Architecture Views complete. Three views render the architecture at the precision the architecture has: two logical containers, one external domain, and two regions that remain undefined at every level. Actors, communication mechanism, and internal component structure were not derivable and are recorded rather than drawn. No architecture was invented and no governed artifact was modified. The next act is per-artifact promotion, separately commissioned.**
