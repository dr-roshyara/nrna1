# ADR-PL-01 — Published Language Evolution: `DeterminationIssued v2` carries `ContestedOutcomeRef`

**Class:** ADR-PL (Published Language) · **Status:** Accepted (ARB-ratified 2026-07-08) · **Driven by:** ADR-UL-01 (per ER-06).
**Contexts:** Adjudication (owns `DeterminationIssued`) · Contestation (owns `ChallengeRaised`) · consumed by Election (PB-004).

## Context
ADR-UL-01 established `ContestedOutcome` + the `ContestedOutcomeRef` VO as Contestation's ubiquitous language, and made explicit that a Challenge/Determination is scoped to exactly one Election. The published integration contracts must now **reflect that language** (not the reverse). `DeterminationIssued v1` (Catalog 50-05) carries `challengeId · outcome · legitimacy · reason · evidenceEnvelopeRef · issuedByAuthority · jurisdiction · finalizedAt` — but not the contested-outcome reference, so a consumer cannot resolve the target Election from the event alone.

## Decision
Evolve the published language to carry the domain concept, versioning per **ADR-T5** (version, never mutate; v1 retained until consumers migrate):

- **`ChallengeRaised`** (Contestation) — **evolved in place** to carry `ContestedOutcomeRef` (replacing the opaque `target` string); **no versioning**. *Rationale (DDD, not repository state):* `ChallengeRaised` is an **internal domain event confined to the Contestation bounded context** — it is **not part of the stable published language** (catalog: Process/Supporting/internal). An internal domain event may evolve in place until explicitly *promoted* to a published integration event; only then does the version-never-mutate rule bind it. *(This holds regardless of how many consumers exist today — the argument is the event's published status, not a consumer count.)*
- **`DeterminationIssued v2`** (Adjudication) — **published integration event** (restricted; consumed cross-context by Election), so it **is** bound by ADR-T5: add `contestedOutcome: ContestedOutcomeRef` (which contains `electionId`) as **v2**, **v1 retained** until consumers migrate. Determination inherits the Challenge's `ContestedOutcomeRef` (ADR-T14: Challenge read-only during `IssueDetermination`).
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
1. Contestation: `TargetRef → ContestedOutcomeRef`; `ChallengeRaised` evolved in place (internal domain event — no version) (RED→GREEN).
2. Adjudication: `Determination` carries `ContestedOutcomeRef`; `DeterminationIssued v2` (RED→GREEN).
3. Register v2 hydrators (Event Registry).
4. PB-004 RED (Election consumes v2).

**Traceability:** ADR-UL-01 · ADR-T5/T14/T16 · Catalog 50-05 · ER-06 · Event Registry.
