# 09: The per-organisation outbox relay E-5, PBDIGIT-345 task 9

**Design:** platform routing tactical design §3.4 (M-4), §3.10.2, ISO-9, E-5. **Proofs:** T-6, **T-24** (carried from task 7).
**Evidence:** `docs/publicdigit/platform/evidence/2026-11-14-pbdigit345-task9/`.

## Business purpose

**An organisation's business change and its announcement must never drift apart.** For example, an admission and its `MembershipCoverageResolved` event (C1):
- both are committed **together** in the organisation's own database (the transactional outbox), or neither is;
- the event is then delivered **only after** that commit, **only in that organisation's context**, and never to another organisation.

**Central platform events keep their own central relay.**

## Ownership

| Part | Owner | Where |
|---|---|---|
| the business fact and the decision to emit its event | the business context (Membership for C1) | e.g. `MembershipCoverageResolvedOutbox::write`, on the connection it is given |
| the organisation outbox table | the organisation migration set | `database/migrations/organisation/2026_11_14_300400_create_outbox_events.php` (9a; guide 04) |
| relaying one database's outbox | the Shared outbox library | `Shared\Infrastructure\Outbox\ConnectionOutboxProcessor` |
| routing the relay to each organisation | Platform | `Queue\RelayOrganisationOutbox` (an `OrganisationJob`), fanned out as `outbox.relay` (guide 08) |
| consumers | the consuming context | listeners on the domain event (same organisation context) |
| central events | unchanged | `OutboxEventProcessor` and `outbox:process` (central connection) |

**Two relays, never one relay reading both.** `OutboxEventProcessor` is not changed (T-6).

## How a run works

```
schedule: php artisan platform:fan-out outbox.relay     (every minute; guide 08)
  → one RelayOrganisationOutbox job per operational organisation (guide 07: registry re-resolved, own connection)
    → ConnectionOutboxProcessor::process($context->connection, $organisationId)
       1. refuse if the connection is inside a transaction            (T-24: dispatch outside any transaction)
       2. CLAIM, one committed statement: pending rows, or rows whose lease ran out,
          available_at <= localtimestamp(0), FOR UPDATE SKIP LOCKED → status 'processing', lease 300 s
       3. ISO-9: organisation_id ≠ the context's → status 'failed', Log::critical('outbox.iso9.organisation_mismatch'), never dispatched
       4. hydrate (EventHydratorRegistry) → dispatch domain event + IntegrationEvent → status 'completed'
       5. unregistered type → 'failed' at once; any other failure → retry after 5 min; after 5 attempts → 'failed'
```

**Guarantees:**
- **The row is never deleted:** a dead-lettered event keeps its payload and organisation for audit.
- **Delivery is at least once:**
  - a crash between dispatch and `completed` re-delivers after the lease;
  - overlapping relays never claim the same row (`SKIP LOCKED` plus the lease);
  - **consumers must be idempotent by `event_id`** (CR-01).
- **The database refuses an event of another organisation** (`outbox_events_organisation_identity_fk`). ISO-9 is the second line, for a row that got in anyway.

**Pitfall (found by the tests):**
- `available_at` is `timestamp(0)`, so its default is **rounded** to the second, up to 0.5 s ahead of the clock.
- The relay therefore compares with `localtimestamp(0)`. Comparing with `now()` would skip an event committed in the same second until the next run.
- **The central relay has exactly that delay:** PBDIGIT-350, minor, not changed here.

## Producing an organisation event (for the consuming contexts' slices)

- **Write the outbox row with the same organisation connection, inside the same transaction as the business change.** Never through `OutboxEvent::create` (Eloquent's default connection is central).
- **Register a hydrator for the event type** in the owning context's provider.
- **Work that must follow the commit** (e.g. queue an organisation job) goes in a **listener**. It runs inside the relay, after the commit, outside any transaction, so `OrganisationJobDispatcher` accepts it (T-25 would refuse it inside the business transaction).

## Not in task 9

**The consumer-side inbox for consumers in the organisation database** comes with the first such consumer (Organisation, slice 3).

## Testing

```
MEMBERSHIP_PG_IMAGE=<pinned> MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite PlatformPostgres --filter 'OrganisationOutboxRelayTest|OrganisationMigrationsTest'
```

**`OrganisationOutboxRelayTest`:**
- **Atomicity:** a Membership admission and its C1 commit together; a failing event write rolls the admission back.
- **T-6:**
  - delivered in A, inside A's context, never seen by B's relay;
  - an event naming B planted in A is dead-lettered and alerted, never dispatched;
  - the central and organisation relays never see each other's events.
- **T-24:**
  - a rolled-back transaction leaves no event and no job;
  - an uncommitted event is invisible;
  - after the commit, the consumer queues follow-up work (the relay is outside any transaction);
  - the processor refuses to run inside a transaction.
- **Failures:** retries and dead-letter (5 attempts); an unregistered type dead-lettered at once; the original row kept.
- **The lease:** no double claim; a crashed claim re-delivered.
- **The same-second case:** an event committed in the second half of a second is relayed at once.
- **A real fan-out and a real worker:** each organisation's event is completed in its own database.

**Traceability:** PBDIGIT-345 task 9 · §3.4, §3.10.2, E-5, ISO-9 · T-6, T-24 · PBDIGIT-350.
