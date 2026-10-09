# 01 — Executive summary

## The question
Can `nrna1` serve `nrna.publicdigit.com`, `nc.publicdigit.com`, `cpnuml.publicdigit.com`, `nrnagermany.publicdigit.com`, … from **one Laravel application**, where each hostname resolves to **one organisation**, and each organisation's business data lives in **its own PostgreSQL database**, while keeping the domain, routes, UI and workflows?

## Answer
**YES — with conditions.**

The change is mostly in **persistence and request edges**, not in the business application. That conclusion rests on four facts:
1. **FACT — the organisation is already the universal partition key.**
   - 53 of 95 tables carry a UUID `organisation_id`; 46 of those have an FK to `organisations` (`05`).
   - The `BelongsToTenant` global scope is on about 45 models (`app/Traits/BelongsToTenant.php:44-63`).
2. **FACT — primary keys are UUIDs on almost all tenant tables**, so rows can be moved to another database without remapping ids. A few tables use `bigint` or ULID keys; their sequences need resetting after copy (`10`).
3. **FACT — identity is already global.**
   - `users.email` is globally unique.
   - Login does not depend on an organisation (`LoginController.php:46`).
   - Users join organisations through `user_organisation_roles` (`UNIQUE(user_id, organisation_id)`).
   - This removes the hardest problem the `election` repository fights with: tenant-local authentication.
4. **FACT — there is no host-based routing today.**
   - The organisation comes from the URL path (`/organisations/{organisation:slug}/…`), the session, or a header (`app/Http/Middleware/TenantContext.php:50-85`).
   - Hostname resolution can therefore be **added in front** of the existing resolution without rewriting routes.

## Target in one picture
```text
<org>.publicdigit.com ──► one Laravel deployment (wildcard vhost)
        │
        ▼  ResolveOrganisationFromHost (central: organisation_domains)
   TenantContext (single authority) ──► membership check (central user_organisation_roles)
        │
        ▼  TenantDatabaseResolver (central: organisation_databases, fail closed)
   PostgreSQL database of that organisation
```

- **Central DB:** identity, organisations, grants, roles, sessions, queue, the hostname registry, the database registry, the routing directory, geography.
- **Tenant DB:** elections, voting, membership, finance, committees, governance, outbox/inbox, audit, demo data.

## Conditions (all required)
1. **Hybrid, not pure tenant.**
   - Identity, access and the registries stay **central**.
   - Tenant databases get a **read-only identity projection** (users subset, the organisation's own row, its grants). This keeps the ~31 tenant tables with FKs to `users`, and all joins, working (`06`).
2. **Default connection = tenant (fail-closed resolver).** Only about 8 central models are pinned to `central`. All 44 `DB::transaction()` blocks then stay on the tenant connection, unchanged (`06`).
3. **Host is validated.** Today `TrustHosts` is not registered and `trustProxies(at: '*')` is on (`bootstrap/app.php:30`). Host-based tenancy needs a **trusted-host allow-list plus a registry lookup**; an unregistered host is rejected (`09`).
4. **Links are built for the tenant's host.** Mails, notifications and signed URLs currently use `APP_URL` or the request host (`04`). The URL root must follow `TenantContext`, also inside queued jobs.
5. **Non-HTTP paths get explicit tenant context.**
   - The schedule, the outbox/inbox processors and async jobs run without context today and already give wrong results (earlier assessment §2.8).
   - The fix is a `TenantRunner` and Laravel `Context` propagation.
6. **Cross-organisation features move to central read models**: login dashboard, platform admin, sitemap.
7. **Cross-database write flows are redesigned as central-first, idempotent and reconciled**: organisation creation, registration, invitation acceptance, member import.
8. **Existing security holes are closed first**:
   - the unprotected admin election approval routes;
   - the `X-Tenant-Id` header trusted without a membership check;
   - the unregistered `TrustHosts`.

## What stays unchanged
- Every route path and URL pattern. Hostnames are **added**; path URLs keep working.
- All Vue/Inertia pages and the `app/Contexts/*/Domain` code.
- Policies' logic, the voting workflow and anonymity design, the `organisation_id` columns (kept as defence in depth), the tenant raw SQL and transactions.

## Biggest risks
| Risk | Why |
|---|---|
| Non-HTTP context | Already broken today; would hit the wrong database after the split |
| Cross-DB atomicity | Registration, organisation creation, invitations |
| Host header trust | Today the Host header is unvalidated; it would *select a database* |
| Incomplete extraction | 4 organisation-key styles, nullable organisation rows, 4 tables with no organisation key at all, 5 tables used in code with no migration |
| Cookie / SSO decision | Host-only cookies isolate tenants but require a login per hostname; a shared `.publicdigit.com` cookie enables SSO but weakens isolation (`07`) |

## Recommended first step
A **reversible proof of concept with no production data** (`16`):
- `nrna.publicdigit.test` and `nc.publicdigit.test` resolve to two test organisations;
- their `TenantContext` selects `nrna_test_t1` and `nrna_test_t2`;
- the tests prove isolation, fail-closed behaviour, central identity and job propagation.

The **security pre-fixes** come before it, as separate stories.
