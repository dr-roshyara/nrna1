# PBDIGIT-274 G6: real concurrency of the voter-slug issuer, 2026-10-13

**Authority:** Human PO ("start G6"), with decisions D2 (a cast stand-in performing the same participation update as `VoteController`) and D3 (test-only pause points, inactive in production).

**Scope:** `pbdigit274_isolated_test` only. Every worker process passes the identity guard (bootstrap plus in-app check) before any work. No password appears in any file here.

**Method:**
- **Processes:** two separate PHP processes (`tests/Isolated/Pbdigit274/Concurrency/g6-worker.php`), each with its **own PostgreSQL connection**, plus the test's own connection as controller.
- **Forcing the order:** the test holds a PostgreSQL advisory lock. Workers wait on it at pause points (`afterLocks()`, `beforeInsert()`; protected no-ops in production) or before the cast commits.
- **No guessing:** before releasing, the test confirms in `pg_locks` / `pg_stat_activity` that each worker really is blocked (on the advisory lock, or on a row lock).
- **The cast is a STAND-IN:** `UPDATE codes SET has_voted = true WHERE id = ? AND has_voted = false` (`VoteController.php:1807-1811`), then the slug marked voted by its primary key (`:1874-1878`), in one transaction. The full cast path is S9.

| Case (plan 5.1) | Forced order | Proven end state |
|---|---|---|
| two issuers, no row yet | both decide "no row", both wait before inserting, released together | **exactly one row**; one `issued`, the other loses on `voter_slugs_active_election_user_unique`, decides again and returns the **same** link (`reused`) |
| two issuers, expired row | A holds the row lock and waits; B is blocked on that row lock | **one replacement**; the old row soft-deleted and `expired`; A `issued`, B `reused` the same new link |
| cast first, then issuer | the cast has consumed participation and holds the code row; the issuer is blocked on it | the cast consumed (1 row); the issuer returns `already_voted`, **writes nothing**, never issues a link after the cast committed |
| issuer first, then cast | the issuer holds the code and slug rows; the cast is blocked on the code row | **O-13 (a) end state**: the issuer `issued` S2; the cast then consumed participation (one vote) and marked the retired S1 voted; S2 current and unvoted; a second cast with S2 refused (`consumed = 0`); a later issuance returns `already_voted` |

| # | File | Result |
|---|---|---|
| 01 | `01-red.out` | RED, before `afterLocks()` existed: 2 of 4 timed out at "holds the lock and waits"; the other 2 already passed |
| 02 | `02-green.out` | after adding the `afterLocks()` pause point: **4 tests, 56 assertions, exit 0** |
| 03 | `03-repeats-and-regression.out` | **3 further G6 runs, each 4 / 56, exit 0**; issuer 18 / 77, entry points 6 / 27 |
| 04 | `04-after-run.out` | no leftover G6 sessions, **no advisory locks**, no voter slugs or codes; migrations 225 (no migration run). **37 users, 37 role rows and 74 elections remain from the S4 entry-point runs** (created 21:47–21:49 and 22:31 UTC): `CandidacyFactory` creates its own user and that test does not clean those up. Harmless on the isolated database; a fixture gap in `VoterSlugIssuanceEntryPointsTest`, not part of G6 |

**Production change in this step:** `VoterSlugIssuer::afterLocks()`, a protected no-op called after the two row locks (D3). It changes nothing for production callers; only the worker's test subclass overrides it.

**What G6 does NOT prove:**
- the full cast path (S9, G7);
- behaviour under high load or with more than two processes;
- the demo flow.

The cases are the four from plan 5.1, each forced into a specific order. They are not exhaustive interleavings.
