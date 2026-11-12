# 16: Ledger slice 1, step 2 — the per-organisation ledger clock table

**Story:** `PBDIGIT-343` · **Plan:** `docs/plans/20261112-1251-release-1-build-slice-1-membership-ledger-clock-and-c1-c2-plan.md` (task 2, D-10, test 17) · **Decision:** D-209 · **Commit:** `2b6302e3b`

## Purpose

D-209 gives each organisation **one ledger clock**: its last record time and its completeness point (`closedThrough`). This step creates the table that holds them.

## Where it fits

```text
Database  database/migrations/2026_11_12_130000_create_org_membership_ledger_clocks_table.php
```

| Column | Type | Meaning |
|---|---|---|
| `organisation_id` | `uuid`, primary key | one row per organisation |
| `last_recorded_at` | `timestamptz(6)`, null | the latest `recordedAt` of the organisation's facts |
| `closed_through` | `timestamptz(6)`, null | the completeness point; null until the first close (step 6) |

## Design decisions

- **No foreign key to `organisations`.** In a per-organisation database (release-1 slice 2) the central registry is not there; the same migration must work unchanged.
- **Backfill** in `up()`: `last_recorded_at` = the latest fact per organisation, from the database the migration runs in. It is idempotent (`ON CONFLICT DO NOTHING`).
- **`down()`** drops the table. It is reversible **only together with the code** that uses it.

## Release gate

**Like the other ledger migrations, it runs only on the PBDIGIT-324 throwaway cluster** until the Principal approves the ledger release gate (C-10). **Never run it on the development database** (and never `migrate:fresh`).

## Testing

`tests/Feature/Contexts/Membership/Organisational/PostgresLedgerClockMigrationTest.php` (4 tests, isolated cluster):
- the exact column shape;
- the primary key and no foreign key;
- the backfill per organisation, with nothing closed;
- `down` → `up` twice gives the same rows.

Run: `MEMBERSHIP_PG_IMAGE=docker.io/library/postgres:15@sha256:7da5d534… bash scripts/testing/membership-pg.sh --filter PostgresLedgerClockMigrationTest` (guide 11).

## Pitfalls

- **Do not add a foreign key** "for consistency" with `org_memberships`; that table's own foreign key is a slice-2 item (plan §12, F-13).
- The guard `tests/Architecture/MembershipLedgerIsolationTest.php` lists this migration and forbids `org_membership_ledger_clocks` outside Membership.

**Traceability:** D-209 · plan task 2, D-10 · PBDIGIT-343.
