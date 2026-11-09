# PBDIGIT-339: One member's own membership data is shown to other members (shared cache key)

**Type:** Security / privacy defect · **Epic:** none · **Created:** 2026-11-09 · **Raised by:** found by Claude during PBDIGIT-338 (outside its scope).

| | |
|---|---|
| **Status** | `IMPLEMENTED` (working tree, not committed) · authorised by the Principal (accepted recommendation, 2026-11-09) · RED → GREEN verified · awaiting PO acceptance |
| **Classification** | **reproduced by test** (HEAD `e2051cbbe`): member B was shown member A's `has_membership = true` |

## Defect (FACT)
- `MembershipDashboardController::index` caches the page data under `membership_dashboard_{organisation_id}_{role}` for 300 s (`app/Http/Controllers/Membership/MembershipDashboardController.php:28-32`).
- For role `member`, the cached data includes `memberSelf = getMemberData($organisation)` (`:98-104`), which reads the **signed-in user** (`auth()->id()`, `:144-150`, `:162`): status, expiry, days left, pending fees, self-renew flag, last five applications.
- The key carries no user. **Consequence:** for up to 5 minutes, every other member of the organisation who opens `organisations/{organisation}/membership` sees the first member's record. A member without membership may also see another person's record, or the reverse.
- Invalidation (`app/Listeners/InvalidateMembershipDashboardCache.php:18`) uses the same per-role key.

## Business impact
Personal data disclosure between members (status, fee debt). In a political party, membership status and fee arrears are sensitive. The page is the natural "my membership" destination on a phone, so mobile access would raise exposure.

## Scope when authorised
1. RED: a feature test where two members of one organisation open the dashboard in turn; the second must see only their own `memberSelf`.
2. GREEN, smallest change: do not cache the member self-view (or key it per user); keep officer aggregates cached if wanted; align the listener.
3. Check other `Cache::remember` keys in Membership controllers for the same pattern.

## Out of scope
Redesigning the dashboard; the ratified Membership kernel cutover.

## Related
`PBDIGIT-338` (member navigation withholds the "My membership" link until this is fixed).

## Progress (2026-11-09)
- **Fix** (`MembershipDashboardController.php`): the `member` branch caches `memberSelf => null`; `index()` fills `memberSelf` per request for role `member`. Officer aggregates stay cached; cache keys and the listener are unchanged. Entries cached before deployment are overwritten on read, so no stale self-view is served.
- **Regression tests** (`tests/Feature/Membership/MembershipDashboardTest.php`):
  - `a_member_never_sees_another_members_own_membership_data`: A (with membership) then B (without); B must see `has_membership = false` and none of A's fields.
  - `a_member_sees_their_own_change_without_waiting_for_a_cache`.
- **Not run yet.** The main tree has a cached configuration (`bootstrap/cache/config.php`, 2026-10-19, git-ignored), so the PBDIGIT-283 guard refuses, as intended. The documented alternative is a clean worktree (`developer_guide/membership/11`). It is prepared in the agent's scratchpad, but needs `.env` linked for the test database password, which the agent's permissions refuse. The Principal runs the RED → GREEN script.
- Other `Cache::remember` sites in Membership controllers: none (searched `app/Http/Controllers/Membership`).

## Evidence (2026-11-09, clean worktree at `e2051cbbe`, test database `nrna_test`; run by the Principal, then compared by Claude)
- **RED** (HEAD controller + new tests): 11 tests, 2 failures, exactly the two new ones: B saw A's `has_membership = true`; A's own change was hidden by the cache.
- **GREEN** (fixed controller): 11 / 11 pass (91 assertions).
- **No regression:** the whole `tests/Feature/Membership` folder has 65 failing tests with the HEAD controller and 63 with the fix. The difference is exactly the two new tests. The 63 failures are pre-existing (for example the `organisation_participants_role_check` violation) and outside this item.
- **Browser confirmation (2026-11-09):** on a throwaway database with a file cache, members A and B opened the page one after the other at phone width, and each saw only their own record. The shared cache entry held no self-view (`PBDIGIT-338`, journey evidence).
