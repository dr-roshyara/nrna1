# Event Registry

**Status:** implemented (Push B §16 step 4; relay wiring = step 5)
**Traceability:** Blueprint Push B §6 · ADR-T3 · ADR-T5 · Decision Log D-08
**Classes:** `Shared\Infrastructure\Outbox\{EventHydrator, EventHydratorRegistry, UnregisteredEventType}` + one hydrator per produced event in its owning context.

## What it is

The extension point between the outbox relay and the domain: `outbox_events.event_type → EventHydrator → domain event object`. It replaces hardcoded event matching in shared infrastructure (Gap G-1).

```text
Producer → Outbox row → Relay (outbox:process)
                           ↓ hydratorFor(event_type)
                        EventHydratorRegistry
                           ↓
                        Context-owned EventHydrator
                           ↓
                        Domain Event → Event::dispatch()
```

## Ownership

- **Contract + registry:** Shared Infrastructure. Frozen; adding events never modifies it.
- **Hydrators:** owned by the event's SOLE PRODUCER context (mirror of AT-EVT-001). The hydrator is the inverse of that context's outbox adapter write — the two form the wire contract and evolve together.
- Current entries: `DeterminationIssued` (Adjudication) · `FeePaid` (Membership, migrated in step 5).

## Registration lifecycle

1. `AppServiceProvider::register()` binds `EventHydratorRegistry` as a **container singleton**.
2. Each context's ServiceProvider registers its hydrators in `boot()`:
   `$this->app->make(EventHydratorRegistry::class)->register(new XHydrator());`
3. Startup order is irrelevant (all providers boot before the scheduler runs `outbox:process`); registration is idempotent-per-boot because the container rebuilds the singleton per process.

## Duplicate registration

`register()` throws `LogicException` if the type is already registered: **one event type has exactly one hydration authority.** Two contexts must never deserialize the same event differently.

## Unknown events

`hydratorFor()` throws `UnregisteredEventType` (carries `deadLetterReason() = UNREGISTERED_EVENT_TYPE`). The relay maps this to an **immediate dead-letter — no retries** (Blueprint §7 F2): retrying cannot fix a missing registration. Recovery: register the hydrator in the owning context's provider, deploy, re-drive the dead-lettered row.

## Versioning (binding rule)

Per ADR-T5, version dispatch lives INSIDE the hydrator (payload `schema_version`, absent = 1):

> **A hydrator MUST support exactly vCurrent and vPrevious. Never more.**

When vNext ships: hydrator supports {vNext, vCurrent}; rows older than vPrevious must be upcast/migrated before the old branch is deleted. Unlimited version support is forbidden — it silently accumulates untested legacy paths. Breaking changes are a NEW event name, not a new version (Blueprint §12).

## Guarantees (test-enforced)

- Registry contract: `tests/Unit/Contexts/Shared/Outbox/EventHydratorRegistryTest.php`
- Wiring (singleton + per-context registration): `tests/Feature/Contexts/Shared/Outbox/EventHydratorRegistryWiringTest.php`
- Completeness (every produced event type has a registered hydrator; no hardcoded matching left in the relay): `tests/Architecture/EventRegistryCompletenessTest.php`

## Operational (deferred)

`artisan event-registry:verify` (list registrations, detect orphaned outbox event types) — operational tooling, post-Push-B.
