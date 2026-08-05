# Knowledge Governance Roadmap — Disposition Register

**Kind:** decision register (per-capability dispositions of an external/generic proposal against the governed repository) — precedent class: decision papers + the qualification findings registers.
**Status:** dispositions recorded per the Decision Authority's readiness-review acceptance, 2026-07-27. Living: a row changes only by a new DA decision, appended rationale preserved.
**Purpose:** record, per major roadmap capability, exactly one disposition — **Adopt unchanged · Operationalize existing · Adapt · Route · Reject/Redesign · Defer** — with rationale, so the "why wasn't this implemented as written?" question never re-surfaces unanswered.
**Subject:** `developer_guide/ai_platform/Knowledge Governance Roadmap.md` (v1.0) · **Analysis:** `engineering/verification/reports/2026-07-27-knowledge-governance-roadmap-readiness-review.md` (+ RR-F3 refinement addendum — ranking ≠ authority).
**Standing rule inherited:** dispositions never create capabilities; every implementation item still enters through its named gate.

| # | Capability | Roadmap § | Disposition | Rationale (condensed — full analysis in the review) | Owner / Gate |
|---|---|---|---|---|---|
| 1 | Domain ownership registry | L1 | **Operationalize existing** | Ownership operates (PGP-02 · registry `owner:` · STANDARDS_INDEX · EKP cards); the queryable unified view = metamodel catalog | Metamodel adoption / **OQ-ENG-004** |
| 2 | Orphan detection & resolution | L1 | **Adopt unchanged** (it already is) | OQ E-2 instruments + knowledge-lint, periodic per R-26 — executed twice | existing OQ pipeline |
| 3 | DKO / Steward / RACI role model | L1 | **Route to KnowledgeOS** | Enterprise role model = product-tier customer content; this repo's four-role model operates | KnowledgeOS track / Stage 3 |
| 4 | Lifecycle state machine | L2 | **Reject — keep existing** | The roadmap's single chain is a regression vs the four orthogonal axes (authority ⊥ status ⊥ maturity ⊥ adoption) | — |
| 5 | Review cadence + stale detection | L2 | **Adapt — new periodic instrument** | The one evidence-backed gap (ENG-005 debt · 8 stale-header findings). OQ-class: run → report → stop; **not** a scheduler daemon; cadence values enter as observed baselines (ES-003.3) | OQ pipeline / proposed id **OQ-ENG-005** (id confirmed at commissioning) |
| 6 | Event-triggered reviews | L2 | **Adapt — extend partial** | Hooks + Governance-Evolution clauses + OQ re-runs exist; systematic trigger catalog is the extension | with #5 |
| 7 | Enterprise metadata schema | L3 | **Operationalize existing** | EKP cards operating (product tier) + Metamodel §7 (CANDIDATE, engineering tier); unification = the queued metamodel + placement work | **OQ-ENG-004** + Placement Rule **D-1..D-5** |
| 8 | Taxonomy / controlled vocabulary | L3 | **Adopt unchanged as policy · Defer as tooling** | UL (Phase-02.6) is the operating taxonomy governance; tooling has no evidence of need | — |
| 9 | Semantic retrieval stack (embeddings · vector store · chunking · router) | L3 | **Route (twice)** | Internal: Context Assembly Research Charter (PROPOSED — open DA gate) + AIP-14 justification required. Product: KnowledgeOS Stage 5 | Charter gate / Stage 5 |
| 10 | Composite retrieval confidence score | L3 | **Reject as authority · Permit as bounded ranking** | Per RR-F3 refined: ranking heuristics allowed (ephemeral, never persisted, never overriding provenance/lifecycle/decisions); authority use rejected (ES-003.2 · metric freeze · Phase-01 truth-score class) | design constraint on #9, wherever built |
| 11 | Composite trust score w/ exclusion authority | L5 | **Reject — redesign** | Score-with-authority = "opaque metric as unaccountable actor". Replacement: fact-based exclusion rules + existing qualitative trust surface (status · authority · verdicts · epistemic labels) | — |
| 12 | Append-only audit trail + provenance | L5 | **Adopt unchanged** (it already is) | AIP-11 corpus — constitutional, operating; the roadmap plans what this repository practises | — |
| 13 | Structured (JSON) audit event taxonomy | L5 | **Defer** | Named trigger already on record: *machine-readable artifacts — trigger: a machine consumer exists*. None exists | trigger fires |
| 14 | Governance dashboard / compliance exports | L5 | **Defer** | Deferral register: *qualification dashboard — trigger: accumulated qualification evidence* | trigger fires |
| 15 | KPI catalog with asserted targets | §8 | **Reject — redesign** | ES-003.3: baseline-then-deliberate-ratchet; drop score-dependent KPIs (fall with #10/#11) | — |
| 16 | Resident CI daemons / schedulers | L4 | **Reject — defer** | Zero-new-hooks burden of proof unmet; periodic instruments preferred (R-26); ladder + evidence is the entry path | ES-006.1 ladder |
| 17 | Docs-as-code + PR workflow | L4 | **Adopt unchanged** (it already is) | The repository is the implementation | — |
| 18 | Phases 0–5 + 90-day enterprise plan + Council model | §5/§6/§10 | **Route to KnowledgeOS** | Customer-journey / operating-model material for the product's target organizations | Stage 3/4 material |
| 19 | *(the roadmap document itself)* | — | **Reclassify (RR-F4 accepted)** | Ungoverned generic draft in a flagged folder → harvest valuable mechanisms as pattern cards (ES-006.3) and/or register as KnowledgeOS discovery input; execution of the reclassification is a small follow-up act | DA (disposition made; execution pending) |

**Net implementation plan (RR-F2 accepted — this replaces the roadmap's phases):** standing gates (ratification → C3 + OQ-ENG-003 → Placement Rule D-1..D-5 → OQ-ENG-004) · one new instrument (#5) · routing decisions (#9, #18) · redesign constraints (#10, #11, #15) attached wherever the routed work lands.

---
*Traceability: DA readiness-review acceptance + register commission, 2026-07-27 · analysis: the readiness review + RR-F3 addendum · dispositions are DA decisions; rows change only by appended DA decision · placement: beside the decision-paper precedents in `docs/implementation/`.*
