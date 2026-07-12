# Strategic DDD Discovery — The Engineering Governance Domain

**Kind:** discovery inventory — the three preparatory exercises that precede a Context Map (Domain Inventory · Aggregate Discovery · Ubiquitous-Language Audit). **NOT a context map. NOT strategic commitments.** No Customer/Supplier, ACL, Shared Kernel, or Published Language relationships are assigned anywhere in this document — per the ARB ruling (2026-07-12): *"the strategic relationship is the conclusion, not the starting point"* and *"Strategic DDD discovery ✅ can begin · Strategic DDD commitments ⏳ await evidence."*
**Status:** DISCOVERY — presented for ARB review. **STOP: no context map may be drawn from this until operational evidence (C3 + pilot) exists and the ARB rules.**
**Method:** evidence only — every concept, candidate aggregate, and language finding below is traceable to a committed repository artifact or a recorded session event. Nothing is invented; genuinely open questions are marked open.
**Dual purpose:** (a) the prerequisite the earlier context-map critique demanded; (b) domain source material for the KnowledgeOS Product Discovery Charter's Domain Discovery stage (stage 3) — written once, consumed by both.

## Exercise 1 — Domain Inventory

Significant concepts of the engineering-governance domain, grouped by the natural clusters the repository itself exhibits (clusters are **observations, not bounded-context claims**):

| Cluster | Concepts (each with a governed home) |
|---|---|
| **Governance** | Engineering Standard (ES-001..006) · Ruling (R-nn, append-only register) · Decision Authority / ARB · Ratification · Freeze (governance R-27 · structural R-37 · conceptual 2026-07-11) · Promotion Ladder (ES-006.1) · Stopping Rule · Rule Parsimony (ES-001.1) · Amendment / Refinement · Override (Decision-Authority act, distinct from architectural recommendation) |
| **Decisions** | Engineering Decision (catalog of 8) · Decision Model · Current Resolution Procedure ("the decision never changes; only its resolution evolves") · Candidate status · Decision Authority & Verification Matrix (3 dimensions) |
| **Execution** | EEP lifecycle (plan → independent review → approval → implement → verify → report → decide) · EP-01 / EP-01-Light · EP-02 Completion Review · EP-03 Readiness Review · Work Plan ≠ Engineering Plan (adopted two-concept model, decision paper HISTORICAL) · Implementation slice · Ticket (PB-xxx) |
| **Verification** | Qualification (OQ-ENG-nnn) · Verification Report · Finding (F-series) · Verdict (PASS · PASS AFTER CORRECTION · WARN · FAIL · INCONCLUSIVE · EMERGENT) · Fitness Test · Merge Gate · Instrument Neutrality ("the instrument measures; the Decision Authority decides") · the five mechanism classes (Runtime Guidance · Build Verification · Merge Qualification · Architecture Governance · Operational Evidence — candidate taxonomy, one use) |
| **Evidence & Learning** | Operational Evidence · Observation (Class A, R-34) · Observation Protocol (falsifiable, scheduled) · Hypothesis · Watch-item / Candidate · Retrospective · Pattern Card (EPC-nnn) · Pattern Evidence Register · Harvest Question (ES-006.4: "did this work REVEAL reusable engineering knowledge?") |
| **Knowledge (research-frozen theory)** | KnowledgeClaim · KnowledgeNeed · WorkingContext · Context Assembly · Artifact (type-specific lifecycles, deletion litmus) · Artifact Promotion (candidate pattern, 3 retrospective confirmations / 0 prospective) |

## Exercise 2 — Aggregate Discovery (candidates, with the evidence for each)

*Candidate roots — ownership and lifecycle observed in the repository, not designed:*

| Candidate aggregate root | Lifecycle observed | Owner | Evidence |
|---|---|---|---|
| **Engineering Standard** | PROPOSED → RATIFIED → STABLE → SUPERSEDED; hosts rules (ES-nnn.n) as internal entities; registered rules are *references*, never contained | Decision Authority | ES-001..006 headers; HOSTED/REGISTERED convention |
| **Ruling** | append-only, entries immutable once recorded; same-day extension *within* a row is the only amendment form (R-37/R-38 precedent) | Decision Authority | `ADR-AIP-LOG-Platform-Rulings.md` |
| **Engineering Decision** | identity is permanent; only its Current Resolution Procedure evolves; enters catalog by extension (stopping rule) or ARB order | Decision Authority (existence) / Engineer (resolution) | Decision Model; the ReusePotential split provenance note |
| **Qualification Record** | commissioned → executed → verdict (with history — PASS AFTER CORRECTION preserves prior state) → immutable; Findings (F-series) are internal entities, id-scoped to the run | Verification | OQ-ENG-001/002; ES-003.1 |
| **Engineering Plan** | Draft → Approved (the promotion event) → Implemented → Historical; distinct from ephemeral Work Plan | Governance (approval) / Engineer (authorship) | Plan Concept Decision Paper (HISTORICAL, adopted) |
| **Observation Protocol** | ACTIVE → CONCLUDED (confirmed \| falsified \| inconclusive) — has a clock, unlike watch-items | Verification (runs) / DA (disposes) | Project-State-Sync protocol; Artifact Promotion plan |
| **Operational Evidence** | append-only accumulation → consumed at retrospective → may trigger promotion; never edited | the producing track; consumed by DA | session logs; Pattern Evidence Register; R-34 |

*Open aggregate questions (recorded, not answered — answering them is design, which waits):*
- Is an **ADR** part of the Standard aggregate or its own root? Evidence leans separate (own registers, own lifecycle, product/platform split) — but unruled.
- Is a **Verdict** a value object of Qualification Record or an entity (its history suggests entity)?
- Does **Observation** (Class A) belong to Operational Evidence or stand alone until promoted?
- The **Ruling ↔ Standard** relationship: rulings create/amend standards, standards host rules — who owns a hosted rule that a ruling created? (Currently: the standard hosts, the ruling records provenance — consistent in practice, unmodeled formally.)

## Exercise 3 — Ubiquitous-Language Audit

*One meaning per term — collisions and resolutions actually observed:*

| Term | Status | Finding |
|---|---|---|
| **Plan** | ✅ RESOLVED (2026-07-11) | Work Plan (runtime, ephemeral) ≠ Engineering Plan (governed) — the decision paper resolved a genuine six-dimension collision |
| **Architecture Governance** | ✅ RESOLVED (2026-07-12) | renamed from "Architectural Review" — an activity name replaced by a capability name |
| **Operationally Validated** | ✅ RESOLVED (2026-07-12) | glossary-defined once ("exercised successfully under real work, including detection/correction of actual violations — not infallibility") |
| **Qualification** | ⚠️ **COLLISION — open** | two related meanings coexist: (a) the verification activity/instrument (ES-003, OQ runs); (b) a promotion-ladder *stage* (ES-006.1: Research → Pilot → **Qualification** → Standard). Contextually disambiguated today; a cold reader could conflate them. Candidate for a future UL ruling — not resolved here |
| **Evidence** | ⚠️ **overloaded — tolerable** | operational evidence (runtime facts) vs. evidence-citation (traceability references) vs. Pattern Evidence Register (a specific artifact). Usage is consistent within each cluster; cross-cluster reading requires care |
| **Candidate** | ✅ consistent | one meaning across all uses (decision, standard clause, automation, pattern, taxonomy): *awaiting operational evidence before promotion* |
| **AI Architecture / AI Engineering Platform / Engineering Platform / AI Knowledge Platform** | ⚠️ **naming drift — known** | already an ARB observation (rename candidate, "not urgent"); the reconstruction report uses them nearly interchangeably in places. The KnowledgeOS discovery must NOT inherit this drift — its charter uses "AI Knowledge Platform" exclusively for the product-side concept |
| **Backlog** | ⚠️ open hypothesis | projection-vs-workflow-step question is exactly what the active Observation Protocol tests — deliberately unresolved until evidence |
| **Verification vs. Validation** | ✅ sharpened | the glossary distinction (validated ≠ proven) now governs |

## What this document deliberately does not do

No context map. No strategic relationship patterns. No new bounded-context declarations (clusters above are observations). No new standards, decisions, or aggregates *created* — only candidates *observed*. The next step after ARB review is **not** drawing the map — it is letting C3 and the pilot produce the operational evidence that makes the map's relationships conclusions instead of guesses.

---
*Traceability: ARB context-map critique (2026-07-12: "discover the domains that deserve to be on the map" before drawing it) + ARB discovery-vs-commitment ruling (same day). Evidence: ES-001..006 · Decision Model · rulings register · Plan Concept Decision Paper · OQ-ENG-001/002 · verification reports 2026-07-11/12 · session logs. STOP — submitted to the Decision Authority.*
