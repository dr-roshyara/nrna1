# Membership Audit Challenge and PBDIGIT-266 Test Plan

**Date:** 2026-10-09 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)
**Commission:** `docs/publicdigit/membership/architecture/brainstorming/20261009_2245_implementation.md`
**Status:** REVIEW + PLAN. **No test executed, no request sent, no code, test, route, schema or configuration changed. It authorises nothing.**
**Reviewed document:** `2026-10-09-membership-theory-synthesis-and-implementation-readiness-audit.md` (`58fdef025`; errata appended).

## 0. Disagreement (stated first)

The commission asks me to challenge an audit **I wrote**. To avoid self-review:
- the challenge was performed by an **independent, read-only reviewer agent** (fresh context, adversarial);
- I verified its two major findings myself (§2).

The verdict below is a **recommendation**. The Principal Reviewer decides.

## 1. Repository state and gate status [DEMO]

**Repository:**
- HEAD `58fdef025` on `knowelegeos-modelling`, as reported.
- **A concurrent session is active** ("Mission 2", `PBDIGIT-211`). It appended to `.claude/sessions/2026-10-09.md`, edited `PBDIGIT-211` and `backlog/README.md`, and created an untracked test (`tests/Feature/Contexts/Shared/Messaging/RelayTenantContextTest.php`). Its notes record test-database setup work: `CREATE DATABASE` was denied.
- All of its changes are distinguishable and left untouched. I commit only my own lines.

**Gate status (from repository records, not transcripts):**

| Gate | Status |
|---|---|
| `PBDIGIT-264` | OPEN · not authorised (PR dispositions F-4b, N1, F-3 recorded in plan §10) |
| `PBDIGIT-265` | OPEN · investigation · not authorised |
| `PBDIGIT-266` | OPEN · investigation · not authorised |
| Slice 1b | ACCEPT WITH CONDITIONS *recommended*; **no formal acceptance act** |
| DV-1 | primary record is in the session transcript; **repository countersignature missing** |
| Slice 2 decisions | open (now to be read with erratum E-1: "D-1" collision) |

## 2. Audit challenge: verdict and material findings

**Recommended verdict: ACCEPT WITH CONDITIONS.** The conditions are the errata E-1 … E-8, appended to the audit.

**Verified correct by the independent reviewer:**
- the eight refusal reasons;
- the three invariants;
- `knownAt` and the Provenance kinds;
- the `AuthorityEvidence` fields;
- the `AuthorityCheckPort` signature, so G-AUTH-1 stands;
- `org_memberships` referenced only by the adapter and migration;
- no Annul / Correct in Slice 0;
- `isAdjacentTo` is dead code;
- the mutation arithmetic;
- the PBDIGIT-264 order and dispositions;
- K-1, K-3, K-4, K-6, K-7.

| # | Finding | Severity | Evidence (verified by me) | Correction |
|---|---|---|---|---|
| E-1 | "D-1" means INV-FID [ADOPTED] in the Slice 1a contract and the admission basis [BIZ] in the Slice 2 pack | **major** | `slice1a-contract.md:4` "It confirms **D-1 = INV-FID**"; plan §3 | K-8; the PO renames the Slice 2 decisions (proposal "S2-D1 … D6") |
| E-2 | The correction slice is ordered after cutover / go-live and lacks prerequisites | **major** | `RefusalReason.php:21` "changing an end date is Correct(End), Slice 7"; act N-4 "BD-REV-ER … needed before the reversal slice" | prerequisites #10, BD-REV-ER, CQ-3, CQ-9; **PO decision: correction before cutover / HTTP** (recommended) |
| E-3 … E-8 | INV-EP / INV-COV structurally unreachable; mutant `IntervalSet.php:76` killable (TG-5); P-proof vs P-arg; a bounded claim stated as general; D.1 over-statements; status vocabulary (CD) and label consistency | minor | `Resolution.php:84-97`; `InvariantSetTest.php:87` | appended as errata |

**Not verified by the reviewer:** the Python model counts (B.2) and the audit's own test runs. Both were executed by me earlier and are reproducible from the models README.

## 3. PBDIGIT-266: reachable authorisation paths (static, full path)

The new trace went past the controller to middleware, FormRequests, policies, handlers, domain guards and tenant scoping. **It changes the picture.**

| Family | Route(s) | Full path found | Reclassification |
|---|---|---|---|
| **1. Election approval** | `POST /admin/elections/{election:slug}/approve` and `/reject` (`electionRoutes.php:679-688`, `web, auth:sanctum, verified`) | `AdminElectionController::approve` → `Election::approve` → `transitionTo` → **`ConstitutionalTransitionGuard::assertAllowed`** (`app/Application/Election/Services/ConstitutionalTransitionGuard.php:40-80`): "User has required role"; `ElectionConstitution` `approve` / `reject` `allowed_roles: ['platform_admin']` (`:32-44`). A non-admin causes `InvalidTransitionException`, which the controller catches → error flash | **guard found at domain level → likely REFUTED** (to be confirmed by test) |
| | `GET /admin/elections/pending` (same group) | `pending()`: `Election::withoutGlobalScopes()->pendingApproval()->paginate(15)` → Inertia props. **No check on any layer.** The guarded twin `routes/platform.php:15` uses `platform_admin` | **probable cross-tenant information disclosure** (every organisation's elections awaiting approval) |
| **2. Committee application review** | `POST /organisations/{organisation}/committee/membership/review` (`committeeRoutes.php:79-80`; group `auth, verified` + web) | `ReviewMembershipApplicationRequest::authorize()` = verified only; controller → `ReviewMembershipApplicationHandler::handle` → `MembershipApplication::approve(reviewedBy)`: state-machine check only, **no reviewer authority**. **The existing test `CommitteeMembershipApplicationTest::test_review_application_approve` has the same user apply and then approve, expecting 200, i.e. it codifies self-approval.** The controller reads `$request->attributes->get('organisation')`, which is set only by `ensure.organisation` and is not on this group, so the route may 500 | **suspected (strong); possibly broken (500)** |
| **3. Governance committee member API** | `POST`/`DELETE /api/v1/governance/committees/{committeeId}/members[/{memberId}]` (`web, json.api, tenant.api, auth, verified`); slug twins under `organisations/{organisation:slug}/api/governance` (registered twice) | `tenant.api` (`IdentifyTenantFromHeader`) requires organisation **membership** (403 otherwise); **no role check** (`EnsureOrganisationMember` docblock: "validates membership but NOT specific roles"; governance README:287 "Role-based permissions NOT enforced yet"). `destroy` → `RemoveCommitteeMemberHandler` imports a non-existent class → **always 500**. Slug routes: `TenantContext` unset → 400. A guest with a valid header → 500 (middleware order), not 401 | **cross-org: guard found**; **same-org role: no guard found** (policy question PQ-3); **two route defects** (500 / 400) |
| **4. Legacy finance** | `/finance/income/{create,submit,verify,store}`, `/finance/outcome/{…}`, `/finance` (`financeRoutes.php`, `auth:sanctum, verified` only) | no FormRequest, policy or role. `Income` is `BelongsToTenant`; with no tenant middleware the `organisation_id` comes from whatever is in the session, otherwise the platform organisation. Store persists and **emails** a hard-coded treasurer address. `OutcomeController` uses `App\Models\Outcome`, but the file declares namespace `App\Domain\Finance\Models` → **outcome store 500** | **no guard found**; **cross-tenant write possible via session** (with family 5); outcome broken |
| **5. (new) Tenant header trust** | routes with the `tenant` middleware: `electionRoutes.php:263` (election management group) and `:731` (`election.debug-tenant`, a debug route live) | `app/Http/Middleware/TenantContext.php:50-55`: if `X-Tenant-Id` is present, it sets `session('current_organisation_id')` and `TenantContext` **without a membership check** ("tests + API escape hatch") | **suspected tenant-context injection.** The session value then persists into other routes (family 4) |

**Static absence is not proof.** Every row is confirmed or refuted only by the tests below.

## 4. Test plan (for authorisation; not written, not run)

**Location:** `tests/Feature/Security/Pbdigit266/` (one class per family).

**Fixtures:**
- organisations A and B;
- users: a guest; an unverified user; a verified member of A (role `member`); a verified owner / admin of A; a verified member of B; a platform admin (`platform_role` column);
- an election of A in `submitted_for_approval`;
- a committee and members of A;
- `Mail::fake()`;
- `TenantContext::clear()` in `setUp` and between requests (a static value leaks across requests).

**Every test asserts the status AND the state:** rows, the session `current_organisation_id`, sent mail, Inertia props. A 500 counts as **neither confirmation nor refutation**; it is classified "route defect, guard unreachable".

**Classification:**
- **CONFIRMED** = a grounded rule (G) is violated by state, disclosure or response;
- **REFUTED** = denial and no state change;
- **INCONCLUSIVE** = the expectation depends on an open policy question (P), or the route errors first.

**Expectation grounding:**
- **G1:** authentication required (the route declares `auth`).
- **G2:** **tenant isolation**, i.e. no cross-tenant visibility or writes. Sources: project charter (`CLAUDE.md`, "Each tenant's data is COMPLETELY ISOLATED"); `developer_guide/api/MULTI_TENANCY.md:330-345` (cross-tenant → 403).
- **G3:** election approval / reject is `platform_admin` only (`ElectionConstitution:32-44`; code rule, consistent with the guarded `/platform` routes).
- **P\*:** a policy question (§5).

| # | Test (class::method) | Caller | Expected | Grounding |
|---|---|---|---|---|
| 1.1 | `AdminElectionApprovalRouteGuardTest::test_member_of_owning_org_cannot_approve` | verified member of A | error flash; election still `submitted_for_approval`; no transition row | G3 |
| 1.2 | `…::test_member_of_other_org_cannot_approve_by_raw_id` | member of B; route segment = A's election **id** (the controller uses `findOrFail` on the raw segment) | as 1.1 | G2, G3 |
| 1.3 | `…::test_platform_admin_can_approve` (baseline) | platform admin | state `approved` | G3 |
| 1.4 | `…::test_reject_follows_the_same_guard` | member of A / B | state unchanged | G3 |
| 1.5 | `…::test_pending_list_is_not_visible_to_tenant_users` | member of B | 403 **or** Inertia props without A's election | G2; **P1** for the exact rule |
| 1.6 | `…::test_guest_is_redirected` | guest | redirect to login; no props | G1 |
| 2.1 | `CommitteeApplicationReviewRouteGuardTest::test_applicant_cannot_approve_own_application` | the applicant (member of A) | not 200; application still pending; no lineage / association rows | **P2** (self-approval, separation of duties) |
| 2.2 | `…::test_member_of_other_org_cannot_review` | member of B, organisation slug of A | denial; no state change | G2 |
| 2.3 | `…::test_ordinary_member_cannot_review_others_application` | member of A ≠ applicant | per P2 | **P2** |
| 2.4 | `…::test_route_resolves_organisation` | an authorised reviewer (once P2 is decided) | 200 path reachable (detects the 500 / attribute defect) | route health |
| 2.5 | `…::test_guest_and_unverified` | guest / unverified | 401 / 403 | G1 |
| 3.1 | `GovernanceCommitteeMemberRouteGuardTest::test_member_of_org_b_header_cannot_add_to_org_a` | member of A sending header B | 403; no projection row | G2 (expected REFUTE) |
| 3.2 | `…::test_plain_member_adding_committee_members` | member of A, header A | per P3; record the projection row | **P3** |
| 3.3 | `…::test_owner_can_add` (baseline) | owner of A | 201 + row | P3 baseline |
| 3.4 | `…::test_guest_with_valid_header_gets_401_not_500` | guest + header A | 401 | G1 (a 500 is a defect) |
| 3.5 | `…::test_destroy_is_reachable` | owner of A | 202 and the row removed (detects the missing-class 500) | route health |
| 3.6 | `…::test_slug_route_has_tenant_context` | owner of A, slug route | not 400 (detects the unset `TenantContext`) | route health |
| 4.1 | `LegacyFinanceRouteGuardTest::test_income_store_writes_only_into_callers_org` | member of A whose session carries `current_organisation_id = B` | no income row with organisation B; no mail | G2 |
| 4.2 | `…::test_user_without_any_org_role_cannot_record_income` | verified user, no organisation role | per P4; record the row and mail | **P4** |
| 4.3 | `…::test_outcome_store_reachable` | any | not 500 (class resolution) | route health |
| 5.1 | `TenantHeaderTrustTest::test_foreign_tenant_header_does_not_switch_session_org` | member of A sends `X-Tenant-Id: B` to `/elections/{slug}/management` | session org ≠ B, or 403 | G2 |
| 5.2 | `…::test_debug_tenant_route_is_not_publicly_reachable` | verified user | per P5 | **P5** |

## 5. Policy questions that code cannot settle

| ID | Question | Owner |
|---|---|---|
| P1 | Who may see the platform-wide list of elections awaiting approval? (The `/platform` twin suggests platform admins only) | PO |
| P2 | Who may review committee-membership applications? Is self-approval forbidden (separation of duties)? Relates to CQ-3 / CQ-7 | PO + Governance owner |
| P3 | Which role or organ may add or remove committee members (OC? owner / admin as in `manageCommittee`)? Relates to CQ-2 / CQ-3 | PO + Governance owner |
| P4 | Who may record finance income / outcome? Must finance be tenant-scoped? Relates to CQ-4 | PO + Finance owner |
| P5 | Is the `X-Tenant-Id` "escape hatch" permitted outside tests? Should `election.debug-tenant` exist in production? | PO + Security |

**Tests depending on P\* are written to *record* the current behaviour.** Their result is INCONCLUSIVE until the policy is decided. G-grounded tests can confirm or refute on their own.

## 6. Execution preconditions (for the authorisation)

1. **Explicit PO authorisation** of `PBDIGIT-266` *investigation* (tests only, no fixes).
2. **Test-database isolation:**
   - `RefreshDatabase` runs `migrate:fresh` on `nrna_test` once per process.
   - **A concurrent session is currently preparing test-database work** (its `CREATE DATABASE` was denied).
   - Runs must be **coordinated**: one session at a time on `nrna_test`, or a dedicated test database created by the PO.
   - Never against the development database.
3. In-process feature tests only: no external HTTP; `Mail::fake()`.
4. Fixes for confirmed rows are **separate stories**, TDD.

## 7. Smallest separately authorisable next action

**Authorise `PBDIGIT-266` as a test-only investigation**, under §6 preconditions 1 – 3, with families 1, 4 and 5 first:
- G-grounded rows: 1.1 – 1.6, 4.1, 5.1;
- the route-health rows (2.4, 3.4 – 3.6, 4.3).

The P-dependent rows run as characterisation, in parallel with the PO answering P1 – P5.

## 8. Updated TODO (delta to audit D.2 + errata; no duplicate tickets)

1. **PO:** authorise the `PBDIGIT-266` test investigation (§7); answer P1 – P5.
2. **PO:** rename the Slice 2 decisions (E-1 / K-8); decide **correction-before-cutover** (E-2).
3. **PR:** formal Slice 1b acceptance act; **PO:** DV-1 countersignature.
4. **PO:** authorise `PBDIGIT-264` (independent of 266).
5. **PO:** a test-only story for TG-1 … TG-5.
6. Then audit D.2 #5 onward, with #16 re-ordered per E-2.

**Files changed:**
- this document (new);
- the audit (errata appended);
- `PBDIGIT-266` (reclassification + family 5);
- the session log and CONTEXT line (my lines only).

**No test, runtime code, route, schema or configuration changed. No test run, no request sent.**
