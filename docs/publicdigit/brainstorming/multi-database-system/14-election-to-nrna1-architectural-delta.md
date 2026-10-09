# 14 — `election` → `nrna1` architectural delta

| Capability | `election` (FACT) | `nrna1` (FACT) | Target | Reuse? |
|---|---|---|---|---|
| Tenant identification by host | Spatie `HybridTenantFinder` (exact `domain` → `*.localhost` → path); 5 more resolvers; `str_contains` / first-label parsing | None | One middleware; exact match in `organisation_domains`; trusted-host allow-list | **Concept only** (exact-match first; `*.localhost` for dev) |
| Unknown or inactive tenant | Finder: fail-open to landlord; one middleware: 404 | n/a | 404, no session write, no tenant connection | Concept (the 404 behaviour) |
| Tenant registry | 3 registries (`tenants`, `tenant_databases`, `elections.tenant_slug`), 5 `Tenant` classes | `organisations` only | `organisations` + `organisation_domains` + `organisation_databases` (separate concerns, one class each) | Concept |
| DB naming | 3 schemes (`tenant_{slug}`, `tenant_` + underscores, `{slug}_db`) | n/a | Stored explicitly in the registry, generated once (e.g. `pd_t_<short uuid>`), **not** derived from the slug at runtime | No |
| Connection switching | ~40 `DB::setDefaultConnection` toggles; empty database default; runtime `env()` | Single default connection | `DB::extend('tenant')` resolver over `TenantContext`; default = tenant; fail closed | Concept (one tenant connection) |
| Identity | Users duplicated (`users` + `tenant_users`, two guards) | Global users, global email, org-agnostic login | Central identity + read-only tenant projection | **No** (`nrna1` is already better) |
| Sessions | Replaced `SessionManager`, per-tenant `shared_sessions` created by a repair script | One DB session table, host-only cookie | One central session table, host-only cookies | No |
| Cookies / SSO | `SESSION_DOMAIN` env only; docs propose `.domain` | Host-only | Host-only now (S1), SSO handshake later (S3) | No |
| URL generation | Path, subdomain and hard-coded styles mixed; undefined `config('app.domain')` | `APP_URL` / request host | URL root follows `TenantContext` (jobs / CLI); identity links on the apex | No |
| Provisioning | 4 provisioners; missing migration path; destructive cleanup; non-idempotent retries; unauthenticated endpoint | None | One idempotent job, status machine, no destructive cleanup, platform-admin only | Concept (job + status + retries) |
| Migrations | Tenant migrations leak into the landlord; referenced folders missing | One set, one DB; 2 folders unloaded; accidental `false` table | Schema-identical per DB + `tenant_adjustments`; `tenants:migrate` from the registry | Concept (migrate-all command) |
| Queue | Spatie tenant-aware jobs (payload key `tenantId`); landlord queue + tenant `jobs` tables (conflict) | `sync`; no tenant in payloads | Laravel 11 `Context` (hidden); central queue; worker reset | Concept |
| Schedule / CLI | Ad-hoc loops; hard-coded `'mysql'` | Runs without context (defect) | `TenantRunner`, `tenants:run --org` | Concept |
| Per-tenant DB credentials | Per-tenant MySQL user with **silent fallback to landlord credentials** | n/a | One app role first; optional per-tenant role with a secret store, no fallback | No (as implemented) |
| Infrastructure | None in repo; wildcard DNS / TLS / nginx only in docs | None in repo | Wildcard DNS + DNS-01 wildcard TLS + one vhost `*.publicdigit.com` | Concept (from docs) |
| Tests | SQLite in-memory; 68 tenancy tests outside any suite; production sqlite bypasses | pgsql; extensive single-DB isolation tests | Shared-mode suite + real multi-DB pgsql suite + fitness tests | No |
| Security posture | Unauthenticated debug and provisioning routes; secrets in repo | Admin approval routes unprotected; `X-Tenant-Id` trusted; Host unvalidated | Fix `nrna1` holes first; never adopt `election`'s routes | No |

## Reusable concepts and why
1. **Exact host lookup:** deterministic, injection-free.
2. **`*.localhost` for development:** no hosts-file editing.
3. **Fail-closed 404 for unknown or inactive tenants:** the isolation invariant.
4. **A registry with status:** enables per-organisation migration and suspension.
5. **Provisioning job with retries:** provisioning is slow and failure-prone.
6. **Tenant id in job payloads:** async correctness.
7. **A migrate-all-tenants command:** schema evolution.
8. **Wildcard DNS / TLS deployment notes:** the only workable model for `*.publicdigit.com` with one application.

## Not to copy, and why
See `03` §3. The common cause: `election` has **no single authority**, for the tenant, the registry, the connection or identity. `nrna1` must keep exactly one of each.
