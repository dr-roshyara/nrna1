# 08 — EventProvenance: the constitutional audit chain (PB-006 6B-1 · ADR-MP-06)

## Constitutional Audit Invariant

> A constitutional decision is not represented by a single event but by a chain of causally related events. Every event belongs to exactly one constitutional conversation (CorrelationId). Every event except the first records the immediate event that caused its publication (CausationId). Together these identifiers create a replay-safe, end-to-end audit chain independent of any specific bounded context.

These are **provenance / message lineage**, not "two UUID columns."

## The rules (ARB, permanent)
1. Exactly **one** producer mints a CorrelationId for each constitutional conversation.
2. Every subsequent producer **propagates it unchanged** — no producer may mint a second correlation within a conversation.
3. Every publication after the first records its **immediate causal predecessor** as the CausationId.
4. **Correlation identifies the conversation, not the event; Causation identifies the direct parent, not the chain.**

## Constitutional Conversation (architectural term)
> The complete causal chain of events originating from one constitutional decision process.

Examples: `Challenge → Determination → Correction → Resolution` · `Membership → Election → Vote → Result → Audit`. One CorrelationId identifies the whole conversation; every event except the first records its immediate causal predecessor as the CausationId.

## Architecture Fitness Rule — CorrelationId minting
Exactly **one** producer may mint a CorrelationId per Constitutional Conversation; all subsequent producers propagate it unchanged; no second mint within a conversation. Enforced by architecture (`start()` only at chain origin), design (`fromConsumed()` propagates, never mints), and test (IT-1: one CorrelationId across all rows).

## Integration Events only — NEVER Domain Events
Domain Events carry **zero** provenance (business facts). Integration Events carry `EventProvenance` (published message + lineage). **Invariant: no Domain Event class may contain CorrelationId/CausationId properties.** Provenance lives exclusively in the messaging layer.

## The API (Shared Application — pure PHP; explicit, never ambient)
```php
EventProvenance::start($minted)                       // chain-starting producer: mint, no cause
EventProvenance::fromConsumed($incoming, $eventId)    // reacting producer: propagate + record cause
```
All outbox ports take provenance explicitly: `enqueue(EventProvenance $provenance, object ...$events)`. Adapters stamp `correlation_id`/`causation_id` onto `outbox_events`; the relay copies them onto the `IntegrationEvent` envelope; the dispatcher propagates them into each consumer's `InboxMessage`.

## Replay-safe fallback (why not a random mint)
A reacting producer whose consumed message carries no correlation uses the **triggering event's id** as the correlation — deterministic, so a redelivered message yields identical provenance. A random UUID minted inside a handler would break replay determinism.

## What this enables (later, without touching aggregates)
Constitutional timeline reconstruction (`Challenge → Determination → Correction → Resolution`), judicial review, incident investigation, replay visualization — all queryable via `correlation_id` across `outbox_events` + `inbox_events`.

## Boundaries
Domain events carry **no** provenance — correlation/causation belong to messaging, never to the domain. Any future transport (Kafka, RabbitMQ, NATS, …) can reuse `EventProvenance` unchanged.

## Traceability
ADR-MP-06 (Constitutional Audit Invariant + D-1) · F-PB006-2 · D-06 · PB-006 6B-1 (`CorrelationChainTest`).
