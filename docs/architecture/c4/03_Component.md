# C3 — Components (one diagram per bounded context)

**Diagrams:**
- [`plantuml/Component_Contestation.puml`](plantuml/Component_Contestation.puml) — domain IMPLEMENTED; app/infra PLANNED (PB-005, Gap G-4)
- [`plantuml/Component_Adjudication.puml`](plantuml/Component_Adjudication.puml) — fully IMPLEMENTED (Push A + PB-001)
- [`plantuml/Component_Election.puml`](plantuml/Component_Election.puml) — operational core EXISTS; correction reaction PLANNED (PB-004, Gap G-3)
- [`plantuml/Component_Voting_Evidence_Appointment.puml`](plantuml/Component_Voting_Evidence_Appointment.puml) — DESIGNED only (Round 50; strangler migration pending)

**Derived from:** verified code inventory (2026-07-06) · Round 50-01/02/05/06/07 · Blueprint §10 · ADR-T14/T16/T17/T19/T20 · Traceability Matrix

## Explanation
Every diagram identifies the DDD rule set the prompt requires: bounded context, aggregate, the three layers, repository (port in Domain, implementation in Infrastructure), events, policies, ports/adapters, outbox/inbox touchpoints, and dependency direction (always inward — Eloquent never appears in Domain).

Implementation status is tagged per element: **IMPLEMENTED** components show concrete class names; **PLANNED** components show the approved design (Blueprint/IDD) awaiting its PB ticket; **DESIGNED** contexts show the frozen Round 50 model awaiting migration.

## Assumptions
1. Election/Lifecycle is drawn as a context for the correction loop even though BDR classifies the lifecycle engine as a supporting capability — the correction reaction (PB-004) gives it a producing role in the Catalog (`ElectionCorrectionApplied`). Flagged in README as a naming nuance, not an inconsistency.
2. Read models (Results, Legitimacy projections) are named but not diagrammed as components — they are designed, unimplemented, and intentionally not aggregates.

## Rationale
The component level maps one-to-one with the DDD model on purpose (audit doc `DDD_Completeness_And_Domain_Model_Catalogue.md`): C4 components = context internals, DDD stays the semantic authority. Nothing here introduces a class that does not exist in code or in a frozen design document.
