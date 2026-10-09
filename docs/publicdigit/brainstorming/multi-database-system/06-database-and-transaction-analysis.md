# 06 — Database, raw SQL and transaction analysis

Counts from the earlier assessment (grep, not re-measured): `DB::transaction(` ×44, `DB::beginTransaction` ×7, `DB::table(` ×139, `DB::select` ×4, `*Raw()` ×40, `withoutGlobalScope(s)` ≈400, explicit `where('organisation_id', …)` ≈331. Only `ElectionSecurityEvent` pins a connection (`pgsql_audit`).

## 1. Which connection should be the default? (measured, not theoretical)

| Option | Code to change | Transaction behaviour | Verdict |
|---|---|---|---|
| **A1:** default = central; tenant models opt in (`$connection='tenant'`) | ≈75 tenant models + ≈113 tenant `DB::table` calls rewritten | **All 44 `DB::transaction()` blocks open on central while their writes go to tenant, so atomicity is silently lost** | Rejected |
| **A2:** default = tenant (resolver, fail closed); central models opt in (`$connection='central'`) | ≈8 central models; ≈26 raw central-table sites (writes only); ≈20 validation rules (`unique:users` → `unique:central.users`); framework config (session, queue, cache, password broker) | Business transactions stay on the tenant connection, **unchanged and correct** | **Recommended** |

**Fail-closed property:** under A2, code that forgets its context fails with a `TenantNotResolved` exception. Under A1 it silently writes to central.

## 2. Raw SQL classification

| Kind | Examples (FACT) | Under A2 |
|---|---|---|
| Tenant raw SQL (majority) | election, vote and membership queries via `DB::table('elections'|'codes'|…)` | Unchanged |
| Central-table reads in a tenant flow | joins to `users` / `organisations` / `user_organisation_roles` (≈25 joins, ≈7 `whereHas`) | Keep working **through the identity projection** in the tenant DB |
| Central-table writes | e.g. `ResetElection.php:197-211`, `AssignmentController.php:21`, `RoleController.php:20` (≈26 sites) | Must be `DB::connection('central')` (the projection is read-only) |
| Ambiguous (cross-organisation) | `DashboardResolver.php:751-812,925-942`, `User::getActiveElection` (`User.php:1285-1375`), platform dashboard, `AdminElectionController`, sitemap, demo list | Rewrite to read the central `election_directory` |
| Dormant `DB::connection('tenant'|'landlord'…)` | Geography, Membership provisioning, `RegisterMemberRequest.php:102-130` | **Quarantine first** (they would come alive) |

## 3. PostgreSQL foreign keys across the boundary (Q14)

A physical database cannot hold an FK to another database (FACT: PostgreSQL has no cross-database FKs).

| FK target | Referencing tenant tables (from `05`) | Solution |
|---|---|---|
| `organisations.id` | 46 tables (`organisation_id … FK`) | **Local projection row** of the own organisation in each tenant DB, so the FKs stay valid. Also used for self-identification. |
| `users.id` | ≈31 tables: elections (4 actor columns), candidacies, codes, voter_registrations, voter_slugs, demo_voter_slugs, images, uploads, organisation_users, members, voters, member_import_jobs, election_memberships (assigned_by), election_officers, candidacy_applications, organisation_invitations, membership_* (types, fees, renewals, applications, payments), organisation_participants, newsletter_recipients, newsletter_attachments, contributions, points_ledger, voter_verifications, voter_invitations, election_state_transitions, election_audit_logs, voter_ip_verifications | **Local read-only `users` projection**, a subset: id, name, email, … **no password or credentials**. Rows are upserted when a user gains a grant in the organisation or appears as an actor. |
| `user_organisation_roles` | the composite FK from `election_memberships` was **dropped** (`2026_05_19_000001`) | No FK left. Joins use the projected grants. |
| `geo_administrative_units` / `countries` | `members.residence_geo_unit_id`, `users.residence_geo_unit_id`, `committees.geo_unit_id` | **Mirror** of global geo plus the organisation's own units |

**Alternative considered:** drop the FKs and enforce references in application code.
- **Rejected for now:** more code, and it loses database-level integrity.
- **Note:** `onDelete cascade` from central `users` no longer reaches tenant rows. User deletion (GDPR) must **fan out** to every tenant through the projector (`07`).

## 4. Transactions across the boundary (Q12)

| Flow (FACT location) | Writes | Classification | Smallest practical solution |
|---|---|---|---|
| Voting: code entry, cast, `has_voted` CAS (`CodeController`, `VoteController::store`) | tenant only | **SAFE** | none |
| Election create / settings / officers / candidacies | tenant only (actor FKs via projection) | **SAFE** | none |
| Member import creating users (`ProcessMemberImportJob`) | central `users` + grant, then tenant `members` | **REQUIRES CENTRAL-FIRST** | Central user + grant (idempotent by email), then projector, then tenant member. Re-runnable per row. |
| Organisation creation (`OrganisationController.php:347-363`) | central `organisations` + owner grant + (new) DB provisioning + tenant seed | **REQUIRES SAGA** (simple) | Central rows + `organisation_databases(status=provisioning)` → `ProvisionOrganisationDatabase` job → `isolated`. UI shows "being prepared". In shared mode it is instant. |
| Registration (`RegisterController.php:42-73`, attaches to the platform org) | central `users` + platform grant (+ platform tenant projection) | **REQUIRES CENTRAL-FIRST** | Central transaction; the projection is asynchronous or reconciled |
| Invitation acceptance (`organisation_invitations`, tenant) | tenant invitation status + central grant | **REQUIRES CENTRAL-FIRST** + idempotent tenant update | Look up the token via the directory; write the central grant; then mark the tenant invitation accepted (idempotent; reconcile on failure) |
| Platform admin approves / rejects an election | tenant only (after resolving the org via the directory) | **SAFE** with `TenantContext::switchTo(org)` before the transaction | `switchTo()` refuses inside an open transaction |
| Outbox publish (aggregate + `outbox_events`) | tenant only | **SAFE** (requires outbox in the tenant DB) | — |
| User profile change / deletion | central `users` + projections in N tenants | **REQUIRES OUTBOX** (central → tenants) | Central projector events, idempotent upserts, nightly `tenants:reconcile-identities` |

No flow needs distributed transactions (2PC). All are central-first, idempotent and reconcilable.

## 5. Migrations (Q15)
**RECOMMENDATION:**
- **Schema-identical migrations** run per database. The same migration set runs on the central database and on every tenant database.
- Plus a small `tenant_adjustments` set:
  - the projection `users.password` becomes nullable;
  - the `users.organisation_id` FK in tenant DBs is dropped or relaxed;
  - REVOKE privileges on central-only tables inside tenant DBs.
- A later clean-up can split the folders into `central/` and `tenant/` with a per-connection `schema:dump` (supported: `MigrateCommand.php:336-340`).
- **Commands:** `tenants:migrate` (iterate the registry; record `schema_version`; continue on error; report). The resolver may refuse a tenant below the minimum schema version.
- **Must fix first:**
  - the two context migration folders that are not loaded;
  - the accidental `false` table;
  - the 5 code-referenced tables with no migration (`05` §G).

## 6. Sequences, ids, types
- **UUID primary keys:** no remapping.
- **`bigint` primary keys** (`organisation_invitations`, `organisation_newsletters`, newsletter children, `incomes`, `election_security_events`, `member_directories`, governance tables, divergence tables, `geo_administrative_units`): after the copy, run `setval(seq, max(id))` per tenant DB. They are independent sequences per database (no conflict).
- **ULID / string keys** (committees): no sequences.
- **Mixed organisation key types** (uuid, string(50), string(64), `tenant_id`): the extraction manifest declares the key column and type per table.
