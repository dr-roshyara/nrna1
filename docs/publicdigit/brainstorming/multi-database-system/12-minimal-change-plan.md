# 12 — Minimal-change plan (KEEP / CHANGE / ADD / REMOVE)

Objective: **change the persistence and isolation boundary, not the business application.**

## KEEP (unchanged)
- All route definitions and URL patterns. Hostnames are added in front.
- All Inertia / Vue pages. Ziggy follows the request host automatically.
- All domain code (`app/Contexts/*/Domain`, `app/Domain`); business rules; policies' logic; the election and voting workflows; anonymity design; demo mode.
- The `Organisation` model and concept; UUID keys; `TenantId` value objects.
- All ~45 `BelongsToTenant` models (the scope becomes strict); all `organisation_id` columns and ≈331 filters.
- All 44 `DB::transaction()` blocks, the tenant raw SQL, and the ≈400 `withoutGlobalScopes()` (harmless inside one tenant DB).
- Login UI, registration UI, Sanctum, Socialite, Spatie roles.
- The existing single-database isolation test suite (it runs in "shared" mode).

## CHANGE
| Area | Change |
|---|---|
| `config/database.php` | Add `central`; resolver-managed `tenant` + `tenant_audit`; default → `tenant` |
| `config/session.php`, `queue.php`, `cache.php` (database store), `auth.php` (password broker) | Point at `central`; keep the session cookie **host-only** (decision S1) |
| `bootstrap/app.php` | Register trusted hosts (allow-list) + restrict trusted proxies; add the resolver middleware early; remove the `X-Tenant-Id` trust path |
| `config/sanctum.php`, `config/cors.php` | Cover the apex + `*.publicdigit.com` |
| `config/services.php` | Add the missing `google` configuration with **one apex callback** |
| Central models (~8): `Organisation`, `User`, `UserOrganisationRole`, Spatie `Role` / `Permission`, `PersonalAccessToken`, `Country`, `Region` (+ global geo) | `protected $connection = 'central'` |
| `ElectionSecurityEvent` | `pgsql_audit` → `tenant_audit` |
| `App\Services\TenantContext` | Single authority; mirrors into Laravel `Context`; `switchTo()` purges; refuses inside a transaction; sets the URL root for jobs and CLI |
| `BelongsToTenant` | Read only `TenantContext`; throw when absent |
| `EnsureOrganisationMember` | Also checks host org == URL org; sets `TenantContext` |
| ~26 raw central-table writes; ~20 `unique:users` / `exists:` rules | Use `central` |
| ~6 cross-organisation readers (`DashboardResolver`, `User` helpers, platform dashboard, admin elections, sitemap, demo list) | Read `election_directory` |
| ~13 scheduled entries + outbox / inbox | `TenantRunner` / `tenants:run` |
| ~4 cross-DB write flows (registration, org creation, invitation acceptance, member import) | Central-first, idempotent, reconcile |
| Mail / notification links, password reset, email verification | Tenant host (via URL root) or apex (identity) |
| SEO / canonical (`config/meta.php`, `SeoService`) and hard-coded hosts | Per host |
| `tenant_log()`, `ElectionAudit` | Read `TenantContext` |
| `tests/TestCase.php` | Central + tenant connections; a shared-mode registry row for factory orgs |

## ADD
| Artefact | Purpose |
|---|---|
| `organisation_domains` (central) | hostname → organisation; `is_primary`; status (active / redirect / disabled) |
| `organisation_databases` (central) | organisation → database, status (shared / provisioning / migrating / isolated / suspended), schema_version |
| `tenant_route_keys` (central) | org-less URL keys → organisation |
| `election_directory` (central) | cross-org read model |
| `jobs` migration (central) | for the async queue |
| `ResolveOrganisationFromHost` (inside a single `ResolveTenantDatabase` middleware) | One resolution point |
| `TenantDatabaseResolver` (`DB::extend`) | Fail-closed connection + self-identification |
| `TenancyServiceProvider` | Resolver, Context hydrate, job reset, URL root |
| `TenantRunner` + `tenants:run`, `tenants:migrate`, `tenants:provision`, `tenants:extract`, `tenants:verify`, `tenants:reconcile-identities` | Operations |
| `ProvisionOrganisationDatabase` job | Idempotent provisioning |
| `TenantIdentityProjector`, `TenantDirectoryWriter` | Projections |
| `config/tenancy.php` | Table classification and extraction manifest (also a test oracle) |
| `database/migrations/tenant_adjustments/` | Tenant-only schema differences + privilege REVOKEs |
| Hostname validation (DNS label rules + reserved names) | Registration safety |
| Tests | `13` |

## REMOVE (only where proven necessary)
- `X-Tenant-Id` trust (`TenantContext.php:50-56`) and pre-auth header resolution.
- The session as a tenant authority. A guarded fallback stays only for the legacy session-driven voting routes, on an allow-list.
- Dormant `DB::connection('tenant'|'landlord'|…)` code paths and the Spatie `NotTenantAware` imports (package not installed).
- The broken `TenantContextInterface` binding (`AppServiceProvider.php:96-99`).
- `Cache::flush()` in Geography (2 sites).
- The accidental `false` table (migration hygiene).
- Later, optional: FK *constraints* to `organisations` (never the columns).

## Implementation order (first → later)
1. **Security pre-fixes** (V1–V5) + dormant-code quarantine + migration hygiene. These are separate stories.
2. **Single tenant context + `TenantRunner` + Context propagation.** This fixes today's non-HTTP defects in the shared DB.
3. **PoC** (`16`): host → org → context → test DB, two test databases, fail-closed. Reversible; no production data.
4. Connections + registries in shared mode (default = tenant).
5. Hostname resolution in production (all orgs still `shared`), trusted hosts, URL root, cookies.
6. Routing directory + `election_directory`.
7. Provisioning + projector + `tenants:migrate`.
8. Pilot extraction → progressive cutover → purge.

## Do NOT implement yet
- Per-tenant database roles / secret store (optional, decision Q6).
- SSO across subdomains (S3) or shared cookies (S2).
- Custom domains (`vote.nrna.org`).
- Shorter tenant URLs or redirects that change the route structure.
- Any data extraction before phases 1–5 are green.
- Any package (Spatie / stancl): not needed with Laravel 11 `DB::extend` + `Context`.
