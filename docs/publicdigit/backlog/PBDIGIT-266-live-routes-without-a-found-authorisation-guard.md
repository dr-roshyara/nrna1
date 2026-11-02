# PBDIGIT-266: live routes whose role or authority guard could not be found (security investigation)

**Type:** Investigation (possible security defects) · **Created:** 2026-10-09 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable). Found during the read-only committee-authority discovery (`docs/publicdigit/reviews/2026-10-09-committee-responsibility-authority-and-membership-boundary-discovery.md`). Outside that research's scope. **Index row:** added 2026-10-09.

| | |
|---|---|
| **Status** | `OPEN — investigation` · not authorised · **priority: high until disproven** |
| **Evidence class** | **static only.** No request was sent. A guard may exist outside the files read (middleware, model event, state-machine guard), so each item must be **confirmed or refuted by a feature test** before any fix |

| # | Route / path | What was found | What was not found |
|---|---|---|---|
| 1 | `POST /admin/elections/{election:slug}/approve` (and `/reject`, `/pending`): `routes/election/electionRoutes.php:679-688` | middleware `auth:sanctum, verified` only. `AdminElectionController::approve` (`app/Http/Controllers/Admin/AdminElectionController.php:28-49`) validates the notes and calls `$election->approve(auth()->id(), …)`. `TransitionMatrix.php:19` declares `approve` for `super_admin`, `platform_admin` | a caller enforcing those roles (searched for `actionRequiresRole` and role reads; only `CapabilityContext.php:32` defines a method). The platform route `routes/platform.php` uses a `platform_admin` middleware; **this duplicate route does not** |
| 2 | committee-membership application review, `routes/committee/committeeRoutes.php:80` | `ReviewMembershipApplicationRequest::authorize()` returns `auth()->check() && hasVerifiedEmail()` (`app/Contexts/Membership/Infrastructure/Http/Requests/Desktop/ReviewMembershipApplicationRequest.php:11-14`) | any role or committee check |
| 3 | Governance API: add / remove committee members (`app/Http/Controllers/Api/Governance/CommitteeMemberController.php:150,164`; `routes/api_v1.php:49-56`, `routes/governance-api.php`) | middleware `auth, verified, tenant.api` / `ensure.organisation` | a role check (reported by the inventory; **not re-read by me**) |
| 4 | legacy finance routes (`routes/finance/financeRoutes.php`): income / outcome create, submit, verify | `auth:sanctum, verified` | an approver role (reported by the inventory; **not re-read by me**) |

## Required handling

1. A feature test per route, as a non-privileged verified user of the **same** and of **another** organisation. Expected: 403.
2. Only a failing test establishes a defect. Then a fix story per route (TDD).
3. Item 1 is the most severe (an election lifecycle approval by an arbitrary verified user, if confirmed). The election may be found by its id because the controller uses `findOrFail` on the raw route segment.

**Related, already tracked:** IERVP-3 (`manageMembers` undefined; suspend / archive always 403) in `PBDIGIT-68` G-7. The tenant-header and tenant-context risks noted in earlier sessions (no item yet).

## Revision (2026-10-09): full-path trace and test plan

Source: `docs/publicdigit/reviews/2026-10-09-membership-audit-challenge-and-pbdigit266-test-plan.md` §3 – §7.
- **Item 1, approve / reject:** a domain-level guard **was found** (`ConstitutionalTransitionGuard` + `ElectionConstitution`, `platform_admin` only). Likely **refuted**; to be confirmed by test.
- **Item 1, `GET /admin/elections/pending`:** **no guard on any layer.** Probable cross-tenant information disclosure.
- **Item 2:** no reviewer-authority check anywhere. The existing test codifies self-approval. The route may 500 (missing organisation attribute).
- **Item 3:** organisation membership is enforced; **no role check**. `destroy` always 500s (missing class); the slug routes return 400.
- **Item 4:** no guard. A cross-tenant write is possible via the session. Outcome store 500s (namespace).
- **New item 5:** the `tenant` middleware trusts `X-Tenant-Id` without a membership check (`app/Http/Middleware/TenantContext.php:50-55`; `electionRoutes.php:263`, `:731` debug route).
- **Policy questions P1 – P5 and execution preconditions** are in the plan. Test-database coordination with concurrent sessions is required.
- **Still not authorised.**

## Human ARB decision ARB-SEC-01 (2026-10-09)

- **What is authorised:** "Security tests are authorised only after isolated test-database readiness is confirmed. The scope is investigation and tests only." No fixes, route, middleware, schema or configuration changes.
- **Gate check 2026-10-09: FAIL.** The only configured target is the shared `nrna_test` (9 open connections). No dedicated database is assigned to this item.
- **No test run.** Prerequisite: a dedicated test DB assigned to `PBDIGIT-266` (the PO creates it; separate authorisation).
- **Record:** `docs/publicdigit/reviews/2026-10-09-membership-human-arb-decision-record.md`.
- **Test design:** apply the corrections in `…-membership-decision-readiness-pbdigit266-and-correction-order.md` §4 before writing the tests.

## Extension (2026-11-02): INV-ORG-SCOPE-1 Stage 1 static investigation

**Source:** `docs/publicdigit/reviews/2026-11-02-inv-org-scope-1-stage1-static-investigation.md` (static only), with appendix `…-stage1-bypass-classification.md`.

**Purpose:** extends this item rather than opening a parallel track, as the Principal's decision required. **Every item remains to be confirmed or refuted by a feature test under ARB-SEC-01.** No test is authorised by this extension.

| Item | Change / addition |
|---|---|
| 1 | **Corroborated:** `GET /admin/elections/pending` lists elections of every organisation (`app/Http/Controllers/Admin/AdminElectionController.php:18`). Approve / reject remain guarded by the domain `platform_admin` guard. |
| 5 | **Refined:** on the election-management group (`routes/election/electionRoutes.php:263`), per-election officer policies (`app/Policies/ElectionPolicy.php:46-60`) contain the header. **Residual:** the debug route `electionRoutes.php:719-732` discloses organisation ids for any slug to any verified user; the header value persists in the session for unauthenticated visitors (`app/Http/Middleware/TenantContext.php:52,58-59`). |
| **6 (new)** | Election route binding ignores the organisation and includes deleted elections (`app/Models/Election.php:90-92`). `BallotPreviewController.php:28-32` with `ChecksElectionAccess` lets any owner / admin / commission member of the **URL** organisation open another organisation's ballot preview [static F]. Also check `ElectionVotingController.php:33` and `ResultController.php:19,228` (published results: OQ-S1-1, possibly intended). |
| **7 (new)** | The session organisation is switched before authorisation: `ElectionManagementController.php:1444-1448` [static F]; `startDemo` `:385-411` [H]. |
| **8 (new)** | "First active election of any organisation" fallbacks: `app/Http/Middleware/ElectionMiddleware.php:141-145` [static F]; `Demo/DemoVoteController.php:225`; `VoteController.php:121` (overlaps PBDIGIT-58). |
| **9 (new)** | `/admin/voting-security/*` is gated only by the global `committee.member` flag (`electionRoutes.php:691-715`). `VotingSecurityController.php:154` issues recovery links for any election id [H]. |
| **10 (new)** | **A removed election officer keeps their role:** `destroy` soft-deletes and leaves `status = 'active'`; `Election::resolveActorRole` bypasses all scopes (`app/Models/Election.php:1840-1844`, caller `:1878`), while `ElectionPolicy` refuses [static F]. |
| **11 (new)** | Vote retrieval checks the receipt only when `receipt_hash` exists (`VoteController.php:3602-3620`) [static F]; the share of affected votes is unknown. |
| side | Invitation `accept` may not compare the user's email with the invitation (`OrganisationMemberInvitationController.php:110-228`) [H]. |

**Separate item:** secrets written to the log, `PBDIGIT-317`.
