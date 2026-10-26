# PBDIGIT-308: Login and voter-management feature tests fail on stale fixtures

**Type:** Test maintenance · **Epic:** none · **Created:** 2026-10-26 · **Raised by:** found while verifying PBDIGIT-307.

| | |
|---|---|
| **Status** | `OPEN` · not started |
| **Classification** | **observed:** failures pre-exist PBDIGIT-307. Same results with and without its change (compared by stashing the change) |
| **Scope** | test fixtures only, until the cause is confirmed |

## Finding (2026-10-26, branch `election-flow-updated`)
- `php artisan test tests/Feature/Auth`: **93 tests fail**, none caused by PBDIGIT-307. The 13 failing files include `CsrfProtectionTest`, `DashboardResolver*`, `LoginFallbackChainTest`, `LoginResponseTest`, `RegistrationAndLoginTest`, `VerifiedMiddlewareTest` and `VoterInvitationSetPasswordTest`.
- The related suites `VoterInvitationSetPassword|ElectionVoterManagement|EnsureElectionVoter|SingleElectionLoginFlow`: **27 fail.** The errors:
  - 18 × `user_organisation_roles_user_id_organisation_id_unique` duplicate key. A factory or observer and the test both create the role row.
  - 8 × `elections.organisation_id` NOT NULL: demo elections are created with `organisation_id = NULL`, which the schema no longer allows.
  - 1 × `elections.slug` NOT NULL.

## Why it matters
Login and invitation regressions cannot be detected by these suites while they are red.

## Next
- Confirm the cause per group.
- Fix the fixtures, not the schema. The NOT NULL on `organisation_id` is a deliberate tenancy decision: check it before touching it.
