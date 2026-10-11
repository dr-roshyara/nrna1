# PBDIGIT-273 `VerifyVoterSlug`: evidence, 2026-10-11

**Authority:** Human PO, 2026-10-11, in session:
- plan approved;
- fixed lifetime from creation, `>=` boundary, cap enforced in the guard now;
- core scope only;
- request-path tests on an isolated database only.

The Principal Reviewer's conditional approvals are recorded in the plan.

Outputs (`.out`) and exit codes (`.exit`) are copied unchanged. Only the **database-free** unit file was run. No `.env` file was read. Nothing is committed.

| Stage | Files | Result |
|---|---|---|
| RED | `01-red-verifyvoterslug` | exit 2: 22 tests, **14 assertion failures**, 1 error (a Mockery count exception: the old code logs at `emergency`). The characterisation cases (valid, voted, owner, inactive, 1 s before the deadline) pass |
| GREEN | `02-green-verifyvoterslug` | exit 0: 22 tests, 67 assertions |
| route marker | `03-route-marker-characterisation` | exit 0: on all 19 verified slug routes, the binding's name convention and the `election.demo` marker agree (characterisation) |
| null organisation (review point 4) | `04-red-null-organisation`, `05-green-null-organisation` | RED: 1 assertion failure (null equals null passed) → GREEN: 24 tests |
| runtime routes and guard order (review points 1, 5) | `06-route-runtime-characterisation` | exit 0: 2 tests, 222 assertions, run on the **registered** route objects (characterisation) |
| fixed lifetime cap | `07-red-creation-cap`, `08-green-creation-cap` | RED: 4 assertion failures → GREEN: 31 tests, 328 assertions |
| REFACTOR | `09-refactor-verifyvoterslug` | exit 0: 31 tests, 328 assertions |

**Every run** prints "OK, but there were issues! … Deprecations: 3". The deprecations are PHP 8.5 notices about `PDO::MYSQL_ATTR_*` constants in `vendor/laravel/framework/config/database.php`. They come from library code, not project code.

**Database contact:**
- The final unit file runs no query: an assertion counts queries, and the other cases avoid the database by construction.
- **Disclosure:** during the RED runs, the **old** middleware ran read-only `SELECT`s on `voter_slugs` / `demo_voter_slugs` in `nrna_test` for the guest-with-string-slug case. Nothing was written. The new code rejects guests before any lookup.

**Written, not run:** `tests/Feature/Voting/VerifyVoterSlugRequestPathTest.php` (7 cases) and `ConstitutionalVotingProtectionTest::test_expired_slug_is_rejected_at_code_creation`. They need an isolated, identity-verified test database.

**Not claimed:**
- request-path correctness;
- the production election-lookup branch;
- removal of the in-place expiry extensions (`PBDIGIT-274`);
- that no raw slug is logged anywhere: the renderer logs the URL (`PBDIGIT-276`).
