# PBDIGIT-274 S7: what the voter sees when a voting link is expired, foreign or the election is closed, 2026-10-13

**Authority:** Human PO ("O-5: plain not found, then start S7").

**Scope:** `pbdigit274_isolated_test` only (committing test base, no migration), plus database-free tests. No password appears in this folder.

## Decisions applied
- **O-5 = (b):** someone else's link answers exactly like an unknown link (404 "Voting link not found."). The reason is logged internally with ids only.
- **O-2:** a closed election is its own outcome, separate from an expired session. No restart is offered.

## Changes
| File | Before | After |
|---|---|---|
| `app/Http/Middleware/VerifyVoterSlug.php` (V-3) | `SlugOwnershipException` (403), which told the caller that the link exists | plain `abort(404, 'Voting link not found.')`, the same as the `{vslug}` binding for an unknown link; a warning log with `slug_id` and `reason = not_owner` |
| `app/Http/Middleware/VerifyVoterSlug.php` (V-4) | context: slug id only | also `election_id` and `organisation_id`, so the renderer can send the **owner** back to the election (ownership is already checked at V-3) |
| `app/Exceptions/Voting/ElectionVotingClosedException.php` (new) | — | 403, message "Voting in this election is closed." |
| `app/Http/Middleware/ValidateVoterSlugWindow.php` (check 2) | an ended election threw `ExpiredVoterSlugException` | throws `ElectionVotingClosedException` |
| `bootstrap/app.php` | every `VotingException` on the web went to the generic handler (dashboard) | `ExpiredVoterSlugException` and `ElectionVotingClosedException` on **web** requests redirect to `elections.show` (fallback `organisations.show`, then `dashboard`) with the matching message; JSON keeps the generic 403. Logs carry ids only, never the URL |

## Runs
| # | File | Result |
|---|---|---|
| 01 | `01-red.out` | RED: 5 of 6 tests in `VoterSlugExpiryRoutingTest` fail (dashboard redirect, 403 for a foreign link, closed shown as expired). The JSON 403 test already passed: it is a regression guard |
| 02 | `02-green.out` | GREEN: `VoterSlugExpiryRoutingTest` 6 / 24 |
| 03 | `03-guard-unit.out` | `VerifyVoterSlugTest` (database-free) 34 / 340, after the decision-driven update below |
| 04a | `04a-voting-unit-shared-cache.out` | database-free voting unit tests (issuer, lifetime, route lookup, S5 fixed lifetime) on the configured cache: **2 failures in `VoterSlugRouteLookupTest`, both 429 "Too many attempts"**. A pre-existing S1 test defect, not S7: the test freezes the clock, so the limiter's 60 s window never expires and hits pile up across runs. It is recorded as PBDIGIT-278 item 5. (The run before it, with 1 failure, was overwritten.) |
| 04b | `04b-voting-unit-array-cache.out` | the same tests with `CACHE_STORE=array`: 50 / 120, all green |
| 05 | `05-isolated-all.out` | the whole isolated PBDIGIT-274 suite: 68 / 310, identity check `pbdigit274_isolated_test` confirmed |

## Decision-driven test changes (O-5)
- `tests/Unit/Http/Middleware/VerifyVoterSlugTest.php`:
  - `test_another_users_slug_is_rejected` now expects `NotFoundHttpException` with "Voting link not found." instead of `SlugOwnershipException`.
  - `test_no_raw_slug_reaches_logs_or_exception_context_and_nothing_is_logged_as_emergency` now checks that the 404's message carries no raw link.
  - The unused imports were removed.

## Default-suite tests updated; NOT RUN here (they use `nrna_test`)
- `tests/Feature/Voting/VerifyVoterSlugRequestPathTest.php::test_another_voters_slug_is_rejected`: 403 `SlugOwnershipException` → 404 (O-5).
- `tests/Feature/Election/ConstitutionalVotingProtectionTest.php::test_expired_slug_is_rejected_at_code_creation`: the stale 410 expectation (from before S1, when the binding rejected expired links) → a redirect to `elections.show`.

Other `assertRedirect(route('dashboard'))` assertions in the default suite cover voters who have **already voted**. S7 does not change them.

## Known limits
- **Guests:** the binding runs before authentication, so a guest still gets 404 for an unknown link but a login redirect for an existing one. This is tracked in PBDIGIT-275, not S7.

Checksums: `SHA256SUMS`.
