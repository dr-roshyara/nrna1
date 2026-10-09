# 08 — Queues, events, background processing, cache, files, logs

## 1. Current state (FACT)
- **Queue:** default `sync` (`config/queue.php:16`, `.env.example:26`). The `database` driver is defined, but **no migration creates `jobs`**.
- **Queued classes** (ShouldQueue): OrganisationNewsletterMail, RepresentativeInvitationMail, OrganisationCreatedMail, Membership mails, SecondVerificationCode, SendFirstVerificationCode, SendVoteSavingCode, CsvRowEmailNotification, WelcomeEmailNotification, ElectionReadyForActivation, OfficerAppointedNotification, SendVoterInvitation, SendNewsletterBatchJob, DispatchNewsletterBatchJob, ProcessMemberImportJob.
- **Schedule:** 13 entries (`routes/console.php:35-63`).
  - **None sets a tenant context.**
  - `BelongsToTenant` falls back to a platform slug `platform` that does not exist. Several jobs therefore process only the "fallback" scope or nothing [I, earlier assessment §2.8].
  - Examples: `GenerateAnnualMembershipFees`, `MarkOverdueMembers`, `elections:flush-expiring-caches`, `outbox:process`, `inbox:redrive`.
- **Listeners:** synchronous; they inherit the request context.
- **Implicit session / HTTP dependencies:**
  - `tenant_log()` reads the session (`app/Helpers/tenant.php:25-31`);
  - `ElectionAudit` reads the session (`app/Helpers/ElectionAudit.php:63-66`);
  - controllers write `session('current_organisation_id')`.

**INFERENCE:** jobs work today only because the queue is `sync`. Under an async worker, scoped `find()` returns null and the jobs silently do nothing.

## 2. Target: context survives HTTP → queue → worker (Q10)

```mermaid
sequenceDiagram
  participant H as HTTP request (nrna.publicdigit.com)
  participant TC as TenantContext
  participant Q as Central queue (jobs)
  participant W as Worker
  H->>TC: set(NRNA) → also Context::addHidden('tenant', NRNA)
  H->>Q: dispatch(job) — payload carries hidden Context (Laravel 11)
  W->>W: JobProcessing → Context hydrated → TenantContext::set(NRNA)
  W->>W: DB::purge('tenant'); URL::forceRootUrl(primary host of NRNA)
  W->>W: unserialize models (tenant models → NRNA DB; central → central)
  W->>W: handle()
  W->>W: JobProcessed/Failed → TenantContext::clear(); DB::purge('tenant'); URL root reset
```

- **Laravel 11 `Context`** (verified in the earlier assessment: `ContextServiceProvider.php:29,39`; `Queue/Worker.php:429,442`) propagates the tenant **without changing any job class**.
- **Model restoration:** `SerializesModels` restores on the model's connection name: `tenant` (now NRNA) or `central`.
- **Worker hygiene is mandatory.** The static `TenantContext` and the `tenant` PDO must be reset after every job, or they leak into the next job.
- **The URL root** follows the tenant, so queued mails link to `nrna.publicdigit.com` (`04` §4).
- **Queue storage:** `jobs` / `failed_jobs` on **central**. One queue for all tenants; **no tenant `jobs` tables** (an `election` anti-pattern).
- **Failed jobs and retries:** the payload keeps the hidden tenant, so a retry runs in the same tenant. Platform jobs (provisioning, directory rebuild) run with **no tenant**, so any tenant query throws (fail closed).

## 3. Scheduled and console work
- `TenantRunner::each(fn () => …)` iterates `organisation_databases` (status `shared` or `isolated`). For each tenant it does: switch, purge, run, catch and record the error, continue.
- `tenants:run {command} [--org=]` wraps organisation-scoped commands. **Tenant-specific commands require an explicit `--org` or the runner (Q-CLI).**
- The outbox and inbox processors become per-tenant loops. At scale: dispatch one job per tenant with pending rows.

**This also fixes today's defects**, and can ship **before** any database split.

## 4. Events, outbox, inbox, notifications
| Item | Target |
|---|---|
| Domain events / listeners | Synchronous; they inherit the context |
| `outbox_events` | **Tenant** table, so the outbox write is in the same transaction as the aggregate |
| `inbox_events`, `processed_events` | Tenant |
| Central → tenant events (identity projection) | A central outbox (new) consumed per tenant |
| Notifications / mailables | Covered by Context propagation + URL root; recipient `User` restored from central |

## 5. Cache (Q-cache)
**FACT:**
- `file` store; global prefix (`config/cache.php:108`);
- about 108 call sites, mostly keyed by election, user or organisation UUID;
- `Cache::flush()` at `GeographyLookupService.php:308` and `GeographyService.php:357`;
- legacy global keys (`ElectionService.php:16,97,108`);
- tenant key `user.{id}.organisation_id` (`TenantContext.php:66`).

**RECOMMENDATION:**
- UUID keys are already unique across tenants, so **no mandatory prefix**.
- Remove the two `Cache::flush()` calls (they would wipe every tenant).
- Review the legacy global keys: either key them by election id or prefix them with `tenant:{org}:`.
- If Redis becomes the store, keep one Redis; physical separation is not needed.

## 6. Files (Q-files)
**FACT:**
- Organisation-prefixed: candidacy photos, imports, election logos (`CandidacyManagementController.php:83,122`, `MemberImportController.php:106-108`, `ElectionManagementController.php:734-735`).
- **Not** organisation-prefixed:
  - organisation logos at creation (`OrganisationController.php:324`);
  - newsletter attachments `newsletters/{id}`;
  - user images `users/{user_id}`;
  - `files/`, `upload_files/`, CSV exports;
  - the audit log folder `logs/audit/{election slug}_…` (`ElectionAuditService.php:138`).

**INFERENCE:** database isolation does **not** isolate files.

**RECOMMENDATION:** a target layout `organisations/{organisation_id}/…` for tenant files, plus `platform/…` for central files. Migrating existing files can go in the same per-organisation extraction step. Whether file isolation is part of the guarantee is a decision (Q8 in `15`).

## 7. Logs
- `tenant_log()` and `ElectionAudit` must read `TenantContext`, not the session.
- The integer return type of `current_organisation_id()` is wrong for UUIDs (`app/Helpers/tenant.php:72-74`).
- Laravel `Context` adds `tenant` to every log record, which gives per-tenant observability without per-tenant files.
