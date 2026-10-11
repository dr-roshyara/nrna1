# PBDIGIT-273: `VerifyVoterSlug`, the server-side voter-slug guard (V-1 … V-6 and the fixed lifetime)

**Type:** Security story (voting request path) · **Epic:** none (voting) · **Created:** 2026-10-11 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), on the Human PO decisions of 2026-10-11 and the Principal Reviewer's conditional approval. **ID note:** the next free ID (`git grep "PBDIGIT-273"`: no occurrence). **Index row:** added 2026-10-11.

| | |
|---|---|
| **Status** | `IN REVIEW` · implemented test-first, **database-free tests green**; request-path tests **written, not run** (they need a dedicated test database); not committed; not accepted. **Must not be deployed without `PBDIGIT-274`** (see Limitations) |
| **Plan** | `docs/plans/20261011-1318-verifyvoterslug-hardening-plan.md` |
| **Evidence** | `docs/publicdigit/election/evidence/2026-10-11-verifyvoterslug/` |
| **Guide** | `developer_guide/election/voter_slug/01_verifyvoterslug_guard.md` |

## Decisions (Human PO, 2026-10-11)
- **The 30-minute validity is fixed from slug creation and never extended in place.** Continuing after expiry needs a new slug.
- **Expired at `now >= expiry`, on every step, step 1 included.** The old idea that an expired slug can be renewed at code entry is dropped.
- **The guard enforces the cap now:** a slug is valid only while `now < min(expires_at, created_at + voting.time_in_minutes)`. The lifecycle fixes follow in `PBDIGIT-274`.
- **Scope is the middleware core only;** the other gaps are tracked in `PBDIGIT-274` … `278`. Those are backlog entries: documented, not authorised, not fixed.
- **Request-path tests need a dedicated test database.**
- **A voter slug is specific to one election.** Only a voter valid for that organisation and election may receive one. This increment enforces that slug, election and organisation agree. Who may *receive* a slug is decided at issuance, in `PBDIGIT-274`.

## Contract (the order inside `handle`)

| # | Rule | On failure |
|---|---|---|
| V-1 | an authenticated user, checked before any lookup | `AuthenticationException` (401 or a login redirect) |
| V-2 | slug type matches the route (`election.demo` middleware marks demo routes); no fallback between types | `InvalidVoterSlugException` 404 |
| V-3 | the slug belongs to the authenticated user | `SlugOwnershipException` 403 |
| V-5 | not revoked: not soft-deleted, active, status not `expired` | `InvalidVoterSlugException` 404 |
| V-4 | not expired: `now >= expires_at`, or `now >= created_at + voting.time_in_minutes`, or a missing timestamp | `ExpiredVoterSlugException` 403 |
| V-6 | the election exists and isn't deleted; organisation present and equal on slug and election; election type matches the route | `ElectionNotFoundException` 404, `OrganisationMismatchException` 500, `ElectionMismatchException` 500 |

## Acceptance criteria
- [x] Database-free tests, run on the real middleware, cover every rule and every boundary, including:
  - a route-runtime test on the 19 registered routes;
  - a guard-order test.
- [ ] The request-path tests (`tests/Feature/Voting/VerifyVoterSlugRequestPathTest.php`) and the rewritten `ConstitutionalVotingProtectionTest::test_expired_slug_is_rejected_at_code_creation` run green on a dedicated test database.
- [ ] Formal acceptance by the Human PO.

## Limitations (do not claim more)
- **Deploy only together with `PBDIGIT-274`.** With the creation-time cap, two existing paths now lock voters out:
  - `ElectionVotingController::start` "restarts" a session on the **old row** (old `created_at`);
  - it also reuses **soft-deleted** rows without restoring them, which V-5 now rejects.
- **The "fixed from creation" lifetime is enforced by this guard only.** The in-place extensions (`CodeController::create`, `VoterSlugService::getOrCreateActiveSlug`, `extendSlugExpiry`) still change `expires_at`; they no longer lengthen a slug's life, but they remain until `PBDIGIT-274`.
- **The production election lookup branch** (`Election::withoutGlobalScopes()->find`) is covered only by the request-path tests, which have not run.
- **"No raw slug in logs" applies to this middleware only.** The exception renderer (`bootstrap/app.php`) logs the request URL, which contains the slug (`PBDIGIT-276`).
- **Step order (V-7) and voting entitlement (V-8)** are later middleware and are not changed here (`PBDIGIT-275`).
