# Voters List — Voted Column, Participation Stats, Voted Filter, Last-Login Column

*(Replaces the previous Candidate Photo Edit plan in this file — that feature is already implemented and committed; see `.claude/sessions/2026-09-13.md` and commit `6b93cbeb7`. This is a new, unrelated task.)*

## Context

`/organisations/{org}/elections/{election}/voters` (`OrganisationController::voters()` → `Organisations/Voters.vue`) lists an election's registered voters. The user asked for four additions:
1. A column showing whether each voter has voted.
2. Participation statistics above the table (voted count, not-voted count, participation %).
3. A filter to show only voted / only not-voted voters.
4. A "Last Login" column, sourced from the `users` table.

## Research findings (grounds every decision below)

- **The existing `has_voted` data source is dead.** `OrganisationController::voters()` (`app/Http/Controllers/OrganisationController.php:722-778`) already maps `'has_voted' => (bool) $m->has_voted` from `ElectionMembership`, and `Voters.vue` already has a checkmark + "Voted" badge wired to it (lines 58-60, 179-187). But `ElectionMembership::markAsVoted()` (`app/Models/ElectionMembership.php:162-169`, sets `has_voted`/`voted_at`/`status`) has **zero production callers anywhere in the codebase** — confirmed via repo-wide search. This column has therefore always shown "not voted" for everyone, regardless of reality.
- **The real source of truth is `Code.has_voted`** (`app/Models/Code.php`) — this is the same fact this session already relied on for the receipt-codes and voting-codes-export work. One `Code` row exists per `(election_id, user_id)`.
- **No login tracking exists at all.** No `last_login_at`/`last_active_at` column on `users`, no listener on `Illuminate\Auth\Events\Login`, no migration for it anywhere in `database/migrations/`. This is new, not a fix.
- **Login already fires the standard Laravel event.** Both login entry points (`app/Http/Controllers/Auth/AuthController.php:56`, `app/Http/Controllers/Auth/LoginController.php:57`) call `Auth::attempt(...)`, which dispatches `Illuminate\Auth\Events\Login` through Laravel's `SessionGuard` — a single `EventServiceProvider` listener covers both, no controller changes needed.
- **No existing test covers `voters()`.** The only test referencing `organisations.elections.voters*` routes (`tests/Feature/Audit/VoterVerificationControllerAuditTest.php`) is about a different route (code verification), not this list endpoint.
- Existing filter/sort/pagination pattern in `voters()` (status filter, `sort`/`direction` query params, `paginate(50)`) is the pattern to extend, not replace.

## Design decisions

1. **Fix `has_voted` at the source**: replace the `ElectionMembership.has_voted` read with a `Code`-backed lookup, scoped to this election. Build one `user_id => bool` map (`Code::withoutGlobalScopes()->where('election_id', ...)->pluck('has_voted', 'user_id')`) once per request and use it both for display and for the new filter — avoids an N+1 and avoids a SQL join against a paginated, sorted membership query.
2. **Do not touch `ElectionMembership::markAsVoted()` or wire it up.** It's dead code with no callers — fixing *why* it's never called is a separate, unrelated concern (likely belongs to the actual vote-casting flow, `VoteController`/`DemoVoteController`, which already sets other models' `has_voted` flags). Out of scope; not silently expanded into this task.
3. **Stats computed over the full electorate for this election**, independent of pagination and independent of the existing `status` filter (so the numbers always describe "the whole election," not whatever page/filter is currently applied) — total voter memberships, voted count (via the same `Code` map), not-voted count, participation percentage (rounded, 0 when total is 0).
4. **New `voted` filter param** (`voted` / `not_voted`), composable with the existing `status` filter — implemented as `whereIn`/`whereNotIn('user_id', $votedUserIds)` against the memberships query, consistent with the existing filter style in the same method.
5. **`last_login_at`**: new nullable `timestamp` column on `users` (migration), a new `App\Listeners\RecordLastLoginTimestamp` on `Illuminate\Auth\Events\Login` (registered in `EventServiceProvider`, alongside the existing `Registered::class` mapping already there), using `updateQuietly()` to avoid triggering unrelated model events. Exposed per-voter in the `voters()` payload.
6. **Frontend**: `Voters.vue` gains (a) a stats bar above the table (Voted / Not Voted / Participation %), (b) a dedicated "Voted" column (Yes/No — additive to, not replacing, the existing name-checkmark), (c) a "Last Login" column (formatted date, or "Never" when null), (d) a voted-filter control next to the existing status filter, using the same `router.get(...)`/`applyFilters()` pattern already there.

## Files to change

**Backend**
- `database/migrations/2026_09_14_xxxxxx_add_last_login_at_to_users_table.php` (new) — nullable `timestamp('last_login_at')->nullable()` on `users`.
- `app/Listeners/RecordLastLoginTimestamp.php` (new) — `handle(Login $event)` → `$event->user->updateQuietly(['last_login_at' => now()])`.
- `app/Providers/EventServiceProvider.php` — add `\Illuminate\Auth\Events\Login::class => [\App\Listeners\RecordLastLoginTimestamp::class]` to `$listen`.
- `app/Http/Controllers/OrganisationController.php` — rewrite `voters()`: build the `Code`-backed voted map, compute stats, add the `voted` filter, add `last_login_at` to both the per-row payload and pass `stats` as a new Inertia prop.

**Frontend**
- `resources/js/Pages/Organisations/Voters.vue` — stats bar, new "Voted" and "Last Login" columns/headers, voted-filter control wired the same way as the existing status `<select>`.

**Tests (written first, TDD)**
- `tests/Feature/Organisation/ElectionVotersListTest.php` (new): voted column reflects `Code.has_voted` (not the dead `ElectionMembership.has_voted`) for both true and false cases; stats (total/voted/not_voted/percentage) correct and unaffected by pagination/status filter; `?voted=voted` and `?voted=not_voted` filter correctly and compose with the existing `?status=` filter; `last_login_at` is `null` for a voter who never logged in and a timestamp for one who has; authorization unchanged (existing `canAccessElection` gate still enforced — one regression-style test).
- `tests/Feature/Auth/RecordLastLoginTimestampTest.php` (new): a successful login updates `users.last_login_at`; a failed login attempt does not.
- `resources/js/Pages/Organisations/Voters.spec.js` (new, Vitest — no existing spec for this page): stats bar renders the right numbers, "Voted"/"Last Login" columns render expected values, the voted-filter control triggers the right query params.

## Verification

1. TDD per the "Required sequence" this session has followed throughout: write the new test files first, run them to confirm RED against the current code, then implement the minimal change, confirm GREEN.
2. `php artisan test tests/Feature/Organisation/ElectionVotersListTest.php tests/Feature/Auth/RecordLastLoginTimestampTest.php` — all new tests GREEN.
3. `npx vitest run resources/js/Pages/Organisations/Voters.spec.js` — GREEN.
4. Re-run any pre-existing tests touching `ElectionMembership`/`OrganisationController::voters()` to confirm no regression (none currently exist for this exact method, per research above, but `ElectionMembership` itself may have unrelated model tests — run those too as a safety check).
5. `npm run design-check` after the Vue change.
6. Manual note: cannot browser-test in this environment; report this explicitly rather than claiming visual verification.

## Progress (same task, continuing)

This is the same approved plan, mid-implementation — not a new task. Status so far:

- **Phase 1–3 read-only verification: DONE.** `Code.has_voted` invariant empirically confirmed (sole writer `VoteController::markUserAsVoted()`, same DB transaction as the vote save, DB-enforced unique index on `(election_id, user_id)`, no other write path found). `ElectionMembership::markAsVoted()` confirmed dead (no callers), left untouched. Login event confirmed fired by all 5 login entry points (password + 2 OAuth controllers + invitation auto-login) via the standard `SessionGuard`. Authorization gate (`ChecksElectionAccess::canAccessElection`) confirmed unchanged in scope. No contradictions found — full findings already reported to the user in-conversation.
- **TDD RED: DONE.** `tests/Feature/Auth/RecordLastLoginTimestampTest.php` (2 tests) and `tests/Feature/Organisation/ElectionVotersListTest.php` (14 tests) written and confirmed RED for the expected reasons (missing `last_login_at` column; controller doesn't yet compute stats/voted-filter/Code-backed has_voted).
- **Implementation, in progress:**
  - ✅ Migration `database/migrations/2026_09_14_081219_add_last_login_at_to_users_table.php` created and migrated (nullable `timestamp('last_login_at')`, no backfill).
  - ⏳ Not yet done: `app/Listeners/RecordLastLoginTimestamp.php`, `EventServiceProvider` wiring, `OrganisationController::voters()` rewrite, `Voters.vue` frontend changes, GREEN verification, `Voters.spec.js`.

No commit has been made. Resuming implementation per the already-approved plan above — no new requirements to plan for.

## Explicitly out of scope

- Fixing `ElectionMembership::markAsVoted()`'s missing caller (a separate, real gap — flagged, not fixed here).
- Any change to the vote-casting flow itself (`VoteController`, `DemoVoteController`).
- Tracking "last activity" beyond login (e.g. last page view) — only login events, per the explicit request ("last logged in").
