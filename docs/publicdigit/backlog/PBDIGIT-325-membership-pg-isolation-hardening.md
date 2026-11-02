# PBDIGIT-325: Membership PostgreSQL isolation: hardening from the PBDIGIT-324 review (F3, F4, F5)

**Type:** Technical debt (test infrastructure, data safety) · **Epic:** `PBDIGIT-EPIC-02` · **Created:** 2026-11-02 · **Raised by:** PBDIGIT-324 acceptance (PO, 2026-11-02; `docs/publicdigit/reviews/2026-11-02-pbdigit324-independent-review-and-acceptance.md` §3)

| | |
|---|---|
| **Status** | `OPEN` · **not authorised** |
| **Classification** | reported by the independent review (VERIFIED there, by probe or mutant) |
| **Scope** | `tests/Support/TestDatabaseSafety.php`, `tests/Unit/Testing/MembershipPgScriptTest.php`, `tests/Unit/Testing/TestDatabaseSafetyTest.php`, `scripts/testing/membership-pg.sh` |

## Items

| ID | Finding | Proposed fix (TDD) |
|---|---|---|
| F3 | The reset allow-list matches `membership_pg_isolated` **by name, for every configuration**. A database with that name on any server could be reset by an unpinned run (e.g. `--no-configuration` with `.env`). This is the same class as the existing `pbdigit274_isolated_test` entry | allow the Membership name only when `MEMBERSHIP_PG_SYSTEM_ID` is set and matches the live `system_identifier`; apply the same idea to the PBDIGIT-274 entry only by a separate decision |
| F4 | Static-test blind spots. These mutants survived: an extra allow-list name; `env > "$EVIDENCE/env.txt"`; `-P` (publish all); `--network=host`; the trap moved after `podman run`; the runtime leak check removed; `applyEnvironment` removed from the bootstrap | pin "exactly three names"; forbid `env`/`printenv`/`set -x` and `-P`/`--network` in the script; assert the trap precedes `podman run`; assert the leak check exists; a bootstrap test that the success path writes the pinned variables |
| F5 | The script records the commit but not whether the tree was clean; a failing `git rev-parse` does not abort | record `git status --porcelain` in `run.txt` (or refuse a dirty tree by default); fail if `git rev-parse` fails |

**Also noted, no action required:**
- F6: SIGTERM orphans the phpunit child, which then fails closed;
- F8: record the image ID as well as the digest;
- F9: pass-through arguments.

## Not in scope

- The accepted residual risks of PBDIGIT-324: W2-2 provisional, `podman inspect` exposure, SIGKILL.
- PBDIGIT-264 Part 2.
