# ADR-PL-01 — Published Language Evolution: `DeterminationIssued` — Payload Schema Version 2 (adds `ContestedOutcomeRef`)

> **ARB clarification (2026-07-08):** the published business event **remains `DeterminationIssued`** — only its **payload schema** evolves (v1 → **schema_version 2**). There is **no `DeterminationIssuedV2` class**. "v2" throughout this ADR means *payload schema version 2*, per ADR-T5 + the Event Registry.

**Class:** ADR-PL (Published Language) · **Status:** Accepted (ARB-ratified 2026-07-08) · **Driven by:** ADR-UL-01 (per ER-06).
**Contexts:** Adjudication (owns `DeterminationIssued`) · Contestation (owns `ChallengeRaised`) · consumed by Election (PB-004).

## Context
ADR-UL-01 established `ContestedOutcome` + the `ContestedOutcomeRef` VO as Contestation's ubiquitous language, and made explicit that a Challenge/Determination is scoped to exactly one Election. The published integration contracts must now **reflect that language** (not the reverse). `DeterminationIssued v1` (Catalog 50-05) carries `challengeId · outcome · legitimacy · reason · evidenceEnvelopeRef · issuedByAuthority · jurisdiction · finalizedAt` — but not the contested-outcome reference, so a consumer cannot resolve the target Election from the event alone.

## Decision
Evolve the published language to carry the domain concept, versioning per **ADR-T5** (version, never mutate; v1 retained until consumers migrate):

- **`ChallengeRaised`** (Contestation) — **evolved in place** to carry `ContestedOutcomeRef` (replacing the opaque `target` string); **no versioning**. *Rationale (DDD, not repository state):* `ChallengeRaised` is an **internal domain event confined to the Contestation bounded context** — it is **not part of the stable published language** (catalog: Process/Supporting/internal). An internal domain event may evolve in place until explicitly *promoted* to a published integration event; only then does the version-never-mutate rule bind it. *(This holds regardless of how many consumers exist today — the argument is the event's published status, not a consumer count.)*
- **`DeterminationIssued` — Payload Schema Version 2** (Adjudication) — **published integration event** (restricted; consumed cross-context by Election), so it is bound by ADR-T5. The change is **additive and backward-compatible**: add an **optional** `contestedOutcome: ContestedOutcomeRef` (which contains `electionId`) to the payload and **increment `schema_version` to 2**. **Same event, same class** — no `DeterminationIssuedV2`. The hydrator supports **vCurrent (2) + vPrevious (1)**: a v1 payload (no `schema_version`, or `contestedOutcome` absent) hydrates with `contestedOutcome = null`; a v2 payload reconstructs the VO. Determination inherits the Challenge's `ContestedOutcomeRef` (ADR-T14: Challenge read-only during `IssueDetermination`).
- The reference crosses contexts as **strings** (ADR-T16); each context reconstructs its local VO. **Anonymity:** `ContestedOutcomeRef` carries no voter↔vote linkage (`electionId`/`type`/`targetId` only).

Consumers (Election/PB-004) read `electionId` from `contestedOutcome` — **because it is the published language**, not a bolt-on.

## Consequences
- `DeterminationIssued` payload gains `schema_version 2` (optional `contestedOutcome`); the **hydrator supports v2 + v1** (vCurrent+vPrevious). No new event class or name.
- Election (PB-004) consumes `DeterminationIssued` (schema_version 2) and resolves the target Election directly — no read-model, no Messaging change (ARR: Messaging remains consume-only).
- Migration: producers stamp `schema_version 2`; the v1 hydration branch is retained until no v1 payload remains in flight, then retired (ADR-T5, vCurrent+vPrevious).

## Alternatives rejected
- A new `DeterminationIssuedV2` **class/event name** — reserved for *breaking* changes; this change is additive/backward-compatible, so it is a schema-version bump on the same event (Event Registry; Blueprint §12).
- Mutating/repurposing an existing v1 field — violates ADR-T5 (TP-3: never rename/repurpose a property).
- Carry a bare `electionId` field — loses the `ContestedOutcome` concept (would re-introduce primitive obsession at the contract level).

## Sequenced work (implementation — TDD)
1. ✔ Contestation: `TargetRef → ContestedOutcomeRef`; `ChallengeRaised` evolved in place (internal domain event — no version).
2. Adjudication: local `ContestedOutcomeRef` (ADR-T16); `Determination` carries it; `DeterminationIssued` payload **schema_version 2** (optional `contestedOutcome`); hydrator v2+v1; `OutboxEventAdapter` stamps schema_version 2 (RED→GREEN).
3. PB-004 RED (Election consumes schema_version 2).

**Traceability:** ADR-UL-01 · ADR-T5/T14/T16 · Catalog 50-05 · ER-06 · Event Registry.
