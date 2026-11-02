# INV-ORG-SCOPE-1 Stage 1: static investigation of organisation scoping and access control

**Date:** 2026-11-02 · **Author:** Claude (static investigation; authority: generated)
**Status:** RESEARCH, for review.
- **Static inspection only.** No test or database access, no change to product code, configuration or data. No `withoutGlobalScopes()` call was changed.
- **Stage 2 (tests, implementation) is not authorised.**

**Commission:** Principal decision 2026-11-02, "Decision 2: approved scope for static investigation only", items 1–6.
**Invariant under test:** `2026-11-01-organisation-inv-org-scope-1-organisation-scoped-access-decision-record.md` (principle approved by the PO 2026-11-01; ARB ratification, OQ-OS-1…3 unchanged).
**Appendix:** `2026-11-02-inv-org-scope-1-stage1-bypass-classification.md`: all 379 sites, one row each.
**Baseline:** branch `election-flow-updated`, HEAD `0c782012c`.
**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0).

**Labels:**
- **[F]** verified by the lead reviewer in current source (file:line given);
- **[H]** hypothesis from the classification, not re-verified by the lead reviewer;
- **[I]** inference;
- **[R]** recommendation;
- **[U]** unknown.

**No finding here is confirmed at runtime.**

---

## 0. Summary

1. **Of the 379 `withoutGlobalScopes()` lines, 359 are real calls** (20 are comments).
   - 223 are compensated by an explicit organisation predicate or a parent-key chain (C1).
   - 73 are apparent platform-level or cross-organisation operations (C2).
   - 43 touch models with no organisation filter (C3).
   - **18 real calls are potential defects (C4)**, collapsing into **7 distinct problems** (§3).
   - 10 calls are in services with no caller found.
2. **No call names a scope.** Every call also removes the **soft-delete** filter. 198 rows were flagged as possibly returning deleted records where it matters.
3. **The missing-organisation fallback never fails closed** [F]. Depending on whether an organisation with slug `platform` exists, it either substitutes that organisation or degrades to `organisation_id IS NULL`. A fail-closed alternative (`ScopedByOrganisation`) exists in the code but **no model uses it**.
4. **The policies are organisation-scoped** [F]. The cross-organisation role merge is confined to dashboard entry and routing.
5. **The unvalidated `X-Tenant-Id` path (PBDIGIT-266 item 5) is contained on the election-management group by per-election officer policies** [F]. The debug route still allows cross-organisation existence checks.
6. **Outside the scoping question, but too serious to leave out:** the demo vote controller **writes the application key (`APP_KEY`) and voters' private keys to the log** [F]. A new backlog item is proposed: `PBDIGIT-317`.

---

## 1. Method

**Inventory.** `grep -rn "withoutGlobalScopes(" app --include=*.php` gave 379 lines. A heuristic mapped each line to its model. Scope facts were taken per model (`BelongsToTenant`, `SoftDeletes`, other global scopes).

**Classification.** Four read-only reviewers classified 92 + 91 + 84 + 112 lines against one rubric:

| Class | Meaning |
|---|---|
| C1 | compensated |
| C2 | platform-level / cross-organisation |
| C3 | the model has no organisation filter |
| C4 | potential defect |
| C5 | unresolved |

Each row also carries a `soft` flag. Completeness was checked mechanically: every input line appears exactly once.

**Verification.** The lead reviewer re-read in code every C4 that drives a conclusion, plus items 2–5. Rows not re-read stay [H].

**Reviewer judgement calls (recorded in the appendix):**
- A filter on `election_id` of an election whose organisation was checked upstream counts as C1.
- Reloading by id an entity a caller already validated counts as C2.

**Limits.**
- Static reading cannot see runtime configuration, deployed data or every caller.
- C1/C2 are "apparent"; a few rest on caller analysis that may be incomplete.

## 2. Results by commissioned item

### Item 1: the 379 bypasses

| Class | Count | Typical case |
|---|---|---|
| C1 compensated | 223 | `->withoutGlobalScopes()->where('organisation_id', $organisation->id)` on a route-bound organisation; relationships keyed by an already-checked `election_id` |
| C2 platform / cross-organisation | 73 | console commands, platform administration with a domain guard (e.g. approve / reject guarded by `platform_admin`), routing before an organisation is chosen |
| C3 no organisation filter | 43 | `User`, `Organisation`, `ElectionOfficer`, `UserOrganisationRole`; only the soft-delete filter is removed |
| C4 potential defect | 20 rows: 18 calls + 2 comment rows | see §3 |
| C5 unresolved | 20 rows: 10 comments + 10 calls in `App\Services\CacheService` and `App\Services\DeviceFingerprint` with no caller found | — |

**Soft-delete effect (all classes):** 198 rows flagged Y, 47 unsure. The most significant are in §4.

### Item 2: organisation-specific role and access-grant lookups and caching

**Policies filter by organisation** [F]. These query `user_organisation_roles` by `user_id` **and** `organisation_id`:
- `app/Policies/OrganisationPolicy.php:14-20,46-54`;
- `app/Policies/MembershipPolicy.php:104-110`;
- `app/Policies/ElectionPolicy.php:121-127`;
- `app/Policies/GeoUnitPolicy.php:29-35`.

**The cross-organisation merge** [F]:
- `User::getDashboardRoles()` reads roles **without** an organisation filter and caches them per user for 3,600 s (`app/Models/User.php:1080-1102`).
- It feeds `CheckUserRole` (`dashboard.role:*`, `routes/web.php:483-496`).
- The gated dashboards show only the user's own data:
  - `AdminDashboardController.php:21-37` lists the user's own admin organisations;
  - `CommissionDashboardController.php:21-41` lists the user's own commission elections.

  **Effect: entry and routing, not another organisation's data** [I].
- `TenantContext::userHasAccess` and `EnsureOrganisationMember` accept **any** role row for the organisation (`app/Http/Middleware/TenantContext.php:103-108`; `EnsureOrganisationMember.php:119-122`). That is membership, not authority, which is correct as long as role checks follow in policies.

**Legacy flag (C12):**
- `users.is_committee_member` grants the commission dashboard (`CheckUserRole.php:60-63`).
- It is the **only** gate on `/admin/voting-security/*` (`committee.member` middleware, `routes/election/electionRoutes.php:691-715`).
- See §3, P-5.

### Item 3: the missing-organisation fallback

[F]:
- `app/Traits/BelongsToTenant.php:48-58` (read) and `:70-80` (create) substitute the organisation with slug **`platform`** when the context is null or 0.
- The seed and factories use **`publicdigit`** (PBDIGIT-309).
- If no `platform` row exists, the substituted id is `null`. Laravel turns `where(col, null)` into **`IS NULL`** (`vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php:933-937`).

So, depending on deployed data [U]:
- **(a)** a request without an organisation context reads and writes the `platform` organisation's data; or
- **(b)** it reads **every row with no organisation**, and its writes fail on NOT NULL columns (PBDIGIT-309 test evidence).

**Neither path fails closed.** INV-ORG-SCOPE-1 now answers PBDIGIT-309's second question in principle: fail closed, never substitute.

**A fail-closed implementation already exists:** `app/Models/Traits/ScopedByOrganisation.php:17-40` throws when `TenantContext` is null. **No model uses it** [F].

### Item 4: `X-Tenant-Id` handling and the slug mismatch

**`tenant` middleware** (`app/Http/Middleware/TenantContext.php:49-56`) [F]:
- If the header is present, its value becomes the session organisation and `TenantContext`.
- **Before** authentication and **without** the membership check that the normal path performs (`:71-80`).

**Where it is used:**
- `routes/election/electionRoutes.php:263` (election-management group);
- `:732` (debug route);
- the `session-api` group (`bootstrap/app.php:54-62`), for which no route use was found [I].

**Priority:** the middleware runs **before** route-model binding (`bootstrap/app.php:126-132`).

**Containment** [F]:
- Every route in the management group carries `->can(...)` on the bound election (`electionRoutes.php:268-300`).
- `ElectionPolicy::manageSettings` / `publishResults` require an active officer record for **that election id** (`app/Policies/ElectionPolicy.php:46-60`).
- That does not depend on tenant context, so **the header alone does not grant management.**

**Residual** [F]:
- the debug closure (`electionRoutes.php:719-732`) returns organisation ids for any slug to any verified user;
- the session keeps the header value for later requests by unauthenticated visitors (`TenantContext.php:52,58-59`).

**`tenant.api` (`IdentifyTenantFromHeader`) does check membership** [F]. Its declared order puts it before `auth` (`routes/api_v1.php:13`), so a missing user leads to an error rather than a bypass [I].

**Slug mismatch:** confirmed as described in PBDIGIT-309 (`BelongsToTenant.php:54,77` vs `database/seeders/OrganisationSeeder.php:29`).

### Item 5: coordination with PBDIGIT-266

| PBDIGIT-266 item | This investigation |
|---|---|
| 1 (`/admin/elections/pending` etc., `auth:sanctum, verified` only) | **corroborated:** the pending list is cross-organisation (`app/Http/Controllers/Admin/AdminElectionController.php:18`) [H]. Approve / reject are guarded by the domain `platform_admin` guard (C2), as the 266 revision already found. |
| 5 (`X-Tenant-Id` trusted) | **refined:** contained on the management group; residual debug-route disclosure; session persistence (item 4 above) |
| new, proposed for 266 | P-1 … P-6 below |

## 3. Potential defects (C4), grouped into problems

| # | Problem | Sites | Evidence | Business significance |
|---|---|---|---|---|
| **P-1** | **Election route binding ignores the organisation** (and includes deleted elections). Controllers that do not compare `$election->organisation_id` with the route organisation expose another organisation's election. | `app/Models/Election.php:90-92` (binding); `app/Http/Controllers/BallotPreviewController.php:28-32` + `app/Traits/ChecksElectionAccess.php` (any owner / admin / commission of the **URL** organisation passes); `ElectionVotingController.php:33` show [H]; `ResultController.php:19,228` (published results, may be intended) [H] | binding and ballot preview **[F]**; others [H] | an administrator of organisation A could view organisation B's ballot preview, and possibly election details. A cross-tenant read. |
| **P-2** | **Session organisation switched by the request, before or without authorisation** | `ElectionManagementController.php:1444-1448` (set **before** `authorize`) **[F]**; `:385-411` `startDemo` (organisation from the URL slug, `auth` only) [H] | — | a later request on routes that rely on the session organisation runs in another organisation's context. Impact depends on those routes [I]. |
| **P-3** | **"First active election of any organisation" fallbacks** | `app/Http/Middleware/ElectionMiddleware.php:141-145` (also stores `selected_election_id`) **[F]**; `Demo/DemoVoteController.php:225` [H]; `VoteController.php:121` [H]; `app/Services/VoterSlugService.php:72` (reads `selected_election_id`; its callers are **inert today** per PBDIGIT-278 item 6) | — | a user can be bound to another organisation's election. Overlaps PBDIGIT-58 "Election Context Resolution". |
| **P-4** | **Vote retrieval checks the receipt only if one exists** | `VoteController.php:3602-3620` + code parsing `:3504-3530` **[F]** | — | anyone holding a vote's UUID can see a vote that has no `receipt_hash`. UUIDs are not guessable; the share of such votes is [U]. Ballot content only: votes carry no voter identity, so ADR-T11 is unaffected [I]. |
| **P-5** | **Global flag as the only gate on cross-organisation security tools** | `Admin/VotingSecurityController.php:154` (recovery links for any election id) [H]; gate `committee.member` (`electionRoutes.php:691-715`) **[F]** | — | a committee member of any organisation could issue recovery links for another organisation's election (C12). |
| **P-6** | **Latent bypasses** (no caller today) | `app/Models/Code.php:220` `scopeUnused`; `app/Models/Organisation.php:110` `resolveChildRouteBinding` [H] | — | none today; risk if used later |
| **P-7** | **Committee aggregate lookup without organisation predicate** | `Membership/Infrastructure/Repositories/EloquentCommitteeAggregateRepository.php:67`; `GetCommitteeDashboard:26` ignores `$tenantId` [H] | — | mitigated: the id comes from a route-bound, session-scoped `CommitteeModel` |

**Low severity, own data only:** `VoteController.php:2806,3047` (the user's latest code across elections; a correctness risk) [H].

## 4. Soft-delete effects worth attention

| Effect | Sites | Status |
|---|---|---|
| **A removed election officer keeps their role.** `ElectionOfficerController::destroy` soft-deletes and leaves `status = 'active'`; `Election::resolveActorRole` bypasses all scopes, so it still finds the officer. `ElectionPolicy` (which keeps the soft-delete filter) refuses them, so the two disagree. | `app/Http/Controllers/ElectionOfficerController.php` (`destroy`: `$officer->delete()`); `app/Models/ElectionOfficer.php:12` (`SoftDeletes`); `app/Models/Election.php:1840-1844`, caller `:1878` | **[F]** |
| a soft-deleted officer still marked active keeps verification authority | `ElectionOfficerVerificationAuthoritySource:25` | [H] |
| soft-deleted posts and candidacies can appear on the real and authoritative ballot | `BallotAssemblyService:23,26,68,71,119` | [H] |
| deleted elections reachable by slug (P-1); deleted votes possibly counted | `Election.php:90`; result paths (appendix) | binding [F]; counting [H] |
| `AuditCleanup` comment says trashed elections are "not found", but the bypass finds them | `AuditCleanup.php:98` | [H] |
| eligibility and newsletter audiences include soft-deleted members, memberships, officers, participants | `EloquentVoterEligibilityQueryService:154`; `NewsletterService` | [H] |

**Architectural reading [I]:** the codebase uses `withoutGlobalScopes()` to escape the organisation filter. Because no call names the scope, the soft-delete filter goes too. That is a second, unintended bypass class, not related to tenancy.

## 5. Side findings (outside the scoping question)

| # | Finding | Evidence | Action |
|---|---|---|---|
| **S-1** | **The application key and voters' private keys are written to the log at info level**: `app_key`, the concatenated hash input, `private_key` together with `vote_id` (i.e. a complete demo verification code) | `app/Http/Controllers/Demo/DemoVoteController.php:4835-4839, 4875-4884` **[F]** | **new `PBDIGIT-317`** (urgent; operational decision on log access and key rotation). The real-election path does not log the key: `VoteController.php:3583` is a return value, not a log [F]. |
| S-2 | Organisation invitation `accept` does not compare the signed-in user's email with the invitation's | `OrganisationMemberInvitationController.php:110-228` | [H]; to be confirmed; candidate for PBDIGIT-266 |
| S-3 | `TenantContext` logs `user_id` with organisation id on every request to `voting_audit` | `TenantContext.php:120-137`; `config/logging.php:122-124` | [F]; a persistence source for INV-AFF-1 item 4 |

## 6. Recommendations [R]

1. **`PBDIGIT-317` first** (S-1). Decide who may read existing logs, whether `APP_KEY` must be rotated, and remove the log lines. This needs a separately authorised slice. It is unrelated to Stage 2 of this investigation.
2. **Extend `PBDIGIT-266`** with P-1, P-2, P-3, P-5 and the removed-officer case (§4). Each is to be confirmed or refuted by a feature test in Stage 2. Highest value: P-1 (one root cause, the binding, behind several symptoms) and the removed officer.
3. **Extend `PBDIGIT-309`** with the item 3 analysis: both fallback paths, and the unused fail-closed trait as an option.
4. **Stage 2 design input:**
   - prefer naming the scope (`withoutGlobalScope(BelongsToTenant-scope)`) where only the organisation filter should go, so the soft-delete filter stays;
   - organisation-aware election binding, or an explicit ownership check, for routes nested under `{organisation}`;
   - fail-closed missing context per INV-ORG-SCOPE-1.

   These are directions, not authorised changes. No mass replacement.
5. **Stage 2 prerequisites:**
   - the isolated test database (ARB-SEC-01 gate, PBDIGIT-283 guard);
   - the ARB part of INV-ORG-SCOPE-1;
   - a decision on whether published results (ResultController) are meant to be public across organisations.

## 7. Open questions

| # | Question | Owner |
|---|---|---|
| OQ-S1-1 | Are published election results meant to be visible to any signed-in user of any organisation? | PO |
| OQ-S1-2 | Which slug does production use for the platform organisation (decides fallback path (a) or (b))? | Ops / PO (PBDIGIT-309) |
| OQ-S1-3 | Share of real votes without `receipt_hash` (P-4) | Ops (read-only query, authorised separately) |
| OQ-S1-4 | Who has had access to application logs containing S-1 data? | Ops + Security |

INV-ORG-SCOPE-1's OQ-OS-1…3 and its ARB ratification are **unchanged**. OQ-OS-3 (classification) is answered statically by this report and its appendix, pending review.

## 8. Traceability

- Principal decision "Decision 2: static investigation only", 2026-11-02.
- `2026-11-01-organisation-inv-org-scope-1-organisation-scoped-access-decision-record.md`.
- `docs/publicdigit/backlog/PBDIGIT-266-…`, `PBDIGIT-309-…`, `PBDIGIT-58-…`, `PBDIGIT-278-…`.
- Appendix `2026-11-02-inv-org-scope-1-stage1-bypass-classification.md`.
