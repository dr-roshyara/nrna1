# 04: The organisation migration set (PBDIGIT-345 task 4)

**Design:** platform routing and isolation tactical design §3.5 (two migration sets), M-5 (F-13), T-8, T-10 part 2; plan task 4. **Evidence:** `docs/publicdigit/platform/evidence/2026-11-14-pbdigit345-task4/`.

## Business purpose

**Each organisation's data lives in its own database.** That database gets its own schema, from its own migration set:
- the **central** `php artisan migrate` never touches it;
- an organisation migration never touches central data.

**The database itself refuses data of another organisation:** every ledger row must reference the database's one `organisation_identity` row (F-13). This holds even if application code were wrong.

## The two sets

| Set | Where | Runs on |
|---|---|---|
| central | `database/migrations/*.php` | the central database (`php artisan migrate`; the migrator never recurses into subdirectories) |
| organisation | `config/organisation_routing.php` → `migration_paths`: **one path per owning context** | each organisation database, through provisioning only |

**The organisation set today:**
- **Platform:** `database/migrations/organisation/2026_11_14_300000_create_organisation_identity.php`
  - one row (`organisation_identity_one_row`), written once at provisioning (task 5);
  - UPDATE and DELETE refused (`ORGANISATION_IDENTITY_IMMUTABLE`).
- **Membership:** `app/Contexts/Membership/Infrastructure/Database/OrganisationMigrations/2026_11_14_300100_create_org_membership_ledger.php`
  - the ledger **at its current central shape**, consolidated;
  - **F-13:** `org_memberships_organisation_identity_fk` (and the ledger clock's) points at `organisation_identity`, not at the central `organisations` table.

**Why the ledger migration lives in Membership:** Membership owns its tables (plan file table: "organisation-schema migration for F-13"). `MembershipLedgerIsolationTest` (the Election-only boundary) caught my first placement under `database/`; the file was moved, the guard left unchanged.

## Running it (`OrganisationMigrations`, Platform Infrastructure)

```php
$context = $factory->establish($resolved);                 // task 3
$applied = app(OrganisationMigrations::class)->apply($context);   // provisioning (task 5) only
$pending = app(OrganisationMigrations::class)->pending($context);
```

- **Runs only on the connection the factory established for that organisation** (`org_<uuid>`). It refuses:
  - the default connection;
  - any other name;
  - any connection on the default connection's database (a misrouted registry locator).

  The refusal is `OrganisationMigrationRefused`, and nothing is written.
- **History is per organisation:** the `migrations` table is in the organisation's own database.
- **Each migration is its own transaction** (PostgreSQL DDL is transactional). A failing one leaves nothing behind and stays pending; earlier ones stay applied. A repeat applies only what is pending.
- **Known limit:** Laravel migrations use the `Schema::`/`DB::` facades, so the migrator switches the **default** connection while they run (`Migrator::usingConnection`). The switch is restored in `finally` (tested), but it is process-wide while it lasts, so **provisioning must run it in its own process** (task 5), never inside a request or a worker serving other organisations.

## The rules that keep the sets apart

- **`OrganisationMigrationSetTest`** (T-10 part 2, database-free). It checks that:
  - the central migrator never sees an organisation file;
  - organisation migrations reference only tables the organisation set creates, and never name a connection;
  - no central migration references `organisation_identity`.

  Comments are stripped before scanning; SQL in heredocs is checked. The test is proven by planted rogue migrations in `tests/Fixtures/OrganisationMigrationSet/`.
- **`OrganisationRoutingDefaultConnectionTest`** (T-10 part 1) now also scans `OrganisationMigrations`.
- **Parity:** `OrganisationMigrationsTest::test_the_organisation_ledger_schema_matches_the_central_one_except_f13` compares columns and constraints with the central ledger. **A change to the central ledger needs the same change in Membership's organisation migration;** the test fails otherwise.

## Testing

```
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Architecture/OrganisationMigrationSetTest.php
MEMBERSHIP_PG_IMAGE=<pinned> MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite PlatformPostgres --filter OrganisationMigrationsTest
```

- **Database tests (5):**
  - one organisation database only; a repeat applies nothing; the default is restored;
  - wrong connection, the central database and a **misrouted** organisation connection refused;
  - **T-8:** another organisation's membership refused by `org_memberships_organisation_identity_fk`; a second identity refused; the identity immutable; another organisation's ledger clock refused;
  - a failing migration rolls back and stays pending;
  - parity.
- **Mutation (runner, 5):**

  | Mutant | Result |
  |---|---|
  | O1 (name check), O5 (pending) | killed by assertions |
  | O3 (one path only), O4 (default not restored) | killed, partly by errors |
  | O2 (central-database check) | **survived at first**: a test gap, closed with the misrouted-locator test; then killed |

  **Migration constraints:** covered by refusal tests that name each constraint or trigger (a missing constraint makes its test fail).

**Administrator: no action now.** Nothing runs this set except tests. When provisioning exists (task 5), organisation databases get their schema there. **Never run `php artisan migrate --path=…/organisation` by hand against a production database;** the migration-approval control and the founding sequence come first.

**Traceability:** PBDIGIT-345 task 4 · tactical design §3.5, M-5 · T-8, T-10 · D-178 §3, D-222.

## Task 9a (2026-11-14): the organisation outbox

**`database/migrations/organisation/2026_11_14_300400_create_outbox_events.php`** adds the transactional outbox to every organisation database (tactical design §3.4).
- **Why:** a business change and its event must commit or roll back **together**, in the organisation's database. Membership's C1 (`MembershipCoverageResolvedOutbox::write`) already writes into `outbox_events` through the connection it is given.
- **Shape:** the central shape (`2026_05_04_000001` plus `2026_07_10_000001`, including the indexes).
- **The one difference:** `organisation_id` references `organisation_identity` (`outbox_events_organisation_identity_fk`), so **an event of another organisation cannot be written into this database** (defence in depth under ISO-9).
- **Parity rule:** a change to the central outbox needs the same change here. `OrganisationMigrationsTest::test_9a_…` compares columns, constraints and indexes.
- **The relay that reads it** is task 9b (guide 09).
