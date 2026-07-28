# Placement Rule — Decision Paper (candidate ES-005.5)

**Status:** DRAFT — **WAITING FOR REVIEW** (Decision Authority) · **Authority:** Generated
**Commission:** R-40/A1 (Decision Authority, 2026-07-27): *"The Engineering Platform shall introduce a governed placement rule for knowledge artifacts … Future repository organization shall follow this rule rather than case-by-case discussion."*
**Lifecycle & precedent:** this paper follows the `Plan_Concept_Decision_Paper.md` pattern — Draft → Adopted → **integrated into its canonical home → HISTORICAL**. Per rule parsimony (ES-001.1) and the OQ-ENG-002 E-4 precedent ("fold as a clarification, not a new rule id" — here a new sub-rule of the standard that already owns placement), the proposed end-state is **ES-005.5, hosted in ES-005 Repository** — never a new standard, never a standalone rulebook. This paper is the transient vehicle.
**Evidence of necessity:** IA review §9 (placement rules stop at the concern boundary); the three-placements-in-one-day record (session log 2026-07-27); IA ambiguities A/B/C/J all reducing to this gap; Knowledge Domain Model §8 ("the repository has placement rules for concerns but no serialization rules for knowledge-object kinds").
**Constraint honored:** A3 — this paper moves nothing; it defines how future placement is *derived*.

---

## 1. Objective

One governed rule that derives an artifact's canonical location from what the artifact *is*, so that placement is a **derivation, not a discussion**.

## 2. The proposed rule — ES-005.5 (candidate text)

> **ES-005.5 — Placement Derivation.** Within the Engineering concern, an artifact's canonical location is **derived**, in order, from:
> 1. **Knowledge Object Kind** — what the artifact carries (per the Knowledge Metamodel catalog);
> 2. **Knowledge Type** — its polarity: **Normative · Descriptive · Evidence · Runtime · Learning** (R-40/A1 vocabulary);
> 3. **Lifecycle** — sealed/frozen history vs living vs dated-record;
> 4. **Authority level** — governed-canonical vs candidate vs generated-analysis;
> 5. **Canonical Serialization** — the id scheme and naming convention its kind prescribes.
>
> The derivation table (§3) is the rule's operative content. An artifact whose derivation is ambiguous is a **finding against the table** (extend the table via governance), never a per-case debate. Placement of a *new kind* requires the kind to enter the Metamodel catalog first — no kind, no placement, no artifact (the R-17 discipline, generalized).

## 3. The derivation table (candidate — derived from current reality; changes nothing today)

| Knowledge Type | Kind (examples) | Lifecycle/Authority | Canonical node | Serialization |
|---|---|---|---|---|
| Normative | ADR · Ruling | accepted / append-only | `architecture/adr/` | `ADR-AIP-nn` · register rows `R-nn` |
| Normative | Standard · Rule · Protocol | PROPOSED→STABLE | `governance/` | `ES-nnn` / `ES-00n.m` (hosted/registered) |
| Normative | Reference Architecture · Decision Model · **adopted Metamodel** | DRAFT→ADOPTED→STABLE | `architecture/reference/` | named singleton per scope |
| Normative (historical) | Sealed Baseline | FROZEN·SEALED | `architecture/baseline/` | `Phase-NN` |
| Descriptive | Architecture View | living-by-addendum, subordinate | `architecture/c4/` | views document |
| **Descriptive** | **Architecture Analysis** (A2: reconstructions, gap analyses, readiness assessments, reviews) | dated, adopts-nothing | **OPEN — Decision D-1 below** | dated filename today; id series is Decision D-2 |
| Evidence | Qualification Record · Finding/Correction/Verdict | executed, verdict+history | `verification/qualification/` | dated `OQ-ENG-nnn` |
| Evidence | Qualification/Observation Protocol (unexecuted) | commissioned | `verification/qualification/` *(or split — Decision D-3)* | named protocol |
| Evidence | Verification Report | submitted/accepted | `verification/reports/` | dated filename |
| Learning | Harvest · Pattern Card · Evidence Register | candidate-tier | `knowledge/patterns/` (+ `sources/`) | `EPC-nnn` + register rows |
| Learning | Methodology Module (once ADOPTED it is Normative-bindable) | candidate→ADOPTED | `knowledge/methodology/` | named module |
| Learning | Research | frozen (ES-006.2) | **project-side** (`docs/implementation/`) per ES-005.3 | `RQ-nnn` / charter names |
| Runtime | Runtime Asset · Registry · runtime binding · Work Plan · Session/Context | per registry / ephemeral | `.claude/` (mount) | `AST-nnn` · registry-first |
| — | Developer Guide (teaches contributors) | living per-step | **OPEN — Decision D-4** (reserved name is `developer/guides/`) | numbered per area |

## 4. Decisions requested (per item: Approve / Reject / Defer)

| # | Decision | Options (trade-offs on record) |
|---|---|---|
| D-1 | **Canonical node for Architecture Analysis (A2 type)** | (a) `architecture/analysis/` — new node, cleanest polarity, scales with the reconstruction practice (the DA's recorded lean); (b) `verification/reports/` — status quo, no new structure, buries current-state descriptions among audits; (c) `architecture/reference/` — discoverable, mixes polarity. *IA review Ambiguity A/B trade-offs incorporated by reference.* |
| D-2 | **Id series for Architecture Analysis artifacts** | The Metamodel's recorded internal inconsistency: reports identify by filename against the ids-not-filenames preference. Options: introduce an id series (e.g. `AA-nnn`-class) · or rule dated-filenames the recorded exception for this kind (the ES-004.2 plans precedent shows exceptions are legal when explicit). |
| D-3 | **Protocols vs records in `verification/qualification/`** | (a) status quo + naming convention (protocol ↔ paired dated record); (b) sub-split. IA Ambiguity C. |
| D-4 | **The developer-guidance node** | Reserved name `developer/guides/` vs existing-but-empty-of-guides `developer_guide/` — interacts with D2 (hook coupling) and the deferred guide migration; resolution belongs to the Transition Plan (A5), but the *name* should be ruled here so the plan derives from it. |
| D-5 | **Adopt ES-005.5 as hosted rule text in ES-005** | On approval (with D-1..D-4 resolved), integrate; this paper → HISTORICAL. |

## 5. Out of scope

No relocation, no renaming, no folder creation (A3). The Transition Plan (A5) derives all moves from the ruled table — never from this paper directly.

---
*Traceability: R-40/A1 · IA review 2026-07-27 §9/§10 · Knowledge Domain Model §8 · precedent `Plan_Concept_Decision_Paper.md` (integrated → HISTORICAL) · parsimony basis OQ-ENG-002 E-4. **STOP — WAITING FOR REVIEW.***
