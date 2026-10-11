# Plan: Strengthen `VerifyVoterSlug`, the server-side voter-slug guard (test-first)

## Context

**Why:** the Product Owner requires a voter slug to be valid for **30 minutes**, and an expired slug must be rejected server-side on every protected voting request. `App\Http\Middleware\VerifyVoterSlug` is the one guard on **every** `{vslug}` voting route: real steps 1–5 and all demo steps (`routes/election/electionRoutes.php:497, 514, 577`).

**What it checks today:** only existence, ownership (`!==`) and `is_active`. It also logs the raw slug and URL at `Log::emergency` on every request.

**Gaps found by exploration (read-only, 3 agents):**
- **No expiry check of its own.** Expiry is checked only by the `{vslug}` route binding (`RouteServiceProvider.php:103`, `isPast()`, so a slug is still valid at exactly `expires_at`), by `ValidateVoterSlugWindow` (not on step 1), and by `EnsureVoterStepOrder` (real step routes only).
- **Revoked slugs are accepted.** Lookups use `withoutGlobalScopes()`, which also drops the soft-delete scope, so soft-deleted slugs resolve (`VoterSlug` uses `SoftDeletes`).
- **No explicit guest rejection.** The real routes have no `auth` middleware, so a guest gets through until `null !== user_id` happens to fail the ownership check.
- **Slug type is not tied to the route.** The middleware falls back from `VoterSlug` to `DemoVoterSlug`, and the election-type check in `VerifyVoterSlugConsistency` is one-way (it misses a real route with a demo election).
- **No election or organisation check in this guard.** The organisation check in `VerifyVoterSlugConsistency` relies on an `organisation_id === 1` shortcut, which is dead code with UUID ids.

**Human PO decisions (2026-10-11, in session):**
- **30-minute rule:** fixed from slug creation, never extended in place; continuing after expiry needs a **new** slug.
- **Boundary:** a slug is expired when `now >= expires_at`, on **all** steps, step 1 included. The idea that an expired slug can be renewed at code/create is dropped.
- **Test database:** request-path tests need a **dedicated test database** that you provision. Until then, only database-free middleware tests are run; the request-path tests are written but not run.
- **Scope:** **only the `VerifyVoterSlug` core** (V-1 to V-6 plus safe logging). Every other gap becomes a backlog item.

Story: a new **`PBDIGIT-273`**. I'll confirm the ID is free before using it.

## Verification contract (the order inside `VerifyVoterSlug::handle`)

| # | Rule | On failure (existing exceptions, rendered by `bootstrap/app.php:213-233`) |
|---|---|---|
| V-1 | an authenticated user is required, checked **before** any slug lookup | `Illuminate\Auth\AuthenticationException` (framework convention: redirect to login, or 401 for JSON) |
| V-2 | resolve the slug **by route type**: if the route name contains `demo`, expect `DemoVoterSlug`; otherwise `VoterSlug`. A model-bound slug must be an instance of the expected class; a string slug is looked up **only** in that model (no cross-fallback) | `InvalidVoterSlugException` (404, generic "Voting session not found") |
| V-3 | ownership: `(string) $slug->user_id === (string) auth()->id()` | `SlugOwnershipException` (403) |
| V-5 | not revoked: rejected if `trashed()` (only `VoterSlug` has `SoftDeletes`), `!is_active`, or `status === 'expired'` | `InvalidVoterSlugException` |
| V-4 | not expired: `expires_at === null` **or** `now() >= expires_at` means expired (a missing expiry is rejected) | `ExpiredVoterSlugException` (403) |
| V-6 | the slug's election exists (through the existing `election()` relation, which bypasses scopes; I'll confirm `DemoVoterSlug` does too, otherwise query explicitly), **and** election `organisation_id` equals slug `organisation_id` (string compare), **and** a demo route requires a demo election while a real route requires a non-demo election | `ElectionNotFoundException` (404), `OrganisationMismatchException`, `ElectionMismatchException` (these two exist with status 500, which is a known oddity and goes to backlog) |
| — | set the `voter_slug` request attribute only after all checks pass. Log at `warning` with the slug's database id only: no raw slug, no URL, no `Log::emergency` | — |

**Not changed here** (reported, then backlog):
- **V-7 step order** is `EnsureVoterStepOrder`, but it covers real routes only; demo routes are missing from `config/election_steps.php`.
- **V-8 voting entitlement** is `EnsureElectionVoter` plus `VoteEligibility`, which `ElectionMembership` / `VotingEntitlementQuery` back. The open gap: a demo-session flag skips `canVote()` on real routes.
- **A voted slug is not rejected,** because the thank-you and verify-to-show routes need it after voting. That belongs to V-7.

## Files

**Change:**
- `app/Http/Middleware/VerifyVoterSlug.php`, the only production file. Small private helpers `expectedModel($request)`, `isExpired($slug)`, `assertElectionConsistent(...)`. Clock is `now()`, controlled in tests with `Carbon::setTestNow` (already used in the repo, e.g. `VotingButtonsStateMachineTest:29`).
- `tests/Feature/Election/ConstitutionalVotingProtectionTest.php:253`: rewrite the "expired slugs must reach CodeController" test to the decided rule (an expired slug is rejected at step 1). It needs a database, so it is only run on the dedicated one.

**New:**
- `tests/Unit/Http/Middleware/VerifyVoterSlugTest.php`: **database-free**.
  - Extends `Illuminate\Foundation\Testing\TestCase` with `Tests\CreatesApplication`. **Not** `Tests\TestCase`, whose `setUp` writes to the database.
  - Uses in-memory `VoterSlug` / `DemoVoterSlug` / `User` / `Election` built with `new … / forceFill` and never saved. The `election` relation is set with `setRelation`; the user comes from `actingAs`.
  - A real `Request` with a bound `Route` (name plus the `vslug` model parameter).
  - The last test asserts **no database connection was opened**, which is evidence the tests really are database-free.
- `tests/Feature/Voting/VerifyVoterSlugRequestPathTest.php`: drives real routes (`slug.code.create`, `slug.code.agreement`, `slug.demo-code.create`) with factories, setting `election_id` / `organisation_id` explicitly because the `VoterSlugFactory` default lacks them. It clears the binding's rate limiter, `voter_slug_lookup:{ip}`. **Written now, run only on the dedicated database.**
- `docs/publicdigit/backlog/PBDIGIT-273-…md` (the story).
- Backlog items for the out-of-scope gaps, grouped:
  1. **Expiry conformance:**
     - remove the in-place extensions in `CodeController::create:179`, `VoterSlugService::getOrCreateActiveSlug:183` and `extendSlugExpiry`;
     - one duration source, `config('voting.time_in_minutes')`, replacing the missing `voting.slug_expiration_minutes` and the hard-coded `30`s;
     - binding boundary `isPast` → `>=`, and the binding resolving soft-deleted slugs;
     - the `voting:clean-expired-slugs` command is not scheduled.
  2. **Request-path authorisation gaps:**
     - no `auth` on the real `{vslug}` groups (which also allows slug probing through 404 / 410);
     - the demo-session bypass in `VoteEligibility:145`;
     - the one-way type check and the 500 status codes in `VerifyVoterSlugConsistency`;
     - demo steps missing from `election_steps`.
  3. **Voter-slug defects:**
     - `MiddlewareChainTest` targets a non-existent path;
     - the `vote_completed` field doesn't exist;
     - `VoterRecoveryService` calls `isExpired()` on `VoterSlug`, which doesn't define it;
     - `unique(election_id,user_id)` collides with soft-deleted rows.
- `developer_guide/election/…` guide step (Definition of Done); plan copy at `docs/plans/20261011-HHMM-verifyvoterslug-hardening-plan.md` (ES-004.2); `.claude/CONTEXT-publicdigit.md` and the session log, added to without touching other sessions' content.

## TDD sequence

1. **Baseline:** run the new unit test file empty (bootstrap check) and the existing database-free unit tests that touch slugs, if any. Record HEAD and the working tree.
2. **RED, database-free unit cases, written first:**
   - valid real and demo slug pass, and the attribute is set;
   - guest (V-1);
   - demo slug on a real route and vice versa (V-2);
   - wrong owner (V-3);
   - soft-deleted, inactive and `status=expired` (V-5);
   - 1 second before the deadline passes; **exactly at** the deadline is rejected; after it is rejected; null expiry is rejected (V-4);
   - organisation mismatch, demo election on a real route and the reverse, missing election (V-6);
   - the attribute is **not** set on failure, and `$next` is not called;
   - no database connection.

   Expected RED: assertion failures for V-1, V-2 (mismatch), V-4, V-5 (trashed / status), V-6. Already passing, as characterisation: valid, owner, inactive. I'll report the actual result.
3. **GREEN:** the minimal change in `VerifyVoterSlug.php` in the contract order; replace the logging.
4. **REFACTOR:** helpers only; rerun.
5. **Request-path tests and the rewritten `ConstitutionalVotingProtectionTest` case:** written. **Run only once the dedicated database exists**, through a scratch bootstrap (in the scratchpad, not committed) that pins that database and aborts unless `current_database()` matches. Otherwise they are recorded as "not run".
6. **Regression (database-free):** the unit suite parts that are database-free, plus `tests/Architecture` (with care: some architecture tests boot the database — run only those that don't, and list them).
7. **Evidence and documentation:**
   - evidence folder `docs/publicdigit/voting/evidence/2026-10-11-verifyvoterslug/` (placement checked with `doc-placement.php`) with outputs, exit codes, `SHA256SUMS` and a credential check;
   - the guide, story, backlog items, CONTEXT and the session log.

## Stop conditions
- Any test or tool would touch `nrna_test` or any shared database.
- The unit test opens a database connection.
- Making a case pass needs a change outside `VerifyVoterSlug.php`.
- A rule needed turns out ambiguous, for example whether a demo slug's organisation is the platform organisation.
- An existing database-free test breaks.

## Verification (what I'll be able to claim)
- **Database-free:** each contract rule is enforced by the middleware itself. Proven by unit tests on the real middleware class, with RED and GREEN outputs.
- **Request paths (steps 1–5, demo steps):** **only after** the dedicated database exists and the request-path tests run green. Until then: "written, not run". I won't claim the full flow is verified.
- **30-minute rule:** after this increment, expiry at `>= expires_at` is enforced on every `{vslug}` route that carries `voter.slug.verify`: all real and demo voting routes. The broken `v/{vslug}/test` route doesn't carry it, so that goes to backlog.
- **What this increment does not deliver:** "fixed from creation" is **not** yet true, because the in-place extensions remain until backlog item 1 is done. I'll state this explicitly.

Nothing is committed or pushed without separate authorisation.

## Progress (2026-10-11)

- **Done, test-first, database-free; not committed** (evidence `docs/publicdigit/election/evidence/2026-10-11-verifyvoterslug/`).
- **RED `01`:** 14 assertion failures, plus 1 Mockery error (the `emergency` logging test), out of 22.
  - While the tests were being set up, the first no-database check exposed that building an Eloquent `User` runs 6 schema-introspection queries, so the fixture uses `GenericUser`.
  - The old middleware ran read-only `SELECT`s on `nrna_test` for the guest-with-string-slug case in the RED runs. Nothing was written.
- **GREEN `02`:** 22 tests.
- **Route marker characterisation `03`:** passed (19 routes).
- **Principal review conditions:**
  - **null organisation:** RED `04`, GREEN `05`;
  - **runtime route detection and guard order:** characterisation `06`, 222 assertions, on the registered routes;
  - **fixed lifetime cap** (Human PO: cap now): RED `07` (4 failures), GREEN `08`.
- **REFACTOR `09`:** 31 tests, 328 assertions, exit 0.
- **Independent review (sub-agent):** no blocking defects. Should-fix items:
  - the `start()` soft-delete lockout goes to `PBDIGIT-274`;
  - the production election-lookup branch is covered only by the unrun request-path test (claim narrowed);
  - the renderer logs the URL (`PBDIGIT-276`).
- **Written, not run:** `tests/Feature/Voting/VerifyVoterSlugRequestPathTest.php` (7 cases) and the rewritten `ConstitutionalVotingProtectionTest::test_expired_slug_is_rejected_at_code_creation`. They need an isolated test database.
- **Not run (they would touch a shared database):** the existing database feature suites, e.g. `EnsureVoterSlugWindowTest`, `MiddlewareChainTest`, `VotingEligibilityMiddlewareRegressionTest`.
- **Deviation from the plan:** the fixed-lifetime cap and the null-organisation rule were added to scope by the Human PO and the review. The route type uses the `election.demo` marker, as the plan specified.
- **Backlog, documented only:** `PBDIGIT-274` … `278`. 276 was split into 276 / 277 / 278 after triage.
