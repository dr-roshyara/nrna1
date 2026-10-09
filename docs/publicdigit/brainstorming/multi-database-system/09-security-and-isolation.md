# 09 — Security and isolation

## Target invariants
1. **No tenant context means no tenant database access.** The resolver throws; it never falls back to a default or shared database.
2. **The hostname's organisation, the authenticated membership, and every other organisation key in the request must agree.**
3. **One request or job has exactly one tenant.** Switching inside a transaction is refused.
4. **A tenant database identifies itself**: exactly one `organisations` row, whose id must equal the resolved organisation.

## 1. Pre-existing vulnerabilities (FACT, fix first, independent of multi-DB)
| # | Finding | Evidence | Why it becomes worse with per-org DBs |
|---|---|---|---|
| V1 | Admin election pending / approve / reject routes protected only by `auth:sanctum, verified`; the controller has no authorisation | `routes/election/electionRoutes.php:678-688`; `AdminElectionController.php:16-68` | Cross-organisation enumeration and approval would become cross-**database** writes |
| V2 | `X-Tenant-Id` header sets the tenant with **no membership check** | `app/Http/Middleware/TenantContext.php:50-56` | A header would **select a database** |
| V3 | `IdentifyTenantFromHeader` runs before `auth` | `bootstrap/app.php:16-19` | Membership check without a user |
| V4 | `TrustHosts` not registered; `trustProxies(at: '*')` | `bootstrap/app.php:30`; `TrustHosts.php:14-18` | With host-based tenancy, a forged `Host` / `X-Forwarded-Host` **selects a tenant** and poisons generated links (password reset) |
| V5 | "No context" matches NULL rows (platform slug mismatch `platform` vs `publicdigit`) | `BelongsToTenant.php:54` | Silent mis-scoping instead of an error |

These are **discovered work outside this investigation**. Recommended as separate stories; **not fixed here**.

## 2. Threats and mitigations

| Threat | Mitigation |
|---|---|
| **Tenant spoofing via Host** | Trusted-host allow-list + exact registry lookup in `organisation_domains`; unknown or inactive hosts → 404; never parse the "first label" or use `str_contains` (`election` anti-pattern) |
| **`X-Forwarded-Host` manipulation** | Trust only the real proxy IPs |
| **Tenant id headers** (`X-Tenant-Id`, `X-Organisation-ID`) | Removed as a selection source; the host decides. If an API needs a header, it is used only *after* `auth`, must match the host, and requires membership. |
| **Session-based tenant switching** | Session is no longer an authority; host-only cookies (S1) bind the session to the tenant host |
| **Missing membership checks** | `EnsureOrganisationMember` stays on authenticated tenant routes; the host only names the tenant |
| **Incorrect DB resolution** | The registry is the only source; never build a connection with an empty database name (libpq connects to a DB named after the user [I]); self-identification check on connect; `schema_version` check |
| **Fail-open** | The resolver throws `TenantNotResolved`; strict `BelongsToTenant` (no session or slug fallback) |
| **Cross-tenant queries** | Physically impossible after the split; the ≈400 `withoutGlobalScopes()` become harmless inside one tenant DB. Cross-org readers move to `election_directory`. |
| **Cross-tenant cache** | UUID keys; remove `Cache::flush()`; review the global keys |
| **Cross-tenant files** | Organisation-prefixed storage paths (decision) |
| **Admin bypass** | Platform admin acts on a tenant only via `TenantContext::switchTo(org)` resolved from the directory, with an audit record; no "query all tenants" in request paths |
| **CLI bypass** | Tenant commands require `--org` or `TenantRunner`; commands without context fail closed; `tenants:reset-db` refuses outside `local` |
| **Job context leakage** | Context hydrate at `JobProcessing`; clear + purge at `JobProcessed` / `JobFailed` |
| **Worker connection leakage** | `DB::purge('tenant')` per job; `switchTo()` refuses with an open transaction (avoids `election`'s stale-PDO bug) |
| **Host-header-poisoned links** | Generate tenant links from the registry's primary hostname, not from the request host, in mails |

## 3. Isolation layers (target)
| Layer | Mechanism |
|---|---|
| Edge | Reverse proxy default server rejects unknown hosts; TLS wildcard |
| Resolution | One middleware; registry exact match; consistency rule |
| Connection | `DB::extend('tenant')` resolver over `TenantContext`, fail closed |
| Self-identification | Tenant DB `organisations` has exactly one row = the resolved org |
| Scope (retained) | Strict `BelongsToTenant` filter on `organisation_id` |
| Database privileges | Inside tenant DBs: REVOKE on central-only tables; after the purge: REVOKE tenant tables in central |
| Optional | A per-tenant PostgreSQL role with `CONNECT` only on its own database (decision Q6) |

## 4. PostgreSQL-level permissions (assessment)
- **Phase 1 (recommended):** one application role with access to all tenant databases. Isolation comes from the resolver and self-identification. Simple operations.
- **Phase 2 (optional):** a role per tenant, credentials in a secret store (not in the DB registry, and **never** a silent fallback to a superuser like `election` does). A wrong database name then fails at authentication.
- **Security note:** a per-tenant role protects against application bugs, **not** against a compromised application host (which holds all credentials).

## 5. Anonymity (ADR-T11, preserved)
- Splitting databases does not change the `votes` / `results` schema (no `user_id`).
- Extraction copies those tables as-is, without logging contents or adding correlation columns.
- The routing directory stores **voter slug → organisation only**. It holds no user or vote reference.
