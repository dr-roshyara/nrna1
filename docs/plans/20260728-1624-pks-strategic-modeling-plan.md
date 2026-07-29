# PKS Phase II — Strategic Modeling Plan

| | |
|---|---|
| **Kind** | Engineering Plan (governed EP-01 deliverable, ES-004.2) — the plan for executing the Strategic Modeling phase the ARB authorized. **Contains no modeling**: no bounded context is drawn, no concept validated, no collision resolved here. |
| **Authority** | Generated — never authoritative without human review. |
| **Status** | **APPROVED — EP-01 gate #1 passed (PA/DA, 2026-07-28: "Yes." + "Phase II Plan has been approved" in the M0+M1 execution commission).** Cadence remains as proposed unless the ARB adjusts at a checkpoint. *(Supersedes PROPOSED.)* |
| **Commission** | ARB ruling DR-3, 2026-07-28 (`docs/implementation/PKS_Phase_I_ARB_Rulings.md`): Strategic Modeling AUTHORIZED, unconditional — authorization is a phase grant, not a plan; this document is the plan. Commission seed + PA enrichments: session log 2026-07-28 (final entries). |
| **Placement** | `docs/plans/` per ES-004.2 (governed engineering plan). Modeling artifacts it produces will live in `docs/implementation/` beside their Phase-I inputs and the EPIC-002 precedents. |

---

## Objective

Produce the accepted strategic model of the PKS domain from the accepted Phase I baseline: **validated concept set · ubiquitous language · domain classification · bounded contexts · context map** — resolving exactly the modeling-routed queue, and **surfacing (never resolving)** governance questions.

## Background

PKS Phase I (evidence discovery + candidate conceptual model + converged synthesis + methodology capture) was ACCEPTED and Strategic Modeling AUTHORIZED by the ARB rulings DR-1..DR-6 of 2026-07-28. The accepted work queue, the governance boundary, and the scope guard are all on record; this plan operationalizes them. Precedent: the EPIC-002 strategic chain (Concept Register → Decomposition Evaluation → Bounded Context Discovery → Readiness → Canonical Context Map → Relationship Pattern Selection), each stage ARB-reviewed — this plan reuses that proven shape rather than inventing one.

## Scope

**In scope (the accepted DR-3 queue, exactly):**
- Candidate-set validation — 17 observed concepts (K-1..K-17) vs the 7 primitives + 3 composites, under the portability filter
- Ubiquitous Language definition
- Collision resolutions C-1..C-4
- OQ-PKS-1 (identity scheme) · OQ-PKS-5 (evolution canon) · OQ-PKS-6 (whole-system completeness, jointly with C-4)
- Knowledge-System Conformance kind decision (A / B / C)
- OQ-CM-1..4 (hypothesis-conditioned — live only where their underlying candidates survive validation)
- Core / supporting / generic domain classification · bounded context discovery · context map · relationship patterns

**Explicitly out of scope:**
C4 diagrams · software architecture · implementation architecture · deployment · APIs · technology choices *(PA scope guard)* · resolving OQ-PKS-2 / 3 / 4 / 7 / 9 / 10 / 11 *(ARB-bucket: surfaced, never resolved — rulings §3)* · adopting the methodology candidate *(DR-4: recorded, not adopted; its instruments — portability filter, per-claim confidence — are used as recorded in the accepted review, without methodology adoption)* · releasing the HELD readiness assessment *(PA-commission path only)* · the Capabilities Pass *(separately commissioned, non-gating; if scheduled, its outputs feed M1/M6 but gate nothing)*.

## Design decisions

1. **Incumbent-independent work first.** OQ-PKS-2 (incumbent) is ARB-owned and undisposed; the WBS front-loads everything that does not depend on it (M0–M4) and localizes the expected surfacing to M6.
2. **Validation before boundary-drawing.** Bounded contexts are discovered only from concepts that survived M1 — never from candidates that might be rejected.
3. **Classification, discovery, and mapping are separate work packages** (PA direction at work-plan approval): context mapping depends on BC discovery stabilizing first, and bundled deliverables make reviews harder.
4. **Surfacing protocol** (governance boundary, operationalized): when a WP hits an ARB-bucket question, record it in the **Surfacing Register** (question · where it bit · what modeling cannot proceed on without it · options as classification only), continue all unblocked work, present the register at the next checkpoint. Never decide; never silently assume.
5. **Polarity used as observed evidence only** — the authoritative/informational distinction informs relationship analysis as an evidenced phenomenon; whether it is a *rule* stays OQ-PKS-4, ARB-owned.
6. **Execution modes** (PA guidance): Plan Mode for decision-bearing milestones (M1, M2, M3, M6, M7); Auto acceptable for mechanical follow-through after approval. Fresh session per milestone or reviewed batch.

## Work breakdown (M0–M8)

| WP | Deliverable | Resolves | Key inputs | Acceptance criterion |
|---|---|---|---|---|
| **M0** | Frame reconciliation note + UL glossary seed | II.A–D frame vs candidate model's phase table (III→IV→V) reconciled explicitly; glossary seeded from K-1..K-17 with vocabulary risks V-1..V-6 attached | Rulings record · both phase frames · Item 1 §1.2 | One frame adopted for Phase II with the mapping to the other recorded; every K-concept has a glossary entry with evidence citation |
| **M1** | **PKS Concept Register** (EPIC-002_Concept_Register precedent) | Candidate-set validation, 17 vs 7+3; per-candidate verdict validated / rejected / deferred, portability filter applied per concept | Item 1 §1.2 · Item 2 §1.2 · ES-005.3 litmus generalized per the accepted review | No verdict without cited evidence; **the filter must demonstrably discriminate** (a filter that rejects nothing is presumed ceremonial — Methodological Fitness Rule); rejected candidates preserved with rationale |
| **M2** | Collision resolutions | C-1 (Requirement trichotomy) · C-2 (merge/invalidation) · C-3 (constraint ≠ invariant) · OQ-5 (evolution canon) · **C-4 + OQ-6 as one item** (the five dimensions ARE the candidate answer to the completeness question) | Synthesis §4 · Item 1 Q4/Q8 · Item 2 §4/§6 | Each resolution states decision · evidence · rejected alternative · confidence; anything requiring new governance → surfaced, not decided |
| **M3** | Conformance-kind decision (A / B / C) | The deliberately-open kind question | M1+M2 outputs · synthesis §3.3/§4 | Falsifiable choice with recorded reversal condition (SR-4, Synthesized-class abstraction risk) |
| **M4** | Identity & lifecycle model | OQ-PKS-1 · surviving OQ-CM-1..4 | M1 verdicts · Item 1 §1.1/Q5 | Hypothesis-conditioned questions whose candidates died in M1 are closed-as-moot **with a record**, never silently dropped |
| **M5** | Domain classification | Core / supporting / generic classification of validated clusters | M0–M4 outputs | Every classification traces to evidence, with rationale |
| **M6** | **Bounded Context Discovery** | BC candidates from evidence seams | M5 · Item 1 Q3/Q6 seams · EPIC-002 BC-discovery precedent | Every BC traces to evidence clusters, not imported taxonomy; **expected surfacing point for OQ-2 (incumbent) and OQ-7 (three roots)** |
| **M7** | **Context Map** + relationship patterns | Context relationships (design decision 5 applies) | M6 stabilized · EPIC-002 context-map + relationship-pattern precedents | Map drawn only over BCs that survived M6 review |
| **M8** | Strategic Modeling Report → **ARB output review (gate #2)** | Assembles M0–M7 · presents the Surfacing Register | All prior WPs | ARB acceptance |

## Review cadence (proposed — the ARB decides)

**Checkpoints after M1 · M2+M3+M4 (batched) · M5+M6 (batched, so BC discovery stabilizes under review before mapping) · M7 · M8.** Matches the house rhythm *produce → review → refine → freeze → next* and EPIC-002 precedent. Alternative — a single review at M8 — is available but carries the stated risk: a wrong turn at M1 undetected until the end invalidates everything downstream.

## Progress

Not started. This plan awaits EP-01 gate #1.

## Risks

- **SR-1 wrong-incumbent** — mitigated by sequencing + the surfacing protocol; not resolved (resolution is the ARB's, OQ-2)
- **SR-3 ceremonial portability filter** — targeted directly by M1's acceptance criterion
- **SR-4 Synthesized-abstraction risk** — targeted by M3's reversal-condition requirement
- **V-1 lifecycle-vocabulary fragmentation** — M4 handles the modeling side; the kind→vocabulary *governance* mapping stays OQ-9, surfaced

## Open questions

1. Review cadence — proposed above; ARB decides at gate #1.
2. M0's frame reconciliation outcome — which phase frame governs Phase II — is itself a deliverable, deliberately not pre-decided here.

## Next actions

1. **EP-01 gate #1: explicit ARB/DA approval of this plan** (human act; per-item, recorded).
2. On approval: open M0+M1 in a fresh session (Plan Mode), per the execution-mode guidance.

---

*Traceability: commissioned by ARB ruling DR-3 (2026-07-28, `docs/implementation/PKS_Phase_I_ARB_Rulings.md`) · commission seed + PA enrichments + scope guard: `.claude/sessions/2026-07-28.md` (final entries) · inputs: the four ACCEPTED Phase-I artifacts (`docs/implementation/Strategic_DDD_Discovery_Product_Knowledge_System_*`, `Strategic_Discovery_Methodology_Candidate.md`) + dossier §4 & §4a · WBS precedent: the EPIC-002 strategic chain · M5-split direction: PA at work-plan approval, 2026-07-28 · work plan: `.claude/plans/shiny-hopping-nest.md`. **STOP — no modeling before gate #1 approval.***
