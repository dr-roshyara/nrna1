# 13 — Testing strategy

Every behaviour below is written **RED first** (repository TDD rule).

**FACT:**
- `tests/TestCase.php:23-36` skips transactions on pgsql. Confirm the actual isolation semantics before relying on `RefreshDatabase`.
- There are about 1,000 existing test files.
- No test covers jobs or commands across organisations.

## Two suites
| Suite | Setup | Purpose |
|---|---|---|
| **Shared mode (default)** | Every factory organisation gets a registry row `status=shared → nrna_test` | The whole existing suite runs **unchanged** (proves the default-connection switch preserves behaviour) |
| **Multi-DB suite** (`tests/MultiDatabase`) | Pool of pre-provisioned databases `nrna_test_t1..t3`, migrated once per run; organisations assigned from the pool; truncation between tests (transactions cannot span connections) | Physical isolation |

## Test catalogue

| Category | Tests |
|---|---|
| **Tenant resolution (host)** | `nrna.publicdigit.test` → NRNA; `nc.publicdigit.test` → NC; apex → no tenant; unknown label → 404; inactive or suspended org → 404; host outside the allow-list → rejected; `nrna.publicdigit.test.evil.tld` → rejected; forged `X-Forwarded-Host` from an untrusted IP → ignored |
| **Consistency** | `nc.publicdigit.test/organisations/nrna/...` → 404; `nc` host + NRNA voter slug → 404 |
| **Database resolution** | NRNA → `nrna_test_t1`; NC → `nrna_test_t2`; registry `shared` → legacy DB |
| **Isolation** | NRNA request cannot read or write NC data through any route group (reuse `TenantIsolationTest` scenarios in multi-DB mode); `Election::withoutGlobalScopes()->count()` in NRNA never sees NC rows |
| **Fail closed** | No context + tenant model → `TenantNotResolved`; missing registry row → throws; registry points at a DB whose self-row ≠ org → throws; empty database name never used; `switchTo()` inside a transaction → throws |
| **Authentication** | Global user + NRNA grant → NRNA dashboard on the NRNA host; same user → NC host without an NC grant → 403; login on a tenant host checks credentials on central; registration uniqueness checks central `users`; password reset and email verification links use the apex |
| **Sessions** | Session cookie set on `nrna` is not sent to `nc` (host-only); logout on one host does not affect the other |
| **URL generation** | Queued voter invitation dispatched in NRNA → link host `nrna.publicdigit.com`; officer signed URL validates on the NRNA host and fails on NC |
| **Jobs** | NRNA job → NRNA DB; NC job → NC DB; job A then job B on one worker → no leak; a job with no tenant touching a tenant model → throws; retry keeps the tenant |
| **CLI** | `tenants:run x --org=NRNA` touches only NRNA; a tenant command without `--org` refuses; `TenantRunner` continues after one tenant fails |
| **Outbox** | An event written in NRNA's transaction is processed only in NRNA |
| **Projection** | Grant → projected user appears in the tenant; email change propagates; deletion anonymises in all tenants; reconcile repairs drift |
| **Provisioning** | Provision twice → idempotent; failure mid-way → retry completes; no `DROP DATABASE` on failure |
| **Migration** | Seeded org extraction: per-table counts and checksums equal; FK validation passes; no row with a foreign organisation key; `votes` / `results` have no `user_id`; legacy rows untouched until the purge |
| **Security regressions** | Non-platform-admin → 403 on `admin/elections/*`; `X-Tenant-Id` ignored |

## Architecture fitness tests
- Every table in `information_schema` is classified in `config/tenancy.php` (unclassified → fail).
- No `DB::connection('tenant'|'landlord'|…)` literal outside `Infrastructure/Tenancy`.
- Central models declare `$connection = 'central'`; tenant models do not declare a connection.
- No `TenantContext` usage inside `app/Contexts/*/Domain`.
- No `env()` calls outside `config/`.
- No `Cache::flush()` in application code.
- Anonymity: `votes` / `results` have no `user_id` in **every** tenant DB.
- Trusted-host middleware is registered; `trustProxies` is not `*` in production config.
