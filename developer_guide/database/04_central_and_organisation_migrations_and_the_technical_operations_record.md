# 04: Central and organisation migrations, and the central record of technical operations (PBDIGIT-345)

**Origin:** PBDIGIT-345 tasks 4–11. **Context-specific detail:** Platform guides 04, 05, 07, 09 and 11; Membership guides 23 and 24.

## Two migration sets, never mixed

| Set | Runs on | How | Examples |
|---|---|---|---|
| **central** (`database/migrations/*.php`) | the central database (default connection) | `php artisan migrate` (production: not before the migration-approval control) | `platform_served_organisations` (+ founding application), `organisation_jobs`, `platform_technical_operations` |
| **organisation** (`database/migrations/organisation/`, `app/Contexts/Membership/Infrastructure/Database/OrganisationMigrations/`) | **each organisation's own database** | only through provisioning (founding step 3) or the operator channel's repair, in the dedicated process | `organisation_identity`, the Membership ledger, `outbox_events` |

**Rules:**
- **The central `migrate` never sees the organisation set:** the migrator does not recurse, and the paths are configured in `config/organisation_routing.php`.
- **Organisation migrations reference only tables their own set creates** (T-10 part 2, `OrganisationMigrationSetTest`).
- **A shape shared by both sets** (the Membership ledger, `outbox_events`) keeps **parity**, proven by tests, except its organisation foreign key. That key targets `organisation_identity`, so the database itself refuses another organisation's row.
- **Never edit an applied migration:** each organisation database records its own history.

## `platform_technical_operations` (central, task 11)

`2026_11_15_330000_create_platform_technical_operations.php`: the record of every operator-channel operation, **written before anything is done** (D-193 gateway rule; ISO-8).

| Column | Meaning |
|---|---|
| `id` uuid | the operation id (also the `event_id` of the organisation's report) |
| `organisation_id` text | as requested, **not a foreign key**: an operation for an unknown organisation is recorded too |
| `operation`, `operator` | what was asked (`migration-repair`, or a refused request), and the **recorded** operator reference (it grants nothing) |
| `status` | `attempted` → `succeeded` / `failed` / `refused` (CHECK) |
| `reason`, `reported` | why (never content); whether the organisation could be told |
| `started_at`, `finished_at` | CHECK: `finished_at` is null exactly while `attempted` |

**Operational implications:**
- **A row stuck in `attempted`** means the command was interrupted between recording and concluding. Investigate it before repeating the operation.
- **`reported = false`** means the organisation could not be told; for example, its database was unreachable, or the operation was refused before any database was involved.
  - **Reporting is not guaranteed.** The central row is the authoritative record.
  - Refused operations are **never** reported to the organisation (only recorded centrally).
- **No business content is stored:** identifiers, kind, outcome and reason only.

## Pitfall: `timestamp(0)` rounds UP (found in task 9)

`outbox_events.available_at` and `created_at` are `timestamp` with precision 0. **PostgreSQL rounds** their `CURRENT_TIMESTAMP` default to the nearest second, so the stored value can be **up to 0.5 s in the future**.
- **Comparing it with `now()`** makes a just-written row look "not yet due".
- **Compare with the same rounding** (`localtimestamp(0)`), as `ConnectionOutboxProcessor` does.
- **The central relay still compares with PHP's `now()`:** PBDIGIT-350.
- **New tables should use `timestamptz(6)`** (as `platform_technical_operations` and the registry do).

## Testing

```
MEMBERSHIP_PG_IMAGE=<pinned> MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite PlatformPostgres
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Architecture/OrganisationMigrationSetTest.php
```

**Traceability:** PBDIGIT-345 tasks 4, 9, 11 · ISO-8 · PBDIGIT-350.
