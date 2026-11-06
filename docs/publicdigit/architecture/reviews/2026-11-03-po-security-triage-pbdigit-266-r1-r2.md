# PO-SEC-TRIAGE-01: authorisation gaps R-1 and R-2 (PBDIGIT-266 items 3 and 2)

| | |
|---|---|
| **Status** | **SECURITY TRIAGE, DECISION PREPARATION.** Read-only investigation. It decides, authorises and changes nothing. All findings are **static source verification**: no request was sent, no test run, no database or log consulted. It is **not** an independent review (§10). |
| **Commission** | PO-SEC-TRIAGE-01, "AUTHORISED FOR READ-ONLY INVESTIGATION AND DECISION PREPARATION" (Principal / PO, 3 November 2026) |
| **Related tracking** | [F] `docs/publicdigit/backlog/PBDIGIT-266-live-routes-without-a-found-authorisation-guard.md`, status "`OPEN — investigation` · not authorised · priority: high until disproven"; PBDIGIT-266 item 3 = R-1 here; item 2 = R-2 here. Its required handling (`:19-21`): a feature test per route as a non-privileged verified user of the same and of another organisation, expected 403; "only a failing test establishes a defect". |
| **Source report** | `docs/publicdigit/architecture/reviews/2026-11-03-dr07a-membership-technical-discovery-m1-m5.md` (R-1, R-2) |
| **Date** | 2026-11-03 |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0) |

**Labels.** [F] verified (static) · [D] existing decision · [P] proposal · [OQ] open question · [EO] engineering observation · [A] assumption. **Classes:** VERIFIED (static) · PARTIALLY VERIFIED · NOT VERIFIED · CONFLICT · OPEN QUESTION.

**Not exploited.** No access was attempted. "A user can do X" below means "the code as read imposes no check that stops X". Whether X succeeds at runtime is for the feature tests (§8).

---

## 1. Executive security assessment

| | R-1: Governance committee members (add / remove) | R-2: committee-membership application review |
|---|---|---|
| Static finding | **VERIFIED (static).** Any authenticated, verified user who has **any** `user_organisation_roles` row for the organisation (any role value, including the registration default `member`) can add a member of that organisation to any of its committees **with any committee role, including `chair`**, and can remove any member, by calling `/api/v1/governance/committees/{id}/members` directly | **VERIFIED (static), wider than recorded.** Any authenticated user with a verified email can approve or reject any application of any organisation whose slug and application id they know. **Organisation membership is not checked** on this route. An applicant receives their application id from `apply` and can **approve their own application**; the domain has no self-review guard |
| Tenant scoping | holds: committee and member are both checked against the header organisation | holds for the application lookup (scoped to the route organisation); **no check that the caller belongs to that organisation** |
| Authority check | none at route, controller, handler or domain level | none at route, request, controller, handler or domain level |
| Live UI | the **only** committee-member UI (Dashboard → `CommitteeMemberManager.vue`) uses this path | no UI caller found; reachable by direct request |
| Audit / actor evidence | none recorded for the actor (logs carry organisation, committee and member, not the acting user) | `reviewed_by` recorded on the application; association actor columns partly null |
| Runtime certainty | NOT VERIFIED; PBDIGIT-266 tests not yet written | NOT VERIFIED; and a separate defect (approval double write) may make approval error at runtime **after** the application is already marked approved (§3.3) |
| Severity [P] | **High.** Unauthorised changes to committee composition and office (chair) inside one organisation | **High.** Unauthorised admission decisions, including self-approval and cross-organisation decisions |

**Bottom line [P]:** both gaps allow organisational decisions without organisational authority, contrary to PO-ORG-MEM-01 ("Administrative responsibility alone does not confer authority …"; "platform access permissions do not constitute organisational authority"). They warrant **immediate PO security priority**, with verification by the PBDIGIT-266 feature tests as the first act.

---

## 2. Repository snapshot

| Item | Value |
|---|---|
| Branch / HEAD | [F] `election-flow-updated` / `821a3bb29` |
| HEAD "discrepancy" reconciled | [F] DR-07a recorded `b95fe8fc4`, the HEAD when its investigation ran. `821a3bb29` was the HEAD when that work finished. The one commit between is another session's KnowledgeOS commit (`821a3bb29 docs(knowledgeos): consolidation S0378–S0402 …`), with no change under `app`, `routes`, `database`, `resources`, `config`, `bootstrap` or `docs/publicdigit`. Two snapshots, not a conflict |
| Working tree | [F] other sessions' changes (`.claude/`, `developer_guide/election/ip_voting/*`, KnowledgeOS, `bootstrap/cache/packages.php`); untracked review documents. Not touched |
| Files changed by this commission | this report only |

---

## 3. R-1: Governance committee-member authorisation

### 3.1 Mount points and middleware

| Mount | Defined at | Middleware (in order) | Tenant source | Effective for writes? |
|---|---|---|---|---|
| `POST / DELETE api/v1/governance/committees/{committeeId}/members[/{memberId}]` | `routes/api_v1.php:49-56`, group in `bootstrap/app.php:19-22` | `web, json.api, tenant.api, auth, verified` | header `X-Organisation-ID` / `X-Tenant-Id`, validated by `tenant.api` | **yes**: `tenant.api` sets `TenantContext` |
| `organisations/{organisation:slug}/api/governance/committees/...` | `routes/governance-api.php:62-72`, via `app/Providers/RouteServiceProvider.php:51-54` | `web, auth, verified, ensure.organisation` | route slug; `ensure.organisation` sets session only | [EO] likely **400** "Missing tenant context" (controller requires `TenantContext`, `CommitteeMemberController.php`, store / destroy start) |
| `organisations/{organisation}/api/governance/committees/...` (second registration of the same file) | `routes/organisations.php:414-418` | outer organisation group + `json.api, auth, verified, ensure.organisation` | as above | as above. [F] route list shows one effective registration per URI and name |

**`tenant.api` (`app/Http/Middleware/IdentifyTenantFromHeader.php`)** [F]:
- requires the header;
- loads the organisation;
- refuses (403) unless `$request->user()->organisations()->where('organisation_id', …)->exists()`;
- then sets `TenantContext` and the session.

`User::organisations()` is a `belongsToMany` over **`user_organisation_roles`** with no role filter (`app/Models/User.php:172-178`). Every registration creates such a row in the bootstrap organisation (BR-2); other organisations add rows for their users.

**[EO] Ordering:** `tenant.api` runs **before** `auth` (`bootstrap/app.php:17-19`). For a guest, `$request->user()` is null, so the request likely errors (500) rather than returning 401. This is a robustness defect, not an access path.

### 3.2 Controller → handler → persistence

| Layer | Store (add) | Destroy (remove) | Authority check |
|---|---|---|---|
| Controller | `CommitteeMemberController::store` (`app/Http/Controllers/Api/Governance/CommitteeMemberController.php:98-197`): requires `TenantContext`; loads `App\Models\Member` by id **and** `organisation_id` = tenant; `CommitteeRole::from($request->input('role', 'member'))`, any of chair, deputy, member, observer | `::destroy` (`:205-241`): builds `RemoveCommitteeMemberCommand` with tenant, committee id, member id | **none**: the injected `CommitteeAssociationLifecyclePolicy $guard` (`:44`) is never used; no `authorize`, Gate or policy call |
| Application | (in controller) | `RemoveCommitteeMemberHandler::handle` (`app/Contexts/Governance/Application/Handlers/RemoveCommitteeMemberHandler.php:20-39`) | none |
| Repository | `findById` checks `committees.id` **and** `organisation_id` = tenant, then returns a blank aggregate (`app/Contexts/Governance/Infrastructure/Repositories/EloquentCommitteeRepository.php:31-48`); `save` dispatches events only (`:56-66`) | same | none |
| Domain | `Committee::addMember` (rejects duplicates only within the blank aggregate, so never in practice) | `removeMember` (unconditional) | none |
| Persistence | `CommitteeMemberProjectionListener::onMemberAssigned`: `updateOrCreate` on (`committee_id`, `member_id`) with the requested role (`app/Contexts/Governance/Infrastructure/Projection/CommitteeMemberProjectionListener.php:30-50`) | `onMemberRemoved`: delete by (`committee_id`, `member_id`) (`:53-58`) | — |

**Tenant isolation** [F, static]:
- The committee must belong to the header organisation, and so must the member (store).
- The remove handler also verifies the committee's organisation before deleting.
- The listener's delete has no tenant filter, but it only runs after that check.
- **Cross-tenant manipulation through these endpoints is not evident in the code.**

This corrects DR-07a R-15, which overstated the delete risk.

**The protected alternative exists but is not wired:**
- [F] the legacy committee path uses `authorize('manageCommittee')` → `OrganisationPolicy::manageCommittee`, which requires an `owner` or `admin` row in `user_organisation_roles` (`app/Policies/OrganisationPolicy.php:46-60`);
- the Governance path applies neither it nor any other policy.

### 3.3 Live UI dependency

[F]:
- `resources/js/Pages/Committee/Dashboard.vue:95, 110` mounts `resources/js/Components/CommitteeMemberManager.vue`.
- That component calls `apiClient.get / post / delete('/governance/committees/{id}/members…')` (`:377, :455, :485`).
- `apiClient` has `baseURL: '/api/v1'` and injects the tenant header (`resources/js/services/api.js:4-38`).
- The legacy member routes have no UI caller (the only caller, `AssignMemberModal.vue`, is imported nowhere), and the legacy assign path is broken (DR-07a W1).

**Consequence:** this unguarded path is the **only working** committee-member management path. Who can open the dashboard page is a separate route question; the API is callable directly in any case.

### 3.4 Authority model: what should be required

- [D] ACT §11: Governance owns competence, organs, authority.
- [D] PO-DR07-01: committees are organs.
- [D] PO-ORG-MEM-01: competent organs or authorised office holders decide; platform access is not authority.
- [OQ] **Which organ or office may change a committee's composition, and appoint a chair, is not established.** It is statute evidence (O-10 / CQ-2; preparation document §5.3, §5.6).
- [F] **Existing implemented rule available for containment:** `manageCommittee` (owner / admin). It is a *platform access* rule. Using it would be an interim measure of the D-4 kind (owner / admin as interim stand-in, PO-decided for Membership admission), not organisational authority.

### 3.5 R-1 conclusion

| Statement | Class |
|---|---|
| No authority check on add / remove at any layer | **VERIFIED (static)** |
| Any user with any role row for the organisation passes `tenant.api` | **VERIFIED (static)** |
| Such a user can assign any committee role, including `chair`, to any member of the organisation, including themselves if they are a member | **VERIFIED (static)**; runtime NOT VERIFIED |
| Such a user can remove any committee member | **VERIFIED (static)**; runtime NOT VERIFIED |
| Cross-organisation changes | **not evident**: tenant checks hold |
| Actions are attributable to the acting user | **NOT** recorded: logs and projection carry no actor |
| Required organisational authority | **OPEN QUESTION** (statutes) |

---

## 4. R-2: committee-membership application review

### 4.1 Route and request

| Item | Evidence | Finding |
|---|---|---|
| Route | `POST organisations/{organisation}/committee/membership/review` (`routes/committee/committeeRoutes.php:80`) inside `Route::middleware(['auth', 'verified'])->prefix('organisations/{organisation}')` (`:26`); file included by `routes/web.php:454` | **no** `ensure.organisation` or `tenant` middleware |
| Binding | `{organisation}` binds by **slug** (`app/Models/Organisation.php:54-57`) | slugs are human-readable identifiers |
| Request authorisation | `ReviewMembershipApplicationRequest::authorize()` = `auth()->check() && hasVerifiedEmail()` (`app/Contexts/Membership/Infrastructure/Http/Requests/Desktop/ReviewMembershipApplicationRequest.php:11-14`) | no role, organisation or committee check |
| Controller | `CommitteeMembershipApplicationController::review` (`app/Contexts/Membership/Infrastructure/Http/Controllers/Desktop/CommitteeMembershipApplicationController.php`): tenant = route organisation; `reviewedBy` = acting user's id; catches `DomainException` only | no authority check |
| Handler | `ReviewMembershipApplicationHandler::handle`: loads the application with `getOrFailForTenant` (scoped to the route organisation, `EloquentCommitteeMembershipApplicationRepository.php:37, 53`); APPROVE or REJECT; saves | no authority check |
| Domain | `MembershipApplication::approve($reviewedBy)` checks only the status transition (`…/Domain/…/MembershipApplication.php:102-125`) | **no guard against the applicant reviewing their own application** |
| Apply step | `apply` returns `application_id` to the applicant (same controller) | the applicant knows the id needed to review |
| UI | no frontend caller of the review route found (`grep` over `resources/js`) | reachable by direct request only |

### 4.2 What approval does

1. Application status → `APPROVED`; `reviewed_by` / `reviewed_at` set; event `CommitteeMembershipApplicationApproved` recorded.
2. Application saved (`repository->saveForTenant`).
3. `MembershipLineage::establish` → active episode saved into `committee_associations` (lineage repository).
4. A second save of the association "for backward compatibility (Elections context queries)" (`ReviewMembershipApplicationHandler.php:59-60`).

**No transaction** wraps the handler or the controller (grep for `transaction`: none).

[EO, static] The fourth write runs `assertCanCreate`, which should throw `DuplicateActiveAssociationException` (a `DomainException`, so the controller answers 422) because of the row from the third write, or it should hit the NOT NULL `lineage_id`. If so, the request **reports failure while the application is already approved and an active association exists**. That is a data-integrity defect, and it may hide successful unauthorised approvals behind an error response. Runtime NOT VERIFIED.

**What approval creates:** a `CommitteeAssociation` (unit or committee belonging; meaning OPEN, DR-07a M-2). It creates **no** fact in the ratified Membership ledger, which is unwired. Whether this is a Membership "admission" in the sense of D-4 is **OPEN** (M-2 Q1 / Q2).

### 4.3 Authority model

| Actor | May review? (as established) |
|---|---|
| Competent organ / authorised office holder | [OQ] per statutes (CQ-3, §5.3 A1–A3 of the preparation document) |
| Organisational Membership Responsible Person | **not by that role alone** ([D] PO-ORG-MEM-01) |
| Platform owner / admin | interim only, **if** the PO extends D-4 to this decision (D-4 covers Membership Admit / End; whether it covers committee-association approval is OPEN) |
| Any verified PublicDigit user | no basis in any record |
| The applicant themselves | no basis in any record |

### 4.4 R-2 conclusion

| Statement | Class |
|---|---|
| No authority check at any layer | **VERIFIED (static)** |
| Caller need not belong to the organisation | **VERIFIED (static)**, wider than PBDIGIT-266 item 2 records |
| Applicant can approve their own application | **VERIFIED (static)**; runtime NOT VERIFIED |
| Approval is partially persisted when the response reports an error | **CONFLICT (static)**; runtime NOT VERIFIED |
| Cross-organisation review | **VERIFIED (static)** given knowledge of slug and application id; ids are UUIDs ([A] not guessable, but exposed to the applicant and to anyone who sees them) |
| Required organisational authority | **OPEN QUESTION** (statutes; D-4 scope) |

---

## 5. Cross-cutting assessment

| # | Topic | R-1 | R-2 |
|---|---|---|---|
| 1 | Tenant isolation | holds (header org; committee and member checked) | application scoped to route org; caller's membership not checked |
| 2 | Organisation scoping of the caller | any `user_organisation_roles` row | none |
| 3 | Identity and role verification | authenticated, verified; no role | authenticated, verified; no role |
| 4 | Policy / Gate enforcement | none (policy exists, not applied) | none |
| 5 | Authorisation at the application-service boundary | none | none |
| 6 | Direct API invocation | the intended use (API) | the only use (no UI) |
| 7 | POST / DELETE consistency | both unguarded; DELETE returns 202, POST 201 | APPROVE / REJECT equally unguarded |
| 8 | Audit and actor evidence | **actor not recorded** | `reviewed_by` recorded |
| 9 | Privilege escalation | a member can make themselves or another member chair | an applicant can admit themselves |
| 10 | Organisational authority vs platform access | neither is checked | neither is checked |

**Related but out of scope:** PBDIGIT-266 item 1 (election approval by any verified user) is recorded as "the most severe" (`PBDIGIT-266…:21`). It is not investigated here.

---

## 6. Risk assessment

| Field | R-1 | R-2 |
|---|---|---|
| Affected operation | add / remove committee member, incl. role assignment | approve / reject committee-membership application |
| Current control | authentication; email verification; `tenant.api` organisation relation; tenant checks on committee and member | authentication; email verification; tenant scoping of the application lookup |
| Missing control | authority to change committee composition and office | caller's organisation membership; authority to decide; self-review exclusion |
| Potential impact | unauthorised appointment or removal of office holders (incl. chair); corrupted committee composition shown in the UI | unauthorised admissions or rejections; self-admission; decisions on another organisation's applications |
| Exposure | potential, any organisation member; actual use unknown | potential, any verified user; actual use unknown (no UI) |
| Likelihood [P] | moderate–high: needs only membership and the standard UI client; the API is the live path | moderate: needs slug and application id, which applicants have for their own |
| Severity [P] | high: office and composition are organisational authority | high: admission is an organisational decision; self-dealing |
| Data integrity | `committee_member_projection` rows created, changed or deleted without attribution | `committee_membership_applications`, `committee_associations` (with partial-write defect) |
| Constitutional impact | bypasses the competent organ for appointments (PO-DR07-01; PO-ORG-MEM-01) | bypasses the competent organ for admission (PO-ORG-MEM-01; CQ-3) |
| Containment | §7 | §7 |
| Permanent remediation | Governance authority model (DR-07a, P11, XD-4) | authority source per statutes; Governance AuthorityCheck |
| Dependencies | statutes (O-10); DR-07a; XD-4 | statutes (CQ-3); M-2 Q1 / Q2; D-4 scope |

---

## 7. Containment and remediation options (none implemented)

### 7.1 R-1

| Option | Content | Security benefit | Operational impact / UI risk | Authority dependency | Ratified fit | Reversible | Approval |
|---|---|---|---|---|---|---|---|
| R1-a Immediate interim containment | apply the **existing** `manageCommittee` policy (owner / admin) to store and destroy | closes the gap for ordinary members | UI keeps working for owners / admins; members lose an ability they should not have | none new: reuses an implemented rule; must be labelled **interim** (D-4 kind), not organisational authority | consistent (no Membership change) | yes | PO (security story); code change |
| R1-b Minimal correction | R1-a **plus** record the acting user on every change, and restrict `chair` / `deputy` assignment separately | adds attribution; limits the highest-impact change | as R1-a | as R1-a; role-specific rule needs statutes, so interim | consistent | yes | PO |
| R1-c Long-term | Governance owns organs, positions and appointments with an authority check against the competent organ (statutes), append-only appointment facts with evidence | full fix | migration (DR-07a M-4 / M-5) | statutes, DR-07a, XD-4, P11 | consistent with ACT §11 | n/a | ARB + PO |
| Not recommended | disable the endpoints | — | removes the only working committee-member UI | — | — | — | — |

### 7.2 R-2

| Option | Content | Security benefit | Operational impact | Authority dependency | Ratified fit | Reversible | Approval |
|---|---|---|---|---|---|---|---|
| R2-a Immediate interim containment | require organisation membership (`ensure.organisation`) **and** an owner / admin role for review; forbid reviewing one's own application | closes cross-organisation, ordinary-user and self-approval paths | no UI caller, so low disruption | owner / admin as interim (PO decides whether D-4 extends here) | consistent | yes | PO; code change |
| R2-b Minimal correction | R2-a **plus** fix the partial write (transaction; remove the second save) so a reported failure leaves no approved state | integrity of decisions | none for users | none new | consistent | yes | PO; code change |
| R2-c Long-term | review through the Governance AuthorityCheck against the competent organ per statutes; classify `CommitteeAssociation` (DR-07b) | full fix | depends on DR-07b | statutes; M-2; P11 | consistent | n/a | ARB + PO |
| Alternative containment | temporarily refuse all reviews (feature off) until R2-a is in place | strongest | no UI uses it, so likely acceptable; [OQ] whether any operator relies on direct calls | none | consistent | yes | PO |

---

## 8. Runtime verification requirements (none performed; each needs separate authorisation)

| Kind | What | Purpose | Permission needed |
|---|---|---|---|
| Static source verification | done here (§3–§4) | — | — |
| Existing automated tests | `tests/Feature/Governance/Api/CommitteeMemberApiTest.php` targets an unmounted URI and has a placeholder POST test (DR-07a R-18); `tests/Feature/Contexts/Membership/CommitteeMembershipApplicationTest.php:144-166` asserts 200 for approval | establish what current tests actually prove | read only (done) |
| New feature tests (PBDIGIT-266 method) | per route, as a non-privileged verified user of the **same** and of **another** organisation, expect 403; plus self-review for R-2; plus `chair` assignment by a member for R-1 | establish the defect (RED) | PO authorisation of the PBDIGIT-266 test slice |
| Isolated database | run those tests and the approval double-write check on the isolated test database (O-4; Membership PostgreSQL container exists) | runtime proof without touching development data | PO / Ops |
| Production configuration | `DB_CONNECTION`; route cache state | constraint and route behaviour in production | Ops |
| Production logs | calls to `api/v1/governance/committees/*/members` (POST / DELETE) and `…/committee/membership/review` | actual exposure; who used them | PO / Ops (privacy) |
| Read-only production data | `committee_member_projection` rows with role `chair` / `deputy` and their creation; approved applications whose `reviewed_by` equals the applicant; approved applications without a matching association or with duplicates | whether the gaps were used; extent of partial writes | PO / Ops |

---

## 9. Exposure summary

| Question | Answer |
|---|---|
| Does the live application currently permit committee changes without the required authority? | **According to static evidence, yes** (R-1). Runtime not verified |
| Does it permit organisational admission decisions without authority? | **According to static evidence, yes** (R-2), including self-approval and cross-organisation decisions. Runtime not verified; a separate defect may make approvals report errors while persisting |
| Has it happened? | **Unknown.** Needs logs and data checks (§8) |

---

## 10. Independent review package

**This report's author also wrote DR-07a. Nothing here is independently reviewed.**

| Finding | Reproduce with |
|---|---|
| `api/v1` middleware | `sed -n 15,25p bootstrap/app.php` |
| slug mount middleware | `sed -n 44,55p app/Providers/RouteServiceProvider.php`; `sed -n 405,420p routes/organisations.php` |
| `tenant.api` check | `sed -n 20,60p app/Http/Middleware/IdentifyTenantFromHeader.php`; `sed -n 172,178p app/Models/User.php` |
| No authority in controller | `grep -nE "authorize\|Gate::\|->can\(\|\\\$this->guard" app/Http/Controllers/Api/Governance/CommitteeMemberController.php` |
| Any role assignable | `grep -n "CommitteeRole::from" app/Http/Controllers/Api/Governance/CommitteeMemberController.php`; `cat app/Contexts/Governance/Domain/Committee/Enums/CommitteeRole.php` |
| Tenant checks hold | `sed -n 28,48p app/Contexts/Governance/Infrastructure/Repositories/EloquentCommitteeRepository.php`; store's Member lookup in the controller |
| Live UI path | `grep -n "governance/committees" resources/js/Components/CommitteeMemberManager.vue`; `sed -n 1,40p resources/js/services/api.js` |
| Existing policy not applied | `sed -n 44,62p app/Policies/OrganisationPolicy.php` |
| R-2 route group | `sed -n 20,30p routes/committee/committeeRoutes.php`; `sed -n 76,82p routes/committee/committeeRoutes.php` |
| R-2 request authorisation | `sed -n 9,15p app/Contexts/Membership/Infrastructure/Http/Requests/Desktop/ReviewMembershipApplicationRequest.php` |
| No self-review guard | `sed -n 100,125p app/Contexts/Membership/Domain/Membership/MembershipApplication.php` |
| Partial write | `sed -n 20,62p app/Contexts/Membership/Application/Membership/ReviewMembershipApplication/ReviewMembershipApplicationHandler.php`; `grep -rn transaction` over handler and controller; [A] a transaction decorator registered in the container was not searched for |

**Judgement-dependent conclusions:** severity and likelihood ratings (§6); "moderate–high" likelihood for R-1; the claim that the slug mount returns 400.

---

## 11. PO decisions required

| ID | Decision | Options prepared | Note |
|---|---|---|---|
| **P1** | Immediate remediation priority for R-1? | yes / no | [P] yes: live path, office-holder impact |
| **P2** | Immediate remediation priority for R-2? | yes / no | [P] yes: self-admission and cross-organisation decisions |
| **P3** | Temporary containment | R1-a (owner / admin via existing `manageCommittee`); R2-a (organisation membership + owner / admin + no self-review), or R2 feature-off | [P] R1-a and R2-a. They reuse implemented rules and are labelled **interim**. They do not make platform access a source of organisational authority (PO-ORG-MEM-01) |
| **P4** | Authority source needed for the permanent rules | statutes: who may change committee composition and appoint office holders (O-10 / CQ-2); who may decide admission to a unit or committee (CQ-3); whether D-4 extends to committee-association approval; M-2 Q1 / Q2 | the widened statutes request (action package §6) already covers these |
| **P5** | Implementation authorisation | (a) PBDIGIT-266 RED tests for items 2 and 3 on the isolated database; (b) containment code (R1-a, R2-a, R2-b) as TDD stories under PBDIGIT-266; (c) read-only production checks (§8) | each needs separate PO approval; the routes, policies and tests named in §3–§4 are the change surface |

[P] **Sequence:** P1 / P2 → P5(a) RED tests → P3 containment as GREEN → P5(c) exposure checks → P4 permanent rules through DR-07a / Governance.

---

## 12. Files changed

This report only. No code, route, middleware, policy, schema, test, permission, ADR, BDR, Platform Rules Index or ratified document was changed. Nothing was committed or pushed.
