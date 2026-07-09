# Inbox Messaging Subsystem — Developer Guide

**Audience:** engineers who will consume events in a bounded context (PB-004 Election reaction, PB-005 Contestation reaction, and every future context), or who maintain the shared messaging infrastructure.

**Status:** the Inbox is built and tested (PB-003, slices C1–C6A). It is the consumer-side half of the platform's event backbone. The producer-side (Outbox + relay) is delivered separately (PB-001/PB-002).

Start with [`why_inbox.md`](./why_inbox.md) for the *why*. This index is the map; each step guide is the *how*.

---

## What this subsystem is

The Inbox gives every consuming context **effectively-once** delivery on top of an **at-least-once** relay:

> at-least-once delivery **+** an idempotent consumer **=** effectively-once processing.

It also doubles as an **audit ledger**. The `inbox_events` table is a durable, per-consumer record of *what each context was asked to process and what happened to it* — processed, parked (awaiting a causal precondition), or dead-lettered. That record is queryable evidence for dispute resolution, without ever storing who voted for whom (see the anonymity rule below).

```text
                       ┌───────────────────────────────────────────────┐
   relay delivers      │                 INBOX (Shared)                 │
   an event  ─────────▶│                                                │
   (at least once)     │  Inbox::consume  ─┐                            │
                       │                   ├─▶ InboxExecutionEngine ─┐  │
   scheduler re-drives │  Redrive (parked) ─┘   (run + classify)     │  │
   (every minute) ────▶│                                             ▼  │
                       │   InboxHandlerRegistry ──▶  your context's Handler
                       │   InboxEvent (inbox_events ledger)             │
                       └───────────────────────────────────────────────┘
                                          │
                        Processed · Duplicate · Parked · DeadLettered
```

---

## The steps (read in order)

| Step | Guide | What it delivers |
|------|-------|------------------|
| C1 | [`01_step_c1_port_contracts.md`](./01_step_c1_port_contracts.md) | The pure-PHP **port** — the contracts a handler implements and the language the Inbox speaks (`InboxMessage`, `InboxHandler`, `InboxOutcome`, the three classification markers). |
| C2 | [`02_step_c2_persistence.md`](./02_step_c2_persistence.md) | The **audit ledger** — the `inbox_events` table and the `InboxEvent` row gateway (dedupe key, status model, state transitions). |
| C3 | [`03_step_c3_idempotent_consumer.md`](./03_step_c3_idempotent_consumer.md) | The **fresh-delivery entry point** — `Inbox::consume()`: dedupe-claim + delegate, in one transaction. |
| C4 | [`04_step_c4_handler_registry.md`](./04_step_c4_handler_registry.md) | The **routing seam** — `InboxHandlerRegistry`, keyed by `(consumer_context, event_type)`; the open/closed boundary between infra and business. |
| C5 | [`05_step_c5_redrive_and_engine.md`](./05_step_c5_redrive_and_engine.md) | The **recovery layer** — `InboxExecutionEngine` (the one run+classify seam), `RedriveParkedInboxEvents`, the `inbox:redrive` command and its schedule. |
| C6A | [`06_step_c6a_architecture_verification.md`](./06_step_c6a_architecture_verification.md) | The **guardrails** — 10 property-based architecture fitness tests + the strict-clock rule that keep the subsystem a reusable platform capability. |
| 6A (PB-006) | [`07_integration_event_dispatcher.md`](./07_integration_event_dispatcher.md) | The **delivery capability** (ADR-MP-06): `IntegrationEventDispatcher` + `ConsumerResolver` — carries relay output into every consuming context's inbox (Registration ≠ Delivery; deterministic ordered routing; consumer isolation; audit continuity). |

---

## Two invariants you must never break

1. **Anonymity (ADR-T11 / Q7).** No inbox message, payload, log line, or dead-letter row may allow a voter to be linked to a vote. The Inbox transports `event_id`, `event_type`, `payload`, `organisation_id`, `correlation_id`, `causation_id` — never `user_id`/`voter_id`/`voting_code`. This is enforced by the greenfield architecture tests.
2. **Messaging ownership.** Shared Infrastructure *transports, schedules, retries, dispatches, and persists* messages. It contains **no business decisions**. All election rules and constitutional policy live inside the owning bounded context's handler. This is enforced by the C6A verification tests.

---

## Reference map

- **Namespaces:** contracts in `App\Contexts\Shared\Application\Inbox`; adapters in `App\Contexts\Shared\Infrastructure\Inbox`; console command in `App\Console\Commands`.
- **Config:** `config/inbox.php` (`park_retry_minutes`, `park_deadline_minutes`).
- **Tests:** `tests/Unit/Contexts/Shared/Inbox`, `tests/Feature/Contexts/Shared/Inbox`, `tests/Architecture/InboxMessagingArchitectureTest.php`.
- **Governance:** IDD `docs/implementation/backlog/PB-003_Inbox_Implementation_Design.md` · Blueprint §6/§7/§8 · ADR-T1/T4/T8/T11 · decisions D-03/D-05/D-06/D-08/D-09/D-10/D-11 in `docs/implementation/PushB_Decision_Log.md` · verification matrix `docs/implementation/Messaging_Architecture_Verification.md`.
