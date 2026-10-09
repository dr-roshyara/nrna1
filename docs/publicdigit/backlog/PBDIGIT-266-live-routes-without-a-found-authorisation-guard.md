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
