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

## Infrastructure failures → Safe Halt (fail-closed, never fail-open)
| Dependency down | Behavior |
|-----------------|----------|
| **Database** | refuse writes; **no vote accepted, no determination issued**; Safe Halt + alert |
| **Outbox store / queue** | events stay durable in producer txn; dispatch retries on recovery (no loss) |
| **Redis (cache/lock)** | degrade reads; **block rebuilds** (lock unavailable); no decision path depends on cache for correctness |
| **Clock (Temporal trust-root)** unavailable/skewed | **refuse time-driven transitions** (no timeout, no window decision); Safe Halt rather than guess time |
| **Storage (evidence)** | `EvidenceRecorded` cannot be produced → **no dependent decision proceeds**; Safe Halt + alert |
> **Safe Halt = fail-closed:** when a trust-critical dependency is unavailable, the system **stops accepting decisions** rather than proceeding on degraded assumptions. Recovery is explicit.

## Domain failures (explicit, not generic 500s)
| Failure | Response |
|---------|----------|
| Challenge against non-existent determination/target | reject at command validation (`ChallengeContentValidation`); no aggregate created |
| Duplicate challenge (same target+raiser within window) | idempotent reject; surface existing `challengeId` |
| Expired mandate used | `DelegationLifecyclePolicy` denies; authorization failure |
| Election expired / wrong lifecycle state | guard denies (`ElectionLifecyclePolicy`) |
| Unknown jurisdiction on route | `ChallengeRoutingDecision` rejects; stays `Admitted` |
| Determination without authority | `DeterminationAuthorityPolicy` denies issuance (no event) |

## Byzantine assumptions (high-assurance posture)
Assume **malicious client, duplicate request, reordered messages, replay attack, clock skew, partial compromise.** Responses: idempotency (dedupe on `EventId`/command id) defeats duplicates/replay; causal ordering + park-not-fail tolerates reorder; clock authority + Safe Halt bounds skew; single-producer + authorization policies bound a partially-compromised actor; integrity halt-not-heal contains tampering. *(Cryptographic E2E verifiability remains deferred — ADR-T13.)*

## Observability (every failure defines all four)
Each failure class emits: **Log** (structured, correlation id) · **Metric** (counter/latency in the 3-category dashboard) · **Alert** (threshold) · **Dashboard** (security/ops). Example: `replay mismatch → IntegrityIncident logged → metric replay_failures++ → alert → Security Dashboard → incident response.`

---
*Failure Strategy — eventing/replay/decision failures; outbox+inbox+dead-letter; park-not-fail for causal order; halt-not-heal for integrity; forward-only correction.*
