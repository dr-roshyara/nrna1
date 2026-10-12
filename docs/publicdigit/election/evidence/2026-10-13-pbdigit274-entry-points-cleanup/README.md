# PBDIGIT-274: entry-point test cleanup fixed, 2026-10-13

**Finding (from the G6 evidence):** `VoterSlugIssuanceEntryPointsTest` left users, role rows, elections and posts in `pbdigit274_isolated_test`. `PostFactory::definition()` eagerly creates an election, and `CandidacyFactory::definition()` eagerly creates a post (with its election) and a user, even when ids are passed in. The test never tracked those rows.

**Fix:** the test inserts its post and approved candidacy directly (`DB::table`), tied to its own election; `candidacies.user_id` is nullable, so no user is needed.

| File | Result |
|---|---|
| `01-run-with-counts.out` | row counts **identical before and after** the run (`users=37 roles=37 elections=74 posts=37 …` both times); 6 tests / 27 assertions, exit 0 |

The leftovers from the earlier runs (37 users, 74 elections, 37 posts on the platform organisation) are still in the isolated database. They are harmless there and are removed by the next `RefreshDatabase` run; not deleted by hand. No password appears in this folder.
