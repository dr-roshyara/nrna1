# 90: Administrator notes: Platform routing (PBDIGIT-345)

**A living page, updated per task.**

## Task 2: the Platform registry (2026-11-13)

| Question | Answer |
|---|---|
| New database migration? | **Yes, one, on the CENTRAL database:** `database/migrations/2026_11_13_170000_create_platform_served_organisations.php` creates the empty table `platform_served_organisations` |
| Data migrated? | **No.** The table starts empty. **Legacy organisations are never copied in** (L-2: legacy cutover is out of scope) |
| New configuration or secret? | **No.** The registry stores a credential *reference* (e.g. `secret-store:…`), never a password. Where those references resolve is task 3 |
| Provider | `App\Contexts\Platform\Infrastructure\Providers\PlatformServiceProvider` is registered in `config/app.php` |
| User-visible change? | **None.** Nothing in HTTP, jobs or the legacy tenant path uses the registry yet |

**Deploying it:**
1. **Development:** the normal `php artisan migrate` creates the empty table. **Never `migrate:fresh` or `migrate:refresh` on a development database** (project rule).
2. **Production: don't run it before PBDIGIT-345 is accepted by the Principal.** The story ships as one routing capability; an empty table alone gives nothing. When it is accepted, run the normal migration and take a backup first.
3. **Rollback:** the migration's `down()` drops the table. It holds no data until organisations are founded (task 5).

**The G-4 production restrictions (Reference Geography) are unrelated to this table, and still apply.**

## Task 3: organisation database connections (2026-11-13)

**Nothing routes to an organisation until an administrator configures its cluster and its database login.** This is deliberate: fail closed.

| What | Where | Example |
|---|---|---|
| **Clusters** (host, port, SSL) | environment variable `ORGANISATION_DB_CLUSTERS` (JSON), read by `config/organisation_routing.php` | `ORGANISATION_DB_CLUSTERS='{"cluster-1":{"host":"db.internal","port":5432,"sslmode":"require"}}'` |
| **One login per organisation** | **real** environment variables (process or container secrets), named by the locator's reference `env:NAME` | reference `env:ORG_0B9E7A52_DB` → `ORG_0B9E7A52_DB_USERNAME`, `ORG_0B9E7A52_DB_PASSWORD` |

**Rules:**
1. **Use a separate PostgreSQL role per organisation,** with rights on that organisation's database only. A wrong locator then fails instead of reading another organisation.
2. **Put logins in real environment variables or the secret store, not only in `.env`.** With `php artisan config:cache`, values that exist only in `.env` are invisible to the application, and the organisation would be refused.
3. **After changing `ORGANISATION_DB_CLUSTERS`, re-run `php artisan config:cache`** (it's read through configuration).
4. **Never point two organisations' locators at one database.** The registry refuses it (unique index), and the roles above make it fail as well.
5. **Nothing to do in production before PBDIGIT-345 is accepted.** No organisation is in the registry yet (founding is task 5).

## Task 4 (2026-11-14)

**No administrator action.** The organisation migration set (`config/organisation_routing.php` → `migration_paths`) is run only by provisioning (task 5, not built yet) and by tests. **Do not run it by hand** against any production database.

## Task 5a (2026-11-14)

**A new central migration:** `database/migrations/2026_11_14_310000_add_founding_application_to_platform_served_organisations.php` (founding evidence, registrar and initial contacts, all required). **Not for production before the migration-approval control.** If it stops because entries already exist, do not fill the columns by hand: report the entries.

## Task 5b (2026-11-14)

- **Organisation migration set:** a new **organisation** migration, `app/Contexts/Membership/Infrastructure/Database/OrganisationMigrations/2026_11_14_300200_create_ledger_clock_on_organisation_identity.php` (a trigger). It reaches organisation databases only through provisioning, never `php artisan migrate`.
- **Command `platform:prepare-organisation-database {organisation}`:** the dedicated provisioning process, started by founding step 3.
  - **Do not run it by hand, and never from a web request.**
  - It needs, in the real process environment: `ORGANISATION_DB_CLUSTERS`, and for each cluster's login reference `env:NAME` the variables `NAME_USERNAME` and `NAME_PASSWORD`.
  - It writes nothing to the registry.
- **If founding step 3 reports "bound to another organisation":** the locator points at a database that already belongs to another organisation. Do not change `organisation_identity` (it is immutable). Correct the locator decision instead.

## Task 5c (2026-11-14)

- **Organisation migration set:** a new **organisation** migration, `app/Contexts/Membership/Infrastructure/Database/OrganisationMigrations/2026_11_14_300300_create_ledger_readiness_function.php` (a read-only function). It reaches organisation databases only through provisioning.
- **No production founding yet.** Founding has no entry point until task 6 (HTTP E-1). Do not call `FoundOrganisation` from tinker or scripts in production.
- **If step 5 is refused with "missing: …":**
  - the named facts are read from the organisation's own database;
  - "ledger schema" usually means the organisation set was extended after step 3: repeat step 3 first (only while the organisation is still provisioning);
  - "a platform contact with an active account": one of the founding application's contacts must activate their account. Contacts are never edited by hand.

## Task 6 (2026-11-14)

- **No migration and no new environment variable.** One new route, `POST /platform/organisations/select`, loaded by `PlatformServiceProvider`.
- **Route cache:** after deploying, rebuild it (`php artisan route:cache`) or clear it (`php artisan route:clear`). A stale cache hides new routes.
  - **On the development machine, `bootstrap/cache/routes-v7.php` dates from 19 October 2026 and is stale.** It still names `SocialController`, which no route file uses any more, and `php artisan route:list` fails on it.
  - Clearing it is the developer's call (`php artisan route:clear`). The isolated test harness ignores it.
- **Production effect today: no registry organisation is reachable over HTTP.** Identity's bindings are not built (PBDIGIT-349), and `NoIdentityBindingsYet` refuses everyone. Never "fix" this by pointing it at the legacy user-organisation tables.

## Task 7: the organisation queue (2026-11-14)

**Development and test only for now.** No production organisation exists, and the production migration-approval control is still required.

1. **Central migration** `database/migrations/2026_11_14_320000_create_organisation_jobs_table.php` (table `organisation_jobs`). `failed_jobs` already exists.
2. **`config/queue.php` has a new connection, `organisation`.** The default `QUEUE_CONNECTION` must stay `sync`: do not change it, or every legacy job would stop running inline.
3. **Optional environment variables:**
   - `ORGANISATION_QUEUE_DB_CONNECTION`: the central database connection name for `organisation_jobs`; empty means the default (central) connection;
   - `ORGANISATION_QUEUE_RETRY_AFTER`: seconds, default 90.
4. **A dedicated worker:** `php artisan queue:work organisation` under a process supervisor (e.g. one `systemd` service or `supervisor` program). It needs **the same real environment as provisioning:** `ORGANISATION_DB_CLUSTERS` and each cluster's `<NAME>_USERNAME`/`<NAME>_PASSWORD`.
5. **After every deploy:** `php artisan queue:restart`. Workers keep old code in memory until they restart.
6. **Failures:** `php artisan queue:failed` lists them (connection `organisation`); `php artisan queue:retry <id>` re-runs one. **A retry resolves the organisation again**, so it stays refused while the organisation is not routable.

## Task 8: fan-out (2026-11-14)

**No migration and no new environment variable.** One new command: `php artisan platform:fan-out <operation>`.

- **No operation is configured yet** (`organisation_routing.fan_out` is empty), so nothing is scheduled. Each periodic operation arrives with its owning context's slice, together with its line in `routes/console.php`:

  ```php
  Schedule::command('platform:fan-out <operation>')->…->withoutOverlapping();
  ```

  `withoutOverlapping` keeps one run of an operation at a time. A second run would dispatch again: the jobs are idempotent, but a duplicate is still avoidable work.
- **The scheduler needs `php artisan schedule:run` every minute** (cron or `schedule:work`), as usual. **The jobs need the `organisation` worker** from task 7.
- **Exit code 1 means an operation was unknown or refused, or a dispatch failed.** Organisations that aren't operational are listed as skipped; that is normal and exits 0.

## Task 9a (2026-11-14)

**A new ORGANISATION migration:** `database/migrations/organisation/2026_11_14_300400_create_outbox_events.php`. It reaches organisation databases only through provisioning (founding step 3). The central database is not changed.

## Task 9b: the per-organisation relay (2026-11-14)

**No new migration in 9b (9a added the organisation outbox) and no new environment variable.**

1. **Schedule the relay** when registry organisations exist, in `routes/console.php`:

   ```php
   Schedule::command('platform:fan-out outbox.relay')->everyMinute()->withoutOverlapping();
   ```

   - It is not scheduled yet: no registry organisation exists, and the central tables must be migrated first.
   - It needs the `organisation` queue worker (task 7) and `schedule:run`.
2. **Keep `outbox:process` scheduled as it is:** it is the **central** relay for central events.
3. **Dead-lettered organisation events stay in that organisation's `outbox_events` with `status = 'failed'`.**
   - An ISO-9 mismatch is logged at **critical** as `outbox.iso9.organisation_mismatch`: alert on it, because it means a row of another organisation was found in this database.
   - Do not re-drive such a row; investigate it.

## Task 11: the operator channel (2026-11-15)

**Development and test only for now.**

1. **Central migration** `database/migrations/2026_11_15_330000_create_platform_technical_operations.php` (the record of every operator-channel operation). Not for production before the migration-approval control.
2. **Repairing a failed organisation migration:**

   ```
   php artisan platform:operate <organisation-id> migration-repair --operator="ops:<your name>"
   ```

   - Run it **on the application server**, with the same environment as provisioning (`ORGANISATION_DB_CLUSTERS`, cluster logins). It starts the dedicated provisioning process itself.
   - **Outcomes:** `succeeded`; `failed: repair-failed`; `failed: readiness-not-restored`; `refused: …`. Each one is in `platform_technical_operations`, and succeeded and failed repairs are also reported in the organisation's own outbox.
   - **It never changes an organisation's status and never reads business content.** If the readiness is not restored, investigate; do not edit the registry by hand.
3. **There is no command to read an organisation's business data.** Requests for it are refused (`case-grant-required`): content access is not offered in release 1 (D-219 (3)).
