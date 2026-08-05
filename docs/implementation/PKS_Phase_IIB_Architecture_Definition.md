# PKS Phase II.B — Architecture Definition (AD-1)

| | |
|---|---|
| **Kind** | **Architecture Definition** — a logical architecture **derived** from the completed, governed Strategic Model. It answers *how should the governed concepts collaborate*; it never answers *what the strategic model should have been*. |
| **Authority** | Generated — never authoritative without human review. **No new strategic knowledge is introduced.** Every statement carries an epistemic class; **no statement is classified Observed, because this commission performs no discovery.** |
| **Status** | **🏁 PROMOTED — the governing logical architecture of the PKS (Authority act, 2026-07-30).** Promotion followed the full chain with each step separately owned: *review complete → findings dispositioned (AD-R1/R2/R3 ACCEPT) → remediation verified → promotion-ready (Package D exit check 17/17, §8 fully satisfied) → **Authority promotion decision** → promoted baseline*. **What promotion does NOT change, stated because promotion is where such things get read into an artifact:** the confidence ceiling (**at most Medium-High, one corpus, one lineage — never citable as independently confirmed**) · **OQ-PKS-7 remains OPEN and ARB-owned**, and AR-1's placement remains **contingent** on it (§6.1) · **AP-3's constitutional status remains UNDETERMINED** · **DR-7's acyclicity is a description, not a constraint on future elements** · the six §12 questions remain open · **Q-AD-1 remains open** · the architecture remains **deliberately PARTIAL**: AR-1 and AR-2 are still architecturally undefined and no component may be defined over them. ***Promotion makes this architecture governing; it does not make it complete, certain, or closed.*** |
| **Status at execution (retained as history)** | **EXECUTED — a deliberately PARTIAL architecture. STOP.** No Phase II.C (C4 views), no implementation planning, no tactical DDD, no promotion, no methodology evolution, no literature benchmarking, no MCA/CDR performed. |
| **Commission** | Phase II.B Architecture Definition Commission AD-1 (PA, 2026-07-28). |
| **Knowledge base (complete; no additional discovery authorized)** | SDM v1 · EOP v1 · Configuration Control · MCR-1..6 · M0–M8 · DAR-1 · M7/M8 Checkpoint · RET-1. **(C-07, per KBI-F3: RET-1 appears in this input list and is cited at §12's derivation, but carries no row in the traceability matrix — it informed the commission's framing without supplying a traced derivation. Recorded rather than back-filled: adding a matrix row would assert a derivation that was not made.)** |
| **External knowledge** | **None imported.** No literature consulted, no architectural style adopted by familiarity, no DDD pattern used beyond those already governed. Where the governed model cannot answer, the Exception Protocol is invoked (§12) rather than filled. |
| **Knowledge sources hierarchy honored** | Repository evidence = primary architectural evidence (highest) · governed decisions = binding baseline · external literature = advisory, **commissioned only** (none consulted) · **engineering experience = interpretation, never authoritative by itself** — the last is why §9.2 records a limit instead of supplying a familiar answer. |
| **Deployment neutrality** | **Derived, and a positive property rather than an omission:** the architecture is deployment-neutral by construction. Every element is defined by responsibility, boundary, and dependency; **no statement below constrains or presumes any infrastructure, runtime, protocol, or technology.** |
| **Placement** | `docs/implementation/`, opening the Phase II.B record set. |
| **Disposition History** | **2026-07-30: PROMOTED by Authority act** — AD-1 is the governing logical architecture; the promotion decision is recorded at `PKS_Phase_II_Execution_Record.md`. · **2026-07-30: AD-R1/AD-R2/AD-R3 disposed ACCEPT (Knowledge Contract Review, architecture-definition emphasis) and applied.** **AD-R1** — DR-7's derived clause retained verbatim; its forward-reaching clause **restated as a reopening trigger and marked NOT DERIVED**, since M7 §8 observes the modelled graph rather than constraining future ones *(the C-04/AP-3 pattern)*. **AD-R2** — four comparative rankings replaced by the facts supporting them. **AD-R3** — §13's two imperatives attributed to AP-2 and DP-7. **Q-AD-1 remains open** (DR-5's *"inward only"* against M7's *"↔"* for R-4). **No component, region, integration, boundary, principle substance, or traced warrant was altered.** · **2026-07-30: AMENDED ONCE under CCP-1 Package A+B, per the AFV-F4 disposition (ACCEPT) — items C-01..C-09 applied in a single amendment per Ordering Rule 2. AR-1's placement qualified with OQ-PKS-7's contingency and its label corrected to the governed name; AFV-F1's issuance constraint restated as *placement-not-determined* (AP-1 + AC-1) with the question recorded as Q-7; DR-1's evidence limb given its L4-8 derivation; AP-3's status marked UNDETERMINED; identifier namespaces declared; RET-1's matrix absence reconciled by record. No element was added, removed, split, or merged — the bijective mapping AFV-1 §10A verified is preserved.** · **2026-07-30: PD-F1 record-completion applied — §11A added, retaining the superseded wordings of C-01/C-05, C-02 (×2), C-03 and C-04 as history, satisfying CI-6. No content statement changed; the amendment became auditable.** · 2026-07-28: executed under AD-1. · 2026-07-28: **additively amended** after reading the commission's variant text (`docs/architecture/brainstorming/20260729_1640_Phase II.B Architecture Definition.md`), which specifies Dependency Rules as a first-class deliverable and requires the Exception Protocol to state *why repository evidence is insufficient*. Added: the knowledge-sources hierarchy and deployment-neutrality rows above · **§10A Dependency Rules (DR-1..DR-8)** · an insufficiency column in §12 · a Ubiquitous Language traceability row in §11. **Existing section numbers are preserved** so all prior cross-references remain valid; no derivation, principle, or component changed. |

**Epistemic classes used (commission-mandated):** **Derived** — an architectural consequence directly inferred from governed knowledge · **Assembly** — integrated presentation of governed knowledge · **Recommendation** — future architectural work · **Out of Scope** — an explicit boundary.

---

## 1. Executive Summary

**Derived: the architecture that follows from this strategic model is partial by construction — two components definable, two regions architecturally undefined, one domain external.** Two logical components can be defined with real boundaries. One domain is external. **Two regions are depended upon by everything and cannot be given a component at all** — because a candidate seam and an unpartitioned region are not bounded contexts, and a logical component requires a governed boundary to enclose.

| Element in the strategic model | Architectural consequence |
|---|---|
| **CBC-1 Knowledge Assessment** (accepted BC) | **Logical component AC-1** — definable, with a boundary and allocated responsibilities |
| **CBC-2 Knowledge Projection** (accepted BC) | **Logical component AC-2** — definable, and constrained by DR-1, DR-2, AP-2 and AP-9 |
| **CBC-4 Work Management** (accepted adjacent) | **External domain XD-1** — an integration boundary, not a component of this architecture |
| **CBC-3 Normative Governance** (candidate seam) | **AR-1: the Normative Governance region — an architecturally undefined region.** Its contents are depended upon by both components; no boundary exists to encapsulate. **No component may be defined here.** **⚠️ PLACEMENT IS CONTINGENT (C-01, per the AFV-F4 disposition):** AR-1 is shown wholly inside the PKS boundary **under OQ-PKS-7's one-corpus reading, which remains open and ARB-owned.** M6 §9's impact statement: *"If one corpus: `binds` is an intra-domain relationship and the Norm Custody reading gains a home. If three: `binds` is an inter-domain contract with the Engineering Governance domain, and **part of the demoted seam's content is outside PKS**."* **This placement asserts no answer to OQ-PKS-7; under the three-corpora reading part of AR-1's content falls outside this architecture.** |
| **Expressed-knowledge core** (unpartitioned region) | **AR-2: an architecturally undefined region.** Everything the components act upon lives here; the model provides no internal structure |

**Derived — the load-bearing consequence:** the architecture **inherits the strategic model's openings rather than closing them.** Filling AR-1 or AR-2 with components would require boundaries the governance has explicitly declined to draw — the elegant-partition error at one remove.

**Derived — a limit, stated as a finding:** the commission asks for a communication model including synchronous and asynchronous communication. **The governed model contains no timing, coupling, or delivery evidence whatsoever.** Dependency *direction* is strongly evidenced; dependency *mechanism* is not evidenced at all. Synchronicity is therefore **not derivable** and is recorded under the Exception Protocol (§12, Q-1) rather than supplied from familiar practice — which the commission forbids and which would be indistinguishable from invention.

**Derived:** ten architectural principles (AP-1..AP-10) are available by derivation. None is invented; each restates a governed rule as its architectural consequence.

**Derived — one component carries a responsibility nothing realizes:** whole-system conformance assessment is **Absent** in the corpus (T-17). AC-1's boundary encloses it, so the architecture is in that respect **a specification for a capability no mechanism performs.** Recorded, not resolved.

---

## 2. Commission

**Authorized:** derive logical components · allocate responsibilities · derive logical service boundaries · derive the communication model · derive the integration model · derive architectural principles already implied by the governed model.

**Boundary honored throughout:** this document performs *derivation*. Where derivation was impossible, the Exception Protocol was invoked (§12) and unblocked work continued.

## 3. Scope

**Out of Scope (each stated explicitly so its absence is not read as an omission):** modifying bounded contexts · redefining or renaming ubiquitous language · changing relationship classifications · resolving Surfacing Register items · admitting PMRs · modifying SDM/EOP · tactical DDD of any kind · aggregates · entities · value objects · repositories · APIs · schemas · deployment architecture · technology choices.

**Verified absent from this document:** no aggregate, entity, value object, repository, API, schema, deployment unit, or technology name appears anywhere below.

## 4. Inputs

**Assembly:** M6 §7.4 (context purposes and UL statements) · M6 §8 (membership with evidence) · M6 §7.5 (the seam and the unpartitioned region) · M7 §5–§8 **as disposed by DAR-1** (relationships, dependency structure, ownership, authority boundaries) · DAR-1 §9 (the post-disposition pattern layer) · M3 (conformance as a derived assessment) · M4 (identity modes; semantic ≠ representational identity; derived artifacts carry no independent identity) · M8 (the assembled model and its citation rule) · Checkpoint §7 (uncertainty classifications) · RET-1 §6 (which concepts required governance support).

---

## 5. Architectural Principles (derived — none invented)

| # | Principle | Architectural consequence | Traced to |
|---|---|---|---|
| **AP-1** | **Knowledge feeds authority; it never holds authority** | **Derived:** no assessment element may perform an authority act. A verdict is an *output*, never a decision. **The act of *issuing* is not performed by the assessment; WHERE it is performed is not determined by the governed model** *(C-02, per AFV-F1: the governed rule separates the **issuer** (a gate or review) from the **author** — these are **roles, not contexts**, and the model nowhere places gates or reviews on either side of a context boundary. The role separation is governed; the placement is not)* | P-8 (item 1 Q0) · M0 G-15 · CBC-1 UL (M6 §7.4) |
| **AP-2** | **Projections are regenerable and non-authoritative** | **Derived:** projection elements hold no source of truth; every projection must be reproducible from its sources; **no element may consume a projection as input to a decision** | "nothing may cite a view as authority" · "recomputed from sources, never hand-edited to disagree" · "the artifact wins and the diagram is corrected" (M6 §8) |
| **AP-3** | **Revision is forward-only** *(status: **UNDETERMINED** — binding as an architectural principle; its constitutional standing is not established)* | **Derived:** logical state changes are supersession or append; **no in-place revocation exists** anywhere in the architecture. *(C-04, per AFV-F3: L1-7 **observes** forward-only revocation across the corpus, and **M6 §3.1's (c) column reads "could be a constitutional property or an unexamined habit"** — the model explicitly leaves the status undetermined. Qualified here in the same pattern AP-4 already uses.)* | L1-7 (item 1 Q7.1: "no in-place revocation exists anywhere") — **an observation, not a constitutional rule** |
| **AP-4** | **Identity is per-kind and register-scoped for assigned-ordinal identity** | **Derived:** identity assignment is a per-kind concern; **no global identifier scheme** may be introduced; collisions are prevented by register discipline, not by a central authority | M4 §1.1–§1.2 (three identity modes; the register as namespace unit) |
| **AP-5** | **Semantic identity ≠ representational identity** | **Derived:** no element may derive semantic identity from a carrier (name, path, ordinal). Carrier-linked identity is permissible only where the carrier is constitutive *by design* | M4 §1.3 · Amendment 3 (by-design vs by-neglect) |
| **AP-6** | **Derived assessments carry no identity of their own** | **Derived:** conformance and completeness require no independent identity mechanism; they ride the identity of the observations and verdicts they are derived from | M3 Step 6 · M4 Part 4 |
| **AP-7** | **Criteria are used here, owned elsewhere** | **Derived:** the assessment boundary holds **no norm authority**. Criteria enter as inbound references; the architecture provides no place to author them | CBC-1 UL (M6 §7.4) · M7 R-1 (post-DAR-1) |
| **AP-8** | **The verdict vocabulary is the interchange language** | **Derived:** assessment outcomes cross a boundary only in the closed vocabulary (PASS · PASS AFTER CORRECTION · WARN · FAIL · INCONCLUSIVE · EMERGENT · CERTIFIED). Nothing richer crosses | M7 R-2 (Published Language, validated) · item 1 K-15 |
| **AP-9** | **The path toward projection is one-way by construction** | **Derived:** the architecture must make a return path from projection *structurally impossible*, not merely discouraged | M7 R-3 constraint, as disposed by DAR-1 (VF-2) |
| **AP-10** | **Work management is outside** | **Derived:** an integration boundary, not an internal component. The interchange terms are the DoD's five knowledge obligations; the translation obligation across the boundary is **unassigned** | CBC-4 disposition (adjacent) · M7 R-4 · U-2 |

**Identifier namespaces (C-06, per KBI-F2 — a declaration only; it characterizes nothing):** `AC-` architecture component · `AR-` architecturally undefined region · `SB-` structural boundary · `IB-` integration boundary · `AP-` architectural principle · `DR-` dependency rule. **These prefixes are local to this document and assert nothing about any element's placement, status, or authority.**

**Derived:** AP-1, AP-2, AP-3, and AP-9 are *prohibitive* — they constrain what the architecture may not do. **Assembly:** that is the shape of the governed model, in which the strongest evidence was always a rule stating what may not happen (RET-1 §5.2).

---

## 6. Logical Architecture

**Derived structure.** *Legend: solid box = definable logical component · dotted box = external domain · wavy = architecturally undefined region (no governed boundary to enclose).*

```
 ································································
 ·  XD-1  WORK MANAGEMENT  (external domain — outside PKS)      ·
 ································································
        ▲  IB-1 integration boundary (AP-10)
        │  interchange terms: the DoD's five knowledge obligations
        │  ⚠ translation obligation UNASSIGNED (U-2)
════════╪═════════════ PKS ARCHITECTURE BOUNDARY ══════════════════
        │
 ~~~~~~~╪~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 ~  AR-1  NORMATIVE REGION  (undefined — candidate seam)         ~
 ~  contents depended upon: norms · authorization acts           ~
 ~  NO component may be defined here (§6.2)                      ~
 ~~~~~╪~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~╪~~~~~~~
       │ criteria in (AP-7)                    verdicts out │ (AP-1/AP-8)
       ▼                                                    │
 ┌─────────────────────────────────────────────────────────┴────┐
 │  AC-1  KNOWLEDGE ASSESSMENT                                  │
 │  record evidence · evaluate against criteria · issue verdicts │
 │  holds NO authority (AP-1) · holds NO criteria (AP-7)         │
 │  ⚠ one enclosed responsibility is unrealized (T-17)           │
 └──────────────────────────┬───────────────────────────────────┘
                            │  IB-2 (one-way, AP-9)
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~│~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 ~  AR-2  EXPRESSED-KNOWLEDGE REGION  (undefined — unpartitioned)│
 ~  Decision · Term · Model element · Contract                  ~│
 ~  what the components act upon; no internal structure given    ~│
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~│~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                            │  IB-2 (one-way, AP-9)
                            ▼
 ┌──────────────────────────────────────────────────────────────┐
 │  AC-2  KNOWLEDGE PROJECTION                                  │
 │  render · serialize · index · regenerate from sources         │
 │  holds NO source of truth · NO authority · NO return path      │
 │  (AP-2, AP-9) · ⚠ standing identity liability (guide steps)    │
 └──────────────────────────────────────────────────────────────┘
        ✕  no integration with XD-1 (M7 R-5, Separate Ways)
```

### 6.1 Why exactly two components (Derived)

A logical component requires a **governed boundary** to enclose. Exactly two elements of the strategic model have one: CBC-1 and CBC-2, both accepted bounded contexts with membership, purpose, and UL statements. CBC-4 has a boundary but is disposed **outside**; the seam and the core have none.

### 6.2 Why no component may be defined over AR-1 or AR-2 (Derived)

**AR-1 (the candidate seam):** CBC-3 is a *candidate seam* — an SDM state formally distinct from a bounded context (MCR-2), disposed as evidence-decided at Low-Medium with bar-minimum convergence and a single-source stability failure. Defining a component over it would assert an encapsulation the governance declined to grant, and would also silently prejudge the preserved Norm-Custody ∥ Authorization-Acts partition, whose only legitimate route is an MCR-3 re-entry collection.

**AR-2 (the expressed-knowledge core):** deliberately left unpartitioned because no evidence partitions its interior; T-16 records that it may in fact be several contexts. A component drawn over it would be a boundary by preference — exactly the error the discovery refused.

**Derived consequence for both:** the architecture records these as **regions with dependency contracts but no encapsulation**. The components depend on their contents (criteria from AR-1; the knowledge acted upon in AR-2) while the regions themselves stay architecturally open.

---

## 7. Component Responsibilities

### AC-1 Knowledge Assessment

| Responsibility | Derived from |
|---|---|
| Record evidence entries carrying an epistemic class | CBC-1 UL: *Observation* |
| Record evidenced defects, scoped to their run by design | CBC-1 UL: *Finding*; M4 mode 2 |
| Evaluate recorded evidence against inbound criteria | CBC-1 purpose (M6 §7.4) |
| Produce a measured magnitude as a **derived observation, never a verdict** | F-BCP-2 (M3 amendment) |
| Hold the categorical judgment vocabulary and emit judgments in it | CBC-1 UL: *Verdict*; AP-8 |
| Derive conformance (adherence) and completeness (sufficiency) as sibling derived assessments over the recorded substrate | M3 Recommendation B; M2 §5 |

**Derived — three constraints on the component, each from a governed rule:**
1. **It cannot issue on its own initiative — and the LOCATION of the issuing trigger is NOT DETERMINED.** *"Issued by a gate or review, never by the author"* (M0 G-15) separates the **issuer role** from the **author role**. *(C-02, per AFV-F1: the earlier form read that G-15 "places the issuing trigger **outside** the boundary — in AR-1's authorization acts." **That is an interpretation presented as a derivation:** author and gate-or-review are **roles**, and the model places neither on either side of a context boundary. What is governed is that the assessment does not self-issue.)* The component must therefore accept an external issuance trigger; it may not self-issue.
2. **It cannot author its criteria** (AP-7). There is no place inside AC-1 to create or amend a norm.
3. **It requires no identity mechanism for its derived assessments** (AP-6).

**Derived — recorded as a specification/realization gap:** whole-system conformance assessment is **Absent** (T-17; M3/M5). AC-1's boundary encloses a responsibility that **no mechanism currently performs, and no role currently owns** (OQ-PKS-3, unresolved). The architecture specifies it because the strategic model places it here; it does not thereby realize it. **Recommendation:** any future realization decision belongs to the Authority, since the ownership vacuum is an open governance question, not an architectural one.

**Derived — three concepts have no allocated handling:** Risk, Question, and Exception record are recorded as **contested and unassigned** at CBC-1 (M6 §8). The architecture therefore allocates their handling **nowhere**, and may not allocate it by default. Recorded, not resolved.

### AC-2 Knowledge Projection

| Responsibility | Derived from |
|---|---|
| Render governed knowledge into consumable forms (teaching, navigation, serialization, visualization) | CBC-2 purpose (M6 §7.4) |
| Regenerate every projection from its sources; never hold an edit that disagrees with a source | AP-2 |
| Carry no independent semantic identity for any projected artifact | M4 §1.4 (L4-8) |
| Hold no authority; be citable as authority by nothing | AP-2, AP-9 |

**Derived — the strongest constraint set in the architecture.** AC-2 is the only component whose *entire* responsibility set is defined by what it must not acquire: identity, authority, or a return path. **Derived consequence:** its correctness is verifiable structurally — if any projection cannot be regenerated from its sources, or is cited as authority anywhere, the component is violated.

**Derived — one standing liability inherited, not introduced:** guide steps carry representational-only identity (M4 mode 3\*, carrier-linkage *by neglect*), which is a known conflict with AP-5 **inside** an accepted boundary. The architecture records it as an inherited liability of an accepted member; it does not repair it (repair would alter the strategic model).

---

## 8. Logical Service Boundaries

**Definition used, to keep this within scope:** a *logical service boundary* is an encapsulation unit plus a statement of what crosses it. **Out of Scope:** APIs, protocols, endpoints, deployment units, technologies — none appears below.

| Boundary | Encapsulates | What crosses | Derived from |
|---|---|---|---|
| **SB-1 (AC-1)** | Evidence records, findings, judgment production, derived assessments | **In:** criteria references (AP-7) · an external issuance trigger (AP-1) · the knowledge being assessed (from AR-2). **Out:** judgments in the closed verdict vocabulary only (AP-8) | CBC-1 membership + purpose; M7 R-1, R-2 |
| **SB-2 (AC-2)** | Rendering and regeneration | **In:** governed knowledge from any source (AC-1, AR-1, AR-2). **Out:** nothing that may be consumed as authority or evidence (AP-2, AP-9) | CBC-2 membership + purpose; M7 R-3 + its constraint |
| **IB-1 (external)** | *(nothing — XD-1's interior is outside PKS design authority)* | The DoD's five knowledge obligations as interchange terms; **translation unassigned (U-2)** | CBC-4 adjacency; M7 R-4 |
| **AR-1 / AR-2** | **No encapsulation available** (§6.2) | Dependency contracts only: criteria out of AR-1; assessed/rendered knowledge out of AR-2 | M6 §7.5; MCR-2 |

**Derived:** SB-1's outbound surface is **narrower than its internal vocabulary.** Internally AC-1 speaks of observations, findings, degrees, criteria, conformance, and completeness; outbound, only the verdict vocabulary crosses. **Assembly:** this is R-2's Published Language expressed as an encapsulation property.

---

## 9. Communication Model

### 9.1 What is derivable

| Aspect | Derived statement |
|---|---|
| **Dependency direction** | Fully derivable and strongly evidenced: criteria → AC-1 · judgments → AR-1's authorization acts · everything → AC-2 (one-way) · knowledge obligations → XD-1. **The graph is acyclic** (M7 §8) |
| **Directionality of projection** | One-way **by construction**, not by convention (AP-9). A return path must be structurally impossible |
| **Published interface** | Exactly one is derivable: the closed verdict vocabulary at SB-1's outbound surface (AP-8). No other published interface is evidenced anywhere in the model |
| **Initiation of judgment** | Derivable and asymmetric: **AC-1 cannot initiate its own issuance** — the trigger originates in AR-1 (AP-1). Assessment is therefore *responsive*, not self-driving |
| **Regeneration** | Derivable as *source-determined*: a projection is a function of its sources (AP-2), so it may be recomputed at any time without consulting its previous state. **Derived consequence:** AC-2 requires no coordination with AC-1 to remain correct — only access to sources |

### 9.2 What is NOT derivable — and is not supplied

**Derived (a limit, stated as a finding):** **the governed model contains no timing, coupling, delivery, or ordering evidence.** Nothing in M0–M8, DAR-1, the checkpoint, or RET-1 states or implies whether any dependency is satisfied synchronously or asynchronously, whether communication is request-driven or notification-driven, or what delivery guarantees apply.

**Therefore synchronous versus asynchronous communication is NOT derived here.** The commission asks for it; the knowledge base cannot answer it; and the two available ways to answer anyway are both forbidden — importing a familiar architectural style (external-knowledge policy) or inferring one from the model's *shape* (which would be interpretation presented as derivation, breaching the Knowledge Fidelity gate).

**Recorded under the Exception Protocol as Q-1 (§12).** All unblocked work continued: §9.1 derives everything the model *does* determine, which is direction, one-wayness, the single published interface, initiation asymmetry, and regeneration independence — a communication model without a coupling mechanism.

**Recommendation:** the missing input is **domain evidence about how the practiced system operates**, not external literature. An Exception *Research* Commission would therefore be the wrong instrument; the right one is either an Authority-commissioned discovery act or an explicitly-recorded architectural decision under configuration control. **Derived:** the distinction matters because OQ-PKS-2 (which designed system is the incumbent) is unresolved, and coupling evidence would likely come from the same source that resolves it.

---

## 10. Integration Model

| Integration | Model | Derived from |
|---|---|---|
| **AR-1 → AC-1** (criteria) | **Upstream/downstream with no coordination assumption.** AC-1 consumes criteria it cannot influence and translates them into its own vocabulary. **No coordination pattern is asserted** — DAR-1 withdrew the Customer/Supplier name because its accommodation condition is unevidenced | M7 R-1 as disposed (VF-1 accepted) |
| **AC-1 → AR-1** (judgments) | **Customer/Supplier over a Published Language.** AC-1 is the upstream supplier; the authorization acts are the downstream customer. The supplier **informs and never binds** | M7 R-2 (validated, survives DAR-1) |
| **{AC-1 · AR-1 · AR-2} → AC-2** | **Conformist, one-way**, plus the constraint that **no return path exists in the authority or evidence direction** | M7 R-3 + constraint (VF-2 accepted) |
| **{AC-1 · AR-1} ↔ XD-1** | **Cross-edge dependency; no coordination pattern asserted.** Interchange terms are the DoD's five knowledge obligations. **Translation obligation unassigned (U-2)** — and the architecture may not assign it, since XD-1's interior is outside PKS design authority | M7 R-4 as disposed (VF-3 accepted) |
| **AC-2 ↔ XD-1** | **No integration.** Separate Ways | M7 R-5 (validated) |

**Derived:** three of the five integrations carry **no coordination pattern**, because DAR-1 withdrew two pattern names and one label. **Assembly:** the integration model is consequently thinner than a conventional context map — and thinner in exactly the places where the evidence was thin (RET-1 §5.2: derived steps were the weakest link).

**Derived — independent components:** none. Every element in the architecture has at least one dependency; only the AC-2 ↔ XD-1 pair is mutually independent.

---

## 10A. Dependency Rules (derived — added by amendment)

*Normative statements of what may depend on what. Each is a consequence of a governed rule, not an architectural preference. **Derived** throughout.*

| # | Rule | Follows from |
|---|---|---|
| **DR-1** | **Nothing may depend on AC-2.** Knowledge Projection is a pure **sink**: no component, region, or external domain may consume a projection as authority or as evidence | AP-2 + AP-9 ("nothing may cite a view as authority"; the one-way constraint disposed at DAR-1). **The *evidence* limb's derivation, supplied per C-03 (AFV-F2): *a derived artifact carrying no independent semantic identity contributes no independent evidence* (L4-8).** *The governed rule covers authority only; the evidence prohibition is retained and its derivation is now stated rather than assumed.* |
| **DR-2** | **No element may substitute a projection for its source.** Where a projection and a source disagree, the source governs and the projection is corrected | AP-2 ("the artifact wins and the diagram is corrected") |
| **DR-3** | **AC-1 may depend on AR-1 (criteria, read-only) and AR-2 (the knowledge assessed), and on an external issuance trigger.** It may depend on nothing else | AP-1, AP-7; CBC-1 purpose and membership |
| **DR-4** | **Criteria dependencies are read-only.** No element inside AC-1 may author, amend, or version a criterion | AP-7 ("used here, owned elsewhere") |
| **DR-5** | **No PKS element may depend on XD-1.** The cross-edge dependency runs *inward only* — work items carry obligations toward knowledge; no knowledge kind depends on a work item | M7 R-4 asymmetry as disposed (VF-3); AP-10 |
| **DR-6** | **Dependencies on AR-1 and AR-2 are dependency *contracts*, never component dependencies.** They may not assume encapsulation, a stable interface, or any internal structure — because none is governed | §6.2; MCR-2 (candidate seam ≠ bounded context); T-16 |
| **DR-7** | **The dependency graph is acyclic** *(derived — a description of the modelled graph)*. **NOT DERIVED, and therefore stated as a reopening trigger rather than a rule** *(per AD-R1)*: **an element that would close a cycle contradicts the modelled graph and shall be treated as a trigger to reopen the dependency model** — no governed rule forbids cycles, so this document may not exclude future elements by its own authority | M7 §8 (graph acyclic **as modeled** — an observation of the current graph, not a constraint on future ones) |
| **DR-8** | **Only the closed verdict vocabulary crosses SB-1 outbound.** A dependent may not reach past that surface for AC-1's internal vocabulary (observations, findings, degrees, criteria, conformance, completeness) | AP-8; M7 R-2 (Published Language) |

**Derived — two rules with consequences worth stating explicitly:** **DR-1** makes AC-2 a *terminal* element of the architecture, which is unusual and entirely evidence-driven — a rendering layer that nothing is permitted to build upon. **DR-5** means the only cross-edge dependency runs *toward* PKS obligations, so the architecture has no outbound dependency on a domain it does not govern.

**Derived — one rule is deliberately permissive:** DR-6 allows dependence on regions that have no boundary, because the components genuinely require their contents (criteria; the knowledge acted upon). **Assembly:** this is the architectural form of the strategic model's own honesty — depending on something the model has not partitioned is permitted, provided the dependency does not pretend the partition exists.

---

## 11. Traceability Matrix

| Architectural element | Originating strategic concept | Originating governed artifact | Governing rationale |
|---|---|---|---|
| **AC-1** Knowledge Assessment | CBC-1 (accepted BC) | M6 §7.4, §8; Authority Disposition §2.1 | An accepted bounded context with membership, purpose, and UL supplies a governed boundary to encapsulate |
| **AC-2** Knowledge Projection | CBC-2 (accepted BC) | M6 §7.4, §8; Authority Disposition §2.2 | As above; membership is defined by identity/authority behavior, not by form |
| **XD-1** Work Management | CBC-4 (accepted adjacent) | Authority Disposition §2.4 | Disposed outside the domain; coupling is a relationship, not membership |
| **AR-1** Normative region (undefined) | CBC-3 (candidate seam) | M6 §7.5, §14 Amendment 1; Disposition §7; MCR-2 | A candidate seam is a formal state distinct from a bounded context; no encapsulation is granted |
| **AR-2** Expressed-knowledge region (undefined) | The unpartitioned core | M6 §7.5; Consolidation §2.1 | Deliberately unpartitioned; T-16 records it may be several contexts |
| **AP-1** Knowledge feeds authority | P-8; the Verdict issuance rule | item 1 Q0; M0 G-15 | Verbatim governed rules; CBC-1's UL restates them locally |
| **AP-2** Projections regenerable, non-authoritative | CBC-2's four defining rules | M6 §8; item 1 Q3.1/Q4/Q7.2 | Each rule is meaningless unless the projection class exists |
| **AP-3** Forward-only revision | L1-7 | item 1 Q7.1 | "No in-place revocation exists anywhere" |
| **AP-4** Per-kind, register-scoped identity | Identity modes; the register as namespace unit | M4 §1.1–§1.2 (+ Amendment 2) | Preferred explanatory model, Medium-High; collisions track register lapses |
| **AP-5** Semantic ≠ representational identity | M4's distinction; ES-004.2 | M4 §1.3, Amendment 3 | Stated as a rule in the corpus |
| **AP-6** Derived assessments carry no identity | Conformance/Completeness | M3 Step 6; M4 Part 4 | They ride Observation/Verdict identity |
| **AP-7** Criteria used here, owned elsewhere | CBC-1's UL; R-1 | M6 §7.4; M7 R-1 | Verbatim UL statement |
| **AP-8** Verdict vocabulary as interchange language | R-2's Published Language | M7 R-2; item 1 K-15 | Validated against the pattern definition |
| **AP-9** One-way toward projection | R-3's constraint | M7 R-3; DAR-1 §6 | Constitutional rule; DAR-1 replaced the borrowed pattern label with the constraint |
| **AP-10** Work management outside | CBC-4 adjacency; R-4 | Disposition §2.4; M7 R-4; DAR-1 §7 | Integration boundary with unassigned translation |
| **SB-1 / SB-2 / IB-1** | The above boundaries | M6, M7 as disposed | Encapsulation plus what crosses; no API or deployment content |
| **Communication model §9.1** | The dependency structure | M7 §8; DAR-1 §9 | Direction is evidenced; mechanism is not (§9.2) |
| **Dependency rules §10A** (DR-1..DR-8) | The relationship constraints and the region statuses | AP-1/2/7/8/9/10; M7 §8; MCR-2 | Each rule restates a governed constraint as a normative dependency statement |
| **Vocabulary used throughout** | The observational glossary and the context-local UL statements | **M0 G-1..G-17**; M2's two-tier canon (14 operational + 3 hypothetical); M6 §7.4 | Every term in this document is drawn from the governed vocabulary; **no term is coined, renamed, or extended** (terminology frozen since M6) |
| **Integration model §10** | The five relationships as disposed | M7 §5; DAR-1 §9 | Post-disposition standing used throughout |

**Nothing appears in this architecture without a row above.**

---

## 11A. Amendment 1 — superseded wordings, retained as history *(added 2026-07-30 per the PD-F1 record-completion)*

**Why this section exists.** CCP-1's configuration-integrity rule **CI-6** requires that *"amendments are additive with a history row; originals are not rewritten to look correct."* The 2026-07-30 amendment (items C-01..C-09) **replaced wording in place** at five points and recorded only a summary of what changed — so the amendment was correct but **unauditable**: a reader could not reconstruct what a statement said before it. **Package D recorded that as PD-F1 (Major) and, being prohibited from editing, specified this remedy rather than applying it.** The pattern followed here is the one already in use at **M6 §14** and **ARB Review Discipline Amendment 1**.

> **⚠️ Provenance of the text below, disclosed rather than assumed.** This artifact is **not under version control**, so the superseded wordings are **reconstructed from the replacement operations that applied the amendment**, not recovered from a committed baseline. They are faithful to what was replaced; they are **not** independently attested by a repository history. *A record-completion that concealed how it was reconstructed would repeat PD-F1 in a different form.*

**Superseded text — retained as history, NOT authoritative. The current statements above govern.**

| Item | Superseded wording |
|---|---|
| **C-01 · C-05** (§6.1, the AR-1 row) | *"**CBC-3 Normative Governance** (candidate seam) — **AR-1: an architecturally undefined region.** Its contents are depended upon by both components; no boundary exists to encapsulate. **No component may be defined here**"* — i.e. **without** the OQ-PKS-7 contingency and **without** the governed label |
| **C-02** (AP-1's second clause) | *"The act of *issuing* is triggered from outside the assessment boundary"* |
| **C-02** (AC-1's first constraint) | *"**It cannot issue on its own initiative.** \"Issued by a gate or review, never by the author\" (M0 G-15) **places the issuing trigger outside the boundary — in AR-1's authorization acts.** The component must therefore…"* |
| **C-03** (DR-1's basis cell) | *"AP-2 + AP-9 (\"nothing may cite a view as authority\"; the one-way constraint disposed at DAR-1)"* — i.e. **without** the L4-8 derivation for the *evidence* limb |
| **C-04** (the AP-3 row) | *"**AP-3** — **Revision is forward-only** — **Derived:** logical state changes are supersession or append; **no in-place revocation exists** anywhere in the architecture — L1-7 (item 1 Q7.1: \"no in-place revocation exists anywhere\")"* — i.e. **without** the UNDETERMINED status qualifier |

**Items C-06, C-07, C-08 and C-09 added content rather than replacing it**, so no superseded wording exists for them and none is claimed. *(C-08's Q-7 is the sole authorized addition of new content, per CCP-1 §7.2.)*

---

## 12. Outstanding Questions (Exception Protocol)

*For each: the question, the missing evidence, **why repository evidence is insufficient**, the effect on this commission, and whether a research commission is warranted.*

| # | Question | Missing evidence | Why repository evidence is insufficient | Blocked? | Right instrument |
|---|---|---|---|---|---|
| **Q-1** | Are the derived dependencies satisfied synchronously or asynchronously; what initiates each; what delivery guarantees apply? | **All timing, coupling, and delivery evidence.** The governed model is silent | The corpus documents *what knowledge exists and how it is governed*, not *how knowledge-handling operates in time*. The practiced system's operational behavior was never a collection target in M0–M8 — and the designed/practiced disjunction (L1-16) means even the designed relationship model would not evidence real coupling | **Partially** — §9.1 completed; the mechanism layer is absent | **Research commission NOT warranted.** The gap is *domain* evidence, not external knowledge — literature could only supply conventions, which the external-knowledge policy forbids and which would be invention. Either an Authority-commissioned discovery act about how the practiced system operates, or an explicitly recorded architectural decision under configuration control. Likely entangled with OQ-PKS-2 |
| **Q-2** | May a logical component ever be defined over a candidate seam or an unpartitioned region? | A governance rule about whether architecture may encapsulate a non-context | The repository governs *strategic* states (bounded context · candidate seam · region) but has never ruled on what architecture may do with each. The rule would be a new governance statement, and none exists to cite | No — §6.2 answered conservatively (no component) | **Authority.** Not research: no external source can rule on this program's governance |
| **Q-3** | Where does the U-2 translation obligation live, given that XD-1's interior is outside PKS design authority? | An ownership decision | The corpus records the interchange terms and the homonym hazard but contains no ownership assignment for the translation — and CBC-4's disposition places the other side of the edge beyond this program's design authority, so the evidence cannot exist on this side alone | No — recorded as unassigned throughout | **Authority** — already an available uncommissioned act |
| **Q-4** | Which component handles Risk, Question, and Exception record? | An allocation | Their membership is **recorded as contested and deliberately unassigned** (M6 §8). The absence is a governed decision, not a documentation gap — so no amount of repository search will resolve it | No — allocated nowhere, deliberately | **Authority / a future strategic act.** Allocating them architecturally would decide a membership the governance left open |
| **Q-5** | Should the Absent capability enclosed by AC-1 (whole-system conformance assessment) be realized, and by whom? | An owner and a realization decision | The capability **operates nowhere** (T-17) and **no role owns it** (OQ-PKS-3, unresolved). Repository evidence establishes the absence; it cannot establish who should fill it | No — specified, not realized | **Authority** — the ownership vacuum is an open governance question |
| **Q-7** *(added per C-08, AFV-F1's second limb)* | **Where is the issuance trigger located?** The governed model separates the issuer role from the author role but **places neither in a context** — so the architecture cannot say whether issuance is triggered inside AR-1, at a boundary, or outside PKS altogether | A placement decision, or a governed rule locating gates and reviews | The corpus states the **role** separation only; no rule assigns those roles to contexts | **Not blocked** — AC-1's non-self-issuance holds regardless | **Authority** |
| **Q-6** | Does the guide-step identity liability inside AC-2 require architectural mitigation, or is it a strategic-model matter? | A ruling on whether an inherited liability inside an accepted boundary is architecture's to repair | M4 records the liability and flags it; nothing in the corpus assigns responsibility for repairing a liability that sits *inside* an accepted boundary | No — recorded as inherited | **Authority**; note that repair would alter the strategic model, which is out of scope here |

**Derived:** five of six questions route to **Authority**, and none routes to literature. **Assembly:** consistent with RET-1 §10, where most remaining boundaries required Authority rather than research.

---

## 13. Inputs to Phase II.C (C4 Views)

**Recommendation (staged; Phase II.C is not commissioned here):**

1. **Two components, one external domain, two undefined regions** (§6). **AP-2 and C4-1's own DP-7 require that a view assert no more than its source; Phase II.C will therefore need to render AR-1 and AR-2 as undefined regions**, never as containers or components *(per AD-R3: the constraint is attributed to its governing principles rather than issued here)*. A view that draws a box around a candidate seam would assert an encapsulation this architecture explicitly withholds.
2. **The ten principles** (§5), four of which are prohibitive and should be visible as constraints rather than annotations.
3. **The logical service boundaries** (§8), with SB-1's asymmetry (narrow outbound surface, broader internal vocabulary) preserved.
4. **The communication model as it stands** (§9.1) — direction, one-wayness, one published interface, initiation asymmetry, regeneration independence — **with the coupling mechanism explicitly absent (Q-1)**. **AP-2 likewise entails that Phase II.C may not supply arrows implying** synchronicity that the architecture does not derive.
5. **The integration model** (§10), including the three integrations that carry no coordination pattern.
6. **The traceability matrix** (§11) as the source for any view's provenance.
7. **The six outstanding questions** (§12) — to be shown as open, not resolved by graphical choice. **Derived:** diagrams resolve ambiguity by omission more easily than prose does; this is the specific risk Phase II.C must manage.
8. **Standing qualifier to carry:** every element above rests on a strategic model graded at most Medium-High on a shared-corpus/shared-lineage basis (MCR-5). **The architecture cannot be more certain than the model it derives from.**

---

## Architectural Quality Gates — verified

| Gate | Verification |
|---|---|
| **Strategic Fidelity** | Every element appears in §11's traceability matrix with concept, artifact, and rationale. No element lacks a row |
| **Governance Fidelity** | No frozen decision modified: all four M6 dispositions, DAR-1's three, SDM v1/EOP v1, MCR-1..6, and the certification status stand untouched. No Surfacing Register item resolved; no PMR admitted |
| **Knowledge Fidelity** | Assembly did not become discovery — no statement is classified Observed, and no new strategic fact appears. Derivation did not become interpretation — where interpretation would have been required (§9.2), the Exception Protocol was invoked instead of a plausible answer. Interpretation did not become governance — all six open questions route out, five to Authority |
| **Boundary Fidelity** | Logical throughout: zero aggregates, entities, value objects, repositories, APIs, schemas, deployment units, or technologies |

---

*Traceability: executes the Phase II.B Architecture Definition Commission AD-1 (PA, 2026-07-28) · derives a logical architecture solely from SDM v1/EOP v1 · MCR-1..6 · M0–M8 · DAR-1 · the M7/M8 Checkpoint · RET-1 · ten principles, two components, one external domain, two architecturally undefined regions, three logical boundaries, five integrations — each traced in §11 · no external knowledge imported, no architectural style adopted by familiarity, no DDD pattern used beyond those governed · synchronicity **not derived** and recorded under the Exception Protocol (Q-1) rather than supplied · six outstanding questions recorded, five routing to Authority, none to literature · no strategic decision revisited, no governance modified, zero tactical content · every statement classified Derived / Assembly / Recommendation / Out of Scope, none Observed. **STOP.***

> **Phase II.B Architecture Definition complete. A logical architecture has been derived from the governed strategic model: two components, one external domain, and two architecturally undefined regions the model does not permit encapsulating. Synchronicity was not derivable and has been recorded rather than supplied. No strategic knowledge was introduced and no governance was reopened. The next act is Phase II.C — C4 Views, separately commissioned.**
