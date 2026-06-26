# Failure Strategy (greenfield Core)

**Status:** implementation-facing; high-assurance failure handling for eventing, replay, and decisions. Built on existing outbox (`ProcessOutboxEvents`) + dead-letter (`DeadLetterEntry`). Concise.
**Date:** 2026-06-26

| Failure | Detection | Response | Terminal handling |
|---------|-----------|----------|-------------------|
| **Outbox publish fails** | relay error | retry w/ backoff (event already durable in producer txn) | → `DeadLetterEntry` after N; alert |
| **Inbox/consumer fails mid-handle** | exception | retry; handler idempotent on `EventId` (ADR-T4) | → dead-letter; no partial side-effect (handler txn-wrapped) |
| **Duplicate event** | `EventId` seen in inbox | **drop** (idempotent) | n/a — expected under at-least-once |
| **Out-of-order (causal) arrival** | missing causal precondition | **park** (not fail) until cause arrives (50-05 §3) | timeout → dead-letter + alert |
| **Event version mismatch** | unknown `SchemaVersion` | upcast old→new (ADR-T5 convertibility) | non-convertible ⇒ treat as NEW event; reject + alert |
| **Replay determinism mismatch** | replay hash ≠ recorded (ReplayDeterminismContract) | **halt**; flag divergence; never auto-correct | investigation; integrity incident |
| **Evidence envelope corruption** | `envelopeHash` mismatch | reject read; immutable so cannot repair | integrity incident; quarantine |
| **Invalid determination** (authority/jurisdiction) | `DeterminationAuthorityPolicy` fails | reject issuance (no event emitted) | surfaced to challenger |
| **Challenge timeout** | window expiry (50-07) | transition → `Lapsed` (terminal) | logged; distinct from Dismissed-on-merits |

**Principles:** (1) at-least-once + idempotent = effectively-once; never assume exactly-once. (2) No cross-aggregate compensation (ADR-T8) — each aggregate atomic; correction is forward-only (`ContainedOnly`). (3) Integrity failures (replay/evidence) **halt and alert**, never silently self-heal (high-assurance). (4) Every dead-letter is alertable and replayable for projection rebuild.

---
*Failure Strategy — eventing/replay/decision failures; outbox+inbox+dead-letter; park-not-fail for causal order; halt-not-heal for integrity; forward-only correction.*
