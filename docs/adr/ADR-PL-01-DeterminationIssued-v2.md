# ADR-PL-01 — Published Language Evolution: `DeterminationIssued v2` carries `ContestedOutcomeRef`

**Class:** ADR-PL (Published Language) · **Status:** Accepted (ARB-ratified 2026-07-08) · **Driven by:** ADR-UL-01 (per ER-06).
**Contexts:** Adjudication (owns `DeterminationIssued`) · Contestation (owns `ChallengeRaised`) · consumed by Election (PB-004).

## Context
ADR-UL-01 established `ContestedOutcome` + the `ContestedOutcomeRef` VO as Contestation's ubiquitous language, and made explicit that a Challenge/Determination is scoped to exactly one Election. The published integration contracts must now **reflect that language** (not the reverse). `DeterminationIssued v1` (Catalog 50-05) carries `challengeId · outcome · legitimacy · reason · evidenceEnvelopeRef · issuedByAuthority · jurisdiction · finalizedAt` — but not the contested-outcome reference, so a consumer cannot resolve the target Election from the event alone.

## Decision
Evolve the published language to carry the domain concept, versioning per **ADR-T5** (version, never mutate; v1 retained until consumers migrate):

- **`ChallengeRaised v2`** (Contestation) — carries `ContestedOutcomeRef` (replacing the opaque `target` string). *(v1 retained.)*
- **`DeterminationIssued v2`** (Adjudication) — adds `contestedOutcome: ContestedOutcomeRef` (which contains `electionId`). Determination inherits the Challenge's `ContestedOutcomeRef` (ADR-T14: Challenge read-only during `IssueDetermination`). *(v1 retained.)*
- The reference crosses contexts as **strings** (ADR-T16); each context reconstructs its local VO. **Anonymity:** `ContestedOutcomeRef` carries no voter↔vote linkage (`electionId`/`type`/`targetId` only).

Consumers (Election/PB-004) read `electionId` from `contestedOutcome` — **because it is the published language**, not a bolt-on.

## Consequences
- Adjudication + Contestation event schemas gain v2; hydrators register v2 alongside v1 (Event Registry; vCurrent+vPrevious rule).
- Election (PB-004) consumes `DeterminationIssued v2` and resolves the target Election directly — no read-model, no Messaging change (ARR: Messaging remains consume-only).
- Migration: producers emit v2; v1 handlers retained until no v1 in flight, then retired (ADR-T5).

## Alternatives rejected
- Mutate `DeterminationIssued v1` in place — violates ADR-T5 (events version, never mutate).
- Carry a bare `electionId` field — loses the `ContestedOutcome` concept (would re-introduce primitive obsession at the contract level).

## Sequenced work (implementation — TDD, next)
1. Contestation: `TargetRef → ContestedOutcomeRef`; `ChallengeRaised v2` (RED→GREEN).
2. Adjudication: `Determination` carries `ContestedOutcomeRef`; `DeterminationIssued v2` (RED→GREEN).
3. Register v2 hydrators (Event Registry).
4. PB-004 RED (Election consumes v2).

**Traceability:** ADR-UL-01 · ADR-T5/T14/T16 · Catalog 50-05 · ER-06 · Event Registry.
