# C4 — Code Level (implemented aggregates)

**Diagrams:** [`plantuml/Code_Challenge.puml`](plantuml/Code_Challenge.puml) · [`plantuml/Code_Determination.puml`](plantuml/Code_Determination.puml)
**Derived from:** class-by-class code inventory (2026-07-06) · Round 50-07 state machines · ADR-T14/T16/T19/T20 · Package Structure conventions

## Explanation
Code diagrams exist ONLY for the two implemented aggregates (Challenge, Determination), exactly as the prompt requires. Each shows the aggregate root with full public API, value objects, domain events, repository interface, exceptions, and (for Determination) the application service chain and infrastructure adapters. No framework classes appear; the Eloquent model/mapper are shown on the Infrastructure side of the Determination diagram only, clearly stereotyped.

## Load-bearing details captured
- **Local VO twins:** Contestation's `DeterminationId` ≠ Adjudication's `DeterminationId`; Adjudication's `ChallengeRef` ≠ Contestation's `ChallengeId` (ADR-T16) — highlighted yellow with notes, because this is the #1 thing a new developer gets wrong.
- **ADR-T20 split** on the Challenge diagram: `Adjudicated` (legal finality) ≠ `Resolved` (operational completion).
- **ADR-T19 Model B** on the Determination diagram: ruling content lives in the event, not aggregate state.
- Silent transitions: `lapse()` and `beginInvestigation()` emit no event — by frozen design, noted so nobody "fixes" it.

## Assumptions
None — this level is drawn 1:1 from code. New aggregates get code diagrams when their PB tickets reach Implemented (per the frozen doc hierarchy, that update accompanies the ticket's documentation step).
