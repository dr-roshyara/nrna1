# Round 39-01 — Governance Architecture Research Methodology — Specification v1.0

**Program:** NRNA DDD Trustworthiness Research Program
**Document:** the single, versioned, **canonical** specification of the research methodology.
**Status:** 🔒 **v1.0 — FROZEN for F-THR and F-REV.** Supersedes scattered phrasing in P2-00/17/18/19/SYN-01 (those remain the detailed normative sources; this is the consolidated reference). Change only via ADR-M + version bump.
**Incorporates evidence through:** F-OBS, F-AUTH, F-PROC.
**Date:** 2026-06-25

---

## 1. Objectives

Translate constitutional intent into software through a disciplined, **traceable, falsifiable** pipeline that never collapses legal design, governance design, and implementation. The methodology must be **predictive** (state what it expects), **self-correcting** (narrow its own claims under evidence), and **bounded** (stop when saturated).

## 2. Principles (binding)

The 13 disciplines (Handbook V0 §4) plus: **discover → synthesize → validate → continue**; **discovery, not optimization** (every mechanism is a hypothesis); **literature informs, never dictates**; **provisional ≠ adopted; working model ≠ established.**

## 3. Definitions (ontology — authoritative)

- **Constitutional Property (S-1..S-5)** — a binding requirement (what must be protected).
- **Governance Capability (`GC-Sx-xx`)** — what the system must be able to do (purpose-only).
- **Emergent Capability Family (`F-XXX`)** — cross-safeguard grouping of capabilities (organizing layer; **not** a context/service).
- **Governance Mechanism** — a concrete realization (how). Organized **Class → Group → Mechanism**.
- **Composite Governance Architecture** — a coordinated selection of interacting mechanisms across orthogonal **dimensions**, realizing a capability; the *unit of design* **when** the property is emergent (see §12, M-07).
- **Dimension** — an orthogonal design axis (F-AUTH: D1 Source / D2 Temporal / D3 Qualification / D4 Approval / D5 Protection; *validated per family, not universal*).
- **Interaction profile** — a family's interaction topology: **Emergent / Additive / Mixed** (candidate typology M-07).
- **GRP (Governance Recursion Point)** — a lifecycle stage applied to itself; ineliminable under single source; surrounded, not closed.
- **Search / Candidate / Design Space** — imaginable / architecturally-plausible / constitutionally-surviving.
- **Architectural emergence** (per-composite property) vs **Research emergence** (methodology-level discovery).
- **Empirical observation** (M-01..04, M-07) vs **Methodological rule** (R-01 orthogonal dimensions, R-02 sketch-before-literature).
- **Evidence strength / Confidence / Transferability** — three independent ★ axes.

## 4. Lifecycle (observation states)

`Observed → Replicated → Reinforced → Working Method Principle → General Principle.` *General Principle requires validation **beyond** the NRNA corpus (a different framework + independent team).* After F-AUTH+F-PROC: M-01..04 Observed (M-01/03 *narrowed*); R-01/R-02 *Replicated*; M-07 Observed (Confidence Low).

## 5. Discovery protocol (state machine)

Pipeline: `Capability → Design Space → Class → Group → Mechanism → Composite → Evaluation → Recommendation/no-dominant → Archive.`

| Transition | Entry | Exit | Allowed | Forbidden |
|------------|-------|------|---------|-----------|
| Capability → Design space | a Pass-1 capability | search/candidate/design mapped | sketch-first, then literature | mechanisms before sketch |
| Classify | mechanisms harvested | Class→Group→Mechanism assigned | grouping | comparing across wrong level |
| Composite | candidates exist | composites + interaction matrix | composition (Rule 10), 3 interaction edge types | forcing a composite where additive |
| Evaluate | composites defined | filled evaluation template | functional + architectural fitness; evidence/confidence/transferability | DDD constructs |
| Recommend | evaluation done | recommendation **or** no-dominant | retain rejected (categorized) | first-idea acceptance |

## 6. Literature protocol

Sketch **before** literature (anti-anchoring). Direction: architecture questions literature, not vice-versa. Categories A (constitutional theory — CLOSED) / B (institutional design) / C (governance engineering) / D (systems & safety). Reading priority: election admin → oversight institutions → institutional design → high-assurance/safety → distributed systems (last). Read nothing that can't fill the extraction fields. **Literature broadens the design space; it never reopens the constitutional architecture.**

## 7. Validation protocol

Each new family is a **hostile replication**: attempt to *break* the provisional observations. Outcomes: **Supported / Narrowed / Refuted / Inconclusive** (operationally defined, §12). Track the **cross-family convergence matrix**, **per-family yield**, and the **saturation criterion** (no protocol change across 2 consecutive families → stable; *stability ≠ correctness*).

## 8. Prediction protocol

The **Prediction Register** (P2-18) is **LOCKED before discovery, unlocked only after** (No Retroactive Prediction). Every prediction: Observation · Prediction · Falsifier · Evidence · Strength · Level (Method/Architecture/Family/Project) · Independence (Foundational/Derived). Derived failing < Foundational failing. Assumptions are distinct from predictions.

## 9. Threats to validity

Internal (confirmation/sketch/selection/interpretation bias) · Construct (are dimensions real?) · External (one constitution, no deployment) · Reliability (single-classifier today; inter-rater planned). **Standing threat:** architecture-level observations rest on ≤2 families — working hypotheses, not principles.

## 10. Maturity

`L0 Exploratory → L1 Repeatable → L2 Predictive → L3 Stable → L4 Externally validated.` **Current: L2+ (Predictive, first successful hostile replication).** L3 needs survival + saturation across F-THR/F-REV; L4 needs independent replication.

## 11. Evidence model

Provenance (8 origins incl. NRNA-original / derived; compositional provenance: primary/secondary/novel-composition/novel-interaction). Three spaces with **categorized exclusions** (Constitutional/Architectural/Functional/Contextual). **Measurable emergence:** a composite property is emergent iff (1) no component has it, (2) removing any component destroys it, (3) the interaction yields behaviour impossible in isolation.

## 12. Decision rules

- **Composite vs mechanism (M-07):** evaluate composites **iff** the family's interaction profile is Emergent/Mixed; **additive** families are evaluated mechanism-by-mechanism.
- **No-dominant** is a valid outcome; recommendations may be risk-tiered/situational.
- **"Dominates"** = adding any other mechanism gives no material improvement. **"Narrowed"** = valid under explicitly reduced scope. **"Inconclusive"** = family lacks conditions to test. **"Entirely novel"** = absent from literature ∧ prior families ∧ taxonomy.

## 13. Outputs

Per capability/family: the **Mechanism Design Space Map** (Class→Group→Mechanism→Composite→Evaluation→Recommendation) + interaction matrix + retained exclusions + open questions. Methodology changes → **ADR-M**. Governance graphs: **G1** capability dependency · **G2** mechanism interaction · **G3** composite (future).

## 14. Governance

- **DDD gate ACTIVE** (no contexts/aggregates/services/code until Strategic DDD authorized).
- **Frozen artifacts** (EGCP-01, GLOSSARY-01, P2-00, **this spec**): change only via dated erratum / ADR-M + version bump.
- **Scope of inference:** every claim states its population; nothing claims beyond NRNA without external validation.
- **Three research objects** (Governance / Methodology / Software Translation) must not be conflated.

---

## Source-document map (detailed normative sources)

| Spec § | Source |
|--------|--------|
| 5 Discovery | P2-00 |
| 6 Literature, 11 Evidence | P2-17 |
| 8 Prediction | P2-18 |
| 9 Threats, 10 Maturity | P2-19 |
| 4 Lifecycle, 7 Validation, 12 Decision | P2-SYN-01 |
| 3 Ontology | GLOSSARY-01 (+ this §3) |
| worked example | P2-01 (F-OBS), P2-02 (F-AUTH), P2-03 (F-PROC) |

---

*Round 39-01 — Methodology Specification v1.0 — FROZEN for F-THR / F-REV*
*Consolidated, versioned, canonical. Change only via ADR-M + version bump. Strategic DDD GATED.*
