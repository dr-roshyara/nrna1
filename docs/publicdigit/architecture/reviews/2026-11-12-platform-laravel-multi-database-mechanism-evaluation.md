# Slice 2: how Laravel implements the multi-database boundary — evaluation — PROPOSED

| | |
|---|---|
| **Status** | **DECIDED: option B (D-222, 2026-11-12).** Was: PROPOSED for the Principal's decision. Design only: no package installed, `composer.json` / `composer.lock` unchanged, no task 2 or 3 code |
| **Commission** | Principal DDD Architect instruction after the task 1 review (2026-11-12) |
| **Fixed, not evaluated** | one central database; one database per organisation on one cluster (D-164, D-178, D-219 (4)); routing only from the registry (ISO-2); explicit connection, default connection stays central (delta C-1, §3.1); two queue connections (§3.10, D-221); per-organisation relay (§3.4) |
| **Question** | A: Spatie Laravel Multitenancy · B: a small custom integration (`OrganisationContext` + `OrganisationConnectionFactory`) · C: a combination |

## 1. Facts

**Project** (`composer.json`, `composer.lock`, `php artisan --version`):
- Laravel Framework **12.71.0**; PHP constraint `^8.3`, runtime 8.5.11.
- **No tenancy package installed.** Spatie packages present: `laravel-permission` 6.25.0, `laravel-query-builder` 6.4.4, `laravel-package-tools` 1.93.3.
- **Already present, legacy:** `TenantContext` (middleware and service, resolving from path, session or header) and `IdentifyTenantFromHeader`; connections `pgsql`, `pgsql_audit`, `pgsql_geo`, `testing`; default queue `sync`, with 55 `ShouldQueue` classes running inline.
- **Slice 1 Membership repositories hold a `Connection` object** given to them (`PostgresMemberRepository(Connection, …)`).

**Spatie Laravel Multitenancy, read from its sources on 2026-11-12** (packagist, `composer.json`, `CHANGELOG.md`, `config/multitenancy.php`, `src/…`, v4 docs):
- Latest **4.3.1** (2026-11-04); `"php": "^8.3"`, `"illuminate/support": "^12.0|^13.0"`. **Laravel 12 is supported.**
- **The current tenant is ambient state:** a container binding `currentTenant` (`Tenant::current()`), a Laravel Context key `tenantId`, and static stacks in `MakeQueueTenantAwareAction`.
- **Switching re-points ONE shared connection:** `SwitchTenantDatabaseTask` sets `database.connections.{tenant}.database`, re-extends and **purges** it. The connection is `tenant_database_connection_name`, or **the default connection if that is null.**
- **The tenant is an Eloquent model in a "landlord" database:** `id, name, domain, database`.
- **Tenant finder:** request-only by default (domain); `makeCurrent()` can be called programmatically.
- **Queues:**
  - listeners on the **global** event dispatcher, so every queue connection (`JobProcessing`, `JobProcessed`, `JobExceptionOccurred`, `JobRetryRequested`);
  - the tenant id rides in Laravel Context, and the tenant is made current at job start;
  - **if it cannot be found, the job is deleted, then the call throws** (source; not stated in the docs);
  - `queues_are_tenant_aware_by_default` ships `true`.
- **Migrations:** `tenants:artisan "migrate --database=tenant"` loops all tenants, with **no guard against the landlord** (source).

## 2. The decisive evidence: a held connection follows the shared name

Laravel (`DatabaseManager::purge` / `reconnect`, `Connection::reconnectIfMissingConnection`): a purged connection object, used again, **reconnects by its name, using whatever that name is configured to at that moment.**

**Probe** (`docs/publicdigit/platform/evidence/2026-11-12-pbdigit345-mechanism-evaluation/stale-connection.php`, two SQLite files, output `stale-connection.out`):

```
1. held object, A current: organisation a
1. SAME held object after switch to B: organisation b      ← one shared name (the SwitchTenantDatabaseTask pattern)
2. held org_a object after purge and B's job: organisation a   ← one name per organisation (delta C-1)
```

**Meaning:** with one shared, re-pointed connection, any object that kept a `Connection` reads or writes **the next organisation's database, silently.** Slice 1's repositories, and the explicit-connection rule of the accepted design, keep exactly such objects. **With one connection name per organisation, a stale object can only ever reach its own organisation.**

## 3. Comparison

| # | Requirement | A: Spatie | B: custom | 
|---|---|---|---|
| 1 | central vs organisation separation | yes (landlord vs tenant connection) | yes (default central; `org_<id>` per organisation) |
| 2 | trusted resolution after authentication and ISO-3 | **custom anyway:** request finder plus our checks, then `makeCurrent()` | `ResolutionService` (task 2) + entry middleware (task 6) |
| 3 | safe switching between requests | purge on switch; **ambient current tenant** | **no switching:** a new immutable `OrganisationContext` per operation |
| 4 | no leak in long-lived workers | **fails §2's probe** for held connections; static stacks in the queue action | **passes §2's probe**; purge per job (Q-4) |
| 5 | jobs carry an organisation id, not credentials or connection names | yes (Context `tenantId`) | yes (`OrganisationJob` envelope, D-221) |
| 6 | separate central and organisation queues | **global listeners act on every connection, including legacy `sync`** (`SyncQueue` fires `JobProcessing`). The shipped default `true` would refuse **and delete** legacy inline jobs; must be configured off and opted in per job | only `organisation`-connection jobs are touched (Q-3, Q-4) |
| 7 | outbox and per-organisation relay | not provided | §3.4, as designed |
| 8 | central vs organisation migrations | `tenants:artisan` loop, **no landlord guard**, no `schemaState` | custom runner that records `schemaState` and checks L-1 (task 4); needed anyway |
| 9 | missing, unknown, unprovisioned, incompatible → refused | tenant "not found" **deletes the job**: no `failed_jobs` record (conflicts with D-221 (4), T-23); no notion of `operational` / `schemaState` | `ServedOrganisation::isRoutable()` (task 1); refusal recorded as a failed job |
| 10 | testability | possible, but the ambient state must be reset in every test | plain objects; the task 0 harness |
| 11 | Laravel 12 / PHP 8.5 | yes (4.3.1) | yes (framework only) |
| 12 | one developer | a dependency whose global behaviour must be configured against, plus a second registry model beside `ServedOrganisation`, plus the legacy `TenantContext`: **three "current organisation" notions** | about six small infrastructure classes, all already in the plan |

## 4. Recommendation: **B, the small custom integration.** Not A, not C.

**Why:**
1. **Safety (§2).** A's core switching pattern lets a held connection cross organisations. It conflicts with the accepted explicit-connection rule and with slice 1's code. Making A safe would mean never holding a connection, which reverses delta C-1.
2. **A provides little that the accepted design doesn't already need custom code for.** Registry rules, ISO-3, the dispatcher's transaction refusal, the relay, schema state and L-1 are custom under either option. What A adds (switching, queue awareness, the migration loop) is either unsafe for us (switching), harmful by default (global queue awareness, job deletion), or too weak (an unguarded migration loop).
3. **One notion of "current organisation"**, an explicit value, instead of a package-wide ambient tenant on top of the legacy `TenantContext`.

**C is not recommended:** the only parts worth borrowing are ideas, not code. For example, "every change made when entering an organisation is undone on leaving it" (Spatie's `forgetCurrent` symmetry) becomes a test obligation in B.

## 5. Minimum components (B), all already in the approved plan

| Component | Layer | Task |
|---|---|---|
| `ServedOrganisationRepository` (port) + PostgreSQL adapter on the **central** connection | Application port / Infrastructure | 2 |
| `ResolutionService`: `TenantId` → routable `ServedOrganisation` or refusal | Application | 2 |
| `OrganisationContext`: immutable `TenantId` + connection name `org_<id>` | Application value | 3 |
| `OrganisationConnectionFactory`: builds `org_<id>` from the locator; credentials by reference; **never re-points a name; never changes the default** | Infrastructure | 3 |
| organisation migration runner: separate path; records `schemaState`; refuses the central connection | Infrastructure | 4 |
| entry middleware, job base class, dispatcher, job middleware, fan-out, relay | Infrastructure | 6–9 |

**Kept custom whatever the choice:** the registry and its rules, ISO-3, ledger rule L-1 to L-3, the dispatcher's refusals, the relay and ISO-9, schema state, and founding.

## 6. Consequences for the next tasks

- **Task 2:** the registry is persisted from `ServedOrganisation` (no Spatie `Tenant` model); its domain events go to the **central** outbox.
- **Task 3:** add a test obligation, **T-26: a connection object held from organisation A's context, used after B's context starts, still reaches A only.** That is §2's probe as a test, against the factory.
- **Task 4:** the migration runner refuses the central connection name. It is not a loop over "all tenants" without the registry's state.
- **Queues and outbox:** unchanged from §3.10 and §3.4 (D-221).
- **Legacy:** untouched; `TenantContext` keeps serving legacy organisations only (T-10).

## 7. Decisions for the Principal

- **Technical (yours by request):** A, B or C. **Recommendation: B.**
- **Business:** none. This choice changes no ownership, topology or accepted rule.
- **Noted for later, not now:** `spatie/laravel-permission` keeps its tables on the default (central) connection. Organisation-side authority is Governance's AuthorityCheck, not those roles. Its place in the multi-database world belongs to a later slice, not slice 2.

**Traceability:** D-164, D-178, D-193, D-219, D-221 · delta revision 3 (§3.1, §3.3, §3.4, §3.10) · plan tasks 2–9 · evidence `docs/publicdigit/platform/evidence/2026-11-12-pbdigit345-mechanism-evaluation/`.
