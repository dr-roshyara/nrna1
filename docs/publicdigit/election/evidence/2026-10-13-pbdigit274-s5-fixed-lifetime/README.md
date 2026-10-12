# PBDIGIT-274 S5: fixed lifetime and kept history for real-election voting links, 2026-10-13

**Authority:** Human PO ("start S5").

**Scope:** `pbdigit274_isolated_test` only (committing test base, no migration), plus database-free tests. No password appears in this folder.

## Changes
| File | Before | After |
|---|---|---|
| `app/Http/Controllers/ElectionController.php` (`POST /election/select`, `election.store`) | real elections via `VoterSlugService::getOrCreateSlug(…, false)`: in-place reuse, and **`cleanupExpiredSlugs` hard-deleted the voter's expired links** | real elections via `VoterSlugIssuer::fresh()`; demo unchanged (a fresh slug each time). **This live real-election entry point was missed in S4** |
| `app/Services/VoterSlugService.php` | `getOrCreateSlug` served real elections; `extendSlugExpiry` (+30 min sliding); `getOrCreateActiveSlug` extended an existing slug | `getOrCreateSlug` **refuses real elections** (LogicException, before any query), so its in-place rewrite, `cleanupExpiredSlugs` and the `forceNew` hard delete are demo-only (D1). `extendSlugExpiry` is a deprecated no-op returning false. `getOrCreateActiveSlug` returns an existing slug unchanged (its only route, `vote.direct`, is unreachable anyway) |
| `app/Http/Controllers/CodeController.php` | on code regeneration (`!has_code1_sent && sent_at`) the voter slug was **extended** (`expires_at = now + T`) and reactivated | no slug write; the lifetime stays fixed |
| `app/Console/Commands/CleanupExpiredVoterSlugs.php` | **hard-deleted every soft-deleted real slug** older than the cutoff, i.e. the replaced-link history | real slugs are never deleted; it only reports what is kept. Demo cleanup unchanged |

## Runs
| # | File | Result |
|---|---|---|
| 01 | `01-red-db-free.out` | RED, database-free: `getOrCreateSlug` (real) went straight to the database; `extendSlugExpiry` wrote. The first attempt of the second test passed vacuously (an unsaved model writes nothing); fixed with `exists = true` |
| 02 | `02-red-database.out` | **misleading RED**: the two `election.store` tests failed only because the request answered **404** (the election is loaded through the tenant scope and the test set no organisation in the session). The code-regeneration test passed vacuously: it regenerated through `getOrCreateCode`, not the extension block |
| 03 | `03-red-database-rerun.out` | code-regeneration fixture corrected (reaches the extension block): **RED, expiry moved by 600 s**. The `election.store` tests were still affected by the 404 |
| 04 | `04-green.out` | the `election.store` tests still failed (404); diagnosed |
| 05 | `05-red-election-store.out` | **honest RED** with `current_organisation_id` in the session and the old `ElectionController` / `VoterSlugService` temporarily restored from HEAD: "expired" fails (**1 row: the old path hard-deleted the expired link**); "reuse while valid" already passed with the old code, so it is a regression guard, not a RED |
| 06 | `06-green.out` | **S5 4 / 14**; recovery 7 / 30; entry points 9 / 47; issuer 18 / 77; G6 4 / 56; all exit 0. Database-free suites green (incl. the 2 new S5 tests) |

## Default-suite test updated; NOT RUN here (it uses `nrna_test`)
`tests/Unit/Models/VoterSlugExpirationTest.php`: the five real-election tests that called `getOrCreateSlug` now call `VoterSlugIssuer` (stand-in entitlement). They are reuse, expired → new, and the isolation cases across elections and users.

**Decision-driven change:** `test_real_election_creates_new_slug_when_existing_is_expired` used to **require a hard delete** of the old slug. It now requires the old slug to be **kept** soft-deleted (PO: real voter slugs are never hard-deleted).
