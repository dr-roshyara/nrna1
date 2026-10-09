# 03 — `election` reference assessment

`election` (Laravel 12, `spatie/laravel-multitenancy` 4.0.7 in `composer.lock`, `vendor/` not checked in) is a **reference to reverse-engineer, not a model to copy**. Paths are relative to the `election` repository root.

## 1. How tenancy really works there (FACT)

### 1.1 At least six tenant-identification mechanisms, which disagree
| # | Mechanism | Behaviour |
|---|---|---|
| 1 | Spatie `HybridTenantFinder` (`config/multitenancy.php:22`; `app/Multitenancy/HybridTenantFinder.php`) | Tries an exact `domain` (`:93`), then `*.localhost` → slug (local only, `:52-62,104-115`), then the **first path segment** as slug on every host (`:124-155`). **Never checks `status`.** Unknown hosts return `null`, so the request is **served in landlord context (fail-open)**. |
| 2 | `IdentifyTenantFromRequest` (appended to `web`, `bootstrap/app.php:61`) | Path first, then subdomain via `env('TENANT_DOMAIN')` and **`str_contains`** (a substring, not a suffix match, `:90-93`). Inactive tenants get 404 (`:62`). It never calls Spatie `makeCurrent()`. |
| 3 | `TenantAwareSessionMiddleware` + `PathTenantResolver` | Only matches `elections/{slug}`, which is a reserved route. |
| 4 | `EnhancedIdentifyTenant` / `TenantAuthResolver` | Looks tenants up in the **`elections`** table (`tenant_slug`). Takes the first label of **any** host (`example.com` → `example`). Always reports `active`. DB-name fallback `{slug}_db`. |
| 5 | `TenantSubdomainMiddleware` / `SubdomainTenantMiddleware` | Not wired. |
| 6 | `App\Infrastructure\TenantResolution\TenantResolver` | Used only by tests. |

The only `Route::domain('{tenant}.'.env('TENANT_DOMAIN'))` (`routes/tenant.php:7-8`) uses the alias `subdomain.tenant`, which is **not registered** (`bootstrap/app.php:73` registers `tenant.subdomain`). [I] Every subdomain route would fail.

### 1.2 Registries and naming
- **Three tenant registries:** `tenants` (with `domain` unique, `subdomain` not unique), `tenant_databases` (a second `slug`, encrypted credentials) and `elections.tenant_slug`.
- **Five `Tenant` classes.**
- **Three database naming schemes:** `tenant_{slug}`, `tenant_` + slug with `-` replaced by `_`, and `{slug}_db`.
- **Provisioning never fills `domain`** (`TenantProvisioningService.php:112-125`). [I] So production host resolution depends on manual data entry.

### 1.3 Connections
- About 40 call sites toggle `DB::setDefaultConnection(...)`. Several reset to a hard-coded `'mysql'` while the default is `'softcrew'`.
- The tenant connection has `'database' => ''` (`config/database.php:163-180`).
- Runtime connections use **landlord `env()` credentials**, not the per-tenant users.
- There are 170 runtime `env()` calls. [I] They break under `config:cache`.

### 1.4 Identity
- Users are duplicated: landlord `users` plus tenant `tenant_users` with a second guard.
- Tenant login is a raw `DB::table('tenant_users')` lookup after switching the default connection.

### 1.5 Sessions
- `TenantAwareSessionManager` replaces the session singleton and builds `tenant_session_{id}` connections with landlord root credentials.
- The `shared_sessions` table is created only by a **root repair script** (`create_tenant_sessions_table.php`, `USE tenant_nrna`).

### 1.6 Provisioning
- Four competing provisioners.
- The main path migrates `database/migrations/tenant`, which **does not exist**.
- Cleanup **drops the database** and soft-deletes the tenant. Retries then fail with "tenant already exists". If the update branch is taken, cleanup can drop an **existing** tenant's database.
- `POST /tenant-provisioning/api` has no authentication and is CSRF-exempt.

### 1.7 Migrations and queue
- Tenant migrations are loaded into the landlord, so landlord `migrate` creates tenant tables (`TenantAuthServiceProvider.php:128-130`).
- The queue runs in the landlord, yet a command creates `jobs` tables in every tenant.
- `tenancy()` (the stancl helper, not installed) is referenced.

### 1.8 Infrastructure
- No Dockerfile, compose, nginx or Kubernetes files.
- Wildcard DNS / TLS / `server_name *.domain` exist **only in documentation**.
- Ansible grants cover `election_%` but not `tenant_%`.
- A plaintext `mysql_root_password` sits in `scripts/inventory.example.yml:36`.

### 1.9 Tests
- SQLite in-memory.
- 68 tenancy test files under `tests/Contexts/**` belong to **no PHPUnit suite**.
- Production code has `sqlite` branches that switch isolation **off**.
- The hybrid finder has no test.

### 1.10 Other hygiene
- Unauthenticated debug routes are loaded in all environments; some create or drop databases (`routes/debug*.php`).
- About 20 repair scripts at the repository root are hard-coded to tenant `nrna`.
- `cookies.txt` and SQL backups are committed.

## 2. Reusable concepts (take the idea, not the code)
| Concept | Why it is reusable |
|---|---|
| A central registry of organisation → database name + status | The minimum information needed for fail-closed resolution |
| A hostname column / registry, with an exact-match lookup first | Exact host match is deterministic and cheap; `election`'s `DOMAIN_EXACT` is the right first strategy |
| **One** early resolution step that rejects unknown or inactive tenants | `IdentifyTenantFromRequest`'s 404 behaviour is right; it must be the *only* step and must run *before* session-dependent work |
| A single `tenant` connection configured in one place | Avoids per-call-site switching |
| A tenant id carried in queued-job payloads and restored on the worker | Necessary for async correctness; Laravel 11 `Context` gives it natively |
| A provisioning job with status, retries and backoff on a dedicated queue | The right shape; it needs real idempotency |
| "Migrate all tenants from the registry" command | Needed for schema evolution |
| Local development via `*.localhost` | Browsers resolve `*.localhost` to 127.0.0.1 without editing `/etc/hosts` |

## 3. Do NOT copy (and why)
| Pattern | Why not |
|---|---|
| Several resolvers, several registries, several `Tenant` classes | No single authority, so the answer depends on which code path runs |
| Fail-open finder (no status check; unknown host means landlord) | Directly contradicts "no tenant context → no tenant DB access" |
| `str_contains` / first-label host parsing | Accepts foreign hosts (`nrna.publicdigit.com.evil.tld`, `example.com`) |
| `DB::setDefaultConnection` toggling at call sites, hard-coded `'mysql'` | Leaks connections between tenants; breaks transactions |
| Tenant-local users and a second guard | `nrna1` already has global identity; duplicating it creates drift and double authentication |
| A replaced `SessionManager` with per-tenant session tables | Unnecessary; sessions belong in central |
| `env()` at runtime | Breaks under `config:cache` |
| Destructive cleanup on failed provisioning | Can drop a live tenant database |
| Tenant migrations loaded into the landlord | Defeats isolation; needs cleanup commands afterwards |
| SQLite-only tests and production `sqlite` bypasses | Tests never exercise the real switching |
| Unauthenticated provisioning and debug routes | Severe security exposure |
| A per-tenant database user that silently falls back to landlord credentials | False sense of isolation |

## 4. Conclusion
**Adopt the eight concepts in §2. Adopt none of `election`'s tenancy code or its package wiring.**

`nrna1`'s global identity and UUID keys remove the two hardest problems `election` struggles with (tenant-local authentication and id remapping). `election`'s documentation describes a better system than its code implements, so **its documents must not be used as evidence of working behaviour**.
