# PB-005 (Contestation Reaction) — Phase 1: Strategic DDD Ownership Review

**Status:** Discovery (Phase 1). **No implementation.** Produced for ARB review; the IDD (Phase 2) follows only after the open questions below are ruled. **2026-07-08.**
**Milestone:** M2 — "the constitutional correction loop closes end-to-end."

---

## 1. Business need / context responsibilities
When an adjudication issues a binding determination on a contested outcome, and the Election applies (or the challenge is dismissed), the **Challenge** must record its terminal facts and close. PB-005 gives the Contestation context its **Application + Infrastructure** so the already-modelled `Challenge` aggregate *reacts* and closes the loop.

- **Contestation owns the Challenge aggregate** — its lifecycle, state, identity, and its events. The Domain is already implemented (`Challenge` with `adjudicate()`/`resolve()`, `ChallengeState`, `ChallengeRepository` interface, events). **Application + Infrastructure are empty (greenfield to build); no ServiceProvider; not in `config/app.php`.**

## 2. Ownership table
| Concern | Owner | Note |
|---|---|---|
| `Challenge` aggregate + lifecycle + identity | **Contestation** | Domain modelled; Contestation *owns* it (unlike PB-004, where Election reacted to a **legacy-owned** election). |
| Challenge **persistence** (`challenges` table, `EloquentChallengeRepository`) | **Contestation (greenfield)** | Single source of truth — Contestation owns the aggregate, so **no ACL, no Strangler, no multi-source reconstruction**. |
| Reaction decisions (`adjudicate`, `resolve`) | **Contestation domain** | Handlers *react*; the aggregate *decides* (state guards). |
| `ChallengeAdjudicated`, `ChallengeResolved` (events) | **Contestation** (producer) | Catalog: Process · internal · consumers AdjudicationService + Audit. |
| Consumed: `DeterminationIssued` | **Adjudication** (producer) | Contestation consumes → `adjudicate()`. |
| Consumed: `ElectionCorrectionApplied` | **Election** (producer) | Contestation consumes → `resolve()`. |
| Transaction boundary (ADR-T1) | **Inherited from `Inbox::consume()`** | as in PB-004 — no Contestation TransactionManager. |
| Tenant scope | Infrastructure boundary (`BelongsToTenant`) | domain tenant-free (ADR-T16). |

## 3. Reaction model (per ADR-T20 / ADR-T14 / Round50-07)
- **`DeterminationIssued` → `Challenge::adjudicate(DeterminationId, at)`** — Routed→**Adjudicated** (legal finality), emits `ChallengeAdjudicated`.
  - **Dismissed short-circuit (T5'):** on a *Dismissed* determination the Election stays silent, so Contestation also calls `resolve()` in the same reaction → emits `ChallengeResolved` (caused by `DeterminationIssued`).
- **`ElectionCorrectionApplied` → `Challenge::resolve(DeterminationId, at)`** — Adjudicated→**Resolved** (operational completion), emits `ChallengeResolved` (Upheld path).
- **Guard:** `resolve()` requires state `Adjudicated`; the domain throws `IllegalChallengeTransition` otherwise (unit-tested). So an `ElectionCorrectionApplied` that arrives **before** its `DeterminationIssued` (out-of-order) cannot resolve yet.

## 4. Candidate invariants
- **Two independent facts, never a synchronous wait (ADR-T20):** `Adjudicated` (legal finality) ≠ `Resolved` (operational completion); Contestation records each on its own event, never blocking on another context.
- **Forward-only, no compensation (ADR-T8):** terminal states are terminal; no saga/rollback.
- **Causal, not global ordering (Round50-05):** out-of-order delivery is *parked and re-driven*, never failed.
- **Anonymity (ADR-T11):** the Challenge references standing/refs, never a voter identity.
- **Tenant-free domain (ADR-T16):** local VOs reconstructed from wire strings at the Infrastructure boundary.

## 5. Inherited assumptions from PB-004 — what transfers, what does NOT
**Transfers (reuse as evidence-justified decisions):**
- Messaging integration shape: outbox adapter (`match(true)`), inbox handler(s) + registry wiring, hydrators — registered in a `ContestationServiceProvider`.
- Inbox-inherited atomicity (no own TransactionManager); tenant via `BelongsToTenant`; injected `ClockInterface` for reaction timestamps; RED-matrix + Feature-test harness discipline (unique org per test; tenant-scoped assertions).

**Does NOT transfer (explicitly):**
- **The ACL / `ElectionExistencePort` / Aggregate-Reconstruction / Strangler pattern.** Contestation **owns** the Challenge, so it uses a **normal single-source greenfield repository** (`EloquentChallengeRepository`) — there is no legacy source and no existence bridge. *This is the key "why it does not fit" finding.*
- **The explicit idempotency ledger.** The Challenge has intrinsic **state**, so idempotency is via inbox dedupe + aggregate state guards, not a separate ledger.

**Genuinely new vs PB-004:**
- **Two inbound events / two reactions** (adjudicate + resolve) — PB-004 had one.
- **Parking (`CausalPreconditionMissing`)** — parking is **not** new (it is an existing Messaging Platform capability, PB-003). What is new: **Contestation becomes the first *reacting* bounded context that intentionally uses parking as part of its business workflow** (PB-004 never parked; its "unknown" was permanent).
- **Dismissed short-circuit** — one reaction performing two transitions.

## 6. Open architectural questions (ARB ruling requested BEFORE the IDD)
1. **Scope of the raise→admit→route path.** The reaction needs a **Routed** Challenge to adjudicate, but Contestation has no persistence and no command path to *reach* Routed. Is PB-005 the **reaction + infra only** (Challenges assumed already persisted/Routed; tests seed a Routed Challenge), with the raise/admit/route command path a separate concern? Or is that command path in PB-005 scope? (Epic: "Contestation Reaction + infra", XL.)
2. **Out-of-order → park.** Confirm the `ResolveChallenge` reaction throws **`CausalPreconditionMissing`** (→ inbox parks + re-drives) when the Challenge is not yet `Adjudicated`, rather than treating it as an error. (Adapts PB-003 parking; new in a reaction.)
3. **Idempotency classification.** On a re-delivery whose transition is already applied (e.g. `DeterminationIssued` when already `Adjudicated`), should the handler classify it **`IdempotentReplay`** (Processed, no-op) rather than let the aggregate throw `IllegalChallengeTransition`? (Inbox dedupe covers exact `event_id` repeats; this is about semantically-superseded re-deliveries.)
4. **Dismissed short-circuit confirmation.** Confirm: on `DeterminationIssued` with `outcome=Dismissed`, the reaction performs `adjudicate()` **and** `resolve()` in one transaction (emitting both events); on `Upheld`, `adjudicate()` only (resolve awaits `ElectionCorrectionApplied`). The handler branches on `outcome`.
5. **Reaction timestamps.** Confirm `ChallengeAdjudicated`/`ChallengeResolved` `occurredAt` = **application time** (injected clock at reaction), consistent with the PB-004 Q1 ruling.
6. **Frozen-catalog inconsistencies (governance).** Two: (a) the `DeterminationIssued` consumer row (Round50-05 §2) omits **Contestation**, contradicting ADR-T14/T20; (b) `ChallengeAdjudicated` has **no payload contract** in Round50-05 §1 (added later by ADR-T20). The Canonical Event Catalog is **FROZEN**. Ruling needed: correct via versioned re-issue + ADR now, or record as a finding and proceed on ADR-T14/T20 authority (deferring the catalog fix)?
7. **`ChallengeResolved` payload mismatch.** Round50-05 §1 specifies `ChallengeResolved` payload = `challengeId · resolution · determinationId · at` — but the **domain event carries no `resolution` field** (only ChallengeId, DeterminationId, occurredAt). Does `ChallengeResolved` need a `resolution` value (e.g. the Upheld/Dismissed outcome), which would require a domain-event change — or is the catalog's `resolution` field to be dropped? (Domain change ⇒ ARB decision.)

---

*Phase 1 output only. No IDD, no code. Awaiting ARB rulings on the seven questions before producing the PB-005 IDD (Phase 2).*
