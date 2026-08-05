# 03 — Messaging integration: outbox adapter · inbox wiring · hydrator (PB-004 Step 4B)

## Purpose

Connect the Election reaction to the frozen Messaging Platform so it (a) *consumes* `DeterminationIssued` through the Inbox and (b) *publishes* `ElectionCorrectionApplied` through the Outbox — atomically, and re-emittable to downstream consumers (Contestation). Consume-only against the platform: no new messaging abstractions (ARR PASS).

## Where it fits (layer / namespace)

```
app/Contexts/Election/
  Infrastructure/Outbox/
    ReactionOutboxAdapter.php               # implements ReactionEventOutbox → writes outbox_events
    ElectionCorrectionAppliedHydrator.php    # implements Shared EventHydrator (payload → domain event)
  Infrastructure/Providers/ElectionServiceProvider.php
    register(): bind ReactionEventOutbox → ReactionOutboxAdapter
    boot():     InboxHandlerRegistry::register(DeterminationIssuedReactionHandler)
                EventHydratorRegistry::register(ElectionCorrectionAppliedHydrator)
```

## Design decisions (traceable to the approved IDD)

- **Ownership split.** Election owns the **publication decision** ("this correction occurred" — the aggregate records `ElectionCorrectionApplied`); the **Shared platform owns the mechanism** (serialize · enqueue · relay). The **Outbox Adapter** assigns the transport `event_id` (a fresh uuid) — the domain event carries only business identity (electionId, determinationId).
- **Integration Event vs Domain Event (explicit).** The Outbox Adapter **translates, serializes, and enqueues** the published Integration Event — it maps the Domain Event (`ElectionCorrectionApplied`) into the outbox representation (`event_type` + payload) and writes the `OutboxEvent`. It does **not** publish and does **not** republish Domain Events directly; the **Shared Relay** (`OutboxEventProcessor`) performs the eventual publication. **Domain↔Integration translation is an Infrastructure responsibility.** The transport `EventId` is owned by the **Outbox / Shared Messaging layer** (adapter-assigned), never the Domain. Responsibility chain:

  `Domain → creates Domain Event → Infrastructure (adapter) translates · serializes · enqueues Integration Event → Shared Relay publishes`.
- **Atomicity is inherited (ADR-T1).** `Inbox::consume()` wraps the whole handling in one `DB::transaction`. The handler's `applyDetermination → outbox.enqueue → repository.save` all run inside it. Election needs **no** TransactionManager. A throw anywhere rolls back the inbox row **and** the outbox write together.
- **Explicit per-event mapping** (`match(true)`), not reflection — mirrors Adjudication; published integration events deserve explicit wire mapping.
- **Tenant safety.** The adapter stamps `organisation_id` via `TenantContext::require()` (throws when unset) — never `get()`, which once silently produced `''` → FK violation.
- **Hydrator hydrates the DOMAIN EVENT.** `ElectionCorrectionAppliedHydrator` reconstructs `ElectionCorrectionApplied` from the published payload (the cross-context contract); the Shared relay then wraps it in the `IntegrationEvent` envelope. Adapter + hydrator define the wire contract together and evolve together.
- **Schema-version discipline.** Election publishes/accepts payload **schema version 1** only; an unsupported version is **rejected** by the hydrator (`InvalidArgumentException`), never silently hydrated.

## How it works (code)

```php
// ReactionOutboxAdapter — inside the inbox transaction
private function writeElectionCorrectionApplied(ElectionCorrectionApplied $event): void
{
    (new OutboxEvent([
        'event_id'        => (string) Str::uuid(),        // transport identity, adapter-assigned
        'organisation_id' => TenantContext::require(),     // throws if unset (FK safety)
        'aggregate_type'  => 'Election',
        'aggregate_id'    => $event->electionId->toString(),
        'event_type'      => 'ElectionCorrectionApplied',
        'payload'         => [
            'schema_version'  => 1,
            'electionId'      => $event->electionId->toString(),
            'determinationId' => $event->determinationId->toString(),
            'correctionType'  => $event->correctionType->value,   // 'contained_only'
            'appliedAt'       => $event->appliedAt->format(DATE_ATOM),
        ],
        'status' => 'pending', 'attempts' => 0, 'available_at' => now(),
    ]))->save();
}
```

The published payload = `{schema_version, electionId, determinationId, correctionType, appliedAt}`. `correctionType` serializes as `'contained_only'` (the enum intentionally holds only `ContainedOnly` — ADR-T8 forward-only); the hydrator maps it back via `CorrectionType::from()`.

## How to use / extend

- Register both the inbox handler and the outbox hydrator in `ElectionServiceProvider::boot()` (they are net-new to boot; `register()` runs first so the handler's deps — repository, outbox, clock — resolve).
- A new Election event would get its own `match(true)` arm in the adapter **and** its own hydrator, registered the same way.

## Testing

- Unit (`tests/Unit/Contexts/Election/`): `ElectionCorrectionAppliedHydratorTest` (event type · round-trip preserves all fields · unsupported schema_version rejected); `ReactionOutboxAdapterTest` (`enqueue` with no tenant → throws before any write).
- Feature/DB (`tests/Feature/Contexts/Election/ElectionReactionMessagingIntegrationTest`): drives `Inbox::consume($message, $handler)` end-to-end — one `ElectionCorrectionApplied` outbox row + one ledger row written atomically; duplicate delivery → no second event (inbox dedupe); a ledger failure rolls back the outbox write (atomicity); the registry resolves the Election handler by `(Election, DeterminationIssued)`.

## Pitfalls

- **Never** stamp `organisation_id` from `TenantContext::get()` in an outbox row — use `require()`.
- **Do not** add an Election `TransactionManager` — atomicity is the inbox's; adding one would create a nested/duplicate boundary.
- The outbox `aggregate_id` column is `uuid` — in production `electionId` is a legacy uuid (via the ACL); tests must use uuid-shaped election ids. (`ElectionId` itself does not enforce uuid — a recorded observation.)
- Keep the adapter payload and the hydrator in lock-step: they are the two halves of one wire contract.

## Traceability

PB-004 Step 4B · IDD `.claude/plans/PB-004-step4-election-infrastructure.md` (§Step 4B) · Canonical Event Catalog / Round50-05 (`ElectionCorrectionApplied` payload: electionId · determinationId · correctionType · appliedAt; consumers: Contestation · Legitimacy · Audit; NOT Voting) · ADR-T1 (one aggregate + its outbox row(s) per txn) · ADR-T8/T11 (forward-only, anonymity) · Blueprint §6/§7 (outbox/relay, hydrator completeness) · ER-03/04 (reuse the frozen platform).
