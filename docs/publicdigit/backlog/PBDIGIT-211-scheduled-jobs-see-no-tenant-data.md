# PBDIGIT-211 — Scheduled jobs see no tenant data: annual fees, overdue marking, renewal reminders and voter-cache flushing silently do nothing

**Type:** Defect (production, multi-tenancy) · **Epic:** `PBDIGIT-EPIC-02` Membership Management (also `EPIC-03` for the cache flush) · **Created:** 2026-10-01
**Found by:** test-estate classification (`docs/publicdigit/reviews/2026-10-01-backlog-completion-review.md` §8), election area (`FlushExpiringVoterCaches`), followed to its root cause
**Raised by:** `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578` (self-declared, not attestable)
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

| | |
|---|---|
| **Status** | 🔴 **OPEN — production-code defect; root cause runtime-confirmed in the test environment** (probe against `nrna_test`; the live production environment was not tested). ⛔ **ARCHITECTURE DECISION REQUIRED before implementation** (ARB review, 2026-10-01): **"What is the authoritative tenant-context model for non-request execution?"** (scheduled, console and queued work) It potentially governs scheduled jobs, queues, event consumers, commands, projections, tenant-scoped repositories and services, and the global scopes. **This story records the defect and the acceptance behaviour. It does not choose the implementation mechanism, and it does not authorise a local fix to the four jobs.** Not authorised. |
| **Problem** | `App\Traits\BelongsToTenant` adds a global scope that filters by the current tenant (`TenantContext::get()`, falling back to `session('current_organisation_id')`). **Scheduled tasks have neither.** The scope then falls back to the organisation with slug **`'platform'`** (`BelongsToTenant.php:50-57`), **which does not exist**: the platform organisation is created with slug **`'publicdigit'`** (migration `2026_03_05_000001_create_uuid_organisations_table.php:49`; `OrganisationSeeder.php:29`). The filter therefore becomes **`organisation_id IS NULL`**, and **every tenant's rows are invisible**. |
| **Business consequence** | The scheduled automation runs but **does nothing for real organisations**, and reports nothing wrong: <br>• **`GenerateAnnualMembershipFees`** (daily 01:00): `Member::where('status','active')` → **no annual fees are generated** <br>• **`MarkOverdueMembers`** (daily 00:30): the member update through `Member::whereIn(...)` → **members are never marked overdue** <br>• **`SendRenewalReminder`** (daily 08:00): the eager-loaded `member` resolves to null → **no renewal reminder is sent** <br>• **`elections:flush-expiring-caches`** (hourly): `ElectionMembership::…` → **voter-count and voter-stats caches are never flushed** when memberships expire. *(All four: `routes/console.php:40-52`.)* |

## Evidence

- **Runtime probe (2026-10-01, scratch test against `nrna_test`):** an organisation with one active member. With no tenant set, `Member::where('status','active')` returns **0** of 1, and compiles to `select * from "members" where "status" = ? and "members"."deleted_at" is null and "members"."organisation_id" is null`.
- **The code confirms the jobs have no tenant handling:**
  - `MarkOverdueMembers` and `GenerateAnnualMembershipFees` import `App\Models\Member` (it uses `BelongsToTenant`, `Member.php:17`) and call it without `withoutGlobalScopes()` or `TenantContext::set()`.
  - `SendRenewalReminder.php:32` states the gap itself: *"TODO Phase 3C: Iterate through TenantContext::allTenants() for multi-tenant"*.
  - No queue or scheduler hook restores tenant context (`git grep createPayloadUsing|Queue::before|TenantContext::set` over providers and jobs → none).
- **Contrast, correct:** `ExpireMemberships` uses `Member::withoutGlobalScopes()`, and `AuditCleanup` uses `Election::query()->withoutGlobalScopes()`.
- **Test:** `tests/Unit/Models/ElectionMembershipTest.php` flush tests fail for exactly this reason (election-area classification).
- **Prior record:** `PBDIGIT-74` §10.7 lists the `'platform'` vs `'publicdigit'` slug mismatch as *"reviewer-reported, not verified"*. **It is now verified.**

## Existing-story search

`git grep -i` over every `PBDIGIT-*.md`, the 76–157 backlog and the 159–209 specs for `GenerateAnnualMembershipFees`, `MarkOverdueMembers`, `SendRenewalReminder`, `FlushExpiringVoterCaches`, `renewal reminder`, `annual fees`, `scheduled job/task/command`, `no tenant context`. Only two hits:
- `PBDIGIT-58` notes that *"queues, scheduled jobs … were not"* covered by its observation.
- The completeness audit's WP-6 horizon trigger (198).

**Neither owns this.** `PBDIGIT-65`/`69` concern the request-time tenant scope of eligibility, and `74` concerns the newsletter audience.

## Scope (when authorised, and only after the architecture decision above)

> The items below are the **candidate solution space**, recorded as evidence. **They are not a choice.** An implementer must not pick between them locally across four jobs. The ARB decides the general mechanism first, and the jobs then follow it.

1. Each scheduled task that processes tenant data **iterates tenants explicitly**: per-tenant `TenantContext`, or a documented `withoutGlobalScopes()` plus an explicit `organisation_id` per row. It never relies on the ambient scope.
2. **Fix the platform fallback slug mismatch**, or remove the silent fallback so that a tenant-scoped query with no tenant fails loudly in console context. **Which of the two is part of the architecture decision above.**
3. One test per job that runs it **with no tenant set** and asserts the **business state** per tenant (fees created, members overdue, reminders queued, caches cleared).

## Out of scope

- Fee *amounts* and renewal *policy*.
- The request-time tenant scope (`PBDIGIT-65`/`69`).
- Queued (non-scheduled) jobs. Whether they run with a tenant depends on the production queue driver, which was **not** inspected (`.env` was not read). **Record it as a follow-up check, not as scope.**

## Acceptance criteria

- With two tenants and no ambient tenant, each of the four tasks acts on **both** tenants' data and on nothing else.
- A tenant-scoped query without a tenant never silently returns another organisation's rows, nor silently nothing.

## Dependencies / required authority

**1. ARB:** the tenant-context model for non-request execution (scheduled, console, queued, consumers). **2. PO** authorisation of this story once that is decided.

## Verification method

The new per-job tests (no tenant, two tenants), and the existing `tests/Unit/Models/ElectionMembershipTest.php` flush tests passing unchanged.

**Traceability:** `app/Traits/BelongsToTenant.php:41-63` · `app/Models/Member.php:17` · `app/Contexts/Membership/Infrastructure/Jobs/{GenerateAnnualMembershipFees.php:34, MarkOverdueMembers.php:50, SendRenewalReminder.php:32-41}` · `app/Console/Commands/FlushExpiringVoterCaches.php:35` · `routes/console.php:40-52` · migration `2026_03_05_000001:49` · `PBDIGIT-74` §10.7.
