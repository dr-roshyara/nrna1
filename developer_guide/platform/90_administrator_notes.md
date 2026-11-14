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
