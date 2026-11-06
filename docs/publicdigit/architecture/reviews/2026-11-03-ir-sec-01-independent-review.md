# IR-SEC-01: independent security and DDD architecture review of R-1 and R-2

| | |
|---|---|
| **Status** | **INDEPENDENT REVIEW (read-only). Decides and authorises nothing.** |
| **Authority** | generated |
| **Date** | 2026-11-03 |
| **Commission** | IR-SEC-01, Principal / Product Owner, 3 November 2026. Read-only review and recommendation. It does not authorise implementation. |
| **Subject** | PO-SEC-TRIAGE-01 (`docs/publicdigit/architecture/reviews/2026-11-03-po-security-triage-pbdigit-266-r1-r2.md`), related DR-07a discovery (`…-dr07a-membership-technical-discovery-m1-m5.md`) |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` printed `docs/publicdigit`, exit 0. File placed under `docs/publicdigit/architecture/reviews/`, beside the reports reviewed. |

**Labels.** [F] fact (static, read from source) · [D] decision · [P] proposal · [OQ] open question · [EO] engineering observation · [A] assumption.

**Method.** The two request paths were reconstructed from source **before** the reports were opened. All findings are static: no request, no test, no database, no log. "Runtime" below means "would happen if the code runs as read". The only commands run were `git`, `grep`, `sed`, `cat`, `find`, `php artisan route:list` (read-only), `php scripts/doc-placement.php`, and one `class_exists()` autoload lookup (no application boot, no database).

---

## A. Executive conclusion

**Classification of PO-SEC-TRIAGE-01: CONFIRMED WITH CORRECTIONS.**

The core claim holds for both items: **no organisational-authority check exists at any layer of either path** (route, middleware, FormRequest, controller, handler, domain, repository). The report's tenant-isolation reading for R-1 also holds. But three of its effect statements are wrong or unsupported by the source, and one is contradicted by the project's own tracking record:

1. **R-1 DELETE cannot currently remove anyone.** [F] `RemoveCommitteeMemberHandler.php:10` imports `App\Contexts\Governance\Domain\Committee\MemberId`, a class that does not exist (no such file; `class_exists` returned false). `MemberId::fromString` at `:24` raises an `Error`, which the controller's `catch (\Throwable)` turns into a generic 500 (`CommitteeMemberController.php`, `destroy`, final catch). The report says a member "can remove any committee member (VERIFIED static)". The authorisation gap on DELETE is real but **latent**; its effect is currently masked by a separate defect. PBDIGIT-266's own 2026-10-09 revision already records "`destroy` always 500s (missing class)"; the triage report did not carry that forward.
2. **R-2 review cannot persist any decision (approve or reject).** [F] `EloquentCommitteeMembershipApplicationRepository::saveForTenant` always calls `Model::create()` (an INSERT) with the application's existing primary key (`…Repository.php:19-31`; PK `id` string(26), migration `…2026_05_14_000001_create_committee_membership_applications_table.php:13`). Review re-inserts a row that `apply` already inserted, so the first write throws a unique-key violation (`QueryException`, not a `DomainException`, so uncaught by the controller, giving a 500). Nothing is written before it. The report's "approval partially persisted while an error is returned" scenario therefore **starts from a write that cannot succeed**. The duplicate-association defect is real in code, but it is unreachable until the application write is repaired. Runtime not verified (see J).
3. **R-2 cross-organisation review is not established.** [F] `CommitteeMembershipApplicationModel` uses `BelongsToTenant`, whose global scope filters on `TenantContext::get() ?? session('current_organisation_id')` (falling back to the `platform` organisation) in **addition to** the repository's explicit `organisation_id = route organisation` (`app/Traits/BelongsToTenant.php:36-58`; repository `:37-39`). The review route sets neither (no tenant middleware). A caller whose session organisation differs from the route organisation would get "not found" (a `RuntimeException`, 500). Cross-organisation review therefore depends on session state and probably fails for most callers. It would pass for the `platform` organisation (the default every user is attached to) or a caller whose session already holds the route organisation. The report presents "wider than recorded" as fact; it is **partially confirmed, runtime-dependent**.

What is **confirmed**: (a) R-1 POST is a working, unauthorised write path reachable by any user holding any `user_organisation_roles` row, with any committee role including `chair`; (b) R-2 has no organisation-membership check, no role check and no self-review guard, at request, handler or domain level; (c) no durable record of the acting user exists for R-1; (d) the existing tests do not test authorisation and, for R-2, are stale against the handler.

**Priority consequence [P].** R-1 POST is the only item of the two with a demonstrated *effective* write path. R-2 is an authority defect sitting behind an independent persistence defect; fixing the persistence defect without first fixing authority would *activate* the exposure. That sequencing risk is the most important operational point of this review (see F).

---

## B. Repository snapshot

| Item | Value |
|---|---|
| Branch | [F] `election-flow-updated` |
| HEAD at review start | [F] `3ef42f243` |
| HEAD at review end | [F] `f826d275b` (moved during the review: another session committed) |
| Reports reviewed written at | `821a3bb29` (earlier discovery `b95fe8fc4`) |
| Commits since `821a3bb29` | [F] `26a766124` (docs/knowledgeos), `3ef42f243` (docs/knowledgeos), `f826d275b` (`chore(claude)`, session-id hooks). `git diff --name-only 821a3bb29 HEAD` filtered to `app/ routes/ database/ resources/ config/ bootstrap/` is **empty**. No source file relevant to R-1 or R-2 changed. |
| Working tree | [F] about 34-37 modified or untracked paths at start and end (other sessions: `.claude/`, KnowledgeOS, `developer_guide/election/ip_voting/`, `bootstrap/cache/packages.php`, untracked review documents). Not touched. This review adds one untracked file. |

---

## C. Independent finding matrix

| # | Finding | Original claim | Independent evidence | Reviewer conclusion | Confidence | Remaining verification |
|---|---|---|---|---|---|---|
| F1 | R-1 effective path | `/api/v1/governance/committees/{id}/members` POST/DELETE with `web, json.api, tenant.api, auth, verified` | Routes `routes/api_v1.php:49-56`; group `bootstrap/app.php:19-22`; `route:list` shows the same middleware list for the three verbs | **Confirmed** | High | none for static |
| F2 | R-1 other mounts | slug mounts "likely 400" | Same controller mounted at `routes/governance-api.php:62-72` via `RouteServiceProvider.php:51-54` (`web, auth, verified, ensure.organisation`) and `routes/organisations.php:414-418`. `route:list` shows one slug registration per verb. `store`/`destroy` read `TenantContext::get()`; only `tenant.api` (and the unattached `tenant` middleware) set it, and neither is on the slug mounts, so they hit `Missing tenant context` 400 (`CommitteeMemberController.php:103-105`, `destroy` equivalent). Additional files `routes/api/governance.php` and `routes/api.php` import the controller but are not in the effective route list | **Confirmed** (static) | Medium-high | one request per mount on isolated DB |
| F3 | R-1 who passes `tenant.api` | any `user_organisation_roles` row | `IdentifyTenantFromHeader.php:40` calls `$request->user()->organisations()`; `User.php:172-178` is a `belongsToMany` on `user_organisation_roles` with no role filter. The header value is client-supplied but must match a row the user holds | **Confirmed** | High | none |
| F4 | R-1 policy not applied | `CommitteeAssociationLifecyclePolicy $guard` injected, unused | `CommitteeMemberController.php:44` injects it; `grep` finds no other use of `guard`, `authorize`, `Gate`, `can(`, `manageCommittee` in the file. Moreover the policy would not help: it concerns association creation (member exists, no duplicate active), not who may act | **Confirmed**, with the addition that the injected policy is **not an authority control even if invoked** | High | none |
| F5 | R-1 `manageCommittee` not on POST/DELETE | not applied | `OrganisationPolicy.php:46-60` (owner/admin only); `grep manageCommittee` finds it only in `app/Http/Controllers/Committee/*` controllers, none in the Governance API controller or routes | **Confirmed** | High | none |
| F6 | R-1 POST: any role incl. chair; self-assignment | verified static | `CommitteeMemberController.php:150` `CommitteeRole::from($request->input('role','member'))`; enum has chair, deputy, member, observer (`CommitteeRole.php`). Member must exist in the header organisation (`:130-133`, `withoutGlobalScopes` with explicit `organisation_id`). Self-assignment requires the caller to have a `Member` row; `Member::user()` is a `hasOneThrough` via `OrganisationUser`. Whether every user with a role row has a `Member` row was not established | **Confirmed**; self-assignment **plausible, not established** | Medium (self); High (others) | isolated-DB test |
| F7 | R-1 POST silently overwrites | not reported | Repository returns a **blank** aggregate (`EloquentCommitteeRepository.php:43-45`), so `addMember`'s "already assigned" rule (`Committee.php:34-36`) never fires. Listener uses `updateOrCreate` on (committee, member) (`CommitteeMemberProjectionListener.php:37-50`): repeating POST with another role **changes an existing member's role** (e.g. promote to chair) and returns 201 | **New finding [EO]** | High | test |
| F8 | R-1 DELETE effective | "can remove any committee member (VERIFIED static)" | `RemoveCommitteeMemberHandler.php:10` imports non-existent `Governance\Domain\Committee\MemberId`; `class_exists` false; no such file under `app/Contexts/Governance`. Even if it existed, `Committee::removeMember` (`Committee.php:48`) is typed to `Membership\Domain\Member\MemberId`. Result: Error to 500 generic body. PBDIGIT-266 revision records the same | **Refuted as to effect; authority gap latent.** Fixing the import alone re-opens the gap | High (class absence is mechanical) | one DELETE on isolated DB |
| F9 | R-1 tenant isolation | holds | store: member lookup `organisation_id = tenant` (`:130-133`); committee `findById` checks `committees.id` and `organisation_id` (`EloquentCommitteeRepository.php:34-37`). destroy: same `findById` first. Listener delete is by (committee, member) only (`:53-58`) but runs after the committee check. Cross-tenant change via these endpoints not evident | **Confirmed** (R-15 overstated, as the report says) | High | test with two organisations |
| F10 | R-1 header trust | n/a | Tenant is the client header, validated against the caller's own rows; a user with rows in two organisations picks which one per request. Not a leak; it means "organisation" is chosen by the caller | [EO] | High | none |
| F11 | R-1 actor attribution | actor not recorded | Events `MemberAssignedToCommittee` / `MemberRemovedFromCommittee` carry committee, member, tenant, role, time, **no actor** (`Events/MemberAssignedToCommittee.php`, `MemberRemovedFromCommittee.php`). Projection row has no actor column in the written fields (`Listener.php:37-49`); removal is a **hard delete**. `store` writes `Log::info` (organisation, committee, member; no user id) at `:107-111` and `Log::error` with trace on failure; `destroy` logs nothing. These are operational logs, not durable audit. Handlers elsewhere (slug mounts) log `user_id` to channel `voting_audit` via `EnsureOrganisationMember.php:152-159`, but that middleware is not on the effective `api/v1` path | **Confirmed**, and stronger: removal leaves **no durable trace at all** | High | check web-server access logs (not examined) |
| F12 | R-1 middleware order | `tenant.api` before `auth` | Declared order `web, json.api, tenant.api, auth, verified` (`bootstrap/app.php:19`), `route:list` shows `IdentifyTenantFromHeader` before `Authenticate`. `IdentifyTenantFromHeader.php:40` dereferences `$request->user()`. If order holds at runtime a guest with a valid header gets an `Error` (500), not 401. Laravel's priority sorting could reorder; not verified | **Confirmed as declared; runtime order unverified** | Medium | one guest request |
| F13 | R-1 CSRF | not reported | `api/v1/*` is exempt from CSRF (`bootstrap/app.php:68-70`) while auth is the session cookie (`web` group). The required custom header (`X-Organisation-ID`) forces a CORS preflight, which mitigates classic cross-site form posts; SameSite value in `config/session.php` was not read | [EO] mitigated by header; confirm session SameSite | Medium | config read |
| F14 | R-1 error disclosure | not reported | `store` returns `'Failed to add member: ' . $e->getMessage()` and `exception_class` with HTTP 500 (`:190-195`) | [EO] information disclosure of internals | High | none |
| F15 | R-1 live UI | only working UI | `CommitteeMemberManager.vue:377,455,485` calls `/governance/committees/…` through `apiClient` with `baseURL '/api/v1'` (`resources/js/services/api.js:17-20`); mounted by `Pages/Committee/Dashboard.vue`. **Consequence of F8:** the UI's remove button always fails (`alert('Failed to remove member')`, `:489-491`) | **Confirmed**; add: remove is non-functional in the UI today | High | none |
| F16 | R-2 route and middleware | no `ensure.organisation` | `routes/committee/committeeRoutes.php:26` group `['auth','verified']`, prefix `organisations/{organisation}`; route at `:80`; loaded by `routes/web.php:454`; `route:list`: `web, Authenticate, EnsureEmailIsVerified` | **Confirmed** | High | none |
| F17 | R-2 binding | slug | `Organisation::getRouteKeyName()` returns `slug` (`Organisation.php:54-57`); group re-registers `Route::model('organisation', Organisation::class)` (`committeeRoutes.php:28`); soft-deleted organisations are excluded by default binding | **Confirmed** | High | none |
| F18 | R-2 FormRequest | verified only | `ReviewMembershipApplicationRequest.php:11-14` | **Confirmed** | High | none |
| F19 | R-2 non-member can review | "VERIFIED, wider than recorded" | No membership check on the route; but see global scope in Section A item 3. Passing requires `TenantContext`/session organisation to equal the route organisation (or both to resolve to the platform organisation) **and** the application id | **Partially confirmed; runtime-dependent** | Medium | test with a user whose session organisation differs |
| F20 | R-2 self-review | applicant can approve own application | Handler passes `reviewedBy: $user->id` (`Controller.php:87-92`); `MembershipApplication::approve` checks status transition only (`:102-128`); handler compares nothing. `apply` returns `application_id` to the applicant (`:66-69`). Application ids are **ULIDs** (`MembershipApplicationId.php:17`, `Str::ulid()`; column `string(26)`), not UUIDs as the report assumes (matters little: both unguessable in practice, but ids are known to the applicant). Note: the applicant's id is stored as `member_id = user->id` (`:39`) and the reviewer's as `reviewedBy = user->id`; the **identifier spaces are the same user id**, so a self-review guard is a simple comparison, but conflating *user id* with *Member id* is itself a modelling hazard (the Governance path uses `Models\Member` ids) | **Confirmed in code; unreachable to effect today (F21)** | High | test after F21 repaired |
| F21 | R-2 decision persistence | application saved, then double write, may leave approved state and an error | Order in handler: load (`:33`), domain transition (`:38`/`:41`), **application save (`:44`)**, lineage save (`:57`), association save (`:60`), events (`:64-66`). Application save is `Model::create` on an existing PK (`Repository.php:21-30`), and it omits `reviewed_by`/`reviewed_at` and writes status from the reflected property: it can never **update**. First write throws; later writes never run; events are not published. No `DB::transaction` in the handler, controller, FormRequest or provider bindings (grep across `app/Contexts/Membership` found transactions only in unrelated handlers; `MembershipServiceProvider.php:305-308` binds the repository directly, no decorator). In the test double, `saveForTenant` is an array assignment (`tests/Doubles/InMemoryMembershipApplicationRepository.php:20-23`), which hides this | **Refuted as stated ("partial approval persisted"); replaced by: the review endpoint most probably fails on every call with no state change.** Static; high confidence on the PK reading, but production schema and whether any other code path updates the row were not checked | Medium-high | isolated-DB run of one approve and one reject |
| F22 | R-2 duplicate association persistence | association persisted twice | Latent defect, true in code: lineage repository inserts a `committee_associations` row with `lineage_id` (`EloquentMembershipLineageRepository.php:258-287`), then the association repository inserts a second row for the **same member and committee** with a different `association_id` and **no `lineage_id`** (`EloquentCommitteeAssociationRepository.php:167-176`). `lineage_id` was added NOT NULL (`2026_05_14_000002…:14`, no `nullable()`), and `assertCanCreate` would first throw `DuplicateActiveAssociationException` (a `DomainException`) because the first row is already active (`LifecyclePolicy.php:45-57`; `Exceptions/DuplicateActiveAssociationException.php:7`) or, earlier, "Member does not exist" (`:36-43`) because `memberRepository->find` is given a user id. Result if reached: 422 returned **after** application and lineage rows are written, i.e. the "error coexists with approval" scenario. **Not reachable today** (F21) | **Confirmed as latent defect, class: static, not reachable until F21 repaired** | Medium-high | after F21 |
| F23 | R-2 actor attribution | `reviewed_by` recorded | Domain stores `reviewedBy`, events carry it, but the repository never writes `reviewed_by`/`reviewed_at` (F21). `approved_by_actor_id` / `_type` are written as null on the lineage path (`:279-280`). So even with F21 repaired, **reviewer attribution is not persisted** at present | **Corrected** (report overstated) | High | none |
| F24 | Tests | CommitteeMemberApiTest targets unmounted URI; Membership test asserts 200 | `CommitteeMemberApiTest.php` calls `/api/governance/committees/…` (no such top-level route; the mounts are `/api/v1/governance/…` and `/organisations/{slug}/api/governance/…`) with header `X-Tenant-Id`; no `actingAs`; expects 200 for DELETE and for a body `'status' => 'deleted'` that the controller does not return (202, different JSON). `CommitteeMembershipApplicationTest.php:62-66` builds `ReviewMembershipApplicationHandler` with **three** arguments; the handler takes four (`…Handler.php:19-25`); the test double is never matched to the constructor. Its approve test (`:144-166`) uses `actingAs($this->user)` where the user is also the application owner, so it **codifies self-approval** and exercises in-memory doubles only | **Confirmed**, plus: the Membership test appears stale against the current handler signature | High | not run |

---

## D. Corrected security model

### D.1 R-1: effective access path

```
Client (CommitteeMemberManager.vue via apiClient, baseURL /api/v1, header X-Organisation-ID)
  -> web group (session, CSRF exempt for api/v1/*)            bootstrap/app.php:19, :68-70
  -> json.api (ForceJsonResponse)
  -> tenant.api = IdentifyTenantFromHeader                    needs header; org exists;
                                                             user has ANY user_organisation_roles row
                                                             sets TenantContext + session
  -> auth, verified                                           (declared after tenant.api)
  -> CommitteeMemberController::store | destroy               no authorisation call
        store:   Models\Member in tenant -> CommitteeRole::from(any) -> blank Committee aggregate
                 (committee must be in tenant) -> addMember -> repository->save -> Event::dispatch
                 -> CommitteeMemberProjectionListener::onMemberAssigned -> updateOrCreate (sync, no transaction)
        destroy: RemoveCommitteeMemberHandler -> Error (missing MemberId class) -> 500
```

Answers to the R-1 questions:

| Question | Answer |
|---|---|
| (a) ordinary member adds another member | **Yes**, static: passes every check if the target is a `Member` of the same organisation and the committee belongs to it |
| (b) removes a committee member | **No effect today**: fails with 500 because of the missing class. Gap is latent |
| (c) assigns themselves | **Plausible**: requires the caller to have a `Member` record; not established that all role-row holders do |
| (d) assigns `chair` | **Yes**, role is unrestricted input |
| (e) assigns `deputy` | **Yes** |
| Is `CommitteeAssociationLifecyclePolicy` invoked? | **No**; injected, never called; and it is not an authority policy |
| Is `manageCommittee` applied to POST/DELETE? | **No**, neither on the `api/v1` nor the slug mounts |
| Tenant isolation | **Holds** for committee and member (F9). A caller chooses among organisations in which they hold a row |
| Actor attribution | **Absent** in events, projection, application logs; removal would be a hard delete with no trace (F11) |
| Operational vs durable | Operational: `Log::info` / `Log::error` in `store` with no user id. Durable audit: **none** for either verb. `voting_audit` actor log exists only on the slug mounts, which return 400 for writes |

### D.2 R-2: effective access path

```
POST organisations/{organisation:slug}/committee/membership/review
  -> web, auth, verified                                     committeeRoutes.php:26; no ensure.organisation / tenant
  -> route model binding by slug
  -> ReviewMembershipApplicationRequest::authorize()         verified email only
  -> Desktop\CommitteeMembershipApplicationController::review   tenant = route org; reviewedBy = auth user id
  -> ReviewMembershipApplicationHandler
        getOrFailForTenant                                   explicit org filter + BelongsToTenant global scope
        MembershipApplication::approve|reject                 status transition only
        repository->saveForTenant(application)                INSERT on existing PK -> unique violation (static)
        [not reached] lineage save; association save (2nd, conflicting); events
```

| Question | Answer |
|---|---|
| Verified user not in the organisation can approve/reject | **Not established.** No route/request/handler check; the global scope may reject depending on session/TenantContext; and the write fails regardless (F21) |
| Applicant reviews own application | Authority path **open** at handler and domain; persistence fails today |
| Records written, order | Intended: application, lineage episode, association, events. Actual (static): none, the first write throws |
| Transaction | **None** (no handler, controller, provider or decorator wrapping) |
| Association persisted twice | **Yes in code** (lineage repo, then association repo); the second write is designed to fail (F22) |
| Later failure leaves approved application | Only if F21 is repaired alone: application and lineage rows written, then 422 or 500 on the second association write |
| Error response coexisting with persisted approval | **Not today**; **would appear** after a partial repair of F21 |
| Static vs runtime | All above static; production schema, table presence of `committee_membership_applications`, and bound repositories in production were not checked |

### D.3 What the legacy and tracked records add

[F] PBDIGIT-266 (2026-10-09 revision) already said: item 2 "may 500 (missing organisation attribute)" and item 3 "`destroy` always 500s (missing class); the slug routes return 400". Items 2 and 3 of the 2026-11-03 report diverge from those records without explaining why. This review agrees with the earlier record on `destroy` and the slug mounts, and finds the "organisation attribute" suspicion for item 2 is about a different controller (`app/Http/Controllers/Membership/CommitteeMembershipApplicationController.php`, no route points at it); the routed controller takes the organisation by route binding.

---

## E. DDD and constitutional assessment

### E.1 Ownership

| Question | Assessment |
|---|---|
| Is Governance the appropriate owner of committee organs, positions, appointments, authority? | **Consistent with the ratified act.** ACT §11 (`…ratification-act.md:309-322`): Governance "owns competence, organs, authority and organisational governance rules"; Membership owns "organisational membership truth". PO-DR07-01 (`…decision-paper.md:261-268`) states governance committees are constituted organs with positions, appointments and governance authority. Therefore appointing a chair or deputy is a Governance fact. **[F] today the write path for it lives in an HTTP controller that loads a blank aggregate and writes a read-model table**: Governance does not yet own committee composition in a durable sense; the projection is the only record (hard-deletable). |
| Which context owns "approve a committee-membership application"? | **Unresolved by authoritative records.** `CommitteeAssociation` could be membership through an organisational unit (Membership) or participation in an organ (Governance); the decision paper records the meaning as unresolved (OQ-DR07-1, M-2, `decision-paper.md:268`). The file/class names (`MembershipApplication`, `Membership` namespace) must not decide it. [OQ] |
| Does the "committee" in R-1 (Governance `committees` table, organ) and R-2 (`committee_associations`, Membership namespace) refer to the same concept? | **Not established.** Same word, two contexts, two stores. This is itself a DDD hazard. [OQ] |

### E.2 Owner / admin containment against PO-ORG-MEM-01

- [F] `manageCommittee` = platform `owner`/`admin` role rows (`OrganisationPolicy.php:46-60`). That is a **platform access role**, and PO-ORG-MEM-01 says platform access permissions "do not constitute organisational authority".
- [A] Reusing it as a stop-gap does not breach PO-ORG-MEM-01 **provided it is documented as interim, as a stand-in, and not as the source of the right to appoint or admit**. It is a restriction ("fewer people than today"), not a grant. It preserves the distinction **only if** no document or code comment presents owner/admin as the competent organ.
- [EO] Risk: owner/admin gains de facto power to appoint a committee chair, which is the thing PO-ORG-MEM-01 says platform roles must not confer. Interim wording and an expiry/replacement criterion are required (D-4 A1 uses exactly this pattern: "labelled interim, replaced by Governance later" and records `AuthorityEvidence`).

### E.3 Extending D-4 to committee-association application review

- [F] D-4 (`2026-10-09-membership-slice1b-acceptance-review-and-slice2-decision-closure.md:293`): DECIDED as A1 for "authority to admit and end" **membership**, as an interim adapter mirroring owner/admin, recording `AuthorityEvidence`, "no Membership-local policy".
- [F] Evidence in D-4: `approveApplication`/`rejectApplication` = owner/admin in `MembershipPolicy`, for the **organisation-membership application** flow (`membership_applications`), not the committee flow.
- [A/EO] The committee-association review is a different aggregate (`committee_membership_applications`) with unresolved meaning (M-2). **There is not sufficient evidence to extend D-4 as a ruling.** What can be said is narrower: using owner/admin as an interim *restriction* on that endpoint is consistent in kind with D-4, but needs its own PO decision and its own interim label; it must not be recorded as "D-4 extended".

### E.4 Questions needing statutes or an organisational representative

1. Which organ or office may change a committee's composition? Who may appoint, and who may appoint chair/deputy? Is self-appointment or a chair appointing members allowed? (O-10 / CQ-2)
2. Who is competent to decide admission to a committee or unit (CQ-3)? Is it the committee itself, a higher organ, or a general assembly?
3. What is a `CommitteeAssociation` in the organisation's own terms (unit membership, organ participation, or an eligibility record)? (OQ-DR07-1)
4. Is an applicant ever allowed to take part in the decision on their own application (recusal rule)? Self-review exclusion looks like a general principle but is a *constitutional* rule and should be confirmed.
5. What evidence must be kept for appointments (minutes, resolution reference, date of effect)? PO-ORG-MEM-01 says PublicDigit "records facts and evidence"; today R-1 records neither.
6. Is removal of a committee member a Governance fact (end of appointment, with reason) rather than a delete? Today it is a hard delete.

### E.5 Consistency with the frozen Membership Architecture

- [F] The report's R-2 remedy (review via Governance AuthorityCheck) is consistent with ACT §11. [F] The ratified aggregate boundary (§10) and fact algebra place Membership facts in the ledger, which the report says is unwired; the committee-association path writes **outside** that ledger. That is a pre-existing divergence, not something either remedy should widen.
- [EO] R2-b's "remove the second save" would delete the "backward compatibility" write for the Elections context (`Handler.php:59-60` comment). Removing it may change what Elections reads. Needs Elections-context consultation before it is called a pure fix.
- [EO] The report's proposed "no self-review" rule is a new business rule in Membership code; under PO-ORG-MEM-01 it is a constitutional question (E.4 item 4), and under the ratified boundary it is more naturally a Governance authority rule than a Membership domain rule. Treat as interim.
- No proposal in the reports contradicts the ratified act; the one wording to correct is "owner/admin" being presented as an authority (see E.2).

---

## F. Containment assessment

All options compared; none is recommended for approval here. Observations are static.

| Option | Security benefit | Operational consequence / UI | Data integrity | Reversibility | Authority assumption | Architectural fit | Approval needed |
|---|---|---|---|---|---|---|---|
| **R1-a** add `manageCommittee` to store and destroy | Closes the only demonstrated write path (POST) for ordinary members; also closes latent DELETE | Dashboard UI keeps working for owners/admins; ordinary members see 403 on add. Remove is already broken for everyone, so no regression | No change to existing rows. Does not stop an owner/admin appointing a chair | Fully reversible | Owner/admin as interim stand-in only | Consistent with D-4 pattern; must be labelled interim | PO (priority and wording); code change story |
| **R1-b** R1-a + actor attribution + separate chair/deputy restriction | Adds accountability; narrows highest-impact role | Needs a new place to record the actor (event field and projection column or a new audit record: **schema/contract change**) | Improves; makes DELETE traceable only after F8 is fixed | Partly reversible (added columns persist) | Chair/deputy rule needs statutes (E.4 item 1); interim role split is a guess | Attribution fits ACT principle of recorded evidence; chair rule may pre-empt Governance | PO; ARB for the event contract change |
| **R1-c** permanent Governance-owned authority per competent organ | Full fix | Migration of write path to a Governance aggregate with append-only appointment facts and evidence | Best | n/a | Statutes required | Matches ACT §11 and PO-DR07-01 | ARB + PO |
| **R2-a** require `ensure.organisation` + owner/admin + no self-review | Closes ordinary-user, cross-organisation (if reachable) and self-approval paths | No UI caller; low disruption. Note: with the endpoint currently failing on persistence (F21) the immediate benefit is **prevention of future exposure**, not closing a live write | None | Reversible | Owner/admin interim; self-review rule is a constitutional assumption | Consistent if labelled interim and not "D-4 extended" | PO; code change story |
| **R2-b** R2-a + fix transaction and duplicate persistence | Prevents inconsistent approved state | **Danger of order:** repairing persistence (F21) without R2-a first would make the endpoint start working for any verified user. R2-b must ship with, or after, R2-a, never before. Removing the second save changes data seen by Elections (E.5) | Improves | Reversible in code, not in data written during a faulty window | None new | Consistent | PO; Elections owner consulted |
| **Feature-off** (route returns 404/503 or is not registered) for R-2 | Strongest; also protects against accidental repair of F21 | No UI caller; only direct callers affected (unknown) | None | Fully reversible | None | Consistent | PO |

**Reviewer recommendation [P], not approval:**

- **R-1:** prioritise R1-a as the smallest control with an immediate effect (the POST path is live). Attribution (R1-b) deserves a separate decision because it changes an event contract. Do not repair the DELETE import (F8) **before** R1-a is in place.
- **R-2:** because no working write exists today, the proportionate step is R2-a or feature-off **before** anyone repairs F21; R2-b only together with or after R2-a. If the PO wants the lowest-risk interim, feature-off costs the least and has no known UI caller. [OQ] confirm there is no operator or script calling the route.
- R1-a and R2-a are **interim controls**. Neither should be recorded as organisational authority.

---

## G. Test recommendations (design only; none written or run)

**Preconditions common to all rows.** Isolated test database only (PBDIGIT-266 gate: ARB-SEC-01 says tests are authorised only after isolated database readiness; the gate failed on 2026-10-09 and has not been re-checked in this review). Organisation A (with committee CA, member MA1, MA2, owner OA, admin AA, ordinary member UA), organisation B (member UB). Realistic wiring: no in-memory doubles for the repositories under test (the current Membership test replaces them).

### G.1 R-1: `POST /api/v1/governance/committees/{CA}/members` and `DELETE …/members/{MA1}`

Request headers: `X-Organisation-ID`, `Accept: application/json`, session cookie.

| Actor | Precondition | Request | Expected HTTP **after** containment (R1-a) | Expected today (static) | Expected persistence | Expected audit evidence |
|---|---|---|---|---|---|---|
| Guest | none | POST with valid header | **401** | likely 500 (middleware order, F12) | none | none |
| Authenticated, unverified | `email_verified_at` null | POST | **403** (Laravel returns 403 for unverified JSON on `verified`) | 403 | none | none |
| Verified, **no** role row in A | header = A | POST | **403** (`UNAUTHORIZED_TENANT_ACCESS`) | 403 | none | none |
| Verified, header missing | | POST | **400** (`MISSING_TENANT_CONTEXT`) | 400 | none | none |
| Verified, header of non-existing organisation | | POST | **404** (`INVALID_ORGANISATION`) | 404 | none | none |
| Ordinary member of A, role `member` | header = A | POST role `member` for MA2 | **403** | 201 | after containment: no projection row | refused attempt should be recorded (decision for PO) |
| Ordinary member of A | header = A | POST role `chair` for MA2 | **403** | 201 | none after containment | same |
| Ordinary member of A, assign self | UA has a `Member` row | POST role `deputy` for UA's member | **403** | 201 if Member row exists | none | same |
| Ordinary member of A, **repeat POST** for existing member with new role | existing projection row | POST | **403** | 201 and role overwritten (F7) | row unchanged | same |
| Ordinary member of A | header = A | DELETE MA1 | **403** | 500 (F8) | none | same |
| Ordinary member of B, header = A | UB has only B rows | POST | **403** | 403 | none | none |
| Ordinary member of B, header = B, committee CA in A | | POST | **404** ("Committee not found", tenant check) | 404 | none | none |
| Owner / admin of A | | POST member role | **201** | 201 | one projection row with the requested role | **actor id recorded** (needs R1-b) |
| Owner / admin of A | | DELETE existing | **202** after F8 is repaired | 500 today | row removed | actor id recorded (R1-b); today removal leaves no trace |
| Owner / admin of A, invalid role value | | POST role `x` | **422** (not 500) | 500 with exception class leaked (F14) | none | none |

Whether 403 is right: yes for authenticated-but-unauthorised; **401** for guest; **404** for committee/member in another organisation (the code returns 404, which avoids confirming existence); **400** for missing tenant header; 422 for invalid role is a recommendation, since the current code returns 500.

### G.2 R-2: `POST /organisations/{slug}/committee/membership/review`

| Actor | Precondition | Request | Expected HTTP after R2-a | Expected today (static) | Expected persistence | Audit evidence |
|---|---|---|---|---|---|---|
| Guest | | review | **302 to login or 401 for JSON** (`auth` middleware with JSON expected: 401) | same | none | none |
| Authenticated, unverified | | review | **403** | 403 | none | none |
| Verified, no role row in A | session org ≠ A | APPROVE | **403** | 500 (global scope / not found) or 403 after R2-a | none | none |
| Verified, no role row in A, session org = A or platform=A | | APPROVE | **403** | pass-through, then 500 (F21) | none | none |
| Ordinary member of A (not applicant) | | APPROVE on another's application | **403** | 500 (F21) | none | none |
| Ordinary member of B | | APPROVE on A's application | **403** | 500 or 403 | none | none |
| Owner/admin of A | | APPROVE another member's application | **200** after R2-b | 500 (F21) | application `APPROVED` with `reviewed_by` and `reviewed_at`; exactly **one** active association episode; events published | reviewer id persisted |
| Owner/admin of A | | REJECT | **200** | 500 (F21) | status `REJECTED`; no association | reviewer id persisted |
| Owner/admin who is the applicant | | APPROVE own | **403** (or 422 with explicit reason, decision for PO) | 500 | none | refused attempt record |
| Applicant (ordinary member) reviewing own | | APPROVE | **403** | 500 | none | same |
| Applicant reviewing someone else's | | APPROVE | **403** | 500 | none | same |
| Any actor, application id unknown or in another organisation | | APPROVE | **404** or **422** ("not found"); current code throws `RuntimeException` -> 500 | 500 | none | none |
| Owner/admin, already approved application | | APPROVE | **422** ("Cannot approve application in APPROVED status") | n/a | unchanged | none |
| Failure injection (R2-b) | force lineage or association write to throw | APPROVE | **non-2xx and no application row changed** (transaction) | n/a | all-or-nothing | none |

### G.3 Existing tests compared with PBDIGIT-266 required handling

PBDIGIT-266 requires "a feature test per route as a non-privileged verified user of the same and of another organisation, expected 403; only a failing test establishes a defect". Existing tests: `CommitteeMemberApiTest.php` uses unmounted `/api/governance/…`, `X-Tenant-Id`, no authenticated user, and asserts response bodies the controller does not return (F24). `CommitteeMembershipApplicationTest.php` uses the correct slug URI and `actingAs`, but substitutes in-memory repositories (hiding F21/F22), builds the handler with the wrong constructor arity, and uses the application owner as reviewer (codifies self-approval). **Neither file tests the PBDIGIT-266 matrix.** Per the project's TDD rule these RED tests precede any fix.

---

## H. Principal / PO decision register

Types: **T** technical, **O** organisational, **A** ARB.

| ID | Decision | Reviewer position [P] | Type |
|---|---|---|---|
| **P1** R-1 priority | Treat R-1 POST as the highest-priority containment of the two: it is the only demonstrated *effective* unauthorised write. State explicitly that DELETE is latent (F8) | T + O (PO) |
| **P2** R-2 priority | High **priority for authority fix-before-repair**, but lower *urgency of exposure* than R-1 because the endpoint appears non-functional (F21). The risk is sequencing: repairing persistence first would expose it | T + O (PO) |
| **P3** Interim containment | Decide among R1-a / R1-b, R2-a / R2-b / feature-off. Reviewer leans: R1-a now; R2 feature-off or R2-a before any repair of F21; R2-b only after R2-a. Interim labelling and replacement criterion required (D-4 pattern) | T (code) + O (wording) + A (event contract if R1-b) |
| **P4** Authority source and D-4 scope | (i) Which organ/office may appoint and remove committee members, and chair/deputy specifically. (ii) Who may decide committee-association applications. (iii) Whether D-4 is extended to the committee-association review: **reviewer position: not on current evidence**; take a separate interim decision, citing M-2 as open | O (statutes / organisational representative) + A (D-4 scope, because D-4 was decided by the Human ARB) |
| **P5** Authorisations | (a) RED tests per G on the isolated database (needs the ARB-SEC-01 gate); (b) containment implementation; (c) repair of F8 and F21 (sequenced after containment); (d) production exposure checks (logs, data) | (a)(b)(c) T with PO; (d) O (privacy/Ops) |
| P6 (added) | Whether PBDIGIT-266 item 1 (election approval) is scheduled: recorded as a **separate high-priority dependency, out of scope here** (`PBDIGIT-266…:21`, though the revision says a domain-level guard was found and the item is "likely refuted"; item 1's `GET /admin/elections/pending` has no guard on any layer per the same revision) | T + PO |
| P7 (added) | Whether refused attempts must be recorded as audit evidence | O / A |

---

## I. Remaining open questions

1. [OQ] Does the production database contain `committee_membership_applications` with the PK shown in the Tenant migration, and do any rows have status other than `SUBMITTED`? (Would confirm or refute F21.)
2. [OQ] Is there any caller (script, admin tool, other client) of the R-2 route?
3. [OQ] At runtime, does Laravel's middleware priority place `Authenticate` before `IdentifyTenantFromHeader`? (F12)
4. [OQ] Do all users holding a `user_organisation_roles` row also have a `Member` row (R-1 self-assignment)?
5. [OQ] What are the values of `TenantContext` and session `current_organisation_id` on the review route for a typical user? (decides F19)
6. [OQ] Is `config/session.php` SameSite `lax` or `strict` (F13)?
7. [OQ] Does anything in the Elections context read `committee_associations` rows written by the "backward compatibility" save (E.5)?
8. [OQ] Was the projection table `committee_member_projection` ever written through paths other than `store`, and does it contain `chair` rows already? (exposure check, not examined)
9. [OQ] Is the `CommitteeId` namespace split (Governance vs Membership `CommitteeId`) a source of further type mismatches like F8? (Not searched beyond the two paths.)
10. [OQ] The statutes questions in E.4.

---

## J. Review limitations and independence

**Not done.** No HTTP request was sent. No test was written or run. No migration or `artisan` command other than `route:list` was run. No database (development, test or production) was queried; no log was read; production configuration (`DB_CONNECTION`, route cache, session SameSite) was not read. The `class_exists` lookup used the Composer autoloader only. Whether the production schema matches the migrations read was not verified. The absence of a transaction decorator was checked by grep over `app/Contexts/Membership` and the provider bindings seen; a binding elsewhere (an unread provider, a package) was not excluded. The other files in the commission (ratification act §10-§11 beyond the quoted lines, decision paper §16, D-4 row) were read for citation only, not re-audited. The DR-07a report was read for its R-1/R-2 rows only. Evidence of prior use or exploitation was **not checked** and no incident is claimed. PBDIGIT-266 item 1 was not investigated.

**Nature of independence.** This review was written by an AI agent (a different model from the author of the reviewed reports, fresh context), commissioned by the same session that produced those reports. I had no access to the author's reasoning. I read the source and reconstructed both request paths before opening either report; where the source and the reports differ, the source wins and the difference is recorded. That is independence of *reading*, not of *commissioning*: the brief I was given summarised the reports' claims, and an AI reviewer can share blind spots with an AI author. Findings F8 and F21 are mechanical (missing class; INSERT on an existing PK) and should be easy to confirm or reject; judgement-dependent items are F7, F12, F13, F19, and the DDD readings in E.

**Recommendation.** A human reviewer (or an external reviewer) should give final sign-off before any decision rests on this document, and the two mechanical findings (F8, F21) should be confirmed by a person running the PBDIGIT-266 tests on an isolated database, since they change the priority ordering between R-1 and R-2.

**Files changed.** This document only. No code, route, middleware, policy, permission, schema, data, test, ADR or existing file was modified; nothing was committed or pushed.
