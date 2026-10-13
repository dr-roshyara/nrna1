# Voter slug — developer guide index

A voter slug is a voter's voting link for one election (`VoterSlug` real, `DemoVoterSlug` demo): `/v/{vslug}/…`, valid for a fixed `VOTING_TIME_IN_MINUTES` (default 30) from its creation.

**The business rule (`PBDIGIT-274`):** an entitled voter has exactly one current, valid link.
- A valid link is reused.
- An expired link is replaced by a new one, and the old one is kept as history (never hard-deleted).
- There is no link after voting or without entitlement.
- A link is a secret: it never appears in logs.

| # | Guide | Story | Status |
|---|---|---|---|
| 01 | [`VerifyVoterSlug`, the server-side voter-slug guard](01_verifyvoterslug_guard.md) | `PBDIGIT-273` | implemented with database-free tests green; request-path tests not run; committed `6d3b9e1e0`, not pushed; merge and deploy only with `PBDIGIT-274` |
| 02 | [The lifetime rule and the route lookup](02_lifetime_rule_and_route_lookup.md) | `PBDIGIT-274` S1 | implemented with database-free tests passing; in HEAD via `4d83dffc0` (original `a78d7c2f2`); not pushed |
| 03 | [The isolated test database guard](03_isolated_database_guard.md) | `PBDIGIT-274` S2 steps 1, 1b | step 1 `2e9017487`; step 1b `2c14db308`; `APPROVED_NAME` set after G4 in `bacef4220`; proved against PostgreSQL in the G5 run (2026-10-12) |
| 04 | [Step tracking by the slug's organisation](04_step_tracking_organisation_lookup.md) | `PBDIGIT-274` | `59ce21fbe` + `6e01ce0c1`; readers `b1364337f` + `e3d97b60f`; real-database test passed in the G5 run |
| 05 | [One current link per voter: the partial unique index](05_active_slug_partial_index.md) | `PBDIGIT-274` S2 | `7ded36437`; run on the isolated database only. **Production execution needs G8** |
| 06 | [`VoterSlugIssuer`: issue, reuse or replace a link](06_voter_slug_issuer.md) | `PBDIGIT-274` S3, G6 | `0cb7c1111`; G6 real concurrency passed (`f3447a931`) |
| 07 | [Entry points and recovery go through the issuer](07_entry_points_and_recovery.md) | `PBDIGIT-274` S4 | `ac84ca4dc`, `4beb3cfbb`; isolated tests passing; default-suite recovery tests not run |
| 08 | [Fixed lifetime and kept history](08_fixed_lifetime_and_kept_history.md) | `PBDIGIT-274` S5 | `e2bd4cc8a`; isolated and database-free tests passing |
| 09 | [One strict voting time for every reader](09_one_strict_voting_time.md) | `PBDIGIT-274` S6 | `ac2a4736c`; characterisation before and after, fail-closed RED → GREEN |
| 10 | [Expired, foreign and closed links](10_expired_foreign_and_closed_links.md) | `PBDIGIT-274` S7 | `0aaadb31c`; isolated tests passing; two default-suite tests updated, not run |
| 11 | [The lifecycle end to end](11_lifecycle_flow_test.md) | `PBDIGIT-274` S8 | test `46bcdb9f7` (verification only) |
| 12 | [The cast success / failure contract](12_cast_success_contract.md) | `PBDIGIT-274` S9a, G7 | repair `083e4e785`, tests `39cad17d9`; receipt finding accepted → `PBDIGIT-279` |
| 13 | [Count invariant, immutability, retention scan](13_counts_immutability_retention.md) | `PBDIGIT-274` S9b | tests `cd259e82c` (verification only) |
| 14 | [No raw voting link in logs](14_no_raw_voting_link_in_logs.md) | `PBDIGIT-274` S9c, O-7 | `712fa9250`, tests `9bce001ae` |

**How to run the database tests:** `. ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml <file>`, on the isolated database only (guide 03). Never `nrna_test`, never a shared or development database.

**Still open for `PBDIGIT-274`:**
- **The default-suite regression:** it needs a test database other than `nrna_test`.
- **G8, the release:** apply the S2 migration to production with the plan's runbook, run the baseline query (guide 13), and check that `MAX_USE_IP_ADDRESS` is set (`PBDIGIT-278` item 8).
- **Closure:** record the outcome on `PBDIGIT-276`.
