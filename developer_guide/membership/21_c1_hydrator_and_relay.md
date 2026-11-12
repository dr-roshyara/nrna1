# 21: Ledger slice 1, step 7 — the C1 hydrator, so the relay delivers `MembershipCoverageResolved`

**Story:** `PBDIGIT-343` · **Plan:** `docs/plans/20261112-1251-release-1-build-slice-1-membership-ledger-clock-and-c1-c2-plan.md` (task 7, D-6; test 14) · **Decisions:** CR-01 C1, CR-02 (schemaVersion 2), D-161 · **Commit:** `6fd1128c5`

## Purpose

Step 4 (guide 18) writes the C1 row into `outbox_events`. **The existing relay (`outbox:process` → `OutboxEventProcessor`) dead-letters any event type without a registered hydrator.** This step registers Membership's hydrator, so the relay hydrates, dispatches and completes C1 rows. **The relay itself is unchanged.**

## Where it fits

```text
Application     Organisational\PublishedLanguage\MembershipCoverageResolved   the C1 event (pure PHP, readonly)
                Organisational\PublishedLanguage\CoverageInterval              [from, to) calendar dates; to = null if open
Infrastructure  Outbox\MembershipCoverageResolvedHydrator      payload → event; fails loudly outside the contract
                Providers\MembershipServiceProvider::boot()     registers it beside FeePaidHydrator
Shared          Outbox\EventHydratorRegistry, OutboxEventProcessor   unchanged
```

**Membership is the one hydration authority** for the event it produces (the registry refuses a second registration).

## The event

```php
new MembershipCoverageResolved(
    eventId, organisationId, membershipId, personRef, seq,
    coverage: list<CoverageInterval>, inForce: list<CoverageInterval>, suspended: list<CoverageInterval>,
    recordedAtMicros, schemaVersion: 2,
);
```

**Rules for consumers** (Organisation, release-1 slice 3+; CR-01 C1):
- order by `(membershipId, seq)`. A lower `seq` → ignore; an equal and identical one → ignore; **an equal and different one → an integrity error, raised, not applied**;
- **current views only:** every "as at d, as known at τ" answer uses C2 (step 8), never a C1 projection;
- consume it **through your own port and adapter** (anti-corruption); never import Membership's domain or infrastructure.

## Contract checks (fail loudly)

`InvalidArgumentException` on any of these:
- a missing field, or a wrong type (`seq`, `recordedAt`, `schemaVersion` must be integers);
- **an unknown `schemaVersion`** (only 2);
- an interval without `from`, without a `to` key, or not in `YYYY-MM-DD`.

The relay then retries and finally marks the row failed: **nothing is dispatched on a guess.**

## Testing

| Test | What it proves |
|---|---|
| `tests/Architecture/EventRegistryCompletenessTest.php` | `MembershipCoverageResolved` is in the produced-types list and has exactly one hydrator |
| `tests/Unit/Contexts/Membership/Infrastructure/Outbox/MembershipCoverageResolvedHydratorTest.php` (9) | the event type; the producer's payload hydrates field by field; open `to` stays null; six broken payloads fail |
| `tests/Feature/Contexts/Membership/Organisational/PostgresC1RelayRoundTripTest.php` | **append → C1 row → `OutboxEventProcessor::processEvent()` → dispatched once, row `completed`, not dead-lettered** |

**RED:** the guard (no hydrator), the unit test (class missing), the round trip (row not completed).

## Pitfalls

- **Adding a new outbox event type?** Register its hydrator **and** add it to `EventRegistryCompletenessTest::PRODUCED_EVENT_TYPES`, or the relay dead-letters it.
- **No consumer exists yet**, so rows are completed with no listener. That is harmless: the ledger is not live, and Organisation's first build rebuilds from C2 (plan R-9).
- **The relay still reads the default connection.** Under per-organisation databases (slice 2) it must iterate the organisation connections (plan §12, R-4).

**Traceability:** CR-01 C1, CR-02, D-161, spec §5, §7 · plan task 7, D-6 · PBDIGIT-343.
