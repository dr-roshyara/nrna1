# 07 — IntegrationEventDispatcher: the delivery capability (PB-006 6A · ADR-MP-06)

## Purpose
Close the gap the PB-006 Discovery exposed (F-PB006-1): the relay dispatched `IntegrationEvent` onto the Laravel bus **and stopped** — nothing carried a published event into consuming contexts' inboxes. **Registration ≠ Delivery** (permanent design principle): registering an `InboxHandler` never delivered anything. The dispatcher is the missing link, owned by the **Messaging Platform** (the Inbox does NOT own delivery — it is one consumer).

## Where it fits
```
app/Contexts/Shared/Infrastructure/Messaging/
  ConsumerResolver.php            # Consumer Discovery port (Messaging-owned)
  RegistryConsumerResolver.php    # registry-backed impl; ordering contract lives here
  IntegrationEventDispatcher.php  # the delivery capability (4 responsibilities)
```
Wiring: `AppServiceProvider` — `ConsumerResolver → RegistryConsumerResolver` binding (register) and `Event::listen(IntegrationEvent::class, [IntegrationEventDispatcher::class, 'handle'])` (boot; **D-2: the relay stays byte-identical** — the dispatcher is just another listener on relay output). `InboxHandlerRegistry` gained one **additive** query, `handlersFor(eventType)`; existing methods untouched.

## The four responsibilities (ADR-MP-06 — each separately testable)
1. **Receive** the relay's `IntegrationEvent`.
2. **Consumer Discovery** via `ConsumerResolver` — deterministic **ordered** set: identical event + registry state ⇒ identical order, ordered by a **stable consumer identifier** (currently `consumerContext()` ascending; the contract is *stability*, not lexicographic comparison). **Ordering carries NO business meaning** — consumers must never rely on being first; it exists only for reproducibility.
3. **Inbox Message Creation** — one `InboxMessage` per consumer; identity, type, payload, organisation (ADR-T16), correlation/causation (D-06, **D-1**: additive nullable envelope fields) propagated. **Audit continuity:** the InboxMessage carries the SAME `event_id` as the originating `OutboxEvent` — every consumption traceable to exactly one outbox record.
4. **Delivery** — `Inbox::consume` per consumer. **Consumer atomicity/isolation:** each consumer runs in its own inbox transaction; one consumer's park/dead-letter/failure never blocks another; no partial inbox state leaks across consumers. A transient Throwable is re-thrown *after* all consumers were attempted — **why:** the relay retry guarantees eventual delivery for the *failed* consumer, while completed consumers are protected by inbox idempotency on the redelivery; failing fast would deny remaining consumers their attempt for no gain.

The dispatcher holds **no state** and **no business decisions**. Empty consumer set ⇒ no-op.

## Testing
`tests/Feature/Contexts/Shared/Messaging/IntegrationEventDispatcherTest.php`: single-consumer delivery + audit continuity + tenant/correlation/causation propagation · deterministic ascending order (consumers registered in reverse) · isolation under park AND under dead-letter · replay-safe redelivery (dedupe) · empty-set no-op.

## Harness lessons enforced here (F-PB006-3 / F-PB006-4)
- Boot-time handler registration is now REAL (Election + Contestation providers). Tests must never register fakes under a real `(consumer, eventType)` pair — use probe contexts (e.g. `RedriveProbe`).
- With the dispatcher live, relay-dispatch tests cause real deliveries → inbox rows accumulate (pgsql harness has no per-test rollback). **Never use global counts/`firstOrFail()`** — always scope by `event_id` (the PB-003 suites were repaired accordingly).
- **The standard regression set now includes `tests/Feature/Contexts/Shared`** — the 5C-era collision went unseen because it didn't.

## Known limitation (F-PB006-2 — open for 6B)
`outbox_events` has **no correlation/causation columns**, so the relay cannot yet stamp them onto the envelope — end-to-end correlation (IT-8) needs an additive outbox migration + producer stamping. The envelope fields (D-1) are nullable pending that ARB decision. For the same reason no `IntegrationEventBuilder` was added in 6A: it would have copied nothing (deviation from the GREEN prompt, documented; the relay stayed truly byte-identical).

## Traceability
ADR-MP-06 · PB-006 IDD (6A) · F-PB006-1..4 · ADR-T1/T4/T16 · D-06 · Blueprint §6/§7.
