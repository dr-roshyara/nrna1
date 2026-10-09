# 05 — Central vs tenant data classification (all 95 tables)

## Method (FACT)
- Every `Schema::create` in **all migration folders that are actually loaded** was enumerated: `database/migrations/`, `database/migrations/landlord/`, and the `app/Contexts/**/Migrations/{Tenant,Landlord}` folders registered by providers.
- That gives **95 distinct tables**. There are 97 create calls: `geo_administrative_units` and `election_security_events` are each created twice.
- No migration sets a `$connection`, so today everything lives in **one database**.
- Two context migration folders are **never loaded**, because `loadMigrationsFrom` does not recurse: `Geography/.../Migrations/Tenant` and `Membership/.../Migrations/Landlord`.

## Classification rules
| Target | Rule |
|---|---|
| **CENTRAL** | Needed **before** a tenant is known (identity, sessions, routing, registries), or genuinely platform-wide reference data, or framework infrastructure shared by all tenants (queue, cache). |
| **TENANT** | Business data owned by exactly one organisation. It moves to that organisation's database. |
| **PROJECTION** | A read-only copy of central data inside a tenant DB (to keep FKs and joins), **or** a central read model built from tenant data (directory). |
| **UNKNOWN** | The repository cannot decide it. It needs a domain or governance decision (`15`). |

"FK users" means the table holds a database FK to `users` (an FK that crosses the boundary once tenants are split, see `06`). "Risk" is the migration risk: L/M/H.

## A. Identity, access, framework → CENTRAL

| Table | Org ownership today | Target | Reason | Risk |
|---|---|---|---|---|
| organisations | is the organisation | **CENTRAL** + own-row **PROJECTION** in each tenant | Registry and identity anchor; tenant FKs (`organisation_id → organisations`) need a local row | M |
| users | `organisation_id` uuid NOT NULL FK **restrict** (home org pointer) | **CENTRAL** + subset **PROJECTION** | Global identity (unique email, org-agnostic login). ~31 tenant tables reference it. | H |
| user_organisation_roles | org uuid FK cascade, user FK | **CENTRAL** + per-org **PROJECTION** | Access grant, checked before tenant data is touched; needed across orgs (dashboard) | M |
| sessions | none | CENTRAL | Read before any tenant is known | L |
| password_reset_tokens | none | CENTRAL | Identity | L |
| personal_access_tokens | none (polymorphic `tokenable_id`) | CENTRAL | Sanctum authentication | L |
| failed_jobs | none | CENTRAL | One queue for all tenants (payload carries the tenant) | L |
| cache, cache_locks | none | CENTRAL | Framework infrastructure | L |
| roles, permissions, model_has_roles, model_has_permissions, role_has_permissions | none (Spatie **teams off**) | CENTRAL | Platform-global roles (`hasRole('admin')`); `model_id` is a string with no FK | M (check whether any role is meant per organisation) |
| countries | none | CENTRAL + PROJECTION (mirror) | Global reference data; tenant FKs (`members.residence_geo_unit_id` → geo units → country) | L |
| regions, region_country | none | CENTRAL (+ mirror if referenced) | Global reference | L |

## B. Election and voting → TENANT

| Table | Org ownership today | Target | Reason | Risk |
|---|---|---|---|---|
| elections | uuid FK cascade; FK users (settings_updated_by, submitted_by, approved_by, rejected_by) | TENANT | Core aggregate; also **feeds the central `election_directory` (PROJECTION)** | M |
| posts | uuid FK | TENANT | Child of election | L |
| candidacies | uuid FK; FK users set null | TENANT | — | L |
| codes | uuid FK; FK users cascade | TENANT | Participation authority (`has_voted`) | M |
| voter_registrations | uuid FK; FK users | TENANT | — | L |
| voter_slugs | uuid FK; FK users | TENANT | Key feeds the central routing directory | M |
| voter_slug_steps | uuid FK | TENANT | — | L |
| votes | uuid FK; **no user_id** (anonymity) | TENANT | Copy as-is; never log contents (ADR-T11) | **H** (integrity) |
| results | uuid FK | TENANT | Same anonymity constraint | **H** |
| receipt_codes | none; FK elections | TENANT (via parent) | — | L |
| election_memberships | uuid **no direct FK** (composite FK to elections) | TENANT | Voter registration | M |
| election_officers | uuid FK; FK users | TENANT | — | L |
| candidacy_applications | uuid FK; FK users | TENANT | — | L |
| voter_verifications | uuid FK; FK users | TENANT | Identity evidence | L |
| voter_ip_verifications | uuid FK; FK users | TENANT | IP evidence | L |
| voter_invitations | uuid FK; FK users | TENANT | Token feeds the routing directory | M |
| election_state_transitions | none; FK elections; FK users | TENANT (via parent) | — | L |
| election_audit_logs | none; FK elections; FK users | TENANT (via parent) | Tenant-local audit | L |
| election_security_events | none; FK elections; written via `pgsql_audit` | TENANT (via parent) | Needs a `tenant_audit` connection | M |
| public_demo_sessions | none; FK elections | TENANT (via parent) | Platform demo (platform org) | L |
| demo_posts, demo_candidacies, demo_codes, demo_voter_slugs, demo_voter_slug_steps, demo_votes | uuid FK | TENANT | Demo data belongs to the organisation that owns the demo election | L |
| demo_results | uuid **nullable, no FK** | TENANT | NULL rows need classifying (Q4) | M |

## C. Membership, finance, communication → TENANT

| Table | Org ownership today | Target | Reason | Risk |
|---|---|---|---|---|
| organisation_users | uuid FK; FK users | **TENANT** (recommended) | Organisation-level membership record (User → OrganisationUser → Member). Access authority stays in `user_organisation_roles`. | M (**decision Q**: some readers may need it centrally) |
| members | uuid FK; FK users (created_by/updated_by); FK geo | TENANT | — | M |
| voters | uuid FK | TENANT | — | L |
| member_import_jobs | uuid FK; FK users | TENANT | — | L |
| membership_types, membership_fees, membership_renewals, membership_applications, membership_payments | uuid FK; FK users | TENANT | — | L |
| organisation_participants | uuid FK; FK users | TENANT | — | L |
| organisation_invitations | uuid FK; FK users; **bigint PK** | **TENANT + routing directory entry** for the token | Invitation acceptance crosses the boundary (creates a central grant), see `06` | **H** |
| contributions, points_ledger | uuid FK; FK users | TENANT | — | L |
| incomes | uuid FK (added later); `user_id` bigint **no FK**, a type mismatch | TENANT | Legacy column | M |
| organisation_newsletters | uuid FK; bigint PK | TENANT | — | L |
| newsletter_recipients, newsletter_attachments | none; FK newsletters; FK users | TENANT (via parent) | — | L |
| newsletter_audit_logs | uuid **no FK** | TENANT | — | L |
| messages, images, uploads, calendars, events | uuid **nullable** FK | TENANT; **NULL rows: decision (Q4)** | Mixed platform / organisation data | M |
| member_directories | uuid **no FK** | TENANT (local read model) | — | L |

## D. Governance, committees, adjudication → TENANT (with key-style caveats)

| Table | Org ownership today | Target | Reason | Risk |
|---|---|---|---|---|
| committees | `organisation_id` **string(50)**, no FK; ULID PK | TENANT | Extraction must filter on the string form | M |
| committee_assignments | uuid, no FK | TENANT | — | L |
| committee_associations | uuid FK | TENANT | — | L |
| committee_membership_applications | string(50), no FK | TENANT | — | M |
| committee_structures | uuid, no FK | TENANT | — | L |
| committee_structure_levels | none; FK committee_structures | TENANT (via parent) | — | L |
| committee_role_mappings, role_localizations | `tenant_id` string(50); seeded with `'example-tenant'` | TENANT; **seed rows: decision** | The seed value is not a real organisation | M |
| governance_level_definitions | `tenant_id` string(36) | TENANT | — | M |
| committee_member_projection | `tenant_id` uuid | TENANT (local PROJECTION) | — | L |
| committee_governance_projections, processed_events | none; `committee_id` string, no FK | TENANT (via logical parent) | Extraction needs a join through `committees` | M |
| determinations, adjudication_processes, challenges, election_applied_determinations | `organisation_id` **string(64)**, no FK | TENANT | — | M |
| projection_rebuild_runs | `tenant_id` string(26) **nullable** | **UNKNOWN** (tenant or central operations log) | — | M |
| role_hierarchy | none (seeded global reference) | **UNKNOWN** → probably CENTRAL reference + mirror | — | M |
| governance_decisions, constitutional_committees | **none at all** | **UNKNOWN** | No organisation column; cannot be extracted per organisation without a domain decision (Q3) | **H** |
| sovereignty_divergence_summaries, divergence_observation_windows | none; `election_id` uuid **no FK** | TENANT (via election), **to confirm** | Extraction must join through `elections` | M |

## E. Infrastructure and messaging

| Table | Org ownership today | Target | Reason | Risk |
|---|---|---|---|---|
| outbox_events | uuid FK | **TENANT** | A transactional outbox must share the database of the aggregate write | M |
| inbox_events | uuid FK | TENANT | Idempotency per consumer and tenant | M |
| dead_letter_queue | uuid **nullable, no FK** | **UNKNOWN** (central ops vs tenant) | — | L |
| geo_administrative_units | uuid **nullable** FK (added later); bigint PK; self-FK | **CENTRAL for global units + TENANT for organisation units** — decision | Mixed table; organisation-specific units must not leak across tenants | **H** |
| `false` | none | **REMOVE** (accidental table from `2026_05_19_215036_fix_election_memberships_unique_index_mysql_compat.php`) | Bug | L |

## F. New tables required by the target (ADD)

| Table | Target | Purpose |
|---|---|---|
| organisation_domains | CENTRAL | hostname → organisation (routing registry) |
| organisation_databases | CENTRAL | organisation → database name, status, schema version |
| tenant_route_keys | CENTRAL (PROJECTION of tenant keys) | election slug, voter slug, token hash → organisation |
| election_directory | CENTRAL (PROJECTION) | cross-organisation read model (dashboard, platform admin, sitemap) |
| jobs (+ job_batches if needed) | CENTRAL | async queue (no migration exists today) |

## G. Tables used in code with no creating migration (FACT, must be resolved before extraction)
| Table | Used at |
|---|---|
| deligate_candidacies, deligate_votes | `app/Http/Controllers/DeligateVoteController.php:81,414,583` (only in a backup migration folder) |
| election_commission_members | `app/Services/DashboardResolver.php:425-531` (guarded by `Schema::hasTable`) |
| geo_candidate_units | `GeographyCandidateService.php:51` |
| outbox_processing_errors | `OutboxEventProcessor.php:84` |

## Summary counts
| Category | Count |
|---|---|
| CENTRAL (incl. framework and reference) | 18, plus organisations / users / grants with projection |
| TENANT | ≈67 |
| PROJECTION (existing, tenant-local read models) | 4 |
| UNKNOWN | 7: governance_decisions, constitutional_committees, role_hierarchy, projection_rebuild_runs, dead_letter_queue, geo_administrative_units (split), NULL-organisation rows of 6 tables |
| Remove | 1 (`false`) |

## organisation_id after the split (Q5)
**RECOMMENDATION: keep every `organisation_id` column.**
- **Defence in depth:** the `BelongsToTenant` scope (strict) still filters by it inside the tenant database, so a misrouted connection returns nothing instead of foreign data.
- **Self-identification:** each tenant DB holds exactly one `organisations` row, so the resolver can verify *DB organisation == resolved organisation* on connect.
- **Projections and directory rows need it** (`election_directory`, `tenant_route_keys`).
- **Removing it costs:** changes to ≈53 tables, ≈331 query sites and the extraction tooling, for no isolation gain.
- **Optional later (after the central purge):** drop the **FK constraints** to `organisations` if the projection row is ever removed. **Never the columns.**
