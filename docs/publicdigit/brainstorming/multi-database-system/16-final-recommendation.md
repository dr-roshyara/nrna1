# 16 — Final recommendation

## 1. Option comparison
Scores: 1 = poor, 5 = best. Judgement based on the evidence in `02`–`10`.

| Criterion | A: shared DB + `organisation_id` (today) | B: DB per org (pure, tenant-local identity) | C: schema per org | **D: DB per org + central identity** | E: separate deployment per org |
|---|---|---|---|---|---|
| Isolation | 2 (logical; ≈400 scope bypasses) | 5 | 4 (cross-schema queries possible) | **5** | 5 |
| Security | 2 (V1–V5) | 4 | 3 | **5** | 4 (but N patch targets) |
| DDD alignment | 3 | 3 (duplicates identity) | 4 | **5** (identity upstream, tenant downstream) | 3 |
| Operational complexity | 5 | 2 | 3 | **3** | 1 (N deployments, N configs) |
| Migration complexity | 5 (none) | 1 (identity migration per tenant) | 3 | **3** | 1 |
| Scalability | 3 | 4 | 3 (one DB, many schemas) | **4** | 4 |
| Maintainability | 3 | 2 | 3 | **4** | 1 |
| Testing | 4 | 2 | 3 | **4** | 2 |
| Cost | 5 | 3 | 4 | **3** | 1 |
| Compatibility with current `nrna1` | 5 | 1 (rewrites authentication) | 3 (`search_path` + raw SQL) | **4** | 2 (shared identity across deployments is hard) |
| Compatibility with `*.publicdigit.com` | 3 (host → org is possible on a shared DB) | 4 | 4 | **5** | 3 (routing to N apps) |
| **Total** | 40 | 31 | 37 | **45** | 27 |

**RECOMMENDATION: Option D.** One deployment; hostname → organisation → own PostgreSQL database; central identity, access and routing.
- **Option C (schema per organisation)** is the recorded fallback if operations cannot run many databases.
- **Option A + hostnames** is a valid **interim state** (phase 5 in `10`): hostnames go live before any data moves.

## 2. Answers to Q1–Q20

| # | Question | Answer |
|---|---|---|
| Q1 | Can `nrna1` serve `nrna`/`nc`/`cpnuml`/`nrnagermany.publicdigit.com` without separate Laravel applications? | **Yes.** One deployment, wildcard DNS / TLS / vhost, a host resolver, a registry. Nothing in the code requires one application per organisation (FACT: no host or deployment coupling exists). |
| Q2 | Can organisation-specific domain behaviour remain? | **Yes.** It is keyed by `organisation_id` / `TenantId`, which is unchanged. |
| Q3 | Can routes remain largely unchanged? | **Yes.** Hosts are added in front; the path routes keep working; org-less URLs resolve through the directory (`04` §3.4, §5). |
| Q4 | Can the domain model remain largely unchanged? | **Yes.** No domain-layer change; `Organisation` gets no infrastructure columns. |
| Q5 | `organisation_id`? | **Keep it everywhere** (defence in depth, self-identification, projections). Only FK constraints may be dropped later (`05`). |
| Q6 | `users`? | **Central.** Read-only subset projected into tenants (no credentials). `users.organisation_id` stays a central preference, never a DB selector. |
| Q7 | `user_organisation_roles`? | **Central access authority**, projected per tenant for joins. |
| Q8 | Authentication? | **Central, unchanged in mechanism.** The host names the tenant; membership is checked centrally; password reset, verification and OAuth run on the apex. |
| Q9 | Sessions? | **One central table**; cookies **host-only** per tenant host (decision D1). |
| Q10 | Queues / jobs? | Central queue; tenant carried by hidden Laravel `Context`; worker restores the tenant, the connection and the URL root, then resets; `TenantRunner` for the schedule. |
| Q11 | Cross-organisation queries? | Removed from request paths; served by the central `election_directory` read model; platform admin acts per tenant via `switchTo()`. |
| Q12 | Cross-database transactions? | None needed. Tenant-only flows stay atomic; the 4–5 cross-boundary flows become central-first, idempotent and reconciled (`06` §4). No 2PC. |
| Q13 | Raw SQL? | Tenant raw SQL is unchanged (default = tenant); ≈26 central-table writes go explicitly to `central`; joins to identity work through the projection. |
| Q14 | PostgreSQL FKs? | No cross-database FKs. FKs to `organisations` (46 tables) and `users` (≈31 tables) stay valid against **local projections**; cascades from central become projector fan-out. |
| Q15 | Migrations? | Schema-identical migrations per database + `tenant_adjustments`; `tenants:migrate` from the registry with `schema_version`; migration hygiene fixed first. |
| Q16 | New organisation provisioning? | Central rows → hostname reserved → idempotent `ProvisionOrganisationDatabase` job (create DB, migrate, project, verify) → `active` (`11` F). |
| Q17 | Hostname registration? | `organisation_domains` row; validated DNS label; reserved names; admin confirmation; wildcard DNS / TLS means no per-org DNS change; renames keep a redirect (`04` §9). |
| Q18 | Tenant database discovery? | `organisation_databases` (central) via `TenantDatabaseResolver`, from `TenantContext` only; self-identification on connect. |
| Q19 | Wrong-tenant access prevention? | Trusted hosts + exact registry match + consistency rule + central membership + fail-closed resolver + self-row check + strict scope + switch guard + privilege REVOKEs (`09`). |
| Q20 | Minimum production change? | `12` CHANGE / ADD: config (DB, session, queue, cache, auth, Sanctum, CORS, services); ~8 central models; `TenantContext`, `BelongsToTenant`, `EnsureOrganisationMember`; trusted hosts / proxies; one resolver middleware; ~26 raw writes and ~20 rules; ~6 cross-org readers; ~13 scheduled entries + outbox / inbox; ~4 cross-DB flows; mail-link URL root; new registries, commands and projectors. **Roughly 5 % of PHP files; no domain, route or UI rewrite.** |

## 3. The final conclusion

**Feasible:**
```text
PublicDigit Platform → Central PostgreSQL → {NRNA DB, NC DB, CPN-UML DB, …} ← {nrna, nc, cpnuml}.publicdigit.com
```
It preserves the domain, application services, routes, UI, organisation concepts, authentication, business rules and election workflows.

1. **Can remain unchanged:**
   - routes and URLs, Vue/Inertia;
   - domain and application services, policies' logic, workflows, anonymity;
   - `organisation_id` columns and filters, transactions, tenant raw SQL;
   - login and registration UI.
2. **Must change:**
   - the connection model (default tenant, central pinned);
   - the tenant context authority;
   - Host / proxy trust, cookie, Sanctum and CORS settings;
   - link generation;
   - cross-org readers, non-HTTP paths, cross-DB write flows;
   - central raw writes and validation rules.
3. **Must be added:**
   - `organisation_domains`, `organisation_databases`, `tenant_route_keys`, `election_directory`, `jobs`;
   - host resolver, DB resolver, tenancy provider, runner and commands, provisioning job, projector, manifest;
   - multi-DB tests.
4. **Must be removed:**
   - `X-Tenant-Id` trust;
   - the session as tenant authority;
   - dormant `tenant` / `landlord` code;
   - the broken interface binding;
   - `Cache::flush()`;
   - the accidental `false` table.
5. **Riskiest assumptions:**
   - (a) the default-connection switch preserves all behaviour;
   - (b) the hidden `Context` round-trip works under the real queue;
   - (c) the identity projection stays consistent;
   - (d) the extraction manifest is complete (4 key styles, NULL rows, 4 keyless tables, 5 migration-less tables);
   - (e) host-only sessions are acceptable to users.
6. **Implement first:**
   - the security pre-fixes (V1–V5);
   - quarantine of the dormant code;
   - a single `TenantContext` + `TenantRunner` (fixes today's defects);
   - then the **PoC** below.
7. **Do NOT implement yet:**
   - per-tenant DB roles, SSO, custom domains;
   - shorter URLs;
   - any data extraction;
   - any tenancy package.
8. **Human / business decisions:** D1–D15 in `15`, above all D1 (cookies / SSO), D2 (apex role), D3 (ownership), D4 / D5 (unclassified data), D6 / D7 (credential and file isolation).

## 4. Recommended first implementation candidate (PoC) — **not implemented**

**"Host → Organisation → TenantContext → Tenant DB" in a branch, with test databases only.**

```text
nrna.publicdigit.test → organisation_domains → NRNA → TenantContext → organisation_databases → nrna_test_t1
nc.publicdigit.test   → organisation_domains → NC   → TenantContext → organisation_databases → nrna_test_t2
```

**Scope:**
- add a `central` connection (same physical test DB) and a resolver-managed `tenant` connection;
- make `tenant` the default;
- pin `User`, `Organisation` and `UserOrganisationRole` to `central`;
- add the two registry tables (test only);
- add `ResolveOrganisationFromHost` (exact match) + trusted hosts for `*.publicdigit.test`;
- make `BelongsToTenant` strict.

**Proves (each as a RED test first):**
1. hostname → organisation (and unknown host → 404);
2. organisation → `TenantContext`;
3. `TenantContext` → connection (NRNA writes land in `t1`, NC writes in `t2`);
4. tenant A cannot see tenant B (same queries, different databases);
5. no tenant context → fail closed (exception, never a default DB);
6. global identity stays central (one user logs in on both hosts, with membership checked per host);
7. existing domain and application code keeps working (a representative existing feature test runs on the NRNA host unchanged);
8. a `database`-queue job dispatched on the NRNA host runs against `t1` (validates the hidden `Context` round-trip).

**Why:**
- it validates the riskiest assumptions with **zero production data** and **zero data migration**;
- it is reversible by dropping the branch;
- its only strict prerequisite is the security pre-fix story (V2 / V4).
