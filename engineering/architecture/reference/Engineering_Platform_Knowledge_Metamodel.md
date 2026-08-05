# Engineering Platform Knowledge Metamodel

**Class:** Engineering Platform architecture (reference) · **Owner:** Decision Authority
**Status:** **CANDIDATE — NOT ADOPTED.** Prepared under explicit authorization R-40/A4 (*"prepare a candidate governed artifact … It shall remain descriptive until explicitly adopted. Do not adopt it. Submit it for review."*). Adoption gate: **OQ-ENG-004** (fresh-session qualification, protocol beside this document's evidence base) → Decision Authority decision.
**Derived from:** the Knowledge Domain Model (`verification/reports/2026-07-27-knowledge-domain-model.md`, evidence class) + the Information Architecture Review (same date) — this document is their **normative-candidate distillation**; on any conflict *while unadopted*, the evidence reports and the standards in force win.
**Placement note:** filed beside its normative-reference siblings per the folder's established DRAFT-first pattern (the Reference Architecture and Decision Model both arrived here pre-adoption). **This placement is provisional until the Placement Rule itself is adopted** (DA review, 2026-07-27) — the rule will derive this document's canonical location from the catalog this document defines, so neither may presuppose the other; the circular dependency is broken by declaring this location non-binding until ES-005.5 exists.
**Prospective-instance declaration (recorded at the time, per the Artifact-Promotion Candidate Qualification Plan §2):** artifact = this metamodel · runtime/report representation = the 2026-07-27 domain-model report §9 · **promotion event, pre-declared = the Decision Authority's adoption decision after OQ-ENG-004** · governed destination = `architecture/reference/` (or as the Placement Rule derives) · qualification responsibility = OQ-ENG-004. All four §2 conditions are hereby named **before** the crossing.

**Corrections from the Decision Authority's review of the domain model, incorporated:**

| DA issue | How this candidate resolves it |
|---|---|
| 1 — over-claim | Central claim reworded to the DA's formulation (§1): *the repository **exhibits evidence of** an implicit knowledge domain model* — the model explains the repository; it is not inferred to *be* it |
| 2 — meta-kinds premature | The seven analytical meta-kinds are **demoted to analysis vocabulary** (kept in the evidence report). The governed type surface here is the DA's own five-type polarity from A1: **Normative · Descriptive · Evidence · Runtime · Learning** (§3) |
| 3 — missing relationship semantics | §5 defines every relationship with cardinality, direction, composition/association, and cycle rules — each cell evidence-derived |
| 4 — no machine semantics | §7 provides a validatable schema (descriptive; tooling only via the evidence path) |
| 5 — families ≠ bounded contexts | §8 states the boundary explicitly; the frozen six-context strategic model is untouched |

**Second-round corrections (DA candidate-acceptance review, 2026-07-27 — folded per EEP §5 "approved with requested changes"):** placement declared provisional until the Placement Rule is adopted (circularity broken) · catalog reworded from closed set to **governed extension** (ES-006.1 path) · CAP→Component cardinality reclassified as an *architectural constraint of the current reference architecture*, with a general invariant-class caveat added to §5 (Domain · Architecture · Implementation · Observation — OQ-ENG-004 classifies each) · §7 retitled **illustrative** schema, not a canonical contract · §8 extended with the explicit cross-reference to the frozen Context Map (strategic layer no longer implicit).

---

## 1. Purpose and epistemic claim

The repository **exhibits evidence of an implicit knowledge domain model**: stable identities that outlive paths ("paths change; ids never do"), one-canonical-home rules, orthogonal lifecycle axes, and authority transitions that occur only at Human Decision Events. This metamodel makes that implicit model explicit so that document placement, naming, tooling, and AI behavior can be **derived from the model** rather than decided per case. It defines vocabulary and constraints; it creates no new governance while CANDIDATE, and on adoption it changes only *how existing rules are indexed and derived from* — every rule it references keeps its canonical home (rules-live-once; this document is a semantic index in the Decision-Model tradition: **never a second rulebook**).

## 2. The three-level structure

```text
KNOWLEDGE OBJECT   identified unit of meaning      (Ruling R-36 · Rule ES-003.2 · Pattern EPC-001)
      │ serialized in (1..n per artifact)
ARTIFACT           governed carrier w/ status+authority headers   (register · standard · dossier)
      │ stored as (contingent)
DOCUMENT           a file at a path                (paths carry no identity)
```

Rules already in force that this structure names: registry identity rule · ids-not-filenames (ES-004.2) · hosted/registered serialization (STANDARDS_INDEX) · the plans-filename exception (explicit, DA-ruled — exceptions are legal when recorded).

## 3. Knowledge Types (the governed polarity — R-40/A1 vocabulary)

| Type | Defining property | Conflict rank |
|---|---|---|
| **Normative** | states what must hold or what was decided; wins conflicts downward | 1 (highest; internally: sealed corpus & rulings > standards > reference models) |
| **Descriptive** | states what exists; **loses every conflict** with Normative ("frozen artifacts win") | 4 |
| **Evidence** | states what was measured/observed; produced-never-asserted; append-only; **never becomes authoritative** | 3 (facts outrank descriptions, never norms) |
| **Runtime** | executes or attaches; points-never-restates; replaceable | 5 |
| **Learning** | might become knowledge; quarantined (`generated`/candidate/frozen) until promoted | (unranked — outside conflicts until promoted) |

## 4. Knowledge Object Catalog (type · identity · canonical serialization)

*Full per-object schema (purpose, producers/consumers, promotion, retirement) remains in the evidence report; this catalog is the governed surface.*

**Extension rule (DA review correction, 2026-07-27):** the catalog is **governably extensible, never permanently closed** — a new kind enters through the promotion ladder (ES-006.1) with a Decision Authority act, exactly as any other vocabulary change; what is forbidden is *silent* addition, not addition. (The architecture is frozen; the vocabulary evolves through governance — these are different properties.)

| Object | Type | Identity | Canonical serialization today |
|---|---|---|---|
| Human Decision Event | Normative (root) | event name + date + authority | recorded inside ADRs/rulings/acceptance records |
| ADR · Ruling | Normative | `ADR-AIP-nn` · `R-nn` | `architecture/adr/` (register append-only) |
| Principle · Platform Decision | Normative | `AIP-nn` · `PD-nn` | sealed baseline |
| Rule · Standard · Protocol | Normative | `ES-00n.m` · `ES-nnn` · named | `governance/` (hosted/registered) |
| Promoted Behaviour | Normative | numbered, AST-013 § | runtime asset by explicit adoption |
| Ubiquitous Language term | Normative | term → one definition | Phase-02.6 (sealed; supersession is the only amendment path — the two-generation condition is this object's open item) |
| Reference Architecture · Engineering Decision (model) | Normative (model) | singleton · `Determine*` | `architecture/reference/` |
| Architecture View | Descriptive | section/diagram | `architecture/c4/` |
| **Architecture Analysis** (A2) | Descriptive | **open — Placement Rule D-1/D-2** | dated reports (today) |
| Sealed Baseline | Normative-historical | `Phase-NN` | `architecture/baseline/` |
| Capability · Component · Runtime Asset · Registry · Binding | Runtime (Capability/Component are Normative-model entries realized by Runtime) | `CAP-nn` · `CMP-nnn` · `AST-nnn` | registry + mount; five-question trace mandatory |
| Methodology Module | Learning → Normative-bindable on adoption | named module | `knowledge/methodology/` |
| Harvest · Pattern Card · Register Entry | Learning | source-name · `EPC-nnn` · dated row | `knowledge/patterns/` |
| Research | Learning (frozen) | `RQ-nnn` / charter | project-side (ES-005.3) |
| Qualification · Protocol · Finding · Correction · Verdict · Evidence Record · Verification Report | Evidence | `OQ-…` · named · `F-…` · `CR-…` · verdict+history · dated | `verification/` |
| Work Plan → Engineering Plan · Session Log · Context Snapshot | Runtime → (plan promotes to governed at EP-01) | filename · timestamp-convention (ruled exception) · date | mount · `docs/plans/` |
| Developer Guide | Descriptive (teaching) | numbered per area | area convention (node open — D-4) |

**Explicitly not modelled (evidence-based exclusions, from the domain model):** Theory (candidate only) · Policy (attribute rows, not an object) · generic Requirement (operating forms: Property, Qualification Method) · EKP Knowledge Items (adjacent domain, PENDING ARB).

## 5. Relationship semantics (cardinality · direction · composition · cycles)

| Relationship | From → To | Cardinality (evidence) | Comp/Assoc | Cycles |
|---|---|---|---|---|
| `records` | ADR/Ruling → Human Decision Event | each ADR records ≥1 event; each event recorded by exactly 1 authority record | association | forbidden |
| `hosts` | Standard → Rule | 1 standard hosts 0..n rules; **each rule hosted by exactly 1 standard** (I-2) | **composition** (rule text lives inside) | n/a |
| `registers` | Standard/Index → Rule elsewhere | 0..n pointers per rule; pointers never carry text | association | n/a |
| `justifies` | Norm → Norm (downward only) | n..m | association | **forbidden upward** (decisions reference principles, never vice versa) |
| `realizes` | Capability → Component | **current implementation realizes each CAP through exactly 1 component** (stated as a rule in frozen Phase-03A §1: "every CAP maps to exactly one component" — an *architectural* constraint of the current reference architecture, not asserted here as a domain invariant); a component realizes 1..n CAPs | association | forbidden |
| `implements` | Component → Runtime Asset | 1 component ← each asset implements exactly 1 component (registry `component:` is single-valued); component has 0..n assets | composition (asset belongs to its component) | forbidden |
| `traces-to` | Asset → CAP/context/AIP/PD/ADR | **exactly 1 of each** (five-question trace, singular by schema) | association | forbidden |
| `binds` | Binding → canonical object | n bindings → 1 canonical; a binding binds exactly 1 object; tighten-only | association | forbidden |
| `points-to` | Runtime/pointer → Norm | n → 1 canonical home | association | forbidden |
| `describes` | Descriptive → anything | 1 → 0..n (a report may describe many objects) | association | forbidden (nothing describes itself as authority) |
| `verifies` | Evidence-kind → Norm/Model conformance | each FF verifies exactly 1 Property; each Standard declares ≥1 Qualification Method; a qualification run verifies 1..n rules | association | forbidden |
| `evidences` | Evidence Record → claim/verdict | **every verdict ← ≥1 record (I-5)**; a record may support 1..n claims | composition (verdict cannot exist without it) | forbidden |
| `promotes` | HDE (+ ladder) → Learning→Normative | each promotion = exactly 1 event per stage; stages never skipped | association | forbidden |
| `supersedes` | any → its predecessor | **exactly 1 → 1**, same kind; predecessor becomes historical | association | **acyclic by construction** (history is monotone) |

**Invariant-class caveat (DA review correction, 2026-07-27):** every constraint in this table carries an implicit classification — **Domain invariant · Architectural constraint · Implementation constraint · Current observation** — and OQ-ENG-004 classifies each explicitly rather than accepting the table wholesale. Provisional guidance: constraints sourced from the sealed constitution (e.g. verdict-requires-evidence, authority-requires-HDE) are domain/governance invariants; constraints sourced from the frozen reference architecture (CAP↔component) are architectural; constraints read off the registry schema or current wiring (asset `component:` single-valued) are implementation constraints — they document current reality and are challenged, not assumed, at qualification.

**Transitivity note:** `supersedes` chains are transitive for authority resolution (only the head is current); `justifies` and `traces-to` chains are transitive for traceability queries (AIP-12: ADR → Capability → Rule → Implementation must resolve end-to-end); no other relationship is treated as transitive.

**Global dependency direction:** Normative → is depended on by → everything; Descriptive and Evidence depend on Normative and on facts, and nothing normative may depend on them (the one gated exception: Evidence reaches Normative **only through** a `promotes` edge at a Human Decision Event).

## 6. Lifecycle semantics — four orthogonal axes (conflation is the policed failure mode)

`authority` (generated → … → authoritative → historical) ⊥ `status` (idea → … → frozen/sealed | superseded → archived) ⊥ `maturity` (Candidate → Observed → Validated → Standard — Learning only) ⊥ `adoption` (planned → adopted → deprecated → removed — Runtime only). Illegal combinations are rejected (precedent: `frozen`+`generated`). Evolution rules (when may X become Y) remain hosted where they live — ES-006.1, the Plan Concept, R-39's multi-context bar — this metamodel indexes, never restates. Two recorded absences: no research-expiry rule; no Architecture-Analysis lifecycle rule (Placement Rule D-2 / OQ-ENG-004 input).

## 7. Illustrative metamodel schema (documents intent — **not a canonical contract**; no runtime validates it, and none is proposed)

```yaml
KnowledgeObject:
  id:            required   # per-kind scheme; kinds without a scheme are a recorded gap, not an exemption
  kind:          required   # from §4 catalog — governed-extension set (ES-006.1 + DA act; never silent addition)
  type:          required   # Normative | Descriptive | Evidence | Runtime | Learning
  owner:         required   # exactly one (I-9)
  authority:     required   # authoritative|derived|generated|historical|provisional
  status:        required   # lifecycle position, orthogonal to authority
  canonical_home: required  # exactly one artifact path-independent home (I-2)
  serialized_in: required   # 1..n artifacts; non-canonical entries MUST be pointers
  relationships: []         # only verbs from §5; each checked against cardinality + direction
constraints:               # each maps to a §5/§6 rule and an existing invariant id
  - authority transitions require a referenced HumanDecisionEvent   # I-1
  - type=Descriptive|Evidence objects never appear as a `justifies`/`hosts` source  # I-4/I-16
  - supersedes-graph is acyclic; exactly one non-superseded head per object line     # I-6
  - illegal (authority,status) pairs rejected                                        # UL rule
```

No validator exists and none is proposed — per the automation stance, a validator earns existence through the promotion ladder with evidence that reasoning-over-this-document fails.

## 8. Boundaries

**Families/meta-kinds ≠ bounded contexts.** The seven analytical meta-kinds of the evidence report are classification vocabulary for analysis, not strategic boundaries and not (yet) ubiquitous language; the platform's strategic model remains the frozen six bounded contexts + two external domains (Phase-02). Any future strategic remodeling of the platform follows AIP-13 with implementation evidence — this metamodel neither proposes nor prejudges it. **Adjacent domain:** EKP/Project-Knowledge objects are out of scope (ES-006 boundary), disposition PENDING ARB.

**The strategic layer (explicit cross-reference — DA review, 2026-07-27: "the strategic relationships between major subdomains remain comparatively implicit").** The platform's Context Map **exists and is frozen**: `architecture/baseline/Phase-02-Domain-Model.md` §3 (six contexts + Project Governance as upstream OHS/PL + Human Authority as the authoritative event source; Conformist, Customer–Supplier, and Separate Ways relationships all named), redrawn in `architecture/c4/AI_Engineering_Platform_Views.md` §3. This metamodel is **tactical relative to that map**: every knowledge object here lives inside one of those contexts (rulings/standards in the governance line, patterns in Knowledge Governance, qualifications in Verification & Evidence, snapshots in Session Continuity). A *new* subdomain-level map (e.g. Engineering Governance → publishes → Engineering Knowledge → constrains → Engineering Runtime) would be strategic remodeling — it remains hypothesis-tier per the recorded capability-above-process question (pattern dossier, note (o)) and enters only through evidence. The gap this paragraph closes is *discoverability of the existing map from this document*, not the map's absence.

## 9. Adoption path and preconditions

1. **OQ-ENG-004** executes in a fresh session (producer ≠ reviewer — this session authored both the reconstruction and this candidate).
2. Findings dispositioned by the Decision Authority; corrections via ES-003.1 lifecycle.
3. Adoption decision = the pre-declared promotion event (header). On adoption: this document's status → ADOPTED; the Placement Rule's §4 kind-catalog dependency binds (ES-005.5 derives from this catalog); the two-generation vocabulary condition is resolved **through** this document only if the DA designates it the Phase-02.6 supersession vehicle — a separate, explicit decision, not implied by adoption.
4. If OQ-ENG-004 returns KEEP-DESCRIPTIVE: this document remains a candidate or is retired to the evidence tier — a legitimate outcome ("no X needed" is a success verdict).

---
*Traceability: R-40/A4 authorization · derived from the 2026-07-27 Knowledge Domain Model + IA Review (evidence tier) · DA review corrections 1–5 incorporated (header table) · qualification: OQ-ENG-004 protocol · prospective-instance declaration in header (Artifact-Promotion plan §2). **STOP — submitted for review; not adopted.***
