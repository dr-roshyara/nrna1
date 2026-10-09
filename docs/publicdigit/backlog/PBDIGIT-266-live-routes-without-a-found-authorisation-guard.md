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
