# Contestation — Developer Guide

**Audience:** engineers working in the Contestation bounded context (the greenfield Core, with Adjudication).

Contestation owns the **`Challenge`** aggregate — a standing-holder's formal, time-bounded contest of a **`ContestedOutcome`** (an election result or a prior determination). It runs the challenge lifecycle (`Raised → Admitted → Routed → Adjudicated → Resolved`, plus `Dismissed`/`Lapsed`) and publishes challenge domain events. It carries **no voter↔vote linkage** (ADR-T11): the raiser is a standing actor, and what is contested is referenced, never the vote content.

## Guides (per step)
| Guide | Covers |
|-------|--------|
| [`01_contested_outcome.md`](./01_contested_outcome.md) | The **`ContestedOutcome`** concept + **`ContestedOutcomeRef`** Value Object (replacing the former `TargetRef:string`) — what a Challenge contests, and why it is election-scoped. (ADR-UL-01) |
| [`02_reaction_handlers.md`](./02_reaction_handlers.md) | PB-005 Step 5A — the **reaction** to `DeterminationIssued` (`adjudicate`, + Dismissed short-circuit) and `ElectionCorrectionApplied` (`resolve`, correlate by determinationId, premature→park); the **business-condition → inbox-marker** translation boundary. |
| [`03_persistence.md`](./03_persistence.md) | PB-005 Step 5B — the single-source `EloquentChallengeRepository` (Contestation owns the Challenge; no ACL), the mapper as sole translation point, the full-schema `challenges` table, and `findByDeterminationId` as a correlation index (not an identity). |
| [`04_messaging.md`](./04_messaging.md) | PB-005 Step 5C — the messaging integration: outbox adapter for `ChallengeAdjudicated`/`ChallengeResolved`, the two hydrators, inbox-registry wiring, and the F-2 `resolution` enrichment seam (`ChallengeResolvedIntegration`) supplied by the Application; domain event stays minimal. |
| [`05_challenge_routed_published_language.md`](./05_challenge_routed_published_language.md) | **WP-3A (ADR-T21):** `ChallengeRouted` becomes **published language** — publication (outbox, schema v1) **and** registration (hydrator) together; provenance supplied, never minted here (Correlation Origin Relocation deferred: it depends on a routing application service). |
| [`06_raise_path_and_conversation_origin.md`](./06_raise_path_and_conversation_origin.md) | **WP-5 + WP-3B (ARB Option B):** the raise path (`raise` / `admit` / `route`) as application services, and the **integration conversation origin** minted at `route` -- distinct from the **business process origin** at `raise`. Publication policy (only integration events reach the outbox), two context-local ports (R-1/R-2 forbid importing Adjudication's), and the mint allowlist's two deliberate originators. |

## Authoritative architecture
- **Ubiquitous language / concept:** `docs/adr/ADR-UL-01-ContestedOutcome.md` (concept, glossary, ownership).
- **Published language:** `docs/adr/ADR-PL-01-DeterminationIssued-v2.md` (how the concept crosses to Adjudication/Election).
- **Aggregate / lifecycle:** Round 50-07 v1.2 · ADR-T20 (`Adjudicated ≠ Resolved`).

## Invariants you must preserve
- **Anonymity (ADR-T11):** nothing here may link a voter to a vote. A `ContestedOutcome` references a Result or Determination — never a vote or voter.
- **Illegal-transition policy:** forbidden state transitions throw with **no mutation**; the caller audits the attempt.
- **Injected time (Principle 9):** no `now()`/`Carbon` in the domain — time is passed in.
