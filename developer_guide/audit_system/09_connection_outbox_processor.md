# 09: `ConnectionOutboxProcessor`, the outbox relay on a given connection (PBDIGIT-345 task 9b)

**Area:** the Shared outbox library (`app/Contexts/Shared/Infrastructure/Outbox/`). **Platform's routing side** (the fan-out job and the organisation context) is in `developer_guide/platform/09_organisation_outbox_relay.md`. This guide is the library side.

## What it is, and why a second class

`OutboxEventProcessor` is the **central** relay. It reads `outbox_events` through Eloquent's **default** connection (the central database) and is run by `outbox:process`. It is **unchanged**, as PBDIGIT-345's T-6 requires.

`ConnectionOutboxProcessor` relays **one database's** outbox **through the connection it is given**: an organisation's database, so a business change and its event stay together there (tactical design §3.4). Rule: **two relays, never one relay reading both.**

## API

```php
$report = app(ConnectionOutboxProcessor::class)->process(ConnectionInterface $db, string $expectedOrganisationId);
// ['delivered' => int, 'retried' => int, 'dead_lettered' => int]
```

**Dependencies:**
- `EventHydratorRegistry`: the **same** context-owned hydrators as the central relay (no event-type matching here; `EventRegistryCompletenessTest` checks both processors);
- the Laravel event `Dispatcher`.

## Behaviour, compared with the central relay

| | `OutboxEventProcessor` (central) | `ConnectionOutboxProcessor` |
|---|---|---|
| connection | Eloquent default | the one passed in; never the default (`OrganisationRoutingDefaultConnectionTest`) |
| inside a transaction | not checked | **refused** (`LogicException`): dispatch only after the commit, outside any transaction |
| selection | the 100 oldest `pending`, `available_at <= now()` (PHP) | **claim** in one committed `UPDATE … FOR UPDATE SKIP LOCKED` of `pending` rows, **or `processing` rows whose 300 s lease ran out**, `available_at <= localtimestamp(0)` |
| organisation check | none | **ISO-9:** `organisation_id ≠ $expectedOrganisationId` → `failed`, `Log::critical('outbox.iso9.organisation_mismatch')`, never dispatched |
| dispatch | domain event, then `IntegrationEvent` | the same |
| unregistered type | dead-lettered at once | the same |
| other failure | 5 attempts, 5 minutes apart, then `failed` | the same |
| row after dead-letter | kept | kept |

**Why `localtimestamp(0)`:**
- `available_at` is `timestamp(0)`, so its default is **rounded** to the second, up to 0.5 s ahead of the clock;
- comparing with `now()` would skip an event committed in the same second until the next run;
- **the central relay has that delay** (PBDIGIT-350, not changed here).

## Pitfalls

- **Never call it inside the business transaction:** it refuses.
- **Never point it at the central database for organisation events:** the central relay owns central events.
- **Delivery is at least once:**
  - a crash between dispatch and `completed` re-delivers after the lease;
  - **consumers deduplicate by `event_id`** (`IntegrationEvent::$eventId`; the inbox pattern of guides 02–03).

## Testing

`tests/Feature/Contexts/Platform/OrganisationOutboxRelayTest.php` (isolated cluster, `--testsuite PlatformPostgres`):
- atomicity;
- T-6 and ISO-9;
- T-24;
- retries and dead-letter;
- the lease;
- the same-second case;
- a real worker.

**Mutation:** 9 mutants, all killed by assertions (evidence `docs/publicdigit/platform/evidence/2026-11-14-pbdigit345-task9/`).

**Traceability:** PBDIGIT-345 task 9b · tactical design §3.4, §3.10.2, ISO-9 · PBDIGIT-350.
