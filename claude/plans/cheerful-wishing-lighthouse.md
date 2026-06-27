# Plan — Push B: close the greenfield correction loop (Contestation side)

## Context
Push A made Adjudication's write-path real (`DeterminationIssued` → outbox). Per the ARB domain decision (**ADR-T20: `Adjudicated` ≠ `Resolved`**), Push B closes the **greenfield half** of the correction loop: a `DeterminationIssued` event drives the `Challenge` `Routed → Adjudicated`, emitting **`ChallengeAdjudicated`** (legal finality). The **operational half** (`Resolved`/`ChallengeResolved`, on `ElectionCorrectionApplied`) is **Push C** — it integrates the **legacy** Election (`app/Models/Election.php`). Exploration found: cross-context consumers use the generic **`IntegrationEvent`** (payload array via `OutboxEventProcessor`); there is **no generic inbox**; and **Contestation has no persistence yet** (deferred from Push A). So Push B = apply the reference pattern to **Contestation** + an inbox + the `adjudicate` consumer.

## Discipline — TDD-first (mandatory, all code phases)
Every code change follows **RED → GREEN → REFACTOR → architecture fitness tests → PHPStan(max) → (Infection when wired) → review gates** (Constitution §8). Concretely: **write the failing test first**, confirm it's red, then the minimal code to green — for the aggregate (`adjudicate()` test before the method), the inbox, the consumer, **and the integration test (write it first; it's red until the consumer + wiring exist)** — exactly as Push A was done. No production code without a failing test that demanded it.

## Phase B1 — Architecture (decisions to freeze; no code)
- **ADR-T17 — LegitimacyDecision placement (RESOLVE):** it is an **Adjudication *domain service*** (not application, not the aggregate). For now legitimacy stays a **command input** produced by that service at the boundary; the coordinator never reasons about it. Real rules = deferred (TDD when they exist). Create only the domain-service **seam** (interface) in Push B if convenient; otherwise record the decision.
- **ADR-T20 — Challenge lifecycle: Adjudicated ≠ Resolved (DOMAIN DECISION, made by ARB):** distinguish **legal finality** from **operational completion**. New Challenge lifecycle:
  `Raised → Admitted → Investigating → Routed → **Adjudicated** → **Resolved**` (terminal branches Dismissed/Lapsed unchanged).
  - **`Adjudicated`** = a binding determination exists (set on `DeterminationIssued`); emits **`ChallengeAdjudicated`** (NEW event, Contestation-owned).
  - **`Resolved`** = every required consequence has completed (set on `ElectionCorrectionApplied`); emits `ChallengeResolved`.
  - Rationale (high-trust analogues): judgment *entered* ≠ judgment *executed*; assessment *issued* ≠ *collected*. Avoids both A's loss of operational distinction and B's cross-context lifecycle coupling — Contestation records two **independent facts**, never waits synchronously.
  - **Clean split:** **Push B implements through `Adjudicated`/`ChallengeAdjudicated`** (greenfield, on `DeterminationIssued`); **Push C implements `Resolved`/`ChallengeResolved`** (on `ElectionCorrectionApplied`, legacy Election).
  - **`Investigating`** (Admitted→Investigating→Routed) is included per the ARB lifecycle; its application trigger is **modeled now, driven later** (no consumer needs it in Push B).
- **Frozen-artifact updates this requires (B1, before code):** update **50-07** (add `Adjudicated`+`Investigating` states, `ChallengeAdjudicated` event, transition tables) · add **`ChallengeAdjudicated`** to the **Canonical Event Catalog v1.0** (Process, Contestation, internal) · record **ADR-T20** · Architecture Review · Traceability. The **Push A `Challenge` aggregate changes** (additive `adjudicate()` Routed→Adjudicated; `resolve()` guard Routed→**Adjudicated**) are authorized by ADR-T20.
- **Event ownership (confirm, Catalog v1.0):** `DeterminationIssued`→Adjudication, `ChallengeResolved`→Contestation, `ElectionCorrectionApplied`→Election. **`ElectionCorrectionApplied` is DEFINED (event class + contract) in Push B but IMPLEMENTED (the Election reaction) in Push C** — the architecture exists before the legacy integration.
- **Cross-context consumption:** Contestation listens to the **`IntegrationEvent`** with `event_type='Challenge…'`… no — it consumes **`DeterminationIssued`** via the IntegrationEvent (payload array: `challengeRef`, `determinationId`, `outcome`). Matches the Finance cross-context convention (`CreateIncomeFromFeePaidProjection`).
- **Inbox/dedupe (ADR-T4 realized):** introduce a **generic `processed_inbox`** table (`event_id` + `consumer` + `processed_at`); each greenfield consumer checks-and-marks before acting (at-least-once ⇒ needed because emitting `ChallengeResolved` is not naturally idempotent). Generalizes the governance `processed_events` pattern.
- **One-aggregate-per-transaction:** the resolve consumer writes **only** the `Challenge` (+ its outbox row). Failure: outbox retry + inbox dedupe; park-not-fail if the Challenge isn't yet `Routed` (causal wait); halt-not-heal on integrity; dead-letter after N.

## Phase B2 — Domain (TDD-first; authorized by ADR-T20)
- **`Challenge` aggregate (additive):** add `Adjudicated` state; add **`adjudicate(DeterminationId, at)`** (guard `Routed` → `Adjudicated`, records **`ChallengeAdjudicated`**); change `resolve()` guard `Routed → Adjudicated` (its consumer is Push C); add `Investigating` state (+ transition) modeled but undriven. New event `ChallengeAdjudicated` (final readonly). TDD: adjudicate from Routed emits one `ChallengeAdjudicated`; forbidden from non-Routed; resolve now requires `Adjudicated`.
- (ADR-T17 seam) optional `Adjudication/Domain/.../LegitimacyDecision` interface (placeholder) — decision recorded regardless.

## Phase B3 — Infrastructure + Application (apply Baseline 1.1 to Contestation)
- **Contestation persistence:** `EloquentChallengeRepository` + `ChallengeModel` + `challenges` migration (state + raiser/target/content refs; `BelongsToTenant`) + `ChallengeMapper` + `Challenge::reconstitute()` (additive, like Determination).
- **Inbox:** `processed_inbox` migration + a small `Inbox` port/adapter (`alreadyProcessed(eventId,consumer)` / `markProcessed`).
- **Consumer:** `AdjudicateChallengeOnDeterminationIssued` listener (consumes `IntegrationEvent`/`DeterminationIssued`): dedupe via inbox → load Challenge by `challengeRef` → if `Routed`, `adjudicate(determinationId)` → `Adjudicated` → save (one txn) → enqueue **`ChallengeAdjudicated`** to outbox. Idempotent + parks if not yet `Routed`.
- **Wiring:** `ContestationServiceProvider` (bind repo/outbox/inbox; register listener in `EventServiceProvider`); add `ChallengeAdjudicated` cases to `OutboxEventProcessor::hydrateDomainEvent()` and a Contestation `OutboxEventAdapter`.
- **Tests (RefreshDatabase, real DB + outbox):** end-to-end — persist a `Routed` Challenge; issue a Determination (Push A path) → `DeterminationIssued` in outbox; dispatch it → Challenge becomes **`Adjudicated`**, exactly one **`ChallengeAdjudicated`** enqueued, inbox marked; **re-dispatch idempotent** (no double adjudicate/event); `Dismissed` determination also adjudicates. Failure: redelivery dedupe; not-yet-`Routed` parks.
- Re-run greenfield fitness + PHPStan max.

## Push C (separate — NOT in Push B): legacy Election correction → Resolved
Election reacts to `DeterminationIssued(Upheld)` → applies a **ContainedOnly** correction to the legacy Election (forward-only; never un-casts votes) → emits `ElectionCorrectionApplied`. **Then Contestation consumes `ElectionCorrectionApplied` → `Challenge.resolve()` (`Adjudicated → Resolved`) → `ChallengeResolved`** — closing the operational half. Isolated because it integrates legacy `app/Models/Election.php`. (`ElectionCorrectionApplied` event is *defined* in Push B, *implemented* here.)

## Sequence (per review — inbox before consumer)
Persistence → **Inbox** → **Consumer** → Outbox → Integration tests → Architecture tests. *(Consumers are meaningless without guaranteed idempotency, so the inbox lands first.)*

## Architecture Baseline 1.2 (promote after Push B)
After Push B the **Contestation** vertical (Aggregate → Repository Port → Eloquent Repository → Mapper → Outbox → **Inbox** → Architecture tests → Integration tests) becomes **Architecture Baseline 1.2** — the canonical reference every future bounded context copies (supersedes 1.1 by adding the consumer/inbox half of the pattern).

## Reference Architecture doc = THE implementation handbook
`docs/implementation/Reference_Architecture_v1.0.md`: the concise handbook every future BC **literally copies** — canonical event flow, BC list, aggregate ownership, repository ports, transaction decorator, outbox + **inbox**, fitness tests, and the defining ADRs. Companion to Baseline 1.2.

## Verification
`php vendor/bin/phpunit tests/Feature/Contexts/Contestation tests/Unit/Contexts/Contestation tests/Architecture/GreenfieldCoreArchitectureTest.php` → green. Assert: Challenge `Adjudicated`; exactly one `ChallengeAdjudicated` in `outbox_events`; inbox row present; re-dispatch idempotent (counts unchanged); not-yet-`Routed` parks (no adjudicate). PHPStan max clean.

## Files (representative)
**B1 frozen-doc updates:** `Round50-07` (states/events/tables), `Canonical_Event_Catalog_v1.0` (+`ChallengeAdjudicated`), ADR-T log (ADR-T17 resolved, **ADR-T20**), Traceability Matrix.
**Domain:** `Challenge` (+`adjudicate()`, +`Adjudicated`/`Investigating` states, +`reconstitute()`), new `Domain/Events/ChallengeAdjudicated.php`; (Push B) define `app/Domain/Election/Events/ElectionCorrectionApplied.php` (impl in Push C).
**Contestation Infra/App:** `EloquentChallengeRepository`, `ChallengeModel`, `ChallengeMapper`, `challenges` migration, `Application/Listeners/AdjudicateChallengeOnDeterminationIssued`, `Infrastructure/Outbox/OutboxEventAdapter`, `Infrastructure/Providers/ContestationServiceProvider`.
**Shared inbox:** `processed_inbox` migration + Inbox port/adapter.
**Edits:** `OutboxEventProcessor` (hydrate `DeterminationIssued`/`ChallengeAdjudicated`), `EventServiceProvider`, `config/app.php`.
**Docs:** Reference Architecture v1.0 (handbook); promote Contestation reference → **Architecture Baseline 1.2**.
