# 18: Ledger slice 1, step 4 — the D-209 append and the atomic C1 event

**Story:** `PBDIGIT-343` · **Plan:** `docs/plans/20261112-1251-release-1-build-slice-1-membership-ledger-clock-and-c1-c2-plan.md` (task 4, D-2, D-5; deviation 2) · **Decisions:** D-209, D-215 (K-C1), CR-01 C1, CR-02 · **Commit:** `79252d9ac`

## Purpose

- **No record time is ever adjusted (D-209).** A reading that does not advance is **refused**, and the caller retries (guide 19).
- **Each membership's record times still strictly increase (V-5)**, now by refusal (K-C1).
- **The fact, the clock update and the C1 event commit or roll back together.**

## Where it fits

```text
Application     Ports\AppendOutcome::RecordTimeRefused        the new, retryable outcome
                Ports\MemberRepositoryPort                    docblock: refusal, never adjustment
Infrastructure  PostgresMemberRepository::appendInTransaction  the D-2 order below
                MembershipCoverageResolvedOutbox              C1 state, payload, aggregate id, write
Test double     tests/Doubles/InMemoryMemberRepository        the same refusal rule (deviation 2, option B)
```

## How the append works (one transaction, READ COMMITTED)

| # | Step |
|---|---|
| 1 | idempotency answer (a replay writes nothing) |
| 2 | storable valid time (R-1a-6) |
| 3 | the clock row already exists: **`LedgerClockRow::ensure()` ran before the transaction** (deviation 4, guide 20). Inside the transaction it is only locked, never created |
| 4 | **lock the clock row `FOR UPDATE`**, before the version |
| 5 | `t` = `LedgerClockReader::now()` (guide 17) |
| 6 | **refuse `RecordTimeRefused`** if `t ≤ closed_through`, `t < last_recorded_at`, or `t` = this membership's last `recordedAt`. **A regression is logged** as `membership.ledger_clock.regression` |
| 7 | version compare-and-set (IDEM-3 re-read kept) |
| 8 | insert the fact with **`recordedAt = t`** |
| 9 | `last_recorded_at = t` |
| 10 | **C1 row**, only if coverage, inForce or suspended changed |
| 11 | the command (idempotency key) row |

**Different memberships may share a reading** (D-209 allows equality across the organisation). Only the same membership may not (V-5).

## The C1 row (`outbox_events`)

- **Written through the repository's own `Connection`,** not the shared `OutboxWriter`, which uses the default connection. This keeps it atomic once each organisation has its own database.
- `event_type = MembershipCoverageResolved`, `aggregate_type = Membership`, `status = pending`.
- **`aggregate_id` is a UUID v5** of `organisationId:membershipId`, because the column is `uuid` but a `MemberId` is free text. The real id is in the payload.
- **Payload** (`schemaVersion: 2`):

  ```json
  {"eventId": "…", "organisationId": "…", "membershipId": "M1", "personRef": "…", "seq": 1,
   "coverage": [{"from": "2024-10-04", "to": null}], "inForce": [{"from": "2024-10-04", "to": null}],
   "suspended": [], "recordedAt": 1760000000000123, "schemaVersion": 2}
  ```

  Intervals are **calendar dates** `[from, to)`, with `to: null` when open-ended (EP-Q6 = T1). **`recordedAt` is in microseconds.**

## Testing

| Test | What it proves |
|---|---|
| `tests/Feature/…/PostgresLedgerClockAppendTest.php` (10) | <ul><li>exact reading; refusal at or before `closed_through`;</li><li>same membership refused, different memberships allowed;</li><li>two organisations independent;</li><li>**forced rollback leaves no fact, clock change or event**;</li><li>C1 on the given connection (query logs of two connections);</li><li>the C1 row contract for a non-UUID member id; one event per replay;</li><li>**the lock order, with forked processes:** while the append waits for the membership row, the clock row is already locked (`NOWAIT` → `55P03`)</li></ul> |
| `tests/Unit/…/Infrastructure/Organisational/MembershipCoverageResolvedOutboxTest.php` (4) | change detection, date encoding, deterministic aggregate id |
| `MemberRepositoryContract` (shared with the double) | backward reading refused and a later one stored exactly; [500, refused, refused, 501] |

**RED evidence:** 9 of the 10 PostgreSQL tests fail on the previous code (KC1-2 passes there, correctly).

## Pitfalls

- **Never reintroduce `max(clock, last + 1)`.** It was COR-4 Q-2's mechanism; D-209 and K-C1 replaced it.
- **Test clocks must advance** (`$this->now++`): a frozen clock now gets the second append of a membership refused.
- `suspended` is always `[]` until slice 5.
- The relay (`outbox:process`) needs a registered hydrator for `MembershipCoverageResolved`, otherwise it dead-letters the row (step 7).

**Traceability:** D-209, D-215 K-C1, CR-01 C1, CR-02, spec §5 · plan task 4 · PBDIGIT-343.
